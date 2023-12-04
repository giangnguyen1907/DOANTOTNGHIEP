<?php
require_once dirname ( __FILE__ ) . '/../lib/psFeatureBranchTimesGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psFeatureBranchTimesGeneratorHelper.class.php';

/**
 * psFeatureBranchTimes actions.
 *
 * @package quanlymamnon.vn
 * @subpackage psFeatureBranchTimes
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psFeatureBranchTimesActions extends autoPsFeatureBranchTimesActions {

	public function executeDetailByWeek(sfWebRequest $request) {

		// print_r($request->getParameter('menus_filter'));die;
		$this->ps_feature_branch_times = $this->getRoute ()
			->getObject ();

		$ps_feature_branch_id = $this->ps_feature_branch_times->getPsFeatureBranchId ();
		if ($ps_feature_branch_id > 0) {

			$ps_feature_branch = Doctrine::getTable ( 'FeatureBranch' )->findOneById ( $ps_feature_branch_id );

			$this->forward404Unless ( $ps_feature_branch, sprintf ( 'Object does not exist.' ) );

			$ps_feature = $ps_feature_branch->getFeature ();

			$this->forward404Unless ( myUser::checkAccessObject ( $ps_feature, 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		} else {

			$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
		}

		$this->form = $this->configuration->getForm ( $this->ps_feature_branch_times );

		// Lay ra gia tri nguoi dung nhap trong database
		$this->start_at = $this->ps_feature_branch_times->getStartAt ();
		$start_at = $this->start_at;

		if ($this->form->getDefault ( 'school_year_id' ) > 0)
			$school_year_id = $this->form->getDefault ( 'school_year_id' );
		else
			$school_year_id = $this->ps_feature_branch_times->getFeatureBranch ()
				->getSchoolYearId ();

		if ($this->form->getDefault ( 'ps_customer_id' ) > 0)
			$ps_customer_id = $this->form->getDefault ( 'ps_customer_id' );
		else
			$ps_customer_id = $this->ps_feature_branch_times->getFeatureBranch ()
				->getFeature ()
				->getPsCustomerId ();

		if ($this->form->getDefault ( 'ps_workplace_id' ) > 0)
			$ps_workplace_id = $this->form->getDefault ( 'ps_workplace_id' );
		else
			$ps_workplace_id = $this->ps_feature_branch_times->getFeatureBranch ()
				->getPsWorkplaceId ();

		if ($this->form->getDefault ( 'date_at' ) > 0)
			$date_at = $this->form->getDefault ( 'date_at' );
		else
			$date_at = $start_at;

		if ($this->form->getDefault ( 'ps_class_id' ) > 0)
			$ps_class_id = $this->form->getDefault ( 'ps_class_id' );
		else {
		}

		$weeks = PsDateTime::getWeeksOfYear ( date ( 'Y', strtotime ( $date_at ) ) );

		$ps_week = date ( "W", strtotime ( $date_at ) );

		$weeks_form = $weeks [$ps_week - 1];

		$form_week_start = $weeks_form ['week_start'];
		$this->week_start = $form_week_start;

		$form_week_end = $weeks_form ['week_end'];
		$this->week_end = $form_week_end;

		$this->week_list = $weeks_form ['week_list'];

		$filterFeatureBranchTimesWeek = $this->processfilterFeatureBranchTimesWeek ( $request, $this->ps_feature_branch_times );

		$this->formFilter = $filterFeatureBranchTimesWeek ['formFilter'];

		$this->formFilter->setDefault ( 'school_year_id', $school_year_id );

		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );

		$this->formFilter->setDefault ( 'date_at', $date_at );

		$this->list_menu = Doctrine::getTable ( 'FeatureBranchTimes' )->getListFBTWeek ( $form_week_start, $form_week_end, $ps_customer_id, $ps_workplace_id, $ps_class_id );
	}

	public function executeShow(sfWebRequest $request) {
		
		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$this->ps_week = null;

		$this->list_menu = $school_year_id = null;

		$this->ps_feature_branch_id = $this->ps_class_id = null;

		$this->filter_list_student = array ();

		$feature_branch_times_filters = $request->getParameter ( 'feature_branch_times_filters' );

		if ($request->isMethod ( 'post' )) {
			
			// Handle the form submission
			$value_student_filter = $feature_branch_times_filters;

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$school_year_id = $value_student_filter ['school_year_id'];

			$this->ps_class_id = $value_student_filter ['ps_class_id'];
			
			if(isset($value_student_filter ['ps_feature_branch_id']))
				$this->ps_feature_branch_id = $value_student_filter ['ps_feature_branch_id'];

			$this->ps_week = $value_student_filter ['ps_week'];

			$this->ps_year = $value_student_filter ['ps_year'];

			$weeks = PsDateTime::getWeeksOfYear ( $this->ps_year );

			$weeks_form = $weeks [$this->ps_week - 1];

			$form_week_start = $weeks_form ['week_start'];
			$this->week_start = $form_week_start;

			$form_week_end = $weeks_form ['week_end'];
			$this->week_end = $form_week_end;

			$this->week_list = $weeks_form ['week_list'];

			$this->list_menu = Doctrine::getTable ( 'FeatureBranchTimes' )->getListFBTWeek ( $form_week_start, $form_week_end, $ps_customer_id, $ps_workplace_id, $this->ps_class_id, $this->ps_feature_branch_id );
			
			$this->list_hour = Doctrine::getTable ( 'FeatureBranchTimes' )->getListFBTHourWorkWeek ( $form_week_start, $form_week_end, $ps_customer_id, $ps_workplace_id,$this->ps_class_id, $this->ps_feature_branch_id );
			
		} else {

			$ps_customer_id = myUser::getPscustomerID ();
			$member_id = myUser::getUser ()->getMemberId ();
			$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );

			$this->ps_year = date ( 'Y' );

			$this->ps_week = PsDateTime::getIndexWeekOfYear ( date ( 'Y-m-d' ) );

			$weeks = PsDateTime::getWeeksOfYear ( $this->ps_year );

			$weeks_form = $weeks [$this->ps_week - 1];
			
			$form_week_start = $weeks_form ['week_start'];
			$this->week_start = $form_week_start;
			//echo $form_week_start;
			$form_week_end = $weeks_form ['week_end'];
			$this->week_end = $form_week_end;
			
			$this->week_list = $weeks_form ['week_list'];

			$this->list_menu = Doctrine::getTable ( 'FeatureBranchTimes' )->getListFBTWeek ( $form_week_start, $form_week_end, $ps_customer_id, $ps_workplace_id );
			
			$this->list_hour = Doctrine::getTable ( 'FeatureBranchTimes' )->getListFBTHourWorkWeek ( $form_week_start, $form_week_end, $ps_customer_id, $ps_workplace_id );
			
		}

		if ($feature_branch_times_filters) {

			$this->school_year_id = isset ( $feature_branch_times_filters ['school_year_id'] ) ? $feature_branch_times_filters ['school_year_id'] : 0;

			$this->ps_workplace_id = isset ( $feature_branch_times_filters ['ps_workplace_id'] ) ? $feature_branch_times_filters ['ps_workplace_id'] : 0;

			$this->ps_class_id = isset ( $feature_branch_times_filters ['ps_class_id'] ) ? $feature_branch_times_filters ['ps_class_id'] : 0;

			$this->ps_feature_branch_id = isset ( $feature_branch_times_filters ['ps_feature_branch_id'] ) ? $feature_branch_times_filters ['ps_feature_branch_id'] : 0;

			$this->ps_year = isset ( $feature_branch_times_filters ['ps_year'] ) ? $feature_branch_times_filters ['ps_year'] : date ( 'Y' );

			$this->ps_week = isset ( $feature_branch_times_filters ['ps_week'] ) ? $feature_branch_times_filters ['ps_week'] : PsDateTime::getIndexWeekOfYear ( date ( 'Y-m-d' ) );

			if ($this->ps_workplace_id > 0) {

				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}

		$years = range ( date ( 'Y' ) + 1, sfConfig::get ( 'app_begin_year' ) );

		$this->formFilter->setWidget ( 'ps_year', new sfWidgetFormChoice ( array (
				'choices' => array_combine ( $years, $years ) ), array (
				'class' => 'select2',
				'style' => "min-width:80px; width:auto;",
				'data-placeholder' => _ ( '-Select year-' ) ) ) );

		$this->formFilter->setValidator ( 'ps_year', new sfValidatorPass ( array (
				'required' => true ) ) );

		if ($this->ps_year == '') {
			$this->ps_year = date ( 'Y' );
		}

		$weeks = PsDateTime::getWeeksOfYear ( $this->ps_year );

		$this->formFilter->setWidget ( 'ps_week', new sfWidgetFormChoice ( array (
				'choices' => PsDateTime::getOptionsWeeks ( $weeks ) ), array (
				'class' => 'select2',
				'style' => "min-width:250px;width:100%;",
				'data-placeholder' => _ ( '-Select week-' ) ) ) );

		$this->formFilter->setValidator ( 'ps_week', new sfValidatorPass ( array (
				'required' => true ) ) );

		// Get week in form
		$form_week_start = null;
		$form_week_end = null;
		$form_week_list = array ();

		$weeks_form = $weeks [$this->ps_week - 1];

		$form_week_start = $weeks_form ['week_start'];
		$this->week_start = $form_week_start;

		$form_week_end = $weeks_form ['week_end'];
		$this->week_end = $form_week_end;

		$this->week_list = $weeks_form ['week_list'];

		// $ps_week = $this->getDefault('ps_week');

		if ($this->ps_week == '') {
			$this->ps_week = PsDateTime::getIndexWeekOfYear ( date ( 'Y-m-d' ) );
		}

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {

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

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		if ($this->ps_workplace_id > 0) {

			// Filters by class
			$this->formFilter->setWidget ( 'ps_class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id,
							'ps_school_year_id' => $this->school_year_id,
							'is_activated' => PreSchool::ACTIVE
					) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => false ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_class_id', new sfValidatorPass () );
		}
		$param_feature = array (
				'school_year_id' => $this->school_year_id,
				'ps_customer_id' => $this->ps_customer_id,
				'ps_workplace_id' => $this->ps_workplace_id,
				'is_activated' => PreSchool::ACTIVE );
		
		$this->formFilter->setWidget ( 'ps_feature_branch_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'FeatureBranch',
				'query' => Doctrine::getTable ( 'FeatureBranch' )->setSqlFeatureBranchByFilters ( $param_feature ),
				'add_empty' => _ ( '-Select feature branch-' ) ), array (
				'class' => 'select2',
				'style' => "min-width:200px;width:100%;",
				'required' => false,
				'data-placeholder' => _ ( '-Select  feature branch-' ) ) ) );

		$this->formFilter->setValidator ( 'ps_feature_branch_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'FeatureBranch',
				'required' => false ) ) );

		$this->formFilter->setDefault ( 'school_year_id', $this->school_year_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'ps_class_id', $this->ps_class_id );

		$this->formFilter->setDefault ( 'ps_feature_branch_id', $this->ps_feature_branch_id );

		$this->formFilter->setDefault ( 'ps_year', $this->ps_year );

		$this->formFilter->setDefault ( 'ps_week', $this->ps_week );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'feature_branch_times_filters[%s]' );
	}

	public function executeExportClass(sfWebRequest $request) {
		
		$student_filters = $request->getParameter ( 'feature_branch_times_filters' );
		
		//print_r($student_filters);die;
		
		$ps_school_year_id = $student_filters ['school_year_id'];
		
		$ps_customer_id = $student_filters ['ps_customer_id'];
		
		$ps_workplace_id = $student_filters ['ps_workplace_id'];
		
		$ps_class_id = $student_filters ['ps_class_id'];
		
		$ps_year = $student_filters ['ps_year'];
		
		$ps_week = $student_filters ['ps_week'];
		
		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' )) {
			if ($ps_customer_id != myUser::getPscustomerID ()) {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}
		}
		
		$this->exportFeatureBranchTimesByClass ($ps_school_year_id, $ps_customer_id, $ps_workplace_id, $ps_class_id, $ps_year, $ps_week );
		
		$this->redirect ( '@ps_feature_branch_times_by_week' );
		
	}
	
	protected function exportFeatureBranchTimesByClass ($ps_school_year_id, $ps_customer_id, $ps_workplace_id, $ps_class_id, $ps_year, $ps_week ){
		
		$exportFile = new ExportStudentLogtimesReportHelper($this);
		
		$file_template_pb = 'bm_feature_branch_times.xls';
		
		$path_template_file = sfConfig::get('sf_web_dir') . '/uploads/export_data/' . $file_template_pb;
		
		//echo $ps_customer_id; die;
		
		//$school_name = Doctrine::getTable('Pscustomer')->findOneBy('id', $ps_customer_id);
		
		$school_name = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, $ps_class_id, $ps_workplace_id );
		
		$title_xls = "TKB";
		
		$weeks = PsDateTime::getWeeksOfYear ( $ps_year );
		
		$weeks_form = $weeks [$ps_week - 1];
		
		$form_week_start = $weeks_form ['week_start'];
		$week_start = $form_week_start;
		
		$form_week_end = $weeks_form ['week_end'];
		$week_end = $form_week_end;
		
		$week_list = $weeks_form ['week_list'];
		
		$title_info = $this->getContext ()->getI18N ()->__ ('Lich hoat dong tu ngay %value1% den ngay %value2%',array('%value1%'=>date('d',strtotime($week_start)),'%value2%'=>date('d/m/Y',strtotime($week_end))));
		
		if($ps_class_id > 0){
			$param = array (
				'ps_school_year_id' => $ps_school_year_id,
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_myclass_id' => $ps_class_id,
				'is_activated' => PreSchool::ACTIVE );
		}else{
			$param = array (
				'ps_school_year_id' => $ps_school_year_id,
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'is_activated' => PreSchool::ACTIVE );
		}
		
		$list_class = Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param )->execute ();
		
		$exportFile->loadTemplate($path_template_file);
		
		foreach ( $list_class as $class ) {
			
			$title_xls = $class->getTitle();
			
			$list_menu = Doctrine::getTable ( 'FeatureBranchTimes' )->getListFBTWeek ( $form_week_start, $form_week_end, $ps_customer_id, $ps_workplace_id, $class->getId(), null );
			
			$list_hour = Doctrine::getTable ( 'FeatureBranchTimes' )->getListFBTHourWorkWeek ( $form_week_start, $form_week_end, $ps_customer_id, $ps_workplace_id, $class->getId(), null );
			
			/**
			 * Clone template
			 */
			
			$exportFile->createNewSheet ();
			
			$exportFile->setDataExportStatisticInfoExportA($school_name, $title_info,$title_xls);
			
			$exportFile->setDataExportThoiKhoaBieuTuan($week_list, $list_menu,$list_hour);
			
		}
		
		$exportFile->removeSheet ();
		
		$exportFile->saveAsFile("ThoiKhoaBieu".".xls");
		
	}
	
	// Lay danh sach hoat dong cua tuan
	public function executeFeatureBranchTimesWeek(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$this->formFilter = new sfFormFilter ();

			$value_student_filter = $request->getParameter ( 'menus_filter' );

			$school_year_id = $value_student_filter ['school_year_id'];
			$ps_customer_id = $value_student_filter ['ps_customer_id'];
			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];
			$ps_class_id = $value_student_filter ['ps_class_id'];
			$date_at = $value_student_filter ['date_at'];

			// Lay thong tin tuan cua nam

			// $this->formFilter->setWidget('week_start', new sfWidgetFormInputHidden());
			// $this->formFilter->setWidget('week_end', new sfWidgetFormInputHidden());
			// $this->formFilter->setWidget('week_list', new sfWidgetFormInputHidden());

			$weeks = PsDateTime::getWeeksOfYear ( date ( 'Y', strtotime ( $date_at ) ) );

			$ps_week = date ( "W", strtotime ( $date_at ) );

			$weeks_form = $weeks [$ps_week - 1];

			$form_week_start = $weeks_form ['week_start'];

			$form_week_end = $weeks_form ['week_end'];

			$this->week_list = $weeks_form ['week_list'];

			$this->formFilter->getWidgetSchema ()
				->setNameFormat ( 'menus_filter[%s]' );

			$this->list_menu = Doctrine::getTable ( 'FeatureBranchTimes' )->getListFBTWeek ( $form_week_start, $form_week_end, $ps_customer_id, $ps_workplace_id, $ps_class_id );

			return $this->renderPartial ( 'psFeatureBranchTimes/ajax_table_menu', array (
					'list_menu' => $this->list_menu,
					'week_start' => $form_week_start,
					'week_end' => $form_week_end,
					'week_list' => $this->week_list,
					'width_th' => (100 / (count ( $this->week_list ) + 1)),
					'formFilter' => $this->formFilter ) );
		} else {
			exit ( 0 );
		}
	}

	protected function processfilterFeatureBranchTimesWeek(sfWebRequest $request, $ps_feature_branch_times = null) {

		$formFilter = new sfFormFilter ();

		$formFilter->setWidget ( 'school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => '-Select school year-' ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ) ) ) );

		$formFilter->setValidator ( 'school_year_id', new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) ) );

		$school_year_id = $request->getParameter ( 'school_year_id' );

		if ($ps_feature_branch_times) {

			$ps_customer_id = $ps_feature_branch_times->getFeatureBranch ()
				->getFeature ()
				->getPsCustomerId ();

			$school_year_id = $ps_feature_branch_times->getFeatureBranch ()
				->getSchoolYearId ();
		}

		$formFilter->setWidget ( 'date_at', new psWidgetFormFilterInputDate () );

		if ($school_year_id) {

			$school_year = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $school_year_id );
		}

		if (myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' )) {

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
		}

		if ($ps_customer_id <= 0)
			$ps_customer_id = $request->getParameter ( 'ps_customer_id' );

		$ps_workplace_id = $request->getParameter ( 'ps_workplace_id' );

		if ($ps_customer_id > 0) {

			$formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkplaces',
					'query' => Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px; width:auto;",
					'data-placeholder' => _ ( '-Select workplace-' ),
					'required' => false ) ) );

			$formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkplaces',
					'required' => false ) ) );
		} else {
			$formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2' ) ) );
		}

		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_school_year_id' => $school_year_id );

		if ($ps_workplace_id > 0) {

			$formFilter->setWidget ( 'ps_class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) ) ) );
		} else {
			$formFilter->setWidget ( 'ps_class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) ) ) );
		}

		$formFilter->setValidator ( 'ps_class_id', new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'MyClass',
				'column' => 'id' ) ) );

		$school_year_id = $request->getParameter ( 'school_year_id' );
		$ps_customer_id = $request->getParameter ( 'ps_customer_id' );
		$ps_workplace_id = $request->getParameter ( 'ps_workplace_id' );
		$date_at = $request->getParameter ( 'date_at' );

		if ($request->isMethod ( 'post' )) {

			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'menus_filter' );

			$school_year_id = $value_student_filter ['school_year_id'];
			$ps_customer_id = $value_student_filter ['ps_customer_id'];
			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];
			$ps_class_id = $value_student_filter ['ps_class_id'];
			$date_at = $value_student_filter ['date_at'];
		}
		if (empty ( $date_at )) {
			// Lay thong tin tuan cua nam
			$ps_year = date ( 'Y', strtotime ( $date_at ) );
			$weeks = PsDateTime::getIndexWeekOfYear ( date ( 'Y-m-d', strtotime ( $date_at ) ) );

			// Lay thong tin tuan cua nam
			$weeks = PsDateTime::getWeeksOfYear ( $ps_year );

			// Get week in form
			$week_start = null;
			$week_end = null;
			$week_list = array ();

			if (isset ( $weeks [$ps_week - 1] )) {

				$weeks_form = $weeks [$ps_week - 1];

				$week_start = $weeks_form ['week_start'];

				$week_end = $weeks_form ['week_end'];

				$week_list = $weeks_form ['week_list'];
			}
		}
		$formFilter->setDefault ( 'school_year_id', $school_year_id );
		$formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
		$formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		$formFilter->setDefault ( 'ps_class_id', $ps_class_id );
		$formFilter->setDefault ( 'date_at', $date_at );

		$formFilter->getWidgetSchema ()
			->setNameFormat ( 'menus_filter[%s]' );

		return array (
				'formFilter' => $formFilter,
				'form_week_start' => $week_start,
				'form_week_end' => $week_end,
				'form_week_list' => $week_list );
	}

	public function executeWarning(sfWebRequest $request) {

		$this->filter_value = $this->getFilters ();

		$this->filter_value ['ps_customer_id'] = (isset ( $this->filter_value ['ps_customer_id'] )) ? $this->filter_value ['ps_customer_id'] : '';

		$this->filter_value ['school_year_id'] = (isset ( $this->filter_value ['school_year_id'] )) ? $this->filter_value ['school_year_id'] : '';

		$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

		if ($this->filter_value ['ps_customer_id'] <= 0) {

			$this->getUser ()
				->setFlash ( 'warning', $this->getContext ()
				->getI18N ()
				->__ ( 'Please select School to filter the data.' ), false );
		} elseif ($this->filter_value ['school_year_id'] <= 0) {
			$this->getUser ()
				->setFlash ( 'warning', $this->getContext ()
				->getI18N ()
				->__ ( 'Please select school year to to filter the data.' ), false );
		}
	}

	public function executeIndex(sfWebRequest $request) {
		
		// sorting
		if ($request->getParameter ( 'sort' ) && $this->isValidSortColumn ( $request->getParameter ( 'sort' ) )) {
			$this->setSort ( array (
					$request->getParameter ( 'sort' ),
					$request->getParameter ( 'sort_type' ) ) );
		}

		$ps_feature_branch_id = $request->getParameter ( 'fbid' );

		if ($ps_feature_branch_id > 0) {

			$ps_feature_branch = Doctrine::getTable ( 'FeatureBranch' )->findOneById ( $ps_feature_branch_id );

			$this->forward404Unless ( $ps_feature_branch, sprintf ( 'Object does not exist.' ) );

			$ps_feature = $ps_feature_branch->getFeature ();

			$this->forward404Unless ( myUser::checkAccessObject ( $ps_feature, 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

			$this->filter_value = array ();
			$filter_value = array ();

			$filter_value ['ps_customer_id'] = $ps_feature->getPsCustomerId ();
			$filter_value ['school_year_id'] = $ps_feature_branch->getSchoolYearId ();
			$filter_value ['ps_obj_group_id'] = $ps_feature_branch->getPsObjGroupId ();
			$filter_value ['ps_workplace_id'] = $ps_feature_branch->getPsWorkplaceId ();

			$filter_value ['feature_id'] = $ps_feature->getId ();
			$filter_value ['ps_feature_branch_id'] = $ps_feature_branch_id;

			$this->filter_value = $filter_value;
			$this->setFilters ( $filter_value );
		} else {

			$this->filter_value = $this->getFilters ();

			$this->filter_value ['ps_customer_id'] = (isset ( $this->filter_value ['ps_customer_id'] )) ? $this->filter_value ['ps_customer_id'] : '';

			$this->filter_value ['school_year_id'] = (isset ( $this->filter_value ['school_year_id'] )) ? $this->filter_value ['school_year_id'] : '';
		}

		$this->filters = $this->configuration->getFilterForm ( $this->filter_value );

		if ($this->filter_value ['ps_customer_id'] <= 0) {

			$this->getUser ()
				->setFlash ( 'warning', $this->getContext ()
				->getI18N ()
				->__ ( 'Please select School to filter the data.' ), false );

			$this->redirect ( '@ps_feature_branch_times_warning' );
		} elseif ($this->filter_value ['school_year_id'] <= 0) {

			$this->getUser ()
				->setFlash ( 'warning', $this->getContext ()
				->getI18N ()
				->__ ( 'Please select school year to to filter the data.' ), false );

			$this->redirect ( '@ps_feature_branch_times_warning' );
		} else {

			// pager
			if ($request->getParameter ( 'page' )) {
				$this->setPage ( $request->getParameter ( 'page' ) );
			}

			$this->pager = $this->getPager ();

			$this->sort = $this->getSort ();
		}
	}

	public function executeNew(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$ps_feature_branch_id = $request->getParameter ( 'fbid' );

			if ($ps_feature_branch_id > 0) {

				$ps_feature_branch = Doctrine::getTable ( 'FeatureBranch' )->findOneById ( $ps_feature_branch_id );

				$this->forward404Unless ( $ps_feature_branch, sprintf ( 'Object does not exist.' ) );

				$ps_feature = $ps_feature_branch->getFeature ();

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_feature, 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$feature_branch_times = new FeatureBranchTimes ();

				$feature_branch_times->setFeatureBranch ( $ps_feature_branch );

				$this->form = $this->configuration->getForm ( $feature_branch_times );

				$this->feature_branch_times = $this->form->getObject ();

				// Lay danh sach lop theo: truong, nam hoc, co so dao tao, nhom tre
				$ps_customer_id = $ps_feature->getPsCustomerId ();

				$school_year_id = $ps_feature_branch->getSchoolYearId ();

				// Co so dao tao
				$ps_workplace_id = $ps_feature_branch->getPsWorkplaceId ();

				// Nhom tre
				$ps_obj_group_id = $ps_feature_branch->getPsObjGroupId ();

				$param_for_myclass = array (
						'ps_customer_id' => $ps_customer_id,
						'ps_school_year_id' => $school_year_id,
						'ps_workplace_id' => $ps_workplace_id,
						'ps_obj_group_id' => $ps_obj_group_id );

				$list_myclass = $this->feature_branch_times->getMyClassForFeatureBranchTimes ( $param_for_myclass );

				return $this->renderPartial ( 'psFeatureBranchTimes/formSuccess', array (
						'feature_branch_times' => $this->feature_branch_times,
						'form' => $this->form,
						'configuration' => $this->configuration,
						'helper' => $this->helper,
						'ps_feature_branch' => $ps_feature_branch,
						'list_myclass' => $list_myclass ) );
			} else {
				exit ( 0 );
			}
		} else {
			exit ( 0 );
		}
	}

	public function executeCreate(sfWebRequest $request) {

		$formValues = $request->getParameter ( 'psactivitie' );

		$ps_feature_branch_id = isset ( $formValues ['ps_feature_branch_id'] ) ? $formValues ['ps_feature_branch_id'] : '';

		if ($ps_feature_branch_id > 0) {

			$ps_feature_branch = Doctrine::getTable ( 'FeatureBranch' )->findOneById ( $ps_feature_branch_id );

			$this->forward404Unless ( $ps_feature_branch, sprintf ( 'Object does not exist.' ) );

			$ps_feature = $ps_feature_branch->getFeature ();

			$this->forward404Unless ( myUser::checkAccessObject ( $ps_feature, 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		}

		$feature_branch_times = new FeatureBranchTimes ();

		$feature_branch_times->setFeatureBranch ( $ps_feature_branch );

		$this->form = $this->configuration->getForm ( $feature_branch_times );

		$this->feature_branch_times = $this->form->getObject ();

		$this->processForm ( $request, $this->form );

		exit ( 0 );
		// $this->setTemplate('formSuccess');
	}

	public function executeEdit(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$this->feature_branch_times = $this->getRoute ()
				->getObject ();

			$ps_feature_branch = $this->feature_branch_times->getFeatureBranch ();

			$ps_feature = $ps_feature_branch->getFeature ();

			$this->forward404Unless ( myUser::checkAccessObject ( $ps_feature, 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

			$this->form = $this->configuration->getForm ( $this->feature_branch_times );

			// Lay danh sach lop theo: truong, nam hoc, co so dao tao, nhom tre
			$ps_customer_id = $ps_feature->getPsCustomerId ();

			$school_year_id = $ps_feature_branch->getSchoolYearId ();

			// Co so dao tao
			$ps_workplace_id = $ps_feature_branch->getPsWorkplaceId ();

			// Nhom tre
			$ps_obj_group_id = $ps_feature_branch->getPsObjGroupId ();

			$param_for_myclass = array (
					'ps_customer_id' => $ps_customer_id,
					'ps_school_year_id' => $school_year_id,
					'ps_workplace_id' => $ps_workplace_id,
					'ps_obj_group_id' => $ps_obj_group_id,
					'is_activated' => PreSchool::ACTIVE
			);

			$list_myclass = $this->feature_branch_times->getMyClassForFeatureBranchTimes ( $param_for_myclass );

			return $this->renderPartial ( 'psFeatureBranchTimes/formSuccess', array (
					'feature_branch_times' => $this->feature_branch_times,
					'form' => $this->form,
					'configuration' => $this->configuration,
					'helper' => $this->helper,
					'ps_feature_branch' => $this->feature_branch_times->getFeatureBranch (),
					'list_myclass' => $list_myclass ) );
		} else {
			exit ( 0 );
		}
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->feature_branch_times = $this->getRoute ()
			->getObject ();
		$this->form = $this->configuration->getForm ( $this->feature_branch_times );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );

		$formValues = $request->getParameter ( $form->getName () );

		$class_apply = $request->getParameter ( 'class_apply' );

		$psactivitie_my_class = isset ( $class_apply ['my_class'] ) ? $class_apply ['my_class'] : null;
		/*
		 * foreach ($psactivitie_my_class as $my_class) {
		 * print_r($my_class);
		 * }
		 * die;
		 */
		$ps_feature_branch_id = isset ( $formValues ['ps_feature_branch_id'] ) ? $formValues ['ps_feature_branch_id'] : '';

		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'Time apply was created successfully.' : 'Time apply was updated successfully.';

			$is_new = $form->getObject ()
				->isNew ();

			$conn = Doctrine_Manager::connection ();

			try {

				$conn->beginTransaction ();

				$feature_branch_times = $form->save ();

				$feature_branch_times_id = $feature_branch_times->getId ();

				if ($is_new) {
					// Them moi FeatureBranchTimeMyClass
					foreach ( $psactivitie_my_class as $my_class ) {

						$note = PreString::trim ( $my_class ['note'] );

						$ps_class_room = PreString::trim ( $my_class ['ps_class_room'] );

						if ($my_class ['ids'] > 0 && ($note <= 2000) && ($ps_class_room <= 300)) {

							$new_psFeatureBranchTimeMyClass = new PsFeatureBranchTimeMyClass ();

							$new_psFeatureBranchTimeMyClass->setPsFeatureBranchTimeId ( $feature_branch_times_id );
							$new_psFeatureBranchTimeMyClass->setPsMyclassId ( $my_class ['ids'] );
							$new_psFeatureBranchTimeMyClass->setNote ( PreString::trim ( $my_class ['note'] ) );
							$new_psFeatureBranchTimeMyClass->setPsClassRoom ( PreString::trim ( $my_class ['ps_class_room'] ) );
							$new_psFeatureBranchTimeMyClass->setUserCreatedId ( sfContext::getInstance ()->getUser ()
								->getGuardUser ()
								->getId () );
							$new_psFeatureBranchTimeMyClass->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
								->getGuardUser ()
								->getId () );
							$new_psFeatureBranchTimeMyClass->save ();
						}
					}
				} else {

					// Xoa toan bo ban ghi cu
					Doctrine::getTable ( 'PsFeatureBranchTimeMyClass' )->createQuery ()
						->delete ()
						->where ( 'ps_feature_branch_time_id = ?', $feature_branch_times_id )
						->execute ();

					foreach ( $psactivitie_my_class as $my_class ) {

						if ($my_class ['ids'] > 0) {

							// print_r($psactivitie_my_class);die;

							$new_psFeatureBranchTimeMyClass = new PsFeatureBranchTimeMyClass ();
							$new_psFeatureBranchTimeMyClass->setPsFeatureBranchTimeId ( $feature_branch_times_id );
							$new_psFeatureBranchTimeMyClass->setPsMyclassId ( $my_class ['ids'] );
							$new_psFeatureBranchTimeMyClass->setNote ( $my_class ['note'] );
							$new_psFeatureBranchTimeMyClass->setPsClassRoom ( $my_class ['ps_class_room'] );
							$new_psFeatureBranchTimeMyClass->setUserCreatedId ( sfContext::getInstance ()->getUser ()
								->getGuardUser ()
								->getId () );
							$new_psFeatureBranchTimeMyClass->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
								->getGuardUser ()
								->getId () );
							$new_psFeatureBranchTimeMyClass->save ();
						}
					}
				}

				$list_myClass = Doctrine::getTable ( 'PsFeatureBranchTimeMyClass' )->getBasicInfoByPsFeatureBranchTimeId ( $feature_branch_times_id );

				$note_class_name = array ();

				foreach ( $list_myClass as $my_class ) {
					array_push ( $note_class_name, $my_class->getClassName () );
				}

				if (count ( $note_class_name ) > 0)
					$note_class_name_text = implode ( ", ", $note_class_name );
				else
					$note_class_name_text = '';

				$featureBranchTimes = Doctrine::getTable ( 'FeatureBranchTimes' )->findOneById ( $feature_branch_times_id );

				$featureBranchTimes->setNoteClassName ( $note_class_name_text );

				$featureBranchTimes->save ();

				$conn->commit ();
			} catch ( Doctrine_Validator_Exception $e ) {

				$conn->rollback ();

				$errorStack = $form->getObject ()
					->getErrorStack ();

				$message = get_class ( $form->getObject () ) . ' has ' . count ( $errorStack ) . " field" . (count ( $errorStack ) > 1 ? 's' : null) . " with validation errors: ";
				foreach ( $errorStack as $field => $errors ) {
					$message .= "$field (" . implode ( ", ", $errors ) . "), ";
				}
				$message = trim ( $message, ', ' );

				$this->getUser ()
					->setFlash ( 'error', $message );

				$this->redirect ( '@ps_feature_branch_edit?id=' . $ps_feature_branch_id . '#pstab_2' );
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $feature_branch_times ) ) );

			$this->getUser ()
				->setFlash ( 'notice', $notice );

			$this->redirect ( '@ps_feature_branch_edit?id=' . $ps_feature_branch_id . '#pstab_2' );
		} else {

			$this->getUser ()
				->setFlash ( 'error', 'Time apply has not been saved due to some errors.' );

			$this->redirect ( '@ps_feature_branch_edit?id=' . $ps_feature_branch_id . '#pstab_2' );
		}
	}

	public function executeDelete(sfWebRequest $request) {
		
		$request->checkCSRFProtection ();
		
		$this->feature_branch_times = $this->getRoute ()
		->getObject ();
		
		$ps_feature_branch_time_id = $this->feature_branch_times->getId ();
		
		$ps_feature_branch_id = $this->feature_branch_times->getPsFeatureBranchId ();
		
		$ps_feature = $this->feature_branch_times->getFeatureBranch ()
		->getFeature ();
		
		$this->forward404Unless ( myUser::checkAccessObject ( $ps_feature, 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		
		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
				->getObject () ) ) );
		
		// Xoa trong PsFeatureBranchTimeMyClass
		$records = Doctrine_Query::create ()->from ( 'PsFeatureBranchTimeMyClass' )
		->where ( 'ps_feature_branch_time_id = ?', $this->feature_branch_times->getId () )
		->execute ();
		
		foreach ( $records as $record ) {
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );
			
			$record->delete ();
		}
		
		if ($this->getRoute ()
				->getObject ()
				->delete ()) {
					$this->getUser ()
					->setFlash ( 'notice', 'Time apply was deleted successfully.' );
				}
				
				$this->redirect ( '@ps_feature_branch_edit?id=' . $ps_feature_branch_id . '#pstab_2' );
	}
	
	// Xuat bieu mau import lịch hoạt động mới
	public function executeExport(sfWebRequest $request)
	{
		$this->formFilter = new sfFormFilter();
		
		$ps_customer_id = null;
		
		$ps_workplace_id = null;
		
		$class_id = null;
		
		$ps_school_year_id = null;
		
		$export_filter = $request->getParameter ( 'export_filter' );
		
		if ($request->isMethod('post')) {
			
			$value_student_filter = $request->getParameter('export_filter');
			
			$ps_customer_id = $value_student_filter['ps_customer_id'];
			
			$ps_workplace_id = $value_student_filter['ps_workplace_id'];
			
			$ps_school_year_id = $value_student_filter['ps_school_year_id'];
			
			$ps_month = $value_student_filter['ps_month'];
			
			$from_date = $value_student_filter['from_date'];
			
			$to_date = $value_student_filter['to_date'];
			
			$this->exportKeHoachGiaoDuc($ps_school_year_id, $ps_customer_id,$ps_workplace_id,$ps_month,$from_date,$to_date);
			
		}
		
		$this->ps_month = isset($value_student_filter['ps_month']) ? $value_student_filter['ps_month'] : date("m-Y");
		
		// Lay nam hoc hien tai
		if ($ps_school_year_id == '') {
			$schoolYearsDefault = Doctrine::getTable('PsSchoolYear')->findOneBy('is_default', PreSchool::ACTIVE);
		} else {
			$schoolYearsDefault = Doctrine::getTable('PsSchoolYear')->findOneBy('id', $ps_school_year_id);
		}
		
		$yearsDefaultStart = date("Y-m", strtotime($schoolYearsDefault->getFromDate()));
		
		$yearsDefaultEnd = date("Y-m", strtotime($schoolYearsDefault->getToDate()));
		
		$this->formFilter->setWidget('ps_month', new sfWidgetFormChoice(array(
				'choices' => array(
						'' => _('-Select month-')
				) + PsDateTime::psRangeMonthYear($yearsDefaultStart, $yearsDefaultEnd)
		), array(
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _('-Select month-'),
				'rel' => 'tooltip',
				'data-original-title' => _('Select month')
		)));
		
		// Lay thang hien tai
		
		$this->number_day = PsDateTime::psNumberDaysOfMonth($this->ps_month);
		
		$this->formFilter->setDefault('ps_month', $this->ps_month);
		
		$date_month = range(1,$this->number_day['number_day_month']);
		
		$this->formFilter->setWidget ( 'from_date', new sfWidgetFormChoice ( array (
				'choices' => array( 
						'' => _('-Start date-') ) + array_combine ( $date_month, $date_month ) ), array (
						'class' => 'select2',
						'style' => "min-width:120px; width:auto;",
						'data-placeholder' => _ ( '-Start date-' ) ) ) );
		
		$this->formFilter->setValidator ( 'from_date', new sfValidatorPass ( array (
				'required' => true ) ) );
		
		$this->formFilter->setWidget ( 'to_date', new sfWidgetFormChoice ( array (
				'choices' => array( '' => _('-Stop date-') ) + array_combine ( $date_month, $date_month ) ), array (
						'class' => 'select2',
						'style' => "min-width:120px; width:auto;",
						'data-placeholder' => _ ( '-Stop date-' ) ) ) );
		
		$this->formFilter->setValidator ( 'to_date', new sfValidatorPass ( array (
				'required' => true ) ) );
		
		
		if (! myUser::credentialPsCustomers('PS_FEE_RECEIPT_NOTICATION_FILTER_SCHOOL')) {
			
			$this->ps_customer_id = myUser::getPscustomerID();
			
			$this->formFilter->setWidget('ps_customer_id', new sfWidgetFormInputHidden());
			
			$this->formFilter->setValidator('ps_customer_id', new sfValidatorInteger(array(
					'required' => true
			)));
		} else {
			
			$this->formFilter->setWidget('ps_customer_id', new sfWidgetFormDoctrineChoice(array(
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(PreSchool::CUSTOMER_ACTIVATED),
					'add_empty' => _('-All school-')
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => true,
					'data-placeholder' => _('-All school-')
			)));
			
			$this->formFilter->setValidator('ps_customer_id', new sfValidatorDoctrineChoice(array(
					'model' => 'PsCustomer',
					'required' => true
			)));
		}
		
		if($this->ps_customer_id ==''){
			$this->ps_customer_id = myUser::getPscustomerID();
			$this->formFilter->setDefault('ps_customer_id' , $this->ps_customer_id);
		}
		$this->formFilter->setDefault('ps_customer_id', $this->ps_customer_id);
		
		$this->formFilter->setDefault('ps_workplace_id', $this->ps_workplace_id);
		
		$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )->getId();
		
		$this->formFilter->setWidget('ps_school_year_id', new sfWidgetFormDoctrineChoice(array(
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable('PsSchoolYear')->setSqlPsSchoolYears(),
				'add_empty' => true
		), array(
				'class' => 'select2',
				'style' => "width:100%;min-width:150px;",
				'data-placeholder' => _('-Select school year-'),
				'required' => true
		)));
		
		$this->formFilter->setValidator('ps_school_year_id', new sfValidatorDoctrineChoice(array(
				'model' => 'PsSchoolYear',
				'column' => 'id',
				'required' => true
		)));
		
		if ($this->ps_customer_id > 0) {
			
			$this->formFilter->setWidget('ps_workplace_id', new sfWidgetFormDoctrineChoice(array(
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable('PsWorkPlaces')->setSQLByCustomerId('id, title', $this->ps_customer_id, PreSchool::ACTIVE),
					'add_empty' => _('-Select workplace-')
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => true,
					'data-placeholder' => _('-Select workplace-')
			)));
			
			$this->formFilter->setValidator('ps_workplace_id', new sfValidatorDoctrineChoice(array(
					'model' => 'PsWorkPlaces',
					'required' => true
			)));
			
		} else {
			$this->formFilter->setWidget('ps_workplace_id', new sfWidgetFormChoice(array(
					'choices' => array(
							'' => _('-Select workplace-')
					)
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _('-Select workplace-')
			)));
			
			$this->formFilter->setValidator('ps_workplace_id', new sfValidatorPass());
			
		}
		
		$this->formFilter->setDefault('ps_school_year_id', $this->ps_school_year_id);
		
		$this->formFilter->setDefault('ps_workplace_id', $this->ps_workplace_id);
		
		$this->formFilter->setDefault('ps_month', $this->ps_month);
		
		$this->formFilter->setDefault('to_date', $this->to_date);
		
		$this->formFilter->setDefault('from_date', $this->from_date);
		
		$this->formFilter->getWidgetSchema()->setNameFormat('export_filter[%s]');
		
	}
	
	protected function exportKeHoachGiaoDuc($ps_school_year_id, $ps_customer_id,$ps_workplace_id,$ps_month,$from_date,$to_date)
	{
		
		$exportFile = new ExportStudentLogtimesReportHelper($this);
		
		$file_template_pb = 'bm_kehoachgiaoduc.xls';
		
		$path_template_file = sfConfig::get('sf_web_dir') . '/uploads/export_data/' . $file_template_pb;
		
		$school_name = Doctrine::getTable('Pscustomer')->findOneBy('id', $ps_customer_id);
		
		$param_array = array(
				'ps_customer_id' => $ps_customer_id, 
				'ps_workplace_id' => $ps_workplace_id, 
				'ps_school_year_id' => $ps_school_year_id, 
				'is_activated' => PreSchool::ACTIVE);
		
		$param_feature = array(
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_school_year_id' => $ps_school_year_id,
				'is_activated' => PreSchool::ACTIVE,
				'is_continuity'=> PreSchool::NOT_ACTIVE
		);
		
		$number_day = PsDateTime::psNumberDaysOfMonth($ps_month);
		
		if($from_date == ''){$from_date = 1;}
		if($to_date ==''){$to_date = $number_day['number_day_month'];}
		
		$title_info = $this->getContext ()->getI18N ()->__ ( 'Ke Hoach Giao Duc Tu Ngay %value1% den %value2%',array('%value1%'=>$from_date."-".$ps_month,'%value2%'=> $to_date."-".$ps_month));
		
		$list_class = Doctrine::getTable ( 'MyClass' ) -> setClassByParams ( $param_array )->execute();
		
		$list_feature_branch = Doctrine::getTable ( 'FeatureBranch' )->getListFBexport ($ps_school_year_id, $ps_customer_id,$ps_workplace_id );
		
		$title_xls = "KHGD";
		
		$exportFile->loadTemplate($path_template_file);
		
		$exportFile->setDataExportTieuDeKeHoachGiaoDuc($school_name, $title_info,$title_xls);
		
		$exportFile->setDataExportDuLieuKeHoachGiaoDuc($list_class,$list_feature_branch,$from_date,$to_date,$ps_month);
		
		//die;
		
		$exportFile->saveAsFile("KeHoachGiaoDuc".".xls");
		
	}
	
	public function executeImport(sfWebRequest $request) {
		
		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = null;
		
		$ps_workplace_id = null;
		
		$ps_school_year_id = null;
		
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {
			
			$this->ps_customer_id = myUser::getPscustomerID ();
			
			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );
			
			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
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
		
		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )
		->getId ();
		
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
		
		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes
		
		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );
		
		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
						'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
						'max_size' => sfContext::getInstance ()->getI18n ()
						->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
								'%value%' => $upload_max_size ) ) ) )
				);
		
		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );
		
		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'import_filter[%s]' );
		
	}
	
	public function executeImportSave(sfWebRequest $request) {
		
		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = null;
		
		$ps_workplace_id = null;
		
		$ps_school_year_id = null;
		
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {
			
			$this->ps_customer_id = myUser::getPscustomerID ();
			
			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );
			
			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
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
		
		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )
		->getId ();
		
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
		
		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes
		
		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );
		
		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
						'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
						'max_size' => sfContext::getInstance ()->getI18n ()
						->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
								'%value%' => $upload_max_size ) ) ) )
				);
		
		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );
		
		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'import_filter[%s]' );
		
		/**
		 * * Import file excel **
		 */
		
		$import_filter_form = $request->getParameter ( 'import_filter' );
		
		$this->formFilter->bind ( $request->getParameter ( 'import_filter' ), $request->getFiles ( 'import_filter' ) );
		// id nam hoc
		$ps_school_year_id = $this->formFilter->getValue ( 'ps_school_year_id' );
		// id truong hoc
		$ps_customer_id = $this->formFilter->getValue ( 'ps_customer_id' );
		// id co so
		$ps_workplace_id = $this->formFilter->getValue ( 'ps_workplace_id' );
		
		$array_class = $array_feature = array();
		
		$param_array = array(
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_school_year_id' => $ps_school_year_id,
				'is_activated' => PreSchool::ACTIVE);
		
		$list_class = Doctrine::getTable ( 'MyClass' ) -> setClassByParams ( $param_array )->execute();
		
		$list_feature_branch = Doctrine::getTable ( 'FeatureBranch' )->getListFBexport ($ps_school_year_id, $ps_customer_id,$ps_workplace_id );
		
		foreach ($list_class as $class){
			array_push($array_class, $class->getId());
		}
		
		foreach ($list_feature_branch as $feature_branch){
			array_push($array_feature, $feature_branch->getId());
		}
		
		$conn = Doctrine_Manager::connection();
		
		try {
			
			$conn->beginTransaction();
			
			if ($this->formFilter->isValid()) {
				
				$user_id = myUser::getUserId();
				
				$file_classify = $this->getContext()->getI18N()->__('Import feature branch');
				
				$file = $this->formFilter->getValue('ps_file');
				
				$filename = time().$file->getOriginalName();
				
				$file_link = 'FeatureBranch' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );
				
				$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';
				
				$file->save($path_file . $filename);
				
				$objPHPExcel = PHPExcel_IOFactory::load($path_file . $filename);
				
				$provinceSheet = $objPHPExcel->setActiveSheetIndex(0); // Set sheet sẽ được đọc dữ liệu
				
				$highestRow    = $provinceSheet->getHighestRow(); // Lấy số hàng lớn nhất trong sheet
				
				$highestColumn = $provinceSheet->getHighestColumn(); // Lấy số cột lớn nhất trong sheet
				
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				
				$array_class_name = $array_class_id = $array_error = $array_id_class_error = $array_id_feature_error = array();
				$false = $check = $true = 0;
				$is_saturday = $is_sunday = 0;
				$feature_date = '1970-01-01';
				$error_time = $feature_date2 = '';
				// lay ra id lop
				for ($k = 2 ;$k < $highestColumnIndex; $k++ ){
					
					$start1 = 5;
					$class_name = $provinceSheet->getCellByColumnAndRow($k, $start1)->getCalculatedValue();
					$start2 = 6;
					$id_class = PreString::trim ($provinceSheet->getCellByColumnAndRow($k, $start2)->getCalculatedValue());
					//echo 'class_id'.$id_class.'<br/>';
					if($id_class !=''){
						if(in_array($id_class, $array_class)){
							array_push($array_class_id, $id_class);
							array_push($array_class_name, $class_name);
						}else{
							$false = 1;
							array_push($array_id_class_error, $id_class);
						}
					}
					$k++;
				}
				//print_r($array_class_id);
				// Neu tat ca cac lop thuoc trong co so
				if(count($array_id_class_error) <= 0){
					
					for ($row = 7; $row <= $highestRow; $row++) {
						
						$row_ct = $row+1;
						
						// Ngày diễn ra hoạt động
						$feature_get_date = PreString::trim ($provinceSheet->getCellByColumnAndRow(0, $row)->getCalculatedValue());
						
						// Neu de dinh dang là date
						if(is_numeric ($feature_get_date)){
							
							$feature_date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($feature_get_date));
							
						}else{ // Neu de dinh dang la text
							
							$feature_date = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $feature_get_date ) ) ); // chuyển định dạng
							
						}
						
						// Chuyen du lieu
						if($feature_get_date != '' && $feature_date !='1970-01-01'){
							
							$feature_date2 = $feature_date;
							
							$title_day = date('D', strtotime($feature_date));
							
							if($title_day == 'Sat'){
								$is_saturday = 1;
							}elseif($title_day == 'Sun'){
								$is_sunday = 1;
							}
							
						}
						
						if($feature_date == '1970-01-01'){
							$feature_date = $feature_date2;
						}
						
						/*
						$feature_date = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $feature_get_date ) ) );
						
						// Chuyen du lieu
						if($feature_get_date != '' && $feature_date !='1970-01-01'){
							
							$feature_date2 = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $feature_get_date ) ) );
							
							$title_day = date('D', strtotime($feature_date));
							
							if($title_day == 'Sat'){
								$is_saturday = 1;
							}elseif($title_day == 'Sun'){
								$is_sunday = 1;
							}
							
						}
						
						if($feature_date == '1970-01-01'){
							$feature_date = $feature_date2;
						}
						*/
						// Lay ra id hoat dong
						$feature_id = PreString::trim ($provinceSheet->getCellByColumnAndRow(1, $row)->getCalculatedValue());
						if($feature_date != '' && $feature_id !=''){
							// Kiem tra xem hoat dong nay co thuoc truong hay khong?
							if(in_array($feature_id, $array_feature)){
								
								for ($k = 2 ;$k < $highestColumnIndex; $k++ ){
									
									$key_class = ($k-2)/2;
	
									$start_time = PHPExcel_Style_NumberFormat::toFormattedString ( $provinceSheet->getCellByColumnAndRow($k, $row)->getCalculatedValue(), 'hh:mm:ss' );
									$k++;
									$stop_time = PHPExcel_Style_NumberFormat::toFormattedString ( $provinceSheet->getCellByColumnAndRow($k, $row)->getCalculatedValue(), 'hh:mm:ss' );
									
									if($start_time !='' && $stop_time !=''){
										// Kiem tra dinh dang thoi gian
										$check_time_st = $this->verifyTime ( $start_time );
										$check_time_sn = $this->verifyTime ( $stop_time );
										
										$content = $provinceSheet->getCellByColumnAndRow($k-1, $row_ct)->getCalculatedValue();
										
										if($check_time_st == true && $check_time_sn == true){
											
											$true ++;
											
											$class_id = $array_class_id[$key_class];
											$class_name = $array_class_name[$key_class];
											
											$feature_branch_time = new FeatureBranchTimes ();
											
											$feature_branch_time->setPsFeatureBranchId ( $feature_id );
											
											$feature_branch_time->setPsClassRoomId ( null );
											$feature_branch_time->setStartAt ( $feature_date );
											$feature_branch_time->setEndAt ( $feature_date );
											$feature_branch_time->setStartTime ( $start_time );
											$feature_branch_time->setEndTime ( $stop_time );
											$feature_branch_time->setIsSaturday ( $is_saturday );
											$feature_branch_time->setIsSunday ( $is_sunday );
											$feature_branch_time->setNote ( $content );
											$feature_branch_time->setNoteClassName ( $class_name );
											
											$feature_branch_time->setUserCreatedId ( $user_id );
											$feature_branch_time->setUserUpdatedId ( $user_id );
											
											$feature_branch_time->save ();
											
											$ps_feature_branch_time_my_class = new PsFeatureBranchTimeMyClass ();
											$ps_feature_branch_time_my_class->setPsFeatureBranchTimeId ( $feature_branch_time->getId() );
											$ps_feature_branch_time_my_class->setPsMyclassId ( $class_id );
											$ps_feature_branch_time_my_class->setNote ( $content );
											$ps_feature_branch_time_my_class->setPsClassRoom ( null );
											$ps_feature_branch_time_my_class->setUserCreatedId ( $user_id );
											$ps_feature_branch_time_my_class->setUserUpdatedId ( $user_id );
											$ps_feature_branch_time_my_class->save ();
											
										}else{
											$false = 1;
											$error_time .= 'Row_'.$row.' Cell_'.$k.'; ';
										}
									}
								}
								
							}else{
								if(is_numeric($feature_id)){
									$false = 1;
								}
								array_push($array_id_feature_error, $feature_id);
							}
						}
						$row++;
					}
				}
				//die;
			}
			
			if($true > 0){
				// luu lich su import file lich hoat dong
				$ps_history_import = new PsHistoryImport ();
				$ps_history_import->setPsCustomerId ( $ps_customer_id );
				$ps_history_import->setPsWorkplaceId ( $ps_workplace_id );
				$ps_history_import->setFileName ( $filename );
				$ps_history_import->setFileLink ( $file_link );
				$ps_history_import->setFileClassify ( $file_classify );
				$ps_history_import->setUserCreatedId ( $user_id );
				
				$ps_history_import->save ();
			}else{
				unlink ( $path_file . $filename );
			}
			
			$conn->commit();
		}catch (Exception $e) {
			unlink ( $path_file . $filename );
			$conn->rollback();
			$error_import = $e->getMessage();
			$this->getUser()->setFlash('error', $error_import);
		}
		
		// Neu xuat hien loi
		if($false == 1){
			if(count($array_id_class_error) > 0){
				$notice_class_error = $this->getContext ()->getI18N ()->__ ( 'ID class not found' ).
				$notice_class_error_line = $this->getContext ()->getI18N ()->__ ( 'ID: %value%', array ('%value%' => implode(' ; ', $array_id_class_error) ) );
				$this->getUser ()->setFlash ( 'notice_class_error', $notice_class_error );
			}
			
			if(count($array_id_feature_error) > 0){
				$notice_feature_error = $this->getContext ()->getI18N ()->__ ( 'ID feature not found' ).
				$notice_feature_error_line = $this->getContext ()->getI18N ()->__ ( 'ID: %value%', array ('%value%' => implode(' ; ', $array_id_feature_error) ) );
				$this->getUser ()->setFlash ( 'notice_feature_error', $notice_feature_error );
			}
			
			if($error_time != ''){
				$notice_time_error = $this->getContext ()->getI18N ()->__ ( 'Error time' ).$error_time;
				$this->getUser ()->setFlash ( 'notice_time_error', $notice_time_error );
			}
		}elseif($true > 0){
			$successfully = $this->getContext()->getI18N()->__( 'Import file feature successfully');
			$this->getUser()->setFlash('notice', $successfully);
		}else{
			$error_feature = $this->getContext()->getI18N()->__( 'Not import data');
			$this->getUser()->setFlash('error', $error_feature);
		}
		
		$this->redirect ( '@ps_feature_branch_times_import' );
		
	}
	
	// Xuat bieu mau import lịch hoạt động mới mau 3
	public function executeExportTem3(sfWebRequest $request)
	{
		$this->formFilter = new sfFormFilter();
		
		$ps_customer_id = null;
		
		$ps_workplace_id = null;
		
		$ps_school_year_id = null;
		
		$this->ps_week = null;
		
		$export_filter = $request->getParameter ( 'export_filter' );
		
		if ($request->isMethod('post')) {
			
			$value_student_filter = $request->getParameter('export_filter');
			
			$ps_customer_id = $value_student_filter['ps_customer_id'];
			
			$ps_workplace_id = $value_student_filter['ps_workplace_id'];
			
			$ps_school_year_id = $value_student_filter['ps_school_year_id'];
			
			$ps_class_id = $value_student_filter['ps_class_id'];
			
			$ps_year = $value_student_filter['ps_year'];
			
			$ps_week = $value_student_filter['ps_week'];
			
			//print_r($ps_class_id); die;
			
			$this->exportKeHoachGiaoDucTem3($ps_school_year_id, $ps_customer_id,$ps_workplace_id,$ps_class_id,$ps_year,$ps_week);
			
		}
		
		$years = range ( date ( 'Y' ) + 1, sfConfig::get ( 'app_begin_year' ) );
		
		$this->formFilter->setWidget ( 'ps_year', new sfWidgetFormChoice ( array (
				'choices' => array_combine ( $years, $years ) ), array (
						'class' => 'select2',
						'style' => "min-width:80px; width:auto;",
						'data-placeholder' => _ ( '-Select year-' ) ) ) );
		
		$this->formFilter->setValidator ( 'ps_year', new sfValidatorPass ( array (
				'required' => true ) ) );
		
		if ($this->ps_year == '') {
			$this->ps_year = date ( 'Y' );
		}
		
		$weeks = PsDateTime::getWeeksOfYear ( $this->ps_year );
		
		$this->formFilter->setWidget ( 'ps_week', new sfWidgetFormChoice ( array (
				'choices' => PsDateTime::getOptionsWeeks ( $weeks ) ), array (
						'class' => 'select2',
						'style' => "min-width:250px;width:100%;",
						'data-placeholder' => _ ( '-Select week-' ) ) ) );
		
		$this->formFilter->setValidator ( 'ps_week', new sfValidatorPass ( array (
				'required' => true ) ) );
		
		// Get week in form
		$form_week_start = null;
		$form_week_end = null;
		$form_week_list = array ();
		
		if ($this->ps_week == '') {
			$this->ps_week = PsDateTime::getIndexWeekOfYear ( date ( 'Y-m-d' ) );
		}
		
		$weeks_form = $weeks [$this->ps_week - 1];
		
		$form_week_start = $weeks_form ['week_start'];
		$this->week_start = $form_week_start;
		
		$form_week_end = $weeks_form ['week_end'];
		$this->week_end = $form_week_end;
		
		$this->week_list = $weeks_form ['week_list'];
		
		if (! myUser::credentialPsCustomers('PS_FEE_RECEIPT_NOTICATION_FILTER_SCHOOL')) {
			
			$this->ps_customer_id = myUser::getPscustomerID();
			
			$this->formFilter->setWidget('ps_customer_id', new sfWidgetFormInputHidden());
			
			$this->formFilter->setValidator('ps_customer_id', new sfValidatorInteger(array(
					'required' => true
			)));
		} else {
			
			$this->formFilter->setWidget('ps_customer_id', new sfWidgetFormDoctrineChoice(array(
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(PreSchool::CUSTOMER_ACTIVATED),
					'add_empty' => _('-All school-')
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => true,
					'data-placeholder' => _('-All school-')
			)));
			
			$this->formFilter->setValidator('ps_customer_id', new sfValidatorDoctrineChoice(array(
					'model' => 'PsCustomer',
					'required' => true
			)));
		}
		
		if($this->ps_customer_id ==''){
			$this->ps_customer_id = myUser::getPscustomerID();
			$member_id = myUser::getUser ()->getMemberId ();
			$this->ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}
		
		$this->formFilter->setDefault('ps_customer_id', $this->ps_customer_id);
		
		$this->formFilter->setDefault('ps_workplace_id', $this->ps_workplace_id);
		
		$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )->getId();
		
		$this->formFilter->setWidget('ps_school_year_id', new sfWidgetFormDoctrineChoice(array(
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable('PsSchoolYear')->setSqlPsSchoolYears(),
				'add_empty' => true
		), array(
				'class' => 'select2',
				'style' => "width:100%;min-width:150px;",
				'data-placeholder' => _('-Select school year-'),
				'required' => true
		)));
		
		$this->formFilter->setValidator('ps_school_year_id', new sfValidatorDoctrineChoice(array(
				'model' => 'PsSchoolYear',
				'column' => 'id',
				'required' => true
		)));
		
		if ($this->ps_customer_id > 0) {
			
			$this->formFilter->setWidget('ps_workplace_id', new sfWidgetFormDoctrineChoice(array(
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable('PsWorkPlaces')->setSQLByCustomerId('id, title', $this->ps_customer_id, PreSchool::ACTIVE),
					'add_empty' => _('-Select workplace-')
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => true,
					'data-placeholder' => _('-Select workplace-')
			)));
			
			$this->formFilter->setValidator('ps_workplace_id', new sfValidatorDoctrineChoice(array(
					'model' => 'PsWorkPlaces',
					'required' => true
			)));
			
		} else {
			$this->formFilter->setWidget('ps_workplace_id', new sfWidgetFormChoice(array(
					'choices' => array(
							'' => _('-Select workplace-')
					)
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _('-Select workplace-')
			)));
			
			$this->formFilter->setValidator('ps_workplace_id', new sfValidatorPass());
			
		}
		
		$param_class = array (
				'ps_school_year_id' => $this->ps_school_year_id,
				'ps_customer_id' => $this->ps_customer_id,
				'ps_workplace_id' => $this->ps_workplace_id,
				'is_activated' => PreSchool::ACTIVE );
		
		$this->formFilter->setWidget ( 'ps_class_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'MyClass',
				'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class ),
				'multiple' => true ), array (
						'class' => 'select2',
						'style' => "min-width:200px;",
						'data-placeholder' => $this->getContext ()
						->getI18N ()
						->__ ( '-Select class-' ) ) ) );
		
		$this->formFilter->setValidator ( 'ps_class_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'MyClass',
				'required' => false ) ) );
		
		
		$this->formFilter->setDefault('ps_school_year_id', $this->ps_school_year_id);
		
		$this->formFilter->setDefault('ps_workplace_id', $this->ps_workplace_id);
		
		$this->formFilter->setDefault('ps_class_id', $this->ps_class_id);
		
		$this->formFilter->setDefault('ps_year', $this->ps_year);
		
		$this->formFilter->setDefault('ps_week', $this->ps_week);
		
		$this->formFilter->getWidgetSchema()->setNameFormat('export_filter[%s]');
		
	}
	
	protected function exportKeHoachGiaoDucTem3($ps_school_year_id, $ps_customer_id,$ps_workplace_id,$ps_class_id,$ps_year,$ps_week)
	{
		
		$exportFile = new ExportStudentLogtimesReportHelper($this);
		
		$file_template_pb = 'bm_kehoachgiaoduc2.xls';
		
		$path_template_file = sfConfig::get('sf_web_dir') . '/uploads/export_data/' . $file_template_pb;
		
		//$school_name = Doctrine::getTable('Pscustomer')->findOneBy('id', $ps_customer_id);
		
		$school_name = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, $ps_class_id, $ps_workplace_id );
		
		$param_array = array(
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_school_year_id' => $ps_school_year_id,
				'ps_myclass_id' => $ps_class_id,
				'is_activated' => PreSchool::ACTIVE);
		
		$list_class = Doctrine::getTable ( 'MyClass' ) -> setClassByParams ( $param_array )->execute();

		$list_date = PsDateTime::getStartAndEndDateOfWeek($ps_week,$ps_year);
		
		$dayofweek = $list_date['week_list'];
		
		$title_info = $this->getContext ()->getI18N ()->__ ( 'Ke Hoach Giao Duc Tu Ngay %value1% den %value2%',array('%value1%'=>date('d-m-Y', strtotime($list_date['week_start'])),'%value2%'=>date('d-m-Y', strtotime($list_date['week_end']))) );
		
		$list_feature_branch = Doctrine::getTable ( 'FeatureBranch' )->getListFBexport ($ps_school_year_id, $ps_customer_id,$ps_workplace_id );
		
		$title_xls = "KHGD";
		
		$exportFile->loadTemplate($path_template_file);
		
		$exportFile->setDataExportTieuDeKeHoachGiaoDuc($school_name, $title_info,$title_xls);
		
		$exportFile->setDataExportDuLieuKeHoachGiaoDucTem3($list_feature_branch,$dayofweek,$list_class);
		
		$exportFile->saveAsFile("KeHoachGiaoDuc".".xls");
		
	}
	
	public function executeImportTem3(sfWebRequest $request) {
		
		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = null;
		
		$ps_workplace_id = null;
		
		$ps_school_year_id = null;
		
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {
			
			$this->ps_customer_id = myUser::getPscustomerID ();
			
			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );
			
			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
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
		
		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )
		->getId ();
		
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
		
		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes
		
		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );
		
		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
				->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );
				
				$this->formFilter->setDefault ( 'ps_file', $this->ps_file );
				
				$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );
				
				$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
				
				$this->formFilter->setDefault ( 'ps_class_id', $this->ps_class_id );
				
				$this->formFilter->getWidgetSchema ()
						->setNameFormat ( 'import_filter[%s]' );
	}
	
	public function executeImportTem3Save(sfWebRequest $request) {
		
		$this->formFilter = new sfFormFilter ();
		
		$ps_customer_id = null;
		
		$ps_workplace_id = null;
		
		$ps_school_year_id = null;
		
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {
			
			$this->ps_customer_id = myUser::getPscustomerID ();
			
			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );
			
			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
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
		
		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )
		->getId ();
		
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
		
		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes
		
		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );
		
		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
				->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) )
				);
		
		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );
		
		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );
		
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		
		$this->formFilter->setDefault ( 'ps_class_id', $this->ps_class_id );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'import_filter[%s]' );
		
		/**
		 * * Import file excel **
		 */
		
		$import_filter_form = $request->getParameter ( 'import_filter' );
		
		$this->formFilter->bind ( $request->getParameter ( 'import_filter' ), $request->getFiles ( 'import_filter' ) );
		// id nam hoc
		$ps_school_year_id = $this->formFilter->getValue ( 'ps_school_year_id' );
		// id truong hoc
		$ps_customer_id = $this->formFilter->getValue ( 'ps_customer_id' );
		// id co so
		$ps_workplace_id = $this->formFilter->getValue ( 'ps_workplace_id' );
		
		$array_feature = $array_class = array();
		
		$param_array = array(
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_school_year_id' => $ps_school_year_id,
				'is_activated' => PreSchool::ACTIVE);
		
		$list_class = Doctrine::getTable ( 'MyClass' ) -> setClassByParams ( $param_array )->execute();
		
		foreach ($list_class as $class){
			array_push($array_class, $class->getId());
		}
		
		$list_feature_branch = Doctrine::getTable ( 'FeatureBranch' )->getListFBexport ($ps_school_year_id, $ps_customer_id,$ps_workplace_id );
		
		foreach ($list_feature_branch as $feature_branch){
			array_push($array_feature, $feature_branch->getId());
		}
		
		$conn = Doctrine_Manager::connection();
		
		try {
			
			$conn->beginTransaction();
			
			if ($this->formFilter->isValid()) {
				
				$user_id = myUser::getUserId();
				
				$file_classify = $this->getContext()->getI18N()->__('Import feature branch');
				
				$file = $this->formFilter->getValue('ps_file');
				
				$filename = time().$file->getOriginalName();
				
				$file_link = 'FeatureBranch' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );
				
				$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';
				
				$file->save($path_file . $filename);
				
				$objPHPExcel = PHPExcel_IOFactory::load($path_file . $filename);
				
				$provinceSheet = $objPHPExcel->setActiveSheetIndex(0); // Set sheet sẽ được đọc dữ liệu
				
				$highestRow    = $provinceSheet->getHighestRow(); // Lấy số hàng lớn nhất trong sheet
				
				$highestColumn = $provinceSheet->getHighestColumn(); // Lấy số cột lớn nhất trong sheet
				
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				
				$array_date = $array_class_id = $array_error = $array_id_class_error = $array_id_feature_error = array();
				
				$false = $check = $true = 0;
				$is_saturday = $is_sunday = 0;
				$feature_date = '1970-01-01';
				$error_time = $error_date =  '';
				
				// Lay ra tat ca cac ngay
				for ($k = 2 ;$k < 8; $k++ ){
					
					$start1 = 6;
					
					$date_at = PreString::trim ($provinceSheet->getCellByColumnAndRow($k, $start1)->getCalculatedValue());
					
					// Neu de dinh dang là date
					if(is_numeric ($date_at)){
						
						$feature_date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($date_at));
						
					}else{ // Neu de dinh dang la text
						
						$feature_date = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $date_at ) ) ); // chuyển định dạng
						
					}
					
					if($feature_date != '1970-01-01'){
						array_push($array_date,$feature_date);
					}else{
						$false = 1;
						$error_date .= $date_at.'; ';
					}
					
				}

				// lay ra id lop
				for ($row = 7; $row <= $highestRow; $row++) {
					
					$class_name = $provinceSheet->getCellByColumnAndRow(8, $row)->getCalculatedValue();
					$class_id = PreString::trim ($provinceSheet->getCellByColumnAndRow(9, $row)->getCalculatedValue());
					
					if(isset($class_id) && $class_id > 0){
						if(in_array($class_id, $array_class)){
							$array_class_id[$class_id] = $class_name;
						}else{
							$false = 1;
							array_push($array_id_class_error,$class_id);
						}
					}
				}
				
				// Nếu không có lỗi id lớp và thời gian
				if(count($array_id_class_error) <= 0 && $error_date ==''){
					
					for ($row = 7; $row <= $highestRow; $row++) {
						
						$start_time = PHPExcel_Style_NumberFormat::toFormattedString ( $provinceSheet->getCellByColumnAndRow(0, $row)->getCalculatedValue(), 'hh:mm:ss' );
						$stop_time = PHPExcel_Style_NumberFormat::toFormattedString ( $provinceSheet->getCellByColumnAndRow(1, $row)->getCalculatedValue(), 'hh:mm:ss' );
						
						if($start_time !='' && $stop_time !=''){
							
							$check_time_st = $this->verifyTime ( $start_time );
							$check_time_sn = $this->verifyTime ( $stop_time );
							
							if($check_time_st == true && $check_time_sn == true){
								
								$name_class_note = implode ( ', ', $array_class_id ); // Danh sach cac lop ap dung
								
								$key_date = 0;
								//$row++;
								for ($k = 2 ;$k < 8; $k++ ){
									
									$start2 = $row+1;
									$feature_id = PreString::trim ($provinceSheet->getCellByColumnAndRow($k, $start2)->getCalculatedValue());
									
									if($feature_id != ''){
									
										// Neu id hoat dong hop le
										if(in_array($feature_id,$array_feature)){
											
											$true++;
											
											$start3 = $start2+1;
											$feature_note = $provinceSheet->getCellByColumnAndRow($k, $start3)->getCalculatedValue();
											
											$feature_date = $array_date[$key_date];
											
											$title_day = date('D', strtotime($feature_date));
											
											if($title_day == 'Sat'){
												$is_saturday = 1;
											}else{
												$is_saturday = 0;
											}
											
											//echo 'date_'.$name_class_note.'<br/>';
											
											$feature_branch_time = new FeatureBranchTimes ();
											
											$feature_branch_time->setPsFeatureBranchId ( $feature_id );
											
											$feature_branch_time->setPsClassRoomId ( null );
											$feature_branch_time->setStartAt ( $feature_date );
											$feature_branch_time->setEndAt ( $feature_date );
											$feature_branch_time->setStartTime ( $start_time );
											$feature_branch_time->setEndTime ( $stop_time );
											$feature_branch_time->setIsSaturday ( $is_saturday );
											$feature_branch_time->setIsSunday ( $is_sunday );
											$feature_branch_time->setNote ( $feature_note );
											$feature_branch_time->setNoteClassName ( $name_class_note );
											
											$feature_branch_time->setUserCreatedId ( $user_id );
											$feature_branch_time->setUserUpdatedId ( $user_id );
											
											$feature_branch_time->save ();
											
											foreach ($array_class_id as $class_id => $class_name){
												$ps_feature_branch_time_my_class = new PsFeatureBranchTimeMyClass ();
												$ps_feature_branch_time_my_class->setPsFeatureBranchTimeId ( $feature_branch_time->getId() );
												$ps_feature_branch_time_my_class->setPsMyclassId ( $class_id );
												$ps_feature_branch_time_my_class->setNote ( $feature_note );
												$ps_feature_branch_time_my_class->setPsClassRoom ( null );
												$ps_feature_branch_time_my_class->setUserCreatedId ( $user_id );
												$ps_feature_branch_time_my_class->setUserUpdatedId ( $user_id );
												$ps_feature_branch_time_my_class->save ();
											}
											
										}else{ // ID hoat dong khong hop le
											$false = 1;
											array_push($array_id_feature_error,$feature_id);
										}
									}
									$key_date++;
								}
							}else{
								$false = 1;
								$error_time .= 'Row: '.$row.'; ';
							}
							$row = $row+2;
						}
					}
				}
			}
			//die;
			if($true > 0){
				// luu lich su import file lich hoat dong
				$ps_history_import = new PsHistoryImport ();
				$ps_history_import->setPsCustomerId ( $ps_customer_id );
				$ps_history_import->setPsWorkplaceId ( $ps_workplace_id );
				$ps_history_import->setFileName ( $filename );
				$ps_history_import->setFileLink ( $file_link );
				$ps_history_import->setFileClassify ( $file_classify );
				$ps_history_import->setUserCreatedId ( $user_id );
				
				$ps_history_import->save ();
			}else{
				unlink ( $path_file . $filename );
			}
			
			$conn->commit();
		}catch (Exception $e) {
			unlink ( $path_file . $filename );
			$conn->rollback();
			$error_import = $e->getMessage();
			$this->getUser()->setFlash('error', $error_import);
		}
		
		// Neu xuat hien loi
		if($false == 1){
			if(count($array_id_class_error) > 0){
				$notice_class_error = $this->getContext ()->getI18N ()->__ ( 'ID class not found' ).
				$notice_class_error_line = $this->getContext ()->getI18N ()->__ ( 'ID: %value%', array ('%value%' => implode(' ; ', $array_id_class_error) ) );
				$this->getUser ()->setFlash ( 'notice_class_error', $notice_class_error );
			}
			
			if(count($array_id_feature_error) > 0){
				$notice_feature_error = $this->getContext ()->getI18N ()->__ ( 'ID feature not found' ).
				$notice_feature_error_line = $this->getContext ()->getI18N ()->__ ( 'ID: %value%', array ('%value%' => implode(' ; ', $array_id_feature_error) ) );
				$this->getUser ()->setFlash ( 'notice_feature_error', $notice_feature_error );
			}
			
			if($error_date != ''){
				$notice_time_error = $this->getContext ()->getI18N ()->__ ( 'Error time' ).$error_date;
				$this->getUser ()->setFlash ( 'notice_time_error', $notice_time_error );
			}
		}elseif($true > 0){
			$successfully = $this->getContext()->getI18N()->__( 'Import file feature successfully');
			$this->getUser()->setFlash('notice', $successfully);
		}else{
			$error_feature = $this->getContext()->getI18N()->__( 'Not import data');
			$this->getUser()->setFlash('error', $error_feature);
		}
		
		$this->redirect ( '@ps_feature_branch_times_import_tem3' );
		
	}
	
	protected function verifyTime($date, $format = 'H:i:s') {
		
		$d = DateTime::createFromFormat ( $format, $date );
		return $d && $d->format ( $format ) == $date;
	}
	
}
