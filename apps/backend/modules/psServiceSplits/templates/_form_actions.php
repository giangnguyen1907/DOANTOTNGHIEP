<div class="form-actions">
<?php
if ($service_split->getService ()
	->getEnableRoll () == PreSchool::SERVICE_TYPE_SCHEDULE) :
	$url_back = '@ps_subjects';
	$label = 'Back to subjects';
else :
	$url_back = '@ps_service';
	$label = 'Back to service';
endif;
?>
<div class="sf_admin_actions">
<?php if ($form->isNew()): ?>
  
  <?php echo link_to(__($label, array(), 'messages'), $url_back, 'class=btn btn-default btn-success bg-color-green btn-psadmin pull-left') ?>
  
  <?php echo $helper->linkToSave($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>
  
<?php else: ?>

  <?php echo link_to(__($label, array(), 'messages'), $url_back, 'class=btn btn-default btn-success bg-color-green btn-psadmin pull-left') ?>
  
  <?php echo $helper->linkToSave($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>
  
  <?php echo $helper->linkToFormDelete($form->getObject(), array(  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
  
<?php endif; ?>
</div>
</div>