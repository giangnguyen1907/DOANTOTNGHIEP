<?php
class ExportStudentReportsHelper extends ExportHelper {

	protected $sheet_index = 0;

	public function __construct($object) {

		parent::__construct ($object);

		$this->object = $object;
	}
	
	public function setActiveSheetIndex($index = 0) {
		
		$this->objPHPExcel->setActiveSheetIndex ( $index );
	}
	
	/**
	 * Clone sheet
	 */
	public function createNewSheetId($sheet_index = 0) {
		
		$objWorkSheet1 = clone $this->objPHPExcel->getSheet ();
		
		$objWorkSheet1->setTitle ( 'Cloned Sheet' );
		
		$this->objPHPExcel->addSheet ( $objWorkSheet1 );
		
		$sheet_index = $sheet_index + 1;
		
		$this->objPHPExcel->setActiveSheetIndex ( $sheet_index );
	}
	
	/**
	 * Remove sheet
	 */
	public function removeSheetId($sheet_index = 0) {
		
		$this->objPHPExcel->removeSheetByIndex ( $sheet_index );
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
	 * Tao tieu de cho tong hop khoan thu
	 */
	public function setDataExportReceivableStatisticInfoExport($school_name, $title_info, $title_xls) {

		if ($school_name != null) {
			// Ve Logo
			if ($school_name->getLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $school_name->getYearData () . '/' . $school_name->getLogo () )) {

				$objDrawing = new PHPExcel_Worksheet_Drawing ();

				$objDrawing->setName ( 'Logo' );

				$objDrawing->setDescription ( $school_name->getTitle () );

				$objDrawing->setPath ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $school_name->getYearData () . '/' . $school_name->getLogo () );

				$objDrawing->setOffsetY ( 5 );
				$objDrawing->setOffsetX ( 5 );
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
				->setCellValue ( 'C2', $school_name->getAddress () . '-' . $address . ': ' . ($school_name->getTel () != '' ? $school_name->getTel () : $school_name->getMobile ()) );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A3', $title_info );

			$this->objPHPExcel->getActiveSheet ()
				->setTitle ( $title_xls );
		}
	}

	/**
	 * Tao tieu de cho bao cao bang tong hop cong no
	 */
	public function setDataExportStatisticInfoExport($school_name, $title_info, $title_xls) {

		$day = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' );
		$month = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Month' );
		$year = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Year' );

		if ($school_name != null) {
			// Ve Logo
			if ($school_name->getLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $school_name->getYearData () . '/' . $school_name->getLogo () )) {

				$objDrawing = new PHPExcel_Worksheet_Drawing ();

				$objDrawing->setName ( 'Logo' );

				$objDrawing->setDescription ( $school_name->getTitle () );

				$objDrawing->setPath ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $school_name->getYearData () . '/' . $school_name->getLogo () );

				$objDrawing->setOffsetY ( 5 );
				$objDrawing->setOffsetX ( 5 );
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
				->setCellValue ( 'C2', $school_name->getAddress () . '-' . $address . ': ' . ($school_name->getTel () != '' ? $school_name->getTel () : $school_name->getMobile ()) );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C3', $title_info );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C4', $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );

			$this->objPHPExcel->getActiveSheet ()
				->setTitle ( $title_xls );
		}
	}

	/**
	 * Tao tieu de cho bao cao danh sach sinh vien
	 */
	public function setCustomerInfoExportStudents($school_info = null, $title_info) {

		if ($school_info != null) {
			// Ve Logo
			if ($school_info->getCusLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $school_info->getCusPath () )) {

				$objDrawing = new PHPExcel_Worksheet_Drawing ();

				$objDrawing->setName ( 'Logo' );

				$objDrawing->setDescription ( $school_info->getCusSchoolName () );

				$objDrawing->setPath ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $school_info->getCusPath () );

				$objDrawing->setOffsetY ( 10 );
				$objDrawing->setOffsetX ( 10 );
				$objDrawing->setCoordinates ( 'A1' );
				$objDrawing->setHeight ( 200 );
				$objDrawing->setWidth ( 170 );

				$objDrawing->getShadow ()
					->setVisible ( true );
				$objDrawing->getShadow ()
					->setDirection ( 80 );
				$objDrawing->setWorksheet ( $this->objPHPExcel->getActiveSheet () );
			}

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C1', $school_info->getCusSchoolName () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C2', $school_info->getWpName () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C3', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Address' ) . ': ' . $school_info->getWpAddress () . ' - ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'Tel' ) . ': ' . ($school_info->getWpPhone ()) );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A6', $title_info ['title_infor'] . $school_info->getSyTitle () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D7', $title_info ['class'] );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D8', $title_info ['group'] );
		}
	}

	/**
	 * Tao tieu de cho thong ke sinh vien theo truong, co so
	 */
	public function setCustomerInfoExportStudentsStatistic($school_year, $school_info = null, $workplace_id = null) {

		if ($school_info != null) {
			// Ve Logo
			if ($school_info->getLogo () != '' && is_file ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $school_info->getPath () )) {

				$objDrawing = new PHPExcel_Worksheet_Drawing ();

				$objDrawing->setName ( 'Logo' );

				$objDrawing->setDescription ( $school_info->getSchoolName () );

				$objDrawing->setPath ( sfConfig::get ( 'app_ps_upload_dir' ) . '/' . $school_info->getPath () );

				$objDrawing->setOffsetY ( 10 );
				$objDrawing->setOffsetX ( 10 );
				$objDrawing->setCoordinates ( 'A1' );
				$objDrawing->setHeight ( 200 );
				$objDrawing->setWidth ( 150 );

				$objDrawing->getShadow ()
					->setVisible ( true );
				$objDrawing->getShadow ()
					->setDirection ( 80 );
				$objDrawing->setWorksheet ( $this->objPHPExcel->getActiveSheet () );
			}

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C1', $school_info->getSchoolName () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C2', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Address' ) . ': ' . $school_info->getAddress () . ' - ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'Tel' ) . ': ' . ($school_info->getTel () ? $school_info->getTel () : $school_info->getMobile ()) );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'C2:H2' );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A6', $this->object->getContext ()
				->getI18N ()
				->__ ( 'STUDENT STATISTICS IN CUSTOMER' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D7', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Year' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E7', $school_year ['title'] );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A8', $this->object->getContext ()
				->getI18N ()
				->__ ( 'No.' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B8', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Class' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C8', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Teacher' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D8', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Total Class' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E8', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Total Student' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F9', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Offical' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F8', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Status' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G9', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Test' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H9', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Pause' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I9', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Stop' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'J9', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Graduation' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'K8', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Note' ) );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A10', $school_info->getSchoolName () );

			$this->objPHPExcel->getActiveSheet ()
				->setTitle ( $school_info->getSchoolName () );
		}
	}

	/**
	 * Tao thong tin lop
	 */
	public function setStudentInfoExportStudents($teacher_info = null, $class_objGroup = null) {

		if ($class_objGroup != null) {

			$this->sheet_title_index = $class_objGroup->getOgTitle ();

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E7', $class_objGroup->getMcName () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E8', $class_objGroup->getOgTitle () );

			$this->objPHPExcel->getActiveSheet ()
				->setTitle ( $class_objGroup->getMcName () );
		}

		if ($teacher_info != null) {
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A9', $this->object->getContext ()
				->getI18N ()
				->__ ( $teacher_info ) );
		}
	}

	/**
	 * Set data cho bao cao danh sach sinh vien
	 */
	public function setDataExportStudents($data_student) {

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
			->setCellValue ( 'A10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'No.' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'B10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Student Code' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'First name' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Last name' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'E10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Nickname' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Birthday' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Gender' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'H10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Nation' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'I10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Nationality' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'J10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Address' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'K10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Status' ) );

		$number_student = count ( $data_student );
		$index = 1;
		$start_row = 11;
		$row = 1;

		// Trang thai hoc cua hoc sinh
		$hoc_thu = 0;
		$chinh_thuc = 0;
		$tam_dung = 0;
		$tot_nghiep = 0;
		$thoi_hoc = 0;
		$giu_cho = 0;

		$culture = sfContext::getInstance ()->getUser ()->getCulture ();
		
		$_countries = sfCultureInfo::getInstance ( $culture )->getCountries ();
		
		$type_student_class = PreSchool::loadStatusStudentClass ();
		$student_sex = PreSchool::getGender ();
		
		foreach ( $data_student as $dt ) {
			
			// Trang thai hoc sinh trong lop
			$type = $dt->getType ();
			
			$index ++;
			
			// STT-A
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $start_row, $row );

			// Ma HS-B
			//$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $start_row, $dt->getStudentCode () );
			$this->objPHPExcel->getActiveSheet ()->setCellValueExplicit( 'B' . $start_row, $dt->getStudentCode (),PHPExcel_Cell_DataType::TYPE_STRING );

			//Ho dem-C
			/*
			if (substr($dt->getFirstName (), 0,0) == '=' )    
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $start_row, "'".$dt->getFirstName () );
			else
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $start_row, $dt->getFirstName () );
			*/
			
			$this->objPHPExcel->getActiveSheet ()->setCellValueExplicit( 'C' . $start_row, $dt->getFirstName (),PHPExcel_Cell_DataType::TYPE_STRING );
			
			//Ten-D
			//$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $start_row, "'".$dt->getLastName () );
			$this->objPHPExcel->getActiveSheet ()->setCellValueExplicit( 'D' . $start_row, $dt->getLastName (),PHPExcel_Cell_DataType::TYPE_STRING );

			//Ten goi khac-E
			//$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $start_row, "'".$dt->getNickName () );
			
			$this->objPHPExcel->getActiveSheet ()->setCellValueExplicit( 'E' . $start_row, $dt->getNickName (),PHPExcel_Cell_DataType::TYPE_STRING );

			switch ($type) {
				case PreSchool::SC_STATUS_OFFICIAL :
					$chinh_thuc = $chinh_thuc + 1;
					break;
				case PreSchool::SC_STATUS_TEST :
					$hoc_thu = $hoc_thu + 1;
					break;
				case PreSchool::SC_STATUS_PAUSE :
					$tam_dung = $tam_dung + 1;
					break;
				case PreSchool::SC_STATUS_STOP_STUDYING :
					$thoi_hoc = $thoi_hoc + 1;
					break;
				case PreSchool::SC_STATUS_GRADUATION :
					$tot_nghiep = $tot_nghiep + 1;
				
				case PreSchool::SC_STATUS_HOLD :
					$giu_cho    = $giu_cho + 1;
			}

			if (isset ( $type_student_class [$type] ))				//
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'K' . $start_row, $this->object->getContext ()->getI18N ()->__ ( $type_student_class [$type] ) );
			else
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'K' . $start_row, null);

			// Ngay sinh-F
			$_birthday = ($dt->getBirthday() != '') ? date ( "d-m-Y", PsDateTime::psDatetoTime ($dt->getBirthday() ) ) : '';
			//$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $start_row, $_birthday );
			
			$this->objPHPExcel->getActiveSheet ()->setCellValueExplicit( 'F' . $start_row, $_birthday,PHPExcel_Cell_DataType::TYPE_STRING );

			// //Gioi tinh-G
			if (isset ( $student_sex [$dt->getSex ()] ))			
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $start_row, $this->object->getContext ()->getI18N ()->__ ( $student_sex [$dt->getSex ()] ) );
			else
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $start_row, null);
			
			// Dan toc-H
			//$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $start_row, $dt->getTitleEthnic () );
			$this->objPHPExcel->getActiveSheet ()->setCellValueExplicit( 'H' . $start_row, $dt->getAddress (),PHPExcel_Cell_DataType::TYPE_STRING );
			
			// Quoc tich-I
			$nationality = isset ( $_countries [$dt->getNationality ()] ) ? $_countries [$dt->getNationality ()] : $dt->getNationality ();			
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'I' . $start_row, $nationality );
			
			//Dia chi-J
			//$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'J' . $start_row, $dt->getAddress ());						
			$this->objPHPExcel->getActiveSheet ()->setCellValueExplicit( 'J' . $start_row, $dt->getAddress (),PHPExcel_Cell_DataType::TYPE_STRING );
			
			
			$this->objPHPExcel->getActiveSheet ()->getRowDimension ( $start_row )->setRowHeight ( - 1 );

			$start_row = $start_row + 1;

			$row = $row + 1;
			
			if ($index <= $number_student)
				$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
		}
		
		$total = $chinh_thuc + $hoc_thu + $tam_dung + $tot_nghiep + $thoi_hoc + $giu_cho;
		
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $start_row, $this->object->getContext ()->getI18N ()->__ ( 'Total' ) );
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $start_row, $total );

		$start_row = $start_row + 1;
		
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $this->object->getContext ()
			->getI18N ()
			->__ ( 'Official' ) . ': ' . $chinh_thuc );
		
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $this->object->getContext ()
			->getI18N ()
			->__ ( 'Test' ) . ': ' . $hoc_thu );

		$start_row = $start_row + 1;
		
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $this->object->getContext ()
			->getI18N ()
			->__ ( 'Pause' ) . ': ' . $tam_dung );
		
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $this->object->getContext ()
			->getI18N ()
			->__ ( 'Stop studying' ) . ': ' . $thoi_hoc );
		
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'J' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );

		$start_row = $start_row + 1;
		
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $start_row, $this->object->getContext ()->getI18N ()->__ ( 'Graduation' ) . ': ' . $tot_nghiep );
		
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $start_row, $this->object->getContext ()->getI18N ()->__ ( 'Hold place' ) . ': ' . $giu_cho );
	}

	/**
	 * Set data cho bao cao danh sach sinh vien voi nguoi than
	 */
	public function setDataExportStudentsWithRelatives($data_student) {

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
			->setCellValue ( 'A10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'No.' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'B10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Student Code' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'First name' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Last name' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'E10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Birthday' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Gender' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Status' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'H10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Address' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'I10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Relationship' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'J10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Name' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'K10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Phone' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'M10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Account' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'N10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Password' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'O10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Note' ) );
		$number_student = count ( $data_student );
		$index = 1;
		$start_row = 11;
		$row = 1;
		$quan_he = '';
		$email = '';
		$ho_ten = '';
		$sdt = '';

		// Trang thai hoc cua hoc sinh
		$hoc_thu = 0;
		$chinh_thuc = 0;
		$tam_dung = 0;
		$tot_nghiep = 0;
		$thoi_hoc = 0;
		
		$type_student_class = PreSchool::loadStatusStudentClass ();
		$student_sex = PreSchool::getGender ();
		
		foreach ( $data_student as $dt ) {
			// 			if ($dt->getType () == PreSchool::SC_STATUS_FINISHED)
			// 				continue;
			$index ++;

			// Lay ra ID Student
			//$this->_student = Doctrine::getTable ( 'Student' )->getStudentById ( $dt->getSId () );

			// Lay ra thong tin tat ca nguoi than
			//$nguoi_dua_chinh = $this->_student->getRelativesOfStudent ();
			
			$nguoi_dua_chinh = Doctrine::getTable ( 'RelativeStudent' )->findByStudentId ( $dt->getSId (), $dt->getPsCustomerId () );
			// Lay ra thong tin nguoi dua don chinh
			// $nguoi_dua_chinh = $this->_student->getMainRelativesOfStudent();
			// $nguoi_dua_chinh = Doctrine::getTable('RelativeStudent')->findMainParentsByStudentId($dt->getSId(),);

			// STT-A
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $row );

			// Ma HS-B
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $dt->getStudentCode () );

			// //Ho dem-C
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $dt->getFirstName () );

			// //Ten-D
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $dt->getLastName () );

			// Ngay sinh-E
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, date ( "d-m-Y", PsDateTime::psDatetoTime ( $dt->getBirthday () ) ) );

			// Gioi tinh-F
			$sex = $dt->getSex ();
			
			if (isset ( $student_sex [$sex] ))
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, $this->object->getContext ()
					->getI18N ()
					->__ ( $student_sex [$sex] ) );
			// Trang thai-G
			$type = $dt->getType ();
			
			switch ($type) {
				case PreSchool::SC_STATUS_OFFICIAL :
					$chinh_thuc = $chinh_thuc + 1;
					break;
				case PreSchool::SC_STATUS_TEST :
					$hoc_thu = $hoc_thu + 1;
					break;
				case PreSchool::SC_STATUS_PAUSE :
					$tam_dung = $tam_dung + 1;
					break;
				case PreSchool::SC_STATUS_STOP_STUDYING :
					$thoi_hoc = $thoi_hoc + 1;
					break;
				case PreSchool::SC_STATUS_GRADUATION :
					$tot_nghiep = $tot_nghiep + 1;
			}

			if (isset ( $type_student_class [$type] ))
				//
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'G' . $start_row, $this->object->getContext ()
					->getI18N ()
					->__ ( $type_student_class [$type] ) );
			// Dia chi-H
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . $start_row, $dt->getAddress () );

			$this->objPHPExcel->getActiveSheet ()
				->getRowDimension ( $start_row )
				->setRowHeight ( - 1 );

			$i = 0;
			foreach ( $nguoi_dua_chinh as $ndc ) {

				if ($i > 0) {
					$curent_row = $start_row;
					$start_row = $start_row + 1;
					$this->objPHPExcel->getActiveSheet ()
						->insertNewRowBefore ( $start_row, 1 );
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'A' . $curent_row . ':A' . $start_row );
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'B' . $curent_row . ':B' . $start_row );
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'C' . $curent_row . ':C' . $start_row );
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'D' . $curent_row . ':D' . $start_row );
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'E' . $curent_row . ':E' . $start_row );
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'F' . $curent_row . ':F' . $start_row );
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'G' . $curent_row . ':G' . $start_row );
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'H' . $curent_row . ':H' . $start_row );
				}

				// Quan he-I
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'I' . $start_row, $ndc->getTitle () );

				// Ten-J
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'J' . $start_row, $ndc->getFullName () );

				// SDT-K
				if ($ndc->getMobile ())
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'K' . $start_row, $ndc->getMobile () );
				else
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'K' . $start_row, $ndc->getPhone () );

				// Email-L
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'L' . $start_row, $ndc->getEmail () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'M' . $start_row, $ndc->getUserName () );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'N' . $start_row, $ndc->getUserPassWord () );

				$i = $i + 1;
			}
			$start_row = $start_row + 1;

			$row = $row + 1;

			if ($index <= $number_student)
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
		}

		$total = $chinh_thuc + $hoc_thu + $tam_dung + $tot_nghiep + $thoi_hoc;
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $this->object->getContext ()
			->getI18N ()
			->__ ( 'Total' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'B' . $start_row, $total );

		$start_row = $start_row + 1;
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $this->object->getContext ()
			->getI18N ()
			->__ ( 'Official' ) . ': ' . $chinh_thuc );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $this->object->getContext ()
			->getI18N ()
			->__ ( 'Test' ) . ': ' . $hoc_thu );

		$start_row = $start_row + 1;
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $this->object->getContext ()
			->getI18N ()
			->__ ( 'Pause' ) . ': ' . $tam_dung );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $this->object->getContext ()
			->getI18N ()
			->__ ( 'Stop studying' ) . ': ' . $thoi_hoc );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'K' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );

		$start_row = $start_row + 1;
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $this->object->getContext ()
			->getI18N ()
			->__ ( 'Graduation' ) . ': ' . $tot_nghiep );
	}

	/**
	 * Thong ke so luong hoc sinh theo Nhom lop, Ten lop va truong
	 */
	public function setDataExportStudentStatistic($workplace, $class, $is_export_workplace) {

		$day = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' );
		$month = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Month' );
		$year = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Year' );

		// Toan truong-A10
		$start_row = 11;
		$start_row_customer = 10;
		$start_row_workplace = 11;

		$stt = 0;
		foreach ( $class as $index => $cl ) {

			if ($class [$index_before] ['workplace_id'] != $cl ['workplace_id']) {

				$workplace [$cl ['workplace_id']] ['row'] = $start_row;

				$stt = 0;

				$start_row_workplace = $s;

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $cl ['workplace_name'] );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . ($start_row) . ':C' . ($start_row) );

				// $this->objPHPExcel->getActiveSheet()->duplicateStyle($this->objPHPExcel->getActiveSheet()->getStyle('A' . $start_row_workplace), 'A' . ($start_row) . ':B' . ($start_row));

				$start_row ++;

				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
			}

			if ($cl ['class_id'] <= 0 || $cl ['class_id'] == '') {
				continue;
			}
			$stt ++;

			// STT
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $stt );

			// Ten lop-B12
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $cl ['class_name'] );

			// GVCN
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $cl ['teacher'] );

			// Trang thai
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $cl ['class_offical'] );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $cl ['class_test'] );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . $start_row, $cl ['class_pause'] );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I' . $start_row, $cl ['class_stop_studying'] );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'J' . $start_row, $cl ['class_graduation'] );

			// Sy so
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, $cl ['class_offical'] + $cl ['class_test'] + $cl ['class_pause'] + $cl ['class_stop_studying'] + $cl ['class_graduation'] );

			// Luu chi so index mang da duyet
			$index_before = $index;

			$start_row ++;

			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
		}

		// Cap nhat gia tri co so
		foreach ( $workplace as $key => $wp ) {
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $wp ['row'], $wp ['total_class'] );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $wp ['row'], $wp ['total_student'] );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $wp ['row'], $wp ['workplace_offical'] );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $wp ['row'], $wp ['workplace_test'] );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . $wp ['row'], $wp ['workplace_pause'] );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I' . $wp ['row'], $wp ['workplace_stop_studying'] );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'J' . $wp ['row'], $wp ['workplace_graduation'] );
		}

		$this->objPHPExcel->getActiveSheet ()
			->removeRow ( $start_row );

		$total_student_offical = array_sum ( array_column ( $workplace, 'workplace_offical' ) );
		$total_student_test = array_sum ( array_column ( $workplace, 'workplace_test' ) );
		$total_student_pause = array_sum ( array_column ( $workplace, 'workplace_pause' ) );
		$total_student_stop = array_sum ( array_column ( $workplace, 'workplace_stop_studying' ) );
		$total_student_graduated = array_sum ( array_column ( $workplace, 'workplace_graduation' ) );
		$total_student = array_sum ( array_column ( $workplace, 'total_student' ) );
		$total_class = array_sum ( array_column ( $workplace, 'total_class' ) );

		if ($is_export_workplace) {
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D10', '' );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F10', '' );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G10', '' );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H10', '' );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I10', '' );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'J10', '' );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E10', '' );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D11', $total_class );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F11', $total_student_offical );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G11', $total_student_test );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H11', $total_student_pause );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I11', $total_student_stop );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'J11', $total_student_graduated );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E11', $total_student );
		} else {
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D10', $total_class );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F10', $total_student_offical );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G10', $total_student_test );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H10', $total_student_pause );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I10', $total_student_stop );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'J10', $total_student_graduated );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E10', $total_student );
		}
		$styleArray = array (
				'borders' => array (
						'allborders' => array (
								'style' => PHPExcel_Style_Border::BORDER_THIN ) ) );

		$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'A' . $start_row_customer . ':K' . ($start_row) )
			->applyFromArray ( $styleArray );
		$this->objPHPExcel->getActiveSheet ()
			->removeRow ( $start_row );

		$start_row = $start_row + 2;

		$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'I' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );
	}

	/**
	 * Thong ke cong no cua hoc sinh
	 */
	public function setDataExportStudentSynthetic2($list_student, $list_service, $list_receivables, $ps_month, $ConfigStartDateSystemFee) {

		$date_ExportReceipt = $receivable_at = '01-' . $ps_month;
		$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );

		$date = date_create ( $date_ExportReceipt );
		date_modify ( $date, "-1 month" );
		$receiptPrevDate = date_format ( $date, "Y-m-d" );

		$array_service = $array_receivable = array ();
		// cac dich vu
		$start_service = 5;
		$index2 = 0;
		foreach ( $list_service as $service ) {

			// array_push($array_service, $service->getId());

			$col = PHPExcel_Cell::stringFromColumnIndex ( 10 + $index2 );
			$col2 = PHPExcel_Cell::columnIndexFromString ( $col );

			$row = $start_service;

			if ($service->getEnableRoll () == PreSchool::ACTIVE) { // neu dich vu la co dinh

				$col3 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 3 );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( $col . $row . ':' . $col3 . $row );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col . $row, $service->getTitle () );

				$row2 = $start_service + 2;

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 - 1 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, $this->object->getContext ()
					->getI18N ()
					->__ ( 'Price' ) );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, 'SL' );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 1 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, $this->object->getContext ()
					->getI18N ()
					->__ ( 'GT co dinh' ) );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 2 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, 'GT %' );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 3 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, $this->object->getContext ()
					->getI18N ()
					->__ ( 'Phai thu' ) );

				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getAlignment ()
					->setWrapText ( true );
				$index2 = $index2 + 5;
			} else {

				$col3 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 10 );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( $col . $row . ':' . $col3 . $row );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col . $row, $service->getTitle () );

				$row1 = $start_service + 1;

				$col_3 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 4 );
				$col4 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 5 );
				$col_4 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 10 );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( $col . $row1 . ':' . $col_3 . $row1 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col . $row1, $this->object->getContext ()
					->getI18N ()
					->__ ( 'Last month' ) );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( $col4 . $row1 . ':' . $col_4 . $row1 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col4 . $row1, $this->object->getContext ()
					->getI18N ()
					->__ ( 'This month' ) );

				$row2 = $start_service + 2;
				// thang truoc
				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 - 1 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, $this->object->getContext ()
					->getI18N ()
					->__ ( 'Price' ) );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, 'SL DK' );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 1 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, $this->object->getContext ()
					->getI18N ()
					->__ ( 'GT co dinh' ) );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 2 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, 'GT %' );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 3 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, 'SL SD' );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 4 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, $this->object->getContext ()
					->getI18N ()
					->__ ( 'Da thu' ) );

				// thang nay

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 5 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, $this->object->getContext ()
					->getI18N ()
					->__ ( 'Price' ) );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 6 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, 'SL DK' );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 7 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, $this->object->getContext ()
					->getI18N ()
					->__ ( 'GT co dinh' ) );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 8 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, 'GT %' );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 9 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, $this->object->getContext ()
					->getI18N ()
					->__ ( 'Last month amount' ) );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 10 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, $this->object->getContext ()
					->getI18N ()
					->__ ( 'Phai thu' ) );

				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getAlignment ()
					->setWrapText ( true );
				$index2 = $index2 + 12;
			}
		}
		// cac khoan phai thu khac
		$index3 = 0;

		$number_receivable = count ( $list_receivables );

		$col5 = PHPExcel_Cell::columnIndexFromString ( $col3 );
		$col6 = PHPExcel_Cell::stringFromColumnIndex ( $col5 );
		$col7 = PHPExcel_Cell::stringFromColumnIndex ( $col5 + $number_receivable );

		$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( $col6 . $start_service . ':' . $col7 . $start_service );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( $col6 . $start_service, $this->object->getContext ()
			->getI18N ()
			->__ ( 'List receivables' ) );

		foreach ( $list_receivables as $receivables ) {

			array_push ( $array_receivable, $receivables->getId () );

			$col = PHPExcel_Cell::stringFromColumnIndex ( $col5 + $index3 );

			$row = $start_service + 2;

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col . $row, $receivables->getTitle () );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getAlignment ()
				->setWrapText ( true );
			$index3 ++;
		}

		$col = PHPExcel_Cell::stringFromColumnIndex ( 10 + $index2 + $index3 );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( $col . $row, $this->object->getContext ()
			->getI18N ()
			->__ ( 'Is late' ) );
		// danh sach hoc sinh
		$start_row = 8; $start_fix = 8;
		$index = 0;
		$number_student = count ( $list_student );

		foreach ( $list_student as $key => $student ) {

			$student_id = $student->getId ();

			$collectedAmount = $balanceAmount = $thute = $totalAmount = $balance_last_month_amount = 0;

			$receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableAndServiceStudentOfMonth ( $student_id, $date_ExportReceipt, $receiptPrevDate );

			$receipt = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $int_date_ExportReceipt );

			if ($receipt) { // hoc sinh co phieu

				$balance_last_month_amount = $receipt->getBalanceLastMonthAmount ();
				$collectedAmount = $receipt->getCollectedAmount ();
				// $balanceAmount = $receipt->getBalanceAmount();
				$balanceAmount = $collectedAmount - $receipt->getBalanceAmount () + $balance_last_month_amount;

				$thute = $collectedAmount - $receipt->getBalanceAmount ();
			}

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $key + 1 );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $student->getStudentCode () );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $student->getStudentName () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, date ( 'd-m-Y', strtotime ( $student->getBirthday () ) ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, date ( 'd-m-Y', strtotime ( $student->getStartDateAt () ) ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $student->getClassName () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $balanceAmount );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . $start_row, $balance_last_month_amount );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I' . $start_row, $thute );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'J' . $start_row, $collectedAmount );

			$index2 = $dichvucodinh = $dichvukocodinh = 0;
			$array_receivable_student = array ();

			foreach ( $list_service as $service ) {

				$service_id = $service->getId ();

				$col = PHPExcel_Cell::stringFromColumnIndex ( 10 + $index2 );
				$col2 = PHPExcel_Cell::columnIndexFromString ( $col );

				if ($service->getEnableRoll () == PreSchool::ACTIVE) { // Neu la dich vu co dinh

					foreach ( $receivable_student as $key => $student_service ) {

						if ($service_id == $student_service->getRsServiceId ()) {

							if ($student_service->getRsUnitPrice () != 0) {

								$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 - 1 );
								$this->objPHPExcel->getActiveSheet ()
									->setCellValue ( $col5 . $start_row, $student_service->getRsUnitPrice () );

								$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 );
								$this->objPHPExcel->getActiveSheet ()
									->setCellValue ( $col5 . $start_row, $student_service->getRsByNumber () );

								$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 1 );
								$this->objPHPExcel->getActiveSheet ()
									->setCellValue ( $col5 . $start_row, $student_service->getRsDiscountAmount () );

								$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 2 );
								$this->objPHPExcel->getActiveSheet ()
									->setCellValue ( $col5 . $start_row, $student_service->getRsDiscount () );

								$rs_amount = ($student_service->getRsDiscount () > 0) ? ((100 - $student_service->getRsDiscount ()) * $student_service->getRsUnitPrice () * $student_service->getRsByNumber ()) / 100 : $student_service->getRsUnitPrice () * $student_service->getRsByNumber ();
								$rs_amount = $rs_amount - ( float ) $student_service->getRsDiscountAmount ();

								$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 3 );
								$this->objPHPExcel->getActiveSheet ()
									->setCellValue ( $col5 . $start_row, $rs_amount );
							}
							unset($receivable_student[$key]);
						}
					}
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( $col . $start_row )
						->getAlignment ()
						->setWrapText ( true );
					$index2 = $index2 + 5;
					
					$dichvucodinh++;
					
				} else {

					foreach ( $receivable_student as $key2=> $student_service ) {

						if ($service_id == $student_service->getRsServiceId ()) {

							if ($student_service->getRsUnitPrice () != 0) {

								if (date ( 'Ym', strtotime ( $student_service->getRsReceiptDate () ) ) == date ( 'Ym', strtotime ( $receiptPrevDate ) )) { // thang truoc

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 - 1 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $student_service->getRsUnitPrice () );

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $student_service->getRsByNumber () );

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 1 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $student_service->getRsDiscountAmount () );

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 2 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $student_service->getRsDiscount () );

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 3 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $student_service->getRsSpentNumber () );

									$rs_amount = ($student_service->getRsDiscount () > 0) ? ((100 - $student_service->getRsDiscount ()) * $student_service->getRsUnitPrice () * $student_service->getRsByNumber ()) / 100 : $student_service->getRsUnitPrice () * $student_service->getRsByNumber ();
									$rs_amount = $rs_amount - ( float ) $student_service->getRsDiscountAmount ();

									$thangtruocchuyensang = $rs_amount - $student_service->getRsAmount ();

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 4 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $rs_amount );
								}
								if (date ( 'Ym', strtotime ( $student_service->getRsReceiptDate () ) ) == date ( 'Ym', strtotime ( $date_ExportReceipt ) )) {

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 5 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $student_service->getRsUnitPrice () );

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 6 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $student_service->getRsByNumber () );

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 7 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $student_service->getRsDiscountAmount () );

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 8 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $student_service->getRsDiscount () );

									$rs_amount = ($student_service->getRsDiscount () > 0) ? ((100 - $student_service->getRsDiscount ()) * $student_service->getRsUnitPrice () * $student_service->getRsByNumber ()) / 100 : $student_service->getRsUnitPrice () * $student_service->getRsByNumber ();
									$rs_amount = $rs_amount - ( float ) $student_service->getRsDiscountAmount ();

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 9 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $thangtruocchuyensang );

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 10 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $rs_amount - $thangtruocchuyensang );
								}
							}
							unset($receivable_student[$key2]);
						}
					}

					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( $col . $start_row )
						->getAlignment ()
						->setWrapText ( true );
					$index2 = $index2 + 12;
					$dichvukocodinh ++;
				}
			}

			$index3 = 0;

			foreach ( $array_receivable as $receivable_id ) {

				$col = PHPExcel_Cell::stringFromColumnIndex ( 10 + $index2 + $index3 );

				foreach ( $receivable_student as $student_service ) {

					if ($receivable_id == $student_service->getRsReceivableId () && date ( 'Ym', strtotime ( $student_service->getRsReceiptDate () ) ) == date ( 'Ym', strtotime ( $date_ExportReceipt ) )) {

						$this->objPHPExcel->getActiveSheet ()
							->setCellValue ( $col . $start_row, $student_service->getRsAmount () );
					}
					if ($student_service->getRsIsLate () == PreSchool::ACTIVE) {
						$is_late = $student_service->getRsAmount ();
					}
				}

				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $start_row )
					->getAlignment ()
					->setWrapText ( true );
				$index3 ++;
			}

			$col = PHPExcel_Cell::stringFromColumnIndex ( 10 + $index2 + $index3 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col . $start_row, $is_late );

			$start_row = $start_row + 1;
			if ($index < $number_student) {
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
			}
		}
		
		$start_end = $start_row;
		$start_row = $start_row + 1;
		$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
		$start_row = $start_row + 1;
		
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $start_row, "=SUM(G".$start_fix.":G".$start_end.")" );
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $start_row, "=SUM(H".$start_fix.":H".$start_end.")" );
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'I' . $start_row, "=SUM(I".$start_fix.":I".$start_end.")" );
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'J' . $start_row, "=SUM(J".$start_fix.":J".$start_end.")" );
		
		for($i = 1; $i<=$dichvucodinh; $i++){
			$index2 = $i*5;
			$col1 = PHPExcel_Cell::stringFromColumnIndex ( 9 + $index2 );
			//$col2 = PHPExcel_Cell::columnIndexFromString ( $col1 );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col1 . $start_row, "=SUM(".$col1.$start_fix.":".$col1.$start_end.")" );
		}
		for($j = 1; $j<= $dichvukocodinh; $j++){
			$index5 = $j*12;
			$col20 = PHPExcel_Cell::stringFromColumnIndex ( 9 + ($dichvucodinh*5) + $index5 );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col20 . $start_row, "=SUM(".$col20.$start_fix.":".$col20.$start_end.")" );
		}
		
		for($k = 1; $k<= $index3+1; $k++){
			$col21 = PHPExcel_Cell::stringFromColumnIndex ( 9 + ($dichvucodinh*5) + ($dichvukocodinh*12) + $k );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col21 . $start_row, "=SUM(".$col21.$start_fix.":".$col21.$start_end.")" );
		}
		
		$this->objPHPExcel->getActiveSheet ()->removeRow ( ($start_end), 1);
	}


	
	public function setDataExportReceivableStudentStatistic($list_student, $list_service, $ps_month) {

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
		$stt = 1;
		$tong_thu_thang_truoc = $tong_thu_thang_nay = 0;
		foreach ( $list_student as $student ) {

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $stt );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $student->getStudentCode () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $student->getStudentName () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, date ( 'd-m-Y', strtotime ( $student->getBirthday () ) ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, $student->getClassName () );

			$tt_unitprice = $tt_soluong = $tt_discount = $tt_discount_amount = $tt_amount = '';
			$tn_unitprice = $tn_soluong = $tn_discount = $tn_discount_amount = $tn_amount = '';

			foreach ( $list_service as $service ) {

				if ($student->getId () == $service->getStudentId () && date ( 'Ym', strtotime ( $service->getReceiptDate () ) ) != date ( 'Ym', strtotime ( '01-' . $ps_month ) )) {
					$tt_unitprice = $service->getUnitPrice ();
					$tt_dukien = $service->getByNumber ();
					$tt_soluong = $service->getSpentNumber ();
					$tt_discount = $service->getDiscount ();
					$tt_discount_amount = $service->getDiscountAmount ();
					$tt_dasudung = $service->getAmount ();

					$tt_amount = ($tt_discount > 0) ? ((100 - $tt_discount) * $tt_unitprice * $tt_dukien) / 100 : $tt_unitprice * $tt_dukien;
					$tt_amount = $tt_amount - ( float ) $tt_discount_amount;

					$thangtruocchuyensang = $tt_amount - $service->getAmount ();
				}
				if ($student->getId () == $service->getStudentId () && date ( 'Ym', strtotime ( $service->getReceiptDate () ) ) == date ( 'Ym', strtotime ( '01-' . $ps_month ) )) {
					$tn_unitprice = $service->getUnitPrice ();
					$tn_soluong = $service->getByNumber ();
					$tn_discount = $service->getDiscount ();
					$tn_discount_amount = $service->getDiscountAmount ();

					$tn_amount = ($tn_discount > 0) ? ((100 - $tn_discount) * $tn_unitprice * $tn_soluong) / 100 : $tn_unitprice * $tn_soluong;
					$tn_amount = $tn_amount - ( float ) $tn_discount_amount;

					$phai_nop_thang_nay = $tn_amount - $thangtruocchuyensang;
				}
			}

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $tt_unitprice );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $tt_soluong );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . $start_row, $tt_discount );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'I' . $start_row, $tt_discount_amount );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'J' . $start_row, $tt_amount );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'K' . $start_row, $tt_dasudung );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'L' . $start_row, $tn_unitprice );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'M' . $start_row, $tn_soluong );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'N' . $start_row, $tn_discount );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'O' . $start_row, $tn_discount_amount );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'P' . $start_row, $tn_amount );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'Q' . $start_row, $thangtruocchuyensang );
			$thangtruocchuyensang = '';
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'R' . $start_row, $phai_nop_thang_nay );
			$phai_nop_thang_nay = '';

			$stt ++;
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
		}

		$this->objPHPExcel->getActiveSheet ()
			->removeRow ( $start_row, 2 );
	}

	public function setDataExportReceivableStudentStatistic2($list_student, $list_receivable, $ps_month) {

		$day = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' );
		$month = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Month' );
		$year = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Year' );

		$start_row = 5;
		$stt = 1;
		$tong_thu_thang_truoc = $tong_thu_thang_nay = 0;
		foreach ( $list_student as $student ) {

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $stt );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $student->getStudentCode () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $student->getStudentName () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, date ( 'd-m-Y', strtotime ( $student->getBirthday () ) ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, $student->getClassName () );

			foreach ( $list_receivable as $receivable ) {
				if ($student->getId () == $receivable->getStudentId ()) {
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'F' . $start_row, $receivable->getAmount () );
				}
			}

			$stt ++;
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
		}

		$this->objPHPExcel->getActiveSheet ()
			->removeRow ( $start_row, 2 );
	}
	
	public function setDataExportStudentSyntheticPayment($list_student_payment) {
		
		$day = $this->object->getContext () ->getI18N () ->__ ( 'Day' );
		$month = $this->object->getContext () ->getI18N ()->__ ( 'Month' );
		$year = $this->object->getContext ()->getI18N ()->__ ( 'Year' );
		$payment_type = '';
		$start_row = 5;
		$stt = 1;
		
		foreach ( $list_student_payment as $student_payment ) {
			
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $start_row, $stt );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $start_row, $student_payment->getReceiptNo() );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $start_row, $student_payment->getStudentCode () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $start_row, $student_payment->getStudentName () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $start_row, $student_payment->getMcName () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $start_row, $student_payment->getCollectedAmount () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $start_row, $student_payment->getPaymentRelativeName () );
			
			$payment_type = $this->object->getContext () ->getI18N () ->__(PreSchool::loadPsPaymentType()[$student_payment->getPaymentType ()]);
			
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $start_row, $payment_type );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'I' . $start_row, $student_payment->getNote () );
			
			$stt ++;
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
		}
		
		$this->objPHPExcel->getActiveSheet ()->removeRow ( $start_row, 1 );
	}
	
	/**/
	public function xuatSoTheoDoiTreTrongLop($list_student,$list_relative) {
		
		$bo = $this->object->getContext () ->getI18N () ->__ ( 'bo' );
		$me = $this->object->getContext () ->getI18N () ->__ ( 'me' );
		
		$start_row = 4;
		$stt = 1;
		
		$gioitinh = PreSchool::loadPsGender();
		
		//$this->objPHPExcel->getActiveSheet () ->getRowDimension(1)->setRowHeight(-1);
		
		foreach ( $list_student as $key2=> $student ) {
			
			$this->objPHPExcel->getActiveSheet () ->getRowDimension($start_row)->setRowHeight(-1);
			
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $start_row, $stt );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $start_row, $student->getStudentName() );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $start_row, date('d-m-Y',strtotime($student->getBirthday ())));
			
			$sex = $this->object->getContext () ->getI18N () ->__($gioitinh[$student->getSex ()]);
			
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $start_row, $sex);
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $start_row, $student->getAddress () );
			
			$error_re = $index = 0;
			$phone_number = null;
			$array_phone = array();
			foreach ($list_relative as $key=>$relative){
				
				if($relative->getStudentId() == $student->getId()){
					
					if(PreString::strLower ($relative->getRssTitle()) == $bo){
						$error_re = 1;
						$col = PHPExcel_Cell::stringFromColumnIndex ( 5 );
						$col2 = PHPExcel_Cell::stringFromColumnIndex ( 6 );
						
						$this->objPHPExcel->getActiveSheet () ->setCellValue ( $col . $start_row, $relative->getFullName() );
						$this->objPHPExcel->getActiveSheet () ->setCellValue ( $col2 . $start_row, $relative->getJob() );
						
					}
					if(PreString::strLower ($relative->getRssTitle()) == $me){
						$error_re = 1;
						$col = PHPExcel_Cell::stringFromColumnIndex ( 7 );
						$col2 = PHPExcel_Cell::stringFromColumnIndex ( 8 );
						
						$this->objPHPExcel->getActiveSheet () ->setCellValue ( $col . $start_row, $relative->getFullName() );
						$this->objPHPExcel->getActiveSheet () ->setCellValue ( $col2 . $start_row, $relative->getJob() );
						
					}
					
					if($error_re == 0){
						$index ++;
						$col = PHPExcel_Cell::stringFromColumnIndex ( 4 + $index);
						$index ++;
						$col2 = PHPExcel_Cell::stringFromColumnIndex ( 4 + $index);
						
						$this->objPHPExcel->getActiveSheet () ->setCellValue ( $col . $start_row, $relative->getRssTitle().': '.$relative->getFullName() );
						$this->objPHPExcel->getActiveSheet () ->setCellValue ( $col2 . $start_row, $relative->getJob() );
					}
					
					if($relative->getMobile() != ''){
						array_push($array_phone,$relative->getMobile());
					}
					unset($list_relative[$key]);
				}
				
			}
			
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'J' . $start_row, implode(' - ',$array_phone) );
			
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'K' . $start_row, '' );
			
			$stt ++;
			
			$start_row = $start_row + 1;
			//$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
			
		}
		
	}
	public function xuatSoDiemDanhTheoThang($list_student,$array_month,$list_logtimes) {
		
		$tieudeSDD = $this->object->getContext () ->getI18N () ->__ ( 'II - THEO DOI TRE DEN LOP' );
		$text_month = $this->object->getContext () ->getI18N () ->__ ( 'Month' );
		$chuyen_can = $this->object->getContext () ->getI18N () ->__ ( 'Chuyen can %' );
		$student_name = $this->object->getContext () ->getI18N () ->__ ( 'Student name' );
		$cuoithang = $this->object->getContext () ->getI18N () ->__ ( 'Cuoi thang' );
		$tongsongay = $this->object->getContext () ->getI18N () ->__ ( 'TS ngay' );
		$tongsongayan = $this->object->getContext () ->getI18N () ->__ ( 'TS ngay an' );
		$tongsotredihoc = $this->object->getContext () ->getI18N () ->__ ( 'Tong so tre di hoc' );
		$tongsotrebaoan = $this->object->getContext () ->getI18N () ->__ ( 'Tong so tre bao an' );
		
		$start_row = 0;
		$index = 1;
		
		$allborder = array( 'allborders' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '333')));
		
		$noneborder = array( 'allborders' => array( 'style' => PHPExcel_Style_Border::BORDER_NONE ));
		
		$alignment_right = array ( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
		
		$alignment_left = array ( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
		
		$alignment_center = array ( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		
		foreach ($array_month as $month){
			
			$number_month = date('t',strtotime('01-'.$month));
			$start_row ++;
			
			$this->objPHPExcel->getActiveSheet () ->getRowDimension($start_row)->setRowHeight(-1);
			
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "A" . $start_row . ":AI" . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $tieudeSDD );
			
			$this->objPHPExcel->getActiveSheet ()->getStyle("A" . $start_row . ":AI" . $start_row)->applyFromArray(array('borders' =>$noneborder));
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "A" . $start_row . ":AD" . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $text_month.' '.$month );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "AE" . $start_row . ":AI" . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'AE' . $start_row, $chuyen_can);
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'AE' . $start_row ) ->applyFromArray ( array ( 'alignment' => $alignment_right) );
			
			$start_row = $start_row + 1;
			//$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, 'STT');
			$this->objPHPExcel->getActiveSheet () ->getStyle ( 'A' . ($start_row) ) ->applyFromArray ( array ( 'alignment' => $alignment_center) );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . $start_row, $student_name);
			
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "AH" . $start_row . ":AI" . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'AH' . $start_row, $cuoithang);
			
			$this->objPHPExcel->getActiveSheet ()->getStyle("A" . $start_row . ":AI" . $start_row)->applyFromArray(array ( 'borders' => $allborder));
			
			$start_row = $start_row + 1;
			
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "A" . ($start_row - 1). ":A" . ($start_row) );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "B" . ($start_row - 1) . ":B" . ($start_row) );
			
			for ($i=1; $i<=$number_month; $i++){
				
				$col = PHPExcel_Cell::stringFromColumnIndex ( $index + $i );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( $col . ($start_row - 1), substr( date('l',strtotime($i.'-'.$month)),  0, 3 ) );
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( $col . ($start_row), $i );
				
			}
			
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'AH' . $start_row, $tongsongay);
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'AI' . $start_row, $tongsongayan);
			
			
			$start_row = $start_row + 1;
			//$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			
			$batdau = $start_row;
			$stt = 1;
			foreach ( $list_student as $student ) {
				
				$tongsongaydihoc = 0;
				
				$student_id = $student->getId();
				
				$this->objPHPExcel->getActiveSheet () ->getStyle ( 'A' . $start_row ) ->applyFromArray ( array ( 'alignment' => $alignment_center) );
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $start_row, $stt );
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $start_row, $student->getStudentName() );
				
				foreach ($list_logtimes as $key=> $logtimes){
					
					$log_value = '';
					if($month == date('m-Y',strtotime($logtimes->getLoginAt())) && $student_id == $logtimes->getStudentId()){
						
						for ($j=1; $j<=$number_month; $j++){
							
							if(date('dmY',strtotime($j.'-'.$month)) == date('dmY',strtotime($logtimes->getLoginAt()))){
								
								if($logtimes->getLogValue() == 1){
									$tongsongaydihoc ++;
									$log_value = 'x';
								}elseif($logtimes->getLogValue() == 2){
									$log_value = 'k';
								}elseif($logtimes->getLogValue() == 0){
									$log_value = 'p';
								}
								
								$col2 = PHPExcel_Cell::stringFromColumnIndex ( $index + $j );
								$this->objPHPExcel->getActiveSheet () ->setCellValue ( $col2 . ($start_row), $log_value );
								
							}
							
						}
						
						unset($list_logtimes[$key]);
						
					}
					
				}
				if($tongsongaydihoc > 0){
					$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'AH' . $start_row, $tongsongaydihoc );
					$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'AI' . $start_row, '' );
				}
				$stt ++;
				
				$start_row = $start_row + 1;
				
				//$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
				
			}
			
			$start_row = $start_row + (37 - $stt);
			
			//$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "A" . $start_row . ":B" . $start_row );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $start_row, $tongsotredihoc );
			
			$symbolCode = 'x';
			for ($k=1; $k<=$number_month; $k++){
				
				$col3 = PHPExcel_Cell::stringFromColumnIndex ( $index + $k );
				
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( $col3 . $start_row, "=COUNTIF(".$col3.$batdau.":".$col3.$start_row.",\"{$symbolCode}\")" );
				
			}
			
			$this->objPHPExcel->getActiveSheet ()->getStyle("A" . $start_row . ":AI" . $start_row)->applyFromArray(array('alignment' => $alignment_center,'borders' =>$allborder));
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "A" . $start_row . ":B" . $start_row );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $start_row, $tongsotrebaoan );
			
			$this->objPHPExcel->getActiveSheet () ->setBreak ( 'A' . $start_row, PHPExcel_Worksheet::BREAK_ROW );
		}
		
	}

	public function xuatThongTinYTeSucKhoe($list_student, $psExamination, $psStudentGrowths) {
		
		$tieudeYte = $this->object->getContext () ->getI18N () ->__ ( 'III - THEO DOI SUC KHOE CUA TRE' );
		$thongtinkhamsuckhoe = $this->object->getContext () ->getI18N () ->__ ( 'Thong tin kham suc khoe' );
		$tremaccacbenh = $this->object->getContext () ->getI18N () ->__ ( 'Tre mac cac benh' );
		$student_name = $this->object->getContext () ->getI18N () ->__ ( 'Student name' );
		$cannang = $this->object->getContext () ->getI18N () ->__ ( 'Can nang' );
		$chieucao = $this->object->getContext () ->getI18N () ->__ ( 'Chieu cao' );
		$kenhbinhthuong = $this->object->getContext () ->getI18N () ->__ ( 'Kenh binh thuong' );
		$sddnhecan = $this->object->getContext () ->getI18N () ->__ ( 'SDD the nhe can' );
		$thuacanbeophi = $this->object->getContext () ->getI18N () ->__ ( 'Thua can beo phi' );
		$sddthapcoi = $this->object->getContext () ->getI18N () ->__ ( 'SDD the thap coi' );
		$sddgaycom = $this->object->getContext () ->getI18N () ->__ ( 'SDD the gay com' );
		$khamtim = $this->object->getContext () ->getI18N () ->__ ( 'Kham tim' );
		$Khamphoi = $this->object->getContext () ->getI18N () ->__ ( 'Kham phoi' );
		$Khamda = $this->object->getContext () ->getI18N () ->__ ( 'Kham da' );
		$note = $this->object->getContext () ->getI18N () ->__ ( 'Note' );
		$tile = $this->object->getContext () ->getI18N () ->__ ( 'Ti le' );
		$tongso = $this->object->getContext () ->getI18N () ->__ ( 'Tong so' );
		
		$start_row = 0;
		$index = 1;
		
		// 		$bottom_dotted = array( 'bottom' => array( 'style' => PHPExcel_Style_Border::BORDER_DOTTED ));
		
		$allborder = array( 'allborders' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN));
		
		$noneborder = array( 'allborders' => array( 'style' => PHPExcel_Style_Border::BORDER_NONE ));
		
		$alignment_right = array ( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
		
		$alignment_left = array ( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
		
		$alignment_center = array ( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		
		foreach ($psExamination as $examination){
			
			$start_row ++;
			
			$this->objPHPExcel->getActiveSheet ()->getStyle("A" . $start_row . ":N" . $start_row)->applyFromArray(array('borders' =>$noneborder));
			
			$this->objPHPExcel->getActiveSheet () ->getRowDimension($start_row)->setRowHeight(-1);
			
			$this->objPHPExcel->getActiveSheet ()->getStyle("A" . $start_row . ":N" . $start_row)->applyFromArray(array ( 'alignment' => $alignment_left));
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "A" . $start_row . ":N" . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $tieudeYte );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "A" . $start_row . ":N" . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $thongtinkhamsuckhoe );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet ()->getStyle("A" . $start_row . ":N" . $start_row)->applyFromArray(array ( 'alignment' => $alignment_center,'borders'=>$allborder));
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "C" . $start_row . ":H" . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'C' . $start_row, $examination->getName() );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "I" . $start_row . ":N" . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'I' . $start_row, $tremaccacbenh );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "C" . $start_row . ":E" . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'C' . $start_row, $cannang );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "F" . $start_row . ":G" . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . $start_row, $chieucao );
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "A" . ($start_row - 2 ) . ":A" . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . ($start_row - 2 ), 'STT' );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "B" . ($start_row - 2 ) . ":B" . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . ($start_row - 2 ), $student_name );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'C' . $start_row, $kenhbinhthuong );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . $start_row, $sddnhecan );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $thuacanbeophi );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . $start_row, $kenhbinhthuong );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, $sddthapcoi );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "H" . ($start_row - 1 ) . ":H" . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . ($start_row - 1 ), $sddgaycom );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "I" . ($start_row - 1 ) . ":I" . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'I' . ($start_row - 1 ), $khamtim );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "J" . ($start_row - 1 ) . ":J" . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'J' . ($start_row - 1 ), $Khamphoi );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "K" . ($start_row - 1 ) . ":K" . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'K' . ($start_row - 1 ), 'TMH' );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "L" . ($start_row - 1 ) . ":L" . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'L' . ($start_row - 1 ), 'RHM' );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "M" . ($start_row - 1 ) . ":M" . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'M' . ($start_row - 1 ), $Khamda );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "N" . ($start_row - 1 ) . ":N" . $start_row );
			$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'N' . ($start_row - 1 ), $note );
			
			$start_row = $start_row + 1;
			//$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
			
			$stt = 1;
			foreach ( $list_student as $student ) {
				
				$student_id = $student->getId();
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $start_row, $stt );
				
				$this->objPHPExcel->getActiveSheet ()->getStyle("A" . $start_row)->applyFromArray(array('alignment' => $alignment_center));
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $start_row, $student->getStudentName() );
				
				foreach ($psStudentGrowths as $key=>$psStudentGrowth){
					
					if($student_id == $psStudentGrowth->getStudentId() && $examination->getId() == $psStudentGrowth->getExaminationId()){
						
						if($psStudentGrowth->getIndexWeight() == 0){
							$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'C' . $start_row, $psStudentGrowth->getWeight() );
						}elseif($psStudentGrowth->getIndexWeight() < 0){
							$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'D' . $start_row, $psStudentGrowth->getWeight() );
						}else{
							$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'E' . $start_row, $psStudentGrowth->getWeight() );
						}
						
						if($psStudentGrowth->getIndexHeight() == 0){
							$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'F' . $start_row, $psStudentGrowth->getHeight() );
						}elseif($psStudentGrowth->getIndexHeight() < 0){
							$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'G' . $start_row, $psStudentGrowth->getHeight() );
						}
						
						if($psStudentGrowth->getIndexHeight() < 0 && $psStudentGrowth->getIndexWeight() < 0){
							$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'H' . $start_row, 'x' );
						}
						
						$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'I' . $start_row, $psStudentGrowth->getIndexHeart() );
						$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'J' . $start_row, $psStudentGrowth->getIndexLung() );
						$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'K' . $start_row, $psStudentGrowth->getIndexThroat() );
						$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'L' . $start_row, $psStudentGrowth->getIndexTooth() );
						$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'M' . $start_row, $psStudentGrowth->getIndexSkin() );
						$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'N' . $start_row, $psStudentGrowth->getNote() );
						
						unset($psStudentGrowths[$key]);
						
					}
					
				}
				
				$stt ++;
				
				$start_row = $start_row + 1;
				
				//$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
				
			}
			
			$start_row = $start_row + (36 - $stt);
			
			$start_row = $start_row + 1;
			//$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "A" . $start_row . ":B" . $start_row );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $start_row, $tongso );
			
			$this->objPHPExcel->getActiveSheet ()->getStyle("A" . $start_row . ":N" . $start_row)->applyFromArray(array('alignment' => $alignment_center,'borders' =>$allborder));
			
			$start_row = $start_row + 1;
			$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
			$this->objPHPExcel->getActiveSheet () ->mergeCells ( "A" . $start_row . ":B" . $start_row );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $start_row, $tile );
			
			$this->objPHPExcel->getActiveSheet () ->setBreak ( 'A'.$start_row, PHPExcel_Worksheet::BREAK_ROW );
		
		}
	}

	public function xuatChiSoDanhGiaTre($list_student,$psEvaluateSubject,$psEvaluateIndexSymbol,$psEvaluateIndexCriteria,$psEvaluateIndexStudent,$array_month){
		
		$tieudeDanhGia = $this->object->getContext () ->getI18N () ->__ ( 'IV - THEO DOI DANH GIA SU PHAT TRIEN CUA TRE' );
		$thongtinthang= $this->object->getContext () ->getI18N () ->__ ( 'Danh gia su phat trien cua tre trong thang :' );
		$linhvucgiaoduc = $this->object->getContext () ->getI18N () ->__ ( 'Linh vuc giao duc: ' );
		$student_name = $this->object->getContext () ->getI18N () ->__ ( 'Student name' );
		$muctieudanhgia = $this->object->getContext () ->getI18N () ->__ ( 'Muc tieu danh gia' );
		$xeploaichung = $this->object->getContext () ->getI18N () ->__ ( 'Xep loai chung' );
		$tile = $this->object->getContext () ->getI18N () ->__ ( 'Ti le' );
		$tongso = $this->object->getContext () ->getI18N () ->__ ( 'Tong so' );
		
		$start_row = 0;
		$index = 1;
		$number_colum = 26;
		$array_symbol = array();
		
		foreach ($psEvaluateIndexSymbol as $symbol){
			array_push($array_symbol, $symbol->getSymbolCode());
		}
		
		$number_symbol = count($psEvaluateIndexSymbol);
		
		$allborder = array( 'allborders' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '333')));
		
		$noneborder = array( 'allborders' => array( 'style' => PHPExcel_Style_Border::BORDER_NONE ));
		
		$alignment_right = array ( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
		
		$alignment_left = array ( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
		
		$alignment_center = array ( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		
		foreach ($array_month as $month){
		
			foreach ($psEvaluateSubject as $evaluateSubject){
				
				$start_row ++;
				
				$this->objPHPExcel->getActiveSheet ()->getStyle("A" . $start_row . ":AA" . $start_row)->applyFromArray(array('borders' =>$noneborder));
				
				$this->objPHPExcel->getActiveSheet () ->getRowDimension($start_row)->setRowHeight(-1);
				
				$this->objPHPExcel->getActiveSheet ()->getStyle("A" . $start_row . ":AA" . $start_row)->applyFromArray(array ( 'alignment' => $alignment_left));
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( "A" . $start_row . ":AA" . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $tieudeDanhGia );
				
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()->getStyle("A" . $start_row . ":AA" . $start_row)->applyFromArray(array('borders' =>$noneborder));
				//$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( "A" . $start_row . ":AA" . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . $start_row, $thongtinthang.' '.$month );
				
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				$col = PHPExcel_Cell::stringFromColumnIndex ( $number_colum - $number_symbol );
				$this->objPHPExcel->getActiveSheet ()->getStyle("A" . $start_row . ":AA" . $start_row)->applyFromArray(array ( 'borders'=>$allborder));
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( "C" . $start_row . ":".$col . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'C' . $start_row, $linhvucgiaoduc.$evaluateSubject->getTitle() );
				$this->objPHPExcel->getActiveSheet ()->getStyle("C" . $start_row )->applyFromArray(array ( 'alignment' => $alignment_left));
				
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				$this->objPHPExcel->getActiveSheet ()->getStyle("A" . $start_row . ":AA" . $start_row)->applyFromArray(array ( 'alignment' => $alignment_center));
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( "C" . $start_row . ":".$col . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'C' . $start_row, $muctieudanhgia );
				
				$col2 = PHPExcel_Cell::stringFromColumnIndex ( $number_colum - $number_symbol + 1 );
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( $col2 . ($start_row -1 ) . ":AA". $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( $col2 . ($start_row -1 ), $xeploaichung );
				
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				
				$array_criteria = array();
				$dem = 0;
				
				foreach ($psEvaluateIndexCriteria as $key=> $evaluateIndexCriteria){
					
					if($evaluateSubject->getId() == $evaluateIndexCriteria->getEsId()){
						
						$dem ++;
						$col4 = PHPExcel_Cell::stringFromColumnIndex ( 1 + $dem );
						$this->objPHPExcel->getActiveSheet () ->setCellValue ( $col4 . $start_row, $evaluateIndexCriteria->getCriteriaCode() );
						
						array_push($array_criteria, $evaluateIndexCriteria->getId());
						
					}
					
				}
				
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( "A" . ($start_row - 2) . ":A" . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'A' . ($start_row - 2), 'STT' );
				
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( "B" . ($start_row - 2) . ":B" . $start_row );
				$this->objPHPExcel->getActiveSheet () ->setCellValue ( 'B' . ($start_row - 2), $student_name );
				
				for ($i=0; $i<$number_symbol; $i++){
					$col3 = PHPExcel_Cell::stringFromColumnIndex ( $number_colum - $number_symbol + $i + 1 );
					$this->objPHPExcel->getActiveSheet () ->setCellValue ( $col3 . ($start_row), $array_symbol[$i] );
				}
				
				$start_row = $start_row + 1;
				//$this->objPHPExcel->getActiveSheet () ->insertNewRowBefore ( $start_row, 1 );
				
				$stt = 1;
				foreach ( $list_student as $student ) {
					
					$tongsongaydihoc = 0;
					
					$student_id = $student->getId();
					
					$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $start_row, $stt );
					$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $start_row, $student->getStudentName() );
					$this->objPHPExcel->getActiveSheet ()->getStyle("A" . $start_row )->applyFromArray(array ( 'alignment' => $alignment_center));
					$chiso = 0;
					foreach ($psEvaluateIndexStudent as $key2=>$evaluateIndexStudent){
						for ($j=0; $j<count($array_criteria); $j++){
							if($month == date('m-Y', strtotime($evaluateIndexStudent->getDateAt())) && $evaluateIndexStudent->getStudentId() == $student_id && $array_criteria[$j] == $evaluateIndexStudent->getEvaluateIndexCriteriaId() ){
								$chiso = 1;
								$col5 = PHPExcel_Cell::stringFromColumnIndex ( 2 + $j );
								$this->objPHPExcel->getActiveSheet () ->setCellValue ( $col5 . $start_row, $evaluateIndexStudent->getSymbolCode() );
								unset($psEvaluateIndexStudent[$key2]);
							}
						}
					}
					
					if($chiso == 1){
						for ($ii=0; $ii<$number_symbol; $ii++){
							$col6 = PHPExcel_Cell::stringFromColumnIndex ( $number_colum - $number_symbol + $ii + 1 );
							$col7 = PHPExcel_Cell::stringFromColumnIndex ( $number_colum - $number_symbol + 1 );
							$this->objPHPExcel->getActiveSheet () ->setCellValue ( $col6 . $start_row, "=COUNTIF(".'C'.$start_row.":".$col7.$start_row.",\"{$array_symbol[$ii]}\")"  );
						}
					}
					
					$stt ++;
					$start_row = $start_row + 1;
					//$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
					
				}
				
				$start_row = $start_row + (36 - $stt);
				
				$start_row = $start_row + 1;
				//$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( "A" . $start_row . ":B" . $start_row );
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $start_row, $tongso );
				
				$this->objPHPExcel->getActiveSheet ()->getStyle("A" . $start_row . ":AA" . $start_row)->applyFromArray(array('alignment' => $alignment_center,'borders' =>$allborder));
				
				$start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( "A" . $start_row . ":B" . $start_row );
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $start_row, $tile );
				
				$this->objPHPExcel->getActiveSheet () ->setBreak ( 'A'.$start_row, PHPExcel_Worksheet::BREAK_ROW );
				
			}
			
			$this->objPHPExcel->getActiveSheet () ->setBreak ( 'A'.$start_row, PHPExcel_Worksheet::BREAK_ROW );
			
		}
	}
	
	/** Có thể bỏ inssert row **/
	public function setDataExportStudentSynthetic($list_student, $list_service, $list_receivables, $ps_month, $ConfigStartDateSystemFee) {

		$date_ExportReceipt = $receivable_at = '01-' . $ps_month;
		$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );

		$date = date_create ( $date_ExportReceipt );
		date_modify ( $date, "-1 month" );
		$receiptPrevDate = date_format ( $date, "Y-m-d" );

		$array_service = $array_receivable = array ();
		// cac dich vu
		$start_service = 5;
		$service_col = 17;
		$index2 = 0;
		foreach ( $list_service as $service ) {

			// array_push($array_service, $service->getId());

			$col = PHPExcel_Cell::stringFromColumnIndex ( $service_col + $index2 );
			$col2 = PHPExcel_Cell::columnIndexFromString ( $col );

			$row = $start_service;

			if ($service->getEnableRoll () == PreSchool::ACTIVE) { // neu dich vu la co dinh
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row, $service->getTitle () );
				$this->objPHPExcel->getActiveSheet () ->mergeCells ( $col . $row . ':' . $col . ($row+2) );

				$index2 = $index2 + 1;
			} else {

				$col3 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 6 );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( $col . $row . ':' . $col3 . $row );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col . $row, $service->getTitle () );

				$row1 = $start_service + 1;

				$col_3 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 2 );
				$col4 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 3 );
				$col_4 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 6 );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( $col . $row1 . ':' . $col_3 . $row1 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col . $row1, $this->object->getContext ()
					->getI18N ()
					->__ ( 'Last month' ) );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( $col4 . $row1 . ':' . $col_4 . $row1 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col4 . $row1, $this->object->getContext ()
					->getI18N ()
					->__ ( 'This month' ) );

				$row2 = $start_service + 2;
				// thang truoc
				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 - 1 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, $this->object->getContext ()
					->getI18N ()
					->__ ( 'Price' ) );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, 'Vé có' );

				
				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 1 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, 'Đã SD' );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 2 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, $this->object->getContext ()
					->getI18N ()
					->__ ( 'Vé còn' ) );

				// thang nay

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 3 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, $this->object->getContext ()
					->getI18N ()
					->__ ( 'Price' ) );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 4 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, 'SL DK' );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 5 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, $this->object->getContext ()
					->getI18N ()
					->__ ( 'Last month amount' ) );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 6 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, $this->object->getContext ()
					->getI18N ()
					->__ ( 'Phai thu' ) );

				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getAlignment ()
					->setWrapText ( true );
				$index2 = $index2 + 8;
			}
		}
		// cac khoan phai thu khac
		$index3 = 0;

		$number_receivable = count ( $list_receivables );

		$col5 = PHPExcel_Cell::columnIndexFromString ( $col3 );
		$col6 = PHPExcel_Cell::stringFromColumnIndex ( $col5 );
		$col7 = PHPExcel_Cell::stringFromColumnIndex ( $col5 + $number_receivable );

		$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( $col6 . $start_service . ':' . $col7 . $start_service );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( $col6 . $start_service, $this->object->getContext ()
			->getI18N ()
			->__ ( 'List receivables' ) );

		foreach ( $list_receivables as $receivables ) {

			array_push ( $array_receivable, $receivables->getId () );

			$col = PHPExcel_Cell::stringFromColumnIndex ( $col5 + $index3 );

			$row = $start_service + 2;

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col . $row, $receivables->getTitle () );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getAlignment ()
				->setWrapText ( true );
			$index3 ++;
		}

		$col = PHPExcel_Cell::stringFromColumnIndex ( $service_col + $index2 + $index3 );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( $col . $row, $this->object->getContext ()
			->getI18N ()
			->__ ( 'Is late' ) );
		// danh sach hoc sinh
		$start_row = 8; $start_fix = 8;
		$index = 0;
		$number_student = count ( $list_student );

		foreach ( $list_student as $key => $student ) {

			$student_id = $student->getId ();

			$hoanTra = $collectedAmount = $balanceAmount = $thucte = $totalAmount = $balance_last_month_amount = 0;

			$payment_date = $payment_type = $chietkhau = '';

			$receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableAndServiceStudentOfMonth ( $student_id, $date_ExportReceipt, $receiptPrevDate );

			$receipt = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $int_date_ExportReceipt );
			
			$tbThuThangNay = $thucTeThuThangNay = $duCuoiKy = $collectedAmount = $balance_last_month_amount = $balanceAmount = $chietkhau = '';
			
			if ($receipt) { // hoc sinh co phieu

				// Tiền thừa tháng trước - dư nợ đầu kỳ
				$balance_last_month_amount = $receipt->getBalanceLastMonthAmount ();

				// Số tiền đã than toán
				$collectedAmount = $receipt->getCollectedAmount (); 
				
				// Số tiền phải thu = đã TT - dư nợ + nợ đầu kỳ
				$balanceAmount = $collectedAmount - $receipt->getBalanceAmount() + $balance_last_month_amount;

				//$thucte = $collectedAmount - $receipt->getBalanceAmount ();

				$payment_type = $receipt->getPaymentType();

				$payment_date = $receipt->getPaymentDate();
				$chietkhau = $receipt->getChietkhau();
				$hoantra = $receipt->getHoantra();
				
				// Số tiền thông báo thu của tháng
				//$thongBaoThu = $thucte + $chietkhau + $hoantra + $balance_last_month_amount;
				
				// Dư nợ cuối kỳ + đã thanh toán
				$tbThuThangNay = $receipt->getBalanceAmount() + $collectedAmount - $chietkhau - $balance_last_month_amount;
				$thucTeThuThangNay = $tbThuThangNay + $balance_last_month_amount+$chietkhau;
				
				$duCuoiKy = $thucTeThuThangNay - $collectedAmount;
				
			}

			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $start_row, $key + 1 );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $start_row, $student->getStudentCode () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $start_row, $student->getStudentName () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $start_row, date ( 'd-m-Y', strtotime ( $student->getBirthday () ) ) );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $start_row, date ( 'd-m-Y', strtotime ( $student->getStartDateAt () ) ) );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $start_row, $student->getClassName () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $start_row, $student->getPolicyCode () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $start_row, $student->getStudentStatus () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'I' . $start_row, $balance_last_month_amount ); // Nợ đầu kỳ
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'J' . $start_row, $tbThuThangNay ); // Thông báo thu

			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'K' . $start_row, $hoantra ); // Hoàn trả
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'L' . $start_row, $chietkhau ); // Chiết khấu sau thông báo

			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'M' . $start_row, $thucTeThuThangNay ); // Thực tế thu tháng này
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'N' . $start_row, $collectedAmount ); // Đã thu tiền
			
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'O' . $start_row, ($duCuoiKy) ); // Dư cuối kỳ
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'P' . $start_row, $payment_date );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'Q' . $start_row, $payment_type );

			$index2 = $dichvucodinh = $dichvukocodinh = 0;
			$array_receivable_student = array ();
			$hoantra = 0;
			foreach ( $list_service as $service ) {

				$service_id = $service->getId ();

				$col = PHPExcel_Cell::stringFromColumnIndex ( $service_col + $index2 );
				$col2 = PHPExcel_Cell::columnIndexFromString ( $col );

				if ($service->getEnableRoll () == PreSchool::ACTIVE) { // Neu la dich vu co dinh
					
					foreach ( $receivable_student as $key => $student_service ) {
						if (date ( 'Ym', strtotime ( $student_service->getRsReceiptDate () ) ) == date ( 'Ym', strtotime ( $date_ExportReceipt ) )) {
							if ($service_id == $student_service->getRsServiceId ()) {

								if ($student_service->getRsUnitPrice () != 0) {
									
									$rs_amount = ($student_service->getRsDiscount () > 0) ? ((100 - $student_service->getRsDiscount ()) * $student_service->getRsUnitPrice () * $student_service->getRsByNumber ()) / 100 : $student_service->getRsUnitPrice () * $student_service->getRsByNumber ();
									$rs_amount = $rs_amount - ( float ) $student_service->getRsDiscountAmount ();

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2-1);
									
									$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col5 . $start_row, $rs_amount );

									$hoantra = $hoantra + $student_service->getHoantra();
								}
								unset($receivable_student[$key]);
							}
						}
					}
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( $col . $start_row )
						->getAlignment ()
						->setWrapText ( true );
					$index2 = $index2 + 1;
					
					$dichvucodinh++;
					
				} else {
					$thangtruocchuyensang = 0;
					foreach ( $receivable_student as $key2=> $student_service ) {
						//$thangtruocchuyensang = 0;
						if ($service_id == $student_service->getRsServiceId ()) {

							if ($student_service->getRsUnitPrice () != 0) {

								if (date ( 'Ym', strtotime ( $student_service->getRsReceiptDate () ) ) == date ( 'Ym', strtotime ( $receiptPrevDate ) )) { // thang truoc
									
									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 - 1 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $student_service->getRsUnitPrice () );

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $student_service->getRsByNumber () );
									
									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 1 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $student_service->getRsSpentNumber () );

									$rs_amount = ($student_service->getRsDiscount () > 0) ? ((100 - $student_service->getRsDiscount ()) * $student_service->getRsUnitPrice () * $student_service->getRsByNumber ()) / 100 : $student_service->getRsUnitPrice () * $student_service->getRsByNumber ();
									$rs_amount = $rs_amount - ( float ) $student_service->getRsDiscountAmount ();

									$thangtruocchuyensang = $rs_amount - $student_service->getRsAmount ();

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 2 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $student_service->getRsByNumber() - $student_service->getRsSpentNumber());
									
								}
								if (date ( 'Ym', strtotime ( $student_service->getRsReceiptDate () ) ) == date ( 'Ym', strtotime ( $date_ExportReceipt ) )) {

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 3 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $student_service->getRsUnitPrice () );

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 4 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $student_service->getRsByNumber () );
									
									$rs_amount = ($student_service->getRsDiscount () > 0) ? ((100 - $student_service->getRsDiscount ()) * $student_service->getRsUnitPrice () * $student_service->getRsByNumber ()) / 100 : $student_service->getRsUnitPrice () * $student_service->getRsByNumber ();
									$rs_amount = $rs_amount - ( float ) $student_service->getRsDiscountAmount ();

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 5 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $thangtruocchuyensang );

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 6 );
									$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col5 . $start_row, $rs_amount - $thangtruocchuyensang );

								}
							}
							unset($receivable_student[$key2]);
						}
					}

					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( $col . $start_row )
						->getAlignment ()
						->setWrapText ( true );
					$index2 = $index2 + 8;
					$dichvukocodinh ++;
				}
			}

			$index3 = 0;

			foreach ( $array_receivable as $receivable_id ) {

				$col = PHPExcel_Cell::stringFromColumnIndex ( $service_col + $index2 + $index3 );

				foreach ( $receivable_student as $student_service ) {

					if ($receivable_id == $student_service->getRsReceivableId () && date ( 'Ym', strtotime ( $student_service->getRsReceiptDate () ) ) == date ( 'Ym', strtotime ( $date_ExportReceipt ) )) {

						$this->objPHPExcel->getActiveSheet ()
							->setCellValue ( $col . $start_row, $student_service->getRsAmount () );
					}
					if ($student_service->getRsIsLate () == PreSchool::ACTIVE) {
						$is_late = $student_service->getRsAmount ();
					}
				}

				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $start_row )
					->getAlignment ()
					->setWrapText ( true );
				$index3 ++;
			}

			$col = PHPExcel_Cell::stringFromColumnIndex ( $service_col + $index2 + $index3 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col . $start_row, $is_late );

			$start_row = $start_row + 1;
			if ($index < $number_student) {
				//$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
			}
		}
		
		$start_end = $start_row;
		$start_row = $start_row + 1;
		//$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
		$start_row = $start_row + 1;
		
		$this->objPHPExcel->getActiveSheet ()->mergeCells ( 'A' . $start_row . ':H'. $start_row);
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $start_row, "Tổng" );
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'I' . $start_row, "=SUM(I".$start_fix.":I".$start_end.")" );
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'J' . $start_row, "=SUM(J".$start_fix.":J".$start_end.")" );
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'K' . $start_row, "=SUM(K".$start_fix.":K".$start_end.")" );
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'L' . $start_row, "=SUM(L".$start_fix.":L".$start_end.")" );
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'M' . $start_row, "=SUM(M".$start_fix.":M".$start_end.")" );
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'N' . $start_row, "=SUM(N".$start_fix.":N".$start_end.")" );
		
		for($i = 1; $i<=$dichvucodinh; $i++){
			$index2 = $i;
			$col1 = PHPExcel_Cell::stringFromColumnIndex ( ($service_col - 1) + $index2 );
			//$col2 = PHPExcel_Cell::columnIndexFromString ( $col1 );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col1 . $start_row, "=SUM(".$col1.$start_fix.":".$col1.$start_end.")" );
		}
		for($j = 1; $j<= $dichvukocodinh; $j++){
			$index5 = $j*8;
			$col20 = PHPExcel_Cell::stringFromColumnIndex ( ($service_col - 1) + ($dichvucodinh) + $index5 );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col20 . $start_row, "=SUM(".$col20.$start_fix.":".$col20.$start_end.")" );
		}
		
		for($k = 1; $k<= $index3+1; $k++){
			$col21 = PHPExcel_Cell::stringFromColumnIndex ( ($service_col - 1) + ($dichvucodinh) + ($dichvukocodinh*8) + $k );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col21 . $start_row, "=SUM(".$col21.$start_fix.":".$col21.$start_end.")" );
		}
		
		$this->objPHPExcel->getActiveSheet ()->removeRow ( ($start_end), 1);
	}

	// Xuất biểu mẫu số dư đầu kỳ để import
	public function setDataExportAmountLastMonth2($list_student, $list_service, $list_receivables, $ps_month, $ConfigStartDateSystemFee) {

		$date_ExportReceipt = $receivable_at = '01-' . $ps_month;
		$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );

		$date = date_create ( $date_ExportReceipt );
		date_modify ( $date, "-1 month" );
		$receiptPrevDate = date_format ( $date, "Y-m-d" );

		$array_service = $array_receivable = array ();
		// cac dich vu
		$start_service = 5;
		$start_col_service = 11;
		$index2 = 0;
		foreach ( $list_service as $service ) {

			// array_push($array_service, $service->getId());

			$col = PHPExcel_Cell::stringFromColumnIndex ( $start_col_service + $index2 );
			$col2 = PHPExcel_Cell::columnIndexFromString ( $col );

			$row = $start_service;

			if ($service->getEnableRoll () == PreSchool::ACTIVE) { // neu dich vu la co dinh

				$col3 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 2 );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( $col . $row . ':' . $col3 . $row );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col . $row, $service->getTitle () );

				$this->objPHPExcel->getActiveSheet ()->mergeCells ( $col . ($row+1) . ':' . $col3 . ($row+1)  );
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . ($row+1) , $service->getId () );

				$this->objPHPExcel->getActiveSheet ()->mergeCells ( $col . ($row+2) . ':' . $col3 . ($row+2)  );
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . ($row+2) , 1 );

				$row2 = $start_service + 3;

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 - 1 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, $this->object->getContext ()
					->getI18N ()
					->__ ( 'Price' ) );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, 'SL' );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 1 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, $this->object->getContext ()
					->getI18N ()
					->__ ( 'GT' ) );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 2 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row2, $this->object->getContext ()
					->getI18N ()
					->__ ( 'Phai thu' ) );

				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getAlignment ()
					->setWrapText ( true );
				$index2 = $index2 + 4;
			} else {

				$col3 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 2 );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( $col . $row . ':' . $col3 . $row );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col . $row, $service->getTitle () );

				$row1 = $start_service + 1;
				$row2 = $start_service + 2;

				$col_3 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 2 );
				
				$this->objPHPExcel->getActiveSheet ()->mergeCells ( $col . $row1 . ':' . $col_3 . $row1 );
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row1, $service->getId() );

				$this->objPHPExcel->getActiveSheet ()->mergeCells ( $col . ($row1+1) . ':' . $col_3 . ($row1+1) );
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . ($row1+1), 2 );

				$row3 = $start_service + 3;
				// thang truoc
				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 - 1 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row3, $this->object->getContext ()
					->getI18N ()
					->__ ( 'Price' ) );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row3, 'SL DK' );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 1 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row3, 'SL SD' );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 2 );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( $col5 . $row3, $this->object->getContext ()
					->getI18N ()
					->__ ( 'Da thu' ) );

				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $row )
					->getAlignment ()
					->setWrapText ( true );
				$index2 = $index2 + 4;
			}
		}
		// cac khoan phai thu khac
		$index3 = 0;

		$number_receivable = count ( $list_receivables );

		$col5 = PHPExcel_Cell::columnIndexFromString ( $col3 );
		$col6 = PHPExcel_Cell::stringFromColumnIndex ( $col5 );
		$col7 = PHPExcel_Cell::stringFromColumnIndex ( $col5 + $number_receivable );
		/*
		$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( $col6 . $start_service . ':' . $col7 . $start_service );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( $col6 . $start_service, $this->object->getContext ()
			->getI18N ()
			->__ ( 'List receivables' ) );
		*/
		foreach ( $list_receivables as $receivables ) {

			$row = $start_service;

			array_push ( $array_receivable, $receivables->getId () );

			$col = PHPExcel_Cell::stringFromColumnIndex ($start_col_service + $index2 + $index3 );
			$col2 = PHPExcel_Cell::columnIndexFromString ( $col );
			$col10 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 2 );
			
			$this->objPHPExcel->getActiveSheet ()->mergeCells ( $col . $row . ':' . $col10 . $row );
			
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col . $row, $receivables->getTitle () );

			$this->objPHPExcel->getActiveSheet ()->mergeCells ( $col . ($row+1) . ':' . $col10 . ($row+1)  );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . ($row+1) , $receivables->getId () );

			$this->objPHPExcel->getActiveSheet ()->mergeCells ( $col . ($row+2) . ':' . $col10 . ($row+2)  );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . ($row+2) , 3 );

			$row2 = $start_service + 3;
			
			$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 - 1 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col5 . $row2, $this->object->getContext ()
				->getI18N ()
				->__ ( 'Price' ) );

			$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col5 . $row2, 'SL' );

			$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 1 );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col5 . $row2, $this->object->getContext ()
				->getI18N ()
				->__ ( 'GT' ) );

			$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 2 );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col5 . $row2, $this->object->getContext ()
				->getI18N ()
				->__ ( 'Phai thu' ) );
			
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getAlignment ()
				->setWrapText ( true );
			$index3 = $index3 + 4;
			
			/*
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row, $receivables->getTitle () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . ($row+1), $receivables->getId () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . ($row+2), 3 ); // Kiểu khoản thu khác

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getAlignment ()
				->setWrapText ( true );
			$index3 ++;
			*/
		}

		$col = PHPExcel_Cell::stringFromColumnIndex ( $start_col_service + $index2 + $index3 );
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row, $this->object->getContext ()->getI18N ()->__ ( 'Is late' ) );
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . ($row+1), '-1');
		$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . ($row+2), '-1');

		// danh sach hoc sinh
		$start_row = 9; $start_fix = 9;
		$index = 0;
		$number_student = count ( $list_student );

		foreach ( $list_student as $key => $student ) {

			$student_id = $student->getId ();

			$phaithuthangnay = $collectedAmount = $balanceAmount = $thucte = $totalAmount = $balance_last_month_amount = 0;

			$dunocuoiky = $payment_date = $payment_type = $chietkhau =  $hoantra = '';
			$receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableAndServiceStudentOfMonth ( $student_id, $date_ExportReceipt, $receiptPrevDate );

			$receipt = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $int_date_ExportReceipt );

			if ($receipt) { // hoc sinh co phieu

				$balance_last_month_amount = $receipt->getBalanceLastMonthAmount (); // Nợ đầu kỳ

				$collectedAmount = $receipt->getCollectedAmount (); // Đã thanh toán
				
				// Phải thu theo thông báo
				$phaithuthangnay = 0 - ($receipt->getBalanceAmount() - $balance_last_month_amount);

				$balanceAmount = $collectedAmount - $receipt->getBalanceAmount () + $balance_last_month_amount;

				$chietkhau = $receipt->getChietkhau();
				$hoantra = $receipt->getHoantra();

				// Thực tế thu = phải thu tháng này - chiết khấu - hoàn trả 
				$thucte = $phaithuthangnay - $balance_last_month_amount - $chietkhau - $hoantra;

				$dunocuoiky = $collectedAmount - $thucte;
			}

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $key + 1 );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $student->getStudentCode () );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $student->getStudentName () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $start_row, $student->getClassName () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $start_row, $balance_last_month_amount ); // Nợ đầu kỳ
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $start_row, $phaithuthangnay ); // Phải thu theo thông báo
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $start_row, $hoantra ); // Hoàn lại

			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $start_row, $chietkhau ); // Chiết khấu sau thông báo
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'I' . $start_row, $thucte ); 
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'J' . $start_row, $collectedAmount );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'K' . $start_row, $dunocuoiky ); // Dư cuối kỳ

			$index2 = $dichvucodinh = $dichvukocodinh = 0;
			$array_receivable_student = array ();

			foreach ( $list_service as $service ) {

				$service_id = $service->getId ();

				$col = PHPExcel_Cell::stringFromColumnIndex ( $start_col_service + $index2 );
				$col2 = PHPExcel_Cell::columnIndexFromString ( $col );

				if ($service->getEnableRoll () == PreSchool::ACTIVE) { // Neu la dich vu co dinh

					foreach ( $receivable_student as $key => $student_service ) {

						if ($service_id == $student_service->getRsServiceId ()) {

							if ($student_service->getRsUnitPrice () != 0) {

								$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 - 1 );
								$this->objPHPExcel->getActiveSheet ()
									->setCellValue ( $col5 . $start_row, $student_service->getRsUnitPrice () );

								$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 );
								$this->objPHPExcel->getActiveSheet ()
									->setCellValue ( $col5 . $start_row, $student_service->getRsByNumber () );

								$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 1 );
								$this->objPHPExcel->getActiveSheet ()
									->setCellValue ( $col5 . $start_row, $student_service->getRsDiscountAmount () );

								$rs_amount = ($student_service->getRsDiscount () > 0) ? ((100 - $student_service->getRsDiscount ()) * $student_service->getRsUnitPrice () * $student_service->getRsByNumber ()) / 100 : $student_service->getRsUnitPrice () * $student_service->getRsByNumber ();
								$rs_amount = $rs_amount - ( float ) $student_service->getRsDiscountAmount ();

								$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 2 );
								$this->objPHPExcel->getActiveSheet ()
									->setCellValue ( $col5 . $start_row, $rs_amount );
							}
							unset($receivable_student[$key]);
						}
					}
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( $col . $start_row )
						->getAlignment ()
						->setWrapText ( true );
					$index2 = $index2 + 4;
					
					$dichvucodinh++;
					
				} else {

					foreach ( $receivable_student as $key2=> $student_service ) {

						if ($service_id == $student_service->getRsServiceId ()) {

							if ($student_service->getRsUnitPrice () != 0) {

								if (date ( 'Ym', strtotime ( $student_service->getRsReceiptDate () ) ) == date ( 'Ym', strtotime ( $receiptPrevDate ) )) { // thang truoc

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 - 1 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $student_service->getRsUnitPrice () );

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $student_service->getRsByNumber () );

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 1 );
									$this->objPHPExcel->getActiveSheet ()
										->setCellValue ( $col5 . $start_row, $student_service->getRsSpentNumber () );

									$rs_amount = ($student_service->getRsDiscount () > 0) ? ((100 - $student_service->getRsDiscount ()) * $student_service->getRsUnitPrice () * $student_service->getRsByNumber ()) / 100 : $student_service->getRsUnitPrice () * $student_service->getRsByNumber ();
									$rs_amount = $rs_amount - ( float ) $student_service->getRsDiscountAmount ();

									$thangtruocchuyensang = $rs_amount - $student_service->getRsAmount ();

									$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 2 );
									$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col5 . $start_row, $rs_amount );
								}
								
							}
							unset($receivable_student[$key2]);
						}
					}

					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( $col . $start_row )
						->getAlignment ()
						->setWrapText ( true );
					$index2 = $index2 + 4;
					$dichvukocodinh ++;
				}
			}

			$index3 = 0;
			$khoanthukhac = 0;
			foreach ( $array_receivable as $receivable_id ) {

				$col = PHPExcel_Cell::stringFromColumnIndex ( $start_col_service + $index2 + $index3 );
				$col2 = PHPExcel_Cell::columnIndexFromString ( $col );
				foreach ( $receivable_student as $student_service ) {

					if ($receivable_id == $student_service->getRsReceivableId () && date ( 'Ym', strtotime ( $student_service->getRsReceiptDate () ) ) == date ( 'Ym', strtotime ( $date_ExportReceipt ) )) {

						//$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $start_row, $student_service->getRsAmount () );
						
						$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 - 1 );
						$this->objPHPExcel->getActiveSheet ()
							->setCellValue ( $col5 . $start_row, $student_service->getRsUnitPrice () );

						$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 );
						$this->objPHPExcel->getActiveSheet ()
							->setCellValue ( $col5 . $start_row, $student_service->getRsByNumber () );

						$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 1 );
						$this->objPHPExcel->getActiveSheet ()
							->setCellValue ( $col5 . $start_row, $student_service->getRsSpentNumber () );

						$rs_amount = ($student_service->getRsDiscount () > 0) ? ((100 - $student_service->getRsDiscount ()) * $student_service->getRsUnitPrice () * $student_service->getRsByNumber ()) / 100 : $student_service->getRsUnitPrice () * $student_service->getRsByNumber ();
						$rs_amount = $rs_amount - ( float ) $student_service->getRsDiscountAmount ();

						$thangtruocchuyensang = $rs_amount - $student_service->getRsAmount ();

						$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 + 2 );
						$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col5 . $start_row, $rs_amount );

					}
					if ($student_service->getRsIsLate () == PreSchool::ACTIVE) {
						$is_late = $student_service->getRsAmount ();
					}
				}

				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( $col . $start_row )
					->getAlignment ()
					->setWrapText ( true );
				$index3 = $index3 + 4;
				$khoanthukhac ++;
			}

			$col = PHPExcel_Cell::stringFromColumnIndex ( $start_col_service + $index2 + $index3 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col . $start_row, $is_late );

			$start_row = $start_row + 1;
			if ($index < $number_student) {
				//$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
			}
		}
		
		$start_end = $start_row;
		$start_row = $start_row + 1;
		
		//$this->objPHPExcel->getActiveSheet ()->removeRow ( ($start_end), 1);
	}


	// Xuất biểu mẫu số dư đầu kỳ để import
	public function setDataExportAmountLastMonth($list_student, $list_service, $list_receivables, $ps_month, $ConfigStartDateSystemFee) {
		$date_ExportReceipt = $receivable_at = '01-' . $ps_month;
		$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );

		$date = date_create ( $date_ExportReceipt );
		date_modify ( $date, "-1 month" );
		$receiptPrevDate = date_format ( $date, "Y-m-d" );

		$array_service = $array_receivable = array ();
		// cac dich vu
		$start_service = 5;
		$start_col_service = 7;
		$index2 = 0;
		foreach ( $list_service as $service ) {
			
			$col = PHPExcel_Cell::stringFromColumnIndex ( $start_col_service + $index2 );
			$col2 = PHPExcel_Cell::columnIndexFromString ( $col );

			$row = $start_service;

			if ($service->getEnableRoll () != PreSchool::ACTIVE) { // neu dich vu la không co dinh
				
				$col3 = PHPExcel_Cell::stringFromColumnIndex ( $col2 );

				$this->objPHPExcel->getActiveSheet ()->mergeCells ( $col . $row . ':' . $col3 . $row );
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row, $service->getTitle () );

				$row1 = $start_service + 1;
				$row2 = $start_service + 2;
				$row3 = $start_service + 3;
				
				$this->objPHPExcel->getActiveSheet ()->mergeCells ( $col . $row1 . ':' . $col3 . $row1 );
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row1, $service->getId() );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 - 1 );
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col5 . $row2, 'SL thừa');

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2);
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col5 . $row2, 'Tiền thừa');

				$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getAlignment ()
				->setWrapText ( true );
				$index2 = $index2 +2;
			}

		}

		// danh sach hoc sinh
		$start_row = 8; $start_fix = 8;
		$index = 0;
		$number_student = count ( $list_student );

		foreach ( $list_student as $key => $student ) {

			$student_id = $student->getId ();
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $key + 1 );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $student->getStudentCode () );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $student->getStudentName () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $start_row, $student->getClassName () );

			$start_row = $start_row + 1;
			
		}

	}


	// Xuất biểu mẫu số dư đầu kỳ để import
	public function exportRegisterService($list_student, $list_service, $list_receivables, $ps_month) {
		$date_ExportReceipt = $receivable_at = '01-' . $ps_month;
		$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );

		$date = date_create ( $date_ExportReceipt );
		date_modify ( $date, "-1 month" );
		$receiptPrevDate = date_format ( $date, "Y-m-d" );

		$array_service = $array_receivable = array ();
		// cac dich vu
		$start_service = 5;
		$start_col_service = 4;
		$index2 = 0;
		foreach ( $list_service as $service ) {
			
			$col = PHPExcel_Cell::stringFromColumnIndex ( $start_col_service + $index2 );
			$col2 = PHPExcel_Cell::columnIndexFromString ( $col );

			$row = $start_service;
			$row1 = $start_service + 1;
			
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row, $service->getTitle () );

			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row1, $service->getId() );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getAlignment ()
				->setWrapText ( true );
				$index2 ++;

			/*
			if ($service->getEnableRoll () != PreSchool::ACTIVE) { // neu dich vu la không co dinh
				
				$col3 = PHPExcel_Cell::stringFromColumnIndex ( $col2 );

				$this->objPHPExcel->getActiveSheet ()->mergeCells ( $col . $row . ':' . $col3 . $row );
				
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row, $service->getTitle () );

				$row1 = $start_service + 1;
				$row2 = $start_service + 2;
				$row3 = $start_service + 3;
				
				$this->objPHPExcel->getActiveSheet ()->mergeCells ( $col . $row1 . ':' . $col3 . $row1 );
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row1, $service->getId() );

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2 - 1 );
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col5 . $row2, 'SL thừa');

				$col5 = PHPExcel_Cell::stringFromColumnIndex ( $col2);
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col5 . $row2, 'Tiền thừa');

				$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getAlignment ()
				->setWrapText ( true );
				$index2 = $index2 +2;
			}
			*/

		}

		// danh sach hoc sinh
		$start_row = 7; $start_fix = 7;
		$index = 0;
		$number_student = count ( $list_student );

		foreach ( $list_student as $key => $student ) {

			$student_id = $student->getId ();
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $key + 1 );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $student->getStudentCode () );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $student->getStudentName () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $start_row, $student->getClassName () );

			$start_row = $start_row + 1;
			
		}

	}

	
	public function setDataExportReceipt ( $psServices ) {
		
		$index1 = 0;
		$this->objPHPExcel->setActiveSheetIndex(2);
		
		// cac dich vu
		$start_service = 2;
		$start_col_service = 3;

		foreach ( $psServices as $service ) {

			$col = PHPExcel_Cell::stringFromColumnIndex ( $start_col_service + $index1 );
			$col2 = PHPExcel_Cell::columnIndexFromString ( $col );

			$row = $start_service;
			$row1 = $start_service + 1;
			
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row, $service->getTitle () );

			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row1, $service->getId() );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getAlignment ()
				->setWrapText ( true );
				$index1 ++;

		}


		$index2 = 0;
		$this->objPHPExcel->setActiveSheetIndex(3);
		$start_service2 = 2;
		$start_col_service2 = 7;

		foreach ( $psServices as $service2 ) {

			$col = PHPExcel_Cell::stringFromColumnIndex ( $start_col_service2 + $index2 );
			$col2 = PHPExcel_Cell::columnIndexFromString ( $col );

			$row = $start_service2;
			$row1 = $start_service2 + 1;
			
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row, $service2->getTitle () );

			$this->objPHPExcel->getActiveSheet ()->setCellValue ( $col . $row1, $service2->getId() );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getAlignment ()
				->setWrapText ( true );
				$index2 ++;

		}

		
	}
}