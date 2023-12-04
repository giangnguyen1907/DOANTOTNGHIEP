<?php

/**
 * PsReview form base class.
 *
 * @method PsReview getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsReviewForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'ps_customer_id'     => new sfWidgetFormInputText(),
      'ps_workplace_id'    => new sfWidgetFormInputText(),
      'member_id'          => new sfWidgetFormInputText(),
      'student_id'         => new sfWidgetFormInputText(),
      'ps_class_id'        => new sfWidgetFormInputText(),
      'category_review_id' => new sfWidgetFormInputText(),
      'review_relative_id' => new sfWidgetFormInputText(),
      'note'               => new sfWidgetFormTextarea(),
      'status'             => new sfWidgetFormInputText(),
	  'date_at'            => new sfWidgetFormDate(),
      'user_created_id'    => new sfWidgetFormInputText(),
      'user_updated_id'    => new sfWidgetFormInputText(),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'     => new sfValidatorInteger(),
      'ps_workplace_id'    => new sfValidatorInteger(),
      'member_id'          => new sfValidatorInteger(array('required' => false)),
      'student_id'         => new sfValidatorInteger(array('required' => false)),
      'ps_class_id'        => new sfValidatorInteger(array('required' => false)),
      'category_review_id' => new sfValidatorInteger(array('required' => false)),
      'review_relative_id' => new sfValidatorInteger(array('required' => false)),
      'note'               => new sfValidatorString(array('required' => false)),
      'status'             => new sfValidatorInteger(array('required' => false)),
	  'date_at'            => new sfValidatorDate(),
      'user_created_id'    => new sfValidatorInteger(),
      'user_updated_id'    => new sfValidatorInteger(array('required' => false)),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_review[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsReview';
  }

}
