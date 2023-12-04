<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/28/2018
 * Time: 3:51 PM
 */
namespace Api\Review\Model;

use App\Model\BaseModel;

class ReviewModel extends BaseModel {

	protected $table = TBL_PS_REVIEW;
	
	/*
	public static function getDetailOfMember($offschool_id, $user_id) {

		return OffSchoolModel::where ( 'id', $offschool_id )->where ( 'user_id', $user_id )
			->get ()
			->first ();
	}
	*/
}