<?php

/**
 * PsMobileApps filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsMobileAppsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserMobileApps'), 'add_empty' => true)),
      'device_id'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_activated'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'active_created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'status_used'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'osname'              => new sfWidgetFormFilterInput(),
      'osvesion'            => new sfWidgetFormFilterInput(),
      'network_name'        => new sfWidgetFormFilterInput(),
      'mobile_network_type' => new sfWidgetFormFilterInput(),
      'params'              => new sfWidgetFormFilterInput(),
      'user_created_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'user_id'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserMobileApps'), 'column' => 'id')),
      'device_id'           => new sfValidatorPass(array('required' => false)),
      'is_activated'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'active_created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'status_used'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'osname'              => new sfValidatorPass(array('required' => false)),
      'osvesion'            => new sfValidatorPass(array('required' => false)),
      'network_name'        => new sfValidatorPass(array('required' => false)),
      'mobile_network_type' => new sfValidatorPass(array('required' => false)),
      'params'              => new sfValidatorPass(array('required' => false)),
      'user_created_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ps_mobile_apps_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsMobileApps';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'user_id'             => 'ForeignKey',
      'device_id'           => 'Text',
      'is_activated'        => 'Number',
      'active_created_at'   => 'Date',
      'status_used'         => 'Number',
      'osname'              => 'Text',
      'osvesion'            => 'Text',
      'network_name'        => 'Text',
      'mobile_network_type' => 'Text',
      'params'              => 'Text',
      'user_created_id'     => 'ForeignKey',
      'user_updated_id'     => 'ForeignKey',
      'created_at'          => 'Date',
      'updated_at'          => 'Date',
    );
  }
}
