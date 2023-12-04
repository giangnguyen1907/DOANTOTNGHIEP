<td class="text-center">
	<div class="btn-group">
<?php if ($ps_logtimes->getId()):?>
	<?php if ($sf_user->hasCredential(array('PS_STUDENT_ATTENDANCE_EDIT'))): ?>
	<?php echo $helper->linkToEdit($ps_logtimes, array('credentials' => 'PS_STUDENT_ATTENDANCE_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
	<?php endif; ?>
<?php else: ?>
	<?php if ($sf_user->hasCredential('PS_STUDENT_ATTENDANCE_ADD')): ?>
	<?php $tracked_at = strtotime($filter_value['tracked_at'])?>
	<a class="btn btn-xs btn-default btn-new-td-action"
			href="<?php echo url_for('@ps_logtimes_new?student_id='.$ps_logtimes->getStudentId().'&tracked_at='.$tracked_at);?>"><i
			class="fa-fw fa fa-plus txt-color-orange"
			title="<?php echo __('Attendance your baby').": ".$ps_logtimes->getStudentName();?>"></i></a>
	<?php endif; ?>
<?php endif; ?>

<?php if ($sf_user->hasCredential('PS_STUDENT_ATTENDANCE_DELETE')): ?>
<?php echo $helper->linkToDelete($ps_logtimes, array('credentials' => 'PS_STUDENT_ATTENDANCE_DELETE',  'params' =>   array(  ),  'confirm' => 'If you delete the attendee information. Used services will also be deleted.\nAre you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>
  </div>
</td>