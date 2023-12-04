<?php
/**
 * @project_name
 * @subpackage     interpreter 
 *
 * @file _list_teacher.php
 * @filecomment Danh sach giao vien cua lop
 * @package_declaration package_declaration
 * @author PC
 * @version 1.0 22-08-2017 -  01:17:58
 */
$ps_class_teachers = $form->getObject ()
	->getTeachers ();
$school_code = $form->getObject ()
	->getPsCustomer ()
	->getSchoolCode ();
?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="widget-body-toolbar">    	
        <?php if ($sf_user->hasCredential('PS_STUDENT_CLASS_ADD')):?>
        <!-- -->
        <a data-toggle="modal" data-target="#remoteModal" data-backdrop="static"
			class="btn btn-default btn-success btn-sm btn-psadmin pull-right"
			href="<?php echo url_for('@ps_teacher_class_new?ps_class_id='.$form->getObject()->getId())?>" style="margin-left: 10px;"><i
			class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></a>
			
			<a class="btn btn-default btn-success btn-sm btn-psadmin pull-right" title="<?php echo __('Added list');?>"
			href="<?php echo url_for('@ps_teacher_class_add_members?id='.$form->getObject()->getId())?>"><i
			class="fa fa-plus-circle fa-lg" aria-hidden="true"></i> <?php echo __('Added list')?></a> 
        <?php endif;?>    
        
    </div>
	<div class="custom-scroll table-responsive" style="<?php if (count($ps_class_teachers) > 10) {?> height:400px; <?php };?>overflow-y: scroll;">
		<table id="dt_basic_teacher" class="display table table-striped table-bordered table-hover" width="100%">
			<thead>
				<tr>
					<th style="width: 50px;" class="text-center no-order"><?php echo __('Image');?></th>
					<th><?php echo __('Full name');?></th>
					<th class="text-center" style="width: 50px;"><?php echo __('HTeacher');?></th>
					<th class="text-center"><?php echo __('Start at');?></th>
					<th class="text-center"><?php echo __('End at');?></th>
					<th class="text-center no-order" style="width: 60px;"><?php echo __('Actions', array(), 'sf_admin') ?></th>
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
					<td><?php echo $teacher->getFullName();?><br> <code><?php echo $teacher->getMemberCode();?></code>
					</td>

					<td class="text-center">
			<?php if ($teacher->getPrimaryTeacher() == PreSchool::ACTIVE): ?>
				<i class="fa fa-check-square-o txt-color-green pre-fa-15x"
						aria-hidden="true" title="<?php echo __('Checked') ?>"></i>				
			<?php endif;?>
			</td>

					<td class="text-center">
			<?php echo false !== strtotime($teacher->getStartAt()) ? format_date($teacher->getStartAt(), "dd/MM/yyyy") : '&nbsp;' ?>				
			</td>
					<td class="text-center"><?php echo false !== strtotime($teacher->getStopAt()) ? format_date($teacher->getStopAt(), "dd/MM/yyyy") : '&nbsp;' ?>				
			</td>
					<td class="text-center">

						<div class="btn-group">
    				<?php if ($sf_user->hasCredential('PS_STUDENT_TEACHER_CLASS_EDIT')):?>
    				<a data-toggle="modal" data-target="#remoteModal"
								data-backdrop="static" class="btn btn-xs btn-default"
								href="<?php echo url_for('@ps_teacher_class_edit?id='.$teacher->getId())?>"><i
								class="fa-fw fa fa-pencil txt-color-orange"
								title="<?php echo __('Edit', array())?>"></i></a>
    				<?php endif; ?>
    				<?php if ($sf_user->hasCredential('PS_STUDENT_TEACHER_CLASS_DELETE')):?>
    				<a data-toggle="modal" data-target="#confirmDeleteModal"
								data-backdrop="static"
								class="btn btn-xs btn-default btn-delete-item pull-right"
								data-item="<?php echo $teacher->getId()?>"><i
								class="fa-fw fa fa-times txt-color-red"
								title="<?php echo __('Delete')?>"></i></a>
    				<?php endif; ?>
    			</div>

					</td>
				</tr>
		<?php endforeach;?>
		</tbody>
		</table>
	</div>
</div>