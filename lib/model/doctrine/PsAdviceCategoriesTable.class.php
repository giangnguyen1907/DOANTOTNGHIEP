<?php

/**
 * PsAdviceCategoriesTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsAdviceCategoriesTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsAdviceCategoriesTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsAdviceCategories' );
	}

	public function doSelectQuery(Doctrine_Query $query) {

		$a = $query->getRootAlias ();

		$query->select ( $a . '.id AS id,' . $a . '.ps_customer_id AS ps_customer_id,' . $a . '.title AS title,' . $a . '.note AS note,' . $a . '.is_activated AS is_activated,' . $a . '.user_created_id AS user_created_id,' . $a . '.user_updated_id AS user_updated_id,' . $a . '.created_at AS created_at,' . $a . '.updated_at AS updated_at,' . 'cus.school_name AS cus_title,' . 'CONCAT(u.first_name, " ", u.last_name) AS updated_by,' . 'CONCAT(uc.first_name, " ", uc.last_name) AS creator_by' );

		$query->leftJoin ( $a . '.PsCustomer cus' );

		$query->leftJoin ( $a . '.UserCreated uc' );

		$query->leftJoin ( $a . '.UserUpdated u' );

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_ADVICE_CATEGORIES_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {

			$query->addWhere ( $a . '.ps_customer_id = ?', myUser::getPscustomerID () );

			$query->addWhere ( $a . '.is_activated = ?', PreSchool::ACTIVE );
		}

		$query->orderBy ( $a . '.created_at DESC' );
		return $query;
	}
}