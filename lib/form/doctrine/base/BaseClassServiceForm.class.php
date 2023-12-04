<?php

/**
 * ClassService form base class.
 *
 * @method ClassService getObject() Returns the current form's model object
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseClassServiceForm extends BaseFormDoctrine {

	public function setup() {

		$this->setWidgets ( array (
				'myclass_id' => new sfWidgetFormInputHidden (),
				'service_id' => new sfWidgetFormInputHidden (),
				'created_at' => new sfWidgetFormDateTime (),
				'updated_at' => new sfWidgetFormDateTime () ) );

		$this->setValidators ( array (
				'myclass_id' => new sfValidatorChoice ( array (
						'choices' => array (
								$this->getObject ()
									->get ( 'myclass_id' ) ),
						'empty_value' => $this->getObject ()
							->get ( 'myclass_id' ),
						'required' => false ) ),
				'service_id' => new sfValidatorChoice ( array (
						'choices' => array (
								$this->getObject ()
									->get ( 'service_id' ) ),
						'empty_value' => $this->getObject ()
							->get ( 'service_id' ),
						'required' => false ) ),
				'created_at' => new sfValidatorDateTime (),
				'updated_at' => new sfValidatorDateTime () ) );

		$this->widgetSchema->setNameFormat ( 'class_service[%s]' );

		$this->errorSchema = new sfValidatorErrorSchema ( $this->validatorSchema );

		$this->setupInheritance ();

		parent::setup ();
	}

	public function getModelName() {

		return 'ClassService';
	}
}
