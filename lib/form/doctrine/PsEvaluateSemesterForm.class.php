<?php
/**
 * PsEvaluateSemester form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsEvaluateSemesterForm extends BasePsEvaluateSemesterForm {

	public function configure() {

		/*
		 * if (! $this->getObject ()->isNew ()) {
		 * $student = $this->getObject ()->getStudent ();
		 * $ps_customer_id = $student->getPsCustomerId ();
		 * $student_class = $student->getClassByDate ( PsDateTime::psDatetoTime ( date ( 'Y-m-d' ) ) );
		 * $this->setDefault ( 'ps_workplace_id', $student_class->getPsWorkplaceId () );
		 * $this->setDefault ( 'ps_class_id', $student_class->getMyclassId () );
		 * $this->setDefault ( 'student_id', $student->getId () ); } else {
		 * if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' )) {
		 * $ps_customer_id = myUser::getPscustomerID ();
		 * $ps_customer = Doctrine::getTable ( 'PsCustomer' )->findOneById ( $ps_customer_id );
		 * $this->widgetSchema ['ps_customer_id'] = new sfWidgetFormChoice ( array ( 'choices' => array ( $ps_customer->getId () => $ps_customer->getSchoolCode () . '-' . $ps_customer->getSchoolName () ) ) );
		 * $this->validatorSchema ['ps_customer_id'] = new sfValidatorChoice ( array ( 'choices' => array ( $ps_customer_id ) ) );
		 * $this->widgetSchema ['ps_customer_id']->setAttributes ( array ( 'class' => 'form-control', 'required' => true ) );
		 * $this->setDefault ( 'ps_customer_id', $ps_customer_id ); } else {
		 * $ps_customer_id = sfContext::getInstance ()->getRequest ()->getParameter ( 'ps_customer_id' );
		 * $ps_workplace_id = sfContext::getInstance ()->getRequest ()->getParameter ( 'ps_workplace_id' );
		 * $ps_class_id = sfContext::getInstance ()->getRequest ()->getParameter ( 'ps_class_id' );
		 * // Lay nhung truong hoc dang hoat dong $this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array ( 'model' => 'PsCustomer', 'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::ACTIVE, null ), 'add_empty' => _ ( '-Select customer-' ) ) );
		 * $this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array ( 'required' => true, 'model' => 'PsCustomer', 'column' => 'id' ) );
		 * $this->setDefault ( 'ps_customer_id', $ps_customer_id );
		 * $this->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		 * $this->setDefault ( 'ps_class_id', $ps_class_id );
		 * $ps_customer_id = $this->getDefault ( 'ps_customer_id' ); } }
		 */
		if (! $this->getObject ()
			->isNew ()) {

			$student = $this->getObject ()
				->getStudent ();

			$ps_customer_id = $student->getPsCustomerId ();

			$student_class = $student->getClassByDate ( PsDateTime::psDatetoTime ( date ( 'Y-m-d' ) ) );

			$this->setDefault ( 'ps_workplace_id', $student_class->getPsWorkplaceId () );

			$this->setDefault ( 'ps_class_id', $student_class->getMyclassId () );

			$this->setDefault ( 'student_id', $student->getId () );

			$ps_customer = Doctrine::getTable ( 'PsCustomer' )->findOneById ( $ps_customer_id );

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							$ps_customer->getId () => $ps_customer->getSchoolCode () . '-' . $ps_customer->getSchoolName () ) ) );

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorChoice ( array (
					'choices' => array (
							$ps_customer_id ) ) );

			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control',
					'required' => true ) );

			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
		} else {

			if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' )) {

				$ps_customer_id = myUser::getPscustomerID ();

				$ps_customer = Doctrine::getTable ( 'PsCustomer' )->findOneById ( $ps_customer_id );

				$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormChoice ( array (
						'choices' => array (
								$ps_customer->getId () => $ps_customer->getSchoolCode () . '-' . $ps_customer->getSchoolName () ) ) );

				$this->validatorSchema ['ps_customer_id'] = new sfValidatorChoice ( array (
						'choices' => array (
								$ps_customer_id ) ) );

				$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
						'class' => 'form-control',
						'required' => true ) );

				$this->setDefault ( 'ps_customer_id', $ps_customer_id );
			} else {

				$ps_customer_id = sfContext::getInstance ()->getRequest ()
					->getParameter ( 'ps_customer_id' );

				$ps_workplace_id = sfContext::getInstance ()->getRequest ()
					->getParameter ( 'ps_workplace_id' );

				$ps_class_id = sfContext::getInstance ()->getRequest ()
					->getParameter ( 'ps_class_id' );

				// Lay nhung truong hoc dang hoat dong
				$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::ACTIVE, null ),
						'add_empty' => _ ( '-Select customer-' ) ) );

				$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
						'required' => true,
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::ACTIVE, null ),
						'column' => 'id' ) );

				$this->setDefault ( 'ps_customer_id', $ps_customer_id );

				$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );

				$this->setDefault ( 'ps_class_id', $ps_class_id );

				$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
			}
		}

		$school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
			->fetchOne ()
			->getId ();

		if ($ps_customer_id > 0) {

			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => true,
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'column' => 'id' ) );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorPass ( array (
					'required' => true ) );
		}

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_school_year_id' => $school_year_id );

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
					'query' => Doctrine::getTable ( 'Student' )->setSqlListStudentsByClassId ( $ps_class_id ),
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
		/*
		 * upload file $upload_max_size = 5000; // KB $upload_max_size_byte = $upload_max_size * 1024; // bytes $this->widgetSchema ['file'] = new sfWidgetFormInputFile (); $this->validatorSchema ['file'] = new myValidatorFile ( array ( 'required' => true, 'max_size' => $upload_max_size_byte ), array ( 'max_size' => sfContext::getInstance ()->getI18n ()->__ ( 'The file is too large. Allowed maximum size is %value%KB', array ( '%value%' => $upload_max_size ) ) ) );
		 */
		$this->widgetSchema ['url_file'] = new sfWidgetFormInputText ();
		$this->widgetSchema ['url_file']->setAttributes ( array (
				'class' => 'input_text form-control' ) );

		$this->validatorSchema ['url_file'] = new sfValidatorString ( array (
				'required' => true ) );

		$this->widgetSchema ['is_public'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
				'class' => 'radiobox' ) );

		$this->setDefault ( 'title', 'Phiếu nhận xét, đánh giá học sinh - Học kỳ I' );

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
