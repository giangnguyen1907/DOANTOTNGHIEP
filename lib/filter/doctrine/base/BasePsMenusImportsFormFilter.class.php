<?php

/**
 * PsMenusImports filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsMenusImportsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ps_customer_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => true)),
      'ps_workplace_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => true)),
      'date_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'ps_meal_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsMeals'), 'add_empty' => true)),
      'ps_object_group_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsObjectGroups'), 'add_empty' => true)),
      'description'        => new sfWidgetFormFilterInput(),
      'iorder'             => new sfWidgetFormFilterInput(),
      'ps_image_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsImages'), 'add_empty' => true)),
      'file_image'         => new sfWidgetFormFilterInput(),
      'user_created_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ps_customer_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsCustomer'), 'column' => 'id')),
      'ps_workplace_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsWorkPlaces'), 'column' => 'id')),
      'date_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'ps_meal_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsMeals'), 'column' => 'id')),
      'ps_object_group_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsObjectGroups'), 'column' => 'id')),
      'description'        => new sfValidatorPass(array('required' => false)),
      'iorder'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ps_image_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsImages'), 'column' => 'id')),
      'file_image'         => new sfValidatorPass(array('required' => false)),
      'user_created_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ps_menus_imports_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsMenusImports';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'ps_customer_id'     => 'ForeignKey',
      'ps_workplace_id'    => 'ForeignKey',
      'date_at'            => 'Date',
      'ps_meal_id'         => 'ForeignKey',
      'ps_object_group_id' => 'ForeignKey',
      'description'        => 'Text',
      'iorder'             => 'Number',
      'ps_image_id'        => 'ForeignKey',
      'file_image'         => 'Text',
      'user_created_id'    => 'ForeignKey',
      'user_updated_id'    => 'ForeignKey',
      'created_at'         => 'Date',
      'updated_at'         => 'Date',
    );
  }
}
