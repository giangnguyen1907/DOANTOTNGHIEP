<?php

/**
 * StudentService filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseStudentServiceFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'student_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => true)),
      'service_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Service'), 'add_empty' => true)),
      'regularity_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsRegularity'), 'add_empty' => true)),
      'ps_service_course_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsServiceCourses'), 'add_empty' => true)),
      'number_month'         => new sfWidgetFormFilterInput(),
      'discount'             => new sfWidgetFormFilterInput(),
      'discount_amount'      => new sfWidgetFormFilterInput(),
      'note'                 => new sfWidgetFormFilterInput(),
      'delete_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'user_deleted_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserDeleted'), 'add_empty' => true)),
      'user_created_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'student_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Student'), 'column' => 'id')),
      'service_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Service'), 'column' => 'id')),
      'regularity_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsRegularity'), 'column' => 'id')),
      'ps_service_course_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsServiceCourses'), 'column' => 'id')),
      'number_month'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'discount'             => new sfValidatorPass(array('required' => false)),
      'discount_amount'      => new sfValidatorPass(array('required' => false)),
      'note'                 => new sfValidatorPass(array('required' => false)),
      'delete_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'user_deleted_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserDeleted'), 'column' => 'id')),
      'user_created_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('student_service_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StudentService';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'student_id'           => 'ForeignKey',
      'service_id'           => 'ForeignKey',
      'regularity_id'        => 'ForeignKey',
      'ps_service_course_id' => 'ForeignKey',
      'number_month'         => 'Number',
      'discount'             => 'Text',
      'discount_amount'      => 'Text',
      'note'                 => 'Text',
      'delete_at'            => 'Date',
      'user_deleted_id'      => 'ForeignKey',
      'user_created_id'      => 'ForeignKey',
      'user_updated_id'      => 'ForeignKey',
      'created_at'           => 'Date',
      'updated_at'           => 'Date',
    );
  }
}
