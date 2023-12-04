<?php
/**
 * @package			kidsschool.vn
 * @subpackage     	API app 
 *
 * @file PsFeeReceivableStudentModel.php
 * @author thangnc
 * @version 1.0 27-02-2017 -  00:51:34
 */
namespace Api\Students\Model;

use App\Model\BaseModel;

class PsFeeReceivableStudentModel extends BaseModel {

	protected $table = TBL_PS_FEE_RECEIVABLE_STUDENT.' as fre';

	/**
	 * Tim phieu thu da thanh toan gan $intdate nhat
	 *
	 * @author Nguyen Chien Thang
	 * 
	 * @param $student_id int ma hoc sinh
	 * @param $intdate strtime thang so sanh
	 * @return $obj
	 */
	public function getFeeReceivableStudentOfFeeReceipt($ps_fee_receipt_id) {

		$q = PsFeeReceivableStudentModel::select('fre.*')
		->where ( "fre.ps_fee_receipt_id", "=", $ps_fee_receipt_id );		
		return $q->get ();
	}
}