<?php

/**
 * PsMemberCertificates form base class.
 *
 * @method PsMemberCertificates getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsMemberCertificatesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'member_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsMember'), 'add_empty' => true)),
      'ps_certificate_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCertificate'), 'add_empty' => true)),
      'shool_name'        => new sfWidgetFormInputText(),
      'graduation_year'   => new sfWidgetFormInputText(),
      'note'              => new sfWidgetFormInputText(),
      'user_created_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'member_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsMember'), 'required' => false)),
      'ps_certificate_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsCertificate'), 'required' => false)),
      'shool_name'        => new sfValidatorString(array('max_length' => 255)),
      'graduation_year'   => new sfValidatorInteger(array('required' => false)),
      'note'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user_created_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_member_certificates[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsMemberCertificates';
  }

}
