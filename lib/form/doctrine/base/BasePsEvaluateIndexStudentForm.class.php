<?php

/**
 * PsEvaluateIndexStudent form base class.
 *
 * @method PsEvaluateIndexStudent getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsEvaluateIndexStudentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                         => new sfWidgetFormInputHidden(),
      'evaluate_index_criteria_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsEvaluateIndexCriteria'), 'add_empty' => true)),
      'ps_student_id'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => false)),
      'evaluate_index_symbol_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsEvaluateIndexSymbol'), 'add_empty' => true)),
      'date_at'                    => new sfWidgetFormDate(),
      'is_public'                  => new sfWidgetFormInputCheckbox(),
      'is_awaiting_approval'       => new sfWidgetFormInputCheckbox(),
      'user_created_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'                 => new sfWidgetFormDateTime(),
      'updated_at'                 => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'evaluate_index_criteria_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsEvaluateIndexCriteria'), 'required' => false)),
      'ps_student_id'              => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Student'))),
      'evaluate_index_symbol_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsEvaluateIndexSymbol'), 'required' => false)),
      'date_at'                    => new sfValidatorDate(array('required' => false)),
      'is_public'                  => new sfValidatorBoolean(array('required' => false)),
      'is_awaiting_approval'       => new sfValidatorBoolean(array('required' => false)),
      'user_created_id'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'                 => new sfValidatorDateTime(),
      'updated_at'                 => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_evaluate_index_student[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsEvaluateIndexStudent';
  }

}
