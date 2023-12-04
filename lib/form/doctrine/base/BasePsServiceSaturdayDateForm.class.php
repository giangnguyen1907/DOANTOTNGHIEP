<?php

/**
 * PsServiceSaturdayDate form base class.
 *
 * @method PsServiceSaturdayDate getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsServiceSaturdayDateForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'ps_service_saturday_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsServiceSaturday'), 'add_empty' => false)),
      'student_id'             => new sfWidgetFormInputText(),
      'service_date'           => new sfWidgetFormDateTime(),
      'note'                   => new sfWidgetFormInputText(),
      'is_status'              => new sfWidgetFormInputText(),
      'feeback_note'           => new sfWidgetFormInputText(),
      'deleted_at'             => new sfWidgetFormDateTime(),
      'user_created_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'             => new sfWidgetFormDateTime(),
      'updated_at'             => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_service_saturday_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsServiceSaturday'))),
      'student_id'             => new sfValidatorInteger(),
      'service_date'           => new sfValidatorDateTime(),
      'note'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_status'              => new sfValidatorInteger(array('required' => false)),
      'feeback_note'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'deleted_at'             => new sfValidatorDateTime(array('required' => false)),
      'user_created_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'             => new sfValidatorDateTime(),
      'updated_at'             => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'PsServiceSaturdayDate', 'column' => array('student_id', 'service_date')))
    );

    $this->widgetSchema->setNameFormat('ps_service_saturday_date[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsServiceSaturdayDate';
  }

}
