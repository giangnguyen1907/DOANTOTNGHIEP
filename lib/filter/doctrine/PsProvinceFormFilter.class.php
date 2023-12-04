<?php

/**
 * PsProvince filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsProvinceFormFilter extends BasePsProvinceFormFilter {

	public function configure() {

		$this->addI18nChoiceCountryFormFilter ();
	}

	public function getFields() {

		return array (
				'id' => 'Number',
				'country_code' => 'ForeignKey',
				's_code' => 'Text',
				'name' => 'Text',
				'description' => 'Text',
				'iorder' => 'Number',
				'is_activated' => 'Boolean',
				'user_created_id' => 'ForeignKey',
				'user_updated_id' => 'ForeignKey',
				'created_at' => 'Date',
				'updated_at' => 'Date' );
	}
}
