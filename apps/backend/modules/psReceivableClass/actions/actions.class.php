<?php
require_once dirname ( __FILE__ ) . '/../lib/psReceivableClassGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psReceivableClassGeneratorHelper.class.php';

/**
 * psReceivableClass actions.
 *
 * @package kidsschool.vn
 * @subpackage psReceivableClass
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psReceivableClassActions extends autoPsReceivableClassActions {

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->getRoute ()
			->getObject (), 'PS_FEE_RECEIVABLE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_receivable_class' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		if (myUser::credentialPsCustomers ( 'PS_FEE_RECEIVABLE_FILTER_SCHOOL' )) {
			$records = Doctrine_Query::create ()->from ( 'ReceivableTemp' )
				->whereIn ( 'id', $ids )
				->execute ();
		} else {
			$records = Doctrine_Query::create ()->from ( 'ReceivableTemp' )
				->whereIn ( 'id', $ids )
				->innerJoin ( 'MyClass mc' )
				->andWhere ( 'mc.ps_customer_id = ?', myUser::getPscustomerID () )
				->execute ();
		}

		foreach ( $records as $record ) {
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );

			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
		$this->redirect ( '@ps_receivable_class' );
	}
}
