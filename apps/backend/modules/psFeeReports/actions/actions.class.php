<?php
require_once dirname ( __FILE__ ) . '/../lib/psFeeReportsGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psFeeReportsGeneratorHelper.class.php';

/**
 * psFeeReports actions.
 *
 * @package kidsschool.vn
 * @subpackage psFeeReports
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psFeeReportsActions extends autoPsFeeReportsActions {

	protected function setReceivableStudentFilters(array $filters) {

		return $this->getUser ()
			->setAttribute ( 'psFeeReports.feeReceivableStudentFilters', $filters, 'admin_module' );
	}

	protected function getReceivableStudentFilters() {

		$filters = $this->getUser ()
			->getAttribute ( 'psFeeReports.feeReceivableStudentFilters', array (), 'admin_module' );

		return $filters;
	}

	// Man hinh trung lua chon xu ly phieu bao
	public function executeFeePanel(sfWebRequest $request) {

	}

	// Thanh toán
	public function executePaymentReceipt(sfWebRequest $request) {

		// $this->exportReport();
		$this->ps_fee_reports = $this->getRoute ()
			->getObject ();

		$this->student = $this->ps_fee_reports->getStudent ();

		if (! myUser::checkAccessObject ( $this->student, 'PS_FEE_REPORT_FILTER_SCHOOL' ) || ! $this->student || ($this->student && $this->student->getDeletedAt ())) {
			$this->getUser ()
				->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
		}

		// Kiem tra thoi gian tam dung nghi hoc

		// Thang bao phi
		$this->receivable_at = $this->ps_fee_reports->getReceivableAt ();

		$int_receivable_at = PsDateTime::psDatetoTime ( $this->receivable_at );

		// Lay phieu thu cua thang
		$this->receipt = $this->student->findReceiptByDate ( $int_receivable_at );

		if (! $this->receipt) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}

		// Lay gia tri thanh toan
		$rec = $request->getParameter ( 'rec' );

		$ps_fee_report_id = $this->ps_fee_reports->getId ();

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			// Validate
			if (($rec ['collected_amount'] > 0) && is_numeric ( $rec ['collected_amount'] ) && mb_strlen ( $rec ['note'] <= 255 )) {
				$this->receipt->setCollectedAmount ( $rec ['collected_amount'] );
				// $this->receipt->setBalanceAmount ( ( float ) ($rec ['collected_amount'] - $rec ['total_payment']) );

				$this->receipt->setBalanceAmount ( ( float ) ($rec ['collected_amount'] - $this->ps_fee_reports->getReceivable ()) );
				$this->receipt->setNote ( PreString::trim ( $rec ['note'] ) );
				$this->receipt->setPaymentStatus ( PreSchool::ACTIVE );
				$this->receipt->setPaymentDate ( date ( "Y-m-d H:i:s" ) );
				$this->receipt->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
					->getGuardUser ()
					->getId () );
				$this->receipt->save ();

				$this->getUser ()
					->setFlash ( 'notice', $this->getContext ()
					->getI18N ()
					->__ ( 'Payment successfully.' ) );
			} else {
				$this->getUser ()
					->setFlash ( 'error', $this->getContext ()
					->getI18N ()
					->__ ( 'Payment fail. Data invalid.' ) );
			}

			$conn->commit ();
		} catch ( Exception $e ) {

			$conn->rollback ();

			$this->getUser ()
				->setFlash ( 'error', 'Payment fail.' );

			$this->redirect ( '@ps_fee_reports_detail?id=' . $ps_fee_report_id );
		}

		$this->redirect ( '@ps_fee_reports' );
	}

	// Sua phieu thu
	public function executeEditPaymentReceipt(sfWebRequest $request) {

		$this->receipt = $this->getRoute ()
			->getObject ();

		$this->student = $this->receipt->getStudent ();

		if (! myUser::checkAccessObject ( $this->student, 'PS_FEE_REPORT_FILTER_SCHOOL' ) || $this->student->getDeletedAt ()) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}

		$this->ps_fee_reports = $this->student->findPsFeeReportOfStudentByDate ( PsDateTime::psDatetoTime ( $this->receipt->getReceiptDate () ) );

		$this->receivable_at = $this->ps_fee_reports->getReceivableAt ();

		// Lay danh sach cac khoan phi cua phieu bao
		$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $this->student->getId (), $this->receipt->getReceiptDate (), null );

		$this->form = new ReceiptForm ( $this->receipt );
	}

	public function executeWarning(sfWebRequest $request) {

		$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );

		$this->filter_value = $this->getFilters ();
		$this->filter_value ['ps_customer_id'] = (isset ( $this->filter_value ['ps_customer_id'] )) ? $this->filter_value ['ps_customer_id'] : '';
		$this->filter_value ['ps_class_id'] = (isset ( $this->filter_value ['ps_class_id'] )) ? $this->filter_value ['ps_class_id'] : '';
		$this->filter_value ['ps_year_month'] = (isset ( $this->filter_value ['ps_year_month'] )) ? $this->filter_value ['ps_year_month'] : date ( "m-Y" );

		$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );
	}

	public function executeIndex(sfWebRequest $request) {

		$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );

		$this->filter_value = $this->getFilters ();

		$this->filter_value ['ps_customer_id'] = (isset ( $this->filter_value ['ps_customer_id'] )) ? $this->filter_value ['ps_customer_id'] : '';
		$this->filter_value ['ps_workplace_id'] = (isset ( $this->filter_value ['ps_workplace_id'] )) ? $this->filter_value ['ps_workplace_id'] : '';
		$this->filter_value ['ps_class_id'] = (isset ( $this->filter_value ['ps_class_id'] )) ? $this->filter_value ['ps_class_id'] : '';
		$this->filter_value ['ps_year_month'] = (isset ( $this->filter_value ['ps_year_month'] )) ? $this->filter_value ['ps_year_month'] : date ( "m-Y" );

		// Lay ngay chot bao phi.
		$closing_date_fee = '01';

		// Tim khoan phai thu trong thang nay cua lop
		$params = array ();
		$params ['ps_customer_id'] = $this->filter_value ['ps_customer_id'];
		$params ['ps_workplace_id'] = $this->filter_value ['ps_workplace_id'];
		$params ['ps_school_year_id'] = $this->filter_value ['ps_school_year_id'];

		// Lay thong so cau hinh cua co so
		if ($params ['ps_customer_id'] > 0 && $params ['ps_workplace_id'] > 0) {

			$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $params ['ps_workplace_id'] );

			$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_FEE_REPORT_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $ps_workplace->getId () ) );

			$closing_date_fee = ($ps_workplace->getConfigClosingDateFee () > 0) ? $ps_workplace->getConfigClosingDateFee () : '01';
		}

		// $params['is_activated'] = PreSchool::ACTIVE;
		$receivable_at = $closing_date_fee . '-' . $this->filter_value ['ps_year_month'];

		if ($this->filter_value ['ps_customer_id'] > 0 && $receivable_at) {
			$params ['date'] = PsDateTime::psDatetoTime ( $receivable_at );
			$params ['ps_myclass_id'] = $this->filter_value ['ps_class_id'];

			$this->list_receivable_temp_receivable_at = Doctrine::getTable ( "Receivable" )->getListReceivableTempByParams ( $params );

			// path: Hoc sinh web/pschool/school_code/reportcard/yyyy/mm/md5(ID hoc sinh)

			/*
			 * Lay phieu bao da xuat ra cua thang path: web/pschool/school_code/reportcard/workplace/yyyymm/md5(ps_workplace_id) web/pschool/school_code/reportcard/class/yyyymm/md5(ps_myclass_id)
			 */
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

		// $students = Doctrine::getTable ( 'Receipt' )->findStudentDebtByDate ( $params ['ps_customer_id'], $params ['ps_myclass_id'], $receivable_at );

		// $this->getUser ()->setFlash ( 'notice', count ( $students ) );
	}

	public function executeFilter(sfWebRequest $request) {

		$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );

		$this->setPage ( 1 );

		if ($request->hasParameter ( '_reset' )) {

			$this->setFilters ( $this->configuration->getFilterDefaults () );

			$this->redirect ( '@ps_fee_reports' );
		}

		$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );

		$this->filters->bind ( $request->getParameter ( $this->filters->getName () ) );

		if ($this->filters->isValid ()) {

			$this->setFilters ( $this->filters->getValues () );

			$this->redirect ( '@ps_fee_reports' );
		}

		$this->setTemplate ( 'warning' );
	}

	// Lay danh sach lop hoc theo: nam hoc, co so
	public function executeFilterClassByWorkplace(sfWebRequest $request) {

		$formFilter = new sfFormFilter ();

		$param_class = array ();

		$param_class = array (
				'ps_school_year_id' => $schoolYearsDefault->id,
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id ); // 'is_active' =>

		if ($ps_workplace_id > 0) {

			$this->formFilter->setWidget ( 'ps_class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class ),
					'expanded' => true,
					'multiple' => true ) ) );
		}
	}

	// Lay danh sach lop hoc theo: nam hoc, co so
	public function executeFilterNumberDayByMonth(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$formFilter = new sfFormFilter ();

			$to_month = $request->getParameter ( 'to_month' );

			// Lay thang
			if ($to_month != '')
				$number_day = PsDateTime::psNumberDaysOfMonth ( $to_month );
			else {
				$number_day ['normal_day'] = 0;
				$number_day ['saturday_day'] = 0;
			}

			$formFilter->setWidget ( 'normal_day', new sfWidgetFormInputText ( array (), array (
					'type' => 'number',
					'min' => 10,
					'max' => 30,
					'required' => true,
					'class' => 'form-control',
					'placeholder' => _ ( 'Normal day' ) ) ) );

			$formFilter->setWidget ( 'saturday_day', new sfWidgetFormInputText ( array (), array (
					'type' => 'number',
					'min' => 10,
					'max' => 30,
					'required' => true,
					'class' => 'form-control',
					'placeholder' => _ ( 'Saturday day' ) ) ) );

			$formFilter->setDefault ( 'normal_day', $number_day ['normal_day'] );

			$formFilter->setDefault ( 'saturday_day', $number_day ['saturday_day'] );

			$formFilter->getWidgetSchema ()
				->setNameFormat ( 'control_filter[%s]' );

			return $this->renderPartial ( 'psFeeReports/box/_form_field_number_day', array (
					'formFilter' => $formFilter,
					'PS_CULTURE' => $this->getUser ()
						->getCulture () ) );
		} else {
			exit ( 0 );
		}
	}

	// Man hinh xu ly phieu bao - phieu thu _Step1
	public function executeFeeControlStep1(sfWebRequest $request) {

		$this->helper = new psFeeReportsGeneratorHelper ();

		$this->configuration = new psFeeReportsGeneratorConfiguration ();

		$this->formFilter = new sfFormFilter ();

		$control_filter = $request->getParameter ( 'control_filter' );

		$this->year_month = isset ( $control_filter ['year_month'] ) ? $control_filter ['year_month'] : date ( "m-Y" );

		$this->normal_day = isset ( $control_filter ['normal_day'] ) ? $control_filter ['normal_day'] : null;

		$this->saturday_day = isset ( $control_filter ['saturday_day'] ) ? $control_filter ['saturday_day'] : null;

		// Lay nam hoc hien tai
		// $schoolYearsDefault = sfContext::getInstance ()->getUser ()->getAttribute ( 'ps_school_year_default' );

		$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
		
		// Lay nam bat dau
		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );
		
		// Lui 5 thang
		$yearsDefaultStart = date ( 'Y-m', strtotime ( "-12 month" , strtotime ( $schoolYearsDefault->getFromDate () )) );

		//$yearsDefaultEnd = date ( "Y-m", strtotime ($schoolYearsDefault->getToDate () ) );
		
		$yearsDefaultEnd = date ( 'Y-m', strtotime ( "+2 month" , strtotime ( $schoolYearsDefault->getToDate () )) );

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

		$number_day = PsDateTime::psNumberDaysOfMonth ( $current_month );

		$this->formFilter->setDefault ( 'year_month', $current_month );

		$this->formFilter->setWidget ( 'normal_day', new sfWidgetFormInputText ( array (), array (
				'type' => 'number',
				'min' => 10,
				'max' => 30,
				'required' => true,
				'class' => 'form-control',
				'placeholder' => $this->getContext ()
					->getI18N ()
					->__ ( 'Normal day' ),
				'rel' => 'tooltip',
				'data-original-title' => $this->getContext ()
					->getI18N ()
					->__ ( 'Normal day' ) ) ) );

		$this->formFilter->setWidget ( 'saturday_day', new sfWidgetFormInputText ( array (), array (
				'type' => 'number',
				'min' => 10,
				'max' => 30,
				'required' => true,
				'class' => 'form-control',
				'placeholder' => $this->getContext ()
					->getI18N ()
					->__ ( 'Saturday day' ),
				'rel' => 'tooltip',
				'data-original-title' => $this->getContext ()
					->getI18N ()
					->__ ( 'Saturday day' ) ) ) );

		$this->formFilter->setDefault ( 'normal_day', $number_day ['normal_day'] );

		$this->formFilter->setDefault ( 'saturday_day', $number_day ['saturday_day'] );

		$this->ps_customer_id = null;

		$this->ps_workplace_id = null;

		if ($control_filter) {

			$this->ps_workplace_id = isset ( $control_filter ['ps_workplace_id'] ) ? $control_filter ['ps_workplace_id'] : 0;

			if ($this->ps_workplace_id > 0) {

				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_FEE_REPORT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

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

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'control_filter[%s]' );
	}

	// Man hinh xu ly phieu bao - phieu thu _Step2
	public function executeFeeControlStep2(sfWebRequest $request) {

		$this->helper = new psFeeReportsGeneratorHelper ();

		$this->configuration = new psFeeReportsGeneratorConfiguration ();

		$this->formFilter = new sfFormFilter ();

		$control_filter = $request->getParameter ( 'control_filter' );

		$this->ps_workplace_id = isset ( $control_filter ['ps_workplace_id'] ) ? $control_filter ['ps_workplace_id'] : 0;

		$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

		$this->ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_workplace, 'PS_FEE_REPORT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->ps_customer_id = $this->ps_workplace->getPsCustomerId ();

		$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

		$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
				'required' => true ) ) );

		$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormInputHidden () );

		$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorInteger ( array (
				'required' => true ) ) );

		$this->year_month = isset ( $control_filter ['year_month'] ) ? $control_filter ['year_month'] : null;

		$this->normal_day = isset ( $control_filter ['normal_day'] ) ? $control_filter ['normal_day'] : null;

		$this->saturday_day = isset ( $control_filter ['saturday_day'] ) ? $control_filter ['saturday_day'] : null;

		$this->ps_class_id = isset ( $control_filter ['ps_class_id'] ) ? $control_filter ['ps_class_id'] : null;

		$this->formFilter->setWidget ( 'year_month', new sfWidgetFormInputText ( array (), array (
				'type' => 'text',
				'class' => 'form-control',
				'readonly' => 'readonly',
				'style' => 'background-color:#fff; border:0px;',
				'rel' => 'tooltip',
				'data-original-title' => $this->getContext ()
					->getI18N ()
					->__ ( 'Process fee report of %%value%%', array (
						'%%value%%' => $this->year_month ) ) ) ) );

		$this->formFilter->setWidget ( 'normal_day', new sfWidgetFormInputText ( array (), array (
				'type' => 'text',
				'class' => 'form-control',
				'readonly' => 'readonly',
				'style' => 'background-color:#fff; border:0px;',
				'rel' => 'tooltip',
				'data-original-title' => $this->getContext ()
					->getI18N ()
					->__ ( 'Normal day' ) ) ) );

		$this->formFilter->setWidget ( 'saturday_day', new sfWidgetFormInputText ( array (), array (
				'type' => 'text',
				'class' => 'form-control',
				'readonly' => 'readonly',
				'style' => 'background-color:#fff; border:0px;',
				'rel' => 'tooltip',
				'data-original-title' => $this->getContext ()
					->getI18N ()
					->__ ( 'Saturday day' ) ) ) );

		// Lay nam hoc hien tai
		// $schoolYearsDefault = sfContext::getInstance ()->getUser ()->getAttribute ( 'ps_school_year_default' );
		$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );

		$param_class = array (
				'ps_school_year_id' => $schoolYearsDefault->getId (),
				'ps_customer_id' => $this->ps_customer_id,
				'ps_workplace_id' => $this->ps_workplace_id,
				'receivable_at' => PsDateTime::psDatetoTime ( '01-' . $this->year_month ) );

		$this->formFilter->setWidget ( 'ps_class_id', new sfWidgetFormDoctrineChoiceGrouped ( array (
				'model' => 'MyClass',
				// 'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class ),
				'query' => Doctrine::getTable ( 'MyClass' )->setSqlMyClassForProcessFeeReports ( $param_class ),
				'expanded' => true,
				'multiple' => true,
				'group_by' => 'og_name',
				'renderer_options' => array (
						'template' => '<label><strong>%group%</strong></label> %options%' ) ) ) );

		$this->formFilter->setValidator ( 'ps_class_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'MyClass',
				'required' => false ) ) );

		// Lay danh sach cac lop da chay xu ly bao phi cua thang nay
		$this->ps_fee_reports_my_class = Doctrine::getTable ( 'PsFeeReportsFlagMyClass' )->setSqlGetListOfParams ( $param_class )
			->execute ();

		// ##################################################################################

		$this->formFilter->setDefault ( 'ps_class_id', $this->ps_class_id );

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'year_month', $this->year_month );

		$this->formFilter->setDefault ( 'normal_day', $this->normal_day );

		$this->formFilter->setDefault ( 'saturday_day', $this->saturday_day );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'control_filter[%s]' );
	}

	// Man hinh xu ly phieu bao - phieu thu _Step3: Them cac khoan thu khac cho bao phi
	public function executeFeeControlStep3(sfWebRequest $request) {

		$this->helper = new psFeeReportsGeneratorHelper ();

		$this->configuration = new psFeeReportsGeneratorConfiguration ();

		$this->formFilter = new sfFormFilter ();

		$control_filter = $request->getParameter ( 'control_filter' );

		$this->ps_workplace_id = isset ( $control_filter ['ps_workplace_id'] ) ? $control_filter ['ps_workplace_id'] : 0;

		$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

		$this->ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_workplace, 'PS_FEE_REPORT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->ps_customer_id = $this->ps_workplace->getPsCustomerId ();

		$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

		$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
				'required' => true ) ) );

		$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormInputHidden () );

		$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorInteger ( array (
				'required' => true ) ) );

		$this->year_month = isset ( $control_filter ['year_month'] ) ? $control_filter ['year_month'] : null;

		$this->normal_day = isset ( $control_filter ['normal_day'] ) ? $control_filter ['normal_day'] : null;

		$this->saturday_day = isset ( $control_filter ['saturday_day'] ) ? $control_filter ['saturday_day'] : null;

		$this->ps_class_id = isset ( $control_filter ['ps_class_id'] ) ? $control_filter ['ps_class_id'] : null;

		$this->receivables = isset ( $control_filter ['receivable'] ) ? $control_filter ['receivable'] : null;

		$this->formFilter->setWidget ( 'year_month', new sfWidgetFormInputText ( array (), array (
				'type' => 'text',
				'class' => 'form-control',
				'readonly' => 'readonly',
				'style' => 'background-color:#fff; border:0px;',
				'rel' => 'tooltip',
				'data-original-title' => $this->getContext ()
					->getI18N ()
					->__ ( 'Process fee report of %%value%%', array (
						'%%value%%' => $this->year_month ) ) ) ) );

		$this->formFilter->setWidget ( 'normal_day', new sfWidgetFormInputText ( array (), array (
				'type' => 'text',
				'class' => 'form-control',
				'readonly' => 'readonly',
				'style' => 'background-color:#fff; border:0px;',
				'rel' => 'tooltip',
				'data-original-title' => $this->getContext ()
					->getI18N ()
					->__ ( 'Normal day' ) ) ) );

		$this->formFilter->setWidget ( 'saturday_day', new sfWidgetFormInputText ( array (), array (
				'type' => 'text',
				'class' => 'form-control',
				'readonly' => 'readonly',
				'style' => 'background-color:#fff; border:0px;',
				'rel' => 'tooltip',
				'data-original-title' => $this->getContext ()
					->getI18N ()
					->__ ( 'Saturday day' ) ) ) );

		// Lay nam hoc hien tai
		// $schoolYearsDefault = sfContext::getInstance ()->getUser ()->getAttribute ( 'ps_school_year_default' );
		$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );

		$param_class = array (
				'ps_school_year_id' => $schoolYearsDefault->getId (),
				'ps_customer_id' => $this->ps_customer_id,
				'ps_workplace_id' => $this->ps_workplace_id,
				'receivable_at' => PsDateTime::psDatetoTime ( '01-' . $this->year_month ) );

		$this->formFilter->setWidget ( 'ps_class_id_2', new sfWidgetFormDoctrineChoiceGrouped ( array (
				'model' => 'MyClass',
				'query' => Doctrine::getTable ( 'MyClass' )->setSqlMyClassForProcessFeeReports ( $param_class ),
				'expanded' => true,
				'multiple' => true,
				'group_by' => 'og_name',
				'renderer_options' => array (
						'template' => '<label><strong>%group%</strong></label> %options%' ) ), array (
				'readonly ' => 'readonly',
				'disabled' => 'disabled' ) ) );

		$this->formFilter->setValidator ( 'ps_class_id_2', new sfValidatorDoctrineChoice ( array (
				'model' => 'MyClass',
				'required' => false ) ) );

		// Lay tat ca cac lop chua chay bao phi cua co so neu khong chon lop
		if (count ( $this->ps_class_id ) <= 0) {

			$_ps_class_id = Doctrine::getTable ( 'MyClass' )->setSqlMyClassForProcessFeeReports ( $param_class )
				->execute ();

			$_ps_class = array ();

			foreach ( $_ps_class_id as $ps_class ) {
				array_push ( $_ps_class, $ps_class->getId () );
			}

			$this->ps_class_id = $_ps_class;
		}

		// Lay danh sach cac lop da chay xu ly bao phi cua thang nay
		$this->ps_fee_reports_my_class = Doctrine::getTable ( 'PsFeeReportsFlagMyClass' )->setSqlGetListOfParams ( $param_class )
			->execute ();

		// Lay danh sach cac khoan phai thu con lai co the chon cua thang
		$receivable_params = array ();
		$receivable_params ['ps_customer_id'] = $this->ps_customer_id;
		$receivable_params ['ps_workplace_id'] = $this->ps_workplace_id;
		$receivable_params ['ps_school_year_id'] = $schoolYearsDefault->getId ();
		$receivable_params ['is_activated'] = PreSchool::ACTIVE;
		$receivable_params ['date'] = PsDateTime::psDatetoTime ( '01-' . $this->year_month );
		$receivable_params ['ps_myclass_id'] = $this->ps_class_id;

		// Lay danh sach cac khoan phai thu khác
		$this->receivable_for_fee_report = Doctrine::getTable ( "Receivable" )->getListReceivableSkipTempByParams ( $receivable_params );

		// ##################################################################################

		// $this->formFilter->setWidget ( 'ps_class_id', new sfWidgetFormInputHidden (array('needs_multipart' => true)) );

		$this->formFilter->setDefault ( 'ps_class_id_2', $this->ps_class_id );

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'year_month', $this->year_month );

		$this->formFilter->setDefault ( 'normal_day', $this->normal_day );

		$this->formFilter->setDefault ( 'saturday_day', $this->saturday_day );

		$this->formFilter->setDefault ( 'receivable', $this->receivables );
		
		/*
		$this->arr_receivable = array ();

		foreach ( $this->receivables as $_receivable ) {
			array_push ( $this->arr_receivable, $_receivable ['ids'] );
		}
		*/
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'control_filter[%s]' );
	}

	// Man hinh xu ly phieu bao - phieu thu _Step4: Xac nhan dieu kien chay bao phi
	public function executeFeeControlStep4(sfWebRequest $request) {

		$this->helper = new psFeeReportsGeneratorHelper ();

		$this->configuration = new psFeeReportsGeneratorConfiguration ();

		$this->formFilter = new sfFormFilter ();

		$control_filter = $request->getParameter ( 'control_filter' );

		$this->ps_workplace_id = isset ( $control_filter ['ps_workplace_id'] ) ? $control_filter ['ps_workplace_id'] : 0;

		$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

		$this->ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_workplace, 'PS_FEE_REPORT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->ps_customer_id = $this->ps_workplace->getPsCustomerId ();

		$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

		$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
				'required' => true ) ) );

		$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormInputHidden () );

		$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorInteger ( array (
				'required' => true ) ) );

		$this->year_month = isset ( $control_filter ['year_month'] ) ? $control_filter ['year_month'] : null;

		$this->normal_day = isset ( $control_filter ['normal_day'] ) ? $control_filter ['normal_day'] : null;

		$this->saturday_day = isset ( $control_filter ['saturday_day'] ) ? $control_filter ['saturday_day'] : null;

		$this->ps_class_id = isset ( $control_filter ['ps_class_id'] ) ? $control_filter ['ps_class_id'] : null;

		$this->receivables = isset ( $control_filter ['receivable'] ) ? $control_filter ['receivable'] : null;

		$this->formFilter->setWidget ( 'year_month', new sfWidgetFormInputHidden () );

		$this->formFilter->setWidget ( 'normal_day', new sfWidgetFormInputHidden () );

		$this->formFilter->setWidget ( 'saturday_day', new sfWidgetFormInputHidden () );

		// Lay nam hoc hien tai
		$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );

		$param_class = array (
				'ps_school_year_id' => $schoolYearsDefault->getId (),
				'ps_customer_id' => $this->ps_customer_id,
				'ps_workplace_id' => $this->ps_workplace_id,
				'receivable_at' => PsDateTime::psDatetoTime ( '01-' . $this->year_month ) );

		$this->formFilter->setWidget ( 'ps_class_id_2', new sfWidgetFormDoctrineChoiceGrouped ( array (
				'model' => 'MyClass',
				'query' => Doctrine::getTable ( 'MyClass' )->setSqlMyClassForProcessFeeReports ( $param_class ),
				'expanded' => true,
				'multiple' => true,
				'group_by' => 'og_name',
				'renderer_options' => array (
						'template' => '<label><strong>%group%</strong></label> %options%' ) ), array (
				'readonly ' => 'readonly',
				'disabled' => 'disabled' ) ) );

		$this->formFilter->setValidator ( 'ps_class_id_2', new sfValidatorDoctrineChoice ( array (
				'model' => 'MyClass',
				'required' => false ) ) );

		// Lay danh sach cac lop da chay xu ly bao phi cua thang nay
		$this->ps_fee_reports_my_class = Doctrine::getTable ( 'PsFeeReportsFlagMyClass' )->setSqlGetListOfParams ( $param_class )
			->execute ();

		// Danh sach cac lop xu ly bao phi
		$this->list_class_process = Doctrine::getTable ( 'MyClass' )->setSqlMyClassForProcessFeeReports ( $param_class )
			->execute ();

		// Lay danh sach cac khoan phai thu con lai co the chon cua thang
		$receivable_params = array ();
		$receivable_params ['ps_customer_id'] = $this->ps_customer_id;
		$receivable_params ['ps_workplace_id'] = $this->ps_workplace_id;
		$receivable_params ['ps_school_year_id'] = $schoolYearsDefault->getId ();
		$receivable_params ['is_activated'] = PreSchool::ACTIVE;

		$receivable_params ['date'] = PsDateTime::psDatetoTime ( '01-' . $this->year_month );
		$receivable_params ['ps_myclass_id'] = $this->ps_class_id;

		$this->receivable_for_fee_report = Doctrine::getTable ( "Receivable" )->getListReceivableSkipTempByParams ( $receivable_params );

		// ##################################################################################

		$this->formFilter->setDefault ( 'ps_class_id_2', $this->ps_class_id );

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'year_month', $this->year_month );

		$this->formFilter->setDefault ( 'normal_day', $this->normal_day );

		$this->formFilter->setDefault ( 'saturday_day', $this->saturday_day );

		$this->formFilter->setDefault ( 'receivable', $this->receivables );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'control_filter[%s]' );
	}

	// Man hinh xu ly phieu bao - phieu thu _Step5: Chay bao phi
	public function executeFeeControlStep5(sfWebRequest $request) {

		$this->helper = new psFeeReportsGeneratorHelper ();

		$this->configuration = new psFeeReportsGeneratorConfiguration ();

		$this->formFilter = new sfFormFilter ();

		$control_filter = $request->getParameter ( 'control_filter' );

		$this->ps_workplace_id = isset ( $control_filter ['ps_workplace_id'] ) ? $control_filter ['ps_workplace_id'] : 0;

		if (! $this->ps_workplace_id) {

			return $this->renderPartial ( 'global/include/_box_modal_error_403404' );
		}

		$this->ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

		if (! myUser::checkAccessObject ( $this->ps_workplace, 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			return $this->renderPartial ( 'global/include/_box_modal_error_403404' );
		}

		$this->ps_customer_id = $this->ps_workplace->getPsCustomerId ();

		$this->year_month = isset ( $control_filter ['year_month'] ) ? $control_filter ['year_month'] : null;

		$this->normal_day = isset ( $control_filter ['normal_day'] ) ? $control_filter ['normal_day'] : null;

		$this->saturday_day = isset ( $control_filter ['saturday_day'] ) ? $control_filter ['saturday_day'] : null;

		$this->ps_class_id = isset ( $control_filter ['ps_class_id'] ) ? $control_filter ['ps_class_id'] : array();

		$receivable_month = isset ( $control_filter ['receivable'] ) ? $control_filter ['receivable'] : array();

		// Lay ngay chay bao phi
		$config_closing_date_fee = $this->ps_workplace->getConfigClosingDateFee ();

		$config_closing_date_fee = '01'; // ($config_closing_date_fee <= 0) ? '01' : $config_closing_date_fee;

		// Chuyển về YYYY-mm-dd
		$date_temp = DateTime::createFromFormat ( 'd-m-Y', $config_closing_date_fee . "-" . $this->year_month );
		$receivable_at_temp = $date_temp->format ( 'Y-m-d' );
		$receivable_at = date ( "Y-m-d", PsDateTime::psDatetoTime ( $receivable_at_temp ) );

		// Them moi cac khoan phai thu cho lop va hoc sinh
		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			// Xu ly khoan phai thu khác cua cac lop vao ReceivableTemp
			foreach ( $receivable_month as $obj ) {

				if (isset ( $obj ['ids'] ) && $obj ['ids'] > 0) {

					// Kiem tra lai table Receivable truoc khi chen
					$receivable = Doctrine::getTable ( "Receivable" )->findOneById ( $obj ['ids'] );

					// Validate
					if ($receivable && is_numeric ( $obj ['amount'] ) && mb_strlen ( $obj ['note'] <= 255 )) {

						foreach ( $this->ps_class_id as $ps_class_id ) {

							/**
							 * Kiem tra lai table Receivable truoc khi insert; - Neu chua co thi them moi; - Khong update lai de tranh xay ra loi neu du lieu do da xu ly phieu bao *
							 */
							$check_receivable_temp = Doctrine::getTable ( "ReceivableTemp" )->getReceivableTempOfClass ( array (
									'receivable_id' => $obj ['ids'],
									'receivable_at' => PsDateTime::psDatetoTime ( $receivable_at ),
									'ps_myclass_id' => $ps_class_id ) );

							if (! $check_receivable_temp) {

								$receivable_temp = new ReceivableTemp ();
								$receivable_temp->setReceivableId ( $obj ['ids'] );
								$receivable_temp->setAmount ( $obj ['amount'] );
								$receivable_temp->setNote ( $obj ['note'] );
								$receivable_temp->setReceivableAt ( $receivable_at );
								$receivable_temp->setPsMyclassId ( $ps_class_id );
								$receivable_temp->setUserCreatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()->getId () );
								$receivable_temp->setUserUpdatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()->getId () );

								$receivable_temp->save ();
							}
						}
					}
				}
			} // End for ReceivableTemp

			// Danh dau lop da duoc chay bao phi PsFeeReportsFlagMyClass
			foreach ( $this->ps_class_id as $ps_class_id ) {

				$ps_fee_reports_flag_my_class = Doctrine::getTable ( 'PsFeeReportsFlagMyClass' )->getOneObject ( $ps_class_id, PsDateTime::psDatetoTime ( $receivable_at ) );

				if (! $ps_fee_reports_flag_my_class) {

					$psFeeReportsFlagMyClass = new PsFeeReportsFlagMyClass ();
					$psFeeReportsFlagMyClass->setReceivableAt ( $receivable_at );
					$psFeeReportsFlagMyClass->setPsMyclassId ( $ps_class_id );
					$psFeeReportsFlagMyClass->setUserCreatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()	->getId () );
					$psFeeReportsFlagMyClass->setUserUpdatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()	->getId () );

					$psFeeReportsFlagMyClass->save ();
				}
			}

			// Lay danh sach hoc sinh cua cac lop duoc chon va chưa co phieu bao, phieu thu của các tháng >= tháng đầu vào
			$list_student_process = Doctrine::getTable ( 'Student' )->getStudentByClassNotInReceiptAndPsFeeReportsOfMonth ( $receivable_at, $this->ps_class_id );
			
			// Nếu danh sách có học sinh
			if (count ( $list_student_process ) > 0) {

				$params = array ();

				$params ['ps_customer_id']  = $this->ps_workplace->getPsCustomerId ();
				
				$params ['ps_workplace_id'] = $this->ps_workplace->getId ();

				$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );

				$params ['ps_school_year_id'] = $schoolYearsDefault->getId ();

				$params ['date'] = PsDateTime::psDatetoTime ( $receivable_at );

				$params ['receivable_at'] = $params ['date'];

				$params ['normal_day'] = $this->normal_day;

				$params ['saturday_day'] = $this->saturday_day;

				// Hinh thuc tinh tien ve muon
				$params ['type_late'] 	 = $this->ps_workplace->getConfigChooseChargeLate ();

				// Khung gia tinh tien ve muon
				$params ['ps_config_late_fees'] = $this->ps_workplace->getListLates ();

				// Gio tan truong
				$params ['config_default_logout'] = $this->ps_workplace->getConfigDefaultLogout ();

				$params ['config_start_date_system_fee'] = $this->ps_workplace->getConfigStartDateSystemFee ();

				$this->processFeeReportsOfStudent ( $list_student_process, $params, $receivable_at );

				// Xóa bỏ khoan phai thu cua cac lop trong ReceivableTemp
				foreach ( $receivable_month as $obj ) {

					if (isset ( $obj ['ids'] ) && $obj ['ids'] > 0) {

						// Kiem tra lai table Receivable truoc khi chen
						$receivable = Doctrine::getTable ( "Receivable" )->findOneById ( $obj ['ids'] );

						// Validate
						if ($receivable && is_numeric ( $obj ['amount'] ) && mb_strlen ( $obj ['note'] <= 255 )) {

							foreach ( $this->ps_class_id as $ps_class_id ) {
								/**
								 * Kiem tra lai table Receivable truoc khi insert; - Neu chua co thi them moi; - Khong update lai de tranh xay ra loi neu du lieu do da xu ly phieu bao *
								 */
								$check_receivable_temp = Doctrine::getTable ( "ReceivableTemp" )->getReceivableTempOfClass ( array (
										'receivable_id' => $obj ['ids'],
										'receivable_at' => PsDateTime::psDatetoTime ( $receivable_at ),
										'ps_myclass_id' => $ps_class_id ) );

								if ($check_receivable_temp) {
									$check_receivable_temp->delete ();
								}
							}
						}
					}
				} // End for ReceivableTemp

				$conn->commit ();
				
				$this->getUser ()->setFlash ( 'notice', $this->getContext ()->getI18N ()->__ ( 'Process fee report successfully.' ) );
				
			} else {

				$this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ( 'System found students were handling press charges.' ) );

				$this->redirect ( '@ps_fee_reports_control_step1' );
			}

			$this->redirect ( '@ps_receipts' );
		
		} catch ( Exception $e ) {

			$conn->rollback ();

			$this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ( 'Process fee report fail.' ) . $e->getMessage ());

			$this->redirect ( '@ps_fee_reports_control_step1' );
		}
	}

	// Man hinh xu ly phieu bao - phieu thu
	public function executeFeeControl(sfWebRequest $request) {

		$this->helper = new psFeeReportsGeneratorHelper ();

		$this->configuration = new psFeeReportsGeneratorConfiguration ();

		$this->formFilter = new sfFormFilter ();

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => true ) ) );

			$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		} else {

			$this->ps_customer_id = null;

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'data-placeholder' => _ ( '-All school-' ) ) ) );
		}

		if ($this->ps_customer_id > 0) {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $this->ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
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
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false ) ) );
		}

		// Lay nam hoc hien tai
		$schoolYearsDefault = sfContext::getInstance ()->getUser ()
			->getAttribute ( 'ps_school_year_default' );

		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->from_date ) );

		$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->to_date ) );

		$this->formFilter->setWidget ( 'year_month', new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select month-' ) ) + PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'data-placeholder' => _ ( '-Select month-' ) ) ) );

		// Lay thang hien tai
		$current_month = date ( "m-Y" );

		$this->formFilter->setDefault ( 'year_month', $current_month );

		$param_class = array (
				'ps_school_year_id' => $schoolYearsDefault->id,
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id );

		if ($this->ps_workplace_id > 0) {

			$this->formFilter->setWidget ( 'ps_class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class ),
					'expanded' => true,
					'multiple' => true ) ) );

			$this->formFilter->setValidator ( 'ps_class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => true ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_class_id', new sfWidgetFormChoice ( array (
					'choices' => array (),
					'expanded' => true,
					'multiple' => true ) ) );

			$this->formFilter->setValidator ( 'ps_class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => false ) ) );
		}

		// Lay cac khoan phai thu cua thang theo cơ sở
		$params ['date'] = PsDateTime::psDatetoTime ( $receivable_at );

		$params ['ps_myclass_id'] = $this->filter_value ['ps_class_id'];

		$this->list_receivable_temp_receivable_at = Doctrine::getTable ( "Receivable" )->getListReceivableTempByParams ( $params );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'control_filter[%s]' );
	}

	// Chi tiet cac khoan phai thu cua 1 học sinh trong thang
	public function executeFeeReceivableStudentDetail(sfWebRequest $request) {

		// if ($request->isXmlHttpRequest()) {
		$student_id = $request->getParameter ( 'sid' );

		$kstime = $request->getParameter ( 'kstime' );

		$ks_date_year = date ( "Y", $kstime );
		$ks_date_month = date ( "m", $kstime );
		$ks_date_day = date ( "d", $kstime );

		if ($student_id > 0 && checkdate ( $ks_date_month, $ks_date_day, $ks_date_year )) {

			$this->student = Doctrine::getTable ( 'Student' )->findOneById ( $student_id );

			if (myUser::checkAccessObject ( $this->student, 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

				// Lấy các khoản phải thu khác của học sinh trong tháng
				$this->receivable_students = Doctrine::getTable ( 'ReceivableStudent' )->getListReceivableStudentInMonth ( $student_id, $ks_date_year . "-" . $ks_date_month . "-" . $ks_date_day );

				// Lấy các dịch vụ của học sinh trong tháng
				$this->service_students = Doctrine::getTable ( 'StudentService' )->findAllServiceByStudentWithMonth ( $student_id, $ks_date_year . "-" . $ks_date_month . "-" . $ks_date_day );

				$this->kstime = $kstime;
			} else {
				return $this->renderPartial ( 'global/include/_box_modal_error_403404' );
			}
		} else {
			echo 'Error';
			return $this->renderPartial ( 'global/include/_box_modal_error_403404' );
			exit ();
		}
	}

	// Man hinh Lập khoản phải thu cho tháng _Step1
	public function executeFeeReceivableStudentStep1(sfWebRequest $request) {

		$this->helper = new psFeeReportsGeneratorHelper ();

		$this->configuration = new psFeeReportsGeneratorConfiguration ();

		$this->formFilter = new sfFormFilter ();

		$keywords = '';
		
		$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );

		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );
		
		// Lui 5 thang
		$yearsDefaultStart = date ( 'Y-m', strtotime ( "-5 month" , strtotime ( $schoolYearsDefault->getFromDate () )) );
		
		$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );

		$this->formFilter->setWidget ( 'year_month', new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select month-' ) ) + PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => $this->getContext ()
					->getI18N ()
					->__ ( 'Select month' ) ) ) );

		$year_month = $this->formFilter->getDefault ( 'year_month' ) ? $this->formFilter->getDefault ( 'year_month' ) : date ( "m-Y" );

		// Ban dau lay thang hien tai
		$this->formFilter->setDefault ( 'year_month', $year_month );

		$class_ids = array ();

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			$ps_customer_id = myUser::getPscustomerID ();

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

			$ps_customer_id = $this->formFilter->getDefault ( 'ps_customer_id' );
		}

		if ($ps_customer_id <= 0)
			$ps_customer_id = myUser::getPscustomerID ();

		if ($request->isMethod ( 'post' )) {

			$value_filter = $request->getParameter ( 'fee_receivable_student_filter' );

			$ps_customer_id = $value_filter ['ps_customer_id'];

			$ps_workplace_id = $value_filter ['ps_workplace_id'];

			$year_month = $value_filter ['year_month'];
			
			if(isset($value_filter ['ps_class_id']))
				$class_ids = $value_filter ['ps_class_id'];
			
			$keywords = $value_filter ['keywords'];

			// $this->setReceivableStudentFilters($value_filter);
		} else {

			$value_filter = $this->getReceivableStudentFilters ();

			if (count ( $value_filter )) {
				$ps_customer_id = $value_filter ['ps_customer_id'];

				$ps_workplace_id = $value_filter ['ps_workplace_id'];

				$year_month = $value_filter ['year_month'];

				$class_ids = $value_filter ['ps_class_id'];

				$keywords = $value_filter ['keywords'];
			}else{
				$ps_customer_id = myUser::getPscustomerID ();
				$member_id = myUser::getUser ()->getMemberId ();
				$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
				$year_month = date('m-Y');
			}
		}

		if ($ps_customer_id > 0) {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
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

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass ( array (
					'required' => true ) ) );
		}

		$this->formFilter->setWidget ( 'keywords', new sfWidgetFormInputText ( array (), array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ),
				'rel' => 'tooltip',
				'data-original-title' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Input: Student code, Fullname' ) ) ) );

		$this->formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );
		$this->formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		$this->formFilter->setDefault ( 'keywords', $keywords );
		$this->formFilter->setDefault ( 'year_month', $year_month );

		$ps_workplace_id = $this->formFilter->getDefault ( 'ps_workplace_id' );

		$keywords = $this->formFilter->getDefault ( 'keywords' );

		$param_class = array (
				'ps_school_year_id' => $schoolYearsDefault->getId (),
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
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

		$this->formFilter->setDefault ( 'ps_class_id', $class_ids );

		$this->schoolYearsDefault = $schoolYearsDefault;

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'fee_receivable_student_filter[%s]' );

		$class_ids = $this->formFilter->getDefault ( 'ps_class_id' );

		// Chuyển về YYYY-mm-dd
		$date_temp = DateTime::createFromFormat ( 'd-m-Y', '01-' . $year_month );
		$receivable_at_temp = $date_temp->format ( 'Y-m-d' );
		;

		$receivable_at = date ( "Y-m-d", PsDateTime::psDatetoTime ( $receivable_at_temp ) );

		$this->pager = new sfDoctrinePager ( 'Student', 100 );
		$this->pager->setQuery ( Doctrine::getTable ( 'Student' )->setStudentNotInReceiptOfMonth ( $receivable_at, $ps_customer_id, $ps_workplace_id, $class_ids, $keywords ) );
		$this->pager->setPage ( $request->getParameter ( 'page', 1 ) );
		$this->pager->init ();

		$this->list_student = $this->pager->getResults ();

		// Lay danh sach cac khoan phai thu
		$receivable_params = array (
				'ps_school_year_id' => $schoolYearsDefault->getId (),
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'is_activated' => PreSchool::ACTIVE );

		$value_filter = array (
				'year_month' => $year_month,
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_class_id' => $class_ids,
				'keywords' => $keywords );

		$this->setReceivableStudentFilters ( $value_filter );
		// danh sach các khoản phải thu
		$this->date_at = '01-' . $year_month;
		$this->receivables = Doctrine::getTable ( "Receivable" )->getListReceivableByParams ( $receivable_params );
		// danh sach dịch vụ của cơ sở
		// $this->list_service = Doctrine::getTable ( "Service" )->getAllServiceByCustomer ( $schoolYearsDefault->getId (),$ps_customer_id,$ps_workplace_id, $year_month );
		// $this->list_service = Doctrine::getTable("Service")->getListServiceOfSchool($schoolYearsDefault->getId (),$ps_customer_id);
	}

	// Luu khoan phai thu cua thang cho cac hoc sinh duoc chon
	public function executeFeeReceivableStudentSave(sfWebRequest $request) {

		// ID học sinh
		$student_ids = $request->getParameter ( 'ids' );

		$control_filter = $request->getParameter ( 'control_filter' );

		// $control_form = $request->getParameter ( 'control_form' );
		// $service_month = isset ( $control_form ['service'] ) ? $control_form ['service'] : null;

		$receivable_month = isset ( $control_filter ['receivable'] ) ? $control_filter ['receivable'] : null;

		if (! count ( $student_ids )) {

			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'You do not select students to perform' ) );

			$this->redirect ( '@ps_fee_reports_receivable_student_step1' );
		} elseif (! count ( $receivable_month )) {

			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'You do not select receivable to perform' ) );

			$this->redirect ( '@ps_fee_reports_receivable_student_step1' );
		}

		$value_filter = $this->getReceivableStudentFilters ();

		$date_year_month = isset ( $value_filter ['year_month'] ) ? $value_filter ['year_month'] : date ( "m-Y" );

		$date_year_month = '01-' . $date_year_month;

		// Chuyển về YYYY-mm-dd
		$date_temp = DateTime::createFromFormat ( 'd-m-Y', $date_year_month );
		$receivable_at_temp = $date_temp->format ( 'Y-m-d' );
		;

		$receivable_at = date ( "Y-m-d", PsDateTime::psDatetoTime ( $receivable_at_temp ) );

		// Chi lay hoc sinh chua có phieu thu cua tháng >= tháng đang chọn
		$list_student_ids = Doctrine::getTable ( 'Student' )->getStudentIdsNotInReceiptOfMonth ( $receivable_at, $student_ids );

		if (count ( $list_student_ids ) <= 0) {

			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'Error list select student.' ) );

			$this->redirect ( '@ps_fee_reports_receivable_student_step1' );
		}

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			foreach ( $list_student_ids as $_student ) {

				foreach ( $receivable_month as $key_id => $obj ) {

					if (isset ( $obj ['ids'] ) && $obj ['ids'] > 0 && is_numeric ( $obj ['amount'] ) && (PreString::length ( $obj ['note'] ) <= 255)) {

						// Kiem tra khaon phai thu khac nay da ton tai chua
						$receivableStudent = Doctrine::getTable ( 'Receivable' )->checkReceivablesOfMonthExists ( $_student->getId (), $key_id, strtotime ( $date_year_month ) );

						// Neu chua có
						if (! $receivableStudent) {
							// echo $date_year_month;die();
							// dem xem da su dung dich vu nay lan nao chua
							$get_mumber = Doctrine::getTable ( 'ReceivableStudent' )->getCountMumberReceivableStudent ( $key_id, $_student->getId () );

							$receivableStudent = new ReceivableStudent ( false );

							$receivableStudent->setStudentId ( $_student->getId () );
							$receivableStudent->setReceivableId ( $key_id );
							$receivableStudent->setServiceId ( null );
							$receivableStudent->setIsLate ( 0 ); // Ve muon
							$receivableStudent->setIsNumber ( $get_mumber + 1 ); // So lan da ap dung
							$receivableStudent->setByNumber ( 1 ); // So luong du kien ban dau

							// $receivableStudent->setUnitPrice ( 0 ); // Don gia
							$receivableStudent->setUnitPrice ( $obj ["amount"] ); // Don gia

							$receivableStudent->setSpentNumber ( 1 ); // So luong su dung thuc te
							$receivableStudent->setAmount ( $obj ["amount"] ); // Tong tien
							$receivableStudent->setNote ( ( string ) $obj ["note"] );

							$receivableStudent->setReceivableAt ( date ( "Ymd", strtotime ( $date_year_month ) ) );

							$receivableStudent->setReceiptDate ( date ( "Ymd", strtotime ( $date_year_month ) ) );

							$receivableStudent->setUserCreatedId ( sfContext::getInstance ()->getUser ()
								->getGuardUser ()
								->getId () );

							$receivableStudent->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
								->getGuardUser ()
								->getId () );

							$receivableStudent->save ();
						}
					}
				}
				/*
				 * foreach ($service_month as $key_id => $obj) {
				 * if (isset($obj['idss']) && $obj['idss'] > 0) {
				 * // Kiem tra dich vu nay da ton tai chua
				 * $StudentServiceObj = Doctrine_Core::getTable ( 'StudentService' )->checkStudentServiceExits($_student->getId(), $key_id);
				 * if (! $StudentServiceObj) {
				 * $discount = ($obj ['discount'] > 0) ? $obj ['discount'] : 0;
				 * $discount_amount = (is_numeric ( $obj ['fixed'] )) ? $obj ['fixed'] : 0;
				 * $StudentService = new StudentService ( false );
				 * $StudentService->setStudentId ( $_student->getId() );
				 * $StudentService->setServiceId ( $key_id );
				 * $StudentService->setDiscountAmount ( $discount_amount );
				 * $StudentService->setDiscount ( $discount );
				 * $StudentService->setUserCreatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()->getId () );
				 * $StudentService->setUserUpdatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()->getId () );
				 * $StudentService->save ();
				 * }
				 * }
				 * }
				 */
			}

			$conn->commit ();
		} catch ( Exception $e ) {

			$conn->rollback ();

			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'Add receivable for month fail.' ) );

			$this->redirect ( '@ps_fee_reports_receivable_student_step1' );
		}

		$this->getUser ()
			->setFlash ( 'notice', $this->getContext ()
			->getI18N ()
			->__ ( 'Add receivable for month successfully.' ) );

		$this->redirect ( '@ps_fee_reports_receivable_student_step1' );
	}

	// Load danh sach hoc sinh
	public function executeFeeReceivableStudentSearch(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$value_filter = $this->getReceivableStudentFilters ();

			if (count ( $value_filter )) {

				$ps_customer_id = $value_filter ['ps_customer_id'];

				$ps_workplace_id = $value_filter ['ps_workplace_id'];

				$year_month = $value_filter ['year_month'];

				$class_ids = $value_filter ['ps_class_id'];

				$keywords = $value_filter ['keywords'];
			} else {
				echo 'Error';
				exit ();
			}

			$this->pager = new sfDoctrinePager ( 'Student', 100 );
			$this->pager->setQuery ( Doctrine::getTable ( 'Student' )->setStudentNotInReceiptOfMonth ( '01-' . $year_month, $ps_customer_id, $ps_workplace_id, $class_ids, $keywords ) );
			$this->pager->setPage ( $request->getParameter ( 'page', 1 ) );
			$this->pager->init ();

			$this->list_student = $this->pager->getResults ();

			$file_result = ($html == 'table') ? 'table_relative' : 'list_relative_main';

			$ktime = '01-' . $value_filter ['year_month'];
			$ktime = strtotime ( $ktime );

			return $this->renderPartial ( 'psFeeReports/list_student', array (
					'ps_student' => $this->ps_student,
					'list_student' => $this->list_student,
					'pager' => $this->pager,
					'ktime' => $ktime ) );
		} else {
			exit ( 0 );
		}
	}

	// Chi tiet phieu bao
	public function executeDetail(sfWebRequest $request) {

		// $this->exportReport();
		$this->ps_fee_reports = $this->getRoute ()
			->getObject ();

		if (! $this->ps_fee_reports) {
			// $this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			$this->getUser ()
				->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
			$this->redirect ( '@ps_fee_reports' );
		}

		if (! myUser::checkAccessObject ( $this->ps_fee_reports->getStudent (), 'PS_FEE_REPORT_FILTER_SCHOOL' ) || $this->ps_fee_reports->getStudent ()
			->getDeletedAt ()) {
			$this->getUser ()
				->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
			$this->redirect ( '@ps_fee_reports' );
		} else {

			$this->student = $this->ps_fee_reports->getStudent ();

			$receiptPrevDate = null;

			$this->balanceAmount = 0;

			$this->collectedAmount = 0;

			// Tong so tien cua mot phiếu
			$this->totalAmount = 0;

			// Kiem tra thoi gian tam dung nghi hoc

			// Thang bao phi
			$this->receivable_at = $this->ps_fee_reports->getReceivableAt ();
			$student_id = $this->ps_fee_reports->getStudentId ();

			$int_receivable_at = PsDateTime::psDatetoTime ( $this->receivable_at );

			// Lat tong so tien du kien cua 1 thang receivable_at
			$this->totalAmountReceivableAt = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentInMonth ( $student_id, $int_receivable_at );

			// Lay phieu thu cua thang duoc chon
			$this->receipt = $this->student->findReceiptByDate ( $int_receivable_at );

			if (! $this->receipt || ($this->receipt && $this->receipt->getPaymentStatus () != PreSchool::ACTIVE)) {

				// Lay lop hoc cua hoc sinh tai thoi diem bao phi
				$infoClass = $this->student->getMyClassByStudent ( $this->receivable_at );

				// Lay thong tin co so dao tao
				$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $infoClass->getPsWorkplaceId () );

				$student_month = $this->student->getPrecedingMontOfStudent ( $int_receivable_at, $psWorkPlace->getConfigStartDateSystemFee () );

				// Ngay cua phieu thu gan nhat
				$receiptPrevDate = $student_month ['receiptPrevDate'];

				// Dư của phiếu thu gần đây nhất
				$this->balanceAmount = $student_month ['BalanceAmount'];

				// Đã nộp của phiếu thu gần đây nhất
				$this->collectedAmount = $student_month ['CollectedAmount'];
			}

			// Lay danh sach cac khoan phi cua phieu bao
			$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );

			// // Tong so tien cua mot phiếu (tháng đang chạy + các tháng trước chưa thanh toán)
			$totalAmount = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );

			if ($totalAmount)
				$this->totalAmount = $totalAmount->getTotalAmount ();

			// Tong_tien_cac_thang_truoc =

			$this->form = new ReceiptForm ( $this->receipt );
		}
	}

	// Xóa phiếu báo - phiếu thu
	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		$ps_fee_reports = $this->getRoute ()
			->getObject ();

		$ps_student = $ps_fee_reports->getStudent ();

		$this->forward404Unless ( myUser::checkAccessObject ( $ps_student, 'PS_FEE_REPORT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		// Lấy phiếu thu của tháng chọn xóa
		$receipt = $ps_student->findReceiptByDate ( PsDateTime::psDatetoTime ( $ps_fee_reports->getReceivableAt () ) );

		// Kiem tra neu phieu thu nay da thanh toan thi khong cho xoa
		if ($receipt && $receipt->getPaymentStatus () == PreSchool::ACTIVE) {
			$this->getUser ()
				->setFlash ( 'error', 'This month has been paid. You can not delete.' );
			$this->redirect ( '@ps_fee_reports' );
		}

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			$receiptPrevDate = null;

			if ($receipt) {

				$int_receivable_at = PsDateTime::psDatetoTime ( $ps_fee_reports->getReceivableAt () );

				$notice = $this->getContext ()->getI18N ()->__ ( 'Delete the tuition fee notice %value% successfully.', array (
						'%value%' => $this->getContext ()
							->getI18N ()
							->__ ( 'month' ) . ' ' . PsDateTime::psTimetoDate ( $int_receivable_at, "m-Y" ) . ' ' . $this->getContext ()
							->getI18N ()
							->__ ( 'of student' ) . ' ' . $ps_student->getFirstName () . ' ' . $ps_student->getLastName () ) );

				// Tìm phiếu thu chưa thanh toán gần phiếu báo được chọn xóa nhất
				$receipt_prev = $ps_student->findReceiptPrevOfStudentByDate ( $int_receivable_at );

				// Ngay cua phieu thu gan nhat
				$receiptPrevDate = $receipt_prev ? $receipt_prev->getReceiptDate () : null;

				// Lay danh sach cac khoan phi cua phieu bao				
				//$receivable_students = Doctrine::getTable ( 'ReceivableStudent' )->getObjectReceivableStudentOfMonth ( $ps_student->getId (), $ps_fee_reports->getReceivableAt (), $receiptPrevDate );
				$receivable_students = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentInMonth ( $ps_student->getId (), $ps_fee_reports->getReceivableAt ());

				// Xoa phieu bao
				if ($this->getRoute ()->getObject ()->delete () && $receipt->delete ()) {

					// Xoa du lieu trong ReceivableStudent
					foreach ( $receivable_students as $receivable_student ) {
						$receivable_student->delete ();
					}
				}

				// Xóa trong ps_fee_reports_flag_my_class ? Không cần. Khi chọn lớp để chạy báo phí vẫn cho hiển thị các lớp đã từng chạy
				$this->getUser ()->setFlash ( 'notice', $notice );
			}

			$conn->commit ();
		} catch ( Exception $e ) {

			$conn->rollback ();

			$this->getUser ()
				->setFlash ( 'error', 'The item was deleted fail.' );
		}

		$this->redirect ( '@ps_fee_reports' );
	}

	public function executeBatch(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		if (! $ids = $request->getParameter ( 'ids' )) {
			$this->getUser ()
				->setFlash ( 'error', 'You must at least select one item.' );

			$this->redirect ( '@ps_fee_reports' );
		}

		echo 'batch_action:' . $request->getParameter ( 'batch_action' );

		die ();

		if (! $action = $request->getParameter ( 'batch_action' )) {
			$this->getUser ()
				->setFlash ( 'error', 'You must select an action to execute on the selected items.' );
			$this->redirect ( '@ps_fee_reports' );
		}

		if (! method_exists ( $this, $method = 'execute' . ucfirst ( $action ) )) {
			throw new InvalidArgumentException ( sprintf ( 'You must create a "%s" method for action "%s"', $method, $action ) );
		}

		if (! $this->getUser ()
			->hasCredential ( $this->configuration->getCredentials ( $action ) )) {
			$this->forward ( sfConfig::get ( 'sf_secure_module' ), sfConfig::get ( 'sf_secure_action' ) );
		}

		$validator = new sfValidatorDoctrineChoice ( array (
				'multiple' => true,
				'model' => 'PsFeeReports' ) );
		try {
			// validate ids
			$ids = $validator->clean ( $ids );

			// execute batch
			$this->$method ( $request );
		} catch ( sfValidatorError $e ) {
			$this->getUser ()
				->setFlash ( 'error', 'A problem occurs when deleting the selected items as some items do not exist anymore.' );
		}

		$this->redirect ( '@ps_fee_reports' );
	}

	// Chay xu ly bao phi cho nhung hoc sinh trong lop duoc chon
	// Dang xu ly do
	public function executeBatchProcessFee(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$access = $this->getUser ()->hasCredential ( array ('PS_FEE_REPORT_ADD','PS_FEE_REPORT_EDIT' ), false );

			if (! $access) {
				$this->getUser ()->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
			} else {

				// Get filters
				$ps_fee_reports_filters = $request->getParameter ( 'ps_fee_reports_filters' );

				// Check ps_myclass_id and receivable_at
				$ps_myclass_id = $ps_fee_reports_filters ['ps_class_id'];

				if ($ps_myclass_id <= 0) {
					$this->getUser ()
						->setFlash ( 'error', 'The system does not identify the class.', false );
				} else {

					$receivable_at = $ps_fee_reports_filters ['receivable_at'];

					$my_class = Doctrine::getTable ( 'MyClass' )->findOneById ( $ps_myclass_id );

					if (! myUser::checkAccessObject ( $my_class, 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
						$this->getUser ()
							->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
					} else {

						// Lay khoan phai thu khac cua thang ap dung cho cac hoc sinh nay
						$params = array ();
						$params ['ps_customer_id'] = $ps_fee_reports_filters ['ps_customer_id'];
						$params ['ps_workplace_id'] = $ps_fee_reports_filters ['ps_workplace_id'];
						$params ['ps_school_year_id'] = $ps_fee_reports_filters ['ps_school_year_id'];
						$params ['date'] = PsDateTime::psDatetoTime ( $ps_fee_reports_filters ['receivable_at'] );
						$params ['ps_myclass_id'] = $ps_fee_reports_filters ['ps_class_id'];

						$student_ids = $request->getParameter ( 'ids' );

						if ($this->processFeeReport ( $student_ids, $params, $receivable_at )) {
							$this->getUser ()
								->setFlash ( 'notice', 'Processed successfully.', false );
						} else {
							$this->getUser ()
								->setFlash ( 'error', 'Process failed.', false );
						}
					}
					// Kiem tra xem bao phi cua lop nay da chay lan nao chua - Co can luu history cac lan chay?
				}
			}

			$helper = new psFeeReportsGeneratorHelper ();

			return $this->renderPartial ( 'psFeeReports/result_process', array (
					'sort' => $this->getSort (),
					'helper' => $helper,
					'pager' => $this->getPager () ) );
		} else {
		}
	}

	// ENd Function

	// In phieu thu ra file cua 1 hoc sinh
	public function executePrintFeeReceipt(sfWebRequest $request) {

		$this->ps_fee_reports = $this->getRoute ()
			->getObject ();

		if (! $this->ps_fee_reports) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}

		$this->student = $this->ps_fee_reports->getStudent ();

		if (! myUser::checkAccessObject ( $this->student, 'PS_FEE_REPORT_FILTER_SCHOOL' ) || $this->student->getDeletedAt ()) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}

		// Lay phieu thu cua thang duoc chon
		$date_ExportReceipt = $this->ps_fee_reports->getReceivableAt ();

		$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );

		$student_id = $this->ps_fee_reports->getStudentId ();

		// Lay phieu thu(tat ca cac trang thai thanh toan
		$this->receipt = $this->student->findReceiptByDate ( $int_date_ExportReceipt );

		$data_fee = array ();

		$receiptPrevDate = null;

		$this->collectedAmount = 0;
		$this->balanceAmount = 0;
		$this->pricePaymentLate = $this->totalAmount = 0;
		$this->balance_last_month_amount = $total_price = 0;
		// Lay lop hoc cua hoc sinh tai thoi diem bao phi
		$infoClass = Doctrine::getTable ( "StudentClass" )->getClassActivateByStudent ( $student_id, $date_ExportReceipt );
		// Nếu không xác định được lớp
		if (! $infoClass) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}

		// Lay thong tin co so dao tao
		//$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $infoClass->getPsWorkplaceId () );

		$this->class_name = $infoClass->getName ();

		$ps_customer_id = $this->student->getPsCustomerId ();

		// thong tin tieu de
		$psClass = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, $infoClass->getClassId (),$infoClass->getPsWorkplaceId () );

		//$this->ps_customer = Doctrine::getTable ( 'PsCustomer' )->findOneById ( $ps_customer_id );

		//$this->ps_workplace = $psWorkPlace;

		$student_month = $this->student->getPrecedingMontOfStudent ( $int_date_ExportReceipt, $psClass->getConfigStartDateSystemFee () );

		$receiptFirst = $student_month ['receiptFirst'];

		// Ngay cua - Phiếu A
		$receiptPrevDate = $student_month ['receiptPrevDate'];

		// Đã nộp của - Phiếu A
		$this->collectedAmount = $student_month ['CollectedAmount'];

		if ($receiptFirst) { // Nếu là phiếu đầu tiên nhất

			$this->balanceAmount = $this->receipt->getBalanceLastMonthAmount ();

			// Dư của phiếu cận Phiếu A nhất
			$this->balance_last_month_amount = $this->receipt->getBalanceLastMonthAmount ();
		} else {

			// Dư của - Phiếu A
			if ($this->receipt->getPaymentStatus () != PreSchool::ACTIVE) {

				$this->balanceAmount = $student_month ['balance_last_month_amount'];

				$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
			} else {

				$this->balanceAmount = $student_month ['BalanceAmount'];

				// Dư của phiếu cận Phiếu A nhất
				$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
			}

			// Tìm phiếu trước của phiếu A
			$student_month_2 = $this->student->getPrecedingMontOfStudent ( $receiptPrevDate, $psClass->getConfigStartDateSystemFee () );

			if ($student_month_2 ['receiptFirst']) {

				// Lay phieu tháng $receiptPrevDate
				$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $receiptPrevDate );

				$this->balanceAmount = $receipt_2->getBalanceLastMonthAmount (); // $receipt_2->getBalanceAmount ();

				// Dư của phiếu cận Phiếu A nhất
				$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
			} else {

				$student_month_2 = $this->student->getPrecedingMontOfStudent ( $student_month_2 ['receiptPrevDate'], $psClass->getConfigStartDateSystemFee () );

				if ($student_month_2 ['receiptFirst']) {

					// Lay phieu tháng $student_month_2['receiptPrevDate']
					$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $receiptPrevDate );

					$this->balanceAmount = $receipt_2->getBalanceAmount ();

					// Dư của phiếu cận Phiếu A nhất
					$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
				}
			}
		}

		// lay so tien phat nop hoc phi muon
		$psConfigLatePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplaceDate ( $psClass->getWpId (), date ( 'Y-m-d' ) );

		$priceConfigLatePayment = 0;
		if ($psConfigLatePayment)
			$priceConfigLatePayment = $psConfigLatePayment->getPrice ();

		$pricePaymentLate = 0;
		// Nếu cấu hình là kiểu lũy tiến, thì phải xem các tháng trước đó đã thanh toán hay chưa

		$status_payment = $this->receipt->getPaymentStatus ();

		// / Trang thai chua thanh toan
		if ($status_payment == PreSchool::NOT_ACTIVE) {

			if ($psClass->getConfigChooseChargePaylate () == 1) {
				// lấy ra tháng đã thanh toán gần nhất so với phiếu thu hiện tại
				$check_receipt_date = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentIsPayment ( $student_id );

				if ($check_receipt_date) { // Tính khoảng cách giữa 2 tháng

					$receipt_date = $check_receipt_date->getReceiptDate ();

					$datetime1 = date_create ( $receipt_date );
					$datetime2 = date_create ( $date_ExportReceipt );
					$interval = date_diff ( $datetime1, $datetime2 );

					$check_month = $interval->format ( '%m' );
				} else { // Nếu chưa từng thanh toán lần nào, thì đếm xem có bao nhiêu tháng trước đó chưa thanh toán

					$check_receipt_date = Doctrine::getTable ( 'Receipt' )->countReceiptOfStudentIsPayment ( $student_id, $int_date_ExportReceipt );

					$check_month = count ( $check_receipt_date ) + 1;
				}

				if ($check_month > 1) {

					for($i = 1; $i < $check_month; $i ++) {

						$track_at = date_create ( $date_ExportReceipt );

						date_modify ( $track_at, "-$i month" );

						$date_receipt = date_format ( $track_at, "Y-m-d" );

						$latePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplacePrevMonth ( $psClass->getWpId (), $date_receipt );
						if ($latePayment) {
							$total_price += $latePayment->getPrice (); // Tính tổng khoản phạt nộp muộn học phí
						}
					}
					$pricePaymentLate = $total_price;
				}
			}

			// Tổng số tiền tính phí nộp chậm
			$this->psConfigLatePayment = $priceConfigLatePayment + $pricePaymentLate;
		} else {
			// Tổng số tiền tính phí nộp chậm
			$this->psConfigLatePayment = $this->receipt->getLatePaymentAmount ();
		}

		// Lay danh sach cac khoan phi cua phieu bao
		$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $date_ExportReceipt, $receiptPrevDate );
		
		//echo $date_ExportReceipt.'__'.$receiptPrevDate;
		
		$data_fee ['balanceAmount'] = $this->balanceAmount;
		$data_fee ['collectedAmount'] = $this->collectedAmount;
		$data_fee ['balance_last_month_amount'] = $this->balance_last_month_amount;
		$data_fee ['receivable_student'] = $this->receivable_student;
		$data_fee ['ps_fee_receipt'] = $this->receipt;
		$data_fee ['ps_fee_reports'] = $this->ps_fee_reports;

		$data_fee ['psConfigLatePayment'] = $this->psConfigLatePayment;

		$this->data = $data_fee;

		return $this->renderPartial ( 'psFeeReports/printFeeReceiptLayout', array (
				'receipt' => $this->receipt,
				'data' => $this->data,
				'psClass' => $psClass,
				'student' => $this->student ) );
	}

	// In phieu thu ra file cua 1 lop hoc
	public function executePrintFeeReceiptByClass(sfWebRequest $request) {

		// Get filters
		$ps_fee_receipt_filters = $request->getParameter ( 'receipt_filters' );

		$receivable_at = $ps_fee_receipt_filters ['receivable_at']; // tháng xuất phiếu thu
		$ps_school_year_id = $ps_fee_receipt_filters ['ps_school_year_id'];
		$ps_customer_id = $ps_fee_receipt_filters ['ps_customer_id'];
		$ps_workplace_id = $ps_fee_receipt_filters ['ps_workplace_id'];
		$ps_class_id = $ps_fee_receipt_filters ['ps_class_id'];

		// Lay phieu thu cua thang duoc chon
		$date_ExportReceipt = $receivable_at;

		$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );
		
		if ($ps_class_id <= 0) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}
		/*
		$ps_class = Doctrine::getTable ( 'MyClass' )->findOneById($ps_class_id);
		
		if (! myUser::checkAccessObject ( $ps_class, 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}
		// Lay thong tin co so dao tao
		
		$ps_workplace_id = $ps_class->getPsClassRooms()->getPsWorkplaceId();
		
		$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $ps_workplace_id,'id, config_start_date_system_fee' );
		
		if (!$psWorkPlace) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}
		*/
		$psClass = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, $ps_class_id );
		if (!$psClass) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}
		$configStart = $psClass->getConfigStartDateSystemFee ();
		
		$all_data_fee = array ();

		// lay danh sach hoc sinh trong lop
		$list_student = Doctrine::getTable ( 'Student' )->getObjectStudentByClass ( $ps_customer_id, $ps_class_id, $receivable_at );

		foreach ( $list_student as $student ) {

			$data_fee = array ();

			$student_id = $student->getId ();

			$receiptPrevDate = null;
			$this->collectedAmount = 0;
			$this->balanceAmount = 0;
			$this->totalAmount = 0;
			$this->balance_last_month_amount = 0;
			// Lay phieu thu(tat ca cac trang thai thanh toan
			$this->receipt = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $int_date_ExportReceipt );

			if ($this->receipt) {

				$data_fee ['id'] = $student_id;

				$student_month = $student->getPrecedingMontOfStudent ( $int_date_ExportReceipt, $configStart );

				$receiptFirst = $student_month ['receiptFirst'];

				// Ngay cua - Phiếu A
				$receiptPrevDate = $student_month ['receiptPrevDate'];

				// Đã nộp của - Phiếu A
				$this->collectedAmount = $student_month ['CollectedAmount'];

				if ($receiptFirst) { // Nếu là phiếu đầu tiên nhất

					$this->balanceAmount = $this->receipt->getBalanceLastMonthAmount ();

					// Dư của phiếu cận Phiếu A nhất
					$this->balance_last_month_amount = $this->receipt->getBalanceLastMonthAmount ();
				} else {

					// Dư của - Phiếu A
					if ($this->receipt->getPaymentStatus () != PreSchool::ACTIVE) {

						$this->balanceAmount = $student_month ['balance_last_month_amount'];

						$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
					} else {

						$this->balanceAmount = $student_month ['BalanceAmount'];

						// Dư của phiếu cận Phiếu A nhất
						$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
					}

					// Tìm phiếu trước của phiếu A
					$student_month_2 = $student->getPrecedingMontOfStudent ( $receiptPrevDate, $configStart );

					if ($student_month_2 ['receiptFirst']) {

						// Lay phieu tháng $receiptPrevDate
						$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $receiptPrevDate );

						$this->balanceAmount = $receipt_2->getBalanceLastMonthAmount (); // $receipt_2->getBalanceAmount ();

						// Dư của phiếu cận Phiếu A nhất
						$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
					} else {

						$student_month_2 = $student->getPrecedingMontOfStudent ( $student_month_2 ['receiptPrevDate'], $configStart );

						if ($student_month_2 ['receiptFirst']) {

							// Lay phieu tháng $student_month_2['receiptPrevDate']
							$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $receiptPrevDate );

							$this->balanceAmount = $receipt_2->getBalanceAmount ();

							// Dư của phiếu cận Phiếu A nhất
							$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
						}
					}
				}
				$status_payment = $this->receipt->getPaymentStatus ();
				if ($status_payment == PreSchool::NOT_ACTIVE) {
					// Tổng số tiền tính phí nộp chậm
					$this->psConfigLatePayment = 0;
				} else {
					// Tổng số tiền tính phí nộp chậm
					$this->psConfigLatePayment = $this->receipt->getLatePaymentAmount ();
				}

				// Lay phieu bao của thang
				$this->ps_fee_reports = Doctrine::getTable ( 'PsFeeReports' )->findPsFeeReportOfStudentByDate ( $student_id, PsDateTime::psDatetoTime ( $date_ExportReceipt ) );

				// Lay danh sach cac khoan phi cua phieu bao
				$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $date_ExportReceipt, $receiptPrevDate );

				$data_fee ['balanceAmount'] = $this->balanceAmount;
				$data_fee ['collectedAmount'] = $this->collectedAmount;
				$data_fee ['balance_last_month_amount'] = $this->balance_last_month_amount;
				$data_fee ['receivable_student'] = $this->receivable_student;
				$data_fee ['ps_fee_receipt'] = $this->receipt;
				$data_fee ['ps_fee_reports'] = $this->ps_fee_reports;
				$data_fee ['psConfigLatePayment'] = $this->psConfigLatePayment;
				$data_fee ['student'] = $student;

				array_push ( $all_data_fee, $data_fee );
			}
		}

		return $this->renderPartial ( 'psFeeReports/printFeeReceiptByClassLayout', array (
				'all_data_fee' => $all_data_fee,
				'psClass' => $psClass ) );
	}

	// In phieu thu ra file cua co so
	public function executePrintFeeReceiptByWorkplace(sfWebRequest $request) {

		// Get filters
		$ps_fee_receipt_filters = $request->getParameter ( 'receipt_filters' );

		$receivable_at = $ps_fee_receipt_filters ['receivable_at']; // tháng xuất phiếu thu
		$ps_school_year_id = $ps_fee_receipt_filters ['ps_school_year_id'];
		$ps_customer_id = $ps_fee_receipt_filters ['ps_customer_id'];
		$ps_workplace_id = $ps_fee_receipt_filters ['ps_workplace_id'];
		$ps_class_id = $ps_fee_receipt_filters ['ps_class_id'];

		// Lay phieu thu cua thang duoc chon
		$date_ExportReceipt = $receivable_at;

		$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );
		/*
		// Lay thong tin co so dao tao
		$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $ps_workplace_id );

		if (! myUser::checkAccessObject ( $psWorkPlace, 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}
		*/
		$psClass = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, $ps_class_id );
		
		$ConfigStartDateSystemFee = $psClass->getConfigStartDateSystemFee ();
		
		$all_data_fee = array ();

		// lay danh sach hoc sinh trong co so
		$list_student = Doctrine::getTable ( 'Student' )->getAllStudentsByCustomerIdWithDate ( $ps_customer_id, $ps_workplace_id, $int_date_ExportReceipt );

		foreach ( $list_student as $student ) {

			$data_fee = array ();

			$student_id = $student->getId ();

			$receiptPrevDate = null;
			$this->collectedAmount = 0;
			$this->balanceAmount = 0;
			$this->totalAmount = 0;
			$this->balance_last_month_amount = 0;
			// Lay phieu thu(tat ca cac trang thai thanh toan
			$this->receipt = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $int_date_ExportReceipt );

			if ($this->receipt) {

				$data_fee ['id'] = $student_id;

				$student_month = $student->getPrecedingMontOfStudent ( $int_date_ExportReceipt, $ConfigStartDateSystemFee );

				$receiptFirst = $student_month ['receiptFirst'];

				// Ngay cua - Phiếu A
				$receiptPrevDate = $student_month ['receiptPrevDate'];

				// Đã nộp của - Phiếu A
				$this->collectedAmount = $student_month ['CollectedAmount'];

				if ($receiptFirst) { // Nếu là phiếu đầu tiên nhất

					$this->balanceAmount = $this->receipt->getBalanceLastMonthAmount ();

					// Dư của phiếu cận Phiếu A nhất
					$this->balance_last_month_amount = $this->receipt->getBalanceLastMonthAmount ();
				} else {

					// Dư của - Phiếu A
					if ($this->receipt->getPaymentStatus () != PreSchool::ACTIVE) {

						$this->balanceAmount = $student_month ['balance_last_month_amount'];

						$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
					} else {

						$this->balanceAmount = $student_month ['BalanceAmount'];

						// Dư của phiếu cận Phiếu A nhất
						$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
					}

					// Tìm phiếu trước của phiếu A
					$student_month_2 = $student->getPrecedingMontOfStudent ( $receiptPrevDate, $psClass->getConfigStartDateSystemFee () );

					if ($student_month_2 ['receiptFirst']) {

						// Lay phieu tháng $receiptPrevDate
						$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $receiptPrevDate );

						$this->balanceAmount = $receipt_2->getBalanceLastMonthAmount (); // $receipt_2->getBalanceAmount ();

						// Dư của phiếu cận Phiếu A nhất
						$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
					} else {

						$student_month_2 = $student->getPrecedingMontOfStudent ( $student_month_2 ['receiptPrevDate'], $psClass->getConfigStartDateSystemFee () );

						if ($student_month_2 ['receiptFirst']) {

							// Lay phieu tháng $student_month_2['receiptPrevDate']
							$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $receiptPrevDate );

							$this->balanceAmount = $receipt_2->getBalanceAmount ();

							// Dư của phiếu cận Phiếu A nhất
							$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
						}
					}
				}
				// Nếu cấu hình là kiểu lũy tiến, thì phải xem các tháng trước đó đã thanh toán hay chưa
				$status_payment = $this->receipt->getPaymentStatus ();
				if ($status_payment == PreSchool::NOT_ACTIVE) {
					// Tổng số tiền tính phí nộp chậm
					$this->psConfigLatePayment = 0;
				} else {
					// Tổng số tiền tính phí nộp chậm
					$this->psConfigLatePayment = $this->receipt->getLatePaymentAmount ();
				}

				// Lay phieu bao của thang
				$this->ps_fee_reports = Doctrine::getTable ( 'PsFeeReports' )->findPsFeeReportOfStudentByDate ( $student_id, PsDateTime::psDatetoTime ( $date_ExportReceipt ) );

				// Lay danh sach cac khoan phi cua phieu bao
				$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $date_ExportReceipt, $receiptPrevDate );

				/*
				 * // Tổng số tiền các dịch vụ của phiếu
				 * $totalAmount = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentOfMonth ( $student_id, $date_ExportReceipt, $receiptPrevDate );
				 * $data_fee ['totalAmount'] = ($totalAmount) ? $totalAmount->getTotalAmount () : 0;
				 * $data_fee ['totalAmountReceivableAt'] = ($this->totalAmountReceivableAt) ? $this->totalAmountReceivableAt->getTotalAmount () : 0;
				 * // Lay tong so tien du kien cua 1 thang receivable_at
				 * $this->totalAmountReceivableAt = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentInMonth ( $student_id, $int_date_ExportReceipt );
				 */

				$data_fee ['balanceAmount'] = $this->balanceAmount;
				$data_fee ['collectedAmount'] = $this->collectedAmount;
				$data_fee ['balance_last_month_amount'] = $this->balance_last_month_amount;
				$data_fee ['receivable_student'] = $this->receivable_student;
				$data_fee ['ps_fee_receipt'] = $this->receipt;
				$data_fee ['ps_fee_reports'] = $this->ps_fee_reports;
				$data_fee ['psConfigLatePayment'] = $this->psConfigLatePayment;
				$data_fee ['student'] = $student;

				array_push ( $all_data_fee, $data_fee );
			}
		}

		return $this->renderPartial ( 'psFeeReports/printFeeReceiptByClassLayout', array (
				'all_data_fee' => $all_data_fee,
				'psClass' => $psClass ) );
	}

	// In phieu bao ra file cua 1 hoc sinh
	public function executePrintFeeReport(sfWebRequest $request) {

		$this->ps_fee_reports = $this->getRoute ()
			->getObject ();

		if (! $this->ps_fee_reports) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}

		$this->student = $this->ps_fee_reports->getStudent ();

		if (! myUser::checkAccessObject ( $this->student, 'PS_FEE_REPORT_FILTER_SCHOOL' ) || $this->student->getDeletedAt ()) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}

		// Lay phieu thu cua thang duoc chon
		$date_ExportReceipt = $this->ps_fee_reports->getReceivableAt ();

		$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );

		$student_id = $this->ps_fee_reports->getStudentId ();

		// Lay phieu thu(tat ca cac trang thai thanh toan
		$this->receipt = $this->student->findReceiptByDate ( $int_date_ExportReceipt );

		$data_fee = array ();

		$receiptPrevDate = null;

		$this->collectedAmount = 0;
		$this->balanceAmount = 0;
		$this->totalAmount = 0;
		$this->balance_last_month_amount = 0;
		// Lay lop hoc cua hoc sinh tai thoi diem bao phi
		$infoClass = $this->student->getMyClassByStudent ( $date_ExportReceipt );

		// Lay thong tin co so dao tao
		//$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $infoClass->getPsWorkplaceId () );

		$this->class_name = $infoClass->getName ();

		$ps_customer_id = $this->student->getPsCustomerId ();

		// thong tin tieu de
		$psClass = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, $infoClass->getClassId () );

		//$this->ps_customer = Doctrine::getTable ( 'PsCustomer' )->findOneById ( $ps_customer_id );

		//$this->ps_workplace = $psWorkPlace;

		$student_month = $this->student->getPrecedingMontOfStudent ( $int_date_ExportReceipt, $psClass->getConfigStartDateSystemFee () );

		$receiptFirst = $student_month ['receiptFirst'];

		// Ngay cua - Phiếu A
		$receiptPrevDate = $student_month ['receiptPrevDate'];

		// Đã nộp của - Phiếu A
		$this->collectedAmount = $student_month ['CollectedAmount'];

		if ($receiptFirst) { // Nếu là phiếu đầu tiên nhất

			$this->balanceAmount = $this->receipt->getBalanceLastMonthAmount ();

			// Dư của phiếu cận Phiếu A nhất
			$this->balance_last_month_amount = $this->receipt->getBalanceLastMonthAmount ();
		} else {

			// Dư của - Phiếu A
			if ($this->receipt->getPaymentStatus () != PreSchool::ACTIVE) {

				$this->balanceAmount = $student_month ['balance_last_month_amount'];

				$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
			} else {

				$this->balanceAmount = $student_month ['BalanceAmount'];

				// Dư của phiếu cận Phiếu A nhất
				$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
			}

			// Tìm phiếu trước của phiếu A
			$student_month_2 = $this->student->getPrecedingMontOfStudent ( $receiptPrevDate, $psClass->getConfigStartDateSystemFee () );

			if ($student_month_2 ['receiptFirst']) {

				// Lay phieu tháng $receiptPrevDate
				$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $receiptPrevDate );

				$this->balanceAmount = $receipt_2->getBalanceLastMonthAmount (); // $receipt_2->getBalanceAmount ();

				// Dư của phiếu cận Phiếu A nhất
				$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
			} else {

				$student_month_2 = $this->student->getPrecedingMontOfStudent ( $student_month_2 ['receiptPrevDate'], $psClass->getConfigStartDateSystemFee () );

				if ($student_month_2 ['receiptFirst']) {

					// Lay phieu tháng $student_month_2['receiptPrevDate']
					$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $receiptPrevDate );

					$this->balanceAmount = $receipt_2->getBalanceAmount ();

					// Dư của phiếu cận Phiếu A nhất
					$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
				}
			}
		}

		// Lay danh sach cac khoan phi cua phieu bao
		$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $date_ExportReceipt, $receiptPrevDate );

		$data_fee ['balanceAmount'] = $this->balanceAmount;
		$data_fee ['collectedAmount'] = $this->collectedAmount;
		$data_fee ['receivable_student'] = $this->receivable_student;
		$data_fee ['ps_fee_receipt'] = $this->receipt;
		$data_fee ['ps_fee_reports'] = $this->ps_fee_reports;
		$data_fee ['balance_last_month_amount'] = $this->balance_last_month_amount;

		$this->data = $data_fee;

		return $this->renderPartial ( 'psFeeReports/printFeeReportLayout', array (
				'receipt' => $this->receipt,
				'data' => $this->data,
				'psClass' => $psClass,
				'student' => $this->student ) );
	}

	// In phieu bao ra file cua 1 lop hoc
	public function executePrintFeeReportByClass(sfWebRequest $request) {

		// Get filters
		$ps_fee_receipt_filters = $request->getParameter ( 'receipt_filters' );

		$receivable_at 		= $ps_fee_receipt_filters ['receivable_at']; // tháng xuất phiếu thu
		
		$ps_customer_id 	= $ps_fee_receipt_filters ['ps_customer_id'];
		$ps_workplace_id 	= $ps_fee_receipt_filters ['ps_workplace_id'];
		$ps_class_id 		= $ps_fee_receipt_filters ['ps_class_id'];
		
		if ($ps_class_id <= 0) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}
		
		$ps_class = Doctrine::getTable ( 'MyClass' )->findOneById($ps_class_id);
		
		if (! myUser::checkAccessObject ( $ps_class, 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}
		
		$psClass = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, $ps_class_id );
		
		// Lay phieu thu cua thang duoc chon
		$date_ExportReceipt = $receivable_at;
		
		$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );
		
		// Lay thong tin co so dao tao
		//$ps_workplace_id = $ps_class->getPsClassRooms()->getPsWorkplaceId();
		
		//$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $ps_workplace_id );
		
		/*
		if (! myUser::checkAccessObject ( $psWorkPlace, 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}
		*/
		if (!$psClass) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}
		
		$ConfigStartDateSystemFee = $psClass->getConfigStartDateSystemFee ();		

		$all_data_fee = array ();

		// lay danh sach hoc sinh trong lop
		$list_student = Doctrine::getTable ( 'Student' )->getObjectStudentByClass ( $ps_customer_id, $ps_class_id, $receivable_at );

		foreach ( $list_student as $student ) {

			$data_fee = array ();

			$student_id = $student->getId ();

			$receiptPrevDate = null;
			$this->collectedAmount = 0;
			$this->balanceAmount = 0;
			$this->totalAmount = 0;
			$this->balance_last_month_amount = 0;
			// Lay phieu thu(tat ca cac trang thai thanh toan
			$this->receipt = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $int_date_ExportReceipt );

			if ($this->receipt) {

				$data_fee ['id'] = $student_id;

				$student_month = $student->getPrecedingMontOfStudent ( $int_date_ExportReceipt, $ConfigStartDateSystemFee );

				$receiptFirst = $student_month ['receiptFirst'];

				// Ngay cua - Phiếu A
				$receiptPrevDate = $student_month ['receiptPrevDate'];

				// Đã nộp của - Phiếu A
				$this->collectedAmount = $student_month ['CollectedAmount'];

				if ($receiptFirst) { // Nếu là phiếu đầu tiên nhất

					$this->balanceAmount = $this->receipt->getBalanceLastMonthAmount ();

					// Dư của phiếu cận Phiếu A nhất
					$this->balance_last_month_amount = $this->receipt->getBalanceLastMonthAmount ();
				} else {

					// Dư của - Phiếu A
					if ($this->receipt->getPaymentStatus () != PreSchool::ACTIVE) {

						$this->balanceAmount = $student_month ['balance_last_month_amount'];

						$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
					} else {

						$this->balanceAmount = $student_month ['BalanceAmount'];

						// Dư của phiếu cận Phiếu A nhất
						$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
					}

					// Tìm phiếu trước của phiếu A
					$student_month_2 = $student->getPrecedingMontOfStudent ( $receiptPrevDate, $ConfigStartDateSystemFee );

					if ($student_month_2 ['receiptFirst']) {

						// Lay phieu tháng $receiptPrevDate
						$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $receiptPrevDate );

						$this->balanceAmount = $receipt_2->getBalanceLastMonthAmount (); // $receipt_2->getBalanceAmount ();

						// Dư của phiếu cận Phiếu A nhất
						$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
					} else {

						$student_month_2 = $student->getPrecedingMontOfStudent ( $student_month_2 ['receiptPrevDate'], $ConfigStartDateSystemFee );

						if ($student_month_2 ['receiptFirst']) {

							// Lay phieu tháng $student_month_2['receiptPrevDate']
							$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $receiptPrevDate );

							$this->balanceAmount = $receipt_2->getBalanceAmount ();

							// Dư của phiếu cận Phiếu A nhất
							$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
						}
					}
				}
				// Nếu cấu hình là kiểu lũy tiến, thì phải xem các tháng trước đó đã thanh toán hay chưa

				$status_payment = $this->receipt->getPaymentStatus ();
				if ($status_payment == PreSchool::NOT_ACTIVE) {
					// Tổng số tiền tính phí nộp chậm
					$this->psConfigLatePayment = 0;
				} else {
					// Tổng số tiền tính phí nộp chậm
					$this->psConfigLatePayment = $this->receipt->getLatePaymentAmount ();
				}

				// Lay phieu bao của thang
				$this->ps_fee_reports = Doctrine::getTable ( 'PsFeeReports' )->findPsFeeReportOfStudentByDate ( $student_id, PsDateTime::psDatetoTime ( $date_ExportReceipt ) );

				// Lay danh sach cac khoan phi cua phieu bao
				$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $date_ExportReceipt, $receiptPrevDate );

				/*
				 * // Tổng số tiền các dịch vụ của phiếu
				 * $totalAmount = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentOfMonth ( $student_id, $date_ExportReceipt, $receiptPrevDate );
				 * $data_fee ['totalAmount'] = ($totalAmount) ? $totalAmount->getTotalAmount () : 0;
				 * $data_fee ['totalAmountReceivableAt'] = ($this->totalAmountReceivableAt) ? $this->totalAmountReceivableAt->getTotalAmount () : 0;
				 * // Lay tong so tien du kien cua 1 thang receivable_at
				 * $this->totalAmountReceivableAt = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentInMonth ( $student_id, $int_date_ExportReceipt );
				 */

				$data_fee ['balanceAmount'] = $this->balanceAmount;
				$data_fee ['collectedAmount'] = $this->collectedAmount;
				$data_fee ['balance_last_month_amount'] = $this->balance_last_month_amount;
				$data_fee ['receivable_student'] = $this->receivable_student;
				$data_fee ['ps_fee_receipt'] = $this->receipt;
				$data_fee ['ps_fee_reports'] = $this->ps_fee_reports;
				$data_fee ['psConfigLatePayment'] = $this->psConfigLatePayment;
				$data_fee ['student'] = $student;

				array_push ( $all_data_fee, $data_fee );
			}
		}

		return $this->renderPartial ( 'psFeeReports/printFeeReportByClassLayout', array (
				'all_data_fee' => $all_data_fee,
				'psClass' => $psClass ) );
	}

	// In phieu bao ra file cua co so
	public function executePrintFeeReportByWorkplace(sfWebRequest $request) {

		// Get filters
		$ps_fee_receipt_filters = $request->getParameter ( 'receipt_filters' );

		$receivable_at = $ps_fee_receipt_filters ['receivable_at']; // tháng xuất phiếu thu
		$ps_school_year_id = $ps_fee_receipt_filters ['ps_school_year_id'];
		$ps_customer_id = $ps_fee_receipt_filters ['ps_customer_id'];
		$ps_workplace_id = $ps_fee_receipt_filters ['ps_workplace_id'];
		$ps_class_id = $ps_fee_receipt_filters ['ps_class_id'];

		// Lay phieu thu cua thang duoc chon
		$date_ExportReceipt = $receivable_at;

		$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );

		// Lay thong tin co so dao tao
		/*
		$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $ps_workplace_id );

		if (! myUser::checkAccessObject ( $psWorkPlace, 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}
		*/
		
		$psClass = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, $ps_class_id );
		
		$ConfigStartDateSystemFee = $psClass->getConfigStartDateSystemFee ();

		$all_data_fee = array ();

		// lay danh sach hoc sinh trong co so
		$list_student = Doctrine::getTable ( 'Student' )->getAllStudentsByCustomerIdWithDate ( $ps_customer_id, $ps_workplace_id, $int_date_ExportReceipt );

		foreach ( $list_student as $student ) {

			$data_fee = array ();

			$student_id = $student->getId ();

			$receiptPrevDate = null;
			$this->collectedAmount = 0;
			$this->balanceAmount = 0;
			$this->totalAmount = 0;
			$this->balance_last_month_amount = 0;
			// Lay phieu thu(tat ca cac trang thai thanh toan
			$this->receipt = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $int_date_ExportReceipt );

			if ($this->receipt) {

				$data_fee ['id'] = $student_id;

				$student_month = $student->getPrecedingMontOfStudent ( $int_date_ExportReceipt, $ConfigStartDateSystemFee );

				$receiptFirst = $student_month ['receiptFirst'];

				// Ngay cua - Phiếu A
				$receiptPrevDate = $student_month ['receiptPrevDate'];

				// Đã nộp của - Phiếu A
				$this->collectedAmount = $student_month ['CollectedAmount'];

				if ($receiptFirst) { // Nếu là phiếu đầu tiên nhất

					$this->balanceAmount = $this->receipt->getBalanceLastMonthAmount ();

					// Dư của phiếu cận Phiếu A nhất
					$this->balance_last_month_amount = $this->receipt->getBalanceLastMonthAmount ();
				} else {

					// Dư của - Phiếu A
					if ($this->receipt->getPaymentStatus () != PreSchool::ACTIVE) {

						$this->balanceAmount = $student_month ['balance_last_month_amount'];

						$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
					} else {

						$this->balanceAmount = $student_month ['BalanceAmount'];

						// Dư của phiếu cận Phiếu A nhất
						$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
					}

					// Tìm phiếu trước của phiếu A
					$student_month_2 = $student->getPrecedingMontOfStudent ( $receiptPrevDate, $ConfigStartDateSystemFee );

					if ($student_month_2 ['receiptFirst']) {

						// Lay phieu tháng $receiptPrevDate
						$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $receiptPrevDate );

						$this->balanceAmount = $receipt_2->getBalanceLastMonthAmount (); // $receipt_2->getBalanceAmount ();

						// Dư của phiếu cận Phiếu A nhất
						$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
					} else {

						$student_month_2 = $student->getPrecedingMontOfStudent ( $student_month_2 ['receiptPrevDate'], $ConfigStartDateSystemFee );

						if ($student_month_2 ['receiptFirst']) {

							// Lay phieu tháng $student_month_2['receiptPrevDate']
							$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $receiptPrevDate );

							$this->balanceAmount = $receipt_2->getBalanceAmount ();

							// Dư của phiếu cận Phiếu A nhất
							$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
						}
					}
				}
				// Nếu cấu hình là kiểu lũy tiến, thì phải xem các tháng trước đó đã thanh toán hay chưa

				$status_payment = $this->receipt->getPaymentStatus ();
				if ($status_payment == PreSchool::NOT_ACTIVE) {
					// Tổng số tiền tính phí nộp chậm
					$this->psConfigLatePayment = 0;
				} else {
					// Tổng số tiền tính phí nộp chậm
					$this->psConfigLatePayment = $this->receipt->getLatePaymentAmount ();
				}

				// Lay phieu bao của thang
				$this->ps_fee_reports = Doctrine::getTable ( 'PsFeeReports' )->findPsFeeReportOfStudentByDate ( $student_id, PsDateTime::psDatetoTime ( $date_ExportReceipt ) );

				// Lay danh sach cac khoan phi cua phieu bao
				$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $date_ExportReceipt, $receiptPrevDate );

				$data_fee ['balanceAmount'] = $this->balanceAmount;
				$data_fee ['collectedAmount'] = $this->collectedAmount;
				$data_fee ['balance_last_month_amount'] = $this->balance_last_month_amount;
				$data_fee ['receivable_student'] = $this->receivable_student;
				$data_fee ['ps_fee_receipt'] = $this->receipt;
				$data_fee ['ps_fee_reports'] = $this->ps_fee_reports;
				$data_fee ['psConfigLatePayment'] = $this->psConfigLatePayment;
				$data_fee ['student'] = $student;

				array_push ( $all_data_fee, $data_fee );
			}
		}

		return $this->renderPartial ( 'psFeeReports/printFeeReportByClassLayout', array (
				'all_data_fee' => $all_data_fee,
				'psClass' => $psClass ) );
	}

	// Xuat bao phi ra file cua 1 hoc sinh
	public function executeExportFeeReports(sfWebRequest $request) {

		$this->ps_fee_reports = $this->getRoute ()
			->getObject ();

		if (! $this->ps_fee_reports) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}

		$this->student = $this->ps_fee_reports->getStudent ();

		if (! myUser::checkAccessObject ( $this->student, 'PS_FEE_REPORT_FILTER_SCHOOL' ) || $this->student->getDeletedAt ()) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}

		// Lay phieu thu cua thang duoc chon
		$date_ExportReceipt = $this->ps_fee_reports->getReceivableAt ();

		$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );

		$student_id = $this->ps_fee_reports->getStudentId ();

		// Lay phieu thu(tat ca cac trang thai thanh toan
		$this->receipt = $this->student->findReceiptByDate ( $int_date_ExportReceipt );

		$data_fee = array ();

		$receiptPrevDate = null;

		$this->collectedAmount = 0;
		$this->balanceAmount = 0;
		$this->pricePaymentLate = $this->totalAmount = 0;
		$this->balance_last_month_amount = $total_price = 0;
		// Lay lop hoc cua hoc sinh tai thoi diem bao phi
		$infoClass = Doctrine::getTable ( "StudentClass" )->getClassActivateByStudent ( $student_id, $date_ExportReceipt );
		// Nếu không xác định được lớp
		if (! $infoClass) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}

		// Lay thong tin co so dao tao
		//$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $infoClass->getPsWorkplaceId () );

		//$this->class_name = $infoClass->getName ();

		$ps_customer_id = $this->student->getPsCustomerId ();

		// thong tin tieu de
		$psClass = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, $infoClass->getClassId () );
		if (! $psClass) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}
		//$this->ps_customer = Doctrine::getTable ( 'PsCustomer' )->findOneById ( $ps_customer_id );

		//$this->ps_workplace = $psWorkPlace;

		$ConfigStartDateSystemFee = $psClass->getConfigStartDateSystemFee ();
		
		$student_month = $this->student->getPrecedingMontOfStudent ( $int_date_ExportReceipt, $ConfigStartDateSystemFee );

		$receiptFirst = $student_month ['receiptFirst'];

		// Ngay cua - Phiếu A
		$receiptPrevDate = $student_month ['receiptPrevDate'];

		// Đã nộp của - Phiếu A
		$this->collectedAmount = $student_month ['CollectedAmount'];

		if ($receiptFirst) { // Nếu là phiếu đầu tiên nhất

			$this->balanceAmount = $this->receipt->getBalanceLastMonthAmount ();

			// Dư của phiếu cận Phiếu A nhất
			$this->balance_last_month_amount = $this->receipt->getBalanceLastMonthAmount ();
		} else {

			// Dư của - Phiếu A
			if ($this->receipt->getPaymentStatus () != PreSchool::ACTIVE) {

				$this->balanceAmount = $student_month ['balance_last_month_amount'];

				$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
			} else {

				$this->balanceAmount = $student_month ['BalanceAmount'];

				// Dư của phiếu cận Phiếu A nhất
				$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
			}

			// Tìm phiếu trước của phiếu A
			$student_month_2 = $this->student->getPrecedingMontOfStudent ( $receiptPrevDate, $ConfigStartDateSystemFee );

			if ($student_month_2 ['receiptFirst']) {

				// Lay phieu tháng $receiptPrevDate
				$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $receiptPrevDate );

				$this->balanceAmount = $receipt_2->getBalanceLastMonthAmount (); // $receipt_2->getBalanceAmount ();

				// Dư của phiếu cận Phiếu A nhất
				$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
			} else {

				$student_month_2 = $this->student->getPrecedingMontOfStudent ( $student_month_2 ['receiptPrevDate'], $ConfigStartDateSystemFee );

				if ($student_month_2 ['receiptFirst']) {

					// Lay phieu tháng $student_month_2['receiptPrevDate']
					$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $receiptPrevDate );

					$this->balanceAmount = $receipt_2->getBalanceAmount ();

					// Dư của phiếu cận Phiếu A nhất
					$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
				}
			}
		}

		// Lay danh sach cac khoan phi cua phieu bao
		$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $date_ExportReceipt, $receiptPrevDate );

		$data_fee ['balanceAmount'] = $this->balanceAmount;
		$data_fee ['collectedAmount'] = $this->collectedAmount;
		$data_fee ['balance_last_month_amount'] = $this->balance_last_month_amount;
		$data_fee ['receivable_student'] = $this->receivable_student;
		$data_fee ['ps_fee_reports'] = $this->ps_fee_reports;
		$action_type = $request->getParameter ( 'action_type' );

		if ($action_type == 'statistic') { // xuat thong ke
			$this->exportStatisticFeeReport ( $this->student, $this->ps_fee_reports, $data_fee );
		} elseif ($action_type == 'notice') { // Xuat bao phi
			$this->exportFeeReport ( $this->student, $this->ps_fee_reports, $data_fee, $infoClass );
		}

		//$this->redirect ( '@ps_fee_reports' );
	}

	// Xuat phieu thu ra file cua 1 hoc sinh
	public function executeExportReceipt(sfWebRequest $request) {

		$this->ps_fee_reports = $this->getRoute ()
			->getObject ();

		if (! $this->ps_fee_reports) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}

		$this->student = $this->ps_fee_reports->getStudent ();

		if (! myUser::checkAccessObject ( $this->student, 'PS_FEE_REPORT_FILTER_SCHOOL' ) || $this->student->getDeletedAt ()) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}

		// Lay phieu thu cua thang duoc chon
		$date_ExportReceipt = $this->ps_fee_reports->getReceivableAt ();

		$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );

		$student_id = $this->ps_fee_reports->getStudentId ();

		// Lay phieu thu(tat ca cac trang thai thanh toan
		$this->receipt = $this->student->findReceiptByDate ( $int_date_ExportReceipt );

		$data_fee = array ();

		$receiptPrevDate = null;

		$this->collectedAmount = 0;
		$this->balanceAmount = 0;
		$this->pricePaymentLate = $this->totalAmount = 0;
		$this->balance_last_month_amount = $total_price = 0;
		// Lay lop hoc cua hoc sinh tai thoi diem bao phi
		$infoClass = Doctrine::getTable ( "StudentClass" )->getClassActivateByStudent ( $student_id, $date_ExportReceipt );
		// Nếu không xác định được lớp
		if (! $infoClass) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}
		/*
		// Lay thong tin co so dao tao
		$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $infoClass->getPsWorkplaceId () );

		$this->class_name = $infoClass->getName ();
		*/
		$ps_customer_id = $this->student->getPsCustomerId ();
		
		// thong tin tieu de
		$psClass = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, $infoClass->getClassId () );

		//$this->ps_customer = Doctrine::getTable ( 'PsCustomer' )->findOneById ( $ps_customer_id );

		//$this->ps_workplace = $psWorkPlace;
		
		$ConfigStartDateSystemFee = $psClass->getConfigStartDateSystemFee ();

		$student_month = $this->student->getPrecedingMontOfStudent ( $int_date_ExportReceipt, $ConfigStartDateSystemFee );

		$receiptFirst = $student_month ['receiptFirst'];

		// Ngay cua - Phiếu A
		$receiptPrevDate = $student_month ['receiptPrevDate'];

		// Đã nộp của - Phiếu A
		$this->collectedAmount = $student_month ['CollectedAmount'];

		if ($receiptFirst) { // Nếu là phiếu đầu tiên nhất

			$this->balanceAmount = $this->receipt->getBalanceLastMonthAmount ();

			// Dư của phiếu cận Phiếu A nhất
			$this->balance_last_month_amount = $this->receipt->getBalanceLastMonthAmount ();
		} else {

			// Dư của - Phiếu A
			if ($this->receipt->getPaymentStatus () != PreSchool::ACTIVE) {

				$this->balanceAmount = $student_month ['balance_last_month_amount'];

				$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
			} else {

				$this->balanceAmount = $student_month ['BalanceAmount'];

				// Dư của phiếu cận Phiếu A nhất
				$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
			}

			// Tìm phiếu trước của phiếu A
			$student_month_2 = $this->student->getPrecedingMontOfStudent ( $receiptPrevDate, $ConfigStartDateSystemFee );

			if ($student_month_2 ['receiptFirst']) {

				// Lay phieu tháng $receiptPrevDate
				$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $receiptPrevDate );

				$this->balanceAmount = $receipt_2->getBalanceLastMonthAmount (); // $receipt_2->getBalanceAmount ();

				// Dư của phiếu cận Phiếu A nhất
				$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
			} else {

				$student_month_2 = $this->student->getPrecedingMontOfStudent ( $student_month_2 ['receiptPrevDate'], $ConfigStartDateSystemFee );

				if ($student_month_2 ['receiptFirst']) {

					// Lay phieu tháng $student_month_2['receiptPrevDate']
					$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $receiptPrevDate );

					$this->balanceAmount = $receipt_2->getBalanceAmount ();

					// Dư của phiếu cận Phiếu A nhất
					$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
				}
			}
		}

		// lay so tien phat nop hoc phi muon
		$psConfigLatePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplaceDate ( $infoClass->getPsWorkplaceId (), date ( 'Y-m-d' ) );

		$priceConfigLatePayment = 0;
		if ($psConfigLatePayment) {
			$priceConfigLatePayment = $psConfigLatePayment->getPrice ();
		}
		$pricePaymentLate = 0;
		// Nếu cấu hình là kiểu lũy tiến, thì phải xem các tháng trước đó đã thanh toán hay chưa

		$status_payment = $this->receipt->getPaymentStatus ();

		// / Trang thai chua thanh toan
		if ($status_payment == PreSchool::NOT_ACTIVE) {

			if ($psClass->getConfigChooseChargePaylate () == 1) {
				// lấy ra tháng đã thanh toán gần nhất so với phiếu thu hiện tại
				$check_receipt_date = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentIsPayment ( $student_id );

				if ($check_receipt_date) { // Tính khoảng cách giữa 2 tháng

					$receipt_date = $check_receipt_date->getReceiptDate ();

					$datetime1 = date_create ( $receipt_date );
					$datetime2 = date_create ( $date_ExportReceipt );
					$interval = date_diff ( $datetime1, $datetime2 );

					$check_month = $interval->format ( '%m' );
				} else { // Nếu chưa từng thanh toán lần nào, thì đếm xem có bao nhiêu tháng trước đó chưa thanh toán

					$check_receipt_date = Doctrine::getTable ( 'Receipt' )->countReceiptOfStudentIsPayment ( $student_id, $int_date_ExportReceipt );

					$check_month = count ( $check_receipt_date ) + 1;
				}

				if ($check_month > 1) {

					for($i = 1; $i < $check_month; $i ++) {

						$track_at = date_create ( $date_ExportReceipt );

						date_modify ( $track_at, "-$i month" );

						$date_receipt = date_format ( $track_at, "Y-m-d" );

						$latePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplacePrevMonth ( $infoClass->getPsWorkplaceId (), $date_receipt );
						if ($latePayment) {
							$total_price += $latePayment->getPrice (); // Tính tổng khoản phạt nộp muộn học phí
						}
					}
					$pricePaymentLate = $total_price;
				}
			}

			// Tổng số tiền tính phí nộp chậm
			$this->psConfigLatePayment = $priceConfigLatePayment + $pricePaymentLate;
		} else {
			// Tổng số tiền tính phí nộp chậm
			$this->psConfigLatePayment = $this->receipt->getLatePaymentAmount ();
		}

		// Lay danh sach cac khoan phi cua phieu bao
		$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $date_ExportReceipt, $receiptPrevDate );
		
		
		$data_fee ['balanceAmount'] = $this->balanceAmount;
		$data_fee ['collectedAmount'] = $this->collectedAmount;
		$data_fee ['balance_last_month_amount'] = $this->balance_last_month_amount;
		$data_fee ['receivable_student'] = $this->receivable_student;
		$data_fee ['ps_fee_receipt'] = $this->receipt;
		$data_fee ['ps_fee_reports'] = $this->ps_fee_reports;
		$data_fee ['psConfigLatePayment'] = $this->psConfigLatePayment;

		$this->exportFeeReceipt ( $this->student, $this->receipt, $data_fee, $infoClass, true );
	}

	// Xuat bao phi ra file theo lop
	public function executeBatchExportFeeClass(sfWebRequest $request) {

		// Get filters
		$ps_fee_receipt_filters = $request->getParameter ( 'receipt_filters' );

		$receivable_at = $ps_fee_receipt_filters ['receivable_at']; // tháng xuất phiếu thu
		$ps_school_year_id = $ps_fee_receipt_filters ['ps_school_year_id'];
		$ps_customer_id = $ps_fee_receipt_filters ['ps_customer_id'];
		$ps_workplace_id = $ps_fee_receipt_filters ['ps_workplace_id'];
		$ps_class_id = $ps_fee_receipt_filters ['ps_class_id'];

		// Lay phieu thu cua thang duoc chon
		$date_ExportReceipt = $receivable_at;

		$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );

		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			if ($ps_customer_id != myUser::getPscustomerID ()) {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}
		}
		
		if ($ps_class_id <= 0) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}
		
		//$ps_class = Doctrine::getTable ( 'MyClass' )->findOneById($ps_class_id);
		
		$psClass = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, $ps_class_id );
		
		/*
		// Lay thong tin co so dao tao
		$ps_workplace_id = $ps_class->getPsClassRooms()->getPsWorkplaceId();
		
		$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $ps_workplace_id );
		
		if (!$psWorkPlace) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}
		*/
		
		$configStart = $psClass->getConfigStartDateSystemFee ();
		
		$all_data_fee = array ();

		// lay danh sach hoc sinh trong lop
		$list_student = Doctrine::getTable ( 'Student' )->getObjectStudentByClass ( $ps_customer_id, $ps_class_id, $receivable_at );

		foreach ( $list_student as $student ) {

			$data_fee = array ();

			$student_id = $student->getId ();

			$receiptPrevDate = null;
			$this->collectedAmount = 0;
			$this->balanceAmount = 0;
			$this->totalAmount = 0;
			$this->balance_last_month_amount = 0;
			// Lay phieu thu(tat ca cac trang thai thanh toan
			$this->receipt = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate2 ( $student_id, $int_date_ExportReceipt );

			if ($this->receipt) {

				$data_fee ['id'] = $student_id;

				$student_month = $student->getPrecedingMontOfStudent ( $int_date_ExportReceipt, $configStart );

				$receiptFirst = $student_month ['receiptFirst'];

				// Ngay cua - Phiếu A
				$receiptPrevDate = $student_month ['receiptPrevDate'];

				// Đã nộp của - Phiếu A
				$this->collectedAmount = $student_month ['CollectedAmount'];

				if ($receiptFirst) { // Nếu là phiếu đầu tiên nhất

					$this->balanceAmount = $this->receipt->getBalanceLastMonthAmount ();

					// Dư của phiếu cận Phiếu A nhất
					$this->balance_last_month_amount = $this->receipt->getBalanceLastMonthAmount ();
				} else {

					// Dư của - Phiếu A
					if ($this->receipt->getPaymentStatus () != PreSchool::ACTIVE) {

						$this->balanceAmount = $student_month ['balance_last_month_amount'];

						$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
					} else {

						$this->balanceAmount = $student_month ['BalanceAmount'];

						// Dư của phiếu cận Phiếu A nhất
						$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
					}

					// Tìm phiếu trước của phiếu A
					$student_month_2 = $student->getPrecedingMontOfStudent ( $receiptPrevDate, $configStart );

					if ($student_month_2 ['receiptFirst']) {

						// Lay phieu tháng $receiptPrevDate
						$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate2 ( $student_id, $receiptPrevDate );

						$this->balanceAmount = $receipt_2->getBalanceLastMonthAmount (); // $receipt_2->getBalanceAmount ();

						// Dư của phiếu cận Phiếu A nhất
						$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
					} else {

						$student_month_2 = $student->getPrecedingMontOfStudent ( $student_month_2 ['receiptPrevDate'], $configStart );

						if ($student_month_2 ['receiptFirst']) {

							// Lay phieu tháng $student_month_2['receiptPrevDate']
							$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate2 ( $student_id, $receiptPrevDate );

							$this->balanceAmount = $receipt_2->getBalanceAmount ();

							// Dư của phiếu cận Phiếu A nhất
							$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
						}
					}
				}

				$status_payment = $this->receipt->getPaymentStatus ();
				if ($status_payment == PreSchool::NOT_ACTIVE) {
					// Tổng số tiền tính phí nộp chậm
					$this->psConfigLatePayment = 0;
				} else {
					// Tổng số tiền tính phí nộp chậm
					$this->psConfigLatePayment = $this->receipt->getLatePaymentAmount ();
				}

				// Lay danh sach cac khoan phi cua phieu bao
				$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $date_ExportReceipt, $receiptPrevDate );

				// Lay phieu bao của thang
				$this->ps_fee_reports = Doctrine::getTable ( 'PsFeeReports' )->findPsFeeReportOfStudentByDate2 ( $student_id, PsDateTime::psDatetoTime ( $date_ExportReceipt ) );

				$data_fee ['balanceAmount'] = $this->balanceAmount;
				$data_fee ['collectedAmount'] = $this->collectedAmount;
				$data_fee ['balance_last_month_amount'] = $this->balance_last_month_amount;
				$data_fee ['receivable_student'] = $this->receivable_student;
				$data_fee ['ps_fee_receipt'] = $this->receipt;
				$data_fee ['report_no'] = $this->ps_fee_reports->getPsFeeReportNo ();

				$data_fee ['psConfigLatePayment'] = $this->psConfigLatePayment;
				$data_fee ['student_code'] = $student->getStudentCode ();
				$data_fee ['student_name'] = $student->getFirstName () . ' ' . $student->getLastName ();

				array_push ( $all_data_fee, $data_fee );
			}
		}

		$this->exportFeeReportByClass ( $all_data_fee, $psClass, $date_ExportReceipt );
	}

	// Xuat phieu thu theo lop
	public function executeBatchExportFeeReceiptClass(sfWebRequest $request) {

		// Get filters
		$ps_fee_receipt_filters = $request->getParameter ( 'receipt_filters' );

		$receivable_at = $ps_fee_receipt_filters ['receivable_at']; // tháng xuất phiếu thu
		$ps_school_year_id = $ps_fee_receipt_filters ['ps_school_year_id'];
		$ps_customer_id = $ps_fee_receipt_filters ['ps_customer_id'];
		$ps_workplace_id = $ps_fee_receipt_filters ['ps_workplace_id'];
		$ps_class_id = $ps_fee_receipt_filters ['ps_class_id'];

		// Lay phieu thu cua thang duoc chon
		$date_ExportReceipt = $receivable_at;

		$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );

		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			if ($ps_customer_id != myUser::getPscustomerID ()) {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}
		}

		if ($ps_class_id <= 0) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}
		
		$psClass = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, $ps_class_id );
		
		// Lay thong tin co so dao tao
		$ps_workplace_id = $psClass->getWpId ();
		/*
		$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $ps_workplace_id );
		
		if (!$psWorkPlace) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}
		*/
		$configStart = $psClass->getConfigStartDateSystemFee ();
		
		// lay so tien phat nop hoc phi muon
		$psConfigLatePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplaceDate ( $ps_workplace_id, date ( 'Y-m-d' ) );

		$priceConfigLatePayment = 0;
		if ($psConfigLatePayment)
			$priceConfigLatePayment = $psConfigLatePayment->getPrice ();

		// lay danh sach hoc sinh trong lop tai thoi diem
		$list_student = Doctrine::getTable ( 'Student' )->getObjectStudentByClass ( $ps_customer_id, $ps_class_id, $receivable_at );

		$all_data_fee = array ();

		foreach ( $list_student as $student ) {

			$data_fee = array ();

			$student_id = $student->getId ();

			$data_fee ['id'] = $student_id;

			$receiptPrevDate = null;
			$this->collectedAmount = 0;
			$this->balanceAmount = 0;
			$this->totalAmount = $total_price = 0;
			$this->balance_last_month_amount = 0;

			// Lay phieu thu(tat ca cac trang thai thanh toan
			$this->receipt = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate2 ( $student_id, $int_date_ExportReceipt );

			if ($this->receipt) { // hoc sinh co phieu

				$student_month = $student->getPrecedingMontOfStudent ( $int_date_ExportReceipt, $configStart );

				$receiptFirst = $student_month ['receiptFirst'];

				// Ngay cua - Phiếu A
				$receiptPrevDate = $student_month ['receiptPrevDate'];

				// Đã nộp của - Phiếu A
				$this->collectedAmount = $student_month ['CollectedAmount'];

				if ($receiptFirst) { // Nếu là phiếu đầu tiên nhất

					$this->balanceAmount = $this->receipt->getBalanceLastMonthAmount ();

					// Dư của phiếu cận Phiếu A nhất
					$this->balance_last_month_amount = $this->receipt->getBalanceLastMonthAmount ();
				} else {

					// Dư của - Phiếu A
					if ($this->receipt->getPaymentStatus () != PreSchool::ACTIVE) {

						$this->balanceAmount = $student_month ['balance_last_month_amount'];

						$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
					} else {

						$this->balanceAmount = $student_month ['BalanceAmount'];

						// Dư của phiếu cận Phiếu A nhất
						$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
					}
					// echo $receiptPrevDate; die;
					// Tìm phiếu trước của phiếu A
					$student_month_2 = $student->getPrecedingMontOfStudent ( $receiptPrevDate, $configStart );

					if ($student_month_2 ['receiptFirst']) {

						// Lay phieu tháng $receiptPrevDate
						$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate2 ( $student_id, $receiptPrevDate );

						$this->balanceAmount = $receipt_2->getBalanceLastMonthAmount (); // $receipt_2->getBalanceAmount ();

						// Dư của phiếu cận Phiếu A nhất
						$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
					} else {

						$student_month_2 = $student->getPrecedingMontOfStudent ( $student_month_2 ['receiptPrevDate'], $configStart );

						if ($student_month_2 ['receiptFirst']) {

							// Lay phieu tháng $student_month_2['receiptPrevDate']
							$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate2 ( $student_id, $receiptPrevDate );

							$this->balanceAmount = $receipt_2->getBalanceAmount ();

							// Dư của phiếu cận Phiếu A nhất
							$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
						}
					}
				}

				$pricePaymentLate = 0;
				// Nếu cấu hình là kiểu lũy tiến, thì phải xem các tháng trước đó đã thanh toán hay chưa

				$status_payment = $this->receipt->getPaymentStatus ();

				if ($status_payment == PreSchool::NOT_ACTIVE) {

					if ($psClass->getConfigChooseChargePaylate () == 1) {
						// lấy ra tháng đã thanh toán gần nhất so với phiếu thu hiện tại
						$check_receipt_date = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentIsPayment ( $student_id );

						if ($check_receipt_date) { // Tính khoảng cách giữa 2 tháng

							$receipt_date = $check_receipt_date->getReceiptDate ();

							$datetime1 = date_create ( $receipt_date );
							$datetime2 = date_create ( $date_ExportReceipt );
							$interval = date_diff ( $datetime1, $datetime2 );

							$check_month = $interval->format ( '%m' );
						} else { // Nếu chưa từng thanh toán lần nào, thì đếm xem có bao nhiêu tháng trước đó chưa thanh toán

							$check_receipt_date = Doctrine::getTable ( 'Receipt' )->countReceiptOfStudentIsPayment ( $student_id, $int_date_ExportReceipt );

							$check_month = count ( $check_receipt_date ) + 1;
						}

						if ($check_month > 1) {

							for($i = 1; $i < $check_month; $i ++) {

								$track_at = date_create ( $date_ExportReceipt );

								date_modify ( $track_at, "-$i month" );

								$date_receipt = date_format ( $track_at, "Y-m-d" );

								$latePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplacePrevMonth ( $ps_workplace_id, $date_receipt );
								if ($latePayment) {
									$total_price += $latePayment->getPrice (); // Tính tổng khoản phạt nộp muộn học phí
								}
							}
							$pricePaymentLate = $total_price;
						}
					}

					// Tổng số tiền tính phí nộp chậm
					$this->psConfigLatePayment = $priceConfigLatePayment + $pricePaymentLate;
				} else {
					// Tổng số tiền tính phí nộp chậm
					$this->psConfigLatePayment = $this->receipt->getLatePaymentAmount ();
				}

				// Lay danh sach cac khoan phi cua phieu bao
				$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $date_ExportReceipt, $receiptPrevDate );

				$data_fee ['balanceAmount'] = $this->balanceAmount;
				$data_fee ['collectedAmount'] = $this->collectedAmount;
				$data_fee ['receivable_student'] = $this->receivable_student;
				$data_fee ['ps_fee_receipt'] = $this->receipt;
				$data_fee ['psConfigLatePayment'] = $this->psConfigLatePayment;
				$data_fee ['balance_last_month_amount'] = $this->balance_last_month_amount;
				$data_fee ['report_no'] = $this->receipt->getReceiptNo ();
				$data_fee ['student_code'] = $student->getStudentCode ();
				$data_fee ['student_name'] = $student->getFirstName () . ' ' . $student->getLastName ();

				array_push ( $all_data_fee, $data_fee );
			}
		}
		$this->exportFeeReceiptByClass ( $all_data_fee, $psClass, $date_ExportReceipt );
	}

	// Xuat phieu thu theo co so
	public function executeBatchExportFeeReceiptWorkplace(sfWebRequest $request) {

		// Get filters
		$ps_fee_receipt_filters = $request->getParameter ( 'receipt_filters' );

		$receivable_at = $ps_fee_receipt_filters ['receivable_at']; // tháng xuất phiếu thu
		$ps_school_year_id = $ps_fee_receipt_filters ['ps_school_year_id'];
		$ps_customer_id = $ps_fee_receipt_filters ['ps_customer_id'];
		$ps_workplace_id = $ps_fee_receipt_filters ['ps_workplace_id'];

		// Lay phieu thu cua thang duoc chon
		$date_ExportReceipt = $receivable_at;

		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			if ($ps_customer_id != myUser::getPscustomerID ()) {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}
		}

		$param = array (
				'ps_school_year_id' => $ps_school_year_id,
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'is_activated' => PreSchool::ACTIVE );

		$list_class = Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param )
			->execute ();

		$this->exportFeeReceiptByWorkplace ( $ps_customer_id, $ps_workplace_id, $list_class, $date_ExportReceipt );
	}

	// Xuat phieu bao theo co so
	public function executeBatchExportFeeReportWorkplace(sfWebRequest $request) {

		// Get filters
		$ps_fee_receipt_filters = $request->getParameter ( 'receipt_filters' );

		$receivable_at = $ps_fee_receipt_filters ['receivable_at']; // tháng xuất phiếu thu
		$ps_school_year_id = $ps_fee_receipt_filters ['ps_school_year_id'];
		$ps_customer_id = $ps_fee_receipt_filters ['ps_customer_id'];
		$ps_workplace_id = $ps_fee_receipt_filters ['ps_workplace_id'];

		// Lay phieu thu cua thang duoc chon
		$date_ExportReceipt = $receivable_at;

		// Lay thong tin co so dao tao
		$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $ps_workplace_id );

		if (! myUser::checkAccessObject ( $psWorkPlace, 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}

		$param = array (
				'ps_school_year_id' => $ps_school_year_id,
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'is_activated' => PreSchool::ACTIVE );

		$list_class = Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param )
			->execute ();

		$this->exportFeeReportByWorkplace ( $ps_customer_id, $ps_workplace_id, $list_class, $date_ExportReceipt );
	}

	/**
	 * * Xử lý xuất phiếu báo theo cơ sở
	 */
	protected function exportFeeReportByWorkplace($ps_customer_id, $ps_workplace_id, $list_class, $date_ExportReceipt) {

		$exportFile = new ExportFeeReportsHelper ( $this );

		$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );

		// thong tin
		$ps_customer = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $ps_workplace_id );

		$file_template_file = $ps_customer->getConfigTemplateReceiptExport ();
		$config_choose_charge_showlate = $ps_customer->getConfigChooseChargeShowlate ();
		$file_template_pt = 'bm_phieubaotheolop_' . $file_template_file . '.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/uploads/export_data/' . $file_template_pt;
		$exportFile->loadTemplate ( $path_template_file );

		// lay so tien phat nop hoc phi muon
		$psConfigLatePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplaceDate ( $ps_customer->getWpId (), date ( 'Y-m-d' ) );

		$priceConfigLatePayment = 0;
		if ($psConfigLatePayment) {
			$priceConfigLatePayment = $psConfigLatePayment->getPrice ();
		}
		$configStart = $ps_customer->getConfigStartDateSystemFee ();

		foreach ( $list_class as $class ) {

			$title_xls = $class->getName ();

			$ps_class_id = $class->getId ();

			// lay danh sach hoc sinh trong lop
			$list_student = Doctrine::getTable ( 'Student' )->getObjectStudentByClass ( $ps_customer_id, $ps_class_id, $date_ExportReceipt );

			$all_data_fee = array ();

			foreach ( $list_student as $student ) {

				$data_fee = array ();

				$student_id = $student->getId ();

				$receiptPrevDate = null;
				$this->collectedAmount = 0;
				$this->balanceAmount = 0;
				$this->totalAmount = 0;
				$this->balance_last_month_amount = 0;
				// Lay phieu thu(tat ca cac trang thai thanh toan
				$this->receipt = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate2 ( $student_id, $int_date_ExportReceipt );

				if ($this->receipt) {

					$data_fee ['id'] = $student_id;

					$student_month = $student->getPrecedingMontOfStudent ( $int_date_ExportReceipt, $configStart );

					$receiptFirst = $student_month ['receiptFirst'];

					// Ngay cua - Phiếu A
					$receiptPrevDate = $student_month ['receiptPrevDate'];

					// Đã nộp của - Phiếu A
					$this->collectedAmount = $student_month ['CollectedAmount'];

					if ($receiptFirst) { // Nếu là phiếu đầu tiên nhất

						$this->balanceAmount = $this->receipt->getBalanceLastMonthAmount ();

						// Dư của phiếu cận Phiếu A nhất
						$this->balance_last_month_amount = $this->receipt->getBalanceLastMonthAmount ();
					} else {

						// Dư của - Phiếu A
						if ($this->receipt->getPaymentStatus () != PreSchool::ACTIVE) {

							$this->balanceAmount = $student_month ['balance_last_month_amount'];

							$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
						} else {

							$this->balanceAmount = $student_month ['BalanceAmount'];

							// Dư của phiếu cận Phiếu A nhất
							$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
						}

						// Tìm phiếu trước của phiếu A
						$student_month_2 = $student->getPrecedingMontOfStudent ( $receiptPrevDate, $configStart );

						if ($student_month_2 ['receiptFirst']) {

							// Lay phieu tháng $receiptPrevDate
							$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate2 ( $student_id, $receiptPrevDate );

							$this->balanceAmount = $receipt_2->getBalanceLastMonthAmount (); // $receipt_2->getBalanceAmount ();

							// Dư của phiếu cận Phiếu A nhất
							$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
						} else {

							$student_month_2 = $student->getPrecedingMontOfStudent ( $student_month_2 ['receiptPrevDate'], $configStart );

							if ($student_month_2 ['receiptFirst']) {

								// Lay phieu tháng $student_month_2['receiptPrevDate']
								$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate2 ( $student_id, $receiptPrevDate );

								$this->balanceAmount = $receipt_2->getBalanceAmount ();

								// Dư của phiếu cận Phiếu A nhất
								$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
							}
						}
					}

					$status_payment = $this->receipt->getPaymentStatus ();
					if ($status_payment == PreSchool::NOT_ACTIVE) {
						// Tổng số tiền tính phí nộp chậm
						$this->psConfigLatePayment = 0;
					} else {
						// Tổng số tiền tính phí nộp chậm
						$this->psConfigLatePayment = $this->receipt->getLatePaymentAmount ();
					}

					// Lay danh sach cac khoan phi cua phieu bao
					$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $date_ExportReceipt, $receiptPrevDate );

					// Lay phieu bao của thang
					$this->ps_fee_reports = Doctrine::getTable ( 'PsFeeReports' )->findPsFeeReportOfStudentByDate2 ( $student_id, PsDateTime::psDatetoTime ( $date_ExportReceipt ) );

					$data_fee ['balanceAmount'] = $this->balanceAmount;
					$data_fee ['collectedAmount'] = $this->collectedAmount;
					$data_fee ['balance_last_month_amount'] = $this->balance_last_month_amount;
					$data_fee ['receivable_student'] = $this->receivable_student;
					$data_fee ['ps_fee_receipt'] = $this->receipt;
					$data_fee ['report_no'] = $this->ps_fee_reports->getPsFeeReportNo ();

					$data_fee ['psConfigLatePayment'] = $this->psConfigLatePayment;
					$data_fee ['student_code'] = $student->getStudentCode ();
					$data_fee ['student_name'] = $student->getFirstName () . ' ' . $student->getLastName ();

					array_push ( $all_data_fee, $data_fee );
				}
			}

			/**
			 * Clone template
			 */

			$exportFile->createNewSheet ();

			if ($file_template_file == '02') { // xuất theo biểu mẫu 2

				$exportFile->setDataExportReportByClassTemplate2 ( $all_data_fee, $ps_customer, $int_date_ExportReceipt, $config_choose_charge_showlate, $title_xls );
			} elseif ($file_template_file == '03') { // xuất theo biểu mẫu 3

				$exportFile->setDataExportReportByClassTemplate3 ( $all_data_fee, $ps_customer, $int_date_ExportReceipt, $config_choose_charge_showlate, $title_xls );
			}elseif ($file_template_file == '04') { // xuất theo biểu mẫu 4
				$exportFile->setDataExportReceiptByClassTemplate4 ( $all_data_fee, $psClass, $int_receipt_date, $config_choose_charge_showlate, 'PB' );
				$exportFile->setDataExportReportByClassTemplate3 ( $all_data_fee, $ps_customer, $int_date_ExportReceipt, $config_choose_charge_showlate, $title_xls );
			}else { // xuất theo biểu mẫu 1

				$exportFile->setDataExportReportByClass ( $all_data_fee, $ps_customer, $int_date_ExportReceipt, $title_xls );
			}
		}

		$exportFile->removeSheet ();

		$exportFile->saveAsFile ( "PB" . date ( "Ym", $int_date_ExportReceipt ) . $ps_customer->getTitle () . ".xls" );
	}

	/**
	 * * Xử lý xuất phiếu thu theo cơ sở
	 */
	protected function exportFeeReceiptByWorkplace($ps_customer_id, $ps_workplace_id, $list_class, $date_ExportReceipt) {

		$exportFile = new ExportFeeReportsHelper ( $this );

		$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );

		// thong tin
		$ps_customer = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $ps_workplace_id );

		$file_template_file = $ps_customer->getConfigTemplateReceiptExport ();
		$config_choose_charge_showlate = $ps_customer->getConfigChooseChargeShowlate ();
		$file_template_pt = 'bm_phieuthutheolop_' . $file_template_file . '.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/uploads/export_data/' . $file_template_pt;
		$exportFile->loadTemplate ( $path_template_file );

		// lay so tien phat nop hoc phi muon
		$psConfigLatePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplaceDate ( $ps_customer->getWpId (), date ( 'Y-m-d' ) );

		$configStart = $ps_customer->getConfigStartDateSystemFee ();
		// echo $configStart; die;
		$priceConfigLatePayment = 0;
		if ($psConfigLatePayment)
			$priceConfigLatePayment = $psConfigLatePayment->getPrice ();

		foreach ( $list_class as $class ) {

			$title_xls = $class->getName ();

			$ps_class_id = $class->getId ();

			$all_data_fee = array ();

			// lay danh sach hoc sinh trong lop
			$list_student = Doctrine::getTable ( 'Student' )->getObjectStudentByClass ( $ps_customer_id, $ps_class_id, $date_ExportReceipt );

			foreach ( $list_student as $student ) {

				$data_fee = array ();

				$student_id = $student->getId ();

				$data_fee ['id'] = $student_id;

				$receiptPrevDate = null;
				$this->collectedAmount = 0;
				$this->balanceAmount = 0;
				$this->totalAmount = $total_price = 0;
				$this->balance_last_month_amount = 0;

				// Lay phieu thu(tat ca cac trang thai thanh toan
				$this->receipt = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate2 ( $student_id, $int_date_ExportReceipt );

				if ($this->receipt) { // hoc sinh co phieu

					$student_month = $student->getPrecedingMontOfStudent ( $int_date_ExportReceipt, $configStart );

					$receiptFirst = $student_month ['receiptFirst'];

					// Ngay cua - Phiếu A
					$receiptPrevDate = $student_month ['receiptPrevDate'];

					// Đã nộp của - Phiếu A
					$this->collectedAmount = $student_month ['CollectedAmount'];

					if ($receiptFirst) { // Nếu là phiếu đầu tiên nhất

						$this->balanceAmount = $this->receipt->getBalanceLastMonthAmount ();

						// Dư của phiếu cận Phiếu A nhất
						$this->balance_last_month_amount = $this->receipt->getBalanceLastMonthAmount ();
					} else {

						// Dư của - Phiếu A
						if ($this->receipt->getPaymentStatus () != PreSchool::ACTIVE) {

							$this->balanceAmount = $student_month ['balance_last_month_amount'];

							$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
						} else {

							$this->balanceAmount = $student_month ['BalanceAmount'];

							// Dư của phiếu cận Phiếu A nhất
							$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
						}
						// echo $receiptPrevDate; die;
						// Tìm phiếu trước của phiếu A
						$student_month_2 = $student->getPrecedingMontOfStudent ( $receiptPrevDate, $configStart );

						if ($student_month_2 ['receiptFirst']) {

							// Lay phieu tháng $receiptPrevDate
							$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate2 ( $student_id, $receiptPrevDate );

							$this->balanceAmount = $receipt_2->getBalanceLastMonthAmount (); // $receipt_2->getBalanceAmount ();

							// Dư của phiếu cận Phiếu A nhất
							$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
						} else {

							$student_month_2 = $student->getPrecedingMontOfStudent ( $student_month_2 ['receiptPrevDate'], $configStart );

							if ($student_month_2 ['receiptFirst']) {

								// Lay phieu tháng $student_month_2['receiptPrevDate']
								$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate2 ( $student_id, $receiptPrevDate );

								$this->balanceAmount = $receipt_2->getBalanceAmount ();

								// Dư của phiếu cận Phiếu A nhất
								$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();
							}
						}
					}

					$pricePaymentLate = 0;
					// Nếu cấu hình là kiểu lũy tiến, thì phải xem các tháng trước đó đã thanh toán hay chưa

					$status_payment = $this->receipt->getPaymentStatus ();

					if ($status_payment == PreSchool::NOT_ACTIVE) {

						if ($ps_customer->getConfigChooseChargePaylate () == 1) {
							// lấy ra tháng đã thanh toán gần nhất so với phiếu thu hiện tại
							$check_receipt_date = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentIsPayment ( $student_id );

							if ($check_receipt_date) { // Tính khoảng cách giữa 2 tháng

								$receipt_date = $check_receipt_date->getReceiptDate ();

								$datetime1 = date_create ( $receipt_date );
								$datetime2 = date_create ( $date_ExportReceipt );
								$interval = date_diff ( $datetime1, $datetime2 );

								$check_month = $interval->format ( '%m' );
							} else { // Nếu chưa từng thanh toán lần nào, thì đếm xem có bao nhiêu tháng trước đó chưa thanh toán

								$check_receipt_date = Doctrine::getTable ( 'Receipt' )->countReceiptOfStudentIsPayment ( $student_id, $int_date_ExportReceipt );

								$check_month = count ( $check_receipt_date ) + 1;
							}

							if ($check_month > 1) {

								for($i = 1; $i < $check_month; $i ++) {

									$track_at = date_create ( $date_ExportReceipt );

									date_modify ( $track_at, "-$i month" );

									$date_receipt = date_format ( $track_at, "Y-m-d" );

									$latePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplacePrevMonth ( $ps_customer->getWpId (), $date_receipt );
									if ($latePayment) {
										$total_price += $latePayment->getPrice (); // Tính tổng khoản phạt nộp muộn học phí
									}
								}
								$pricePaymentLate = $total_price;
							}
						}

						// Tổng số tiền tính phí nộp chậm
						$this->psConfigLatePayment = $priceConfigLatePayment + $pricePaymentLate;
					} else {
						// Tổng số tiền tính phí nộp chậm
						$this->psConfigLatePayment = $this->receipt->getLatePaymentAmount ();
					}

					// Lay danh sach cac khoan phi cua phieu bao
					$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $date_ExportReceipt, $receiptPrevDate );

					$data_fee ['balanceAmount'] = $this->balanceAmount;
					$data_fee ['collectedAmount'] = $this->collectedAmount;
					$data_fee ['receivable_student'] = $this->receivable_student;
					$data_fee ['ps_fee_receipt'] = $this->receipt;
					$data_fee ['psConfigLatePayment'] = $this->psConfigLatePayment;
					$data_fee ['balance_last_month_amount'] = $this->balance_last_month_amount;
					$data_fee ['student_code'] = $student->getStudentCode ();
					$data_fee ['student_name'] = $student->getFirstName () . ' ' . $student->getLastName ();

					array_push ( $all_data_fee, $data_fee );
				}
			}

			/**
			 * Clone template
			 */

			$exportFile->createNewSheet ();

			if ($file_template_file == '02') { // xuất theo biểu mẫu 2
				$exportFile->setDataExportReceiptByClassTemplate2 ( $all_data_fee, $ps_customer, $int_date_ExportReceipt, $config_choose_charge_showlate, false, $title_xls );
			} elseif ($file_template_file == '03') { // xuất theo biểu mẫu 3
				$exportFile->setDataExportReceiptByClassTemplate3 ( $all_data_fee, $ps_customer, $int_date_ExportReceipt, $config_choose_charge_showlate, false, $title_xls );
			} else { // // xuất theo biểu mẫu 1
				$exportFile->setDataExportReceiptByClass ( $all_data_fee, $ps_customer, $int_date_ExportReceipt, true, $title_xls );
			}
		}
		$exportFile->removeSheet ();

		$exportFile->saveAsFile ( "PT" . date ( "Ym", $int_date_ExportReceipt ) . $ps_customer->getTitle () . ".xls" );
	}

	// Xuat So no hoc phi theo lop
	public function executeRunBatchExportFeeDebtClass(sfWebRequest $request) {

		$ps_fee_reports_filters = $request->getParameter ( 'ps_fee_reports_filters' );

		$params ['ps_myclass_id'] = $ps_fee_reports_filters ['ps_class_id'];

		if ($params ['ps_myclass_id'] <= 0) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}

		// Check quyen cua lop
		$psMyClass = Doctrine::getTable ( 'MyClass' )->findOneById ( $params ['ps_myclass_id'] );
		if (! myUser::checkAccessObject ( $psMyClass, 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		}

		// Lay params
		$params = array ();
		$ps_month = $ps_fee_reports_filters ['ps_month'];
		$ps_year = $ps_fee_reports_filters ['ps_year'];
		$params ['ps_customer_id'] = $ps_fee_reports_filters ['ps_customer_id'];
		$params ['ps_workplace_id'] = $ps_fee_reports_filters ['ps_workplace_id'];
		$params ['ps_school_year_id'] = $ps_fee_reports_filters ['ps_school_year_id'];
		$params ['ps_myclass_id'] = $ps_fee_reports_filters ['ps_class_id'];
		$params ['receivable_at'] = PsDateTime::psDatetoTime ( $ps_fee_reports_filters ['receivable_at'] );

		// Lay danh sach hoc sinh chua thanh toan tien
		// $students = Doctrine::getTable('Receipt')->findStudentDebtByDate($params['ps_customer_id'], $params['ps_myclass_id'], $params['receivable_at']);
		$students = Doctrine::getTable ( 'Receipt' )->findStudentDebtByDate ( $params ['ps_customer_id'], $params ['ps_myclass_id'], $ps_fee_reports_filters ['receivable_at'] );

		$this->exportFeeDebtClass ( $students, $params );

		$this->redirect ( '@ps_fee_reports' );

		// $this->getUser()->setFlash('notice', count($students));

		// $this->redirect('@ps_fee_reports');
	}

	/**
	 * Ham xu ly bao phi cho nhieu hoc sinh
	 *
	 * @param $student_ids -
	 *        	mixed ID hoc sinh chon de xu ly
	 * @param $params -
	 *        	mixed; $params ['ps_customer_id'] $params ['ps_workplace_id'] $params ['ps_school_year_id'] $params ['date'] - strtotime $params ['ps_myclass_id']
	 * @param $receivable_at -
	 *        	date, format: yyyy-mm-dd
	 */
	protected function processFeeReportsOfStudent($student_ids, $params, $receivable_at) {
		
		$receivable_at = ($receivable_at != '') ? $receivable_at : date ( "Y-m" ) . "-01";

		$strtime_receivable_at = PsDateTime::psDatetoTime ( $receivable_at );

		$year_month_receivable_at = PsDateTime::psTimetoDate ( $strtime_receivable_at, "Ym" );

		$is_current = ($year_month_receivable_at != date ( "Ym" )) ? 0 : 1;

		// Set gia tri tinh Du kien khi lien quan toi so luong mac dinh
		$service = new Service ();

		$service->setPsSchoolYearId ( $params ['ps_school_year_id'] );
		$service->setPsCustomerId ( $params ['ps_customer_id'] );
		$service->setPsWorkplaceId ( $params ['ps_workplace_id'] );

		$list_service_split_flag = Doctrine::getTable ( 'Service' )->getCountServiceInServiceSplit ( $params ['ps_school_year_id'], $params ['ps_customer_id'], $params ['ps_workplace_id'] );

		$service_split_flag = array ();

		foreach ( $list_service_split_flag as $obj ) {
			$service_split_flag [$obj->get ( 's_service_id' )] = $obj->get ( 'cnt' );
		}

		$obj_order_max = Doctrine::getTable ( 'Service' )->getMaxOrder ( $params ['ps_school_year_id'], $params ['ps_customer_id'], $params ['ps_workplace_id'] );
		
		$user_id_process = sfContext::getInstance ()->getUser ()->getGuardUser ()->getId ();
		
		/** Lấy tất cả chính sách, mã giảm trừ **/
		$tatCaChinhSach = Doctrine_Query::create()->from('PsPolicyGroup')->addWhere('ps_customer_id=?',$params ['ps_customer_id'])->andWhere('ps_workplace_id=?',$params ['ps_workplace_id'])->execute();

		$tatCaGiamTru = Doctrine_Query::create()->from('PsReduceYourself')->addWhere('ps_customer_id=?',$params ['ps_customer_id'])->andWhere('ps_workplace_id=?',$params ['ps_workplace_id'])->execute();

		// Lấy thông tin năm học
		$psSchoolYear = Doctrine::getTable('PsSchoolYear')->findOneById($params ['ps_school_year_id']);

		// Lấy tất cả dịch vụ theo độ tuổi
		$tatCaDoTuoi = Doctrine_Query::create()->from('PsTypeAge')->addWhere('ps_customer_id=?',$params ['ps_customer_id'])->andWhere('ps_workplace_id=?',$params ['ps_workplace_id'])->execute();

		// Lấy cấu hình cơ sở để lập phiếu
		$psWorkPlace = Doctrine_Query::create()->from('PsWorkplaces')->select('receipt_code,type_receipt')->addWhere('id=?',$params ['ps_workplace_id'])->fetchOne();
		$receipt_code = '';
		if($psWorkPlace){
			$type_receipt = $psWorkPlace->type_receipt;
			$receipt_code = $psWorkPlace->receipt_code;

			if($type_receipt == 0){ // số chứng từ theo cở sở, không theo năm, tháng
				$kiemTraSoPhieu = Doctrine_Query::create()->from('Receipt')->select('receipt_number')->addWhere('ps_workplace_id=?',$params ['ps_workplace_id'])->orderBy('receipt_number DESC')->fetchOne();
				//$receipt_code = $receipt_code.date("Ym",strtotime($receivable_at));
			}else if($type_receipt == 1){ // Số chứng từ lấy theo năm
				$kiemTraSoPhieu = Doctrine_Query::create()->from('Receipt')->select('receipt_number')->addWhere('ps_workplace_id=?',$params ['ps_workplace_id'])->andWhere('DATE_FORMAT(receipt_date,"%Y") =?',date("Y",strtotime($receivable_at)))->orderBy('receipt_number DESC')->fetchOne();
				$receipt_code = $receipt_code.date("Y",strtotime($receivable_at));
			}elseif($type_receipt == 2){ // Số chứng từ lấy theo tháng
				$kiemTraSoPhieu = Doctrine_Query::create()->from('Receipt')->select('receipt_number')->addWhere('ps_workplace_id=?',$params ['ps_workplace_id'])->andWhere('DATE_FORMAT(receipt_date,"%Y%m") =?',date("Ym",strtotime($receivable_at)))->orderBy('receipt_number DESC')->fetchOne();
				$receipt_code = $receipt_code.date("Ym",strtotime($receivable_at));
			}else if($type_receipt == 3){ // số chứng từ theo toàn trường, không theo năm, tháng
				$kiemTraSoPhieu = Doctrine_Query::create()->from('Receipt')->select('receipt_number')->addWhere('ps_customer_id=?',$params ['ps_customer_id'])->orderBy('receipt_number DESC')->fetchOne();
			}else if($type_receipt == 4){ // số chứng từ theo toàn trường, theo năm
				$kiemTraSoPhieu = Doctrine_Query::create()->from('Receipt')->select('receipt_number')->addWhere('ps_customer_id=?',$params ['ps_customer_id'])->andWhere('DATE_FORMAT(receipt_date,"%Y") =?',date("Y",strtotime($receivable_at)))->orderBy('receipt_number DESC')->fetchOne();
				$receipt_code = $receipt_code.date("Y",strtotime($receivable_at));
			}else if($type_receipt == 5){ // số chứng từ theo toàn trường, theo tháng
				$kiemTraSoPhieu = Doctrine_Query::create()->from('Receipt')->select('receipt_number')->addWhere('ps_customer_id=?',$params ['ps_customer_id'])->andWhere('DATE_FORMAT(receipt_date,"%Y%m") =?',date("Ym",strtotime($receivable_at)))->orderBy('receipt_number DESC')->fetchOne();
				$receipt_code = $receipt_code.date("Ym",strtotime($receivable_at));
			}

		}

		$receipt_number = 0;
		if($kiemTraSoPhieu){
			$receipt_number = $kiemTraSoPhieu->receipt_number;
		}
		//echo $receipt_number;die;
		try {
			
			foreach ( $student_ids as $student ) {
				$receipt_number++;
				$fees_student = $student->processFeeReportsStudentMain ( $params, $service_split_flag, $obj_order_max,$tatCaChinhSach,$tatCaGiamTru,$psSchoolYear,$tatCaDoTuoi );
				/*echo '<pre>';
				print_r($fees_student);
				echo '</pre>';
				die;*/
				$tong_bao_phi = 0;
				

				$prefix_code = 'PB' . $receipt_code;

				$sochungtu = PreSchool::renderCode ( "%04s", $receipt_number );
				
				$psFeeReportNo = $prefix_code . '-' . $sochungtu;
				
				// $tong_bao_phi = $tong_bao_phi - $fees_student ['CollectedAmount'];
				
				// So tien bao phi cua thang
				$tong_bao_phi = $fees_student ['bao_phi_thang_nay'];
				
				// Dư thực tế của phiếu tháng trước chuyển sang
				$totalLastMonthAmount = $fees_student ['BalanceAmount'];

				$hoantra_thangtruoc = $fees_student ['hoantra_thangtruoc'];
				$chietkhau_thangtruoc = $fees_student ['chietkhau_thangtruoc'];

				$hoanTra = $fees_student ['tongHoanTra'];
				
				// Số dư của phiếu thu này (khi chưa đóng tiền)
				$new_balanceAmount = $tong_bao_phi;
				
				$psFeeReports = new PsFeeReports ();

				$psFeeReports->setPsFeeReportNo ( $psFeeReportNo );
				$psFeeReports->setStudentId ( $student->getId () );
				$psFeeReports->setReceivable ( $tong_bao_phi );
				$psFeeReports->setReceivableAt ( $receivable_at );
				$psFeeReports->setReceiptNumber ( $receipt_number );
				$psFeeReports->setUserCreatedId ( $user_id_process);
				$psFeeReports->setUserUpdatedId ( $user_id_process);
				$psFeeReports->setPsWorkplaceId ( $student->getPsWorkplaceId () );

				$psFeeReports->save ();
				
				if ($psFeeReports->getId () > 0) {
					
					$prefix_code = 'PT' . $receipt_code;
					$psReceiptNo = $prefix_code . '-' . PreSchool::renderCode ( "%04s", $receipt_number );

					$psReceipt = new Receipt ();
					
					$psReceipt->setPsCustomerId ( $student->getPsCustomerId () );
					$psReceipt->setPsWorkplaceId ( $student->getPsWorkplaceId () );
					$psReceipt->setStudentId ( $student->getId () );
					$psReceipt->setTitle ( 'PT: ' . $receivable_at );
					$psReceipt->setReceiptNo ( $psReceiptNo );
					$psReceipt->setReceiptDate ( $receivable_at );
					$psReceipt->setCollectedAmount ( 0 );
					$psReceipt->setBalanceAmount ( $new_balanceAmount );
					$psReceipt->setHoantra ( $hoanTra ); // Hoàn trả của tháng trước (Nhưng tháng này mới tính)
					
					$psReceipt->setHoantraThangtruoc ( $hoantra_thangtruoc ); // Hoàn trả của tháng trước
					$psReceipt->setChietkhauThangtruoc ( $chietkhau_thangtruoc ); // Chiết khấu của tháng trước

					// Tien du thang truoc chuyen sang
					$psReceipt->setBalanceLastMonthAmount ( $totalLastMonthAmount );
					
					$psReceipt->setIsCurrent ( $is_current );
					$psReceipt->setReceiptNumber ( $receipt_number );
					$psReceipt->setPaymentStatus ( 0 );
					$psReceipt->setPaymentDate ( null );
					$psReceipt->setRelativeId ( null );
					
					$psReceipt->setNote ( null );
					
					$psReceipt->setUserCreatedId ( $user_id_process);
					$psReceipt->setUserUpdatedId ( $user_id_process);
					
					$psReceipt->save ();
					
				}
				
				

				foreach ( $fees_student ['receiStudentTemp'] as $obj ) {

					$soluong_dichvu = $thanhtien_dichvu = 0;

					//$service_type = $obj['service_type'];

					// Dịch vụ tự hủy
					if($obj['service_type'] == 5){

						Doctrine_Query::create()->update('StudentService')->set(array('delete_at'=>$receivable_at))->addWhere('student_id =?',$obj ["student_id"])->andWhere('service_id =?',$obj ["service_id"])->andWhere('delete_at is null')->execute();

					}


					if ($obj ['receivable_student_id'] > 0) { // Nếu là cập nhật
						
						$receivableStudent = Doctrine::getTable ( 'ReceivableStudent' )->findOneById ( $obj ['receivable_student_id'] );
						
						if ($receivableStudent) {

							$soluong_dichvu = $obj ["spent_number"];
							$thanhtien_dichvu = $obj ["hoantra"];

							if ($obj ["receivable_id"] > 0) {
								
								// Tinh so lan da duoc tinh
								$is_number = Doctrine::getTable ( 'ReceivableStudent' )->getCountMumberReceivableStudent ( $obj ["receivable_id"], $student->getId () );
								
								$receivableStudent->setIsNumber ( $is_number );
							}

							$receivableStudent->setTitle ( $obj ["title"] );
							
							// So luong du kien ban dau => KO cho sửa
							// $receivableStudent->setByNumber ( $obj ["by_number"] );
							
							$receivableStudent->setSpentNumber ( $obj ["spent_number"] ); // So luong su dung

							$receivableStudent->setNumberMonth($obj ["ss_number_month"] );
							
							$receivableStudent->setAmount ( $obj ["amount"] ); // Phi phai nop * tần xuất thu
							
							$receivableStudent->setHoantra ( $obj ["hoantra"] ); // Hoàn trả
							$receivableStudent->setNote ( $obj ["note"] ); // Ghi chu
							$receivableStudent->setUserUpdatedId ($user_id_process);
							
							// $receivableStudent->setReceiptDate ($receivable_at);
							
							$receivableStudent->save ();
							
						}

					} else {
						$soluong_dichvu = $obj ["by_number"];
						$thanhtien_dichvu = $obj ["amount"];
						//echo 'BBBBBB'.$obj ["ss_number_month"];die;
						$receivableStudent = new ReceivableStudent ( false );
						
						if ($obj ["receivable_id"] > 0) {
							
							// Tinh so lan da duoc tinh
							$is_number = Doctrine::getTable ( 'ReceivableStudent' )->getCountMumberReceivableStudent ( $obj ["receivable_id"], $student->getId () );
							
							$receivableStudent->setIsNumber ( $is_number );
						}
						$receivableStudent->setTitle ( $obj ["title"] );
						$receivableStudent->setStudentId ( $obj ["student_id"] );
						$receivableStudent->setReceivableId ( $obj ["receivable_id"] ? ( int ) $obj ["receivable_id"] : null );
						$receivableStudent->setServiceId ( $obj ["service_id"] ? ( int ) $obj ["service_id"] : null );
						
						$receivableStudent->setDiscount ( $obj ["ss_discount"] );
						$receivableStudent->setDiscountAmount ( $obj ["ss_discount_amount"] );
						
						$receivableStudent->setIsLate ( ( int ) $obj ["is_late"] ); // Ve muon
						
						$receivableStudent->setByNumber ( $obj ["by_number"] );
						$receivableStudent->setNumberMonth($obj ["ss_number_month"] );
						$receivableStudent->setUnitPrice ( ( float ) $obj ["unit_price"] ); // Don gia
						$receivableStudent->setSpentNumber ( $obj ["spent_number"] ); // So luong su dung thuc te
						$receivableStudent->setAmount ( $obj ["amount"] ); // Tong tien * tần xuất thu
						$receivableStudent->setHoantra ( $obj ["hoantra"] ); // Hoàn trả
						$receivableStudent->setNote ( ( string ) $obj ["note"] );
						$receivableStudent->setReceivableAt ( $obj ["receivable_at"] );
						
						$receivableStudent->setReceiptDate ( $receivable_at );
						
						$receivableStudent->setUserCreatedId ( $user_id_process);
						$receivableStudent->setUserUpdatedId ($user_id_process);
						
						$receivableStudent->save ();
						
					}

					if($obj ["tk_no"] !='' and $obj ['tk_co'] !=''){
						// Lưu vào nhật ký công nợ
						$nhatKy = new PsNhatKyCongNo();

						$nhatKy -> setPsCustomerId($params ['ps_customer_id']);
						$nhatKy -> setPsWorkplaceId($params ['ps_workplace_id']);
						$nhatKy -> setTkno($obj ["tk_no"]);
						$nhatKy -> setTkco($obj ['tk_co']);
						$nhatKy -> setThoigian($receivable_at);
						$nhatKy -> setChungtu('PB'.$year_month_receivable_at);
						$nhatKy -> setSochungtu($sochungtu);
						$nhatKy -> setDoituongno($obj ["doituongno"]);
						$nhatKy -> setIdhocsinh($obj['student_id']);
						$nhatKy -> setIddichvu($obj['service_id']);
						$nhatKy -> setTendichvu($obj['title']);
						$nhatKy -> setSoluong($soluong_dichvu);
						$nhatKy -> setDongia($obj ["unit_price"]);
						$nhatKy -> setThanhtien($thanhtien_dichvu);
						$nhatKy -> setGiamtru(0 - $obj ["ss_discount_amount"]);

						$nhatKy -> setUserCreatedId ( $user_id_process);
						$nhatKy -> setUserUpdatedId ($user_id_process);
						$nhatKy -> save();

					}
					
					if($obj ['tk_mua'] !=''){

						$nhatKy = new PsNhatKyCongNo();

						$nhatKy -> setPsCustomerId($params ['ps_customer_id']);
						$nhatKy -> setPsWorkplaceId($params ['ps_workplace_id']);
						$nhatKy -> setTkno($obj ["tk_mua"]);
						$nhatKy -> setTkco($obj ['tk_no']);
						$nhatKy -> setThoigian($receivable_at);
						$nhatKy -> setChungtu('PX'.$year_month_receivable_at);
						$nhatKy -> setSochungtu($sochungtu);
						$nhatKy -> setDoituongno($obj ["doituongno"]);
						$nhatKy -> setIdhocsinh($obj['student_id']);
						$nhatKy -> setIddichvu($obj['service_id']);
						$nhatKy -> setTendichvu($obj['title']);
						$nhatKy -> setSoluong($soluong_dichvu);
						$nhatKy -> setDongia($obj ["unit_price"]);
						$nhatKy -> setThanhtien($thanhtien_dichvu);

						$nhatKy -> setUserCreatedId ( $user_id_process);
						$nhatKy -> setUserUpdatedId ($user_id_process);
						$nhatKy -> save();

					}

				}
				
				foreach ( $fees_student ['giamTruTheoMucDo'] as $luuGiamtru ) {
					
					$student_service_reduce = new PsStudentServiceReduce();

					$student_service_reduce -> setTitle($luuGiamtru['title']);
					$student_service_reduce -> setStudentId($luuGiamtru['student_id']);
					$student_service_reduce -> setServiceId($luuGiamtru['service_id']);
					$student_service_reduce -> setReceivableAt($luuGiamtru['receivable_at']);
					$student_service_reduce -> setIsType($luuGiamtru['is_type']);
					$student_service_reduce -> setLevel($luuGiamtru['level']);
					$student_service_reduce -> setDiscount($luuGiamtru['discount']);
					$student_service_reduce -> setUserCreatedId ( $user_id_process);
					$student_service_reduce -> setUserUpdatedId ($user_id_process);
					$student_service_reduce -> save();

					// Lưu vào nhật ký công nợ
					$nhatKy = new PsNhatKyCongNo();

					$nhatKy -> setPsCustomerId($params ['ps_customer_id']);
					$nhatKy -> setPsWorkplaceId($params ['ps_workplace_id']);
					$nhatKy -> setTkno($luuGiamtru ["tk_no"]);
					$nhatKy -> setTkco($luuGiamtru ['tk_co']);
					$nhatKy -> setThoigian($receivable_at);
					$nhatKy -> setChungtu('PB'.$year_month_receivable_at);
					$nhatKy -> setSochungtu($sochungtu);
					$nhatKy -> setDoituongno($luuGiamtru ["doituongno"]);
					$nhatKy -> setIdhocsinh($luuGiamtru['student_id']);
					$nhatKy -> setIddichvu($luuGiamtru['service_id']);
					$nhatKy -> setTendichvu($luuGiamtru['title']);
					$nhatKy -> setMachietkhau($luuGiamtru['machietkhau']);
					$nhatKy -> setMucdo($luuGiamtru['level']);
					$nhatKy -> setChietkhau($luuGiamtru['discount']);
					$nhatKy -> setKieuchietkhau($luuGiamtru['is_type']);

					$nhatKy -> setUserCreatedId ( $user_id_process);
					$nhatKy -> setUserUpdatedId ($user_id_process);
					$nhatKy -> save();

				}
				
			}
			
		} catch (Exception $e) {
			$this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ( 'Process fee report fail.' ) . $e->getMessage ());
		}
		
		return;
	}

	/**
	 * Ham xu ly bao phi cho thang - BỎ
	 *
	 * @param $student_ids -
	 *        	mixed ID hoc sinh chon de xu ly
	 * @param $params -
	 *        	mixed; $params ['ps_customer_id'] $params ['ps_workplace_id'] $params ['ps_school_year_id'] $params ['date'] - strtotime $params ['ps_myclass_id']
	 * @param $receivable_at -
	 *        	date, format: yyyy-mm-dd
	 */
	protected function processFeeReport($student_ids, $params, $receivable_at) {

		$receivable_at = ($receivable_at != '') ? $receivable_at : date ( "Y-m" ) . "-01";

		$return = false;

		$conn = Doctrine_Manager::connection ();

		try {
			$conn->beginTransaction ();

			/* Kiem tra student trong $student_ids. Neu da co phieu thu hoac phieu bao thi khong chay xu ly phieu bao nua */
			$list_student_process = Doctrine::getTable ( 'Student' )->getStudentNotInReceiptAndPsFeeReportsOfMonth ( $receivable_at, $student_ids );

			// Lay khoan phai thu khac cua thang ap dung cho lop(co trong ReceivableTemp nhung chua co trong receivable_student)
			$list_receivable_temp_receivable_at = Doctrine::getTable ( "Receivable" )->getListReceivableTempByParams ( $params );

			// Set gia tri tinh Du kien khi lien quan toi so luong mac dinh
			$service = new Service ();

			$service->setPsSchoolYearId ( $params ['ps_school_year_id'] );
			$service->setPsCustomerId ( $params ['ps_customer_id'] );
			$service->setPsWorkplaceId ( $params ['ps_workplace_id'] );

			// Lay cac thong so cau hinh

			// $list_service_split_flag = $service->getCountServiceInServiceSplit();

			$list_service_split_flag = Doctrine::getTable ( 'Service' )->getCountServiceInServiceSplit ( $params ['ps_school_year_id'], $params ['ps_customer_id'], $params ['ps_workplace_id'] );

			$service_split_flag = array ();

			foreach ( $list_service_split_flag as $obj ) {
				$service_split_flag [$obj->get ( 's_service_id' )] = $obj->get ( 'cnt' );
			}

			// Dung de sap sep thu tu cua dich vu tren danh sach
			// $obj_order_max = $service->getMaxOrder();

			$obj_order_max = Doctrine::getTable ( 'Service' )->getMaxOrder ( $params ['ps_school_year_id'], $params ['ps_customer_id'], $params ['ps_workplace_id'] );

			// So ngay du tinh cho co thu 7 va khong co thu 7
			$number_days = array (
					'weekdays' => 25,
					'saturday' => 26 );

			$priceLate = 10000; // Demo, $params['priceLate']; Gia tien tinh khi ve muon

			$finishTime = '17:00'; // $params['finishTime']; Gio don tre quy dịnh, neu don tre sau gio nay thi la ve muon

			foreach ( $list_student_process as $student ) {

				/*
				 * $totalAmount = 0; foreach ($list_receivable_temp_receivable_at as $receivable_temp) { $receivableStudent = new ReceivableStudent(); $receivableStudent->setStudentId($student->getId()); $receivableStudent->setReceivableId($receivable_temp->getReceivableId()); $receivableStudent->setServiceId(null); $receivableStudent->setByNumber(null); $receivableStudent->setSpentNumber(null); $receivableStudent->setUnitPrice(null); $receivableStudent->setAmount($receivable_temp->getAmount()); $receivableStudent->setNote($receivable_temp->getNote()); $receivableStudent->setReceivableAt($receivable_temp->getReceivableAt()); $receivableStudent->setUserCreatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()->getId () ); $receivableStudent->setUserUpdatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()->getId () ); $receivableStudent->save(); $totalAmount = $totalAmount + $receivable_temp->getAmount(); } // Luu vao tong bao phi $psFeeReports = new PsFeeReports(); $psFeeReports->setStudentId($student->getId()); $psFeeReports->setReceivable($totalAmount); $psFeeReports->setReceivableAt($receivable_at); $psFeeReports->setUserCreatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()->getId () ); $psFeeReports->setUserUpdatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()->getId () ); $psFeeReports->save();
				 */
				$fees_student = $student->processFeeAmountOfStudent ( PsDateTime::psDatetoTime ( $receivable_at ), $number_days, $obj_order_max, $service_split_flag, $priceLate, $finishTime, true );
				
				foreach ( $fees_student ['receiStudentTemp'] as $obj ) {

					if ($obj ['receivable_student_id'] > 0) {

						$receivableStudentForm = new ReceivableStudentForm ();

						$receivableStudentForm->setDefault ( 'id', $obj ['receivable_student_id'] );

						$receivableStudent = new ReceivableStudent ( $receivableStudentForm );

						if ($receivableStudent) {
							$receivableStudent->setStudentId ( $student_id );
							$receivableStudent->setByNumber ( $obj ["by_number"] );
							$receivableStudent->setSpentNumber ( $obj ["spent_number"] );
							$receivableStudent->setAmount ( $obj ["amount"] );
							$receivableStudent->setNote ( $obj ["note"] );
							$receivableStudent->setUserUpdatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()->getId () );
							$receivableStudent->save ();
						}
					} else {

						$receivableStudent = new ReceivableStudent ( false );

						$receivableStudent->setStudentId ( $obj ["student_id"] );
						$receivableStudent->setReceivableId ( $obj ["receivable_id"] ? ( int ) $obj ["receivable_id"] : null );
						$receivableStudent->setServiceId ( $obj ["service_id"] ? ( int ) $obj ["service_id"] : null );
						$receivableStudent->setIsLate ( ( int ) $obj ["is_late"] ); // Ve muon
						$receivableStudent->setByNumber ( $obj ["by_number"] ); // So luong du kien ban dau
						$receivableStudent->setUnitPrice ( $obj ["unit_price"] ); // Don gia
						$receivableStudent->setSpentNumber ( $obj ["spent_number"] ); // So luong su dung thuc te
						$receivableStudent->setAmount ( $obj ["amount"] ); // Tong tien =
						$receivableStudent->setNote ( ( string ) $obj ["note"] );
						$receivableStudent->setReceivableAt ( $obj ["receivable_at"] );

						// $receivableStudent->setFeeReportAt($receivable_at);

						$receivableStudent->setUserCreatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()->getId () );

						$receivableStudent->setUserUpdatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()->getId () );

						$receivableStudent->save ();
					}
				}

				$psFeeReports = new PsFeeReports ();

				$psFeeReports->setStudentId ( $student->getId () );
				$psFeeReports->setReceivable ( $fees_student ['tong_phai_dong'] );
				$psFeeReports->setReceivableAt ( $receivable_at );
				$psFeeReports->setUserCreatedId ( sfContext::getInstance ()->getUser ()
					->getGuardUser ()
					->getId () );
				$psFeeReports->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
					->getGuardUser ()
					->getId () );

				$psFeeReports->save ();
			}

			$return = true;

			$conn->commit ();
		} catch ( Exception $e ) {
			$conn->rollback ();
		}

		return $return;
	}

	// Xuat phieu thu cho lop hoc
	protected function exportFeeReceiptByClass($all_data_fee, $psClass, $date_ExportReceipt) {

		$exportFile = new ExportFeeReportsHelper ( $this );

		$int_receipt_date = PsDateTime::psDatetoTime ( $date_ExportReceipt );

		$file_template_file = $psClass->getConfigTemplateReceiptExport ();
		$config_choose_charge_showlate = $psClass->getConfigChooseChargeShowlate ();
		$file_template_pt = 'bm_phieuthutheolop_' . $file_template_file . '.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/uploads/export_data/' . $file_template_pt;
		$exportFile->loadTemplate ( $path_template_file );

		if ($file_template_file == '02') { // xuất theo biểu mẫu 2

			$exportFile->setDataExportReceiptByClassTemplate2 ( $all_data_fee, $psClass, $int_receipt_date, $config_choose_charge_showlate, false );
		} elseif ($file_template_file == '03') { // xuất theo biểu mẫu 3
			$exportFile->setDataExportReceiptByClassTemplate3 ( $all_data_fee, $psClass, $int_receipt_date, $config_choose_charge_showlate, 'PT' );
			//$exportFile->setDataExportReceiptByClassTemplate3 ( $all_data_fee, $psClass, $int_receipt_date, $config_choose_charge_showlate, false );
		}elseif ($file_template_file == '04') { // xuất theo biểu mẫu 3
			
			$exportFile->setDataExportReceiptByClassTemplate4 ( $all_data_fee, $psClass, $int_receipt_date, $config_choose_charge_showlate, 'PT' );
		}else { // // xuất theo biểu mẫu 1

			$exportFile->setDataExportReceiptByClass ( $all_data_fee, $psClass, $int_receipt_date, true );
		}

		$exportFile->saveAsFile ( "PT" . date ( "Ym", $int_receipt_date ) . $psClass->getName () . ".xls" );
	}

	// Xuat thong ke chi tiet hoc phi cho 1 hoc sinh
	protected function exportStatisticFeeReport($student, $ps_fee_reports, $data_fee) {

		$exportFile = new ExportFeeReportsHelper ( $this );

		$file_template_pb = 'ps_statistic_fee_report_02.xls';

		$path_template_file = sfConfig::get ( 'app_ps_data_dir' ) . '/template_export/' . $file_template_pb;

		$exportFile->loadTemplate ( $path_template_file );

		$int_receivable_at = PsDateTime::psDatetoTime ( $ps_fee_reports->getReceivableAt () );

		// Truong hoc
		$ps_customer = $student->getPsCustomer ();

		// Lay lop hoc cua hoc sinh tai thoi diem bao phi
		$infoClass = $student->getMyClassByStudent ( $ps_fee_reports->getReceivableAt () );

		$class_name = $infoClass->getName ();

		// Lay thong tin co so dao tao
		$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $infoClass->getPsWorkplaceId () );

		$header_info = array ();

		if ($ps_customer->getLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $ps_customer->getYearData () . '/' . $ps_customer->getLogo () )) {
			$header_info ['path_logo'] = sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $ps_customer->getYearData () . '/' . $ps_customer->getLogo ();
		} else {
			$header_info ['path_logo'] = '';
		}

		$header_info ['school_name'] = $ps_customer->getTitle ();

		$header_info ['wp_name'] = $psWorkPlace->getTitle ();

		$header_info ['address'] = $this->getContext ()
			->getI18N ()
			->__ ( 'Address' ) . ": " . $psWorkPlace->getAddress () . '-' . $this->getContext ()
			->getI18N ()
			->__ ( 'Tel2' ) . ": " . $psWorkPlace->getPhone ();

		if ($psWorkPlace->getEmail () != '') {
			$header_info ['address'] = $header_info ['address'] . '-' . $this->getContext ()
				->getI18N ()
				->__ ( 'Email' ) . ': ' . $psWorkPlace->getEmail ();
		}

		$header_info ['title_notification'] = $this->getContext ()
			->getI18N ()
			->__ ( 'Detail tuition fees' ) . ' ' . date ( "m-Y", $int_receivable_at );

		$exportFile->setTopInfoFormStatistic ( $header_info );

		$exportFile->setStudentInfoExportFees ( $student, $int_receivable_at, $class_name );

		$exportFile->setDataStatisticExportFees ( $data_fee, $ps_fee_reports );

		$exportFile->saveAsFile ( "PB" . date ( "Ym", $int_receivable_at ) . '_' . $student->getStudentCode () . ".xls" );
	}

	// Xuat thong bao nop hoc phi cho 1 hoc sinh
	protected function exportFeeReport($student, $ps_fee_reports, $data_fee, $infoClass) {

		$exportFile = new ExportFeeReportsHelper ( $this );

		$int_receivable_at = strtotime ( $ps_fee_reports->getReceivableAt () );

		// Truong hoc
		$ps_customer = $student->getPsCustomer ();

		// Lay lop hoc cua hoc sinh tai thoi diem bao phi
		// $infoClass = $student->getMyClassByStudent ( $ps_fee_reports->getReceivableAt () );
		$class_name = $infoClass->getName ();
		// Lay thong tin co so dao tao
		$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $infoClass->getPsWorkplaceId () );
		$config_choose_charge_showlate = $psWorkPlace->getConfigChooseChargeShowlate ();
		$file_template_file = $psWorkPlace->getConfigTemplateReceiptExport ();

		$file_template_pt = 'bm_phieubao_' . $file_template_file . '.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/uploads/export_data/' . $file_template_pt;

		$exportFile->loadTemplate ( $path_template_file );

		$header_info = array ();

		if ($ps_customer->getLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $ps_customer->getYearData () . '/' . $ps_customer->getLogo () )) {
			$header_info ['path_logo'] = sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $ps_customer->getYearData () . '/' . $ps_customer->getLogo ();
		} else {
			$header_info ['path_logo'] = '';
		}

		$header_info ['school_name'] = $ps_customer->getTitle ();

		$header_info ['wp_name'] = $psWorkPlace->getTitle ();

		$header_info ['address'] = $this->getContext ()
			->getI18N ()
			->__ ( 'Address' ) . ": " . $psWorkPlace->getAddress () . '-' . $this->getContext ()
			->getI18N ()
			->__ ( 'Tel2' ) . ": " . $psWorkPlace->getPhone ();

		if ($psWorkPlace->getEmail () != '') {
			$header_info ['address'] = $header_info ['address'] . '-' . $this->getContext ()
				->getI18N ()
				->__ ( 'Email' ) . ': ' . $psWorkPlace->getEmail ();
		}

		$header_info ['title_notification'] = $this->getContext ()
			->getI18N ()
			->__ ( 'Notice of tuition fees' ) . ' ' . date ( "m-Y", $int_receivable_at );

		$header_info ['title_xls'] = 'PB_' . date ( "mY", $int_receivable_at ) . '_' . $student->getStudentCode ();

		if ($file_template_file == '01') { // xuất theo biểu mẫu 1
			
			$exportFile->setTopInfoFormReportFees ( $header_info );
			
			$exportFile->setStudentInfoExportFees ( $student, $int_receivable_at, $class_name );
			
			// Du lieu thong bao nop hoc phi
			$exportFile->setDataReportFees ( $data_fee, $ps_fee_reports );
		} elseif ($file_template_file == '02') { // xuất theo biểu mẫu 2
			
			$exportFile->setTopInfoFormReportFees ( $header_info );
			
			$exportFile->setStudentInfoExportFees ( $student, $int_receivable_at, $class_name );
			
			// Du lieu thong bao nop hoc phi
			$exportFile->setDataReportFeesTemplate2 ( $data_fee, $ps_fee_reports, $config_choose_charge_showlate );
		} elseif ($file_template_file == '03') { // // xuất theo biểu mẫu 3
			/*
			$exportFile->setTopInfoFormReportFees ( $header_info );
			
			$exportFile->setStudentInfoExportFees ( $student, $int_receivable_at, $class_name );
			
			$exportFile->setDataReportFeesTemplate3 ( $data_fee, $ps_fee_reports, $config_choose_charge_showlate );
			*/
			$exportFile->setTopInfoFormReportFees04 ( $header_info, $student, $int_receivable_at, $class_name );
			
			$exportFile->setDataReportFeesTemplate3 ( $data_fee, $ps_fee_reports, $config_choose_charge_showlate );
			
		}elseif($file_template_file == '04'){
			
			$exportFile->setTopInfoFormReportFees04 ( $header_info, $student, $int_receivable_at, $class_name );
			
			$exportFile->setDataExportReportTemplate4 ( $data_fee, $ps_fee_reports, $config_choose_charge_showlate );
			
		}
		//die;
		$exportFile->saveAsFile ( "PB" . date ( "Ym", $int_receivable_at ) . '_' . $student->getStudentCode () . ".xls" );
	}

	// Xuat phieu bao cho lop hoc
	protected function exportFeeReportByClass($all_data_fee, $psClass, $date_ExportReceipt) {

		$exportFile = new ExportFeeReportsHelper ( $this );

		$int_receipt_date = PsDateTime::psDatetoTime ( $date_ExportReceipt );

		$file_template_file = $psClass->getConfigTemplateReceiptExport ();
		$config_choose_charge_showlate = $psClass->getConfigChooseChargeShowlate ();
		$file_template_pt = 'bm_phieubaotheolop_' . $file_template_file . '.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/uploads/export_data/' . $file_template_pt;
		$exportFile->loadTemplate ( $path_template_file );

		if ($file_template_file == '02') { // xuất theo biểu mẫu 2
			$exportFile->setDataExportReportByClassTemplate2 ( $all_data_fee, $psClass, $int_receipt_date, $config_choose_charge_showlate );
		} elseif ($file_template_file == '03') { // xuất theo biểu mẫu 3
			//$exportFile->setDataExportReportByClassTemplate3 ( $all_data_fee, $psClass, $int_receipt_date, $config_choose_charge_showlate );
			$exportFile->setDataExportReceiptByClassTemplate3 ( $all_data_fee, $psClass, $int_receipt_date, $config_choose_charge_showlate, 'PB' );
		}elseif ($file_template_file == '04') { // xuất theo biểu mẫu 4
			$exportFile->setDataExportReceiptByClassTemplate4 ( $all_data_fee, $psClass, $int_receipt_date, $config_choose_charge_showlate, 'PB' );
		}else { // xuất theo biểu mẫu 1
			$exportFile->setDataExportReportByClass ( $all_data_fee, $psClass, $int_receipt_date );
		}
		
		$exportFile->saveAsFile ( "PB" . date ( "Ym", $int_receipt_date ) . $psClass->getClassName () . ".xls" );
	}

	// Xuat phieu cho 1 hoc sinh
	protected function exportFeeReceipt($student, $ps_receipt, $data_fee, $infoClass, $a4 = true) {

		$exportFile = new ExportFeeReportsHelper ( $this );
		

		$int_receipt_date = PsDateTime::psDatetoTime ( $ps_receipt->getReceiptDate () );

		// Truong hoc
		$ps_customer = $student->getPsCustomer ();

		// Lay thong tin co so dao tao
		$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $infoClass->getPsWorkplaceId () );

		$config_choose_charge_showlate = $psWorkPlace->getConfigChooseChargeShowlate ();

		$file_template_file = $psWorkPlace->getConfigTemplateReceiptExport ();

		$file_template_pt = 'bm_phieuthu_' . $file_template_file . '.xls';

		// echo $file_template_pt; die;

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/uploads/export_data/' . $file_template_pt;

		$exportFile->loadTemplate ( $path_template_file );

		$header_info = array ();

		if ($ps_customer->getLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $ps_customer->getYearData () . '/' . $ps_customer->getLogo () )) {
			$header_info ['path_logo'] = sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $ps_customer->getYearData () . '/' . $ps_customer->getLogo ();
		} else {
			$header_info ['path_logo'] = '';
		}

		$header_info ['school_name'] = $ps_customer->getTitle ();

		$header_info ['wp_name'] = $psWorkPlace->getTitle ();

		$header_info ['address'] = $this->getContext ()
			->getI18N ()
			->__ ( 'Address' ) . ": " . $psWorkPlace->getAddress () . '-' . $this->getContext ()
			->getI18N ()
			->__ ( 'Tel2' ) . ": " . $psWorkPlace->getPhone ();

		if ($psWorkPlace->getEmail () != '') {
			$header_info ['address'] = $header_info ['address'] . '-' . $this->getContext ()
				->getI18N ()
				->__ ( 'Email' ) . ': ' . $psWorkPlace->getEmail ();
		}

		$header_info ['title_notification'] = $this->getContext ()
			->getI18N ()
			->__ ( 'Tuition receipts' ) . ' ' . date ( "m-Y", $int_receipt_date );

		$header_info ['title_xls'] = 'PT_' . date ( "Ym", $int_receipt_date ) . '_' . $student->getStudentCode ();

		if ($file_template_file == '01') { // xuất theo biểu mẫu 1

			$exportFile->setTopInfoFormReceiptFees ( $header_info );

			$exportFile->setStudentInfoExportReceipt ( $student, $int_receipt_date, $infoClass->getName (), true );

			$exportFile->setDataExportReceipt ( $data_fee, $ps_receipt, true );
		} elseif ($file_template_file == '02') { // xuất theo biểu mẫu 2

			$exportFile->setTopInfoFormReceiptFees ( $header_info, false );

			$exportFile->setStudentInfoExportReceipt ( $student, $int_receipt_date, $infoClass->getName (), false );

			$exportFile->setDataExportReceiptTemplate2 ( $data_fee, $ps_receipt, $config_choose_charge_showlate, false );
			
			// $exportFile->setDataExportReceiptTemplate2New ( $receivable_student,$receivable_at,$balanceAmount,$collectedAmount,$ps_fee_reports,$receipt,$receiptOfStudentNextMonth,$balance_last_month_amount,$old_balance_amount,$psWorkPlace);
		} elseif ($file_template_file == '03') { // // xuất theo biểu mẫu 3
			/*
			$exportFile->setTopInfoFormReceiptFees ( $header_info, false );

			$exportFile->setStudentInfoExportReceipt ( $student, $int_receipt_date, $infoClass->getName (), false );

			$exportFile->setDataExportReceiptTemplate3 ( $data_fee, $ps_receipt, $config_choose_charge_showlate, false );
			*/
			$exportFile->setTopInfoFormReportFees04 ( $header_info, $student, $int_receipt_date, $infoClass->getName () );
			
			$exportFile->setDataExportReceiptTemplate3 ( $data_fee, $ps_receipt, $config_choose_charge_showlate );
		}elseif($file_template_file == '04'){
			
			$exportFile->setTopInfoFormReportFees04 ( $header_info, $student, $int_receipt_date, $infoClass->getName () );
			
			$exportFile->setDataExportReceiptTemplate4 ( $data_fee, $ps_receipt, $config_choose_charge_showlate );
		}

		$exportFile->saveAsFile ( "PT" . date ( "Ym", $int_receipt_date ) . '_' . $student->getStudentCode () . ".xls" );
	}

	protected function exportFeeDebtClass($students, $params) {

		$exportFile = new ExportFeeReportsHelper ( $this );

		$file_template_pb = 'ps_fee_receipt_not_pay_a4.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/pschool/template_export/' . $file_template_pb;

		$exportFile->loadTemplate ( $path_template_file );

		// Lay ra thong tin truong
		$my_customer = Doctrine::getTable ( 'MyClass' )->getCustomerInfoByClassId ( $params ['ps_myclass_id'] );

		$title_info = array ();
		$title_info ['fee_notification'] = $this->getContext ()
			->getI18N ()
			->__ ( 'FEE LIST UNPAID IN MONTH ' );
		$title_info ['fee_time'] = PsDateTime::psTimetoDate ( $params ['receivable_at'], 'm-Y' );

		$my_class = Doctrine::getTable ( 'MyClass' )->setClassByParams ( $params )
			->fetchOne ();
		// $my_class = Doctrine::getTable('MyClass')->findOneById($ps_myclass_id);

		// Thong tin truong khi bao cao
		$exportFile->setCustomerInfoExportFeeDebtClass ( $my_customer, $title_info );

		// Set data khi export
		$date_at = date ( 'Y-m-d', $params ['receivable_at'] );

		$exportFile->setDataExportFeeDebtClass ( $students, $my_class->getTitle () );

		$exportFile->saveAsFile ( "PN" . $date_at . ".xls" );
	}
}