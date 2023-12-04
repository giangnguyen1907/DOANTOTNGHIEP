<?php
namespace App\PsUtil;

/**
 */
class PsNumber {

	public function __construct() {

	}

	public static function format_price($amount, $currency = 'VND') {

		// return money_format('%i', $amount).' '.$currency;
		if (! is_numeric ( $amount ))
			return number_format ( $amount, 2, ',', '.' ).$currency;
		else
			return number_format ( $amount, 0, ',', '.' ).$currency;
	}

	public static function format_currency($amount, $currency = null, $culture = null) {

		return format_currency ( $amount, $currency, $culture);
	}
	
	/*
	* Function: number_clean
	* Purpose: Remove trailing and leading zeros - just to return cleaner number
	*/ 
	public function number_clean($num){ 
	
	  //remove zeros from end of number ie. 140.00000 becomes 140.
	  $clean = rtrim($num, '0');
	  
	  //remove decimal point if an integer ie. 140. becomes 140
	  $clean = rtrim($clean, '.');
	
	  return $clean;
	}

}