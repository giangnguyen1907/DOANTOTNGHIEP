<?php
require_once dirname ( __FILE__ ) . '/../lib/psTimesheetGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psTimesheetGeneratorHelper.class.php';

/**
 * psTimesheet actions.
 *
 * @package kidsschool.vn
 * @subpackage psTimesheet
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psTimesheetActions extends autoPsTimesheetActions {

	public function executePsLogtimeMember(sfWebRequest $request) {

		$member_id = $request->getParameter ( 'mb_id' );

		if ($request->getParameter ( 'time_at' ) != '') {
			$date = $request->getParameter ( 'time_at' );
		} else {
			$date = date ( 'Y-m-d' );
		}

		if ($request->getParameter ( 'input_date' ) != '')
			$input_date = $request->getParameter ( 'input_date' );
		else
			$input_date = date ( 'H:i:s' );

		$input_date = date ( "H:i:s", strtotime ( $input_date ) );

		$date = date ( "Y-m-d", strtotime ( $date ) );

		$login_at = $date . " " . $input_date;

		if ($request->getParameter ( 'absent_type' ) == 0) {
			$absent_type = 1;
			$number_time = 0;
		} else {
			$absent_type = 0;

			$time_at = Doctrine::getTable ( 'PsTimesheet' )->getTimesheetByMember ( $member_id );
			if ($time_at) {
				$first_date = strtotime ( $time_at->getTimeAt () );
				$second_date = strtotime ( $login_at );
				$datediff = abs ( $second_date - $first_date );
				// $number_time = $datediff;
				$number_time = floor ( $datediff / (60) );
			}
		}

		$created = sfContext::getInstance ()->getUser ()
			->getGuardUser ()
			->getId ();
		$update = $created;

		$psTimesheet = new PsTimesheet ();
		$psTimesheet->setMemberId ( $member_id );
		$psTimesheet->setIsIo ( $absent_type );
		$psTimesheet->setTimeAt ( $login_at );
		$psTimesheet->setTimesheetAt ( $date );
		$psTimesheet->setNumberTime ( $number_time );
		$psTimesheet->setDescription ( null );
		$psTimesheet->setUserCreatedId ( $created );
		$psTimesheet->setUserUpdatedId ( $update );

		$psTimesheet->save ();

		return $this->renderPartial ( 'psTimesheet/index_timesheet_load', array (
				'value' => $psTimesheet ) );
	}

	public function executePsLogtimeMemberTime(sfWebRequest $request) {

		$member_id = $request->getParameter ( 'mb_id' );

		$member_time = Doctrine::getTable ( 'PsTimesheet' )->getMemberTimesheet ( $member_id );

		return $this->renderPartial ( 'psTimesheet/list_field_time', array (
				'ps_timesheet' => $member_time ) );
	}

	public function executeReview(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$ps_school_year_id = null;

		$year_month = null;

		$ps_department_id = null;

		$member_id = null;

		$this->filter_list_student = array ();

		$timesheet_filter = $request->getParameter ( 'timesheet_filter' );

		if ($request->isMethod ( 'post' )) {

			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'timesheet_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$ps_department_id = $value_student_filter ['ps_department_id'];

			$member_id = $value_student_filter ['member_id'];

			$date_at = $value_student_filter ['date_at'];

			$this->filter_list_timesheet = Doctrine::getTable ( 'PsTimesheet' )->getListTimesheet ( $ps_customer_id, $ps_workplace_id, $ps_department_id, $member_id, $date_at );
		}

		if ($timesheet_filter) {

			$this->ps_workplace_id = isset ( $timesheet_filter ['ps_workplace_id'] ) ? $timesheet_filter ['ps_workplace_id'] : 0;

			$this->ps_department_id = isset ( $timesheet_filter ['ps_department_id'] ) ? $timesheet_filter ['ps_department_id'] : 0;

			$this->member_id = isset ( $timesheet_filter ['member_id'] ) ? $timesheet_filter ['member_id'] : 0;

			$this->date_at = isset ( $timesheet_filter ['date_at'] ) ? $timesheet_filter ['date_at'] : '';

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

		$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
			->fetchOne ()
			->getId ();

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );
		$this->formFilter->setDefault ( 'ps_department_id', $this->ps_department_id );
		$this->formFilter->setDefault ( 'member_id', $this->member_id );
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
		} else {
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );
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

		// Filters department

		$this->formFilter->setWidget ( 'ps_department_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsDepartment',
				'query' => Doctrine::getTable ( 'PsDepartment' )->setDepartmentByWorkplaceId ( $this->ps_workplace_id, $this->ps_customer_id ),
				'add_empty' => _ ( '-Select department-' ) ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select department-' ) ) ) );

		$this->formFilter->setValidator ( 'ps_department_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsDepartment',
				'required' => true ) ) );

		if ($this->ps_department_id > 0) {
			// Filters member

			$this->formFilter->setWidget ( 'member_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsMember',
					'query' => Doctrine::getTable ( 'PsMemberDepartments' )->setMemberDepartments ( $this->ps_department_id ),
					'add_empty' => _ ( '-Select member-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select member-' ) ) ) );

			$this->formFilter->setValidator ( 'member_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsMember',
					'required' => false ) ) );
		} else {
			$this->formFilter->setWidget ( 'member_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select member-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select member-' ) ) ) );
		}

		$this->formFilter->setWidget ( 'date_at', new psWidgetFormFilterInputDate ( array (), array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => _ ( 'Date at' ),
				'data-original-title' => $this->getContext ()
					->getI18N ()
					->__ ( 'Date at' ),
				'rel' => 'tooltip' ) ) );

		$this->formFilter->setValidator ( 'date_at', new sfValidatorDate ( array (
				'required' => true ) ) );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'ps_department_id', $this->ps_department_id );

		$this->formFilter->setDefault ( 'member_id', $this->member_id );

		$this->formFilter->setDefault ( 'date_at', $this->date_at );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'timesheet_filter[%s]' );
	}
}
