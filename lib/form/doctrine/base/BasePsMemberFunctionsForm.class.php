<?php

/**
 * PsMemberFunctions form base class.
 *
 * @method PsMemberFunctions getObject() Returns the current form's model object
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsMemberFunctionsForm extends BaseFormDoctrine {

	public function setup() {

		$this->setWidgets ( array (
				'id' => new sfWidgetFormInputHidden (),
				'member_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'PsMember' ),
						'add_empty' => true ) ),
				'ps_function_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'PsFunction' ),
						'add_empty' => true ) ),
				'start_at' => new sfWidgetFormDate (),
				'stop_at' => new sfWidgetFormDate (),
				'is_current' => new sfWidgetFormInputCheckbox (),
				'note' => new sfWidgetFormInputText (),
				'user_created_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'UserCreated' ),
						'add_empty' => true ) ),
				'user_updated_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'UserUpdated' ),
						'add_empty' => true ) ),
				'created_at' => new sfWidgetFormDateTime (),
				'updated_at' => new sfWidgetFormDateTime () ) );

		$this->setValidators ( array (
				'id' => new sfValidatorChoice ( array (
						'choices' => array (
								$this->getObject ()
									->get ( 'id' ) ),
						'empty_value' => $this->getObject ()
							->get ( 'id' ),
						'required' => false ) ),
				'member_id' => new sfValidatorDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'PsMember' ),
						'required' => false ) ),
				'ps_function_id' => new sfValidatorDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'PsFunction' ),
						'required' => false ) ),
				'start_at' => new sfValidatorDate (),
				'stop_at' => new sfValidatorDate ( array (
						'required' => false ) ),
				'is_current' => new sfValidatorBoolean ( array (
						'required' => false ) ),
				'note' => new sfValidatorString ( array (
						'max_length' => 255,
						'required' => false ) ),
				'user_created_id' => new sfValidatorDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'UserCreated' ),
						'required' => false ) ),
				'user_updated_id' => new sfValidatorDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'UserUpdated' ),
						'required' => false ) ),
				'created_at' => new sfValidatorDateTime (),
				'updated_at' => new sfValidatorDateTime () ) );

		$this->widgetSchema->setNameFormat ( 'ps_member_functions[%s]' );

		$this->errorSchema = new sfValidatorErrorSchema ( $this->validatorSchema );

		$this->setupInheritance ();

		parent::setup ();
	}

	public function getModelName() {

		return 'PsMemberFunctions';
	}
}
