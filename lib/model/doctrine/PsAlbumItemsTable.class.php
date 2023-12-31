<?php

/**
 * PsAlbumItemsTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsAlbumItemsTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsAlbumItemsTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsAlbumItems' );
	}

	public function doSelectQuery(Doctrine_Query $query) {

		$a = $query->getRootAlias ();

		$query->select ( $a . '.id AS id,' . $a . '.album_id AS album_id,' . $a . '.title AS title,' . $a . '.url_file AS url_file,' . $a . '.url_thumbnail AS url_thumbnail,' . $a . '.is_activated AS is_activated,' . $a . '.note AS note,' . $a . '.number_like AS number_like,' . $a . '.number_dislike AS number_dislike,' . $a . '.user_updated_id AS user_updated_id,' . $a . '.updated_at AS updated_at,' . 'CONCAT(u.first_name, " ", u.last_name) AS updated_by,' . 'CONCAT(uc.first_name, " ", uc.last_name) AS creator_by' );

		$query->leftJoin ( $a . '.UserCreated uc' );

		$query->leftJoin ( $a . '.UserUpdated u' );

		// Neu khong la admin, chi hien bai viet da active
		// if (! myUser::isAdministrator()) {
		// $query->addWhere($a. '.is_activated = ?', PreSchool::ACTIVE);
		// }
		if (! myUser::credentialPsCustomers ( 'PS_CMS_ALBUMS_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {

			$query->addWhere ( $a . '.ps_customer_id = 0 or ' . $a . '.ps_customer_id = ?', myUser::getPscustomerID () );

			$query->addWhere ( $a . '.is_activated = ?', PreSchool::ACTIVE );
		}

		// $query->groupBy($a. '.ps_service_course_schedule_id');

		$query->addOrderBy ( 'created_at DESC' );

		return $query;
	}

	/**
	 * Lay items list theo album_id
	 *
	 * @param
	 *        	$class_id
	 * @return Object
	 */
	public function getItemsByAlbumId($album_id) {

		// echo $album_id; die();
		$query = $this->createQuery ( 'a' );

		$query->select ( 'a.id AS id,' . 'a.album_id AS album_id,' . 'a.title AS title,' . 'a.url_file AS url_file,' . 'a.url_thumbnail AS url_thumbnail,' . 'a.is_activated AS is_activated,' . 'a.note AS note,' . 'a.number_like AS number_like,' . 'a.number_dislike AS number_dislike,' . 'a.user_updated_id AS user_updated_id,' . 'a.updated_at AS updated_at,' . 'CONCAT(u.first_name, " ", u.last_name) AS updated_by,' . 'CONCAT(uc.first_name, " ", uc.last_name) AS creator_by' );

		$query->leftJoin ( 'a.UserCreated uc' );

		$query->leftJoin ( 'a.UserUpdated u' );

		$query->addWhere ( 'a.album_id = ?', $album_id );

		return $query->execute ();
	}
	
	/**
	 * Lay so luong anh duoc them trong ngay
	 *
	 * @param
	 *        	$ps_workplace_id, $date_at
	 * @return Object
	 */
	public function getNumberAlbumItems($ps_workplace_id,$date_at,$option = null,$class_id = null) {
		
		$date_at = $date_at ? date('Ymd',strtotime($date_at)) : date('Ymd');
		
		$date_at2 = $date_at ? date('Ym',strtotime($date_at)) : date('Ym');
		
		$query = $this->createQuery ( 'a' );
		
		$query->select ( 'a.id AS id, mc.id AS ps_class_id, a.created_at AS created_at, ab.id AS album_id' );
		
		$query->innerJoin( 'a.PsAlbums ab' );
		
		$query->innerJoin( 'ab.MyClass mc' );
		
		$query->addWhere ( 'mc.ps_workplace_id =?', $ps_workplace_id );
		
		if($option == 1){
			$query->andWhere ( 'DATE_FORMAT(a.created_at,"%Y%m%d") = ?', $date_at );
		}else{
			$query->andWhere ( 'DATE_FORMAT(a.created_at,"%Y%m") = ?', $date_at2 );
		}
		
		if($class_id > 0){
			$query->addWhere ( 'mc.id =?', $class_id );
		}
		
		return $query->execute();
	}

}