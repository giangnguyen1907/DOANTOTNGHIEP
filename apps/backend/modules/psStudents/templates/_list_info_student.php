<?php
// Danh sach nguoi than cua hoc sinh
$list_relative = $student->getRelativesOfStudent ();
$school_code = $student->getPsCustomer ()
	->getSchoolCode ();
$list_class = $student->getAllClassOfStudent ();
$type_student_class = PreSchool::loadStatusStudentClass ();
$enable_roll = PreSchool::loadPsRoll ();

// Lay cac dich vu dang su dung
$list_service = array ();
$current_date = date ( "Y-m-d" );
$list_service = $student->getServicesStudentUsing ( $current_date );
// Lay cac dich vu da hủy - giá hiển thị theo tai thơi diem huy dich vu
$list_service_notusing = $student->getServicesStudentNotUsing ( $current_date );

// $list_service['list_service'] = $list_service;
// $list_service['list_service_notusing'] = $list_service_notusing;

?>
<div class="infomation_student custom-scroll table-responsive"
	style="width: 100%; max-height: 500px; overflow-y: scroll;">
	<h4><?php echo __('Student infomation')?></h4>
	<div class="table-responsive">
		<table class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th class="text-center"><?php echo __('Image') ?></th>
					<th class="text-center"><?php echo __('Student code') ?></th>
					<th class="text-center"><?php echo __('Full name') ?></th>
					<th class="text-center"><?php echo __('Nick name') ?></th>
					<th class="text-center"><?php echo __('Birthday') ?></th>
					<th class="text-center"><?php echo __('Gender') ?></th>
					<th class="text-center"><?php echo __('Start date at') ?></th>
					<th class="text-center"><?php echo __('Address') ?></th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td><?php echo get_partial('psStudents/view_img', array('type' => 'list', 'student' => $student)) ?></td>
					<td><?php echo $student->getStudentCode()?></td>
					<td><?php echo $student->getFirstName().' '.$student->getLastName();?></td>
					<td><?php echo $student->getNickName();?></td>
					<td class="text-center"><?php echo get_partial('global/field_custom/_field_birthday_student', array('value' => $student->getBirthday())) ?></td>
					<td class="text-center"><?php echo get_partial('global/field_custom/_field_sex', array('value' => $student->getSex()));?></td>
					<td class="text-center"><?php echo date('d-m-Y',strtotime($student->getStartDateAt())) ?></td>
					<td><?php echo $student->getAddress()?></td>
				</tr>
			</tbody>

		</table>
	</div>

	<h4><?php echo __('Relative of student')?></h4>
	<div class="table-responsive">
		<table class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th style="width: 70px;" class="text-center"><?php echo __('Image') ?></th>
					<th><?php echo __('Full name');?></th>
					<th class="text-center"><?php echo __('Birthday') ?></th>
					<th class="text-center"><?php echo __('Gender') ?></th>
					<th class="text-center"><?php echo __('Mobile') ?></th>
					<th class="text-center"><?php echo __('Email') ?></th>
					<th class="text-center" style="width: 100px;"><?php echo __('Relation');?></th>
					<th class="text-center sorting_disabled" rowspan="1" colspan="1"
						style="width: 420px;">
                  <?php echo __('Role');?>
                  <ul style="list-style-type: none;">
							<li class="col-md-3 pull-left text-center"><?php echo __('Main');?></li>
							<li class="col-md-3 pull-left text-center"><?php echo __('Parent');?></li>
							<li class="col-md-3 pull-left text-center"><?php echo __('Avatar');?></li>
							<li class="col-md-3 pull-left text-center"><?php echo __('Register service');?></li>
						</ul>
					</th>
				</tr>
			</thead>
			<tbody>
        	<?php foreach($list_relative as $relative): ?>
    		<tr>
					<td>			
    			<?php
										if ($relative->getImage () != '') {
											$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_RELATIVE . '/' . $school_code . '/' . $relative->getYearData () . '/' . $relative->getImage ();
											echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
										}
										?>
    			</td>
					<td><?php echo $relative->getFullName();?></td>
					<td class="text-center"><?php echo $relative->getRelativeBirthday() ? date('d-m-Y',strtotime($relative->getRelativeBirthday())) : '';?></td>
					<td class="text-center"><?php echo get_partial('global/field_custom/_field_sex', array('value' => $relative->getSex())) ?></td>
					<td class="text-center"><?php echo $relative->getMobile();?></td>
					<td class="text-center"><?php echo $relative->getEmail();?></td>

					<td class="text-center"><?php echo $relative->getTitle();?></td>
					<td>
						<ul style="list-style-type: none;"
							id="box-<?php echo $relative->getId()?>">
            			 
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
        	</tbody>

		</table>
	</div>

	<h4><?php echo __('Class infomation')?></h4>
	<div class="table-responsive">
		<table class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th style="width: 250px;"><?php echo __ ( 'Class name' );?></th>
					<th class="text-center" style="width: 100px;"><?php echo __ ( 'Start at' );?></th>
					<th class="text-center" style="width: 100px;"><?php echo __ ( 'Stop at' );?></th>
					<th class="text-center" style="width: 100px;"><?php echo __ ( 'Saturday school' );?></th>
					<th class="text-center" style="width: 200px;"><?php echo __ ( 'From class' );?></th>
					<th class="text-center"><?php echo __ ( 'Status studying' );?></th>
					<th class="text-center"><?php echo __ ( 'Class activated' );?></th>
					<th class="text-center"><?php echo __ ( 'Updated by' );?></th>

				</tr>
			</thead>
			<tbody>
        	<?php foreach ( $list_class as $class ) :?>
    		<tr>
					<td><?php echo $class->getName ();?><br /> <small><i><?php echo __('School year').': '.$class->getSchoolYear();?></i></small>
					</td>
					<td class="text-center">
    			<?php echo format_date ( $class->getStartAt (), "dd-MM-yyyy" );?></td>
					<td class="text-center">
    			<?php echo format_date ( $class->getStopAt (), "dd-MM-yyyy" );?></td>
					<td class="text-center">
    				<?php echo ($class->getMyclassMode () == PreSchool::ACTIVE) ? __ ( 'yes' ) : __ ( 'no' )?>
    			</td>
					<td>
    				<?php if ($class->getFromClassName() != ''):?>
    				<?php echo $class->getFromClassName ();?><br />
					<small><i><?php echo __('School year').': '.$class->getSchoolYearOld();?></i></small>
    				<?php endif;?>
    			</td>
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
        	</tbody>

		</table>
	</div>

	<h4><?php echo __('Registered service')?></h4>
	<div class="table-responsive">
		<table class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th style="width: 55px;" class="text-center"><?php echo __('Icon');?></th>
					<th><?php echo __('Service name');?></th>
					<th class="text-center" style="width: 120px;"><?php echo __('Enable roll');?></th>
					<th class="text-center" style="width: 100px;"><?php echo __('Service amount');?></th>
					<th class="text-center" style="width: 100px;"><?php echo __('Service detail at');?></th>
					<th class="text-center" style="width: 80px;"><?php echo __('By number');?></th>
					<th class="text-center" style="width: 80px;"><?php echo __('Discount fixed');?></th>
					<th class="text-center" style="width: 80px;"><?php echo __('Discount');?></th>
					<th class="text-center" style="width: 110px;"><?php echo __('Total money');?></th>
					<th class="text-center"><?php echo __('Note');?></th>
					<th class="text-center" style="width: 200px;"><?php echo __ ( 'Updated by' );?></th>

				</tr>
			</thead>
			<tbody>
        	<?php
									foreach ( $list_service as $service ) :
										?>
    		<tr>
					<td class="text-center">			
    			<?php
										if ($service->getFileName () != '') {
											echo image_tag ( '/sys_icon/' . $service->getFileName (), array (
													'style' => 'max-width:35px;text-align:center;' ) );
										}
										?>
    			</td>
					<td><?php echo $service->getTitle();?><br /> <small
						style="font-size: 75%;"><i><?php echo __('School year').': '.$service->getSchoolYear();?></i>, <?php echo ($service->getWpTitle() != '') ? $service->getWpTitle() : __('Whole School');?></small>
					</td>
					<td>
    			<?php if (isset($enable_roll[$service->getEnableRoll()])) echo __($enable_roll[$service->getEnableRoll()]);?>
    			<?php if ($service->getEnableSchedule() == 1 ) echo '<br><code>'.__('Subject').'</code>';?>
    			</td>

					<td class="text-right"><?php echo PreNumber::number_format($service->getAmount());?></td>
					<td class="text-center"><code><?php echo false !== strtotime($service->getDetailAt()) ? format_date($service->getDetailAt(), "MM/yyyy") : '&nbsp;' ?></code>
					</td>
					<td class="text-center"><?php echo PreNumber::number_format($service->getByNumber());?></td>
					<td class="text-center"><?php echo PreNumber::number_format($service->getDiscountAmount());?></td>
					<td class="text-center"><?php echo $service->getDiscount();?></td>
					<td class="text-right"><?php echo PreNumber::number_format(($service->getAmount()*$service->getByNumber()*(100-$service->getDiscount())/100) - $service->getDiscountAmount());?></td>
					<td class="">
    			<?php echo $service->getNote() ?><br />
					</td>
					<td class="text-center">
    			<?php echo $service->getUpdatedBy() ?><br />
      			<?php echo false !== strtotime($service->getUpdatedAt()) ? format_date($service->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
    			</td>

				</tr>
    		<?php endforeach;?>
    		<?php if(count($list_service['list_service_notusing']) > 0):?>		
    		<tr>
					<td colspan="11"><strong><?php echo __('Service unregistered')?></strong></td>
				</tr>		
    		<?php endif;?>
    		<?php
						foreach ( $list_service_notusing as $service ) :
							?>
    		<tr class="highlight">
					<td class="text-center">			
    			<?php
							if ($service->getFileName () != '') {
								echo image_tag ( '/sys_icon/' . $service->getFileName (), array (
										'style' => 'max-width:35px;text-align:center;' ) );
							}
							?>
    			</td>
					<td id="row-<?php echo $service->getSsId()?>"><?php echo $service->getTitle();?><br />
						<small style="font-size: 75%;"><i><?php echo __('School year').': '.$service->getSchoolYear();?></i>, <?php echo ($service->getWpTitle() != '') ? $service->getWpTitle() : __('Whole School');?></small>
					</td>
					<td>
    			<?php if (isset($enable_roll[$service->getEnableRoll()])) echo __($enable_roll[$service->getEnableRoll()]);?>
    			<?php if ($service->getEnableSchedule() == 1 ) echo '<br><code>'.__('Subject').'</code>';?>
    			</td>

					<td class="text-right"><?php //echo PreNumber::number_format($service->getAmount());?></td>
					<td class="text-center"><code><?php //echo false !== strtotime($service->getDetailAt()) ? format_date($service->getDetailAt(), "MM/yyyy") : '&nbsp;' ?></code>
					</td>
					<td class="text-center"><?php //echo PreNumber::number_clean($service->getByNumber());?></td>
					<td class="text-center"><?php //echo PreNumber::number_clean($service->getDiscountAmount());?></td>
					<td class="text-center"><?php //echo PreNumber::number_clean($service->getDiscount());?></td>
					<td class="text-right"><?php //echo PreNumber::number_format(($service->getAmount()*$service->getByNumber()*(100-$service->getDiscount())/100) - $service->getDiscountAmount());?></td>
					<td class="text-center">
    			<?php echo $service->getUpdatedBy() ?><br />
      			<?php echo false !== strtotime($service->getUpdatedAt()) ? format_date($service->getUpdatedAt(), "HH:mm dd/MM/yyyy") : '&nbsp;' ?>
    			</td>
					<td></td>
				</tr>
    		<?php endforeach;?>
        </tbody>

		</table>
	</div>
</div>