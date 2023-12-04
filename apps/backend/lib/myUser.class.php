<?php
class myUser extends sfGuardSecurityUser {

	public static $accountGlobalSuperAdmin = 'Administrator';

	public static function getPsSchoolYearDefault() {

		$ps_school_year = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );

		if ($ps_school_year) {

			$ps_school_year_default = new \stdClass ();

			$ps_school_year_default->id 	   = $ps_school_year->getId ();
			$ps_school_year_default->title     = $ps_school_year->getTitle ();
			$ps_school_year_default->from_date = $ps_school_year->getFromDate ();
			$ps_school_year_default->to_date   = $ps_school_year->getToDate ();

			sfContext::getInstance ()->getUser ()->setAttribute ( 'ps_school_year_default', $ps_school_year_default );
		}
	}

	/**
	 * getPsWorkplaceId()
	 *
	 *
	 * @author Phung Van Thanh
	 *        
	 * @param $id -
	 *        	ID nhan su
	 * @return int id sfGuardUser login
	 *        
	 */
	public static function getWorkPlaceId($id) {

		$department = Doctrine::getTable ( 'psMemberDepartments' )->getDepartmentByPsMemberId ( $id );

		if ($department) {
			return $department->getPsWorkplaceId ();
		} else {
			//return Doctrine::getTable ( 'psMember' )->findOneById ( $id )->getPsWorkplaceId ();			
			return sfContext::getInstance ()->getUser ()->getGuardUser ()->getProfileShort()->getPsWorkplaceId();
		}
	}
	
	/**
	 * Lấy tên, id Cơ sở dào tạo cua user
	 * 
	**/
	public static function getWorkPlaceDepartment($member_id) {
		
		$department = Doctrine::getTable ( 'psMemberDepartments' )->getDepartmentByPsMemberId ( $member_id );
		
		return $department;
		
	}

	/**
	 *
	 * @function getUserObj()
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param
	 *        	int if of user
	 * @return Objuser user
	 *        
	 */
	public static function fakeGlobalAdministratorName() {

		return self::$accountGlobalSuperAdmin;

	}

	/**
	 *
	 * @function getUserObj()
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param
	 *        	int if of user
	 * @return Objuser user
	 *        
	 */
	public static function getUserObj($id) {

		$userObj = Doctrine::getTable ( 'sfGuardUser' )->findOneBy ( 'id', $id );
		return $userObj;

	}

	/**
	 * getUser()
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @return obj user
	 */
	public function getUser() {

		return sfContext::getInstance ()->getUser ()->getGuardUser ();

	}

	/**
	 * getUserId()
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @return int id sfGuardUser login
	 *        
	 */
	public function getUserId() {

		return sfContext::getInstance ()->getUser ()->getGuardUser ()->getId ();

	}

	/**
	 * Returns whether or not the user is a global super admin.
	 *
	 * @return boolean
	 */
	public function isGlobalSuperAdmin() {

		return $this->getGuardUser () ? $this->getGuardUser ()->getIsGlobalSuperAdmin () : false;

	}

	/**
	 *
	 * @function isAdministrator()
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param
	 * @return Boolean - TRUE is Global Admin; FALSE not is Global Admin
	 *        
	 */
	public static function isAdministrator() {

		if (! sfContext::getInstance ()->getUser ()->isAuthenticated ())
			return null;

		return (sfContext::getInstance ()->getUser ()->getGuardUser ()->getIsGlobalSuperAdmin () && sfContext::getInstance ()->getUser ()->getGuardUser ()->getIsSuperAdmin ());

	}

	/**
	 * Kiem tra quyen chon PsCustomer cho he thong
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param int $psCustomerID
	 * @return boolean *
	 */
	public static function showPscustomers($function_code = null) {

		if (self::isAdministrator ())
			return true;
		else {
			if ($function_code != null)
				return self::hasCredential ( $function_code, false );
			else
				return false;
		}

	}

	/**
	 * Kiem tra quyen $function_code cho he thong
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param $function_code -
	 *        	string; Ma chuc nang
	 * @return boolean *
	 */
	public static function credentialPsCustomers($function_code = null) {

		if (self::isAdministrator ())
			return true;
		else {
			return ($function_code != null) ? sfContext::getInstance ()->getUser ()->hasCredential ( $function_code, false ) : false;
		}

	}

	/**
	 * Thiet lap session cua PsCustomer cho he thong
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param int $psCustomerID
	 * @return void description
	 *         *
	 */
	public static function setPscustomerID($psCustomerID) {

		// sfContext :: getInstance()->getUser()->setAttribute('ADpsCustomerID', $psCustomerID/*, 'myUser'*/);
		sfContext::getInstance ()->getUser ()->setAttribute ( 'ADpsCustomerID', $psCustomerID, 'myUser' );

	}

	/**
	 * getPscustomerID() - Lay ID cua PsCustomer tham gia vao he thong.
	 *
	 * - Neu la Administrator login thi ID PsCustomer nay do Administrator load ra trong qua trinh thao tac
	 * - Neu la do Customer login thi ID chinh là ID PsCustomer su dung he thong
	 *
	 * @param
	 *        	null
	 * @return int ID customer cua member
	 *        
	 */
	public static function getPscustomerID() {
	
		$ADpsCustomerID = (sfContext::getInstance ()->getUser ()->isAuthenticated ()) ? sfContext::getInstance ()->getUser ()->getGuardUser ()->getPsCustomerId () : false;
		
		/*
		$psHeaderFilter = sfContext::getInstance ()->getUser ()->getAttribute ( 'psHeaderFilter', null, 'admin_module' );
		
		if (! $psHeaderFilter) {
			$ps_customer_id = sfContext::getInstance ()->getUser ()->getGuardUser ()->getPsCustomerId ();
		} else {
			$ps_customer_id = $psHeaderFilter ['ps_customer_id'];
		}
		*/
		return $ADpsCustomerID;

	}

	// Lay Ps Customer
	public static function getPsCustomerById($psCustomerID) {

		return Doctrine::getTable ( 'PsCustomer' )->findOneById ( $psCustomerID );

	}

	/**
	 * checkAccessObject($obj_customerID)
	 * Kiem tra quyen tac dong vao mot Object thuoc PsCustomer
	 *
	 *
	 * @param int $obj_customerID
	 *        	- id PsCustomer cua Object
	 * @param
	 *        	string - $function_code
	 * @return boolean
	 *
	 */
	public static function checkAccessObject($obj, $function_code = null) {

		if (! $obj) {
			return false;
		} else {
			return (self::credentialPsCustomers ( $function_code ) || ($obj->getPsCustomerId () == self::getPscustomerID ()));
		}

	}

	/**
	 * checkRoleObject($obj_customerID)
	 * Kiem tra quyen tac dong vao mot Object thuoc PsCustomer neu User dang nhap khong phai la Administrator
	 *
	 *
	 * @param int $obj_customerID
	 *        	- id PsCustomer cua Object
	 *        	return boolean
	 *        	*
	 */
	public static function checkRoleObject($obj) {

		if (! $obj) {
			return false;
		} elseif (self::isAdministrator () || ($obj->getPsCustomerId () == self::getPscustomerID ())) {
			return true;
		}

		return false;

	}

	/**
	 * Returns true if user has credential.
	 *
	 * @author Nguyen Chien Thang <ntsc279@hotmail.com>
	 *        
	 * @param mixed $credentials
	 * @param bool $useAnd specify the mode, either AND or OR
	 * @return boolean
	 */
	public function hasCredential($credential, $useAnd = true) {

		// combine the credential and the permission check
		return parent::hasCredential ( $credential, $useAnd );

	}

}