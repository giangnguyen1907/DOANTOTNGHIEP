<?php

/**
 * PsLogtimes form base class.
 *
 * @method PsLogtimes getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsLogtimesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'student_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => true)),
      'login_at'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('DayInMonth'), 'add_empty' => true)),
      'login_relative_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('RelativeLogin'), 'add_empty' => true)),
      'login_member_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsMemberLogin'), 'add_empty' => true)),
      'logout_at'          => new sfWidgetFormDateTime(),
      'logout_member_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsMemberLogout'), 'add_empty' => true)),
      'logout_relative_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('RelativeLogout'), 'add_empty' => true)),
      'log_value'          => new sfWidgetFormInputText(),
      'log_code'           => new sfWidgetFormInputText(),
      'note'               => new sfWidgetFormInputText(),
      'user_created_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'student_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'required' => false)),
      'login_at'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('DayInMonth'), 'required' => false)),
      'login_relative_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('RelativeLogin'), 'required' => false)),
      'login_member_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsMemberLogin'), 'required' => false)),
      'logout_at'          => new sfValidatorDateTime(array('required' => false)),
      'logout_member_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsMemberLogout'), 'required' => false)),
      'logout_relative_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('RelativeLogout'), 'required' => false)),
      'log_value'          => new sfValidatorInteger(array('required' => false)),
      'log_code'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'note'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user_created_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_logtimes[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsLogtimes';
  }

}
