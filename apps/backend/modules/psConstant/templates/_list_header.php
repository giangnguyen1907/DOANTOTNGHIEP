<?php if ($sf_user->hasFlash('msg')): ?>
<div class="alert alert-warning fade in">
	<button class="close" data-dismiss="alert">×</button>
	<i class="fa-fw fa fa-warning ps-fa-2x" aria-hidden="true"></i> <?php echo __($sf_user->getFlash('msg'), array(), 'sf_admin');?>
  </div>
<?php endif; ?>