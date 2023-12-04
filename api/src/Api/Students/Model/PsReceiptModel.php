<?php
/**
 * @package			truongnet.com
 * @subpackage     	API app 
 *
 * @file PsReceiptModel.php
 * @author thangnc
 * @version 1.0 27-02-2017 -  00:51:34
 */
namespace Api\Students\Model;

use App\Model\BaseModel;

class PsReceiptModel extends BaseModel {

	protected $table = TBL_PS_RECEIPT.' as re';

	/**
	 * Lay phieu thu - phieu bao cua thang
	 *
	 * @author Nguyen Chien Thang
	 * @param $student_id int        	
	 * @param $intdate date
	 *        	(yyyy-mm-dd)
	 */
	public static function findOfStudentByDate($student_id, $receivable_at) {

		$q = PsReceiptModel::selectRaw ( "id, is_public, receipt_no, receipt_date, collected_amount, balance_amount, balance_last_month_amount,payment_status, payment_date, note" )
		->whereRaw ( "DATE_FORMAT(receipt_date,'%Y%m') = ? ", date ( "Ym", strtotime ( $receivable_at ) ) )
		->where ( "student_id", "=", $student_id )->get ()->first ();
		
		return $q;
	}
	
	/**
	 * Tim phieu thu da thanh toan gan $intdate nhat
	 *
	 * @author Nguyen Chien Thang
	 * 
	 * @param $student_id int ma hoc sinh
	 * @param $intdate strtime thang so sanh
	 * @return $obj
	 */
	public function findReceiptPrevOfStudentByDate($student_id, $intdate) {

		$q = PsReceiptModel::select('re.*')
		->where ( "re.student_id", "=", $student_id )
		->where ( "re.payment_status", "=", STATUS_ACTIVE)
		->whereRaw ( "DATE_FORMAT(receipt_date,'%Y%m') < ? ", date ( "Ym", strtotime ( $intdate ) ) );
		
		return $q->get ()->first ();
	}
}