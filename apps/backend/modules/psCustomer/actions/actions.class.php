<?php
require_once dirname ( __FILE__ ) . '/../lib/psCustomerGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psCustomerGeneratorHelper.class.php';

/**
 * psCustomer actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psCustomer
 * @author quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version 1.0
 */
class psCustomerActions extends autoPsCustomerActions {

	// Lay Don vi theo Xa - Phuong
	public function executePsCustomerWard(sfWebRequest $request) {

		if ($this->getRequest ()->isXmlHttpRequest ()) {

			$wid = intval ( $request->getParameter ( "wid" ) );

			$ps_customers = Doctrine::getTable ( 'PsCustomer' )->getCustomersByPsWardId ( $wid );

			$this->forward404Unless ( $ps_customers, sprintf ( 'Object (%s) does not exist .', $wid ) );

			return $this->renderPartial ( 'option_select', array (
					'option_select' => $ps_customers
			) );
		} else {
			exit ( 0 );
		}

	}

	public function executeLock(sfWebRequest $request) {

		/*
		 * $ps_customer = $this->getRoute()->getObject();
		 * $ps_customer->setIsActivated(2);
		 * $ps_customer->setUserUpdatedId($this->getUser()->getUserId());
		 * $ps_customer->setUpdatedAt(date('Y-m-d H:i:s',time()));
		 * $ps_customer->save();
		 * $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $ps_customer)));
		 * $this->getUser()->setFlash('notice', 'The item was updated successfully.');
		 */
		$this->redirect ( '@ps_customer' );

	}

	// Xoa thong tin truong hoc
	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$obj = $this->getRoute ()->getObject ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $obj
		) ) );

		if ($obj->getIsRoot ())
			$this->forward404Unless ( true, sprintf ( 'Object does not exist .' ) );

		$notice = array ();

		$number_PsCmsArticles = $obj->getNumberObject ( 'PsCmsArticles' );

		if ($number_PsCmsArticles > 0) {
			array_push ( $notice, $this->getContext ()->getI18N ()->__ ( 'Article' ) );
		}

		if ($obj->getNumberStudent () > 0) {
			array_push ( $notice, $this->getContext ()->getI18N ()->__ ( 'Student' ) );
		}

		if ($obj->getNumberMember () > 0) {
			array_push ( $notice, $this->getContext ()->getI18N ()->__ ( 'HR' ) );
		}

		if ($obj->getNumberWorkPlaces () > 0) {
			array_push ( $notice, $this->getContext ()->getI18N ()->__ ( 'Work places' ) );
		}

		if ($obj->getNumberServiceGroup () > 0) {
			array_push ( $notice, $this->getContext ()->getI18N ()->__ ( 'Service group' ) );
		}

		if ($obj->getNumberService () > 0) {
			array_push ( $notice, $this->getContext ()->getI18N ()->__ ( 'Service' ) );
		}

		$number_Feature = $obj->getNumberObject ( 'Feature' );
		if ($number_Feature > 0) {
			array_push ( $notice, $this->getContext ()->getI18N ()->__ ( 'Feature' ) );
		}

		$number_FeatureOption = $obj->getNumberObject ( 'FeatureOption' );
		if ($number_FeatureOption > 0) {
			array_push ( $notice, $this->getContext ()->getI18N ()->__ ( 'Feature option' ) );
		}

		$number_PsFoods = $obj->getNumberObject ( 'PsFoods' );
		if ($number_PsFoods > 0) {
			array_push ( $notice, $this->getContext ()->getI18N ()->__ ( 'Foods' ) );
		}

		$number_PsMeals = $obj->getNumberObject ( 'PsMeals' );
		if ($number_PsMeals > 0) {
			array_push ( $notice, $this->getContext ()->getI18N ()->__ ( 'Meals' ) );
		}

		$number_PsMenus = $obj->getNumberObject ( 'PsMenus' );
		if ($number_PsMenus > 0) {
			array_push ( $notice, $this->getContext ()->getI18N ()->__ ( 'Menus' ) );
		}

		$number_ps_function = $obj->getNumberObject ( 'PsFunction' );
		if ($number_ps_function > 0) {
			array_push ( $notice, $this->getContext ()->getI18N ()->__ ( 'Category position' ) );
		}

		$number_PsProfessional = $obj->getNumberObject ( 'PsProfessional' );
		if ($number_PsProfessional > 0) {
			array_push ( $notice, $this->getContext ()->getI18N ()->__ ( 'Category professional' ) );
		}

		$number_PsDepartment = $obj->getNumberObject ( 'PsDepartment' );
		if ($number_PsDepartment > 0) {
			array_push ( $notice, $this->getContext ()->getI18N ()->__ ( 'Category department' ) );
		}

		// Kiem tra dieu kien xoa
		/**
		 * Kiem tra du lieu Hoc sinh, Nguoi than, nhan su, nhom dich vu, dich vu, co so dao tao
		 */
		// Kiem tra du lieu nguoi dung
		if (! $notice) {

			try {

				// Thong tin cac thu muc, du lieu
				$path_file_logo = sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $obj->getYearData () . '/' . $obj->getLogo ();

				$path_cache_data = sfConfig::get ( 'app_ps_data_cache_dir' ) . '/' . $obj->getCacheData ();

				$path_file_data = sfConfig::get ( 'app_ps_data_dir' ) . '/' . $obj->getSchoolCode ();

				if ($this->getRoute ()->getObject ()->delete ()) {

					if (is_file ( $path_file_logo )) {
						unlink ( $path_file_logo );
					}

					if ($obj->getCacheData () != '')
						Doctrine_Lib::removeDirectories ( $path_cache_data );

					if ($obj->getSchoolCode () != '')
						Doctrine_Lib::removeDirectories ( $path_file_data );

					// Xoa du lieu: Camera, Phong hoc

					$this->getUser ()->setFlash ( 'notice', 'The item was deleted successfully.' );
				}

				$this->getUser ()->setFlash ( 'notice', 'The item was deleted successfully.' );
			} catch ( Exception $e ) {
			}
		} else {
			$this->getUser ()->setFlash ( 'danger', $this->getContext ()->getI18N ()->__ ( 'The school is holding the relevant data:', array (), 'sf_admin' ) . " " . implode ( ", ", $notice ) );
		}

		$this->redirect ( '@ps_customer' );

	}

	public function executeView(sfWebRequest $request) {

		$customer_id = myUser::getPscustomerID ();

		if ($customer_id <= 0) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
		}

		// lay thong tin truong hoc
		$this->customer_detail = Doctrine::getTable ( 'psCustomer' )->getCustomerById ( $customer_id );
		
		// Lay thong tin co so
		$this->work_places_number = Doctrine::getTable ( 'psWorkPlaces' )->getNumberWorkPlacesByCustomerId ( $customer_id );
		$this->work_places = Doctrine::getTable ( 'psWorkPlaces' )->getWorkPlacesByCustomerId ( $customer_id );

	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_customer = $this->getRoute ()->getObject ();
		
		if (($this->ps_customer->getIsRoot () == PreSchool::ACTIVE) && ! myUser::isAdministrator ()) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );		
		} else {
			
			if (!myUser::credentialPsCustomers ( 'PS_SYSTEM_CUSTOMER_FILTER_SCHOOL' ) && $this->ps_customer->getId() != myUser::getPscustomerID ()) {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
			}
		}

		$this->form = $this->configuration->getForm ( $this->ps_customer );

	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		if ($form->isValid ()) {
			$is_new = $form->getObject ()->isNew ();

			try {

				$ps_customer = $form->save ();

				if ($is_new) {

					$notice = 'The item was created successfully.';

					$renderCode = 'KS' . PreSchool::renderCode ( "%010s", $ps_customer->getId () );
					$ps_customer->setSchoolCode ( $renderCode );
					$ps_customer->setCacheData ( $renderCode );

					$ps_customer->save ();

					// $ps_customer->setYearData($ps_customer->getYearData());

					$root_customer_path = sfConfig::get ( 'app_ps_data_dir' ) . '/' . $renderCode;

					Doctrine_Lib::makeDirectories ( $root_customer_path );

					// Tao folder chua du lieu cua khach hang
					Doctrine_Lib::copyDirectory ( sfConfig::get ( 'app_ps_sch_code_dir' ), $root_customer_path . '/' . $ps_customer->getYearData () );
				} else {
					$notice = 'The item was updated successfully.';
				}
			} catch ( Doctrine_Validator_Exception $e ) {

				$errorStack = $form->getObject ()->getErrorStack ();

				$message = get_class ( $form->getObject () ) . ' has ' . count ( $errorStack ) . " field" . (count ( $errorStack ) > 1 ? 's' : null) . " with validation errors: ";
				foreach ( $errorStack as $field => $errors ) {
					$message .= "$field (" . implode ( ", ", $errors ) . "), ";
				}
				$message = trim ( $message, ', ' );

				$this->getUser ()->setFlash ( 'error', $message );
				return sfView::SUCCESS;
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $ps_customer
			) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()->setFlash ( 'notice', $notice . ' You can add another one below.' );

				$this->redirect ( '@ps_customer_new' );
			} else {
				$this->getUser ()->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_customer_edit',
						'sf_subject' => $ps_customer
				) );
			}
		} else {
			$this->getUser ()->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}

	}

	public function executeDetail(sfWebRequest $request) {

		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
			$this->filter_value = $this->getFilters ();

			$customer_id = $request->getParameter ( 'id' );

			if ($customer_id <= 0) {
				$this->setTemplate('detailError404','psCpanel');
			}
			// lay thong tin truong hoc
			$this->customer_detail = Doctrine::getTable ( 'psCustomer' )->getCustomerById ( $customer_id );

			if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_CUSTOMER_FILTER_SCHOOL' )) {
				if($customer_id != myUser::getPscustomerID ()){
					$this->setTemplate('detailError404','psCpanel');
				}
			}

			// lay thong tin co so
			$this->work_places_number = Doctrine::getTable ( 'psWorkPlaces' )->getNumberWorkPlacesByCustomerId ( $customer_id );

			$this->work_places = Doctrine::getTable ( 'psWorkPlaces' )->getWorkPlacesByCustomerId ( $customer_id );

			//$this->updatephone ( $is_object = 1 );
		} else {

			//$this->updatephone ( $is_object = 2 );
			$this->setTemplate('detailError404','psCpanel');
			//exit ( 0 );
		}

	}

	protected function updatephone($is_object = 1) {

		if ($is_object == 1) { // cap nhat so dien thoai cua nhan su

			$list_teachers = Doctrine_Query::create ()->from ( 'PsMember' )->execute ();
		} elseif ($is_object == 2) { // cap nhat so dien thoai cua phu huynh

			$list_teachers = Doctrine_Query::create ()->from ( 'Relative' )->execute ();
		}

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			foreach ( $list_teachers as $teachers ) {
				$datas = $teachers->getMobile ();
				$mobile = PreString::strReplace ( $datas, array (
						'.',
						',',
						';',
						' ' ) );
				if (strlen ( $mobile ) == 11) {
					$first = substr ( $mobile, 0, 3 );
					$secon = substr ( $mobile, 3, 11 );

					if ($first == '016') {
						$doi = str_replace ( "016", "03", $first );
					} else {

						$first = substr ( $mobile, 0, 4 );
						$secon = substr ( $mobile, 4, 11 );

						if ($first == '0120') {
							$doi = str_replace ( "0120", "070", $first );
						} elseif ($first == '0121') {
							$doi = str_replace ( "0121", "079", $first );
						} elseif ($first == '0122') {
							$doi = str_replace ( "0122", "077", $first );
						} elseif ($first == '0126') {
							$doi = str_replace ( "0126", "076", $first );
						} elseif ($first == '0128') {
							$doi = str_replace ( "0128", "078", $first );
						} elseif ($first == '0123') {
							$doi = str_replace ( "0123", "083", $first );
						} elseif ($first == '0124') {
							$doi = str_replace ( "0124", "084", $first );
						} elseif ($first == '0125') {
							$doi = str_replace ( "0125", "085", $first );
						} elseif ($first == '0127') {
							$doi = str_replace ( "0127", "081", $first );
						} elseif ($first == '0129') {
							$doi = str_replace ( "0129", "082", $first );
						} elseif ($first == '0186') {
							$doi = str_replace ( "0186", "056", $first );
						} elseif ($first == '0188') {
							$doi = str_replace ( "0188", "058", $first );
						} elseif ($first == '0199') {
							$doi = str_replace ( "0199", "059", $first );
						}
					}

					$new = $doi . $secon;
					$teachers->setMobile ( $new );
					$teachers->save ();
				} else {
					$teachers->setMobile ( $mobile );
					$teachers->save ();
				}
			}

			$conn->commit ();
		} catch ( Exception $e ) {

			$conn->rollback ();

			$error_import = $this->getContext ()
				->getI18N ()
				->__ ( 'Update mobile failed.' );

			$this->getUser ()
				->setFlash ( 'error', $error_import );

			$this->redirect ( '@ps_customer_update_mobile' );
		}
	}
}