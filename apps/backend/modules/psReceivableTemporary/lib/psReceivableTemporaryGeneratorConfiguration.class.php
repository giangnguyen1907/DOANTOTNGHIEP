<?php

/**
 * psReceivableTemporary module configuration.
 *
 * @package    kidsschool.vn
 * @subpackage psReceivableTemporary
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psReceivableTemporaryGeneratorConfiguration extends BasePsReceivableTemporaryGeneratorConfiguration {

	// mac dinh la thong bao da nhan
	public function getFilterDefaults() {

		$member_id = myUser::getUser ()->getMemberId ();
		$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		return array (
				'ps_customer_id' => myUser::getPscustomerID (),
				'ps_workplace_id' => $ps_workplace_id );
	}
}
