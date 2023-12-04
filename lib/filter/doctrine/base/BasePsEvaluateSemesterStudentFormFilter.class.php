<?php

/**
 * PsEvaluateSemesterStudent filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsEvaluateSemesterStudentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'school_year_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsSchoolYear'), 'add_empty' => true)),
      'ps_customer_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => true)),
      'ps_semester'     => new sfWidgetFormFilterInput(),
      'student_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => true)),
      'symbol_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsEvaluateIndexSymbol'), 'add_empty' => true)),
      'number'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'school_year_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsSchoolYear'), 'column' => 'id')),
      'ps_customer_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsCustomer'), 'column' => 'id')),
      'ps_semester'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'student_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Student'), 'column' => 'id')),
      'symbol_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsEvaluateIndexSymbol'), 'column' => 'id')),
      'number'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_created_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ps_evaluate_semester_student_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsEvaluateSemesterStudent';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'school_year_id'  => 'ForeignKey',
      'ps_customer_id'  => 'ForeignKey',
      'ps_semester'     => 'Number',
      'student_id'      => 'ForeignKey',
      'symbol_id'       => 'ForeignKey',
      'number'          => 'Number',
      'user_created_id' => 'ForeignKey',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
