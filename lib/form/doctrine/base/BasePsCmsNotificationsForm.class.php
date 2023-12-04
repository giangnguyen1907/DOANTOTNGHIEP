<?php

/**
 * PsCmsNotifications form base class.
 *
 * @method PsCmsNotifications getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsCmsNotificationsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'ps_customer_id'        => new sfWidgetFormInputText(),
      'title'                 => new sfWidgetFormInputText(),
      'description'           => new sfWidgetFormTextarea(),
      'private_key'           => new sfWidgetFormInputText(),
      'is_system'             => new sfWidgetFormInputText(),
      'is_all'                => new sfWidgetFormInputText(),
      'is_object'             => new sfWidgetFormInputText(),
      'is_delete'             => new sfWidgetFormInputText(),
      'is_status'             => new sfWidgetFormInputText(),
      'date_at'               => new sfWidgetFormInputText(),
      'total_object_received' => new sfWidgetFormInputText(),
      'text_object_received'  => new sfWidgetFormTextarea(),
	  'root_screen'           => new sfWidgetFormInputText(),
      'user_created_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'created_at'            => new sfWidgetFormDateTime(),
      'updated_at'            => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'        => new sfValidatorInteger(array('required' => false)),
      'title'                 => new sfValidatorString(array('max_length' => 150)),
      'description'           => new sfValidatorString(array('required' => false)),
      'private_key'           => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'is_system'             => new sfValidatorInteger(array('required' => false)),
      'is_all'                => new sfValidatorInteger(array('required' => false)),
      'is_object'             => new sfValidatorInteger(array('required' => false)),
      'is_delete'             => new sfValidatorInteger(array('required' => false)),
      'is_status'             => new sfValidatorString(array('max_length' => 6, 'required' => false)),
      'date_at'               => new sfValidatorPass(array('required' => false)),
      'total_object_received' => new sfValidatorInteger(array('required' => false)),
      'text_object_received'  => new sfValidatorString(array('required' => false)),
	  'root_screen'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user_created_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'created_at'            => new sfValidatorDateTime(),
      'updated_at'            => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_cms_notifications[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsCmsNotifications';
  }

}
