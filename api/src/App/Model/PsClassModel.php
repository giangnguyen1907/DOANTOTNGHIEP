<?php
/**
 * @package
 * @subpackage API app
 *
 * @file PsClassModel.php
 *
 * @author thangnc
 * @version 1.0 2017/03/17
 */
namespace App\Model;

class PsClassModel extends BaseModel {
	
	protected $table = CONST_TBL_MYCLASS;
	
	/**
	 * Lay lop
	 **/
	public static function getClassById($ps_customer_id, $ps_myclass_id) {
	
		$tbl = CONST_TBL_MYCLASS;
	
		$ps_obj = PsClassModel::select ( $tbl. '.id as class_id', $tbl . '.name as class_name', $tbl . '.ps_workplace_id as ps_workplace_id')
		->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )
		->where ( $tbl.'.ps_customer_id', $ps_customer_id )
		->where ( $tbl.'.id', $ps_myclass_id )
		->where ( $tbl.'.is_activated', STATUS_ACTIVE )
		->get ()->first ();
	
		return $ps_obj;
	}
	
	/**
	 * Lay lop duoc phan cong cua giao vien tai thoi diem date_at
	 **/
	public static function getClassOfMember($ps_member_id, $date_at = null) {
		
		$tbl = CONST_TBL_MYCLASS;
		
		$curr_day = ($date_at == null) ? date ( 'Y-m-d' ) : $date_at;
		
		$ps_obj = PsClassModel::select ( $tbl. '.id as class_id', $tbl . '.name as class_name', $tbl . '.ps_workplace_id as ps_workplace_id')
		->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )
		->join(TBL_PS_TEACHER_CLASS . ' as TC', function ($q) use ($curr_day, $tbl) {
			$q->on('TC.ps_myclass_id', '=', $tbl . '.id')
			->whereDate('TC.start_at', '<=', $curr_day)
			->whereRaw('(DATE_FORMAT(TC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $curr_day . '", "%Y%m%d") OR TC.stop_at IS NULL )');
		})
		->where ( 'TC.ps_member_id', '=', $ps_member_id )
		->where ( $tbl.'.is_activated', STATUS_ACTIVE )
		->get ()->first ();
		
		return $ps_obj;
	}
	
	/**
	 * Lay danh sach lop hoc cua co so 
	**/
	public static function getListMyClassOfWorkPlace($ps_customer_id,$ps_workplace_id) {
		
		$tbl = CONST_TBL_MYCLASS;
		
		$ps_obj = PsClassModel::select ( $tbl. '.id as class_id', $tbl . '.name as class_name', $tbl . '.ps_workplace_id as ps_workplace_id')
		->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )
		->join ( TBL_PS_SCHOOL_YEAR . ' as SY', 'SY.id', '=', $tbl . '.school_year_id' )
		->where ( 'SY.is_default', STATUS_ACTIVE )
		->where ( $tbl.'.is_activated', STATUS_ACTIVE )		
		->where ( $tbl.'.ps_customer_id', $ps_customer_id )
		->where ( $tbl.'.ps_workplace_id', $ps_workplace_id )
		->orderBy ( $tbl.'.name' )->get ();
		
		return $ps_obj;
	}
	
}