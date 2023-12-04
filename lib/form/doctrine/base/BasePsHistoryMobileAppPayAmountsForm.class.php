<?php

/**
 * PsHistoryMobileAppPayAmounts form base class.
 *
 * @method PsHistoryMobileAppPayAmounts getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsHistoryMobileAppPayAmountsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'user_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserHistoryMobileAppPayAmounts'), 'add_empty' => false)),
      'amount'         => new sfWidgetFormInputText(),
      'pay_created_at' => new sfWidgetFormDateTime(),
      'pay_type'       => new sfWidgetFormInputText(),
      'description'    => new sfWidgetFormInputText(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserHistoryMobileAppPayAmounts'))),
      'amount'         => new sfValidatorPass(array('required' => false)),
      'pay_created_at' => new sfValidatorDateTime(array('required' => false)),
      'pay_type'       => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'description'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_history_mobile_app_pay_amounts[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsHistoryMobileAppPayAmounts';
  }

}
