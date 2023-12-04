<?php
class exportRelativeStatisticHelper extends ExportHelper {

	protected $sheet_index = 0;

	public function __construct($object) {

		parent::__construct ($object);

		$this->object = $object;
	}

	/**
	 * Tao tieu de cho thong ke sinh vien theo truong, co so
	 */
	public function setCustomerInfoExportAccountStatistic($school_year, $school_info = null, $workplace_id = null) {

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
				$objDrawing->setWidth ( 150 );

				$objDrawing->getShadow ()
					->setVisible ( true );
				$objDrawing->getShadow ()
					->setDirection ( 80 );
				$objDrawing->setWorksheet ( $this->objPHPExcel->getActiveSheet () );
			}

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C1', $school_info->getCusSchoolName () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C2', $this->object->getContext ()
				->getI18N ()
				->__ ( Address ) . ': ' . $school_info->getCusAddress () . ' - ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'Tel' ) . ': ' . ($school_info->getCusTel () ? $school_info->getCusTel () : $school_info->getCusMobile ()) );

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'C2:H2' );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A6', $this->object->getContext ()
				->getI18N ()
				->__ ( 'ACCOUNTS RELATIVE STATISTIC BY CLASS ' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E7', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Year' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F7', $school_year ['title'] );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E8', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Class' ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F8', $school_info->getMcName () );

			$this->objPHPExcel->getActiveSheet ()
				->setTitle ( $school_info->getMcName () );
		}
	}

	/**
	 * Set data cho bao cao danh sach tai khoan phu huynh
	 */
	public function setDataExportRelativeAccounts($data_accounts, $student_list = null) {

		$day = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' );
		$month = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Month' );
		$year = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Year' );

		$total = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Total: ' );

		$granted = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Granted: ' );

		$active_app = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Active app: ' );
		$relative_count = count ( $data_accounts );
		$granted_count = 0;
		$active_count = 0;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A9', $this->object->getContext ()
			->getI18N ()
			->__ ( 'No.' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'B9', $this->object->getContext ()
			->getI18N ()
			->__ ( 'First name' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C9', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Last name' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D9', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Student' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'E9', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Sex' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F9', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Mobile' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G9', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Email' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'H9', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Username' ) );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'I9', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Activated app' ) );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'J9', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Note' ) );

		$start_row = 10;

		foreach ( $data_accounts as $key => $account ) {

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, ($key + 1) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $account->getFirstName () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $account->getLastName () );
			foreach ( $student_list as $k => $student ) {
				if ($account->getMemberId () == $k) {
					$data = implode ( "\n", $student );
					$count = count ( $student );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'D' . $start_row, $data );
					$this->objPHPExcel->getActiveSheet ()
						->getRowDimension ( $start_row )
						->setRowHeight ( - 1 );
				}
			}
			if ($account->getSex () == 1) {
				$gender = $this->object->getContext ()
					->getI18N ()
					->__ ( 'Male' );
			} else {
				$gender = $this->object->getContext ()
					->getI18N ()
					->__ ( 'Female' );
			}
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, $gender );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $account->getMobile () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'G' . $start_row, $account->getEmail () );
			if ($account->getUserId () > 0) {
				$granted_count ++;
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'H' . $start_row, $account->getUsername () );

				if ($account->getAppDeviceId () != '') {
					$active_count ++;
					$status = $this->object->getContext ()
						->getI18N ()
						->__ ( 'Actived' );
				} else {
					$status = $this->object->getContext ()
						->getI18N ()
						->__ ( 'Not Active' );
				}

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'I' . $start_row, $status );
			}
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'J' . $start_row, '' );

			if ($relative_count != $key + 1) {
				$start_row ++;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
			}
		}

		$start_row = $start_row + 2;
		$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $total . $relative_count );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $granted . $granted_count );

		// $this->objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		$start_row ++;
		$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $active_app . $active_count );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );
	}
}
