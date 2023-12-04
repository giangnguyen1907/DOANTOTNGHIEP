<?php

/**
 * PsExamination form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsExaminationForm extends BasePsExaminationForm {

	public function configure() {

		$this->addPsCustomerFormNotEdit ( 'PS_MEDICAL_EXAMINATION_FILTER_SCHOOL' );
		
		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
		
		if ($ps_customer_id <= 0)
			$ps_customer_id = $this->getObject ()->getPsCustomerId ();
			
		/*
		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
		if ($ps_customer_id <= 0) {
			$ps_customer_id = myUser::getPscustomerID ();
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		*/
		
		if ($ps_customer_id > 0) {
			
			$workplace_query = Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id );
			
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => "PsWorkplaces",
					'query' => $workplace_query,
					'add_empty' => _ ( '-Select basis enrollment-' ) ) );
			
			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsWorkplaces',
				'query' => $workplace_query,
				'column' => 'id' ) );
			
		} else {
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => _ ( '-Select basis enrollment-' ) ) ), array (
					'class' => 'select2' ) );
			
			$this->validatorSchema['ps_workplace_id'] = new sfValidatorInteger(array(
				'required' => true
			));
		}
		
		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears () ) );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$this->widgetSchema ['input_date_at'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['input_date_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );

		$this->validatorSchema ['input_date_at'] = new sfValidatorDate ( array (
				'required' => true,
				'max' => date ( 'Y-m-d' ) ) );

		$this->validatorSchema->setPostValidator ( 
		new sfValidatorDoctrineUnique ( array (
				'model' => 'PsExamination',
				'column' => array (
						'input_date_at',
						'ps_workplace_id' ) ), array (
				'invalid' => 'Examination already exist.' ) ) );

		$this->addBootstrapForm ();

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ) || ! $this->isNew ()) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control' ) );
		}
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
