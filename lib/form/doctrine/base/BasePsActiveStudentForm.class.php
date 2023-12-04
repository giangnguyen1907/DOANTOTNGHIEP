<?php

/**
 * PsActiveStudent form base class.
 *
 * @method PsActiveStudent getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsActiveStudentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'ps_class_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass'), 'add_empty' => false)),
      'start_at'        => new sfWidgetFormDate(),
      'end_at'          => new sfWidgetFormDate(),
      'start_time'      => new sfWidgetFormInputText(),
      'end_time'        => new sfWidgetFormInputText(),
      'title'           => new sfWidgetFormInputText(),
      'note'            => new sfWidgetFormInputText(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => false)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_class_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass'))),
      'start_at'        => new sfValidatorDate(),
      'end_at'          => new sfValidatorDate(array('required' => false)),
      'start_time'      => new sfValidatorString(array('max_length' => 255)),
      'end_time'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'title'           => new sfValidatorString(),
      'note'            => new sfValidatorString(),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'))),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_active_student[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsActiveStudent';
  }

}
