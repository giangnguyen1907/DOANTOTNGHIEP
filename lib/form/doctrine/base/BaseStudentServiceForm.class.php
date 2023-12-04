<?php

/**
 * StudentService form base class.
 *
 * @method StudentService getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseStudentServiceForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'student_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => true)),
      'service_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Service'), 'add_empty' => true)),
      'regularity_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsRegularity'), 'add_empty' => true)),
      'ps_service_course_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsServiceCourses'), 'add_empty' => true)),
      'number_month'         => new sfWidgetFormInputText(),
      'discount'             => new sfWidgetFormInputText(),
      'discount_amount'      => new sfWidgetFormInputText(),
      'note'                 => new sfWidgetFormInputText(),
      'delete_at'            => new sfWidgetFormInputText(),
      'user_deleted_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserDeleted'), 'add_empty' => true)),
      'user_created_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'           => new sfWidgetFormDateTime(),
      'updated_at'           => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'student_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'required' => false)),
      'service_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Service'), 'required' => false)),
      'regularity_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsRegularity'), 'required' => false)),
      'ps_service_course_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsServiceCourses'), 'required' => false)),
      'number_month'         => new sfValidatorInteger(array('required' => false)),
      'discount'             => new sfValidatorPass(array('required' => false)),
      'discount_amount'      => new sfValidatorPass(array('required' => false)),
      'note'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'delete_at'            => new sfValidatorPass(array('required' => false)),
      'user_deleted_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserDeleted'), 'required' => false)),
      'user_created_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'           => new sfValidatorDateTime(),
      'updated_at'           => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('student_service[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StudentService';
  }

}
