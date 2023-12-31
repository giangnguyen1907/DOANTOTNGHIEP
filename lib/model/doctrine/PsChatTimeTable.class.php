<?php

/**
 * PsChatTimeTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsChatTimeTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsChatTimeTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsChatTime' );
	}

	public function doSelectQuery(Doctrine_Query $query) {

		$a = $query->getRootAlias ();

		$query->select ( $a . '.id AS id,' . $a . '.ps_customer_id AS ps_customer_id,' . $a . '.ps_workplace_id AS ps_workplace_id,' . $a . '.title AS title,' . $a . '.start_time AS start_time,' . $a . '.end_time AS end_time,' . $a . '.note AS note,' . $a . '.is_activated AS is_activated,' . $a . '.created_at AS created_at,' . $a . '.updated_at AS updated_at,' . 'CONCAT(u.first_name, " ", u.last_name) AS updated_by,' . 'cus.school_name AS school_name,' );

		if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_CHAT_TIME_CONFIG_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			$query->addWhere ( $a . '.ps_customer_id = ?', myUser::getPscustomerID () );

			$query->addWhere ( $a . '.is_activated = ?', PreSchool::ACTIVE );
		}

		$query->leftJoin ( $a . '.PsCustomer cus' );

		$query->leftJoin ( $a . '.UserUpdated u' );

		$query->addOrderBy ( $a . '.created_at DESC' );

		return $query;
	}
}