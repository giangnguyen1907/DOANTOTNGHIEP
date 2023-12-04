<?php
/**
 * psServiceCourses module configuration.
 *
 * @package    kidsschool.vn
 * @subpackage psServiceCourses
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psServiceCoursesGeneratorConfiguration extends BasePsServiceCoursesGeneratorConfiguration {

	public function getFilterDefaults() {

		$school_year_id = null;

		$ps_school_year = sfContext::getInstance ()->getUser ()
			->getAttribute ( 'ps_school_year_default' );

		if ($ps_school_year)
			$school_year_id = $ps_school_year->id;
		else {
			$ps_school_year = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );

			if ($ps_school_year)
				$school_year_id = $ps_school_year->getId ();
		}

		$member_id = myUser::getUser ()->getMemberId ();

		$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );

		return array (
				'school_year_id' => $school_year_id,
				'ps_customer_id' => myUser::getPscustomerID (),
				'ps_workplace_id' => $ps_workplace_id );
	}
}
