<?php
use_helper ( 'I18N', 'Date' );
sfConfig::set ( 'enableRollText', PreSchool::loadPsRoll () );
?>
<style>
#student table tr th{color: #333;line-height: 25px!important;}
</style>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title" style="font-weight: bold;" id="myModalLabel"><?php echo __('Class info: %%name%%', array('%%name%%' => $my_class_detail->getName())) ?></h4>
</div>
<div class="modal-body">
	<div class="row">
		<ul class="nav nav-tabs pull-left clear-fix">
			<li class="active"><a data-toggle="tab" href="#home"><?php echo __('Class infomation') ?></a></li>
			<li><a data-toggle="tab" href="#student"><?php echo __('Class student') ?></a></li>
			<li><a data-toggle="tab" href="#teacher"><?php echo __('Class teacher') ?></a></li>
			<li><a data-toggle="tab" href="#service"><?php echo __('Class services') ?></a></li>
		</ul>
	</div>
	<div class="row">
		<div class="tab-content">
			<div id="home" class="tab-pane fade in active">
				<div class="custom-scroll table-responsive" style="height: 400px; overflow-y: scroll;padding-top: 5px;">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<label class="control-label"><strong><?php echo __('Code') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<p><?php echo $my_class_detail->getCode();?></p>
								</div>
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<label class="control-label"><strong><?php echo __('Class name') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<p><?php echo $my_class_detail->getName();?></p>
								</div>
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<label class="control-label"><strong><?php echo __('Object group') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<p><?php echo $my_class_detail->getPsObjectGroups()->getTitle();?></p>
								</div>
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<label class="control-label"><strong><?php echo __('School') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<p><?php echo $my_class_detail->getPsCustomer()->getTitle();?></p>
								</div>
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<label class="control-label"><strong><?php echo __('Ward') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<p><?php echo $my_class_detail->getPsCustomer()->getPsWard()->getName();?></p>
								</div>
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<label class="control-label"><strong><?php echo __('District') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<p><?php echo $my_class_detail->getPsCustomer()->getPsWard()->getPsDistrict()->getName();?></p>
								</div>
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<label class="control-label"><strong><?php echo __('Province') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<p><?php echo $my_class_detail->getPsCustomer()->getPsWard()->getPsDistrict()->getPsProvince()->getName();?></p>
								</div>
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<label class="control-label"><strong><?php echo __('Work places') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<p><?php echo $my_class_detail->getPsClassRooms()->getPsWorkPlaces()->getTitle();?></p>
								</div>
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<label class="control-label"><strong><?php echo __('Class room') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<p><?php echo $my_class_detail->getPsClassRooms()->getTitle();?></p>
								</div>
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<label class="control-label"><strong><?php echo __('School year') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<p><?php echo $my_class_detail->getPsSchoolYear()->getTitle();?></p>
								</div>
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<label class="control-label"><strong><?php echo __('Note') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<p><?php echo $my_class_detail->getNote();?></p>
								</div>
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<label class="control-label"><strong><?php echo __('Description') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<p><?php echo $my_class_detail->getDescription();?></p>
								</div>
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
									<label class="control-label"><strong><?php echo __('Is activated') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<p><?php echo $my_class_detail->getIsActivated() ?  '<span class="label-success" style="color:white;">'. __('Activity') .'</span>' :  '<span class="label-warning" style="color:white;">'. __('Inactive') .'</span>'; ?></p>
								</div>
							</div>
						</div>

					</div>
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">

						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<label class="control-label"><strong><?php echo __('Created at') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<p><?php echo format_date($my_class_detail->getCreatedAt(), 'HH:mm - dd/MM/yyyy');?></p>
								</div>
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<label class="control-label"><strong><?php echo __('User created') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<p><?php echo $my_class_detail->getUserCreated();?></p>
								</div>
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<label class="control-label"><strong><?php echo __('Updated at') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<p><?php echo format_date($my_class_detail->getUpdatedAt(),'HH:mm - dd/MM/yyyy');?></p>
								</div>
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<label class="control-label"><strong><?php echo __('User updated') ?></strong></label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<p><?php echo $my_class_detail->getUserUpdated();?></p>
								</div>
							</div>
						</div>

					</div>
				</div>
				</div>
			</div>

			<div id="student" class="tab-pane fade">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="custom-scroll table-responsive" style="height: 400px; overflow-y: scroll;padding-top: 5px;">
						<table id="dt_basic_student" class="display table table-striped table-bordered table-hover" width="100%" style="padding-top: 7px;">
							<thead>
								<tr>
									<th style="width: 50px;" class="no-order text-center"><?php echo __('Image');?></th>
									<th style="width: auto;"><?php echo __('Full name');?></th>
									<th class="text-center" style="max-width: 80px;"><?php echo __('Birthday');?></th>
									<th class="text-center" style="max-width: 80px;"><?php echo __('Sex');?></th>
									<th style="width: 120px;" class="text-center"><?php echo __('Status in class'); ?> <br>
									<span class="label label-warning" style="font-weight: normal;"><?php echo __('Total student') ?>: <?php echo count($ps_students_class) ?></span>
									</th>
									<th style="width: 100px;" class="text-center"><?php echo __('Day to class');?> </th>
									<th style="width: 100px;" class="text-center"><?php echo __('Stop at');?> </th>
								</tr>
							</thead>
							<tbody>
				              <?php
				              $arr_number_student_by_status = array();
				              
				              foreach (PreSchool::loadStatusStudentClass() as $key => $status_text) {
								$arr_number_student_by_status[$key]['number'] = 0;
								$arr_number_student_by_status[$key]['text']   = $status_text;
				              }
				              
				              foreach ($ps_students_class as $student_class) :
				              ?>
              	<tr>
					<td style="min-width: 50px;" class="text-center">
	                <?php
						if ($student_class->getImage () != '') {
							$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $school_code . '/' . $student_class->getYearData () . '/' . $student_class->getImage ();
							echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
						}
					?>
	                </td>
					<td><?php echo $student_class->getFirstName() . ' ' . $student_class->getLastName();?><br>
						<code><?php echo $student_class->getStudentCode();?></code>
					</td>
						<td class="text-center">
										<div class="date">
                    <?php echo (false !== strtotime($student_class->getBirthday())) ? format_date($student_class->getBirthday(), "dd-MM-yyyy") . '<br><code>' . PreSchool::getAge($student_class->getBirthday(), false) . '</code>' : '';?>
                    </div>
						</td>
						<td class="text-center">
                  		<?php echo get_partial('global/field_custom/_field_sex', array('value' => $student_class->getSex())) ?>
                  		</td>
						<td class="text-center">
		                  <?php
								$status = $student_class->getStudentClassType ();
								
								$my_class_detail = '';
								
								if ($status == PreSchool::SC_STATUS_OFFICIAL) {
									$my_class_detail = 'label-success';							
								} elseif ($status == PreSchool::SC_STATUS_TEST) {
									$my_class_detail = 'label-primary';
								} elseif ($status == PreSchool::SC_STATUS_PAUSE) {
									$my_class_detail = 'label-warning';
								} elseif ($status == PreSchool::SC_STATUS_STOP_STUDYING) {
									$my_class_detail = 'label-danger';
								} elseif ($status == PreSchool::SC_STATUS_GRADUATION) {
									$my_class_detail = 'label-primary';						
								} elseif ($status == PreSchool::SC_STATUS_HOLD) {
									$my_class_detail = 'label-primary';
								}
								
								$arr_number_student_by_status[$status]['number'] = $arr_number_student_by_status[$status]['number'] + 1;
								
								if (isset($type_student_class [$status] ))
									echo '<span class="label ' . $my_class_detail . '">' . __ ( $type_student_class[$status] ) . '</span>';
						  ?>
						 </td>
                  <td class="text-center">
					<div class="date">
                    <?php echo (false !== strtotime($student_class->getStartAt())) ? format_date($student_class->getStartAt(), "dd-MM-yyyy") : '';?>
                    </div>
                   </td>
                   <td class="text-center">
						<div class="date">
                    	<?php echo (false !== strtotime($student_class->getStopAt())) ? format_date($student_class->getStopAt(), "dd-MM-yyyy") : '';?>
                    	</div>
					</td>
					</tr>
              <?php endforeach;?>
              </tbody>
						</table>						
					</div>
					<div class="modal-footer" style="padding-top: 7px; font-weight: bold;">
						<h4 style="font-weight: normal;"><?php echo __('Total student') ?>: <?php echo count($ps_students_class) ?></h4>
						<?php
						foreach ($arr_number_student_by_status as $status_text) {
							echo __($status_text['text']).': '.$status_text['number'].'  ';
						}
						?>	
					</div>					
				</div>
			</div>

			<div id="teacher" class="tab-pane fade">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="custom-scroll table-responsive" style="height: 400px; overflow-y: scroll;padding-top: 5px;">
					<table id="dt_basic_teacher" class="display table table-striped table-bordered table-hover" width="100%">
						<thead>
							<tr>
								<th style="width: 50px;" class="text-center no-order"><?php echo __('Image');?></th>
								<th><?php echo __('Full name');?></th>
								<th class="text-center" style="width: 50px;"><?php echo __('HTeacher');?></th>
								<th class="text-center"><?php echo __('Start at');?></th>
								<th class="text-center"><?php echo __('End at');?></th>
							</tr>
						</thead>
						<tbody>
			            <?php foreach($ps_class_teachers as $teacher): ?>
			            <tr>
							<td class="text-center">      
			              <?php
								if ($teacher->getImage () != '') {
									$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_TEACHER . '/' . $school_code . '/' . $teacher->getPsMember ()
										->getYearData () . '/' . $teacher->getImage ();
									echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
								}
								?>
			              </td>
			              <td><?php echo $teacher->getFullName();?><br /> <code><?php echo $teacher->getMemberCode();?></code></td>
			              <td class="text-center">
			              <?php if ($teacher->getPrimaryTeacher() == PreSchool::ACTIVE): ?>
			              <i class="fa fa-check-square-o txt-color-green pre-fa-15x" aria-hidden="true" title="<?php echo __('Checked') ?>"></i>        
			              <?php endif;?>
			              </td>
						  <td class="text-center">
			              <?php echo false !== strtotime($teacher->getStartAt()) ? format_date($teacher->getStartAt(), "dd/MM/yyyy") : '&nbsp;' ?>        
			              </td>
						  <td class="text-center"><?php echo false !== strtotime($teacher->getStopAt()) ? format_date($teacher->getStopAt(), "dd/MM/yyyy") : '&nbsp;' ?></td>
						</tr>
			            <?php endforeach;?>
			            </tbody>
					</table>
					</div>									
				</div>
			</div>

			<div id="service" class="tab-pane fade">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="custom-scroll table-responsive" style="height: 400px; overflow-y: scroll;padding-top: 5px;">
					<table id="dt_basic_student"
						class="display table table-striped table-bordered table-hover form-inline"
						width="100%">
						<thead>
							<tr>
								<th style="width: 50px;" class="text-center no-order"><?php echo __('Image');?></th>
								<th style="width: auto;"><?php echo __('Name Service/Subject');?></th>
								<th><?php echo __('Note');?></th>
								<th class="text-center" style="width: 100px;"><?php echo __('Enable roll');?></th>
								<th class="text-center" style="width: 100px;"><?php echo __('Is default', array(), 'messages');?></th>
							</tr>
						</thead>
						<tbody>     
			              <?php foreach ($services as $service) :?>
			              <?php if ($service->getCsServiceId() > 0):?>     
			              <tr>
							<td class="text-center">
			                <?php
								$image_file = $service->getFileName ();
								if ($image_file != '') {
									echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">' . image_tag ( '/sys_icon/' . $image_file, array ('style' => 'max-width:35px;text-align:center;' ) ) . '</div>';
								}
							?>
			                </td>
							<td><?php echo $service->getTitle();?></td>
							<td><?php echo $service->getNote();?></td>
							<td class="text-center"><?php echo __(sfConfig::get('enableRollText')[$service->getEnableRoll()]);?></td>
							<td class="text-center">
							<?php
								if ($service->getIsDefault () == 1) {
									echo '<i class="fa fa-check-square-o txt-color-green pre-fa-15x" title="' . __ ( 'Yes' ) . '"></i>';
								}
							?>
			                </td>
							</tr>
			              <?php endif;?>              
			              <?php endforeach;?>
			            </tbody>
					</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
  
  <?php
		if ($sf_user->hasCredential ( 'PS_STUDENT_CLASS_EDIT' )) {
			echo link_to ( '<i class="fa-fw fa fa-pencil"></i> ' . __ ( 'Edit' ), 'ps_class_edit', array (
					'id' => $my_class_id ), array (
					'class' => 'btn btn-default btn-success btn-sm btn-psadmin' ) );
		}
		?>
  <button type="button" class="btn btn-default" data-dismiss="modal">
		<i class="fa-fw fa fa-ban"></i>&nbsp;<?php echo __('Close') ?></button>
</div>
