<?php
require_once dirname ( __FILE__ ) . '/../lib/psTimesheetSummarysGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psTimesheetSummarysGeneratorHelper.class.php';

/**
 * psTimesheetSummarys actions.
 *
 * @package kidsschool.vn
 * @subpackage psTimesheetSummarys
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psTimesheetSummarysActions extends autoPsTimesheetSummarysActions {

	// Ham thong ke cham cong cua nhan vien
	public function executeStatistic(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$year_month = null;

		$ps_school_year_id = null;

		$department_id = null;

		$this->filter_list_student = array ();

		$timesheet_filter = $request->getParameter ( 'timesheet_filter' );

		if ($request->isMethod ( 'post' )) {

			// Handle the form submission

			$value_student_filter = $request->getParameter ( 'timesheet_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];

			$department_id = $value_student_filter ['ps_department_id'];

			$member_id = $value_student_filter ['member_id'];

			$this->year_month = $value_student_filter ['year_month'];

			// echo 'Cus_'.$ps_customer_id.'wp_'.$ps_workplace_id.'dp_'.$department_id.'Y-m :'.$year_month; die();

			$this->filter_list_student = Doctrine::getTable ( 'PsMember' )->getPsMemberByDepartmentId ( $ps_customer_id, $ps_workplace_id, $department_id, $member_id, $this->year_month );

			$this->timesheet_summarys = Doctrine::getTable ( 'PsTimesheetSummarys' )->getTimesheetSummaryOfMember ( $ps_customer_id, $ps_workplace_id, $department_id, $this->year_month );

			$this->member_absent = Doctrine::getTable ( 'PsMemberAbsents' )->getMemberAbsentInMonth ( $ps_customer_id, $ps_workplace_id, $department_id, $this->year_month );
			// Doctrine::getTable('PsTimesheetSummarys')->getTimesheetSummaryOfMember($ps_workplace_id,$department_id,$year_month);
		}

		$this->year_month = isset ( $logtimes_filter ['year_month'] ) ? $logtimes_filter ['year_month'] : date ( "m-Y" );

		// Lay nam hoc hien tai
		if ($ps_school_year_id == '') {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
		} else {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'id', $ps_school_year_id );
		}

		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );

		$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );

		$this->formFilter->setWidget ( 'year_month', new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select month-' ) ) + PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) ) );

		// Lay thang hien tai
		// $current_month = $this->year_month;

		$this->number_day = PsDateTime::psNumberDaysOfMonth ( $this->year_month );

		$this->formFilter->setDefault ( 'year_month', $this->year_month );

		if ($timesheet_filter) {

			$this->ps_school_year_id = isset ( $timesheet_filter ['ps_school_year_id'] ) ? $timesheet_filter ['ps_school_year_id'] : 0;

			$this->ps_customer_id = isset ( $timesheet_filter ['ps_customer_id'] ) ? $timesheet_filter ['ps_customer_id'] : myUser::getPscustomerID ();

			$this->ps_workplace_id = isset ( $timesheet_filter ['ps_workplace_id'] ) ? $timesheet_filter ['ps_workplace_id'] : 0;

			$this->ps_department_id = isset ( $timesheet_filter ['ps_department_id'] ) ? $timesheet_filter ['ps_department_id'] : 0;

			$this->member_id = isset ( $timesheet_filter ['member_id'] ) ? $timesheet_filter ['member_id'] : 0;

			$this->year_month = isset ( $timesheet_filter ['year_month'] ) ? $timesheet_filter ['year_month'] : date ( "m-Y" );

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
		if ($this->ps_customer_id == '') {
			$this->ps_customer_id = myUser::getPscustomerID ();
			$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$this->ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		if ($this->ps_school_year_id == '') {
			$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

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

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

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

			$this->formFilter->setValidator ( 'member_id', new sfValidatorPass () );
		}

		$this->formFilter->setDefault ( 'year_month', $this->year_month );

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'ps_department_id', $this->ps_department_id );

		$this->formFilter->setDefault ( 'member_id', $this->member_id );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'timesheet_filter[%s]' );
	}

	// Tong hop
	public function executeSynthetic(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$year_month = null;

		$ps_school_year_id = null;

		$department_id = null;

		$this->filter_list_student = array ();

		$timesheet_summary = $request->getParameter ( 'timesheet_summary' );

		if ($request->isMethod ( 'post' )) {

			// Handle the form submission

			$value_student_filter = $request->getParameter ( 'timesheet_summary' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];

			$department_id = $value_student_filter ['ps_department_id'];

			$year_month = $value_student_filter ['year_month'];

			// echo 'Cus_'.$ps_customer_id.'wp_'.$ps_workplace_id.'dp_'.$department_id.'Y-m :'.$year_month; die();
			$number_day = PsDateTime::psNumberDaysOfMonth ( $year_month );
			$number_day ['number_day_month'];

			$delete_student = Doctrine::getTable ( 'PsTimesheetSummarys' )->getTimesheetSummaryOfMember ( $ps_customer_id, $ps_workplace_id, $department_id, $year_month );
			foreach ( $delete_student as $record ) {
				$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
						'object' => $record ) ) );
				$record->delete ();
			}
			$created = sfContext::getInstance ()->getUser ()
				->getGuardUser ()
				->getId ();
			$update = $created;

			$this->list_student = Doctrine::getTable ( 'PsTimesheet' )->getSumTimesheetOfMember ( $ps_customer_id, $ps_workplace_id, $department_id, $year_month );

			foreach ( $this->list_student as $timesheet ) {

				$summary = new PsTimesheetSummarys ();
				$summary->setMemberId ( $timesheet->getMemberId () );
				$summary->setTimesheetAt ( $timesheet->getTimesheetAt () );
				$summary->setNumberTime ( $timesheet->getNumberTime () );
				$summary->setDescription ( null );
				$summary->setNumberBelated ( null );
				$summary->setNumberEarly ( null );
				$summary->setUserCreatedId ( $created );
				$summary->setUserUpdatedId ( $update );

				$summary->save ();
			}

			$this->redirect ( '@ps_timesheet_summarys_statistic' );
		}

		// Lay nam hoc hien tai

		$this->year_month = isset ( $timesheet_summary ['year_month'] ) ? $timesheet_summary ['year_month'] : date ( "m-Y" );

		$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );

		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );

		$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );

		$this->formFilter->setWidget ( 'year_month', new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select month-' ) ) + PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) ) );

		// Lay thang hien tai
		$current_month = $this->year_month;

		$this->number_day = PsDateTime::psNumberDaysOfMonth ( $current_month );

		$this->formFilter->setDefault ( 'year_month', $current_month );

		if ($timesheet_summary) {

			$this->ps_workplace_id = isset ( $timesheet_summary ['ps_workplace_id'] ) ? $timesheet_summary ['ps_workplace_id'] : 0;

			$this->ps_department_id = isset ( $timesheet_summary ['ps_department_id'] ) ? $timesheet_summary ['ps_department_id'] : 0;

			$this->member_id = isset ( $timesheet_summary ['member_id'] ) ? $timesheet_summary ['member_id'] : 0;

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

		if ($this->ps_customer_id == '') {
			$this->ps_customer_id = myUser::getPscustomerID ();
			$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		}

		if ($this->ps_school_year_id == '') {
			$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

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

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		// $this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId ();

		// $this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		// $this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );
		// $this->formFilter->setDefault ( 'ps_department_id', $this->ps_department_id );
		// $this->formFilter->setDefault ( 'member_id', $this->member_id );
		if ($this->ps_customer_id > 0) {

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
		} else {
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );
		}

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
				'required' => false ) ) );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'ps_department_id', $this->ps_department_id );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'timesheet_summary[%s]' );
	}

	// so cham cong
	public function executeExportTimesheetSummarys(sfWebRequest $request) {

		$customer_id = $request->getParameter ( 'cus' );

		$workplace_id = $request->getParameter ( 'wp' );

		$department_id = $request->getParameter ( 'dpm' );

		$year_month = $request->getParameter ( 'date' );

		$this->exportReportTimesheetSummarys ( $customer_id, $workplace_id, $department_id, $year_month );

		$this->redirect ( '@ps_timesheet_summarys_statistic' );
	}

	protected function exportReportTimesheetSummarys($customer_id, $workplace_id, $department_id, $year_month) {

		$exportFile = new ExportTimesheetSummaryReportHelper ( $this );

		$file_template_pb = 'tk_bangchamcong_00001.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/pschool/template_export/' . $file_template_pb;

		// $ps_customer_id = myUser::getPscustomerID();

		$school_name = Doctrine::getTable ( 'Pscustomer' )->findOneBy ( 'id', $customer_id );

		$member_id = null;

		$filter_list_member = Doctrine::getTable ( 'PsMember' )->getPsMemberByDepartmentId ( $customer_id, $workplace_id, $department_id, $member_id, $year_month );

		$timesheet_summarys = Doctrine::getTable ( 'PsTimesheetSummarys' )->getTimesheetSummaryOfMember ( $customer_id, $workplace_id, $department_id, $year_month );

		$member_absent = Doctrine::getTable ( 'PsMemberAbsents' )->getMemberAbsentInMonth ( $customer_id, $ps_workplace_id, $department_id, $year_month );

		$number_day = PsDateTime::psNumberDaysOfMonth ( $year_month );

		$exportFile->loadTemplate ( $path_template_file );

		$title_info = 'Sổ chấm công nhân viên' . '( Tháng ' . $year_month . ' )';

		$title_xls = 'SoChamCongThang_' . $year_month;

		$exportFile->setDataExportTimesheetInfoExport ( $school_name, $title_info, $title_xls );

		$exportFile->setDataExportTimesheet ( $filter_list_member, $timesheet_summarys, $member_absent, $year_month );

		$exportFile->saveAsFile ( "SoChamCong" . ".xls" );
	}
}
