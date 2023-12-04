<?php

/**
 * psLogtimes module configuration.
 *
 * @package    quanlymamnon.vn
 * @subpackage psLogtimes
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psLogtimesGeneratorConfiguration extends BasePsLogtimesGeneratorConfiguration {

	public function getFilterDefaults() {

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {
			
			$class = Doctrine::getTable ( 'PsTeacherClass' )->getClassIdByUserId ( myUser::getUserId () );

			if ($class) {
				return array (
						'ps_class_id' => $class->getMyclassId (),
						'tracked_at' => date ( 'Y-m-d' ) );
			}
			return array (
					'tracked_at' => date ( 'Y-m-d' ) );
		} else {
			return array (
					'tracked_at' => date ( 'Y-m-d' ) );
		}
	}

}
