<?php
require_once dirname ( __FILE__ ) . '/../lib/psReceiptTemporaryGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psReceiptTemporaryGeneratorHelper.class.php';

/**
 * psReceiptTemporary actions.
 *
 * @package kidsschool.vn
 * @subpackage psReceiptTemporary
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psReceiptTemporaryActions extends autoPsReceiptTemporaryActions {

	public function executeIndex(sfWebRequest $request) {

		$this->filter_value = $this->getFilters ();

		$this->filter_value ['ps_customer_id'] = (isset ( $this->filter_value ['ps_customer_id'] )) ? $this->filter_value ['ps_customer_id'] : '';

		// pager
		if ($request->getParameter ( 'page' )) {
			$this->setPage ( $request->getParameter ( 'page' ) );
		}

		if ($this->filter_value ['ps_customer_id'] <= 0) {

			$this->filters = $this->configuration->getFilterForm ( $this->getFilters () );
			$this->setTemplate ( 'warning' );
		} else {
			$this->pager = $this->getPager ();
			$this->sort = $this->getSort ();
		}
	}

	protected function executeBatchDelete(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		$records = Doctrine_Query::create ()->from ( 'PsReceiptTemporary' )
			->whereIn ( 'id', $ids )
			->execute ();
		$user_id = myUser::getUserId ();
		$conn = Doctrine_Manager::connection ();

		try {
			$conn->beginTransaction ();
			$array_fee = array ();
			foreach ( $records as $record ) {
				$receipt_date = date ( 'Y-m-d', strtotime ( $record->getReceiptDate () ) );
				$strtime_receivable_at = PsDateTime::psDatetoTime ( $receipt_date );
				$year_month_receivable_at = PsDateTime::psTimetoDate ( $strtime_receivable_at, "Ym" );
				$student_id = $record->getStudentId ();
				// $relative_id = $record->getRelativeId();
				if ($record->getRelativeId () > 0) {
					$relative_id = $record->getRelativeId ();
				} else {
					$relative_id = null;
				}
				$note = $record->getNote ();

				// check xem thang day da co phieu thu chưa
				$check_report = Doctrine::getTable ( 'PsFeeReports' )->checkFeeReportsOfMonth ( $student_id, $receipt_date );

				if ($check_report) {
					$false ++;
					array_push ( $array_fee, $check_report->getStudentName () );
				} else {

					$true ++;

					$psFeeReports = new PsFeeReports ();

					$psFeeReports->setStudentId ( $student_id );
					$psFeeReports->setReceivable ( $record->getReceivable () );
					$psFeeReports->setReceivableAt ( $receipt_date );
					$psFeeReports->setUserCreatedId ( $user_id );
					$psFeeReports->setUserUpdatedId ( $user_id );

					$psFeeReports->save ();

					$prefix_code = 'PB' . $year_month_receivable_at;
					$psFeeReportNo = $prefix_code . '-' . PreSchool::renderCode ( "%010s", $psFeeReports->getId () );
					$psFeeReports->setPsFeeReportNo ( $psFeeReportNo );
					$psFeeReports->save ();

					if ($psFeeReports->getId () > 0) {

						$psReceipt = new Receipt ();

						$psReceipt->setPsCustomerId ( $record->getPsCustomerId () );
						$psReceipt->setStudentId ( $student_id );
						$psReceipt->setTitle ( 'Phiếu thanh toán phí' . $receipt_date );
						$psReceipt->setReceiptNo ( time () );
						$psReceipt->setReceiptDate ( $receipt_date );
						$psReceipt->setCollectedAmount ( $record->getCollectedAmount () );
						$psReceipt->setBalanceAmount ( $record->getBalanceAmount () );
						$psReceipt->setIsCurrent ( 0 );
						$psReceipt->setIsImport ( 1 );
						$psReceipt->setPaymentStatus ( 1 );
						$psReceipt->setPaymentDate ( null );
						$psReceipt->setRelativeId ( $relative_id );
						$psReceipt->setNote ( $note );
						$psReceipt->setUserCreatedId ( $user_id );
						$psReceipt->setUserUpdatedId ( $user_id );

						$psReceipt->save ();

						$prefix_code = 'PT' . $year_month_receivable_at;
						$psReceiptNo = $prefix_code . '-' . PreSchool::renderCode ( "%010s", $psReceipt->getId () );
						$psReceipt->setReceiptNo ( $psReceiptNo );
						$psReceipt->setUserUpdatedId ( $user_id );

						$psReceipt->save ();
					}
					$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
							'object' => $record ) ) );
					$record->delete ();
				}
			}
			$conn->commit ();
		} catch ( Exception $e ) {
			$conn->rollback ();
			$error_import = $this->getContext ()
				->getI18N ()
				->__ ( 'The selected items forward failed.' );
			$this->getUser ()
				->setFlash ( 'error', $error_import );
			$this->redirect ( '@ps_receipt_temporary' );
		}
		if ($false > 0) {

			$error_name = implode ( ' ; ', $array_fee );

			$loi = $this->getContext ()
				->getI18N ()
				->__ ( 'The selected items have been forward error.' );

			$error_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Error : ' ) . $false;

			$error_array = $this->getContext ()
				->getI18N ()
				->__ ( 'Studen name' ) . $error_name;

			if ($true > 0) {
				$success = $this->getContext ()
					->getI18N ()
					->__ ( 'The selected items have been forward successfully %value% data.', array (
						'%value%' => $true ) );
				$this->getUser ()
					->setFlash ( 'notice1', $success );
			}
			$this->getUser ()
				->setFlash ( 'notice5', $loi );
			$this->getUser ()
				->setFlash ( 'notice2', $error_number );
			$this->getUser ()
				->setFlash ( 'notice3', $error_array );
		} else {

			$success = $this->getContext ()
				->getI18N ()
				->__ ( 'The selected items have been forward successfully.' );
			$this->getUser ()
				->setFlash ( 'notice', $success );
		}

		$this->redirect ( '@ps_receipt_temporary' );
	}

	public function executeHistory(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$ps_school_year_id = null;

		$this->filter_list_history = array ();

		$history_filter = $request->getParameter ( 'history_filter' );

		if ($request->isMethod ( 'post' )) {

			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'history_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];

			$date_at_from = $value_student_filter ['date_at_from'];

			$date_at_to = $value_student_filter ['date_at_to'];

			$this->filter_list_history = Doctrine::getTable ( 'PsHistoryImport' )->getHistoryImportBySchool ( $ps_customer_id, $ps_workplace_id, $date_at_from, $date_at_to );
		}

		if ($history_filter) {

			$this->ps_school_year_id = isset ( $history_filter ['ps_school_year_id'] ) ? $history_filter ['ps_school_year_id'] : 0;

			$this->ps_workplace_id = isset ( $history_filter ['ps_workplace_id'] ) ? $history_filter ['ps_workplace_id'] : 0;

			$this->date_at_from = isset ( $history_filter ['date_at_from'] ) ? $history_filter ['date_at_from'] : '';

			$this->date_at_to = isset ( $history_filter ['date_at_to'] ) ? $history_filter ['date_at_to'] : '';

			if ($this->ps_workplace_id > 0) {

				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			} else {

				$this->ps_customer_id = isset ( $history_filter ['ps_customer_id'] ) ? $history_filter ['ps_customer_id'] : 0;
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

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
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

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'date_at_from', $this->date_at_from );

		$this->formFilter->setDefault ( 'date_at_to', $this->date_at_to );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'history_filter[%s]' );
	}
}
