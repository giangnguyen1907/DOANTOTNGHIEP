<?php
require_once dirname ( __FILE__ ) . '/../lib/psAllowanceGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psAllowanceGeneratorHelper.class.php';

/**
 * psAllowance actions.
 *
 * @package kidsschool.vn
 * @subpackage psAllowance
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psAllowanceActions extends autoPsAllowanceActions {

	public function executeDetail(sfWebRequest $request) {

		$ps_allowance_id = $request->getParameter ( 'id' );

		if ($ps_allowance_id <= 0) {
			$this->forward404Unless ( $ps_allowance_id, sprintf ( 'Object does not exist.' ) );
		}

		$this->ps_allowance = Doctrine::getTable ( 'PsAllowance' )->getAllowanceById ( $ps_allowance_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_allowance, 'PS_HR_SALARY_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $ps_allowance_id ) );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_allowance = $this->getRoute ()
			->getObject ();
		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_allowance, 'PS_HR_SALARY_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $ps_allowance_id ) );
		$this->form = $this->configuration->getForm ( $this->ps_allowance );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$id = $request->getParameter ( 'id' );

		if ($id <= 0) {

			$this->forward404Unless ( $id, sprintf ( 'Object does not exist.' ) );
		}

		$ps_allowance = Doctrine::getTable ( 'PsMemberAllowance' )->checkMemberAllowanceExits ( $id );

		if (count ( $ps_allowance ) > 0) {

			$this->getUser ()
				->setFlash ( 'error', 'A problem occurs when deleting the selected items has some children relationship.' );

			$this->redirect ( '@ps_allowance' );
		}

		$ps_allowance = Doctrine::getTable ( 'Allowance' )->findOneById ( $id );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->getRoute ()
			->getObject (), 'PS_HR_SALARY_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $id ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_allowance' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$ps_allowance = Doctrine::getTable ( 'PsMemberAllowance' )->checkMemberAllowanceExits ( $ids );

		if (count ( $ps_allowance ) > 0) {

			$this->getUser ()
				->setFlash ( 'error', 'A problem occurs when deleting the selected items has some children relationship.' );

			$this->redirect ( '@ps_allowance' );
		}

		if (myUser::credentialPsCustomers ( 'PS_HR_SALARY_FILTER_SCHOOL' )) {
			$records = Doctrine_Query::create ()->from ( 'PsAllowance' )
				->whereIn ( 'id', $ids )
				->execute ();
		} else {
			$records = Doctrine_Query::create ()->from ( 'PsAllowance' )
				->whereIn ( 'id', $ids )
				->andWhere ( 'ps_customer_id = ?', myUser::getPscustomerID () )
				->execute ();
		}

		foreach ( $records as $record ) {
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );

			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
		$this->redirect ( '@ps_allowance' );
	}
}
