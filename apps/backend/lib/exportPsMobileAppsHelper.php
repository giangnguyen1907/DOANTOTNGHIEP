<?php
class exportPsMobileAppsHelper extends ExportHelper {

	private $start_row = 10;

	protected $sheet_index = 0;

	public function __construct($object) {

		parent::__construct ();

		$this->object = $object;
	}

	/**
	 * Tao tieu de cho thong ke sinh vien theo truong, co so
	 */
	public function setCustomerInfoExport($school_info = null, $customer_info = null, $workplace_info = null, $month = null) {

		if ($customer_info != null) {
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A1', $customer_info->getTitle () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A2', $this->object->getContext ()
				->getI18N ()
				->__ ( Address ) . ': ' . $customer_info->getAddress () . ' - ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'Tel' ) . ': ' . ($customer_info->getTel () ? $customer_info->getTel () : $customer_info->getMobile ()) );

			$this->objPHPExcel->getActiveSheet ()
				->setTitle ( $customer_info->getTitle () );
		}

		if ($workplace_info != null) {

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A2', $workplace_info->getTitle () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A3', $this->object->getContext ()
				->getI18N ()
				->__ ( Address ) . ': ' . $workplace_info->getAddress () . ' - ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'Tel' ) . ': ' . $workplace_info->getPhone () );

			$this->objPHPExcel->getActiveSheet ()
				->setTitle ( $workplace_info->getTitle () );
		}

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A6', $this->object->getContext ()
			->getI18N ()
			->__ ( 'LIST RELATIVE ACCOUNTS ACTIVE' ) . " {$month}" );

		if ($school_info != null) {
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D7', $this->object->getContext ()
				->getI18N ()
				->__ ( 'Year' ) . ": {$school_info['title']}" );
		} else {
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D7', '' );
		}
	}

	/**
	 * Tao tieu de cho thong ke sinh vien theo truong, co so
	 */
	public function setCustomerInfoExportCrossChecking($customer_info = null, $param = null) {

		if ($customer_info != null) {
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A1', $customer_info->getCusSchoolName () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A2', $customer_info->getWpName () );

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A3', $this->object->getContext ()
				->getI18N ()
				->__ ( Address ) . ': ' . $customer_info->getWpAddress () . ' - ' . $this->object->getContext ()
				->getI18N ()
				->__ ( 'Tel' ) . ': ' . $customer_info->getWpPhone () );

			$this->objPHPExcel->getActiveSheet ()
				->setTitle ( $customer_info->getMcName () );
		}

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A6', $this->object->getContext ()
			->getI18N ()
			->__ ( 'CROSS CHECKING RELATIVE ACCOUNTS' ) );

		$str = $this->object->getContext ()
			->getI18N ()
			->__ ( 'From' ) . ": {$param['from_date']}   " . $this->object->getContext ()
			->getI18N ()
			->__ ( 'To' ) . ": {$param['to_date']}";
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'E7', $str );
	}

	/**
	 * Set data cho bao cao danh sach tai khoan phu huynh
	 */
	public function setDataExport($data_accounts) {

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
			->__ ( 'Total' );

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
			->__ ( 'Account' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'E9', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Device' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F9', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Actived at' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G9', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Note' ) );

		$start_row = 10;

		$total_account = count ( $data_accounts );

		foreach ( $data_accounts as $key => $account ) {

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, ($key + 1) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $account->getFirstName () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $account->getLastName () );
			// $this->objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $account->getUsername()."\n"."Email: {$account->getEmailAddress()}");
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $account->getUsername () );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, "OS: {$account->getOsname()}\nOs vesion: {$account->getOsvesion()}" );
			$actived_at = date ( 'H:m d/m/Y', strtotime ( $account->getActiveCreatedAt () ) );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $actived_at );

			$this->objPHPExcel->getActiveSheet ()
				->getRowDimension ( $start_row )
				->setRowHeight ( - 1 );

			if ($total_account != $key + 1) {
				$start_row ++;
				$this->objPHPExcel->getActiveSheet ()
					->insertNewRowBefore ( $start_row, 1 );
			}
		}

		$start_row = $start_row + 2;
		// $this->objPHPExcel->getActiveSheet()->insertNewRowBefore($start_row, 1);

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, "{$total}: {$total_account}" );
		// $this->objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $granted . $granted_count);

		// $this->objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		$start_row ++;
		$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );

		// $this->objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $active_app . $active_count);
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );
	}

	public function setDataExportCrossChecking($list_month, $relative_arr) {

		$up = '↑ ';
		$down = '↓ ';
		$day = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Day' );
		$year = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Year' );

		$total = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Total' );

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A9', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Month' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'B9', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Relatives' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'B10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Created on month' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'C10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Deleted on month' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Total relative' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'E9', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Users account' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'E10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Actived on month' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Total user' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'G10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Account quantity change' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'H10', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Mobile actived during the month' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'I9', $this->object->getContext ()
			->getI18N ()
			->__ ( 'Note' ) );

		$start_row = 11;
		$total_month = count ( $list_month );

		// In dữ liệu trước ngày được chọn
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $this->object->getContext ()
			->getI18N ()
			->__ ( 'Before' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D' . $start_row, $relative_arr ['total_relative_before'] );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F' . $start_row, $relative_arr ['total_account_active_before'] );

		$start_row ++;
		$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );

		$total_relative = $relative_arr ['total_relative_before'];
		$total_user = $relative_arr ['total_account_active_before'];
		foreach ( $list_month as $key => $month ) {

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'A' . $start_row, $month ['month'] );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'B' . $start_row, $month ['created_on_month'] );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'C' . $start_row, $month ['deleted_on_month'] );

			$total_relative = $total_relative + ( int ) $month ['created_on_month'] - ( int ) $month ['deleted_on_month'];

			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'D' . $start_row, $total_relative );
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'E' . $start_row, $month ['total_account'] );

			$total_user2 = $total_user;
			$total_user = $total_user + $month ['total_account'];
			$this->objPHPExcel->getActiveSheet ()
				->setCellValue ( 'F' . $start_row, $total_user );
			if ($key > 0 && $month ['total_account'] > 0) {
				$key_down = $key - 1;
				$change = $total_user - $total_user2;
				if ($change > 0) {
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'G' . $start_row, $up . $change );
					// $this->objPHPExcel->getActiveSheet()->getStyle('G' . $start_row)->getFont()->setBold(true)->getColor()->setRGB('FFFF00');
				} else if ($change < 0) {
					$change = abs ( $change );
					$this->objPHPExcel->getActiveSheet ()
						->setCellValue ( 'G' . $start_row, $down . $change );
					// $this->objPHPExcel->getActiveSheet()->getStyle('G' . $start_row)->getFont()->setBold(true)->getColor()->setRGB('FFFF00');
				}
			}

			if ($month ['total_mobile'] != 0)
				$this->objPHPExcel->getActiveSheet ()
					->setCellValue ( 'H' . $start_row, $month ['total_mobile'] );

			$start_row ++;
			$this->objPHPExcel->getActiveSheet ()
				->insertNewRowBefore ( $start_row, 1 );
		}

		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'A' . $start_row, $this->object->getContext ()
			->getI18N ()
			->__ ( 'Total' ) );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'D' . $start_row, $total_relative );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'E' . $start_row, $this->object->getContext ()
			->getI18N ()
			->__ ( 'Locked' ) . ": {$relative_arr['total_account_lock']}" );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'F' . $start_row, abs ( $total_user - $relative_arr ['total_account_lock'] ) );

		$this->objPHPExcel->getActiveSheet ()
			->getStyle ( "A{$start_row}:I{$start_row}" )
			->getFont ()
			->setBold ( true );
		$this->objPHPExcel->getActiveSheet ()
			->getStyle ( "A{$start_row}:I{$start_row}" )
			->getFill ()
			->applyFromArray ( array (
				'startcolor' => array (
						'rgb' => 'ffd4009c' ) ) );
		$this->objPHPExcel->getActiveSheet ()
			->getStyle ( "A11:I11" )
			->getFont ()
			->setBold ( true );
		$this->objPHPExcel->getActiveSheet ()
			->getStyle ( "A11:I11" )
			->getFill ()
			->applyFromArray ( array (
				'startcolor' => array (
						'rgb' => 'ffd4009c' ) ) );
		$start_row = $start_row + 3;

		$this->objPHPExcel->getActiveSheet ()
			->insertNewRowBefore ( $start_row, 1 );
		$month = $this->object->getContext ()
			->getI18N ()
			->__ ( 'Month' );
		$this->objPHPExcel->getActiveSheet ()
			->setCellValue ( 'H' . $start_row, $day . ' ' . date ( 'd' ) . ' ' . $month . ' ' . date ( 'm' ) . ' ' . $year . ' ' . date ( 'Y' ) );
	}
}
