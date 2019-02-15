<div class="page-header">
	<h1 class="page-title">
		<?php echo lang('index_heading');?>
	</h1>
	<div class="page-subtitle">
		<?php echo lang('index_subheading');?>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<?php if (! empty($message)): ?>
				<div class="alert alert-info" role="alert">
					<?php echo $message;?>
				  </div>
				<?php endif; ?>

				<div class="table-responsive">
					<table class="table card-table table-vcenter text-nowrap">
						<thead>
							<tr>
								<th>
									<?php echo lang('index_fname_th');?>
								</th>
								<th>
									<?php echo lang('index_lname_th');?>
								</th>
								<th>
									<?php echo lang('index_email_th');?>
								</th>
								<th>
									<?php echo lang('index_groups_th');?>
								</th>
								<th>
									<?php echo lang('index_status_th');?>
								</th>
								<th>
									<?php echo lang('index_action_th');?>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($users as $user):?>
							<tr>
								<td>
									<?php echo htmlspecialchars($user->first_name,ENT_QUOTES,'UTF-8');?>
								</td>
								<td>
									<?php echo htmlspecialchars($user->last_name,ENT_QUOTES,'UTF-8');?>
								</td>
								<td>
									<?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?>
								</td>
								<td>
									<?php foreach ($user->groups as $group):?>
									<?php echo anchor("auth/edit_group/".$group->id, htmlspecialchars($group->name,ENT_QUOTES,'UTF-8')) ;?><br />
									<?php endforeach?>
								</td>
								<td>
									<?php echo ($user->active) ?
				'<span class="status-icon bg-success"></span>'.anchor("auth/deactivate/".$user->id, lang('index_active_link')) :
				'<span class="status-icon bg-danger"></span>'. anchor("auth/activate/". $user->id, lang('index_inactive_link'));?></td>
								<td>
									<?php echo anchor("auth/edit_user/".$user->id, 'Edit', 'class="btn btn-secondary btn-sm"') ;?>
								</td>
							</tr>
							<?php endforeach;?>
							<tbody>
					</table>

					<div class="card-footer text-right">
						<?php echo anchor('auth/create_user', lang('index_create_user_link'), 'class="btn btn-primary"')?>
						<?php echo anchor('auth/create_group', lang('index_create_group_link'), 'class="btn btn-primary"')?>
					</div>

				</div>
			</div>
		</div>
	</div>