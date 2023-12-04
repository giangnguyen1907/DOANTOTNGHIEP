<?php

/**
 * PsStudentGrowths filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsStudentGrowthsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'student_id'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => true)),
      'height'                  => new sfWidgetFormFilterInput(),
      'weight'                  => new sfWidgetFormFilterInput(),
      'index_height'            => new sfWidgetFormFilterInput(),
      'index_weight'            => new sfWidgetFormFilterInput(),
      'index_tooth'             => new sfWidgetFormFilterInput(),
      'index_throat'            => new sfWidgetFormFilterInput(),
      'index_eye'               => new sfWidgetFormFilterInput(),
      'index_heart'             => new sfWidgetFormFilterInput(),
      'index_lung'              => new sfWidgetFormFilterInput(),
      'index_skin'              => new sfWidgetFormFilterInput(),
      'index_age'               => new sfWidgetFormFilterInput(),
      'date_push_notication'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'number_push_notication'  => new sfWidgetFormFilterInput(),
      'people_make'             => new sfWidgetFormFilterInput(),
      'organization_make'       => new sfWidgetFormFilterInput(),
      'examination_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsExamination'), 'add_empty' => true)),
      'note'                    => new sfWidgetFormFilterInput(),
      'user_push_notication_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserPushNotication'), 'add_empty' => true)),
      'user_created_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'student_id'              => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Student'), 'column' => 'id')),
      'height'                  => new sfValidatorPass(array('required' => false)),
      'weight'                  => new sfValidatorPass(array('required' => false)),
      'index_height'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'index_weight'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'index_tooth'             => new sfValidatorPass(array('required' => false)),
      'index_throat'            => new sfValidatorPass(array('required' => false)),
      'index_eye'               => new sfValidatorPass(array('required' => false)),
      'index_heart'             => new sfValidatorPass(array('required' => false)),
      'index_lung'              => new sfValidatorPass(array('required' => false)),
      'index_skin'              => new sfValidatorPass(array('required' => false)),
      'index_age'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'date_push_notication'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'number_push_notication'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'people_make'             => new sfValidatorPass(array('required' => false)),
      'organization_make'       => new sfValidatorPass(array('required' => false)),
      'examination_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsExamination'), 'column' => 'id')),
      'note'                    => new sfValidatorPass(array('required' => false)),
      'user_push_notication_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserPushNotication'), 'column' => 'id')),
      'user_created_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ps_student_growths_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsStudentGrowths';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'student_id'              => 'ForeignKey',
      'height'                  => 'Text',
      'weight'                  => 'Text',
      'index_height'            => 'Number',
      'index_weight'            => 'Number',
      'index_tooth'             => 'Text',
      'index_throat'            => 'Text',
      'index_eye'               => 'Text',
      'index_heart'             => 'Text',
      'index_lung'              => 'Text',
      'index_skin'              => 'Text',
      'index_age'               => 'Number',
      'date_push_notication'    => 'Date',
      'number_push_notication'  => 'Number',
      'people_make'             => 'Text',
      'organization_make'       => 'Text',
      'examination_id'          => 'ForeignKey',
      'note'                    => 'Text',
      'user_push_notication_id' => 'ForeignKey',
      'user_created_id'         => 'ForeignKey',
      'user_updated_id'         => 'ForeignKey',
      'created_at'              => 'Date',
      'updated_at'              => 'Date',
    );
  }
}
