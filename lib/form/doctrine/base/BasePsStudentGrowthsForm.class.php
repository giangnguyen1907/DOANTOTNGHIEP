<?php

/**
 * PsStudentGrowths form base class.
 *
 * @method PsStudentGrowths getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsStudentGrowthsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'student_id'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => false)),
      'height'                  => new sfWidgetFormInputText(),
      'weight'                  => new sfWidgetFormInputText(),
      'index_height'            => new sfWidgetFormInputText(),
      'index_weight'            => new sfWidgetFormInputText(),
      'index_tooth'             => new sfWidgetFormInputText(),
      'index_throat'            => new sfWidgetFormInputText(),
      'index_eye'               => new sfWidgetFormInputText(),
      'index_heart'             => new sfWidgetFormInputText(),
      'index_lung'              => new sfWidgetFormInputText(),
      'index_skin'              => new sfWidgetFormInputText(),
      'index_age'               => new sfWidgetFormInputText(),
      'date_push_notication'    => new sfWidgetFormDateTime(),
      'number_push_notication'  => new sfWidgetFormInputText(),
      'people_make'             => new sfWidgetFormInputText(),
      'organization_make'       => new sfWidgetFormInputText(),
      'examination_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsExamination'), 'add_empty' => false)),
      'note'                    => new sfWidgetFormInputText(),
      'user_push_notication_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserPushNotication'), 'add_empty' => true)),
      'user_created_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'              => new sfWidgetFormDateTime(),
      'updated_at'              => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'student_id'              => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Student'))),
      'height'                  => new sfValidatorPass(array('required' => false)),
      'weight'                  => new sfValidatorPass(array('required' => false)),
      'index_height'            => new sfValidatorInteger(array('required' => false)),
      'index_weight'            => new sfValidatorInteger(array('required' => false)),
      'index_tooth'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'index_throat'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'index_eye'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'index_heart'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'index_lung'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'index_skin'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'index_age'               => new sfValidatorInteger(array('required' => false)),
      'date_push_notication'    => new sfValidatorDateTime(),
      'number_push_notication'  => new sfValidatorInteger(array('required' => false)),
      'people_make'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'organization_make'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'examination_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsExamination'))),
      'note'                    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user_push_notication_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserPushNotication'), 'required' => false)),
      'user_created_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'              => new sfValidatorDateTime(),
      'updated_at'              => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'PsStudentGrowths', 'column' => array('student_id', 'examination_id')))
    );

    $this->widgetSchema->setNameFormat('ps_student_growths[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsStudentGrowths';
  }

}
