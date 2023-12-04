<?php

/**
 * RelativeStudent form base class.
 *
 * @method RelativeStudent getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseRelativeStudentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'student_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => false)),
      'relative_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Relative'), 'add_empty' => false)),
      'relationship_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Relationship'), 'add_empty' => true)),
      'is_parent_main'  => new sfWidgetFormInputCheckbox(),
      'is_parent'       => new sfWidgetFormInputCheckbox(),
      'is_role'         => new sfWidgetFormInputCheckbox(),
      'role_service'    => new sfWidgetFormInputCheckbox(),
      'iorder'          => new sfWidgetFormInputText(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'student_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Student'))),
      'relative_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Relative'))),
      'relationship_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Relationship'), 'required' => false)),
      'is_parent_main'  => new sfValidatorBoolean(array('required' => false)),
      'is_parent'       => new sfValidatorBoolean(array('required' => false)),
      'is_role'         => new sfValidatorBoolean(array('required' => false)),
      'role_service'    => new sfValidatorBoolean(array('required' => false)),
      'iorder'          => new sfValidatorInteger(array('required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('relative_student[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'RelativeStudent';
  }

}
