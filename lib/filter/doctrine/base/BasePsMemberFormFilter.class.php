<?php

/**
 * PsMember filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsMemberFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ps_customer_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsCustomer'), 'add_empty' => true)),
      'ps_workplace_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsWorkPlaces'), 'add_empty' => true)),
      'ps_province_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsProvinces'), 'add_empty' => true)),
      'ps_district_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsDistricts'), 'add_empty' => true)),
      'member_code'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'first_name'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'last_name'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sex'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'birthday'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'identity_card'   => new sfWidgetFormFilterInput(),
      'card_date'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'card_local'      => new sfWidgetFormFilterInput(),
      'image'           => new sfWidgetFormFilterInput(),
      'avatar'          => new sfWidgetFormFilterInput(),
      'ethnic_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsEthnic'), 'add_empty' => true)),
      'religion_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsReligion'), 'add_empty' => true)),
      'address'         => new sfWidgetFormFilterInput(),
      'phone'           => new sfWidgetFormFilterInput(),
      'mobile'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'email'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsEmails'), 'add_empty' => true)),
      'nationality'     => new sfWidgetFormFilterInput(),
      'year_data'       => new sfWidgetFormFilterInput(),
      'is_status'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'rank'            => new sfWidgetFormFilterInput(),
      'is_import'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ps_customer_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsCustomer'), 'column' => 'id')),
      'ps_workplace_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsWorkPlaces'), 'column' => 'id')),
      'ps_province_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsProvinces'), 'column' => 'id')),
      'ps_district_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsDistricts'), 'column' => 'id')),
      'member_code'     => new sfValidatorPass(array('required' => false)),
      'first_name'      => new sfValidatorPass(array('required' => false)),
      'last_name'       => new sfValidatorPass(array('required' => false)),
      'sex'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'birthday'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'identity_card'   => new sfValidatorPass(array('required' => false)),
      'card_date'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'card_local'      => new sfValidatorPass(array('required' => false)),
      'image'           => new sfValidatorPass(array('required' => false)),
      'avatar'          => new sfValidatorPass(array('required' => false)),
      'ethnic_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsEthnic'), 'column' => 'id')),
      'religion_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsReligion'), 'column' => 'id')),
      'address'         => new sfValidatorPass(array('required' => false)),
      'phone'           => new sfValidatorPass(array('required' => false)),
      'mobile'          => new sfValidatorPass(array('required' => false)),
      'email'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsEmails'), 'column' => 'id')),
      'nationality'     => new sfValidatorPass(array('required' => false)),
      'year_data'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_status'       => new sfValidatorPass(array('required' => false)),
      'rank'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_import'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'user_created_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ps_member_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsMember';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'ps_customer_id'  => 'ForeignKey',
      'ps_workplace_id' => 'ForeignKey',
      'ps_province_id'  => 'ForeignKey',
      'ps_district_id'  => 'ForeignKey',
      'member_code'     => 'Text',
      'first_name'      => 'Text',
      'last_name'       => 'Text',
      'sex'             => 'Boolean',
      'birthday'        => 'Date',
      'identity_card'   => 'Text',
      'card_date'       => 'Date',
      'card_local'      => 'Text',
      'image'           => 'Text',
      'avatar'          => 'Text',
      'ethnic_id'       => 'ForeignKey',
      'religion_id'     => 'ForeignKey',
      'address'         => 'Text',
      'phone'           => 'Text',
      'mobile'          => 'Text',
      'email'           => 'ForeignKey',
      'nationality'     => 'Text',
      'year_data'       => 'Number',
      'is_status'       => 'Text',
      'rank'            => 'Number',
      'is_import'       => 'Boolean',
      'user_created_id' => 'ForeignKey',
      'user_updated_id' => 'ForeignKey',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
