<?php

/**
 * CollectedReceipt form base class.
 *
 * @method CollectedReceipt getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCollectedReceiptForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'collected_student_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('CollectedStudent'), 'add_empty' => false)),
      'receipt_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Receipt'), 'add_empty' => true)),
      'spent_number'         => new sfWidgetFormInputText(),
      'amount'               => new sfWidgetFormInputText(),
      'pay_amount'           => new sfWidgetFormInputText(),
      'created_at'           => new sfWidgetFormDateTime(),
      'updated_at'           => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'collected_student_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('CollectedStudent'))),
      'receipt_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Receipt'), 'required' => false)),
      'spent_number'         => new sfValidatorPass(array('required' => false)),
      'amount'               => new sfValidatorPass(array('required' => false)),
      'pay_amount'           => new sfValidatorPass(array('required' => false)),
      'created_at'           => new sfValidatorDateTime(),
      'updated_at'           => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('collected_receipt[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CollectedReceipt';
  }

}
