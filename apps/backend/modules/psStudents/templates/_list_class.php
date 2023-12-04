<?php
/**
 * @project_name
 *
 * @subpackage interpreter
 *            
 *             @file _list_relative.php
 *             @filecomment Danh sach phu huyenh cua hoc sinh
 * @package _declaration package_declaration
 * @author PC
 * @version 1.0 21-10-2017 - 01:17:58
 *         
 */
$type_student_class = PreSchool::loadStatusStudentClass ();
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="widget-body-toolbar">
        <?php if ($sf_user->hasCredential('PS_STUDENT_CLASS_REGISTER_STUDENT')) :?>
        <a data-toggle="modal" data-target="#remoteModal"
			data-backdrop="static"
			class="btn btn-default btn-success btn-sm btn-psadmin pull-right"
			href="<?php echo url_for ( '@ps_student_class_new?student_id=' . $form->getObject ()->getId () )?>"><i
			class="fa fa-fw fa-recycle"></i> <?php echo __ ( 'Change class' )?></a>
        <?php endif;?>
    </div>
	<div class="custom-scroll table-responsive">
		<table id="dt_basic"
			class="table table-striped table-bordered table-hover" width="100%">
			<tr>
				<th style="width: 250px;"><?php echo __ ( 'Class name' );?></th>
				<th class="text-center" style="width: 100px;"><?php echo __ ( 'Start at' );?></th>
				<th class="text-center" style="width: 100px;"><?php echo __ ( 'Stop at' );?></th>
				<th class="text-center" style="width: 100px;"><?php echo __ ( 'Saturday school' );?></th>
				<th class="text-center" style="width: 200px;"><?php echo __ ( 'From class' );?></th>
				<th class="text-center"><?php echo __ ( 'Status studying' );?></th>
				<th class="text-center"><?php echo __ ( 'Class activated' );?></th>
				<th class="text-center"><?php echo __ ( 'Updated by' );?></th>
				<th class="text-center" style="width: 90px;"><?php echo __ ( 'Actions', array (), 'sf_admin' )?></th>
			</tr>
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
				<td>
					<div class="btn-group">
				<?php if ($sf_user->hasCredential ( 'PS_STUDENT_CLASS_REGISTER_STUDENT' )) :?>
					<a data-toggle="modal" data-target="#remoteModal"
							data-backdrop="static" class="btn btn-xs btn-default"
							href="<?php echo url_for ( '@ps_student_class_edit?id=' . $class->getId () )?>"><i
							class="fa-fw fa fa-pencil txt-color-orange"
							title="<?php echo __ ( 'Edit', array () )?>"></i></a> <a
							data-toggle="modal" data-target="#confirmDeleteClass"
							data-backdrop="static"
							class="btn btn-xs btn-default btn-delete-class pull-right"
							data-item="<?php echo $class->getId ()?>"><i
							class="fa-fw fa fa-times txt-color-red"
							title="<?php echo __ ( 'Delete' )?>"></i></a>
    				<?php endif;?>
         			</div>
				</td>
			</tr>
		<?php
		endforeach
		;
		?>
	</table>
	</div>
</div>