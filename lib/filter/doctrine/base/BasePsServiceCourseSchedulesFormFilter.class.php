<?php

/**
 * PsServiceCourseSchedules filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsServiceCourseSchedulesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ps_service_course_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsServiceCourses'), 'add_empty' => true)),
      'ps_class_room_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsClassRooms'), 'add_empty' => true)),
      'date_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'start_time_at'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'end_time_at'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'note'                 => new sfWidgetFormFilterInput(),
      'is_activated'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'user_created_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ps_service_course_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsServiceCourses'), 'column' => 'id')),
      'ps_class_room_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsClassRooms'), 'column' => 'id')),
      'date_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'start_time_at'        => new sfValidatorPass(array('required' => false)),
      'end_time_at'          => new sfValidatorPass(array('required' => false)),
      'note'                 => new sfValidatorPass(array('required' => false)),
      'is_activated'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'user_created_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ps_service_course_schedules_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsServiceCourseSchedules';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'ps_service_course_id' => 'ForeignKey',
      'ps_class_room_id'     => 'ForeignKey',
      'date_at'              => 'Date',
      'start_time_at'        => 'Text',
      'end_time_at'          => 'Text',
      'note'                 => 'Text',
      'is_activated'         => 'Boolean',
      'user_created_id'      => 'ForeignKey',
      'user_updated_id'      => 'ForeignKey',
      'created_at'           => 'Date',
      'updated_at'           => 'Date',
    );
  }
}
