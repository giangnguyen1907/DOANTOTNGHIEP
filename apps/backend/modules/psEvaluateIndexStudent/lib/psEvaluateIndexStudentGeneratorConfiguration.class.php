<?php

/**
 * psEvaluateIndexStudent module configuration.
 *
 * @package    kidsschool.vn
 * @subpackage psEvaluateIndexStudent
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psEvaluateIndexStudentGeneratorConfiguration extends BasePsEvaluateIndexStudentGeneratorConfiguration {

	public function getFilterDefaults() {

		$member_id = myUser::getUser ()->getMemberId ();

		$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );

		return array (
				'school_year_id' => Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId (),
				'ps_customer_id' => myUser::getPscustomerID (),
				'ps_workplace_id' => $ps_workplace_id,
				'ps_month' => date('m-Y')
		);
	}
}
