<?php

/**
 * psDistrict module configuration.
 *
 * @package    quanlymamnon.vn
 * @subpackage psDistrict
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psDistrictGeneratorConfiguration extends BasePsDistrictGeneratorConfiguration {

	public function getFilterDefaults() {

		$default_country_code = strtoupper ( sfConfig::get ( 'app_ps_default_country' ) );
		return array (
				'country_code' => $default_country_code );
	}
}
