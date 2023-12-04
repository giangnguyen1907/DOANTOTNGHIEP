<?php use_helper('I18N') ?>

<?php include_partial('global/button_top') ?>

<div class="login-form">
	<div class="title">
		<div class="icon_signin"><?php echo __('Signin') ?></div>
	</div>
    <?php echo get_partial('sfGuardAuth/signin_form', array('form' => $form)) ?>    
</div>
<div class="line-vertical"></div>
<div class="login-introduction">
	<div class="title"><?php echo __('Management software preschool');?></div>
    <div class="" style="color:#666;"><?php echo __('Develop &amp; Design by...');?></div>
</div>
<br style="clear:both;" /><br /><br /><br /><br /><br /><br />