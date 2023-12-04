<?php

/**
 * PsHistoryFeesTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsHistoryFeesTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsHistoryFeesTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsHistoryFees' );
	}
	
	/**
	 * FUNCTION: doSelectQuery(Doctrine_Query $query)
	 *
	 * @param
	 *        	Doctrine SQL
	 * @return string SQL
	 *
	 */
	public function doSelectQuery(Doctrine_Query $query) {
	
		$a = $query->getRootAlias ();
	
		$query->select (
				$a . '.receipt_no AS receipt_no,' . 
				$a . '.receipt_date AS receipt_date, ' . 
				$a . '.ps_action AS ps_action, ' .
				$a . '.updated_at AS updated_at,' .
				's.student_code AS student_code,' .
				'CONCAT(s.first_name, " ", s.last_name) AS student_name' );
		
		$query->innerJoin ($a. '.Student s' );
		//$query->leftJoin ( $a . '.UserUpdated u' );
		
		return $query;
	}
}