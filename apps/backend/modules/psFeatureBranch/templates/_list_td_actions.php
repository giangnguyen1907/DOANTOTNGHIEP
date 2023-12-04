<td class="text-center">
	<div class="btn-group">
  
<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_SYSTEM_FEATURE_BRANCH_EDIT',    1 => 'PS_SYSTEM_FEATURE_BRANCH_DETAIL',    2 => 'PS_SYSTEM_FEATURE_BRANCH_DELETE',  ),))): ?>
<?php //echo $helper->linkToSchedule($feature_branch, array(  'credentials' =>   array(    0 =>     array(      0 => 'PS_SYSTEM_FEATURE_BRANCH_EDIT',      1 => 'PS_SYSTEM_FEATURE_BRANCH_DETAIL',      2 => 'PS_SYSTEM_FEATURE_BRANCH_DELETE',    ),  ),  'params' =>   array(  ),  'class_suffix' => 'schedule',  'label' => 'Schedule',)) ?>
<?php endif; ?>
    
<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_SYSTEM_FEATURE_BRANCH_EDIT',    1 => 'PS_SYSTEM_FEATURE_BRANCH_DETAIL',    2 => 'PS_SYSTEM_FEATURE_BRANCH_DELETE',  ),))): ?>
<?php //echo $helper->linkToDetail($feature_branch, array(  'credentials' =>   array(    0 =>     array(      0 => 'PS_SYSTEM_FEATURE_BRANCH_EDIT',      1 => 'PS_SYSTEM_FEATURE_BRANCH_DETAIL',      2 => 'PS_SYSTEM_FEATURE_BRANCH_DELETE',    ),  ),  'params' =>   array(  ),  'class_suffix' => 'detail',  'label' => 'Detail',)) ?>
<?php endif; ?>

    <?php if ($sf_user->hasCredential('PS_SYSTEM_FEATURE_BRANCH_EDIT')): ?>
<?php echo $helper->linkToEdit($feature_branch, array(  'credentials' => 'PS_SYSTEM_FEATURE_BRANCH_EDIT',  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
<?php endif; ?>
    <?php if ($sf_user->hasCredential('PS_SYSTEM_FEATURE_BRANCH_DELETE')): ?>
<?php echo $helper->linkToDelete($feature_branch, array(  'credentials' => 'PS_SYSTEM_FEATURE_BRANCH_DELETE',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>

  </div>
</td>