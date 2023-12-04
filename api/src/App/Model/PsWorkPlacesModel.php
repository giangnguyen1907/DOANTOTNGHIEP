<?php
/**
 * @package truongnet.com
 * @subpackage API app
 * @file PsWorkPlacesModel.php
 *
 * @author thangnc
 * @version 1.0 2017/03/17
 */
namespace App\Model;

class PsWorkPlacesModel extends BaseModel {
	
	protected $table = TBL_PS_WORKPLACES;
	
	/** get obj id **/
	public static function findById($id) {
		
		$obj = PsWorkPlacesModel::select("*")->where('id', $id)->where('is_activated', '=' ,STATUS_ACTIVE)->get()->first();
		
		return $obj;
	}
		
	/** Lay ra cac column **/
	public static function getColumnById($id, $string_sql = '*') {
		
		$obj = PsWorkPlacesModel::selectRaw($string_sql)->where('id', $id)->where('is_activated', '=' ,STATUS_ACTIVE)->get()->first();
		
		return $obj;
	}	
	
	/** Lay thong tin co so boi id lop hoc **/
	public static function getColumnByClassId($class_id) {
		
		if ($class_id <= 0)
			return null;
		
		$tbl = TBL_PS_WORKPLACES;
		
		$obj = PsWorkPlacesModel::selectRaw($tbl.'.config_multiple_teacher_process_album as config_multiple_teacher_process_album')
		->join(CONST_TBL_MYCLASS . ' as MC', 'MC.ps_workplace_id', '=', $tbl.'.id')
		->where('MC.id', $class_id)->where($tbl.'.is_activated', '=' ,STATUS_ACTIVE)->get()->first();
		
		return $obj;
	}
}