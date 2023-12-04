<?php
require_once dirname ( __FILE__ ) . '/../lib/psFeeReceivableStudentGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psFeeReceivableStudentGeneratorHelper.class.php';

/**
 * psFeeReceivableStudent actions.
 *
 * @package kidsschool.vn
 * @subpackage psFeeReceivableStudent
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psFeeReceivableStudentActions extends autoPsFeeReceivableStudentActions {

	public function executeNew(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {
			$ps_fee_receipt_id = $request->getParameter ( 'fbid' );

			if ($ps_fee_receipt_id <= 0) {

				$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
			} else {
				$ps_fee_receipt = Doctrine::getTable ( 'PsFeeReceipt' )->findOneById ( $ps_fee_receipt_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_fee_receipt, 'PS_FEE_REPORT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$ps_fee_receivable_student = new PsFeeReceivableStudent ();

				$ps_fee_receivable_student->setPsFeeReceiptId ( $ps_fee_receipt_id );

				$this->form = $this->configuration->getForm ( $ps_fee_receivable_student );

				$this->ps_fee_receivable_student = $this->form->getObject ();

				return $this->renderPartial ( 'psFeeReceivableStudent/formSuccess', array (
						'ps_fee_receivable_student' => $this->ps_fee_receivable_student,
						'form' => $this->form,
						'configuration' => $this->configuration,
						'helper' => $this->helper,
						'ps_fee_receipt' => $ps_fee_receipt ) );
			}
		} else {
			exit ( 0 );
		}
	}

	public function executeCreate(sfWebRequest $request) {

		$formValues = $request->getParameter ( 'psactivitie' );

		$ps_fee_receipt_id = isset ( $formValues ['ps_fee_receipt_id'] ) ? $formValues ['ps_fee_receipt_id'] : '';

		if ($ps_fee_receipt_id > 0) {

			$ps_fee_receipt = Doctrine::getTable ( 'PsFeeReceipt' )->findOneById ( $ps_fee_receipt_id );

			$this->forward404Unless ( myUser::checkAccessObject ( $ps_fee_receipt, 'PS_FEE_REPORT_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		}

		$ps_fee_receivable_student = new PsFeeReceivableStudent ();

		$ps_fee_receivable_student->setPsFeeReceiptId ( $ps_fee_receipt_id );

		$this->form = $this->configuration->getForm ( $ps_fee_receivable_student );

		$this->ps_fee_receivable_student = $this->form->getObject ();

		$this->processForm ( $request, $this->form );

		exit ( 0 );
	}

	public function executeEdit(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$this->ps_fee_receivable_student = $this->getRoute ()
				->getObject ();

				$ps_fee_receipt_id = $this->ps_fee_receivable_student->getPsFeeReceiptId ();

			$ps_fee_receipt = Doctrine::getTable ( 'PsFeeReceipt' )->findOneById ( $ps_fee_receipt_id );

			$this->forward404Unless ( myUser::checkAccessObject ( $ps_fee_receipt, 'PS_FEE_REPORT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

			$this->form = $this->configuration->getForm ( $this->ps_fee_receivable_student );

			return $this->renderPartial ( 'psFeeReceivableStudent/formSuccess', array (
					'ps_fee_receivable_student' => $this->ps_fee_receivable_student,
					'form' => $this->form,
					'configuration' => $this->configuration,
					'helper' => $this->helper,
					'ps_fee_receipt' => $ps_fee_receipt ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_fee_receivable_student = $this->getRoute ()
			->getObject ();
		$this->form = $this->configuration->getForm ( $this->ps_fee_receivable_student );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		$formValues = $request->getParameter ( $form->getName () );
		$ps_fee_receipt_id = isset ( $formValues ['ps_fee_receipt_id'] ) ? $formValues ['ps_fee_receipt_id'] : '';

		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {
				$ps_fee_receivable_student = $form->save ();
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

			// $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $ps_fee_receivable_student)));

			$this->getUser ()
				->setFlash ( 'notice', $notice );

			$this->redirect ( '@ps_fee_receipt_edit?id=' . $ps_fee_receipt_id . '#pstab_2' );
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );

			$this->redirect ( '@ps_fee_receipt_edit?id=' . $ps_fee_receipt_id . '#pstab_2' );
		}
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->ps_fee_receivable_student = $this->getRoute ()
			->getObject ();

		$ps_fee_receivable_student_id = $this->ps_fee_receivable_student->getId ();

		$ps_fee_receipt_id = $this->ps_fee_receivable_student->getPsFeeReceiptId ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_fee_receipt_edit?id=' . $ps_fee_receipt_id . '#pstab_2' );
	}
}
