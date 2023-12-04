<?php
/**
 * psReceivableClass module configuration.
 *
 * @package    kidsschool.vn
 * @subpackage psReceivableClass
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psReceivableClassGeneratorConfiguration extends BasePsReceivableClassGeneratorConfiguration {

	public function getFilterDefaults() {

		$ps_school_year_default = sfContext::getInstance ()->getUser ()
			->getAttribute ( 'ps_school_year_default' );
		$member_id = myUser::getUser ()->getMemberId ();
		$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );

		return array (
				'ps_school_year_id' => $ps_school_year_default->id,
				'ps_customer_id' => myUser::getPscustomerID (),
				'ps_workplace_id' => $ps_workplace_id );
	}
}
