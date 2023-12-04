<?php

/**
 * Receipt form base class.
 *
 * @method Receipt getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseReceiptForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                        => new sfWidgetFormInputHidden(),
      'ps_customer_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => true)),
      'student_id'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => false)),
      'title'                     => new sfWidgetFormInputText(),
      'receipt_no'                => new sfWidgetFormInputText(),
      'receipt_date'              => new sfWidgetFormInputText(),
      'payment_order'             => new sfWidgetFormInputText(),
      'file_name'                 => new sfWidgetFormInputText(),
      'collected_amount'          => new sfWidgetFormInputText(),
      'balance_amount'            => new sfWidgetFormInputText(),
      'chietkhau'                 => new sfWidgetFormInputText(),
      'hoantra'                   => new sfWidgetFormInputText(),
      'chietkhau_thangtruoc'      => new sfWidgetFormInputText(),
      'hoantra_thangtruoc'        => new sfWidgetFormInputText(),
      'balance_last_month_amount' => new sfWidgetFormInputText(),
      'late_payment_amount'       => new sfWidgetFormInputText(),
      'is_current'                => new sfWidgetFormInputText(),
      'is_import'                 => new sfWidgetFormInputText(),
      'payment_status'            => new sfWidgetFormInputCheckbox(),
      'relative_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Relative'), 'add_empty' => true)),
      'payment_relative_name'     => new sfWidgetFormInputText(),
      'payment_date'              => new sfWidgetFormInputText(),
      'payment_type'              => new sfWidgetFormInputText(),
      'cashier_name'              => new sfWidgetFormInputText(),
      'note'                      => new sfWidgetFormInputText(),
      'is_public'                 => new sfWidgetFormInputCheckbox(),
      'number_push_notication'    => new sfWidgetFormInputText(),
      'note_edit'                 => new sfWidgetFormInputText(),
      'user_created_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'                => new sfWidgetFormDateTime(),
      'updated_at'                => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'required' => false)),
      'student_id'                => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Student'))),
      'title'                     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'receipt_no'                => new sfValidatorString(array('max_length' => 50)),
      'receipt_date'              => new sfValidatorPass(array('required' => false)),
      'payment_order'             => new sfValidatorInteger(array('required' => false)),
      'file_name'                 => new sfValidatorString(array('max_length' => 255)),
      'collected_amount'          => new sfValidatorPass(array('required' => false)),
      'balance_amount'            => new sfValidatorPass(array('required' => false)),
      'chietkhau'                 => new sfValidatorInteger(array('required' => false)),
      'hoantra'                   => new sfValidatorInteger(array('required' => false)),
      'chietkhau_thangtruoc'      => new sfValidatorPass(array('required' => false)),
      'hoantra_thangtruoc'        => new sfValidatorPass(array('required' => false)),
      'balance_last_month_amount' => new sfValidatorPass(array('required' => false)),
      'late_payment_amount'       => new sfValidatorPass(array('required' => false)),
      'is_current'                => new sfValidatorInteger(array('required' => false)),
      'is_import'                 => new sfValidatorInteger(array('required' => false)),
      'payment_status'            => new sfValidatorBoolean(array('required' => false)),
      'relative_id'               => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Relative'), 'required' => false)),
      'payment_relative_name'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'payment_date'              => new sfValidatorPass(array('required' => false)),
      'payment_type'              => new sfValidatorString(array('max_length' => 2, 'required' => false)),
      'cashier_name'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'note'                      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_public'                 => new sfValidatorBoolean(array('required' => false)),
      'number_push_notication'    => new sfValidatorInteger(array('required' => false)),
      'note_edit'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user_created_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'                => new sfValidatorDateTime(),
      'updated_at'                => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('receipt[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Receipt';
  }

}
