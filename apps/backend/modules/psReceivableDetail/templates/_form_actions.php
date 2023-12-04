<div class="form-actions">

	<div class="sf_admin_actions">
			<?php if ($form->isNew()): ?>
		
		
		  <?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_FEE_RECEIVABLE_ADD',    1 => 'PS_FEE_RECEIVABLE_EDIT',  ),))): ?>
<?php echo $helper->linkToSave($form->getObject(), array(  'credentials' =>   array(    0 =>     array(      0 => 'PS_FEE_RECEIVABLE_ADD',      1 => 'PS_FEE_RECEIVABLE_EDIT',    ),  ),  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>
<?php endif; ?>
	
		  <?php echo $helper->linkToCancel(array(  'params' =>   array(  ),  'class_suffix' => 'cancel',  'label' => 'Cancel',)) ?>	
			
					<?php else: ?>
		
		
		  <?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_FEE_RECEIVABLE_ADD',    1 => 'PS_FEE_RECEIVABLE_EDIT',  ),))): ?>
<?php echo $helper->linkToSave($form->getObject(), array(  'credentials' =>   array(    0 =>     array(      0 => 'PS_FEE_RECEIVABLE_ADD',      1 => 'PS_FEE_RECEIVABLE_EDIT',    ),  ),  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>
<?php endif; ?>
	
			
		  <?php if ($sf_user->hasCredential('PS_FEE_RECEIVABLE_DELETE')): ?>
<?php echo $helper->linkToFormDelete($form->getObject(), array(  'credentials' => 'PS_FEE_RECEIVABLE_DELETE',  'confirm' => 'Are you sure?',  'params' =>   array(  ),  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>
	
		  <?php echo $helper->linkToCancel(array(  'params' =>   array(  ),  'class_suffix' => 'cancel',  'label' => 'Cancel',)) ?>	
			
		<?php endif; ?>
</div>
</div>