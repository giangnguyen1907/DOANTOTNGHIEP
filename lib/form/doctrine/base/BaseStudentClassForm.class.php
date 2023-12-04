<?php

/**
 * StudentClass form base class.
 *
 * @method StudentClass getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseStudentClassForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                        => new sfWidgetFormInputHidden(),
      'myclass_id'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass'), 'add_empty' => true)),
      'from_myclass_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass2'), 'add_empty' => true)),
      'student_id'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => true)),
      'is_activated'              => new sfWidgetFormInputCheckbox(),
      'myclass_mode'              => new sfWidgetFormInputText(),
      'start_at'                  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('DayInMonth'), 'add_empty' => true)),
      'stop_at'                   => new sfWidgetFormDateTime(),
      'type'                      => new sfWidgetFormInputText(),
      'statistic_myclass_id'      => new sfWidgetFormInputText(),
      'form_statistic_myclass_id' => new sfWidgetFormInputText(),
      'user_created_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'                => new sfWidgetFormDateTime(),
      'updated_at'                => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'myclass_id'                => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass'), 'required' => false)),
      'from_myclass_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass2'), 'required' => false)),
      'student_id'                => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'required' => false)),
      'is_activated'              => new sfValidatorBoolean(array('required' => false)),
      'myclass_mode'              => new sfValidatorInteger(array('required' => false)),
      'start_at'                  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('DayInMonth'), 'required' => false)),
      'stop_at'                   => new sfValidatorDateTime(array('required' => false)),
      'type'                      => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'statistic_myclass_id'      => new sfValidatorInteger(array('required' => false)),
      'form_statistic_myclass_id' => new sfValidatorInteger(array('required' => false)),
      'user_created_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'                => new sfValidatorDateTime(),
      'updated_at'                => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('student_class[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StudentClass';
  }

}
