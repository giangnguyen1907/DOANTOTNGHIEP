<?php

/**
 * FeatureBranchTimes form base class.
 *
 * @method FeatureBranchTimes getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseFeatureBranchTimesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'ps_feature_branch_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('FeatureBranch'), 'add_empty' => false)),
      'ps_class_room_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsClassRooms'), 'add_empty' => true)),
      'start_at'             => new sfWidgetFormDate(),
      'end_at'               => new sfWidgetFormDate(),
      'start_time'           => new sfWidgetFormTime(),
      'end_time'             => new sfWidgetFormTime(),
      'is_saturday'          => new sfWidgetFormInputCheckbox(),
      'is_sunday'            => new sfWidgetFormInputCheckbox(),
      'note'                 => new sfWidgetFormTextarea(),
      'note_class_name'      => new sfWidgetFormTextarea(),
      'user_created_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'           => new sfWidgetFormDateTime(),
      'updated_at'           => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_feature_branch_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('FeatureBranch'))),
      'ps_class_room_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsClassRooms'), 'required' => false)),
      'start_at'             => new sfValidatorDate(),
      'end_at'               => new sfValidatorDate(),
      'start_time'           => new sfValidatorTime(array('required' => false)),
      'end_time'             => new sfValidatorTime(array('required' => false)),
      'is_saturday'          => new sfValidatorBoolean(array('required' => false)),
      'is_sunday'            => new sfValidatorBoolean(array('required' => false)),
      'note'                 => new sfValidatorString(array('required' => false)),
      'note_class_name'      => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'user_created_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'           => new sfValidatorDateTime(),
      'updated_at'           => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('feature_branch_times[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'FeatureBranchTimes';
  }

}
