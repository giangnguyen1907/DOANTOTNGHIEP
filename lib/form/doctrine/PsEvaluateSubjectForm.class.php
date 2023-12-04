<?php

/**
 * PsEvaluateSubject form.
 *
 * @package kidsschool.vn
 * @subpackage form
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsEvaluateSubjectForm extends BasePsEvaluateSubjectForm {

	public function configure() {

		/*
		$this->addPsCustomerFormNotEdit('PS_EVALUATE_INDEX_SUBJECT_FILTER_SCHOOL');
		
		if ($this->getObject()->isNew()) {
			$ps_customer_id = myUser::getPscustomerID();
			$school_year_query = Doctrine::getTable('PsSchoolYear')->getPsSchoolYearsDefault();
			$this->setDefault('ps_customer_id', $ps_customer_id);
		} else {
			$school_year_query = Doctrine::getTable('PsSchoolYear')->setSqlPsSchoolYears();
			$ps_customer_id = $this->getDefault('ps_customer_id');
		}
		*/
		
		if (myUser::credentialPsCustomers ( 'PS_EVALUATE_INDEX_SYMBOL_FILTER_SCHOOL' )) {
			$ps_customer_id = null;
		} else {
			$ps_customer_id = myUser::getPscustomerID ();
		}
		
		if (! $this->getObject ()->isNew ()) {

			$ps_customer_id = $this->getObject ()->getPsCustomerId ();

			$ps_workplace_id = $this->getObject ()->getPsWorkplaceId ();

			$this->setDefault ( 'ps_customer_id', $ps_customer_id );

			$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		}
		
		$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsCustomer',
				'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::ACTIVE, $ps_customer_id ),
				'add_empty' => '-Select customer-' ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select customer-' ) ) );

		$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsCustomer',
				'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::ACTIVE, $ps_customer_id ),
				'column' => 'id' ) );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id > 0) {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => "PsWorkplaces",
					'query' => Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id ),
					'add_empty' => _ ( '-Select workplaces-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplaces-' ) ) );
			
			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkplaces',
				'query' => Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id ),
				'column' => 'id' ) );
			
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => _ ( '-Select workplaces-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplaces-' ) ) );
							
			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorInteger( array (
				'required' => false ) );
		}
		
		$this->widgetSchema['school_year_id'] = new sfWidgetFormDoctrineChoice(array(
				'model' => 'PsSchoolYear',
				'query' => $school_year_query,
				'add_empty' => '-Select school year-'
		), array(
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _('-Select school year-')
		));
		
		$this->validatorSchema['school_year_id'] = new sfValidatorDoctrineChoice(array(
				'required' => false,
				'model' => 'PsSchoolYear',
				'query' => $school_year_query,
				'column' => 'id'
		));
		
		$this->widgetSchema['is_activated'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsActivity()
		), array(
				'class' => 'radiobox'
		));
		
		$this->validatorSchema['subject_code'] = new sfValidatorString(array(
				'required' => true
		));
		
		$this->addBootstrapForm();
		
		if (! myUser::credentialPsCustomers('PS_EVALUATE_INDEX_SUBJECT_FILTER_SCHOOL' ) || ! $this->isNew ()) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control',
					'required' => 'required' ) );
		}
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
