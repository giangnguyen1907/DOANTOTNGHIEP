<?php

/**
 * PsFeeAcounts form base class.
 *
 * @method PsFeeAcounts getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsFeeAcountsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'ps_customer_id'  => new sfWidgetFormInputText(),
      'ps_workplace_id' => new sfWidgetFormInputText(),
      'title'           => new sfWidgetFormInputText(),
      'tk_no'           => new sfWidgetFormInputText(),
      'tk_co'           => new sfWidgetFormInputText(),
      'tk_von'          => new sfWidgetFormInputText(),
      'tk_xuat'         => new sfWidgetFormInputText(),
      'is_type'         => new sfWidgetFormInputText(),
      'user_created_id' => new sfWidgetFormInputText(),
      'user_updated_id' => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'  => new sfValidatorInteger(),
      'ps_workplace_id' => new sfValidatorInteger(),
      'title'           => new sfValidatorString(array('max_length' => 255)),
      'tk_no'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'tk_co'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'tk_von'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'tk_xuat'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_type'         => new sfValidatorInteger(array('required' => false)),
      'user_created_id' => new sfValidatorInteger(),
      'user_updated_id' => new sfValidatorInteger(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_fee_acounts[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsFeeAcounts';
  }

}
