<?php
require_once dirname ( __FILE__ ) . '/../lib/psFunctionGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psFunctionGeneratorHelper.class.php';

/**
 * psFunction actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psFunction
 * @author quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version 1.0
 */
class psFunctionActions extends autoPsFunctionActions {

	public function executeDetail(sfWebRequest $request) {

		$this->ps_function = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->feature, 'PS_HR_FUNCTION_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_function );
		// $this->setTemplate('edit');
	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_function = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->feature, 'PS_HR_FUNCTION_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_function );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_function = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->feature, 'PS_HR_FUNCTION_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_function );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->getRoute ()
			->getObject (), 'PS_HR_FUNCTION_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_function' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$records = Doctrine_Query::create ()->from ( 'PsFunction' )
			->whereIn ( 'id', $ids );

		if (! myUser::credentialPsCustomers ( 'PS_HR_FUNCTION_FILTER_SCHOOL' ))
			$records->andWhere ( 'ps_customer_id = ?', myUser::getPscustomerID () );

		$_records = $records->execute ();

		foreach ( $_records as $record ) {
			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
		$this->redirect ( '@ps_function' );
	}
}
