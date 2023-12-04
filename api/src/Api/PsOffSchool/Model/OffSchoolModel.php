<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/28/2018
 * Time: 3:51 PM
 */
namespace Api\PsOffSchool\Model;

use App\Model\BaseModel;

class OffSchoolModel extends BaseModel {

	protected $table = TBL_PS_OFF_SCHOOL;

	public static function getDetailOfMember($offschool_id, $user_id) {

		return OffSchoolModel::where ( 'id', $offschool_id )->where ( 'user_id', $user_id )
			->get ()
			->first ();
	}
}