<?php

/**
 * 
 */
class PreString {

	// Địa chỉ URl lấy dữ liệu file ảnh avatar
	const PS_URL_MEDIA = 'https://quanly.kidsschool.vn/media';

	public function __construct() {

	}

	public static function trim($text) {

		return trim ( $text );
	}

	public static function length($text) {

		return mb_strlen ( $text );
	}

	public static function strLower($text) {

		return mb_strtolower ( $text );
	}

	public static function stripTags($text) {
		
		return strip_tags ( $text );
	}
	
	public static function strReplace($text, $arr_char = array('.',',',';')) {

		return str_replace ( $arr_char, "", $text );
	}
	
	/**
	 * Convert text Viet Nam to latin
	 * *
	 */
	public static function covert_to_latin($str) {
		$str = preg_replace ( "/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str );
		$str = preg_replace ( "/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str );
		$str = preg_replace ( "/(ì|í|ị|ỉ|ĩ)/", 'i', $str );
		$str = preg_replace ( "/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str );
		$str = preg_replace ( "/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str );
		$str = preg_replace ( "/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str );
		$str = preg_replace ( "/(đ)/", 'd', $str );
		$str = preg_replace ( "/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str );
		$str = preg_replace ( "/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str );
		$str = preg_replace ( "/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str );
		$str = preg_replace ( "/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str );
		$str = preg_replace ( "/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str );
		$str = preg_replace ( "/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str );
		$str = preg_replace ( "/(Đ)/", 'D', $str );
		
		$str = str_replace ( '-', ' ', $str );
		$str = str_replace ( '_', ' ', $str );
		
		$str = preg_replace ( array ('/\s+/','/[^A-Za-z0-9\-]/'), array ('-',''), $str );
		
		return $str;
	}

	/**
	 * Ham cat sâu
	 */
	function stringTruncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false) {

		if ($length == 0) {
			return '';
		}

		$charset = 'UTF-8';
		$_UTF8_MODIFIER = 'u';

		if (mb_strlen ( $string, $charset ) > $length) {
			$length -= min ( $length, mb_strlen ( $etc, $charset ) );
			if (! $break_words && ! $middle) {
				$string = preg_replace ( '/\s+?(\S+)?$/' . $_UTF8_MODIFIER, '', mb_substr ( $string, 0, $length + 1, $charset ) );
			}
			if (! $middle) {
				return mb_substr ( $string, 0, $length, $charset ) . $etc;
			}

			return mb_substr ( $string, 0, $length / 2, $charset ) . $etc . mb_substr ( $string, - $length / 2, $length, $charset );
		}

		return $string;
	}
	
	/** Ham convert html entity  to text unicode
	 * 
	 * 
	 * @param string $full_name
	 * @return string
	**/
	function htmlEntityDecode ($string, $flags = null, $encoding = null) {
		
		return html_entity_decode($string, $flags, $encoding);
		
	}

	public static function getFullName($full_name) {
		
		$full_name = self::trim($full_name);
		
		$array_sun = array ();
		
		if ($full_name == '') {
			$array_sun ['first_name'] = '';
			$array_sun ['last_name']  = '';
		} else {
			$stack = explode ( ' ', $full_name );
			$fruit = array_pop ( $stack );
			$first_name = implode ( ' ', $stack );
			$last_name = $fruit;
	
			$array_sun ['first_name'] = $first_name;
			$array_sun ['last_name'] = $last_name;
		}
		
		return $array_sun;
	}

	/**
	 * Ham tra ve url media avatar cua Giao vien, hoc sinh, nguoi than
	 *
	 * @param $cache_data -
	 *        	string, folder media
	 * @param $year_data -
	 *        	int, nam chua du lieu
	 * @param $type -
	 *        	string, kieu du lieu: 01- Nhan su; 02- Nguoi than; 03- Hoc sinh
	 * @param $file_name -
	 *        	string, file anh
	 */
	public static function getUrlMediaAvatar($cache_data, $year_data, $file_name, $type) {

		return self::PS_URL_MEDIA . '/image/' . $type . '/' . $cache_data . '/' . $year_data . '/' . $file_name;
	}
}