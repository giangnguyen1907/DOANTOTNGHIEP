<?php

/**
 * Receipt filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseReceiptFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ps_customer_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => true)),
      'student_id'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => true)),
      'title'                     => new sfWidgetFormFilterInput(),
      'receipt_no'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'receipt_date'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'payment_order'             => new sfWidgetFormFilterInput(),
      'file_name'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'collected_amount'          => new sfWidgetFormFilterInput(),
      'balance_amount'            => new sfWidgetFormFilterInput(),
      'chietkhau'                 => new sfWidgetFormFilterInput(),
      'hoantra'                   => new sfWidgetFormFilterInput(),
      'chietkhau_thangtruoc'      => new sfWidgetFormFilterInput(),
      'hoantra_thangtruoc'        => new sfWidgetFormFilterInput(),
      'balance_last_month_amount' => new sfWidgetFormFilterInput(),
      'late_payment_amount'       => new sfWidgetFormFilterInput(),
      'is_current'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_import'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'payment_status'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'relative_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Relative'), 'add_empty' => true)),
      'payment_relative_name'     => new sfWidgetFormFilterInput(),
      'payment_date'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'payment_type'              => new sfWidgetFormFilterInput(),
      'cashier_name'              => new sfWidgetFormFilterInput(),
      'note'                      => new sfWidgetFormFilterInput(),
      'is_public'                 => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'number_push_notication'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'note_edit'                 => new sfWidgetFormFilterInput(),
      'user_created_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'                => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'                => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ps_customer_id'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsCustomer'), 'column' => 'id')),
      'student_id'                => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Student'), 'column' => 'id')),
      'title'                     => new sfValidatorPass(array('required' => false)),
      'receipt_no'                => new sfValidatorPass(array('required' => false)),
      'receipt_date'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'payment_order'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'file_name'                 => new sfValidatorPass(array('required' => false)),
      'collected_amount'          => new sfValidatorPass(array('required' => false)),
      'balance_amount'            => new sfValidatorPass(array('required' => false)),
      'chietkhau'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hoantra'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'chietkhau_thangtruoc'      => new sfValidatorPass(array('required' => false)),
      'hoantra_thangtruoc'        => new sfValidatorPass(array('required' => false)),
      'balance_last_month_amount' => new sfValidatorPass(array('required' => false)),
      'late_payment_amount'       => new sfValidatorPass(array('required' => false)),
      'is_current'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_import'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'payment_status'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'relative_id'               => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Relative'), 'column' => 'id')),
      'payment_relative_name'     => new sfValidatorPass(array('required' => false)),
      'payment_date'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'payment_type'              => new sfValidatorPass(array('required' => false)),
      'cashier_name'              => new sfValidatorPass(array('required' => false)),
      'note'                      => new sfValidatorPass(array('required' => false)),
      'is_public'                 => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'number_push_notication'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'note_edit'                 => new sfValidatorPass(array('required' => false)),
      'user_created_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'                => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'                => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('receipt_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Receipt';
  }

  public function getFields()
  {
    return array(
      'id'                        => 'Number',
      'ps_customer_id'            => 'ForeignKey',
      'student_id'                => 'ForeignKey',
      'title'                     => 'Text',
      'receipt_no'                => 'Text',
      'receipt_date'              => 'Date',
      'payment_order'             => 'Number',
      'file_name'                 => 'Text',
      'collected_amount'          => 'Text',
      'balance_amount'            => 'Text',
      'chietkhau'                 => 'Number',
      'hoantra'                   => 'Number',
      'chietkhau_thangtruoc'      => 'Text',
      'hoantra_thangtruoc'        => 'Text',
      'balance_last_month_amount' => 'Text',
      'late_payment_amount'       => 'Text',
      'is_current'                => 'Number',
      'is_import'                 => 'Number',
      'payment_status'            => 'Boolean',
      'relative_id'               => 'ForeignKey',
      'payment_relative_name'     => 'Text',
      'payment_date'              => 'Date',
      'payment_type'              => 'Text',
      'cashier_name'              => 'Text',
      'note'                      => 'Text',
      'is_public'                 => 'Boolean',
      'number_push_notication'    => 'Number',
      'note_edit'                 => 'Text',
      'user_created_id'           => 'ForeignKey',
      'user_updated_id'           => 'ForeignKey',
      'created_at'                => 'Date',
      'updated_at'                => 'Date',
    );
  }
}
