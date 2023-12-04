<?php
require_once dirname ( __FILE__ ) . '/../lib/psReceivableDetailGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psReceivableDetailGeneratorHelper.class.php';

/**
 * psReceivableDetail actions.
 *
 * @package kidsschool.vn
 * @subpackage psReceivableDetail
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psReceivableDetailActions extends autoPsReceivableDetailActions {

	public function executeNew(sfWebRequest $request) {

		$receivable_id = $request->getParameter ( 'fbid' );

		if ($receivable_id <= 0) {

			$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
		} else {

			$receivable = Doctrine::getTable ( 'Receivable' )->findOneById ( $receivable_id );

			$this->forward404Unless ( myUser::checkAccessObject ( $receivable, 'PS_FEE_RECEIVABLE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

			$receivable_detail = new ReceivableDetail ();

			$receivable_detail->setReceivableId ( $receivable_id );

			$this->form = $this->configuration->getForm ( $receivable_detail );

			$this->receivable_detail = $this->form->getObject ();

			return $this->renderPartial ( 'psReceivableDetail/formSuccess', array (
					'receivable_detail' => $this->receivable_detail,
					'form' => $this->form,
					'configuration' => $this->configuration,
					'helper' => $this->helper ) );
		}
	}

	public function executeCreate(sfWebRequest $request) {

		$formValues = $request->getParameter ( 'psactivitie' );

		$receivable_id = isset ( $formValues ['receivable_id'] ) ? $formValues ['receivable_id'] : '';

		if ($receivable_id > 0) {

			$receivable = Doctrine::getTable ( 'Receivable' )->findOneById ( $receivable_id );

			$this->forward404Unless ( myUser::checkAccessObject ( $receivable, 'PS_FEE_RECEIVABLE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		}

		$receivable_detail = new ReceivableDetail ();

		$receivable_detail->setReceivableId ( $receivable_id );

		$this->form = $this->configuration->getForm ( $receivable_detail );

		$this->receivable_detail = $this->form->getObject ();

		$this->processForm ( $request, $this->form );

		exit ( 0 );
	}

	public function executeEdit(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$this->receivable_detail = $this->getRoute ()
				->getObject ();

			$receivable = $this->receivable_detail->getReceivable ();

			$this->forward404Unless ( myUser::checkAccessObject ( $receivable, 'PS_FEE_RECEIVABLE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

			$this->form = $this->configuration->getForm ( $this->receivable_detail );

			return $this->renderPartial ( 'psReceivableDetail/formSuccess', array (
					'receivable_detail' => $this->receivable_detail,
					'form' => $this->form,
					'configuration' => $this->configuration,
					'helper' => $this->helper ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->receivable_detail = $this->getRoute ()
			->getObject ();

		$receivable = $this->receivable_detail->getReceivable ();

		$this->forward404Unless ( myUser::checkAccessObject ( $receivable, 'PS_FEE_RECEIVABLE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->receivable_detail );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		$formValues = $request->getParameter ( $form->getName () );
		$receivable_id = isset ( $formValues ['receivable_id'] ) ? $formValues ['receivable_id'] : '';

		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {

				$receivable_detail = $form->save ();
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

			$this->getUser ()
				->setFlash ( 'notice', $notice );

			$this->redirect ( '@receivable_edit?id=' . $receivable_id . '#pstab_2' );
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );

			$this->redirect ( '@receivable_edit?id=' . $receivable_id . '#pstab_2' );
		}
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->receivable_detail = $this->getRoute ()
			->getObject ();

		$receivable = $this->receivable_detail->getReceivable ();

		$this->forward404Unless ( myUser::checkAccessObject ( $receivable, 'PS_FEE_RECEIVABLE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$receivable_id = $this->receivable_detail->getReceivableId ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@receivable_edit?id=' . $receivable_id . '#pstab_2' );
	}
}
