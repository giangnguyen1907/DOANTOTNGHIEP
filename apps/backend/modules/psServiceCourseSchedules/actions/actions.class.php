<?php
require_once dirname ( __FILE__ ) . '/../lib/psServiceCourseSchedulesGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psServiceCourseSchedulesGeneratorHelper.class.php';

/**
 * psServiceCourseSchedules actions.
 *
 * @package kidsschool.vn
 * @subpackage psServiceCourseSchedules
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psServiceCourseSchedulesActions extends autoPsServiceCourseSchedulesActions {

	/**
	 * Kiem tra tkb da ton tai chua.
	 *
	 *
	 * @param
	 *        	string -
	 * @return json
	 */
	public function executeCheckSchedules(sfWebRequest $request) {

		$ps_customer_id = $request->getParameter ( 'ps_customer_id' );

		$ps_week = $request->getParameter ( 'ps_week' );

		$ps_year = $request->getParameter ( 'ps_year' );

		$weeks = PsDateTime::getWeeksOfYear ( $ps_year );

		$weeks_form = $weeks [$ps_week - 1];

		$form_week_start = $weeks_form ['week_start'];

		$form_week_end = $weeks_form ['week_end'];

		echo json_encode ( array (
				'valid' => true,
				'message' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Destination schedules has data. Do you want to continue?' ),
				'available' => Doctrine::getTable ( 'PsServiceCourseSchedules' )->checkListSchedulesWeek ( $form_week_start, $form_week_end, $ps_customer_id ) ) );

		exit ( 0 );
	}

	public function executeCoursesByCustomer(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {
			$psc_id = intval ( $request->getParameter ( "psc_id" ) );

			$courses = Doctrine::getTable ( 'PsServiceCourses' )->setPsServiceCoursesByPsCustomer ( 'id, title', $psc_id, PreSchool::ACTIVE )
				->execute ();

			return $this->renderPartial ( 'option_courses', array (
					'courses' => $courses ) );
		} else {
			exit ( 0 );
		}
	}

	protected function processFilterCourseSchedulesWeek(sfWebRequest $request) {

		$formFilter = new sfFormFilter ();

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_COURSE_SHEDULES_FILTER_SCHOOL' )) {

			$formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px; width:auto;",
					'data-placeholder' => _ ( '-All school-' ),
					'required' => true ) ) );

			$formFilter->setValidator ( 'ps_customer_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true ) ) );
		} else {

			$formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$formFilter->setDefault ( 'ps_customer_id', myUser::getPscustomerID () );
		}

		$ps_customer_id = $formFilter->getDefault ( 'ps_customer_id' );

		if (! $ps_customer_id)
			$ps_customer_id = $request->getParameter ( 'ps_customer_id' );

		$ps_member_id = $formFilter->getDefault ( 'ps_member_id' );
		if (! $ps_member_id)
			$ps_member_id = $request->getParameter ( 'ps_member_id' );

		if ($ps_customer_id > 0) {

			$formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select workplace-' ) ) ) );

			$formFilter->setWidget ( 'ps_service_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Service',
					'query' => Doctrine::getTable ( 'Service' )->setServicesTypeScheduleByPsCustomer ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => '-Select subjects-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select subjects-' ) ) ) );

			$formFilter->setWidget ( 'ps_member_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsMember',
					'query' => Doctrine::getTable ( 'PsMember' )->setSQLMembers ( $ps_customer_id ),
					'add_empty' => '-Select teacher-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select teacher-' ) ) ) );
		} else {

			$formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select workplace-' ) ) ) );
			$formFilter->setWidget ( 'ps_member_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select teacher-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select teacher-' ) ) ) );

			$formFilter->setWidget ( 'ps_service_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select subjects-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select subjects-' ) ) ) );
		}

		$ps_workplace_id = $formFilter->getDefault ( 'ps_workplace_id' );

		if (! $ps_workplace_id)
			$ps_workplace_id = $request->getParameter ( 'ps_workplace_id' );

		if ($ps_workplace_id > 0) {
			$formFilter->setWidget ( 'ps_class_room_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsClassRooms',
					'query' => Doctrine::getTable ( 'PsClassRooms' )->setSqlParams ( 'id, title', array (
							'ps_workplace_id' => $formFilter->getDefault ( 'ps_workplace_id' ),
							'ps_customer_id' => $ps_customer_id ) ),
					'add_empty' => '-Select class room-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select class room-' ) ) ) );
		} else {
			$formFilter->setWidget ( 'ps_class_room_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class room-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select class room-' ) ) ) );
		}

		$ps_service_id = $formFilter->getDefault ( 'ps_service_id' );
		if (! $ps_service_id)
			$ps_service_id = $request->getParameter ( 'ps_service_id' );

		$ps_service_course_id = $formFilter->getDefault ( 'ps_service_course_id' );
		if (! $ps_service_course_id)
			$ps_service_course_id = $request->getParameter ( 'ps_service_course_id' );

		if ($ps_service_id > 0) {
			$formFilter->setWidget ( 'ps_service_course_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsServiceCourses',
					'query' => Doctrine::getTable ( 'PsServiceCourses' )->setPsServiceCoursesByPsService ( 'id, title', $ps_service_id, PreSchool::ACTIVE ),
					'add_empty' => '-Select courses-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select courses-' ) ) ) );
		} else {
			$formFilter->setWidget ( 'ps_service_course_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select courses-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select courses-' ) ) ) );
		}

		$formFilter->setValidator ( 'ps_service_course_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsServiceCourses',
				'required' => false ) ) );

		$formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsWorkPlaces',
				'required' => false ) ) );

		$ps_class_room_id = $formFilter->getDefault ( 'ps_class_room_id' );
		if (! $ps_class_room_id)
			$ps_class_room_id = $request->getParameter ( 'ps_class_room_id' );

		$formFilter->setValidator ( 'ps_class_room_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsClassRooms',
				'required' => false ) ) );
		$years = range ( date ( 'Y' ) + 1, sfConfig::get ( 'app_begin_year' ) );

		$formFilter->setWidget ( 'ps_year', new sfWidgetFormChoice ( array (
				'choices' => array_combine ( $years, $years ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px; width:auto;",
				'data-placeholder' => _ ( '-Select year-' ) ) ) );

		$formFilter->setWidget ( 'ps_number_week', new sfWidgetFormInputHidden () );
		$formFilter->setWidget ( 'ps_current_year', new sfWidgetFormInputHidden () );

		$ps_week = $request->getParameter ( 'ps_week' );
		$ps_year = $request->getParameter ( 'ps_year' );

		// Nam hien tai
		$ps_year = $ps_year ? $ps_year : date ( 'Y' );

		// Tuan trong nam cua ngay hien tai
		$ps_week = $ps_week ? $ps_week : PsDateTime::getIndexWeekOfYear ( date ( 'Y-m-d' ) );
		$weeked = ( int ) $ps_week;
		if ($request->isMethod ( 'post' )) {

			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'course_schedules_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_week = ($value_student_filter ['ps_week']) ? $value_student_filter ['ps_week'] : $ps_week;
			$ps_year = ($value_student_filter ['ps_year']) ? $value_student_filter ['ps_year'] : $ps_year;

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$ps_class_room_id = $value_student_filter ['ps_class_room_id'];

			$ps_member_id = $value_student_filter ['ps_member_id'];

			$ps_service_id = $value_student_filter ['ps_service_id'];

			$ps_service_course_id = $value_student_filter ['ps_service_course_id'];
		}

		// Lay thong tin tuan cua nam
		$weeks = PsDateTime::getWeeksOfYear ( $ps_year );

		$formFilter->setWidget ( 'ps_week', new sfWidgetFormChoice ( array (
				'choices' => PsDateTime::getOptionsWeeks ( $weeks ) ), array (
				'class' => 'select2',
				'style' => "min-width:300px;width:100%;",
				'data-placeholder' => _ ( '-Select district-' ) ) ) );

		$formFilter->setDefault ( 'ps_number_week', count ( $weeks ) );

		// Get week in form
		$form_week_start = null;
		$form_week_end = null;
		$form_week_list = array ();

		if (isset ( $weeks [$ps_week - 1] )) {

			$weeks_form = $weeks [$ps_week - 1];

			$form_week_start = $weeks_form ['week_start'];

			$form_week_end = $weeks_form ['week_end'];

			$form_week_list = $weeks_form ['week_list'];
		}

		$formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
		$formFilter->setDefault ( 'ps_week', $weeked );
		$formFilter->setDefault ( 'ps_year', $ps_year );
		$formFilter->setDefault ( 'ps_current_year', $ps_year );

		$formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		$formFilter->setDefault ( 'ps_class_room_id', $ps_class_room_id );
		$formFilter->setDefault ( 'ps_member_id', $ps_member_id );
		$formFilter->setDefault ( 'ps_service_id', $ps_service_id );
		$formFilter->setDefault ( 'ps_service_course_id', $ps_service_course_id );

		$formFilter->getWidgetSchema ()
			->setNameFormat ( 'course_schedules_filter[%s]' );

		return array (
				'formFilter' => $formFilter,
				'form_week_start' => $form_week_start,
				'form_week_end' => $form_week_end,
				'form_week_list' => $form_week_list );
	}

	// Lay tkb cua tuan
	public function executePsCourseSchedulesWeek(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			// $this->form = $this->configuration->getForm();

			$this->formFilter = new sfFormFilter ();

			$value_student_filter = $request->getParameter ( 'course_schedules_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];
			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];
			$ps_service_course_id = $value_student_filter ['ps_service_course_id'];
			$ps_class_room_id = $value_student_filter ['ps_class_room_id'];
			$ps_member_id = $value_student_filter ['ps_member_id'];
			$ps_service_id = $value_student_filter ['ps_service_id'];
			$ps_week = $value_student_filter ['ps_week'];
			$ps_year = $value_student_filter ['ps_year'];

			// Lay thong tin tuan cua nam
			$weeks = PsDateTime::getWeeksOfYear ( $ps_year );

			$this->formFilter->setWidget ( 'ps_number_week', new sfWidgetFormInputHidden () );
			$this->formFilter->setDefault ( 'ps_number_week', count ( $weeks ) );
			$this->formFilter->setWidget ( 'ps_current_year', new sfWidgetFormInputHidden () );

			// Get week in form
			$this->week_start = null;
			$this->week_end = null;
			$this->week_list = array ();

			if (isset ( $weeks [$ps_week - 1] )) {

				$weeks_form = $weeks [$ps_week - 1];

				$this->week_start = $weeks_form ['week_start'];
				$this->week_end = $weeks_form ['week_end'];
				$this->week_list = $weeks_form ['week_list'];
			}

			$this->formFilter->setDefault ( 'ps_current_year', $ps_year );

			$this->formFilter->getWidgetSchema ()
				->setNameFormat ( 'course_schedules_filter[%s]' );

			$this->list_course_schedules = array ();

			if ($ps_customer_id > 0 && $this->week_start != null)
				$this->list_course_schedules = Doctrine::getTable ( 'PsServiceCourseSchedules' )->getListCourseSchedulesWeek ( $this->week_start, $this->week_end, $ps_customer_id, $ps_service_id, $ps_service_course_id, $ps_class_room_id, $ps_member_id, $ps_workplace_id );

			return $this->renderPartial ( 'psServiceCourseSchedules/ajax_table_schedule', array (

					'list_course_schedules' => $this->list_course_schedules,
					'week_list' => $this->week_list,
					'width_th' => (100 / (count ( $this->week_list ) + 1)),
					'formFilter' => $this->formFilter ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeIndex(sfWebRequest $request) {

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

		$this->redirect ( '@ps_service_course_schedules_new' );
	}

	public function executeNew(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$filterCourseSchedulesWeek = $this->processFilterCourseSchedulesWeek ( $request );

		$this->formFilter = $filterCourseSchedulesWeek ['formFilter'];

		if ($this->form->getDefault ( 'ps_customer_id' ) > 0)
			$ps_customer_id = $this->form->getDefault ( 'ps_customer_id' );
		else
			$ps_customer_id = $this->formFilter->getDefault ( 'ps_customer_id' );

		$this->ps_service_course_schedules = $this->form->getObject ();

		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );

		$this->form->setDefault ( 'ps_customer_id', $ps_customer_id );

		$this->week_list = $filterCourseSchedulesWeek ['form_week_list'];

		$this->list_course_schedules = array ();

		if ($ps_customer_id > 0 && $filterCourseSchedulesWeek ['form_week_start'] != null)

			$this->list_course_schedules = Doctrine::getTable ( 'PsServiceCourseSchedules' )->getListCourseSchedulesWeek ( $filterCourseSchedulesWeek ['form_week_start'], $filterCourseSchedulesWeek ['form_week_end'], $ps_customer_id );
	}

	public function executeCreate(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$filterCourseSchedulesWeek = $this->processFilterCourseSchedulesWeek ( $request );

		$this->formFilter = $filterCourseSchedulesWeek ['formFilter'];

		if ($this->form->getDefault ( 'ps_customer_id' ) > 0)
			$ps_customer_id = $this->form->getDefault ( 'ps_customer_id' );
		else
			$ps_customer_id = $this->formFilter->getDefault ( 'ps_customer_id' );

		$this->ps_service_course_schedules = $this->form->getObject ();

		$this->form = $this->configuration->getForm ( $this->ps_service_course_schedules );

		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );

		$this->week_list = $filterCourseSchedulesWeek ['form_week_list'];

		$this->list_course_schedules = array ();

		if ($ps_customer_id > 0 && $filterCourseSchedulesWeek ['form_week_start'] != null)

			$this->list_course_schedules = Doctrine::getTable ( 'PsServiceCourseSchedules' )->getListCourseSchedulesWeek ( $filterCourseSchedulesWeek ['form_week_start'], $filterCourseSchedulesWeek ['form_week_end'], $ps_customer_id );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'new' );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->ps_service_course_schedules = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_service_course_schedules->getPsServiceCourses ()
			->getPsService (), 'PS_STUDENT_SERVICE_COURSE_SHEDULES_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_service_course_schedules );

		$filterCourseSchedulesWeek = $this->processFilterCourseSchedulesWeek ( $request );

		$this->formFilter = $filterCourseSchedulesWeek ['formFilter'];

		$ps_customer_id = $this->ps_service_course_schedules->getPsServiceCourses ()
			->getPsService ()
			->getPsCustomerId ();

		$ps_class_room_id = $this->ps_service_course_schedules->getPsClassRoomId ();

		$ps_workplace_id = $this->ps_service_course_schedules->getPsClassRooms ()
			->getPsWorkplaceId ();

		$ps_service_course_id = $this->ps_service_course_schedules->getPsServiceCourseId ();

		// Mon học
		$ps_service_id = $this->ps_service_course_schedules->getPsServiceCourses ()
			->getPsServiceId ();

		$date_at = $this->ps_service_course_schedules->getDateAt ();

		$ps_week = date ( "W", strtotime ( $date_at ) );

		$ps_year = date ( "Y", strtotime ( $date_at ) );

		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );

		$this->formFilter->setDefault ( 'ps_class_room_id', $ps_class_room_id );

		$this->formFilter->setDefault ( 'ps_service_course_id', $ps_service_course_id );

		$this->formFilter->setDefault ( 'ps_service_id', $ps_service_id );

		$this->formFilter->setDefault ( 'ps_week', $ps_week );

		$this->formFilter->setDefault ( 'ps_year', $ps_year );

		$weeks = PsDateTime::getWeeksOfYear ( $ps_year );

		$weeks_form = $weeks [$ps_week - 1];

		$form_week_start = $weeks_form ['week_start'];

		$form_week_end = $weeks_form ['week_end'];

		$this->week_list = $weeks_form ['week_list'];

		$this->list_course_schedules = Doctrine::getTable ( 'PsServiceCourseSchedules' )->getListCourseSchedulesWeek ( $form_week_start, $form_week_end, $ps_customer_id );

		$this->setTemplate ( 'new' );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_service_course_schedules = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_service_course_schedules->getPsServiceCourses ()
			->getPsService (), 'PS_STUDENT_SERVICE_COURSE_SHEDULES_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_service_course_schedules );

		$filterCourseSchedulesWeek = $this->processFilterCourseSchedulesWeek ( $request );

		$this->formFilter = $filterCourseSchedulesWeek ['formFilter'];

		$ps_customer_id = $this->ps_service_course_schedules->getPsServiceCourses ()
			->getPsService ()
			->getPsCustomerId ();

		$date_at = $this->ps_service_course_schedules->getDateAt ();

		$ps_week = date ( "W", strtotime ( $date_at ) );

		$ps_year = date ( "Y", strtotime ( $date_at ) );

		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );

		$this->formFilter->setDefault ( 'ps_week', $ps_week );

		$this->formFilter->setDefault ( 'ps_year', $ps_year );

		$weeks = PsDateTime::getWeeksOfYear ( $ps_year );

		$weeks_form = $weeks [$ps_week - 1];

		$form_week_start = $weeks_form ['week_start'];

		$form_week_end = $weeks_form ['week_end'];

		$this->week_list = $weeks_form ['week_list'];

		$this->list_course_schedules = Doctrine::getTable ( 'PsServiceCourseSchedules' )->getListCourseSchedulesWeek ( $form_week_start, $form_week_end, $ps_customer_id );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'new' );
	}

	public function executePersonal(sfWebRequest $request) {

		// $this->form = $this->configuration->getForm();
		$filterCourseSchedulesWeek = $this->processFilterCourseSchedulesWeek ( $request );

		$this->formFilter = $filterCourseSchedulesWeek ['formFilter'];

		$ps_customer_id = myUser::getPscustomerID ();

		// $this->ps_service_course_schedules = $this->form->getObject();

		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );

		// $filterCourseSchedulesWeek = $this->processFilterCourseSchedulesWeek($request);

		// $this->formFilter = $filterCourseSchedulesWeek['formFilter'];

		$this->week_list = $filterCourseSchedulesWeek ['form_week_list'];

		$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

		$this->list_course_schedules = array ();

		if ($ps_customer_id > 0 && $filterCourseSchedulesWeek ['form_week_start'] != null)

			$this->list_course_schedules = Doctrine::getTable ( 'PsServiceCourseSchedules' )->getListCourseSchedulesWeek ( $filterCourseSchedulesWeek ['form_week_start'], $filterCourseSchedulesWeek ['form_week_end'], $ps_customer_id );

		// $this->setTemplate('new');
	}

	public function executePsSchedulesCopy(sfWebRequest $request) {

		$form_schedule = $request->getParameter ( 'form' );

		$ps_customer_id = $form_schedule ['ps_customer_id'];
		$ps_year_source = $form_schedule ['ps_year_source'];
		$ps_week_source = $form_schedule ['week_source'];
		$ps_year_destination = $form_schedule ['ps_year_destination'];
		$ps_week_destination = $form_schedule ['week_destination'];
		$ps_class_room_id = $form_schedule ['ps_class_room_id'];
		$ps_service_id = $form_schedule ['ps_service_id'];
		$ps_member_id = $form_schedule ['ps_member_id'];
		$ps_service_course_id = $form_schedule ['ps_service_course_id'];

		$ps_workplace_id = $form_schedule ['ps_workplace_id'];

		// Lay thong tin tuan cua nam - nguon
		$weeks_source = PsDateTime::getWeeksOfYear ( $ps_year_source );

		$weeks_source_form = $weeks_source [$ps_week_source - 1];
		// Ngay bat dau cua tuan nguon
		$form_week_start_source = $weeks_source_form ['week_start'];
		$form_week_end_source = $weeks_source_form ['week_end'];

		// Lay danh sach schedule tuan nguon
		$list_schedule = Doctrine::getTable ( 'PsServiceCourseSchedules' )->getListCourseSchedulesWeek ( $form_week_start_source, $form_week_end_source, $ps_customer_id, $ps_service_id, $ps_service_course_id, $ps_class_room_id, $ps_member_id );

		// Lay thong tin tuan cua nam - dich
		$weeks_destination = PsDateTime::getWeeksOfYear ( $ps_year_destination );
		$weeks_destination_form = $weeks_destination [$ps_week_destination - 1];
		// Ngay bat dau cua tuan dich
		$form_week_start_destination = $weeks_destination_form ['week_start'];

		// tinh so ngay chech lech giua tuan nguon va tuan dich
		$date1 = date_create ( $form_week_start_source );
		$date2 = date_create ( $form_week_start_destination );
		$diff = date_diff ( $date1, $date2 );
		$number_day = $diff->format ( "%R%a" );

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			foreach ( $list_schedule as $schedule ) {
				$new_schedules = new PsServiceCourseSchedules ();
				$new_schedules->setPsServiceCourseId ( $schedule->getCoursesId () );
				$new_schedules->setPsClassRoomId ( $schedule->getClassRoomId () );
				$new_schedules->setStartTimeAt ( $schedule->getStartTimeAt () );
				$new_schedules->setEndTimeAt ( $schedule->getEndTimeAt () );
				$date_at = strtotime ( $number_day . " day", strtotime ( $schedule->getDateAt () ) );
				$new_schedules->setDateAt ( date ( 'Y-m-d', $date_at ) );
				$new_schedules->setIsActivated ( $schedule->getIsActivated () );

				$new_schedules->setUserCreatedId ( sfContext::getInstance ()->getUser ()
					->getGuardUser ()
					->getId () );
				$new_schedules->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
					->getGuardUser ()
					->getId () );

				$new_schedules->save ();
			}
			$conn->commit ();
		} catch ( Exception $e ) {
			throw new Exception ( $e->getMessage () );
			$this->getUser ()
				->setFlash ( 'error', 'Schedules was copied fail.' );
			$conn->rollback ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'Schedules was copied successfully' );

		// $this->redirect('@ps_service_course_schedules_new');
		if (myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_COURSE_SHEDULES_FILTER_SCHOOL' ))
			$this->redirect ( '@ps_service_course_schedules_new?ps_customer_id=' . $ps_customer_id . '&ps_week=' . $ps_week_destination . '&ps_year=' . $ps_year_destination . '&ps_service_id=' . $ps_service_id . '&ps_service_course_id=' . $ps_service_course_id . '&ps_member_id=' . $ps_member_id . '&ps_workplace_id=' . $ps_workplace_id . '&ps_class_room_id=' . $ps_class_room_id );
		else
			$this->redirect ( '@ps_service_course_schedules?&ps_week=' . $ps_week_destination . '&ps_year=' . $ps_year_destination . '&ps_service_id=' . $ps_service_id . '&ps_service_course_id=' . $ps_service_course_id . '&ps_member_id=' . $ps_member_id . '&ps_workplace_id=' . $ps_workplace_id . '&ps_class_room_id=' . $ps_class_room_id );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {

				$ps_service_course_schedules = $form->save ();
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
					'object' => $ps_service_course_schedules ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				$this->redirect ( '@ps_service_course_schedules_new' );
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$ps_customer_id = $ps_service_course_schedules->getPsServiceCourses ()
					->getPsService ()
					->getPsCustomerId ();

				$date_at = $ps_service_course_schedules->getDateAt ();

				$ps_week = date ( "W", strtotime ( $date_at ) );

				$ps_year = date ( "Y", strtotime ( $date_at ) );

				// $this->redirect('@ps_service_course_schedules_new');
				$this->redirect ( '@ps_service_course_schedules_edit?id=' . $ps_service_course_schedules->getId () );
				// if (myUser::credentialPsCustomers('PS_STUDENT_SERVICE_COURSE_SHEDULES_FILTER_SCHOOL'))
				// $this->redirect('@ps_service_course_schedules_edit?id=' . $ps_service_course_schedules->getId());
				// else
				// $this->redirect('@ps_service_course_schedules_edit?id=' . $ps_service_course_schedules->getId());
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$object = $this->getRoute ()
			->getObject ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $object ) ) );

		$ps_service_course_schedules = $this->getRoute ()
			->getObject ();

		$ps_customer_id = $ps_service_course_schedules->getPsServiceCourses ()
			->getPsService ()
			->getPsCustomerId ();

		$date_at = $ps_service_course_schedules->getDateAt ();

		$ps_week = date ( "W", strtotime ( $date_at ) );

		$ps_year = date ( "Y", strtotime ( $date_at ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}
		if (myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_COURSE_SHEDULES_FILTER_SCHOOL' ))
			$this->redirect ( '@ps_service_course_schedules_new?ps_customer_id=' . $ps_customer_id . '&ps_week=' . $ps_week . '&ps_year=' . $ps_year );
		else
			$this->redirect ( '@ps_service_course_schedules?&ps_week=' . $ps_week . '&ps_year=' . $ps_year );
	}
}
