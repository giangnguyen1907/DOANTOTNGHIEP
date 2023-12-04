<?php
/**
 * sfGuardGroup form.
 *
 * @package    Preschool
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardGroupForm extends PluginsfGuardGroupForm {

	public function configure() {

		if (myUser::isAdministrator ()) {
			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers (),
					'add_empty' => _ ( '-Select customer-' ) ) );

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => false ) );

			if (! $this->getObject ()->isNew ())
				$this->setDefault ( 'ps_customer_id', $this->getObject ()->getPsCustomerId () );
		} else {
			$this->addPsCustomerFormNotEdit ( 'PS_SYSTEM_GROUP_USER_FILTER_SCHOOL' );
		}

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		// Chi lay user la nhan su
		$this->addUsersExpandedForm ( 'users_list', $ps_customer_id );
		$this->widgetSchema ['users_list']->setAttributes ( array (
				'class' => 'select2' ) );

		$this->addPermissionsForm ( 'permissions_list' );

		$this->widgetSchema ['description']->setAttributes ( array (
				'class' => 'form-control',
				'rows' => '5' ) );

		$this->addBootstrapForm ();

		// Nếu không có quyền chỉnh sửa chi tiết
		if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_GROUP_USER_EDIT_DETAIL' )) {
			unset ( $this ['is_super_admin'] );
		} else {
			$this->widgetSchema ['is_super_admin'] = new psWidgetFormSelectRadio ( array (
					'choices' => PreSchool::loadPsBoolean () ), array (
					'class' => 'radiobox' ) );
		}

		if (myUser::isAdministrator ()) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'select2',
					'required' => true ) );
		} elseif (! myUser::credentialPsCustomers ( 'PS_SYSTEM_GROUP_USER_FILTER_SCHOOL' ) || ! $this->getObject ()
			->isNew ()) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control',
					'required' => 'required' ) );
		}
	}

	public function updateObject($values = null) {

		$object = parent::baseUpdateObject ( $values );

		/*
		 * if (!myUser :: isAdministrator()) {
		 * $psCustomerId = myUser :: getPscustomerID();
		 * $object->setPsCustomerId(myUser :: getPscustomerID());
		 * }
		 */

		return $object;
	}

	public function checkSomething($validator, $values) {

		$this->addUsersExpandedForm ( 'users_list', $values ["ps_customer_id"] );

		return $values;
	}
}