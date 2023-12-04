<?php
require_once dirname ( __FILE__ ) . '/../lib/psStudentsGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psStudentsGeneratorHelper.class.php';

/**
 * psStudents actions
 *
 * @package quanlymamnon.vn
 * @subpackage psStudents
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psStudentsActions extends autoPsStudentsActions {

	// Lay danh sach hoc sinh cua mot lop hoc khong hoc thu 7
	public function executeStudentNotSaturday(sfWebRequest $request) {

		$class_id = $request->getParameter ( 'c_id' );

		if ($class_id <= 0) {
			exit ( 0 );
		} else {

			$this->ps_student = Doctrine::getTable ( 'StudentClass' )->getStudentsNotSaturday ( $class_id );

			return $this->renderPartial ( 'psStudents/option_select_student', array (
					'option_select' => $this->ps_student ) );
		}
	}

	// Lay danh sach hoc sinh cua mot lop hoc
	public function executeStudentByClass(sfWebRequest $request) {

		$class_id = $request->getParameter ( 'c_id' );

		if ($class_id <= 0) {
			exit ( 0 );
		} else {

			$this->ps_student = Doctrine::getTable ( 'StudentClass' )->getStudentsByClassId ( $class_id );

			return $this->renderPartial ( 'psStudents/option_select_student', array ('option_select' => $this->ps_student ) );
		}
	}

	public function executeNew(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->student = $this->form->getObject ();

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {

			$ps_customer_id = $request->getParameter ( 'customer_id' );

			if ($ps_customer_id > 0) {

				$psCustomer = Doctrine::getTable ( 'PsCustomer' )->findOneById ( $ps_customer_id );

				$this->forward404Unless ( $psCustomer, sprintf ( 'Object does not exist .' ) );

				$this->form->setDefault ( 'ps_customer_id', $ps_customer_id );

				$this->form->getObject ()
					->setPsCustomerId ( $ps_customer_id );

				$this->form = $this->configuration->getForm ( $this->form->getObject () );
			}
		}
	}

	public function executeEdit(sfWebRequest $request) {

		$this->student = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->student );

		// Lấy lớp học hiện tại của học sinh
		$this->currentClass = $this->student->getCurrentClassOfStudent ();

		// Lay cac dich vu dang su dung
		$current_date = date ( "Y-m-d" );
		$list_service = $this->student->getServicesStudentUsing ( $current_date );
		//$list_service = Doctrine::getTable ( 'Service' )->getAllServicesOfStudent ( $this->student->getId() );
		
		// Lay cac dich vu da hủy - giá hiển thị theo tai thơi diem huy dich vu
		$list_service_notusing = $this->student->getServicesStudentNotUsing ( $current_date );

		$this->list_service = array ();

		$this->list_service ['list_service'] = $list_service;
		$this->list_service ['list_service_notusing'] = $list_service_notusing;

		// Danh sach nguoi than cua hoc sinh
		$this->list_relative = $this->student->getRelativesOfStudent ();

		// Lay danh sach lop hoc ma hoc sinh tung tham gia
		$this->list_class = $this->student->getAllClassOfStudent ();
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->student = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->student );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		if ($form->isValid ()) {
			$check_new = $form->getObject ()
				->isNew ();

			// $notice = $check_new ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {

				$student = $form->save ();

				if ($check_new) {

					$notice = 'The item was created successfully.';

					$prefix_code = 'KS';

					$renderCode = $prefix_code . PreSchool::renderCode ( "%010s", $student->getId () );

					$student->setStudentCode ( $renderCode );

					$student->save ();
				} else {
					$notice = 'The item was updated successfully.';
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

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $student ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				if (myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {
					$this->redirect ( '@ps_students_new?customer_id=' . $student->getPsCustomerId () );
				} else {
					$this->redirect ( '@ps_students_new' );
				}
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_students_edit',
						'sf_subject' => $student ) );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}

	public function executeDelete(sfWebRequest $request) {

		$student_id = $request->getParameter ( 'id' );

		$student = Doctrine::getTable ( 'Student' )->findOneById ( $student_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		// Check va xoa nguoi than cua hoc sinh
		$check_relative_exist = Doctrine::getTable ( 'RelativeStudent' )->checkRelativeHaveManyStudentNotDelete ( $student_id );

		$relative_id = array ();
		if ($check_relative_exist == 1) {

			$all_relatives = $this->getRoute ()
				->getObject ()
				->getRelativesOfStudent ()
				->toArray ();

			$relative_id = array_merge ( $relative_id, array_column ( $all_relatives, 'relative_id' ) );
		}

		$userId = sfContext::getInstance ()->getUser ()->getGuardUser ()->getId ();

		$date = date ( 'YmdHis' );
		
		if (count ( $relative_id ) > 0) {

			// cap nhat trang thai da bi xoa
			$records = Doctrine_Query::create ()->from ( 'Relative' )
				->update ()
				->set ( 'deleted_at', $date )
				->set ( 'updated_at', $date )
				->set ( 'user_updated_id', $userId )
				->whereIn ( 'id', $relative_id )
				->execute ();

			// Khoa tai khoan phu huynh
			Doctrine_Query::create ()->from ( 'SfGuardUser' )
				->update ()
				->set ( 'is_active', PreSchool::CUSTOMER_LOCK )
				->set ( 'notification_token', 'null' )
				->set ( 'user_updated_id', $userId )
				->set ( 'updated_at', $date )
				->andWhereIn ( 'member_id', $relative_id )
				->addWhere ( 'user_type=?', PreSchool::USER_TYPE_RELATIVE )
				->execute ();

			// End khoa tai khoan phu huynh
		}

		if ($student->getDeletedAt () == '') {

			$student->setDeletedAt ( date ( 'Y-m-d H:i:s' ) );

			$student->setUserUpdatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()->getId () );

			$student->save ();

			$this->getUser ()->setFlash ( 'notice', 'The item was deleted successfully.' );
		} else {

			$this->getUser ()
				->setFlash ( 'notice', $this->getContext ()
				->getI18N ()
				->__ ( 'This student has been deleted.' ) );
		}

		$this->redirect ( '@ps_students' );
	}

	public function executeRestore(sfWebRequest $request) {

		$this->student = $this->getRoute ()->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		if ($this->student && $this->student->getDeletedAt () != '') {

			$this->student->setDeletedAt ( null );
			$this->student->setUserUpdatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()->getId () );

			if ($this->student->save ())$this->getUser ()->setFlash ( 'notice', $this->getContext ()->getI18N ()->__ ( 'This student has been restore.' ) );
		}

		$this->redirect ( '@ps_students' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );
		$relative_id = array ();
		if (myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ))
			$records = Doctrine_Query::create ()->from ( 'Student' )
				->whereIn ( 'id', $ids )
				->execute ();
		else
			$records = Doctrine_Query::create ()->from ( 'Student' )
				->whereIn ( 'id', $ids )
				->andWhere ( 'ps_customer_id =?', myUser::getPscustomerID () )
				->execute ();

		foreach ( $records as $record ) {

			// $this->forward404Unless(myUser::checkAccessObject($record, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL'), sprintf('Object does not exist.'));

			$record->setDeletedAt ( date ( 'Y-m-d H:i:s' ) );

			$check_relative_exist = Doctrine::getTable ( 'RelativeStudent' )->checkRelativeHaveManyStudentNotDelete ( $record->getId () );

			$record->save ();

			// Check va day id nguoi than cua hoc sinh vao mang
			if ($check_relative_exist == 1) {
				$all_relatives = Doctrine::getTable ( 'RelativeStudent' )->findByStudentId ( $record->getId (), $record->getPsCustomerId () )
					->toArray ();

				$relative_id = array_merge ( $relative_id, array_column ( $all_relatives, 'relative_id' ) );

				// End check va day id nguoi than cua hoc sinh vao mang
			}
		}

		$userId = sfContext::getInstance ()->getUser ()
			->getGuardUser ()
			->getId ();
		$date = date ( 'YmdHis' );

		if (count ( $relative_id ) > 0) {
			$records = Doctrine_Query::create ()->from ( 'Relative' )
				->update ()
				->set ( 'deleted_at', $date )
				->set ( 'updated_at', $date )
				->set ( 'user_updated_id', $userId )
				->whereIn ( 'id', $relative_id )
				->execute ();

			// Khoa tai khoan phu huynh

			Doctrine_Query::create ()->from ( 'SfGuardUser' )
				->update ()
				->set ( 'is_active', PreSchool::CUSTOMER_LOCK )
				->set ( 'notification_token', 'null' )
				->set ( 'user_updated_id', $userId )
				->set ( 'updated_at', $date )
				->andWhereIn ( 'member_id', $relative_id )
				->addWhere ( 'user_type=?', PreSchool::USER_TYPE_RELATIVE )
				->execute ();

			// End khoa tai khoan phu huynh
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );

		$this->redirect ( '@ps_students' );
	}

	public function executeDetail(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$student_id = $request->getParameter ( 'id' );

			if ($student_id <= 0) {
				$this->setTemplate('detailError404','psCpanel');
				//$this->forward404Unless ( $student_id, sprintf ( 'Object does not exist.' ) );
			}

			$this->_student = Doctrine::getTable ( 'Student' )->getStudentByID ( $student_id );

			if(!myUser::checkAccessObject ( $this->_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )){
				$this->setTemplate('detailError404','psCpanel');
			}
			
// 			$this->forward404Unless ( myUser::checkAccessObject ( $this->_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->getRoute ()
// 				->getObject ()
// 				->getId () ) );

			// Danh sach dich vu
			// $this->list_service = $this->_student->getServicesOfStudent();
			//$this->list_service = Doctrine::getTable ( 'Service' )->getAllServicesOfStudent ( $student_id );
			
			$current_date = date ("Y-m-d" );
			$this->list_service = $this->_student->getServicesStudentUsing ($current_date);

			// Danh sach nguoi than cua hoc sinh
			$this->list_relative = $this->_student->getRelativesOfStudent ();

			// Lay danh sach lop hoc ma hoc sinh dang hoc
			$this->list_class = $this->_student->getAllClassOfStudent ();
			// $this->list_class = $this->_student->getMyClassByStudent();
			
			// Lay cac dich vu da hủy - giá hiển thị theo tai thơi diem huy dich vu
			$this->list_service_notusing = $this->_student->getServicesStudentNotUsing ($current_date);
			
		} else {
			exit ( 0 );
		}
	}

	public function executeSynthetic(sfWebRequest $request) {

		$this->student_id = $request->getParameter ( 'sid' );

		$date_at = $request->getParameter ( 'date' );

		$this->tabmenu = $request->getParameter ( 'menu' );
		
		if($this->tabmenu == ''){
			$this->tabmenu = 1;
		}
		
		$this->ps_month = date ( 'm-Y', $date_at );

		$this->student = Doctrine::getTable ( 'Student' )->findOneById ( $this->student_id );
		// ? Cần check lại
		
		$this->forward404Unless ( myUser::checkAccessObject ( $this->student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form_filter = new sfForm ();

		$this->year = $request->getParameter ( 'year' );

		$this->month = $request->getParameter ( 'month' );

		if (! $this->year || ! $this->month) {
			if ($date_at) {
				$this->month = date ( 'm', $date_at );
				$this->year = date ( 'Y', $date_at );
			}
		}

		$default_month = $this->month;

		$this->month = ($this->month < 10) ? '0' . ( int ) $this->month : $this->month;

		$tracked_at = '01' . '-' . $this->month . '-' . $this->year;

		$years = range ( date ( 'Y' ), sfConfig::get ( 'app_begin_year' ) );

		$this->form_filter->setWidget ( 'year', new sfWidgetFormChoice ( array (
				'choices' => array_combine ( $years, $years ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px; width:auto;",
				'data-placeholder' => _ ( '-Select year-' ) ) ) );

		$month = range ( 1, 12 );

		$this->form_filter->setWidget ( 'month', new sfWidgetFormChoice ( array (
				'choices' => array_combine ( $month, $month ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px; width:auto;",
				'data-placeholder' => _ ( '-Select month-' ) ) ) );

		$this->form_filter->setDefault ( 'month', ( int ) $default_month );
		$this->form_filter->setDefault ( 'year', $this->year );

		$this->ps_month = $default_month . '-' . $this->year;

		if ($this->student_id > 0) {
			$infoClass = Doctrine::getTable ( "StudentClass" )->getClassActivateByStudent ( $this->student_id );
			$this->class_name = $infoClass->getName ();
			$this->ps_customer_id = $this->student->getPsCustomerId ();
			$ps_workplace_id = $infoClass->getPsWorkplaceId ();
			$this->class_id = $infoClass->getClassId ();

			$this->list_student = Doctrine::getTable ( 'Student' )->getListStudentServiceByClass ( $this->ps_customer_id, $ps_workplace_id, $this->class_id, $this->ps_month )
				->execute ();
			$this->defaultLogout = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $ps_workplace_id ,'config_default_logout')
				->getConfigDefaultLogout ();
		}
	}

	/**
	 * Export data Student
	 */
	public function executeExportStudentData(sfWebRequest $request) {

		$student_filters = $request->getParameter ( 'student_filters' );
		$class_id = $student_filters ['ps_class_id'];
		$workplace_id = $student_filters ['ps_workplace_id'];

		// Neu khong chon lop -> empty($class_id) = 1
		if (empty ( $class_id )) {
			// Neu khong chon co so -> empty($workplace_id) = 1
			if (empty ( $workplace_id )) {
				$this->redirect ( '@ps_students' );
			} else {
				$this->executeExportByWorkPlace ( $request );
			}
		} else {
			$this->executeExportByClass ( $request );
		}
	}

	public function executeExportStudentDataWithRelatives(sfWebRequest $request) {

		$student_filters = $request->getParameter ( 'student_filters' );
		$class_id = $student_filters ['ps_class_id'];
		$workplace_id = $student_filters ['ps_workplace_id'];

		// Neu khong chon lop -> empty($class_id) = 1
		if (empty ( $class_id )) {
			// Neu khong chon co so -> empty($workplace_id) = 1
			if (empty ( $workplace_id )) {
				$this->redirect ( '@ps_students' );
			} else {
				$this->executeExportByWorkPlaceWithRelatives ( $request );
			}
		} else {
			$this->executeExportByClassWithRelatives ( $request );
		}
	}

	/**
	 * Xuat du lieu co ban hoc sinh, nguoi than theo co so
	 */
	public function executeExportByWorkPlaceWithRelatives(sfWebRequest $request) {

		$student_filters = $request->getParameter ( 'student_filters' );

		$workplace_id = $student_filters ['ps_workplace_id'];

		$customer_id = $student_filters ['ps_customer_id'];

		$schoolyear_id = $student_filters ['school_year_id'];

		$school_year = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $schoolyear_id )->toArray ();

		$params = array ();
		$params ['ps_school_year_id'] = $schoolyear_id;
		$params ['ps_customer_id'] = $customer_id;
		$params ['ps_workplace_id'] = $workplace_id;

		$my_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $workplace_id,'ps_customer_id' );

		if (empty ( $my_workplace )) {

			$this->redirect ( '@ps_students' );
		}

		$this->forward404Unless ( myUser::checkAccessObject ( $my_workplace, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		// Lay danh sach lop hoc cua co so hien tai

		$list_class = Doctrine::getTable ( 'MyClass' )->getClassByParams ( $params );

		$_arr_class = array ();

		foreach ( $list_class as $c ) {
			array_push ( $_arr_class, $c->getId () );
		}

		$this->exportReportByClassWithRelatives ( $_arr_class, $school_year ['from_date'], $school_year ['to_date'] );

		$this->redirect ( '@ps_students' );
	}

	/**
	 * Xuat du lieu co ban hoc sinh theo co so
	 */
	public function executeExportByWorkPlace(sfWebRequest $request) {

		$student_filters = $request->getParameter ( 'student_filters' );

		$workplace_id = $student_filters ['ps_workplace_id'];

		$customer_id = $student_filters ['ps_customer_id'];

		$schoolyear_id = $student_filters ['school_year_id'];

		$school_year = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $schoolyear_id )
			->toArray ();

		$params = array ();
		$params ['ps_school_year_id'] = $schoolyear_id;
		$params ['ps_customer_id'] = $customer_id;
		$params ['ps_workplace_id'] = $workplace_id;

		$my_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $workplace_id,'id,ps_customer_id' );
		
		if (empty ( $my_workplace )) {

			$this->redirect ( '@ps_students' );
		}

		$this->forward404Unless ( myUser::checkAccessObject ( $my_workplace, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		// Lay danh sach lop hoc cua co so hien tai

		$list_class = Doctrine::getTable ( 'MyClass' )->getClassByParams ( $params );

		$_arr_class = array ();

		foreach ( $list_class as $c ) {
			array_push ( $_arr_class, $c->getId () );
		}

		$this->exportReport ( $_arr_class, $school_year ['from_date'], $school_year ['to_date'] );

		$this->redirect ( '@ps_students' );
	}

	/**
	 * Xuat du lieu hoc sinh voi nguoi than theo lop
	 */
	public function executeExportByClassWithRelatives(sfWebRequest $request) {

		$student_filters = $request->getParameter ( 'student_filters' );

		$class_id = $student_filters ['ps_class_id'];

		$schoolyear_id = $student_filters ['school_year_id'];

		$school_year = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $schoolyear_id )
			->toArray ();

		if (empty ( $class_id )) {

			$this->redirect ( '@ps_students' );
		}

		$my_classs = Doctrine::getTable ( 'MyClass' )->getMyClassByField ( $class_id,'id,ps_customer_id' );
		
		$this->forward404Unless ( myUser::checkAccessObject ( $my_classs, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		// Lay danh sach hoc sinh cua lop tai thoi diem hien tai
		// $list_student = Doctrine::getTable('StudentClass')->getAllStudentsByClassId($class_id);

		$this->exportReportByClassWithRelatives ( $class_id, $school_year ['from_date'], $school_year ['to_date'] );

		$this->redirect ( '@ps_students' );
	}

	/**
	 * Xuat du lieu co ban cua hoc sinh theo lop
	 */
	public function executeExportByClass(sfWebRequest $request) {

		$student_filters = $request->getParameter ( 'student_filters' );

		$class_id      = $student_filters ['ps_class_id'];

		$schoolyear_id = $student_filters ['school_year_id'];

		$school_year   = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $schoolyear_id )->toArray ();

		if (empty ( $class_id )) {

			$this->redirect ( '@ps_students' );
		}
		
		$my_classs = Doctrine::getTable ( 'MyClass' )->getMyClassByField ( $class_id,'id,ps_customer_id' );

		$this->forward404Unless ( myUser::checkAccessObject ( $my_classs, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		
		$this->exportReport( $class_id, $school_year ['from_date'], $school_year ['to_date'] );
		
		$this->redirect ( '@ps_students' );
	}

	protected function exportReportByClassWithRelatives($class_id, $from_date, $to_date) {

		$exportFile = new ExportStudentReportsHelper ( $this );

		$file_template_pb = 'ds_hs_nt_00001.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/pschool/template_export/' . $file_template_pb;
		// $path_template_file = sfConfig::get ( 'sf_web_dir' ).'/pschool/template_export/'.$file_template_pb;

		$exportFile->loadTemplate ( $path_template_file );

		$title_info = array ();

		$title_info ['title_infor'] = $this->getContext ()
			->getI18N ()
			->__ ( 'STUDENT LIST BY YEAR ' );
		$title_info ['class'] = $this->getContext ()
			->getI18N ()
			->__ ( 'Class' );
		$title_info ['group'] = $this->getContext ()
			->getI18N ()
			->__ ( 'Group' );

		if (count ( $class_id ) == 1) {

			// Lay thong tin truong, giao vien
			$school_info = Doctrine::getTable ( 'MyClass' )->getCustomerInfoByClassId ( $class_id );

			// lay ra giao vien trong lop bị sai
// 			$teacher = Doctrine::getTable ( 'PsTeacherClass' )->getTeachersByClassIdWithTime ( $class_id, $from_date, $to_date );
			$teacher = Doctrine::getTable ( 'PsTeacherClass' )->getTeachersByClassIdWithTime ( $class_id );
			$teacher_info = $this->getContext ()
				->getI18N ()
				->__ ( 'Teacher: ' );

			$_arr_teacher = array ();

			foreach ( $teacher as $t ) {
				array_push ( $_arr_teacher, $t->getFullName () );
			}

			$teacher_info = $teacher_info . implode ( "; ", $_arr_teacher );

			// Lay thong tin lop, nhom
			$class_objGroup = Doctrine::getTable ( 'MyClass' )->getClassObjGroupNameByClassId ( $class_id );

			$data_student = Doctrine::getTable ( 'StudentClass' )->getAllStudentsByClassId ( $class_id );

			$exportFile->setCustomerInfoExportStudents ( $school_info, $title_info );

			$exportFile->setStudentInfoExportStudents ( $teacher_info, $class_objGroup );

			$exportFile->setDataExportStudentsWithRelatives ( $data_student );
		} else {

			$school_info = Doctrine::getTable ( 'MyClass' )->getCustomerInfoByClassId ( $class_id [0] );

// 			$teacher = Doctrine::getTable ( 'PsTeacherClass' )->getTeachersByClassIdWithTime ( $class_id [0], $from_date, $to_date );

			$teacher_info = $this->getContext ()
				->getI18N ()
				->__ ( 'Teacher: ' );

			$number_class = count ( $class_id );

			foreach ( $class_id as $class_id ) {

				// Lay data hoc sinh
				$data_student = Doctrine::getTable ( 'StudentClass' )->getAllStudentsByClassId ( $class_id );

				// if (count($data_student) == 0) {

				// continue;
				// }
				// Lay data giao vien
// 				$teacher = Doctrine::getTable ( 'PsTeacherClass' )->getTeachersByClassIdWithTime ( $class_id, $from_date, $to_date );
				$teacher = Doctrine::getTable ( 'PsTeacherClass' )->getTeachersByClassIdWithTime ( $class_id );

				$_arr_teacher = array ();

				foreach ( $teacher as $t ) {
					array_push ( $_arr_teacher, $t->getFullName () );
				}

				$teacher_info = $teacher_info . implode ( "; ", $_arr_teacher );

				// Lay thong tin lop, nhom
				$class_objGroup = Doctrine::getTable ( 'MyClass' )->getClassObjGroupNameByClassId ( $class_id );

				/**
				 * Clone template
				 */
				$exportFile->createNewSheet ();

				$exportFile->setCustomerInfoExportStudents ( $school_info, $title_info );

				$exportFile->setStudentInfoExportStudents ( $teacher_info, $class_objGroup );

				$teacher_info = $this->getContext ()->getI18N ()->__ ( 'Teacher: ' );

				$exportFile->setDataExportStudentsWithRelatives ( $data_student );
			}
			
			$exportFile->removeSheet ();
		}

		$exportFile->saveAsFile ( "DSHSNT" . date ( 'YmdHi' ) . ".xls" );
	}

	protected function exportReport($class_id, $from_date, $to_date) {
		
			$exportFile = new ExportStudentReportsHelper ( $this );
			
			$file_template_pb = 'ds_hs_00001.xls';
			
			$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/pschool/template_export/' . $file_template_pb;
			
			$exportFile->loadTemplate ( $path_template_file );
			
			$title_info = array ();
			
			$title_info ['title_infor'] = $this->getContext ()->getI18N ()->__ ( 'STUDENT LIST BY YEAR ' );
			$title_info ['class'] = $this->getContext ()->getI18N ()->__ ( 'Class' );
			$title_info ['group'] = $this->getContext ()->getI18N ()->__ ( 'Group' );
			
			if (count ( $class_id ) == 1) {
				
				// Lay thong tin truong, giao vien
				$school_info = Doctrine::getTable ( 'MyClass' )->getCustomerInfoByClassId ( $class_id );
				
// 				$teacher = Doctrine::getTable ( 'PsTeacherClass' )->getTeachersByClassIdWithTime ( $class_id, $from_date, $to_date );
				$teacher = Doctrine::getTable ( 'PsTeacherClass' )->getTeachersByClassIdWithTime ( $class_id );
				
				$teacher_info = $this->getContext ()->getI18N ()->__ ( 'Teacher: ' );
				
				$_arr_teacher = array ();
				
				foreach ( $teacher as $t ) {
					array_push ( $_arr_teacher, $t->getFullName () );
				}
				
				$teacher_info = $teacher_info . implode ( "; ", $_arr_teacher );
				
				// Lay thong tin lop, nhom
				$class_objGroup = Doctrine::getTable ( 'MyClass' )->getClassObjGroupNameByClassId ( $class_id );
				
				$data_student = Doctrine::getTable ( 'StudentClass' )->getAllStudentsByClassId ( $class_id );
				
				$exportFile->setCustomerInfoExportStudents ( $school_info, $title_info );
				
				$exportFile->setStudentInfoExportStudents ( $teacher_info, $class_objGroup );
				
				$exportFile->setDataExportStudents ( $data_student );
				
			} elseif (count ( $class_id ) > 1) {
				
				$school_info = Doctrine::getTable ( 'MyClass' )->getCustomerInfoByClassId ( $class_id [0] );
				
				//$teacher = Doctrine::getTable ( 'PsTeacherClass' )->getTeachersByClassIdWithTime ( $class_id [0], $from_date, $to_date );
				
				$teacher_info = $this->getContext ()->getI18N ()->__ ( 'Teacher: ' );
				
				//$number_class = count ( $class_id );
				
				foreach ( $class_id as $class_id ) {
					
					// Lay data hoc sinh
					$data_student = Doctrine::getTable ( 'StudentClass' )->getAllStudentsByClassId ( $class_id );
					
					// Lay data giao vien
// 					$teacher = Doctrine::getTable ( 'PsTeacherClass' )->getTeachersByClassIdWithTime ( $class_id, $from_date, $to_date );
					$teacher = Doctrine::getTable ( 'PsTeacherClass' )->getTeachersByClassIdWithTime ( $class_id );
					$_arr_teacher = array ();
					
					foreach ( $teacher as $t ) {
						array_push ( $_arr_teacher, $t->getFullName () );
					}
					
					$teacher_info = $teacher_info . implode ( "; ", $_arr_teacher );
					
					// Lay thong tin lop, nhom
					$class_objGroup = Doctrine::getTable ( 'MyClass' )->getClassObjGroupNameByClassId ( $class_id );
					
					/**
					 * Clone template
					 */
					$exportFile->createNewSheet ();
					
					$exportFile->setCustomerInfoExportStudents ( $school_info, $title_info );
					
					$exportFile->setStudentInfoExportStudents ( $teacher_info, $class_objGroup );
					
					$teacher_info = $this->getContext ()->getI18N ()->__ ( 'Teacher: ' );
					
					$exportFile->setDataExportStudents ( $data_student );
			}
			
			$exportFile->removeSheet ();
		}
		
		$exportFile->saveAsFile ( "DSHS" . date ( 'YmdHi' ) . ".xls" );	
	}

	public function executeExportStudentStatisticByWorkplace(sfWebRequest $request) {

		$student_filters = $request->getParameter ( 'student_filters' );

		$workplace_id = $student_filters ['ps_workplace_id'];

		$customer_id = $student_filters ['ps_customer_id'];

		$schoolyear_id = $student_filters ['school_year_id'];

		if (! $workplace_id || ! $customer_id) {

			$this->redirect ( '@ps_students' );
		}

		//$my_customer = Doctrine::getTable ( 'PsCustomer' )->findOneById ( $customer_id );

		//$this->forward404Unless ( myUser::checkAccessObject ( $my_customer, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers('PS_STUDENT_MSTUDENT_FILTER_SCHOOL')) {
			if($customer_id != myUser::getPscustomerID()){
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}
		}
		
		$this->exportReportStudentStatistic ( $schoolyear_id, $customer_id, $workplace_id );

		$this->redirect ( '@ps_students' );
	}

	public function executeExportStudentStatisticByCustomer(sfWebRequest $request) {

		$student_filters = $request->getParameter ( 'student_filters' );

		$customer_id = $student_filters ['ps_customer_id'];

		$schoolyear_id = $student_filters ['school_year_id'];

		if (! $schoolyear_id || ! $customer_id) {

			$this->redirect ( '@ps_students' );
		}
		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers('PS_STUDENT_MSTUDENT_FILTER_SCHOOL')) {
			if($customer_id != myUser::getPscustomerID()){
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}
		}
		//$my_customer = Doctrine::getTable ( 'PsCustomer' )->findOneById ( $customer_id );

		//$this->forward404Unless ( myUser::checkAccessObject ( $my_customer, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->exportReportStudentStatistic ( $schoolyear_id, $customer_id );

		$this->redirect ( '@ps_students' );
	}

	protected function exportReportStudentStatistic($school_year_id, $customer_id, $workplace_id = null) {

		$exportFile = new ExportStudentReportsHelper ( $this );

		$file_template_pb = 'tk_truong_00001.xls';

		$path_template_file = sfConfig::get ( 'app_ps_data_dir' ) . '/template_export/' . $file_template_pb;

		$exportFile->loadTemplate ( $path_template_file );

		$class = array ();

		$is_export_workplace = ($workplace_id == null) ? false : true;

		if (! $workplace_id) {

			$workplace = Doctrine::getTable ( 'PsWorkplaces' )->getWorkPlacesByCustomerId ( $customer_id )
				->toArray ();

			$workplace_id = array ();

			$params = array ();
			$class_index = 0;
			foreach ( $workplace as $wp ) {

				$params ['ps_school_year_id'] = $school_year_id;
				$params ['ps_customer_id'] = $customer_id;
				$params ['ps_workplace_id'] = $wp ['id'];

				array_push ( $workplace_id, $wp ['id'] );

				$class_obj = Doctrine::getTable ( 'MyClass' )->setClassByParams ( $params )
					->fetchArray ();
				$number_class = count ( $class_obj );

				foreach ( $class_obj as $key => $cl ) {
					$class [$cl ['id']] = array (
							'workplace_id' => $wp ['id'],
							'workplace_name' => $wp ['title'],
							'number_class' => $number_class,
							'class_id' => $cl ['id'],
							'class_name' => $cl ['name'],
							'code' => $cl ['code'],
							'teacher' => null,
							'class_offical' => 0,
							'class_graduation' => 0,
							'class_pause' => 0,
							'class_stop_studying' => 0,
							'class_test' => 0 );
				}

				if ($number_class == 0) {
					$class_index --;

					$class [$class_index] = array (
							'workplace_id' => $wp ['id'],
							'workplace_name' => $wp ['title'],
							'number_class' => 0,
							'class_id' => null,
							'class_name' => null,
							'code' => null,
							'teacher' => null,
							'class_offical' => 0,
							'class_graduation' => 0,
							'class_pause' => 0,
							'class_stop_studying' => 0,
							'class_test' => 0 );
				}
			}
		} else {
			$params = array ();
			$params ['ps_school_year_id'] = $school_year_id;
			$params ['ps_customer_id'] = $customer_id;
			$params ['ps_workplace_id'] = $workplace_id;
			$workplace = Doctrine::getTable ( 'PsWorkplaces' )->getColumnWorkPlaceById ( $workplace_id ,'title')
				->toArray ();
			$class_obj = Doctrine::getTable ( 'MyClass' )->setClassByParams ( $params )
				->fetchArray ();

			$number_class = count ( $class_obj );
			$class_index = 0;
			foreach ( $class_obj as $key => $cl ) {
				$class [$cl ['id']] = array (
						'workplace_id' => $workplace_id,
						'workplace_name' => $workplace ['title'],
						'number_class' => $number_class,
						'class_id' => $cl ['id'],
						'class_name' => $cl ['name'],
						'code' => $cl ['code'],
						'teacher' => null,
						'class_offical' => 0,
						'class_graduation' => 0,
						'class_pause' => 0,
						'class_stop_studying' => 0,
						'class_test' => 0 );
			}

			if ($number_class == 0) {
				$class_index --;

				$class [$class_index] = array (
						'workplace_id' => $workplace_id,
						'workplace_name' => $workplace ['title'],
						'number_class' => 0,
						'class_id' => null,
						'class_name' => null,
						'code' => null,
						'teacher' => null,
						'class_offical' => 0,
						'class_graduation' => 0,
						'class_pause' => 0,
						'class_stop_studying' => 0,
						'class_test' => 0 );
			}
		}

		$data = Doctrine::getTable ( 'StudentClass' )->getCountAllStudentStatusBySchoolyearIdAndCustomerId ( $school_year_id, $customer_id, $workplace_id, array_column ( $class, 'class_id' ) );

		foreach ( $data as $key => $dt ) {

			if ($dt ['type'] == PreSchool::SC_STATUS_OFFICIAL) {

				$class [$dt ['class_id']] ['class_offical'] = $dt ['number'];
			} elseif ($dt ['type'] == PreSchool::SC_STATUS_GRADUATION) {

				$class [$dt ['class_id']] ['class_graduation'] = $dt ['number'];
			} elseif ($dt ['type'] == PreSchool::SC_STATUS_PAUSE) {

				$class [$dt ['class_id']] ['class_pause'] = $dt ['number'];
			} elseif ($dt ['type'] == PreSchool::SC_STATUS_STOP_STUDYING) {

				$class [$dt ['class_id']] ['class_stop_studying'] = $dt ['number'];
			} elseif ($dt ['type'] == PreSchool::SC_STATUS_TEST) {

				$class [$dt ['class_id']] ['class_test'] = $dt ['number'];
			}
		}

		// Thong tin truong khi bao cao
		$my_customer = Doctrine::getTable ( 'PsCustomer' )->getCustomerById ( $customer_id );
		$school_year = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $school_year_id )
			->toArray ();

		$exportFile->setCustomerInfoExportStudentsStatistic ( $school_year, $my_customer );

		$teacher = Doctrine::getTable ( 'PsTeacherClass' )->getTeachersByClassIdWithTime ( array_column ( $class, 'class_id' ), $school_year ['from_date'], $school_year ['to_date'] )
			->toArray ();

		$temp = array ();
		foreach ( $teacher as $val ) {
			$temp [$val ['class_id']] [] = $val ['full_name'];
		}

		unset ( $teacher );
		foreach ( $temp as $key => $value ) {
			// merge values, fetch unique, then merge again
			$values = implode ( ',', array_unique ( explode ( ',', implode ( ',', $value ) ) ) );

			$class [$key] ['teacher'] = $values; // store
		}

		unset ( $temp );
		unset ( $workplace );
		foreach ( $class as $key => $cl ) {
			// if($cl['class_id'] > 0)
			{
				if (isset ( $workplace [$cl ['workplace_id']] )) {
					$workplace [$cl ['workplace_id']] ['total_class'] = $cl ['number_class'];
					$workplace [$cl ['workplace_id']] ['total_student'] += $cl ['class_offical'] + $cl ['class_graduation'] + $cl ['class_pause'] + $cl ['class_stop_studying'] + $cl ['class_test'];
					$workplace [$cl ['workplace_id']] ['workplace_offical'] += $cl ['class_offical'];
					$workplace [$cl ['workplace_id']] ['workplace_graduation'] += $cl ['class_graduation'];
					$workplace [$cl ['workplace_id']] ['workplace_pause'] += $cl ['class_pause'];
					$workplace [$cl ['workplace_id']] ['workplace_stop_studying'] += $cl ['class_stop_studying'];
					$workplace [$cl ['workplace_id']] ['workplace_test'] += $cl ['class_test'];
				} else {
					$workplace [$cl ['workplace_id']] ['total_class'] = $cl ['number_class'];
					$workplace [$cl ['workplace_id']] ['total_student'] = $cl ['class_offical'] + $cl ['class_graduation'] + $cl ['class_pause'] + $cl ['class_stop_studying'] + $cl ['class_test'];
					$workplace [$cl ['workplace_id']] ['workplace_offical'] = $cl ['class_offical'];
					$workplace [$cl ['workplace_id']] ['workplace_graduation'] = $cl ['class_graduation'];
					$workplace [$cl ['workplace_id']] ['workplace_pause'] = $cl ['class_pause'];
					$workplace [$cl ['workplace_id']] ['workplace_stop_studying'] = $cl ['class_stop_studying'];
					$workplace [$cl ['workplace_id']] ['workplace_test'] = $cl ['class_test'];
				}
			}
		}

		$exportFile->setDataExportStudentStatistic ( $workplace, $class, $is_export_workplace );

		$exportFile->saveAsFile ( "TKSLHS" . date ( 'YmdHi' ) . ".xls" );
	}
	
	public function executeSyntheticExport(sfWebRequest $request) {
		
		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = null;
		
		$ps_workplace_id = null;
		
		$class_id = null;
		
		$ps_school_year_id = null;
		
		$type_export = 0;
		
		$export_filter = $request->getParameter ( 'export_filter' );
		
		if ($request->isMethod ( 'post' )) {
			
			$value_student_filter = $export_filter;
			
			$ps_customer_id = $value_student_filter ['ps_customer_id'];
			
			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];
			
			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];
			
			$class_id = $value_student_filter ['class_id'];
			
			$type_export = $value_student_filter ['type_export'];
			
			$this->exportSoTheoDoiHocSinh ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id, $class_id, $type_export );
		}
		/*
		if($export_filter){
			
			$ps_school_year_id = isset ( $export_filter ['ps_school_year_id'] ) ? $export_filter ['ps_school_year_id'] : 0;
			
			$ps_workplace_id = isset ( $export_filter ['ps_workplace_id'] ) ? $export_filter ['ps_workplace_id'] : 0;
			
			$class_id = isset ( $export_filter ['class_id'] ) ? $export_filter ['class_id'] : 0;
			
			$type_export = isset ( $export_filter ['type_export'] ) ? $export_filter ['type_export'] : 0;
			
			if ($ps_workplace_id > 0) {
				
				$this->forward404Unless ( $ps_workplace_id, sprintf ( 'Object does not exist.' ) );
				
				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $ps_workplace_id );
				
				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
				
				$ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
			
		}
		*/
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {
			
			$ps_customer_id = myUser::getPscustomerID ();
			
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
		
		if ($ps_customer_id == '') {
			$ps_customer_id = myUser::getPscustomerID ();
			$member_id = myUser::getUser ()->getMemberId ();
			$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}
		
		if ($ps_school_year_id == '') {
			$ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId ();
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
		
		if ($ps_customer_id > 0) {
			
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
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
									'required' => false,
									'data-placeholder' => _ ( '-Select workplace-' ) ) ) );
			
			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );
		}
		
		if ($ps_workplace_id > 0) {
			
			// Filters by class
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id,
							'ps_school_year_id' => $ps_school_year_id,
							'is_activated' => PreSchool::ACTIVE
					) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
							'class' => 'select2',
							'style' => "min-width:150px;",
							'required' => true,
							'data-placeholder' => _ ( '-Select class-' ) ) ) );
			
			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => true ) ) );
		} else {
			
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
									'class' => 'select2',
									'style' => "min-width:200px;",
									'required' => true,
									'data-placeholder' => _ ( '-Select class-' ) ) ) );
			
			$this->formFilter->setValidator ( 'class_id', new sfValidatorPass () );
		}
		
		$this->formFilter->setWidget ('type_export', new sfWidgetFormSelect ( array (
				'choices' => array (
						'0' => _('Export students'),
						'1' => _('Export logtimes'),
						'2' => _('Export examination'),
						'3' => _('Export evaluate')
				)
		),array('class' => 'select2','style' => "min-width:170px;width:100%;",) ) );
		
		$this->formFilter->setValidator ('type_export', new sfValidatorPass ( array (
				'required' => false
		) ) );
		
		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
		
		$this->formFilter->setDefault ( 'ps_school_year_id', $ps_school_year_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		
		$this->formFilter->setDefault ( 'class_id', $class_id );
		
		$this->formFilter->setDefault ( 'type_export', $type_export );
		
		$this->formFilter->getWidgetSchema () ->setNameFormat ( 'export_filter[%s]' );
		
	}
	
	protected function exportSoTheoDoiHocSinh($ps_school_year_id, $ps_customer_id, $ps_workplace_id, $class_id, $type_export) {
		
		$exportFile = new ExportStudentReportsHelper ( $this );
		
		if($type_export == 1){
			$file_template_pb = 'tkhs_sotheodoitre01.xls';
		}elseif($type_export == 2){
			$file_template_pb = 'tkhs_sotheodoitre02.xls';
		}elseif($type_export == 3){
			$file_template_pb = 'tkhs_sotheodoitre03.xls';
		}else{
			$file_template_pb = 'tkhs_sotheodoitre.xls';
		}
		
		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/uploads/export_data/' . $file_template_pb;
		
		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {
			if ($ps_customer_id != myUser::getPscustomerID ()) {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}
		}
		
		// lay tat ca hoc sinh trong lop - Xem lai phan lay du lieu
		$list_student = Doctrine::getTable ( 'Student' )->getAllStudentsByCustomerId ( $ps_customer_id, $ps_workplace_id, $class_id );
		
		$exportFile->loadTemplate ( $path_template_file );
		
		$exportFile->setActiveSheetIndex(0);
		
		if($type_export == 1){
			
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'id', $ps_school_year_id );
			
			$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );
			
			$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );
			
			$array_month = PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd );
			
			// Du lieu diem danh - Xem lai phan lay du lieu (Lay tat ca hoc sinh trong lop)
			$list_logtimes = Doctrine::getTable ( 'PsLogtimes' )->layTatCaDuLieuDiemDanhTrongNamHoc ( $class_id,$schoolYearsDefault->getFromDate (),$schoolYearsDefault->getToDate () );
			
			$exportFile->xuatSoDiemDanhTheoThang ( $list_student, $array_month, $list_logtimes );
			
			$exportFile->saveAsFile ( "DuLieuDiemDanh.xls" );
			
		}elseif($type_export == 2){
			// lop thuoc nhom tre nao
			$ps_obj_group_id = Doctrine::getTable ( 'MyClass' )->getMyClassByField($class_id,'ps_obj_group_id')->getPsObjGroupId();
			
			$param = array(
					'ps_customer_id' => $ps_customer_id,
					'ps_workplace_id' => $ps_workplace_id,
					'ps_school_year_id' => $ps_school_year_id,
					'ps_obj_group_id'=> $ps_obj_group_id,
					'orderby' => 'ASC'
			);
			
			// Lay ra tat ca dot kham theo nhom tre
			$psExamination = Doctrine::getTable ( 'PsExamination' )->getListExaminationByParams($param);
			
			// Lay tat ca du lieu da kham cua lop
			$psStudentGrowths = Doctrine::getTable ( 'PsStudentGrowths' )->getAllStudentsByClassId($ps_school_year_id, $ps_customer_id, $ps_workplace_id, $class_id);
			
			$exportFile->xuatThongTinYTeSucKhoe ( $list_student, $psExamination, $psStudentGrowths );
			
			$exportFile->saveAsFile ( "DuLieuYTe.xls" );
			
		}elseif($type_export == 3){
			
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'id', $ps_school_year_id );
			
			$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );
			
			$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );
			
			$array_month = PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd );
			
			// Lay tat ca linh vuc danh gia
			$psEvaluateSubject = Doctrine::getTable ( 'PsEvaluateSubject' )->getSubjectByCustomerWorkplaceClassId($ps_customer_id, $ps_workplace_id, $ps_school_year_id,$class_id);
			//echo count($psEvaluateSubject); die;
			// Lay ky hieu danh gia
			$psEvaluateIndexSymbol = Doctrine::getTable ( 'PsEvaluateIndexSymbol' )->getSymbolByCustomerSchoolyearId($ps_customer_id, $ps_school_year_id, $ps_workplace_id);
			// Lay tieu chi danh gia
			$psEvaluateIndexCriteria = Doctrine::getTable ( 'PsEvaluateIndexCriteria' )->getPsEvaluateIndexCriteria($ps_customer_id,$ps_workplace_id,$ps_school_year_id);
			// Danh gia cua hoc sinh
			$psEvaluateIndexStudent = Doctrine::getTable ( 'PsEvaluateIndexStudent' )->getEvaluteStudentByClassId2($class_id, null);
			
			$exportFile->xuatChiSoDanhGiaTre ( $list_student,$psEvaluateSubject,$psEvaluateIndexSymbol,$psEvaluateIndexCriteria,$psEvaluateIndexStudent,$array_month );
			
			$exportFile->saveAsFile ( "TheoDoiDanhGiaTre.xls" );
			
		}else{
			// lay danh sach nguoi than cua be trong lop
			$list_relative = Doctrine::getTable ( 'RelativeStudent' )->getAllRelativeByClass ( $class_id );
			
			$exportFile->xuatSoTheoDoiTreTrongLop ( $list_student,$list_relative );
			
			$exportFile->saveAsFile ( "ThongTinHocSinh.xls" );
			
		}
		
		/*
		$exportFile->setActiveSheetIndex(0);
		
		$exportFile->xuatSoTheoDoiTreTrongLop ( $list_student,$list_relative );
		
		$exportFile->setActiveSheetIndex(1);
		
		$exportFile->xuatSoDiemDanhTheoThang ( $list_student, $array_month, $list_logtimes );
		
		$exportFile->setActiveSheetIndex(2);
		
		$exportFile->xuatThongTinYTeSucKhoe ( $list_student, $psExamination, $psStudentGrowths );
		
		$exportFile->setActiveSheetIndex(3);
		
		$exportFile->xuatChiSoDanhGiaTre ( $list_student,$psEvaluateSubject,$psEvaluateIndexSymbol,$psEvaluateIndexCriteria,$psEvaluateIndexStudent );
		*/
		//$exportFile->saveAsFile ( "SoTheoDoiTre.xls" );
		
	}
	
	// Cap nhat lop hien tai cho hoc sinh vao bang Student
	protected function updatedStudentClass($ps_customer_id = null) {
		
		$userId = myUser::getUserId();
		$date = date ( 'YmdHis' );
		// Lay danh sach hoc sinh trang thai lop hien tại = 1
		$studentClassIsUpdated = Doctrine::getTable('StudentClass')->getListStudentClassByIsActivated($ps_customer_id);
		/*
		foreach ($studentClassIsUpdated as $studentClass) {
			echo 'sID: '.$studentClass->getStudentId().'<br/>';
		}
		
		echo count($studentClassIsUpdated);die;
		*/
		
		
		foreach ($studentClassIsUpdated as $studentClass){
			
			Doctrine_Query::create ()->from ( 'Student' )->update ()
			->set ( 'current_class_id', $studentClass->getMyclassId() )
			//->set ( 'user_updated_id', $userId )
			//->set ( 'updated_at', $date )
			->addWhere ( 'id =?', $studentClass->getStudentId() )
			->execute ();
			
		}
		
	}

}
