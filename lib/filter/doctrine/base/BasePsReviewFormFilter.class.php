<?php

/**
 * PsReview filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsReviewFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ps_customer_id'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ps_workplace_id'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'member_id'          => new sfWidgetFormFilterInput(),
      'student_id'         => new sfWidgetFormFilterInput(),
      'ps_class_id'        => new sfWidgetFormFilterInput(),
      'category_review_id' => new sfWidgetFormFilterInput(),
      'review_relative_id' => new sfWidgetFormFilterInput(),
      'note'               => new sfWidgetFormFilterInput(),
      'status'             => new sfWidgetFormFilterInput(),
      'user_created_id'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_updated_id'    => new sfWidgetFormFilterInput(),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ps_customer_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ps_workplace_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'member_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'student_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ps_class_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'category_review_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'review_relative_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'note'               => new sfValidatorPass(array('required' => false)),
      'status'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_created_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_updated_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ps_review_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsReview';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'ps_customer_id'     => 'Number',
      'ps_workplace_id'    => 'Number',
      'member_id'          => 'Number',
      'student_id'         => 'Number',
      'ps_class_id'        => 'Number',
      'category_review_id' => 'Number',
      'review_relative_id' => 'Number',
      'note'               => 'Text',
      'status'             => 'Number',
      'user_created_id'    => 'Number',
      'user_updated_id'    => 'Number',
      'created_at'         => 'Date',
      'updated_at'         => 'Date',
    );
  }
}
