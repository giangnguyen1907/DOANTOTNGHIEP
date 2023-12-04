<?php

/**
 * PsMemberNational form base class.
 *
 * @method PsMemberNational getObject() Returns the current form's model object
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsMemberNationalForm extends BaseFormDoctrine {

	public function setup() {

		$this->setWidgets ( array (
				'national_code' => new sfWidgetFormInputHidden (),
				'member_id' => new sfWidgetFormInputHidden (),
				'created_at' => new sfWidgetFormDateTime (),
				'updated_at' => new sfWidgetFormDateTime () ) );

		$this->setValidators ( array (
				'national_code' => new sfValidatorChoice ( array (
						'choices' => array (
								$this->getObject ()
									->get ( 'national_code' ) ),
						'empty_value' => $this->getObject ()
							->get ( 'national_code' ),
						'required' => false ) ),
				'member_id' => new sfValidatorChoice ( array (
						'choices' => array (
								$this->getObject ()
									->get ( 'member_id' ) ),
						'empty_value' => $this->getObject ()
							->get ( 'member_id' ),
						'required' => false ) ),
				'created_at' => new sfValidatorDateTime (),
				'updated_at' => new sfValidatorDateTime () ) );

		$this->widgetSchema->setNameFormat ( 'ps_member_national[%s]' );

		$this->errorSchema = new sfValidatorErrorSchema ( $this->validatorSchema );

		$this->setupInheritance ();

		parent::setup ();
	}

	public function getModelName() {

		return 'PsMemberNational';
	}
}
