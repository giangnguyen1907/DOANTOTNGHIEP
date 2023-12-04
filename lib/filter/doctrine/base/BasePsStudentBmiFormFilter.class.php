<?php

/**
 * PsStudentBmi filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsStudentBmiFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'min_height1'     => new sfWidgetFormFilterInput(),
      'max_height1'     => new sfWidgetFormFilterInput(),
      'medium_height'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'min_height'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'max_height'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'min_weight1'     => new sfWidgetFormFilterInput(),
      'max_weight1'     => new sfWidgetFormFilterInput(),
      'medium_weight'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'min_weight'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'max_weight'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sex'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_month'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'note'            => new sfWidgetFormFilterInput(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'min_height1'     => new sfValidatorPass(array('required' => false)),
      'max_height1'     => new sfValidatorPass(array('required' => false)),
      'medium_height'   => new sfValidatorPass(array('required' => false)),
      'min_height'      => new sfValidatorPass(array('required' => false)),
      'max_height'      => new sfValidatorPass(array('required' => false)),
      'min_weight1'     => new sfValidatorPass(array('required' => false)),
      'max_weight1'     => new sfValidatorPass(array('required' => false)),
      'medium_weight'   => new sfValidatorPass(array('required' => false)),
      'min_weight'      => new sfValidatorPass(array('required' => false)),
      'max_weight'      => new sfValidatorPass(array('required' => false)),
      'sex'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_month'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'note'            => new sfValidatorPass(array('required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ps_student_bmi_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsStudentBmi';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'min_height1'     => 'Text',
      'max_height1'     => 'Text',
      'medium_height'   => 'Text',
      'min_height'      => 'Text',
      'max_height'      => 'Text',
      'min_weight1'     => 'Text',
      'max_weight1'     => 'Text',
      'medium_weight'   => 'Text',
      'min_weight'      => 'Text',
      'max_weight'      => 'Text',
      'sex'             => 'Boolean',
      'is_month'        => 'Number',
      'note'            => 'Text',
      'user_created_id' => 'ForeignKey',
      'user_updated_id' => 'ForeignKey',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
