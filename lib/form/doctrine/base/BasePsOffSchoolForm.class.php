<?php

/**
 * PsOffSchool form base class.
 *
 * @method PsOffSchool getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsOffSchoolForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'ps_customer_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => false)),
      'ps_workplace_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => false)),
      'ps_class_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass'), 'add_empty' => false)),
      'user_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserId'), 'add_empty' => true)),
      'relative_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Relative'), 'add_empty' => false)),
      'student_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => false)),
      'description'     => new sfWidgetFormInputText(),
      'reason_illegal'  => new sfWidgetFormInputText(),
      'is_activated'    => new sfWidgetFormInputText(),
      'date_at'         => new sfWidgetFormInputText(),
      'from_date'       => new sfWidgetFormDate(),
      'to_date'         => new sfWidgetFormDate(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'))),
      'ps_workplace_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'))),
      'ps_class_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass'))),
      'user_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserId'), 'required' => false)),
      'relative_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Relative'))),
      'student_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Student'))),
      'description'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'reason_illegal'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_activated'    => new sfValidatorInteger(array('required' => false)),
      'date_at'         => new sfValidatorPass(),
      'from_date'       => new sfValidatorDate(),
      'to_date'         => new sfValidatorDate(),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_off_school[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsOffSchool';
  }

}
