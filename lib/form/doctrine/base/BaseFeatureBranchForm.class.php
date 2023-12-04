<?php

/**
 * FeatureBranch form base class.
 *
 * @method FeatureBranch getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseFeatureBranchForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'school_year_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsSchoolYear'), 'add_empty' => false)),
      'feature_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Feature'), 'add_empty' => false)),
      'ps_workplace_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => true)),
      'ps_obj_group_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsObjectGroups'), 'add_empty' => true)),
      'name'                 => new sfWidgetFormInputText(),
      'mode'                 => new sfWidgetFormInputText(),
      'note'                 => new sfWidgetFormInputText(),
      'iorder'               => new sfWidgetFormInputText(),
      'ps_image_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsImages'), 'add_empty' => true)),
      'is_activated'         => new sfWidgetFormInputCheckbox(),
      'is_continuity'        => new sfWidgetFormInputCheckbox(),
      'is_study'             => new sfWidgetFormInputCheckbox(),
      'is_depend_attendance' => new sfWidgetFormInputCheckbox(),
      'number_option'        => new sfWidgetFormInputText(),
      'user_created_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'           => new sfWidgetFormDateTime(),
      'updated_at'           => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'school_year_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsSchoolYear'))),
      'feature_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Feature'))),
      'ps_workplace_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'required' => false)),
      'ps_obj_group_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsObjectGroups'), 'required' => false)),
      'name'                 => new sfValidatorString(array('max_length' => 255)),
      'mode'                 => new sfValidatorInteger(),
      'note'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'iorder'               => new sfValidatorInteger(array('required' => false)),
      'ps_image_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsImages'), 'required' => false)),
      'is_activated'         => new sfValidatorBoolean(array('required' => false)),
      'is_continuity'        => new sfValidatorBoolean(array('required' => false)),
      'is_study'             => new sfValidatorBoolean(array('required' => false)),
      'is_depend_attendance' => new sfValidatorBoolean(array('required' => false)),
      'number_option'        => new sfValidatorInteger(array('required' => false)),
      'user_created_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'           => new sfValidatorDateTime(),
      'updated_at'           => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('feature_branch[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'FeatureBranch';
  }

}
