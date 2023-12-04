<?php

/**
 * PsReceiptTemporaryTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsReceiptTemporaryTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsReceiptTemporaryTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsReceiptTemporary' );
	}

	public function doSelectQuery(Doctrine_Query $query) {

		$a = $query->getRootAlias ();

		$query->select ( $a . '.id AS id, ' . $a . '.ps_customer_id AS ps_customer_id, ' . $a . '.student_id, ' . $a . '.title AS title, ' . $a . '.receipt_date AS receipt_date, ' . $a . '.receivable AS receivable, ' . $a . '.collected_amount	 AS collected_amount	,' . $a . '.balance_amount AS balance_amount, ' . $a . '.is_current	 AS is_current	, ' . 'CONCAT(u.first_name, " ", u.last_name) AS updated_by,' . $a . '.is_import		 AS is_import		, ' . $a . '.payment_status		 AS payment_status		, ' . $a . '.note	 AS note	, ' . $a . '.updated_at	 AS updated_at	, ' . 
		// 's.id AS s_id ,'.
		're.id AS re_id ,' . 'CONCAT(re.first_name, " ", re.last_name) AS relative_name,' . 'CONCAT(s.first_name, " ", s.last_name) AS student_name,' . 's.student_code AS student_code,' );

		$query->innerJoin ( $a . '.PsCustomer cus' );

		$query->leftJoin ( $a . '.Student s' );

		$query->leftJoin ( $a . '.UserUpdated u' );

		$query->leftJoin ( $a . '.Relative re' );
		// $query->leftJoin ( 's.Relative re' );

		if (! myUser::credentialPsCustomers ( 'PS_FEE_RECEIVABLE_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			$query->where ( $a . '.ps_customer_id = ?', myUser::getPscustomerID () );
		}

		return $query;
	}
}