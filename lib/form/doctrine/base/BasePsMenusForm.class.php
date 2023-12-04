<?php

/**
 * PsMenus form base class.
 *
 * @method PsMenus getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsMenusForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'ps_customer_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => false)),
      'ps_workplace_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => true)),
      'date_at'            => new sfWidgetFormDateTime(),
      'ps_meal_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsMeals'), 'add_empty' => false)),
      'ps_food_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsFoods'), 'add_empty' => false)),
      'ps_object_group_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsObjectGroups'), 'add_empty' => false)),
      'note'               => new sfWidgetFormInputText(),
      'description'        => new sfWidgetFormTextarea(),
      'iorder'             => new sfWidgetFormInputText(),
      'user_created_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'))),
      'ps_workplace_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'required' => false)),
      'date_at'            => new sfValidatorDateTime(),
      'ps_meal_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsMeals'))),
      'ps_food_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsFoods'))),
      'ps_object_group_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsObjectGroups'))),
      'note'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'        => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'iorder'             => new sfValidatorInteger(array('required' => false)),
      'user_created_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_menus[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsMenus';
  }

}
