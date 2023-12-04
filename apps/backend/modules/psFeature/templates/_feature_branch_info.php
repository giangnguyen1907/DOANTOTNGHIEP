<?php if ($sf_user->hasCredential(array(  0 => 'PS_SYSTEM_FEATURE_BRANCH_SHOW',  1 => 'PS_SYSTEM_FEATURE_BRANCH_ADD',  2 => 'PS_SYSTEM_FEATURE_BRANCH_EDIT',  3 => 'PS_SYSTEM_FEATURE_BRANCH_DELETE',))): ?>
<div class="text-center">
<?php echo link_to('<i class="fa fa-info fa-1x" aria-hidden="true"></i>', '@ps_feature_branch?feature_id='.$feature->getId(), array('class' => 'btn btn-xs btn-default btn-primary', 'title' => __('Branch list'))) ?>
</div>
<?php endif; ?>