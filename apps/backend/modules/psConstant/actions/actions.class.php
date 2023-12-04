<?php
require_once dirname ( __FILE__ ) . '/../lib/psConstantGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psConstantGeneratorHelper.class.php';

/**
 * psConstant actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psConstant
 * @author quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psConstantActions extends autoPsConstantActions {

	public function executeIndex(sfWebRequest $request) {

		$this->getUser ()
			->setFlash ( 'msg', 'These are important constants of the system. Please careful when data processing.' );

		parent::executeIndex ( $request );
	}

	public function executePsConstantList(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$ps_customer_id = $request->getParameter ( 'cid' );
			if ($ps_customer_id != '')
				$this->psConstants = Doctrine::getTable ( 'PsConstant' )->loadPsConstantByCustomer ( $ps_customer_id )
					->execute ();
			else
				$this->psConstants = array ();

			return $this->renderPartial ( 'psConstant/list_constant', array (
					'psConstants' => $this->psConstants ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeOption(sfWebRequest $request) {

		$this->redirect ( '@ps_constant_option' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		// Kiem tra dieu kien is_notremove
		if ($this->getRoute ()
			->getObject ()
			->getIsNotremove () == 1) { // Khong cho xoa

			$this->getUser ()
				->setFlash ( 'error', 'This item is not allowed to be deleted.' );
		} else {

			// Kiem tra ton tai du lieu ps_constant_option
			$ids = array (
					$this->getRoute ()
						->getObject ()
						->getId () );

			$ps_constant_options = Doctrine_Query::create ()->select ( 'id' )
				->from ( 'PsConstantOption' )
				->whereIn ( 'ps_constant_id', $ids )
				->execute ();

			if (count ( $ps_constant_options )) {

				$this->getUser ()
					->setFlash ( 'error', 'The item has not been remove due have data related.' );
			} elseif ($this->getRoute ()
				->getObject ()
				->delete ()) {

				$this->getUser ()
					->setFlash ( 'notice', 'The item was deleted successfully.' );
			}
		}

		$this->redirect ( '@ps_constant' );
	}
}
