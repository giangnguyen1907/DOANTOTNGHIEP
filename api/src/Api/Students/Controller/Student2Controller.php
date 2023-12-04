<?php

namespace Api\Students\Controller;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Authentication\PsAuthentication;
use App\Controller\BaseController;
use Exception;
use Respect\Validation\Validator as vali;
use App\Model\PsWorkPlacesModel;
use App\Model\PsMobileAppAmountsModel;
use App\Model\PsStudentBmiModel;
use Api\Students\Model\PsFeeNewsLettersModel;
use Api\Students\Model\PsStudentGrowthsModel;
use Api\Students\Model\StudentModel;
use Api\Students\Model\StudentServiceCourseCommentModel;
use Api\Students\Model\FeatureOptionModel;
use Api\Students\Model\PsReceiptModel;
use Api\Students\Model\PsFeeReceiptModel;
use Api\Students\Model\PsFeeReceivableStudentModel;
use Api\PsMembers\Model\PsMemberModel;
use App\PsUtil\PsEndCode;
use App\PsUtil\PsFile;
use App\PsUtil\PsNumber;
use App\PsUtil\PsString;
use App\PsUtil\PsCountry;
use App\PsUtil\PsDateTime;
use App\PsUtil\PsI18n;
use App\PsUtil\PsWebContent;
use Api\Students\Model\PsFeeReportsModel;

class StudentController extends BaseController {

	public $container;

	protected $user_token;

	public function __construct(LoggerInterface $logger, $container, $app) {

		parent::__construct ( $logger, $container );

		$this->user_token = $app->user_token;

	}

	// get information Student - Not check fee mobile
	public function informationStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		// Khong co du lieu student chung => khong co quyen xem thong tin phu huynh nay
		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => '[]'
		);

		$user = $this->user_token;

		$device_id = $request->getHeaderLine ( 'deviceid' );

		// get data from URI
		$student_id = $args ['student_id'];

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		try {

			if ($student_id > 0) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {
					return $response->withJson ( $return_data );
				}

				// Lay thong tin hoc sinh co quan he voi nguoi than
				if ($user->user_type == USER_TYPE_RELATIVE)
					$ps_student = StudentModel::getStudentInfoForRelative ( $student_id, $user->member_id );
				elseif ($user->user_type == USER_TYPE_TEACHER)
					$ps_student = StudentModel::getStudentForTeacher ( $student_id, $user->ps_customer_id );

				if ($ps_student) {

					$user_info = new \stdClass ();

					$user_info->role = $ps_student->is_role != '' ? $ps_student->is_role : 0;
					$user_info->student_code = ($ps_student->student_code != '') ? $ps_student->student_code : '';
					$user_info->first_name = ($ps_student->first_name != '') ? $ps_student->first_name : '';
					$user_info->last_name = ($ps_student->last_name != '') ? $ps_student->last_name : '';
					$user_info->birthday = PsDateTime::toDMY ( $ps_student->birthday );
					$user_info->gender = $ps_student->sex;
					$user_info->common_name = ($ps_student->common_name != '') ? $ps_student->common_name : '';
					$user_info->nick_name = ($ps_student->nick_name != '') ? $ps_student->nick_name : '';
					$user_info->ethnic = ($ps_student->ethnic != '') ? $ps_student->ethnic : '';
					$user_info->religion = ($ps_student->religion != '') ? $ps_student->religion : '';
					$user_info->status = $ps_student->status;
					$user_info->address = ($ps_student->address != '') ? $ps_student->address : '';
					$user_info->nationality = ($ps_student->nationality != '') ? $ps_student->nationality : '';
					$user_info->class_name = ($ps_student->class_name != '') ? $ps_student->class_name : '';
					$user_info->class_id = $ps_student->class_id;

					if ($ps_student->wp_name != '') {
						$user_info->school_name = ( string ) $ps_student->wp_name;
					} else {
						$user_info->school_name = ($ps_student->school_name != '') ? ( string ) $ps_student->school_name : '';
					}

					$user_info->school_id = $ps_student->ps_customer_id;

					//$user_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR_STUDENT;
					
					if ($ps_student->avatar != '') {
						
						$user_info->avatar_url = PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT );
						
						if (!PsFile::urlExists($user_info->avatar_url)) {
							if ($ps_student->sex == STATUS_ACTIVE) {
								$user_info->avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE.'change_boy_avatar_default.png';
							} else {
								$user_info->avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE.'change_girl_avatar_default.png';
							}
						}
						
					} else {
						if ($ps_student->sex == STATUS_ACTIVE) {
							$user_info->avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE.'change_boy_avatar_default.png';
						} else {
							$user_info->avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE.'change_girl_avatar_default.png';
						}
					}
					
					$user_info->school_logo_url = PsString::getUrlLogoPsCustomer ( $ps_student->year_data, $ps_student->logo );
					
					if (!PsFile::urlExists($user_info->school_logo_url)) {
						$user_info->school_logo_url = PS_CONST_API_URL_IMAGE_DEFAULT_NOTLOGO;
					}

					$countries = new PsCountry ();

					if (isset ( $countries::$country_array [$user_info->nationality] ))
						$user_info->nationality = $countries::$country_array [$user_info->nationality];

					$return_data = array (
							'_msg_code' => MSG_CODE_TRUE
					);
					$return_data ['_data'] ['user_info'] = $user_info;
				}
			}
		} catch ( Exception $e ) {

			// Log
			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	/**
	 * Chỉ số tăng trưởng chiều cao
	 */
	public function growthHeightStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => '[]'
		);

		$user = $this->user_token;

		$user_app_config = json_decode ( $user->app_config );

		$app_config_color = (isset ( $user_app_config->style ) && $user_app_config->style != '') ? $user_app_config->style : 'green';

		if ($app_config_color == 'yellow_orange')
			$app_config_color = 'orange';

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		// get data from URI
		$student_id = $args ['student_id'];

		try {

			if ($student_id > 0) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {

					return $response->withJson ( $return_data );
				}

				if ($user->user_type == USER_TYPE_RELATIVE) {

					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {

						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);

						return $response->withJson ( $return_data );
					}

					$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

					$_data = array ();

					if ($ps_student) {
						$user_info = new \stdClass ();
						$user_info->student_id = ( int ) $ps_student->id;
						$user_info->birthday = ( string ) PsDateTime::toDMY ( $ps_student->birthday );
						$user_info->first_name = $ps_student->first_name;
						$user_info->last_name = $ps_student->last_name;
						$user_info->class_id = ( int ) $ps_student->class_id;
						$user_info->class_name = ($ps_student->class_name != '') ? $ps_student->class_name : '';
						// $user_info->avatar_url = PsString::generateUrlImage ( 'profile', $ps_student->avatar, $ps_student->ps_customer_id, $api_token );
						$user_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

						$_data ['user_info'] = $user_info;

						// get all growth of student
						// $ps_student_growths = PsStudentGrowthsModel::select('student_id', 'height', 'weight', 'input_date_at', 'index_height', 'index_weight', 'index_age')->where('student_id', '=', $student_id)->orderBy('index_age', 'desc')->get();

						$ps_student_growths = PsStudentGrowthsModel::getPsStudentGrowths ( $student_id );

						$_growth_info = array ();
						
						$check_height = 0;

						foreach ( $ps_student_growths as $obj ) {
							
							if ($obj->height > 0) {
								
								$growth_info = array ();
	
								$growth_info ['student_id'] = ( int ) $obj->student_id;
								$growth_info ['_age'] = $obj->index_age . " " . $psI18n->__ ( 'Month' );
	
								$growth_info ['_height'] = PsNumber::number_clean ( $obj->height ) . ' ' . UNIT_HEIGHT;
								
								$check_height = $check_height + $obj->height;
	
								switch ($obj->index_height) {
									case - 2 :
										$growth_info ['_index_height'] = $psI18n->__ ( 'Stunting level 2' );
										break;
									case - 1 :
										$growth_info ['_index_height'] = $psI18n->__ ( 'Stunting level 1' );
										break;
									case 0 :
										$growth_info ['_index_height'] = $psI18n->__ ( 'Normal height' );
										break;
									case 1 :
										$growth_info ['_index_height'] = $psI18n->__ ( 'Higher than age' );
										break;
									default :
										$growth_info ['_index_height'] = $psI18n->__ ( 'Normal height' );
								}
								array_push ( $_growth_info, $growth_info );
							}
						}

						if (count ( $ps_student_growths ) > 0 && $check_height > 0) {

							$web_content = PsWebContent::BeginHTMLPage ();

							$web_content .= '<div style="margin-bottom:15px;">';

							$web_content .= '<table class="w3-table">';

							$web_content .= '<thead>
												<tr class="w3-text-' . $app_config_color . ' w3-border-bottom w3-border-' . $app_config_color . '">
													<th scope="col">' . $psI18n->__ ( 'Age' ) . '</th><th scope="col" class="w3-center">' . $psI18n->__ ( 'Height' ) . '</th><th scope="col" class="w3-center">' . $psI18n->__ ( 'Evaluate' ) . '</th>
												</tr>
											</thead>';

							$web_content .= '<tbody>';

							foreach ( $ps_student_growths as $obj ) {
								
								if ($obj->height > 0) {
									
									$growth_info = array ();
									
									$growth_info ['student_id'] = ( int ) $obj->student_id;
									$growth_info ['_age'] = $obj->index_age . " " . $psI18n->__ ( 'Month' );
									
									$growth_info ['_height'] = PsNumber::number_clean ( $obj->height ) . ' ' . UNIT_HEIGHT;
									
									switch ($obj->index_height) {
										case - 2 :
											$_index_height = $psI18n->__ ( 'Stunting level 2' );
											break;
										case - 1 :
											$_index_height = $psI18n->__ ( 'Stunting level 1' );
											break;
										case 0 :
											$_index_height = $psI18n->__ ( 'Normal height' );
											break;
										case 1 :
											$_index_height = $psI18n->__ ( 'Higher than age' );
											break;
										default :
											$_index_height = $psI18n->__ ( 'Normal height' );
									}
									
									$web_content .= '<tr><td colspan="3" style="font-style:italic;font-weight:bolder;"><small>' . $psI18n->__ ( 'Day care' ) . ' ' . PsDateTime::toDMY ( $obj->input_date_at ) . '</small></td></tr>';
									
									$web_content .= '<tr class="w3-border-bottom">
													<td><small>' . $obj->index_age . " " . $psI18n->__ ( 'Month' ) . '</small></td>
													<td class="w3-center"><small>' . PsNumber::number_clean ( $obj->height ) . ' ' . UNIT_HEIGHT . '</small></td>
													<td class="w3-center"><small>' . $_index_height . '</small></td>
												  </tr>';
								}

							}
							
							if ($ps_student_growths [0]->index_tooth != '' || $ps_student_growths [0]->index_throat || $ps_student_growths [0]->index_eye || $ps_student_growths [0]->index_heart || $ps_student_growths [0]->index_lung || $ps_student_growths [0]->index_skin) {

								$web_content .= '<tr><td colspan="3" style="padding-top:10px;"><span style="font-weight:bolder;" class="w3-text-' . $app_config_color . '">' . $psI18n->__ ( 'General health information' ) . '</span>';
								$web_content .= '<div><small>' . $psI18n->__ ( 'Recent examination date' ) . ': ' . PsDateTime::toDMY ( $ps_student_growths [0]->input_date_at ) . '</small></div>';
	
								$web_content .= '</td></tr>';
								
								$web_content .= '<tr>
							                      <td colspan="2" width="50%"><strong><small>Răng - Hàm - Mặt</small></strong><div><small>'.$ps_student_growths [0]->index_tooth.'</small></div></td>
							                      <td width="50%"><strong><small>Tai - Mũi - Họng</small></strong><div><small>'.$ps_student_growths [0]->index_throat.'</small></div></td>
							                    </tr>';
								
								$web_content .= '<tr>
							                      <td colspan="2" width="50%"><strong><small>Mắt</small></strong><div><small>'.$ps_student_growths [0]->index_eye.'</small></div></td>
							                      <td width="50%"><strong><small>Tim</small></strong><div><small>'.$ps_student_growths [0]->index_heart.'</small></div></td>
							                    </tr>';
								
								$web_content .= '<tr>
							                      <td colspan="2" width="50%"><strong><small>Phổi</small></strong><div><small>'.$ps_student_growths [0]->index_lung.'</small></div></td>
							                      <td width="50%"><strong><small>Da</small></strong><div><small>'.$ps_student_growths [0]->index_skin.'</small></div></td>
							                    </tr>';							
							}
							
							$web_content .= '</tbody>';
							$web_content .= '</table>';
							$web_content .= '</div>';							

							$web_content .= PsWebContent::EndHTMLPage ();
							
						} else {
							$web_content = PsWebContent::BeginHTMLPage ();
							$web_content .= '<div class="w3-padding-16 w3-text-red w3-center">' . $psI18n->__ ( 'Baby has no information about Height.' ) . '</div>';
							$web_content .= PsWebContent::EndHTMLPage ();
						}

						// $_data ['growth_info'] = $_growth_info;

						$_data ['content'] = $web_content;
						
						if ($user->username == 'nguyenphuong') {
							//echo $web_content;die;
						}

						$return_data = array (
								'_msg_code' => MSG_CODE_TRUE,
								'_data' => $_data
						);
					} else {
						$return_data ['_msg_code'] = MSG_CODE_500;
						$return_data ['message'] = $psI18n->__ ( 'You can not see the student\'s information.' );
					}
				}
			}
		} catch ( Exception $e ) {

			// Log
			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	// Chỉ số tăng trưởng cân nặng
	public function growthWeightStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		// Log message
		// $this->WriteLog("Growth weight Student");
		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => '[]'
		);

		$user = $this->user_token;

		$user_app_config = json_decode ( $user->app_config );

		$app_config_color = (isset ( $user_app_config->style ) && $user_app_config->style != '') ? $user_app_config->style : 'green';

		if ($app_config_color == 'yellow_orange')
			$app_config_color = 'orange';

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		// get data from URI
		$student_id = $args ['student_id'];

		try {

			if ($student_id > 0) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {

					return $response->withJson ( $return_data );
				}

				if ($user->user_type == USER_TYPE_RELATIVE) {

					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {

						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);

						return $response->withJson ( $return_data );
					}

					$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

					$_data = array ();

					if ($ps_student) {
						$user_info = new \stdClass ();
						$user_info->student_id = ( int ) $ps_student->id;
						$user_info->birthday = ( string ) PsDateTime::toDMY ( $ps_student->birthday );
						$user_info->first_name = $ps_student->first_name;
						$user_info->last_name = $ps_student->last_name;
						$user_info->class_id = ( int ) $ps_student->class_id;
						$user_info->class_name = ($ps_student->class_name != '') ? $ps_student->class_name : '';
						$user_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
						$_data ['user_info'] = $user_info;

						// get all growth of student
						// $ps_student_growths = PsStudentGrowthsModel::select('student_id', 'weight', 'input_date_at', 'index_weight', 'index_age')->where('student_id', '=', $student_id)->orderBy('index_age', 'desc')->get();

						$ps_student_growths = PsStudentGrowthsModel::getPsStudentGrowths ( $student_id );
						
						$check_weight = 0;
						
						foreach ( $ps_student_growths as $obj ) {
							$check_weight = $check_weight + $obj->weight;
						}

						if (count ( $ps_student_growths ) > 0 && $check_weight > 0) {

							$web_content = PsWebContent::BeginHTMLPage ();

							$web_content .= '<div>';

							$web_content .= '<table class="w3-table">';

							$web_content .= '<thead>
												<tr class="w3-text-' . $app_config_color . ' w3-border-bottom w3-border-' . $app_config_color . '">
													<th scope="col">' . $psI18n->__ ( 'Age' ) . '</th><th scope="col" class="w3-center">' . $psI18n->__ ( 'Weight' ) . '</th><th scope="col" class="w3-center">' . $psI18n->__ ( 'Evaluate' ) . '</th>
												</tr>
											</thead>';

							$web_content .= '<tbody>';

							foreach ( $ps_student_growths as $obj ) {

								/*
								 * $growth_info = array();
								 *
								 * $growth_info['student_id'] = (int) $obj->student_id;
								 * $growth_info['_age'] = $obj->index_age . " " . $psI18n->__('Month');
								 * $growth_info['_weight'] = PsNumber::number_clean($obj->weight) . ' ' . UNIT_WEIGHT;
								 */
								
								if ($obj->weight > 0) {

									switch ($obj->index_weight) {
										case - 2 :
											$_index_weight = $psI18n->__ ( 'Severe malnutrition' );
											break;
										case - 1 :
											$_index_weight = $psI18n->__ ( 'Moderate malnutrition' );
											break;
										case 0 :
											$_index_weight = $psI18n->__ ( 'Normal weight' );
											break;
										case 1 :
											$_index_weight = $psI18n->__ ( 'Weight is higher than age' );
											break;
										default :
											$_index_weight = $psI18n->__ ( 'Normal weight' );
									}
	
									$web_content .= '<tr><td colspan="3" style="font-style:italic;font-weight:bolder;"><small>' . $psI18n->__ ( 'Day care' ) . ' ' . PsDateTime::toDMY ( $obj->input_date_at ) . '</small></td></tr>';
	
									$web_content .= '<tr class="w3-border-bottom">
														<td><small>' . $obj->index_age . " " . $psI18n->__ ( 'Month' ) . '</small></td>
														<td class="w3-center"><small>' . PsNumber::number_clean ( $obj->weight ) . ' ' . UNIT_WEIGHT . '</small></td>
														<td class="w3-center"><small>' . $_index_weight . '</small></td>
													  </tr>';
								}
							}

							//$web_content .= '<tr><td colspan="3" style="padding-top:10px;"><span style="font-weight:bolder;" class="w3-text-' . $app_config_color . '">' . $psI18n->__ ( 'General health information' ) . '</span><br/>';
							//$web_content .= '<p><small>' . $psI18n->__ ( 'Recent examination date' ) . ': ' . PsDateTime::toDMY ( $ps_student_growths [0]->input_date_at ) . '</small></p>';

							//$web_content .= '</td></tr>';
							
							if ($ps_student_growths [0]->index_tooth != '' || $ps_student_growths [0]->index_throat || $ps_student_growths [0]->index_eye || $ps_student_growths [0]->index_heart || $ps_student_growths [0]->index_lung || $ps_student_growths [0]->index_skin) {
								
								$web_content .= '<tr><td colspan="3" style="padding-top:10px;"><span style="font-weight:bolder;" class="w3-text-' . $app_config_color . '">' . $psI18n->__ ( 'General health information' ) . '</span>';
								$web_content .= '<div><small>' . $psI18n->__ ( 'Recent examination date' ) . ': ' . PsDateTime::toDMY ( $ps_student_growths [0]->input_date_at ) . '</small></div>';
								
								$web_content .= '</td></tr>';
								
								$web_content .= '<tr>
							                      <td colspan="2" width="50%"><strong><small>Răng - Hàm - Mặt</small></strong><div><small>'.$ps_student_growths [0]->index_tooth.'</small></div></td>
							                      <td width="50%"><strong><small>Tai - Mũi - Họng</small></strong><div><small>'.$ps_student_growths [0]->index_throat.'</small></div></td>
							                    </tr>';
								
								$web_content .= '<tr>
							                      <td colspan="2" width="50%"><strong><small>Mắt</small></strong><div><small>'.$ps_student_growths [0]->index_eye.'</small></div></td>
							                      <td width="50%"><strong><small>Tim</small></strong><div><small>'.$ps_student_growths [0]->index_heart.'</small></div></td>
							                    </tr>';
								
								$web_content .= '<tr>
							                      <td colspan="2" width="50%"><strong><small>Phổi</small></strong><div><small>'.$ps_student_growths [0]->index_lung.'</small></div></td>
							                      <td width="50%"><strong><small>Da</small></strong><div><small>'.$ps_student_growths [0]->index_skin.'</small></div></td>
							                    </tr>';
							}

							$web_content .= '</tbody>';
							$web_content .= '</table>';
							$web_content .= '</div>';
							$web_content .= PsWebContent::EndHTMLPage ();
						} else {
							$web_content = PsWebContent::BeginHTMLPage ();
							$web_content .= '<div class="w3-padding-16 w3-text-red w3-center">' . $psI18n->__ ( 'Baby has no information about Weight.' ) . '</div>';
							$web_content .= PsWebContent::EndHTMLPage ();
						}

						$_data ['content'] = $web_content;

						$return_data = array (
								'_msg_code' => MSG_CODE_TRUE,
								'_data' => $_data
						);
					} else {
						$return_data ['_msg_code'] = MSG_CODE_500;
						$return_data ['message'] = $psI18n->__ ( 'Bạn không có quyền xem thông tin của học sinh này.' );
					}
				}
			}
		} catch ( Exception $e ) {

			// Log
			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	// Biểu đồ chiều cao
	public function growthChartHeightStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => '[]'
		);

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		// get data from URI
		$student_id = $args ['student_id'];

		try {

			if ($student_id > 0) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {

					return $response->withJson ( $return_data );
				}

				if ($user->user_type == USER_TYPE_RELATIVE) {

					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {

						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);

						return $response->withJson ( $return_data );
					}

					$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

					$_data = array ();

					if ($ps_student) {
						$user_info = new \stdClass ();
						$user_info->student_id = ( int ) $ps_student->id;
						$user_info->birthday = ( string ) PsDateTime::toDMY ( $ps_student->birthday );
						$user_info->first_name = ( string ) $ps_student->first_name;
						$user_info->last_name = ( string ) $ps_student->last_name;
						$user_info->class_id = ( int ) $ps_student->class_id;
						$user_info->class_name = ($ps_student->class_name != '') ? ( string ) $ps_student->class_name : '';
						$user_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

						$_data ['user_info'] = $user_info;

						// get all growth of student
						$ps_student_growths = PsStudentGrowthsModel::getPsStudentGrowths ( $student_id, 'asc' );

						$_min_age = 0;
						$_max_age = 0;
						$_max_bmi_month = 0;

						$top_lable = ($ps_student->sex == 0) ? $psI18n->__ ( 'Chart height/length for age baby girl' ) : $psI18n->__ ( 'Chart height/length for age baby boy' );
						$_x_lable = $psI18n->__ ( 'Month age' );
						$_y_lable = $psI18n->__ ( 'Height/Length' );

						$_data_student_growths_chart = array ();

						$index = 0;

						$_data_student_growths = array ();
						
						$check_height = 0;

						foreach ( $ps_student_growths as $obj ) {
							
							if ($obj->height > 0) {

								$_min_age = $obj->min_age;
	
								$_max_age = $obj->max_age;
								
								$check_height = $check_height + $obj->height;
	
								$_data_student_growths_chart ['month_age'] [$index] = ( int ) $obj->index_age;
	
								$_data_student_growths_chart ['height'] [$index] = ( float ) $obj->height;
	
								$index ++;
	
								$_data_x_y = '{x:' . ( int ) $obj->index_age . ',y:' . ( float ) $obj->height . '}';
	
								array_push ( $_data_student_growths, $_data_x_y );
							}
						}

						if (count ( $ps_student_growths ) <= 0 || $check_height <= 0) {
							$web_content = PsWebContent::BeginHTMLPage ();
							$web_content .= '<div class="w3-padding-16 w3-text-red w3-center">' . $psI18n->__ ( 'Baby has no information about Height.' ) . '</div>';
							$web_content .= PsWebContent::EndHTMLPage ();
						} else {

							$ps_bmis = $this->db->table ( TBL_PS_STUDENT_BMI )
							->selectRaw ( 'id AS id, min_height1 AS min_height1, min_height AS min_height, max_height AS max_height,max_height1 AS max_height1, is_month AS is_month, medium_height AS medium_height, medium_weight AS medium_weight' )
							->where ( 'sex', ( int ) $ps_student->sex )
							->groupBy ( 'id' )
							->orderBy ( 'is_month' )->get ();

							$max_bmi_month = PsStudentBmiModel::getMaxBmiMonth ( $ps_student->sex );

							if (count ( $max_bmi_month ) > 0) {
								$_max_bmi_month = $max_bmi_month->max_bmi_month;
							}

							$start_x = (($_min_age - 5) > 0) ? ($_min_age - 5) : $_min_age;

							$_60_month = 60;

							if ($_max_age <= $_60_month) {

								$end_x = ($_max_age + 5 > $_60_month) ? $_60_month : $_max_age;
							} else {

								$end_x = (($_max_age + 10) <= $_max_bmi_month) ? ($_max_age + 10) : $_max_bmi_month;
							}

							// Data BMI chuẩn để vẽ
							$_bmi_data_chart = array ();

							$index = 0;
							
							foreach ( $ps_bmis as $bmi ) {
								
								if (($bmi->is_month >= $start_x) && ($bmi->is_month <= $end_x)) {

									$_bmi_data_chart ['index_x_month'] [$index] = ( int ) $bmi->is_month;

									// -3SD
									if ($bmi->min_height1 > 0)
										$_bmi_data_chart ['index_min_3SD'] [$index] = '{x:' . ( int ) $bmi->is_month . ',y:' . ( float ) $bmi->min_height1 . '}';

									// +3SD
									if ($bmi->max_height1 > 0)
										$_bmi_data_chart ['index_max_3SD'] [$index] = '{x:' . ( int ) $bmi->is_month . ',y:' . ( float ) $bmi->max_height1 . '}';

									// -2SD
									if ($bmi->min_height > 0)
										$_bmi_data_chart ['index_min_2SD'] [$index] = '{x:' . ( int ) $bmi->is_month . ',y:' . ( float ) $bmi->min_height . '}';

									// +2SD
									if ($bmi->max_height > 0)
										$_bmi_data_chart ['index_max_2SD'] [$index] = '{x:' . ( int ) $bmi->is_month . ',y:' . ( float ) $bmi->max_height . '}';

									if ($bmi->medium_height > 0)
										$_bmi_data_chart ['index_medium'] [$index] = '{x:' . ( int ) $bmi->is_month . ',y:' . ( float ) $bmi->medium_height . '}';

									$index ++;
								}
							}

							/**
							 * Lấy thông số để thiết lập tháng bắt đầu và kết thúc của biểu đồ vẽ
							 *
							 * Chọn cận cho trục X là 5;
							 *
							 * Nếu min_age - 5 > 0 => start_x = min_age - 5 trái lại start_x = min_age
							 *
							 * Nếu max_age + 5 <= $_max_bmi_month => start_x = max_age - 5
							 */
							$web_content = PsWebContent::BeginHTMLPageChart ();
							$web_content .= '<div class="w3-padding-16" style="padding:0px;">';
							$web_content .= PsWebContent::ChartCanvas ( '100%' );
							$web_content .= PsWebContent::ChartCanvasPsBMI ( $_bmi_data_chart, $_data_student_growths, $top_lable, $_x_lable, $_y_lable, $start_x );
							$web_content .= '</div>';

							$web_content .= '<div class="w3-container w3-responsive" style="margin:0 auto; text-align:center; padding-top:0px;padding-bottom:5px;">
												<table width="90%" align="center" class="w3-table w3-bordered">
												  <tr>
													<td style="width:30%;vertical-align:middle;padding-left:0px;">
														<div style="border:8px solid #2196F3;"></div>
													</td>
													<td style="width:70%;padding:0xp;">' . $psI18n->__ ( 'Baby\'s straight line' ) . '</td>
												  </tr>
												  <tr>
													<td style="width:30%;vertical-align:middle;padding-left:0px;">
														<div style="border:8px solid #C6F1FF;"></div>
													</td>
													<td style="width:70%;padding:0xp;">' . $psI18n->__ ( 'Normal height' ) . '</td>
												  </tr>
												  <tr>
													<td style="width:30%;vertical-align:middle;padding-left:0px;">
														<div style="border:8px solid rgb(255, 248, 175);"></div>
													</td>
													<td style="width:70%;padding:0xp;">' . $psI18n->__ ( 'Higher than age' ) . '</td>
												  </tr>
												  
												  <tr>
													<td style="width:30%;vertical-align:middle;padding-left:0px;">
														<div style="border:8px solid rgb(248, 169, 128);"></div>
													</td>
													<td style="width:70%;padding:0xp;">' . $psI18n->__ ( 'Stunting level 1' ) . '</td>
												  </tr>
												  <tr>
													<td style="width:30%;vertical-align:middle;padding-left:0px;">
														<div style="border:8px solid rgb(255, 125, 125);"></div>
													</td>
													<td style="width:70%;padding:0xp;">' . $psI18n->__ ( 'Stunting level 2' ) . '</td>
												  </tr>
												</table>			
											</div>';

							$web_content .= PsWebContent::EndHTMLPage ();
						}

						// $_data ['_bmi_data_chart'] = $_bmi_data_chart;

						$_data ['content'] = $web_content;

						$return_data = array (
								'_msg_code' => MSG_CODE_TRUE,
								'_data' => $_data
						);
					}
				}
			}
		} catch ( Exception $e ) {

			// Log
			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	// Biểu đồ chỉ số tăng trưởng cân nặng
	public function growthChartWeightStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => '[]'
		);

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		// get data from URI
		$student_id = $args ['student_id'];

		try {

			if ($student_id > 0) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {

					return $response->withJson ( $return_data );
				}

				if ($user->user_type == USER_TYPE_RELATIVE) {

					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {

						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);

						return $response->withJson ( $return_data );
					}

					$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

					$_data = array ();

					if ($ps_student) {
						$user_info = new \stdClass ();
						$user_info->student_id = ( int ) $ps_student->id;
						$user_info->birthday = ( string ) PsDateTime::toDMY ( $ps_student->birthday );
						$user_info->first_name = $ps_student->first_name;
						$user_info->last_name = $ps_student->last_name;
						$user_info->class_id = ( int ) $ps_student->class_id;
						$user_info->class_name = ($ps_student->class_name != '') ? $ps_student->class_name : '';
						$user_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

						$_data ['user_info'] = $user_info;

						// get all growth of student
						$ps_student_growths = PsStudentGrowthsModel::getPsStudentGrowths ( $student_id, 'asc' );

						$_min_age = 0;
						$_max_age = 0;
						$_max_bmi_month = 0;

						$top_lable = ($ps_student->sex == 0) ? $psI18n->__ ( 'Weight for age baby girl' ) : $psI18n->__ ( 'Weight for age baby boy' );
						$_x_lable = $psI18n->__ ( 'Month age' );
						$_y_lable = $psI18n->__ ( 'Weight (kg)' );

						$_data_student_growths_chart = array ();

						$index = 0;

						$_data_student_growths = array ();
						
						$check_weight = 0;

						foreach ( $ps_student_growths as $obj ) {
							
							if ($obj->weight > 0) {
								$check_weight = $check_weight + $obj->weight;
		
								$_min_age = $obj->min_age;
	
								$_max_age = $obj->max_age;
	
								$_data_student_growths_chart ['month_age'] [$index] = ( int ) $obj->index_age;
	
								$_data_student_growths_chart ['weight'] [$index] = ( float ) $obj->weight;
	
								$index ++;
	
								$_data_x_y = '{x:' . ( int ) $obj->index_age . ',y:' . ( float ) $obj->weight . '}';
	
								array_push ( $_data_student_growths, $_data_x_y );
							}
						}

						if (count ( $ps_student_growths ) <= 0 || $check_weight <= 0) {
							$web_content = PsWebContent::BeginHTMLPage ();
							$web_content .= '<div class="w3-padding-16 w3-text-red w3-center">' . $psI18n->__ ( 'Baby has no information about Weight.' ) . '</div>';
							$web_content .= PsWebContent::EndHTMLPage ();
						} else {

							$ps_bmis = $this->db->table ( TBL_PS_STUDENT_BMI )->selectRaw ( 'id AS id,min_weight1 AS min_weight1, min_weight AS min_weight, max_weight AS max_weight,max_weight1 AS max_weight1, is_month AS is_month, medium_weight AS medium_weight' )->where ( 'sex', ( int ) $ps_student->sex )->groupBy ( 'id' )->orderBy ( 'is_month' )->get ();

							$max_bmi_month = PsStudentBmiModel::getMaxBmiMonth ( $ps_student->sex );

							if (count ( $max_bmi_month ) > 0) {
								$_max_bmi_month = $max_bmi_month->max_bmi_month;
							}

							$start_x = (($_min_age - 5) > 0) ? ($_min_age - 5) : $_min_age;

							$_60_month = 60;

							if ($_max_age <= $_60_month) {

								$end_x = ($_max_age + 8 > $_60_month) ? $_60_month : ($_max_age + 2);
								
								//$end_x = ($_max_age + 8 > $_60_month) ? $_60_month : ($_60_month - $_min_age);
								
							} else {

								$end_x = (($_max_age + 10) <= $_max_bmi_month) ? ($_max_age + 10) : $_max_bmi_month;
							}

							// Data BMI chuẩn để vẽ
							$_bmi_data_chart = array ();

							$index = 0;

							foreach ( $ps_bmis as $bmi ) {

								if (($bmi->is_month >= $start_x) && ($bmi->is_month <= $end_x)) {

									$_bmi_data_chart ['index_x_month'] [$index] = ( int ) $bmi->is_month;

									// -3SD
									if ($bmi->min_weight1 > 0)
										$_bmi_data_chart ['index_min_3SD'] [$index] = '{x:' . ( int ) $bmi->is_month . ',y:' . ( float ) $bmi->min_weight1 . '}';

									// +3SD
									if ($bmi->max_weight1 > 0)
										$_bmi_data_chart ['index_max_3SD'] [$index] = '{x:' . ( int ) $bmi->is_month . ',y:' . ( float ) $bmi->max_weight1 . '}';

									// -2SD
									if ($bmi->min_weight > 0)
										$_bmi_data_chart ['index_min_2SD'] [$index] = '{x:' . ( int ) $bmi->is_month . ',y:' . ( float ) $bmi->min_weight . '}';

									// +2SD
									if ($bmi->max_weight > 0)
										$_bmi_data_chart ['index_max_2SD'] [$index] = '{x:' . ( int ) $bmi->is_month . ',y:' . ( float ) $bmi->max_weight . '}';

									if ($bmi->medium_weight > 0)
										$_bmi_data_chart ['index_medium'] [$index] = '{x:' . ( int ) $bmi->is_month . ',y:' . ( float ) $bmi->medium_weight . '}';

									$index ++;
								}
							}

							$web_content = PsWebContent::BeginHTMLPageChart ();
							$web_content .= '<div class="w3-padding-16" style="padding:0px;">';
							$web_content .= PsWebContent::ChartCanvas ( '100%' );
							$web_content .= PsWebContent::ChartCanvasPsBMI ( $_bmi_data_chart, $_data_student_growths, $top_lable, $_x_lable, $_y_lable, $start_x );
							$web_content .= '</div>';

							$web_content .= '<div class="w3-container w3-responsive" style="margin:0 auto; text-align:center; padding-top:0px;padding-bottom:5px;">
												<table width="90%" align="center" class="w3-table w3-bordered">
												  <tr>
													<td style="width:30%;vertical-align:middle;padding-left:0px;">
														<div style="border:8px solid #2196F3;"></div>
													</td>
													<td style="width:70%;padding:0xp;">' . $psI18n->__ ( 'Baby\'s straight line' ) . '</td>
												  </tr>
												  <tr>
													<td style="width:30%;vertical-align:middle;padding-left:0px;">
														<div style="border:8px solid #C6F1FF;"></div>
													</td>
													<td style="width:70%;padding:0xp;">' . $psI18n->__ ( 'Normal weight' ) . '</td>
												  </tr>
												  <tr>
													<td style="width:30%;vertical-align:middle;padding-left:0px;">
														<div style="border:8px solid rgb(255, 248, 175);"></div>
													</td>
													<td style="width:70%;padding:0xp;">' . $psI18n->__ ( 'Weight is higher than age' ) . '</td>
												  </tr>
												  
												  <tr>
													<td style="width:30%;vertical-align:middle;padding-left:0px;">
														<div style="border:8px solid rgb(248, 169, 128);"></div>
													</td>
													<td style="width:70%;padding:0xp;">' . $psI18n->__ ( 'Moderate malnutrition' ) . '</td>
												  </tr>
												  <tr>
													<td style="width:30%;vertical-align:middle;padding-left:0px;">
														<div style="border:8px solid rgb(255, 125, 125);"></div>
													</td>
													<td style="width:70%;padding:0xp;">' . $psI18n->__ ( 'Severe malnutrition' ) . '</td>
												  </tr>
												</table>			
											</div>';
							$web_content .= PsWebContent::EndHTMLPage ();
						}

						$_data ['content'] = $web_content; // 'Biểu đồ cân nặng';

						$return_data = array (
								'_msg_code' => MSG_CODE_TRUE,
								'_data' => $_data
						);
					}
				}
			}
		} catch ( Exception $e ) {

			// Log
			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	/**
	 * Screen diaryAttendanceMonthStudent - Xem nhat ky diem danh cua 1 thang
	 */
	public function diaryAttendanceMonthStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		$return_data = array ();
		$return_data ['_msg_code'] 			= MSG_CODE_TRUE;
		$return_data ['_data'] ['title'] 	= $psI18n->__ ('Attendance');
		$return_data ['_data'] ['content'] 	= $psI18n->__ ('Diary of attendance month baby.');

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		// get data from URI
		$student_id = $args ['student_id'];

		$month = $args ['month'];

		if ($user->user_type != USER_TYPE_RELATIVE || $student_id <= 0) {

			$return_data ['_data'] ['content'] = $psI18n->__ ( 'You do not have access to this data' );

			return $response->withJson ( $return_data );
		}

		try {

			if (! PsAuthentication::checkDeviceUserRelative ( $user, $device_id )) {

				return $response->withJson ( $return_data );
			}

			$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

			if (! $amount_info) {

				$return_data = array (
						'_msg_code' => MSG_CODE_PAYMENT,
						'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
				);

				return $response->withJson ( $return_data );
			}
			
			// Set style for view HTML
			$user_app_config = json_decode ( $user->app_config );
			
			$app_config_color = (isset ( $user_app_config->style ) && $user_app_config->style != '') ? $user_app_config->style : 'green';
			
			if ($app_config_color == 'yellow_orange')
				$app_config_color = 'orange';

			$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );
			
			$web_content = '';
			
			if ($ps_student) {
				
				$ps_student_info = StudentModel::studentInfo ( $ps_student );
				/*
				if ($user->ps_customer_id == 6) {
					$web_content = $this->setUIDiaryOfStudent($month, $psI18n,$code_lang, $ps_student, $ps_student_info, $app_config_color);
				} else {
					$web_content = $this->setUIDiaryDetailOfStudent($month, $psI18n,$code_lang, $ps_student, $ps_student_info, $app_config_color);
				}
				*/
				
				$web_content = $this->setUIDiaryDetailOfStudent($month, $psI18n,$code_lang, $ps_student, $ps_student_info, $app_config_color);
				
			} else {
				$web_content = PsWebContent::BeginHTMLPage();
				$web_content .= '<div class="w3-padding-16 w3-text-red w3-center">' . $psI18n->__ ( 'This content is no longer available.' ) . '</div>';
				$web_content = PsWebContent::EndHTMLPage();
			}
			
			$return_data ['_data'] ['content'] = $web_content;
		
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	/**
	 * Get diary student today
	 */
	public function diaryStudentAndroid(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'next_page' => '',
				'_data' => '[]'
		);

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		// get data from URI
		$student_id = ( int ) $args ['student_id'];
		$page = isset ( $args ['page'] ) ? ( int ) $args ['page'] : 1;

		try {

			if ($student_id > 0 && $page >= 1) {

				// get device_id app
				$device_id = $request->getHeaderLine ( 'deviceid' );

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {
					return $response->withJson ( $return_data );
				}

				$ps_student = null;

				$_data = array ();

				// Kiem tra moi quan he nguoi than va lay thong tin hoc sinh
				if ($user->user_type == USER_TYPE_RELATIVE) {

					// Check tien trong tai khoan
					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {

						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);

						return $response->withJson ( $return_data );
					}

					$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );
				}

				if ($ps_student) {

					$user_info = new \stdClass ();

					$user_info->student_id = ( int ) $ps_student->id;
					$user_info->first_name = $ps_student->first_name;
					$user_info->last_name = $ps_student->last_name;
					$user_info->class_id = ( int ) $ps_student->class_id;
					$user_info->class_name = ($ps_student->class_name != '') ? $ps_student->class_name : '';
					// $user_info->avatar_url = PsString::generateUrlImage ( 'profile', $ps_student->avatar, $ps_student->ps_customer_id, $api_token );

					$user_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

					$_data ['user_info'] = $user_info;

					$sql_count = $this->db->table ( CONST_TBL_PS_LOGTIMES . ' as D' )
					->leftJoin ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=', 'D.login_relative_id' )
					->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', 'D.login_relative_id' )
					->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id' )
					->leftJoin ( CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'D.login_member_id' )
					->leftJoin ( CONST_TBL_RELATIVE . ' as R2', 'R2.id', '=', 'D.logout_relative_id' )
					->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS2', 'RS2.relative_id', '=', 'D.logout_relative_id' )
					->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE2', 'RE2.id', '=', 'RS2.relationship_id' )
					->leftJoin ( CONST_TBL_PS_MEMBER . ' as M2', 'M2.id', '=', 'D.logout_member_id' )
					
					->whereRaw ( 'D.student_id = ' . $student_id . ' AND ( (RS.student_id = ' . $student_id . ' OR RS.student_id IS NULL) AND (RS2.student_id = ' . $student_id . ' OR RS2.student_id IS NULL ) ) ' )
					
					//->where( 'D.student_id', $student_id)
					
					->select ( 'D.id' )->get ();

					$ps_diarys_count = $sql_count->count ();

					$limit = PS_CONST_LIMIT_DIARY;

					if ($ps_diarys_count % $limit == 0)
						$diary_number_pages = $ps_diarys_count / $limit;
					else
						$diary_number_pages = ( int ) ($ps_diarys_count / $limit) + 1;

					if ($page > $diary_number_pages)
						$page = 1;

					$next_page = '/ps_student/' . $student_id . '/' . ($page + 1) . '/diary';

					if (($diary_number_pages == 1) || ($page == $diary_number_pages))
						$next_page = '';

					// get diary of student
					$sql = $this->db->table ( CONST_TBL_PS_LOGTIMES . ' as D' )
					->leftJoin ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=', 'D.login_relative_id' )
					->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', 'D.login_relative_id' )
					->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id' )
					->leftJoin ( CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'D.login_member_id' )
					->leftJoin ( CONST_TBL_RELATIVE . ' as R2', 'R2.id', '=', 'D.logout_relative_id' )
					->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS2', 'RS2.relative_id', '=', 'D.logout_relative_id' )
					->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE2', 'RE2.id', '=', 'RS2.relationship_id' )
					->leftJoin ( CONST_TBL_PS_MEMBER . ' as M2', 'M2.id', '=', 'D.logout_member_id' )
					->whereRaw ( 'D.student_id = ' . $student_id . ' AND ( (RS.student_id = ' . $student_id . ' OR RS.student_id IS NULL) AND (RS2.student_id = ' . $student_id . ' OR RS2.student_id IS NULL ) ) ' )
					//->where( 'D.student_id', $student_id)
					->orderBy ( 'D.login_at', 'desc' )->distinct ()->select ( 'D.id', 'D.student_id', 'D.login_relative_id', 'D.login_at', 'D.logout_at', 'D.logout_relative_id', 'RE.title AS _part_in', 'D.login_member_id AS _id_teacher_in', 'M.avatar AS _avatar_teacher_in', 'M.ps_customer_id AS customer_id_in', 'RE2.title AS _part_out', 'D.logout_member_id AS _id_teacher_out', 'M2.ps_customer_id AS customer_id_out' )->selectRaw ( 'CONCAT(R.first_name," ", R.last_name) AS _name_take' )->selectRaw ( 'CONCAT(M.first_name," ", M.last_name) AS _name_teacher' )->selectRaw ( 'CONCAT(R2.first_name," ", R2.last_name) AS _name_reveler' )->selectRaw ( 'CONCAT(M2.first_name," ", M2.last_name) AS _name_teacher_reveler' );

					$ps_diarys = $sql->forPage ( $page, $limit )->get ();

					$_diary_info = array ();

					if (count ( $ps_diarys ) > 0) {

						foreach ( $ps_diarys as $obj ) {

							// $diary_info['student_id'] = $student_id;

							$diary_info = array ();

							$diary_info ['_day_week'] = ($obj->login_at != '') ? PsDateTime::toDayInWeek ( $obj->login_at, $code_lang ) : '';
							$diary_info ['_day'] = ($obj->login_at != '') ? PsDateTime::toDMY ( $obj->login_at, 'd/m/Y' ) : '';
							$diary_info ['_hours_in_class'] = ($obj->login_at != '') ? PsDateTime::toDateTimeToTime ( $obj->login_at ) : '';
							$diary_info ['_part_in'] = ($obj->_part_in != '') ? $obj->_part_in : '';
							$diary_info ['_name_take'] = ($obj->_name_take != '') ? $obj->_name_take : '';
							$diary_info ['_take_relative_id'] = ( int ) $obj->login_relative_id;
							$diary_info ['_name_teacher'] = ($obj->_name_teacher != '') ? $obj->_name_teacher : '';
							$diary_info ['_id_teacher_in'] = ( int ) $obj->_id_teacher_in;

							$diary_info ['_hours_get_home'] = ($obj->logout_at != '') ? PsDateTime::toDateTimeToTime ( $obj->logout_at ) : '';
							$diary_info ['_part_out'] = ($obj->_part_out != '') ? $obj->_part_out : '';
							$diary_info ['_name_reveler'] = ($obj->_name_reveler != '') ? $obj->_name_reveler : '';
							$diary_info ['_logout_reveler_id'] = ( int ) $obj->logout_relative_id;
							$diary_info ['_name_teacher_reveler'] = ($obj->_name_teacher_reveler != "") ? $obj->_name_teacher_reveler : '';
							$diary_info ['_id_teacher_out'] = ( int ) $obj->_id_teacher_out;

							array_push ( $_diary_info, $diary_info );
						}
					
					} else {

						$next_page = '';
						$diary_info = array ();
						$diary_info ['_day_week'] = PsDateTime::toDayInWeek ( date ( 'Y-m-d' ), $code_lang );
						$diary_info ['_day'] = PsDateTime::toDMY ( date ( 'Y-m-d' ), 'd/m/Y' );
						$diary_info ['_hours_in_class'] = '';
						$diary_info ['_part_in'] = '';
						$diary_info ['_name_take'] = '';
						$diary_info ['_take_relative_id'] = 0;
						$diary_info ['_name_teacher'] = '';
						$diary_info ['_id_teacher_in'] = 0;

						$diary_info ['_hours_get_home'] = '';
						$diary_info ['_part_out'] = '';
						$diary_info ['_name_reveler'] = '';
						$diary_info ['_logout_reveler_id'] = 0;
						$diary_info ['_name_teacher_reveler'] = '';
						$diary_info ['_id_teacher_out'] = 0;

						array_push ( $_diary_info, $diary_info );
					}

					$_data ['next_page'] = $next_page;
					$_data ['diary_info'] = $_diary_info;

					$return_data = array (
							'_msg_code' => MSG_CODE_TRUE,
							'_data' => $_data
					);
				}
			}
		} catch ( Exception $e ) {

			// Log
			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	/**
	 * Screen diarysStudent for Android, convert id to INT
	 * Get diarys student by page
	 */
	public function diarysStudentAndroid(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'next_page' => '',
				'_data' => '[]'
		);

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		// get data from URI
		$student_id = ( int ) $args ['student_id'];

		$page = isset ( $args ['page'] ) ? ( int ) $args ['page'] : 1;

		try {

			if ($student_id > 0 && $page >= 1) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {

					return $response->withJson ( $return_data );
				}

				$ps_student = null;

				if ($user->user_type == USER_TYPE_RELATIVE) {

					// Check tien trong tai khoan
					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {

						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);

						return $response->withJson ( $return_data );
					}

					$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );
				}

				$_data = array ();

				if ($ps_student) {

					$user_info = new \stdClass ();

					$user_info->student_id = ( int ) $ps_student->id;
					$user_info->first_name = $ps_student->first_name;
					$user_info->last_name = $ps_student->last_name;
					$user_info->class_id = ( int ) $ps_student->class_id;
					$user_info->class_name = ($ps_student->class_name != '') ? $ps_student->class_name : '';

					// $user_info->avatar_url = PsString::generateUrlImage ( 'profile', $ps_student->avatar, $ps_student->ps_customer_id, $api_token );

					$user_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

					$_data ['user_info'] = $user_info;

					$sql_count = $this->db->table ( CONST_TBL_PS_LOGTIMES . ' as D' )
					->leftJoin ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=', 'D.login_relative_id' )
					->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', 'D.login_relative_id' )
					->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id' )
					->leftJoin ( CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'D.login_member_id' )
					->leftJoin ( CONST_TBL_RELATIVE . ' as R2', 'R2.id', '=', 'D.logout_relative_id' )
					->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS2', 'RS2.relative_id', '=', 'D.logout_relative_id' )
					->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE2', 'RE2.id', '=', 'RS2.relationship_id' )
					->leftJoin ( CONST_TBL_PS_MEMBER . ' as M2', 'M2.id', '=', 'D.logout_member_id' )
					->whereRaw ( 'D.student_id = ' . $student_id . ' AND ( (RS.student_id = ' . $student_id . ' OR RS.student_id IS NULL) AND (RS2.student_id = ' . $student_id . ' OR RS2.student_id IS NULL ) ) ' )
					//->where( 'D.student_id', $student_id)
					->select ( 'D.id' )->get ();

					$ps_diarys_count = $sql_count->count ();

					$limit = PS_CONST_LIMIT_DIARY;

					if ($ps_diarys_count % $limit == 0)
						$diary_number_pages = $ps_diarys_count / $limit;
					else
						$diary_number_pages = ( int ) ($ps_diarys_count / $limit) + 1;

					if ($page > $diary_number_pages)

						$page = 1;

					$next_page = '/ps_student/diary/' . $student_id . '/' . ($page + 1);

					if (($diary_number_pages == 1) || ($page == $diary_number_pages))
						$next_page = '';

					// get diary of student
					$sql = $this->db->table ( CONST_TBL_PS_LOGTIMES . ' as D' )
					->leftJoin ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=', 'D.login_relative_id' )
					->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', 'D.login_relative_id' )
					->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id' )
					->leftJoin ( CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'D.login_member_id' )
					->leftJoin ( CONST_TBL_RELATIVE . ' as R2', 'R2.id', '=', 'D.logout_relative_id' )
					->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS2', 'RS2.relative_id', '=', 'D.logout_relative_id' )
					->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE2', 'RE2.id', '=', 'RS2.relationship_id' )
					->leftJoin ( CONST_TBL_PS_MEMBER . ' as M2', 'M2.id', '=', 'D.logout_member_id' )
					->whereRaw ( 'D.student_id = ' . $student_id . ' AND ( (RS.student_id = ' . $student_id . ' OR RS.student_id IS NULL) AND (RS2.student_id = ' . $student_id . ' OR RS2.student_id IS NULL ) ) ' )
					//->where( 'D.student_id', $student_id)
					->orderBy ( 'D.login_at', 'desc' )->distinct ()->select ( 'D.id', 'D.student_id', 'D.login_relative_id', 'D.login_at', 'D.logout_at', 'D.logout_relative_id', 'RE.title AS _part_in', 'D.login_member_id AS _id_teacher_in', 'M.avatar AS _avatar_teacher_in', 'M.ps_customer_id AS customer_id_in', 'RE2.title AS _part_out', 'D.logout_member_id AS _id_teacher_out', 'M2.ps_customer_id AS customer_id_out' )->selectRaw ( 'CONCAT(R.first_name," ", R.last_name) AS _name_take' )->selectRaw ( 'CONCAT(M.first_name," ", M.last_name) AS _name_teacher' )->selectRaw ( 'CONCAT(R2.first_name," ", R2.last_name) AS _name_reveler' )->selectRaw ( 'CONCAT(M2.first_name," ", M2.last_name) AS _name_teacher_reveler' );

					$ps_diarys = $sql->forPage ( $page, $limit )->get ();

					$_diary_info = array ();

					if (count ( $ps_diarys ) > 0) {

						foreach ( $ps_diarys as $obj ) {

							$diary_info = array ();
							// $diary_info['student_id'] = $student_id;

							$diary_info ['_day_week'] = PsDateTime::toDayInWeek ( $obj->login_at );
							$diary_info ['_day'] = PsDateTime::toDMY ( $obj->login_at, 'd/m/Y' );
							$diary_info ['_hours_in_class'] = ($obj->login_at != '') ? PsDateTime::toDateTimeToTime ( $obj->login_at ) : '';
							$diary_info ['_part_in'] = $obj->_part_in;
							$diary_info ['_name_take'] = ($obj->_name_take != '') ? $obj->_name_take : '';
							$diary_info ['_take_relative_id'] = ( int ) $obj->login_relative_id;
							$diary_info ['_name_teacher'] = ($obj->_name_teacher != '') ? $obj->_name_teacher : '';
							$diary_info ['_id_teacher_in'] = ( int ) $obj->_id_teacher_in;

							$diary_info ['_hours_get_home'] = ($obj->logout_at != '') ? PsDateTime::toDateTimeToTime ( $obj->logout_at ) : '';
							$diary_info ['_part_out'] = ($obj->_part_out != '') ? $obj->_part_out : '';
							$diary_info ['_name_reveler'] = ($obj->_name_reveler != '') ? $obj->_name_reveler : '';
							$diary_info ['_logout_reveler_id'] = ( int ) $obj->logout_relative_id;
							$diary_info ['_name_teacher_reveler'] = ($obj->_name_teacher_reveler != "") ? $obj->_name_teacher_reveler : '';
							$diary_info ['_id_teacher_out'] = ( int ) $obj->_id_teacher_out;

							array_push ( $_diary_info, $diary_info );
						}
					} else {

						$next_page = '';

						$diary_info = array ();

						$diary_info ['_day_week'] = PsDateTime::toDayInWeek ( date ( 'Y-m-d' ) );
						$diary_info ['_day'] = PsDateTime::toDMY ( date ( 'Y-m-d' ), 'd/m/Y' );
						$diary_info ['_hours_in_class'] = '';
						$diary_info ['_part_in'] = '';
						$diary_info ['_name_take'] = '';
						$diary_info ['_take_relative_id'] = 0;
						$diary_info ['_name_teacher'] = '';
						$diary_info ['_id_teacher_in'] = 0;

						$diary_info ['_hours_get_home'] = '';
						$diary_info ['_part_out'] = '';
						$diary_info ['_name_reveler'] = '';
						$diary_info ['_logout_reveler_id'] = 0;
						$diary_info ['_name_teacher_reveler'] = '';
						$diary_info ['_id_teacher_out'] = 0;

						array_push ( $_diary_info, $diary_info );
					}

					$_data ['next_page'] = $next_page;
					$_data ['diary_info'] = $_diary_info;

					$return_data = array (
							'_msg_code' => MSG_CODE_TRUE,
							'_data' => $_data
					);
				}
			}
		} catch ( Exception $e ) {
			// Log
			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	/**
	 * Screen diaryStudentNext - ko dung den
	 * Get diary student by page
	 */
	public function diaryStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'next_page' => '',
				'_data' => '[]'
		);

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		// get data from URI
		$student_id = ( int ) $args ['student_id'];

		$page = isset ( $args ['page'] ) ? ( int ) $args ['page'] : 1;

		try {

			if ($student_id > 0 && $page >= 1) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {
					return $response->withJson ( $return_data );
				}

				$ps_student = null;

				if ($user->user_type == USER_TYPE_RELATIVE) {

					// Check tien trong tai khoan
					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {

						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);

						return $response->withJson ( $return_data );
					}

					// Kiem tra moi quan he nguoi than va lay thong tin hoc sinh
					$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );
				}

				$_data = array ();

				if ($ps_student) {

					$user_info = new \stdClass ();

					$user_info->student_id = $ps_student->id;
					$user_info->first_name = $ps_student->first_name;
					$user_info->last_name = $ps_student->last_name;
					$user_info->class_id = $ps_student->class_id;
					$user_info->class_name = ($ps_student->class_name != '') ? $ps_student->class_name : '';
					$user_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
					$_data ['user_info'] = $user_info;

					$sql_count = $this->db->table ( CONST_TBL_PS_LOGTIMES . ' as D' )
					->leftJoin ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=', 'D.login_relative_id' )
					->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', 'D.login_relative_id' )
					->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id' )
					->leftJoin ( CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'D.login_member_id' )
					->leftJoin ( CONST_TBL_RELATIVE . ' as R2', 'R2.id', '=', 'D.logout_relative_id' )
					->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS2', 'RS2.relative_id', '=', 'D.logout_relative_id' )
					->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE2', 'RE2.id', '=', 'RS2.relationship_id' )
					->leftJoin ( CONST_TBL_PS_MEMBER . ' as M2', 'M2.id', '=', 'D.logout_member_id' )->whereRaw ( 'D.student_id = ' . $student_id . ' AND ( RS.student_id = ' . $student_id . ' AND (RS2.student_id = ' . $student_id . ' OR RS2.student_id IS NULL) ) ' )->select ( 'D.id' )->get ();

					$ps_diarys_count = $sql_count->count ();

					$limit = PS_CONST_LIMIT_DIARY;

					if ($ps_diarys_count % $limit == 0)
						$diary_number_pages = $ps_diarys_count / $limit;
					else
						$diary_number_pages = ( int ) ($ps_diarys_count / $limit) + 1;

					if ($page > $diary_number_pages)
						$page = 1;

					// $next_page = '/ps_student/' . $student_id . '/' . ($page + 1) . '/diary';

					if (($diary_number_pages == 1) || ($page == $diary_number_pages))
						$next_page = '';
					else
						$next_page = $page + 1;

					// get diary of student
						$sql = $this->db->table ( CONST_TBL_PS_LOGTIMES . ' as D' )->leftJoin ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=', 'D.login_relative_id' )->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', 'D.login_relative_id' )->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id' )->leftJoin ( CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'D.login_member_id' )->leftJoin ( CONST_TBL_RELATIVE . ' as R2', 'R2.id', '=', 'D.logout_relative_id' )->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS2', 'RS2.relative_id', '=', 'D.logout_relative_id' )->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE2', 'RE2.id', '=', 'RS2.relationship_id' )->leftJoin ( CONST_TBL_PS_MEMBER . ' as M2', 'M2.id', '=', 'D.logout_member_id' )->whereRaw ( 'D.student_id = ' . $student_id . ' AND ( RS.student_id = ' . $student_id . ' AND (RS2.student_id = ' . $student_id . ' OR RS2.student_id IS NULL ) ) ' )->orderBy ( 'D.login_at', 'desc' )->distinct ()->select ( 'D.id', 'D.student_id', 'D.login_relative_id', 'D.login_at', 'D.logout_at', 'D.logout_relative_id', 'RE.title AS _part_in', 'D.login_member_id AS _id_teacher_in', 'M.avatar AS _avatar_teacher_in', 'M.ps_customer_id AS customer_id_in', 'RE2.title AS _part_out', 'D.logout_member_id AS _id_teacher_out', 'M2.ps_customer_id AS customer_id_out' )->selectRaw ( 'CONCAT(R.first_name," ", R.last_name) AS _name_take' )->selectRaw ( 'CONCAT(M.first_name," ", M.last_name) AS _name_teacher' )->selectRaw ( 'CONCAT(R2.first_name," ", R2.last_name) AS _name_reveler' )->selectRaw ( 'CONCAT(M2.first_name," ", M2.last_name) AS _name_teacher_reveler' );

					$ps_diarys = $sql->forPage ( $page, $limit )->get ();

					$_diary_info = array ();

					if (count ( $ps_diarys ) > 0) {

						foreach ( $ps_diarys as $obj ) {

							$diary_info = array ();

							// $diary_info['student_id'] = $student_id;
							$diary_info ['_day_week'] = ($obj->login_at != '') ? PsDateTime::toDayInWeek ( $obj->login_at, $code_lang ) : '';
							$diary_info ['_day'] = ($obj->login_at != '') ? PsDateTime::toDMY ( $obj->login_at, 'd/m/Y' ) : '';
							$diary_info ['_hours_in_class'] = ($obj->login_at != '') ? PsDateTime::toDateTimeToTime ( $obj->login_at ) : '';
							$diary_info ['_part_in'] = ($obj->_part_in != '') ? $obj->_part_in : '';
							$diary_info ['_name_take'] = ($obj->_name_take != '') ? $obj->_name_take : '';
							$diary_info ['_take_relative_id'] = $obj->login_relative_id;
							$diary_info ['_name_teacher'] = ($obj->_name_teacher != '') ? $obj->_name_teacher : '';
							$diary_info ['_id_teacher_in'] = $obj->_id_teacher_in;

							$diary_info ['_hours_get_home'] = ($obj->logout_at != '') ? PsDateTime::toDateTimeToTime ( $obj->logout_at ) : '';
							$diary_info ['_part_out'] = ($obj->_part_out != '') ? $obj->_part_out : '';
							$diary_info ['_name_reveler'] = ($obj->_name_reveler != '') ? $obj->_name_reveler : '';
							$diary_info ['_logout_reveler_id'] = $obj->logout_relative_id;
							$diary_info ['_name_teacher_reveler'] = ($obj->_name_teacher_reveler != "") ? $obj->_name_teacher_reveler : '';
							$diary_info ['_id_teacher_out'] = $obj->_id_teacher_out;

							array_push ( $_diary_info, $diary_info );
						}
					} else {

						$next_page = '';

						$diary_info = array ();

						$diary_info ['_day_week'] = PsDateTime::toDayInWeek ( date ( 'Y-m-d' ), $code_lang );
						$diary_info ['_day'] = PsDateTime::toDMY ( date ( 'Y-m-d' ), 'd/m/Y' );
						$diary_info ['_hours_in_class'] = '';
						$diary_info ['_part_in'] = '';
						$diary_info ['_name_take'] = '';
						$diary_info ['_take_relative_id'] = 0;
						$diary_info ['_name_teacher'] = '';
						$diary_info ['_id_teacher_in'] = 0;

						$diary_info ['_hours_get_home'] = '';
						$diary_info ['_part_out'] = '';
						$diary_info ['_name_reveler'] = '';
						$diary_info ['_logout_reveler_id'] = 0;
						$diary_info ['_name_teacher_reveler'] = '';
						$diary_info ['_id_teacher_out'] = 0;

						array_push ( $_diary_info, $diary_info );
					}

					$_data ['next_page'] = $next_page;
					$_data ['diary_info'] = $_diary_info;

					$_data ['page'] = $page;

					$_data ['number_pages'] = $diary_number_pages;

					$return_data = array (
							'_msg_code' => MSG_CODE_TRUE,
							'_data' => $_data
					);
				}
			}
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	/**
	 * Screen diarysStudent
	 * Get diary student by page
	 */
	public function diarysStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'next_page' => '',
				'_data' => '[]'
		);

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		// get data from URI
		$student_id = ( int ) $args ['student_id'];

		$page = isset ( $args ['page'] ) ? ( int ) $args ['page'] : 1;

		try {

			if ($student_id > 0 && $page >= 1) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {
					return $response->withJson ( $return_data );
				}

				$ps_student = null;

				if ($user->user_type == USER_TYPE_RELATIVE) {

					// Check tien trong tai khoan
					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {

						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);

						return $response->withJson ( $return_data );
					}

					// Kiem tra moi quan he nguoi than va lay thong tin hoc sinh
					$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );
					// $return_data ['ps_student'] = $ps_student;
				}
				
				$_data = array ();
				
				if ($ps_student) {

					$user_info = new \stdClass ();

					$user_info->student_id = $ps_student->id;
					$user_info->first_name = $ps_student->first_name;
					$user_info->last_name = $ps_student->last_name;
					$user_info->class_id = ( int ) $ps_student->class_id;
					$user_info->class_name = ($ps_student->class_name != '') ? $ps_student->class_name : '';
					$user_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
					$_data ['user_info'] = $user_info;

					$sql_count = $this->db->table ( CONST_TBL_PS_LOGTIMES . ' as D' )
					->leftJoin ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=', 'D.login_relative_id' )
					->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', 'D.login_relative_id' )
					->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id' )
					->leftJoin ( CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'D.login_member_id' )
					->leftJoin ( CONST_TBL_RELATIVE . ' as R2', 'R2.id', '=', 'D.logout_relative_id' )
					->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS2', 'RS2.relative_id', '=', 'D.logout_relative_id' )
					->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE2', 'RE2.id', '=', 'RS2.relationship_id' )
					->leftJoin ( CONST_TBL_PS_MEMBER . ' as M2', 'M2.id', '=', 'D.logout_member_id' )
					->whereRaw ( 'D.student_id = ' . $student_id . ' AND ( (RS.student_id = ' . $student_id . ' OR RS.student_id IS NULL) AND (RS2.student_id = ' . $student_id . ' OR RS2.student_id IS NULL ) ) ' )
					//->where( 'D.student_id', $student_id)
					->select ( 'D.id' )->get ();

					$ps_diarys_count = $sql_count->count ();

					$limit = PS_CONST_LIMIT_DIARY;

					if ($ps_diarys_count % $limit == 0)
						$diary_number_pages = $ps_diarys_count / $limit;
					else
						$diary_number_pages = ( int ) ($ps_diarys_count / $limit) + 1;

					if ($page > $diary_number_pages)
						$page = 1;

					$next_page = $page + 1;

					// $next_page = '/ps_student/diary/' . $student_id . '/' . ($page + 1);

					if (($diary_number_pages == 1) || ($page == $diary_number_pages))
						$next_page = '';

					// get diary of student
						$sql = $this->db->table ( CONST_TBL_PS_LOGTIMES . ' as D' )
						->leftJoin ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=', 'D.login_relative_id' )
						->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', 'D.login_relative_id' )
						->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id' )
						->leftJoin ( CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'D.login_member_id' )
						->leftJoin ( CONST_TBL_RELATIVE . ' as R2', 'R2.id', '=', 'D.logout_relative_id' )
						->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS2', 'RS2.relative_id', '=', 'D.logout_relative_id' )
						->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE2', 'RE2.id', '=', 'RS2.relationship_id' )
						->leftJoin ( CONST_TBL_PS_MEMBER . ' as M2', 'M2.id', '=', 'D.logout_member_id' )
						->whereRaw ( 'D.student_id = ' . $student_id . ' AND ( (RS.student_id = ' . $student_id . ' OR RS.student_id IS NULL) AND (RS2.student_id = ' . $student_id . ' OR RS2.student_id IS NULL ) ) ' )
						//->where( 'D.student_id', $student_id)
						->orderBy ( 'D.login_at', 'desc' )->distinct ()->select ( 'D.id', 'D.student_id', 'D.login_relative_id', 'D.login_at', 'D.logout_at', 'D.logout_relative_id', 'RE.title AS _part_in', 'D.login_member_id AS _id_teacher_in', 'M.avatar AS _avatar_teacher_in', 'M.ps_customer_id AS customer_id_in', 'RE2.title AS _part_out', 'D.logout_member_id AS _id_teacher_out', 'M2.ps_customer_id AS customer_id_out' )->selectRaw ( 'CONCAT(R.first_name," ", R.last_name) AS _name_take' )->selectRaw ( 'CONCAT(M.first_name," ", M.last_name) AS _name_teacher' )->selectRaw ( 'CONCAT(R2.first_name," ", R2.last_name) AS _name_reveler' )->selectRaw ( 'CONCAT(M2.first_name," ", M2.last_name) AS _name_teacher_reveler' );

					$ps_diarys = $sql->forPage ( $page, $limit )->get ();

					$_diary_info = array ();

					if (count ( $ps_diarys ) > 0) {

						foreach ( $ps_diarys as $obj ) {

							$diary_info = array ();

							// $diary_info['student_id'] = $student_id;
							$diary_info ['_day_week'] = PsDateTime::toDayInWeek ( $obj->login_at, $code_lang );
							$diary_info ['_day'] = PsDateTime::toDMY ( $obj->login_at, 'd/m/Y' );
							$diary_info ['_hours_in_class'] = PsDateTime::toDateTimeToTime ( $obj->login_at );
							$diary_info ['_part_in'] = (string)$obj->_part_in;
							$diary_info ['_name_take'] = ($obj->_name_take != '') ? (string)$obj->_name_take : '';
							$diary_info ['_take_relative_id'] = (int)$obj->login_relative_id;
							$diary_info ['_name_teacher'] = ($obj->_name_teacher != '') ? (string)$obj->_name_teacher : '';
							$diary_info ['_id_teacher_in'] = (int)$obj->_id_teacher_in;

							$diary_info ['_hours_get_home'] = PsDateTime::toDateTimeToTime ( $obj->logout_at );
							$diary_info ['_part_out'] = ($obj->_part_out != '') ? (string)$obj->_part_out : '';
							$diary_info ['_name_reveler'] = ($obj->_name_reveler != '') ? (string)$obj->_name_reveler : '';
							$diary_info ['_logout_reveler_id'] = (int)$obj->logout_relative_id;
							$diary_info ['_name_teacher_reveler'] = ($obj->_name_teacher_reveler != "") ? (string)$obj->_name_teacher_reveler : '';
							$diary_info ['_id_teacher_out'] = (int)$obj->_id_teacher_out;

							array_push ( $_diary_info, $diary_info );
						}
					} else {

						$next_page = '';

						$diary_info = array ();

						$diary_info ['_day_week'] = PsDateTime::toDayInWeek ( date ( 'Y-m-d' ), $code_lang );
						$diary_info ['_day'] = PsDateTime::toDMY ( date ( 'Y-m-d' ), 'd/m/Y' );
						$diary_info ['_hours_in_class'] = '';
						$diary_info ['_part_in'] = '';
						$diary_info ['_name_take'] = '';
						$diary_info ['_take_relative_id'] = 0;
						$diary_info ['_name_teacher'] = '';
						$diary_info ['_id_teacher_in'] = 0;

						$diary_info ['_hours_get_home'] = '';
						$diary_info ['_part_out'] = '';
						$diary_info ['_name_reveler'] = '';
						$diary_info ['_logout_reveler_id'] = 0;
						$diary_info ['_name_teacher_reveler'] = '';
						$diary_info ['_id_teacher_out'] = 0;

						array_push ( $_diary_info, $diary_info );
					}

					$_data ['next_page'] = $next_page;
					$_data ['diary_info'] = $_diary_info;

					// $_data['page'] = $page;
					// $_data['number_pages'] = $diary_number_pages;

					$return_data = array (
							'_msg_code' => MSG_CODE_TRUE,
							'_data' => $_data
					);
				}
			}
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	/**
	 * Screen teacherStudent - Chi tiet giao vien
	 * Get infomation teacher student
	 */
	public function teacherStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		// Log message
		$this->WriteLog ( "Teacher info" );

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => '[]'
		);

		$user = $this->user_token;

		// get config ngon ngu cua user
		$psI18n = new PsI18n ( $this->getUserLanguage ( $user ) );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		// get data from URI
		$m_id = $args ['m_id'];

		try {

			if ($m_id > 0) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {
					return $response->withJson ( $return_data );
				}

				if ($user->user_type == USER_TYPE_RELATIVE) {
					// Check tien trong tai khoan
					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {

						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);

						return $response->withJson ( $return_data );
					}

					$ps_member = PsMemberModel::getPsMemberById ( $m_id );

					$_data = array ();

					if ($ps_member) {

						$user_info = new \stdClass ();

						$user_info->school_id = $ps_member->ps_customer_id;
						$user_info->school_name = ($ps_member->school_name != '') ? $ps_member->school_name : '';
						// $user_info->school_logo_url = PsString::generateUrlImage('customer', $ps_member->logo, $ps_member->ps_customer_id, $api_token);

						$user_info->school_logo_url = PsString::getUrlLogoPsCustomer ( $ps_member->year_data, $ps_member->logo );

						$user_info->teacher_id = $ps_member->id;

						// $user_info->avatar_url = PsString::generateUrlImage ( 'hr', $ps_member->avatar, $ps_member->ps_customer_id, $api_token );

						$user_info->avatar_url = ($ps_member->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_member->cache_data, $ps_member->year_data, $ps_member->avatar, MEDIA_TYPE_TEACHER ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

						$user_info->full_name = $ps_member->first_name . ' ' . $ps_member->last_name;
						$user_info->birthday = PsDateTime::toDMY ( $ps_member->birthday );
						$user_info->gender = $ps_member->sex;
						$user_info->phone = $ps_member->mobile;

						$user_info->user_key = PsEndCode::psHash256 ( $ps_member->m_user_id );

						$_data ['user_info'] = $user_info;

						$_diary_info = $diary_info = array ();

						if ($ps_member->address != '') {
							$diary_info ['title'] = $psI18n->__ ( 'address' );
							$diary_info ['value'] = $ps_member->address;

							array_push ( $_diary_info, $diary_info );
						}

						if ($ps_member->email != '') {
							$diary_info ['title'] = $psI18n->__ ( 'email' );
							$diary_info ['value'] = $ps_member->email;

							array_push ( $_diary_info, $diary_info );
						}

						$_data ['diary_info'] = $_diary_info;

						$return_data = array (
								'_msg_code' => MSG_CODE_TRUE,
								'_data' => $_data
						);
					}
				}
			}
		} catch ( Exception $e ) {
			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}
		return $response->withJson ( $return_data );

	}

	/**
	 *
	 * @param RequestInterface $request
	 * @param ResponseInterface $response
	 * @param array $args
	 * @return mixed
	 */
	public function servicesStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => [ ]
		);

		$user = $this->user_token;

		$psI18n = new PsI18n ( $this->getUserLanguage ( $user ) );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		$student_id = ( int ) $args ['student_id'];

		try {

			if ($student_id > 0) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {
					return $response->withJson ( $return_data );
				}

				$ps_student = null;
				if ($user->user_type == USER_TYPE_RELATIVE) {

					// Check tien trong tai khoan
					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {

						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);

						return $response->withJson ( $return_data );
					}

					$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );
				}

				if ($ps_student) {
					$user_info = new \stdClass ();
					$user_info->role_service = $ps_student->role_service;
					$user_info->student_id = $ps_student->id;
					$user_info->first_name = $ps_student->first_name;
					$user_info->last_name = $ps_student->last_name;
					$user_info->class_id = $ps_student->class_id;
					$user_info->class_name = $ps_student->class_name;
					// $user_info->avatar_url = PsString::generateUrlImage ( 'profile', $ps_student->avatar, $ps_student->ps_customer_id, $api_token );
					$user_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
					// get info service
					$curr_day = date ( 'Y-m-d' );
					$customer = $ps_student->ps_customer_id;

					$service_sql = $this->db->table ( CONST_TBL_SERVICE . ' as S' )->leftjoin ( CONST_TBL_SERVICE_DETAIL . ' as SD', 'SD.service_id', '=', 'S.id' )->leftJoin ( CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', 'S.ps_image_id' )->leftJoin ( CONST_TBL_STUDENT_SERVICE . ' as SS', function ($q) use ($student_id) {
						$q->on ( 'SS.service_id', '=', 'S.id' )->where ( 'SS.student_id', '=', $student_id )->whereNull ( 'SS.delete_at' );
					} )->select ( 'S.id', 'S.title', 'S.is_default', 'I.file_name', 'SD.amount', 'SS.id as student_service_id' )->where ( 'S.ps_customer_id', '=', $customer )->where ( 'S.ps_school_year_id', '=', $ps_student->school_year_id )->whereRaw ( '(S.ps_workplace_id IS NULL OR S.ps_workplace_id =?)', array (
							$ps_student->ps_workplace_id
					) )->where ( 'S.is_activated', '=', 1 )->whereDate ( 'SD.detail_at', '<=', $curr_day )->whereDate ( 'SD.detail_end', '>=', $curr_day )->orderBy ( 'S.iorder' )->distinct ();

					$services = $service_sql->get ();

					$data_info = array ();

					foreach ( $services as $service ) {

						$data = array ();

						$data ['service_id'] = $service->id;
						$data ['icon_url'] = PsString::getUrlPsImage ( $service->file_name );
						$data ['title'] = $service->title;
						$data ['default'] = $service->is_default;
						$data ['price'] = $service->amount;
						$data ['price'] = PsNumber::format_price ( $service->amount );
						$data ['student_service_id'] = $service->student_service_id;
						$data ['status'] = ($service->student_service_id == null) ? 0 : 1;
						array_push ( $data_info, $data );
					}

					$return_data ['_data'] ['user_info'] = $user_info;

					$return_data ['_data'] ['data_info'] = $data_info;

					$return_data ['_msg_code'] = MSG_CODE_TRUE;
				}
			}
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	// Lay danh sach dich vu da dang ky
	public function servicesUsedStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => [ ]
		);

		$user = $this->user_token;

		$psI18n = new PsI18n ( $this->getUserLanguage ( $user ) );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		$student_id = $args ['student_id'];

		try {

			if ($student_id > 0) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {
					return $response->withJson ( $return_data );
				}

				$ps_student = null;

				if ($user->user_type == USER_TYPE_RELATIVE) {

					// Check tien trong tai khoan
					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {
						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);
						return $response->withJson ( $return_data );
					}

					$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );
				}
				if ($ps_student) {
					$user_info = new \stdClass ();
					$user_info->role_service = $ps_student->role_service;
					$user_info->student_id = $ps_student->id;
					$user_info->first_name = $ps_student->first_name;
					$user_info->last_name = $ps_student->last_name;
					$user_info->class_id = $ps_student->class_id;
					$user_info->class_name = $ps_student->class_name;
					// $user_info->avatar_url = PsString::generateUrlImage ( 'profile', $ps_student->avatar, $ps_student->ps_customer_id, $api_token );
					$user_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
					// get info service
					$curr_day = date ( 'Y-m-d' );
					$ps_customer_id = $ps_student->ps_customer_id;
					$services = $this->db->table ( CONST_TBL_SERVICE . ' as S' )->leftjoin ( CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.service_id', '=', 'S.id' )->leftjoin ( CONST_TBL_SERVICE_DETAIL . ' as SD', 'SD.service_id', '=', 'S.id' )->leftJoin ( CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', 'S.ps_image_id' )->select ( 'S.id', 'SS.id as student_service_id', 'S.title', 'S.ps_image_id', 'S.is_default', 'I.file_name', 'SD.amount' )->where ( 'S.ps_customer_id', '=', $ps_customer_id )->where ( 'S.is_activated', '=', 1 )->whereDate ( 'SD.detail_at', '<=', $curr_day )->whereDate ( 'SD.detail_end', '>=', $curr_day )->where ( 'SS.student_id', '=', $student_id )->where ( 'S.ps_school_year_id', '=', $ps_student->school_year_id )->whereRaw ( '(S.ps_workplace_id IS NULL OR S.ps_workplace_id =?)', array (
							$ps_student->ps_workplace_id
					) )->whereNull ( 'SS.delete_at' )->distinct ()->get ();

					$data_info = array ();

					foreach ( $services as $service ) {
						$data = array ();

						$data ['service_id'] = $service->id;
						$data ['student_service_id'] = $service->student_service_id;
						$data ['icon_url'] = PsString::getUrlPsImage ( $service->file_name );
						$data ['title'] = $service->title;
						$data ['default'] = $service->is_default;
						// $data ['price'] = $service->amount;
						$data ['price'] = PsNumber::format_price ( $service->amount );
						$data ['status'] = 1;
						array_push ( $data_info, $data );
					}

					$return_data ['_data'] ['user_info'] = $user_info;
					$return_data ['_data'] ['data_info'] = $data_info;
					$return_data ['_msg_code'] = MSG_CODE_TRUE;
				}
			}
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	// Lay danh sach dich vu chua dang ky
	public function servicesUnregisterStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array ();

		$return_data ['_msg_code'] = MSG_CODE_FALSE;
		$return_data ['_data'] = array ();

		$user = $this->user_token;

		$psI18n = new PsI18n ( $this->getUserLanguage ( $user ) );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		$student_id = $args ['student_id'];

		try {

			if ($student_id > 0) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {
					return $response->withJson ( $return_data );
				}

				$ps_student = null;

				if ($user->user_type == USER_TYPE_RELATIVE) {

					// Check tien trong tai khoan
					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {
						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);
						return $response->withJson ( $return_data );
					}

					$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );
				}

				if ($ps_student) {
					$user_info = new \stdClass ();
					$user_info->role_service = $ps_student->role_service;
					$user_info->student_id = $ps_student->id;
					$user_info->first_name = $ps_student->first_name;
					$user_info->last_name = $ps_student->last_name;
					$user_info->class_id = $ps_student->class_id;
					$user_info->class_name = $ps_student->class_name;

					$user_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

					$curr_day = date ( 'Y-m-d' );
					$customer = $ps_student->ps_customer_id;
					$services = $this->db->table ( CONST_TBL_SERVICE . ' as S' )->leftjoin ( CONST_TBL_SERVICE_DETAIL . ' as SD', 'SD.service_id', '=', 'S.id' )->leftJoin ( CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', 'S.ps_image_id' )->select ( 'S.id', 'S.title', 'S.is_default', 'I.file_name', 'SD.amount' )->where ( 'S.ps_customer_id', '=', $customer )->where ( 'S.is_activated', '=', 1 )->whereDate ( 'SD.detail_at', '<=', $curr_day )->whereDate ( 'SD.detail_end', '>=', $curr_day )->whereNotIn ( 'S.id', function ($query) use ($customer, $curr_day, $student_id) {
						$query->select ( 'S.id' )->from ( CONST_TBL_SERVICE . ' as S' )->leftjoin ( CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.service_id', '=', 'S.id' )->leftjoin ( CONST_TBL_SERVICE_DETAIL . ' as SD', 'SD.service_id', '=', 'S.id' )->leftJoin ( CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', 'S.ps_image_id' )->where ( 'S.ps_customer_id', '=', $customer )->where ( 'S.is_activated', '=', 1 )->whereDate ( 'SD.detail_at', '<=', $curr_day )->whereDate ( 'SD.detail_end', '>=', $curr_day )->where ( 'SS.student_id', '=', $student_id )->whereNull ( 'SS.delete_at' )->distinct ()->get ();
					} )->distinct ()->get ();

					$data_info = array ();

					foreach ( $services as $service ) {
						$data = array ();
						$data ['service_id'] = $service->id;
						$data ['icon_url'] = PsString::getUrlPsImage ( $service->file_name );
						$data ['title'] = $service->title;
						$data ['default'] = $service->is_default;
						// $data ['price'] = $service->amount;
						$data ['price'] = PsNumber::format_price ( $service->amount );
						$data ['status'] = 0;
						array_push ( $data_info, $data );
					}

					$return_data ['_data'] ['user_info'] = $user_info;
					$return_data ['_data'] ['data_info'] = $data_info;
					$return_data ['_msg_code'] = MSG_CODE_TRUE;
				}
			}
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	// Lay chi tiet dich vu
	public function serviceStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array ();

		$return_data ['_msg_code'] = MSG_CODE_FALSE;
		$return_data ['_data'] = array ();

		$user = $this->user_token;

		$psI18n = new PsI18n ( $this->getUserLanguage ( $user ) );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		$student_service_id = $request->getHeaderLine ( 'studentserviceid' );
		$service_id = $args ['service_id'];
		$student_id = $args ['student_id'];

		try {

			if ($student_id > 0 && $service_id > 0) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {
					return $response->withJson ( $return_data );
				}

				$ps_student = null;

				if ($user->user_type == USER_TYPE_RELATIVE) {

					// Check tien trong tai khoan
					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {
						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);
						return $response->withJson ( $return_data );
					}

					$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );
				}

				if ($ps_student) {

					$school = $this->db->table ( CONST_TBL_SERVICE . ' as S' )->leftJoin ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'S.ps_customer_id' )->leftJoin ( CONST_TBL_SERVICE_GROUP . ' as SG', 'SG.id', '=', 'S.service_group_id' )->select ( 'S.ps_customer_id', 'C.school_name', 'C.year_data', 'C.logo', 'S.title', 'SG.title as service_group_title', 'S.enable_roll', 'S.is_default' )->where ( 'S.id', '=', $service_id )->where ( 'S.is_activated', '=', 1 )->get ()->first ();

					if ($school) {

						$user_info = new \stdClass ();
						$user_info->role_service = $ps_student->role_service;
						$user_info->school_id = $school->ps_customer_id;
						$user_info->school_name = $school->school_name;

						$user_info->school_logo_url = PsString::getUrlLogoPsCustomer ( $school->year_data, $school->logo );

						$user_info->service_id = $service_id;
						$user_info->service_title = $school->title;
						$user_info->service_group = $school->service_group_title;
						$user_info->service_type = $psI18n->__ ( PsString::$ps_roll [$school->enable_roll] );
						$user_info->student_service_id = $student_service_id;
						$user_info->default = $school->is_default; // truong hop dich vu mac dinh thuoc nha truong

						// Service detail
						$curr_day = date ( 'Y-m-d' );

						$service_detail = $this->db->table ( CONST_TBL_SERVICE . ' as S' )->leftJoin ( CONST_TBL_SERVICE_DETAIL . ' as SD', 'SD.service_id', '=', 'S.id' )->select ( 'SD.amount', 'SD.detail_at', 'SD.detail_end' )->where ( 'S.is_activated', '=', 1 )->where ( 'S.id', '=', $service_id )->whereDate ( 'SD.detail_at', '<=', $curr_day )->whereDate ( 'SD.detail_end', '>=', $curr_day )->get ()->first ();
						$fee_info = array ();
						$data_info = array ();

						if ($service_detail) {

							$fee_info = new \stdClass ();

							$fee_info->price = PsNumber::format_price ( $service_detail->amount, "" );

							$fee_info->currency_unit = "đ";

							$fee_info->time_apply = PsDateTime::toDMY ( $service_detail->detail_at );

							$fee_info->status = ($student_service_id == null) ? 0 : 1;
							
							/*

							$split = $this->db->table ( CONST_TBL_SERVICE . ' as S' )->join ( CONST_TBL_SERVICE_SPLIT . ' as SS', 'SS.service_id', '=', 'S.id' )->select ( 'SS.count_value', 'SS.count_ceil', 'SS.split_value' )->where ( 'S.is_activated', '=', 1 )->where ( 'S.id', '=', $service_id )->get ();

							foreach ( $split as $sp ) {
								$_data = array ();
								$_data ['number_used'] = $sp->count_value . '-' . $sp->count_ceil;
								$_data ['price'] = PsNumber::format_price ( $sp->split_value, "" );
								array_push ( $data_info, $_data );
							}
							*/
						}

						$return_data ['_data'] ['user_info'] = $user_info;
						$return_data ['_data'] ['fee_info'] = $fee_info;
						$return_data ['_data'] ['data_info'] = $data_info;
						$return_data ['_msg_code'] = MSG_CODE_TRUE;
					}
				}
			}
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	// huy Hoac dang ky dich vu cua hoc sinh
	public function removeServiceStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => [ ]
		);

		$user = $this->user_token;

		$psI18n = new PsI18n ( $this->getUserLanguage ( $user ) );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		$body = $request->getParsedBody ();

		$student_id = isset ( $body ['student_id'] ) ? $body ['student_id'] : '';

		$service_id = isset ( $body ['service_id'] ) ? $body ['service_id'] : '';

		$student_service_id = isset ( $body ['student_service_id'] ) ? $body ['student_service_id'] : '';

		try {
			if ($student_id > 0) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {
					return $response->withJson ( $return_data );
				}

				if ($user->user_type == USER_TYPE_RELATIVE) {

					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {

						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);

						return $response->withJson ( $return_data );
					}

					$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

					if ($ps_student && $ps_student->role_service = 1) {

						if ($student_service_id > 0) {

							$service_student = $this->db->table ( CONST_TBL_STUDENT_SERVICE . ' as SS' )->select ( 'SS.student_id' )->where ( 'SS.id', $student_service_id )->get ()->first ();

							if ($service_student) {
								// huy dich vu
								$sql = $this->db->table ( CONST_TBL_STUDENT_SERVICE )->where ( 'id', $student_service_id )->update ( [ 
										'delete_at' => date ( "Y-m-d H:i:s" ),
										'updated_at' => date ( "Y-m-d H:i:s" ),
										'user_updated_id' => $user->id
								] );

								$return_data ['_msg_code'] = MSG_CODE_TRUE;
							}
						} else {
							$check = $this->db->table ( CONST_TBL_STUDENT_SERVICE . ' as SS' )->where ( 'SS.student_id', $student_id )->where ( 'SS.service_id', $service_id )->whereNull ( 'SS.delete_at' )->get ()->first ();

							if (! $check) {
								// dang ky dich vu
								$sql = $this->db->table ( CONST_TBL_STUDENT_SERVICE )->insertGetId ( [ 
										'service_id' => $service_id,
										'discount' => 0,
										'student_id' => $student_id,
										'user_created_id' => $user->id
								] );

								$return_data ['_msg_code'] = ($sql) ? MSG_CODE_TRUE : MSG_CODE_FALSE;
							}
						}
					}
				}
			}
		} catch ( Exception $e ) {
			// Log
			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	// dang ky dich vu - APIs of iOS
	public function addServiceStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => [ ]
		);

		$user = $this->user_token;
		$psI18n = new PsI18n ( $this->getUserLanguage ( $user ) );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		$body = $request->getParsedBody ();

		$student_id = isset ( $body ['student_id'] ) ? $body ['student_id'] : '';

		$service_id = isset ( $body ['service_id'] ) ? $body ['service_id'] : '';

		try {
			if ($student_id > 0) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {

					return $response->withJson ( $return_data );
				}

				if ($user->user_type == USER_TYPE_RELATIVE) {

					// Kiem tra tai khoan
					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {

						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);

						return $response->withJson ( $return_data );
					}

					$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

					if ($ps_student && $ps_student->role_service = 1) {

						// check xem dich vu nay da dang ky chua
						$check = $this->db->table ( CONST_TBL_STUDENT_SERVICE . ' as SS' )->where ( 'SS.student_id', $student_id )->where ( 'SS.service_id', $service_id )->whereNull ( 'SS.delete_at' )->get ()->first ();

						if (! $check) {
							// dang ky dich vu
							$sql = $this->db->table ( CONST_TBL_STUDENT_SERVICE )->insertGetId ( [ 
									'service_id' => $service_id,
									'discount' => 0,
									'student_id' => $student_id,
									'created_at' => date ( 'Y-m-d H:i:s' ),
									'user_created_id' => $user->id
							] );
							$return_data ['_msg_code'] = ($sql) ? MSG_CODE_TRUE : MSG_CODE_FALSE;
						}
					}
				}
			}
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	// huy dich vu vios
	public function deleteServiceStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => [ ]
		);

		$user = $this->user_token;

		$psI18n = new PsI18n ( $this->getUserLanguage ( $user ) );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		$service_student_id = $args ['service_student_id'];

		try {

			if ($service_student_id > 0) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {
					return $response->withJson ( $return_data );
				}

				if ($user->user_type == USER_TYPE_RELATIVE) {
					// Check tien trong tai khoan
					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {

						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);

						return $response->withJson ( $return_data );
					}

					$service_student = $this->db->table ( CONST_TBL_STUDENT_SERVICE . ' as SS' )->select ( 'SS.student_id' )->where ( 'id', $service_student_id )->get ()->first ();

					if ($service_student) {

						$ps_student = StudentModel::getStudentForRelative ( $service_student->student_id, $user->member_id );

						if ($ps_student && $ps_student->role_service = 1) {

							// Huy dich vu
							$sql = $this->db->table ( CONST_TBL_STUDENT_SERVICE )->where ( 'id', $service_student_id )->update ( [ 
									'delete_at' => date ( "Y-m-d H:i:s" ),
									'updated_at' => date ( "Y-m-d H:i:s" ),
									'user_updated_id' => $user->id
							] );

							$return_data ['_msg_code'] = $sql ? MSG_CODE_TRUE : MSG_CODE_FALSE;
						}
					}
				}
			}
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	// Lay menu trong ngay cua hoc sinh
	public function menuStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => [ ]
		);

		$user = $this->user_token;

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		$student_id = $args ['student_id'];

		// $page = $args['page_day'];

		$date = $args ['date'];

		// Set style for view HTML
		$user_app_config = json_decode ( $user->app_config );
		$app_config_color = (isset ( $user_app_config->style ) && $user_app_config->style != '') ? $user_app_config->style : 'green';

		if ($app_config_color == 'yellow_orange')
			$app_config_color = 'orange';

		try {
			if ($student_id > 0) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {
					return $response->withJson ( $return_data );
				}

				if ($user->user_type == USER_TYPE_RELATIVE) {

					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {

						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);

						return $response->withJson ( $return_data );
					}

					$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

					if ($ps_student) {

						// Lay thong tin hoc sinh
						$user_info = new \stdClass ();
						$user_info->student_id = $ps_student->id;
						$user_info->birthday = ( string ) PsDateTime::toDMY ( $ps_student->birthday );
						$user_info->first_name = ( string ) $ps_student->first_name;
						$user_info->last_name = ( string ) $ps_student->last_name;
						$user_info->class_id = ( int ) $ps_student->class_id;
						$user_info->class_name = ( string ) $ps_student->class_name;

						$user_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

						if (! $date)
							$date_at = date ( 'Y-m-d' );
						else {
							$date_at = vali::dateTime ( 'Ymd' )->validate ( $date ) ? date ( 'Y-m-d', strtotime ( $date ) ) : date ( 'Y-m-d' );
						}

						$return_data ['_data'] ['title'] = ($date_at == date ( 'Y-m-d' )) ? $psI18n->__ ( "Today's menu Baby" ) : $psI18n->__ ( "Menu for baby" );

						$return_data ['_data'] ['day_at'] = PsDateTime::toFullDayInWeek ( $date_at, $code_lang );

						// $return_data ['_data'] ['title_menu'] = $psI18n->__("Meal");

						// $return_data ['_data'] ['title_food'] = $psI18n->__("Foods");

						if ($user_info->class_id <= 0) {

							$web_content = PsWebContent::BeginHTMLPage ();
							$web_content .= '<div class="w3-padding-16 w3-text-red w3-center">' . $psI18n->__ ( 'No data' ) . '</div>';
							$web_content .= PsWebContent::EndHTMLPage ();
						} else {

							// Lay ten bua an
							$meals = $this->db->table ( TBL_PS_MEALS . ' as M' )
							->select ( 'M.title as meal_title', 'M.id AS meal_id', 'M.note as meal_note' )
							->where ( 'M.is_activated', STATUS_ACTIVE )
							->where ( 'M.ps_customer_id', $ps_student->ps_customer_id )
							->whereRaw ( '(M.ps_workplace_id IS NULL OR M.ps_workplace_id =' . $ps_student->ps_workplace_id . ')' )
							->orderBy ( 'M.iorder' )->distinct ()->get ();

							// Noi dung tra ve kieu wb content
							$web_content = PsWebContent::BeginHTMLPage ();

							$web_content .= '<div class="w3-padding-16">';

							$web_content .= '<table class="w3-table">';

							$web_content .= '<thead>
													<tr class="w3-text-' . $app_config_color . ' w3-border-bottom w3-border-' . $app_config_color . '">
														<th scope="col" class="w3-center" style="width:30%;">' . $psI18n->__ ( 'Meal' ) . '</th>
														<th scope="col" class="w3-center" style="width:70%;">' . $psI18n->__ ( 'Foods' ) . '</th>
													</tr>
											</thead>';

							$web_content .= '<tbody>';

							$check_foods = false;

							foreach ( $meals as $meal ) {

								$foods = $this->db->table ( TBL_PS_MENUS . ' as MN' )
								->join ( TBL_PS_MEALS . ' as M', 'M.id', '=', 'MN.ps_meal_id' )
								->join ( TBL_PS_FOODS . ' as F', 'MN.ps_food_id', '=', 'F.id' )
								->leftJoin ( CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', 'F.ps_image_id' )
								->select ( 'F.title as food_title', 'F.file_image as food_image', 'F.note as note', 'I.file_name', 'MN.note as mn_note' )
								->where ( 'M.is_activated', STATUS_ACTIVE )
								->where ( 'F.is_activated', STATUS_ACTIVE )
								->whereDate ( 'MN.date_at', $date_at )
								->where ( 'MN.ps_meal_id', $meal->meal_id )
								->where ( 'MN.ps_object_group_id', $ps_student->ps_obj_group_id )
								->where ( 'MN.ps_customer_id', $ps_student->ps_customer_id )
								->whereRaw ( '(MN.ps_workplace_id IS NULL OR MN.ps_workplace_id =' . $ps_student->ps_workplace_id.')' )
								->orderBy ( 'MN.iorder' )->distinct ()->get ();

								$web_content .= '<tr class="w3-border-bottom w3-border-' . $app_config_color . '">';

								
								if ($meal->meal_note != '')
								
									$web_content .= '<td class="w3-border-' . $app_config_color . '" style="border-right: 1px dashed; padding-left:3px;">' . PsString::htmlSpecialChars ( $meal->meal_title ).'<br/><small>'.PsString::htmlSpecialChars ( $meal->meal_note ) . '</small></td>';
								else
									$web_content .= '<td class="w3-border-' . $app_config_color . '" style="border-right: 1px dashed; padding-left:3px;">' . PsString::htmlSpecialChars ( $meal->meal_title ) . '</td>';
								

								$web_content .= '<td>';

								foreach ( $foods as $food ) {

									$check_foods = true;

									$web_content .= '<div class="w3-row w3-padding-16">';

									// icon food
									$web_content .= '<div class="w3-col s3">';
									
									if ($food->food_image != '') {
										
										$web_content .= '<img src="' . PsString::getUrlFoodImage ( $food->food_image ) . '" style="width:100%"/>';
										
									} elseif ($food->file_name != '') {
										$web_content .= '<img src="' . PsString::getUrlPsImage ( $food->file_name ) . '" style="width:100%"/>';
									}
									
									$web_content .= '</div>';

									// Title
									$web_content .= '<div class="w3-col s9" style="padding-left:5px;">';

									$web_content .= '<div class="w3-text-green" style="padding-top:7px;">' . PsString::htmlSpecialChars ( $food->food_title ) . '</div>';

									if (PsString::trimString ( $food->note ) != '') {
										
										$web_content .= '<div style="font-style:italic;"><small>' . $psI18n->__ ( 'Note' ) . ': ' . PsString::nl2brChars ( $food->note ) . '</small></div>';
										
										if (PsString::trimString ( $food->mn_note ) != '') {
											$web_content .= '<div style="font-style:italic;"><small>' . PsString::nl2brChars ( $food->mn_note ) . '</small></div>';
										}
									} elseif (PsString::trimString ( $food->mn_note ) != '') {
										$web_content .= '<div style="font-style:italic;"><small>' . $psI18n->__ ( 'Note' ) . ': ' . PsString::nl2brChars ( $food->mn_note ) . '</small></div>';
									}

									$web_content .= '</div>';

									$web_content .= '</div>';
								}

								$web_content .= '</td>';

								$web_content .= '</tr>';
							}

							$web_content .= '</tbody>';
							$web_content .= '</table>';
							$web_content .= '</div>';

							$web_content .= PsWebContent::EndHTMLPage ();
						}

						if (! $check_foods) {
							$web_content = PsWebContent::BeginHTMLPage ();
							$web_content .= '<div class="w3-padding-16 w3-text-red w3-center">' . $psI18n->__ ( 'No data' ) . '</div>';
							$web_content .= PsWebContent::EndHTMLPage ();
						}

						$return_data ['_msg_code'] = MSG_CODE_TRUE;

						// $return_data ['_msg_text'] = count($data) ? $psI18n->__("Have data") : $psI18n->__("No data");

						$return_data ['_data'] ['user_info'] = $user_info;

						$return_data ['_data'] ['content'] = $web_content; // 'Thực đơn của bé';
					} else {
						
						//$return_data ['_msg_code'] = MSG_CODE_500;

						//$return_data ['message'] = $psI18n->__ ( 'You can not see the student\'s information.' );
						
						$return_data ['_msg_code'] = MSG_CODE_TRUE;
						
						$web_content = PsWebContent::BeginHTMLPage ();

						$web_content .= '<div class="w3-padding-16 w3-text-red w3-center">'.$psI18n->__ ( 'No data' ).'</div>';

						$web_content .= PsWebContent::EndHTMLPage ();
						
						$return_data ['_data'] ['content'] = $web_content; // 'Thực đơn của bé';
					}
				}
			} else {
				
				$return_data ['_msg_code'] = MSG_CODE_TRUE;
				
				$web_content = PsWebContent::BeginHTMLPage ();

				$web_content .= '<div class="w3-padding-16 w3-text-red w3-center">'.$psI18n->__ ( 'No data' ).'</div>';

				$web_content .= PsWebContent::EndHTMLPage ();
				
				$return_data ['_data'] ['content'] = $web_content; // 'Thực đơn của bé';
			}
		} catch ( Exception $e ) {
			// Log
			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message']   = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}
		return $response->withJson ( $return_data );

	}

	// Lay danh sach hoat dong cua hoc sinh theo ngay
	public function featureStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => [ ]
		);

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		$student_id = $args ['student_id'];

		$page = $args ['page_day'];

		// try {

		if ($student_id > 0) {

			if (! PsAuthentication::checkDevice ( $user, $device_id )) {
				return $response->withJson ( $return_data );
			}

			if ($user->user_type == USER_TYPE_RELATIVE) {

				// Kiem tra tai khoan
				$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

				if (! $amount_info) {

					$return_data = array (
							'_msg_code' => MSG_CODE_PAYMENT,
							'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
					);

					return $response->withJson ( $return_data );
				}

				$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

				if ($ps_student) {

					// Lay thong tin hoc sinh
					$user_info = new \stdClass ();
					$user_info->student_id = $ps_student->id;
					$user_info->birthday = ( string ) PsDateTime::toDMY ( $ps_student->birthday );
					$user_info->first_name = ( string ) $ps_student->first_name;
					$user_info->last_name = ( string ) $ps_student->last_name;
					$user_info->class_id = $ps_student->class_id;
					$user_info->class_name = ($ps_student->class_name != '') ? ( string ) $ps_student->class_name : '';
					$user_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

					// lay ngay
					$page = ($page < - (PS_CONST_LIMIT_DAY_FEATURE) || $page > PS_CONST_LIMIT_DAY_FEATURE) ? 0 : $page;

					$date_at = date ( 'Y-m-d', strtotime ( $page . " days" ) );

					$day_info = new \stdClass ();

					// $weekday = PsDateTime::toDayInWeek($date_at);
					// $day_info->today = $weekday . ', ' . PsDateTime::toDMY($date_at);

					$day_info->day_at = PsDateTime::toFullDayInWeek ( $date_at, $this->getUserLanguage ( $user ) );
					$day_info->next_day = $page + 1;
					$day_info->pre_day = $page - 1;
					$day_info->to_day = ($page == 0) ? 1 : 0;

					// Lay danh sach hoat dong
					$features = $this->db->table ( TBL_FEATURE_BRANCH . ' as FB' )
					->leftjoin ( TBL_FEATURE_BRANCH_TIMES . ' as FBT', 'FBT.ps_feature_branch_id', '=', 'FB.id' )
					->leftjoin ( TBL_FEATURE_BRANCH_TIME_MY_CLASS . ' as FBTC', 'FBTC.ps_feature_branch_time_id', '=', 'FBT.id' )
					->join ( TBL_FEATURE . ' as F', 'F.id', '=', 'FB.feature_id' )
					->leftJoin ( CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', 'FB.ps_image_id' )
					->leftJoin ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'FBT.ps_class_room_id' )
					->selectRaw ( 'FB.id as id, FB.name as feature_title, I.file_name, FBT.start_time as start_at, FBT.end_time as end_at, CR.title as class_room_title, FBTC.ps_class_room AS ps_class_room,FBTC.id AS fbtc_id,FBTC.note as note, FBT.note AS fbt_note' )
					->where ( 'F.ps_customer_id', $ps_student->ps_customer_id )
					->whereDate ( 'FBT.start_at', '<=', $date_at )
					->whereDate ( 'FBT.end_at', '>=', $date_at )
					->where ( 'F.is_activated', STATUS_ACTIVE )
					->where ( 'FB.is_activated', STATUS_ACTIVE )
					->where ( function ($query) use ($ps_student) {
						$query->where ( 'FBTC.ps_myclass_id', '=', $ps_student->class_id );
						// $query->orWhereRaw ( 'F.ps_obj_group_id', $ps_student->ps_obj_group_id )->orwhereNull ( 'F.ps_obj_group_id' );
					} );

					$number_day = PsDateTime::getNumberDayOfDate ( $date_at );

					if ($number_day == '0') {
						$features = $features->where ( 'FBT.is_sunday', '=', STATUS_ACTIVE );
					} elseif ($number_day == 6) {
						$features = $features->where ( 'FBT.is_saturday', '=', STATUS_ACTIVE );
					}

					$_features = $features->orderBy ( 'start_at' )->distinct ( 'id' )->get (); // thong tin hoat dong

					// Lay thong tin dich vu theo thoi khoa bieu
					$services = $this->db->table ( TBL_PS_SERVICE_COURSES . ' as SC' )->join ( CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'SC.ps_service_id' )->join ( CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.ps_service_course_id', '=', 'SC.id' )->join ( TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', 'SCS.ps_service_course_id', '=', 'SC.id' )->leftJoin ( CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', 'S.ps_image_id' )->leftJoin ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'SCS.ps_class_room_id' )->selectRaw ( 'Null as id, S.title as feature_title, I.file_name, SCS.start_time_at as start_at, SCS.end_time_at as end_at, CR.title as class_room_title, null AS ps_class_room ,SCS.id AS fbtc_id, SCS.note as note, null AS fbt_note' )->where ( 'SS.student_id', $student_id )->whereNull ( 'SS.delete_at' )->whereDate ( 'SCS.date_at', $date_at )->whereDate ( 'SC.start_at', '<=', $date_at )->whereDate ( 'SC.end_at', '>=', $date_at )->where ( 'S.ps_customer_id', $ps_student->ps_customer_id )->where ( 'SC.is_activated', STATUS_ACTIVE )->where ( 'S.enable_schedule', ENABLE_ROLL_SCHEDULE )->where ( 'SCS.is_activated', STATUS_ACTIVE )->where ( 'S.is_activated', STATUS_ACTIVE );

					// $_services = $services->orderBy('start_at')->get(); // thong tin dich vu

					// Ghep dich vu va hoat dong
					$feature_services = $services->unionall ( $features )->orderBy ( 'start_at' )->distinct ()->get ();

					$data_feature = $data = $_option = array ();

					foreach ( $feature_services as $feature_service ) {

						$data_feature = array ();

						$data_feature ['feature_title'] = $feature_service->feature_title;
						$data_feature ['icon'] = PsString::getUrlPsImage ( $feature_service->file_name );
						$data_feature ['time_at'] = PsDateTime::getTime ( $feature_service->start_at ) . '-' . PsDateTime::getTime ( $feature_service->end_at );
						$data_feature ['class_room'] = ($feature_service->class_room_title != '') ? $feature_service->class_room_title : '';
						$data_feature ['ps_class_room'] = ($feature_service->ps_class_room != '') ? $feature_service->ps_class_room : '';

						$data_feature ['note'] = $feature_service->note;
						$data_feature ['fbt_note'] = $feature_service->fbt_note;

						if ($feature_service->id != null) {

							// Lay danh gia cua hoat dong
							$rate_features = FeatureOptionModel::getRateFeature ( $student_id, $feature_service->id, $date_at, $ps_student->ps_customer_id );

							foreach ( $rate_features as $rate_feature ) {

								$option = ($rate_feature->type == 1) ? $rate_feature->name : $rate_feature->note;

								array_push ( $_option, $option );
							}

							$data_feature ['rate'] = implode ( '<br>', $_option );

							$_option = array ();
						} else { // lay danh gia cua dich vu

							$rate_features = StudentServiceCourseCommentModel::getRateService ( $student_id, $feature_service->fbtc_id );

							foreach ( $rate_features as $rate_feature ) {
								$option = ($rate_feature->type == 1) ? ( string ) $rate_feature->name : ( string ) $rate_feature->note;
								array_push ( $_option, $option );
							}

							$data_feature ['rate'] = implode ( '<br>', $_option );

							$_option = array ();
						}

						array_push ( $data, $data_feature );
					}

					// Tim hoat dong trung lich voi dich vu
					/*
					 * foreach ($_features as $key => $_feature) {
					 *
					 * foreach ($_services as $key => $_service) {
					 *
					 * if ((PsDateTime::getTime($_service->start_at) < PsDateTime::getTime($_feature->start_at)) && (PsDateTime::getTime($_service->end_at) < PsDateTime::getTime($_feature->end_at)) && (PsDateTime::getTime($_service->start_at) < PsDateTime::getTime($_feature->end_at)) && (PsDateTime::getTime($_service->end_at) > PsDateTime::getTime($_feature->start_at)) || (PsDateTime::getTime($_service->start_at) < PsDateTime::getTime($_feature->start_at)) && (PsDateTime::getTime($_service->end_at) > PsDateTime::getTime($_feature->end_at)) && (PsDateTime::getTime($_service->start_at) < PsDateTime::getTime($_feature->end_at)) && (PsDateTime::getTime($_service->end_at) > PsDateTime::getTime($_feature->start_at)) || (PsDateTime::getTime($_service->start_at) > PsDateTime::getTime($_feature->start_at)) && (PsDateTime::getTime($_service->end_at) < PsDateTime::getTime($_feature->end_at)) && (PsDateTime::getTime($_service->start_at) < PsDateTime::getTime($_feature->end_at)) && (PsDateTime::getTime($_service->end_at) > PsDateTime::getTime($_feature->start_at)) || (PsDateTime::getTime($_service->start_at) > PsDateTime::getTime($_feature->start_at)) && (PsDateTime::getTime($_service->end_at) > PsDateTime::getTime($_feature->end_at)) && (PsDateTime::getTime($_service->start_at) < PsDateTime::getTime($_feature->end_at)) && (PsDateTime::getTime($_service->end_at) > PsDateTime::getTime($_feature->start_at))) {
					 *
					 * $_option = array();
					 *
					 * $data_feature['feature_title'] = $_feature->feature_title;
					 *
					 * $data_feature['icon'] = PsString::getUrlPsImage($_feature->file_name);
					 *
					 * $data_feature['time_at'] = PsDateTime::getTime($_feature->start_at) . '-' . PsDateTime::getTime($_feature->end_at);
					 *
					 * $data_feature['class_room'] = $_feature->class_room_title;
					 *
					 * $data_feature['ps_class_room'] = $_feature->ps_class_room;
					 *
					 * $data_feature['note'] = $_feature->note;
					 *
					 * $data_feature['fbt_note'] = $_feature->fbt_note;
					 *
					 * $rate_features = FeatureOptionModel::getRateFeature($student_id, $_feature->id, $date_at, $ps_student->ps_customer_id);
					 *
					 * foreach ($rate_features as $rate_feature) {
					 * $option = ($rate_feature->type == 1) ? $rate_feature->name : $rate_feature->note;
					 * array_push($_option, $option);
					 * }
					 * $data_feature['rate'] = implode('<br>', $_option);
					 *
					 * foreach (array_keys($data, $data_feature, true) as $key) {
					 * unset($data[$key]); // unset hoat dong bi trung voi dich vu
					 * }
					 * }
					 * }
					 * }
					 */

					$data = array_values ( $data );

					// Noi dung tra ve kieu wb content
					$web_content = PsWebContent::BeginHTMLPage ();

					$web_content .= '<div class="w3-container">';

					foreach ( $data as $value ) {

						$web_content .= '<div class="w3-row w3-padding-16 w3-border-bottom">';

						// icon
						$web_content .= '<div class="w3-col s1">';
						if ($value ['icon'] != '') {
							$web_content .= '<img src="' . $value ['icon'] . '" style="width:100%"/>';
						}

						$web_content .= '</div>';

						// Title + content
						$web_content .= '<div class="w3-col s11 w3-container">';

						$web_content .= '<div style="font-style:italic;padding-top:0px;"><small>' . $value ['time_at'] . '</small></div>';

						if ($value ['ps_class_room'] != '') {

							$web_content .= '<div style="font-style:italic;"><small>' . $psI18n->__ ( 'Place' ) . ': ' . PsString::htmlSpecialChars ( $value ['ps_class_room'] ) . '</small></div>';
						} elseif ($value ['class_room'] != '') {
							$web_content .= '<div style="font-style:italic;"><small>' . $psI18n->__ ( 'Place' ) . ': ' . PsString::htmlSpecialChars ( $value ['class_room'] ) . '</small></div>';
						}

						$web_content .= '<div class="w3-text-green" style="padding-top:7px;">'. PsString::htmlSpecialChars ( $value ['feature_title'] ) . '</div>';

						if ($value ['note'] != '') {

							$web_content .= '<div style="font-style:italic;padding-top:7px;font-weight:bolder;">' . $psI18n->__ ( 'Content' ) . '</div>';

							$web_content .= '<div style="padding-top:5px;" class="w3-justify">'. PsString::nl2brChars ( $value ['note'] ) . '</div>';
						} elseif ($value ['fbt_note'] != '') {

							$web_content .= '<div style="font-style:italic;padding-top:7px;font-weight:bolder;">' . $psI18n->__ ( 'Content' ) . '</div>';

							$web_content .= '<div style="padding-top:5px;" class="w3-justify">'. PsString::nl2brChars ( $value ['fbt_note'] ) . '</div>';
						}

						if ($value ['rate'] != '') {

							$web_content .= '<div style="font-style:italic;padding-top:7px;font-weight:bolder;">' . $psI18n->__ ( 'Comment of Teacher' ) . '</div>';

							$web_content .= '<div style="padding-top:5px;" class="w3-justify">'. PsString::nl2brChars ( $value ['rate'] ) . '</div>';
						}

						$web_content .= '</div>';

						$web_content .= '</div>';
					}

					$web_content .= '</div>';

					$web_content .= PsWebContent::EndHTMLPage ();

					$return_data ['_data'] ['content'] = $web_content;

					$return_data ['_data'] ['user_info'] = $user_info;

					$return_data ['_data'] ['day_info'] = $day_info;

					// $return_data['_data']['data_info'] = $data;

					$return_data ['_msg_code'] = MSG_CODE_TRUE;
				}
			}
		}
		/*
		 * } catch (Exception $e) {
		 *
		 * $this->logger->err($e->getMessage());
		 *
		 * $return_data['_msg_code'] = MSG_CODE_500;
		 *
		 * $return_data['message'] = $psI18n->__('Network connection is not stable. Please do it again in a few minutes.');
		 * }
		 */

		return $response->withJson ( $return_data );

	}

	// Bao phi theo thang-nam chon hoặc theo tháng hiện tại
	public function feeStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => [ ]
		);

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		$student_id = $args ['student_id'];

		$date = isset ( $args ['date'] ) ? ($args ['date'] != '' ? $args ['date'] : date ( 'mY' )) : date ( 'mY' );

		// $this->WriteLog ( "XEM BAO PHI 1: ".$date );

		try {

			if (PsDateTime::validateDate ( '01' . $date, 'dmY' ) && $student_id > 0) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {
					return $response->withJson ( $return_data );
				}

				if ($user->user_type == USER_TYPE_RELATIVE) {

					// Check tien trong tai khoan
					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {

						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);

						return $response->withJson ( $return_data );
					}

					$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

					if ($ps_student) {

						$date_at = substr ( $date, 0, - 4 ) . '-' . substr ( $date, 2 );

						// Lay thong tin hoc sinh
						$user_info = new \stdClass ();

						$user_info->student_id 	= ( int ) $ps_student->id;
						$user_info->birthday 	= ( string ) PsDateTime::toDMY ( $ps_student->birthday );
						$user_info->first_name 	= ( string ) $ps_student->first_name;
						$user_info->last_name 	= ( string ) $ps_student->last_name;
						$user_info->class_id 	= ( int ) $ps_student->class_id;
						$user_info->class_name 	= ($ps_student->class_name != '') ? ( string ) $ps_student->class_name : '';
						$user_info->avatar_url 	= ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;

						$user_info->date_info = $date_at;

						$data = array ();

						$data_info = array ();

						$ps_work_places = null;

						if ($user_info->class_id > 0) {

							
							//$ps_workplace_id = $ps_student->ps_workplace_id;
							//$ps_work_places = PsWorkPlacesModel::findById ( $ps_workplace_id );
							
							// Lay cau hinh cua co so
							$ps_work_places = PsWorkPlacesModel::getColumnById($ps_student->ps_workplace_id, ' config_choose_charge_fee_mobile as config_choose_charge_fee_mobile, is_receipt as is_receipt ' );
						}

						if ($user_info->class_id <= 0 || ! $ps_work_places) {

							$web_content = PsWebContent::BeginHTMLPage ();
							$web_content .= '<div class="w3-padding-16 w3-text-red w3-center">' . $psI18n->__ ( 'No data' ) . '</div>';
							$web_content .= PsWebContent::EndHTMLPage ();
							
						} else {

							// Neu nguon phi từ Quản lý thông báo phí
							if ($ps_work_places->is_receipt == M_FEE_NOTIFICATION) {

								$receipt_date = date ( "Y-m-d", strtotime ( '01-' . $user_info->date_info ) );

								$ps_fee_receipt = PsFeeReceiptModel::findOfStudentByDate ( $student_id, $receipt_date );
								
								$txt_note = '';

								if (! $ps_fee_receipt) {

									$data_info ['title'] = $psI18n->__ ( 'Data fee' );

									$data_info ['value'] = $psI18n->__ ( 'Not data fee' );

									$data_info ['type']  = ('');

									array_push ( $data, $data_info );
								} else {
									
									if ($ps_fee_receipt->note != '') {
										$txt_note = '<strong>'.$psI18n->__ ( 'Note' ).'</strong>:<br>'.PsString::nl2brChars($ps_fee_receipt->note);
									}

									if ($ps_work_places->config_choose_charge_fee_mobile == M_VIEW_FEE_TOTAL) { // Hien thi theo tong

										$data_info = array ();

										// Lấy số tiền phải nộp
										$data_info ['title'] = $psI18n->__ ( 'Total amount to pay' ) . ' ';

										$data_info ['value'] = ( string ) PsNumber::format_price ( $ps_fee_receipt->receivable_amount, '' );

										$data_info ['type']  = $psI18n->__ ('$');

										array_push ( $data, $data_info );
									
									} else { // Hiển thị chi tiết
										
										// Lấy danh sách các khoản thu nếu chọn hiển thị chi tiết
										$ps_fee_receivable_students = PsFeeReceivableStudentModel::getFeeReceivableStudentOfFeeReceipt ( $ps_fee_receipt->id );

										foreach ( $ps_fee_receivable_students as $ps_fee_receivable_student ) {

											$data_info = array ();

											$data_info ['title'] = ( string ) $ps_fee_receivable_student->title;

											$data_info ['value'] = ( string ) PsNumber::format_price ( $ps_fee_receivable_student->amount, '' );

											$data_info ['type']  = $psI18n->__ ( 'VND' );

											array_push ( $data, $data_info );
										}
									
										$data_info = array ();
										
										if ($ps_work_places->config_choose_charge_fee_mobile == M_VIEW_FEE_DETAIL) {
											
											if ($ps_fee_receipt->payment_status == PS_PAYMENT_STATUS_PAID) {
												$payment_status = '<span class="w3-text-green">' . $psI18n->__ ( 'Paid' ) . '</span>';
											} else {
												$payment_status = '<span class="w3-text-orange">' . $psI18n->__ ( 'Unpaid' ) . '</span>';
											}

											// Lấy số tiền phải nộp
											$data_info ['title'] = $psI18n->__ ( 'Total amount to pay' ) . ' ';
	
											$data_info ['value'] = ( string ) PsNumber::format_price ( $ps_fee_receipt->receivable_amount, '' );
	
											$data_info ['type'] = $psI18n->__ ( '$' );
	
											array_push ( $data, $data_info );
	
											$data_info = array ();
	
											$data_info ['title'] = $psI18n->__ ( 'Paid amount' ) . ' ';
	
											$data_info ['value'] = ( string ) PsNumber::format_price ( $ps_fee_receipt->collected_amount, '' );
	
											$data_info ['type'] = $psI18n->__ ( '$' );
	
											array_push ( $data, $data_info );
	
											$data_info = array ();
	
											$data_info ['title'] = $psI18n->__ ( 'Balance' ) . ' ';
	
											$data_info ['value'] = ( string ) PsNumber::format_price ( $ps_fee_receipt->balance_amount, '' );
	
											$data_info ['type'] = $psI18n->__ ( '$' );
	
											array_push ( $data, $data_info );
										}
									}
								}
							} elseif ($ps_work_places->is_receipt == M_FEE) {// Quan ly phi

								// Tim phieu thu gan $date_at nhat
								// $ps_receipt_prev_of_student = PsReceiptModel::findReceiptPrevOfStudentByDate($user_info->student_id, '01-' . $user_info->date_info);

								// Phiếu thu
								$ps_receipt = PsReceiptModel::findOfStudentByDate ( $user_info->student_id, '01-' . $user_info->date_info );

								// Phiếu báo
								$ps_fee_reports = PsFeeReportsModel::findOfStudentByDate ( $user_info->student_id, '01-' . $user_info->date_info );

								if ($ps_fee_reports && $ps_receipt && $ps_receipt->is_public == STATUS_ACTIVE) {
									
									if ($ps_receipt->note != '') {
										$txt_note = '<strong>'.$psI18n->__ ( 'Note' ).'</strong>:<br>'.PsString::nl2brChars($ps_receipt->note);
									}

									$data_info = array ();

									// Lấy dự kiến thu
									$data_info ['title'] = $psI18n->__ ( 'Expected monthly income' ) . ' ';

									$data_info ['value'] = ( string ) PsNumber::format_price ( $ps_fee_reports->amount, '' );

									$data_info ['type'] = $psI18n->__ ( '$' );

									array_push ( $data, $data_info );

									// Dư tháng trước thực tế
									$data_info ['title'] = $psI18n->__ ( 'Balance last month' ) . ' ';

									$data_info ['value'] = ( string ) PsNumber::format_price ( $ps_receipt->balance_last_month_amount, '' );

									$data_info ['type'] = $psI18n->__ ( '$' );

									array_push ( $data, $data_info );

									// Tổng tiền phải nộp
									$data_info ['title'] = $psI18n->__ ( 'Total amount to pay' ) . ' ';

									$data_info ['value'] = ( string ) PsNumber::format_price ( $ps_fee_reports->amount, '' );

									$data_info ['type'] = $psI18n->__ ( '$' );

									array_push ( $data, $data_info );

									// Đã nộp
									$data_info ['title'] = $psI18n->__ ( 'Paid amount' ) . ' ';

									$data_info ['value'] = ( string ) PsNumber::format_price ( $ps_receipt->collected_amount, '' );

									$data_info ['type'] = $psI18n->__ ( '$' );

									array_push ( $data, $data_info );

									// Số dư
									$data_info ['title'] = $psI18n->__ ( 'Balance' ) . ' ';

									$data_info ['value'] = ( string ) PsNumber::format_price ( $ps_receipt->balance_amount, '' );

									$data_info ['type'] = $psI18n->__ ( '$' );

									array_push ( $data, $data_info );
								} else {

									$data_info = array ();

									$data_info ['title'] = $psI18n->__ ( 'Data fee' );

									$data_info ['value'] = $psI18n->__ ( 'Not data fee' );

									$data_info ['type'] = ('');

									array_push ( $data, $data_info );
								}
							} elseif ($ps_work_places->is_receipt == M_FEE_NEWSLETTER) {
								
								$receipt_date = date ( "Ym", strtotime ( '01-' . $user_info->date_info ) );
								
								$psFeeNewsLetters = PsFeeNewsLettersModel::findOfStudentByDate( $ps_student->ps_workplace_id, $receipt_date );
								
							}

							// Noi dung tra ve kieu wb content
							$web_content = PsWebContent::BeginHTMLPage ();

							$web_content .= '<div class="w3-padding-16">';
								
								if ($ps_work_places->is_receipt == M_FEE_NOTIFICATION || $ps_work_places->is_receipt == M_FEE) {
	
									$web_content .= '<table class="w3-table">';
									
									if (isset($payment_status) && $payment_status != '') {
		
										$web_content .= '<tr class="w3-border-bottom">
															<th colspan="2" class="w3-right-align">' . $payment_status . '</th>
														 </tr>';
									}
		
									foreach ( $data as $fee ) {
		
										$web_content .= '<tr class="w3-border-bottom">';
										$web_content .= '<td>' . PsString::htmlSpecialChars ( $fee ['title'] ) . '</td>';
										$web_content .= '<td class="w3-right-align w3-text-red">' . PsString::htmlSpecialChars ( $fee ['value'] ) . '</td>';
										$web_content .= '</tr>';
									}
									
									if ($txt_note != '') {
										$web_content .= '<tr><td colspan="2" class="w3-left-align">'.$txt_note.'</td></tr>';
									}
		
									$web_content .= '</table>';
								
								} elseif ($ps_work_places->is_receipt == M_FEE_NEWSLETTER) {
									
									if ($psFeeNewsLetters) {
										$web_content .= '<div class="w3-padding-7">'.$psFeeNewsLetters->title.'</div>';
										$web_content .= '<div>'.$psFeeNewsLetters->note.'</div>';									
									} else {
										$web_content .= '<div class="w3-padding-16 w3-text-red w3-center">' . $psI18n->__ ( 'Not data fee' ) . '</div>';
									}
									
								}
							
							$web_content .= '</div>';

							$web_content .= PsWebContent::EndHTMLPage ();
						}

						$return_data ['_data'] ['user_info'] = $user_info;

						$return_data ['_data'] ['content'] = $web_content;

						$return_data ['_msg_code'] = MSG_CODE_TRUE;
					}
				}
			}
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	// Tải báo phí
	public function downloadReportFees(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => [ ]
		);

		$user = $this->user_token;

		$psI18n = new PsI18n ( $this->getUserLanguage ( $user ) );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		$student_id = $args ['student_id'];

		$date = ($args ['date']);

		try {

			if (PsDateTime::validateDate ( '01' . $date, 'dmY' ) && $student_id > 0) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {
					return $response->withJson ( $return_data );
				}

				if ($user->user_type == USER_TYPE_RELATIVE) {

					// Kiem tra tai khoan
					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {

						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);

						return $response->withJson ( $return_data );
					}

					$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

					if ($ps_student) {

						// $date_at = substr($date, 0, - 4) . '-' . substr($date, 2);

						$return_data ['_data'] = PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;

						$return_data ['_msg_code'] = MSG_CODE_TRUE;
					}
				}
			}
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	// Camera lop hoc
	public function classCamera(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => [ ]
		);

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		$student_id = $args ['student_id'];

		try {

			if (! PsAuthentication::checkDevice ( $user, $device_id )) {
				return $response->withJson ( $return_data );
			}

			$cameras = array ();

			if (($student_id > 0) && ($user->user_type == USER_TYPE_RELATIVE)) {

				// Check tien trong tai khoan
				$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

				if (! $amount_info) {

					$return_data = array (
							'_msg_code' => MSG_CODE_PAYMENT,
							'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
					);

					return $response->withJson ( $return_data );
				}

				$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

				if ($ps_student && $ps_student->class_id > 0) {

					$return_data ['_msg_code'] = MSG_CODE_TRUE;

					// Lay thong tin camera
					$cameras = $this->db->table ( TBL_CAMERA . ' as C' )->join ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'C.ps_class_room_id' )->join ( CONST_TBL_MYCLASS . ' as M', 'M.ps_class_room_id', '=', 'CR.id' )->join ( TBL_PS_WORK_PLACES . ' as W', 'W.id', '=', 'CR.ps_workplace_id' )->join ( CONST_TBL_PS_CUSTOMER . ' as Cus', 'Cus.id', '=', 'W.ps_customer_id' )->select ( 'C.id', 'C.title', 'C.image_name', 'C.year_data', 'Cus.school_code' )->where ( 'M.id', '=', $ps_student->class_id )->where ( 'C.is_activated', '=', STATUS_ACTIVE )->where ( 'CR.is_global', '=', CAMERA_NOT_GLOBAL )->get ();
				}
			} elseif ($user->user_type == USER_TYPE_TEACHER) {

				// Lay lop hoc cua giao vien
				$ps_member = PsMemberModel::getMember ( $user->member_id );

				if ($ps_member && $ps_member->myclass_id > 0) {

					$return_data ['_msg_code'] = MSG_CODE_TRUE;

					// Lay thong tin camera
					$cameras = $this->db->table ( TBL_CAMERA . ' as C' )->join ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'C.ps_class_room_id' )->join ( CONST_TBL_MYCLASS . ' as M', 'M.ps_class_room_id', '=', 'CR.id' )->join ( TBL_PS_WORK_PLACES . ' as W', 'W.id', '=', 'CR.ps_workplace_id' )->join ( CONST_TBL_PS_CUSTOMER . ' as Cus', 'Cus.id', '=', 'W.ps_customer_id' )->select ( 'C.id', 'C.title', 'C.image_name', 'C.year_data', 'Cus.school_code' )->where ( 'M.id', '=', $ps_member->myclass_id )->where ( 'C.is_activated', '=', STATUS_ACTIVE )->where ( 'CR.is_global', '=', CAMERA_NOT_GLOBAL )->get ();
				}
			}

			$data_info = array ();

			foreach ( $cameras as $cam ) {

				$data = array ();

				$data ['id'] = $cam->id;

				$data ['title'] = $cam->title;

				$data ['image_url'] = ($cam->image_name != '') ? PsString::getUrlMediaAvatar ( $cam->school_code, $cam->year_data, $cam->image_name, MEDIA_TYPE_CAMERA ) : PS_CONST_API_URL_IMAGE_DEFAULT_NOTLOGO;

				array_push ( $data_info, $data );
			}

			$return_data ['_data'] ['data_info'] = $data_info;
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}
		return $response->withJson ( $return_data );

	}

	// camera ngoai canh
	public function globalCamera(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => [ ]
		);

		$user = $this->user_token;

		$psI18n = new PsI18n ( $this->getUserLanguage ( $user ) );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		$student_id = $args ['student_id'];

		$return_data ['_data'] ['data_info'] = [ ];

		try {

			if (! PsAuthentication::checkDevice ( $user, $device_id )) {

				return $response->withJson ( $return_data );
			}

			if ($student_id > 0 && $user->user_type == USER_TYPE_RELATIVE) {

				// Check tien trong tai khoan
				$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

				if (! $amount_info) {

					$return_data = array (
							'_msg_code' => MSG_CODE_PAYMENT,
							'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
					);

					return $response->withJson ( $return_data );
				}

				$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

				if ($ps_student && $ps_student->class_id > 0) {

					$return_data ['_msg_code'] = MSG_CODE_TRUE;

					$cameras = $this->db->table ( TBL_CAMERA . ' as C' )->join ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'C.ps_class_room_id' )->join ( TBL_PS_WORK_PLACES . ' as W', 'W.id', '=', 'CR.ps_workplace_id' )->join ( CONST_TBL_PS_CUSTOMER . ' as Cus', 'Cus.id', '=', 'W.ps_customer_id' )->select ( 'C.id', 'C.title', 'C.image_name', 'C.year_data', 'Cus.school_code' )->where ( 'W.id', '=', $ps_student->ps_workplace_id )->where ( 'C.is_activated', '=', STATUS_ACTIVE )->where ( 'CR.is_global', '=', CAMERA_GLOBAL )->get ();
				}
			} elseif ($user->user_type == USER_TYPE_TEACHER) {

				// Lay lop hoc cua giao vien
				$ps_member = PsMemberModel::getMember ( $user->member_id );

				if ($ps_member && $ps_member->ps_workplace_id > 0) {

					$return_data ['_msg_code'] = MSG_CODE_TRUE;

					/*
					 * $cameras = $this->db->table ( TBL_CAMERA . ' as C' )
					 * ->join ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'C.ps_class_room_id' )
					 * ->join ( TBL_PS_WORK_PLACES . ' as W', 'W.id', '=', 'CR.ps_workplace_id' )
					 * ->join ( CONST_TBL_PS_CUSTOMER.' as Cus', 'Cus.id', '=', 'W.ps_customer_id' )
					 * ->select ( 'C.id', 'C.title', 'C.image_name' , 'C.year_data', 'Cus.school_code')
					 * ->where ( 'W.id', '=', $ps_student->ps_workplace_id )
					 * ->where ( 'C.is_activated', '=', STATUS_ACTIVE )
					 * ->where ( 'CR.is_global', '=', CAMERA_GLOBAL )->get ();
					 */

					// Lay danh sach camera chung
					$cameras = $this->db->table ( TBL_CAMERA . ' as C' )->join ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'C.ps_class_room_id' )->join ( TBL_PS_WORK_PLACES . ' as W', 'W.id', '=', 'CR.ps_workplace_id' )->join ( CONST_TBL_PS_CUSTOMER . ' as Cus', 'Cus.id', '=', 'W.ps_customer_id' )->select ( 'C.id', 'C.title', 'C.image_name', 'C.year_data', 'Cus.school_code' )->where ( 'W.id', '=', $ps_member->ps_workplace_id )->where ( 'C.is_activated', '=', STATUS_ACTIVE )->where ( 'CR.is_global', '=', CAMERA_GLOBAL )->get ();
				}
			}

			$data_info = array ();

			foreach ( $cameras as $cam ) {
				$data = array ();
				$data ['id'] = $cam->id;

				$data ['title'] = $cam->title;

				$data ['image_url'] = ($cam->image_name != '') ? PsString::getUrlMediaAvatar ( $cam->school_code, $cam->year_data, $cam->image_name, MEDIA_TYPE_CAMERA ) : PS_CONST_API_URL_IMAGE_DEFAULT_NOTLOGO;

				array_push ( $data_info, $data );
			}

			$return_data ['_data'] ['data_info'] = $data_info;
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}
		return $response->withJson ( $return_data );

	}

	// camera chi tiet
	public function cameraPlay(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => [ ]
		);

		$user = $this->user_token;

		$psI18n = new PsI18n ( $this->getUserLanguage ( $user ) );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		$student_id = $args ['student_id'];
		$camera_id = $args ['camera_id'];

		if (! PsAuthentication::checkDevice ( $user, $device_id )) {
			return $response->withJson ( $return_data );
		}

		if ($camera_id > 0) {

			$check = false;

			if ($student_id > 0 && $user->user_type == USER_TYPE_RELATIVE) {
				// Check tien trong tai khoan
				$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

				if (! $amount_info) {

					$return_data = array (
							'_msg_code' => MSG_CODE_PAYMENT,
							'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
					);

					return $response->withJson ( $return_data );
				}

				$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

				$check = count ( $ps_student ) > 0 ? true : false;

				$ps_workplace_id = $ps_student->ps_workplace_id;
			} elseif ($user->user_type == USER_TYPE_TEACHER) {

				$check = true;

				// Lay lop hoc cua giao vien
				$ps_member = PsMemberModel::getMember ( $user->member_id );

				$ps_workplace_id = $ps_member->ps_workplace_id;
			}

			if ($check) {

				$camera = $this->db->table ( TBL_CAMERA . ' as C' )->join ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'C.ps_class_room_id' )->join ( TBL_PS_WORK_PLACES . ' as W', 'W.id', '=', 'CR.ps_workplace_id' )->select ( 'C.id', 'C.title', 'C.user_camera', 'C.password_camera', 'C.url_ip', 'C.image_name', 'CR.title as class_room_title', 'CR.is_global' )->where ( 'W.id', '=', $ps_workplace_id )->where ( 'C.is_activated', '=', STATUS_ACTIVE )->where ( 'C.id', '=', $camera_id )->get ()->first ();

				$return_data ['_data'] ['camera_info'] = [ ];

				if ($camera) { // Chi tiet camera

					$camera_info = new \stdClass ();
					$camera_info->title = $camera->title;
					$camera_info->user = $camera->user_camera;
					$camera_info->password = $camera->password_camera;
					$camera_info->url_play = $camera->url_ip;
					$return_data ['_data'] ['camera_info'] = $camera_info;

					$return_data ['_msg_code'] = MSG_CODE_TRUE;

					// Danh sach camera cung loai
					$return_data ['_data'] ['data_info'] = [ ];
					$data_info = array ();

					$cameras = array ();

					if ($camera->is_global == CAMERA_GLOBAL) { // Ngoai canh
						$cameras = $this->db->table ( TBL_CAMERA . ' as C' )->join ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'C.ps_class_room_id' )->join ( TBL_PS_WORK_PLACES . ' as W', 'W.id', '=', 'CR.ps_workplace_id' )->join ( CONST_TBL_PS_CUSTOMER . ' as Cus', 'Cus.id', '=', 'W.ps_customer_id' )->select ( 'C.id', 'C.title', 'C.image_name', 'C.year_data', 'Cus.school_code' )->where ( 'W.id', '=', $ps_workplace_id )->where ( 'C.is_activated', '=', STATUS_ACTIVE )->where ( 'CR.is_global', '=', CAMERA_GLOBAL )->where ( 'C.id', '!=', $camera->id )->get ();
					} else {
						// Trong lop
						$cameras = $this->db->table ( TBL_CAMERA . ' as C' )->join ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'C.ps_class_room_id' )->join ( CONST_TBL_MYCLASS . ' as M', 'M.ps_class_room_id', '=', 'CR.id' )->join ( TBL_PS_WORK_PLACES . ' as W', 'W.id', '=', 'CR.ps_workplace_id' )->join ( CONST_TBL_PS_CUSTOMER . ' as Cus', 'Cus.id', '=', 'W.ps_customer_id' )->select ( 'C.id', 'C.title', 'C.image_name', 'C.year_data', 'Cus.school_code' )->where ( 'W.id', '=', $ps_workplace_id )->where ( 'C.is_activated', '=', STATUS_ACTIVE )->where ( 'CR.is_global', '=', CAMERA_NOT_GLOBAL )->get ();
					}

					foreach ( $cameras as $cam ) {
						$data = array ();
						$data ['id'] = $cam->id;
						$data ['title'] = $cam->title;
						$data ['image_url'] = ($cam->image_name != '') ? PsString::getUrlMediaAvatar ( $cam->school_code, $cam->year_data, $cam->image_name, MEDIA_TYPE_CAMERA ) : PS_CONST_API_URL_IMAGE_DEFAULT_NOTLOGO;
						array_push ( $data_info, $data );
					}

					$return_data ['_data'] ['data_info'] = $data_info;
				}
			}
		}

		return $response->withJson ( $return_data );

	}

	// Lich hoc tuan hien tai cua be
	public function scheduleStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => [ ]
		);

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		$student_id = ( int ) $args ['student_id'];

		try {
			if ($student_id > 0) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {
					return $response->withJson ( $return_data );
				}

				if ($user->user_type == USER_TYPE_RELATIVE) {

					// Kiem tra tai khoan
					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {
						return $response->withJson ( $return_data );
					}

					$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

					if ($ps_student) {
						// lay thong tin hoc sinh ( get student_info )
						$user_info = new \stdClass ();
						$user_info->student_id = $ps_student->id;
						$user_info->first_name = $ps_student->first_name;
						$user_info->last_name = $ps_student->last_name;
						$user_info->class_id = $ps_student->class_id;
						$user_info->class_name = $ps_student->class_name;

						$user_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR_STUDENT;

						$return_data ['_data'] ['user_info'] = $user_info;

						// lay thong tin tuan ( get date_info )
						$date_at = date ( 'Y-m-d' );
						$date_from = date ( 'Y-m-01' );
						$date_to = date ( 'Y-m-t' );

						$date_info = new \stdClass ();

						$date_info->title = date ( 'm/Y' );

						$date_info->month_next = date ( 'Ym', strtotime ( $date_from . " + 1 month" ) );
						$date_info->month_pre = date ( 'Ym', strtotime ( $date_from . " - 1 month" ) );

						$return_data ['_data'] ['block_info'] = $date_info;

						$data_haha = array ();
						for($week = 0; $week <= 5; $week ++) {
							$date_from_at = date ( 'Y-m-d', strtotime ( date ( 'Y-m-01' ) . " + " . $week . " week" ) );
							$date_from = date ( "Y-m-d", strtotime ( 'monday this week', strtotime ( $date_from_at ) ) );

							$date_to = date ( 'Y-m-d', strtotime ( $date_from . " + 6 days" ) );

							$date1 = strtotime ( $date_from );
							$date2 = strtotime ( date ( 'Y-m-01' ) . " + 1 month" );

							// neu ngay dau tien cua tuan lon hon hoac bang ngay dau tien cua thang sau thi dung
							if ($date1 >= $date2)
								break;

							$services = $this->db->table ( TBL_PS_SERVICE_COURSES . ' as SC' )->join ( CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'SC.ps_service_id' )->join ( CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.ps_service_course_id', '=', 'SC.id' )->join ( TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', function ($q) {
								$q->on ( 'SCS.ps_service_course_id', '=', 'SC.id' )->whereRaw ( 'DATE_FORMAT(SC.start_at, "%Y%m%d") <= DATE_FORMAT(SCS.date_at, "%Y%m%d")' )->whereRaw ( 'DATE_FORMAT(SC.end_at, "%Y%m%d") >= DATE_FORMAT(SCS.date_at, "%Y%m%d")' );
							} )->select ( 'SCS.date_at as date_at', 'S.id as service_id' )->where ( 'SS.student_id', $student_id )->whereNull ( 'SS.delete_at' )->whereDate ( 'SCS.date_at', '>=', $date_from )->whereDate ( 'SCS.date_at', '<=', $date_to )->where ( 'S.ps_customer_id', $ps_student->ps_customer_id )->where ( 'SC.is_activated', STATUS_ACTIVE )->where ( 'S.enable_schedule', STATUS_ACTIVE )->where ( 'SCS.is_activated', STATUS_ACTIVE )->where ( 'S.is_activated', STATUS_ACTIVE )->where ( 'S.ps_customer_id', $ps_student->ps_customer_id )->orderBy ( 'SCS.date_at' )->distinct ()->get ();

							$week_info = null;
							$data_subject = $data = array ();
							$data ['block_week'] = array ();
							$data ['week_info'] = array ();
							$subject_info = array ();

							$week_info->week_data = $psI18n->__ ( 'Your baby does not have a schedule' );
							$week_info->week_title = $week + 1;
							$week_info->date_from_to = date ( 'd/m/Y', strtotime ( $date_from ) ) . '-' . date ( 'd/m/Y', strtotime ( $date_to ) );
							// Tuan hien tai
							$week_info->week_current = (date ( "W", strtotime ( date ( 'Y-m-d' ) ) ) == date ( "W", strtotime ( $date_from ) )) ? 1 : 0;

							foreach ( $services as $service ) {
								$data_info = array ();
								$data_info ['day_of_week'] = PsDateTime::toFullDayInWeek ( $service->date_at, $code_lang );

								// Lay mon hoc co ngay trung voi ngay vua tim dc ( get subject_info )
								$_services = $this->db->table ( TBL_PS_SERVICE_COURSES . ' as SC' )->join ( CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'SC.ps_service_id' )->join ( CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.ps_service_course_id', '=', 'SC.id' )->join ( TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', 'SCS.ps_service_course_id', '=', 'SC.id' )->leftJoin ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'SCS.ps_class_room_id' )->leftJoin ( CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'SC.ps_member_id' )->select ( 'S.title as subject_title', 'SCS.start_time_at as start_at', 'SCS.end_time_at as end_at', 'CR.title as class_room_title', 'M.id as teacher_id' )->selectRaw ( 'CONCAT(M.first_name," ", M.last_name) AS teacher_name' )->whereDate ( 'SCS.date_at', '=', $service->date_at )->where ( 'S.id', '=', $service->service_id )->orderBy ( 'SCS.start_time_at' )->get ();

								if ($_services) {
									$week_info->week_data = '';
								}

								foreach ( $_services as $_service ) {
									$data_subject ['subject_title'] = $_service->subject_title;
									$data_subject ['time_at'] = PsDateTime::getTime ( $_service->start_at ) . '-' . PsDateTime::getTime ( $_service->end_at );
									$data_subject ['class_room'] = $_service->class_room_title;
									$data_subject ['teacher_id'] = $_service->teacher_id;
									$data_subject ['teacher_name'] = $_service->teacher_name;
									array_push ( $subject_info, $data_subject );
									$data_info ['subject_info'] = $subject_info;
								}

								array_push ( $data ['block_week'], $data_info );

								$subject_info = [ ];
							}

							/*
							 * $_week_info['week_info'] = $week_info;
							 * array_push($data['block_week'], $_week_info);
							 */

							$data ['week_info'] = $week_info;

							array_push ( $data_haha, $data );
						}

						$return_data ['_data'] ['data_info'] = $data_haha;
						$return_data ['_msg_code'] = MSG_CODE_TRUE;
					}
				}
			}
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	// Lay lich hoc theo thang
	public function scheduleWeekStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => [ ]
		);

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		$student_id = ( int ) $args ['student_id'];

		$month = ( int ) $args ['month'];

		try {

			if (PsDateTime::validateDate ( $month, 'Ym' )) {

				if ($student_id > 0) {

					if ($user->user_type == USER_TYPE_RELATIVE) {

						// Kiem tra tai khoan
						$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );
						if (! $amount_info) {
							return $response->withJson ( $return_data );
						}

						$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

						if ($ps_student) {

							// Lay thong tin hoc sinh ( get student_info )
							$user_info = new \stdClass ();
							$user_info->student_id = $ps_student->id;
							$user_info->first_name = $ps_student->first_name;
							$user_info->last_name = $ps_student->last_name;
							$user_info->class_id = $ps_student->class_id;
							$user_info->class_name = $ps_student->class_name;
							$user_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR_STUDENT;
							$return_data ['_data'] ['user_info'] = $user_info;

							$month = $month . '01';

							$datetime1 = date_create ( date ( 'Y-m-d', strtotime ( $month ) ) );

							$datetime2 = date_create ( date ( 'Y-m-d' ) );

							$interval = $datetime2->diff ( $datetime1 );

							$diff = $interval->format ( '%y' ) * 12 + $interval->format ( '%m' );

							/*
							 * if ($diff < PS_CONST_LIMIT_MONTH_SHEDULE) {
							 * $year_month_title = date('Ym', strtotime($month));
							 * } else {
							 * $year_month_title = date('Ym');
							 * $month = date('Ymd');
							 * }
							 */

							$year_month_title = date ( 'Ym', strtotime ( $month ) );

							// BEGIN TEST **********************************************************************************************
							/*
							 * $start_at = date('Y-m-d', strtotime(date($year_month_title . '01') . " + 0 week"));
							 *
							 * $start_at = date("Ymd", strtotime('monday this week', strtotime($start_at)));
							 *
							 * $end_at = date('Ymd', strtotime(date($year_month_title . '01') . " + 5 week"));
							 *
							 *
							 * $return_data['_data']['start_at - end_at'] = $start_at.' : '.$end_at;
							 *
							 * $service_course_schedules = $this->db->table(TBL_PS_SERVICE_COURSES . ' as SC')
							 * ->join(CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'SC.ps_service_id')
							 * ->join(TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', function ($q) {
							 * $q->on('SCS.ps_service_course_id', '=', 'SC.id')
							 * ->whereRaw('DATE_FORMAT(SC.start_at, "%Y%m%d") <= DATE_FORMAT(SCS.date_at, "%Y%m%d")')
							 * ->whereRaw('DATE_FORMAT(SC.end_at, "%Y%m%d") >= DATE_FORMAT(SCS.date_at, "%Y%m%d")');
							 * })
							 * ->join(CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.ps_service_course_id', '=', 'SC.id')
							 * ->leftJoin(TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'SCS.ps_class_room_id')
							 * ->leftJoin(CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'SC.ps_member_id')
							 * ->select('SCS.date_at as date_at', 'S.title','SCS.start_time_at','SCS.end_time_at','CR.title as class_room_title')
							 * ->selectRaw('CONCAT(M.first_name," ", M.last_name) AS teacher_name')
							 * ->where('SS.student_id', $student_id)
							 * ->whereNull('SS.delete_at')
							 * ->whereDate('SCS.date_at', '>=', $start_at)
							 * ->whereDate('SCS.date_at', '<=', $end_at)
							 * ->where('S.ps_customer_id', $ps_student->ps_customer_id)
							 * ->where('SC.is_activated', STATUS_ACTIVE)
							 * ->where('S.enable_roll', ENABLE_ROLL_SCHEDULE)
							 * ->where('SCS.is_activated', STATUS_ACTIVE)
							 * ->where('S.is_activated', STATUS_ACTIVE)
							 * ->orderBy('SCS.date_at')
							 * ->distinct()
							 * ->get();
							 *
							 * $return_data['_data']['list_service_courses'] = $service_course_schedules;
							 */

							// END TEST*****************************************

							// Lay thong tin tuan ( get date_info )

							$date_at = date ( 'Y-m-d' );
							$date_from = date ( $year_month_title . '01' );

							$date_to = date ( "Y-m-t", strtotime ( $month ) );

							$date_info = new \stdClass ();

							$date_info->title = date ( 'm/Y', strtotime ( $month ) );
							$date_info->month_next = date ( 'Ym', strtotime ( $date_from . " + 1 month" ) );
							$date_info->month_pre = date ( 'Ym', strtotime ( $date_from . " - 1 month" ) );

							$return_data ['_data'] ['block_info'] = $date_info;

							$data_haha = array ();

							// BEGIN NEW ********************
							// Lay so tuan cua thang
							/*
							 * $number_week = PsDateTime::get_weeks(date('m', strtotime($month)), date('Y', strtotime($month)));
							 *
							 * $return_data['_data']['number_week'] = $number_week;
							 *
							 * // So thu tu cua tuan trong 1 nam voi ngay bat dau
							 * $start_WeekOfYear = PsDateTime::getIndexWeekOfYear($start_at);
							 *
							 * $end_WeekOfYear = PsDateTime::getIndexWeekOfYear($end_at);
							 *
							 * $return_data['_data']['start_WeekOfYear - end_WeekOfYear'] = $start_WeekOfYear.' : '.$end_WeekOfYear;
							 *
							 * $year = date('Y', strtotime($month));
							 *
							 * $number_week = $end_WeekOfYear - $start_WeekOfYear;
							 *
							 * $index_week_title = 0;
							 *
							 * for ($i= $start_WeekOfYear; $i <= $end_WeekOfYear; $i++) {
							 *
							 * //$info_week = PsDateTime::getStartAndEndDateOfWeek($i, $year, 'Y-m-d');
							 *
							 *
							 * $week_info = null;
							 * $data_subject = array();
							 *
							 * $data['block_week'] = array();
							 * $data['week_info'] = array();
							 *
							 * $subject_info = array();
							 *
							 * $week_info->week_data = $psI18n->__('Your baby does not have a schedule');
							 * $week_info->week_title = $index_week_title + 1;
							 * $index_week_title++;
							 *
							 * $week_info->date_from_to = $info_week['week_start'] . '-' . $info_week['week_end'];
							 *
							 * $week_info->week_current = (date("W", strtotime(date('Y-m-d'))) == date("W", strtotime($info_week['week_start']))) ? 1 : 0;
							 *
							 * foreach ($info_week['week_list'] as $date) {
							 *
							 * foreach ($service_course_schedules as $course_schedule) {
							 *
							 * if ($course_schedule->date_at == $date) {
							 *
							 * $data_info['day_of_week'] = PsDateTime::toFullDayInWeek($course_schedule->date_at, $code_lang);
							 *
							 * $week_info->week_data = '';
							 *
							 * $data_subject['subject_title'] = $course_schedule->title;
							 * $data_subject['time_at'] = PsDateTime::getTime($course_schedule->start_time_at) . '-' . PsDateTime::getTime($course_schedule->end_time_at);
							 * $data_subject['class_room'] = $course_schedule->class_room_title;
							 * //$data_subject['teacher_id'] = $_service->teacher_id;
							 * //$data_subject['teacher_name'] = $_service->teacher_name;
							 * array_push($subject_info, $data_subject);
							 * $data_info['subject_info'] = $subject_info;
							 * }
							 *
							 * array_push($data['block_week'], $data_info);
							 * $subject_info = [];
							 * }
							 * }
							 *
							 * $data['week_info'] = $week_info;
							 * array_push($data_haha, $data);
							 *
							 * }
							 */
							// END NEW **********************
							for($week = 0; $week <= 5; $week ++) {

								$date_from_at = date ( 'Y-m-d', strtotime ( date ( $year_month_title . '01' ) . " + " . $week . " week" ) );

								$date_from = date ( "Y-m-d", strtotime ( 'monday this week', strtotime ( $date_from_at ) ) );

								$date_to = date ( 'Y-m-d', strtotime ( $date_from . " + 6 days" ) );

								$date1 = strtotime ( $date_from );
								$date2 = strtotime ( date ( $year_month_title . '01' ) . " + 1 month" );
								// neu ngay dau tien cua tuan lon hon hoac bang ngay dau tien cua thang sau thi dung
								if ($date1 >= $date2)
									break;

								$services = $this->db->table ( TBL_PS_SERVICE_COURSES . ' as SC' )->join ( CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'SC.ps_service_id' )->join ( TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', function ($q) {
									$q->on ( 'SCS.ps_service_course_id', '=', 'SC.id' )->whereRaw ( 'DATE_FORMAT(SC.start_at, "%Y%m%d") <= DATE_FORMAT(SCS.date_at, "%Y%m%d")' )->whereRaw ( 'DATE_FORMAT(SC.end_at, "%Y%m%d") >= DATE_FORMAT(SCS.date_at, "%Y%m%d")' );
								} )->join ( CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.ps_service_course_id', '=', 'SC.id' )->select ( 'SCS.date_at as date_at', 'S.id as service_id' )->where ( 'SS.student_id', $student_id )->whereNull ( 'SS.delete_at' )->whereDate ( 'SCS.date_at', '>=', $date_from )->whereDate ( 'SCS.date_at', '<=', $date_to )->where ( 'S.ps_customer_id', $ps_student->ps_customer_id )->where ( 'SC.is_activated', STATUS_ACTIVE )->where ( 'S.enable_schedule', STATUS_ACTIVE )->where ( 'SCS.is_activated', STATUS_ACTIVE )->where ( 'S.is_activated', STATUS_ACTIVE )->orderBy ( 'SCS.date_at' )->distinct ()->get ();

								$week_info = null;
								$data_subject = $data = array ();

								$data ['block_week'] = array ();
								$data ['week_info'] = array ();

								$subject_info = array ();

								$week_info->week_data = $psI18n->__ ( 'Your baby does not have a schedule' );
								$week_info->week_title = $week + 1;
								$week_info->date_from_to = date ( 'd/m/Y', strtotime ( $date_from ) ) . '-' . date ( 'd/m/Y', strtotime ( $date_to ) );
								// $week_info->week_current = PsDateTime::weekOfMonth(date('Y-m-d'));
								$week_info->week_current = (date ( "W", strtotime ( date ( 'Y-m-d' ) ) ) == date ( "W", strtotime ( $date_from ) )) ? 1 : 0;

								foreach ( $services as $service ) {

									$data_info = array ();

									$data_info ['day_of_week'] = PsDateTime::toFullDayInWeek ( $service->date_at, $code_lang );

									// lay mon hoc co ngay trung voi ngay vua tim dc ( get subject_info )
									$_services = $this->db->table ( TBL_PS_SERVICE_COURSES . ' as SC' )->join ( CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'SC.ps_service_id' )->join ( CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.ps_service_course_id', '=', 'SC.id' )->join ( TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', 'SCS.ps_service_course_id', '=', 'SC.id' )->leftJoin ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'SCS.ps_class_room_id' )->leftJoin ( CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'SC.ps_member_id' )->select ( 'S.title as subject_title', 'SCS.start_time_at as start_at', 'SCS.end_time_at as end_at', 'CR.title as class_room_title', 'M.id as teacher_id' )->selectRaw ( 'CONCAT(M.first_name," ", M.last_name) AS teacher_name' )->whereDate ( 'SCS.date_at', '=', $service->date_at )->where ( 'S.id', '=', $service->service_id )->orderBy ( 'SCS.start_time_at' )->distinct ()->get ();
									if ($_services) {
										$week_info->week_data = '';
									}
									foreach ( $_services as $_service ) {

										$data_subject = array ();

										$data_subject ['subject_title'] = $_service->subject_title;
										$data_subject ['time_at'] = PsDateTime::getTime ( $_service->start_at ) . '-' . PsDateTime::getTime ( $_service->end_at );
										$data_subject ['class_room'] = $_service->class_room_title;
										$data_subject ['teacher_id'] = $_service->teacher_id;
										$data_subject ['teacher_name'] = $_service->teacher_name;

										array_push ( $subject_info, $data_subject );

										$data_info ['subject_info'] = $subject_info;
									}

									array_push ( $data ['block_week'], $data_info );

									$subject_info = [ ];
								}

								$data ['week_info'] = $week_info;

								array_push ( $data_haha, $data );
							}

							$return_data ['_data'] ['data_info'] = $data_haha;
							$return_data ['_msg_code'] = MSG_CODE_TRUE;
						}
					}
				}
			}
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	// Nhận xét của bé
	public function commentOfStudent(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => [ ]
		);

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		$user_app_config = json_decode ( $user->app_config );

		$app_config_color = (isset ( $user_app_config->style ) && $user_app_config->style != '') ? $user_app_config->style : 'green';

		if ($app_config_color == 'yellow_orange')
			$app_config_color = 'orange';

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		$student_id = $args ['student_id'];

		try {

			if ($student_id > 0) {

				if (! PsAuthentication::checkDevice ( $user, $device_id )) {
					return $response->withJson ( $return_data );
				}

				if ($user->user_type == USER_TYPE_RELATIVE) {

					// Kiem tra tai khoan
					$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

					if (! $amount_info) {

						$return_data = array (
								'_msg_code' => MSG_CODE_PAYMENT,
								'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
						);

						return $response->withJson ( $return_data );
					}

					$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

					if ($ps_student) {

						// Lay thong tin hoc sinh
						$user_info = new \stdClass ();
						$user_info->student_id  = $ps_student->id;
						$user_info->birthday 	= ( string ) PsDateTime::toDMY ( $ps_student->birthday );
						$user_info->first_name  = ( string ) $ps_student->first_name;
						$user_info->last_name   = ( string ) $ps_student->last_name;
						$user_info->class_id    = $ps_student->class_id;
						$user_info->class_name  = ($ps_student->class_name != '') ? ( string ) $ps_student->class_name : '';
						
						//$user_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
						
						if ($ps_student->avatar != '') {						    
						    $avatar_url = PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT );						    
						} else {
						    if ($ps_student->sex == STATUS_ACTIVE) {
						        $avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE.'boy_avatar_default.png';
						    } else {
						        $avatar_url = PS_CONST_API_URL_PATH_CACHE_IMAGE.'girl_avatar_default.png';
						    }
						}
						
						$user_info->avatar_url = $avatar_url;						

						$queryParams = $request->getQueryParams ();
						
						$date_at = isset ( $queryParams ['date'] ) ? $queryParams ['date'] : date ( 'Y-m-d' );

						// Lay danh sach hoat dong
						$features = $this->db->table ( TBL_FEATURE_BRANCH . ' as FB' )
						->leftjoin ( TBL_FEATURE_BRANCH_TIMES . ' as FBT', 'FBT.ps_feature_branch_id', '=', 'FB.id' )
						->leftjoin ( TBL_FEATURE_BRANCH_TIME_MY_CLASS . ' as FBTC', 'FBTC.ps_feature_branch_time_id', '=', 'FBT.id' )
						->join ( TBL_FEATURE . ' as F', 'F.id', '=', 'FB.feature_id' )
						->leftJoin ( CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', 'FB.ps_image_id' )
						->leftJoin ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'FBT.ps_class_room_id' )
						->selectRaw ( 'FB.id as id, FB.name as feature_title, I.file_name, FBT.start_time as start_at, FBT.end_time as end_at, CR.title as class_room_title, FBTC.ps_class_room AS ps_class_room,FBTC.note as note, FBT.note AS fbt_note' )
						->where ( 'F.ps_customer_id', $ps_student->ps_customer_id )
						->whereDate ( 'FBT.start_at', '<=', $date_at )
						->whereDate ( 'FBT.end_at', '>=', $date_at )
						->where ( 'F.is_activated', STATUS_ACTIVE )
						->where ( 'FB.is_activated', STATUS_ACTIVE )
						->where ( function ($query) use ($ps_student) {
							$query->where ( 'FBTC.ps_myclass_id', '=', $ps_student->class_id );
							// $query->orWhereRaw ( 'F.ps_obj_group_id', $ps_student->ps_obj_group_id )->orwhereNull ( 'F.ps_obj_group_id' );
						} );

						// $_features = $features->orderBy('start_at')->distinct('id')->get(); // thong tin hoat dong

						// Lay thong tin dich vu theo thoi khoa bieu
						$services = $this->db->table ( TBL_PS_SERVICE_COURSES . ' as SC' )
						->join ( CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'SC.ps_service_id' )
						->join ( CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.ps_service_course_id', '=', 'SC.id' )
						->join ( TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', 'SCS.ps_service_course_id', '=', 'SC.id' )
						->leftJoin ( CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', 'S.ps_image_id' )
						->leftJoin ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'SCS.ps_class_room_id' )
						->selectRaw ( 'Null as id, S.title as feature_title, I.file_name, SCS.start_time_at as start_at, SCS.end_time_at as end_at, CR.title as class_room_title, null AS ps_class_room ,SCS.note as note, null AS fbt_note' )
						->where ( 'SS.student_id', $student_id )
						->whereNull ( 'SS.delete_at' )
						->whereDate ( 'SCS.date_at', $date_at )
						->whereDate ( 'SC.start_at', '<=', $date_at )
						->whereDate ( 'SC.end_at', '>=', $date_at )
						->where ( 'S.ps_customer_id', $ps_student->ps_customer_id )
						->where ( 'SC.is_activated', STATUS_ACTIVE )
						->where ( 'S.enable_roll', ENABLE_ROLL_SCHEDULE )
						->where ( 'SCS.is_activated', STATUS_ACTIVE )
						->where ( 'S.is_activated', STATUS_ACTIVE );

						// $_services = $services->orderBy('start_at')->get(); // thong tin dich vu

						// Ghep dich vu va hoat dong
						$feature_services = $services->unionall ( $features )->orderBy ( 'start_at' )->distinct ()->get ();

						$data = $_option = array ();

						foreach ( $feature_services as $feature_service ) {

							$data_feature = array ();

							$data_feature ['feature_title'] = $feature_service->feature_title;
							$data_feature ['icon'] = PsString::getUrlPsImage ( $feature_service->file_name );
							$data_feature ['time_at'] = PsDateTime::getTime ( $feature_service->start_at ) . '-' . PsDateTime::getTime ( $feature_service->end_at );
							$data_feature ['class_room'] = ($feature_service->class_room_title != '') ? $feature_service->class_room_title : '';
							$data_feature ['ps_class_room'] = ($feature_service->ps_class_room != '') ? $feature_service->ps_class_room : '';

							$data_feature ['note'] = $feature_service->note;
							$data_feature ['fbt_note'] = $feature_service->fbt_note;

							if ($feature_service->id !== null) {

								// Lay danh gia cua hoat dong
								$rate_features = FeatureOptionModel::getRateFeature ( $student_id, $feature_service->id, $date_at, $ps_student->ps_customer_id );

								foreach ( $rate_features as $rate_feature ) {

									$option = ($rate_feature->type == 1) ? $rate_feature->name : $rate_feature->note;

									array_push ( $_option, $option );
								}

								$data_feature ['rate'] = implode ( '<br>', $_option );

								$_option = array ();
							} else { // lay danh gia cua dich vu

								$data_feature ['rate'] = StudentServiceCourseCommentModel::getRateService ( $student_id, $feature_service->note )->note;
							}

							array_push ( $data, $data_feature );
						}

						$data = array_values ( $data );

						// Noi dung tra ve kieu wb content
						$web_content = PsWebContent::BeginHTMLPage ();

						$web_content .= '<div class="w3-container" style="padding-left:0px;padding-right:0px;">';

						$_check_rate = false;

						foreach ( $data as $value ) {

							if ($value ['rate'] != '') {

								$_check_rate = true;

								$web_content .= '<div class="w3-row w3-padding-16 w3-border-bottom">';

								// icon
								$web_content .= '<div class="w3-col s1">';
								if ($value ['icon'] != '') {
									$web_content .= '<img src="' . $value ['icon'] . '" style="width:100%"/>';
								}

								$web_content .= '</div>';

								// Title + content
								$web_content .= '<div class="w3-col s11 w3-container">';

								$web_content .= '<div style="font-style:italic;padding-top:0px;"><small>' . $value ['time_at'] . '</small></div>';

								if ($value ['ps_class_room'] != '') {

									$web_content .= '<div style="font-style:italic;"><small>' . $psI18n->__ ( 'Place' ) . ': ' . PsString::htmlSpecialChars ( $value ['ps_class_room'] ) . '</small></div>';
								} elseif ($value ['class_room'] != '') {
									$web_content .= '<div style="font-style:italic;"><small>' . $psI18n->__ ( 'Place' ) . ': ' . PsString::htmlSpecialChars ( $value ['class_room'] ) . '</small></div>';
								}

								$web_content .= '<div class="w3-text-' . $app_config_color . '" style="padding-top:7px;">' . PsString::htmlSpecialChars ( $value ['feature_title'] ) . '</div>';

								if ($value ['note'] != '') {

									$web_content .= '<div style="font-style:italic;padding-top:7px;font-weight:bolder;">' . $psI18n->__ ( 'Content' ) . '</div>';

									$web_content .= '<div style="padding-top:5px;" class="w3-justify">' . PsString::nl2brChars ( $value ['note'] ) . '</div>';
								} elseif ($value ['fbt_note'] != '') {

									$web_content .= '<div style="font-style:italic;padding-top:7px;font-weight:bolder;">' . $psI18n->__ ( 'Content' ) . '</div>';

									$web_content .= '<div style="padding-top:5px;" class="w3-justify">' . PsString::nl2brChars ( $value ['fbt_note'] ) . '</div>';
								}

								$web_content .= '<div style="font-style:italic;padding-top:7px;font-weight:bolder;">' . $psI18n->__ ( 'Comment of Teacher' ) . '</div>';

								$web_content .= '<div style="padding-top:5px;" class="w3-justify">' . PsString::nl2brChars ( $value ['rate'] ) . '</div>';

								$web_content .= '</div>';

								$web_content .= '</div>';
							}
						}

						if (! $_check_rate) {
							$web_content .= '<div class="w3-padding-16 w3-text-red w3-center">' . $psI18n->__ ( 'Baby' ) . ' <strong>' . $ps_student->last_name . '</strong> ' . $psI18n->__ ( 'today has no comment.' ) . '</div>';
						}
						
						if ($user->ps_customer_id == 6) {
    						// -- BEGIN: Nhan xet thang
    						$ps_comment_months = $this->db->table ( TBL_PS_COMMENT_WEEK . ' as cw' )
    						->select ( 'cw.title', 'cw.ps_month','cw.ps_year','cw.ps_customer_id','cw.created_at' )
    						->where ( 'cw.is_activated', '=', STATUS_ACTIVE )
    						->where ( 'cw.student_id', '=', $ps_student->id )
    						->whereNull ( 'cw.ps_week')
    						->orderBy ( 'cw.ps_year', 'DESC' )
    						->orderBy ( 'cw.ps_month', 'DESC' )
    						->get ()->first ();
    						
    						if ($ps_comment_months) {
    						    
    						    $web_content .= '<div class="w3-row ks-padding-7"><h5 class="w3-text-' . $app_config_color . '">' . $psI18n->__ ( 'Comment month' ) . '</h5></div>';
    						    
    						    $web_content .= '<div class="w3-row">';    						    
    						    // icon
    						    //$icon_url = 'https://quanly.kidsschool.vn/icon/icon_nhanxet.png';
    						    $web_content .= '<div class="w3-col s2">';
    						    //$web_content .= '<img src="' . $icon_url . '" style="width:100%"/>';
    						    $web_content .= $ps_comment_months->ps_month.'.'.$ps_comment_months->ps_year;
    						    $web_content .= '</div>';
    						    
    						    // Title + content
    						    $web_content .= '<div class="w3-col s10 w3-container">';
    						    $web_content .= '<div class="w3-text-' . $app_config_color . '"><a target="_parent" href="https://kidsschool.vn/ho-tro-khach-hang-su-dung-kidsschool/huong-dan-cong-khai-album-anh-tren-app-kidsschool-teacher">' . $ps_comment_months->title . '</a></div>';    						    
    						    $web_content .= '</div>';
    						    
    						    $web_content .= '</div><hr>';
    						}
    						
    						// -- END: Nhan xet thang
    						
    						// Chi so danh gia
    						$web_content .= '<div class="w3-row ks-padding-7"><h5 class="w3-text-' . $app_config_color . '">' . $psI18n->__ ( 'Chỉ số đánh giá trẻ' ) . '</h5></div>';
						}
						
						

						// -- BEGIN: Ghép nhận xét cuối kỳ
						$ps_evaluate_semester = $this->db->table ( TBL_PS_EVALUATE_SEMESTER . ' as es' )
						->select ( 'es.*' )->where ( 'es.is_public', '=', STATUS_ACTIVE )
						->where ( 'es.student_id', '=', $ps_student->id )
						->orderBy ( 'es.id', 'DESC' )
						->get ()->first ();

						if ($ps_evaluate_semester) {

							$web_content .= '<div class="w3-row w3-padding-16"><h5 class="w3-text-' . $app_config_color . '">' . $psI18n->__ ( 'Votes comments' ) . '</h5></div>';

							$web_content .= '<div class="w3-row">';

							// icon Phiếu nhận xét cuối kỳ
							$icon_url = 'https://quanly.kidsschool.vn/icon/icon_nhanxet.png';
							$web_content .= '<div class="w3-col s1">';
							$web_content .= '<img src="' . $icon_url . '" style="width:100%"/>';
							$web_content .= '</div>';

							// Title + content
							$web_content .= '<div class="w3-col s11 w3-container">';
							$web_content .= '<div class="w3-text-' . $app_config_color . '">' . $ps_evaluate_semester->title . '</div>';
							$web_content .= '<div><a href="' . $ps_evaluate_semester->url_file . '">' . $psI18n->__ ( 'See' ) . '</a></div>';

							$web_content .= '</div>';

							$web_content .= '</div><hr>';
						}

						// -- END: Ghép nhận xét cuối kỳ

						$web_content .= '</div>';

						$web_content .= PsWebContent::EndHTMLPage ();

						// $return_data['_data']['title'] = $psI18n->__('Comment').' '.$psI18n->__('Day').' '.PsDateTime::toDMY($date_at);
						$return_data ['_data'] ['title'] = $psI18n->__ ( 'Comment of student' );
						$return_data ['_data'] ['user_info'] = $user_info;
						$return_data ['_data'] ['content'] = $web_content;

						$return_data ['_msg_code'] = MSG_CODE_TRUE;
					}
				}
			}
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );

			$return_data ['_msg_code'] = MSG_CODE_500;

			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	// Xem lich hoc theo thang - dành cho Android
	public function studentScheduleForAndroid(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => [ ]
		);

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		$student_id = ( int ) $args ['student_id'];

		$month = ( int ) $args ['month'];
		/*
		 * $this->WriteLog('-- BEGIN: XEM LICH HOC CUA THANG: Android');
		 * $this->WriteLog("USER ID: " . $user->id);
		 * $this->WriteLog("USERNAME: " . $user->username);
		 * $this->WriteLog("STUDENT ID: " . $student_id);
		 * $this->WriteLog('-- END: XEM LICH HOC CUA THANG: Android');
		 */

		// Nếu nhận dạng là bản mới
		if (PsDateTime::validateDate ( $month, 'Ymd' )) {

			$data = $this->studentScheduleOfDay ( $request, $response, $args );

			return $response->withJson ( $data );
		}

		try {
			if (PsDateTime::validateDate ( $month, 'Ym' )) {

				if ($student_id > 0) {

					if (! PsAuthentication::checkDevice ( $user, $device_id )) {
						return $response->withJson ( $return_data );
					}

					if ($user->user_type == USER_TYPE_RELATIVE) {

						// Kiem tra tai khoan
						$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

						if (! $amount_info) {

							$return_data = array (
									'_msg_code' => MSG_CODE_PAYMENT,
									'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
							);

							return $response->withJson ( $return_data );
						}

						$return_data ['_msg_code'] = MSG_CODE_TRUE;

						$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

						if ($ps_student) {

							// Get infomation student
							$user_info = new \stdClass ();

							$user_info->student_id = ( int ) $ps_student->id;
							$user_info->first_name = ( string ) $ps_student->first_name;
							$user_info->last_name = ( string ) $ps_student->last_name;
							$user_info->class_id = ( string ) $ps_student->class_id;
							$user_info->class_name = ( string ) $ps_student->class_name;

							$user_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR_STUDENT;

							$return_data ['_data'] ['user_info'] = $user_info;

							$month = $month . '01';

							/*
							 * $datetime1 = date_create(date('Y-m-d', strtotime($month)));
							 * $datetime2 = date_create(date('Y-m-d'));
							 * $interval = $datetime2->diff($datetime1);
							 * $diff = $interval->format('%y') * 12 + $interval->format('%m');
							 * if ($diff < PS_CONST_LIMIT_MONTH_SHEDULE) {
							 * $year_month_title = date ( 'Ym', strtotime ( $month ) );
							 * } else {
							 * $year_month_title = date ( 'Ym' );
							 * $month = date ( 'Ymd' );
							 * }
							 */

							$year_month_title = date ( 'Ym', strtotime ( $month ) );

							// Lay thong tin tuan ( get date_info )
							$date_at = date ( 'Y-m-d' );
							$date_from = date ( $year_month_title . '01' );
							$date_info = new \stdClass ();
							$date_to = date ( "Y-m-t", strtotime ( $month ) );
							$date_info->title = date ( 'm/Y', strtotime ( $month ) );
							$date_info->month_next = date ( 'Ym', strtotime ( $date_from . " + 1 month" ) );
							$date_info->month_pre = date ( 'Ym', strtotime ( $date_from . " - 1 month" ) );

							$return_data ['_data'] ['block_info'] = $date_info;

							$data_haha = array ();

							// Lay dich vụ học trong tuan
							for($week = 0; $week <= 5; $week ++) {

								$date_from_at = date ( 'Y-m-d', strtotime ( date ( $year_month_title . '01' ) . " + " . $week . " week" ) );

								$date_from = date ( "Y-m-d", strtotime ( 'monday this week', strtotime ( $date_from_at ) ) );

								$date_to = date ( 'Y-m-d', strtotime ( $date_from . " + 6 days" ) );

								$date1 = strtotime ( $date_from );

								$date2 = strtotime ( date ( $year_month_title . '01' ) . " + 1 month" );
								// neu ngay dau tien cua tuan lon hon hoac bang ngay dau tien cua thang sau thi dung
								if ($date1 >= $date2)
									break;

								$services = $this->db->table ( TBL_PS_SERVICE_COURSES . ' as SC' )->join ( CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'SC.ps_service_id' )->join ( TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', function ($q) {
									$q->on ( 'SCS.ps_service_course_id', '=', 'SC.id' )->whereRaw ( 'DATE_FORMAT(SC.start_at, "%Y%m%d") <= DATE_FORMAT(SCS.date_at, "%Y%m%d")' )->whereRaw ( 'DATE_FORMAT(SC.end_at, "%Y%m%d") >= DATE_FORMAT(SCS.date_at, "%Y%m%d")' );
								} )->join ( CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.ps_service_course_id', '=', 'SC.id' )->select ( 'SC.id as id', 'SCS.date_at as date_at', 'S.id as service_id' )->where ( 'SS.student_id', $student_id )->whereNull ( 'SS.delete_at' )->whereDate ( 'SCS.date_at', '>=', $date_from )->whereDate ( 'SCS.date_at', '<=', $date_to )->where ( 'S.ps_customer_id', $ps_student->ps_customer_id )->where ( 'SC.is_activated', STATUS_ACTIVE )->where ( 'S.enable_schedule', ENABLE_ROLL_SCHEDULE )->where ( 'SCS.is_activated', STATUS_ACTIVE )->where ( 'S.is_activated', STATUS_ACTIVE )->orderBy ( 'SCS.date_at' )->distinct ()->get ();

								$week_info = null;
								$data = array ();
								$data ['block_week'] = array ();
								$subject_info = array ();

								$week_info = new \stdClass ();
								$week_info->week_data = $psI18n->__ ( 'Your baby does not have a schedule' );
								$week_info->week_title = $week + 1;
								$week_info->date_from_to = date ( 'd/m/Y', strtotime ( $date_from ) ) . '-' . date ( 'd/m/Y', strtotime ( $date_to ) );
								$week_info->week_current = (date ( "W", strtotime ( date ( 'Y-m-d' ) ) ) == date ( "W", strtotime ( $date_from ) )) ? 1 : 0;

								$_data_info = array ();

								$block_info = array ();

								$subject_info = array ();

								$_week_info = array ();

								$_week_info ['week_info'] = $week_info;

								array_push ( $data ['block_week'], $_week_info );

								foreach ( $services as $key => $service ) {

									$block_info [$service->date_at] = PsDateTime::toFullDayInWeek ( $service->date_at, $code_lang );

									// Lay danh sach lich hoat dong hoc
									$features = $this->db->table ( TBL_FEATURE_BRANCH . ' as FB' )->leftjoin ( TBL_FEATURE_BRANCH_TIMES . ' as FBT', 'FBT.ps_feature_branch_id', '=', 'FB.id' )->leftjoin ( TBL_FEATURE_BRANCH_TIME_MY_CLASS . ' as FBTC', 'FBTC.ps_feature_branch_time_id', '=', 'FBT.id' )->join ( TBL_FEATURE . ' as F', 'F.id', '=', 'FB.feature_id' )->leftJoin ( CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', 'FB.ps_image_id' )->leftJoin ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'FBT.ps_class_room_id' )->selectRaw ( '"Is study" AS subject_title,FB.name AS service_course_title,FBT.start_time as start_at,FBT.end_time as end_at,CR.title as class_room_title' )->selectRaw ( 'Null as teacher_id, Null AS teacher_name, FBTC.ps_class_room AS ps_class_room' )->where ( 'F.ps_customer_id', $ps_student->ps_customer_id )->whereDate ( 'FBT.start_at', '<=', $date_at )->whereDate ( 'FBT.end_at', '>=', $date_at )->where ( 'F.is_activated', STATUS_ACTIVE )->where ( 'FB.is_study', '=', STATUS_ACTIVE )->where ( 'FB.is_activated', STATUS_ACTIVE )->where ( function ($query) use ($ps_student) {
										$query->where ( 'FBTC.ps_myclass_id', '=', $ps_student->class_id );
									} );

									$number_day = PsDateTime::getNumberDayOfDate ( $date_at );

									if ($number_day == '0') {
										$features = $features->where ( 'FBT.is_sunday', '=', STATUS_ACTIVE );
									} elseif ($number_day == 6) {
										$features = $features->where ( 'FBT.is_saturday', '=', STATUS_ACTIVE );
									}

									// Lay mon hoc co ngay trung voi ngay vua tim dc ( get subject_info )
									$_services = $this->db->table ( TBL_PS_SERVICE_COURSES . ' as SC' )->join ( CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'SC.ps_service_id' )->join ( CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.ps_service_course_id', '=', 'SC.id' )->join ( TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', 'SCS.ps_service_course_id', '=', 'SC.id' )->leftJoin ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'SCS.ps_class_room_id' )->leftJoin ( CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'SC.ps_member_id' )->select ( 'S.title as subject_title', 'SC.title AS service_course_title', 'SCS.start_time_at as start_at', 'SCS.end_time_at as end_at', 'CR.title as class_room_title', 'M.id as teacher_id' )->selectRaw ( 'CONCAT(M.first_name," ", M.last_name) AS teacher_name, CR.title As ps_class_room' )->whereDate ( 'SCS.date_at', '=', $service->date_at )->where ( 'S.id', '=', $service->service_id )->where ( 'SC.id', '=', $service->id );

									// ->orderBy ('SCS.start_time_at' )->distinct ()->get ();

									// Ghep dich vu va hoat dong
									$feature_services = $_services->unionall ( $features )->orderBy ( 'start_at' )->distinct ()->get ();

									if ($feature_services) {
										$week_info->week_data = '';
									}

									foreach ( $feature_services as $key => $_service ) {

										$_subject = array ();

										$_subject ['date_at'] = ( string ) $service->date_at;
										$_subject ['subject_title'] = ( string ) $psI18n->__ ( $_service->subject_title ) . '-' . ( string ) $_service->service_course_title;
										$_subject ['time_at'] = PsDateTime::getTime ( $_service->start_at ) . '-' . PsDateTime::getTime ( $_service->end_at );
										$_subject ['class_room'] = ($_service->class_room_title != '') ? $_service->class_room_title : ' ';

										if ($_service->ps_class_room != '') {
											$_subject ['class_room'] = ( string ) $_service->ps_class_room;
										} elseif ($_service->class_room_title != '') {
											$_subject ['class_room'] = ( string ) $_service->class_room_title;
										} else {
											$_subject ['class_room'] = '';
										}

										$_subject ['teacher_id'] = ( int ) $_service->teacher_id;
										$_subject ['teacher_name'] = ( string ) $_service->teacher_name;

										array_push ( $subject_info, $_subject );
									}
								}

								foreach ( $block_info as $key => $_info ) {

									$_data_info ['day_of_week'] = $_info;

									$subject_info_temp = array ();

									foreach ( $subject_info as $subject ) {
										if ($subject ['date_at'] == $key) {
											unset ( $subject ['date_at'] );
											array_push ( $subject_info_temp, $subject );
										}
									}

									$_data_info ['subject_info'] = $subject_info_temp;

									array_push ( $data ['block_week'], $_data_info );
								}

								array_push ( $data_haha, $data );
							}

							$return_data ['_data'] ['data_info'] = $data_haha;

							$content = $this->studentScheduleWebContent ( $request, $response, $args );
							$return_data ['_data'] ['content'] = $content ['_data'] ['content'];
						} else {
							$return_data ['_msg_code'] = MSG_CODE_500;
							$return_data ['message'] = $psI18n->__ ( 'You do not have access to this data' );
						}
					}
				}
			}
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );
			$return_data ['_msg_code'] = MSG_CODE_500;
			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	// Xem lich hoc theo thang - dành cho iOS
	public function studentScheduleForIos(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array (
				'_msg_code' => MSG_CODE_FALSE,
				'_data' => [ ]
		);

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		$student_id = ( int ) $args ['student_id'];

		$month = ( int ) $args ['month'];

		if ($month == '')
			$month = date ( 'Ymd' );

		// Nếu nhận dạng là bản mới
		if (PsDateTime::validateDate ( $month, 'Ymd' )) {

			$data = $this->studentScheduleOfDay ( $request, $response, $args );

			return $response->withJson ( $data );
		}

		try {
			if (PsDateTime::validateDate ( $month, 'Ym' )) {

				if ($student_id > 0) {

					if (! PsAuthentication::checkDevice ( $user, $device_id )) {
						return $response->withJson ( $return_data );
					}

					if ($user->user_type == USER_TYPE_RELATIVE) {

						// Kiem tra tai khoan
						$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

						if (! $amount_info) {

							$return_data = array (
									'_msg_code' => MSG_CODE_PAYMENT,
									'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
							);

							return $response->withJson ( $return_data );
						}

						$return_data ['_msg_code'] = MSG_CODE_TRUE;

						$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

						if ($ps_student) {

							// Get infomation student
							$user_info = new \stdClass ();

							$user_info->student_id = ( int ) $ps_student->id;
							$user_info->first_name = ( string ) $ps_student->first_name;
							$user_info->last_name = ( string ) $ps_student->last_name;
							$user_info->class_id = ( string ) $ps_student->class_id;
							$user_info->class_name = ( string ) $ps_student->class_name;

							$user_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR_STUDENT;

							$return_data ['_data'] ['user_info'] = $user_info;
							$web_content = 'Lịch học của bé';
							$return_data ['_data'] ['content'] = $web_content;

							$month = $month . '01';

							/*
							 * $datetime1 = date_create(date('Y-m-d', strtotime($month)));
							 * $datetime2 = date_create(date('Y-m-d'));
							 * $interval = $datetime2->diff($datetime1);
							 * $diff = $interval->format('%y') * 12 + $interval->format('%m');
							 */

							$year_month_title = date ( 'Ym', strtotime ( $month ) );

							// Lay thong tin tuan ( get date_info )
							$date_at = date ( 'Y-m-d' );
							$date_from = date ( $year_month_title . '01' );
							$date_info = new \stdClass ();
							$date_to = date ( "Y-m-t", strtotime ( $month ) );
							$date_info->title = date ( 'm/Y', strtotime ( $month ) );
							$date_info->month_next = date ( 'Ym', strtotime ( $date_from . " + 1 month" ) );
							$date_info->month_pre = date ( 'Ym', strtotime ( $date_from . " - 1 month" ) );

							$return_data ['_data'] ['block_info'] = $date_info;

							$data_haha = array ();

							// Lay dich vụ học trong tuan
							for($week = 0; $week <= 5; $week ++) {

								$date_from_at = date ( 'Y-m-d', strtotime ( date ( $year_month_title . '01' ) . " + " . $week . " week" ) );

								$date_from = date ( "Y-m-d", strtotime ( 'monday this week', strtotime ( $date_from_at ) ) );

								$date_to = date ( 'Y-m-d', strtotime ( $date_from . " + 6 days" ) );

								$date1 = strtotime ( $date_from );

								$date2 = strtotime ( date ( $year_month_title . '01' ) . " + 1 month" );
								// neu ngay dau tien cua tuan lon hon hoac bang ngay dau tien cua thang sau thi dung
								if ($date1 >= $date2)
									break;

								$services = $this->db->table ( TBL_PS_SERVICE_COURSES . ' as SC' )->join ( CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'SC.ps_service_id' )->join ( TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', function ($q) {
									$q->on ( 'SCS.ps_service_course_id', '=', 'SC.id' )->whereRaw ( 'DATE_FORMAT(SC.start_at, "%Y%m%d") <= DATE_FORMAT(SCS.date_at, "%Y%m%d")' )->whereRaw ( 'DATE_FORMAT(SC.end_at, "%Y%m%d") >= DATE_FORMAT(SCS.date_at, "%Y%m%d")' );
								} )->join ( CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.ps_service_course_id', '=', 'SC.id' )->select ( 'SC.id as id', 'SCS.date_at as date_at', 'S.id as service_id' )->where ( 'SS.student_id', $student_id )->whereNull ( 'SS.delete_at' )->whereDate ( 'SCS.date_at', '>=', $date_from )->whereDate ( 'SCS.date_at', '<=', $date_to )->where ( 'S.ps_customer_id', $ps_student->ps_customer_id )->where ( 'SC.is_activated', STATUS_ACTIVE )->where ( 'S.enable_schedule', ENABLE_ROLL_SCHEDULE )->where ( 'SCS.is_activated', STATUS_ACTIVE )->where ( 'S.is_activated', STATUS_ACTIVE )->orderBy ( 'SCS.date_at' )->distinct ()->get ();

								$week_info = null;
								$data = array ();
								$data ['block_week'] = array ();
								$data ['week_info'] = array ();
								$subject_info = array ();

								$week_info = new \stdClass ();
								$week_info->week_data = $psI18n->__ ( 'Your baby does not have a schedule' );
								$week_info->week_title = $week + 1;
								$week_info->date_from_to = date ( 'd/m/Y', strtotime ( $date_from ) ) . '-' . date ( 'd/m/Y', strtotime ( $date_to ) );
								$week_info->week_current = (date ( "W", strtotime ( date ( 'Y-m-d' ) ) ) == date ( "W", strtotime ( $date_from ) )) ? 1 : 0;

								$_data_info = array ();

								$block_info = array ();

								$subject_info = array ();

								foreach ( $services as $key => $service ) {

									$block_info [$service->date_at] = PsDateTime::toFullDayInWeek ( $service->date_at, $code_lang );

									// Lay danh sach lich hoat dong hoc
									$features = $this->db->table ( TBL_FEATURE_BRANCH . ' as FB' )->leftjoin ( TBL_FEATURE_BRANCH_TIMES . ' as FBT', 'FBT.ps_feature_branch_id', '=', 'FB.id' )->leftjoin ( TBL_FEATURE_BRANCH_TIME_MY_CLASS . ' as FBTC', 'FBTC.ps_feature_branch_time_id', '=', 'FBT.id' )->join ( TBL_FEATURE . ' as F', 'F.id', '=', 'FB.feature_id' )->leftJoin ( CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', 'FB.ps_image_id' )->leftJoin ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'FBT.ps_class_room_id' )->selectRaw ( '"Is study" AS subject_title,FB.name AS service_course_title,FBT.start_time as start_at,FBT.end_time as end_at,CR.title as class_room_title' )->selectRaw ( 'Null as teacher_id, Null AS teacher_name, FBTC.ps_class_room AS ps_class_room' )->where ( 'F.ps_customer_id', $ps_student->ps_customer_id )->whereDate ( 'FBT.start_at', '<=', $date_at )->whereDate ( 'FBT.end_at', '>=', $date_at )->where ( 'F.is_activated', STATUS_ACTIVE )->where ( 'FB.is_study', '=', STATUS_ACTIVE )->where ( 'FB.is_activated', STATUS_ACTIVE )->where ( function ($query) use ($ps_student) {
										$query->where ( 'FBTC.ps_myclass_id', '=', $ps_student->class_id );
									} );

									$number_day = PsDateTime::getNumberDayOfDate ( $date_at );

									if ($number_day == '0') {
										$features = $features->where ( 'FBT.is_sunday', '=', STATUS_ACTIVE );
									} elseif ($number_day == 6) {
										$features = $features->where ( 'FBT.is_saturday', '=', STATUS_ACTIVE );
									}

									// Lay mon hoc co ngay trung voi ngay vua tim dc ( get subject_info )
									$_services = $this->db->table ( TBL_PS_SERVICE_COURSES . ' as SC' )->join ( CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'SC.ps_service_id' )->join ( CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.ps_service_course_id', '=', 'SC.id' )->join ( TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', 'SCS.ps_service_course_id', '=', 'SC.id' )->leftJoin ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'SCS.ps_class_room_id' )->leftJoin ( CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'SC.ps_member_id' )->select ( 'S.title as subject_title', 'SC.title AS service_course_title', 'SCS.start_time_at as start_at', 'SCS.end_time_at as end_at', 'CR.title as class_room_title', 'M.id as teacher_id' )->selectRaw ( 'CONCAT(M.first_name," ", M.last_name) AS teacher_name, CR.title As ps_class_room' )->whereDate ( 'SCS.date_at', '=', $service->date_at )->where ( 'S.id', '=', $service->service_id )->where ( 'SC.id', '=', $service->id );

									// ->orderBy ('SCS.start_time_at' )->distinct ()->get ();

									// Ghep dich vu va hoat dong
									$feature_services = $_services->unionall ( $features )->orderBy ( 'start_at' )->distinct ()->get ();

									if ($feature_services) {
										$week_info->week_data = '';
									}

									foreach ( $feature_services as $key => $_service ) {

										$_subject = array ();

										$_subject ['date_at'] = ( string ) $service->date_at;
										$_subject ['subject_title'] = ( string ) $psI18n->__ ( $_service->subject_title ) . '-' . ( string ) $_service->service_course_title;
										$_subject ['time_at'] = PsDateTime::getTime ( $_service->start_at ) . '-' . PsDateTime::getTime ( $_service->end_at );
										$_subject ['class_room'] = ($_service->class_room_title != '') ? $_service->class_room_title : ' ';

										if ($_service->ps_class_room != '') {
											$_subject ['class_room'] = ( string ) $_service->ps_class_room;
										} elseif ($_service->class_room_title != '') {
											$_subject ['class_room'] = ( string ) $_service->class_room_title;
										} else {
											$_subject ['class_room'] = '';
										}

										$_subject ['teacher_id'] = ( int ) $_service->teacher_id;
										$_subject ['teacher_name'] = ( string ) $_service->teacher_name;

										array_push ( $subject_info, $_subject );
									}
								}

								$_week_info = array ();

								$_week_info ['week_info'] = $week_info;

								// array_push ( $data ['block_week'], $_week_info );

								foreach ( $block_info as $key => $_info ) {

									$_data_info ['day_of_week'] = $_info;

									$subject_info_temp = array ();

									foreach ( $subject_info as $subject ) {
										if ($subject ['date_at'] == $key) {
											unset ( $subject ['date_at'] );
											array_push ( $subject_info_temp, $subject );
										}
									}

									$_data_info ['subject_info'] = $subject_info_temp;

									array_push ( $data ['block_week'], $_data_info );
								}

								array_push ( $data ['week_info'], $week_info );

								array_push ( $data_haha, $data );
							}

							$return_data ['_data'] ['data_info'] = $data_haha;

							$content = $this->studentScheduleWebContent ( $request, $response, $args );

							$return_data ['_data'] ['content'] = $content ['_data'] ['content'];
						} else {

							$return_data ['_msg_code'] = MSG_CODE_500;

							$return_data ['message'] = $psI18n->__ ( 'You do not have access to this data' );
						}
					}
				}
			} else {
				$return_data ['_msg_code'] = MSG_CODE_500;
				$return_data ['message'] = $psI18n->__ ( 'Time is invalid' );
			}
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );
			$return_data ['_msg_code'] = MSG_CODE_500;
			$return_data ['message'] = $psI18n->__ ( 'Network connection is not stable. Please do it again in a few minutes.' );
		}

		return $response->withJson ( $return_data );

	}

	// Xem lịch theo ngày
	public function studentScheduleOfDay(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array ();

		$return_data ['_msg_code'] = MSG_CODE_TRUE;

		$return_data ['_data'] = [ ];

		$user = $this->user_token;

		$code_lang = $this->getUserLanguage ( $user );

		$psI18n = new PsI18n ( $code_lang );

		if ($user->user_type != USER_TYPE_RELATIVE) {
			$return_data ['_msg_code'] = MSG_CODE_500;
			$return_data ['message'] = $psI18n->__ ( 'You do not have access to this data' );
			return $return_data;
		}

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		if (! PsAuthentication::checkDevice ( $user, $device_id )) {
			$return_data ['_msg_code'] = MSG_CODE_500;
			$return_data ['message'] = $psI18n->__ ( 'You have not confirmed the Terms to use. Please log out and log in again.' );
			return $return_data;
		} else {

			// Kiem tra tai khoan
			$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

			if (! $amount_info) {

				$return_data = array (
						'_msg_code' => MSG_CODE_PAYMENT,
						'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
				);

				return $return_data;
			}
		}

		$student_id = ( int ) $args ['student_id'];

		if ($student_id <= 0) {

			$return_data = array (
					'_msg_code' => MSG_CODE_500,
					'message' => $psI18n->__ ( 'You do not have access to this data' )
			);

			return $return_data;
		}

		$date_at = $args ['month'];

		if ($date_at == '' || ($date_at != '' && ! PsDateTime::validateDate ( $date_at, 'Ymd' ))) {
			$date_at = date ( "Ymd" );
		}

		// Set style for view HTML
		$user_app_config = json_decode ( $user->app_config );

		$app_config_color = (isset ( $user_app_config->style ) && $user_app_config->style != '') ? $user_app_config->style : 'green';

		if ($app_config_color == 'yellow_orange')
			$app_config_color = 'orange';

		try {
			// Lay thông tin học sinh va nguoi than
			$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

			if (! $ps_student) {
				$return_data = array (
						'_msg_code' => MSG_CODE_500,
						'message' => $psI18n->__ ( 'You do not have access to this data' )
				);

				return $return_data;
			} else {

				// Get infomation student
				$ps_student_info = StudentModel::studentInfo ( $ps_student );

				$web_content = PsWebContent::BeginHTMLPageBootstrapForSchedule ();

				$web_content .= '<div class="container-fluid">';
				$web_content .= '<div class="row py-2">'; // info hoc sinh
				$web_content .= '<div class="col-3"><img class="rounded-circle border ks-border-light-' . $app_config_color . '" src="' . $ps_student_info->avatar_url . '" style="width:100%;" /></div>';
				$web_content .= '<div class="col-9 ks-padding-5" style="line-height:1.3">';
				$web_content .= '<div class="ks-text-' . $app_config_color . '"><span style="font-size:16px;">' . $ps_student_info->first_name . ' ' . $ps_student_info->last_name . '</span></div>';
				$web_content .= '<div class="ks-text-grey">' . $psI18n->__ ( 'Birthday' ) . ' ' . $ps_student_info->birthday . '</div>';
				$web_content .= '<div class="ks-text-grey"><small>' . $psI18n->__ ( 'Class' ) . ': ' . $ps_student_info->class_name . '</small></div>';
				$web_content .= '</div>';
				$web_content .= '</div>';

				$web_content .= '</div>'; // END container-fluid

				$web_content .= '<div class="main">';

				/*
				 * if ($user->ps_customer_id == 6) {
				 * $web_content .= '<div class="ks-light-'.$app_config_color.' py-1 mb-2 pl-2 border-bottom ks-border-white ">'.$app_config_color.'-'.PsDateTime::toFullDayInWeek($date_at, $code_lang).'</div>';
				 * } else {
				 * // Title ngày
				 * $web_content .= '<div class="ks-light-'.$app_config_color.' py-1 mb-2 pl-2 border-bottom ks-border-white ">'.PsDateTime::toFullDayInWeek($date_at, $code_lang).'</div>';
				 * }
				 */
				$web_content .= '<div class="ks-light-' . $app_config_color . ' py-1 mb-2 pl-2 border-bottom ks-border-white ">' . PsDateTime::toFullDayInWeek ( $date_at, $code_lang ) . '</div>';

				// Lay lich
				$schedules = $this->getDataScheduleOfStudent ( $ps_student->ps_customer_id, $ps_student->class_id, $student_id, $date_at );

				$modal_id = 0;

				$web_content .= '<div class="px-3">';

				if (count ( $schedules ) <= 0) {

					$web_content .= '<div class="text-center">' . $psI18n->__ ( 'Your baby does not have a schedule' ) . '</div>';
				} else {

					foreach ( $schedules as $schedule ) {

						$modal_id ++;

						$web_content .= '<div class="border-bottom py-2">';

						if ($schedule->ps_class_room != '') {
							$class_room_name = ', <strong>' . $psI18n->__ ( 'Place' ) . '</strong>: ' . PsString::htmlSpecialChars ( $schedule->ps_class_room );
						} elseif ($schedule->class_room != '') {
							$class_room_name = ', <strong>' . $psI18n->__ ( 'Place' ) . '</strong>: ' . PsString::htmlSpecialChars ( $schedule->class_room );
						}

						$web_content .= '<div class="font-italic"><i class="far fa-clock"></i> <small>' . PsDateTime::getTime ( $schedule->start_at ) . '-' . PsDateTime::getTime ( $schedule->end_at ) . $class_room_name . '</small></div>';

						if ($schedule->id > 0)
							$web_content .= '<div><span class="font-weight-bold">' . $psI18n->__ ( 'Is study' ) . '</span>: <span class="ks-text-light-' . $app_config_color . ' ">' . PsString::htmlSpecialChars ( $schedule->feature_title ) . '</span></div>';
						else
							$web_content .= '<div class="ks-text-light-' . $app_config_color . ' " >' . PsString::htmlSpecialChars ( $schedule->feature_title ) . '</div>';

						if ($schedule->note != '') {

							$web_content .= '<div class="font-weight-bold font-italic" >' . $psI18n->__ ( 'Content' ) . '</div>';

							if (PsString::length ( $schedule->note ) <= 80) {

								$web_content .= '<div class="text-justify">' . PsString::nl2brChars ( $schedule->note ) . '</div>';
							} else {

								$web_content .= '<div class="text-justify">' . PsString::nl2brChars ( PsString::stringTruncate ( $schedule->note, 70 ) );

								$web_content .= '<button type="button" class="btn btn-link" data-toggle="modal" data-target="#ks_' . $modal_id . '">[' . $psI18n->__ ( 'Detail' ) . ']</button>' . '</div>';

								$web_content .= PsWebContent::modalPage ( $app_config_color, 'ks_' . $modal_id, '<div class="text-justify">' . PsString::nl2brChars ( $schedule->note ) . '</div>' );
							}
						} elseif ($schedule->fbt_note != '') {

							$web_content .= '<div class="font-weight-bold font-italic">' . $psI18n->__ ( 'Content' ) . '</div>';

							if (PsString::length ( $schedule->fbt_note ) <= 80) {
								$web_content .= '<div class="text-justify">' . PsString::nl2brChars ( $schedule->fbt_note ) . '</div>';
							} else {

								$web_content .= '<div class="text-justify">' . PsString::nl2brChars ( PsString::stringTruncate ( $schedule->fbt_note, 70 ) );

								$web_content .= '<button type="button" class="btn btn-link" data-toggle="modal" data-target="#ks_' . $modal_id . '">[' . $psI18n->__ ( 'Detail' ) . ']</button>' . '</div>';

								$web_content .= PsWebContent::modalPage ( $app_config_color, 'ks_' . $modal_id, '<div class="text-justify">' . PsString::nl2brChars ( $schedule->fbt_note ) . '</div>' );
							}
						}

						$web_content .= '</div>';
					}
				}

				$web_content .= '</div>';

				$web_content .= '</div>';

				$web_content .= PsWebContent::EndHTMLPage ();

				$return_data ['_msg_code'] = MSG_CODE_TRUE;
				$return_data ['_data'] ['user_info'] = $ps_student_info;
				$return_data ['_data'] ['content'] = $web_content;
			}
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );
			$return_data ['_msg_code'] = MSG_CODE_500;
			$return_data ['message'] = $psI18n->__ ( 'You can not see the student\'s information.' );
		}

		return $return_data;

	}

	// Xem lich hoc theo thang - dành cho all device
	public function studentScheduleWebContent(RequestInterface $request, ResponseInterface $response, array $args) {

		$return_data = array ();

		$return_data ['_msg_code'] = MSG_CODE_TRUE;

		$return_data ['_data'] = [ ];

		$user = $this->user_token;
		$code_lang = $this->getUserLanguage ( $user );
		$psI18n = new PsI18n ( $code_lang );

		if ($user->user_type != USER_TYPE_RELATIVE) {
			$return_data ['_msg_code'] = MSG_CODE_500;
			$return_data ['message'] = $psI18n->__ ( 'You do not have access to this data' );
			return $response->withJson ( $return_data );
		}

		// get device_id app
		$device_id = $request->getHeaderLine ( 'deviceid' );

		if (! PsAuthentication::checkDevice ( $user, $device_id )) {
			$return_data ['_msg_code'] = MSG_CODE_500;
			$return_data ['message'] = $psI18n->__ ( 'You have not confirmed the Terms to use. Please log out and log in again.' );
			return $response->withJson ( $return_data );
		} else {

			// Kiem tra tai khoan
			$amount_info = PsMobileAppAmountsModel::checkAmountInfo ( $user->id );

			if (! $amount_info) {

				$return_data = array (
						'_msg_code' => MSG_CODE_PAYMENT,
						'message' => $psI18n->__ ( 'Your account has run out of money. Please recharge to continue using.' )
				);

				return $response->withJson ( $return_data );
			}
		}

		$student_id = ( int ) $args ['student_id'];

		if ($student_id <= 0) {

			$return_data = array (
					'_msg_code' => MSG_CODE_500,
					'message' => $psI18n->__ ( 'You do not have access to this data' )
			);

			return $response->withJson ( $return_data );
		}

		$month = $args ['month'];

		if ($month == '' || ($month != '' && ! PsDateTime::validateDate ( $month, 'Ymd' ))) {
			$month = date ( "Ym" );
		}

		$_msg_text_error = '';

		// Set style for view HTML
		$user_app_config = json_decode ( $user->app_config );
		$app_config_color = (isset ( $user_app_config->style ) && $user_app_config->style != '') ? $user_app_config->style : 'green';

		if ($app_config_color == 'yellow_orange')
			$app_config_color = 'orange';

		try {
			// Lay thông tin học sinh va nguoi than
			$ps_student = StudentModel::getStudentForRelative ( $student_id, $user->member_id );

			if (! $ps_student) { // Khong tim thay thong tin hoc sinh
				$_msg_text_error = $psI18n->__ ( 'Find your system has not been associated with any student.' );
			} else {

				// Get infomation student
				$ps_student_info = StudentModel::studentInfo ( $ps_student );

				$month = $month . '01';

				$year_month_title = date ( 'Ym', strtotime ( $month ) );

				// Lay thong tin tuan ( get date_info )
				$date_at = date ( 'Y-m-d' );
				$date_from = date ( $year_month_title . '01' );
				$date_info = new \stdClass ();
				$date_to = date ( "Y-m-t", strtotime ( $month ) );
				$date_info->title = date ( 'm/Y', strtotime ( $month ) );
				$date_info->month_next = date ( 'Ym', strtotime ( $date_from . " + 1 month" ) );
				$date_info->month_pre = date ( 'Ym', strtotime ( $date_from . " - 1 month" ) );

				$return_data ['_data'] ['block_info'] = $date_info;

				$web_content = PsWebContent::BeginHTMLPageBootstrapForSchedule ();
				$web_content .= '<div id="box-webview">';

				$web_content .= '<div class="container-fluid">';
				$web_content .= '<div class="row py-2">'; // info hoc sinh
				$web_content .= '<div class="col-3"><img class="rounded-circle border ks-border-light-green" src="' . $ps_student_info->avatar_url . '" style="width:100%;" /></div>';
				$web_content .= '<div class="w3-col s10 w3-padding-small" style="line-height:1.3">';
				$web_content .= '<div class="ks-text-' . $app_config_color . '"><span style="font-size:16px;">' . $ps_student_info->first_name . ' ' . $ps_student_info->last_name . '</span></div>';
				$web_content .= '<div class="ks-text-grey">' . $psI18n->__ ( 'Birthday' ) . ' ' . $ps_student_info->birthday . '</div>';
				$web_content .= '<div class="ks-text-grey"><small>' . $psI18n->__ ( 'Class' ) . ': ' . $ps_student_info->class_name . '</small></div>';
				$web_content .= '</div>';
				$web_content .= '</div>';
				$web_content .= '</div>'; // END info hoc sinh

				$web_content .= '<div id="box-calendar" class="main">';

				$web_content_1 = '';

				for($week = 0; $week <= 5; $week ++) {

					$date_from_at = date ( 'Y-m-d', strtotime ( date ( $year_month_title . '01' ) . " + " . $week . " week" ) );

					$date_from = date ( "Y-m-d", strtotime ( 'monday this week', strtotime ( $date_from_at ) ) );

					$date_to = date ( 'Y-m-d', strtotime ( $date_from . " + 6 days" ) );

					$date1 = strtotime ( $date_from );

					$date2 = strtotime ( date ( $year_month_title . '01' ) . " + 1 month" );

					// neu ngay dau tien cua tuan lon hon hoac bang ngay dau tien cua thang sau thi dung
					if ($date1 >= $date2)
						break;

					$week_not_data = $psI18n->__ ( 'Your baby does not have a schedule' ); // Khong có lịch

					$week_current = (date ( "W", strtotime ( date ( 'Y-m-d' ) ) ) == date ( "W", strtotime ( $date_from ) )) ? 'show' : ''; // Tuan hien tai hay khong

					$week_title = $week + 1; // Ten tuan
					$text_date_from_to = date ( 'd.m.Y', strtotime ( $date_from ) ) . '-' . date ( 'd.m.Y', strtotime ( $date_to ) );

					$modal_id = $week;

					$web_content .= '<div id="id-week-' . $week . '">';

					// Ten tuan
					$web_content .= '<div class="ks-light-' . $app_config_color . ' py-1 mb-2 pl-2 text-uppercase border-bottom ks-border-white "><i class="fas fa-clock"></i>' . $psI18n->__ ( 'Week' ) . ' ' . $week_title . ': ' . $text_date_from_to . '</div>';
					// ' <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse_'.$week.'" aria-expanded="true" aria-controls="collapse_'.$week.'"> XX</button></div>';

					// Cac ngay trong tuan
					// $web_content .= '<div id="collapse_'.$week.'" class="collapse '.$week_current.' box-day-week px-3" aria-labelledby="heading_'.$week.'">';

					$chk_schedule_week = 0;

					$web_content .= '<div id="collapse_' . $week . '" class="box-day-week px-3">';
					for($i = 0; $i <= 6; $i ++) {

						$modal_id ++;

						$day_date = date ( 'Y-m-d', strtotime ( $date_from . " + " . $i . " days" ) );

						$_date_at = date ( 'Ymd', strtotime ( $date_from . " + " . $i . " days" ) );

						// Lay lich cua tung ngay
						$schedule_week = $this->getDataCalendarOfStudent ( $ps_student->ps_customer_id, $ps_student->class_id, $student_id, $day_date, $day_date );

						if (count ( $schedule_week ) > 0) {

							if ($_date_at == date ( Ymd )) {
								$web_content .= '<a id="anchor_today" style="padding-top:4px;"></a><div class="ks-text-' . $app_config_color . ' ks-border-bottom-light-green py-2 font-weight-bold">' . PsDateTime::toFullDayInWeek ( $day_date, $code_lang ) . '</div>';
							} else {
								$web_content .= '<div class="ks-text-' . $app_config_color . ' ks-border-bottom-light-green py-2 font-weight-bold">' . PsDateTime::toFullDayInWeek ( $day_date, $code_lang ) . '</div>';
							}

							$chk_schedule_week ++;

							$index_day = PsDateTime::getNumberDayOfDate ( $day_date );

							foreach ( $schedule_week as $schedule ) {

								if (($schedule->id > 0 && $index_day != 0 && $index_day != 6) || ($schedule->id <= 0 && PsDateTime::toYMD ( $schedule->scs_start_at ) == $day_date)) {

									$modal_id ++;

									$web_content .= '<div class="border-bottom pb-3">';

									if ($schedule->ps_class_room != '') {
										$class_room_name = ', <strong>' . $psI18n->__ ( 'Place' ) . '</strong>: ' . PsString::htmlSpecialChars ( $schedule->ps_class_room );
									} elseif ($schedule->class_room != '') {
										$class_room_name = ', <strong>' . $psI18n->__ ( 'Place' ) . '</strong>: ' . PsString::htmlSpecialChars ( $schedule->class_room );
									}

									$web_content .= '<div class="font-italic"><small>' . PsDateTime::getTime ( $schedule->start_at ) . '-' . PsDateTime::getTime ( $schedule->end_at ) . $class_room_name . '</small></div>';

									if ($schedule->id > 0)
										$web_content .= '<div><span class="font-weight-bold">' . $psI18n->__ ( 'Is study' ) . '</span>: <span class="ks-text-light-' . $app_config_color . ' ">' . PsString::htmlSpecialChars ( $schedule->feature_title ) . '</span></div>';
									else
										$web_content .= '<div class="ks-text-light-' . $app_config_color . ' " >' . PsString::htmlSpecialChars ( $schedule->feature_title ) . '</div>';

									if ($schedule->note != '') {

										$web_content .= '<div class="font-weight-bold font-italic" >' . $psI18n->__ ( 'Content' ) . '</div>';

										$web_content .= '<div class="text-justify">' . PsString::nl2brChars ( PsString::stringTruncate ( $schedule->note, 70 ) ) . '</div>';
									} elseif ($schedule->fbt_note != '') {

										$web_content .= '<div class="font-weight-bold font-italic">' . $psI18n->__ ( 'Content' ) . '</div>';

										if (PsString::length ( $schedule->fbt_note ) <= 70) {
											$web_content .= '<div class="text-justify">' . PsString::nl2brChars ( $schedule->fbt_note ) . '</div>';
										} else {

											$web_content .= '<div class="text-justify">' . PsString::nl2brChars ( PsString::stringTruncate ( $schedule->fbt_note, 100, '' ) );

											$web_content .= '<button type="button" class="btn btn-link" data-toggle="modal" data-target="#ks_' . $modal_id . '">[' . $psI18n->__ ( 'Detail' ) . ']</button>' . '</div>';

											$web_content .= PsWebContent::modalPage ( $app_config_color, 'ks_' . $modal_id, '<div class="text-justify">' . PsString::nl2brChars ( $schedule->fbt_note ) . '</div>' );
										}
									}

									$web_content .= '</div>';
								}
							}
						}
					}

					if ($chk_schedule_week <= 0) {
						$web_content .= '<div class="text-center py-1" style="color:#dc3545;">' . $week_not_data . '</div>';
					} else {

						$web_content .= '</div>';
						$web_content .= $web_content_1 . '</div>';
					}

					$web_content .= '</div>';
				}

				$web_content .= '</div>'; // END box-calendar
				$web_content .= '</div>'; // box-webview
				$web_content .= PsWebContent::EndHTMLPage ();

				$return_data ['_msg_code'] = MSG_CODE_TRUE;
				$return_data ['_data'] ['user_info'] = $ps_student_info;
				$return_data ['_data'] ['content'] = $web_content;
			}
		} catch ( Exception $e ) {

			$this->logger->err ( $e->getMessage () );
			$return_data ['_msg_code'] = MSG_CODE_500;
			$return_data ['message'] = $psI18n->__ ( 'You can not see the student\'s information.' );
		}

		return $return_data;

	}

	// Lay Lich hoat dong va lich hoc
	protected function getDataCalendarOfStudent($ps_customer_id, $class_id, $student_id, $from_date, $to_date) {

		// Lay danh sach hoat dong
		$features = $this->db->table ( TBL_FEATURE_BRANCH . ' as FB' )->leftjoin ( TBL_FEATURE_BRANCH_TIMES . ' as FBT', 'FBT.ps_feature_branch_id', '=', 'FB.id' )->leftjoin ( TBL_FEATURE_BRANCH_TIME_MY_CLASS . ' as FBTC', 'FBTC.ps_feature_branch_time_id', '=', 'FBT.id' )->join ( TBL_FEATURE . ' as F', 'F.id', '=', 'FB.feature_id' )->leftJoin ( CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', 'FB.ps_image_id' )->leftJoin ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'FBT.ps_class_room_id' )->selectRaw ( 'FB.id as id, FB.name as feature_title, I.file_name, FBT.start_at as scs_start_at, FBT.start_time as start_at, FBT.end_time as end_at,FBT.is_sunday as is_sunday,FBT.is_saturday as is_saturday ,CR.title as class_room_title, FBTC.ps_class_room AS ps_class_room,FBTC.id AS fbtc_id,FBTC.note as note, FBT.note AS fbt_note' )->where ( 'F.ps_customer_id', $ps_customer_id )->whereDate ( 'FBT.start_at', '<=', $from_date )->whereDate ( 'FBT.end_at', '>=', $to_date )->where ( 'F.is_activated', STATUS_ACTIVE )->where ( 'FB.is_activated', STATUS_ACTIVE )->where ( 'FB.is_study', '=', STATUS_ACTIVE )->where ( function ($query) use ($class_id) {
			$query->where ( 'FBTC.ps_myclass_id', '=', $class_id );
		} );

		// Lay thong tin dich vu theo thoi khoa bieu
		$services = $this->db->table ( TBL_PS_SERVICE_COURSES . ' as SC' )->join ( CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'SC.ps_service_id' )->join ( CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.ps_service_course_id', '=', 'SC.id' )->join ( TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', 'SCS.ps_service_course_id', '=', 'SC.id' )->leftJoin ( CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', 'S.ps_image_id' )->leftJoin ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'SCS.ps_class_room_id' )->selectRaw ( 'Null as id, S.title as feature_title, I.file_name, SCS.date_at as scs_start_at, SCS.start_time_at as start_at, SCS.end_time_at as end_at, 0 as is_sunday,0 as is_saturday, CR.title as class_room_title, null AS ps_class_room ,SCS.id AS fbtc_id, SCS.note as note, null AS fbt_note' )->where ( 'SS.student_id', $student_id )->whereNull ( 'SS.delete_at' )->

		// ->whereDate('SCS.date_at', $from_date)

		where ( function ($query) use ($from_date, $to_date) {
			$query->whereDate ( 'SCS.date_at', '>=', $from_date )->whereDate ( 'SCS.date_at', '<=', $to_date )->where ( 'SCS.is_activated', STATUS_ACTIVE );
		} )->
		whereDate ( 'SC.start_at', '<=', $from_date )->whereDate ( 'SC.end_at', '>=', $to_date )->where ( 'S.ps_customer_id', $ps_customer_id )->where ( 'SC.is_activated', STATUS_ACTIVE )->where ( 'S.enable_schedule', ENABLE_ROLL_SCHEDULE )->where ( 'S.is_activated', STATUS_ACTIVE );

		// Ghep dich vu va hoat dong
		$feature_services = $services->unionall ( $features )->orderBy ( 'start_at' )->distinct ()->get ();

		return $feature_services;

	}

	// Lay Lich hoat dong va lich hoc
	protected function getDataScheduleOfStudent($ps_customer_id, $class_id, $student_id, $date_at) {

		$this->WriteLog ( 'ps_customer_id: ' . $ps_customer_id . '- Class ID: ' . $class_id . ' - student_id:' . $student_id . '- Date: ' . $date_at );

		$date_at = date ( "Y-m-d", strtotime ( $date_at ) );

		// Lay danh sach hoat dong
		$features = $this->db->table ( TBL_FEATURE_BRANCH . ' as FB' )->leftjoin ( TBL_FEATURE_BRANCH_TIMES . ' as FBT', 'FBT.ps_feature_branch_id', '=', 'FB.id' )->leftjoin ( TBL_FEATURE_BRANCH_TIME_MY_CLASS . ' as FBTC', 'FBTC.ps_feature_branch_time_id', '=', 'FBT.id' )->join ( TBL_FEATURE . ' as F', 'F.id', '=', 'FB.feature_id' )->leftJoin ( CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', 'FB.ps_image_id' )->leftJoin ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'FBT.ps_class_room_id' )->selectRaw ( 'FB.id as id, FB.name as feature_title, I.file_name, FBT.start_at as scs_start_at, FBT.start_time as start_at, FBT.end_time as end_at,FBT.is_sunday as is_sunday,FBT.is_saturday as is_saturday ,CR.title as class_room_title, FBTC.ps_class_room AS ps_class_room,FBTC.id AS fbtc_id,FBTC.note as note, FBT.note AS fbt_note' )->where ( 'F.ps_customer_id', $ps_customer_id )->whereDate ( 'FBT.start_at', '<=', $date_at )->whereDate ( 'FBT.end_at', '>=', $date_at )->where ( 'F.is_activated', STATUS_ACTIVE )->where ( 'FB.is_activated', STATUS_ACTIVE )->where ( 'FB.is_study', '=', STATUS_ACTIVE )->where ( function ($query) use ($class_id) {
			$query->where ( 'FBTC.ps_myclass_id', '=', $class_id );
		} );

		$number_day = PsDateTime::getNumberDayOfDate ( $date_at );

		if ($number_day == '0') {
			$features = $features->where ( 'FBT.is_sunday', '=', STATUS_ACTIVE );
		} elseif ($number_day == 6) {
			$features = $features->where ( 'FBT.is_saturday', '=', STATUS_ACTIVE );
		}

		// Lay thong tin dich vu theo thoi khoa bieu
		$services = $this->db->table ( TBL_PS_SERVICE_COURSES . ' as SC' )->join ( CONST_TBL_SERVICE . ' as S', 'S.id', '=', 'SC.ps_service_id' )->join ( CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.ps_service_course_id', '=', 'SC.id' )->join ( TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', 'SCS.ps_service_course_id', '=', 'SC.id' )->leftJoin ( CONST_TBL_PSIMAGES . ' as I', 'I.id', '=', 'S.ps_image_id' )->leftJoin ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'SCS.ps_class_room_id' )->selectRaw ( 'Null as id, S.title as feature_title, I.file_name, SCS.date_at as scs_start_at, SCS.start_time_at as start_at, SCS.end_time_at as end_at, 0 as is_sunday,0 as is_saturday, CR.title as class_room_title, null AS ps_class_room ,SCS.id AS fbtc_id, SCS.note as note, null AS fbt_note' )->where ( 'SS.student_id', $student_id )->whereNull ( 'SS.delete_at' )
		
		//->whereDate('SCS.date_at', $from_date)
		
		->where(function ($query) use ($date_at) {
			$query->whereDate('SCS.date_at', '=', $date_at)->where('SCS.is_activated', STATUS_ACTIVE);
		})
		
		//->whereDate('SC.start_at', '<=', $date_at)
		//->whereDate('SC.end_at', '>=', $date_at)
		->where('S.ps_customer_id', $ps_customer_id)
		->where('SC.is_activated', STATUS_ACTIVE)
		->where('S.enable_schedule', ENABLE_ROLL_SCHEDULE)
		->where('S.is_activated', STATUS_ACTIVE);
		
		// Ghep dich vu va hoat dong
		$feature_services = $services->unionall($features)
		->orderBy('start_at')
		->distinct()
		->get();
		
		return $feature_services;
	}
	
	// UI Diem danh kieu 1 Chi tiet - Tong quan
	protected function setUIDiaryOfStudent($month, $psI18n,$code_lang, $ps_student, $ps_student_info, $app_config_color) {
		
		$student_id = $ps_student->id;
		
		$ps_student_info = StudentModel::studentInfo ( $ps_student );
			
		$ps_diarys = $this->db->table ( CONST_TBL_PS_LOGTIMES . ' as D' )
		->leftJoin ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=', 'D.login_relative_id' )
		->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', 'D.login_relative_id' )
		->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id' )
		->leftJoin ( CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'D.login_member_id' )
		->leftJoin ( CONST_TBL_RELATIVE . ' as R2', 'R2.id', '=', 'D.logout_relative_id' )
		->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS2', 'RS2.relative_id', '=', 'D.logout_relative_id' )
		->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE2', 'RE2.id', '=', 'RS2.relationship_id' )
		->leftJoin ( CONST_TBL_PS_MEMBER . ' as M2', 'M2.id', '=', 'D.logout_member_id' )
		
		->whereRaw ( 'D.student_id = ' . $student_id . ' AND ( (RS.student_id = ' . $student_id . ' OR RS.student_id IS NULL) AND (RS2.student_id = ' . $student_id . ' OR RS2.student_id IS NULL ) ) ' )
		
		//->where( 'D.student_id', $student_id)
		
		->whereRaw('DATE_FORMAT(D.login_at, "%Y%m") ='.$month)
		->orderBy ( 'D.login_at', 'desc' )
		->distinct ()
		->select ( 'D.id', 'D.student_id','D.log_value as log_value','D.note as note' ,'D.login_relative_id', 'D.login_at', 'D.logout_at', 'D.logout_relative_id', 'RE.title AS _part_in', 'D.login_member_id AS _id_teacher_in', 'M.avatar AS _avatar_teacher_in', 'M.ps_customer_id AS customer_id_in', 'RE2.title AS _part_out', 'D.logout_member_id AS _id_teacher_out', 'M2.ps_customer_id AS customer_id_out' )
		->selectRaw ( 'CONCAT(R.first_name," ", R.last_name) AS _name_take' )
		->selectRaw ( 'CONCAT(M.first_name," ", M.last_name) AS _name_teacher' )
		->selectRaw ( 'CONCAT(R2.first_name," ", R2.last_name) AS _name_reveler' )
		->selectRaw ( 'CONCAT(M2.first_name," ", M2.last_name) AS _name_teacher_reveler' )->get ();
		
		$web_content 	 = PsWebContent::BeginHTMLPageCalendar ( 'transparent' );
		
		$web_content .= '<div class="container-fluid ks-light-green fixed-top" style="width:100%;padding:0px;">';
		
			$web_content .= '<div class="row py-1">';// info hoc sinh
				$web_content .= '<div class="col-3"><img class="rounded-circle border border-white" src="' . $ps_student_info->avatar_url . '" style="width:100%;" /></div>';
				$web_content .= '<div class="col-9 ks-padding-5" style="line-height:1.0">';
				$web_content .= '<div><span style="font-size:16px;">' . $ps_student_info->first_name . ' ' . $ps_student_info->last_name . '</span></div>';
				$web_content .= '<div><small>' . $psI18n->__ ( 'Birthday' ) . ': ' . $ps_student_info->birthday . '</small></div>';
				$web_content .= '<div><small>' . $psI18n->__ ( 'Class' ) . ': ' . $ps_student_info->class_name . '</small></div>';
				$web_content .= '</div>';		
			$web_content .= '</div>';
		
		$web_content .= '</div>';// END container-fluid		
		
		$web_content .= '<div class="tab-content" id="nav-tabContent" style="padding-top:80px;">';
			
			$web_content .= '<div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1">';
		
				$web_content .= '<div class="container-fluid" style="opacity: 0.7;padding:0px;">';
				
				foreach ($ps_diarys as $ps_diary) {
					$web_content .= '<div class="shadow bg-white rounded text-center py-2" style="margin-top:3px;margin-bottom:2px;width:100%;">';
						$web_content .= '<div class="row py-1 ml-0 mr-0">';
						$web_content .= '<div class="col-12 border-bottom border-light ks-border-bottom-light-grey ks-text-'.$app_config_color.'">'.PsDateTime::toDayInWeek ( $ps_diary->login_at, $code_lang ).', '.PsDateTime::toDMY ( $ps_diary->login_at, 'd/m/Y' ).'</div>';
						$web_content .= '</div>';
						
					if ($ps_diary->log_value == CONSTANT_LOGVALUE_1) {
						
						$web_content .= '<div class="row py-1 ml-0 mr-0">';
							$web_content .= '<div class="col-2 ml-0 mr-0 border-bottom border-light"><img src="' . PS_URL_LIB_MEDIA.'/layout/mobile/icon/ic_time_in_class.png' . '" style="width:100%;"/></div>';
							$web_content .= '<div class="col-10 border-bottom border-light" style="text-align:left;padding-left:0px;">'.$psI18n->__('Check in').': <span class="text-danger">'.PsDateTime::toDateTimeToTime ( $ps_diary->login_at ).'</span></div>';
						$web_content .= '</div>';
						
						if ($ps_diary->_name_take != '') {
							$web_content .= '<div class="row py-1 ml-0 mr-0">';
								$web_content .= '<div class="col-2 border-bottom border-light"><img src="' . PS_URL_LIB_MEDIA.'/layout/mobile/icon/ic_relative_in.png' . '" style="width:100%;"/></div>';
								$web_content .= '<div class="col-10" style="text-align:left;padding-left:0px;">'.$psI18n->__('Relative login').': '.$ps_diary->_part_in.' '.$ps_diary->_name_take.'</div>';
							$web_content .= '</div>';
						}
						
						if ($ps_diary->_name_teacher != '') {
							$web_content .= '<div class="row py-1 ml-0 mr-0">';
							$web_content .= '<div class="col-2 border-bottom border-light"><img src="' . PS_URL_LIB_MEDIA.'/layout/mobile/icon/ic_teacher_in.png' . '" style="width:100%;"/></div>';
							$web_content .= '<div class="col-10" style="text-align:left;padding-left:0px;">'.$psI18n->__('Teacher receives').': '.$ps_diary->_name_teacher.'</div>';
							$web_content .= '</div>';
						}
						
						$web_content .= '<div class="row py-1 ml-0 mr-0">';
							$web_content .= '<div class="col-2 border-bottom border-light"><img src="' . PS_URL_LIB_MEDIA.'/layout/mobile/icon/ic_time_out_class.png' . '" style="width:100%;"/></div>';
							$web_content .= '<div class="col-10" style="text-align:left;padding-left:0px;">'.$psI18n->__('Check out').': '.PsDateTime::toDateTimeToTime ( $ps_diary->logout_at ).'</div>';
						$web_content .= '</div>';
						
						if ($ps_diary->_name_reveler != '') {
							$web_content .= '<div class="row py-1 ml-0 mr-0">';
							$web_content .= '<div class="col-2 border-bottom border-light"><img src="' . PS_URL_LIB_MEDIA.'/layout/mobile/icon/ic_relative_out.png' . '" style="width:100%;"/></div>';
							$web_content .= '<div class="col-10" style="text-align:left;padding-left:0px;">'.$psI18n->__('Relative logout').': '.$ps_diary->_part_out.' '.$ps_diary->_name_reveler.'</div>';
							$web_content .= '</div>';
						}
						
						if ($ps_diary->_name_teacher_reveler != '') {
							$web_content .= '<div class="row py-1 ml-0 mr-0">';
							$web_content .= '<div class="col-2 border-bottom border-light"><img src="' . PS_URL_LIB_MEDIA.'/layout/mobile/icon/ic_teacher_out.png' . '" style="width:100%;"/></div>';
							$web_content .= '<div class="col-10" style="text-align:left;padding-left:0px;">'.$psI18n->__('Teacher handover').': '.$ps_diary->_name_teacher_reveler.'</div>';
							$web_content .= '</div>';				
						}
						
						if ($ps_diary->note != '') {
							$web_content .= '<div class="row py-1 ml-0 mr-0">';
							$web_content .= '<div class="col-2 border-bottom border-light bg-white"><img class="bg-white" src="' . PS_URL_LIB_MEDIA.'/layout/mobile/icon/ic_note.png' . '" style="width:100%;"/></div>';
							$web_content .= '<div class="col-10 ks-small" style="text-align:left;padding-left:0px;">'.$psI18n->__('Note').': '.$ps_diary->note.'</div>';
							$web_content .= '</div>';
						}
						
					} elseif ($ps_diary->log_value == CONSTANT_LOGVALUE_0) {
						$web_content .= '<div class="row py-1 ml-0 mr-0">';
						$web_content .= '<div class="col-12 border-bottom border-light text-danger">'.$psI18n->__('Excused absence').'</div>';
						$web_content .= '</div>';
						if ($ps_diary->note != '') {
							$web_content .= '<div class="row py-1 ml-0 mr-0">';
							$web_content .= '<div class="col-2 border-bottom border-light bg-white"><img class="bg-white" src="' . PS_URL_LIB_MEDIA.'/layout/mobile/icon/ic_note.png' . '" style="width:100%;"/></div>';
							$web_content .= '<div class="col-10 ks-small" style="text-align:left;padding-left:0px;">'.$psI18n->__('Note').': '.$ps_diary->note.'</div>';
							$web_content .= '</div>';
						}
					}elseif ($ps_diary->log_value == CONSTANT_LOGVALUE_2) {
						$web_content .= '<div class="row py-1 ml-0 mr-0">';
						$web_content .= '<div class="col-12 border-bottom border-light text-danger">'.$psI18n->__('The student absent').'</div>';
						$web_content .= '</div>';
						if ($ps_diary->note != '') {
							$web_content .= '<div class="row py-1 ml-0 mr-0">';
							$web_content .= '<div class="col-2 border-bottom border-light bg-white"><img class="bg-white" src="' . PS_URL_LIB_MEDIA.'/layout/mobile/icon/ic_note.png' . '" style="width:100%;"/></div>';
							$web_content .= '<div class="col-10 ks-small" style="text-align:left;padding-left:0px;">'.$psI18n->__('Note').': '.$ps_diary->note.'</div>';
							$web_content .= '</div>';
						}
					}
					
					$web_content .= '</div>';
				}	
				$web_content .= '</div>';
			
			$web_content .= '</div>';
			
			$web_content .= '<div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2">';
				$web_content .= '<div class="container-fluid">Tổng quan</div>';
			$web_content .= '</div>';
		
		$web_content .= '</div>';// End tab_content		
		
		$web_content .= '<div class="container-fluid fixed-bottom ks-light-'.$app_config_color.'" style="opacity:0.7;padding:0px;">';		
		$web_content .= '<nav>
							<div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
							<a style="width:50%;" class="nav-item nav-link active" id="atab1" data-toggle="tab" href="#tab1" role="tab" aria-controls="nav-home" aria-selected="true">Chi tiết</a>
							<a style="width:50%;" class="nav-item nav-link" id="atab2" data-toggle="tab" href="#tab2" role="tab" aria-controls="nav-profile" aria-selected="false">Tổng quan</a>
							</div>
						</nav>';		
		$web_content .= '</div>';
		
		$web_content .= PsWebContent::EndHTMLPage ();
		
		return $web_content;
	}
	
	// UI Diem danh kieu 2 - Chi tiet
	protected function setUIDiaryDetailOfStudent($month, $psI18n,$code_lang, $ps_student, $ps_student_info, $app_config_color) {
	
		$student_id = $ps_student->id;
	
		$web_content = PsWebContent::BeginHTMLPageCalendar ( 'transparent' );
	
		$ps_student_info = StudentModel::studentInfo ( $ps_student );
			
		$ps_diarys = $this->db->table ( CONST_TBL_PS_LOGTIMES . ' as D' )
		->leftJoin ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=', 'D.login_relative_id' )
		->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.relative_id', '=', 'D.login_relative_id' )
		->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE', 'RE.id', '=', 'RS.relationship_id' )
		->leftJoin ( CONST_TBL_PS_MEMBER . ' as M', 'M.id', '=', 'D.login_member_id' )
		->leftJoin ( CONST_TBL_RELATIVE . ' as R2', 'R2.id', '=', 'D.logout_relative_id' )
		->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS2', 'RS2.relative_id', '=', 'D.logout_relative_id' )
		->leftJoin ( CONST_TBL_RELATIONSHIP . ' as RE2', 'RE2.id', '=', 'RS2.relationship_id' )
		->leftJoin ( CONST_TBL_PS_MEMBER . ' as M2', 'M2.id', '=', 'D.logout_member_id' )
	
		->whereRaw ( 'D.student_id = ' . $student_id . ' AND ( (RS.student_id = ' . $student_id . ' OR RS.student_id IS NULL) AND (RS2.student_id = ' . $student_id . ' OR RS2.student_id IS NULL ) ) ' )
	
		//->where( 'D.student_id', $student_id)
	
		->whereRaw('DATE_FORMAT(D.login_at, "%Y%m") ='.$month)
		->orderBy ( 'D.login_at', 'desc' )
		->distinct ()
		->select ( 'D.id', 'D.student_id','D.log_value as log_value','D.note as note' ,'D.login_relative_id', 'D.login_at', 'D.logout_at', 'D.logout_relative_id', 'RE.title AS _part_in', 'D.login_member_id AS _id_teacher_in', 'M.avatar AS _avatar_teacher_in', 'M.ps_customer_id AS customer_id_in', 'RE2.title AS _part_out', 'D.logout_member_id AS _id_teacher_out', 'M2.ps_customer_id AS customer_id_out' )
		->selectRaw ( 'CONCAT(R.first_name," ", R.last_name) AS _name_take' )
		->selectRaw ( 'CONCAT(M.first_name," ", M.last_name) AS _name_teacher' )
		->selectRaw ( 'CONCAT(R2.first_name," ", R2.last_name) AS _name_reveler' )
		->selectRaw ( 'CONCAT(M2.first_name," ", M2.last_name) AS _name_teacher_reveler' )->get ();
	
		$web_content .= '<div class="container-fluid ks-light-green fixed-top" style="width:100%;padding-bottom:0px;">';
	
		$web_content .= '<div class="row py-1">';// info hoc sinh
		$web_content .= '<div class="col-3"><img class="rounded-circle border border-white" src="' . $ps_student_info->avatar_url . '" style="width:100%;" /></div>';
		$web_content .= '<div class="col-9 ks-padding-5" style="line-height:1.3">';
		$web_content .= '<div><span style="font-size:16px;">' . $ps_student_info->first_name . ' ' . $ps_student_info->last_name . '</span></div>';
		$web_content .= '<div><small>' . $psI18n->__ ( 'Birthday' ) . ': ' . $ps_student_info->birthday . '</small></div>';
		$web_content .= '<div><small>' . $psI18n->__ ( 'Class' ) . ': ' . $ps_student_info->class_name . '</small></div>';
		$web_content .= '</div>';
		$web_content .= '</div>';
	
		$web_content .= '</div>'; // END container-fluid
	
		$web_content .= '<div class="container-fluid" style="opacity: 0.7;padding-top:85px;padding-left:0px;padding-right:0px;">';
	
		foreach ($ps_diarys as $ps_diary) {
			
			$web_content .= '<div class="shadow bg-white rounded text-center py-2" style="margin-top:3px;margin-bottom:2px;width:100%;">';
				$web_content .= '<div class="row py-1 ml-0 mr-0">';
				$web_content .= '<div class="col-12 border-bottom border-light ks-border-bottom-light-grey ks-text-'.$app_config_color.'">'.PsDateTime::toDayInWeek ( $ps_diary->login_at, $code_lang ).', '.PsDateTime::toDMY ( $ps_diary->login_at, 'd/m/Y' ).'</div>';
				$web_content .= '</div>';
	
			if ($ps_diary->log_value == CONSTANT_LOGVALUE_1) {
	
				$web_content .= '<div class="row py-1 ml-0 mr-0">';
				$web_content .= '<div class="col-2 ml-0 mr-0 border-bottom border-light"><img src="' . PS_URL_LIB_MEDIA.'/layout/mobile/icon/ic_time_in_class.png' . '" style="width:100%;"/></div>';
				$web_content .= '<div class="col-10 border-bottom border-light" style="text-align:left;padding-left:0px;">'.$psI18n->__('Check in').': <span class="text-danger">'.PsDateTime::toDateTimeToTime ( $ps_diary->login_at ).'</span></div>';
				$web_content .= '</div>';
	
				if ($ps_diary->_name_take != '') {
					$web_content .= '<div class="row py-1 ml-0 mr-0">';
					$web_content .= '<div class="col-2 border-bottom border-light"><img src="' . PS_URL_LIB_MEDIA.'/layout/mobile/icon/ic_relative_in.png' . '" style="width:100%;"/></div>';
					$web_content .= '<div class="col-10" style="text-align:left;padding-left:0px;">'.$psI18n->__('Relative login').': '.$ps_diary->_part_in.' '.$ps_diary->_name_take.'</div>';
					$web_content .= '</div>';
				}
	
				if ($ps_diary->_name_teacher != '') {
					$web_content .= '<div class="row py-1 ml-0 mr-0">';
					$web_content .= '<div class="col-2 border-bottom border-light"><img src="' . PS_URL_LIB_MEDIA.'/layout/mobile/icon/ic_teacher_in.png' . '" style="width:100%;"/></div>';
					$web_content .= '<div class="col-10" style="text-align:left;padding-left:0px;">'.$psI18n->__('Teacher receives').': '.$ps_diary->_name_teacher.'</div>';
					$web_content .= '</div>';
				}
	
				$web_content .= '<div class="row py-1 ml-0 mr-0">';
				$web_content .= '<div class="col-2 border-bottom border-light"><img src="' . PS_URL_LIB_MEDIA.'/layout/mobile/icon/ic_time_out_class.png' . '" style="width:100%;"/></div>';
				$web_content .= '<div class="col-10" style="text-align:left;padding-left:0px;">'.$psI18n->__('Check out').': '.PsDateTime::toDateTimeToTime ( $ps_diary->logout_at ).'</div>';
				$web_content .= '</div>';
	
				if ($ps_diary->_name_reveler != '') {
					$web_content .= '<div class="row py-1 ml-0 mr-0">';
					$web_content .= '<div class="col-2 border-bottom border-light"><img src="' . PS_URL_LIB_MEDIA.'/layout/mobile/icon/ic_relative_out.png' . '" style="width:100%;"/></div>';
					$web_content .= '<div class="col-10" style="text-align:left;padding-left:0px;">'.$psI18n->__('Relative logout').': '.$ps_diary->_part_out.' '.$ps_diary->_name_reveler.'</div>';
					$web_content .= '</div>';
				}
	
				if ($ps_diary->_name_teacher_reveler != '') {
					$web_content .= '<div class="row py-1 ml-0 mr-0">';
					$web_content .= '<div class="col-2 border-bottom border-light"><img src="' . PS_URL_LIB_MEDIA.'/layout/mobile/icon/ic_teacher_out.png' . '" style="width:100%;"/></div>';
					$web_content .= '<div class="col-10" style="text-align:left;padding-left:0px;">'.$psI18n->__('Teacher handover').': '.$ps_diary->_name_teacher_reveler.'</div>';
					$web_content .= '</div>';
				}
	
				if ($ps_diary->note != '') {
					$web_content .= '<div class="row py-1 ml-0 mr-0">';
					$web_content .= '<div class="col-2 border-bottom border-light bg-white"><img class="bg-white" src="' . PS_URL_LIB_MEDIA.'/layout/mobile/icon/ic_note.png' . '" style="width:100%;"/></div>';
					$web_content .= '<div class="col-10 ks-small" style="text-align:left;padding-left:0px;">'.$psI18n->__('Note').': '.$ps_diary->note.'</div>';
					$web_content .= '</div>';
				}
	
			} elseif ($ps_diary->log_value == CONSTANT_LOGVALUE_0) {
				$web_content .= '<div class="row py-1 ml-0 mr-0">';
				$web_content .= '<div class="col-12 border-bottom border-light text-danger">'.$psI18n->__('Excused absence').'</div>';
				$web_content .= '</div>';
				if ($ps_diary->note != '') {
					$web_content .= '<div class="row py-1 ml-0 mr-0">';
					$web_content .= '<div class="col-2 border-bottom border-light bg-white"><img class="bg-white" src="' . PS_URL_LIB_MEDIA.'/layout/mobile/icon/ic_note.png' . '" style="width:100%;"/></div>';
					$web_content .= '<div class="col-10 ks-small" style="text-align:left;padding-left:0px;">'.$psI18n->__('Note').': '.$ps_diary->note.'</div>';
					$web_content .= '</div>';
				}
			} elseif ($ps_diary->log_value == CONSTANT_LOGVALUE_2) {
				$web_content .= '<div class="row py-1 ml-0 mr-0">';
				$web_content .= '<div class="col-12 border-bottom border-light text-danger">'.$psI18n->__('The student absent').'</div>';
				$web_content .= '</div>';
				if ($ps_diary->note != '') {
					$web_content .= '<div class="row py-1 ml-0 mr-0">';
					$web_content .= '<div class="col-2 border-bottom border-light bg-white"><img class="bg-white" src="' . PS_URL_LIB_MEDIA.'/layout/mobile/icon/ic_note.png' . '" style="width:100%;"/></div>';
					$web_content .= '<div class="col-10 ks-small" style="text-align:left;padding-left:0px;">'.$psI18n->__('Note').': '.$ps_diary->note.'</div>';
					$web_content .= '</div>';
				}
			}
			
			$web_content .= '</div>';
		}
		
		$web_content .= '</div>';
		
		$web_content .= PsWebContent::EndHTMLPage ();
	
		return $web_content;
	}
}
