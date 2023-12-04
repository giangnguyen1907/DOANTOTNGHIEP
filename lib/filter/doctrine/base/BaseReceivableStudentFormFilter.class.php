<?php

/**
 * ReceivableStudent filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseReceivableStudentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'           => new sfWidgetFormFilterInput(),
      'student_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => true)),
      'receivable_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Receivable'), 'add_empty' => true)),
      'service_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Service'), 'add_empty' => true)),
      'by_number'       => new sfWidgetFormFilterInput(),
      'discount'        => new sfWidgetFormFilterInput(),
      'discount_amount' => new sfWidgetFormFilterInput(),
      'spent_number'    => new sfWidgetFormFilterInput(),
      'unit_price'      => new sfWidgetFormFilterInput(),
      'amount'          => new sfWidgetFormFilterInput(),
      'hoantra'         => new sfWidgetFormFilterInput(),
      'is_late'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_number'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'number_month'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'receivable_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'receipt_date'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'note'            => new sfWidgetFormFilterInput(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'title'           => new sfValidatorPass(array('required' => false)),
      'student_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Student'), 'column' => 'id')),
      'receivable_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Receivable'), 'column' => 'id')),
      'service_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Service'), 'column' => 'id')),
      'by_number'       => new sfValidatorPass(array('required' => false)),
      'discount'        => new sfValidatorPass(array('required' => false)),
      'discount_amount' => new sfValidatorPass(array('required' => false)),
      'spent_number'    => new sfValidatorPass(array('required' => false)),
      'unit_price'      => new sfValidatorPass(array('required' => false)),
      'amount'          => new sfValidatorPass(array('required' => false)),
      'hoantra'         => new sfValidatorPass(array('required' => false)),
      'is_late'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_number'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'number_month'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'receivable_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'receipt_date'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'note'            => new sfValidatorPass(array('required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('receivable_student_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ReceivableStudent';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'title'           => 'Text',
      'student_id'      => 'ForeignKey',
      'receivable_id'   => 'ForeignKey',
      'service_id'      => 'ForeignKey',
      'by_number'       => 'Text',
      'discount'        => 'Text',
      'discount_amount' => 'Text',
      'spent_number'    => 'Text',
      'unit_price'      => 'Text',
      'amount'          => 'Text',
      'hoantra'         => 'Text',
      'is_late'         => 'Boolean',
      'is_number'       => 'Number',
      'number_month'    => 'Number',
      'receivable_at'   => 'Date',
      'receipt_date'    => 'Date',
      'note'            => 'Text',
      'user_created_id' => 'ForeignKey',
      'user_updated_id' => 'ForeignKey',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
