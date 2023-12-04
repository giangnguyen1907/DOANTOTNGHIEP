<?php
class exportStudentServiceSaturdayReportHelper extends ExportHelper {

	public function __construct($object) {

		parent::__construct ($object);

		$this->object = $object;
	}

	/**
	 * Tao tieu de cho bao cao danh sach sinh vien
	 */
	public function setGrowthsStatisticInfoExport($school_name, $title_info, $saturday) {

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
				->setTitle ( $saturday );
		}
	}

	/**
	 * Tao thong tin lop
	 */
	public function setStatisticInfoExportSaturday($workplace_name) {

		$class = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Class' );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A5', $workplace_name->getTitle () );
	}

	/**
	 * Set data cho bao cao danh sach sinh vien
	 */
	public function setDataExportStatisticSaturday($filter_list_student) {

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
		$number_class = count ( $filter_list_student );

		foreach ( $filter_list_student as $list_student ) {

			$index ++;

			// Ten hoc sinh - A7
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $list_student->getStudentName () );

			// Ten Lop - B7
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $list_student->getMcName () );

			// Dich vu - c7
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $list_student->getSvTitle () );

			// Phu huynh - D7
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $list_student->getFullName () );

			// Ngay dang ky - E7

			$inputdate = $list_student->getInputDateAt ();
			$inputdateat = PsDateHelper::format_date ( $inputdate );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, $inputdateat );

			// Ghi chu - F7
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $list_student->getNote () );

			// Ghi chu - G7
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $list_student->getUpdatedBy () );

			$start_row = $start_row + 1;
			if ($index <= $number_class)
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
		}

		$start_row = $start_row + 2;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );
	}
}