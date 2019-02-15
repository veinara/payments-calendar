<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Inv for handling the Payments Calendar
 *
 */
class Inv extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();

		$this->load->library(['ion_auth', 'form_validation']);
		$this->load->helper(['url', 'language', 'date']);
		$this->load->model('inv_model');
		$this->load->language('inv');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}
	}

	/**
	 * Shows the payments calendar
	 *
	 * @return void
	 */
	public function index()
	{
		$date_format = $this->config->item('inv_date_format');
		$sorted_inv = array();

		// do we have filters applied
		$from_date = $this->input->get('inv-from-date');
		$to_date = $this->input->get('inv-to-date');

		if ((int)$this->input->get('inv-supplier') > 0) {
			$supplier_id = $this->input->get('inv-supplier');
		} else {
			$supplier_id = FALSE;
		}

		$invoices = $this->inv_model->get_invoices($from_date, $to_date, $supplier_id);
		$suppliers = $this->inv_model->get_suppliers();

		// get the from-to dates, that have unpaid invoices
		$minmax_dates = $this->inv_model->get_minmax_pending_dates();
		if ($minmax_dates) {
			$min_date = $minmax_dates->min_date;
			$max_date = $minmax_dates->max_date;
		}

		// group invoices by due_date
		foreach ($invoices as $key => $inv) {
			$sorted_inv[$inv->due_date][$key] = $inv;
		}
		ksort($sorted_inv, SORT_NUMERIC);

		$from_date = $from_date ? $from_date : $min_date;
		$to_date = $to_date ? $to_date : $max_date;

		//we need a daterange
		$dt_from = DateTime::createFromFormat($date_format, $from_date);
		$dt_to = DateTime::createFromFormat($date_format, $to_date);
		if ($dt_to) {
			$dt_to->modify( '+1 day' );
		}
		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($dt_from, $interval, $dt_to);

		//passing data to the view
		$this->data['period'] = $period;
		$this->data['from_date'] = $from_date;
		$this->data['to_date'] = $to_date;
		$this->data['sel_supplier_id'] = $supplier_id;
		$this->data['min_date'] = $min_date;
		$this->data['max_date'] = $max_date;
		$this->data['invoices'] = $sorted_inv;
		$this->data['suppliers'] = $suppliers;
		$this->data['summary'] = $this->get_summary($from_date, $to_date, $supplier_id);

		$this->_render_page('dashboard', 'inv' .DIRECTORY_SEPARATOR. 'index', $this->data);
	}

	/**
	 * Ajax function for setting the pending status
	 *
	 * @param integer   $id
	 * @param bool     	$status
	 *
	 * @return void
	 */
	public function pending($id, $status)
	{
		if (!$this->input->is_ajax_request()) {
			show_error(lang('inv_error_direct_access'), 403);
			exit;
		}

		if (!$this->ion_auth->in_group(array('admin', 'approvers'))) {
			show_error(lang('inv_error_not_allowed'), 401);
			exit;
		}

		// set pending status and record activity on success
		if ($this->inv_model->set_pending($id, $status)) {
			$this->_record_activity('set_pending', $id, $status);
		}

		echo $this->get_summary();
	}

	/**
	 * Ajax function for setting an invoice's date of payment
	 *
	 * @param integer     $id
	 *
	 */
	public function paid($id)
	{
		if (!$this->input->is_ajax_request()) {
			show_error(lang('inv_error_direct_access'), 403);
			exit;
		}

		if (!$this->ion_auth->in_group(array('admin', 'accountants'))) {
			show_error(lang('inv_error_not_allowed'), 401);
			exit;
		}

		$this->form_validation->set_rules('paid_date', $this->lang->line('inv_validation_paid_date_label'), 'required|valid_date');

		if ($this->form_validation->run() === TRUE) {

			$paid_date = $this->input->post('paid_date');
			if ($this->inv_model->set_paid($id, $paid_date)) {
				$this->_record_activity('set_paid', $id, $paid_date);

				exit(lang('inv_success_set_paid'));
			} else {
				exit(lang('inv_error_set_paid'));
			}
		}
		exit(validation_errors() ? validation_errors() : lang('inv_error_set_paid'));
	}

	/**
	 * Generates client's invoices summary
	 *
	 * @return string
	 */
	private function get_summary($from_date=false, $to_date=false, $supplier_id=false)
	{
		$invoices = $this->inv_model->get_pending_sums($from_date, $to_date, $supplier_id);
		$sorted_inv = array();

		if (is_array($invoices) && count($invoices) > 0) {
			foreach ($invoices as $inv) {
				$sorted_inv[$inv->supplier_id][] = $inv;
			}
		}
		$this->data['supplier_sums'] = $sorted_inv;

		return $this->_render_page('ajax', 'inv' .DIRECTORY_SEPARATOR. 'summary', $this->data, TRUE);
	}

	/**
	 * Create invoice
	 *
	 */
	public function create_inv()
	{
		if (!$this->ion_auth->in_group(array('admin', 'accountants'))) {
			exit('You are not allowed to do that.');
		}

		$this->data['suppliers'] = $this->inv_model->get_suppliers();
		$this->data['currencies'] = $this->inv_model->get_currencies();

		// validate form input
		$this->form_validation->set_rules('supplier_name', $this->lang->line('inv_validation_supplier_name_label'), 'trim');
		$this->form_validation->set_rules('supplier_id', $this->lang->line('inv_validation_supplier_label'), 'integer');
		$this->form_validation->set_rules('inv_number', $this->lang->line('inv_validation_inv_number_label'), 'trim|required|numeric');
		$this->form_validation->set_rules('due_date', $this->lang->line('inv_validation_due_date_label'), 'required|valid_date');
		$this->form_validation->set_rules('amount', $this->lang->line('inv_validation_amount_label'), 'trim|required|regex_match[/^\d+\.\d\d$/]');
		$this->form_validation->set_rules('currency_id', $this->lang->line('inv_validation_currency_label'), 'integer');


		if ($this->form_validation->run() === TRUE)
		{
			$supplier_id = $this->input->post('supplier_id');
			$supplier_name = $this->input->post('supplier_name');

			$inv_data = array(
				'inv_number' 	=> $this->input->post('inv_number'),
				'due_date' 		=> $this->input->post('due_date'),
				'amount' 		=> $this->input->post('amount'),
				'currency_id' 	=> $this->input->post('currency_id')
			);

			if (!$supplier_id > 0) {
				if (empty($supplier_name)) {
					$this->session->set_flashdata('message', lang('inv_validation_supplier_name_empty'));
				} else {
					$supplier_id = $this->inv_model->save_supplier($supplier_name);

					if (!$supplier_id > 0) {
						$this->session->set_flashdata('message', lang('inv_error_save_supplier'));
					} else {
						$this->_record_activity('created_supplier', $supplier_id);
					}
				}
			}

			//if we have a valid supplier id at last..
			if ($supplier_id > 0) {
				$inv_data['supplier_id'] = $supplier_id;

				$inv_id = $this->inv_model->save_invoice($inv_data);
				if ($inv_id > 0) {
					$this->_record_activity('created_invoice', $inv_id);
					$this->session->set_flashdata('message', lang('inv_success_save_invoice'));
					redirect("inv/create_inv", 'refresh');
				} else {
					$this->session->set_flashdata('message', lang('inv_error_save_invoice'));
				}
			}
		}
			// display the create invoice form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() :$this->session->flashdata('message'));

			$this->data['supplier_name'] = [
				'name' => 'supplier_name',
				'id' => 'supplier_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('supplier_name'),
			];
			$this->data['supplier_id'] = isset($supplier_id) ? $supplier_id : FALSE;

			$this->data['inv_number'] = [
				'name' => 'inv_number',
				'id' => 'inv_number',
				'type' => 'text',
				'value' => $this->form_validation->set_value('inv_number'),
			];
			$this->data['due_date'] = [
				'name' => 'due_date',
				'id' => 'due_date',
				'type' => 'text',
				'value' => $this->form_validation->set_value('due_date', date(config_item('inv_date_format'), time())),
			];
			$this->data['amount'] = [
				'name' => 'amount',
				'id' => 'amount',
				'type' => 'text',
				'value' => $this->form_validation->set_value('amount'),
			];
			$this->data['currency_id'] = isset($currency_id) ? $currency_id : FALSE;

		$this->_render_page('dashboard', 'inv' .DIRECTORY_SEPARATOR. 'create_invoice', $this->data);
	}

	/**
	 * Add records in db for some user's activities
	 * @param string     $action
	 *
	 * @return void
	 */
	private function _record_activity($action)
	{
		$args = func_get_args();
		array_shift($args);
		$additional = implode(";", $args);

		$user_id = $this->ion_auth->user()->row()->id;
		$this->inv_model->save_activity($action, $user_id, $additional);
	}

	/**
	 * @param string     $layout
	 * @param string     $view
	 * @param array|null $data
	 * @param bool       $returnhtml
	 *
	 * @return mixed
	 */
	private function _render_page($layout, $view, $data = NULL, $returnhtml = FALSE)
	{
		$viewdata = (empty($data)) ? $this->data : $data;
		$this->template->set('user', $this->ion_auth->user()->row());

		$this->template->set('app_name', $this->config->item('app_name'));
		$this->template->set('app_version', $this->config->item('app_version'));

		header("Access-Control-Allow-Origin: *");
		$layout_html = $this->template->load($layout. '_layout', 'contents' , $view, $viewdata, $returnhtml);

		if ($returnhtml)
		{
			return $layout_html;
		}
	}
}
