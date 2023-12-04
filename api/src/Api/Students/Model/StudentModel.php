<?php

/**
 *
 * @package truongnet.com
 * @subpackage API app
 *            
 *             @file StudentModel.php
 * @author thangnc
 * @version 1.0 27-02-2017 - 00:51:34
 *         
 */
namespace Api\Students\Model;

// use Illuminate\Database\Eloquent\Model;
use App\Model\BaseModel;
use App\PsUtil\PsDateTime;
use App\PsUtil\PsString;

class StudentModel extends BaseModel {

	protected $table = CONST_TBL_STUDENT;

	public static function getStudentByID($student_id) {

		return StudentModel::where ( 'id', $student_id )->get ()->first ();
	}

	/**
	 * Ham tra ve thong tin hoc sinh co ban cua hoc sinh
	 */
	public static function getStudentInfoByID($student_id) {

		return StudentModel::select ( 'first_name', 'last_name', 'id', 'ps_customer_id' )->where ( 'id', $student_id )
			->get ()
			->first ();
	}

	/**
	 * Ham tra ve thong tin co ban cua hoc sinh, moi quan he giua hoc sinh - nguoi than - user - lop hoc
	 */
	public static function getStudentUserByIdAndMemberId($student_id, $relative_id) {

		// Kiem tra co ton tai moi quan he giua User - Student - Nguoi than
		$tbl = CONST_TBL_STUDENT;

		$curr_day = date ( 'Y-m-d' );

		$ps_student = StudentModel::select ( $tbl . '.id', $tbl . '.first_name', $tbl . '.last_name', $tbl . '.birthday', 'RS.role_service', 'CL.name AS class_name', 'CR.id as ps_class_room_id', 'CL.id AS class_id', 'CL.ps_obj_group_id', $tbl . '.ps_customer_id', $tbl . '.avatar', $tbl . '.year_data', 'C.cache_data', 'W.id as ps_workplace_id', 'LT.id as logtime_id', 'LT.login_at as login_at', 'LT.logout_at as logout_at', 'LT.login_relative_id as login_relative_id', 'LT.logout_relative_id as logout_relative_id' )->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )
			->join ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.student_id', '=', $tbl . '.id' )
			->join ( CONST_TBL_USER . ' as User', 'User.member_id', '=', 'RS.relative_id' )
			->leftJoin ( CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($curr_day, $tbl) {
			$q->on ( 'SC.student_id', '=', $tbl . '.id' )
				->where ( 'SC.is_activated', STATUS_ACTIVE )
				->whereIn ( 'SC.type', [ 
					STUDENT_HT,
					STUDENT_CT ] )
				->whereDate ( 'SC.start_at', '<=', $curr_day )
				->whereRaw ( '(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $curr_day . '", "%Y%m%d") OR SC.stop_at IS NULL )' );
		} )
			->leftJoin ( CONST_TBL_MYCLASS . ' as CL', function ($q) {
			$q->on ( 'CL.id', '=', 'SC.myclass_id' )
				->where ( 'CL.is_activated', STATUS_ACTIVE );
		} )
			->leftJoin ( TBL_PS_CLASS_ROOMS . ' as CR', function ($q) {
			$q->on ( 'CR.id', '=', 'CL.ps_class_room_id' )
				->where ( 'CR.is_activated', STATUS_ACTIVE );
		} )
			->leftJoin ( TBL_PS_WORK_PLACES . ' as W', function ($q) {
			$q->on ( 'W.id', '=', 'CR.ps_workplace_id' )
				->where ( 'W.is_activated', STATUS_ACTIVE );
		} )
			->leftJoin ( CONST_TBL_PS_LOGTIMES . ' as LT', function ($q) use ($curr_day, $tbl) {
			$q->on ( 'LT.student_id', '=', $tbl . '.id' )
				->whereDate ( 'LT.login_at', '=', date ( 'Y-m-d', strtotime ( $curr_day ) ) );
		} )
			->where ( $tbl . '.id', '=', $student_id )
			->whereRaw ( $tbl . '.deleted_at IS NULL' )
			->where ( 'RS.relative_id', '=', $relative_id )
			->get ()
			->first ();

		return $ps_student;
	}

	/**
	 * Ham tra ve thong tin co ban cua hoc sinh danh cho nguoi than.
	 * Du hoc sinh nay da nghi hoc hay van con hoc
	 *
	 * @author thangnc
	 * @param $student_id -
	 *        	int
	 * @param $relative_id -
	 *        	int
	 *        	
	 * @return $obj
	 */
	public static function getStudentForRelative($student_id, $relative_id) {

		// Kiem tra co ton tai moi quan he giua User - Student - Nguoi than
		$tbl = CONST_TBL_STUDENT;

		// $curr_day = date('Y-m-d');

		//$ps_student = StudentModel::select ( $tbl . '.id as id', $tbl.'.ps_customer_id as ps_customer_id', $tbl . '.birthday as birthday', $tbl . '.first_name as first_name', $tbl . '.sex as sex',$tbl . '.last_name as last_name', $tbl . '.avatar as avatar', $tbl . '.year_data AS s_year_data', 'RS.is_role', 'RS.role_service', 'CL.name AS class_name', 'CL.id AS class_id', 'CL.ps_obj_group_id', 'CL.school_year_id', 'RE.title as religion', 'E.title as ethnic', 'C.school_name', 'C.year_data', 'C.logo', 'C.cache_data', 'CL.ps_workplace_id as ps_workplace_id', 'W.title as wp_name', 'W.config_start_date_system_fee AS date_system_fee', 'SC.type AS type' )
		//$ps_student = StudentModel::select ( $tbl . '.*', $tbl . '.ps_customer_id AS ps_customer_id' ,$tbl . '.year_data AS s_year_data', 'RS.is_role', 'RS.role_service', 'CL.name AS class_name', 'CL.id AS class_id', 'CL.ps_obj_group_id', 'CL.school_year_id', 'RE.title as religion', 'E.title as ethnic', 'C.school_name', 'C.year_data', 'C.logo', 'C.cache_data', 'CL.ps_workplace_id as ps_workplace_id', 'W.title as wp_name', 'W.config_start_date_system_fee AS date_system_fee', 'SC.type AS type' )
		
		$ps_student = StudentModel::select ( $tbl . '.id as id', $tbl.'.ps_customer_id as ps_customer_id', $tbl . '.birthday as birthday', $tbl . '.first_name as first_name', $tbl . '.sex as sex',$tbl . '.last_name as last_name', $tbl . '.avatar as avatar', $tbl . '.year_data AS s_year_data', 'RS.is_role', 'RS.role_service', 'CL.name AS class_name', 'CL.id AS class_id', 'CL.ps_obj_group_id', 'CL.school_year_id', 'RE.title as religion', 'E.title as ethnic', 'C.school_name', 'C.year_data', 'C.logo','C.school_code' , 'C.school_code as cache_data', 'CL.ps_workplace_id as ps_workplace_id', 'W.title as wp_name', 'W.config_start_date_system_fee AS date_system_fee', 'SC.type AS type' )
		->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )
		
			->join ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.student_id', '=', $tbl . '.id' )
			->join ( CONST_TBL_USER . ' as User', 'User.member_id', '=', 'RS.relative_id' )
			->leftJoin ( CONST_TBL_STUDENTCLASS . ' as SC', 'SC.student_id', '=', $tbl . '.id' )
            
            /*
            leftJoin(CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($tbl) {
            $q->on('SC.student_id', '=', $tbl . '.id');
                //->where('SC.is_activated', STATUS_ACTIVE);
                //->whereIn('SC.type', [STUDENT_HT,STUDENT_CT])
                //->whereDate('SC.start_at', '<=', $curr_day)
                //->whereRaw('(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $curr_day . '", "%Y%m%d") OR SC.stop_at IS NULL )');
            })
            */
            ->leftJoin ( CONST_TBL_MYCLASS . ' as CL', function ($q) {
			$q->on ( 'CL.id', '=', 'SC.myclass_id' )
				->where ( 'CL.is_activated', STATUS_ACTIVE );
			} )
			//->leftJoin ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'CL.ps_class_room_id' )
			
			->leftJoin ( TBL_PS_WORK_PLACES . ' as W', function ($q) {
			$q->on ( 'W.id', '=', 'CL.ps_workplace_id' )
				->where ( 'W.is_activated', STATUS_ACTIVE );
		} )
			->leftJoin ( CONST_TBL_PS_ETHNIC . ' as E', 'E.id', '=', $tbl . '.ethnic_id' )
			->leftJoin ( CONST_TBL_PS_RELIGION . ' as RE', 'RE.id', '=', $tbl . '.religion_id' )
			->where ( $tbl . '.id', '=', $student_id )
			->whereRaw ( $tbl . '.deleted_at IS NULL' )
			->where ( 'RS.relative_id', '=', $relative_id )
			->orderByRaw ( 'CL.school_year_id DESC,SC.start_at DESC' )
			->get ()
			->first ();

		return $ps_student;
	}
	
	/**
	 * Ham tra ve thong tin co ban day du cua hoc sinh danh cho nguoi than.
	 * Du hoc sinh nay da nghi hoc hay van con hoc
	 *
	 * @author thangnc
	 * @param $student_id -
	 *        	int
	 * @param $relative_id -
	 *        	int
	 *
	 * @return $obj
	 */
	public static function getStudentInfoForRelative($student_id, $relative_id) {
		
		// Kiem tra co ton tai moi quan he giua User - Student - Nguoi than
		$tbl = CONST_TBL_STUDENT;
		
		$ps_student = StudentModel::select ( $tbl . '.*', $tbl . '.year_data AS s_year_data', 'RS.is_role', 'RS.role_service', 'CL.name AS class_name', 'CL.id AS class_id', 'CL.ps_obj_group_id', 'CL.school_year_id', 'RE.title as religion', 'E.title as ethnic', 'C.school_name', 'C.year_data', 'C.logo', 'C.cache_data', 'CL.ps_workplace_id as ps_workplace_id', 'W.title as wp_name', 'W.config_start_date_system_fee AS date_system_fee', 'SC.type AS type' )
		
		->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )
		->join ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.student_id', '=', $tbl . '.id' )
		->join ( CONST_TBL_USER . ' as User', 'User.member_id', '=', 'RS.relative_id' )
		->leftJoin ( CONST_TBL_STUDENTCLASS . ' as SC', 'SC.student_id', '=', $tbl . '.id' )
		->leftJoin ( CONST_TBL_MYCLASS . ' as CL', function ($q) {
			$q->on ( 'CL.id', '=', 'SC.myclass_id' )
			->where ( 'CL.is_activated', STATUS_ACTIVE );
		} )
		->leftJoin ( TBL_PS_WORK_PLACES . ' as W', function ($q) {
				$q->on ( 'W.id', '=', 'CL.ps_workplace_id' )
				->where ( 'W.is_activated', STATUS_ACTIVE );
		} )
		->leftJoin ( CONST_TBL_PS_ETHNIC . ' as E', 'E.id', '=', $tbl . '.ethnic_id' )
		->leftJoin ( CONST_TBL_PS_RELIGION . ' as RE', 'RE.id', '=', $tbl . '.religion_id' )
		->where ( $tbl . '.id', '=', $student_id )
		->whereRaw ( $tbl . '.deleted_at IS NULL' )
		->where ( 'RS.relative_id', '=', $relative_id )
		->orderByRaw ( 'CL.school_year_id DESC,SC.start_at DESC' )
		->get ()
		->first ();
			
		return $ps_student;
	}

	/**
	 * Ham tra ve thong tin co ban cua hoc sinh danh cho nguoi than - BackUp
	 *
	 * @author thangnc
	 * @param $student_id -
	 *        	int
	 * @param $relative_id -
	 *        	int
	 *        	
	 * @return $obj
	 */
	public static function getStudentForRelativeBAK($student_id, $relative_id) {

		// Kiem tra co ton tai moi quan he giua User - Student - Nguoi than
		$tbl = CONST_TBL_STUDENT;

		$curr_day = date ( 'Y-m-d' );

		$ps_student = StudentModel::select ( $tbl . '.*', $tbl . '.year_data AS s_year_data', 'RS.is_role', 'RS.role_service', 'CL.name AS class_name', 'CL.id AS class_id', 'CL.ps_obj_group_id', 'CL.school_year_id', 'RE.title as religion', 'E.title as ethnic', 'C.school_name', 'C.year_data', 'C.logo', 'C.cache_data', 'W.id as ps_workplace_id', 'W.title as wp_name', 'W.config_start_date_system_fee AS date_system_fee' )->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )
			->join ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.student_id', '=', $tbl . '.id' )
			->join ( CONST_TBL_USER . ' as User', 'User.member_id', '=', 'RS.relative_id' )
			->leftJoin ( CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($curr_day, $tbl) {
			$q->on ( 'SC.student_id', '=', $tbl . '.id' )
				->where ( 'SC.is_activated', STATUS_ACTIVE );
			// ->whereIn('SC.type', [STUDENT_HT,STUDENT_CT])
			// ->whereDate('SC.start_at', '<=', $curr_day)
			// ->whereRaw('(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $curr_day . '", "%Y%m%d") OR SC.stop_at IS NULL )');
		} )
			->leftJoin ( CONST_TBL_MYCLASS . ' as CL', function ($q) {
			$q->on ( 'CL.id', '=', 'SC.myclass_id' )
				->where ( 'CL.is_activated', STATUS_ACTIVE );
		} )
			->
		leftJoin ( TBL_PS_CLASS_ROOMS . ' as CR', 'CR.id', '=', 'CL.ps_class_room_id' )
			->
		leftJoin ( TBL_PS_WORK_PLACES . ' as W', function ($q) {
			$q->on ( 'W.id', '=', 'CR.ps_workplace_id' )
				->where ( 'W.is_activated', STATUS_ACTIVE );
		} )
			->leftJoin ( CONST_TBL_PS_ETHNIC . ' as E', 'E.id', '=', $tbl . '.ethnic_id' )
			->leftJoin ( CONST_TBL_PS_RELIGION . ' as RE', 'RE.id', '=', $tbl . '.religion_id' )
			->where ( $tbl . '.id', '=', $student_id )
			->whereRaw ( $tbl . '.deleted_at IS NULL' )
			->where ( 'RS.relative_id', '=', $relative_id )
			->orderByRaw ( 'SC.start_at DESC' )
			->get ()
			->first ();

		return $ps_student;
	}

	/**
	 * Ham tra ve thong tin co ban cua hoc sinh danh cho giao vien (Chi giao vien cung truong moi co quyen xem)
	 *
	 * @author thangnc
	 * @param $student_id -
	 *        	int
	 * @param $ps_customer_id -
	 *        	int
	 *        	
	 * @return $obj
	*/
	public static function getStudentForTeacher($student_id, $ps_customer_id) {

		$tbl = CONST_TBL_STUDENT;
		
		$curr_day = date ( 'Y-m-d' );

		$ps_student = StudentModel::select ( $tbl . '.*', $tbl . '.year_data AS s_year_data', 'RS.is_role AS 0', 'RS.role_service AS 0', 'CL.name AS class_name', 'CL.id AS class_id', 'CL.ps_obj_group_id', 'RE.title as religion', 'E.title as ethnic', 'C.school_name', 'C.year_data', 'C.logo', 'C.cache_data', 'W.title as wp_name' )->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )
		->leftJoin ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.student_id', '=', $tbl . '.id' )
		->leftJoin ( CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($curr_day, $tbl) {
			$q->on ( 'SC.student_id', '=', $tbl . '.id' )
				->where ( 'SC.is_activated', STATUS_ACTIVE )
				->whereIn ( 'SC.type', [ 
					STUDENT_HT,
					STUDENT_CT ] )
				->whereDate ( 'SC.start_at', '<=', $curr_day )
				->whereRaw ( '(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $curr_day . '", "%Y%m%d") OR SC.stop_at IS NULL )' );
		} )
		->leftJoin ( CONST_TBL_MYCLASS . ' as CL', function ($q) {
			$q->on ( 'CL.id', '=', 'SC.myclass_id' )
				->where ( 'CL.is_activated', STATUS_ACTIVE );
		} )
		->leftJoin ( TBL_PS_WORK_PLACES . ' as W', function ($q) {
			$q->on ( 'W.id', '=', 'CL.ps_workplace_id' )
				->where ( 'W.is_activated', STATUS_ACTIVE );
		} )
		->leftJoin ( CONST_TBL_PS_ETHNIC . ' as E', 'E.id', '=', $tbl . '.ethnic_id' )
			->leftJoin ( CONST_TBL_PS_RELIGION . ' as RE', 'RE.id', '=', $tbl . '.religion_id' )
			->where ( $tbl . '.id', '=', $student_id )
			->whereRaw ( $tbl . '.deleted_at IS NULL' )
			->where ( $tbl . '.ps_customer_id', '=', $ps_customer_id )
			->get ()
			->first ();

		return $ps_student;
	}
	
	/**
	 * Ham tra ve thong tin co ban ngan gon cua hoc sinh(ho ten, lop hien tai, co so, truong, phu huynh theo id...)
	 * danh cho giao vien (Chi giao vien cung truong moi co quyen xem)
	 *
	 * @author thangnc 
	 * @param $student_id - int
	 * @param $ps_customer_id - id int
	 *
	 * @return mixed
	 */
	public static function getStudentInfoShortForTeacher($student_id, $ps_customer_id, $relative_id) {
		
		$tbl = CONST_TBL_STUDENT;
		
		$ps_student = StudentModel::select ( $tbl . '.first_name AS first_name',$tbl . '.last_name AS last_name', $tbl . '.year_data AS s_year_data', 'CL.name AS class_name', 'CL.id AS class_id', 'C.school_name', 'C.year_data', 'C.logo', 'C.cache_data', 'W.title as wp_name' )
		->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )
		->join ( CONST_TBL_RELATIVESTUDENT . ' as RS', 'RS.student_id', '=', $tbl . '.id' )
		->join ( CONST_TBL_RELATIVE . ' as R', 'R.id', '=','RS.relative_id' )
		->join ( CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($tbl) {
			$q->on ( 'SC.student_id', '=', $tbl . '.id' )
			->where ( 'SC.is_activated', STATUS_ACTIVE )
			->whereIn ( 'SC.type', [
					STUDENT_HT,
					STUDENT_CT ] );
		} )
		->join ( CONST_TBL_MYCLASS . ' as CL', function ($q) {
			$q->on ( 'CL.id', '=', 'SC.myclass_id' )
			->where ( 'CL.is_activated', STATUS_ACTIVE );
		} )
		->join ( TBL_PS_WORK_PLACES . ' as W', function ($q) {
			$q->on ( 'W.id', '=', 'CL.ps_workplace_id' )
			->where ( 'W.is_activated', STATUS_ACTIVE );
		} )
		->where ( $tbl . '.id', '=', $student_id )
		->where ( 'R.id', '=', $relative_id )
		->whereRaw ( $tbl . '.deleted_at IS NULL' )
		->where ( $tbl . '.ps_customer_id', '=', $ps_customer_id )
		->get ()
		->first ();
		
		return $ps_student;
	}
	
	public static function getStudentInfoShortForTeacher2($student_id, $ps_customer_id) {
		
		$tbl = CONST_TBL_STUDENT;
		
		$ps_student = StudentModel::select ( $tbl . '.first_name AS first_name',$tbl . '.last_name AS last_name', $tbl . '.year_data AS s_year_data', 'CL.name AS class_name', 'CL.id AS class_id', 'C.school_name', 'C.year_data', 'C.logo', 'C.cache_data', 'W.title as wp_name' )
		->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )
		->join ( CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($tbl) {
			$q->on ( 'SC.student_id', '=', $tbl . '.id' )
			->where ( 'SC.is_activated', STATUS_ACTIVE )
			->whereIn ( 'SC.type', [
					STUDENT_HT,
					STUDENT_CT ] );
		} )
		->join ( CONST_TBL_MYCLASS . ' as CL', function ($q) {
			$q->on ( 'CL.id', '=', 'SC.myclass_id' )
			->where ( 'CL.is_activated', STATUS_ACTIVE );
		} )
		->join ( TBL_PS_WORK_PLACES . ' as W', function ($q) {
			$q->on ( 'W.id', '=', 'CL.ps_workplace_id' )
			->where ( 'W.is_activated', STATUS_ACTIVE );
		} )
		->where ( $tbl . '.id', '=', $student_id )
		->whereRaw ( $tbl . '.deleted_at IS NULL' )
		->where ( $tbl . '.ps_customer_id', '=', $ps_customer_id )
		->get ()
		->first ();
		
		return $ps_student;
	}

	/**
	 * Ham tra ve danh sach hoc sinh cua lop tinh theo thoi diem
	 *
	 * @author thangnc
	 * @param $class_id -
	 *        	int
	 * @param $date_at -
	 *        	YYYY-mm-dd
	 *        	
	 * @return $obj
	 */
	public static function getStudentsOfClass($class_id, $date_at) {

		$tbl = CONST_TBL_STUDENT;

		// Lay danh sach hoc sinh co mat hom nay theo giao vien
		$students = StudentModel::select ( $tbl . '.id as student_id', $tbl . '.nick_name as nickname', $tbl . '.birthday as birthday', $tbl . '.first_name as first_name', $tbl . '.last_name as last_name', $tbl . '.avatar as avatar', $tbl . '.year_data', $tbl . '.ps_customer_id as ps_customer_id', 'C.logo', 'C.cache_data' )
		->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )
		->join ( CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($date_at, $tbl) {
			$q->on ( 'SC.student_id', '=', $tbl . '.id' )
				->where ( 'SC.is_activated', STATUS_ACTIVE )
				->whereIn ( 'SC.type', [ 
					STUDENT_HT,
					STUDENT_CT ] )
				->whereDate ( 'SC.start_at', '<=', $date_at )
				->whereRaw ( '(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $date_at . '", "%Y%m%d") OR SC.stop_at IS NULL )' );
		} )
		->join ( CONST_TBL_MYCLASS . ' as M', 'M.id', '=', 'SC.myclass_id' )
		->where ( 'M.id', $class_id )
		->whereRaw ( $tbl . '.deleted_at IS NULL' )
		->where ( 'M.is_activated', STATUS_ACTIVE )
		->orderBy ( $tbl . '.last_name' )
		->get ();

		return $students;
	}
	
	/**
	 * Ham tra ve danh sach hoc sinh + trang thai diem danh cua lop tinh theo thoi diem
	 *
	 * @author thangnc
	 * @param $class_id int
	 * @param $date_at string YYYY-mm-dd
	 *        	
	 *
	 * @return $obj
	 */
	public static function getStudentsStatusAttendanceOfClass($class_id, $date_at) {
		
		$tbl = CONST_TBL_STUDENT;
		
		// Lay danh sach hoc sinh co mat hom nay
		$students = StudentModel::select ( $tbl . '.id as student_id', $tbl . '.nick_name as nickname', $tbl . '.birthday as birthday', $tbl . '.first_name as first_name', $tbl . '.last_name as last_name', $tbl . '.avatar as avatar', $tbl . '.year_data', 'C.cache_data', 'LT.id AS logtime_id', 'LT.log_value AS log_value')
		->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )
		->join ( CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($date_at, $tbl) {
			$q->on ( 'SC.student_id', '=', $tbl . '.id' )
			->where ( 'SC.is_activated', STATUS_ACTIVE )
			->whereIn ( 'SC.type', [
					STUDENT_HT,
					STUDENT_CT ] )
					->whereDate ( 'SC.start_at', '<=', $date_at )
					->whereRaw ( '(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $date_at . '", "%Y%m%d") OR SC.stop_at IS NULL )' );
		} )
		->join ( CONST_TBL_MYCLASS . ' as MC', 'MC.id', '=', 'SC.myclass_id' )
		->leftJoin ( CONST_TBL_PS_LOGTIMES . ' as LT', function ($q) use ($tbl,$date_at) {
			$q->on ( 'LT.student_id', '=', $tbl.'.id' )
			->whereDate ( 'LT.login_at', '=', $date_at);
		} )
		->where ( 'MC.id', $class_id )
		->where ( 'MC.is_activated', STATUS_ACTIVE )
		->whereRaw ( $tbl . '.deleted_at IS NULL' )
		->orderBy ( $tbl . '.last_name' )
		->get ();
		
		return $students;
	}
	
	/**
	 * Ham tra ve danh sach hoc sinh cua lop da qua lưu trong bảng điểm danh diem danh tai mot thoi diem
	 *
	 * @author thangnc
	 * @param $class_id -
	 *        	int
	 * @param $date_at -
	 *        	YYYY-mm-dd
	 *
	 * @return $obj
	 */
	public static function getStudentsByLogValueOfClass($class_id, $date_at, $log_value = 0) {
		
		$tbl = CONST_TBL_STUDENT;
		
		// Lay danh sach hoc sinh co mat hom nay theo giao vien
		$students = StudentModel::select ( $tbl . '.id as student_id', $tbl . '.nick_name as nickname', $tbl . '.birthday as birthday', $tbl . '.first_name as first_name', $tbl . '.last_name as last_name', $tbl . '.avatar as avatar', $tbl . '.year_data', $tbl . '.ps_customer_id as ps_customer_id', 'C.logo', 'C.cache_data' )
		->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )
		->join ( CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($date_at, $tbl) {
			$q->on ( 'SC.student_id', '=', $tbl . '.id' )
			->where ( 'SC.is_activated', STATUS_ACTIVE )
			->whereIn ( 'SC.type', [
					STUDENT_HT,
					STUDENT_CT ] )
					->whereDate ( 'SC.start_at', '<=', $date_at )
					->whereRaw ( '(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $date_at . '", "%Y%m%d") OR SC.stop_at IS NULL )' );
		} )
		->join ( CONST_TBL_MYCLASS . ' as M', 'M.id', '=', 'SC.myclass_id' )
		->join ( CONST_TBL_PS_LOGTIMES . ' as L', 'L.student_id', '=', $tbl . '.id' )
		->where ( 'M.id', $class_id )
		->whereDate ( 'L.login_at', $date_at )
		->where ( 'L.log_value', STATUS_ACTIVE )
		->whereRaw ( $tbl . '.deleted_at IS NULL' )
		->where ( 'M.is_activated', STATUS_ACTIVE )
		->orderBy ( $tbl . '.last_name' )
		->get ();
		
		return $students;
	}

	/**
	 * getStudentsOfServiceCourse($ps_service_course_schedules_id, $date_at)
	 *
	 * Ham tra ve danh sach hoc sinh cua mot khoa hoc
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param $ps_service_course_schedules_id -
	 *        	int , ID cua lich hoc
	 * @param $date_at -
	 *        	ngay lay du lieu
	 *        	
	 * @return $list
	 */
	public static function getStudentsOfServiceCourse($ps_service_course_schedules_id, $date_at) {

		$tbl = CONST_TBL_STUDENT;

		$curr_day = $date_at;

		// Lay danh sach hoc sinh co mat hom nay theo giao vien
		$students = StudentModel::select ( $tbl . '.id as student_id', $tbl . '.nick_name as nickname', $tbl . '.birthday as birthday', $tbl . '.first_name as first_name', $tbl . '.last_name as last_name', $tbl . '.avatar as avatar', $tbl . '.year_data', $tbl . '.ps_customer_id as ps_customer_id', 'C.logo', 'C.cache_data' )->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id' )
			->
		join ( CONST_TBL_STUDENT_SERVICE . ' as SS', 'SS.student_id', '=', $tbl . '.id' )
			->join ( TBL_PS_SERVICE_COURSES . ' as SEC', 'SEC.id', '=', 'SS.ps_service_course_id' )
			->join ( TBL_PS_SERVICE_COURSES_SCHEDULES . ' as SCS', 'SCS.ps_service_course_id', '=', 'SEC.id' )
			->join ( CONST_TBL_STUDENTCLASS . ' as SC', function ($q) use ($curr_day, $tbl) {
			$q->on ( 'SC.student_id', '=', $tbl . '.id' )
				->where ( 'SC.is_activated', STATUS_ACTIVE )
				->whereIn ( 'SC.type', [ 
					STUDENT_HT,
					STUDENT_CT ] )
				->whereDate ( 'SC.start_at', '<=', $curr_day )
				->whereRaw ( '(DATE_FORMAT(SC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $curr_day . '", "%Y%m%d") OR SC.stop_at IS NULL )' );
		} )
			->join ( CONST_TBL_MYCLASS . ' as M', 'M.id', '=', 'SC.myclass_id' )
			->join ( CONST_TBL_PS_LOGTIMES . ' as L', 'L.student_id', '=', $tbl . '.id' )
			->where ( 'SCS.id', $ps_service_course_schedules_id )
			->whereDate ( 'L.login_at', $date_at )
			->whereRaw ( $tbl . '.deleted_at IS NULL' )
			->distinct ()
			->orderBy ( $tbl . '.last_name' )
			->get ();

		return $students;
	}

	/**
	 * Hàm trả về object là thong tin co ban cua 1 hoc sinh
	 *
	 * @param $ps_student mixed
	 * @param $style_avatar int
	 *        	Kieu hien thi anh: VD anh tren man hinh Home; man hinh chuc nang trong
	 *        	$style_avatar = 1 => trang Home; 2: trang trong - Khong có chữ: Chạm vào đây để xem thông tin; 3: Cham vao day de doi anh be
	 * @return resource
	 */
	public static function studentInfo($ps_student, $style_avatar = null) {

		// Get infomation student
		$student_info = new \stdClass ();

		$student_info->student_id = ( int ) $ps_student->id;
		$student_info->birthday = ( string ) PsDateTime::toDMY ( $ps_student->birthday );
		$student_info->first_name = ( string ) $ps_student->first_name;
		$student_info->last_name = ( string ) $ps_student->last_name;
		$student_info->class_id = ( string ) $ps_student->class_id;
		$student_info->class_name = ( string ) $ps_student->class_name;

		// Can sua anh mac dinh theo gioi tinh
		$student_info->avatar_url = ($ps_student->avatar != '') ? PsString::getUrlMediaAvatar ( $ps_student->cache_data, $ps_student->s_year_data, $ps_student->avatar, MEDIA_TYPE_STUDENT ) : PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR_STUDENT;
		//$student_info->avatar_url = PS_CONST_API_URL_IMAGE_DEFAULT_AVATAR_STUDENT;
    	
    	return $student_info;
    }
    
    /** Lay danh sach tat ca hoc sinh chua bi xoa cua 1 nguoi than **/
    public static function getListStudentOfRelative($ps_customer_id, $relative_id) {
    		
    }
}