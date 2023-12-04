<div class="form-actions">

<div class="sf_admin_actions">
<?php if ($form->isNew()): ?>
  <?php echo $helper->linkToFormDelete($form->getObject(), array(  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete')) ?>
  <?php echo $helper->linkToSave($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save draft')) ?>
  <?php echo $helper->linkToSaveAndAdd($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save_and_add',  'label' => 'Send')) ?>
<?php else: ?>
  <?php echo $helper->linkToFormDelete($form->getObject(), array(  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
  <?php echo $helper->linkToSave($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>
  
<button type="submit" name="_save_and_add" class="btn btn-default btn-success btn-sm btn-psadmin"><i class="fa-fw fa fa-cloud-upload" aria-hidden="true"></i> <?php echo __('Send') ?></button>
		
<?php endif; ?>
</div>
</div>