<?php

/**
 * Student filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseStudentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ps_customer_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => true)),
      'ps_workplace_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => true)),
      'student_code'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'first_name'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'last_name'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sex'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'birthday'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'common_name'        => new sfWidgetFormFilterInput(),
      'nick_name'          => new sfWidgetFormFilterInput(),
      'image'              => new sfWidgetFormFilterInput(),
      'avatar'             => new sfWidgetFormFilterInput(),
      'ethnic_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsEthnic'), 'add_empty' => true)),
      'religion_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsReligion'), 'add_empty' => true)),
      'nationality'        => new sfWidgetFormFilterInput(),
      'address'            => new sfWidgetFormFilterInput(),
      'status'             => new sfWidgetFormFilterInput(),
      'year_data'          => new sfWidgetFormFilterInput(),
      'number_ae'          => new sfWidgetFormFilterInput(),
      'type_year'          => new sfWidgetFormFilterInput(),
      'deleted_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'statistic_class_id' => new sfWidgetFormFilterInput(),
      'relative_id'        => new sfWidgetFormFilterInput(),
      'start_date_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'is_import'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'current_class_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass'), 'add_empty' => true)),
      'policy_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsPolicyGroup'), 'add_empty' => true)),
      'caphoc'             => new sfWidgetFormFilterInput(),
      'chuongtrinh'        => new sfWidgetFormFilterInput(),
      'khoihoc'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsObjectGroups'), 'add_empty' => true)),
      'doituong'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsTargetGroup'), 'add_empty' => true)),
      'attributes'         => new sfWidgetFormFilterInput(),
      'user_created_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ps_customer_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsCustomer'), 'column' => 'id')),
      'ps_workplace_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsWorkPlaces'), 'column' => 'id')),
      'student_code'       => new sfValidatorPass(array('required' => false)),
      'first_name'         => new sfValidatorPass(array('required' => false)),
      'last_name'          => new sfValidatorPass(array('required' => false)),
      'sex'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'birthday'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'common_name'        => new sfValidatorPass(array('required' => false)),
      'nick_name'          => new sfValidatorPass(array('required' => false)),
      'image'              => new sfValidatorPass(array('required' => false)),
      'avatar'             => new sfValidatorPass(array('required' => false)),
      'ethnic_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsEthnic'), 'column' => 'id')),
      'religion_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsReligion'), 'column' => 'id')),
      'nationality'        => new sfValidatorPass(array('required' => false)),
      'address'            => new sfValidatorPass(array('required' => false)),
      'status'             => new sfValidatorPass(array('required' => false)),
      'year_data'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'number_ae'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type_year'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'deleted_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'statistic_class_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'relative_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'start_date_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'is_import'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'current_class_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('MyClass'), 'column' => 'id')),
      'policy_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsPolicyGroup'), 'column' => 'id')),
      'caphoc'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'chuongtrinh'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'khoihoc'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsObjectGroups'), 'column' => 'id')),
      'doituong'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsTargetGroup'), 'column' => 'id')),
      'attributes'         => new sfValidatorPass(array('required' => false)),
      'user_created_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('student_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Student';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'ps_customer_id'     => 'ForeignKey',
      'ps_workplace_id'    => 'ForeignKey',
      'student_code'       => 'Text',
      'first_name'         => 'Text',
      'last_name'          => 'Text',
      'sex'                => 'Boolean',
      'birthday'           => 'Date',
      'common_name'        => 'Text',
      'nick_name'          => 'Text',
      'image'              => 'Text',
      'avatar'             => 'Text',
      'ethnic_id'          => 'ForeignKey',
      'religion_id'        => 'ForeignKey',
      'nationality'        => 'Text',
      'address'            => 'Text',
      'status'             => 'Text',
      'year_data'          => 'Number',
      'number_ae'          => 'Number',
      'type_year'          => 'Number',
      'deleted_at'         => 'Date',
      'statistic_class_id' => 'Number',
      'relative_id'        => 'Number',
      'start_date_at'      => 'Date',
      'is_import'          => 'Boolean',
      'current_class_id'   => 'ForeignKey',
      'policy_id'          => 'ForeignKey',
      'caphoc'             => 'Number',
      'chuongtrinh'        => 'Number',
      'khoihoc'            => 'ForeignKey',
      'doituong'           => 'ForeignKey',
      'attributes'         => 'Text',
      'user_created_id'    => 'ForeignKey',
      'user_updated_id'    => 'ForeignKey',
      'created_at'         => 'Date',
      'updated_at'         => 'Date',
    );
  }
}
