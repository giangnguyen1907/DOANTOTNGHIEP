<?php
/**
 * @author     Nguyen Chien Thang <ntsc279@gmail.com>
 * @version    1.0.0
 */
class psValidatorEmail {

	public function __construct() {

	}

	/**
	 * Function check unique email from ps member model
	 *
	 * @param
	 *        	string email
	 *        	
	 * @return boolean
	 */
	public function checkUniqueEmailPsMember($email, $obj_id = null, $obj_type) {

		$ps_email_boolean = Doctrine::getTable ( 'PsEmails' )->checkEmailExits ( $email, $obj_id, $obj_type );

		return ! $ps_email_boolean;
	}

	/**
	 * Kiem tra dia chi email co hop le khong
	 *
	 * @param $email -
	 *        	string
	 * @return boolean
	 */
	public function validEmail($email) {

		return filter_var ( $email, FILTER_VALIDATE_EMAIL );
	}
}