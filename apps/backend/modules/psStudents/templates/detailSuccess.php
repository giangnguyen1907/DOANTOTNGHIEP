<style>
#remoteModal .modal-dialog {
	width: 90% !important;
}
</style>
<?php use_helper('I18N', 'Date', 'Number')?>
<?php
// Su dung bien global
sfConfig::set ( 'enableRollText', PreSchool::loadPsRoll () );
$type_student_class = PreSchool::loadStatusStudentClass ();
?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" id="myModalLabel">
		<?php echo __('Student infomation: %%first_name%% %%last_name%%', array('%%first_name%%' => $_student->getFirstName(), '%%last_name%%' => $_student->getLastName())) ?>
		<small>
		(<?php if (false !== strtotime($_student->getBirthday())) echo format_date($_student->getBirthday(), "dd-MM-yyyy").'<code>'.PreSchool::getAge($_student->getBirthday(),false).'</code>';?>)
		<?php echo ' - '.__('Start date at').' : '?>
		<?php if($_student->getStartDateAt() !=''){ echo format_date($_student->getStartDateAt(), "dd-MM-yyyy") ;}?>
		</small>
	</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="padding-10">
				<ul class="nav nav-tabs tabs-pull-right">
					<li class="active"><a href="#a1" data-toggle="tab" aria-expanded="true"><?php echo __('Infomation detail') ?></a></li>
					<li><a href="#a3" data-toggle="tab" aria-expanded="true"><?php echo __('Relative of student') ?></a></li>
					<li><a href="#a2" data-toggle="tab" aria-expanded="true"><?php echo __('Class infomation') ?></a></li>
					<li><a href="#a4" data-toggle="tab" aria-expanded="true"><?php echo __('Registered service') ?></a></li>
				</ul>
				<div class="tab-content padding-top-10">
					<div class="tab-pane fade in active" id="a1">
						<div class="col-sm-12 col-md-12 col-lg-2">
							<div style="width: 100%; text-align: center; margin: 0 auto;">
								<?php
								if ($_student->getImage () != '') {
									$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $_student->getSchoolCode () . '/' . $_student->getYearData () . '/' . $_student->getImage ();
								} else {
									$path_file = '/images/no_img_avatar.png';
								}
								?>
								<img
									alt="<?php echo __('Full name').': '. $_student->getFirstName().' '.$_student->getLastName() ?>"
									src="<?php echo $path_file;?>" id="profile-image1"
									class="img-circle img-responsive" style="margin: 0 auto;" />

							</div>
						</div>
						<div class="col-sm-12 col-md-12 col-lg-10">
							<div class="col-md-2">
								<b><?php echo __('Current class')?></b>:
							</div>
							<div class="col-md-10"> <?php echo ($_student->getClassTitle()) ? $_student->getClassTitle() : '-' ?></div>
							<div class="col-md-2">
								<b><?php echo __('Student code')?></b>:
							</div>
							<div class="col-md-10"> <?php echo ($_student->getStudentCode()) ? $_student->getStudentCode() : '-' ?></div>
							<div class="col-md-2">
								<b><?php echo __('Ps customer')?></b>:
							</div>
							<div class="col-md-10"> <?php echo ($_student->getSchoolName()) ? $_student->getSchoolName() : '-' ?></div>
							<div class="col-md-2">
								<b><?php echo __('Address')?></b>:
							</div>
							<div class="col-md-10"><?php echo ($_student->getSchoolAddress()) ? $_student->getSchoolAddress() : '-' ?></div>
							<div class="col-md-2">
								<b><?php echo __('Ward')?></b>:
							</div>
							<div class="col-md-10"><?php echo ($_student->getWardName()) ? $_student->getWardName() : '-' ?></div>
							<div class="col-md-2">
								<b><?php echo __('District')?></b>:
							</div>
							<div class="col-md-10"><?php echo ($_student->getDistrictName()) ? $_student->getDistrictName() : '-'?></div>
							<div class="col-md-2">
								<b><?php echo __('Province')?></b>:
							</div>
							<div class="col-md-10"><?php echo ($_student->getProvinceName()) ? $_student->getProvinceName() : '-' ?></div>
							<div class="col-md-2">
								<b><?php echo __('Basis enrollment')?></b>:
							</div>
							<div class="col-md-10"><?php echo ($_student->getWpTitle()) ? $_student->getWpTitle() : '-' ?></div>
						</div>
						<span class="timeline-seperator text-left"> <span><?php echo __('Personal information') ?></span>
						</span><br />
						<div class="col-sm-12 col-md-12 col-lg-12">
							<div class="col-sm-12 col-md-12 col-lg-6">
								<div class="row">
									<div class="col-md-4">
										<b><?php echo __('Birthday')?></b>:
									</div>
									<div class="col-md-8"> <?php echo ($_student->getBirthday()) ? date('d-m-Y', strtotime($_student->getBirthday())) : '--/--/----'?></div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<b><?php  echo __('Age') ?></b>:
									</div>
									<div class="col-md-8">
										<code><?php echo ($_student->getBirthday()) ? PreSchool::getAge($_student->getBirthday(), false) : '-' ?></code>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<b><?php echo __('Sex')?></b>:
									</div>
									<div class="col-md-8"><?php echo ( $_student->getSex() == 1 ) ? __('Male') : __('Female') ?></div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<b><?php echo __('Common name')?></b>:
									</div>
									<div class="col-md-8"><?php echo ($_student->getCommonName()) ? $_student->getCommonName() : '-'  ?></div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<b><?php echo __('Nick name')?></b>:
									</div>
									<div class="col-md-8"><?php echo ($_student->getNickName()) ? $_student->getNickName() : '-'  ?></div>
								</div>
							</div>
							<div class="col-sm-12 col-md-12 col-lg-6">
								<div class="row">
									<div class="col-md-4">
										<b><?php echo __('Nationality')?></b>:
									</div>
									<div class="col-md-8">
									<?php
									if ($_student->getNationality ()) {
										$culture = sfContext::getInstance ()->getUser ()
											->getCulture ();
										echo __ ( sfCultureInfo::getInstance ( $culture )->getCountry ( $_student->getNationality () ) );
									} else
										echo '-';
									?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<b><?php echo __('Ethnic')?></b>:
									</div>
									<div class="col-md-8"><?php echo ($_student->getEthnicTitle()) ? $_student->getEthnicTitle() : '-'?></div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<b><?php echo __('Religion')?></b>:
									</div>
									<div class="col-md-8"><?php echo ($_student->getReligionTitle()) ? $_student->getReligionTitle() : '-' ?></div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<b><?php echo __('Address')?></b>:
									</div>
									<div class="col-md-8"><?php echo  ($_student->getAddress()) ? $_student->getAddress() : '-' ?></div>
								</div>
							</div>
						</div>

						<span class="timeline-seperator text-left"> <span><?php echo __('Status') ?></span>
						</span> <br>
						<div class="col-sm-12 col-md-12 col-lg-12">
							<div class="col-sm-12 col-md-12 col-lg-6">
								<b> <?php if(isset($status_student[$_student->getStatus()])) {$status_student = PreSchool::loadStatusStudentClass(); echo __($status_student[$_student->getStatus()]);} ?></b>
							</div>
						</div>
					</div>

					<div class="tab-pane fade" id="a2">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="custom-scroll table-responsive">
								<table id="dt_basic"
									class="table table-striped table-bordered table-hover"
									width="100%">
									<tr class="info">
										<th style="width: 250px;"><?php echo __ ( 'Class name' );?></th>
										<th class="text-center" style="width: 100px;"><?php echo __ ( 'Start at' );?></th>
										<th class="text-center" style="width: 100px;"><?php echo __ ( 'Stop at' );?></th>
										<th class="text-center" style="width: 100px;"><?php echo __ ( 'Saturday school' );?></th>
										<th class="text-center" style="width: 200px;"><?php echo __ ( 'From class' );?></th>
										<th class="text-center"><?php echo __ ( 'Status studying' );?></th>
										<th class="text-center"><?php echo __ ( 'Class activated' );?></th>
										<th class="text-center"><?php echo __ ( 'Updated by' );?></th>
									</tr>

									<!-- Code cu, $list_class la tat ca danh sach lop cua hoc sinh -->
									<!-- $this->list_class = $this->_student->getAllClassOfStudent(); -->
								<?php foreach ( $list_class as $class ) :?>

									<tr>
										<td>
									<?php echo $class->getName ();?><br /> <small><i><?php echo __('WorkPlace').': '.$class->getWorkplaceTitle();?></i></small>
										</td>
										<td class="text-center"><?php echo format_date ( $class->getStartAt (), "dd-MM-yyyy" );?></td>
										<td class="text-center"><?php

									echo format_date ( $class->getStopAt (), "dd-MM-yyyy" );
									?></td>
										<td class="text-center"><?php

									echo ($class->getMyclassMode () == 1) ? __ ( 'yes' ) : __ ( 'no' )?></td>
										<td><?php echo $class->getFromClassName ();?></td>
										<td class="text-center">
										
										<?php
										if (isset ( $type_student_class [$class->getType ()] )) :
										?>
										<?php if ($class->getType() == PreSchool::SC_STATUS_OFFICIAL):?>
										<span class="label label-success" style="font-weight: normal;"><?php echo __($type_student_class[$class->getType()]);?></span>
										<?php elseif ($class->getType() == PreSchool::SC_STATUS_GRADUATION):?>
										<span class="label label-primary"><?php echo __($type_student_class[$class->getType()]);?></span>
										<?php else :?>
										<span class="label label-warning"><?php echo __($type_student_class[$class->getType()]);?></span>
										<?php endif;?>
										
									<?php endif;?>
										
										</td>
									<td class="text-center">
										<?php
									if ($class->getIsActivated ()) :
										?>
										<i class="fa fa-check-circle-o txt-color-green f-2x"
											aria-hidden="true" title="<?php echo __ ( 'Checked' )?>"></i>
										<?php else :?>
										<i class="fa fa-ban txt-color-red" aria-hidden="true"
											title="<?php echo __ ( 'UnChecked' )?>"></i>
										<?php endif;?>
									</td>
										<td class="text-center">
									<?php echo $class->getUpdatedBy() ?><br />
						  			<?php echo false !== strtotime($class->getUpdatedAt()) ? format_date($class->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
									</td>
									
									</tr>
									
							<?php endforeach;?>
							<!-- End Code -->

								</table>
							</div>
						</div>
					</div>

					<div class="tab-pane fade" id="a3">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="custom-scroll table-responsive">
								<table id="dt_basic"
									class="table table-striped table-bordered table-hover"
									width="100%">
									<tr class="info">
										<th style="width: 50px;" class="text-center"><?php echo __('Image');?></th>
										<th><?php echo __('Full name');?></th>
										<th class="text-center"><?php echo __('Birthday');?></th>
										<th class="text-center"><?php echo __('Sex');?></th>
										<th class="text-center"><?php echo __('Contact');?></th>
										<th class="text-center"><?php echo __('Username');?></th>
										<th class="text-center"><?php echo __('Relation');?></th>
										<th class="text-center">
										<?php echo __('Role');?>
							              <ul class="col-md-12"
												style="list-style-type: none;">
												<li class="col-md-3 text-center"><?php echo __('Main');?></li>
												<li class="col-md-3 text-center"><?php echo __('Parent');?></li>
												<li class="col-md-3 text-center"><?php echo __('Avatar');?></li>
												<li class="col-md-3 text-center"><?php echo __('Service');?></li>

											</ul>
										</th>
									</tr>
								<?php foreach($list_relative as $relative): ?>
								<tr>
										<td>			
									<?php
									if ($relative->getImage () != '') {
										$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_RELATIVE . '/' . $_student->getSchoolCode () . '/' . $relative->getYearData () . '/' . $relative->getImage ();
										echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
									}
									?>
									</td>
										<td><?php echo $relative->getFullName();?></td>
										<td class="text-center"><?php echo $relative->getRelativeBirthday() ? format_date($relative->getRelativeBirthday(), "dd-MM-yyyy") : '';?></td>
										<td class="text-center"><?php echo get_partial('global/field_custom/_field_sex', array('value' => $relative->getSex())) ?></td>
										<td class="text-left">
											<div>
												<i class="fa fa-phone"></i> <?php echo $relative->getMobile();?></div>
											<i class="fa fa-envelope-o"></i> <?php echo $relative->getEmail();?>
									</td>
										<td class="text-center">
									<?php
									if ($sf_user->hasCredential ( 'PS_SYSTEM_USER_EDIT' )) {
										if ($relative->getUserId () > 0)
											echo link_to ( $relative->getUserName (), '@sf_guard_user_edit?id=' . $relative->getUserId (), array (
													'data-original-title' => 'Edit user relative',
													'rel' => 'tooltip',
													'target' => '_blank' ) );
										else {
											// Add new account
											echo link_to ( '<i class="fa fa-user-plus txt-color-green"></i> ', '@sf_guard_user_new', array (
													'data-original-title' => __ ( 'New user relative' ),
													'rel' => 'tooltip',
													'target' => '_blank',
													'data-placement' => "bottom",
													'class' => 'btn btn-xs btn-default btn-add-td-action',
													'query_string' => 'utype=R&mid=' . $relative->getRelativeId () ) );
										}
									} else {
										echo $relative->getUserName ();
									}
									?>
									</td>
										<td class="text-center"><?php echo $relative->getTitle();?></td>
										<td class="text-center">
											<ul style="list-style-type: none;">
											<?php if ($relative->getIsParentMain() == 1): ?>
												<li class="col-md-3 pull-left text-center"><i
													class="fa fa-check txt-color-green" aria-hidden="true"
													title="<?php echo __('Checked') ?>"></i></li>
											<?php else: ?>
												<li class="col-md-3 pull-left text-center"></li>
											<?php endif;?>
											<?php if ($relative->getIsParent() == 1): ?>
												<li class="col-md-3 pull-left text-center"><i
													class="fa fa-check txt-color-green" aria-hidden="true"
													title="<?php echo __('Checked') ?>"></i></li>
											<?php else: ?>
												<li class="col-md-3 pull-left text-center"></li>
											<?php endif;?>
											<?php if ($relative->getRoleAvatar() == 1): ?>
												<li class="col-md-3 pull-left text-center"><i
													class="fa fa-check txt-color-green" aria-hidden="true"
													title="<?php echo __('Checked') ?>"></i></li>
											<?php else: ?>
												<li class="col-md-3 pull-left text-center"></li>
											<?php endif;?>		
											
											<?php if ($relative->getRoleService() == 1): ?>
												<li class="col-md-3 pull-left text-center"><i
													class="fa fa-check txt-color-green" aria-hidden="true"
													title="<?php echo __('Checked') ?>"></i></li>
											<?php else: ?>
												<li class="col-md-3 pull-left text-center"></li>
											<?php endif;?>
											</ul>
										</td>
									</tr>
								<?php endforeach;?>
							</table>
							</div>
						</div>
					</div>

					<div class="tab-pane fade" id="a4">
						<?php include_partial('psStudents/list_service_2', array('list_service' => $list_service,'list_service_notusing'=>$list_service_notusing)) ?>
					</div>
					<!-- end tab -->
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">	
	<?php
	if ($sf_user->hasCredential ( 'PS_STUDENT_MSTUDENT_EDIT' ) && $_student->getDeletedAt () == '') {
		echo link_to ( '<i class="fa-fw fa fa-pencil"></i> ' . __ ( 'Edit' ), 'ps_students_edit', $_student, array (
				'class' => 'btn btn-default btn-success btn-sm btn-psadmin' ) );
	}
	?>
	<?php
	if ($sf_user->hasCredential ( 'PS_STUDENT_MSTUDENT_RESTORE' ) && $_student->getDeletedAt () != '') {
		echo '<a class="btn btn-sm btn-default btn-restore" title="' . __ ( 'Detail' ) . '" href="' . url_for ( '@ps_students_restore?id=' . $_student->getId () ) . '"><i class="fa-fw fa fa-rotate-right txt-color-red"></i> ' . __ ( 'Restore' ) . '</a>';
	}
	?>
	<button type="button" class="btn btn-default btn-sm"
		data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
</div>

<script type="text/javascript"
	src="<?php echo sfContext::getInstance()->getRequest()->getRelativeUrlRoot();?>/psAdminThemePlugin/js/table_detail.js"></script>
