<?php
namespace Api\PsMembers\Model;

//use Illuminate\Database\Eloquent\Model;
use App\Model\BaseModel;

class FeatureOptionSubjectModel extends BaseModel {

	protected $table = TBL_FEATURE_OPTION_SUBJECT;

	// lay feature_option_subject feature_option_subject_id
	public static function getFeatureOptionSubject($feature_option_subject_id) {
		
		$tbl = TBL_FEATURE_OPTION_SUBJECT;
		
		$feature_option_subject = FeatureOptionSubjectModel::where($tbl.'.id', $feature_option_subject_id)
		
		->join(TBL_FEATURE_OPTION . ' as FO', 'FO.id', '=', $tbl.'.feature_option_id')
			->select($tbl.'.id AS id',$tbl.'.type as type', 'FO.name AS f_option_name')
			->get()
			->first();
		
		return $feature_option_subject;
	
	}

}