<?php if ($sf_user->hasCredential('PS_SYSTEM_GROUP_USER_ADD')): ?>
<?php echo $helper->linkToNew(array(  'credentials' => 'PS_SYSTEM_GROUP_USER_ADD',  'params' =>   array(  ),  'class_suffix' => 'new',  'label' => 'New',)) ?>
<?php endif; ?>