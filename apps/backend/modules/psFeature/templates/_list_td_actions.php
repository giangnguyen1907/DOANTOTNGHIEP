<td class="text-center hidden-md hidden-sm hidden-xs">
  <?php if (myUser::checkAccessObject($feature, 'PS_SYSTEM_FEATURE_FILTER_SCHOOL')):?>
  <div class="btn-group"> 
    <?php if ($sf_user->hasCredential('PS_SYSTEM_FEATURE_EDIT')): ?>
		<?php echo $helper->linkToEdit($feature, array(  'credentials' => 'PS_SYSTEM_FEATURE_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
	<?php endif; ?>
    <?php if ($sf_user->hasCredential('PS_SYSTEM_FEATURE_DELETE')): ?>
		<?php echo $helper->linkToDelete($feature, array(  'credentials' => 'PS_SYSTEM_FEATURE_DELETE',  'confirm' => 'Are you sure?',  'params' =>   array(  ),  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
	<?php endif; ?>
  </div>
  <?php endif;?>
</td>