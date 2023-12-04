<?php

/**
 * PsProvince form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsProvinceForm extends BasePsProvinceForm {

	public function configure() {

		$this->addFormI18nChoiceCountry ( 'country_code', $this->getObject ()
			->getCountryCode () );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['s_code']->setAttribute ( 'maxlength', 10 );
		$this->widgetSchema ['name']->setAttribute ( 'maxlength', 255 );
		$this->widgetSchema ['description']->setAttribute ( 'maxlength', 250 );

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}