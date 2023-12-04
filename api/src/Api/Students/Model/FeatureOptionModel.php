<?php
namespace Api\Students\Model;

use App\Model\BaseModel;

class FeatureOptionModel extends BaseModel {

	protected $table = TBL_FEATURE_OPTION;

	// lay danh gia hoat dong cua hoc sinh
	public static function getRateFeature($student_id, $feature_id, $date_at, $ps_customer_id) {

		$tbl = TBL_FEATURE_OPTION;
		$feature_option = FeatureOptionModel::select($tbl . '.name', $tbl . '.id', 'SF.note as note', 'FOF.type as type', 'FOF.id as feature_option_feature_id')
		->join(TBL_FEATURE_OPTION_FEATURE . ' as FOF', 'FOF.feature_option_id', '=', $tbl . '.id')
			->join(TBL_STUDENT_FEATURE . ' as SF', 'SF.feature_option_feature_id', '=', 'FOF.id')
			->where('SF.student_id', $student_id)
			->whereDate('SF.tracked_at', $date_at)
			->where('FOF.feature_branch_id', $feature_id)
			->where($tbl . '.is_activated', STATUS_ACTIVE)
			//->whereRaw($tbl . '.ps_customer_id', $ps_customer_id)
		->whereRaw('('.$tbl . '.ps_customer_id = ? OR '.$tbl . '.ps_customer_id IS NULL' . ')', array($ps_customer_id))
		->orderBy('FOF.order_by')
		->get();
		return $feature_option;
	
	}
	// lay danh gia dich vu cua hoc sinh
	public static function getRateServiceCourses($student_id, $feature_id, $date_at, $ps_customer_id) {
	
	    $tbl = TBL_FEATURE_OPTION;
	    $feature_option = FeatureOptionModel::select($tbl . '.name', $tbl . '.id', 'SSCC.note as note', 'FOS.type as type', 'FOS.id as feature_option_feature_id')
	    ->join(TBL_FEATURE_OPTION_SUBJECT . ' as FOS', 'FOS.feature_option_id', '=', $tbl . '.id')
	    ->join(TBL_STUDENT_SERVICE_COURSES_COMMENT . ' as SSCC', 'SSCC.feature_option_subject_id', '=', 'FOS.id')
	    ->join(TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', 'SSCC.ps_service_course_schedule_id', '=', 'SCS.id')
	    ->where('SSCC.student_id', $student_id) 
	    ->where('SSCC.ps_service_course_schedule_id', $feature_id)
	    ->where($tbl . '.is_activated', STATUS_ACTIVE)
	    //->whereRaw($tbl . '.ps_customer_id', $ps_customer_id)
	    ->whereRaw('('.$tbl . '.ps_customer_id = ? OR '.$tbl . '.ps_customer_id IS NULL' . ')', array($ps_customer_id))
	    ->orderBy('FOS.order_by')
	    ->get();
	    return $feature_option;
	
	}
	

	public static function getStudentFeature($student_id) {

		$student_feature = FeatureOptionModel::where('student_id', $student_id)->whereDate('tracked_at', '=', date('Y-m-d'))
			->get()
			->first();
		return $feature_option_feature;
	}
}