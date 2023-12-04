<?php
/**
 * Receivable form.
 *
 * @package    Preschool
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ReceivableForm extends BaseReceivableForm {

	public function configure() {

		$this->addPsCustomerFormNotEdit ( 'PS_FEE_RECEIVABLE_FILTER_SCHOOL' );

		// Bat buoc cho moi truong
		// $this->validatorSchema['ps_customer_id'] = new sfValidatorInteger(array('required' => true));
		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id > 0) {

			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'PsWorkPlaces',
					'column' => 'id' ) );
		} else {
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorInteger ( array (
					'required' => false ) );
		}

		$this->widgetSchema ['ps_school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => "PsSchoolYear",
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears () ) );

		$this->validatorSchema ['ps_school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$this->validatorSchema ['amount'] = new sfValidatorNumber ();
		$this->validatorSchema ['amount']->setOption ( 'max', 999999999 );
		$this->validatorSchema ['amount']->setOption ( 'min', - 999999999 );

		$this->widgetSchema ['amount']->setAttributes ( array (
				'type' => 'number' ) );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['description']->setAttributes ( array (
				'class' => 'form-control' ) );

		// $this->widgetSchema['note'] = new sfWidgetFormInput();
		/*
		 * $this->validatorSchema['title'] = new sfValidatorString( array( 'required' => true, 'max_length' => 255), array( 'required' => 'Required', 'max_length' => 'Maximum %max_length% characters') );
		 */

		/*
		 * $this->validatorSchema['amount'] = new sfValidatorNumber( array( 'required' => true, 'max' => 999999999, 'min'=>-999999999), array( 'required' => 'Required', 'max' => 'Must be no larger than'.'(%max%)', 'min' => 'Must be no smaller than'.'(%min%)', 'invalid' => 'Is not a number' ) );
		 */

		$this->addBootstrapForm ();

		if (! $this->getObject ()
			->isNew ()) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control' ) );
		}
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
class MyReceivableForm extends BaseReceivableForm {

	public function configure() {

		$this->useFields ( array (
				'id' ) );

		$this->validatorSchema ['amount'] = new sfValidatorNumber ();
		$this->validatorSchema ['amount']->setOption ( 'max', 999999999 );
		$this->validatorSchema ['amount']->setOption ( 'min', - 999999999 );
	}

	public function bind(array $taintedValues = null, array $taintedFiles = null) {

		$new_forms = new BaseForm ();

		foreach ( $taintedValues ['new'] as $key => $new_form ) {
			$obj = new Receivable ( false );

			$obj->setAmount ( $new_form ['amount'] );

			$obj_form = new MyReceivableListForm ( $obj );

			$new_forms->embedForm ( $key, $obj_form );

			// unset($taintedValues['new'][$key]['note']);
		}

		$this->embedForm ( 'new', $new_forms );

		parent::bind ( $taintedValues, $taintedFiles );
	}
}
class MyReceivableListForm extends BaseReceivableForm {

	public function configure() {

		// /$this->removeFields();

		// $this->widgetSchema['amount'] = new sfWidgetFormInput();
		/*
		 * $this->widgetSchema['note'] = new sfWidgetFormInput(); $this->validatorSchema['note'] = new sfValidatorString( array( 'required' => false, 'max_length' => 255), array( 'max_length' => 'Maximum %max_length% characters') );
		 */

		/*
		 * $this->validatorSchema['amount'] = new sfValidatorNumber( array( 'required' => true, 'max' => 999999999, 'min'=>-999999999), array( 'required' => 'Required Amount value', 'max' => 'Must be no larger than'.'(%max%)', 'min' => 'Must be no smaller than'.'(%min%)', 'invalid' => 'Is not a number' ) );
		 */
		$this->validatorSchema ['amount'] = new sfValidatorNumber ();
		$this->validatorSchema ['amount']->setOption ( 'max', 999999999 );
		$this->validatorSchema ['amount']->setOption ( 'min', - 999999999 );
	}
	/*
	 * protected function removeFields() { unset( $this['created_at'], $this['updated_at'] ); }
	 */
}
