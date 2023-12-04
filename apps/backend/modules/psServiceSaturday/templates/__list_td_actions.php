<td class="text-center">
	<div class="btn-group">
    <?php if ($sf_user->hasCredential('PS_SERVICE_SATURDAY_EDIT')): ?>
<?php echo $helper->linkToEdit($ps_service_saturday, array(  'credentials' => 'PS_SERVICE_SATURDAY_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>

<?php endif; ?>
    <?php if ($sf_user->hasCredential('PS_SERVICE_SATURDAY_DELETE')): ?>
<?php echo $helper->linkToDelete($ps_service_saturday, array(  'credentials' => 'PS_SERVICE_SATURDAY_DELETE',  'confirm' => 'Are you sure?',  'params' =>   array(  ),  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>

  </div>
</td>