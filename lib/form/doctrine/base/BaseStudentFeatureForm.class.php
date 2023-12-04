<?php

/**
 * StudentFeature form base class.
 *
 * @method StudentFeature getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseStudentFeatureForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                        => new sfWidgetFormInputHidden(),
      'student_id'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => false)),
      'feature_option_feature_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('FeatureOptionFeature'), 'add_empty' => true)),
      'tracked_at'                => new sfWidgetFormDateTime(),
      'note'                      => new sfWidgetFormTextarea(),
      'time_at'                   => new sfWidgetFormDateTime(),
      'user_created_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'                => new sfWidgetFormDateTime(),
      'updated_at'                => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'student_id'                => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Student'))),
      'feature_option_feature_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('FeatureOptionFeature'), 'required' => false)),
      'tracked_at'                => new sfValidatorDateTime(array('required' => false)),
      'note'                      => new sfValidatorString(array('max_length' => 300, 'required' => false)),
      'time_at'                   => new sfValidatorDateTime(array('required' => false)),
      'user_created_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'                => new sfValidatorDateTime(),
      'updated_at'                => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('student_feature[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StudentFeature';
  }

}
