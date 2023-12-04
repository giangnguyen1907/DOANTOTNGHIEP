<?php

/**
 * PsMemberSalary form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsMemberSalaryForm extends BasePsMemberSalaryForm {

	public function configure() {

		$this->widgetSchema ['ps_member_id'] = new sfWidgetFormInputHidden ();

		// $this->addPsCustomerFormNotEdit('PS_HR_HR_FILTER_SCHOOL');
		$ps_member = $this->getObject ()
			->getPsMember ();

		$ps_customer_id = $ps_member->getPsCustomerId ();

		$ps_member_id = $this->getObject ()
			->getPsMemberId ();

		$this->validatorSchema ['ps_member_id'] = new sfValidatorInteger ( array (
				'required' => true ) );

		$this->setDefault ( 'ps_member_id', $ps_member_id );

		if ($this->isNew ())
			$ps_customer_id = $this->getObject ()
				->getPsMember ()
				->getPsCustomerId ();
		else
			$ps_customer_id = $this->getObject ()
				->getPsSalary ()
				->getPsCustomerId ();
		$is_activated = PreSchool::ACTIVE;

		// ========================================================================
		if ($ps_customer_id > 0) {

			$this->widgetSchema ['ps_salary_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => "PsSalary",
					'query' => Doctrine::getTable ( 'PsSalary' )->setSQLByCustomerId ( $ps_customer_id, $is_activated ),
					'add_empty' => _ ( '-Select basic salary-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;" ) );
		} else {

			$this->widgetSchema ['ps_salary_id'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => _ ( '-Select basic salary-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;" ) );
		}

		$this->validatorSchema ['ps_salary_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSalary',
				'column' => 'id' ) );

		// ========================================================================
		$this->widgetSchema ['days_working'] = new sfWidgetFormInput ( array (), array (
				'type' => 'number',
				'max' => '31',
				'min' => '1' ) );

		$this->widgetSchema ['start_at'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['start_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );

		$this->validatorSchema ['start_at'] = new sfValidatorDate ( array (
				'required' => true ) );

		$this->widgetSchema ['stop_at'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['stop_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy' ) );

		$this->validatorSchema ['stop_at'] = new sfValidatorDate ( array (
				'required' => false ) );

		$this->addBootstrapForm ();
		$this->widgetSchema ['ps_salary_id']->setAttributes ( array (
				'class' => 'form-control',
				'required' => true ) );
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
