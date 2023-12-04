<?php

/**
 * Relative form base class.
 *
 * @method Relative getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseRelativeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'ps_customer_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => true)),
      'ps_workplace_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => true)),
      'first_name'      => new sfWidgetFormInputText(),
      'last_name'       => new sfWidgetFormInputText(),
      'sex'             => new sfWidgetFormInputCheckbox(),
      'birthday'        => new sfWidgetFormInputText(),
      'identity_card'   => new sfWidgetFormInputText(),
      'card_date'       => new sfWidgetFormInputText(),
      'card_local'      => new sfWidgetFormInputText(),
      'image'           => new sfWidgetFormInputText(),
      'avatar'          => new sfWidgetFormInputText(),
      'ethnic_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsEthnic'), 'add_empty' => true)),
      'religion_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsReligion'), 'add_empty' => true)),
      'address'         => new sfWidgetFormInputText(),
      'phone'           => new sfWidgetFormInputText(),
      'mobile'          => new sfWidgetFormInputText(),
      'email'           => new sfWidgetFormInputText(),
      'job'             => new sfWidgetFormInputText(),
      'nationality'     => new sfWidgetFormInputText(),
      'year_data'       => new sfWidgetFormInputText(),
      'deleted_at'      => new sfWidgetFormDateTime(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'required' => false)),
      'ps_workplace_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'required' => false)),
      'first_name'      => new sfValidatorString(array('max_length' => 255)),
      'last_name'       => new sfValidatorString(array('max_length' => 255)),
      'sex'             => new sfValidatorBoolean(array('required' => false)),
      'birthday'        => new sfValidatorPass(array('required' => false)),
      'identity_card'   => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'card_date'       => new sfValidatorPass(array('required' => false)),
      'card_local'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'image'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'avatar'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'ethnic_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsEthnic'), 'required' => false)),
      'religion_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsReligion'), 'required' => false)),
      'address'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'phone'           => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'mobile'          => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'email'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'job'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'nationality'     => new sfValidatorString(array('max_length' => 2, 'required' => false)),
      'year_data'       => new sfValidatorInteger(array('required' => false)),
      'deleted_at'      => new sfValidatorDateTime(array('required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('relative[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Relative';
  }

}
