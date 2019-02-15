<div class="page-header">
	<h1 class="page-title">
		<?php echo lang('create_group_heading');?>
	</h1>
	<div class="page-subtitle">
		<?php echo lang('create_group_subheading');?>
	</div>
</div>

<?php if (! empty($message)): ?>
<div class="alert alert-info" role="alert">
	<?php echo $message;?>
</div>
<?php endif; ?>


<div class="col-lg-12">

	<?php echo form_open("auth/create_group", 'class="card"');?>
	<div class="card-body">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="form-label" for="group_name"><?php echo lang('create_group_name_label'); ?><span class="form-required">*</span></label>
					<?php echo form_input($group_name, '', 'required class="form-control" placeholder="'. lang('create_group_name_label') .'"');?>
				</div>
			</div>

			<div class="col-md-9">
				<div class="form-group">
					<?php echo lang('create_group_desc_label', 'description', array('class' => "form-label"));?>
					<?php echo form_input($description, '', 'class="form-control" placeholder="'. lang('create_group_desc_label') .'"');?>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer text-right">
		<?php echo form_submit('submit', lang('create_group_submit_btn'), 'class="btn btn-primary"');?>
	</div>
	<?php echo form_close();?>
</div>