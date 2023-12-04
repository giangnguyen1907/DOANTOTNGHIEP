<div class="form-actions">
	<div class="sf_admin_actions">
	<?php if ($form->isNew()):?>
		<?php echo $helper->linkToFormDelete($form->getObject(), array('params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',));?>	
		<?php echo $helper->linkToList(array('params' =>   array(  ),  'class_suffix' => 'list',  'label' => 'Back to list',));?>	
		<?php echo $helper->linkToSaveAndAdd($form->getObject(), array('params' =>   array(  ),  'class_suffix' => 'save_and_add',  'label' => 'Save',));?>	
	<?php else:?>
		<?php echo $helper->linkToFormDelete($form->getObject(), array('params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',));?>	
		<?php echo $helper->linkToList(array('params' =>   array(  ),  'class_suffix' => 'list',  'label' => 'Back to list',));?>	
		<?php echo $helper->linkToSave($form->getObject(), array('params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',));?>	
		<?php echo $helper->linkToSaveAndAdd($form->getObject(), array('params' =>   array(  ),  'class_suffix' => 'save_and_add',  'label' => 'Save and new',));?>	
	<?php endif;?>
	</div>
</div>