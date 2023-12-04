<?php

/**
 * PsStudentBmi form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsStudentBmiForm extends BasePsStudentBmiForm {

	public function configure() {

		// chieu cao
		// $this->widgetSchema ['min_height1']->setAttributes ( array (
		// 'class' => 'form-control',
		// 'maxlength' => 12,
		// 'required' => false,
		// 'type' => 'number'
		// ) );

		// $this->validatorSchema ['min_height1'] = new sfValidatorInteger( array (
		// 'required' => false
		// ) );

		// $this->widgetSchema ['max_height1']->setAttributes ( array (
		// 'class' => 'form-control',
		// 'maxlength' => 12,
		// 'required' => false,
		// 'type' => 'number'
		// ) );

		// $this->validatorSchema ['max_height1'] = new sfValidatorInteger( array (
		// 'required' => false
		// ) );

		// $this->widgetSchema ['min_height']->setAttributes ( array (
		// 'class' => 'form-control',
		// 'maxlength' => 12,
		// 'required' => true,
		// 'type' => 'number'
		// ) );

		// $this->validatorSchema ['min_height'] = new sfValidatorInteger( array (
		// 'required' => true
		// ) );

		// $this->widgetSchema ['max_height']->setAttributes ( array (
		// 'class' => 'form-control',
		// 'maxlength' => 12,
		// 'required' => true,
		// 'type' => 'number'
		// ) );

		// $this->validatorSchema ['max_height'] = new sfValidatorInteger( array (
		// 'required' => true
		// ) );

		// // can nang
		// $this->widgetSchema ['min_weight1']->setAttributes ( array (
		// 'class' => 'form-control',
		// 'maxlength' => 12,
		// 'required' => false,
		// 'type' => 'number'
		// ) );

		// $this->validatorSchema ['min_weight1'] = new sfValidatorInteger( array (
		// 'required' => false
		// ) );

		// $this->widgetSchema ['max_weight1']->setAttributes ( array (
		// 'class' => 'form-control',
		// 'maxlength' => 12,
		// 'required' => false,
		// 'type' => 'number'
		// ) );

		// $this->validatorSchema ['max_weight1'] = new sfValidatorInteger( array (
		// 'required' => false
		// ) );

		// $this->widgetSchema ['min_weight']->setAttributes ( array (
		// 'class' => 'form-control',
		// 'maxlength' => 12,
		// 'required' => true,
		// 'type' => 'number'
		// ) );

		// $this->validatorSchema ['min_weight'] = new sfValidatorInteger( array (
		// 'required' => true
		// ) );

		// $this->widgetSchema ['max_weight']->setAttributes ( array (
		// 'class' => 'form-control',
		// 'maxlength' => 12,
		// 'required' => true,
		// 'type' => 'number'
		// ) );

		// $this->validatorSchema ['max_weight'] = new sfValidatorInteger( array (
		// 'required' => true
		// ) );
		$this->validatorSchema ['is_month'] = new sfValidatorInteger ( array (
				'required' => true ) );

		$this->widgetSchema ['is_month']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 12,
				'required' => true,
				'type' => 'number' ) );

		$this->validatorSchema ['is_month'] = new sfValidatorInteger ( array (
				'required' => true ) );

		$this->widgetSchema ['sex'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsGender () ), array (
				'class' => 'radiobox' ) );

		$this->setDefault ( 'min_height1', 0 );
		$this->setDefault ( 'max_height1', 0 );

		$this->setDefault ( 'min_weight1', 0 );
		$this->setDefault ( 'max_weight1', 0 );

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		/*
		 * if ($this->getValue ( 'image_delete' )) {
		 * $this->removeDataFile($this->getObject ()->image);
		 * }
		 */
		$object = parent::baseUpdateObject ( $values );

		return $object;
	}
}