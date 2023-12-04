<?php

/**
 * Student form base class.
 *
 * @method Student getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseStudentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'ps_customer_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => false)),
      'ps_workplace_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => true)),
      'student_code'       => new sfWidgetFormInputText(),
      'first_name'         => new sfWidgetFormInputText(),
      'last_name'          => new sfWidgetFormInputText(),
      'sex'                => new sfWidgetFormInputCheckbox(),
      'birthday'           => new sfWidgetFormInputText(),
      'common_name'        => new sfWidgetFormInputText(),
      'nick_name'          => new sfWidgetFormInputText(),
      'image'              => new sfWidgetFormInputText(),
      'avatar'             => new sfWidgetFormInputText(),
      'ethnic_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsEthnic'), 'add_empty' => true)),
      'religion_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsReligion'), 'add_empty' => true)),
      'nationality'        => new sfWidgetFormInputText(),
      'address'            => new sfWidgetFormInputText(),
      'status'             => new sfWidgetFormInputText(),
      'year_data'          => new sfWidgetFormInputText(),
      'number_ae'          => new sfWidgetFormInputText(),
      'type_year'          => new sfWidgetFormInputText(),
      'deleted_at'         => new sfWidgetFormDateTime(),
      'statistic_class_id' => new sfWidgetFormInputText(),
      'relative_id'        => new sfWidgetFormInputText(),
      'start_date_at'      => new sfWidgetFormDateTime(),
      'is_import'          => new sfWidgetFormInputCheckbox(),
      'current_class_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass'), 'add_empty' => true)),
      'policy_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsPolicyGroup'), 'add_empty' => true)),
      'caphoc'             => new sfWidgetFormInputText(),
      'chuongtrinh'        => new sfWidgetFormInputText(),
      'khoihoc'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsObjectGroups'), 'add_empty' => true)),
      'doituong'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsTargetGroup'), 'add_empty' => true)),
      'attributes'         => new sfWidgetFormInputText(),
      'user_created_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'))),
      'ps_workplace_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'required' => false)),
      'student_code'       => new sfValidatorString(array('max_length' => 100)),
      'first_name'         => new sfValidatorString(array('max_length' => 255)),
      'last_name'          => new sfValidatorString(array('max_length' => 255)),
      'sex'                => new sfValidatorBoolean(array('required' => false)),
      'birthday'           => new sfValidatorPass(array('required' => false)),
      'common_name'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'nick_name'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'image'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'avatar'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'ethnic_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsEthnic'), 'required' => false)),
      'religion_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsReligion'), 'required' => false)),
      'nationality'        => new sfValidatorString(array('max_length' => 2, 'required' => false)),
      'address'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'status'             => new sfValidatorString(array('max_length' => 4, 'required' => false)),
      'year_data'          => new sfValidatorInteger(array('required' => false)),
      'number_ae'          => new sfValidatorInteger(array('required' => false)),
      'type_year'          => new sfValidatorInteger(array('required' => false)),
      'deleted_at'         => new sfValidatorDateTime(array('required' => false)),
      'statistic_class_id' => new sfValidatorInteger(array('required' => false)),
      'relative_id'        => new sfValidatorInteger(array('required' => false)),
      'start_date_at'      => new sfValidatorDateTime(array('required' => false)),
      'is_import'          => new sfValidatorBoolean(array('required' => false)),
      'current_class_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass'), 'required' => false)),
      'policy_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsPolicyGroup'), 'required' => false)),
      'caphoc'             => new sfValidatorInteger(array('required' => false)),
      'chuongtrinh'        => new sfValidatorInteger(array('required' => false)),
      'khoihoc'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsObjectGroups'), 'required' => false)),
      'doituong'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsTargetGroup'), 'required' => false)),
      'attributes'         => new sfValidatorPass(array('required' => false)),
      'user_created_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Student', 'column' => array('student_code')))
    );

    $this->widgetSchema->setNameFormat('student[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Student';
  }

}
