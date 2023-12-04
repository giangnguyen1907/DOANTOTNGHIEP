<?php
require_once dirname ( __FILE__ ) . '/../lib/psFeatureOptionSubjectGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psFeatureOptionSubjectGeneratorHelper.class.php';

/**
 * psFeatureOptionSubject actions.
 *
 * @package kidsschool.vn
 * @subpackage psFeatureOptionSubject
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psFeatureOptionSubjectActions extends autoPsFeatureOptionSubjectActions {

	public function executeIndex(sfWebRequest $request) {

		$this->service_id = $request->getParameter ( 'service_id' );

		$this->service = Doctrine_Core::getTable ( 'Service' )->findOneBy ( 'id', $this->service_id );

		$this->forward404Unless ( (myUser::checkAccessObject ( $this->service, 'PS_STUDENT_SUBJECT_FILTER_SCHOOL' ) && ($this->service->getEnableSchedule () == PreSchool::ACTIVE)), sprintf ( 'Object does not exist.' ) );

		$this->setFilters ( array (
				'ps_service_id' => $this->service_id ) );

		$request->setParameter ( 'service_id', $this->service_id );

		// get all FeatureOption of User login
		$this->feature_options = Doctrine_Core::getTable ( 'FeatureOption' )->findAllForAddNewService ( $this->service_id, $this->service->getPsCustomerId () );

		parent::executeIndex ( $request );

		$this->service2 = $this->service;

		$this->setTemplate ( 'show' );
	}

	public function executeSaveOptionSubject(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$service_id = $request->getParameter ( 'service_id' );

		$ids = $request->getParameter ( 'ids' );

		// Check ids
		if (! $ids) {
			$this->getUser ()
				->setFlash ( 'error', 'You must at least select one item.' );

			$this->redirect ( '@ps_feature_option_subject_service?service_id=' . $service_id );
		}

		if (! $this->getUser ()
			->hasCredential ( $this->configuration->getCredentials ( 'save' ) )) {
			$this->forward ( sfConfig::get ( 'sf_secure_module' ), sfConfig::get ( 'sf_secure_action' ) );
		}

		$this->forward404Unless ( $service_id, sprintf ( 'Object is not exist.' ) );

		$this->service = Doctrine_Core::getTable ( 'Service' )->findOneById ( $service_id );

		$this->forward404Unless ( (myUser::checkAccessObject ( $this->service, 'PS_STUDENT_SUBJECT_FILTER_SCHOOL' ) && ($this->service->getEnableSchedule () == PreSchool::ACTIVE)), sprintf ( 'Object does not exist.' ) );

		$type = $request->getParameter ( 'type' );

		$validator = new sfValidatorDoctrineChoice ( array (
				'multiple' => true,
				'model' => 'FeatureOption' ) );

		$conn = Doctrine_Manager::connection ();
		try {

			$conn->beginTransaction ();

			$ids = $validator->clean ( $ids );

			foreach ( $ids as $feature_option_id ) {

				$obj = Doctrine_Core::getTable ( 'FeatureOptionSubject' )->findOneByPsServiceIdAndFeatureOptionId ( $service_id, $feature_option_id );

				if (! $obj) {
					$obj = new FeatureOptionSubject ();
				}

				$obj->setType ( $type [$feature_option_id] );
				$obj->setOrderBy ( Doctrine_Core::getTable ( 'FeatureOptionSubject' )->getMaxOrderBy ( $service_id ) + 1 );
				$obj->setPsServiceId ( $service_id );
				$obj->setFeatureOptionId ( $feature_option_id );

				$obj->setUserCreatedId ( $this->getUser ()
					->getUserId () );
				$obj->setUserUpdatedId ( $this->getUser ()
					->getUserId () );
				$obj->setUpdatedAt ( date ( 'Y-m-d H:i:s', time () ) );
				$obj->save ();
			}

			$conn->commit ();
		} catch ( Exception $e ) {
			throw new Exception ( $e->getMessage () );
			$conn->rollback ();
			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'Update failed' ) );
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The item was created successfully.' . ' You can add another one below.' );

		$this->redirect ( '@ps_feature_option_subject_service?service_id=' . $service_id );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$ps_feature_option_subject = $this->getRoute ()
			->getObject ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $ps_feature_option_subject ) ) );

		$service_id = $ps_feature_option_subject->getPsServiceId ();

		$this->forward404Unless ( $service_id, sprintf ( 'Object is not exist.' ) );

		$service = $ps_feature_option_subject->getService ();

		$this->forward404Unless ( (myUser::checkAccessObject ( $service, 'PS_STUDENT_SUBJECT_FILTER_SCHOOL' ) && ($service->getEnableSchedule () == PreSchool::ACTIVE)), sprintf ( 'Object does not exist.' ) );

		// Check StudentFeature da su dung chua
		if ($ps_feature_option_subject->getNumberRecordStudentServiceCourseComment () > 0) {
			$this->getUser ()
				->setFlash ( 'danger', 'This evaluation is being used. Can not delete.' );
		} else {

			if ($ps_feature_option_subject->delete ()) {
				$this->getUser ()
					->setFlash ( 'notice', 'The item was deleted successfully.' );
			} else {
				$this->getUser ()
					->setFlash ( 'notice', 'The item was deleted failed' );
			}
		}

		$this->redirect ( '@ps_feature_option_subject_service?service_id=' . $service_id );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$records = Doctrine_Query::create ()->from ( 'FeatureOptionSubject' )
			->whereIn ( 'id', $ids )
			->execute ();

		foreach ( $records as $record ) {
			$service_id = $record->getPsServiceId ();

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );

			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
		$this->redirect ( '@ps_feature_option_subject_service?service_id=' . $request->getParameter ( 'service_id' ) );
	}

	public function executeBatch(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$service_id = $request->getParameter ( 'service_id' );

		$this->service = Doctrine_Core::getTable ( 'Service' )->findOneBy ( 'id', $service_id );

		$this->forward404Unless ( $this->service, sprintf ( 'Object is not exist.' ) );

		if (! $ids = $request->getParameter ( 'ids' )) {
			$this->getUser ()
				->setFlash ( 'error', 'You must at least select one item.' );

			$this->redirect ( '@ps_feature_option_subject?service_id=' . $service_id );
		}

		if (! $action = $request->getParameter ( 'batch_action' )) {
			$this->getUser ()
				->setFlash ( 'error', 'You must select an action to execute on the selected items.' );

			$this->redirect ( '@ps_feature_option_subject_branch?service_id=' . $service_id );
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
				'model' => 'FeatureOptionSubject' ) );
		try {
			// validate ids
			$ids = $validator->clean ( $ids );

			// execute batch
			$this->$method ( $request );
		} catch ( sfValidatorError $e ) {
			$this->getUser ()
				->setFlash ( 'error', 'A problem occurs when deleting the selected items as some items do not exist anymore.' );
		}

		$this->redirect ( '@ps_feature_option_subject_service?service_id=' . $request->getParameter ( 'service_id' ) );
	}

	protected function executeBatchUpdateOrder(sfWebRequest $request) {

		$iorder = $request->getParameter ( 'order_by' );

		if (! count ( $iorder )) {
			$this->getUser ()
				->setFlash ( 'error', 'You must at least select one item.' );
		} else {

			$conn = Doctrine_Manager::connection ();

			try {

				$conn->beginTransaction ();

				foreach ( $iorder as $key => $value ) {
					if (! is_numeric ( $value )) {
						$this->getUser ()
							->setFlash ( 'error', 'Is not a number' );
						break;
					} else {
						$obj = Doctrine::getTable ( 'FeatureOptionSubject' )->find ( $key );
						$obj->setOrderBy ( $value );
						$obj->setUserUpdatedId ( $this->getUser ()
							->getUserId () );
						$obj->setUpdatedAt ( date ( 'Y-m-d H:i:s', time () ) );
						$obj->save ();
					}
				}

				$conn->commit ();

				$this->getUser ()
					->setFlash ( 'notice', $this->getContext ()
					->getI18N ()
					->__ ( 'The item was updated successfully.' ) );
			} catch ( Exception $e ) {
				$conn->rollback ();
				throw new Exception ( $e->getMessage () );
				$this->getUser ()
					->setFlash ( 'error', $this->getContext ()
					->getI18N ()
					->__ ( 'Update failed' ) );
			}

			/*
			 * $conn = Doctrine_Manager :: connection();
			 * try {
			 * $conn->beginTransaction();
			 * foreach ( $iorder as $key => $value ) {
			 * if (!is_numeric($value)) {
			 * $this->getUser()->setFlash('error', 'Is not a number');
			 * break;
			 * } else {
			 * $feature_option_subject = Doctrine::getTable('FeatureOptionFeature')->find($key);
			 * $feature_option_subject->setOrderBy($value);
			 * $feature_option_subject->save();
			 * }
			 * }
			 * $this->getUser()->setFlash('notice', $this->getContext()->getI18N()->__('The item was updated successfully.'));
			 * $conn->commit();
			 * } catch (Exception $e) {
			 * throw new Exception($e->getMessage());
			 * $conn->rollback();
			 * $this->getUser()->setFlash('error', $this->getContext()->getI18N()->__('Update failed'));
			 * }
			 */
		}

		$this->redirect ( '@ps_feature_option_subject_service?service_id=' . $request->getParameter ( 'service_id' ) );
	}
}
