<div class="page-header">
	<h1 class="page-title">
		<?php echo lang('create_user_heading');?>
	</h1>
	<div class="page-subtitle">
		<?php echo lang('create_user_subheading');?>
	</div>
</div>

<?php if (! empty($message)): ?>
<div class="alert alert-info" role="alert">
	<?php echo $message;?>
</div>
<?php endif; ?>


<div class="col-lg-12">
	<?php echo form_open("auth/create_user", 'class="card"');?>
	<div class="card-body">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="form-label" for="first_name"><?php echo lang('create_user_fname_label'); ?><span class="form-required">*</span></label>
					<?php echo form_input($first_name, '', 'required class="form-control" placeholder="'. lang('create_user_fname_label') .'"');?>
				</div>
			</div>

			<div class="col-sm-6 col-md-3">
				<div class="form-group">
					<label class="form-label" for="last_name"><?php echo lang('create_user_lname_label'); ?><span class="form-required">*</span></label>
					<?php echo form_input($last_name, '', 'required class="form-control" placeholder="'. lang('create_user_lname_label') .'"');?>
				</div>
			</div>

			<?php if($identity_column !== 'email'): ?>
			<div class="col-sm-6 col-md-4">
				<div class="form-group">
					<?php
					    echo lang('create_user_identity_label', 'identity', array('class' => "form-label"));
					    echo form_error('identity');
					    echo form_input($identity, '', 'class="form-control" placeholder="'. lang('create_user_identity_label') .'"');
					    ?>
				</div>
			</div>
			<?php endif;?>

			<div class="col-md-6">
				<div class="form-group">
					<?php echo lang('create_user_company_label', 'company', array('class' => "form-label"));?>
					<?php echo form_input($company, '', 'class="form-control" placeholder="'. lang('create_user_company_label') .'"');?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="form-label" for="email"><?php echo lang('create_user_email_label'); ?><span class="form-required">*</span></label>
					<?php echo form_input($email, '', ' required type="email" class="form-control" placeholder="'. lang('create_user_email_label') .'"');?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<?php echo lang('create_user_phone_label', 'phone', array('class' => "form-label"));?>
					<?php echo form_input($phone, '', 'class="form-control" placeholder="'. lang('create_user_phone_label') .'"');?>
				</div>
			</div>
			<div class="col-sm-6 col-md-3">
				<div class="form-group">
					<label class="form-label" for="password"><?php echo lang('create_user_password_label'); ?><span class="form-required">*</span></label>
					<?php echo form_input($password, '', ' required class="form-control" placeholder="'. lang('create_user_password_label') .'"');?>
				</div>
			</div>
			<div class="col-sm-6 col-md-3">
				<div class="form-group">
					<label class="form-label" for="password_confirm"><?php echo lang('create_user_password_confirm_label'); ?><span class="form-required">*</span></label>
					<?php echo form_input($password_confirm, '', ' required class="form-control" placeholder="'. lang('create_user_password_confirm_label') .'"');?>
				</div>
			</div>
		</div>
	</div>
	<div class="card-footer text-right">
		<?php echo form_submit('submit', lang('create_user_submit_btn'), 'class="btn btn-primary"');?>
	</div>
	<?php echo form_close();?>
</div>