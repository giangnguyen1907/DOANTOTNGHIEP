<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/28/2018
 * Time: 10:30 AM
 */

namespace Api\PsOffSchool\Controller;

use Api\PsMembers\Model\PsMemberModel;
use Api\PsOffSchool\Model\OffSchoolModel;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Exception;
use Respect\Validation\Validator as vali;
use App\Controller\BaseController;
use App\Authentication\PsAuthentication;
use Api\Users\Model\UserModel;
use App\Model\PsMobileAppAmountsModel;
use Api\Students\Model\StudentModel;
use App\PsUtil\PsFile;
use App\PsUtil\PsString;
use App\PsUtil\PsI18n;
use App\PsUtil\PsNotification;

class OffSchoolController extends BaseController
{

	public $container;

	protected $user_token;

	public function __construct(LoggerInterface $logger, $container, $app)
	{

		parent::__construct($logger, $container);

		$this->user_token = $app->user_token;
	}

	// Gui yeu cau xin nghi hoc - APP phu huynh gui den giao vien
	public function sendOffSchool(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array();

		$return_data['_msg_code'] = MSG_CODE_FALSE;

		$return_data['_msg_text'] = $return_data['message'] = $psI18n->__('Send off school error');

		$device_id = $request->getHeaderLine('deviceid');

		if (!PsAuthentication::checkDevice($user, $device_id)) {

			return $response->withJson($return_data);
		} elseif ($user->user_type == USER_TYPE_RELATIVE) {

			$body = $request->getParsedBody();

			$info = isset($body['info']) ? $body['info'] : '';

			$student_id = isset($info['student_id']) ? $info['student_id'] : '';

			// Kt quan hệ của người thân và học sinh
			$ps_student = StudentModel::getStudentForRelative($student_id, $user->member_id);

			if (!$ps_student) {

				$return_data = array(
					'_msg_code' => MSG_CODE_FALSE,
					'_msg_text' => $psI18n->__('You do not have access to this data'),
					'message' 	=> $psI18n->__('You do not have access to this data')
				);

				return $response->withJson($return_data);
			} else {

				// Check trang thai hoc sinh. Nếu không phải Đang học; Học thử thì báo lỗi
				if (($ps_student->type != STUDENT_HT) && ($ps_student->type != STUDENT_CT)) {

					$type = '';
					switch ($ps_student->type) {
						case STUDENT_TD:
							$type = 'Pause';
							break;

						case STUDENT_TN:
							$type = 'Graduating';
							break;

						case STUDENT_TH:
							$type = 'Stop learning';
							break;

						case STUDENT_GC:
							$type = 'Reservations';
							break;

						default:
							$type = 'Pause';
							break;
					}

					$_msg_text = sprintf($psI18n->__('This student is in a state %s. You cannot create a leave application.'), $psI18n->__($type));

					$return_data = array(
						'_msg_code' => MSG_CODE_FALSE,
						'_msg_text' => $_msg_text,
						'message'   => $_msg_text
					);

					return $response->withJson($return_data);
				}

				// Check quyen tao don: Chi nguoi bao ho chinh moi co quyen tao don
				if ($ps_student->is_role != 1) {	
					$return_data = array(
						'_msg_code' => MSG_CODE_FALSE,
						'_msg_text' => $psI18n->__('This function is only for guardian main'),
						'message' 	=> $psI18n->__('This function is only for guardian main')
					);

					return $response->withJson($return_data);
				}
			}

			// Kiem tra tai khoan
			$amount_info = PsMobileAppAmountsModel::checkAmountInfo($user->id);

			if (!$amount_info) {

				$return_data = array(
					'_msg_code' => MSG_CODE_PAYMENT,
					'_msg_text' => $psI18n->__('Your account has run out of money. Please recharge to continue using.'),
					'message' 	=> $psI18n->__('Your account has run out of money. Please recharge to continue using.')
				);

				return $response->withJson($return_data);
			}
		}

		$boolean_validator = false;

		if (count($info) <= 0) {

			return $response->withJson($return_data);
		} else {

			$description = isset($info['description']) ? PsString::trimString($info['description']) : null;

			$user_id = isset($info['user_id']) ? $info['user_id'] : null; //id của giáo viên nhận

			$student_id = isset($info['student_id']) ? $info['student_id'] : null;

			$from_date = isset($info['from_date']) ? $info['from_date'] : null;

			$to_date = isset($info['to_date']) ? $info['to_date'] : null;

			if (!$description || !$from_date || !$user_id || !$student_id || !$to_date) {

				return $response->withJson($return_data);
			} else {

				$from_date = date('Y-m-d', strtotime($from_date));

				$to_date = date('Y-m-d', strtotime($to_date));

				$chk_from_date = (int) vali::notEmpty()->dateTime('Y-m-d')
					->validate($from_date);

				$chk_to_date = (int) vali::notEmpty()->dateTime('Y-m-d')
					->validate($to_date);

				$chk_des = vali::notEmpty()->stringType()
					->length(1, 255)
					->validate($description);

				$boolean_validator = ($chk_from_date && $chk_to_date && $chk_des);
			}
		}

		// Kiem tra du lieu push len truoc khi lam tiep
		if ($boolean_validator) {

			$ps_work_place = $this->db->table(TBL_PS_WORK_PLACES)
				->select('config_time_receive_valid')
				->where('id', $ps_student->ps_workplace_id)
				->get()
				->first();

			if ($ps_work_place->config_time_receive_valid != null) {
				$hour = (int)date('H', strtotime($ps_work_place->config_time_receive_valid));
				$minutes = (int)date('i', strtotime($ps_work_place->config_time_receive_valid));
			} else {
				$hour = 24;
				$minutes = 0;
			}

			$firstDay = date('Y-m-d H:i', strtotime('-1 day', strtotime($from_date)));

			// Lấy ra thời gian cho phép xin nghỉ học
			$timeConfig = date('Y-m-d H:i', strtotime('+' . $hour . ' hour +' . $minutes . ' minutes', strtotime($firstDay)));

			// Thời gian gửi
			$curren_datetime = date('Y-m-d H:i');

			// Kiểm tra ngày xin nghỉ có hợp lệ
			$chk_date = $this->db->table(TBL_PS_OFF_SCHOOL)
				->select('id')
				->whereDate('from_date', '<=', $from_date)
				->whereDate('to_date', '>= ', $from_date)
				->where('student_id', $student_id)
				->get()
				->count();

			if ($chk_date > 0) {

				$return_data['_msg_text'] = $psI18n->__('The time of leave is the same as another application.');

				return $response->withJson($return_data);
			}

			// if (strtotime($curren_datetime) > strtotime($timeConfig) || strtotime($from_date) > strtotime($to_date)) {

			// 	$return_data['message'] = $return_data['_msg_text'] = $psI18n->__('The start time is invalid');

			// 	return $response->withJson($return_data);
			// }

			if (strtotime($from_date) > strtotime($to_date)) {

				$return_data['message'] = $return_data['_msg_text'] = $psI18n->__('The start time is invalid');

				return $response->withJson($return_data);
			}


			$checkStopAt = $this->db->table(CONST_TBL_STUDENTCLASS)
				->where('myclass_id', $ps_student->class_id)
				->where('student_id', $student_id)
				->where('is_activated', STATUS_ACTIVE)
				->select('stop_at')
				->get()
				->first();

			if ($checkStopAt && (($checkStopAt->stop_at != '' && (strtotime($to_date) > strtotime($checkStopAt->stop_at))))) {

				$return_data['message'] = $return_data['_msg_text'] = $psI18n->__('Ending time is invalid.');

				return $response->withJson($return_data);
			}

			try {

				OffSchoolModel::beginTransaction();

				// them du lieu vao bang PsOffSchool
				/*
				$newOffScholl = $this->db->table ( TBL_PS_OFF_SCHOOL )
					->insertGetId ( [ 
						'ps_customer_id' => $user->ps_customer_id,
						'description' => $description,
						'ps_workplace_id' => $ps_student->ps_workplace_id,
						'ps_class_id' => $ps_student->class_id,
						'relative_id' => $user->member_id,
						'date_at' => $curren_datetime,
						'user_id' => $user_id, // user_id nhan, id cua tbl user
						'is_activated' => 0,
						'from_date' => date ( 'Y-m-d', strtotime ( $from_date ) ),
						'to_date' => date ( 'Y-m-d', strtotime ( $to_date ) ),
						'student_id' => $student_id,
						'user_created_id' => $user->id,
						'user_updated_id' => $user->id,
						'created_at' => $curren_datetime,
						'updated_at' => $curren_datetime ] );
				*/

				$curren_time = date('Y-m-d H:i:s');

				$newOffScholl =	new OffSchoolModel();

				$newOffScholl->ps_customer_id 	= $user->ps_customer_id;
				$newOffScholl->description		= $description;
				$newOffScholl->ps_workplace_id	= $ps_student->ps_workplace_id;
				$newOffScholl->ps_class_id		= $ps_student->class_id;
				$newOffScholl->relative_id		= $user->member_id;
				$newOffScholl->date_at			= $curren_time;
				$newOffScholl->user_id			= $user_id;
				$newOffScholl->is_activated		= STATUS_NOT_ACTIVE;
				$newOffScholl->from_date		= date('Y-m-d', strtotime($from_date));
				$newOffScholl->to_date			= date('Y-m-d', strtotime($to_date));
				$newOffScholl->student_id		= $student_id;
				$newOffScholl->user_created_id	= $user->id;
				$newOffScholl->user_updated_id	= $user->id;
				$newOffScholl->created_at		= $curren_time;
				$newOffScholl->updated_at		= $curren_time;

				//$return_data ['_msg_code'] = ($newOffScholl) ? MSG_CODE_TRUE : MSG_CODE_FALSE;

				if ($newOffScholl->save()) {

					$return_data['_msg_code'] = MSG_CODE_TRUE;
					$return_data['message']   = $return_data['_msg_text'] = $psI18n->__('Send off school success');

					// Gui notication den giao vien
					$this->pushNotificationNewOffSchool($psI18n, $user, $newOffScholl);
				}
				// BEGIN: Push Nocation

				OffSchoolModel::commit();
			} catch (Exception $e) {

				$this->logger->err($e->getMessage());

				$return_data['_msg_code'] = MSG_CODE_500;

				$return_data['_msg_text'] = $return_data['message'] = $psI18n->__('Send off school error') . $e->getMessage();
			}
		}

		return $response->withJson($return_data);
	}

	// Lay chi tiet 1 lần xin nghỉ học
	public function detailOffSchool(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array();

		$return_data['_msg_code'] = MSG_CODE_TRUE;
		$return_data['message'] = $return_data['_msg_text'] = "TRUE";
		$return_data['_data'] = array();
		$return_data['_data']['title'] = $psI18n->__('Off shool detail');

		// get device_id app
		$device_id = $request->getHeaderLine('deviceid');

		$offschool_id = $args['offschool_id'];

		if (!offschool_id) {
			return $response->withJson($return_data);
		}

		try {

			if ($user->user_type == USER_TYPE_RELATIVE) {

				if (!PsAuthentication::checkDeviceUserRelative($user, $device_id)) {

					$return_data['_msg_code'] = MSG_CODE_NOT_REGISTER_DEVICEID;
					$return_data['_msg_text'] 	= $return_data['message'] = $psI18n->__('You have not confirmed the Terms to use. Please log out and log in again.');
					$return_data['_data'] = array();
					$return_data['_data']['title'] = $psI18n->__('Off shool detail');

					return $response->withJson($return_data);
				}

				$amount_info = PsMobileAppAmountsModel::checkAmountInfo($user->id);

				if (!$amount_info) {

					$return_data = array(
						'_msg_code' => MSG_CODE_PAYMENT,
						'message' => $psI18n->__('Your account has run out of money. Please recharge to continue using.'),
						'_msg_text' => $psI18n->__('Your account has run out of money. Please recharge to continue using.')
					);

					return $response->withJson($return_data);
				}

				$ps_off_school = $this->db->table(TBL_PS_OFF_SCHOOL . ' as OS')
					->join(CONST_TBL_USER . ' as U', 'OS.user_id', '=', 'U.id')
					->leftjoin(CONST_TBL_PS_MEMBER . ' as M', 'U.member_id', '=', 'M.id')
					->leftjoin(CONST_TBL_RELATIVE . ' as R', 'OS.relative_id', '=', 'R.id')
					->join(CONST_TBL_STUDENT . ' as S', 'OS.student_id', '=', 'S.id')
					->join(CONST_TBL_STUDENTCLASS . ' as SC', 'SC.student_id', '=', 'S.id')
					->join(CONST_TBL_MYCLASS . ' as MC', 'SC.myclass_id', '=', 'MC.id')
					->select('OS.id', 'OS.from_date', 'OS.description', 'OS.is_activated', 'OS.to_date', 'OS.date_at', 'M.avatar', 'MC.name as class_name', 'OS.user_id', 'OS.relative_id', 'OS.student_id')
					->selectRaw('CONCAT(U.first_name," ", U.last_name) AS teacher_fullname')
					->selectRaw('CONCAT(S.first_name," ", S.last_name) AS student_fullname')
					->selectRaw('CONCAT(R.first_name," ", R.last_name) AS relative_fullname')
					->where('OS.id', '=', $offschool_id)
					->whereRaw('(SC.start_at <= OS.from_date AND  (SC.stop_at >= OS.to_date OR SC.stop_at IS NULL) )')
					->first();
				// ->whereRaw ( 'OS.id = ' . $offschool_id )
				// ->whereRaw ( 'SC.stop_at >= OS.to_date and SC.start_at < OS.from_date' )

			} elseif ($user->user_type == USER_TYPE_TEACHER) {

				$ps_off_school = $this->db->table(TBL_PS_OFF_SCHOOL . ' as OS')
					->join(CONST_TBL_USER . ' as U', 'OS.user_id', '=', 'U.id')
					->leftjoin(CONST_TBL_RELATIVE . ' as R', 'OS.relative_id', '=', 'R.id')
					->join(CONST_TBL_STUDENT . ' as S', 'OS.student_id', '=', 'S.id')
					->join(CONST_TBL_STUDENTCLASS . ' as SC', 'SC.student_id', '=', 'S.id')
					->join(CONST_TBL_MYCLASS . ' as MC', 'SC.myclass_id', '=', 'MC.id')
					->select('OS.id', 'OS.from_date', 'OS.description', 'OS.is_activated', 'OS.to_date', 'OS.date_at', 'R.avatar', 'MC.name as class_name', 'U.id as user_relative_id', 'OS.relative_id', 'OS.student_id')
					->selectRaw('CONCAT(U.first_name," ", U.last_name) AS teacher_fullname')
					->selectRaw('CONCAT(R.first_name," ", R.last_name) AS relative_fullname')
					->selectRaw('CONCAT(S.first_name," ", S.last_name) AS student_fullname')
					->where('OS.id', $offschool_id)
					->where('OS.user_id', $user->id)
					->whereRaw('(SC.start_at <= OS.from_date AND  (SC.stop_at >= OS.to_date OR SC.stop_at IS NULL) )')
					->first();
			}

			if (!$ps_off_school) {

				$return_data['_msg_code'] = MSG_CODE_TRUE;
				$return_data['message'] 	= $return_data['_msg_text'] = $psI18n->__('No data');

				return $response->withJson($return_data);
			} else {

				$ps_student = StudentModel::getStudentForRelative($ps_off_school->student_id, $ps_off_school->relative_id);

				$data_info = array();
				$data_info['id'] = (int) $ps_off_school->id;
				$data_info['is_confirm'] = (int) $ps_off_school->is_activated;

				$data_info['description'] = (string) $ps_off_school->description . PsString::newLine() . 'Đơn được gửi bởi: ' . $ps_off_school->relative_fullname;

				$data_info['date_at'] = date('H:i d-m-Y', strtotime($ps_off_school->date_at));
				$data_info['from_date'] = date('d-m-Y', strtotime($ps_off_school->from_date));
				$data_info['to_date'] = date('d-m-Y', strtotime($ps_off_school->to_date));
				$data_info['student_name'] = (string) $ps_off_school->student_fullname;
				$data_info['student_id'] = (int) $ps_off_school->student_id;
				$data_info['class_name'] = (string) $ps_off_school->class_name;
				$data_info['student_avatar'] = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar($ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR_STUDENT;

				$data_info['relative_fullname'] = (string) $ps_off_school->relative_fullname;

				if ($user->user_type == USER_TYPE_RELATIVE) {

					// anh nguoi nhan
					$user_avatar = UserModel::getUserAvatarByUserId($ps_off_school->user_id, USER_TYPE_TEACHER);

					$data_info['avatar_teacher'] = ($user_avatar->avatar != '') ? PsString::getUrlMediaAvatar($user_avatar->cache_data, $user_avatar->year_data, $user_avatar->avatar, MEDIA_TYPE_TEACHER) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

					$data_info['teacher_fullname'] = $ps_off_school->teacher_fullname;
				} elseif ($user->user_type == USER_TYPE_TEACHER) {

					// anh nguoi gui
					$user_avatar = UserModel::getUserAvatarByUserId($ps_off_school->user_relative_id, USER_TYPE_RELATIVE);

					$data_info['avatar_relative'] = ($user_avatar->avatar != '') ? PsString::getUrlMediaAvatar($user_avatar->cache_data, $user_avatar->year_data, $user_avatar->avatar, MEDIA_TYPE_RELATIVE) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
					$data_info['teacher_fullname'] = $ps_off_school->teacher_fullname;
				}

				$data = array();

				array_push($data, $data_info);

				$return_data['_data']['data_info'] = $data;
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['_msg_text'] 	= $return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// Lay danh sach nguoi gui ( app cho phu huynh )
	public function listUserSendForRelative(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$return_data = array();
		$return_data['_msg_code'] = MSG_CODE_TRUE;
		$return_data['_data'] = array();

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$device_id = $request->getHeaderLine('deviceid');

		$student_id = $args['student_id'];
		try {

			if (!PsAuthentication::checkDevice($user, $device_id)) {

				return $response->withJson($return_data);
			}

			if ($user->user_type == USER_TYPE_RELATIVE) {

				$amount_info = PsMobileAppAmountsModel::checkAmountInfo($user->id);

				if (!$amount_info) {

					$return_data = array(
						'_msg_code' => MSG_CODE_PAYMENT,
						'message' => $psI18n->__('Your account has run out of money. Please recharge to continue using.')
					);

					return $response->withJson($return_data);
				}

				$ps_student = StudentModel::getStudentForRelative($student_id, $user->member_id);

				if ($ps_student) {

					$list_service = array();

					// lay danh sach dich vu ma hoc sinh dang ky
					$services = $this->db->table(TBL_PS_SERVICE_COURSES . ' as SC')
						->join(CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'SC.ps_service_id')
						->join(CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.ps_service_course_id', '=', 'SC.id')
						->selectRaw('S.id')
						->where('SS.student_id', $student_id)
						->whereNull('SS.delete_at')
						->whereDate('SC.start_at', '<=', date('Y-m-d'))
						->whereDate('SC.end_at', '>=', date('Y-m-d'))
						->where('S.ps_customer_id', $ps_student->ps_customer_id)
						->where('SC.is_activated', STATUS_ACTIVE)
						->where('S.enable_roll', ENABLE_ROLL_SCHEDULE)
						->where('S.is_activated', STATUS_ACTIVE)
						->distinct()
						->get();

					foreach ($services as $service) {
						array_push($list_service, $service->id);
					}

					// Danh sach giao vien nhan
					$list_user = $this->db->table(CONST_TBL_USER . ' as U')
						->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'U.ps_customer_id')
						->leftjoin(CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'U.member_id')
						->leftJoin(TBL_PS_TEACHER_CLASS . ' as TC', function ($q) {
							$q->on('TC.ps_member_id', '=', 'M.id')
								->where('TC.is_activated', STATUS_ACTIVE)
								->whereDate('TC.start_at', '<', date('Y-m-d'))
								->whereDate('TC.stop_at', '>', date('Y-m-d'));
						})
						->leftjoin(TBL_PS_SERVICE_COURSES . ' as SC', 'SC.ps_member_id', '=', 'M.id')
						->select('U.id as user_id', 'U.user_type', 'M.avatar as avatar', 'M.year_data', 'C.cache_data')
						->selectRaw('CONCAT(U.first_name," ", U.last_name) AS fullname')
						->where('U.ps_customer_id', $user->ps_customer_id)
						->where(function ($query) use ($ps_student, $list_service) {
							$query->where('TC.ps_myclass_id', $ps_student->class_id)
								->orwhereIn('SC.ps_service_id', $list_service);
						})
						->where('U.user_type', USER_TYPE_TEACHER)
						->where('U.is_active', STATUS_ACTIVE)
						->distinct()
						->get();

					foreach ($list_user as $_user) {

						$list_member = array();

						$list_member['user_id'] = $_user->user_id;
						$list_member['full_name'] = $_user->fullname;
						$list_member['avatar_url'] = ($_user->avatar != '') ? PsString::getUrlMediaAvatar($_user->cache_data, $_user->year_data, $_user->avatar, MEDIA_TYPE_TEACHER) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
						array_push($return_data['_data'], $list_member);
					}

					$return_data['_msg_code'] = MSG_CODE_TRUE;
				}
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// Hiển thị danh sách xin nghỉ -- app phu huynh
	public function listOffSchool(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$psI18n = new PsI18n($this->getUserLanguage($user));

		$return_data = array();
		$return_data['_msg_code'] = MSG_CODE_FALSE;
		$return_data['_msg_text'] = $psI18n->__('You do not have access to this data');
		$return_data['_data'] = array();

		$device_id = $request->getHeaderLine('deviceid');

		if (!PsAuthentication::checkDevice($user, $device_id)) {
			return $response->withJson($return_data);
		}

		$student_id = isset($args['student_id']) ? (int) $args['student_id'] : null;

		if ($student_id <= 0)
			return $response->withJson($return_data);

		try {

			if ($user->user_type == USER_TYPE_RELATIVE) {

				$amount_info = PsMobileAppAmountsModel::checkAmountInfo($user->id);

				if (!$amount_info) {

					$return_data = array(
						'_msg_code' => MSG_CODE_PAYMENT,
						'message' => $psI18n->__('Your account has run out of money. Please recharge to continue using.'),
						'_msg_text' => $psI18n->__('Your account has run out of money. Please recharge to continue using.')
					);

					return $response->withJson($return_data);
				}

				$queryParams = $request->getQueryParams();

				$status_type = isset($queryParams['status']) ? $queryParams['status'] : '';

				if ($status_type == 'ok') {
					$status = STATUS_ACTIVE;
					$title = 'Off shool approved';
				} elseif ($status_type == 'cd') {
					$status = STATUS_NOT_ACTIVE;
					$title = 'Off shool unapproved';
				} else {

					$return_data = array(
						'_msg_code' => MSG_CODE_FALSE,
						'_msg_text' => $psI18n->__('No data'),
						'_data' => []
					);

					return $response->withJson($return_data);
				}

				// kiem tra nguoi dung co phai phu huynh hoc sinh
				$ps_student = StudentModel::getStudentForRelative($student_id, $user->member_id);

				if (!$ps_student) {
					return $response->withJson($return_data);
				}

				$page = isset($args['page']) ? (int) $args['page'] : 1;
				if ($page < 1)
					$page = 1;

				$ps_offschools = $this->db->table(TBL_PS_OFF_SCHOOL . ' as OS')
					->join(CONST_TBL_USER . ' as U', 'OS.user_id', '=', 'U.id')
					->join(CONST_TBL_PS_MEMBER . ' as M', 'U.member_id', '=', 'M.id')
					->join(CONST_TBL_RELATIVE . ' as R', 'OS.relative_id', '=', 'R.id')
					->join(CONST_TBL_STUDENT . ' as S', 'OS.student_id', '=', 'S.id')
					->join(CONST_TBL_STUDENTCLASS . ' as SC', 'SC.student_id', '=', 'S.id')
					->join(CONST_TBL_MYCLASS . ' as MC', 'SC.myclass_id', '=', 'MC.id')
					->select('OS.id', 'OS.from_date', 'OS.description', 'OS.is_activated', 'OS.to_date', 'OS.date_at', 'MC.name as class_name', 'OS.user_id')
					->selectRaw('CONCAT(U.first_name," ", U.last_name) AS teacher_fullname')
					->selectRaw('CONCAT(S.first_name," ", S.last_name) AS student_fullname')
					->selectRaw('CONCAT(R.first_name," ", R.last_name) AS relative_fullname')
					->where('OS.is_activated', $status)
					->where('OS.student_id', $student_id)
					->whereRaw('(SC.start_at <= OS.from_date AND  (SC.stop_at >= OS.to_date OR SC.stop_at IS NULL) )');

				$ps_offschool_count = $ps_offschools->get()
					->count();

				$limit = PS_CONST_LIMIT_ITEM;

				if ($ps_offschool_count % $limit == 0) {
					$ps_offschool_number_pages = $ps_offschool_count / $limit;
				} else {
					$ps_offschool_number_pages = (int) ($ps_offschool_count / $limit) + 1;
				}

				if ($page > $ps_offschool_number_pages) {
					$page = 1;
				}

				$next_page = ($page + 1);

				$pre_page = ($page - 1);

				if (($ps_offschool_number_pages == 1) || ($page == 1)) {
					$pre_page = 0;
				}

				if (($ps_offschool_number_pages == 1) || ($page == $ps_offschool_number_pages)) {
					$next_page = 0;
				}

				$ps_offschools = $ps_offschools->forPage($page, $limit)
					->orderBy('OS.date_at', 'desc')
					->groupby('OS.id')
					->get();

				$data_info = $data = array();

				foreach ($ps_offschools as $ps_off_school) {

					$data_info = array();

					$data_info['id'] = (int) $ps_off_school->id;
					$data_info['user_id'] = (int) $ps_off_school->user_id;
					$data_info['is_confirm'] = (int) $ps_off_school->is_activated;
					$data_info['description'] = (string) PsString::stringTruncate($ps_off_school->description, 40);
					$data_info['date_at'] = date('H:i d-m-Y', strtotime($ps_off_school->date_at));
					$data_info['from_date'] = date('d-m-Y', strtotime($ps_off_school->from_date));
					$data_info['to_date'] = date('d-m-Y', strtotime($ps_off_school->to_date));
					$data_info['student_name'] = (string) $ps_off_school->student_fullname;
					$data_info['relative_fullname'] = (string) $ps_off_school->relative_fullname;
					$data_info['class_name'] = (string) $ps_off_school->class_name;

					$user_avatar = UserModel::getUserAvatarByUserId($ps_off_school->user_id, USER_TYPE_TEACHER);

					$data_info['avatar_teacher'] = ($user_avatar->avatar != '') ? PsString::getUrlMediaAvatar($user_avatar->cache_data, $user_avatar->year_data, $user_avatar->avatar, MEDIA_TYPE_TEACHER) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

					$data_info['teacher_fullname'] = $ps_off_school->teacher_fullname;

					array_push($data, $data_info);
				}

				$return_data['_data']['next_page'] = $next_page;

				$return_data['_data']['pre_page'] = $pre_page;

				$return_data['_data']['title'] = $title;

				$return_data['_data']['data_info'] = $data;

				$return_data['_msg_code'] = MSG_CODE_TRUE;

				$return_data['_msg_text'] = ($ps_offschool_count <= 0) ? $psI18n->__('No data') : 'OK';
			} else {

				$return_data['_msg_code'] = MSG_CODE_500;

				$return_data['message'] = $psI18n->__('You do not have access to this data');

				$return_data['_msg_text'] = $psI18n->__('You do not have access to this data');

				return $response->withJson($return_data);
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;
		}

		return $response->withJson($return_data);
	}

	// Hiển thị danh sách xin nghỉ -- app giao vien
	public function listOffSchoolForTeacher(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$psI18n = new PsI18n($this->getUserLanguage($user));

		$return_data = array();

		$return_data['_msg_code'] = MSG_CODE_FALSE;

		$return_data['_msg_text'] = $psI18n->__('You do not have access to this data');

		$return_data['_data'] = array();

		$ps_offSchool = OffSchoolModel::find(50);

		$this->pushNotificationNewOffSchool($psI18n, $user, $ps_offSchool);

		//$this->pushNotificationOffSchoolToRelative($psI18n, $user, $ps_offSchool);

		try {

			if ($user->user_type == USER_TYPE_RELATIVE) {
				return $response->withJson($return_data);
			} elseif ($user->user_type == USER_TYPE_TEACHER) {

				$page = isset($args['page']) ? (int) $args['page'] : 1;
				if ($page < 1)
					$page = 1;

				$queryParams = $request->getQueryParams();

				$status_type = isset($queryParams['status']) ? $queryParams['status'] : 'cd';

				if ($status_type == 'ok') {
					$status = STATUS_ACTIVE;
					$title = 'Off shool approved';
				} elseif ($status_type == 'cd') {
					$status = STATUS_NOT_ACTIVE;
					$title = 'Off shool unapproved';
				} else {

					$return_data = array(
						'_msg_code' => MSG_CODE_FALSE,
						'_msg_text' => $psI18n->__('No data'),
						'_data' => []
					);

					return $response->withJson($return_data);
				}

				$ps_off_school = $this->db->table(TBL_PS_OFF_SCHOOL . ' as OS')
					->join(CONST_TBL_USER . ' as U', 'OS.user_created_id', '=', 'U.id')
					->leftjoin(CONST_TBL_RELATIVE . ' as R', 'OS.relative_id', '=', 'R.id')
					->join(CONST_TBL_STUDENT . ' as S', 'OS.student_id', '=', 'S.id')
					->join(CONST_TBL_STUDENTCLASS . ' as SC', 'SC.student_id', '=', 'S.id')
					->join(CONST_TBL_MYCLASS . ' as MC', 'SC.myclass_id', '=', 'MC.id')
					->select('OS.id', 'OS.from_date', 'OS.description', 'OS.is_activated', 'OS.to_date', 'OS.date_at', 'MC.name as class_name', 'U.id as user_relative_id')
					->selectRaw('CONCAT(U.first_name," ", U.last_name) AS relative_fullname')
					->selectRaw('CONCAT(S.first_name," ", S.last_name) AS student_fullname')
					->where('OS.user_id', $user->id)
					->where('OS.is_activated', $status)
					//->whereRaw('(SC.stop_at >= OS.to_date and SC.start_at < OS.from_date)')
					->whereRaw('(SC.start_at <= OS.from_date AND  (SC.stop_at >= OS.to_date OR SC.stop_at IS NULL) )');

				$ps_offschool_count = $ps_off_school->get()
					->count();

				$limit = PS_CONST_LIMIT_ITEM;

				if ($ps_offschool_count % $limit == 0) {
					$ps_offschool_number_pages = $ps_offschool_count / $limit;
				} else {
					$ps_offschool_number_pages = (int) ($ps_offschool_count / $limit) + 1;
				}

				if ($page > $ps_offschool_number_pages) {
					$page = 1;
				}

				$next_page = ($page + 1);

				$pre_page = ($page - 1);

				if (($ps_offschool_number_pages == 1) || ($page == 1)) {
					$pre_page = 0;
				}

				if (($ps_offschool_number_pages == 1) || ($page == $ps_offschool_number_pages)) {
					$next_page = 0;
				}

				$ps_offschools = $ps_off_school->forPage($page, $limit)
					->orderBy('OS.date_at', 'desc')
					->groupby('OS.id')
					->get();

				$data_info = $data = array();

				foreach ($ps_offschools as $ps_off_school) {

					$data_info['id'] = (int) $ps_off_school->id; 
					$data_info['is_confirm'] = (int) $ps_off_school->is_activated;
					$data_info['description'] = (string) $ps_off_school->description;
					$data_info['date_at'] = date('H:i d-m-Y', strtotime($ps_off_school->date_at));
					$data_info['from_date'] = date('d-m-Y', strtotime($ps_off_school->from_date));
					$data_info['to_date'] = date('d-m-Y', strtotime($ps_off_school->to_date));
					$data_info['student_name'] = (string) $ps_off_school->student_fullname;
					$data_info['relative_fullname'] = (string) $ps_off_school->relative_fullname;
					$data_info['class_name'] = (string) $ps_off_school->class_name;

					$user_avatar = UserModel::getUserAvatarByUserId($ps_off_school->user_relative_id, USER_TYPE_RELATIVE);

					$data_info['avatar_relative'] = ($user_avatar->avatar != '') ? PsString::getUrlMediaAvatar($user_avatar->cache_data, $user_avatar->year_data, $user_avatar->avatar, MEDIA_TYPE_RELATIVE) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

					array_push($data, $data_info);
				}

				$return_data['_data']['next_page'] = $next_page;

				$return_data['_data']['pre_page'] = $pre_page;

				// $return_data ['_data'] ['title'] = $psI18n->__('Off shool');

				$return_data['_data']['title'] = $psI18n->__($title);
				$return_data['_data']['class_name'] = $ps_off_school->class_name;
               
				$return_data['_data']['data_info'] = $data;

				$return_data['_msg_code'] = MSG_CODE_TRUE;
				$return_data['_msg_text'] = ($ps_offschool_count <= 0) ? $psI18n->__('No data') : 'OK';
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// chỉnh sủa yêu cầu xin nghi học
	public function editOffSchool(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array();

		$return_data['_msg_code'] = MSG_CODE_FALSE;
		$return_data['message'] = $return_data['_msg_text'] = $psI18n->__('Confirmed, can not edit');

		$device_id = $request->getHeaderLine('deviceid');

		if ($user->user_type == USER_TYPE_RELATIVE) {

			if (!PsAuthentication::checkDeviceUserRelative($user, $device_id)) {

				return $response->withJson($return_data);
			}

			// Kiem tra tai khoan
			$amount_info = PsMobileAppAmountsModel::checkAmountInfo($user->id);

			if (!$amount_info) {

				$return_data = array(
					'_msg_code' => MSG_CODE_PAYMENT,
					'_msg_text' => $psI18n->__('Your account has run out of money. Please recharge to continue using.'),
					'message' => $psI18n->__('Your account has run out of money. Please recharge to continue using.')
				);

				return $response->withJson($return_data);
			}
		}

		$offschool_id = $args['offschool_id'];

		if (!$offschool_id)
			return $response->withJson($return_data);

		$body = $request->getParsedBody();

		$info = isset($body['info']) ? $body['info'] : '';

		if (count($info) <= 0) {
			return $response->withJson($return_data);
		} else {

			$offschool = OffSchoolModel::find($offschool_id);

			if (count($offschool) <= 0)
				return $response->withJson($return_data);

			if ($offschool->is_activated == STATUS_ACTIVE) {

				$return_data['message'] = $return_data['_msg_text'] = $psI18n->__('Confirmed, can not edit');

				return $response->withJson($return_data);
			}

			/*

			if ($user->member_id != $offschool->relative_id) {

				$return_data ['_msg_text'] = $psI18n->__ ( 'You do not have permission to edit' );

				return $response->withJson ( $return_data );
			}*/

			$description = isset($info['description']) ? $info['description'] : null;

			$user_id = isset($info['user_id']) ? $info['user_id'] : null;

			$from_date = isset($info['from_date']) ? $info['from_date'] : null;

			$to_date = isset($info['to_date']) ? $info['to_date'] : null;

			if (!$description || !$from_date || !$user_id || !$to_date) {

				return $response->withJson($return_data);
			} else {
				$from_date = date('Y-m-d', strtotime($from_date));
				$to_date = date('Y-m-d', strtotime($to_date));
				$chk_from_date = (int) vali::notEmpty()->dateTime('Y-m-d')
					->validate($from_date);

				$chk_to_date = (int) vali::notEmpty()->dateTime('Y-m-d')
					->validate($to_date);

				$chk_des = vali::notEmpty()->stringType()
					->length(1, 255)
					->validate($description);

				$boolean_validator = ($chk_from_date && $chk_to_date && $chk_des);
			}
		}

		$ps_work_place = $this->db->table(TBL_PS_WORK_PLACES)
			->select('config_time_receive_valid AS config_time_receive_valid')
			->where('id', $offschool->ps_workplace_id)
			->get()
			->first();

		if ($ps_work_place->config_time_receive_valid != '') {
			$hour = date('H', strtotime($ps_work_place->config_time_receive_valid));
			$minutes = date('i', strtotime($ps_work_place->config_time_receive_valid));
		} else {
			$hour = 24;
			$minutes = 0;
		}

		$firstDay = date('Y-m-d H:i', strtotime('-1 day', strtotime($from_date)));

		// Lấy ra thời gian cho phép xin nghỉ học
		$timeConfig = date('Y-m-d H:i', strtotime('+' . $hour . ' hour +' . $minutes . ' minutes', strtotime($firstDay)));

		// Thời gian gửi
		$date_at = date('Y-m-d H:i', strtotime($offschool->date_at));

		// Kiểm tra ngày xin nghỉ có hợp lệ
		$chk_date = $this->db->table(TBL_PS_OFF_SCHOOL)
			->select('id')
			->whereDate('from_date', '<=', $from_date)
			->whereDate('to_date', '>= ', $from_date)
			->where('student_id', $offschool->student_id)
			->where('id', '!=', $offschool_id)
			->get()
			->count();

		// return $response->withJson(strtotime($date_at) > strtotime($timeConfig));
		if ($chk_date > 0) {

			$return_data['_msg_text'] = $psI18n->__('The time of leave is the same as another application.');

			return $response->withJson($return_data);
		}

		if (strtotime($from_date) > strtotime($to_date)) {

			$return_data['message'] = $return_data['_msg_text'] = $psI18n->__('Ngày bắt đầu phải nhỏ hơn ngày kết thúc.');

			return $response->withJson($return_data);
		}

		if (strtotime($date_at) > strtotime($timeConfig)) {

			// Ngày bắt đầu xin nghỉ phải trước $timeConfig
			$return_data['message'] = $return_data['_msg_text'] = $psI18n->__('The start time is invalid');

			return $response->withJson($return_data);
		}

		// Kiem tra du lieu push len truoc khi lam tiep
		if ($boolean_validator) {

			try {

				$offschool->description = $description;
				$offschool->user_id = $user_id;
				$offschool->from_date = $from_date;
				$offschool->to_date = $to_date;

				if ($offschool->save()) {

					$return_data['_msg_code'] = MSG_CODE_TRUE;
					$return_data['message'] = $return_data['_msg_text'] = $psI18n->__('Edit successfully');
				}
			} catch (Exception $e) {

				$this->logger->err($e->getMessage());

				$return_data['_msg_code'] = MSG_CODE_500;

				$return_data['message'] = $return_data['_msg_text'] = $psI18n->__('Send off school error');
			}
		}

		return $response->withJson($return_data);
	}

	// Người thân xóa yêu cầu xin nghi học
	public function deleteOffSchool(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array();

		$return_data['_msg_code'] = MSG_CODE_FALSE;

		$return_data['_msg_text'] = $psI18n->__('Delete a Off shool failure.');

		if ($user->user_type != USER_TYPE_RELATIVE) {
			$return_data['_msg_text'] = $psI18n->__('You do not have access to this data');
			return $response->withJson($return_data);
		}

		$device_id = $request->getHeaderLine('deviceid');

		if (!PsAuthentication::checkDeviceUserRelative($user, $device_id)) {

			return $response->withJson($return_data);
		}

		// Kiem tra tai khoan
		$amount_info = PsMobileAppAmountsModel::checkAmountInfo($user->id);

		if (!$amount_info) {

			$return_data['_msg_code'] = MSG_CODE_PAYMENT;
			$return_data['_msg_text'] = $psI18n->__('Your account has run out of money. Please recharge to continue using.');
			$return_data['message'] = $psI18n->__('Your account has run out of money. Please recharge to continue using.');

			return $response->withJson($return_data);
		}

		$offschool_id = $args['offschool_id'];

		if (!$offschool_id)
			return $response->withJson($return_data);

		$offschool = OffSchoolModel::find($offschool_id);

		if (count($offschool) <= 0)
			return $response->withJson($return_data);

		if ($offschool->is_activated == STATUS_ACTIVE) {

			$return_data['_msg_text'] = $psI18n->__('Confirmed, can not edit');

			return $response->withJson($return_data);
		}

		if ($user->member_id != $offschool->relative_id) {

			$return_data['_msg_text'] = $psI18n->__('You do not have permission to edit');

			return $response->withJson($return_data);
		}

		/*
		$currentTime = date ( 'Y-m-d' );

		if (strtotime ( $currentTime ) >= strtotime ( $offschool->from_date )) {
			$return_data ['_msg_text'] = $psI18n->__ ( 'Exceeds the allowed time' );
			return $response->withJson ( $return_data );
		}
		*/

		try {

			if ($offschool->delete()) {
				$return_data['_msg_code'] = MSG_CODE_TRUE;
				$return_data['_msg_text'] = $psI18n->__('Delete a Off shool successful.');
			} else {
				$return_data['_msg_code'] = MSG_CODE_FALSE;
				$return_data['_msg_text'] = $psI18n->__('Delete a Off shool failure.');
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['message'] = $psI18n->__('Delete a Off shool failure.');
		}

		return $response->withJson($return_data);
	}

	// Xác nhận yêu cầu xin nghỉ của phụ huynh -- app giáo viên
	public function confirmOffSchool(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$psI18n = new PsI18n($this->getUserLanguage($user));

		$return_data = array();

		$return_data['_msg_code']  = MSG_CODE_FALSE;
		$return_data['message'] 	= $return_data['_msg_text'] = $psI18n->__('Confirm failure.');

		$offschool_id = $args['offschool_id'];

		if (!$offschool_id)
			return $response->withJson($return_data);

		// Lay ra don xin nghi danh cho GV nay
		// $offSchool = OffSchoolModel::find($offschool_id);
		$offSchool = OffSchoolModel::getDetailOfMember($offschool_id, $user->id);

		if (!$offSchool)
			return $response->withJson($return_data);

		try {
			/*
			 * if ($offSchool->user_id == $user->id) {
			 * $offSchool->is_activated = 1;
			 * $offSchool->updated_at = date('Y-m-d H:i:s');
			 * $offSchool->save();
			 * $return_data['_msg_code'] = MSG_CODE_TRUE;
			 * } else {
			 * return $response->withJson($return_data);
			 * }
			 */

			$offSchool->is_activated = STATUS_ACTIVE;

			$offSchool->updated_at = date('Y-m-d H:i:s');

			$offSchool->user_updated_id = $user->id;

			if ($offSchool->save()) {
				$return_data['_msg_code'] = MSG_CODE_TRUE;
				$return_data['message'] = $return_data['_msg_text'] = $psI18n->__('Confirm successfully.');

				// Báo đơn xin nghỉ đã được xác nhận
				$this->pushNotificationOffSchoolToRelative($psI18n, $user, $offSchool);
			}
		} catch (Exception $e) {
			$this->logger->err($e->getMessage());
			$return_data['_msg_code'] = MSG_CODE_500;
			$return_data['message'] = $return_data['_msg_text'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}
		return $response->withJson($return_data);
	}

	/**
	 * Ham push notification den giao vien khi co don xin nghi
	 *
	 * @author thangnc
	 *        
	 * @param $psI18n - mixed
	 * @param $user - mixed, User gửi (Nguoi than gui)
	 * @param $ps_offSchool - mixed, Dan do
	 * @return void
	 **/
	protected function pushNotificationNewOffSchool($psI18n, $user, $ps_offSchool)
	{

		if ($ps_offSchool->id <= 0)

			return false;

		// Lay thong tin hoc sinh tu Dan do: Hoc sinh, Lop hien tai
		$ps_student = StudentModel::getStudentInfoShortForTeacher($ps_offSchool->student_id, $user->ps_customer_id, $ps_offSchool->relative_id);

		// Lay giao vien nhan
		$user_teacher = UserModel::getUserByArrayUserId(array($ps_offSchool->user_id), false)->first();

		if ($ps_student && $user_teacher) {

			if ($ps_student->avatar != '') {

				$avatar_url = PsString::getUrlMediaAvatar($ps_student->cache_data, $ps_student->year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT);

				if (!PsFile::urlExists($avatar_url)) {
					if ($ps_student->sex == STATUS_ACTIVE) {
						$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE . 'boy_avatar_default.png';
					} else {
						$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE . 'girl_avatar_default.png';
					}
				}
			} else {
				if ($ps_student->sex == STATUS_ACTIVE) {
					$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE . 'boy_avatar_default.png';
				} else {
					$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE . 'girl_avatar_default.png';
				}
			}

			$psI18n = new PsI18n($this->getUserLanguage($user));

			// Lay thong tin giao vien nhan    	    
			$setting_notification = new \stdClass();

			$setting_notification->title = $psI18n->__("There are student leave applications") . ' ' . $ps_student->first_name . ' ' . $ps_student->last_name;

			$setting_notification->subTitle     = $psI18n->__('From') . ' ' . $psI18n->__('Day') . ': ' . $ps_offSchool->from_date . '->' . $ps_offSchool->to_date;
			$setting_notification->tickerText   = $setting_notification->subTitle;

			$setting_notification->message      = $ps_offSchool->description;
			$setting_notification->lights       = '1';
			$setting_notification->vibrate      = '1';
			$setting_notification->sound        = '1';
			$setting_notification->smallIcon    = IC_SMALL_NOTIFICATION;
			$setting_notification->smallIconOld = 'ic_small_notification_old';

			$setting_notification->largeIcon    = $avatar_url;

			$setting_notification->screenCode   = PS_CONST_SCREEN_OFFSCHOOL_DETAIL;
			$setting_notification->itemId       = $ps_offSchool->id;
			$setting_notification->studentId    = $ps_offSchool->student_id;
			$setting_notification->clickUrl     = '';
			$setting_notification->registrationIds = $user_teacher->notification_token;

			$notification = new PsNotification($setting_notification);

			return $notification->pushNotification($user_teacher->osname);
		}
	}

	/**
	 **/
	protected function pushNotificationOffSchoolToRelative($psI18n, $user, $ps_offSchool)
	{

		if ($ps_offSchool->id <= 0)
			return false;

		// Lay thong tin hoc sinh tu Dan do: Hoc sinh, Lop hien tai
		$ps_student = StudentModel::getStudentForRelative($ps_offSchool->student_id, $ps_offSchool->relative_id);

		// Lay phu huynh nhan
		$user_relative = UserModel::getUserByArrayUserId(array($ps_offSchool->user_created_id), false)->first();

		if ($ps_student && $user_relative) {

			if ($ps_student->avatar != '') {

				$avatar_url = PsString::getUrlMediaAvatar($ps_student->cache_data, $ps_student->year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT);

				if (!PsFile::urlExists($avatar_url)) {
					if ($ps_student->sex == STATUS_ACTIVE) {
						$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE . 'boy_avatar_default.png';
					} else {
						$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE . 'girl_avatar_default.png';
					}
				}
			} else {
				if ($ps_student->sex == STATUS_ACTIVE) {
					$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE . 'boy_avatar_default.png';
				} else {
					$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE . 'girl_avatar_default.png';
				}
			}

			$psI18n = new PsI18n($this->getUserLanguage($user));

			// Lay thong tin giao vien nhan
			$setting_notification = new \stdClass();

			if ($ps_offSchool->is_activated == STATUS_ACTIVE)
				$setting_notification->title 	= $psI18n->__("Teachers confirmed her resignation.") . ' ' . $user->first_name . ' ' . $user->last_name;
			else
				$setting_notification->title 	= $psI18n->__("Off shool") . ' ' . $psI18n->__('of student') . $user->first_name . ' ' . $user->last_name . ' ' . $psI18n->__('not yet confirmed');

			$setting_notification->subTitle     = $psI18n->__("Off shool") . ' ' . $psI18n->__('From') . ' ' . $psI18n->__('Day') . ': ' . $ps_offSchool->from_date . '->' . $ps_offSchool->to_date;
			$setting_notification->tickerText   = $setting_notification->subTitle;
			$setting_notification->message      = ($ps_offSchool->reason_illegal != '') ? $ps_offSchool->reason_illegal : '';
			$setting_notification->lights       = '1';
			$setting_notification->vibrate      = '1';
			$setting_notification->sound        = '1';
			$setting_notification->smallIcon    = IC_SMALL_NOTIFICATION;
			$setting_notification->smallIconOld = 'ic_small_notification_old';

			$setting_notification->largeIcon    = $avatar_url;

			$setting_notification->screenCode   = PS_CONST_SCREEN_OFFSCHOOL_DETAIL;
			$setting_notification->itemId       = $ps_offSchool->id;
			$setting_notification->studentId    = $ps_offSchool->student_id;
			$setting_notification->clickUrl     = '';
			$setting_notification->registrationIds = $user_relative->notification_token;

			$notification = new PsNotification($setting_notification);

			return $notification->pushNotification($user_relative->osname);
		}
	}
}
