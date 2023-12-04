<?php if ($sf_user->hasFlash('notice')): ?>
<div class="alert alert-success no-margin fade in">
	<button class="close" data-dismiss="alert">×</button>
	<i class="fa-fw fa fa-check ps-fa-2x"></i> <?php echo __($sf_user->getFlash('notice'), array(), 'sf_admin') ?>
  </div>
<br />

<?php endif; ?>

<?php if ($sf_user->hasFlash('notice1')): ?>
<div class="alert alert-success no-margin fade in">
      <?php echo __($sf_user->getFlash('notice1'), array(), 'sf_admin') ?><br />
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

<div class="text-center">
	<a
		class="btn btn-default btn-success bg-color-green btn-sm btn-psadmin"
		href="<?php echo url_for('@ps_students') ?>"><i
		class="fa-fw fa fa-list-ul" title="<?php echo __('Roll Back')?>"></i><?php echo __('Roll Back')?></a>
</div>
