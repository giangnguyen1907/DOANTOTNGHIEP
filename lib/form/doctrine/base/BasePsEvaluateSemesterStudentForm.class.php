<?php

/**
 * PsEvaluateSemesterStudent form base class.
 *
 * @method PsEvaluateSemesterStudent getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsEvaluateSemesterStudentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'school_year_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsSchoolYear'), 'add_empty' => true)),
      'ps_customer_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => false)),
      'ps_semester'     => new sfWidgetFormInputText(),
      'student_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => false)),
      'symbol_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsEvaluateIndexSymbol'), 'add_empty' => false)),
      'number'          => new sfWidgetFormInputText(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'school_year_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsSchoolYear'), 'required' => false)),
      'ps_customer_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'))),
      'ps_semester'     => new sfValidatorInteger(array('required' => false)),
      'student_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Student'))),
      'symbol_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsEvaluateIndexSymbol'))),
      'number'          => new sfValidatorInteger(),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'PsEvaluateSemesterStudent', 'column' => array('school_year_id', 'ps_semester', 'student_id', 'symbol_id')))
    );

    $this->widgetSchema->setNameFormat('ps_evaluate_semester_student[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsEvaluateSemesterStudent';
  }

}
