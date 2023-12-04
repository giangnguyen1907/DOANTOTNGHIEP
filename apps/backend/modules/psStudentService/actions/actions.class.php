<?php
require_once dirname ( __FILE__ ) . '/../lib/psStudentServiceGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psStudentServiceGeneratorHelper.class.php';

/**
 * psStudentService actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psStudentService
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psStudentServiceActions extends autoPsStudentServiceActions {

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$student_service = $this->getRoute ()->getObject ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $student_service ) ) );

		$ps_student_id = $student_service->getStudentId ();

		$ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $ps_student_id );

		if (! myUser::checkAccessObject ( $ps_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {
			$this->getUser ()->setFlash ( 'error', 'Object does not exist' );
			$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_4' );
		}

		if ($student_service) {

			$student_service->setDeleteAt ( date ( 'Y-m-d H:i:s' ) );

			$student_service->setUserDeletedId ( myUser::getUserId () );

			$student_service->save ();

			$this->getUser ()->setFlash ( 'notice', 'Remove registered service successfully.' );
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'Remove registered service failed.' );
		}
		
		$back_url = $request->getParameter('back_url');
		
		if($back_url != '') {
			
			$back_url = PsEndCode::ps64Decode($back_url);			
			$this->redirect ($back_url);
			
		}
		
		$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_4' );
	}
	
	public function executeBatch(sfWebRequest $request)
	{
		
		$request->checkCSRFProtection();
		
		if (!$ids = $request->getParameter('ids'))
		{
			$this->getUser()->setFlash('error', 'You must at least select one item.');
			
			$this->redirect('@ps_student_service');
		}
		
		if (!$action = $request->getParameter('batch_action'))
		{
			$this->getUser()->setFlash('error', 'You must select an action to execute on the selected items.');
			
			$this->redirect('@ps_student_service');
		}
		
		if (!method_exists($this, $method = 'execute'.ucfirst($action)))
		{
			throw new InvalidArgumentException(sprintf('You must create a "%s" method for action "%s"', $method, $action));
		}
		
		if (!$this->getUser()->hasCredential($this->configuration->getCredentials($action)))
		{
			$this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
		}
		
		$validator = new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'StudentService'));
		try
		{
			// validate ids
			$ids = $validator->clean($ids);
			
			// execute batch
			$this->$method($request);
		}
		catch (sfValidatorError $e)
		{
			$this->getUser()->setFlash('error', 'A problem occurs when deleting the selected items as some items do not exist anymore.');
		}
		
		$this->redirect('@ps_student_service');
	}
	
	protected function executeBatchDelete(sfWebRequest $request)
	{
		$ids = $request->getParameter('ids');
		
		$records = Doctrine_Query::create()
		->from('StudentService')
		->whereIn('id', $ids)
		->execute();
		
		foreach ($records as $record)
		{
			$this->dispatcher->notify(new sfEvent($this, 'admin.delete_object', array('object' => $record)));
			$record -> setDeleteAt (date('Y-m-d H:i:s'));
			$record -> setUserDeletedId ( myUser::getUserId () );
			$record -> save();
			//$record->delete();
		}
		/**/
		$this->getUser()->setFlash('notice', 'Remove registered service successfully.');
		$this->redirect('@ps_student_service');
	}

	public function executeNew(sfWebRequest $request) {

		$ps_student_id = $request->getParameter ( 'student_id' );

		if ($ps_student_id <= 0) 
		{
			$this->forward404Unless ( $ps_student_id, sprintf ( 'Object (%s) does not exist .', $ps_student_id ) );
		} else {

			$this->ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $ps_student_id );

			$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->ps_student->getId () ) );

			$ps_customer_id = $this->ps_student->getPsCustomerId();
			$ps_workplace_id = $this->ps_student->getPsWorkplaceId();

			$student_service = new StudentService ();

			$student_service->setStudentId ( $ps_student_id );
			
			//$student_service->setPsCustomerId ( $ps_customer_id );
			//$student_service->setPsWorkplaceId ( $ps_workplace_id );

			$this->form = $this->configuration->getForm ( $student_service );

			$this->student_service = $this->form->getObject ();

			$this->helper = new psStudentServiceGeneratorHelper ();

			$student_class = $this->ps_student->getClassByDate ( time () );

			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );

			$this->list_service = Doctrine::getTable ( 'Service' )->getListServicesForAddNew ( $ps_student_id, $this->ps_student->getPsCustomerId (), null, null, $schoolYearsDefault ? $schoolYearsDefault->getId () : null );

			// Lấy danh sách tần xuất thu
			$psRegularity = Doctrine_Query::create()->from('PsRegularity')->addWhere('ps_customer_id =?',$ps_customer_id)->andWhere('ps_workplace_id =?',$ps_workplace_id)->execute();


			return $this->renderPartial ( 'psStudentService/formSuccess', array (
					'student_service' => $this->student_service,
					'form' => $this->form,
					'list_service' => $this->list_service,
					'ps_student' => $this->ps_student,
					'psRegularity' => $psRegularity,
					'configuration' => $this->configuration,
					'helper' => $this->helper,
					'student_class' => $student_class,
					'schoolYearsDefault' => $schoolYearsDefault ) );
		}
	}

	public function executeEdit(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$ps_student_service = $this->getRoute ()
				->getObject ();

			if (! $ps_student_service) {

				return $this->renderPartial ( 'global/include/_box_modal_error_403404' );
			} else {

				$ps_student_id = $ps_student_service->getStudentId ();

				$ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $ps_student_id );

				if (! myUser::checkAccessObject ( $ps_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {

					return $this->renderPartial ( 'global/include/_box_modal_error_403404' );
				}

				$form = $this->configuration->getForm ( $ps_student_service );

				$helper = new psStudentServiceGeneratorHelper ();

				// Lay lop hien tai
				$student_class = $ps_student->getClassByDate ( time () );

				return $this->renderPartial ( 'psStudentService/editSuccess', array (
						'ps_student_service' => $ps_student_service,
						'form' => $form,
						'ps_student' => $ps_student,
						'student_class' => $student_class,
						'configuration' => $this->configuration,
						'helper' => $helper ) );
			}
		} else {

			return $this->renderPartial ( 'global/include/_box_modal_error_403404' );
		}
	}

	public function executeUpdate(sfWebRequest $request) {

		$formValues = $request->getParameter ( 'student_service' );

		$ps_student_id = isset ( $formValues ['student_id'] ) ? $formValues ['student_id'] : '';

		if($ps_student_id > 0){
			$ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $ps_student_id );
			$ps_customer_id = $ps_student->getPsCustomerId();
		}else{
			$this->getUser ()->setFlash ( 'error', 'Lỗi không xác định được học sinh' );
			$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_4' );
		}

		$psRegularity = Doctrine_Query::create()->from('PsRegularity')->addWhere('ps_customer_id =?',$ps_customer_id)->execute();

		$array_regularity = array();
		foreach($psRegularity as $regularity){
			$array_regularity[$regularity->getId()]['number'] = $regularity->getNumber();
			$array_regularity[$regularity->getId()]['discount'] = $regularity->getDiscount();
			$array_regularity[$regularity->getId()]['is_type'] = $regularity->getIsType();
		}

		if(count($array_regularity) <= 0){
			$this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ( 'Error list select student.' ) );
			$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_4' );
		}

		$tanxuat = $formValues['regularity_id'];

		if(is_array($array_regularity[$tanxuat])){
			$number_month = $array_regularity[$tanxuat]['number'];
			if($array_regularity[$tanxuat]['is_type'] == 0){
				$discount = $array_regularity[$tanxuat]['discount'];
				$discount_amount = 0;
			}else{
				$discount = 0;
				$discount_amount = $array_regularity[$tanxuat]['discount'];
			}
		}else{
			break;
		}

		$service_id = $formValues['service_id'];

		$note = $formValues['note'];

		$ps_student_service = Doctrine_Core::getTable ( 'StudentService' )->checkStudentServiceExits ( $ps_student_id, $service_id );

		if($ps_student_service){

			// $ps_student_service->setDiscountAmount ( $discount_amount );

			// $ps_student_service->setDiscount ( $discount );

			$ps_student_service->setRegularityId ( $tanxuat );

			$ps_student_service->setNumberMonth ( $number_month );

			$ps_student_service->setNote ( $note );

			$ps_student_service->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
								->getGuardUser ()
								->getId () );

			$ps_student_service->save();

		}

		// $this->ps_student_service = $this->getRoute ()->getObject ();

		// $this->form = $this->configuration->getForm ( $this->ps_student_service );

		// $ps_student_id = $this->ps_student_service->getStudentId ();

		// $this->processForm ( $request, $this->form );

		$this->ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $ps_student_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $ps_student_id ) );

		$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_4' );
		
	}

	public function executeStudentKeywords(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$keywords = $request->getParameter ( 'keywords' );
			$html = $request->getParameter ( 'html' );
			$ps_service_course_id = $request->getParameter ( 'ps_service_course_id' );

			if ($ps_service_course_id <= 0) {
				exit ( 0 );
			} else {

				$ps_service_courses = Doctrine::getTable ( 'PsServiceCourses' )->findOneBy ( 'id', $ps_service_course_id );

				// $this->forward404Unless(myUser::checkRoleObject($ps_service_courses), sprintf('Object (%s) does not exist .', $ps_service_course_id));

				$this->helper = new psStudentServiceGeneratorHelper ();

				$student_service = new StudentService ();

				$student_service->setPsServiceCourseId ( $ps_service_course_id );

				$this->form = $this->configuration->getForm ( $student_service );

				$this->form->setDefault ( 'keywords', $keywords );

				$this->pager = new sfDoctrinePager ( 'Student', 5 );
				$this->pager->setQuery ( Doctrine::getTable ( 'Student' )->setListStudentForPsServiceCourses ( $ps_service_courses->getPsServiceId (), $keywords ) );
				$this->pager->setPage ( $request->getParameter ( 'page', 1 ) );
				$this->pager->init ();

				$this->list_student = $this->pager->getResults ();

				$file_result = ($html == 'table') ? 'table_student' : 'list_student_main';

				return $this->renderPartial ( 'psStudentService/' . $file_result, array (
						'ps_service_courses' => $ps_service_courses,
						'student_service' => $student_service,
						'list_student' => $this->list_student,
						'form' => $this->form,
						'pager' => $this->pager ) );
			}
		} else {
			exit ( 0 );
		}
	}

	public function executeRegister(sfWebRequest $request) {
		
		$this->formFilter = new sfFormFilter ();

		$this->formFilter->setWidget ( 'keywords', new sfWidgetFormInputText () );
		$this->formFilter->setValidator ( 'keywords', new sfValidatorString (array ('required' => false ) ) );

		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'student_filter[%s]' );

		$this->form = $this->configuration->getForm ();

		$ps_service_course_id = $request->getParameter ( 'ps_service_course_id' );

		if ($ps_service_course_id <= 0) {
			$this->forward404Unless ( $ps_service_course_id, sprintf ( 'Object (%s) does not exist .', $ps_service_course_id ) );
		} else {

			$ps_service_courses = Doctrine::getTable ( 'PsServiceCourses' )->findOneBy ( 'id', $ps_service_course_id );

			// $this->forward404Unless(myUser::checkRoleObject($ps_service_courses), sprintf('Object (%s) does not exist .', $ps_service_course_id));

			$this->helper = new psStudentServiceGeneratorHelper ();

			$this->pager = new sfDoctrinePager ( 'Student', 5 );
			$this->pager->setQuery ( Doctrine::getTable ( 'Student' )->setListStudentForPsServiceCourses ( $ps_service_courses->getPsServiceId () ) );
			$this->pager->setPage ( $request->getParameter ( 'page', 1 ) );
			$this->pager->init ();

			$this->list_student = $this->pager->getResults ();

			$this->form->setDefault ( 'ps_service_course_id', $ps_service_course_id );

			return $this->renderPartial ( 'psStudentService/registerSuccess', array (
					'ps_service_courses' => $ps_service_courses,
					'list_student' => $this->list_student,
					'configuration' => $this->configuration,
					'helper' => $this->helper,
					'pager' => $this->pager,
					'form' => $this->form,
					'formFilter' => $this->formFilter ) );
		}
	}

	public function executeRegisterCreate(sfWebRequest $request) {

		$formValues = $request->getParameter ( 'form_student_service' );

		$_formValues = $request->getParameter ( 'student_service' );

		$ps_service_course_id = isset ( $_formValues ['ps_service_course_id'] ) ? $_formValues ['ps_service_course_id'] : '';

		if ($ps_service_course_id <= 0) {
			$this->forward404Unless ( $ps_service_course_id, sprintf ( 'Object (%s) does not exist .', $ps_service_course_id ) );
		} else {

			$ps_service_courses = Doctrine::getTable ( 'PsServiceCourses' )->findOneBy ( 'id', $ps_service_course_id );

			$form = $this->configuration->getForm ();

			$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );

			$notice = 'Register courses for student successfully.';

			try {

				foreach ( $formValues as $key => $values ) {
					$student_id = $key;

					$ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $student_id );

					$this->forward404Unless ( myUser::checkAccessObject ( $ps_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $ps_student->getId () ) );

					if (isset ( $values ['select'] )) {

						// lay obj hoc sinh dang ky dich vu
						$StudentServiceObj = Doctrine_Core::getTable ( 'StudentService' )->getStudentServiceCourse ( $student_id, $ps_service_courses->getPsServiceId () );

						if ($StudentServiceObj) {
							$StudentServiceObj->setPsServiceCourseId ( $ps_service_course_id );
							$StudentServiceObj->save ();
						}
					}
				}
				if (! $StudentServiceObj) {

					$this->getUser ()
						->setFlash ( 'error', 'Register courses for student fail.' );

					$this->redirect ( '@ps_service_courses_edit?id=' . $ps_service_course_id . '#pstab_2' );
				} else {
					$this->getUser ()
						->setFlash ( 'notice', $notice );

					$this->redirect ( '@ps_service_courses_edit?id=' . $ps_service_course_id . '#pstab_2' );
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

				$this->redirect ( '@ps_service_courses_edit?id=' . $ps_service_course_id . '#pstab_2' );
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $ps_service_courses ) ) );
		}

		exit ( 0 );
	}

	public function executeCourseDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$_formValues = $request->getParameter ( 'student_service' );

		$student_service_id = isset ( $_formValues ['student_service_id'] ) ? $_formValues ['student_service_id'] : '';

		$student_service = Doctrine::getTable ( 'StudentService' )->findOneBy ( 'id', $student_service_id );

		$ps_service_course_id = $student_service->getPsServiceCourseId ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $student_service ) ) );

		$ps_student_id = $student_service->getStudentId ();

		$ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $ps_student_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $ps_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $ps_student->getId () ) );

		if ($student_service) {

			$student_service->setPsServiceCourseId ( null );

			$student_service->save ();

			$this->getUser ()
				->setFlash ( 'notice', 'Remove student from courses successfully.' );
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'Remove student from courses failed.' );
		}

		$this->redirect ( '@ps_service_courses_edit?id=' . $ps_service_course_id . '#pstab_2' );
	}

	public function executeCreate(sfWebRequest $request) {

		$formValues = $request->getParameter ( 'student_service' );

		$ps_student_id = isset ( $formValues ['student_id'] ) ? $formValues ['student_id'] : '';
		//$regularity_id = isset ( $formValues ['regularity_id'] ) ? $formValues ['regularity_id'] : '';

		//echo $regularity_id;die;

		if ($ps_student_id <= 0) {

			$this->forward404Unless ( $ps_student_id, sprintf ( 'Object (%s) does not exist .', $ps_student_id ) );
		} else {

			$ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $ps_student_id );

			$this->forward404Unless ( myUser::checkAccessObject ( $ps_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $ps_student->getId () ) );

			//echo $ps_student_id;die;

			$student_service = new StudentService ();

			$student_service->setStudentId ( $ps_student_id );

			$this->form = $this->configuration->getForm ( $student_service );

			$this->processFormNew ( $request, $this->form );
		}

		exit ( 0 );
	}

	public function executeStudentServiceRestore(sfWebRequest $request) {

		$restore_id = $request->getParameter ( 'id' );

		if ($restore_id > 0) {

			$ps_student_service = Doctrine::getTable ( 'StudentService' )->findOneById ( $restore_id );

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $ps_student_service ) ) );

			$ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $ps_student_service->getStudentId () );

			$this->forward404Unless ( myUser::checkAccessObject ( $ps_student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $ps_student_service->getStudentId () ) );

			try {
				$ps_student_service->setDeleteAt ( null );

				$ps_student_service->save ();

				$this->getUser ()
					->setFlash ( 'notice', $this->getContext ()
					->getI18N ()
					->__ ( 'Restore registered service successfully.' ) );
			} catch ( Exception $e ) {

				$this->getUser ()
					->setFlash ( 'error', $this->getContext ()
					->getI18N ()
					->__ ( 'Restore registered service fail.' ) );
			}

			$this->redirect ( '@ps_students_edit?id=' . $ps_student_service->getStudentId () . '#pstab_3' );
		} else {
			$this->forward404Unless ( false, sprintf ( 'Object (%s) does not exist .', $restore_id ) );
		}
	}

	protected function processFormNew(sfWebRequest $request, sfForm $form) {

		$formValues = $request->getParameter ( 'form_student_service' );

		$_formValues = $request->getParameter ( 'student_service' );

		$ps_student_id = isset ( $_formValues ['student_id'] ) ? $_formValues ['student_id'] : '';

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );

		$notice = 'Register service for your baby successfully.';

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			$b_check = false;

			if($ps_student_id > 0){
				$ps_student = Doctrine::getTable ( 'Student' )->findOneById ( $ps_student_id );
				$ps_customer_id = $ps_student->getPsCustomerId();
			}else{
				$this->getUser ()->setFlash ( 'error', 'Lỗi không xác định được học sinh' );
				//$this->redirect ( '@ps_service_registration_student' );
				$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_4' );
			}

			// Danh sách tần xuất thu và giảm trừ
			$psRegularity = Doctrine_Query::create()->from('PsRegularity')->addWhere('ps_customer_id =?',$ps_customer_id)->execute();

			$array_regularity = array();
			foreach($psRegularity as $regularity){
				$array_regularity[$regularity->getId()]['number'] = $regularity->getNumber();
				$array_regularity[$regularity->getId()]['discount'] = $regularity->getDiscount();
				$array_regularity[$regularity->getId()]['is_type'] = $regularity->getIsType();
			}

			if(count($array_regularity) <= 0){
				$this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ( 'Error list select student.' ) );
				//$this->redirect ( '@ps_service_registration_student' );
				$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_4' );
			}


			foreach ( $formValues as $key => $values ) {

				$service_id = $key;

				if (isset ( $values ['select'] ) && $service_id > 0 && $ps_student_id > 0) {

					// Kiem tra dich vu nay dang ky chua
					$StudentServiceObj = Doctrine_Core::getTable ( 'StudentService' )->checkStudentServiceExits ( $ps_student_id, $service_id );

					if (! $StudentServiceObj) {

						$tanxuat = $values ['regularity_id'];

						if(is_array($array_regularity[$tanxuat])){
							$number_month = $array_regularity[$tanxuat]['number'];
							if($array_regularity[$tanxuat]['is_type'] == 0){
								$discount = $array_regularity[$tanxuat]['discount'];
								$discount_amount = 0;
							}else{
								$discount = 0;
								$discount_amount = $array_regularity[$tanxuat]['discount'];
							}
						}else{
							break;
						}

						//$discount = ($values ['discount'] > 0) ? $values ['discount'] : 0;

						//$discount_amount = (is_numeric ( $values ['discount_amount'] )) ? $values ['discount_amount'] : 0;

						$StudentService = new StudentService ( false );

						$StudentService->setStudentId ( $ps_student_id );

						$StudentService->setServiceId ( $service_id );

						// $StudentService->setDiscountAmount ( $discount_amount );

						// $StudentService->setDiscount ( $discount );

						$StudentService->setRegularityId ( $tanxuat );

						$StudentService->setNumberMonth ( $number_month );

						$StudentService->setUserCreatedId ( sfContext::getInstance ()->getUser ()
							->getGuardUser ()
							->getId () );

						$StudentService->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
							->getGuardUser ()
							->getId () );

						$StudentService->save ();
					}
				}
			}

			/*
			 * if (!$StudentService) { $this->getUser()->setFlash('error', 'Register service for your baby fail.'); } else { $this->getUser()->setFlash('notice', $notice); }
			 */

			$conn->commit ();

			$this->getUser ()
				->setFlash ( 'notice', $notice );

			$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_4' );
		} catch ( Doctrine_Validator_Exception $e ) {

			$conn->rollback ();

			$errorStack = $form->getObject ()
				->getErrorStack ();

			$message = get_class ( $form->getObject () ) . ' has ' . count ( $errorStack ) . " field" . (count ( $errorStack ) > 1 ? 's' : null) . " with validation errors: ";

			foreach ( $errorStack as $field => $errors ) {
				$message .= "$field (" . implode ( ", ", $errors ) . "), ";
			}

			$message = trim ( $message, ', ' );

			$this->getUser ()
				->setFlash ( 'error', $message );

			$this->redirect ( '@ps_students_edit?id=' . $ps_student_id . '#pstab_4' );
		}

		// $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $student_service)));

		// }

		// else {

		// $this->getUser()->setFlash('error', 'Teacher assignment failed.');

		// $this->redirect('@ps_students_edit?id=' . $ps_student_id . '#pstab_2');
		// }
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );

		if ($form->isValid ()) {

			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {

				//echo $form->getValue('regularity_id');die;

				$ps_student_service = $form->save ();



			} catch ( Doctrine_Validator_Exception $e ) {

				$errorStack = $form->getObject ()
					->getErrorStack ();

				$message = get_class ( $form->getObject () ) . ' has ' . count ( $errorStack ) . " field" . (count ( $errorStack ) > 1 ? 's' : null) . " with validation errors: ";

				foreach ( $errorStack as $field => $errors ) {
					$message .= "$field (" . implode ( ", ", $errors ) . "), ";
				}

				$message = trim ( $message, ', ' );

				$this->getUser ()->setFlash ( 'error', $message );

				return sfView::SUCCESS;
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $my_class ) ) );

			$this->getUser ()
				->setFlash ( 'notice', $notice );

			$this->redirect ( '@ps_students_edit?id=' . $ps_student_service->getStudentId () . '#pstab_4' );
		} else {

			$student_service_form = $request->getParameter ( $form->getName () );

			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );

			if ($student_service_form ['student_id'] > 0)
				$this->redirect ( '@ps_students_edit?id=' . $student_service_form ['student_id'] . '#pstab_4' );
			else
				exit ();
		}
	}

	/**
	 * * Phung Van Thanh **
	 */
	// Xoa dich vu cua hoc sinh - Xu ly bang ajax (chua lam)
	public function executeDeleteAjax(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {
			$rs_id = $request->getParameter ( 'rs_id' );
			// echo $rs_id;die();
			$student_service = Doctrine_Core::getTable ( 'StudentService' )->findOneById ( $rs_id );
			$delete_at = $student_service->getDeleteAt ();
			if ($delete_at != '') {
				$student_service->setDeleteAt ( null );
				$student_service->setUserDeletedId ( null );
				$student_service->setUserUpdatedId ( myUser::getUserId () );
				$student_service->save ();
			} else {
				$student_service->setDeleteAt ( date ( 'Y-m-d H:i:s' ) );
				$student_service->setUserDeletedId ( myUser::getUserId () );
				$student_service->setUserUpdatedId ( myUser::getUserId () );
				$student_service->save ();
			}
		}
	}

	// Chi tiet dich vu
	public function executeDetail(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$student_id = $request->getParameter ( 'sid' );

			$ps_school_year_id = $request->getParameter ( 'kstime' );

			if ($student_id > 0 && $ps_school_year_id > 0) {

				$this->student = Doctrine::getTable ( 'Student' )->getStudentByField ( $student_id,'last_name, first_name,student_code' );

				// Kiem tra quyen loc hoc sinh theo truong
				if (myUser::checkAccessObject ( $this->student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {

					// Lấy danh sach dich vu da dang ky
					//$this->list_service = Doctrine::getTable ( 'Service' )->getAllServicesOfStudentByYear ( $student_id, $ps_school_year_id );
					
					$current_date = date ("Y-m-d" );
					// dich vu da dang ky - Giong ben man hinh hoc sinh
					$this->list_service = Doctrine::getTable ( 'Service' )->getServicesStudentUsing ($student_id,$current_date);
					
					// Lay cac dich vu da hủy - giá hiển thị theo tai thơi diem huy dich vu
					$this->list_service_notusing = Doctrine::getTable ( 'StudentService' )->getServicesStudentNotUsing ($student_id,$current_date);
					
					//$this->kstime = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $ps_school_year_id );
				
				} else {
					return $this->renderPartial ( 'global/include/_box_modal_error_403404' );
				}
			} else {
				echo 'Error';
				return $this->renderPartial ( 'global/include/_box_modal_error_403404' );
				exit ();
			}
		} else {
			echo 'Error';
			exit ();
		}
	}

	protected function setReceivableStudentFilters(array $filters) {

		return $this->getUser ()
			->setAttribute ( 'psStudentService.registration_filters', $filters, 'admin_module' );
	}

	protected function getReceivableStudentFilters() {

		$filters = $this->getUser ()
			->getAttribute ( 'psStudentService.registration_filters', array (), 'admin_module' );

		return $filters;
	}

	// dang ky dich vu hang loat
	public function executeRegistration(sfWebRequest $request) {

		$this->ps_customer_id = $this->ps_school_year_id = '';
		$this->helper = new psStudentServiceGeneratorHelper ();

		$this->configuration = new psStudentServiceGeneratorConfiguration ();

		$this->formFilter = new sfFormFilter ();

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_FILTER_SCHOOL' )) {

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

		if ($request->isMethod ( 'post' )) {

			$value_filter = $request->getParameter ( 'student_filter' );

			$this->ps_customer_id = $value_filter ['ps_customer_id'];

			$this->ps_workplace_id = $value_filter ['ps_workplace_id'];

			$this->ps_school_year_id = $value_filter ['ps_school_year_id'];

			$this->class_id = $value_filter ['class_id'];

			// $this->setReceivableStudentFilters($value_filter);
		} else {

			$value_filter = $this->getReceivableStudentFilters ();

			if (count ( $value_filter )) {
				$this->ps_customer_id = $value_filter ['ps_customer_id'];

				$this->ps_workplace_id = $value_filter ['ps_workplace_id'];

				$this->ps_school_year_id = $value_filter ['ps_school_year_id'];

				$this->class_id = $value_filter ['class_id'];
			}
		}

		if ($this->ps_customer_id == '') {
			$this->ps_customer_id = myUser::getPscustomerID ();
			$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$this->ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		if ($this->ps_school_year_id == '') {
			$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId ();
		}

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

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

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

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
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		if ($this->ps_workplace_id > 0) {

			// Filters by class
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id,
							'ps_school_year_id' => $this->ps_school_year_id,
							'is_activated' 		=> PreSchool::ACTIVE
					) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => false ) ) );
		} else {

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorPass () );
		}

		$this->formFilter->setDefault ( 'class_id', $this->class_id );

		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'student_filter[%s]' );

		$this->pager = new sfDoctrinePager ( 'Student', 100 );
		$this->pager->setQuery ( Doctrine::getTable ( 'Student' )->getListStudentServiceByClass ( $this->ps_customer_id, $this->ps_workplace_id, $this->class_id ) );
		$this->pager->setPage ( $request->getParameter ( 'page', 1 ) );
		$this->pager->init ();
		$this->list_student = $this->pager->getResults ();

		// Lay danh sach cac khoan phai thu

		$value_filter = array (
				'ps_school_year_id' => $this->ps_school_year_id,
				'ps_customer_id' => $this->ps_customer_id,
				'ps_workplace_id' => $this->ps_workplace_id,
				'class_id' => $this->class_id );

		$this->setReceivableStudentFilters ( $value_filter );
		
		//$this->list_service = Doctrine::getTable ( "Service" )->getListServiceOfSchool ( $this->ps_school_year_id, $this->ps_customer_id );
		
		$this->list_service = Doctrine::getTable ( "Service" )->getListServiceForStudentServiceRegistration ( $this->ps_school_year_id, $this->ps_customer_id, $this->class_id );

		// Lấy danh sách tần xuất thu
		$this->psRegularity = Doctrine_Query::create()->from('PsRegularity')->execute();
	}

	// Dang ký dịch vụ theo lớp cho cac hoc sinh duoc chon
	public function executeRegistrationSave(sfWebRequest $request) {

		$ps_customer_id = $request->getParameter ( 'ps_customer_id' );

		$student_ids = $request->getParameter ( 'ids' );

		$control_filter = $request->getParameter ( 'control_filter' );

		$receivable_month = isset ( $control_filter ['receivable'] ) ? $control_filter ['receivable'] : null;

		if (! count ( $student_ids )) {

			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'You do not select students to perform' ) );

			$this->redirect ( '@ps_service_registration_student' );
		} elseif (! count ( $receivable_month )) {

			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'You do not select receivable to perform' ) );

			$this->redirect ( '@ps_service_registration_student' );
		}

		$list_student_ids = Doctrine::getTable ( 'Student' )->getStudentIdsRegistrationService ( $student_ids );

		if (count ( $list_student_ids ) <= 0) {

			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'Error list select student.' ) );

			$this->redirect ( '@ps_service_registration_student' );
		}

		// Danh sách tần xuất thu và giảm trừ
		$psRegularity = Doctrine_Query::create()->from('PsRegularity')->where('ps_customer_id =?',$ps_customer_id)->execute();

		$array_regularity = array();
		foreach($psRegularity as $regularity){
			$array_regularity[$regularity->getId()]['number'] = $regularity->getNumber();
			$array_regularity[$regularity->getId()]['discount'] = $regularity->getDiscount();
			$array_regularity[$regularity->getId()]['is_type'] = $regularity->getIsType();
		}

		if(count($array_regularity) <= 0){
			$this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ( 'Error list select student.' ) );
			$this->redirect ( '@ps_service_registration_student' );
		}

		//echo count($psRegularity);

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			foreach ( $list_student_ids as $_student ) {

				foreach ( $receivable_month as $key_id => $obj ) {

					if (isset ( $obj ['ids'] ) && $obj ['ids'] > 0) {

						$tanxuat = $obj['regularity'];

						if(is_array($array_regularity[$tanxuat])){
							$number_month = $array_regularity[$tanxuat]['number'];
							if($array_regularity[$tanxuat]['is_type'] == 0){
								$discount = $array_regularity[$tanxuat]['discount'];
								$discount_amount = 0;
							}else{
								$discount = 0;
								$discount_amount = $array_regularity[$tanxuat]['discount'];
							}
						}else{
							break;
						}

						// Kiem tra dich vu nay da ton tai chua
						$StudentServiceObj = Doctrine_Core::getTable ( 'StudentService' )->checkStudentServiceExits ( $_student->getId (), $key_id );

						if (! $StudentServiceObj) {

							//$discount = ($obj ['discount'] > 0) ? $obj ['discount'] : 0;

							//$discount_amount = (is_numeric ( $obj ['fixed'] )) ? $obj ['fixed'] : 0;

							$StudentService = new StudentService ( false );

							$StudentService->setStudentId ( $_student->getId () );

							$StudentService->setServiceId ( $key_id );

							// $StudentService->setDiscountAmount ( $discount_amount );

							// $StudentService->setDiscount ( $discount );

							$StudentService->setRegularityId ( $tanxuat );

							$StudentService->setNumberMonth ( $number_month );

							$StudentService->setUserCreatedId ( sfContext::getInstance ()->getUser ()
								->getGuardUser ()
								->getId () );

							$StudentService->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
								->getGuardUser ()
								->getId () );

							$StudentService->save ();
						}else{

							// $StudentServiceObj->setDiscountAmount ( $discount_amount );

							// $StudentServiceObj->setDiscount ( $discount );

							$StudentServiceObj->setRegularityId ( $tanxuat );

							$StudentServiceObj->setNumberMonth ( $number_month );

							$StudentServiceObj->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
								->getGuardUser ()
								->getId () );

							$StudentServiceObj->save ();
						}
					}
				}
			}

			$conn->commit ();
		} catch ( Exception $e ) {

			$conn->rollback ();

			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'Add receivable for month fail.' ) );

			$this->redirect ( '@ps_service_registration_student' );
		}

		$this->getUser ()
			->setFlash ( 'notice', $this->getContext ()
			->getI18N ()
			->__ ( 'Add receivable for month successfully.' ) );

		$this->redirect ( '@ps_service_registration_student' );
	}

	public function executeRegistrationSearch(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {
			$year_id = $request->getParameter ( 'year_id' );
			$cus_id = $request->getParameter ( 'cus_id' );
			$wp_id = $request->getParameter ( 'wp_id' );
			$c_id = $request->getParameter ( 'c_id' );
			$this->pager = new sfDoctrinePager ( 'Student', 10 );
			$this->pager->setQuery ( Doctrine::getTable ( 'Student' )->getListStudentServiceByClass ( $cus_id, $wp_id, $c_id ) );
			$this->pager->setPage ( $request->getParameter ( 'page', 1 ) );
			$this->pager->init ();

			$this->list_student = $this->pager->getResults ();

			$file_result = ($html == 'table') ? 'table_relative' : 'list_relative_main';

			return $this->renderPartial ( 'psStudentService/list_student', array (
					'list_student' => $this->list_student,
					'ktime' => $year_id,
					'pager' => $this->pager ) );
		} else {
			exit ( 0 );
		}
	}
}
