<?php

/**
 * PsAllowance form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsAllowanceForm extends BasePsAllowanceForm {

	public function configure() {

		$this->setDefault ( 'is_activated', PreSchool::NOT_ACTIVE );

		if ($this->getObject ()
			->isNew ()) {

			$ps_customer_id = myUser::getPscustomerID ();
		} else {
			$ps_customer_id = $this->getObject ()
				->getPsCustomerId ();
		}

		$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();

		$this->setDefault ( 'ps_customer_id', $ps_customer_id );

		$this->widgetSchema ['title'] = new sfWidgetFormInput ();

		$this->validatorSchema ['title'] = new sfValidatorString ( array (), array (
				'required' => true ) );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['allowance_value'] = new sfWidgetFormInput ( array (), array (
				'type' => 'number',
				'step' => '1000.00',
				'min' => '0.00' ) );

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
