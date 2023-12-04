<?php
require_once dirname ( __FILE__ ) . '/../lib/psServiceSaturdayGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psServiceSaturdayGeneratorHelper.class.php';

/**
 * psServiceSaturday actions.
 *
 * @package kidsschool.vn
 * @subpackage psServiceSaturday
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psServiceSaturdayActions extends autoPsServiceSaturdayActions {

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form_value = $request->getParameter ( $form->getName () );

		$form->bind ( $form_value, $request->getFiles ( $form->getName () ) );

		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			$conn = Doctrine_Manager::connection ();

			try {

				$conn->beginTransaction ();
				// echo $form; die();

				$ps_service_saturday = $form->save ();

				// sau 1 ngay khoi tao thi khong cho sua ngay dang ky di hoc
				$startTime = date ( "Y-m-d H:i:s" );
				$cenvertedTime = date ( 'Y-m-d H:i:s', strtotime ( '+1 day', strtotime ( $startTime ) ) );

				$records = Doctrine_Query::create ()->from ( 'PsServiceSaturdayDate' )
					->addWhere ( 'student_id =?', $ps_service_saturday->getStudentId () )
					->andWhere ( 'DATE_FORMAT(service_date,"%Y%m%d") >=?', date ( "Ymd", strtotime ( $cenvertedTime ) ) )
					->execute ();

				foreach ( $records as $record ) {
					if (in_array ( date ( "d-m-Y", strtotime ( $record->getServiceDate () ) ), $form_value ['service_date'] )) {
						$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
								'object' => $record ) ) );
						$record->delete ();
					}
				}

				foreach ( $form_value ['service_date'] as $service_date ) {

					$_service_date = date ( "Y-m-d", strtotime ( $service_date ) );
					if (date ( "Y-m-d", strtotime ( $service_date ) ) > date ( "Y-m-d", strtotime ( $cenvertedTime ) )) {
						$psServiceSaturdayDate = new PsServiceSaturdayDate ();
						$psServiceSaturdayDate->setPsServiceSaturdayId ( $ps_service_saturday->getId () );
						$psServiceSaturdayDate->setStudentId ( $ps_service_saturday->getStudentId () );
						$psServiceSaturdayDate->setServiceDate ( $_service_date );
						$psServiceSaturdayDate->setUserCreatedId ( sfContext::getInstance ()->getUser ()
							->getGuardUser ()
							->getId () );
						$psServiceSaturdayDate->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
							->getGuardUser ()
							->getId () );

						$psServiceSaturdayDate->save ();
					}
				}

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
				return sfView::SUCCESS;
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $ps_service_saturday ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				$this->redirect ( '@ps_service_saturday_new' );
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_service_saturday_edit',
						'sf_subject' => $ps_service_saturday ) );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$records = Doctrine_Query::create ()->from ( 'PsServiceSaturday' )
			->whereIn ( 'id', $ids )
			->execute ();

		foreach ( $records as $record ) {
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
					'object' => $record ) ) );

			$record->delete ();
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The selected items have been deleted successfully.' );
		$this->redirect ( '@ps_service_saturday' );
	}

	// Ham thong ke hoc sinh dang ky di hoc thu 7
	public function executeStatistic(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$ps_week = $year_month = null;

		$ps_school_year_id = null;

		$this->class_id = null;

		$this->filter_list_student = array ();

		$saturday_filter = $request->getParameter ( 'saturday_filter' );

		if ($request->isMethod ( 'post' )) {

			$value_student_filter = $request->getParameter ( 'saturday_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$this->ps_workplace_id = $value_student_filter ['ps_workplace_id'];
			$ps_workplace_id = $this->ps_workplace_id;

			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];

			$this->class_id = $value_student_filter ['class_id'];
			$class_id = $this->class_id;

			$year_month = $value_student_filter ['year_month'];

			$this->week_month = $value_student_filter ['ps_week'];
			$week_month = $this->week_month;

			$date_month = '01-' . $year_month;

			$date_year = date ( "Y", strtotime ( $date_month ) );

			$std = PsDateTime::getStaturdayOfWeek ( $week_month, $date_year, $format = 'Y-m-d' );

			$this->saturday = $std ['week_end'];

			$this->filter_list_student = Doctrine::getTable ( 'PsServiceSaturday' )->getServiceSatudayClassId ( $this->class_id, $this->ps_workplace_id, $this->saturday );
		}

		$this->year_month = isset ( $saturday_filter ['year_month'] ) ? $saturday_filter ['year_month'] : date ( "m-Y" );

		// Lay nam hoc hien tai

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

		// Lay thong tin tuan cua nam

		// $ps_week = $request->getParameter('ps_week');

		$ps_year = $request->getParameter ( 'ps_year' );
		// Nam hien tai
		$ps_year = $ps_year ? $ps_year : date ( 'Y' );

		// Tuan trong nam cua ngay hien tai
		$this->ps_week = $ps_week ? $ps_week : PsDateTime::getIndexWeekOfYear ( date ( 'Y-m-d' ) );

		$ps_week = $this->ps_week;
		// echo $ps_year; die();

		$weeks = PsDateTime::getSaturdayOfWeeks ( $ps_year );

		$this->formFilter->setDefault ( 'ps_week', $ps_week );

		$this->formFilter->setWidget ( 'ps_week', new sfWidgetFormChoice ( array (
				'choices' => PsDateTime::getOptionsSaturday ( $weeks ) ), array (
				'class' => 'select2',
				'style' => "min-width:300px;width:100%;",
				'data-placeholder' => _ ( '-Select district-' ) ) ) );

		$this->formFilter->setDefault ( 'ps_number_week', count ( $weeks ) );
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

		if ($saturday_filter) {

			$this->ps_week = isset ( $saturday_filter ['ps_week'] ) ? $saturday_filter ['ps_week'] : $ps_week;

			$this->ps_workplace_id = isset ( $saturday_filter ['ps_workplace_id'] ) ? $saturday_filter ['ps_workplace_id'] : 0;

			$this->class_id = isset ( $saturday_filter ['class_id'] ) ? $saturday_filter ['class_id'] : 0;

			if ($this->ps_workplace_id > 0) {

				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}
		// Lay thang hien tai
		$current_month = $this->year_month;

		$this->number_day = PsDateTime::psNumberDaysOfMonth ( $current_month );

		$this->formFilter->setDefault ( 'year_month', $current_month );

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
		if ($this->ps_school_year_id == '') {
			$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}
		if ($this->ps_customer_id == '') {
			$this->ps_customer_id = myUser::getPscustomerID ();
			$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$this->ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

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
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id,
							'ps_school_year_id' => $this->ps_school_year_id ) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
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

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'class_id', $this->class_id );

		$this->formFilter->setDefault ( 'ps_week', $this->ps_week );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'saturday_filter[%s]' );
	}

	// xuat file danh sach hoc sinh dang ky hoc thu 7
	public function executeExportServiceSaturday(sfWebRequest $request) {

		$ps_workplace_id = $request->getParameter ( 'wid' );
		$class_id = $request->getParameter ( 'clid' );
		$saturday = $request->getParameter ( 'date' );
		// echo $ps_workplace_id.$class_id.$date; die();

		$this->exportReportServiceSaturday ( $ps_workplace_id, $class_id, $saturday );
		$this->redirect ( '@ps_service_saturday_export' );
	}

	protected function exportReportServiceSaturday($ps_workplace_id, $class_id, $saturday) {

		$exportFile = new ExportStudentServiceSaturdayReportHelper ( $this );

		$file_template_pb = 'tkhs_hocthu7_00001.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/pschool/template_export/' . $file_template_pb;

		$workplace_name = Doctrine::getTable ( 'PsWorkPlaces' )->findOneBy ( 'id', $ps_workplace_id );

		$ps_customer_id = $workplace_name->getPsCustomerId ();

		$school_name = Doctrine::getTable ( 'Pscustomer' )->findOneBy ( 'id', $ps_customer_id );

		$filter_list_student = Doctrine::getTable ( 'PsServiceSaturday' )->getServiceSatudayClassId ( $class_id, $ps_workplace_id, $saturday );

		$exportFile->loadTemplate ( $path_template_file );

		$title_info = 'DANH SÁCH HỌC SINH ĐI HỌC THỨ 7';

		$exportFile->setGrowthsStatisticInfoExport ( $school_name, $title_info, $saturday );
		$exportFile->setStatisticInfoExportSaturday ( $workplace_name );
		$exportFile->setDataExportStatisticSaturday ( $filter_list_student );

		$exportFile->saveAsFile ( "DiHocThu7" . ".xls" );
	}
}
