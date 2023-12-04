<?php
namespace Api\PsMembers\Model;

use App\Model\BaseModel;

class PsFeatureBranchSyntheticModel extends BaseModel {
	
	protected $table = TBL_PS_FEATURE_BRANCH_SYNTHETIC;
	
	/**
	* Ham kiem tra so luong danh gia hoat dong cua 1 lop
	* 
	* @param $feature_branch_id - int, ID hoat dong
	* @param $class_id - int, ID lop
	* @param $date_at - string, yyyy-mm-dd
	* 
	* @return Object
	**/
	public static function getPsFeatureBranchSyntheticOfClassByDate($feature_branch_id, $class_id, $date_at) {
	    
	    return PsFeatureBranchSyntheticModel::where ('ps_class_id', '=' ,$class_id )->where('feature_id', '=', $feature_branch_id)->whereDate ( 'tracked_at', '=', date ( 'Y-m-d',strtotime($date_at) ) )->get ()->first ();
	}
}