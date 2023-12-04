<?php
namespace Api\PsMembers\Model;

//use Illuminate\Database\Eloquent\Model;
use App\Model\BaseModel;
use App\PsUtil\PsDateTime;

class ServiceModel extends BaseModel {

	protected $table = CONST_TBL_SERVICE;

	// lay tat ca dich vu ma hoc sinh dang ky
	public static function getServiceOLD($student_id, $ps_customer_id, $date_at = NULL) {

		$date_at = ($date_at == NULL) ? date('Y-m-d') : $date_at;
		$tbl = CONST_TBL_SERVICE;
		$services = ServiceModel::select($tbl . '.id', $tbl . '.title', 'I.file_name', 'SSD.id as student_service_diary_id')->leftjoin(CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.service_id', '=', $tbl . '.id')
			->leftjoin(CONST_TBL_SERVICE_DETAIL . ' as SD', 'SD.service_id', '=', $tbl . '.id')
			->leftJoin(CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', $tbl . '.ps_image_id')
			->leftJoin(TBL_STUDENT_SERVICE_DIARY . ' as SSD', function ($q) use ($date_at, $student_id, $tbl) {
			$q->on('SSD.service_id', '=', $tbl . '.id')
				->whereDate('SSD.tracked_at', '=', date('Y-m-d', strtotime($date_at)))
				->where('SSD.student_id', $student_id);
		})
			->where($tbl . '.ps_customer_id', '=', $ps_customer_id)
			->where($tbl . '.is_activated', '=', STATUS_ACTIVE)
			->where('SS.ps_service_course_id', NULL)
			->whereNull('SS.delete_at')
			->whereDate('SD.detail_at', '<=', $date_at)
			->whereDate('SD.detail_end', '>=', $date_at)
			->where('SS.student_id', '=', $student_id)
			->distinct()
			->get();
		return $services;
	
	}
	
	/**
	 * Lay dich vu ma hoc sinh dang ky
	 * - Ngày thường: Không hiển thị dịch vụ chỉ dành cho ngày thứ 7
	 * - Thứ 7: Hiển thị ra để có thể chọn hoặc không chọn
	 **/
	public static function getService($student_id, $ps_customer_id, $date_at = NULL) {
		
		$date_at = ($date_at == NULL) ? date('Y-m-d') : $date_at;
		
		$tbl = CONST_TBL_SERVICE;
		
		$services = ServiceModel::select($tbl . '.id', $tbl . '.title', 'I.file_name', 'SSD.id as student_service_diary_id')
		
		->join(TBL_PS_SCHOOL_YEAR. ' as SY', function ($q) use ($tbl) {
			$q->on('SY.id', '=', $tbl . '.ps_school_year_id')
			->where('SY.is_default', STATUS_ACTIVE);
		})
		
		->leftjoin(CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.service_id', '=', $tbl . '.id')
		->leftjoin(CONST_TBL_SERVICE_DETAIL . ' as SD', 'SD.service_id', '=', $tbl . '.id')
		->leftJoin(CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', $tbl . '.ps_image_id')
		->leftJoin(TBL_STUDENT_SERVICE_DIARY . ' as SSD', function ($q) use ($date_at, $student_id, $tbl) {
			$q->on('SSD.service_id', '=', $tbl . '.id')
			->whereDate('SSD.tracked_at', '=', date('Y-m-d', strtotime($date_at)))
			->where('SSD.student_id', $student_id);
		})
		->where($tbl . '.ps_customer_id', '=', $ps_customer_id)
		->where($tbl . '.is_activated', '=', STATUS_ACTIVE)
		->where('SS.ps_service_course_id', NULL)
		->whereNull('SS.delete_at')
		->whereDate('SD.detail_at', '<=', $date_at)
		->whereDate('SD.detail_end', '>=', $date_at)
		->where('SS.student_id', '=', $student_id);
		
		$number_day = PsDateTime::getNumberDayOfDate($date_at);
		
		if ($number_day == '6') {// Nếu là thứ 7
			$services = $services->where($tbl.'.enable_saturday', '=' ,STATUS_ACTIVE);
		} else {
			$services = $services->where($tbl.'.enable_saturday', '!=' ,STATUS_ACTIVE);
		}
		
		$list = $services->distinct()->get();
		
		return $list;
	}

	// lay mon hoc ma giao vien day theo khoa hoc
	public static function getServiceTeacher($course_schedules_id, $ps_customer_id, $ps_member_id, $date_at = NULL) {

		$date_at = ($date_at == NULL) ? date('Y-m-d') : $date_at;
		$tbl = CONST_TBL_SERVICE;
		$services = ServiceModel::select($tbl . '.id as service_id', $tbl . '.title as service_title', 'I.file_name', 'SCS.id as service_courses_scheducles_id')->join(TBL_PS_SERVICE_COURSES . ' as SC', 'SC.ps_service_id', '=', $tbl . '.id')
			->join(TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', 'SCS.ps_service_course_id', '=', 'SC.id')
			->leftJoin(CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', $tbl . '.ps_image_id')
			->whereDate('SCS.date_at', $date_at)
			->whereDate('SC.start_at', '<=', $date_at)
			->whereDate('SC.end_at', '>=', $date_at)
			->where($tbl . '.ps_customer_id', $ps_customer_id)
			->where('SC.is_activated', STATUS_ACTIVE)
			->where($tbl . '.enable_schedule', STATUS_ACTIVE)
			->where('SC.ps_member_id', $ps_member_id)
			->where('SCS.is_activated', STATUS_ACTIVE)
			->where($tbl . '.is_activated', STATUS_ACTIVE)
			->where('SCS.id', $course_schedules_id)
			->get()
			->first();
		return $services;
	
	}

	/**
	 * Lay danh sach dich vu
	 * 
	 * @param 	$arr_service - mixed
	 * @param 	$ps_customer_id - int
	 * @return 	object
	 **/
	public static function getServiceInArrayId($arr_service, $ps_customer_id = null) {
		
		$tbl = CONST_TBL_SERVICE;
		$services = ServiceModel::select($tbl . '.id', $tbl . '.title')
		->whereIn($tbl . '.id',$arr_service)
		->where($tbl . '.ps_customer_id', '=', $ps_customer_id)
		->where($tbl . '.is_activated', '=', STATUS_ACTIVE)
		->get();
		
		return $services;
	}
}