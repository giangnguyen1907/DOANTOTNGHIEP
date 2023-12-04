<td class="text-center hidden-md hidden-sm hidden-xs">
<?php if (myUser::checkAccessObject($ps_function, 'PS_HR_FUNCTION_FILTER_SCHOOL')):?>
  <div class="btn-group">
    <?php if ($sf_user->hasCredential('PS_HR_FUNCTION_EDIT')): ?>
<?php echo $helper->linkToEdit($ps_function, array(  'credentials' => 'PS_HR_FUNCTION_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
<?php endif; ?>
    <?php if ($sf_user->hasCredential('PS_HR_FUNCTION_DELETE')): ?>
<?php echo $helper->linkToDelete($ps_function, array(  'credentials' => 'PS_HR_FUNCTION_DELETE',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>
  </div>
  <?php endif;?>
</td>
