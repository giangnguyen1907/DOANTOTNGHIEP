<?php
/**
 * psStudentService module configuration.
 *
 * @package    quanlymamnon.vn
 * @subpackage psStudentService
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psStudentServiceGeneratorConfiguration extends BasePsStudentServiceGeneratorConfiguration {
	public function getFilterDefaults() {
		
		$psHeaderFilter = sfContext::getInstance ()->getUser ()->getAttribute ( 'psHeaderFilter', null, 'admin_module' );
		
		if (! $psHeaderFilter) {
			
			$ps_school_year_default = sfContext::getInstance ()->getUser ()->getAttribute ( 'ps_school_year_default' );
			
			$school_year_id = $ps_school_year_default->id;
		} else {
			$school_year_id = $psHeaderFilter ['ps_school_year_id'];
		}
		
		$member_id = myUser::getUser ()->getMemberId ();
		
		$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		
		return array (
			'school_year_id' => $school_year_id,
			'ps_customer_id' => myUser::getPscustomerID ()
		);
	}
}
