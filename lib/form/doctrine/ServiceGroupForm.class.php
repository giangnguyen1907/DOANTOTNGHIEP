<?php

/**
 * ServiceGroup form.
 *
 * @package    backend
 * @subpackage form
 * @author     Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ServiceGroupForm extends BaseServiceGroupForm {

	public function configure() {

		$this->addPsCustomerFormNotEdit ( 'PS_STUDENT_SERVICE_GROUP_FILTER_SCHOOL' );
		$this->addBootstrapForm ();

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_GROUP_FILTER_SCHOOL' )) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control',
					'required' => 'required' ) );
		}
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
