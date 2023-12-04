<?php
require_once dirname ( __FILE__ ) . '/../lib/psDepartmentGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psDepartmentGeneratorHelper.class.php';

/**
 * psDepartment actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psDepartment
 * @author quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version 1.0
 */
class psDepartmentActions extends autoPsDepartmentActions {

	public function executeEdit(sfWebRequest $request) {

		$this->ps_department = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_department, 'PS_HR_DEPARTMENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_department );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_department = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_department, 'PS_HR_DEPARTMENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_department );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->getRoute ()
			->getObject (), 'PS_HR_DEPARTMENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$ps_department_id = $request->getParameter ( 'id' );

		$ps_department = Doctrine::getTable ( 'PsMemberDepartments' )->checkMemberDepartmentExits ( $ps_department_id );
		if ($ps_department) {

			$this->getUser ()
				->setFlash ( 'error', 'A problem occurs when deleting the selected items has some children relationship.' );

			$this->redirect ( '@ps_department' );
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

		$this->redirect ( '@ps_department' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$ps_department = Doctrine::getTable ( 'PsMemberDepartments' )->checkMemberDepartmentExits ( $ids );

		if ($ps_department) {

			$this->getUser ()
				->setFlash ( 'error', 'A problem occurs when deleting the selected items has some children relationship.' );

			$this->redirect ( '@ps_department' );
		}

		if (myUser::credentialPsCustomers ( 'PS_HR_DEPARTMENT_FILTER_SCHOOL' )) {
			$records = Doctrine_Query::create ()->from ( 'PsDepartment' )
				->whereIn ( 'id', $ids )
				->execute ();
		} else {
			$records = Doctrine_Query::create ()->from ( 'PsDepartment' )
				->whereIn ( 'id', $ids )
				->andWhere ( 'ps_customer_id = ?', myUser::getPscustomerID () )
				->execute ();
		}
		foreach ( $records as $record ) {
			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );

		$this->redirect ( '@ps_department' );
	}
}
