<td class="text-center hidden-md hidden-sm hidden-xs">
  <?php if (myUser::checkAccessObject($feature_option, 'PS_SYSTEM_FEATURE_OPTION_FILTER_SCHOOL')):?>
  <div class="btn-group">
    <?php if ($sf_user->hasCredential('PS_SYSTEM_FEATURE_OPTION_EDIT')): ?>
	<?php echo $helper->linkToEdit($feature_option, array(  'credentials' => 'PS_SYSTEM_FEATURE_OPTION_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
	<?php endif; ?>
	    <?php if ($sf_user->hasCredential('PS_SYSTEM_FEATURE_OPTION_DELETE')): ?>
	<?php echo $helper->linkToDelete($feature_option, array(  'credentials' => 'PS_SYSTEM_FEATURE_OPTION_DELETE',  'confirm' => 'Are you sure?',  'params' =>   array(  ),  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
	<?php endif; ?>
  </div>
<?php endif;?>
</td>
