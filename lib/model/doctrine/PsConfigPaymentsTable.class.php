<?php

/**
 * PsConfigPaymentsTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsConfigPaymentsTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsConfigPaymentsTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsConfigPayments' );
	}

	/**
	 * FUNCTION: doSelectQuery(Doctrine_Query $query)
	 *
	 * @param
	 *        	Doctrine SQL
	 * @return string SQL
	 */
	public function doSelectQuery(Doctrine_Query $query) {

		$a = $query->getRootAlias ();

		$query->addSelect ( $a . '.id,' . $a . '.title, ' . $a . '.price,' . $a . '.number_month,' . $a . '.note,' . $a . '.is_activated, ' . $a . '.updated_at, CONCAT(u.first_name, " ", u.last_name) AS updated_by' )
			->
		leftJoin ( $a . '.UserUpdated u' );

		if (! myUser::credentialPsCustomers ( 'PS_FEE_CONFIG_PAYMENT_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			$query->where ( $a . '.ps_customer_id = ?', myUser::getPscustomerID () );
		}

		return $query;
	}
}