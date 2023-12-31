<?php

/**
 * PsDistrictTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsDistrictTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsDistrictTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsDistrict' );
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
	 *        
	 */
	public function doSelectQuery(Doctrine_Query $query) {

		$a = $query->getRootAlias ();

		$query->select ( $a . '.id AS id,' . $a . '.s_code AS s_code, ' . $a . '.name AS name, ' . $a . '.iorder AS iorder, ' . $a . '.is_activated AS is_activated, ' . $a . '.updated_at AS updated_at,' . 'p.country_code AS country_code,' . 'p.name AS province_name,' . 'CONCAT(u.first_name, " ", u.last_name) AS updated_by' );

		$query->leftJoin ( $a . '.PsProvince p' );

		$query->leftJoin ( $a . '.UserUpdated u' );

		return $query;
	}

	/**
	 * FUNCTION: getOnePsDistrictById($id)
	 *
	 * @param $id -
	 *        	string
	 * @return one obj
	 */
	public function getOnePsDistrictById($id = null) {

		$sql = $this->createQuery ()
			->select ( 'id,ps_province_id,name' );

		if ($id != '')
			$sql->where ( 'id = ?', $id );

		return $sql->fetchOne ();
	}

	/**
	 * findColumnAllObjectByProvince - Lay id, name cua PsDistrict theo Tinh/Thanh
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param $province_id -
	 *        	int id tinh thanh
	 * @return mixed
	 */
	public function findColumnAllObjectByProvince($province_id = '') {

		$q = $this->createQuery ()
			->select ( 'id, name' );

		if ($province_id != '')
			$q->where ( 'ps_province_id = ?', $province_id );

		$q->orderBy ( 'iorder' );

		return $q->execute ();
	}

	/**
	 * getAllByProvinceId - Lay id, name cua PsDistrict theo Tinh/Thanh
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param $province_id -
	 *        	int id tinh thanh
	 * @param
	 *        	$id
	 * @return mixed
	 */
	public function getAllByProvinceId($province_id = null, $id = null) {

		return $this->setSqlPsDistrictByProvinceId ( $province_id, $id )
			->execute ();
	}

	/**
	 * FUNCTION: setSqlPsProvinceByCountry(Doctrine_Query $query)
	 *
	 * @param $country_code -
	 *        	string
	 * @return string - sql
	 */
	public function setSqlPsDistrictByProvinceId($province_id = null, $id = null) {

		$q = $this->createQuery ()
			->select ( 'id, CONCAT(s_code, "-", name ) AS name' );

		if ($province_id > 0)
			$q->where ( 'ps_province_id = ?', $province_id );

		if ($id > 0) {
			$q->where ( 'id = ?', $id );
		}

		$q->orderBy ( 'iorder' );

		return $q;
	}

	/**
	 * setGroupPsDistricts
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param $country_code String
	 * @return mixed
	 *        
	 */
	public function setGroupPsDistricts($country_code = '') {

		$query = $this->createQuery ( 'a' );

		$query->select ( 'a.id, a.name, p.id AS province_id, CONCAT(p.id, "-", p.name ) AS province_name' );

		$query->leftJoin ( 'a.PsProvince p' );

		if ($country_code != '') {
			$query->where ( 'p.country_code = ?', $country_code );
		}

		return $query;
	}

	/**
	 * getGroupPsDistricts
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param $country_code String
	 * @return mixed
	 *        
	 */
	public function getGroupPsDistricts($country_code = '') {

		$chois = array ();

		$query = $this->createQuery ( 'a' );

		$query->select ( 'a.id, a.name, p.id AS province_id, p.name AS province_name' );

		$query->leftJoin ( 'a.PsProvince p' );

		if ($country_code != '')
			$query->where ( 'p.country_code = ?', $country_code );

		$districts = $query->execute ();

		$count_district = count ( $districts );

		for($i = 0; $i < $count_district; $i ++) {
			for($j = 1; $j < $count_district; $j ++) {
				if ($districts [$i]->getProvinceId () == $districts [$j]->getProvinceId ()) {
					$chois [$districts [$i]->getProvinceName ()] [$districts [$i]->getId ()] = $districts [$i]->getName ();
				}
			}
		}

		return $chois;
	}
	
	/**
	 * FUNCTION: setSqlPsUserDistrictByUserId($province_id, $user_id)
	 *
	 
	 * @return string - sql
	 */
	public function setSqlPsUserDistrictByUserId($province_id, $user_id) {
		
		$query = $this->createQuery ('d')->select ( "d.id, CONCAT(d.s_code,'-',d.name) AS name" );
		
		//$query->leftJoin( 'd.PsUserDistricts ud ON d.id = ud.ps_province_id' );
		
		$query->leftJoin( 'd.PsUserDistricts ud With ud.user_id = ?', array($user_id) );
		
		$query->where ( 'd.ps_province_id = ?', $province_id );
		
		$query->orderBy ( 'd.iorder' );
		
		return $query;
	}
}