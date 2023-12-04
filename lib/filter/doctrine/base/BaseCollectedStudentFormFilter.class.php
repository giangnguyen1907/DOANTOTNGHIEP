<?php

/**
 * CollectedStudent filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCollectedStudentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'student_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => true)),
      'receivable_student_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ReceivableStudent'), 'add_empty' => true)),
      'collected_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'amount'                => new sfWidgetFormFilterInput(),
      'note'                  => new sfWidgetFormFilterInput(),
      'created_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'student_id'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Student'), 'column' => 'id')),
      'receivable_student_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ReceivableStudent'), 'column' => 'id')),
      'collected_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'amount'                => new sfValidatorPass(array('required' => false)),
      'note'                  => new sfValidatorPass(array('required' => false)),
      'created_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('collected_student_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CollectedStudent';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'student_id'            => 'ForeignKey',
      'receivable_student_id' => 'ForeignKey',
      'collected_at'          => 'Date',
      'amount'                => 'Text',
      'note'                  => 'Text',
      'created_at'            => 'Date',
      'updated_at'            => 'Date',
    );
  }
}
