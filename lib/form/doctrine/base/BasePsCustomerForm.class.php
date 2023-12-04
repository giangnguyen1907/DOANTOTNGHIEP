<?php

/**
 * PsCustomer form base class.
 *
 * @method PsCustomer getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsCustomerForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'ps_ward_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWard'), 'add_empty' => false)),
      'school_code'       => new sfWidgetFormInputText(),
      'school_name'       => new sfWidgetFormInputText(),
      'title'             => new sfWidgetFormInputText(),
      'address'           => new sfWidgetFormInputText(),
      'tel'               => new sfWidgetFormInputText(),
      'fax'               => new sfWidgetFormInputText(),
      'mobile'            => new sfWidgetFormInputText(),
      'email'             => new sfWidgetFormInputText(),
      'url'               => new sfWidgetFormInputText(),
      'ps_typeschool_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsTypeSchool'), 'add_empty' => true)),
      'is_root'           => new sfWidgetFormInputText(),
      'agent'             => new sfWidgetFormInputText(),
      'principal'         => new sfWidgetFormInputText(),
      'note'              => new sfWidgetFormInputText(),
      'description'       => new sfWidgetFormTextarea(),
      'iorder'            => new sfWidgetFormInputText(),
      'is_activated'      => new sfWidgetFormInputText(),
      'is_deploy'         => new sfWidgetFormInputText(),
      'logo'              => new sfWidgetFormInputText(),
      'activated_at'      => new sfWidgetFormDateTime(),
      'year_data'         => new sfWidgetFormInputText(),
      'cache_data'        => new sfWidgetFormInputText(),
      'user_activated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserActivated'), 'add_empty' => true)),
      'user_created_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_ward_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsWard'))),
      'school_code'       => new sfValidatorString(array('max_length' => 15, 'required' => false)),
      'school_name'       => new sfValidatorString(array('max_length' => 255)),
      'title'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'address'           => new sfValidatorString(array('max_length' => 255)),
      'tel'               => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'fax'               => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'mobile'            => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'email'             => new sfValidatorString(array('max_length' => 255)),
      'url'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'ps_typeschool_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsTypeSchool'), 'required' => false)),
      'is_root'           => new sfValidatorInteger(array('required' => false)),
      'agent'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'principal'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'note'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'       => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'iorder'            => new sfValidatorInteger(array('required' => false)),
      'is_activated'      => new sfValidatorInteger(array('required' => false)),
      'is_deploy'         => new sfValidatorInteger(array('required' => false)),
      'logo'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'activated_at'      => new sfValidatorDateTime(array('required' => false)),
      'year_data'         => new sfValidatorInteger(array('required' => false)),
      'cache_data'        => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'user_activated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserActivated'), 'required' => false)),
      'user_created_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'PsCustomer', 'column' => array('school_code')))
    );

    $this->widgetSchema->setNameFormat('ps_customer[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsCustomer';
  }

}
