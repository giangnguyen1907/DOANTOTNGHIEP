<?php

/**
 * psProvince module configuration.
 *
 * @package    quanlymamnon.vn
 * @subpackage psProvince
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psProvinceGeneratorConfiguration extends BasePsProvinceGeneratorConfiguration {

	public function getFilterDefaults() {

		$default_country_code = strtoupper ( sfConfig::get ( 'app_ps_default_country' ) );
		return array (
				'country_code' => $default_country_code );
	}
}
