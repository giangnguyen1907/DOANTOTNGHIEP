<?php

/**
 * PsFeeNewsLetters form base class.
 *
 * @method PsFeeNewsLetters getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsFeeNewsLettersForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'ps_workplace_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => false)),
      'ps_year_month'          => new sfWidgetFormInputText(),
      'title'                  => new sfWidgetFormInputText(),
      'note'                   => new sfWidgetFormTextarea(),
      'is_public'              => new sfWidgetFormInputCheckbox(),
      'number_push_notication' => new sfWidgetFormInputText(),
      'user_created_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'             => new sfWidgetFormDateTime(),
      'updated_at'             => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_workplace_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'))),
      'ps_year_month'          => new sfValidatorInteger(),
      'title'                  => new sfValidatorString(array('max_length' => 255)),
      'note'                   => new sfValidatorString(),
      'is_public'              => new sfValidatorBoolean(array('required' => false)),
      'number_push_notication' => new sfValidatorInteger(array('required' => false)),
      'user_created_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'             => new sfValidatorDateTime(),
      'updated_at'             => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_fee_news_letters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsFeeNewsLetters';
  }

}
