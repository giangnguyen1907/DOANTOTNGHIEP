<?php
require_once dirname ( __FILE__ ) . '/../lib/psSalaryGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psSalaryGeneratorHelper.class.php';

/**
 * psSalary actions.
 *
 * @package kidsschool.vn
 * @subpackage psSalary
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psSalaryActions extends autoPsSalaryActions {

	public function executeDetail(sfWebRequest $request) {

		// $this->ps_salary = $this->getRoute()->getObject();
		// $this->form = $this->configuration->getForm($this->ps_salary);
		$ps_salary_id = $request->getParameter ( 'id' );

		if ($ps_salary_id <= 0) {
			$this->forward404Unless ( $ps_salary_id, sprintf ( 'Object does not exist.' ) );
		}

		$this->ps_salary = Doctrine::getTable ( 'PsSalary' )->getSalaryById ( $ps_salary_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_salary, 'PS_HR_SALARY_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $ps_salary_id ) );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$ps_salary_id = $request->getParameter ( 'id' );

		if ($ps_salary_id <= 0) {

			$this->forward404Unless ( $ps_salary_id, sprintf ( 'Object does not exist.' ) );
		}

		$ps_salary = Doctrine::getTable ( 'PsMemberSalary' )->checkMemberSalaryExits ( $ps_salary_id );
		// print_r($ps_salary);die;
		if ($ps_salary) {

			$this->getUser ()
				->setFlash ( 'error', 'A problem occurs when deleting the selected items has some children relationship.' );

			$this->redirect ( '@ps_salary' );
		}

		$this->forward404Unless ( myUser::checkAccessObject ( $ps_salary, 'PS_HR_SALARY_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $ps_salary_id ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_salary' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$ps_salary = Doctrine::getTable ( 'PsMemberSalary' )->checkMemberSalaryExits ( $ids );

		if (count ( $ps_salary ) > 0) {

			$this->getUser ()
				->setFlash ( 'error', 'A problem occurs when deleting the selected items has some children relationship.' );

			$this->redirect ( '@ps_salary' );
		}

		if (myUser::credentialPsCustomers ( 'PS_HR_SALARY_FILTER_SCHOOL' )) {
			$records = Doctrine_Query::create ()->from ( 'PsSalary' )
				->whereIn ( 'id', $ids )
				->execute ();
		} else {
			$records = Doctrine_Query::create ()->from ( 'PsSalary' )
				->whereIn ( 'id', $ids )
				->andWhere ( 'ps_customer_id = ?', myUser::getPscustomerID () )
				->execute ();
		}

		// $this->forward404Unless ( myUser::checkAccessObject( $ids,'PS_HR_SALARY_FILTER_SCHOOL'), sprintf ( 'Object (%s) does not exist .', $ids ) );

		foreach ( $records as $record ) {
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );

			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
		$this->redirect ( '@ps_salary' );
	}
}
