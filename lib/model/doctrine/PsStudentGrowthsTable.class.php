<?php

/**
 * PsStudentGrowthsTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsStudentGrowthsTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsStudentGrowthsTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsStudentGrowths' );
	}

	public function doSelectQuery(Doctrine_Query $query) {

		$a = $query->getRootAlias ();

		$query->select ( $a . '.id AS id, ' . $a . '.height AS height, ' . $a . '.weight AS weight, ' . $a . '.index_height AS index_height, ' . $a . '.index_weight AS index_weight, ' . $a . '.index_age AS index_age, ' . $a . '.examination_id AS examination_id, ' . $a . '.index_tooth AS index_tooth, ' . $a . '.index_throat AS index_throat, ' . $a . '.index_eye AS index_eye, ' . $a . '.index_heart AS index_heart, ' . $a . '.index_lung AS index_lung, ' . $a . '.index_skin AS index_skin, ' . $a . '.people_make AS people_make, '. $a . '.number_push_notication AS number_push_notication, ' . 'ex.name AS ex_name, ' . 'ex.input_date_at AS ex_input_date_at, ' . 's.ps_customer_id AS ps_customer_id,' . 's.image AS image, ' . 's.id as student_id, s.student_code AS student_code, ' . 's.sex AS sex, ' . 's.birthday AS birthday, ' . 's.year_data AS year_data, ' . 'cus.school_code AS school_code,' . 'CONCAT(s.first_name, " ", s.last_name) AS student_name' );

		$query->addSelect ( 'CONCAT(u.first_name, " ", u.last_name) AS updated_by' );

		$query->leftJoin ( $a . '.PsExamination ex' );

		$query->innerJoin ( $a . '.UserUpdated u' );

		$query->innerJoin ( $a . '.Student s' );

		$query->innerJoin ( 's.PsCustomer cus' );

		$query->addWhere ( 's.deleted_at IS NULL' );

		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			$query->addWhere ( 's.ps_customer_id = ?', myUser::getPscustomerID () );
		}

		$query->orderBy ( $a . '.examination_id desc, s.id' );

		return $query;
	}

	/**
	 * Lay thong tin chi so
	 *
	 * @return $listobj
	 *
	 */
	public function doSelectQuery2(Doctrine_Query $query) {

		$query = Doctrine_Query::create ()->from ( 'Student s' );
		$query->select ( 'psg.id AS id, ' . 's.student_code AS student_code, ' . 'CONCAT(s.first_name, " ", s.last_name) AS student_name,' . 's.birthday AS birthday, ' . 'psg.height AS height, ' . 'psg.weight AS weight, psg.index_height AS index_height, psg.index_weight AS index_weight, ' . 'psg.people_make AS people_make, ' . 'psg.examination_id AS examination_id, ' . 'psg.organization_make AS organization_make, ' . 'psg.note AS note, ' . 'psg.user_updated_id AS user_updated_id, ' . 'psg.updated_at AS updated_at, s.id AS student_id' );

		$query->addSelect ( 'CONCAT(u.first_name, " ", u.last_name) AS updated_by' );

		$query->innerJoin ( 's.StudentClass sc' );

		$query->innerJoin ( 's.PsStudentGrowths psg' );

		$query->innerJoin ( 'psg.PsExaminations ex' );

		$query->innerJoin ( 's.PsCustomer cus' );

		$query->innerJoin ( 'psg.UserUpdated u' );
		
		$query->addWhere ( 's.deleted_at IS NULL' );

		$query->whereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );

		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			$query->addWhere ( 's.ps_customer_id = ?', myUser::getPscustomerID () );
		}

		$query->orderBy ( 'psg.examination_id desc' );

		return $query;
	}
	
	// man hinh thong tin lan kham chua noi duoc de lay ten dot kham
	public function getStudentGrowthsById($id_student) {

		$q = $this->createQuery ( 'psg' )
			->select ( 'psg.*, ex.name AS name,ex.input_date_at AS input_date_at' )
			->leftJoin ( 'psg.PsExamination ex' )
			->where ( 'psg.student_id = ?', ( int ) $id_student )
			->orderBy ( 'psg.index_age desc' );
		return $q->execute ();
	}

	/**
	 * Lấy thông tin khám sức khỏe theo học sinh và đợt khám modul import
	 * ThanhPV
	 */
	public function getStudentGrowthsByStudentId($student_id, $examination_id) {

		$q = $this->createQuery ( 'psg' )
			->select ( 'psg.*' )
			->
		addWhere ( 'psg.student_id = ?', ( int ) $student_id )
			->andWhere ( 'psg.examination_id = ?', ( int ) $examination_id )
			->orderBy ( 'psg.index_age desc' );

		return $q->fetchOne ();
	}

	/*
	 * getStudentsGrowthsByClassId($myclass_id, $tracked_at)
	 * Lay danh sach y te hoc sinh co trang thai: Chinh thuc, Hoc thu tai mot thoi diem cua lop
	 * @param $myclass_id - int
	 * @param $tracked_at
	 * @return $list
	 */
	public function getStudentsGrowthsByClassId($myclass_id, $tracked_at = null, $examination_id) {

		// echo $tracked_at; die;
		$tracked_at = ($tracked_at != '') ? date ( "Ymd", strtotime ( $tracked_at ) ) : date ( "Ymd" );

		$q = $this->createQuery ()
			->from ( 'Student s' )
			->select ( "sc.id as id,sc.student_id AS student_id, s.student_code as student_code, CONCAT(s.first_name, ' ', s.last_name) AS full_name, s.birthday AS birthday, s.image AS image,s.sex AS sex, s.year_data AS year_data ,s.status AS status,
		    sg.id as ps_student_growths_id, sg.height as height,sg.index_height as index_height,ex.name as ex_name, sg.examination_id as examination_id, sg.index_weight as index_weight, sg.weight as weight,sg.number_push_notication as number_push_notication,cus.school_code AS school_code, ex.input_date_at AS input_date_at" )
			->innerJoin ( 's.PsCustomer cus' );

		if ($examination_id > 0) {

			$q->leftJoin ( 's.PsStudentGrowths sg With sg.examination_id = ?', $examination_id );

			$q->leftJoin ( 'sg.PsExamination ex With ex.id = ?', $examination_id );
		} else {

			$q->leftJoin ( 's.PsStudentGrowths sg' );

			$q->leftJoin ( 'sg.PsExamination ex' );
		}

		$q->innerJoin ( 's.StudentClass sc With ( sc.myclass_id = ? AND (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= ? ) AND (DATE_FORMAT(sc.start_at,"%Y%m%d") <= ?) AND sc.type IN (\'' . PreSchool::SC_STATUS_TEST . '\', \'' . PreSchool::SC_STATUS_OFFICIAL . '\')) ', array (
				$myclass_id,
				$tracked_at,
				$tracked_at ) );

		$q->innerJoin ( 'sc.MyClass mc' );

		/*
		 * $q->andWhereIn('sc.type', array(
		 * PreSchool::SC_STATUS_TEST,
		 * PreSchool::SC_STATUS_OFFICIAL
		 * ));
		 */

		$q->addWhere ( 's.deleted_at IS NULL' );

		$q->orderBy ( 'sg.examination_id DESC, s.last_name, s.first_name' );

		return $q->execute ();
	}

	// Ham lay hoc sinh da kham
	public function getStudentsGrowthsIndexch($myclass_id, $date_at, $exami) {

		$query = Doctrine_Query::create ()->from ( 'Student s' )
			->select ( 's.student_code AS student_code, ' . 'CONCAT(s.first_name, " ", s.last_name) AS student_name,' . 's.birthday AS birthday, ' . 's.sex AS sex, ' . 'ex.name AS ex_name, ' . 'ex.input_date_at AS ex_input_date_at, ' . 'psg.height AS height, ' . 'psg.weight AS weight, psg.index_height AS index_height, psg.index_weight AS index_weight, ' . 'psg.people_make AS people_make, ' . 'psg.examination_id AS examination_id, ' . 'psg.organization_make AS organization_make, ' . 'psg.note AS note, ' . 'psg.user_updated_id AS user_updated_id, ' . 'psg.updated_at AS updated_at, s.id AS student_id' )
			->innerJoin ( 's.StudentClass sc' )
			->innerJoin ( 's.PsStudentGrowths psg' )
			->innerJoin ( 'psg.PsExamination ex' )
			->addwhere ( 'ex.id = ?', $exami )
			->andWhere ( 's.deleted_at IS NULL' )
			->andWhereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );
		$query->andWhere ( ' DATE_FORMAT(sc.start_at,"%Y%m%d") <= ?', date ( "Ymd", strtotime ( $date_at ) ) );
		$query->andWhere ( '(sc.stop_at IS NULL OR  DATE_FORMAT(sc.stop_at,"%Y%m%d") >= ?)', date ( "Ymd", strtotime ( $date_at ) ) );
		// $query->andWhere('psg.student_id != ?', 's.id');
		$query->addwhere ( 'sc.myclass_id = ?', $myclass_id );
		return $query->execute ();
	}

	// Ham lay hoc sinh theo chi so kham cua chieu cao
	public function getStudentsGrowthsIndexh($examination, $class_id, $growths_index) {

		$q = $this->createQuery ( 'psg' )
			->select ( 'psg.id AS id, ' . 's.student_code AS student_code, ' . 'CONCAT(s.first_name, " ", s.last_name) AS student_name,' . 's.birthday AS birthday, ' . 's.sex AS sex, ' . 'ex.name AS ex_name, ' . 'ex.input_date_at AS ex_input_date_at, ' . 'psg.height AS height, ' . 'psg.weight AS weight, psg.index_height AS index_height, psg.index_weight AS index_weight, ' . 'psg.people_make AS people_make, ' . 'psg.examination_id AS examination_id, ' . 'psg.organization_make AS organization_make, ' . 'psg.note AS note, ' . 'psg.user_updated_id AS user_updated_id, ' . 'psg.updated_at AS updated_at, s.id AS student_id' )
			->leftJoin ( 'psg.Student s' )
			->leftJoin ( 'psg.PsExamination ex' )
			->leftJoin ( 'ex.PsCustomer cus' )
			->leftJoin ( 'ex.PsWorkPlaces wp' )
			->innerJoin ( 's.StudentClass sc With (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= DATE_FORMAT(ex.input_date_at,"%Y%m%d")) AND (DATE_FORMAT(sc.start_at,"%Y%m%d") <= DATE_FORMAT(ex.input_date_at,"%Y%m%d"))' )
			->leftJoin ( 'sc.MyClass mc' )
			->addWhere ( 'ex.id = ?', $examination );

		$q->andWhere ( 'psg.index_height = ?', ( int ) $growths_index );

		$q->andwhere ( 'sc.myclass_id = ?', $class_id )
			->orderBy ( 'psg.index_age desc' );

		return $q->execute ();
	}

	// Ham lay hoc sinh theo chi so kham cua can nang
	public function getStudentsGrowthsIndexw($examination, $class_id, $growths_index) {

		$q = $this->createQuery ( 'psg' )
			->select ( 'psg.id AS id, ' . 's.student_code AS student_code, ' . 'CONCAT(s.first_name, " ", s.last_name) AS student_name,' . 's.birthday AS birthday, ' . 's.sex AS sex, ' . 'ex.name AS ex_name, ' . 'ex.input_date_at AS ex_input_date_at, ' . 'psg.height AS height, ' . 'psg.weight AS weight, psg.index_height AS index_height, psg.index_weight AS index_weight, ' . 'psg.people_make AS people_make, ' . 'psg.examination_id AS examination_id, ' . 'psg.organization_make AS organization_make, ' . 'psg.note AS note, ' . 'psg.user_updated_id AS user_updated_id, ' . 'psg.updated_at AS updated_at, s.id AS student_id' )
			->leftJoin ( 'psg.Student s' )
			->leftJoin ( 'psg.PsExamination ex' )
			->leftJoin ( 'ex.PsCustomer cus' )
			->leftJoin ( 'ex.PsWorkPlaces wp' )
			->innerJoin ( 's.StudentClass sc With (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= DATE_FORMAT(ex.input_date_at,"%Y%m%d")) AND (DATE_FORMAT(sc.start_at,"%Y%m%d") <= DATE_FORMAT(ex.input_date_at,"%Y%m%d"))' )
			->leftJoin ( 'sc.MyClass mc' )
			->addWhere ( 'ex.id = ?', $examination );

		$q->andWhere ( 'psg.index_weight = ?', ( int ) $growths_index );

		$q->andwhere ( 'sc.myclass_id = ?', $class_id )
			->orderBy ( 'psg.index_age desc' );

		return $q->execute ();
	}

	// Ham lay tat ca hoc sinh cua lop trong bang y te
	public function getAllStudentsByClassId($ps_school_year_id, $ps_customer_id, $ps_workplace_id, $class_id) {
		
		$query = $this->createQuery ( 'psg' );
		$query->select ( 'psg.id AS id,psg.student_id AS student_id,psg.index_height AS index_height, psg.index_weight AS index_weight,psg.examination_id AS examination_id,s.id AS s_id,sc.id AS scid,mc.id AS mcid,
		psg.height AS height, psg.weight AS weight,psg.index_heart AS index_heart,psg.index_age AS index_age, psg.index_eye AS index_eye, psg.index_lung AS index_lung, psg.index_throat AS index_throat, psg.index_tooth AS index_tooth
		psg.index_skin AS index_skin, psg.note AS note
		' );
		
		$query->innerJoin ( 'psg.PsExamination ex' );
		
		$query->innerJoin ( 'psg.Student s' );
		
		$query->innerJoin ( 's.StudentClass sc' );
		
		$query->innerJoin ( 'sc.MyClass mc' );
		
		$query->addWhere ( 's.deleted_at IS NULL' );
		
		$query->andWhere ( 's.ps_customer_id = ?', $ps_customer_id );
		$query->andWhere ( 'mc.id = ? ', $class_id );
		$query->andWhere ( 'ex.school_year_id = ? ', $ps_school_year_id );
		$query->andWhere ( 'mc.ps_workplace_id = ? ', $ps_workplace_id );
		$query->whereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );
		
		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			$query->addWhere ( 's.ps_customer_id = ?', myUser::getPscustomerID () );
		}
		
		return $query->execute ();
	}
	
	// Ham lay tat ca hoc sinh cua truong trong bang y te
	public function getAllStudentsByCustomerId($ps_school_year_id, $ps_customer_id, $ps_workplace_id, $examination = null, $object = null) {

		$query = $this->createQuery ( 'psg' );
		$query->select ( 'psg.id AS id, ' . 'psg.student_id AS student_id, ' . 'psg.index_height AS index_height, ' . 'psg.index_weight AS index_weight, ' . 'psg.examination_id AS examination_id, ' . 's.id AS student_id,' . 'sc.id AS sid,' . 'mc.id AS mcid,' . 'ex.id AS exid' );

		$query->innerJoin ( 'psg.PsExamination ex' );
		$query->innerJoin ( 'psg.Student s' );
		if ($examination > 0) {
			$query->innerJoin ( 's.StudentClass sc With (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= DATE_FORMAT(ex.input_date_at,"%Y%m%d")) AND (DATE_FORMAT(sc.start_at,"%Y%m%d") <= DATE_FORMAT(ex.input_date_at,"%Y%m%d"))' );
			$query->addWhere ( 'psg.examination_id = ?', $examination );
		} else {
			$query->innerJoin ( 's.StudentClass sc' );
		}

		$query->innerJoin ( 'sc.MyClass mc' );
		
		$query->addWhere ( 's.deleted_at IS NULL' );

		if ($object > 0) {
			$query->andWhere ( 'mc.ps_obj_group_id = ?', $object );
		}
		$query->andWhere ( 's.ps_customer_id = ?', $ps_customer_id );
		$query->andWhere ( 'mc.school_year_id = ? ', $ps_school_year_id );
		$query->andWhere ( 'ex.school_year_id = ? ', $ps_school_year_id );
		$query->andWhere ( 'mc.ps_workplace_id = ? ', $ps_workplace_id );
		$query->whereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );

		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			$query->addWhere ( 's.ps_customer_id = ?', myUser::getPscustomerID () );
		}

		return $query->execute ();
	}

	// Ham lay tat ca hoc sinh suy dinh duong
	public function getAllStudentsMalnutrition($ps_school_year_id, $ps_customer_id, $ps_workplace_id, $examination = null, $object = null, $class_id = null) {
		
		$query = $this->createQuery ( 'psg' );
		$query->select ( 'psg.*');
		$query->addSelect('s.id AS student_id, CONCAT(s.first_name, " ", s.last_name) AS student_name, s.student_code as student_code ,sc.id AS sid,mc.id AS mcid,mc.name as class_name, ex.id AS exid');
		$query->innerJoin ( 'psg.PsExamination ex' );
		$query->innerJoin ( 'psg.Student s' );
		if ($examination > 0) {
			$query->innerJoin ( 's.StudentClass sc With (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= DATE_FORMAT(ex.input_date_at,"%Y%m%d")) AND (DATE_FORMAT(sc.start_at,"%Y%m%d") <= DATE_FORMAT(ex.input_date_at,"%Y%m%d"))' );
			$query->addWhere ( 'psg.examination_id = ?', $examination );
		} else {
			$query->innerJoin ( 's.StudentClass sc' );
		}
		
		$query->innerJoin ( 'sc.MyClass mc' );
		$query->innerJoin ( 'mc.PsClassRooms cr' );
		
		$query->addWhere ( 's.deleted_at IS NULL' );
		
		if ($object > 0) {
			$query->andWhere ( 'mc.ps_obj_group_id = ?', $object );
		}
		
		if($class_id > 0){
			$query->andWhere ( 'mc.id = ?', $class_id );
		}
		
		$query->andWhere ( 'psg.index_height < 0 OR psg.index_weight != 0' );
		$query->andWhere ( 's.ps_customer_id = ?', $ps_customer_id );
		$query->andWhere ( 'ex.school_year_id = ? ', $ps_school_year_id );
		$query->andWhere ( 'cr.ps_workplace_id = ? ', $ps_workplace_id );
		$query->whereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );
		
		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			$query->addWhere ( 's.ps_customer_id = ?', myUser::getPscustomerID () );
		}
		
		return $query->execute ();
	}
	// Ham lay tat ca hoc sinh suy dinh duong
	public function setAllStudentsMalnutrition($ps_school_year_id, $ps_customer_id, $ps_workplace_id, $examination = null, $object = null, $class_id = null) {
		
		$query = $this->createQuery ( 'psg' );
		$query->select ( 'psg.*');
		$query->addSelect('s.id AS student_id,s.sex AS sex, CONCAT(s.first_name, " ", s.last_name) AS student_name, s.student_code as student_code ,sc.id AS sid,mc.id AS mcid,mc.name as class_name, ex.id AS exid');
		$query->innerJoin ( 'psg.PsExamination ex' );
		$query->innerJoin ( 'psg.Student s' );
		if ($examination > 0) {
			$query->innerJoin ( 's.StudentClass sc With (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= DATE_FORMAT(ex.input_date_at,"%Y%m%d")) AND (DATE_FORMAT(sc.start_at,"%Y%m%d") <= DATE_FORMAT(ex.input_date_at,"%Y%m%d"))' );
			$query->addWhere ( 'psg.examination_id = ?', $examination );
		} else {
			$query->innerJoin ( 's.StudentClass sc' );
		}
		
		$query->innerJoin ( 'sc.MyClass mc' );
		$query->innerJoin ( 'mc.PsClassRooms cr' );
		
		$query->addWhere ( 's.deleted_at IS NULL' );
		
		if ($object > 0) {
			$query->andWhere ( 'mc.ps_obj_group_id = ?', $object );
		}
		
		if($class_id > 0){
			$query->andWhere ( 'mc.id = ?', $class_id );
		}
		
		$query->andWhere ( 'psg.index_height < 0 OR psg.index_weight != 0' );
		$query->andWhere ( 's.ps_customer_id = ?', $ps_customer_id );
		$query->andWhere ( 'ex.school_year_id = ? ', $ps_school_year_id );
		$query->andWhere ( 'cr.ps_workplace_id = ? ', $ps_workplace_id );
		$query->whereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );
		
		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			$query->addWhere ( 's.ps_customer_id = ?', myUser::getPscustomerID () );
		}
		
		return $query;
	}
	
	// Ham dem so hoc sinh da kham
	public function getStudentsGrowthsCount($mylass_id, $examination, $object) {

		$query = Doctrine_Query::create ()->from ( 'Student s' );
		$query->select ( 'psg.id AS id, ' . 'psg.student_id AS student_id, ' . 's.id AS student_id' . 'sc.id AS sid' . 'mc.id AS mid' . 'ex.id as exid' );

		$query->innerJoin ( 's.PsCustomer cus' );

		$query->leftJoin ( 's.PsStudentGrowths psg' );

		$query->leftJoin ( 'psg.PsExamination ex' );

		if ($examination > 0) {
			$query->innerJoin ( 's.StudentClass sc With (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= DATE_FORMAT(ex.input_date_at,"%Y%m%d")) AND (DATE_FORMAT(sc.start_at,"%Y%m%d") <= DATE_FORMAT(ex.input_date_at,"%Y%m%d"))' );
			$query->addWhere ( 'ex.id = ?', $examination );
		} else {
			$query->innerJoin ( 's.StudentClass sc' );
		}

		$query->innerJoin ( 'sc.MyClass mc' );

		$query->addWhere ( 's.deleted_at IS NULL' );

		if ($object > 0)
			$query->andWhere ( 'mc.ps_obj_group_id = ?', $object );

		$query->andWhere ( 'sc.myclass_id = ?', $mylass_id );

		$query->whereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );

		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			$query->addWhere ( 's.ps_customer_id = ?', myUser::getPscustomerID () );
		}

		return $query->count ();
	}

	// Ham dem so hoc sinh chieu cao dat
	public function getStudentsGrowthsCountHeight($mylass_id, $examination, $object) {

		$query = Doctrine_Query::create ()->from ( 'Student s' );
		$query->select ( 'psg.id AS id, ' . 'psg.student_id AS student_id, ' . 's.id AS student_id' . 'sc.id AS sid' . 'mc.id AS mid' . 'ex.id as exid' );

		$query->innerJoin ( 's.PsCustomer cus' );

		$query->leftJoin ( 's.PsStudentGrowths psg' );

		$query->leftJoin ( 'psg.PsExamination ex' );

		if ($examination > 0) {
			$query->innerJoin ( 's.StudentClass sc With (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= DATE_FORMAT(ex.input_date_at,"%Y%m%d")) AND (DATE_FORMAT(sc.start_at,"%Y%m%d") <= DATE_FORMAT(ex.input_date_at,"%Y%m%d"))' );
			$query->addWhere ( 'ex.id = ?', $examination );
		} else {
			$query->innerJoin ( 's.StudentClass sc' );
		}

		$query->innerJoin ( 'sc.MyClass mc' );

		$query->addWhere ( 's.deleted_at IS NULL' );

		if ($object > 0)
			$query->andWhere ( 'mc.ps_obj_group_id = ?', $object );

		$query->andWhere ( 'sc.myclass_id = ?', $mylass_id );

		$query->andWhere ( 'psg.index_height >= ?', '0' );

		$query->andWhere ( 'psg.examination_id = ?', $examination );

		$query->whereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );

		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			$query->addWhere ( 's.ps_customer_id = ?', myUser::getPscustomerID () );
		}

		return $query->count ();
	}

	// Ham dem so hoc sinh can nang dat
	public function getStudentsGrowthsCountWeight($mylass_id, $examination, $object) {

		$query = Doctrine_Query::create ()->from ( 'Student s' );
		$query->select ( 'psg.id AS id, ' . 'psg.student_id AS student_id, ' . 's.id AS student_id' . 'sc.id AS sid' . 'mc.id AS mid' . 'ex.id as exid' );

		$query->innerJoin ( 's.PsCustomer cus' );

		$query->leftJoin ( 's.PsStudentGrowths psg' );

		$query->leftJoin ( 'psg.PsExamination ex' );

		if ($examination > 0) {
			$query->innerJoin ( 's.StudentClass sc With (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= DATE_FORMAT(ex.input_date_at,"%Y%m%d")) AND (DATE_FORMAT(sc.start_at,"%Y%m%d") <= DATE_FORMAT(ex.input_date_at,"%Y%m%d"))' );
			$query->addWhere ( 'ex.id = ?', $examination );
		} else {
			$query->innerJoin ( 's.StudentClass sc' );
		}

		$query->innerJoin ( 'sc.MyClass mc' );

		$query->addWhere ( 's.deleted_at IS NULL' );

		if ($object > 0)
			$query->andWhere ( 'mc.ps_obj_group_id = ?', $object );

		$query->andWhere ( 'sc.myclass_id = ?', $mylass_id );

		$query->andWhere ( 'psg.index_weight >= ?', '0' );

		$query->andWhere ( 'psg.examination_id = ?', $examination );

		$query->whereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );

		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			$query->addWhere ( 's.ps_customer_id = ?', myUser::getPscustomerID () );
		}

		return $query->count ();
	}

	// Ham dem so hoc sinh thap coi do 2
	public function getStudentsGrowthsCountHeight2($mylass_id, $examination, $object) {

		$query = Doctrine_Query::create ()->from ( 'Student s' );
		$query->select ( 'psg.id AS id, ' . 'psg.student_id AS student_id, ' . 's.id AS student_id' . 'sc.id AS sid' . 'mc.id AS mid' . 'ex.id as exid' );

		$query->innerJoin ( 's.PsCustomer cus' );

		$query->leftJoin ( 's.PsStudentGrowths psg' );

		$query->leftJoin ( 'psg.PsExamination ex' );

		if ($examination > 0) {
			$query->innerJoin ( 's.StudentClass sc With (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= DATE_FORMAT(ex.input_date_at,"%Y%m%d")) AND (DATE_FORMAT(sc.start_at,"%Y%m%d") <= DATE_FORMAT(ex.input_date_at,"%Y%m%d"))' );
			$query->addWhere ( 'ex.id = ?', $examination );
		} else {
			$query->innerJoin ( 's.StudentClass sc' );
		}

		$query->innerJoin ( 'sc.MyClass mc' );

		$query->addWhere ( 's.deleted_at IS NULL' );

		if ($object > 0)
			$query->andWhere ( 'mc.ps_obj_group_id = ?', $object );

		$query->andWhere ( 'sc.myclass_id = ?', $mylass_id );

		$query->andWhere ( 'psg.index_height = ?', '-2' );

		$query->andWhere ( 'psg.examination_id = ?', $examination );

		$query->whereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );

		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			$query->addWhere ( 's.ps_customer_id = ?', myUser::getPscustomerID () );
		}

		return $query->count ();
	}

	// Ham dem so hoc sinh thap coi do 1
	public function getStudentsGrowthsCountHeight1($mylass_id, $examination, $object) {

		$query = Doctrine_Query::create ()->from ( 'Student s' );
		$query->select ( 'psg.id AS id, ' . 'psg.student_id AS student_id, ' . 's.id AS student_id' . 'sc.id AS sid' . 'mc.id AS mid' . 'ex.id as exid' );

		$query->innerJoin ( 's.PsCustomer cus' );

		$query->leftJoin ( 's.PsStudentGrowths psg' );

		$query->leftJoin ( 'psg.PsExamination ex' );

		if ($examination > 0) {
			$query->innerJoin ( 's.StudentClass sc With (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= DATE_FORMAT(ex.input_date_at,"%Y%m%d")) AND (DATE_FORMAT(sc.start_at,"%Y%m%d") <= DATE_FORMAT(ex.input_date_at,"%Y%m%d"))' );
			$query->addWhere ( 'ex.id = ?', $examination );
		} else {
			$query->innerJoin ( 's.StudentClass sc' );
		}

		$query->innerJoin ( 'sc.MyClass mc' );

		$query->addWhere ( 's.deleted_at IS NULL' );

		if ($object > 0)
			$query->andWhere ( 'mc.ps_obj_group_id = ?', $object );

		$query->andWhere ( 'sc.myclass_id = ?', $mylass_id );

		$query->andWhere ( 'psg.index_height = ?', '-1' );

		$query->andWhere ( 'psg.examination_id = ?', $examination );

		$query->whereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );

		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			$query->addWhere ( 's.ps_customer_id = ?', myUser::getPscustomerID () );
		}

		return $query->count ();
	}

	// Ham dem so hoc sinh suy dinh duong nang
	public function getStudentsGrowthsCountWeight2($mylass_id, $examination, $object) {

		$query = Doctrine_Query::create ()->from ( 'Student s' );
		$query->select ( 'psg.id AS id, ' . 'psg.student_id AS student_id, ' . 's.id AS student_id' . 'sc.id AS sid' . 'mc.id AS mid' . 'ex.id as exid' );

		$query->innerJoin ( 's.PsCustomer cus' );

		$query->leftJoin ( 's.PsStudentGrowths psg' );

		$query->leftJoin ( 'psg.PsExamination ex' );

		if ($examination > 0) {
			$query->innerJoin ( 's.StudentClass sc With (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= DATE_FORMAT(ex.input_date_at,"%Y%m%d")) AND (DATE_FORMAT(sc.start_at,"%Y%m%d") <= DATE_FORMAT(ex.input_date_at,"%Y%m%d"))' );
			$query->addWhere ( 'ex.id = ?', $examination );
		} else {
			$query->innerJoin ( 's.StudentClass sc' );
		}

		$query->innerJoin ( 'sc.MyClass mc' );

		$query->addWhere ( 's.deleted_at IS NULL' );

		if ($object > 0)
			$query->andWhere ( 'mc.ps_obj_group_id = ?', $object );

		$query->andWhere ( 'sc.myclass_id = ?', $mylass_id );

		$query->andWhere ( 'psg.index_weight = ?', '-2' );

		$query->andWhere ( 'psg.examination_id = ?', $examination );

		$query->whereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );

		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			$query->addWhere ( 's.ps_customer_id = ?', myUser::getPscustomerID () );
		}

		return $query->count ();
	}

	// Ham dem so hoc sinh suy dinh duong nhe
	public function getStudentsGrowthsCountWeight1($mylass_id, $examination, $object) {

		$query = Doctrine_Query::create ()->from ( 'Student s' );
		$query->select ( 'psg.id AS id, ' . 'psg.student_id AS student_id, ' . 's.id AS student_id' . 'sc.id AS sid' . 'mc.id AS mid' . 'ex.id as exid' );

		$query->innerJoin ( 's.PsCustomer cus' );

		$query->leftJoin ( 's.PsStudentGrowths psg' );

		$query->leftJoin ( 'psg.PsExamination ex' );

		if ($examination > 0) {
			$query->innerJoin ( 's.StudentClass sc With (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= DATE_FORMAT(ex.input_date_at,"%Y%m%d")) AND (DATE_FORMAT(sc.start_at,"%Y%m%d") <= DATE_FORMAT(ex.input_date_at,"%Y%m%d"))' );
			$query->addWhere ( 'ex.id = ?', $examination );
		} else {
			$query->innerJoin ( 's.StudentClass sc' );
		}

		$query->innerJoin ( 'sc.MyClass mc' );

		$query->addWhere ( 's.deleted_at IS NULL' );

		if ($object > 0)
			$query->andWhere ( 'mc.ps_obj_group_id = ?', $object );

		$query->andWhere ( 'sc.myclass_id = ?', $mylass_id );

		$query->andWhere ( 'psg.index_weight = ?', '-1' );

		$query->andWhere ( 'psg.examination_id = ?', $examination );

		$query->whereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );

		if (! myUser::credentialPsCustomers ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' ) && myUser::getPscustomerID () > 0) {
			$query->addWhere ( 's.ps_customer_id = ?', myUser::getPscustomerID () );
		}

		return $query->count ();
	}

	
	/**
	 * Gui thong bao cho tung hoc sinh 
	 **/
	public function getPsStudentGrowthsById($growth_id) {
		$query = $this->createQuery ( 'psg' );
		$query->select ( 'psg.id AS id,psg.height AS height, psg.weight AS weight, psg.number_push_notication AS number_push_notication,psg.user_push_notication_id AS user_push_notication_id,s.id AS student_id, 
		psg.date_push_notication AS date_push_notication,CONCAT(s.first_name, " ", s.last_name) AS student_name, s.ps_customer_id AS ps_customer_id');
		$query->innerJoin ( 'psg.Student s' );
		$query->addWhere ('psg.id =?',$growth_id);
		return $query->fetchOne();
	}
	
	/**
	 * Gui thong bao cho nhieu hoc sinh
	 **/
	public function getPsStudentGrowthsByIds($growth_id, $ps_customer_id = null) {
		$query = $this->createQuery ( 'psg' );
		$query->select ( 'psg.id AS id,psg.height AS height, psg.weight AS weight, psg.number_push_notication AS number_push_notication,psg.user_push_notication_id AS user_push_notication_id,s.id AS student_id,
		psg.date_push_notication AS date_push_notication, CONCAT(s.first_name, " ", s.last_name) AS student_name, s.ps_customer_id AS ps_customer_id');
		$query->innerJoin ( 'psg.Student s' );
		$query->whereIn('psg.id',$growth_id);
		if($ps_customer_id > 0){
			$query->addWhere('s.ps_customer_id =?',$ps_customer_id);
		}
		return $query->execute();
	}
	
	/**
	 * Lay tat ca du lieu trong bang luu tru thong tin kham
	 *
	 * De update du lieu sai
	 *
	 * @author ThanhPV
	 *        
	 *         *
	 */
	public function getAllDataStudentsByGrowth() {

		$query = $this->createQuery ( 'psg' );

		$query->select ( 'psg.id AS id, ' . 'psg.student_id AS student_id, ' . 'psg.index_height AS index_height, ' . 'psg.index_weight AS index_weight, ' . 'psg.examination_id AS examination_id, ' . 's.id AS student_id,' . 's.sex AS sex' );

		$query->innerJoin ( 'psg.Student s' );

		$query->addWhere ( 's.deleted_at IS NULL' );

		return $query->execute ();
	}
}