<?php
class exportStudentLogtimesReportHelper extends ExportHelper {

	public function __construct($object) {

		parent::__construct ($object);

		$this->object = $object;
	}

	/**
	 * Clone sheet
	 */
	public function createNewSheet($key) {
		
		$objWorkSheet1 = clone $this->objPHPExcel->getSheet ();
		
		$objWorkSheet1->setTitle ( 'Cloned Sheet' );
		
		$this->objPHPExcel->addSheet ( $objWorkSheet1 );
		
		$this->sheet_index = $key + 1;
		
		$this->objPHPExcel->setActiveSheetIndex ( $this->sheet_index );
	}
	
	/**
	 * Remove sheet
	 */
	public function removeSheet() {
		
		$this->objPHPExcel->removeSheetByIndex ( 0 );
	}
	
	/**
	 * Tao tieu de cho IMPORT ke hoach giao duc
	 */
	public function setDataExportTieuDeKeHoachGiaoDuc($school_name, $title_info, $title_xls) {
		
		// echo $school_name->getTitle(); die();
		if ($school_name != null) {
			// Ve Logo
			if ($school_name->getLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $school_name->getYearData () . '/' . $school_name->getLogo () )) {
				
				$objDrawing = new PHPExcel_Worksheet_Drawing ();
				
				$objDrawing->setName ( 'Logo' );
				
				$objDrawing->setDescription ( $school_name->getTitle () );
				
				$objDrawing->setPath ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $school_name->getYearData () . '/' . $school_name->getLogo () );
				
				$objDrawing->setOffsetY ( 8 );
				$objDrawing->setOffsetX ( 8 );
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
			->setCellValue ( 'C1', $school_name->getTitle () );
			
			$address = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Tel2' );
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C2', $school_name->getAddress () . '-' . $address . ': ' . ($school_name->getTel () != '' ? $school_name->getTel () : $school_name->getMobile ()) );
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A3', $title_info );
			
			$this->objPHPExcel->getActiveSheet ()
			->setTitle ( $title_xls );
		}
	}
	/**
	 * Tao tieu de cho bao cao danh sach sinh vien
	 */
	public function setDataExportStatisticInfoExportA($school_name, $title_info, $title_xls) {
		
		// echo $school_name->getTitle(); die();
		if ($school_name != null) {
			// Ve Logo
			if ($school_name->getLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $school_name->getYearData () . '/' . $school_name->getLogo () )) {
				
				$objDrawing = new PHPExcel_Worksheet_Drawing ();
				
				$objDrawing->setName ( 'Logo' );
				
				$objDrawing->setDescription ( $school_name->getTitle () );
				
				$objDrawing->setPath ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $school_name->getYearData () . '/' . $school_name->getLogo () );
				
				$objDrawing->setOffsetY ( 11 );
				$objDrawing->setOffsetX ( 40 );
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
			->setCellValue ( 'B1', $school_name->getTitle () );
			
			$address = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Tel2' );
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'B2', $school_name->getAddress () . '-' . $address . ': ' . ($school_name->getTel () != '' ? $school_name->getTel () : $school_name->getMobile ()) );
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A3', $title_info );
			
			$this->objPHPExcel->getActiveSheet ()
			->setTitle ( $title_xls );
		}
	}
	
	/**
	 * Tao tieu de cho bao cao danh sach sinh vien
	 */
	public function setDataExportStatisticInfoExport($school_name, $title_info, $title_xls) {

		// echo $school_name->getTitle(); die();
		if ($school_name != null) {
			// Ve Logo
			if ($school_name->getLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $school_name->getYearData () . '/' . $school_name->getLogo () )) {

				$objDrawing = new PHPExcel_Worksheet_Drawing ();

				$objDrawing->setName ( 'Logo' );

				$objDrawing->setDescription ( $school_name->getTitle () );

				$objDrawing->setPath ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $school_name->getYearData () . '/' . $school_name->getLogo () );

				$objDrawing->setOffsetY ( 11 );
				$objDrawing->setOffsetX ( 40 );
				$objDrawing->setCoordinates ( 'B1' );
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
				->setCellValue ( 'A3', $title_info );

			$this->objPHPExcel->getActiveSheet ()
				->setTitle ( $title_xls );
		}
	}

	/**
	 * Tao thong tin lop
	 */
	public function setStatisticInfoExportGrowths($class_name, $examination) {

		$class = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Class' );
		$examina = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Examination' );
		$date = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Date' );

		$inputdate = $examination->getInputDateAt ();
		$inputdateat = PsDateHelper::format_date ( $inputdate );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A5', $class_name->getWpName () );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C5', $class . ' : ' . $class_name->getMcName () );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'E5', $examina . ' : ' . $examination->getName () . ' , ' . $date . ' : ' . $inputdateat );
	}

	/**
	 * Set data cho bao cao danh sach sinh vien
	 */
	public function setDataExportStatistic($filter_list_student, $filter_list_logtime, $month_year,$infoclass) {

		$day = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' );
		$month = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Month' );
		$year = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Year' );
	
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A4', $infoclass );
		
		$number_day = PsDateTime::psNumberDaysOfMonth ( $month_year );

		$saturday = PsDateTime::psSaturdaysOfMonth ( $month_year );
		$sunday = PsDateTime::psSundaysOfMonth ( $month_year );

		//print_r($number_day); die;
		
		$songaykohocthu7 = $number_day ['normal_day'];
		$songayhocthu7 = $number_day ['saturday_day'];
		
		$f = $a = $b = $c = $e = $h = '';
		$start = 6;
		$index = 0;

		for($k = 1; $k <= $number_day ['number_day_month']; $k ++) {
			$col = PHPExcel_Cell::stringFromColumnIndex ( 3 + $index );
			$row = $start;
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col . $row, $k );
			if (in_array ( $k, $sunday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}
			if (in_array ( $k, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
			}

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getAlignment ()
				->setWrapText ( true );
			$index ++;
		}

		$start_row = 7;
		$index = 1;
		$number = count ( $filter_list_student );

		$array_logtime = array ();
		$array_goschool = $array_notgoschool = array ();
		foreach ( $filter_list_logtime as $list_logtimes ) {
			array_push ( $array_logtime, $list_logtimes->getStudentId () . date ( "Ymd", strtotime ( $list_logtimes->getLtLoginAt () ) ) . $list_logtimes->getLogValue () );
			if ($list_logtimes->getLogValue () == 1) {
				array_push ( $array_goschool, date ( "Ymd", strtotime ( $list_logtimes->getLtLoginAt () ) ) . $list_logtimes->getLogValue () );
			}else{
				array_push ( $array_notgoschool, date ( "Ymd", strtotime ( $list_logtimes->getLtLoginAt () ) ) );
			}
		}

		foreach ( $filter_list_student as $ky => $list_student ) {
			
			$index ++;

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $ky + 1 );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $list_student->getStudentCode () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $list_student->getFullName () );

			$x = 'x';
			$ko = 'k';
			$p = 'p';
			// viet ham moi
			$index2 = 0;
			for($k = 1; $k <= $number_day ['number_day_month']; $k ++) {

				$col = PHPExcel_Cell::stringFromColumnIndex ( 3 + $index2 );
				$row = $start_row;

				if (in_array ( $list_student->getSId () . date ( "Ymd", strtotime ( $k . '-' . $month_year ) ) . '1.00', $array_logtime )) {
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( $col . $row, $x );
					$c ++;
				} elseif (in_array ( $list_student->getSId () . date ( "Ymd", strtotime ( $k . '-' . $month_year ) ) . '0.00', $array_logtime )) {
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( $col . $row, $p );
					$a ++;
				} elseif (in_array ( $list_student->getSId () . date ( "Ymd", strtotime ( $k . '-' . $month_year ) ) . '2.00', $array_logtime )) {
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( $col . $row, $ko );
					$b ++;
				}

				if (in_array ( $k, $sunday )) {
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( $col . $row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'a90329' ) ) );
				}
				if (in_array ( $k, $saturday )) {
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( $col . $row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'c79121' ) ) );
				}

				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getAlignment ()
					->setWrapText ( true );
				$index2 ++;
			}
			if($list_student->getMyclassMode() == 1){ // Nếu học sinh này đi học cả t7
				$h = ($c/$songayhocthu7)*100;
			}else{ // Nếu không đi học thứ 7
				$h = ($c/$songaykohocthu7)*100;
			}
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'AI' . $start_row, $a );
			$a = '';
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'AJ' . $start_row, $b  );
			$b = '';
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'AK' . $start_row, $c );
			$c = '';
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'AL' . $start_row, $h );
			$h = '';
			
			$start_row = $start_row + 1;
			if ($index <= $number)
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
		}

		$index3 = 0;
		for($k = 1; $k <= $number_day ['number_day_month']; $k ++) {

			$col = PHPExcel_Cell::stringFromColumnIndex ( 3 + $index3 );
			
			$row2 = $start_row;
			$row3 = $start_row + 2;
			$row = $start_row + 1;

			foreach ( $array_goschool as $list ) {
				if ($list == date ( "Ymd", strtotime ( $k . '-' . $month_year ) ) . '1') {
					$e ++;
				}
			}
			foreach ( $array_notgoschool as $list2 ) {
				if ($list2 == date ( "Ymd", strtotime ( $k . '-' . $month_year ) )) {
					$f ++;
				}
			}
			
			$g = ($e/$number)*100;
			
			if (in_array ( $k, $sunday )) {
				$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getFill ()
				->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
				
				$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row2 )
				->getFill ()
				->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}
			
			if (in_array ( $k, $saturday )) {

				$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getFill ()
				->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
				$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row2 )
				->getFill ()
				->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
			}
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( $col . $row2, $f );
			$f = '';
			
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col . $row, $e );
			$e = '';
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( $col . $row3, $g );
			$g = '';
			
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getAlignment ()
				->setWrapText ( true );
			$index3 ++;
		}

		$start_row = $start_row + 3;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'AF' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );
	}

	public function setDataExportBirthdayInfoExport($school_name, $title_info, $title_xls) {

		// echo $school_name->getTitle(); die();
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
				->setCellValue ( 'B1', $school_name->getTitle () );

			$address = $this->object->getContext ()
				->getI18N ()
				->__ ( 'Tel2' );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B2', $school_name->getAddress () . '-' . $address . ': ' . ($school_name->getTel () != '' ? $school_name->getTel () : $school_name->getMobile ()) );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A3', $title_info );

			$this->objPHPExcel->getActiveSheet ()
				->setTitle ( $title_xls );
		}
	}

	/**
	 * Danh sach sinh nhat
	 */
	public function setDataExportStatisticBirthday($relatives_list, $students_list, $teachers_list, $students) {

		$day = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' );
		$month = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Month' );
		$year = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Year' );

		$start_row = 6;
		$index = 1;

		if (count ( $students_list ) > 0) {

			foreach ( $students_list as $student ) {
				$index ++;

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $student->getStudentCode () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'B' . $start_row, $student->getFirstName () . ' ' . $student->getLastName () );

				$birthdayfm = $student->getBirthday ();
				$birthday = PsDateHelper::format_date ( $birthdayfm );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'C' . $start_row, $this->object->getContext ()
					->getI18N ()
					->__ ( $birthday ) );

				$sex = $student->getSex ();
				$student_sex = PreSchool::getGender ();
				if (isset ( $student_sex [$sex] ))
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'D' . $start_row, $this->object->getContext ()
						->getI18N ()
						->__ ( $student_sex [$sex] ) );

				$is_status = $student->getIsActivated ();
				if ($is_status == PreSchool::ACTIVE) {
					$status = PreSchool::loadStatusStudentClass ();
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'E' . $start_row, $this->object->getContext ()
						->getI18N ()
						->__ ( $status [$student->getStudentType ()] ) );
				} else {
					$status = PreSchool::loadPsActivity ();
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'E' . $start_row, $this->object->getContext ()
						->getI18N ()
						->__ ( $status [$student->getIsActivated ()] ) );
				}

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, $student->getMcName () );

				$start_row = $start_row + 1;
				if ($index <= count ( $students_list ))
					$this->objPHPExcel->getActiveSheet ()
						->insertNewRowBefore ( $start_row, 1 );
			}
		} else {
			$start_row = $start_row + 1;
		}

		if (count ( $teachers_list ) > 0) {

			$start_row = $start_row + 1;
			$index = 1;

			foreach ( $teachers_list as $teacher ) {

				$index ++;

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $teacher->getMemberCode () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'B' . $start_row, $teacher->getFirstName () . ' ' . $teacher->getLastName () );

				$birthdayfm = $teacher->getBirthday ();
				$birthday = PsDateHelper::format_date ( $birthdayfm );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'C' . $start_row, $this->object->getContext ()
					->getI18N ()
					->__ ( $birthday ) );

				$sex = $teacher->getSex ();
				$student_sex = PreSchool::getGender ();
				if (isset ( $student_sex [$sex] ))
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'D' . $start_row, $this->object->getContext ()
						->getI18N ()
						->__ ( $student_sex [$sex] ) );

				$start_row = $start_row + 1;
				if ($index <= count ( $teachers_list ))
					$this->objPHPExcel->getActiveSheet ()
						->insertNewRowBefore ( $start_row, 1 );
			}
		} else {
			$start_row = $start_row + 2;
		}

		if (count ( $relatives_list ) > 0) {

			$start_row = $start_row + 1;
			$index = 1;

			foreach ( $relatives_list as $relative ) {

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $relative->getFirstName () . ' ' . $relative->getLastName () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'B' . $start_row, $relative->getMobile () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'C' . $start_row, $relative->getEmail () );

				$birthdayfm = $relative->getBirthday ();
				$birthday = PsDateHelper::format_date ( $birthdayfm );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'D' . $start_row, $this->object->getContext ()
					->getI18N ()
					->__ ( $birthday ) );

				$sex = $relative->getSex ();
				$student_sex = PreSchool::getGender ();
				if (isset ( $student_sex [$sex] ))
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'E' . $start_row, $this->object->getContext ()
						->getI18N ()
						->__ ( $student_sex [$sex] ) );

				foreach ( $students as $key => $student ) {

					if ($relative->getMemberId () == $key) {
						foreach ( $student as $s ) {
							$str_student .= $s . ', ';
						}
					}
				}

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, $str_student );

				$start_row = $start_row + 1;
				if ($index <= count ( $relatives_list ))
					$this->objPHPExcel->getActiveSheet ()
						->insertNewRowBefore ( $start_row, 1 );
			}
		}

		$start_row = $start_row + 2;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );
	}

	/**
	 * Thong ke tong hop cua co so trong ngay
	 * setDataExportSyntheticStatisticDay
	 */
	public function setDataExportSyntheticStatisticDay($my_class, $filter_list_logtime, $feture_branch, $list_feture_branch,$list_member,$ps_album,$ps_album_items,$ps_notications,$ps_cms_articles) {

		$day = $this->object->getContext ()	->getI18N ()->__ ( 'Day' );
		$month = $this->object->getContext ()->getI18N ()->__ ( 'Month' );
		$year = $this->object->getContext ()->getI18N ()->__ ( 'Year' );
			
		$write_comment = $this->object->getContext ()->getI18N ()->__ ( 'Write comment' );

		$array_branch = array ();
		foreach ( $feture_branch as $branch ) {
			$array_branch [$branch->getId ()] = $branch->getTitle ();
		}
		$array_list = array ();
		foreach ( $list_feture_branch as $list_feture ) {
			$array_list [$list_feture->getPsClassId () . $list_feture->getFeatureId ()] = $list_feture->getFeatureSum ().'_'.$list_feture->getNoteSum ();
		}

		$array_album_number = array();
		foreach ($ps_album as $album){
			$array_album_number[$album->getId()] = $album->getPsClassId();
		}
		$array_image_number = array();
		foreach ($ps_album_items as $album_items){
			$array_image_number[$album_items->getId()] = $album_items->getPsClassId();
		}
		
		$array_data_album = (array_count_values($array_album_number));
		$array_data_image = (array_count_values($array_image_number));
		
		$count_branch = count ( $array_branch );

		$start = 5;
		$index = 0;

		foreach ( $array_branch as $key => $branch ) {
			$col = PHPExcel_Cell::stringFromColumnIndex ( 8 + $index );
			$row = $start;
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col . $row, $branch );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getAlignment ()
				->setWrapText ( true );
			$index ++;
		}

		$start_row = 6;
		$index = 0;
		$number_class = count ( $my_class );

		foreach ( $my_class as $ky => $class ) {
			$class_id = $class->getMcId ();
			$number_articles = $number_notication = $a = $b = 0;
			//$list_member = Doctrine::getTable ( 'PsMember' )->getAllTeachersInClass ( $class_id );
			foreach ( $filter_list_logtime as $list_class ) {
				if ($list_class->getPsClassId () == $class->getMcId ()) {
					$a = $list_class->getLoginSum ();
					$b = $list_class->getLogoutSum ();
				}
			}

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $class->getName () . ' ( ' . $class->getNumberStudentActivitie () . ' H/s ) ' );

			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $start_row, $array_data_album[$class_id] );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $start_row, $array_data_image[$class_id] );
			foreach ($ps_notications as $notications){
				if($notications->getPsClassId() == '' || $notications->getPsClassId() == $class_id){
					$number_notication ++;
				}
			}
			foreach ($ps_cms_articles as $articles){
				if($articles->getPsClassId() == '' || $articles->getPsClassId() == $class_id){
					$number_articles ++;
				}
			}
			
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $start_row, $number_notication );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $start_row, $number_articles );
			
				
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $start_row, $a );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $start_row, $b );
			
			$index2 = 0;

			foreach ( $array_branch as $key => $branch ) {

				$col = PHPExcel_Cell::stringFromColumnIndex ( 8 + $index2 );

				$row = $start_row;

				$c = $d = '';
				$check_data = $class->getMcId () . $key;
				
				if(isset($array_list[$check_data])){
					$array_chuoi = explode('_', $array_list[$check_data]);
					
					$c = $array_chuoi['0'];
					$d = $array_chuoi['1'];
					unset($array_list[$check_data]);
					
				}
				if($d > 0){
					$comment = $c.' | '.$d.$write_comment;
				}else{
					$comment = $c;
				}
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( $col . $row, $comment );
				$this->objPHPExcel->getActiveSheet () ->getStyle ( $col . $row ) ->getAlignment () ->setWrapText ( true );
				$index2 ++;
			}

			$i = 0;

			foreach ( $list_member as $member ) {
				
				if($member->getMyclassId() == $class_id){
					
					if ($i > 0) {
						$curent_row = $start_row;
						$start_row = $start_row + 1;
						$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
						$this->objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $curent_row . ':A' . $start_row );
						$this->objPHPExcel->getActiveSheet ()->mergeCells ( 'C' . $curent_row . ':C' . $start_row );
						$this->objPHPExcel->getActiveSheet ()->mergeCells ( 'D' . $curent_row . ':D' . $start_row );
						$this->objPHPExcel->getActiveSheet ()->mergeCells ( 'E' . $curent_row . ':E' . $start_row );
						$this->objPHPExcel->getActiveSheet ()->mergeCells ( 'F' . $curent_row . ':F' . $start_row );
						$this->objPHPExcel->getActiveSheet ()->mergeCells ( 'G' . $curent_row . ':G' . $start_row );
						$this->objPHPExcel->getActiveSheet ()->mergeCells ( 'H' . $curent_row . ':H' . $start_row );
						$index3 = 0;
						for($k = 0; $k < $count_branch; $k ++) {
							$col = PHPExcel_Cell::stringFromColumnIndex ( 4 + $index3 );
							$row = $start_row;
							$this->objPHPExcel->getActiveSheet ()
								->mergeCells ( $col . $curent_row . ':' . $col . $start_row );
							$this->objPHPExcel->getActiveSheet ()
								->getStyle ( $col . $row )
								->getAlignment ()
								->setWrapText ( true );
							$index3 ++;
						}
					}
					
					$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $start_row, $member->getTitle () );
					
					$i = $i + 1;
				}
			}

			$start_row = $start_row + 1;
			if ($index <= $number_class)
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
		}

		$start_row = $start_row + 2;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );
	}

	/**
	 * Thong ke tong hop cua lop trong thang
	 * setDataExportSyntheticStatistic
	 */
	public function setDataExportSyntheticStatistic($filter_list_logtime, $list_feture_branch, $feture_branch, $class_name, $number_day, $ps_month,$ps_album,$ps_album_items,$ps_notications,$ps_cms_articles) {
		
		$day = $this->object->getContext ()->getI18N ()->__ ( 'Day' );
		$month = $this->object->getContext ()->getI18N ()->__ ( 'Month' );
		$year = $this->object->getContext ()->getI18N ()->__ ( 'Year' );

		$sunday = PsDateTime::psSundaysOfMonth ( $ps_month );
		$saturday = PsDateTime::psSaturdaysOfMonth ( $ps_month );

		$array_goschool = array ();
		$array_outschool = array ();
		foreach ( $filter_list_logtime as $list_logtime ) {
			$array_goschool [$list_logtime->getPsClassId () . date ( "Ymd", strtotime ( $list_logtime->getTrackedAt () ) )] = $list_logtime->getLoginSum ();
			$array_outschool [$list_logtime->getPsClassId () . date ( "Ymd", strtotime ( $list_logtime->getTrackedAt () ) )] = $list_logtime->getLogoutSum ();
		}
		$array_list = array ();
		foreach ( $list_feture_branch as $list_fetures ) {
			$array_list [$list_fetures->getPsClassId () . $list_fetures->getFeatureId () . date ( "Ymd", strtotime ( $list_fetures->getTrackedAt () ) )] = $list_fetures->getFeatureSum ();
		}

		$class_id = $class_name->getId ();
		$name_class = $class_name->getClName () . ' ( ' . $total = Doctrine::getTable ( 'StudentClass' )->getNumberStudentActivitie ( $class_id ) . ' H/s )';
		
		$array_album_number = array();
		foreach ($ps_album as $album){
			$array_album_number[$album->getId()] = date('Ymd',strtotime($album->getCreatedAt()));
		}
		
		$array_image_number = array();
		foreach ($ps_album_items as $album_items){
			$array_image_number[$album_items->getId()] = date('Ymd',strtotime($album_items->getCreatedAt()));
		}
		$array_notication_number = array();
		foreach ($ps_notication as $notication){
			$array_notication_number[$notication->getId()] = date('Ymd',strtotime($notication->getUpdatedAt()));
		}
		$array_article_number = array();
		foreach ($ps_cms_articles as $articles){
			$array_article_number[$articles->getId()] = date('Ymd',strtotime($articles->getUpdatedAt()));
		}
		
		$array_data_album = (array_count_values($array_album_number));
		$array_data_image = (array_count_values($array_image_number));
		$array_data_notication = (array_count_values($array_notication_number));
		$array_data_articles = (array_count_values($array_article_number));
		
		$start = 5;
		$index = 0;
		
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start, $name_class );

		for($k = 1; $k <= $number_day ['number_day_month']; $k ++) {
			$col = PHPExcel_Cell::stringFromColumnIndex ( 1 + $index );
			$row = $start;
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col . $row, $k );
			if (in_array ( $k, $sunday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}
			if (in_array ( $k, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
			}

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getAlignment ()
				->setWrapText ( true );
			$index ++;
		}
		
		$start_in = 6;
		$index = 0;
		for($k = 1; $k <= $number_day ['number_day_month']; $k ++) {
			
			$a = '';
			
			if (date ( "Ymd", strtotime ( $k . '-' . $ps_month ) ) <= date ( "Ymd" )) {
				$col = PHPExcel_Cell::stringFromColumnIndex ( 1 + $index );
				$row = $start_in;
				
				if(isset($array_goschool[$class_id . date ( "Ymd", strtotime ( $k . '-' . $ps_month ) )])){
					$a = $array_goschool[$class_id . date ( "Ymd", strtotime ( $k . '-' . $ps_month ) )];
				}
				if (in_array ( $k, $sunday )) {
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( $col . $row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'a90329' ) ) );
				}
				if (in_array ( $k, $saturday )) {
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( $col . $row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'c79121' ) ) );
				}

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col . $row, $a );
				
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getAlignment ()
					->setWrapText ( true );
				$index ++;
			}
		}

		$start_out = 7;
		$index = 0;
		for($k = 1; $k <= $number_day ['number_day_month']; $k ++) {
			$b = '';
			if (date ( "Ymd", strtotime ( $k . '-' . $ps_month ) ) <= date ( "Ymd" )) {
				$col = PHPExcel_Cell::stringFromColumnIndex ( 1 + $index );
				$row = $start_out;
				
				if(isset($array_outschool[$class_id . date ( "Ymd", strtotime ( $k . '-' . $ps_month ) )])){
					$b = $array_outschool[$class_id . date ( "Ymd", strtotime ( $k . '-' . $ps_month ) )];
				}
				
				if (in_array ( $k, $sunday )) {
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( $col . $row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'a90329' ) ) );
				}
				if (in_array ( $k, $saturday )) {
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( $col . $row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'c79121' ) ) );
				}

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col . $row, $b );
				
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getAlignment ()
					->setWrapText ( true );
				$index ++;
			}
		}
		
		$start_album = 8;
		$index = 0;
		for($k = 1; $k <= $number_day ['number_day_month']; $k ++) {
			$number_album = '';
			if (date ( "Ymd", strtotime ( $k . '-' . $ps_month ) ) <= date ( "Ymd" )) {
				$col = PHPExcel_Cell::stringFromColumnIndex ( 1 + $index );
				$row = $start_album;
				
				$number_album = $array_data_album[date("Ymd", strtotime($k.'-'.$ps_month))];
				
				if (in_array ( $k, $sunday )) {
					$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getFill ()
					->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'a90329' ) ) );
				}
				if (in_array ( $k, $saturday )) {
					$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getFill ()
					->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'c79121' ) ) );
				}
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row, $number_album );
				$this->objPHPExcel->getActiveSheet ()->getStyle ( $col . $row )->getAlignment ()->setWrapText ( true );
				$index ++;
			}
		}
		
		$start_image = 9;
		$index = 0;
		for($k = 1; $k <= $number_day ['number_day_month']; $k ++) {
			$number_image = '';
			if (date ( "Ymd", strtotime ( $k . '-' . $ps_month ) ) <= date ( "Ymd" )) {
				$col = PHPExcel_Cell::stringFromColumnIndex ( 1 + $index );
				$row = $start_image;
				
				$number_image = $array_data_image[date("Ymd", strtotime($k.'-'.$ps_month))];
				
				if (in_array ( $k, $sunday )) {
					$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getFill ()
					->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'a90329' ) ) );
				}
				if (in_array ( $k, $saturday )) {
					$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getFill ()
					->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'c79121' ) ) );
				}
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row, $number_image );
				$this->objPHPExcel->getActiveSheet ()->getStyle ( $col . $row )->getAlignment ()->setWrapText ( true );
				$index ++;
			}
		}
		
		$start_notication = 10;
		$index = 0;
		for($k = 1; $k <= $number_day ['number_day_month']; $k ++) {
			$number_notication = '';
			if (date ( "Ymd", strtotime ( $k . '-' . $ps_month ) ) <= date ( "Ymd" )) {
				$col = PHPExcel_Cell::stringFromColumnIndex ( 1 + $index );
				$row = $start_notication;
				
				$number_notication = $array_data_notication[date("Ymd", strtotime($k.'-'.$ps_month))];
				
				if (in_array ( $k, $sunday )) {
					$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getFill ()
					->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'a90329' ) ) );
				}
				if (in_array ( $k, $saturday )) {
					$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getFill ()
					->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'c79121' ) ) );
				}
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row, $number_notication );
				$this->objPHPExcel->getActiveSheet ()->getStyle ( $col . $row )->getAlignment ()->setWrapText ( true );
				$index ++;
			}
		}
		
		$start_article = 11;
		$index = 0;
		for($k = 1; $k <= $number_day ['number_day_month']; $k ++) {
			$number_article = '';
			if (date ( "Ymd", strtotime ( $k . '-' . $ps_month ) ) <= date ( "Ymd" )) {
				$col = PHPExcel_Cell::stringFromColumnIndex ( 1 + $index );
				$row = $start_article;
				
				$number_article = $array_data_articles[date("Ymd", strtotime($k.'-'.$ps_month))];
				
				if (in_array ( $k, $sunday )) {
					$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getFill ()
					->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'a90329' ) ) );
				}
				if (in_array ( $k, $saturday )) {
					$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getFill ()
					->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'c79121' ) ) );
				}
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row, $number_article );
				$this->objPHPExcel->getActiveSheet ()->getStyle ( $col . $row )->getAlignment ()->setWrapText ( true );
				$index ++;
			}
		}
		
		$start_row = 13;
		$index2 = 1;
		$number_branch = count ( $feture_branch );

		foreach ( $feture_branch as $branch ) {
			$index2 ++;

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $branch->getName () );

			$index = 0;

			for($k = 1; $k <= $number_day ['number_day_month']; $k ++) {

				$col = PHPExcel_Cell::stringFromColumnIndex ( 1 + $index );
				$row = $start_row;

				if (in_array ( $k, $sunday )) {
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( $col . $row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'a90329' ) ) );
				}
				if (in_array ( $k, $saturday )) {
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( $col . $row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'c79121' ) ) );
				}
				if (date ( "Ymd", strtotime ( $k . '-' . $ps_month ) ) <= date ( "Ymd" )) {
					$c = '';
					$check = $class_id . $branch->getFbId () . date ( "Ymd", strtotime ( $k . '-' . $ps_month ) );
					/*foreach ( $array_list as $khoa => $feature_sum ) {
						if ($check == $khoa) {
							$c = $feature_sum;
							break;
						}
					}*/
					if(isset($array_list[$check])){
						$c = $array_list[$check];
					}
					
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( $col . $row, $c );
				}
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getAlignment ()
					->setWrapText ( true );
				$index ++;
			}
			$start_row = $start_row + 1;
			if ($index2 <= $number_branch) {
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			}
		}

		$start_row = $start_row + 2;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'W' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );
	}

	/**
	 * Xuat ra danh sach hoc sinh trong lop
	 * setDataExportFeeReceiptStudent
	 */
	public function setDataExportFeeReceiptStudent($students) {

		// $start = 3;
		// $this->objPHPExcel->getActiveSheet()->setCellValue('A'.$start, $title_class);
		$start_row = 6;
		$index = 0;
		$number_student = count ( $students );

		foreach ( $students as $key => $student ) {
			
			if($student->getCommonName () != ''){
				$tenhocsinh = $student->getStudentName () . ' ('.$student->getCommonName ().')';
			}else{
				$tenhocsinh = $student->getStudentName ();
			}
			
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $key + 1 );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $tenhocsinh );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $student->getStudentCode () );

			$start_row = $start_row + 1;
			if ($index <= $number_student)
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
		}
	}

	/**
	 * Xuat ra danh sach hoc sinh trong lop de import diem danh
	 * setDataExportAttendanceStudent
	 */
	public function setDataExportAttendanceStudent($students, $list_service, $ps_month) {

		$number_day = PsDateTime::psNumberDaysOfMonth ( $ps_month );
		$saturday = PsDateTime::psSaturdaysOfMonth ( $ps_month );
		$sunday = PsDateTime::psSundaysOfMonth ( $ps_month );

		$start = 5;
		$index = 0;

		for($k = 1; $k <= $number_day ['number_day_month']; $k ++) {
			$col = PHPExcel_Cell::stringFromColumnIndex ( 3 + $index );
			$row = $start;
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col . $row, $k );
			if (in_array ( $k, $sunday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'a90329' ) ) );
			}
			if (in_array ( $k, $saturday )) {
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getFill ()
					->applyFromArray ( array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array (
								'rgb' => 'c79121' ) ) );
			}

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getAlignment ()
				->setWrapText ( true );
			$index ++;
		}

		$start_service = 4;
		$index = 0;

		foreach ( $list_service as $service ) {

			$col = PHPExcel_Cell::stringFromColumnIndex ( 37 + $index );

			$row = $start_service;

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col . $row, $service->getTitle () );

			$row = $start_service + 1;

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col . $row, $service->getId () );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getAlignment ()
				->setWrapText ( true );
			$index ++;
		}

		$start_row = 6;
		$index = 0;
		$number_student = count ( $students );

		foreach ( $students as $key => $student ) {
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $key + 1 );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $student->getStudentCode () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $student->getStudentName () );

			$index2 = 0;
			for($k = 1; $k <= $number_day ['number_day_month']; $k ++) {

				$col = PHPExcel_Cell::stringFromColumnIndex ( 3 + $index2 );
				$row = $start_row;

				if (in_array ( $k, $sunday )) {
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( $col . $row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'a90329' ) ) );
				}
				if (in_array ( $k, $saturday )) {
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( $col . $row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'c79121' ) ) );
				}

				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getAlignment ()
					->setWrapText ( true );
				$index2 ++;
			}

			$start_row = $start_row + 1;
			if ($index <= $number_student)
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
		}
	}

	/**
	 * Xuat ra danh sach hoc sinh co phieu thu va trang thai phieu thu
	 * setDataExportReceiptStudentStatistic
	 */
	public function setDataExportReceiptStudentStatistic($school_name, $title_info, $title_xls, $list_student) {

		$day = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' );
		$month = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Month' );
		$year = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Year' );

		// echo $school_name->getTitle(); die();
		if ($school_name != null) {
			// Ve Logo
			if ($school_name->getLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $school_name->getYearData () . '/' . $school_name->getLogo () )) {

				$objDrawing = new PHPExcel_Worksheet_Drawing ();

				$objDrawing->setName ( 'Logo' );

				$objDrawing->setDescription ( $school_name->getTitle () );

				$objDrawing->setPath ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $school_name->getYearData () . '/' . $school_name->getLogo () );

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

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C1', $school_name->getTitle () );

			$address = $this->object->getContext ()
				->getI18N ()
				->__ ( 'Tel2' );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C2', $school_name->getAddress () . '-' . $address . ': ' . ($school_name->getTel () != '' ? $school_name->getTel () : '') );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A3', $title_info );

			$this->objPHPExcel->getActiveSheet ()
				->setTitle ( $title_xls );
		}

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G4', $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );

		$start_row = 6;
		$index = 0;
		$number_student = count ( $list_student );

		foreach ( $list_student as $key => $student ) {

			$index ++;

			if ($student->getPaymentDate () != '') {
				$payment_date = date ( 'd-m-Y', strtotime ( $student->getPaymentDate () ) );
			} else {
				$payment_date = '';
			}

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $key + 1 );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $student->getStudentCode () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $student->getStudentName () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $student->getReceiptNo () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, $student->getReceivable () + $student->getLatePaymentAmount () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $student->getCollectedAmount () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $payment_date );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . $start_row, $student->getCashierName () );

			$start_row = $start_row + 1;
			if ($index <= $number_student)
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
		}

		$start_row = $start_row + 2;
	}

	/**
	 * Xuat ra danh sach hoc sinh co phieu thu va trang thai phieu thu de import du dau ky
	 * setDataExportReceiptBalanceLastMonth
	 */
	public function setDataExportReceiptBalanceLastMonth($school_name, $title_info, $title_xls, $list_student) {

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
				->setCellValue ( 'C1', $school_name->getTitle () );

			$address = $this->object->getContext ()
				->getI18N ()
				->__ ( 'Tel2' );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C2', $school_name->getAddress () . '-' . $address . ': ' . ($school_name->getTel () != '' ? $school_name->getTel () : '') );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A3', $title_info );

			$this->objPHPExcel->getActiveSheet ()
				->setTitle ( $title_xls );
		}

		$start_row = 6;
		$index = 0;
		$number_student = count ( $list_student );

		foreach ( $list_student as $key => $student ) {

			$index ++;

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $key + 1 );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $student->getStudentCode () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $student->getStudentName () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $student->getReceiptNo () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, $student->getBalanceLastMonthAmount () );

			$start_row = $start_row + 1;
			if ($index <= $number_student)
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
		}

		$start_row = $start_row + 1;
	}

	/**
	 * Xuat ra danh sach bua an
	 * setDataExportReceiptBalanceLastMonth
	 */
	public function setDataExportMeals($ps_meals) {
		
		$start_row = 2;
		$index = 0;
		
		foreach ( $ps_meals as $meals ) {
			
			$row = $start_row;
			
			$row2 = $row+1;
			
			$col1 = PHPExcel_Cell::stringFromColumnIndex ( 1 + $index );
			
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col1 . $row, $meals->getTitle () );
			
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col1 . $row2, $meals->getId () );
			
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( $col1 . $row )
			->getAlignment ()
			->setWrapText ( true );
			$index ++;
			
		}
		
	}
	

	/**
	 * Xuat ra bieu mau import lịch hoat dong
	 * setDataExportDuLieuKeHoachGiaoDuc
	 */
	public function setDataExportDuLieuKeHoachGiaoDuc($list_class,$list_feature_branch,$from_date,$to_date,$ps_month) {
		
		$start = 5;
		$index = 0;
		
		$number_class = count($list_class);
		$number_branch = count($list_feature_branch);
		foreach ( $list_class as $class ) {
			
			$row = $start;
			
			$row2 = $row+1;
			
			$col1 = PHPExcel_Cell::stringFromColumnIndex ( 2 + $index );
			
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col1 . $row, $class->getTitle () );
			
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col1 . $row2, $class->getId () );
			
			$index ++;
			
			$col2 = PHPExcel_Cell::stringFromColumnIndex ( 2 + $index );
			
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( $col1 . $row . ':' . $col2.$row );
			
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( $col1 . $row2 . ':' . $col2.$row2 );
			
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( $col1 . $row )
			->getAlignment ()
			->setWrapText ( true );
			$index ++;
			
		}
		
		$start_row = 7;
		
		for ($i = $from_date; $i <= $to_date; $i++){
			
			$curent_row = $start_row + 1;
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $i.'-'.$ps_month );
			$index2 = 0;
			foreach ( $list_feature_branch as $feature_branch ) {
				$index2 ++;
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $feature_branch->getId() );
				
				$start_row ++;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $feature_branch->getTitle() );
				
				for ($j=0; $j< $number_class*2 ;$j++){
					
					//$index1 = $j ;
					$col1 = PHPExcel_Cell::stringFromColumnIndex ( 2 + $j );
					$j ++;
					$col2 = PHPExcel_Cell::stringFromColumnIndex ( 2 + $j );
					
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( $col1.$start_row, 'noi dung hd' );
					$this->objPHPExcel->getActiveSheet ()->mergeCells ( $col1.$start_row . ':'.$col2.$start_row );
				
				}
				if($index2 < $number_branch){
					$start_row ++;
					$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				}
			}
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $curent_row, $this->object->getContext ()
					->getI18N ()
					->__ (date('l',strtotime($i.'-'.$ps_month)) ));
			
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $curent_row . ':A' . $start_row );
			
			if($i != $to_date){
				$start_row ++;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			}
		}
		
	}
	
	/**
	 * Xuat ra bieu mau import lịch hoat dong mau 3
	 * setDataExportDuLieuKeHoachGiaoDucTem3
	 */
	public function setDataExportDuLieuKeHoachGiaoDucTem3($list_feature_branch,$dayofweek,$list_class) {
		
		$start = 6;
		$index = 0;
		
		foreach ( $dayofweek as $key=>$day ) {
			
			if($index < 6){
				$row = $start;
				
				$col = PHPExcel_Cell::stringFromColumnIndex ( 2 + $index );
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row, date('d-m-Y',strtotime($key)));
				
				$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getAlignment ()
				->setWrapText ( true );
				$index ++;
			}
		}
		
		$start_row = 7;
		
		foreach ($list_feature_branch as $feature_branch){
			
			$curent_row = $start_row;
			
			$title = $feature_branch->getTitle();
			
			$branch_id = $feature_branch->getId();
			
			$this->objPHPExcel->getActiveSheet()->getRowDimension($start_row)->setRowHeight(25);
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'C' . $start_row, $title );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . $start_row, $title );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $title );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . $start_row, $title );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, $title );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, $title );
			
			$start_row ++;
			
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'C' . $start_row, $branch_id );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . $start_row, $branch_id );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $branch_id );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . $start_row, $branch_id );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, $branch_id );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, $branch_id );
			
			$start_row ++;
			
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet()->getRowDimension($start_row)->setRowHeight(50);
			
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'A' . $curent_row . ':A' . $start_row );
			
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( 'B' . $curent_row . ':B' . $start_row );
			
			$start_row ++;
			
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			
		}
		
		$start_class = 7;
		foreach ($list_class as $class){
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'I' . $start_class, $class->getTitle() );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'J' . $start_class, $class->getId() );
			$start_class ++;
			
		}
		
		$this->objPHPExcel->getActiveSheet()->removeRow($start_row, 1);
		
	}
	/**
	 * Xuat ra bieu mau import lịch hoat dong mau 3
	 * setDataExportDuLieuKeHoachGiaoDucTem3
	 */
	public function setDataExportThoiKhoaBieuTuan($week_list, $list_menu,$list_hour) {
		
		$start = 5;
		$index = 0;
		
		foreach ( $week_list as $key=>$day ) {
			
			if($index < 6){
				$row = $start;
				
				$col = PHPExcel_Cell::stringFromColumnIndex ( 1 + $index );
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row, date('d-m-Y',strtotime($key)));
				
				$this->objPHPExcel->getActiveSheet () ->getStyle ( $col . $row ) ->getAlignment () ->setWrapText ( true );
				$index ++;
			}
		}
		
		$start_row = 6;
		$num_jump = 1;
		
		foreach ($list_hour as $hour){
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row,( date('H:i',strtotime($hour->getStartTime())).'-'.date('H:i',strtotime($hour->getEndTime())) ));
			$index1 = 0;
			$curent_row = $start_row;
			$num_row = 0;
			
			foreach ($week_list as $date => $monday){
				if($index1 < 6){
					$row2 = $start_row;
					
					foreach ($list_menu as $key => $fbtimes){
						
						if (strtotime($date) >= strtotime($fbtimes->getStartAt ()) && strtotime($date) <= strtotime($fbtimes->getEndAt ()) && $hour->getStartTime() == $fbtimes->getStartTime()) {
							
							$col2 = PHPExcel_Cell::stringFromColumnIndex ( 1 + $index1 );
							
							$num_row ++;
							
							if ($fbtimes->getIsSaturday () == 0 && date ( 'N', strtotime ( $date ) ) == 6) :
							continue;
							elseif ($fbtimes->getIsSunday () == 0 && date ( 'N', strtotime ( $date ) ) == 7) :
							continue;
							endif;
							
							$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col2 . $row2, $fbtimes->getFbName());
							
							$row2 ++;
							
							if($row2 > $num_jump){
								$num_jump = $row2;
							}
							
						}
						
					}
					$index1++;
				}
				
			}
			$start_row = $num_jump;
			$end_row = $start_row - 1;
			$this->objPHPExcel->getActiveSheet ()->mergeCells ( 'A'.$curent_row . ':A'.($end_row));
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			
		}
		//die;
		$this->objPHPExcel->getActiveSheet()->removeRow($start_row, count($list_hour));
		
		$this->objPHPExcel->getActiveSheet()->removeRow($start_row, 1);
		
	}
}