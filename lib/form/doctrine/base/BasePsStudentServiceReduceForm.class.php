<?php

/**
 * PsStudentServiceReduce form base class.
 *
 * @method PsStudentServiceReduce getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsStudentServiceReduceForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'title'           => new sfWidgetFormInputText(),
      'student_id'      => new sfWidgetFormInputText(),
      'service_id'      => new sfWidgetFormInputText(),
      'receivable_at'   => new sfWidgetFormDate(),
      'level'           => new sfWidgetFormInputText(),
      'discount'        => new sfWidgetFormInputText(),
      'is_type'         => new sfWidgetFormInputCheckbox(),
      'user_created_id' => new sfWidgetFormInputText(),
      'user_updated_id' => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'title'           => new sfValidatorString(array('max_length' => 255)),
      'student_id'      => new sfValidatorInteger(),
      'service_id'      => new sfValidatorInteger(),
      'receivable_at'   => new sfValidatorDate(array('required' => false)),
      'level'           => new sfValidatorInteger(array('required' => false)),
      'discount'        => new sfValidatorInteger(array('required' => false)),
      'is_type'         => new sfValidatorBoolean(array('required' => false)),
      'user_created_id' => new sfValidatorInteger(),
      'user_updated_id' => new sfValidatorInteger(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_student_service_reduce[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsStudentServiceReduce';
  }

}
