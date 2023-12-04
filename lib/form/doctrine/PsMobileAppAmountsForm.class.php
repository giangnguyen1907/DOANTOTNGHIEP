<?php

/**
 * PsMobileAppAmounts form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsMobileAppAmountsForm extends BasePsMobileAppAmountsForm {

	public function configure() {

		$this->addPsCustomerFormNotEdit ( 'PS_CMS_ARTICLES_ADD' );
		$this->widgetSchema ['user_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => $this->getRelatedModelName ( 'UserMobileAppAmounts' ),
				'add_empty' => true ) );

		$this->widgetSchema ['expiration_date_at'] = new psWidgetFormInputDate ();
		$this->widgetSchema ['expiration_date_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );

		$this->validatorSchema ['expiration_date_at'] = new sfValidatorDate ( array (
				'required' => false ) );

		$this->widgetSchema ['description'] = new sfWidgetFormTextarea ();
		$this->addBootstrapForm ();
	}
}
