<?php
class exportStatisticGrowthsReportHelper extends ExportHelper {

	public function __construct($object) {

		parent::__construct ($object);

		$this->object = $object;
	}

	/**
	 * Tao tieu de cho bao cao danh sach sinh vien
	 */
	public function setGrowthsStatisticInfoExport($school_name, $title_info, $title_xls) {

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
				->setCellValue ( 'C2', $school_name->getAddress () . '-' . $address . ': ' . ($school_name->getTel () != '' ? $school_name->getTel () : $school_name->getMobile ()) );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A3', $title_info );

			$this->objPHPExcel->getActiveSheet ()
				->setTitle ( $title_xls );
		}
	}

	/**
	 * Set data cho bao cao danh sach sinh vien
	 */
	public function setDataExportStatistic_OLD($object_groups, $workplaces, $list_my_class, $psexamination, $all_students) {

		// echo 'ABCDEF'; die;
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

		if ($object_groups == '') {
			$object = '';
			$object_title = 'Nhóm trẻ : ';
		} else {
			$object = $object_groups->getId ();
			$object_title = 'Nhóm trẻ : ' . $object_groups->getTitle ();
		}
		$wp = count ( $workplaces );
		$num = 1;

		foreach ( $workplaces as $workplace ) {
			$num ++;
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $workplace->getTitle () );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':B' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . $start_row . ':B' . $start_row )
				->getAlignment ()
				->setHorizontal ( True );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $object_title );
			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'C' . $start_row . ':I' . $start_row );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'C' . $start_row . ':I' . $start_row )
				->getAlignment ()
				->setHorizontal ( True );

			$start_row = $start_row + 1;

			$psexa = count ( $psexamination );
			$stt = 1;

			foreach ( $psexamination as $psexami ) {
				$stt ++;
				if ($workplace->getId () == $psexami->getPsWorkplaceId ()) {
					$examina = $this->object->getContext ()
						->getI18N ()
						->__ ( 'Examination' );
					$date = $this->object->getContext ()
						->getI18N ()
						->__ ( 'Date' );

					$inputdate = $psexami->getInputDateAt ();
					$inputdateat = PsDateHelper::format_date ( $inputdate );

					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'A' . $start_row, $examina . ' : ' . $psexami->getName () . ' , ' . $date . ' : ' . $inputdateat );

					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'A' . $start_row . ':I' . $start_row );

					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'A' . $start_row )
						->getFont ()
						->setBold ( true );
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'A' . $start_row )
						->getAlignment ()
						->setHorizontal ( True );

					$start_row = $start_row + 1;

					$number_class = count ( $list_my_class );
					$index = 1;
					foreach ( $list_my_class as $class ) {
						$index ++;
						$countactive = $class->getNumberStudentActivitie ();
						if ($workplace->getId () == $class->getPsWorkplaceId ()) {
							foreach ( $all_students as $all_student ) {
								if ($class->getId () == $all_student->getMcid () && $psexami->getExid () == $all_student->getExaminationId ()) {
									$dakham ++;
								}
								if ($class->getId () == $all_student->getMcid () && $psexami->getExid () == $all_student->getExaminationId () && $all_student->getIndexHeight () >= 0) {
									$caodat ++;
								}
								if ($class->getId () == $all_student->getMcid () && $psexami->getExid () == $all_student->getExaminationId () && $all_student->getIndexWeight () >= 0) {
									$nangdat ++;
								}
								if ($class->getId () == $all_student->getMcid () && $psexami->getExid () == $all_student->getExaminationId () && $all_student->getIndexHeight () == - 1) {
									$thap1 ++;
								}
								if ($class->getId () == $all_student->getMcid () && $psexami->getExid () == $all_student->getExaminationId () && $all_student->getIndexHeight () == - 2) {
									$thap2 ++;
								}
								if ($class->getId () == $all_student->getMcid () && $psexami->getExid () == $all_student->getExaminationId () && $all_student->getIndexWeight () == - 1) {
									$nang1 ++;
								}
								if ($class->getId () == $all_student->getMcid () && $psexami->getExid () == $all_student->getExaminationId () && $all_student->getIndexWeight () == - 2) {
									$nang2 ++;
								}
							}
							// Ten lop - A7 275b89
							$this->objPHPExcel->getActiveSheet ()
								->setCellValue ( 'A' . $start_row, $class->getName () );

							// Tong so Hs - B7
							$this->objPHPExcel->getActiveSheet ()
								->setCellValue ( 'B' . $start_row, $countactive );
							$this->objPHPExcel->getActiveSheet ()
								->getStyle ( 'B' . $start_row )
								->getFill ()
								->applyFromArray ( array (
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'startcolor' => array (
											'rgb' => '275b89' ) ) );
							// HS da kham - c7
							$this->objPHPExcel->getActiveSheet ()
								->setCellValue ( 'C' . $start_row, $dakham . ' (' . round ( ($dakham / $countactive * 100), 2 ) . '%)' );
							$this->objPHPExcel->getActiveSheet ()
								->getStyle ( 'C' . $start_row )
								->getFill ()
								->applyFromArray ( array (
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'startcolor' => array (
											'rgb' => '275b89' ) ) );
							// Chieu cao dat - D7
							$this->objPHPExcel->getActiveSheet ()
								->setCellValue ( 'D' . $start_row, $caodat . ' (' . round ( ($caodat / $countactive * 100), 2 ) . '%)' );
							$this->objPHPExcel->getActiveSheet ()
								->getStyle ( 'D' . $start_row )
								->getFill ()
								->applyFromArray ( array (
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'startcolor' => array (
											'rgb' => '5b835b' ) ) );
							// Can nang dat - E7
							$this->objPHPExcel->getActiveSheet ()
								->setCellValue ( 'E' . $start_row, $nangdat . ' (' . round ( ($nangdat / $countactive * 100), 2 ) . '%)' );
							$this->objPHPExcel->getActiveSheet ()
								->getStyle ( 'E' . $start_row )
								->getFill ()
								->applyFromArray ( array (
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'startcolor' => array (
											'rgb' => '5b835b' ) ) );
							// Thap coi do 2 - F7
							$this->objPHPExcel->getActiveSheet ()
								->setCellValue ( 'F' . $start_row, $thap2 . ' (' . round ( ($thap2 / $countactive * 100), 2 ) . '%)' );
							$this->objPHPExcel->getActiveSheet ()
								->getStyle ( 'F' . $start_row )
								->getFill ()
								->applyFromArray ( array (
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'startcolor' => array (
											'rgb' => 'a90329' ) ) );
							// Thap coi do 1 - G7
							$this->objPHPExcel->getActiveSheet ()
								->setCellValue ( 'G' . $start_row, $thap1 . ' (' . round ( ($thap1 / $countactive * 100), 2 ) . '%)' );
							$this->objPHPExcel->getActiveSheet ()
								->getStyle ( 'G' . $start_row )
								->getFill ()
								->applyFromArray ( array (
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'startcolor' => array (
											'rgb' => 'c79121' ) ) );
							// Suy dinh duong nang - H7
							$this->objPHPExcel->getActiveSheet ()
								->setCellValue ( 'H' . $start_row, $nang2 . ' (' . round ( ($nang2 / $countactive * 100), 2 ) . '%)' );
							$this->objPHPExcel->getActiveSheet ()
								->getStyle ( 'H' . $start_row )
								->getFill ()
								->applyFromArray ( array (
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'startcolor' => array (
											'rgb' => 'a90329' ) ) );
							// Suy dinh duong nhe - I7
							$this->objPHPExcel->getActiveSheet ()
								->setCellValue ( 'I' . $start_row, $nang1 . ' (' . round ( ($nang1 / $countactive * 100), 2 ) . '%)' );
							$this->objPHPExcel->getActiveSheet ()
								->getStyle ( 'I' . $start_row )
								->getFill ()
								->applyFromArray ( array (
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'startcolor' => array (
											'rgb' => 'c79121' ) ) );
						} // xet dieu kien
						$dakham = 0;
						$caodat = 0;
						$nangdat = 0;
						$thap1 = 0;
						$thap2 = 0;
						$nang1 = 0;
						$nang2 = 0;

						if ($index <= $number_class) {
							$start_row = $start_row + 1;
							// $this->objPHPExcel->getActiveSheet()->insertNewRowBefore($start_row, 1);
						}
					} // loc theo lop
				} // kiem tra dieu kien

				if ($stt <= $psexa) {
					$start_row = $start_row + 1;
					$this->objPHPExcel->getActiveSheet ()
						->insertNewRowBefore ( $start_row, 1 );
				}
			} // loc theo dot kham cua co so

			if ($num <= $wp) {
				// $start_row = $start_row + 1;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
			}
		} // loc theo co so

		$start_row = $start_row + 2;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );
	}

	public function setDataExportStatistic($object_groups, $workplace, $list_my_class, $psexamination, $all_students) {

		// echo 'ABCDEF'; die;
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

		if ($object_groups == '') {
			$object = '';
			$object_title = $this->object->getContext ()
				->getI18N ()
				->__ ( 'Group student' ); // ngon ngu
		} else {
			$object = $object_groups->getId ();
			$object_title = $this->object->getContext ()
				->getI18N ()
				->__ ( 'Group student' ) . $object_groups->getTitle ();
		}

		$num = 1;

		// foreach ($workplaces as $workplace) {

		$num ++;
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $workplace->getTitle () );
		$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'A' . $start_row . ':B' . $start_row );
		$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'A' . $start_row . ':B' . $start_row )
			->getAlignment ()
			->setHorizontal ( True );

		$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'C' . $start_row . ':K' . $start_row )
			->applyFromArray ( array (
				'font' => array (
						'color' => array (
								'rgb' => '333333' ) ) ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $object_title );
		$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'C' . $start_row . ':K' . $start_row );
		$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'C' . $start_row . ':K' . $start_row )
			->getAlignment ()
			->setHorizontal ( True );
		$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'C' . $start_row )
			->getFill ()
			->applyFromArray ( array (
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' => array (
						'rgb' => 'ffffff' ) ) );
		// Dot kham
		foreach ( $psexamination as $psexami ) {

			if ($workplace->getId () == $psexami->getPsWorkplaceId ()) {

				$start_row = $start_row + 1;

				$examina = $this->object->getContext ()
					->getI18N ()
					->__ ( 'Examination' );
				$date = $this->object->getContext ()
					->getI18N ()
					->__ ( 'Date' );

				$inputdate = $psexami->getInputDateAt ();
				$inputdateat = PsDateHelper::format_date ( $inputdate );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $examina . ' : ' . $psexami->getName () . ' , ' . $date . ' : ' . $inputdateat );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . $start_row . ':K' . $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->getStyle ( 'A' . $start_row . ':I' . $start_row )
					->getAlignment ()
					->setHorizontal ( true );

				// Lop hoc sinh
				foreach ( $list_my_class as $class ) {
					// if ($workplace->getId() == $class->getPsWorkplaceId()) {

					$start_row = $start_row + 1;
					$countactive = $class->getNumberStudentActivitie ();
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'A' . $start_row, $class->getName () );

					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'A' . $start_row )
						->getAlignment ()
						->setHorizontal ( true );

					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'A' . $start_row )
						->applyFromArray ( array (
							'font' => array (
									'bold' => false ) ) );
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( "A" . $start_row . ":K" . $start_row )
						->applyFromArray ( array (
							'borders' => array (
									'allborders' => array (
											'style' => PHPExcel_Style_Border::BORDER_THIN,
											'color' => array (
													'rgb' => '333333' ) ) ) ) );

					$invalue = $class->getId () . $psexami->getExid ();
					$invalue1 = $class->getId () . $psexami->getExid () . '-2';
					$invalue2 = $class->getId () . $psexami->getExid () . '-1';
					$invalue3 = $class->getId () . $psexami->getExid () . '0';
					$invalue4 = $class->getId () . $psexami->getExid () . '1';

					foreach ( $all_students as $all_student ) {

						if ($invalue == $all_student->getMcid () . $all_student->getExaminationId ()) {
							$dakham ++;
						}
						if ($invalue4 == $all_student->getMcid () . $all_student->getExaminationId () . $all_student->getIndexHeight ()) {
							$caohon ++;
						}
						if ($invalue4 == $all_student->getMcid () . $all_student->getExaminationId () . $all_student->getIndexWeight ()) {
							$nanghon ++;
						}
						if ($invalue3 == $all_student->getMcid () . $all_student->getExaminationId () . $all_student->getIndexHeight ()) {
							$caodat ++;
						}
						if ($invalue3 == $all_student->getMcid () . $all_student->getExaminationId () . $all_student->getIndexWeight ()) {
							$nangdat ++;
						}
						if ($invalue2 == $all_student->getMcid () . $all_student->getExaminationId () . $all_student->getIndexHeight ()) {
							$thap1 ++;
						}
						if ($invalue1 == $all_student->getMcid () . $all_student->getExaminationId () . $all_student->getIndexHeight ()) {
							$thap2 ++;
						}
						if ($invalue2 == $all_student->getMcid () . $all_student->getExaminationId () . $all_student->getIndexWeight ()) {
							$nang1 ++;
						}
						if ($invalue1 == $all_student->getMcid () . $all_student->getExaminationId () . $all_student->getIndexWeight ()) {
							$nang2 ++;
						}
					}

					// Tong so Hs - B7
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'B' . $start_row )
						->applyFromArray ( array (
							'font' => array (
									'color' => array (
											'rgb' => '333333' ) ) ) );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'B' . $start_row, $countactive );

					// HS da kham - c7
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'C' . $start_row )
						->applyFromArray ( array (
							'font' => array (
									'color' => array (
											'rgb' => '333333' ) ) ) );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'C' . $start_row, $dakham . ' (' . round ( ($dakham / $countactive * 100), 2 ) . '%)' );

					// Chieu cao dat - D7
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'D' . $start_row )
						->applyFromArray ( array (
							'font' => array (
									'color' => array (
											'rgb' => '333333' ) ) ) );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'D' . $start_row, $caodat . ' (' . round ( ($caodat / $countactive * 100), 2 ) . '%)' );
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'D' . $start_row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'ecf3f6' ) ) );
					// Can nang dat - E7
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'E' . $start_row )
						->applyFromArray ( array (
							'font' => array (
									'color' => array (
											'rgb' => '333333' ) ) ) );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'E' . $start_row, $nangdat . ' (' . round ( ($nangdat / $countactive * 100), 2 ) . '%)' );
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'E' . $start_row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'ecf3f6' ) ) );
					// Cao hon - F7
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'F' . $start_row )
						->applyFromArray ( array (
							'font' => array (
									'color' => array (
											'rgb' => '333333' ) ) ) );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'F' . $start_row, $thap2 . ' (' . round ( ($thap2 / $countactive * 100), 2 ) . '%)' );
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'F' . $start_row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'f8f89b' ) ) );
					// Thap coi do 1 - G7
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'G' . $start_row )
						->applyFromArray ( array (
							'font' => array (
									'color' => array (
											'rgb' => '333333' ) ) ) );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'G' . $start_row, $thap1 . ' (' . round ( ($thap1 / $countactive * 100), 2 ) . '%)' );
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'G' . $start_row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'e5eadf' ) ) );
					// Suy dinh duong nang - H7
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'H' . $start_row )
						->applyFromArray ( array (
							'font' => array (
									'color' => array (
											'rgb' => '333333' ) ) ) );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'H' . $start_row, $nang2 . ' (' . round ( ($nang2 / $countactive * 100), 2 ) . '%)' );
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'H' . $start_row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'e07c87' ) ) );
					// nang hon - I7
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'I' . $start_row )
						->applyFromArray ( array (
							'font' => array (
									'color' => array (
											'rgb' => '333333' ) ) ) );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'I' . $start_row, $nanghon . ' (' . round ( ($nanghon / $countactive * 100), 2 ) . '%)' );
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'I' . $start_row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'f8f89b' ) ) );
					// Suy dinh duong nang - I7
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'J' . $start_row )
						->applyFromArray ( array (
							'font' => array (
									'color' => array (
											'rgb' => '333333' ) ) ) );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'J' . $start_row, $nang1 . ' (' . round ( ($nang1 / $countactive * 100), 2 ) . '%)' );
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'J' . $start_row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'e5eadf' ) ) );

					// Suy dinh duong nhe - I7
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'K' . $start_row )
						->applyFromArray ( array (
							'font' => array (
									'color' => array (
											'rgb' => '333333' ) ) ) );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'K' . $start_row, $nang2 . ' (' . round ( ($nang2 / $countactive * 100), 2 ) . '%)' );
					$this->objPHPExcel->getActiveSheet ()
						->getStyle ( 'K' . $start_row )
						->getFill ()
						->applyFromArray ( array (
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'startcolor' => array (
									'rgb' => 'e07c87' ) ) );

					$dakham = 0;
					$caodat = 0;
					$nangdat = 0;
					$thap1 = 0;
					$thap2 = 0;
					$nang1 = 0;
					$nang2 = 0;
					$caohon = 0;
					$nanghon = 0;
					// }
				}
			}
		}

		$start_row = $start_row + 1;

		$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
		// } // loc theo co so

		$this->objPHPExcel->getActiveSheet ()
			->removeRow ( $start_row, 1 );

		$start_row = $start_row + 1;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'H' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );
	}
	
	public function setDataExportStudentMalnutrition ( $list_student_malnutrition ){
		
		$start_row = 6;
		$index = 0;
		$number_student = count ( $list_student_malnutrition );
		
		foreach ( $list_student_malnutrition as $key => $student ) {
			
			$coment_weight = $coment_height = '';
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $key + 1 );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $start_row, $student->getStudentName () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $start_row, $student->getStudentCode () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $start_row, $student->getClassName () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $start_row, $student->getIndexAge () );
			
			$height = $student->getHeight ();
			$weight = $student->getWeight ();
			
			if($student->getIndexHeight () == -1){
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $start_row, $height );
			}
			
			if($student->getIndexHeight () == -2){
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $start_row, $height );
			}
			
			if($student->getIndexWeight () == -1){
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $start_row, $weight );
			}
			
			if($student->getIndexWeight () == -2){
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'I' . $start_row, $weight );
			}
			
			if($student->getIndexWeight () == 1){
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'J' . $start_row, $weight );
			}
			
			if($student->getIndexWeight () == 2){
				$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'K' . $start_row, $weight );
			}
			
			if($student->getIndexHeight () < 0){
				$coment_height = $this->object->getContext ()->getI18N () ->__ (PreSchool::getHeightBMI()[$student->getIndexHeight ()]);
			}
			if($student->getIndexWeight () != 0){
				$coment_weight = $this->object->getContext ()->getI18N () ->__ (PreSchool::getWeightBMI()[$student->getIndexWeight ()]);
			}
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'L' . $start_row, $coment_height );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'M' . $start_row, $coment_weight );
			
			/*
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $start_row, $student->getWeight () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $start_row, $student->getIndexTooth () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $start_row, $student->getIndexThroat () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'I' . $start_row, $student->getIndexEye () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'J' . $start_row, $student->getIndexHeart () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'K' . $start_row, $student->getIndexLung () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'L' . $start_row, $student->getIndexSkin () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'M' . $start_row, $student->getPeopleMake () );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'N' . $start_row, $student->getNote () );
			
			$coment_height = $this->object->getContext ()->getI18N () ->__ (PreSchool::getHeightBMI()[$student->getIndexHeight ()]);
			
			$coment_weight = $this->object->getContext ()->getI18N () ->__ (PreSchool::getWeightBMI()[$student->getIndexWeight ()]);
			
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'O' . $start_row, $coment_height );
			$this->objPHPExcel->getActiveSheet ()->setCellValue ( 'P' . $start_row, $coment_weight );
			*/
			
			$start_row = $start_row + 1;
			if ($index <= $number_student){
				$this->objPHPExcel->getActiveSheet ()->insertNewRowBefore ( $start_row, 1 );
			}
		}
		
		$this->objPHPExcel->getActiveSheet ()->removeRow($start_row,1);
		
	}
}