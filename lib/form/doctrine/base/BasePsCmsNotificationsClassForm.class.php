<?php

/**
 * PsCmsNotificationsClass form base class.
 *
 * @method PsCmsNotificationsClass getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsCmsNotificationsClassForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'ps_customer_id'     => new sfWidgetFormInputText(),
      'ps_class_id'        => new sfWidgetFormInputText(),
      'ps_notification_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Notification'), 'add_empty' => false)),
      'user_created_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'     => new sfValidatorInteger(),
      'ps_class_id'        => new sfValidatorInteger(),
      'ps_notification_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Notification'))),
      'user_created_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_cms_notifications_class[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsCmsNotificationsClass';
  }

}
