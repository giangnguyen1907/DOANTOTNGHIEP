<?php

/**
 * PsAdvicesTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsAdvicesTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsAdvicesTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsAdvices' );
	}

	public function doSelectQuery(Doctrine_Query $query) {
		
		$a = $query->getRootAlias ();
		
		$query->select ( $a . '.id AS id,' . $a . '.student_id AS student_id,' . $a . '.user_id AS user_id,' . $a . '.category_id AS category_id,' . $a . '.title AS title,' . $a . '.content AS content,' . $a . '.is_activated AS is_activated,' . 'ac.title AS ac_title,' . $a . '.user_created_id AS user_created_id,' . $a . '.created_at AS created_at,' . $a . '.updated_at AS updated_at,' . $a . '.date_at AS date_at,' . 'CONCAT(s.first_name, " ", s.last_name) AS student_name,' . 'CONCAT(ui.first_name, " ", ui.last_name) AS teacher_receive,' . 'CONCAT(uc.first_name, " ", uc.last_name) AS creator_by,'  . 'af.content AS feedback_content,'
				.'rl.id as rl_id,' .'CONCAT(rl.first_name, " ", rl.last_name) AS relative_name,'
				);
		
		$query->innerJoin ( $a . '.PsStudent s' );
		
		$query->leftJoin ( $a . '.PsAdviceCategories ac' );
		
		$query->leftJoin ( $a . '.Relative rl' );
		
		$query->leftJoin ( $a . '.UserCreated uc' );
		
		$query->leftJoin ( $a . '.UserId ui' );
		
		$query->leftJoin ( $a . '.PsAdviceFeedbacks af WITH (af.advice_id =' . $a . '.id AND af.is_teacher=? ) ', 1 );
		
		// $query->addWhere('ac.is_activated = ? ', PreSchool::ACTIVE);
		
		$date = date ( "Ymd" );
		
		$query->leftJoin ( 's.StudentClass sc With (DATE_FORMAT(sc.start_at,"%Y%m%d") <= ?  AND (sc.stop_at IS NULL OR  DATE_FORMAT(sc.stop_at,"%Y%m%d") >= ?))', array (
				$date,
				$date ) );
		
		$query->whereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );
		
		$query->innerJoin ( 'sc.MyClass mc' );
		
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_RELATIVE_ADVICE_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			
			$query->addWhere ( 's.ps_customer_id = ?', myUser::getPscustomerID () );
			
			$query->addWhere ( $a . '.is_activated = ?', PreSchool::ACTIVE );
		}
		
		$query->orderBy ( $a . '.created_at DESC' );
		
		return $query;
	}

	/**
	 * Lay thong tin bang PsAdvice qua id
	 */
	public function getAdviceById($id) {

		$query = $this->createQuery ( 'a' );

		$query->select ( 'a.id AS id,' . 'a.student_id AS student_id,' . 'a.user_id AS user_id,' . 'a.category_id AS category_id,' . 'a.title AS title,' . 'a.content AS content,' . 'a.is_activated AS is_activated,' . 'ac.title AS ac_title,' . 'a.user_created_id AS user_created_id,' . 'a.created_at AS created_at,' . 'a.updated_at AS updated_at,' . 'a.date_at AS date_at,' . 's.student_code AS student_code,' . 'CONCAT(s.first_name, " ", s.last_name) AS student_name,' . 'CONCAT(uc.first_name, " ", uc.last_name) AS creator_by,' . 'af.content AS feedback_content,' );

		$query->innerJoin ( 'a.PsStudent s' );

		$query->leftJoin ( 'a.PsAdviceCategories ac' );

		$query->leftJoin ( 'a.UserCreated uc' );

		$query->leftJoin ( 'a.PsAdviceFeedbacks af WITH (af.advice_id = a.id AND af.is_teacher=? ) ', 1 );

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_RELATIVE_ADVICE_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {

			$query->addWhere ( 'a.ps_customer_id = ?', myUser::getPscustomerID () );

			$query->addWhere ( 'a.is_activated = ?', PreSchool::ACTIVE );
		}

		$query->leftJoin ( 'a.UserId ui' );

		$query->addWhere ( 'a.id =?', $id );

		return $query->fetchOne ();
	}
}