<?php
require_once dirname ( __FILE__ ) . '/../lib/psReceiptsGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psReceiptsGeneratorHelper.class.php';

/**
 * psReceipts actions.
 *
 * @package kidsschool.vn
 * @subpackage psReceipts
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psReceiptsActions extends autoPsReceiptsActions {

	public function executeEdit(sfWebRequest $request) {

		$this->receipt = $this->getRoute ()
			->getObject ();

		$this->form = $this->configuration->getForm ( $this->receipt );
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

				$notice = $this->getContext ()
					->getI18N ()
					->__ ( 'Delete the tuition fee notice %value% successfully.', array (
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
				$receivable_students = Doctrine::getTable ( 'ReceivableStudent' )->getObjectReceivableStudentOfMonth ( $ps_student->getId (), $ps_fee_reports->getReceivableAt (), $receiptPrevDate );

				// Xoa phieu bao
				if ($this->getRoute ()
					->getObject ()
					->delete () && $receipt->delete ()) {

					// Xoa du lieu trong ReceivableStudent
					foreach ( $receivable_students as $receivable_student ) {
						$receivable_student->delete ();
					}
				}

				// Xóa trong ps_fee_reports_flag_my_class ? Không cần. Khi chọn lớp để chạy báo phí vẫn cho hiển thị các lớp đã từng chạy

				$this->getUser ()
					->setFlash ( 'notice', $notice );
			}

			$conn->commit ();
		} catch ( Exception $e ) {

			$conn->rollback ();

			$this->getUser ()
				->setFlash ( 'error', 'The item was deleted fail.' );
		}

		$this->redirect ( '@ps_fee_reports' );
	}

	// Chi tiet phieu thu
	public function executeDetail(sfWebRequest $request) {

		$this->receipt = $this->getRoute ()
			->getObject ();

		$this->error = false;

		try {
			if (! $this->receipt) {
				$this->getUser ()
					->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
				$this->error = true;
			} else {

				if (! myUser::checkAccessObject ( $this->receipt->getStudent (), 'PS_FEE_REPORT_FILTER_SCHOOL' ) || $this->receipt->getStudent ()
					->getDeletedAt ()) {
					$this->getUser ()
						->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
					$this->error = true;
				} else {

					$this->student = $this->receipt->getStudent ();

					$receiptPrevDate = null;

					$this->balanceAmount = 0;

					$this->collectedAmount = 0;

					// Tong so tien cua mot phiếu
					$this->totalAmount = 0;

					// Kiem tra thoi gian tam dung nghi hoc

					// Thang bao phi
					$this->receivable_at = $this->receipt->getReceiptDate ();
					$student_id = $this->receipt->getStudentId ();

					$int_receivable_at = PsDateTime::psDatetoTime ( $this->receivable_at );

					// Lat tong so tien du kien cua 1 thang receivable_at
					$this->totalAmountReceivableAt = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentInMonth ( $student_id, $int_receivable_at );

					// Lay phieu thu cua thang duoc chon
					// $this->receipt = $this->student->findReceiptByDate ( $int_receivable_at );

					if (! $this->receipt || ($this->receipt && $this->receipt->getPaymentStatus () != PreSchool::ACTIVE)) {

						// Lay lop hoc cua hoc sinh tai thoi diem bao phi
						$infoClass = $this->student->getMyClassByStudent ( $this->receivable_at );

						// Lay thong tin co so dao tao
						$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $infoClass->getPsWorkplaceId () );

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

					$this->form = new ReceiptForm ( $this->receipt );

					$this->ps_fee_reports = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $this->receivable_at ) );
				}
			}
		} catch ( Exception $e ) {
			$this->getUser ()
				->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
			$this->error = true;
		}
	}

	// Thanh toan
	public function executePayment(sfWebRequest $request) {

		$this->receipt = $this->getRoute ()
			->getObject ();

		if (! $this->receipt) {

			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		} elseif ($this->receipt->getPaymentStatus () == PreSchool::ACTIVE) {

			$this->getUser ()
				->setFlash ( 'notice', $this->getContext ()
				->getI18N ()
				->__ ( 'The bill has been paid.' ) );

			$this->redirect ( '@ps_receipts' );
		}

		$this->student = $this->receipt->getStudent ();

		if (! myUser::checkAccessObject ( $this->student, 'PS_FEE_REPORT_FILTER_SCHOOL' ) || ! $this->student || ($this->student && $this->student->getDeletedAt ())) {
			$this->getUser ()
				->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
		}

		// Kiem tra thoi gian tam dung nghi hoc

		// Thang bao phi
		$this->receivable_at = $this->receipt->getReceiptDate ();

		$int_receivable_at = PsDateTime::psDatetoTime ( $this->receivable_at );

		// Lay gia tri thanh toan
		$rec = $request->getParameter ( 'rec' );

		// Lay phieu bao cua thang
		$this->ps_fee_reports = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $this->receivable_at ) );

		$ps_fee_report_id = $this->ps_fee_reports->getId ();

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			// Validate
			if (($rec ['collected_amount'] > 0) && is_numeric ( $rec ['collected_amount'] ) && mb_strlen ( $rec ['note'] <= 255 )) {

				$this->receipt->setCollectedAmount ( $rec ['collected_amount'] );
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

			$this->redirect ( '@ps_receipts' );
		}

		$this->redirect ( '@ps_receipts' );
	}

	// import phieu thu
	public function executeImport(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_file = null;

		$import_filter = $request->getParameter ( 'import_filter' );

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

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
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile () );

		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );

		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_filter[%s]' );
	}

	public function executeImportSave(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_file = null;

		$import_filter = $request->getParameter ( 'import_filter' );

		if ($import_filter) {

			$this->ps_school_year_id = isset ( $import_filter ['ps_school_year_id'] ) ? $import_filter ['ps_school_year_id'] : 0;

			$this->ps_workplace_id = isset ( $import_filter ['ps_workplace_id'] ) ? $import_filter ['ps_workplace_id'] : 0;

			if ($this->ps_workplace_id > 0) {

				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

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
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile () );

		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );

		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_filter[%s]' );

		/**
		 * * Import file excel **
		 */

		$import_filter_form = $request->getParameter ( 'import_filter' );

		$this->formFilter->bind ( $request->getParameter ( 'import_filter' ), $request->getFiles ( 'import_filter' ) );

		// id truong hoc
		$ps_customer_id = $this->formFilter->getValue ( 'ps_customer_id' );

		$students = Doctrine::getTable ( 'Student' )->getAllStudentsByCustomerId ( $ps_customer_id );

		$array_student = array ();

		$_array_student = array ();

		foreach ( $students as $student ) {

			array_push ( $array_student, $student->getStudentCode () );

			$_array_student [$student->getStudentCode ()] = $student->getId ();
		}

		$conn = Doctrine_Manager::connection ();

		try {
			$conn->beginTransaction ();

			if ($this->formFilter->isValid ()) {

				$user_id = myUser::getUserId ();

				$file_classify = $this->getContext ()
					->getI18N ()
					->__ ( 'Fee report import' );

				$file = $this->formFilter->getValue ( 'ps_file' );

				$filename = time () . $file->getOriginalName ();

				$file_link = 'FeeReports' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );

				$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';

				$file->save ( $path_file . $filename );

				$objPHPExcel = PHPExcel_IOFactory::load ( $path_file . $filename );

				$provinceSheet = $objPHPExcel->setActiveSheetIndex ( 0 ); // Set sheet sẽ được đọc dữ liệu

				$highestRow = $provinceSheet->getHighestRow (); // Lấy số row lớn nhất trong sheet

				$array_error = array ();

				$false = 0;

				$true = 0;

				for($row = 3; $row <= $highestRow; $row ++) {

					$student_code = $provinceSheet->getCellByColumnAndRow ( 1, $row )
						->getValue ();

					$note = $provinceSheet->getCellByColumnAndRow ( 7, $row )
						->getValue ();

					$str_number = strlen ( $note );

					if ($student_code != '' && in_array ( $student_code, $array_student ) && $str_number < 255) {

						$true ++;

						$receipt = $provinceSheet->getCellByColumnAndRow ( 2, $row )
							->getValue ();

						$receipt_date = PHPExcel_Style_NumberFormat::toFormattedString ( $receipt, "YYYY-MM-DD" );

						$receipt_title = $provinceSheet->getCellByColumnAndRow ( 3, $row )
							->getValue ();

						$receivable = $provinceSheet->getCellByColumnAndRow ( 4, $row )
							->getValue ();

						$collected_amount = $provinceSheet->getCellByColumnAndRow ( 5, $row )
							->getValue ();

						$balance_amount = $provinceSheet->getCellByColumnAndRow ( 6, $row )
							->getValue ();

						$student_id = null;

						foreach ( $_array_student as $key => $_student_id ) {
							if ($key == $student_code) {
								$student_id = $_student_id;
								break;
							}
						}

						if ($student_id > 0) {
							$strtime_receivable_at = PsDateTime::psDatetoTime ( $receipt_date );

							$psReceipt = new PsReceiptTemporary ();

							$psReceipt->setPsCustomerId ( $ps_customer_id );
							$psReceipt->setStudentId ( $student_id );
							$psReceipt->setTitle ( $receipt_title );
							$psReceipt->setReceiptDate ( $receipt_date );
							$psReceipt->setReceivable ( $receivable );
							$psReceipt->setCollectedAmount ( $collected_amount );
							$psReceipt->setBalanceAmount ( $balance_amount );
							$psReceipt->setIsCurrent ( 0 );
							$psReceipt->setIsImport ( 1 );
							$psReceipt->setPaymentStatus ( 0 );
							$psReceipt->setRelativeId ( null );
							$psReceipt->setNote ( $note );
							$psReceipt->setUserCreatedId ( $user_id );
							$psReceipt->setUserUpdatedId ( $user_id );

							$psReceipt->save ();
						}
					} else {
						$false ++;
						array_push ( $array_error, $student_code );
					}
				}
				if ($true > 0) {
					// luu lich su import file phieu ghi no
					$ps_history_import = new PsHistoryImport ();
					$ps_history_import->setPsCustomerId ( $ps_customer_id );
					$ps_history_import->setPsWorkplaceId ( $ps_workplace_id );
					$ps_history_import->setFileName ( $filename );
					$ps_history_import->setFileLink ( $file_link );
					$ps_history_import->setFileClassify ( $file_classify );
					$ps_history_import->setUserCreatedId ( $user_id );

					$ps_history_import->save ();
				} else {
					unlink ( $path_file . $filename );
					$error_import = $this->getContext ()
						->getI18N ()
						->__ ( 'Import file failed.' );
					$this->getUser ()
						->setFlash ( 'error', $error_import );
					$this->redirect ( '@ps_receipts_import' );
				}

				$error_line = implode ( ' ; ', $array_error );
			} else {
				$error_import = $this->getContext ()
					->getI18N ()
					->__ ( 'Import file failed.' );
				$this->getUser ()
					->setFlash ( 'error', $error_import );
				$this->redirect ( '@ps_receipts_import' );
			}
			$conn->commit ();
		} catch ( Exception $e ) {
			$conn->rollback ();
			$error_import = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file failed.' );
			$this->getUser ()
				->setFlash ( 'error', $error_import );
			$this->redirect ( '@ps_receipts_import' );
		}
		if ($false == 0) {
			$successfully = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file successfully %value% data. No error student code', array (
					'%value%' => $true ) );
			$this->getUser ()
				->setFlash ( 'notice', $successfully );
		} else {

			$successfully = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file successfully.' );

			$success_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Successfully : ' ) . $true;

			$error_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Error : ' ) . $false;

			$error_array = $this->getContext ()
				->getI18N ()
				->__ ( 'Studen code' ) . $error_line;

			$this->getUser ()
				->setFlash ( 'notice', $successfully );
			$this->getUser ()
				->setFlash ( 'notice1', $success_number );
			$this->getUser ()
				->setFlash ( 'notice2', $error_number );
			$this->getUser ()
				->setFlash ( 'notice3', $error_array );
		}

		$this->redirect ( '@ps_receipts_import' );
	}

	public function executeImportSaveOld(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_file = null;

		$import_filter = $request->getParameter ( 'import_filter' );

		if ($import_filter) {

			$this->ps_school_year_id = isset ( $import_filter ['ps_school_year_id'] ) ? $import_filter ['ps_school_year_id'] : 0;

			$this->ps_workplace_id = isset ( $import_filter ['ps_workplace_id'] ) ? $import_filter ['ps_workplace_id'] : 0;

			if ($this->ps_workplace_id > 0) {

				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

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
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile () );

		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );

		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_filter[%s]' );

		/**
		 * * Import file excel **
		 */

		$import_filter_form = $request->getParameter ( 'import_filter' );

		$this->formFilter->bind ( $request->getParameter ( 'import_filter' ), $request->getFiles ( 'import_filter' ) );

		// id truong hoc
		$ps_customer_id = $this->formFilter->getValue ( 'ps_customer_id' );

		$students = Doctrine::getTable ( 'Student' )->getAllStudentsByCustomerId ( $ps_customer_id );
		$array_student = array ();
		foreach ( $students as $student ) {
			array_push ( $array_student, $student->getStudentCode () );
		}
		$conn = Doctrine_Manager::connection ();
		try {
			$conn->beginTransaction ();

			if ($this->formFilter->isValid ()) {

				$user_id = myUser::getUserId ();

				$file_classify = $this->getContext ()
					->getI18N ()
					->__ ( 'Fee report import' );

				$file = $this->formFilter->getValue ( 'ps_file' );

				$filename = time () . $file->getOriginalName ();

				$file_link = 'FeeReports' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );

				$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';

				$file->save ( $path_file . $filename );

				$objPHPExcel = PHPExcel_IOFactory::load ( $path_file . $filename );

				$provinceSheet = $objPHPExcel->setActiveSheetIndex ( 0 ); // Set sheet sẽ được đọc dữ liệu

				$highestRow = $provinceSheet->getHighestRow (); // Lấy số row lớn nhất trong sheet

				$array_error = array ();
				$false = 0;
				$true = 0;
				for($row = 3; $row <= $highestRow; $row ++) {

					$student_code = $provinceSheet->getCellByColumnAndRow ( 1, $row )
						->getValue ();

					if ($student_code != '' && in_array ( $student_code, $array_student )) {

						$true ++;

						$receipt = $provinceSheet->getCellByColumnAndRow ( 2, $row )
							->getValue ();

						$receipt_date = PHPExcel_Style_NumberFormat::toFormattedString ( $receipt, "YYYY-MM-DD" );

						$receipt_title = $provinceSheet->getCellByColumnAndRow ( 3, $row )
							->getValue ();

						$receivable = $provinceSheet->getCellByColumnAndRow ( 4, $row )
							->getValue ();

						$collected_amount = $provinceSheet->getCellByColumnAndRow ( 5, $row )
							->getValue ();

						$balance_amount = $provinceSheet->getCellByColumnAndRow ( 6, $row )
							->getValue ();

						$note = $provinceSheet->getCellByColumnAndRow ( 7, $row )
							->getValue ();

						$student_id = Doctrine::getTable ( 'Student' )->findOneByStudentCode ( $student_code )
							->getId ();

						$strtime_receivable_at = PsDateTime::psDatetoTime ( $receipt_date );

						$year_month_receivable_at = PsDateTime::psTimetoDate ( $strtime_receivable_at, "Ym" );

						$psFeeReports = new PsFeeReports ();

						$psFeeReports->setStudentId ( $student_id );
						$psFeeReports->setReceivable ( $receivable );
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

							$psReceipt->setPsCustomerId ( $ps_customer_id );
							$psReceipt->setStudentId ( $student_id );
							$psReceipt->setTitle ( 'Phiếu thanh toán phí' . $receipt_date );
							$psReceipt->setReceiptNo ( time () );
							$psReceipt->setReceiptDate ( $receipt_date );
							$psReceipt->setCollectedAmount ( $collected_amount );
							$psReceipt->setBalanceAmount ( $balance_amount );
							$psReceipt->setIsCurrent ( 0 );
							$psReceipt->setIsImport ( 1 );
							$psReceipt->setPaymentStatus ( 0 );
							$psReceipt->setPaymentDate ( null );
							$psReceipt->setRelativeId ( null );
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
					} else {
						$false ++;
						array_push ( $array_error, $student_code );
					}
				}
				if ($true > 0) {
					// luu lich su import file phieu ghi no
					$ps_history_import = new PsHistoryImport ();
					$ps_history_import->setPsCustomerId ( $ps_customer_id );
					$ps_history_import->setPsWorkplaceId ( $ps_workplace_id );
					$ps_history_import->setFileName ( $filename );
					$ps_history_import->setFileLink ( $file_link );
					$ps_history_import->setFileClassify ( $file_classify );
					$ps_history_import->setUserCreatedId ( $user_id );

					$ps_history_import->save ();
				} else {
					unlink ( $path_file . $filename );
					$error_import = $this->getContext ()
						->getI18N ()
						->__ ( 'Import file failed.' );
					$this->getUser ()
						->setFlash ( 'error', $error_import );
					$this->redirect ( '@ps_fee_reports_import' );
				}

				$error_line = implode ( ' ; ', $array_error );
			} else {
				throw new Exception ( $e->getMessage () );
				$error_import = $this->getContext ()
					->getI18N ()
					->__ ( 'Import file failed.' );
				$this->getUser ()
					->setFlash ( 'error', $error_import );
				$this->redirect ( '@ps_fee_reports_import' );
			}
			$conn->commit ();
		} catch ( Exception $e ) {
			$conn->rollback ();
		}
		if ($false == 0) {
			$successfully = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file successfully %value% data. No error student code', array (
					'%value%' => $true ) );
			$this->getUser ()
				->setFlash ( 'notice', $successfully );
		} else {

			$successfully = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file successfully.' );

			$success_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Successfully : ' ) . $true;

			$error_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Error : ' ) . $false;

			$error_array = $this->getContext ()
				->getI18N ()
				->__ ( 'Studen code' ) . $error_line;

			$this->getUser ()
				->setFlash ( 'notice', $successfully );
			$this->getUser ()
				->setFlash ( 'notice1', $success_number );
			$this->getUser ()
				->setFlash ( 'notice2', $error_number );
			$this->getUser ()
				->setFlash ( 'notice3', $error_array );
		}

		$this->redirect ( '@ps_fee_reports_import' );
	}

	public function executeImportSaveOld(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_file = null;

		$import_filter = $request->getParameter ( 'import_filter' );

		if ($import_filter) {

			$this->ps_school_year_id = isset ( $import_filter ['ps_school_year_id'] ) ? $import_filter ['ps_school_year_id'] : 0;

			$this->ps_workplace_id = isset ( $import_filter ['ps_workplace_id'] ) ? $import_filter ['ps_workplace_id'] : 0;

			if ($this->ps_workplace_id > 0) {

				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

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
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile () );

		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );

		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_filter[%s]' );

		/**
		 * * Import file excel **
		 */

		$import_filter_form = $request->getParameter ( 'import_filter' );

		$this->formFilter->bind ( $request->getParameter ( 'import_filter' ), $request->getFiles ( 'import_filter' ) );

		// id truong hoc
		$ps_customer_id = $this->formFilter->getValue ( 'ps_customer_id' );

		$students = Doctrine::getTable ( 'Student' )->getAllStudentsByCustomerId ( $ps_customer_id );
		$array_student = array ();
		foreach ( $students as $student ) {
			array_push ( $array_student, $student->getStudentCode () );
		}
		$conn = Doctrine_Manager::connection ();
		try {
			$conn->beginTransaction ();

			if ($this->formFilter->isValid ()) {

				$user_id = myUser::getUserId ();

				$file_classify = $this->getContext ()
					->getI18N ()
					->__ ( 'Fee report import' );

				$file = $this->formFilter->getValue ( 'ps_file' );

				$filename = time () . $file->getOriginalName ();

				$file_link = 'FeeReports' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );

				$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';

				$file->save ( $path_file . $filename );

				$objPHPExcel = PHPExcel_IOFactory::load ( $path_file . $filename );

				$provinceSheet = $objPHPExcel->setActiveSheetIndex ( 0 ); // Set sheet sẽ được đọc dữ liệu

				$highestRow = $provinceSheet->getHighestRow (); // Lấy số row lớn nhất trong sheet

				$array_error = array ();
				$false = 0;
				$true = 0;
				for($row = 3; $row <= $highestRow; $row ++) {

					$student_code = $provinceSheet->getCellByColumnAndRow ( 1, $row )
						->getValue ();

					if ($student_code != '' && in_array ( $student_code, $array_student )) {

						$true ++;

						$receipt = $provinceSheet->getCellByColumnAndRow ( 2, $row )
							->getValue ();

						$receipt_date = PHPExcel_Style_NumberFormat::toFormattedString ( $receipt, "YYYY-MM-DD" );

						$receipt_title = $provinceSheet->getCellByColumnAndRow ( 3, $row )
							->getValue ();

						$receivable = $provinceSheet->getCellByColumnAndRow ( 4, $row )
							->getValue ();

						$collected_amount = $provinceSheet->getCellByColumnAndRow ( 5, $row )
							->getValue ();

						$balance_amount = $provinceSheet->getCellByColumnAndRow ( 6, $row )
							->getValue ();

						$note = $provinceSheet->getCellByColumnAndRow ( 7, $row )
							->getValue ();

						$student_id = Doctrine::getTable ( 'Student' )->findOneByStudentCode ( $student_code )
							->getId ();

						$strtime_receivable_at = PsDateTime::psDatetoTime ( $receipt_date );

						$year_month_receivable_at = PsDateTime::psTimetoDate ( $strtime_receivable_at, "Ym" );

						$psFeeReports = new PsFeeReports ();

						$psFeeReports->setStudentId ( $student_id );
						$psFeeReports->setReceivable ( $receivable );
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

							$psReceipt->setPsCustomerId ( $ps_customer_id );
							$psReceipt->setStudentId ( $student_id );
							$psReceipt->setTitle ( 'Phiếu thanh toán phí' . $receipt_date );
							$psReceipt->setReceiptNo ( time () );
							$psReceipt->setReceiptDate ( $receipt_date );
							$psReceipt->setCollectedAmount ( $collected_amount );
							$psReceipt->setBalanceAmount ( $balance_amount );
							$psReceipt->setIsCurrent ( 0 );
							$psReceipt->setIsImport ( 1 );
							$psReceipt->setPaymentStatus ( 0 );
							$psReceipt->setPaymentDate ( null );
							$psReceipt->setRelativeId ( null );
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
					} else {
						$false ++;
						array_push ( $array_error, $student_code );
					}
				}
				if ($true > 0) {
					// luu lich su import file phieu ghi no
					$ps_history_import = new PsHistoryImport ();
					$ps_history_import->setPsCustomerId ( $ps_customer_id );
					$ps_history_import->setPsWorkplaceId ( $ps_workplace_id );
					$ps_history_import->setFileName ( $filename );
					$ps_history_import->setFileLink ( $file_link );
					$ps_history_import->setFileClassify ( $file_classify );
					$ps_history_import->setUserCreatedId ( $user_id );

					$ps_history_import->save ();
				} else {
					unlink ( $path_file . $filename );
					$error_import = $this->getContext ()
						->getI18N ()
						->__ ( 'Import file failed.' );
					$this->getUser ()
						->setFlash ( 'error', $error_import );
					$this->redirect ( '@ps_fee_reports_import' );
				}

				$error_line = implode ( ' ; ', $array_error );
			} else {
				throw new Exception ( $e->getMessage () );
				$error_import = $this->getContext ()
					->getI18N ()
					->__ ( 'Import file failed.' );
				$this->getUser ()
					->setFlash ( 'error', $error_import );
				$this->redirect ( '@ps_fee_reports_import' );
			}
			$conn->commit ();
		} catch ( Exception $e ) {
			$conn->rollback ();
		}
		if ($false == 0) {
			$successfully = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file successfully %value% data. No error student code', array (
					'%value%' => $true ) );
			$this->getUser ()
				->setFlash ( 'notice', $successfully );
		} else {

			$successfully = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file successfully.' );

			$success_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Successfully : ' ) . $true;

			$error_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Error : ' ) . $false;

			$error_array = $this->getContext ()
				->getI18N ()
				->__ ( 'Studen code' ) . $error_line;

			$this->getUser ()
				->setFlash ( 'notice', $successfully );
			$this->getUser ()
				->setFlash ( 'notice1', $success_number );
			$this->getUser ()
				->setFlash ( 'notice2', $error_number );
			$this->getUser ()
				->setFlash ( 'notice3', $error_array );
		}

		$this->redirect ( '@ps_fee_reports_import' );
	}

	public function executeImportSaveOld(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_file = null;

		$import_filter = $request->getParameter ( 'import_filter' );

		if ($import_filter) {

			$this->ps_school_year_id = isset ( $import_filter ['ps_school_year_id'] ) ? $import_filter ['ps_school_year_id'] : 0;

			$this->ps_workplace_id = isset ( $import_filter ['ps_workplace_id'] ) ? $import_filter ['ps_workplace_id'] : 0;

			if ($this->ps_workplace_id > 0) {

				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

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
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile () );

		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );

		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_filter[%s]' );

		/**
		 * * Import file excel **
		 */

		$import_filter_form = $request->getParameter ( 'import_filter' );

		$this->formFilter->bind ( $request->getParameter ( 'import_filter' ), $request->getFiles ( 'import_filter' ) );

		// id truong hoc
		$ps_customer_id = $this->formFilter->getValue ( 'ps_customer_id' );

		$students = Doctrine::getTable ( 'Student' )->getAllStudentsByCustomerId ( $ps_customer_id );
		$array_student = array ();
		foreach ( $students as $student ) {
			array_push ( $array_student, $student->getStudentCode () );
		}
		$conn = Doctrine_Manager::connection ();
		try {
			$conn->beginTransaction ();

			if ($this->formFilter->isValid ()) {

				$user_id = myUser::getUserId ();

				$file_classify = $this->getContext ()
					->getI18N ()
					->__ ( 'Fee report import' );

				$file = $this->formFilter->getValue ( 'ps_file' );

				$filename = time () . $file->getOriginalName ();

				$file_link = 'FeeReports' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );

				$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';

				$file->save ( $path_file . $filename );

				$objPHPExcel = PHPExcel_IOFactory::load ( $path_file . $filename );

				$provinceSheet = $objPHPExcel->setActiveSheetIndex ( 0 ); // Set sheet sẽ được đọc dữ liệu

				$highestRow = $provinceSheet->getHighestRow (); // Lấy số row lớn nhất trong sheet

				$array_error = array ();
				$false = 0;
				$true = 0;
				for($row = 3; $row <= $highestRow; $row ++) {

					$student_code = $provinceSheet->getCellByColumnAndRow ( 1, $row )
						->getValue ();

					if ($student_code != '' && in_array ( $student_code, $array_student )) {

						$true ++;

						$receipt = $provinceSheet->getCellByColumnAndRow ( 2, $row )
							->getValue ();

						$receipt_date = PHPExcel_Style_NumberFormat::toFormattedString ( $receipt, "YYYY-MM-DD" );

						$receipt_title = $provinceSheet->getCellByColumnAndRow ( 3, $row )
							->getValue ();

						$receivable = $provinceSheet->getCellByColumnAndRow ( 4, $row )
							->getValue ();

						$collected_amount = $provinceSheet->getCellByColumnAndRow ( 5, $row )
							->getValue ();

						$balance_amount = $provinceSheet->getCellByColumnAndRow ( 6, $row )
							->getValue ();

						$note = $provinceSheet->getCellByColumnAndRow ( 7, $row )
							->getValue ();

						$student_id = Doctrine::getTable ( 'Student' )->findOneByStudentCode ( $student_code )
							->getId ();

						$strtime_receivable_at = PsDateTime::psDatetoTime ( $receipt_date );

						$year_month_receivable_at = PsDateTime::psTimetoDate ( $strtime_receivable_at, "Ym" );

						$psFeeReports = new PsFeeReports ();

						$psFeeReports->setStudentId ( $student_id );
						$psFeeReports->setReceivable ( $receivable );
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

							$psReceipt->setPsCustomerId ( $ps_customer_id );
							$psReceipt->setStudentId ( $student_id );
							$psReceipt->setTitle ( 'Phiếu thanh toán phí' . $receipt_date );
							$psReceipt->setReceiptNo ( time () );
							$psReceipt->setReceiptDate ( $receipt_date );
							$psReceipt->setCollectedAmount ( $collected_amount );
							$psReceipt->setBalanceAmount ( $balance_amount );
							$psReceipt->setIsCurrent ( 0 );
							$psReceipt->setIsImport ( 1 );
							$psReceipt->setPaymentStatus ( 0 );
							$psReceipt->setPaymentDate ( null );
							$psReceipt->setRelativeId ( null );
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
					} else {
						$false ++;
						array_push ( $array_error, $student_code );
					}
				}
				if ($true > 0) {
					// luu lich su import file phieu ghi no
					$ps_history_import = new PsHistoryImport ();
					$ps_history_import->setPsCustomerId ( $ps_customer_id );
					$ps_history_import->setPsWorkplaceId ( $ps_workplace_id );
					$ps_history_import->setFileName ( $filename );
					$ps_history_import->setFileLink ( $file_link );
					$ps_history_import->setFileClassify ( $file_classify );
					$ps_history_import->setUserCreatedId ( $user_id );

					$ps_history_import->save ();
				} else {
					unlink ( $path_file . $filename );
					$error_import = $this->getContext ()
						->getI18N ()
						->__ ( 'Import file failed.' );
					$this->getUser ()
						->setFlash ( 'error', $error_import );
					$this->redirect ( '@ps_fee_reports_import' );
				}

				$error_line = implode ( ' ; ', $array_error );
			} else {
				throw new Exception ( $e->getMessage () );
				$error_import = $this->getContext ()
					->getI18N ()
					->__ ( 'Import file failed.' );
				$this->getUser ()
					->setFlash ( 'error', $error_import );
				$this->redirect ( '@ps_fee_reports_import' );
			}
			$conn->commit ();
		} catch ( Exception $e ) {
			unlink ( $path_file . $filename );
			$conn->rollback ();
		}
		if ($false == 0) {
			$successfully = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file successfully %value% data. No error student code', array (
					'%value%' => $true ) );
			$this->getUser ()
				->setFlash ( 'notice', $successfully );
		} else {

			$successfully = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file successfully.' );

			$success_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Successfully : ' ) . $true;

			$error_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Error : ' ) . $false;

			$error_array = $this->getContext ()
				->getI18N ()
				->__ ( 'Studen code' ) . $error_line;

			$this->getUser ()
				->setFlash ( 'notice', $successfully );
			$this->getUser ()
				->setFlash ( 'notice1', $success_number );
			$this->getUser ()
				->setFlash ( 'notice2', $error_number );
			$this->getUser ()
				->setFlash ( 'notice3', $error_array );
		}

		$this->redirect ( '@ps_fee_reports_import' );
	}
}
