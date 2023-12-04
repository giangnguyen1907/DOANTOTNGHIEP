<?php
/**
 * @package truongnet.com
 * @subpackage API app 
 * @file PsCustomerModel.php
 * 
 * @author thangnc
 * @version 1.0 2017/03/17
 */
namespace App\Model;

class PsConstantOptionModel extends BaseModel {
		
	protected $table = TBL_PS_CONSTANT_OPTION;
		
		/**
		 * getConstantByCode($ps_customer_id, $c_code = '')
		*/
		public static function getConstantByCode($ps_customer_id, $c_code = '') {
			
			$tbl 	= TBL_PS_CONSTANT_OPTION;			
			
			if ($c_code != '') {
				
				$resualt = PsConstantOptionModel::select($tbl.'.id', $tbl.'.value', 'C.c_code AS c_code', 'C.value_default AS value_default', 'C.title AS title')
				->join ( TBL_PS_CONSTANT.' as C', 'C.id', '=', $tbl.'.ps_constant_id' )
				->where($tbl.'.ps_customer_id', $ps_customer_id)				
				->where('C.c_code','=' ,$c_code)->get()->first();
			} else {
				$resualt = PsConstantOptionModel::select($tbl.'.id', $tbl.'.value', 'C.c_code AS c_code', 'C.value_default AS value_default', 'C.title AS title')
				->join ( TBL_PS_CONSTANT.' as C', 'C.id', '=', $tbl.'.ps_constant_id' )
				->where($tbl.'.ps_customer_id', $ps_customer_id)->get();
			}
			
			return $resualt;
		}
}



