<?php

/**
 * PsCmsReceivedNotification form base class.
 *
 * @method PsCmsReceivedNotification getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsCmsReceivedNotificationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'ps_cms_notification_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Notification'), 'add_empty' => false)),
      'title'                  => new sfWidgetFormInputText(),
      'user_id'                => new sfWidgetFormInputText(),
      'is_read'                => new sfWidgetFormInputCheckbox(),
      'date_at'                => new sfWidgetFormInputText(),
      'private_key'            => new sfWidgetFormInputText(),
      'is_delete'              => new sfWidgetFormInputCheckbox(),
      'user_created_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'created_at'             => new sfWidgetFormDateTime(),
      'updated_at'             => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_cms_notification_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Notification'))),
      'title'                  => new sfValidatorString(array('max_length' => 150)),
      'user_id'                => new sfValidatorInteger(),
      'is_read'                => new sfValidatorBoolean(array('required' => false)),
      'date_at'                => new sfValidatorPass(array('required' => false)),
      'private_key'            => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'is_delete'              => new sfValidatorBoolean(array('required' => false)),
      'user_created_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'created_at'             => new sfValidatorDateTime(),
      'updated_at'             => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_cms_received_notification[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsCmsReceivedNotification';
  }

}
