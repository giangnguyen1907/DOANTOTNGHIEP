<?php
require_once dirname ( __FILE__ ) . '/../lib/psSubjectsGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psSubjectsGeneratorHelper.class.php';

/**
 * psSubjects actions.
 *
 * @package kidsschool.vn
 * @subpackage psSubjects
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psSubjectsActions extends autoPsSubjectsActions {

	public function executeNew(sfWebRequest $request) {

		// $this->form = $this->configuration->getForm();
		$this->service = new Service (); // $this->form->getObject();

		$this->service->setEnableSchedule ( PreSchool::ACTIVE );

		$this->form = $this->configuration->getForm ( $this->service );
	}

	public function executeCreate(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->service = $this->form->getObject ();

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'new' );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->service = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( (myUser::checkAccessObject ( $this->service, 'PS_STUDENT_SUBJECT_FILTER_SCHOOL' ) && $this->service->getEnableSchedule () == PreSchool::ACTIVE), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->service );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->service = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( (myUser::checkAccessObject ( $this->service, 'PS_STUDENT_SUBJECT_FILTER_SCHOOL' ) && $this->service->getEnableSchedule () == PreSchool::ACTIVE), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->service );

		$this->form->loadRowSeviceDetailTemplate ();

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	public function executeShow(sfWebRequest $request) {

		$this->service = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( (myUser::checkAccessObject ( $this->service, 'PS_STUDENT_SUBJECT_FILTER_SCHOOL' ) && $this->service->getEnableSchedule () == PreSchool::ACTIVE), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->service );

		$this->setTemplate ( 'edit' );
	}

	public function executeAddDetail(sfWebRequest $request) {

		// $this->forward404unless($request->isXmlHttpRequest());
		if ($this->getRequest ()
			->isXmlHttpRequest ()) {

			$number = intval ( $request->getParameter ( "num" ) );

			$this->form = new ServiceForm ();

			$this->form->addNewFields ( $number );

			return $this->renderPartial ( 'add_detail', array (
					'form' => $this->form,
					'number' => $number ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeSubmit(sfWebRequest $request) {

		$tainted_values = $request->getParameter ( 'service' );

		$serviceId = $tainted_values ['id'];

		$this->service = Doctrine::getTable ( 'Service' )->find ( $tainted_values ['id'] );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->service, 'PS_STUDENT_SUBJECT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = new ServiceForm ( $this->service );

		if ($request->isMethod ( 'post' )) {
			if ($this->form->bindAndSave ( $tainted_values, $taintedFiles = null, $con = null )) {
				if ($serviceId) {
					$this->getUser ()
						->setFlash ( 'notice', 'Update successfully' );
					$this->redirect ( '@ps_service_edit?id=' . $tainted_values ['id'] );
				} else {
					$this->getUser ()
						->setFlash ( 'notice', 'The item was created successfully.' );
				}
				$this->redirect ( 'ps_service/index' );
			} else {
				$this->getUser ()
					->setFlash ( 'error', 'Update failed' );
			}
		}
		$this->setTemplate ( 'edit' );
	}

	public function executeServiceGroup(sfWebRequest $request) {

		if ($this->getRequest ()
			->isXmlHttpRequest ()) {

			$psc_id = intval ( $request->getParameter ( "psc_id" ) );

			$serviceGroups = Doctrine::getTable ( 'ServiceGroup' )->setSQLServiceGroup ( 'id, title', $psc_id )
				->execute ();

			return $this->renderPartial ( 'option_service_group', array (
					'serviceGroups' => $serviceGroups ) );
		} else {
			exit ( 0 );
		}
	}

	protected function executeBatchUpdateOrder(sfWebRequest $request) {

		$iorder = $request->getParameter ( 'iorder' );

		if (! count ( $iorder )) {
			// $this->getUser()->setFlash('error', $this->getContext()->getI18N()->__('Update failed'));
			$this->getUser ()
				->setFlash ( 'error', 'You must at least select one item.' );
		} else {

			$conn = Doctrine_Manager::connection ();
			try {

				$conn->beginTransaction ();

				if (myUser::credentialPsCustomers ( 'PS_STUDENT_SUBJECT_FILTER_SCHOOL' )) {
					foreach ( $iorder as $key => $value ) {
						if (! is_numeric ( $value )) {
							$this->getUser ()
								->setFlash ( 'error', 'Is not a number' );
							break;
						} else {
							$obj = Doctrine::getTable ( 'Service' )->findOneById ( $key );
							$obj->setIorder ( $value );
							$obj->setUserUpdatedId ( $this->getUser ()
								->getUserId () );
							$obj->setUpdatedAt ( date ( 'Y-m-d H:i:s', time () ) );
							$obj->save ();
						}
					}
				} else {
					foreach ( $iorder as $key => $value ) {
						if (! is_numeric ( $value )) {
							$this->getUser ()
								->setFlash ( 'error', 'Is not a number' );
							break;
						} else {
							$obj = Doctrine::getTable ( 'Service' )->findOneById ( $key );
							if ($obj->getPsCustomerId () == myUser::getPscustomerID ()) {
								$service->setIorder ( $value );
								$obj->setUserUpdatedId ( $this->getUser ()
									->getUserId () );
								$obj->setUpdatedAt ( date ( 'Y-m-d H:i:s', time () ) );
								$obj->save ();
							}
						}
					}
				}
				$this->getUser ()
					->setFlash ( 'notice', $this->getContext ()
					->getI18N ()
					->__ ( 'The item was updated successfully.' ) );
				$conn->commit ();
			} catch ( Exception $e ) {
				throw new Exception ( $e->getMessage () );
				$conn->rollback ();
				$this->getUser ()
					->setFlash ( 'error', $this->getContext ()
					->getI18N ()
					->__ ( 'Update failed' ) );
			}
		}
		$this->redirect ( '@ps_service' );
	}

	public function executeByCustomer(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$ps_customer_id = $request->getParameter ( 'psc_id' );

			$this->form = new MyClassForm ();

			$this->form->addServiceExpandedForm ( 'services_list', $ps_customer_id );

			echo $this->form ['services_list']->render ();
			exit ( 0 );
			// return $this->renderPartial('PsClass/form_field_services', array ('form' => $this->form));
		} else {
			exit ( 0 );
		}
	}

	// Action xu ly khi submit form
	public function processForm(sfWebRequest $request, sfForm $form) {

		$serviceValueForm = $request->getParameter ( $form->getName () );

		if (isset ( $serviceValueForm ['id'] ) && $serviceValueForm ['id'] > 0) {

			// Check quyen voi Service nay
			$service = Doctrine::getTable ( 'Service' )->find ( $serviceValueForm ['id'] );
			$this->forward404Unless ( myUser::checkAccessObject ( $service, 'PS_STUDENT_SUBJECT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		}

		// Kiem tra du lieu ServiceDetail muon Remove da duoc su dung chua
		if (isset ( $serviceValueForm ['ServiceDetail'] )) {
			foreach ( $serviceValueForm ['ServiceDetail'] as $i => $bookmarkValues ) {
				if (isset ( $bookmarkValues ['delete'] ) && $bookmarkValues ['id']) {

					// Kiem tra id $bookmarkValues['id']

					// Neu da ton tai thi thong bao ko xoa
					// $this->getUser()->setFlash('warning', $this->getContext()->getI18N()->__('Information "Price information and the time apply" has generated data. Can not delete.'));

					// $this->redirect(array('sf_route' => 'ps_service_edit', 'sf_subject' => $service));
				}
			}
		}

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {
				$service = $form->save ();
				$service->setEnableSchedule ( PreSchool::ACTIVE );
				$service->save ();
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
					'object' => $service ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				$this->redirect ( '@ps_subjects_new' );
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_subjects_edit',
						'sf_subject' => $service ) );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$service = $this->getRoute ()
			->getObject ();

		// Check role tac dong den id
		$this->forward404Unless ( (myUser::checkAccessObject ( $service, 'PS_STUDENT_SUBJECT_FILTER_SCHOOL' ) && $service->getEnableSchedule () == PreSchool::ACTIVE), sprintf ( 'Object does not exist.' ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $service ) ) );

		// Chech du lieu da duoc su dung

		// StudentService
		$number_student_services = $service->getCountStudentService ();

		// StudentServiceDiary - dung khi diem danh
		$number_student_service_diarys = $service->getCountStudentServiceDiary ();

		// ClassService - da dang ky su dung cho lop hoc
		$number_class_services = $service->getCountClassService ();

		// Dem so khoa hoc da mo cho mon hoc nay
		$number_service_courses = $service->getCountPsServiceCourses ();

		// StudentFeeTemp ?
		if ($number_student_services > 0 || $number_student_service_diarys > 0 || $number_class_services > 0 || $number_service_courses > 0) {

			// $number = 'student_services:'.$number_student_services.' - student_service_diarys:'.count($student_service_diarys).' '.count($class_services);

			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'This service has generated data. Can not delete.' ) );

			$this->redirect ( array (
					'sf_route' => 'ps_subjects_edit',
					'sf_subject' => $service ) );
		} else {

			if ($this->getRoute ()
				->getObject ()
				->delete ()) {
				$this->getUser ()
					->setFlash ( 'notice', 'The item was deleted successfully.' );
			} else {
				$this->getUser ()
					->setFlash ( 'error', 'System an error' );
			}
		}

		$this->redirect ( '@ps_subjects' );
	}

	public function executeDetail(sfRequest $request) {

		if ($request->isXmlHttpRequest ()) {
			$this->subject_detail = $this->getRoute ()
				->getObject ();
			$this->service_details = Doctrine::getTable ( 'ServiceDetail' )->getServiceDetailById ( $this->subject_detail->getId () );
		} else
			exit ( 0 );
	}
}
