<?php echo form_open("auth/login", array("class" => "card"));?>
<div class="card-body p-6">
	<div class="card-title">
		<?php echo lang('login_heading');?>
	</div>

	<?php //echo lang('login_subheading');?>
	<?php if (! empty($message)): ?>
	<div class="alert alert-info" role="alert">
		<?php echo $message;?>
	</div>
	<?php endif; ?>

	<div class="form-group">
		<?php echo lang('login_identity_label', 'identity', array('class' => 'form-label'));?>
		<?php echo form_input($identity, '', 'class="form-control" aria-describedby="emailHelp" placeholder="'. lang('login_identity_label') .'"');?>
	</div>

	<div class="form-group">
		<label class="form-label">
            <?php echo lang('login_password_label', 'password');?>
			<a href="forgot_password" tabIndex="-1" class="float-right small"><?php echo lang('login_forgot_password');?></a>
        </label>
		<?php echo form_input($password, '', 'class="form-control" placeholder="'. lang('login_password_label') .'"');?>
	</div>

	<div class="form-group">
		<label class="custom-control custom-checkbox">
			<?php echo form_checkbox('remember', '1', FALSE, 'id="remember" class="custom-control-input"');?>
			<span class="custom-control-label"><?php echo lang('login_remember_label', 'remember');?></span>
        </label>
	</div>

	<div class="form-footer">
		<?php echo form_submit('submit', lang('login_submit_btn'), 'class="btn btn-primary btn-block"');?>
	</div>
</div>
<?php echo form_close();?>