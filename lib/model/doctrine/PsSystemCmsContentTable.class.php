<?php

/**
 * PsSystemCmsContentTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsSystemCmsContentTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsSystemCmsContentTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsSystemCmsContent' );
	}

	/**
	 * FUNCTION: doSelectQuery(Doctrine_Query $query)
	 *
	 * @param
	 *        	Doctrine SQL
	 * @return string SQL
	 */
	public function doSelectQuery(Doctrine_Query $query) {

		$alias = $query->getRootAlias ();
		$query->addSelect ( $alias . '.title,' . $alias . '.is_activated,' . $alias . '.ps_system_cms_content_code, ' . $alias . '.updated_at,' . $alias . '.user_updated_id, CONCAT(u.first_name," ",u.last_name) AS updated_by' )
			->leftJoin ( $alias . '.UserUpdated u', true );

		return $query;
	}

	public function getSystemCmsContentById($system_content_id) {

		$query = $this->createQuery ( 's' );
		$query->select ( 's.id AS id,' . 's.ps_system_cms_content_code,' . 's.title AS title,' . 's.description AS description,' . 's.note AS note,' . 's.is_activated,' . 's.created_at AS created_at,' . 's.updated_at AS updated_at,' . 'CONCAT(uc.first_name, " ", uc.last_name) AS created_by,' . 'CONCAT(ud.first_name, " ", ud.last_name) AS updated_by' );

		$query->leftJoin ( 's.UserCreated AS uc' );
		$query->leftJoin ( 's.UserUpdated AS ud' );
		$query->addWhere ( 's.id = ?', $system_content_id );

		return $query->fetchOne ();
	}

	// Chuyen tat ca cac trang thai cua code ve ko hoat dong
	public function DeactivateByCode($system_content_code, $id) {

		$query = Doctrine::getTable ( 'PsSystemCmsContent' )->createQuery ()
			->update ()
			->set ( 'is_activated', 0 )
			->addWhere ( 'ps_system_cms_content_code = ?', $system_content_code )
			->addWhere ( 'id != ?', $id );

		return $query->execute ();
	}

	// set is_activate = 1 cho cms update gan nhat sau khi xoa
	public function ActivateByCode($system_content_code, $id) {

		$query = Doctrine::getTable ( 'PsSystemCmsContent' )->createQuery ()
			->select ( 'id' )
			->where ( 'ps_system_cms_content_code = ?', $system_content_code )
			->whereNotIn ( 'id', $id )
			->orderBy ( 'updated_at DESC' );

		$obj = $query->fetchOne ();

		$query1 = Doctrine::getTable ( 'PsSystemCmsContent' )->createQuery ()
			->update ()
			->set ( 'is_activated', 1 )
			->addWhere ( 'id = ?', $obj->getId () )
			->execute ();
	}
}