<?php if ($sf_user->hasCredential(array(  0 => 'PS_SYSTEM_USER_ADD',))): ?>
<?php echo link_to('<i class="fa-fw fa fa-plus"></i> '.__('New user relative', array(), 'messages'), 'sfGuardUser/new', array(  'class' => 'btn btn-default btn-success bg-color-green btn-psadmin',  'query_string' => 'utype=R',)) ?>
<?php endif; ?>
  
<?php if ($sf_user->hasCredential(array(  0 => 'PS_SYSTEM_USER_ADD',))): ?>
<?php echo link_to('<i class="fa-fw fa fa-plus"></i> '.__('New user member', array(), 'messages'), 'sfGuardUser/new', array(  'class' => 'btn btn-default btn-success bg-color-green btn-psadmin',  'query_string' => 'utype=T',)) ?>
<?php endif; ?>

<?php if ($sf_user->hasCredential(array(  0 => 'PS_SYSTEM_USER_ADD',  1 => 'PS_SYSTEM_USER_MANAGER_DEPARTMENT'))): ?>
<?php echo link_to('<i class="fa-fw fa fa-plus"></i> '.__('Create account for manager', array(), 'messages'), 'ps_user_departments/new', array(  'class' => 'btn btn-default btn-success bg-color-green btn-psadmin')) ?>
<?php endif; ?>