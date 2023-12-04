<td class="text-center hidden-md hidden-sm hidden-xs">
	<div class="btn-group">
    <?php if ($sf_user->hasCredential(array(  0 => 'PS_STUDENT_SUBJECT_EDIT',  1 => 'PS_STUDENT_SUBJECT_DETAIL',  2 => 'PS_STUDENT_SUBJECT_DELETE',  3 => 'PS_STUDENT_SUBJECT_FILTER_SCHOOL',))): ?>
		<a class="btn btn-xs btn-default" data-backdrop="static"
			data-toggle="modal" data-target="#remoteModal"
			title="<?php echo __('Detail')?>"
			href="<?php echo url_for('@ps_subjects_detail?id='.$service->getId())?>"><i
			class="fa-fw fa fa-eye txt-color-blue"></i></a>	
<?php endif; ?>

    <?php if ($sf_user->hasCredential('PS_STUDENT_SUBJECT_EDIT')): ?>
<?php echo $helper->linkToEdit($service, array(  'credentials' => 'PS_STUDENT_SUBJECT_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
<?php endif; ?>
    <?php if ($sf_user->hasCredential('PS_STUDENT_SUBJECT_DELETE')): ?>
<?php echo $helper->linkToDelete($service, array(  'credentials' => 'PS_STUDENT_SUBJECT_DELETE',  'confirm' => 'Are you sure?',  'params' =>   array(  ),  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>

  </div>
</td>