<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Inv Model
 * @property Inv app
 *
 */
class Inv_model extends CI_Model
{
	/**
	 * Get invoices
	 *
	 * @param    string	 	$from_date
	 * @param    string 	$to_date
	 * @param    int  		$supplier_id
	 *
	 * @return    mixed
	 */
	public function get_invoices($from_date=false, $to_date=false, $supplier_id=false)
	{
		$this->db->select('
						invoices.id,
						inv_number as num,
						DATE_FORMAT(due_date, "%d.%m.%y") as due_date,
						DATE_FORMAT(paid_date, "%d.%m.%y") as paid_date,
						is_pending,
						amount,
						currencies.code as currency,
						suppliers.name as supplier_name');
		$this->db->from('invoices');
		$this->db->join('currencies', 'currencies.id = invoices.currency_id');
		$this->db->join('suppliers', 'suppliers.id = invoices.supplier_id');

		if ($from_date) {
			$from_date = date_custom2mysql($from_date);
			$this->db->where('due_date >=', $from_date);
		}

		if ($to_date) {
			$to_date = date_custom2mysql($to_date);
			$this->db->where('due_date <=', $to_date);
		}

		if ($supplier_id) {
			$this->db->where('supplier_id', $supplier_id);
		}

		//echo $this->db->get_compiled_select(); exit;

		$query = $this->db->get();
        return $query->result();
	}

	/**
	 * Get earliest and latest dates, that have pending invoices
	 *
	 * @param    int  	$supplier_id
	 *
	 * @return    mixed
	 */
	public function get_minmax_pending_dates($supplier_id=false)
	{
				$this->db->select('
						DATE_FORMAT(MIN(due_date), "%d.%m.%y") as min_date,
						DATE_FORMAT(MAX(due_date), "%d.%m.%y") as max_date');
		$this->db->from('invoices');

		if ($supplier_id) {
			$this->db->where('supplier_id', $supplier_id);
		}

		$query = $this->db->get();
        return $query->row();
	}

	/**
	 * Get the sum amounts of pending invoices per supplier
	 *
	 * @param    string	 	$from_date
	 * @param    string 	$to_date
	 * @param    int  		$supplier_id
	 *
	 * @return    mixed
	 */
	public function get_pending_sums($from_date=false, $to_date=false, $supplier_id=false)
	{
		$this->db->select('
						is_pending,
						SUM(amount) as amount,
						currencies.code as currency,
						supplier_id,
						suppliers.name as supplier_name');

		$this->db->from('invoices');
		$this->db->join('currencies', 'currencies.id = invoices.currency_id');
		$this->db->join('suppliers', 'suppliers.id = invoices.supplier_id');
		$this->db->where('is_pending', 1);
		$this->db->where('paid_date IS NULL');

		if ($from_date) {
			$from_date = date_custom2mysql($from_date);
			$this->db->where('invoices.due_date >=', $from_date);
		}

		if ($to_date) {
			$to_date = date_custom2mysql($to_date);
			$this->db->where('invoices.due_date <=', $to_date);
		}

		if ($supplier_id) {
			$this->db->where('invoices.supplier_id', $supplier_id);
		}

		$this->db->group_by(array('currency_id', 'supplier_id'));
		$this->db->order_by('supplier_name, currency_id', 'asc');

		//echo $this->db->get_compiled_select(); exit;

		$query = $this->db->get();
        return $query->result();
	}

	/**
	 * Get suppliers
	 *
	 * @return    mixed
	 */
	public function get_suppliers()
	{
		$this->db->select('
						id,
						name');

		$this->db->from('suppliers');
		$this->db->order_by('name');

		$query = $this->db->get();
        return $query->result();
	}

	/**
	 * Get currencies
	 *
	 * @return    mixed
	 */
	public function get_currencies()
	{
		$this->db->select('
						  id,
						code');

		$this->db->from('currencies');
		$this->db->order_by('code');

		$query = $this->db->get();
        return $query->result();
	}

	/**
	 * Set invoice's pending status
	 *
	 * @param    int	 	$id
	 * @param    int(bool) 	$status
	 *
	 * @return    bool
	 */
	public function set_pending($id, $status)
	{
		$data = [
			'is_pending'          => (int)(bool)$status
		];

		$this->db->update('invoices', $data, ['id' => $id]);
		//echo $this->db->get_compiled_update(); exit;

		if ($this->db->affected_rows() === 1)
		{
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Set invoice's paid date status
	 *
	 * @param    int	 	$id
	 * @param    string 	$paid_date
	 *
	 * @return    bool
	 */
	public function set_paid($id, $paid_date)
	{
		$data = [
			'paid_date'          => date_custom2mysql($paid_date),
			'is_pending'          => 0
		];

		$this->db->update('invoices', $data, ['id' => $id]);
		//echo $this->db->get_compiled_update(); exit;

		if ($this->db->affected_rows() === 1)
		{
			return TRUE;
		} else {
			return FALSE;
		}
	}


	/**
	 * Create supplier
	 *
	 * @param    string 	$supplier_name
	 *
	 * @return    bool
	 */
	public function save_supplier($supplier_name)
	{
		$supplier_data = array(
			'name' => $supplier_name
		);
		$this->db->insert('suppliers', $supplier_data);
		//echo $this->db->last_query(); exit;

		$id = $this->db->insert_id('suppliers' . '_id_seq');
		return (isset($id)) ? $id : FALSE;
	}

	/**
	 * Create invoice
	 *
	 * @param    mixed 	$inv_data
	 *
	 * @return    bool
	 */
	public function save_invoice($inv_data)
	{
		$inv_data['due_date'] = date_custom2mysql($inv_data['due_date']);
		$this->db->insert('invoices', $inv_data);

		$id = $this->db->insert_id('invoices' . '_id_seq');
		return (isset($id)) ? $id : FALSE;
	}

	/**
	 * Create activity record
	 *
	 * @param    string 	$action
	 * @param    int 		$user_id
	 * @param    string		$additional
	 *
	 * @return    bool
	 */
	public function save_activity($action, $user_id, $additional)
	{
		$data = array(
			'action' => $action,
			'user_id' => $user_id,
			'additional' => $additional
		);

		$this->db->insert('activities', $data);
		//echo $this->db->last_query(); exit;

		$id = $this->db->insert_id('activities' . '_id_seq');
		return (isset($id)) ? $id : FALSE;
	}
}