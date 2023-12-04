<?php

/**
 * PsMobileApps form base class.
 *
 * @method PsMobileApps getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsMobileAppsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'user_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserMobileApps'), 'add_empty' => false)),
      'device_id'           => new sfWidgetFormInputText(),
      'is_activated'        => new sfWidgetFormInputText(),
      'active_created_at'   => new sfWidgetFormDateTime(),
      'status_used'         => new sfWidgetFormInputText(),
      'osname'              => new sfWidgetFormInputText(),
      'osvesion'            => new sfWidgetFormInputText(),
      'network_name'        => new sfWidgetFormInputText(),
      'mobile_network_type' => new sfWidgetFormInputText(),
      'params'              => new sfWidgetFormTextarea(),
      'user_created_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'          => new sfWidgetFormDateTime(),
      'updated_at'          => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_id'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserMobileApps'))),
      'device_id'           => new sfValidatorString(array('max_length' => 50)),
      'is_activated'        => new sfValidatorInteger(array('required' => false)),
      'active_created_at'   => new sfValidatorDateTime(array('required' => false)),
      'status_used'         => new sfValidatorInteger(array('required' => false)),
      'osname'              => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'osvesion'            => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'network_name'        => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'mobile_network_type' => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'params'              => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'user_created_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'          => new sfValidatorDateTime(),
      'updated_at'          => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_mobile_apps[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsMobileApps';
  }

}
