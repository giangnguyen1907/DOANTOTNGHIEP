<?php

/**
 * PsExamination form base class.
 *
 * @method PsExamination getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsExaminationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'school_year_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsSchoolYear'), 'add_empty' => false)),
      'ps_customer_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => true)),
      'ps_workplace_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => true)),
      'ps_obj_group_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsObjectGroups'), 'add_empty' => true)),
      'name'            => new sfWidgetFormInputText(),
      'input_date_at'   => new sfWidgetFormDate(),
      'note'            => new sfWidgetFormInputText(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'school_year_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsSchoolYear'))),
      'ps_customer_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'required' => false)),
      'ps_workplace_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'required' => false)),
      'ps_obj_group_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsObjectGroups'), 'required' => false)),
      'name'            => new sfValidatorString(array('max_length' => 100)),
      'input_date_at'   => new sfValidatorDate(),
      'note'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'PsExamination', 'column' => array('school_year_id', 'ps_workplace_id', 'input_date_at')))
    );

    $this->widgetSchema->setNameFormat('ps_examination[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsExamination';
  }

}
