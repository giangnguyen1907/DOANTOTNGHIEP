<?php

/**
 * PsSalary form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsSalaryForm extends BasePsSalaryForm {

	public function configure() {

		$this->addPsCustomerFormNotEdit ( 'PS_HR_SALARY_FILTER_SCHOOL' );

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

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['basic_salary'] = new sfWidgetFormInput ( array (), array (
				'type' => 'number',
				'step' => '.01',
				'min' => '0.01' ) );
		$this->widgetSchema ['day_work_per_month'] = new sfWidgetFormInput ( array (), array (
				'type' => 'number',
				'max' => '31',
				'min' => '1' ) );

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
