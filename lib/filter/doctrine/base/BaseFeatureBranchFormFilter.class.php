<?php

/**
 * FeatureBranch filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseFeatureBranchFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'school_year_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsSchoolYear'), 'add_empty' => true)),
      'feature_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Feature'), 'add_empty' => true)),
      'ps_workplace_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => true)),
      'ps_obj_group_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsObjectGroups'), 'add_empty' => true)),
      'name'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'mode'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'note'                 => new sfWidgetFormFilterInput(),
      'iorder'               => new sfWidgetFormFilterInput(),
      'ps_image_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsImages'), 'add_empty' => true)),
      'is_activated'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_continuity'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_study'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_depend_attendance' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'number_option'        => new sfWidgetFormFilterInput(),
      'user_created_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'school_year_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsSchoolYear'), 'column' => 'id')),
      'feature_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Feature'), 'column' => 'id')),
      'ps_workplace_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsWorkPlaces'), 'column' => 'id')),
      'ps_obj_group_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsObjectGroups'), 'column' => 'id')),
      'name'                 => new sfValidatorPass(array('required' => false)),
      'mode'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'note'                 => new sfValidatorPass(array('required' => false)),
      'iorder'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ps_image_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsImages'), 'column' => 'id')),
      'is_activated'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_continuity'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_study'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_depend_attendance' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'number_option'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_created_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('feature_branch_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'FeatureBranch';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'school_year_id'       => 'ForeignKey',
      'feature_id'           => 'ForeignKey',
      'ps_workplace_id'      => 'ForeignKey',
      'ps_obj_group_id'      => 'ForeignKey',
      'name'                 => 'Text',
      'mode'                 => 'Number',
      'note'                 => 'Text',
      'iorder'               => 'Number',
      'ps_image_id'          => 'ForeignKey',
      'is_activated'         => 'Boolean',
      'is_continuity'        => 'Boolean',
      'is_study'             => 'Boolean',
      'is_depend_attendance' => 'Boolean',
      'number_option'        => 'Number',
      'user_created_id'      => 'ForeignKey',
      'user_updated_id'      => 'ForeignKey',
      'created_at'           => 'Date',
      'updated_at'           => 'Date',
    );
  }
}
