<?php

/**
 * FeatureBranchTimes filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseFeatureBranchTimesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ps_feature_branch_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('FeatureBranch'), 'add_empty' => true)),
      'ps_class_room_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsClassRooms'), 'add_empty' => true)),
      'start_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'end_at'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'start_time'           => new sfWidgetFormFilterInput(),
      'end_time'             => new sfWidgetFormFilterInput(),
      'is_saturday'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_sunday'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'note'                 => new sfWidgetFormFilterInput(),
      'note_class_name'      => new sfWidgetFormFilterInput(),
      'user_created_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ps_feature_branch_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('FeatureBranch'), 'column' => 'id')),
      'ps_class_room_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsClassRooms'), 'column' => 'id')),
      'start_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'end_at'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'start_time'           => new sfValidatorPass(array('required' => false)),
      'end_time'             => new sfValidatorPass(array('required' => false)),
      'is_saturday'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_sunday'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'note'                 => new sfValidatorPass(array('required' => false)),
      'note_class_name'      => new sfValidatorPass(array('required' => false)),
      'user_created_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('feature_branch_times_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'FeatureBranchTimes';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'ps_feature_branch_id' => 'ForeignKey',
      'ps_class_room_id'     => 'ForeignKey',
      'start_at'             => 'Date',
      'end_at'               => 'Date',
      'start_time'           => 'Text',
      'end_time'             => 'Text',
      'is_saturday'          => 'Boolean',
      'is_sunday'            => 'Boolean',
      'note'                 => 'Text',
      'note_class_name'      => 'Text',
      'user_created_id'      => 'ForeignKey',
      'user_updated_id'      => 'ForeignKey',
      'created_at'           => 'Date',
      'updated_at'           => 'Date',
    );
  }
}
