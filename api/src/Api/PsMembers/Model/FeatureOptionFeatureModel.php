<?php
namespace Api\PsMembers\Model;

//use Illuminate\Database\Eloquent\Model;
use App\Model\BaseModel;

class FeatureOptionFeatureModel extends BaseModel {

	protected $table = TBL_FEATURE_OPTION_FEATURE;

	// lay feature_option_feature theo feature_option_id va feature_branch_id
	public static function getFeatureOptionFeature($feature_option_feature_id) {
		
		$tbl = TBL_FEATURE_OPTION_FEATURE;
		
		$feature_option_feature = FeatureOptionFeatureModel::where($tbl.'.id', $feature_option_feature_id)
		->join(TBL_FEATURE_OPTION . ' as FO', 'FO.id', '=', $tbl.'.feature_option_id')
			->select($tbl.'.id AS id',$tbl.'.type as type', 'FO.name AS f_option_name')
			->get()
			->first();
		return $feature_option_feature;	
	}

}