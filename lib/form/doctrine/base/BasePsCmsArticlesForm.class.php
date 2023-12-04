<?php

/**
 * PsCmsArticles form base class.
 *
 * @method PsCmsArticles getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsCmsArticlesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'ps_customer_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => true)),
      'ps_workplace_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => true)),
      'title'           => new sfWidgetFormInputText(),
      'file_name'       => new sfWidgetFormInputText(),
      'note'            => new sfWidgetFormInputText(),
      'description'     => new sfWidgetFormTextarea(),
      'is_publish'      => new sfWidgetFormInputText(),
      'is_access'       => new sfWidgetFormInputCheckbox(),
      'is_global'       => new sfWidgetFormInputCheckbox(),
      'year_data'       => new sfWidgetFormInputText(),
      'user_publish_id' => new sfWidgetFormInputText(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'required' => false)),
      'ps_workplace_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'required' => false)),
      'title'           => new sfValidatorString(array('max_length' => 150)),
      'file_name'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'note'            => new sfValidatorString(array('max_length' => 250, 'required' => false)),
      'description'     => new sfValidatorString(array('required' => false)),
      'is_publish'      => new sfValidatorInteger(array('required' => false)),
      'is_access'       => new sfValidatorBoolean(array('required' => false)),
      'is_global'       => new sfValidatorBoolean(array('required' => false)),
      'year_data'       => new sfValidatorInteger(array('required' => false)),
      'user_publish_id' => new sfValidatorInteger(array('required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_cms_articles[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsCmsArticles';
  }

}
