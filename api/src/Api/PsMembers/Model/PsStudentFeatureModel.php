<?php
namespace Api\PsMembers\Model;

use App\Model\BaseModel;

class PsStudentFeatureModel extends BaseModel {

	protected $table = TBL_STUDENT_FEATURE;

	/**
	 * Đếm số lượng học sinh được đánh giá của một hoạt dộng theo ngày
	 * 
	 * @param int $ps_customer_id
	 * @param int $mylass_id
	 * @param int $feature_branch_id - ID hoạt động
	 * @param yyyy-mm-dd $tracked_at
	 * 
	**/
    public static function getStudentFeatureCount($ps_customer_id, $mylass_id, $feature_branch_id, $tracked_at) {
        
    	$tbl = TBL_STUDENT_FEATURE;
    	
    	$students = PsStudentFeatureModel::select( $tbl.'.id as id')
				
					->join ( CONST_TBL_STUDENT . ' as S', function ($q) use ($tbl,$tracked_at) {
						$q->on ( $tbl.'.student_id', '=', 'S.id' )
						->whereDate ( $tbl.'.tracked_at', '=', date ( 'Y-m-d', strtotime ( $tracked_at ) ) );
					} )
					
					->join ( TBL_FEATURE_OPTION_FEATURE . ' as FOF', 'FOF.id', '=', $tbl.'.feature_option_feature_id' )
					->join ( TBL_FEATURE_BRANCH . ' as FB', 'FB.id', '=', 'FOF.feature_branch_id' )
					
					->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'S.ps_customer_id' )					
					->join ( CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($tracked_at) {
						$q->on ( 'SC.student_id', '=', 'S.id' )
						->where ( 'SC.is_activated', STATUS_ACTIVE )
						->whereIn ( 'SC.type', [ 
								STUDENT_HT,
								STUDENT_CT 
						] );
						//->whereDate ( 'SC.start_at', '<=', $tracked_at )
						//->whereRaw ( '(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $tracked_at . '", "%Y%m%d") OR SC.stop_at IS NULL )' );
					} )
					->join ( CONST_TBL_MYCLASS . ' as MC', 'MC.id', '=', 'SC.myclass_id' )										
					->where ( 'MC.id', $mylass_id )
					->where ( 'FB.id', $feature_branch_id )
					->whereRaw ( 'S.deleted_at IS NULL' )
					->where ( 'SC.is_activated', STATUS_ACTIVE )
					->where ( 'MC.is_activated', STATUS_ACTIVE )
					->where ( 'S.ps_customer_id', $ps_customer_id )
					->groupBy('S.id')->get()->count();
					
					return $students;
    }

}