<?php

/**
 * PsEvaluateIndexSymbol form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsEvaluateIndexSymbolForm extends BasePsEvaluateIndexSymbolForm {

	public function configure() {

		$this->addPsCustomerFormNotEdit ( 'PS_EVALUATE_INDEX_SYMBOL_FILTER_SCHOOL' );

		if ($this->getObject ()
			->isNew ()) {

			$schoolyear_query = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ();
		} else {

			$schoolyear_query = Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears ();
		}

		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => $schoolyear_query,
				'add_empty' => '-Select school year-' ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ) ) );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
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
							
			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorString ( array (
				'required' => true ) );
		}

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$schoolyear_id = $this->getDefault ( 'school_year_id' );

		$this->validatorSchema ['symbol_code'] = new sfValidatorString ( array (
				'required' => true ) );

		// $this->widgetSchema['date_start'] = new psWidgetFormFilterInputDate();

		// $this->widgetSchema['date_start']->setAttributes(array(
		// 'data-dateformat' => 'dd-mm-yyyy',
		// 'placeholder' => 'dd-mm-yyyy',
		// 'data-original-title' => sfContext::getInstance()->getI18n()->__('From date'),
		// ));

		// $this->widgetSchema['date_start']->addOption('tooltip', sfContext::getInstance()->getI18n()->__('From date'));

		// $this->validatorSchema['date_start'] = new sfValidatorDate(array(
		// 'required' => false
		// ));

		// $this->widgetSchema['date_end'] = new psWidgetFormFilterInputDate();

		// $this->widgetSchema['date_end']->setAttributes(array(
		// 'data-dateformat' => 'dd-mm-yyyy',
		// 'placeholder' => 'dd-mm-yyyy',
		// 'data-original-title' => sfContext::getInstance()->getI18n()->__('To date'),
		// ));

		// $this->widgetSchema['date_end']->addOption('tooltip', sfContext::getInstance()->getI18n()->__('To date'));

		// $this->validatorSchema['date_end'] = new sfValidatorDate(array(
		// 'required' => false
		// ));

		$this->addBootstrapForm ();

		if (! myUser::credentialPsCustomers ( 'PS_EVALUATE_INDEX_SYMBOL_FILTER_SCHOOL' ) || ! $this->isNew ()) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control',
					'required' => 'required' ) );
		}

		// $this->mergePostValidator(new sfValidatorCallback(array(
		// 'callback' => array(
		// $this,
		// 'postValidateSymbolCodeExits'
		// )
		// )));
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}

	public function postValidateSymbolCodeExits(sfValidatorCallback $validator, array $values) {

		$param = array ();

		$param ['symbol_code'] = $values ['symbol_code'];

		$param ['ps_customer_id'] = $values ['ps_customer_id'];

		$param ['school_year_id'] = $values ['school_year_id'];

		$param ['ps_workplace_id'] = $values ['ps_workplace_id'];

		$param ['is_activated'] = $values ['is_activated'];

		$checkSymbolCodeExits = Doctrine::getTable ( 'PsEvaluateIndexSymbol' )->checkSymbolCodeExits ( $param );

		if ($checkSymbolCodeExits) {
			$error = new sfValidatorError ( $validator, 'Symbol code in this school year already exist.' );
			throw new sfValidatorErrorSchema ( $validator, array (
					"symbol_code" => $error ) );
		}

		return $values;
	}
}
