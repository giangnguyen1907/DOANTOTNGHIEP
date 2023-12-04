<?php

class PreSchool {
	const PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO = 'https://quanly.kidsschool.vn/cache/image/app_logo_default.png';

	const PS_DETECTION_TYPE_MOBILE   = 'phone';
	const PS_DETECTION_TYPE_TABLE    = 'table';
	const PS_DETECTION_TYPE_COMPUTER = 'computer';
	
	const PS_CONST_PLATFORM_IOS = 'IOS';

	const PS_CONST_PLATFORM_ANDROID = 'ANDROID';
	
	// Goi cuoc
	public static $package_fee = array(
			1,
			3,
			6,
			12
	);
	
	// =========== LIST: template export report
	const PS_TEMPLATE_FEE_REPORT_01 = 'ps_fee_report01.xls';
	
	// Bỏ
	// Bao phi goc
	const PS_TEMPLATE_RECEIPT_REPORT_02 = 'ps_receipt_report01.xls';
	
	// Bỏ
	
	// Mau phieu thu goc
	const PS_TEMPLATE_STUDENT_REPORT_03 = 'ps_student_report01.xls';
	
	// Bỏ
	
	// Mau xuat ra danh sach hoc sinh toan bo 1 co so
	const PS_TEMPLATE_RECEIPT_01 = '01';

	const PS_TEMPLATE_RECEIPT_02 = '02';

	const PS_TEMPLATE_RECEIPT_03 = '03';
	
	const PS_TEMPLATE_RECEIPT_04 = '04';
	
	// =========== END: list template export report
	const TITLE_LATE = '01';

	const MEDIA_TYPE_TEACHER = '01';

	const MEDIA_TYPE_RELATIVE = '02';

	const MEDIA_TYPE_STUDENT = '03';

	const MEDIA_TYPE_CAMERA = '04';

	const DEFAULT_CURRENCY = 'VND';

	const ACTIVE = 1;

	const NOT_ACTIVE = 0;

	const LOCK = 2;

	const FILE_GROUP_SERVICE = 'SERVICE';

	const FILE_GROUP_FEATURE = 'FEATURE';

	const FILE_GROUP_FOODS = 'FOOD';

	const USER_TYPE_TEACHER = 'T';

	const USER_TYPE_RELATIVE = 'R';
	
	const USER_TYPE_MANAGER = 'M';// Quan ly Cap So -  Phong
	
	// BEGIN: Loại dich vu
	const SERVICE_TYPE_SCHEDULE = 2;
	
	// Dich vu ngoai khoa (khi do can tinh toi co dinh hay theo buoi) - Bo
	const SERVICE_TYPE_FIXED = 1;
	
	// Co dinh
	const SERVICE_TYPE_NOT_FIXED = 0;
	
	// Tinh theo so lan su dung
	
	// END: Loai dich vu
	const CUSTOMER_NOT_ACTIVATED = 0;

	const CUSTOMER_ACTIVATED = 1;

	const CUSTOMER_LOCK = 2;

	const CUSTOMER_NOT_DEPLOY = 0;

	const CUSTOMER_DEPLOYING = 1;
	
	// ---- BEGIN: Trang thai hoc sinh trong lop: Hoc thu, Chinh thuc, Tam dung, Tot nghiep, Thoi hoc ---------->
	
	// Da chuyen
	const SC_STATUS_FINISHED = 'CL';
	
	
	
	
	
	// Chua phan lop
	const NOT_IN_CLASS = 'NIC';
	
	// Lop da khoa
	const CLASS_LOCKED = 'CLL';
	
	// ---- END: Trang thai hoc sinh trong lop
	
	// Chinh thuc - Dang hoc
	const S_STATUS_ACTIVITIES = 'CT';
	
	// Hoc thu
	const S_STATUS_SCHOOL_TEST = 'HT';
	
	// Tam dung
	const S_STATUS_PAUSE = 'TD';
	
	// Thoi hoc
	const S_STATUS_STOP_STUDYING = 'TH';
	
	// Tot nghiep
	const S_STATUS_GRADUATION = 'TN';

	/**
	 * BEGIN: : MODULE ps_constant - constant option *
	 */
	const CONSTANT_OPTION_DEFAULT_LOGIN = 'DEFAULT_LOGIN';

	const CONSTANT_OPTION_DEFAULT_LOGOUT = 'DEFAULT_LOGOUT';

	const CONSTANT_OPTION_LATE_MONEY = 'LATE_MONEY';

	const CONSTANT_OPTION_FULL_DAY = 'FULL_DAY';

	const CONSTANT_OPTION_NORMAL_DAY = 'NORMAL_DAY';
	
	// Ngay trong thang(01-28) duoc chon de chot bao phi
	const CONSTANT_OPTION_CLOSING_DATE_FEE = 'CLOSING_DATE_FEE';

	const PUBLISH = 1;

	const NOT_PUBLISH = 0;
	
	// Tinh trang lam viec cua nhan su
	const HR_STATUS_WORKING = 'W';

	const HR_STATUS_LEAVE = 'L';

	/**
	 * END: MODULE ps_constant *
	 */
	
	/**
	 * BEGIN: MODULE ps_member - rank
	 */
	const HR_RANK_RANK_1 = 1;

	const HR_RANK_RANK_2 = 2;

	const HR_RANK_RANK_3 = 3;

	const HR_RANK_RANK_NULL = 0;

	/**
	 * END: MODULE ps_member *
	 */
	
	/**
	 * BEGIN: MODULE ps_off_school - is_activated
	 */
	const OFFSCHOOL_NOT_ACTIVE = 0;

	const OFFSCHOOL_NOT_INVALID = 2;

	const OFFSCHOOL_VALID = 1;

	const GO_SCHOOL = 1; // di hoc
	
	const PERMISSION = 0; // Nghi co phep
	
	const NOT_PERMISSION = 2; // Nghi khong phep
	/**
	 * Khai bao kieu thong bao *
	 */
	const PS_CMS_NOTIFICATIONS_RECEIVED = 'received';

	const PS_CMS_NOTIFICATIONS_SENT = 'sent';

	const PS_CMS_NOTIFICATIONS_TRASH = 'trash';

	/**
	 * END: MODULE ps_off_school - is_activated
	 */
	public $properties;

	public function __construct() {

		$this->properties = parse_ini_file(sfConfig::get('sf_config_dir') . '/properties.ini', true);
	
	}

	/**
	 * Retrieves a config parameter.
	 *
	 * @param string $name
	 *        	A config parameter name
	 * @param mixed $default
	 *        	A default config parameter value
	 * @return mixed A config parameter value, if the config parameter exists, otherwise null
	 */
	public static function getPropertie($name, $var) {

		$properties = parse_ini_file(sfConfig::get('sf_config_dir') . '/properties.ini', true);
		return isset($properties[$name][$var]) ? $properties[$name][$var] : '';
	
	}
	
	// Set current Menu
	/*
	 * public static function currentMenu($strmenu, $style = 'current') { return (self::checkModule(explode(',', $strmenu))) ? 'class="' . $style . '"' : ''; } // Set current Menu public static function checkModule($array_menu) { $module = sfContext::getInstance()->getModuleName(); // Module dang load return in_array($module, $array_menu); }
	 */
	
	// Active current Menu
	public static function askActiveMenu($current_menu) {

		$module = sfContext::getInstance()->getModuleName(); // Module dang load
		
		return (strtolower($current_menu) == strtolower($module));
	
	}
	
	// Active current menu
	public static function askCurrentMenu($current_module, $current_action) {

		$module = sfContext::getInstance()->getModuleName(); // Module dang load
		
		$action = sfContext::getInstance()->getActionName();
		
		return ((strtolower($current_module) == strtolower($module)) && (strtolower($current_action) == strtolower($action)));
	
	}

	/**
	 * Ham tra ve so thoi gian don tre muon
	 */
	public static function getLogtimeTobeLate($value) {

		$hours = 0;
		
		if ($value != null) {
			
			$hours = floor($value / 60);
			$minutes = $value % 60;
			
			if ($minutes >= 45)
				$hours = $hours + 1; // Neu phan du lon hon 45 thi se tinh 1h
			else 
				if ($minutes >= 15)
					$hours = $hours + 0.5; // Neu chi >=15 va < 45 thi se tinh 0.5h
		}
		
		return $hours;
	
	}

	/**
	 * Ham dinh dang so
	 */
	public static function format_number($value = 0, $char = ",", $zero_later = 0) {

		if ($value == 0)
			return 0;
		
		return number_format($value, $zero_later, '.', $char);
	
	}

	/**
	 * Ham dinh dang tien
	 */
	public static function format_price($price = 0, $char = ",") {

		if ($price == 0)
			return 0;
		return number_format($price, 0, '.', $char);
	
		/*
		 * if (is_numeric($price)) return number_format($price, 0, '.', ','); else return number_format($price, 2, '.', ',');
		 */
	}

	public static function convertStrToNumber($price) {

		return str_replace(",", "", $price);
	
	}

	/**
	 * Ham tinh tuoi cua hoc sinh
	 *
	 * @author Nguyen Chien Thang
	 * @param timestamp $date        	
	 * @return string
	 *
	 */
	public static function getAge($date, $show_text = true) {

		$endtime = time() - strtotime($date);
		
		$days = (date("j", $endtime) - 1);
		
		if ($days < 10)
			$days = '0' . $days;
		
		$months = (date("n", $endtime) - 1);
		
		if ($months < 10)
			$months = '0' . $months;
		
		$years = (date("Y", $endtime) - 1970);
		
		if ($years < 10)
			$years = '0' . $years;
		
		if ($show_text) {
			$t = __('age_short');
			$th = __('Month');
			$ng = ' ' . __('Day');
		} else {
			$t = __('t');
			$th = __('th');
			$ng = __('ng');
		}
		
		if ($years != 0) {
			// $ago = ($months == 0) ? $years . $t : $years . $t . ' ' . $months . $th;
			$ago = $years . $t . ' ' . $months . $th;
		} else {
			$ago = ($months == 0 ? $days . $ng : $months . $th);
		}
		
		return $ago;
	
	}

	public static function getMonthYear1($date, $inputdate, $show_text = false) {
		
		// echo $inputdate; die();
		$endtime = strtotime($inputdate) - strtotime($date);
		
		$months = (date("n", $endtime) - 1);
		if ($months < 10)
			$months = '0' . $months;
		
		$years = (date("Y", $endtime) - 1970);
		if ($years < 10)
			$years = '0' . $years;
		if ($show_text) {
			$t = __('age_short');
			$th = __('Month');
			$ng = ' ' . __('Day');
		} else {
			$t = __('t');
			$th = __('th');
			$ng = __('ng');
		}
		
		if ($years != 0) {
			// $ago = ($months == 0) ? $years . $t : $years . $t . ' ' . $months . $th;
			$ago = $years . $t . ' ' . $months . $th;
		} else {
			$ago = ($months == 0 ? $days . $ng : $months . $th);
		}
		return $ago;
	
	}

	/**
	 * * phuc vu cho viec xuat file xls
	 * Lay ra tuoi cua hoc sinh.
	 * Ví du: 4t 03tháng
	 */
	public static function getMonthYear2($date, $inputdate, $show_text = false) {
		
		// echo $inputdate; die();
		$endtime = strtotime($inputdate) - strtotime($date);
		
		$months = (date("n", $endtime) - 1);
		if ($months < 10)
			$months = '0' . $months;
		
		$years = (date("Y", $endtime) - 1970);
		if ($years < 10)
			$years = '0' . $years;
		
		$t = 't';
		$th = 'th';
		$ng = 'ng';
		
		if ($years != 0) {
			// $ago = ($months == 0) ? $years . $t : $years . $t . ' ' . $months . $th;
			$ago = $years . $t . ' ' . $months . $th;
		} else {
			$ago = ($months == 0 ? $days . $ng : $months . $th);
		}
		if ($years != 0) {
			// $ago = ($months == 0) ? $years . $t : $years . $t . ' ' . $months . $th;
			$ago = $years . $t . ' ' . $months . $th;
		} else {
			$ago = ($months == 0 ? $days . $ng : $months . $th);
		}
		return $ago;
	
	}

	/**
	 * Tinh ra thang tuoi cua hoc sinh *
	 */
	public static function getMonthYear($date, $inputdate) {
		
		// echo $inputdate; die();
		$endtime = strtotime($inputdate) - strtotime($date);
		
		$days = (date("j", $endtime) - 1);
		
		$months = (date("n", $endtime) - 1);
		
		$years = (date("Y", $endtime) - 1970);
		
		if ($years != 0) {
			$ago = 12 * $years + $months;
		} else {
			//$ago = ($months == 0 ? $days : $months);
			$ago = $months;
		}
		return $ago;
	
	}



	/**
	 * Tinh ra thang tuoi cua hoc sinh *
	 */
	public static function soThangSauKhaiGiang($date, $inputdate) {
		
		$endtime = strtotime($inputdate) - strtotime($date);
		
		$days = (date("j", $endtime) - 1);
		
		$months = (date("n", $endtime) - 1);
		
		$years = (date("Y", $endtime) - 1970);
		
		if ($years != 0) {
			$ago = 12 * $years + $months;
		} else {
			$ago = $months;
		}
		return $ago;
	
	}
	
	
	public static $gender = array(
			'' => 'Undefined',
			'1' => 'Male',
			'0' => 'Female'
	);

	/**
	 * Ham lay gia tri gioi tinh
	 */
	public static function getGender() {

		return self::$gender;
	
	}

	public static $timesheet = array(
			'' => 'In',
			'0' => 'In',
			'1' => 'Out'
	);

	/**
	 * Ham xet trang thai cham cong
	 */
	public static function getTimesheet() {

		return self::$timesheet;
	
	}

	public static $absent_type = array(
			'0' => 'Not',
			'1' => 'Ok'
	);

	/**
	 * Ham xet ngay nghi hop le hay khong
	 */
	public static function getAbsentType() {

		return self::$absent_type;
	
	}

	public static $height = array(
			'-2' => 'Stunting',
			'-1' => 'Low',
			'0' => 'Normal Height',
			'1' => 'Tall'
	);

	/**
	 * Ham lay gia tri chieu cao tieu chuan
	 */
	public static function getHeightBMI() {

		return self::$height;
	
	}

	public static $weight = array(
			'-2' => 'Malnutrition',
			'-1' => 'Thin',
			'0' => 'Normal Weight',
			'1' => 'Fat'
	);

	/**
	 * Ham lay gia tri can nang tieu chuan
	 */
	public static function getWeightBMI() {

		return self::$weight;
	
	}

	/**
	 * Ham lay gia tri don xin nghi hoc
	 */
	public static $offSchoolStatus = array(
			'0' => 'Inactive',
			'2' => 'Not Valid',
			'1' => 'Valid'
	);

	public static function getOffSchoolStatus() {

		return self::$offSchoolStatus;
	
	}

	public static $status = array(
			'0' => 'Inactive',
			'1' => 'Inactived'
	);

	/**
	 * Ham lay danh sach trang thai hoat dong cua Lop
	 */
	public static function getStatus() {

		return self::$status;
	
	}

	public static $isstudy = array(
			'0' => 'No study',
			'1' => 'Yes study'
	);

	/**
	 * Ham trang thai la hoat dong hoc hay khong
	 */
	public static function getIsStudy() {

		return self::$isstudy;
	
	}

	/**
	 * Ham lay ten trang thai
	 */
	public static function getTextStatus($var) {

		return self::$status[$var];
	
	}

	public static $type = array(
			'1' => 'Pause',
			'0' => 'Stop studying'
	);

	/**
	 * Ham lay danh sach trang thai *
	 */
	public static function getTypeW() {

		return self::$type;
	
	}

	/**
	 * Ham lay ten trang thai *
	 */
	public static function getTextTypeW($var) {

		return self::$type[$var];
	
	}

	/**
	 * Ham tao list so nguyen theo block tuy bien
	 *
	 * @author Nguyen Chien Thang
	 * @param int $start
	 *        	- gia tri bat dau
	 * @param int $end
	 *        	- gia tri ket thuc
	 * @param int $block
	 *        	- buoc nhay
	 * @return Lits
	 *
	 */
	public static function getBlockInt($start = 0, $end = 60, $block = 5) {

		$minutes = array();
		for ($i = $start; $i < $end; $i = $i + $block) {
			$minutes[$i] = sprintf('%02d', $i);
		}
		return $minutes;
	
	}

	/**
	 * Ham kiem tra thang/nam (co tinh toi thoi gian khoi tao cua chuong trinh) va tra ve datime
	 *
	 * @author thangnc - thangnc@ithanoi.com
	 * @param
	 *        	datetime - $date
	 * @return datetime - $date if $date hop le - time hien tai neu $date ko hop le
	 */
	public static function getDate($date) {
		
		// Validator date of paramater
		$years = date('Y', $date);
		$month = date('m', $date);
		
		if ($years < sfConfig::get('app_begin_year') || $years > (date('Y') + 1))
			$years = date('Y');
		
		if ($month < 1 || $month > 12)
			$month = date('m');
		
		return mktime(0, 0, 0, $month, 1, $years);
	
	}

	/**
	 * delete file
	 *
	 * @author thangnc - thangnc@ithanoi.com
	 * @param
	 *        	String - $str_file
	 * @return boolean
	 *
	 */
	public static function deleteFile($str_file) {

		if (PreSchool::isCheckFileExists($str_file)) {
			return unlink($str_file);
		}
		
		return false;
	
	}

	/**
	 * findAllFile($directory)
	 * : Ham lay tat ca cac file trong thu muc chi dinh(ko lay o thu muc con)
	 *
	 * @author thangnc - thangnc@ithanoi.com
	 *        
	 * @return array - chua cac file
	 *        
	 */
	public static function findAllFile($directory) {

		if (substr($directory, - 1) == DIRECTORY_SEPARATOR) {
			$directory = substr($directory, 0, - 1);
		}
		
		if (! file_exists($directory) || ! is_dir($directory)) {
			return null;
		} elseif (! is_readable($directory)) {
			return null;
		} else {
			$directoryHandle = opendir($directory);
			$files = null;
			while ($contents = readdir($directoryHandle)) {
				if ($contents != '.' && $contents != '..') {
					$file = $directory . DIRECTORY_SEPARATOR . $contents;
					if (is_file ( $file ) && ($contents != 'Thumbs.db')) {
						$files [] = $contents;
					}
				}
			}
		}
		closedir ( $directoryHandle );

		return $files;
	}

	// end function

	/**
	 * Kiem tra su ton tai cua file
	 *
	 * @author thangnc - thangnc@ithanoi.com
	 *        
	 *        
	 */
	public static function isCheckFileExists($str_file) {

		if (file_exists ( $str_file ))
			return true;
		else
			return false;
	}

	/**
	 * check floder
	 *
	 * @author thangnc - thangnc@ithanoi.com
	 *        
	 */
	public static function checkDir($strPath) {

		return is_dir ( $strPath );
	}

	/**
	 * Tao thu muc
	 *
	 * @author thangnc - thangnc@ithanoi.com
	 *        
	 */
	public static function makeDir($strPath) {

		mkdir ( $strPath );
		chmod ( $strPath, 0777 );
		return TRUE;
	}

	/**
	 * Tao thu muc de xuat phieu bao hoac phieu thu
	 * Dung cho phieu thu hoac phieu bao: folder cuoi se chua file: yyyymm/student_id/file.xls
	 * Dung cho phieu bao tinh phi hang loat: folder cuoi se chua file : yyyymm/batch/file.xls
	 *
	 * @author thangnc - thangnc@ithanoi.com
	 *        
	 * @param
	 *        	string or int - $folder2, 'batch' hoac ma hoc sinh
	 * @param
	 *        	time - $date, thoi gian
	 * @param
	 *        	boolean - $batch , neu co tinh phi hang loat
	 *        	
	 */
	public static function makeFolderReport($folder2, $date, $root = './') {

		$path1 = $root . date ( 'Ym', $date ) . DIRECTORY_SEPARATOR . $folder2;
		$path2 = $root . date ( 'Ym', $date );

		if (! PreSchool::checkDir ( $root )) {
			PreSchool::makeDir ( $root );
		}

		if (PreSchool::checkDir ( $path1 )) {
			return true;
		} elseif (PreSchool::checkDir ( $path2 )) {
			if (PreSchool::makeDir ( $path1 )) {
				return true;
			} else {
				return false;
			}
		} else {
			if (PreSchool::makeDir ( $path2 )) {
				if (PreSchool::makeDir ( $path1 ))
					return true;
			} else {
				return false;
			}
		}
	}

	/**
	 * get_filesize($dir, $file)
	 *
	 * @author @author thangnc - thangnc@ithanoi.com
	 * @param
	 *        	String - $path_file, path and file
	 * @param
	 *        	String - $file, file name
	 * @return int - size for file (bytes)
	 *        
	 */
	public static function getFileSize($path_file) {

		return filesize ( $path_file );
	}

	/**
	 * readFileData($path_file)
	 *
	 * @author thangnc - thangnc@ithanoi.com
	 *        
	 * @param
	 *        	String - $path_file, path and file
	 * @return int
	 *
	 */
	public static function readFileData($path_file) {

		readfile ( $path_file );
		return;
	}

	/**
	 * isDownloadFile($dir, $file, $msg_erros)
	 *
	 * @author thangnc - thangnc@ithanoi.com
	 *        
	 * @param
	 *        	String - $dir, path of file
	 * @param
	 *        	String - $file, fiel name
	 * @param
	 *        	String - $msg_erros
	 *        	
	 */
	public static function isDownloadFile($dir, $file, $msg_erros = 'Error') {

		if (is_file ( $dir . $file ) && PreSchool::isCheckFileExists ( $dir . $file )) {

			$str = str_replace ( ".", "", strrchr ( $file, "." ) );

			$content_type = PreSchool::getContentType ( $str );

			if ((isset ( $file )) && (PreSchool::isCheckFileExists ( $dir . $file ))) {
				header ( "Content-type: application/force-download" );
				header ( 'Content-Disposition: inline; filename="' . $dir . $file . '"' );
				header ( "Content-Transfer-Encoding: Binary" );
				header ( "Content-length: " . PreSchool::getFileSize ( $dir . $file ) );
				header ( 'Content-Type: ' . $content_type );
				header ( 'Content-Disposition: attachment; filename="' . $file . '"' );
				PreSchool::readFileData ( $dir . $file );
			} else {
				echo $msg_erros;
			} // end if
		} else {
			echo $msg_erros;
		}
		return;
	}

	/**
	 * *********************************************************************
	 * Function: getContentType($ext ,$song=false)
	 * Description: returns content type for the file type
	 * Arguments: $ext as extension file string
	 * $funcPath as name of function get path
	 * Returns: String
	 * **********************************************************************
	 */
	public static function getContentType($ext, $song = false) {

		switch (strtolower ( $ext )) {
			case "gif" :
				return "image/gif";
				break;
			case "png" :
				return "image/png";
				break;
			case "pnz" :
				return "image/png";
				break;
			case "jpg" :
				return "image/jpeg";
				break;
			case "jpz" :
				return "image/jpeg";
				break;
			case "jpeg" :
				return "image/jpeg";
				break;
			case "mld" :
				return "application/x-mld";
			case "mid" :
				return "audio/mid";
				break;
			case "mmf" :
				return "application/x-smaf";
			case "pmd" :
				return "application/x-pmd";
			case "asf" :
				return "video/x-ms-asf";
				break;
			case "flv" :
				return "video/x-flv";
				break;
			case "amc" :
				return "application/x-mpeg";
				break;
			case "3gp" :

				if ($song == true) {
					return "audio/3gpp";
					break;
				} else {
					return "video/3gpp";
					break;
				}
			case "3g2" :
				return "audio/3gpp2";
				break;
			case "mp4" :
				return "video/mp4";
				break;
			case "swf" :
				return "application/x-shockwave-flash";
				break;
			case "dmt" :
				return "application/octet-stream";
				break;
			case "txt" :
				return "application/x-tex";
				break;
			default :
				return "application/octet-stream";
		}
	}

	/**
	 * Ham show button or link of action
	 *
	 * @param
	 *        	String - name key of action
	 *        	
	 */
	public static function renderButton($name = 'Back to list', $icon = '/images2/40/icon-veds.png', $link = '', $style = '') {

		$html = '<li class="button_top"><a href="' . ($link ? url_for ( $link ) : '#') . '"><img src="' . $icon . '" alt="' . $name . '" /><br />' . $name . '</a></li>';
		/*
		 * $html = '<li class="button_top">';
		 * $html .= link_to('<img src="'.$icon.'" alt="'.$name.'" /><br />'.$name.'</a>', $link);
		 * $html.= '</li>';
		 */
		return $html;
	}

	/**
	 * Ham show button or link of action
	 *
	 * @param
	 *        	String - name key of action
	 *        	
	 */
	public static function btnLink($name = 'Back to list', $icon = "list", $link = '') {

		$html = '<li class="button_top"><a class="icon_' . $icon . '" href="' . ($link ? url_for ( $link ) : '#') . '">' . $name . '</a></li>';
		return $html;
	}

	/**
	 * Render ma
	 */
	public static function renderCode($format = "%08s", $_fixx = null) {

		return sprintf ( $format, $_fixx );
	}

	public static $state = array (
			self::ACTIVE => 'Activity',
			self::NOT_ACTIVE => 'Inactive' );

	/**
	 * Ham lay danh sach trang thai *
	 */
	public static function loadPsActivity() {

		return self::$state;
	}

	// Trang thai hoat dong cua ung dung
	public static $ps_app_active = array (
			'0' => 'Not active', // Chua hoat dong
			'1' => 'Activated', // Hoat dong
			'2' => 'Deactivated' );

	// Tam dung hoạt động

	/**
	 * Lay danh sach trang thai cua ung dung
	 *
	 * @return $list an option HTML
	 */
	public static function loadPsAppActivated() {

		return self::$ps_app_active;
	}

	// Lay danh sach thang
	public static $ps_month = array (
			1 => 'January',
			2 => 'February',
			3 => 'March',
			4 => 'April',
			5 => 'May',
			6 => 'June',
			7 => 'July',
			8 => 'August',
			9 => 'September',
			10 => 'October',
			11 => 'November',
			12 => 'December' );

	public static function loadPsMonth() {

		return self::$ps_month;
	}

	// Trang thai hoat dong cua school_code
	public static $ps_customer_active = array (
			self::CUSTOMER_NOT_ACTIVATED => 'Not active',
			self::CUSTOMER_ACTIVATED => 'Activated',
			self::CUSTOMER_LOCK => 'Lock' );

	/**
	 * Lay danh sach trang thai cua school_code
	 *
	 * @return $list an option HTML
	 */
	public static function loadPsCustomerActivated() {

		return self::$ps_customer_active;
	}

	/**
	 * Lay danh sach trang thai trien khai cua school_code
	 *
	 * @return $list an option HTML
	 */
	public static function loadPsCustomerDeploy() {

		return self::$ps_customer_deploy;
	}

	// Trang thai trien khai cua customer
	public static $ps_customer_deploy = array (
			self::CUSTOMER_NOT_DEPLOY => 'Not deploy',
			self::CUSTOMER_DEPLOYING => 'Deploying' );

	// Trang thai hoat dong cua user
	public static $ps_user_active = array (
			self::CUSTOMER_NOT_ACTIVATED => 'Not active',
			self::CUSTOMER_ACTIVATED => 'Activated',
			self::CUSTOMER_LOCK => 'Lock' );

	/**
	 * Lay danh sach trang thai cua user
	 *
	 * @return $list an option HTML
	 */
	public static function loadPsUserActivated() {

		return self::$ps_user_active;
	}

	// Level of User system
	public static $ps_level_user = array (
			'1' => 'Is super admin',
			'1' => 'Is global super admin' );

	/**
	 * Lay danh sach Level cua User
	 *
	 * @return $list an option HTML
	 */
	public static function loadPsLevelUser() {

		return self::$ps_level_user;
	}

	public static $ps_gender = array (
			'' => 'Undefined',
			'1' => 'Male',
			'0' => 'Female' );

	/**
	 * Ham lay gia tri gioi tinh *
	 */
	public static function loadPsGender() {

		return self::$ps_gender;
	}

	public static $ps_boolean = array (
			'1' => 'yes',
			'0' => 'no' );

	/**
	 * Ham lay gia tri boolean
	 */
	public static function loadPsBoolean() {

		return self::$ps_boolean;
	}

	public static $ps_symgroup = array (
			'1' => 'Dịch vụ',
			'0' => 'Điểm danh đi học' );

	/**
	 * Ham lay gia tri boolean
	 */
	public static function loadPsSymGroup() {

		return self::$ps_symgroup;
	}

	public static $ps_giamtru = array (
			'0' => 'Giảm trừ %',
			'1' => 'Giảm trừ tiền mặt',
			'2' => 'Thu trực tiếp' );

	/**
	 * Ham lay gia tri boolean
	 */
	public static function loadPsGiamtru() {

		return self::$ps_giamtru;
	}
	
	public static $ps_choise_relative = array (
			'1' => 'View relative',
			'0' => 'Not view relative' );
	
	/**
	 * Ham lay gia tri boolean
	 */
	public static function loadPsChoiseRelative() {
		return self::$ps_choise_relative;
	}
	
	public static $ps_roll = array (
			// self::SERVICE_TYPE_SCHEDULE => 'Fixed schedule note',
			self::SERVICE_TYPE_FIXED => 'Fixed',
			self::SERVICE_TYPE_NOT_FIXED => 'Not fixed' );

	/**
	 * Ham lay loai hinh dich vu: Co dinh or Ko co dinh or theo TKB (co lich hoc)*
	 */
	public static function loadPsRoll() {

		return self::$ps_roll;
	}

	public static $ps_service_default = array (
			'1' => 'yes',
			'0' => 'no' );

	/**
	 * Ham lay cach thuc dang ky dich vu: 1- Nha truong se dang ky; 0- Phu huynh co the lua chon *
	 */
	public static function loadPsServiceDefault() {

		return self::$ps_service_default;
	}

	/**
	 * Cách chia phí nếu dịch vụ là Không cố định*
	 */
	public static $ps_is_type_fee = array (
			'0' => 'Calculated according to the number of uses', // Quy đổi giá theo số lần sử dụng
			'1' => 'Calculated according to the number of unused times' // Quy đổi giá theo số lần không sử dụng
	);

	/**
	 * Hàm lấy cách thức tính phí dịch vụ trong truong hop phí là loại không cố định
	 */
	public static function loadPsIsTypeFee() {

		return self::$ps_is_type_fee;
	}


	/**
	 * Định nghĩa kiểu dịch vụ*
	 */
	public static $service_type = array (
		'0' => 'Dịch vụ thu bình thường',
		'1' => 'Dịch vụ có chiết khấu trong năm học cuối',
	);

	/**
	 * Hàm lấy cách thức tính phí dịch vụ trong truong hop phí là loại không cố định
	 */
	public static function loadServiceType() {

		return self::$service_type;
	}



	public static $ps_branch_mode = array (
			'1' => 'Single',
			'2' => 'Multi' );

	/**
	 * Ham lay gia tri branch_mode *
	 */
	public static function loadPsBranchMode() {

		return self::$ps_branch_mode;
	}

	public static $ps_featureOptionFeature = array (
			'1' => 'Check in',
			'2' => 'Input text' // '3' => 'Time'
	);

	/**
	 * Ham lay gia tri FeatureOptionFeature *
	 */
	public static function loadPsFeatureOptionFeature() {

		return self::$ps_featureOptionFeature;
	}

	public static $ps_user_type  = array (
		self::USER_TYPE_RELATIVE => 'User relative',
		self::USER_TYPE_TEACHER  => 'User member',
		self::USER_TYPE_MANAGER  => 'User department'
	);

	/**
	 * Ham lay gia tri FeatureOptionFeature *
	 */
	public static function loadPsUserType() {

		return self::$ps_user_type;
	}
	
	const MANAGER_TYPE_PROVINCIAL = 'P';
	const MANAGER_TYPE_DISTRICT   = 'D';
	const MANAGER_TYPE_GLOBAL     = 'G';// quan ly chung
		
	public static $ps_manager_type  = array (
		self::MANAGER_TYPE_GLOBAL  		=> 'Global manager',
		self::MANAGER_TYPE_PROVINCIAL   => 'Provincial manager',
		self::MANAGER_TYPE_DISTRICT     => 'District manager'			
	);
	
	/**
	 * Ham lay danh sach loai User quan ly cua So/ Phong giao duc
	 */
	public static function loadPsManagerType() {
		return self::$ps_manager_type;
	}

	public static $ps_file_group = array (
			self::FILE_GROUP_SERVICE => 'Icon service',
			self::FILE_GROUP_FEATURE => 'Icon feature',
			self::FILE_GROUP_FOODS => 'Icon food' );

	/**
	 * Ham load danh sach file group *
	 */
	public static function loadPsFileGroup() {
		return self::$ps_file_group;
	}

	public static $ps_primary_teacher = array (
			self::ACTIVE => 'yes',
			self::NOT_ACTIVE => 'no' );

	/**
	 * Ham load primary_teacher *
	 */
	public static function loadPsPrimaryTeacher() {
		return self::$ps_primary_teacher;
	}

	/**
	 * Ham load trang thai hoc sinh trong tuong hoc *
	 */
	public static $status_student = array(
			/*'0' => 'Temporary',*/
			self::S_STATUS_ACTIVITIES => 'Activities',
			self::S_STATUS_SCHOOL_TEST => 'School test',
			self::S_STATUS_PAUSE => 'Pause',
			self::S_STATUS_STOP_STUDYING => 'Stop studying',
			self::S_STATUS_GRADUATION => 'Graduation' );

	public static function loadStatusStudent() {
		return self::$status_student;
	}

	public static $content_code = array (
			'terms_of_use' => 'Terms of use' );

	/**
	 * Ham lay danh sach ma *
	 */
	public static function loadPsSystemCmsContentCode() {

		return self::$content_code;
	}

	/**
	 * //self::SC_STATUS_FINISHED => 'Change class', // Da chuyen lop
	 * 
	 * Ham load trang thai hoc sinh trong lop hoc *
	 *
	 * // dang hoc
	 * const SC_STATUS_OFFICIAL = 'DH'; // Chinh thuc
	 *
	 * // Tam dung,
	 * const SC_STATUS_PAUSE = 'TD';
	 *
	 * // Tot nghiep
	 * const SC_STATUS_GRADUATION = 'TN';
	 *
	 * // Thoi hoc
	 * const SC_STATUS_STOP_STUDYING = 'TH';
	 */
	//------------------//
	
	// Mới: Tháng đầu tiên sau nhập học
	const SC_STATUS_NEW = 'T1';

	//Mới 1: Tháng thứ 2 sau nhập học
	const SC_STATUS_NEW1 = 'T2';

	// Đang học
	const SC_STATUS_STUDYING = 'DH';

	// Năm cuối
	const NAM_CUOI = '1';

	// Năm đầu
	const NAM_DAU = '2';

	// Năm cuối
	const SC_STATUS_LASTYEAR = 'NC';

	// Hoc thu
	const SC_STATUS_TEST = 'HT';

	// Giữ chỗ
	const SC_STATUS_HOLD = 'GC';

	// Thoi hoc
	const SC_STATUS_STOP_STUDYING = 'TH';

	// Tam dung / tam nghi
	const SC_STATUS_PAUSE = 'TD';

	// Nghi han
	const SC_STATUS_LEAVE = 'NH';

	// Tot nghiep
	const SC_STATUS_GRADUATION = 'TN';
	
	// Chinh thuc
	const SC_STATUS_OFFICIAL = 'CT';
	//-------------------//

	public static $status_student_class = array (
			// self::SC_STATUS_TEST 	 		=> 'School test', // Hoc thu
			// self::SC_STATUS_OFFICIAL 		=> 'Official', // Chinh thuc SC_STATUS_OFFICIAL
			// self::SC_STATUS_PAUSE 	 		=> 'Pause', // Tam dung
			// self::SC_STATUS_HOLD 			=> 'Hold place', // giữ chỗ
			// self::SC_STATUS_GRADUATION 		=> 'Graduation', // Tot nghiep - ra truong
			// self::SC_STATUS_STOP_STUDYING   => 'Stop studying' // Thoi hoc	

			self::SC_STATUS_NEW 			=> 'Mới', //Tháng đầu tiên sau nhập học
			self::SC_STATUS_NEW1 			=> 'Mới 1', //Tháng thứ 2 sau nhập học
			self::SC_STATUS_OFFICIAL 		=> 'Đang học', //Đang học
			self::SC_STATUS_LASTYEAR 		=> 'Năm cuối',
			self::SC_STATUS_TEST 			=> 'Học thử',
			self::SC_STATUS_HOLD 			=> 'Giữ chỗ',
			self::SC_STATUS_STOP_STUDYING 	=> 'Thôi học',
			self::SC_STATUS_PAUSE 			=> 'Tạm nghỉ',
			self::SC_STATUS_LEAVE 			=> 'Nghỉ hẳn',
			self::SC_STATUS_GRADUATION		=> 'Tốt nghiệp'

	);

	public static function loadStatusStudentClass() {

		return self::$status_student_class;
	}

	public static $check_status_student = array (
		self::SC_STATUS_NEW 			=> 'Mới', //Tháng đầu tiên sau nhập học
		self::SC_STATUS_NEW1 			=> 'Mới 1', //Tháng thứ 2 sau nhập học
		self::SC_STATUS_OFFICIAL 		=> 'Đang học', //Đang học
		self::SC_STATUS_LASTYEAR 		=> 'Năm cuối',
		self::SC_STATUS_TEST 			=> 'Học thử',
	);

	public static function loadCheckStatusStudent() {

		return self::$check_status_student;
	}

	public static $status_student_not_class = array (

			self::SC_STATUS_TEST 	 		=> 'School test', // Hoc thu
			self::SC_STATUS_OFFICIAL 		=> 'Official', // Chinh thuc SC_STATUS_OFFICIAL
			self::SC_STATUS_PAUSE 	 		=> 'Pause', // Tam dung
			self::SC_STATUS_HOLD 			=> 'Hold place', // giữ chỗ
			self::SC_STATUS_GRADUATION 		=> 'Graduation', // Tot nghiep - ra truong
			self::SC_STATUS_STOP_STUDYING   => 'Stop studying', // Thoi hoc
			self::NOT_IN_CLASS => 'Not in class' // Chưa phân lớp
	);

	public static function loadStatusStudentNotClass() {

		return self::$status_student_not_class;
	}

	public static $not_in_class_active = array (
			self::NOT_IN_CLASS => '-Not in class-', // Chưa phân lớp
			self::CLASS_LOCKED   => '-Class locked-' // Lop hoc da khoa
	);
	
	public static function loadClassNotActive() {
		
		return self::$not_in_class_active;
	}
	
	/**
	 * Trang thai tin tuc *
	 */
	public static $ps_is_publish = array (
			// self::ARTICLE => 'Icon service', Phan hoi ko duyet bai
			self::PUBLISH => 'Publish',
			self::NOT_PUBLISH => 'Not publish' );

	/**
	 * Ham load trang thai duyet hien thi tin bai *
	 */
	public static function loadCmsArticles() {

		return self::$ps_is_publish;
	}

	/**
	 * Trang thai tin tuc khoa *
	 */
	public static $ps_is_lock = array (
			// self::ARTICLE => 'Icon service', Phan hoi ko duyet bai
			self::PUBLISH => 'Publish',
			self::NOT_PUBLISH => 'Not publish',
			self::LOCK => 'Lock' );

	/**
	 * Ham load trang thai duyet hien thi tin bai *
	 */
	public static function loadCmsArticlesLock() {

		return self::$ps_is_lock;
	}

	/**
	 * Duyet nhan xet thang, tuan Browse articles *
	 */
	public static $ps_browse_articles = array (
			// self::ARTICLE => 'Icon service', Phan hoi ko duyet bai
			self::ACTIVE => 'Browse',
			self::NOT_ACTIVE => 'Not browse' );

	/**
	 * Ham load trang thai duyet nhan xet *
	 */
	public static function loadBrowseArticles() {

		return self::$ps_browse_articles;
	}

	/**
	 * Vung hien thi tin tuc *
	 */
	public static $ps_is_access = array (
			self::ACTIVE => 'Public',
			self::NOT_ACTIVE => 'Private' );

	/**
	 * Ham load trang thai duyet hien thi tin bai *
	 */
	public static function loadCmsArticleAccess() {

		return self::$ps_is_access;
	}

	/**
	 * Cap do cua HR *
	 */
	public static $ps_hr_rank = array (
			// self::HR_RANK_RANK_NULL => 'Not any rank',
			self::HR_RANK_RANK_1 => 'Rank 1',
			self::HR_RANK_RANK_2 => 'Rank 2',
			self::HR_RANK_RANK_3 => 'Rank 3' );

	/**
	 * Ham load trang thai duyet hien thi tin bai *
	 */
	public static function loadHrRank() {

		return self::$ps_hr_rank;
	}

	/**
	 * Trang thai lam viẹc cua HR *
	 */
	public static $ps_hr_is_status = array (
			self::HR_STATUS_WORKING => 'Working',
			self::HR_STATUS_LEAVE => 'Leave' );

	/**
	 * Ham load trang thai duyet hien thi tin bai *
	 */
	public static function loadHrStatus() {

		return self::$ps_hr_is_status;
	}

	/**
	 * Mau export phi *
	 */
	public static $ps_template_receipt = array (
			self::PS_TEMPLATE_RECEIPT_01 => 'Template 01',
			self::PS_TEMPLATE_RECEIPT_02 => 'Template 02',
			self::PS_TEMPLATE_RECEIPT_03 => 'Template 03',
			self::PS_TEMPLATE_RECEIPT_04 => 'Template 04'
	);

	/**
	 * Ham load mau phieu thu *
	 */
	public static function loadPsTemplateReceipts() {

		return self::$ps_template_receipt;
	}

	/**
	 * Ham tao danh sach gia tri bao nghi cho ngay thu bay *
	 */
	public static function loadPsTimeCancelSaturday() {

		$ranger = range ( 8, 48, 2 );

		$arr_value = array ();

		foreach ( $ranger as $value ) {
			$arr_value [$value] = $value . ' h';
		}

		return $arr_value;
	}

	//kiểu áp dụng giảm trừ
	public static $ps_reduce_status = array (
		'0' => 'Độ tuổi',
		'1' => 'Nhập học giữa tháng',
		'2' => 'Học sau khai giảng',
		'6' => 'Năm đầu',
		'3' => 'Năm cuối',
		'4' => 'Số buổi đi học',
		'5' => 'Số buổi nghỉ học',
	);

	public static function loadPsReduceStatus() {

		return self::$ps_reduce_status;
	}

	// Trang thai thanh toan
	public static $ps_payment_status = array (
			self::NOT_ACTIVE => 'Unpaid',
			self::ACTIVE => 'Paid' );

	/**
	 * Ham lay Trang thai thanh toan
	 */
	public static function loadPsPaymentStatus() {

		return self::$ps_payment_status;
	}

	public static $ps_type_fee_paylate = array (
			0 => 'No progression',
			1 => 'Multiplication progression' );

	/**
	 * Ham lay gia tri boolean
	 */
	public static function loadPsTypeFeePayLate() {

		return self::$ps_type_fee_paylate;
	}

	public static $ps_type_fee_late = array (
			0 => 'By total hour',
			1 => 'By day' );

	/**
	 * Ham lay gia tri boolean
	 */
	public static function loadPsTypeFeeLate() {

		return self::$ps_type_fee_late;
	}
	
	public static $ps_type_menus = array (
	    0 => 'By system menus one',
	    1 => 'By system menus two' );
	
	/**
	 * Ham lay gia tri boolean
	 */
	public static function loadPsTypeMenus() {
	    
	    return self::$ps_type_menus;
	}
	
	/**
	 * Ham quy doi gio ve muon tren phieu
	 */
	public static $ps_type_minute_hour = array (
			0 => 'Type minute',
			1 => 'Type hour' );

	/**
	 * Ham lay gia tri boolean
	 */
	public static function loadPsTypeMinuteHour() {

		return self::$ps_type_minute_hour;
	}
	
	const  M_VIEW_FEE_DETAIL = 0;
	const  M_VIEW_FEE_TOTAL = 1;
	const  M_VIEW_FEE_CATEGORY   = 2;
	
	public static $ps_config_view_fee_mobile = array (
			self::M_VIEW_FEE_DETAIL => 'View detail',
			self::M_VIEW_FEE_TOTAL => 'View total',
			self::M_VIEW_FEE_CATEGORY => 'View category');

	/**
	 * Ham cau hinhn hien thi phi tren app phu huynh
	*/
	public static function loadPsViewFeeMobile() {

		return self::$ps_config_view_fee_mobile;
	}

	// Trang thai thanh toan
	public static $ps_is_public = array (
			self::ACTIVE => 'Publish',
			self::NOT_ACTIVE => 'Not publish' );

	/**
	 * Ham load Trang thai hien thi phieu thu
	 */
	public static function loadPsIsPublic() {

		return self::$ps_is_public;
	}

	/**
	 * Hinh thuc thanh toan *
	 */
	public static $payment_type = array (
			'TM' => 'Cash',
			'CK' => 'Transfer',
			'QT' => 'Swipe' );

	/**
	 * Ham lay gia tri boolean
	 */
	public static function loadPsPaymentType() {

		return self::$payment_type;
	}

	public static $app_mobile_actived = array (
			'0' => 'Not active app',
			'1' => 'Actived app' );

	/**
	 * Hàm lấy danh sach trạng thái kích hoạt của app *
	 */
	public static function getAppMobileActived() {

		return self::$app_mobile_actived;
	}
	
	const  M_FEE_NOTIFICATION = 0;
	const  M_FEE 			  = 1;
	const  M_FEE_NEWSLETTER   = 2;
	
	// Trang thai thanh toan
	public static $is_source_receipt = array (
			self::M_FEE_NOTIFICATION 	=> 'Management fee notification',
			self::M_FEE 				=> 'Management fee',
			self::M_FEE_NEWSLETTER 		=> 'Management newsletter fee');

	/**
	 * Ham load lua chon nguon hien thi phi ra APP
	*/
	public static function loadIsSourceReceipt() {
		return self::$is_source_receipt;
	}

	/**
	 * Kiểu dịch vụ *
	 */
	public static $loaiDichVu = array (
		'0' => 'Bình thường',
		'3' => 'Tháng tuổi',
		'1' => 'Tháng nhập học (Cứ đến tháng nhập học là thu)',
		'4' => 'Đầu năm học (Cứ đến đầu năm học là thu, và thu rải rác cả năm)',
		'5' => 'Tự hủy sau khi sử dụng (VD: DV đồng phục)',
		'2' => 'Tháng ấn định'
	);
	public static function loadLoaiDichVu() {

		return self::$loaiDichVu;
	}

	/**
	 * Khai báo cấp *
	 */
	public static $capHoc = array (
		'' => '-Chọn cấp-',
		'1' => 'Mầm non',
		'2' => 'Tiểu học',
		'3' => 'Trung học cơ sở',
		'4' => 'Trung học phổ thông'
	);
	public static function loadCapHoc() {
		return self::$capHoc;
	}

	/**
	 * Khai chương trình đào tạo *
	 */

	public static $chuongTrinhDaoTao = array (
		'' => '-Chọn CT đào tạo-',
		'1' => 'Truyền thống',
		'2' => 'Quốc tế'
	);
	public static function loadChuongTrinhDaoTao() {
		return self::$chuongTrinhDaoTao;
	}

	/**  **/
	public static $ps_type_receipt = array (
		0 => 'Số chứng từ theo cơ sở, không theo năm, tháng',
		1 => 'Số chứng từ theo cơ sở, theo năm',
		2 => 'Số chứng từ theo cơ sở, theo tháng',
		3 => 'Số chứng từ theo trường, không theo năm, tháng',
		4 => 'Số chứng từ theo trường, theo tháng ',
		5 => 'Số chứng từ theo trường, theo tháng ',
	 );

	public static function loadPsTypeReceipt() {
		return self::$ps_type_receipt;
	}

}