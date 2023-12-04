<?php use_helper('I18N', 'Date') ?>
<?php include_partial('psAppPermission/assets') ?>

<div id="sf_admin_container">
	<h1><?php echo __('New application permission', array(), 'messages') ?></h1>

  <?php include_partial('psAppPermission/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('psAppPermission/form_header', array('ps_app_permission' => $ps_app_permission, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>

	<div id="sf_admin_content">
    <?php include_partial('psAppPermission/form', array('ps_app_permission' => $ps_app_permission, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </div>

	<div id="sf_admin_footer">
    <?php include_partial('psAppPermission/form_footer', array('ps_app_permission' => $ps_app_permission, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
</div>
