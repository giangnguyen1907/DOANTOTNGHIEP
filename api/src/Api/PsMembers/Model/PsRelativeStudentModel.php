<?php
namespace Api\PsMembers\Model;

use App\Model\BaseModel;

class PsRelativeStudentModel extends BaseModel {

	protected $table = CONST_TBL_RELATIVESTUDENT;

	// lay phu huynh theo hoc sinh
	public static function getRelativeByStudentID($student_id) {

		$tbl = CONST_TBL_RELATIVESTUDENT;
		$relative_student = PsRelativeStudentModel::select ( $tbl . '.relative_id as relative_id', $tbl . '.is_parent_main as is_parent_main', $tbl . '.is_parent as is_parent', 'R.mobile as mobile', 'RS.title as relationship_title', 'R.avatar as avatar','R.year_data' ,'R.ps_customer_id AS ps_customer_id','C.cache_data' )
		->selectRaw ( 'CONCAT(R.first_name," ", R.last_name) AS fullname' )
		->leftJoin ( CONST_TBL_STUDENT . ' as S', 'S.id', '=', $tbl . '.student_id' )
		->leftJoin ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=', $tbl . '.relative_id' )
		->join ( CONST_TBL_PS_CUSTOMER.' as C', 'C.id', '=', 'R.ps_customer_id' )
		->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RS', 'RS.id', '=', $tbl . '.relationship_id' )
		->orderBy ( $tbl.'.iorder')
		->orderBy ( $tbl.'.is_parent', 'DESC')
		->orderBy ( $tbl.'.is_parent_main', 'DESC')		
		->where ( 'S.id', $student_id )->get ();
		return $relative_student;
	}
	
	
	// Lay chi tiet 1 nguoi than
	public static function getRelativeByRelativeID($relative_id) {

		$tbl = CONST_TBL_RELATIVESTUDENT;
		$relative = PsRelativeStudentModel::select ( $tbl . '.relative_id as relative_id', $tbl . '.is_parent_main as is_parent_main', 'R.mobile as mobile', 'RS.title as relationship_title', 'R.avatar as avatar', 'R.year_data', 'C.cache_data', 'R.ps_customer_id AS ps_customer_id' )
		->selectRaw ( 'CONCAT(R.first_name," ", R.last_name) AS fullname' )
		->join ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=', $tbl . '.relative_id' )
		->join ( CONST_TBL_PS_CUSTOMER.' as C', 'C.id', '=', 'R.ps_customer_id' )
		->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RS', 'RS.id', '=', $tbl . '.relationship_id' )
		->where ( 'R.id', $relative_id )->get ()->first ();
		return $relative;
	}

	/**
	 * Ham kiem tra xem member_id co phai la nguoi than cua hoc sinh
	 * 
	 * @author thangnc
	 *        
	 *        
	 */
	public static function checkAccessRelativeInfo($student_id, $ps_member_id) {

		$RS = CONST_TBL_RELATIVESTUDENT;
		
		// Kiem tra co ton tai moi quan he giua User - Student - Nguoi than
		$number_student = PsRelativeStudentModel::select ( 'S.id' )
		->join ( CONST_TBL_STUDENT . ' as S', $RS . '.student_id', '=', 'S.id' )
		->where ( 'S.id', '=', $student_id )
		->where ( $RS.'.relative_id', '=', $ps_member_id )->get ()->count ();
		
		return ($number_student > 0 ) ? true : false;
	}
}