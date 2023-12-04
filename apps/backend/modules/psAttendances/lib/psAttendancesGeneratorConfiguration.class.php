<?php

/**
 * psAttendances module configuration.
 *
 * @package    kidsschool.vn
 * @subpackage psAttendances
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psAttendancesGeneratorConfiguration extends BasePsAttendancesGeneratorConfiguration {
	
	public function getFilterDefaults() {
		
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {
			
			$class = Doctrine::getTable ( 'PsTeacherClass' )->getClassIdByUserId ( myUser::getUserId () );
			
			if ($class) {
				return array (
						'ps_workplace_id' => $class->getPsWorkplaceId(),
						'ps_class_id' => $class->getMyclassId (),
						'tracked_at' => date ( 'Y-m-d' ) );
			}
			
			return array ('tracked_at' => date ( 'Y-m-d' ) );
			
		} else {
			return array (
					'tracked_at' => date ( 'Y-m-d' ) );
		}
	}
	
	public function getFilterDelay() {
		return date ( 'Y-m-d' );
	}
}
