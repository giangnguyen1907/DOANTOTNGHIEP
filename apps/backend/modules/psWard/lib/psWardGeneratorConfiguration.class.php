<?php

/**
 * psWard module configuration.
 *
 * @package    quanlymamnon.vn
 * @subpackage psWard
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psWardGeneratorConfiguration extends BasePsWardGeneratorConfiguration {

	public function getFilterDefaults() {

		$default_country_code = strtoupper ( sfConfig::get ( 'app_ps_default_country' ) );
		return array (
				'country_code' => $default_country_code );
	}
}
