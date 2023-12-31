<?php

/**
 * MyClassTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class MyClassTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object MyClassTable
	 */
	public static function getInstance() {
		return Doctrine_Core::getTable ( 'MyClass' );
	}

	/**
	 * ================================ BEGIN: V1.5 Cloud ============================================================*
	 */

	/**
	 * FUNCTION: doSelectQuery(Doctrine_Query $query)
	 *
	 * @param
	 *        	Doctrine SQL
	 * @return string SQL
	 *        
	 */
	public function doSelectQuery(Doctrine_Query $query) {
		$a = $query->getRootAlias ();

		$query->select ( $a . '.id AS id, ' . $a . '.code AS code, ' . $a . '.name AS name, ' . $a . '.iorder AS iorder, ' . $a . '.note AS note, ' . $a . '.is_activated AS is_activated, ' . $a . '.user_updated_id AS user_updated_id, ' . $a . '.updated_at AS updated_at,' . 'og.title AS obj_group_title,' . 'cr.title AS ps_class_room_name, ' . "wp.title AS work_place_name, " . "wp.address AS work_place_address, " . 'CONCAT(u.first_name, " ", u.last_name) AS updated_by' );

		$query->leftJoin ( $a . '.UserUpdated u' );
		$query->innerJoin ( $a . '.PsCustomer cus' );
		$query->innerJoin ( $a . '.PsClassRooms cr' );
		$query->innerJoin ( 'cr.PsWorkPlaces wp' );

		$query->innerJoin ( $a . '.PsObjectGroups og' );

		$query->where ( $a . '.ps_customer_id = wp.ps_customer_id' );

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_CLASS_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0)
			$query->addWhere ( $a . '.ps_customer_id = ?', myUser::getPscustomerID () );

		return $query;
	}

	// public function getMyClassByFromDateToDate($customer_id, $workplace_id, $from_date, $to_date){

	// $from_date = $from_date ? date('Ymd', strtotime($from_date)) : date('Ymd');
	// $to_date = $to_date ? date('Ymd', strtotime($to_date)) : date('Ymd');

	// $query = $this->createQuery('mc');

	// $query->select('mc.id AS id, mc.name AS name');

	// $query->innerJoin ( 'mc.PsSchoolYear y' );

	// $query->addWhere('(DATE_FORMAT(y.from_date, "%Y%m%d") <=? OR DATE_FORMAT(y.to_date, "%Y%m%d") >=?)', array($from_date, $to_date));

	// $query->innerJoin ( 'mc.PsClassRooms cr' );
	// $query->innerJoin ( 'cr.PsWorkPlaces wp' );

	// $query->addWhere( 'mc.ps_customer_id = wp.ps_customer_id' );

	// echo $query." {$from_date} {$to_date}";die;
	// $query->execute();
	// }
	public function getMyClassByField($class_id, $getField = null) {
		$query = $this->createQuery ()->select ( $getField != '' ? $getField : '*' );

		$query->addWhere ( 'id = ?', $class_id );

		return $query->fetchOne ();
	}

	/**
	 * Ham lay danh sach cac lop de phan cho Danh gia chi so
	 *
	 * @author Tam NT
	 * @param
	 *        	int - $feature_branch_id
	 * @return $list
	 */
	public function getMyClassForEvaluateIndexCriteria($criteria_id, $params) {
		$query = $this->createQuery ( 'mc' );

		$query->select ( 'mc.id AS id, mc.name as class_name, og.title as group_name, cr.title as room_name, wp.id as workplace_id,wp.title as workplace_name ' );

		$query->addSelect ( 'ect.id AS evaluate_class_id, ect.myclass_id AS evaluate_myclass_id, ect.date_start AS date_start, ect.date_end AS date_end' );

		$query->innerJoin ( 'mc.PsObjectGroups og' );

		$query->innerJoin ( 'mc.PsClassRooms cr' );

		$query->innerJoin ( 'cr.PsWorkPlaces wp' );

		$query->leftJoin ( 'mc.PsEvaluateClassTime ect With ect.criteria_id = ?', $criteria_id );

		// $query->leftJoin('ect.PsEvaluateIndexCriteria ec With ec.is_activated = ?', PreSchool::ACTIVE);

		// $query->leftJoin('ec.PsEvaluateSubject es With es.is_activated = ?', PreSchool::ACTIVE);

		if (isset ( $params ['ps_school_year_id'] ) && $params ['ps_school_year_id'] > 0) {
			$query->andWhere ( 'mc.school_year_id = ?', $params ['ps_school_year_id'] );
		}

		if (isset ( $params ['ps_customer_id'] ) && $params ['ps_customer_id'] > 0) {
			$query->andWhere ( 'mc.ps_customer_id = ?', $params ['ps_customer_id'] );
		}

		if (isset ( $params ['ps_workplace_id'] ) && $params ['ps_workplace_id'] > 0) {
			$query->andWhere ( 'cr.ps_workplace_id = ?', $params ['ps_workplace_id'] );
		}

		if (isset ( $params ['ps_obj_group_id'] ) && $params ['ps_obj_group_id'] > 0) {
			$query->andWhere ( 'mc.ps_obj_group_id = ?', $params ['ps_obj_group_id'] );
		}

		if (isset ( $params ['ps_myclass_id'] ) && $params ['ps_myclass_id'] > 0) {
			$query->andWhere ( 'mc.id = ?', $params ['ps_myclass_id'] );
		}

		if (isset ( $params ['is_activated'] ) && $params ['is_activated'] >= 0) {
			$query->andWhere ( 'mc.is_activated = ?', $params ['is_activated'] );
		}

		$query->orderBy ( 'mc.id ASC, ect.created_at DESC' );

		return $query->execute ();
	}

	/**
	 * setSqlMyClassStudentNotIn($student_id, $ps_customer_id)
	 *
	 * Lay danh sach lop ma hoc sinh chua hoc
	 *
	 * @author Thien
	 *        
	 * @return String - SQL
	 *        
	 */
	public function setSqlMyClassStudentNotIn($student_id, $ps_customer_id = null) {
		$query = $this->createQuery ( 's' )->select ( 's.id, s.name AS title, CONCAT(s.name, "- ", wp.title) AS name' );

		$query->leftJoin ( 's.StudentClass sc With sc.student_id = ?', $student_id );

		$query->innerJoin ( 's.PsCustomer cus' );
		$query->innerJoin ( 's.PsClassRooms cr' );
		$query->innerJoin ( 'cr.PsWorkPlaces wp' );

		$query->where ( 's.ps_customer_id = wp.ps_customer_id' );

		$query->addWhere ( 'sc.id IS NULL' );

		if ($ps_customer_id > 0) {
			$query->andWhere ( 's.ps_customer_id = ?', $ps_customer_id );
		}

		$query->orderBy ( 'wp.id, s.iorder' );

		return $query;
	}

	/**
	 * setSqlMyClasss($ps_school_year_id)
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @return String - SQL
	 */
	public function setSqlMyClasss($ps_school_year_id = null) {
		$query = $this->createQuery ()->select ( 'id, name, name AS title' );

		if ($ps_school_year_id > 0) {
			$query->andWhere ( 'school_year_id = ?', $ps_school_year_id );
		}

		if (myUser::getPscustomerID () > 0)
			$query->addWhere ( 'ps_customer_id = ?', myUser::getPscustomerID () );

		$query->orderBy ( 'iorder' );

		return $query;
	}

	/**
	 * getMyClasss($ps_school_year_id)
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @return $list obj
	 */
	public function getMyClasss($ps_school_year_id = null) {
		return $this->setSqlMyClasss ( $ps_school_year_id )->execute ();
	}

	// Dang dung de chuyen lop cho hoc sinh
	public function setSqlMyClassByCustomer($ps_customer_id = null, $my_class_id = null, $ps_school_year_id = null) {

		// $query = $this->createQuery()->select('id, name, name AS title');
		$query = $this->createQuery ( 's' )->select ( 's.id, s.name AS title, CONCAT(s.name, "-", wp.title) AS name, wp.id AS wp_id, wp.title AS wp_title, cr.id AS cr_id, wp.config_template_receipt_export as config_template_receipt_export' );

		$query->innerJoin ( 's.PsCustomer cus' );
		$query->innerJoin ( 's.PsClassRooms cr' );
		$query->innerJoin ( 's.PsWorkPlaces wp' );

		$query->where ( 's.ps_customer_id = wp.ps_customer_id' );

		if ($ps_customer_id > 0) {
			$query->andWhere ( 's.ps_customer_id = ?', $ps_customer_id );
		}

		if ($my_class_id > 0) {
			$query->andWhere ( 's.id = ?', $my_class_id );
		}

		if ($ps_school_year_id > 0) {
			$query->andWhere ( 's.school_year_id = ?', $ps_school_year_id );
		}

		/*
		 * if (myUser::getPscustomerID() > 0)
		 * $query->addWhere('ps_customer_id = ?', myUser::getPscustomerID());
		 */

		$query->orderBy ( 'wp.id, s.iorder' );

		return $query;
	}

	// Khong thay dung
	public function getMyClassByCustomer($ps_customer_id = null, $my_class_id = null) {
		return $this->setSqlMyClassByCustomer ( $ps_customer_id, $my_class_id )->execute ();
	}

	/**
	 * Láº¥y thĂ´ng tin lá»›p há»�c, trÆ°á»�ng, cÆ¡ sá»Ÿ
	 *
	 * @author Phung Van Thanh
	 *        
	 */
	public function getInfoMyClassByCustomer($ps_customer_id = null, $my_class_id = null, $ps_workplace_id = null) {
		$query = $this->createQuery ( 's' )->select ( 's.id, s.name AS cl_name, 
        wp.id AS wp_id, wp.title AS title,wp.address as address, wp.config_template_receipt_export as config_template_receipt_export,
        wp.config_template_report_export as config_template_report_export,wp.config_start_date_system_fee as config_start_date_system_fee,
        wp.config_choose_charge_paylate as config_choose_charge_paylate,wp.config_choose_charge_showlate as config_choose_charge_showlate,
        wp.phone as phone, wp.email as email,
        cr.id AS cr_id,cus.title as cus_title, cus.year_data as year_data, cus.logo as logo,cus.tel as tel,cus.mobile as mobile' );

		$query->innerJoin ( 's.PsCustomer cus' );
		$query->innerJoin ( 's.PsClassRooms cr' );
		$query->innerJoin ( 'cr.PsWorkPlaces wp' );

		$query->where ( 's.ps_customer_id = wp.ps_customer_id' );

		if ($ps_customer_id > 0) {
			$query->andWhere ( 's.ps_customer_id = ?', $ps_customer_id );
		}

		if ($my_class_id > 0) {
			$query->andWhere ( 's.id = ?', $my_class_id );
		}

		if ($ps_workplace_id > 0) {
			$query->andWhere ( 'wp.id = ?', $ps_workplace_id );
		}

		$query->orderBy ( 'wp.id, s.iorder' );

		return $query->fetchOne ();
	}

	/**
	 * getAllMyClassBySchoolYearsId($ps_school_year_id)
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @return $list obj
	 */
	public function getAllMyClassBySchoolYearsId($ps_school_year_id) {
		$query = $this->createQuery ()->select ( 'id, name, name AS title' );

		if ($ps_school_year_id > 0) {
			$query->andWhere ( 'school_year_id = ?', $ps_school_year_id );
		}

		$query->orderBy ( 'iorder' );

		return $query->execute ();
	}

	/**
	 * lay ra ta ca cac lop theo nam, truong va nhom tre
	 *
	 * @author Phung Van Thanh
	 *        
	 * @return $obj
	 */
	public function getClassByCustomerGroup($ps_school_year_id, $ps_customer_id, $ps_workplace_id, $object_id = null, $class_id = null) {
		$query = $this->createQuery ( 'mc' )->select ( 'mc.id as mc_id, mc.code as code, mc.name as title,cr.ps_workplace_id AS ps_workplace_id, cr.id AS cr_id' );

		$query->innerJoin ( 'mc.PsClassRooms cr' );
		$query->addWhere ( 'mc.ps_customer_id = ?', $ps_customer_id );
		$query->andWhere ( 'mc.school_year_id = ? ', $ps_school_year_id );
		$query->andWhere ( 'cr.ps_workplace_id = ? ', $ps_workplace_id );

		if ($class_id > 0) {

			$query->andWhere ( 'mc.id = ? ', $class_id );
		} elseif ($object_id > 0) {

			$query->andWhere ( 'mc.ps_obj_group_id = ? ', $object_id );
		}

		$query->andWhere ( 'is_activated = ?', PreSchool::ACTIVE );

		return $query->execute ();
	}

	// Tong so lop hoc dang hoat dong cua truong trong 1 nÄƒm há»�c $ps_customer_id
	public function getTotalClassInYearOfCustomer($ps_customer_id, $ps_school_year_id) {
		$query = $this->createQuery ()->select ( 'id' );

		$query->where ( 'ps_customer_id = ?', $ps_customer_id );
		$query->andWhere ( 'school_year_id = ? ', $ps_school_year_id );
		$query->andWhere ( 'is_activated = ?', PreSchool::ACTIVE );

		return $query->count ();
	}

	/**
	 * setClassByParams($params)
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @return $listobj
	 */
	public function setClassByParams($params) {
		$query = $this->createQuery ( 'c' )->select ( 'c.id, c.name, c.code AS code, c.name AS title, c.ps_workplace_id AS ps_workplace_id, cr.id AS cr_id, psy.title AS school_year' );

		$query->innerJoin ( 'c.PsSchoolYear psy' );

		if (isset ( $params ['ps_school_year_id'] ) && $params ['ps_school_year_id'] > 0) {
			$query->andWhere ( 'c.school_year_id = ?', $params ['ps_school_year_id'] );
		}

		if (isset ( $params ['ps_customer_id'] ) && $params ['ps_customer_id'] > 0) {
			$query->andWhere ( 'c.ps_customer_id = ?', $params ['ps_customer_id'] );
		}

		/*
		 * if (isset ( $params ['ps_workplace_id'] ) && $params ['ps_workplace_id'] > 0) {
		 * $query->innerJoin ( 'c.PsClassRooms cr' );
		 * $query->andWhere ( 'cr.ps_workplace_id = ?', $params ['ps_workplace_id'] );
		 * } else {
		 * $query->innerJoin ( 'c.PsClassRooms cr' );
		 * }
		 */

		$query->innerJoin ( 'c.PsClassRooms cr' );

		if (isset ( $params ['ps_workplace_id'] ) && $params ['ps_workplace_id'] > 0) {
			$query->andWhere ( 'c.ps_workplace_id = ?', $params ['ps_workplace_id'] );
		}

		if (isset ( $params ['ps_obj_group_id'] ) && $params ['ps_obj_group_id'] > 0) {
			$query->andWhere ( 'c.ps_obj_group_id = ?', $params ['ps_obj_group_id'] );
		}

		if (isset ( $params ['ps_myclass_id'] )) {

			if (is_array ( $params ['ps_myclass_id'] ))
				$query->andWhereIn ( 'c.id', $params ['ps_myclass_id'] );
			else
				$query->andWhere ( 'c.id = ?', $params ['ps_myclass_id'] );
		}

		if (isset ( $params ['class_from_id'] ) && $params ['class_from_id'] > 0) {
			$query->andWhere ( 'c.id != ?', $params ['class_from_id'] );
		}

		if (isset ( $params ['is_activated'] ) && $params ['is_activated'] >= 0) {
			$query->andWhere ( 'c.is_activated = ?', $params ['is_activated'] );
		}

		$query->orderBy ( 'c.name' );

		return $query;
	}

	/**
	 * public function setClassByPsCustomerAndSchoolYear
	 *
	 * @author Pham Van Thien
	 *        
	 * @return $list obj
	 */
	public function setClassByPsCustomerAndSchoolYear($params) {
		$query = $this->createQuery ( 'c' )->select ( 'c.id, name AS c.title' );

		if (isset ( $params ['ps_school_year_id'] ) && isset ( $params ['ps_customer_id'] )) {
			$query->andWhere ( 'ps_school_year_id = ?', $params ['ps_school_year_id'] );
			$query->andWhere ( 'ps_customer_id = ?', $params ['ps_customer_id'] );
		}

		if (isset ( $params ['ps_workplace_id'] ) && isset ( $params ['ps_school_year_id'] )) {
			$query->andWhere ( 'ps_school_year_id = ?', $params ['ps_school_year_id'] );
			$query->innerJoin ( 'c.PsClassRooms cr' );
			$query->andWhere ( 'cr.ps_workplace_id = ?', $params ['ps_workplace_id'] );
		}

		if (isset ( $params ['ps_myclass_id'] ) && $params ['ps_myclass_id'] > 0) {
			$query->andWhere ( 'id = ?', $params ['ps_myclass_id'] );
		}

		if (isset ( $params ['is_activated'] ) && $params ['is_activated'] > 0) {
			$query->andWhere ( 'is_activated = ?', $params ['is_activated'] );
		}

		$query->orderBy ( 'iorder' );

		return $query;
	}

	/**
	 * getClassByParams($params)
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @return $list obj
	 */
	public function getClassByParams($params) {
		return $this->setClassByParams ( $params )->execute ();
	}

	/**
	 *
	 * Lay danh sach lop cua giao vien day
	 *
	 * public function setClassByPsMember
	 *
	 * @author Pham Van Thien
	 *        
	 * @return $listobj
	 */
	public function getClassByPsMember($ps_customer_id, $ps_member_id, $date = null) {
		$date = ($date == null) ? date ( 'Ymd' ) : date ( 'Ymd', strtotime ( $date ) );

		$query = $this->createQuery ( 'mc' )->select ( 'mc.id' )->innerJoin ( 'mc.PsTeacherClass tc' )->andWhere ( ' DATE_FORMAT(tc.start_at,"%Y%m%d") <= ?', $date )->andWhere ( 'tc.stop_at IS NULL OR  DATE_FORMAT(tc.stop_at,"%Y%m%d") >= ?', $date )->andWhere ( 'mc.ps_customer_id = ?', $ps_customer_id )->addWhere ( 'mc.is_activated = ?', PreSchool::ACTIVE )->addWhere ( 'tc.is_activated = ?', PreSchool::ACTIVE )->addWhere ( 'tc.ps_member_id = ?', $ps_member_id );

		return $query->execute ();
	}

	/**
	 * getClassIdByUserId($user_id)
	 * Lay lop duoc phan cong boi user dang nhap
	 *
	 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
	 * @param $class_id -
	 *        	int
	 * @return $list object
	 */
	public function getClassIdByUserId($user_id) {
		$date = date ( 'Ymd' );

		$query = $this->createQuery ( 'c' )->select ( 'c.id, c.name, c.name AS title' );

		$query->innerJoin ( 'c.PsTeacherClass a' );

		$query->addWhere ( 'u.id = ?', $user_id );

		$query->addWhere ( 'u.user_type = ?', PreSchool::USER_TYPE_TEACHER );

		$query->andWhere ( 'DATE_FORMAT(a.start_at,"%Y%m%d") <= ?', $date );

		$query->andWhere ( '((DATE_FORMAT(a.stop_at,"%Y%m%d") >= ?) OR (a.stop_at IS NULL) )', $date );

		$query->innerJoin ( 'a.PsMember m' );

		$query->innerJoin ( 'm.sfGuardUser u' );

		return $query;
	}

	/**
	 *
	 * @author Phung Van Thanh
	 *         Lay lop duoc phan cong boi user dang nhap va co so
	 *         config hĂ m getClassIdByUserId trong pháº§n Ä‘iá»ƒm danh vĂ  Ä‘Ă¡nh giĂ¡ hoáº¡t Ä‘á»™ng
	 *         Cáº­p nháº­t: NgĂ y cuá»‘i cĂ¹ng cá»§a thĂ¡ng 11
	 */
	public function getClassIdByUserIdWorkplace($user_id, $ps_workplace_id, $school_year_id) {
		$date = date ( 'Ymd' );

		$query = $this->createQuery ( 'c' )->select ( 'c.id, c.name, c.name AS title' );

		$query->innerJoin ( 'c.PsTeacherClass a' );

		$query->addWhere ( 'u.id = ?', $user_id );

		$query->addWhere ( 'u.user_type = ?', PreSchool::USER_TYPE_TEACHER );

		$query->andWhere ( 'DATE_FORMAT(a.start_at,"%Y%m%d") <= ?', $date );

		$query->andWhere ( '((DATE_FORMAT(a.stop_at,"%Y%m%d") >= ?) OR (a.stop_at IS NULL) )', $date );

		$query->innerJoin ( 'a.PsMember m' );

		if ($ps_workplace_id > 0) {

			$query->leftJoin ( "m.PsMemberDepartments md" );

			$query->leftJoin ( "md.PsDepartment de With de.ps_workplace_id = ? ", $ps_workplace_id );

			$query->addWhere ( '(md.id IS NOT NULL) OR (md.id IS NULL AND m.ps_workplace_id =?) ', $ps_workplace_id );
		}

		if (isset ( $school_year_id ) && $school_year_id > 0) {
			$query->andWhere ( 'c.school_year_id =?', $school_year_id );
		}

		$query->andWhere ( 'c.is_activated =?', PreSchool::ACTIVE );

		$query->innerJoin ( 'm.sfGuardUser u' );

		$query->orderBy ( 'c.name' );

		return $query;
	}

	/**
	 * Lay danh sach lop theo truong va co so
	 *
	 * @param int $ps_customer_id
	 * @param int $workplace_id
	 * @return mixed
	 */
	public function getClassByPsCustomer($ps_customer_id, $ps_workplace_id = null) {
		$query = $this->createQuery ( 'c' );

		$query->select ( 'c.id AS id,' . 'c.name AS name,' . 'c.student_number AS student_number' );

		$query->innerJoin ( 'c.PsSchoolYear sy' );

		$query->addWhere ( 'sy.is_default = ?', PreSchool::ACTIVE );

		$query->andWhere ( 'c.ps_customer_id = ?', $ps_customer_id );

		$query->andWhere ( 'c.is_activated = ?', PreSchool::ACTIVE );

		if ($ps_workplace_id > 0) {
			$query->andWhere ( 'c.ps_workplace_id = ?', $ps_workplace_id );
		}

		return $query->execute ();
	}

	/**
	 * Returns an instance of this class.
	 *
	 * Lay id, name class va objectGroup theo class_id
	 */
	public function getClassObjGroupNameByClassId($class_id) {
		$q = $this->createQuery ( 'mc' )->select ( 'mc.id, mc.name as mc_name,og.id AS og_id, og.title AS og_title' )->leftJoin ( 'mc.PsObjectGroups og' )->andWhere ( 'mc.id = ?', $class_id )->orderBy ( 'mc.id ASC' );

		return $q->fetchOne ();
	}

	/**
	 * Returns an instance of this class.
	 *
	 * Lay ten co so va ten truong va ten lop theo class_id
	 */
	public function getCustomerInfoByClassId($class_id) {
		$q = $this->createQuery ( 'mc' )->select ( 'mc.id as mc_id, mc.name as mc_name,mc.ps_customer_id as ps_customer_id, cus.school_name as cus_school_name,cus.logo as cus_logo, CONCAT(cus.year_data, "/", cus.logo)  AS cus_path, cus.address AS cus_address,cus.tel AS cus_tel, cus.mobile AS cus_mobile,ts.title AS ts_type_school,cus.year_data as cus_year_data,cr.id AS cr_id,wp.id AS wp_id,wp.title AS wp_name, wp.address AS wp_address, wp.phone AS wp_phone, sy.id AS sy_id, sy.title AS sy_title, og.title AS og_title' )->leftJoin ( 'mc.PsCustomer cus' )->leftJoin ( 'mc.PsClassRooms cr' )->leftJoin ( 'cr.PsWorkPlaces wp' )->leftJoin ( 'mc.PsSchoolYear sy' )->leftJoin ( 'cus.PsTypeSchool AS ts' )->leftJoin ( 'mc.PsObjectGroups og' )->andWhere ( 'mc.id = ?', $class_id )->orderBy ( 'mc.id ASC' );

		return $q->fetchOne ();
	}

	/**
	 * Returns an instance of this class.
	 *
	 * Lay ten co so va ten truong va ten lop theo class_id
	 */
	public function getClassInfoByCustomerId($customer_id, $schoolyear_id = null, $workplace_id = null, $class_id = null) {
		$q = $this->createQuery ( 'mc' )->select ( 'mc.id as mc_id, mc.name as title, ' )->addSelect ( 'cr.id AS cr_id,' )->addSelect ( 'wp.id AS wp_id, wp.title AS wp_name, ' )->addSelect ( 'sy.id AS sy_id, sy.title AS sy_title, ' )->addSelect ( 'og.title AS og_title' )->andWhere ( 'mc.ps_customer_id = ?', $customer_id )->leftJoin ( 'mc.PsClassRooms cr' );
		if ($workplace_id > 0) {
			$q->andWhere ( 'cr.ps_workplace_id = ?', $workplace_id );
		}
		if ($schoolyear_id > 0) {
			$q->andWhere ( 'mc.school_year_id = ?', $schoolyear_id );
		}
		if ($class_id > 0) {
			$q->andWhere ( 'mc.id =?', $class_id );
		}
		$q->andWhere ( 'mc.is_activated =?', PreSchool::ACTIVE );
		$q->leftJoin ( 'cr.PsWorkPlaces wp' )->leftJoin ( 'mc.PsSchoolYear sy' )->leftJoin ( 'mc.PsObjectGroups og' )->orderBy ( 'mc.school_year_id ASC, mc.ps_customer_id ASC, cr.ps_workplace_id ASC, mc.id ASC' );

		return $q->execute ();
	}

	/**
	 * Returns an instance of this class.
	 *
	 * Lay ten co so va ten truong va ten lop theo class_id
	 */
	public function getClassName($class_id) {
		$q = $this->createQuery ( 'mc' )->select ( 'mc.id, mc.name as mc_name, cus.school_name as school_name,cus.logo as logo,cus.id as cus_id,cus.year_data as year_data,cr.id AS cr_id,wp.id AS wp_id,wp.title AS wp_name, wp.address AS wp_address, wp.phone AS wp_phone, sy.id AS sy_id, sy.title AS sy_title' )->leftJoin ( 'mc.PsCustomer cus' )->leftJoin ( 'mc.PsClassRooms cr' )->leftJoin ( 'cr.PsWorkPlaces wp' )->leftJoin ( 'mc.PsSchoolYear sy' )->andWhere ( 'mc.id = ?', $class_id )->orderBy ( 'mc.id ASC' );

		return $q->fetchOne ();
	}
	public function getClassByPsWorkplace($ps_work_place_id, $object) {
		$query = $this->createQuery ( 'c' );

		$query->select ( 'c.id AS id,' . 'c.name AS class_name,' . 'c.name AS title,' . 'c.name AS name,' . 'c.student_number AS student_number' );

		$query->innerJoin ( 'c.PsClassRooms cr' );

		$query->innerJoin ( 'cr.PsWorkPlaces wp' );

		$query->innerJoin ( 'c.PsSchoolYear sy' );

		$query->innerJoin ( 'c.PsObjectGroups ob' );

		$query->addWhere ( 'sy.is_default = ?', PreSchool::ACTIVE );

		$query->addWhere ( 'c.is_activated = ?', PreSchool::ACTIVE );

		if (isset ( $ps_work_place_id ) && $ps_work_place_id > 0)
			$query->addWhere ( 'wp.id = ?', $ps_work_place_id );

		if (isset ( $object ) && $object > 0)
			$query->addWhere ( 'ob.id = ?', $object );

		return $query->execute ();
	}

	/**
	 * Lay danh sach lop theo lop
	 *
	 * @param int $class_id
	 * @return mixed
	 */
	public function getClassByMyClassId($class_id, $object) {
		$query = $this->createQuery ( 'c' );

		$query->select ( 'c.id AS id,c.name AS name' );

		$query->innerJoin ( 'c.PsClassRooms cr' );

		$query->innerJoin ( 'cr.PsWorkPlaces wp' );

		$query->innerJoin ( 'c.PsSchoolYear sy' );

		$query->innerJoin ( 'c.PsObjectGroups ob' );

		$query->addWhere ( 'sy.is_default = ?', PreSchool::ACTIVE );

		$query->addWhere ( 'c.is_activated = ?', PreSchool::ACTIVE );

		$query->addWhere ( 'c.id = ?', $class_id );

		if ($object > 0)
			$query->addWhere ( 'ob.id = ?', $object );

		return $query->execute ();
	}

	/**
	 * Lay group danh sach lop hoc cua truong *
	 */
	public function getChoisGroupMyClassByCustomer($ps_customer_id, $ps_myclass_id = null, $is_activated = NULL) {
		$chois = array ();

		// Lay tat ca cac co so dang hoat dong cua mot truong
		$ps_work_places = Doctrine_Query::create ()->from ( 'PsWorkPlaces' )->select ( "id AS id, title AS title" )->where ( 'is_activated = ?', PreSchool::ACTIVE )->andWhere ( 'ps_customer_id =? ', $ps_customer_id )->orderBy ( 'iorder' )->execute ();

		$params = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_myclass_id' => $ps_myclass_id,
				'is_activated' => $is_activated
		);

		$list_my_class = $this->setClassByParams ( $params )->execute ();

		foreach ( $ps_work_places as $ps_work_place ) {
			foreach ( $list_my_class as $my_class ) {
				if ($ps_work_place->getId () == $my_class->getPsWorkplaceId ()) {
					$chois [$ps_work_place->getTitle ()] [$my_class->getId ()] = $my_class->getTitle () . ' (' . $my_class->getSchoolYear () . ')';
				}
			}
		}

		return $chois;
	}

	/**
	 * Lay group danh sach lop hoc cua truong theo nam hoc *
	 */
	public function getChoisGroupMyClassByCustomerAndYear($ps_customer_id, $ps_schoolyear_id = null, $ps_myclass_id = null, $is_activated = NULL) {
		$chois = array ();

		// Lay tat ca cac co so dang hoat dong cua mot truong
		$ps_work_places = Doctrine_Query::create ()->from ( 'PsWorkPlaces' )->select ( "id AS id, title AS title" )->where ( 'is_activated = ?', PreSchool::ACTIVE )->andWhere ( 'ps_customer_id =? ', $ps_customer_id )->orderBy ( 'iorder' )->execute ();

		$params = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_school_year_id' => $ps_schoolyear_id,
				'ps_myclass_id' => $ps_myclass_id,
				'is_activated' => $is_activated
		);

		$list_my_class = $this->setClassByParams ( $params )->execute ();

		foreach ( $ps_work_places as $ps_work_place ) {
			foreach ( $list_my_class as $my_class ) {
				if ($ps_work_place->getId () == $my_class->getPsWorkplaceId ()) {
					$chois [$ps_work_place->getTitle ()] [$my_class->getId ()] = $my_class->getTitle () . ' (' . $my_class->getSchoolYear () . ')';
				}
			}
		}
		// print_r($chois);die;
		return $chois;
	}

	/**
	 * Ham lay danh sach cac lop de phan cho Hoat dong
	 *
	 * @author Nguyen Chien Thang
	 * @param
	 *        	int - $feature_branch_id
	 * @return $list
	 */
	public function getMyClassForFeatureBranchTimes($feature_branch_times_id, $params) {
		$query = $this->createQuery ( 'mc' );

		$query->select ( 'mc.id AS id, mc.name as class_name, og.title as group_name, cr.title as room_name, wp.id as workplace_id,wp.title as workplace_name ' );

		$query->addSelect ( 'fbct.note AS fbct_note_class, fbct.ps_class_room AS fbct_ps_class_room_id, fbct.ps_myclass_id AS fbct_myclass_id' );

		$query->innerJoin ( 'mc.PsObjectGroups og' );

		$query->innerJoin ( 'mc.PsClassRooms cr' );

		$query->innerJoin ( 'cr.PsWorkPlaces wp' );

		/*
		 * if ($feature_branch_times_id > 0)
		 * $query->leftJoin('mc.PsFeatureBranchTimeMyClass fbct With fbct.ps_feature_branch_time_id = ?', $feature_branch_times_id);
		 * else
		 * $query->leftJoin('mc.PsFeatureBranchTimeMyClass fbct');
		 */

		$query->leftJoin ( 'mc.PsFeatureBranchTimeMyClass fbct With fbct.ps_feature_branch_time_id = ?', $feature_branch_times_id );

		if (isset ( $params ['ps_school_year_id'] ) && $params ['ps_school_year_id'] > 0) {
			$query->andWhere ( 'mc.school_year_id = ?', $params ['ps_school_year_id'] );
		}

		if (isset ( $params ['ps_customer_id'] ) && $params ['ps_customer_id'] > 0) {
			$query->andWhere ( 'mc.ps_customer_id = ?', $params ['ps_customer_id'] );
		}

		if (isset ( $params ['ps_workplace_id'] ) && $params ['ps_workplace_id'] > 0) {
			$query->andWhere ( 'cr.ps_workplace_id = ?', $params ['ps_workplace_id'] );
		}

		if (isset ( $params ['ps_obj_group_id'] ) && $params ['ps_obj_group_id'] > 0) {
			$query->andWhere ( 'mc.ps_obj_group_id = ?', $params ['ps_obj_group_id'] );
		}

		if (isset ( $params ['ps_myclass_id'] ) && $params ['ps_myclass_id'] > 0) {
			$query->andWhere ( 'mc.id = ?', $params ['ps_myclass_id'] );
		}

		if (isset ( $params ['is_activated'] ) && $params ['is_activated'] >= 0) {
			$query->andWhere ( 'mc.is_activated = ?', $params ['is_activated'] );
		}

		/*
		 * $q->leftJoin('mc.PsFeatureBranchMyClass fbc With fbc.ps_feature_branch_id = ?', $feature_branch_id);
		 * $q->where('mc.is_activated = ?', PreSchool::ACTIVE);
		 * $q->where('mc.ps_customer_id = ?', $ps_customer_id);
		 * $q->andWhere('fbc.ps_myclass_id IS NULL OR mc.id IS NULL');
		 * $q->addOrderBy('wp.id, og.id,mc.id DESC');
		 */

		return $query->execute ();
	}

	/**
	 * Tra ve SQL lay danh sach cac lop chua chay bao phi cua thang
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param mixed $params
	 *        	*
	 * @return string sql
	 */
	public function setSqlMyClassForProcessFeeReports($params = array()) {
		$q = $this->createQuery ( 'mc' );

		$q->select ( 'mc.id AS id, mc.name as name, frc.id AS frc_id, og.id AS og_id, og.title AS og_name' );

		$q->innerJoin ( 'mc.PsClassRooms cr' );

		$q->innerJoin ( 'mc.PsObjectGroups og' );

		$q->leftJoin ( 'mc.PsFeeReportsFlagMyClass frc With DATE_FORMAT(frc.receivable_at,"%Y%m") = ?', array (
				PsDateTime::psTimetoDate ( $params ['receivable_at'], "Ym" )
		) );

		$q->where ( 'mc.is_activated = ?', PreSchool::ACTIVE );

		$q->andWhere ( 'mc.school_year_id = ?', $params ['ps_school_year_id'] );

		$q->andWhere ( 'cr.ps_workplace_id = ?', $params ['ps_workplace_id'] );

		// $q->andWhere('frc.id IS NULL');

		$q->addOrderBy ( 'mc.name ASC' );

		return $q;
	}

	/**
	 * Kiem tra xem ton tai class khong nam trong mang class_ids ko
	 *
	 * @param int $ps_customer_id
	 * @param int $workplace_id
	 * @return int
	 */
	public function checkClassByPsCustomer($ps_customer_id, $ps_workplace_id = null, $ps_class_ids = array()) {
		$query = $this->createQuery ( 'c' );

		$query->select ( 'c.id AS id' );

		$query->innerJoin ( 'c.PsSchoolYear sy' );

		$query->addWhere ( 'sy.is_default = ?', PreSchool::ACTIVE );

		$query->andWhere ( 'c.ps_customer_id = ?', $ps_customer_id );

		$query->andWhere ( 'c.is_activated = ?', PreSchool::ACTIVE );

		if ($ps_workplace_id > 0) {
			$query->andWhere ( 'c.ps_workplace_id = ?', $ps_workplace_id );
		}

		if (is_array ( $ps_class_ids )) {
			$query->whereIn ( 'c.id', $ps_class_ids );
		}

		return $query->execute ()->count ();
	}

	/**
	 * ================================ END: ============================================================*
	 */
	public function retrieveBackendMyClassList(Doctrine_Query $query) {
		$rootAlias = $query->getRootAlias ();
		$query->leftJoin ( $rootAlias . '.sfGuardUser gu' );
		$query->leftJoin ( $rootAlias . '.sfGuardUser_2 gu2' );

		return $query;
	}
	public function getWithStudents() {

		/*
		 * $q = $this->createQuery('c')
		 * ->leftJoin('c.StudentClass sc')
		 * ->orderBy('c.name');
		 */
		$q = $this->createQuery ( 'c' )->select ( 'c.id, c.name' )->orderBy ( 'c.name' );

		return $q->execute ();
	}

	/**
	 * getClassByWhere($class_id = '')
	 * : Lay danh sach lop
	 *
	 * *
	 */
	public function getClassByWhere($class_id = '') {
		$q = $this->createQuery ( 'c' );
		$q->addSelect ( 'c.id AS u_id' );

		if ($class_id > 0) {
			$q->andWhere ( 'c.id = ?', $class_id );
		}

		$q->orderBy ( 'c.name' );

		return $q->execute ();
	}
	public function getMyClassforListbox($myclass) {
		$c = array ();
		foreach ( $myclass as $value ) {
			$c [$value->getId ()] = $value->getName ();
		}

		return $c;
	}

	/**
	 * Lay du lieu diem danh các lop cua co so
	 *
	 * @param $ps_work_place_id int
	 *        	, ID cơ sở
	 * @param $tracked_at int
	 *        	*
	 */
	public function getAttendancesOfClassByPsWorkplace($ps_work_place_id, $tracked_at) {
		$query = $this->createQuery ( 'c' );

		$query->select ( 'c.id AS id,' . 'c.name AS class_name,' . 'c.name AS title,' . 'c.name AS name,' . 'c.student_number AS student_numbe, pas.id as pas_id, pas.login_sum as login_sum, pas.logout_sum as logout_sum' );

		// $query->innerJoin ( 'c.PsClassRooms cr' );
		// $query->innerJoin ( 'c.PsObjectGroups ob' );

		$query->innerJoin ( 'c.PsWorkPlaces wp' );

		$query->innerJoin ( 'c.PsSchoolYear sy' );

		$query->leftJoin ( 'c.PsAttendancesSynthetic pas With DATE_FORMAT(pas.tracked_at,"%Y%m%d") = ?', array (
				PsDateTime::psTimetoDate ( $tracked_at, "Ymd" )
		) );

		$query->andWhere ( 'sy.is_default = ?', PreSchool::ACTIVE );

		$query->andWhere ( 'c.is_activated = ?', PreSchool::ACTIVE );

		if (is_array ( $ps_work_place_id ))
			$query->andWhereIn ( 'wp.id', $ps_work_place_id );
		elseif ($ps_work_place_id > 0)
			$query->andWhere ( 'wp.id = ?', $ps_work_place_id );

			$query->orderBy('c.iorder');
			
		return $query->execute ();
	}
}