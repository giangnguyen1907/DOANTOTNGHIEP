<?php

/**
 * PsLogtimes form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsLogtimesForm extends BasePsLogtimesForm {

	public function configure() {

		$student = $this->getObject ()
			->getStudent ();

		$ps_customer_id = $class_id = $ps_workplace_id = null;

		if ($student->getId () > 0) {

			$tracked_at_formFilter = date ( 'd-m-Y', strtotime ( $this->getObject ()
				->getLoginAt () ) );

			$student_class = $student->getMyClassByStudent ( $tracked_at_formFilter );

			$ps_customer_id = $student_class->getPsCustomerId ();

			$class_id = $student_class->getClassId ();

			$ps_workplace_id = $student_class->getMyClass ()
				->getPsClassRooms ()
				->getPsWorkplaceId ();

			$this->widgetSchema ['student_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							$student->getId () => $student->getFirstName () . ' ' . $student->getLastName () ) ), array (
					'class' => 'form-control',
					'required' => true ) );

			$this->widgetSchema ['login_relative_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'RelativeStudent',
					'query' => Doctrine::getTable ( 'RelativeStudent' )->sqlFindByStudentId ( $student->getId (), $ps_customer_id ),
					'add_empty' => _ ( '-Select login relative-' ) ), array (
					'class' => 'select2',
					'style' => "",
					'data-placeholder' => _ ( '-Select login relative-' ),
					'required' => true ) );

			$this->widgetSchema ['logout_relative_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'RelativeStudent',
					'query' => Doctrine::getTable ( 'RelativeStudent' )->sqlFindByStudentId ( $student->getId (), $ps_customer_id ),
					'add_empty' => _ ( '-Select logout relative-' ) ), array (
					'class' => 'select2',
					'style' => "",
					'data-placeholder' => _ ( '-Select logout relative-' ) ) );

			$ps_teacher_class = Doctrine::getTable ( 'PsTeacherClass' )->getChoiceTeachersByClassId ( $class_id );

			$this->widgetSchema ['login_member_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select login member-' ) ) + $ps_teacher_class ), array (
					'class' => 'select2',
					'style' => "",
					'data-placeholder' => _ ( '-Select login member-' ),
					'required' => true ) );

			$this->widgetSchema ['logout_member_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select logout member-' ) ) + $ps_teacher_class ), array (
					'class' => 'select2',
					'style' => "",
					'data-placeholder' => _ ( '-Select logout member-' ),
					'required' => false ) );

			$this->widgetSchema ['student_service'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Service',
					'query' => Doctrine::getTable ( 'Service' )->setSqlServicesDiaryByStudent ( $student->getId (), $class_id, $tracked_at_formFilter, $ps_customer_id ) ) );

			$this->widgetSchema ['student_service']->setOption ( 'expanded', true );

			$this->widgetSchema ['student_service']->setOption ( 'multiple', true );
		} else {

			$this->widgetSchema ['student_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select student-' ) ) ), array (
					'class' => 'form-control',
					'data-placeholder' => _ ( '-Select student-' ),
					'required' => true ) );

			$this->widgetSchema ['login_relative_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select login relative-' ) ) ), array (
					'class' => 'select2',
					'style' => "",
					'data-placeholder' => _ ( '-Select login relative-' ),
					'required' => true ) );

			$this->widgetSchema ['logout_relative_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select logout relative-' ) ) ), array (
					'class' => 'select2',
					'style' => "",
					'data-placeholder' => _ ( '-Select logout relative-' ) ) );

			$this->widgetSchema ['login_member_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select login member-' ) ) ), array (
					'class' => 'select2',
					'style' => "",
					'data-placeholder' => _ ( '-Select login member-' ),
					'required' => true ) );

			$this->widgetSchema ['logout_member_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select logout member-' ) ) ), array (
					'class' => 'select2',
					'style' => "",
					'data-placeholder' => _ ( '-Select logout member-' ) ) );

			$this->widgetSchema ['student_service'] = new sfWidgetFormInputHidden ();
		}

		// gan gia tri mac dinh cho log_value
		$this->widgetSchema ['log_value'] = new sfWidgetFormInputHidden ();

		$this->validatorSchema ['log_value'] = new sfValidatorInteger ( array (
				'required' => false ) );

		$this->setDefault ( 'log_value', 1 );

		$this->widgetSchema ['tracked_at'] = new sfWidgetFormInputHidden ();

		$this->validatorSchema ['tracked_at'] = new sfValidatorDate ( array (
				'required' => true ) );

		$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['class_id'] = new sfWidgetFormInputHidden ();

		$this->validatorSchema ['ps_customer_id'] = new sfValidatorInteger ( array (
				'required' => true ) );

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorInteger ( array (
				'required' => true ) );

		$this->validatorSchema ['class_id'] = new sfValidatorInteger ( array (
				'required' => true ) );

		$this->widgetSchema ['login_at'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['login_at']->setAttributes ( array (
				'class' => 'time_picker form-control',
				'maxlength' => "5",
				'required' => true ) );

		$this->widgetSchema ['note'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['note']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => "255" ) );

		$this->widgetSchema ['logout_at'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['logout_at']->setAttributes ( array (
				'class' => 'time_picker form-control',
				'maxlength' => "5" ) );

		$this->validatorSchema ['student_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'Student',
				'required' => true ) );

		$this->validatorSchema ['login_relative_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'Relative',
				'required' => true ) );

		$this->validatorSchema ['login_member_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsMember',
				'required' => true ) );

		$this->validatorSchema ['logout_relative_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'Relative',
				'required' => false ) );

		$this->validatorSchema ['logout_member_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsMember',
				'required' => false ) );

		$this->validatorSchema ['student_service'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'Service',
				'required' => false ) );

		$this->setDefault ( 'ps_customer_id', $ps_customer_id );
		$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		$this->setDefault ( 'class_id', $class_id );
		// $subForm = new sfForm();

		// $student_service = new StudentServiceDiary();

		// $student_service_form = new StudentServiceDiaryForm($student_service);

		// $subForm->embedForm(null, $student_service_form);

		// $this->embedForm('student_service', $subForm);

		// $this->addBootstrapForm();
	}

	public function processValues($values) {

		$values = parent::processValues ( $values );
		$values ['login_at'] = date ( "Y-m-d", strtotime ( $values ["tracked_at"] ) ) . ' ' . date ( "H:i:s", strtotime ( $values ["login_at"] ) );
		$values ['logout_at'] = $values ["logout_at"] ? date ( "Y-m-d", strtotime ( $values ["tracked_at"] ) ) . ' ' . date ( "H:i:s", strtotime ( $values ["logout_at"] ) ) : null;

		return $values;
	}
}