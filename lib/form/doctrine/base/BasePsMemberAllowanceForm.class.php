<?php

/**
 * PsMemberAllowance form base class.
 *
 * @method PsMemberAllowance getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsMemberAllowanceForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'ps_member_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsMember'), 'add_empty' => true)),
      'ps_allowance_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsAllowance'), 'add_empty' => true)),
      'start_at'        => new sfWidgetFormDate(),
      'stop_at'         => new sfWidgetFormDate(),
      'note'            => new sfWidgetFormInputText(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_member_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsMember'), 'required' => false)),
      'ps_allowance_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsAllowance'), 'required' => false)),
      'start_at'        => new sfValidatorDate(),
      'stop_at'         => new sfValidatorDate(array('required' => false)),
      'note'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_member_allowance[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsMemberAllowance';
  }

}
