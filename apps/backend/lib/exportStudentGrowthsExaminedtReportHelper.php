<?php
class exportStudentGrowthsExaminedReportHelper extends ExportHelper {

	public function __construct($object) {

		parent::__construct ($object);

		$this->object = $object;
	}

	/**
	 * Tao tieu de cho bao cao danh sach sinh vien
	 */
	public function setGrowthsStatisticInfoExport($school_name, $title_info, $examination) {

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
				->setTitle ( $examination->getName () );
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
	public function setDataExportStatistic($growths_weight, $examination) {

		$day = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' );
		$month = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Month' );
		$year = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Year' );

		$start_row = 7;

		$index = 1;
		$number_class = count ( $growths_weight );

		foreach ( $growths_weight as $growths ) {

			$index ++;

			// Ten hoc sinh - A7
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $growths->getStudentName () );

			// Ngay sinh - B7
			// PsDateHelper
			// $birthdayfm = format_date($growths->getBirthday(), "dd-MM-yyyy");
			// $this->objPHPExcel->getActiveSheet()->setCellValue('B'.$start_row, $birthdayfm);

			$birthdayfm = $growths->getBirthday ();
			$birthday = PsDateHelper::format_date ( $birthdayfm );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $this->object->getContext ()
				->getI18N ()
				->__ ( $birthday ) );

			// Gioi tinh - c7
			$sex = $growths->getSex ();
			$student_sex = PreSchool::getGender ();
			if (isset ( $student_sex [$sex] ))
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'C' . $start_row, $this->object->getContext ()
					->getI18N ()
					->__ ( $student_sex [$sex] ) );
			// Thoi diem kham - D7
			// $this->objPHPExcel->getActiveSheet()->setCellValue('D'.$start_row, $growths->getBirthday());

			$birthday = $growths->getBirthday ();
			$show = $examination->getInputDateAt ();
			$get_age = PreSchool::getMonthYear2 ( $birthday, $show );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $this->object->getContext ()
				->getI18N ()
				->__ ( $get_age ) );

			// Chieu cao - E7
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, $growths->getHeight () );

			// Danh gia - F7
			$height = $growths->getIndexHeight ();
			$indexheight = PreSchool::getHeightBMI ();
			if (isset ( $indexheight [$height] ))
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'F' . $start_row, $this->object->getContext ()
					->getI18N ()
					->__ ( $indexheight [$height] ) );

			// Can nang - G7
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $growths->getWeight () );

			// Danh gia - H7
			$weight = $growths->getIndexWeight ();
			$indexweight = PreSchool::getWeightBMI ();
			if (isset ( $indexweight [$weight] ))
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'H' . $start_row, $this->object->getContext ()
					->getI18N ()
					->__ ( $indexweight [$weight] ) );

			$start_row = $start_row + 1;
			if ($index <= $number_class)
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
		}

		$start_row = $start_row + 1;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );
	}
}