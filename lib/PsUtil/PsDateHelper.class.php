<?php
/**
 * @project_name
 * @subpackage     interpreter 
 *
 * @file PsDateHelper.class.php
 * @filecomment filecomment
 * @package_declaration package_declaration
 * @author PreSchool.vn
 * @version 1.0 21-06-2017 -  09:01:15
 */
class PsDateHelper {

	/**
	 * format_date($value, $format)
	 *
	 * @desc: Used format_date of DateHelper in symfony
	 *
	 * @param $value -
	 *        	DateTime
	 * @param $format -
	 *        	string
	 *        	
	 * @return string
	 */
	public static function format_date($value, $format = 'dd-MM-yyyy') {

		$sf_date = new sfDateFormat ();

		return $sf_date->format ( $value, $format );
	}
}