<?php use_helper('I18N', 'Date')?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel"><?php echo __('Member infomation') ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-sm-12 col-md-12 col-lg-2">
			<div style="width: 100%; text-align: center; margin: 0 auto;">
				<?php
				if ($ps_member->getImage () != '') :
					$path_file = '/media-web/root/' . PreSchool::MEDIA_TYPE_TEACHER . '/' . $ps_member->getSchoolCode () . '/' . $ps_member->getYearData () . '/' . $ps_member->getImage ();
					echo '<img class="img-circle img-responsive" style="margin: 0 auto; max-width: 150px; max-height: 150px;" src="' . $path_file . '">';
					endif;

				?>
			</div>
		</div>
		<div class="col-sm-12 col-md-12 col-lg-10">
			<div class="padding-10">
				<ul class="nav nav-tabs tabs-pull-right">
					<li class="active"><a href="#a1" data-toggle="tab"
						aria-expanded="true"><?php echo __('Infomation detail') ?></a></li>
					<li class=""><a href="#a2" data-toggle="tab" aria-expanded="false"><?php echo __('Groups list')?></a></li>
					<li class=""><a href="#a3" data-toggle="tab" aria-expanded="false"><?php echo __('Permissions specific')?></a></li>
					<li class=""><a href="#a4" data-toggle="tab" aria-expanded="false"><?php echo __('Member Department')?></a></li>
					<li class=""><a href="#a5" data-toggle="tab" aria-expanded="false"><?php echo __('Member Salary')?></a></li>
					<li class="pull-left"><span class="margin-top-10 display-inline">&nbsp;<?php echo $ps_member->getFirstName().' '.$ps_member->getLastName() ?></span></li>
				</ul>
				<div class="tab-content padding-top-10">


					<div class="custom-scroll tab-pane fade" id="a5"
						style="height: 290px; overflow-y: scroll;">
						<div class="col-sm-12 col-md-12 col-lg-12">
							<?php foreach ( $member_salary as $key => $member_salary ):?>
								<div class="row">
								<span class="timeline-seperator text-left"> <span><?php echo __('Salary information') ?></span>
								</span><br>
								<div class="col-md-5">
									<b><?php echo __('Customer')?></b>:
								</div>
								<div class="col-md-7"> <?php echo $member_salary->getSchoolName() ?></div>
								<div class="col-md-5">
									<b><?php echo __('Days Working')?></b>:
								</div>
								<div class="col-md-7"><?php echo $member_salary->getDaysWorking() ?>	</div>
								<div class="col-md-5">
									<b><?php echo __('Basic Salary')?></b>:
								</div>
								<div class="col-md-7"><?php echo number_format($member_salary->getBasicSalary(), 2, '.',' ')?></div>
								<div class="col-md-5">
									<b><?php echo __('Start At')?></b>:
								</div>
								<div class="col-md-7"><?php echo (false !== strtotime($member_salary->getStartAt())) ? format_date($member_salary->getStartAt(), "dd-MM-yyyy") : '';?></div>
								<div class="col-md-5">
									<b><?php echo __('Stop At')?></b>:
								</div>
								<div class="col-md-7"><?php echo (false !== strtotime($member_salary->getStopAt())) ? format_date($member_salary->getStopAt(), "dd-MM-yyyy") : '';?></div>
								<div class="col-md-5">
									<b><?php echo __('Updated By')?></b>:
								</div>
								<div class="col-md-7">
										<?php echo $member_salary->getUpdatedBy()?>
										<?php echo ' - '?>
                						<?php echo (false !== strtotime($member_salary->getUpdatedAt())) ? format_date($member_salary->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '';?>
									</div>
							</div>
							<?php endforeach;?>
						</div>
					</div>
					<div class="custom-scroll tab-pane fade" id="a4"
						style="height: 290px; overflow-y: scroll;">
						<div class="col-sm-12 col-md-12 col-lg-12">
							<?php foreach ( $member_department as $key  => $member_department):?>
								<div class="row">
								<span class="timeline-seperator text-left"> <span><?php echo __('Department information') ?></span>
								</span><br>
								<div class="col-md-4">
									<b><?php echo __('Department')?></b>:
								</div>
								<div class="col-md-8"> <?php echo $member_department->getDTitle() ?></div>
								<div class="col-md-4">
									<b><?php echo __('Function')?></b>:
								</div>
								<div class="col-md-8"><?php echo $member_department->getFcTitle() ?></div>
								<div class="col-md-4">
									<b><?php echo __('Note')?></b>:
								</div>
								<div class="col-md-8"><?php echo $member_department->getNote() ? $member_department->getNote() : '<br>' ?>	</div>

								<div class="col-md-4">
									<b><?php echo __('Is current')?></b>:
								</div>
								<div class="col-md-8">
										<?php
								if ($member_department->getIsCurrent () == PreSchool::ACTIVE)
									echo __ ( 'Current' );
								else
									echo __ ( 'Not current' );
								?>
									</div>
								<div class="col-md-4">
									<b><?php echo __('Working Time')?></b>:
								</div>
								<div class="col-md-8">
										<?php echo (false !== strtotime($member_department->getStartAt())) ? format_date($member_department->getStartAt(), "dd/MM/yyyy") : '';?>
                						<?php echo ' - '?>
                						<?php echo (false !== strtotime($member_department->getStopAt())) ? format_date($member_department->getStopAt(), "dd/MM/yyyy") : '';?>
									</div>
								<div class="col-md-4">
									<b><?php echo __('Updated By')?></b>:
								</div>
								<div class="col-md-8">
										<?php echo $member_department->getUpdatedBy()?>
										<?php echo ' - '?>
                						<?php echo (false !== strtotime($member_department->getUpdatedAt())) ? format_date($member_department->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '';?>
									</div>
							</div>
							<?php endforeach;?>
						</div>
					</div>



					<div class="custom-scroll tab-pane fade" id="a3"
						style="height: 290px; overflow-y: scroll;">
						<ul><?php
						foreach ( $_permissions as $key => $_permission ) {
							echo '<li><p><b>' . $_permission->getAppName () . ':</b><p></li>';
							echo $_permission->getPermissionName ();
						}
						?>
    						</ul>
					</div>
					<div class="custom-scroll tab-pane fade" id="a2"
						style="height: 290px; overflow-y: scroll;">
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
					<div class="tab-pane fade in active" id="a1">
						<div class="col-sm-12 col-md-12 col-lg-12">
							<div class="col-md-2"
								style="overflow: hidden; white-space: nowrap;">
								<b><?php echo __('Member code')?></b>:
							</div>
							<div class="col-md-10"> <?php echo ($ps_member->getMemberCode()) ? $ps_member->getMemberCode() : '-' ?></div>
							<div class="col-md-2">
								<b><?php echo __('Ps customer')?></b>:
							</div>
							<div class="col-md-10"> <?php echo ($ps_member->getSchoolName()) ? $ps_member->getSchoolName() : '-' ?></div>
							<div class="col-md-2">
								<b><?php echo __('Address')?></b>:
							</div>
							<div class="col-md-10"><?php echo ($ps_member->getSchoolAddress()) ? $ps_member->getSchoolAddress() : '-' ?></div>
							<div class="col-md-2">
								<b><?php echo __('Ward')?></b>:
							</div>
							<div class="col-md-10"><?php echo ($ps_member->getWardName()) ? $ps_member->getWardName() : '-' ?></div>
							<div class="col-md-2">
								<b><?php echo __('District')?></b>:
							</div>
							<div class="col-md-10"><?php echo ($ps_member->getDistrictName()) ? $ps_member->getDistrictName() : '-'?></div>
							<div class="col-md-2">
								<b><?php echo __('Province')?></b>:
							</div>
							<div class="col-md-10"><?php echo ($ps_member->getProvinceName()) ? $ps_member->getProvinceName() : '-' ?></div>

						</div>
						<span class="timeline-seperator text-left"> <span><?php echo __('Personal information') ?></span>
						</span><br>

						<div class="col-sm-12 col-md-12 col-lg-12">

							<div class="col-sm-12 col-md-12 col-lg-6">
								<div class="row">
									<div class="col-md-4">
										<b><?php echo __('Birthday')?></b>:
									</div>
									<div class="col-md-8"> <?php echo ($ps_member->getBirthday()) ? date('d-m-Y', strtotime($ps_member->getBirthday())) : '--/--/----'?></div>
									<div class="col-md-4">
										<b><?php echo __('Sex')?></b>:
									</div>
									<div class="col-md-8"><?php echo ( $ps_member->getSex() == 1 ) ? __('Male') : __('Female') ?></div>
									<div class="col-md-4">
										<b><?php echo __('Identity card')?></b>:
									</div>
									<div class="col-md-8"><?php echo ($ps_member->getIdentityCard()) ? $ps_member->getIdentityCard() : '-' ?>	</div>
									<div class="col-md-4">
										<b><?php echo __('Card date')?></b>:
									</div>
									<div class="col-md-8"><?php echo ($ps_member->getCardDate()) ? date('d-m-Y', strtotime($ps_member->getCardDate())) : '--/--/----'?></div>
									<div class="col-md-4">
										<b><?php echo __('Card local')?></b>:
									</div>
									<div class="col-md-8"><?php echo ($ps_member->getCardLocal()) ? $ps_member->getCardLocal() : '-'?></div>
								</div>
							</div>

							<div class="col-sm-12 col-md-12 col-lg-6">
								<div class="row">
									<div class="col-md-4">
										<b><?php echo __('Nationality')?></b>:
									</div>
									<div class="col-md-8"> <?php

if ($ps_member->getNationality ()) {
										$culture = sfContext::getInstance ()->getUser ()
											->getCulture ();
										echo __ ( sfCultureInfo::getInstance ( $culture )->getCountry ( $ps_member->getNationality () ) );
									} else
										echo '-';
									?></div>
									<div class="col-md-4">
										<b><?php echo __('Ethnic')?></b>:
									</div>
									<div class="col-md-8"><?php echo ($ps_member->getEthnicTitle()) ? $ps_member->getEthnicTitle() : '-'?></div>
									<div class="col-md-4">
										<b><?php echo __('Religion')?></b>:
									</div>
									<div class="col-md-8"><?php echo ($ps_member->getReligionTitle()) ? $ps_member->getReligionTitle() : '-' ?></div>
									<div class="col-md-4">
										<b><?php echo __('Address')?></b>:
									</div>
									<div class="col-md-8"><?php echo  ($ps_member->getAddress()) ? $ps_member->getAddress() : '-' ?></div>
								</div>
							</div>
						</div>
						<span class="timeline-seperator text-left"> <span><?php echo __('Communications') ?></span>
						</span> <br>
						<div class="col-sm-12 col-md-12 col-lg-12">
							<div class="col-md-2">
								<i class="fa fa-phone fa-lg" aria-hidden="true"></i>
							</div>
							<div class="col-md-10"><?php echo ($ps_member->getPhone()) ? $ps_member->getPhone() : '-' ?></div>
							<div class="col-md-2">
								<i class="fa fa-mobile fa-lg" aria-hidden="true"></i>
							</div>
							<div class="col-md-10"><?php echo ($ps_member->getMobile()) ? $ps_member->getMobile() : '-' ?></div>
							<div class="col-md-2">
								<i class="fa fa-envelope-o fa-lg" aria-hidden="true"></i>
							</div>
							<div class="col-md-10"><?php echo ($ps_member->getEmail()) ? $ps_member->getEmail() : '-'?></div>


						</div>
						<span class="timeline-seperator text-left"> <span><?php echo __('Account information') ?></span>
						</span> <br>
						<div class="col-sm-12 col-md-12 col-lg-12">
							<div class="col-md-2">
								<i class="fa fa-user fa-lg" aria-hidden="true"></i>
							</div>
							<div class="col-md-10"><?php echo ($ps_member->getUsername()) ? $ps_member->getUsername() : '-' ?></div>
						</div>
					</div>
					<!-- end tab -->
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<?php

	if ($sf_user->hasCredential ( 'PS_HR_HR_EDIT' )) {
		echo link_to ( '<i class="fa-fw fa fa-pencil"></i> ' . __ ( 'Edit' ), 'ps_member_edit', $ps_member, array (
				'class' => 'btn btn-default btn-success btn-sm btn-psadmin' 
		) );
	}
	?>
	<button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
</div>