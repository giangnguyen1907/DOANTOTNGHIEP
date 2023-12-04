<?php

/**
 * PsWorkingTime form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsWorkingTimeForm extends BasePsWorkingTimeForm {

	public function configure() {

		$this->addPsCustomerFormNotEdit ( 'PS_HR_WORKINGTIME_FILTER_SCHOOL' );

		$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsCustomer',
				'required' => true ) );

		$this->setDefault ( 'ps_customer_id', myUser::getPscustomerID () );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id <= 0) {

			$ps_customer_id = $this->getObject ()
				->getPsCustomerId ();
		}

		if ($ps_customer_id > 0) {
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => "PsWorkplaces",
					'query' => Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id ),
					'add_empty' => _ ( '-Select basis enrollment-' ) ) );
		} else {
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => _ ( '-Select basis enrollment-' ) ) ), array (
					'class' => 'select2' ) );
		}

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkplaces',
				'column' => 'id' ) );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['start_time'] = new psWidgetFormInputTime ();

		$this->widgetSchema ['start_time']->setAttributes ( array (
				'class' => 'startTime time_picker',
				'data-mode' => "24h",
				'required' => false ) );

		$this->validatorSchema ['start_time'] = new sfValidatorTime ( array (
				'required' => false ) );

		$this->widgetSchema ['end_time'] = new psWidgetFormInputTime ();

		$this->widgetSchema ['end_time']->setAttributes ( array (
				'class' => 'endTime time_picker',
				'data-mode' => "24h",
				'required' => false ) );

		$this->validatorSchema ['end_time'] = new sfValidatorTime ( array (
				'required' => false ) );

		$this->addBootstrapForm ();

		if (! myUser::credentialPsCustomers ( 'PS_HR_WORKINGTIME_FILTER_SCHOOL' )) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control' ) );
		}
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
