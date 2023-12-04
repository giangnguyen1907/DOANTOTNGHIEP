<?php

/**
 * PsEmails form base class.
 *
 * @method PsEmails getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsEmailsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'ps_email'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsEmails'), 'add_empty' => false)),
      'obj_id'     => new sfWidgetFormInputText(),
      'obj_type'   => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_email'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsEmails'))),
      'obj_id'     => new sfValidatorInteger(),
      'obj_type'   => new sfValidatorString(array('max_length' => 1)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'PsEmails', 'column' => array('ps_email')))
    );

    $this->widgetSchema->setNameFormat('ps_emails[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsEmails';
  }

}
