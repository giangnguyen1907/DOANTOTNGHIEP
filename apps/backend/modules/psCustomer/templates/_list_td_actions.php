<td class="text-center">
	<div class="btn-group">
    <?php if ( ($ps_customer->getIsRoot() != PreSchool::ACTIVE && $sf_user->hasCredential('PS_SYSTEM_CUSTOMER_EDIT')) || myUser::isAdministrator()): ?>
	<?php echo $helper->linkToEdit($ps_customer, array(  'credentials' => 'PS_SYSTEM_CUSTOMER_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
	<?php endif; ?>
    <?php if ($ps_customer->getIsRoot() != PreSchool::ACTIVE && $sf_user->hasCredential('PS_SYSTEM_CUSTOMER_DELETE')): ?>
	<?php echo $helper->linkToDelete($ps_customer, array(  'credentials' => 'PS_SYSTEM_CUSTOMER_DELETE',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
	<?php endif; ?>
  </div>
</td>