<?php

/**
 * PsFeeAcounts filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsFeeAcountsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ps_customer_id'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ps_workplace_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'title'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'tk_no'           => new sfWidgetFormFilterInput(),
      'tk_co'           => new sfWidgetFormFilterInput(),
      'tk_von'          => new sfWidgetFormFilterInput(),
      'tk_xuat'         => new sfWidgetFormFilterInput(),
      'is_type'         => new sfWidgetFormFilterInput(),
      'user_created_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_updated_id' => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ps_customer_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ps_workplace_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'title'           => new sfValidatorPass(array('required' => false)),
      'tk_no'           => new sfValidatorPass(array('required' => false)),
      'tk_co'           => new sfValidatorPass(array('required' => false)),
      'tk_von'          => new sfValidatorPass(array('required' => false)),
      'tk_xuat'         => new sfValidatorPass(array('required' => false)),
      'is_type'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_created_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_updated_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ps_fee_acounts_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsFeeAcounts';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'ps_customer_id'  => 'Number',
      'ps_workplace_id' => 'Number',
      'title'           => 'Text',
      'tk_no'           => 'Text',
      'tk_co'           => 'Text',
      'tk_von'          => 'Text',
      'tk_xuat'         => 'Text',
      'is_type'         => 'Number',
      'user_created_id' => 'Number',
      'user_updated_id' => 'Number',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
