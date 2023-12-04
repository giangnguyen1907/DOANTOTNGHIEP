<?php

/**
 * sfGuardUser filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasesfGuardUserFormFilter extends BaseFormFilterDoctrine

{
  public function setup()
  {
    $this->setWidgets(array(
      'ps_customer_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => true)),
      'user_key'              => new sfWidgetFormFilterInput(),
      'member_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsMember'), 'add_empty' => true)),
      'user_type'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'manager_type'          => new sfWidgetFormFilterInput(),
      'first_name'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'last_name'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'email_address'         => new sfWidgetFormFilterInput(),
      'username'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'password'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'avatar'                => new sfWidgetFormFilterInput(),
      'app_device_id'         => new sfWidgetFormFilterInput(),
      'app_config'            => new sfWidgetFormFilterInput(),
      'api_token'             => new sfWidgetFormFilterInput(),
      'token_last_login'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'token_expires_in'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'refresh_token'         => new sfWidgetFormFilterInput(),
      'notification_token'    => new sfWidgetFormFilterInput(),
      'notification_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'osname'                => new sfWidgetFormFilterInput(),
      'osvesion'              => new sfWidgetFormFilterInput(),
      'algorithm'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'salt'                  => new sfWidgetFormFilterInput(),
      'is_active'             => new sfWidgetFormFilterInput(),
      'is_super_admin'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_global_super_admin' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'last_login'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'user_created_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'groups_list'           => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup')),
      'permissions_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardPermission')),
    ));

    $this->setValidators(array(
      'ps_customer_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsCustomer'), 'column' => 'id')),
      'user_key'              => new sfValidatorPass(array('required' => false)),
      'member_id'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsMember'), 'column' => 'id')),
      'user_type'             => new sfValidatorPass(array('required' => false)),
      'manager_type'          => new sfValidatorPass(array('required' => false)),
      'first_name'            => new sfValidatorPass(array('required' => false)),
      'last_name'             => new sfValidatorPass(array('required' => false)),
      'email_address'         => new sfValidatorPass(array('required' => false)),
      'username'              => new sfValidatorPass(array('required' => false)),
      'password'              => new sfValidatorPass(array('required' => false)),
      'avatar'                => new sfValidatorPass(array('required' => false)),
      'app_device_id'         => new sfValidatorPass(array('required' => false)),
      'app_config'            => new sfValidatorPass(array('required' => false)),
      'api_token'             => new sfValidatorPass(array('required' => false)),
      'token_last_login'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'token_expires_in'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'refresh_token'         => new sfValidatorPass(array('required' => false)),
      'notification_token'    => new sfValidatorPass(array('required' => false)),
      'notification_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'osname'                => new sfValidatorPass(array('required' => false)),
      'osvesion'              => new sfValidatorPass(array('required' => false)),
      'algorithm'             => new sfValidatorPass(array('required' => false)),
      'salt'                  => new sfValidatorPass(array('required' => false)),
      'is_active'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_super_admin'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_global_super_admin' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'last_login'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'user_created_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'groups_list'           => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup', 'required' => false)),
      'permissions_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardPermission', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_guard_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.sfGuardUserPermission sfGuardUserPermission')
      ->andWhereIn('sfGuardUserPermission.permission_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'sfGuardUser';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'ps_customer_id'        => 'ForeignKey',
      'user_key'              => 'Text',
      'member_id'             => 'ForeignKey',
      'user_type'             => 'Text',
      'manager_type'          => 'Text',
      'first_name'            => 'Text',
      'last_name'             => 'Text',
      'email_address'         => 'Text',
      'username'              => 'Text',
      'password'              => 'Text',
      'avatar'                => 'Text',
      'app_device_id'         => 'Text',
      'app_config'            => 'Text',
      'api_token'             => 'Text',
      'token_last_login'      => 'Date',
      'token_expires_in'      => 'Date',
      'refresh_token'         => 'Text',
      'notification_token'    => 'Text',
      'notification_at'       => 'Date',
      'osname'                => 'Text',
      'osvesion'              => 'Text',
      'algorithm'             => 'Text',
      'salt'                  => 'Text',
      'is_active'             => 'Number',
      'is_super_admin'        => 'Boolean',
      'is_global_super_admin' => 'Boolean',
      'last_login'            => 'Date',
      'user_created_id'       => 'ForeignKey',
      'user_updated_id'       => 'ForeignKey',
      'created_at'            => 'Date',
      'updated_at'            => 'Date',
      'groups_list'           => 'ManyKey',
      'permissions_list'      => 'ManyKey',
    );
  }
}
