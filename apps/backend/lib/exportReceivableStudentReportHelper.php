<?php
class exportReceivableStudentReportHelper extends ExportHelper {

	public function __construct($object) {

		parent::__construct ($object);

		$this->object = $object;
	}

	protected $sheet_index = 0;

	private $receivable_col = array ();

	/**
	 * Tao tieu de cho bao cao danh sach hoc sinh
	 */
	public function setDataExportSchoolInfoExport($school_name, $title_info, $title_xls) {

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
	 * Set data cho bao cao danh sach sinh vien
	 */
	public function setDataExportReceivableStudentByClass($list_student) {

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
		$number_class = count ( $list_student );

		foreach ( $list_student as $student ) {

			$index ++;

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $student->getStudentCode () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $student->getStudentName () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $student->getAmount () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $student->getIsNumber () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, $student->getNote () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $student->getUpdatedBy () );

			$start_row = $start_row + 1;
			if ($index <= $number_class)
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
		}

		$start_row = $start_row + 1;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );
	}

	/**
	 * Set data
	 */
	public function setDataExportReceivableStudentByClassId($list_student, $filter_list_student, $list_receivable) {

		$day = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' );
		$month = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Month' );
		$year = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Year' );

		$list_receivable_std = array ();
		$_array_student = array ();
		$list_receivable_title = array ();
		foreach ( $list_receivable as $receivable ) {
			$list_receivable_title [$receivable->getId ()] = $receivable->getTitle ();
			$_array_student [$receivable->getId ()] = $receivable->getAmount ();
		}
		foreach ( $filter_list_student as $receivables ) {
			array_push ( $list_receivable_std, $receivables->getStudentId () . '_' . $receivables->getReceivableId () );
		}

		$start = 5;
		$index = 0;
		$mumber = count ( $list_receivable_title );
		foreach ( $list_receivable_title as $key => $receivable_title ) {

			/**
			 * $col la ten cot ung voi moi lan lap hoc sinh, gia tri cuoi cung la ten cot cua hox sinh cuoi cung
			 *
			 * @var $col
			 */

			$index = $index + 1;

			$col = PHPExcel_Cell::stringFromColumnIndex ( 1 + $index );

			$row = $start;

			if ($index < $mumber) {
				$this->objPHPExcel->getActiveSheet ()
					->insertNewColumnBefore ( $col, 1 );
			}
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col . $row, $receivable_title );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getAlignment ()
				->setWrapText ( true );

			$this->receivable_col [$key] = $col;
		}

		$start_row = 6;
		$index = 1;
		$number_class = count ( $list_student );

		foreach ( $list_student as $key => $student ) {

			$index ++;

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $student->getStudentCode () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $student->getStudentName () );

			foreach ( $_array_student as $key2 => $amount ) {
				$check = $student->getId () . '_' . $key2;
				if (in_array ( $check, $list_receivable_std )) {
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( $this->receivable_col [$key2] . $start_row, $amount );
				}
			}
			$start_row = $start_row + 1;
			if ($index <= $number_class)
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
		}

		$start_row = $start_row + 1;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );
	}

	/**
	 * Set data cho bao cao danh sach sinh vien
	 */
	public function setDataExportReceivableStudentById($student, $list_receivable) {

		$day = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' );
		$month = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Month' );
		$year = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Year' );
		$student_name = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Student' ) . ' : ' . $student->getFirstName () . ' ' . $student->getLastName ();

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A4' . $start_row, $student_name );

		$start_row = 6;

		$index = 1;
		$number_class = count ( $list_receivable );

		foreach ( $list_receivable as $receivable ) {

			$index ++;

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $receivable->getReceivableTitle () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $receivable->getAmount () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $receivable->getIsNumber () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $receivable->getNote () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, $receivable->getUpdatedBy () );

			$start_row = $start_row + 1;
			if ($index <= $number_class)
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
		}

		$start_row = $start_row + 1;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );
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
}