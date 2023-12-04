<?php

/**
 * 
 */
class PreNumber {

	public function __construct() {

	}

	public static function number_format($number, $decimals = 0, $dec_point = ".", $thousands_sep = ",") {

		return number_format ( $number, $decimals, $dec_point, $thousands_sep );
	}

	public static function format_number($number, $culture = null) {

		return format_number ( $number, $culture );
	}

	public static function format_currency($amount, $currency = null, $culture = null) {

		return format_currency ( $amount, $currency, $culture );
	}

	public static function _current_language($culture) {

		return _current_language ( $culture );
	}

	/*
	 * Function: number_clean
	 * Purpose: Remove trailing and leading zeros - just to return cleaner number
	 */
	public function number_clean($num) {

		// remove zeros from end of number ie. 140.00000 becomes 140.
		$clean = rtrim ( $num, '0' );

		// remove decimal point if an integer ie. 140. becomes 140
		$clean = rtrim ( $clean, '.' );

		return $clean;
	}
}