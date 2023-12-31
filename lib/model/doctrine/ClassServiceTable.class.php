<?php

/**
 * ClassServiceTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class ClassServiceTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object ClassServiceTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'ClassService' );
	}

	/**
	 * getNumberServiceByClassId($ps_myclass_id)
	 * Lay so luong dich vu cua lop
	 *
	 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
	 *        
	 * @param $class_id -
	 *        	int
	 * @return int
	 */
	public function getNumberServiceByClassId($class_id) {

		$query = $this->createQuery ( 'a' )
			->where ( 'a.ps_myclass_id = ?', $class_id );

		return $query->count ();
	}

	/**
	 * Lay so luong lop da dang ky dich vu
	 *
	 * @author thangnc
	 *        
	 * @param
	 *        	$service_id
	 * @return int
	 */
	public function getCountClassServiceOfServiceId($service_id) {

		return $this->createQuery ( 'a' )
			->select ( 'a.id' )
			->addWhere ( 'a.service_id = ?', $service_id )
			->count ();
	}
}