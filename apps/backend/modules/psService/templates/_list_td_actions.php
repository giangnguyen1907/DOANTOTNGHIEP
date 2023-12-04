<td class="text-center">

	<div class="btn-group">
  	<?php if ($sf_user->hasCredential(array(0 => array( 0 => 'PS_STUDENT_SERVICE_EDIT', 1 => 'PS_STUDENT_SERVICE_DETAIL',2 => 'PS_STUDENT_SERVICE_DELETE',  ),))): ?>
	<?php echo $helper->linkToDetail($service, array(  'credentials' =>   array( 0 => array( 0 => 'PS_STUDENT_SERVICE_EDIT', 1 => 'PS_STUDENT_SERVICE_DETAIL',      2 => 'PS_STUDENT_SERVICE_DELETE',    ),  ),  'params' =>   array(  ),  'class_suffix' => 'detail',  'label' => 'Detail',)) ?>
	<?php endif;?>
	
    <?php if ($sf_user->hasCredential('PS_STUDENT_SERVICE_EDIT')): ?>
	<?php echo $helper->linkToEdit($service, array(  'credentials' => 'PS_STUDENT_SERVICE_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
	<?php endif; ?>
	
    <?php if ($sf_user->hasCredential('PS_STUDENT_SERVICE_DELETE')): ?>
	<?php echo $helper->linkToDelete($service, array(  'credentials' => 'PS_STUDENT_SERVICE_DELETE',  'confirm' => 'Are you sure wish delete this service?',  'params' =>   array(  ),  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
	<?php endif; ?>
  </div>
</td>