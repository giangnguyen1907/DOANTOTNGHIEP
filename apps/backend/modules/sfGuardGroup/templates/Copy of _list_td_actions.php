<td class="text-center hidden-md hidden-sm hidden-xs">
	<div class="btn-group">
	
    <?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_SYSTEM_GROUP_USER_EDIT',    1 => 'PS_SYSTEM_GROUP_USER_DETAIL',    2 => 'PS_SYSTEM_GROUP_USER_DELETE',  ),))): ?>
	<?php echo $helper->linkToDetail($sf_guard_group, array(  'credentials' =>   array(    0 =>     array(      0 => 'PS_SYSTEM_GROUP_USER_EDIT',      1 => 'PS_SYSTEM_GROUP_USER_DETAIL',      2 => 'PS_SYSTEM_GROUP_USER_DELETE',    ),  ),  'params' =>   array(  ),  'class_suffix' => 'detail',  'label' => 'Detail',)) ?>
	<?php endif; ?>
		
	<?php if (($sf_guard_group->getPsCustomerId() == '' && myUser::isAdministrator()) || ($sf_guard_group->getPsCustomerId() > 0 && ($sf_guard_group->getPsCustomerId() == myUser::getPscustomerID() || myUser::credentialPsCustomers('PS_SYSTEM_GROUP_USER_FILTER_SCHOOL')) ) ) :?>
	    
	    <?php if ($sf_guard_group->getIsSuperAdmin() != 1 &&  $sf_user->hasCredential(array(0 =>   array(0 => 'PS_SYSTEM_GROUP_USER_EDIT',    1 => 'PS_SYSTEM_GROUP_USER_DELETE',  ),))): ?>
			<?php echo $helper->linkToEdit($sf_guard_group, array(  'credentials' =>   array(    0 =>     array(      0 => 'PS_SYSTEM_GROUP_USER_EDIT',      1 => 'PS_SYSTEM_GROUP_USER_DELETE',    ),  ),  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
		<?php elseif ($sf_guard_group->getIsSuperAdmin() == 1 &&  $sf_user->hasCredential(array(0 =>   array(0 => 'PS_SYSTEM_GROUP_USER_EDIT_DETAIL'),))) :?>
		
		<?php endif;?>	
	
	    <?php if (($sf_guard_group->getIsSuperAdmin() != 1 && $sf_user->hasCredential('PS_SYSTEM_GROUP_USER_DELETE')) || ($sf_guard_group->getIsSuperAdmin() == 1 && $sf_user->hasCredential('PS_SYSTEM_GROUP_USER_DELETE') && $sf_user->hasCredential('PS_SYSTEM_GROUP_USER_EDIT_DETAIL'))): ?>
		<?php echo $helper->linkToDelete($sf_guard_group, array(  'credentials' => 'PS_SYSTEM_GROUP_USER_DELETE',  'confirm' => 'Are you sure?',  'params' =>   array(  ),  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
		<?php endif;?>
	
	<?php endif; ?>
  </div>
</td>