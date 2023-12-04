<?php

/**
 *
 * @package truongnet.com
 * @subpackage API app
 *            
 *             @file PsMemberModel.php
 * @author thangnc
 * @version 1.0 27-02-2017 - 00:51:34
 *         
 */

namespace Api\PsMembers\Model;

// use Illuminate\Database\Eloquent\Model;
use App\Model\BaseModel;

class PsMemberModel extends BaseModel
{

	protected $table = CONST_TBL_PS_MEMBER;

	/**
	 * Kiem tra quan he giao vien va lop o thoi diem $day_at, $day_at == null thi 
	 * check theo ngay hien tai(YYYY-MM-DD)
	 * 
	 * @author thangnc
	 * @param $id - id member_id
	 * @param $date_at - format YYYY-mm-dd
	 * 
	 * @return mixed
	 */
	public static function getMember($member_id, $date_at = null, $class_id)
	{

		//return $class_id;
		$tbl = CONST_TBL_PS_MEMBER;

		$curr_day = ($date_at == null) ? date('Y-m-d') : $date_at;

		$ps_member = PsMemberModel::select($tbl . '.id as id', $tbl . '.ps_customer_id as ps_customer_id', $tbl . '.ps_workplace_id AS member_workplace_id', $tbl . '.avatar AS avatar', $tbl . '.first_name AS first_name',$tbl . '.last_name AS last_name', $tbl . '.year_data AS s_year_data', 'C.school_code','C.school_name',  'C.cache_data', 'TC.ps_myclass_id as myclass_id', 'MC.name as myclass_name', 'MC.student_number as number_student', 'MC.ps_obj_group_id', 'MC.school_year_id', 'W.id AS ps_workplace_id')

			->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id')

			->leftJoin(TBL_PS_TEACHER_CLASS . ' as TC', function ($q) use ($curr_day, $tbl) {
				$q->on('TC.ps_member_id', '=', $tbl . '.id')
					->whereDate('TC.start_at', '<=', $curr_day)
					->whereRaw('(DATE_FORMAT(TC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $curr_day . '", "%Y%m%d") OR TC.stop_at IS NULL )');
			})

			//->leftJoin(CONST_TBL_MYCLASS . ' as MC', 'MC.id', '=', 'TC.ps_myclass_id')
			->leftJoin(CONST_TBL_MYCLASS . ' as MC', function ($q) {
				$q->on('MC.id', '=', 'TC.ps_myclass_id')
					->where('MC.is_activated', '=', STATUS_ACTIVE);
			})

			->leftJoin(TBL_PS_WORK_PLACES . ' as W', 'W.id', '=', 'MC.ps_workplace_id')

			->leftJoin(TBL_PS_SCHOOL_YEAR . ' as SY', 'SY.id', '=', 'MC.school_year_id')

			/*
			->leftJoin(TBL_PS_SCHOOL_YEAR . ' as SY', function ($q) use ($curr_day, $tbl) {
            
				$q->on('SY.id', '=', 'MC.school_year_id')
                ->whereDate('SY.from_date', '<=', $curr_day)
                ->whereDate('SY.to_date', '>', $curr_day)
				->where('SY.is_default', '=', STATUS_ACTIVE);
            })
			*/
			->where('C.is_activated', '=', STATUS_ACTIVE)
			->where('C.is_deploy', '=', STATUS_ACTIVE)
			->where($tbl . '.is_status', '=', HR_STATUS_WORKING)
			->where($tbl . '.id', '=', $member_id)
			->where('SY.is_default', '=', STATUS_ACTIVE)
			->where('MC.id', '=', $class_id)
			->get()
			->first();

		return $ps_member;
	}
	public static function getMember2($member_id, $date_at = null)
	{

		$tbl = CONST_TBL_PS_MEMBER;

		$curr_day = ($date_at == null) ? date('Y-m-d') : $date_at;

		$ps_member = PsMemberModel::select($tbl . '.id as id', $tbl . '.ps_customer_id as ps_customer_id', $tbl . '.ps_workplace_id AS member_workplace_id', $tbl . '.avatar AS avatar', $tbl . '.year_data AS s_year_data', 'C.school_code', 'C.cache_data', 'TC.ps_myclass_id as myclass_id', 'MC.name as myclass_name', 'MC.student_number as number_student', 'MC.ps_obj_group_id', 'MC.school_year_id', 'W.id AS ps_workplace_id')

			->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id')

			->leftJoin(TBL_PS_TEACHER_CLASS . ' as TC', function ($q) use ($curr_day, $tbl) {
				$q->on('TC.ps_member_id', '=', $tbl . '.id')
					->whereDate('TC.start_at', '<=', $curr_day)
					->whereRaw('(DATE_FORMAT(TC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $curr_day . '", "%Y%m%d") OR TC.stop_at IS NULL )');
			})

			//->leftJoin(CONST_TBL_MYCLASS . ' as MC', 'MC.id', '=', 'TC.ps_myclass_id')
			->leftJoin(CONST_TBL_MYCLASS . ' as MC', function ($q) {
				$q->on('MC.id', '=', 'TC.ps_myclass_id')
					->where('MC.is_activated', '=', STATUS_ACTIVE);
			})

			->leftJoin(TBL_PS_WORK_PLACES . ' as W', 'W.id', '=', 'MC.ps_workplace_id')

			->leftJoin(TBL_PS_SCHOOL_YEAR . ' as SY', 'SY.id', '=', 'MC.school_year_id')

			/*
			->leftJoin(TBL_PS_SCHOOL_YEAR . ' as SY', function ($q) use ($curr_day, $tbl) {
            
				$q->on('SY.id', '=', 'MC.school_year_id')
                ->whereDate('SY.from_date', '<=', $curr_day)
                ->whereDate('SY.to_date', '>', $curr_day)
				->where('SY.is_default', '=', STATUS_ACTIVE);
            })
			*/
			->where('C.is_activated', '=', STATUS_ACTIVE)
			->where('C.is_deploy', '=', STATUS_ACTIVE)
			->where($tbl . '.is_status', '=', HR_STATUS_WORKING)
			->where($tbl . '.id', '=', $member_id)
			->where('SY.is_default', '=', STATUS_ACTIVE)
			->get();


		return $ps_member;
	}
	/**
	 * Lay avatar cua nhan su
	 * 
	 * @author thangnc
	 * @param $member_id int - ID nhân sự
	 * @return mixed
	 */
	public static function getAvatar($member_id)
	{

		$tbl = CONST_TBL_PS_MEMBER;

		$ps_member = PsMemberModel::select($tbl . '.id', $tbl . '.avatar as avatar', $tbl . '.year_data', $tbl . '.ps_customer_id', 'C.cache_data')
			->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id')
			->where($tbl . '.id', '=', $member_id)
			->get()
			->first();

		return $ps_member;
	}

	// Thong tin member boi id
	public static function getPsMemberById($id)
	{

		$tbl = CONST_TBL_PS_MEMBER;

		$ps_obj = PsMemberModel::select($tbl . '.*', 'C.cache_data', 'C.school_code', 'C.logo', 'u.user_key', 'u.id AS m_user_id', 'u.osname AS osname', 'u.notification_token')
			->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id')
			->join(CONST_TBL_USER . ' as u', 'u.member_id', '=', $tbl . '.id')
			->where('u.user_type', '=', USER_TYPE_TEACHER)
			->where($tbl . '.id', '=', $id)->get()->first();
		/*
		$ps_member = $this->db->table ( CONST_TBL_PS_MEMBER . ' as M' )
		->select ( 'M.id AS teacher_id', 'M.first_name', 'M.last_name', 'M.avatar', 'M.birthday', 'M.sex AS gender', 'M.mobile AS phone', 'M.email', 'M.address', 'M.ps_customer_id' )
		->addSelect ( 'C.school_name', 'C.year_data', 'C.logo', 'u.user_key', 'u.id AS m_user_id' )
		->join ( CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', 'M.ps_customer_id' )
		->join ( CONST_TBL_USER . ' as u', 'u.member_id', '=', 'M.id' )
		->where ( 'M.id', '=', $m_id )
		->where ( 'u.user_type', '=', USER_TYPE_TEACHER )->get ()->first ();
		*/
		return $ps_obj;
	}

	// Kiem tra lop - giao vien
	public function checkMyClassOfMember($user_id, $ps_class_id)
	{

		$tbl = CONST_TBL_PS_MEMBER;

		$ps_obj = PsMemberModel::select($tbl . '.id')
			->join(CONST_TBL_USER . ' as u', 'u.member_id', '=', $tbl . '.id')
			->join(TBL_PS_TEACHER_CLASS . ' as TC', 'TC.ps_member_id', '=', $tbl . '.id')
			->where('TC.ps_myclass_id', '=', $ps_class_id)
			->where('u.user_type', '=', USER_TYPE_TEACHER)
			->where('u.id', '=', $user_id)->get()->first();
		return $ps_obj;
	}

	/** Kiem tra thoi diem hien tai giao vien con hoat dong trong lop hoc o mot thoi diem ko **/
	public function checkCurrentMyClassOfMember($user_id, $ps_class_id, $date_at = null)
	{

		$tbl = CONST_TBL_PS_MEMBER;

		$curr_day = ($date_at == null) ? date('Y-m-d') : $date_at;

		$ps_obj = PsMemberModel::select($tbl . '.id')
			->join(CONST_TBL_USER . ' as u', 'u.member_id', '=', $tbl . '.id')
			->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id')
			->join(TBL_PS_TEACHER_CLASS . ' as TC', function ($q) use ($curr_day, $tbl) {
				$q->on('TC.ps_member_id', '=', $tbl . '.id')
					->whereDate('TC.start_at', '<=', $curr_day)
					->whereRaw('(DATE_FORMAT(TC.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $curr_day . '", "%Y%m%d") OR TC.stop_at IS NULL )');
			})
			->join(CONST_TBL_MYCLASS . ' as MC', 'MC.id', '=', 'TC.ps_myclass_id')
			->where('TC.ps_myclass_id', '=', $ps_class_id)
			->where('MC.is_activated', STATUS_ACTIVE)
			->where('u.user_type', '=', USER_TYPE_TEACHER)
			->where('u.id', '=', $user_id)->get()->first();

		return $ps_obj;
	}

	// Lay ps_workplace_id cua member boi id
	public static function getPsWorkPlaceIdOfMember($id)
	{

		$tbl = CONST_TBL_PS_MEMBER;

		$ps_obj = PsMemberModel::select($tbl . '.ps_workplace_id AS m_workplace_id', 'WP.id AS ps_workplace_id', 'PDE.ps_workplace_id AS pde_workplace_id', 'C.year_data', 'C.cache_data', 'C.school_code', 'C.logo')

			->join(CONST_TBL_PS_CUSTOMER . ' as C', 'C.id', '=', $tbl . '.ps_customer_id')

			->leftJoin(TBL_PS_WORK_PLACES . ' as WP', 'WP.id', '=', $tbl . '.ps_workplace_id')

			->leftJoin(TBL_PS_MEMBER_DEPARTMENTS . ' as MD', function ($q) use ($tbl) {
				$q->on('MD.ps_member_id', '=', $tbl . '.id')->where('MD.is_current', '=', STATUS_ACTIVE);
			})

			->leftJoin(CONST_TBL_PS_DEPARTMENT . ' as PDE', 'PDE.id', '=', 'MD.ps_department_id')
			->where($tbl . '.id', '=', $id)->get()->first();

		return $ps_obj;
	}
}
