<?php

/**
 * psAdvices module configuration.
 *
 * @package    kidsschool.vn
 * @subpackage psAdvices
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psAdvicesGeneratorConfiguration extends BasePsAdvicesGeneratorConfiguration {
	
	public function getFilterDefaults() {
		
		$member_id = myUser::getUser ()->getMemberId ();
		
		$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		
		$list_date = PsDateTime::getStartAndEndDateOfWeek(date('W'),date('Y'));
		
		$ps_school_year_default = sfContext::getInstance ()->getUser () ->getAttribute ( 'ps_school_year_default' );
		
		return array (
			'school_year_id' => $ps_school_year_default->id,
			'ps_customer_id' => myUser::getPscustomerID (),
			'ps_workplace_id' => $ps_workplace_id,
			'start_at' => date('d-m-Y', strtotime($list_date['week_start'])),
			'stop_at' => date('d-m-Y', strtotime($list_date['week_end'])),
			 );
	}
}
