<?php

namespace App\PsUtil;

/**
* 
*/
class PsEndCode
{
	
	function __construct()
	{
		# code...
	}

	// decode of string
	public static function ps64Decode($string) {
		return base64_decode($string);
	}

	// endcode of string
	public static function ps64EndCode($string) {
		return base64_encode($string);
	}

	// urlencode of string
	public static function psEndCode($string) {
		return urlencode($string);
	}

	// urldecode of string
	public static function psUrlDeCode($string) {
		return urldecode($string);
	}
	
	public static function psHash256($string) {
		return hash("sha256", $string);
	}
	
	// Tạo album key
	public static function psGenerateAlbumKey($ps_customer_id, $ps_class_id) {
		return md5 ( md5 ( $user->ps_customer_id ) + $ps_class_id + time () );
	}
	
	// Tạo path url album in firebase
	public static function psGeneratePathUrlAlbum($ps_customer_id, $album_key, $current_date_time) {
		
		return md5 ($ps_customer_id) . '/' . date ( "Ym", strtotime ( $current_date_time ) ) .'/' . date ( "d", strtotime ( $current_date_time ) ) . '/' . $album_key;
	}
}