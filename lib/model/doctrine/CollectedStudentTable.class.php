<?php

/**
 * CollectedStudentTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class CollectedStudentTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object CollectedStudentTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'CollectedStudent' );
	}

	/**
	 * *
	 * Cac khoan da thu khac (Da thu bat thuong)
	 *
	 * @param int $student_id
	 * @param timestamp $date:
	 *        	Ngay nhap du lieu
	 */
	public function getCollectedByDate($student_id, $date) {

		$q = $this->createQuery ( 'c' )
			->select ( "cd.id AS collected_id, cd.title AS title,c.id,c.amount,c.note" )
			->innerJoin ( "c.Collected cd" )
			->where ( "DATE_FORMAT(c.collected_at,'%Y%m')=?", date ( "Ym", $date ) )
			->addWhere ( "c.student_id = ? ", $student_id );
		return $q->execute ();
	}

	/**
	 *
	 * @param
	 *        	$studetn_id
	 * @param
	 *        	$date
	 */
	public function getCollectedStudentTotalByDate($student_id, $date) {

		$q = $this->createQuery ( 'c' )
			->select ( "Sum(c.amount) As total" )
			->addWhere ( "DATE_FORMAT(c.collected_at,'%Y%m')=?", date ( "Ym", $date ) )
			->addWhere ( "c.student_id=?", $student_id )
			->groupBy ( "c.student_id, DATE_FORMAT(c.collected_at,'%Y%m')" );

		return $q->fetchOne ();
	}

	/**
	 * *
	 * Khoan da thu khac
	 *
	 * @param int $receivable_id
	 * @param int $student_id
	 * @param timestamp $date:
	 *        	Ngay nhap du lieu
	 */
	public function findOneOfStudent($receivable_student_id, $student_id, $date) {

		$q = $this->createQuery ( 'c' )
			->select ( "c.amount" )
			->leftJoin ( "c.Collected cd" )
			->where ( "DATE_FORMAT(c.collected_at,'%Y%m')=?", date ( "Ym", $date ) )
			->addWhere ( "c.receivable_student_id = ?", $receivable_student_id )
			->addWhere ( "c.student_id=?", $student_id );

		return $q->fetchOne ();
	}

	/**
	 * *
	 * Lay khoan da thu cua Phai thu bat thuong
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param int $receivable_student_id-
	 *        	id cua receivable_student
	 * @param int $student_id
	 * @param timestamp $date:
	 *        	Ngay nhap du lieu
	 */
	public function findOneOnlyOfStudent($receivable_student_id, $student_id, $date) {

		$q = $this->createQuery ( 'cs' )
			->select ( "cs.id, cs.student_id, cs.receivable_student_id, cs.amount, cs.note, cs.collected_at" )
			->addWhere ( "DATE_FORMAT(cs.collected_at,'%Y%m')=?", date ( "Ym", $date ) )
			->addWhere ( "cs.receivable_student_id = ? ", $receivable_student_id )
			->addWhere ( "cs.student_id = ? ", $student_id )
			->limit ( 1 );

		return $q->fetchOne ();
	}

	/**
	 * Lay tat ca cac phieu thu cua mot dich vu can phai thu cua mot thang nao do
	 */
	/**
	 * *
	 * Khoan da thu khac
	 *
	 * @param int $receivable_id
	 * @param int $student_id
	 * @param timestamp $date:
	 *        	Ngay nhap du lieu
	 */
	public function getAllReceiptOfCollectedStudent($collected_student_id) {

		$q = $this->createQuery ()
			->select ( "cr.id,r.id, r.file_name AS file_name,r.receipt_date AS receipt_date" )
			->from ( "Receipt r" )
			->innerJoin ( "r.CollectedReceipt cr" )
			->addWhere ( "cr.collected_student_id = ? ", $collected_student_id );

		return $q->execute ();
	}
}