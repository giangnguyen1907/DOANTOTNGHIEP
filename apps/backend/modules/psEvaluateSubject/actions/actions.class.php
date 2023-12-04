<?php
require_once dirname ( __FILE__ ) . '/../lib/psEvaluateSubjectGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psEvaluateSubjectGeneratorHelper.class.php';

/**
 * psEvaluateSubject actions.
 *
 * @package kidsschool.vn
 * @subpackage psEvaluateSubject
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psEvaluateSubjectActions extends autoPsEvaluateSubjectActions {

	/**
	 * Lay danh sach chu de danh gia tre em boi params: truong, co so dao tao
	 */
	public function executeEvaluateSubjectByParams(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$c_id = $request->getParameter ( 'c_id' );
			$w_id = $request->getParameter ( 'w_id' );
			$y_id = $request->getParameter ( 'y_id' );
			$subject_id = $request->getParameter ( 'id' );
			$state = $request->getParameter ( 'state' );

			$this->ps_subject = array ();

			if (myUser::credentialPsCustomers ( 'PS_EVALUATE_INDEX_CRITERIA_FILTER_SCHOOL' )) {

				$this->ps_subject = Doctrine::getTable ( 'PsEvaluateSubject' )->setSQLEvaluateIndexSubjectByParam ( array (
						'ps_customer_id' => $c_id,
						'ps_workplace_id' => $w_id,
						'subject_id' => $subject_id,
						'school_year_id' => $y_id,
						'is_activated' => $state ) )
					->execute ();
			} else {
				$this->ps_subject = Doctrine::getTable ( 'PsEvaluateSubject' )->setSQLEvaluateIndexSubjectByParam ( array (
						'ps_customer_id' => myUser::getPscustomerID (),
						'ps_workplace_id' => $w_id,
						'subject_id' => $subject_id,
						'school_year_id' => $y_id,
						'is_activated' => $state ) )
					->execute ();
			}

			return $this->renderPartial ( 'psEvaluateSubject/option_select_subject', array (
					'option_select' => $this->ps_subject ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeDetail(sfWebRequest $request) {

		$this->ps_evaluate_subject = $this->getRoute ()
			->getObject ();
		$this->form = $this->configuration->getForm ( $this->ps_evaluate_subject );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_evaluate_subject = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_evaluate_subject, 'PS_EVALUATE_INDEX_SUBJECT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_evaluate_subject );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$symbol = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $symbol, 'PS_EVALUATE_INDEX_SUBJECT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_evaluate_subject' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		if (myUser::credentialPsCustomers ( 'PS_EVALUATE_INDEX_SUBJECT_FILTER_SCHOOL' )) {

			$records = Doctrine_Query::create ()->from ( 'PsEvaluateSubject' )
				->whereIn ( 'id', $ids )
				->execute ();
		} else {

			$records = Doctrine_Query::create ()->from ( 'PsEvaluateSubject' )
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
		$this->redirect ( '@ps_evaluate_subject' );
	}
}
