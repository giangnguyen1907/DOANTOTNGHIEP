<td class="text-center hidden-md hidden-sm hidden-xs">
<?php if (myUser::checkAccessObject($ps_department, 'PS_HR_DEPARTMENT_FILTER_SCHOOL')):?>
  <div class="btn-group">
    <?php if ($sf_user->hasCredential('PS_HR_DEPARTMENT_EDIT')): ?>
<?php echo $helper->linkToEdit($ps_department, array(  'credentials' => 'PS_HR_DEPARTMENT_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
<?php endif; ?>
    <?php if ($sf_user->hasCredential('PS_HR_DEPARTMENT_DELETE')): ?>
<?php echo $helper->linkToDelete($ps_department, array(  'credentials' => 'PS_HR_DEPARTMENT_DELETE',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>

  </div>
  <?php endif;?>
</td>