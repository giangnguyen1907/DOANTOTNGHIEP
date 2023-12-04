<?php

/**
 * FeatureOptionCourse form base class.
 *
 * @method FeatureOptionCourse getObject() Returns the current form's model object
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseFeatureOptionCourseForm extends BaseFormDoctrine {

	public function setup() {

		$this->setWidgets ( array (
				'id' => new sfWidgetFormInputHidden (),
				'type' => new sfWidgetFormInputText (),
				'order_by' => new sfWidgetFormInputText (),
				'feature_option_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'FeatureOption' ),
						'add_empty' => true ) ),
				'ps_service_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'Service' ),
						'add_empty' => true ) ),
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
				'type' => new sfValidatorInteger ( array (
						'required' => false ) ),
				'order_by' => new sfValidatorInteger ( array (
						'required' => false ) ),
				'feature_option_id' => new sfValidatorDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'FeatureOption' ),
						'required' => false ) ),
				'ps_service_id' => new sfValidatorDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'Service' ),
						'required' => false ) ),
				'user_created_id' => new sfValidatorDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'UserCreated' ),
						'required' => false ) ),
				'user_updated_id' => new sfValidatorDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'UserUpdated' ),
						'required' => false ) ),
				'created_at' => new sfValidatorDateTime (),
				'updated_at' => new sfValidatorDateTime () ) );

		$this->widgetSchema->setNameFormat ( 'feature_option_course[%s]' );

		$this->errorSchema = new sfValidatorErrorSchema ( $this->validatorSchema );

		$this->setupInheritance ();

		parent::setup ();
	}

	public function getModelName() {

		return 'FeatureOptionCourse';
	}
}
