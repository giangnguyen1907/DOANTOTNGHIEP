<td class="text-center hidden-md hidden-sm hidden-xs">
	<div class="btn-group">
		<!-- 	 neu la thu nhap thi dc sua / xoa -->
  <?php if ($filter_value['type'] == 'drafts'): ?>
  <?php echo $helper->linkToEdit($ps_cms_notifications, array(  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>    <?php echo $helper->linkToDelete($ps_cms_notifications, array(  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',))?>
 
  <?php else : ?>
  <?php echo $helper->linkToDelete($ps_cms_notifications, array(  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)); ?>
  <?php endif; ?>
  </div>
</td>