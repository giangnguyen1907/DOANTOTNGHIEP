<?php
require_once dirname ( __FILE__ ) . '/../lib/psWorkingTimeGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psWorkingTimeGeneratorHelper.class.php';

/**
 * psWorkingTime actions.
 *
 * @package kidsschool.vn
 * @subpackage psWorkingTime
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psWorkingTimeActions extends autoPsWorkingTimeActions {

	public function executeEdit(sfWebRequest $request) {

		$this->ps_working_time = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_working_time, 'PS_HR_WORKINGTIME_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_working_time );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_working_time = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_working_time, 'PS_HR_WORKINGTIME_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_working_time );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->getRoute ()
			->getObject (), 'PS_HR_WORKINGTIME_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$ps_working_time_id = $request->getParameter ( 'id' );

		$ps_working_time = Doctrine::getTable ( 'PsMemberWorkingTime' )->checkMemberWorkingTimeExits ( $ps_working_time_id );
		if ($ps_working_time) {

			$this->getUser ()
				->setFlash ( 'error', 'A problem occurs when deleting the selected items has some children relationship.' );

			$this->redirect ( '@ps_working_time' );
		}

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_working_time' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$ps_working_time = Doctrine::getTable ( 'PsMemberWorkingTime' )->checkMemberWorkingTimeExits ( $ids );

		if ($ps_working_time) {

			$this->getUser ()
				->setFlash ( 'error', 'A problem occurs when deleting the selected items has some children relationship.' );

			$this->redirect ( '@ps_working_time' );
		}

		if (myUser::credentialPsCustomers ( 'PS_HR_WORKINGTIME_FILTER_SCHOOL' )) {
			$records = Doctrine_Query::create ()->from ( 'PsWorkingTime' )
				->whereIn ( 'id', $ids )
				->execute ();
		} else {
			$records = Doctrine_Query::create ()->from ( 'PsWorkingTime' )
				->whereIn ( 'id', $ids )
				->andWhere ( 'ps_customer_id = ?', myUser::getPscustomerID () )
				->execute ();
		}

		// $this->forward404Unless(myUser::checkAccessObject($records, 'PS_HR_WORKINGTIME_FILTER_SCHOOL'), sprintf('Object does not exist.'));

		foreach ( $records as $record ) {
			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );

		$this->redirect ( '@ps_working_time' );
	}
}
