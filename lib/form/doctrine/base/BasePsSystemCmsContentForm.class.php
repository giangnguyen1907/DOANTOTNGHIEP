<?php

/**
 * PsSystemCmsContent form base class.
 *
 * @method PsSystemCmsContent getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsSystemCmsContentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                         => new sfWidgetFormInputHidden(),
      'ps_system_cms_content_code' => new sfWidgetFormInputText(),
      'title'                      => new sfWidgetFormInputText(),
      'note'                       => new sfWidgetFormInputText(),
      'description'                => new sfWidgetFormTextarea(),
      'is_activated'               => new sfWidgetFormInputCheckbox(),
      'user_created_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'                 => new sfWidgetFormDateTime(),
      'updated_at'                 => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_system_cms_content_code' => new sfValidatorString(array('max_length' => 15)),
      'title'                      => new sfValidatorString(array('max_length' => 250)),
      'note'                       => new sfValidatorString(array('max_length' => 250, 'required' => false)),
      'description'                => new sfValidatorString(array('required' => false)),
      'is_activated'               => new sfValidatorBoolean(array('required' => false)),
      'user_created_id'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'                 => new sfValidatorDateTime(),
      'updated_at'                 => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_system_cms_content[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsSystemCmsContent';
  }

}
