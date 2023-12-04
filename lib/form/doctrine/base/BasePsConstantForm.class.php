<?php

/**
 * PsConstant form base class.
 *
 * @method PsConstant getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsConstantForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'c_code'          => new sfWidgetFormInputText(),
      'title'           => new sfWidgetFormInputText(),
      'value_default'   => new sfWidgetFormInputText(),
      'note'            => new sfWidgetFormTextarea(),
      'iorder'          => new sfWidgetFormInputText(),
      'is_notremove'    => new sfWidgetFormInputCheckbox(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'c_code'          => new sfValidatorString(array('max_length' => 100)),
      'title'           => new sfValidatorString(array('max_length' => 255)),
      'value_default'   => new sfValidatorString(array('max_length' => 100)),
      'note'            => new sfValidatorString(array('max_length' => 300, 'required' => false)),
      'iorder'          => new sfValidatorInteger(array('required' => false)),
      'is_notremove'    => new sfValidatorBoolean(array('required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'PsConstant', 'column' => array('c_code')))
    );

    $this->widgetSchema->setNameFormat('ps_constant[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsConstant';
  }

}
