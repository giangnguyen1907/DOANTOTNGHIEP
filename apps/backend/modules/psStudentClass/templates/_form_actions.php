<?php if ($form->isNew()): ?>
  
  <?php echo $helper->linkToSave($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save_and_add',  'label' => 'Save',)) ?>
  <?php echo $helper->linkToCancel(array(  'params' =>   array(  ),  'class_suffix' => 'cancel',  'label' => 'Cancel',)) ?>
  <?php echo $helper->linkToFormDelete($form->getObject(), array(  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php else: ?>
  <?php echo $helper->linkToSave($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>
  <?php echo $helper->linkToFormDelete($form->getObject(), array(  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
  <?php echo $helper->linkToCancel(array(  'params' =>   array(  ),  'class_suffix' => 'cancel',  'label' => 'Cancel',)) ?>
<?php endif; ?>