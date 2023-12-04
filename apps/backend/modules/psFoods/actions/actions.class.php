<?php
require_once dirname ( __FILE__ ) . '/../lib/psFoodsGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psFoodsGeneratorHelper.class.php';

/**
 * psFoods actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psFoods
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psFoodsActions extends autoPsFoodsActions {

	public function executeCustomerFoods(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$cid = intval ( $request->getParameter ( "cid" ) );

			$ps_foods = Doctrine::getTable ( 'PsFoods' )->setSQLByCustomerId ( 'id, title', $cid )->execute ();

			return $this->renderPartial ( 'option_select', array (
					'option_select' => $ps_foods ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeNew(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->ps_foods = $this->form->getObject ();
	}

	public function executeCreate(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->ps_foods = $this->form->getObject ();

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'new' );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_foods = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_foods, 'PS_NUTRITION_FOOD_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->getRoute ()
			->getObject ()
			->getId () ) );

		$this->form = $this->configuration->getForm ( $this->ps_foods );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_foods = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_foods, 'PS_NUTRITION_FOOD_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->getRoute ()
			->getObject ()
			->getId () ) );

		$this->form = $this->configuration->getForm ( $this->ps_foods );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$records = Doctrine_Query::create ()->from ( 'PsFoods' )
			->whereIn ( 'id', $ids );

		if (! myUser::credentialPsCustomers ( 'PS_NUTRITION_FOOD_FILTER_SCHOOL' )) {
			$records->andWhere ( 'ps_customer_id = ?', myUser::getPscustomerID () );
		}

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

		$this->redirect ( '@ps_foods' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$object = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $object, 'PS_NUTRITION_FOOD_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->getRoute ()
			->getObject ()
			->getId () ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $object ) ) );

		$check = Doctrine::getTable ( 'PsMenus' )->checkFood ( $this->getRoute ()
			->getObject ()
			->getId () );

		if ($check) {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been remove due have data related.' );
		} else {
			
			$link = sfConfig::get ( 'sf_web_dir' ) . '/uploads/ps_nutrition/';
			
			$file_name = $this->getRoute () ->getObject ()->getFileImage ();
			
			if (is_file ( $link . '/' . $file_name )) {
				unlink ( $link . '/' . $file_name );
			}
			
			if (is_file ( $link . '/thumb/' . $file_name )) {
				unlink ( $link . '/thumb/' . $file_name );
			}
			
			if ($this->getRoute ()
				->getObject ()
				->delete ()) {
				$this->getUser ()
					->setFlash ( 'notice', 'The item was deleted successfully.' );
			}
		}

		$this->redirect ( '@ps_foods' );
	}
}
