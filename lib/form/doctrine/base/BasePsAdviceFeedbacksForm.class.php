<?php

/**
 * PsAdviceFeedbacks form base class.
 *
 * @method PsAdviceFeedbacks getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsAdviceFeedbacksForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'advice_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsAdvices'), 'add_empty' => false)),
      'umember_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UMember'), 'add_empty' => true)),
      'urelative_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('URelative'), 'add_empty' => true)),
      'is_teacher'      => new sfWidgetFormInputCheckbox(),
      'content'         => new sfWidgetFormInputText(),
      'is_activated'    => new sfWidgetFormInputCheckbox(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'advice_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsAdvices'))),
      'umember_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UMember'), 'required' => false)),
      'urelative_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('URelative'), 'required' => false)),
      'is_teacher'      => new sfValidatorBoolean(array('required' => false)),
      'content'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_activated'    => new sfValidatorBoolean(array('required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_advice_feedbacks[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsAdviceFeedbacks';
  }

}
