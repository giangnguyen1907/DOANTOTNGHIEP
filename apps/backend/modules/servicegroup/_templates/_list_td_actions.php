<td>
<?php
  if (($service_group->getPsCustomerId() == myUser::getPscustomerID()) || myUser::isAdministrator()):
?>
  <ul class="sf_admin_td_actions">
    <?php if ($sf_user->hasCredential('PS_STUDENT_SERVICE_EDIT')): ?>
<?php echo $helper->linkToEdit($service_group, array(  'credentials' => 'PS_STUDENT_SERVICE_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
<?php endif; ?>

    <?php if ($sf_user->hasCredential('PS_STUDENT_SERVICE_DELETE')): ?>
<?php echo $helper->linkToDelete($service_group, array(  'credentials' => 'PS_STUDENT_SERVICE_DELETE',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>

  </ul>
<?php endif;?>
</td>
