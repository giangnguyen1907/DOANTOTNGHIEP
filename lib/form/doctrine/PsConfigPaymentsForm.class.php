<?php
/**
 * PsConfigPayments form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsConfigPaymentsForm extends BasePsConfigPaymentsForm {

	public function configure() {

		$this->widgetSchema ['price']->setAttributes ( array (
				'type' => 'number',
				'required' => 'required',
				'pattern' => "[0-9]" ) );

		$this->validatorSchema ['price'] = new sfValidatorNumber ( array (
				'required' => true ) );

		$number_month = array_combine ( PreSchool::$package_fee, PreSchool::$package_fee );

		$this->widgetSchema ['number_month'] = new sfWidgetFormChoice ( array (
				'choices' => $number_month ), array (
				'class' => 'form-control' ) );

		$this->validatorSchema ['number_month'] = new sfValidatorChoice ( array (
				'choices' => array_keys ( PreSchool::$package_fee ),
				'required' => true ) );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
