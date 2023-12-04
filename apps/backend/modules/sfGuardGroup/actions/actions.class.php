<?php

/**
 * sfGuardGroup actions.
 *
 * @package sfGuardPlugin
 * @subpackage sfGuardGroup
 * @author Fabien Potencier
 * @version SVN: $Id: actions.class.php 23319 2009-10-25 12:22:23Z Kris.Wallsmith $
 */
class sfGuardGroupActions extends autosfGuardGroupActions {

	public function executeEdit(sfWebRequest $request) {

		$this->sf_guard_group = $this->getRoute ()
			->getObject ();

		// Chi Admin he thong moi co quyen xu ly du lieu chung
		if ($this->sf_guard_group->getPsCustomerId () == '' && ! myUser::isAdministrator ()) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
		}

		$this->forward404Unless ( myUser::checkAccessObject ( $this->sf_guard_group, 'PS_SYSTEM_GROUP_USER_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		if ($this->sf_guard_group->getIsSuperAdmin () == 1 && ! myUser::credentialPsCustomers ( 'PS_SYSTEM_GROUP_USER_EDIT_DETAIL' )) {
			$this->forward404Unless ( false, 'The data you asked for is secure and you do not have proper credentials.' );
		}

		$this->form = $this->configuration->getForm ( $this->sf_guard_group );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->sf_guard_group = $this->getRoute ()
			->getObject ();

		// Chi Admin he thong moi co quyen xu ly du lieu chung
		if ($this->sf_guard_group->getPsCustomerId () == '' && ! myUser::isAdministrator ()) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
		}

		$this->forward404Unless ( myUser::checkAccessObject ( $this->sf_guard_group, 'PS_SYSTEM_GROUP_USER_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		if ($this->sf_guard_group->getIsSuperAdmin () == 1 && ! myUser::credentialPsCustomers ( 'PS_SYSTEM_GROUP_USER_EDIT_DETAIL' )) {
			$this->forward404Unless ( false, 'The data you asked for is secure and you do not have proper credentials.' );
		}

		$this->form = $this->configuration->getForm ( $this->sf_guard_group );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->sf_guard_group = $this->getRoute ()
			->getObject ();

		// Chi Admin he thong moi co quyen xu ly du lieu chung
		if ($this->sf_guard_group->getPsCustomerId () == '' && ! myUser::isAdministrator ()) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
		}

		$this->forward404Unless ( myUser::checkAccessObject ( $this->sf_guard_group, 'PS_SYSTEM_GROUP_USER_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->sf_guard_group ) ) );

		if ($this->sf_guard_group->getIsSuperAdmin () == 1 && ! myUser::credentialPsCustomers ( 'PS_SYSTEM_GROUP_USER_EDIT_DETAIL' )) {
			$this->forward404Unless ( false, 'The data you asked for is secure and you do not have proper credentials.' );
		}

		if ($this->sf_guard_group->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@sf_guard_group' );
	}

	protected function executeBatchUpdateOrder(sfWebRequest $request) {

		$iorder = $request->getParameter ( 'iorder' );

		if (! count ( $iorder )) {
			$this->getUser ()
				->setFlash ( 'error', 'You must at least select one item.' );
		} else {

			$conn = Doctrine_Manager::connection ();

			$conn->beginTransaction ();

			foreach ( $iorder as $key => $value ) {

				if (! is_numeric ( $value )) {

					$this->getUser ()
						->setFlash ( 'error', 'Is not a number' );
				} else {

					$obj = Doctrine::getTable ( 'sfGuardGroup' )->findOneById ( $key );

					$obj->setIorder ( $value );

					$obj->setUserUpdatedId ( $this->getUser ()
						->getUserId () );
					$obj->setUpdatedAt ( date ( 'Y-m-d H:i:s', time () ) );

					$obj->save ();
				}
			}

			$this->getUser ()
				->setFlash ( 'notice', $this->getContext ()
				->getI18N ()
				->__ ( 'The item was updated successfully.' ) );

			$conn->commit ();
		}

		$this->redirect ( '@sf_guard_group' );
	}
}
