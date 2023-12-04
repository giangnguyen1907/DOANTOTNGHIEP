<?php

/**
 * @package			truongnet.com
 * @subpackage     	API app 
 *
 * @file Ps_ReceivableStudentModel.php
 * @author nghianc
 * @version 1.0 27-02-2017 -  00:51:34
 */

namespace Api\Students\Model;

use App\Model\BaseModel;

class PsReceivableStudentModel extends BaseModel
{

	protected $table = TBL_RECEIVABLE_STUDENT . ' as re';

	/**
	 * Lay phieu bao cua thang
	 *
	 * @author Nguyen Chien Thang
	 * @param $student_id int        	
	 * @param $intdate date (yyyy-mm-dd)
	 **/
	public static function findOfStudentByDate($student_id, $receivable_at)
	{
		$q = PsReceivableStudentModel::selectRaw("re.id, re.student_id,re.by_number, re.note, re.amount as unit_price,service.title as se")
			->leftjoin("service", "re.service_id", "=", "service.id")
			->whereRaw("DATE_FORMAT(receivable_at,'%Y%m') = ? ", date("Ym", strtotime($receivable_at)))
			->where("student_id", "=", $student_id)->get();
		return $q;
	}
}
