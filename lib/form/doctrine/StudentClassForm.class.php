<?php
class StudentClassForm extends BaseStudentClassForm {

	public function configure() {

		$this->widgetSchema ['url_callback'] = new sfWidgetFormInputHidden ();

		$this->validatorSchema ['url_callback'] = new sfValidatorString ( array ('required' => false ) );

		$student = $this->getObject ()->getStudent ();
		
		$schoolYearsDefault = sfContext::getInstance ()->getUser ()->getAttribute ( 'ps_school_year_default' );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
				'class' => 'radiobox' ) );

		$this->validatorSchema ['is_activated'] = new sfValidatorInteger ( array (
				'required' => false ) );

		// Lay các lop cua năm học dang mặc dinh
		if ($schoolYearsDefault) {
			$choices = Doctrine::getTable ( 'MyClass' )->getChoisGroupMyClassByCustomerAndYear ( $student->getPsCustomerId (), $schoolYearsDefault->id,$this->getObject ()->getMyclassId (), PreSchool::ACTIVE );
		} else {
			$choices = Doctrine::getTable ( 'MyClass' )->getChoisGroupMyClassByCustomer ( $student->getPsCustomerId (), $this->getObject ()->getMyclassId (), PreSchool::ACTIVE );
		}
		

		$this->widgetSchema ['myclass_id'] = new sfWidgetFormSelect ( array (
				'choices' => array ('' => '-Select class-' ) + $choices ) );

		$this->validatorSchema ['myclass_id'] = new sfValidatorInteger ( array (
				'required' => true ) );

		$this->widgetSchema ['myclass_id']->setLabel ( 'To class' );

		$this->widgetSchema ['statistic_myclass_id'] = new sfWidgetFormSelect ( 
			array (
				'choices' => array ('' => '-Select class-' ) + $choices ),
			array(
				'class' => 'form-control')
		);

		$this->validatorSchema ['statistic_myclass_id'] = new sfValidatorInteger ( array (
				'required' => false ) );

		$this->widgetSchema ['statistic_myclass_id']->setLabel ( 'Statistic class' );

		$this->validatorSchema ['is_activated'] = new sfValidatorInteger ( array (
				'required' => false ) );

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
				'placeholder' => 'dd-mm-yyyy' // 'required' => fa
		) );

		$this->validatorSchema ['stop_at'] = new sfValidatorDate ( array (
				'required' => false ) );

		if ($schoolYearsDefault && $this->getObject ()->isNew ()) {
			$yearsDefaultEnd = date ( "d-m-Y", strtotime ( $schoolYearsDefault->to_date ) );
			$this->setDefault ( 'stop_at', $yearsDefaultEnd );
		}

		$this->widgetSchema ['myclass_mode'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['myclass_mode']->setLabel ( 'Saturday school' );

		$this->setDefault ( 'myclass_mode', PreSchool::NOT_ACTIVE );

		$this->validatorSchema ['myclass_mode'] = new sfValidatorInteger ( array (
				'required' => false ) );

		$this->widgetSchema ['type'] = new sfWidgetFormChoice ( array (
				'choices' => PreSchool::loadStatusStudentClass () ), array (
				'class' => 'select2',
				'style' => "min-width:200px;",
				'data-placeholder' => _ ( '-Select status studying-' ) ) );

		$this->widgetSchema ['type']->setLabel ( 'Status studying' );

		/*
		 * $this->validatorSchema ['type'] = new sfValidatorChoice( array (
		 * 'required' => false, 'choices' => array_keys(PreSchool::loadStatusStudentClass ())
		 * ) );
		 */

		$this->validatorSchema ['type'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->widgetSchema ['student_id'] = new sfWidgetFormInputHidden ();

		$this->validatorSchema ['student_id'] = new sfValidatorInteger ( array (
				'required' => true ) );

		$this->widgetSchema ['start_date_at'] = new psWidgetFormInputCheckbox ( array (), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['start_date_at']->setLabel ( 'Start date at' );

		$this->validatorSchema ['start_date_at'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->addBootstrapForm ();

		$this->showUseFields ();

		$this->removeFields ();
	}

	protected function showUseFields() {

		$this->useFields ( array (
				'myclass_id',
				'statistic_myclass_id',
				'myclass_mode',
				'start_at',
				'start_date_at',
				'stop_at',
				'student_id',
				'type',
				'is_activated' ) );
	}

	protected function removeFields() {

		unset ( $this ['created_at'], $this ['updated_at'], $this ['user_created_id'], $this ['user_updated_id'], $this ['from_myclass_id'] );
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}