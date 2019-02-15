<div class="page-header">
	<h1 class="page-title">
		<?php echo lang('edit_user_heading');?>
	</h1>
	<div class="page-subtitle">
		<?php echo lang('edit_user_subheading');?>
	</div>
</div>

	<?php if (! empty($message)): ?>
	<div class="alert alert-info" role="alert">
		<?php echo $message;?>
	</div>
	<?php endif; ?>

<div class="col-lg-12">
<?php echo form_open(uri_string(), 'class="card"');?>
	<div class="card-body">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="form-label" for="first_name"><?php echo lang('edit_user_fname_label'); ?><span class="form-required">*</span></label>
					<?php echo form_input($first_name, '', 'required class="form-control" placeholder="'. lang('edit_user_fname_label') .'"');?>
				</div>
			</div>

			<div class="col-sm-6 col-md-3">
				<div class="form-group">
					<label class="form-label" for="last_name"><?php echo lang('edit_user_lname_label'); ?><span class="form-required">*</span></label>
					<?php echo form_input($last_name, '', 'required class="form-control" placeholder="'. lang('edit_user_lname_label') .'"');?>
				</div>
			</div>


			<div class="col-md-3">
				<div class="form-group">
					<?php echo lang('edit_user_company_label', 'company', array('class' => "form-label"));?>
					<?php echo form_input($company, '', 'class="form-control" placeholder="'. lang('edit_user_company_label') .'"');?>
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<?php echo lang('edit_user_phone_label', 'phone', array('class' => "form-label"));?>
					<?php echo form_input($phone, '', 'class="form-control" placeholder="'. lang('edit_user_phone_label') .'"');?>
				</div>
			</div>
			<div class="col-sm-6 col-md-3">
				<div class="form-group">
					<label class="form-label" for="password"><?php echo lang('edit_user_password_label'); ?><span class="form-required">*</span></label>
					<?php echo form_input($password, '', ' class="form-control" placeholder="'. lang('edit_user_password_label') .'"');?>
				</div>
			</div>
			<div class="col-sm-6 col-md-3">
				<div class="form-group">
					<label class="form-label" for="password_confirm"><?php echo lang('edit_user_password_confirm_label'); ?><span class="form-required">*</span></label>
					<?php echo form_input($password_confirm, '', ' class="form-control" placeholder="'. lang('edit_user_password_confirm_label') .'"');?>
				</div>
			</div>


		<div class="col-md-6">
			<?php if ($this->ion_auth->is_admin()): ?>
			<div class="form-group">
				<div class="form-label">
					<?php echo lang('edit_user_groups_heading');?>
				</div>
				<div class="custom-controls-stacked">
					<?php foreach ($groups as $group):?>

					<?php
						$gID=$group['id'];
						$checked = null;
						$item = null;
						foreach($currentGroups as $grp) {
						    if ($gID == $grp->id) {
							  $checked= ' checked="checked"';
						    break;
						    }
						}
					  ?>
						<label class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
					  <span class="custom-control-label"><?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?></span>
				    </label>

						<?php endforeach?>
				</div>
			</div>
		      <?php endif ?>

      <?php echo form_hidden('id', $user->id);?>
      <?php echo form_hidden($csrf); ?>
		</div>
	</div>

	<div class="card-footer text-right">
		<?php echo form_submit('submit', lang('edit_user_submit_btn'), 'class="btn btn-primary"');?>
	</div>
	<?php echo form_close();?>
</div>
