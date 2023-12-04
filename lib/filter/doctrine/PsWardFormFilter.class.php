<?php

/**
 * PsWard filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsWardFormFilter extends BasePsWardFormFilter {

	public function configure() {

		/*
		 * $this->addI18nChoiceCountryFormFilter ();
		 * $this->validatorSchema ['country_code'] = new sfValidatorPass ( array (
		 * 'required' => false
		 * ) );
		 * $country_code = $this->getDefault ( 'country_code' );
		 */
		$country_code = strtoupper ( sfConfig::get ( 'app_ps_default_country' ) );

		$this->widgetSchema ['country_code'] = new sfWidgetFormInputHidden ();

		$this->setDefault ( 'country_code', $country_code );

		$this->validatorSchema ['country_code'] = new sfValidatorPass ( array (
				'required' => true ) );

		$this->widgetSchema ['ps_district_id'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select district-' ) ) + Doctrine::getTable ( 'PsDistrict' )->getGroupPsDistricts ( $country_code ) ), array (
				'class' => 'select2',
				'style' => "min-width:200px;",
				'data-placeholder' => _ ( '-Select district-' ) ) );

		// $this->widgetSchema ['ps_district_id']->setAttribute( 'placeholder', _( '-Select district-' ));
	}

	public function getFields() {

		$fields = parent::getFields ();
		$fields ['country_code'] = 'ForeignKey';
		return $fields;
	}

	// Add virtual_column_name for filter
	public function addCountryCodeColumnQuery($query, $field, $value) {

		// $a = $query->getRootAlias ();
		$query->innerJoin ( 'd.PsProvince p' )
			->andWhere ( 'p.country_code = ?', $value );

		return $query;
	}
}
