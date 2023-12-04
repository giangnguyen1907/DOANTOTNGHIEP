<?php

/**
 * servicegroup module configuration.
 *
 * @package    backend
 * @subpackage servicegroup
 * @author     Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class servicegroupGeneratorConfiguration extends BaseServicegroupGeneratorConfiguration {
	
	public function getFilterDefaults() {
		
		return array (
			'ps_customer_id' => myUser::getPscustomerID ());
	}
}
