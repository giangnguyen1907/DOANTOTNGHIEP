<?php
/**
 * @package			truongnet.com
 * @subpackage     	API app 
 *
 * @file RelativeModel.php
 * @author thangnc
 * @version 1.0 27-02-2017 -  00:51:34
 */
namespace Api\Relatives\Model;

use App\Model\BaseModel;

class RelativeModel extends BaseModel {

	protected $table = CONST_TBL_RELATIVE;
	
	// Check email unique
	public static function checkEmailUnique($email, $id = null) {

		$check = RelativeModel::select ( 'id' )->where ( 'email', $email );
		
		if ($id > 0) {
			$check->where ( 'id', '!=', $id );
		}
		
		$obj = $check->first ();
		
		return $obj ? true : false;
	}
	
	// Lay avatar cua nguoi than
	public static function getAvatar($id) {

		$tbl = CONST_TBL_RELATIVE;
		
		$ps_obj = RelativeModel::select ( $tbl . '.id', $tbl . '.avatar as avatar', $tbl . '.year_data', 'C.cache_data' )->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )->where ( $tbl . '.id', '=', $id )->get ()->first ();
		
		return $ps_obj;
	}
	
	// Thong tin boi id
	public static function getRelativeById($id) {

		$tbl = CONST_TBL_RELATIVE;
		
		$ps_obj = RelativeModel::select ( $tbl . '.*','C.cache_data' )->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )->where ( $tbl . '.id', '=', $id )->get ()->first ();
		
		return $ps_obj;
	}
	
	// Lay danh sach nguoi than co quyen dua don cua be de gui thong bao
	public static function getRelativesByStudentId($student_id) {

		$tbl = CONST_TBL_RELATIVE;
		
		$relatives = RelativeModel::select ( $tbl . '.id AS id', $tbl . '.first_name', $tbl . '.last_name', 'RE.title AS relationship', 'U.notification_token', 'U.osname', 'U.app_config' )
		->join ( CONST_TBL_RELATIVESTUDENT . ' as RS', $tbl . '.id', '=', 'RS.relative_id' )
		->join ( CONST_TBL_USER . ' as U', 'U.member_id', '=', $tbl . '.id' )
		->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id' )
		->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )
		->where ( 'RS.student_id', '=', $student_id )
		->where ( 'U.user_type', '=', USER_TYPE_RELATIVE )
		->where ( 'U.is_active', '=', STATUS_ACTIVE )
		->where ( 'RS.is_parent', '=',STATUS_ACTIVE )
		->distinct($tbl . '.id') ->get ();	
		
		// ->where ( 'U.notification_token', '!=', null )		
		
		return $relatives;
	}

	/**
	 * Lay danh sach nguoi than cua hoc sinh
	 *
	 * @param $student_id -
	 *        	int
	 *        	
	 * @return $list_obj - danh sach nguoi than        
	 */
	public static function getAllRelativeByStudentId($student_id) {

		$tbl = CONST_TBL_RELATIVE;
		$relatives = RelativeModel::select ( $tbl . '.id', $tbl . '.first_name', $tbl . '.last_name', $tbl . '.avatar', $tbl . '.mobile AS phone', $tbl . '.year_data', $tbl . '.ps_customer_id', 'C.cache_data', 'RS.is_parent', 'RE.title AS relationship' )
		->join ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', $tbl . '.id' )
		->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id' )
		->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )
		->where ( 'RS.student_id', '=', $student_id )
		->orderBy ( 'RS.is_parent', 'desc' )
		->orderBy ( 'RE.iorder', 'asc' )->get ();
		return $relatives;
	}

	/**
	 * Lay chi tiet mot nguoi than cua hoc sinh
	 *
	 * @param $relative_id - int
	 * @param $student_id - int
	 * @param $ps_member_id - int       	
	 * @return $list_obj - danh sach nguoi than
	**/
	public static function getRelativeDetailOfStudent($relative_id, $student_id) {

		$R = CONST_TBL_RELATIVE;
		
		$relative = RelativeModel::select ( $R . '.*', 'RE.title as religion', 'E.title as ethnic', 'RS.is_parent', 'RES.title AS relationship', 'C.cache_data' )
		->join ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', $R.'.id' )
		->join ( CONST_TBL_RELATIONSHIP . ' as RES', 'RES.id', '=', 'RS.relationship_id' )
		->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $R . '.ps_customer_id' )
		->leftJoin ( CONST_TBL_PS_ETHNIC . ' as E', 'E.id', '=', $R.'.ethnic_id' )
		->leftJoin ( CONST_TBL_PS_RELIGION . ' as RE', 'RE.id', '=', $R.'.religion_id' )
		->where($R.'.id', $relative_id)
		->where ( 'RS.student_id', '=', $student_id )
		->get ()->first ();
				
		return $relative;
	}
	
	/**
	 * Lay thong tin ngan cua nguoi than cua hoc sinh
	 *
	 * @param $relative_id - int
	 * @param $student_id - int
	 * @param $ps_member_id - int
	 * @return $list_obj - danh sach nguoi than
	 **/
	public static function getRelativeShortOfStudent($relative_id, $student_id) {
		
		$R = CONST_TBL_RELATIVE;
		
		$relative = RelativeModel::select ( $R . '.first_name as first_name', $R . '.last_name as last_name', 'RES.title AS relationship')
		->selectRaw ( 'CONCAT(S.first_name," ", S.last_name) AS student_fullname' )
		->join ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', $R.'.id' )
		->join ( CONST_TBL_STUDENT . ' as S', 'S.id', '=', 'RS.student_id' )
		->join ( CONST_TBL_RELATIONSHIP . ' as RES', 'RES.id', '=', 'RS.relationship_id' )
		->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $R . '.ps_customer_id' )
		->where($R.'.id', $relative_id)
		->where ( 'RS.student_id', '=', $student_id )
		->get ()->first ();
		
		return $relative;
	}
}