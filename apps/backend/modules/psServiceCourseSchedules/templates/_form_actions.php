<div class="form-actions">

	<div class="sf_admin_actions">
<?php if ($form->isNew()): ?>
			  <?php if ($sf_user->hasCredential(array(  0 => 'PS_STUDENT_SERVICE_COURSE_SHEDULES_EDIT',))): ?>
				<?php //echo $helper->linkToList(array(  'credentials' =>   array(    0 => 'PS_STUDENT_SERVICE_COURSE_SHEDULES_EDIT',  ),  'params' =>   array(  ),  'class_suffix' => 'list',  'label' => 'Back to list',)) ?>
				<?php endif; ?>
	
				  <?php if ($sf_user->hasCredential(array(  0 => 'PS_STUDENT_SERVICE_COURSE_SHEDULES_EDIT',))): ?>
					<?php echo $helper->linkToSave($form->getObject(), array(  'credentials' =>   array(    0 => 'PS_STUDENT_SERVICE_COURSE_SHEDULES_EDIT',  ),  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>
					<?php endif; ?>
	
				  <?php if ($sf_user->hasCredential(array(  0 => 'PS_STUDENT_SERVICE_COURSE_SHEDULES_EDIT',))): ?>
<?php echo $helper->linkToSaveAndAdd($form->getObject(), array(  'credentials' =>   array(    0 => 'PS_STUDENT_SERVICE_COURSE_SHEDULES_EDIT',  ),  'params' =>   array(  ),  'class_suffix' => 'save_and_add',  'label' => 'Save and new',)) ?>
<?php endif; ?>
	
		<?php else: ?>
			  <?php //echo $helper->linkToList(array(  'params' =>   array(  ),  'class_suffix' => 'list',  'label' => 'Back to list',)) ?>	
				  <?php if ($sf_user->hasCredential('PS_STUDENT_SERVICE_COURSE_SHEDULES_DELETE')): ?>
<?php echo $helper->linkToFormDelete($form->getObject(), array(  'credentials' => 'PS_STUDENT_SERVICE_COURSE_SHEDULES_DELETE',  'confirm' => 'Are you sure?',  'params' =>   array(  ),  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>
	
				  <?php echo $helper->linkToSave($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>	
				  <?php echo $helper->linkToSaveAndAdd($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save_and_add',  'label' => 'Save and new',)) ?>	
		<?php endif; ?>
</div>
</div>