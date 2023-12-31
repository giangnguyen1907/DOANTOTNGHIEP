<?php

/**
 * PsEvaluateIndexCriteriaTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsEvaluateIndexCriteriaTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsEvaluateIndexCriteriaTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsEvaluateIndexCriteria' );
	}

	public function doSelectQuery(Doctrine_Query $query) {

		$alias = $query->getRootAlias ();
		$query->addSelect ( $alias . '.id AS id,' . $alias . '.title AS title,' . $alias . '.criteria_code AS criteria_code,' . $alias . '.is_activated AS is_activated,' . $alias . '.iorder AS iorder,' . $alias . '.updated_at,' );

		$query->addSelect ( 'cus.id AS ps_customer_id, cus.title AS school_name' );

		$query->addSelect ( 'wp.id AS ps_workplace_id, wp.title AS wp_name' );

		$query->addSelect ( 'CONCAT(u.first_name, " ", u.last_name) AS updated_by' );

		$query->addSelect ( 'es.id AS evaluate_subject_id, CONCAT(es.subject_code, ": ",es.title) AS subject_title' );

		$query->leftJoin ( $alias . '.PsEvaluateSubject es' );

		$query->leftJoin ( 'es.PsCustomer cus' );

		$query->leftJoin ( 'es.PsWorkPlaces wp' );

		$query->leftJoin ( $alias . '.UserUpdated u' );

		if (! myUser::credentialPsCustomers ( 'PS_EVALUATE_INDEX_CRITERIA_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			$query->addWhere ( 'es.ps_customer_id = ?', myUser::getPscustomerID () );
		}

		$query->addOrderBy ( $alias . '.iorder asc' );

		return $query;
	}

	/**
	 * Lay ra chi so danh gia va chu de danh gia
	 *
	 * @param int $criteria_id
	 */
	public function getSubjectById($criteria_id) {

		$q = $this->createQuery ( 'c' );

		// $q->andWhere('c.is_activated=?', PreSchool::ACTIVE);

		$q->select ( 'c.id AS id, c.criteria_code AS criteria_code, c.title AS criteria_title' );

		$q->addSelect ( 's.id AS subject_id, s.subject_code AS subject_code, s.title AS subject_title, s.ps_customer_id AS ps_customer_id, s.ps_workplace_id AS ps_workplace_id, s.school_year_id AS school_year_id' );

		// $q->andWhere('s.is_activated=?', PreSchool::ACTIVE);

		$q->leftJoin ( 'c.PsEvaluateSubject s ' );

		$q->andWhere ( 'c.id =? ', $criteria_id );

		$q->groupBy ( 's.id, c.id' );

		$q->addOrderBy ( 's.iorder asc, c.iorder asc' );

		return $q->fetchOne ();
	}

	/**
	 * Lay ra chi so danh gia va chu de danh gia
	 *
	 * @param unknown $subject_id
	 */
	public function getCriteriaBySubject($subject_id) {

		$q = $this->createQuery ( 'c' );

		$q->andWhere ( 'c.is_activated=?', PreSchool::ACTIVE );

		$q->select ( 'c.id AS id, c.criteria_code AS criteria_code, c.title AS criteria_title' );

		$q->addSelect ( 's.id AS subject_id, s.subject_code AS subject_code, s.title AS subject_title' );

		$q->andWhere ( 's.is_activated=?', PreSchool::ACTIVE );

		$q->leftJoin ( 'c.PsEvaluateSubject s ' );

		if (is_array ( $subject_id )) {

			$q->andWhereIn ( 's.id', $subject_id );
		} else {

			$q->andWhere ( 's.id =? ', $subject_id );
		}

		$q->groupBy ( 's.id, c.id' );

		$q->addOrderBy ( 's.iorder asc, c.iorder asc' );

		return $q->execute ();
	}

	/**
	 * Kiem tra ton tai criteria_code
	 *
	 * @return boolean - true: Da ton tai, khong the su dung
	 */
	public function checkCriteriaCodeExits($param) {

		$q = $this->createQuery ()
			->select ( 'id' );

		if (isset ( $param ['criteria_code'] ) && strlen ( $param ['criteria_code'] ) > 0) {
			$q->where ( 'criteria_code = ?', $param ['criteria_code'] );
		}

		if (isset ( $param ['evaluate_subject_id'] ) && $param ['evaluate_subject_id'] > 0) {
			$q->andWhere ( 'evaluate_subject_id = ?', $param ['evaluate_subject_id'] );
		}

		$q->where ( 'is_activated = ?', $param ['is_activated'] );

		// $q->leftJoin('PsEvaluateSubject s');

		// $q->andWhere('s.ps_customer_id =?', $customer_id);

		$ps_code = $q->fetchOne ();

		if ($ps_code) {
			return true;
		} else {
			return false;
		}
	}

	public function getSchoolYearIdById($criteria_id) {

		// Lay thong tin cua subject
		$q = Doctrine_Query::create ()->from ( 'PsEvaluateIndexCriteria ec' )
			->select ( "ec.id AS id, es.id AS subject_id, es.school_year_id AS school_year_id" )
			->where ( 'ec.is_activated = ?', PreSchool::ACTIVE )
			->innerJoin ( 'ec.PsEvaluateSubject es WITH es.is_activated = ?', PreSchool::ACTIVE )
			->andWhere ( 'ec.id = ? ', $criteria_id )
			->fetchOne ();
		if ($q && $q->getSchoolYearId () > 0) {

			return Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $q->getSchoolYearId () );
		} else {

			return Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ();
		}
	}

	/**
	 * Kiem tra du lieu bang lien quan truoc khi xoa
	 *
	 * @param int $criteria_id
	 */
	public function checkForeignDataExit($criteria_id) {

		$q = $this->createQuery ( 'c' )
			->select ( 'c.id, ct.id, s.id' );

		if (is_array ( $criteria_id )) {

			$q->andWhereIn ( 'c.id', $criteria_id );
		} else {

			$q->where ( 'c.id =?', $criteria_id );
		}

		$q->innerJoin ( 'c.PsEvaluateClassTime ct' );

		$q->innerJoin ( 'c.PsEvaluateIndexStudent s' );

		return $q->fetchArray ();
	}
	
	public function getPsEvaluateIndexCriteria($ps_customer_id, $ps_workplace_id, $ps_school_year_id) {
		$q = $this->createQuery ( 'c' );
		$q -> select('c.id as id, c.title as title, c.criteria_code as criteria_code, es.id as es_id');
		$q -> innerJoin('c.PsEvaluateSubject es');
		
		$q -> addWhere('c.is_activated =?',PreSchool::ACTIVE);
		$q -> andWhere('es.ps_customer_id =?', $ps_customer_id);
		$q -> andWhere('es.ps_workplace_id =?', $ps_workplace_id);
		$q -> andWhere('es.school_year_id =?', $ps_school_year_id);
		
		return $q-> execute();
	}
	
}