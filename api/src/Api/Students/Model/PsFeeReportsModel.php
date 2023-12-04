<?php
/**
 * @package			truongnet.com
 * @subpackage     	API app 
 *
 * @file PsFeeReportsModel.php
 * @author thangnc
 * @version 1.0 27-02-2017 -  00:51:34
 */
namespace Api\Students\Model;

use App\Model\BaseModel;

class PsFeeReportsModel extends BaseModel {

	protected $table = TBL_PS_FEE_REPORTS.' as fe';

	/**
	 * Lay phieu bao cua thang
	 *
	 * @author Nguyen Chien Thang
	 * @param $student_id int        	
	 * @param $intdate date (yyyy-mm-dd)
	**/
	public static function findOfStudentByDate($student_id, $receivable_at) {

		$q = PsFeeReportsModel::selectRaw ( "id, ps_fee_report_no, receivable AS amount, receivable_at" )
		->whereRaw ( "DATE_FORMAT(receivable_at,'%Y%m') = ? ", date ( "Ym", strtotime ( $receivable_at ) ) )
		->where ( "student_id", "=", $student_id )->get ()->first ();
		
		return $q;
	}	
	
}