<?php
class PsServiceSaturdayForm extends BasePsServiceSaturdayForm {

	public function configure() {

		if ($this->getObject ()
			->isNew ()) {
			$ps_customer_id = myUser::getPscustomerID ();
		} else {

			$student = $this->getObject ()
				->getStudent ();

			$ps_customer_id = $student->getPsCustomerId ();

			// Lay lop hoc cua hoc sinh tai thoi diem dang ky
			$student_class = $student->getClassByDate ( PsDateTime::psDatetoTime ( $this->getObject ()
				->getInputDateAt () ) );

			$this->setDefault ( 'ps_workplace_id', $student_class->getPsWorkplaceId () );

			$this->setDefault ( 'ps_class_id', $student_class->getMyclassId () );

			$this->setDefault ( 'student_id', $student->getId () );

			// Lấy danh sách service_date từ PsServiceSaturdayDate của PsServiceSaturdayID
			$service_dates = Doctrine::getTable ( 'PsServiceSaturdayDate' )->getListByPsServiceSaturdayId ( $this->getObject ()
				->getId () );

			$arr_service_dates = array ();

			foreach ( $service_dates as $service_date ) {
				$service_date = date ( "d-m-Y", strtotime ( $service_date->getServiceDate () ) );
				array_push ( $arr_service_dates, $service_date );
			}

			$this->setDefault ( 'service_date', $arr_service_dates );
		}

		$ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
			->fetchOne ()
			->getId ();

		if ($ps_customer_id > 0) {

			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
		}
		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsWorkPlaces',
				'column' => 'id' ) );

		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_school_year_id' => $ps_school_year_id );

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		if ($ps_workplace_id > 0) {

			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => true,
					'model' => 'MyClass',
					'column' => 'id' ) );
		} else {
			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorPass ( array (
					'required' => true ) );
		}

		$ps_class_id = $this->getDefault ( 'ps_class_id' );

		if ($ps_class_id > 0) {

			$this->widgetSchema ['student_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Student',
					'query' => Doctrine::getTable ( 'Student' )->setSqlListStudentsNotSaturday ( $ps_class_id ),
					'add_empty' => _ ( '-Select student-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select student-' ) ) );

			$this->validatorSchema ['student_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => true,
					'model' => 'Student',
					'column' => 'id' ) );
		} else {
			$this->widgetSchema ['student_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select student-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select students-' ) ) );

			$this->validatorSchema ['student_id'] = new sfValidatorPass ( array (
					'required' => true ) );
		}

		$student_id = $this->getDefault ( 'student_id' );

		// nguoi than
		if ($student_id > 0) {

			$this->widgetSchema ['relative_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'RelativeStudent',
					'query' => Doctrine::getTable ( 'RelativeStudent' )->sqlFindByStudentId ( $student_id, $ps_customer_id ),
					'add_empty' => _ ( '-Relative student-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Relative student-' ) ) );

			$this->validatorSchema ['relative_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => true,
					'model' => 'RelativeStudent',
					'column' => 'id' ) );
		} else {
			$this->widgetSchema ['relative_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Relative student-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Relative students-' ) ) );

			$this->validatorSchema ['relative_id'] = new sfValidatorPass ( array (
					'required' => true ) );
		}

		// lay dich vu ngay thu 7

		$this->widgetSchema ['service_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'Service',
				'query' => Doctrine::getTable ( 'Service' )->setServiceSaturday (),
				'add_empty' => _ ( '-Select service-' ) ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select service-' ) ) );

		$this->validatorSchema ['service_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'Service',
				'column' => 'id' ) );

		$this->widgetSchema ['input_date_at'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['input_date_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );

		$this->validatorSchema ['input_date_at'] = new sfValidatorDate ( array (
				'required' => true,
				'max' => date ( 'Y-m-d' ) ) );

		// lay ra thu 7 của tháng hien tai va thang ke tiep
		$service_date = PsDateTime::psListDaysValueOfMonth ( "Sat" );

		$this->widgetSchema ['service_date'] = new sfWidgetFormChoice ( array (
				'choices' => array_combine ( $service_date, $service_date ) ) );

		$this->widgetSchema ['service_date']->setOption ( 'expanded', true );
		$this->widgetSchema ['service_date']->setOption ( 'multiple', true );

		$this->validatorSchema ['service_date'] = new sfValidatorDate ( array (
				'required' => true ) );

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
/**
 * PsServiceSaturday form.
 *
 * @package kidsschool.vn
 * @subpackage form
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsServiceSaturdayForm2 extends BasePsServiceSaturdayForm {

	public function configure() {

		if ($this->getObject ()
			->isNew ()) {
			$ps_customer_id = myUser::getPscustomerID ();
		} else {

			$student = $this->getObject ()
				->getStudent ();

			$ps_customer_id = $student->getPsCustomerId ();

			// Lay lop hoc cua hoc sinh tai thoi diem dang ky
			$student_class = $student->getClassByDate ( PsDateTime::psDatetoTime ( $this->getObject ()
				->getInputDateAt () ) );

			$this->setDefault ( 'ps_workplace_id', $student_class->getPsWorkplaceId () );

			$this->setDefault ( 'ps_class_id', $student_class->getMyclassId () );

			$this->setDefault ( 'student_id', $student->getId () );
		}

		if ($ps_customer_id > 0) {

			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
		}
		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkPlaces',
				'column' => 'id' ) );

		$param_class = array (
				'ps_customer_id' => $ps_customer_id );

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		if ($ps_workplace_id > 0) {

			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'MyClass',
					'column' => 'id' ) );
		} else {
			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorPass ( array (
					'required' => false ) );
		}

		$ps_class_id = $this->getDefault ( 'ps_class_id' );

		if ($ps_class_id > 0) {

			$this->widgetSchema ['student_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Student',
					'query' => Doctrine::getTable ( 'Student' )->setSqlListStudentsByClassId ( $ps_class_id ),
					'add_empty' => _ ( '-Select student-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select student-' ) ) );

			$this->validatorSchema ['student_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'Student',
					'column' => 'id' ) );
		} else {
			$this->widgetSchema ['student_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select student-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select students-' ) ) );

			$this->validatorSchema ['student_id'] = new sfValidatorPass ( array (
					'required' => false ) );
		}

		$student_id = $this->getDefault ( 'student_id' );

		// nguoi than
		if ($student_id > 0) {

			$this->widgetSchema ['relative_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'RelativeStudent',
					'query' => Doctrine::getTable ( 'RelativeStudent' )->sqlFindByStudentId ( $student_id, $ps_customer_id ),
					'add_empty' => _ ( '-Relative student-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Relative student-' ) ) );

			$this->validatorSchema ['relative_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'RelativeStudent',
					'column' => 'id' ) );

			// Lay ra danh sach cac ngay thu 7 hoc sinh nay da dang ky
		} else {
			$this->widgetSchema ['relative_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Relative student-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Relative students-' ) ) );

			$this->validatorSchema ['relative_id'] = new sfValidatorPass ( array (
					'required' => false ) );
		}

		// lay ra thu 7 của tháng hien tai va thang ke tiep
		$service_date = PsDateTime::psListDaysValueOfMonth ( "Sat" );

		$this->widgetSchema ['service_date'] = new sfWidgetFormChoice ( array (
				'choices' => array_combine ( $service_date, $service_date ) ) );

		$this->widgetSchema ['service_date']->setOption ( 'expanded', true );
		$this->widgetSchema ['service_date']->setOption ( 'multiple', true );

		$this->widgetSchema ['input_date_at'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['input_date_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );

		$this->validatorSchema ['input_date_at'] = new sfValidatorDate ( array (
				'required' => true,
				'max' => date ( 'Y-m-d' ) ) );

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
