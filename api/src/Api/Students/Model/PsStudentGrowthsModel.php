<?php

/**
 * @package			truongnet.com
 * @subpackage     	API app 
 *
 * @file PsStudentGrowthsModel.php
 * @author thangnc
 * @version 1.0 27-02-2017 -  00:51:34
 */

namespace  Api\Students\Model;

//use Illuminate\Database\Eloquent\Model;

use App\Model\BaseModel;

class PsStudentGrowthsModel extends BaseModel
{

	protected $table = CONST_TBL_STUDENT_GROWTH . ' as sg';

	public static function getPsStudentGrowths($student_id, $orderBy = 'desc')
	{

		$list = PsStudentGrowthsModel::selectRaw('sg.student_id AS student_id,ex.name AS examination_name,
				sg.weight AS weight,sg.height AS height,sg.index_height AS index_height,
				sg.index_weight AS index_weight,sg.index_tooth AS index_tooth,
				sg.index_throat AS index_throat,sg.index_eye AS index_eye,
				sg.index_heart AS index_heart,sg.index_lung AS index_lung,
				sg.index_skin AS index_skin,sg.index_age AS index_age,sg.people_make AS people_make,sg.note AS note,
				ex.input_date_at AS input_date_at,MIN(sg.index_age) AS min_age,MAX(sg.index_age) AS max_age')
			->join(TBL_PS_EXAMINATION . ' as ex', 'ex.id', '=', 'sg.examination_id')
			->where('sg.student_id', '=', $student_id)
			->groupBy('sg.id')
			->orderBy('ex.input_date_at', $orderBy)->get();

		return $list;
	}
}
