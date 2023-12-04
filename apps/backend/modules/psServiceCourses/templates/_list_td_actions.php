<td class="text-center hidden-md hidden-sm hidden-xs">
	<div class="btn-group">

<?php if ($sf_user->hasCredential('PS_STUDENT_SERVICE_COURSES_DETAIL')): ?>
	<a class="btn btn-xs btn-default" data-backdrop="static"
			data-toggle="modal" data-target="#remoteModal"
			title="<?php echo __('Detail')?>"
			href="<?php echo url_for('@ps_service_courses_detail?id='.$ps_service_courses->getId())?>"><i
			class="fa-fw fa fa-eye txt-color-blue"></i></a>	
<?php endif;?>

<?php if ($sf_user->hasCredential('PS_STUDENT_SERVICE_COURSES_EDIT')): ?>
<?php echo $helper->linkToEdit($ps_service_courses, array(  'credentials' => 'PS_STUDENT_SERVICE_COURSES_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
<?php endif; ?>
    <?php if ($sf_user->hasCredential('PS_STUDENT_SERVICE_COURSES_DELETE')): ?>
<?php echo $helper->linkToDelete($ps_service_courses, array(  'credentials' => 'PS_STUDENT_SERVICE_COURSES_DELETE',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>

  </div>
</td>