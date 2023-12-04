<?php

/**
 * PsConfigLatePayment form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsConfigLatePaymentForm extends BasePsConfigLatePaymentForm {

	public function configure() {

		$this->addPsCustomerFormNotEdit ( 'PS_FEE_CONFIG_LATE_PAYMENT_FILTER_SCHOOL' );

		$ps_customer_id = $this->getObject ()
			->getPsCustomerId ();

		if ($ps_customer_id <= 0) {
			$ps_customer_id = myUser::getPscustomerID ();
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		$school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
			->fetchOne ()
			->getId ();

		$this->setDefault ( 'school_year_id', $school_year_id );

		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => "PsSchoolYear",
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears () ) );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		// Lay nam hoc hien tai
		if ($school_year_id == '') {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
		} else {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'id', $school_year_id );
		}

		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );

		$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );

		$this->widgetSchema ['ps_month'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select month-' ) ) + PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) );

		$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );

		if ($ps_customer_id > 0) {

			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'required' => true ) );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorInteger ( array (
					'required' => true ) );
		}

		$this->widgetSchema ['from_date'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['from_date']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );

		$this->validatorSchema ['from_date'] = new sfValidatorDate ( array (
				'required' => true ) );

		$this->widgetSchema ['to_date'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['to_date']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );

		$this->validatorSchema ['to_date'] = new sfValidatorDate ( array (
				'required' => true ) );

		$this->widgetSchema ['price']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 10,
				'min' => 1,
				'required' => true,
				'type' => 'number' ) );

		$this->validatorSchema ['price'] = new sfValidatorNumber ( array (
				'required' => true ) );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		$object = parent::baseUpdateObject ( $values );

		return $object;
	}
}
