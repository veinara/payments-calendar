<?php echo form_open("auth/forgot_password", array("class" => "card"));?>
<div class="card-body p-6">

	<div class="card-title">
		<?php echo lang('forgot_password_heading');?>
	</div>

	<p>
		<?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?>
	</p>

	<?php if (! empty($message)): ?>
	<div class="alert alert-info" role="alert">
		<?php echo $message;?>
	</div>
	<?php endif; ?>


	<div class="form-group">
		<label for="identity"><?php echo (($type=='email') ? sprintf(lang('forgot_password_email_label'), $identity_label) : sprintf(lang('forgot_password_identity_label'), $identity_label));?></label> <br />
		<?php echo form_input($identity);?>
	</div>

	<div class="form-footer">
		<?php echo form_submit('submit', lang('forgot_password_submit_btn'), 'class="btn btn-primary btn-block"');?>
	</div>


</div>
<?php echo form_close();?>