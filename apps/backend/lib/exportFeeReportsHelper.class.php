<?php
class ExportFeeReportsHelper extends ExportHelper {

	public function __construct($object) {

		parent::__construct ($object);

		$this->object = $object;
	}

	/**
	 * Clone sheet
	 */
	public function createNewSheet() {

		$objWorkSheet1 = clone $this->objPHPExcel->getSheet ();

		$objWorkSheet1->setTitle ( 'Cloned Sheet' );

		$this->objPHPExcel->addSheet ( $objWorkSheet1 );

		$this->sheet_index = $this->sheet_index + 1;

		$this->objPHPExcel->setActiveSheetIndex ( $this->sheet_index );
	}

	/**
	 * Remove sheet
	 */
	public function removeSheet() {

		$this->objPHPExcel->removeSheetByIndex ( 0 );
	}

	
	/**
	 * Tao tieu de cho bao phi
	 */
	public function setCustomerInfoExportFees($ps_customer_info = null, $title_info = array()) {

		if ($ps_customer_info != null) {
			// Ve Logo
			if ($ps_customer_info->getLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $ps_customer_info->getYearData () . '/' . $ps_customer_info->getLogo () )) {

				$objDrawing = new PHPExcel_Worksheet_Drawing ();

				$objDrawing->setName ( 'Logo' );

				$objDrawing->setDescription ( $ps_customer_info->getTitle () );

				$objDrawing->setPath ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $ps_customer_info->getYearData () . '/' . $ps_customer_info->getLogo () );

				$objDrawing->setOffsetY ( 10 );
				$objDrawing->setOffsetX ( 10 );
				$objDrawing->setCoordinates ( 'A1' );
				$objDrawing->setHeight ( 80 );
				$objDrawing->setWidth ( 80 );

				$objDrawing->getShadow ()
					->setVisible ( true );
				$objDrawing->getShadow ()
					->setDirection ( 80 );
				$objDrawing->setWorksheet ( $this->objPHPExcel->getActiveSheet () );
			}

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C1', $ps_customer_info->getTitle () );

			$address = $this->object->getContext ()
				->getI18N ()
				->__ ( 'Tel2' );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C2', $ps_customer_info->getAddress () . '-' . $address . ': ' . ($ps_customer_info->getTel () != '' ? $ps_customer_info->getTel () : $ps_customer_info->getMobile ()) );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A3', $title_info ['fee_notification'] );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A4', $title_info ['fee_time'] );
		}
	}

	/**
	 * Tao tieu de bieu mau: Logo; Ten truong; Co so; Dia chi co so
	 */
	public function setTopInfoFormStatistic($header_info) {

		if ($header_info ['path_logo'] != '') {

			$objDrawing = new PHPExcel_Worksheet_Drawing ();

			// $objDrawing->setName('Logo');

			$objDrawing->setDescription ( 'Logo' );

			$objDrawing->setPath ( $header_info ['path_logo'] );

			$objDrawing->setOffsetY ( 10 );
			$objDrawing->setOffsetX ( 10 );
			$objDrawing->setCoordinates ( 'A1' );
			$objDrawing->setHeight ( 100 );
			$objDrawing->setWidth ( 100 );
			$objDrawing->getShadow ()
				->setVisible ( true );
			$objDrawing->getShadow ()
				->setDirection ( 80 );
			$objDrawing->setWorksheet ( $this->objPHPExcel->getActiveSheet () );
		}

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C1', $header_info ['school_name'] );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C2', $header_info ['wp_name'] );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C3', $header_info ['address'] );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A4', $header_info ['title_notification'] );
	}

	/**
	 * Tao thong tin hoc sinh cho bieu mau: Thông kê báo phí + Mẫu báo phí
	 */
	public function setStudentInfoExportFees($ps_student_info = null, $date_int = null, $class_name = null) {

		if ($ps_student_info != null) {

			// $full_text_name = $ps_student_info->getFirstName () . " " . $ps_student_info->getLastName () . " (" . $this->object->getContext ()->getI18N ()->__ ( 'Student code' ) . ": " . $ps_student_info->getStudentCode () . ")";

			$birthday = PsDateTime::psTimetoDate ( PsDateTime::psDatetoTime ( $ps_student_info->getBirthday (), "d-m-Y" ) );

			$full_text_name = $ps_student_info->getFirstName () . " " . $ps_student_info->getLastName () . " (" . $birthday . ")";

			if ($class_name != '') {
				$class_name = $class_name;
			} else {
				$class_name = '';
			}

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B4', $full_text_name );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G5', $ps_student_info->getStudentCode () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B5', $class_name );

			$this->objPHPExcel->getActiveSheet ()
				->setTitle ( 'PB-' . $ps_student_info->getStudentCode () );
		}
	}

	/**
	 * Set data cho thong ke chi tiet hoc phi
	 */
	public function setDataStatisticExportFees($data, $ps_fee_reports) {

		$receivable_at = $ps_fee_reports->getReceivableAt ();

		// Ma phieu bao
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G5', $ps_fee_reports->getPsFeeReportNo () );

		$current_month = false;
		
		$array_current = array();
		
		$start_row = 10;

		foreach ( $data ['receivable_student'] as $k => $rs ) {

			// Set du lieu phi
			if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) )) {

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, date ( "m/Y", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) ) );

				if ($rs->getRsReceivableId ()) {
					$title = $rs->getRTitle ();
				} elseif ($rs->getRsServiceId ()) {
					$title = $rs->getSTitle ();
				} elseif ($rs->getRsIsLate () == 1) {
					$title = $this->object->getContext ()
						->getI18N ()
						->__ ( 'Out late' );
				}

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'B' . $start_row, mb_convert_encoding ( $title, "UTF-8", "auto" ) );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'C' . $start_row, ( float ) $rs->getRsUnitPrice () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'D' . $start_row, ( float ) $rs->getRsByNumber () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'E' . $start_row, ( float ) $rs->getRsUnitPrice () * $rs->getRsByNumber () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, ( float ) $rs->getRsSpentNumber () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . $start_row, ( float ) $rs->getRsAmount () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'H' . $start_row, ($rs->getIsLate () == 1) ? $this->object->getContext ()
					->getI18N ()
					->__ ( $rs->getNote () ) : $rs->getNote () );

				$start_row = $start_row + 1;

				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
			}else{
				array_push($array_current,$rs);
			}
		}

		$this->objPHPExcel->getActiveSheet ()
			->removeRow ( $start_row, 2 );

		$start_row = $start_row + 2;

		foreach ( $array_current as $rs ) {

			if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) )) {

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, date ( "m/Y", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) ) );

				if ($rs->getRsReceivableId ()) {
					$title = $rs->getRTitle ();
				} elseif ($rs->getRsServiceId ()) {
					$title = $rs->getSTitle ();
				} elseif ($rs->getRsIsLate () == 1) {
					$title = $this->object->getContext ()
						->getI18N ()
						->__ ( 'Out late' );
				}

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'B' . $start_row, mb_convert_encoding ( $title, "UTF-8", "auto" ) );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'C' . $start_row, ( float ) $rs->getRsUnitPrice () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'D' . $start_row, ( float ) $rs->getRsByNumber () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'E' . $start_row, ( float ) $rs->getRsUnitPrice () * $rs->getRsByNumber () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, ( float ) $rs->getRsSpentNumber () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . $start_row, ( float ) $rs->getRsAmount () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'H' . $start_row, ($rs->getIsLate () == 1) ? $this->object->getContext ()
					->getI18N ()
					->__ ( $rs->getNote () ) : $rs->getNote () );

				$start_row = $start_row + 1;

				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
			}
		}

		// Tiền thừa của tháng trước
		$balanceAmountReality = $data ['collectedAmount'] - ($data ['totalAmount'] - $data ['totalAmountReceivableAt']);

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . ($start_row + 3), ( float ) $balanceAmountReality );

		if ($balanceAmountReality < 0)
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . ($start_row + 3), $this->object->getContext ()
				->getI18N ()
				->__ ( 'Old debt' ) );

		$info_date_export = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'year' ) . ' ' . date ( "Y" );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'E' . ($start_row + 5), $info_date_export );

		$this->objPHPExcel->getActiveSheet ()
			->removeRow ( $start_row, 1 );

		$this->objPHPExcel->getActiveSheet ()
			->removeRow ( $start_row, 1 );
	}

	/**
	 * Tao tieu de bieu mau: Logo; Ten truong; Co so; Dia chi co so
	 */
	public function setTopInfoFormReportFees($header_info) {

		if ($header_info ['path_logo'] != '') {

			$objDrawing = new PHPExcel_Worksheet_Drawing ();

			// $objDrawing->setName('Logo');

			$objDrawing->setDescription ( 'Logo' );

			$objDrawing->setPath ( $header_info ['path_logo'] );

			$objDrawing->setOffsetY ( 10 );
			$objDrawing->setOffsetX ( 10 );
			$objDrawing->setCoordinates ( 'B1' );
			$objDrawing->setHeight ( 100 );
			$objDrawing->setWidth ( 100 );

			$objDrawing->getShadow ()
				->setVisible ( true );
			$objDrawing->getShadow ()
				->setDirection ( 80 );
			$objDrawing->setWorksheet ( $this->objPHPExcel->getActiveSheet () );
		}

		// $this->objPHPExcel->getActiveSheet ()->setCellValue ( 'C1', $header_info ['school_name'] );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C1', $header_info ['wp_name'] );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C2', $header_info ['address'] );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A3', $header_info ['title_notification'] );

		$this->objPHPExcel->getActiveSheet ()
			->setTitle ( $header_info ['title_xls'] );
	}

	/**
	 * Tao tieu de mau phieu thu 04
	 */
	public function setTopInfoFormReportFees04($header_info,$ps_student_info, $int_receipt_date, $infoClass) {
		
		if ($header_info ['path_logo'] != '') {
			
			$objDrawing = new PHPExcel_Worksheet_Drawing ();
			
			// $objDrawing->setName('Logo');
			
			$objDrawing->setDescription ( 'Logo' );
			
			$objDrawing->setPath ( $header_info ['path_logo'] );
			
			$objDrawing->setOffsetY ( 10 );
			$objDrawing->setOffsetX ( 10 );
			$objDrawing->setCoordinates ( 'B1' );
			$objDrawing->setHeight ( 80 );
			$objDrawing->setWidth ( 80 );
			
			$objDrawing->getShadow ()
			->setVisible ( true );
			$objDrawing->getShadow ()
			->setDirection ( 80 );
			$objDrawing->setWorksheet ( $this->objPHPExcel->getActiveSheet () );
		}
		
		// $this->objPHPExcel->getActiveSheet ()->setCellValue ( 'C1', $header_info ['school_name'] );
		
		$this->objPHPExcel->getActiveSheet ()
		->setCellValue ( 'C1', $header_info ['wp_name'] );
		
		$this->objPHPExcel->getActiveSheet ()
		->setCellValue ( 'C2', $header_info ['address'] );
		
		$this->objPHPExcel->getActiveSheet ()
		->setCellValue ( 'A3', $header_info ['title_notification'] );
		
		$this->objPHPExcel->getActiveSheet ()
		->setTitle ( $header_info ['title_xls'] );
		
		if ($ps_student_info != null) {
			
			$this->objPHPExcel->getActiveSheet ()
			->setTitle ( 'PT-' . $ps_student_info->getStudentCode () );
			
			$birthday = ($ps_student_info->getBirthday () != '') ? " (" . PsDateTime::psTimetoDate ( PsDateTime::psDatetoTime ( $ps_student_info->getBirthday () ), "d-m-Y" ) . ")" : '';
			
			$full_text_name = $ps_student_info->getFirstName () . " " . $ps_student_info->getLastName () . $birthday;
			
			$class_name = isset ( $infoClass ) ? $infoClass : '';
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'B4', $full_text_name );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'E5', $ps_student_info->getStudentCode () );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'B5', $class_name );
			
		}
		
	}
	
	/**
	 * * Xuất phiếu báo theo biểu mẫu 2
	 * *
	 */
	public function setDataReportFeesTemplate2($data, $ps_fee_reports, $config_choose_charge_showlate) {

		$receivable_at = $ps_fee_reports->getReceivableAt ();

		// Ma phieu bao
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G4', $ps_fee_reports->getPsFeeReportNo () );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'I8', $data ['balance_last_month_amount'] );

		$collectedAmount = $data ['collectedAmount']; // so tien da nop

		$start_row = 11;

		$total_oldRsAmount = $tong_cac_thang_cu = $index = $tong_du_kien_cac_thang_cu = 0;

		$rs_current = array ();

		$index_row_month_pre_remove_1 = $index_row_month_pre_remove_2 = 0;

		foreach ( $data ['receivable_student'] as $r_s ) {

			if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) )) {
				$index ++;

				$tong_cac_thang_cu = $tong_cac_thang_cu + $r_s->getRsAmount ();

				$month_prev = PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, date ( 'm-Y', $month_prev ) );

				if ($r_s->getRsReceivableId ()) {
					$title_sevice = $r_s->getRTitle ();
				} elseif ($r_s->getRsServiceId ()) {
					$title_sevice = $r_s->getSTitle ();
				} elseif ($r_s->getRsIsLate () == 1) {
					$title_sevice = $this->object->getContext ()
						->getI18N ()
						->__ ( 'Out late' ) . '(' . date ( "m/Y", strtotime ( $r_s->getRsReceivableAt () ) ) . ')';
				}

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'B' . $start_row, $title_sevice );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'C' . $start_row, $r_s->getRsUnitPrice () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'D' . $start_row, $r_s->getRsByNumber () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'E' . $start_row, ($r_s->getRsDiscountAmount () > 0) ? $r_s->getRsDiscountAmount () : '' );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, ($r_s->getRsDiscount () > 0) ? $r_s->getRsDiscount () : '' );

				if ($r_s->getRsServiceId () > 0) {
					$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

					$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
				} else {
					$rs_amount = $r_s->getRsAmount ();
				}

				if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceiptDate () ) )) {
					$rs_amount = 0;
				}

				$tong_du_kien_cac_thang_cu = $tong_du_kien_cac_thang_cu + $rs_amount;

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . $start_row, $rs_amount );

				$spentNumber = $r_s->getRsEnableRoll () == 1 ? $r_s->getRsByNumber () : $r_s->getRsSpentNumber ();

				if ($r_s->getRsIsLate () == 1) {
					$spentNumber = ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . __ ( 'Minute' );
				} else {
					$spentNumber = PreNumber::number_format ( $spentNumber );
				}

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'H' . $start_row, $spentNumber );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'I' . $start_row, $r_s->getRsAmount () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'J' . $start_row, ($r_s->getRsIsLate () == 1) ? $this->object->getContext ()
					->getI18N ()
					->__ ( $r_s->getRsNote () ) : $r_s->getRsNote () );

				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
			} else {
				array_push ( $rs_current, $r_s );
			}
		}

		$index_row_month_pre_remove_1 = $start_row;

		$start_row = $start_row + 1;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . $start_row, $tong_du_kien_cac_thang_cu );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'I' . $start_row, $tong_cac_thang_cu );

		$start_row = $start_row + 1;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'I' . $start_row, $collectedAmount );

		$start_row = $start_row + 1;

		// tien thua thuc te thang truoc
		$newBalanceAmont = $collectedAmount - $tong_cac_thang_cu + $data ['balance_last_month_amount'];

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'I' . $start_row, $newBalanceAmont );

		$start_row = $start_row + 3;

		$tong_du_kien_thang_nay = 0;

		foreach ( $rs_current as $r_s ) {

			$month_prev = PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, date ( 'm-Y', $month_prev ) );

			if ($r_s->getRsReceivableId ()) {
				$title_sevice = $r_s->getRTitle ();
			} elseif ($r_s->getRsServiceId ()) {
				$title_sevice = $r_s->getSTitle ();
			} elseif ($r_s->getRsIsLate () == 1) {
				$title_sevice = __ ( 'Out late' ) . '(' . format_date ( $r_s->getRsReceivableAt (), "MM/yyyy" ) . ')';
			}

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $title_sevice );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $r_s->getRsUnitPrice () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $r_s->getRsByNumber () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, ($r_s->getRsDiscountAmount () > 0) ? $r_s->getRsDiscountAmount () : '' );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, ($r_s->getRsDiscount () > 0) ? $r_s->getRsDiscount () : '' );

			// Phi du kien
			if ($r_s->getRsServiceId () > 0) {

				$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

				$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
				$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
			} else {
				$rs_amount = $r_s->getRsAmount ();
				$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
			}

			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, $rs_amount );

			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, $r_s->getRsNote() );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
		}

		$index_row_month_pre_remove_2 = $start_row;

		$start_row = $start_row + 1;

		$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, $tong_du_kien_thang_nay );

		$balanceAmountReality = $newBalanceAmont;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . ($start_row + 1), ( float ) $balanceAmountReality );

		$tongphainop = $tong_du_kien_thang_nay - $balanceAmountReality;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . ($start_row + 2), $tongphainop );

		if ($balanceAmountReality < 0) {
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . ($start_row + 1), 'KH nợ' );
		}
		$info_date_export = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'year' ) . ' ' . date ( "Y" );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . ($start_row + 3), $info_date_export );
		// Delete Row
		$this->objPHPExcel->getActiveSheet ()
			->removeRow ( $index_row_month_pre_remove_2, 1 );
		$this->objPHPExcel->getActiveSheet ()
			->removeRow ( $index_row_month_pre_remove_1, 1 );
	}

	/**
	 * * Xuất phiếu báo theo biểu mẫu 3
	 * *
	 */
	public function setDataReportFeesTemplate3($data, $ps_fee_reports, $config_choose_charge_showlate) {

		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'E4', $ps_fee_reports->getPsFeeReportNo () );
		
		$receivable_at = $ps_fee_reports->getReceivableAt ();
		
		$collectedAmount = $data ['collectedAmount']; // so tien da nop
		
		$balance_last_month_amount = $data ['balance_last_month_amount'];
		
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A6', $this->object->getContext () ->getI18N () ->__ ( 'Du kien phieu thu thang' ).date('m-Y',strtotime($receivable_at)) );
		
		//echo $psConfigLatePayment; die;
		$start_row = 8;
		$index = 0;
		
		$tong_du_kien_thang_cu = $tong_cac_thang_cu = $rs_amount = 0;
		$rs_current = array ();
		$service_id = $service_detail = array();
		$tong_du_kien_thang_nay = 0;
		
		foreach ( $data ['receivable_student'] as $rs ) {
			if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) )) {
				
				$tong_cac_thang_cu = $tong_cac_thang_cu + $rs->getRsAmount ();
				
				$spentNumber = $rs->getRsEnableRoll () == 1 ? $rs->getRsByNumber () : $rs->getRsSpentNumber ();
				
				$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();
				
				$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
				
				if ($rs->getRsServiceId () > 0) {
					$service_id[$rs->getRsServiceId ()] = array('amount'=>($rs_amount - $rs->getRsAmount ()),'number'=>$spentNumber) ;
				}
				if($rs->getRsIsLate() == 1){
					$spentNumber = ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . __ ( 'Minute' );
					$service_id['is_late'] = array('amount'=>($rs->getRsAmount ()),'number'=>$spentNumber) ;
				}
				
				$tong_du_kien_thang_cu = $tong_du_kien_thang_cu + $rs_amount;
				
			}else {
				array_push ( $rs_current, $rs );
			}
		}
		//print_r($service_id); die;
		foreach ( $rs_current as $r_s ) {
			
			$tien_thua = $number_use = $number_not_use = '';
			
			if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) )) {
				
				$index ++;
				
				$month_prev = PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () );
				
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $index );
				
				if ($r_s->getRsReceivableId ()) {
					$title_sevice = $r_s->getRTitle ();
				} elseif ($r_s->getRsServiceId ()) {
					$title_sevice = $r_s->getSTitle ();
				} elseif ($r_s->getRsIsLate () == 1) {
					$title_sevice = $this->object->getContext ()
					->getI18N ()
					->__ ( 'Out late' ) . '(' . date ( "m/Y", strtotime ( $r_s->getRsReceivableAt () ) ) . ')';
				}
				
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $title_sevice );
				
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $r_s->getRsUnitPrice () );
				
				// Phi du kien
				
				if ($r_s->getRsServiceId () > 0) {
					
					$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();
					
					$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
					
					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
					
					if(isset($service_id[$r_s->getRsServiceId ()]['amount'])){
						$tien_thua = $service_id[$r_s->getRsServiceId ()]['amount'];
						$number_use = $service_id[$r_s->getRsServiceId ()]['number'];
					}
					
				} else {
					$rs_amount = $r_s->getRsAmount ();
					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
				}
				
				$spentNumber = $r_s->getRsEnableRoll () == 1 ? $r_s->getRsByNumber () : $r_s->getRsSpentNumber ();
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . $start_row, $r_s->getRsByNumber() );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $rs_amount );
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $start_row, $number_use );
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $start_row, $tien_thua );
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $start_row, $r_s->getRsNote () );
				
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			}
		}
		
		$remove_index = $start_row;
		
		if(isset($service_id['is_late'])){
			
			$start_row = $start_row + 1;
			$title_sevice = $this->object->getContext ()->getI18N ()->__ ( 'Out late' );
			$index = $index+1;
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $index );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . $start_row, $service_id['is_late']['number'] );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, (0 - $service_id['is_late']['amount']) );
			
		}else{
			$this->objPHPExcel->getActiveSheet () ->removeRow ( $start_row, 1 );
		}
		
		$new_balance_last_month_amount2 = $collectedAmount - $tong_du_kien_thang_cu + $data ['balance_last_month_amount'];
		
		if($new_balance_last_month_amount2 != 0){
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			$title_sevice = $this->object->getContext ()->getI18N ()->__ ( 'No thang truoc' );
			$index = $index+1;
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $index );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, $new_balance_last_month_amount2 );
			
			if($new_balance_last_month_amount2 > 0){
				$debt = $this->object->getContext ()->getI18N ()->__ ('Nha truong dang no');
			}else{
				$debt = $this->object->getContext ()->getI18N ()->__ ('Phu huynh dang no');
			}
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, $debt );
		}
		
		if($collectedAmount <= 0){
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			$title_sevice = $this->object->getContext ()->getI18N ()->__ ( 'No thang truoc' );
			$index = $index+1;
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $index );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, $collectedAmount - $tong_cac_thang_cu + $data ['balance_last_month_amount'] );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, $this->object->getContext ()->getI18N ()->__ ('Old debt') );
		}
		
		$start_row = $start_row + 1;
		
		$this->objPHPExcel->getActiveSheet () ->removeRow ( $remove_index, 1 );
		
		// tien thua thuc te thang truoc
		$newBalanceAmont = $collectedAmount - $tong_cac_thang_cu + $data ['balance_last_month_amount'];
		//$newBalanceAmont = $ps_receipt->getBalanceLastMonthAmount ();
		//echo $newBalanceAmont; die;
		$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $newBalanceAmont );
		
		$info_date_export = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' . $this->object->getContext ()
		->getI18N ()
		->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' . $this->object->getContext ()
		->getI18N () ->__ ( 'year' ) . ' ' . date ( "Y" );
		
		$start_row = $start_row + 4;
		$this->objPHPExcel->getActiveSheet ()
		->setCellValue ( 'F' . $start_row, $info_date_export );
		
	}

	/**
	 * Xuất phiếu bao của học sinh theo biểu mẫu 4
	 */
	public function setDataExportReportTemplate4($data, $ps_fee_reports, $config_choose_charge_showlate) {
		
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'E4', $ps_fee_reports->getPsFeeReportNo () );
		
		$receivable_at = $ps_fee_reports->getReceivableAt ();
		
		$collectedAmount = $data ['collectedAmount']; // so tien da nop
		
		$balance_last_month_amount = $data ['balance_last_month_amount'];
		
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A6', $this->object->getContext () ->getI18N () ->__ ( 'Du kien phieu thu thang' ).date('m-Y',strtotime($receivable_at)) );
		
		//echo $psConfigLatePayment; die;
		$start_row = 8;
		$index = 0;
		
		$tong_cac_thang_cu = $rs_amount = 0;
		$rs_current = array ();
		$service_id = $service_detail = array();
		$tong_du_kien_thang_nay = 0;
		
		foreach ( $data ['receivable_student'] as $rs ) {
			if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) )) {
				
				$tong_cac_thang_cu = $tong_cac_thang_cu + $rs->getRsAmount ();
				
				$spentNumber = $rs->getRsEnableRoll () == 1 ? $rs->getRsByNumber () : $rs->getRsSpentNumber ();
				/*
				if ($rs->getIsTypeFee () == 1) {
					$spentNumber = $spentNumber;
				}else{
					$spentNumber = '';
				}
				*/
				$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();
				
				$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
				
				//if ($rs->getRsEnableRoll() == 0) { // Neu loai dich vu khong co dinh
					if ($rs->getRsServiceId () > 0) {
						$service_id[$rs->getRsServiceId ()] = array('amount'=>($rs_amount - $rs->getRsAmount ()),'number'=>$spentNumber) ;
					}
				//}
				if($rs->getRsIsLate() == 1){
					$spentNumber = ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . __ ( 'Minute' );
					$service_id['is_late'] = array('amount'=>($rs->getRsAmount ()),'number'=>$spentNumber) ;
				}
				
			}else {
				array_push ( $rs_current, $rs );
			}
		}
		//print_r($service_id); die;
		foreach ( $rs_current as $r_s ) {
			
			$tien_thua = $number_use = $number_not_use = '';
			
			if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) )) {
				
				$index ++;
				
				$month_prev = PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () );
				
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $index );
				
				if ($r_s->getRsReceivableId ()) {
					$title_sevice = $r_s->getRTitle ();
				} elseif ($r_s->getRsServiceId ()) {
					$title_sevice = $r_s->getSTitle ();
				} elseif ($r_s->getRsIsLate () == 1) {
					$title_sevice = $this->object->getContext ()
					->getI18N ()
					->__ ( 'Out late' ) . '(' . date ( "m/Y", strtotime ( $r_s->getRsReceivableAt () ) ) . ')';
				}
				
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $title_sevice );
				
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $r_s->getRsUnitPrice () );
				
				// Phi du kien
				
				if ($r_s->getRsServiceId () > 0) {
					
					$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();
					
					$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
					
					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
					
					if(isset($service_id[$r_s->getRsServiceId ()]['amount'])){
						$tien_thua = $service_id[$r_s->getRsServiceId ()]['amount'];
						if($r_s->getIsTypeFee () == 1){
							$number_not_use = $service_id[$r_s->getRsServiceId ()]['number'];
						}else{
							$number_use = $service_id[$r_s->getRsServiceId ()]['number'];
						}
					}
					
				} else {
					$rs_amount = $r_s->getRsAmount ();
					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
				}
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . $start_row, $r_s->getRsSpentNumber () );
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $rs_amount );
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $start_row, $number_use );
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $start_row, $number_not_use );
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $start_row, $tien_thua );
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'I' . $start_row, $r_s->getRsNote () );
				
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			}
		}
		
		$remove_index = $start_row;
		
		if(isset($service_id['is_late'])){ // Neu co ve muon
			
			$start_row = $start_row + 1;
			$title_sevice = $this->object->getContext ()->getI18N ()->__ ( 'Out late' );
			$index = $index+1;
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $index );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . $start_row, $service_id['is_late']['number'] );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, (0 - $service_id['is_late']['amount']) );
			
		}else{
			$this->objPHPExcel->getActiveSheet () ->removeRow ( $start_row, 1 );
		}
		// Neu co khoan du dau ky
		if($balance_last_month_amount != 0){
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			$title_sevice = $this->object->getContext ()->getI18N ()->__ ( 'Balance last month amount' );
			$index = $index+1;
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $index );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, $balance_last_month_amount );
			
		}
		
		if($collectedAmount <= 0){
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			$title_sevice = $this->object->getContext ()->getI18N ()->__ ( 'No thang truoc' );
			$index = $index+1;
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $index );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, $collectedAmount - $tong_cac_thang_cu + $data ['balance_last_month_amount'] );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'I' . $start_row, $this->object->getContext ()->getI18N ()->__ ('Old debt') );
		}
		
		$start_row = $start_row + 1;
		
		$this->objPHPExcel->getActiveSheet () ->removeRow ( $remove_index, 1 );
		
		// tien thua thuc te thang truoc
		$newBalanceAmont = $collectedAmount - $tong_cac_thang_cu + $data ['balance_last_month_amount'];
		
		//echo $newBalanceAmont; die;
		$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $newBalanceAmont );
		
		$info_date_export = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' . $this->object->getContext ()
		->getI18N ()
		->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' . $this->object->getContext ()
		->getI18N () ->__ ( 'year' ) . ' ' . date ( "Y" );
		
		$start_row = $start_row + 4;
		$this->objPHPExcel->getActiveSheet ()
		->setCellValue ( 'G' . $start_row, $info_date_export );
		
	}
	
	// Set data xuat ra Báo phí rút gọn
	public function setDataReportFees($data, $ps_fee_reports) {

		$receivable_at = $ps_fee_reports->getReceivableAt ();

		// Ma phieu bao
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G4', $ps_fee_reports->getPsFeeReportNo () );

		$start_row = 10;

		$i = $total_oldRsAmount = 0;

		$rs_current = array ();

		foreach ( $data ['receivable_student'] as $k => $rs ) {

			if ((date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) ))) {
				$i ++;
				if ($rs->getRsReceivableId ()) {
					$title = $rs->getRTitle ();
				} elseif ($rs->getRsServiceId ()) {
					$title = $rs->getSTitle ();
				} elseif ($rs->getRsIsLate () == 1) {
					$title = $this->object->getContext ()
						->getI18N ()
						->__ ( 'Out late' );
				}

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $i );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'B' . $start_row, mb_convert_encoding ( $title, "UTF-8", "auto" ) );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'C' . $start_row, $rs->getRsUnitPrice () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'D' . $start_row, $rs->getRsByNumber () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'E' . $start_row, ( float ) $rs->getRsUnitPrice () * $rs->getRsByNumber () );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, $rs->getRsSpentNumber () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . $start_row, $rs->getRsAmount () );

				$start_row = $start_row + 1;

				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
			} else {
				$total_oldRsAmount = $total_oldRsAmount + $rs->getRsAmount (); // Tong tien khong chứa Về muộn
			}
		}

		// Thong so cuoi phiếu

		// Tiền thừa của tháng trước
		// $balanceAmountReality = $data ['collectedAmount'] - ($data ['totalAmount'] - $data ['totalAmountReceivableAt']);

		$balanceAmountReality = $data ['collectedAmount'] - ($total_oldRsAmount - $data ['balance_last_month_amount']);

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . ($start_row + 3), ( float ) $balanceAmountReality );

		if ($balanceAmountReality < 0)
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . ($start_row + 3), 'KH nợ' );

		$info_date_export = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'year' ) . ' ' . date ( "Y" );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F' . ($start_row + 5), $info_date_export );

		$this->objPHPExcel->getActiveSheet ()
			->removeRow ( $start_row, 1 );

		$this->objPHPExcel->getActiveSheet ()
			->removeRow ( $start_row, 1 );
	}

	/**
	 * Tao tieu de bieu mau: Logo; Ten truong; Co so; Dia chi co so cho phieu thu
	 */
	public function setTopInfoFormReceiptFees($header_info, $a4 = true) {

		if ($header_info ['path_logo'] != '') {

			// Lien 1
			$objDrawing_R1 = new PHPExcel_Worksheet_Drawing ();

			$objDrawing_R1->setDescription ( 'Logo' );

			$objDrawing_R1->setPath ( $header_info ['path_logo'] );

			$objDrawing_R1->setOffsetY ( 3 );
			$objDrawing_R1->setOffsetX ( 10 );
			$objDrawing_R1->setCoordinates ( 'B1' );
			$objDrawing_R1->setHeight ( 100 );
			$objDrawing_R1->setWidth ( 100 );
			$objDrawing_R1->getShadow ()
				->setVisible ( true );
			$objDrawing_R1->getShadow ()
				->setDirection ( 80 );
			$objDrawing_R1->setWorksheet ( $this->objPHPExcel->getActiveSheet () );
			// == END

			if ($a4) {
				// Lien 2
				$objDrawing_R2 = new PHPExcel_Worksheet_Drawing ();

				$objDrawing_R2->setDescription ( 'Logo' );

				$objDrawing_R2->setPath ( $header_info ['path_logo'] );

				$objDrawing_R2->setOffsetY ( 3 );
				$objDrawing_R2->setOffsetX ( 10 );
				$objDrawing_R2->setCoordinates ( 'J1' );
				$objDrawing_R2->setHeight ( 100 );
				$objDrawing_R2->setWidth ( 100 );
				$objDrawing_R2->getShadow ()
					->setVisible ( true );
				$objDrawing_R2->getShadow ()
					->setDirection ( 80 );
				$objDrawing_R2->setWorksheet ( $this->objPHPExcel->getActiveSheet () );
				// == END
			}
		}

		// Lien 1
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D1', $header_info ['school_name'] );

		// $this->objPHPExcel->getActiveSheet ()->setCellValue ( 'D2', $header_info ['wp_name'] );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D3', $header_info ['address'] );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A4', $header_info ['title_notification'] );

		if ($a4) {
			// Lien 2
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'L1', $header_info ['school_name'] );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'L2', $header_info ['wp_name'] );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'L3', $header_info ['address'] );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I4', $header_info ['title_notification'] );
		}

		$this->objPHPExcel->getActiveSheet ()
			->setTitle ( $header_info ['title_xls'] );
	}

	/**
	 * Tao thong tin hoc sinh cho bieu mau phieu thu
	 */
	public function setStudentInfoExportReceipt($ps_student_info = null, $date_int = null, $infoClass = null, $a4 = true) {

		if ($ps_student_info != null) {

			$this->objPHPExcel->getActiveSheet ()
				->setTitle ( 'PT-' . $ps_student_info->getStudentCode () );

			$birthday = ($ps_student_info->getBirthday () != '') ? " (" . PsDateTime::psTimetoDate ( PsDateTime::psDatetoTime ( $ps_student_info->getBirthday () ), "d-m-Y" ) . ")" : '';

			$full_text_name = $ps_student_info->getFirstName () . " " . $ps_student_info->getLastName () . $birthday;

			$class_name = isset ( $infoClass ) ? $infoClass : '';

			// Lien 1
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B6', $full_text_name );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G6', $ps_student_info->getStudentCode () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B7', $class_name );

			// Lien 2
			if ($a4) {
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'J6', $full_text_name );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'O6', $ps_student_info->getStudentCode () );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'J7', $class_name );
			}
		}
	}

	/**
	 * Xuat file xls phiếu báo cả lớp mẫu 1
	 */
	public function setDataExportReportByClass($all_data_fee, $psClass, $int_receipt_date, $class_name = null) {

		$start_row = 1;

		$workplace_info = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Address' ) . ": " . $psClass->getAddress () . '-' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'Tel2' ) . ": " . $psClass->getPhone ();
		if ($psClass->getEmail () != '') {
			$workplace_info = $workplace_info . '-' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'Email' ) . ': ' . $psClass->getEmail ();
		}

		if ($psClass->getLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $psClass->getYearData () . '/' . $psClass->getLogo () )) {
			$header_logo = sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $psClass->getYearData () . '/' . $psClass->getLogo ();
		} else {
			$header_logo = '';
		}

		if ($class_name) {
			$class_name = $class_name;
			$title_xls = date ( "mY", $int_receipt_date ) . '_' . $class_name;
		} else {
			$class_name = $psClass->getClName ();
			$title_xls = date ( "mY", $int_receipt_date ) . '_' . $psClass->getClName ();
		}

		$title_xls = substr ( $title_xls, 0, 30 );

		$title_notification = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Notice of tuition fees' ) . ' ' . date ( "m-Y", $int_receipt_date );

		$this->objPHPExcel->getActiveSheet ()
			->setTitle ( $title_xls );

			
			$estimated_fees = $this->object->getContext ()->getI18N ()->__ ( 'Estimated fees' );
			$fee_expected = $this->object->getContext ()->getI18N ()->__ ( 'Fee expected' );
			$songaynghi = $this->object->getContext ()->getI18N ()->__ ( 'So ngay nghi' );
			$excess_money = $this->object->getContext ()->getI18N ()->__ ( 'Excess money' );
			
			$previous = $this->object->getContext ()->getI18N ()->__ ( 'Payment fees for previous month' );
			$name_class = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Class' );
			$lien1 = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Lien 1' );
			$lien2 = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Lien 2' );
			$receipt_no = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Report no' ); // ma phieu thu
			$name = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Student' ); // ho ten
			$student_code = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Student code' );
			$oul_late = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Out late' ); // ve muon
			$service = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Total expected' ); // dich vu khac
			$estimated = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Estimated fees this month' ); // du kien thang nay
			$name_fees = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Name fees' ); // ten dich vu
			$price = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Price' ); // Tien
			$quantily = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Quantily expected full' ); // So luong du kien
			$discount_fixed = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Discount fixed' ); // Giam tru co dinh
			$discount = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Discount' ); // Giam tru phan tram
			$temporary_money = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Temporary money' ); // tam tinh
			$Used = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Used' ); // su dung
			$actual_costs = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Actual costs' ); // phi thuc te
			$Note = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Note' );
			$total_provisional = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Total provisional' ); // tong tam tinh
			$tongdukien = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Total expected' ); // tong du kien
			$dathanhtoan = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Paid' ); // da thanh toan
			$tienduno = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Debt' ); // Tiền dư nợ
			$tongthucte = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Total reality' ); // tong thuc te
			$price_pre_month = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Price previous month' ); // thua thang truoc
			$late_payment = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Late payment' ); // Phi nop muon
			$relative_payment = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Relative payment' ); // Phu hunh nop
			$tienthua = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Amount relative' ); // Tien thua
			$tongphainop = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Total amount payment' ); // Tong phai nop
			$relative = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Relative' ); // Phu huynh
			$nguoilap = $this->object->getContext ()
			->getI18N ()
			->__ ( 'User create' ); // Nguoi lap
			$cashier = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Cashier' ); // Thu ngan
			$kyten = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Ki ten' ); // ky ten
			$month = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Month' ); // ky ten
			
			$style_center = array (
					'alignment' => array (
							'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ) );
			$style_right = array (
					'alignment' => array (
							'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT ) );
			
			$style_left = array (
					'alignment' => array (
							'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT ) );
			
		
		foreach ( $all_data_fee as $data_fee ) {

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':B' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'C' . $start_row . ':G' . $start_row );
			if ($header_logo != '') {

				$objDrawing = new PHPExcel_Worksheet_Drawing ();

				// $objDrawing->setName('Logo');

				$objDrawing->setDescription ( 'Logo' );

				$objDrawing->setPath ( $header_logo );

				$objDrawing->setOffsetY ( 3 );
				$objDrawing->setOffsetX ( 3 );
				$objDrawing->setCoordinates ( 'B' . $start_row );
				$objDrawing->setHeight ( 80 );
				$objDrawing->setWidth ( 80 );

				$objDrawing->getShadow ()
					->setVisible ( true );
				$objDrawing->getShadow ()
					->setDirection ( 80 );
				$objDrawing->setWorksheet ( $this->objPHPExcel->getActiveSheet () );
			}

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $psClass->getTitle () );

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':B' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'C' . $start_row . ':G' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $workplace_info );

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':G' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $title_notification );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_center );

			$start_row = $start_row + 2;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 2 );

			// Ma phieu thu
			// Lien 1
			// $this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A'.$start_row, $lien1 );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $receipt_no );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $data_fee ['report_no'] );

			// ten hoc sinh
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $name );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $student_code );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'B' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $data_fee ['student_name'] );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $data_fee ['student_code'] );

			// tieu de : cac khoan thu thang truoc
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':G' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $previous );

			// Tong cac khoan phi (ko tinh về muộn) của các tháng trước
			$total_oldRsAmount = 0;
			$total_oldRsLateAmount = 0;

			// tieu de phi thang nay
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, 'STT' );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'B' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $name_fees );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $price );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, $quantily );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $temporary_money );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $Note );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . $start_row . ':O' . $start_row )
				->applyFromArray ( $style_center );

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$tong_du_kien_thang_nay = 0;

			$i = 1;

			foreach ( $data_fee ['receivable_student'] as $k => $rs ) {
				if ((date ( "Ym", PsDateTime::psDatetoTime ( $data_fee ['ps_fee_receipt']->getReceiptDate () ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) ))) {
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'B' . $start_row . ':C' . $start_row );
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'J' . $start_row . ':K' . $start_row );

					if ($rs->getRsReceivableId ()) {
						$title = $rs->getRTitle ();
					} elseif ($rs->getRsServiceId ()) {
						$title = $rs->getSTitle ();
					} elseif ($rs->getRsIsLate () == 1) {
						$title = $this->object->getContext ()
							->getI18N ()
							->__ ( 'Out late' );
					}

					// Phi du kien
					if ($rs->getRsServiceId () > 0) {
						$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();
						$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
					} else {
						$rs_amount = $rs->getRsAmount ();
					}

					// Liên 1
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'A' . $start_row, $i );
					$i ++;
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'A' . ($start_row) )
						->applyFromArray ( $style_center );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'B' . $start_row, mb_convert_encoding ( $title, "UTF-8", "auto" ) );

					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'D' . $start_row, $rs->getRsUnitPrice () );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'E' . $start_row, $rs->getRsByNumber () );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'F' . $start_row, $rs_amount );
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'F' . ($start_row) )
						->applyFromArray ( $style_right );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'G' . $start_row, $rs->getRsNote () );

					if ($rs->getRsServiceId () > 0) {

						$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();

						$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
						$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
					} else {
						$rs_amount = $rs->getRsAmount ();
						$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
					}

					$start_row = $start_row + 1;

					$this->objPHPExcel->getActiveSheet ()
						->insertNewRowBefore ( $start_row, 1 );
				} else {
					$total_oldRsAmount = $total_oldRsAmount + $rs->getRsAmount (); // Tong tien khong chứa Về muộn
				}
			}

			// tong du kien thang nay
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':E' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $total_provisional );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $tong_du_kien_thang_nay );

			// tien thua thang truoc
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':E' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $price_pre_month );
			$tien_thua_thang_truoc = $data_fee ['collectedAmount'] - ($total_oldRsAmount - $data_fee ['balance_last_month_amount']);

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . ($start_row), $tien_thua_thang_truoc );

			// phi nop muon

			if ($data_fee ['psConfigLatePayment'] > 0) {
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . $start_row . ':E' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $late_payment );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, $data_fee ['psConfigLatePayment'] );
			}
			// tong tien phai nop
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
			$tongtienphainop = $tong_du_kien_thang_nay - $tien_thua_thang_truoc + $data_fee ['psConfigLatePayment'];

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':E' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $tongphainop );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $tongtienphainop );

			if ($data_fee ['ps_fee_receipt']->getCollectedAmount () > 0) {
				// phu huynh nop tien
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . $start_row . ':E' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $relative_payment );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, $data_fee ['ps_fee_receipt']->getCollectedAmount () );

				// Tien du
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );

				if ($data_fee ['ps_fee_receipt']->getPaymentStatus () == PreSchool::ACTIVE) {
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'A' . $start_row . ':E' . $start_row );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'A' . $start_row, $tienthua );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'F' . $start_row, $data_fee ['ps_fee_receipt']->getBalanceAmount () );
				} else {
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'A' . $start_row . ':E' . $start_row );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'A' . $start_row, $tienthua );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'F' . $start_row, $data_fee ['ps_fee_receipt']->getCollectedAmount () - $tongtienphainop );
				}
			}

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$info_date_export = $this->object->getContext ()
				->getI18N ()
				->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'year' ) . ' ' . date ( "Y" );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'F' . $start_row . ':G' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . ($start_row), $info_date_export );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'F' . ($start_row) )
				->applyFromArray ( $style_center );

			$start_row = $start_row + 4;

			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 4 );

			$this->objPHPExcel->getActiveSheet ()
				->setBreak ( 'A' . $start_row, PHPExcel_Worksheet::BREAK_ROW );

			$start_row = $start_row + 1;
		}
	}

	/**
	 * Xuat file xls phiếu báo cả lớp mẫu 2
	 */
	public function setDataExportReportByClassTemplate2($all_data_fee, $psClass, $int_receipt_date, $config_choose_charge_showlate, $class_name = null) {

		$start_row = 1;

		$workplace_info = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Address' ) . ": " . $psClass->getAddress () . '-' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'Tel2' ) . ": " . $psClass->getPhone ();
		if ($psClass->getEmail () != '') {
			$workplace_info = $workplace_info . '-' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'Email' ) . ': ' . $psClass->getEmail ();
		}

		if ($psClass->getLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $psClass->getYearData () . '/' . $psClass->getLogo () )) {
			$header_logo = sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $psClass->getYearData () . '/' . $psClass->getLogo ();
		} else {
			$header_logo = '';
		}

		if ($class_name) {
			$class_name = $class_name;
			$title_xls = date ( "mY", $int_receipt_date ) . '_' . $class_name;
		} else {
			$class_name = $psClass->getClName ();
			$title_xls = date ( "mY", $int_receipt_date ) . '_' . $psClass->getClName ();
		}

		$title_xls = substr ( $title_xls, 0, 30 );

		$title_notification = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Notice of tuition fees' ) . ' ' . date ( "m-Y", $int_receipt_date );

		$this->objPHPExcel->getActiveSheet ()
			->setTitle ( $title_xls );
		
			
			$estimated_fees = $this->object->getContext ()->getI18N ()->__ ( 'Estimated fees' );
			$fee_expected = $this->object->getContext ()->getI18N ()->__ ( 'Fee expected' );
			$songaynghi = $this->object->getContext ()->getI18N ()->__ ( 'So ngay nghi' );
			$excess_money = $this->object->getContext ()->getI18N ()->__ ( 'Excess money' );
			
			$previous = $this->object->getContext ()->getI18N ()->__ ( 'Payment fees for previous month' );
			$name_class = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Class' );
			$lien1 = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Lien 1' );
			$lien2 = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Lien 2' );
			$receipt_no = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Report no' ); // ma phieu thu
			$name = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Student' ); // ho ten
			$student_code = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Student code' );
			$oul_late = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Out late' ); // ve muon
			$service = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Total expected' ); // dich vu khac
			$estimated = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Estimated fees this month' ); // du kien thang nay
			$name_fees = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Name fees' ); // ten dich vu
			$price = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Price' ); // Tien
			$quantily = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Quantily expected full' ); // So luong du kien
			$discount_fixed = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Discount fixed' ); // Giam tru co dinh
			$discount = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Discount' ); // Giam tru phan tram
			$temporary_money = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Temporary money' ); // tam tinh
			$Used = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Used' ); // su dung
			$actual_costs = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Actual costs' ); // phi thuc te
			$Note = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Note' );
			$total_provisional = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Total provisional' ); // tong tam tinh
			$tongdukien = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Total expected' ); // tong du kien
			$dathanhtoan = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Paid' ); // da thanh toan
			$tienduno = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Debt' ); // Tiền dư nợ
			$tongthucte = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Total reality' ); // tong thuc te
			$price_pre_month = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Price previous month' ); // thua thang truoc
			$late_payment = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Late payment' ); // Phi nop muon
			$relative_payment = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Relative payment' ); // Phu hunh nop
			$tienthua = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Amount relative' ); // Tien thua
			$tongphainop = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Total amount payment' ); // Tong phai nop
			$relative = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Relative' ); // Phu huynh
			$nguoilap = $this->object->getContext ()
			->getI18N ()
			->__ ( 'User create' ); // Nguoi lap
			$cashier = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Cashier' ); // Thu ngan
			$kyten = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Ki ten' ); // ky ten
			$month = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Month' ); // ky ten
			
			$style_center = array (
					'alignment' => array (
							'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ) );
			$style_right = array (
					'alignment' => array (
							'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT ) );
			
			$style_left = array (
					'alignment' => array (
							'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT ) );
			
			
		foreach ( $all_data_fee as $data_fee ) {

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':B' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'C' . $start_row . ':J' . $start_row );
			if ($header_logo != '') {

				$objDrawing = new PHPExcel_Worksheet_Drawing ();

				// $objDrawing->setName('Logo');

				$objDrawing->setDescription ( 'Logo' );

				$objDrawing->setPath ( $header_logo );

				$objDrawing->setOffsetY ( 3 );
				$objDrawing->setOffsetX ( 3 );
				$objDrawing->setCoordinates ( 'B' . $start_row );
				$objDrawing->setHeight ( 80 );
				$objDrawing->setWidth ( 80 );

				$objDrawing->getShadow ()
					->setVisible ( true );
				$objDrawing->getShadow ()
					->setDirection ( 80 );
				$objDrawing->setWorksheet ( $this->objPHPExcel->getActiveSheet () );
			}

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $psClass->getTitle () );

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':B' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'C' . $start_row . ':J' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $workplace_info );

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':J' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $title_notification );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_center );

			$start_row = $start_row + 2;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 2 );

			// Ma phieu thu
			// Lien 1
			// $this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A'.$start_row, $lien1 );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $receipt_no );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'G' . $start_row . ':J' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $data_fee ['report_no'] );

			// ten hoc sinh
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $name );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $student_code );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'B' . $start_row . ':E' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $data_fee ['student_name'] );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'G' . $start_row . ':J' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $data_fee ['student_code'] );

			// Lop hoc
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $name_class );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $class_name );

			// Khoan thua cua thang truoc

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':H' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_left );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $es_timated );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I' . $start_row, $data_fee ['balance_last_month_amount'] );

			// tieu de : cac khoan thu thang truoc
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':J' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $previous );

			$receivable_at = $data_fee ['ps_fee_receipt']->getReceiptDate (); // Tháng thu phí

			$collectedAmount = $data_fee ['collectedAmount'];

			$tong_cac_thang_cu = 0;
			$tong_du_kien_cac_thang_cu = $tong_du_kien_thang_nay = 0;

			// tieu de phi thang nay
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $month );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $name_fees );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $price );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $quantily );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, $discount_fixed );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $discount );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $temporary_money );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . $start_row, $Used );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I' . $start_row, $actual_costs );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'J' . $start_row, $Note );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . $start_row . ':J' . $start_row )
				->applyFromArray ( $style_center );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . $start_row . ':J' . $start_row )
				->applyFromArray ( array (
					'borders' => array (
							'allborders' => array (
									'style' => PHPExcel_Style_Border::BORDER_THIN,
									'color' => array (
											'rgb' => '000000' ) ) ) ) );

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$rs_current = array ();
			foreach ( $data_fee ['receivable_student'] as $k => $rs ) {

				if ((date ( "Ym", PsDateTime::psDatetoTime ( $data_fee ['ps_fee_receipt']->getReceiptDate () ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) ))) {

					$tong_cac_thang_cu = $tong_cac_thang_cu + $rs->getRsAmount ();

					if ($rs->getRsReceivableId ()) {
						$title = $rs->getRTitle ();
					} elseif ($rs->getRsServiceId ()) {
						$title = $rs->getSTitle ();
					} elseif ($rs->getRsIsLate () == 1) {
						$title = $this->object->getContext ()
							->getI18N ()
							->__ ( 'Out late' ) . '(' . date ( "m/Y", $rs->getRsReceivableAt () ) . ')';
					}

					// Phi du kien
					if ($rs->getRsServiceId () > 0) {
						$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();
						$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
					} else {
						$rs_amount = $rs->getRsAmount ();
					}

					// Liên 1
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'A' . $start_row, date ( 'm-Y', PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) ) );
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'A' . ($start_row) )
						->applyFromArray ( $style_center );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'B' . $start_row, mb_convert_encoding ( $title, "UTF-8", "auto" ) );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'C' . $start_row, $rs->getRsUnitPrice () );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'D' . $start_row, $rs->getRsByNumber () );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'E' . $start_row, $rs->getRsDiscountAmount () );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'F' . $start_row, $rs->getRsDiscount () );
					if ($rs->getRsServiceId () > 0) {
						$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();

						$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
					} else {
						$rs_amount = $rs->getRsAmount ();
					}

					if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceiptDate () ) )) {
						$rs_amount = 0;
					}

					$tong_du_kien_cac_thang_cu = $tong_du_kien_cac_thang_cu + $rs_amount;

					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'G' . $start_row, $rs_amount );

					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'G' . ($start_row) )
						->applyFromArray ( $style_right );

					$spentNumber = $rs->getRsEnableRoll () == 1 ? $rs->getRsByNumber () : $rs->getRsSpentNumber ();

					if ($rs->getRsIsLate () == 1) {
						$spentNumber = ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . $this->object->getContext ()
							->getI18N ()
							->__ ( 'Minute' );
					} else {
						$spentNumber = PreNumber::number_format ( $spentNumber );
					}

					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'H' . $start_row, $spentNumber );

					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'I' . ($start_row) )
						->applyFromArray ( $style_right );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'I' . $start_row, $rs->getRsAmount () );

					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'J' . $start_row, ($rs->getRsIsLate () == 1) ? $this->object->getContext ()
						->getI18N ()
						->__ ( $rs->getRsNote () ) : $rs->getRsNote () );

					$start_row = $start_row + 1;

					$this->objPHPExcel->getActiveSheet ()
						->insertNewRowBefore ( $start_row, 1 );
				} else {
					array_push ( $rs_current, $rs );
				}
			}

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':F' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $tongdukien );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_right );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $tong_du_kien_cac_thang_cu );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . $start_row, $tongthucte );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'H' . ($start_row) )
				->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I' . $start_row, $tong_cac_thang_cu );

			// da thanh toan
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . $start_row, $dathanhtoan );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'H' . ($start_row) )
				->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I' . $start_row, $data_fee ['collectedAmount'] );

			// Du thuc te thang truoc
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$newBalanceAmont = $data_fee ['collectedAmount'] - $tong_cac_thang_cu + $data_fee ['balance_last_month_amount'];

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . $start_row, $tienduno );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'H' . ($start_row) )
				->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I' . $start_row, $newBalanceAmont );

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':J' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_left );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $estimated );

			// tieu de phi thang nay
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $month );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $name_fees );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $price );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $quantily );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, $discount_fixed );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $discount );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $temporary_money );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'H' . $start_row . ':J' . $start_row );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . $start_row, $Note );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . $start_row . ':J' . $start_row )
				->applyFromArray ( $style_center );

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			foreach ( $rs_current as $k => $r_s ) {

				$month_prev = PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, date ( 'm-Y', $month_prev ) );

				if ($r_s->getRsReceivableId ()) {
					$title_sevice = $r_s->getRTitle ();
				} elseif ($r_s->getRsServiceId ()) {
					$title_sevice = $r_s->getSTitle ();
				} elseif ($r_s->getRsIsLate () == 1) {
					$title_sevice = $this->object->getContext ()
						->getI18N ()
						->__ ( 'Out late' ) . '(' . date ( "m/Y", $r_s->getRsReceivableAt () ) . ')';
				}

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'B' . $start_row, $title_sevice );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'C' . $start_row, $r_s->getRsUnitPrice () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'D' . $start_row, $r_s->getRsByNumber () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'E' . $start_row, ($r_s->getRsDiscountAmount () > 0) ? $r_s->getRsDiscountAmount () : '' );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, ($r_s->getRsDiscount () > 0) ? $r_s->getRsDiscount () : '' );

				// Phi du kien
				if ($r_s->getRsServiceId () > 0) {

					$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

					$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
				} else {
					$rs_amount = $r_s->getRsAmount ();
					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
				}
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'G' . ($start_row) )
					->applyFromArray ( $style_right );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . $start_row, $rs_amount );

				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
			}

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':F' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $total_provisional );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $tong_du_kien_thang_nay );

			// Thong so cuoi phiếu
			// Tiền thừa của tháng trước
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			// $tien_thua_thang_truoc = $data_fee ['collectedAmount'] - ($data_fee ['totalAmount'] - $data_fee ['totalAmountReceivableAt']);
			$tien_thua_thang_truoc = $newBalanceAmont;

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':F' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $price_pre_month );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . ($start_row), $tien_thua_thang_truoc );
			if ($tien_thua_thang_truoc < 0) {
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'H' . ($start_row), $this->object->getContext ()
					->getI18N ()
					->__ ( 'Old debt' ) );
			}

			if ($data_fee ['psConfigLatePayment'] > 0) { // neu có phí nộp muộn
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . $start_row . ':F' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $late_payment );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'A' . ($start_row) )
					->applyFromArray ( $style_right );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . ($start_row), $data_fee ['psConfigLatePayment'] );
			}

			$tongphainop = $tong_du_kien_thang_nay - $tien_thua_thang_truoc + $data_fee ['psConfigLatePayment'];

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':F' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $tongtienphainop );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . ($start_row), $tongphainop );

			if ($data_fee ['ps_fee_receipt']->getPaymentStatus () == PreSchool::ACTIVE) {

				// phu huynh nop tien
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . $start_row . ':F' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $relative_payment );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . $start_row, $data_fee ['ps_fee_receipt']->getCollectedAmount () );

				// Tien du
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . $start_row . ':F' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $tienthua );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . $start_row, $data_fee ['ps_fee_receipt']->getBalanceAmount () );
			}

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
			$info_date_export = $this->object->getContext ()
				->getI18N ()
				->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'year' ) . ' ' . date ( "Y" );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'H' . $start_row . ':J' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . ($start_row), $info_date_export );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . $start_row . ':J' . $start_row )
				->applyFromArray ( array (
					'borders' => array (
							'allborders' => array (
									'style' => PHPExcel_Style_Border::BORDER_NONE ) ) ) );

			$start_row = $start_row + 4;

			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 4 );

			$this->objPHPExcel->getActiveSheet ()
				->setBreak ( 'A' . $start_row, PHPExcel_Worksheet::BREAK_ROW );
			$start_row = $start_row + 1;
		}
	}

	/**
	 * Xuat file xls phiếu báo cả lớp mẫu 3
	 */
	public function setDataExportReportByClassTemplate3($all_data_fee, $psClass, $int_receipt_date, $config_choose_charge_showlate,$receipt_title) {
		
		$title_info = $psClass->getTitle ();
		
		$workplace_info = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Address' ) . ": " . $psClass->getAddress () . '-' . $this->object->getContext ()
		->getI18N ()
		->__ ( 'Tel2' ) . ": " . $psClass->getPhone ();
		if ($psClass->getEmail () != '') {
			$workplace_info = $workplace_info . '-' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'Email' ) . ': ' . $psClass->getEmail ();
		}
		
		if ($psClass->getLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $psClass->getYearData () . '/' . $psClass->getLogo () )) {
			$header_logo = sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $psClass->getYearData () . '/' . $psClass->getLogo ();
		} else {
			$header_logo = '';
		}
		
		$title_xls = date ( "mY", $int_receipt_date ) . '_' . $psClass->getClName ();
		$class_name = $psClass->getClName ();
		
		$title_xls = substr ( $title_xls, 0, 30 );
		$title_notification = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Tuition receipts' ) . ' ' . date ( "m-Y", $int_receipt_date );
		
		$this->objPHPExcel->getActiveSheet ()
		->setTitle ( $title_xls );
		
		$start_row = 1;
		
		$estimated_fees = $this->object->getContext ()->getI18N ()->__ ( 'Estimated fees' );
		$fee_expected = $this->object->getContext ()->getI18N ()->__ ( 'Fee expected' );
		$songaynghi = $this->object->getContext ()->getI18N ()->__ ( 'So ngay nghi' );
		$excess_money = $this->object->getContext ()->getI18N ()->__ ( 'Excess money' );
		$quantily = $this->object->getContext ()->getI18N ()->__ ( 'Quantily' );
		
		$name_class = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Class' );
		$receipt_no = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Receipt no' ); // ma phieu thu
		$report_no = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Fee report no' ); // ma phieu bao
		$name = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Student' ); // ho ten
		$student_code = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Student code' );
		$oul_late = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Out late' ); // ve muon
		$service = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total expected' ); // dich vu khac
		$estimated = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Estimated fees this month' ); // du kien thang nay
		$name_fees = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Name fees' ); // ten dich vu
		$price = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Price' ); // Tien
		
		$temporary_money = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Temporary money' ); // tam tinh
		$Used = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Used' ); // su dung
		$actual_costs = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Actual costs' ); // phi thuc te
		$Note = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Note' );
		$total_provisional = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total provisional' ); // tong tam tinh
		$tongdukien = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total expected' ); // tong du kien
		$dathanhtoan = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Paid' ); // da thanh toan
		$tienduno = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Debt' ); // Tiền dư nợ
		$tongthucte = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total reality' ); // tong thuc te
		$price_pre_month = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Price previous month' ); // thua thang truoc
		$late_payment = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Late payment' ); // Phi nop muon
		$relative_payment = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Relative payment' ); // Phu hunh nop
		$tienthua = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Amount relative' ); // Tien thua
		$tongphainop = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total amount payment' ); // Tong phai nop
		$relative = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Relative' ); // Phu huynh
		$nguoilap = $this->object->getContext ()
		->getI18N ()
		->__ ( 'User create' ); // Nguoi lap
		$cashier = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Cashier' ); // Thu ngan
		$kyten = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Ki ten' ); // ky ten
		$month = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Month' ); // ky ten
		
		$style_center = array (
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ) );
		$style_right = array (
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT ) );
		
		$style_left = array (
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT ) );
		
		foreach ( $all_data_fee as $data_fee ) {
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( "A" . ($start_row) . ":B" . ($start_row) );
			
			if ($header_logo != '') {
				
				$objDrawing = new PHPExcel_Worksheet_Drawing ();
				
				$objDrawing->setDescription ( 'Logo' );
				
				$objDrawing->setPath ( $header_logo );
				
				$objDrawing->setOffsetY ( 3 );
				$objDrawing->setOffsetX ( 3 );
				$objDrawing->setCoordinates ( 'B' . $start_row );
				$objDrawing->setHeight ( 80 );
				$objDrawing->setWidth ( 80 );
				
				$objDrawing->getShadow ()
				->setVisible ( true );
				$objDrawing->getShadow ()
				->setDirection ( 80 );
				$objDrawing->setWorksheet ( $this->objPHPExcel->getActiveSheet () );
			}
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( "C" . ($start_row) . ":I" . ($start_row) );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $title_info );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( "A" . ($start_row) . ":B" . ($start_row) );
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( "C" . ($start_row) . ":H" . ($start_row) );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $workplace_info );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'A' . $start_row . ':H' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $title_notification );
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'A' . ($start_row) )
			->applyFromArray ( $style_center );
			
			$start_row = $start_row + 2;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 2 );
			
			// Ma phieu thu
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $name );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'B' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $data_fee ['student_name'] );
			
			if($receipt_title == 'PT'){
				$receipt_no = $receipt_no;
			}else{
				$receipt_no = $report_no;
			}
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . $start_row, $receipt_no );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'E' . $start_row . ':H' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $data_fee ['report_no'] );
			
			// ten lop
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $name_class );
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'B' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'B' . $start_row, $class_name );
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D' . $start_row, $student_code );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'E' . $start_row . ':H' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'E' . $start_row, $data_fee ['student_code'] );
			
			// tieu de : cac khoan du kien thu
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'A' . $start_row . ':H' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $estimated_fees );
			
			$receivable_at = $data_fee ['ps_fee_receipt']->getReceiptDate (); // Tháng thu phí
			
			$collectedAmount = $data_fee ['collectedAmount'];
			
			$tong_cac_thang_cu = 0;
			$tong_du_kien_cac_thang_cu = $tong_du_kien_thang_nay = 0;
			
			$service_id = $rs_current = array ();
			foreach ( $data_fee ['receivable_student'] as $k => $rs ) {
				
				if ((date ( "Ym", PsDateTime::psDatetoTime ( $data_fee ['ps_fee_receipt']->getReceiptDate () ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) ))) {
					
					$tong_cac_thang_cu = $tong_cac_thang_cu + $rs->getRsAmount ();
					
					$spentNumber = $rs->getRsEnableRoll () == 1 ? $rs->getRsByNumber () : $rs->getRsSpentNumber ();
					
					$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();
					$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
					
					if ($rs->getRsServiceId () > 0) {
						$service_id[$rs->getRsServiceId ()] = array('amount'=>($rs_amount - $rs->getRsAmount ()),'number'=>$spentNumber) ;
					}
					if($rs->getRsIsLate() == 1){
						$spentNumber = ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . __ ( 'Minute' );
						$service_id['is_late'] = array('amount'=>($rs->getRsAmount ()),'number'=>$spentNumber) ;
					}
					
				} else {
					array_push ( $rs_current, $rs );
				}
			}
			
			$newBalanceAmont = $data_fee ['collectedAmount'] - $tong_cac_thang_cu + $data_fee ['balance_last_month_amount'];
			
			// tieu de phi thang nay
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, 'STT' );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'A' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $name_fees );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'B' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'C' . $start_row, $price );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'C' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . $start_row, $quantily );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'D' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $fee_expected );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'E' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . $start_row, 'SL SD' );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'F' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, $excess_money );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'G' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, $Note );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'H' . $start_row ) ->applyFromArray ( $style_center );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			
			foreach ( $rs_current as $k => $r_s ) {
				
				$number_use = $tien_thua = '';
				if ((date ( "Ym", PsDateTime::psDatetoTime ( $data_fee ['ps_fee_receipt']->getReceiptDate () ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) ))) {
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $k+1 );
					
					if ($r_s->getRsReceivableId ()) {
						$title_sevice = $r_s->getRTitle ();
					} elseif ($r_s->getRsServiceId ()) {
						$title_sevice = $r_s->getSTitle ();
					} elseif ($r_s->getRsIsLate () == 1) {
						$title_sevice = $this->object->getContext ()
						->getI18N ()
						->__ ( 'Out late' ) . '(' . date ( "m/Y", strtotime ( $r_s->getRsReceivableAt () ) ) . ')';
					}
					// Phi du kien
					if ($r_s->getRsServiceId () > 0) {
						
						$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();
						$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
						$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
						
						if(isset($service_id[$r_s->getRsServiceId ()]['amount'])){
							$tien_thua = $service_id[$r_s->getRsServiceId ()]['amount'];
							$number_use = $service_id[$r_s->getRsServiceId ()]['number'];
						}
						
					} else {
						$rs_amount = $r_s->getRsAmount ();
						$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
					}
					
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'B' . $start_row ) ->applyFromArray ( $style_left );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'C' . $start_row, $r_s->getRsUnitPrice () );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'C' . $start_row ) ->applyFromArray ( $style_right );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . $start_row, $r_s->getRsByNumber () );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'D' . $start_row ) ->applyFromArray ( $style_center );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $rs_amount );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'E' . $start_row ) ->applyFromArray ( $style_right );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . $start_row, $number_use );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'F' . $start_row ) ->applyFromArray ( $style_center );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, $tien_thua );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'G' . $start_row ) ->applyFromArray ( $style_center );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, $r_s->getRsNote () );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'H' . $start_row ) ->applyFromArray ( $style_right );
					
					$start_row = $start_row + 1;
					$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				}
			}
			
			if(isset($service_id['is_late'])){
				
				$start_row = $start_row + 1;
				$title_sevice = $this->object->getContext ()->getI18N ()->__ ( 'Out late' );
				$k = $k+1;
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $k );
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . $start_row, $service_id['is_late']['number'] );
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, (0 - $service_id['is_late']['amount']) );
				
			}
			/*
			if($data_fee ['balance_last_month_amount'] != 0){
				
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				$title_sevice = $this->object->getContext ()->getI18N ()->__ ( 'Balance last month amount' );
				$k = $k+1;
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $k );
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, $data_fee ['balance_last_month_amount'] );
				
			}
			*/
			/*Thong so cuoi phiếu */
			// Tong tien du kien trong thang
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $total_provisional );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'A' . ($start_row) ) ->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $tong_du_kien_thang_nay );
			
			// Tiền thừa của tháng trước
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			
			$tien_thua_thang_truoc = $newBalanceAmont;
			
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $price_pre_month );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'A' . ($start_row) ) ->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . ($start_row), ( float ) $tien_thua_thang_truoc );
			if ($tien_thua_thang_truoc < 0) {
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . ($start_row), $this->object->getContext ()
						->getI18N ()
						->__ ( 'Old debt' ) );
			}
			
			if ($data_fee ['psConfigLatePayment'] > 0) { // neu có phí nộp muộn
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $late_payment );
				$this->objPHPExcel->getActiveSheet () ->getStyle ( 'A' . ($start_row) ) ->applyFromArray ( $style_right );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . ($start_row), $data_fee ['psConfigLatePayment'] );
			}
			
			$tongtienphainop = $tong_du_kien_thang_nay - $tien_thua_thang_truoc + $data_fee ['psConfigLatePayment'];
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $tongphainop );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'A' . ($start_row) ) ->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . ($start_row), $tongtienphainop );
			
			if ($data_fee ['ps_fee_receipt']->getPaymentStatus () == PreSchool::ACTIVE) {
				// phu huynh nop tien
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $relative_payment );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $data_fee ['ps_fee_receipt']->getCollectedAmount () );
				
				// Tien du
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $tienthua );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $data_fee ['ps_fee_receipt']->getBalanceAmount () );
				
			} else {
				// phu huynh nop tien
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $relative_payment );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, '' );
				
				// Tien du
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $tienthua );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, '' );
			}
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			$info_date_export = $this->object->getContext ()
			->getI18N () ->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' .
			$this->object->getContext () ->getI18N () ->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' .
			$this->object->getContext () ->getI18N () ->__ ( 'year' ) . ' ' . date ( "Y" );
			
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'F' . $start_row . ':H' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . ($start_row), $info_date_export );
			
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'A' . $start_row . ':H' . $start_row )
			->applyFromArray ( array (
					'borders' => array (
							'allborders' => array (
									'style' => PHPExcel_Style_Border::BORDER_NONE ) ) ) );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'A' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . ($start_row), $relative );
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'A' . ($start_row) )
			->applyFromArray ( $style_center );
			
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'D' . $start_row . ':E' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . ($start_row), $nguoilap );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'D' . ($start_row) ) ->applyFromArray ( $style_center );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'F' . $start_row . ':H' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F' . ($start_row), $cashier );
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'F' . ($start_row) )
			->applyFromArray ( $style_center );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'A' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . ($start_row), $kyten );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'D' . $start_row . ':E' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D' . ($start_row), $kyten );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'F' . $start_row . ':H' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F' . ($start_row), $kyten );
			
			$start_row = $start_row + 4;
			
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 4 );
			
			$this->objPHPExcel->getActiveSheet ()
			->setBreak ( 'A' . $start_row, PHPExcel_Worksheet::BREAK_ROW );
			
			$start_row = $start_row + 1;
		}
	}

	/**
	 * Xuat file xls phiếu thu cả lớp mẫu 1
	 */
	public function setDataExportReceiptByClass($all_data_fee, $psClass, $int_receipt_date, $a4 = true, $class_name = null) {

		$title_info = $psClass->getTitle ();
		$workplace_info = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Address' ) . ": " . $psClass->getAddress () . '-' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'Tel2' ) . ": " . $psClass->getPhone ();
		if ($psClass->getEmail () != '') {
			$workplace_info = $workplace_info . '-' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'Email' ) . ': ' . $psClass->getEmail ();
		}

		if ($psClass->getLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $psClass->getYearData () . '/' . $psClass->getLogo () )) {
			$header_logo = sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $psClass->getYearData () . '/' . $psClass->getLogo ();
		} else {
			$header_logo = '';
		}

		if ($class_name) {
			$class_name = $class_name;
			$title_xls = date ( "mY", $int_receipt_date ) . '_' . $class_name;
		} else {
			$class_name = $psClass->getName ();
			$title_xls = date ( "mY", $int_receipt_date ) . '_' . $psClass->getClName ();
		}
		$title_xls = substr ( $title_xls, 0, 30 );
		$title_notification = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Tuition receipts' ) . ' ' . date ( "m-Y", $int_receipt_date );
		$this->objPHPExcel->getActiveSheet ()
			->setTitle ( $title_xls );

		$start_row = 1;

		
		$estimated_fees = $this->object->getContext ()->getI18N ()->__ ( 'Estimated fees' );
		$fee_expected = $this->object->getContext ()->getI18N ()->__ ( 'Fee expected' );
		$songaynghi = $this->object->getContext ()->getI18N ()->__ ( 'So ngay nghi' );
		$excess_money = $this->object->getContext ()->getI18N ()->__ ( 'Excess money' );
		
		$previous = $this->object->getContext ()->getI18N ()->__ ( 'Payment fees for previous month' );
		$name_class = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Class' );
		$lien1 = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Lien 1' );
		$lien2 = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Lien 2' );
		$receipt_no = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Report no' ); // ma phieu thu
		$name = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Student' ); // ho ten
		$student_code = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Student code' );
		$oul_late = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Out late' ); // ve muon
		$service = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total expected' ); // dich vu khac
		$estimated = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Estimated fees this month' ); // du kien thang nay
		$name_fees = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Name fees' ); // ten dich vu
		$price = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Price' ); // Tien
		$quantily = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Quantily expected full' ); // So luong du kien
		$discount_fixed = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Discount fixed' ); // Giam tru co dinh
		$discount = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Discount' ); // Giam tru phan tram
		$temporary_money = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Temporary money' ); // tam tinh
		$Used = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Used' ); // su dung
		$actual_costs = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Actual costs' ); // phi thuc te
		$Note = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Note' );
		$total_provisional = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total provisional' ); // tong tam tinh
		$tongdukien = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total expected' ); // tong du kien
		$dathanhtoan = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Paid' ); // da thanh toan
		$tienduno = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Debt' ); // Tiền dư nợ
		$tongthucte = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total reality' ); // tong thuc te
		$price_pre_month = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Price previous month' ); // thua thang truoc
		$late_payment = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Late payment' ); // Phi nop muon
		$relative_payment = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Relative payment' ); // Phu hunh nop
		$tienthua = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Amount relative' ); // Tien thua
		$tongphainop = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total amount payment' ); // Tong phai nop
		$relative = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Relative' ); // Phu huynh
		$nguoilap = $this->object->getContext ()
		->getI18N ()
		->__ ( 'User create' ); // Nguoi lap
		$cashier = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Cashier' ); // Thu ngan
		$kyten = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Ki ten' ); // ky ten
		$month = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Month' ); // ky ten
		
		$style_center = array (
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ) );
		$style_right = array (
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT ) );
		
		$style_left = array (
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT ) );
		
		
		foreach ( $all_data_fee as $data_fee ) {

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( "A" . ($start_row) . ":C" . ($start_row) );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( "I" . ($start_row) . ":K" . ($start_row) );

			if ($header_logo != '') {

				$objDrawing = new PHPExcel_Worksheet_Drawing ();

				// $objDrawing->setName('Logo');

				$objDrawing->setDescription ( 'Logo' );

				$objDrawing->setPath ( $header_logo );

				$objDrawing->setOffsetY ( 10 );
				$objDrawing->setOffsetX ( 10 );
				$objDrawing->setCoordinates ( 'B' . $start_row );
				$objDrawing->setHeight ( 80 );
				$objDrawing->setWidth ( 80 );

				$objDrawing->getShadow ()
					->setVisible ( true );
				$objDrawing->getShadow ()
					->setDirection ( 80 );
				$objDrawing->setWorksheet ( $this->objPHPExcel->getActiveSheet () );

				if ($a4) {
					// Lien 2
					$objDrawing_R2 = new PHPExcel_Worksheet_Drawing ();

					$objDrawing_R2->setDescription ( 'Logo' );

					$objDrawing_R2->setPath ( $header_logo );

					$objDrawing_R2->setOffsetY ( 10 );
					$objDrawing_R2->setOffsetX ( 10 );
					$objDrawing_R2->setCoordinates ( 'J' . $start_row );
					$objDrawing_R2->setHeight ( 80 );
					$objDrawing_R2->setWidth ( 80 );
					$objDrawing_R2->getShadow ()
						->setVisible ( true );
					$objDrawing_R2->getShadow ()
						->setDirection ( 80 );
					$objDrawing_R2->setWorksheet ( $this->objPHPExcel->getActiveSheet () );
					// == END
				}
			}

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( "D" . ($start_row) . ":G" . ($start_row) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $title_info );

			if ($a4) {
				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( "L" . ($start_row) . ":O" . ($start_row) );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'L' . $start_row, $title_info );
			}

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( "A" . ($start_row) . ":C" . ($start_row) );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( "D" . ($start_row) . ":G" . ($start_row) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $workplace_info );
			if ($a4) {
				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( "I" . ($start_row) . ":K" . ($start_row) );
				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( "L" . ($start_row) . ":O" . ($start_row) );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'L' . $start_row, $workplace_info );
			}
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':G' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $title_notification );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_center );

			if ($a4) {
				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( "I" . ($start_row) . ":O" . ($start_row) );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'I' . $start_row, $title_notification );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'I' . ($start_row) )
					->applyFromArray ( $style_center );
			}

			$start_row = $start_row + 2;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 2 );

			// Ma phieu thu
			// Lien 1
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $lien1 );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $receipt_no );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $data_fee ['ps_fee_receipt']->getReceiptNo () );
			// Lien 2
			if ($a4) {
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'I' . $start_row, $lien2 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'N' . $start_row, $receipt_no );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'O' . $start_row, $data_fee ['ps_fee_receipt']->getReceiptNo () );
			}
			// ten hoc sinh
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $name );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $student_code );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'B' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $data_fee ['student_name'] );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $data_fee ['student_code'] );

			if ($a4) {
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'I' . $start_row, $name );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'N' . $start_row, $student_code );
				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'J' . $start_row . ':K' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'J' . $start_row, $data_fee ['student_name'] );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'O' . $start_row, $data_fee ['student_code'] );
			}
			// Lop hoc
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $name_class );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $class_name );

			if ($a4) {
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'I' . $start_row, $name_class );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'J' . $start_row, $class_name );
			}
			// tieu de : cac khoan thu thang truoc
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':G' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $previous );

			if ($a4) {
				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'I' . $start_row . ':O' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'I' . $start_row, $previous );
			}

			// Tong cac khoan phi (ko tinh về muộn) của các tháng trước
			$total_oldRsAmount = 0;
			$total_oldRsLateAmount = 0;

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':G' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $estimated );

			if ($a4) {
				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'I' . $start_row . ':O' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'I' . $start_row, $estimated );
			}
			// tieu de phi thang nay
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, 'STT' );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'B' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $name_fees );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $price );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, $quantily );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $temporary_money );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $Note );

			if ($a4) {
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'I' . $start_row, 'STT' );
				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'J' . $start_row . ':K' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'J' . $start_row, $name_fees );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'L' . $start_row, $price );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'M' . $start_row, $quantily );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'N' . $start_row, $temporary_money );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'O' . $start_row, $Note );
			}

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . $start_row . ':O' . $start_row )
				->applyFromArray ( $style_center );

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$tong_du_kien_thang_nay = $i = 0;

			foreach ( $data_fee ['receivable_student'] as $k => $rs ) {

				if ((date ( "Ym", PsDateTime::psDatetoTime ( $data_fee ['ps_fee_receipt']->getReceiptDate () ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) ))) {
					$i ++;
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'B' . $start_row . ':C' . $start_row );
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'J' . $start_row . ':K' . $start_row );

					if ($rs->getRsReceivableId ()) {
						$title = $rs->getRTitle ();
					} elseif ($rs->getRsServiceId ()) {
						$title = $rs->getSTitle ();
					} elseif ($rs->getRsIsLate () == 1) {
						$title_sevice = $this->object->getContext ()
							->getI18N ()
							->__ ( 'Out late' ) . '(' . date ( "m/Y", strtotime ( $rs->getRsReceivableAt () ) ) . ')';
					}

					// Phi du kien
					if ($rs->getRsServiceId () > 0) {
						$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();
						$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
					} else {
						$rs_amount = $rs->getRsAmount ();
					}

					// Liên 1
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'A' . $start_row, $i );
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'A' . ($start_row) )
						->applyFromArray ( $style_center );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'B' . $start_row, mb_convert_encoding ( $title, "UTF-8", "auto" ) );

					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'D' . $start_row, $rs->getRsUnitPrice () );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'E' . $start_row, $rs->getRsByNumber () );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'F' . $start_row, $rs_amount );
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'F' . ($start_row) )
						->applyFromArray ( $style_right );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'G' . $start_row, $rs->getRsNote () );

					if ($rs->getRsServiceId () > 0) {

						$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();

						$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
						$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
					} else {
						$rs_amount = $rs->getRsAmount ();
						$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
					}

					if ($a4) { // Liên 2
						$this->objPHPExcel->getActiveSheet ()
							->setCellValue ( 'I' . $start_row, $i );
						$this->objPHPExcel->getActiveSheet ()
							->getStyle ( 'I' . ($start_row) )
							->applyFromArray ( $style_center );
						$this->objPHPExcel->getActiveSheet ()
							->setCellValue ( 'J' . $start_row, mb_convert_encoding ( $title, "UTF-8", "auto" ) );

						$this->objPHPExcel->getActiveSheet ()
							->setCellValue ( 'L' . $start_row, $rs->getRsUnitPrice () );
						$this->objPHPExcel->getActiveSheet ()
							->setCellValue ( 'M' . $start_row, $rs->getRsByNumber () );
						$this->objPHPExcel->getActiveSheet ()
							->setCellValue ( 'N' . $start_row, $rs_amount );
						$this->objPHPExcel->getActiveSheet ()
							->getStyle ( 'N' . ($start_row) )
							->applyFromArray ( $style_right );
						$this->objPHPExcel->getActiveSheet ()
							->setCellValue ( 'O' . $start_row, $rs->getRsNote () );
					}

					$start_row = $start_row + 1;

					$this->objPHPExcel->getActiveSheet ()
						->insertNewRowBefore ( $start_row, 1 );
				} else {
					if ($rs->getRsIsLate () == 1 && $rs->getRsAmount () > 0) {
						$total_oldRsLateAmount = $total_oldRsLateAmount + $rs->getRsAmount ();
					} else {
						$total_oldRsAmount = $total_oldRsAmount + $rs->getRsAmount (); // Tong tien khong chứa Về muộn
					}
				}
			}
			// tong du kien thang nay
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':E' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $total_provisional );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $tong_du_kien_thang_nay );

			if ($a4) {
				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'I' . $start_row . ':M' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'I' . $start_row, $total_provisional );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'I' . ($start_row) )
					->applyFromArray ( $style_right );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'N' . $start_row, $tong_du_kien_thang_nay );
			}
			// tien thua thang truoc
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$tien_thua_thang_truoc = $data_fee ['collectedAmount'] - ($total_oldRsAmount + $total_oldRsLateAmount - $data_fee ['balance_last_month_amount']);

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':E' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $price_pre_month );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . ($start_row), $tien_thua_thang_truoc );
			if ($a4) {
				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'I' . $start_row . ':M' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'I' . $start_row, $price_pre_month );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'N' . ($start_row), $tien_thua_thang_truoc );
			}

			// phi nop muon

			if ($data_fee ['psConfigLatePayment'] > 0) {
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . $start_row . ':E' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $late_payment );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, $data_fee ['psConfigLatePayment'] );

				if ($a4) {
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'I' . $start_row . ':M' . $start_row );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'I' . $start_row, $late_payment );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'N' . $start_row, $data_fee ['psConfigLatePayment'] );
				}
			}
			// tong tien phai nop
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
			$tongtienphainop = $tong_du_kien_thang_nay - $tien_thua_thang_truoc + $data_fee ['psConfigLatePayment'];

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':E' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $tongphainop );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $tongtienphainop );

			if ($a4) {
				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'I' . $start_row . ':M' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'I' . $start_row, $tongphainop );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'N' . $start_row, $tongtienphainop );
			}

			if ($data_fee ['ps_fee_receipt']->getPaymentStatus () == PreSchool::ACTIVE) { // Neu trang thai da thanh toan
			                                                                              // phu huynh nop tien
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . $start_row . ':E' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $relative_payment );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, $data_fee ['ps_fee_receipt']->getCollectedAmount () );

				if ($a4) {
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'I' . $start_row . ':M' . $start_row );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'I' . $start_row, $relative_payment );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'N' . $start_row, $data_fee ['ps_fee_receipt']->getCollectedAmount () );
				}

				// Tien du
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . $start_row . ':E' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $tienthua );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, $data_fee ['ps_fee_receipt']->getBalanceAmount () );

				if ($a4) {
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'I' . $start_row . ':M' . $start_row );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'I' . $start_row, $tienthua );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'N' . $start_row, $data_fee ['ps_fee_receipt']->getBalanceAmount () );
				}
			} else {
				// phu huynh nop tien
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . $start_row . ':E' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $relative_payment );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, '' );

				if ($a4) {
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'I' . $start_row . ':M' . $start_row );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'I' . $start_row, $relative_payment );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'N' . $start_row, '' );
				}

				// Tien du
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . $start_row . ':E' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $tienthua );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, '' );

				if ($a4) {
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'I' . $start_row . ':M' . $start_row );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'I' . $start_row, $tienthua );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'N' . $start_row, '' );
				}
			}

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$info_date_export = $this->object->getContext ()
				->getI18N ()
				->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'year' ) . ' ' . date ( "Y" );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'F' . $start_row . ':G' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . ($start_row), $info_date_export );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'F' . ($start_row) )
				->applyFromArray ( $style_center );
			if ($a4) {
				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'N' . $start_row . ':O' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'N' . ($start_row), $info_date_export );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'N' . ($start_row) )
					->applyFromArray ( $style_center );
			}

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . ($start_row), $relative );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_center );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'D' . $start_row . ':F' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . ($start_row), $nguoilap );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'D' . ($start_row) )
				->applyFromArray ( $style_center );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . ($start_row), $cashier );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'G' . ($start_row) )
				->applyFromArray ( $style_center );

			if ($a4) {

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'I' . $start_row . ':K' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'I' . ($start_row), $relative );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'I' . ($start_row) )
					->applyFromArray ( $style_center );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'L' . $start_row . ':N' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'L' . ($start_row), $nguoilap );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'L' . ($start_row) )
					->applyFromArray ( $style_center );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'O' . ($start_row), $cashier );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'O' . ($start_row) )
					->applyFromArray ( $style_center );
			}

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . ($start_row), $kyten );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'D' . $start_row . ':F' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . ($start_row), $kyten );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . ($start_row), $kyten );

			if ($a4) {

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'I' . $start_row . ':K' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'I' . ($start_row), $kyten );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'L' . $start_row . ':N' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'L' . ($start_row), $kyten );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'O' . ($start_row), $kyten );
			}

			$start_row = $start_row + 4;

			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 4 );

			$this->objPHPExcel->getActiveSheet ()
				->setBreak ( 'A' . $start_row, PHPExcel_Worksheet::BREAK_ROW );

			$start_row = $start_row + 1;
		}
	}

	/**
	 * Xuat file xls phiếu thu cả lớp mẫu 2
	 */
	public function setDataExportReceiptByClassTemplate2($all_data_fee, $psClass, $int_receipt_date, $config_choose_charge_showlate, $a4 = false, $class_name = null) {

		$title_info = $psClass->getTitle ();
		$workplace_info = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Address' ) . ": " . $psClass->getAddress () . '-' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'Tel2' ) . ": " . $psClass->getPhone ();
		if ($psClass->getEmail () != '') {
			$workplace_info = $workplace_info . '-' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'Email' ) . ': ' . $psClass->getEmail ();
		}

		if ($psClass->getLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $psClass->getYearData () . '/' . $psClass->getLogo () )) {
			$header_logo = sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $psClass->getYearData () . '/' . $psClass->getLogo ();
		} else {
			$header_logo = '';
		}
		/* */
		if ($class_name) {
			$class_name = $class_name;
			$title_xls = date ( "mY", $int_receipt_date ) . '_' . $class_name;
		} else {
			$title_xls = date ( "mY", $int_receipt_date ) . '_' . $psClass->getClName ();
			$class_name = $psClass->getClName ();
		}

		$title_xls = substr ( $title_xls, 0, 30 );

		$title_notification = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Tuition receipts' ) . ' ' . date ( "m-Y", $int_receipt_date );

		$this->objPHPExcel->getActiveSheet ()
			->setTitle ( $title_xls );

		$start_row = 1;
		
		
		$estimated_fees = $this->object->getContext ()->getI18N ()->__ ( 'Estimated fees' );
		$fee_expected = $this->object->getContext ()->getI18N ()->__ ( 'Fee expected' );
		$songaynghi = $this->object->getContext ()->getI18N ()->__ ( 'So ngay nghi' );
		$excess_money = $this->object->getContext ()->getI18N ()->__ ( 'Excess money' );
		
		$previous = $this->object->getContext ()->getI18N ()->__ ( 'Payment fees for previous month' );
		$name_class = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Class' );
		$lien1 = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Lien 1' );
		$lien2 = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Lien 2' );
		$receipt_no = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Report no' ); // ma phieu thu
		$name = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Student' ); // ho ten
		$student_code = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Student code' );
		$oul_late = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Out late' ); // ve muon
		$service = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total expected' ); // dich vu khac
		$estimated = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Estimated fees this month' ); // du kien thang nay
		$name_fees = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Name fees' ); // ten dich vu
		$price = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Price' ); // Tien
		$quantily = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Quantily expected full' ); // So luong du kien
		$discount_fixed = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Discount fixed' ); // Giam tru co dinh
		$discount = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Discount' ); // Giam tru phan tram
		$temporary_money = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Temporary money' ); // tam tinh
		$Used = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Used' ); // su dung
		$actual_costs = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Actual costs' ); // phi thuc te
		$Note = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Note' );
		$total_provisional = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total provisional' ); // tong tam tinh
		$tongdukien = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total expected' ); // tong du kien
		$dathanhtoan = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Paid' ); // da thanh toan
		$tienduno = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Debt' ); // Tiền dư nợ
		$tongthucte = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total reality' ); // tong thuc te
		$price_pre_month = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Price previous month' ); // thua thang truoc
		$late_payment = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Late payment' ); // Phi nop muon
		$relative_payment = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Relative payment' ); // Phu hunh nop
		$tienthua = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Amount relative' ); // Tien thua
		$tongphainop = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total amount payment' ); // Tong phai nop
		$relative = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Relative' ); // Phu huynh
		$nguoilap = $this->object->getContext ()
		->getI18N ()
		->__ ( 'User create' ); // Nguoi lap
		$cashier = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Cashier' ); // Thu ngan
		$kyten = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Ki ten' ); // ky ten
		$month = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Month' ); // ky ten
		
		$style_center = array (
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ) );
		$style_right = array (
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT ) );
		
		$style_left = array (
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT ) );
		
		
		foreach ( $all_data_fee as $data_fee ) {

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( "A" . ($start_row) . ":B" . ($start_row) );

			if ($header_logo != '') {

				$objDrawing = new PHPExcel_Worksheet_Drawing ();

				// $objDrawing->setName('Logo');

				$objDrawing->setDescription ( 'Logo' );

				$objDrawing->setPath ( $header_logo );

				$objDrawing->setOffsetY ( 3 );
				$objDrawing->setOffsetX ( 3 );
				$objDrawing->setCoordinates ( 'B' . $start_row );
				$objDrawing->setHeight ( 80 );
				$objDrawing->setWidth ( 80 );

				$objDrawing->getShadow ()
					->setVisible ( true );
				$objDrawing->getShadow ()
					->setDirection ( 80 );
				$objDrawing->setWorksheet ( $this->objPHPExcel->getActiveSheet () );
			}

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( "C" . ($start_row) . ":J" . ($start_row) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $title_info );

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( "A" . ($start_row) . ":B" . ($start_row) );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( "C" . ($start_row) . ":J" . ($start_row) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $workplace_info );

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':J' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $title_notification );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_center );

			$start_row = $start_row + 2;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 2 );

			// Ma phieu thu
			// Lien 1
			// $this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A'.$start_row, $lien1 );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $receipt_no );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'G' . $start_row . ':J' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $data_fee ['ps_fee_receipt']->getReceiptNo () );

			// ten hoc sinh
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $name );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $student_code );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'B' . $start_row . ':E' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $data_fee ['student_name'] );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'G' . $start_row . ':J' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $data_fee ['student_code'] );

			// Lop hoc sinh
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $name_class );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $class_name );

			// Khoan thua cua thang truoc

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':H' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_left );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $es_timated );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I' . $start_row, $data_fee ['balance_last_month_amount'] );

			// tieu de : cac khoan thu thang truoc
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':J' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $previous );

			$receivable_at = $data_fee ['ps_fee_receipt']->getReceiptDate (); // Tháng thu phí

			$collectedAmount = $data_fee ['collectedAmount'];

			$tong_cac_thang_cu = 0;
			$tong_du_kien_cac_thang_cu = $tong_du_kien_thang_nay = 0;

			// tieu de phi thang nay
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $month );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $name_fees );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $price );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $quantily );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, $discount_fixed );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $discount );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $temporary_money );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . $start_row, $Used );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I' . $start_row, $actual_costs );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'J' . $start_row, $Note );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . $start_row . ':J' . $start_row )
				->applyFromArray ( $style_center );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . $start_row . ':J' . $start_row )
				->applyFromArray ( array (
					'borders' => array (
							'allborders' => array (
									'style' => PHPExcel_Style_Border::BORDER_THIN,
									'color' => array (
											'rgb' => '000000' ) ) ) ) );

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$rs_current = array ();
			foreach ( $data_fee ['receivable_student'] as $k => $rs ) {

				if ((date ( "Ym", PsDateTime::psDatetoTime ( $data_fee ['ps_fee_receipt']->getReceiptDate () ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) ))) {

					$tong_cac_thang_cu = $tong_cac_thang_cu + $rs->getRsAmount ();

					if ($rs->getRsReceivableId ()) {
						$title = $rs->getRTitle ();
					} elseif ($rs->getRsServiceId ()) {
						$title = $rs->getSTitle ();
					} elseif ($rs->getRsIsLate () == 1) {
						$title_sevice = $this->object->getContext ()
							->getI18N ()
							->__ ( 'Out late' ) . '(' . date ( "m/Y", strtotime ( $rs->getRsReceivableAt () ) ) . ')';
					}

					// Phi du kien
					if ($rs->getRsServiceId () > 0) {
						$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();
						$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
					} else {
						$rs_amount = $rs->getRsAmount ();
					}

					// Liên 1
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'A' . $start_row, date ( 'm-Y', PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) ) );
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'A' . ($start_row) )
						->applyFromArray ( $style_center );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'B' . $start_row, mb_convert_encoding ( $title, "UTF-8", "auto" ) );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'C' . $start_row, $rs->getRsUnitPrice () );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'D' . $start_row, $rs->getRsByNumber () );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'E' . $start_row, $rs->getRsDiscountAmount () );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'F' . $start_row, $rs->getRsDiscount () );
					if ($rs->getRsServiceId () > 0) {
						$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();

						$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
					} else {
						$rs_amount = $rs->getRsAmount ();
					}

					if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceiptDate () ) )) {
						$rs_amount = 0;
					}

					$tong_du_kien_cac_thang_cu = $tong_du_kien_cac_thang_cu + $rs_amount;

					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'G' . $start_row, $rs_amount );

					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'G' . ($start_row) )
						->applyFromArray ( $style_right );

					$spentNumber = $rs->getRsEnableRoll () == 1 ? $rs->getRsByNumber () : $rs->getRsSpentNumber ();

					if ($rs->getRsIsLate () == 1) {
						$spentNumber = ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . $this->object->getContext ()
							->getI18N ()
							->__ ( 'Minute' );
					} else {
						$spentNumber = PreNumber::number_format ( $spentNumber );
					}

					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'H' . $start_row, $spentNumber );

					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'I' . ($start_row) )
						->applyFromArray ( $style_right );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'I' . $start_row, $rs->getRsAmount () );

					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'J' . $start_row, ($rs->getRsIsLate () == 1) ? $this->object->getContext ()
						->getI18N ()
						->__ ( $rs->getRsNote () ) : $rs->getRsNote () );

					$start_row = $start_row + 1;

					$this->objPHPExcel->getActiveSheet ()
						->insertNewRowBefore ( $start_row, 1 );
				} else {
					array_push ( $rs_current, $rs );
				}
			}

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':F' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $tongdukien );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_right );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $tong_du_kien_cac_thang_cu );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . $start_row, $tongthucte );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'H' . ($start_row) )
				->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I' . $start_row, $tong_cac_thang_cu );

			// da thanh toan
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . $start_row, $dathanhtoan );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'H' . ($start_row) )
				->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I' . $start_row, $data_fee ['collectedAmount'] );

			// Du thuc te thang truoc
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$newBalanceAmont = $data_fee ['collectedAmount'] - $tong_cac_thang_cu + $data_fee ['balance_last_month_amount'];

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . $start_row, $tienduno );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'H' . ($start_row) )
				->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I' . $start_row, $newBalanceAmont );

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':J' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_left );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $estimated );

			// tieu de phi thang nay
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $month );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $name_fees );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $price );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $quantily );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, $discount_fixed );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $discount );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $temporary_money );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'H' . $start_row . ':J' . $start_row );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . $start_row, $Note );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . $start_row . ':J' . $start_row )
				->applyFromArray ( $style_center );

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			foreach ( $rs_current as $k => $r_s ) {

				$month_prev = PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, date ( 'm-Y', $month_prev ) );

				if ($r_s->getRsReceivableId ()) {
					$title_sevice = $r_s->getRTitle ();
				} elseif ($r_s->getRsServiceId ()) {
					$title_sevice = $r_s->getSTitle ();
				} elseif ($r_s->getRsIsLate () == 1) {
					$title_sevice = $this->object->getContext ()
						->getI18N ()
						->__ ( 'Out late' ) . '(' . date ( "m/Y", strtotime ( $r_s->getRsReceivableAt () ) ) . ')';
				}

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'B' . $start_row, $title_sevice );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'C' . $start_row, $r_s->getRsUnitPrice () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'D' . $start_row, $r_s->getRsByNumber () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'E' . $start_row, ($r_s->getRsDiscountAmount () > 0) ? $r_s->getRsDiscountAmount () : '' );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, ($r_s->getRsDiscount () > 0) ? $r_s->getRsDiscount () : '' );

				// Phi du kien
				if ($r_s->getRsServiceId () > 0) {

					$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

					$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
				} else {
					$rs_amount = $r_s->getRsAmount ();
					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
				}
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'G' . ($start_row) )
					->applyFromArray ( $style_right );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . $start_row, $rs_amount );

				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
			}

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':F' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $total_provisional );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $tong_du_kien_thang_nay );

			// Thong so cuoi phiếu
			// Tiền thừa của tháng trước
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
			// $tien_thua_thang_truoc = $data_fee ['collectedAmount'] - ($data_fee ['totalAmount'] - $data_fee ['totalAmountReceivableAt']);

			$tien_thua_thang_truoc = $newBalanceAmont;

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':F' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $price_pre_month );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . ($start_row), ( float ) $tien_thua_thang_truoc );
			if ($tien_thua_thang_truoc < 0) {
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'H' . ($start_row), $this->object->getContext ()
					->getI18N ()
					->__ ( 'Old debt' ) );
			}

			if ($data_fee ['psConfigLatePayment'] > 0) { // neu có phí nộp muộn
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . $start_row . ':F' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $late_payment );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'A' . ($start_row) )
					->applyFromArray ( $style_right );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . ($start_row), $data_fee ['psConfigLatePayment'] );
			}

			$tongphainop = $tong_du_kien_thang_nay - $tien_thua_thang_truoc + $data_fee ['psConfigLatePayment'];

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':F' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $tongtienphainop );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . ($start_row), $tongphainop );

			if ($data_fee ['ps_fee_receipt']->getPaymentStatus () == PreSchool::ACTIVE) {
				// phu huynh nop tien
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . $start_row . ':F' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $relative_payment );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . $start_row, $data_fee ['ps_fee_receipt']->getCollectedAmount () );

				// Tien du
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . $start_row . ':F' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $tienthua );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . $start_row, $data_fee ['ps_fee_receipt']->getBalanceAmount () );
			} else {
				// phu huynh nop tien
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . $start_row . ':F' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $relative_payment );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . $start_row, '' );

				// Tien du
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . $start_row . ':F' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $tienthua );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . $start_row, '' );
			}

			$start_row = $start_row + 1;

			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
			$info_date_export = $this->object->getContext ()
				->getI18N ()
				->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'year' ) . ' ' . date ( "Y" );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'H' . $start_row . ':J' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . ($start_row), $info_date_export );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . $start_row . ':J' . $start_row )
				->applyFromArray ( array (
					'borders' => array (
							'allborders' => array (
									'style' => PHPExcel_Style_Border::BORDER_NONE ) ) ) );

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . ($start_row), $relative );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . ($start_row) )
				->applyFromArray ( $style_center );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'D' . $start_row . ':F' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . ($start_row), $nguoilap );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'D' . ($start_row) )
				->applyFromArray ( $style_center );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'G' . $start_row . ':J' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . ($start_row), $cashier );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'G' . ($start_row) )
				->applyFromArray ( $style_center );

			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . ($start_row), $kyten );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'D' . $start_row . ':F' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . ($start_row), $kyten );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'G' . $start_row . ':J' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . ($start_row), $kyten );

			$start_row = $start_row + 4;

			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 4 );

			$this->objPHPExcel->getActiveSheet ()
				->setBreak ( 'A' . $start_row, PHPExcel_Worksheet::BREAK_ROW );

			$start_row = $start_row + 1;
		}
	}

	/**
	 * Xuat file xls phiếu thu cả lớp mẫu 3
	 */
	public function setDataExportReceiptByClassTemplate3($all_data_fee, $psClass, $int_receipt_date, $config_choose_charge_showlate,$receipt_title) {
		
		$title_info = $psClass->getTitle ();
		
		$workplace_info = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Address' ) . ": " . $psClass->getAddress () . '-' . $this->object->getContext ()
		->getI18N ()
		->__ ( 'Tel2' ) . ": " . $psClass->getPhone ();
		if ($psClass->getEmail () != '') {
			$workplace_info = $workplace_info . '-' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'Email' ) . ': ' . $psClass->getEmail ();
		}
		
		if ($psClass->getLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $psClass->getYearData () . '/' . $psClass->getLogo () )) {
			$header_logo = sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $psClass->getYearData () . '/' . $psClass->getLogo ();
		} else {
			$header_logo = '';
		}
		
		$title_xls = date ( "mY", $int_receipt_date ) . '_' . $psClass->getClName ();
		$class_name = $psClass->getClName ();
		
		$title_xls = substr ( $title_xls, 0, 30 );
		$title_notification = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Tuition receipts' ) . ' ' . date ( "m-Y", $int_receipt_date );
		
		$this->objPHPExcel->getActiveSheet ()
		->setTitle ( $title_xls );
		
		$start_row = 1;
		
		$estimated_fees = $this->object->getContext ()->getI18N ()->__ ( 'Estimated fees' );
		$fee_expected = $this->object->getContext ()->getI18N ()->__ ( 'Fee expected' );
		$songaynghi = $this->object->getContext ()->getI18N ()->__ ( 'So ngay nghi' );
		$excess_money = $this->object->getContext ()->getI18N ()->__ ( 'Excess money' );
		$quantily = $this->object->getContext ()->getI18N ()->__ ( 'Quantily' );
		
		$name_class = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Class' );
		$receipt_no = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Receipt no' ); // ma phieu thu
		$report_no = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Fee report no' ); // ma phieu bao
		$name = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Student' ); // ho ten
		$student_code = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Student code' );
		$oul_late = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Out late' ); // ve muon
		$service = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total expected' ); // dich vu khac
		$estimated = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Estimated fees this month' ); // du kien thang nay
		$name_fees = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Name fees' ); // ten dich vu
		$price = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Price' ); // Tien
		
		$temporary_money = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Temporary money' ); // tam tinh
		$Used = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Used' ); // su dung
		$actual_costs = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Actual costs' ); // phi thuc te
		$Note = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Note' );
		$total_provisional = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total provisional' ); // tong tam tinh
		$tongdukien = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total expected' ); // tong du kien
		$dathanhtoan = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Paid' ); // da thanh toan
		$tienduno = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Debt' ); // Tiền dư nợ
		$tongthucte = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total reality' ); // tong thuc te
		$price_pre_month = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Price previous month' ); // thua thang truoc
		$late_payment = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Late payment' ); // Phi nop muon
		$relative_payment = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Relative payment' ); // Phu hunh nop
		$tienthua = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Amount relative' ); // Tien thua
		$tongphainop = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total amount payment' ); // Tong phai nop
		$relative = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Relative' ); // Phu huynh
		$nguoilap = $this->object->getContext ()
		->getI18N ()
		->__ ( 'User create' ); // Nguoi lap
		$cashier = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Cashier' ); // Thu ngan
		$kyten = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Ki ten' ); // ky ten
		$month = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Month' ); // ky ten
		
		$style_center = array (
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ) );
		$style_right = array (
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT ) );
		
		$style_left = array (
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT ) );
		
		foreach ( $all_data_fee as $data_fee ) {
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( "A" . ($start_row) . ":B" . ($start_row) );
			
			if ($header_logo != '') {
				
				$objDrawing = new PHPExcel_Worksheet_Drawing ();
				
				$objDrawing->setDescription ( 'Logo' );
				
				$objDrawing->setPath ( $header_logo );
				
				$objDrawing->setOffsetY ( 3 );
				$objDrawing->setOffsetX ( 3 );
				$objDrawing->setCoordinates ( 'B' . $start_row );
				$objDrawing->setHeight ( 80 );
				$objDrawing->setWidth ( 80 );
				
				$objDrawing->getShadow ()
				->setVisible ( true );
				$objDrawing->getShadow ()
				->setDirection ( 80 );
				$objDrawing->setWorksheet ( $this->objPHPExcel->getActiveSheet () );
			}
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( "C" . ($start_row) . ":I" . ($start_row) );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $title_info );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( "A" . ($start_row) . ":B" . ($start_row) );
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( "C" . ($start_row) . ":H" . ($start_row) );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $workplace_info );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'A' . $start_row . ':H' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $title_notification );
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'A' . ($start_row) )
			->applyFromArray ( $style_center );
			
			$start_row = $start_row + 2;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 2 );
			
			// Ma phieu thu
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $name );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'B' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $data_fee ['student_name'] );
			
			if($receipt_title == 'PT'){
				$receipt_no = $receipt_no;
			}else{
				$receipt_no = $report_no;
			}
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . $start_row, $receipt_no );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'E' . $start_row . ':H' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $data_fee ['report_no'] );
			
			// ten lop
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $name_class );
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'B' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'B' . $start_row, $class_name );
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D' . $start_row, $student_code );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'E' . $start_row . ':H' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'E' . $start_row, $data_fee ['student_code'] );
			
			// tieu de : cac khoan du kien thu
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'A' . $start_row . ':H' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $estimated_fees );
			
			$receivable_at = $data_fee ['ps_fee_receipt']->getReceiptDate (); // Tháng thu phí
			
			$collectedAmount = $data_fee ['collectedAmount'];
			
			$tong_cac_thang_cu = 0;
			$tong_du_kien_cac_thang_cu = $tong_du_kien_thang_nay = 0;
			
			$service_id = $rs_current = array ();
			foreach ( $data_fee ['receivable_student'] as $k => $rs ) {
				
				if ((date ( "Ym", PsDateTime::psDatetoTime ( $data_fee ['ps_fee_receipt']->getReceiptDate () ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) ))) {
					
					$tong_cac_thang_cu = $tong_cac_thang_cu + $rs->getRsAmount ();
					
					$spentNumber = $rs->getRsEnableRoll () == 1 ? $rs->getRsByNumber () : $rs->getRsSpentNumber ();
					
					$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();
					$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
					
					if ($rs->getRsServiceId () > 0) {
						$service_id[$rs->getRsServiceId ()] = array('amount'=>($rs_amount - $rs->getRsAmount ()),'number'=>$spentNumber) ;
					}
					if($rs->getRsIsLate() == 1){
						$spentNumber = ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . __ ( 'Minute' );
						$service_id['is_late'] = array('amount'=>($rs->getRsAmount ()),'number'=>$spentNumber) ;
					}
					
				} else {
					array_push ( $rs_current, $rs );
				}
			}
			
			$newBalanceAmont = $data_fee ['collectedAmount'] - $tong_cac_thang_cu + $data_fee ['balance_last_month_amount'];
			
			// tieu de phi thang nay
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, 'STT' );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'A' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $name_fees );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'B' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'C' . $start_row, $price );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'C' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . $start_row, $quantily );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'D' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $fee_expected );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'E' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . $start_row, 'SL SD' );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'F' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, $excess_money );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'G' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, $Note );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'H' . $start_row ) ->applyFromArray ( $style_center );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			
			foreach ( $rs_current as $k => $r_s ) {
				
				$number_use = $tien_thua = '';
				if ((date ( "Ym", PsDateTime::psDatetoTime ( $data_fee ['ps_fee_receipt']->getReceiptDate () ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) ))) {
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $k+1 );
					
					if ($r_s->getRsReceivableId ()) {
						$title_sevice = $r_s->getRTitle ();
					} elseif ($r_s->getRsServiceId ()) {
						$title_sevice = $r_s->getSTitle ();
					} elseif ($r_s->getRsIsLate () == 1) {
						$title_sevice = $this->object->getContext ()
						->getI18N ()
						->__ ( 'Out late' ) . '(' . date ( "m/Y", strtotime ( $r_s->getRsReceivableAt () ) ) . ')';
					}
					// Phi du kien
					if ($r_s->getRsServiceId () > 0) {
						
						$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();
						$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
						$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
						
						if(isset($service_id[$r_s->getRsServiceId ()]['amount'])){
							$tien_thua = $service_id[$r_s->getRsServiceId ()]['amount'];
							$number_use = $service_id[$r_s->getRsServiceId ()]['number'];
						}
						
					} else {
						$rs_amount = $r_s->getRsAmount ();
						$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
					}
					
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'B' . $start_row ) ->applyFromArray ( $style_left );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'C' . $start_row, $r_s->getRsUnitPrice () );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'C' . $start_row ) ->applyFromArray ( $style_right );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . $start_row, $r_s->getRsByNumber () );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'D' . $start_row ) ->applyFromArray ( $style_center );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $rs_amount );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'E' . $start_row ) ->applyFromArray ( $style_right );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . $start_row, $number_use );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'F' . $start_row ) ->applyFromArray ( $style_center );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, $tien_thua );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'G' . $start_row ) ->applyFromArray ( $style_center );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, $r_s->getRsNote () );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'H' . $start_row ) ->applyFromArray ( $style_right );
					
					$start_row = $start_row + 1;
					$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				}
			}
			
			if(isset($service_id['is_late'])){
				
				$start_row = $start_row + 1;
				$title_sevice = $this->object->getContext ()->getI18N ()->__ ( 'Out late' );
				$k = $k+1;
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $k );
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . $start_row, $service_id['is_late']['number'] );
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, (0 - $service_id['is_late']['amount']) );
				
			}
			
			if($data_fee ['balance_last_month_amount'] != 0){
				
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				$title_sevice = $this->object->getContext ()->getI18N ()->__ ( 'Balance last month amount' );
				$k = $k+1;
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $k );
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, $data_fee ['balance_last_month_amount'] );
				
			}
			
			/*Thong so cuoi phiếu */
			// Tong tien du kien trong thang
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $total_provisional );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'A' . ($start_row) ) ->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $tong_du_kien_thang_nay );
			
			// Tiền thừa của tháng trước
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			
			$tien_thua_thang_truoc = $newBalanceAmont;
			
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $price_pre_month );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'A' . ($start_row) ) ->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . ($start_row), ( float ) $tien_thua_thang_truoc );
			if ($tien_thua_thang_truoc < 0) {
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . ($start_row), $this->object->getContext ()
						->getI18N ()
						->__ ( 'Old debt' ) );
			}
			
			if ($data_fee ['psConfigLatePayment'] > 0) { // neu có phí nộp muộn
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $late_payment );
				$this->objPHPExcel->getActiveSheet () ->getStyle ( 'A' . ($start_row) ) ->applyFromArray ( $style_right );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . ($start_row), $data_fee ['psConfigLatePayment'] );
			}
			
			$tongtienphainop = $tong_du_kien_thang_nay - $tien_thua_thang_truoc + $data_fee ['psConfigLatePayment'];
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $tongphainop );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'A' . ($start_row) ) ->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . ($start_row), $tongtienphainop );
			
			if ($data_fee ['ps_fee_receipt']->getPaymentStatus () == PreSchool::ACTIVE) {
				// phu huynh nop tien
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $relative_payment );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $data_fee ['ps_fee_receipt']->getCollectedAmount () );
				
				// Tien du
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $tienthua );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $data_fee ['ps_fee_receipt']->getBalanceAmount () );
				
			} else {
				// phu huynh nop tien
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $relative_payment );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, '' );
				
				// Tien du
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $tienthua );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, '' );
			}
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			$info_date_export = $this->object->getContext ()
			->getI18N () ->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' .
			$this->object->getContext () ->getI18N () ->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' .
			$this->object->getContext () ->getI18N () ->__ ( 'year' ) . ' ' . date ( "Y" );
			
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'F' . $start_row . ':H' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . ($start_row), $info_date_export );
			
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'A' . $start_row . ':H' . $start_row )
			->applyFromArray ( array (
					'borders' => array (
							'allborders' => array (
									'style' => PHPExcel_Style_Border::BORDER_NONE ) ) ) );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'A' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . ($start_row), $relative );
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'A' . ($start_row) )
			->applyFromArray ( $style_center );
			
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'D' . $start_row . ':E' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . ($start_row), $nguoilap );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'D' . ($start_row) ) ->applyFromArray ( $style_center );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'F' . $start_row . ':H' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F' . ($start_row), $cashier );
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'F' . ($start_row) )
			->applyFromArray ( $style_center );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'A' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . ($start_row), $kyten );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'D' . $start_row . ':E' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D' . ($start_row), $kyten );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'F' . $start_row . ':H' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F' . ($start_row), $kyten );
			
			$start_row = $start_row + 4;
			
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 4 );
			
			$this->objPHPExcel->getActiveSheet ()
			->setBreak ( 'A' . $start_row, PHPExcel_Worksheet::BREAK_ROW );
			
			$start_row = $start_row + 1;
		}
	}

	/**
	 * Xuat file xls phiếu thu cả lớp mẫu 4
	 */
	public function setDataExportReceiptByClassTemplate4($all_data_fee, $psClass, $int_receipt_date, $config_choose_charge_showlate,$receipt_title) {
		
		$title_info = $psClass->getTitle ();
		
		$workplace_info = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Address' ) . ": " . $psClass->getAddress () . '-' . $this->object->getContext ()
		->getI18N ()
		->__ ( 'Tel2' ) . ": " . $psClass->getPhone ();
		if ($psClass->getEmail () != '') {
			$workplace_info = $workplace_info . '-' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'Email' ) . ': ' . $psClass->getEmail ();
		}
		
		if ($psClass->getLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $psClass->getYearData () . '/' . $psClass->getLogo () )) {
			$header_logo = sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $psClass->getYearData () . '/' . $psClass->getLogo ();
		} else {
			$header_logo = '';
		}
		
		$title_xls = date ( "mY", $int_receipt_date ) . '_' . $psClass->getClName ();
		$class_name = $psClass->getClName ();
		
		$title_xls = substr ( $title_xls, 0, 30 );
		$title_notification = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Tuition receipts' ) . ' ' . date ( "m-Y", $int_receipt_date );
		
		$this->objPHPExcel->getActiveSheet ()
		->setTitle ( $title_xls );
		
		$start_row = 1;
		
		$estimated_fees = $this->object->getContext ()->getI18N ()->__ ( 'Estimated fees' );
		$fee_expected = $this->object->getContext ()->getI18N ()->__ ( 'Fee expected' );
		$songaynghi = $this->object->getContext ()->getI18N ()->__ ( 'So ngay nghi' );
		$excess_money = $this->object->getContext ()->getI18N ()->__ ( 'Excess money' );
		$quantily = $this->object->getContext ()->getI18N ()->__ ( 'Quantily' );
		
		$name_class = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Class' );
		$receipt_no = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Receipt no' ); // ma phieu thu
		$report_no = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Fee report no' ); // ma phieu bao
		$name = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Student' ); // ho ten
		$student_code = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Student code' );
		$oul_late = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Out late' ); // ve muon
		$service = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total expected' ); // dich vu khac
		$estimated = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Estimated fees this month' ); // du kien thang nay
		$name_fees = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Name fees' ); // ten dich vu
		$price = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Price' ); // Tien
		
		$temporary_money = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Temporary money' ); // tam tinh
		$Used = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Used' ); // su dung
		$actual_costs = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Actual costs' ); // phi thuc te
		$Note = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Note' );
		$total_provisional = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total provisional' ); // tong tam tinh
		$tongdukien = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total expected' ); // tong du kien
		$dathanhtoan = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Paid' ); // da thanh toan
		$tienduno = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Debt' ); // Tiền dư nợ
		$tongthucte = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total reality' ); // tong thuc te
		$price_pre_month = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Price previous month' ); // thua thang truoc
		$late_payment = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Late payment' ); // Phi nop muon
		$relative_payment = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Relative payment' ); // Phu hunh nop
		$tienthua = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Amount relative' ); // Tien thua
		$tongphainop = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Total amount payment' ); // Tong phai nop
		$relative = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Relative' ); // Phu huynh
		$nguoilap = $this->object->getContext ()
		->getI18N ()
		->__ ( 'User create' ); // Nguoi lap
		$cashier = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Cashier' ); // Thu ngan
		$kyten = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Ki ten' ); // ky ten
		$month = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Month' ); // ky ten
		
		$style_center = array (
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ) );
		$style_right = array (
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT ) );
		
		$style_left = array (
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT ) );
		
		foreach ( $all_data_fee as $data_fee ) {
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( "A" . ($start_row) . ":B" . ($start_row) );
			
			if ($header_logo != '') {
				
				$objDrawing = new PHPExcel_Worksheet_Drawing ();
				
				$objDrawing->setDescription ( 'Logo' );
				
				$objDrawing->setPath ( $header_logo );
				
				$objDrawing->setOffsetY ( 3 );
				$objDrawing->setOffsetX ( 3 );
				$objDrawing->setCoordinates ( 'B' . $start_row );
				$objDrawing->setHeight ( 80 );
				$objDrawing->setWidth ( 80 );
				
				$objDrawing->getShadow ()
				->setVisible ( true );
				$objDrawing->getShadow ()
				->setDirection ( 80 );
				$objDrawing->setWorksheet ( $this->objPHPExcel->getActiveSheet () );
			}
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( "C" . ($start_row) . ":I" . ($start_row) );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $title_info );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( "A" . ($start_row) . ":B" . ($start_row) );
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( "C" . ($start_row) . ":I" . ($start_row) );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $workplace_info );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'A' . $start_row . ':I' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $title_notification );
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'A' . ($start_row) )
			->applyFromArray ( $style_center );
			
			$start_row = $start_row + 2;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 2 );
			
			// Ma phieu thu
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $name );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'B' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $data_fee ['student_name'] );
			
			if($receipt_title == 'PT'){
				$receipt_no = $receipt_no;
			}else{
				$receipt_no = $report_no;
			}
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . $start_row, $receipt_no );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'E' . $start_row . ':I' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $data_fee ['report_no'] );
			
			// ten lop
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $name_class );
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'B' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'B' . $start_row, $class_name );
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D' . $start_row, $student_code );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'E' . $start_row . ':I' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'E' . $start_row, $data_fee ['student_code'] );
			
			// tieu de : cac khoan du kien thu
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'A' . $start_row . ':I' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $estimated_fees );
			
			$receivable_at = $data_fee ['ps_fee_receipt']->getReceiptDate (); // Tháng thu phí
			
			$collectedAmount = $data_fee ['collectedAmount'];
			
			$tong_cac_thang_cu = 0;
			$tong_du_kien_cac_thang_cu = $tong_du_kien_thang_nay = 0;
			
			$service_id = $rs_current = array ();
			foreach ( $data_fee ['receivable_student'] as $k => $rs ) {
				
				if ((date ( "Ym", PsDateTime::psDatetoTime ( $data_fee ['ps_fee_receipt']->getReceiptDate () ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) ))) {
					
					$tong_cac_thang_cu = $tong_cac_thang_cu + $rs->getRsAmount ();
					
					$spentNumber = $rs->getRsEnableRoll () == 1 ? $rs->getRsByNumber () : $rs->getRsSpentNumber ();
					/*
					if ($rs->getIsTypeFee () == 1) {
						$spentNumber = $spentNumber;
					}else{
						$spentNumber = '';
					}
					*/
					$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();
					$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
					
					//if ($rs->getRsEnableRoll() == 0) { // Neu loai dich vu khong co dinh
						if ($rs->getRsServiceId () > 0) {
							$service_id[$rs->getRsServiceId ()] = array('amount'=>($rs_amount - $rs->getRsAmount ()),'number'=>$spentNumber) ;
						}
					//}
					if($rs->getRsIsLate() == 1){
						$spentNumber = ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . __ ( 'Minute' );
						$service_id['is_late'] = array('amount'=>($rs->getRsAmount ()),'number'=>$spentNumber) ;
					}
					
				} else {
					array_push ( $rs_current, $rs );
				}
			}
			
			$newBalanceAmont = $data_fee ['collectedAmount'] - $tong_cac_thang_cu + $data_fee ['balance_last_month_amount'];
			
			// tieu de phi thang nay
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, 'STT' );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'A' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $name_fees );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'B' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'C' . $start_row, $price );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'C' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . $start_row, $quantily );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'D' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $fee_expected );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'E' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . $start_row, 'SL SD' );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'F' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, $songaynghi );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'G' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, $excess_money );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'G' . $start_row ) ->applyFromArray ( $style_center );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'I' . $start_row, $Note );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'I' . $start_row ) ->applyFromArray ( $style_center );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			
			foreach ( $rs_current as $k => $r_s ) {
				
				$number_use = $tien_thua = '';
				if ((date ( "Ym", PsDateTime::psDatetoTime ( $data_fee ['ps_fee_receipt']->getReceiptDate () ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) ))) {
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $k+1 );
					
					if ($r_s->getRsReceivableId ()) {
						$title_sevice = $r_s->getRTitle ();
					} elseif ($r_s->getRsServiceId ()) {
						$title_sevice = $r_s->getSTitle ();
					} elseif ($r_s->getRsIsLate () == 1) {
						$title_sevice = $this->object->getContext ()
						->getI18N ()
						->__ ( 'Out late' ) . '(' . date ( "m/Y", strtotime ( $r_s->getRsReceivableAt () ) ) . ')';
					}
					// Phi du kien
					if ($r_s->getRsServiceId () > 0) {
						
						$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();
						$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
						$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
						
						if(isset($service_id[$r_s->getRsServiceId ()]['amount'])){
							$tien_thua = $service_id[$r_s->getRsServiceId ()]['amount'];
							if($r_s->getIsTypeFee() == 1){
								$number_not_use = $service_id[$r_s->getRsServiceId ()]['number'];
							}else{
								$number_use = $service_id[$r_s->getRsServiceId ()]['number'];
							}
						}
						
					} else {
						$rs_amount = $r_s->getRsAmount ();
						$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
					}
					
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'B' . $start_row ) ->applyFromArray ( $style_left );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'C' . $start_row, $r_s->getRsUnitPrice () );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'C' . $start_row ) ->applyFromArray ( $style_right );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . $start_row, $r_s->getRsByNumber () );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'D' . $start_row ) ->applyFromArray ( $style_center );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $rs_amount );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'E' . $start_row ) ->applyFromArray ( $style_right );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . $start_row, $number_use );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'F' . $start_row ) ->applyFromArray ( $style_center );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, $number_not_use );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'G' . $start_row ) ->applyFromArray ( $style_center );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, $tien_thua );
					$this->objPHPExcel->getActiveSheet () ->getStyle ( 'H' . $start_row ) ->applyFromArray ( $style_right );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'I' . $start_row, $r_s->getRsNote () );
					
					$start_row = $start_row + 1;
					$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				}
			}
			
			if(isset($service_id['is_late'])){
				
				$start_row = $start_row + 1;
				$title_sevice = $this->object->getContext ()->getI18N ()->__ ( 'Out late' );
				$k = $k+1;
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $k );
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . $start_row, $service_id['is_late']['number'] );
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, (0 - $service_id['is_late']['amount']) );
				
			}
			
			if($data_fee ['balance_last_month_amount'] != 0){
				
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				$title_sevice = $this->object->getContext ()->getI18N ()->__ ( 'Balance last month amount' );
				$k = $k+1;
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $k );
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, $data_fee ['balance_last_month_amount'] );
				
			}
			
			/*Thong so cuoi phiếu */
			// Tong tien du kien trong thang
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $total_provisional );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'A' . ($start_row) ) ->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $tong_du_kien_thang_nay );
			
			// Tiền thừa của tháng trước
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			
			$tien_thua_thang_truoc = $newBalanceAmont;
			
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $price_pre_month );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'A' . ($start_row) ) ->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . ($start_row), ( float ) $tien_thua_thang_truoc );
			if ($tien_thua_thang_truoc < 0) {
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . ($start_row), $this->object->getContext ()
						->getI18N ()
						->__ ( 'Old debt' ) );
			}
			
			if ($data_fee ['psConfigLatePayment'] > 0) { // neu có phí nộp muộn
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $late_payment );
				$this->objPHPExcel->getActiveSheet () ->getStyle ( 'A' . ($start_row) ) ->applyFromArray ( $style_right );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . ($start_row), $data_fee ['psConfigLatePayment'] );
			}
			
			$tongtienphainop = $tong_du_kien_thang_nay - $tien_thua_thang_truoc + $data_fee ['psConfigLatePayment'];
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $tongphainop );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'A' . ($start_row) ) ->applyFromArray ( $style_right );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . ($start_row), $tongtienphainop );
			
			if ($data_fee ['ps_fee_receipt']->getPaymentStatus () == PreSchool::ACTIVE) {
				// phu huynh nop tien
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $relative_payment );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $data_fee ['ps_fee_receipt']->getCollectedAmount () );
				
				// Tien du
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $tienthua );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $data_fee ['ps_fee_receipt']->getBalanceAmount () );
			
			} else {
				// phu huynh nop tien
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $relative_payment );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, '' );
				
				// Tien du
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $start_row . ':D' . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $tienthua );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, '' );
			}
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			$info_date_export = $this->object->getContext () 
					->getI18N () ->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' . 
			$this->object->getContext () ->getI18N () ->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' . 
			$this->object->getContext () ->getI18N () ->__ ( 'year' ) . ' ' . date ( "Y" );
			
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'G' . $start_row . ':I' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . ($start_row), $info_date_export );
			
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'A' . $start_row . ':I' . $start_row )
			->applyFromArray ( array (
					'borders' => array (
							'allborders' => array (
									'style' => PHPExcel_Style_Border::BORDER_NONE ) ) ) );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'A' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . ($start_row), $relative );
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'A' . ($start_row) )
			->applyFromArray ( $style_center );
			
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'D' . $start_row . ':F' . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . ($start_row), $nguoilap );
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'D' . ($start_row) ) ->applyFromArray ( $style_center );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'G' . $start_row . ':I' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . ($start_row), $cashier );
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'G' . ($start_row) )
			->applyFromArray ( $style_center );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'A' . $start_row . ':C' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . ($start_row), $kyten );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'D' . $start_row . ':F' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D' . ($start_row), $kyten );
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'G' . $start_row . ':I' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . ($start_row), $kyten );
			
			$start_row = $start_row + 4;
			
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 4 );
			
			$this->objPHPExcel->getActiveSheet ()
			->setBreak ( 'A' . $start_row, PHPExcel_Worksheet::BREAK_ROW );
			
			$start_row = $start_row + 1;
		}
	}
	
	
	/**
	 * Xuất phiếu thu của học sinh theo biểu mẫu 2
	 */
	 public function setDataExportReceiptTemplate2New($data, $ps_receipt, $config_choose_charge_showlate, $a4 = false) {

		$not_used = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Not used' );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G5', $ps_receipt->getReceiptNo () );

		// Du thang truoc
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'I8', $data ['balance_last_month_amount'] );

		$receivable_at = $data ['ps_fee_receipt']->getReceiptDate (); // Tháng thu phí

		$collectedAmount = $data ['collectedAmount']; // so tien da nop

		$tong_cac_thang_cu = 0;
		$tong_du_kien_cac_thang_cu = 0;

		$start_row = 11;
		$index = 0;

		$rs_current = array ();

		$index_row_month_pre_remove_1 = $index_row_month_pre_remove_2 = 0;

		foreach ( $data ['receivable_student'] as $r_s ) {

			if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) )) {
				$index ++;

				$tong_cac_thang_cu = $tong_cac_thang_cu + $r_s->getRsAmount ();

				$month_prev = PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, date ( 'm-Y', $month_prev ) );

				if ($r_s->getRsReceivableId ()) {
					$title_sevice = $r_s->getRTitle ();
				} elseif ($r_s->getRsServiceId ()) {
					$title_sevice = $r_s->getSTitle ();
				} elseif ($r_s->getRsIsLate () == 1) {
					$title_sevice = $this->object->getContext ()
						->getI18N ()
						->__ ( 'Out late' ) . '(' . date ( "m/Y", strtotime ( $r_s->getRsReceivableAt () ) ) . ')';
				}

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'B' . $start_row, $title_sevice );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'C' . $start_row, $r_s->getRsUnitPrice () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'D' . $start_row, $r_s->getRsByNumber () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'E' . $start_row, ($r_s->getRsDiscountAmount () > 0) ? $r_s->getRsDiscountAmount () : '' );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, ($r_s->getRsDiscount () > 0) ? $r_s->getRsDiscount () : '' );

				if ($r_s->getRsServiceId () > 0) {
					$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

					$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
				} else {
					$rs_amount = $r_s->getRsAmount ();
				}

				if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceiptDate () ) )) {
					$rs_amount = 0;
				}

				$tong_du_kien_cac_thang_cu = $tong_du_kien_cac_thang_cu + $rs_amount;

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . $start_row, $rs_amount );

				$spentNumber = $r_s->getRsEnableRoll () == 1 ? $r_s->getRsByNumber () : $r_s->getRsSpentNumber ();

				if ($r_s->getRsIsLate () == 1) {
					$spentNumber = ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . __ ( 'Minute' );
				} else {
					$spentNumber = PreNumber::number_format ( $spentNumber );
				}

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'H' . $start_row, $spentNumber );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'I' . $start_row, $r_s->getRsAmount () );
				if ($r_s->getIsTypeFee () == 1) {
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'J' . $start_row, $not_used );
				} else {
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'J' . $start_row, ($r_s->getRsIsLate () == 1) ? $this->object->getContext ()
						->getI18N ()
						->__ ( $r_s->getRsNote () ) : $r_s->getRsNote () );
				}
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
			} else {
				array_push ( $rs_current, $r_s );
			}
		}

		$index_row_month_pre_remove_1 = $start_row;

		$start_row = $start_row + 1;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . $start_row, $tong_du_kien_cac_thang_cu );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'I' . $start_row, $tong_cac_thang_cu );

		$start_row = $start_row + 1;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'I' . $start_row, $collectedAmount );

		// tien thua thuc te thang truoc
		$newBalanceAmont = $collectedAmount - $tong_cac_thang_cu + $data ['balance_last_month_amount'];

		$start_row = $start_row + 1;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'I' . $start_row, $newBalanceAmont );

		$start_row = $start_row + 3;

		$tong_du_kien_thang_nay = 0;

		foreach ( $rs_current as $r_s ) {

			$month_prev = PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, date ( 'm-Y', $month_prev ) );

			if ($r_s->getRsReceivableId ()) {
				$title_sevice = $r_s->getRTitle ();
			} elseif ($r_s->getRsServiceId ()) {
				$title_sevice = $r_s->getSTitle ();
			} elseif ($r_s->getRsIsLate () == 1) {
				$title_sevice = __ ( 'Out late' ) . '(' . format_date ( $r_s->getRsReceivableAt (), "MM/yyyy" ) . ')';
			}

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $title_sevice );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $r_s->getRsUnitPrice () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $r_s->getRsByNumber () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, ($r_s->getRsDiscountAmount () > 0) ? $r_s->getRsDiscountAmount () : '' );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, ($r_s->getRsDiscount () > 0) ? $r_s->getRsDiscount () : '' );

			// Phi du kien
			if ($r_s->getRsServiceId () > 0) {

				$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

				$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
				$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
			} else {
				$rs_amount = $r_s->getRsAmount ();
				$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
			}

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $rs_amount );
				
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'H' . $start_row, $r_s->getRsNote () );
				
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
		}

		$index_row_month_pre_remove_2 = $start_row;

		$start_row = $start_row + 1;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . $start_row, $tong_du_kien_thang_nay );

		// Thong so cuoi phiếu
		// Tiền thừa của tháng trước
		// $tien_thua_thang_truoc = $data ['collectedAmount'] - ($data ['totalAmount'] - $data ['totalAmountReceivableAt']);

		$tien_thua_thang_truoc = $newBalanceAmont;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . ($start_row + 1), ( float ) $tien_thua_thang_truoc );

		if ($data ['psConfigLatePayment'] > 0) {
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . ($start_row + 2), $data ['psConfigLatePayment'] );
		} else {
			$index_row_month_pre_remove_3 = $start_row + 2;
		}

		$tongphainop = $tong_du_kien_thang_nay - $tien_thua_thang_truoc + $data ['psConfigLatePayment'];

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . ($start_row + 3), $tongphainop );

		if ($tien_thua_thang_truoc < 0)
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . ($start_row + 1), $this->object->getContext ()
				->getI18N ()
				->__ ( 'Old debt' ) );

		if ($ps_receipt->getPaymentStatus () == PreSchool::ACTIVE) {
			// Phu huynh nop
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . ($start_row + 4), ( float ) $ps_receipt->getCollectedAmount () );

			// Du cua phieu thu thang nay
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . ($start_row + 5), ( float ) $ps_receipt->getBalanceAmount () );
		}

		$info_date_export = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'year' ) . ' ' . date ( "Y" );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . ($start_row + 6), $info_date_export );

		// Delete Row
		if ($data ['psConfigLatePayment'] <= 0) {
			$this->objPHPExcel->getActiveSheet ()
				->removeRow ( $index_row_month_pre_remove_3, 1 );
		}

		$this->objPHPExcel->getActiveSheet ()
			->removeRow ( $index_row_month_pre_remove_2, 1 );
		$this->objPHPExcel->getActiveSheet ()
			->removeRow ( $index_row_month_pre_remove_1, 1 );
	}
	public function setDataExportReceiptTemplate2($data, $ps_receipt, $config_choose_charge_showlate, $a4 = false) {
	
		$not_used = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Not used' );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G5', $ps_receipt->getReceiptNo () );

		// Du thang truoc
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'I8', $data ['balance_last_month_amount'] );

		$receivable_at = $data ['ps_fee_receipt']->getReceiptDate (); // Tháng thu phí

		$collectedAmount = $data ['collectedAmount']; // so tien da nop

		$tong_cac_thang_cu = 0;
		$tong_du_kien_cac_thang_cu = 0;

		$start_row = 11;
		$index = 0;
		
		$hoantra = 0;

		$rs_current = array ();

		$index_row_month_pre_remove_1 = $index_row_month_pre_remove_2 = 0;

		foreach ( $data ['receivable_student'] as $r_s ) {
			$receiptDate  = $r_s->getRsReceiptDate();
			// if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) )) {
			if (date ( "Ym", strtotime ( $receivable_at ) ) != date ( "Ym", strtotime ( $receiptDate ) )){
				$index ++;

				$tong_cac_thang_cu = $tong_cac_thang_cu + $r_s->getRsAmount ();

				$month_prev = PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () );

				if ($r_s->getRsServiceId () > 0) {
					$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

					$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
				} else {
					$rs_amount = $r_s->getRsAmount ();
				}

				$spentNumber = $r_s->getRsEnableRoll () == 1 ? $r_s->getRsByNumber () : $r_s->getRsSpentNumber ();
				$dutien = 0;
				if($r_s->getRsEnableRoll () == 0){
					$dutien = ($r_s->getRsSpentNumber() - $r_s->getRsByNumber())*$r_s->getRsUnitPrice ();
				}
				
				if ($r_s->getRsIsLate () == 1) {
					$sl_sd = ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . __ ( 'Minute' );
					$array_hoantra_phatsinh[-1]['tien'] = $r_s->getRsAmount ();
					$array_hoantra_phatsinh[-1]['sl'] = $sl_sd;
					$array_hoantra_phatsinh[-1]['tieude'] = $this->object->getContext ()->getI18N()->__( 'Out late' ) .'('. format_date ( $receiptDate, "MM/yyyy" ) . ')';
					$array_hoantra_phatsinh[-1]['ghichu'] = $this->object->getContext ()->getI18N()->__($r_s->getRsNote());
					$dutien = $r_s->getRsAmount ();
				} else {
					
					$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['tien'] = $dutien;
					$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['sl'] = $sl_sd;
					$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['tieude'] = $r_s->getRsServiceId() ? $r_s->getSTitle() : $r_s->getRTitle();
					$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['ghichu'] = $this->object->getContext ()->getI18N()->__($r_s->getRsNote());
					
					//echo $r_s->getSTitle()."___".$dutien.'<br>';
					
					//$array_hoantra_phatsinh[$r_s->getRsServiceId ()] = $dutien;
					//$array_hoantra_phatsinh[$r_s->getRsServiceId ()] = $r_s->getRsAmount () - $rs_amount;
				}
				$hoantra = $hoantra + $dutien;
				
			} else {
				
				array_push ( $rs_current, $r_s );
				
			}
		}

		// $index_row_month_pre_remove_1 = $start_row;

		// $start_row = $start_row + 1;

		// $this->objPHPExcel->getActiveSheet ()
			// ->setCellValue ( 'G' . $start_row, $tong_du_kien_cac_thang_cu );

		// $this->objPHPExcel->getActiveSheet ()
			// ->setCellValue ( 'I' . $start_row, $tong_cac_thang_cu );

		// $start_row = $start_row + 1;

		// $this->objPHPExcel->getActiveSheet ()
			// ->setCellValue ( 'I' . $start_row, $collectedAmount );

		// // tien thua thuc te thang truoc
		// $newBalanceAmont = $collectedAmount - $tong_cac_thang_cu + $data ['balance_last_month_amount'];

		// $start_row = $start_row + 1;

		// $this->objPHPExcel->getActiveSheet ()
			// ->setCellValue ( 'I' . $start_row, $newBalanceAmont );

		// $start_row = $start_row + 3;

		$tong_du_kien_thang_nay = 0;
		
		
		
		foreach ( $rs_current as $r_s ) {

			$month_prev = PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () );
			
			$receiptDate  = $r_s->getRsReceivableAt ();
			
			if (date ( "Ym", strtotime ( $receivable_at ) ) == date ( "Ym", strtotime ( $receiptDate ) )){
			
				//tháng
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, date ( 'm-Y', $month_prev ) );

				if ($r_s->getRsReceivableId ()) {
					$title_sevice = $r_s->getRTitle ();
				} elseif ($r_s->getRsServiceId ()) {
					$title_sevice = $r_s->getSTitle ();
				} elseif ($r_s->getRsIsLate () == 1) {
					$title_sevice = $this->object->getContext ()->getI18N ()->__ ( 'Out late' ) . '(' . format_date ( $r_s->getRsReceivableAt (), "MM/yyyy" ) . ')';
				}
				//tên phí
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'B' . $start_row, $title_sevice );
				//giá
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'C' . $start_row, $r_s->getRsUnitPrice () );
				//số lượng
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'D' . $start_row, $r_s->getRsByNumber () );
				//Giảm trừ
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'E' . $start_row, ($r_s->getRsDiscountAmount () > 0) ? $r_s->getRsDiscountAmount () : $r_s->getRsDiscount () );

				// $this->objPHPExcel->getActiveSheet ()
					// ->setCellValue ( 'F' . $start_row, ($r_s->getRsDiscount () > 0) ? $r_s->getRsDiscount () : '' );
					
				//Tạm tính
				if ($r_s->getRsServiceId () > 0) {

					$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

					$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
				} else {
					$rs_amount = $r_s->getRsAmount ();
					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
				}
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, PreNumber::number_format($rs_amount) );
					
				//Hoàn trả / phát sinh
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, isset($array_hoantra_phatsinh[$r_s->getRsServiceId()]) ? PreNumber::number_format($array_hoantra_phatsinh[$r_s->getRsServiceId()]['tien']) : '');
				
				//Ghi chú
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I' . $start_row, $r_s->getRsNote () );
					
				unset($array_hoantra_phatsinh[$r_s->getRsServiceId()]);
				
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
			}
		}

		foreach($array_hoantra_phatsinh as $khoanPhatSinh){
			
			if($khoanPhatSinh['tien'] !=0){
			
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'B' . $start_row, $khoanPhatSinh['tieude']);
				//số lượng
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'D' . $start_row, $khoanPhatSinh['sl']);
				//hoàn trả / phát sinh
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . $start_row, PreNumber::number_format($khoanPhatSinh['tien']));
				//Ghi chú
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'I' . $start_row, $khoanPhatSinh['ghichu']);
					
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
			
			}
		}
		

		$index_row_month_pre_remove_2 = $start_row;

		$start_row = $start_row + 1;
		
		//tổng tạm tính
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F' . $start_row, PreNumber::number_format($tong_du_kien_thang_nay) );
		
		//Số nợ kỳ trước
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F' . ($start_row + 1),  PreNumber::number_format($data['balance_last_month_amount']));
		
		//Hoàn trả phát sinh trì trước
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F' . ($start_row + 2),  PreNumber::number_format($hoantra));
		
		//Dư thực tế tháng trước
		$thuctethangtruoc = $hoantra + $data['balance_last_month_amount'];
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'I' . ($start_row + 2), PreNumber::number_format($thuctethangtruoc));
		
		//Tổng thu kì này
		$phaithukynay = $tong_du_kien_thang_nay + $data['balance_last_month_amount'] + $hoantra;
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F' . ($start_row + 3), PreNumber::number_format($phaithukynay));
			
			
		// Thong so cuoi phiếu
		// Tiền thừa của tháng trước
		// $tien_thua_thang_truoc = $data ['collectedAmount'] - ($data ['totalAmount'] - $data ['totalAmountReceivableAt']);
		// $tien_thua_thang_truoc = $newBalanceAmont;
		// $this->objPHPExcel->getActiveSheet ()
			// ->setCellValue ( 'G' . ($start_row + 1), ( float ) $tien_thua_thang_truoc );
		// if ($data ['psConfigLatePayment'] > 0) {
			// $this->objPHPExcel->getActiveSheet ()
				// ->setCellValue ( 'G' . ($start_row + 2), $data ['psConfigLatePayment'] );
		// } else {
			// $index_row_month_pre_remove_3 = $start_row + 2;
		// }

		// $tongphainop = $tong_du_kien_thang_nay - $tien_thua_thang_truoc + $data ['psConfigLatePayment'];

		// $this->objPHPExcel->getActiveSheet ()
			// ->setCellValue ( 'G' . ($start_row + 3), $tongphainop );

		// if ($tien_thua_thang_truoc < 0)
			// $this->objPHPExcel->getActiveSheet ()
				// ->setCellValue ( 'H' . ($start_row + 1), $this->object->getContext ()
				// ->getI18N ()
				// ->__ ( 'Old debt' ) );
		
		// if ($ps_receipt->getPaymentStatus () == PreSchool::ACTIVE) {
			// // Phu huynh nop
			// $this->objPHPExcel->getActiveSheet ()
				// ->setCellValue ( 'G' . ($start_row + 4), ( float ) $ps_receipt->getCollectedAmount () );

			// // Du cua phieu thu thang nay
			// $this->objPHPExcel->getActiveSheet ()
				// ->setCellValue ( 'G' . ($start_row + 5), ( float ) $ps_receipt->getBalanceAmount () );
		// }

		$info_date_export = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'year' ) . ' ' . date ( "Y" );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . ($start_row + 5), $info_date_export );

		// Delete Row
		// if ($data ['psConfigLatePayment'] <= 0) {
			// $this->objPHPExcel->getActiveSheet ()
				// ->removeRow ( $index_row_month_pre_remove_3, 1 );
		// }

		// $this->objPHPExcel->getActiveSheet ()
			// ->removeRow ( $index_row_month_pre_remove_2, 1 );
		// $this->objPHPExcel->getActiveSheet ()
			// ->removeRow ( $index_row_month_pre_remove_1, 1 );
	}

	/**
	 * Xuất phiếu thu của học sinh theo biểu mẫu 3
	 */
	 public function setDataExportReceiptTemplate3Old($data, $ps_receipt, $config_choose_charge_showlate) {
		
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'E4', $ps_receipt->getReceiptNo () );
		
		$receivable_at = $data ['ps_fee_receipt']->getReceiptDate (); // Tháng thu phí
		
		$collectedAmount = $data ['collectedAmount']; // so tien da nop
		
		$balance_last_month_amount = $data ['balance_last_month_amount'];
		
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A6', $this->object->getContext () ->getI18N () ->__ ( 'Du kien phieu thu thang' ).date('m-Y',strtotime($receivable_at)) );
		
		//echo $psConfigLatePayment; die;
		$start_row = 8;
		$index = 0;
		
		$tong_du_kien_thang_cu = $tong_cac_thang_cu = $rs_amount = 0;
		$rs_current = array ();
		$service_id = $service_detail = array();
		$tong_du_kien_thang_nay = 0;
		
		foreach ( $data ['receivable_student'] as $rs ) {
			if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) )) {
				
				$tong_cac_thang_cu = $tong_cac_thang_cu + $rs->getRsAmount ();
				
				$spentNumber = $rs->getRsEnableRoll () == 1 ? $rs->getRsByNumber () : $rs->getRsSpentNumber ();
				
				$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();
				
				$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
				
				if ($rs->getRsServiceId () > 0) {
					$service_id[$rs->getRsServiceId ()] = array('amount'=>($rs_amount - $rs->getRsAmount ()),'number'=>$spentNumber) ;
				}
				if($rs->getRsIsLate() == 1){
					$spentNumber = ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . __ ( 'Minute' );
					$service_id['is_late'] = array('amount'=>($rs->getRsAmount ()),'number'=>$spentNumber) ;
				}
				
				$tong_du_kien_thang_cu = $tong_du_kien_thang_cu + $rs_amount;
				
			}else {
				array_push ( $rs_current, $rs );
			}
		}
		//print_r($service_id); die;
		foreach ( $rs_current as $r_s ) {
			
			$tien_thua = $number_use = $number_not_use = '';
			
			if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) )) {
				
				$index ++;
				
				$month_prev = PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () );
				
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $index );
				
				if ($r_s->getRsReceivableId ()) {
					$title_sevice = $r_s->getRTitle ();
				} elseif ($r_s->getRsServiceId ()) {
					$title_sevice = $r_s->getSTitle ();
				} elseif ($r_s->getRsIsLate () == 1) {
					$title_sevice = $this->object->getContext ()
					->getI18N ()
					->__ ( 'Out late' ) . '(' . date ( "m/Y", strtotime ( $r_s->getRsReceivableAt () ) ) . ')';
				}
				
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $title_sevice );
				
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $r_s->getRsUnitPrice () );
				
				// Phi du kien
				
				if ($r_s->getRsServiceId () > 0) {
					
					$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();
					
					$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
					
					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
					
					if(isset($service_id[$r_s->getRsServiceId ()]['amount'])){
						$tien_thua = $service_id[$r_s->getRsServiceId ()]['amount'];
						$number_use = $service_id[$r_s->getRsServiceId ()]['number'];
					}
					
				} else {
					$rs_amount = $r_s->getRsAmount ();
					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
				}
				
				$spentNumber = $r_s->getRsEnableRoll () == 1 ? $r_s->getRsByNumber () : $r_s->getRsSpentNumber ();
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . $start_row, $r_s->getRsByNumber() );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $rs_amount );
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $start_row, $number_use );
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $start_row, $tien_thua );
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $start_row, $r_s->getRsNote () );
				
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			}
		}
		
		$remove_index = $start_row;
		
		if(isset($service_id['is_late'])){
			
			$start_row = $start_row + 1;
			$title_sevice = $this->object->getContext ()->getI18N ()->__ ( 'Out late' );
			$index = $index+1;
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $index );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . $start_row, $service_id['is_late']['number'] );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, (0 - $service_id['is_late']['amount']) );
			
		}else{
			$this->objPHPExcel->getActiveSheet () ->removeRow ( $start_row, 1 );
		}
		
		$new_balance_last_month_amount2 = $collectedAmount - $tong_du_kien_thang_cu + $data ['balance_last_month_amount'];
		
		if($new_balance_last_month_amount2 != 0){
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			$title_sevice = $this->object->getContext ()->getI18N ()->__ ( 'No thang truoc' );
			$index = $index+1;
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $index );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, $new_balance_last_month_amount2 );
			
			if($new_balance_last_month_amount2 > 0){
				$debt = $this->object->getContext ()->getI18N ()->__ ('Nha truong dang no');
			}else{
				$debt = $this->object->getContext ()->getI18N ()->__ ('Phu huynh dang no');
			}
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, $debt );
		}
		
		$start_row = $start_row + 1;
		
		$this->objPHPExcel->getActiveSheet () ->removeRow ( $remove_index, 1 );
		
		// tien thua thuc te thang truoc
		$newBalanceAmont = $ps_receipt->getBalanceLastMonthAmount ();
		
		$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $newBalanceAmont );
		
		if ($ps_receipt->getPaymentStatus () == PreSchool::ACTIVE) {
			// Phu huynh nop
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . ($start_row+2), ( float ) $ps_receipt->getCollectedAmount () );
			
			// Du cua phieu thu thang nay
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'E' . ($start_row + 3), ( float ) $ps_receipt->getBalanceAmount () );
		}
		
		$info_date_export = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' . $this->object->getContext ()
		->getI18N ()
		->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' . $this->object->getContext ()
		->getI18N () ->__ ( 'year' ) . ' ' . date ( "Y" );
		
		$start_row = $start_row + 4;
		$this->objPHPExcel->getActiveSheet ()
		->setCellValue ( 'F' . $start_row, $info_date_export );
		
	}
	public function setDataExportReceiptTemplate3($data, $ps_receipt, $config_choose_charge_showlate) {
		
		$not_used = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Not used' );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G5', $ps_receipt->getReceiptNo () );

		// Du thang truoc
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'I8', $data ['balance_last_month_amount'] );

		$receivable_at = $data ['ps_fee_receipt']->getReceiptDate (); // Tháng thu phí

		$collectedAmount = $data ['collectedAmount']; // so tien da nop

		$tong_cac_thang_cu = 0;
		$tong_du_kien_cac_thang_cu = 0;

		$start_row = 11;
		$index = 0;
		
		$hoantra = 0;

		$rs_current = array ();

		$index_row_month_pre_remove_1 = $index_row_month_pre_remove_2 = 0;

		foreach ( $data ['receivable_student'] as $r_s ) {
			$receiptDate  = $r_s->getRsReceiptDate();
			// if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) )) {
			if (date ( "Ym", strtotime ( $receivable_at ) ) != date ( "Ym", strtotime ( $receiptDate ) )){
				$index ++;

				$tong_cac_thang_cu = $tong_cac_thang_cu + $r_s->getRsAmount ();

				$month_prev = PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () );

				if ($r_s->getRsServiceId () > 0) {
					$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

					$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
				} else {
					$rs_amount = $r_s->getRsAmount ();
				}

				$spentNumber = $r_s->getRsEnableRoll () == 1 ? $r_s->getRsByNumber () : $r_s->getRsSpentNumber ();
				$dutien = 0;
				if($r_s->getRsEnableRoll () == 0){
					$dutien = ($r_s->getRsSpentNumber() - $r_s->getRsByNumber())*$r_s->getRsUnitPrice ();
				}
				
				if ($r_s->getRsIsLate () == 1) {
					$sl_sd = ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . __ ( 'Minute' );
					$array_hoantra_phatsinh[-1]['tien'] = $r_s->getRsAmount ();
					$array_hoantra_phatsinh[-1]['sl'] = $sl_sd;
					$array_hoantra_phatsinh[-1]['tieude'] = $this->object->getContext ()->getI18N()->__( 'Out late' ) .'('. format_date ( $receiptDate, "MM/yyyy" ) . ')';
					$array_hoantra_phatsinh[-1]['ghichu'] = $this->object->getContext ()->getI18N()->__($r_s->getRsNote());
					$dutien = $r_s->getRsAmount ();
				} else {
					
					$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['tien'] = $dutien;
					$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['sl'] = $sl_sd;
					$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['tieude'] = $r_s->getRsServiceId() ? $r_s->getSTitle() : $r_s->getRTitle();
					$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['ghichu'] = $this->object->getContext ()->getI18N()->__($r_s->getRsNote());
					
				}
				$hoantra = $hoantra + $dutien;
				
			} else {
				
				array_push ( $rs_current, $r_s );
				
			}
		}

		$tong_du_kien_thang_nay = 0;
		
		foreach ( $rs_current as $r_s ) {

			$month_prev = PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () );
			
			$receiptDate  = $r_s->getRsReceivableAt ();
			
			if (date ( "Ym", strtotime ( $receivable_at ) ) == date ( "Ym", strtotime ( $receiptDate ) )){
			
				//tháng
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, date ( 'm-Y', $month_prev ) );

				if ($r_s->getRsReceivableId ()) {
					$title_sevice = $r_s->getRTitle ();
				} elseif ($r_s->getRsServiceId ()) {
					$title_sevice = $r_s->getSTitle ();
				} elseif ($r_s->getRsIsLate () == 1) {
					$title_sevice = $this->object->getContext ()->getI18N ()->__ ( 'Out late' ) . '(' . format_date ( $r_s->getRsReceivableAt (), "MM/yyyy" ) . ')';
				}
				//tên phí
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'B' . $start_row, $title_sevice );
				//giá
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'C' . $start_row, $r_s->getRsUnitPrice () );
				//số lượng
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'D' . $start_row, $r_s->getRsByNumber () );
				//Giảm trừ
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'E' . $start_row, ($r_s->getRsDiscountAmount () > 0) ? $r_s->getRsDiscountAmount () : $r_s->getRsDiscount () );
				//Tạm tính
				if ($r_s->getRsServiceId () > 0) {

					$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

					$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
				} else {
					$rs_amount = $r_s->getRsAmount ();
					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
				}
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, PreNumber::number_format($rs_amount) );
					
				//Hoàn trả / phát sinh
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, isset($array_hoantra_phatsinh[$r_s->getRsServiceId()]) ? PreNumber::number_format($array_hoantra_phatsinh[$r_s->getRsServiceId()]['tien']) : '');
				
				//Ghi chú
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I' . $start_row, $r_s->getRsNote () );
					
				unset($array_hoantra_phatsinh[$r_s->getRsServiceId()]);
				
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
			}
		}

		foreach($array_hoantra_phatsinh as $khoanPhatSinh){
			
			if($khoanPhatSinh['tien'] !=0){
			
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'B' . $start_row, $khoanPhatSinh['tieude']);
				//số lượng
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'D' . $start_row, $khoanPhatSinh['sl']);
				//hoàn trả / phát sinh
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . $start_row, PreNumber::number_format($khoanPhatSinh['tien']));
				//Ghi chú
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'I' . $start_row, $khoanPhatSinh['ghichu']);
					
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
			
			}
		}
		

		$index_row_month_pre_remove_2 = $start_row;

		$start_row = $start_row + 1;
		
		//tổng tạm tính
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F' . $start_row, PreNumber::number_format($tong_du_kien_thang_nay) );
		
		//Số nợ kỳ trước
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F' . ($start_row + 1),  PreNumber::number_format($data['balance_last_month_amount']));
		
		//Hoàn trả phát sinh trì trước
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F' . ($start_row + 2),  PreNumber::number_format($hoantra));
		
		//Dư thực tế tháng trước
		$thuctethangtruoc = $hoantra + $data['balance_last_month_amount'];
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'I' . ($start_row + 2), PreNumber::number_format($thuctethangtruoc));
		
		//Tổng thu kì này
		$phaithukynay = $tong_du_kien_thang_nay + $data['balance_last_month_amount'] + $hoantra;
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F' . ($start_row + 3), PreNumber::number_format($phaithukynay));

		$info_date_export = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'year' ) . ' ' . date ( "Y" );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . ($start_row + 5), $info_date_export );
		
	}

	/**
	 * Xuất phiếu thu của học sinh theo biểu mẫu 4
	 */
	public function setDataExportReceiptTemplate4($data, $ps_receipt, $config_choose_charge_showlate) {
		
		$not_used = $this->object->getContext () ->getI18N () ->__ ( 'Not used' );
		
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'E4', $ps_receipt->getReceiptNo () );
		
		$receivable_at = $data ['ps_fee_receipt']->getReceiptDate (); // Tháng thu phí
		
		$collectedAmount = $data ['collectedAmount']; // so tien da nop
		
		$balance_last_month_amount = $data ['balance_last_month_amount'];
		
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A6', $this->object->getContext () ->getI18N () ->__ ( 'Du kien phieu thu thang' ).date('m-Y',strtotime($receivable_at)) );
		
		//echo $psConfigLatePayment; die;
		$start_row = 8;
		$index = 0;
		
		$tong_cac_thang_cu = $rs_amount = 0;
		$rs_current = array ();
		$service_id = $service_detail = array();
		$tong_du_kien_thang_nay = 0;
		
		foreach ( $data ['receivable_student'] as $rs ) {
			if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) != date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) )) {
				
				$tong_cac_thang_cu = $tong_cac_thang_cu + $rs->getRsAmount ();
				
				$spentNumber = $rs->getRsEnableRoll () == 1 ? $rs->getRsByNumber () : $rs->getRsSpentNumber ();
				/*
				if ($rs->getIsTypeFee () == 1) {
					$spentNumber = $spentNumber;
				}else{
					$spentNumber = '';
				}
				*/
				$rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();
				
				$rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
				
				//if ($rs->getRsEnableRoll() == 0) { // Neu loai dich vu khong co dinh
					if ($rs->getRsServiceId () > 0) {
						$service_id[$rs->getRsServiceId ()] = array('amount'=>($rs_amount - $rs->getRsAmount ()),'number'=>$spentNumber) ;
					}
				//}
				if($rs->getRsIsLate() == 1){
					$spentNumber = ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . __ ( 'Minute' );
					$service_id['is_late'] = array('amount'=>($rs->getRsAmount ()),'number'=>$spentNumber) ;
				}
				
			}else {
				array_push ( $rs_current, $rs );
			}
		}
		//print_r($service_id); die;
		foreach ( $rs_current as $r_s ) {
			
			$tien_thua = $number_use = $number_not_use = '';
			
			if (date ( "Ym", PsDateTime::psDatetoTime ( $receivable_at ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () ) )) {
			
				$index ++;
				
				$month_prev = PsDateTime::psDatetoTime ( $r_s->getRsReceivableAt () );
				
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $index );
				
				if ($r_s->getRsReceivableId ()) {
					$title_sevice = $r_s->getRTitle ();
				} elseif ($r_s->getRsServiceId ()) {
					$title_sevice = $r_s->getSTitle ();
				} elseif ($r_s->getRsIsLate () == 1) {
					$title_sevice = $this->object->getContext ()
					->getI18N ()
					->__ ( 'Out late' ) . '(' . date ( "m/Y", strtotime ( $r_s->getRsReceivableAt () ) ) . ')';
				}
				
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $title_sevice );
				
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $r_s->getRsUnitPrice () );
				
				// Phi du kien
				
				if ($r_s->getRsServiceId () > 0) {
					
					$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();
					
					$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
					
					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
					
					if(isset($service_id[$r_s->getRsServiceId ()]['amount'])){
						$tien_thua = $service_id[$r_s->getRsServiceId ()]['amount'];
						if($r_s->getIsTypeFee () == 1){ // Neu la dich vu theo so lan khong su dung
							$number_not_use = $service_id[$r_s->getRsServiceId ()]['number'];
						}else{
							$number_use = $service_id[$r_s->getRsServiceId ()]['number'];
						}
					}
					
				} else {
					$rs_amount = $r_s->getRsAmount ();
					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
				}
				
				$spentNumber = $r_s->getRsEnableRoll () == 1 ? $r_s->getRsByNumber () : $r_s->getRsSpentNumber ();
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . $start_row, $r_s->getRsByNumber() );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $rs_amount );
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $start_row, $number_use );
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $start_row, $number_not_use );
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $start_row, $tien_thua );
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'I' . $start_row, $r_s->getRsNote () );
				
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			}
		}
		
		$remove_index = $start_row;
		
		if(isset($service_id['is_late'])){
			
			$start_row = $start_row + 1;
			$title_sevice = $this->object->getContext ()->getI18N ()->__ ( 'Out late' );
			$index = $index+1;
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $index );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . $start_row, $service_id['is_late']['number'] );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, (0 - $service_id['is_late']['amount']) );
			
		}else{
			$this->objPHPExcel->getActiveSheet () ->removeRow ( $start_row, 1 );
		}
		
		if($balance_last_month_amount != 0){
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			$title_sevice = $this->object->getContext ()->getI18N ()->__ ( 'Balance last month amount' );
			$index = $index+1;
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $index );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, $balance_last_month_amount );
			
		}
		
		if($collectedAmount <= 0){
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			$title_sevice = $this->object->getContext ()->getI18N ()->__ ( 'No thang truoc' );
			$index = $index+1;
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $index );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $title_sevice );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, $collectedAmount - $tong_cac_thang_cu + $data ['balance_last_month_amount'] );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, $this->object->getContext ()->getI18N ()->__ ('Old debt') );
		}
		
		$start_row = $start_row + 1;
		
		$this->objPHPExcel->getActiveSheet () ->removeRow ( $remove_index, 1 );
	
		// tien thua thuc te thang truoc
		$newBalanceAmont = $collectedAmount - $tong_cac_thang_cu + $data ['balance_last_month_amount'];
		
		//echo $newBalanceAmont; die;
		$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $newBalanceAmont );
		
		if ($ps_receipt->getPaymentStatus () == PreSchool::ACTIVE) {
			// Phu huynh nop
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . ($start_row+2), ( float ) $ps_receipt->getCollectedAmount () );
			
			// Du cua phieu thu thang nay
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'E' . ($start_row + 3), ( float ) $ps_receipt->getBalanceAmount () );
		}
		
		$info_date_export = $this->object->getContext ()
		->getI18N ()
		->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' . $this->object->getContext ()
		->getI18N ()
		->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' . $this->object->getContext ()
		->getI18N () ->__ ( 'year' ) . ' ' . date ( "Y" );
		
		$start_row = $start_row + 4;
		$this->objPHPExcel->getActiveSheet ()
		->setCellValue ( 'G' . $start_row, $info_date_export );
		
	}
	
	/**
	 * Set data cho phieu thu cua 1 hoc sinh *
	 */
	public function setDataExportReceipt($data, $ps_receipt, $a4 = true) {

		// Ma phieu thu
		// Lien 1
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G5', $ps_receipt->getReceiptNo () );

		// Lien 2
		if ($a4)
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'P5', $ps_receipt->getReceiptNo () );

		// Tong cac khoan phi (ko tinh về muộn) của các tháng trước
		$total_oldRsAmount = 0;
		$total_oldRsLateAmount = 0;
		$i = 1;
		// $collectedAmount == $data ['collectedAmount'];

		$rs_current = array ();

		$start_row = 11;

		$index_row_month_pre_remove = 0;
		
		/*-------------*/
		$receivable_at = $ps_receipt->getReceiptDate(); // Tháng thu phí
						
		$rs_current = array();
		$tong_cac_thang_cu = 0;
		$tong_du_kien_cac_thang_cu = 0;
		$hoantra = 0;
		$array_hoantra_phatsinh = array();
		
		foreach ( $data ['receivable_student'] as $k => $r_s ) {
			$receiptDate  = $r_s->getRsReceiptDate();
			if (date ( "Ym", strtotime ( $receivable_at ) ) != date ( "Ym", strtotime ( $receiptDate ) )){
				
				$tong_cac_thang_cu = $tong_cac_thang_cu + $r_s->getRsAmount ();
				
				if ($r_s->getRsServiceId () > 0) {
					$rs_amount = ($r_s->getRsDiscount () > 0) ? ((100 - $r_s->getRsDiscount ()) * $r_s->getRsUnitPrice () * $r_s->getRsByNumber ()) / 100 : $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();

					$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
				} else {
					$rs_amount = $r_s->getRsAmount ();
				}

				$spentNumber = $r_s->getRsEnableRoll () == 1 ? $r_s->getRsByNumber () : $r_s->getRsSpentNumber ();
				$dutien = 0;
				if($r_s->getRsEnableRoll () == 0){
					$dutien = ($r_s->getRsSpentNumber() - $r_s->getRsByNumber())*$r_s->getRsUnitPrice ();
				}
				
				if ($r_s->getRsIsLate () == 1) {
					$sl_sd = ($config_choose_charge_showlate == 1) ? PsDateTime::psMinutetoHour ( $spentNumber, '%02dh:%02d' ) : PreNumber::number_format ( $spentNumber ) . ' ' . __ ( 'Minute' );
					$array_hoantra_phatsinh[-1]['tien'] = $r_s->getRsAmount ();
					$array_hoantra_phatsinh[-1]['sl'] = $sl_sd;
					$array_hoantra_phatsinh[-1]['tieude'] = $this->object->getContext ()->getI18N ()->__( 'Out late' ) .'('. format_date ( $receiptDate, "MM/yyyy" ) . ')';
					$array_hoantra_phatsinh[-1]['ghichu'] = $this->object->getContext ()->getI18N ()->__($r_s->getRsNote());
					$dutien = $r_s->getRsAmount ();
				} else {
					
					$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['tien'] = $dutien;
					$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['sl'] = $sl_sd;
					$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['tieude'] = $r_s->getRsServiceId() ? $r_s->getSTitle() : $r_s->getRTitle();
					$array_hoantra_phatsinh[$r_s->getRsServiceId ()]['ghichu'] = $this->object->getContext ()->getI18N ()->__($r_s->getRsNote());
				}
				$hoantra = $hoantra + $dutien;

			} else {
			
				array_push ( $rs_current, $r_s );
			
			}
		}
		$tong_du_kien_thang_nay = 0;
		foreach ( $rs_current as $r_s ) {
			$receiptDate  = $r_s->getRsReceivableAt ();
			if (date ( "Ym", strtotime ( $receivable_at ) ) == date ( "Ym", strtotime ( $receiptDate ) )){
				
				if ($r_s->getRsReceivableId ()) {
					$title = $r_s->getRTitle ();
				} elseif ($r_s->getRsServiceId ()) {
					$title = $r_s->getSTitle ();
				} elseif ($r_s->getRsIsLate () == 1) {
					$title = $this->object->getContext ()
						->getI18N ()
						->__ ( 'Out late' );
				}
				
				// Phi du kien
				if ($r_s->getRsServiceId () > 0) {
					$rs_amount = $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();
					$rs_amount = $rs_amount - ( float ) $r_s->getRsDiscountAmount ();
					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
				} else {
					$rs_amount = $r_s->getRsUnitPrice () * $r_s->getRsByNumber ();
					$tong_du_kien_thang_nay = $tong_du_kien_thang_nay + $rs_amount;
				}
				
				// Liên 1
				//stt
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $i );
				//khoản thu
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'B' . $start_row, mb_convert_encoding ( $title, "UTF-8", "auto" ) );
				//đơn giá
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'D' . $start_row, $r_s->getRsUnitPrice () );
				//sl dự kiến
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'E' . $start_row, $r_s->getRsByNumber () );
				//tiền tạm tính
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, $rs_amount );
				//hoàn trả / phát sinh
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . $start_row, isset($array_hoantra_phatsinh[$r_s->getRsServiceId()]) ? $array_hoantra_phatsinh[$r_s->getRsServiceId()]['tien'] : '');
				unset($array_hoantra_phatsinh[$r_s->getRsServiceId()]);
				//ghi chú
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'H' . $start_row, $r_s->getRsNote () );
					
				//Liên 2	
				if($a4){
					//stt
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'J' . $start_row, $i );
					//khoản thu
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'K' . $start_row, mb_convert_encoding ( $title, "UTF-8", "auto" ) );
					//đơn giá
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'M' . $start_row, $r_s->getRsUnitPrice () );
					//sl dự kiến
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'N' . $start_row, $r_s->getRsByNumber () );
					//tiền tạm tính
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'O' . $start_row, $rs_amount );
					//hoàn trả / phát sinh
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'P' . $start_row, isset($array_hoantra_phatsinh[$r_s->getRsServiceId()]) ? $array_hoantra_phatsinh[$r_s->getRsServiceId()]['tien'] : '');
					unset($array_hoantra_phatsinh[$r_s->getRsServiceId()]);
					//ghi chú
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'Q' . $start_row, $r_s->getRsNote () );
				}
				
				$i ++;
				$start_row = $start_row + 1;
				
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'B' . $start_row . ':C' . $start_row );
			}
		}
		
		foreach($array_hoantra_phatsinh as $khoanPhatSinh){
			
			if($khoanPhatSinh['tien'] !=0){
				
				//Liên 1
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'B' . $start_row, $khoanPhatSinh['tieude']);
				//số lượng
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'E' . $start_row, $khoanPhatSinh['sl']);
				//hoàn trả / phát sinh
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . $start_row, PreNumber::number_format($khoanPhatSinh['tien']));
				//Ghi chú
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'H' . $start_row, $khoanPhatSinh['ghichu']);
				
				//Liên 2
				if($a4){
					
					$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'K' . $start_row, $khoanPhatSinh['tieude']);
					//số lượng
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'N' . $start_row, $khoanPhatSinh['sl']);
					//hoàn trả / phát sinh
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'P' . $start_row, PreNumber::number_format($khoanPhatSinh['tien']));
					//Ghi chú
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'Q' . $start_row, $khoanPhatSinh['ghichu']);
				
				}
					
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
			
			}
		}

		$start_row = $start_row + 1;
		
		//Liên 1
			//tổng tạm tính
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, PreNumber::number_format($tong_du_kien_thang_nay) );
			
			//Số nợ kỳ trước
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . ($start_row + 1),  PreNumber::number_format($data['balance_last_month_amount']));
			
			//Hoàn trả phát sinh trì trước
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . ($start_row + 2),  PreNumber::number_format($hoantra));
			
			//Dư thực tế tháng trước
			$thuctethangtruoc = $hoantra + $data['balance_last_month_amount'];
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . ($start_row + 2), PreNumber::number_format($thuctethangtruoc));
			
			//Tổng thu kì này
			$phaithukynay = $tong_du_kien_thang_nay + $data['balance_last_month_amount'] + $hoantra;
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . ($start_row + 3), PreNumber::number_format($phaithukynay));

			$info_date_export = $this->object->getContext ()
				->getI18N ()
				->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'year' ) . ' ' . date ( "Y" );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . ($start_row + 5), $info_date_export );
				
		//Liên 2
		if($a4){
			//tổng tạm tính
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'O' . $start_row, PreNumber::number_format($tong_du_kien_thang_nay) );
			
			//Số nợ kỳ trước
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'O' . ($start_row + 1),  PreNumber::number_format($data['balance_last_month_amount']));
			
			//Hoàn trả phát sinh trì trước
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'O' . ($start_row + 2),  PreNumber::number_format($hoantra));
			
			//Dư thực tế tháng trước
			$thuctethangtruoc = $hoantra + $data['balance_last_month_amount'];
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'Q' . ($start_row + 2), PreNumber::number_format($thuctethangtruoc));
			
			//Tổng thu kì này
			$phaithukynay = $tong_du_kien_thang_nay + $data['balance_last_month_amount'] + $hoantra;
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'O' . ($start_row + 3), PreNumber::number_format($phaithukynay));

			$info_date_export = $this->object->getContext ()
				->getI18N ()
				->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'year' ) . ' ' . date ( "Y" );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'O' . ($start_row + 5), $info_date_export );
		}
		
		/*-------------*/

		// foreach ( $data ['receivable_student'] as $k => $rs ) {

			// if ((date ( "Ym", PsDateTime::psDatetoTime ( $ps_receipt->getReceiptDate () ) ) == date ( "Ym", PsDateTime::psDatetoTime ( $rs->getRsReceivableAt () ) ))) {
				// if ($rs->getRsReceivableId ()) {
					// $title = $rs->getRTitle ();
				// } elseif ($rs->getRsServiceId ()) {
					// $title = $rs->getSTitle ();
				// } elseif ($rs->getRsIsLate () == 1) {
					// $title = $this->object->getContext ()
						// ->getI18N ()
						// ->__ ( 'Out late' );
				// }

				// // Phi du kien
				// if ($rs->getRsServiceId () > 0) {
					// $rs_amount = ($rs->getRsDiscount () > 0) ? ((100 - $rs->getRsDiscount ()) * $rs->getRsUnitPrice () * $rs->getRsByNumber ()) / 100 : $rs->getRsUnitPrice () * $rs->getRsByNumber ();
					// $rs_amount = $rs_amount - ( float ) $rs->getRsDiscountAmount ();
				// } else {
					// $rs_amount = $rs->getRsAmount ();
				// }

				// // Liên 1
				// $this->objPHPExcel->getActiveSheet ()
					// ->setCellValue ( 'A' . $start_row, $i );
				// $this->objPHPExcel->getActiveSheet ()
					// ->setCellValue ( 'B' . $start_row, mb_convert_encoding ( $title, "UTF-8", "auto" ) );

				// $this->objPHPExcel->getActiveSheet ()
					// ->setCellValue ( 'D' . $start_row, $rs->getRsUnitPrice () );
				// $this->objPHPExcel->getActiveSheet ()
					// ->setCellValue ( 'E' . $start_row, $rs->getRsByNumber () );
				// $this->objPHPExcel->getActiveSheet ()
					// ->setCellValue ( 'F' . $start_row, $rs_amount );
				// $this->objPHPExcel->getActiveSheet ()
					// ->setCellValue ( 'G' . $start_row, $rs->getRsNote () );

				// if ($a4) { // Liên 2
					// $this->objPHPExcel->getActiveSheet ()
						// ->setCellValue ( 'I' . $start_row, $i );
					// $this->objPHPExcel->getActiveSheet ()
						// ->setCellValue ( 'J' . $start_row, mb_convert_encoding ( $title, "UTF-8", "auto" ) );

					// $this->objPHPExcel->getActiveSheet ()
						// ->setCellValue ( 'L' . $start_row, $rs->getRsUnitPrice () );
					// $this->objPHPExcel->getActiveSheet ()
						// ->setCellValue ( 'M' . $start_row, $rs->getRsByNumber () );
					// $this->objPHPExcel->getActiveSheet ()
						// ->setCellValue ( 'N' . $start_row, $rs_amount );
					// $this->objPHPExcel->getActiveSheet ()
						// ->setCellValue ( 'O' . $start_row, $rs->getRsNote () );
				// }
				// $i ++;
				// $start_row = $start_row + 1;

				// $this->objPHPExcel->getActiveSheet ()
					// ->insertNewRowBefore ( $start_row, 1 );

				// $this->objPHPExcel->getActiveSheet ()
					// ->mergeCells ( 'B' . $start_row . ':C' . $start_row );
			// } else {
				// if ($rs->getRsIsLate () == 1 && $rs->getRsAmount () > 0) {
					// $total_oldRsLateAmount = $total_oldRsLateAmount + $rs->getRsAmount ();
				// } else {
					// $total_oldRsAmount = $total_oldRsAmount + $rs->getRsAmount (); // Tong tien khong chứa Về muộn
				// }
			// }
		// }

		// // Thong so cuoi phiếu
		// // Liên 1
		// // Tiền thừa của tháng trước
		// // $tien_thua_thang_truoc = $data ['collectedAmount'] - ($data ['totalAmount'] - $data ['totalAmountReceivableAt']);

		// $tien_thua_thang_truoc = $data ['collectedAmount'] - ($total_oldRsAmount + $total_oldRsLateAmount - $data ['balance_last_month_amount']);

		// $this->objPHPExcel->getActiveSheet ()
			// ->setCellValue ( 'F' . ($start_row + 3), ( float ) $tien_thua_thang_truoc );

		// $this->objPHPExcel->getActiveSheet ()
			// ->setCellValue ( 'F' . ($start_row + 4), $data ['psConfigLatePayment'] );

		// if ($tien_thua_thang_truoc < 0)
			// $this->objPHPExcel->getActiveSheet ()
				// ->setCellValue ( 'G' . ($start_row + 3), $this->object->getContext ()
				// ->getI18N ()
				// ->__ ( 'Old debt' ) );

		// if ($ps_receipt->getPaymentStatus () == PreSchool::ACTIVE) {
			// // Phu huynh nop
			// $this->objPHPExcel->getActiveSheet ()
				// ->setCellValue ( 'F' . ($start_row + 6), ( float ) $ps_receipt->getCollectedAmount () );

			// // Du cua phieu thu thang nay
			// $this->objPHPExcel->getActiveSheet ()
				// ->setCellValue ( 'F' . ($start_row + 7), ( float ) $ps_receipt->getBalanceAmount () );
		// }

		// $info_date_export = $this->object->getContext ()
			// ->getI18N ()
			// ->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' . $this->object->getContext ()
			// ->getI18N ()
			// ->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' . $this->object->getContext ()
			// ->getI18N ()
			// ->__ ( 'year' ) . ' ' . date ( "Y" );

		// $this->objPHPExcel->getActiveSheet ()
			// ->setCellValue ( 'F' . ($start_row + 8), $info_date_export );
		// // END Liên 1

		// $row_format = $start_row + 8;

		// if ($a4) { // Lien 2

			// // Tiền thừa của tháng trước
			// // $tien_thua_thang_truoc = $data ['collectedAmount'] - ($data ['totalAmount'] - $data ['totalAmountReceivableAt']);
			// $this->objPHPExcel->getActiveSheet ()
				// ->setCellValue ( 'N' . ($start_row + 3), ( float ) $tien_thua_thang_truoc );

			// $this->objPHPExcel->getActiveSheet ()
				// ->setCellValue ( 'N' . ($start_row + 4), $data ['psConfigLatePayment'] );

			// if ($tien_thua_thang_truoc < 0)
				// $this->objPHPExcel->getActiveSheet ()
					// ->setCellValue ( 'O' . ($start_row + 3), $this->object->getContext ()
					// ->getI18N ()
					// ->__ ( 'Old debt' ) );

			// if ($ps_receipt->getPaymentStatus () == PreSchool::ACTIVE) {

				// // Du cua phieu thu thang nay
				// $this->objPHPExcel->getActiveSheet ()
					// ->setCellValue ( 'N' . ($start_row + 7), ( float ) $ps_receipt->getBalanceAmount () );

				// // Phu huynh nop
				// $this->objPHPExcel->getActiveSheet ()
					// ->setCellValue ( 'N' . ($start_row + 6), ( float ) $ps_receipt->getCollectedAmount () );
			// }

			// $info_date_export = $this->object->getContext ()
				// ->getI18N ()
				// ->__ ( 'Day' ) . ' ' . date ( "d" ) . ' ' . $this->object->getContext ()
				// ->getI18N ()
				// ->__ ( 'month' ) . ' ' . date ( "m" ) . ' ' . $this->object->getContext ()
				// ->getI18N ()
				// ->__ ( 'year' ) . ' ' . date ( "Y" );

			// $this->objPHPExcel->getActiveSheet ()
				// ->setCellValue ( 'N' . ($start_row + 8), $info_date_export );
		// }

		// $this->objPHPExcel->getActiveSheet ()
			// ->removeRow ( $start_row, 1 );
		// $this->objPHPExcel->getActiveSheet ()
			// ->removeRow ( $start_row, 1 );

		// if ($index_row_month_pre_remove > 0) {
			// $this->objPHPExcel->getActiveSheet ()
				// ->removeRow ( $index_row_month_pre_remove, 1 );

			// if ($index_row_month_pre_remove == 8) {
				// $this->objPHPExcel->getActiveSheet ()
					// ->removeRow ( $index_row_month_pre_remove, 1 );
				// $this->objPHPExcel->getActiveSheet ()
					// ->removeRow ( $index_row_month_pre_remove, 1 );
				// $this->objPHPExcel->getActiveSheet ()
					// ->removeRow ( $index_row_month_pre_remove, 1 );
			// }
		// }
	}

	/**
	 * Tao tieu de cho bao phi bao no
	 */
	public function setCustomerInfoExportFeeDebtClass($ps_customer_info = null, $title_info = array()) {

		if ($ps_customer_info != null) {
			// Ve Logo
			if ($ps_customer_info->getCusLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $ps_customer_info->getCusYearData () . '/' . $ps_customer_info->getCusLogo () )) {

				$objDrawing = new PHPExcel_Worksheet_Drawing ();

				$objDrawing->setName ( 'Logo' );

				$objDrawing->setDescription ( $ps_customer_info->getCusSchoolName () );

				$objDrawing->setPath ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $ps_customer_info->getCusYearData () . '/' . $ps_customer_info->getCusLogo () );

				$objDrawing->setOffsetY ( 10 );
				$objDrawing->setOffsetX ( 10 );
				$objDrawing->setCoordinates ( 'A1' );
				$objDrawing->setHeight ( 80 );
				$objDrawing->setWidth ( 80 );

				$objDrawing->getShadow ()
					->setVisible ( true );
				$objDrawing->getShadow ()
					->setDirection ( 80 );
				$objDrawing->setWorksheet ( $this->objPHPExcel->getActiveSheet () );
			}

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C1', $ps_customer_info->getCusSchoolName () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C2', $ps_customer_info->getWpName () );

			$tel = $this->object->getContext ()
				->getI18N ()
				->__ ( 'Tel2' );

			$address = $this->object->getContext ()
				->getI18N ()
				->__ ( 'Address' );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C3', $address . ': ' . $ps_customer_info->getCusAddress () . ' - ' . $tel . ': ' . ($ps_customer_info->getCusTel () != '' ? $ps_customer_info->getCusTel () : $ps_customer_info->getCusMobile ()) );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A4', $title_info ['fee_notification'] . $title_info ['fee_time'] );

			$this->objPHPExcel->getActiveSheet ()
				->setTitle ( $ps_customer_info->getMcName () );
		}
	}

	/**
	 * Set data cho thong ke chi tiet phieu no phi
	 */
	public function setDataExportFeeDebtClass($data, $class_name) {

		// In thong tin ten lop,
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A5', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Class' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'B5', $class_name );

		$no_info = $this->object->getContext ()
			->getI18N ()
			->__ ( 'No data information' );
		$start_row = 7;

		foreach ( $data as $key => $dt ) {
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $key + 1 );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $dt->getStudentCode () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $dt->getStudentName () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $dt->getReceiptNo () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, $dt->getBalanceAmount () ? abs ( $dt->getBalanceAmount () ) : $no_info );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $dt->getCollectedAmount () ? $dt->getCollectedAmount () : $no_info );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $dt->getCollectedAmount () - abs ( $dt->getBalanceAmount () ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . $start_row, '' );

			$start_row ++;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
		}
		$this->objPHPExcel->getActiveSheet ()
			->removeRow ( $start_row, 2 );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'E' . $start_row, '=SUM(E7:E' . $start_row . ')' );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F' . $start_row, '=SUM(F7:F' . $start_row . ')' );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . $start_row, '=SUM(G7:G' . $start_row . ')' );

		$start_row ++;
		$day = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' );
		$month = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Month' );
		$year = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Year' );
		$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'G' . $start_row )
			->getAlignment ()
			->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );
	}
}