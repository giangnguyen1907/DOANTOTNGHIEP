<?php

/**
 * ReceivableStudent form base class.
 *
 * @method ReceivableStudent getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseReceivableStudentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'title'           => new sfWidgetFormInputText(),
      'student_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => false)),
      'receivable_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Receivable'), 'add_empty' => true)),
      'service_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Service'), 'add_empty' => true)),
      'by_number'       => new sfWidgetFormInputText(),
      'discount'        => new sfWidgetFormInputText(),
      'discount_amount' => new sfWidgetFormInputText(),
      'spent_number'    => new sfWidgetFormInputText(),
      'unit_price'      => new sfWidgetFormInputText(),
      'amount'          => new sfWidgetFormInputText(),
      'hoantra'         => new sfWidgetFormInputText(),
      'is_late'         => new sfWidgetFormInputCheckbox(),
      'is_number'       => new sfWidgetFormInputText(),
      'number_month'    => new sfWidgetFormInputText(),
      'receivable_at'   => new sfWidgetFormDateTime(),
      'receipt_date'    => new sfWidgetFormInputText(),
      'note'            => new sfWidgetFormInputText(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'student_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Student'))),
      'receivable_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Receivable'), 'required' => false)),
      'service_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Service'), 'required' => false)),
      'by_number'       => new sfValidatorPass(array('required' => false)),
      'discount'        => new sfValidatorPass(array('required' => false)),
      'discount_amount' => new sfValidatorPass(array('required' => false)),
      'spent_number'    => new sfValidatorPass(array('required' => false)),
      'unit_price'      => new sfValidatorPass(array('required' => false)),
      'amount'          => new sfValidatorPass(array('required' => false)),
      'hoantra'         => new sfValidatorPass(array('required' => false)),
      'is_late'         => new sfValidatorBoolean(array('required' => false)),
      'is_number'       => new sfValidatorInteger(array('required' => false)),
      'number_month'    => new sfValidatorInteger(array('required' => false)),
      'receivable_at'   => new sfValidatorDateTime(array('required' => false)),
      'receipt_date'    => new sfValidatorPass(array('required' => false)),
      'note'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('receivable_student[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ReceivableStudent';
  }

}
