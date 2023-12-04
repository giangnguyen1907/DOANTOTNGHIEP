<?php
require_once dirname ( __FILE__ ) . '/../lib/psMemberWorkingTimeGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psMemberWorkingTimeGeneratorHelper.class.php';

/**
 * psMemberWorkingTime actions.
 *
 * @package kidsschool.vn
 * @subpackage psMemberWorkingTime
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psMemberWorkingTimeActions extends autoPsMemberWorkingTimeActions {

	public function executeNew(sfWebRequest $request) {

		$ps_member_id = $request->getParameter ( 'ps_member_id' );

		if ($ps_member_id <= 0) {

			$this->getUser ()
				->setFlash ( 'error', 'Object does not exist.' );

			$this->redirect ( '@ps_member' );
		} else {

			$this->ps_member = Doctrine::getTable ( 'PsMember' )->findOneById ( $ps_member_id );

			$ps_member_workingtime = new PsMemberWorkingTime ();

			$ps_member_workingtime->setPsMemberId ( $ps_member_id );

			$this->form = $this->configuration->getForm ( $ps_member_workingtime );

			$this->ps_member_working_time = $this->form->getObject ();

			// $this->ps_member = $this->getRoute()->getObject()->getPsMember();

			if (! myUser::checkAccessObject ( $this->ps_member, 'PS_HR_HR_FILTER_SCHOOL' )) {
				$this->getUser ()
					->setFlash ( 'error', 'Object does not exist.' );
				$this->redirect ( '@ps_member' );
			}

			$this->helper = new psMemberWorkingTimeGeneratorHelper ();

			return $this->renderPartial ( 'psMemberWorkingTime/newSuccess', array (
					'ps_member_working_time' => $this->ps_member_working_time,
					'form' => $this->form,
					'ps_member' => $this->ps_member,
					'configuration' => $this->configuration,
					'helper' => $this->helper ) );
		}
	}

	public function executeCreate(sfWebRequest $request) {

		$formValues = $request->getParameter ( 'ps_member_working_time' );

		$ps_member_id = isset ( $formValues ['ps_member_id'] ) ? $formValues ['ps_member_id'] : '';
		// print_r($request);die;
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

		$ps_member_workingtime = new PsMemberWorkingTime ();

		$ps_member_workingtime->setPsMemberId ( $ps_member_id );

		$this->form = $this->configuration->getForm ( $ps_member_workingtime );

		$this->processForm2 ( $request, $this->form, $ps_member );

		exit ( 0 );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_member_working_time = $this->getRoute ()
			->getObject ();

		$this->ps_member = $this->getRoute ()
			->getObject ()
			->getPsMember ();

		if (! myUser::checkAccessObject ( $this->ps_member, 'PS_HR_HR_FILTER_SCHOOL' )) {
			$this->getUser ()
				->setFlash ( 'error', 'Object does not exist.' );
			$this->redirect ( '@ps_member' );
		}

		$this->form = $this->configuration->getForm ( $this->ps_member_working_time );

		$this->form->setDefault ( 'url_callback', $request->getParameter ( 'url_callback' ) );

		$this->helper = new psMemberWorkingTimeGeneratorHelper ();

		return $this->renderPartial ( 'psMemberWorkingTime/newSuccess', array (
				'ps_member_working_time' => $this->ps_member_working_time,
				'form' => $this->form,
				'configuration' => $this->configuration,
				'helper' => $this->helper ) );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_member_working_time = $this->getRoute ()
			->getObject ();

		$this->form = $this->configuration->getForm ( $this->ps_member_working_time );

		$this->ps_member = $this->ps_member_working_time->getPsMember ();

		$ps_member = $this->ps_member;

		if (! myUser::checkAccessObject ( $ps_member, 'PS_HR_HR_FILTER_SCHOOL' )) {
			$this->getUser ()
				->setFlash ( 'error', 'Object does not exist.' );
			$this->redirect ( '@ps_member' );
		}

		$this->helper = new psMemberWorkingTimeGeneratorHelper ();

		$this->processForm2 ( $request, $this->form, $ps_member );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$ps_member_workingtime = $this->getRoute ()
			->getObject ();

		$ps_member = $ps_member_workingtime->getPsMember ();

		// $this->forward404Unless(myUser::checkAccessObject($this->ps_member_departments, 'PS_HR_HR_FILTER_SCHOOL'), sprintf('Object (%s) does not exist .', $this->ps_member_departments->getId()));
		if (! myUser::checkAccessObject ( $ps_member, 'PS_HR_HR_FILTER_SCHOOL' )) {
			$this->getUser ()
				->setFlash ( 'error', 'Object does not exist.' );
			$this->redirect ( '@ps_member' );
		}

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $ps_member_workingtime ) ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_member_edit?id=' . $ps_member->getId () . '#pstab_5' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$ps_working_time = Doctrine::getTable ( 'PsMemberWorkingTime' )->checkMemberWorkingTimeExits ( $ids );

		if ($ps_working_time) {

			$this->getUser ()
				->setFlash ( 'error', 'A problem occurs when deleting the selected items has some children relationship.' );

			$this->redirect ( '@ps_working_time' );
		}

		$records = Doctrine_Query::create ()->from ( 'PsWorkingTime' )
			->whereIn ( 'id', $ids )
			->execute ();

		$this->forward404Unless ( myUser::checkAccessObject ( $records, 'PS_HR_WORKINGTIME_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		foreach ( $records as $record ) {
			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );

		$this->redirect ( '@ps_working_time' );
	}

	protected function processForm2(sfWebRequest $request, sfForm $form, $ps_member) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The member working time was created successfully.' : 'The member working time was updated successfully.';

			try {

				$formValues = $request->getParameter ( 'ps_member_working_time' );

				$ps_member_workingtime = isset ( $formValues ['ps_workingtime_id'] ) ? $formValues ['ps_workingtime_id'] : '';

				if ($ps_member_workingtime == '') {

					$message = 'Error: Working Time Id was empty';

					$this->getUser ()
						->setFlash ( 'error', $message );

					$this->redirect ( '@ps_member_edit?id=' . $ps_member->getId () . '#pstab_5' );
				}

				$ps_member_workingtime = $form->save ();
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

				$this->redirect ( '@ps_member_edit?id=' . $ps_member->getId () . '#pstab_5' );
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $ps_member_working_time ) ) );

			$this->getUser ()
				->setFlash ( 'notice', $notice );

			$this->redirect ( '@ps_member_edit?id=' . $ps_member->getId () . '#pstab_5' );
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}
}
