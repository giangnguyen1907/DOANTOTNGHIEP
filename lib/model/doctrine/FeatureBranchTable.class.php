<?php

/**
 * FeatureBranchTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class FeatureBranchTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object FeatureBranchTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'FeatureBranch' );
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

		$a = $query->getRootAlias ();

		$query->select ( $a . '.id AS id, ' . $a . '.name AS name, ' . $a . '.mode AS mode, ' . $a . '.ps_image_id AS ps_image_id,' . 'I.file_name AS file_name, ' . $a . '.is_activated AS is_activated,' . $a . '.is_study AS is_study, ' . $a . '.number_option AS number_option, ' . $a . '.updated_at AS updated_at,' . 'CONCAT(u.first_name, " ", u.last_name) AS updated_by' );

		$query->innerJoin ( $a . '.Feature f' );
		$query->leftJoin ( $a . '.PsImages I' );
		$query->leftJoin ( $a . '.UserUpdated u' );
		$query->innerJoin ( 'f.PsCustomer cus' );
		if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			$query->addWhere ( 'f.ps_customer_id = ?', myUser::getPscustomerID () );
		}

		// $query->addOrderBy ( $a . '.iorder' );

		return $query;
	}

	public function getFeatureBranchByField($id,$getField = null) {
		
		$query = $this->createQuery () ->select ( $getField != '' ? $getField : '*' );
		
		$query->addWhere ( 'id = ?', $id );
		
		return $query->fetchOne ();
	}
	
	/**
	 * lay danh sach hoat dong
	 *
	 * FUNCTION: setSqlFeatureBranch(Doctrine_Query $query)
	 *
	 * @param
	 *        	Doctrine SQL
	 * @return string SQL
	 *        
	 */
	public function setSqlFeatureBranch($ps_customer_id, $feature_id = '') {

		$q = $this->createQuery ( 'fb' )
			->select ( 'fb.id,fb.name as name, fb.name as title' )
			->leftJoin ( 'fb.Feature f' )
			->addWhere ( 'f.ps_customer_id = ?', $ps_customer_id )
			->andWhere ( 'fb.is_activated = ?', PreSchool::ACTIVE );
		if ($feature_id > 0)
			$q->andWhere ( 'fb.feature_id = ?', $feature_id );
		return $q;
	}

	/**
	 * Phung Van Thanh
	 * lay danh sach hoat dong theo nam hoc, truong va nhom hoat dong
	 *
	 * @return string SQL
	 *        
	 */
	public function setSqlFeatureBranchByYear($ps_school_year_id, $ps_customer_id, $feature_id = '') {

		$q = $this->createQuery ( 'fb' )
			->select ( 'fb.id,fb.name as name, fb.name as title' )
			->leftJoin ( 'fb.Feature f' )
			->addWhere ( 'f.ps_customer_id = ?', $ps_customer_id )
			->andWhere ( 'fb.is_activated = ?', PreSchool::ACTIVE );
		$q->andWhere ( 'fb.school_year_id = ?', $ps_school_year_id );
		if ($feature_id > 0)
			$q->andWhere ( 'fb.feature_id = ?', $feature_id );

		return $q;
	}

	/**
	 * Phung Van Thanh
	 * lay danh sach hoat dong theo nam hoc, truong, lop
	 *
	 * Date : 12/11/2018
	 */
	public function getFeatureBranchByCustomerOfMonth($ps_school_year_id, $ps_customer_id, $tracked_at, $ps_class_id = null) {

		$q = $this->createQuery ( 'fb' )
			->select ( 'fb.id as fb_id,fb.name as name' )
			->addSelect ( 'fbt.start_at as start_at, fbt.end_at as end_at,fbtm.ps_myclass_id as ps_myclass_id' )
			->innerJoin ( 'fb.Feature f' )
			->innerJoin ( 'fb.FeatureBranchTimes fbt' )
			->innerJoin ( 'fbt.PsFeatureBranchTimeMyClass fbtm' )
			->addWhere ( 'f.ps_customer_id = ?', $ps_customer_id )
			->andWhere ( 'fb.number_option >= ?', 1 )
			->andWhere ( 'fb.is_activated = ?', PreSchool::ACTIVE );
		$q->andWhere ( 'fb.school_year_id = ?', $ps_school_year_id );
		$q->where ( 'DATE_FORMAT(fbt.start_at,"%Y%m") <= ? AND  DATE_FORMAT(fbt.end_at,"%Y%m") >= ?', array (
				date ( 'Ym', strtotime ( $tracked_at ) ),
				date ( 'Ym', strtotime ( $tracked_at ) ) ) );
		if ($ps_class_id > 0) {
			$q->andWhere ( 'fbtm.ps_myclass_id =? ', $ps_class_id );
		}
		$q->orderBy ( 'fb.name' );
		return $q->execute ();
	}

	/**
	 * Tao SQL lay danh sach theo ps_customer_id
	 *
	 * @return $list obj
	 *        
	 */
	public function getFeatureBrandListByCustomerId($ps_customer_id) {

		$db = Doctrine_Manager::getInstance ()->getCurrentConnection ();
		$query = $db->prepare ( "SELECT `fb.id AS id, fb.name AS name, fbt.start_time AS start_time, fb.end_time AS end_time` FROM `FeatureBranch fb` LEFT JOIN Feature f ON 'fb.feature_id = f.id' LEFT JOIN 'FeatureBranchTimes fbt' ON 'fb.id = fbt.ps_feature_branch_id'  WHERE 'f.ps_customer_id' = :ps_customer_id" and 'f.is_activated = 1' );

		$query->execute ( array (
				'ps_customer_id' => $ps_customer_id ) );

		return $query->fetchAll ();
	}

	/**
	 * Lay danh sach hoat dong theo dieu kien
	 *
	 * FUNCTION: setSqlFeatureBranchByFilters($filters = null)
	 *
	 * @author Nguyen Chien Thang
	 * @param $filters -
	 *        	array
	 * @return string SQL
	 *        
	 */
	public function setSqlFeatureBranchByFilters($filters = null) {

		$q = $this->createQuery ( 'fb' )
			->select ( 'fb.id,fb.name as name, fb.name as title' )
			->innerJoin ( 'fb.Feature f' );
		$q->innerJoin ( 'fb.FeatureBranchTimes fbt' );

		if (isset ( $filters ['school_year_id'] ) && $filters ['school_year_id'] > 0)
			$q->andWhere ( 'fb.school_year_id = ?', $filters ['school_year_id'] );

		if (isset ( $filters ['ps_workplace_id'] ) && $filters ['ps_workplace_id'] > 0)
			$q->andWhere ( 'fb.ps_workplace_id = 0 OR fb.ps_workplace_id IS NULL OR fb.ps_workplace_id = ?', $filters ['ps_workplace_id'] );
		if (isset ( $filters ['ps_obj_group_id'] ) && $filters ['ps_obj_group_id'] > 0)
			$q->andWhere ( 'fb.ps_obj_group_id = ?', $filters ['ps_obj_group_id'] );

		if (isset ( $filters ['ps_customer_id'] ) && $filters ['ps_customer_id'] > 0)
			$q->andWhere ( 'f.ps_customer_id = ?', $filters ['ps_customer_id'] );

		if (isset ( $filters ['feature_id'] ) && $filters ['feature_id'] > 0)
			$q->andWhere ( 'fb.feature_id = ?', $feature_id );

		if (isset ( $filters ['is_activated'] ) && $filters ['is_activated'] != '' && $filters ['is_activated'] >= 0)
			$q->andWhere ( 'fb.is_activated = ?', $filters ['is_activated'] );

		if (isset ( $filters ['is_continuity'] ) && $filters ['is_continuity'] >= 0)
			$q->andWhere ( 'fb.is_continuity = ?', $filters ['is_continuity'] );
			
		$q->orderBy ( 'fb.iorder' );

		return $q;
	}

	/**
	 * Lay danh sach hoat dong theo dieu kien
	 *
	 * FUNCTION: setSqlFeatureBranchByMyClassParams($filters = array())
	 *
	 * @author Nguyen Chien Thang
	 * @param $filters -
	 *        	array
	 * @return string SQL
	 *         edit Thanh
	 *        
	 */
	public function setSqlFeatureBranchByMyClassParams($filters = array()) {

		$filters ['tracked_at'] = ($filters ['tracked_at'] == '') ? date ( "Ymd" ) : $filters ['tracked_at'];

		$tracked_at = date ( "Ymd", PsDateTime::psDatetoTime ( $filters ['tracked_at'] ) );

		$q = $this->createQuery ( 'fb' )
			->select ( 'fb.id AS id,fb.name as name, fb.name as title' )
			->addSelect ( 'fbt.id as fbt_id,fbtm.ps_myclass_id as ps_class_id' )
			->innerJoin ( 'fb.Feature f' )
			->innerJoin ( 'fb.FeatureBranchTimes fbt With DATE_FORMAT(fbt.start_at,"%Y%m%d") <= ?  AND DATE_FORMAT(fbt.end_at,"%Y%m%d") >= ? ', array (
				$tracked_at,
				$tracked_at ) );
		
		if (isset ( $filters ['ps_myclass_id'] ) && $filters ['ps_myclass_id'] > 0){
		$q->leftJoin ( 'fbt.PsFeatureBranchTimeMyClass fbtm With fbtm.ps_myclass_id = ?', $filters ['ps_myclass_id'] );
		}else{
			$q->leftJoin ( 'fbt.PsFeatureBranchTimeMyClass fbtm ');
		}
		if (isset ( $filters ['ps_customer_id'] ) && $filters ['ps_customer_id'] > 0)
			$q->where ( 'f.ps_customer_id = ?', $filters ['ps_customer_id'] );

		if (isset ( $filters ['ps_school_year_id'] ) && $filters ['ps_school_year_id'] > 0)
			$q->andWhere ( 'fb.school_year_id = ?', $filters ['ps_school_year_id'] );

		if (isset ( $filters ['ps_workplace_id'] ) && $filters ['ps_workplace_id'] > 0)
			$q->andWhere ( 'fb.ps_workplace_id IS NULL OR fb.ps_workplace_id = ?', $filters ['ps_workplace_id'] );
		else
			$q->andWhere ( 'fb.ps_workplace_id IS NULL' );

		if (isset ( $filters ['ps_obj_group_id'] ) && $filters ['ps_obj_group_id'] > 0)
			$q->andWhere ( 'fb.ps_obj_group_id IS NULL OR fb.ps_obj_group_id = ?', $filters ['ps_obj_group_id'] );

		if (isset ( $filters ['is_activated'] ) && $filters ['is_activated'] != '' && $filters ['is_activated'] >= 0)
			$q->andWhere ( 'fb.is_activated = ?', $filters ['is_activated'] );

		if (isset ( $filters ['number_option'] ) && $filters ['number_option'] != '' && $filters ['number_option'] >= 0)
			$q->andWhere ( 'fb.number_option >= ?', $filters ['number_option'] );

		$q->orderBy ( 'fb.iorder' );

		return $q;
	}

	/**
	 * Lay danh sach lich hoat dong theo dieu kien loc
	 *
	 * @param
	 *        	date_from, date_to
	 * @return $Obj
	 */
	public function getListFBWeek($ps_customer_id, $ps_workplace_id = null, $ps_class_id = null) {

		$query = $this->createQuery ( 'a' )
			->select ( 'a.id AS id, ' . 'a.name AS name,' . 'a.ps_workplace_id AS ps_workplace_id,' . 'a.ps_obj_group_id AS ps_obj_group_id,' . 'a.school_year_id AS school_year_id,' . 'a.note AS note,' . 'fbt.id,' . 'fbt.ps_class_room_id AS fbt_ps_class_room_id,' . 'fbt.start_at AS fbt_start_at,' . 'fbt.end_at AS fbt_end_at,' . 'fbt.start_time AS fbt_start_time,' . 'fbt.end_time AS fbt_end_time,' . 'fbt.is_saturday AS fbt_is_saturday,' . 'fbt.is_sunday AS fbt_is_sunday,' . 'fbt.note AS fbt_note,' . 'fbt.note_class_name AS fbt_note_class_name,' );

		$query->innerJoin ( 'a.FeatureBranchTimes fbt' );
		$query->innerJoin ( 'a.Feature f' );

		$query->addWhere ( 'f.ps_customer_id = ?', $ps_customer_id );
		$query->andWhere ( 'a.is_activated = ?', PreSchool::ACTIVE );

		if ($ps_workplace_id > 0) {
			$query->addWhere ( 'a.ps_workplace_id = 0 OR a.ps_workplace_id IS NULL OR a.ps_workplace_id = ?', $ps_workplace_id );
		}

		if ($ps_class_id > 0) {
			$query->leftJoin ( 'fbt.PsFeatureBranchTimeMyClass fbtmc' );

			$query->andWhere ( 'length(fbt.note_class_name) = 0 OR length(fbt.note_class_name) IS NULL OR fbtmc.ps_myclass_id =? ', $ps_class_id );
		}

		return $query;
	}
	
	/**
	 * Lay danh sach lich hoat dong theo dieu kien loc
	 *
	 * @param
	 *        	date_from, date_to
	 * @return $Obj
	 */
	public function getListFBexport($ps_school_year_id,$ps_customer_id, $ps_workplace_id = null) {
		
		$query = $this->createQuery ( 'fb' )
		->select ( 'fb.id,fb.name as name, fb.name as title' )
		->innerJoin ( 'fb.Feature f' );
		
		$query->addWhere ( 'f.ps_customer_id = ?', $ps_customer_id );
		$query->andWhere ( 'fb.is_activated = ?', PreSchool::ACTIVE );
		
		$query->andWhere ( 'fb.school_year_id = ?', $ps_school_year_id );
		
		if ($ps_workplace_id > 0) {
			$query->addWhere ( 'fb.ps_workplace_id = 0 OR fb.ps_workplace_id IS NULL OR fb.ps_workplace_id = ?', $ps_workplace_id );
		}
		
		$query->andWhere ( 'fb.is_continuity = ?',PreSchool::NOT_ACTIVE);
		
		return $query->execute();
	}
	
	// Cap nhat so luong tieu chi
	public function updateNumberOptionFeature($getField,$branch_id) {
		
		$q = $this->createQuery ()->select ( $getField != '' ? $getField : '*' );
		$q -> addWhere('id = ?',$branch_id);
		return $q->fetchOne();
	}
	
}