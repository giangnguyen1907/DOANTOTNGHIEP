<?php

/**
 * PsDistrict filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsDistrictFormFilter extends BasePsDistrictFormFilter {

	public function configure() {

		$this->addI18nChoiceCountryFormFilter ();

		$this->validatorSchema ['country_code'] = new sfValidatorPass ( array (
				'required' => false ) );

		$country_code = $this->getDefault ( 'country_code' );

		$this->widgetSchema ['ps_province_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsProvince',
				'query' => Doctrine::getTable ( 'PsProvince' )->setSqlPsProvinceByCountry ( $country_code ),
				'add_empty' => '-Select province-' ), array (
				'class' => 'form-control' ) );
	}

	public function getFields() {

		$fields = parent::getFields ();
		$fields ['country_code'] = 'ForeignKey';
		return $fields;
	}

	// Add virtual_column_name for filter
	public function addCountryCodeColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->andWhere ( 'p.country_code = ?', $value );

		return $query;
	}
}
