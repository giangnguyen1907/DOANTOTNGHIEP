<td class="text-center hidden-md hidden-sm hidden-xs">
<?php //if (myUser::checkAccessObject($ps_work_places, 'PS_SYSTEM_WORK_PLACES_FILTER_SCHOOL')):?>
  <div class="btn-group">
    <?php if ($sf_user->hasCredential('PS_SYSTEM_WORK_PLACES_EDIT')): ?>
	<?php echo $helper->linkToEdit($ps_work_places, array(  'credentials' => 'PS_SYSTEM_WORK_PLACES_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
	<?php endif; ?>
	
	<?php if ($sf_user->hasCredential('PS_SYSTEM_WORK_PLACES_DELETE')): ?>
	<?php echo $helper->linkToDelete($ps_work_places, array(  'credentials' => 'PS_SYSTEM_WORK_PLACES_DELETE',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
	<?php endif; ?>
  </div>
<?php //endif; ?>
</td>