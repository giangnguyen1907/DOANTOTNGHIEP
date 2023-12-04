<?php
/**
 * RelativeStudentTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class RelativeStudentTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object RelativeStudentTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'RelativeStudent' );
	}

	/**
	 * Sql lay ra danh sach hoc sinh theo nguoi than va lop
	 *
	 * @var int $class_id
	 * @var $relative_id;
	 * @var date $trach_at
	 */
	public function sqlGetStudentByRelativeId($relative_id, $track_at = null) {

		$date_at = isset ( $track_at ) ? date ( 'Ymd', strtotime ( $track_at ) ) : date ( 'Ymd' );

		$q = $this->createQuery ( 'rs' );

		$q->select ( 'rs.id, rs.relative_id AS relative_id' );

		$q->addSelect ( 's.id as student_id, s.student_code AS student_code, CONCAT(s.first_name, " ",  s.last_name) AS student_name,' );

		$q->addSelect ( 'mc.id AS mc_id, mc.name AS mc_name' );

		$q->addSelect ( 'sc.id' );

		$q->addSelect ( 'rls.id, rls.title AS relation_ship' );

		if (is_array ( $relative_id )) {

			$q->andWhereIn ( 'rs.relative_id', $relative_id );
		} else {

			$q->andWhere ( 'rs.relative_id =?', $relative_id );
		}
		$q->innerJoin ( 'rs.Student s WITH s.deleted_at IS NULL' );

		$q->innerJoin ( 's.StudentClass sc WITH DATE_FORMAT(sc.start_at, "%Y%m%d") <=? AND (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at, "%Y%m%d") >=?)', array (
				$date_at,
				$date_at ) );

		$q->andWhereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );

		// $q->andWhere('sc.is_activated =?', PreSchool::ACTIVE);

		$q->leftJoin ( 'sc.MyClass mc' );

		$q->leftJoin ( 'rs.Relationship rls' );

		$q->orderBy ( 's.last_name, s.first_name' );

		return $q;
	}

	/**
	 * Ham check Phu huynh co nhieu con em dang theo hoc (student.delete_at = null)
	 * Return so luong hoc sinh deleted_at = null
	 *
	 * @param int $student_id
	 *
	 * @return int
	 */
	public function checkRelativeHaveManyStudentNotDelete($student_id) {

		$q = $this->createQuery ( 'rs' )
			->select ( 'rs.id AS id, rs.relative_id AS r_id' );

		$q->innerJoin ( 'rs.Student s' );

		$q->addWhere ( 's.id = ?', $student_id );

		$q->addWhere ( 's.deleted_at IS NULL' );

		$q = $q->fetchArray ();

		$check = Doctrine::getTable ( 'RelativeStudent' )->createQuery ( 'rs' )
			->select ( 'count(rs.id)' )
			->andWhereIn ( 'rs.relative_id', array_column ( $q, 'r_id' ) )
			->innerJoin ( 'rs.Student s WITH s.deleted_at IS NULL' )
			->count ();

		return $check;
	}

	/**
	 * Ham kiem tra xem nguoi than nay da gan voi hoc sinh nao chua
	 *
	 * @param int $student_id
	 * @param int $relative_id
	 * @return object for relative_student
	 *        
	 */
	public function checkRelativeStudentExits($student_id, $relative_id) {

		$q = $this->createQuery ( 'c' )
			->addWhere ( 'c.student_id = ? AND c.relative_id = ?', array (
				$student_id,
				$relative_id ) )
			->limit ( 1 );

		return $q->fetchOne ();
	}

	public function checkparentStudentExits($student_id, $is_parent_main) {

		$q = $this->createQuery ( 'c' )
			->addWhere ( 'c.student_id = ? AND c.is_parent_main = ?', array (
				$student_id,
				$is_parent_main ) );

		return $q->execute ();
	}

	/**
	 * Lay danh sach nguoi than co tai khoan theo class
	 *
	 * @author Phung Van Thanh
	 *        
	 */
	public function getRelativeByClassId($ps_class_id) {

		$date_at = date ( 'Ymd' );
		$query = $this->createQuery ( 'rs' );
		$query->select ( 'rs.id as id,s.id as s_id, r.id as r_id, CONCAT(r.first_name, " ",  r.last_name) AS full_name, 
        CONCAT(s.first_name, " ",  s.last_name) AS student_name, rss.title as rss_title, u.id as user_id' );
		$query->innerJoin ( 'rs.Relative r WITH r.deleted_at IS NULL' );
		$query->innerJoin ( 'rs.Relationship rss' );
		$query->innerJoin ( 'rs.Student s WITH s.deleted_at IS NULL' );
		$query->innerJoin ( 's.StudentClass sc WITH DATE_FORMAT(sc.start_at, "%Y%m%d") <=? AND (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at, "%Y%m%d") >=?)', array (
				$date_at,
				$date_at ) );
		$query->innerJoin ( 'r.sfGuardUser u WITH (u.user_type =? AND u.is_active = ?)', array (
				PreSchool::USER_TYPE_RELATIVE,
				PreSchool::CUSTOMER_ACTIVATED ) );
		$query->andWhereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );
		$query->andWhere ( 'sc.myclass_id = ?', $ps_class_id );
		$query->addGroupBy ( 'r.id' );
		$query->orderBy ( 'r.last_name,r.first_name' );

		return $query->execute ();
	}

	/**
	 * Lay danh sach nguoi than theo class
	 *
	 * @author Phung Van Thanh
	 *
	 */
	public function getAllRelativeByClass($ps_class_id) {
		
		$date_at = date ( 'Ymd' );
		$query = $this->createQuery ( 'rs' );
		$query->select ( 'rs.id as id,s.id as student_id, r.id as r_id, CONCAT(r.first_name, " ",  r.last_name) AS full_name,r.job AS job,r.mobile AS mobile,
        CONCAT(s.first_name, " ",  s.last_name) AS student_name, rss.title as rss_title' );
		$query->innerJoin ( 'rs.Relative r WITH r.deleted_at IS NULL' );
		$query->innerJoin ( 'rs.Relationship rss' );
		$query->innerJoin ( 'rs.Student s WITH s.deleted_at IS NULL' );
		$query->innerJoin ( 's.StudentClass sc WITH DATE_FORMAT(sc.start_at, "%Y%m%d") <=? AND (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at, "%Y%m%d") >=?)', array (
				$date_at,
				$date_at ) );
		$query->andWhereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );
		$query->andWhere ( 'sc.myclass_id = ?', $ps_class_id );
		$query->addGroupBy ( 'r.id' );
		$query->orderBy ( 's.last_name,s.first_name,rss.title,r.last_name' );
		
		return $query->execute ();
	}
	/**
	 * Lay danh sach hoc sinh co moi quan he voi phu huynh theo ma phu huynh va ma lop
	 *
	 * @param
	 *        	int relative_id,ps_class_id
	 * @return Obj
	 *
	 *
	 */
	// public function findStudentsByRelativeIdAndClassId($relative_id, $ps_class_id) {

	// $query = Doctrine::getTable('PsSchoolYear')->findOneById($ps_class_id);
	// $start_at = $query->getFromDate() ? $query->getFromDate() : date('Ymd');
	// $stop_at = $query->getToDate() ? $query->getToDate() : date('Ymd');

	// $q = $this->createQuery('c')
	// ->select('c.id AS id,c.is_parent AS is_parent,c.relative_id AS relative_id,c.student_id as student_id, rs.title AS title,CONCAT(s.first_name, " ", s.last_name) AS student_name,s.image AS image, s.year_data AS year_data, cus.school_code AS school_code, sc.id AS sc_id, mc.id AS mc_id, mc.name AS mc_title,scy.id AS scy_id, scy.title AS scy_title')
	// ->innerJoin('c.Student s')
	// //
	// ->innerJoin('s.StudentClass sc With (DATE_FORMAT(sc.start_at,"%Y%m%d") <= ? AND (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= ?))', array($start_at, $stop_at) )
	// ->innerJoin('sc.MyClass mc')
	// ->innerJoin('mc.PsSchoolYear scy')
	// //
	// ->innerJoin('s.PsCustomer cus')
	// ->leftJoin('c.Relationship rs')
	// ->addWhere('s.deleted_at IS NULL')
	// ->addWhere('c.relative_id = ? AND mc.id = ?', array(
	// $relative_id,
	// $ps_class_id
	// ))
	// ->orderBy('c.is_parent_main DESC');

	// return $q->execute();

	// }

	/**
	 * Lay danh sach hoc sinh co moi quan he voi phu huynh theo ma phu huynh va ma truong
	 *
	 * @author Pham Van Thien
	 *        
	 * @edit by: Nguyen Chien Thang
	 *
	 * @param
	 *        	int relative_id,ps_customer_id
	 * @return Obj
	 *
	 *
	 */
	public function findStudentsByRelativeId($relative_id, $ps_customer_id) {

		$q = $this->createQuery ( 'c' )
			->select ( 'c.id AS id,c.is_parent AS is_parent,c.relative_id AS relative_id,c.student_id as student_id, rs.title AS title,CONCAT(s.first_name, " ", s.last_name) AS student_name,s.image AS image, s.year_data AS year_data, cus.school_code AS school_code,sc.id AS sc_id, mc.id AS mc_id, mc.name AS mc_title,scy.id AS scy_id, scy.title AS scy_title, s.birthday AS student_brithday, s.sex AS student_sex, sc.type AS student_type' )
			->addSelect ( 'sc.is_activated AS is_activated' )
			->innerJoin ( 'c.Student s' )
			->leftJoin ( 's.StudentClass sc' )
			->leftJoin ( 'sc.MyClass mc' )
			->leftJoin ( 'mc.PsSchoolYear scy' )
			->innerJoin ( 's.PsCustomer cus' )
			->leftJoin ( 'c.Relationship rs' )
			->addWhere ( 's.deleted_at IS NULL' )
			->addWhere ( 'c.relative_id = ? AND s.ps_customer_id = ?', array (
				$relative_id,
				$ps_customer_id ) )
			->orderBy ( 'mc.school_year_id DESC, sc.id DESC, c.is_parent_main DESC' );

		return $q->execute ();
	}

	// Kiem tra xem phu hunh con quan he voi hoc sinh nao dang theo hoc thoi gian hien tai hay khong
	public function getRelativeStudentsByRelativeId($relative_id, $student_id) {
		//echo $student_id; die;
		$date = date("Ymd");
		$q = $this->createQuery ( 'c' )->select ( 'c.id AS id' )
		->innerJoin ( 'c.Student s' )
		->innerJoin ('s.StudentClass sc')
		->addWhere ( 's.deleted_at IS NULL' )
		->addWhere ( 'c.relative_id = ?', $relative_id)
		->andWhere ( 'c.student_id != ?', $student_id)
		->andWhere( '(DATE_FORMAT(sc.start_at,"%Y%m%d") <= ? AND (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= ?))', array (
				$date,
				$date ) )
		->andWhereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) )
		->orderBy ( 'c.is_parent_main DESC' );
		
		return $q->fetchOne ();
	}
	
	/**
	 * Lay danh sach nguoi than co moi quan he voi hoc sinh theo ma hoc sinh va ma truong
	 *
	 * @author Pham Van Thien
	 *        
	 * @param
	 *        	int student_id,ps_customer_id
	 * @return Obj
	 *
	 *
	 */
	public function findByStudentId($student_id = '', $ps_customer_id = '') {

		$q = $this->createQuery ( 'c' )
			->select ( 'c.id AS id,c.relative_id AS relative_id, r.sex as sex, r.id as r_id,
			    c.student_id as student_id, rs.title AS title,CONCAT(r.first_name, " ", r.last_name) AS full_name,
			    r.phone AS phone,r.birthday AS relative_birthday,r.phone AS phone,r.mobile AS mobile,r.email AS email,
			    r.image AS image, c.is_role as role_avatar, r.year_data as year_data ,
	           c.role_service as role_service, c.is_parent AS is_parent, c.is_parent_main AS is_parent_main,
	           u.username AS user_name, u.username AS user_pass_word, u.id as user_id' )
			->innerJoin ( 'c.Relative r WITH r.deleted_at IS NULL' )
			->
		// ->leftJoin('r.sfGuardUser u WITH (u.user_type =? AND u.is_active <>?)', array(PreSchool::USER_TYPE_RELATIVE, PreSchool::CUSTOMER_LOCK))
		leftJoin ( 'r.sfGuardUser u WITH (u.user_type =?)', array (
				PreSchool::USER_TYPE_RELATIVE ) )
			->leftJoin ( 'c.Relationship rs WITH rs.is_activated = ?', PreSchool::ACTIVE )
			->
		// ->addWhere('rs.is_activated = ?', PreSchool::ACTIVE)
		addWhere ( 'c.student_id = ? AND r.ps_customer_id = ?', array (
				$student_id,
				$ps_customer_id ) )
			->orderBy ( 'c.iorder ASC,c.is_parent_main DESC' );

		return $q->execute ();
	}

	/**
	 *	lay danh relative_id co tai khoan dang kich hoat
	 **/
	public function getRelativeByStudentId($student_id = '', $ps_customer_id = '') {
		
		$q = $this->createQuery ( 'c' )
		->select ( 'c.relative_id AS relative_id' )
	    ->innerJoin ( 'c.Relative r WITH r.deleted_at IS NULL' )
	    ->innerJoin ( 'r.sfGuardUser u WITH (u.user_type =? AND u.is_active =?)', array (PreSchool::USER_TYPE_RELATIVE, PreSchool::ACTIVE ) )
		->addWhere ( 'c.student_id = ? AND r.ps_customer_id = ?', array (
				$student_id,
				$ps_customer_id ) )
				
		->orderBy ( 'c.iorder ASC,c.is_parent_main DESC' );
				
		return $q->execute ();
	}
	
	
	/**
	 * Lay danh sach nguoi than co moi quan he voi hoc sinh theo ma hoc sinh va ma truong, co quyen dua don chinh
	 *
	 * @author Pham Van Thien
	 *        
	 * @param int student_id,ps_customer_id
	 * @return Obj
	*/
	public function findMainParentsByStudentId($student_id = '', $ps_customer_id = '') {

		$q = $this->createQuery ( 'c' )
			->select ( 'c.id AS id,c.relative_id AS relative_id, r.sex as sex, r.id as r_id,
			    c.student_id as student_id, rs.title AS title,CONCAT(r.first_name, " ", r.last_name) AS full_name,
			    r.phone AS phone,r.birthday AS relative_birthday,r.phone AS phone,r.mobile AS mobile,r.email AS email,
			    r.image AS image, c.is_role as role_avatar, r.year_data as year_data ,
	           c.role_service as role_service, c.is_parent AS is_parent, c.is_parent_main AS is_parent_main,
	           u.username AS user_name, u.username AS user_pass_word' )
			->innerJoin ( 'c.Relative r  WITH r.deleted_at IS NULL' )
			->leftJoin ( 'c.Relationship rs' )
			->leftJoin ( 'r.sfGuardUser u WITH (u.user_type =? AND u.is_active <>?)', array (
				PreSchool::USER_TYPE_RELATIVE,
				PreSchool::CUSTOMER_LOCK ) )
			->addWhere ( 'c.is_parent_main = ?', PreSchool::ACTIVE )
			->addWhere ( 'rs.is_activated = ?', PreSchool::ACTIVE )
			->addWhere ( 'c.student_id = ? AND r.ps_customer_id = ?', array (
				$student_id,
				$ps_customer_id ) )
			->orderBy ( 'c.is_parent_main DESC' );

		return $q->execute ();
	}

	/**
	 * Lay SQL danh sach nguoi than co moi quan he voi hoc sinh theo ma hoc sinh va ma truong
	 *
	 * @author Pham Van Thien
	 *        
	 * @param
	 *        	int student_id,
	 * @param
	 *        	ps_customer_id
	 * @return sql
	 */
	public function sqlFindByStudentId($student_id, $ps_customer_id = '') {

		$q = $this->createQuery ( 'c' )
			->select ( 'c.relative_id as id,r.image AS image, CONCAT(rs.title,": ",r.first_name, " ", r.last_name) AS title' )
			->innerJoin ( 'c.Relative r' )
			->leftJoin ( 'c.Relationship rs' )
			->orderBy ( 'c.is_parent_main DESC' )
			->andWhere ( 'r.deleted_at IS NULL' )
			->addWhere ( 'c.student_id = ? AND r.ps_customer_id = ?', array (
				$student_id,
				$ps_customer_id ) );

		return $q;
	}

	public function sqlFindAllRelativeByStudent($student_id, $ps_customer_id = '') {

		$q = $this->createQuery ( 'c' )
			->select ( 'c.relative_id as id,CONCAT(rs.title,": ",r.first_name, " ", r.last_name) AS title' )
			->leftJoin ( 'c.Relative r' )
			->leftJoin ( 'c.Relationship rs' )
			->orderBy ( 'c.is_parent_main DESC' )
			->andWhere ( 'r.deleted_at IS NULL' )
			->addWhere ( 'c.student_id = ? AND r.ps_customer_id = ?', array (
				$student_id,
				$ps_customer_id ) );

		return $q;
	}

	// Lay 1 nguoi than cua hoc sinh
	public function sqlFindOneByStudentId($student_id, $ps_customer_id = '') {

		$q = $this->createQuery ( 'c' )
			->select ( 'c.id, c.relative_id as relative_id,CONCAT(rs.title,": ",r.first_name, " ", r.last_name) AS title' )
			->innerJoin ( 'c.Relative r' )
			->leftJoin ( 'c.Relationship rs' )
			->addWhere ( 'c.student_id = ? AND r.ps_customer_id = ?', array (
				$student_id,
				$ps_customer_id ) );

		$q->orderBy ( 'c.is_parent DESC, c.is_parent_main DESC' );

		return $q->fetchOne ();
	}

	/**
	 * Lay danh sach nguoi than hoc sinh da kich hoat tai khoan nguoi dung
	 */
	public function getRelativeActiveAccount($customer_id, $workplace_id = null) {

		$date_at = date ( 'Ymd' );
		$q = $this->createQuery ( 'rs' )
			->select ( 'rs.id, r.id, u.id AS id, u.app_device_id AS app_device_id, u.api_token AS api_token' );

		$q->innerJoin ( 'rs.Relative r WITH r.deleted_at IS NULL' );
		$q->leftJoin ( 'r.sfGuardUser u WITH (u.user_type =? AND u.is_active = ?)', array (
				PreSchool::USER_TYPE_RELATIVE,
				PreSchool::CUSTOMER_ACTIVATED ) );

		$q->andWhere ( 'u.ps_customer_id =?', $customer_id );
		$q->innerJoin ( 'rs.Student s WITH s.deleted_at IS NULL' );
		$q->innerJoin ( 's.StudentClass sc WITH DATE_FORMAT(sc.start_at, "%Y%m%d") <=? AND (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at, "%Y%m%d") >=?)', array (
				$date_at,
				$date_at ) );

		$q->andWhereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );
		if (isset ( $workplace_id )) {
			if (is_array ( $workplace_id )) {
				$q->andWhereIn ( 'r.ps_workplace_id', $workplace_id );
			} else {
				$q->andWhere ( 'r.ps_workplace_id =? ', $workplace_id );
			}
		}
		return $q->execute ();
	}
}