<?php

/**
 * PsSalaryTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsSalaryTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsSalaryTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsSalary' );
	}

	public function doSelectQuery(Doctrine_Query $query) {

		$a = $query->getRootAlias ();

		$tracked_at = date ( 'Ymd' );

		$query->select ( $a . '.id AS id,' . $a . '.ps_customer_id AS ps_customer_id,' . $a . '.basic_salary AS title,' . $a . '.day_work_per_month AS day_work_per_month,' . $a . '.is_activated AS is_activated,' . $a . '.note AS 	note,' . 'CONCAT(u.first_name, " ", u.last_name) AS updated_by,' . $a . '.updated_at AS updated_at, ' . 'cus.school_name AS school_name,' );
		$query->leftJoin ( $a . '.UserUpdated u' );

		$query->leftJoin ( $a . '.PsCustomer cus' );

		if (! myUser::credentialPsCustomers ( 'PS_HR_SALARY_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			$query->where ( $a . '.ps_customer_id = ?', myUser::getPscustomerID () );
		}

		return $query;
	}

	/**
	 * Lay ra danh muc luong cua nhan vien
	 */
	public function getSalaryByCustomerId($customer_id) {

		$query = $this->createQuery ( 'a' );
		$query->select ( 'a.id AS id,' . 'a.ps_customer_id AS ps_customer_id,' . 
		// 'a.basic_salary AS basic_salary,' .
		'a.basic_salary AS title,' . 'a.day_work_per_month AS day_work_per_month,' . 'a.is_activated AS is_activated,' . 'a.note AS note,' . 'cus.school_name AS school_name,' . 'CONCAT(uc.first_name, " ", uc.last_name) AS creator_by, ' . 'CONCAT(ud.first_name, " ", ud.last_name) AS updated_by, ' );

		$query->leftJoin ( 'a.PsCustomer cus' );

		$query->leftJoin ( 'a.UserCreated uc' );

		$query->leftJoin ( 'a.UserUpdated ud' );

		$query->addWhere ( 'a.ps_customer_id = ?', $customer_id );

		if (! myUser::isAdministrator ()) {

			$query->addWhere ( 'a.is_activated=?', PreSchool::ACTIVE );
		}

		$query->orderBy ( 'a.created_at DESC' );

		return $query->execute ();
	}

	/**
	 * Tao SQL lay danh sach theo ps_customer_id
	 *
	 * @return $list obj
	 *        
	 */
	public function setSQLByCustomerId($psCustomerId = null, $is_activated = null) {

		$q = $this->createQuery ()->select ( 'id, FORMAT(basic_salary, 0) AS title' );

		if ($psCustomerId > 0)
			$q->addWhere ( 'ps_customer_id = ?', $psCustomerId );

		if (isset ( $is_activated ))
			$q->addWhere ( 'is_activated = ?', $is_activated );

		return $q;
	}

	/**
	 * Lay ra chi tiet 1 ban luong
	 */
	public function getSalaryById($salary_id) {

		$query = $this->createQuery ( 'a' );
		$query->select ( 'a.id AS id,' . 'a.ps_customer_id AS ps_customer_id,' . 'a.basic_salary AS title,' . 'a.day_work_per_month AS day_work_per_month,' . 'a.is_activated AS is_activated,' . 'a.note AS note,' . 'cus.school_name AS school_name,' . 'CONCAT(uc.first_name, " ", uc.last_name) AS creator_by, ' . 'CONCAT(ud.first_name, " ", ud.last_name) AS updated_by, ' );

		$query->leftJoin ( 'a.PsCustomer cus' );

		$query->leftJoin ( 'a.UserCreated uc' );

		$query->leftJoin ( 'a.UserUpdated ud' );

		$query->addWhere ( 'a.id = ?', $salary_id );

		return $query->fetchOne ();
	}
}