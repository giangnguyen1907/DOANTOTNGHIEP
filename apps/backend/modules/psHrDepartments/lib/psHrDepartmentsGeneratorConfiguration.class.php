<?php

/**
 * psHrDepartments module configuration.
 *
 * @package    kidsschool.vn
 * @subpackage psHrDepartments
 * @author     kidsschool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psHrDepartmentsGeneratorConfiguration extends BasePsHrDepartmentsGeneratorConfiguration {

	public function getFilterDefaults() {

		$ps_province_id = null;
		$ps_district_id = null;

		return array (
				'ps_province_id' => $ps_province_id,
				'ps_district_id' => $ps_district_id
		);

	}

}
