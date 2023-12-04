<td class="text-center" style="width: 107px">
	<div class="btn-group">
  	<?php if ($sf_user->hasCredential(array(  0 => 'PS_SYSTEM_USER_DETAIL',))): ?>
		<a data-backdrop="static" data-toggle="modal"
			data-target="#remoteModal" title="<?php echo __('Detail')?>"
			href="<?php echo url_for('@sf_guard_user_detail?id='.$sf_guard_user->getId())?>"
			class="btn btn-xs btn-default"> <i
			class="fa-fw fa fa-eye txt-color-blue"></i>
		</a>	
	<?php endif; ?>
	
	<?php if ($sf_user->hasCredential(array(  0 => 'PS_SYSTEM_USER_EDIT',  1 => 'PS_SYSTEM_USER_RESET_PASSWORD'))): ?>
    <a data-backdrop="static" data-toggle="modal"
			data-target="#remoteModal" title="<?php echo __('Reset password')?>"
			href="<?php echo url_for('@sf_guard_user_refresh_password?id='.$sf_guard_user->getId())?>"
			class="btn btn-xs btn-default"> <i
			class="fa-fw fa fa-refresh txt-color-blue"></i>
		</a>
    <?php endif; ?>
    
    <?php if ($sf_user->hasCredential(array(0 => 'PS_SYSTEM_USER_EDIT'))): ?>
    	<?php if ($sf_guard_user->getUserType() != PreSchool::USER_TYPE_MANAGER):?>
    	<?php echo $helper->linkToEdit($sf_guard_user, array(  'credentials' =>   array(0 => 'PS_SYSTEM_USER_EDIT',  ),  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
    	<?php else:?>
    	<?php echo $helper->linkToEditManager($sf_guard_user, array(  'credentials' =>   array(0 => 'PS_SYSTEM_USER_EDIT',  ),  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
    	<?php endif;?>
    	
	<?php endif; ?>

    <?php if ($sf_user->hasCredential(array(  0 => 'PS_SYSTEM_USER_DELETE',))): ?>
		<?php echo $helper->linkToDelete($sf_guard_user, array(  'credentials' =>   array(    0 => 'PS_SYSTEM_USER_DELETE',  ),  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
	<?php endif; ?>
  </div>
</td>