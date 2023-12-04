<?php
require_once dirname ( __FILE__ ) . '/../lib/psStudentFeaturesGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psStudentFeaturesGeneratorHelper.class.php';

/**
 * psStudentFeatures actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psStudentFeatures
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psStudentFeaturesActions extends autoPsStudentFeaturesActions {

	public function executeWarning(sfWebRequest $request) {

		$this->filter_value = $this->getFilters ();
		$this->filter_value ['ps_class_id'] = (isset ( $this->filter_value ['ps_class_id'] )) ? $this->filter_value ['ps_class_id'] : '';
		$this->filter_value ['feature_branch_id'] = (isset ( $this->filter_value ['feature_branch_id'] )) ? $this->filter_value ['feature_branch_id'] : '';

		$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

		if ($this->filter_value ['ps_class_id'] <= 0) {

			$this->getUser ()
				->setFlash ( 'warning', 'Please select class to view student list', false );
		} elseif ($this->filter_value ['feature_branch_id'] <= 0) {
			$this->getUser ()
				->setFlash ( 'warning', 'Please select feature branch to view student list', false );
		} else {

			$checkLogtimes = Doctrine::getTable ( 'PsLogtimes' )->checkLogtimeByClassIdAndTrackedAt ( $this->filter_value ['ps_class_id'], $this->filter_value ['tracked_at'] );

			if (! $checkLogtimes) {

				$this->getUser ()
					->setFlash ( 'danger', $this->getContext ()
					->getI18N ()
					->__ ( 'Day %value% not yet implemented student attendance.', array (
						'%value%' => date ( "d-m-Y", strtotime ( $this->filter_value ['tracked_at'] ) ) ) ), false );
			}
		}
	}

	public function executeIndex(sfWebRequest $request) {

		// sorting
		if ($request->getParameter ( 'sort' ) && $this->isValidSortColumn ( $request->getParameter ( 'sort' ) )) {
			$this->setSort ( array (
					$request->getParameter ( 'sort' ),
					$request->getParameter ( 'sort_type' ) ) );
		}

		$this->filter_value = $this->getFilters ();
		$this->filter_value ['ps_class_id'] = (isset ( $this->filter_value ['ps_class_id'] )) ? $this->filter_value ['ps_class_id'] : '';
		$this->filter_value ['feature_branch_id'] = (isset ( $this->filter_value ['feature_branch_id'] )) ? $this->filter_value ['feature_branch_id'] : '';

		// pager
		if ($request->getParameter ( 'page' )) {
			$this->setPage ( $request->getParameter ( 'page' ) );
		}

		if ($this->filter_value ['ps_class_id'] <= 0) {

			$this->getUser ()
				->setFlash ( 'warning', 'Please select class to view student list', false );

			$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

			// $this->setTemplate ( 'warning' );
			$this->redirect ( '@ps_student_features_warning' );
		} else {
			// Kiem tra xem hoat dong nay co can phai diem danh hay khong
			$featureBranch = Doctrine::getTable("FeatureBranch")->getFeatureBranchByField($this->filter_value ['feature_branch_id'], 'is_depend_attendance');
			
			if($featureBranch->getIsDependAttendance() == PreSchool::ACTIVE){
				
				$checkLogtimes = Doctrine::getTable ( 'PsLogtimes' )->checkLogtimeByClassIdAndTrackedAt ( $this->filter_value ['ps_class_id'], $this->filter_value ['tracked_at'] );
				
				if (! $checkLogtimes) {
					
					$this->getUser ()
					->setFlash ( 'danger', $this->getContext ()
							->getI18N ()
							->__ ( 'Day %value% not yet implemented student attendance.', array (
									'%value%' => date ( "d-m-Y", strtotime ( $this->filter_value ['tracked_at'] ) ) ) ), false );
							
							$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );
							
							// $this->redirect('@ps_student_features');
							
							$this->setTemplate ( 'warning' );
							
							// exit ();
				}elseif ($this->filter_value ['feature_branch_id'] <= 0) {
					
					$this->getUser ()
					->setFlash ( 'warning', 'Please select feature branch to view student list', false );
					$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );
					
					// $this->setTemplate ( 'warning' );
					$this->redirect ( '@ps_student_features_warning' );
					// exit ();
				} else {
					$this->pager = $this->getPager ();
					$this->sort = $this->getSort ();
				}
			}else{
				
				$this->pager = $this->getPager ();
				$this->sort = $this->getSort ();
				
			}
			/*
			$checkLogtimes = Doctrine::getTable ( 'PsLogtimes' )->checkLogtimeByClassIdAndTrackedAt ( $this->filter_value ['ps_class_id'], $this->filter_value ['tracked_at'] );

			if (! $checkLogtimes) {

				$this->getUser ()
					->setFlash ( 'danger', $this->getContext ()
					->getI18N ()
					->__ ( 'Day %value% not yet implemented student attendance.', array (
						'%value%' => date ( "d-m-Y", strtotime ( $this->filter_value ['tracked_at'] ) ) ) ), false );

				$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

				// $this->redirect('@ps_student_features');

				$this->setTemplate ( 'warning' );

				// exit ();
			} elseif ($this->filter_value ['feature_branch_id'] <= 0) {

				$this->getUser ()
					->setFlash ( 'warning', 'Please select feature branch to view student list', false );
				$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

				// $this->setTemplate ( 'warning' );
				$this->redirect ( '@ps_student_features_warning' );
				// exit ();
			} else {
				$this->pager = $this->getPager ();
				$this->sort = $this->getSort ();
			}
			*/
		}
	}

	public function executeFilter(sfWebRequest $request) {

		$this->setPage ( 1 );

		if ($request->hasParameter ( '_reset' )) {
			$this->setFilters ( $this->configuration->getFilterDefaults () );

			$this->redirect ( '@ps_student_features' );
		}

		$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

		$this->filters->bind ( $request->getParameter ( $this->filters->getName () ) );
		if ($this->filters->isValid ()) {
			$this->setFilters ( $this->filters->getValues () );

			$this->redirect ( '@ps_student_features' );
		}

		$this->filter_value = $request->getParameter ( $this->filters->getName () );

		$this->filter_value ['ps_class_id'] = (isset ( $this->filter_value ['ps_class_id'] )) ?: '';
		$this->filter_value ['feature_branch_id'] = (isset ( $this->filter_value ['feature_branch_id'] )) ? $this->filter_value ['feature_branch_id'] : '';
		$this->pager = $this->getPager ();
		$this->sort = $this->getSort ();

		if ($this->filter_value ['ps_class_id'] <= 0) {

			$this->getUser ()
				->setFlash ( 'warning', 'Please select class to view student list', false );
			// $this->redirect('@ps_student_features/warning');
			$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

			$this->setTemplate ( 'warning' );

			// $this->redirect('@ps_student_features_warning');
		} else if ($this->filter_value ['feature_branch_id'] <= 0) {

			$this->getUser ()
				->setFlash ( 'warning', 'Please select feature branch to view student list', false );

			$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );
			$this->redirect ( '@ps_student_features_warning' );
		} else {
			$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );
			$this->setTemplate ( 'warning' );
		}
	}

	public function executeSaveStudentFeature(sfWebRequest $request) {

		$getFilter = $this->getFilters ();
		$ps_customer_id = $getFilter ['ps_customer_id'];
		$class_id = $getFilter ['ps_class_id'];
		$date = $getFilter ['tracked_at'];
		$ps_workplace_id = $getFilter ['ps_workplace_id'];

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_FEATURE_FILTER_SCHOOL' ) || !myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {
			if ($ps_customer_id != myUser::getPscustomerID ()) {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
			}
		}
		
		$tracked_at = $request->getParameter ( 'tracked_at' );
		$feature_branch_id = $request->getParameter ( 'feature_branch_id' );
		$feature_options = $request->getParameter ( 'feature_option' );

		// print_r($feature_options); die;
		
		$feature_branch_one = Doctrine::getTable ( 'FeatureBranch' )->updateNumberOptionFeature ( 'id, name',$feature_branch_id );

		$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $ps_workplace_id,'id,from_time_notication_activities,to_time_notication_activities');
		$start_sent_notifi = $ps_workplace->getFromTimeNoticationActivities (); // gio bat dau nhan thong bao
		$stop_sent_notifi = $ps_workplace->getToTimeNoticationActivities (); // gio ket thuc nhan thong bao

		$cauhinh = 0;
		if (strtotime ( $tracked_at ) == strtotime ( date ( 'Y-m-d' ) )) {
			if ($start_sent_notifi == "00:00:00" && $stop_sent_notifi == "00:00:00") { // neu khong cau hinh thi gui thong bao
				$cauhinh = 1;
			} elseif (strtotime ( $start_sent_notifi ) <= strtotime ( date ( 'H:i:s' ) ) && strtotime ( $stop_sent_notifi ) >= strtotime ( date ( 'H:i:s' ) )) {
				$cauhinh = 1;
			}
		}

		$user_id = myUser::getUserId ();
		
		$current_date = date ( "Ymd" );

		$check_current_date = (PsDateTime::psDatetoTime ( $tracked_at ) - PsDateTime::psDatetoTime ( $current_date ) >= 0) ? true : false; // Ngay hien tai

		$check_current_date = true;

		if (! $check_current_date) {
			$this->getUser ()
				->setFlash ( 'notice', 'You can only edit or delete performance evaluation for 1 day.' );

			$this->redirect ( '@ps_student_features' );
		}

		$currentUser = myUser::getUser ();

		if ($feature_options) {

			$history = array ();
			$sum_feature = $sum_note = 0;

			foreach ( $feature_options as $student_id => $feature_option_id ) {

				$email = 0;
				$sum_feature ++;
				$error = $time = $text = null;

				$feature_option_feature_id = $feature_option_id;

				$featureHistory = new PsHistoryStudentFeatures ();

				$featureHistory->setStudentId ( $student_id );

				$featureHistory->setTrackedAt ( $tracked_at );

				$student_info = Doctrine::getTable ( 'Student' )->getStudentByField ( $student_id,'id,first_name,last_name,ps_customer_id' );

				if (Doctrine_Core::getTable ( 'StudentFeature' )->updateStudentFeatureByDate ( $student_id, $feature_branch_id, strtotime ( $tracked_at ) )) {
					$featureHistory->setPsAction ( 'edit' );
				} else {
					$featureHistory->setPsAction ( 'add' );
				}
				$history [$student_id] = $featureHistory;

				Doctrine_Core::getTable ( 'StudentFeature' )->updateStudentFeatureByDate ( $student_id, $feature_branch_id, strtotime ( $tracked_at ) )->delete ();

				if (is_array ( $feature_option_id ) && $feature_option_id != 0) {

					// <<<---- Start filter ----
					foreach ( $feature_option_id as $oid => $value ) {

						// get featureOptionFeature id
						if ($oid > 0) {

							$feature_option_feature_id = $oid;

							// *** Is textbox or selecttime ***
							if (is_array ( $value )) {

								foreach ( $value as $j => $get ) {

									// Is textkbox
									if ($j == 'textbox') {
										if ($get == null) {
											$error = 1;
											$this->getUser ()
												->setFlash ( 'error', 'A comment is required' );
											$this->redirect ( '@ps_student_features' );
										} else {
											$sum_note ++;
											$text = $get;
											$student_feature = new StudentFeature ();
											$student_feature->setStudentId ( $student_id );
											$student_feature->setTrackedAt ( $tracked_at );
											$student_feature->setNote ( $text );
											$student_feature->setTimeAt ( $time );
											$student_feature->setFeatureOptionFeatureId ( $feature_option_feature_id );
											$student_feature->setUserCreatedId ( $user_id );
											$student_feature->setUserUpdatedId ( $user_id );
											$student_feature->save ();
										}
									}
									// Is selecttime
								}
							} else {

								$student_feature = new StudentFeature ();
								$student_feature->setStudentId ( $student_id );
								$student_feature->setTrackedAt ( $tracked_at );
								$student_feature->setNote ( $text );
								$student_feature->setTimeAt ( $time );
								$student_feature->setFeatureOptionFeatureId ( $value );
								$student_feature->setUserCreatedId ( $user_id );
								$student_feature->setUserUpdatedId ( $user_id );
								$student_feature->save ();
							}
						} elseif ($oid == 'email') {
							$email = $value;
						}
					}
				} else {

					$student_feature = new StudentFeature ();
					$student_feature->setStudentId ( $student_id );
					$student_feature->setTrackedAt ( $tracked_at );
					$student_feature->setNote ( $text );
					$student_feature->setTimeAt ( $time );
					$student_feature->setFeatureOptionFeatureId ( $feature_option_feature_id );
					$student_feature->setUserCreatedId ( $user_id );
					$student_feature->setUserUpdatedId ( $user_id );
					$student_feature->save ();
				}

				$feature = "";

				$int_tracked_at = PsDateTime::psDatetoTime ( $tracked_at );

				$optionFeature = Doctrine::getTable ( 'StudentFeature' )->getFeatureOption ( $student_id, $int_tracked_at );

				foreach ( $optionFeature as $option ) {
					if ($option->getNote () == null)
						$feature .= $option->getFeatureOptionName () . ", ";
					else
						$feature .= $option->getNote () . ", ";
				}

				$feature = substr ( $feature, 0, - 2 );

				// gui thong bao anh Thang check lai code xem da dung hay chua?

				if ($cauhinh == 1 && ($ps_customer_id == $student_info->getPsCustomerId())) {

					$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getRelativeSentNotificationByStudent ( $ps_customer_id, $class_id, $student_id );

					$registrationIds_ios = array ();
					$registrationIds_android = array ();

					foreach ( $list_received_id as $user_nocation ) {
						if ($user_nocation->getNotificationToken () != '') {
							if ($user_nocation->getOsname () == 'IOS')
								array_push ( $registrationIds_ios, $user_nocation->getNotificationToken () );
							else
								array_push ( $registrationIds_android, $user_nocation->getNotificationToken () );
						}
					}

					if ((count ( $registrationIds_android ) > 0 || count ( $registrationIds_ios ) > 0)) {
						$psI18n = $this->getContext ()
							->getI18N ();
						$notication_setting = new \stdClass ();

						$notication_setting->title = $psI18n->__ ( 'Comment student' ) . " " . $student_info->getFirstName () . " " . $student_info->getLastName (); // ho ten hoc sinh

						$notication_setting->subTitle = $psI18n->__ ( 'From teacher' ) . ' ' . myUser::getUser ()->getFirstName () . " " . myUser::getUser ()->getLastName (); // ho ten nguoi danh gia hoat dong

						$notication_setting->tickerText = $psI18n->__ ( 'Message - KidsSchool.vn' );

						$content = $psI18n->__ ( 'Comment activities' ) . ' ' . $feature_branch_one->getName (); // ten hoat dong

						$content .= $feature;

						$notication_setting->message = $content;

						$notication_setting->lights = '1';
						$notication_setting->vibrate = '1';
						$notication_setting->sound = '1';

						$notication_setting->smallIcon = IC_SMALL_NOTIFICATION;
						$notication_setting->smallIconOld = 'ic_small_notification_old';

						// Lay avatar nguoi gui
						$profile = $this->getUser ()
							->getGuardUser ()
							->getProfileShort ();

						if ($profile && $profile->getAvatar () != '') {

							$url_largeIcon = PreString::getUrlMediaAvatar ( $profile->getCacheData (), $profile->getYearData (), $profile->getAvatar (), '01' );

							$largeIcon = PsFile::urlExists ( $url_largeIcon ) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
						} else {
							$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
						}

						$notication_setting->largeIcon = $largeIcon;

						$notication_setting->screenCode = PsScreenCode::PS_CONST_SCREEN_FEATURE;
						$notication_setting->itemId = '0';
						$notication_setting->clickUrl = '';

						// Deviceid registration firebase
						if ($registrationIds_ios > 0) {
							$notication_setting->registrationIds = $registrationIds_ios;
							$notification = new PsNotification ( $notication_setting );
							$result = $notification->pushNotification ( PS_CONST_PLATFORM_IOS );
						}

						if ($registrationIds_android > 0) {
							$notication_setting->registrationIds = $registrationIds_android;
							$notification = new PsNotification ( $notication_setting );
							$result = $notification->pushNotification ( PS_CONST_PLATFORM_ANDROID );
						}
					} // end send notication
				} // end check setting

				if ($email == 1) { // gui email cho phu huynh

					/*
					lay email nguoi giam ho chinh
					$list_relatives = Doctrine::getTable('Relative')->getEmailRelativeParentMain($ps_customer_id,$student_id);
					echo count($list_relative);
					if(count($list_relative) > 0){

					$mailto = array();
					foreach ($list_relatives as $list_relative){
					array_push($mailto, $list_relative->getEmail());
					}

					$this->getMailer()->compose('thanhp421@gmail.com', 'thanhpv32@wru.vn', 'Subject', 'Body');
					$this->getMailer()->send();

					$this->getMailer()->composeAndSend('from@example.com', $mailto, 'Subject', 'Body');

					cau hinh gui email o day
					$message = Swift_Message::newInstance()
					->setFrom('from@example.com')
					->setTo($email_send)
					->setSubject('Subject')
					->setBody('Body')
					// ->attach(Swift_Attachment::fromPath('/path/to/a/file.zip'))
					;

					$this->getMailer()->send($message);
					if($this->getMailer()->send($message)){
					$success = 1;
					}else{
					$success = 2;
					}
					echo 'AAAAA_'.'<br/>';
					}else {
					echo 'BBBBB_'.'<br/>';
					}
					*/
				}

				$featureBranchs = Doctrine::getTable ( 'FeatureBranchTimes' )->getFeatureBranchInformation ( $feature_branch_id, $int_tracked_at );

				foreach ( $featureBranchs as $featureBranch ) {

					$historyContent = $this->getContext ()
						->getI18N ()
						->__ ( 'Feature branch name' ) . ": " . $featureBranch->getFeatureName () . '\n' . $this->getContext ()
						->getI18N ()
						->__ ( 'Start time' ) . ": " . $featureBranch->getStartTime () . '\n' . $this->getContext ()
						->getI18N ()
						->__ ( 'End time' ) . ": " . $featureBranch->getEndTime () . '\n' . $this->getContext ()
						->getI18N ()
						->__ ( 'Feature' ) . ": " . $feature . '\n' . $this->getContext ()
						->getI18N ()
						->__ ( 'Created by' ) . ": " . $currentUser->getFirstName () . " " . $currentUser->getLastName () . "(" . $currentUser->getUsername () . ")" . '\n';

					$history [$student_id]->setHistoryContent ( $historyContent );

					$history [$student_id]->save ();
				}
			}

			// Cap nhat thong ke danh gia hoat dong
			$number_feature = Doctrine_Core::getTable ( 'PsFeatureBranchSynthetic' )->getPsFeatureBranchSyntheticByClass ( $class_id, $feature_branch_id, $date );

			if (! $number_feature) {
				$number_feature = new PsFeatureBranchSynthetic ();
				$number_feature->setPsCustomerId ( $ps_customer_id );
				$number_feature->setPsClassId ( $class_id );
				$number_feature->setFeatureId ( $feature_branch_id );
				$number_feature->setFeatureSum ( $sum_feature );
				$number_feature->setNoteSum($sum_note);
				$number_feature->setTrackedAt ( $date );
				$number_feature->setUserUpdatedId ( $user_id );
				$number_feature->save ();
			} else {
				$number_feature->setFeatureSum ( $sum_feature );
				$number_feature->setNoteSum($sum_note);
				$number_feature->save ();
			}
		}

		$this->getUser ()
			->setFlash ( 'notice', 'Performance evaluation was saved successfully. You can add another one below.' );
		$this->redirect ( '@ps_student_features' );
	}

	public function executeHistory(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_school_year_id = null;

		$ps_workplace_id = null;

		$year_month = null;

		$class_id = null;

		$student_id = null;

		$this->filter_list_student = array ();

		$history_filter = $request->getParameter ( 'history_filter' );

		if ($request->isMethod ( 'post' )) {

			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'history_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$class_id = $value_student_filter ['class_id'];

			$student_id = $value_student_filter ['student_id'];

			$date_at_from = $value_student_filter ['date_at_from'];

			$date_at_to = $value_student_filter ['date_at_to'];

			// echo $student_id.$date_at_from.$date_at_to; die();

			$this->filter_list_history = Doctrine::getTable ( 'PsHistoryStudentFeatures' )->getHistoryStudentFeatures ( $student_id, $date_at_from, $date_at_to );
		}

		if ($history_filter) {

			$this->ps_workplace_id = isset ( $history_filter ['ps_workplace_id'] ) ? $history_filter ['ps_workplace_id'] : 0;

			$this->class_id = isset ( $history_filter ['class_id'] ) ? $history_filter ['class_id'] : 0;

			$this->student_id = isset ( $history_filter ['student_id'] ) ? $history_filter ['student_id'] : 0;

			$this->date_at_from = isset ( $history_filter ['date_at_from'] ) ? $history_filter ['date_at_from'] : '';

			$this->date_at_to = isset ( $history_filter ['date_at_to'] ) ? $history_filter ['date_at_to'] : '';

			if ($this->ps_workplace_id > 0) {

				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => true ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => true,
					'data-placeholder' => _ ( '-All school-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true ) ) );
		}
		$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
			->fetchOne ()
			->getId ();

		if ($this->ps_customer_id == '') {
			$this->ps_customer_id = myUser::getPscustomerID ();
			$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$this->ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		if ($this->ps_customer_id > 0) {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $this->ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => true,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => true ) ) );

			// Filters by class
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id,
							'ps_school_year_id' => $this->ps_school_year_id,
							'is_activated' => PreSchool::ACTIVE ) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => true ) ) );

			// Filters student
			$this->formFilter->setWidget ( 'student_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Student',
					'query' => Doctrine::getTable ( 'Student' )->setSqlListStudentsNotSaturday ( $class_id ),
					'add_empty' => _ ( '-Select student-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select student-' ) ) ) );

			$this->formFilter->setValidator ( 'student_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'Student',
					'required' => false ) ) );
		} else {
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorPass () );

			$this->formFilter->setWidget ( 'student_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select student-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select student-' ) ) ) );

			$this->formFilter->setValidator ( 'student_id', new sfValidatorPass () );
		}

		$this->formFilter->setWidget ( 'ps_school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => false ), array (
				'class' => 'select2',
				'style' => "width:100%;min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ),
				'required' => true ) ) );

		$this->formFilter->setValidator ( 'ps_school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'column' => 'id',
				'required' => true ) ) );

		$this->formFilter->setWidget ( 'date_at_from', new psWidgetFormFilterInputDate ( array (), array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => _ ( 'Date at' ),
				'data-original-title' => $this->getContext ()
					->getI18N ()
					->__ ( 'Date at' ),
				'rel' => 'tooltip' ) ) );

		$this->formFilter->setValidator ( 'date_at_from', new sfValidatorDate ( array (
				'required' => false ) ) );

		$this->formFilter->setWidget ( 'date_at_to', new psWidgetFormFilterInputDate ( array (), array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => _ ( 'Date to' ),
				'data-original-title' => $this->getContext ()
					->getI18N ()
					->__ ( 'Date to' ),
				'rel' => 'tooltip' ) ) );

		$this->formFilter->setValidator ( 'date_at_to', new sfValidatorDate ( array (
				'required' => false ) ) );

		$this->formFilter->setDefault ( 'date_at_from', $this->date_at_from );

		$this->formFilter->setDefault ( 'date_at_to', $this->date_at_to );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'class_id', $this->class_id );

		$this->formFilter->setDefault ( 'student_id', $this->student_id );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'history_filter[%s]' );
	}
}
