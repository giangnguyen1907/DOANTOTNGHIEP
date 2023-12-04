<?php

/**
 * PsAppPermissionTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsAppPermissionTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsAppPermissionTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsAppPermission' );
	}

	/**
	 * FUNCTION: doSelectQuery(Doctrine_Query $query)
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param
	 *        	Doctrine SQL
	 * @return string SQL
	 *        
	 */
	public function doSelectQuery(Doctrine_Query $query) {

		/*
		 * $query = Doctrine::getTable('PsApp')->createQuery('s')
		 * ->select('s.*,ape.*')
		 * ->leftJoin('s.PsApps ape')
		 * ->addOrderBy('s.app_code asc');
		 * return $query;
		 */
		// echo $query;die;
		$query = Doctrine::getTable ( 'PsApp' )->setSQLPsApp ( null, null );

		return $query;
	}

	/**
	 * FUNCTION: doBuildQuery(Doctrine_Query $query)
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param
	 *        	Doctrine SQL
	 * @return string SQL
	 *        
	 */
	public function doBuildQuery(Doctrine_Query $query) {

		$alias = $query->getRootAlias ();

		/*
		 * $query->addSelect($alias.'.id AS app_id', $alias.'.title AS app_title', $alias.'.app_code','ap.*')
		 * ->leftJoin($alias.'.PsApp ap')
		 * ->addWhere('ap.ps_app_root IS NOT NUll')
		 * ->addOrderBy('ap.app_code',$alias.'.iorder asc');
		 */

		$alias = $query->getRootAlias ();
		$query = Doctrine::getTable ( 'PsApp' )->createQuery ( $alias )
			->select ( 'pe.id,pe.title,pe.app_permission_code,pe.ps_app_id' )
			->leftJoin ( $alias . '.PsApps pe' );

		// ->addWhere($alias.'.ps_app_root IS NOT NUll')
		// ->addOrderBy($alias.'.app_code asc');*/
		// 'SELECT p.id AS p__id, p.title AS p__title, p.ps_app_id AS p__ps_app_id, p.app_permission_code AS p__app_permission_code, p.description AS p__description, p.iorder AS p__iorder, p.user_created_id AS p__user_created_id, p.user_updated_id AS p__user_updated_id, p.created_at AS p__created_at, p.updated_at AS p__updated_at FROM ps_app_permission p ORDER BY p.id desc LIMIT 20';

		return $query;
	}

	/**
	 * Lay gia tri Max cua iorder, return: int - max order
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @return int max iorder
	 *        
	 */
	public function getMaxIorder() {

		return $this->createQuery ()
			->select ( 'MAX(iorder) AS max_order' )
			->fetchOne ()
			->getMaxOrder ();
	}

	/**
	 * getPsAppPermissionsByAppId by app_id
	 *
	 * @author thangnc(newwaytech.vn)
	 *        
	 * @param $app_id -
	 *        	int
	 * @return list obj
	 */
	public function getPsAppPermissionsByAppId($app_id) {

		return $this->createQuery ( 'c' )
			->select ( "c.id,c.title,c.app_permission_code" )
			->where ( "c.ps_app_id = ?", $app_id )
			->execute ();
	}

	/**
	 * Check Unique of app_permission_code
	 *
	 * @author thangnc(newwaytech.vn)
	 *        
	 * @param $app_permission_code -
	 *        	string; ma chuc nang
	 * @param $id -
	 *        	int
	 *        	
	 * @return boolean
	 */
	public function checkAppPermissionCodeExits($app_permission_code, $id = null) {

		$query = $this->createQuery ()
			->select ( 'id' );

		if ($app_permission_code != '')
			$query->where ( 'app_permission_code = ?', $app_permission_code );

		if ($id > 0)
			$query->andwhere ( 'id <> ?', $id );

		$records = $query->execute ();

		return count ( $records ) ? false : true;
	}
}