<?php use_helper('I18N', 'Date')?>
<style type="text/css">
.control-label {
	font-weight: bold;
}

.mt-1 {
	margin-top: 2.5rem;
}
</style>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">
		<strong><?php echo __('User information: %%name%%', array('%%name%%' => $user_detail->getUserName())) ?></strong>
	</h4>
</div>

<div class="modal-body">
	<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2"></div>
	<div class=" col-lg-10 col-md-10 col-sm-12 col-xs-12 padding-top-10">

		<ul class="nav nav-tabs tabs-pull-right">
			<li class="active"><a data-toggle="tab" href="#user_information"><?php echo __('User information') ?></a></li>
			<li><a data-toggle="tab" href="#user_history"><?php echo __('User history') ?></a></li>
			<li><a data-toggle="tab" href="#user_group"><?php echo __('User group') ?></a></li>
			<li><a data-toggle="tab" href="#user_permission"><?php echo __('User permission') ?></a></li>
		</ul>


		<div class="tab-content mt-1">

			<div id="user_information" class="tab-pane fade in active">
				<div class="row">

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('Username') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
									<?php echo $user_detail->getUserName()?>
								</p>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('Full name') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
									<?php echo $user_detail->getFullName()?>
								</p>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('Ward') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
									<?php echo $user_detail->getWard()?>
								</p>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('District') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
									<?php echo $user_detail->getDistrict()?>
								</p>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('Province') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
									<?php echo $user_detail->getProvince()?>
								</p>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('School name') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
									<?php echo $user_detail->getSchoolName()?>
								</p>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('User type') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
									<?php echo $user_detail->getUserType() == 'T' ? '<code>'.__('User member').'</code>': __('User type')?>
								</p>
						</div>
					</div>

					<div class="form-group">
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<label class="control-label"><?php echo __('Is activated') ?></label>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
							<p>
									<?php

switch ($user_detail->getIsActive ()) {
										case 1 :
											echo '<i class="fa fa-check txt-color-green"></i> ' . __ ( 'Active' );
											break;
										case 2 :
											echo '<i class="fa fa-lock txt-color-red"></i> ' . __ ( 'Lock' );
											break;
										default :
											echo '<i class="fa fa-times"></i> ' . __ ( 'Not active' );
											break;
									}
									?>
								</p>
						</div>
					</div>
				</div>
			</div>

			<div id="user_history" class="tab-pane fade">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label class="control-label"><?php echo __('Last login') ?></label>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
								<p>
									<?php echo format_date($user_detail->getLastLogin(), 'hh:mm - dd/MM/yyyy') ?>
								</p>
							</div>
						</div>
					</div>

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label class="control-label"><?php echo __('Created at') ?></label>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
								<p>
									<?php echo format_date($user_detail->getCreatedAt(), 'hh:mm - dd/MM/yyyy') ?>
								</p>
							</div>
						</div>
					</div>

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label class="control-label"><?php echo __('Created by') ?></label>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
								<p>
									<?php echo $user_detail->getCreatedBy() ?>
								</p>
							</div>
						</div>
					</div>

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label class="control-label"><?php echo __('Updated at') ?></label>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
								<p>
									<?php echo format_date($user_detail->getUpdatedAt(), 'hh:mm - dd/MM/yyyy') ?>
								</p>
							</div>
						</div>
					</div>

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
								<label class="control-label"><?php echo __('Updated by') ?></label>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
								<p>
									<?php echo $user_detail->getUpdatedBy() ?>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="user_permission" class="tab-pane fade custom-scroll"
				style="height: 200px; overflow-y: scroll;">
				<ul>
					<?php
					foreach ( $_permissions as $key => $_permission ) {
						echo '<li><p><b>' . $_permission->getAppName () . ':</b><p></li>';
						echo $_permission->getPermissionName ();
					}
					?>
				</ul>
			</div>

			<div id="user_group" class="tab-pane fade">
				<ul>
					<?php
					$group_id = array ();
					foreach ( $groups as $key => $group ) {
						array_push ( $group_id, $group->getGroupId () );
					}
					$permissions = Doctrine::getTable ( 'sfGuardPermission' )->getPermissionByGroupId ( $group_id );
					$_permissionss = array ();
					foreach ( $permissions as $key => $permission ) {
						$_data ['group_id'] = $permission->getGroupId ();
						$_data ['app_name'] = $permission->getAppName ();
						$_data ['permission_name'] = $permission->getPermissionName ();
						array_push ( $_permissionss, $_data );
					}

					foreach ( $groups as $key => $group ) {
						?>
							<li><p>
							<b><?php echo $group->getGroupName()?>:&nbsp;</b>
						</p>
						<ul><?php
						foreach ( $_permissionss as $_permission ) {
							if ($group->getGroupId () == $_permission ['group_id']) {
								echo '<li><p><b>' . $_permission ['app_name'] . ':</b><p></li>';
								echo $_permission ['permission_name'];
							}
						}
						?>
					</ul></li>
					<?php } ?>		
				</ul>
			</div>

		</div>
	</div>

	<div class="clearfix"></div>
</div>

<div class="modal-footer">

<?php

if ($sf_user->hasCredential ( 'PS_SYSTEM_USER_EDIT' )) {
	echo link_to ( '<i class="fa-fw fa fa-pencil"></i> ' . __ ( 'Edit' ), 'sf_guard_user_edit', $user_detail, array (
			'class' => 'btn btn-default btn-success btn-sm btn-psadmin' ) );
}
?>

	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
</div>


