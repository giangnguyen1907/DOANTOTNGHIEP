<?php

namespace Api\PsMembers\Controller;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Controller\BaseController;
use Exception;
use Respect\Validation\Validator as vali;
use Api\PsMembers\Model\PsMemberModel;
use Api\PsMembers\Model\PsLogtimesModel;
use App\PsUtil\PsFile;
use App\PsUtil\PsString;
use App\PsUtil\PsDateTime;
use Api\PsMembers\Model\PsRelativeStudentModel;
use Api\PsMembers\Model\ServiceModel;
use Api\PsMembers\Model\PsAttendancesSyntheticModel;
use Api\PsMembers\Model\PsStudentFeatureModel;
use Api\PsMembers\Model\PsFeatureBranchSyntheticModel;

use Api\Students\Model\FeatureOptionModel;
use Api\PsMembers\Model\FeatureOptionFeatureModel;
use Api\PsMembers\Model\FeatureOptionSubjectModel;
use Api\Students\Model\StudentServiceCourseCommentModel;
use Api\Relatives\Model\RelativeModel;
use Api\Students\Model\StudentModel;
use App\PsUtil\PsI18n;
use App\PsUtil\PsNotification;
use App\Model\PsClassModel;
use App\Model\PsWorkPlacesModel;
use Slim\Http\StatusCode;

class TeacherController extends BaseController
{

	public $container;

	protected $user_token;

	public function __construct(LoggerInterface $logger, $container, $app)
	{

		parent::__construct($logger, $container);

		$this->user_token = $app->user_token;
	}

	// Trang index - hien thi danh sach hoc sinh
	public function home(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$return_data = array();
		$return_data['_msg_code'] 	= MSG_CODE_FALSE;
		$return_data['_data'] 		= [];

		//$return_data ['_msg_text'] 	= $return_data ['message'] = 'FALSE';

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$curr_day = date('Y-m-d');
		// $ps_member = PsMemberModel::getMember($user->member_id);
		// $class_info = [];

		// foreach ($ps_member as $member) {
		// 	$class_info[] = $member->myclass_id;
		// }

		// $return_data['_data']['class_info'] = $class_info;

		try {

			if ($user && ($user->user_type == USER_TYPE_TEACHER)) {

				$return_data['_msg_code'] = MSG_CODE_TRUE;

				$ps_member = PsMemberModel::getMember2($user->member_id);

				if ($ps_member) {
					$class_info = [];

					foreach ($ps_member as $member) {
						$class = new \stdClass();
						$class->class_id = $member->myclass_id;
						$class->class_name = $member->myclass_name;
						$class->number_student = $member->number_student;
						$class->date_at = PsDateTime::toFullDayInWeek($curr_day, $code_lang);
						$class_info[] = $class;
					}

					// Trả về kết quả lặp qua
					$return_data['_data']['class_info'] = $class_info;


					// if ($ps_member->myclass_id > 0) {

					// 	// Danh sach hoc sinh trong lop
					// 	$students = $this->db->table(CONST_TBL_STUDENT . ' as S')
					// 		->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'S.ps_customer_id')
					// 		->leftJoin(CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($curr_day) {
					// 			$q->on('SC.student_id', '=', 'S.id')
					// 				->where('SC.is_activated', STATUS_ACTIVE)
					// 				->whereIn('SC.type', [
					// 					STUDENT_HT,
					// 					STUDENT_CT
					// 				])
					// 				->whereDate('SC.start_at', '<=', $curr_day)
					// 				->whereRaw('(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $curr_day . '", "%Y%m%d") OR SC.stop_at IS NULL )');
					// 		})
					// 		->join(CONST_TBL_MYCLASS . ' as M', 'M.id', '=', 'SC.myclass_id')
					// 		->leftJoin(CONST_TBL_PS_LOGTIMES . ' as LT', function ($q) use ($curr_day) {
					// 			$q->on('LT.student_id', '=', 'S.id')
					// 				->whereDate('LT.login_at', '=', date('Y-m-d', strtotime($curr_day)))
					// 				->where('LT.log_value', STATUS_ACTIVE);
					// 		})
					// 		->select(
					// 			'S.id as student_id',
					// 			'S.birthday as birthday',
					// 			'S.first_name as first_name',
					// 			'S.last_name as last_name',
					// 			'S.avatar as avatar',
					// 			'S.year_data',
					// 			'S.sex as sex',
					// 			'C.cache_data',
					// 			'S.ps_customer_id as ps_customer_id',
					// 			'LT.id AS logtime_id',
					// 			'LT.log_value AS log_value'
					// 		)
					// 		->where('M.id', $ps_member->myclass_id)
					// 		->whereRaw('S.deleted_at IS NULL')
					// 		->where('SC.is_activated', STATUS_ACTIVE)
					// 		->where('M.is_activated', STATUS_ACTIVE)
					// 		->where('S.ps_customer_id', $ps_member->ps_customer_id)
					// 		->orderBy('S.last_name')->distinct()->get();

					// 	$data 		= array();
					// 	$absent 	= 0; // dem so hoc sinh vang mat

					// 	$class_info->number_student = count($students);

					// 	foreach ($students as $student) {

					// 		$data_info  = array();

					// 		$data_info['student_id'] = $student->student_id;

					// 		//$data_info ['avatar_url'] = ($student->avatar != '') ? PsString::getUrlMediaAvatar ( $student->cache_data, $student->year_data, $student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR_STUDENT;

					// 		if ($student->avatar != '') {

					// 			$avatar_url = PsString::getUrlMediaAvatar($student->cache_data, $student->year_data, $student->avatar, MEDIA_TYPE_STUDENT);

					// 			/*
					// 			if (!PsFile::urlExists($avatar_url)) {
					// 				if ($student->sex == STATUS_ACTIVE) {
					// 					$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE.'boy_avatar_default.png';
					// 				} else {
					// 					$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE.'girl_avatar_default.png';
					// 				}
					// 			}
					// 			*/
					// 		} else {
					// 			if ($student->sex == STATUS_ACTIVE) {
					// 				$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE . 'boy_avatar_default.png';
					// 			} else {
					// 				$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE . 'girl_avatar_default.png';
					// 			}
					// 		}

					// 		$data_info['avatar_url'] = $avatar_url;

					// 		$data_info['age'] 		  = PsDateTime::getAgeMonth($student->birthday, $curr_day, true, $code_lang);
					// 		$data_info['first_name'] = $student->first_name;
					// 		$data_info['last_name']  = $student->last_name;

					// 		$status 				  = ($student->log_value == CONSTANT_LOGVALUE_1) ? 1 : 0;

					// 		$data_info['status'] 	  = $status;
					// 		$absent = ($status == 0) ? $absent + 1 : $absent;
					// 		array_push($data, $data_info);
					// 	}

					// 	$class_info->absent = $absent;

					// 	$class_info->attendance = $class_info->number_student - $absent;

					// 	$class_info->number_student = (string) count($students);
					// 	array_push($class_data, $class_info);
					// 	$return_data['_data']['class_info'] = $class_data;

					// 	// $return_data['_data']['data_info'] = $data;
					// } else { // Neu khong phai giao viên cố dinh cua lop thi tim danh sach hoc sinh cua khoa hoc
					// 	// Tìm khóa học cận giờ nhất mà giáo viên phụ trách
					// }
				} else {

					$class_info = new \stdClass();

					$class_info->class_id 		= 0;

					$class_info->class_name 	= $psI18n->__('You are not assigned to any class.');

					$class_info->date_at 		= PsDateTime::toFullDayInWeek($curr_day, $code_lang);

					$class_info->number_student = 0;
					$class_info->absent 		= 0;
					$class_info->attendance 	= 0;

					$return_data['_data']['class_info'] 	= $class_info;

					$return_data['_data']['data_info']  	= array();

					$return_data['_msg_text'] = $return_data['message'] = $psI18n->__('You are not assigned to any class.');
				}
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['_msg_text'] = $return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		//$login_count = PsLogtimesModel::getLoginCount(133,398,date("Y-m-d"));

		//$return_data ['_data'] ['login_count']  	= count($login_count);

		return $response->withJson($return_data);
	}

	// Hien thi danh sach hoc sinh diem danh theo lop
	public function attendancefastStudent(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$return_data = array();
		$return_data['_msg_code'] 	= MSG_CODE_FALSE;
		$return_data['_data'] 		= [];

		//$return_data ['_msg_text'] 	= $return_data ['message'] = 'FALSE';

		$user = $this->user_token;

		//$this->WriteLog ( '--BEGIN: DANH SACH HOC SINH DANH DIEM DANH THEO LOP --' );
		//$this->WriteLog ( "USER ID: " . $user->id);
		//$this->WriteLog ( "USER ID: " . $user->username);
		//$this->WriteLog ( "USER ID: " . $user->user_type);

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$queryParams = $request->getQueryParams();

		$attendance_type = isset($queryParams['attendancetype']) ? $queryParams['attendancetype'] : '';
		$class_id = isset($queryParams['class_id']) ? $queryParams['class_id'] : '';

		$this->WriteLog("KIEU ĐIEM DANH: " . $attendance_type);

		if ($attendance_type != 'in' && $attendance_type != 'out') {

			return $response->withJson($return_data);
		}

		$curr_day = date('Y-m-d');

		try {

			if ($user->user_type == USER_TYPE_TEACHER) {

				$ps_member = PsMemberModel::getMember($user->member_id, null, $class_id);
				//return $ps_member;
				if ($ps_member) {

					$attendance_info = new \stdClass();

					$attendance_info->teacher_id = (int)$ps_member->id;

					$attendance_info->teacher_name = (string)$ps_member->first_name.' '.(string)$ps_member->last_name; 
                    
                    $attendance_info->avatar = (string)($ps_member->avatar != '') ? PsString::getUrlMediaAvatar($ps_member->cache_data, $ps_member->s_year_data, $ps_member->avatar, MEDIA_TYPE_TEACHER) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR_STUDENT;

					$attendance_info->school_name = (string)$ps_member->school_name;

					$attendance_info->date_at 	 = date('d-m-Y', strtotime($curr_day));
					// $return_data['_data']['attendance_info'] = $attendance_info;
					$attendance_info->class_id 	 = (string)$ps_member->myclass_id;

					$attendance_info->class_name = (string) $ps_member->myclass_name;

					$attendance_info->number_student = (string)$ps_member->number_student;


					//$this->WriteLog ( "CLASS ID: " . $ps_member->myclass_id);
					//$this->WriteLog ( "CLASS NAME: " . $ps_member->myclass_name);					
					//$this->WriteLog ( '--END: DANH SACH HOC SINH DANH DIEM DANH THEO LOP --' );

					$ps_workplace_id = ($ps_member->ps_workplace_id > 0) ? $ps_member->ps_workplace_id : $ps_member->member_workplace_id;

					$ps_work_places  = PsWorkPlacesModel::getColumnById($ps_workplace_id, 'config_class_late,config_default_logout,config_choose_attendances_relative');

					// Bât buoc chon nguoi dua don ko
					$config_choose_attendances_relative = 0;

					if ($ps_work_places) {

						$config_choose_attendances_relative = $ps_work_places->config_choose_attendances_relative;
					}

					if ($attendance_type == 'in') {

						$students = $this->db->table(CONST_TBL_STUDENT . ' as S')
							->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'S.ps_customer_id')
							->join(CONST_TBL_STUDENTCLASS . ' as SC', 'SC.student_id', '=', 'S.id')
							->join(CONST_TBL_MYCLASS . ' as M', 'M.id', '=', 'SC.myclass_id')
							->leftJoin(CONST_TBL_PS_LOGTIMES . ' as LT', function ($q) use ($curr_day) {
								$q->on('LT.student_id', '=', 'S.id')
									->whereDate('LT.login_at', '=', date('Y-m-d', strtotime($curr_day)));
							})->select('S.id as student_id','S.student_code as student_code', 'S.birthday as birthday', 'S.first_name as first_name', 'S.last_name as last_name', 'S.avatar as avatar', 'S.year_data', 'C.cache_data', 'S.ps_customer_id as ps_customer_id', 'LT.id as logtime_id', 'LT.login_at as login_at', 'LT.logout_at as logout_at', 'LT.login_relative_id as login_relative_id', 'LT.logout_relative_id as logout_relative_id', 'LT.log_value AS log_value', 'LT.log_code AS log_code')
							->where('M.id', $ps_member->myclass_id)
							->whereIn('SC.type', [
								STUDENT_HT,
								STUDENT_CT
							])
							->whereRaw('S.deleted_at IS NULL')
							->whereDate('SC.start_at', '<=', $curr_day)
							->whereRaw('(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $curr_day . '", "%Y%m%d") OR SC.stop_at IS NULL )')
							->where('SC.is_activated', STATUS_ACTIVE)
							->where('M.is_activated', STATUS_ACTIVE)
							->where('S.ps_customer_id', $ps_member->ps_customer_id)
							->orderBy('S.last_name')->get();
					} elseif ($attendance_type == 'out') {

						$is_config_class_late_show = false;

						if ($ps_work_places && $ps_work_places->config_class_late == STATUS_ACTIVE) {

							$return_data['_data']['is_config_class_late_show_1'] = $is_config_class_late_show;

							$config_default_logout_time = $ps_work_places->config_default_logout;

							$config_time  = date("Y-m-d H:i", strtotime(date('Y-m-d') . ' ' . $config_default_logout_time));

							$current_time = date("Y-m-d H:i", time());

							if (strtotime($current_time) > strtotime($config_time))
								$is_config_class_late_show = true;
						}

						$is_config_class_late_show = false;

						if (!$is_config_class_late_show) {

							$students = $this->db->table(CONST_TBL_STUDENT . ' as S')
								->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'S.ps_customer_id')
								->join(CONST_TBL_STUDENTCLASS . ' as SC', 'SC.student_id', '=', 'S.id')
								->join(CONST_TBL_MYCLASS . ' as M', 'M.id', '=', 'SC.myclass_id')
								->join(CONST_TBL_PS_LOGTIMES . ' as LT', function ($q) use ($curr_day) {
									$q->on('LT.student_id', '=', 'S.id')
										->whereDate('LT.login_at', '=', date('Y-m-d', strtotime($curr_day)));
								})

								->select('S.id as student_id','S.student_code as student_code', 'S.birthday as birthday', 'S.first_name as first_name', 'S.last_name as last_name', 'S.avatar as avatar', 'S.year_data', 'C.cache_data', 'S.ps_customer_id as ps_customer_id', 'LT.id as logtime_id', 'LT.login_at as login_at', 'LT.logout_at as logout_at', 'LT.login_relative_id as login_relative_id', 'LT.logout_relative_id as logout_relative_id', 'LT.log_value AS log_value', 'LT.log_code AS log_code')
								->where('M.id', $ps_member->myclass_id)
								->whereIn('SC.type', [STUDENT_HT, STUDENT_CT])
								->whereRaw('S.deleted_at IS NULL')
								->whereDate('SC.start_at', '<=', $curr_day)
								->whereRaw('(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $curr_day . '", "%Y%m%d") OR SC.stop_at IS NULL )')
								->where('SC.is_activated', STATUS_ACTIVE)
								->where('M.is_activated', STATUS_ACTIVE)
								->where('S.ps_customer_id', $ps_member->ps_customer_id)
								->where('LT.log_value', '=', CONSTANT_LOGVALUE_1)
								->orderBy('S.last_name')->get();

							$student_out_avatar_url = PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR_STUDENT_NO_OUT;
						} else { // Lop ve muon

							$students = $this->db->table(CONST_TBL_STUDENT . ' as S')
								->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'S.ps_customer_id')
								->join(CONST_TBL_STUDENTCLASS . ' as SC', 'SC.student_id', '=', 'S.id')
								->join(CONST_TBL_MYCLASS . ' as M', 'M.id', '=', 'SC.myclass_id')
								->join(CONST_TBL_PS_LOGTIMES . ' as LT', function ($q) use ($curr_day) {
									$q->on('LT.student_id', '=', 'S.id')
										->whereDate('LT.login_at', '=', date('Y-m-d', strtotime($curr_day)));
								})

								->select('S.id as student_id','S.student_code as student_code', 'S.birthday as birthday', 'S.first_name as first_name', 'S.last_name as last_name', 'S.avatar as avatar', 'S.year_data', 'C.cache_data', 'S.ps_customer_id as ps_customer_id', 'LT.id as logtime_id', 'LT.login_at as login_at', 'LT.logout_at as logout_at', 'LT.login_relative_id as login_relative_id', 'LT.logout_relative_id as logout_relative_id', 'LT.log_value AS log_value', 'LT.log_code AS log_code')
								->where('M.ps_workplace_id', $ps_workplace_id)
								->whereIn('SC.type', [STUDENT_HT, STUDENT_CT])
								->whereRaw('S.deleted_at IS NULL')
								->whereDate('SC.start_at', '<=', $curr_day)
								->whereRaw('(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $curr_day . '", "%Y%m%d") OR SC.stop_at IS NULL )')
								->where('SC.is_activated', STATUS_ACTIVE)
								->where('M.is_activated', STATUS_ACTIVE)
								->where('S.ps_customer_id', $ps_member->ps_customer_id)
								->where('LT.log_value', '=', CONSTANT_LOGVALUE_1)
								//->whereRaw ( 'LT.logout_at IS NULL' )
								->orderBy('S.last_name')->get();

							// Đổi tên lớp
							$attendance_info->class_name = $psI18n->__('Late');

							$student_out_avatar_url = PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR_STUDENT_NO_OUT;
						}
					}

					$data_info = $str_student_id = $data = $relatives_info = $relative_info = array();

					$check = false;
					$absent = 0; // dem so hoc sinh vang mat
                    $dihoc = 0; 
                    $nghicophep = 0;
                    $nghikophep = 0;
                    $muon = 0;
					$attendance_info->number_student = count($students);

					foreach ($students as $student) {
						array_push($str_student_id, $student->student_id);
					}

					// Kiem tra xem ngay hom nay diem danh lan nao chua. neu chua diem danh status cua tat ca hoc sinh = 1
					$check = PsLogtimesModel::checkLogtimeByDate($str_student_id);

					if ($attendance_type == 'in') {

						$begin_time = date("H:i");

						foreach ($students as $student) 	{ 
                            if($student->log_code=="Đi học") $dihoc ++;
                             if($student->log_code=="Muộn") $muon ++;
                              if($student->log_code=="Có phép") $nghicophep ++;
                               if($student->log_code=="Không phép") $nghikophep ++;
							$relatives_student = PsRelativeStudentModel::getRelativeByStudentID($student->student_id); // danh sach nguoi than

							//if ($user->ps_customer_id == 6) {

							$relative_info = array();

							$relative_info['relative_id'] 	= LOGIN_RELATIVE_ID_NO;
							$relative_info['text_name']  	= $psI18n->__('Relative no');

							$relative_info['relationship'] = '';

							$relative_info['avatar_url'] 	= PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

							if (count($relatives_student) <= 0 && ($student->logtime_id <= 0)) {

								$relative_info['is_active'] = (int)$config_choose_attendances_relative;
							} else {

								if ($student->logtime_id > 0) {
									$relative_info['is_active'] = ($student->login_relative_id == LOGIN_RELATIVE_ID_NO) ? STATUS_ACTIVE : STATUS_NOT_ACTIVE;
								} else {
									$relative_info['is_active'] = (int)$config_choose_attendances_relative;
								}
							}

							$relative_info['phone_number'] = '';

							array_push($relatives_info, $relative_info);
							//}							

							foreach ($relatives_student as $relative) {

								$relative_info = array();

								$relative_info['relative_id'] 	= (string)$relative->relative_id;

								$relative_info['text_name']  	= (string)$relative->relationship_title . ' ' . (string)$relative->fullname;

								//$relative_info ['relationship'] = $relative->relationship_title;

								$relative_info['relationship'] = '';

								$relative_info['avatar_url'] 	= ($relative->avatar != '') ? PsString::getUrlMediaAvatar($relative->cache_data, $relative->year_data, $relative->avatar, MEDIA_TYPE_RELATIVE) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

								if ($student->logtime_id > 0) { // neu da diem danh phu huynh dua/don = phu huynh trong bang diem danh. Nguoc lai phu huynh dua/don = phu huynh dua/don chinh
									$relative_info['is_active'] = ($student->login_relative_id == $relative->relative_id) ? STATUS_ACTIVE : STATUS_NOT_ACTIVE;
								} else {
									$relative_info['is_active'] = ($relative->is_parent_main == 1 && !$check) ? (int)$config_choose_attendances_relative : STATUS_NOT_ACTIVE;
								}

								$relative_info['phone_number'] = (string)$relative->mobile;

								array_push($relatives_info, $relative_info);
							}

							//if ($user->ps_customer_id == 6) {

							$relative_info = array();

							$relative_info['relative_id'] 	= LOGIN_RELATIVE_ID_INSTEAD;

							$relative_info['text_name']  	= $psI18n->__('Relative instead');

							$relative_info['relationship'] = '';

							$relative_info['avatar_url'] 	= PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

							if ($student->logtime_id > 0 && $student->log_value == CONSTANT_LOGVALUE_1) {
								$relative_info['is_active'] = ($student->login_relative_id == LOGIN_RELATIVE_ID_INSTEAD) ? 1 : 0;
							} else {
								$relative_info['is_active'] = 0;
							}

							$relative_info['phone_number'] = '';

							array_push($relatives_info, $relative_info);

							//}

							$data_info['logtime_id'] = ($student->logtime_id) ? (string)$student->logtime_id : '';
                            
                            $data_info['log_code'] = (string)$student->log_code;
							$data_info['student_id'] = (string)$student->student_id;
							$data_info['student_code'] = (string)$student->student_code;

							$data_info['avatar_url'] = ($student->avatar != '') ? PsString::getUrlMediaAvatar($student->cache_data, $student->year_data, $student->avatar, MEDIA_TYPE_STUDENT) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR_STUDENT;

							$data_info['age'] = PsDateTime::getAgeMonth($student->birthday, $curr_day, true, $code_lang);
							$data_info['first_name'] = $student->first_name;
							$data_info['last_name'] = $student->last_name; 

							$data_info['birthday'] = $student->birthday;

							$_status = ($student->log_value == CONSTANT_LOGVALUE_1) ? 1 : 0;

							$absent = ($_status != CONSTANT_LOGVALUE_1) ? $absent + 1 : $absent;

							$absent = (int)$absent;

							$data_info['status'] = (int)$_status;

							if ($student->logtime_id) {
								$data_info['time_at'] = ($student->login_at) ? date('H:i', strtotime($student->login_at)) : date('H:i', strtotime($begin_time));
							} else {
								$data_info['time_at'] = date('H:i', strtotime($begin_time));
							}

							$data_info['relatives_info'] = $relatives_info;

							array_push($data, $data_info);

							$relatives_info = array();
						}
					} else if ($attendance_type == 'out') {

						foreach ($students as $student) {

							$relatives_student = PsRelativeStudentModel::getRelativeByStudentID($student->student_id);

							//if ($user->ps_customer_id == 6) {

							$relative_info = array();

							$relative_info['relative_id'] 	= LOGIN_RELATIVE_ID_NO;
							$relative_info['text_name']  	= $psI18n->__('Relative no');
							$relative_info['relationship'] = '';
							$relative_info['avatar_url'] 	= PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

							if (count($relatives_student) <= 0 && ($student->logout_at == '')) {
								$relative_info['is_active']    = 1;
							} else {
								if ($student->logout_at != '') {
									$relative_info['is_active'] = ($student->login_relative_id == LOGIN_RELATIVE_ID_NO) ? 1 : 0;
								} else {
									$relative_info['is_active'] = 1;
								}
							}

							$relative_info['phone_number'] = '';

							array_push($relatives_info, $relative_info);
							//}


							// danh sach nguoi than
							$relative_name="Không chọn";
							foreach ($relatives_student as $relative) {
                                 
                                 if($student->logout_relative_id == $relative->relative_id)
                                 	$relative_name=(string)$relative->relationship_title . ' ' . (string) $relative->fullname;
								$relative_info = array();

								$relative_info['relative_id']  = (string)$relative->relative_id;
								$relative_info['text_name'] 	= (string)$relative->relationship_title . ' ' . (string) $relative->fullname;

								//$relative_info ['relationship'] = ( string ) $relative->relationship_title;

								$relative_info['relationship'] = '';

								$relative_info['avatar_url'] = ($relative->avatar != '') ? PsString::getUrlMediaAvatar($relative->cache_data, $relative->year_data, $relative->avatar, MEDIA_TYPE_RELATIVE) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

								if ($student->logout_at != '' && $student->log_value == CONSTANT_LOGVALUE_1) { // neu da diem danh phu huynh dua/don = phu huynh trong bang diem danh. Nguoc lai phu huynh dua/don = phu huynh dua/don chinh
									$relative_info['is_active'] = ($student->logout_relative_id == $relative->relative_id) ? 1 : 0;
								} else {
									$relative_info['is_active'] = ($relative->is_parent_main == 1) ? 1 : 0;
								}

								$relative_info['phone_number'] = (string) $relative->mobile;

								array_push($relatives_info, $relative_info);
							}

							//if ($user->ps_customer_id == 6) {

							$relative_info = array();

							$relative_info['relative_id'] 	= LOGIN_RELATIVE_ID_INSTEAD;

							$relative_info['text_name']  	= $psI18n->__('Check out instead');

							$relative_info['relationship'] = '';

							$relative_info['avatar_url'] 	= PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

							if ($student->logout_at != '' && $student->log_value == CONSTANT_LOGVALUE_1) {
								$relative_info['is_active'] = ($student->logout_relative_id == LOGIN_RELATIVE_ID_INSTEAD) ? 1 : 0;
							} else {
								$relative_info['is_active'] = $student->logout_at;
							}

							$relative_info['phone_number'] = '';

							array_push($relatives_info, $relative_info);

							//}

							$data_info['logtime_id'] = ($student->logtime_id) ? (string)$student->logtime_id : '';
							$data_info['log_code'] = (string)$student->log_code;
							$data_info['relative_id'] = (string)$student->logout_relative_id;
							$data_info['relative_name'] = $relative_name;
							$data_info['student_id'] = (string)$student->student_id;
							$data_info['student_code'] = (string)$student->student_code;
							$data_info['birthday'] = $student->birthday;

							// $data_info ['avatar_url'] = ($student->avatar != '') ? PsString::getUrlMediaAvatar ( $student->cache_data, $student->year_data, $student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR_STUDENT;

							if ($student->logtime_id > 0 && $student->logout_at == '') {
								$data_info['avatar_url'] = $student_out_avatar_url;
							} else {
								$data_info['avatar_url'] = ($student->avatar != '') ? PsString::getUrlMediaAvatar($student->cache_data, $student->year_data, $student->avatar, MEDIA_TYPE_STUDENT) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR_STUDENT;
							}

							$data_info['age'] 		  = PsDateTime::getAgeMonth($student->birthday, $curr_day, true, $code_lang);
							$data_info['first_name'] = $student->first_name;
							$data_info['last_name']  = $student->last_name; 

							$status = ($student->logout_at != '') ? 1 : 0;
							$absent = ($status == 0) ? $absent + 1 : $absent;

							$data_info['status'] = $status;

							if ($student->logtime_id) {
								$data_info['time_at'] = ($student->logout_at) ? date('H:i', strtotime($student->logout_at)) : date('H:i');
							} else {
								$data_info['time_at'] = date('H:i');
							}

							$data_info['relatives_info'] = $relatives_info;

							array_push($data, $data_info);

							$relatives_info = array();
						}
					}

					$attendance_info->absent 	 	 = (int)$absent;

					$attendance_info->attendance 	 = $attendance_info->number_student - $absent;

					$attendance_info->number_student = (string) $attendance_info->number_student;

					$attendance_info->sl_dihoc = (int)$dihoc;

					$attendance_info->sl_cophep = (int)$nghicophep;

					$attendance_info->sl_khongphep = (int)$nghikophep;

					$attendance_info->sl_muon = (int)$muon;

					$return_data['_data']['attendance_info'] = $attendance_info;

					$return_data['_data']['data_info'] = $data;

					$return_data['_msg_code'] = MSG_CODE_TRUE;
				}
			}
		} catch (Exception $e) {

			$this->WriteLog('-- BEGIN ERROR--: DANH SACH DIEM DANH THEO LOP');

			$this->WriteLogError($e->getMessage(), $user);

			$this->WriteLog('-- END ERROR--: DANH SACH DIEM DANH THEO LOP');

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['_msg_text'] = $return_data['message'] 	= $psI18n->__('Network connection is not stable. Please do it again in a few minutes.') . $e->getMessage();
		}

		return $response->withJson($return_data);
	}

	// Luu diem danh theo lop
	public function saveAttendance(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$return_data = array();
		$return_data['_msg_code'] 	= MSG_CODE_FALSE;
		$return_data['_data'] 		= [];
		$return_data['_msg_text'] 	= $return_data['message'] = 'FALSE';

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		// get data from URI
		$info = $request->getParsedBody();

		$fr = isset($info['fr']) ? $info['fr'] : '';

		$attendance_type = isset($info['attendancetype']) ? $info['attendancetype'] : '';
		$class_id= isset($info['class_id']) ? $info['class_id'] : '';

		/*
		$this->WriteLog ( ' ' );
		$this->WriteLog ( '--BEGIN: LUU DIEM DANH THEO LOP' );
		$this->WriteLog ( "USER ID: " . $user->id);
		$this->WriteLog ( "USERNAME: " . $user->username);
		$this->WriteLog ( "KIEU DIEM DANH: " . (($attendance_type == 'in') ? 'DEN' : 'VE'));		
		$this->WriteLog ( "DATA_PUSH: " . date ( 'Y-m-d H:i:s' ) . ' ' . $response->withJson ( $info ) );
		*/

		if (($attendance_type == 'in' || $attendance_type == 'out') && ($user->user_type == USER_TYPE_TEACHER)) {

			$ps_member = PsMemberModel::getMember($user->member_id, null, $class_id);

			//$this->WriteLog ( "CLASS ID: " . $ps_member->myclass_id );

			//$this->WriteLog ( "CLASS NAME: " . $ps_member->myclass_name );

			if ($ps_member) {

				try {

					PsLogtimesModel::beginTransaction();

					// Lay cơ sơ cua lop hoc
					//$ps_work_places = PsWorkPlacesModel::where('id', $ps_member->ps_workplace_id)->get()->first();
					$ps_work_places = PsWorkPlacesModel::getColumnById($ps_member->ps_workplace_id, 'from_time_notication_attendances,to_time_notication_attendances,config_choose_attendances_relative,config_push_notication_update_attendance');

					// <= 12h
					$time_pushNoticationLoginAt 	= '12:00';

					// <= 7h:30 PM
					$time_pushNoticationLogoutAt 	= '19:30';

					if ($ps_work_places) {

						$time_pushNoticationLoginAt 	= 	$ps_work_places->from_time_notication_attendances;

						$time_pushNoticationLogoutAt 	= 	$ps_work_places->to_time_notication_attendances;

						$pushNoticationUpdate 			= 	$ps_work_places->config_push_notication_update_attendance;
					} else {

						// Lay co so cua giao vien
						//$ps_work_places = PsWorkPlacesModel::where('id', $ps_member->member_workplace_id)->get()->first();
						$ps_work_places = PsWorkPlacesModel::getColumnById($ps_member->member_workplace_id, 'from_time_notication_attendances,to_time_notication_attendances,config_choose_attendances_relative,config_push_notication_update_attendance');

						$time_pushNoticationLoginAt 	= 	$ps_work_places->from_time_notication_attendances;

						$time_pushNoticationLogoutAt 	= 	$ps_work_places->to_time_notication_attendances;

						$pushNoticationUpdate 			= 	$ps_work_places->config_push_notication_update_attendance;
					}

					if ($fr == 'ad') { // Truong hop truyen len theo kieu mang

						$arr_info = isset($info['info']) ? $info['info'] : '';

						$arr_student_ids = isset($arr_info['studentID']) ? $arr_info['studentID'] : '';

						$arr_students = array(); 


						foreach ($arr_student_ids as $key => $student_logtime) {

							$obj = array();

							$obj['studentID']   = (int) $key;
							$obj['logtime_id']  = $student_logtime['logtime_id'];
							$obj['relative_id'] = isset($student_logtime['relative_id']) ? $student_logtime['relative_id'] : null;
							$obj['time_at'] 	 = $student_logtime['time_at'];
							$obj['log_code'] 	 = $student_logtime['log_code'];


							array_push($arr_students, $obj);


						}
							$return_data['_msg_text'] 	= $return_data['message']   = $psI18n->__('Điểm danh nhanh thành công');	
					} else {

						$info_students = isset($info['students']) ? $info['students'] : '';

						$arr_students = json_decode($info_students, true);
					}

					$date_at = date('Ymd');

					if (PsDateTime::validateDate($date_at, 'Ymd') && (strtotime($date_at) >= strtotime(date('Ymd')))) {

						$date_at = date('Y-m-d', strtotime($date_at));

						if ($attendance_type == 'in') { // Điểm danh đến

							$return_data['_msg_code'] = (int)$this->processAttendanceIn($user, $ps_member, $arr_students, $date_at, $time_pushNoticationLoginAt, $pushNoticationUpdate);
						} elseif ($attendance_type == 'out') {

							$return_data['_msg_code'] = (int) $this->processAttendanceOut($user, $ps_member, $arr_students, $date_at, $time_pushNoticationLogoutAt, $pushNoticationUpdate);
						}
					} else {

						$this->WriteLog('--END: LUU DIEM DANH THEO LOP : THAT BAI - DO LOI THOI GIAN: ' . $date_at);

						$return_data['_msg_code'] = MSG_CODE_500;

						$return_data['_msg_text'] 	= $return_data['message']   = $psI18n->__('Invalid attendance date') . '. ' . $psI18n->__('Save attendance list failed');
					}

					PsLogtimesModel::commit();
				} catch (Exception $e) {

					PsLogtimesModel::rollback();

					$this->WriteLog('-- BEGIN ERROR--: LUU DIEM DANH THEO LOP');

					$this->WriteLogError($e->getMessage(), $user);

					$this->WriteLog('-- END ERROR--: LUU DIEM DANH THEO LOP');

					$return_data['_msg_code'] = MSG_CODE_500;

					$return_data['_msg_text'] 	= $return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
				}
			} else {

				$this->WriteLog('--END: LUU DIEM DANH THEO LOP: ERROR: GIAO VIEN NAY DUONG NHU KHONG CO QUYEN THAO TAC VOI LOP HOC NAY');
				$this->WriteLog('');

				$return_data['_msg_code'] = MSG_CODE_500;

				$return_data['_msg_text'] 	= $return_data['message'] = $psI18n->__('You do not have access to this data');
			}
		} else {
			$this->WriteLog('--END: LUU DIEM DANH THEO LOP: ERROR: KHONG XAC DINH DUOC KIEU DIEM DANH HOAC USER TYPE KHONG HOP LE');
			$this->WriteLog('');
			$return_data['_msg_code'] = MSG_CODE_500;
			$return_data['_msg_text'] 	= $return_data['message'] = $psI18n->__('You do not have access to this data');
		}

		return $response->withJson($return_data);
	}

	// Hien thi thong tin diem danh chi tiet - tung hoc sinh
	public function attendanceStudent(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$return_data = array();
		$return_data['_msg_code'] 	= MSG_CODE_FALSE;
		$return_data['_data'] 		= [];
		$return_data['_msg_text'] 	= $return_data['message'] = 'FALSE';

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$queryParams = $request->getQueryParams();

		$attendance_type = isset($queryParams['attendancetype']) ? $queryParams['attendancetype'] : '';

		$class_id = isset($queryParams['class_id']) ? $queryParams['class_id'] : '';

		$student_id = $args['student_id'];

		if (($student_id < 0) || ($attendance_type != 'in' && $attendance_type != 'out')) {
			return $response->withJson($return_data);
		}

		$curr_day = date('Y-m-d');

		try {

			if ($user->user_type == USER_TYPE_TEACHER) {

				// Thong tin giao vien
				$ps_member = PsMemberModel::getMember($user->member_id, null, $class_id);

				if ($ps_member) {

					$ps_workplace_id = ($ps_member->ps_workplace_id > 0) ? $ps_member->ps_workplace_id : $ps_member->member_workplace_id;

					$ps_work_places  = PsWorkPlacesModel::getColumnById($ps_workplace_id, 'config_class_late,config_default_logout,config_choose_attendances_relative');

					// Bât buoc chon nguoi dua don ko
					$config_choose_attendances_relative = 0;

					if ($ps_work_places) {

						$config_choose_attendances_relative = $ps_work_places->config_choose_attendances_relative;
					}

					// Thong tin hoc sinh
					$student = $this->db->table(CONST_TBL_STUDENT . ' as S')
						->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'S.ps_customer_id')
						->join(CONST_TBL_STUDENTCLASS . ' as SC', 'SC.student_id', '=', 'S.id')
						->join(CONST_TBL_MYCLASS . ' as M', 'M.id', '=', 'SC.myclass_id')
						->leftJoin(CONST_TBL_PS_LOGTIMES . ' as LT', function ($q) use ($curr_day) {
							$q->on('LT.student_id', '=', 'S.id')
								->whereDate('LT.login_at', '=', date('Y-m-d', strtotime($curr_day)));
						})->select('S.id as student_id', 'S.birthday as birthday', 'S.first_name as first_name', 'S.last_name as last_name', 'S.avatar as avatar', 'S.ps_customer_id as ps_customer_id', 'S.year_data', 'C.cache_data', 'LT.id as logtime_id', 'LT.login_at as login_at', 'LT.log_value AS log_value', 'M.name AS class_name', 'M.id AS class_id', 'LT.logout_at as logout_at', 'LT.login_relative_id as login_relative_id', 'LT.logout_relative_id as logout_relative_id', 'LT.log_value AS log_value', 'LT.log_code AS log_code')
						->where('M.id', $ps_member->myclass_id)
						->whereIn('SC.type', [
							STUDENT_HT,
							STUDENT_CT
						])
						->whereRaw('S.deleted_at IS NULL')
						->whereDate('SC.start_at', '<=', $curr_day)
						->whereRaw('(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $curr_day . '", "%Y%m%d") OR SC.stop_at IS NULL )')
						->where('SC.is_activated', STATUS_ACTIVE)
						->where('M.is_activated', STATUS_ACTIVE)
						->where('S.ps_customer_id', $ps_member->ps_customer_id)
						->where('S.id', $student_id)->get()->first();

					$student_info = $relatives_info = $relative_info = $services_info = $service_info = array();

					$student_info['logtime_id'] = ($student->logtime_id) ? $student->logtime_id : '';

					$student_info['log_value'] = ($student->log_value) ? $student->log_value : '';
					$student_info['log_code'] = ($student->log_code) ? $student->log_code : '';

					if ($attendance_type == 'in') {

						if ($student->log_value == CONSTANT_LOGVALUE_1) {
							$student_info['status'] = 1;
						} else {
							$student_info['status'] = 0;
						}

						$student_info['time_at'] = ($student->login_at) ? date('H:i', strtotime($student->login_at)) : date('H:i');
					} else if ($attendance_type == 'out') {

						if ($student->logout_at == '') {
							$student_info['status'] = 0;
						} else {
							$student_info['status'] = 1;
						}

						if ($student->logtime_id) {

							$student_info['time_at'] = ($student->logout_at) ? date('H:i', strtotime($student->logout_at)) : date('H:i');
						} else
							$student_info['time_at'] = date('H:i');
					}

					$student_info['date_at'] = date('d-m-Y', strtotime($curr_day));
					$student_info['student_id'] = $student->student_id;
					$student_info['first_name'] = $student->first_name;
					$student_info['last_name'] = $student->last_name;
					$student_info['class_id'] = $student->class_id;
					$student_info['class_name'] = $student->class_name;
					$student_info['avatar_url'] = ($student->avatar != '') ? PsString::getUrlMediaAvatar($student->cache_data, $student->s_year_data, $student->avatar, MEDIA_TYPE_STUDENT) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR_STUDENT;

					if ($attendance_type == 'in') { // diem danh den

						$relatives_student = PsRelativeStudentModel::getRelativeByStudentID($student->student_id); // danh sach nguoi than

						$relative_info = array();

						$relative_info['relative_id'] 	= LOGIN_RELATIVE_ID_NO;
						$relative_info['text_name']  	= $psI18n->__('Relative no');

						$relative_info['relationship'] = '';

						$relative_info['avatar_url'] 	= PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

						if (count($relatives_student) <= 0 && ($student->logtime_id <= 0)) {

							$relative_info['is_active']    = (int)$config_choose_attendances_relative;
						} else {

							if ($student->logtime_id > 0) {
								$relative_info['is_active'] = ($student->login_relative_id == LOGIN_RELATIVE_ID_NO) ? 1 : 0;
							} else {
								$relative_info['is_active']    = (int)$config_choose_attendances_relative;
							}
						}

						$relative_info['phone_number'] = '';

						array_push($relatives_info, $relative_info);

						foreach ($relatives_student as $relative) {
							$relative_info['relative_id'] = $relative->relative_id;
							$relative_info['text_name'] = $relative->relationship_title . ' ' . $relative->fullname;
							$relative_info['phone_number'] = $relative->mobile;
							$relative_info['avatar_url'] = ($relative->avatar != '') ? PsString::getUrlMediaAvatar($relative->cache_data, $relative->year_data, $relative->avatar, MEDIA_TYPE_RELATIVE) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

							if ($student->logtime_id > 0) { // neu da diem danh phu huynh dua/don = phu huynh trong bang diem danh. Nguoc lai phu huynh dua/don = phu huynh dua/don chinh

								$relative_info['is_active'] = ($student->login_relative_id == $relative->relative_id) ? 1 : 0;
							} else
								$relative_info['is_active'] = ($relative->is_parent_main == 1) ? 1 : 0;

							array_push($relatives_info, $relative_info);
						}

						$relative_info = array();

						$relative_info['relative_id'] 	= LOGIN_RELATIVE_ID_INSTEAD;

						$relative_info['text_name']  	= $psI18n->__('Relative instead');

						$relative_info['relationship'] = '';

						$relative_info['avatar_url'] 	= PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

						if ($student->logtime_id > 0) {
							$relative_info['is_active'] = ($student->login_relative_id == LOGIN_RELATIVE_ID_INSTEAD) ? 1 : 0;
						} else {
							$relative_info['is_active'] = 0;
						}

						$relative_info['phone_number'] = '';

						array_push($relatives_info, $relative_info);


						// danh sach dich vu ma hoc sinh dang ky
						$services = ServiceModel::getService($student_id, $student->ps_customer_id);

						// Edit => Lấy danh sách dịch vụ và dịch vụ đã lưu khi điểm danh

						foreach ($services as $service) {
							$service_info['service_id'] = $service->id;
							$service_info['icon_url']   = PsString::getUrlPsImage($service->file_name);
							$service_info['title']      = $service->title;
							$service_info['student_service_diary_id'] = $service->student_service_diary_id ? $service->student_service_diary_id : '';

							if ($student->logtime_id > 0  && $student->log_value == CONSTANT_LOGVALUE_1) {
								$service_info['is_status'] = $service->student_service_diary_id > 0 ? 1 : 0;
							} else {
								$service_info['is_status'] = 1;
							}

							//$service_info ['is_status'] = $service->student_service_diary_id > 0 ? 1 : 0;

							$service_info['logtime_id'] = $student->logtime_id;

							array_push($services_info, $service_info);
						}
					} else if ($attendance_type == 'out') { // diem danh ve

						$relatives_student = PsRelativeStudentModel::getRelativeByStudentID($student->student_id); // danh sach nguoi than

						$relative_info = array();

						$relative_info['relative_id'] 	= LOGIN_RELATIVE_ID_NO;
						$relative_info['text_name']  	= $psI18n->__('Relative no');
						$relative_info['relationship'] = '';
						$relative_info['avatar_url'] 	= PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

						if (count($relatives_student) <= 0 && ($student->logout_at == '')) {
							$relative_info['is_active']    = 1;
						} else {
							if ($student->logout_at != '') {
								$relative_info['is_active'] = ($student->login_relative_id == LOGIN_RELATIVE_ID_NO) ? 1 : 0;
							} else {
								$relative_info['is_active'] = 1;
							}
						}

						$relative_info['phone_number'] = '';

						array_push($relatives_info, $relative_info);

						foreach ($relatives_student as $relative) {

							$relative_info['relative_id'] = $relative->relative_id;
							$relative_info['text_name'] = $relative->relationship_title . ' ' . $relative->fullname;
							$relative_info['avatar_url'] = ($relative->avatar != '') ? PsString::getUrlMediaAvatar($relative->cache_data, $relative->year_data, $relative->avatar, MEDIA_TYPE_RELATIVE) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
							$relative_info['phone_number'] = $relative->mobile;
							if ($student->logtime_id > 0 && $student->logout_relative_id) { // neu da diem danh phu huynh dua/don = phu huynh trong bang diem danh. Nguoc lai phu huynh dua/don = phu huynh dua/don chinh

								$relative_info['is_active'] = ($student->logout_relative_id == $relative->relative_id) ? 1 : 0;
							} else
								$relative_info['is_active'] = ($relative->is_parent_main == 1) ? 1 : 0;

							array_push($relatives_info, $relative_info);
						}

						$relative_info = array();

						$relative_info['relative_id'] 	= LOGIN_RELATIVE_ID_INSTEAD;

						$relative_info['text_name']  	= $psI18n->__('Check out instead');

						$relative_info['relationship'] = '';

						$relative_info['avatar_url'] 	= PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

						if ($student->logtime_id > 0 && $student->log_value == CONSTANT_LOGVALUE_1) {
							$relative_info['is_active'] = ($student->login_relative_id == LOGIN_RELATIVE_ID_INSTEAD) ? 1 : 0;
						} else {
							$relative_info['is_active'] = 0;
						}

						$relative_info['phone_number'] = '';

						array_push($relatives_info, $relative_info);
					}

					$return_data['_data'] = $student_info;
					$return_data['_data']['relative_info'] = $relatives_info;
					$return_data['_data']['service_info'] = $services_info;
					$return_data['_msg_code'] = MSG_CODE_TRUE;

					$return_data['_msg_text'] 	= $return_data['message'] = 'TRUE';
				}
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['_msg_text'] 	= $return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}
		return $response->withJson($return_data);
	}

	// Luu diem danh chi tiet - tung hoc sinh
	public function saveAttendanceStudent(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$return_data = array();
		$return_data['_msg_code'] 	= MSG_CODE_FALSE;
		$return_data['_data'] 		= [];
		$return_data['_msg_text'] 	= $return_data['message'] = 'FALSE';

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		// get data from URI
		$info = $request->getParsedBody();

		$attendance_type = isset($info['attendancetype']) ? $info['attendancetype'] : '';

		$class_id = isset($info['class_id']) ? $info['class_id'] : '';
		/*
		$this->WriteLog ( '--BEGIN: LUU DIEM DANH CHI TIET' );		
		$this->WriteLog ( "USER ID: " . $user->id);		
		$this->WriteLog ( "USERNAME: " . $user->username);
		$this->WriteLog ( "KIEU DIEM DANH: " . (($attendance_type == 'in') ? 'DEN' : 'VE'));
		$this->WriteLog ( "DIEM DANH CHI TIET DATA_PUSH: " . date ( 'Y-m-d H:i:s' ) . ' ' . $response->withJson ( $info ) );		
		*/
   
		if ($attendance_type != '' && $user->user_type == USER_TYPE_TEACHER) {

			$ps_member = PsMemberModel::getMember($user->member_id,null,$class_id);


			//$return_data ['ps_member'] = $ps_member;

			//$this->WriteLog ( "CLASS ID: " . $ps_member->myclass_id );

			//$this->WriteLog ( "CLASS NAME: " . $ps_member->myclass_name );

			// Lay thong tin hoc sinh
			$student_info = StudentModel::getStudentInfoByID((int) $info['student_id']);

			//return "AAAAAAA";

			if ($ps_member && $student_info) {

				try {
                   
					$date_at = isset($info['date_at']) ? $info['date_at'] : date("Y-m-d");
					
					if ($date_at !="" && (strtotime($date_at) >= strtotime(date('Ymd')))) { 

						//return "AAAAAAAAAAAA";

						$date_at = date('Y-m-d', strtotime($date_at));

						$student_id = (int)$student_info->id;

						$status     = (int) $info['status'];

						$logtime_id = (int) $info['logtime_id'];

						$relative_id = (int) $info['relative_id'];

						$time_at = $info['time_at']; 

						$log_code = $info['log_code'];

						$log_at = $date_at . ' ' . $time_at;

						$arr_service_used_name = array();
						// Lay cơ sơ cua lop hoc
						//$ps_work_places = PsWorkPlacesModel::where('id', $ps_member->ps_workplace_id)->get()->first();

						$ps_work_places = PsWorkPlacesModel::getColumnById($ps_member->ps_workplace_id, 'from_time_notication_attendances,to_time_notication_attendances,config_choose_attendances_relative,config_push_notication_update_attendance');

						// <= 12h
						$time_pushNoticationLoginAt 	= '12:00';

						// <= 7h:30 PM
						$time_pushNoticationLogouttAt 	= '19:30';

						$acess_pushNotication = false;

						if ($ps_work_places) { 


							$time_pushNoticationLoginAt 	= 	$ps_work_places->from_time_notication_attendances;

							$time_pushNoticationLogouttAt 	= 	$ps_work_places->to_time_notication_attendances;
                             
							$pushNoticationUpdate 			= 	$ps_work_places->config_push_notication_update_attendance;
						} else {
                            
							// Lay co so cua giao vien
							//$ps_work_places = PsWorkPlacesModel::where('id', $ps_member->member_workplace_id)->get()->first();

							$ps_work_places = PsWorkPlacesModel::getColumnById($ps_member->member_workplace_id, 'from_time_notication_attendances,to_time_notication_attendances,config_choose_attendances_relative,config_push_notication_update_attendance');

							$time_pushNoticationLoginAt 	= 	$ps_work_places->from_time_notication_attendances;

							$time_pushNoticationLogouttAt 	= 	$ps_work_places->to_time_notication_attendances;

							$pushNoticationUpdate 			= 	$ps_work_places->config_push_notication_update_attendance;
						}
   
						$logtime = PsLogtimesModel::where('student_id', $student_id)->whereRaw('DATE_FORMAT(login_at, "%Y%m%d") = '. DATE("Ymd",strtotime($date_at)))->first();
                        //return $date_at.$logtime->id;
						// Diem danh den
						if ($attendance_type == 'in') {
							if ($date_at == date('Y-m-d')) {

								if ($time_pushNoticationLoginAt == "00:00:00") { // neu khong cau hinh thi gui thong bao
                                   
									$acess_pushNotication = true;
								} else { // Gui thong bao diem danh truoc gio cau hinh trong co so

									$config_time  = date("Y-m-d H:i", strtotime(date('Y-m-d') . ' ' . $time_pushNoticationLoginAt));

									$current_time = date("Y-m-d H:i", time());

									if (strtotime($current_time) <= strtotime($config_time))
										$acess_pushNotication = true;
								}
							}
                       
                            
							$service_ids = isset($info['service_id']) ? $info['service_id'] : null;
                            
							// xoa student_service_diary
							$this->db->table(TBL_STUDENT_SERVICE_DIARY)->where('student_id', $student_id)->whereDate('tracked_at', $date_at)->delete();
							// Them dich vu vao bang student_service_diary nếu có kể cả nghỉ
							if (count($service_ids) > 0) {
								// Kiem tra lai data service_id - danh sach dich vu ma hoc sinh dang ky
								$list_check_service = ServiceModel::getServiceInArrayId($service_ids, $student_info->ps_customer_id);

								foreach ($list_check_service as $obj_service) {
									$insert_service_diary = $this->db->table(TBL_STUDENT_SERVICE_DIARY)->insertGetId([
										'student_id' => $student_id,
										'service_id' => (int) $obj_service->id,
										'tracked_at' => $date_at,
										'user_created_id' => $user->id,
										'created_at' => date('Y-m-d H:i:s'),
										'updated_at' => date('Y-m-d H:i:s')
									]);

									array_push($arr_service_used_name, $obj_service->title);
								}
							}
                            
							if ($status == CONSTANT_LOGVALUE_1 || $status == CONSTANT_LOGVALUE_0) {

								if (!$logtime) {

									$insert_logtime = $this->db->table(CONST_TBL_PS_LOGTIMES)->insertGetId([
										'student_id' => $student_id, 
										'log_code' => $log_code,	
										'login_at' => date('Y-m-d H:i:s', strtotime($log_at)),
										'login_relative_id' => $relative_id,
										'login_member_id' => $ps_member->id,
										'user_created_id' => $user->id,
										'log_value' 	  => $status,
										'created_at' => date('Y-m-d H:i:s'),
										'updated_at' => date('Y-m-d H:i:s')
									]);
									$return_data['message'] = $return_data['_msg_text'] = 'Thêm mới điểm danh thành công';

								} else {
                                    
									// Ko gui Notication nếu là update
									$acess_pushNotication		= $pushNoticationUpdate;

									if ($status != CONSTANT_LOGVALUE_1)
										$acess_pushNotication = false;

									$logtime->login_at 			= date('Y-m-d H:i:s', strtotime($log_at));
									$logtime->login_relative_id = (int) $relative_id;
									$logtime->login_member_id 	= (int) $ps_member->id;
									$logtime->log_value 		= $status;
									$logtime->log_code		= $log_code;
									$logtime->user_updated_id 	= (int) $user->id;
									$logtime->updated_at 		= date("Y-m-d H:i:s");
									$logtime->save();
                                  	$return_data['message'] = $return_data['_msg_text'] = 'Cập nhật điểm danh thành công';
                                  	
								}
							} else { // Nếu = 0 thì xóa bỏ coi như chưa điểm danh

								//$acess_pushNotication = false;

								//PsLogtimesModel::where ( 'student_id', $student_id )->whereRaw ( 'DATE_FORMAT(login_at, "%Y%m%d") = DATE_FORMAT("' . $date_at . '", "%Y%m%d")' )->delete ();
							}

							// Lay so luong diem danh den
							$login_count = PsLogtimesModel::getLoginCount($ps_member->ps_customer_id, $ps_member->myclass_id, $date_at);

							$psAttendancesSynthetic = PsAttendancesSyntheticModel::getAttendanceSyntheticByDate($ps_member->myclass_id, $date_at);

							if (!$psAttendancesSynthetic) {
								$psAttendancesSynthetic = new PsAttendancesSyntheticModel();
								$psAttendancesSynthetic->ps_customer_id   = $ps_member->ps_customer_id;
								$psAttendancesSynthetic->ps_class_id      = $ps_member->myclass_id;
								$psAttendancesSynthetic->login_sum        = count($login_count);
								$psAttendancesSynthetic->logout_sum	   = 0;
								$psAttendancesSynthetic->tracked_at	   = $date_at;
								$psAttendancesSynthetic->user_updated_id  = $user->id;
								$psAttendancesSynthetic->save();
							} else {
								$psAttendancesSynthetic->login_sum       = count($login_count);
								$psAttendancesSynthetic->save();
							}

							// Fix cho KidsSchool
							//if ($ps_member->ps_customer_id == 6) 
							//$acess_pushNotication = true;

							if ($acess_pushNotication) {

								// BEGIN: Push notication
								$params = array();
								$params['student_id'] 			= $student_id;
								$params['student_info'] 		= $student_info;
								$params['relative_id'] 		= $relative_id;
								$params['time_at'] 			= date('H:i d-m-Y', strtotime($log_at));
								$params['ps_member'] 			= $ps_member;

								$params['service_used_name'] 	= implode("; ", $arr_service_used_name);

								$this->pushNotificationAttendanceToSchool($user, $params, $logtime);
								// END: Push notication
							}
						} elseif ($attendance_type == 'out') { // diem danh ve

							if ($logtime) {

								if ($date_at == date('Y-m-d')) {

									if ($time_pushNoticationLogouttAt == "00:00:00") { // neu khong cau hinh thi gui thong bao

										$acess_pushNotication = true;
									} else { // Gui thong bao diem danh truoc gio cau hinh trong co so

										$config_time  = date("Y-m-d H:i", strtotime(date('Y-m-d') . ' ' . $time_pushNoticationLogouttAt));

										$current_time = date("Y-m-d H:i", time());

										if (strtotime($current_time) <= strtotime($config_time))
											$acess_pushNotication = true;
									}
								}

								// Neu la cap nhat diem danh ve
								if ($logtime->logout_at != '') {
									$acess_pushNotication = $pushNoticationUpdate;
								}

								if ($status == STATUS_ACTIVE) { // Lưu điểm danh về
									//return "AAAAAAAA".$log_at;
									$logtime->logout_at 			= $log_at;
									$logtime->logout_relative_id 	= $relative_id;
									$logtime->logout_member_id 		= (int) $ps_member->id;
									
								} else { // Xóa điểm danh về
									$logtime->logout_at 			= null;
									$logtime->logout_relative_id 	= null;
									$logtime->logout_member_id 		= null;

									$acess_pushNotication = false;
								}

								$logtime->user_updated_id 		= (int) $user->id;
								$logtime->updated_at 			= date("Y-m-d H:i:s");
								$logtime->save();
                                $return_data['message'] = $return_data['_msg_text'] = 'Lưu điểm danh về thành công';

								// Fix cho KidsSchool
								//if ($ps_member->ps_customer_id == 6) 
								//$acess_pushNotication = true;
                              
								if ($acess_pushNotication) {
									// BEGIN: Push notication
									$params = array();
									$params['student_id'] 		= $student_id;
									$params['student_info'] 	= $student_info;
									$params['relative_id'] 	= $relative_id;
									$params['time_at'] 		= date('H:i d-m-Y', strtotime($log_at));

									$params['ps_member'] = $ps_member;

									$this->pushNotificationAttendanceOutSchool($user, $params, $logtime);
									// END: Push notication
								}

								// Cap nhat so luong diem danh ve
								$logout_sum = PsLogtimesModel::getLogoutCount($ps_member->ps_customer_id, $ps_member->myclass_id, $date_at);

								$psAttendancesSynthetic = PsAttendancesSyntheticModel::getAttendanceSyntheticByDate($ps_member->myclass_id, $date_at);

								if ($psAttendancesSynthetic) {
									$psAttendancesSynthetic->logout_sum       = count($logout_sum);
									$psAttendancesSynthetic->save();
								}
							} else {
								$this->WriteLog('STUDENT ID: ' . $student_id . ' . CHUA CO DIEM DANH DEN');
							}
						}

						$return_data['_msg_code'] = MSG_CODE_TRUE;

						$this->WriteLog('--END: LUU DIEM DANH CHI TIET : THANH CONG');
					} else {

						$return_data['message'] = $return_data['_msg_text'] = 'Invalid date';

						$this->WriteLog('--END: LUU DIEM DANH CHI TIET : THAT BAI - LOI THOI GIAN: ' . $date_at);
					}
				} catch (Exception $e) {

					$this->WriteLog('-- BEGIN ERROR--: LUU DIEM DANH CHI TIET');

					$this->WriteLogError($e->getMessage(), $user);

					$this->WriteLog('-- END ERROR--: LUU DIEM DANH CHI TIET');

					$return_data['_error_code'] 	= 0;

					$return_data['_msg_code']   	= MSG_CODE_500;

					$return_data['_msg_text'] = $return_data['message'] 		= $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
				}
			}
		}

		return $response->withJson($return_data);
	}

	// Lich giao vien
	public function featureTeacher(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$return_data = array();
		$return_data['_msg_code'] 	= MSG_CODE_FALSE;
		$return_data['_data'] 		= [];
		//$return_data ['_msg_text'] 	= $return_data ['message'] = 'FALSE'; 

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$date_at = $args['page_day'] ? date('Y-m-d', strtotime($args['page_day'])) : date('Y-m-d');

		try {
			if (($user->user_type == USER_TYPE_TEACHER) && PsDateTime::validateDate($date_at, 'Y-m-d')) {

				$ps_member = PsMemberModel::getMember($user->member_id);

				if ($ps_member) {

					$return_data['_msg_code'] = MSG_CODE_TRUE;

					$datetime1 = date_create($date_at);

					$datetime2 = date_create(date('Y-m-d'));

					$interval = date_diff($datetime1, $datetime2);

					//$date_at = ($interval->format('%a') < PS_CONST_LIMIT_DAY_FEATURE) ? $date_at : date('Y-m-d');

					$day_info = new \stdClass();
					$day_info->day_at = PsDateTime::toFullDayInWeek($date_at, $code_lang);
					$day_info->avatar_url = ($ps_member->avatar != '') ? PsString::getUrlMediaAvatar($ps_member->cache_data, $ps_member->s_year_data, $ps_member->avatar, MEDIA_TYPE_TEACHER) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

					$day_info->day_next = date('Ymd', strtotime($date_at . ' + 1 day'));
					$day_info->day_pre = date('Ymd', strtotime($date_at . ' - 1 day'));

					// Lay danh sach hoat dong
					$features = $this->db->table(TBL_FEATURE_BRANCH . ' as FB')
						->leftjoin(TBL_FEATURE_BRANCH_TIMES . ' as FBT', 'FBT.ps_feature_branch_id', '=', 'FB.id')
						//->join ( TBL_FEATURE_BRANCH_TIME_MY_CLASS . ' as FBTC', 'FBTC.ps_feature_branch_time_id', '=', 'FBT.id' )

						->join(TBL_FEATURE_BRANCH_TIME_MY_CLASS . ' as FBTC', function ($q) use ($ps_member) {
							$q->on('FBTC.ps_feature_branch_time_id', '=', 'FBT.id')->where('FBTC.ps_myclass_id', '=', $ps_member->myclass_id);
						})
						->join(TBL_FEATURE . ' as F', 'F.id', '=', 'FB.feature_id')
						->leftJoin(CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', 'FB.ps_image_id')
						->leftJoin(TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'FBT.ps_class_room_id')
						->selectRaw('FB.id as id, FB.name as feature_title, I.file_name, FBT.start_time as start_at, FBT.end_time as end_at, CR.title as class_room_title, Null as note')
						->where('F.ps_customer_id', $ps_member->ps_customer_id)
						->whereDate('FBT.start_at', '<=', $date_at)
						->whereDate('FBT.end_at', '>=', $date_at)
						->where('F.is_activated', STATUS_ACTIVE)
						->where('FB.is_activated', STATUS_ACTIVE)
						->where('FB.school_year_id', $ps_member->school_year_id)->distinct('FB.id');

					//->where ( function ($query) use ($ps_member) {

					//$query->where ( 'FBTC.ps_myclass_id', '=' ,$ps_member->myclass_id );

					//$query->orWhereRaw ( 'FBTC.ps_myclass_id IS NULL AND( FBTC.id IS NULL AND FB.ps_obj_group_id = ?)', array($ps_member->ps_obj_group_id) );

					//$query->orWhereRaw ( 'FBTC.id IS NULL AND (FB.ps_obj_group_id IS NULL AND FB.ps_workplace_id)', array($ps_member->ps_workplace_id) );

					//$query->orWhereRaw ( 'FBTC.id IS NULL AND FB.ps_obj_group_id IS NULL AND FB.ps_workplace_id IS NULL ');

					//$query->orWhereRaw ( 'FBTC.ps_myclass_id = ? OR ( FBTC.id IS NULL AND (((FB.ps_obj_group_id = ? AND FB.ps_workplace_id IS NULL) OR FB.ps_obj_group_id IS NULL) OR (FB.ps_workplace_id IS NULL OR (FB.ps_workplace_id = ? AND FB.ps_obj_group_id IS NULL)) ) )' , array($ps_member->myclass_id, $ps_member->ps_obj_group_id, $ps_member->ps_workplace_id));

					//} );

					// Lay thong tin dich vu theo thoi khoa bieu - Lay lich day cua giao vien theo ngay
					$services = $this->db->table(TBL_PS_SERVICE_COURSES . ' as SC')
						->join(CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'SC.ps_service_id')
						->join(TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', 'SCS.ps_service_course_id', '=', 'SC.id')
						->leftJoin(CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', 'S.ps_image_id')
						->leftJoin(TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'SCS.ps_class_room_id')
						->selectRaw('Null as id, S.title as feature_title, I.file_name, SCS.start_time_at as start_at, SCS.end_time_at as end_at, CR.title as class_room_title, SCS.id as note')
						->whereDate('SCS.date_at', $date_at)
						//->whereDate ( 'SC.start_at', '<=', $date_at )
						//->whereDate ( 'SC.end_at', '>=', $date_at )
						->where('S.ps_customer_id', $ps_member->ps_customer_id)
						->where('SC.is_activated', STATUS_ACTIVE)
						->where('S.enable_roll', ENABLE_ROLL_SCHEDULE)
						->where('SC.ps_member_id', $ps_member->id)
						->where('SCS.is_activated', STATUS_ACTIVE)
						->where('S.is_activated', STATUS_ACTIVE);


					// Ghep dich vu va hoat dong
					$feature_services = $services->unionall($features)->orderBy('start_at')->distinct()->get();

					$data = array();

					foreach ($feature_services as $feature_service) {

						$data_feature = array();

						$data_feature['feature_id'] = ($feature_service->id == !null) ? ($feature_service->id) : ($feature_service->note);
						$data_feature['feature_title'] = (string)$feature_service->feature_title;
						$data_feature['icon'] = PsString::getUrlPsImage($feature_service->file_name);
						$data_feature['time_at'] = PsDateTime::getTime($feature_service->start_at) . '-' . PsDateTime::getTime($feature_service->end_at);
						$data_feature['class_room'] = (string)$feature_service->class_room_title;
						$data_feature['feature_type'] = ($feature_service->id == !null) ? 0 : 1;
						array_push($data, $data_feature);
					}

					$return_data['_data']['day_info'] = $day_info;
					$return_data['_data']['data_info'] = $data;
					$return_data['_msg_code'] = MSG_CODE_TRUE;
					$return_data['_msg_text'] 	= $return_data['message'] = "TRUE";
				}
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['_msg_text'] 	= $return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// Hien thi Danh gia hoat dong
	public function rateFeatureStudent(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$return_data = array();
		$return_data['_msg_code'] 	= MSG_CODE_FALSE;
		$return_data['_data'] 		= [];
		$return_data['_msg_text'] 	= $return_data['message'] = 'FALSE';

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$feature_id = (int) $args['feature_id'];

		$queryParams = $request->getQueryParams();

		$feature_type = isset($queryParams['feature_type']) ? $queryParams['feature_type'] : '';

		$this->WriteLog('-- BEGIN: HIEN THI DANH GIA HOAT DONG');
		$this->WriteLog("USER ID: " . $user->id);
		$this->WriteLog("USER ID: " . $user->username);
		$this->WriteLog('LOAI: 0- HOAT DONG; 1-HOC NGOAI KHOA: ' . $feature_type);
		$this->WriteLog('ITEM ID: ' . $feature_id);
		$this->WriteLog('-- END: HIEN THI DANH GIA HOAT DONG');

		$curr_day = date('Y-m-d');

		try {

			if ($user->user_type == USER_TYPE_TEACHER) {

				$return_data['_data']['feature_info'] = array();
				$return_data['_data']['student_info'] = array();
				$return_data['_data']['data_info'] 	= array();
				$return_data['_msg_code'] 				= MSG_CODE_TRUE;

				$return_data['_data']['title'] = ($feature_type == FEATURE_TYPE_ACTIVITI) ? $psI18n->__('Evaluate activities') : $psI18n->__('Evaluate subject');

				$ps_member = PsMemberModel::getMember($user->member_id);

				if ($ps_member) {

					// neu feature_type == 0 lay thong tin hoat dong
					if ($feature_type == FEATURE_TYPE_ACTIVITI) {
						// Kiem tra hoat dong voi giao vien va lay thong tin hoat dong
						$features = $this->db->table(TBL_FEATURE_BRANCH . ' as FB')
							->leftjoin(TBL_FEATURE_BRANCH_TIMES . ' as FBT', 'FBT.ps_feature_branch_id', '=', 'FB.id')
							->leftjoin(TBL_FEATURE . ' as F', 'F.id', '=', 'FB.feature_id')
							->leftJoin(CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', 'FB.ps_image_id')
							->leftJoin(TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'FBT.ps_class_room_id')
							->selectRaw('FB.id as id, FB.mode as option_mode, FB.name as feature_title, FB.is_depend_attendance AS is_depend_attendance, I.file_name, FBT.start_time as start_at, FBT.end_time as end_at, CR.title as class_room_title, Null as note')
							->where('F.ps_customer_id', $ps_member->ps_customer_id)
							->whereDate('FBT.start_at', '<=', $curr_day)
							->whereDate('FBT.end_at', '>=', $curr_day)
							->where('F.is_activated', STATUS_ACTIVE)
							->where('FB.is_activated', STATUS_ACTIVE)
							->where(function ($query) use ($ps_member) {
								$query->where('FB.ps_obj_group_id', $ps_member->ps_obj_group_id)->orwhereNull('FB.ps_obj_group_id');
								$query->where('FB.ps_workplace_id', $ps_member->ps_workplace_id)->orwhereNull('FB.ps_workplace_id');
							})
							->where('FB.id', $feature_id)->get()->first();
					} elseif ($feature_type == FEATURE_TYPE_SUBJECT) {

						// $feature_id là PsServiceCourseSchedules.id

						// neu feature_type !== 0 lay thong tin mon hoc
						$features = $this->db->table(TBL_PS_SERVICE_COURSES . ' as SC')
							->join(TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', 'SCS.ps_service_course_id', '=', 'SC.id')
							->join(CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'SC.ps_service_id')
							->leftJoin(CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', 'S.ps_image_id')
							->selectRaw('SC.id as id, SC.title as feature_title, I.file_name, S.mode as option_mode ')
							->where('S.ps_customer_id', $ps_member->ps_customer_id)
							/*->whereDate ( 'SC.start_at', '<=', $curr_day )
						->whereDate ( 'SC.end_at', '>=', $curr_day )*/
							->whereDate('SCS.date_at', $curr_day)
							->where('S.is_activated', STATUS_ACTIVE)
							->where('SCS.id', $feature_id)->distinct()->get()->first();
					}

					if ($features) {

						$feature_info = new \stdClass();

						$feature_info->feature_id = $feature_id;
						$feature_info->feature_title = $features->feature_title;
						$feature_info->icon = PsString::getUrlPsImage($features->file_name);

						//$feature_info->option_mode = ($feature_type == FEATURE_TYPE_ACTIVITI) ? $features->option_mode : 2;

						$feature_info->option_mode = $features->option_mode;

						// Lay danh sach hoc sinh co mat hom nay theo giao vien
						/*
						$students = $this->db->table ( CONST_TBL_STUDENT . ' as S' )
						->join ( CONST_TBL_STUDENTCLASS . ' as SC', 'SC.student_id', '=', 'S.id' )
						->join ( CONST_TBL_MYCLASS . ' as M', 'M.id', '=', 'SC.myclass_id' )
						->join ( CONST_TBL_PS_LOGTIMES . ' as L', 'L.student_id', '=', 'S.id' )
						->select ( 'S.id as student_id', 'S.nick_name as nickname', 'S.birthday as birthday', 'S.first_name as first_name', 'S.last_name as last_name', 'S.avatar as avatar', 'S.ps_customer_id as ps_customer_id' )
						->where ( 'M.id', $ps_member->myclass_id )
						->whereDate ( 'L.login_at', $curr_day )
						->whereIn ( 'S.status', [ 
								STUDENT_HT,
								STUDENT_CT 
						] )->whereDate ( 'SC.start_at', '<', $curr_day )
						
						->whereRaw('(DATE_FORMAT(SC.stop_at, "%Y%m%d") > DATE_FORMAT("' . $curr_day . '", "%Y%m%d") OR SC.stop_at IS NULL )')
						
						->where ( 'SC.is_activated', STATUS_ACTIVE )
						->where ( 'M.is_activated', STATUS_ACTIVE )
						->where ( 'S.ps_customer_id', $ps_member->ps_customer_id )
						->orderBy ( 'S.last_name' )->get ();
						*/

						if ($feature_type == FEATURE_TYPE_ACTIVITI) { // Neu la Hoat dong thi lay theo danh sach Lop hoc sinh

							if ($features->is_depend_attendance == STATUS_ACTIVE)
								$students = StudentModel::getStudentsByLogValueOfClass($ps_member->myclass_id, $curr_day, STATUS_ACTIVE);
							else
								$students = StudentModel::getStudentsOfClass($ps_member->myclass_id, $curr_day);
						} elseif ($feature_type == FEATURE_TYPE_SUBJECT) { // Neu la mon hoc thi lay Danh sach theo Khoa hoc

							$students = StudentModel::getStudentsOfServiceCourse($feature_id, $curr_day);
						}

						$_student = $student_info = $option_info = array();

						foreach ($students as $student) {

							$_student = array();

							$_student['student_id'] 	= $student->student_id;

							$_student['image'] 		= ($student->avatar != '') ? PsString::getUrlMediaAvatar($student->cache_data, $student->year_data, $student->avatar, MEDIA_TYPE_STUDENT) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR_STUDENT;

							$_student['first_name'] 	= $student->first_name;

							$_student['last_name'] 	= $student->last_name;

							//$_student ['nickname'] 		= ($student->nick_name != '') ? $student->nick_name : $student->last_name;

							$_student['nickname'] 		= $student->first_name . " " . $student->last_name;

							if ($feature_type == FEATURE_TYPE_ACTIVITI) {
								// Lay ma hoat dong ma hoc sinh da duoc danh gia
								$rate_features = FeatureOptionModel::getRateFeature($student->student_id, $feature_id, $curr_day, $ps_member->ps_customer_id);
							} elseif ($feature_type == FEATURE_TYPE_SUBJECT) {

								//$rate_features = FeatureOptionModel::getRateServiceCourses ( $student->student_id, $feature_id, $curr_day, $ps_member->ps_customer_id );

								$rate_features = StudentServiceCourseCommentModel::getRateService($student->student_id, $feature_id);
							}

							foreach ($rate_features as $rate_feature) {

								$_option_info = array();

								$_option_info['option_id'] = $rate_feature->feature_option_feature_id;
								$_option_info['option_title'] = $rate_feature->name;
								$_option_info['option_note']  = $rate_feature->note;
								array_push($option_info, $_option_info);
							}

							$_student['option_info'] = $option_info;

							$option_info = array();

							array_push($student_info, $_student);
						}

						// Lay thong tin cac option danh gia
						if ($feature_type == FEATURE_TYPE_ACTIVITI) {
							$feature_options = $this->db->table(TBL_FEATURE_OPTION . ' as FO')->join(TBL_FEATURE_OPTION_FEATURE . ' as FOF', 'FOF.feature_option_id', '=', 'FO.id')->select('FOF.id  as option_id', 'FO.name as option_title', 'FOF.type as option_type', 'FOF.id as feature_option_feature_id')->where('FOF.feature_branch_id', $feature_id)->where('FO.is_activated', STATUS_ACTIVE)->whereRaw('(FO.ps_customer_id = ? OR FO.ps_customer_id IS NULL)', array(
								$ps_member->ps_customer_id
							))->orderBy('FOF.order_by')->get();
						} elseif ($feature_type == FEATURE_TYPE_SUBJECT) {

							$feature_options = $this->db->table(TBL_FEATURE_OPTION . ' as FO')
								->join(TBL_FEATURE_OPTION_SUBJECT . ' as FOS', 'FOS.feature_option_id', '=', 'FO.id')
								->join(CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'FOS.ps_service_id')
								->join(TBL_PS_SERVICE_COURSES . ' as SC', 'S.id', '=', 'SC.ps_service_id')
								->join(TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', 'SCS.ps_service_course_id', '=', 'SC.id')
								->select('FOS.id  as option_id', 'FO.name as option_title', 'FOS.type as option_type', 'FOS.id as feature_option_feature_id')
								->where('SCS.id', $feature_id)
								->where('FO.is_activated', STATUS_ACTIVE)
								->whereRaw('(FO.ps_customer_id = ? OR FO.ps_customer_id IS NULL)', array(
									$ps_member->ps_customer_id
								))->orderBy('FOS.order_by')->get();
						}

						$data = array();

						foreach ($feature_options as $feature_option) {

							$data_info = array();

							$data_info['option_id'] 	= $feature_option->feature_option_feature_id;

							$data_info['option_title'] = $feature_option->option_title;

							$data_info['option_type'] 	= $feature_option->option_type;

							array_push($data, $data_info);
						}

						$return_data['_data']['feature_info'] = $feature_info;
						$return_data['_data']['student_info'] = $student_info;
						$return_data['_data']['data_info'] 	= $data;
						$return_data['_msg_code'] 				= MSG_CODE_TRUE;
						$return_data['_msg_text'] 	= $return_data['message'] = 'TRUE';
					}
				}
			}
		} catch (Exception $e) {

			$this->WriteLog('-- BEGIN ERROR--: HIEN THI DANH GIA HOAT DONG');

			$this->WriteLogError($e->getMessage(), $user);

			$this->WriteLog('-- END ERROR--: HIEN THI DANH GIA HOAT DONG');

			$return_data['_msg_code'] 	= MSG_CODE_500;

			$return_data['_msg_text'] 	= $return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// Luu danh gia hoat dong
	public function saveRateFeatureStudent(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$return_data = array();

		$return_data['_msg_code'] 	= MSG_CODE_FALSE;
		$return_data['_data'] 		= [];
		$return_data['_msg_text'] 	= $return_data['message'] = 'FALSE';

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		// get data from URI
		$body = $request->getParsedBody();

		$student_features 	  = isset($body['student_features']) ? $body['student_features'] : null;
		$feature_type 		  = isset($body['feature_type']) ? $body['feature_type'] : '';
		$feature_branch_id 	  = isset($body['feature_id']) ? $body['feature_id'] : '';

		//$this->WriteLog ( '-- BEGIN: LUU DANH GIA HOAT DONG' );
		//$this->WriteLog ( "USER ID: " . $user->id);
		//$this->WriteLog ( "USERNAME: " . $user->username);		
		//$this->WriteLog ( 'LOAI: 0- HOAT DONG; 1-HOC NGOAI KHOA: '.$feature_type);
		//$this->WriteLog ( 'ITEM ID: '.$feature_branch_id);		

		$arr_student_features = json_decode($student_features, true);

		if (count($arr_student_features) <= 0) {

			$return_data['_error_code'] = MSG_CODE_FALSE;

			$return_data['_msg_code']   = MSG_CODE_500;

			$return_data['message']     = $psI18n->__('Need to select students to perform');

			$return_data['_msg_text']   = $psI18n->__('Need to select students to perform');

			$this->WriteLog('-- ERROR: Khong co hoc sinh');

			$this->WriteLog('-- END: LUU DANH GIA HOAT DONG');

			return $response->withJson($return_data);
		}

		if ($user->user_type != USER_TYPE_TEACHER) {

			$return_data['_error_code'] = MSG_CODE_FALSE;

			$return_data['_msg_code']   = MSG_CODE_500;

			$return_data['message']     = $psI18n->__('You do not have permission to perform this function.');

			$return_data['_msg_text']   = $psI18n->__('You do not have permission to perform this function.');

			$this->WriteLog('-- ERROR: User khong phai giao vien');

			$this->WriteLog('-- END: LUU DANH GIA HOAT DONG');

			return $response->withJson($return_data);
		}

		$curr_day = date('Y-m-d');

		try {

			FeatureOptionFeatureModel::beginTransaction();

			$sql = false;

			$feature_title = '';

			if ($feature_type == FEATURE_TYPE_ACTIVITI) {

				$features = $this->db->table(TBL_FEATURE_BRANCH . ' as FB')
					->select('FB.name as feature_title')
					->where('FB.is_activated', STATUS_ACTIVE)
					->where('FB.id', $feature_branch_id)->get()->first();

				$feature_title = $features->feature_title;
			} elseif ($feature_type == FEATURE_TYPE_SUBJECT) {

				$subject = $this->db->table(TBL_PS_SERVICE_COURSES . ' as SC')
					->join(TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', 'SCS.ps_service_course_id', '=', 'SC.id')
					->join(CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'SC.ps_service_id')
					->select('SC.title as feature_title')
					->where('S.is_activated', STATUS_ACTIVE)
					->where('SCS.id', $feature_branch_id)->get()->first();

				$feature_title = $subject->feature_title;
			}

			// Lay thong tin giao vien va lop hoc			
			$ps_member = PsMemberModel::getMember($user->member_id);

			$push_notication = false;

			if ($ps_member) {

				// Uu tien lay co so theo Lop hoc: $ps_member->ps_workplace_id
				$_workplace_id = $ps_member->ps_workplace_id > 0 ? $ps_member->ps_workplace_id : $ps_member->member_workplace_id;

				//$ps_work_places  = PsWorkPlacesModel::findById($_workplace_id);

				$ps_work_places  = PsWorkPlacesModel::getColumnById($_workplace_id, 'is_notication_activities, from_time_notication_activities, to_time_notication_activities');


				if ($ps_work_places) {

					$push_notication 					= 	$ps_work_places->is_notication_activities;

					$from_time_notication_activities 	= 	$ps_work_places->from_time_notication_activities;

					$to_time_notication_activities 		= 	$ps_work_places->to_time_notication_activities;

					if ($push_notication) {

						$current_date_time = strtotime(date("Y-m-d H:i"), time());

						$from_date_time    = strtotime(date("Y-m-d H:i", strtotime(date('Y-m-d') . ' ' . $from_time_notication_activities)));

						$to_date_time      = strtotime(date("Y-m-d H:i", strtotime(date('Y-m-d') . ' ' . $to_time_notication_activities)));

						if ($from_date_time <= $current_date_time && $current_date_time <= $to_date_time) {

							$push_notication = true;
						} else {
							/*
							$this->WriteLog ( '-- TIME:' .date("Y-m-d H:i"), time());
							
							$this->WriteLog ( '-- FROM: '.$from_time_notication_activities. ' :: '.date("Y-m-d H:i" ,strtotime(date('Y-m-d').' '.$from_time_notication_activities)) );
							
							$this->WriteLog ( '-- TO: '.$to_time_notication_activities. ' :: '.date("Y-m-d H:i" ,strtotime(date('Y-m-d').' '.$to_time_notication_activities)));
							
							$this->WriteLog ( '-- HET GIO GUI:' );
							*/
							$push_notication = false;
						}
					}
				}
			}

			// So luong danh gia - nhan xet bang go text
			$note_sum = 0;

			foreach ($arr_student_features as $student_feature) {

				$student_id = (int) $student_feature['student_id'];

				if ($student_id > 0) {

					$text_content = '';

					$option_infos = $student_feature['option_info'];

					$number_option_feature = 0;

					if ($feature_type == FEATURE_TYPE_ACTIVITI) {

						// Xoa danh gia hoat dong cu neu co
						$this->db->table(TBL_STUDENT_FEATURE)
							->join(TBL_FEATURE_OPTION_FEATURE . ' as FOF', 'FOF.id', '=', TBL_STUDENT_FEATURE . '.feature_option_feature_id')
							->where('FOF.feature_branch_id', $feature_branch_id)
							->whereDate('tracked_at', $curr_day)
							->where('student_id', $student_id)
							->delete();

						// Lay id đánh gia hoat dong
						foreach ($option_infos as $option_info) {

							$request_feature_option_feature_id = (int) $option_info['option_id'];

							if ($request_feature_option_feature_id > 0) {

								// Kiem tra lai du lieu									
								$featureOptionFeature 		= FeatureOptionFeatureModel::getFeatureOptionFeature($request_feature_option_feature_id);

								$feature_option_feature_id 	= $featureOptionFeature->id;

								// $this->WriteLog ( 'request_feature_option_feature_id:' . $request_feature_option_feature_id . '-feature_branch_id-' . $feature_branch_id . '-feature_option_feature_id:' . $feature_option_feature_id );

								//$feature_option_feature_id = $request_feature_option_feature_id;

								if ($feature_option_feature_id > 0) {

									$student_feature_note = (isset($option_info['option_note']) && $option_info['option_note'] != '') ? PsString::trimString($option_info['option_note']) : NULL;

									if (($student_feature_note == '') || ($student_feature_note != '' && vali::stringType()->length(0, 2000)->validate($student_feature_note))) {

										//$this->db->table ( TBL_STUDENT_FEATURE )->whereDate ( 'tracked_at', $curr_day )->where ( 'student_id', $student_id )->delete ();

										$number_option_feature++;

										// Them danh gia hoat dong
										$sql = $this->db->table(TBL_STUDENT_FEATURE)->insertGetId([
											'student_id' 				=> $student_id,
											'tracked_at' 				=> $curr_day,
											'feature_option_feature_id' => $feature_option_feature_id,
											'note' 						=> $student_feature_note,
											'time_at' 					=> null,
											'created_at' 				=> date("Y-m-d H:i:s"),
											'updated_at' 				=> date("Y-m-d H:i:s"),
											'user_created_id' 			=> $user->id,
											'user_updated_id' 			=> $user->id
										]);

										if ($featureOptionFeature->type == FEATUREOPTIONFEATURE_TYPE_INPUT) { // Neu la nhập nhan xet

											$text_content .= ($text_content != '') ? '; ' . $student_feature_note : $student_feature_note;

											$note_sum = $note_sum + 1;
										} else
											$text_content .= ($text_content != '') ? '; ' . $featureOptionFeature->f_option_name : $featureOptionFeature->f_option_name;
									}
								}
							}
						}

						// Gui notication
						if ($push_notication && $number_option_feature > 0) {

							$content = $psI18n->__('Comment activities') . ' ' . $feature_title . '' . PsString::newLine();

							$content .= $text_content;

							$this->pushNotificationRateFeature($user, $ps_member, $student_id, $content, $psI18n, $response);
						}
					} elseif ($feature_type == FEATURE_TYPE_SUBJECT) {

						// Xu ly theo mon hoc
						// Lay id danh gia mon hoc
						foreach ($option_infos as $option_info) {

							$request_feature_option_subject_id = (int) $option_info['option_id'];

							if ($request_feature_option_subject_id > 0) {

								// Kiem tra lai du lieu
								$feature_option_subject = FeatureOptionSubjectModel::getFeatureOptionSubject($request_feature_option_subject_id);

								//$this->WriteLog ( 'request_feature_option_subject_id:' . $request_feature_option_subject_id . '-feature_branch_id-' . $feature_branch_id . '-feature_option_subject_id:' . $feature_option_subject_id );

								$feature_option_subject_id = $feature_option_subject->id;

								if ($feature_option_subject_id > 0) {

									$student_feature_note = isset($option_info['option_note']) ? PsString::trimString($option_info['option_note']) : '';

									if (($student_feature_note == '') || ($student_feature_note != '' && vali::stringType()->length(0, 300)->validate($student_feature_note))) {


										// Xoa danh gia mon hoc cu neu co
										$this->db->table(TBL_STUDENT_SERVICE_COURSES_COMMENT)->where('ps_service_course_schedule_id', $feature_branch_id)->where('student_id', $student_id)->delete();

										$number_option_feature++;

										// Them danh gia mon hoc
										$sql = $this->db->table(TBL_STUDENT_SERVICE_COURSES_COMMENT)->insertGetId([
											'student_id' => (int)$student_id,
											'ps_service_course_schedule_id' => (int)$feature_branch_id,
											'feature_option_subject_id' 	=> (int)$feature_option_subject_id,
											'note' 							=> $student_feature_note,
											'created_at' 				=> date("Y-m-d H:i:s"),
											'updated_at' 				=> date("Y-m-d H:i:s"),
											'user_created_id' => $user->id,
											'user_updated_id' => $user->id
										]);

										if ($feature_option_subject->type == FEATUREOPTIONFEATURE_TYPE_INPUT) // Neu la nhập nhan xet
											$text_content .= ($text_content != '') ? '; ' . $student_feature_note : $student_feature_note;
										else
											$text_content .= ($text_content != '') ? '; ' . $feature_option_subject->f_option_name : $feature_option_subject->f_option_name;
									}
								}
							}
						}

						// Gui notication
						if ($push_notication && $number_option_feature > 0) {

							$content = $psI18n->__('Comment subject') . ' ' . $feature_title . '' . PsString::newLine();

							$content .= $text_content;

							$this->pushNotificationRateFeature($user, $ps_member, $student_id, $content, $psI18n, $response);
						}
					}
				}
			}

			// Cap nhat thong ke danh gia hoat dong			
			if (/*$ps_member->ps_customer_id == 6 && */$feature_type == FEATURE_TYPE_ACTIVITI && $feature_branch_id > 0 && $ps_member->myclass_id) {

				// Dem so luong danh gia cua hoat dong $feature_branch_id cua lop theo ngay
				$int_student_feature_count 	  = PsStudentFeatureModel::getStudentFeatureCount($ps_member->ps_customer_id, $ps_member->myclass_id, $feature_branch_id, $curr_day);

				// Tim xem da duoc luu lan nao chua
				$psFeatureBranchSynthetic = PsFeatureBranchSyntheticModel::getPsFeatureBranchSyntheticOfClassByDate($feature_branch_id, $ps_member->myclass_id, $curr_day);

				if (!$psFeatureBranchSynthetic) {

					$psFeatureBranchSynthetic = new PsFeatureBranchSyntheticModel();

					$psFeatureBranchSynthetic->ps_customer_id   = $ps_member->ps_customer_id;
					$psFeatureBranchSynthetic->ps_class_id      = $ps_member->myclass_id;
					$psFeatureBranchSynthetic->feature_id       = $feature_branch_id;
					$psFeatureBranchSynthetic->feature_sum      = $int_student_feature_count;
					$psFeatureBranchSynthetic->note_sum      	 = $note_sum;
					$psFeatureBranchSynthetic->tracked_at	     = $curr_day;
					$psFeatureBranchSynthetic->user_updated_id  = $user->id;

					$psFeatureBranchSynthetic->save();
				} else {
					$psFeatureBranchSynthetic->feature_sum       = $int_student_feature_count;
					$psFeatureBranchSynthetic->note_sum      	  = $note_sum;
					$psFeatureBranchSynthetic->user_updated_id   = $user->id;
					$psFeatureBranchSynthetic->updated_at   	  = date("Y-m-d H:i:s");
					$psFeatureBranchSynthetic->save();
				}
			}

			$return_data['_msg_code'] = MSG_CODE_TRUE;
			$return_data['_msg_text'] 	= $return_data['message'] = 'TRUE';

			FeatureOptionFeatureModel::commit();

			//$this->WriteLog ( '-- END: LUU DANH GIA HOAT DONG' );

		} catch (Exception $e) {

			FeatureOptionFeatureModel::rollback();

			$this->WriteLog('-- BEGIN ERROR--: LUU DANH GIA');

			$this->WriteLogError($e->getMessage(), $user);

			$this->WriteLog('-- END ERROR--: LUU DANH GIA');

			$return_data['_error_code'] = 0;
			$return_data['_msg_code']   = MSG_CODE_500;
			$return_data['_msg_text'] 	= $return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// Trang index - hien thi danh sach hoc sinh
	public function homeTest(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_data' => []
		);

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$curr_day = date('Y-m-d');

		if ($user && ($user->user_type == USER_TYPE_TEACHER)) {

			$return_data['_msg_code'] = MSG_CODE_TRUE;

			$ps_member = PsMemberModel::getMember($user->member_id);

			if ($ps_member) {

				//$return_data ['user'] = $user;

				$class_info = new \stdClass();

				$class_info->class_id = $ps_member->myclass_id;

				$class_info->class_name = $ps_member->myclass_name;

				$class_info->number_student = $ps_member->number_student;

				$class_info->date_at = PsDateTime::toFullDayInWeek($curr_day, $code_lang);

				if ($ps_member->myclass_id > 0) {

					// Danh sach hoc sinh trong lop
					$students = $this->db->table(CONST_TBL_STUDENT . ' as S')
						->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'S.ps_customer_id')
						->leftJoin(CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($curr_day) {
							$q->on('SC.student_id', '=', 'S.id')
								->where('SC.is_activated', STATUS_ACTIVE)
								->whereIn('SC.type', [
									STUDENT_HT,
									STUDENT_CT
								])
								->whereDate('SC.start_at', '<=', $curr_day)
								->whereRaw('(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $curr_day . '", "%Y%m%d") OR SC.stop_at IS NULL )');
						})
						->join(CONST_TBL_MYCLASS . ' as M', 'M.id', '=', 'SC.myclass_id')
						->leftJoin(CONST_TBL_PS_LOGTIMES . ' as LT', function ($q) use ($curr_day) {
							$q->on('LT.student_id', '=', 'S.id')->whereDate('LT.login_at', '=', date('Y-m-d', strtotime($curr_day)))->where('LT.log_value', STATUS_ACTIVE);
						})
						->select(
							'S.id as student_id',
							'S.birthday as birthday',
							'S.first_name as first_name',
							'S.last_name as last_name',
							'S.avatar as avatar',
							'S.year_data',
							'C.cache_data',
							'S.ps_customer_id as ps_customer_id',
							'LT.id AS logtime_id',
							'LT.log_value AS log_value'
						)
						->where('M.id', $ps_member->myclass_id)
						->whereRaw('S.deleted_at IS NULL')
						->where('SC.is_activated', STATUS_ACTIVE)
						->where('M.is_activated', STATUS_ACTIVE)
						->where('S.ps_customer_id', $ps_member->ps_customer_id)
						->orderBy('S.last_name')->distinct()->get();

					$data 		= array();
					$absent 	= 0; // dem so hoc sinh vang mat

					$class_info->number_student = count($students);

					foreach ($students as $student) {

						$data_info  = array();

						$data_info['student_id'] = $student->student_id;
						$data_info['avatar_url'] = ($student->avatar != '') ? PsString::getUrlMediaAvatar($student->cache_data, $student->year_data, $student->avatar, MEDIA_TYPE_STUDENT) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR_STUDENT;
						$data_info['age'] 		  = PsDateTime::getAgeMonth($student->birthday, $curr_day, true, $this->getUserLanguage($user));
						$data_info['first_name'] = $student->first_name;
						$data_info['last_name']  = $student->last_name;

						$status 				  = ($student->log_value) ? 1 : 0;

						$data_info['status'] 	  = $status;
						$absent = ($status == 0) ? $absent + 1 : $absent;
						array_push($data, $data_info);
					}

					$class_info->absent = $absent;

					$class_info->attendance = $class_info->number_student - $absent;

					$class_info->number_student = (string) count($students);

					$return_data['_data']['class_info'] = $class_info;

					$return_data['_data']['data_info'] = $data;

					$return_data['_data']['user_info']  	= $user;


					$date_at = date('Y-m-d');

					$students = $this->db->table(CONST_TBL_PS_LOGTIMES . ' as LT')

						->join(CONST_TBL_STUDENT . ' as S', 'S.id', '=', 'LT.student_id')
						->join(CONST_TBL_STUDENTCLASS . ' as SC', 'SC.student_id', '=', 'S.id')
						->join(CONST_TBL_MYCLASS . ' as M', 'M.id', '=', 'SC.myclass_id')
						->select('LT.*')
						->where('M.id', $ps_member->myclass_id)->whereIn('SC.type', [
							STUDENT_HT,
							STUDENT_CT
						])

						->whereDate('SC.start_at', '<=', $date_at)
						->whereRaw('(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $date_at . '", "%Y%m%d") OR SC.stop_at IS NULL )')
						->where('SC.is_activated', STATUS_ACTIVE)
						->where('M.is_activated', STATUS_ACTIVE)
						->where('S.ps_customer_id', $ps_member->ps_customer_id)
						->whereRaw('S.deleted_at IS NULL')
						->where('LT.log_value', '=', 1)
						->whereDate('LT.login_at', '=', date('Y-m-d', strtotime($date_at)))
						->orderBy('S.last_name')->get();

					$return_data['_data']['students']  	= $students;

					if ($user->ps_customer_id == 6) {

						$feature_branch_id = 950;

						// Dem so luong danh gia cua hoat dong $feature_branch_id cua lop theo ngay
						$int_student_feature_count 	  = PsStudentFeatureModel::getStudentFeatureCount($ps_member->ps_customer_id, $ps_member->myclass_id, $feature_branch_id, $curr_day);

						$return_data['_data']['count']  	= $int_student_feature_count;

						// Tim xem da duoc luu lan nao chua							
						$psFeatureBranchSynthetic = PsFeatureBranchSyntheticModel::getPsFeatureBranchSyntheticOfClassByDate($feature_branch_id, $ps_member->myclass_id, $curr_day);

						if (!$psFeatureBranchSynthetic) {

							$psFeatureBranchSynthetic = new PsFeatureBranchSyntheticModel();

							$psFeatureBranchSynthetic->ps_customer_id   = $ps_member->ps_customer_id;
							$psFeatureBranchSynthetic->ps_class_id      = $ps_member->myclass_id;
							$psFeatureBranchSynthetic->feature_id       = $feature_branch_id;
							$psFeatureBranchSynthetic->feature_sum      = $int_student_feature_count;
							$psFeatureBranchSynthetic->tracked_at	     = $date_at;
							$psFeatureBranchSynthetic->user_updated_id  = $user->id;
							$psFeatureBranchSynthetic->save();
						} else {
							$psFeatureBranchSynthetic->feature_sum      = $int_student_feature_count;
							$psFeatureBranchSynthetic->user_updated_id  = $user->id;
							$psFeatureBranchSynthetic->save();
						}
					}
				} else { // Neu khong phai giao viên cố dinh cua lop thi tim danh sach hoc sinh cua khoa hoc

					// Tìm khóa học cận giờ nhất
				}
			} else {

				$class_info = new \stdClass();

				$class_info->class_id 		= 0;

				$class_info->class_name 	= $psI18n->__('You are not assigned to any class.');

				$class_info->date_at 		= PsDateTime::toFullDayInWeek($curr_day, $code_lang);

				$class_info->number_student = 0;
				$class_info->absent 		= 0;
				$class_info->attendance 	= 0;

				$return_data['_data']['class_info'] 	= $class_info;

				$return_data['_data']['data_info']  	= array();

				$return_data['message'] 				= $psI18n->__('You are not assigned to any class.');
			}
		}

		return $response->withJson($return_data);
	}

	// Trang HomeTeacher - hien thi danh sach hoc sinh
	public function HomeTeacher(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$_date = date('Y-m-d');

		$return_data 				= array();
		$return_data['_msg_code'] 	= MSG_CODE_TRUE;
		$return_data['title'] 		= PsDateTime::toFullDayInWeek($_date, $code_lang);
		$return_data['school_logo_url'] = '';


		$return_data['_data'] 		= [];

		$queryParams = $request->getQueryParams();

		$class_id = isset($queryParams['class_id']) ? $queryParams['class_id'] : 0;

		try {

			if ($user->user_type == USER_TYPE_TEACHER) {
				// add menu
				$return_data['_data']['menus'] 		= $this->loadMenuHome($user, $psI18n);

				// Lay danh sach lop ma giao vien dang hoat dong
				$_list_class = array();

				// Lay co so cua giao vien
				$psWorkPlace = PsMemberModel::getPsWorkPlaceIdOfMember($user->member_id);

				$return_data['school_logo_url'] = PsString::getUrlLogoPsCustomer($psWorkPlace->cache_data, $psWorkPlace->logo);

				if ($psWorkPlace->pde_workplace_id > 0) // cơ sở theo Phòng ban công tác
					$ps_workplace_id = $psWorkPlace->pde_workplace_id;
				elseif ($psWorkPlace->m_workplace_id > 0)
					$ps_workplace_id = $psWorkPlace->m_workplace_id;

				$list_class = PsClassModel::getListMyClassOfWorkPlace($user->ps_customer_id, $ps_workplace_id);

				/*
				foreach ($list_class as $_class) {
					$_class->class_id = (int)$_class->class_id;
					$_class->active   = STATUS_NOT_ACTIVE;
					array_push($_list_class, $_class);
				}
				*/

				if ($class_id > 0) {
					$class = PsClassModel::getClassById($user->ps_customer_id, $class_id);
				} else {
					// Lay lop cua giao vien dang day
					$class = PsClassModel::getClassOfMember($user->member_id, $_date);
				}

				$class = PsClassModel::getClassOfMember($user->member_id, $_date);

				$class_info = new \stdClass();

				if ($class) {
					$check_invali = false;
					foreach ($list_class as $_class) {

						$_class->class_id = (int)$_class->class_id;

						if ((int)$_class->class_id == (int)$class->class_id) {
							$class_info->class_id 	 = (int)$class->class_id;
							$class_info->class_name  = $psI18n->__('Class') . ' ' . $class->class_name;
							$class_info->active    	 = STATUS_ACTIVE;
							$check_invali = true;

							$_class->active     = STATUS_ACTIVE;
							$_class->class_name  = $psI18n->__('Class') . ' ' . $class->class_name;
							//break;
						} else {
							$_class->active     = STATUS_NOT_ACTIVE;
						}

						array_push($_list_class, $_class);
					}

					if (!$check_invali) { // Neu list khong ton tai lop hoc nao thi list_class chi chua 1 lop hoc
						$class_info->class_id 	 = (int)$class->class_id;
						$class_info->class_name  = $psI18n->__('Class') . ' ' . $class->class_name;
						array_push($_list_class, array('class_id' => $class_info->class_id, 'class_name' =>  $class_info->class_name, 'active' => STATUS_ACTIVE));
					}

					// Lay danh sach hoc sinh cua lop
					$students = StudentModel::getStudentsStatusAttendanceOfClass($class_info->class_id, $_date);

					$class_info->tonghocsinh = count($students);
					$class_info->dihoc = 0;
					$class_info->nghicophep = 0;
					$class_info->nghikophep = 0;

					// Danh sach hoc sinh
					$data_students = array();

					foreach ($students as $student) {

						$student_info  = array();

						$student_info['student_id'] = (int)$student->student_id;
						$student_info['age'] 		  = PsDateTime::getAgeMonth($student->birthday, $_date, true, $code_lang);
						$student_info['first_name'] = (string)$student->first_name;
						$student_info['last_name']  = (string)$student->last_name;

						if ($student->avatar != '') {
							$avatar_url = PsString::getUrlMediaAvatar($student->cache_data, $student->year_data, $student->avatar, MEDIA_TYPE_STUDENT);
						} else {
							if ($student->sex == STATUS_ACTIVE) {
								$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE . 'boy_avatar_default.svg';
							} else {
								$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE . 'girl_avatar_default.svg';
							}
						}

						$student_info['avatar_url'] = $avatar_url;

						if ($student->log_value == CONSTANT_LOGVALUE_1) {
							$class_info->dihoc++;
							$student_info['status'] 	  = (int)CONSTANT_LOGVALUE_1;
						} elseif ($student->log_value == CONSTANT_LOGVALUE_0) {
							$class_info->nghicophep++;
							$student_info['status'] 	  = (int)CONSTANT_LOGVALUE_0;
						} else {
							$class_info->nghikophep++;
							$student_info['status'] 	  = (int)CONSTANT_LOGVALUE_2;
						}



						array_push($data_students, $student_info);
					}
				} else {
					$return_data['message'] = '<b>' . $psI18n->__('Không có lớp học hoặc lớp học bạn chọn không tồn tại.') . '</b>';
				}

				$return_data['_data']['list_class'] 		= $_list_class;
				$return_data['_data']['class_info'] 		= $class_info;
				$return_data['_data']['students'] 			= $data_students;
			} else {
				$return_data['message'] = '<b>' . $psI18n->__('You do not have access to this data') . '</b>';
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['_msg_text'] = $return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	/**
	 * load menu he thong tren man hinh home
	 *
	 * @author thangnc
	 *
	 * @param  $user - mixed
	 * @param  $psI18n - obj
	 * @return mixed
	 **/
	protected function loadMenuHome($user, $psI18n)
	{

		$menus = array();

		$configs = $this->default_setting_app;
		$user_app_config = json_decode($user->app_config);

		$app_config_color = (isset($user_app_config->style) && $user_app_config->style != '') ? $user_app_config->style : APP_CONFIG_STYLE;

		$check_style_color = false;
		foreach ($configs['style'] as $value) {
			if ($app_config_color == $value) {
				$check_style_color = true;
				break;
			}
		}
		if (!$check_style_color)
			$app_config_color = APP_CONFIG_STYLE;


		$url = PsString::getUrlIconMenuApp() . 'KidsSchoolTeacher/' . $app_config_color . '/';

		// Dan do
		$menu = array('screen_code' => PS_CONST_SCREEN_ADVICE, 'name' => $psI18n->__('Advice'),  'number_notification' => 0, 'icon_url' => $url . 'dando.png');
		array_push($menus, $menu);

		// Xin nghi
		$menu = array('screen_code' => PS_CONST_SCREEN_OFFSCHOOL, 'name' => $psI18n->__('Absent'),  'number_notification' => 0, 'icon_url' => $url . 'xinnghi.png');
		array_push($menus, $menu);

		// Album
		$menu = array('screen_code' => PS_CONST_SCREEN_ALBUMS, 'name' => $psI18n->__('Albums'),  'number_notification' => 0, 'icon_url' => $url . 'album.png');
		array_push($menus, $menu);

		// Tin tuc
		$menu = array('screen_code' => PS_CONST_SCREEN_NEWS, 'name' => $psI18n->__('News'),  'number_notification' => 0, 'icon_url' => $url . 'news.png');
		array_push($menus, $menu);

		// Thông báo
		$menu = array('screen_code' => PS_CONST_SCREEN_CMSNOTIFICATION, 'name' => $psI18n->__('Message'),  'number_notification' => 0, 'icon_url' => $url . 'thongbao.png');
		array_push($menus, $menu);

		return $menus;
	}

	/**
	 * Lưu điểm danh đến hàng loạt
	 * @author Nguyen Chien Thang - kidsschool.vn
	 * 
	 * @param resource $user - User login app
	 * @param resource $member - doi tuong giao vien
	 * @param array $arr_students - mang thong tin hoc sinh do client gui len
	 * @param string $date_at - yyyy-mm-dd
	 * @param string $time_pushNoticationLoginAt - Thơi gian cho phép gửi thông báo
	 * 
	 * @return boolean
	 **/
	protected function processAttendanceIn($user, $ps_member, array $arr_students, $date_at, $time_pushNoticationLoginAt = null, $pushNoticationUpdate = false)
	{

		// Lay cac hoc sinh co ID thuoc mang này để xử lý (Loại bỏ ID giả chèn đưa lên) Cần sửa Loại bỏ các học sinh mà Bố mẹ đã xin nghỉ và được duyệt
		$students = $this->db->table(CONST_TBL_STUDENT . ' as S')
			->join(CONST_TBL_STUDENTCLASS . ' as SC', 'SC.student_id', '=', 'S.id')
			->join(CONST_TBL_MYCLASS . ' as M', 'M.id', '=', 'SC.myclass_id')
			->select('S.id as student_id')
			->where('M.id', $ps_member->myclass_id)->whereIn('SC.type', [
				STUDENT_HT,
				STUDENT_CT
			])
			->whereDate('SC.start_at', '<=', $date_at)
			->whereRaw('(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $date_at . '", "%Y%m%d") OR SC.stop_at IS NULL )')
			->where('SC.is_activated', STATUS_ACTIVE)
			->where('M.is_activated', STATUS_ACTIVE)
			->where('S.ps_customer_id', $ps_member->ps_customer_id)
			->whereRaw('S.deleted_at IS NULL')
			->orderBy('S.last_name')->get();

		if (count($students) <= 0) { // Nếu lớp không có học sinh thì báo lỗi
			return false;
		}

		if (count($arr_students) <= 0) { // Nếu không có hoc sinh gửi lên => Xóa hết

			// Mang chua ID hoc sinh cua lop
			$array_student_id = array();

			foreach ($students as $student) {

				array_push($array_student_id, (int) $student->student_id);
			}

			$delete_logtime = $this->db->table(CONST_TBL_PS_LOGTIMES)->whereIN('student_id', $array_student_id)->whereRaw('DATE_FORMAT(login_at, "%Y%m%d") = DATE_FORMAT("' . $date_at . '", "%Y%m%d")')->delete();

			$delete_service_diary = $this->db->table(TBL_STUDENT_SERVICE_DIARY)->whereIN('student_id', $array_student_id)->whereRaw('DATE_FORMAT(tracked_at, "%Y%m%d") = DATE_FORMAT("' . $date_at . '", "%Y%m%d")')->delete();

			// Lay so luong diem danh den
			$login_count = 0;
			$psAttendancesSynthetic = PsAttendancesSyntheticModel::getAttendanceSyntheticByDate($ps_member->myclass_id, $date_at);

			if (!$psAttendancesSynthetic) {

				$psAttendancesSynthetic = new PsAttendancesSyntheticModel();
				$psAttendancesSynthetic->ps_customer_id   = $ps_member->ps_customer_id; 
				$psAttendancesSynthetic->ps_class_id      = $ps_member->myclass_id;
				$psAttendancesSynthetic->login_sum        = count($login_count);
				$psAttendancesSynthetic->logout_sum	   = 0;
				$psAttendancesSynthetic->tracked_at	   = $date_at;
				$psAttendancesSynthetic->user_updated_id  = $user->id;
				$psAttendancesSynthetic->save();
			} else {
				$psAttendancesSynthetic->login_sum       = count($login_count);
				$psAttendancesSynthetic->save();
			}

			return true;
		} else { // Co học sinh gửi lên

			// Điều kiên gưi thông báo
			$acess_pushNotication = false;

			// Lay cấu hình gửi thông báo Điểm danh đến
			if ($date_at == date('Y-m-d')) {

				if ($time_pushNoticationLoginAt == "00:00:00" || $time_pushNoticationLoginAt == '') { // neu khong cau hinh thi gui thong bao

					$acess_pushNotication = true;
				} else { // Gui thong bao diem danh truoc gio cau hinh trong co so

					$config_time  = date("Y-m-d H:i", strtotime(date('Y-m-d') . ' ' . $time_pushNoticationLoginAt));

					$current_time = date("Y-m-d H:i", time());

					if (strtotime($current_time) <= strtotime($config_time))
						$acess_pushNotication = true;
				}
			}

			$array_student_id_remove = array(); // Mang chứa ID hoc sinh se xoa bo trong bang diem danh

			$arr_student_id_logtime  = array(); // Mang chứa ID hoc sinh do Client push lên

			// Mang chua ID hoc sinh cua lop
			$array_student_id = array();
			/*
			foreach ( $students as $student ) {
				
				foreach ( $arr_students as $key => $obj_logtime ) {
					
					if ($obj_logtime ['studentID'] == $student->student_id) {
						
						array_push ( $arr_student_logtime, $obj_logtime );
					
					} elseif ($student->student_id > 0) {
						array_push ( $array_student_id_remove, $student->student_id );
					}
				}				
			}
			
			*/

			foreach ($arr_students as $obj_logtime) {
				if ($obj_logtime['studentID'] > 0)
					array_push($arr_student_id_logtime, $obj_logtime['studentID']);
			}

			foreach ($students as $student) {

				array_push($array_student_id, (int) $student->student_id);

				if (!in_array($student->student_id, $arr_student_id_logtime)) {
					array_push($array_student_id_remove, $student->student_id);
				}
			}

			// Xoa logtime co student_id thuoc mang $str_student_id
			if (count($array_student_id_remove) > 0) {

				$delete_logtime = $this->db->table(CONST_TBL_PS_LOGTIMES)->whereIN('student_id', $array_student_id_remove)->whereRaw('DATE_FORMAT(login_at, "%Y%m%d") = DATE_FORMAT("' . $date_at . '", "%Y%m%d")')->delete();
			}

			if (count($array_student_id) > 0) {
				// Xóa dịch vụ của tất cả học sinh trong lớp
				$delete_service_diary = $this->db->table(TBL_STUDENT_SERVICE_DIARY)->whereIN('student_id', $array_student_id)->whereRaw('DATE_FORMAT(tracked_at, "%Y%m%d") = DATE_FORMAT("' . $date_at . '", "%Y%m%d")')->delete();
			}

			foreach ($arr_students as $student_logtime) {

				$student_id   = (int) $student_logtime['studentID'];

				$relative_id  = isset($student_logtime['relative_id']) ? (int)$student_logtime['relative_id'] : null;

				$time_at 	  = $student_logtime['time_at'];

				$log_at 	  = $date_at . ' ' . $time_at; 

				$log_code = isset($student_logtime['log_code']) ? (string)$student_logtime['log_code'] : null;


				$log_at_to_db = date('Y-m-d H:i:s', strtotime($log_at));

				// Kiem tra hoc sinh diem danh chua				
				$logtime = PsLogtimesModel::where('student_id', $student_id)->whereRaw('DATE_FORMAT(login_at, "%Y%m%d") = DATE_FORMAT("' . $date_at . '", "%Y%m%d")')->first();

				$arr_service_used_name = array(); // Mang lưu dich vụ sử dụng

				if (!$logtime) { // Lưu mới
					$logtime = new PsLogtimesModel();
					$logtime->student_id = (int)$student_id;
					$logtime->login_at   = $log_at_to_db;
					$logtime->login_relative_id = $relative_id;
					$logtime->login_member_id   = (int)$ps_member->id;
					$logtime->log_value = 1;
					$logtime->log_code= $log_code;
					$logtime->user_created_id = (int)$user->id;
					$logtime->created_at = date("Y-m-d H:i:s");
					$logtime->updated_at = date("Y-m-d H:i:s");
					$logtime->save();

					// Lay dich vu hoc sinh dang ky
					$services = ServiceModel::getService($student_id, $user->ps_customer_id, $date_at);
					if ($services) {
						foreach ($services as $key => $service) {
							$insert_service_diary = $this->db->table(TBL_STUDENT_SERVICE_DIARY)->insertGetId([
								'student_id' => (int) $student_id,
								'service_id' => (int) $service->id,
								'tracked_at' => $date_at,
								'user_created_id' => (int) $user->id,
								'created_at' => date("Y-m-d H:i:s"),
								'updated_at' => date("Y-m-d H:i:s")
							]);

							array_push($arr_service_used_name, $service->title);
						}
					}
				} else {
					// update lai logtime, Cập nhật điểm danh đến thì ko gửi Notication									
					$acess_pushNotication = $pushNoticationUpdate;

					$logtime->login_at 			= $log_at_to_db;
					$logtime->login_relative_id = $relative_id;
					$logtime->login_member_id 	= (int) $ps_member->id;
					$logtime->log_value 		= CONSTANT_LOGVALUE_1;
					$logtime->user_updated_id 	= (int) $user->id;
					$logtime->updated_at 		= date("Y-m-d H:i:s");
					$logtime->save();
				}

				// Fix cho KidsSchool
				//if ($ps_member->ps_customer_id == 6 || $ps_member->ps_customer_id == 14) 
				//$acess_pushNotication = true;

				if ($acess_pushNotication) {

					// BEGIN: Push notication

					// Lay thong tin hoc sinh
					$student_info = StudentModel::getStudentInfoByID($student_id);

					$params = array();
					$params['student_id'] = $student_id;
					$params['student_info'] = $student_info;
					$params['relative_id'] = $relative_id;
					$params['time_at'] = date('H:i d-m-Y', strtotime($log_at));

					$params['ps_member'] = $ps_member;
					$params['service_used_name'] = implode("; ", $arr_service_used_name);
					$this->pushNotificationAttendanceToSchool($user, $params, $logtime);
					// END: Push notication
				}
			} // end foreach ( $arr_student_logtime


			// Lay so luong diem danh den
			$login_count = PsLogtimesModel::getLoginCount($ps_member->ps_customer_id, $ps_member->myclass_id, $date_at);

			$psAttendancesSynthetic = PsAttendancesSyntheticModel::getAttendanceSyntheticByDate($ps_member->myclass_id, $date_at);

			if (!$psAttendancesSynthetic) {

				$psAttendancesSynthetic = new PsAttendancesSyntheticModel();
				$psAttendancesSynthetic->ps_customer_id   = $ps_member->ps_customer_id;
				$psAttendancesSynthetic->ps_class_id      = $ps_member->myclass_id;
				$psAttendancesSynthetic->login_sum        = count($login_count);
				$psAttendancesSynthetic->logout_sum	   = 0;
				$psAttendancesSynthetic->tracked_at	   = $date_at;
				$psAttendancesSynthetic->user_updated_id  = $user->id;
				$psAttendancesSynthetic->save();
			} else {
				$psAttendancesSynthetic->login_sum       = count($login_count);
				$psAttendancesSynthetic->save();
			}

			return true;
		} // End có học sinh gửi lên

	} // end function

	/**
	 * Lưu điểm danh Về hàng loạt
	 * @author Nguyen Chien Thang - kidsschool.vn
	 * 
	 * @param resource $user - User login app
	 * @param resource $member - doi tuong giao vien
	 * @param array $arr_students - mang thong tin hoc sinh do client gui len
	 * @param string $date_at - yyyy-mm-dd
	 * @param string $time_pushNoticationLogoutAt - Thơi gian cho phép gửi thông báo điểm danh về
	 * 
	 * @return boolean
	 **/
	protected function processAttendanceOut($user, $ps_member, $arr_students, $date_at, $time_pushNoticationLogoutAt = null, $pushNoticationUpdate = false)
	{

		// Lay cac hoc sinh co ID đã điểm danh đến
		$students = $this->db->table(CONST_TBL_STUDENT . ' as S')
			->join(CONST_TBL_STUDENTCLASS . ' as SC', 'SC.student_id', '=', 'S.id')
			->join(CONST_TBL_MYCLASS . ' as M', 'M.id', '=', 'SC.myclass_id')
			->join(CONST_TBL_PS_LOGTIMES . ' as LT', 'LT.student_id', '=', 'S.id')
			->select('S.id as student_id')
			->where('M.id', $ps_member->myclass_id)->whereIn('SC.type', [
				STUDENT_HT,
				STUDENT_CT
			])
			->whereDate('SC.start_at', '<=', $date_at)
			->whereRaw('(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $date_at . '", "%Y%m%d") OR SC.stop_at IS NULL )')
			->where('SC.is_activated', STATUS_ACTIVE)
			->where('M.is_activated', STATUS_ACTIVE)
			->where('S.ps_customer_id', $ps_member->ps_customer_id)
			->whereRaw('S.deleted_at IS NULL')
			->where('LT.log_value', '=', 1)
			->whereDate('LT.login_at', '=', date('Y-m-d', strtotime($date_at)))
			->orderBy('S.last_name')->get();

		if (count($students) <= 0) { // Ko có học sinh nào
			return false;
		}

		// Mang chua ID hoc sinh trong lớp đã được điểm danh đến = 1 (có đi học) va cần xóa điểm danh về
		$array_student_id_remove = array();

		$arr_student_id_logtime  = array(); // Mảng chứa ID học sinh từ Client gửi lên để điểm danh về

		// Mang chua ID hoc sinh trong lớp đã được điểm danh đến = 1 (có đi học)
		$array_student_id 		 = array();

		foreach ($arr_students as $obj_logtime) {
			if ($obj_logtime['studentID'] > 0)
				array_push($arr_student_id_logtime, $obj_logtime['studentID']);
		}

		foreach ($students as $student) {

			array_push($array_student_id, (int) $student->student_id);

			if (!in_array($student->student_id, $arr_student_id_logtime)) {
				array_push($array_student_id_remove, $student->student_id);
			}
		}

		if (count($arr_students) <= 0) { // Nếu không có hoc sinh gửi lên để điểm danh về => Xóa điểm danh về tất cả các học sinh đã đi học

			$this->db->table(CONST_TBL_PS_LOGTIMES)->whereIn('student_id', $array_student_id)->whereRaw('DATE_FORMAT(login_at, "%Y%m%d") = DATE_FORMAT("' . $date_at . '", "%Y%m%d")')->update([
				'logout_at' => null,
				'logout_relative_id' => null,
				'logout_member_id'   => null,
				'user_updated_id'	=> (int) $user->id,
				'updated_at'		=> date("Y-m-d H:i:s")
			]);
		} else {

			$acess_pushNotication = false;

			if ($date_at == date('Y-m-d')) {

				if ($time_pushNoticationLogoutAt == "00:00:00" || $time_pushNoticationLogoutAt == '') { // neu khong cau hinh thi gui thong bao

					$acess_pushNotication = true;
				} else { // Gui thong bao diem danh truoc gio cau hinh trong co so

					$config_time  = date("Y-m-d H:i", strtotime(date('Y-m-d') . ' ' . $time_pushNoticationLogoutAt));

					$current_time = date("Y-m-d H:i", time());

					if (strtotime($current_time) <= strtotime($config_time))
						$acess_pushNotication = true;
				}
			}

			// Lay logtime
			$logtimes = PsLogtimesModel::whereIn('student_id', $arr_student_id_logtime)->whereRaw('DATE_FORMAT(login_at, "%Y%m%d") = DATE_FORMAT("' . $date_at . '", "%Y%m%d")')->get();

			foreach ($logtimes as $logtime) {

				foreach ($arr_students as $student_logtime) {

					if ($student_logtime['studentID'] == $logtime->student_id) {

						if ($logtime->logout_at != '') { // Neu la cap nhat diem danh ve
							$acess_pushNotication = $pushNoticationUpdate;
						}

						$time_at = $student_logtime['time_at'];
						$log_at  = $date_at . ' ' . $time_at;
						$logout_at_to_db = date('Y-m-d H:i:s', strtotime($log_at));

						$logtime->logout_at 			= $logout_at_to_db;
						$logtime->logout_relative_id 	= isset($student_logtime['relative_id']) ? $student_logtime['relative_id'] : null;
						$logtime->logout_member_id 		= (int) $ps_member->id;
						$logtime->user_updated_id 		= (int) $user->id;
						$logtime->updated_at 			= date("Y-m-d H:i:s");

						if ($logtime->save() && $acess_pushNotication) {

							// BEGIN: Push notication

							// Lay thong tin hoc sinh
							$student_info = StudentModel::getStudentInfoByID($logtime->student_id);

							$params = array();

							$params['student_id'] 		= $logtime->student_id;
							$params['student_info'] 	= $student_info;
							$params['relative_id'] 	= $logtime->logout_relative_id;
							$params['time_at'] 		= date('H:i d-m-Y', strtotime($log_at));

							$params['ps_member'] 		= $ps_member;


							$this->pushNotificationAttendanceOutSchool($user, $params);

							// END: Push notication
						}
					}
				}
			}

			if (count($array_student_id_remove) > 0) {
				$this->db->table(CONST_TBL_PS_LOGTIMES)->whereIN('student_id', $array_student_id_remove)->whereRaw('DATE_FORMAT(login_at, "%Y%m%d") = DATE_FORMAT("' . $date_at . '", "%Y%m%d")')->update([
					'logout_at' => null,
					'logout_relative_id' => null,
					'logout_member_id'   => null,
					'user_updated_id'	=> (int) $user->id,
					'updated_at'		=> date("Y-m-d H:i:s")
				]);
			}
		}

		// Cap nhat so luong diem danh ve							
		$logout_sum = PsLogtimesModel::getLogoutCount($ps_member->ps_customer_id, $ps_member->myclass_id, $date_at);

		$psAttendancesSynthetic = PsAttendancesSyntheticModel::getAttendanceSyntheticByDate($ps_member->myclass_id, $date_at);

		if ($psAttendancesSynthetic) {
			$psAttendancesSynthetic->logout_sum       = count($logout_sum);
			$psAttendancesSynthetic->save();
		}

		return true;
	}

	/**
	 * pushNotificationAttendanceToSchool($user, array $params, $new = true
	 * Ham push notification khi diem danh den lop cua tung hoc sinh
	 *
	 * @author thangnc
	 *
	 * @param $user - mixed
	 * @param $params - array
	 * @param $new - boolean
	 * @return boolean
	 *
	 **/
	protected function pushNotificationAttendanceToSchool($user, array $params, $status_update = true)
	{

		if ($params['student_id'] < 0)
			return false;

		// Lay thong tin hoc sinh
		$student_info = $params['student_info'];

		// Lay danh sach nguoi than co quyen dua don cua hoc sinh
		$notication_relatives = RelativeModel::getRelativesByStudentId($params['student_id']);

		$registrationIds_ios = array();
		$registrationIds_android = array();
		$relative_login = '';

		foreach ($notication_relatives as $relative) {
			if ($relative->notification_token != '') {
				if ($relative->osname == PS_CONST_PLATFORM_IOS)
					array_push($registrationIds_ios, $relative->notification_token);
				else
					array_push($registrationIds_android, $relative->notification_token);
			}

			if (isset($params['relative_id']) && $relative->id == $params['relative_id']) {
				$relative_login = $relative->relationship . ' ' . $relative->first_name . ' ' . $relative->last_name;
			}
		}

		if (count($registrationIds_android) > 0 || count($registrationIds_ios) > 0) {
			$psI18n = new PsI18n($this->getUserLanguage($user));

			$notication_setting = new \stdClass();

			if (!$status_update) {
				$notication_setting->title = $psI18n->__('Notice of attendance of the baby') . " " . $student_info->first_name . " " . $student_info->last_name;
			} else {
				$notication_setting->title = $psI18n->__('Update attendance record of the baby') . " " . $student_info->first_name . " " . $student_info->last_name;
			}

			$notication_setting->subTitle = $psI18n->__('From teacher') . ' ' . $user->first_name . " " . $user->last_name;

			$notication_setting->tickerText = $psI18n->__('Attendance from KidsSchool.vn');

			$content = $psI18n->__('Login at') . ": " . $params['time_at'] . '. ';

			$content .= $psI18n->__('Relative login') . ": " . $relative_login . '. ';

			$content .= $psI18n->__('Teacher receives') . ": " . $user->first_name . " " . $user->last_name;

			if ($params['service_used_name'] != '')
				$content .= '. ' . $psI18n->__('Service use') . ": " . $params['service_used_name'];

			$notication_setting->message = $content;

			$notication_setting->lights = '1';
			$notication_setting->vibrate = '1';
			$notication_setting->sound = '1';

			$notication_setting->smallIcon = IC_SMALL_NOTIFICATION;
			$notication_setting->smallIconOld = 'ic_small_notification_old';

			$ps_member = $params['ps_member'];

			if ($ps_member->avatar != '') {
				$notication_setting->largeIcon = PsString::getUrlPsAvatar($ps_member->school_code, 1, $ps_member->avatar);
			} else
				$notication_setting->largeIcon = PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;

			$notication_setting->screenCode = PS_CONST_SCREEN_ATTENDANCE;
			$notication_setting->itemId = '0';
			$notication_setting->studentId = $params['student_id'];
			$notication_setting->clickUrl = '';

			// Deviceid registration firebase
			if ($registrationIds_ios > 0) {
				$notication_setting->registrationIds = $registrationIds_ios;
				$notification = new PsNotification($notication_setting);
				$result = $notification->pushNotification(PS_CONST_PLATFORM_IOS);
			}

			if ($registrationIds_android > 0) {
				$notication_setting->registrationIds = $registrationIds_android;
				$notification = new PsNotification($notication_setting);
				$result = $notification->pushNotification(PS_CONST_PLATFORM_ANDROID);
			}
		}

		return true;
	}

	/**
	 * pushNotificationAttendanceOutSchool($user, array $params, $new = true)
	 * Ham push notification khi hoc sinh roi truong
	 *
	 * @author thangnc
	 *        
	 * @param $user - mixed
	 * @param $params - array
	 * @param $new - boolean
	 * @return boolean
	 *
	 */
	protected function pushNotificationAttendanceOutSchool($user, array $params)
	{

		if ($params['student_id'] < 0)
			return false;

		// Lay thong tin hoc sinh
		$student_info = $params['student_info'];

		// Lay danh sach nguoi than co quyen dua don cua hoc sinh
		$notication_relatives = RelativeModel::getRelativesByStudentId($params['student_id']);

		$registrationIds_ios = array();
		$registrationIds_android = array();
		$relative_logout = '';

		foreach ($notication_relatives as $relative) {

			if ($relative->notification_token != '') {

				if ($relative->osname == PS_CONST_PLATFORM_IOS)
					array_push($registrationIds_ios, $relative->notification_token);
				else
					array_push($registrationIds_android, $relative->notification_token);
			}

			if (isset($params['relative_id']) && ($relative->id == $params['relative_id'])) {
				$relative_logout = $relative->relationship . ' ' . $relative->first_name . ' ' . $relative->last_name;
			}
		}

		if (count($registrationIds_android) > 0 || count($registrationIds_ios) > 0) {

			$psI18n = new PsI18n($this->getUserLanguage($user));

			$notication_setting = new \stdClass();

			$notication_setting->title = $psI18n->__('Update attendance record of the baby') . " " . $student_info->first_name . " " . $student_info->last_name . ' ' . $psI18n->__('was out');

			$notication_setting->subTitle = $psI18n->__('From teacher') . ' ' . $user->first_name . " " . $user->last_name;

			$notication_setting->tickerText = $psI18n->__('Attendance from KidsSchool.vn');

			$content = $psI18n->__('Logout at') . ": " . $params['time_at'] . '. ';

			$content .= $psI18n->__('Relative logout') . ": " . $relative_logout . '. ';

			$content .= $psI18n->__('Teacher handover') . ": " . $user->first_name . " " . $user->last_name;

			$notication_setting->message = $content;

			$notication_setting->lights = '1';
			$notication_setting->vibrate = '1';
			$notication_setting->sound = '1';

			$notication_setting->smallIcon = IC_SMALL_NOTIFICATION;
			$notication_setting->smallIconOld = 'ic_small_notification_old';

			$ps_member = $params['ps_member'];

			if ($ps_member->avatar != '') {
				//$notication_setting->largeIcon = PsString::getUrlPsAvatar ( $ps_member->school_code, 1, $ps_member->avatar );
				$notication_setting->largeIcon = PsString::getUrlMediaAvatar($ps_member->cache_data, $ps_member->s_year_data, $ps_member->avatar, MEDIA_TYPE_TEACHER);
			} else
				$notication_setting->largeIcon = PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;

			$notication_setting->screenCode = PS_CONST_SCREEN_ATTENDANCE;
			$notication_setting->itemId = $params['student_id'];
			$notication_setting->studentId = $params['student_id'];
			$notication_setting->clickUrl = '';

			// Deviceid registration firebase
			if ($registrationIds_ios > 0) {
				$notication_setting->registrationIds = $registrationIds_ios;
				$notification = new PsNotification($notication_setting);
				$result = $notification->pushNotification(PS_CONST_PLATFORM_IOS);
			}

			if ($registrationIds_android > 0) {
				$notication_setting->registrationIds = $registrationIds_android;
				$notification = new PsNotification($notication_setting);
				$result = $notification->pushNotification(PS_CONST_PLATFORM_ANDROID);
			}
		}

		return true;
	}

	/**
	 * Ham push notification khi danh gia hoat dong
	 *
	 * @author thangnc
	 *        
	 * @param  $user - mixed
	 * @param  $ps_member - mixed
	 * @param  $student_id - int
	 * @param  $psI18n - obj
	 * @return boolean
	 **/
	protected function pushNotificationRateFeature($user, $ps_member, $student_id, $content, $psI18n)
	{

		if ($student_id <= 0)
			return false;

		// Lay thong tin hoc sinh
		$student_info = StudentModel::getStudentInfoByID($student_id);

		// Lay danh sach nguoi than co quyen dua don cua hoc sinh
		$notication_relatives = RelativeModel::getRelativesByStudentId($student_id);

		$registrationIds_ios 		= array();
		$registrationIds_android 	= array();

		foreach ($notication_relatives as $relative) {
			if ($relative->notification_token != '') {
				if ($relative->osname == PS_CONST_PLATFORM_IOS)
					array_push($registrationIds_ios, $relative->notification_token);
				else
					array_push($registrationIds_android, $relative->notification_token);
			}
		}

		if (count($registrationIds_android) > 0 || count($registrationIds_ios) > 0) {

			$notication_setting = new \stdClass();

			$notication_setting->title 		= $psI18n->__('Comment student') . " " . $student_info->first_name . " " . $student_info->last_name;

			$notication_setting->subTitle 	= $psI18n->__('From teacher') . ' ' . $user->first_name . " " . $user->last_name;

			$notication_setting->tickerText = $psI18n->__('Message - KidsSchool.vn');

			$notication_setting->message 	= $content;

			$notication_setting->lights  = '1';
			$notication_setting->vibrate = '1';
			$notication_setting->sound   = '1';

			$notication_setting->smallIcon 	  = IC_SMALL_NOTIFICATION;
			$notication_setting->smallIconOld = 'ic_small_notification_old';

			if ($ps_member->avatar != '') {
				$notication_setting->largeIcon = PsString::getUrlMediaAvatar($ps_member->cache_data, $ps_member->s_year_data, $ps_member->avatar, MEDIA_TYPE_TEACHER);
			} else
				$notication_setting->largeIcon = PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;

			$current_time = date('H:i', time());

			if ($current_time >= '17:00')
				$notication_setting->screenCode = PS_CONST_SCREEN_TODAY_STUDENT;
			else
				$notication_setting->screenCode = PS_CONST_SCREEN_FEATURE;

			$notication_setting->itemId = '';

			$notication_setting->studentId = $student_id; // Thông báo ứng với học sinh này

			$notication_setting->clickUrl = '';

			// Deviceid registration firebase
			if ($registrationIds_ios > 0) {
				$notication_setting->registrationIds = $registrationIds_ios;
				$notification = new PsNotification($notication_setting);
				$result = $notification->pushNotification(PS_CONST_PLATFORM_IOS);
			}

			if ($registrationIds_android > 0) {
				$notication_setting->registrationIds = $registrationIds_android;
				$notification = new PsNotification($notication_setting);
				$result = $notification->pushNotification(PS_CONST_PLATFORM_ANDROID);
			}
		}

		return true;
	}
}
