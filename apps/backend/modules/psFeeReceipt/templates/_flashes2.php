<?php if ($sf_user->hasFlash('notice')): ?>
<div class="alert alert-success no-margin fade in">
	<button class="close" data-dismiss="alert">×</button>
	<i class="fa-fw fa fa-check ps-fa-2x"></i> <?php echo __($sf_user->getFlash('notice'), array(), 'sf_admin') ?>
  </div>
<br />

<?php endif; ?>

<?php if ($sf_user->hasFlash('notice1') || $sf_user->hasFlash('notice2') || $sf_user->hasFlash('notice3')): ?>
<div class="alert alert-success no-margin fade in">
	<button class="close" data-dismiss="alert">×</button>
      <?php if ($sf_user->hasFlash('notice1')){ echo __($sf_user->getFlash('notice1'), array(), 'sf_admin'); ?><br /> <?php }?>
      <?php if ($sf_user->hasFlash('notice2')){ echo __($sf_user->getFlash('notice2'), array(), 'sf_admin'); ?><br /> <?php }?>
      <?php if ($sf_user->hasFlash('notice3')){ echo __($sf_user->getFlash('notice3'), array(), 'sf_admin'); }?>
  </div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('error')): ?>
<div class="alert alert-danger fade in">
	<button class="close" data-dismiss="alert">×</button>
	<i class="fa-fw fa fa-times ps-fa-2x"></i> <?php echo __($sf_user->getFlash('error'), array(), 'sf_admin') ?>
  </div>
<?php endif; ?>
