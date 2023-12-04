<?php
require_once dirname ( __FILE__ ) . '/../lib/psCmsNotificationGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psCmsNotificationGeneratorHelper.class.php';

/**
 * psCmsNotification actions.
 *
 * @package kidsschool.vn
 * @subpackage psCmsNotification
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psCmsNotificationActions extends autoPsCmsNotificationActions {

	public function executeBirthdayNotify(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$this->ps_customer_id = null;

		$this->ps_workplace_id = null;

		$this->school_year_id = null;

		$this->ps_class_id = null;

		$this->track_at = null;

		$this->year_month = (int)date('m');

		$ps_cms_notifications = $request->getParameter ( 'birthday_notify' );

		if ($request->isMethod ( 'post' )) {

			// Handle the form submission
			$value_student_filter = $ps_cms_notifications;

			$this->ps_customer_id = $value_student_filter ['ps_customer_id'];

			$this->ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$this->school_year_id = $value_student_filter ['school_year_id'];

			$this->ps_class_id = $value_student_filter ['ps_class_id'];

			$this->year_month = $value_student_filter ['year_month'];

			$day = date ( 'd' );
			$year = date ( 'Y' );
			$month_birthday = $day . '-' . $this->year_month . '-' . $year;

			$this->relatives_list = Doctrine::getTable ( 'Relative' )->getRelativesBirthday ( $this->ps_customer_id, $this->ps_workplace_id, $this->ps_class_id, $month_birthday );

			$relative_id = array_column ( $this->relatives_list->toArray (), 'id' );

			$this->students = array ();

			if (count ( $relative_id ) > 0) {
				$student = Doctrine::getTable ( 'RelativeStudent' )->sqlGetStudentByRelativeId ( $relative_id )
					->fetchArray ();

				foreach ( $student as $student ) {
					$this->students [$student ['relative_id']] [] = "{$student['student_name']}    ({$student['mc_name']})";
				}
			}

			$this->students_list = Doctrine::getTable ( 'StudentClass' )->getStudentsBirthdayOfMonth ( $this->ps_customer_id, $this->ps_workplace_id, $this->ps_class_id, $month_birthday );

			$this->teachers_list = Doctrine::getTable ( 'PsMember' )->getTeachersBirthday ( $this->ps_customer_id, $this->ps_workplace_id, $this->ps_class_id, $month_birthday );
		
		}else{
			
			$month_birthday = date('d-m-Y');
			
			$this->ps_customer_id = myUser::getPscustomerID ();
			$member_id = myUser::getUser ()->getMemberId ();
			$this->ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
			
			$this->relatives_list = Doctrine::getTable ( 'Relative' )->getRelativesBirthday ( $this->ps_customer_id, $this->ps_workplace_id, $this->ps_class_id, $month_birthday );
			
			$relative_id = array_column ( $this->relatives_list->toArray (), 'id' );
			
			$this->students = array ();
			
			if (count ( $relative_id ) > 0) {
				$student = Doctrine::getTable ( 'RelativeStudent' )->sqlGetStudentByRelativeId ( $relative_id )
				->fetchArray ();
				
				foreach ( $student as $student ) {
					$this->students [$student ['relative_id']] [] = "{$student['student_name']}    ({$student['mc_name']})";
				}
			}
			
			$this->students_list = Doctrine::getTable ( 'StudentClass' )->getStudentsBirthdayOfMonth ( $this->ps_customer_id, $this->ps_workplace_id, $this->ps_class_id, $month_birthday );
			
			$this->teachers_list = Doctrine::getTable ( 'PsMember' )->getTeachersBirthday ( $this->ps_customer_id, $this->ps_workplace_id, $this->ps_class_id, $month_birthday );
			
		}

		$this->formFilter->setWidget ( 'year_month', new sfWidgetFormChoice ( array (
				'choices' => PreSchool::loadPsMonth () ), array (
				'class' => 'select2',
				'style' => "min-width:120px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) ) );

		$this->formFilter->setValidator ( 'year_month', new sfValidatorChoice ( array (
				'choices' => PreSchool::loadPsMonth (),
				'required' => true ) ) );

		if ($ps_cms_notifications) {

			$this->school_year_id = ($ps_cms_notifications ['school_year_id']);

			$this->ps_workplace_id = ($ps_cms_notifications ['ps_workplace_id']);

			$this->ps_class_id = ($ps_cms_notifications ['ps_class_id']);

			$this->year_month = ($ps_cms_notifications ['year_month']);

			if ($this->ps_workplace_id > 0) {

				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_CMS_NOTIFICATIONS_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}
		
		if (! myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_FILTER_SCHOOL', 'PS_CMS_NOTIFICATIONS_ALL' )) {

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

		if ($this->ps_customer_id == '') {
			$this->ps_customer_id = myUser::getPscustomerID ();
			$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$this->ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		if ($this->school_year_id == '') {
			$this->school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}

		$this->formFilter->setDefault ( 'school_year_id', $this->school_year_id );

		$this->formFilter->setWidget ( 'school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => true ), array (
				'class' => 'select2',
				'style' => "width:100%;min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ),
				'required' => true ) ) );

		$this->formFilter->setValidator ( 'school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'column' => 'id',
				'required' => true ) ) );

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
		} else {
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );
		}

		$this->formFilter->setWidget ( 'ps_class_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'MyClass',
				'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
						'ps_school_year_id' => $this->school_year_id,
						'ps_customer_id' => $this->ps_customer_id,
						'ps_workplace_id' => $this->ps_workplace_id,
						'is_activated' => PreSchool::ACTIVE ) ),
				'add_empty' => _ ( '-Select class-' ) ), array (
				'class' => 'select2',
				'style' => "width:100%;min-width:150px;",
				'data-placeholder' => _ ( '-Select class-' ),
				'required' => false ) ) );

		$this->formFilter->setValidator ( 'ps_class_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'MyClass',
				'column' => 'id',
				'required' => false ) ) );

		$this->formFilter->setDefault ( 'school_year_id', $this->school_year_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'ps_class_id', $this->ps_class_id );

		$this->formFilter->setDefault ( 'year_month', $this->year_month );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'birthday_notify[%s]' );

	}

	// Xuat danh sach thong ke hoc sinh da kham theo tung dot kham
	public function executeExportByClassOfMonth(sfWebRequest $request) {

		$ps_customer_id = $request->getParameter ( 'cid' );

		$ps_workplace_id = $request->getParameter ( 'wid' );

		$ps_class_id = $request->getParameter ( 'clid' );

		$month = $request->getParameter ( 'date' ); // Tháng sinh nhật

		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {
			if (myUser::getPscustomerID () != $ps_customer_id) {
				$this->forward404Unless ( sprintf ( 'Object does not exist.' ) );
			}
		}

		$this->exportReportBirthdayOfClass ( $ps_customer_id, $ps_workplace_id, $ps_class_id, $month );

		$this->redirect ( '@ps_cms_notifications_ps_cms_notification_birthday_notify' );
	}

	// Xuat danh sach thong ke hoc sinh da kham theo tung dot kham
	protected function exportReportBirthdayOfClass($ps_customer_id, $ps_workplace_id, $ps_class_id, $month) {

		$exportFile = new ExportStudentLogtimesReportHelper ( $this );

		$file_template_pb = 'tksn_sinhnhatcualop_00001.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/pschool/template_export/' . $file_template_pb;

		$school_name = Doctrine::getTable ( 'Pscustomer' )->findOneBy ( 'id', $ps_customer_id );

		$month_birthday = date ( 'd' ) . '-' . $month . '-' . date ( 'Y' );

		$relatives_list = Doctrine::getTable ( 'Relative' )->getRelativesBirthday ( $ps_customer_id, $ps_workplace_id, $ps_class_id, $month_birthday );

		$students_list = Doctrine::getTable ( 'StudentClass' )->getStudentsBirthdayOfMonth ( $ps_customer_id, $ps_workplace_id, $ps_class_id, $month_birthday );

		$teachers_list = Doctrine::getTable ( 'PsMember' )->getTeachersBirthday ( $ps_customer_id, $ps_workplace_id, $ps_class_id, $month_birthday );

		$relative_id = array_column ( $relatives_list->toArray (), 'id' );

		$students = array ();

		if (count ( $relative_id ) > 0) {
			$student = Doctrine::getTable ( 'RelativeStudent' )->sqlGetStudentByRelativeId ( $relative_id )
				->fetchArray ();

			foreach ( $student as $student ) {
				$students [$student ['relative_id']] [] = "{$student['student_name']}    ({$student['mc_name']})";
			}
		}

		$exportFile->loadTemplate ( $path_template_file );

		$title_info = $this->getContext ()
			->getI18N ()
			->__ ( 'Statistic birthday of month' ) . $month;

		$title_xls = 'DanhSachSinhNhat_Thang_' . $month;

		$exportFile->setDataExportStatisticInfoExport ( $school_name, $title_info, $title_xls );

		$exportFile->setDataExportStatisticBirthday ( $relatives_list, $students_list, $teachers_list, $students );

		$exportFile->saveAsFile ( "DanhSachSinhNhat" . ".xls" );
	}

	// load ajax
	public function executeLoadAjax(sfWebRequest $request) {

		$school_id = $request->getParameter ( 'y_id' );

		$customer_id = $request->getParameter ( 'c_id' );

		$workplace_id = $request->getParameter ( 'w_id' );

		$my_class = Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
				'ps_school_year_id' => $school_id,
				'ps_customer_id' => $customer_id,
				'ps_workplace_id' => $workplace_id,
				'is_activated' => PreSchool::ACTIVE ) )
			->execute ();

		return $this->renderPartial ( 'psCmsNotification/table_list_class', array (
				'my_class' => $my_class ) );
	}

	public function executeIndex(sfWebRequest $request) {

		// get values filter
		$this->type = $request->getParameter ( 'type' );
		if ($this->type) {
			$this->setFilters ( array (
					'type' => $this->type ) );
		} else
			$this->type = $this->filter_value ['type'];

		$this->filter_value = $this->getFilters ();

		// sorting
		if ($request->getParameter ( 'sort' ) && $this->isValidSortColumn ( $request->getParameter ( 'sort' ) )) {
			$this->setSort ( array (
					$request->getParameter ( 'sort' ),
					$request->getParameter ( 'sort_type' ) ) );
		}

		// pager
		if ($request->getParameter ( 'page' )) {
			$this->setPage ( $request->getParameter ( 'page' ) );
		}

		$this->pager = $this->getPager ();
		$this->sort = $this->getSort ();
	}

	public function executeFilter(sfWebRequest $request) {

		$this->setPage ( 1 );

		if ($request->hasParameter ( '_reset' )) {
			$this->setFilters ( $this->configuration->getFilterDefaults () );

			$this->redirect ( '@ps_cms_notifications_ps_cms_notification' );
		}

		$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

		$this->filters->bind ( $request->getParameter ( $this->filters->getName () ) );
		if ($this->filters->isValid ()) {
			$this->setFilters ( $this->filters->getValues () );

			$this->redirect ( '@ps_cms_notifications_ps_cms_notification' );
		}

		$this->pager = $this->getPager ();
		$this->sort = $this->getSort ();

		$this->setTemplate ( 'index' );
	}

	public function executeDetail(sfWebRequest $request) {

		$this->filter_value = $this->getFilters ();

		$notification_id = $request->getParameter ( 'id' );

		if ($notification_id <= 0) {

			$this->forward404Unless ( $notification_id, sprintf ( 'Object does not exist.' ) );
		}
		// lay thong tin thong bao
		$this->notification = Doctrine::getTable ( 'PsCmsNotifications' )->getNotificationById ( $notification_id );
		if ($this->filter_value ['type'] == 'received') {
			// chuyen tin chua doc thanh da doc
			$ps_cms_received_notification = Doctrine::getTable ( 'PsCmsReceivedNotification' )->getReceivedNotificationByNotificationId ( $notification_id, myUser::getUserId () );

			$this->isread = $ps_cms_received_notification->getIsRead ();

			if ($ps_cms_received_notification && $ps_cms_received_notification->getIsRead () == 0) {
				$ps_cms_received_notification->setIsRead ( '1' );
				$ps_cms_received_notification->save ();
			}
		}
	}

	public function executeNew2(sfWebRequest $request) {

		$this->form = new PsCmsNotificationForm ();

		$this->ps_cms_notifications = $this->form->getObject ();
	}

	public function executeCreate(sfWebRequest $request) {

		$this->form = new PsCmsNotificationForm ();

		$this->ps_cms_notifications = $this->form->getObject ();

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'new' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->filter_value = $this->getFilters ();

		$notification_id = $this->getRoute ()->getObject ()->getId ();

		$ps_customer_id = $this->getRoute ()->getObject ()->getPsCustomerId ();
		
		$user_sent = $this->getRoute ()->getObject ()->getUserCreatedId ();

		$error_noti = $this->getContext ()->getI18N ()->__ ( 'Delete notification error.' );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()->getObject () ) ) );

		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_FILTER_SCHOOL' )) {
			if ($ps_customer_id != myUser::getPscustomerID ()) {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}
		}
		
		/*
		 * Quyen cua nguoi quan ly
		 * neu co quyen xoa thì se xoa đc tat ca thong bao cho ng nhan, con khong thi se chi xoa đc cua minh
		 */

		if (myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_DELETE' )) {
			// neu thong bao nhan hoac gui, chuyen vao thung rac
			if ($this->filter_value ['type'] == 'received' || $this->filter_value ['type'] == 'sent') {

				$received = Doctrine::getTable ( 'PsCmsReceivedNotification' )->getReceivedNotificationByNotificationId ( $notification_id, myUser::getUserId () );

				if ($received) {
					$received->setIsDelete ( 1 );
					$received->save ();
				}

				$notification = $this->getRoute ()->getObject ();
				$notification->setIsStatus ( 'trash' );
				$notification->save ();
				
			} else {
				
			    Doctrine::getTable('PsCmsReceivedNotification')->getCheckNotificationByNotificationId($notification_id)->delete();
				
				Doctrine::getTable('PsCmsNotificationsClass')->getCheckNotificationClassByNotificationId($notification_id)->delete();
				
				$this->getRoute ()->getObject ()->delete ();
			}

			$this->getUser ()->setFlash ( 'notice', 'The item was deleted successfully.' );
			
		} else {

			// neu thong bao nhan hoac gui, chuyen vao thung rac
			if ($this->filter_value ['type'] == 'received') {
				$received = Doctrine::getTable ( 'PsCmsReceivedNotification' )->getReceivedNotificationByNotificationId ( $notification_id, myUser::getUserId () );
				if ($received) {
					$received->setIsDelete ( 1 );
					$received->save ();
					$this->getUser ()
						->setFlash ( 'notice', 'The item was deleted successfully.' );
				} else {
					$this->getUser ()
						->setFlash ( 'error', $error_noti );
				}
			} elseif ($this->filter_value ['type'] == 'sent') {

				$received = Doctrine::getTable ( 'PsCmsReceivedNotification' )->getReceivedNotificationByNotificationId ( $notification_id, myUser::getUserId () );
				if ($received) {
					$received->setIsDelete ( 1 );
					$received->save ();
				}
				// neu thong bao la do nguoi gui thi chuyen thu vao thung rac, khong thi thoi
				if ($user_sent == $user_id) {
					$notification = $this->getRoute ()
						->getObject ();
					$notification->setIsStatus ( 'trash' );
					$notification->save ();
					$this->getUser ()
						->setFlash ( 'notice', 'The item was deleted successfully.' );
				} else {
					$this->getUser ()
						->setFlash ( 'error', $error_noti );
				}
			} else {

				$received = Doctrine_Query::create ()->from ( 'PsCmsReceivedNotification' )
					->whereIn ( 'ps_cms_notification_id', $notification_id )
					->whereIn ( 'user_id', myUser::getUserId () )
					->fetchOne ();

				if ($received) {
					$received->delete ();
					$this->getUser ()
						->setFlash ( 'notice', 'The item was deleted successfully.' );
				} else {
					$this->getUser ()
						->setFlash ( 'error', $error_noti );
				}
			}
		}

		$this->redirect ( '@ps_cms_notifications_ps_cms_notification' );
	}

	public function executeBatch(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		if (! $ids = $request->getParameter ( 'ids' )) {
			$this->getUser ()
				->setFlash ( 'error', 'You must at least select one item.' );

			$this->redirect ( '@ps_cms_notifications_ps_cms_notification' );
		}

		if (! $action = $request->getParameter ( 'batch_action' )) {
			$this->getUser ()
				->setFlash ( 'error', 'You must select an action to execute on the selected items.' );

			$this->redirect ( '@ps_cms_notifications_ps_cms_notification' );
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
				'model' => 'PsCmsNotifications' ) );
		try {
			// validate ids
			$ids = $validator->clean ( $ids );

			// execute batch
			$this->$method ( $request );
		} catch ( sfValidatorError $e ) {
			$this->getUser ()
				->setFlash ( 'error', 'A problem occurs when deleting the selected items as some items do not exist anymore.' );
		}

		$this->redirect ( '@ps_cms_notifications_ps_cms_notification' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );
		
		$error_noti = $this->getContext ()->getI18N ()->__ ( 'Delete notification error.' );
		$warning_noti = $this->getContext ()->getI18N ()->__ ( 'You deleted just sent notification.' );
		$number_error = 0;
		$number_success = 0;
		
		$user_id = myUser::getUserId ();
		
		$ps_customer = Doctrine::getTable ( 'sfGuardUser' )->getsfGuardUserByField ( $user_id,'ps_customer_id' );
		// Kiem tra nguoi dung co quyen loc truong hay khong
		$this->forward404Unless ( myUser::checkAccessObject ( $ps_customer, 'PS_CMS_NOTIFICATIONS_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$conn = Doctrine_Manager::connection ();
		
		try {
			
			$conn->beginTransaction ();
			
			$records = Doctrine_Query::create ()->from ( 'PsCmsNotifications' )->whereIn ( 'id', $ids )->execute ();
			$this->filter_value = $this->getFilters ();
			// echo $this->filter_value['type']; die;
			foreach ( $records as $record ) {
				$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
						'object' => $record ) ) );
				/*
				 * Quyen cua nguoi quan ly
				 * neu co quyen xoa thì se xoa đc tat ca thong bao cho ng nhan, con khong thi se chi xoa đc cua minh
				 */
				if (myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_DELETE' )) {
					// neu thong bao nhan hoac gui thì chuyen vao thung rac
					if ($this->filter_value ['type'] == 'received' || $this->filter_value ['type'] == 'sent') {
	
						$received = Doctrine::getTable ( 'PsCmsReceivedNotification' )->getReceivedNotificationByNotificationId ( $record->getId (), $user_id );
						if ($received) {
							$received->setIsDelete ( 1 );
							$received->save ();
						}
						$notification = $record;
						$notification->setIsStatus ( 'trash' );
						$notification->save ();
					} else { // neu dang o thung rac thi xoa cac ban ghi co ng nhan
						
					    Doctrine::getTable('PsCmsReceivedNotification')->getCheckNotificationByNotificationId($record->getId ())->delete();
					    
					    Doctrine::getTable('PsCmsNotificationsClass')->getCheckNotificationClassByNotificationId($record->getId ())->delete();
// 					    
						$record->delete ();
						
					}
				} else { // Chi co quyen xoa thong bao ca nhan
					// neu thong bao den -> chuyen vao thung rac
					if ($this->filter_value ['type'] == 'received') {
						$received = Doctrine::getTable ( 'PsCmsReceivedNotification' )->getReceivedNotificationByNotificationId ( $record->getId (), $user_id );
	
						if ($received) {
							$received->setIsDelete ( 1 );
							$received->save ();
							$number_success ++;
						} else {
							$number_error ++;
						}
					} elseif ($this->filter_value ['type'] == 'sent') { // Trang thai la gui -> chuyen vao thung rac
						$user_sent = $record->getUserCreatedId ();
						$received = Doctrine::getTable ( 'PsCmsReceivedNotification' )->getReceivedNotificationByNotificationId ( $record->getId (), $user_id );
	
						if ($received) {
							$received->setIsDelete ( 1 );
							$received->save ();
						}
						if ($user_sent == $user_id) { // xoa thong bao doi voi nguoi nhan
							$notification = $record;
							$notification->setIsStatus ( 'trash' );
							$notification->save ();
	
							$number_success ++;
						} else {
							$number_error ++;
						}
					} else { // Trang thai thu trong thung rac
						
						//Doctrine::getTable ( 'PsCmsReceivedNotification' )->getListReceivedNotificationByNotificationId ( $record->getId (), $user_id )->delete ();
						$number_success ++;
						
					}
				}
			}
		
			$conn->commit ();
		} catch ( Exception $e ) {
			$conn->rollback ();
			$error_delete = $e->getMessage ();
			$this->getUser ()->setFlash ( 'error', $error_delete );
			$this->redirect ( '@ps_cms_notifications_ps_cms_notification' );
		}
		if ($number_error > 0 && $number_success > 0) {
			$this->getUser ()
				->setFlash ( 'notice', $warning_noti );
		} elseif ($number_error == 0) {
			$this->getUser ()
				->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
		} elseif ($number_success == 0) {
			$this->getUser ()
				->setFlash ( 'error', $error_noti );
		}
		$this->redirect ( '@ps_cms_notifications_ps_cms_notification' );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {

				$ps_customer_id = $form->getValue ( 'ps_customer_id' );

				$ps_workplace_id = $form->getValue ( 'ps_workplace_id' );

				$is_system = $form->getValue ( 'is_system' );

				$is_all = $form->getValue ( 'is_all' );

				$is_object = $form->getValue ( 'is_object' );
				
				$root_screen = $form->getValue ( 'root_screen' );
				
				$is_status = $form->getValue ( 'is_status' );

				$notifications = $request->getParameter ( 'notification' );

				if ($ps_customer_id < 1) {
					$ps_customer_id = myUser::getPscustomerID ();
				}

				$count_class = count ( $notifications );

				$arr_received_id = array ();

				if ($request->hasParameter ( '_save_and_add' )) {

					if ($is_system == 1) { // gui toan bo he thong

						$list_received_id = array ();

						$list_teacher = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotificationByTeacher ( null, null, myUser::getUserId () );
						foreach ( $list_teacher as $teacher ) {
							array_push ( $list_received_id, $teacher );
						}

						$list_relative = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotificationByRelative ( null, null );

						foreach ( $list_relative as $relative ) {
							array_push ( $list_received_id, $relative );
						}
					} elseif ($is_system == 2) { // gui toan bo giao vien trong he thong

						$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotificationByTeacher ( null, null, myUser::getUserId () );
					} elseif ($is_system == 3) { // Gui toan bo phu huynh trong he thong

						$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotificationByRelative ( null, null );
					} elseif ($is_all == 1) {

						// Lay toan bo danh sach user_id trong truong
						$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotificationByCustomer ( $ps_customer_id )
							->execute ();
					} elseif ($is_all == 2) {

						if ($ps_workplace_id == '') {
							$member_id = myUser::getUser ()->getMemberId ();
							$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
						}

						$user_type_m = PreSchool::USER_TYPE_TEACHER;
						$user_type_r = PreSchool::USER_TYPE_RELATIVE;

						$list_received_id = array ();

						// lay danh sach user trong toan co so
						$list_teacher = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotification ( $user_type_m, $ps_customer_id, myUser::getUserId (), null, $ps_workplace_id )
							->execute ();
						foreach ( $list_teacher as $teacher ) {
							array_push ( $list_received_id, $teacher );
						}

						$list_relative = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotification ( $user_type_r, $ps_customer_id, myUser::getUserId (), null, $ps_workplace_id )
							->execute ();

						foreach ( $list_relative as $relative ) {
							array_push ( $list_received_id, $relative );
						}
					} elseif ($is_object == 1) {						
						if ($ps_customer_id > 0 && $ps_workplace_id > 0)							
							$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotificationByTeacher ( $ps_customer_id, $ps_workplace_id, myUser::getUserId () );
						else
							$list_received_id = null;						
					} elseif ($is_object == 2) {
						
						if ($ps_customer_id > 0 && $ps_workplace_id > 0)
							$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getUsersSentNotificationByRelative ( $ps_customer_id, $ps_workplace_id );
						else
							$list_received_id = null;
					} else {

						$teacher_class = $request->getParameter ( 'teacher_class' );
						$relative_class = $request->getParameter ( 'relative_class' );
						$teacher_class = array_unique ( $teacher_class );
						$relative_class = array_unique ( $relative_class );

						if (count ( $teacher_class ) > 0 && count ( $relative_class ) > 0) {
							$arr_received_id = array_merge ( $teacher_class, $relative_class );
						} elseif (count ( $teacher_class ) > 0) {
							$arr_received_id = $teacher_class;
						} elseif (count ( $relative_class ) > 0) {
							$arr_received_id = $relative_class;
						}

						// Lay danh sách user để gửi Notify
						if (count ( $arr_received_id ) > 0) {
							$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getUserNotification ( $arr_received_id );
						} else {
							$list_received_id = array ();
						}
					}

					$check_count = count ( $list_received_id );

					$list_received = array ();

					if ($check_count > 0) {

						$ps_cms_notifications = $form->save ();

						// gui cho danh sach người nhận
						foreach ( $list_received_id as $received_id ) {
							if ($received_id->getId () != myUser::getUserId ()) {
								$ps_cms_received_notification = new PsCmsReceivedNotification ();
								$ps_cms_received_notification->setPsCmsNotificationId ( $ps_cms_notifications->getId () );
								$received_id = $received_id->getId ();
								array_push ( $list_received, $received_id );
								$ps_cms_received_notification->setUserId ( $received_id );
								$ps_cms_received_notification->setDateAt ( $ps_cms_notifications->getDateAt () );
								$ps_cms_received_notification->setIsDelete ( 0 );
								$ps_cms_received_notification->setUserCreatedId ( myUser::getUserId () );
								$ps_cms_received_notification->save ();
							}
						}
						if ($list_received) {
							$str_received_id = implode ( ',', $list_received );
							$ps_cms_notifications->setPsCustomerId ( $ps_customer_id );
							$ps_cms_notifications->setIsStatus ( 'sent' );
							$ps_cms_notifications->setTotalObjectReceived ( count ( $list_received ) );
							$ps_cms_notifications->setTextObjectReceived ( $str_received_id );
							$ps_cms_notifications->save ();
						}

						// gui them mot thong bao cho chinh nguoi gui
						$ps_cms_received_notification = new PsCmsReceivedNotification ();
						$ps_cms_received_notification->setPsCmsNotificationId ( $ps_cms_notifications->getId () );
						$ps_cms_received_notification->setDateAt ( $ps_cms_notifications->getDateAt () );
						$ps_cms_received_notification->setUserId ( myUser::getUserId () );
						$ps_cms_received_notification->setUserCreatedId ( myUser::getUserId () );
						$ps_cms_received_notification->save ();

						if ($count_class > 0) {
							foreach ( $notifications as $notification ) {
								if ($notification > 0) {
									$ps_cms_notifications_class = new PsCmsNotificationsClass ();
									$ps_cms_notifications_class->setPsCustomerId ( $ps_customer_id );
									$ps_cms_notifications_class->setPsNotificationId ( $ps_cms_notifications->getId () );
									$ps_cms_notifications_class->setPsClassId ( $notification );
									$ps_cms_notifications_class->setUserCreatedId ( myUser::getUserId () );
									$ps_cms_notifications_class->save ();
								}
							}
						}

						// gui notification
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

							$setting = new \stdClass ();

							$profile = $this->getUser ()->getGuardUser ()->getProfileShort ();
							
							$setting->title = $ps_cms_notifications->getTitle ();
							$setting->subTitle = $this->getContext ()->getI18N ()->__ ( 'From' ) . ' ' .$profile->getFirstName() . ' ' . $profile->getLastName();

							//$setting->message = PreString::stringTruncate ( PreString::stripTags($ps_cms_notifications->getDescription ()), 100, '...' );
							
							$setting->message = $setting->title;
							
							$setting->tickerText = $this->getContext ()->getI18N ()->__ ( 'From' ) . ' ' . $profile->getFirstName() . ' ' . $profile->getLastName();
							$setting->lights = '1';
							$setting->vibrate = '1';
							$setting->sound = '1';
							$setting->smallIcon = 'ic_small_notification';
							$setting->smallIconOld = 'ic_small_notification_old';

							// Chỗ này cần thay bằng avatar của user gửi hoặc Logo trường

							// Lay avatar nguoi gui
							
							if ($profile && $profile->getAvatar () != '') {

								$url_largeIcon = PreString::getUrlMediaAvatar ( $profile->getCacheData (), $profile->getYearData (), $profile->getAvatar (), '01' );

								$largeIcon = PsFile::urlExists ( $url_largeIcon ) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							} else {
								$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							}

							$setting->largeIcon = $largeIcon;

							$setting->screenCode = PsScreenCode::PS_CONST_SCREEN_CMSNOTIFICATION;
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
								$result = $notification->pushNotification ();
							}
						}
					} else {

						$this->getUser ()
							->setFlash ( 'error', $this->getContext ()
							->getI18N ()
							->__ ( 'The item empty.' ) );
						return sfView::SUCCESS;
					}
				}
			} catch ( Doctrine_Validator_Exception $e ) {

				$errorStack = $form->getObject ()->getErrorStack ();

				$message = get_class ( $form->getObject () ) . ' has ' . count ( $errorStack ) . " field" . (count ( $errorStack ) > 1 ? 's' : null) . " with validation errors: ";
				foreach ( $errorStack as $field => $errors ) {
					$message .= "$field (" . implode ( ", ", $errors ) . "), ";
				}
				$message = trim ( $message, ', ' );

				$this->getUser ()
					->setFlash ( 'error', $message );

				return sfView::SUCCESS;
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $ps_cms_notifications ) ) );

			if ($check_count > 0) {
				$this->getUser ()
					->setFlash ( 'notice', 'Sent successful notification.' );
				$this->redirect ( '@ps_cms_notifications_ps_cms_notification_new' );
			} else {
				$err_text = $this->getContext ()
					->getI18N ()
					->__ ( 'The item empty.' );
				$this->getUser ()
					->setFlash ( 'error', $err_text );
				$this->redirect ( '@ps_cms_notifications_ps_cms_notification_new' );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}

	/**
	 * Hàm chèn người thân cho học sinh *
	 */
	protected function processRalativeStudent($ps_customer_id, $ralative_id, $relation_id) {

		/*
		 * Cập nhật người thân cho học sinh chưa có phụ huynh theo cơ sở
		 * int $ralative_id - id của phụ huynh
		 * int $relation_id - id vai trò
		 */
		// $relation_id = 1; // lấy id vai trò

		// Lấy ra tất cả học sinh theo trường và update phụ huynh cho học sinh
		$all_student = Doctrine::getTable ( 'Student' )->getAllStudentsByCustomerId ( $ps_customer_id );

		$i = 1;
		foreach ( $all_student as $student ) {

			$update_relative_student = Doctrine_Query::create ()->from ( 'RelativeStudent' )
				->where ( 'student_id =?', $student->getId () )
				->fetchOne ();

			if (! $update_relative_student) { // Chưa có quan hệ giữa học sinh và phụ huynh thì thêm mới

				// echo $i.': '.$student->getStudentCode().' '.$student->getFirstName() .' '. $student->getLastName().'<br/>';
				// $i++;

				$relative_student = new RelativeStudent ();

				$relative_student->setStudentId ( $student->getId () );

				$relative_student->setRelationshipId ( $relation_id );

				$relative_student->setRelativeId ( $ralative_id );

				$relative_student->setIsParent ( 1 );

				$relative_student->setIsParentMain ( 1 );

				$relative_student->setIsRole ( 1 );

				$relative_student->setRoleService ( 1 );

				$relative_student->setUserCreatedId ( myUser::getUserId () );

				$relative_student->setUserUpdatedId ( myUser::getUserId () );

				$relative_student->save ();
			}
		}
	}

	/**
	**/
	public function executeNew(sfWebRequest $request) {

		$this->form = new PsCmsNotificationForm ();
		$this->ps_cms_notifications = $this->form->getObject ();
	}
}
