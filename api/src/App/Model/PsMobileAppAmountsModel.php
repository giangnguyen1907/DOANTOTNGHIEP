<?php
namespace App\Model;

class PsMobileAppAmountsModel extends BaseModel {

	protected $table = CONST_TBL_PS_MOBILE_APP_AMOUNTS;

	/**
	 * get all device_id by user_id
	 */
	public static function checkAmountInfo($user_id) {

		$curr_date = date ( "Y-m-d" );
		
		$amount = PsMobileAppAmountsModel::select ( 'expiration_date_at', 'amount' )->where ( 'user_id', $user_id )->whereDate ( 'expiration_date_at', '>=', date ( 'Y-m-d', strtotime ( $curr_date ) ) )->get ()->first ();
		
		$amount = true;
		
		return $amount ? true : false;
	}
}
