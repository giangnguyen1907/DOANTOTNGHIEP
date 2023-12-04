<?php
/**
 * psRelatives module configuration.
 *
 * @package    quanlymamnon.vn
 * @subpackage psRelatives
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psRelativesGeneratorConfiguration extends BasePsRelativesGeneratorConfiguration {

	public function getFilterDefaults() {

		$school_year_id = null;

		$ps_school_year = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );

		if ($ps_school_year)
			$school_year_id = $ps_school_year->getId ();

		return array (
				'school_year_id' => $school_year_id,
				'ps_customer_id' => myUser::getPscustomerID () );
	}
}
