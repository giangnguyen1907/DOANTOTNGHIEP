<?php
/**
 * @package truongnet.com
 * @subpackage API app 
 * @file PsEmailModel.php
 * 
 * @author thangnc
 * @version 1.0 2017/03/17
 */
namespace App\Model;


class PsEmailModel extends BaseModel {
		
	protected $table = CONST_TBL_PS_EMAILS;
		
	// Check email unique
	public static function checkEmailUnique ($email, $id = null, $obj_type) {
		
		$check = PsEmailModel::select('*')->where('ps_email', $email);
		
		/*
		if ($id > 0) {
			$check->where('obj_id', '!=', $id);
		}
		
		if ($obj_type != '') {
			$check->where('obj_id', '=', $obj_type);
		}*/
		
		$obj = $check->first();
		
		
		
		if (!$obj)
			return true;
		else
			return (($id > 0) && ($obj->obj_id == $id) && ($obj->obj_type == $obj_type));

		//return $obj ? true: false;
	}

	/**
	* getPsEmailByEmail($email)
	* get record by email
	* 
	* @param: $email - string
	* @return: obj
	**/
	public static function getPsEmailByEmail($email) {
		
		return PsEmailModel::where('ps_email', $email)->first();
	}


}