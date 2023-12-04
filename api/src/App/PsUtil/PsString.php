<?php
namespace App\PsUtil;

// use PsEncryptDecrypt;

/**
 */
class PsString {

	public static $cipher_mcrypt = MCRYPT_RC2;

	public static $mode_mcrypt = 'ecb';

	public static $ps_roll = array (
			'2' => 'Fixed schedule note',
			'1' => 'Fixed',
			'0' => 'Not fixed' 
	);

	/**
	 * Ham lay loai hinh dich vu: Co dinh or Ko co dinh or theo TKB (co lich hoc)*
	 */
	public static function loadPsRoll() {

		return self::$ps_roll;
	}

	public static function length($text) {

		return mb_strlen ( $text );
	}

	/**
	 * Render ma
	 */
	public static function renderCode($format = "%08s", $_fixx = null) {

		return sprintf ( $format, $_fixx );
	}

	/*
	 * Create a random string
	 * @author
	 * @param $length the length of the string to create
	 * @return $str the string
	 */
	public static function randomString($length = 10) {

		$str = "";
		
		$characters = array_merge ( range ( 'A', 'Z' ), range ( 'a', 'z' ), range ( '0', '9' ) );
		
		$max = count ( $characters ) - 1;
		
		for($i = 0; $i < $length; $i ++) {
			$rand = mt_rand ( 0, $max );
			$str .= $characters [$rand];
		}
		
		return $str;
	}

	/*
	 * TRIM a string
	 * @author thangnc@newwaytech.vn
	 *
	 * @param $str - string
	 * @return string
	 */
	public static function trimString($str) {

		return trim ( $str );
	}
	
	// Ham thay the
	public static function strReplace($text, $arr_char = array('.',',',';')) {

		return str_replace ( $arr_char, "", $text );
	}

	/*
	 * Convert Upper a string
	 * @author thangnc@newwaytech.vn
	 *
	 * @param $str - string
	 * @return string
	 */
	public static function strToUpperString($str) {

		return mb_strtoupper ( $str );
	}

	/*
	 * encryptString($str) - Ham ma hoa xau du lieu
	 * @author thangnc@newwaytech.vn
	 *
	 * @param $str - string
	 * @return string - da ma hoa
	 */
	public static function encryptString($str, $private_key) {

		return mcrypt_encrypt ( self::$cipher_mcrypt, $private_key, $str, self::$mode_mcrypt );
	}

	/*
	 * decryptString($str) - Ham ma hoa xau du lieu
	 * @author thangnc@newwaytech.vn
	 *
	 * @param $str - string
	 * @return string - giai ma
	 */
	public static function decryptString($str, $private_key) {

		return mcrypt_decrypt ( self::$cipher_mcrypt, $private_key, $str, self::$mode_mcrypt );
	}

	/*
	 * haskString($str) - Ham ma hoa text url
	 * @author thangnc@newwaytech.vn
	 *
	 * @param $str - string
	 * @return string - url ma hoa
	 */
	public static function encryptUrl($str, $private_key) {

		return mcrypt_decrypt ( self::$cipher_mcrypt, $private_key, $str, self::$mode_mcrypt );
	}

	/**
	 * Ham tra ve url lay du lieu file anh
	 *
	 * @param $type_img -
	 *        	string , loai file ảnh: hr- Nhan su, relative - phu huynh, student - hoc sinh
	 * @param $file_img -
	 *        	string, file anh
	 * @param $school_code -
	 *        	string, ma truong
	 * @param $public_key -
	 *        	string, khoa cong khai( su dung de ma hoa va giai ma)
	 */
	public static function generateUrlImage($type_img, $file_img, $school_id, $public_key='') {
		
		/*
		if ($file_img != '') {
			$url_img = PS_CONST_API_URL_IMAGE . '/' . PsEndCode::ps64EndCode ( $file_img ) . '/' . $type_img . '/' . rawurlencode ( PsEncryptDecrypt::encryptData ( $school_id, $public_key ) );
		} else {
			$url_img = PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
		}
		*/
		
		$school_code = 'PSM'.PsString::renderCode("%05s", $school_id);
		
		if ($file_img != '') {
			$url_img = PS_CONST_URL_AVATAR_IMAGE . $school_code . '/' . $type_img . '/' . $file_img;
		} else {
			$url_img = PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
		}
		
		return $url_img;
	}
	
	public static function xemAnhDaiDien($type_img, $file_img, $school_id) {
		
		$school_code = 'PSM'.PsString::renderCode("%05s", $school_id);
		
		if ($file_img != '') {
			$url_img = PS_CONST_URL_AVATAR_IMAGE . $school_code . '/' . $type_img . '/' . $file_img;
		} else {
			$url_img = PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR;
		}
		
		return $url_img;
	}
	
	
	/**
	 * Ham tra ve url lay icon cua menu he thong
	 *
	 * @return string
	*/
	public static function getUrlIconMenuApp() {
		return PS_CONST_URL_SERVER . '/app/';
	}

	/**
	 * Ham tra ve url lay icon cua dich vu, hoat dong...
	 *
	 * @param $file_img -
	 *        	string, file anh
	 */
	public static function getUrlPsImage($file_img) {

		//$url_img = PS_CONST_URL_SERVER . '/icon/' . $file_img;
		$url_img = PS_CONST_URL_WEB_SERVER . '/sys_icon/' . $file_img;
		
		return $url_img;
	}
	
	
	
	/**
	 * Ham tra ve url lay icon cua dich vu, hoat dong...
	 *
	 * @param string $file_img
	 * 
	 *  @return string URL file anh
	 */
	public static function getUrlFoodImage($file_img) {
		
		$url_img = PS_CONST_URL_MEDIA . '/foods/' . $file_img;
		
		return $url_img;
	}
	

	/**
	 * Ham tra ve url lay Avatar cua Giao vien, hoc sinh, Logo - Dung trong Notification
	 *
	 * @param $ps_school_code -
	 *        	string, Ma truong
	 * @param $type -
	 *        	string, kieu du lieu: 1- Nhan su; 2- Nguoi than; 3- Hoc sinh; 4-Logo customer
	 * @param $file_img -
	 *        	string, file anh
	 */
	public static function getUrlPsAvatar($ps_school_code, $type, $file_img) {

		$url_img = PS_CONST_API_URL_AVATAR . '?scode=' . PsEndCode::ps64EndCode ( $ps_school_code ) . '&_t=' . PsEndCode::ps64EndCode ( $type ) . '&_f=' . PsEndCode::ps64EndCode ( $file_img );
		
		return $url_img;
	}
	
	/**
	 * Ham tra ve url alias lay Logo
	 *
	 * @param $year_folder - int, folder chua du lieu
	 * @param $file_logo - string, ten file anh
	 */
	public static function getUrlLogoPsCustomer($year_folder,$file_logo) {
		return PS_CONST_URL_MEDIA.'/logo/'.$year_folder.'/'.$file_logo;		
	}
	
	/**
	 * Ham tra ve url alias lay image camera
	 *
	 * @param $school_code - ma truong
	 * @param $year_folder - int, folder chua du lieu
	 * @param $file_logo - string, ten file anh
	 */
	public static function getUrlMediaCamera($school_code, $year_folder,$file_logo) {
		return PS_CONST_URL_MEDIA.'/camera/'.$school_code.'/'.$year_folder.'/'.$file_logo;		
	}
	
	/**
	 * Ham tra ve url media avatar cua Giao vien, hoc sinh, nguoi than
	 *
	 * @param $cache_data - string, folder media
	 * @param $year_data - int, nam chua du lieu
	 * @param $type 	- string, kieu du lieu: 01- Nhan su; 02- Nguoi than; 03- Hoc sinh
	 * @param $file_name - string, file anh
	 */
	public static function getUrlMediaAvatar($cache_data,$year_data,$file_name, $type) {
		return PS_CONST_URL_MEDIA . '/root/'. $type .'/'.$cache_data.'/'.$year_data.'/'.$file_name;
		//return PS_CONST_URL_MEDIA . '/image/'. $type .'/'.$cache_data.'/'.$file_name;
	}
	
	/** Ham tra ve url ảnh thumb tin tức **/
	public static function getUrlMediaThumbArticle($ps_customer_id,$path_date,$file_name) {
		return PS_CONST_URL_MEDIA_ARTICLE . '/article/thumb/'.$path_date.'/'.$file_name;
		//return PS_CONST_URL_MEDIA_ARTICLE . '/'.$path_date.'/thumb/'.$file_name;
	}
	
	/** Ham tra ve url ảnh tin tức **/
	public static function getUrlMediaArticle($ps_customer_id,$path_date,$file_name) {
		return PS_CONST_URL_MEDIA_ARTICLE . '/article/'.$path_date.'/'.$file_name;
		//return PS_CONST_URL_MEDIA_ARTICLE . '/'.$path_date.'/'.$file_name;
	}

	/***********************************************************************
    Function:		getContentType($ext ,$song=false) 
    Description:    returns content type for the file type 
    Arguments:      $ext as extension file string 
					$funcPath as name of function get path
    Returns:        String
	************************************************************************/
	public static function getContentType( $ext, $song=false ) {
		switch ( strtolower( $ext ) ) 
		{
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
			case "mid":
				return "audio/mid";
				break;
			case "mmf" :
				return "application/x-smaf";
			case "pmd" :
				return "application/x-pmd";
			case "asf":
				return "video/x-ms-asf";
				break;
			case "flv":
				return "video/x-flv";
				break;
			case "amc":
				return "application/x-mpeg";
				break;
			case "3gp":
				
				if ($song == true) {
					return "audio/3gpp";
					break;
				}else {
					return "video/3gpp";
					break;
				}
			case "3g2":
				return "audio/3gpp2";
				break;
			case "mp4":
				return "video/mp4";
				break;
			case "swf":
				return "application/x-shockwave-flash";
				break;
			case "dmt":
				return "application/octet-stream";
				break;
			case "txt":
				return "application/x-tex";
				break;
			default :
				return "application/octet-stream";
		}
	}

	/***********************************************************************
    Function:		getFileType($file_name) 
    Description:    returns content type for the file type 
    Arguments:      $file_name as extension file string
    Returns:        String
	************************************************************************/
	public static function getFileType($file_name) {
		$str = '';
		if (ereg ( "\.", $file_name )) {
			return substr ( $file_name, (strrpos ( $file_name, "." ) + 1), strlen ( $file_name ) );
		}
	}
	
	/** Ham cat xâu */
	public static function stringTruncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false) {
		if ($length == 0) {
			return '';
		}
		
		$charset = 'UTF-8';
		$_UTF8_MODIFIER = 'u';
		
		if (mb_strlen($string, $charset) > $length) {
			$length -= min($length, mb_strlen($etc, $charset));
			if (!$break_words && !$middle) {
				$string = preg_replace('/\s+?(\S+)?$/' . $_UTF8_MODIFIER, '', mb_substr($string, 0, $length + 1, $charset));
			}
			if (!$middle) {
				return mb_substr($string, 0, $length, $charset) . $etc;
			}
			
			return mb_substr($string, 0, $length / 2, $charset) . $etc . mb_substr($string, - $length / 2, $length, $charset);
		}
		
		return $string;
	}
	
	public static function stringTruncateText($text, $length = 80, $truncate_string = '...', $truncate_lastspace = false) {
	  
		if ($text == '')
	  {
	    return '';
	  }
	
	  $mbstring = extension_loaded('mbstring');
	  
	  if($mbstring)
	  {
	   $old_encoding = mb_internal_encoding();
	   @mb_internal_encoding(mb_detect_encoding($text));
	  }
	  $strlen = ($mbstring) ? 'mb_strlen' : 'strlen';
	  $substr = ($mbstring) ? 'mb_substr' : 'substr';
	
	  if ($strlen($text) > $length)
	  {
	    $truncate_text = $substr($text, 0, $length - $strlen($truncate_string));
	    if ($truncate_lastspace)
	    {
	      $truncate_text = preg_replace('/\s+?(\S+)?$/', '', $truncate_text);
	    }
	    $text = $truncate_text.$truncate_string;
	  }
	
	  if($mbstring)
	  {
	   @mb_internal_encoding($old_encoding);
	  }
	
	  return $text;
	}

	/** Ham tra ve ky tu xuong dong **/
	public static function newLine () {
		return chr(10);
	}
	
	/** Ham format HTML text **/
	public static function htmlSpecialChars ($text) {
		return htmlspecialchars($text);
	}
	
	public static function htmlEntitiesChars($text) {
		return htmlentities($text);
	}
	
	/** Ham format xuong dòng **/
	public static function nl2brChars ($text) {
		return nl2br($text,false);
	}
}