<?php
namespace Api\PsMembers\Model;

use App\Model\BaseModel;

class PsAttendancesSyntheticModel extends BaseModel {
	
	protected $table = TBL_PS_ATTENDANCES_SYNTHETIC;
	
	/**
	* Ham lay du lieu diem danh cua lop
	* 
	* @param $class_id - int, ID lop
	* @param $date_at - string, yyyy-mm-dd
	* 
	* @return Object
	**/
	public static function getAttendanceSyntheticByDate( $class_id, $date_at) {
	    
	    return PsAttendancesSyntheticModel::where ('ps_class_id', $class_id )->whereDate ( 'tracked_at', '=', date ( 'Y-m-d',strtotime($date_at) ) )->get ()->first ();
	}
}