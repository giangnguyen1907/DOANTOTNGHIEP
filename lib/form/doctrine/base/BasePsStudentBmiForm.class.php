<?php

/**
 * PsStudentBmi form base class.
 *
 * @method PsStudentBmi getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsStudentBmiForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'min_height1'     => new sfWidgetFormInputText(),
      'max_height1'     => new sfWidgetFormInputText(),
      'medium_height'   => new sfWidgetFormInputText(),
      'min_height'      => new sfWidgetFormInputText(),
      'max_height'      => new sfWidgetFormInputText(),
      'min_weight1'     => new sfWidgetFormInputText(),
      'max_weight1'     => new sfWidgetFormInputText(),
      'medium_weight'   => new sfWidgetFormInputText(),
      'min_weight'      => new sfWidgetFormInputText(),
      'max_weight'      => new sfWidgetFormInputText(),
      'sex'             => new sfWidgetFormInputCheckbox(),
      'is_month'        => new sfWidgetFormInputText(),
      'note'            => new sfWidgetFormInputText(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'min_height1'     => new sfValidatorPass(array('required' => false)),
      'max_height1'     => new sfValidatorPass(array('required' => false)),
      'medium_height'   => new sfValidatorPass(),
      'min_height'      => new sfValidatorPass(),
      'max_height'      => new sfValidatorPass(),
      'min_weight1'     => new sfValidatorPass(array('required' => false)),
      'max_weight1'     => new sfValidatorPass(array('required' => false)),
      'medium_weight'   => new sfValidatorPass(),
      'min_weight'      => new sfValidatorPass(),
      'max_weight'      => new sfValidatorPass(),
      'sex'             => new sfValidatorBoolean(array('required' => false)),
      'is_month'        => new sfValidatorInteger(),
      'note'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_student_bmi[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsStudentBmi';
  }

}
