<?php
require_once dirname ( __FILE__ ) . '/../lib/psReceivableStudentsGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psReceivableStudentsGeneratorHelper.class.php';

/**
 * psReceivableStudents actions.
 *
 * @package kidsschool.vn
 * @subpackage psReceivableStudents
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psReceivableStudentsActions extends autoPsReceivableStudentsActions {

	// Xoa khoan phai thu cua hoc sinh - Xu ly bang ajax
	public function executeDeleteAjax(sfWebRequest $request) {

		// Chỉ xóa nếu chưa có phiếu thu- phiếu báo
		if ($request->isXmlHttpRequest ()) {

			$rs_id = $request->getParameter ( 'rs_id' );

			$receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->findOneById ( $rs_id );

			if (! $receivable_student) {
				echo 'Page Not Found or The data you asked for is secure and you do not have proper credentials. 0';
				exit ( 0 );
			} else {

				// kiem tra dieu kien hoc sinh
				$student = $receivable_student->getStudent ();

				// Neu khong co quyen Xu ly phi cho cac truong
				if (! myUser::checkAccessObject ( $student, 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
					echo 'Page Not Found or The data you asked for is secure and you do not have proper credentials. 1';
					exit ( 0 );
				} else {

					$receivable_at = $receivable_student->getReceivableAt ();
					$student_id = $receivable_student->getStudentId ();
					$is_number = $receivable_student->getIsNumber ();
					$receivable_id = $receivable_student->getReceivableId ();
					$list_receivable_students = Doctrine::getTable ( 'ReceivableStudent' )->updatedNumberReceivableStudent ( $student_id, $receivable_id, $is_number );
					// Kiem tra dieu kien xem co nam trong phieu bao, phieu thu nao khong Phieu bao - Phieu thu
					$receipt = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentNearDate ( $student_id, strtotime ( $receivable_at ) );

					if ($receipt) {
					} else {

						if ($receivable_student->delete ()) {
							foreach ( $list_receivable_students as $list_id ) {
								$receivableStudent = Doctrine::getTable ( 'ReceivableStudent' )->findOneById ( $list_id->getId () );
								$receivableStudent->setIsNumber ( $list_id->getIsNumber () - 1 );
								$receivableStudent->save ();
							}
							$receivable_students = Doctrine::getTable ( 'ReceivableStudent' )->getListReceivableStudentInMonth ( $student_id, date ( "Y-m-d", strtotime ( $receivable_at ) ) );
							$this->getUser ()
								->setFlash ( 'notice_receivable', $this->getContext ()
								->getI18N ()
								->__ ( 'Delete this receivable successfully.' ) );
							return $this->renderPartial ( 'psFeeReports/box/_list_receivable_receivable_students', array (
									'receivable_students' => $receivable_students ) );
							exit ();
						} else {
							echo 'Page Not Found or The data you asked for is secure and you do not have proper credentials. 2';
							exit ( 0 );
						}
					}
				}
			}
		}
	}

	public function executeIndex(sfWebRequest $request) {

		// sorting
		$this->filter_value = $this->getFilters ();
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
	}

	// Xóa Khoản phải thu khác trong dự kiến thu của tháng
	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$psReceivableStudents = $this->getRoute ()
			->getObject ();

		if (! $psReceivableStudents) {
			$this->forward404Unless ( sprintf ( 'Object does not exist.' ) );
		}

		$student_id = $psReceivableStudents->getStudentId ();
		$receipt_date = $psReceivableStudents->getReceiptDate ();

		$delete_amount = $psReceivableStudents->getUnitPrice ();

		$receipt = Doctrine_Core::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, strtotime ( $receipt_date ) );

		if ($receipt) {

			$receipt_id = $receipt->getId ();

			// Nếu là dịch vụ thì ko xóa
			if ($this->getRoute ()
				->getObject ()
				->getServiceId () > 0) {
				$this->getUser ()
					->setFlash ( 'error', $this->getContext ()
					->getI18N ()
					->__ ( 'You cannot delete the expected collection as a service.' ) );
				$this->redirect ( '@ps_receipts_show?id=' . $receipt_id );
			}

			// Neu khong co quyen Xu ly phi cho cac truong
			if (! myUser::checkAccessObject ( $receipt, 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
				$this->forward404Unless ( sprintf ( 'Object does not exist.' ) );
			}

			if ($receipt->getPaymentStatus () == PreSchool::ACTIVE) {
				$this->getUser ()
					->setFlash ( 'error', $this->getContext ()
					->getI18N ()
					->__ ( 'This month has been paid. You can not edit.' ) );
				$this->redirect ( '@ps_receipts_show?id=' . $receipt_id );
			}

			$receipt_no = $receipt->getReceiptNo ();
			$ps_customer_id = $receipt->getPsCustomerId ();

			// Lay Phieu báo ứng với phiếu thu này
			$ps_fee_report = Doctrine_Core::getTable ( 'PsFeeReports' )->checkFeeReportsOfMonth ( $student_id, $receipt_date );

			$conn = Doctrine_Manager::connection ();

			try {

				$conn->beginTransaction ();

				$ps_receipt_old = $receipt;
				$ps_fee_report_old = $ps_fee_report;

				// Lấy các khoản dự kiến thu trong tháng của Phiếu báo trước khi xóa
				$receivable_students = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentInMonth ( $student_id, $receipt_date );

				if ($ps_fee_report) {

					// Phải nộp = Tổng ban đầu - khoản thu dự kiến
					$so_tien_phai_nop = $ps_fee_report->getReceivable () - $delete_amount;

					// echo $so_tien_phai_nop; die;

					$ps_fee_report->setReceivable ( $so_tien_phai_nop );
					$ps_fee_report->save ();

					// Tiền dư = Đã thanh toán - Số tiền phải nộp
					$balance_amount = $receipt->getCollectedAmount () - $so_tien_phai_nop;

					$receipt->setBalanceAmount ( $balance_amount );

					$receipt->setUserUpdatedId ( $user_id );

					$receipt->save ();
				}

				// $psReceiptsActions = new psReceiptsActions();

				$this->saveHistoryReceipt ( $ps_receipt_old, $ps_fee_report_old, $receivable_students, 'delete' );

				// Xoa du kien thu
				$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
						'object' => $psReceivableStudents ) ) );

				if ($psReceivableStudents->delete ()) {
					$this->getUser ()
						->setFlash ( 'notice', 'The item was deleted successfully.' );
				}

				$conn->commit ();
			} catch ( Exception $e ) {

				$conn->rollback ();

				$this->getUser ()
					->setFlash ( 'error', 'The item was deleted fail.' );
			}
		}

		$this->redirect ( '@ps_receipts_show?id=' . $receipt_id );
	}

	/**
	 * Ham lưu history phieu báo, phiếu thu, các khoản phải thu của phiếu
	 *
	 * @param Object $ps_receipt
	 * @param Object $ps_fee_reports
	 * @param
	 *        	List Object $receivable_students
	 * @param string $action:
	 *        	edit; delete
	 * @return boolean
	 */
	public function saveHistoryReceipt($ps_receipt, $ps_fee_reports, $receivable_students = null, $action = 'edit') {

		if (! $ps_receipt || ! $ps_fee_reports) {
			return false;
		}

		$history_content = '';

		$history_content .= 'ID truong: ' . $ps_receipt->getPsCustomerId () . '\n';

		$history_content .= 'ID phieu bao: ' . $ps_fee_reports->getId () . '\n';
		$history_content .= 'Ma phieu bao: ' . $ps_fee_reports->getPsFeeReportNo () . '\n';
		$history_content .= 'Tong tien phai nop(chua co phi nop muon): ' . $ps_fee_reports->getReceivable () . '\n';

		$history_content .= 'ID phieu thu: ' . $receipt_id . '\n';
		$history_content .= 'Tieu de: ' . $ps_receipt->getTitle () . '\n';
		$history_content .= 'Ma phieu thu: ' . $ps_receipt->getReceiptNo () . '\n';
		$history_content .= 'Phieu cua thang: ' . $ps_receipt->getReceiptDate () . '\n';
		$history_content .= 'So tien da nop: ' . $ps_receipt->getCollectedAmount () . '\n';
		$history_content .= 'So du: ' . $ps_receipt->getBalanceAmount () . '\n';
		$history_content .= 'Du thang truoc: ' . $ps_receipt->getBalanceLastMonthAmount () . '\n';
		$history_content .= 'Phi nop muon: ' . $ps_receipt->getLatePaymentAmount () . '\n';
		$history_content .= 'La import(1-Import; 0-Khong): ' . $ps_receipt->getIsImport () . '\n';
		$history_content .= 'Trang thai thanh toan: ' . $ps_receipt->getPaymentStatus () . '\n';
		$history_content .= 'ID nguoi than nop tien: ' . $ps_receipt->getRelativeId () . '\n';
		$history_content .= 'Ten nguoi nop tien: ' . $ps_receipt->getPaymentRelativeName () . '\n';
		$history_content .= 'Ngay nop tien: ' . $ps_receipt->getPaymentDate () . '\n';
		$history_content .= 'Hinh thuc nop tien(TM: Tien mat ,CK: Chuyen khoan,QT: Quet the): ' . $ps_receipt->getPaymentType () . '\n';
		$history_content .= 'Ten thu ngan: ' . $ps_receipt->getCashierName () . '\n';
		$history_content .= 'Ghi chu cua phieu: ' . $ps_receipt->getNote () . '\n';
		$history_content .= 'Hien thi ra APP phu huynh: ' . $ps_receipt->getIsPublic () . '\n';
		$history_content .= 'Ghi chu sua phieu thu truc tiep: ' . $ps_receipt->getNoteEdit () . '\n';
		$history_content .= 'ID user tao ban dau: ' . $ps_receipt->getUserCreatedId () . '\n';
		$history_content .= 'ID user cap nhat cuoi: ' . $ps_receipt->getUserUpdatedId () . '\n';

		$history_content .= 'DANH SACH CAC KHOAN THU CUA PHIEU:\n';

		foreach ( $receivable_students as $receivable_student ) {

			$history_content .= 'ID: ' . $rs_id . '\n';
			$history_content .= 'ID hoc sinh: ' . $receivable_student->getStudentId () . '\n';
			$history_content .= 'ID dich vu: ' . $receivable_student->getServiceId () . '\n';
			$history_content .= 'ID khoan phai thu khac: ' . $receivable_student->getReceivableId () . '\n';
			$history_content .= 'So luong du kien: ' . $receivable_student->getByNumber () . '\n';
			$history_content .= 'Gia: ' . $receivable_student->getUnitPrice () . '\n';
			$history_content .= 'Giam tru co dinh: ' . $receivable_student->getDiscountAmount () . '\n';
			$history_content .= 'Giam tru theo %: ' . $receivable_student->getDiscount () . '\n';
			$history_content .= 'So luong su dung: ' . $receivable_student->getSpentNumber () . '\n';
			$history_content .= 'Ve muon(0: Khong phai; 1: La dich vu ve muon): ' . $receivable_student->getIsLate () . '\n';
			$history_content .= 'So lan su dung: ' . $receivable_student->getIsNumber () . '\n';
			$history_content .= 'So tien: ' . $receivable_student->getAmount () . '\n';
			$history_content .= 'Thang phat sinh phi: ' . $receivable_student->getReceivableAt () . '\n';
			$history_content .= 'Tinh vao phieu thu cua thang: ' . $receivable_student->getReceiptDate () . '\n';
			$history_content .= 'Ghi chu: ' . $receivable_student->getNote () . '\n';
			$history_content .= 'ID nguoi tao: ' . $receivable_student->getUserCreatedId () . '\n';
			$history_content .= 'Ngay tao: ' . $receivable_student->getUserCreated () . '\n';
			$history_content .= 'ID nguoi cap nhat: ' . $receivable_student->getUserUpdatedId () . '\n';
			$history_content .= 'Ngay cap nhat: ' . $receivable_student->getUserUpdated () . '\n';
		}

		$ps_history_fees = new PsHistoryFees ();

		$ps_history_fees->setPsCustomerId ( $ps_receipt->getPsCustomerId () );
		$ps_history_fees->setPsReceiptId ( $ps_receipt->getId () );
		$ps_history_fees->setReceiptNo ( $ps_receipt->getReceiptNo () );
		$ps_history_fees->setReceiptDate ( $ps_receipt->getReceiptDate () );
		$ps_history_fees->setStudentId ( $ps_receipt->getStudentId () );
		$ps_history_fees->setPsAction ( $action );
		$ps_history_fees->setHistoryContent ( $history_content );
		$ps_history_fees->setCreatedAt ( date ( "Y-m-d H:i:s" ) );
		$ps_history_fees->setUpdatedAt ( date ( "Y-m-d H:i:s" ) );
		$ps_history_fees->setUserCreatedId ( sfContext::getInstance ()->getUser ()
			->getGuardUser ()
			->getId () );

		$ps_history_fees->save ();
	}

	// xuat khoan thu theo lớp trang index
	public function executeExportReceivable(sfWebRequest $request) {

		$year_id = $request->getParameter ( 'year_id' );

		$month = $request->getParameter ( 'month' );

		$customer = $request->getParameter ( 'customer' );

		$workplace = $request->getParameter ( 'workplace' );

		$class = $request->getParameter ( 'class' );

		$receivable_id = $request->getParameter ( 'receivable_id' );

		$this->exportReportReceivableStudent ( $year_id, $month, $customer, $workplace, $class, $receivable_id );

		$this->redirect ( '@ps_receivable_students' );
	}

	protected function exportReportReceivableStudent($year_id, $month, $customer, $workplace, $class, $receivable_id) {

		$exportFile = new ExportReceivableStudentReportHelper ( $this );

		$file_template_pb = 'tkhs_phieuthu_00001.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/pschool/template_export/' . $file_template_pb;

		$school_name = Doctrine::getTable ( 'Pscustomer' )->findOneBy ( 'id', $customer );

		$exportFile->loadTemplate ( $path_template_file );

		$receivable_title = Doctrine::getTable ( 'Receivable' )->findOneBy ( 'id', $receivable_id )
			->getTitle ();

		$title_info = $receivable_title . ' (Tháng ' . $month . ')';

		if ($class > 0) {

			$title_xls = Doctrine::getTable ( 'MyClass' )->findOneBy ( 'id', $class )
				->getName ();

			$list_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentByClass ( $month, $customer, $workplace, $class, $receivable_id );

			$exportFile->setDataExportSchoolInfoExport ( $school_name, $title_info, $title_xls );

			$exportFile->setDataExportReceivableStudentByClass ( $list_student );
		} else {
			$params = array (
					'ps_school_year_id' => $year_id,
					'ps_customer_id' => $customer,
					'ps_workplace_id' => $workplace );
			// Lay danh sach lop hoc cua co so hien tai

			$list_class = Doctrine::getTable ( 'MyClass' )->getClassByParams ( $params );

			foreach ( $list_class as $class_id ) {

				$title_xls = $class_id->getTitle ();

				$class = $class_id->getId ();
				// Lay data hoc sinh
				$list_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentByClass ( $month, $customer, $workplace, $class, $receivable_id );

				/**
				 * Clone template
				 */

				$exportFile->createNewSheet ();

				$exportFile->setDataExportSchoolInfoExport ( $school_name, $title_info, $title_xls );

				$exportFile->setDataExportReceivableStudentByClass ( $list_student );
			}

			$exportFile->removeSheet ();
		}

		$exportFile->saveAsFile ( "PhieuThu_" . $month . ".xls" );
	}

	public function executeStatistic(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$year_month = null;

		$ps_school_year_id = null;

		$this->class_id = null;

		$this->list_students = $this->filter_list_student = $this->list_receivable =  array ();

		$receivable_filter = $request->getParameter ( 'receivable_filter' );

		if ($request->isMethod ( 'post' )) {

			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'receivable_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];

			$this->class_id = $value_student_filter ['class_id'];

			$this->year_month = $value_student_filter ['year_month'];

			$this->list_students = Doctrine::getTable ( 'Student' )->getListStudentServiceByClassId ( $ps_customer_id, $ps_workplace_id, $this->class_id, $this->year_month )
				->execute ();

			$this->filter_list_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentByClass ( $this->year_month, $ps_customer_id, $ps_workplace_id, $this->class_id, null );

			$this->list_receivable = Doctrine::getTable ( 'ReceivableStudent' )->getAllReceivableBySchool ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id );
		}

		$this->year_month = isset ( $receivable_filter ['year_month'] ) ? $receivable_filter ['year_month'] : date ( "m-Y" );

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

		$this->number_day = PsDateTime::psNumberDaysOfMonth ( $this->year_month );

		$this->formFilter->setDefault ( 'year_month', $this->year_month );

		if ($receivable_filter) {

			$this->ps_school_year_id = isset ( $receivable_filter ['ps_school_year_id'] ) ? $receivable_filter ['ps_school_year_id'] : 0;

			$this->ps_workplace_id = isset ( $receivable_filter ['ps_workplace_id'] ) ? $receivable_filter ['ps_workplace_id'] : 0;

			$this->class_id = isset ( $receivable_filter ['class_id'] ) ? $receivable_filter ['class_id'] : 0;

			$this->year_month = isset ( $receivable_filter ['year_month'] ) ? $receivable_filter ['year_month'] : date ( "m-Y" );

			if ($this->ps_workplace_id > 0) {

				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
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
		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		if ($this->ps_workplace_id > 0) {

			// Filters by class
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id,
							'ps_school_year_id' => $this->ps_school_year_id,
							'is_activated' => PreSchool::ACTIVE
					) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => false ) ) );
		} else {

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorPass () );
		}

		$this->formFilter->setDefault ( 'year_month', $this->year_month );

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'class_id', $this->class_id );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'receivable_filter[%s]' );
	}

	// xuat khoan thu khac theo lớp
	public function executeExportReceivableClass(sfWebRequest $request) {

		$year_id = $request->getParameter ( 'year_id' );

		$month = $request->getParameter ( 'month' );

		$customer = $request->getParameter ( 'customer' );

		$workplace = $request->getParameter ( 'workplace' );

		$class = $request->getParameter ( 'class' );

		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			$check_customer = myUser::getPscustomerID ();
			if ($check_customer != $customer) {
				$this->forward404Unless ( sprintf ( 'Object does not exist.' ) );
			}
		}

		$this->exportReportReceivableStudentByClass ( $year_id, $month, $customer, $workplace, $class );

		$this->redirect ( '@ps_receivable_students_statistic' );
	}

	protected function exportReportReceivableStudentByClass($year_id, $month, $customer, $workplace, $class) {

		$exportFile = new ExportReceivableStudentReportHelper ( $this );

		$file_template_pb = 'tkhs_phieuthu_00003.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/pschool/template_export/' . $file_template_pb;

		$school_name = Doctrine::getTable ( 'PsCustomer' )->findOneBy ( 'id', $customer );

		$exportFile->loadTemplate ( $path_template_file );

		if ($class > 0) {

			$title_xls = Doctrine::getTable ( 'MyClass' )->findOneBy ( 'id', $class )
				->getName ();

			$title_info = 'Lớp: ' . $title_xls . ' (Tháng ' . $month . ')';

			$list_student = Doctrine::getTable ( 'Student' )->getListStudentServiceByClassId ( $customer, $workplace, $class, $month )
				->execute ();

			$filter_list_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentByClass ( $month, $customer, $workplace, $class );

			$list_receivable = Doctrine::getTable ( 'ReceivableStudent' )->getAllReceivableBySchool ( $year_id, $customer, $class );

			$exportFile->setDataExportSchoolInfoExport ( $school_name, $title_info, $title_xls );

			$exportFile->setDataExportReceivableStudentByClassId ( $list_student, $filter_list_student, $list_receivable );
		} else {
			$params = array (
					'ps_school_year_id' => $year_id,
					'ps_customer_id' => $customer,
					'ps_workplace_id' => $workplace,
					'is_activated' => PreSchool::ACTIVE
			);
			// Lay danh sach lop hoc cua co so hien tai

			$list_class = Doctrine::getTable ( 'MyClass' )->getClassByParams ( $params );

			foreach ( $list_class as $class_id ) {

				$title_xls = $class_id->getTitle ();

				$title_info = 'Lớp: ' . $title_xls . ' (Tháng ' . $month . ')';

				$class = $class_id->getId ();
				// Lay data hoc sinh
				$list_student = Doctrine::getTable ( 'Student' )->getListStudentServiceByClassId ( $customer, $workplace, $class, $month )
					->execute ();

				$filter_list_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentByClass ( $month, $customer, $workplace, $class );

				$list_receivable = Doctrine::getTable ( 'ReceivableStudent' )->getAllReceivableBySchool ( $year_id, $customer, $class );

				/**
				 * Clone template
				 */

				$exportFile->createNewSheet ();

				$exportFile->setDataExportSchoolInfoExport ( $school_name, $title_info, $title_xls );

				$exportFile->setDataExportReceivableStudentByClassId ( $list_student, $filter_list_student, $list_receivable );
			}
			$exportFile->removeSheet ();
		}

		$exportFile->saveAsFile ( "CacKhoanPhaiThuKhacTheoLop_" . $month . ".xls" );
	}

	// Xem cac lan su dung khoan thu
	public function executeDetail(sfWebRequest $request) {

		// ID học sinh
		$id_student = $request->getParameter ( 'sid' );
		$id_receivable = $request->getParameter ( 'rid' );

		if ($id_student <= 0) {
			$this->forward404Unless ( $id_student, sprintf ( 'Object does not exist.' ) );
		}

		$this->student = Doctrine::getTable ( 'Student' )->findOneBy ( 'id', $id_student );

		// $this->forward404Unless(myUser::checkAccessObject($this->student, 'PS_MEDICAL_GROWTH_FILTER_SCHOOL'), sprintf('Object does not exist.', $this->student));

		// lay thong tin chi tiet dot kham cua tre
		$this->list_receivable = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentDetail ( $id_student, $id_receivable );
	}

	// Cập nhật lại giá trị lấy từ màn hình Sửa Phiếu thu
	/*
	 * public function executeSaveReceivableStudentInReceipt(sfWebRequest $request) {
	 * if ($request->isXmlHttpRequest()) {
	 * $rs_id = $request->getParameter('rs_id');
	 * if ($rs_id > 0) {
	 * $receivable_student = Doctrine::getTable('ReceivableStudent')->findOneBy('id', $rs_id);
	 * if (!$receivable_student) {
	 * echo $this->getContext ()->getI18N ()->__ ('Page Not Found or The data you asked for is secure and you do not have proper credentials.');
	 * exit(0);
	 * } else {
	 * $student = $receivable_student->getStudent();
	 * // Kiem tra quyen chon truong
	 * if (! myUser::checkAccessObject ( $student, 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
	 * echo $this->getContext ()->getI18N ()->__ ('Page Not Found or The data you asked for is secure and you do not have proper credentials.');
	 * exit ( 0 );
	 * } else {
	 * $receivable_student_form = $request->getParameter('receivable_student');
	 * $chk_validator = true;
	 * if ( isset($receivable_student_form['rs_discount_amount'])) {
	 * $rs_discount_amount = PreString::trim($receivable_student_form['rs_discount_amount']);
	 * // Validate
	 * if (!is_numeric($rs_discount_amount)) {
	 * $chk_validator = false;
	 * } else {
	 * $receivable_student->setDiscountAmount($rs_discount_amount);
	 * }
	 * }
	 * if ( isset($receivable_student_form['rs_discount'])) {
	 * $rs_discount = PreString::trim($receivable_student_form['rs_discount']);
	 * if (!is_numeric($rs_discount)) {
	 * $chk_validator = false;
	 * } else {
	 * $receivable_student->setDiscount($rs_discount);
	 * }
	 * }
	 * if ( isset($receivable_student_form['rs_spent_number'])) {
	 * $rs_spent_number = PreString::trim($receivable_student_form['rs_spent_number']);
	 * if (!is_numeric($rs_spent_number)) {
	 * $chk_validator = false;
	 * } else {
	 * $receivable_student->setSpentNumber($rs_spent_number);
	 * }
	 * }
	 * if ( isset($receivable_student_form['rs_amount'])) {
	 * $rs_amount = PreString::trim($receivable_student_form['rs_amount']);
	 * if (!is_numeric($rs_amount)) {
	 * $chk_validator = false;
	 * } else {
	 * $receivable_student->setAmount($rs_amount);
	 * }
	 * }
	 * if ( isset($receivable_student_form['rs_note'])) {
	 * $rs_note = PreString::trim($receivable_student_form['rs_note']);
	 * if (PreString::length($rs_amount) > 255) {
	 * $chk_validator = false;
	 * } else {
	 * $receivable_student->setNote($rs_note);
	 * }
	 * }
	 * if ($chk_validator) {
	 * $receivable_student->save();
	 * //return $this->renderPartial ( 'psReceipts/fees/_tr_form_field_receivable_student', array ('r_s' => $receivable_students ) );
	 * echo 'OK';
	 * exit ();
	 * } else {
	 * echo $this->getContext ()->getI18N ()->__ ('Error.');
	 * exit(0);
	 * }
	 * }
	 * }
	 * }
	 * } else {
	 * echo $this->getContext ()->getI18N ()->__ ('Page Not Found or The data you asked for is secure and you do not have proper credentials.');
	 * exit(0);
	 * }
	 * }
	 */

	// Cập nhật hàng loạt- Trên màn hình sửa phiếu thu gửi đến
	public function executeUpdateReceivableStudentReceipt(sfWebRequest $request) {

		$receivable_student_form = $request->getParameter ( 'receivable_student' );

		// print_r($receivable_student_form);

		$receipt_form = $request->getParameter ( 'receipt' );

		if (isset ( $receipt_form ['id'] ) && $receipt_form ['id'] > 0 && isset ( $receipt_form ['sid'] ) && $receipt_form ['sid'] > 0) {

			$ps_receipt = Doctrine::getTable ( 'Receipt' )->findOneById ( $receipt_form ['id'] );

			if ($ps_receipt && $ps_receipt->getStudentId () == $receipt_form ['sid']) {

				$conn = Doctrine_Manager::connection ();

				try {

					$conn->beginTransaction ();

					$arr_err_receivable_student = array ();

					$content_history_rs = 'CAC KHOAN PHI THU CUA THANG TRUOC KHI SUA:\n';

					foreach ( $receivable_student_form as $rs_id => $_receivable_student ) {

						$receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->findOneBy ( 'id', $rs_id );

						$content_history_rs .= 'ID: ' . $rs_id . '\n';
						$content_history_rs .= 'ID hoc sinh: ' . $receivable_student->getStudentId () . '\n';
						$content_history_rs .= 'ID dich vu: ' . $receivable_student->getServiceId () . '\n';
						$content_history_rs .= 'ID khoan phai thu khac: ' . $receivable_student->getReceivableId () . '\n';
						$content_history_rs .= 'So luong du kien: ' . $receivable_student->getByNumber () . '\n';
						$content_history_rs .= 'Gia: ' . $receivable_student->getUnitPrice () . '\n';
						$content_history_rs .= 'Giam tru co dinh: ' . $receivable_student->getDiscountAmount () . '\n';
						$content_history_rs .= 'Giam tru theo %: ' . $receivable_student->getDiscount () . '\n';
						$content_history_rs .= 'So luong su dung: ' . $receivable_student->getSpentNumber () . '\n';
						$content_history_rs .= 'Ve muon(0: Khong phai; 1: La dich vu ve muon): ' . $receivable_student->getIsLate () . '\n';
						$content_history_rs .= 'So lan su dung: ' . $receivable_student->getIsNumber () . '\n';
						$content_history_rs .= 'So tien: ' . $receivable_student->getAmount () . '\n';
						$content_history_rs .= 'Thang phat sinh phi: ' . $receivable_student->getReceivableAt () . '\n';
						$content_history_rs .= 'Tinh vao phieu thu cua thang: ' . $receivable_student->getReceiptDate () . '\n';
						$content_history_rs .= 'Ghi chu: ' . $receivable_student->getNote () . '\n';
						$content_history_rs .= 'ID nguoi tao: ' . $receivable_student->getUserCreatedId () . '\n';
						$content_history_rs .= 'Ngay tao: ' . $receivable_student->getUserCreated () . '\n';
						$content_history_rs .= 'ID nguoi cap nhat: ' . $receivable_student->getUserUpdatedId () . '\n';
						$content_history_rs .= 'Ngay cap nhat: ' . $receivable_student->getUserUpdated () . '\n';

						if ($receivable_student) {

							$chk_validator = true;

							if (isset ( $_receivable_student ['rs_by_number'] )) {

								$rs_by_number = PreString::trim ( $_receivable_student ['rs_by_number'] );

								if ($rs_by_number == '')
									$rs_by_number = 0;

								// Validate
								if (! is_numeric ( $rs_by_number )) {
									$chk_validator = false;
									$arr_err_receivable_student [$rs_id] ['rs_by_number'] = 'has-error';
								} else {
									$receivable_student->setByNumber ( $rs_by_number );
								}
							}

							if (isset ( $_receivable_student ['rs_discount_amount'] )) {

								$rs_discount_amount = PreString::trim ( $_receivable_student ['rs_discount_amount'] );

								if ($rs_discount_amount == '')
									$rs_discount_amount = 0;

								// Validate
								if (! is_numeric ( $rs_discount_amount )) {
									$chk_validator = false;
									$arr_err_receivable_student [$rs_id] ['rs_discount_amount'] = 'has-error';
								} else {
									$receivable_student->setDiscountAmount ( $rs_discount_amount );
								}
							}

							if (isset ( $_receivable_student ['rs_discount'] )) {

								$rs_discount = PreString::trim ( $_receivable_student ['rs_discount'] );

								if ($rs_discount == '')
									$rs_discount = 0;

								if (! is_numeric ( $rs_discount )) {
									$chk_validator = false;
									$arr_err_receivable_student [$rs_id] ['rs_discount'] = 'has-error';
								} else {
									$receivable_student->setDiscount ( $rs_discount );
								}
							}

							if (isset ( $_receivable_student ['rs_spent_number'] )) {

								$rs_spent_number = PreString::trim ( $_receivable_student ['rs_spent_number'] );

								if ($rs_spent_number == '')
									$rs_spent_number = 0;

								if (! is_numeric ( $rs_spent_number )) {
									$chk_validator = false;
									$arr_err_receivable_student [$rs_id] ['rs_spent_number'] = 'has-error';
								} else {
									$receivable_student->setSpentNumber ( $rs_spent_number );
								}
							}

							if (isset ( $_receivable_student ['rs_amount'] )) {

								$rs_amount = PreString::trim ( $_receivable_student ['rs_amount'] );

								if ($rs_amount == '')
									$rs_amount = 0;

								if (! is_numeric ( $rs_amount )) {
									$chk_validator = false;
									$arr_err_receivable_student [$rs_id] ['rs_amount'] = 'has-error';
								} else {
									$receivable_student->setAmount ( $rs_amount );
								}
							}

							if (isset ( $_receivable_student ['rs_note'] )) {

								$rs_note = PreString::trim ( $_receivable_student ['rs_note'] );

								if (PreString::length ( $rs_amount ) > 255) {
									$chk_validator = false;
									$arr_err_receivable_student [$rs_id] ['rs_note'] = 'has-error';
								} else {
									$receivable_student->setNote ( $rs_note );
								}
							}

							$this->getUser ()
								->setFlash ( 'err_receivable_student', $arr_err_receivable_student );

							if ($chk_validator) {
								$receivable_student->save ();
							}
						} else {
							$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
						}
					}

					if ($chk_validator) {

						// Lay phieu bao
						$ps_fee_reports = Doctrine::getTable ( 'PsFeeReports' )->findPsFeeReportOfStudentByDate ( $ps_receipt->getStudentId (), strtotime ( $ps_receipt->getReceiptDate () ) );

						$history_content = '';

						$history_content .= 'ID truong: ' . $ps_receipt->getPsCustomerId () . '\n';

						if ($ps_fee_reports) {
							$history_content .= 'ID phieu bao: ' . $ps_fee_reports->getId () . '\n';
							$history_content .= 'Ma phieu bao: ' . $ps_fee_reports->getPsFeeReportNo () . '\n';
							$history_content .= 'Tong tien phai nop(chua co phi nop muon): ' . $ps_fee_reports->getReceivable () . '\n';
						}

						$history_content .= 'ID phieu thu: ' . $ps_receipt->getId () . '\n';
						$history_content .= 'Tieu de: ' . $ps_receipt->getTitle () . '\n';
						$history_content .= 'Ma phieu thu: ' . $ps_receipt->getReceiptNo () . '\n';
						$history_content .= 'Phieu cua thang: ' . $ps_receipt->getReceiptDate () . '\n';
						$history_content .= 'So tien da nop: ' . $ps_receipt->getCollectedAmount () . '\n';
						$history_content .= 'So du: ' . $ps_receipt->getBalanceAmount () . '\n';
						$history_content .= 'Du thang truoc: ' . $ps_receipt->getBalanceLastMonthAmount () . '\n';
						$history_content .= 'Phi nop muon: ' . $ps_receipt->getLatePaymentAmount () . '\n';
						$history_content .= 'La import(1-Import; 0-Khong): ' . $ps_receipt->getIsImport () . '\n';
						$history_content .= 'Trang thai thanh toan: ' . $ps_receipt->getPaymentStatus () . '\n';
						$history_content .= 'ID nguoi than nop tien: ' . $ps_receipt->getRelativeId () . '\n';
						$history_content .= 'Ten nguoi nop tien: ' . $ps_receipt->getPaymentRelativeName () . '\n';
						$history_content .= 'Ngay nop tien: ' . $ps_receipt->getPaymentDate () . '\n';
						$history_content .= 'Hinh thuc nop tien(TM: Tien mat ,CK: Chuyen khoan,QT: Quet the): ' . $ps_receipt->getPaymentType () . '\n';
						$history_content .= 'Ten thu ngan: ' . $ps_receipt->getCashierName () . '\n';
						$history_content .= 'Ghi chu cua phieu: ' . $ps_receipt->getNote () . '\n';
						$history_content .= 'Hien thi ra APP phu huynh: ' . $ps_receipt->getIsPublic () . '\n';
						$history_content .= 'Ghi chu sua phieu thu truc tiep: ' . $ps_receipt->getNoteEdit () . '\n';
						$history_content .= 'ID nguoi tao: ' . $ps_receipt->getUserCreatedId () . '\n';
						$history_content .= 'ID nguoi cap nhat: ' . $ps_receipt->getUserUpdatedId () . '\n';

						$ps_history_fees = new PsHistoryFees ();

						$ps_history_fees->setPsCustomerId ( $ps_receipt->getPsCustomerId () );
						$ps_history_fees->setPsReceiptId ( $ps_receipt->getId () );
						$ps_history_fees->setReceiptNo ( $ps_receipt->getReceiptNo () );
						$ps_history_fees->setReceiptDate ( $ps_receipt->getReceiptDate () );
						$ps_history_fees->setStudentId ( $ps_receipt->getStudentId () );
						$ps_history_fees->setPsAction ( 'edit' );

						$history_content = $history_content . '\n' . $content_history_rs;

						$ps_history_fees->setHistoryContent ( $history_content );

						$ps_history_fees->setCreatedAt ( date ( "Y-m-d H:i:s" ) );
						$ps_history_fees->setUpdatedAt ( date ( "Y-m-d H:i:s" ) );
						$ps_history_fees->setUserCreatedId ( sfContext::getInstance ()->getUser ()
							->getGuardUser ()
							->getId () );

						$ps_history_fees->save ();

						$this->getUser ()
							->setFlash ( 'notice_receivable_student', 'The items was updated successfully.' );
					} else {
						$this->getUser ()
							->setFlash ( 'error_receivable_student', 'The items has not been saved due to some errors. Please check the boxes marked in red.' );
						$this->getUser ()
							->setFlash ( 'error', $this->getContext ()
							->getI18N ()
							->__ ( 'Cập nhật số liệu lỗi.' ) );
					}

					$conn->commit ();
				} catch ( Exception $e ) {

					$conn->rollback ();

					$this->getUser ()
						->setFlash ( 'error', $this->getContext ()
						->getI18N ()
						->__ ( 'Update fail.' ) . $e->getMessage () );
				}

				$this->redirect ( '@ps_receipts_edit?id=' . $ps_receipt->getId () . '#sf_fieldset_receivable_student' );
			} else {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
			}
		} else {
			$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
		}
	}
}
