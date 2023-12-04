<?php

/**
 * PsCmsUseGuide form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsCmsUseGuideForm extends BasePsCmsUseGuideForm {

	public function configure() {

		$this->widgetSchema ['url_file'] = new sfWidgetFormInputText ()
		;

		$this->widgetSchema ['url_file']->setAttributes ( array (
				'maxlength' => 500,
				'class' => 'input_text form-control' ) );

		$this->validatorSchema ['url_file'] = new sfValidatorString ( array (
				'required' => true ) );

		$this->widgetSchema ['note'] = new sfWidgetFormTextarea ( array (), array (
				'class' => 'ckeditor' ) );

		$this->validatorSchema ['note'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
				'class' => 'radiobox' ) );

		$this->validatorSchema ['is_activated'] = new sfValidatorBoolean ( array (
				'required' => false ) );

		$this->addBootstrapForm ();
	}
}
