<?php

/**
 * ReceivableTemp form.
 *
 * @package    backend
 * @subpackage form
 * @author     Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ReceivableTempForm extends BaseReceivableTempForm {

	public function configure() {

		$this->setDefault ( 'ps_customer_id', $ps_customer_id );

		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault () // 'add_empty' => '-Select school year-'
		), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ) ) );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$schoolyear_id = $this->getDefault ( 'school_year_id' );

		if (myUser::credentialPsCustomers ( 'PS_FEE_RECEIVABLE_FILTER_SCHOOL' )) {

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ) // 'add_empty' => _('-Select customer-')
			), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select customer-' ) ) );
		} else {

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();

			$this->setDefault ( 'ps_customer_id', myUser::getPscustomerID () );
		}

		$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsCustomer',
				'column' => 'id' ) );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id > 0) {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => "PsWorkplaces",
					'query' => Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id ),
					'add_empty' => _ ( '-Select basis enrollment-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplaces-' ) ) );

			$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => _ ( '-Select workplaces-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplaces-' ) ) );
		}

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				// 'required' => true,
				'required' => false,
				'model' => 'PsWorkplaces',
				'column' => 'id' ) );

		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id );

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		if ($ps_workplace_id > 0) {

			$workplace = Doctrine::getTable ( 'PsWorkplaces' )->findOneById ( $ps_workplace_id );

			$this->widgetSchema ['ps_myclass_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_myclass_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => true,
					'model' => 'MyClass',
					'column' => 'id' ) );
		} else {
			$this->widgetSchema ['ps_myclass_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_myclass_id'] = new sfValidatorPass ( array (
					// 'required' => true
					'required' => false ) );
		}

		$this->widgetSchema ['amount'] = new sfWidgetFormInput ( array (), array (
				'type' => 'number',
				'step' => '10000',
				'min' => '0' ) );

		$this->widgetSchema ['receivable_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'Receivable',
				'query' => Doctrine::getTable ( 'Receivable' )->setListReceivableTempByParams ( array (
						'ps_school_year_id' => $schoolyear_id,
						'ps_customer_id' => $ps_customer_id // 'ps_workplace_id' => $wp_id
				) ),
				'add_empty' => _ ( '-Select receivable-' ) ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select receivable-' ) ) );

		$this->validatorSchema ['receivable_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'Receivable',
				'column' => 'id' ) );

		$this->widgetSchema ['receivable_at'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['receivable_at']->setAttributes ( array (
				'class' => 'receivable_at date_picker',
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );

		$this->validatorSchema ['receivable_at'] = new sfValidatorDate ( array (
				'required' => true,
				'max' => date ( 'Y-m-d' ) ) );

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
