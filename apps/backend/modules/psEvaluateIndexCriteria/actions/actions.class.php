<?php
require_once dirname ( __FILE__ ) . '/../lib/psEvaluateIndexCriteriaGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psEvaluateIndexCriteriaGeneratorHelper.class.php';

/**
 * psEvaluateIndexCriteria actions.
 *
 * @package kidsschool.vn
 * @subpackage psEvaluateIndexCriteria
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psEvaluateIndexCriteriaActions extends autoPsEvaluateIndexCriteriaActions {

	public function executeEdit(sfWebRequest $request) {

		$this->ps_evaluate_index_criteria = $this->getRoute ()
			->getObject ();

		$subject_id = $this->ps_evaluate_index_criteria->getEvaluateSubjectId ();

		if ($subject_id > 0) {

			$subject = Doctrine::getTable ( 'PsEvaluateSubject' )->findOneById ( $subject_id );

			$this->forward404Unless ( myUser::checkAccessObject ( $subject, 'PS_EVALUATE_INDEX_SUBJECT_FILTER_SCHOOL' ) || myUser::checkAccessObject ( $subject, 'PS_EVALUATE_INDEX_CRITERIA_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		} else {

			$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
		}

		// try{
		// $this->forward404Unless(myUser::checkAccessObject($this->ps_evaluate_index_criteria, 'PS_EVALUATE_INDEX_CRITERIA_FILTER_SCHOOL'), sprintf('Object does not exist.'));
		// } catch (Exception $e){

		// $this->getUser()->setFlash('error', 'Object does not exist.');

		// $this->redirect('@ps_evaluate_index_criteria');
		// }
		$this->form = $this->configuration->getForm ( $this->ps_evaluate_index_criteria );

		$criteria_id = $this->ps_evaluate_index_criteria->getId ();

		$school_year = Doctrine::getTable ( 'PsEvaluateIndexCriteria' )->getSchoolYearIdById ( $criteria_id );

		$subject = Doctrine::getTable ( 'PsEvaluateIndexCriteria' )->getSubjectById ( $criteria_id );

		$param = array ();
		$param ['ps_customer_id'] = $subject ? $subject->getPsCustomerId () : '';
		$param ['ps_workplace_id'] = $subject->getPsWorkplaceId ();

		if (isset ( $subject ) && $subject->getSchoolYearId () > 0) {

			$schoolyear = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $subject->getSchoolYearId () );
			$param ['ps_school_year_id'] = $subject->getSchoolYearId ();
		} else {

			$schoolyear = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ();

			if ($schoolyear) {

				$param ['ps_school_year_id'] = $schoolyear->getId ();
			}
		}

		$this->list_class = Doctrine::getTable ( 'MyClass' )->getMyClassForEvaluateIndexCriteria ( $criteria_id, $param );
		$this->schoolyear = $schoolyear;
		$this->subject = $subject;
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$criteria = $this->getRoute ()
			->getObject ();

		$check_foreign_exist = Doctrine::getTable ( 'PsEvaluateIndexCriteria' )->checkForeignDataExit ( $criteria->getId () );

		if ($check_foreign_exist) {

			$this->getUser ()
				->setFlash ( 'error', 'Please delete all data in Evaluate index student and Evaluate class time before delete this' );

			$this->redirect ( '@ps_evaluate_index_criteria' );
		}

		$subject_id = $criteria->getEvaluateSubjectId ();

		if ($subject_id > 0) {

			$subject = Doctrine::getTable ( 'PsEvaluateSubject' )->findOneById ( $subject_id );

			$this->forward404Unless ( myUser::checkAccessObject ( $subject, 'PS_EVALUATE_INDEX_SUBJECT_FILTER_SCHOOL' ) || myUser::checkAccessObject ( $subject, 'PS_EVALUATE_INDEX_CRITERIA_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		} else {

			$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
		}

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $criteria ) ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_evaluate_index_criteria' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$check_foreign_exist = Doctrine::getTable ( 'PsEvaluateIndexCriteria' )->checkForeignDataExit ( $criteria->getId () );

		if ($check_foreign_exist) {

			$this->getUser ()
				->setFlash ( 'error', 'Please delete all data in Evaluate index student and Evaluate class time before delete this' );

			$this->redirect ( '@ps_evaluate_index_criteria' );
		}

		if (myUser::credentialPsCustomers ( 'PS_EVALUATE_INDEX_CRITERIA_FILTER_SCHOOL' ) && myUser::credentialPsCustomers ( 'PS_EVALUATE_INDEX_SUBJECT_FILTER_SCHOOL' )) {
			$records = Doctrine_Query::create ()->from ( 'PsEvaluateIndexCriteria' )
				->whereIn ( 'id', $ids )
				->execute ();
		} else {
			$records = Doctrine_Query::create ()->from ( 'PsEvaluateIndexCriteria' )
				->innerJoin ( 'PsEvaluateSubject s' )
				->andWhere ( 's.ps_customer_id =?', myUser::getPscustomerID () )
				->whereIn ( 'id', $ids )
				->execute ();
		}
		foreach ( $records as $record ) {
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );

			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );

		$this->redirect ( '@ps_evaluate_index_criteria' );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );

		$formValues = $request->getParameter ( $form->getName () );

		$class_apply = $request->getParameter ( 'class_apply' );

		$psactivitie_my_class = isset ( $class_apply ['my_class'] ) ? $class_apply ['my_class'] : null;

		// print_r($class_apply);die;
		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			$is_new = $form->getObject ()
				->isNew ();

			$conn = Doctrine_Manager::connection ();

			try {

				$conn->beginTransaction ();

				$ps_evaluate_index_criteria = $form->save ();

				$criteria_id = $ps_evaluate_index_criteria->getId ();

				if ($is_new) {
					// Them moi Evaluate Class Time
					foreach ( $psactivitie_my_class as $my_class ) {

						if ($my_class ['ids'] > 0) {

							$from_date = date ( 'Y-m-d', strtotime ( $my_class ['from_date'] ) );
							$to_date = date ( 'Y-m-d', strtotime ( $my_class ['to_date'] ) );

							$new_psEvaluateClassTime = new PsEvaluateClassTime ();
							$new_psEvaluateClassTime->setCriteriaId ( $criteria_id );
							$new_psEvaluateClassTime->setMyclassId ( $my_class ['ids'] );
							$new_psEvaluateClassTime->setDateStart ( $from_date );
							$new_psEvaluateClassTime->setDateEnd ( $to_date );
							$new_psEvaluateClassTime->setUserCreatedId ( sfContext::getInstance ()->getUser ()
								->getGuardUser ()
								->getId () );
							$new_psEvaluateClassTime->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
								->getGuardUser ()
								->getId () );
							$new_psEvaluateClassTime->save ();
						}
					}
				} else {
					// Xoa toan bo ban ghi cu
					Doctrine::getTable ( 'PsEvaluateClassTime' )->createQuery ()
						->delete ()
						->where ( 'criteria_id = ?', $criteria_id )
						->execute ();

					foreach ( $psactivitie_my_class as $my_class ) {
						if ($my_class ['ids'] > 0) {
							// print_r($my_class);die;
							$from_date = date ( 'Y-m-d', strtotime ( $my_class ['from_date'] ) );
							$to_date = date ( 'Y-m-d', strtotime ( $my_class ['to_date'] ) );

							$new_psEvaluateClassTime = new PsEvaluateClassTime ();
							$new_psEvaluateClassTime->setCriteriaId ( $criteria_id );
							$new_psEvaluateClassTime->setMyclassId ( $my_class ['ids'] );
							$new_psEvaluateClassTime->setDateStart ( $from_date );
							$new_psEvaluateClassTime->setDateEnd ( $to_date );
							$new_psEvaluateClassTime->setUserCreatedId ( sfContext::getInstance ()->getUser ()
								->getGuardUser ()
								->getId () );
							$new_psEvaluateClassTime->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
								->getGuardUser ()
								->getId () );
							$new_psEvaluateClassTime->save ();
						}
					}
				}

				$conn->commit ();
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
				return sfView::SUCCESS;
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $ps_evaluate_index_criteria ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				$this->redirect ( '@ps_evaluate_index_criteria_new' );
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_evaluate_index_criteria_edit',
						'sf_subject' => $ps_evaluate_index_criteria ) );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}
}
