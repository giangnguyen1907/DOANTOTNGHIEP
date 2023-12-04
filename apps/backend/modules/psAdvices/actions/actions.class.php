<?php
require_once dirname ( __FILE__ ) . '/../lib/psAdvicesGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psAdvicesGeneratorHelper.class.php';

/**
 * psAdvices actions.
 *
 * @package kidsschool.vn
 * @subpackage psAdvices
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psAdvicesActions extends autoPsAdvicesActions {

	public function executeDetail(sfWebRequest $request) {

		$this->filter_value = $this->getFilters ();

		$advice_id = $request->getParameter ( 'id' );

		if ($advice_id <= 0) {

			$this->forward404Unless ( $advice_id, sprintf ( 'Object does not exist.' ) );
		}

		$advice_detail = Doctrine::getTable ( 'PsAdvices' )->getAdviceById ( $advice_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $advice_detail, 'PS_STUDENT_RELATIVE_ADVICE_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $advice_id ) );

		$this->advice_detail = $advice_detail;
	}

	public function executeNew(sfWebRequest $request) {

		$this->redirect ( '@ps_advices' );
	}

	public function executeEdit(sfWebRequest $request)
	{

		if ($request->isXmlHttpRequest ()) {
			
			$this->ps_advices = $this->getRoute ()->getObject ();
			
			$obj_student = Doctrine::getTable ( 'Student' )->findOneById ( $this->ps_advices->getStudentId () );
			
			$this->forward404Unless ( myUser::checkAccessObject ( $obj_student, 'PS_STUDENT_RELATIVE_ADVICE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
			
			$this->form = $this->configuration->getForm ( $this->ps_advices );
			
			return $this->renderPartial ( 'psAdvices/formSuccess', array (
					'ps_advices' => $this->ps_advices,
					'form' => $this->form,
					'obj_student'=>$obj_student,
					'configuration' => $this->configuration,
					'helper' => $this->helper ) );
		} else {
			exit ( 0 );
		}
		
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form_value = $request->getParameter ( $form->getName () );

		$form->bind ( $form_value, $request->getFiles ( $form->getName () ) );
		// print_r($form_value);die;
		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			$conn = Doctrine_Manager::connection ();

			try {

				$conn->beginTransaction ();

				$ps_advice = $form->save ();

				$feedback = Doctrine::getTable('PsAdviceFeedbacks')->findOneByAdviceId($ps_advice->getId());
				
				$feedback_content = $form->getValue('feedback_content');
				
				if($feedback){
					
					$feedback ->setContent($feedback_content);
					$feedback -> save();
					
				}else{
					if ($form->getValue('feedback_content') != '') {
						
						$psAdviceFeedbacks = new PsAdviceFeedbacks ();
						$psAdviceFeedbacks->setAdviceId ( $ps_advice->getId () );
						$psAdviceFeedbacks->setUmemberId ( $ps_advice->getUserId () );
						$psAdviceFeedbacks->setUrelativeId ( $ps_advice->getUserCreatedId () );
						$psAdviceFeedbacks->setIsTeacher ( 1 );
						$psAdviceFeedbacks->setContent ( $feedback_content );
						$psAdviceFeedbacks->setIsActivated ( 1 );
						
						$psAdviceFeedbacks->setUserCreatedId ( sfContext::getInstance ()->getUser ()
								->getGuardUser ()
								->getId () );
						
						$psAdviceFeedbacks->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
								->getGuardUser ()
								->getId () );
						
						$psAdviceFeedbacks->save ();
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
					'object' => $ps_advice ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				$this->redirect ( '@ps_advices' );
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

// 				$this->redirect ( array (
// 						'sf_route' => 'ps_advices_edit',
// 						'sf_subject' => $ps_advice ) );
				$this->redirect ( '@ps_advices' );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$advice_id = $request->getParameter ( 'id' );

		if ($advice_id <= 0) {

			$this->forward404Unless ( $advice_id, sprintf ( 'Object does not exist.' ) );
		}

		$ps_advice = Doctrine::getTable ( 'PsAdvices' )->findOneById ( $advice_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $ps_advice, 'PS_STUDENT_RELATIVE_ADVICE_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $advice_id ) );

		$records = Doctrine_Query::create ()->from ( 'PsAdviceFeedbacks' )
			->addWhere ( 'advice_id =?', $advice_id )
			->execute ();

		foreach ( $records as $record ) {
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );
			$record->delete ();
		}

		$records = Doctrine_Query::create ()->from ( 'PsAdvices' )
			->addWhere ( 'id =?', $advice_id )
			->execute ();

		foreach ( $records as $record ) {
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );
			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The item was deleted successfully.' );

		$this->redirect ( '@ps_advices' );
	}

	public function executeBatch(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$this->forward404Unless ( myUser::checkAccessObject ( $ids, 'PS_STUDENT_RELATIVE_ADVICE_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $ids ) );

		if ($ids) {

			$records = Doctrine_Query::create ()->from ( 'PsAdviceFeedbacks' )
				->whereIn ( 'advice_id', $ids )
				->execute ();

			foreach ( $records as $record ) {
				$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
						'object' => $record ) ) );
				$record->delete ();
			}

			$records = Doctrine_Query::create ()->from ( 'PsAdvices' )
				->whereIn ( 'id', $ids )
				->execute ();

			foreach ( $records as $record ) {
				$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
						'object' => $record ) ) );
				$record->delete ();
			}

			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		} else {

			$this->getUser ()
				->setFlash ( 'error', 'Not choose any items to delete.' );
		}
		$this->redirect ( '@ps_advices' );
	}
}
