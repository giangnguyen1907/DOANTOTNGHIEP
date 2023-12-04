<?php
require_once dirname ( __FILE__ ) . '/../lib/psEvaluateIndexSymbolGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psEvaluateIndexSymbolGeneratorHelper.class.php';

/**
 * psEvaluateIndexSymbol actions.
 *
 * @package kidsschool.vn
 * @subpackage psEvaluateIndexSymbol
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psEvaluateIndexSymbolActions extends autoPsEvaluateIndexSymbolActions {

	public function executeDetail(sfWebRequest $request) {

		$symbol_id = $request->getParameter ( 'id' );

		if ($symbol_id <= 0) {

			$this->forward404Unless ( $symbol_id, sprintf ( 'Object does not exist.' ) );
		}

		$this->ps_evaluate_index_symbol = Doctrine::getTable ( 'PsEvaluateIndexSymbol' )->findOneById ( $symbol_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_evaluate_index_symbol, 'PS_EVALUATE_INDEX_SYMBOL_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->ps_evaluate_index_symbol ) );

		// $this->form = $this->configuration->getForm($this->ps_evaluate_index_symbol);
	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_evaluate_index_symbol = $this->getRoute ()
			->getObject ();
		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_evaluate_index_symbol, 'PS_EVALUATE_INDEX_SYMBOL_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		$this->form = $this->configuration->getForm ( $this->ps_evaluate_index_symbol );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$symbol = $this->getRoute ()
			->getObject ();

		$symbol_id = $request->getParameter ( 'id' );

		$ps_check = Doctrine::getTable ( 'PsEvaluateIndexStudent' )->checkDataForeignSymbolExits ( $symbol_id );

		if ($ps_check) {

			$this->getUser ()
				->setFlash ( 'error', 'A problem occurs when deleting the selected items has some children relationship.' );

			$this->redirect ( '@ps_evaluate_index_symbol' );
		}

		$this->forward404Unless ( myUser::checkAccessObject ( $symbol, 'PS_EVALUATE_INDEX_SYMBOL_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $symbol ) ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_evaluate_index_symbol' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		if (myUser::credentialPsCustomers ( 'PS_EVALUATE_INDEX_SYMBOL_FILTER_SCHOOL' )) {

			$records = Doctrine_Query::create ()->from ( 'PsEvaluateIndexSymbol' )
				->whereIn ( 'id', $ids )
				->execute ();
		} else {

			$records = Doctrine_Query::create ()->from ( 'PsEvaluateIndexSymbol' )
				->whereIn ( 'id', $ids )
				->andWhere ( 'ps_customer_id = ?', myUser::getPscustomerID () )
				->execute ();
		}

		foreach ( $records as $record ) {
			$ps_check = Doctrine::getTable ( 'PsEvaluateIndexStudent' )->checkDataForeignSymbolExits ( $record->getId () );

			if ($ps_check) {
				continue;
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );

			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
		$this->redirect ( '@ps_evaluate_index_symbol' );
	}
}
