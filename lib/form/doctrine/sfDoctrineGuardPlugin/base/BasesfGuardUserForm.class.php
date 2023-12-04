<?php

/**
 * sfGuardUser form base class.
 *
 * @method sfGuardUser getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasesfGuardUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'ps_customer_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => true)),
      'user_key'              => new sfWidgetFormTextarea(),
      'member_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsMember'), 'add_empty' => true)),
      'user_type'             => new sfWidgetFormInputText(),
      'manager_type'          => new sfWidgetFormInputText(),
      'first_name'            => new sfWidgetFormInputText(),
      'last_name'             => new sfWidgetFormInputText(),
      'email_address'         => new sfWidgetFormInputText(),
      'username'              => new sfWidgetFormInputText(),
      'password'              => new sfWidgetFormInputText(),
      'avatar'                => new sfWidgetFormInputText(),
      'app_device_id'         => new sfWidgetFormTextarea(),
      'app_config'            => new sfWidgetFormInputText(),
      'api_token'             => new sfWidgetFormTextarea(),
      'token_last_login'      => new sfWidgetFormDateTime(),
      'token_expires_in'      => new sfWidgetFormDateTime(),
      'refresh_token'         => new sfWidgetFormTextarea(),
      'notification_token'    => new sfWidgetFormTextarea(),
      'notification_at'       => new sfWidgetFormInputText(),
      'osname'                => new sfWidgetFormInputText(),
      'osvesion'              => new sfWidgetFormInputText(),
      'algorithm'             => new sfWidgetFormInputText(),
      'salt'                  => new sfWidgetFormInputText(),
      'is_active'             => new sfWidgetFormInputText(),
      'is_super_admin'        => new sfWidgetFormInputCheckbox(),
      'is_global_super_admin' => new sfWidgetFormInputCheckbox(),
      'last_login'            => new sfWidgetFormDateTime(),
      'user_created_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'            => new sfWidgetFormDateTime(),
      'updated_at'            => new sfWidgetFormDateTime(),
      'groups_list'           => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup')),
      'permissions_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardPermission')),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'required' => false)),
      'user_key'              => new sfValidatorString(array('max_length' => 300, 'required' => false)),
      'member_id'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsMember'), 'required' => false)),
      'user_type'             => new sfValidatorString(array('max_length' => 1)),
      'manager_type'          => new sfValidatorString(array('max_length' => 1, 'required' => false)),
      'first_name'            => new sfValidatorString(array('max_length' => 255)),
      'last_name'             => new sfValidatorString(array('max_length' => 255)),
      'email_address'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'username'              => new sfValidatorString(array('max_length' => 128)),
      'password'              => new sfValidatorString(array('max_length' => 128)),
      'avatar'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'app_device_id'         => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'app_config'            => new sfValidatorPass(array('required' => false)),
      'api_token'             => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'token_last_login'      => new sfValidatorDateTime(array('required' => false)),
      'token_expires_in'      => new sfValidatorDateTime(array('required' => false)),
      'refresh_token'         => new sfValidatorString(array('max_length' => 256, 'required' => false)),
      'notification_token'    => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'notification_at'       => new sfValidatorPass(array('required' => false)),
      'osname'                => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'osvesion'              => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'algorithm'             => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'salt'                  => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'is_active'             => new sfValidatorInteger(array('required' => false)),
      'is_super_admin'        => new sfValidatorBoolean(array('required' => false)),
      'is_global_super_admin' => new sfValidatorBoolean(array('required' => false)),
      'last_login'            => new sfValidatorDateTime(array('required' => false)),
      'user_created_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'            => new sfValidatorDateTime(),
      'updated_at'            => new sfValidatorDateTime(),
      'groups_list'           => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup', 'required' => false)),
      'permissions_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardPermission', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'sfGuardUser', 'column' => array('username'))),
        new sfValidatorDoctrineUnique(array('model' => 'sfGuardUser', 'column' => array('member_id', 'user_type'))),
      ))
    );

    $this->widgetSchema->setNameFormat('sf_guard_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfGuardUser';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['groups_list']))
    {
      $this->setDefault('groups_list', $this->object->Groups->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['permissions_list']))
    {
      $this->setDefault('permissions_list', $this->object->Permissions->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveGroupsList($con);
    $this->savePermissionsList($con);

    parent::doSave($con);
  }

  public function saveGroupsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['groups_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Groups->getPrimaryKeys();
    $values = $this->getValue('groups_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Groups', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Groups', array_values($link));
    }
  }

  public function savePermissionsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['permissions_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Permissions->getPrimaryKeys();
    $values = $this->getValue('permissions_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Permissions', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Permissions', array_values($link));
    }
  }

}
