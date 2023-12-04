<?php
require_once dirname ( __FILE__ ) . '/../lib/psConfigLateFeesGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psConfigLateFeesGeneratorHelper.class.php';

/**
 * psConfigLateFees actions.
 *
 * @package kidsschool.vn
 * @subpackage psConfigLateFees
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psConfigLateFeesActions extends autoPsConfigLateFeesActions {

	public function executeEdit(sfWebRequest $request) {

		$this->ps_config_late_fees = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_config_late_fees, 'PS_FEE_CONFIG_LATE_FEES_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_config_late_fees );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_config_late_fees = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_config_late_fees, 'PS_FEE_CONFIG_LATE_FEES_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_config_late_fees );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->getRoute ()
			->getObject (), 'PS_FEE_CONFIG_LATE_FEES_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_config_late_fees' );
	}
}
