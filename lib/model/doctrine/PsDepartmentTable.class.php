<?php

/**
 * PsDepartmentTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsDepartmentTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsDepartmentTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsDepartment' );
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

		$query->select ( $a . '.id AS id, ' . $a . '.title AS title, ' . $a . '.description AS description, ' . $a . '.iorder AS iorder, ' . $a . '.is_activated AS is_activated, ' . $a . '.updated_at AS updated_at,' . $a . '.ps_customer_id AS ps_customer_id, ' . $a . '.ps_workplace_id AS ps_workplace_id, ' . 'cus.title AS customer_title, ' . 'CONCAT(u.first_name, " ", u.last_name) AS updated_by' );

		$query->leftJoin ( $a . '.PsCustomer cus' );
		$query->leftJoin ( $a . '.UserUpdated u' );

		// $query->where($a .'.ps_customer_id IS NULL');// Lay danh sach chung

		if (! myUser::credentialPsCustomers ( 'PS_HR_DEPARTMENT_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0)
			$query->orWhere ( $a . '.ps_customer_id = ?', ( int ) myUser::getPscustomerID () );
		else
			$query->orWhere ( '1=1' );

		return $query;
	}

	/**
	 * Set SQL loc ra phong ban theo customer id
	 */
	public function setDepartmentByPsCustomer($customer_id) {

		$query = $this->createQuery ( 'd' );

		$query->select ( 'd.id , ' . 'd.title AS name,' );

		// $query->innerJoin('d.PsCustomer cus');

		$query->andWhere ( 'd.is_activated = ?', PreSchool::ACTIVE );

		if ($customer_id > 0) {
			$query->andWhere ( 'd.ps_customer_id IS NULL OR d.ps_customer_id = ?', $customer_id );
		}

		if (! isset ( $customer_id )) {
			$query->andWhere ( 'd.ps_customer_id IS NULL' );
		}

		$query->orderBy ( 'iorder DESC, title ASC' );
		return $query;
	}

	/**
	 * Set SQL loc ra phong ban cua 1 truong theo params
	 */
	public function setDepartmentByParams($params) {

		$query = $this->createQuery ( 'd' );

		$query->select ( 'd.id AS id , d.title AS name, d.ps_workplace_id AS ps_workplace_id' );

		if (isset ( $params ['ps_customer_id'] ) && ($params ['ps_customer_id'] > 0)) {

			// $query->andWhere('d.ps_customer_id IS NULL OR d.ps_customer_id = ?', $params['ps_customer_id']);
			$query->andWhere ( 'd.ps_customer_id = ?', $params ['ps_customer_id'] );
		}

		if (isset ( $params ['ps_workplace_id'] ) && $params ['ps_workplace_id'] > 0) {

			$query->andWhere ( 'd.ps_workplace_id IS NULL OR d.ps_workplace_id = ?', $params ['ps_workplace_id'] );
		}

		if (isset ( $params ['ps_department_id'] )) {

			if (is_array ( $params ['ps_department_id'] ))

				$query->andWhereIn ( 'd.id', $params ['ps_department_id'] );

			else

				$query->andWhere ( 'd.id = ?', $params ['ps_department_id'] );
		}

		if (isset ( $params ['is_activated'] )) {

			$query->andWhere ( 'd.is_activated = ?', $params ['is_activated'] );
		} else {

			$query->andWhere ( 'd.is_activated = ?', PreSchool::ACTIVE );
		}

		$query->orderBy ( 'iorder ASC, title ASC' );

		return $query;
	}

	/**
	 * Lay group danh sach phong ban cua truong*
	 */
	public function getChoisGroupDepartmentByCustomer($ps_customer_id, $ps_department_id = null, $is_activated = NULL) {

		$chois = array ();

		// Lay thong tin cua mot truong
		$ps_customer = Doctrine_Query::create ()->from ( 'PsCustomer' )
			->select ( "id AS id, school_name AS title" )
			->where ( 'is_activated = ?', PreSchool::ACTIVE )
			->andWhere ( 'id = ? ', $ps_customer_id )
			->fetchOne ();

		// Lay thong tin cua co so
		$ps_work_places = Doctrine_Query::create ()->from ( 'PsWorkPlaces' )
			->select ( "id AS id, title AS title" )
			->where ( 'is_activated = ?', PreSchool::ACTIVE )
			->andWhere ( 'ps_customer_id =? ', $ps_customer_id )
			->orderBy ( 'iorder' )
			->execute ();

		// Lay tat ca phòng ban đang hoat động của trường
		$params = array (
				'ps_customer_id' => $ps_customer_id,
				'is_activated' => $is_activated );

		$list_department = $this->setDepartmentByParams ( $params )
			->execute ();

		if (! $ps_work_places) {

			foreach ( $list_department as $department ) {
				if ($department->getPsWorkplaceId () <= 0) {
					$chois [$ps_customer->getTitle ()] [$department->getId ()] = $department->getTitle ();
				}
			}
		} else {
			foreach ( $ps_work_places as $ps_work_place ) {
				foreach ( $list_department as $department ) {
					if ($department->getPsWorkplaceId () <= 0) {
						$chois [$ps_customer->getTitle ()] [$department->getId ()] = $department->getTitle ();
					} elseif ($ps_work_place->getId () == $department->getPsWorkplaceId ()) {
						$chois [$ps_work_place->getTitle ()] [$department->getId ()] = $department->getTitle ();
					}
				}
			}
		}

		return $chois;
	}

	// lay ra phong ban thuoc so so hoac cua truong (load ajax)
	public function setDepartmentByWorkplaceId($ps_workplace_id, $ps_customer_id) {

		// $date = date('Ymd');
		$query = $this->createQuery ( 'a' )
			->select ( 'a.id as id, a.title as title, a.ps_workplace_id as ps_workplace_id. a.ps_customer_id as ps_customer_id' );
		if ($ps_workplace_id > 0) {
			$query->addWhere ( 'a.ps_workplace_id = ?', $ps_workplace_id );
		} else {
			$query->andWhere ( 'a.ps_customer_id = ?', $ps_customer_id );
		}
		$query->orderBy ( 'a.iorder DESC' );

		return $query;
	}

	// lay ra phong ban thuoc so so hoac cua truong (select box)
	public function getDepartmentByWorkplaceId($ps_workplace_id, $ps_customer_id) {

		$query = $this->createQuery ( 'a' )
			->select ( 'a.id as id, a.title as title, a.ps_workplace_id as ps_workplace_id. a.ps_customer_id as ps_customer_id' );
		if ($ps_workplace_id > 0) {
			$query->addWhere ( 'a.ps_workplace_id = ?', $ps_workplace_id );
		} else {
			$query->addWhere ( 'a.ps_customer_id =?', $ps_customer_id );
		}

		$query->orderBy ( 'a.iorder DESC' );

		return $query->execute ();
	}
}