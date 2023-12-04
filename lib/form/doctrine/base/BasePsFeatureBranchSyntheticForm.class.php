<?php

/**
 * PsFeatureBranchSynthetic form base class.
 *
 * @method PsFeatureBranchSynthetic getObject() Returns the current form's model object
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsFeatureBranchSyntheticForm extends BaseFormDoctrine {

	public function setup() {

		$this->setWidgets ( array (
				'id' => new sfWidgetFormInputHidden (),
				'ps_customer_id' => new sfWidgetFormInputText (),
				'ps_class_id' => new sfWidgetFormInputText (),
				'feature_id' => new sfWidgetFormInputText (),
				'feature_sum' => new sfWidgetFormInputText (),
				'tracked_at' => new sfWidgetFormDateTime (),
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
				'ps_customer_id' => new sfValidatorInteger ( array (
						'required' => false ) ),
				'ps_class_id' => new sfValidatorInteger ( array (
						'required' => false ) ),
				'feature_id' => new sfValidatorInteger ( array (
						'required' => false ) ),
				'feature_sum' => new sfValidatorInteger ( array (
						'required' => false ) ),
				'tracked_at' => new sfValidatorDateTime ( array (
						'required' => false ) ),
				'user_updated_id' => new sfValidatorDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'UserUpdated' ),
						'required' => false ) ),
				'created_at' => new sfValidatorDateTime (),
				'updated_at' => new sfValidatorDateTime () ) );

		$this->widgetSchema->setNameFormat ( 'ps_feature_branch_synthetic[%s]' );

		$this->errorSchema = new sfValidatorErrorSchema ( $this->validatorSchema );

		$this->setupInheritance ();

		parent::setup ();
	}

	public function getModelName() {

		return 'PsFeatureBranchSynthetic';
	}
}
