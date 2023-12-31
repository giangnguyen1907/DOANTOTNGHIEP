<?php

/**
 * PsFeatureBranchTimeMyClassTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsFeatureBranchTimeMyClassTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsFeatureBranchTimeMyClassTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsFeatureBranchTimeMyClass' );
	}

	/**
	 * Lay tat ca time
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param
	 *        	$ps_feature_branch_time_id
	 *        	
	 * @return $list
	 */
	public function getAllByPsFeatureBranchTimeId($ps_feature_branch_time_id) {

		$q = $this->createQuery ( 'a' )
			->select ( 'a.*' );

		$q->innerJoin ( 'a.MyClass mc' );

		$q->andWhere ( 'a.ps_feature_branch_time_id = ?', $ps_feature_branch_time_id );

		return $q->execute ();
	}

	/**
	 * Lay thong tin co ban gom time va lop
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param
	 *        	$ps_feature_branch_time_id
	 *        	
	 * @return $list
	 */
	public function getBasicInfoByPsFeatureBranchTimeId($ps_feature_branch_time_id) {

		$q = $this->createQuery ( 'a' )
			->select ( 'a.id,a.ps_myclass_id, a.ps_class_room  as ps_class_room, a.note as note, mc.name AS class_name,fbt.id as fbt_id, fbt.note as fbt_note' );

		$q->innerJoin ( 'a.MyClass mc' );
		$q->innerJoin ( 'a.FeatureBranchTimes fbt' );
		$q->andWhere ( 'a.ps_feature_branch_time_id = ?', $ps_feature_branch_time_id );

		return $q->execute ();
	}

	/**
	 * Lay thong tin co ban gom time va lop
	 *
	 * @author Phung Van Thanh
	 *        
	 * @param
	 *        	$ps_feature_branch_time_id
	 *        	
	 * @return fetchOne()
	 */
	public function getBasicInfoByPsFeatureBranchTimeIdAndClassId($ps_feature_branch_time_id, $ps_class_id) {

		$q = $this->createQuery ( 'a' )
			->select ( 'a.id,a.ps_myclass_id, a.ps_class_room  as ps_class_room, a.note as note, mc.name AS class_name,fbt.id as fbt_id, fbt.note as fbt_note' );

		$q->innerJoin ( 'a.MyClass mc' );
		$q->innerJoin ( 'a.FeatureBranchTimes fbt' );
		$q->addWhere ( 'a.ps_myclass_id = ?', $ps_class_id );

		$q->andWhere ( 'a.ps_feature_branch_time_id = ?', $ps_feature_branch_time_id );

		return $q->fetchOne ();
	}

	/**
	 * Lay tat ca time
	 */
	public function getFeatureBranchClassIdOfMonth($ps_class_id, $ps_month) {

		$date = '01-' . $ps_month;
		$q = $this->createQuery ( 'a' )
			->select ( 'a.id as id,a.ps_myclass_id, a.ps_class_room  as ps_class_room,mc.name AS class_name,
        fbt.id as fbt_id,fb.name as name,fb.id as fb_id,fbt.start_at as start_at, fbt.end_at as end_at' );
		$q->innerJoin ( 'a.FeatureBranchTimes fbt' );
		$q->innerJoin ( 'fbt.FeatureBranch fb' );
		$q->innerJoin ( 'a.MyClass mc' );
		$q->addWhere ( 'fb.number_option >=?', 1 );
		$q->andWhere ( 'fb.is_activated =?', PreSchool::ACTIVE );
		$q->addWhere ( 'a.ps_myclass_id =?', $ps_class_id );
		$q->andWhere ( 'DATE_FORMAT(fbt.start_at,"%Y%m") <= ?', date ( "Ym", strtotime ( $date ) ) );
		$q->andWhere ( 'DATE_FORMAT(fbt.end_at,"%Y%m") >= ?', date ( "Ym", strtotime ( $date ) ) );

		return $q->execute ();
	}

	/**
	 * Phung Van Thanh
	 * Lay tat cac lop co hoat dong trong ngay
	 * Update danh gia hoat dong
	 */
	public function getFeatureBranchCustomerIdOfDay($ps_school_year_id, $ps_customer_id, $date_at, $class_id) {

		$q = $this->createQuery ( 'a' )
			->select ( 'a.id as id,a.ps_myclass_id, a.ps_class_room  as ps_class_room,mc.name AS class_name,
        fbt.id as fbt_id,fbt.ps_feature_branch_id as ps_feature_branch_id,fb.name as name,fb.id as fb_id,fbt.start_at as start_at, fbt.end_at as end_at' );
		$q->innerJoin ( 'a.FeatureBranchTimes fbt' );
		$q->innerJoin ( 'fbt.FeatureBranch fb' );
		$q->innerJoin ( 'fb.Feature f' );
		$q->innerJoin ( 'a.MyClass mc' );
		$q->andWhere ( 'fb.number_option > 0' );
		$q->andWhere ( 'fb.is_activated =?', PreSchool::ACTIVE );
		$q->andWhere ( 'fb.school_year_id =?', $ps_school_year_id );
		$q->andWhere ( 'f.ps_customer_id =?', $ps_customer_id );
		if ($class_id > 0) {
			$q->andWhere ( 'a.ps_myclass_id =?', $class_id );
		}
		$q->andWhere ( 'DATE_FORMAT(fbt.start_at,"%Y%m%d") <= ?', date ( "Ymd", strtotime ( $date_at ) ) );
		$q->andWhere ( 'DATE_FORMAT(fbt.end_at,"%Y%m%d") >= ?', date ( "Ymd", strtotime ( $date_at ) ) );
		$q->orderBy ( 'fb.name,a.id' );
		return $q->execute ();
	}

	/**
	 * Phung Van Thanh
	 * Lay tat cac lop co hoat dong trong thang
	 */
	public function getFeatureBranchCustomerIdOfMonth($ps_school_year_id, $ps_customer_id, $date_at, $class_id) {

		$q = $this->createQuery ( 'a' )
			->select ( 'a.id as id,a.ps_myclass_id, a.ps_class_room  as ps_class_room,mc.name AS class_name,
        fbt.id as fbt_id,fbt.ps_feature_branch_id as ps_feature_branch_id,fb.name as name,fb.id as fb_id,fbt.start_at as start_at, fbt.end_at as end_at' );
		$q->innerJoin ( 'a.FeatureBranchTimes fbt' );
		$q->innerJoin ( 'fbt.FeatureBranch fb' );
		$q->innerJoin ( 'fb.Feature f' );
		$q->innerJoin ( 'a.MyClass mc' );
		$q->andWhere ( 'fb.number_option > 0' );
		$q->andWhere ( 'fb.is_activated =?', PreSchool::ACTIVE );
		$q->andWhere ( 'fb.school_year_id =?', $ps_school_year_id );
		$q->andWhere ( 'f.ps_customer_id =?', $ps_customer_id );
		if ($class_id > 0) {
			$q->andWhere ( 'a.ps_myclass_id =?', $class_id );
		}
		$q->andWhere ( 'DATE_FORMAT(fbt.start_at,"%Y%m") <= ?', date ( "Ym", strtotime ( $date_at ) ) );
		$q->andWhere ( 'DATE_FORMAT(fbt.end_at,"%Y%m") >= ?', date ( "Ym", strtotime ( $date_at ) ) );
		$q->orderBy ( 'fb.name,a.id' );
		return $q->execute ();
	}

	/**
	 * Phung Van Thanh
	 * Lay tat cac lop co hoat dong trong thang
	 * Ngay 15/11/2018
	 */
	public function getFeatureBranchCustomerIdOfMonthGroup($ps_school_year_id, $ps_customer_id, $date_at, $class_id) {

		$q = $this->createQuery ( 'a' )
			->select ( 'a.id as id,a.ps_myclass_id, a.ps_class_room  as ps_class_room,mc.name AS class_name,
        fbt.id as fbt_id,fbt.ps_feature_branch_id as ps_feature_branch_id,fb.name as name,fb.id as fb_id,fbt.start_at as start_at, fbt.end_at as end_at' );
		$q->innerJoin ( 'a.FeatureBranchTimes fbt' );
		$q->innerJoin ( 'fbt.FeatureBranch fb' );
		$q->innerJoin ( 'fb.Feature f' );
		$q->innerJoin ( 'a.MyClass mc' );
		$q->andWhere ( 'fb.number_option > 0' );
		$q->andWhere ( 'fb.is_activated =?', PreSchool::ACTIVE );
		$q->andWhere ( 'fb.school_year_id =?', $ps_school_year_id );
		$q->andWhere ( 'f.ps_customer_id =?', $ps_customer_id );
		if ($class_id > 0) {
			$q->andWhere ( 'a.ps_myclass_id =?', $class_id );
		}
		$q->andWhere ( 'DATE_FORMAT(fbt.start_at,"%Y%m") <= ?', date ( "Ym", strtotime ( $date_at ) ) );
		$q->andWhere ( 'DATE_FORMAT(fbt.end_at,"%Y%m") >= ?', date ( "Ym", strtotime ( $date_at ) ) );
		$q->addGroupBy ( 'fbt.ps_feature_branch_id' );
		$q->orderBy ( 'fb.name,a.id' );
		return $q->execute ();
	}
}