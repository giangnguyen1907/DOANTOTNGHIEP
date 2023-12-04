<?php
require_once dirname ( __FILE__ ) . '/../lib/psServiceGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psServiceGeneratorHelper.class.php';

/**
 * psService actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psService
 * @author quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version 1.0
 */
class psServiceActions extends autoPsServiceActions {

	public function executeLoadService(sfWebRequest $request) {
		
		if ($request->isXmlHttpRequest ()) {
			
			$ps_customer_id = $request->getParameter ( 'c_id' );
			$ps_workplace_id = $request->getParameter ( 'w_id' );
			$ps_school_year_id = $request->getParameter ( 'y_id' );
			
			return $this->renderPartial ( 'psService/group_service', array (
					'ps_customer_id' => $ps_customer_id,
					'ps_workplace_id' => $ps_workplace_id,
					'ps_school_year_id' => $ps_school_year_id
			) );
			
		} else {
			exit ( 0 );
		}
	}
	
	public function executeServiceMyclassId(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$class_id = $request->getParameter ( 'myclass_id' );
			$student_id = $request->getParameter ( 'student_id' );
			$ps_customer_id = $request->getParameter ( 'ps_customer_id' );

			if ($class_id > 0 && $student_id > 0 && $ps_customer_id > 0)

				$this->list_service = Doctrine::getTable ( 'Service' )->getListServicesForAddNew ( $student_id, $ps_customer_id, $class_id );

			else
				$this->list_service = array ();

			return $this->renderPartial ( 'psStudentClass/table_service', array (
					'list_service' => $this->list_service ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeNew(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->service = $this->form->getObject ();
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

		$this->forward404Unless ( (myUser::checkAccessObject ( $this->service, 'PS_STUDENT_SERVICE_FILTER_SCHOOL' ) && ($this->service->getEnableSchedule () == PreSchool::NOT_ACTIVE)), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->service );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->service = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( (myUser::checkAccessObject ( $this->service, 'PS_STUDENT_SERVICE_FILTER_SCHOOL' ) && ($this->service->getEnableSchedule () == PreSchool::NOT_ACTIVE)), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->service );

		$this->form->loadRowSeviceDetailTemplate ();

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	public function executeShow(sfWebRequest $request) {

		$this->service = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( (myUser::checkAccessObject ( $this->service, 'PS_STUDENT_SERVICE_FILTER_SCHOOL' ) && ($this->service->getEnableSchedule () == PreSchool::NOT_ACTIVE)), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->service );

		$this->setTemplate ( 'edit' );
	}

	public function executeAddDetail(sfWebRequest $request) {

		$this->forward404unless ( $request->isXmlHttpRequest () );

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

	public function executeDetail(sfWebRequest $request) {

		$service_id = $request->getParameter ( 'id' );

		if ($service_id <= 0) {

			$this->forward404Unless ( $service_id, sprintf ( 'Object does not exist.' ) );
		}
		// lay thong tin bai viet
		$this->services = Doctrine::getTable ( 'Service' )->getServiceById ( $service_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->services, 'PS_STUDENT_SERVICE_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->service ) );

		$this->service_details = Doctrine::getTable ( 'ServiceDetail' )->getServiceDetailById ( $service_id );
		$this->service_splits = Doctrine::getTable ( 'ServiceSplit' )->findByServiceId ( $service_id );
		$this->amount = Doctrine::getTable ( 'ServiceDetail' )->getLatestAmountById ( $service_id );
	}

	public function executeSubmit(sfWebRequest $request) {

		$tainted_values = $request->getParameter ( 'service' );

		$serviceId = $tainted_values ['id'];

		$this->service = Doctrine::getTable ( 'Service' )->find ( $tainted_values ['id'] );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->service, 'PS_STUDENT_SERVICE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = new ServiceForm ( $this->service );

		if ($request->isMethod ( 'post' )) {
			if ($this->form->bindAndSave ( $tainted_values )) {
				if ($serviceId) {
					
					if ($request->hasParameter ( '_save_and_add' )) {
						
						$this->getUser ()->setFlash ( 'notice', 'The item was created successfully. You can add another one below.' );
						
						$this->redirect ( '@ps_service_new');
					
					} else {
						$this->getUser ()->setFlash ( 'notice', 'Update successfully' );
						$this->redirect ( '@ps_service_edit?id=' . $tainted_values ['id'] );
					}					
				} else {
					$this->getUser ()->setFlash ( 'notice', 'The item was created successfully.' );
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

		// $this->forward404unless($request->isXmlHttpRequest());
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

	/*
	 * public function executeUpdateOrder(sfWebRequest $request) {
	 * if ($this->getRequest()->isXmlHttpRequest()) {
	 * $iorder = $request->getParameter('iorder');
	 * if (!count($iorder)) {
	 * $this->getUser()->setFlash('error_message', $this->getContext()->getI18N()->__('Update failed'));
	 * } else {
	 * $conn = Doctrine_Manager :: connection();
	 * try {
	 * $conn->beginTransaction();
	 * foreach ( $iorder as $key => $value ) {
	 * if (!is_numeric($value)) {
	 * $this->getUser()->setFlash('error_message', $this->getContext()->getI18N()->__('Is not a number'));
	 * break;
	 * } else {
	 * $service = Doctrine::getTable('Service')->find($key);
	 * $service->setIorder($value);
	 * $service->save();
	 * }
	 * }
	 * $this->getUser()->setFlash('message', $this->getContext()->getI18N()->__('Update successfully'));
	 * $conn->commit();
	 * } catch (Exception $e) {
	 * throw new Exception($e->getMessage());
	 * $conn->rollback();
	 * $this->getUser()->setFlash('error_message', $this->getContext()->getI18N()->__('Update failed'));
	 * }
	 * }
	 * $query = Doctrine_Core :: getTable('Service')->createQuery('s')->leftJoin('s.ServiceGroup sg')->addOrderBy('sg.iorder ASC,s.iorder ASC');
	 * $services = $query->execute();
	 * return $this->renderPartial('service/list', array ('services' => $services));
	 * }
	 * }
	 */
	protected function executeBatchUpdateOrder(sfWebRequest $request) {

		$iorder = $request->getParameter ( 'iorder' );

		if (! count ( $iorder )) {
			// $this->getUser()->setFlash('error', $this->getContext()->getI18N()->__('Update failed'));
			$this->getUser ()->setFlash ( 'error', 'You must at least select one item.' );
		} else {

			$conn = Doctrine_Manager::connection ();
			try {

				$conn->beginTransaction ();
				
				if (myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_FILTER_SCHOOL' )) {
					
					foreach ( $iorder as $key => $value ) {
						
						if (!is_numeric ( $value )) {
							$this->getUser ()->setFlash ( 'error', 'Is not a number' );
							break;
						} else {
							$obj = Doctrine::getTable ( 'Service' )->findOneById ( $key );
							$obj->setIorder ( $value );
							$obj->setUserUpdatedId ( $this->getUser ()->getUserId () );
							$obj->setUpdatedAt ( date ( 'Y-m-d H:i:s', time () ) );
							$obj->save ();
						}
					}
				} else {
					foreach ( $iorder as $key => $value ) {
						if (! is_numeric ( $value )) {
							$this->getUser ()->setFlash ( 'error', 'Is not a number' );
							break;
						} else {
							$obj = Doctrine::getTable ( 'Service' )->findOneById ( $key );
							if ($obj->getPsCustomerId () == myUser::getPscustomerID ()) {
								$service->setIorder ( $value );
								$obj->setUserUpdatedId ( $this->getUser ()->getUserId () );
								$obj->setUpdatedAt ( date ( 'Y-m-d H:i:s', time () ) );
								$obj->save ();
							}
						}
					}
				}

				$this->getUser ()->setFlash ( 'notice', $this->getContext ()->getI18N ()->__ ( 'The item was updated successfully.' ) );
				
				$conn->commit ();
				
			} catch ( Exception $e ) {
				throw new Exception ( $e->getMessage () );
				$conn->rollback ();
				$this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ( 'Update failed' ) );
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

			$this->forward404Unless ( myUser::checkAccessObject ( $service, 'PS_STUDENT_SERVICE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

			/** Thành cập nhật tạm thời rào lại **/
			
			/*
			$is_default = $service->getIsDefault(); // Mặc định do trường đăng ký. tự động đăng ký cho học sinh luôn

			
			if($is_default == 1){

				$ps_customer_id = $service->getPsCustomerId();
				$ps_workplace_id = $service->getPsWorkplaceId();
				$class_id = $service->getClassId();
				$caphoc = $service->getCaphoc();
				$chuongtrinh = $service->getChuongtrinh();
				$khoihoc = $service->getKhoihoc();
				$doituong = $service->getDoituong();
				
				$params = array(
					'ps_customer_id'=>$ps_customer_id,
					'ps_workplace_id'=>$ps_workplace_id,
					'class_id'=>$class_id,
					'caphoc' => $caphoc,
					'chuongtrinh' => $chuongtrinh,
					'khoihoc' => $khoihoc,
					'doituong' => $doituong,
				);
				
				$layTanXuatMacDinh = Doctrine_Query::create()->from('PsRegularity')
				->addWhere('ps_customer_id =?',$ps_customer_id)
				->andWhere('is_default = 1')->fetchOne();
				
				if($layTanXuatMacDinh){
					$number_month = $layTanXuatMacDinh->getNumber();
					$regularity_id = $layTanXuatMacDinh->getId();
				}else{
					$number_month = 1;	
					$regularity_id = null;
				}

				// Lấy danh sách học sinh theo cơ sở hoặc theo lớp để đăng ký dịch vụ tự động
				$ps_list_students = Doctrine::getTable('Student')->getAllStudentsByParams($params);
				
				$service_id = $serviceValueForm ['id'];
				
				foreach($ps_list_students as $listStudent){
					
					$ps_student_id = $listStudent->getId();
					
					// Kiểm tra xem dịch vụ này đã đăng ký hay chưa
					$StudentServiceObj = Doctrine_Core::getTable ( 'StudentService' )->checkStudentServiceExits ( $ps_student_id, $service_id );

					if (! $StudentServiceObj) {
						
						$discount = 0;

						$discount_amount = 0;

						//$number_month = 1; // Tần xuất thu mặc định 1 tháng

						$StudentService = new StudentService ( false );

						$StudentService->setStudentId ( $ps_student_id );

						$StudentService->setServiceId ( $service_id );

						$StudentService->setDiscountAmount ( $discount_amount );

						$StudentService->setDiscount ( $discount );

						$StudentService->setRegularityId ( $regularity_id );

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
			*/

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

		parent::processForm ( $request, $form );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$service = $this->getRoute ()
			->getObject ();

		// Check role tac dong den id
		$this->forward404Unless ( (myUser::checkAccessObject ( $service, 'PS_STUDENT_SERVICE_FILTER_SCHOOL' ) && ($service->getEnableSchedule () == PreSchool::NOT_ACTIVE)), sprintf ( 'Object does not exist.' ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $service ) ) );

		// Chech du lieu da duoc su dung

		// StudentService
		$number_student_services = $service->getCountStudentService ();

		// StudentServiceDiary - dung khi diem danh
		$number_student_service_diarys = $service->getCountStudentServiceDiary ();

		// ClassService - da dang ky su dung cho lop hoc
		$number_class_services = $service->getCountClassService ();

		// StudentFeeTemp ?
		if ($number_student_services > 0 || $number_student_service_diarys > 0 || $number_class_services > 0) {

			// $number = 'student_services:'.$number_student_services.' - student_service_diarys:'.count($student_service_diarys).' '.count($class_services);

			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'This service has generated data. Can not delete.' ) );

			$this->redirect ( array (
					'sf_route' => 'ps_service_edit',
					'sf_subject' => $service ) );
		} else {

			try {
				if ($this->getRoute ()
					->getObject ()
					->delete ()) {
					$this->getUser ()
						->setFlash ( 'notice', 'The item was deleted successfully.' );
				} else {
					$this->getUser ()
						->setFlash ( 'error', 'System an error' );
				}
			} catch ( Exception $e ) {
				$this->getUser ()
					->setFlash ( 'error', 'The item has not been remove due have data related.' );
			}
		}

		$this->redirect ( '@ps_service' );
	}
}	

