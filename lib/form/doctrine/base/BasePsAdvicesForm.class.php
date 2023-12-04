<?php

/**
 * PsAdvices form base class.
 *
 * @method PsAdvices getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsAdvicesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'student_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsStudent'), 'add_empty' => false)),
      'user_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserId'), 'add_empty' => false)),
      'category_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsAdviceCategories'), 'add_empty' => false)),
      'title'           => new sfWidgetFormInputText(),
      'content'         => new sfWidgetFormInputText(),
      'is_activated'    => new sfWidgetFormInputCheckbox(),
      'relative_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Relative'), 'add_empty' => true)),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'date_at'         => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'student_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsStudent'))),
      'user_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserId'))),
      'category_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsAdviceCategories'))),
      'title'           => new sfValidatorString(array('max_length' => 255)),
      'content'         => new sfValidatorString(array('max_length' => 255)),
      'is_activated'    => new sfValidatorBoolean(array('required' => false)),
      'relative_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Relative'), 'required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'date_at'         => new sfValidatorPass(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_advices[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsAdvices';
  }

}
