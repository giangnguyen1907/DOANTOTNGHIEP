
<?php if ($form->isNew()): ?>
  <?php echo $helper->linkToCancel(array(  'params' =>   array(  ),  'class_suffix' => 'cancel',  'label' => 'Cancel',)) ?>
<button type="submit" name="_save_and_add"
	onclick="return addRelative_Click();"
	class="btn btn-default btn-success btn-sm btn-psadmin">
	<i class="fa-fw fa fa-floppy-o" aria-hidden="true"></i><?php echo __('Save')?>
</button>

<?php else: ?>
  <?php echo $helper->linkToCancel(array(  'params' =>   array(  ),  'class_suffix' => 'cancel',  'label' => 'Cancel',)) ?>
  <?php echo $helper->linkToFormDelete($form->getObject(), array(  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',))?>
  <?php echo $helper->linkToSave($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',))?>  
<?php endif; ?>
