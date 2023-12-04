<?php
/**
 * PsDistrict form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsDistrictForm extends BasePsDistrictForm {

	public function configure() {

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		if (! $this->getObject ()
			->isNew ()) { // Edit
			$country_code = Doctrine::getTable ( 'PsProvince' )->findOneById ( $this->getObject ()
				->getPsProvinceId () )
				->getCountryCode ();
		} else { // New
			$country_code = strtoupper ( sfConfig::get ( 'app_ps_default_country' ) );
		}

		$this->addFormI18nChoiceCountry ( 'country_code', $country_code );

		$this->widgetSchema ['ps_province_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsProvince',
				'query' => Doctrine::getTable ( 'PsProvince' )->setSqlPsProvinceByCountry ( $country_code ),
				'add_empty' => '-Select province-' ), array (
				'class' => 'form-control' ) );

		$this->widgetSchema ['s_code']->setAttribute ( 'maxlength', 10 );
		$this->widgetSchema ['name']->setAttribute ( 'maxlength', 255 );
		$this->widgetSchema ['description']->setAttribute ( 'maxlength', 250 );

		/*
		 * Not used if config in generator.yml
		 * $this->useFields(array (
		 * 'id',
		 * 'country_code',
		 * 'ps_province_id',
		 * 's_code',
		 * 'name',
		 * 'iorder',
		 * 'description',
		 * 'is_activated'
		 * ));
		 */

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}