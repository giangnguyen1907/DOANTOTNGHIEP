<?php
require_once dirname ( __FILE__ ) . '/../lib/psProfessionalGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psProfessionalGeneratorHelper.class.php';

/**
 * psProfessional actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psProfessional
 * @author quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version 1.0
 */
class psProfessionalActions extends autoPsProfessionalActions {

	public function executeEdit(sfWebRequest $request) {

		$this->ps_professional = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_professional, 'PS_HR_PROFESSIONAL_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_professional );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_professional = $this->getRoute ()
			->getObject ();
		$this->forward404Unless ( myUser::checkAccessObject ( $this->getRoute ()
			->getObject (), 'PS_HR_PROFESSIONAL_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		$this->form = $this->configuration->getForm ( $this->ps_professional );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->getRoute ()
			->getObject (), 'PS_HR_PROFESSIONAL_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_professional' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$records = Doctrine_Query::create ()->from ( 'PsProfessional' )
			->whereIn ( 'id', $ids );

		if (! myUser::credentialPsCustomers ( 'PS_HR_PROFESSIONAL_FILTER_SCHOOL' ))
			$records->andWhere ( 'ps_customer_id = ?', myUser::getPscustomerID () );

		$records_delete = $records->execute ();

		foreach ( $records_delete as $record ) {
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );
			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
		$this->redirect ( '@ps_professional' );
	}
}
