<?php
/**
 * @package truongnet.com
 * @subpackage API app 
 * @file PsAppModel.php
 * 
 * @author thangnc
 * @version 1.0 2017/03/17
 */
namespace App\Model;

class PsMobileAppsModel extends BaseModel {

	protected $table = CONST_TBL_PS_MOBILE_APPS;

	/**
	 * get all device_id by user_id
	 */
	public static function getAllDeviceByUserId($user_id) {

		$users = PsMobileAppsModel::select('id', 'status_used')->where('user_id', $user_id)->get();

		return $users;
	}

	/**
	 * get device by device_id, $user_id and status_used is active
	 */
	public static function getDeviceActived($user_id, $device_id, $status_used) {
		
		$user = PsMobileAppsModel::where('status_used', 1)->where('device_id', $device_id)->where('user_id', $user_id)->first();

		return $user;
	}
}