<?php

/**
 * PsMobileApps form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsMobileAppsForm extends BasePsMobileAppsForm {

	public function configure() {

		$this->widgetSchema ['device_id'] = new sfWidgetFormInputHidden ();
		$this->validatorSchema ['device_id'] = new sfValidatorString ();

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['active_created_at'] = new psWidgetFormInputDate ();

		$this->validatorSchema ['active_created_at'] = new sfValidatorDateTime ( array (
				'required' => false ) );

		$this->widgetSchema ['osname'] = new sfWidgetFormInputHidden ();
		$this->validatorSchema ['osname'] = new sfValidatorString ();

		$this->widgetSchema ['osvesion'] = new sfWidgetFormInputHidden ();
		$this->validatorSchema ['osvesion'] = new sfValidatorString ();

		$this->widgetSchema ['network_name'] = new sfWidgetFormInputHidden ();
		$this->validatorSchema ['network_name'] = new sfValidatorString ();

		$this->widgetSchema ['mobile_network_type'] = new sfWidgetFormInputHidden ();
		$this->validatorSchema ['mobile_network_type'] = new sfValidatorString ();

		$this->widgetSchema ['params'] = new sfWidgetFormInputHidden ();
		$this->validatorSchema ['params'] = new sfValidatorString ();

		$this->addBootstrapForm ();
	}
}
