<?php
/**
 * @package			kidsschool.vn
 * @subpackage     	API app 
 *
 * @file PsFeeReceiptModel.php
 * @author thangnc
 * @version 1.0 27-02-2017 -  00:51:34
 */
namespace Api\Students\Model;

use App\Model\BaseModel;

class PsFeeReceiptModel extends BaseModel {

	protected $table = TBL_PS_FEE_RECEIPT . ' as re';

	/**
	 * Lay phieu thu - phieu bao cua thang duoc phep hien thi cho phu huynh
	 *
	 * @author Nguyen Chien Thang
	 * @param $student_id int        	
	 * @param $intdate date
	 *        	(yyyy-mm-dd)
	 */
	public static function findOfStudentByDate($student_id, $receivable_at) {

		$q = PsFeeReceiptModel::selectRaw ( "id, ps_customer_id, student_id, title, receipt_no, receipt_date, receivable_amount, collected_amount, balance_amount, payment_status,payment_relative,payment_date,note" )
		->whereRaw ( "DATE_FORMAT(receipt_date,'%Y%m') = ? ", date ( "Ym", strtotime ( $receivable_at ) ) )
		->where ( "student_id", "=", $student_id )
		->where ( "is_public", "=", 1 )->get ()->first ();
		
		return $q;
	}
}