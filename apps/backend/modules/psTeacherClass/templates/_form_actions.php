<?php if ($form->isNew()): ?>
  	<?php if ($sf_user->hasCredential('PS_STUDENT_TEACHER_CLASS_EDIT')): ?>
	<?php echo $helper->linkToSave($form->getObject(), array(  'credentials' => 'PS_STUDENT_TEACHER_CLASS_EDIT',  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>
	<?php endif; ?>
	
	<?php echo $helper->linkToCancel(array(  'params' =>   array(  ),  'class_suffix' => 'cancel',  'label' => 'Cancel',)) ?>
<?php else: ?>

  	<?php if ($sf_user->hasCredential('PS_STUDENT_TEACHER_CLASS_EDIT')): ?>
	<?php echo $helper->linkToSave($form->getObject(), array(  'credentials' => 'PS_STUDENT_TEACHER_CLASS_EDIT',  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>
	<?php endif; ?>
	
	<?php if ($sf_user->hasCredential('PS_STUDENT_TEACHER_CLASS_DELETE')): ?>
	<?php echo $helper->linkToFormDelete($form->getObject(), array(  'credentials' => 'PS_STUDENT_TEACHER_CLASS_DELETE',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
	<?php endif; ?>
	
	<?php echo $helper->linkToCancel(array(  'params' =>   array(  ),  'class_suffix' => 'cancel',  'label' => 'Cancel',)) ?>
<?php endif; ?>