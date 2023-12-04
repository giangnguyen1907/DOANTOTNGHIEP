<td class="text-center hidden-md hidden-sm hidden-xs">
	<div class="btn-group">
<?php if ($sf_user->hasCredential(array(  0 => 'PS_STUDENT_ATTENDANCE_ADD',  1 => 'PS_STUDENT_ATTENDANCE_EDIT',))): ?>
<a class="btn btn-xs btn-default"
			href="<?php echo url_for('@ps_student_info_trackbook?sid='.$student->getId())?>"
			title="<?php echo __('Trackbook')?>"><i
			class="fa-fw fa fa-calendar-check-o txt-color-yellow"></i></a>
<?php endif;?>
<?php //if ($sf_user->hasCredential(array(  0 => 'PS_STUDENT_MSTUDENT_DETAIL',  1 => 'PS_STUDENT_MSTUDENT_DELETE',))): ?>
<!--  <a class="btn btn-xs btn-default" href="#" title="<?php echo __('Payment')?>"><i class="fa-fw fa fa-usd txt-color-blue"></i></a>-->
<?php //endif; ?>
  </div>
</td>