<div class="form-actions">
	<div class="sf_admin_actions">
	<?php if ($form->isNew()): ?>		  
		  <?php echo $helper->linkToList(array(  'params' =>   array(  ),  'class_suffix' => 'list',  'label' => 'Back to list',)) ?>	
			
		  <?php echo $helper->linkToSave($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>	
			
		  <?php echo $helper->linkToSaveAndAdd($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save_and_add',  'label' => 'Save and add',)) ?>	  
			
		  <?php if ($sf_user->hasCredential('PS_STUDENT_MSTUDENT_DELETE')): ?>
		  <?php echo $helper->linkToFormDelete($form->getObject(), array(  'credentials' => 'PS_STUDENT_MSTUDENT_DELETE',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',))?>
		  <?php endif; ?>

	<?php else: ?>		
		
		  <?php echo $helper->linkToList(array(  'params' =>   array(  ),  'class_suffix' => 'list',  'label' => 'Back to list',)) ?>
		  
			  <?php if ($form->getObject()->getDeletedAt() == ''): ?>
			  
			  <?php echo $helper->linkToSave($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>	
				
			  <?php echo $helper->linkToSaveAndAdd($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save_and_add',  'label' => 'Save and add',)) ?>	  
				
			  <?php if ($sf_user->hasCredential('PS_STUDENT_MSTUDENT_DELETE')): ?>
				<?php echo $helper->linkToFormDelete($form->getObject(), array(  'credentials' => 'PS_STUDENT_MSTUDENT_DELETE',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',))?>
			  <?php endif; ?>
		  
		  	<?php endif; ?>
		
	<?php endif; ?>
</div>
</div>