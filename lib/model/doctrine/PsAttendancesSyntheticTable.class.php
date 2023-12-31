<?php

/**
 * PsAttendancesSyntheticTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsAttendancesSyntheticTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsAttendancesSyntheticTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsAttendancesSynthetic' );
	}

	public function getAttendanceSyntheticByDate($class_id, $date) {

		$query = $this->createQuery ( 'at' );
		$query->select ( 'at.id, at.ps_class_id,at.login_sum,at.logout_sum' );
		$query->addWhere ( 'at.ps_class_id =?', $class_id );
		$query->andWhere ( ' DATE_FORMAT(at.tracked_at,"%Y%m%d") = ?', date ( "Ymd", strtotime ( $date ) ) );
		return $query->fetchOne ();
	}

	public function getAttendanceSyntheticByMonth($class_id, $date_at) {

		$query = $this->createQuery ( 'at' );
		$query->select ( 'at.id, at.ps_class_id,at.login_sum,at.logout_sum,at.tracked_at' );
		$query->addWhere ( 'at.ps_class_id =?', $class_id );
		$query->andWhere ( ' DATE_FORMAT(at.tracked_at,"%Y%m") = ?', date ( "Ym", strtotime ( $date_at ) ) );
		return $query->execute ();
	}

	public function getAttendanceSyntheticByMonthOfCustomer($ps_customer_id, $date_at) {

		$query = $this->createQuery ( 'at' );
		$query->select ( 'at.id, at.ps_class_id,at.login_sum,at.logout_sum,at.tracked_at' );
		$query->addWhere ( 'at.ps_customer_id =?', $ps_customer_id );
		$query->andWhere ( ' DATE_FORMAT(at.tracked_at,"%Y%m") = ?', date ( "Ym", strtotime ( $date_at ) ) );
		return $query->execute ();
	}

	public function getAttendanceSyntheticByDayOfCustomer($ps_customer_id, $date_at) {

		$query = $this->createQuery ( 'at' );
		$query->select ( 'at.id, at.ps_class_id,at.login_sum,at.logout_sum,at.tracked_at' );
		$query->addWhere ( 'at.ps_customer_id =?', $ps_customer_id );
		$query->andWhere ( ' DATE_FORMAT(at.tracked_at,"%Y%m%d") = ?', date ( "Ymd", strtotime ( $date_at ) ) );
		return $query->execute ();
	}
}