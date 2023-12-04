<?php
use function Respect\Validation\primeNumber;

require_once dirname ( __FILE__ ) . '/../lib/psServiceCoursesGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psServiceCoursesGeneratorHelper.class.php';

/**
 * psServiceCourses actions.
 *
 * @package kidsschool.vn
 * @subpackage psServiceCourses
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psServiceCoursesActions extends autoPsServiceCoursesActions {

	// Ham phan lop cho hoc sinh dang ky dich vu chua phan lop
	public function executeMoveClass(sfWebRequest $request) {

		$service_id = $request->getParameter ( "service_id" );

		$student_id = $request->getParameter ( "student_id" );

		if ($service_id <= 0) {

			$this->getUser ()
				->setFlash ( 'error', 'Object does not exist.' );

			$this->redirect ( '@ps_service_courses' );
		} else {

			$this->service_couse = Doctrine::getTable ( 'PsServiceCourses' )->getPsServiceCoursesByPsServiceId ( $service_id );

			$this->helper = new psServiceCoursesGeneratorHelper ();

			return $this->renderPartial ( 'psServiceCourses/moveClassSuccess', array (
					'service_couse' => $this->service_couse,
					'student_id' => $student_id,
					'service_id' => $service_id,
					'helper' => $this->helper ) );
		}
	}

	public function executeMoveClassSave(sfWebRequest $request) {

		$student_id = $request->getParameter ( "student_id" );

		$student_id_arr = explode ( ",", $student_id );

		$service_id = $request->getParameter ( "service_id" );

		$service_course_id = $request->getParameter ( "course_id" );

		$student_sevices = Doctrine_Query::create ()->from ( 'StudentService' )
			->where ( 'ps_service_course_id IS NULL AND service_id =? AND delete_at IS NULL', $service_id )
			->orderBy ( 'created_at DESC' );

		$student_sevices = $student_sevices->andWhereIn ( 'student_id', $student_id_arr )
			->execute ();

		if (count ( $student_sevices ) > 0) {

			foreach ( $student_sevices as $student_sevice ) {

				// $student_sevice->setStudentId($student_id);

				// $student_sevice->setServiceId($service_id);

				$student_sevice->setPsServiceCourseId ( $service_course_id );

				$student_sevice->setUserCreatedId ( sfContext::getInstance ()->getUser ()
					->getGuardUser ()
					->getId () );

				$student_sevice->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
					->getGuardUser ()
					->getId () );

				$student_sevice->save ();
			}
		}

		$this->filter_list_student = Doctrine::getTable ( 'Student' )->setListStudentForPsServiceCourses ( $service_id )
			->execute ();

		// $this->executeStatistic($request);
		return $this->redirect ( '@ps_service_courses_statistic' );
		// return $this->renderPartial('psServiceCourses/moveClassSuccess', array(
		// 'filter_list_student' => $this->filter_list_student,
		// ));

		// $this->getUser()->setFlash('notice', 'Successfully.');

		// $this->redirect('@ps_service_courses_statistic');
	}

	// public function executeStudentServiceSave(sfWebRequest $request)
	// {
	// $student_id = $request->getParameter ("student_id" );

	// $service_id = $request->getParameter ("service_id" );

	// $service_course_id = $request->getParameter ("course_id" ) ? $request->getParameter ("course_id" ) : null;

	// $student_sevice = Doctrine_Query::create()->from('StudentService')->andWhere('student_id =? AND service_id =? AND ps_service_course_id =? AND delete_at IS NULL', array($student_id, $service_id, $service_course_id))->fetchOne();

	// if(count($student_sevice) >0 ) {

	// $student_sevice->setPsServiceCourseId($service_course_id);

	// $student_sevice->setUserCreatedId(sfContext::getInstance()->getUser()
	// ->getGuardUser()
	// ->getId());

	// $student_sevice->setUserUpdatedId(sfContext::getInstance()->getUser()
	// ->getGuardUser()
	// ->getId());

	// $student_sevice->save();

	// } else{

	// $student_sevice = new StudentService();

	// $student_sevice->setStudentId($student_id);

	// $student_sevice->setServiceId($service_id);

	// $student_sevice->setPsServiceCourseId($service_course_id);

	// $student_sevice->setUserCreatedId(sfContext::getInstance()->getUser()
	// ->getGuardUser()
	// ->getId());

	// $student_sevice->setUserUpdatedId(sfContext::getInstance()->getUser()
	// ->getGuardUser()
	// ->getId());

	// $student_sevice->save();
	// }

	// // $this->getUser()->setFlash('notice', 'Successfully.');

	// $this->redirect('@ps_service_courses_statistic');
	// }
	// Ham thong ke hoc sinh chua dang ky dich vu hoc ngoai khoa
	public function executeStatistic(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$school_year_id = null;

		$ps_workplace_id = null;

		$ps_customer_id = null;

		$ps_service_id = null;

		// $ps_member_id = null;

		// $is_status = null;

		// $date_at_from = null;

		// $date_at_to = null;

		$keywords = null;

		// $course = null;

		$this->filter_list_student = array ();

		$student_service_filter = $request->getParameter ( 'student_service_filter' );

		if ($request->isMethod ( 'POST' )) {

			$value_student_filter = $request->getParameter ( 'student_service_filter' );

			$school_year_id = $value_student_filter ['school_year_id'];
			$this->school_year_id = $school_year_id;

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];
			$this->ps_workplace_id = $ps_workplace_id;

			$ps_service_id = $value_student_filter ['ps_service_id'];
			$this->ps_service_id = $ps_service_id;

			// $ps_member_id = $value_student_filter ['ps_member_id'];
			// $this->ps_member_id = $ps_member_id;

			// $is_status = $value_student_filter ['is_status'];
			// $this->is_status = $is_status;

			// $date_at_from = $value_student_filter ['date_at_from'];
			// $this->date_at_from = $date_at_from;

			// $date_at_to = $value_student_filter ['date_at_to'];
			// $this->date_at_to = $date_at_to;

			$keywords = $value_student_filter ['keywords'];
			$this->keywords = $keywords;

			// $course = $value_student_filter ['course'];
			// $this->course = $course;

			// if ($is_status == '')
			// $is_status = - 1;

			// $this->filter_list_student = Doctrine::getTable('StudentService')->getStudentByPsServiceCourse(3);
			// $this->filter_list_student = Doctrine::getTable('Student')->getListStudentForPsServiceCoursesByStatus($ps_service_id, $course, $is_status);
			if ($ps_service_id > 0)
				$this->filter_list_student = Doctrine::getTable ( 'Student' )->setListStudentForPsServiceCourses ( $ps_service_id, $keywords )
					->execute ();
		}

		if ($ps_service_id > 0) {
			$this->course_list = Doctrine::getTable ( 'PsServiceCourses' )->setPsServiceCoursesByPsService ( 'id, title', $ps_service_id )
				->execute ();
		} else {
			$this->course_list = array ();
		}
		if ($student_service_filter) {

			$this->ps_workplace_id = isset ( $student_service_filter ['ps_workplace_id'] ) ? $student_service_filter ['ps_workplace_id'] : 0;

			$this->school_year_id = isset ( $student_service_filter ['ps_school_year_id'] ) ? $student_service_filter ['ps_school_year_id'] : '';

			$this->ps_service_id = isset ( $student_service_filter ['ps_service_id'] ) ? $student_service_filter ['ps_service_id'] : 0;

			if ($this->ps_workplace_id > 0) {

				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_COURSES_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => true ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => true,
					'data-placeholder' => _ ( '-All school-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true ) ) );
		}

		if ($this->ps_customer_id == '') {
			$this->ps_customer_id = myUser::getPscustomerID ();
			$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$this->ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		if ($this->school_year_id == '') {
			$this->school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		$this->formFilter->setDefault ( 'school_year_id', $this->school_year_id );

		$this->formFilter->setWidget ( 'school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => false ), array (
				'class' => 'select2',
				'style' => "width:100%;min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ),
				'required' => true ) ) );

		$this->formFilter->setValidator ( 'school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'column' => 'id',
				'required' => true ) ) );

		if ($this->ps_customer_id > 0) {

			$school_code = Doctrine::getTable ( 'PsCustomer' )->findOneById ( $this->ps_customer_id );

			$this->school_code = $school_code->getSchoolCode ();

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $this->ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false ) ) );

			$this->formFilter->setWidget ( 'ps_service_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Service',
					'query' => Doctrine::getTable ( 'Service' )->setServicesTypeScheduleByPsCustomer ( 'id, title', $this->ps_customer_id, PreSchool::ACTIVE, $this->ps_workplace_id, $this->school_year_id ),
					'add_empty' => '-Select subjects-' ), array (
					'class' => 'select2',
					'required' => true,
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select subjects-' ) ) ) );
		} else {
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );

			$this->formFilter->setWidget ( 'ps_service_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select subjects-' ) ) ), array (
					'class' => 'select2',
					'required' => true,
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select subjects-' ) ) ) );
		}
		// if ($ps_customer_id > 0) {
		// $school_code = Doctrine::getTable ( 'PsCustomer' )->findOneById ( $ps_customer_id );

		// $this->school_code = $school_code->getSchoolCode ();

		// $this->formFilter->setWidget ( 'ps_service_id', new sfWidgetFormDoctrineChoice ( array (
		// 'model' => 'Service',
		// 'query' => Doctrine::getTable ( 'Service' )->setServicesTypeScheduleByPsCustomer ( 'id, title', $ps_customer_id, PreSchool::ACTIVE, $ps_workplace_id, $school_year_id ),
		// 'add_empty' => '-Select subjects-' ), array (
		// 'class' => 'select2',
		// 'required' => true,
		// 'style' => "min-width:200px;",
		// 'data-placeholder' => sfContext::getInstance ()->getI18n ()->__ ( '-Select subjects-' ) ) ) );

		// $this->formFilter->setWidget('ps_member_id', new sfWidgetFormDoctrineChoice ( array (
		// 'model' => 'PsMember',
		// 'query' => Doctrine::getTable ( 'PsMember' )->setSQLMembers ( $ps_customer_id ),
		// 'add_empty' => '-Select member-'
		// ), array (
		// 'class' => 'select2',
		// 'style' => "min-width:200px;",
		// 'data-placeholder' => sfContext::getInstance ()->getI18n ()->__ ( '-Select member-' )
		// ) ) );
		// } else {

		// $this->formFilter->setWidget ( 'ps_service_id', new sfWidgetFormChoice ( array (
		// 'choices' => array (
		// '' => _ ( '-Select subjects-' ) ) ), array (
		// 'class' => 'select2',
		// 'required' => true,
		// 'style' => "min-width:200px;",
		// 'data-placeholder' => sfContext::getInstance ()->getI18n ()->__ ( '-Select subjects-' ) ) ) );

		// $this->formFilter->setWidget('ps_member_id', new sfWidgetFormChoice ( array (
		// 'choices' => array (
		// '' => _ ( '-Select member-' )
		// )
		// ), array (
		// 'class' => 'select2',
		// 'style' => "min-width:200px;",
		// 'data-placeholder' => sfContext::getInstance ()->getI18n ()->__ ( '-Select member-' )
		// ) ) );
		// }

		$this->formFilter->setWidget ( 'keywords', new sfWidgetFormInputText ( array (), 
		array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) ) );

		$this->formFilter->setValidator ( 'keywords', new sfValidatorString ( array (
				'required' => false ) ) );

		// $this->formFilter->setValidator ('ps_member_id', new sfValidatorDoctrineChoice ( array (
		// 'model' => 'PsMember',
		// 'required' => false
		// ) ) );

		$this->formFilter->setValidator ( 'ps_service_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'Service',
				'required' => true ) ) );

		// if($ps_service_id > 0){
		// $this->formFilter->setWidget('course', new sfWidgetFormDoctrineChoice ( array (
		// 'model' => 'Service',
		// 'query' => Doctrine::getTable ( 'PsServiceCourses' )->setPsServiceCoursesByPsService ( 'id, title', $ps_service_id, PreSchool::ACTIVE ),
		// 'add_empty' => '-Select course-'
		// ), array (
		// 'class' => 'select2',
		// 'style' => "min-width:200px;",
		// 'data-placeholder' => sfContext::getInstance ()->getI18n ()->__ ( '-Select course-' ),
		// 'data-original-title' => _('Select month')
		// ) ) );
		// }else{
		// $this->formFilter->setWidget('course', new sfWidgetFormChoice ( array (
		// 'choices' => array (
		// '' => _ ( '-Select course-' )
		// )
		// ), array (
		// 'class' => 'select2',
		// 'style' => "min-width:200px;",
		// 'data-placeholder' => sfContext::getInstance ()->getI18n ()->__ ( '-Select course-' )
		// ) ) );
		// }

		// $this->formFilter->setValidator ('course', new sfValidatorDoctrineChoice ( array (
		// 'model' => 'PsServiceCourses',
		// 'required' => true
		// ) ) );

		// $this->formFilter->setWidget('is_status', new sfWidgetFormChoice( array (
		// 'choices' => array ('' => '-Select student in course state-' ) + PreSchool::loadPsServiceDefault(),
		// ),
		// array(
		// 'class' => 'select2',
		// 'style' => "min-width:200px;",
		// 'data-placeholder' => _('-Select state-')
		// ) ));

		// $this->formFilter->setValidator ('is_status', new sfValidatorChoice ( array (
		// 'required' => false,
		// 'choices' => array ('' => '-Select student in course state-' ) + PreSchool::loadPsServiceDefault()
		// ) ) );

		// $this->formFilter->setWidget('date_at_from', new psWidgetFormFilterInputDate(array(

		// ),array(
		// 'data-dateformat' => 'dd-mm-yyyy',
		// 'placeholder' => 'dd-mm-yyyy',
		// 'style' => "max-width:200px;",
		// 'data-original-title' => sfContext::getInstance()->getI18n()->__('From date'),
		// )));

		// $this->formFilter->setValidator ('date_at_from', new sfValidatorDate(array(
		// 'required' => false
		// )));

		// $this->formFilter->setWidget('date_at_to', new psWidgetFormFilterInputDate(array(
		// ),array(
		// 'data-dateformat' => 'dd-mm-yyyy',
		// 'placeholder' => 'dd-mm-yyyy',
		// 'style' => "max-width:200px;",
		// 'data-original-title' => sfContext::getInstance()->getI18n()->__('To date'),
		// )
		// ));

		// $this->formFilter->setValidator ('date_at_to', new sfValidatorDate(array(
		// 'required' => false
		// )));
		// $this->formFilter->setDefault ( 'ps_member_id', $ps_member_id );
		// $this->formFilter->setDefault ( 'course', $course );
		// $this->formFilter->setDefault ( 'date_at_to', $date_at_to );
		// $this->formFilter->setDefault ( 'date_at_from', $date_at_from );
		// $this->formFilter->setDefault ( 'is_status', $is_status );

		$this->formFilter->setDefault ( 'school_year_id', $this->school_year_id );
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		$this->formFilter->setDefault ( 'ps_service_id', $this->ps_service_id );
		$this->formFilter->setDefault ( 'keywords', $keywords );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'student_service_filter[%s]' );
	}

	public function executeIndex(sfWebRequest $request) {

		$service_id = $request->getParameter ( 'sid' );

		if ($service_id > 0) {

			$this->service = Doctrine::getTable ( 'Service' )->findOneById ( $service_id );

			// if (!$this->service)

			$this->forward404Unless ( $this->service, sprintf ( 'Object does not exist.' ) );

			$this->forward404Unless ( myUser::checkAccessObject ( $this->service, 'PS_STUDENT_SERVICE_COURSES_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

			$this->setFilters ( array (
					'ps_service_id' => $this->service->getId (),
					'ps_customer_id' => $this->service->getPsCustomerId () ) );
		}
		// sorting
		if ($request->getParameter ( 'sort' ) && $this->isValidSortColumn ( $request->getParameter ( 'sort' ) )) {
			$this->setSort ( array (
					$request->getParameter ( 'sort' ),
					$request->getParameter ( 'sort_type' ) ) );
		}

		// pager
		if ($request->getParameter ( 'page' )) {
			$this->setPage ( $request->getParameter ( 'page' ) );
		}

		$this->pager = $this->getPager ();
		$this->sort = $this->getSort ();
	}

	// Loc môn học theo trường, cơ sở
	public function executeServicePsCustomer(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$psc_id = intval ( $request->getParameter ( "psc_id" ) );

			$psCustomer = Doctrine::getTable ( 'PsCustomer' )->findOneById ( $psc_id );

			if ($psCustomer) {

				// Check quyen loc du lieu
				if (myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_COURSE_COMMENT_FILTER_SCHOOL' ) || ($psc_id == myUser::getPscustomerID ())) {
					$service = Doctrine::getTable ( 'Service' )->setServicesTypeScheduleByPsCustomer ( 'id, title', $psc_id )
						->execute ();
				} else {
					$service = array ();
				}
				return $this->renderPartial ( 'option_service', array (
						'service' => $service ) );
			}
		} else {
			exit ( 0 );
		}
	}

	// Loc môn học theo trường, cơ sở
	public function executeServicePsWorkplace(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$y_id = $request->getParameter ( "y_id" );

			$c_id = $request->getParameter ( "c_id" );

			$w_id = $request->getParameter ( "w_id" );

			$e_sc = $request->getParameter ( "e_sc" );

			$psCustomer = Doctrine::getTable ( 'PsCustomer' )->findOneById ( $c_id );

			if ($psCustomer) {

				// Check quyen loc du lieu
				if (myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_COURSE_COMMENT_FILTER_SCHOOL' ) || ($c_id == myUser::getPscustomerID ())) {
					$service = Doctrine::getTable ( 'Service' )->setServicesTypeScheduleByPsCustomer ( 'id, title', $c_id, PreSchool::ACTIVE, $w_id, $y_id, $e_sc )
						->execute ();
				} else {
					$service = array ();
				}
				return $this->renderPartial ( 'option_service', array (
						'service' => $service ) );
			}
		} else {
			exit ( 0 );
		}
	}

	public function executeServiceCoursesService(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$ps_service_id = intval ( $request->getParameter ( "sid" ) );

			$psService = Doctrine::getTable ( 'Service' )->findOneById ( $ps_service_id );

			if (($psService->getEnableSchedule () == PreSchool::ACTIVE)) {
				// Neu ko co quyen loc mon hoc va danh gia ket qua mon hoc thi kiem tra xac thuc ban ghi co phai cua truong ko
				if (! myUser::credentialPsCustomers ( 'PS_STUDENT_SUBJECT_FILTER_SCHOOL' ) && ! myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_COURSE_COMMENT_FILTER_SCHOOL' ) && ($psService->getPsCustomerId () != myUser::getPscustomerID ())) {
					$service_courses = array ();
				} elseif (myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_COURSE_COMMENT_FILTER_SCHOOL' )) {
					$service_courses = Doctrine::getTable ( 'PsServiceCourses' )->setPsServiceCoursesByPsService ( 'id, title', $ps_service_id )
						->execute ();
				} else {
					$service_courses = Doctrine::getTable ( 'PsServiceCourses' )->getServiceCoursesByUserId ( myUser::getUserId (), $ps_service_id );
				}

				/*
				 * elseif(myUser::credentialPsCustomers('PS_STUDENT_SERVICE_COURSE_COMMENT_TEACHER') and !myUser::isAdministrator())
				 * {
				 * $service_courses = Doctrine::getTable('PsServiceCourses')->getServiceCoursesByUserId(myUser::getUserId(), $ps_service_id);
				 * }
				 * else {
				 * $service_courses = Doctrine::getTable ( 'PsServiceCourses' )->setPsServiceCoursesByPsService ( 'id, title', $ps_service_id )->execute ();
				 * }
				 */
			} else {
				$service_courses = array ();
			}

			return $this->renderPartial ( 'option_service_courses', array (
					'service_courses' => $service_courses ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeServiceCoursesMember(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {
			$psc_id = intval ( $request->getParameter ( "psc_id" ) );

			$ps_member = Doctrine::getTable ( 'PsMember' )->setSQLMembers ( $psc_id )
				->execute ();

			return $this->renderPartial ( 'option_ps_member', array (
					'ps_member' => $ps_member ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeNew(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->ps_service_courses = $this->form->getObject ();

		$this->list_student = array ();
	}

	public function executeCreate(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->ps_service_courses = $this->form->getObject ();

		$this->processForm ( $request, $this->form );

		$this->list_student = array ();

		$this->setTemplate ( 'new' );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_service_courses = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_service_courses->getPsService (), 'PS_STUDENT_SERVICE_COURSES_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_service_courses );

		$this->list_student = Doctrine::getTable ( 'StudentService' )->getStudentByPsServiceCourse ( $this->form->getObject ()
			->getId () );

		// foreach ($this->list_student as $stu) {
		// echo $stu->getFullName();
		// echo '<br>';
		// }
		// exit(0);
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_service_courses = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_service_courses->getPsService (), 'PS_STUDENT_SERVICE_COURSES_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_service_courses );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {
				$ps_service_courses = $form->save ();

				// Lay so luong khoa hoc cua mon hoc
				if ($ps_service_courses) {

					$numberCourse = Doctrine::getTable ( 'PsServiceCourses' )->getNumberServiceCoursesByServiceId ( $ps_service_courses->getPsServiceId () );

					// update lai truong 'so lop' trong bang mon hoc
					$ps_service = Doctrine::getTable ( 'Service' )->findOneBy ( 'id', $ps_service_courses->getPsServiceId () );
					$ps_service->setNumberCourse ( $numberCourse );
					$ps_service->save ();
				}
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
					'object' => $ps_service_courses ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				$this->redirect ( '@ps_service_courses_new' );
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_service_courses_edit',
						'sf_subject' => $ps_service_courses ) );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->ps_service_courses = $this->getRoute ()
			->getObject ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->ps_service_courses ) ) );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_service_courses->getPsService (), 'PS_STUDENT_SERVICE_COURSES_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		// $ps_service = Doctrine::getTable('Service')->findOneBy('id', $this->getRoute()->getObject()->getPsServiceId());
		$ps_service = $this->ps_service_courses->getPsService ();
		// Chech du lieu da duoc su dung

		// thoi khoa bieu
		$ps_service_course_schedules = Doctrine::getTable ( 'PsServiceCourseSchedules' )->findOneBy ( 'ps_service_course_id', $this->ps_service_courses->getId () );

		// danh sach hoc sinh dang ky hoc
		$list_student = Doctrine::getTable ( 'StudentService' )->getStudentByPsServiceCourse ( $this->ps_service_courses->getId (), $this->ps_service_courses->getPsServiceId () );

		if ($ps_service_course_schedules || count ( $list_student ) > 0) {

			$this->getUser ()
				->setFlash ( 'error', 'This course has generated data. Can not delete.' );
		} else {
			if ($this->ps_service_courses->delete ()) {
				$this->getUser ()
					->setFlash ( 'notice', 'The item was deleted successfully.' );
				$ps_service->setNumberCourse ( ( int ) ($ps_service->getNumberCourse () - 1) );
				$ps_service->save ();
			} else {
				$this->getUser ()
					->setFlash ( 'error', 'System an error' );
			}
		}

		$this->redirect ( '@ps_service_courses' );
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$records = Doctrine_Query::create ()->from ( 'PsServiceCourses' )
			->whereIn ( 'id', $ids )
			->execute ();

		foreach ( $records as $record ) {
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );

			$ps_service_course_schedules = Doctrine::getTable ( 'PsServiceCourseSchedules' )->findOneBy ( 'ps_service_course_id', $record->getId () );
			$list_student = Doctrine::getTable ( 'StudentService' )->getStudentByPsServiceCourse ( $this->ps_service_courses->getId (), $record->getPsServiceId () );

			if (! $ps_service_course_schedules && count ( $list_student ) <= 0) {

				$ps_service = Doctrine::getTable ( 'Service' )->findOneBy ( 'id', $record->getPsServiceId () );
				$ps_service->setNumberCourse ( ( int ) ($ps_service->getNumberCourse () - 1) );
				$ps_service->save ();

				$record->delete ();
			}
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );

		$this->redirect ( '@ps_service_courses' );
	}

	public function executeDetail(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$sc_id = $request->getParameter ( 'id' );

			if ($sc_id <= 0) {
				$this->forward404Unless ( $sc_id, sprintf ( 'Object does not exist.' ) );
			}

			$this->service_courses_detail = Doctrine::getTable ( 'PsServiceCourses' )->getServiceCoursesDetail ( $sc_id );

			$this->forward404Unless ( myUser::checkAccessObject ( $this->service_courses_detail->getPsService (), 'PS_STUDENT_SERVICE_COURSES_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

			// $this->list_student = Doctrine::getTable ( 'StudentService' )->getStudentByPsServiceCourse ($sc_id, $this->service_courses_detail->getPsServiceId () );
			$this->list_student = Doctrine::getTable ( 'StudentService' )->getStudentByPsServiceCourse ( $sc_id );
		} else
			exit ( 0 );
	}
}
