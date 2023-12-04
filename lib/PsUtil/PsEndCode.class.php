<?php
/**
 * 
 */
class PsEndCode {

	// public const PS_API_HASH_USER_ENCRYPT_KEY = 'MTM2YWIzODY0NjYzOGJlZWI1MjgxOTZlOGM5ZWZjZTcxNzc3YTcyYjQ3ZjVjODZhND';
	function __construct() {

		// code...
	}

	// decode of string
	public static function ps64Decode($string) {

		return base64_decode ( $string );
	}

	// endcode of string
	public static function ps64EndCode($string) {

		return base64_encode ( $string );
	}

	// urlencode of string
	public static function psEndCode($string) {

		return urlencode ( $string );
	}

	// urldecode of string
	public static function psUrlDeCode($string) {

		return urldecode ( $string );
	}

	public static function psHash256($string) {

		return hash ( "sha256", $string );
	}
}