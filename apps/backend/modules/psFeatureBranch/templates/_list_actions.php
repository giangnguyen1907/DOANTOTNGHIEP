<?php if ($sf_user->hasCredential(array(  0 => 'PS_SYSTEM_FEATURE_BRANCH_SHOW',))): ?>
<?php
	$label = '<i class="fa-fw fa fa-list-ul" title="' . __ ( 'Back features', array (), 'sf_admin' ) . '"></i> ';
	echo link_to ( $label . __ ( 'Back features', array (), 'messages' ), '@ps_feature', 'class=btn btn-default btn-success bg-color-green btn-psadmin pull-left' )?>
<?php endif; ?>

<?php if ($sf_user->hasCredential('PS_SYSTEM_FEATURE_BRANCH_ADD')): ?>
<?php echo $helper->linkToNew(array(  'credentials' => 'PS_SYSTEM_FEATURE_BRANCH_ADD',  'params' =>   array(  ),  'class_suffix' => 'new',  'label' => 'New',)) ?>
<?php endif; ?>