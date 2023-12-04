<?php

/**
 * psEvaluateSemester module configuration.
 *
 * @package    kidsschool.vn
 * @subpackage psEvaluateSemester
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psEvaluateSemesterGeneratorConfiguration extends BasePsEvaluateSemesterGeneratorConfiguration {

	public function getFilterDefaults() {
		
		$member_id = myUser::getUser ()->getMemberId ();
		
		$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		
		return array (
				'ps_customer_id' => myUser::getPscustomerID (),
				'ps_workplace_id' => $ps_workplace_id,
				'school_year_id' => Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId (),
		);
		
	}
}
