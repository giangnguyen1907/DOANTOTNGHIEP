<?php

/**
 * Service filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseServiceFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ps_customer_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => true)),
      'ps_workplace_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => true)),
      'caphoc'            => new sfWidgetFormFilterInput(),
      'chuongtrinh'       => new sfWidgetFormFilterInput(),
      'khoihoc'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsObjectGroups'), 'add_empty' => true)),
      'dotuoi'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsTypeAge'), 'add_empty' => true)),
      'doituong'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsTargetGroup'), 'add_empty' => true)),
      'ps_school_year_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsSchoolYear'), 'add_empty' => true)),
      'class_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass'), 'add_empty' => true)),
      'title'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'service_group_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ServiceGroup'), 'add_empty' => true)),
      'enable_roll'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'service_type'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'enable_schedule'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'enable_saturday'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_type_fee'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'note'              => new sfWidgetFormFilterInput(),
      'description'       => new sfWidgetFormFilterInput(),
      'iorder'            => new sfWidgetFormFilterInput(),
      'is_default'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_activated'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'ps_image_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsImages'), 'add_empty' => true)),
      'is_kidsschool'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'price'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'number_course'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'mode'              => new sfWidgetFormFilterInput(),
      'service_reduce'    => new sfWidgetFormFilterInput(),
      'service_month'     => new sfWidgetFormFilterInput(),
      'user_created_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'my_classs_list'    => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'MyClass')),
    ));

    $this->setValidators(array(
      'ps_customer_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsCustomer'), 'column' => 'id')),
      'ps_workplace_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsWorkPlaces'), 'column' => 'id')),
      'caphoc'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'chuongtrinh'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'khoihoc'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsObjectGroups'), 'column' => 'id')),
      'dotuoi'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsTypeAge'), 'column' => 'id')),
      'doituong'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsTargetGroup'), 'column' => 'id')),
      'ps_school_year_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsSchoolYear'), 'column' => 'id')),
      'class_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('MyClass'), 'column' => 'id')),
      'title'             => new sfValidatorPass(array('required' => false)),
      'service_group_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('ServiceGroup'), 'column' => 'id')),
      'enable_roll'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'service_type'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'enable_schedule'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'enable_saturday'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_type_fee'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'note'              => new sfValidatorPass(array('required' => false)),
      'description'       => new sfValidatorPass(array('required' => false)),
      'iorder'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_default'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_activated'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'ps_image_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsImages'), 'column' => 'id')),
      'is_kidsschool'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'price'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'number_course'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'mode'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'service_reduce'    => new sfValidatorPass(array('required' => false)),
      'service_month'     => new sfValidatorPass(array('required' => false)),
      'user_created_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'my_classs_list'    => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'MyClass', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('service_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.ClassService ClassService')
      ->andWhereIn('ClassService.myclass_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Service';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'ps_customer_id'    => 'ForeignKey',
      'ps_workplace_id'   => 'ForeignKey',
      'caphoc'            => 'Number',
      'chuongtrinh'       => 'Number',
      'khoihoc'           => 'ForeignKey',
      'dotuoi'            => 'ForeignKey',
      'doituong'          => 'ForeignKey',
      'ps_school_year_id' => 'ForeignKey',
      'class_id'          => 'ForeignKey',
      'title'             => 'Text',
      'service_group_id'  => 'ForeignKey',
      'enable_roll'       => 'Number',
      'service_type'      => 'Number',
      'enable_schedule'   => 'Boolean',
      'enable_saturday'   => 'Boolean',
      'is_type_fee'       => 'Boolean',
      'note'              => 'Text',
      'description'       => 'Text',
      'iorder'            => 'Number',
      'is_default'        => 'Boolean',
      'is_activated'      => 'Boolean',
      'ps_image_id'       => 'ForeignKey',
      'is_kidsschool'     => 'Boolean',
      'price'             => 'Number',
      'number_course'     => 'Number',
      'mode'              => 'Number',
      'service_reduce'    => 'Text',
      'service_month'     => 'Text',
      'user_created_id'   => 'ForeignKey',
      'user_updated_id'   => 'ForeignKey',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
      'my_classs_list'    => 'ManyKey',
    );
  }
}
