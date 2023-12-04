<?php if ($sf_user->hasFlash('notice')): ?>
<div class="alert alert-success no-margin fade in">
	<button class="close" data-dismiss="alert">×</button>
	<i class="fa-fw fa fa-check ps-fa-2x"></i> <?php echo __($sf_user->getFlash('notice'), array(), 'sf_admin') ?>
  </div>
<br />

<?php endif; ?>

<?php if ($sf_user->hasFlash('error')): ?>
<div class="alert alert-danger fade in">
	<button class="close" data-dismiss="alert">×</button>
	<i class="fa-fw fa fa-times ps-fa-2x"></i> <?php echo __($sf_user->getFlash('error'), array(), 'sf_admin') ?>
  </div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('successfully') || $sf_user->hasFlash('warning_email') || $sf_user->hasFlash('successfully_class') || $sf_user->hasFlash('notice_relative_error') || $sf_user->hasFlash('warning_class')): ?>
<div class="alert alert-warning no-margin fade in">
      <button class="close" data-dismiss="alert">×</button>
      
      <i class="fa-fw fa fa-times ps-fa-2x"></i>
      
      <?php 
      	echo __($sf_user->getFlash('successfully'));
		if ($sf_user->hasFlash ( 'successfully_class' )) {
			echo '<br/>' . __ ( $sf_user->getFlash ( 'successfully_class' ) );
		}
	
		if ($sf_user->hasFlash ( 'warning_class' )) {
			echo '<br/>' . __ ( $sf_user->getFlash ( 'warning_class' ) );
		}
	
		if ($sf_user->hasFlash ( 'notice_relative_error' )) {
			echo '<br/>' . __ ( $sf_user->getFlash ( 'notice_relative_error' ) );
		}
	
		if ($sf_user->hasFlash ( 'notice_relative_error_line' )) {
			echo '<br/>' . __ ( $sf_user->getFlash ( 'notice_relative_error_line' ) );
		}
	
		if ($sf_user->hasFlash ( 'warning_email' )) {
			echo '<br/>' . __ ( $sf_user->getFlash ( 'warning_email' ) );
		}
	?>
</div>
<?php endif;?>
