<?php

/**
 * StudentServiceCourseComment form base class.
 *
 * @method StudentServiceCourseComment getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseStudentServiceCourseCommentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                            => new sfWidgetFormInputHidden(),
      'student_id'                    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => false)),
      'ps_service_course_schedule_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsServiceCourseSchedules'), 'add_empty' => true)),
      'feature_option_subject_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('FeatureOptionSubject'), 'add_empty' => true)),
      'note'                          => new sfWidgetFormInputText(),
      'user_created_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'                    => new sfWidgetFormDateTime(),
      'updated_at'                    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'student_id'                    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Student'))),
      'ps_service_course_schedule_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsServiceCourseSchedules'), 'required' => false)),
      'feature_option_subject_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('FeatureOptionSubject'), 'required' => false)),
      'note'                          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user_created_id'               => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'               => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'                    => new sfValidatorDateTime(),
      'updated_at'                    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('student_service_course_comment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StudentServiceCourseComment';
  }

}
