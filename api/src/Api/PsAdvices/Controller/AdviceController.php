<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/25/2018
 * Time: 3:12 PM
 */

namespace Api\PsAdvices\Controller;

use Api\PsAdvices\Model\AdviceModel;
use Api\PsMembers\Model\PsMemberModel;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Exception;
use Respect\Validation\Validator as vali;
use App\Controller\BaseController;
use App\Authentication\PsAuthentication;
use Api\Users\Model\UserModel;
use App\PsUtil\PsDateTime;
use App\PsUtil\PsFile;
use App\PsUtil\PsString;
use App\PsUtil\PsI18n;
use App\PsUtil\PsWebContent;
use App\PsUtil\PsNotification;
use Api\Students\Model\StudentModel;
use App\Model\PsMobileAppAmountsModel;

class AdviceController extends BaseController
{
	public $container;
	protected $user_token;
	public function __construct(LoggerInterface $logger, $container, $app)
	{
		parent::__construct($logger, $container);

		$this->user_token = $app->user_token;
	}

	// gui dan do cho giao vien
	public function sendAdvice(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_msg_text' => $psI18n->__('Send advice error')
		);

		$device_id = $request->getHeaderLine('deviceid');

		if (!PsAuthentication::checkDevice($user, $device_id)) {

			return $response->withJson($return_data);
		} elseif ($user->user_type == USER_TYPE_RELATIVE) {

			// Kiem tra tai khoan
			$amount_info = PsMobileAppAmountsModel::checkAmountInfo($user->id);

			if (!$amount_info) {

				$return_data = array(
					'_msg_code' => MSG_CODE_PAYMENT,
					'_msg_text' => $psI18n->__('Your account has run out of money. Please recharge to continue using.')
				);

				return $response->withJson($return_data);
			}
		}

		$body = $request->getParsedBody();

		$info = isset($body['info']) ? $body['info'] : '';

		$boolean_validator = false;

		if (count($info) <= 0) {
			return $response->withJson($return_data);
		} else {

			$title = isset($info['title']) ? $info['title'] : null;

			$content = isset($info['content']) ? $info['content'] : null;

			$user_id = isset($info['user_id']) ? $info['user_id'] : null; // id User giáo viên nhận

			$student_id = isset($info['student_id']) ? $info['student_id'] : null;

			$category_id = isset($info['category_id']) ? $info['category_id'] : null;


			if (!$title || !$content || !$user_id || !$student_id || !$category_id) {

				return $response->withJson($return_data);
			} else {

				$chk_title = vali::notEmpty()->stringType()->length(1, 150)->validate($title);
				$chk_content = vali::notEmpty()->stringType()->length(1, 255)->validate($content);

				$boolean_validator = ($chk_title && $chk_content);
			}
		}

		// Kiem tra du lieu push len truoc khi lam tiep
		if ($boolean_validator) {

			try {

				AdviceModel::beginTransaction();

				// them du lieu vao bang PsAdvices
				$curren_datetime = date('Y-m-d H:i:s');

				/*
				 * $newAdvice = $this->db->table ( TBL_PS_ADVICES )->insertGetId ( [
				 * 'title' => ( string ) $title,
				 * 'content' => ( string ) $content,
				 * 'date_at' => date ( 'Y-m-d H:i:s' ),
				 * 'user_id' => ( int ) $user_id, // user_id nhan
				 * 'category_id' => ( int ) $category_id,
				 * 'student_id' => ( int ) $student_id,
				 * 'relative_id' => ( int ) $user->id,
				 * 'user_created_id' => ( int ) $user->id,
				 * 'created_at' => $curren_datetime,
				 * 'updated_at' => $curren_datetime
				 * ] );
				 */

				$ps_new_advice = new AdviceModel();

				$ps_new_advice->title = (string) $title;
				$ps_new_advice->content = (string) $content;
				$ps_new_advice->date_at = $curren_datetime;
				$ps_new_advice->user_id = (int) $user_id; // GV nhận
				$ps_new_advice->category_id = (int) $category_id;
				$ps_new_advice->student_id = (int) $student_id;
				$ps_new_advice->relative_id = (int) $user->member_id;
				$ps_new_advice->user_created_id = (int) $user->id;
				$ps_new_advice->created_at = $curren_datetime;
				$ps_new_advice->updated_at = $curren_datetime;

				if ($ps_new_advice->save()) {

					$return_data['_msg_code'] = MSG_CODE_TRUE;
					$return_data['_msg_text'] = $psI18n->__('Send advice success');
					// BEGIN: Push Nocation - Báo giáo viên có dặn dò
					$this->pushNotificationAdviceNewToTeacher($psI18n, $user, $ps_new_advice);
				} else {
					$return_data['_msg_code'] = MSG_CODE_FALSE;
					$return_data['_msg_text'] = $psI18n->__('Send advice error');
				}

				AdviceModel::commit();
			} catch (Exception $e) {

				AdviceModel::rollBack();

				$this->logger->err($e->getMessage());

				$return_data['_msg_code'] = MSG_CODE_500;

				$return_data['message'] = $psI18n->__('Send advice error');
			}
		}
		return $response->withJson($return_data);
	}

	// Hiển thị danh sách dặn dò -- app phu huynh
	public function listAdvices(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_data' => []
		);

		$user = $this->user_token;

		$psI18n = new PsI18n($this->getUserLanguage($user));

		/*

		$ps_new_advice = AdviceModel::where ( 'id', 45 )->first ();

		if ($ps_new_advice)
			$this->pushNotificationAdviceNewToTeacher ( $psI18n, $user, $ps_new_advice );

		$return_data ['ps_new_advice'] = $ps_new_advice;
		*/

		$device_id = $request->getHeaderLine('deviceid');

		$page = isset($args['page']) ? (int) $args['page'] : 1;
		if ($page < 1)
			$page = 1;

		$student_id = isset($args['student_id']) ? (int) $args['student_id'] : null;

		if (!$student_id)
			return $response->withJson($return_data);

		$queryParams = $request->getQueryParams();

		$status_type = isset($queryParams['status']) ? $queryParams['status'] : '';

		$catid = isset($queryParams['catid']) ? $queryParams['catid'] : '';

		$title = $psI18n->__('Advice of baby');

		if ($status_type == 'ok') {
			$status = STATUS_ACTIVE;
			// $title = $psI18n->__ ('Advices approved');
		} elseif ($status_type == 'cd') {
			$status = STATUS_NOT_ACTIVE;
			// $title = $psI18n->__ ('Advices unapproved');
		} else {

			$return_data = array(
				'_msg_code' => MSG_CODE_FALSE,
				'_msg_text' => $psI18n->__('No data'),
				'_data' => []
			);

			return $response->withJson($return_data);
		}

		try {

			if ($user->user_type == USER_TYPE_RELATIVE) {

				if (!PsAuthentication::checkDeviceUserRelative($user, $device_id)) {
					return $response->withJson($return_data);
				}

				$amount_info = PsMobileAppAmountsModel::checkAmountInfo($user->id);

				if (!$amount_info) {

					$return_data = array(
						'_msg_code' => MSG_CODE_PAYMENT,
						'message' => $psI18n->__('Your account has run out of money. Please recharge to continue using.')
					);

					return $response->withJson($return_data);
				}

				$chk_relative_student = $this->db->table(CONST_TBL_USER . ' as U')->join(CONST_TBL_RELATIVE . ' as R', 'U.member_id', '=', 'R.id')->join(CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', 'R.id')->select('U.id')->where('U.id', $user->id)->where('RS.student_id', $student_id)->get();

				if (count($chk_relative_student) <= 0) {
					return $response->withJson($return_data);
				}

				$ps_advices = $this->db->table(TBL_PS_ADVICES . ' as A')
					->join(CONST_TBL_USER . ' as U', 'A.user_id', '=', 'U.id')
					->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'U.ps_customer_id')
					->leftjoin(CONST_TBL_PS_MEMBER . ' as M', 'U.member_id', '=', 'M.id')
					->join(CONST_TBL_RELATIVE . ' as R', 'A.relative_id', '=', 'R.id')
					->join(TBL_PS_ADVICE_CATEGORIES . ' as AC', 'A.category_id', '=', 'AC.id')
					->join(CONST_TBL_STUDENT . ' as S', 'A.student_id', '=', 'S.id')
					->select('A.id', 'A.title', 'A.content', 'A.is_activated', 'AC.title as category_name', 'A.date_at as date_at_send', 'M.avatar', 'M.year_data', 'C.cache_data')
					->selectRaw('CONCAT(U.first_name," ", U.last_name) AS user_fullname')
					->selectRaw('CONCAT(S.first_name," ", S.last_name) AS student_fullname')
					// ->where ( 'A.relative_id', $user->member_id )
					->where('A.is_activated', $status)
					->where('A.student_id', $student_id);

				if ($catid > 0)
					$ps_advices->where('A.category_id', $catid);
			} else {
				return $response->withJson($return_data);
			}

			$ps_advice_count = $ps_advices->get()->count();

			$limit = PS_CONST_LIMIT_ITEM;

			if ($ps_advice_count % $limit == 0) {
				$ps_advice_number_pages = $ps_advice_count / $limit;
			} else {
				$ps_advice_number_pages = (int) ($ps_advice_count / $limit) + 1;
			}

			if ($page > $ps_advice_number_pages) {
				$page = 1;
			}

			$next_page = ($page + 1);

			$pre_page = ($page - 1);

			if (($ps_advice_number_pages == 0) || ($ps_advice_number_pages == 1) || ($page == 1)) {
				$pre_page = 0;
			}

			if (($ps_advice_number_pages == 0) || ($ps_advice_number_pages == 1) || ($page == $ps_advice_number_pages)) {
				$next_page = 0;
			}

			$ps_advices = $ps_advices->forPage($page, $limit)->orderBy('A.date_at', 'desc')->get();

			$data = array();

			foreach ($ps_advices as $ps_advice) {

				$data_info = array();

				$data_info['advice_id'] = (int) $ps_advice->id;

				$data_info['title_name'] = 'Nhắn ' . $psI18n->__('Teacher');

				$data_info['user_fullname'] = (string) $ps_advice->user_fullname;

				$data_info['student_name'] = (string) $ps_advice->student_fullname;

				$data_info['advice_title'] = $ps_advice->title;

				$data_info['category_name'] = (string) $ps_advice->category_name;

				$data_info['content'] = (string) $ps_advice->content;
				// $data_info ['avatar_user'] = ($ps_advice->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_advice->cache_data, $ps_advice->year_data, $ps_advice->avatar, MEDIA_TYPE_TEACHER ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
				// $data_info ['advice_title'] = PsEndCode::ps64EndCode($ps_advice->title);

				$data_info['status_read'] = (int) $ps_advice->is_activated;

				$data_info['date_at'] = date('H:i d-m-Y', strtotime($ps_advice->date_at_send));

				array_push($data, $data_info);
			}

			// Lay danh mục dặn dò

			$return_data['_data']['next_page'] = $next_page;

			$return_data['_data']['pre_page'] = $pre_page;

			$return_data['_data']['title'] = $psI18n->__($title);

			$return_data['_data']['data_info'] = $data;

			$return_data['_msg_code'] = MSG_CODE_TRUE;
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;
			$return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.') . $e->getMessage();
		}

		// $this->WriteLog ( '--- END List notification---' );

		return $response->withJson($return_data);
	}

	// Hiển thị danh sách dặn dò -- app giao vien
	public function listAdvicesForTeacher(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$return_data = array(
			'_msg_code' => MSG_CODE_TRUE,
			'_data' => []
		);

		$user = $this->user_token;

		$psI18n = new PsI18n($this->getUserLanguage($user));

		//$device_id = $request->getHeaderLine ( 'deviceid' );

		$page = isset($args['page']) ? (int) $args['page'] : 1;
		if ($page < 1)
			$page = 1;

		$queryParams = $request->getQueryParams();

		$status_type = isset($queryParams['status']) ? $queryParams['status'] : '';
		$catid = isset($queryParams['catid']) ? $queryParams['catid'] : '';
		$class_id = isset($queryParams['class_id']) ? $queryParams['class_id'] : '';
        
		if ($status_type == 'ok') {
			$status = STATUS_ACTIVE;
			$title = 'Advices approved';
		} elseif ($status_type == 'cd') {
			$status = STATUS_NOT_ACTIVE;
			$title = 'Advices unapproved';
		} else {

			$return_data = array(
				'_msg_code' => MSG_CODE_TRUE,
				'_msg_text' => $psI18n->__('No data'),
				'_data' => []
			);

			return $response->withJson($return_data);
		}

		try {
			/*
			 * if (! PsAuthentication::checkDevice ( $user, $device_id )) {
			 * return $response->withJson ( $return_data );
			 * }
			**/ 
			if ($user->user_type == USER_TYPE_RELATIVE) {

				return $response->withJson($return_data);
			} elseif ($user->user_type == USER_TYPE_TEACHER) {
				$ps_member = PsMemberModel::getMember($user->member_id,null,$class_id);
                //return $ps_member;
				if (!$ps_member) {
					$return_data = array(
						'_msg_code' => MSG_CODE_TRUE,
						'_msg_text' => $psI18n->__('No data'),
						'_data' => []
					);

					return $response->withJson($return_data);
				}

				// Lay danh sách dặn dò của các học sinh trong lớp mà GV đang hoạt động
				$ps_advices = $this->db->table(TBL_PS_ADVICES . ' as A')
					->join(CONST_TBL_USER . ' as U', 'A.user_created_id', '=', 'U.id')
					->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'U.ps_customer_id')
					->join(CONST_TBL_RELATIVE . ' as R', 'U.member_id', '=', 'R.id')
					->join(TBL_PS_ADVICE_CATEGORIES . ' as AC', 'A.category_id', '=', 'AC.id')
					->join(CONST_TBL_STUDENT . ' as S', 'A.student_id', '=', 'S.id')
					->join(CONST_TBL_STUDENTCLASS . ' as SC', 'SC.student_id', '=', 'S.id')
					->select('A.id', 'A.title', 'A.content', 'A.is_activated', 'AC.title as category_name', 'A.date_at as date_at_send', 'R.avatar', 'R.year_data', 'C.cache_data')
					->selectRaw('CONCAT(U.first_name," ", U.last_name) AS user_fullname')
					->selectRaw('CONCAT(S.first_name," ", S.last_name) AS student_fullname')
					->where('SC.myclass_id', $ps_member->myclass_id)
					->where('A.is_activated', $status);

				// ->where ( 'A.user_id', $user->id );

				if ($catid > 0)
					$ps_advices->where('A.category_id', $catid);
			}

			$ps_advice_count = $ps_advices->get()->count();

			$limit = PS_CONST_LIMIT_ITEM;

			if ($ps_advice_count % $limit == 0) {
				$ps_advice_number_pages = $ps_advice_count / $limit;
			} else {
				$ps_advice_number_pages = (int) ($ps_advice_count / $limit) + 1;
			}

			if ($page > $ps_advice_number_pages) {
				$page = 1;
			}

			$next_page = ($page + 1);
			$pre_page = ($page - 1);

			if (($ps_advice_number_pages == 0) || ($ps_advice_number_pages == 1) || ($page == 1)) {
				$pre_page = 0;
			}

			if (($ps_advice_number_pages == 0) || ($ps_advice_number_pages == 1) || ($page == $ps_advice_number_pages)) {
				$next_page = 0;
			}

			$ps_advices = $ps_advices->forPage($page, $limit)->orderBy('A.date_at', 'desc')->distinct('A.id')->get();

			$data_info = $data = array();

			foreach ($ps_advices as $ps_advice) {

				$data_info = array();

				$data_info['advice_id'] = (int) $ps_advice->id;
				$data_info['title_name'] = $psI18n->__('Baby');
				$data_info['user_fullname'] = (string) $ps_advice->user_fullname;
				$data_info['advice_title'] = $ps_advice->title;
				$data_info['student_name'] = (string) $ps_advice->student_fullname;
				$data_info['category_name'] = (string) $ps_advice->category_name;

				$data_info ['content'] = ( string ) $ps_advice->content;
			    $data_info ['avatar_user'] = ($ps_advice->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_advice->cache_data, $ps_advice->year_data, $ps_advice->avatar, MEDIA_TYPE_RELATIVE ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
				$data_info['status_read'] = (int) $ps_advice->is_activated;
				$data_info['date_at'] = date('H:i d-m-Y', strtotime($ps_advice->date_at_send));

				array_push($data, $data_info);
			}

			$return_data['_data']['next_page'] = $next_page;

			$return_data['_data']['pre_page'] = $pre_page;

			$title = $psI18n->__('Advice of baby');

			$return_data['_data']['title'] = $psI18n->__($title);

			$return_data['_data']['data_info'] = $data;

			// $return_data ['_data'] ['user_info'] = $user;

			$return_data['_msg_code'] = MSG_CODE_TRUE;
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// Lay chi tiet 1 dan do theo advice_id
	public function detailAdvice(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array(
			'_msg_code' => MSG_CODE_TRUE,
			'message'   => $psI18n->__('No data'),
			'_msg_text' => $psI18n->__('No data'),
			'_data' => []
		);

		// get device_id app
		$device_id = $request->getHeaderLine('deviceid');

		$advice_id = $args['advice_id'];

		if (!$advice_id) {
			/*
			$return_data = array (
					'_msg_code' => MSG_CODE_TRUE,
					'message'   => $psI18n->__ ( 'No data' ),
					'_msg_text' => $psI18n->__ ( 'No data' ),
					'_data' => [ ]
			);
			
			return $response->withJson ( $return_data );
			
			$return_data ['_msg_code'] = MSG_CODE_TRUE;
			$return_data ['_msg_text'] = $psI18n->__ ( 'OK' );
			$return_data ['_data'] ['title'] = $psI18n->__ ( 'Advice' );
			*/

			$web_content = PsWebContent::BeginHTMLPageBootstrap();

			$web_content .= '<div class="container-fluid" style="">';
			$web_content .= '<div class="row">' . $psI18n->__('This content is no longer available.') . '</div>';
			$web_content .= '</div>';
			$web_content .= PsWebContent::EndHTMLPage();

			$return_data['_data']['content'] = $web_content;
		}

		try {

			if ($user->user_type == USER_TYPE_RELATIVE) {

				if (!PsAuthentication::checkDeviceUserRelative($user, $device_id)) {
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

				$ps_advice = AdviceModel::detailForRelative($advice_id);
			} elseif ($user->user_type == USER_TYPE_TEACHER) {

				$ps_advice = AdviceModel::detailForTeacher($advice_id, $user->id);
			}

			if (!$ps_advice) {
				//return $response->withJson ( $return_data );

				$return_data['_msg_code'] = MSG_CODE_TRUE;
				$return_data['message']  = $return_data['_msg_text'] = $psI18n->__('OK');
				$return_data['_data']['title'] = $psI18n->__('Advice');

				$web_content = PsWebContent::BeginHTMLPageBootstrap();

				$web_content .= '<div class="container-fluid" style="">';
				$web_content .= '<div class="row">' . $psI18n->__('This content is no longer available.') . '</div>';
				$web_content .= '</div>';
				$web_content .= PsWebContent::EndHTMLPage();

				$return_data['_data']['content'] = $web_content;
			} else {

				/*
				 * $data_info = $data = array ();
				 *
				 * $data_info ['teacher_fullname'] = ( string ) $ps_advice->teacher_fullname;
				 * $data_info ['student_name'] = ( string ) $ps_advice->student_fullname;
				 * $data_info ['category_name'] = ( string ) $ps_advice->category_name;
				 * $data_info ['class_name'] = ( string ) $ps_advice->class_name;
				 *
				 * $data_info ['advice_title'] = ( string ) $ps_advice->title;
				 * $data_info ['date_at'] = date ( 'H:i d-m-Y', strtotime ( $ps_advice->date_at_send ) );
				 *
				 * $data_info ['advice_id'] = ( int ) $ps_advice->id;
				 * $data_info ['status_read'] = ( int ) $ps_advice->is_activated;
				 * $data_info ['content'] = ( string ) $ps_advice->content;
				 *
				 * if ($user->user_type == USER_TYPE_RELATIVE) {
				 * //$user_avatar = UserModel::getUserAvatarByUserId ( $ps_advice->user_id, USER_TYPE_TEACHER );
				 * // anh giao vien
				 * $data_info ['avatar_teacher'] = ($ps_advice->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_advice->cache_data, $ps_advice->year_data, $ps_advice->avatar, MEDIA_TYPE_TEACHER ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
				 *
				 * } elseif ($user->user_type == USER_TYPE_TEACHER) {
				 *
				 * $user_avatar = UserModel::getUserAvatarByUserId ( $ps_advice->user_id, USER_TYPE_RELATIVE );
				 *
				 * $data_info ['avatar_teacher'] = ($user_avatar->avatar != '') ? PsString::getUrlMediaAvatar ( $user_avatar->cache_data, $user_avatar->year_data, $user_avatar->avatar, MEDIA_TYPE_RELATIVE ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
				 * }
				 *
				 */

				$student_avatar_url = ($ps_advice->s_avatar != '') ? PsString::getUrlMediaAvatar($ps_advice->cache_data, $ps_advice->s_year_data, $ps_advice->s_avatar, MEDIA_TYPE_STUDENT) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR_STUDENT;

				// Style content Webview
				// Set style for view HTML
				$user_app_config = json_decode($user->app_config);
				$app_config_color = (isset($user_app_config->style) && $user_app_config->style != '') ? $user_app_config->style : 'green';

				if ($app_config_color == 'yellow_orange')
					$app_config_color = 'orange';

				$web_content = PsWebContent::BeginHTMLPageBootstrap();

				$web_content .= '<div class="container-fluid" style="">';
				$web_content .= '<div class="row">';
				$web_content .= '<div class="col-3 pl-0 pr-0"><img class="rounded-circle border ks-border-light-' . $app_config_color . '" src="' . $student_avatar_url . '" style="width:100%;" /></div>';
				$web_content .= '<div class="col-9" style="line-height:1.3;">';
				$web_content .= '<div class="ks-text-' . $app_config_color . '"><span style="font-size:16px;">' . $ps_advice->student_fullname . '</span></div>';
				$web_content .= '<div class="ks-text-grey"><small>' . $psI18n->__('Birthday') . ': ' . PsDateTime::toDMY($ps_advice->birthday) . '</small></div>';
				$web_content .= '<div class="ks-text-grey"><small>' . $psI18n->__('Class') . ': ' . $ps_advice->class_name . '</small></div>';
				$web_content .= '</div>';
				$web_content .= '</div>';
				$web_content .= '</div>'; // END container-fluid


				/*
				$web_content .= '<div class="main" style="line-height:2.0;">';

				$web_content .= '<div class="row border-bottom border-top mt-2 pt-1 pb-1" style="line-height:2.0;">';
				$web_content .= '<div class="col-5">' . $psI18n->__ ( 'People advice' ) . ':</div>';
				$web_content .= '<div class="col-7 pl-0">' . $ps_advice->re_title . ' ' . $ps_advice->relative_fullname . '</div>';
				$web_content .= '</div>';

				$web_content .= '<div class="row border-bottom pt-1 pb-1" style="line-height:2.0;">';
				$web_content .= '<div class="col-5">' . $psI18n->__ ( 'Date advice' ) . ':</div>';
				$web_content .= '<div class="col-7 pl-0">' . PsDateTime::toDMY ( $ps_advice->date_at_send, "H:i d-m-Y" ) . '</div>';
				$web_content .= '</div>';

				$web_content .= '<div class="row border-bottom pt-1 pb-1" style="line-height:2.0;">';
				$web_content .= '<div class="col-5">' . $psI18n->__ ( 'Teachers receive' ) . ':</div>';
				$web_content .= '<div class="col-7 pl-0">' . $ps_advice->teacher_fullname . '</div>';
				$web_content .= '</div>';

				$web_content .= '<div class="row border-bottom pt-1 pb-1" style="line-height:2.0;">';
				$web_content .= '<div class="col-5">' . $psI18n->__ ( 'Status' ) . ':</div>';
				$web_content .= '<div class="col-7 pl-0">' . (($ps_advice->is_activated == STATUS_ACTIVE) ? '<span class="text-info">' . $psI18n->__ ( 'Confirmed' ) . '</span>' : '<span class="text-danger">' . $psI18n->__ ( 'Unconfimred' ) . '</span>') . '</div>';
				$web_content .= '</div>';

				$web_content .= '<div class="row border-bottom pt-1 pb-1" style="line-height:2.0;">';
				$web_content .= '<div class="col-5">' . $psI18n->__ ( 'Topic' ) . ':</div>';
				$web_content .= '<div class="col-7 pl-0 text-warning">' . $ps_advice->category_name . '</div>';
				$web_content .= '</div>';

				$web_content .= '<div class="row border-bottom pt-1 pb-1" style="line-height:2.0;">';
				$web_content .= '<div class="col-5">' . $psI18n->__ ( 'Subject' ) . ':</div>';
				$web_content .= '<div class="col-7 pl-0">' . $ps_advice->title . '</div>';
				$web_content .= '</div>';

				$web_content .= '<div class="row border-bottom pt-1 pb-1" style="line-height:2.0;">';
				$web_content .= '<div class="col-12"><b>' . $psI18n->__ ( 'Content' ) . '</b><br>' . $ps_advice->content;

				if ($ps_advice->af_content != '')
					$web_content .= '<br><b>' . $psI18n->__ ( 'Teacher note' ) . '</b><br>' . $ps_advice->af_content;

				$web_content .= '</div>';
				$web_content .= '</div>';
				$web_content .= '</div>';
				*/

				$txt_content =  $ps_advice->content;

				if ($ps_advice->af_content != '')
					$txt_content .= '<br><b>' . $psI18n->__('Teacher note') . '</b><br>' . $ps_advice->af_content;

				$web_content .= '<div class="container-fluid mt-2 pt-1 pb-1">';

				$web_content .= '<div class="row">';

				$web_content .= '<table class="table">
												  <tbody>
												    <tr>
												      <th>' . $psI18n->__('People advice') . '</th>
												      <td>' . $ps_advice->re_title . ' ' . $ps_advice->relative_fullname . '</td>
												    </tr><tr>
												      <th>' . $psI18n->__('Date advice') . '</th>
												      <td>' . PsDateTime::toDMY($ps_advice->date_at_send, "H:i d-m-Y") . '</td>
												    </tr><tr>
												      <th>' . $psI18n->__('Teachers receive') . '</th>
												      <td>' . $ps_advice->teacher_fullname . '</td>
												    </tr><tr>
												      <th>' . $psI18n->__('Status') . '</th>
												      <td>' . (($ps_advice->is_activated == STATUS_ACTIVE) ? '<span class="text-info">' . $psI18n->__('Confirmed') . '</span>' : '<span class="text-danger">' . $psI18n->__('Unconfimred') . '</span>') . '</td>
												    </tr><tr>
												      <th>' . $psI18n->__('Topic') . '</th>
												      <td>' . $ps_advice->category_name . '</td>
												    </tr><tr>
												      <th>' . $psI18n->__('Subject') . '</th>
												      <td>' . $ps_advice->title . '</td>
												    </tr><tr>
												      <th>' . $psI18n->__('Content') . '</th>
												      <td>' . $txt_content . '</td>
												    </tr>
												  </tbody>
												</table>';
				$web_content .= '</div>';
				$web_content .= '</div>';

				$web_content .= PsWebContent::EndHTMLPage();

				// $data_info ['content'] = $web_content;

				// array_push ( $data, $data_info );

				// $return_data ['_data'] ['data_info'] = $data;

				$return_data['_msg_code'] = MSG_CODE_TRUE;
				$return_data['message']  = $return_data['_msg_text'] = $psI18n->__('OK');

				$return_data['_data']['title'] = $psI18n->__('Advice');
				$return_data['_data']['advice_id'] = (int) $ps_advice->id;
				$return_data['_data']['status_read'] = (int) $ps_advice->is_activated;

				$return_data['_data']['content'] = $web_content;
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['_msg_text'] = $return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.') . $e->getMessage();
		}
		return $response->withJson($return_data);
	}

	// Số lượng dặn dò chưa duyệt -- app giao vien
	public function numberNotRead(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$this->WriteLog('--- show list advice---');
		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_data' => []
		);

		$user = $this->user_token;

		$psI18n = new PsI18n($this->getUserLanguage($user));
		$device_id = $request->getHeaderLine('deviceid');

		try {

			if (!PsAuthentication::checkDevice($user, $device_id)) {

				return $response->withJson($return_data);
			}

			if ($user->user_type == USER_TYPE_RELATIVE) {

				return $response->withJson($return_data);
			} elseif ($user->user_type == USER_TYPE_TEACHER) {

				$number_advices_not_read = $this->db->table(TBL_PS_ADVICES . ' as A')->select('A.id')->where('A.user_id', $user->id)->where('A.is_activated', STATUS_NOT_ACTIVE)->get()->count();

				$return_data['_data']['number_advices_not_read'] = (int) $number_advices_not_read;

				$return_data['_msg_code'] = MSG_CODE_TRUE;
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// Lay danh sach nguoi gui ( app cho phu huynh )
	public function listUserSendForRelative(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_data' => []
		);

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
					$services = $this->db->table(TBL_PS_SERVICE_COURSES . ' as SC')->join(CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'SC.ps_service_id')->join(CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.ps_service_course_id', '=', 'SC.id')->selectRaw('S.id')->where('SS.student_id', $student_id)->whereNull('SS.delete_at')->whereDate('SC.start_at', '<=', date('Y-m-d'))->whereDate('SC.end_at', '>=', date('Y-m-d'))->where('S.ps_customer_id', $ps_student->ps_customer_id)->where('SC.is_activated', STATUS_ACTIVE)->where('S.enable_roll', ENABLE_ROLL_SCHEDULE)->where('S.is_activated', STATUS_ACTIVE)->distinct()->get();

					foreach ($services as $service) {
						array_push($list_service, $service->id);
					}

					// Danh sach giao vien nhan dặn dò
					$data_member = array();

					$list_user = $this->db->table(CONST_TBL_USER . ' as U')->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'U.ps_customer_id')->leftjoin(CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'U.member_id')->leftJoin(TBL_PS_TEACHER_CLASS . ' as TC', function ($q) {
						$q->on('TC.ps_member_id', '=', 'M.id')->where('TC.is_activated', STATUS_ACTIVE)->whereDate('TC.start_at', '<', date('Y-m-d'))->whereDate('TC.stop_at', '>', date('Y-m-d'));
					})->leftjoin(TBL_PS_SERVICE_COURSES . ' as SC', 'SC.ps_member_id', '=', 'M.id')->select('U.id as user_id', 'U.user_type', 'M.avatar as avatar', 'M.year_data', 'C.cache_data')->selectRaw('CONCAT(U.first_name," ", U.last_name) AS fullname')->where('U.ps_customer_id', $user->ps_customer_id)->where(function ($query) use ($ps_student, $list_service) {
						$query->where('TC.ps_myclass_id', $ps_student->class_id)->orwhereIn('SC.ps_service_id', $list_service);
					})->where('U.user_type', USER_TYPE_TEACHER)->where('U.is_active', STATUS_ACTIVE)->distinct()->get();

					$data_member['list'] = array();

					foreach ($list_user as $_user) {

						$list_member = array();

						$list_member['user_id'] = (int) $_user->user_id;
						$list_member['full_name'] = (string) $_user->fullname;
						// $list_member ['avatar_url'] = PsString::generateUrlImage ( 'hr', $_user->avatar, $user->ps_customer_id, $api_token );
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

	// Lấy danh mục dặn dò - app phu huynh - app giao vien
	public function adviceCategories(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_data' => []
		);

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$device_id = $request->getHeaderLine('deviceid');

		try {

			if ($user->user_type == USER_TYPE_RELATIVE) {

				if (!PsAuthentication::checkDevice($user, $device_id)) {

					return $response->withJson($return_data);
				}

				$amount_info = PsMobileAppAmountsModel::checkAmountInfo($user->id);

				if (!$amount_info) {

					$return_data = array(
						'_msg_code' => MSG_CODE_PAYMENT,
						'message' => $psI18n->__('Your account has run out of money. Please recharge to continue using.')
					);

					return $response->withJson($return_data);
				}

				$student_id = $args['student_id'];

				$ps_student = StudentModel::getStudentForRelative($student_id, $user->member_id);

				if ($ps_student) {

					$_data = array();
					// Danh mục dặn dò
					$categories = $this->db->table(TBL_PS_ADVICE_CATEGORIES . ' as CAT')->select('CAT.id as category_id', 'CAT.title')->where('CAT.is_activated', STATUS_ACTIVE)->where('CAT.ps_customer_id', $ps_student->ps_customer_id)->get();

					foreach ($categories as $_category) {

						$_categories = array();

						$_categories['category_id'] = (int) $_category->category_id;
						$_categories['title'] = (string) $_category->title;
						array_push($_data, $_categories);
					}

					$return_data['_msg_code'] = MSG_CODE_TRUE;

					$return_data['_data'] = $_data;
				} else {
					$return_data['_msg_text'] = $psI18n->__('You do not have access to this data');
				}
			} elseif ($user->user_type == USER_TYPE_TEACHER) {

				$_data = array();

				$categories = $this->db->table(TBL_PS_ADVICE_CATEGORIES . ' as CAT')->select('CAT.id as category_id', 'CAT.title')->where('CAT.is_activated', STATUS_ACTIVE)->where('CAT.ps_customer_id', $user->ps_customer_id)->get();

				foreach ($categories as $_category) {

					$_categories = array();
					$_categories['category_id'] = (int) $_category->category_id;
					$_categories['title'] = (string) $_category->title;

					array_push($_data, $_categories);
				}

				$return_data['_msg_code'] = MSG_CODE_TRUE;

				$return_data['_data'] = $_data;
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// Giáo viên Xác nhận dặn dò của phụ huynh
	public function confirmAdvice(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$psI18n = new PsI18n($this->getUserLanguage($user));

		$return_data = array();
		$return_data['_msg_code'] = MSG_CODE_FALSE;
		$return_data['_msg_text'] = '';

		if ($user->user_type != USER_TYPE_TEACHER) {

			$return_data['_msg_text'] = $psI18n->__('You do not have access to this data');

			return $response->withJson($return_data);
		}

		$advice_id = $args['advice_id'];

		if (!$advice_id) {

			$return_data['_msg_text'] = $psI18n->__('No data');

			return $response->withJson($return_data);
		}

		$body = $request->getParsedBody();

		$info = isset($body['info']) ? $body['info'] : '';

		$note = isset($info['note']) ? PsString::trimString($info['note']) : '';

		if (PsString::length($note) > 255) {
			$return_data['_msg_text'] = $psI18n->__('Confirm failure.') . PsString::newLine() . $psI18n->__('Reply content up to 255 characters.');
			return $response->withJson($return_data);
		}

		try {

			$curr_datetime = date('Y-m-d H:i:s');

			$confirm = AdviceModel::find($advice_id);
			$confirm->is_activated = STATUS_ACTIVE;
			$confirm->updated_at = $curr_datetime;

			if ($confirm->save() && $note != '') {

				$feedback = $this->db->table(TBL_PS_ADVICE_FEEDBACKS)->insertGetId([
					'advice_id' => (int) $advice_id,
					'umember_id' => (int) $user->id,
					'is_teacher' => STATUS_ACTIVE,
					'content' => $note,
					'is_activated' => STATUS_ACTIVE,
					'user_created_id' => $user->id,
					'user_updated_id' => $user->id,
					'created_at' => $curr_datetime,
					'updated_at' => $curr_datetime
				]);
			}

			// Gui thong bao cho PH la Don da duoc Xac nhan
			$this->pushNotificationAdviceToRelative($psI18n, $user, $confirm);

			$return_data['_msg_code'] = MSG_CODE_TRUE;

			$return_data['_msg_text'] = $psI18n->__('Confirm successfully.');
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}
		return $response->withJson($return_data);
	}

	/**
	 * pushNotificationAdviceNewToTeacher($psI18n, $user, $ps_album)
	 * Ham push notification den giao vien
	 *
	 * @author thangnc
	 *        
	 * @param $psI18n -
	 *        	mixed
	 * @param $user -
	 *        	mixed, User gửi (Nguoi than gui)
	 * @param $ps_advice -
	 *        	mixed, Dan do
	 * @return void
	 */
	/**
	 * $ps_new_advice->title = ( string ) $title;
	 * $ps_new_advice->content = ( string ) $content;
	 * $ps_new_advice->date_at = $curren_datetime;
	 * $ps_new_advice->user_id = ( int ) $user_id; // GV nhận
	 * $ps_new_advice->category_id = ( int ) $category_id;
	 * $ps_new_advice->student_id = ( int ) $student_id;
	 * $ps_new_advice->relative_id = ( int ) $user->member_id;
	 * $ps_new_advice->user_created_id = ( int ) $user->id;
	 * $ps_new_advice->created_at = $curren_datetime;
	 * $ps_new_advice->updated_at = $curren_datetime;
	 */
	protected function pushNotificationAdviceNewToTeacher($psI18n, $user, $ps_advice)
	{
		if ($ps_advice->id <= 0)

			return false;

		// Lay thong tin hoc sinh tu Dan do: Hoc sinh, Lop hien tai
		$ps_student = StudentModel::getStudentInfoShortForTeacher($ps_advice->student_id, $user->ps_customer_id, $ps_advice->relative_id);

		// Lay giao vien nhan
		$user_teacher = UserModel::getUserByArrayUserId(array(
			$ps_advice->user_id
		), false)->first();

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

			$setting_notification->title = $psI18n->__("Parents advised from baby") . ' ' . $ps_student->first_name . ' ' . $ps_student->last_name;

			$setting_notification->subTitle = $ps_advice->title;
			$setting_notification->tickerText = $ps_advice->title;

			$setting_notification->message = $ps_advice->content;
			$setting_notification->lights = '1';
			$setting_notification->vibrate = '1';
			$setting_notification->sound = '1';
			$setting_notification->smallIcon = IC_SMALL_NOTIFICATION;
			$setting_notification->smallIconOld = 'ic_small_notification_old';

			$setting_notification->largeIcon = $avatar_url;

			$setting_notification->screenCode = PS_CONST_SCREEN_ADVICE_DETAIL;
			$setting_notification->itemId = $ps_advice->id;
			$setting_notification->studentId = $ps_advice->student_id;
			$setting_notification->clickUrl = '';
			$setting_notification->registrationIds = $user_teacher->notification_token;

			$notification = new PsNotification($setting_notification);

			return $notification->pushNotification($user_teacher->osname);
		}
	}

	/**
	 * pushNotificationAdviceToRelative($psI18n, $user, $ps_album)
	 * Ham push notification den phu huynh
	 *
	 * @author thangnc
	 *        
	 * @param $psI18n -
	 *        	mixed
	 * @param $user -
	 *        	mixed, User gửi (Nguoi than gui)
	 * @param $ps_advice -
	 *        	mixed, Dan do
	 * @return void
	 */
	/**
	 * $ps_new_advice->title = ( string ) $title;
	 * $ps_new_advice->content = ( string ) $content;
	 * $ps_new_advice->date_at = $curren_datetime;
	 * $ps_new_advice->user_id = ( int ) $user_id; // GV nhận
	 * $ps_new_advice->category_id = ( int ) $category_id;
	 * $ps_new_advice->student_id = ( int ) $student_id;
	 * $ps_new_advice->relative_id = ( int ) $user->member_id;
	 * $ps_new_advice->user_created_id = ( int ) $user->id;
	 * $ps_new_advice->created_at = $curren_datetime;
	 * $ps_new_advice->updated_at = $curren_datetime;
	 */
	protected function pushNotificationAdviceToRelative($psI18n, $user, $ps_advice)
	{
		if ($ps_advice->id <= 0)

			return false;

		// Lay thong tin hoc sinh tu Dan do: Hoc sinh, Lop hien tai
		$ps_student = StudentModel::getStudentForRelative($ps_advice->student_id, $ps_advice->relative_id);

		// Lay phu huynh nhan
		$user_relative = UserModel::getUserByArrayUserId(array(
			$ps_advice->user_created_id
		), false)->first();

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

			$setting_notification->title = $ps_advice->title;

			$setting_notification->subTitle = $psI18n->__("Has been confirmed") . ' ' . $user->first_name . ' ' . $user->last_name;
			$setting_notification->tickerText = $ps_advice->title;

			$setting_notification->message = $ps_advice->content;
			$setting_notification->lights = '1';
			$setting_notification->vibrate = '1';
			$setting_notification->sound = '1';
			$setting_notification->smallIcon = IC_SMALL_NOTIFICATION;
			$setting_notification->smallIconOld = 'ic_small_notification_old';

			$setting_notification->largeIcon = $avatar_url;

			$setting_notification->screenCode = PS_CONST_SCREEN_ADVICE_DETAIL;
			$setting_notification->itemId = $ps_advice->id;
			$setting_notification->studentId = $ps_advice->student_id;
			$setting_notification->clickUrl = '';
			$setting_notification->registrationIds = $user_relative->notification_token;

			$notification = new PsNotification($setting_notification);

			return $notification->pushNotification($user_relative->osname);
		}
	}
}
