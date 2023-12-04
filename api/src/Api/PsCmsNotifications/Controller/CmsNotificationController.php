<?php

namespace Api\PsCmsNotifications\Controller;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Exception;
use Respect\Validation\Validator as vali;
use App\Authentication\PsAuthentication;
use App\Controller\BaseController;
use Api\Users\Model\UserModel;
use Api\Students\Model\StudentModel;
use Api\PsCmsNotifications\Model\PsCmsNotificationsModel;
use App\Model\PsWorkPlacesModel;
use App\Model\PsMobileAppAmountsModel;
use App\PsUtil\PsFile;
use App\PsUtil\PsString;
use App\PsUtil\PsEndCode;
use App\PsUtil\PsI18n;
use App\PsUtil\PsNotification;
use App\PsUtil\PsWebContent;
use App\PsUtil\PsDateTime;

class CmsNotificationController extends BaseController
{

	public $container;

	protected $user_token;

	public function __construct(LoggerInterface $logger, $container, $app)
	{
		$this->logger = $logger;

		$this->db = $container['db'];

		$this->container = $container;

		$this->user_token = $app->user_token;
	}

	// So thong bao chua doc
	public function notRead(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$return_data = array();

		$return_data['_msg_code'] = MSG_CODE_TRUE;

		//$return_data['_data']['number_notifications_not_read'] = 0;

		$user = $this->user_token;

		$number_notifications = $this->db->table(TBL_PS_CMS_RECEIVED_NOTIFICATION . ' as RN')->leftJoin(TBL_PS_CMS_NOTIFICATIONS . ' as N', function ($q) {
			$q->on('RN.ps_cms_notification_id', '=', 'N.id'); // kiem tra key giai ma
		})->where('RN.is_delete', 0)->where('RN.is_read', 0)->where('RN.user_id', $user->id)->where('N.user_created_id', '!=', $user->id)->where('N.is_status', 'sent')->count();

		$return_data['_data']['number_notifications_not_read'] = $number_notifications;

		return $response->withJson($return_data);
	} 

	public function notReadAppT(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$return_data = array();

		$return_data['_msg_code'] = MSG_CODE_TRUE;

		//$return_data['_data']['number_notifications_not_read'] = 0;

		$user = $this->user_token;

		$psI18n = new PsI18n($this->getUserLanguage($user));

		
		$number_notifications = $this->db->table(TBL_PS_CMS_RECEIVED_NOTIFICATION . ' as RN')->leftJoin(TBL_PS_CMS_NOTIFICATIONS . ' as N', function ($q) {
			$q->on('RN.ps_cms_notification_id', '=', 'N.id'); // kiem tra key giai ma
		})->where('RN.is_delete', 0)->where('RN.is_read', 0)->where('RN.user_id', $user->id)->where('N.is_status', 'sent')->count(); 
		//return $user->id;
   
		$return_data['_data']['number_notifications_not_read'] = $number_notifications;

		return $response->withJson($return_data);
	}

	// Lay danh sach thong bao
	public function listNotifications(RequestInterface $request, ResponseInterface $response, array $args)
	{

		// $this->WriteLog ( '--- BEGIN List notification---' );
		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_data' => []
		);

		$user = $this->user_token;

		$psI18n = new PsI18n($this->getUserLanguage($user));

		$device_id = $request->getHeaderLine('deviceid');

		$page = isset($args['page']) ? (int) $args['page'] : 1;

		$ntype = isset($args['ntype']) ? $args['ntype'] : 'received';

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
			}

			if ($ntype == 'received') {
				$ps_notifications = $this->db->table(TBL_PS_CMS_NOTIFICATIONS . ' as N')->leftJoin(TBL_PS_CMS_RECEIVED_NOTIFICATION . ' as RN', function ($q) {
					$q->on('RN.ps_cms_notification_id', '=', 'N.id');
				})->leftJoin(CONST_TBL_USER . ' as U', 'N.user_created_id', '=', 'U.id')->select('N.id', 'N.title', 'N.description', 'N.is_status', 'N.private_key', 'N.date_at as date_at_send', 'RN.is_read', 'RN.date_at as date_at_read', 'N.text_object_received', 'N.user_created_id', 'U.username')->selectRaw('CONCAT(U.first_name," ", U.last_name) AS user_fullname')->where('RN.user_id', $user->id)->where('N.user_created_id', '!=', $user->id)->where('N.is_status', 'sent'); // minh gui cho minh thi ko hien
			} else if ($ntype == 'sent') {

				// $ps_notifications = $this->db->table(TBL_PS_CMS_NOTIFICATIONS . ' as N')->leftJoin(TBL_PS_CMS_RECEIVED_NOTIFICATION . ' as RN', function ($q) {
				// 	$q->on('RN.ps_cms_notification_id', '=', 'N.id');
				// })->select('N.id', 'N.title', 'N.date_at as date_at_send', 'N.user_created_id', 'N.text_object_received' , 'N.description')->where('N.user_created_id', $user->id)->where('RN.user_id', $user->id)->where('N.is_status', 'sent');

       $ps_notifications = $this->db->table(TBL_PS_CMS_NOTIFICATIONS . ' as N')
    	->leftJoin(TBL_PS_CMS_RECEIVED_NOTIFICATION . ' as RN', 'RN.ps_cms_notification_id', '=', 'N.id')
    	->select('N.*', 'RN.user_id as received_user_id', 'RN.is_read')
    	->where('RN.user_id',$user->id)
   	    ->where('N.is_status', 'sent')
   	    ->orderBy('N.id','desc');
 
          // print_r($ps_notifications);


			} else {
				return $response->withJson($return_data);
			} 

            
			$ps_notifications_count = $ps_notifications->get()->count();

			$limit = PS_CONST_LIMIT_NOTIFICATION;

			if ($ps_notifications_count % $limit == 0) {
				$ps_notifications_number_pages = $ps_notifications_count / $limit;
			} else {
				$ps_notifications_number_pages = (int) ($ps_notifications_count / $limit) + 1;
			}

			if ($page > $ps_notifications_number_pages) {
				$page = 1;
			}

			$next_page = ($page + 1);

			$pre_page  = ($page - 1);

			if (($ps_notifications_number_pages == 1) || ($page == 1)) {
				$pre_page = 0;
			}

			if (($ps_notifications_number_pages == 1) || ($page == $ps_notifications_number_pages)) {
				$next_page = 0;
			}

			$ps_notifications = $ps_notifications->forPage($page, $limit)->orderBy('N.date_at', 'desc')->get();
         
			$data_info = $data = array();

        


			foreach ($ps_notifications as $ps_notification) {
               
            
				if ($ntype == 'received') { // nhan thi obj_title: nguoi gui

					$data_info['obj_title'] = $psI18n->__('From') . ' ' . $ps_notification->user_fullname;

					$data_info['status_read'] = $ps_notification->is_read;
				} else if ($ntype == 'sent') { 
                      
					$data_info['status_read'] = $ps_notification->is_read;
                    
					$list_received = explode(',', $ps_notification->text_object_received);

					$list_user = UserModel::getUserByArrayUserId($list_received);

					$user_received = '';

					if (count($list_user) > 1) {

						foreach ($list_user as $user) {
							$user_received = $user->first_name . ' ' . $user->last_name . '; ' . $user_received;
						}

						$data_info['obj_title'] = $user_received . '...';
					} else {

						foreach ($list_user as $user) {
							$user_received = $user->first_name . ' ' . $user->last_name;
						}

						$data_info['obj_title'] = $psI18n->__('Sent to') . ' ' . $user_received;
					}
				}

				$data_info['notification_id'] = $ps_notification->id;

				$data_info['notification_title'] = $ps_notification->title;

				$data_info['notification_description'] = $ps_notification->description;

				$data_info['root_screen'] = $ps_notification->root_screen;

				$data_info['date_at'] = date('H:i d-m-Y', strtotime($ps_notification->date_at));

				array_push($data, $data_info);

			}

			$return_data['_data']['next_page'] = $next_page;

			$return_data['_data']['pre_page'] = $pre_page;

			$return_data['_data']['tb_chuadoc'] = $dem_notifications;

			$return_data['_data']['title'] = $psI18n->__('Notification');

			$return_data['_data']['data_info'] = $data;

			$return_data['_msg_code'] = MSG_CODE_TRUE;
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		// $this->WriteLog ( '--- END List notification ---' );

		return $response->withJson($return_data);
	}

	// Lay chi tiet 1 notification theo n_id
	public function detail(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_data' => []
		);

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$user_app_config = json_decode($user->app_config);

		$app_config_color = (isset($user_app_config->style) && $user_app_config->style != '') ? $user_app_config->style : 'green';

		if ($app_config_color == 'yellow_orange')
			$app_config_color = 'orange';

		// get device_id app
		$device_id = $request->getHeaderLine('deviceid');

		$notification_id = $args['n_id'];

		$ntype = $args['ntype'];

		try {

			if (!PsAuthentication::checkDevice($user, $device_id)) {
				return $response->withJson($return_data);
			}

			// Check tiền
			if ($user->user_type == USER_TYPE_RELATIVE) {

				$amount_info = PsMobileAppAmountsModel::checkAmountInfo($user->id);

				if (!$amount_info) {

					$return_data = array(
						'_msg_code' => MSG_CODE_PAYMENT,
						'message' => $psI18n->__('Your account has run out of money. Please recharge to continue using.')
					);

					return $response->withJson($return_data);
				}
			}

			// Lay thong bao theo id
			$ps_notifications = $this->db->table(TBL_PS_CMS_NOTIFICATIONS . ' as N')
				->leftJoin(TBL_PS_CMS_RECEIVED_NOTIFICATION . ' as RN', function ($q) {
					$q->on('RN.ps_cms_notification_id', '=', 'N.id'); // kiem tra key giai ma
				})
				->leftJoin(CONST_TBL_USER . ' as U', 'N.user_created_id', '=', 'U.id')
				->select('N.id', 'N.title', 'N.description', 'N.is_status', 'N.private_key', 'N.date_at as date_at_send', 'RN.is_read', 'RN.date_at as date_at_read', 'N.text_object_received', 'N.user_created_id', 'U.username', 'U.user_type')
				->selectRaw('CONCAT(U.first_name," ", U.last_name) AS user_fullname')
				->where('N.id', $notification_id)->get()->first(); 

			if (!$ps_notifications) {

				$return_data['_msg_code'] = MSG_CODE_TRUE;

				// $return_data ['_data'] ['content'] = $psI18n->__ ( 'This content is no longer available.' );

				$web_content = PsWebContent::BeginHTMLPage();
				$web_content .= '<div class="w3-padding-16 w3-text-red w3-center">' . $psI18n->__('This content is no longer available.') . '</div>';
				$web_content .= PsWebContent::EndHTMLPage();

				$return_data['_data']['content'] = $web_content;

				return $response->withJson($return_data);
			}

			$notifications = new \stdClass();

			if ($ntype == 'received') { // Thư đến

				// lay avatar
				$user_avatar = UserModel::getUserAvatarByUserId($ps_notifications->user_created_id, $ps_notifications->user_type);

				if ($ps_notifications->user_type == USER_TYPE_TEACHER) {

					// $notifications->avatar_url = PsString::generateUrlImage('hr', $user_avatar->avatar, $user->ps_customer_id, $api_token);
					// $notifications->avatar_url = PsString::getUrlPsAvatar ( $user_avatar->school_code, 1, $user_avatar->avatar );

					$notifications->avatar_url = ($user_avatar->avatar != '') ? PsString::getUrlMediaAvatar($user_avatar->cache_data, $user_avatar->year_data, $user_avatar->avatar, MEDIA_TYPE_TEACHER) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
				} else if ($ps_notifications->user_type == USER_TYPE_RELATIVE) {
					// $notifications->avatar_url = PsString::generateUrlImage('relative', $user_avatar->avatar, $user->ps_customer_id, $api_token);
					$notifications->avatar_url = ($user_avatar->avatar != '') ? PsString::getUrlMediaAvatar($user_avatar->cache_data, $user_avatar->year_data, $user_avatar->avatar, MEDIA_TYPE_RELATIVE) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
				}

				$notifications->obj_title = $psI18n->__('Sender') . ': ' . PsString::htmlSpecialChars($ps_notifications->user_fullname);
			} else if ($ntype == 'sent') { // Thư đi

				$list_received = explode(',', $ps_notifications->text_object_received);

				$list_user = UserModel::getUserByArrayUserId($list_received);

				$user_received = null;

				$i = 0;

				foreach ($list_user as $user) {
					$user_received = $user->first_name . ' ' . $user->last_name . '; ' . $user_received;
					$i++;
					if ($i == 3) {
						$user_received = $user_received . '...';
						break;
					}
				}

				$notifications->avatar_url = PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
				$notifications->obj_title = $psI18n->__('Receiver') . ': ' . PsString::htmlSpecialChars($user_received);
			}

			// $notifications->date_at_send = date ( 'H:i d-m-Y', strtotime ( $ps_notifications->date_at_send ) );
			$notifications->date_at_send = $psI18n->__('Sent date') . ': ' . date('H:i d-m-Y', strtotime($ps_notifications->date_at_send));

			// $notifications->notification_title = PsEndCode::ps64EndCode ( $ps_notifications->title );

			// $notifications->notification_content = PsEndCode::ps64EndCode ( $ps_notifications->description );

			$notifications->notification_title = $ps_notifications->title;
			$notifications->notification_content = $ps_notifications->description;

			// update lai trang thai doc
			if ($ps_notifications->is_status == 'sent' && $ntype == 'sent') {
				
				$received_notification = $this->db->table(TBL_PS_CMS_RECEIVED_NOTIFICATION)->where('ps_cms_notification_id', $notification_id)->where('user_id', $user->id)->update([
					'is_read' => 1,
					'date_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
				]);

		

			}
            //$return_data['giang'] = $received_notification;
			$return_data['_msg_code'] = MSG_CODE_TRUE;

			// Noi dung tra ve kieu web content
			$web_content = PsWebContent::BeginHTMLPage();

			$web_content .= '<div class="w3-panel">';

			if ($ntype == 'received') {
				// Top
				$web_content .= '<div class="w3-row">';
				$web_content .= '<div class="w3-col s2">';
				$web_content .= '<img alt="img" class="w3-circle" src="' . $notifications->avatar_url . '" style="width:100%;"/>';
				$web_content .= '</div>';

				$web_content .= '<div class="w3-col s10" style="padding-left:5px;">';
				$web_content .= '<div class="w3-text-' . $app_config_color . '">' . $notifications->obj_title . '</div>';
				$web_content .= '<small class="small w3-text-grey"><dfn>' . $notifications->date_at_send . '</dfn></small>';
				$web_content .= '</div>';
				$web_content .= '</div>';
			} elseif ($ntype == 'sent') {
				// Top
				$web_content .= '<div class="w3-row">';
				$web_content .= '<div class="w3-col s12">';
				$web_content .= '<div class="w3-text-' . $app_config_color . '">' . $notifications->obj_title . '</div>';
				$web_content .= '<small class="small w3-text-grey"><dfn>' . $notifications->date_at_send . '</dfn></small>';
				$web_content .= '</div>';
				$web_content .= '</div>';
			}

			// Content
			$web_content .= '<div class="w3-row">';
			$web_content .= '<div class="w3-col s12">';
			$web_content .= '<h6 class="w3-text-' . $app_config_color . '">' . PsString::htmlSpecialChars($notifications->notification_title) . '</h6>';

			/*
			if (date ( 'Ymd', strtotime($notifications->date_at_send )) <= '20190401') {
				$web_content .= '<div style="text-align:justify;">' . PsString::nl2brChars ( $notifications->notification_content ) . '</div>';
			} else {
				$web_content .= '<div style="text-align:justify;">' . $notifications->notification_content. '</div>';
			}
			*/

			$web_content .= '<div style="text-align:justify;">' . $notifications->notification_content . '</div>';

			$web_content .= '</div>';
			$web_content .= '</div>';

			$web_content .= '</div>';

			$web_content .= PsWebContent::EndHTMLPage();

			$return_data['_data']['content'] = $web_content;
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

					// Danh sach giao vien nhan thong bao
					$data_member = array();
					$data_member['title'] = $psI18n->__('Teacher');

					$list_user = $this->db->table(CONST_TBL_USER . ' as U')->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'U.ps_customer_id')->leftjoin(CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'U.member_id')->leftJoin(TBL_PS_TEACHER_CLASS . ' as TC', function ($q) {
						$q->on('TC.ps_member_id', '=', 'M.id')->where('TC.is_activated', STATUS_ACTIVE)->whereDate('TC.start_at', '<', date('Y-m-d'))->whereDate('TC.stop_at', '>', date('Y-m-d'));
					})->leftjoin(TBL_PS_SERVICE_COURSES . ' as SC', 'SC.ps_member_id', '=', 'M.id')->select('U.id as user_id', 'U.user_type', 'M.avatar as avatar', 'M.year_data', 'C.cache_data')->selectRaw('CONCAT(U.first_name," ", U.last_name) AS fullname')->where('U.ps_customer_id', $user->ps_customer_id)->where(function ($query) use ($ps_student, $list_service) {
						$query->where('TC.ps_myclass_id', $ps_student->class_id)->orwhereIn('SC.ps_service_id', $list_service);
					})->where('U.user_type', USER_TYPE_TEACHER)->where('U.is_active', STATUS_ACTIVE)->distinct()->get();

					$data_member['list'] = array();

					foreach ($list_user as $_user) {

						$list_member = array();

						$list_member['user_id'] = $_user->user_id;
						$list_member['full_name'] = $_user->fullname;
						$list_member['avatar_url'] = ($_user->avatar != '') ? PsString::getUrlMediaAvatar($_user->cache_data, $_user->year_data, $_user->avatar, MEDIA_TYPE_TEACHER) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
						array_push($data_member['list'], $list_member);
					}

					array_push($return_data['_data'], $data_member);

					// kiểm tra cấu hình của trường
					$ps_workplace = PsWorkPlacesModel::select('config_msg_relative_to_relative')->where('id', $ps_student->ps_workplace_id)->get()->first();

					// Neu cho phep phu huynh gui cho nhau
					if ($ps_workplace && $ps_workplace->config_msg_relative_to_relative == 1) {

						$data_relative = array();

						$data_relative['title'] = $psI18n->__('Relatives of the baby');
						$data_relative['list'] = array();

						// Danh sach nguoi than nhan thong bao
						$list_user = $this->db->table(CONST_TBL_USER . ' as U')->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'U.ps_customer_id')->leftjoin(CONST_TBL_RELATIVE . ' as R', 'R.id', '=', 'U.member_id')->leftjoin(CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', 'R.id')->leftjoin(CONST_TBL_STUDENT . ' as S', 'RS.student_id', '=', 'S.id')->leftjoin(CONST_TBL_STUDENTCLASS . ' as SC', 'SC.student_id', '=', 'S.id')->leftJoin(CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.student_id', 'S.id')->join(CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id')->select('U.id as user_id', 'U.user_type', 'R.avatar as avatar', 'R.year_data', 'C.cache_data', 'RE.title AS re_title')->selectRaw('CONCAT(U.first_name," ", U.last_name) AS fullname, CONCAT(S.first_name," ",S.last_name) AS student_name')->where('U.ps_customer_id', $user->ps_customer_id)->where('U.user_type', USER_TYPE_RELATIVE)->where('U.is_active', STATUS_ACTIVE)->where('U.id', '!=', $user->id)->whereRaw('S.deleted_at IS NULL')->where(function ($query) use ($ps_student, $list_service) {
							$query->where('SC.myclass_id', $ps_student->class_id)->orwhereIn('SS.service_id', $list_service);
						})->groupby('U.id')->distinct()->get();

						foreach ($list_user as $_user) {

							$list_relative = array();

							$list_relative['user_id'] = $_user->user_id;

							$list_relative['info'] = $_user->re_title . ' ' . $psI18n->__('baby') . ' ' . $_user->student_name;

							$list_relative['full_name'] = $_user->fullname . ', ' . $list_relative['info'];

							$list_relative['avatar_url'] = ($_user->avatar != '') ? PsString::getUrlMediaAvatar($_user->cache_data, $_user->year_data, $_user->avatar, MEDIA_TYPE_RELATIVE) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

							array_push($data_relative['list'], $list_relative);
						}

						array_push($return_data['_data'], $data_relative);
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

	// Lay danh sach nguoi gui cho giao vien
	public function listUserSendForTeacher(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_data' => []
		);

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		try {

			if ($user->user_type == USER_TYPE_TEACHER) {

				$date_at = date("Y-m-d");

				$data_relative = $list_service = $list_class = array();

				$data_relative['title'] = $psI18n->__('Relatives in class');

				// lay danh sach dich vu ma giao vien dang ky day
				$services = $this->db->table(CONST_TBL_SERVICE . ' as S')->select('S.id')->join(TBL_PS_SERVICE_COURSES . ' as SC', 'SC.ps_service_id', '=', 'S.id')->whereDate('SC.start_at', '<=', date('Y-m-d'))->whereDate('SC.end_at', '>=', date('Y-m-d'))->where('S.ps_customer_id', $user->ps_customer_id)->where('SC.is_activated', STATUS_ACTIVE)->where('S.enable_schedule', STATUS_ACTIVE)->where('SC.ps_member_id', $user->member_id)->where('S.is_activated', STATUS_ACTIVE)->distinct()->get();
				foreach ($services as $service) {
					array_push($list_service, $service->id);
				}

				// lay danh sach lop ma giao vien day
				$my_class = $this->db->table(CONST_TBL_MYCLASS . ' as MC')->select('MC.id')->join(TBL_PS_TEACHER_CLASS . ' as TC', 'TC.ps_myclass_id', '=', 'MC.id')->whereDate('TC.start_at', '<=', date('Y-m-d'))->whereDate('TC.stop_at', '>=', date('Y-m-d'))->where('MC.ps_customer_id', $user->ps_customer_id)->where('TC.is_activated', STATUS_ACTIVE)->where('MC.is_activated', STATUS_ACTIVE)->where('TC.ps_member_id', $user->member_id)->distinct()->get();

				foreach ($my_class as $class) {
					array_push($list_class, $class->id);
				}

				// Danh sach nguoi than nhan thong bao
				$list_user = $this->db->table(CONST_TBL_USER . ' as U')->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'U.ps_customer_id')->leftjoin(CONST_TBL_RELATIVE . ' as R', 'R.id', '=', 'U.member_id')->leftjoin(CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', 'R.id')->leftjoin(CONST_TBL_STUDENT . ' as S', 'RS.student_id', '=', 'S.id')->

					// ->leftjoin ( CONST_TBL_STUDENTCLASS . ' as SC', 'SC.student_id', '=', 'S.id' )

					join(CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id')->join(CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($date_at) {
						$q->on('SC.student_id', '=', 'S.id')->where('SC.is_activated', STATUS_ACTIVE)->whereIn('SC.type', [
							STUDENT_HT,
							STUDENT_CT
						])->whereDate('SC.start_at', '<=', $date_at)->whereRaw('(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $date_at . '", "%Y%m%d") OR SC.stop_at IS NULL )');
					})->leftJoin(CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.student_id', 'S.id')->select('U.id as user_id', 'U.user_type', 'R.avatar as avatar', 'R.year_data', 'C.cache_data', 'RE.title AS re_title', 'S.last_name AS student_name')->selectRaw('CONCAT(U.first_name," ", U.last_name) AS fullname')->where('U.ps_customer_id', $user->ps_customer_id)->where('U.user_type', USER_TYPE_RELATIVE)->where('U.is_active', STATUS_ACTIVE)->whereRaw('S.deleted_at IS NULL')->where(function ($query) use ($list_class, $list_service) {
						$query->whereIn('SC.myclass_id', $list_class)->orwhereIn('SS.service_id', $list_service);
					})->groupby('U.id')->distinct()->get();

				$data_relative['list'] = array();

				foreach ($list_user as $_user) {

					$list_relative = array();

					$list_relative['user_id'] = $_user->user_id;

					$list_relative['info'] = $_user->re_title . ' ' . $psI18n->__('baby') . ' ' . $_user->student_name;

					$list_relative['full_name'] = $_user->fullname . ', ' . $list_relative['info'];

					$list_relative['avatar_url'] = ($_user->avatar != '') ? PsString::getUrlMediaAvatar($_user->cache_data, $_user->year_data, $_user->avatar, MEDIA_TYPE_RELATIVE) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

					array_push($data_relative['list'], $list_relative);
				}

				// Danh sach giao vien nhan thong bao
				$data_member = array();
				$data_member['title'] = $psI18n->__('Teacher');

				$list_user = $this->db->table(CONST_TBL_USER . ' as U')
					->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'U.ps_customer_id')
					->leftjoin(CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'U.member_id')->join(TBL_PS_TEACHER_CLASS . ' as TC', 'TC.ps_member_id', '=', 'M.id')

					/*
				->join ( TBL_PS_TEACHER_CLASS . ' as TC', function ($q, $list_class) {
					$q->on ( 'TC.ps_member_id', '=', 'M.id' )
					->where ( 'TC.is_activated', STATUS_ACTIVE )
					->whereIn ( 'TC.ps_myclass_id', $list_class )
					->whereDate ( 'TC.start_at', '<', date ( 'Y-m-d' ) )
					->whereDate ( 'TC.stop_at', '>', date ( 'Y-m-d' ) );
				} )
				*/

					->select('U.id as user_id', 'U.user_type', 'M.avatar as avatar', 'M.year_data', 'C.cache_data')
					->selectRaw('CONCAT(U.first_name," ", U.last_name) AS fullname')
					->where('U.ps_customer_id', $user->ps_customer_id)
					->where('U.user_type', USER_TYPE_TEACHER)
					->where('U.is_active', STATUS_ACTIVE)
					->where('U.id', '!=', $user->id)
					->whereIn('TC.ps_myclass_id', $list_class)->distinct()->get();

				$data_member['list'] = array();

				foreach ($list_user as $_user) {

					$list_member = array();

					$list_member['user_id'] = $_user->user_id;
					$list_member['full_name'] = $_user->fullname;
					$list_member['avatar_url'] = ($_user->avatar != '') ? PsString::getUrlMediaAvatar($_user->cache_data, $_user->year_data, $_user->avatar, MEDIA_TYPE_TEACHER) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
					array_push($data_member['list'], $list_member);
				}

				array_push($return_data['_data'], $data_relative);
				array_push($return_data['_data'], $data_member);

				$return_data['_msg_code'] = MSG_CODE_TRUE;
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_error_code'] = 0;

			$return_data['_msg_code'] = MSG_CODE_500;
			$return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// Gui thong bao
	public function send(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'message' => 'Send notification error',
			'_data' => []
		);

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$device_id = $request->getHeaderLine('deviceid');

		if (!PsAuthentication::checkDevice($user, $device_id)) {

			return $response->withJson($return_data);
		} elseif ($user->user_type == USER_TYPE_RELATIVE) {

			// Kiem tra tai khoan
			$amount_info = PsMobileAppAmountsModel::checkAmountInfo($user->id);

			if (!$amount_info) {

				$return_data = array(
					'_msg_code' => MSG_CODE_PAYMENT,
					'message' => $psI18n->__('Your account has run out of money. Please recharge to continue using.')
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

			$user_ids = isset($info['user_id']) ? $info['user_id'] : null;

			if (!$title || !$content || count($user_ids) <= 0) {

				return $response->withJson($return_data);
			} else {
				/*
				 * V2 không còn endcode dữ liệu nữa
				 */

				$title = PsEndCode::ps64Decode($title);

				$content = PsEndCode::ps64Decode($content);

				$chk_title = vali::notEmpty()->stringType()->length(1, 150)->validate($title);

				$chk_content = vali::notEmpty()->stringType()->length(1, 10000)->validate($content);

				$boolean_validator = ($chk_title && $chk_content);
			}
		}

		// Kiem tra du lieu push len truoc khi lam tiep
		if ($boolean_validator) {

			try {

				PsCmsNotificationsModel::beginTransaction();

				// them du lieu vao bang PsCmsNotifications
				$private_key = time();

				$curren_datetime = date('Y-m-d H:i:s');



				$newNotification = $this->db->table(TBL_PS_CMS_NOTIFICATIONS)->insertGetId([
					'ps_customer_id' => $user->ps_customer_id,
					'title' => $title,
					'description' => PsString::nl2brChars($content),
					'date_at' => date('Y-m-d H:i:s'),
					'is_status' => 'sent',
					
					'private_key' => $private_key,
					'total_object_received' => count($info['user_id']), // so user_id nhan
					'text_object_received' => implode(",", $info['user_id']), // danh sach user_id nhan
					'user_created_id' => $user->id,
					'created_at' => $curren_datetime,
					'updated_at' => $curren_datetime
				]);


				// gui them 1 thong bao cho chinh nguoi gui
				$newReceived = $this->db->table(TBL_PS_CMS_RECEIVED_NOTIFICATION)->insertGetId([
					'user_id' => $user->id,
					'ps_cms_notification_id' => $newNotification,
					'private_key' => $private_key,
					'user_created_id' => $user->id,
					'created_at' => $curren_datetime,
					'updated_at' => $curren_datetime
				]);

				// them du lieu vao bang PsCmsReceivedNotification:
				foreach ($info['user_id'] as $user_id) {
					$newReceived = $this->db->table(TBL_PS_CMS_RECEIVED_NOTIFICATION)->insertGetId([
						'user_id' => (int) $user_id,
						'ps_cms_notification_id' => $newNotification,
						'private_key' => $private_key,
						'user_created_id' => $user->id,
						'created_at' => $curren_datetime,
						'updated_at' => $curren_datetime
					]);
				}

				$return_data['_msg_code'] = ($newReceived && $newNotification) ? MSG_CODE_TRUE : MSG_CODE_FALSE;

				if ($newReceived && $newNotification) {

					$return_data['_msg_code'] = MSG_CODE_TRUE;
					$return_data['_msg_text'] = $psI18n->__('Send notifications success');

					// BEGIN: Push Nocation
					$users_id = $info['user_id'];

					// Danh sach push
					$users_push = UserModel::getUserByArrayUserId($users_id, false);

					$registrationIds_ios = array();
					$registrationIds_android = array();

					foreach ($users_push as $user_nocation) {
						if ($user_nocation->notification_token != '') {
							if ($user_nocation->osname == 'IOS')
								array_push($registrationIds_ios, $user_nocation->notification_token);
							else
								array_push($registrationIds_android, $user_nocation->notification_token);
						}
					}

					if (count($registrationIds_android) > 0 || count($registrationIds_ios) > 0) {

						// Thong nguoi gui thong bao di
						$user_send = UserModel::getUserByUserKey(PsEndCode::psHash256($user->id));

						$setting = new \stdClass();

						$setting->title = $title;

						if ($user_send->user_type == USER_TYPE_TEACHER) {
							$setting->subTitle   = $psI18n->__('From teacher') . ' ' . $user->first_name . ' ' . $user->last_name;
							$setting->tickerText = $psI18n->__('From teacher') . ' ' . $user->first_name . ' ' . $user->last_name;
						} else {
							$setting->subTitle   = $psI18n->__('From') . ' ' . $user->first_name . ' ' . $user->last_name;
							$setting->tickerText = $psI18n->__('From') . ' ' . $user->first_name . ' ' . $user->last_name;
						}

						$setting->message = PsString::stringTruncate($content, 100, '...');
						$setting->lights = '1';
						$setting->vibrate = '1';
						$setting->sound = '1';
						$setting->smallIcon = IC_SMALL_NOTIFICATION;
						$setting->smallIconOld = 'ic_small_notification_old';

						// $setting->largeIcon = PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;

						$type = ($user_send->user_type == USER_TYPE_TEACHER) ? MEDIA_TYPE_TEACHER : MEDIA_TYPE_RELATIVE;

						if ($user_send->avatar != '')
							$setting->largeIcon = PsString::getUrlMediaAvatar($user_send->cache_data, $user_send->user_year_data, $user_send->avatar, $type);
						else
							$setting->largeIcon = PsString::getUrlLogoPsCustomer($user_send->year_data, $user_send->logo); // Logo truong

						if (!PsFile::urlExists($setting->largeIcon)) {
							$setting->largeIcon = PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
						}

						$setting->screenCode = PS_CONST_SCREEN_CMSNOTIFICATION;
						$setting->itemId     = $newNotification;

						if ($user->username == 'demo03' || $user->username == 'nguyenphuong' || $user->username == 'nguyenvanan') {
							$setting->screenCode = PS_CONST_SCREEN_CMSNOTIFICATION_DETAIL;
						}

						$setting->clickUrl = '';

						// Deviceid registration firebase
						if ($registrationIds_ios > 0) {
							$setting->registrationIds = $registrationIds_ios;

							// $this->WriteLog ( '-- BUOC 8 .1 --');

							$notification = new PsNotification($setting);

							// $this->WriteLog ( '-- BUOC 8 .1.1 --');

							$result = $notification->pushNotification(PS_CONST_PLATFORM_IOS);

							// $this->WriteLog ( '-- BUOC 8 .1.1 --');

							$return_data[PS_CONST_PLATFORM_IOS] = $result;
						}

						if ($registrationIds_android > 0) {

							// $this->WriteLog ( '-- BUOC 8 .2 --');

							$setting->registrationIds = $registrationIds_android;

							$notification = new PsNotification($setting);

							// $this->WriteLog ( '-- BUOC 8 .2.2 --');

							$result = $notification->pushNotification();

							$return_data['Android'] = $result;
						}

						// $this->WriteLog ( '-- BUOC 9 --');
					}
				} else {
					$return_data['_msg_code'] = MSG_CODE_500;
					$return_data['message'] = $psI18n->__('Send notification error');
				}

				// BEGIN: Push Nocation

				PsCmsNotificationsModel::commit();
			} catch (Exception $e) {

				PsCmsNotificationsModel::rollBack();

				$this->logger->err($e->getMessage());

				$return_data['_msg_code'] = MSG_CODE_500;

				$return_data['message'] = $psI18n->__('Send notification error');
			}
		}

		return $response->withJson($return_data);
	}

	// Gui thong bao
	public function sendOLD(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'message' => 'Send notification error',
			'_data' => []
		);

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$device_id = $request->getHeaderLine('deviceid');

		if (!PsAuthentication::checkDevice($user, $device_id)) {

			return $response->withJson($return_data);
		} elseif ($user->user_type == USER_TYPE_RELATIVE) {

			// Kiem tra tai khoan
			$amount_info = PsMobileAppAmountsModel::checkAmountInfo($user->id);

			if (!$amount_info) {

				$return_data = array(
					'_msg_code' => MSG_CODE_PAYMENT,
					'message' => $psI18n->__('Your account has run out of money. Please recharge to continue using.')
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

			$user_ids = isset($info['user_id']) ? $info['user_id'] : null;

			if (!$title || !$content || count($user_ids) <= 0) {

				return $response->withJson($return_data);
			} else {

				$title = PsEndCode::ps64Decode($title);

				$content = PsEndCode::ps64Decode($content);

				$chk_title = vali::notEmpty()->stringType()->length(1, 150)->validate($title);

				$chk_content = vali::notEmpty()->stringType()->length(1, 5000)->validate($content);

				$boolean_validator = ($chk_title && $chk_content);
			}
		}

		// Kiem tra du lieu push len truoc khi lam tiep
		if ($boolean_validator) {

			try {

				PsCmsNotificationsModel::beginTransaction();

				// them du lieu vao bang PsCmsNotifications
				$private_key = time();

				$curren_datetime = date('Y-m-d H:i:s');

				$newNotification = $this->db->table(TBL_PS_CMS_NOTIFICATIONS)->insertGetId([
					'title' => $title,
					'description' => $content,
					'date_at' => date('Y-m-d H:i:s'),
					'is_status' => 'sent',
					'private_key' => $private_key,
					'total_object_received' => count($info['user_id']), // so user_id nhan
					'text_object_received' => implode(",", $info['user_id']), // danh sach user_id nhan
					'user_created_id' => $user->id,
					'created_at' => $curren_datetime,
					'updated_at' => $curren_datetime
				]);

				// gui them 1 thong bao cho chinh nguoi gui
				$newReceived = $this->db->table(TBL_PS_CMS_RECEIVED_NOTIFICATION)->insertGetId([
					'user_id' => $user->id,
					'ps_cms_notification_id' => $newNotification,
					'private_key' => $private_key,
					'user_created_id' => $user->id,
					'created_at' => $curren_datetime,
					'updated_at' => $curren_datetime
				]);

				// them du lieu vao bang PsCmsReceivedNotification:
				foreach ($info['user_id'] as $user_id) {
					$newReceived = $this->db->table(TBL_PS_CMS_RECEIVED_NOTIFICATION)->insertGetId([
						'user_id' => (int) $user_id,
						'ps_cms_notification_id' => $newNotification,
						'private_key' => $private_key,
						'user_created_id' => $user->id,
						'created_at' => $curren_datetime,
						'updated_at' => $curren_datetime
					]);
				}

				$return_data['_msg_code'] = ($newReceived && $newNotification) ? MSG_CODE_TRUE : MSG_CODE_FALSE;

				if ($newReceived && $newNotification) {

					$return_data['_msg_code'] = MSG_CODE_TRUE;
					$return_data['_msg_text'] = $psI18n->__('Send notifications success');

					// BEGIN: Push Nocation
					$users_id = $info['user_id'];
					// Danh sach push
					$users_push = UserModel::getUserByArrayUserId($users_id, false);

					$registrationIds_ios = array();
					$registrationIds_android = array();

					foreach ($users_push as $user_nocation) {
						if ($user_nocation->notification_token != '') {
							if ($user_nocation->osname == 'IOS')
								array_push($registrationIds_ios, $user_nocation->notification_token);
							else
								array_push($registrationIds_android, $user_nocation->notification_token);
						}
					}

					if (count($registrationIds_android) > 0 || count($registrationIds_ios) > 0) {

						$setting = new \stdClass();

						$setting->title = $title;
						$setting->subTitle = $psI18n->__('From') . ' ' . $user->first_name . ' ' . $user->last_name;

						$setting->message = PsString::stringTruncate($content, 100, '...');
						$setting->tickerText = $psI18n->__('From') . ' ' . $user->first_name . ' ' . $user->last_name;
						$setting->lights = '1';
						$setting->vibrate = '1';
						$setting->sound = '1';
						$setting->smallIcon = IC_SMALL_NOTIFICATION;
						// $setting->smallIconOld = 'ic_small_notification_old';

						$setting->largeIcon = 'http://truongnet.com/web/images/newwaytech_logo_app.png';
						$setting->screenCode = '006';
						$setting->itemId = '0';
						$setting->clickUrl = '';

						// Deviceid registration firebase
						if ($registrationIds_ios > 0) {
							$setting->registrationIds = $registrationIds_ios;

							$notification = new PsNotification($setting);
							$result = $notification->pushNotification(PS_CONST_PLATFORM_IOS);

							$return_data[PS_CONST_PLATFORM_IOS] = $result;
						}

						if ($registrationIds_android > 0) {
							$setting->registrationIds = $registrationIds_android;

							$notification = new PsNotification($setting);
							$result = $notification->pushNotification();

							$return_data['Android'] = $result;
						}
					}
				} else {
					$return_data['_msg_code'] = MSG_CODE_500;
					$return_data['message'] = $psI18n->__('Send notification error');
				}

				// BEGIN: Push Nocation

				PsCmsNotificationsModel::commit();
			} catch (Exception $e) {

				PsCmsNotificationsModel::rollBack();

				$this->logger->err($e->getMessage());

				$return_data['_msg_code'] = MSG_CODE_500;

				$return_data['message'] = $psI18n->__('Send notification error');
			}
		}
		return $response->withJson($return_data);
	}

	public function delete(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$this->WriteLog('list send');

		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_data' => []
		);

		$user 		= $this->user_token;

		$code_lang 	= $this->getUserLanguage($user);

		$psI18n 	= new PsI18n($code_lang);

		$device_id 	= $request->getHeaderLine('deviceid');

		$notification_id = $args['n_id'];

		try {
			/*
                 * if ($ntype == 'received') {
                 * $received_notification = $this->db->table(TBL_PS_CMS_RECEIVED_NOTIFICATION)
                 * ->where('ps_cms_notification_id', $notification_id)
                 * ->where('user_id', $user->id)
                 * ->update([
                 * 'is_delete' => 1
                 * ]);
                 * } else
                 * if ($ntype == 'sent') {
                 * $notification = $this->db->table(TBL_PS_CMS_RECEIVED_NOTIFICATION)
                 * ->where('ps_cms_notification_id', $notification_id)
                 * ->where('user_id', $user->id)
                 * ->delete();
                 * }
                 */

			if (!PsAuthentication::checkDevice($user, $device_id)) {
				return $response->withJson($return_data);
			}

			$notification = $this->db->table(TBL_PS_CMS_RECEIVED_NOTIFICATION)
				->where('ps_cms_notification_id', $notification_id)
				->where('user_id', $user->id)
				->delete();

			$return_data['_msg_code'] = ($notification) ? MSG_CODE_TRUE : MSG_CODE_FALSE;
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_error_code'] = 0;

			$return_data['_msg_code'] 	= MSG_CODE_500;

			$return_data['message'] 	= $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}
}
