<?php
class ExportClassReportsHelper extends ExportHelper {

	protected $sheet_index = 0;

	public function __construct($object) {

		parent::__construct ($object);

		$this->object = $object;
	}

	/**
	 * Tao tieu de cho thong ke sinh vien theo truong, co so
	 */
	public function setCustomerInfoExportClass($school_year_id, $school_info = null, $workplace = null) {

		$school_year = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $school_year_id );
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
				->__ ( Address ) . ': ' . $school_info->getAddress () . ' - ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'Tel' ) . ': ' . ($school_info->getTel () ? $school_info->getTel () : $school_info->getMobile ()) );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A6', $this->object->getContext ()
				->getI18N ()
				->__ ( 'REPORT TOTAL STUDENT OF YEAR ' ) . $school_year->getTitle () );

			if ($workplace) {

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A8', $this->object->getContext ()
					->getI18N ()
					->__ ( 'Workplace: ' ) . $workplace->getTitle () );
			} else {
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A8', '' );
			}
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A9', $this->object->getContext ()
				->getI18N ()
				->__ ( 'No.' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B9', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Code' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C9', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Class' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D9', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Object group' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E9', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Total Student' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F9', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Teacher class' ) );

			if ($workplace) {
				$this->objPHPExcel->getActiveSheet ()
					->setTitle ( $workplace->getTitle () );
			} else {
				$this->objPHPExcel->getActiveSheet ()
					->setTitle ( $school_info->getSchoolName () );
			}
		}
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
	 * Thong ke so luong hoc sinh theo Nhom lop, Ten lop va truong
	 */
	public function setDataExportClassFinalReport($school_year_id, $customer_id, $workplace_id, $object_id) {

		$day = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' );
		$month = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Month' );
		$year = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Year' );

		$params = array ();
		$params ['ps_school_year_id'] = $school_year_id;
		$params ['ps_customer_id'] = $customer_id;
		$params ['ps_workplace_id'] = $workplace_id;
		$params ['ps_obj_group_id'] = $object_id;
		$start_row = 11;

		if ($object_id > 0) {
			$obj_group = Doctrine::getTable ( 'PsObjectGroups' )->findOneById ( $object_id );
			$obj_title = $obj_group->getTitle ();
		} else {
			$obj_title = '';
		}
		$my_class = Doctrine::getTable ( 'MyClass' )->setClassByParams ( $params )
			->execute ();

		foreach ( $my_class as $key => $class ) {
			$start_row ++;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, ($key + 1) );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $class->getCode () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $class->getTitle () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $obj_title );

			$total_student = count ( Doctrine::getTable ( 'StudentClass' )->getAllStudentsByClassId ( $class->getId () ) );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, $total_student );

			$teacher = Doctrine::getTable ( 'PsTeacherClass' )->getTeachersByClassId ( $class->getId () );
			$_arr_teacher = array ();

			$date_now = date ( 'Ymd' );
			foreach ( $teacher as $t ) {
				if (date ( 'Ymd', strtotime ( $t->getStopAt () ) ) < $date_now) {
					array_push ( $_arr_teacher, $t->getFullName () . $this->object->getContext ()
						->getI18N ()
						->__ ( '(Before)' ) );
				} else {
					array_push ( $_arr_teacher, $t->getFullName () . $this->object->getContext ()
						->getI18N ()
						->__ ( '(Now)' ) );
				}
			}
			$teacher = implode ( "; ", $_arr_teacher );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $teacher );

			$this->objPHPExcel->getActiveSheet ()
				->getRowDimension ( $start_row )
				->setRowHeight ( - 1 );
		}

		$styleArray = array (
				'borders' => array (
						'allborders' => array (
								'style' => PHPExcel_Style_Border::BORDER_THIN ) ) );
		if (count ( $my_class ) > 0) {
			$this->objPHPExcel->getActiveSheet ()
				->removeRow ( 11 );
			$start_row = $start_row + 2;
		} else {
			$start_row = $start_row + 3;
		}
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );
	}
}