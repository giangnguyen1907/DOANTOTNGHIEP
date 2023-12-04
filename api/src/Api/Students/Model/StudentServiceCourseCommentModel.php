<?php

namespace Api\Students\Model;

//use Illuminate\Database\Eloquent\Model;

use App\Model\BaseModel;

class StudentServiceCourseCommentModel extends BaseModel {
	
	protected $table = TBL_STUDENT_SERVICE_COURSES_COMMENT;
	
	// Lay danh gia/nhan xet cua giao vien danh cho hoc sinh(dich vu)
	public static function getRateService($student_id, $ps_service_course_schedule_id) {
		/*
		$student_service_course_comment = StudentServiceCourseCommentModel::select ('id','note' )		
		->where ( 'student_id', $student_id )
		->where ( 'ps_service_course_schedule_id', $ps_service_course_schedule_id )
		->get()->first();
		return $student_service_course_comment;
		*/
		
		$tbl = TBL_STUDENT_SERVICE_COURSES_COMMENT;
		
		$student_service_course_comment = StudentServiceCourseCommentModel::select ($tbl.'.id',$tbl.'.note','FO.name AS name','FOS.id AS feature_option_feature_id', 'FOS.type as type' )
		
		->join(TBL_FEATURE_OPTION_SUBJECT . ' as FOS', 'FOS.id', '=', $tbl.'.feature_option_subject_id')
		
		->join(TBL_FEATURE_OPTION . ' as FO', 'FO.id', '=', 'FOS.feature_option_id')
		
		->where ( $tbl.'.student_id', $student_id )
		
		->where ( $tbl.'.ps_service_course_schedule_id', $ps_service_course_schedule_id )
		
		->get();
		
		return $student_service_course_comment;
		
		
	}


}