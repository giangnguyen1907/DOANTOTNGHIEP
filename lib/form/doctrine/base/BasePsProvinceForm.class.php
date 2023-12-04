<?php

/**
 * PsProvince form base class.
 *
 * @method PsProvince getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsProvinceForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'country_code'    => new sfWidgetFormInputText(),
      's_code'          => new sfWidgetFormInputText(),
      'name'            => new sfWidgetFormInputText(),
      'description'     => new sfWidgetFormInputText(),
      'iorder'          => new sfWidgetFormInputText(),
      'is_activated'    => new sfWidgetFormInputCheckbox(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'country_code'    => new sfValidatorString(array('max_length' => 3)),
      's_code'          => new sfValidatorString(array('max_length' => 10)),
      'name'            => new sfValidatorString(array('max_length' => 255)),
      'description'     => new sfValidatorString(array('max_length' => 250, 'required' => false)),
      'iorder'          => new sfValidatorInteger(array('required' => false)),
      'is_activated'    => new sfValidatorBoolean(array('required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'PsProvince', 'column' => array('s_code')))
    );

    $this->widgetSchema->setNameFormat('ps_province[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsProvince';
  }

}
