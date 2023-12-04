<?php
class ExportEvaluateIndexStudentHelper extends ExportHelper {

	protected $sheet_index = 0;

	public function __construct($object) {

		parent::__construct ($object);

		$this->object = $object;
	}

	private $start_row = 6;

	private $start_column_student = 4;

	private $end_column_student = 0;

	private $lever_row = array ();

	// Tong so chi so danh gia
	private $criteria_total = 0;

	private $criteria_row = array ();

	private $student_col = array ();

	private $symbol_col = array ();

	public function setCustomerInfoExport($school_info, $symbols, $param) {

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A1', mb_strtoupper ( $school_info->getCusSchoolName () ) );

		$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'A1:B1' );

		$title = $this->object->getContext ()
			->getI18N ()
			->__ ( 'EVALUATE INDEX STUDENT %%obj_group%% (%%class%%)', array (
				'%%obj_group%%' => mb_strtoupper ( $school_info->getOgTitle () ),
				'%%class%%' => mb_strtoupper ( $school_info->getMcName () ) ), 'messages' );

		$month = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Month %%month%%', array (
				'%%month%%' => $param ['month'] ? date ( 'm/Y', strtotime ( $param ['month'] ) ) : '' ), 'messages' );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A3', $title );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A4', $month );

		$symbol_rule = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Rule of symbol evaluate' );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'B6', $symbol_rule );

		$this->objPHPExcel->getActiveSheet ()
			->setTitle ( $school_info->getMcName () );
	}
	
	public function setDataEvaluateExport($students, $evaluate, $symbols, $param) {
		
		$total_student = count ( $students );
		
		$total_evaluate = count ( $evaluate );
		
		$total_symbol = count ( $symbols );
		
		$month = date ( 'm', strtotime ( $param ['month'] ) );
		
		$start_column_student = 4;
		
		$end_column_student = 0;
		
		$lever_row = array ();
		
		// Tong so chi so danh gia
		$criteria_total = 0;
		
		$criteria_row = array ();
		
		$student_col = array ();
		
		$symbol_col = array ();
		
		$start_row = 6;
		
		//$end_column_student = ($total_student >= 0) ? $start_column_student + $total_student : $start_column_student;
		switch ($total_student) {
			
			case 0 :
				$this->objPHPExcel->getActiveSheet ()
				->removeColumn ( 'D' );
				$this->objPHPExcel->getActiveSheet ()
				->removeColumn ( 'E' );
				break;
			case 1 :
				$this->objPHPExcel->getActiveSheet ()
				->removeColumn ( 'E' );
				break;
			case 2 :
				break;
			default :
				$this->objPHPExcel->getActiveSheet ()
				->insertNewColumnBefore ( 'E', $total_student - 2 );
		}
		// ký hiệu đánh giá
		foreach ( $symbols as $key => $symbol ) {
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $symbol->getTitle () );
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'H' . $start_row, $symbol->getSymbolCode () );
			
			$start_row ++;
			
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
		}
		
		// đoạn này xét chữ
		$row = $start_row - $total_symbol;
		$row2 = $start_row - 1;
		$BStyle = array (
				'borders' => array (
						'allborders' => array (
								'style' => PHPExcel_Style_Border::BORDER_THIN ) ) );
		$this->objPHPExcel->getActiveSheet ()
		->getStyle ( "H{$row}:H{$row2}" )
		->applyFromArray ( $BStyle );
		
		$start_row ++;
		
		$this->objPHPExcel->getActiveSheet ()
		->setCellValue ( 'A' . $start_row, mb_strtoupper ( ($this->object->getContext ()
				->getI18N ()
				->__ ( 'Criteria code' )) ) ); // mã tiêu chí
		$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . $start_row . ':A' . ($start_row + 1) );
		$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, mb_strtoupper ( $this->object->getContext ()
						->getI18N ()
						->__ ( 'Title' ) ) ); // Tiêu đề
		$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'B' . $start_row . ':B' . ($start_row + 1) );
		$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'C' . $start_row, mb_strtoupper ( $this->object->getContext ()
								->getI18N ()
								->__ ( 'Time apply' ) ) ); // Thời gian áp dụng
		$this->objPHPExcel->getActiveSheet ()
								->mergeCells ( 'C' . $start_row . ':C' . ($start_row + 1) );
								
		$this->objPHPExcel->getActiveSheet () ->getDefaultColumnDimension () ->setWidth ( 7 );
		
		foreach ( $students as $key => $student ) {
			
			/**
			 * $col la ten cot ung voi moi lan lap hoc sinh, gia tri cuoi cung la ten cot cua hox sinh cuoi cung
			 *
			 * @var $col
			 */
			$col = PHPExcel_Cell::stringFromColumnIndex ( $start_column_student + $key - 1 );
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( $col . $start_row, $key + 1 );
			
			$row = $start_row + 1;
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( $col . $row, $student ['full_name'] . "\n" . $student ['student_code'] );
			
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( $col . $row )
			->getAlignment ()
			->setWrapText ( true );
			
			array_push ( $student_col, $col );
		}
								
		unset ( $student );
		$start_row = $start_row + 2;
		
		switch ($total_evaluate) { // tổng lĩnh vực
			
			case 0 :
				$this->objPHPExcel->getActiveSheet ()
				->removeRow ( $start_row );
				$this->objPHPExcel->getActiveSheet ()
				->removeRow ( $start_row + 1 );
				break;
			case 1 :
				$this->objPHPExcel->getActiveSheet ()
				->removeColumn ( $start_row + 1 );
				break;
			default :
				// $this->objPHPExcel->getActiveSheet()->insertNewColumnBefore('E', $total_student - 2);
		}
		
		$criteria_count = $total_criteria = 0;
		
		$this->objPHPExcel->getActiveSheet ()
		->removeRow ( $start_row );
		foreach ( $evaluate as $key => $eval ) {
			
			$start_row ++;
			
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
			if (isset ( $eval ['criteria_id'] ) && $eval ['criteria_id'] > 0) { // Nếu tồn tại tiêu chí đánh giá
				
				$criteria_count ++;
				
				$total_criteria ++;
				
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $eval ['criteria_code'] );
				
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $eval ['criteria_title'] );
				
				// In ra tung chi so danh gia cua hoc sinh
				
				foreach ( $eval ['evaluate'] as $k => $val ) {
					
					$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'C' . $start_row, $month );
					
					$col = PHPExcel_Cell::stringFromColumnIndex ( $start_column_student + $k - 1 );
					
					if ($val ['is_awaiting_approval'] > 0 && $val ['is_public'] > 0) {
						
						$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( $col . $start_row, $val ['symbol_code'] );
					}
				}
			} else {
										
				if ($criteria_count > 0) {
					array_push ( $criteria_row, array (
							'end' => $start_row - 1,
							'start' => $criteria_row_start,
							'criteria_count' => $criteria_count,
							'level_code' => $level_code ) );
					$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
					$start_row ++;
				}
				
				array_push ( $lever_row, $start_row );
				$level_code = $eval ['subject_code'];
				$criteria_count = 0;
				if (isset ( $evaluate [$key + 1] ['criteria_code'] )) {
					$criteria_row_start = $start_row + 1;
				}
				
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $eval ['subject_code'] );
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $eval ['subject_title'] );
			}
		}
		
		array_push ( $criteria_row, array (
				'end' => $start_row,
				'start' => $criteria_row_start,
				'criteria_count' => $criteria_count,
				'level_code' => $level_code ) );
		
		// echo '<pre>';
		// print_r ($criteria_row);
		// echo '</pre>';
		// die;
		//$student_evaluate = array_reverse ( $student_evaluate );
		
		foreach ( $lever_row as $row ) {
			
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( "A{$row}:B{$row}" )
			->getFont ()
			->setBold ( TRUE );
			
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( "A{$row}:{$col}{$row}" )
			->getFill ()
			->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )
			->getStartColor ()
			->setRGB ( 'FFFF00' );
		}
		
		$criteria_row = array_reverse ( $criteria_row );
		
		// print_r($criteria_row);
		
		foreach ( $criteria_row as $key => $criteria_row ) {
			
			$row = $criteria_row ['end'] + 1;
			$criteria_row_end = $criteria_row ['end'];
			$criteria_row_start = $criteria_row ['start'];
			$level_code = $criteria_row ['level_code'];
			$criteria_count = $criteria_row ['criteria_count'];
			$this->criteria_total += $criteria_count;
			
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $row, 1 );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $row, $this->object->getContext ()
					->getI18N ()
					->__ ( 'SUM' ) . "\n" . $level_code );
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'A' . $row )
			->getAlignment ()
			->setWrapText ( true );
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( "A{$row}:B{$row}" )
			->getFont ()
			->setBold ( TRUE );
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( "A{$row}:B{$row}" )
			->getAlignment ()
			->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )
			->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'B' . $row, $criteria_count . ' ' . $this->object->getContext ()
					->getI18N ()
					->__ ( 'index criteria' ) );
			
			foreach ( $symbols as $k => $symbol ) {
				
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $row, $symbol->getTitle () );
				
				$this->sumEvaluateStudent ( $student_col, $row, $criteria_row_start, $criteria_row_end, $symbol->getSymbolCode () );
				
				$row ++;
				
				$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $row, 1 );
				
				if ($total_symbol > $k + 1) {
					$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . ($row - 1) . ':A' . ($row) );
					
					$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'B' . ($row - 1) . ':B' . ($row) );
				}
			}
									
			// Tinh ty le trung binh Linh vuc danh gia
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $row, $this->object->getContext ()
					->getI18N ()
					->__ ( 'PECENTAGE' ) . "\n" . $level_code );
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'B' . $row, $criteria_count . ' ' . $this->object->getContext ()
					->getI18N ()
					->__ ( 'index criteria' ) );
			
			foreach ( $symbols as $k => $symbol ) {
				
				$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $row, $symbol->getTitle () );
				
				$this->percantageEvaluateStudent ( $student_col, $row, $criteria_row_start, $criteria_row_end, $symbol->getSymbolCode (), $criteria_count );
				
				$row ++;
				
				$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $row, 1 );
				
				if ($total_symbol > $k + 1) {
					$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . ($row - 1) . ':A' . ($row) );
					
					$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'B' . ($row - 1) . ':B' . ($row) );
				}
			}
			$this->objPHPExcel->getActiveSheet ()
			->removeRow ( $row, 1 );
		}
								
		// Tinh tong cac chi so danh gia
		
		$criteria_row_start = reset ( $lever_row );
		$criteria_row_end = $start_row - 3 + count ( $lever_row ) * $total_symbol * 2;
		// $criteria_row_end = reset($criteria_row)['end'];
		$start_row = $criteria_row_end + 4;
		$this->objPHPExcel->getActiveSheet ()
		->insertNewRowBefore ( $start_row, 1 );
		
		$this->objPHPExcel->getActiveSheet ()
		->setCellValue ( 'A' . $start_row, $this->object->getContext ()
				->getI18N ()
				->__ ( 'SUM' ) . ' ' . $this->object->getContext ()
				->getI18N ()
				->__ ( "Month" ) . ' ' . $month );
		$this->objPHPExcel->getActiveSheet ()
		->setCellValue ( 'B' . $start_row, $this->criteria_total . ' ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'index criteria' ) );
		
		foreach ( $symbols as $k => $symbol ) {
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $symbol->getTitle () );
			
			$this->sumEvaluateStudent ( $student_col, $start_row, $criteria_row_start, $criteria_row_end, $symbol->getSymbolCode () );
			
			$start_row ++;
			
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
			if ($total_symbol > $k + 1) {
				$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . ($start_row - 1) . ':A' . ($start_row) );
				
				$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'B' . ($start_row - 1) . ':B' . ($start_row) );
			}
		}
								
		$this->objPHPExcel->getActiveSheet ()
		->setCellValue ( 'A' . $start_row, $this->object->getContext ()
				->getI18N ()
				->__ ( 'PECENTAGE' ) . ' ' . $this->object->getContext ()
				->getI18N ()
				->__ ( "Month" ) . ' ' . $month );
		$this->objPHPExcel->getActiveSheet ()
		->setCellValue ( 'B' . $start_row, $this->criteria_total . ' ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'index criteria' ) );
		
		foreach ( $symbols as $k => $symbol ) {
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $symbol->getTitle () );
			
			$this->percantageEvaluateStudent ( $student_col, $start_row, $criteria_row_start, $criteria_row_end, $symbol->getSymbolCode (), $this->criteria_total );
			
			$start_row ++;
			
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
			if ($total_symbol > $k + 1) {
				$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . ($start_row - 1) . ':A' . ($start_row) );
				
				$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'B' . ($start_row - 1) . ':B' . ($start_row) );
			}
		}
								
		// Đánh giá tỉ lệ tháng của lớp
		$this->objPHPExcel->getActiveSheet ()
		->setCellValue ( 'A' . $start_row, $this->object->getContext ()
				->getI18N ()
				->__ ( "PECENTAGE Class" ) );
		
		// Tổng tỉ lệ của cả tháng / lớp
		$col_end = PHPExcel_Cell::stringFromColumnIndex ( 2 + $total_student );
		
		$this->objPHPExcel->getActiveSheet ()
		->mergeCells ( 'D' . ($start_row) . ':' . $col_end . ($start_row) );
		
		$this->objPHPExcel->getActiveSheet ()
		->setCellValue ( 'D' . $start_row, "=SUM(D" . ($start_row - $total_symbol) . ":" . $col_end . ($start_row - $total_symbol) . ")/" . $total_student );
		$this->objPHPExcel->getActiveSheet ()
		->getStyle ( 'D' . $start_row )
		->getNumberFormat ()
		->applyFromArray ( array (
				'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE ) );
		foreach ( $symbols as $key => $symbol ) {
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, $symbol->getTitle () );
			
			$start_row ++;
			
			$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
			
			if ($total_symbol > $key + 1) {
				
				$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . ($start_row) . ':B' . ($start_row) );
				
				$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'A' . ($start_row - 1) . ':A' . ($start_row) );
				
				$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'B' . ($start_row - 1) . ':B' . ($start_row) );
			}
			
			$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'D' . ($start_row) . ':' . $col_end . ($start_row) );
			
			$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D' . $start_row, "=SUM(D" . ($start_row - $total_symbol) . ":" . $col_end . ($start_row - $total_symbol) . ")/" . $total_student );
			
			$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'D' . $start_row )
			->getNumberFormat ()
			->applyFromArray ( array (
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE ) );
		}
								
		$this->objPHPExcel->getActiveSheet ()
		->removeRow ( $start_row, 1 );
		
		$total_col = count ( $student_col );
		
		if ($total_col > 0) {
			switch ($total_col) {
				case 1 :
				case 2 :
				case 3 :
					$col = 4;
					break;
				default :
					$col = count ( $student_col ) - 1;
			}
		} else {
			$col = 4;
		}
	}
	
	public function setDataEvaluateExport123($students, $evaluate, $symbols, $param) {

		$total_student = count ( $students );

		$total_evaluate = count ( $evaluate );

		$total_symbol = count ( $symbols );

		$month = date ( 'm', strtotime ( $param ['month'] ) );

		$this->end_column_student = ($total_student >= 0) ? $start_column_student + $total_student : $start_column_student;

		switch ($total_student) {

			case 0 :
				$this->objPHPExcel->getActiveSheet ()
					->removeColumn ( 'D' );
				$this->objPHPExcel->getActiveSheet ()
					->removeColumn ( 'E' );
				break;
			case 1 :
				$this->objPHPExcel->getActiveSheet ()
					->removeColumn ( 'E' );
				break;
			case 2 :
				break;
			default :
				$this->objPHPExcel->getActiveSheet ()
					->insertNewColumnBefore ( 'E', $total_student - 2 );
		}
		// ký hiệu đánh giá
		foreach ( $symbols as $key => $symbol ) {

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $symbol->getTitle () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'H' . $start_row, $symbol->getSymbolCode () );

			$start_row ++;

			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			$this->symbol [$symbol->getTitle ()] = $symbol->getSymbolCode ();
		}

		// đoạn này xét chữ
		$row = $start_row - $total_symbol;
		$row2 = $start_row - 1;
		$BStyle = array (
				'borders' => array (
						'allborders' => array (
								'style' => PHPExcel_Style_Border::BORDER_THIN ) ) );
		$this->objPHPExcel->getActiveSheet ()
			->getStyle ( "H{$row}:H{$row2}" )
			->applyFromArray ( $BStyle );

		$start_row ++;

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, mb_strtoupper ( ($this->object->getContext ()
			->getI18N ()
			->__ ( 'Criteria code' )) ) ); // mã tiêu chí
		$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'A' . $start_row . ':A' . ($start_row + 1) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'B' . $start_row, mb_strtoupper ( $this->object->getContext ()
			->getI18N ()
			->__ ( 'Title' ) ) ); // Tiêu đề
		$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'B' . $start_row . ':B' . ($start_row + 1) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C' . $start_row, mb_strtoupper ( $this->object->getContext ()
			->getI18N ()
			->__ ( 'Time apply' ) ) ); // Thời gian áp dụng
		$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'C' . $start_row . ':C' . ($start_row + 1) );

		$this->objPHPExcel->getActiveSheet ()
			->getDefaultColumnDimension ()
			->setWidth ( 7 );
		
		$student_evaluate = array();
		
		foreach ( $students as $key => $student ) {

			/**
			 * $col la ten cot ung voi moi lan lap hoc sinh, gia tri cuoi cung la ten cot cua hox sinh cuoi cung
			 *
			 * @var $col
			 */
			$col = PHPExcel_Cell::stringFromColumnIndex ( $start_column_student + $key - 1 );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col . $start_row, $key + 1 );

			$row = $start_row + 1;

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col . $row, $student ['full_name'] . "\n" . $student ['student_code'] );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row )
				->getAlignment ()
				->setWrapText ( true );

			array_push ( $student_col, $col );
		}

		unset ( $student );
		$start_row = $start_row + 2;

		switch ($total_evaluate) { // tổng lĩnh vực

			case 0 :
				$this->objPHPExcel->getActiveSheet ()
					->removeRow ( $start_row );
				$this->objPHPExcel->getActiveSheet ()
					->removeRow ( $start_row + 1 );
				break;
			case 1 :
				$this->objPHPExcel->getActiveSheet ()
					->removeColumn ( $start_row + 1 );
				break;
			default :
			// $this->objPHPExcel->getActiveSheet()->insertNewColumnBefore('E', $total_student - 2);
		}
		$criteria_count = $total_criteria = 0;
		
		$this->objPHPExcel->getActiveSheet ()
			->removeRow ( $start_row );
		foreach ( $evaluate as $key => $eval ) {

			$start_row ++;

			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			if (isset ( $eval ['criteria_id'] ) && $eval ['criteria_id'] > 0) { // Nếu tồn tại tiêu chí đánh giá

				$criteria_count ++;

				$total_criteria ++;

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $eval ['criteria_code'] );

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'B' . $start_row, $eval ['criteria_title'] );

				// In ra tung chi so danh gia cua hoc sinh

				foreach ( $eval ['evaluate'] as $k => $val ) {

					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'C' . $start_row, $month );

					$col = PHPExcel_Cell::stringFromColumnIndex ( $start_column_student + $k - 1 );

					if ($val ['is_awaiting_approval'] > 0 && $val ['is_public'] > 0) {

						$this->objPHPExcel->getActiveSheet ()
							->setCellValue ( $col . $start_row, $val ['symbol_code'] );
					}
				}
			} else {

				if ($criteria_count > 0) {
					array_push ( $criteria_row, array (
							'end' => $start_row - 1,
							'start' => $criteria_row_start,
							'criteria_count' => $criteria_count,
							'level_code' => $level_code ) );
					$this->objPHPExcel->getActiveSheet ()
						->insertNewRowBefore ( $start_row, 1 );
					$start_row ++;
				}

				array_push ( $lever_row, $start_row );
				$level_code = $eval ['subject_code'];
				$criteria_count = 0;
				if (isset ( $evaluate [$key + 1] ['criteria_code'] )) {
					$criteria_row_start = $start_row + 1;
				}

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'A' . $start_row, $eval ['subject_code'] );
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'B' . $start_row, $eval ['subject_title'] );
			}
		}

		array_push ( $criteria_row, array (
				'end' => $start_row,
				'start' => $criteria_row_start,
				'criteria_count' => $criteria_count,
				'level_code' => $level_code ) );

		// echo '<pre>';
		// print_r ($criteria_row);
		// echo '</pre>';
		// die;
		//$student_evaluate = array_reverse ( $student_evaluate );

		foreach ( $lever_row as $row ) {

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( "A{$row}:B{$row}" )
				->getFont ()
				->setBold ( TRUE );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( "A{$row}:{$col}{$row}" )
				->getFill ()
				->setFillType ( PHPExcel_Style_Fill::FILL_SOLID )
				->getStartColor ()
				->setRGB ( 'FFFF00' );
		}

		$criteria_row = array_reverse ( $criteria_row );

		// print_r($criteria_row);

		foreach ( $criteria_row as $key => $criteria_row ) {

			$row = $criteria_row ['end'] + 1;
			$criteria_row_end = $criteria_row ['end'];
			$criteria_row_start = $criteria_row ['start'];
			$level_code = $criteria_row ['level_code'];
			$criteria_count = $criteria_row ['criteria_count'];
			$this->criteria_total += $criteria_count;

			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $row, 1 );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $row, $this->object->getContext ()
				->getI18N ()
				->__ ( 'SUM' ) . "\n" . $level_code );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'A' . $row )
				->getAlignment ()
				->setWrapText ( true );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( "A{$row}:B{$row}" )
				->getFont ()
				->setBold ( TRUE );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( "A{$row}:B{$row}" )
				->getAlignment ()
				->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER )
				->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $row, $criteria_count . ' ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'index criteria' ) );

			foreach ( $symbols as $k => $symbol ) {

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'C' . $row, $symbol->getTitle () );

				$this->sumEvaluateStudent ( $student_col, $row, $criteria_row_start, $criteria_row_end, $symbol->getSymbolCode () );

				$row ++;

				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $row, 1 );

				if ($total_symbol > $k + 1) {
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'A' . ($row - 1) . ':A' . ($row) );

					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'B' . ($row - 1) . ':B' . ($row) );
				}
			}

			// Tinh ty le trung binh Linh vuc danh gia

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $row, $this->object->getContext ()
				->getI18N ()
				->__ ( 'PECENTAGE' ) . "\n" . $level_code );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $row, $criteria_count . ' ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'index criteria' ) );

			foreach ( $symbols as $k => $symbol ) {

				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'C' . $row, $symbol->getTitle () );

				$this->percantageEvaluateStudent ( $student_col, $row, $criteria_row_start, $criteria_row_end, $symbol->getSymbolCode (), $criteria_count );

				$row ++;

				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $row, 1 );

				if ($total_symbol > $k + 1) {
					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'A' . ($row - 1) . ':A' . ($row) );

					$this->objPHPExcel->getActiveSheet ()
						->mergeCells ( 'B' . ($row - 1) . ':B' . ($row) );
				}
			}
			$this->objPHPExcel->getActiveSheet ()
				->removeRow ( $row, 1 );
		}

		// Tinh tong cac chi so danh gia

		$criteria_row_start = reset ( $lever_row );
		$criteria_row_end = $start_row - 3 + count ( $lever_row ) * $total_symbol * 2;
		// $criteria_row_end = reset($criteria_row)['end'];
		$start_row = $criteria_row_end + 4;
		$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $this->object->getContext ()
			->getI18N ()
			->__ ( 'SUM' ) . ' ' . $this->object->getContext ()
			->getI18N ()
			->__ ( "Month" ) . ' ' . $month );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'B' . $start_row, $this->criteria_total . ' ' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'index criteria' ) );

		foreach ( $symbols as $k => $symbol ) {

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $symbol->getTitle () );

			$this->sumEvaluateStudent ( $student_col, $start_row, $criteria_row_start, $criteria_row_end, $symbol->getSymbolCode () );

			$start_row ++;

			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			if ($total_symbol > $k + 1) {
				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . ($start_row - 1) . ':A' . ($start_row) );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'B' . ($start_row - 1) . ':B' . ($start_row) );
			}
		}

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $this->object->getContext ()
			->getI18N ()
			->__ ( 'PECENTAGE' ) . ' ' . $this->object->getContext ()
			->getI18N ()
			->__ ( "Month" ) . ' ' . $month );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'B' . $start_row, $this->criteria_total . ' ' . $this->object->getContext ()
			->getI18N ()
			->__ ( 'index criteria' ) );

		foreach ( $symbols as $k => $symbol ) {

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $symbol->getTitle () );

			$this->percantageEvaluateStudent ( $student_col, $start_row, $criteria_row_start, $criteria_row_end, $symbol->getSymbolCode (), $this->criteria_total );

			$start_row ++;

			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			if ($total_symbol > $k + 1) {
				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . ($start_row - 1) . ':A' . ($start_row) );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'B' . ($start_row - 1) . ':B' . ($start_row) );
			}
		}

		// Đánh giá tỉ lệ tháng của lớp
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $this->object->getContext ()
			->getI18N ()
			->__ ( "PECENTAGE Class" ) );

		// Tổng tỉ lệ của cả tháng / lớp
		$col_end = PHPExcel_Cell::stringFromColumnIndex ( 2 + $total_student );

		$this->objPHPExcel->getActiveSheet ()
			->mergeCells ( 'D' . ($start_row) . ':' . $col_end . ($start_row) );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D' . $start_row, "=SUM(D" . ($start_row - $total_symbol) . ":" . $col_end . ($start_row - $total_symbol) . ")/" . $total_student );
		$this->objPHPExcel->getActiveSheet ()
			->getStyle ( 'D' . $start_row )
			->getNumberFormat ()
			->applyFromArray ( array (
				'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE ) );
		foreach ( $symbols as $key => $symbol ) {

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $symbol->getTitle () );

			$start_row ++;

			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );

			if ($total_symbol > $key + 1) {

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . ($start_row) . ':B' . ($start_row) );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'A' . ($start_row - 1) . ':A' . ($start_row) );

				$this->objPHPExcel->getActiveSheet ()
					->mergeCells ( 'B' . ($start_row - 1) . ':B' . ($start_row) );
			}

			$this->objPHPExcel->getActiveSheet ()
				->mergeCells ( 'D' . ($start_row) . ':' . $col_end . ($start_row) );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, "=SUM(D" . ($start_row - $total_symbol) . ":" . $col_end . ($start_row - $total_symbol) . ")/" . $total_student );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( 'D' . $start_row )
				->getNumberFormat ()
				->applyFromArray ( array (
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE ) );
		}

		$this->objPHPExcel->getActiveSheet ()
			->removeRow ( $start_row, 1 );

		$total_col = count ( $student_col );

		if ($total_col > 0) {
			switch ($total_col) {
				case 1 :
				case 2 :
				case 3 :
					$col = 4;
					break;
				default :
					$col = count ( $student_col ) - 1;
			}
		} else {
			$col = 4;
		}
		/*
		 * $str_date = $this->object->getContext()
		 * ->getI18N()
		 * ->__('Day') . ' ' . date('d'). ' ' . $this->object->getContext()
		 * ->getI18N()
		 * ->__('Month') . ' ' . date('m') . ' ' . $this->object->getContext()
		 * ->getI18N()
		 * ->__('Year') . ' ' . date('Y');
		 * $this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $start_row, $str_date);
		 */
	}

	protected function sumEvaluateStudent($student_col, $row_apply, $criteria_row_start, $criteria_row_end, $symbolCode) {

		foreach ( $student_col as $col ) {
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col . $row_apply, "=COUNTIF(" . $col . $criteria_row_start . ":" . $col . ($criteria_row_end) . ",\"{$symbolCode}\")" );
			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row_apply )
				->getNumberFormat ()
				->applyFromArray ( array (
					'code' => PHPExcel_Style_NumberFormat::FORMAT_GENERAL ) );
		}
	}

	protected function percantageEvaluateStudent($student_col, $row_apply, $criteria_row_start, $criteria_row_end, $symbolCode, $criteria_count) {

		foreach ( $student_col as $col ) {
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( $col . $row_apply, "=(COUNTIF(" . $col . $criteria_row_start . ":" . $col . ($criteria_row_end) . ",\"{$symbolCode}\"))/(" . $criteria_count . ")" );

			$this->objPHPExcel->getActiveSheet ()
				->getStyle ( $col . $row_apply )
				->getNumberFormat ()
				->applyFromArray ( array (
					'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE ) );
		}
	}

	// protected function setSumDataCell($student_col, &$row, $symbols, $criteria_count)
	// {
	// $row++;
	// $total_symbol = count($symbols);
	// $this->objPHPExcel->getActiveSheet()->insertNewRowBefore($row, 1);

	// $this->objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $this->object->getContext()
	// ->getI18N()
	// ->__('SUM') . "\n" . $level_code);

	// $this->objPHPExcel->getActiveSheet()
	// ->getStyle('A' . $row)
	// ->getAlignment()
	// ->setWrapText(true);

	// $this->objPHPExcel->getActiveSheet()
	// ->getStyle("A{$row}:B{$row}")
	// ->getFont()
	// ->setBold(TRUE);

	// $this->objPHPExcel->getActiveSheet()
	// ->getStyle("A{$row}:B{$row}")
	// ->getAlignment()
	// ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
	// ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	// $this->objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $criteria_count . ' ' . $this->object->getContext()
	// ->getI18N()
	// ->__('index criteria'));

	// $row2 = $row;
	// foreach ($symbols as $k => $symbol) {

	// $this->objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $symbol->getTitle());

	// $row ++;

	// $this->objPHPExcel->getActiveSheet()->insertNewRowBefore($row, 1);

	// if ($total_symbol > $k + 1) {
	// $this->objPHPExcel->getActiveSheet()->mergeCells('A' . ($row - 1) . ':A' . ($row));

	// $this->objPHPExcel->getActiveSheet()->mergeCells('B' . ($row - 1) . ':B' . ($row));
	// }
	// }

	// foreach ($student_col as $col){
	// // $this->objPHPExcel->getActiveSheet()->setCellValue($col . $row2, );
	// $row2++;
	// }
	// }
}