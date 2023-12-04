<?php

/**
 * PsFeeReceipt form base class.
 *
 * @method PsFeeReceipt getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsFeeReceiptForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'ps_customer_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => true)),
      'student_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => false)),
      'title'                  => new sfWidgetFormInputText(),
      'receipt_no'             => new sfWidgetFormInputText(),
      'receipt_date'           => new sfWidgetFormInputText(),
      'receivable_amount'      => new sfWidgetFormInputText(),
      'collected_amount'       => new sfWidgetFormInputText(),
      'balance_amount'         => new sfWidgetFormInputText(),
      'payment_status'         => new sfWidgetFormInputCheckbox(),
      'payment_relative'       => new sfWidgetFormInputText(),
      'payment_date'           => new sfWidgetFormInputText(),
      'number_push_notication' => new sfWidgetFormInputText(),
      'note'                   => new sfWidgetFormTextarea(),
      'is_public'              => new sfWidgetFormInputCheckbox(),
      'user_created_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'             => new sfWidgetFormDateTime(),
      'updated_at'             => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'required' => false)),
      'student_id'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Student'))),
      'title'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'receipt_no'             => new sfValidatorString(array('max_length' => 50)),
      'receipt_date'           => new sfValidatorPass(array('required' => false)),
      'receivable_amount'      => new sfValidatorPass(array('required' => false)),
      'collected_amount'       => new sfValidatorPass(array('required' => false)),
      'balance_amount'         => new sfValidatorPass(array('required' => false)),
      'payment_status'         => new sfValidatorBoolean(array('required' => false)),
      'payment_relative'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'payment_date'           => new sfValidatorPass(array('required' => false)),
      'number_push_notication' => new sfValidatorInteger(array('required' => false)),
      'note'                   => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'is_public'              => new sfValidatorBoolean(array('required' => false)),
      'user_created_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'             => new sfValidatorDateTime(),
      'updated_at'             => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_fee_receipt[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsFeeReceipt';
  }

}
