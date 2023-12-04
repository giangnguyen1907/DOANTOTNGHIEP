<td class="text-center hidden-md hidden-sm hidden-xs">
	<div class="btn-group">
    <?php if ($sf_user->hasCredential('PS_SYSTEM_WARD_EDIT')): ?>
<?php echo $helper->linkToEdit($ps_ward, array(  'credentials' => 'PS_SYSTEM_WARD_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
<?php endif; ?>
    <?php if ($sf_user->hasCredential('PS_SYSTEM_WARD_DELETE')): ?>
<?php echo $helper->linkToDelete($ps_ward, array(  'credentials' => 'PS_SYSTEM_WARD_DELETE',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>

  </div>
</td>