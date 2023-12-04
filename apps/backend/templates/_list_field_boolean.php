<?php

if ($value == 1) :
	echo image_tag ( sfConfig::get ( 'sf_admin_module_web_dir' ) . '/images/active.png', array (
			'alt' => __ ( 'Activated', array (), 'sf_admin' ),
			'title' => __ ( 'Activated', array (), 'sf_admin' ) ) );
else :
	echo image_tag ( sfConfig::get ( 'sf_admin_module_web_dir' ) . '/images/not-active.png', array (
			'alt' => __ ( 'Not active', array (), 'sf_admin' ),
			'title' => __ ( 'Not active', array (), 'sf_admin' ) ) );
endif;
?>
