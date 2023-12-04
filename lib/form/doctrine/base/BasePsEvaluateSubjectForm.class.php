<?php

/**
 * PsEvaluateSubject form base class.
 *
 * @method PsEvaluateSubject getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsEvaluateSubjectForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'ps_customer_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => false)),
      'ps_workplace_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => true)),
      'school_year_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsSchoolYear'), 'add_empty' => true)),
      'title'           => new sfWidgetFormInputText(),
      'subject_code'    => new sfWidgetFormInputText(),
      'is_activated'    => new sfWidgetFormInputCheckbox(),
      'iorder'          => new sfWidgetFormInputText(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'))),
      'ps_workplace_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'required' => false)),
      'school_year_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsSchoolYear'), 'required' => false)),
      'title'           => new sfValidatorString(array('max_length' => 255)),
      'subject_code'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_activated'    => new sfValidatorBoolean(array('required' => false)),
      'iorder'          => new sfValidatorInteger(array('required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_evaluate_subject[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsEvaluateSubject';
  }

}
