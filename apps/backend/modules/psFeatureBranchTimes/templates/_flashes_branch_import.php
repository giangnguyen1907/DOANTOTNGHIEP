<?php if ($sf_user->hasFlash('notice')): ?>
<div class="alert alert-success no-margin fade in">
	<button class="close" data-dismiss="alert">×</button>
	<i class="fa-fw fa fa-check ps-fa-2x"></i> <?php echo __($sf_user->getFlash('notice'), array(), 'sf_admin') ?>
  </div>
<br />

<?php endif; ?>

<?php if ($sf_user->hasFlash('notice_class_error') || $sf_user->hasFlash('notice_feature_error') || $sf_user->hasFlash('notice_time_error')): ?>
  <div class="alert alert-success no-margin fade in">
	 <button class="close" data-dismiss="alert">×</button>
	 <i class="fa-fw fa fa-times ps-fa-2x"></i>
      <?php if ($sf_user->hasFlash('notice_class_error')) echo __($sf_user->getFlash('notice_class_error'), array(), 'sf_admin') ?><br />
      <?php if ($sf_user->hasFlash('notice_feature_error')) echo __($sf_user->getFlash('notice_feature_error'), array(), 'sf_admin') ?><br />
      <?php if ($sf_user->hasFlash('notice_time_error')) echo __($sf_user->getFlash('notice_time_error'), array(), 'sf_admin') ?>
  </div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('error')): ?>
<div class="alert alert-danger fade in">
	<button class="close" data-dismiss="alert">×</button>
	<i class="fa-fw fa fa-times ps-fa-2x"></i> <?php echo __($sf_user->getFlash('error'), array(), 'sf_admin') ?>
  </div>
<?php endif; ?>
