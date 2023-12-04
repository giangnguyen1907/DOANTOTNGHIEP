<?php
/**
 * @package			kidsschool.vn
 * @subpackage     	API app 
 *
 * @file PsFeeNewsLettersModel.php
 * @author thangnc
 * @version 1.0 27-02-2017 -  00:51:34
 */
namespace Api\Students\Model;

use App\Model\BaseModel;

class PsFeeNewsLettersModel extends BaseModel {

	protected $table = TBL_PS_FEE_NEWSLETTERS;

	/**
	 * Lay phieu thu - phieu bao cua thang duoc phep hien thi cho phu huynh
	 *
	 * @author Nguyen Chien Thang
	 * @param $student_id int        	
	 * @param $intdate string
	 *        	(yyyy-mm-dd)
	 */
	public static function findOfStudentByDate($ps_workplace_id, $receivable_at) {

		$q = PsFeeNewsLettersModel::selectRaw("title as title,note as note")
		->where( "ps_year_month","=", $receivable_at )
		->where ( "ps_workplace_id", "=", $ps_workplace_id )
		->where ( "is_public", "=", STATUS_ACTIVE )->get ()->first ();
		
		return $q;
	}
}