<?php
/**
 * @project_name
 *
 * @subpackage interpreter
 *
 * @file _list_student.php
 * @filecomment Danh sach hoc sinh cua lop
 * @package _declaration package_declaration
 * @author thangnc
 * @version 1.0 22-08-2017 - 01:17:58
 *
 */
// $ps_students_class = $form->getObject()->getPsStudents();
$ps_students_class = $form->getObject ()->getPsStudentsInClassAllStatus ();

$ps_students_active = $form->getObject ()->getActiveStudentInClass ();

$school_code = $form->getObject ()->getPsCustomer ()->getSchoolCode ();

$type_student_class = PreSchool::loadStatusStudentClass ();
$url_callback = PsEndCode::ps64EndCode ( (sfContext::getInstance ()->getRouting ()
	->getCurrentRouteName () . '?id=' . $form->getObject ()
	->getId ()) );

$thoihoc = $totnghiep = $tamdung = $giucho = 0;

foreach ( $ps_students_class as $ps_class ) {
	if ($ps_class->getStudentClassType () == PreSchool::SC_STATUS_STOP_STUDYING) {
		$thoihoc ++;
	} elseif ($ps_class->getStudentClassType () == PreSchool::SC_STATUS_PAUSE) {
		$tamdung ++;
	} elseif ($ps_class->getStudentClassType () == PreSchool::SC_STATUS_GRADUATION) {
		$totnghiep ++;
	} elseif ($ps_class->getStudentClassType () == PreSchool::SC_STATUS_HOLD) {
		$giucho ++;
	}
}

?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="custom-scroll table-responsive" style="<?php if (count($ps_students_class) > 10) {?> height:400px; <?php };?>overflow-y: scroll;">
		<table id="dt_basic_student"
			class="display table table-striped table-bordered table-hover"
			width="100%">
			<thead>
				<tr>
					<th style="width: 50px;" class="no-order text-center"><?php echo __('Image');?></th>
					<th style="width: auto;"><?php echo __('Full name');?></th>
					<th class="text-center" style="max-width: 80px;"><?php echo __('Birthday');?></th>
					<th class="text-center"><?php echo __('Sex');?></th>
					<th style="width: auto;" class="text-center"><?php echo __('Status in class'); ?> <br>
					<span class="label label-warning" style="font-weight: normal;">
					<?php echo __('Total student') ?>: <?php echo count($ps_students_class) ?></span>
					</th>
					<th style="width: 100px;" class="text-center"><?php echo __('Day to class');?> </th>
					<th style="width: 100px;" class="text-center"><?php echo __('Stop at');?> </th>
					<th class="no-order text-center" style="width: 60px;"><?php echo __('Actions', array(), 'sf_admin')?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			  $arr_number_student_by_status = array();
				              
              foreach (PreSchool::loadStatusStudentClass() as $key => $status_text) {
				$arr_number_student_by_status[$key]['number'] = 0;
				$arr_number_student_by_status[$key]['text']   = $status_text;
              }
				              
			foreach ( $ps_students_class as $student_class ) :?>
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
						<code><?php echo $student_class->getStudentCode();?></code></td>
					<td class="text-center">
						<div class="date">
						<?php echo (false !== strtotime($student_class->getBirthday())) ? format_date($student_class->getBirthday(), "dd-MM-yyyy") . '<code>' . PreSchool::getAge($student_class->getBirthday(), false) . '</code>' : '';?>
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
								} else {
									$my_class_detail = 'label-warning';
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
					<td class="text-center">
						<div class="btn-group">							
						<?php if ($sf_user->hasCredential ( 'PS_STUDENT_CLASS_REGISTER_STUDENT' )) :?>							
						<a data-toggle="modal" data-target="#remoteModal"
								data-backdrop="static" class="btn btn-xs btn-default"
								href="<?php echo url_for ( '@ps_student_class_edit?id=' . $student_class->getStudentClassId().'&url_callback='.$url_callback )?>">
								<i class="fa-fw fa fa-pencil txt-color-orange"
								title="<?php echo __('Edit', array())?>"></i>
							</a> <a data-toggle="modal" data-target="#confirmDeleteClass"
								data-backdrop="static"
								class="btn btn-xs btn-default btn-delete-class pull-right"
								data-item="<?php echo $student_class->getStudentClassId()?>"> <i
								class="fa-fw fa fa-times txt-color-red"
								title="<?php echo __('Delete')?>"></i>
							</a>
						<?php endif;?>
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