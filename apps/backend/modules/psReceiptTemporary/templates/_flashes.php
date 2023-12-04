<?php if ($sf_user->hasFlash('notice')): ?>
<div class="alert alert-success no-margin fade in">
	<button class="close" data-dismiss="alert">×</button>
	<i class="fa-fw fa fa-check ps-fa-2x"></i> <?php echo __($sf_user->getFlash('notice'), array(), 'sf_admin') ?>
  </div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('notice1')): ?>
<div class="alert alert-success no-margin fade in">
	<button class="close" data-dismiss="alert">×</button>
      <?php echo __($sf_user->getFlash('notice1'), array(), 'sf_admin') ?>
  </div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('notice2')): ?>
<div class="alert alert-danger no-margin fade in">
	<button class="close" data-dismiss="alert">×</button>
	  <?php echo __($sf_user->getFlash('notice5'), array(), 'sf_admin') ?><br />
      <?php echo __($sf_user->getFlash('notice2'), array(), 'sf_admin') ?><br />
      <?php echo __($sf_user->getFlash('notice3'), array(), 'sf_admin') ?>
  </div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('error')): ?>
<div class="alert alert-danger fade in">
	<button class="close" data-dismiss="alert">×</button>
	<i class="fa-fw fa fa-times ps-fa-2x"></i> <?php echo __($sf_user->getFlash('error'), array(), 'sf_admin') ?>
  </div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('warning')): ?>
<div class="alert alert-warning fade in">
	<button class="close" data-dismiss="alert">×</button>
	<i class="fa-fw fa fa-warning ps-fa-2x" aria-hidden="true"></i> <?php echo __($sf_user->getFlash('warning'), array(), 'sf_admin') ?>
  </div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('danger')): ?>
<div class="alert alert-danger fade in">
	<button class="close" data-dismiss="alert">×</button>
	<i class="fa-fw fa fa-ban ps-fa-2x" aria-hidden="true"></i>  <?php echo __($sf_user->getFlash('danger'), array(), 'sf_admin') ?>
  </div>
<?php endif; ?>


<?php if ($sf_user->hasFlash('info')): ?>
<div class="alert alert-info fade in">
	<button class="close" data-dismiss="alert">×</button>
	<i class="fa fa fa-info-circle ps-fa-2x" aria-hidden="true"></i>  <?php echo __($sf_user->getFlash('info'), array(), 'sf_admin') ?>
  </div>
<?php endif; ?>
