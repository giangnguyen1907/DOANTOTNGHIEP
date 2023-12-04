<?php
/**
 * @package
 * @subpackage API app
 * 
 * @file PsStudentBmiModel.php
 * 
 * @author thangnc
 * @version 1.0 2017/03/17
 */
namespace App\Model;


class PsStudentBmiModel extends BaseModel {
		
	protected $table = TBL_PS_STUDENT_BMI.' as s_bm';
		
		/** Sex - giới tính **/
		public static function getMinMaxBmiMonth($sex) {
			
			$obj = PsStudentBmiModel::selectRaw("MIN(s_bm.is_month) AS min_bmi_month, MAX(s_bm.is_month) AS max_bmi_month")
			->where('s_bm.sex', (int)$sex)
			->orderBy('s_bm.is_month')->get()->first();		
			return $obj;
		}
		
		/** Sex - giới tính **/
		public static function getMaxBmiMonth($sex) {
			
			$obj = PsStudentBmiModel::selectRaw("MAX(s_bm.is_month) AS max_bmi_month")
			->where('s_bm.sex', (int)$sex)
			->orderBy('s_bm.is_month')->get()->first();		
			return $obj;
		}
		
		// Lay table BMI theo gioi tinh
		public static function findBmiMonthByGender($sex) {
			
			$obj = PsStudentBmiModel::selectRaw("MIN(s_bm.is_month) AS min_bmi_month, MAX(s_bm.is_month) AS max_bmi_month")
			->where('s_bm.sex', (int)$sex)
			->orderBy('s_bm.is_month')->get();		
			return $obj;
		}
}