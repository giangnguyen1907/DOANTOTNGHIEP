<?php
/**
 * PsConstantTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsConstantTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsConstantTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsConstant' );
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

		$query->select ( $a . '.id AS id, ' . $a . '.title AS title, ' . $a . '.c_code AS c_code, ' . $a . '.value_default AS value_default, ' . $a . '.note AS note, ' . $a . '.iorder AS iorder, ' . $a . '.updated_at AS updated_at,' . 'CONCAT(u.first_name, " ", u.last_name) AS updated_by' );

		$query->leftJoin ( $a . '.UserUpdated u' );

		return $query;
	}

	/**
	 * FUNCTION: loadPsConstantByCustomer($ps_customer_id)
	 *
	 * @param int $ps_customer_id
	 * @return list obj
	 */
	public function loadPsConstantByCustomer($ps_customer_id = null, $ps_constant_id = null) {

		$q = $this->createQuery ( 'a' )
			->select ( 'a.id, a.title' )
			->where ( '1=1' );

		if ($ps_customer_id > 0 && $ps_constant_id > 0)
			$q->andWhere ( 'a.id IN (SELECT b.ps_constant_id FROM PsConstantOption b WHERE b.ps_customer_id =? AND b.ps_constant_id = ?)', array (
					$ps_customer_id,
					$ps_constant_id ) );
		elseif ($ps_customer_id > 0 && $ps_constant_id <= 0)
			$q->andWhere ( 'a.id NOT IN (SELECT b.ps_constant_id FROM PsConstantOption b WHERE b.ps_customer_id =?)', array (
					$ps_customer_id ) );

		// if ($ps_constant_id > 0)
		// $q->andWhere('a.id <> ?', $ps_constant_id);

		$q->orderBy ( 'a.iorder' );

		// return $q->execute();
		return $q;
	}
}