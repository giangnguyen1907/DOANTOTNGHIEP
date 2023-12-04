<?php

/**
 * PsFeatureBranchTimeMyClass filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsFeatureBranchTimeMyClassFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ps_feature_branch_time_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('FeatureBranchTimes'), 'add_empty' => true)),
      'ps_myclass_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass'), 'add_empty' => true)),
      'ps_class_room'             => new sfWidgetFormFilterInput(),
      'note'                      => new sfWidgetFormFilterInput(),
      'user_created_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'                => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'                => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ps_feature_branch_time_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('FeatureBranchTimes'), 'column' => 'id')),
      'ps_myclass_id'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('MyClass'), 'column' => 'id')),
      'ps_class_room'             => new sfValidatorPass(array('required' => false)),
      'note'                      => new sfValidatorPass(array('required' => false)),
      'user_created_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'                => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'                => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ps_feature_branch_time_my_class_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsFeatureBranchTimeMyClass';
  }

  public function getFields()
  {
    return array(
      'id'                        => 'Number',
      'ps_feature_branch_time_id' => 'ForeignKey',
      'ps_myclass_id'             => 'ForeignKey',
      'ps_class_room'             => 'Text',
      'note'                      => 'Text',
      'user_created_id'           => 'ForeignKey',
      'user_updated_id'           => 'ForeignKey',
      'created_at'                => 'Date',
      'updated_at'                => 'Date',
    );
  }
}
