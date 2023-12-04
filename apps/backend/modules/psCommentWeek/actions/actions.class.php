<?php
require_once dirname ( __FILE__ ) . '/../lib/psCommentWeekGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psCommentWeekGeneratorHelper.class.php';

/**
 * psCommentWeek actions.
 *
 * @package kidsschool.vn
 * @subpackage psCommentWeek
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psCommentWeekActions extends autoPsCommentWeekActions {

	// Gui thong bao cho tung phu huynh
	public function executeNotication(sfWebRequest $request) {

		$comment_id = $request->getParameter ( 'comment_id' );
		$student_id = $request->getParameter ( 'student_id' );
		$user_id = myUser::getUserId();
		
		$student = Doctrine::getTable ( 'Student' )->getStudentByField ( $student_id,'first_name,last_name,student_code,ps_customer_id' );
		$psCommentWeek = Doctrine_Core::getTable ( 'PsCommentWeek' )->getCommentWeekByField ( $comment_id,'id,number_push_notication' );
		
		if (! myUser::checkAccessObject ( $student, 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			echo $this->getContext ()
				->getI18N ()
				->__ ( 'Not roll data' );

			exit ( 0 );
		} else {

			$conn = Doctrine_Manager::connection ();

			try {
				
				$conn->beginTransaction ();

				if ($psCommentWeek) {
					$psCommentWeek->setNumberPushNotication ( $psCommentWeek->getNumberPushNotication () + 1 );
					$psCommentWeek->save ();
					// $receipt_date = $receipt->getReceiptDate ();
					$student_name = $student->getFirstName () . ' ' . $student->getLastName ();
					$student_code = $student->getStudentCode ();

					$ps_customer_id = $student->getPsCustomerId ();

					$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getRelativeSentNotificationByStudent ( $ps_customer_id, null, $student_id );
					
					if (count ( $list_received_id ) > 0) {
						
						$registrationIds_ios = array ();
						$registrationIds_android = array ();
						
						$relative_ids = [];
						$relative_ids_str = '';

						foreach ( $list_received_id as $user_nocation ) {

							if ($user_nocation->getNotificationToken () != '') {

								if ($user_nocation->getOsname () == PreSchool::PS_CONST_PLATFORM_IOS) {
									array_push ( $registrationIds_ios, $user_nocation->getNotificationToken () );
								} else {
									array_push ( $registrationIds_android, $user_nocation->getNotificationToken () );
								}
							}
							
							$relative_ids[] = $user_nocation->id;
						}
						$relative_ids_str = implode(',', $relative_ids);

						$psI18n = $this->getContext ()->getI18N ();

						$title = $psCommentWeek->getTitle() ? $psCommentWeek->getTitle() : 'Thông báo nhận xét tháng - tuần';

						if ((count ( $registrationIds_android ) > 0 || count ( $registrationIds_ios ) > 0)) {
							
							$setting = new \stdClass ();

							

							$setting->title = $title;

							$setting->subTitle = $student_name;

							$setting->tickerText = 'Thông báo nhận xét tháng - tuần từ KidsSchool.vn';

							$content = 'Học sinh' . ": " . $student_code . ' - ' . $student_name;

							$content .= ' Thông báo nhận xét tháng - tuần.';

							$setting->message = $content;

							$setting->lights = '1';
							$setting->vibrate = '1';
							$setting->sound = '1';
							$setting->smallIcon = 'ic_small_notification';
							$setting->smallIconOld = 'ic_small_notification_old';

							// Lay avatar nguoi gui thong bao
							$profile = $this->getUser ()
								->getGuardUser ()
								->getProfileShort ();

							if ($profile && $profile->getAvatar () != '') {

								$url_largeIcon = PreString::getUrlMediaAvatar ( $profile->getCacheData (), $profile->getYearData (), $profile->getAvatar (), '01' );

								$largeIcon = PsFile::urlExists ( $url_largeIcon ) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							} else {
								$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							}

							$setting->largeIcon = $largeIcon;

							$setting->screenCode = PsScreenCode::PS_CONST_SCREEN_REPORT_FEE;
							$setting->itemId = '0';
							$setting->clickUrl = '';

							// Deviceid registration firebase
							if (count ( $registrationIds_ios ) > 0) {
								$setting->registrationIds = $registrationIds_ios;

								$notification = new PsNotification ( $setting );
								$result = $notification->pushNotification ( PreSchool::PS_CONST_PLATFORM_IOS );
							}

							if (count ( $registrationIds_android ) > 0) {
								
								$setting->registrationIds = $registrationIds_android;
								$notification = new PsNotification ( $setting );
								$result 	  = $notification->pushNotification ();
							}
							
							
						} // end sent notication
						$content_notif = 'Học sinh' . ": " . $student_code . ' - ' . $student_name;

						$content_notif .= ' Thông báo nhận xét tháng - tuần.';
						
						$notifi = new PsCmsNotifications();
						$notifi-> setPsCustomerId($ps_customer_id);
						$notifi-> setTitle($title);
						$notifi-> setDescription($content_notif);
						$notifi-> setIsStatus('sent');
						$notifi-> setDateAt(date('Y-m-d H:i:s'));
						$notifi-> setTextObjectReceived($relative_ids_str);
						$notifi-> setRootScreen('NhanXet');
						$notifi-> setUserCreatedId($user_id);
						$notifi-> save();
						
						$ps_cms_notification_id = $notifi->id;
						
						foreach ($list_received_id as $received) {
							
							$rece = new PsCmsReceivedNotification();
							$rece-> setPsCmsNotificationId($ps_cms_notification_id);
							$rece-> setUserId($received->id);
							$rece-> setIsRead(0);
							$rece-> setDateAt(date('Y-m-d H:i:s'));
							$rece-> setUserCreatedId($user_id);
							$rece-> setIsDelete(0);
							$rece-> save();
						}
					}
				}

				$conn->commit ();

				return $this->renderPartial ( 'psCommentWeek/load_number_notication', array ('psCommentWeek' => $psCommentWeek ) );
				
			} catch ( Exception $e ) {

				throw new Exception ( $e->getMessage () );

				$this->logMessage ( "ERROR GUI THONG BAO NHAN XET THANG - TUAN: " . $e->getMessage () );

				$conn->rollback ();

				echo $this->getContext ()->getI18N ()->__ ( 'No send notication was saved failed.' );

				exit ();
			}
		}
	}

	// Gui thong bao cho nhieu phu huynh
	protected function executeBatchPushNotication(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		if (myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			$records = Doctrine_Query::create ()->from ( 'PsCommentWeek' )
				->whereIn ( 'id', $ids )
				->execute ();
		} else {
			$records = Doctrine_Query::create ()->from ( 'PsCommentWeek' )
				->whereIn ( 'id', $ids )
				->addWhere ( 'ps_customer_id =?', myUser::getPscustomerID () )
				->execute ();
		}

		$true = 0;
		
		$student_ids = array();
		
		$user_id = myUser::getUserId();
		

		foreach ( $records as $key => $record ) {

			if ($key == 0) { // chi lay 1 lan
				$ps_customer_id = $record->getPsCustomerId ();
				// $receipt_date = $record->getReceiptDate ();
			}

			$student_id = $record->getStudentId ();

			array_push($student_ids, $student_id);
			
			$record->setNumberPushNotication ( $record->getNumberPushNotication () + 1 );

			$record->save ();

			$true ++;
			
		}
		if(count($student_ids) > 0){
			$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getRelativeSentNotificationByStudent ( $ps_customer_id, null, $student_ids );
		}else{ 
			$list_received_id = array(); 
		}
		if (count ( $list_received_id ) > 0) {
			
			$registrationIds_ios = array ();
			
			$registrationIds_android = array ();
			
			$psI18n = $this->getContext ()->getI18N ();
			
			$relative_ids = [];
						
			$relative_ids_str = '';
			
			foreach ($list_received_id as $user_nocation ) {
				
				if ($user_nocation->getNotificationToken () != '') {
					
					$setting = new \stdClass ();
					
					$setting->title = 'Thông báo nhận xét tháng - tuần';
					
					$setting->subTitle = 'Thông báo nhận xét tháng - tuần của ' . $user_nocation->getStudentName();
					
					$setting->tickerText = 'Thông báo nhận xét tháng - tuần từ KidsSchool.vn';
					
					$content = 'Học sinh' . ": " . $user_nocation->getStudentCode() . ' - ' . $user_nocation->getStudentName();
					
					$content .= ' Thông báo nhận xét tháng - tuần.';
					
					$setting->message = $content;
					
					$setting->lights = '1';
					$setting->vibrate = '1';
					$setting->sound = '1';
					$setting->smallIcon = 'ic_small_notification';
					$setting->smallIconOld = 'ic_small_notification_old';
					
					// Lay avatar nguoi gui thong bao
					$profile = $this->getUser ()->getGuardUser ()->getProfileShort ();
					
					if ($profile && $profile->getAvatar () != '') {
						
						$url_largeIcon = PreString::getUrlMediaAvatar ( $profile->getCacheData (), $profile->getYearData (), $profile->getAvatar (), '01' );
						
						$largeIcon = PsFile::urlExists ( $url_largeIcon ) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
					} else {
						$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
					}
					
					$setting->largeIcon = $largeIcon;
					
					$setting->screenCode = PsScreenCode::PS_CONST_SCREEN_REPORT_FEE;
					$setting->itemId = '0';
					$setting->clickUrl = '';
					
					if ($user_nocation->getOsname () == PreSchool::PS_CONST_PLATFORM_IOS ) {
						
						$setting->registrationIds = $user_nocation->getNotificationToken ();
						
						$notification = new PsNotification ( $setting );
						$result = $notification->pushNotification ( PreSchool::PS_CONST_PLATFORM_IOS );
						
					}else{
						
						$setting->registrationIds = $user_nocation->getNotificationToken ();
						
						$notification = new PsNotification ( $setting );
						$result = $notification->pushNotification ();
						
					}
				}
				
				$relative_ids_str = $user_nocation->id;
				
				$content_notif = 'Học sinh' . ": " . $user_nocation->getStudentCode() . ' - ' . $user_nocation->getStudentName();
				$content_notif .= ' Thông báo nhận xét tháng - tuần';
				
				$notifi = new PsCmsNotifications();
				$notifi-> setPsCustomerId($ps_customer_id);
				$notifi-> setTitle('Thông báo nhận xét tháng - tuần.');
				$notifi-> setDescription($content_notif);
				$notifi-> setIsStatus('sent');
				$notifi-> setDateAt(date('Y-m-d H:i:s'));
				$notifi-> setTextObjectReceived($relative_ids_str);
				$notifi-> setRootScreen('NhanXet');
				$notifi-> setUserCreatedId($user_id);
				$notifi-> save();
				
				$ps_cms_notification_id = $notifi->id;
					
				$rece = new PsCmsReceivedNotification();
				$rece-> setPsCmsNotificationId($ps_cms_notification_id);
				$rece-> setUserId($user_nocation->id);
				$rece-> setIsRead(0);
				$rece-> setDateAt(date('Y-m-d H:i:s'));
				$rece-> setUserCreatedId($user_id);
				$rece-> setIsDelete(0);
				$rece-> save();
				
			}
			
		}
		
		if ($true == 0) {
			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'You must at least select one item is public' ) );
		} else {
			$this->getUser ()
				->setFlash ( 'notice', $this->getContext ()
				->getI18N ()
				->__ ( 'The selected items have been send notication successfully.' ) );
		}
		$this->redirect ( '@ps_comment_week' );
	}

	public function executeFilter(sfWebRequest $request) {

		$this->setPage ( 1 );

		$ps_comment_week_url = $request->getParameter ( 'ps_comment_week_url' );

		if ($request->hasParameter ( '_reset' )) {
			$this->setFilters ( $this->configuration->getFilterDefaults () );

			if ($ps_comment_week_url != ''){
				$this->redirect ( '@ps_comment_week_all' );
			}else{
				$this->redirect ( '@ps_comment_week' );
			}
		}

		$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

		$this->filters->bind ( $request->getParameter ( $this->filters->getName () ) );
		if ($this->filters->isValid ()) {
			$this->setFilters ( $this->filters->getValues () );

			if ($ps_comment_week_url != ''){
				$this->redirect ( '@ps_comment_week_all' );
			}else{
				$this->redirect ( '@ps_comment_week' );
			}
		}

		$this->pager = $this->getPager ();
		$this->sort = $this->getSort ();

		$this->setTemplate ( 'index' );
	}

	public function executeNew(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$student_id = $request->getParameter ( 'sid' );
			
			if ($student_id <= 0) {
				$this->setTemplate('detailError404','psCpanel');
			}
			
			$ps_student = Doctrine::getTable ( 'Student' )->getStudentByField ( $student_id,'id,first_name,last_name,birthday,ps_customer_id' );
			
			if (!myUser::checkAccessObject ( $ps_student, 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' )) {
				$this->setTemplate('detailError404','psCpanel');
			}
			
			$comment_week_filters = $this->getFilters ( 'ps_comment_week_filters' );

			$ps_customer_id = isset($comment_week_filters ['ps_customer_id']) ? $comment_week_filters ['ps_customer_id'] : 0;

			$ps_year = isset($comment_week_filters ['ps_year']) ? $comment_week_filters ['ps_year'] : date("Y");

			$ps_month = isset($comment_week_filters ['ps_month']) ? $comment_week_filters ['ps_month'] : date("m");

			$ps_week = isset($comment_week_filters ['ps_week']) ? $comment_week_filters ['ps_week'] : null;

			$ps_comment_week = new PsCommentWeek ();
			
			$ps_comment_week->setStudentId ( $student_id );
			$ps_comment_week->setPsCustomerId ( $ps_customer_id );
			$ps_comment_week->setPsYear ( $ps_year );
			$ps_comment_week->setPsMonth ( $ps_month );
			$ps_comment_week->setPsWeek ( $ps_week );
			
			$this->form = $this->configuration->getForm ( $ps_comment_week );
			
			$this->ps_comment_week = $this->form->getObject ();
			
			return $this->renderPartial ( 'psCommentWeek/formSuccess', array (
					'ps_comment_week' => $this->ps_comment_week,
					'form' => $this->form,
					'configuration' => $this->configuration,
					'ps_student'=> $ps_student,
					'helper' => $this->helper ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeCreate(sfWebRequest $request) {

		$formValues = $request->getParameter ( 'ps_comment_week' );

		$student_id = isset ( $formValues ['student_id'] ) ? $formValues ['student_id'] : '';

		if ($ps_student > 0) {

			$ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $student_id );

			$this->forward404Unless ( myUser::checkAccessObject ( $ps_student, 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		}

		$ps_comment_week = new PsCommentWeek ();

		$ps_comment_week->setStudentId ( $student_id );

		$this->form = $this->configuration->getForm ( $ps_comment_week );

		$this->ps_comment_week = $this->form->getObject ();

		$this->processForm ( $request, $this->form );

		exit ( 0 );
	}

	public function executeEdit(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$this->ps_comment_week = $this->getRoute ()
				->getObject ();

			$student_id = $this->ps_comment_week->getStudentId ();
			
			$student = Doctrine::getTable ( 'Student' )->getStudentByField ( $student_id,'id,first_name,last_name,birthday,ps_customer_id' );
			
			$this->forward404Unless ( myUser::checkAccessObject ( $student, 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

			$this->form = $this->configuration->getForm ( $this->ps_comment_week );

			return $this->renderPartial ( 'psCommentWeek/formSuccess', array (
					'ps_comment_week' => $this->ps_comment_week,
					'form' => $this->form,
					'configuration' => $this->configuration,
					'ps_student'=> $student,
					'helper' => $this->helper ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeDelete(sfWebRequest $request) {

		$ps_customer_id = $this->getRoute ()
			->getObject ()
			->getPsCustomerId ();

		if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' )) {
			if ($ps_customer_id != myUser::getPscustomerID ()) {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}
		}

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_comment_week' );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_comment_week = $this->getRoute ()
			->getObject ();

		$this->form = $this->configuration->getForm ( $this->ps_comment_week );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	public function executeBatch(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$ids = array_filter ( $request->getParameter ( 'ids' ) );

		if (! $ids) {
			$this->getUser ()
				->setFlash ( 'error', 'You must at least select one item.' );

			$this->redirect ( '@ps_comment_week' );
		}

		if (! $action = $request->getParameter ( 'batch_action' )) {
			$this->getUser ()
				->setFlash ( 'error', 'You must select an action to execute on the selected items.' );

			$this->redirect ( '@ps_comment_week' );
		}

		if (! method_exists ( $this, $method = 'execute' . ucfirst ( $action ) )) {
			throw new InvalidArgumentException ( sprintf ( 'You must create a "%s" method for action "%s"', $method, $action ) );
		}

		if (! $this->getUser ()
			->hasCredential ( $this->configuration->getCredentials ( $action ) )) {
			$this->forward ( sfConfig::get ( 'sf_secure_module' ), sfConfig::get ( 'sf_secure_action' ) );
		}

		$validator = new sfValidatorDoctrineChoice ( array (
				'multiple' => true,
				'model' => 'PsCommentWeek' ) );

		try {

			// validate ids
			$ids = $validator->clean ( $ids );

			// execute batch
			$this->$method ( $request );
		} catch ( sfValidatorError $e ) {
			$this->getUser ()
				->setFlash ( 'error', 'A problem occurs when deleting the selected items as some items do not exist anymore.' );
		}

		$this->redirect ( '@ps_comment_week' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = array_filter ( $request->getParameter ( 'ids' ) );

		$records = Doctrine_Query::create ()->from ( 'PsCommentWeek' )
			->whereIn ( 'id', $ids )
			->execute ();

		foreach ( $records as $record ) {
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );

			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
		$this->redirect ( '@ps_comment_week' );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		$formValues = $request->getParameter ( $form->getName () );

		$student_id = isset ( $formValues ['student_id'] ) ? $formValues ['student_id'] : '';

		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {
				$error = 0;
				$ps_month = $form->getValue ( 'ps_month' );

				$ps_week = $form->getValue ( 'ps_week' );

				// echo $ps_week;die;
				if ($ps_month > 0 || $ps_week > 0) {
					$ps_comment_week = $form->save ();
				} else {
					$error = 1;
				}
			} catch ( Doctrine_Validator_Exception $e ) {

				$errorStack = $form->getObject ()
					->getErrorStack ();

				$message = get_class ( $form->getObject () ) . ' has ' . count ( $errorStack ) . " field" . (count ( $errorStack ) > 1 ? 's' : null) . " with validation errors: ";
				foreach ( $errorStack as $field => $errors ) {
					$message .= "$field (" . implode ( ", ", $errors ) . "), ";
				}
				$message = trim ( $message, ', ' );

				$this->getUser ()
					->setFlash ( 'error', $message );
				return sfView::SUCCESS;
			}

			if ($error == 1) {
				$this->getUser ()
					->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );
			}

			$this->redirect ( '@ps_comment_week' );
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );

			$this->redirect ( '@ps_comment_week' );
		}
	}

	public function executeComment(sfWebRequest $request) {

		$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

		$comment_week_filters = $this->getFilters ( 'ps_comment_week_filters' );

		$this->ps_customer_id = isset($comment_week_filters ['ps_customer_id']) ? $comment_week_filters ['ps_customer_id'] : null;

		$this->ps_workplace_id = isset($comment_week_filters ['ps_workplace_id']) ? $comment_week_filters ['ps_workplace_id'] : null;

		$this->ps_class_id = isset($comment_week_filters ['ps_class_id']) ? $comment_week_filters ['ps_class_id'] : null;

		$this->ps_year = isset($comment_week_filters ['ps_year']) ? $comment_week_filters ['ps_year'] : date("Y");

		$this->ps_month = isset($comment_week_filters ['ps_month']) ? $comment_week_filters ['ps_month'] : date("m");

		$this->ps_week = isset($comment_week_filters ['ps_week']) ? $comment_week_filters ['ps_week'] : null;

		if ($this->ps_month != '') {
			$var_month = $this->ps_month . '-' . $this->ps_year;
		} elseif ($this->ps_week != '') {
			$ps_date_at = PsDateTime::getStaturdayOfWeek ( $this->ps_week, $this->ps_year );
			$var_month = date ( 'm-Y', strtotime ( $ps_date_at ['week_end'] ) );
		} else {
			$var_month = date ( 'm-Y' );
		}

		$this->list_student = Doctrine::getTable ( 'Student' )->getListStudentServiceByClass ( $this->ps_customer_id, $this->ps_workplace_id, $this->ps_class_id, $var_month )
			->execute ();
		$this->comment_student = Doctrine::getTable ( 'PsCommentWeek' )->getCommentWeekByWeek ( $this->ps_customer_id, $this->ps_year, $this->ps_week, $this->ps_month );
	}

	public function executeCommentSave(sfWebRequest $request) {

		$comment_week_fix = $request->getParameter ( 'comment_week_fix' );
		$comment_week = $request->getParameter ( 'comment_week' );

		$user_id = myUser::getUserId ();

		$title = $comment_week_fix ['title'];

		if ($comment_week_fix ['ps_month'] == '') {
			$ps_month = '';
			$ps_week = $comment_week_fix ['ps_week'];
		} else {
			$ps_month = $comment_week_fix ['ps_month'];
			$ps_week = '';
		}

		$ps_year = $comment_week_fix ['ps_year'];

		$ps_customer_id = $comment_week_fix ['ps_customer_id'];

		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' )) {
			if ($ps_customer_id != myUser::getPscustomerID ()) {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}
		}

		$conn = Doctrine_Manager::connection ();

		try {
			$conn->beginTransaction ();

			foreach ( $comment_week as $key => $comment ) {

				$student_id = $key;
				// $comment = $comment['comment'];
				if ($comment ['comment'] != '') {

					$ps_comment_week = Doctrine_Core::getTable ( 'PsCommentWeek' )->checkCommentWeekByStudent ( $student_id, $ps_year, $ps_week, $ps_month );

					if ($comment ['title'] != '') {
						$title = $comment ['title'];
					}
					if ($ps_comment_week) {

						$ps_comment_week->setTitle ( $title );
						$ps_comment_week->setComment ( $comment ['comment'] );
						$ps_comment_week->setUserUpdatedId ( $user_id );
						$ps_comment_week->save ();
					} else {

						$ps_comment_week = new PsCommentWeek ();
						$ps_comment_week->setPsCustomerId ( $ps_customer_id );
						$ps_comment_week->setStudentId ( $student_id );
						$ps_comment_week->setPsYear ( $ps_year );
						$ps_comment_week->setPsMonth ( $ps_month );
						$ps_comment_week->setPsWeek ( $ps_week );
						$ps_comment_week->setTitle ( $title );
						$ps_comment_week->setComment ( $comment ['comment'] );
						$ps_comment_week->setUserUpdatedId ( $user_id );
						$ps_comment_week->setUserCreatedId ( $user_id );
						$ps_comment_week->save ();
					}
				}
			}

			$conn->commit ();
		} catch ( Exception $e ) {

			throw new Exception ( $e->getMessage () );

			$this->getUser ()
				->setFlash ( 'error', 'Trackbook attendance was saved failed.' );

			$conn->rollback ();
		}

		$this->getUser ()
			->setFlash ( 'notice', $this->getContext ()
			->getI18N ()
			->__ ( 'Comment week was saved successfully. You can add another one below.' ) );

		$this->redirect ( '@ps_comment_week' );
	}

	// Duyệt bài
	protected function executeBatchPublishComment(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		if (myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' )) {
			$records = Doctrine_Query::create ()->from ( 'PsCommentWeek' )
				->whereIn ( 'id', $ids )
				->execute ();
		} else {
			$records = Doctrine_Query::create ()->from ( 'PsCommentWeek' )
				->whereIn ( 'id', $ids )
				->addWhere ( 'ps_customer_id =?', myUser::getPscustomerID () )
				->execute ();
		}

		foreach ( $records as $record ) {

			$record->setIsActivated ( PreSchool::ACTIVE );

			$record->save ();
		}

		$this->getUser ()
			->setFlash ( 'notice', $this->getContext ()
			->getI18N ()
			->__ ( 'The selected items have been publish comment successfully.' ) );

		$this->redirect ( '@ps_comment_week' );
	}
}
