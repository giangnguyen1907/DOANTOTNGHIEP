<?php

namespace Api\PsMembers\Model;

use App\Model\BaseModel;

class PsLogtimesModel extends BaseModel {
	
	protected $table = CONST_TBL_PS_LOGTIMES;
	
	// lay logtime theo id hoc sinh va ngay hien tai
	public static function getLogtimeByDate( $student_id, $date_at = null) {
	    
	    $date_at = ($date_at == null) ? date ( 'Y-m-d' ) : $date_at;
	    
		$logtime = PsLogtimesModel::where ('student_id', $student_id )->whereDate ( 'login_at', '=', date ( 'Y-m-d',strtotime($date_at) ) )->get ()->first ();
		
		return $logtime;
	}
	
	// kiem tra xem ngay hom nay diem danh lan nao chua
	public static function checkLogtimeByDate( $str_student_id, $date_at = null) {
	     
	    $date_at = ($date_at == null) ? date ( 'Y-m-d' ) : $date_at;
	     
	    $logtime = PsLogtimesModel::whereIn ('student_id', $str_student_id )->whereDate ( 'login_at', '=', date ( 'Y-m-d',strtotime($date_at) ) )->get ()->first ();
	    
	    return $logtime ? true : false;
	}
	
/**
	 * Đếm học sinh đi học cua mot lop trong mot ngay
	 * 
	 * @param int $mylass_id
	 * @param yyyy-mm-dd $tracked_at
	**/
    public static function getLoginCount($ps_customer_id, $mylass_id, $tracked_at = null) {
        
    	$tbl = CONST_TBL_PS_LOGTIMES;
    	
    	$students = PsLogtimesModel::select( $tbl.'.id as id')
				
					->join ( CONST_TBL_STUDENT . ' as S', function ($q) use ($tbl,$tracked_at) {
						$q->on ( $tbl.'.student_id', '=', 'S.id' )
						->whereDate ( $tbl.'.login_at', '=', date ( 'Y-m-d', strtotime ( $tracked_at ) ) );
					} )				
					->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'S.ps_customer_id' )					
					->join ( CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($tracked_at) {
						$q->on ( 'SC.student_id', '=', 'S.id' )
						->where ( 'SC.is_activated', STATUS_ACTIVE )
						->whereIn ( 'SC.type', [ 
								STUDENT_HT,
								STUDENT_CT 
						] )
						->whereDate ( 'SC.start_at', '<=', $tracked_at )
						->whereRaw ( '(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $tracked_at . '", "%Y%m%d") OR SC.stop_at IS NULL )' );
					} )
					->join ( CONST_TBL_MYCLASS . ' as M', 'M.id', '=', 'SC.myclass_id' )										
					->where ( 'M.id', $mylass_id )
					->whereNull ( 'S.deleted_at' )
					->where ( $tbl.'.log_value', CONSTANT_LOGVALUE_1)
					->where ( 'SC.is_activated', STATUS_ACTIVE )
					->where ( 'M.is_activated', STATUS_ACTIVE )
					->where ( 'S.ps_customer_id', $ps_customer_id )
					->groupBy('S.id')
					->orderBy ( 'S.last_name' )->get();
					
		return $students;
    }
    
    /**
	 * Đếm học sinh duoc diem danh ve
	 * 
	 * @param int $mylass_id
	 * @param yyyy-mm-dd $tracked_at
	**/
    public static function getLogoutCount($ps_customer_id, $mylass_id, $tracked_at = null) {
        
    	$tbl = CONST_TBL_PS_LOGTIMES;
    	
    	$students = PsLogtimesModel::select( $tbl.'.id as id')
				
					->join ( CONST_TBL_STUDENT . ' as S', function ($q) use ($tbl,$tracked_at) {
						$q->on ( $tbl.'.student_id', '=', 'S.id' )
						->whereDate ( $tbl.'.login_at', '=', date ( 'Y-m-d', strtotime ( $tracked_at ) ) );
					} )				
					->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'S.ps_customer_id' )					
					->join ( CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($tracked_at) {
						$q->on ( 'SC.student_id', '=', 'S.id' )
						->where ( 'SC.is_activated', STATUS_ACTIVE )
						->whereIn ( 'SC.type', [ 
								STUDENT_HT,
								STUDENT_CT 
						] )
						->whereDate ( 'SC.start_at', '<=', $tracked_at )
						->whereRaw ( '(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $tracked_at . '", "%Y%m%d") OR SC.stop_at IS NULL )' );
					} )
					->join ( CONST_TBL_MYCLASS . ' as M', 'M.id', '=', 'SC.myclass_id' )										
					->where ( 'M.id', $mylass_id )
					->whereRaw ( 'S.deleted_at IS NULL' )
					->where ( 'SC.is_activated', STATUS_ACTIVE )
					->where ( 'M.is_activated', STATUS_ACTIVE )
					->where ( 'S.ps_customer_id', $ps_customer_id )
					
					->where ( $tbl.'.log_value', CONSTANT_LOGVALUE_1)
					->whereRaw ( $tbl.'.logout_at IS NOT NULL')
					/*
					->where ( $tbl.'.logout_relative_id','>=' , 1 )
					->where ( $tbl.'.logout_member_id','>=' , 1 )*/
					
					->groupBy('S.id')
					->orderBy ( 'S.last_name' )->get();
			
		return $students;
    }

}