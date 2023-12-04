<td class="text-center">
	<div class="btn-group">
    <?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_STUDENT_MSTUDENT_DETAIL',    1 => 'PS_STUDENT_MSTUDENT_ADD',    2 => 'PS_STUDENT_MSTUDENT_EDIT',    3 => 'PS_STUDENT_MSTUDENT_DELETE', 4 => 'PS_STUDENT_MSTUDENT_RESTORE' ),))): ?>
	<a class="btn btn-xs btn-default" data-backdrop="static"
			data-toggle="modal" data-target="#remoteModal"
			title="<?php echo __('Detail')?>"
			href="<?php echo url_for('@ps_students_detail?id='.$student->getId())?>"><i
			class="fa-fw fa fa-eye txt-color-blue"></i></a>
	<?php endif;?>
	<?php if ($sf_user->hasCredential(array(  0 => 'PS_STUDENT_ATTENDANCE_ADD',  1 => 'PS_STUDENT_ATTENDANCE_EDIT',))): ?>	<!--
    <a class="btn btn-xs btn-default"
			href="<?php echo url_for('@ps_student_info_trackbook?sid='.$student->getId())?>"
			title="<?php echo __('Trackbook')?>"><i
			class="fa-fw fa fa-calendar-check-o txt-color-yellow"></i></a> <a
			class="btn btn-xs btn-default" target="_blank"
			href="<?php echo url_for('@ps_students_synthetic?sid='.$student->getId().'&date='.time())?>"
			title="<?php echo __('Synthetic')?>"><i
			class="fa-fw fa fa-book txt-color-yellow"></i></a>	-->
    <a class="btn btn-xs btn-default" data-backdrop="static" data-toggle="modal" data-target="#remoteModal"  title="<?php echo __('Student Off School')?>" href="<?php echo url_for('@ps_off_school_new?id='.$student->getId())?>"><i class="fa-fw fa fa-check-square-o txt-color-blue"></i></a>	
    <?php endif;?>    
  </div>
	<div class="btn-group">
    <?php if ($sf_user->hasCredential('PS_STUDENT_MSTUDENT_EDIT')): ?>
		<?php echo $helper->linkToEdit($student, array(  'credentials' => 'PS_STUDENT_MSTUDENT_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
	<?php endif;?>
	
	<?php if ($sf_user->hasCredential('PS_STUDENT_MSTUDENT_DELETE')): ?>
		<?php echo $helper->linkToDelete($student, array(  'credentials' => 'PS_STUDENT_MSTUDENT_DELETE',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
	<?php endif;?>
  </div>
</td>