<?php
/**
 * PsFunction form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsFunctionForm extends BasePsFunctionForm {

	public function configure() {

		// $this->addPsCustomerForm();
		$this->addPsCustomerFormNotEdit ( 'PS_HR_FUNCTION_FILTER_SCHOOL' );

		if ($this->getObject ()
			->isNew ()) {

			$ps_customer_id = myUser::getPscustomerID ();
		} else {

			$ps_customer_id = $this->getObject ()
				->getPsCustomerId ();
		}

		$this->setDefault ( 'ps_customer_id', $ps_customer_id );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->addBootstrapForm ();

		if (! myUser::credentialPsCustomers ( 'PS_HR_FUNCTION_FILTER_SCHOOL' )) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control' ) );
		}
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
