<?php

/**
 * PsCamera filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsCameraFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ps_workplace_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => true)),
      'ps_class_room_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsClassRooms'), 'add_empty' => true)),
      'title'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_camera'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'password_camera'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'url_ip'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'port_tcp'         => new sfWidgetFormFilterInput(),
      'port_udp'         => new sfWidgetFormFilterInput(),
      'port_http'        => new sfWidgetFormFilterInput(),
      'year_data'        => new sfWidgetFormFilterInput(),
      'image_name'       => new sfWidgetFormFilterInput(),
      'note'             => new sfWidgetFormFilterInput(),
      'iorder'           => new sfWidgetFormFilterInput(),
      'is_activated'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'user_created_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ps_workplace_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsWorkPlaces'), 'column' => 'id')),
      'ps_class_room_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsClassRooms'), 'column' => 'id')),
      'title'            => new sfValidatorPass(array('required' => false)),
      'user_camera'      => new sfValidatorPass(array('required' => false)),
      'password_camera'  => new sfValidatorPass(array('required' => false)),
      'url_ip'           => new sfValidatorPass(array('required' => false)),
      'port_tcp'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'port_udp'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'port_http'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'year_data'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'image_name'       => new sfValidatorPass(array('required' => false)),
      'note'             => new sfValidatorPass(array('required' => false)),
      'iorder'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_activated'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'user_created_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ps_camera_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsCamera';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'ps_workplace_id'  => 'ForeignKey',
      'ps_class_room_id' => 'ForeignKey',
      'title'            => 'Text',
      'user_camera'      => 'Text',
      'password_camera'  => 'Text',
      'url_ip'           => 'Text',
      'port_tcp'         => 'Number',
      'port_udp'         => 'Number',
      'port_http'        => 'Number',
      'year_data'        => 'Number',
      'image_name'       => 'Text',
      'note'             => 'Text',
      'iorder'           => 'Number',
      'is_activated'     => 'Boolean',
      'user_created_id'  => 'ForeignKey',
      'user_updated_id'  => 'ForeignKey',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
    );
  }
}
