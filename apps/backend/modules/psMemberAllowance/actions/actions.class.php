<?php
require_once dirname ( __FILE__ ) . '/../lib/psMemberAllowanceGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psMemberAllowanceGeneratorHelper.class.php';

/**
 * psMemberAllowance actions.
 *
 * @package kidsschool.vn
 * @subpackage psMemberAllowance
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psMemberAllowanceActions extends autoPsMemberAllowanceActions {

	public function executeNew(sfWebRequest $request) {

		$ps_member_id = $request->getParameter ( 'ps_member_id' );

		if ($ps_member_id <= 0) {

			$this->getUser ()
				->setFlash ( 'error', 'Object does not exist.' );

			$this->redirect ( '@ps_member' );
		} else {

			$this->ps_member = Doctrine::getTable ( 'PsMember' )->findOneById ( $ps_member_id );

			$ps_member_allowance = new PsMemberAllowance ();

			$ps_member_allowance->setPsMemberId ( $ps_member_id );

			$this->form = $this->configuration->getForm ( $ps_member_allowance );

			$this->ps_member_allowance = $this->form->getObject ();

			// $this->ps_member = $this->getRoute()->getObject()->getPsMember();

			if (! myUser::checkAccessObject ( $this->ps_member, 'PS_HR_HR_FILTER_SCHOOL' )) {
				$this->getUser ()
					->setFlash ( 'error', 'Object does not exist.' );
				$this->redirect ( '@ps_member' );
			}

			$this->helper = new psMemberAllowanceGeneratorHelper ();

			return $this->renderPartial ( 'psMemberAllowance/newSuccess', array (
					'ps_member_allowance' => $this->ps_member_allowance,
					'form' => $this->form,
					'ps_member' => $this->ps_member,
					'configuration' => $this->configuration,
					'helper' => $this->helper ) );
		}
	}

	public function executeCreate(sfWebRequest $request) {

		$formValues = $request->getParameter ( 'ps_member_allowance' );

		$ps_member_id = isset ( $formValues ['ps_member_id'] ) ? $formValues ['ps_member_id'] : '';

		if ($ps_member_id <= 0) {
			$this->getUser ()
				->setFlash ( 'error', 'Object does not exist.' );
			$this->redirect ( '@ps_member' );
		}

		$ps_member = Doctrine::getTable ( 'PsMember' )->findOneById ( $ps_member_id );

		if (! myUser::checkAccessObject ( $ps_member, 'PS_HR_HR_FILTER_SCHOOL' )) {
			$this->getUser ()
				->setFlash ( 'error', 'Object does not exist.' );
			$this->redirect ( '@ps_member' );
		}

		$ps_member_allowance = new PsMemberAllowance ();

		$ps_member_allowance->setPsMemberId ( $ps_member_id );

		$this->form = $this->configuration->getForm ( $ps_member_allowance );

		$this->processForm2 ( $request, $this->form, $ps_member );

		exit ( 0 );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_member_allowance = $this->getRoute ()
			->getObject ();

		$this->ps_member = $this->getRoute ()
			->getObject ()
			->getPsMember ();

		if (! myUser::checkAccessObject ( $this->ps_member, 'PS_HR_HR_FILTER_SCHOOL' )) {
			$this->getUser ()
				->setFlash ( 'error', 'Object does not exist.' );
			$this->redirect ( '@ps_member' );
		}

		$this->form = $this->configuration->getForm ( $this->ps_member_allowance );

		$this->form->setDefault ( 'url_callback', $request->getParameter ( 'url_callback' ) );

		$this->helper = new psMemberAllowanceGeneratorHelper ();

		return $this->renderPartial ( 'psMemberAllowance/newSuccess', array (
				'ps_member_allowance' => $this->ps_member_allowance,
				'form' => $this->form,
				'configuration' => $this->configuration,
				'helper' => $this->helper ) );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_member_allowance = $this->getRoute ()
			->getObject ();

		$this->form = $this->configuration->getForm ( $this->ps_member_allowance );

		$this->ps_member = $this->ps_member_allowance->getPsMember ();

		$ps_member = $this->ps_member;

		if (! myUser::checkAccessObject ( $ps_member, 'PS_HR_HR_FILTER_SCHOOL' )) {
			$this->getUser ()
				->setFlash ( 'error', 'Object does not exist.' );
			$this->redirect ( '@ps_member' );
		}

		$this->helper = new psMemberAllowanceGeneratorHelper ();

		$this->processForm2 ( $request, $this->form, $ps_member );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$ps_member_allowance = $this->getRoute ()
			->getObject ();

		$ps_member = $ps_member_allowance->getPsMember ();

		// $this->forward404Unless(myUser::checkAccessObject($this->ps_member, 'PS_HR_HR_FILTER_SCHOOL'), sprintf('Object (%s) does not exist .', $this->ps_member_departments->getId()));
		if (! myUser::checkAccessObject ( $ps_member, 'PS_HR_HR_FILTER_SCHOOL' )) {
			$this->getUser ()
				->setFlash ( 'error', 'Object does not exist.' );
			$this->redirect ( '@ps_member' );
		}

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $ps_member_allowance ) ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_member_edit?id=' . $ps_member->getId () . '#pstab_4' );
	}

	protected function processForm2(sfWebRequest $request, sfForm $form, $ps_member) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The member allowance was created successfully.' : 'The member allowance was updated successfully.';

			try {
				$formValues = $request->getParameter ( 'ps_member_allowance' );

				$allowance = isset ( $formValues ['ps_allowance_id'] ) ? $formValues ['ps_allowance_id'] : '';

				if ($allowance == '') {

					$message = 'Error: Basic Allowance was empty';

					$this->getUser ()
						->setFlash ( 'error', $message );

					$this->redirect ( '@ps_member_edit?id=' . $ps_member->getId () . '#pstab_4' );
				}

				$ps_member_allowance = $form->save ();
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

				$this->redirect ( '@ps_member_edit?id=' . $ps_member->getId () . '#pstab_4' );
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $ps_member_allowance ) ) );

			$this->getUser ()
				->setFlash ( 'notice', $notice );

			$this->redirect ( '@ps_member_edit?id=' . $ps_member->getId () . '#pstab_4' );
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}
}
