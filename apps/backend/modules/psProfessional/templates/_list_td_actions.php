<td class="text-center hidden-md hidden-sm hidden-xs">
	<div class="btn-group">
    <?php if ($sf_user->hasCredential('PS_HR_PROFESSIONAL_EDIT')): ?>
	<?php echo $helper->linkToEdit($ps_professional, array(  'credentials' => 'PS_HR_PROFESSIONAL_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
	<?php endif; ?>
    <?php if ($sf_user->hasCredential('PS_HR_PROFESSIONAL_DELETE')): ?>
	<?php echo $helper->linkToDelete($ps_professional, array(  'credentials' => 'PS_HR_PROFESSIONAL_DELETE',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
	<?php endif; ?>
  </div>
</td>