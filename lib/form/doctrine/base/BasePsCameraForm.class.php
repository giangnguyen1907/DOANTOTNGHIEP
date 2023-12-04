<?php

/**
 * PsCamera form base class.
 *
 * @method PsCamera getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsCameraForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'ps_workplace_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => false)),
      'ps_class_room_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsClassRooms'), 'add_empty' => true)),
      'title'            => new sfWidgetFormInputText(),
      'user_camera'      => new sfWidgetFormInputText(),
      'password_camera'  => new sfWidgetFormInputText(),
      'url_ip'           => new sfWidgetFormTextarea(),
      'port_tcp'         => new sfWidgetFormInputText(),
      'port_udp'         => new sfWidgetFormInputText(),
      'port_http'        => new sfWidgetFormInputText(),
      'year_data'        => new sfWidgetFormInputText(),
      'image_name'       => new sfWidgetFormInputText(),
      'note'             => new sfWidgetFormInputText(),
      'iorder'           => new sfWidgetFormInputText(),
      'is_activated'     => new sfWidgetFormInputCheckbox(),
      'user_created_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_workplace_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'))),
      'ps_class_room_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsClassRooms'), 'required' => false)),
      'title'            => new sfValidatorString(array('max_length' => 255)),
      'user_camera'      => new sfValidatorString(array('max_length' => 255)),
      'password_camera'  => new sfValidatorString(array('max_length' => 255)),
      'url_ip'           => new sfValidatorString(array('max_length' => 500)),
      'port_tcp'         => new sfValidatorInteger(array('required' => false)),
      'port_udp'         => new sfValidatorInteger(array('required' => false)),
      'port_http'        => new sfValidatorInteger(array('required' => false)),
      'year_data'        => new sfValidatorInteger(array('required' => false)),
      'image_name'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'note'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'iorder'           => new sfValidatorInteger(array('required' => false)),
      'is_activated'     => new sfValidatorBoolean(array('required' => false)),
      'user_created_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_camera[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsCamera';
  }

}
