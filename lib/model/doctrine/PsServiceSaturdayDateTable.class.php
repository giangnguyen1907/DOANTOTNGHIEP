<?php

/**
 * PsServiceSaturdayDateTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsServiceSaturdayDateTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsServiceSaturdayDateTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsServiceSaturdayDate' );
	}

	// Chuyen tat ca cac trang thai cua code ve ko hoat dong
	public function getListByPsServiceSaturdayId($ps_service_saturday_id) {

		$query = $this->createQuery ( 's' )
			->select ( 's.service_date AS service_date' )
			->where ( 's.ps_service_saturday_id = ?', $ps_service_saturday_id );

		return $query->execute ();
	}

	public function findByStudentId($student_id) {

		$query = $this->createQuery ( 's' )
			->select ( 's.service_date AS service_date' )
			->where ( 's.student_id = ?', $student_id );

		return $query->execute ();
	}
}