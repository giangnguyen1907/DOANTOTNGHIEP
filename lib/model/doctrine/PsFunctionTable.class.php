<?php

/**
 * PsFunctionTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsFunctionTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsFunctionTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsFunction' );
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

		$query->select ( $a . '.id AS id, ' . $a . '.title AS title, ' . $a . '.description AS description, ' . $a . '.iorder AS iorder, ' . $a . '.ps_customer_id AS ps_customer_id, ' . 'cus.title AS customer_title, ' . $a . '.is_activated AS is_activated, ' . $a . '.user_updated_id AS user_updated_id, ' . $a . '.updated_at AS updated_at,' . 'CONCAT(u.first_name, " ", u.last_name) AS updated_by' );

		$query->leftJoin ( $a . '.PsCustomer cus' );
		$query->leftJoin ( $a . '.UserUpdated u' );

		$query->where ( $a . '.ps_customer_id IS NULL' ); // Lay danh sach chung

		if (! myUser::credentialPsCustomers ( 'PS_HR_FUNCTION_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0)
			$query->orWhere ( $a . '.ps_customer_id = ?', ( int ) myUser::getPscustomerID () );
		else
			$query->orWhere ( '1=1' );

		return $query;
	}

	/**
	 * Set SQL loc ra Chuc vu theo customer id
	 */
	public function setFunctionByCutomer($customer_id) {

		$query = $this->createQuery ( 'fc' );

		$query->select ( 'fc.id AS fc_id,' . 'fc.title AS function_name,' );

		$query->andWhere ( 'fc.is_activated = ?', PreSchool::ACTIVE );

		if ($customer_id > 0) {
			$query->andWhere ( 'fc.ps_customer_id IS NULL OR fc.ps_customer_id = ?', $customer_id );
		}

		if (! isset ( $customer_id )) {
			$query->andWhere ( 'fc.ps_customer_id IS NULL' );
		}

		$query->orderBy ( 'iorder DESC, title ASC' );

		return $query;
	}
}