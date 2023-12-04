<?php
require_once dirname ( __FILE__ ) . '/../lib/psConstantOptionGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psConstantOptionGeneratorHelper.class.php';

/**
 * psConstantOption actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psConstantOption
 * @author quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psConstantOptionActions extends autoPsConstantOptionActions {

	public function executeConstant(sfWebRequest $request) {

		$this->redirect ( '@ps_constant' );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_constant_option = $this->getRoute ()
			->getObject ();
		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_constant_option, 'PS_SYSTEM_CONSTANT_OPTION_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		$this->form = $this->configuration->getForm ( $this->ps_constant_option );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_constant_option = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_constant_option, 'PS_SYSTEM_CONSTANT_OPTION_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_constant_option );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->getRoute ()
			->getObject (), 'PS_SYSTEM_CONSTANT_OPTION_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_constant_option' );
	}
}