<?php

/**
 * PsWard form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsWardForm extends BasePsWardForm {

	public function configure() {

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		if ($this->getObject ()
			->isNew ()) { // Add new

			$country_code = strtoupper ( sfConfig::get ( 'app_ps_default_country' ) );
		} else {

			// get Province by district_id
			$ps_province_id = Doctrine::getTable ( 'PsDistrict' )->getOnePsDistrictById ( $this->getObject ()->ps_district_id )
				->getPsProvinceId ();

			// get Country_code
			$country_code = Doctrine::getTable ( 'PsProvince' )->getOnePsProvinceById ( $ps_province_id )
				->getCountryCode ();
		}

		$this->addFormI18nChoiceCountry ( 'country_code', $country_code );
		$this->validatorSchema ['country_code'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->widgetSchema ['ps_district_id'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select district-' ) ) + Doctrine::getTable ( 'PsDistrict' )->getGroupPsDistricts ( $country_code ) ), array (
				'class' => 'select2',
				'style' => "min-width:200px;",
				'data-placeholder' => _ ( '-Select district-' ) ) );

		$this->widgetSchema ['s_code']->setAttribute ( 'maxlength', 10 );
		$this->widgetSchema ['name']->setAttribute ( 'maxlength', 255 );
		$this->widgetSchema ['description']->setAttribute ( 'maxlength', 250 );

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
