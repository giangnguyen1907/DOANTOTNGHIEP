<?php

/**
 * psFeatureBranchTimes module configuration.
 *
 * @package    quanlymamnon.vn
 * @subpackage psFeatureBranchTimes
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psFeatureBranchTimesGeneratorConfiguration extends BasePsFeatureBranchTimesGeneratorConfiguration {

	public function getFilterDefaults() {

		$ps_school_year_default = sfContext::getInstance ()->getUser ()
			->getAttribute ( 'ps_school_year_default' );
		return array (
				'school_year_id' => $ps_school_year_default->id,
				'ps_customer_id' => myUser::getPscustomerID () );
	}
}
