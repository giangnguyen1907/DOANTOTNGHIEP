<?php

/**
 * PsContractTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsContractTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsContractTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsContract' );
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

		$query->select ( $a . '.id AS id, ' . $a . '.title AS title, ' . $a . '.iorder AS iorder, ' . $a . '.is_activated AS is_activated, ' . $a . '.updated_at AS updated_at,' . 'CONCAT(u.first_name, " ", u.last_name) AS updated_by' );

		$query->leftJoin ( $a . '.UserUpdated u' );

		return $query;
	}
}