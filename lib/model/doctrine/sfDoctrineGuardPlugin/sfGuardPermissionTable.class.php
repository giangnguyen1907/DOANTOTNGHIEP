<?php

/**
 * sfGuardPermissionTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class sfGuardPermissionTable extends PluginsfGuardPermissionTable {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object sfGuardPermissionTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'sfGuardPermission' );
	}

	/**
	 * Lay danh sach quyen theo ma nhom
	 *
	 * @author Pham Van Thien (thien95tm@gmail.com)
	 *        
	 * @param
	 *        	array group_id
	 * @return Obj
	 */
	public function getPermissionByGroupId($group_id) {

		$query = $this->createQuery ( 'sfGuardPermission p' );
		$query->select ( "p.id as permission_id, " . "GROUP_CONCAT( p.title SEPARATOR '; ') as permission_name, " . "gp.group_id as group_id, " . "App.id as ps_app_id, " . "App.title as app_name" 
		);

		$query->innerJoin ( 'p.sfGuardGroupPermission gp' );

		$query->innerJoin ( 'p.PsApp App' );

		$query->WhereIn ( 'gp.group_id', $group_id );
		$query->groupBy ( 'p.ps_app_id' );

		return $query->execute ();
	}

	/**
	 * Lay danh sach quyen theo ma nguoi dung
	 *
	 * @author Pham Van Thien (thien95tm@gmail.com)
	 *        
	 * @param
	 *        	int group_id
	 * @return Obj
	 */
	public function getPermissionByUserId($user_id) {

		$query = $this->createQuery ( 'sfGuardPermission p' );
		$query->select ( "p.id as permission_id, " . "GROUP_CONCAT( p.title SEPARATOR '; ') as permission_name, " . "App.id as ps_app_id, " . "App.title as app_name" 
		);

		$query->innerJoin ( 'p.sfGuardUserPermission up' );

		$query->innerJoin ( 'p.PsApp App' );

		$query->addWhere ( 'up.user_id = ?', $user_id );

		$query->groupBy ( 'p.ps_app_id' );

		return $query->execute ();
	}
}