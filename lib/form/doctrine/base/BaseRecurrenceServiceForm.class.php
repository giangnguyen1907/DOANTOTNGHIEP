<?php

/**
 * RecurrenceService form base class.
 *
 * @method RecurrenceService getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseRecurrenceServiceForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'student_service_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('StudentService'), 'add_empty' => true)),
      'amount'             => new sfWidgetFormInputText(),
      'effectives_at'      => new sfWidgetFormDateTime(),
      'expires_at'         => new sfWidgetFormDateTime(),
      'recurrence_at'      => new sfWidgetFormDateTime(),
      'user_created_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'student_service_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('StudentService'), 'required' => false)),
      'amount'             => new sfValidatorPass(array('required' => false)),
      'effectives_at'      => new sfValidatorDateTime(array('required' => false)),
      'expires_at'         => new sfValidatorDateTime(array('required' => false)),
      'recurrence_at'      => new sfValidatorDateTime(array('required' => false)),
      'user_created_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('recurrence_service[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'RecurrenceService';
  }

}
