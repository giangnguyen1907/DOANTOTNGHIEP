<td class="text-center" style="width: 120px;">
	<div class="btn-group">
    <?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_SYSTEM_FEATURE_BRANCH_EDIT',    1 => 'PS_SYSTEM_FEATURE_BRANCH_DETAIL',    2 => 'PS_SYSTEM_FEATURE_BRANCH_DELETE',  ),))): ?>
<?php echo $helper->linkToDetail($feature_branch_times, array(  'credentials' =>   array(    0 =>     array(      0 => 'PS_SYSTEM_FEATURE_BRANCH_EDIT',      1 => 'PS_SYSTEM_FEATURE_BRANCH_DETAIL',      2 => 'PS_SYSTEM_FEATURE_BRANCH_DELETE',    ),  ),  'params' =>   array(  ),  'class_suffix' => 'detail',  'label' => 'Detail',)) ?>
<?php endif; ?>

    <?php if ($sf_user->hasCredential('PS_SYSTEM_FEATURE_BRANCH_EDIT')): ?>
<?php echo $helper->linkToEdit($feature_branch_times, array(  'credentials' => 'PS_SYSTEM_FEATURE_BRANCH_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
<?php endif; ?>
    <?php if ($sf_user->hasCredential('PS_SYSTEM_FEATURE_BRANCH_DELETE')): ?>
<?php echo $helper->linkToDelete($feature_branch_times, array(  'credentials' => 'PS_SYSTEM_FEATURE_BRANCH_DELETE',  'confirm' => 'Are you sure?',  'params' =>   array(  ),  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>

  </div>
</td>