<div class="form-actions">

	<div class="sf_admin_actions">
<?php if ($form->isNew()): ?>
  <?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_SYSTEM_WARD_SHOW',    1 => 'PS_SYSTEM_WARD_EDIT',    2 => 'PS_SYSTEM_WARD_ADD',    3 => 'PS_SYSTEM_WARD_DELETE',  ),))): ?>
<?php echo $helper->linkToList(array(  'credentials' =>   array(    0 =>     array(      0 => 'PS_SYSTEM_WARD_SHOW',      1 => 'PS_SYSTEM_WARD_EDIT',      2 => 'PS_SYSTEM_WARD_ADD',      3 => 'PS_SYSTEM_WARD_DELETE',    ),  ),  'params' =>   array(  ),  'class_suffix' => 'list',  'label' => 'Back to list',)) ?>
<?php endif; ?>

  <?php if ($sf_user->hasCredential(array(  0 => 'PS_SYSTEM_WARD_ADD',))): ?>
<?php echo $helper->linkToSave($form->getObject(), array(  'credentials' =>   array(    0 => 'PS_SYSTEM_WARD_ADD',  ),  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>
<?php endif; ?>

  <?php if ($sf_user->hasCredential(array(  0 => 'PS_SYSTEM_WARD_ADD',))): ?>
<?php echo $helper->linkToSaveAndAdd($form->getObject(), array(  'credentials' =>   array(    0 => 'PS_SYSTEM_WARD_ADD',  ),  'params' =>   array(  ),  'class_suffix' => 'save_and_add',  'label' => 'Save and add',)) ?>
<?php endif; ?>

<?php else: ?>
  <?php if ($sf_user->hasCredential('PS_SYSTEM_WARD_DELETE')): ?>
<?php echo $helper->linkToFormDelete($form->getObject(), array(  'credentials' => 'PS_SYSTEM_WARD_DELETE',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>

  <?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_SYSTEM_WARD_SHOW',    1 => 'PS_SYSTEM_WARD_DETAIL',    2 => 'PS_SYSTEM_WARD_EDIT',  ),))): ?>
<?php echo $helper->linkToList(array(  'credentials' =>   array(    0 =>     array(      0 => 'PS_SYSTEM_WARD_SHOW',      1 => 'PS_SYSTEM_WARD_DETAIL',      2 => 'PS_SYSTEM_WARD_EDIT',    ),  ),  'params' =>   array(  ),  'class_suffix' => 'list',  'label' => 'Back to list',)) ?>
<?php endif; ?>

  <?php if ($sf_user->hasCredential(array(  0 => 'PS_SYSTEM_WARD_EDIT',))): ?>
<?php echo $helper->linkToSave($form->getObject(), array(  'credentials' =>   array(    0 => 'PS_SYSTEM_WARD_EDIT',  ),  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>
<?php endif; ?>

  <?php if ($sf_user->hasCredential(array(  0 => 'PS_SYSTEM_WARD_EDIT',  1 => 'PS_SYSTEM_WARD_ADD',))): ?>
<?php echo $helper->linkToSaveAndAdd($form->getObject(), array(  'credentials' =>   array(    0 => 'PS_SYSTEM_WARD_EDIT',    1 => 'PS_SYSTEM_WARD_ADD',  ),  'params' =>   array(  ),  'class_suffix' => 'save_and_add',  'label' => 'Save and add',)) ?>
<?php endif; ?>

<?php endif; ?>
</div>
</div>