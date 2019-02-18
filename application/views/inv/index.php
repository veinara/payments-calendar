<div class="page-header">
	<h1 class="page-title">
		Calendar
	</h1>
	<div class="page-subtitle">
		<?php echo lang('index_subheading');?>

		<?php if($this->ion_auth->in_group(array('admin', 'accountants'))):?>
		<a href="<?php echo site_url('inv/create_inv');?>" class="btn btn-primary" style="color: #fff">
			<?php echo lang('create_inv_btn');?>
		</a>
		<?php endif;?>
	</div>
	<div class="page-options">

		<?php echo form_open('inv', 'class="form-group" method="get"');?>

		<!--<div class="form-group">-->
		<div class="row gutters-xs">

			<div class="col">
				<label class="form-label">From date</label>
				<input type="text" name="inv-from-date" style="width: 90px" class="form-control" data-mask="00.00.00" data-mask-clearifnotmatch="true" placeholder="dd.mm.yy" value="<?php echo $from_date;?>" />
			</div>

			<div class="col">
				<label class="form-label">To date</label>
				<input type="text" name="inv-to-date" style="width: 90px" class="form-control" data-mask="00.00.00" data-mask-clearifnotmatch="true" placeholder="dd.mm.yy" value="<?php echo $to_date;?>" />
			</div>

			<div class="col">
				<label class="form-label"><?php echo lang('inv_supplier');?></label>
				<select class="form-control custom-select w-auto" name="inv-supplier">
						<option value="0">All</option>
						<?php foreach($suppliers as $supplier):?>
						<option value="<?php echo $supplier->id;?>" <?php if($sel_supplier_id == $supplier->id) echo 'selected="selected"'?>><?php echo htmlspecialchars($supplier->name,ENT_QUOTES,'UTF-8');?></option>
						<?php endforeach;?>
					</select>
			</div>

			<div>
				<label class="form-label">&nbsp;</label>
				<button type="submit" class="btn btn-primary">Show</button>
			</div>

		</div>
		<?php echo form_close();?>

	</div>
</div>


<div class="row row-cards">

	<div class="col-lg-4">
		<div class="card" style="position: -webkit-sticky; position: sticky; top: 10px;">
			<div class="card-status bg-orange"></div>
			<div class="card-body" id="inv-summary">
				<div class="table-responsive">

					<?php echo $summary;?>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-8">
		<div class="card">
			<!--<div class="card-body">-->
			<div class="table-responsive">
				<table class="table  card-table table-vcenter" id="inv-list">
					<tbody>
						<?php
							foreach ($period as $dt):
								$curr_date = $dt->format('d.m.y'); //mysql date format
								$rowspan = (!isset($invoices[$curr_date]) ? 1 : count($invoices[$curr_date])+1);
								$is_today = date('d.m.y', time()) == $dt->format('d.m.y');

							?>
							<tr class="<?php if ($is_today) echo 'today';?>">
								<th class="text-center">
									<?php if ($is_today) echo 'today';?>
									<?php //echo $dt->format('l'); ?>
									<div style="font-size: <?php echo ($is_today ? '3em; color: #000' : '2em');?>; line-height: 1em;">
										<?php echo $dt->format('d'); ?>
									</div>
									<?php echo $dt->format('m.Y'); ?>
								</th>


								<?php if($rowspan == 1) continue; ?>
								<td style="padding: 0">
									<?php foreach ($invoices[$curr_date] as $key => $inv):

												$is_paid = !is_null($inv->paid_date);
												$is_pending = $inv->is_pending == 1;
								?>
									<table class="table table-inv-info" style="margin: 0">

										<tr class="<?php if ($is_today) echo ' today'; if($inv->is_pending == 1) echo ' selected';  ?>">

											<?php if ($this->ion_auth->in_group(array('admin', 'approvers'))): ?>
											<td style="width: 10%">
												<?php if (is_null($inv->paid_date)): ?>
												<label class="custom-control custom-checkbox">
													<?php echo form_checkbox('pending[]', $inv->id, $is_pending, 'class="custom-control-input"');?>
													<div class="custom-control-label"></div>
												</label>
												<?php endif; ?>
											</td>
											<?php endif;?>


											<td style="width: 45%">
												<?php echo htmlspecialchars($inv->supplier_name,ENT_QUOTES,'UTF-8');?>
											</td>


											<td style="width: 15%">
												<?php if($is_paid):
												echo '<span class="status-icon bg-success"></span>';
												echo '<a href="javascript: void(0)"  data-toggle="tooltip" data-placement="top" title="'. lang('paid_on').' '.$inv->paid_date .'">'. $inv->num .'</a>';
											else:
													echo '<span class="status-icon bg-'.($is_pending ? 'warning' : 'danger').'"></span>';
													echo $inv->num;
											 endif;?>
											</td>


											<td style="width: 20%">
												<?php echo $inv->amount .' '. $inv->currency;?>
											</td>


											<?php if ($this->ion_auth->in_group(array('admin', 'accountants'))):?>
											<td style="width: 10%">
												<?php echo anchor("inv/paid/".$inv->id, lang('set_paid_date'), ($is_paid ? 'style="visibility: hidden"' : '') .'class="btn btn-secondary btn-sm inv-btn-pay'. (!$is_pending ? ' disabled' : '') .'"');
										endif; ?>
											</td>


										</tr>
									</table>
									<?php endforeach;?>
								</td>
							</tr>
							<?php endforeach;?>
							<tbody>
				</table>
			</div>
		</div>
		<!--</div>-->
	</div>

</div>


<?php if ($this->ion_auth->in_group(array('admin', 'accountants'))): ?>
<div id="inv-pay-modal" class="modal inv-modal-pay" tabindex="-1" role="dialog">
	<div class="modal-dialog  modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<?php echo lang('set_paid_date');?>
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <!--<span aria-hidden="true">&times;</span>-->
        </button>
			</div>
			<?php echo form_open("inv/pay");?>
			<div class="modal-body">

				<div class="form-group">
					<label class="form-label" for="paid_date"><?php echo lang('create_inv_paid_date_label'); ?></label>
					<input name="paid_date" type="text" value="<?php echo date(config_item('inv_date_format'), time());?>" data-mask="00.00.00" data-mask-clearifnotmatch="true" class="form-control" placeholder="dd.mm.yy" />
				</div>

			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Save</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			</div>
			<?php echo form_close();?>
		</div>
	</div>
</div>
<?php endif; ?>

<style>
	.table-inv-info tr {
		background: #fff;
	}

	tr.today {
		background: #f8f8f8;
	}

	tr.selected {
		background: #C8D9F1;
	}
</style>
<script>
	require(['input-mask']);

	requirejs(['jquery'], function($) {

		<?php if ($this->ion_auth->in_group(array('admin', 'accountants'))): ?>
		$("#inv-pay-modal form").submit(function(e) {
			var form = $(this);
			var url = form.attr('action');

			$.ajax({
				type: "POST",
				url: url,
				data: form.serialize(), // serializes the form's elements.
				success: function(data) {
					//alert(data); // show response from the php script.
					window.location.reload(true);
				}
			});

			e.preventDefault(); // avoid to execute the actual submit of the form.
		});

		$("a.inv-btn-pay").click(function(e) {
			e.preventDefault();

			$("#inv-pay-modal").modal('show');
			$("#inv-pay-modal input").focus();
			$("#inv-pay-modal form").attr("action", $(this).attr("href"));
		})
		<?php endif;?>

		<?php if ($this->ion_auth->in_group(array('admin', 'approvers'))): ?>
		$("#inv-list tr input[type='checkbox']").click(function() {
			$(this).closest('tr').toggleClass('selected');

			let inv_id = $(this).val();
			let inv_is_pending = $(this).is(':checked') | 0;

			$.ajax({
					url: "<?php echo base_url();?>inv/pending/" + inv_id + "/" + inv_is_pending
				})
				.done(function(data) {
					$("#inv-summary").html(data);
				});
		});
		<?php endif;?>
	});
</script>