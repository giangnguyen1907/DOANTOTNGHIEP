<?php
namespace App\Model;
/**
 *
 * @package truongnet.com
 * @subpackage API app
 *             @file PsSystemCmsContentModel.php
 *            
 * @author thangnc
 * @version 1.0 2017/03/17
 */

class PsSystemCmsContentModel extends BaseModel {

	protected $table = TBL_PS_SYSTEM_CMS_CONTENT;

	/**
	 * get item by code
	 */
	public static function getItemByCode($s_code) {

		$record = PsSystemCmsContentModel::select('description')->where('ps_system_cms_content_code', $s_code)
			->where('is_activated', STATUS_ACTIVE)
			->first();
		
		return $record;
	
	}

}