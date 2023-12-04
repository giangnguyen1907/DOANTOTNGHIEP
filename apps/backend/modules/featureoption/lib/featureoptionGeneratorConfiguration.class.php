<?php
/**
 * featureoption module configuration.
 *
 * @package    backend
 * @subpackage featureoption
 * @author     Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class featureoptionGeneratorConfiguration extends BaseFeatureoptionGeneratorConfiguration {

	public function getFilterDefaults() {

		return array (
				'ps_customer_id' => myUser::getPscustomerID ()
		);
	}
}