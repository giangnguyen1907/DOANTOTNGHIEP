<?php

/**
 * PsWorkPlaces
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    quanlymamnon.vn
 * @subpackage model
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class PsWorkPlaces extends BasePsWorkPlaces {

	/**
	 * Lay danh sach khoang phi ve khung gia ve muon *
	 */
	public function getListLates() {

		return Doctrine::getTable ( 'PsConfigLateFees' )->getListLates ( $this->getId () );
	}
}