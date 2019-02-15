<div class="page-header">
	<h1 class="page-title">
		<?php echo lang('create_inv_heading');?>
	</h1>
	<div class="page-subtitle">
		<?php echo lang('create_inv_subheading');?>
	</div>
</div>

<?php if (! empty($message)): ?>
<div class="alert alert-info" role="alert">
	<?php echo $message;?>
</div>
<?php endif; ?>


<div class="col-lg-12">
	<?php echo form_open("inv/create_inv", 'class="card"');?>
	<div class="card-body">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="form-label" for="supplier_name"><?php echo lang('create_inv_supplier_label'); ?><span class="form-required">*</span></label>
					<select class="form-control custom-select w-auto" name="supplier_id" style="max-width: 250px">
						<option value="0">----------------</option>
						<?php foreach($suppliers as $supplier):?>
						<option value="<?php echo $supplier->id;?>" <?php if($supplier_id == $supplier->id) echo 'selected="selected"'?>><?php echo htmlspecialchars($supplier->name,ENT_QUOTES,'UTF-8');?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label class="form-label">&nbsp;</label>
					<?php echo form_input($supplier_name, '', 'class="form-control" placeholder="'. lang('create_inv_supplier_label') .'"');?>
				</div>
			</div>

			<div class="col-md-4"></div>

			<div class="col-sm-6 col-md-3">
				<div class="form-group">
					<label class="form-label" for="inv_number"><?php echo lang('create_inv_inv_number_label'); ?><span class="form-required">*</span></label>
					<?php echo form_input($inv_number, '', ' class="form-control" placeholder="'. lang('create_inv_inv_number_label') .'"');?>
				</div>
			</div>

			<div class="col-sm-6 col-md-3">
				<div class="form-group">
					<label class="form-label" for="due_date"><?php echo lang('create_inv_due_date_label'); ?><span class="form-required">*</span></label>
					<?php echo form_input($due_date, '', ' data-mask="00.00.00" data-mask-clearifnotmatch="true" class="form-control" placeholder="dd.mm.yy"');?>
				</div>
			</div>

			<div class="col-md-6"></div>

			<div class="col-md-2">
				<div class="form-group">
					<label class="form-label" for="amount"><?php echo lang('create_inv_amount_label'); ?><span class="form-required">*</span></label>
					<?php echo form_input($amount, '', '   data-mask="099999999999.00" class="form-control" placeholder="0000000.00"');?>
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group">
					<label class="form-label">&nbsp;</label>
					<select class="form-control custom-select w-auto" name="currency_id">
						<?php foreach($currencies as $currency):?>
						<option value="<?php echo $currency->id;?>" <?php if($currency_id == $currency->id) echo 'selected="selected"'?>><?php echo $currency->code;?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>

		</div>
	</div>
	<div class="card-footer text-right">
		<?php echo form_submit('submit', lang('create_inv_submit_btn'), 'class="btn btn-primary"');?>
	</div>
	<?php echo form_close();?>
</div>
<script>
	require(['input-mask']);
</script>