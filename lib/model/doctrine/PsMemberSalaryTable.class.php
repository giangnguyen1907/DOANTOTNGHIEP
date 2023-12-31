<?php

/**
 * PsMemberSalaryTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsMemberSalaryTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsMemberSalaryTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsMemberSalary' );
	}

	/**
	 * Lay ra danh sach luong cua nhan vien
	 */
	public function getMemberSalaryByMemberId($member_id, $track_at = null) {

		$track_at = $track_at ? $track_at : '';

		$query = $this->createQuery ( 'a' );
		$query->select ( 'a.id AS id,' . 'a.start_at AS start_at,' . 'a.stop_at AS stop_at,' . 'a.days_working AS days_working,' . 'a.note AS note,' . 'CONCAT(uc.first_name, " ", uc.last_name) AS creator_by, ' . 'CONCAT(ud.first_name, " ", ud.last_name) AS updated_by, ' . 'CONCAT(mb.first_name, " ", mb.last_name) AS member_name, ' . 's.basic_salary AS basic_salary, ' . 'cus.school_name AS school_name,' . 'a.updated_at AS updated_at,' );
		$query->leftJoin ( 'a.PsMember mb' );

		$query->leftJoin ( 'a.PsSalary s' );

		$query->leftJoin ( 's.PsCustomer cus' );

		$query->leftJoin ( 'a.UserCreated uc' );

		$query->leftJoin ( 'a.UserUpdated ud' );

		if ($track_at != '') {

			$query->addWhere ( 'DATE_FORMAT(a.start_at,"%Y%m%d") <= ?', date ( 'Ymd', strtotime ( $track_at ) ) );
			$query->addWhere ( 'DATE_FORMAT(a.stop_at,"%Y%m%d") >=?', date ( 'Ymd', strtotime ( $track_at ) ) );
		}

		$query->addWhere ( 'a.ps_member_id=?', $member_id );

		$query->addWhere ( 's.is_activated=?', PreSchool::ACTIVE );

		$query->orderBy ( 'a.created_at DESC' );

		return $query->execute ();
	}

	/**
	 * Lay ra luong hien tai cua nhan vien
	 */
	public function getCurrentMemberSalaryByMemberId($member_id, $track_at = null) {

		$track_at = $track_at ? $track_at : '';

		$query = $this->createQuery ( 'a' );

		$query->select ( 'a.id AS id,' . 's.id AS salary_id,' . 'SUM(basic_salary) AS basic_salary, ' . '' );

		$query->leftJoin ( 'a.PsSalary s' );

		$query->andWhere ( 'a.ps_member_id =?', $member_id );

		$query->andWhere ( 'DATE_FORMAT(a.start_at,"%Y%m%d") <= ? AND DATE_FORMAT(a.stop_at,"%Y%m%d") >=? AND s.is_activated =?', array (
				date ( 'Ymd', strtotime ( $track_at ) ),
				date ( 'Ymd', strtotime ( $track_at ) ),
				PreSchool::ACTIVE ) );

		// $query->groupBy('a.start_at DESC');
		// echo $query;die;
		return $query->fetchOne ();
	}

	/**
	 * Lay ra chi tiet/danhsach luong cua nhan vien
	 */
	// public function getMemberSalaryBySalaryId($salary_id)
	// {

	// $query = $this->createQuery('a');
	// $query->select(
	// 'a.id AS id,' .
	// 'a.start_at AS start_at,' .
	// 'a.stop_at AS stop_at,' .
	// 'a.days_working AS days_working,' .
	// 'a.note AS note,' .
	// 'CONCAT(uc.first_name, " ", uc.last_name) AS creator_by, ' .
	// 'CONCAT(ud.first_name, " ", ud.last_name) AS updated_by, ' .
	// 'CONCAT(mb.first_name, " ", mb.last_name) AS member_name, ' .
	// 's.basic_salary AS basic_salary, ' .
	// 'cus.school_name AS school_name,' .
	// 'a.updated_at AS updated_at,'
	// );
	// $query->leftJoin('a.PsMember mb');

	// $query->leftJoin('a.PsSalary s');

	// $query->leftJoin('s.PsCustomer cus');

	// $query->leftJoin('a.UserCreated uc');

	// $query->leftJoin('a.UserUpdated ud');

	// if(isset($salary_id)) {

	// if(is_array($salary_id) > 0)

	// $query->whereIn('a.ps_salary_id',$salary_id);

	// else

	// $query->andWhere('a.ps_salary_id=?', $salary_id);
	// }

	// $query->orderBy('a.created_at DESC');

	// return $query->execute();
	// }

	// Ham ton tai rang buoc giua MemberSalary va Salary
	public function checkMemberSalaryExits($salary_id, $objid = null) {

		$query = $this->createQuery ()
			->select ( 'id' );

		if (isset ( $salary_id )) {

			if (is_array ( $salary_id ) > 0)

				$query->whereIn ( 'ps_salary_id', $salary_id );

			else

				$query->andWhere ( 'ps_salary_id=?', $salary_id );
		}

		if ($objid > 0)
			$query->andwhere ( 'id <> ?', $objid );

		$records = $query->execute ();

		return count ( $records ) ? true : false;
	}
}