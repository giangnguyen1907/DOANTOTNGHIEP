<div class="form-actions">

	<div class="sf_admin_actions">
<?php if ($form->isNew()): ?>
		<?php if ($sf_user->hasCredential(array(  0 => 'PS_NUTRITION_MENUS_EDIT',))): ?>
			<?php echo $helper->linkToList(array(  'credentials' =>   array(    0 => 'PS_NUTRITION_MENUS_EDIT',  ),  'params' =>   array(  ),  'class_suffix' => 'list',  'label' => 'Back to list',)) ?>
		<?php endif; ?>
		<?php if ($sf_user->hasCredential(array(  0 => 'PS_NUTRITION_MENUS_EDIT',))): ?>
			<?php echo $helper->linkToSave($form->getObject(), array(  'credentials' =>   array(    0 => 'PS_NUTRITION_MENUS_EDIT',  ),  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>
		<?php endif; ?>
		
		<?php if ($sf_user->hasCredential(array(  0 => 'PS_NUTRITION_MENUS_EDIT',))): ?>
			<?php echo $helper->linkToSaveAndAdd($form->getObject(), array(  'credentials' =>   array(    0 => 'PS_NUTRITION_MENUS_EDIT',  ),  'params' =>   array(  ),  'class_suffix' => 'save_and_add',  'label' => 'Save and add',)) ?>
		<?php endif; ?>
			
		<?php else: ?>
			  <?php echo $helper->linkToList(array(  'params' =>   array(  ),  'class_suffix' => 'list',  'label' => 'Back to list',)) ?>
			  <?php echo $helper->linkToSave($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>
			  <?php echo $helper->linkToSaveAndAdd($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save_and_add',  'label' => 'Save and add',)) ?>	
			  <?php if ($sf_user->hasCredential('PS_NUTRITION_MENUS_DELETE')): ?>
			  		<?php echo $helper->linkToFormDelete($form->getObject(), array(  'credentials' => 'PS_NUTRITION_MENUS_DELETE',  'confirm' => 'Are you sure?',  'params' =>   array(  ),  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
			  <?php endif; ?>
		<?php endif; ?>
</div>
</div>