<?php if ($sf_user->hasCredential('PS_SYSTEM_WARD_ADD')): ?>
<?php echo $helper->linkToNew(array(  'credentials' => 'PS_SYSTEM_WARD_ADD',  'params' =>   array(  ),  'class_suffix' => 'new',  'label' => 'New',)) ?>
<?php endif; ?>