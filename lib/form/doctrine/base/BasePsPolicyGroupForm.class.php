<?php

/**
 * PsPolicyGroup form base class.
 *
 * @method PsPolicyGroup getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsPolicyGroupForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'ps_customer_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => false)),
      'ps_workplace_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => false)),
      'policy_code'     => new sfWidgetFormInputText(),
      'title'           => new sfWidgetFormInputText(),
      'level'           => new sfWidgetFormInputText(),
      'discount'        => new sfWidgetFormInputText(),
      'is_type'         => new sfWidgetFormInputCheckbox(),
      'json_service'    => new sfWidgetFormTextarea(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => false)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'))),
      'ps_workplace_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'))),
      'policy_code'     => new sfValidatorString(array('max_length' => 255)),
      'title'           => new sfValidatorString(array('max_length' => 255)),
      'level'           => new sfValidatorInteger(array('required' => false)),
      'discount'        => new sfValidatorInteger(array('required' => false)),
      'is_type'         => new sfValidatorBoolean(array('required' => false)),
      'json_service'    => new sfValidatorString(array('required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'))),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_policy_group[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsPolicyGroup';
  }

}
