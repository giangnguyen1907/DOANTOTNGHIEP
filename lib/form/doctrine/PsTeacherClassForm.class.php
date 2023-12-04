<?php

/**
 * PsTeacherClass form.
 *
 * @package quanlymamnon.vn
 * @subpackage form
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsTeacherClassForm extends BasePsTeacherClassForm {

	public function configure() {

		if (! $this->getObject ()->isNew ()) {
			$my_class = $this->getObject ()->getMyClass ();
			$ps_customer_id = $my_class->getPsCustomerId ();
			
			$_school_year =  $my_class->getPsSchoolYear();
			
		} else {
			$my_class = Doctrine::getTable ( 'MyClass' )->findOneById ( $this->getObject ()->get ( 'ps_myclass_id' ) );
			$ps_customer_id = $my_class->getPsCustomerId ();
			$_school_year =  $my_class->getPsSchoolYear();
		}
		
		$this->widgetSchema ['ps_myclass_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => $this->getRelatedModelName ( 'MyClass' ),
				'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
						'ps_myclass_id' => $this->getObject ()
							->get ( 'ps_myclass_id' ) ) ),
				'add_empty' => false ) );

		$this->validatorSchema ['ps_myclass_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => $this->getRelatedModelName ( 'MyClass' ),
				'required' => true ) );

		$this->widgetSchema ['ps_myclass_id']->setLabel ( 'Class' );

		$this->widgetSchema ['ps_myclass_id']->setAttributes ( array (
				'class' => 'select2',
				'style' => "min-width:200px;width:100%",
				'required' => true ) );

		$this->widgetSchema ['ps_member_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => $this->getRelatedModelName ( 'PsMember' ),
				'query' => Doctrine::getTable ( 'PsMember' )->setSQLTeachersNotInClass ( $this->getObject ()
					->getPsMyclassId (), $ps_customer_id, $this->getObject ()
					->getPsMemberId () ),
				//'multiple' => true
		), array (
				'class' => 'select2',
				'style' => "width:100%;",
				'required' => true ) );

		$this->validatorSchema ['ps_member_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => $this->getRelatedModelName ( 'PsMember' ),
				'required' => true ) );

		$this->widgetSchema ['ps_member_id']->setLabel ( 'Teacher class' );

		$this->widgetSchema ['primary_teacher'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['primary_teacher']->setLabel ( 'HTeacher' );

		$this->widgetSchema ['start_at'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['start_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );

		$this->validatorSchema ['start_at'] = new sfValidatorDate ( array (
				'required' => true ) );
		
		$this->setDefault ( 'start_at', date ( "d-m-Y", strtotime (date ( "d-m-Y", strtotime ($_school_year->getFromDate()) )) ) );

		$this->widgetSchema ['stop_at'] = new psWidgetFormInputDate () ;

		$school_years_todate = date ( "d-m-Y", strtotime ($_school_year->getToDate()) );

		$this->setDefault ( 'stop_at', date ( "d-m-Y", strtotime ($school_years_todate) ) );

		$this->widgetSchema ['stop_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => true
				//'max' => $school_years_todate
		) );

		$this->validatorSchema ['stop_at'] = new sfValidatorDate ( array ('required' => true ) );

		$this->addBootstrapForm ();
	}

	protected function removeFields() {

		unset ( $this ['created_at'], $this ['updated_at'], $this ['user_created_id'], $this ['user_updated_id'], $this ['is_activated'] );
	}

	public function updateDefaultsFromObject() {

		parent::updateDefaultsFromObject ();
		/*
		 * if (isset($this->widgetSchema['date'])) {
		 * $this->setDefault('date', array(
		 * "from" => $this->getObject()
		 * ->getStartAt(),
		 * "to" => $this->getObject()
		 * ->getStopAt()
		 * ));
		 * }
		 */
	}

	public function processValues($values) {

		$values = parent::processValues ( $values );
		/*
		 * $values['start_at'] = $values["date"]["from"];
		 * $values['stop_at'] = $values["date"]["to"];
		 */
		return $values;
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
