<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Exception;
use App\PsUtil\PsEndCode;
use App\PsUtil\PsString;
use App\PsUtil\PsI18n;

class DefaultController extends BaseController {
	
	public $apps_id;
	
	public function __construct(LoggerInterface $logger, $renderer, $container) {
		$this->logger = $logger;
		$this->renderer = $renderer;

		if (isset ( $container->apps_id ))
			$this->apps_id = $container->apps_id;
	}
	public function index(RequestInterface $request, ResponseInterface $response, $args) {

		// Sample log message
		$this->WriteLog ();

		// Render index view
		return $this->renderer->render ( $response, 'index.phtml', $args );
		// return $this->view->render($response, 'index.html.twig', $args);
	}

	/**
	 * Check version app
	 */
	public function checkAppVersion(RequestInterface $request, ResponseInterface $response, array $args) {
		$return_data = array ();

		$return_data ['_msg_code'] = MSG_CODE_TRUE;

		$return_data ['_msg_text'] = $return_data ['message'] = '';

		$return_data ['newVersion'] = STATUS_NOT_ACTIVE;
		$return_data ['urlApp'] 	= '';

		$app_id = $request->getHeaderLine ( 'appid' );
		$app_version = $request->getHeaderLine ( 'appversion' );
		$languagecode = $request->getHeaderLine ( 'languagecode' );

		$psI18n = new PsI18n ( $languagecode );

		try {
			if ($app_id == '' || $app_version == '') {
				return $response->withJson ( $return_data );
			} else {

				if (is_array ( $this->apps_id )) {

					$app_store = isset ( $this->apps_id [$app_id] ) ? $this->apps_id [$app_id] : '';
					
					if ($app_id == 'com.kidsschoolteacher.android') {
						return $response->withJson ( $return_data );
					}
					
					if (isset($app_store['version']) && $app_store['version']!= $app_version) {
						$return_data ['newVersion'] = STATUS_ACTIVE;
						$return_data ['urlApp']     = $app_store['urlApp'];
						$return_data ['_msg_text']  = $return_data ['message'] = '<center><font size="5"><strong><font color="#8bd139">'.$app_store['name'].'</font></strong>'.' '.$psI18n->__ ( 'new version available').'<br>'.$psI18n->__('Please update application to new version to continue.' ).'</font></center>';
					}
				}
			}
		} catch ( Exception $e ) {
			$return_data ['_msg_code'] = MSG_CODE_FALSE;			
		}
		
		return $response->withJson ( $return_data );
	}

	/**
	 * get content file image from url and export in API
	 *
	 * folder_type = relative; => phu huynh
	 * folder_type = teacher; => giao vien hoac nhan su cua nha truong
	 */
	public function imageShow(RequestInterface $request, ResponseInterface $response, $args) {
		$tokenAuth = new \Slim\Middleware\TokenAuthentication ();

		$api_token = $tokenAuth->findToken ( $request ); // check from header

		$file_img = isset ( $args ['img'] ) ? $args ['img'] : '';

		$folder_type = isset ( $args ['path_virtual'] ) ? $args ['path_virtual'] : '';

		$ps_code = isset ( $args ['ps_code'] ) ? rawurldecode ( $args ['ps_code'] ) : '';

		$ps_code = PsString::trimString ( PsString::decryptString ( $ps_code, $api_token ) );

		if ($file_img != '' && $folder_type != '' && $ps_code != '') {

			// get content file from server web
			if ($folder_type == 'customer') {

				$url_img = PS_CONST_URL_WEB_SERVER . '/pschool/logo/' . PsEndCode::ps64Decode ( $file_img );

				$headers = get_headers ( $url_img, 1 );

				if (($headers [0] == 'HTTP/1.1 404 Not Found') || ($headers [0] == 'HTTP/1.1 401 Unauthorized')) { // valid
					$url_img = PS_CONST_URL_WEB_SERVER . '/images/no_logo2.png';
					$headers = get_headers ( $url_img, 1 );
				}
			} else {

				// $pschool_code = 'PS'.sprintf("%010s", $ps_code);
				$pschool_code = 'PS' . PsString::renderCode ( "%010s", $ps_code );

				$url_img = PS_CONST_URL_WEB_SERVER . '/pschool/' . $pschool_code . '/' . $folder_type . '/' . PsEndCode::ps64Decode ( $file_img );

				$headers = get_headers ( $url_img, 1 );

				if (($headers [0] == 'HTTP/1.1 404 Not Found') || ($headers [0] == 'HTTP/1.1 401 Unauthorized')) { // valid
					$url_img = PS_CONST_URL_WEB_SERVER . '/images/no_img_avatar.png';
					$headers = get_headers ( $url_img, 1 );
				}
			}

			$imginfo = getimagesize ( $url_img );

			if ($imginfo) {
				header ( "Content-type: {$imginfo['mime']}" );
				echo file_get_contents ( $url_img );
			}
		}
		exit ( 0 );
	}
	
	public function uploadAvatar2(RequestInterface $request, ResponseInterface $response, $args) {
		
		$user = $this->user_token;

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_msg_text' => $psI18n->__ ( 'Upload photos failed.' )
		);
		
		$body = $request->getParsedBody ();
		return $response->withJson ( $body );
		
	}
	
	
	// Update avatar
	public function uploadAvatar(RequestInterface $request, ResponseInterface $response, array $args) {
		/*
		$api_token = $this->user_token;
		
		$return_data = array (
				'_error_code' => 1,
				'_msg_code' => 0
		);
		*/
		$user = $this->user_token;

		$code_lang = $this->getUserLanguage($user);

		$psI18n = new PsI18n($code_lang);

		$return_data = array();

		$return_data['_msg_code'] = MSG_CODE_FALSE;

		
		return $user->user_type;
		//return "AAAAAAAA";
		
		//try {
			
			//print_r($api_token);die;
			
			$user = UserModel::getUserColumnByToken ( $api_token );
			
			$device_id  = $request->getHeaderLine ('deviceid');
			
			// get data from body
			$body = $request->getParsedBody ();
			
			$avatar_type = isset ( $body ['avatar_type'] ) ? $body ['avatar_type'] : '';
			
			if ($avatar_type == ''){
				return $response->withJson ( $return_data );
			}
			
			if ($user && ($user->app_device_id == $device_id)) {
				
				// get device_id app
				$device_id = $request->getHeaderLine ('deviceid');
				
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
				}
				
				//print_r($new_avatar);die;
				
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
						if ($avatar_type == 'profile') {
							
							$school_code 	= $relative->school_code;
							
							$new_file_name 	= 'avatar_'.time () . '.' . PsString::getFileType ( $new_avatar_name );
							
							$new_avatar->moveTo (PS_CONST_PATH_UPLOAD_FILE . $school_code . '/relative/' . $new_file_name);
							
							$relative = RelativeModel::where ( 'id', $user->member_id )->first ();
							
							if ($relative) {
								
								// delete avatar old: $relative->image
								if (PsFile::isCheckFile(PS_CONST_PATH_UPLOAD_FILE . $school_code . '/relative/' . $relative->avatar)) {
									
									if(PsFile::deleteFile (PS_CONST_PATH_UPLOAD_FILE . $school_code . '/relative/' . $relative->avatar)) {
										
										//$relative->image            		= $new_file_name;
										$relative->avatar            		= $new_file_name;
										
										if ($relative->save()) {
											$return_data ['_msg_code']  	= MSG_CODE_TRUE;
											$return_data ['_error_code'] 	= 0;
											
											$user_info->avatar_url 			= PsString::generateUrlImage('relative', $new_file_name, $ps_customer_id, $api_token);
											
											$return_data['_data'] ['user_info'] = $user_info;
											
											
										} else {
											$return_data ['_msg_code']  	= MSG_CODE_FALSE;
										}
									}
								} else {
									
									//$relative->image            		= $new_file_name;
									$relative->avatar            		= $new_file_name;
									
									if ($relative->save()) {
										
										$return_data ['_msg_code']  		= MSG_CODE_TRUE;
										$return_data ['_error_code'] 		= 0;
										$user_info->avatar_url 				= PsString::generateUrlImage('relative', $new_file_name, $ps_customer_id, $api_token);
										
										$return_data['_data'] ['user_info'] = $user_info;
										
									} else {
										$return_data ['_msg_code']  	= MSG_CODE_FALSE;
									}
								}
							}
						} elseif ($avatar_type == 'student') {
							
							$student_id = isset ( $body ['student_id'] ) ? $body ['student_id'] : '';
							
							if ($student_id > 0) {
								
								$new_file_name = 'avatar_'.time () . '.' . PsString::getFileType ( $new_avatar_name );
								
								$student = StudentModel::where ( 'id', $student_id )->first ();
								
								if ($student) {
									
									$school_code = 'PSM'.PsString::renderCode("%010s", $student->ps_customer_id);
									
									$ps_customer_id = $student->ps_customer_id;
									
									$new_avatar->moveTo (PS_CONST_PATH_UPLOAD_FILE . $school_code . '/profile/' . $new_file_name);
									
									// delete avatar old: $student->avatar
									if (PsFile::isCheckFile(PS_CONST_PATH_UPLOAD_FILE . $school_code . '/profile/' . $student->avatar)) {
										
										if(PsFile::deleteFile (PS_CONST_PATH_UPLOAD_FILE . $school_code . '/profile/' . $student->avatar)) {
											
											$student->avatar            		= $new_file_name;
											if ($student->save()) {
												$return_data ['_msg_code']  	= MSG_CODE_TRUE;
												$return_data ['_error_code'] 	= 0;
												
												$user_info->avatar_url 			= PsString::generateUrlImage('profile', $new_file_name, $ps_customer_id, $api_token);
												
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
											
											$user_info->avatar_url 			= PsString::generateUrlImage('profile', $new_file_name, $ps_customer_id, $api_token);
											
											$return_data['_data'] ['user_info'] = $user_info;
											
										} else {
											$return_data ['_msg_code']  	= MSG_CODE_FALSE;
										}
									}
								}
							}
						}
						
					}
				} elseif ($check_file && ($user->user_type == USER_TYPE_TEACHER) && ($avatar_type == 'profile')) {
					
					// Lay thong tin User - Member
					$ps_member = $this->db->table ( CONST_TBL_PS_MEMBER . ' as M' )->select ( 'M.id', 'M.image', 'C.school_code' )->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'M.ps_customer_id' )->where ( 'M.id', (int)$user->member_id)->first ();
					
					if ($ps_member) {
						
						$school_code 	= $ps_member->school_code;
						
						$new_file_name 	= 'avatar_'.time () . '.' . PsString::getFileType ($new_avatar_name );
						
						$new_avatar->moveTo (PS_CONST_PATH_UPLOAD_FILE . $school_code . '/hr/' . $new_file_name);
						
						$ps_member = PsMemberModel::where ('id', $user->member_id )->first ();
						
						// delete avatar old: $ps_member->image
						if (PsFile::isCheckFile(PS_CONST_PATH_UPLOAD_FILE . $school_code . '/hr/' . $ps_member->image)) {
							
							if(PsFile::deleteFile (PS_CONST_PATH_UPLOAD_FILE . $school_code . '/hr/' . $ps_member->image)) {
								
								$ps_member->avatar            		= $new_file_name;
								//$ps_member->image            		= $new_file_name;
								
								if ($ps_member->save()) {
									
									$return_data ['_msg_code']  	= MSG_CODE_TRUE;
									$return_data ['_error_code'] 	= 0;
									
									$user_info->avatar_url 			= PsString::generateUrlImage('hr', $new_file_name, $ps_customer_id, $api_token);
									
									$return_data['_data'] ['user_info'] = $user_info;
									
								} else {
									$return_data ['_msg_code']  	= MSG_CODE_FALSE;
								}
							}
						} else {
							
							$ps_member->avatar            			= $new_file_name;
							//$ps_member->image            			= $new_file_name;
							
							if ($ps_member->save()) {
								
								$return_data ['_msg_code']  		= MSG_CODE_TRUE;
								$return_data ['_error_code'] 		= 0;
								$user_info->avatar_url 				= PsString::generateUrlImage('hr', $new_file_name, $ps_member->ps_customer_id, $api_token);
								
								$return_data['_data'] ['user_info'] = $user_info;
								
							} else {
								$return_data ['_msg_code']  	= MSG_CODE_FALSE;
							}
						}
					}
				}
			}
		// } catch (Exception $e) {
			
			// $this->logger->err ( $e->getMessage () );
			
			// $return_data ['_error_code']    = 1;
			
			// $return_data ['_msg_code']      = MSG_CODE_FALSE;
			
			// $return_data ['_text']          = $e->getMessage ();
		// }
		
		
		return $response->withJson ( $return_data );
	}
	
}