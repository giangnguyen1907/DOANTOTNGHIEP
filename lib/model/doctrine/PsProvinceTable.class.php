<?php

/**
 * PsProvinceTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsProvinceTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsProvinceTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsProvince' );
	}

	// Lay gia tri Max cua iorder, return: int - max order
	public function getMaxIorder() {

		return $this->createQuery ()
			->select ( 'MAX(iorder) AS max_order' )
			->fetchOne ()
			->getMaxOrder ();
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

		$query->select ( $a . '.id AS id, ' . $a . '.s_code AS s_code, ' . $a . '.name AS name, ' . $a . '.iorder AS iorder, ' . $a . '.is_activated AS is_activated, ' . $a . '.user_updated_id AS user_updated_id, ' . $a . '.updated_at AS updated_at,' . 'CONCAT(u.first_name, " ", u.last_name) AS updated_by' );

		$query->leftJoin ( $a . '.UserUpdated u' );

		return $query;
	}

	/**
	 * FUNCTION: getOnePsProvinceById($id)
	 *
	 * @param $id -
	 *        	string
	 * @return one obj
	 */
	public function getOnePsProvinceById($id = null) {

		$sql = $this->createQuery ()
			->select ( 'id,country_code,name' );

		if ($id != '')
			$sql->where ( 'id = ?', $id );

		return $sql->fetchOne ();
	}

	/**
	 * SQL lay danh sach tinh thanh
	 *
	 * @param $country_code - string
	 * @param $id - int or array
	 * @return string - sql
	 */
	public function setSqlPsProvinceByCountry($country_code = null, $id = null) {

		$q = $this->createQuery ()->select ( "id, CONCAT(s_code,'-',name) AS name" );

		if ($country_code != '')
			$q->andWhere('country_code = ?', $country_code );
		
		if (is_array($id) && count($id) > 0) {
			$q->andWhereIn('id', $id );
		} elseif ($id > 0) {
			$q->andWhere ( 'id = ?', $id );
		}

		$q->orderBy ( 'iorder' );

		return $q;
	}

	/**
	 * FUNCTION: loadPsProvinceByCountry($country_code)
	 *
	 * @param $country_code -
	 *        	string
	 * @return mixed
	 */
	public function loadPsProvinceByCountry($country_code = null) {

		return $this->setSqlPsProvinceByCountry ( $country_code )
			->execute ();
	}

	/**
	 * getGroupPsDistricts($country_code = '')
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param $country_code String
	 * @return mixed
	 *        
	 */
	public function getGroupPsProvinceDistricts($country_code = '') {

		$chois = array ();

		$query = $this->createQuery ();

		$query->select ( 'id, name' );

		$query->leftJoin ( 'p.PsDistrict d' );

		if ($country_code != '')

			$query->where ( 'p.country_code = ?', $country_code );

		$districts = $query->execute ();

		$count_district = count ( $districts );

		for($i = 0; $i < $count_district - 1; $i ++) {
			for($j = 1; $j < $count_district; $j ++) {
				if ($districts [$i]->getId () == $districts [$j]->getId ()) {
					$chois [$districts [$i]->getName ()] [$districts [$i]->getDId ()] = $districts [$i]->getDName ();
				}
			}
		}

		return $chois;
	}
	
	/**
	 * FUNCTION: setSqlPsUserProvinceByUserId($user_id)
	 *
	
	 * @return string - sql
	 */
	public function setSqlPsUserProvinceByUserId($user_id) {

		$query = $this->createQuery ('p')->select ( "p.id, CONCAT(p.s_code,'-',p.name) AS name" );

		$query->innerJoin( 'p.PsUserProvinces up ON p.id = up.ps_province_id' );

		$query->where ( 'up.user_id = ?', $user_id );

		$query->orderBy ( 'p.iorder' );

		return $query;
	}
}