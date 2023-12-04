<?php

namespace Api\Users\Controller;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Exception;
use Respect\Validation\Validator as vali;
use App\PsValidator\PsPasswordValidation;
use App\Controller\BaseController;
use App\Authentication\PsAuthentication;
use Api\Users\Model\UserModel;
use Api\Students\Model\StudentModel;
use Api\Relatives\Model\RelativeModel;
use Api\Albums\Model\AlbumModel;
use Api\PsMembers\Model\PsMemberModel;
use Api\PsMembers\Model\PsRelativeStudentModel;
use App\Model\PsCustomerModel;
use App\Model\PsMobileAppAmountsModel;
use App\Model\PsMobileAppsModel;
use App\Model\PsForgotPasswordModel;
use App\Model\PsEmailModel;
use App\Model\PsSystemCmsContentModel;
use App\PsUtil\PsEndCode;
use App\PsUtil\PsFile;
use App\PsUtil\PsString;
use App\PsUtil\PsCountry;
use App\PsUtil\PsDateTime;
use App\PsUtil\PsI18n;
//use Raulr\GooglePlayScraper\Scraper;

class UserController extends BaseController
{

	public $salt = PS_API_SALT;

	protected $user_token;

	public function __construct(LoggerInterface $logger, $container, $app)
	{

		parent::__construct($logger, $container);

		if (isset($app->user_token))
			$this->user_token = $app->user_token;
	}
	
	// Update avatar
	public function uploadAvatar(RequestInterface $request, ResponseInterface $response, array $args) {
		
		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array();

		$return_data['_msg_code'] = MSG_CODE_FALSE;
		
		//return $api_token->user_type;
		try {
			
			$body = $request->getParsedBody();
			
			$avatar_type = isset ( $body ['avatar_type'] ) ? $body ['avatar_type'] : '';
			
			// get data from body
			$files = $request->getUploadedFiles ();
			
			$check_file = false;
			
			if (empty ( $files ['avatar'] )) {
				$return_data ['_error_code'] = 1;
				$return_data ['_msg_code'] = MSG_CODE_FALSE;
			} else {
				
				$new_avatar = $files ['avatar'];
				
				// validate type and size
				if ($new_avatar->getError () === UPLOAD_ERR_OK) {
					
					$new_avatar_name = $new_avatar->getClientFilename ();
					$new_avatar_size = $new_avatar->getSize ();
					
					$fileInfo = new \SplFileInfo ( $new_avatar->file );
					
					$check1 = vali::size ( null, SIZE_FILE_AVATAR )->validate ($fileInfo);
					
					$check2 = vali::image ()->validate ( $new_avatar->file );
					
					$_error_info = array (
							'_error_size' => (int) !$check1,
							'_error_mimetype' => (int) !$check2
					);
					
					$return_data ['_error_info'] = $_error_info;
					
					$check_file = ($check1 && $check2);
					
				}
				
				if ($check_file && $user->user_type == USER_TYPE_RELATIVE) { // Neu nguoi dung la phu huynh
				
					// Lay thong tin User nguoi than
					$relative = $this->db->table ( CONST_TBL_RELATIVE . ' as R' )->select ( 'R.id', 'R.image', 'C.school_code' )->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'R.ps_customer_id' )->where ( 'R.id', $user->member_id )->first ();
					
					//return $response->withJson ( CONST_TBL_RELATIVE );
					
					if (! $relative) {
						$return_data = array (
								'_error_code' 	=> 1,
								'_msg_code' 	=> MSG_CODE_FALSE
						);
					} else {
						$return_data = array (
								'_error_code' 	=> 0,
								'_msg_code' 	=> MSG_CODE_TRUE
						);
						
						if ($avatar_type == 'student') {
							
							$student_id = isset ( $body ['student_id'] ) ? $body ['student_id'] : '';
							
							if ($student_id > 0) {
								
								
								$new_file_name = 'avatar_'.time () . '.' . PsString::getFileType ( $new_avatar_name );
								
								$student = StudentModel::where ( 'id', $student_id )->first ();
								
								if ($student) {
									
									$ps_customer_id = $student->ps_customer_id;
									
									$school_code = 'PSM'.PsString::renderCode("%05s", $ps_customer_id);
									
									$uploadPath = PS_CONST_PATH_UPLOAD_FILE . $school_code . '/avatar';
									
									if (!is_dir($uploadPath))
									mkdir($uploadPath, 0777, true);
									chmod($uploadPath, 0777); 
									
									$new_avatar->moveTo ($uploadPath. '/' . $new_file_name);
									
									// delete avatar old: $student->avatar
									if (PsFile::isCheckFile($uploadPath.'/' . $student->avatar)) {
										//return "AAAAAaA";
										if(PsFile::deleteFile ($uploadPath.'/' . $student->avatar)) {
											
											$student->avatar            		= $new_file_name;
											if ($student->save()) {
												$return_data ['_msg_code']  	= MSG_CODE_TRUE;
												$return_data ['_error_code'] 	= 0;
												
												$user_info->avatar_url 			= PsString::xemAnhDaiDien('avatar', $new_file_name, $ps_customer_id, $api_token);
												
												$return_data['_data'] ['user_info'] = $user_info;
												
												
											} else {
												$return_data ['_msg_code']  	= MSG_CODE_FALSE;
											}
										}
									} else {
										
										$student->avatar            		= $new_file_name;
										
										if ($student->save()) {
											$return_data ['_msg_code']  	= MSG_CODE_TRUE;
											$return_data ['_error_code'] 	= 0;
											
											$user_info->avatar_url 			= PsString::xemAnhDaiDien('avatar', $new_file_name, $ps_customer_id, $api_token);
											
											$return_data['_data'] ['user_info'] = $user_info;
											
										} else {
											$return_data ['_msg_code']  	= MSG_CODE_FALSE;
										}
									}
									
								}
							}
						}else if ($avatar_type == 'relative') {
							
							// Thiết lập cập nhật ảnh của người thân
							
						}
						
					}
					
				}else if ($check_file && $user->user_type == USER_TYPE_TEACHER){
					
					// Lay thong tin User - Member
					//$ps_member = $this->db->table ( CONST_TBL_PS_MEMBER . ' as M' )->select ( 'M.id', 'M.image', 'C.school_code' )->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'M.ps_customer_id' )->where ( 'M.id', (int)$user->member_id)->first ();
					
					$ps_member = PsMemberModel::where ('id', $user->member_id )->first ();
					
					if ($ps_member) {
						
						$school_code 	= $ps_member->ps_customer_id;
						
						$new_file_name 	= 'avatar_'.time () . '.' . PsString::getFileType ($new_avatar_name );
						
						$uploadPath = PS_CONST_PATH_UPLOAD_FILE . $school_code . '/teacher';
						
						if (!is_dir($uploadPath))
						mkdir($uploadPath, 0777, true);
						chmod($uploadPath, 0777); 
						
						$new_avatar->moveTo ($uploadPath.'/' . $new_file_name);
						
						//$ps_member = PsMemberModel::where ('id', $user->member_id )->first ();
						
						// delete avatar old: $ps_member->image
						if (PsFile::isCheckFile($uploadPath. '/' . $ps_member->image)) {
							
							if(PsFile::deleteFile ($uploadPath . '/' . $ps_member->image)) {
								
								$ps_member->avatar            		= $new_file_name;
								//$ps_member->image            		= $new_file_name;
								
								if ($ps_member->save()) {
									
									$return_data ['_msg_code']  	= MSG_CODE_TRUE;
									$return_data ['_error_code'] 	= 0;
									
									$user_info->avatar_url 			= PsString::generateUrlImage('teacher', $new_file_name, $ps_customer_id, $api_token);
									
									$return_data['_data'] ['user_info'] = $user_info;
									
								} else {
									$return_data ['_msg_code']  	= MSG_CODE_FALSE;
								}
							}
						} else {
							
							$ps_member->avatar            			= $new_file_name;
							
							if ($ps_member->save()) {
								
								$return_data ['_msg_code']  		= MSG_CODE_TRUE;
								$return_data ['_error_code'] 		= 0;
								$user_info->avatar_url 				= PsString::generateUrlImage('teacher', $new_file_name, $ps_member->ps_customer_id, $api_token);
								
								$return_data['_data'] ['user_info'] = $user_info;
								
							} else {
								$return_data ['_msg_code']  	= MSG_CODE_FALSE;
							}
						}
					}
				}
				
			}
			
			return $response->withJson($return_data);
			
		} catch (Exception $e) {
			
			$this->logger->err ( $e->getMessage () );
			
			$return_data ['_error_code']    = 1;
			
			$return_data ['_msg_code']      = MSG_CODE_FALSE;
			
			$return_data ['_text']          = $e->getMessage ();
		}
		
	}
	
	
	/*Moi them ham nay de test*/
	public function avatar(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_msg_text' => $psI18n->__('Upload photos failed.')
		);

		$body = $request->getParsedBody();
		// file
		$url_files = isset($body['avatar']) ? $body['avatar'] : '';
		$url_file_check = vali::notEmpty()->arrayType()->validate($url_files);

		if ($url_file_check) {
			$text_msg = "AAAAAA";
		} else {
			$text_msg = "BBBBBB";
		}

		return $response->withJson($body);
	}

	/**
	 * Screen Home page.
	 * Get all student of user (relative of student)
	 */
	public function home(RequestInterface $request, ResponseInterface $response, array $args)
	{

		// Log message
		// $this->WriteLog ("HOME");
		$return_data = array();
		$return_data['_msg_code'] = MSG_CODE_FALSE;
		//$return_data ['_msg_text'] = $return_data ['message'] = 'FALSE';
		$return_data['_data'] = array();

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);
		$psI18n = new PsI18n($code_lang);

		// get device_id app
		$device_id = $request->getHeaderLine('deviceid');

		if ($user->user_type != USER_TYPE_RELATIVE) {
			$return_data['_msg_code'] = MSG_CODE_500;
			$return_data['_msg_text'] = $return_data['message'] = $psI18n->__('Find your system using the wrong version.');
			return $response->withJson($return_data);
		}

		if ($user->app_device_id != $device_id) {
			$return_data['_msg_code'] = MSG_CODE_NOT_REGISTER_DEVICEID;
			$return_data['_msg_text'] = $return_data['message'] = $psI18n->__('You have not confirmed the Terms to use. Please log out and log in again.');
			return $response->withJson($return_data);
		}

		// if (PsAuthentication::checkDeviceUserRelative ( $user, $device_id )) {

		// Lay danh sach hoc sinh cua user nay
		$relative_id = $user->member_id;

		$students = $this->db->table(CONST_TBL_STUDENT . ' as S')->select('S.id', 'S.avatar', 'S.last_name', 'S.first_name', 'S.sex', 'S.ps_customer_id', 'S.year_data', 'C.cache_data')->join(CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.student_id', '=', 'S.id')->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'S.ps_customer_id')->where('RS.relative_id', '=', $relative_id)->where('S.deleted_at', '=', NULL)->get();

		if (count($students) <= 0) {

			$return_data['_msg_code'] = MSG_CODE_500;
			$return_data['_msg_text'] = $return_data['message'] = $psI18n->__('Find your system has not been associated with any student.');

			return $response->withJson($return_data);
		}

		$data = array();

		foreach ($students as $student) {

			$_data = array();

			$_data['_id'] = $student->id;
			$_data['student_name'] = $student->first_name . ' ' . $student->last_name;
			// $_data['student_name'] = $student->first_name;

			if ($student->avatar != '') {

				$avatar_url = PsString::getUrlMediaAvatar($student->cache_data, $student->year_data, $student->avatar, MEDIA_TYPE_STUDENT);
				//return $student->avatar;//PS0000000006;
				if (!PsFile::urlExists($avatar_url)) {
					if ($student->sex == STATUS_ACTIVE) {
						$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE . 'view_boy_avatar_default.png';
					} else {
						$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE . 'view_girl2_avatar_default.png';
					}
				}
			} else {
				if ($student->sex == STATUS_ACTIVE) {
					$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE . 'view_boy_avatar_default.png';
				} else {
					$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE . 'view_girl2_avatar_default.png';
				}
			}

			$_data['avatar_url'] = $avatar_url;

			array_push($data, $_data);
		}

		$return_data = array(
			'_msg_code' => MSG_CODE_TRUE,
			//'message' => 'TRUE',
			//'_msg_text' => 'TRUE',
			'_data' => $data
		);
		/*
		 * } else {
		 *
		 *
		 *
		 * $return_data ['_msg_code'] = MSG_CODE_500;
		 * $return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		 * }
		 */

		return $response->withJson($return_data);
	}

	public function do_login(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$return_data = array();

		$return_data['_msg_code'] = MSG_CODE_FALSE;
		$return_data['_data'] = array();

		/*
		 * $return_data = array (
		 * '_msg_code' => MSG_CODE_FALSE,
		 * '_data' => ''
		 * );
		 */

		$psI18n = new PsI18n(APP_CONFIG_LANGUAGE);

		//$return_data ['_msg_text'] = $return_data ['message'] = $psI18n->__ ('Login failed. Please check your username and password.');

		//$return_data ['_msg_text'] = $return_data ['message'] = null;
		// get device_id app
		$device_id = $request->getHeaderLine('deviceid');

		// get data from body
		$body = $request->getParsedBody();

		$user_name = isset($body['username']) ? $body['username'] : '';
		$password = isset($body['password']) ? $body['password'] : '';
		$app_code = isset($body['appcode']) ? $body['appcode'] : '';
		
		//return $device_id;
		//$this->WriteLog ( "Login:" . $response->withJson ( $body ) );

		try {
			
			if ($user_name != '' && $password != '' && $device_id != '' && $app_code != '') {
				
				// $user = UserModel::getUserByUserNameAndAppCode ( $user_name, $app_code );

				$user = UserModel::getUserByUserNameOrEmail($user_name);
				
				//return $user->password;
				
				if ($user && ($user->password == PsAuthentication::hashPassword($user->salt, $password, $user->algorithm))) {
					
					// Check kieu user
					if (($app_code == USER_TYPE_TEACHER) && ($user->user_type == USER_TYPE_RELATIVE)) { // Neu app la giao vien nhung account la phu huynh thi thong bao

						$return_data['_msg_code'] = MSG_CODE_500;

						// $return_data['_msg_text'] = $return_data['message'] = $psI18n->__('Find your system using the wrong version app 2.') . PsString::newLine() . $psI18n->__('Please contact: (+84) 24 2245 9696 for assistance.');
						$return_data['_msg_text'] = $return_data['message'] = 'Tài khoản hoặc mật khẩu không chính xác xin vui lòng nhập lại';

						return $response->withJson($return_data);
					}

					if (($app_code == USER_TYPE_RELATIVE) && ($user->user_type == USER_TYPE_TEACHER)) { // Neu app la giao vien nhung account la phu huynh thi thong bao

						$return_data['_msg_code'] = MSG_CODE_500;

						// $return_data['_msg_text'] = $return_data['message'] = $psI18n->__('Find your system using the wrong version app 1.') . PsString::newLine() . $psI18n->__('Please contact: (+84) 24 2245 9696 for assistance.');
						$return_data['_msg_text'] = $return_data['message'] = 'Tài khoản hoặc mật khẩu không chính xác xin vui lòng nhập lại';

						return $response->withJson($return_data);
					}

					// Current active device_id <> push device_id => Need active app
					if ($user->user_type == USER_TYPE_RELATIVE) {

						if ($user->app_device_id != $device_id) {

							$return_data['_msg_code'] = MSG_CODE_NOT_REGISTER_DEVICEID;

							$return_data['_data']["user_type"] = (string) $user->user_type;

							$return_data['_data']["device_check"] = 0;

							$text_terms_of_use = PsSystemCmsContentModel::getItemByCode('terms_of_use')->description;

							$text_html = '<?xml version="1.0" encoding="UTF-8"?><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1"></head><body>' . $text_terms_of_use . '</body></html>';

							$return_data['_data']["terms_of_use"] = PsEndCode::ps64EndCode($text_html);
						} else {

							$return_data['_data']["user_type"] = (string) $user->user_type;

							$return_data['_data']["device_check"] = 1;

							$return_data['_msg_code'] = MSG_CODE_TRUE;
						}

						$user->app_config = json_encode(array(
							'language' => APP_CONFIG_LANGUAGE,
							'style' => APP_CONFIG_STYLE
						));
					} elseif ($user->user_type == USER_TYPE_TEACHER) {
						$return_data['_msg_code'] = MSG_CODE_TRUE;
						$return_data['_data']["user_type"] = (string) $user->user_type;
						$return_data['_data']["device_check"] = 1;
					}

					$api_token = PsAuthentication::generateApiKey(PS_API_SALT);
					$return_data['_data']["api_token"] = $api_token;
					$return_data['_data']["user_info"]["user_id"] = (int) $user->id;
					$return_data['_data']["user_info"]["user_email"] = (string) $user->email_address;
					$return_data['_data']["user_info"]["first_name"] = (string) $user->first_name;
					$return_data['_data']["user_info"]["last_name"] = (string) $user->last_name;

					$return_data['_data']["user_info"]["user_key"] = PsEndCode::psHash256($user->id);

					if ($user->app_config == '') {
						$user->app_config = json_encode(array(
							'language' => APP_CONFIG_LANGUAGE,
							'style' => APP_CONFIG_STYLE
						));
					}

					$return_data['_data']['config'] = json_decode($user->app_config);

					$avatar_url = PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

					if ($user->user_type == USER_TYPE_RELATIVE) {

						// Lay thong tin nguoi than
						$relative = RelativeModel::getAvatar($user->member_id);
						if ($relative) {
							$avatar_url = ($relative->avatar != '') ? PsString::getUrlMediaAvatar($relative->cache_data, $relative->year_data, $relative->avatar, MEDIA_TYPE_RELATIVE) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
						}
					} elseif ($user->user_type == USER_TYPE_TEACHER) {
						
						$return_data['_data']["device_check"] = 1; // Mac dinh device dung

						$user->app_device_id = $device_id;

						$ps_member = PsMemberModel::getAvatar($user->member_id);

						if ($ps_member) {
							$avatar_url = ($ps_member->avatar != '') ? PsString::getUrlMediaAvatar($ps_member->cache_data, $ps_member->year_data, $ps_member->avatar, MEDIA_TYPE_TEACHER) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
						}
					}

					$return_data['_data']["user_info"]["avatar_url"] = $avatar_url;

					// process write token
					$user->id = $user->id;
					$user->api_token = $api_token;
					$user->token_expires_in = null; // Thoi diem het han
					$user->refresh_token = time() + 3600; // Thoi gian cap lai token

					$user->osname = null;
					$user->osvesion = null;
					$return_data['_data']["api_token"] = $api_token;
					$return_data['_msg_text'] = $return_data['message'] = "";
					if (!$user->save()) {
						$return_data['_msg_code'] = MSG_CODE_FALSE;
						$return_data['_data'] = [];
						$return_data['_msg_text'] = $return_data['message'] = $psI18n->__('Login failed. Please check your username and password.');
					}
				}
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;
			$return_data['_msg_text'] = $return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// POST: User logout
	public function do_logout(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$return_data = array();

		$return_data['_msg_code'] = MSG_CODE_TRUE;
		$return_data['_msg_text'] = $return_data['message'] = 'TRUE';

		$tokenAuth = new \Slim\Middleware\TokenAuthentication();

		$api_token = $tokenAuth->findToken($request);

		$user = UserModel::getUserByToken($api_token);

		if ($user) {

			// remove token $api_token
			$user->api_token = '';
			$user->token_expires_in = null;
			$user->refresh_token = null;
			$user->notification_token = null;
			$user->notification_at = null;

			$return_data['_msg_code'] = $user->save();

			$return_data['_msg_code'] = MSG_CODE_TRUE;
		} else {

			$return_data['_msg_code'] = MSG_CODE_500;

			$psI18n = new PsI18n(APP_CONFIG_LANGUAGE);

			$return_data['_msg_text'] = $return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// User register device_id into system
	public function registerDeviceId(RequestInterface $request, ResponseInterface $response, array $args)
	{

		// Log message
		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'_data' => ''
		);

		$user = $this->user_token;

		$return_data['user'] = $user;



		// get device_id app
		$device_id = $request->getHeaderLine('deviceid');

		// get data from body
		$body = $request->getParsedBody();

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		try {

			// Begin Transaction.
			UserModel::beginTransaction();

			// get all device_id of user update list device_id other is not work
			$ps_mobile_apps_for_user = PsMobileAppsModel::getAllDeviceByUserId($user->id);

			foreach ($ps_mobile_apps_for_user as $ps_mobile_app) {
				$ps_mobile_app->status_used = 0; // Lock all device_id old
				$ps_mobile_app->save();
			}

			$ps_mobile_apps = new PsMobileAppsModel();

			$ps_mobile_apps->user_id = $user->id;
			$ps_mobile_apps->device_id = $device_id;
			$ps_mobile_apps->active_created_at = date("Y-m-d H:i:s");
			$ps_mobile_apps->status_used = 1;
			$ps_mobile_apps->is_activated = 1;
			$ps_mobile_apps->user_created_id = $user->id;
			$ps_mobile_apps->user_updated_id = $user->id;

			$ps_mobile_apps->osname = isset($body['osname']) ? $body['osname'] : '';
			$ps_mobile_apps->osvesion = isset($body['osvesion']) ? $body['osvesion'] : '';
			$ps_mobile_apps->network_name = isset($body['network_name']) ? $body['network_name'] : '';
			$ps_mobile_apps->mobile_network_type = isset($body['mobile_network_type']) ? $body['mobile_network_type'] : '';

			// Used Validate data
			$ps_mobile_apps->save();

			$user->app_device_id = $device_id;
			$user->app_config = json_encode(array(
				'language' => APP_CONFIG_LANGUAGE,
				'style' => APP_CONFIG_STYLE
			));

			if ($user->save()) {

				$return_data['_msg_code'] = MSG_CODE_TRUE;

				$return_data['_data']['config']['language'] = APP_CONFIG_LANGUAGE;

				$return_data['_data']['config']['style'] = APP_CONFIG_STYLE;
			}

			// Commit.
			UserModel::commit();
		} catch (Exception $e) {

			// Rollback.
			UserModel::rollback();

			// Log
			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// User forgot password
	public function forgotPassword(RequestInterface $request, ResponseInterface $response, array $args)
	{

		// Log message
		$this->WriteLog('CAP LAI MAT KHAU');

		$psI18n = new PsI18n(APP_CONFIG_LANGUAGE);

		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'message' => $psI18n->__('Reset password failure')
		);

		// get data from body
		$body = $request->getParsedBody();

		$user_name = isset($body['username']) ? $body['username'] : '';

		$appcode = isset($body['appcode']) ? $body['appcode'] : '';

		$this->WriteLog('forgot_password:' . $user_name . ' - appcode:' . $appcode);

		try {

			if (PsString::trimString($user_name) != '' && PsString::trimString($appcode) != '') {

				// get user
				// $user = UserModel::getUserByUserName ( $user_name );
				$user = UserModel::getUserByUserNameOrEmail($user_name);

				if ($user) {
					// generate api token for action
					$_keyapi = PsAuthentication::generateApiKey($user->salt);

					$link_reset = PS_CONST_API_URL_RESET_PASS . '/reset/password/' . date("YmdHsi") . '/' . $_keyapi;

					$userForgotPassword = new PsForgotPasswordModel();

					$userForgotPassword->id = null;
					$userForgotPassword->user_id = $user->id;
					$userForgotPassword->unique_key = $_keyapi;
					$userForgotPassword->expires_at = date("Y-m-d H:s:i");

					if ($userForgotPassword->save()) {

						$psI18n = new PsI18n($this->getUserLanguage($user));

						$ps_mailer = $this->container->mailer;

						$ps_mailer->setSubject($psI18n->__('subject_forgot_password'));

						$ps_mailer->setFrom(array(
							$psI18n->__('email_cskh_truongnet') => $psI18n->__('subject_cskh_truongnet')
						));

						$ps_mailer->setTo(array(
							$user->email_address => $user->first_name . ' ' . $user->last_name
						));

						$user->link_reset = $link_reset;

						$ps_mailer->setBody('forgot_password.html', array(
							'user' => $user
						));

						$return_data['_msg_code'] = (int) $ps_mailer->sendMail();

						// Delete info reset password old when expires_at < date ("Y-m-d H:s:i");
						// UserForgotPasswordModel::where('user_id', $user->id)->delete();
						$return_data['message'] = $psI18n->__('Reset password succeed');
					}
				}
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// User Change password
	public function changePassword(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$return_data = array(
			'_msg_code' => MSG_CODE_FALSE,
			'message' => 'the system encountered an unknown_error'
		);

		$user = $this->user_token;

		try {

			if ($user) {

				// get device_id app
				$device_id = $request->getHeaderLine('deviceid');

				$psI18n = new PsI18n($this->getUserLanguage($user));

				if (!PsAuthentication::checkDevice($user, $device_id)) {

					$return_data = array(
						'_msg_code' => MSG_CODE_NOT_REGISTER_DEVICEID,
						'message' => $psI18n->__('Network connection is not stable. Please do it again in a few minutes.')
					);

					return $response->withJson($return_data);
				}

				// get data from body
				$body = $request->getParsedBody();

				$current_password = PsString::trimString(isset($body['current_password']) ? $body['current_password'] : '');
				$new_password = PsString::trimString(isset($body['new_password']) ? $body['new_password'] : '');
				$confirm_new_password = PsString::trimString(isset($body['confirm_new_password']) ? $body['confirm_new_password'] : '');

				if ($confirm_new_password == '')
					$confirm_new_password = PsString::trimString(isset($body['confconfirm_password']) ? $body['confconfirm_password'] : '');

				/*
				 * $this->WriteLog ( '--BEGIN: DOI MAT KHAU --' );
				 * $this->WriteLog ( 'User name:'.$user->username );
				 *
				 * $this->WriteLog ( 'OLD:'.$current_password );
				 * $this->WriteLog ( '1:'.$new_password );
				 * $this->WriteLog ( '2:'.$confirm_new_password );
				 *
				 * $this->WriteLog ( 'BODY'.$response->withJson ( $body ) );
				 *
				 * $this->WriteLog ( '--END: DOI MAT KHAU --' );
				 */

				$return_data = array();

				// Check current_password
				if ($user->password == PsAuthentication::hashPassword($user->salt, $current_password, $user->algorithm)) {

					if ($new_password == $confirm_new_password) {

						$regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()\-_=+{};:,<.>])/';

						$validator = new PsPasswordValidation(6, $regex);

						$check = $validator->validate('new_password', $new_password);

						if (!$check) {
							$return_data['_error_code'] = 3; // Loi khong hop le
							$return_data['_msg_code'] = MSG_CODE_FALSE;
							$return_data['message'] = $psI18n->__('msg_err_confirm_new_password_is_incorrect');
						} else {

							// set hash new password
							$hash_new_password = PsAuthentication::hashPassword($user->salt, $new_password, $user->algorithm);

							$user->password = $hash_new_password;

							if ($user->save()) {
								$return_data['_error_code'] = 0;
								$return_data['_msg_code'] = MSG_CODE_TRUE;
								$return_data['message'] = $psI18n->__('change_password_succeed');
							} else {
								$return_data['_error_code'] = 1; // Loi bat thuong
								$return_data['_msg_code'] = MSG_CODE_FALSE;
								$return_data['message'] = $psI18n->__('msg_err_change_password_failure');
							}
						}
					} else {

						// Xac nhan mat khau moi loi
						$return_data['_error_code'] = 4; // Loi khong hop le
						$return_data['_msg_code'] = MSG_CODE_FALSE;
						$return_data['message'] = $psI18n->__('msg_err_confirm_new_password_is_incorrect');
					}
				} else {

					$return_data['_error_code'] = 2; // Mat khau cu khong dung
					$return_data['_msg_code'] = MSG_CODE_FALSE;
					$return_data['message'] = $psI18n->__('msg_err_old_password');
				}
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_error_code'] = 1; // Loi bat thuong

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// update notification token
	public function updateNotificationToken(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$return_data = array();

		$return_data['_msg_code'] = MSG_CODE_FALSE;

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$api_token = $user->api_token;
		try {

			if ($user) {

				// get data from body
				$body = $request->getParsedBody();

				// $this->WriteLog ( "notification_token 1:".$response->withJson ( $body ));

				$notification_token = PsString::trimString(isset($body['notification_token']) ? $body['notification_token'] : '');

				$return_data['notification_key'] = $notification_token;

				if ($notification_token != '') {

					// update notification_token = null in DB - Các máy khác cần gán = null
					$update_notification_token = $this->db->table(CONST_TBL_USER)->where('notification_token', '=', $notification_token)->where('api_token', '!=', $api_token)->update([
						// 'login_at' => $log_at,
						'notification_token' => null,
						'notification_at' => null
					]);

					$user_after = UserModel::getUserByToken($api_token);

					$user_after->notification_token = $notification_token;
					$user_after->notification_at = date("Y-m-d H:s:i");

					$_osname = PsString::trimString(isset($body['osname']) ? $body['osname'] : '');
					$_osvesion = PsString::trimString(isset($body['osvesion']) ? $body['osvesion'] : '');

					if ($_osname != '') {
						$user_after->osname = PsString::strToUpperString($_osname);
						$user_after->osvesion = $_osvesion;
					}
					$return_data['_msg_code'] = $user_after->save() ? MSG_CODE_TRUE : MSG_CODE_FALSE;

					// $this->WriteLog ( "KET QUA: ".(int)$return_data ['_msg_code']);
				} else {
					$return_data['_msg_code'] = MSG_CODE_FALSE;
				}
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// get User profile
	public function userProfile(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$return_data = array();

		$return_data['_error_code'] = 1;
		$return_data['_msg_code'] = MSG_CODE_FALSE;
		$return_data['_data'] = array();

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		try {

			if ($user) {

				// get device_id app
				$device_id = $request->getHeaderLine('deviceid');

				if (!PsAuthentication::checkDevice($user, $device_id)) {

					$return_data = array(
						'_msg_code' => MSG_CODE_NOT_REGISTER_DEVICEID,
						'message' => $psI18n->__('Network connection is not stable. Please do it again in a few minutes.')
					);

					return $response->withJson($return_data);
				}

				// Lay danh sach Dan toc ps_ethnic
				$ps_ethnics = $this->db->table(CONST_TBL_PS_ETHNIC)->select('title', 'id')->where('is_activated', 1)->orderBy('iorder', 'asc')->get();
				$return_data['ethnics'] = $ps_ethnics;

				// Lay danh sach Ton giao
				$ps_religions = $this->db->table(CONST_TBL_PS_RELIGION)->select('title', 'id')->where('is_activated', 1)->orderBy('iorder', 'asc')->get();

				$return_data['religions'] = $ps_religions;

				// Country
				$countries = new PsCountry();
				$return_data['countries'] = $countries::countryList();

				if ($user->user_type == USER_TYPE_RELATIVE) { // Neu nguoi dung la phu huynh

					// Lay thong tin nguoi than
					$relative = RelativeModel::getRelativeById($user->member_id);

					if ($relative) {

						$user_info = new \stdClass();

						$user_info->username = (string) $user->username;
						$user_info->password = '********';

						$user_info->email = (string) $relative->email;
						$user_info->first_name = (string) $relative->first_name;
						$user_info->last_name = (string) $relative->last_name;
						$user_info->birthday = (string) PsDateTime::toDMY($relative->birthday);
						$user_info->gender = (string) $relative->sex;
						$user_info->identity_card = ($relative->identity_card != '') ? (string) $relative->identity_card : '';

						$user_info->card_date = ($relative->card_date != '') ? (string) PsDateTime::toDMY($relative->card_date) : '01-01-1970';

						if ($user->osname == "IOS") {
							$user_info->card_date = ($relative->card_date != '') ? (string) PsDateTime::toDMY($relative->card_date) : '01-01-1900';
						} else {
							$user_info->card_date = ($relative->card_date != '') ? (string) PsDateTime::toDMY($relative->card_date) : '';
						}

						$user_info->place_cards = ($relative->card_local != '') ? (string) $relative->card_local : '';

						// $user_info->ethnic = ($relative->ethnic_id > 0) ? $relative->ethnic_id : '';
						$user_info->ethnic = (string) $relative->ethnic_id;

						// $user_info->religion = ($relative->religion_id > 0) ? $relative->religion_id : '';
						$user_info->religion = (string) $relative->religion_id;

						$user_info->job = ($relative->job != '') ? (string) $relative->job : '';
						$user_info->phone = ($relative->mobile != '') ? (string) $relative->mobile : '';
						$user_info->address = ($relative->address != '') ? (string) $relative->address : '';
						$user_info->nationality = ($relative->nationality != '') ? (string) $relative->nationality : '';

						// $user_info->avatar_url = ($relative->avatar != '') ? PsString::generateUrlImage ( 'relative', $relative->avatar, $relative->ps_customer_id, $api_token ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

						$user_info->avatar_url = ($relative->avatar != '') ? PsString::getUrlMediaAvatar($relative->cache_data, $relative->year_data, $relative->avatar, MEDIA_TYPE_RELATIVE) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
					}
				} elseif ($user->user_type == USER_TYPE_TEACHER) {

					$ps_member = PsMemberModel::getPsMemberById($user->member_id);

					if ($ps_member) {

						$user_info = new \stdClass();

						$user_info->username = (string) $user->username;

						$user_info->password = '********';

						$user_info->email = (string) $ps_member->email;
						$user_info->first_name = (string) $ps_member->first_name;
						$user_info->last_name = (string) $ps_member->last_name;
						$user_info->birthday = (string) PsDateTime::toDMY($ps_member->birthday);
						$user_info->gender = (string) $ps_member->sex;
						$user_info->identity_card = ($ps_member->identity_card != '') ? (string) $ps_member->identity_card : '';

						// $user_info->card_date = ($ps_member->card_date != '') ? (string)PsDateTime::toDMY ( $ps_member->card_date ) : '';

						if ($user->osname == "IOS") {
							$user_info->card_date = ($ps_member->card_date != '') ? (string) PsDateTime::toDMY($ps_member->card_date) : '01-01-1900';
						} else {
							$user_info->card_date = ($ps_member->card_date != '') ? (string) PsDateTime::toDMY($ps_member->card_date) : '';
						}

						$user_info->place_cards = ($ps_member->card_local != '') ? (string) $ps_member->card_local : '';

						// $user_info->ethnic = ($ps_member->ethnic_id > 0) ? $ps_member->ethnic_id : '';
						// $user_info->religion = ($ps_member->religion_id > 0) ? $ps_member->religion_id : '';

						$user_info->ethnic = (string) $ps_member->ethnic_id;
						$user_info->religion = (string) $ps_member->religion_id;

						$user_info->job = '';
						$user_info->phone = ($ps_member->mobile != '') ? (string) $ps_member->mobile : '';
						$user_info->address = ($ps_member->address != '') ? (string) $ps_member->address : '';
						$user_info->nationality = ($ps_member->nationality != '') ? (string) $ps_member->nationality : '';
						$user_info->cache_data = $ps_member->cache_data;
						$user_info->avatar_url = ($ps_member->avatar != '') ? PsString::getUrlMediaAvatar($ps_member->cache_data, $ps_member->year_data, $ps_member->avatar, MEDIA_TYPE_TEACHER) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
					}
				}

				$return_data['_error_code'] = 0;
				$return_data['_msg_code'] = MSG_CODE_TRUE;
				$return_data['_data']['user_info'] = $user_info;
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_error_code'] = 1;

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// Update profile
	public function updateProfile(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$return_data = array();

		$return_data['_error_code'] = 1;
		$return_data['_msg_code'] = MSG_CODE_FALSE;

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		try {

			if ($user) {

				// get device_id app
				$device_id = $request->getHeaderLine('deviceid');

				if (!PsAuthentication::checkDevice($user, $device_id)) {

					$return_data = array(
						'_msg_code' => MSG_CODE_NOT_REGISTER_DEVICEID,
						'message' => $psI18n->__('Network connection is not stable. Please do it again in a few minutes.')
					);

					return $response->withJson($return_data);
				}

				// get data from body
				$body = $request->getParsedBody();

				$info = isset($body['info']) ? $body['info'] : '';

				if ($user->user_type == USER_TYPE_RELATIVE) { // Neu nguoi dung la phu huynh

					// Kiem tra tai khoan
					$amount_info = PsMobileAppAmountsModel::checkAmountInfo($user->id);

					if (!$amount_info) {

						$return_data = array(
							'_msg_code' => MSG_CODE_PAYMENT,
							'message' => $psI18n->__('Your account has run out of money. Please recharge to continue using.')
						);

						return $response->withJson($return_data);
					}

					RelativeModel::beginTransaction();

					// Lay thong tin nguoi than
					$relative = RelativeModel::where('id', $user->member_id)->first();

					if (!$relative) {
						$return_data = array(
							'_error_code' => 1,
							'_msg_code' => MSG_CODE_FALSE
						);
					} else {

						$old_email = $relative->email;

						// if validate true
						$info['id'] = $user->member_id;

						foreach ($info as $key => $value) {
							$info[$key] = PsString::trimString($value);
						}

						$info['phone'] = PsString::strReplace($info['phone'], array(
							" "
						));

						$validate = $this->validateDataRelative($info);

						$this->WriteLog($response->withJson($validate));

						if ($validate['msg']) {

							$relative->email = isset($info['email']) ? $info['email'] : $relative->email;

							$relative->first_name = isset($info['first_name']) ? $info['first_name'] : $relative->first_name;
							$relative->last_name = isset($info['last_name']) ? $info['last_name'] : $relative->last_name;
							$relative->birthday = isset($info['birthday']) ? date('Y-m-d', strtotime($info['birthday'])) : $relative->birthday;
							$relative->sex = isset($info['gender']) ? (int) $info['gender'] : $relative->sex;
							$relative->identity_card = isset($info['identity_card']) ? $info['identity_card'] : $relative->identity_card;
							$relative->card_date = isset($info['card_date']) ? date('Y-m-d', strtotime($info['card_date'])) : $relative->card_date;
							$relative->card_local = isset($info['place_cards']) ? $info['place_cards'] : $relative->card_local;
							$relative->ethnic_id = isset($info['ethnic']) ? $info['ethnic'] : -1;
							$relative->religion_id = isset($info['religion']) ? $info['religion'] : -1;
							$relative->address = isset($info['address']) ? $info['address'] : $relative->address;
							$relative->mobile = isset($info['phone']) ? $info['phone'] : $relative->mobile;
							$relative->job = isset($info['job']) ? $info['job'] : $relative->job;
							$relative->nationality = isset($info['nationality']) ? $info['nationality'] : null;

							$relative->updated_at = date("Y-m-d H:i:s");
							$relative->user_updated_id = $user->id;

							if ($relative->save()) {

								// Update họ tên; Email vào bảng User
								$this->db->table(CONST_TBL_USER)->where('id', '=', $user->id)->update([
									'first_name' => $relative->first_name,
									'last_name' => $relative->last_name,
									'email_address' => $info['email'],
									'user_updated_id' => $user->id
								]);

								// Update Email vào bảng Email
								$ps_email = PsEmailModel::getPsEmailByEmail($old_email);

								if ($ps_email) {
									$ps_email->ps_email = $info['email'];
									$ps_email->save();
								}

								$return_data = array(
									'_error_code' => 0,
									'_msg_code' => MSG_CODE_TRUE
								);
							} else {
								$return_data = array(
									'_error_code' => 1,
									'_msg_code' => MSG_CODE_FALSE
								);
							}
						} else {
							$return_data['_error_info']['user_info'] = $validate['user_info'];
						}
					}

					RelativeModel::commit();
				} elseif ($user->user_type == USER_TYPE_TEACHER) { // Giao vien

					PsMemberModel::beginTransaction();

					$ps_member = PsMemberModel::where('id', $user->member_id)->first();

					if (!$ps_member) {
						$return_data = array(
							'_error_code' => 1,
							'_msg_code' => MSG_CODE_FALSE
						);
					} else {

						// if validate true
						$info['id'] = $user->member_id;

						$old_email = $ps_member->email;

						foreach ($info as $key => $value) {
							$info[$key] = PsString::trimString($value);
						}

						$info['phone'] = PsString::strReplace($info['phone'], array(
							" "
						));

						$validate = $this->validateDataMember($info);

						if ($validate['msg']) {

							$ps_member->email = isset($info['email']) ? $info['email'] : $ps_member->email;

							$ps_member->member_code = isset($info['member_code']) ? $info['member_code'] : $ps_member->member_code;

							$ps_member->first_name = isset($info['first_name']) ? $info['first_name'] : $ps_member->first_name;

							$ps_member->last_name = isset($info['last_name']) ? $info['last_name'] : $ps_member->last_name;

							$ps_member->birthday = isset($info['birthday']) ? PsDateTime::toYMD($info['birthday']) : '';

							$ps_member->sex = isset($info['gender']) ? $info['gender'] : $ps_member->sex;

							$ps_member->identity_card = isset($info['identity_card']) ? $info['identity_card'] : '';

							// $ps_member->card_date = isset ( $info ['card_date'] ) ? date ( 'Y-m-d', strtotime ( $info ['card_date'] ) ) : $ps_member->card_date;

							$ps_member->card_date = isset($info['card_date']) ? PsDateTime::toYMD($info['card_date']) : '';

							$ps_member->card_local = isset($info['place_cards']) ? $info['place_cards'] : '';

							$ps_member->ethnic_id = isset($info['ethnic']) ? (int) $info['ethnic'] : -1;

							$ps_member->religion_id = isset($info['religion']) ? (int) $info['religion'] : -1;

							$ps_member->address = isset($info['address']) ? $info['address'] : '';

							// $ps_member->job = isset ( $info ['job'] ) ? $info ['job'] : $ps_member->job;

							$ps_member->mobile = isset($info['phone']) ? (string) $info['phone'] : '';

							$ps_member->nationality = isset($info['nationality']) ? $info['nationality'] : '';

							$ps_member->user_updated_id = $user->id;

							$ps_member->updated_at = date("Y-m-d H:i:s");

							if ($ps_member->save()) {

								// Update họ tên; Email vào bảng User
								$this->db->table(CONST_TBL_USER)->where('id', '=', $user->id)->update([
									'first_name' => $ps_member->first_name,
									'last_name' => $ps_member->last_name,
									'email_address' => $info['email'],
									'user_updated_id' => $user->id
								]);

								$ps_email = PsEmailModel::getPsEmailByEmail($old_email);

								if ($ps_email) {
									$ps_email->ps_email = $info['email'];
									$ps_email->save();
								}

								$return_data = array(
									'_error_code' => 0,
									'_msg_code' => MSG_CODE_TRUE
								);
							} else {
								$return_data = array(
									'_error_code' => 1,
									'_msg_code' => MSG_CODE_FALSE
								);
							}
						} else {

							$return_data = array();
							$return_data['_error_info']['user_info'] = $validate['user_info'];
							$return_data['_error_code'] = 1;
							$return_data['_msg_code'] = MSG_CODE_FALSE;
						}
					}

					PsMemberModel::commit();
				}
			}
		} catch (Exception $e) {

			if ($user->user_type == USER_TYPE_RELATIVE) {
				RelativeModel::rollBack();
			} elseif ($user->user_type == USER_TYPE_TEACHER) {
				PsMemberModel::rollBack();
			}

			$this->logger->err($e->getMessage());

			$return_data['_error_code'] = 1;

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// Information relatives - Lay danh sach nguoi than cua hoc sinh
	public function informationRelatives(RequestInterface $request, ResponseInterface $response, array $args)
	{
		$return_data = array();

		$return_data['_msg_code'] = MSG_CODE_FALSE;
		$return_data['_error_code'] = 1;
		$return_data['_data'] = array();

		$user = $this->user_token;

		// get data from URI
		$student_id = $args['student_id'];

		$code_lang = $this->getUserLanguage($user);
		$psI18n = new PsI18n($code_lang);

		try {
			if ($student_id > 0) {

				$device_id = $request->getHeaderLine('deviceid');

				if ($user) {

					if ($user->user_type == USER_TYPE_RELATIVE) {

						if ($user->app_device_id != $device_id) {

							$return_data = array(
								'_msg_code' => MSG_CODE_NOT_REGISTER_DEVICEID,
								'message' => $psI18n->__('Network connection is not stable. Please do it again in a few minutes.')
							);

							return $response->withJson($return_data);
						} else {

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

						// Kiem tra rang buoc User va hoc sinh
						$check = PsRelativeStudentModel::checkAccessRelativeInfo($student_id, $user->member_id);

						if (!$check) { // Khong co quyen truy cap
							$return_data['_msg_code'] = MSG_CODE_LOCK;
							$return_data['_error_code'] = 2;
						} else {

							// Lay danh sach va kiem tra moi quan he giua hoc sinh va member
							$relatives = RelativeModel::getAllRelativeByStudentId($student_id);

							$data = [];

							foreach ($relatives as $relative) {

								$_data = array();

								$_data['student_id'] = $student_id;
								$_data['relative_id'] = (string) $relative->id;
								$_data['text_name'] = $relative->first_name . ' ' . $relative->last_name;
								$_data['phone'] = ($relative->phone != '') ? $relative->phone : '';
								$_data['part'] = $relative->is_parent;
								$_data['relationship'] = $relative->relationship;

								$_data['avatar_url'] = ($relative->avatar != '') ? PsString::getUrlMediaAvatar($relative->cache_data, $relative->year_data, $relative->avatar, MEDIA_TYPE_RELATIVE) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

								array_push($data, $_data);
							}

							$return_data['_error_code'] = 0;
							$return_data['_msg_code'] = MSG_CODE_TRUE;
							$return_data['_data'] = $data;
						}
					} elseif ($user->user_type == USER_TYPE_TEACHER) {
						// Giao vien trong truong co quyen xem du lieu trong truong
						$relatives = RelativeModel::getAllRelativeByStudentId($student_id);

						$data = [];

						foreach ($relatives as $relative) {

							$_data = array();

							$_data['student_id'] = $student_id;
							$_data['relative_id'] = (string) $relative->id;
							$_data['text_name'] = $relative->first_name . ' ' . $relative->last_name;
							$_data['phone'] = ($relative->phone != '') ? $relative->phone : '';
							$_data['part'] = $relative->is_parent;
							$_data['relationship'] = $relative->relationship;
							$_data['avatar_url'] = ($relative->avatar != '') ? PsString::getUrlMediaAvatar($relative->cache_data, $relative->year_data, $relative->avatar, MEDIA_TYPE_RELATIVE) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

							array_push($data, $_data);
						}

						$return_data['_error_code'] = 0;

						$return_data['_msg_code'] = MSG_CODE_TRUE;

						$return_data['_data'] = $data;
					}
				}
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// get information relative
	public function informationRelative(RequestInterface $request, ResponseInterface $response, array $args)
	{

		// Khong co du lieu student chung => khong co quyen xem thong tin phu huynh nay
		$return_data = array();

		$return_data['_msg_code'] = 2;
		$return_data['_error_code'] = 2;
		$return_data['_data'] = array();

		$user = $this->user_token;

		$psI18n = new PsI18n($this->getUserLanguage($user));

		// get data from URI
		$student_id = $args['student_id'];

		$relative_id = $args['relative_id'];

		try {
			if ($student_id > 0 && $relative_id > 0) {

				$device_id = $request->getHeaderLine('deviceid');

				if (!PsAuthentication::checkDevice($user, $device_id)) {

					$return_data = array(
						'_msg_code' => MSG_CODE_NOT_REGISTER_DEVICEID,
						'message' => $psI18n->__('Network connection is not stable. Please do it again in a few minutes.')
					);

					return $response->withJson($return_data);
				} else {

					if ($user->user_type == USER_TYPE_RELATIVE) {

						// Check tien trong tai khoan
						$amount_info = PsMobileAppAmountsModel::checkAmountInfo($user->id);

						if (!$amount_info) {

							$return_data = array(
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__('Your account has run out of money. Please recharge to continue using.')
							);

							return $response->withJson($return_data);
						}

						$check = PsRelativeStudentModel::checkAccessRelativeInfo($student_id, $user->member_id);
					} else {
						$check = true;
					}

					if ($check) {

						$relative = RelativeModel::getRelativeDetailOfStudent($relative_id, $student_id);

						if ($relative) {

							$user_info = new \stdClass();

							$user_info->relationship = ($relative->relationship != '') ? $relative->relationship : '';

							$user_info->part = (int) $relative->is_parent;

							$user_info->email = $relative->email;
							$user_info->first_name = $relative->first_name;
							$user_info->last_name = $relative->last_name;
							$user_info->birthday = PsDateTime::toDMY($relative->birthday);
							$user_info->gender = $relative->sex;
							$user_info->identity_card = ($relative->identity_card != '') ? $relative->identity_card : '';
							$user_info->card_date = ($relative->card_date != '') ? PsDateTime::toDMY($relative->card_date) : '';
							$user_info->place_cards = ($relative->card_local != '') ? $relative->card_local : '';
							$user_info->ethnic = ($relative->ethnic != '') ? $relative->ethnic : '';
							$user_info->religion = ($relative->religion != '') ? $relative->religion : '';
							$user_info->job = ($relative->job != '') ? $relative->job : '';
							$user_info->phone = ($relative->mobile != '') ? $relative->mobile : '';
							$user_info->address = ($relative->address != '') ? $relative->address : '';
							$user_info->nationality = ($relative->nationality != '') ? $relative->nationality : '';
							$user_info->avatar_url = ($relative->avatar != '') ? PsString::getUrlMediaAvatar($relative->cache_data, $relative->year_data, $relative->avatar, MEDIA_TYPE_RELATIVE) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

							$countries = new PsCountry();

							if (isset($countries::$country_array[$user_info->nationality]))
								$user_info->nationality = $countries::$country_array[$user_info->nationality];
							else
								$user_info->nationality = null;

							$return_data = array(
								'_error_code' => 0,
								'_msg_code' => MSG_CODE_TRUE
							);

							$return_data['_data']['user_info'] = $user_info;
						}
					}
				}
			}
		} catch (Exception $e) {

			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}

		return $response->withJson($return_data);
	}

	// User config
	public function config(RequestInterface $request, ResponseInterface $response, array $args)
	{

		// Log message
		$return_data = array();
		$return_data['_msg_code'] = MSG_CODE_TRUE;

		$user = $this->user_token;

		// get device_id app
		$device_id = $request->getHeaderLine('deviceid');

		// get data from body
		$body = $request->getParsedBody();

		if (!isset($body['info'])) {
			return $response->withJson($return_data);
		}

		$language = isset($body['info']['language']) ? strtoupper($body['info']['language']) : '';

		$style = isset($body['info']['style']) ? $body['info']['style'] : '';

		if (($language == '' && $style == '') || (!$user) || ($user && !PsAuthentication::checkDevice($user, $device_id))) {
			return $response->withJson($return_data);
		}

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		try {

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

			// Begin Transaction.
			UserModel::beginTransaction();

			$configs = $this->default_setting_app;

			$app_config = json_decode($user->app_config);

			$check = false;

			if ($language != '') {
				foreach ($configs['language'] as $value) {
					if ($language == strtoupper($value)) {
						$check = true;
						break;
					}
				}
				if ($check) {

					$user->app_config = json_encode(array(
						'language' => $language,
						'style' => $app_config->style
					));
				}
			} elseif ($style != '') {

				foreach ($configs['style'] as $value) {
					if ($style == $value) {
						$check = true;
						break;
					}
				}

				if ($check) {
					$user->app_config = json_encode(array(
						'language' => $app_config->language,
						'style' => $style
					));
				}
			}

			if ($check && $user->save()) {
				$return_data['_msg_code'] = MSG_CODE_TRUE;
			} else {
				$return_data['_msg_code'] = MSG_CODE_FALSE;
			}

			// Commit
			UserModel::commit();
		} catch (Exception $e) {
			// Rollback.
			UserModel::rollback();

			// Log
			$this->logger->err($e->getMessage());

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		}
		return $response->withJson($return_data);
	}


	/**
	 * Screen new Home page.
	 * Get all student of user (relative of student)
	 */
	public function HomeRelative(RequestInterface $request, ResponseInterface $response, array $args)
	{

		$return_data = array();
		$return_data['_msg_code']  = MSG_CODE_TRUE;


		$user = $this->user_token;
		$code_lang = $this->getUserLanguage($user);
		$psI18n = new PsI18n($code_lang);

		$return_data['title'] = $psI18n->__('Home');
		$return_data['school_logo_url'] = '';
		$return_data['_data'] 		= array();

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

		try {
			// get device_id app
			$device_id = $request->getHeaderLine('deviceid');

			$queryParams = $request->getQueryParams();

			if (!PsAuthentication::checkDeviceUserRelative($user, $device_id)) {
				$return_data = array(
					'_msg_code' => MSG_CODE_NOT_REGISTER_DEVICEID,
					'message' => $psI18n->__('You have not confirmed the Terms to use. Please log out and log in again.')
				);

				return $response->withJson($return_data);
			}

			$ps_customer = PsCustomerModel::getInfo($user->ps_customer_id);
			$return_data['school_logo_url'] = PsString::getUrlLogoPsCustomer($ps_customer->year_data, $ps_customer->logo);

			$relative_id = $user->member_id;

			// Lay danh sach hoc sinh cua user nay
			$students = $this->db->table(CONST_TBL_STUDENT . ' as S')
				->select('S.id', 'S.avatar', 'S.sex', 'S.last_name', 'S.ps_customer_id', 'S.year_data', 'C.cache_data')
				->join(CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.student_id', '=', 'S.id')
				->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'S.ps_customer_id')
				->where('RS.relative_id', '=', $relative_id)
				->where('S.deleted_at', '=', NULL)
				->orderBy('RS.is_parent_main', 'DESC')
				->orderBy('S.last_name')->get();

			if (count($students) <= 0) {

				$return_data['_msg_code'] = MSG_CODE_500;
				$return_data['_msg_text'] = $return_data['message'] = $psI18n->__('Find your system has not been associated with any student.');

				return $response->withJson($return_data);
			}

			$student_id = isset($queryParams['student_id']) ? $queryParams['student_id'] : 0;
			if ($student_id > 0) {
				// Kiem tra moi quan he giua hoc sinh va nguoi than
				if (!PsRelativeStudentModel::checkAccessRelativeInfo($student_id, $relative_id)) {
					$return_data['_msg_code'] = MSG_CODE_500;
					$return_data['_msg_text'] = $return_data['message'] = $psI18n->__('Find your system has not been associated with any student.');

					return $response->withJson($return_data);
				}
			}

			// Danh sach hoc sinh cho nguoi than
			$data_students = array();
			$index = 0;
			foreach ($students as $student) {

				$student_info  = array();

				$student_info['student_id'] 	= (int)$student->id;

				$student_info['student_name']  = (string)$student->last_name;

				if ($student->avatar != '') {
					$avatar_url = PsString::getUrlMediaAvatar($student->cache_data, $student->year_data, $student->avatar, MEDIA_TYPE_STUDENT);
				} else {
					if ($student->sex == STATUS_ACTIVE) {
						$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE . 'boy_avatar_default.svg';
					} else {
						$avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE . 'girl_avatar_default.svg';
					}
				}

				$student_info['avatar_url'] 	 = $avatar_url;

				if ($student_id > 0) {
					$student_info['active'] 	 = ($student_id == $student->id) ? STATUS_ACTIVE : STATUS_NOT_ACTIVE;
				} elseif ($index == 0) {
					$student_info['active'] 	 = STATUS_ACTIVE;
					$student_id					 = $student->id;
				}

				$index++;

				array_push($data_students, $student_info);
			}

			$return_data['_data']['students'] 			= $data_students;

			$url_menu = PsString::getUrlIconMenuApp() . 'KidsSchool/' . $app_config_color . '/';

			// Album box
			$return_data['_data']['albums_menu'] = array();

			$albums_menu       = array('screen_code' => PS_CONST_SCREEN_ALBUMS, 'icon_url' => $url_menu . 'show-album.png');

			array_push($return_data['_data']['albums_menu'], $albums_menu);

			$data_list_albums = array();

			$limit_album = 3;
			// Lay ra 3 album moi nhat cua hoc sinh
			$list_albums = AlbumModel::getAlbumsOfStudent($student_id, $user->ps_customer_id, $limit_album);
			//$list_albums = AlbumModel::getListAlbumsOfRelative($student_id, $user->ps_customer_id);


			if (count($list_albums) < $limit_album) { // Neu ko du 3 album thi lay 3 anh cua album moi nhat

			} else {
				foreach ($list_albums as $album) {
					$temp_album = new \stdClass();
					$temp_album->album_id  = (int) $album->id;
					$temp_album->image_id  = (int) $album->image_id;
					$temp_album->image_url = $album->url_thumbnail;

					array_push($data_list_albums, $temp_album);
				}
			}

			$return_data['_data']['albums_list'] = $data_list_albums;

			// MENU 1 - Bao quanh hoc sinh
			$menus_1 = array();
			// Diem danh
			$menu = array('screen_code' => PS_CONST_SCREEN_ATTENDANCE, 'name' => $psI18n->__('Attendance'),  'number_notification' => 0, 'position' => 1, 'icon_url' => $url_menu . 'attendance.png');
			array_push($menus_1, $menu);

			$menu = array('screen_code' => PS_CONST_SCREEN_MENU, 'name' => $psI18n->__('Menu'),  'number_notification' => 0, 'position' => 2, 'icon_url' => $url_menu . 'menu.png');
			array_push($menus_1, $menu);

			$menu = array('screen_code' => PS_CONST_SCREEN_REPORT_FEE, 'name' => $psI18n->__('Notice fees'),  'number_notification' => 0, 'position' => 3, 'icon_url' => $url_menu . 'fee.png');
			array_push($menus_1, $menu);

			$menu = array('screen_code' => PS_CONST_SCREEN_SCHEDULE, 'name' => $psI18n->__('Schedule'),  'number_notification' => 0, 'position' => 4, 'icon_url' => $url_menu . 'schedule.png');
			array_push($menus_1, $menu);

			$return_data['_data']['menu_1'] 		= $menus_1;

			$return_data['_data']['menus_2'] 		= $this->loadMenuHome($user, $psI18n, $app_config_color);
		} catch (Exception $e) {

			$return_data['_msg_code'] = MSG_CODE_500;

			$return_data['_msg_text'] = $return_data['message'] 		= $e->getMessage();
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
	protected function loadMenuHome($user, $psI18n, $app_config_color)
	{

		$menus = array();

		$url = PsString::getUrlIconMenuApp() . 'KidsSchool/' . $app_config_color . '/';

		// Dan do
		$menu = array('screen_code' => PS_CONST_SCREEN_ADVICE, 'name' => $psI18n->__('Advice'),  'number_notification' => 0, 'icon_url' => $url . 'dando.png');
		array_push($menus, $menu);

		// Xin nghi
		$menu = array('screen_code' => PS_CONST_SCREEN_OFFSCHOOL, 'name' => $psI18n->__('Absent'),  'number_notification' => 0, 'icon_url' => $url . 'xinnghi.png');
		array_push($menus, $menu);

		// Thông báo
		$menu = array('screen_code' => PS_CONST_SCREEN_CMSNOTIFICATION, 'name' => $psI18n->__('Message'),  'number_notification' => 0, 'icon_url' => $url . 'thongbao.png');
		array_push($menus, $menu);

		// Y tê - Sức khỏe
		$menu = array('screen_code' => PS_CONST_SCREEN_GROWTHSTUDENT, 'name' => $psI18n->__('Y tế-Sức khỏe'),  'number_notification' => 0, 'icon_url' => $url . 'ytesuckhoe.png');
		array_push($menus, $menu);

		// Tin tuc
		$menu = array('screen_code' => PS_CONST_SCREEN_NEWS, 'name' => $psI18n->__('News'),  'number_notification' => 0, 'icon_url' => $url . 'news.png');
		array_push($menus, $menu);

		// Nguoi than cua be
		$menu = array('screen_code' => PS_CONST_SCREEN_RELATIVES, 'name' => $psI18n->__('D/s người thân'),  'number_notification' => 0, 'icon_url' => $url . 'nxcuoithang.png');
		array_push($menus, $menu);

		/*
				
		// Dan do
		$menu = array('screen_code' => PS_CONST_SCREEN_ADVICE, 'name' => $psI18n->__ ('Advice'),  'number_notification' => 0, 'icon_url' => $url.'dando.png' );
		array_push($menus, $menu);
		
		// Xin nghi
		$menu = array('screen_code' => PS_CONST_SCREEN_OFFSCHOOL, 'name' => $psI18n->__ ('Absent'),  'number_notification' => 5, 'icon_url' => $url.'xinnghi.png' );
		array_push($menus, $menu);
		
		// Tin tuc
		$menu = array('screen_code' => PS_CONST_SCREEN_NEWS, 'name' => $psI18n->__ ('News'),  'number_notification' => 3, 'icon_url' => $url.'news.png' );
		array_push($menus, $menu);
		
		
		// Dan do
		$menu = array('screen_code' => PS_CONST_SCREEN_ADVICE, 'name' => $psI18n->__ ('Advice'),  'number_notification' => 0, 'icon_url' => $url.'dando.png' );
		array_push($menus, $menu);
		
		// Xin nghi
		$menu = array('screen_code' => PS_CONST_SCREEN_OFFSCHOOL, 'name' => $psI18n->__ ('Absent'),  'number_notification' => 5, 'icon_url' => $url.'xinnghi.png' );
		array_push($menus, $menu);
		
		// Tin tuc
		$menu = array('screen_code' => PS_CONST_SCREEN_NEWS, 'name' => $psI18n->__ ('News'),  'number_notification' => 3, 'icon_url' => $url.'news.png' );
		array_push($menus, $menu);
		
		*/

		/*
		// Dan do
		$menu = array('screen_code' => PS_CONST_SCREEN_ADVICE, 'name' => $psI18n->__ ('Advice'),  'number_notification' => 0, 'icon_url' => $url.'dando.png' );
		array_push($menus, $menu);
		
		// Xin nghi
		$menu = array('screen_code' => PS_CONST_SCREEN_OFFSCHOOL, 'name' => $psI18n->__ ('Absent'),  'number_notification' => 5, 'icon_url' => $url.'xinnghi.png' );
		array_push($menus, $menu);
		
		// Tin tuc
		$menu = array('screen_code' => PS_CONST_SCREEN_NEWS, 'name' => $psI18n->__ ('News'),  'number_notification' => 3, 'icon_url' => $url.'news.png' );
		array_push($menus, $menu);		
		
		
		// Dan do
		$menu = array('screen_code' => PS_CONST_SCREEN_ADVICE, 'name' => $psI18n->__ ('Advice'),  'number_notification' => 0, 'icon_url' => $url.'dando.png' );
		array_push($menus, $menu);
		
		// Xin nghi
		$menu = array('screen_code' => PS_CONST_SCREEN_OFFSCHOOL, 'name' => $psI18n->__ ('Absent'),  'number_notification' => 5, 'icon_url' => $url.'xinnghi.png' );
		array_push($menus, $menu);
		
		// Tin tuc
		$menu = array('screen_code' => PS_CONST_SCREEN_NEWS, 'name' => $psI18n->__ ('News'),  'number_notification' => 3, 'icon_url' => $url.'news.png' );
		array_push($menus, $menu);
				
		*/

		return $menus;
	}

	/**
	 * Validate form data update profile
	 *
	 * @param
	 *        	$data
	 * @return array - msg status
	 */
	protected function validateDataRelative($info)
	{

		// check field in form
		$control_name = array(
			'id',
			'email',
			'first_name',
			'last_name',
			'birthday',
			'gender',
			'identity_card',
			'card_date',
			'place_cards',
			'ethnic',
			'religion',
			'job',
			'phone',
			'address',
			'nationality'
		);

		$check = $return = array();

		$return['msg'] = true;

		try {
			foreach ($control_name as $key) {
				if (!isset($info[$key]))
					$info[$key] = null;
			}

			$check['email'] = (int) vali::email()->validate($info['email']);

			if ($check['email']) { // Check unique

				$check['email'] = (int) PsEmailModel::checkEmailUnique($info['email'], $info['id'], USER_TYPE_RELATIVE);
			}

			$check['first_name'] = (int) vali::notEmpty()->stringType()->length(1, 255)->validate($info['first_name']);

			$check['last_name'] = (int) vali::notEmpty()->stringType()->length(1, 255)->validate($info['last_name']);

			$check['birthday'] = (int) vali::notEmpty()->dateTime('d-m-Y')->between('01-01-1900', 'now')->validate($info['birthday']);

			$check['gender'] = (int) vali::intVal()->between(0, 1)->validate($info['gender']);

			$check['identity_card'] = (int) vali::stringType()->length(null, 50)->validate($info['identity_card']);

			if ($info['card_date'])
				$check['card_date'] = (int) vali::dateTime('d-m-Y')->between('01-01-1900', 'now')->validate($info['card_date']);
			else
				$check['card_date'] = 1;

			$check['place_cards'] = (int) vali::length(null, 255)->validate($info['place_cards']);

			if ($check['ethnic'] != '')
				$check['ethnic'] = (int) vali::intVal()->validate($info['ethnic']);
			else
				$check['ethnic'] = 1;

			if ($check['religion'] != '')
				$check['religion'] = (int) vali::intVal()->validate($info['religion']);
			else
				$check['religion'] = 1;

			$check['job'] = (int) vali::length(null, 255)->validate($info['job']);

			// $check ['phone'] = ( int ) vali::notEmpty ()->phone ()->length ( 1, 50 )->validate ( $info ['phone'] );
			$check['phone'] = (int) vali::length(null, 50)->validate($info['phone']);

			$check['address'] = (int) vali::length(null, 255)->validate($info['address']);

			$check['nationality'] = (int) vali::length(null, 2)->validate($info['nationality']);

			foreach ($check as $value) {
				if (!$value) {
					$return['msg'] = false;
					break;
				}
			}

			$return['user_info'] = $check;
		} catch (Exception $e) {
			$this->WriteLog("ERR:" . $e->getMessage());
		}

		return $return;
	}

	/**
	 * Validate form data update profile
	 *
	 * @param
	 *        	$data
	 * @return array - msg status
	 */
	protected function validateDataMember($info)
	{

		// check field in form
		$control_name = array(
			'id',
			'email',
			'first_name',
			'last_name',
			'birthday',
			'gender',
			'identity_card',
			'card_date',
			'place_cards',
			'ethnic',
			'religion',
			'job',
			'phone',
			'address',
			'nationality'
		);

		foreach ($control_name as $key) {
			if (!isset($info[$key]))
				$info[$key] = null;
		}

		$check = $return = array();

		$check['email'] = (int) vali::email()->validate($info['email']);

		if ($check['email']) { // Check unique

			$check['email'] = (int) PsEmailModel::checkEmailUnique($info['email'], $info['id'], USER_TYPE_TEACHER);
		}

		$check['first_name'] = (int) vali::notEmpty()->stringType()->length(1, 255)->validate($info['first_name']);

		$check['last_name'] = (int) vali::notEmpty()->stringType()->length(1, 255)->validate($info['last_name']);

		$check['birthday'] = (int) vali::notEmpty()->dateTime('d-m-Y')->between('01-01-1900', 'now')->validate($info['birthday']);

		$check['gender'] = (int) vali::intVal()->between(0, 1)->validate($info['gender']);

		if ($info['identity_card'] != '')
			$check['identity_card'] = (int) vali::stringType()->length(null, 50)->validate($info['identity_card']);
		else
			$check['identity_card'] = 1;

		if ($info['card_date'])
			$check['card_date'] = (int) vali::dateTime('d-m-Y')->between('01-01-1900', 'now')->validate($info['card_date']);
		else
			$check['card_date'] = 1;

		$check['place_cards'] = (int) vali::length(null, 255)->validate($info['place_cards']);

		if ($check['ethnic'] != '')
			$check['ethnic'] = (int) vali::intVal()->validate($info['ethnic']);
		else
			$check['ethnic'] = 1;

		if ($check['religion'] != '')
			$check['religion'] = (int) vali::intVal()->validate($info['religion']);
		else
			$check['religion'] = 1;

		$check['job'] = (int) vali::length(null, 255)->validate($info['job']);

		// $check ['phone'] = ( int ) vali::notEmpty ()->phone ()->length ( 1, 50 )->validate ( $info ['phone'] );
		$check['phone'] = (int) vali::length(null, 50)->validate($info['phone']);

		$check['address'] = (int) vali::length(null, 255)->validate($info['address']);

		$check['nationality'] = (int) vali::length(null, 2)->validate($info['nationality']);

		$return['msg'] = true;

		foreach ($check as $value) {
			if (!$value) {
				$return['msg'] = false;
				break;
			}
		}

		$return['user_info'] = $check;

		return $return;
	}

}
