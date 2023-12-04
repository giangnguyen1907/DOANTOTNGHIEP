<?php
class exportTimesheetSummaryReportHelper extends ExportHelper {

	public function __construct($object) {

		parent::__construct ($object);

		$this->object = $object;
	}

	/**
	 * Tao tieu de cho bao cao danh sach sinh vien
	 */
	public function setDataExportTimesheetInfoExport($school_name, $title_info, $title_xls) {

		if ($school_name != null) {
			// Ve Logo
			if ($school_name->getLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $school_name->getYearData () . '/' . $school_name->getLogo () )) {

				$objDrawing = new PHPExcel_Worksheet_Drawing ();

				$objDrawing->setName ( 'Logo' );

				$objDrawing->setDescription ( $school_name->getTitle () );

				$objDrawing->setPath ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $school_name->getYearData () . '/' . $school_name->getLogo () );

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
				->setCellValue ( 'D1', $school_name->getTitle () );

			$address = $this->object->getContext ()
				->getI18N ()
				->__ ( 'Tel2' );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D2', $school_name->getAddress () . '-' . $address . ': ' . ($school_name->getTel () != '' ? $school_name->getTel () : $school_name->getMobile ()) );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A4', $title_info );

			$this->objPHPExcel->getActiveSheet ()
				->setTitle ( $title_xls );
		}
	}

	/**
	 * Set data cho bao cao danh sach sinh vien
	 */
	public function setDataExportTimesheet($filter_list_member, $timesheet_summarys, $member_absent, $year_month) {

		$day = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' );
		$month = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Month' );
		$year = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Year' );

		$saturday = PsDateTime::psSaturdaysOfMonth ( $year_month );
		$sunday = PsDateTime::psSundaysOfMonth ( $year_month );

		$number_day = PsDateTime::psNumberDaysOfMonth ( $year_month );

		// print_r($sunday); die();
		$start_row = 6;
		$index = 1;
		$number = count ( $filter_list_member );

		$array_time = array ();
		foreach ( $timesheet_summarys as $timesheet ) {
			array_push ( $array_time, $timesheet->getMemberId () . date ( "Ymd", strtotime ( $timesheet->getTimesheetAt () ) ) );
		}

		$array_absent = array ();
		foreach ( $member_absent as $absent ) {
			array_push ( $array_absent, $absent->getMemberId () . $absent->getAbsentType () );
		}

		foreach ( $filter_list_member as $ky => $list_member ) {
			$index ++;

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $ky + 1 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $list_member->getMemberName () );

			$x = 1;
			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '01-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'C' . $start_row, $x );
			}
			if (in_array ( 1, $saturday ))
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'C' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
			elseif (in_array ( 1, $sunday ))
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'C' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '02-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'D' . $start_row, $x );
			}
			if (in_array ( 2, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'D' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'E' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '03-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'E' . $start_row, $x );
			}
			if (in_array ( 3, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'E' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'F' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '04-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, $x );
			}

			if (in_array ( 4, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'F' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'G' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '05-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . $start_row, $x );
			}
			if (in_array ( 5, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'G' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'H' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '06-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'H' . $start_row, $x );
			}
			if (in_array ( 6, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'H' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'I' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '07-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'I' . $start_row, $x );
			}
			if (in_array ( 7, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'I' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'J' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '08-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'J' . $start_row, $x );
			}
			if (in_array ( 8, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'J' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'K' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '09-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'K' . $start_row, $x );
			}
			if (in_array ( 9, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'K' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'L' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '10-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'L' . $start_row, $x );
			}
			if (in_array ( 10, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'L' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'M' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '11-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'M' . $start_row, $x );
			}
			if (in_array ( 11, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'M' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'N' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '12-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'N' . $start_row, $x );
			}
			if (in_array ( 12, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'N' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'O' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '13-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'O' . $start_row, $x );
			}
			if (in_array ( 13, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'O' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'P' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '14-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'P' . $start_row, $x );
			}
			if (in_array ( 14, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'P' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'Q' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '15-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'Q' . $start_row, $x );
			}
			if (in_array ( 15, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'Q' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'R' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '16-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'R' . $start_row, $x );
			}
			if (in_array ( 16, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'R' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'S' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '17-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'S' . $start_row, $x );
			}
			if (in_array ( 17, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'S' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'T' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '18-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'T' . $start_row, $x );
			}
			if (in_array ( 18, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'T' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'U' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '19-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'U' . $start_row, $x );
			}
			if (in_array ( 19, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'U' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'V' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '20-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'V' . $start_row, $x );
			}
			if (in_array ( 20, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'V' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'W' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '21-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'W' . $start_row, $x );
			}
			if (in_array ( 21, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'W' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'X' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '22-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'X' . $start_row, $x );
			}
			if (in_array ( 22, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'X' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'Y' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '23-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'Y' . $start_row, $x );
			}
			if (in_array ( 23, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'Y' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'Z' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '24-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'Z' . $start_row, $x );
			}
			if (in_array ( 24, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'Z' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'AA' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '25-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'AA' . $start_row, $x );
			}
			if (in_array ( 25, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'AA' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'AB' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '26-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'AB' . $start_row, $x );
			}
			if (in_array ( 26, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'AB' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'AC' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '27-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'AC' . $start_row, $x );
			}
			if (in_array ( 27, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'AC' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'AD' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '28-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'AD' . $start_row, $x );
			}
			if (in_array ( 28, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'AD' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				if ($number_day ['number_day_month'] <= 29)
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'AD' . $start_row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => '3c454c' ) ) );
				else
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'AE' . $start_row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'a90329' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '29-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'AE' . $start_row, $x );
			}
			if ($number_day ['number_day_month'] <= 28) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'AE' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => '3c454c' ) ) );
			} elseif (in_array ( 29, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'AE' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				if ($number_day ['number_day_month'] <= 30)
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'AF' . $start_row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'a90329' ) ) );
				else
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'AG' . $start_row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => '3c454c' ) ) );
			}

			if (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '30-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'AF' . $start_row, $x );
			}
			if ($number_day ['number_day_month'] <= 29) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'AF' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => '3c454c' ) ) );
			} elseif (in_array ( 30, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'AF' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				if ($number_day ['number_day_month'] <= 30)
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'AG' . $start_row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => '3c454c' ) ) );
				else
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'AG' . $start_row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'a90329' ) ) );
			}

			if ($number_day ['number_day_month'] <= 30) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'AG' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => '3c454c' ) ) );
			} elseif (in_array ( $list_member->getMbId () . date ( "Ymd", strtotime ( '31-' . $year_month ) ), $array_time )) {
				$c ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'AG' . $start_row, $x );
			}
			if (in_array ( 31, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'AG' . $start_row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
			}

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'AH' . $start_row, $number_day ['saturday_day'] );

			if (in_array ( $list_member->getMbId () . '1', $array_absent )) {
				$a ++;
			} elseif (in_array ( $list_member->getMbId () . '0', $array_absent )) {
				$b ++;
			}
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'AI' . $start_row, $a );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'AJ' . $start_row, $b );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'AK' . $start_row, $c );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'AL' . $start_row, $a + $c );
			$a = '';
			$c = '';
			$b = '';

			$start_row = $start_row + 1;
			if ($index <= $number)
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
		}

		$start_row = $start_row + 2;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'AF' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );
	}
}