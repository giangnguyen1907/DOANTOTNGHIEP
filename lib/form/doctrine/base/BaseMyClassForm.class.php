<?php

/**
 * MyClass form base class.
 *
 * @method MyClass getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseMyClassForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'ps_customer_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => false)),
      'ps_workplace_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => true)),
      'ps_obj_group_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsObjectGroups'), 'add_empty' => false)),
      'school_year_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsSchoolYear'), 'add_empty' => false)),
      'ps_class_room_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsClassRooms'), 'add_empty' => false)),
      'code'             => new sfWidgetFormInputText(),
      'name'             => new sfWidgetFormInputText(),
      'note'             => new sfWidgetFormInputText(),
      'description'      => new sfWidgetFormTextarea(),
      'iorder'           => new sfWidgetFormInputText(),
      'student_number'   => new sfWidgetFormInputText(),
      'type_year'        => new sfWidgetFormInputText(),
      'is_activated'     => new sfWidgetFormInputCheckbox(),
      'is_lastyear'      => new sfWidgetFormInputCheckbox(),
      'user_created_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
      'services_list'    => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Service')),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'))),
      'ps_workplace_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'required' => false)),
      'ps_obj_group_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsObjectGroups'))),
      'school_year_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsSchoolYear'))),
      'ps_class_room_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsClassRooms'))),
      'code'             => new sfValidatorString(array('max_length' => 100)),
      'name'             => new sfValidatorString(array('max_length' => 255)),
      'note'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'      => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'iorder'           => new sfValidatorInteger(array('required' => false)),
      'student_number'   => new sfValidatorInteger(array('required' => false)),
      'type_year'        => new sfValidatorInteger(array('required' => false)),
      'is_activated'     => new sfValidatorBoolean(array('required' => false)),
      'is_lastyear'      => new sfValidatorBoolean(array('required' => false)),
      'user_created_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
      'services_list'    => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Service', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'MyClass', 'column' => array('code', 'ps_customer_id', 'school_year_id')))
    );

    $this->widgetSchema->setNameFormat('my_class[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MyClass';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['services_list']))
    {
      $this->setDefault('services_list', $this->object->Services->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveServicesList($con);

    parent::doSave($con);
  }

  public function saveServicesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['services_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Services->getPrimaryKeys();
    $values = $this->getValue('services_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Services', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Services', array_values($link));
    }
  }

}
