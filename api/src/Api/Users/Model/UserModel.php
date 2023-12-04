<?php

/**
 * @package			truongnet.com
 * @subpackage     	API app 
 *
 * @file UserModel.php
 * @author thangnc
 * @version 1.0 27-02-2017 -  00:51:34
 */
namespace Api\Users\Model;

use App\Model\BaseModel;
use Api\Relatives\Model\RelativeModel;
use Api\PsMembers\Model\PsMemberModel;

class UserModel extends BaseModel {
	protected $table = CONST_TBL_USER;

	/**
	 * Get the phone record associated with the user.
	 */
	public function PsCustomer() {
		return $this->hasOne ( 'App\Model\PsCustomerModel' );
	}

	/**
	 * get user by user_name or email
	 */
	public static function getUserByUserNameOrEmail($user_name_email) {
		$user = UserModel::where ( 'is_active', USER_ACTIVE )->where ( function ($q) use ($user_name_email) {
			$q->where ( 'username', $user_name_email )->orWhere ( 'email_address', $user_name_email );
		} )->first ();

		// UserModel::where('is_active', USER_ACTIVE)->whereRaw('(username = ? OR email_address = ?', $user_name_email)->first();

		return $user;
	}

	/**
	 * get user by user_name and app_code
	 */
	public static function getUserByUserNameAndAppCode($user_name, $app_code) {
		return UserModel::where ( 'is_active', USER_ACTIVE )->where ( 'user_type', $app_code )->where ( 'username', $user_name )->first ();
	}

	/**
	 * Search user on database by authorization api_token
	 */
	public static function getUserByToken($api_token) {
		return UserModel::where ( 'is_active', USER_ACTIVE )->where ( 'api_token', $api_token )->first ();
	}

	/**
	 * Search user on database by authorization api_token
	 */
	public static function getUserColumnByToken($api_token) {
		/*
		 * return UserModel::select('id')->where('is_active', USER_ACTIVE)
		 * ->where('api_token', $api_token)
		 * ->first();
		 */
		return UserModel::where ( 'is_active', USER_ACTIVE )->where ( 'api_token', $api_token )->first ();
	}

	/**
	 * get user by user_name
	 *
	 * @author thangnc@newwaytech.vn
	 *        
	 * @param $user_name - string
	 * @return mixed
	 */
	public static function getUserByUserName($user_name) {
		return UserModel::where ( 'is_active', USER_ACTIVE )->where ( 'username', $user_name )->first ();
	}

	// Check email unique
	public static function checkEmailUnique($email, $id = null) {
		$check = UserModel::select ( 'id' )->where ( 'email', $email );

		if ($id > 0) {
			$check->where ( 'id', '!=', $id );
		}

		$obj = $check->first ();

		return $obj ? true : false;
	}

	/**
	 * get user by user_id
	 *
	 * @author thien
	 *        
	 * @param user_id - int
	 * @return mixed
	 */
	public static function getUserByUserId($user_id) {
		return UserModel::where ( 'is_active', USER_ACTIVE )->where ( 'id', $user_id )->first ();
	}

	/**
	 * get user by user_id
	 *
	 * @author thien
	 *        
	 * @param $user_id -
	 *        	array
	 * @return mixed
	 */
	public static function getUserByArrayUserId($user_id, $flag_limit = true) {
		if ($flag_limit) {
			return UserModel::select ( 'id', 'first_name', 'last_name', 'notification_token', 'osname', 'osvesion' )->where ( 'is_active', USER_ACTIVE )->whereIn ( 'id', $user_id )->forPage ( 1, 3 )->get ();
		} else {
			return UserModel::select ( 'id', 'first_name', 'last_name', 'notification_token', 'osname', 'osvesion' )->where ( 'is_active', USER_ACTIVE )->whereIn ( 'id', $user_id )->get ();
		}
	}

	// public static function getUserByArrayUserId2($user_id) {
	// 		return UserModel::select( 'id', 'first_name', 'last_name', 'notification_token', 'osname', 'osvesion' )->where( 'id', $user_id )->get();	
	// }

	/**
	 * get avatar by user_id and user_type
	 *
	 * @author thien
	 *        
	 * @param
	 *        	user_id - int, user_type - string
	 * @return mixed
	 */
	public static function getUserAvatarByUserId($user_id, $user_type) {
		$tbl = CONST_TBL_USER;
		if ($user_type == USER_TYPE_RELATIVE) {
			$user = UserModel::select ( $tbl . '.id as user_id', $tbl . '.user_type', 'R.avatar as avatar', 'R.year_data', 'C.school_code', 'C.cache_data' )->selectRaw ( 'CONCAT(' . $tbl . '.first_name," ", ' . $tbl . '.last_name) AS fullname' )->leftjoin ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=', $tbl . '.member_id' )->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )->where ( $tbl . '.id', $user_id )->where ( $tbl . '.user_type', $user_type )->where ( $tbl . '.is_active', STATUS_ACTIVE )->get ()->first ();
		} else if ($user_type == USER_TYPE_TEACHER) {
			$user = UserModel::select ( $tbl . '.id as user_id', $tbl . '.user_type', 'M.avatar as avatar', 'M.year_data', 'C.school_code', 'C.cache_data' )->selectRaw ( 'CONCAT(' . $tbl . '.first_name," ", ' . $tbl . '.last_name) AS fullname' )->leftjoin ( CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', $tbl . '.member_id' )->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )->where ( $tbl . '.id', $user_id )->where ( $tbl . '.user_type', $user_type )->where ( $tbl . '.is_active', STATUS_ACTIVE )->get ()->first ();
		}
		return $user;
	}

	/**
	 * get user by user_key - su dung trong check key chat
	 *
	 * @author thangnc
	 *        
	 * @param $user_key :
	 *        	string
	 * @return mixed
	 */
	public static function getUserByUserKey($user_key) {
		
		$u = CONST_TBL_USER;

		$user = UserModel::select ( $u . '.id', $u . '.first_name', $u . '.last_name', $u . '.notification_token', $u . '.osname', $u . '.osvesion', $u . '.app_config', $u . '.user_type', $u . '.member_id', 'C.school_code', 'C.cache_data', 'C.logo', 'C.year_data' )->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $u . '.ps_customer_id' )->where ( $u . '.is_active', USER_ACTIVE )->whereRaw ( "SHA2(" . $u . ".id,256) = ?", $user_key )->first ();

		if ($user->user_type == USER_TYPE_RELATIVE) {
			$obj = RelativeModel::getAvatar ( $user->member_id );
		} elseif ($user->user_type == USER_TYPE_TEACHER) {
			$obj = PsMemberModel::getAvatar ( $user->member_id );
		}

		$user->avatar = $obj->avatar;
		$user->user_year_data = $obj->year_data;
        $user->logo = $obj->logo;

        return $user;
    }
    
    /**
     * Ham lay danh sach user nguoi than de gui notication
    **/
    public static function getUserRelativeInfo ($ps_customer_id, $ps_class_id) {
    	
    	if ($ps_customer_id <= 0 || $ps_class_id <= 0)
    		return  null;
    	
    	$u = CONST_TBL_USER;
    	
    	$date_at = date("Y-m-d");
    	
    	$list = UserModel::select ( $u.'.id as user_id', $u.'.user_type', $u.'.notification_token', $u.'.osname', $u.'.app_config', 'R.avatar as avatar', 'R.year_data', 'C.cache_data', 'RE.title AS re_title', 'S.last_name AS student_name', 'S.id as student_id' )
    	->selectRaw ( 'CONCAT(R.first_name," ", R.last_name) AS fullname' )    	
    	->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $u.'.ps_customer_id' )
    	->join ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=', $u.'.member_id' )
    	->join ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', 'R.id' )
    	->join ( CONST_TBL_STUDENT . ' as S', 'RS.student_id', '=', 'S.id' )
    	->join ( CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id' )
    	->join ( CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($date_at) {
    		$q->on ( 'SC.student_id', '=', 'S.id' )
    		->where ( 'SC.is_activated', STATUS_ACTIVE )
    		->whereIn ( 'SC.type', [STUDENT_HT,STUDENT_CT])
    		->whereDate ( 'SC.start_at', '<=', $date_at )
    		->whereRaw ( '(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $date_at . '", "%Y%m%d") OR SC.stop_at IS NULL )' );
    	} )
    	->where ( $u.'.ps_customer_id', $ps_customer_id)
    	->where ( $u.'.user_type', USER_TYPE_RELATIVE )
    	->where ( $u.'.is_active', STATUS_ACTIVE )
    	->where( 'SC.myclass_id', $ps_class_id )
    	->whereRaw ( 'S.deleted_at IS NULL')
    	->groupby ($u.'.id' )->distinct ()->get ();
    	
    	return $list;
    }
	
	public static function getUserRelativeInfo2($ps_customer_id, $student_id){
    	
    	if ($ps_customer_id <= 0){
    		return false;
    	}
    	
    	$u = CONST_TBL_USER;
    	
    	$date_at = date("Y-m-d");
    	 	
    	$list = UserModel::select ( $u.'.id as user_id', $u.'.user_type', $u.'.notification_token', $u.'.osname', $u.'.app_config', 'R.avatar as avatar', 'R.year_data', 'C.cache_data', 'RE.title AS re_title', 'S.last_name AS student_name', 'S.id as student_id' )
    	->selectRaw ( 'CONCAT(R.first_name," ", R.last_name) AS fullname' )    	
    	->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $u.'.ps_customer_id' )
    	->join ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=', $u.'.member_id' )
    	->join ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', 'R.id' )
    	->join ( CONST_TBL_STUDENT . ' as S', 'RS.student_id', '=', 'S.id' )
    	->join ( CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id' )
    	->join ( CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($date_at) {
    		$q->on ( 'SC.student_id', '=', 'S.id' )
    		->where ( 'SC.is_activated', STATUS_ACTIVE )
    		->whereIn ( 'SC.type', [STUDENT_HT,STUDENT_CT])
    		->whereDate ( 'SC.start_at', '<=', $date_at )
    		->whereRaw ( '(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $date_at . '", "%Y%m%d") OR SC.stop_at IS NULL )' );
    	} )
    	->where ( $u.'.ps_customer_id', $ps_customer_id)
    	->where ( $u.'.user_type', USER_TYPE_RELATIVE )
    	->where ( $u.'.is_active', STATUS_ACTIVE )
		->where ('S.id',$student_id)
    	->whereRaw ( 'S.deleted_at IS NULL')
    	->groupby ($u.'.id' )->distinct()->get ();
    	return $list;
    }
}