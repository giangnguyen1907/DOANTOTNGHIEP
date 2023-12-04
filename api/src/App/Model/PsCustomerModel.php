<?php
/**
 * @package truongnet.com
 * @subpackage API app 
 * @file PsCustomerModel.php
 * 
 * @author thangnc
 * @version 1.0 2017/03/17
 */
namespace App\Model;

class PsCustomerModel extends BaseModel {
	
	protected $table = CONST_TBL_PS_CUSTOMER;

	/**
	 * get all device_id by user_id
	 */
	public static function getAllDeviceByUserId($user_id) {
		$users = PsMobileAppsModel::select ( 'id', 'status_used' )->where ( 'user_id', $user_id )->get ();

		return $users;
	}

	/**
	 * Lay thong tin co ban cua truong hoc 
	 * 
	 * @param int $ps_customer_id
	 * @return object
	 **/
	 public static function getInfo($ps_customer_id) {
	 	
	 	$tbl = CONST_TBL_PS_CUSTOMER;
	 	$ps_obj = PsCustomerModel::select ( $tbl . '.id AS ps_customer_id', $tbl.'.year_data', $tbl.'.cache_data', $tbl.'.school_code', $tbl.'.logo' )->where ( $tbl . '.id', '=', $ps_customer_id )->get ()->first ();
		return $ps_obj;
	}
}