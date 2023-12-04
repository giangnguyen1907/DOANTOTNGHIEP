<?php

/**
 * Feature filter form.
 *
 * @package    backend
 * @subpackage filter
 * @author     Nguyen Chien Thang <ntsc279@hotmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FeatureFormFilter extends BaseFeatureFormFilter {

	public function configure() {
		$this->addPsCustomerFormFilter ( 'PS_SYSTEM_FEATURE_FILTER_SCHOOL' );
		
		// Load feature_id of ps_customer_id
		// Load servicegroup_id of ps_customer_id
	}
}