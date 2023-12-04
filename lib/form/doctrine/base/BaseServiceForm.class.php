<?php

/**
 * Service form base class.
 *
 * @method Service getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseServiceForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'ps_customer_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => false)),
      'ps_workplace_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => true)),
      'caphoc'            => new sfWidgetFormInputText(),
      'chuongtrinh'       => new sfWidgetFormInputText(),
      'khoihoc'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsObjectGroups'), 'add_empty' => true)),
      'dotuoi'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsTypeAge'), 'add_empty' => true)),
      'doituong'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsTargetGroup'), 'add_empty' => true)),
      'ps_school_year_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsSchoolYear'), 'add_empty' => true)),
      'class_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass'), 'add_empty' => true)),
      'title'             => new sfWidgetFormInputText(),
      'service_group_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ServiceGroup'), 'add_empty' => false)),
      'enable_roll'       => new sfWidgetFormInputText(),
      'service_type'      => new sfWidgetFormInputText(),
      'enable_schedule'   => new sfWidgetFormInputCheckbox(),
      'enable_saturday'   => new sfWidgetFormInputCheckbox(),
      'is_type_fee'       => new sfWidgetFormInputCheckbox(),
      'note'              => new sfWidgetFormInputText(),
      'description'       => new sfWidgetFormTextarea(),
      'iorder'            => new sfWidgetFormInputText(),
      'is_default'        => new sfWidgetFormInputCheckbox(),
      'is_activated'      => new sfWidgetFormInputCheckbox(),
      'ps_image_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsImages'), 'add_empty' => true)),
      'is_kidsschool'     => new sfWidgetFormInputCheckbox(),
      'price'             => new sfWidgetFormInputText(),
      'number_course'     => new sfWidgetFormInputText(),
      'mode'              => new sfWidgetFormInputText(),
      'service_reduce'    => new sfWidgetFormInputText(),
      'service_month'     => new sfWidgetFormInputText(),
      'user_created_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
      'my_classs_list'    => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'MyClass')),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'))),
      'ps_workplace_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'required' => false)),
      'caphoc'            => new sfValidatorInteger(array('required' => false)),
      'chuongtrinh'       => new sfValidatorInteger(array('required' => false)),
      'khoihoc'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsObjectGroups'), 'required' => false)),
      'dotuoi'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsTypeAge'), 'required' => false)),
      'doituong'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsTargetGroup'), 'required' => false)),
      'ps_school_year_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsSchoolYear'), 'required' => false)),
      'class_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass'), 'required' => false)),
      'title'             => new sfValidatorString(array('max_length' => 255)),
      'service_group_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ServiceGroup'))),
      'enable_roll'       => new sfValidatorInteger(array('required' => false)),
      'service_type'      => new sfValidatorInteger(array('required' => false)),
      'enable_schedule'   => new sfValidatorBoolean(array('required' => false)),
      'enable_saturday'   => new sfValidatorBoolean(array('required' => false)),
      'is_type_fee'       => new sfValidatorBoolean(array('required' => false)),
      'note'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'description'       => new sfValidatorString(array('max_length' => 2000, 'required' => false)),
      'iorder'            => new sfValidatorInteger(array('required' => false)),
      'is_default'        => new sfValidatorBoolean(array('required' => false)),
      'is_activated'      => new sfValidatorBoolean(array('required' => false)),
      'ps_image_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsImages'), 'required' => false)),
      'is_kidsschool'     => new sfValidatorBoolean(array('required' => false)),
      'price'             => new sfValidatorInteger(array('required' => false)),
      'number_course'     => new sfValidatorInteger(array('required' => false)),
      'mode'              => new sfValidatorInteger(array('required' => false)),
      'service_reduce'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'service_month'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user_created_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
      'my_classs_list'    => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'MyClass', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('service[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Service';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['my_classs_list']))
    {
      $this->setDefault('my_classs_list', $this->object->MyClasss->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveMyClasssList($con);

    parent::doSave($con);
  }

  public function saveMyClasssList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['my_classs_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->MyClasss->getPrimaryKeys();
    $values = $this->getValue('my_classs_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('MyClasss', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('MyClasss', array_values($link));
    }
  }

}
