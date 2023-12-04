<?php

/**
 * MyClass filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseMyClassFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ps_customer_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => true)),
      'ps_workplace_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => true)),
      'ps_obj_group_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsObjectGroups'), 'add_empty' => true)),
      'school_year_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsSchoolYear'), 'add_empty' => true)),
      'ps_class_room_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsClassRooms'), 'add_empty' => true)),
      'code'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'note'             => new sfWidgetFormFilterInput(),
      'description'      => new sfWidgetFormFilterInput(),
      'iorder'           => new sfWidgetFormFilterInput(),
      'student_number'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type_year'        => new sfWidgetFormFilterInput(),
      'is_activated'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_lastyear'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'user_created_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'services_list'    => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Service')),
    ));

    $this->setValidators(array(
      'ps_customer_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsCustomer'), 'column' => 'id')),
      'ps_workplace_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsWorkPlaces'), 'column' => 'id')),
      'ps_obj_group_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsObjectGroups'), 'column' => 'id')),
      'school_year_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsSchoolYear'), 'column' => 'id')),
      'ps_class_room_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsClassRooms'), 'column' => 'id')),
      'code'             => new sfValidatorPass(array('required' => false)),
      'name'             => new sfValidatorPass(array('required' => false)),
      'note'             => new sfValidatorPass(array('required' => false)),
      'description'      => new sfValidatorPass(array('required' => false)),
      'iorder'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'student_number'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type_year'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_activated'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_lastyear'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'user_created_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'services_list'    => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Service', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('my_class_filters[%s]');

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
      ->andWhereIn('ClassService.service_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'MyClass';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'ps_customer_id'   => 'ForeignKey',
      'ps_workplace_id'  => 'ForeignKey',
      'ps_obj_group_id'  => 'ForeignKey',
      'school_year_id'   => 'ForeignKey',
      'ps_class_room_id' => 'ForeignKey',
      'code'             => 'Text',
      'name'             => 'Text',
      'note'             => 'Text',
      'description'      => 'Text',
      'iorder'           => 'Number',
      'student_number'   => 'Number',
      'type_year'        => 'Number',
      'is_activated'     => 'Boolean',
      'is_lastyear'      => 'Boolean',
      'user_created_id'  => 'ForeignKey',
      'user_updated_id'  => 'ForeignKey',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
      'services_list'    => 'ManyKey',
    );
  }
}
