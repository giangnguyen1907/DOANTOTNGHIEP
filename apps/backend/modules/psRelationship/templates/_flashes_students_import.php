<?php if ($sf_user->hasFlash('error')): ?>
<div class="alert alert-danger fade in">
	<button class="close" data-dismiss="alert">×</button>
	<i class="fa-fw fa fa-times ps-fa-2x"></i> <?php echo __($sf_user->getFlash('error'), array(), 'sf_admin') ?>
  </div>
<?php endif; ?>
<?php if ($sf_user->hasFlash('notice')): ?>
<div class="alert alert-success no-margin fade in">
	<button class="close" data-dismiss="alert">×</button>
	<i class="fa-fw fa fa-check ps-fa-2x"></i><?php echo __($sf_user->getFlash('notice'), array(), 'sf_admin') ?>
  </div>
<?php endif; ?>
<?php if ($sf_user->hasFlash('successfully') || $sf_user->hasFlash('notice_student_error') || $sf_user->hasFlash('warning_student_code')): ?>
<div class="alert alert-warning no-margin fade in">
      <?php echo __($sf_user->getFlash('successfully'))?>
      
      <?php
	if ($sf_user->hasFlash ( 'notice_student_error' )) {
		echo '<br/>' . __ ( $sf_user->getFlash ( 'notice_student_error' ) );
	}
	?>      
      <?php
	if ($sf_user->hasFlash ( 'notice_student_error_line' )) {
		echo '<br/>' . __ ( $sf_user->getFlash ( 'notice_student_error_line' ) );
	}
	?>
      
      <?php
	if ($sf_user->hasFlash ( 'notice_relative_error' )) {
		echo '<br/>' . __ ( $sf_user->getFlash ( 'notice_relative_error' ) );
	}
	?>      
      <?php
	if ($sf_user->hasFlash ( 'notice_relative_error_line' )) {
		echo '<br/>' . __ ( $sf_user->getFlash ( 'notice_relative_error_line' ) );
	}
	?>      
      <?php
	if ($sf_user->hasFlash ( 'warning_email' )) {
		echo '<br/>' . __ ( $sf_user->getFlash ( 'warning_email' ) );
	}
	?>
      <?php
	if ($sf_user->hasFlash ( 'warning_student_code' )) {
		echo '<br/>' . __ ( $sf_user->getFlash ( 'warning_student_code' ) );
	}
	?>
  </div>
<?php endif;?>