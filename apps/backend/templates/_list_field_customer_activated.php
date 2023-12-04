
<?php if ($value > 0): ?>
  <?php
	if ($value == 1) {
		echo image_tag ( sfConfig::get ( 'sf_admin_module_web_dir' ) . '/images/active.png', array (
				'alt' => __ ( 'Activated', array (), 'sf_admin' ),
				'title' => __ ( 'Activated', array (), 'sf_admin' ) ) );
		// echo '<a href="'.url_for('@ps_customer_lock?id='.$ps_customer->getId()).'">'.image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/active.png', array('alt' => __('Activated', array(), 'sf_admin'), 'title' => __('Activated', array(), 'sf_admin'))).'</a>';
		// $helper->linkToLock($obj, array( 'params' => array( ), 'class_suffix' => 'lock', 'label' => 'UnActivated',));
		// echo $helper->linkToLock($obj, array( 'params' => array( ), 'class_suffix' => 'lock', 'label' => 'Activated',));
	} else
		echo image_tag ( sfConfig::get ( 'sf_admin_module_web_dir' ) . '/images/lock.png', array (
				'alt' => __ ( 'Lock', array (), 'sf_admin' ),
				'title' => __ ( 'Lock', array (), 'sf_admin' ) ) );
	?>
<?php else: ?>
  <?php echo image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/not-active.png', array('alt' => __('Not active', array(), 'sf_admin'), 'title' => __('Not active', array(), 'sf_admin'))); ?>
<?php endif; ?>