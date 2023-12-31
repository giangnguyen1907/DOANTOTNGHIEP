<?php

/**
 * PsHistoryImportTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsHistoryImportTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsHistoryImportTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsHistoryImport' );
	}

	/**
	 * Hien thi lich su import
	 *
	 * @author Phung Van Thanh
	 */
	public function getHistoryImportBySchool($ps_customer_id, $ps_workplace_id, $date_at_from = null, $date_at_to = null) {

		// echo $student_id; die();
		$q = $this->createQuery ( 'hip' )
			->
		select ( 'hip.id, hip.file_name, hip.file_link,hip.file_classify, hip.created_at' );

		$q->addSelect ( 'CONCAT(u.first_name, " ", u.last_name) AS created_by' )
			->
		innerJoin ( 'hip.UserCreated u' );

		if ($ps_workplace_id > 0) {
			$q->addWhere ( 'hip.ps_workplace_id =?', $ps_workplace_id );
		} else {
			$q->addWhere ( 'hip.ps_customer_id =?', $ps_customer_id );
		}

		if ($date_at_from != '')
			$q->andWhere ( ' DATE_FORMAT(hip.created_at,"%Y%m%d") >= ?', date ( "Ymd", strtotime ( $date_at_from ) ) );

		if ($date_at_to != '')
			$q->andWhere ( ' DATE_FORMAT(hip.created_at,"%Y%m%d") <= ?', date ( "Ymd", strtotime ( $date_at_to ) ) );

		$q->orderBy ( 'hip.created_at desc' );

		$q->limit(100);
		
		return $q->execute ();
	}
}