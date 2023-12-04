<?php
require_once dirname ( __FILE__ ) . '/../lib/psMealsGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psMealsGeneratorHelper.class.php';

/**
 * psMeals actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psMeals
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psMealsActions extends autoPsMealsActions {

	public function executeCustomerMeals(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$cid = intval ( $request->getParameter ( "cid" ) );

			$wp_id = intval ( $request->getParameter ( "wp_id" ) );

			$params ['ps_customer_id'] = $cid;
			$params ['ps_workplace_id'] = $wp_id;
			$params ['is_activated'] = PreSchool::ACTIVE;

			$ps_meals = Doctrine::getTable ( 'PsMeals' )->setSQLByParams ( $params )
				->execute ();

			return $this->renderPartial ( 'option_select', array (
					'option_select' => $ps_meals ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeNew(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->ps_meals = $this->form->getObject ();
	}

	public function executeCreate(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->ps_meals = $this->form->getObject ();

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'new' );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_meals = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_meals, 'PS_NUTRITION_MEALS_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_meals );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_meals = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_meals, 'PS_NUTRITION_MEALS_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_meals );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$records = Doctrine_Query::create ()->from ( 'Psmeals' )
			->whereIn ( 'id', $ids );

		$c = flase;

		if (! myUser::credentialPsCustomers ( 'PS_NUTRITION_MEALS_FILTER_SCHOOL' ))
			$records->andWhere ( 'ps_customer_id = ?', myUser::getPscustomerID () );

		$list_record = $records->execute ();

		foreach ( $list_record as $record ) {
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );
			$check = Doctrine::getTable ( 'PsMenus' )->checkMeal ( $record->id );
			$c = $check ? true : false;
		}

		if ($c) {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been remove due have data related.' );
		} else {

			$record->delete ();

			$this->getUser ()
				->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
		}

		$this->redirect ( '@ps_meals' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$object = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $object, 'PS_NUTRITION_MEALS_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $object ) ) );

		$check = Doctrine::getTable ( 'PsMenus' )->checkMeal ( $object = $this->getRoute ()
			->getObject ()->id );

		if ($check) {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been remove due have data related.' );
		} else {
			if ($this->getRoute ()
				->getObject ()
				->delete ()) {
				$this->getUser ()
					->setFlash ( 'notice', 'The item was deleted successfully.' );
			}
		}
		$this->redirect ( '@ps_meals' );
	}
}
