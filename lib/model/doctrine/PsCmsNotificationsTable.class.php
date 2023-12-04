<?php

/**
 * PsCmsNotificationsTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsCmsNotificationsTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsCmsNotificationsTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsCmsNotifications' );
	}

	public function doSelectQuery(Doctrine_Query $query) {

		$a = $query->getRootAlias ();

		$query->select ( $a . '.id AS id, ' . $a . '.title AS title, ' . $a . '.description AS description, ' . $a . '.private_key AS private_key, ' . 'RN.is_read as is_read, RN.user_id as user_id, RN.is_delete as is_delete,' . $a . '.is_system AS is_system, ' . $a . '.is_all AS is_all, ' . $a . '.is_status AS is_status, ' . $a . '.date_at AS date_at, ' . $a . '.total_object_received AS total_object_received, ' . $a . '.text_object_received AS text_object_received, ' . $a . '.updated_at AS updated_at,' . 'CONCAT(u.first_name, " ", u.last_name) AS created_by' );
		$query->innerJoin ( $a . '.UserCreated u' );
		$query->innerJoin ( $a . '.PsCmsReceivedNotification RN' );
		$query->orderBy ( $a . '.date_at DESC' );

		return $query;
	}

	public function doSelectQuery2(Doctrine_Query $query) {

		$a = $query->getRootAlias ();

		$query->select ( $a . '.id AS id, ' . $a . '.title AS title, ' . $a . '.description AS description, ' . $a . '.private_key AS private_key, ' . 'RN.is_read as is_read, RN.user_id as user_id, RN.is_delete as is_delete,' . $a . '.is_system AS is_system, ' . $a . '.is_all AS is_all, ' . $a . '.is_status AS is_status, ' . $a . '.date_at AS date_at, ' . $a . '.total_object_received AS total_object_received, ' . $a . '.text_object_received AS text_object_received, ' . $a . '.updated_at AS updated_at,' . 'CONCAT(u.first_name, " ", u.last_name) AS created_by' );
		$query->innerJoin ( $a . '.UserCreated u' );
		$query->innerJoin ( $a . '.PsCmsReceivedNotification RN' );
		$query->orderBy ( $a . '.date_at DESC' );

		return $query;
	}

	/**
	 * Lay thong bao theo id.
	 *
	 * @return object PsCmsNotificationsTable
	 */
	public function getNotificationById($notification_id) {

		$query = $this->createQuery ( 'm' );

		$query->select ( 'm.id AS id, ' . 'm.title AS title, ' . 'm.description AS description, ' . 'm.private_key AS private_key, ' . 'RN.is_read, RN.user_id as user_id, RN.is_delete as is_delete,' . 'm.is_system AS is_system, ' . 'm.is_status AS is_status, ' . 'm.date_at AS date_at, ' . 'm.total_object_received AS total_object_received, ' . 'm.text_object_received AS text_object_received, ' . 'm.updated_at AS updated_at,' . 'CONCAT(u.first_name, " ", u.last_name) AS created_by' );
		$query->leftJoin ( 'm.UserCreated u' );
		$query->leftJoin ( 'm.PsCmsReceivedNotification RN' );
		$query->addWhere ( 'm.id = ?', $notification_id );
		return $query->fetchOne ();
	}

	/**
	 * Ham phuc vu cho viec updated ps_customer_id trong thong bao
	 */
	public function getAllUserId() {

		$query = $this->createQuery ( 'm' );

		$query->select ( 'm.user_created_id AS user_created_id' );

		return $query->execute ();
	}

	/**
	 * dem thu chua doc.
	 *
	 * @return object PsCmsNotificationsTable
	 */
	public function getNoReadNotification($user_id) {

		$query = $this->createQuery ( 'm' );

		$query->select ( 'm.id AS id,' );
		$query->leftJoin ( 'm.PsCmsReceivedNotification RN' );
		$query->addWhere ( 'm.user_created_id != ?', $user_id );
		$query->addWhere ( 'RN.is_read = ?', 0 );
		$query->addWhere ( 'RN.is_delete = ?', 0 );
		$query->addWhere ( 'RN.user_id = ?', $user_id );
		return count ( $query->execute () );
	}
	
	/**
	 *  Dem tat ca thong bao duoc tao trong truong cua ngay
	 *  
	 *  $ps_customer_id, $date_at
	 * 
	 **/
	public function getNumberNotification($ps_customer_id, $date_at, $option = null,$class_id = null) {
		
		$date_at = $date_at ? date('Ymd',strtotime($date_at)) : date('Ymd');
		
		$date_at2 = $date_at ? date('Ym',strtotime($date_at)) : date('Ym');
		
		$query = $this->createQuery ( 'm' );
		
		$query->select ( 'm.id AS id, m.updated_at AS updated_at, ac.ps_class_id AS ps_class_id' );
		
		$query->leftJoin('m.PsCmsNotificationsClass ac');
		
		$query->addWhere ( 'm.ps_customer_id = ?', $ps_customer_id );
		
		if($option == 1){
			$query->andWhere ( 'DATE_FORMAT(m.created_at,"%Y%m%d") = ?', $date_at );
		}else{
			$query->andWhere ( 'DATE_FORMAT(m.created_at,"%Y%m") = ?', $date_at2 );
		}
		
		if($class_id > 0){
			$query->addWhere ( 'ac.id IS NULL OR ac.ps_class_id = ?', $class_id );
		}
		
		return $query->execute ();
	}
	
}