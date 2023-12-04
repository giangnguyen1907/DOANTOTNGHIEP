<?php

/**
 * PsReligionTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsReligionTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsReligionTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsReligion' );
	}

	/**
	 * FUNCTION: doSelectQuery(Doctrine_Query $query)
	 *
	 * @param
	 *        	Doctrine SQL
	 * @return string SQL
	 */
	public function doSelectQuery(Doctrine_Query $query) {

		$alias = $query->getRootAlias ();
		$query->addSelect ( $alias . '.title,' . $alias . '.is_activated,' . $alias . '.iorder, ' . $alias . '.updated_at,CONCAT(u.first_name," ",u.last_name) AS updated_by' )
			->leftJoin ( $alias . '.UserUpdated u' );
		return $query;
	}

	public function setSQLSelectReligion() {

		$query = $this->createQuery ( 're' );
		$query->addSelect ( 're.title AS title, re.id AS id' );
		$query->andWhere ( 're.is_activated=?', PreSchool::ACTIVE );
		$query->orderBy ( 're.iorder' );
		return $query;
	}
}