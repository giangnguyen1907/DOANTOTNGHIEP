<?php

/**
 * @project_name
 *
 * @subpackage interpreter
 *            
 *             @file PsDateTime.class.php
 *             @filecomment filecomment
 * @package _declaration package_declaration
 *         
 * @author PreSchool.vn
 *        
 * @version 1.0 21-06-2017 - 09:01:15
 */
class PsDateTime {

	public $datetime;

	public function __construct() {

		$this->datetime = new DateTime ();
	}

	/**
	 *
	 * @return string : YYYY-MM-DD H:i:s
	 */
	public function getCurrentDateTime() {

		return $this->datetime->format ( 'Y-m-d H:i:s' );
	}

	/**
	 *
	 * @return string : YYYY-MM-DD
	 */
	public function getCurrentDate() {

		return $this->datetime->format ( 'Y-m-d' );
	}

	/**
	 * Ham tra ve thu cua ngay ($date => format: Y-m-d)
	 *
	 * @param
	 *        	date - string (Y-m-d)- Ngay trong tuan
	 * @return string - Monday name
	 */
	public static function getMondayOfDate($date, $patther = '-') {

		// yyyy-m-d
		list ( $year, $month, $day ) = explode ( $patther, $date );

		$wkday = date ( 'l', mktime ( '0', '0', '0', $month, $day, $year ) );

		return $wkday;
	}

	/**
	 * Ham tra ve so thu tu cua tuan trong nam ($date => format: Y-m-d) *
	 */
	public static function getIndexWeekOfYear($date) {

		while ( date ( 'w', strtotime ( $date ) ) != 1 ) {
			$tmp = strtotime ( '-1 day', strtotime ( $date ) );
			$date = date ( 'Y-m-d', $tmp );
		}
		return date ( 'W', strtotime ( $date ) );
	}

	/**
	 * Ham lay so tuan cua 1 nam
	 *
	 * @param
	 *        	int - $year
	 * @return int - So tuan cua nam
	 */
	public static function getNumberWeekOfYear($year) {

		return idate ( 'W', mktime ( 0, 0, 0, 12, 27, $year ) );
	}

	/**
	 * getStartAndEndDateOfWeek($week, $year, $format = 'Y-m-d') - Ham tra ve ngay bat dau va ket thuc cua 1 tuan
	 *
	 * @param $week -int
	 *        	thu tu tuan trong nam
	 * @param $year -int
	 *        	nam
	 * @param $format -
	 *        	string format date
	 * @return mixed
	 */
	public static function getStartAndEndDateOfWeek($week, $year, $format = 'Y-m-d') {

		$dto = new DateTime ();

		$ret ['week_index'] = $week;

		// Ngay bat dau cua tuan
		$ret ['week_start'] = $dto->setISODate ( $year, $week )
			->format ( $format );

		$list_day_in_week = array ();

		$list_day_in_week [$ret ['week_start']] = self::getMondayOfDate ( $ret ['week_start'] );

		// array_push($list_day_in_week, $ret['week_start']);

		for($i = 1; $i <= 6; $i ++) {

			$date = $dto->modify ( '+1 days' )
				->format ( $format );
			$list_day_in_week [$date] = self::getMondayOfDate ( $date );

			// array_push($list_day_in_week, $dto->modify('+1 days')->format($format));
		}

		$ret ['week_list'] = $list_day_in_week;

		// Ngay ket thuc cua tuan
		$ret ['week_end'] = $dto->modify ( '+0 days' )
			->format ( $format );

		return $ret;
	}

	/**
	 * tra ve ngay thu 7 trong tuan
	 *
	 * @return mixed
	 */
	public static function getStaturdayOfWeek($week, $year, $format = 'Y-m-d') {

		$dto = new DateTime ();

		$ret ['week_index'] = $week;

		// Ngay bat dau cua tuan
		$ret ['week_start'] = $dto->setISODate ( $year, $week )
			->format ( $format );

		$list_day_in_week = array ();

		$list_day_in_week [$ret ['week_start']] = self::getMondayOfDate ( $ret ['week_start'] );

		for($i = 1; $i <= 5; $i ++) {

			$date = $dto->modify ( '+1 days' )
				->format ( $format );
			$list_day_in_week [$date] = self::getMondayOfDate ( $date );
		}

		$ret ['week_list'] = $list_day_in_week;

		// Ngay ket thuc cua tuan
		$ret ['week_end'] = $dto->modify ( '+0 days' )
			->format ( $format );

		return $ret;
	}

	/**
	 * Ham lay danh sach tuan trong nam
	 * VD: Tuan 1: dd/mm/yyyy - dd/mm/yyyy
	 * Tuan 2: dd/mm/yyyy - dd/mm/yyyy
	 *
	 * @param
	 *        	int - $year
	 * @return mixed
	 */
	public static function getWeeksOfYear($year, $format = 'Y-m-d') {

		$number_week = self::getNumberWeekOfYear ( $year );

		$array_weeks = array ();

		for($i = 1; $i <= $number_week; $i ++) {

			array_push ( $array_weeks, self::getStartAndEndDateOfWeek ( $i, $year, $format ) );
		}

		return $array_weeks;
	}

	/**
	 * Ham lay danh sach thu 7 cua tuan tuan trong nam
	 * VD: Tuan 1: dd/mm/yyyy - dd/mm/yyyy
	 * Tuan 2: dd/mm/yyyy - dd/mm/yyyy
	 *
	 * @param
	 *        	int - $year
	 * @return mixed
	 */
	public static function getSaturdayOfWeeks($year, $format = 'Y-m-d') {

		$number_week = self::getNumberWeekOfYear ( $year );

		$array_weeks = array ();

		for($i = 1; $i <= $number_week; $i ++) {

			array_push ( $array_weeks, self::getStaturdayOfWeek ( $i, $year, $format ) );
		}

		return $array_weeks;
	}

	// Lay option for choi
	public static function getOptionsWeeks($weeks) {

		$chois = array ();

		foreach ( $weeks as $week ) {

			$chois [$week ['week_index']] = sfContext::getInstance ()->getI18n ()
				->__ ( 'Week' ) . ' ' . $week ['week_index'] . ': ' . PsDateHelper::format_date ( $week ['week_start'] ) . ' -> ' . PsDateHelper::format_date ( $week ['week_end'] );
		}

		return $chois;
	}

	// // Lay ra tuan cua nam va ngay thu 7 trong do
	public static function getOptionsSaturday($weeks) {

		$chois = array ();

		foreach ( $weeks as $week ) {
			$chois [$week ['week_index']] = sfContext::getInstance ()->getI18n ()
				->__ ( 'Week' ) . ' ' . $week ['week_index'] . ': ' . PsDateHelper::format_date ( $week ['week_end'] );
		}

		return $chois;
	}

	/**
	 * convert date to date int *
	 */
	public static function psDatetoTime($date, $now = null) {

		return strtotime ( $date, $now );
	}

	/**
	 * convert int to date *
	 */
	public static function psTimetoDate($timestamp = null, $format = "Y-m-d") {

		return $timestamp != '' ? date ( $format, $timestamp ) : date ( $format );
	}

	/**
	 * Ham tra ve mang chua danh sach Nam-thang
	 *
	 * @author Nguyen Chien Thang(ntsc279@gmail.com)
	 *        
	 * @param $yearsMonthStart -
	 *        	yyyymmdd
	 * @param $yearsMonthEnd -
	 *        	yyyymmdd
	 * @return mixed
	 */
	public static function psRangeMonthYear($yearsMonthStart, $yearsMonthEnd) {

		$year_month = array ();
		$k = 0;
		for($i = $yearsMonthStart; $i <= $yearsMonthEnd; $i = date ( "Y-m", strtotime ( $yearsMonthStart . " +" . $k . " Month" ) )) {
			//echo $i.'<br>';
			$month_year = date ( "m-Y", strtotime ( $i . '-01' ) );

			array_push ( $year_month, $month_year );

			$k ++;
		}

		return array_combine ( $year_month, $year_month );
	}
	
	/**
	 * Ham tra ve mang chua danh sach Nam-thang
	 *
	 * @author Nguyen Chien Thang(ntsc279@gmail.com)
	 *
	 * @param $yearsMonthStart - string yyyymmdd
	 * @param $yearsMonthEnd - string yyyymmdd
	 * @return mixed
	 */
	public static function psRangeYYYYMM($yearsMonthStart, $yearsMonthEnd) {
		
		$year_month_text = array ();
		$year_month_option = array ();
		
		$k = 0;
		for($i = $yearsMonthStart; $i <= $yearsMonthEnd; $i = date ( "Y-m", strtotime ( $yearsMonthStart . " +" . $k . " Month" ) )) {
			
			$month_year = date ( "m-Y", strtotime ( $i . '-01' ) );
			
			array_push ( $year_month_text, $month_year );
			array_push ( $year_month_option, date ( "Ym", strtotime ( $i . '-01' ) ) );
			
			$k ++;
		}
		
		return array_combine ( $year_month_option, $year_month_text );
	}

	/**
	 * Ham tra ve ngay thu 7 trong 1 tháng
	 *
	 * @param $month_year: mm-yyyy
	 * @return mixed
	 */
	public static function psSaturdaysOfMonth($month_year) {

		$day_month_year = '01-' . $month_year;

		$number_day_of_thang = date ( 't', self::psDatetoTime ( $day_month_year ) );

		$number_sunday = 0;

		$number_saturday = 0;

		$array_std = array ();

		for($day = 1; $day <= $number_day_of_thang; $day ++) {

			$date = $day . '-' . $month_year;

			$w = date ( 'w', self::psDatetoTime ( $date ) );

			if ($w == 6)
				array_push ( $array_std, $day );
		}

		return $array_std;
	}

	/**
	 * Ham tra ve ngay chu nhat trong 1 tháng
	 *
	 * @param $month_year: mm-yyyy
	 * @return mixed
	 */
	public static function psSundaysOfMonth($month_year) {

		$day_month_year = '01-' . $month_year;

		$number_day_of_thang = date ( 't', self::psDatetoTime ( $day_month_year ) );

		$number_sunday = 0;

		$number_saturday = 0;

		$array_sun = array ();

		for($day = 1; $day <= $number_day_of_thang; $day ++) {

			$date = $day . '-' . $month_year;

			$w = date ( 'w', self::psDatetoTime ( $date ) );

			if ($w == 0)
				array_push ( $array_sun, $day );
		}

		return $array_sun;
	}

	/**
	 * Ham tra ve so ngay, so ngay co thu 7, so ngay co CN trong 1 thang
	 *
	 * @param $month_year: mm-yyyy
	 * @return mixed
	 */
	public static function psNumberDaysOfMonth($month_year) {

		$day_month_year = '01-' . $month_year;

		$number_day_of_thang = date ( 't', self::psDatetoTime ( $day_month_year ) );

		$number_sunday = 0;

		$number_saturday = 0;

		for($day = 1; $day <= $number_day_of_thang; $day ++) {

			$date = $day . '-' . $month_year;

			$w = date ( 'w', self::psDatetoTime ( $date ) );

			if ($w == 0)
				$number_sunday ++;
			elseif ($w == 6)
				$number_saturday ++;
		}

		$normal_day = $number_day_of_thang - $number_sunday - $number_saturday;

		return array (
				'number_day_month' => $number_day_of_thang,
				'normal_day' => $normal_day,
				'saturday_day' => ($normal_day + $number_saturday) );
	}
	
	// Số ngày đi học còn lại trong tháng
	public static function psNumberDaysOfMonth2($day_month_year) {

		//$day_month_year = '01-' . $month_year;
		
		$month_year = date('m-Y',strtotime($day_month_year));
		
		$day = date('d',strtotime($day_month_year))*1;
		
		$number_day_of_thang = date ( 't', self::psDatetoTime ( $day_month_year ) );

		$number_sunday = 0;
		$normal_day = 0;
		$number_saturday = 0;
		$ngayConLai = 0;
		
		for($day; $day <= $number_day_of_thang; $day ++) {
			
			$ngayConLai++;
			
			//echo $day_month_year.'<br>';
			$date = $day . '-' . $month_year;

			$w = date ( 'w', self::psDatetoTime ( $date ) );

			if ($w == 0)
				$number_sunday ++;
			elseif ($w == 6)
				$number_saturday ++;
		}

		$normal_day = $ngayConLai - $number_sunday - $number_saturday;
		/**/
		return array (
				'number_day_month' => $number_day_of_thang,
				'normal_day' => $normal_day,
				'saturday_day' => ($normal_day + $number_saturday) );
	}
	
	
	/**
	 * Ham tra ve dnah sach cac ngày thu 7 cua tháng hiện tại và tháng kế tiếp
	 *
	 * @param $dayvalue -
	 *        	ten thứ can lay: Sun Mon Tue Wed Thu Fri Sat hoặc ten du cua thứ cần lấy
	 * @return $list - mixed
	 */
	public static function psListDaysValueOfMonth($dayvalue) {

		$list_date = array ();
		$startdate = strtotime ( $dayvalue );
		$enddate = strtotime ( "+1 month", $startdate );
		while ( $startdate < $enddate ) {
			$startdate_convert = date ( "d-m-Y", $startdate );
			array_push ( $list_date, $startdate_convert );
			$startdate = strtotime ( "+1 week", $startdate );
		}
		return $list_date;
	}

	/**
	 * *
	 * Ham tra ve số tháng giữa 2 thời điểm
	 */
	public static function psNumberMonthByDate($from_date, $to_date) {

		$datetime1 = date_create ( $from_date );
		$datetime2 = date_create ( $to_date );
		$interval = date_diff ( $datetime1, $datetime2 );
		return $interval->format ( '%m' );
	}

	/**
	 * Hàm quy đổi số phút ra giờ
	 *
	 * @author kidsschool.vn
	 * @param $minute -
	 *        	int
	 * @param $format -
	 *        	string
	 */
	public static function psMinutetoHour($minute, $format = '%02d:%02d') {

		if ($minute < 1) {
			return;
		}
		$hours = floor ( $minute / 60 );
		$minutes = ($minute % 60);
		return sprintf ( $format, $hours, $minutes );
	}
}