<?php

/**
 * PsCmsReceivedNotification filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsCmsReceivedNotificationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ps_cms_notification_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Notification'), 'add_empty' => true)),
      'title'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_id'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_read'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'date_at'                => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'private_key'            => new sfWidgetFormFilterInput(),
      'is_delete'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'user_created_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'created_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ps_cms_notification_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Notification'), 'column' => 'id')),
      'title'                  => new sfValidatorPass(array('required' => false)),
      'user_id'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_read'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'date_at'                => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'private_key'            => new sfValidatorPass(array('required' => false)),
      'is_delete'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'user_created_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'created_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ps_cms_received_notification_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsCmsReceivedNotification';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'ps_cms_notification_id' => 'ForeignKey',
      'title'                  => 'Text',
      'user_id'                => 'Number',
      'is_read'                => 'Boolean',
      'date_at'                => 'Date',
      'private_key'            => 'Text',
      'is_delete'              => 'Boolean',
      'user_created_id'        => 'ForeignKey',
      'created_at'             => 'Date',
      'updated_at'             => 'Date',
    );
  }
}
