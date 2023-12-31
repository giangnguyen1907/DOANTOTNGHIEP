<?php

/**
 * PsWorkingTimeTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsWorkingTimeTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsWorkingTimeTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsWorkingTime' );
	}

	public function doSelectQuery(Doctrine_Query $query) {

		$a = $query->getRootAlias ();

		$query->select ( $a . '.id AS id, ' . $a . '.title AS title, ' . $a . '.start_time AS start_time, ' . $a . '.end_time AS end_time, ' . $a . '.note AS note, ' . $a . '.is_activated AS is_activated, ' . $a . '.updated_at AS updated_at,' . $a . '.ps_customer_id AS ps_customer_id, ' . $a . '.ps_workplace_id AS ps_workplace_id, ' . 'cus.title AS customer_title, ' . 'wp.title AS workplace_title,' . 'CONCAT(u.first_name, " ", u.last_name) AS updated_by' );

		$query->leftJoin ( $a . '.PsCustomer cus' );
		$query->leftJoin ( $a . '.PsWorkPlaces wp' );
		$query->leftJoin ( $a . '.UserUpdated u' );

		// $query->where($a .'.ps_customer_id IS NULL');// Lay danh sach chung

		if (! myUser::credentialPsCustomers ( 'PS_HR_WORKINGTIME_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0)
			$query->orWhere ( $a . '.ps_customer_id = ?', ( int ) myUser::getPscustomerID () );
		else
			$query->orWhere ( '1=1' );

		return $query;
	}
}