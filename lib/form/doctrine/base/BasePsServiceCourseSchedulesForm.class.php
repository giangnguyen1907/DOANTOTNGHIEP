<?php

/**
 * PsServiceCourseSchedules form base class.
 *
 * @method PsServiceCourseSchedules getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsServiceCourseSchedulesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'ps_service_course_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsServiceCourses'), 'add_empty' => false)),
      'ps_class_room_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsClassRooms'), 'add_empty' => true)),
      'date_at'              => new sfWidgetFormDate(),
      'start_time_at'        => new sfWidgetFormTime(),
      'end_time_at'          => new sfWidgetFormTime(),
      'note'                 => new sfWidgetFormInputText(),
      'is_activated'         => new sfWidgetFormInputCheckbox(),
      'user_created_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'           => new sfWidgetFormDateTime(),
      'updated_at'           => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_service_course_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsServiceCourses'))),
      'ps_class_room_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsClassRooms'), 'required' => false)),
      'date_at'              => new sfValidatorDate(),
      'start_time_at'        => new sfValidatorTime(),
      'end_time_at'          => new sfValidatorTime(),
      'note'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_activated'         => new sfValidatorBoolean(array('required' => false)),
      'user_created_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'           => new sfValidatorDateTime(),
      'updated_at'           => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_service_course_schedules[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsServiceCourseSchedules';
  }

}
