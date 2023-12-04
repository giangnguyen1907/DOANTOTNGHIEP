<?php
require_once dirname ( __FILE__ ) . '/../lib/psStudentClassGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psStudentClassGeneratorHelper.class.php';

/**
 * psStudentClass actions.
 *
 * @package kidsschool.vn
 * @subpackage psStudentClass
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psStudentClassActions extends autoPsStudentClassActions {

	// Phan lop cho hoc sinh moi nhap hoc
	public function executeAssignClassStudents(sfWebRequest $request) {

	}

	// Lay danh sach hoc sinh de phan lop
	public function executeStudentsForClass(sfWebRequest $request) {

		// Kiem tra du lieu
		$ps_class_id = $request->getParameter ( 'ps_class_id' );

		// if ($ps_class_id <= 0)
		$this->forward404Unless ( $ps_class_id, sprintf ( 'Object (%s) does not exist .', $ps_class_id ) );

		$my_class = Doctrine::getTable ( 'MyClass' )->findOneById ( $ps_class_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $my_class, 'PS_STUDENT_CLASS_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $ps_class_id ) );

		// Lay hoc sinh de phan lop: chi lay trang thai Dang hoc - Hoc thu. Sap sep uu tien: H/s chua duoc phan lop, do tuoi tang dan
		// Lay hoc sinh hien tai chua duoc phan lop

		$students = $my_class->getPsStudentsNotInClass ();

		return $this->renderPartial ( 'psStudentClass/studentForClass', array (
				'students' => $students,
				'my_class' => $my_class ) );
	}

	public function executeNew(sfWebRequest $request) {

		$ps_student_id = $request->getParameter ( 'student_id' );

		if ($ps_student_id <= 0) {
			$this->forward404Unless ( $ps_student_id, sprintf ( 'Object (%s) does not exist .', $ps_student_id ) );
		} else {

			$this->ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $ps_student_id );

			$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->ps_student->getId () ) );

			$student_class = new StudentClass ();

			$student_class->setStudentId ( $ps_student_id );

			$this->form = $this->configuration->getForm ( $student_class );

			$this->student_class = $this->form->getObject ();

			$this->helper = new psStudentClassGeneratorHelper ();

			$this->list_service = array ();

			return $this->renderPartial ( 'psStudentClass/newSuccess', array (
					'student_class' => $this->student_class,
					'form' => $this->form,
					'ps_student' => $this->ps_student,
					'list_service' => $this->list_service,
					'configuration' => $this->configuration,
					'helper' => $this->helper ) );
		}
	}

	public function executeCreate(sfWebRequest $request) {

		$formValues = $request->getParameter ( 'student_class' );

		$statistic_class_id = $formValues['statistic_myclass_id'];

		$class_to_id  = $formValues['myclass_id'];

		$start_at = $formValues['start_at']; // Ngày bắt đầu

		$is_activated = $formValues['is_activated']; // Là lớp hiện tại không

		$stop_at_old = date ( 'Y-m-d', strtotime ( '-1 days', strtotime ( $start_at ) ) );

		$ps_student_id = isset ( $formValues ['student_id'] ) ? $formValues ['student_id'] : '';

		$user_id = myUser::getUserId();

		if ($ps_student_id <= 0) {
			$this->forward404Unless ( $ps_class_id, sprintf ( 'Object (%s) does not exist .', $ps_class_id ) );
		} else {

			$this->ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $ps_student_id );

			$class_from_id = $this->ps_student->getCurrentClassId();

			$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->ps_student->getId () ) );

			// Kiem tra thoi diem hien tai hoc sinh dang nam trong lop hay khong
			$student_class_from = Doctrine::getTable ( 'StudentClass' )->getObjByStudentAndClass ( $ps_student_id, $class_to_id, date('Y-m-d'))->fetchOne ();
			if ($student_class_from) { // Nếu có dữ liệu
						
				$student_class_from->setStopAt ( $stop_at_old );
				
				if($is_activated == PreSchool::ACTIVE){
					$student_class_from->setIsActivated ( PreSchool::NOT_ACTIVE );
				}
				
				$student_class_from->setUserUpdatedId ( $user_id );
				
				$student_class_from->save ();						
			}

			if($is_activated == PreSchool::ACTIVE){
				// lay tat ca cac lop ma hoc sinh nay co mat va trang thai la lop hien tai
				$student_class_active = Doctrine::getTable ( 'StudentClass' )->getObjByStudentAndClass ( $ps_student_id )->execute ();
				foreach ($student_class_active as $student_active){
					$student_active -> setIsActivated ( PreSchool::NOT_ACTIVE );
					$student_active -> save();
				}
				$update_class_id = $class_to_id;

			}

			if($statistic_class_id > 0){
				if($statistic_class_id > 0){
					$class_to = $statistic_class_id;
				}else{
					$class_to = $class_to_id;
				}
			}

			// Cap nhat lop hien tai vao bang hoc sinh
			$psStudents = Doctrine::getTable('Student')->findOneById($ps_student_id);
			$psStudents -> setStatisticClassId($class_to);
			if($update_class_id > 0){
				$psStudents -> setCurrentClassId($update_class_id);
			}
			$psStudents -> save();
			

			$this->student_class = new StudentClass ();

			$this->student_class->setStudentId ( $ps_student_id );

			$this->student_class->setFromMyclassId ( $class_from_id );

			$this->student_class->setMyclassId ( $class_to_id );

			$this->student_class->setFormStatisticMyclassId ( $class_from_id );

			$this->student_class->setStatisticMyclassId ( $class_to );

			$this->form = $this->configuration->getForm ( $this->student_class );

			$this->processForm ( $request, $this->form, $this->ps_student );
		}

		exit ( 0 );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->student_class = $this->getRoute ()
			->getObject ();

		$ps_student_id = $this->student_class->getStudentId ();

		$this->ps_student = $this->student_class->getStudent (); // Doctrine::getTable ( 'Student' )->findOneById ( $ps_student_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->ps_student->getId () ) );

		$this->form = $this->configuration->getForm ( $this->student_class );

		$this->form->setDefault ( 'url_callback', $request->getParameter ( 'url_callback' ) );

		$this->student_class = $this->form->getObject ();

		$this->helper = new psStudentClassGeneratorHelper ();

		$this->list_service = Doctrine::getTable ( 'Service' )->getListServicesForAddNew ( $ps_student_id, $this->ps_student->getPsCustomerId (), $this->student_class->getMyclassId () );

		return $this->renderPartial ( 'psStudentClass/newSuccess', array (
				'student_class' => $this->student_class,
				'form' => $this->form,
				'ps_student' => $this->ps_student,
				'list_service' => $this->list_service,
				'configuration' => $this->configuration,
				'helper' => $this->helper ) );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->student_class = $this->getRoute ()->getObject ();

		$ps_student_id = $this->student_class->getStudentId ();

		$this->ps_student = $this->student_class->getStudent (); // Doctrine::getTable ( 'Student' )->findOneById ( $ps_student_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->ps_student->getId () ) );

		$this->form = $this->configuration->getForm ( $this->student_class );

		$this->student_class = $this->form->getObject ();

		$this->helper = new psStudentClassGeneratorHelper ();

		$this->processForm ( $request, $this->form, $this->ps_student );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$student_class = $this->getRoute ()
			->getObject ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $student_class ) ) );

		$ps_student_id = $student_class->getStudentId ();

		$this->ps_student = $student_class->getStudent (); // Doctrine::getTable ( 'Student' )->findOneById ( $ps_student_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->ps_student->getId () ) );

		$obj_student_class = Doctrine::getTable ( 'StudentClass' )->findOneById ( $student_class->getId () );
		
		if($obj_student_class && $obj_student_class->getIsActivated() == PreSchool::ACTIVE){
			
			$this->ps_student -> setCurrentClassId(null);
			$this->ps_student -> save();
			
		}
		
		if ($obj_student_class->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'Remove class successfully.' );
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'Remove class failed.' );
		}

		$url_callback = $request->getParameter ( 'url_callback' );

		if ($url_callback != '') {
			$this->redirect ( '@' . PsEndCode::ps64Decode ( $url_callback ) . '#pstab_3' );
		} else {
			$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_3' );
		}
	}

	// chuyen lop cho tung hoc sinh
	protected function processForm(sfWebRequest $request, sfForm $form) {

		$_formValues = $request->getParameter ( $form->getName () );

		$student_id = $ps_student_id = isset ( $_formValues ['student_id'] ) ? $_formValues ['student_id'] : '';

		$formValues = $request->getParameter ( 'form_student_service' );

		if(count($formValues) == 0){$formValues = array();}
		
		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );

		$is_activated = $_formValues ['is_activated'];

		$start_date_at = isset ($_formValues ['start_date_at']) ? $_formValues ['start_date_at'] : '' ; // Chọn có phải ngày nhập học hay không

		$type = $_formValues ['type'];

		if($_formValues ['stop_at'] !='' && (strtotime($_formValues ['start_at']) > strtotime($_formValues ['stop_at']))){
			$this->getUser () ->setFlash ( 'error', $this->getContext ()->getI18N ()->__('Stop at invalid' ));
			$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_3' );
		}
		
		$student_update = Doctrine::getTable ( 'Student' )-> getStudentByField($ps_student_id,'ps_customer_id, start_date_at');
		
		$ps_customer_id = $student_update->getPsCustomerId();
		
		if ($type == PreSchool::SC_STATUS_OFFICIAL && $start_date_at == 'on') {

			$date_at = date ( 'Y-m-d', strtotime ( $_formValues ['start_at'] ) );

			$student_update->setStartDateAt ( $date_at );

			$student_update->save ();
			
		}

		if ($is_activated == 1 && $_formValues ['id'] > 0) { // Cap nhat lop hoc hien tai cua hoc sinh
			$activated_class = Doctrine_Query::create ()->from ( 'StudentClass' )
				->whereIn ( 'student_id', $ps_student_id )
				->addWhere ( 'id !=?', $_formValues ['id'] )
				->execute ();
		} elseif ($is_activated == 1) {
			$activated_class = Doctrine_Query::create ()->from ( 'StudentClass' )->whereIn ( 'student_id', $ps_student_id )->execute ();
		}

		foreach ( $activated_class as $activated ) {
			$activated->setIsActivated ( 0 );
			$activated->save ();
		}
		
		$userId = myUser::getUserId();
		
		if ($form->isValid ()) {

			$conn = Doctrine_Manager::connection ();

			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {

				$conn->beginTransaction ();

				if ($form->getObject ()->isNew ()) {

					// Tim ban ghi co start_at dung truoc start_at moi
					$studentclassObj = Doctrine_Core::getTable ( 'StudentClass' )->getPsStartAtMaxByObj ( $ps_student_id, $_formValues ['start_at'], $_formValues ['id'] );

					// Tim ban ghi co start_at nho nhat so voi start_at moi(start_at > start_at new)
					$studentclassStartAtMinObj = Doctrine_Core::getTable ( 'StudentClass' )->getPsStartAtMinByObj ( $ps_student_id, $_formValues ['start_at'], $_formValues ['id'] );

					$stop_at_new = $_formValues ['stop_at'] ? $_formValues ['stop_at'] : null;
					$fromMyclassId = null;

					if ($studentclassObj) {

						// De cap nhat lai cho $studentclassObj
						$stop_at = date ( 'Ymd', strtotime ( '-1 days', strtotime ( $_formValues ['start_at'] ) ) );

						$fromMyclassId = $studentclassObj->getMyclassId ();

						if (! $studentclassObj->getStopAt ()) { // Neu day la ban thu cuoi cung tren list truoc khi them moi

							// Gan lai gia tri stop_at
							$studentclassObj->setStopAt ( $stop_at );
							$studentclassObj->save ();
						} else { // Neu khong phai ban ghi cuoi tren list

							// Lay gia tri stop_at cho ban ghi moi them vao
							if (date ( "Ymd", strtotime ( $studentclassObj->getStopAt () ) ) >= date ( "Ymd", strtotime ( $_formValues ['start_at'] ) )) {
								// $stop_at_new = $studentclassObj->getStopAt();

								// Gan lai gia tri stop_at
								$studentclassObj->setStopAt ( $stop_at );
								$studentclassObj->save ();
							}
						}
					}

					if ($studentclassStartAtMinObj) {

						if (date ( "Ymd", strtotime ( $string_start_at ) ) < date ( "Ymd", strtotime ( $studentclassStartAtMinObj->getStopAt () ) ))
							$stop_at_new = null;
						else
							$stop_at_new = date ( 'Ymd', strtotime ( '-1 days', strtotime ( $studentclassStartAtMinObj->getStartAt () ) ) );

						$fromMyclassId = $studentclassStartAtMinObj->getMyclassId ();
					}

					// Save value for new class
					$student_class_new = $form->save ();

					$student_class_new->setFromMyclassId ( $fromMyclassId );

					$student_class_new->setStopAt ( $stop_at_new );

					$student_class_new->save ();
				} else {
					// sua -
					$student_class_new = $form->save ();
				}

				// Them dich vu
				foreach ( $formValues as $key => $values ) {

					$service_id = $key;

					if (isset ( $values ['select'] )) {

						$StudentServiceObj = Doctrine_Core::getTable ( 'StudentService' )->checkStudentServiceExits ( $ps_student_id, $service_id );

						if (! $StudentServiceObj) {

							$StudentService = new StudentService ();

							$StudentService->setStudentId ( $ps_student_id );

							$StudentService->setDiscount ( 0 );

							$StudentService->setServiceId ( $service_id );

							$StudentService->save ();
						}
					}
				}

				if($student_class_new -> getIsActivated() == PreSchool::ACTIVE){
					
					$psStudent = Doctrine::getTable("Student")->getStudentByField($ps_student_id, 'current_class_id, user_updated_id');
					
					if($psStudent){
						$psStudent -> setCurrentClassId($student_class_new->getMyclassId());
						$psStudent -> setUserUpdatedId($userId);
						$psStudent -> save();
					}
				}
				
				$array_relative_id = $relative_id = array();
				
				if($type == PreSchool::SC_STATUS_STOP_STUDYING){
					// Check va xoa nguoi than cua hoc sinh
					$check_relative_exist = Doctrine::getTable ( 'RelativeStudent' )->checkRelativeHaveManyStudentNotDelete ( $student_id );
					// Check va day id nguoi than cua hoc sinh vao mang
					if ($check_relative_exist == 1) {
						$all_relatives = Doctrine::getTable ( 'RelativeStudent' )->findByStudentId ( $student_id, $ps_customer_id )->toArray ();
						$relative_id = array_merge ( $relative_id, array_column ( $all_relatives, 'relative_id' ) );
						// End check va day id nguoi than cua hoc sinh vao mang
					}
					
					$date = date ( 'YmdHis' );
					
					if (count ( $relative_id ) > 0) {
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
				}
				
				/* 
				// Khoa tai khoan phu huynh khi trang thai hoc la Thoi Hoc
				$userId = sfContext::getInstance ()->getUser ()->getGuardUser ()->getId ();
				
				$date = date ( 'YmdHis' );
				
				// Trang thai la Thoi Hoc thi khoa tai khoan
				if($type == PreSchool::SC_STATUS_STOP_STUDYING){
					
					// Lay ra danh sach nguoi than cua hoc sinh ma co tai khoan
					$all_relatives = Doctrine::getTable ( 'RelativeStudent' )->getRelativeByStudentId ( $student_id, $ps_customer_id );
					
					foreach ($all_relatives as $relatives){
						array_push($array_relative_id, $relatives -> getRelativeId());
					}
					
				}
				
				foreach ($array_relative_id as $relative_id){
					
					// Kiem tra nguoi than day co quan he voi hoc sinh nao dang hoat dong nua hay khong
					$check_relatives = Doctrine::getTable ( 'RelativeStudent' )->getRelativeStudentsByRelativeId($relative_id,$student_id);
					
					// Neu khong co quan he voi hoc sinh nao thi khoa tai khoan lai
					if(!$check_relatives){
						
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
						
					}
					
				}
				*/
				$conn->commit ();
				
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

				$conn->rollback ();

				return sfView::SUCCESS;
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $student_class ) ) );

			if ($_formValues ['url_callback'] != '') {

				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( '@' . PsEndCode::ps64Decode ( $_formValues ['url_callback'] ) . '#pstab_3' );
			} else {

				if ($request->hasParameter ( '_save_and_add' )) {

					$this->getUser ()
						->setFlash ( 'notice', $notice . ' You can add another one below.' );

					$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_3' );
				} else {

					$this->getUser ()
						->setFlash ( 'notice', $notice );

					$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_3' );
				}
			}
		} else {

			if ($_formValues ['url_callback'] != '') {

				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( '@' . PsEndCode::ps64Decode ( $_formValues ['url_callback'] ) . '#pstab_3' );
			} else {

				$this->getUser ()
					->setFlash ( 'error', 'The item has not been saved due to some errors.' );

				$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_3' );
			}
		}
	}
}
