<?php
/**
 * psStudentFeatures module configuration.
 *
 * @package    quanlymamnon.vn
 * @subpackage psStudentFeatures
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psStudentFeaturesGeneratorConfiguration extends BasePsStudentFeaturesGeneratorConfiguration {

	public function getFilterDefaults() {

		$member_id = myUser::getUser ()->getMemberId ();

		$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		
		$ps_school_year_default = sfContext::getInstance ()->getUser ()->getAttribute ( 'ps_school_year_default' );

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {

			$class = Doctrine::getTable ( 'PsTeacherClass' )->getClassIdByUserId ( myUser::getUserId () );

			if ($class) {
				return array (
						'school_year_id' => $ps_school_year_default->id,
						'class_id' => $class->getMyclassId (),
						'ps_customer_id' => $class->getPsCustomerId (),
						'ps_workplace_id' => $ps_workplace_id,
						'tracked_at' => date ( 'Y-m-d' ) );
			} else {
				return array (
						'school_year_id' => $ps_school_year_default->id,
						'tracked_at' => date ( 'Y-m-d' ),
						'ps_customer_id' => myUser::getPscustomerID (),
						'ps_workplace_id' => $ps_workplace_id );
			}
		} else {
			return array (
					'school_year_id' => $ps_school_year_default->id,
					'tracked_at' => date ( 'Y-m-d' ),
					'ps_customer_id' => myUser::getPscustomerID (),
					'ps_workplace_id' => $ps_workplace_id );
		}
	}
}
