<?php

/**
 * PsCustomer filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsCustomerFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ps_ward_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWard'), 'add_empty' => true)),
      'school_code'       => new sfWidgetFormFilterInput(),
      'school_name'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'title'             => new sfWidgetFormFilterInput(),
      'address'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'tel'               => new sfWidgetFormFilterInput(),
      'fax'               => new sfWidgetFormFilterInput(),
      'mobile'            => new sfWidgetFormFilterInput(),
      'email'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'url'               => new sfWidgetFormFilterInput(),
      'ps_typeschool_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsTypeSchool'), 'add_empty' => true)),
      'is_root'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'agent'             => new sfWidgetFormFilterInput(),
      'principal'         => new sfWidgetFormFilterInput(),
      'note'              => new sfWidgetFormFilterInput(),
      'description'       => new sfWidgetFormFilterInput(),
      'iorder'            => new sfWidgetFormFilterInput(),
      'is_activated'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_deploy'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'logo'              => new sfWidgetFormFilterInput(),
      'activated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'year_data'         => new sfWidgetFormFilterInput(),
      'cache_data'        => new sfWidgetFormFilterInput(),
      'user_activated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserActivated'), 'add_empty' => true)),
      'user_created_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ps_ward_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsWard'), 'column' => 'id')),
      'school_code'       => new sfValidatorPass(array('required' => false)),
      'school_name'       => new sfValidatorPass(array('required' => false)),
      'title'             => new sfValidatorPass(array('required' => false)),
      'address'           => new sfValidatorPass(array('required' => false)),
      'tel'               => new sfValidatorPass(array('required' => false)),
      'fax'               => new sfValidatorPass(array('required' => false)),
      'mobile'            => new sfValidatorPass(array('required' => false)),
      'email'             => new sfValidatorPass(array('required' => false)),
      'url'               => new sfValidatorPass(array('required' => false)),
      'ps_typeschool_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsTypeSchool'), 'column' => 'id')),
      'is_root'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'agent'             => new sfValidatorPass(array('required' => false)),
      'principal'         => new sfValidatorPass(array('required' => false)),
      'note'              => new sfValidatorPass(array('required' => false)),
      'description'       => new sfValidatorPass(array('required' => false)),
      'iorder'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_activated'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_deploy'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'logo'              => new sfValidatorPass(array('required' => false)),
      'activated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'year_data'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'cache_data'        => new sfValidatorPass(array('required' => false)),
      'user_activated_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserActivated'), 'column' => 'id')),
      'user_created_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ps_customer_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsCustomer';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'ps_ward_id'        => 'ForeignKey',
      'school_code'       => 'Text',
      'school_name'       => 'Text',
      'title'             => 'Text',
      'address'           => 'Text',
      'tel'               => 'Text',
      'fax'               => 'Text',
      'mobile'            => 'Text',
      'email'             => 'Text',
      'url'               => 'Text',
      'ps_typeschool_id'  => 'ForeignKey',
      'is_root'           => 'Number',
      'agent'             => 'Text',
      'principal'         => 'Text',
      'note'              => 'Text',
      'description'       => 'Text',
      'iorder'            => 'Number',
      'is_activated'      => 'Number',
      'is_deploy'         => 'Number',
      'logo'              => 'Text',
      'activated_at'      => 'Date',
      'year_data'         => 'Number',
      'cache_data'        => 'Text',
      'user_activated_id' => 'ForeignKey',
      'user_created_id'   => 'ForeignKey',
      'user_updated_id'   => 'ForeignKey',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
    );
  }
}
