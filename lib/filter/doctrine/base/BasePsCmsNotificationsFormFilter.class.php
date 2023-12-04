<?php

/**
 * PsCmsNotifications filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsCmsNotificationsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ps_customer_id'        => new sfWidgetFormFilterInput(),
      'title'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'description'           => new sfWidgetFormFilterInput(),
      'private_key'           => new sfWidgetFormFilterInput(),
      'is_system'             => new sfWidgetFormFilterInput(),
      'is_all'                => new sfWidgetFormFilterInput(),
      'is_object'             => new sfWidgetFormFilterInput(),
      'is_delete'             => new sfWidgetFormFilterInput(),
      'is_status'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'date_at'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'total_object_received' => new sfWidgetFormFilterInput(),
      'text_object_received'  => new sfWidgetFormFilterInput(),
      'user_created_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'created_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ps_customer_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'title'                 => new sfValidatorPass(array('required' => false)),
      'description'           => new sfValidatorPass(array('required' => false)),
      'private_key'           => new sfValidatorPass(array('required' => false)),
      'is_system'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_all'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_object'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_delete'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_status'             => new sfValidatorPass(array('required' => false)),
      'date_at'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'total_object_received' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'text_object_received'  => new sfValidatorPass(array('required' => false)),
      'user_created_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'created_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ps_cms_notifications_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsCmsNotifications';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'ps_customer_id'        => 'Number',
      'title'                 => 'Text',
      'description'           => 'Text',
      'private_key'           => 'Text',
      'is_system'             => 'Number',
      'is_all'                => 'Number',
      'is_object'             => 'Number',
      'is_delete'             => 'Number',
      'is_status'             => 'Text',
      'date_at'               => 'Date',
      'total_object_received' => 'Number',
      'text_object_received'  => 'Text',
      'user_created_id'       => 'ForeignKey',
      'created_at'            => 'Date',
      'updated_at'            => 'Date',
    );
  }
}
