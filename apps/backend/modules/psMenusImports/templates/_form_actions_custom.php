<div class="sf_admin_actions">
<?php if ($form->isNew()): ?>	
		
	<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_NUTRITION_MENUS_ADD',    1 => 'PS_NUTRITION_MENUS_EDIT',  ),))): ?>
	<?php echo $helper->linkToSave($form->getObject(), array(  'credentials' =>   array(    0 =>     array(      0 => 'PS_NUTRITION_MENUS_ADD',      1 => 'PS_NUTRITION_MENUS_EDIT',    ),  ),  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>
	<?php endif; ?>
	<?php echo $helper->linkToCancel(array(  'params' =>   array(  ),  'class_suffix' => 'cancel',  'label' => 'Cancel',)) ?>
<?php else: ?>
		
	<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_NUTRITION_MENUS_ADD',    1 => 'PS_NUTRITION_MENUS_EDIT',  ),))): ?>
	<?php echo $helper->linkToSave($form->getObject(), array(  'credentials' =>   array(    0 =>     array(      0 => 'PS_NUTRITION_MENUS_ADD',      1 => 'PS_NUTRITION_MENUS_EDIT',    ),  ),  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>
	<?php endif; ?>
	
	<?php echo $helper->linkToCancel(array(  'params' =>   array(  ),  'class_suffix' => 'cancel',  'label' => 'Cancel',)) ?>
	
	<?php if ($sf_user->hasCredential('PS_NUTRITION_MENUS_DELETE')): ?>
	<?php echo $helper->linkToFormDelete($form->getObject(), array(  'credentials' => 'PS_NUTRITION_MENUS_DELETE',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
	<?php endif; ?>
	
<?php endif; ?>
</div>