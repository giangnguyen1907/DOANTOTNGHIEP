<td class="text-center">
  <?php if($feature_option_feature->getSfId() == '' || $feature_option_feature->getSfId() <= 0){?>
  <div class="btn-group">
    <?php if ($sf_user->hasCredential('PS_SYSTEM_FEATURE_BRANCH_DELETE')): ?>
<?php echo $helper->linkToDelete($feature_option_feature, array(  'credentials' => 'PS_SYSTEM_FEATURE_BRANCH_DELETE',  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
<?php endif; ?>

  </div>
  <?php }?>
</td>