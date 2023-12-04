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

class PsTeacherClassModel extends BaseModel {

	protected $table = CONST_TBL_PS_TEACHER_CLASS;
	
	public static function getClassByMemberId($member_id, $date_at = null) {

		$tbl = CONST_TBL_PS_TEACHER_CLASS;
		
		$curr_day = ($date_at == null) ? date ( 'Y-m-d' ) : $date_at;
		
		$ps_member = PsTeacherClassModel::select ( $tbl . '.id as id', $tbl . '.ps_myclass_id as ps_myclass_id' )
		->where('(DATE_FORMAT('.$tbl.'.start_at, "%Y%m%d") <= DATE_FORMAT("' . $curr_day . '", "%Y%m%d"))')
		->where('(DATE_FORMAT('.$tbl.'.stop_at, "%Y%m%d") >= DATE_FORMAT("' . $curr_day . '", "%Y%m%d"))')
		->where($tbl.'.is_activated', '=', STATUS_ACTIVE)
		->get();
		
		return $ps_member;
	
	}
	
}