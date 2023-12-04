<?php

/**
 * PsMemberContract form base class.
 *
 * @method PsMemberContract getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsMemberContractForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'ps_customer_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => true)),
      'member_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsMember'), 'add_empty' => false)),
      'code'            => new sfWidgetFormInputText(),
      'factorial'       => new sfWidgetFormInputText(),
      'allowance'       => new sfWidgetFormInputText(),
      'salaries'        => new sfWidgetFormInputText(),
      'start_at'        => new sfWidgetFormDateTime(),
      'expire_at'       => new sfWidgetFormDateTime(),
      'signature_at'    => new sfWidgetFormDateTime(),
      'is_activated'    => new sfWidgetFormInputCheckbox(),
      'note'            => new sfWidgetFormInputText(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'required' => false)),
      'member_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsMember'))),
      'code'            => new sfValidatorString(array('max_length' => 100)),
      'factorial'       => new sfValidatorPass(),
      'allowance'       => new sfValidatorPass(array('required' => false)),
      'salaries'        => new sfValidatorPass(array('required' => false)),
      'start_at'        => new sfValidatorDateTime(array('required' => false)),
      'expire_at'       => new sfValidatorDateTime(array('required' => false)),
      'signature_at'    => new sfValidatorDateTime(array('required' => false)),
      'is_activated'    => new sfValidatorBoolean(array('required' => false)),
      'note'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'PsMemberContract', 'column' => array('code')))
    );

    $this->widgetSchema->setNameFormat('ps_member_contract[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsMemberContract';
  }

}
