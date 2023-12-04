<?php

/**
 * psCmsNotifications module configuration.
 *
 * @package    quanlymamnon.vn
 * @subpackage psCmsNotifications
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psCmsNotificationsGeneratorConfiguration extends BasePsCmsNotificationsGeneratorConfiguration {

	// mac dinh la thong bao da nhan
	public function getFilterDefaults() {

		return array (
				'type' => 'received' );
	}
}
