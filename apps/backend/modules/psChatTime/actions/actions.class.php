<?php
require_once dirname ( __FILE__ ) . '/../lib/psChatTimeGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psChatTimeGeneratorHelper.class.php';

/**
 * psChatTime actions.
 *
 * @package kidsschool.vn
 * @subpackage psChatTime
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psChatTimeActions extends autoPsChatTimeActions {

	public function executeEdit(sfWebRequest $request) {

		$this->ps_chat_time = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_chat_time, 'PS_SYSTEM_CHAT_TIME_CONFIG_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_chat_time );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_chat_time = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_chat_time, 'PS_SYSTEM_CHAT_TIME_CONFIG_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		$this->form = $this->configuration->getForm ( $this->ps_chat_time );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->getRoute ()
			->getObject (), 'PS_SYSTEM_CHAT_TIME_CONFIG_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_chat_time' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		if (myUser::credentialPsCustomers ( 'PS_SYSTEM_CHAT_TIME_CONFIG_FILTER_SCHOOL' )) {
			$records = Doctrine_Query::create ()->from ( 'PsChatTime' )
				->whereIn ( 'id', $ids )
				->execute ();
		} else {
			$records = Doctrine_Query::create ()->from ( 'PsChatTime' )
				->whereIn ( 'id', $ids )
				->andWhere ( 'ps_customer_id = ?', myUser::getPscustomerID () )
				->execute ();
		}

		// $this->forward404Unless(myUser::checkAccessObject($records, 'PS_SYSTEM_CHAT_TIME_CONFIG_FILTER_SCHOOL'), sprintf('Object does not exist.'));

		foreach ( $records as $record ) {
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );

			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
		$this->redirect ( '@ps_chat_time' );
	}
}
