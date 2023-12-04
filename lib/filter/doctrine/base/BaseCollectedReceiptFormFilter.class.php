<?php

/**
 * CollectedReceipt filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCollectedReceiptFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'collected_student_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('CollectedStudent'), 'add_empty' => true)),
      'receipt_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Receipt'), 'add_empty' => true)),
      'spent_number'         => new sfWidgetFormFilterInput(),
      'amount'               => new sfWidgetFormFilterInput(),
      'pay_amount'           => new sfWidgetFormFilterInput(),
      'created_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'collected_student_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('CollectedStudent'), 'column' => 'id')),
      'receipt_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Receipt'), 'column' => 'id')),
      'spent_number'         => new sfValidatorPass(array('required' => false)),
      'amount'               => new sfValidatorPass(array('required' => false)),
      'pay_amount'           => new sfValidatorPass(array('required' => false)),
      'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('collected_receipt_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CollectedReceipt';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'collected_student_id' => 'ForeignKey',
      'receipt_id'           => 'ForeignKey',
      'spent_number'         => 'Text',
      'amount'               => 'Text',
      'pay_amount'           => 'Text',
      'created_at'           => 'Date',
      'updated_at'           => 'Date',
    );
  }
}
