<?php
/**
 * @project_name
 * @subpackage     interpreter 
 *
 * @file _list_relative.php
 * @filecomment Danh sach phu huyenh cua hoc sinh
 * @package_declaration package_declaration
 * @author PC
 * @version 1.0 21-10-2017 -  01:17:58
 */
$school_code = $form->getObject ()
	->getPsService ()
	->getPsCustomer ()
	->getSchoolCode ();

?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="widget-body-toolbar">
		<?php if ($sf_user->hasCredential('PS_STUDENT_SERVICE_COURSES_REGISTER_STUDENT')):?>
        <a data-toggle="modal" data-target="#remoteModal"
			data-backdrop="static"
			class="btn btn-default btn-success btn-sm btn-psadmin pull-right"
			href="<?php echo url_for('@ps_student_service_register?ps_service_course_id='.$form->getObject()->getId())?>"
			style="margin-right: 10px;"><i class="fa fa-lg fa-fw fa-street-view"></i><?php echo __('Added from the list')?></a>
        <?php endif;?>
    </div>
	<table id="dt_basic"
		class="table table-striped table-bordered table-hover" width="100%">
		<tr class="info">
			<th style="width: 50px;" class="text-center"><?php echo __('Image');?></th>
			<th><?php echo __('Student code');?></th>
			<th><?php echo __('Full name');?></th>
			<th class="text-center"><?php echo __('Birthday');?></th>
			<th class="text-center"><?php echo __('Sex');?></th>



			<th class="text-center" style="width: 90px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>
		</tr>
		<?php foreach($list_student as $student): ?>
		<tr>
			<td>			
			<?php
			if ($student->getImage () != '') {
				$path_file = '/media-web/' . PreSchool::MEDIA_TYPE_STUDENT . '/' . $school_code . '/' . $student->getYearData () . '/' . $student->getImage ();
				echo '<img style="max-width: 45px; text-align: center;" src="' . $path_file . '">';
			}
			?>
			</td>
			<td><?php echo $student->getStudentCode();?></td>
			<td><?php echo $student->getFullName();?></td>
			<td class="text-center">
				<div class="date"><?php echo (false !== strtotime($student->getBirthday())) ? format_date($student->getBirthday(),"dd-MM-yyyy").'<div><code>'.PreSchool::getAge($student->getBirthday(),false).'</code>' : '';?>
				
			
			</td>
			<td class="text-center"><?php echo get_partial('global/field_custom/_field_sex', array('value' => $student->getSex())) ?></td>

			<td class="text-center">
				<div class="btn-group">
				<?php if ($sf_user->hasCredential('PS_STUDENT_RELATIVE_REGISTER_STUDENT')):?>
    				<a data-toggle="modal" data-target="#confirmDeleteModal"
						data-backdrop="static"
						class="btn btn-xs btn-default btn-delete-student pull-right"
						data-item="<?php echo $student->getId()?>"><i
						class="fa-fw fa fa-times txt-color-red"
						title="<?php echo __('Delete')?>"></i></a>
 				 <?php endif;?>
    			</div>
			</td>
		</tr>
		<?php endforeach;?>
	</table>
</div>