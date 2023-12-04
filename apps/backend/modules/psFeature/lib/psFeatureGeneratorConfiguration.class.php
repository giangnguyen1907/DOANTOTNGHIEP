<?php

/**
 * psFeature module configuration.
 *
 * @package    quanlymamnon.vn
 * @subpackage psFeature
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psFeatureGeneratorConfiguration extends BasePsFeatureGeneratorConfiguration {
	
	public function getFilterDefaults() {
		
		return array (
				'ps_customer_id' => myUser::getPscustomerID (),
				'is_activated' => PreSchool::ACTIVE );
	}
}
