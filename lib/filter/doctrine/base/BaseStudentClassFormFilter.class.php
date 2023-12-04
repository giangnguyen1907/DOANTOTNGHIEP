<?php

/**
 * StudentClass filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseStudentClassFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'myclass_id'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass'), 'add_empty' => true)),
      'from_myclass_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass2'), 'add_empty' => true)),
      'student_id'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => true)),
      'is_activated'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'myclass_mode'              => new sfWidgetFormFilterInput(),
      'start_at'                  => new sfWidgetFormDoctrineChoice(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'model' => $this->getRelatedModelName('DayInMonth'), 'add_empty' => true)),
      'stop_at'                   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'type'                      => new sfWidgetFormFilterInput(),
      'statistic_myclass_id'      => new sfWidgetFormFilterInput(),
      'form_statistic_myclass_id' => new sfWidgetFormFilterInput(),
      'user_created_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'                => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'                => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'myclass_id'                => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('MyClass'), 'column' => 'id')),
      'from_myclass_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('MyClass2'), 'column' => 'id')),
      'student_id'                => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Student'), 'column' => 'id')),
      'is_activated'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'myclass_mode'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'start_at'                  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('DayInMonth'), 'column' => 'id')),
      'stop_at'                   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'type'                      => new sfValidatorPass(array('required' => false)),
      'statistic_myclass_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'form_statistic_myclass_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_created_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'                => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'                => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('student_class_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StudentClass';
  }

  public function getFields()
  {
    return array(
      'id'                        => 'Number',
      'myclass_id'                => 'ForeignKey',
      'from_myclass_id'           => 'ForeignKey',
      'student_id'                => 'ForeignKey',
      'is_activated'              => 'Boolean',
      'myclass_mode'              => 'Number',
      'start_at'                  => 'ForeignKey',
      'stop_at'                   => 'Date',
      'type'                      => 'Text',
      'statistic_myclass_id'      => 'Number',
      'form_statistic_myclass_id' => 'Number',
      'user_created_id'           => 'ForeignKey',
      'user_updated_id'           => 'ForeignKey',
      'created_at'                => 'Date',
      'updated_at'                => 'Date',
    );
  }
}
