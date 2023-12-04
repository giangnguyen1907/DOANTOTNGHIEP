<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_SYSTEM_FEATURE_BRANCH_ADD',    1 => 'PS_SYSTEM_FEATURE_BRANCH_EDIT',  ),))): ?>
<?php echo link_to('<i class="fa-fw fa fa-list-ul"></i>'.__('Back work', array(), 'messages'), '@ps_feature_branch?feature_id='.$feature_branch->getFeatureId(), 'class=btn btn-default btn-success bg-color-green btn-psadmin pull-left') ?>
<?php endif;?>