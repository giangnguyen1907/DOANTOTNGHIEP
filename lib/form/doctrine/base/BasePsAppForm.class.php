<?php

/**
 * PsApp form base class.
 *
 * @method PsApp getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsAppForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'ps_app_root'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsApp'), 'add_empty' => true)),
      'title'           => new sfWidgetFormInputText(),
      'app_code'        => new sfWidgetFormInputText(),
      'description'     => new sfWidgetFormTextarea(),
      'iorder'          => new sfWidgetFormInputText(),
      'is_system'       => new sfWidgetFormInputCheckbox(),
      'is_activated'    => new sfWidgetFormInputText(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_app_root'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsApp'), 'required' => false)),
      'title'           => new sfValidatorString(array('max_length' => 255)),
      'app_code'        => new sfValidatorString(array('max_length' => 255)),
      'description'     => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'iorder'          => new sfValidatorInteger(array('required' => false)),
      'is_system'       => new sfValidatorBoolean(array('required' => false)),
      'is_activated'    => new sfValidatorInteger(array('required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'PsApp', 'column' => array('app_code')))
    );

    $this->widgetSchema->setNameFormat('ps_app[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsApp';
  }

}
