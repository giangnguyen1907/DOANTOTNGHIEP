<?php
require_once dirname(__FILE__) . '/../lib/psClassGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/psClassGeneratorHelper.class.php';

/**
 * psClass actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psClass
 * @author quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psClassActions extends autoPsClassActions {

	/**
	 * Xuat file bao cao sy so hoc sinh theo lop
	 */
	public function executeExportTotalStudentByClass(sfWebRequest $request) {

		$ps_school_year_id = $request->getParameter('y_id');
		
		$ps_customer_id = $request->getParameter('c_id');
		
		if (! myUser::credentialPsCustomers('PS_STUDENT_CLASS_FILTER_SCHOOL')) {
			
			$ps_customer_id = myUser::getPscustomerID();
		}
		
		$workplace_id = $request->getParameter('w_id');
		
		if ($workplace_id <= 0) {
			$this->getUser()->setFlash('error', $this->getContext()
				->getI18N()
				->__('Workplace filter was empty'));
			$this->redirect('@ps_class');
			// $this->forward404Unless(true, sprintf('Object does not exist .'));
		}
		
		// Check quyen cua lop
		$workplaces = Doctrine::getTable('PsWorkplaces')->getColumnWorkPlaceById($workplace_id,'ps_customer_id');
		if (! myUser::checkAccessObject($workplaces, 'PS_STUDENT_CLASS_FILTER_SCHOOL')) {
			$this->forward404Unless(false, sprintf('Object does not exist .'));
		}
		
		$object_id = $request->getParameter('og_id');
		
		$this->exportReportTotalStudent($ps_school_year_id, $ps_customer_id, $workplace_id, $object_id);
		$this->redirect('@ps_class');
	
	}

	/**
	 * Lay nhom lop hoc boi params: truong, co so dao tao, nam hoc
	 */
	public function executeGroupClassByParams(sfWebRequest $request) {

		if ($request->isXmlHttpRequest()) {
			
			$c_id = $request->getParameter('c_id');
			$y_id = $request->getParameter('y_id');
			$class_from_id = $request->getParameter('class_from_id');
			$status = $request->getParameter('status') ? $request->getParameter('status') : PreSchool::ACTIVE;
			
			return $this->renderPartial('psClass/option_select_group_class', array(
					'customer_id' => $c_id,
					'status' => $status,
					'schoolyear_id' => $y_id,
					'class_id' => $class_from_id
			));
		} else {
			exit(0);
		}
	
	}

	/**
	 * Lay Truong theo Customer Id
	 */
	public function executeClassCustomer(sfWebRequest $request) {

		if ($request->isXmlHttpRequest()) {
			$c_id = $request->getParameter('c_id');
			if ($c_id != '')
				$this->ps_class = Doctrine::getTable('MyClass')->getClassByPsCustomer($c_id);
			
			else
				$this->ps_class = array();
			
			return $this->renderPartial('psClass/ps_class_by_customer', array(
					'ps_class' => $this->ps_class
			));
		} else {
			exit(0);
		}
	
	}

	/**
	 * Lay lop hoc boi params: truong, co so dao tao, nam hoc
	 */
	public function executeClassByParams(sfWebRequest $request) {

		if ($request->isXmlHttpRequest()) {
			
			$c_id = $request->getParameter('c_id');
			$w_id = $request->getParameter('w_id');
			$y_id = $request->getParameter('y_id');
			$notclass = $request->getParameter('not_class');
			
			$class_from_id = $request->getParameter('class_from_id');
			
			$this->ps_class = array();
			
			if (myUser::credentialPsCustomers('PS_STUDENT_ATTENDANCE_FILTER_SCHOOL') || ! myUser::credentialPsCustomers('PS_STUDENT_ATTENDANCE_TEACHER')) {
				$this->ps_class = Doctrine::getTable ( 'MyClass' )->getClassByParams ( array (
						'ps_customer_id' => $c_id,
						'ps_workplace_id' => $w_id,
						'ps_school_year_id' => $y_id,
						'is_activated' => PreSchool::ACTIVE,
						'class_from_id' => $class_from_id ) );
			} else { // hàm cũ: getClassIdByUserId()
				$this->ps_class = Doctrine::getTable ( 'MyClass' )->getClassIdByUserIdWorkplace ( myUser::getUserId (), $w_id )
					->execute ();
			}
			if ($notclass == 'not_in_class') {
				return $this->renderPartial ( 'psClass/option_select_not_class', array (
						'option_select' => $this->ps_class ) );
			} else {
				return $this->renderPartial ( 'psClass/option_select_class', array (
						'option_select' => $this->ps_class ) );
			}
		} else {
			exit ( 0 );
		}
	}
	
	/**
	 * Lay lop hoc boi params: truong, co so dao tao, nam hoc
	 */
	public function executeClassByParams2(sfWebRequest $request) {
		
		if ($request->isXmlHttpRequest()) {
			
			$c_id = $request->getParameter('c_id');
			$w_id = $request->getParameter('w_id');
			$y_id = $request->getParameter('y_id');
			
			$class_from_id = $request->getParameter('class_from_id');
			
			$this->ps_class = array();
			
			if (myUser::credentialPsCustomers('PS_STUDENT_ATTENDANCE_FILTER_SCHOOL') || ! myUser::credentialPsCustomers('PS_STUDENT_ATTENDANCE_TEACHER')) {
				$this->ps_class = Doctrine::getTable ( 'MyClass' )->getClassByParams ( array (
						'ps_customer_id' => $c_id,
						'ps_workplace_id' => $w_id,
						'ps_school_year_id' => $y_id,
						'is_activated' => PreSchool::ACTIVE,
						'class_from_id' => $class_from_id ) );
			} else { // hàm cũ: getClassIdByUserId()
				$this->ps_class = Doctrine::getTable ( 'MyClass' )->getClassIdByUserIdWorkplace ( myUser::getUserId (), $w_id )
				->execute ();
			}
			return $this->renderPartial ( 'psClass/option_select_class2', array (
					'option_select' => $this->ps_class ) );
		} else {
			exit ( 0 );
		}
	}

	/**
	 * Lay lop hoc boi params: truong, co so dao tao, nam hoc, nhom tre
	 */
	public function executeClassByObjectGroups(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$c_id = $request->getParameter ( 'c_id' );
			$w_id = $request->getParameter ( 'w_id' );
			$y_id = $request->getParameter ( 'y_id' );
			$o_id = $request->getParameter ( 'o_id' );

			$this->ps_class = array ();

			$c_id = myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ) ? $c_id : myUser::getPscustomerID ();

			$this->ps_class = Doctrine::getTable ( 'MyClass' )->getClassByParams ( array (
					'ps_customer_id' => $c_id,
					'ps_workplace_id' => $w_id,
					'ps_school_year_id' => $y_id,
					'ps_obj_group_id' => $o_id,
					'is_activated' => PreSchool::ACTIVE ) );

			return $this->renderPartial ( 'psClass/option_select_class', array (
					'option_select' => $this->ps_class ) );
		} else {
			exit ( 0 );
		}
	}

	//
	public function executeClassSchoolYear(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$sid = $request->getParameter ( 'sid' );

			if ($sid != '')
				$this->ps_class = Doctrine::getTable ( 'MyClass' )->getMyClasss ( $sid );

			else
				$this->ps_class = array ();

			return $this->renderPartial ( 'psClass/ps_class_school_year', array (
					'ps_class' => $this->ps_class ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeIndex(sfWebRequest $request) {

		$my_class_filters = $this->getFilters (); // $request->getParameter('my_class_filters');
		$school_year_id = '';
		$school_year_id = $my_class_filters ['school_year_id'];
		
		if ($school_year_id == null) { // get $school_year_id is_default
			
			$ps_school_year_default = sfContext::getInstance ()->getUser ()
				->getAttribute ( 'ps_school_year_default' );
			$my_class_filters ['school_year_id'] = $ps_school_year_default->id;
		}

		$this->setFilters ( $my_class_filters );
		$this->my_class_filters = $my_class_filters;
		parent::executeIndex ( $request );
	}

	public function executeNew(sfWebRequest $request) {
		
		$this->form = $this->configuration->getForm ();

		$ps_customer_id = '';

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_CLASS_FILTER_SCHOOL' )) {

			$ps_customer_id = $request->getParameter ( 'customer_id' );

		} else {

			$ps_customer_id = myUser::getPscustomerID ();

		}

		$this->my_class = $this->form->getObject ();

		$this->my_class->setPsCustomerId ( $ps_customer_id );

		$this->form = $this->configuration->getForm ( $this->my_class );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->my_class = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->my_class, 'PS_STUDENT_CLASS_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->my_class );
	}

	public function executeCreate(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->my_class = $this->form->getObject ();

		$formValues = $request->getParameter ( $this->form->getName () );

		$this->my_class->setPsCustomerId ( $formValues ['ps_customer_id'] );

		$this->my_class->setPsClassRoomId ( $formValues ['ps_class_room_id'] );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->my_class, 'PS_STUDENT_CLASS_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->my_class );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'new' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$ps_my_class = $this->getRoute ()->getObject ();

		// Kiem tra quyen
		$this->forward404Unless ( myUser::checkAccessObject ( $ps_my_class, 'PS_STUDENT_CLASS_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $this->getRoute ()
			->getObject ()
			->getId () ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );
		
		try {
			// Giao vien
			//$number_teacher_class = $ps_my_class->getNumberTeacher ();
			
			// Hoc sinh - studentClass
			$number_student_class = $ps_my_class->getNumberStudent();
			
			if ($number_student_class <= 0) {
			
				if ($this->getRoute ()->getObject ()->delete ()) {			
					// Xóa dịch vu cua lop
					
					// Xoa phan cong giao vien trong lop
			
					$this->getUser ()->setFlash ( 'notice', 'The item was deleted successfully.' );
						
				} else {
					$this->getUser ()->setFlash ( 'error', 'The item was deleted failed');
				}
			} else {				
				$this->getUser ()->setFlash ( 'error', 'This class has students. You cannot delete.');
			}
		} catch (Exception $e) {
			$this->getUser ()->setFlash ( 'error', 'The item was deleted failed');
		}

		$this->redirect ( '@ps_class' );
	}

	// Phan giao vien vao lop hoc
	public function executeTeachersClass(sfWebRequest $request) {

		/*
		 * $this->my_class = $this->getRoute()->getObject();
		 * echo $this->my_class->getId();
		 * echo 'my_class_id:'.$my_class_id = $request->getParameter('my_class_id');
		 * $teachers_id = $request->getParameter('teachers_id');
		 * print_r($teachers_id);
		 * $ps_teacher_class = $request->getParameter('teacher_class');
		 * print_r($ps_teacher_class);
		 * $ps_teacher_class_start = $request->getParameter('ps_teacher_class_start');
		 * print_r($ps_teacher_class_start);
		 * die('XXXXXXXX');
		 */
	}

	public function executeMove(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id    = null;
		$ps_workplace_id   = null;

		//$ps_school_year_id = $this->getContext()->getUser()->getAttribute('ps_school_year_default');		
		$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );		
		$ps_school_year_id  = $schoolYearsDefault->getId ();

		$class_id = $request->getParameter ( 'class_id' );

		$this->list_student_class_to = $this->filter_list_student = $this->filter_list_student2 = array ();

		$tracked_at_formFilter = date ( 'd-m-Y' );

		$class_to_id = $request->getParameter ( 'class_to_id' );

		if ($class_to_id > 0) {
			//$this->list_student_class_to = Doctrine::getTable ( 'StudentClass' )->getStudentsByClassId ( $class_to_id, $tracked_at_formFilter );
			$this->list_student_class_to = Doctrine::getTable ( 'StudentClass' )->getListStudentCurrentOfClass ( $class_to_id);
		}

		$statistic_class_id = $request->getParameter ( 'statistic_class_id' );

		if ($statistic_class_id > 0) {
			$this->list_student_statistic_class = Doctrine::getTable ( 'StudentClass' )->getListStudentCurrentOfClass ( $statistic_class_id);
		}
		
		if ($request->isMethod ( 'post' )) {

			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'student_filter' );

			$class_id = $value_student_filter ['class_id'];
			
			if ($value_student_filter ['school_year_id'] > 0)
				$ps_school_year_id = $value_student_filter ['school_year_id'];
			
			$ps_customer_id = $value_student_filter ['ps_customer_id'];
			
			if ($value_student_filter ['ps_workplace_id'] > 0)
				$ps_workplace_id = $value_student_filter ['ps_workplace_id'];
				
		}else{
			$ps_customer_id = myUser::getPscustomerID ();
			$member_id = myUser::getUser ()->getMemberId ();
			$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		if ($class_id > 0) {

			$ps_class = Doctrine::getTable ( 'MyClass' )->findOneBy ( 'id', $class_id );

			$ps_workplace_id = $ps_class->getPsWorkplaceId ();

			$ps_customer_id = $ps_class->getPsCustomerId ();

			$ps_school_year_id = $ps_class->getSchoolYearId ();
			
			// Lay hoc sinh hien tai cua lop
			$this->filter_list_student = Doctrine::getTable ( 'StudentClass' )->getListStudentCurrentOfClass ( $class_id);
			
		} else {
			
			$this->filter_list_student = Doctrine::getTable ( 'Student' )->getAllStudentNotInStudentClassOfCustomer ( $ps_customer_id, $ps_workplace_id );
		}

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-All school-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => false ) ) );
		} else {
			$ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger () );
		}

		if ($ps_customer_id > 0) {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => true ) ) );

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id,
							'is_activated' => PreSchool::ACTIVE,
							'ps_school_year_id' => $ps_school_year_id ) ),
					'add_empty' => _ ( 'Not in class' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => false ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => true ) ) );

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => false ) ) );
		}

		if ($class_id > 0) {
			$this->formFilter->setWidget ( 'class_to_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id,
							'ps_school_year_id' => $ps_school_year_id,
							'class_from_id' => $class_id,
							'is_activated' => PreSchool::ACTIVE ) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:170px;",
					'data-placeholder' => _ ( '-Select class-' ) ) ) );
			$this->formFilter->setWidget ( 'statistic_class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id,
							'ps_school_year_id' => $ps_school_year_id,
							'class_from_id' => $class_id,
							'is_activated' => PreSchool::ACTIVE ) ),
					'add_empty' => _ ( '-Select statistic class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:170px;",
					'data-placeholder' => _ ( '-Select statistic class-' ) ) ) );
		} else {
			$this->formFilter->setWidget ( 'class_to_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id,
							'is_activated' => PreSchool::ACTIVE,
							'ps_school_year_id' => $ps_school_year_id ) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:170px;",
					'data-placeholder' => _ ( '-Select class-' ) ) ) );
			$this->formFilter->setWidget ( 'statistic_class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id,
							'is_activated' => PreSchool::ACTIVE,
							'ps_school_year_id' => $ps_school_year_id ) ),
					'add_empty' => _ ( '-Select statistic class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:170px;",
					'data-placeholder' => _ ( '-Select statistic class-' ) ) ) );
		}
		
		$this->formFilter->setValidator ( 'class_to_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'MyClass',
				'required' => false ) ) );
		$this->formFilter->setValidator ( 'statistic_class_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'MyClass',
				'required' => false ) ) );
		$this->formFilter->setWidget ( 'school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears () ), array (
				'class' => 'select2',
				'style' => "min-width:120px;width:100%;",
				'data-placeholder' => _ ( '-Select school year-' ) ) ) );

		$this->formFilter->setValidator ( 'school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'required' => true ) ) );

		$this->formFilter->setWidget ( 'tracked_at', new psWidgetFormInputDate () );

		$this->formFilter->setValidator ( 'tracked_at', new sfValidatorDate ( array (
				'required' => true ) ) );

		if ($request->isMethod ( 'post' )) {
			$student_filer = $request->getParameter ( 'student_filter' );
			$student_filer ['ps_workplace_id'] = $ps_workplace_id;
			$this->formFilter->bind ( $student_filer, $request->getFiles ( 'student_filter' ) );
		}

		$this->formFilter->setDefault ( 'class_id', $class_id );
		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		$this->formFilter->setDefault ( 'class_to_id', $class_to_id );
		$this->formFilter->setDefault ( 'statistic_class_id', $statistic_class_id );
		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
		$this->formFilter->setDefault ( 'school_year_id', $ps_school_year_id );

		$this->formFilter->getWidgetSchema () ->setNameFormat ( 'student_filter[%s]' );

		$this->class_id = $class_id;
		$this->class_to_id = $class_to_id;
		$this->statistic_class_id = $statistic_class_id;
		
		$this->form = new sfForm();
		
		$this->ps_customer_id = $ps_customer_id; // lay id truong theo lop
		
		$this->form->setWidget('ps_customer_id', new sfWidgetFormInputHidden());
		
		$this->form->setValidator('ps_customer_id', new sfValidatorInteger(array(
				'required' => true
		)));
		$this->form->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )->getId();
		
		$this->form->setWidget('ps_school_year_id', new sfWidgetFormDoctrineChoice(array(
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable('PsSchoolYear')->setSqlPsSchoolYears(),
				'add_empty' => false
		), array(
				'class' => 'form-control',
				'style' => "width:100%;",
				'data-placeholder' => _('-Select school year-'),
				'required' => true
		)));
		
		$this->form->setValidator('ps_school_year_id', new sfValidatorDoctrineChoice(array(
				'model' => 'PsSchoolYear',
				'column' => 'id',
				'required' => true
		)));
		
		$choices = Doctrine::getTable ( 'MyClass' )->getChoisGroupMyClassByCustomerAndYear ( $this->ps_customer_id,$this->ps_school_year_id, null, PreSchool::ACTIVE );
		
		$this->form->setWidget('myclass_id', new sfWidgetFormSelect ( array (
				'choices' => array (
						'' => '-Select class-' ) + $choices ),
				array(
						'class' => "form-control",
						'required' => true
				) ) );
		
		$this->form->setValidator('myclass_id', new sfValidatorInteger ( array ('required' => true ) ) );

		$this->form->setWidget('statistic_class_id', new sfWidgetFormSelect ( array (
				'choices' => array (
						'' => '-Select class-' ) + $choices ),
				array(
						'class' => "form-control",
						'required' => true
				) ) );
		
		$this->form->setValidator('statistic_class_id', new sfValidatorInteger ( array ('required' => true ) ) );
		
		$this->form->setWidget('myclass_mode', new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
						'class' => 'radiobox' ) ) );
		
		$this->form->setDefault ( 'myclass_mode', PreSchool::NOT_ACTIVE );
		
		$this->form->setValidator('myclass_mode', new sfValidatorInteger ( array (
				'required' => false ) ) );
		
		$this->form->setWidget ( 'start_at', new psWidgetFormFilterInputDate ( array (), array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => _ ( 'Start at' ),
				'data-original-title' => $this->getContext ()->getI18N ()->__ ( 'Start at' ),
				'rel' => 'tooltip' ) ) );
		
		$this->form->setValidator ( 'start_at', new sfValidatorDate ( array (
				'required' => true ) ) );
		
		$this->form->setWidget ( 'stop_at', new psWidgetFormFilterInputDate ( array (), array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => _ ( 'Stop at' ),
				'data-original-title' => $this->getContext ()->getI18N ()->__ ( 'Stop at' ),
				'rel' => 'tooltip' ) ) );
		
		$this->form->setValidator ( 'stop_at', new sfValidatorDate ( array (
				'required' => false ) ) );
		
		$this->form->setDefault ( 'is_activated', PreSchool::ACTIVE );
		$this->form->setWidget ('is_activated', new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
						'class' => 'radiobox' ) ) );
		
		$this->form->setValidator ('is_activated', new sfValidatorInteger ( array (
				'required' => false ) ) );
		
		$this->form->setWidget ('start_date_at', new psWidgetFormInputCheckbox ( array (), array (
				'class' => 'checkbox' ) ) );
		
		$this->form->setValidator ('start_date_at', new sfValidatorString ( array (
				'required' => false ) ) );
		
		$this->form->setWidget ('type', new sfWidgetFormChoice ( array (
				'choices' => PreSchool::loadStatusStudentClass () ), array (
						'class' => 'form-control',
						'style' => "min-width:200px;",
						'data-placeholder' => _ ( '-Select status studying-' ) ) ) );
		
		$this->form->setValidator ('type', new sfValidatorString ( array (
				'required' => false ) ) );
		
		$this->form->getWidgetSchema ()->setNameFormat ( 'move_class[%s]' );
		
	}
	
	// Luu Chuyen lop hang loat
	public function executeSaveStudentsMove(sfWebRequest $request) {
		
		$class_from_id = $request->getParameter ( 'class_from_id' );
		
		$move_class   = $request->getParameter ( 'move_class' );
		
		$class_to_id  = $move_class['myclass_id']; // Lop chuyen den

		$statistic_class_id  = $move_class['statistic_class_id']; // lớp báo cáo

		$ps_school_year_id  = $move_class['ps_school_year_id']; // Nam hoc
		
		// Validate truong hoc
		$ps_customer_id = isset($move_class['ps_customer_id']) ? $move_class['ps_customer_id'] : 0;
		
		// Kiem tra xem du lieu co bị trong hay khong
		if($class_to_id > 0 && $ps_customer_id > 0){
			
			// Kiem tra xem lop nay co thuoc truong hay khong
			$check_class = Doctrine::getTable('MyClass')->setSqlMyClassByCustomer($ps_customer_id,$class_to_id,$ps_school_year_id)->fetchOne();
		
			if (!$check_class) {
				
				$this->getUser () ->setFlash ( 'error', 'Value invalid' );
				
				$this->redirect ( '@ps_class_move?class_id=' . $class_from_id . '&class_to_id=' . $class_to_id );
				
			} else {
				
				$ps_customer_check = Doctrine::getTable('PsCustomer')->getColumnById($ps_customer_id, 'id');
				
				if (!$ps_customer_check || !myUser::credentialPsCustomers(array('PS_STUDENT_CLASS_FILTER_SCHOOL','PS_STUDENT_CLASS_REGISTER_STUDENT'))) {
					$this->forward404 ('The data you asked for is secure and you do not have proper	credentials.');
				}
			}
			
		}else{
			
			$this->getUser () ->setFlash ( 'error', 'Value invalid' );
			
			$this->redirect ( '@ps_class_move?class_id=' . $class_from_id . '&class_to_id=' );
			
		}
		
		if($class_from_id <= 0){
			$class_from_id = null;
		}
		
		$array_student = Doctrine::getTable('Student')->getStudentInCustomerId($ps_customer_id, $class_from_id);
		$array_id = array();
		
		foreach ($array_student as $_student){
			array_push($array_id,$_student->getId());
		}
		
		// mang id hoc sinh
		$student_ids  = $request->getParameter ( 'student_ids' );
		
		if (count($student_ids) <= 0) {
			$this->getUser () ->setFlash ( 'error', 'You need to select students to perform.' );			
			$this->redirect ( '@ps_class_move?class_id=' . $class_from_id . '&class_to_id=' . $class_to_id );
		}
		
		$myclass_mode = $move_class['myclass_mode']; // Học thứ 7 hay không
		
		$is_activated = $move_class['is_activated']; // Là lớp hiện tại không
		
		$type = $move_class['type']; // Trạng thái học
		
		$start_at = $move_class['start_at']; // Ngày bắt đầu
		
		if($start_at == '') {
			$start_at = date('d-m-Y');
		}
		
		$stop_at = $move_class['stop_at']; // Ngày kết thúc
		
		if($stop_at !=''){
			
			$stop_at = date('Y-m-d', strtotime($stop_at));
			
			// Nếu ngày bắt đầu lớn hơn ngày kết thúc
			if(strtotime($start_at) > strtotime($stop_at)) {
				$this->getUser () ->setFlash ( 'error', 'Stop at invalid' );
				$this->redirect ( '@ps_class_move?class_id=' . $class_from_id . '&class_to_id=' . $class_to_id );				
			}
			
		} else {
			$stop_at = null;
		}
		
		$user_id = myUser::getUserId();
		
		$stop_at_old = date ( 'Y-m-d', strtotime ( '-1 days', strtotime ( $start_at ) ) );		
	
		$conn = Doctrine_Manager::connection ();
		
		try {
			
			$conn->beginTransaction ();
			
			$update_class_id = null;
			$dung = 0;
			$array_student_id = array();
			// Kiem tra de chi lay hoc sinh cua truong $ps_customer_id
			foreach ( $student_ids as $student_id ) {
				
				// Neu hoc sinh nam trong Truong hoac Lop
				if($student_id > 0 && in_array($student_id, $array_id)) {
					
					array_push($array_student_id,$student_id);
					
					$dung ++;
					// Kiem tra thoi diem hien tai hoc sinh dang nam trong lop hay khong
					$student_class_from = Doctrine::getTable ( 'StudentClass' )->getObjByStudentAndClass ( $student_id, $class_from_id, date('Y-m-d'))->fetchOne ();
					
					if ($student_class_from) { // Nếu có dữ liệu
						
						$student_class_from->setStopAt ( $stop_at_old );
						
						if($is_activated == PreSchool::ACTIVE){
							$student_class_from->setIsActivated ( PreSchool::NOT_ACTIVE );
						}
						
						$student_class_from->setUserUpdatedId ( $user_id );
						
						$student_class_from->save ();						
					}
					
					if($is_activated == PreSchool::ACTIVE){
						// lay tat ca cac lop ma hoc sinh nay co mat va trang thai la lop hien tai
						$student_class_active = Doctrine::getTable ( 'StudentClass' )->getObjByStudentAndClass ( $student_id )->execute ();
						foreach ($student_class_active as $student_active){
							$student_active -> setIsActivated ( PreSchool::NOT_ACTIVE );
							$student_active -> save();
						}
						$update_class_id = $class_to_id;
					}

					if($statistic_class_id > 0){
						$class_to = $statistic_class_id;
					}else{
						$class_to = $class_to_id;
					}
					
					$student_class_to = new StudentClass ();
					$student_class_to->setStartAt ( date ( 'Y-m-d',strtotime($start_at) ) );
					$student_class_to->setStopAt ( $stop_at );
					$student_class_to->setStudentId ( $student_id );
					$student_class_to->setFromMyclassId ( $class_from_id );
					$student_class_to->setMyclassId ( $class_to_id );
					$student_class_to->setFormStatisticMyclassId ( $class_from_id );
					$student_class_to->setStatisticMyclassId ( $class_to );
					$student_class_to->setType ( $type );
					$student_class_to->setMyclassMode ( $myclass_mode );
					$student_class_to->setIsActivated ( $is_activated );
					$student_class_to->setUserCreatedId ( $user_id );
					$student_class_to->setUserUpdatedId ( $user_id );
					
					$student_class_to->save ();
				}
			}
			
			if(count($array_student_id) > 0){
				
				// Cap nhat lop hien tai vao bang hoc sinh
				$psStudents = Doctrine::getTable('Student')->getStudentInfoByArray($array_student_id);
				
				foreach ($psStudents as $psStudent){
					$psStudent -> setStatisticClassId($class_to);
					if($update_class_id > 0){
						$psStudent -> setCurrentClassId($update_class_id);
					}
					$psStudent -> save();
				}
				$relative_id = array();
				if($type == PreSchool::SC_STATUS_STOP_STUDYING){
					
					foreach ($array_student_id as $student_id){
						
						$check_relative_exist = Doctrine::getTable ( 'RelativeStudent' )->checkRelativeHaveManyStudentNotDelete ( $student_id );
						// Check va day id nguoi than cua hoc sinh vao mang
						if ($check_relative_exist == 1) {
							$all_relatives = Doctrine::getTable ( 'RelativeStudent' )->findByStudentId ( $student_id, $ps_customer_id )
							->toArray ();
							
							$relative_id = array_merge ( $relative_id, array_column ( $all_relatives, 'relative_id' ) );
							
							// End check va day id nguoi than cua hoc sinh vao mang
						}
					}
					
					$userId = myUser::getUserId();
					$date = date ( 'YmdHis' );
					
					if (count ( $relative_id ) > 0) {
						// Khoa tai khoan phu huynh
						Doctrine_Query::create ()->from ( 'SfGuardUser' )
						->update ()
						->set ( 'is_active', PreSchool::CUSTOMER_LOCK )
						->set ( 'notification_token', 'null' )
						->set ( 'user_updated_id', $userId )
						->set ( 'updated_at', $date )
						->andWhereIn ( 'member_id', $relative_id )
						->addWhere ( 'user_type=?', PreSchool::USER_TYPE_RELATIVE )
						->execute ();
						// End khoa tai khoan phu huynh
					}
				}
				
				/*
				// Khoa tai khoan phu huynh khi trang thai hoc la Thoi Hoc
				$array_relative_id = array();
				
				// Neu trang thai la Thoi Hoc thi khoa tai khoan phu huynh lai
				if($type == PreSchool::SC_STATUS_STOP_STUDYING){
					
					foreach ($array_student_id as $student_id){
						// Lay ra danh sach nguoi than cua hoc sinh ma co tai khoan
						$all_relatives = Doctrine::getTable ( 'RelativeStudent' )->getRelativeByStudentId ( $student_id, $ps_customer_id );
						
						foreach ($all_relatives as $relatives){
							array_push($array_relative_id, $relatives -> getRelativeId());
						}
						
						foreach ($array_relative_id as $relative_id){
							
							// Kiem tra nguoi than day co quan he voi hoc sinh nao dang hoat dong nua hay khong
							$check_relatives = Doctrine::getTable ( 'RelativeStudent' )->getRelativeStudentsByRelativeId($relative_id,$student_id);
							
							// Neu khong co quan he voi hoc sinh nao thi khoa tai khoan lai
							if(!$check_relatives){
								
								// Khoa tai khoan phu huynh
								Doctrine_Query::create ()->from ( 'SfGuardUser' )
								->update ()
								->set ( 'is_active', PreSchool::CUSTOMER_LOCK )
								->set ( 'notification_token', 'null' )
								->set ( 'user_updated_id', $userId )
								->set ( 'updated_at', $date )
								->andWhereIn ( 'member_id', $relative_id )
								->addWhere ( 'user_type=?', PreSchool::USER_TYPE_RELATIVE )
								->execute ();
								
							}
						}
					}
				}*/
			}
			
			$conn->commit ();
			
			if ($dung > 0) {
				$this->getUser () ->setFlash ( 'notice', 'Students are transferred class successfully.' );
			} else {
				$this->getUser () ->setFlash ( 'error', 'Students are transferred class failed.' );
			}
			
			$this->redirect ( '@ps_class_move?class_id=' . $class_from_id . '&class_to_id=' . $class_to_id );
		
		} catch ( Exception $e ) {
			throw new Exception ( $e->getMessage () );
			$this->getUser () ->setFlash ( 'error', 'Students are transferred class failed.' );
			$conn->rollback ();
		}
	}
	
	// Phan lop cho hoc sinh moi nhap truong
	public function executeAssignStudents(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-All school-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true ) ) );
		} else {
			$ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger () );
		}

		$this->students_not_exits_class = array ();

		// Danh sach hoc sinh moi nhap hoc
		if ($ps_customer_id > 0) {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select basis enrollment-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-Select basis enrollment-' ) ) ) );

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id,
							'is_activated' => PreSchool::ACTIVE,
							'ps_school_year_id' => $ps_school_year_id ) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => true ) ) );

			$this->students_not_exits_class = Doctrine::getTable ( 'Student' )->getPsStudentsNotExitsClass ( $ps_customer_id );
		} else {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false ) ) );

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => true ) ) );
		}

		$this->formFilter->setWidget ( 'school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears () ), array (
				'class' => 'select2',
				'style' => "min-width:120px;width:100%;",
				'data-placeholder' => _ ( '-Select school year-' ) ) ) );

		$this->formFilter->setValidator ( 'school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'required' => true ) ) );

		$this->list_student_class_to = array ();

		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		$this->formFilter->setDefault ( 'school_year_id', $school_year_id );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'student_filter[%s]' );

		// Form chon lop de phan cho hoc sinh
		$this->formAssignStudents = new AssignStudentsClassForm ();
	}

	// Lay danh sach hoc sinh chua duoc phan lop
	public function executeStudentNotExitsClass(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$ps_customer_id = $request->getParameter ( 'cus_id' );

			if ($ps_customer_id <= 0) {
				return $this->renderPartial ( 'psClass/list_for_assign_students', array (
						'students_not_exits_class' => array () ) );
			} else {

				if (! myUser::credentialPsCustomers ( 'PS_STUDENT_CLASS_FILTER_SCHOOL' )) {
					$ps_customer_id = myUser::getPscustomerID ();
				}

				$ps_workplace_id = $request->getParameter ( 'w_id' );

				$list_student = Doctrine::getTable ( 'Student' )->getPsStudentsNotExitsClass ( $ps_customer_id, $ps_workplace_id );

				return $this->renderPartial ( 'psClass/list_for_assign_students', array (
						'students_not_exits_class' => $list_student ) );
			}
		}
	}

	public function executeStudentClass(sfWebRequest $request) {

		$class_id = $request->getParameter ( 'cid' );

		if ($class_id <= 0) {
			exit ( 0 );
		} else {
			$tracked_at_formFilter = date ( 'd-m-Y' );

			$this->list_student = Doctrine::getTable ( 'StudentClass' )->getStudentsByClassId ( $class_id, $tracked_at_formFilter );

			return $this->renderPartial ( 'psClass/table_student', array (

					'list_student_class_to' => $this->list_student ) );
		}
	}

	public function executeStopStudying(sfWebRequest $request) {

		$class_from_id = $request->getParameter ( 'class_from_id' );
		
		$class_to_id = $class_from_id;
		
		if ($request->getParameter ( 'form_student_start_at' ) == '') {
			$start_at = date ( 'Y-m-d' );
		} else {
			$start_at = date ( 'Y-m-d', strtotime ( $request->getParameter ( 'form_student_start_at' ) ) );
		}
		
		$ps_customer_id = Doctrine::getTable('MyClass')->getMyClassByField($class_from_id,'ps_customer_id')->getPsCustomerId();
		
		if (!myUser::credentialPsCustomers(array('PS_STUDENT_CLASS_FILTER_SCHOOL','PS_STUDENT_CLASS_REGISTER_STUDENT'))) {
			if(myUser::getPscustomerID () != $ps_customer_id){
				$this->forward404 ('The data you asked for is secure and you do not have proper	credentials.');
			}
		}
		
		$array_student = Doctrine::getTable('Student')->getStudentInCustomerId($ps_customer_id, $class_from_id);
		$array_id = array();
		foreach ($array_student as $_student){
			array_push($array_id,$_student->getId());
		}
		
		// mang id hoc sinh
		$student_ids  = $request->getParameter ( 'student_ids' );
		
		if (count($student_ids) <= 0) {
			$this->getUser () ->setFlash ( 'error', 'You need to select students to perform.' );
			$this->redirect ( '@ps_class_move?class_id=' . $class_from_id . '&class_to_id=' . $class_to_id );
		}
		
		$user_id = myUser::getUserId();
		
		$stop_at = date ( 'Y-m-d', strtotime ( '-1 days', strtotime ( $start_at ) ) );

		$stop_at_new = '';

		if ($request->getParameter ( 'form_student_stop_at' ) != '') {
			$stop_at_new = date ( 'Y-m-d', strtotime ( $request->getParameter ( 'form_student_stop_at' ) ) );
		}
		
		$is_activated = $request->getParameter ( 'form_student_myclass_mode' ); // la lop hien tai hay khong

		$sai = $dung = 0;

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			foreach ( $student_ids as $student_id ) {

				if($student_id > 0 && in_array($student_id,$array_id)){
				
					// Kiem tra thoi diem hien tai co thuoc lop hay khong
					$student_class_from = Doctrine::getTable ( 'StudentClass' )->getObjByStudentAndClass ( $student_id, $class_from_id, date('Y-m-d') )->fetchOne();
	
					if ($student_class_from) {
						$dung ++;
						// Cập nhật ngày kết thúc
						$student_class_from->setStopAt ( $stop_at );
						
						if($is_activated == PreSchool::ACTIVE){
							$student_class_from->setIsActivated ( PreSchool::NOT_ACTIVE );
						}
						
						$student_class_from->setUserUpdatedId ( $user_id );
						
						$student_class_from->save ();
						
						// Khoi tao moi
						$student_class_to = new StudentClass ();
						$student_class_to->setStartAt ( $start_at );
	
						if ($stop_at_new != '') {
							$student_class_to->setStopAt ( $stop_at_new );
						}
						
						$student_class_to->setStudentId ( $student_id );
						$student_class_to->setFromMyclassId ( $class_from_id );
						$student_class_to->setMyclassId ( $class_from_id );
						$student_class_to->setType ( PreSchool::SC_STATUS_PAUSE );
						$student_class_to->setMyclassMode ( 0 );
						$student_class_to->setIsActivated ( $is_activated );
	
						$student_class_to->setUserCreatedId ( $user_id );
						$student_class_to->setUserUpdatedId ( $user_id );
	
						$student_class_to->save ();
						
					} else {
						$sai ++;
					}
				}
			}
			
			$conn->commit ();
			
		} catch ( Exception $e ) {
			throw new Exception ( $e->getMessage () );
			$this->getUser ()
			->setFlash ( 'error', $this->getContext () ->getI18N () ->__ ( 'Students are not stop study.' ) );
			$conn->rollback ();
		}
		if ($dung > 0 && $sai == 0) {
			$message = $this->getContext ()
				->getI18N ()
				->__ ( 'Students are stop study, %value% successfully.', array (
					'%value%' => $dung ) );
			$this->getUser ()
				->setFlash ( 'notice', $message );
		} elseif ($dung == 0) {
			$message = $this->getContext ()
				->getI18N ()
				->__ ( 'Students are not stop study.' );
			$this->getUser ()
				->setFlash ( 'error', $message );
		} else {
			$message = $this->getContext ()
				->getI18N ()
				->__ ( 'Students success %value1%, error %value2%', array (
					'%value1%' => $dung,
					'%value2%' => $sai ) );
			$this->getUser ()
				->setFlash ( 'warning', $message );
		}
		$this->redirect ( '@ps_class_move?class_id=' . $class_from_id . '&class_to_id=' . $class_to_id );
	}

	public function executeDetail(sfWebRequest $request) {

		if ($this->getRequest ()
			->isXmlHttpRequest ()) {

			$this->my_class_detail = $this->getRoute ()->getObject ();

			//$this->forward404Unless ( myUser::checkAccessObject ( $this->my_class_detail, 'PS_STUDENT_CLASS_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
			
			if (!myUser::checkAccessObject ( $this->my_class_detail, 'PS_STUDENT_CLASS_FILTER_SCHOOL' ))
				$this->setTemplate('detailError404','psCpanel');

			// student
			//$this->ps_students_class = $this->my_class_detail->getPsStudentsInClass ();
			
			$this->ps_students_class  = $this->my_class_detail->getPsStudentsInClassAllStatus ();
			
			//$this->ps_students_active = $this->my_class_detail->getActiveStudentInClass ();
			
			$this->school_code = $this->my_class_detail->getPsCustomer ()->getSchoolCode ();

			$this->type_student_class = PreSchool::loadStatusStudentClass ();
			$this->url_callback = PsEndCode::ps64EndCode ( (sfContext::getInstance ()->getRouting ()
				->getCurrentRouteName () . '?id=' . $this->my_class_detail->getId ()) );

			// teacher
			$this->ps_class_teachers = $this->my_class_detail->getTeachers ();

			// service
			$this->services = Doctrine::getTable ( 'Service' )->getServicesAndClassService ( $this->my_class_detail->getId (), $this->my_class_detail->getPsCustomerId () )->execute ();

			$this->my_class_id = $this->my_class_detail->getId ();
		} else {
			exit ( 0 );
		}
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );

		if ($form->isValid ()) {
			
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {
				
				//lâm cập nhật nếu tích chọn lớp là năm cuối sẽ thay đổi trạng thái học sinh
				$value = $request->getParameter ('my_class');
				
				$is_lastyear = $value['is_lastyear'];

				$class_id = $value['id'];

				if($is_lastyear == '1'){
					
					Doctrine_Query::create()->update('Student')->set(array('type_year'=>1))->addwhere('current_class_id =?', $class_id)->execute();
					
					/*
					$st_student = $array_status = array();
					$st_student = PreSchool::loadCheckStatusStudent();
					$stt = 0;

					foreach($st_student as $key => $st){
						$array_status[$stt++] = $key;
						array_push($array_status);
					}
					
					//lấy ra danh sách học sinh thuộc lớp đang sửa và có trạng thái phù hợp
					$list_student = Doctrine_Query::create ()->from ( 'Student' )
					->addwhere('current_class_id =?', $class_id)
					->whereIn ('status',$array_status)
					->execute ();

					foreach ($list_student as $key => $list) {
						$list->setStatus ('NC');
						$list->setTypeYear (1);
						$list->save();
					}
					*/
				}else{
					Doctrine_Query::create()->update('Student')->set(array('type_year'=>0))->addwhere('current_class_id =?', $class_id)->execute();
				}

				$my_class = $form->save ();
				
			} catch ( Doctrine_Validator_Exception $e ) {

				$errorStack = $form->getObject ()
					->getErrorStack ();

				$message = get_class ( $form->getObject () ) . ' has ' . count ( $errorStack ) . " field" . (count ( $errorStack ) > 1 ? 's' : null) . " with validation errors: ";
				foreach ( $errorStack as $field => $errors ) {
					$message .= "$field (" . implode ( ", ", $errors ) . "), ";
				}
				$message = trim ( $message, ', ' );

				$this->getUser ()
					->setFlash ( 'error', $message );
				return sfView::SUCCESS;
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $my_class ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {

				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				$this->redirect ( '@ps_class_new' );

				if (myUser::credentialPsCustomers ( 'PS_STUDENT_CLASS_FILTER_SCHOOL' )) {
					$this->redirect ( '@ps_class_new?customer_id=' . $my_class->getPsCustomerId () );
				} else {
					$this->redirect ( '@ps_class_new' );
				}
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_class_edit',
						'sf_subject' => $my_class ) );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}

	public function exportReportTotalStudent($school_year_id, $customer_id, $workplace_id = null, $object_id = null) {

		$exportFile = new ExportClassReportsHelper ( $this );

		$file_template_pb = 'baocao_syso_tonghop.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/pschool/template_export/' . $file_template_pb;

		$exportFile->loadTemplate ( $path_template_file );

		// Lay ra thong tin truong
		$my_customer = Doctrine::getTable ( 'PsCustomer' )->getCustomerById ( $customer_id );

		if ($workplace_id) {
			$workplace = Doctrine::getTable ( 'PsWorkplaces' )->getColumnWorkPlaceById ( $workplace_id,'title' );
		}
		// Thong tin truong khi bao cao
		$exportFile->setCustomerInfoExportClass ( $school_year_id, $my_customer, $workplace );

		$exportFile->setDataExportClassFinalReport ( $school_year_id, $customer_id, $workplace_id, $object_id );

		$exportFile->saveAsFile ( "Bao_cao_sy_so" . date ( 'YmdHi' ) . ".xls" );
	}

	protected function updatedStatusClass() {
		
		$ps_customer_id = null;// myUser::getPscustomerID();
		
		// Lay danh sach hoc sinh trang thai lop hien tại > 1
		$studentClassIsUpdated = Doctrine::getTable('StudentClass')->getListStudentClassByCustomerId($ps_customer_id);
		
		$conn = Doctrine_Manager::connection ();
		
		try {
			
			$conn->beginTransaction ();
			
			foreach ($studentClassIsUpdated as $studentClass){
				
				$student_id = $studentClass->getStudentId();
				
				$list_student_class = Doctrine::getTable('StudentClass')->getListClassByStudentId($student_id);
				
				$count_list = count($list_student_class);
				
				$dacapnhat = 0;
					
				foreach ($list_student_class as $key => $student_class){
					
					if($key == 0 && $student_class->getStopAt() == ''){
						$dacapnhat = 1;
					}

					if($dacapnhat == 1){
						if($key > 0){
							//echo 'Khoa:'.$key.'_'. $student_class->getStartAt().'<br>';
							$student_class->setIsActivated(PreSchool::NOT_ACTIVE);
							$student_class->save();
						}
					}else{
						if($key < ($count_list - 1)){
							//echo 'Khoa:'.$key.'_'. $student_class->getStartAt().'<br>';
							$student_class->setIsActivated(PreSchool::NOT_ACTIVE);
							$student_class->save();
						}
					}
				}
			}
			//die;
			$conn->commit ();
			
		} catch ( Exception $e ) {
			throw new Exception ( $e->getMessage () );
			$this->getUser () ->setFlash ( 'error', 'Updated status student class failed.' );
			$conn->rollback ();
		}
		
	}
	
}
