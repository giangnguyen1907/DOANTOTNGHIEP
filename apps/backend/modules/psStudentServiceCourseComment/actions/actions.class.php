<?php
require_once dirname ( __FILE__ ) . '/../lib/psStudentServiceCourseCommentGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psStudentServiceCourseCommentGeneratorHelper.class.php';

/**
 * psStudentServiceCourseComment actions.
 *
 * @package kidsschool.vn
 * @subpackage psStudentServiceCourseComment
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psStudentServiceCourseCommentActions extends autoPsStudentServiceCourseCommentActions {

	public function executeWarning(sfWebRequest $request) {

		$this->filter_value = $this->getFilters ();

		// Mon hoc
		$this->filter_value ['ps_service_id'] = (isset ( $this->filter_value ['ps_service_id'] )) ? $this->filter_value ['ps_service_id'] : '';

		// Khoa hoc
		$this->filter_value ['ps_service_course_id'] = (isset ( $this->filter_value ['ps_service_course_id'] )) ? $this->filter_value ['ps_service_course_id'] : '';

		// Lich hoc
		$this->filter_value ['ps_service_course_schedule_id'] = (isset ( $this->filter_value ['ps_service_course_schedule_id'] )) ? $this->filter_value ['ps_service_course_schedule_id'] : '';

		$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

		if ($this->filter_value ['ps_service_id'] <= 0 || $this->filter_value ['ps_service_course_id'] <= 0 || $this->filter_value ['ps_service_course_schedule_id'] <= 0) {

			$this->getUser ()
				->setFlash ( 'warning', 'Please select the subject, course, schedule to filter the data.', false );
		}
	}

	public function executeIndex(sfWebRequest $request) {

		// sorting
		if ($request->getParameter ( 'sort' ) && $this->isValidSortColumn ( $request->getParameter ( 'sort' ) )) {
			$this->setSort ( array (
					$request->getParameter ( 'sort' ),
					$request->getParameter ( 'sort_type' ) ) );
		}

		$this->filter_value = $this->getFilters ();

		if ($request->getParameter ( 'psscs_id' ) > 0) {

			$psscs_id = $request->getParameter ( 'psscs_id' );

			$schedule = Doctrine::getTable ( 'PsServiceCourseSchedules' )->findOneBy ( 'id', $psscs_id );

			$date_at = $schedule->getDateAt ();

			$service_course = $schedule->getPsServiceCourses ();

			$service = $service_course->getPsService ();

			$customer = $service->getPsCustomer ();

			$this->filter_value ['ps_customer_id'] = $customer->getId ();
			$this->filter_value ['ps_service_id'] = $service->getId ();
			$this->filter_value ['ps_service_course_id'] = $service_course->getId ();
			$this->filter_value ['tracked_at'] = $date_at;
			$this->filter_value ['ps_service_course_schedule_id'] = $psscs_id;

			$this->setFilters ( $this->filter_value );
		} else {
			// Mon hoc
			$this->filter_value ['ps_service_id'] = (isset ( $this->filter_value ['ps_service_id'] )) ? $this->filter_value ['ps_service_id'] : '';

			// Khoa hoc
			$this->filter_value ['ps_service_course_id'] = (isset ( $this->filter_value ['ps_service_course_id'] )) ? $this->filter_value ['ps_service_course_id'] : '';

			// Lich hoc
			$this->filter_value ['ps_service_course_schedule_id'] = (isset ( $this->filter_value ['ps_service_course_schedule_id'] )) ? $this->filter_value ['ps_service_course_schedule_id'] : '';
		}

		// foreach ($this->filter_value as $key => $value) {
		// echo $key."-".$value."<br>" ;
		// }

		// pager
		if ($request->getParameter ( 'page' )) {
			$this->setPage ( $request->getParameter ( 'page' ) );
		}

		if ($this->filter_value ['ps_service_id'] <= 0 || $this->filter_value ['ps_service_course_id'] <= 0 || $this->filter_value ['ps_service_course_schedule_id'] <= 0) {

			$this->getUser ()
				->setFlash ( 'warning', 'Please select the subject, course, schedule to filter the data.', false );

			$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

			$this->redirect ( '@ps_student_service_course_comment_warning' );
		}

		$this->pager = $this->getPager ();

		$this->sort = $this->getSort ();
	}

	public function executeFilter(sfWebRequest $request) {

		$this->setPage ( 1 );

		if ($request->hasParameter ( '_reset' )) {

			$this->setFilters ( $this->configuration->getFilterDefaults () );

			$this->redirect ( '@ps_student_service_course_comment' );
		}

		$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

		$this->filters->bind ( $request->getParameter ( $this->filters->getName () ) );

		if ($this->filters->isValid ()) {

			$this->setFilters ( $this->filters->getValues () );

			$this->redirect ( '@ps_student_service_course_comment' );
		}

		$this->filter_value = $request->getParameter ( $this->filters->getName () );

		$this->filter_value ['ps_service_course_schedule_id'] = (isset ( $this->filter_value ['ps_service_course_schedule_id'] )) ?: '';

		$this->pager = $this->getPager ();

		$this->sort = $this->getSort ();

		if ($this->filter_value ['ps_service_course_schedule_id'] <= 0) {

			$this->getUser ()
				->setFlash ( 'warning', 'Please select feature branch to view student list', false );

			$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

			$this->setTemplate ( 'warning' );
		} else {

			$this->setTemplate ( 'index' );
		}
	}

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

			if ($ps_customer_id == '') {
				$ps_customer_id = myUser::getPscustomerID ();
			}

			if ($this->week_start != null) {

				if (myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_COURSE_COMMENT_FILTER_SCHOOL' )) {
					$this->list_course_schedules = Doctrine::getTable ( 'PsServiceCourseSchedules' )->getListCourseSchedulesWeek ( $this->week_start, $this->week_end, $ps_customer_id, $ps_service_id, $ps_service_course_id, $ps_class_room_id, $ps_member_id, $ps_workplace_id );
				} else {
					$ps_member_id = Doctrine::getTable ( 'sfGuardUser' )->findOneById ( myUser::getUserId () )
						->getMemberId ();
					$this->list_course_schedules = Doctrine::getTable ( 'PsServiceCourseSchedules' )->getListCourseSchedulesWeek ( $this->week_start, $this->week_end, $ps_customer_id, $ps_service_id, $ps_service_course_id, $ps_class_room_id, $ps_member_id, $ps_workplace_id );
				}
			}
			return $this->renderPartial ( 'psStudentServiceCourseComment/ajax_table_schedule', array (

					'list_course_schedules' => $this->list_course_schedules,
					'week_list' => $this->week_list,
					'width_th' => (100 / (count ( $this->week_list ) + 1)),
					'formFilter' => $this->formFilter ) );
		} else {
			exit ( 0 );
		}
	}

	// Xem thong tin lich hoc
	public function executeDetail(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();

		$this->ps_customer_id = $request->getParameter ( 'customer' );
		$this->ps_workplace_id = $request->getParameter ( 'workplace' );
		$this->ps_service_id = $request->getParameter ( 'service' );
		$this->ps_service_course_id = $request->getParameter ( 'course_id' );
		$date_at = $request->getParameter ( 'date_at' );

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_COURSE_SHEDULES_FILTER_SCHOOL' )) {

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

		if ($this->ps_customer_id > 0) {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $this->ps_customer_id ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setWidget ( 'ps_service_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Service',
					'query' => Doctrine::getTable ( 'Service' )->setServicesTypeScheduleByPsCustomer ( 'id, title', $this->ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => '-Select subjects-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select subjects-' ) ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setWidget ( 'ps_service_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select subjects-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select subjects-' ) ) ) );
		}

		// $this->ps_workplace_id = $this->formFilter->getDefault ( 'ps_workplace_id' );

		if (! $this->ps_workplace_id) {
			$this->ps_workplace_id = $request->getParameter ( 'ps_workplace_id' );
		}
		// $this->ps_service_id = $this->formFilter->getDefault ( 'ps_service_id' );
		if (! $this->ps_service_id) {
			$this->ps_service_id = $request->getParameter ( 'ps_service_id' );
		}
		// $this->ps_service_course_id = $this->formFilter->getDefault ( 'ps_service_course_id' );
		if (! $this->ps_service_course_id) {
			$this->ps_service_course_id = $request->getParameter ( 'ps_service_course_id' );
		}
		if ($this->ps_service_id > 0) {
			$this->formFilter->setWidget ( 'ps_service_course_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsServiceCourses',
					'query' => Doctrine::getTable ( 'PsServiceCourses' )->setPsServiceCoursesByPsService ( 'id, title', $this->ps_service_id, PreSchool::ACTIVE ),
					'add_empty' => '-Select courses-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select courses-' ) ) ) );
		} else {
			$this->formFilter->setWidget ( 'ps_service_course_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select courses-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select courses-' ) ) ) );
		}

		$this->formFilter->setValidator ( 'ps_service_course_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsServiceCourses',
				'required' => false ) ) );

		$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsWorkPlaces',
				'required' => false ) ) );

		$years = range ( date ( 'Y' ) + 1, sfConfig::get ( 'app_begin_year' ) );

		$this->formFilter->setWidget ( 'ps_year', new sfWidgetFormChoice ( array (
				'choices' => array_combine ( $years, $years ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px; width:auto;",
				'data-placeholder' => _ ( '-Select year-' ) ) ) );

		$this->formFilter->setWidget ( 'ps_number_week', new sfWidgetFormInputHidden () );
		$this->formFilter->setWidget ( 'ps_current_year', new sfWidgetFormInputHidden () );

		$this->ps_week = $request->getParameter ( 'ps_week' );
		$this->ps_year = $request->getParameter ( 'ps_year' );

		// Nam hien tai
		$this->ps_year = $this->ps_year ? $this->ps_year : date ( 'Y' );

		// Tuan trong nam cua ngay hien tai
		if ($date_at != '') {
			$this->ps_week = $this->ps_week ? $this->ps_week : PsDateTime::getIndexWeekOfYear ( $date_at );
		} else {
			$this->ps_week = $this->ps_week ? $this->ps_week : PsDateTime::getIndexWeekOfYear ( date ( 'Y-m-d' ) );
		}

		$weeked = ( int ) $this->ps_week;

		$this->formFilter->setDefault ( 'ps_week', $weeked );

		$weeks = PsDateTime::getWeeksOfYear ( $this->ps_year );

		$this->formFilter->setWidget ( 'ps_week', new sfWidgetFormChoice ( array (
				'choices' => PsDateTime::getOptionsWeeks ( $weeks ) ), array (
				'class' => 'select2',
				'style' => "min-width:300px;width:100%;",
				'data-placeholder' => _ ( '-Select district-' ) ) ) );

		$this->formFilter->setDefault ( 'ps_number_week', count ( $weeks ) );

		// Get week in form
		$this->form_week_start = null;
		$this->form_week_end = null;
		$this->form_week_list = array ();

		if (isset ( $weeks [$this->ps_week - 1] )) {

			$weeks_form = $weeks [$this->ps_week - 1];

			$this->form_week_start = $weeks_form ['week_start'];

			$this->form_week_end = $weeks_form ['week_end'];

			$this->form_week_list = $weeks_form ['week_list'];
		}

		$this->week_list = $this->form_week_list;

		$this->formFilter->setDefault ( 'ps_year', $this->ps_year );
		$this->formFilter->setDefault ( 'ps_current_year', $this->ps_year );

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		$this->formFilter->setDefault ( 'ps_service_id', $this->ps_service_id );
		$this->formFilter->setDefault ( 'ps_service_course_id', $this->ps_service_course_id );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'course_schedules_filter[%s]' );

		if ($this->form_week_start != null) {
			if (myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_COURSE_COMMENT_FILTER_SCHOOL' )) {
				$this->list_course_schedules = Doctrine::getTable ( 'PsServiceCourseSchedules' )->getListCourseSchedulesWeek ( $this->form_week_start, $this->form_week_end, $this->ps_customer_id );
			} else {
				$member_id = Doctrine::getTable ( 'sfGuardUser' )->findOneById ( myUser::getUserId () )
					->getMemberId ();
				$this->list_course_schedules = Doctrine::getTable ( 'PsServiceCourseSchedules' )->getListCourseSchedulesWeek ( $this->form_week_start, $this->form_week_end, $this->ps_customer_id, null, null, null, $member_id );
			}
		}
	}

	// ham nay khong dung den
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

		if ($ps_customer_id != '') {
			$ps_customer_id = myUser::getPscustomerID ();
		}
		// $ps_customer_id = $request->getParameter ( 'ps_customer_id' );

		$ps_member_id = $formFilter->getDefault ( 'ps_member_id' );
		if (! $ps_member_id) {
			$ps_member_id = $request->getParameter ( 'ps_member_id' );
		}
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
		} else {

			$formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select workplace-' ) ) ) );

			$formFilter->setWidget ( 'ps_service_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select subjects-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select subjects-' ) ) ) );
		}

		$ps_workplace_id = $formFilter->getDefault ( 'ps_workplace_id' );

		if (! $ps_workplace_id) {
			$ps_workplace_id = $request->getParameter ( 'ps_workplace_id' );
		}
		$ps_service_id = $formFilter->getDefault ( 'ps_service_id' );
		if (! $ps_service_id) {
			$ps_service_id = $request->getParameter ( 'ps_service_id' );
		}
		$ps_service_course_id = $formFilter->getDefault ( 'ps_service_course_id' );
		if (! $ps_service_course_id) {
			$ps_service_course_id = $request->getParameter ( 'ps_service_course_id' );
		}
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

		// if ($request->isMethod ( 'post' )) {

		// // Handle the form submission
		// $value_student_filter = $request->getParameter ( 'course_schedules_filter' );

		// $ps_customer_id = $value_student_filter ['ps_customer_id'];

		// $ps_week = ($value_student_filter ['ps_week']) ? $value_student_filter ['ps_week'] : $ps_week;
		// $ps_year = ($value_student_filter ['ps_year']) ? $value_student_filter ['ps_year'] : $ps_year;

		// $ps_workplace_id = $value_student_filter ['ps_workplace_id'];

		// $ps_service_id = $value_student_filter ['ps_service_id'];

		// $ps_service_course_id = $value_student_filter ['ps_service_course_id'];
		// }

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
		$formFilter->setDefault ( 'ps_week', $ps_week );
		$formFilter->setDefault ( 'ps_year', $ps_year );
		$formFilter->setDefault ( 'ps_current_year', $ps_year );

		$formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );
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

	public function executePsServiceCourseScheduleByPsServiceCourse(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$ps_service_course_id = intval ( $request->getParameter ( "sc_id" ) );

			$tracked_at = $request->getParameter ( "tracked_at" );

			$ps_service_course_shedules = Doctrine::getTable ( 'PsServiceCourseSchedules' )->setPsServiceCourseSchedulesByPsServiceCourse ( $ps_service_course_id, $tracked_at )
				->execute ();

			return $this->renderPartial ( 'option_ps_service_course_shedules', array (
					'ps_service_course_shedules' => $ps_service_course_shedules ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeSaveStudentServiceCourseComment(sfWebRequest $request) {

		$tracked_at = $request->getParameter ( 'tracked_at' );

		$current_date = date ( "Ymd" );

		$check_current_date = (PsDateTime::psDatetoTime ( $tracked_at ) - PsDateTime::psDatetoTime ( $current_date ) >= 0) ? true : false; // Ngay hien tai

		if (! $check_current_date) {
			$this->getUser ()
				->setFlash ( 'notice', 'You can not change comment after 1 day.' );

			$this->redirect ( '@ps_student_service_course_comment' );
		}

		$ps_service_course_schedule_id = $request->getParameter ( 'ps_service_course_schedule_id' );

		$feature_options = $request->getParameter ( 'feature_option' );

		$currentUser = myUser::getUser ();

		if ($feature_options) {

			$history = array ();

			foreach ( $feature_options as $student_id => $feature_option_id ) {

				$commentHistory = new PsHistoryStudentServiceCourseComment ();

				$commentHistory->setStudentId ( $student_id );

				$commentHistory->setPsServiceCourseScheduleId ( $ps_service_course_schedule_id );

				if (Doctrine::getTable ( 'StudentServiceCourseComment' )->checkExists ( $student_id, $ps_service_course_schedule_id ))
					$commentHistory->setPsAction ( "edit" );
				else
					$commentHistory->setPsAction ( "add" );

				$history [$student_id] = $commentHistory;
			}
		}

		Doctrine_Core::getTable ( 'StudentServiceCourseComment' )->updateStudentServiceCourseCommentByPsServiceCourseSchedule ( $ps_service_course_schedule_id )
			->delete ();

		if ($feature_options) {

			foreach ( $feature_options as $student_id => $feature_option_id ) {

				$error = $time = $text = null;

				$feature_option_subject_id = $feature_option_id;

				if (is_array ( $feature_option_id ) && $feature_option_id != 0) {

					// <<<---- Start filter ----
					foreach ( $feature_option_id as $oid => $value ) {

						// get featureOptionFeature id

						$feature_option_subject_id = $oid;

						// *** Is textbox or selecttime ***
						if (is_array ( $value )) {
							foreach ( $value as $j => $get ) {

								// Is textkbox
								if ($j == 'textbox') {
									if ($get == null) {
										$error = 1;
										$this->getUser ()
											->setFlash ( 'error', 'A comment is required' );
										$this->redirect ( '@ps_student_service_course_comment' );
									} else {
										$text = $get;
										$student_feature = new StudentServiceCourseComment ();
										$student_feature->setStudentId ( $student_id );
										$student_feature->setPsServiceCourseScheduleId ( $ps_service_course_schedule_id );
										$student_feature->setNote ( $text );
										$student_feature->setFeatureOptionSubjectId ( $feature_option_subject_id );
										$student_feature->save ();
									}
								}
								// Is selecttime
							}
						} else {

							$student_feature = new StudentServiceCourseComment ();
							$student_feature->setStudentId ( $student_id );
							$student_feature->setPsServiceCourseScheduleId ( $ps_service_course_schedule_id );
							$student_feature->setFeatureOptionSubjectId ( $feature_option_subject_id );
							$student_feature->save ();
						}
					}
				} else {

					$student_feature = new StudentServiceCourseComment ();
					$student_feature->setStudentId ( $student_id );
					$student_feature->setPsServiceCourseScheduleId ( $ps_service_course_schedule_id );
					$student_feature->setFeatureOptionSubjectId ( $feature_option_subject_id );
					$student_feature->save ();
				}

				$comment = "";

				$optionFeature = Doctrine::getTable ( 'StudentServiceCourseComment' )->getFeatureOptionByScheduleAndStudent ( $student_id, $ps_service_course_schedule_id );

				foreach ( $optionFeature as $option ) {
					if ($option->getNote () == null)
						$comment .= $option->getOptionName () . ", ";
					else
						$comment .= $option->getNote () . ", ";
				}

				$comment = substr ( $comment, 0, - 2 );

				$serviceCourseSchedule = Doctrine::getTable ( 'PsServiceCourseSchedules' )->findOneBy ( 'id', $ps_service_course_schedule_id );

				$dateAt = $serviceCourseSchedule->getDateAt ();

				$startTime = $serviceCourseSchedule->getStartTimeAt ();

				$endTime = $serviceCourseSchedule->getEndTimeAt ();

				$serviceCourseTitle = $serviceCourseSchedule->getPsServiceCourses ()
					->getTitle ();

				$historyContent .= $this->getContext ()
					->getI18N ()
					->__ ( 'Service course' ) . ": " . $serviceCourseTitle . '\n' . $this->getContext ()
					->getI18N ()
					->__ ( 'Date at' ) . ": " . $dateAt . '\n' . $this->getContext ()
					->getI18N ()
					->__ ( 'Start time at' ) . ": " . $startTime . '\n' . $this->getContext ()
					->getI18N ()
					->__ ( 'End time at' ) . ": " . $endTime . '\n' . $this->getContext ()
					->getI18N ()
					->__ ( 'Comment' ) . ": " . $comment . '\n' . $this->getContext ()
					->getI18N ()
					->__ ( 'Created by' ) . ": " . $currentUser->getFirstName () . ' ' . $currentUser->getLastName () . '(' . $currentUser->getUsername () . ')' . '\n';

				$history [$student_id]->setHistoryContent ( $historyContent );

				$history [$student_id]->save ();
			}
		}
		$this->getUser ()
			->setFlash ( 'notice', 'Performance evaluation was saved successfully. You can add another one below.' );
		$this->redirect ( '@ps_student_service_course_comment' );
	}

	public function executeNew(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();
		$this->student_service_course_comment = $this->form->getObject ();
		// $this->student_id = $request->getParameter('student_id');
		// $this->course_id = $request->getParameter('course_id');
	}

	public function executeViewComment(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();

		$ps_service_course_id = $request->getParameter ( 'schid' );

		$student_id = $request->getParameter ( 'student' );

		$ps_customer_id = myUser::getPsCustomerId ();

		$ps_service_id = null;

		$this->student_id = $student_id;

		if ($student_id > 0) {
			$student = Doctrine::getTable ( 'Student' )->findOneBy ( 'id', $student_id );

			$course = Doctrine::getTable ( 'PsServiceCourses' )->findOneBy ( 'id', $ps_service_course_id );

			if ($student && $course) {
				$this->forward404Unless ( myUser::checkAccessObject ( $student, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$ps_service_id = $course->getPsService ()
					->getId ();

				$ps_customer_id = $student->getPsCustomerId ();

				$this->formFilter->setDefault ( 'ps_service_course_id', $ps_service_course_id );

				$this->formFilter->setDefault ( 'ps_service_id', $ps_service_id );

				$this->filter_list_student = Doctrine::getTable ( 'StudentService' )->getStudentByPsServiceCourse ( $ps_service_course_id );

				$this->comment_list = Doctrine::getTable ( 'StudentServiceCourseComment' )->getCourseCommentByStudentId ( $student_id, $ps_service_course_id );
			} else {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
			}
		}

		if ($request->isMethod ( 'post' )) {

			// Handle the form submission
			$ps_service_id = $request->getParameter ( 'ps_service_id' );

			$ps_service_course_id = $request->getParameter ( 'ps_service_course_id' );

			$this->formFilter->setDefault ( 'ps_service_course_id', $ps_service_course_id );

			$this->formFilter->setDefault ( 'ps_service_id', $ps_service_id );

			$ps_customer_id = Doctrine::getTable ( 'Service' )->findOneBy ( 'id', $ps_service_id )
				->getPsCustomerId ();

			$this->filter_list_student = Doctrine::getTable ( 'StudentService' )->getStudentByPsServiceCourse ( $ps_service_course_id );
		}

		$this->formFilter->setWidget ( 'ps_service_course_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsServiceCourses',
				'query' => Doctrine::getTable ( 'PsServiceCourses' )->setPsServiceCoursesByPsService ( 'id, title', $ps_service_id ),
				'add_empty' => _ ( '-Select courses-' ) ), array (
				'class' => 'select2',
				'style' => 'min-width:200px; width: 100%',
				'data-placeholder' => _ ( '-Select courses-' ) ) ) );

		$this->formFilter->setValidator ( 'ps_service_course_id', new sfValidatorInteger ( array (
				'required' => false ) ) );

		$this->formFilter->setWidget ( 'ps_service_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'Service',
				'query' => Doctrine::getTable ( 'Service' )->loadServices ( $ps_customer_id ) ), array (
				'class' => 'select2',
				'style' => 'min-width:200px; width: 100%',
				'data-placeholder' => _ ( '-Select subjects-' ) ) ) );

		$this->formFilter->setValidator ( 'ps_service_id', new sfValidatorInteger ( array (
				'required' => false ) ) );
	}

	public function executeHistory(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$ps_school_year_id = null;

		$year_month = null;

		$class_id = null;

		$student_id = null;

		$this->filter_list_student = array ();

		$history_filter = $request->getParameter ( 'history_filter' );

		if ($request->isMethod ( 'post' )) {

			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'history_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];

			$class_id = $value_student_filter ['class_id'];

			$student_id = $value_student_filter ['student_id'];

			$date_at_from = $value_student_filter ['date_at_from'];

			$date_at_to = $value_student_filter ['date_at_to'];

			// echo $student_id.$date_at_from.$date_at_to; die();

			$this->filter_list_history = Doctrine::getTable ( 'PsHistoryStudentServiceCourseComment' )->getHistoryStudentCourseComment ( $student_id, $date_at_from, $date_at_to );
		}

		if ($history_filter) {

			$this->ps_workplace_id = isset ( $history_filter ['ps_workplace_id'] ) ? $history_filter ['ps_workplace_id'] : 0;

			$this->class_id = isset ( $history_filter ['class_id'] ) ? $history_filter ['class_id'] : 0;

			$this->student_id = isset ( $history_filter ['student_id'] ) ? $history_filter ['student_id'] : 0;

			$this->date_at_from = isset ( $history_filter ['date_at_from'] ) ? $history_filter ['date_at_from'] : '';

			$this->date_at_to = isset ( $history_filter ['date_at_to'] ) ? $history_filter ['date_at_to'] : '';

			if ($this->ps_workplace_id > 0) {

				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {

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

		$this->ps_ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
			->fetchOne ()
			->getId ();

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		$this->formFilter->setDefault ( 'ps_ps_school_year_id', $this->ps_ps_school_year_id );

		if ($this->ps_customer_id > 0) {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $this->ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => true,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => true ) ) );

			// Filters by class

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id,
							'ps_school_year_id' => $ps_school_year_id ) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => false ) ) );

			// Filters student

			$this->formFilter->setWidget ( 'student_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Student',
					'query' => Doctrine::getTable ( 'Student' )->setSqlListStudentsNotSaturday ( $class_id ),
					'add_empty' => _ ( '-Select student-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select student-' ) ) ) );

			$this->formFilter->setValidator ( 'student_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'Student',
					'required' => false ) ) );
		} else {
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorPass () );

			$this->formFilter->setWidget ( 'student_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select student-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select student-' ) ) ) );

			$this->formFilter->setValidator ( 'student_id', new sfValidatorPass () );
		}

		$this->formFilter->setWidget ( 'ps_school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => false ), array (
				'class' => 'select2',
				'style' => "width:100%;min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ),
				'required' => true ) ) );

		$this->formFilter->setValidator ( 'ps_school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'column' => 'id',
				'required' => true ) ) );

		$this->formFilter->setWidget ( 'date_at_from', new psWidgetFormFilterInputDate ( array (), array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => _ ( 'Date at' ),
				'data-original-title' => $this->getContext ()
					->getI18N ()
					->__ ( 'Date at' ),
				'rel' => 'tooltip' ) ) );

		$this->formFilter->setValidator ( 'date_at_from', new sfValidatorDate ( array (
				'required' => false ) ) );

		$this->formFilter->setWidget ( 'date_at_to', new psWidgetFormFilterInputDate ( array (), array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => _ ( 'Date to' ),
				'data-original-title' => $this->getContext ()
					->getI18N ()
					->__ ( 'Date to' ),
				'rel' => 'tooltip' ) ) );

		$this->formFilter->setValidator ( 'date_at_to', new sfValidatorDate ( array (
				'required' => false ) ) );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'class_id', $this->class_id );

		$this->formFilter->setDefault ( 'student_id', $this->student_id );

		$this->formFilter->setDefault ( 'date_at_from', $this->date_at_from );

		$this->formFilter->setDefault ( 'date_at_to', $this->date_at_to );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'history_filter[%s]' );
	}
}
