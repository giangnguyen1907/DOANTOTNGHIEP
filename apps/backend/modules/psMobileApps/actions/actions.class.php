<?php
use Respect\Validation\Exceptions\ExecutableException;

require_once dirname ( __FILE__ ) . '/../lib/psMobileAppsGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psMobileAppsGeneratorHelper.class.php';

/**
 * psMobileApps actions.
 *
 * @package kidsschool.vn
 * @subpackage psMobileApps
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psMobileAppsActions extends autoPsMobileAppsActions {

	public function executeCrossChecking(sfWebRequest $request) {

		$formFilter = $this->processfilterCrossChecking ( $request );

		$this->form = $this->configuration->getForm ();

		$this->formFilter = $formFilter ['formFilter'];

		$this->school_year_id = null;
		if ($formFilter ['school_year_id'] > 0) {
			$this->formFilter->setDefault ( 'school_year_id', $formFilter ['school_year_id'] );
			$this->school_year_id = $formFilter ['school_year_id'];
		}

		$this->ps_class_id = null;
		if ($formFilter ['ps_class_id'] > 0) {
			$this->formFilter->setDefault ( 'ps_class_id', $formFilter ['ps_class_id'] );
			$this->ps_class_id = $formFilter ['ps_class_id'];
		}

		$this->ps_customer_id = null;
		if ($formFilter ['ps_customer_id'] > 0) {
			$this->formFilter->setDefault ( 'ps_customer_id', $formFilter ['ps_customer_id'] );
			$this->ps_customer_id = $formFilter ['ps_customer_id'];
		}

		$this->ps_workplace_id = null;
		if ($formFilter ['ps_workplace_id'] > 0) {
			$this->formFilter->setDefault ( 'ps_workplace_id', $formFilter ['ps_workplace_id'] );
			$this->ps_workplace_id = $formFilter ['ps_workplace_id'];
		}

		$this->from_date = null;
		if (isset ( $formFilter ['from_date'] )) {
			$this->formFilter->setDefault ( 'from_date', $formFilter ['from_date'] );
			$this->from_date = date ( 'Ymd', strtotime ( $formFilter ['from_date'] ) );
			$from_date = date ( 'Y-m', strtotime ( $formFilter ['from_date'] ) );
		}

		$this->to_date = null;
		if (isset ( $formFilter ['to_date'] )) {
			$this->formFilter->setDefault ( 'to_date', $formFilter ['to_date'] );
			$this->to_date = date ( 'Ymd', strtotime ( $formFilter ['to_date'] ) );
			$to_date = date ( 'Y-m', strtotime ( $formFilter ['to_date'] ) );
		}

		if (isset ( $this->from_date ) && isset ( $this->to_date ) && $this->ps_customer_id > 0 && $this->ps_workplace_id > 0) {

			$this->months = PsDateTime::psRangeMonthYear ( $from_date, $to_date );

			$param = array ();
			$param ['school_year_id'] = $this->school_year_id;
			$param ['ps_class_id'] = $this->ps_class_id;
			$param ['ps_customer_id'] = $this->ps_customer_id;
			$param ['ps_workplace_id'] = $this->ps_workplace_id;
			$param ['from_date'] = $this->from_date;
			$param ['to_date'] = $this->to_date;

			// Id Phu huynh va Tổng phụ huynh trước thời điểm chọn
			$relative_id_before = Doctrine_Query::create ()->from ( 'Relative r' )
				->andWhere ( 'r.ps_customer_id=?', $param ['ps_customer_id'] )
				->innerJoin ( 'r.RelativeStudent rs' )
				->innerJoin ( 'rs.Student s' )
				->andWhere ( '(s.deleted_at IS NULL OR DATE_FORMAT(s.deleted_at, "%Y%m%d") >=?)', $param ['to_date'] )
				->innerJoin ( 's.StudentClass sc' )
				->andWhereIn ( 'sc.type', array (
					PreSchool::SC_STATUS_OFFICIAL,
					PreSchool::SC_STATUS_TEST ) )
				->innerJoin ( 'sc.MyClass mc' )
				->andWhere ( 'mc.id=?', $param ['ps_class_id'] )
				->innerJoin ( 'mc.PsClassRooms cr' )
				->andWhere ( 'cr.ps_workplace_id =?', $param ['ps_workplace_id'] )
				->
			// ->andWhere('(r.deleted_at IS NULL OR DATE_FORMAT(r.deleted_at,"%Y%m%d")<?)', $param['from_date'])
			andWhere ( 'DATE_FORMAT(r.created_at,"%Y%m%d")<?', $param ['from_date'] )
				->addSelect ( 'r.id' )
				->fetchArray ();

			$this->total_relative_before_from_date = count ( $relative_id_before );

			// Lay danh sach id user từ bảng nguoi than
			$relative = Doctrine::getTable ( 'Relative' )->getRelativeIdForCrossChecking ( $param );
			$relative_id = array_column ( $relative, 'id' );
			$user_id = array_column ( $relative, 'user_id' );

			// Chi tiết số lượng phụ huynh được tạo/xóa theo từng tháng
			$this->created_on_month = Doctrine::getTable ( 'Relative' )->getTotalRelativeForCrossChecking ( $relative_id );
			$relative_id = array_merge ( array_column ( $relative_id_before, 'id' ), $relative_id );
			$this->deleted_on_month = Doctrine::getTable ( 'Relative' )->getTotalRelativeDeleteForCrossChecking ( $relative_id, array_keys ( $this->months ) );

			// Lấy danh sách kích hoạt app theo từng tháng

			$this->total_relative_mobile = Doctrine::getTable ( 'PsMobileApps' )->getTotalRelativeAccountForCrossChecking ( $param, $user_id );

			$user_id_before = Doctrine_Query::create ()->from ( 'SfGuardUser u' )
				->andWhere ( 'u.ps_customer_id=?', $param ['ps_customer_id'] )
				->andWhere ( 'u.user_type=?', PreSchool::USER_TYPE_RELATIVE )
				->innerJoin ( 'u.PsRelative r' )
				->andWhere ( 'r.ps_customer_id=?', $param ['ps_customer_id'] )
				->innerJoin ( 'r.RelativeStudent rs' )
				->innerJoin ( 'rs.Student s' )
				->andWhere ( '(s.deleted_at IS NULL OR DATE_FORMAT(s.deleted_at, "%Y%m%d") >=?)', $param ['to_date'] )
				->innerJoin ( 's.StudentClass sc' )
				->andWhereIn ( 'sc.type', array (
					PreSchool::SC_STATUS_OFFICIAL,
					PreSchool::SC_STATUS_TEST ) )
				->innerJoin ( 'sc.MyClass mc' )
				->andWhere ( 'mc.id=?', $param ['ps_class_id'] )
				->innerJoin ( 'mc.PsClassRooms cr' )
				->andWhere ( 'cr.ps_workplace_id =?', $param ['ps_workplace_id'] )
				->
			// ->andWhere('r.deleted_at IS NULL OR DATE_FORMAT(r.deleted_at,"%Y%m%d")<?', $param['from_date'])
			andWhere ( 'DATE_FORMAT(r.created_at,"%Y%m%d")<?', $param ['from_date'] )
				->addSelect ( 'u.id' )
				->fetchArray ();

			$this->total_account_active_before_from_date = count ( $user_id_before );
			// Lấy danh sách tài khoản và tổng số bị khóa với user_id được lấy từ bảng Mobile Apps
			$this->total_relative_account = Doctrine::getTable ( 'SfGuardUser' )->getTotalRelativeAccountForCrossChecking ( $param, $user_id );
			$user_id = array_merge ( $user_id, array_column ( $user_id_before, 'id' ) );
			$this->total_relative_account_lock = Doctrine::getTable ( 'SfGuardUser' )->getCountRelativeAccountLockedForCrossChecking ( $param, $user_id );
		}
	}

	public function executeExport(sfWebRequest $request) {

		if (myUser::credentialPsCustomers ( 'PS_REPORT_MOBILE_APPS_EXPORT' )) {
			$school_year_id = $request->getParameter ( 'y_id' );
			$ps_month = $request->getParameter ( 'month' );
			$ps_customer_id = $request->getParameter ( 'c_id' );
			$ps_workplace_id = $request->getParameter ( 'w_id' );

			$param = array ();
			$param ['schoolyear_id'] = $school_year_id;
			$param ['month'] = ($ps_month != 0) ? $ps_month : null;
			$param ['customer_id'] = $ps_customer_id;
			$param ['workplace_id'] = $ps_workplace_id;

			$this->reportHelper ( $param );

			$this->redirect ( '@ps_mobile_apps' );
		} else {
			$this->setTemplate ( 'ps_mobile_apps' );
		}
	}

	public function executeExportCrossChecking(sfWebRequest $request) {

		if (myUser::credentialPsCustomers ( 'PS_REPORT_MOBILE_APPS_EXPORT' )) {

			$school_year_id = $request->getParameter ( 'y_id' );
			$class_id = $request->getParameter ( 'class_id' );
			$to_date = $request->getParameter ( 'to' );
			$from_date = $request->getParameter ( 'from' );
			$ps_customer_id = $request->getParameter ( 'c_id' );
			$ps_workplace_id = $request->getParameter ( 'w_id' );

			$param = array ();
			$param ['to_date'] = ($to_date != 0) ? $to_date : null;
			$param ['from_date'] = ($from_date != 0) ? $from_date : null;
			$param ['ps_customer_id'] = $ps_customer_id;
			$param ['ps_workplace_id'] = $ps_workplace_id;
			$param ['ps_class_id'] = $class_id;
			$param ['school_year_id'] = $school_year_id;

			try {
				$this->reportHelperCrossChecking ( $param );
			} catch ( Exception $e ) {
				$this->getUser ()
					->setFlash ( 'error', $e );
				$this->redirect ( '@ps_mobile_apps' );
			}
			$this->redirect ( '@ps_mobile_apps' );
		} else {
			$this->setTemplate ( 'ps_mobile_apps' );
		}
	}

	public function executeIndex(sfWebRequest $request) {

		// if (!myUser::credentialPsCustomers('PS_REPORT_MOBILE_APPS_FILTER_SCHOOL')) {
		// echo 'Hệ thống đang nâng cấp';
		// die;
		// }

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

		$this->filtersForm = $this->getFilters ();
	}

	public function executeDetail(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {
			$ps_mobile_apps_id = $request->getParameter ( 'id' );

			if ($ps_mobile_apps_id <= 0) {
				$this->forward404Unless ( $ps_mobile_apps_id, sprintf ( 'Object does not exist.' ) );
			}

			try {
				$mobile_apps = Doctrine::getTable ( 'PsMobileApps' )->findOneById ( $ps_mobile_apps_id );

				$this->ps_mobile_apps = Doctrine::getTable ( 'PsMobileApps' )->getPsMobileAppsByUserId ( $mobile_apps->getUserId () );

				$this->user = Doctrine::getTable ( 'SfGuardUser' )->findOneById ( $mobile_apps->getUserId () );

				$this->_relative = Doctrine::getTable ( 'Relative' )->getRelativeById ( $this->user->getMemberId () );

				$this->students = Doctrine::getTable ( 'RelativeStudent' )->sqlGetStudentByRelativeId ( $this->user->getMemberId () )
					->fetchArray ();

				$this->forward404Unless ( myUser::checkAccessObject ( $this->user, 'PS_REPORT_MOBILE_APPS_FILTER_SCHOOL' ), sprintf ( 'Object (%s) does not exist .', $ps_mobile_apps_id ) );
			} catch ( Exception $e ) {
				$this->forward404Unless ( $ps_mobile_apps_id, sprintf ( 'Object does not exist.' ) );
			}
		} else {
			exit ( 0 );
		}
	}

	protected function processfilterCrossChecking(sfWebRequest $request) {

		$formFilter = new sfFormFilter ();

		$school_year_id = null;

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$ps_class_id = null;

		$from_date = null;

		$to_date = null;

		$relative_cross_checking = $request->getParameter ( 'relative_cross_checking' );

		if ($relative_cross_checking) {

			$school_year_id = isset ( $relative_cross_checking ['school_year_id'] ) ? $relative_cross_checking ['school_year_id'] : null;

			$ps_customer_id = isset ( $relative_cross_checking ['ps_customer_id'] ) ? $relative_cross_checking ['ps_customer_id'] : null;

			$ps_workplace_id = isset ( $relative_cross_checking ['ps_workplace_id'] ) ? $relative_cross_checking ['ps_workplace_id'] : null;

			$ps_class_id = isset ( $relative_cross_checking ['ps_class_id'] ) ? $relative_cross_checking ['ps_class_id'] : null;

			$from_date = isset ( $relative_cross_checking ['from_date'] ) ? $relative_cross_checking ['from_date'] : null;

			$to_date = isset ( $relative_cross_checking ['to_date'] ) ? $relative_cross_checking ['to_date'] : null;
		}

		if ($request->isMethod ( 'post' )) {

			// Handle the form submission
			$value_filter = $request->getParameter ( 'relative_cross_checking' );

			$school_year_id = $value_filter ['school_year_id'];

			$ps_class_id = $value_filter ['ps_class_id'];

			$ps_customer_id = $value_filter ['ps_customer_id'];

			$ps_workplace_id = $value_filter ['ps_workplace_id'];

			$from_date = $value_filter ['from_date'];

			$to_date = $value_filter ['to_date'];
		}

		if (myUser::credentialPsCustomers ( 'PS_REPORT_MOBILE_APPS_FILTER_SCHOOL' )) {

			$formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::ACTIVE ),
					'add_empty' => '-Select customer-' ), array (
					'class' => 'select2',
					'required' => 'required',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select customer-' ) ) ) );

			$formFilter->setValidator ( 'ps_customer_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true ) ) );
		} else {

			$formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$ormFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => true ) ) );
		}

		$formFilter->setDefault ( 'ps_customer_id', $ps_customer_id );

		$formFilter->setWidget ( 'school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => false ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ) ) ) );

		$formFilter->setValidator ( 'school_year_id', new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) ) );

		if ($ps_customer_id == '') {
			$ps_customer_id = myUser::getPscustomerID ();
			$member_id = myUser::getUser ()->getMemberId ();
			$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		$formFilter->getDefault ( 'school_year_id', $school_year_id );

		if ($ps_customer_id > 0) {

			$formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkplaces',
					'query' => Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px; width:auto;",
					'data-placeholder' => _ ( '-Select workplace-' ),
					'required' => true ) ) );

			$formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkplaces',
					'required' => true ) ) );
		} else {
			$formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2' ) ) );
		}

		$formFilter->setDefault ( 'ps_workplace_id', $ps_workplace_id );

		$formFilter->setWidget ( 'from_date', new psWidgetFormFilterInputDate ( array (), array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'From date' ),
				'required' => true ) ) );

		$formFilter->setWidget ( 'to_date', new psWidgetFormFilterInputDate ( array (), array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'To date' ),
				'required' => true ) ) );

		if ($ps_workplace_id > 0) {

			// Filters by class
			$formFilter->setWidget ( 'ps_class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id,
							'ps_school_year_id' => $school_year_id,
							'is_activated' => PreSchool::ACTIVE ) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$formFilter->setValidator ( 'ps_class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => true ) ) );
		} else {

			$formFilter->setWidget ( 'ps_class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );
		}

		// Lay ra nam hoc dau tien
		// Lay ra nam hoc cuoi cung
		$formFilter->setDefault ( 'ps_class_id', $ps_class_id );

		$formFilter->getWidgetSchema ()
			->setNameFormat ( 'relative_cross_checking[%s]' );

		return array (
				'formFilter' => $formFilter,
				'school_year_id' => $school_year_id,
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_class_id' => $ps_class_id,
				'from_date' => $from_date,
				'to_date' => $to_date );
	}

	protected function reportHelper($param) {

		$exportFile = new exportPsMobileAppsHelper ( $this );

		$file_template_pb = 'ps_mobile_apps_00001.xls';

		$path_template_file = sfConfig::get ( 'app_ps_data_dir' ) . '/template_export/' . $file_template_pb;

		$exportFile->loadTemplate ( $path_template_file );

		$ps_month = $param ['month'];
		$school_info = null;
		$customer_info = null;
		$workplace_info = null;

		if ($param ['schoolyear_id'] > 0) {
			$school_info = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $param ['schoolyear_id'] )
				->toArray ();
			$param ['from_date'] = date ( 'Ymd', strtotime ( $school_info ['from_date'] ) );
			$param ['to_date'] = date ( 'Ymd', strtotime ( $school_info ['to_date'] ) );
		}

		if ($param ['workplace_id'] > 0) {
			$workplace_info = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $param ['workplace_id'] );
		}

		if ($param ['customer_id'] > 0) {
			$customer_info = Doctrine::getTable ( 'PsCustomer' )->findOneById ( $param ['customer_id'] );
		}

		$exportFile->setCustomerInfoExport ( $school_info, $customer_info, $workplace_info, $ps_month );

		$account_list = Doctrine::getTable ( 'PsMobileApps' )->getRelativeMobileAppsByParam ( $param );
		$exportFile->setDataExport ( $account_list );

		$exportFile->saveAsFile ( "Danh_Sach_Tai_Khoan_Phu_Huynh_Kich_Hoat_" . date ( 'YmdHi' ) . ".xls" );

		$this->setTemplate ( 'ps_mobile_apps' );
	}

	protected function reportHelperCrossChecking($param) {

		$exportFile = new exportPsMobileAppsHelper ( $this );

		$file_template_pb = 'ps_mobile_apps_cross_ckecking_00001.xls';

		$path_template_file = sfConfig::get ( 'app_ps_data_dir' ) . '/template_export/' . $file_template_pb;

		$exportFile->loadTemplate ( $path_template_file );

		// Lấy ra danh sách các tháng
		$from_date = date ( 'Y-m', strtotime ( $param ['from_date'] ) );
		$to_date = date ( 'Y-m', strtotime ( $param ['to_date'] ) );
		$months = PsDateTime::psRangeMonthYear ( $from_date, $to_date );

		$from_date = date ( 'Ymd', strtotime ( $param ['from_date'] ) );
		$to_date = date ( 'Ymd', strtotime ( $param ['to_date'] ) );

		// Id Phu huynh va Tổng phụ huynh trước thời điểm chọn
		$relative_id_before = Doctrine_Query::create ()->from ( 'Relative r' )
			->andWhere ( 'r.ps_customer_id=?', $param ['ps_customer_id'] )
			->innerJoin ( 'r.RelativeStudent rs' )
			->innerJoin ( 'rs.Student s' )
			->andWhere ( '(s.deleted_at IS NULL OR DATE_FORMAT(s.deleted_at, "%Y%m%d") >=?)', $to_date )
			->innerJoin ( 's.StudentClass sc' )
			->andWhereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) )
			->innerJoin ( 'sc.MyClass mc' )
			->andWhere ( 'mc.id=?', $param ['ps_class_id'] )
			->innerJoin ( 'mc.PsClassRooms cr' )
			->andWhere ( 'cr.ps_workplace_id =?', $param ['ps_workplace_id'] )
			->
		// ->andWhere('(r.deleted_at IS NULL OR DATE_FORMAT(r.deleted_at,"%Y%m%d")<?)', $param['from_date'])
		andWhere ( 'DATE_FORMAT(r.created_at,"%Y%m%d")<?', $from_date )
			->addSelect ( 'r.id' )
			->fetchArray ();

		$total_relative_before_from_date = count ( $relative_id_before );

		// Lay danh sach id user từ bảng nguoi than
		$relative = Doctrine::getTable ( 'Relative' )->getRelativeIdForCrossChecking ( $param );
		$relative_id = array_column ( $relative, 'id' );
		$user_id = array_column ( $relative, 'user_id' );

		// Chi tiết số lượng phụ huynh được tạo/xóa theo từng tháng
		$created_on_month = Doctrine::getTable ( 'Relative' )->getTotalRelativeForCrossChecking ( $relative_id );
		$relative_id = array_merge ( array_column ( $relative_id_before, 'id' ), $relative_id );
		$deleted_on_month = Doctrine::getTable ( 'Relative' )->getTotalRelativeDeleteForCrossChecking ( $relative_id, array_keys ( $months ) );

		// Lấy danh sách kích hoạt app theo từng tháng
		$total_relative_mobile = Doctrine::getTable ( 'PsMobileApps' )->getTotalRelativeAccountForCrossChecking ( $param, $user_id );

		$user_id_before = Doctrine_Query::create ()->from ( 'SfGuardUser u' )
			->andWhere ( 'u.ps_customer_id=?', $param ['ps_customer_id'] )
			->andWhere ( 'u.user_type=?', PreSchool::USER_TYPE_RELATIVE )
			->innerJoin ( 'u.PsRelative r' )
			->andWhere ( 'r.ps_customer_id=?', $param ['ps_customer_id'] )
			->innerJoin ( 'r.RelativeStudent rs' )
			->innerJoin ( 'rs.Student s' )
			->andWhere ( '(s.deleted_at IS NULL OR DATE_FORMAT(s.deleted_at, "%Y%m%d") >=?)', $param ['to_date'] )
			->innerJoin ( 's.StudentClass sc' )
			->andWhereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) )
			->innerJoin ( 'sc.MyClass mc' )
			->andWhere ( 'mc.id=?', $param ['ps_class_id'] )
			->innerJoin ( 'mc.PsClassRooms cr' )
			->andWhere ( 'cr.ps_workplace_id =?', $param ['ps_workplace_id'] )
			->
		// ->andWhere('r.deleted_at IS NULL OR DATE_FORMAT(r.deleted_at,"%Y%m%d")<?', $param['from_date'])
		andWhere ( 'DATE_FORMAT(r.created_at,"%Y%m%d")<?', $param ['from_date'] )
			->addSelect ( 'u.id' )
			->fetchArray ();

		$total_account_active_before_from_date = count ( $user_id_before );

		// Lấy danh sách tài khoản và tổng số bị khóa với user_id được lấy từ bảng Mobile Apps
		$total_relative_account = Doctrine::getTable ( 'SfGuardUser' )->getTotalRelativeAccountForCrossChecking ( $param, $user_id );
		$user_id = array_merge ( $user_id, array_column ( $user_id_before, 'id' ) );
		$total_relative_account_lock = Doctrine::getTable ( 'SfGuardUser' )->getCountRelativeAccountLockedForCrossChecking ( $param, $user_id );

		// Xử lý data khi xuất file
		$list_month = array ();
		$key = 0;
		$list_month [$key] ['total_mobile'] = 0;
		foreach ( $months as $month ) {

			foreach ( $created_on_month as $k => $r ) {
				if ($r ['month'] == $month) {
					$list_month [$key] ['created_on_month'] = $r ['count'];
					break;
				}
			}

			foreach ( $deleted_on_month as $k => $r ) {
				if ($r ['deleted_at'] == $month) {
					$list_month [$key] ['deleted_on_month'] = $r ['count'];
					break;
				}
			}

			foreach ( $total_relative_account as $k => $r ) {
				if ($r ['month'] == $month) {
					$list_month [$key] ['total_account'] = $r ['count'];
					break;
				}
			}

			foreach ( $total_relative_mobile as $k => $r ) {
				if ($r ['month'] == $month) {
					$list_month [$key] ['total_mobile'] += 1;
				}
			}

			$list_month [$key] ['month'] = $month;
			$key ++;
		}

		$customer_info = Doctrine::getTable ( 'MyClass' )->getCustomerInfoByClassId ( $param ['ps_class_id'] );

		$param ['to_date'] = $param ['to_date'] ? date ( 'd-m-Y', strtotime ( $param ['to_date'] ) ) : date ( 'd-m-Y' );
		$param ['from_date'] = $param ['from_date'] ? date ( 'd-m-Y', strtotime ( $param ['from_date'] ) ) : date ( 'd-m-Y' );

		$exportFile->setCustomerInfoExportCrossChecking ( $customer_info, $param );

		$relative_arr = array (
				'total_relative_before' => $total_relative_before_from_date,
				'total_account_active_before' => $total_account_active_before_from_date,
				'total_account_lock' => $total_relative_account_lock );
		$exportFile->setDataExportCrossChecking ( $list_month, $relative_arr );

		$exportFile->saveAsFile ( "Doi_Soat_Tai_Khoan_Phu_Huynh_{$param['from_date']}_{$param['to_date']}.xls" );
	}
}
