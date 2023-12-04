<?php
namespace Api\PsCmsChat\Controller;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Exception;
use App\Authentication\PsAuthentication;
use App\Controller\BaseController;
use Api\Users\Model\UserModel;
use Api\Relatives\Model\RelativeModel;
use Api\Students\Model\StudentModel;
use Api\PsMembers\Model\PsMemberModel;

use App\Model\PsWorkPlacesModel;

use App\PsUtil\PsString;
use App\PsUtil\PsI18n;
use App\PsUtil\PsEndCode;
use App\PsUtil\PsNotification;
use App\PsUtil\PsFile;


class CmsChatController extends BaseController {

	public $container;

	protected $user_token;

	public function __construct(LoggerInterface $logger, $container, $app) {

		parent::__construct ( $logger, $container );
		
		$this->user_token = $app->user_token;
		
	}

	// Lay danh sach nguoi chat (dành cho app phu huynh )
	public function listUserSendForRelative(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => [ ] 
		);
		
		// get data from URI
		$user 		 = $this->user_token;
		
		$code_lang   = $this->getUserLanguage ( $user );
		
		$psI18n 	= new PsI18n ( $code_lang );
		
		$device_id  = $request->getHeaderLine ( 'deviceid' );
		
		$student_id = $args ['student_id'];
		
		try {
			
			if (! PsAuthentication::checkDeviceUserRelative ( $user, $device_id )) {
				return $response->withJson ( $return_data );
			}
			
			$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );
			
			if ($ps_student) {
				
				// Lay thong tin co so cua hoc sinh				
				//$ps_work_places  = PsWorkPlacesModel::findById($ps_student->ps_workplace_id);
				
				$ps_work_places  = PsWorkPlacesModel::getColumnById($ps_student->ps_workplace_id, 'config_chat_relative_to_teacher,config_time_chat_relative_to_teacher,config_chat_relative_to_relative');
				
				// Quyền Nguoi than chat với giáo viên
				$role_chat_relative_to_teacher = false;
				
				// config_chat_relative_to_relative: Trò chuyện với nhau				
				$role_chat_chat_relative_to_relative = false;
				
				if ($ps_work_places) {
					
					$role_chat_chat_relative_to_relative = $ps_work_places->config_chat_relative_to_relative;
					
					if ($ps_work_places->config_chat_relative_to_teacher == STATUS_ACTIVE) {
						
						$current_date_time = strtotime(date("Y-m-d H:i"), time());
						
						$config_time_chat_relative_to_teacher   = 	$ps_work_places->config_time_chat_relative_to_teacher;
						
						$from_date_time    = strtotime(date("Y-m-d H:i" ,strtotime(date('Y-m-d').' '.$config_time_chat_relative_to_teacher)));
						
						if($config_time_chat_relative_to_teacher == "00:00:00"){ // neu khong cau hinh thi gui thong bao
		                    
		            		$role_chat_relative_to_teacher = true;
		            		
		            	} elseif ($current_date_time >= $from_date_time) {
							
		            		$role_chat_relative_to_teacher = true;
		            		
						}
					}					
				}
				
				$date_at = date("Y-m-d");
				
				$data_relative = $list_service = array ();
								
				// Danh sach giao vien cua lớp
				//$role_chat_relative_to_teacher = true;
				
				$data_member = array ();
					
				$data_member ['title'] = $psI18n->__ ( 'Teacher' );
				$data_member ['list']  = array ();
				
				$data_relative ['title'] = $psI18n->__ ( 'Relatives in class' );				
				$data_relative ['list']  = array ();
				
				if ($role_chat_relative_to_teacher) {
					
					$list_user = $this->db->table ( CONST_TBL_USER . ' as U' )
					->leftjoin ( CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'U.member_id' )
					->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'U.ps_customer_id' )
					->leftJoin ( TBL_PS_TEACHER_CLASS . ' as TC', function ($q) use ($date_at) {
						$q->on ( 'TC.ps_member_id', '=', 'M.id' )
						->where ( 'TC.is_activated', STATUS_ACTIVE )
						->whereDate ( 'TC.start_at', '<=', $date_at )
						//->whereDate ( 'TC.stop_at', '>=', date ( 'Y-m-d' ) );
						->whereRaw('(DATE_FORMAT(TC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $date_at . '", "%Y%m%d") OR TC.stop_at IS NULL )');
					} )
					->leftjoin ( TBL_PS_SERVICE_COURSES . ' as SC', 'SC.ps_member_id', '=', 'M.id' )
					->select ( 'U.id as user_id', 'U.user_type', 'M.avatar as avatar', 'M.year_data', 'C.cache_data' )
					->selectRaw ( 'CONCAT(U.first_name," ", U.last_name) AS fullname' )
					->where ( 'U.ps_customer_id', $user->ps_customer_id )
					->where ( function ($query) use ($ps_student, $list_service) {
						$query->where ( 'TC.ps_myclass_id', $ps_student->class_id )->orwhereIn ( 'SC.ps_service_id', $list_service );
					} )
					->where ( 'U.user_type', USER_TYPE_TEACHER )
					->where ( 'U.notification_token','!=' ,"")
					->where ( 'U.is_active', STATUS_ACTIVE )->distinct ()->get ();
					
					// Danh sách giáo viên
					foreach ( $list_user as $_user ) {
						
						$list_member = array ();
						
						$list_member ['user_key']     = PsEndCode::psHash256 ( $_user->user_id);
						$list_member ['full_name']    = $_user->fullname;
						$list_member ['student_id']   = 0;
						
						//$list_member ['avatar_url']   = ($_user->avatar != '') ? PsString::getUrlMediaAvatar ( $_user->cache_data, $_user->year_data, $_user->avatar, MEDIA_TYPE_TEACHER ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
						
						if ($_user->avatar != '') {							
							$avatar_url = PsString::getUrlMediaAvatar ( $_user->cache_data, $_user->year_data, $_user->avatar, MEDIA_TYPE_TEACHER );
							//$avatar_url = PsFile::urlExists($avatar_url) ? $avatar_url : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;							
						} else {
							$avatar_url = PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
						}
						
						$list_member ['avatar_url']   = $avatar_url;
						
						array_push ( $data_member ['list'], $list_member );
					}
				}
				
				array_push ( $return_data ['_data'], $data_member );
				
				if ($role_chat_chat_relative_to_relative) {
				
					// Lay danh sach Mon hoc(dich vu) ma hoc sinh dang ky
					$services = $this->db->table ( TBL_PS_SERVICE_COURSES . ' as SC' )
					->join ( CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'SC.ps_service_id' )
					->join ( CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.ps_service_course_id', '=', 'SC.id' )
					->selectRaw ( 'S.id' )->where ( 'SS.student_id', $student_id )
					->whereNull ( 'SS.delete_at' )
					->whereDate ( 'SC.start_at', '<=', date ( 'Y-m-d' ) )->whereDate ( 'SC.end_at', '>=', date ( 'Y-m-d' ) )
					->where ( 'S.ps_customer_id', $ps_student->ps_customer_id )
					->where ( 'SC.is_activated', STATUS_ACTIVE )
					->where ( 'S.enable_schedule', STATUS_ACTIVE )
					->where ( 'S.is_activated', STATUS_ACTIVE )->distinct ()->get ();
					
					foreach ( $services as $service ) {
						array_push ( $list_service, $service->id );
					}
					
					// Danh sach nguoi than
					$list_user = $this->db->table ( CONST_TBL_USER . ' as U' )
					->join ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=', 'U.member_id' )
					->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'U.ps_customer_id' )
					->join ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', 'R.id' )
					->join ( CONST_TBL_STUDENT . ' as S', 'RS.student_id', '=', 'S.id' )
					->leftjoin ( CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id' )
					
					->join(CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($date_at) {
		            	$q->on('SC.student_id', '=', 'S.id')
		                ->where('SC.is_activated', STATUS_ACTIVE)
		                ->whereIn ( 'SC.type', [ 
										STUDENT_HT,
										STUDENT_CT 
								] )
		                ->whereDate('SC.start_at', '<=', $date_at)
		                ->whereRaw('(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $date_at . '", "%Y%m%d") OR SC.stop_at IS NULL )');
		            })				
					
					->leftJoin ( CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.student_id', 'S.id' )
					->select ( 'U.id as user_id', 'U.user_type', 'R.last_name as r_last_name','R.avatar as avatar', 'R.year_data', 'C.cache_data', 'RE.title AS re_title')
					->selectRaw ( 'CONCAT(U.first_name," ", U.last_name) AS fullname , CONCAT(S.first_name," ",S.last_name) AS student_name, S.id as student_id' )
					->where ( 'U.ps_customer_id', $user->ps_customer_id )
					->where ( 'U.user_type', USER_TYPE_RELATIVE )
					->where ( 'U.is_active', STATUS_ACTIVE )
					->where ( 'U.id', '!=', $user->id )
					//->where ( 'U.notification_token','!=' ,"")
					//->where ( 'RS.is_parent_main', STATUS_ACTIVE )
						->where ( 'U.app_device_id','!=' ,"")
					->whereRaw ( 'S.deleted_at IS NULL' )
					->where ( function ($query) use ($ps_student, $list_service) {
						$query->where ( 'SC.myclass_id', $ps_student->class_id )->orwhereIn ( 'SS.service_id', $list_service );
					} )->groupby('U.id')->distinct ()->get ();
					
					// Danh sách người thân của bé trong lớp
					foreach ( $list_user as $_user ) {
						
						$list_relative = array ();
						
						$list_relative ['user_key'] = PsEndCode::psHash256 ( $_user->user_id );
	
						$list_relative ['full_name'] = (string)$_user->re_title.' '.$psI18n->__ ( 'baby' ).' '.(string)$_user->student_name;
						//$list_relative ['full_name'] = (string)$_user->r_last_name.', '.(string)$_user->re_title.' '.$psI18n->__ ( 'baby' ).' '.(string)$_user->student_name;
	
						//$list_relative ['avatar_url'] = ($_user->avatar != '') ? PsString::getUrlMediaAvatar ( $_user->cache_data, $_user->year_data, $_user->avatar, MEDIA_TYPE_RELATIVE ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
						
						$list_relative ['student_id']   = (int)$_user->student_id;
						
						if ($_user->avatar != '') {
							$avatar_url = PsString::getUrlMediaAvatar ( $_user->cache_data, $_user->year_data, $_user->avatar, MEDIA_TYPE_RELATIVE );
							//$avatar_url = PsFile::urlExists($avatar_url) ? $avatar_url : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
						} else {
							$avatar_url = PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
						}
						
						$list_relative ['avatar_url']   = $avatar_url;
						
						
						array_push ( $data_relative ['list'], $list_relative );
					}					
				}
				
				array_push ( $return_data ['_data'], $data_relative );
				
				$return_data ['_msg_code'] = MSG_CODE_TRUE;				
								
			}
		
		} catch ( Exception $e ) {
			
			$return_data ['_msg_code'] = MSG_CODE_500;
			
			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );			
		}
		
		return $response->withJson ( $return_data );
	}

	// lay danh sach chat (danh cho app cho giao vien)
	public function listUserSendForTeacher(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => [ ] 
		);
		
		// get data from URI
		//global $user_login;
		//$user = $user_login;
		
		$user 		 = $this->user_token;
		
		$psI18n = new PsI18n ( $this->getUserLanguage ( $user ) );
		
		if ($user && ($user->user_type == USER_TYPE_TEACHER)) {
			
			try {
				
				$ps_member = PsMemberModel::getMember ( $user->member_id );
				
				$role_chat = false;
				
				if ($ps_member) {
					// Lay thong tin co so cua hoc sinh				
					//$ps_work_places  = PsWorkPlacesModel::findById($ps_member->ps_workplace_id);
					
					$ps_work_places  = PsWorkPlacesModel::getColumnById($ps_member->ps_workplace_id, 'config_chat_relative_to_teacher,config_time_chat_relative_to_teacher');
					
					
					if ($ps_work_places) {
						
						if ($ps_work_places->config_chat_relative_to_teacher == STATUS_ACTIVE) {
							
							$current_date_time = strtotime(date("Y-m-d H:i"), time());
							
							$config_time_chat_relative_to_teacher   = 	$ps_work_places->config_time_chat_relative_to_teacher;
							
							$from_date_time    = strtotime(date("Y-m-d H:i" ,strtotime(date('Y-m-d').' '.$config_time_chat_relative_to_teacher)));
							
							if($config_time_chat_relative_to_teacher == "00:00:00"){ // neu khong cau hinh thi gui thong bao
			                    
			            		$role_chat = true;
			                
			            	} elseif ($current_date_time >= $from_date_time) {
								$role_chat = true;
							}
						}						
					}				
				}
				
				$date_at = date("Y-m-d");				
				
				$data_relative = $list_service = $list_class = array ();			
				
				//$role_chat = true;
				
				$data_relative ['title'] = $psI18n->__ ( 'Relatives in class' );
				
				$data_relative ['list']  = array ();
				
				// Danh sach giao vien cua lop
				$data_member = array ();
				
				$data_member ['title'] = $psI18n->__ ( 'Teacher' );
				$data_member ['list']  = array ();
				
				// lay danh sach dich vu ma giao vien dang ky day
				$services = $this->db->table ( CONST_TBL_SERVICE . ' as S' )
				->select ( 'S.id' )
				->join ( TBL_PS_SERVICE_COURSES . ' as SC', 'SC.ps_service_id', '=', 'S.id' )
				->whereDate ( 'SC.start_at', '<=', date ( 'Y-m-d' ) )
				->whereDate ( 'SC.end_at', '>=', date ( 'Y-m-d' ) )
				->where ( 'S.ps_customer_id', $user->ps_customer_id )
				->where ( 'SC.is_activated', STATUS_ACTIVE )
				->where ( 'S.enable_schedule', STATUS_ACTIVE )
				->where ( 'SC.ps_member_id', $user->member_id )
				->where ( 'S.is_activated', STATUS_ACTIVE )->distinct ()->get ();
				foreach ( $services as $service ) {
					array_push ( $list_service, $service->id );
				}
				
				// lay danh sach lop ma giao vien day
				$my_class = $this->db->table ( CONST_TBL_MYCLASS . ' as MC' )				
				->select ( 'MC.id' )
				
				//->join ( TBL_PS_TEACHER_CLASS . ' as TC', 'TC.ps_myclass_id', '=', 'MC.id' )
				
				->join(TBL_PS_TEACHER_CLASS . ' as TC', function ($q) use ($date_at) {
					$q->on('TC.ps_myclass_id', '=', 'MC.id')
					->where('TC.is_activated', STATUS_ACTIVE)
					->whereDate('TC.start_at', '<=', $date_at)
					->whereRaw('(DATE_FORMAT(TC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $date_at . '", "%Y%m%d") OR TC.stop_at IS NULL )');
				})
				
				->leftJoin(TBL_PS_SCHOOL_YEAR . ' as SY', function ($q) use ($date_at) {
            
					$q->on('SY.id', '=', 'MC.school_year_id')
	                ->whereDate('SY.from_date', '<=', $date_at)
	                ->whereDate('SY.to_date', '>', $date_at)
					->where('SY.is_default', '=', STATUS_ACTIVE);
	            })
	            /*
				->whereDate ( 'TC.start_at', '<=', date ( 'Y-m-d' ) )
				->whereRaw('(DATE_FORMAT(TC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . date ( 'Y-m-d' . '", "%Y%m%d") OR TC.stop_at IS NULL )')*/
				
				->where ( 'MC.ps_customer_id', $user->ps_customer_id )
				->where ( 'TC.is_activated', STATUS_ACTIVE )
				->where ( 'MC.is_activated', STATUS_ACTIVE )
				->where('SY.is_default', '=', STATUS_ACTIVE)
				->where ( 'TC.ps_member_id', $user->member_id )->distinct ()->get ();
				
				foreach ( $my_class as $class ) {
					array_push ( $list_class, $class->id );
				}
				
				//$return_data ['list_class'] = $list_class;
				
				if ($role_chat) {
					
					// Danh sach nguoi than cua hoc sinh
					$list_user_relative = $this->db->table ( CONST_TBL_USER . ' as U' )
					->join ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=', 'U.member_id' )
					->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'U.ps_customer_id' )
					->join ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', 'R.id' )
					->join ( CONST_TBL_STUDENT . ' as S', 'RS.student_id', '=', 'S.id' )
					
					->join ( CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id' )
					//->leftjoin ( CONST_TBL_STUDENTCLASS . ' as SC', 'SC.student_id', '=', 'S.id' )
					
					->join(CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($date_at) {
		            	$q->on('SC.student_id', '=', 'S.id')
		                ->where('SC.is_activated', STATUS_ACTIVE)
		                ->whereIn ( 'SC.type', [ 
										STUDENT_HT,
										STUDENT_CT 
								] )
		                ->whereDate('SC.start_at', '<=', $date_at)
		                ->whereRaw('(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $date_at . '", "%Y%m%d") OR SC.stop_at IS NULL )');
		            })
					
					->leftJoin ( CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.student_id', 'S.id' )
					->select ( 'U.id as user_id', 'U.user_type', 'R.avatar as avatar', 'R.year_data', 'C.cache_data','RE.title AS re_title' )
					->selectRaw ( 'CONCAT(U.first_name," ", U.last_name) AS fullname, CONCAT(S.first_name," ",S.last_name) AS student_name, S.id as student_id')
					->where ( 'U.ps_customer_id', $user->ps_customer_id )
					->where ( 'U.user_type', USER_TYPE_RELATIVE )
					->where ( 'U.is_active', STATUS_ACTIVE )
					//->where ( 'U.notification_token','!=' ,"")					
					->where ( 'U.app_device_id','!=' ,"")
					//->where ( 'RS.is_parent_main', STATUS_ACTIVE )
					->whereRaw ( 'S.deleted_at IS NULL' )
					->where ( function ($query) use ($list_class, $list_service) {
						$query->whereIn ( 'SC.myclass_id', $list_class )->orwhereIn ( 'SS.service_id', $list_service );
					} )->groupby('U.id')->distinct ()->get ();
					
					foreach ( $list_user_relative as $_user ) {
						
						$list_relative = array ();
						
						$list_relative ['user_key'] = PsEndCode::psHash256 ( $_user->user_id );
											
						//$list_relative ['info'] = $_user->re_title.' '.$psI18n->__ ( 'baby' ).' '.$_user->student_name;
	
						//$list_relative ['full_name'] = $_user->fullname;
	
						//$list_relative ['full_name'] = $_user->fullname.', '.$_user->re_title.' '.$psI18n->__ ( 'baby' ).' '.$_user->student_name;
						
						$list_relative ['full_name'] = (string)$_user->re_title.' '.$psI18n->__ ( 'baby' ).' '.(string)$_user->student_name;
						
						$list_relative ['student_id']   = (int)$_user->student_id;
	
						//$list_relative ['avatar_url'] = ($_user->avatar != '') ? PsString::getUrlMediaAvatar ( $_user->cache_data, $_user->year_data, $_user->avatar, MEDIA_TYPE_RELATIVE ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
						
						if ($_user->avatar != '') {
							$avatar_url = PsString::getUrlMediaAvatar ( $_user->cache_data, $_user->year_data, $_user->avatar, MEDIA_TYPE_RELATIVE );
							//$avatar_url = PsFile::urlExists($avatar_url) ? $avatar_url : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
						} else {
							$avatar_url = PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
						}
						
						$list_relative ['avatar_url']   = $avatar_url;
						/*
						if ($user->username == 'demo03' && $i == 0) {
							$list_relative ['avatar_url']   = 'https://apis.kidsschool.vn/vxxx/';
						}
						$i++;
						*/
						array_push ( $data_relative ['list'], $list_relative );
					}
				}
				
				array_push ( $return_data ['_data'], $data_relative );
				
				// Danh sach giáo viên
				$list_user_teacher = $this->db->table ( CONST_TBL_USER . ' as U' )
				->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'U.ps_customer_id' )
				
				->join( CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'U.member_id' )
				
				->join ( TBL_PS_TEACHER_CLASS . ' as TC', function ($q) {
					$q->on ( 'TC.ps_member_id', '=', 'M.id' )
					->where ( 'TC.is_activated', STATUS_ACTIVE )
					->whereDate ( 'TC.start_at', '<=', date ( 'Y-m-d' ) )
					->whereDate ( 'TC.stop_at', '>=', date ( 'Y-m-d' ) );
				} )
				
				->select ( 'U.id as user_id', 'U.user_type', 'M.avatar as avatar', 'M.year_data', 'C.cache_data' )
				->selectRaw ( 'CONCAT(U.first_name," ", U.last_name) AS fullname' )
				->where ( 'U.ps_customer_id', $user->ps_customer_id )
				->where ( 'U.user_type', USER_TYPE_TEACHER )
				->where ( 'U.is_active', STATUS_ACTIVE )
				->whereIn ( 'TC.ps_myclass_id', $list_class )
				->where ( 'U.id', '!=', $user->id )->distinct ()->get ();
				
				foreach ( $list_user_teacher as $_user ) {
					
					$list_member = array ();
					
					$list_member ['user_key'] = PsEndCode::psHash256 ( $_user->user_id );
					$list_member ['full_name'] = $_user->fullname;
					
					$list_member ['student_id'] = 0;
					
					//$list_member ['avatar_url'] = ($_user->avatar != '') ? PsString::getUrlMediaAvatar ( $_user->cache_data, $_user->year_data, $_user->avatar, MEDIA_TYPE_TEACHER ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
					
					if ($_user->avatar != '') {
						$avatar_url = PsString::getUrlMediaAvatar ( $_user->cache_data, $_user->year_data, $_user->avatar, MEDIA_TYPE_TEACHER );
						//$avatar_url = PsFile::urlExists($avatar_url) ? $avatar_url : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
					} else {
						$avatar_url = PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
					}
					
					$list_member ['avatar_url']   = $avatar_url;
					
					array_push ( $data_member ['list'], $list_member );
				}
				
				array_push ( $return_data ['_data'], $data_member );
				
				$return_data ['_msg_code'] = MSG_CODE_TRUE;
			
			} catch ( Exception $e ) {
				
				$this->logger->err ( $e->getMessage () );
				
				$return_data ['_error_code'] = 0;
				
				$return_data ['_msg_code'] = MSG_CODE_FALSE;
			}
		}
		
		return $response->withJson ( $return_data );
	}

	// push notification to member chat
	public function pushNotificationChat(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_msg_text' => 'System error',
				'_data' => [ ] 
		);
		
		// get data from URI
		$user = $this->user_token;
		
		if ($user) {
			
			$body = $request->getParsedBody ();
			
			$user_key = isset ( $body ['user_key'] ) ? $body ['user_key'] : '';
			$content  = isset ( $body ['content'] ) ? $body ['content'] : '';
			$student_id = isset ( $body ['student_id'] ) ? $body ['student_id'] : 0;
			
			/*
			$this->WriteLog ( '--BEGIN: NOI DUNG CHAT--' );
			
			$this->WriteLog ( 'UserKey:'. $user_key );
			
			$this->WriteLog ( 'Content:'. $content );
			
			//$this->WriteLog ( 'Received fullname:'. $user_received_fullname );
			
			$this->WriteLog ( '--END: NOI DUNG CHAT--' );
			
			*/
			
			if ($user_key != '') {
				
				// Nguoi nhan
				$user_received = UserModel::getUserByUserKey ($user_key);
				
				$return_data ['user_received'] = $user_received;
				
				if ($user_received) {
					
					// Nguoi gui
					$user_send = UserModel::getUserByUserKey ($user->user_key);
					
					$psI18n = new PsI18n ( $this->getUserLanguage ( $user_send ) );
					
					// Push notication
					$setting = new \stdClass ();
					
					$setting->title 		= $psI18n->__ ( 'Message from' ) . " " . $user->first_name . " " . $user->last_name;
					$setting->subTitle 	 	= $psI18n->__ ( 'Message - KidsSchool.vn' );
					$setting->message 	 	= $content;
					$setting->tickerText 	= $psI18n->__ ( 'Message - KidsSchool.vn' );
					$setting->lights 		= 1;
					$setting->vibrate 		= 1;
					$setting->sound 		= 1;
					
					$setting->smallIcon = IC_SMALL_NOTIFICATION;
					$setting->smallIconOld = 'ic_small_notification_old';
					
					$type = ($user_send->user_type == USER_TYPE_TEACHER) ? MEDIA_TYPE_TEACHER : MEDIA_TYPE_RELATIVE;
					
					if ($user_send->avatar != '') {
						
						$setting->largeIcon = PsString::getUrlMediaAvatar ( $user_send->cache_data, $user_send->user_year_data, $user_send->avatar, $type );					
						$setting->largeIcon = PsFile::urlExists($setting->largeIcon) ? $setting->largeIcon : PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
						
					} else {
						//$setting->largeIcon = PsString::getUrlLogoPsCustomer ( $user_send->year_data, $user_send->logo );// Logo truong
						$setting->largeIcon = PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
					}
					$setting->screenCode = PS_CONST_SCREEN_CHAT;
					$setting->itemId     = '0';
					
					$setting->clickUrl 	 = '';
					
					//$setting->userKey    = PsEndCode::psHash256 ($user->id);// userKey cua nguoi gui tin nhan di
					
					$setting->userKey = $user->user_key;
					
					$setting->userUrlAvatar = $setting->largeIcon;
					
					// Neu nguoi gui la Nguoi than hoc sinh
					if ($user_send->user_type == USER_TYPE_RELATIVE) {
						$setting->studentId     = 0;
						$relative =	RelativeModel::getRelativeShortOfStudent($user_send->member_id, $student_id);
						$setting->userFullName = $relative->relationship.$psI18n->__ ( 'baby') .$relative->student_fullname;
					} else {
						$setting->userFullName  =  $user->first_name.' '.$user->last_name;						
						$setting->studentId     =  $student_id;
					}
					
					// Deviceid registration firebase
					$setting->registrationIds = array (
						$user_received->notification_token 
					);
					
					$notification = new PsNotification ( $setting );
					
					$result = $notification->pushNotification ( $user_received->osname);
					
					
					$return_data = array(
							'_msg_code' 		=> MSG_CODE_TRUE,
							'_msg_text' 		=> 'OK',
							'result'			=> $result
							//'_data'				=> $user_received,
							//'notification'		=> $setting,
							//'user_send'			=> $user_send,
							//'user_received'		=> $user_received,
							//'user_key_nhan'		=> $user_key,
							
					);					
				}
			}
		}		
		return $response->withJson($return_data);	
	}
}



