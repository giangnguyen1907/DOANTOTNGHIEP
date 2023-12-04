<?php

/**
 * PsEvaluateClassTime form base class.
 *
 * @method PsEvaluateClassTime getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsEvaluateClassTimeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'criteria_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsEvaluateIndexCriteria'), 'add_empty' => false)),
      'myclass_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass'), 'add_empty' => false)),
      'date_start'      => new sfWidgetFormDate(),
      'date_end'        => new sfWidgetFormDate(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'criteria_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsEvaluateIndexCriteria'))),
      'myclass_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass'))),
      'date_start'      => new sfValidatorDate(array('required' => false)),
      'date_end'        => new sfValidatorDate(array('required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_evaluate_class_time[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsEvaluateClassTime';
  }

}
