<?php
namespace App\PsUtil;

/**
 */
class PsDateTime {

	/*
	 * convert yyyy-mm-dd to dd-mm-yyyy
	 * @author thangnc@newwaytech.vn
	 *
	 * @param $date - yyyy-mm-dd
	 * @return string
	 */
	public static function toDMY($date, $format = 'd-m-Y') {

		return ($date != '') ? date ( $format, strtotime ( $date ) ) : '';
	}

	public static function toWeek($date, $format = 'd-m-Y') {

		return ($date != '') ? date ( $format, strtotime ( $date ) ) : '';
	}

	public static function toMY($date, $format = 'm-Y') {

		return ($date != '') ? date ( $format, strtotime ( $date ) ) : '';
	}

	/*
	 * convert dd-mm-yyyy to yyyy-mm-dd
	 * @author thangnc@newwaytech.vn
	 *
	 * @param $date - dd-mm-yyyy
	 * @return string
	 */
	public static function toYMD($date) {

		return ($date != '') ? date ( 'Y-m-d', strtotime ( $date ) ) : '';
	}

	/*
	 * toDateTimeToTime($datetime)
	 * @author thangnc@newwaytech.vn
	 *
	 * @param $datetime
	 * @return string
	 */
	public static function toDateTimeToTime($datetime) {

		$datetime = strtotime ( $datetime );
		
		return ($datetime != '') ? date ( "H", $datetime ) . 'h' . date ( "i", $datetime ) : '';
	}

	/*
	 * toDayInWeek($date)
	 * @author thangnc@newwaytech.vn
	 *
	 * @param $date
	 * @return string
	 */
	public static function toDayInWeek($date, $code_lang = APP_CONFIG_LANGUAGE) {

		$psI18n = new PsI18n ( $code_lang );
		
		$week = array (
				$psI18n->__ ( 'Monday' ),
				$psI18n->__ ( 'Tuesday' ),
				$psI18n->__ ( 'Wednesday' ),
				$psI18n->__ ( 'Thursday' ),
				$psI18n->__ ( 'Friday' ),
				$psI18n->__ ( 'Saturday' ),
				$psI18n->__ ( 'Sunday' ) 
		);
		
		$day_of_week = date ( 'N', strtotime ( $date ) );
		
		return $week [$day_of_week - 1];
	}

	/*
	 * toDateInWeek($date)
	 * @author thangnc@newwaytech.vn
	 *
	 * @param $date
	 * @return string
	 */
	public static function toFullDayInWeek($date, $code_lang = APP_CONFIG_LANGUAGE) {

		$psI18n = new PsI18n ( $code_lang );
		
		return self::toDayInWeek ( $date, $code_lang ) . ', ' . $psI18n->__ ( 'Day' ) . ' ' . self::toDMY ( $date );
	}

	/*
	 * toDMYByTimestamp($timestamp)
	 * @author thangnc@newwaytech.vn
	 *
	 * @param $timestamp - timestamp
	 * @return string- d/m/y
	 */
	public static function toDMYByTimestamp($timestamp) {

		return ($timestamp != '') ? date ( 'd/m/Y', $timestamp ) : '';
	}

	/**
	 * Ham tinh tuoi cua hoc sinh
	 *
	 * @author thangnc@newwaytech.vn
	 *        
	 * @param $date -
	 *        	string
	 * @return string
	 *
	 */
	public static function getAge($birthday, $date, $show_text = true, $lang = APP_CONFIG_LANGUAGE)
    {
        $endtime = strtotime($date) - strtotime($birthday);
        
        $days = (date("j", $endtime) - 1);
        
        /*
         * if ($days < 10)
         * $days = '0' . $days;
         */
        
        $months = (date("n", $endtime) - 1);
        /*
         * if ($months < 10)
         * $months = '0' . $months;
         */
        
        $years = (date("Y", $endtime) - 1970);
        /*
         * if ($years < 10)
         * $years = '0' . $years;
         */
        
        $psI18n = new PsI18n($lang);
        
        if ($show_text) {
            $t = $psI18n->__('Age');
            $th = $psI18n->__('Month');
            $ng = ' ' . $psI18n->__('Day');
        } else {
            $t = $psI18n->__('t');
            $th = $psI18n->__('th');
            $ng = $psI18n->__('ng');
        }
        
        if ($years != 0) {
            $ago = $years . $t . ' ' . $months . $th;
        } else {
            $ago = ($months == 0 ? $days . $ng : $months . $th);
        }
        return $ago;
    }

    /**
     * Ham tinh tuoi cua hoc sinh
     *
     * @author thangnc@newwaytech.vn
     *        
     * @param $date -
     *            string
     * @return string
     *
     */
    public static function getAgeMonth($birthday, $date, $show_text = true, $lang = APP_CONFIG_LANGUAGE)
    {
        $endtime = strtotime($date) - strtotime($birthday);
        
        $days = (date("j", $endtime) - 1);
        
        $months = (date("n", $endtime) - 1);
        
        $years = (date("Y", $endtime) - 1970);
        
        $psI18n = new PsI18n($lang);
        
        if ($show_text) {
            $t = $psI18n->__('Age');
            $th = $psI18n->__('Month');
            $ng = ' ' . $psI18n->__('Day');
        } else {
            $t = $psI18n->__('t');
            $th = $psI18n->__('th');
            $ng = $psI18n->__('ng');
        }
        
        if ($years != 0) {
            $ago = ($years * 12 + $months);
            if ($ago < 10)
                $ago = "0" . $ago;
            $ago = $ago . " " . $th;
        } else {
            $ago = ($months == 0 ? $days . " " . $ng : $months . " " . $th);
        }
        
        return $ago;
    }

    public static function getTime($time)
    {
        $times = date('H:i', strtotime($time));
        return $times;
    }
    
    // lay tuan hien theo ngay
    public static function getWeekNumber($date)
    {
        $week = date('W', strtotime($date));
        return $week;
    }
    // tra ve ngay dau tien cua tuan theo ngay
    public static function getFirstDayOfWeek($date)
    {
        $dayofweek = date('w', strtotime($date));
        $date_from = date('Y-m-d', strtotime((- $dayofweek + 1) . ' day', strtotime($date)));
        return $date_from;
    }
    // tra ve ngay cuoi cung cua tuan theo ngay
    public static function getLastDayOfWeek($date)
    {
        $dayofweek = date('w', strtotime($date));
        $date_to = date('Y-m-d', strtotime((- $dayofweek + 7) . ' day', strtotime($date)));
        return $date_to;
    }
    
    /**
	 * Ham tra ve thu cua ngay trong tuan ($date => format: Y-m-d)
	 * 0 - Chu nhat; 1 - Thu hai; 2 - Thu ba; 3 - Thu tu; 4 - Thu 5; 5- Thu 6; 6- Thu 7
	 * 
	 * @param  date - string (Y-m-d)- Ngay trong tuan
	 * @return int
	 */
	public static function getNumberDayOfDate($date, $patther = '-') {

		// yyyy-m-d
		list ($year, $month, $day) = explode($patther, $date);
		
		$wkday = date('w', mktime('0', '0', '0', $month, $day, $year));
		
		return $wkday;
	}
    
    // tra ve ngay dau tien cua tuan theo number week
    public static function getFirstDayOfWeekByWeek($week)
    {
        $year = date('Y');
        $date_to = date('Y-m-d', strtotime($year . 'W' . $week));
        return $date_to;
    }
    
    // tra ve ngay cuoi cung cua tuan theo number week
    public static function getLastDayOfWeekByWeek($week)
    {
        $year = date('Y');
        $date_to = date('Y-m-d', strtotime($year . 'W' . $week));
        $date_from = date('Y-m-d', strtotime($date_to . " + 1 weeks"));
        return $date_from;
    }
    
    /**
	 * Ham tra ve thu cua ngay ($date => format: Y-m-d)
	 *
	 * @param  date - string (Y-m-d)- Ngay trong tuan
	 * @return string - Monday name
	 */
	public static function getMondayOfDate($date, $patther = '-') {

		// yyyy-m-d
		list ($year, $month, $day) = explode($patther, $date);
		
		$wkday = date('l', mktime('0', '0', '0', $month, $day, $year));
		
		return $wkday;
	
	}
    
    // Ham tra ve so tuan cua thang
    function get_weeks($month, $year)
    {
        $nb_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $first_day = date('w', mktime(0, 0, 0, $month, 1, $year));
        
        if ($first_day > 1 && $first_day < 6) {
            // month started on Tuesday-Friday, no chance of having 5 weeks
            return 4;
        } else 
            if ($nb_days == 31)
                return 5;
            else 
                if ($nb_days == 30)
                    return ($first_day == 0 || $first_day == 1) ? 5 : 4;
                else 
                    if ($nb_days == 29)
                        return $first_day == 1 ? 5 : 4;
    }
    
    // lay so thu tu tuan theo ngay
    function weekOfMonth($date)
    {
        $firstOfMonth = date("Y-m-01", strtotime($date));
        if ($date == $firstOfMonth) {
            return 1;
        } else
            return intval(date("W", strtotime($date))) - intval(date("W", strtotime($firstOfMonth)));
    }
    
    /**
	 * Ham tra ve so thu tu cua tuan trong nam ($date => format: Y-m-d) *
	 */
	public static function getIndexWeekOfYear($date) {

		while (date('w', strtotime($date)) != 1) {
			$tmp = strtotime('-1 day', strtotime($date));
			$date = date('Y-m-d', $tmp);
		}
		return date('W', strtotime($date));
	
	}
	
/**
	 * getStartAndEndDateOfWeek($week, $year, $format = 'Y-m-d') - Ham tra ve ngay bat dau va ket thuc cua 1 tuan
	 *
	 * @param $week -int
	 *        	thu tu tuan trong nam
	 * @param $year -int
	 *        	nam
	 * @param $format - string format date
	 *        	
	 * @return mixed
	 */
	public static function getStartAndEndDateOfWeek($week, $year, $format = 'Y-m-d') { 
		
		$dto = new DateTime();
		
		$ret['week_index'] = $week; 
		
		// Ngay bat dau cua tuan
		$ret['week_start'] = $dto->setISODate($year, $week)->format($format);
		
		$list_day_in_week = array();
		
		
		$list_day_in_week[$ret['week_start']] = self::getMondayOfDate($ret['week_start']);
		
		for ($i = 1; $i <= 6; $i ++) {
			
			$date = $dto->modify('+1 days')->format($format);
			$list_day_in_week[$date] = self::getMondayOfDate($date);
			
			//array_push($list_day_in_week, $dto->modify('+1 days')->format($format));
		}
		
		$ret['week_list'] = $list_day_in_week;
		
		// Ngay ket thuc cua tuan
		$ret['week_end'] = $dto->modify('+0 days')->format($format);
		
		return $ret;
	
	}
    
    // ham check thoi gian
    function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}