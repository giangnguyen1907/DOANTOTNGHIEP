<?php

/**
 * PsLogtimes filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsLogtimesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'student_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => true)),
      'login_at'           => new sfWidgetFormDoctrineChoice(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'model' => $this->getRelatedModelName('DayInMonth'), 'add_empty' => true)),
      'login_relative_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('RelativeLogin'), 'add_empty' => true)),
      'login_member_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsMemberLogin'), 'add_empty' => true)),
      'logout_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'logout_member_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsMemberLogout'), 'add_empty' => true)),
      'logout_relative_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('RelativeLogout'), 'add_empty' => true)),
      'log_value'          => new sfWidgetFormFilterInput(),
      'log_code'           => new sfWidgetFormFilterInput(),
      'note'               => new sfWidgetFormFilterInput(),
      'user_created_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'student_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Student'), 'column' => 'id')),
      'login_at'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('DayInMonth'), 'column' => 'id')),
      'login_relative_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('RelativeLogin'), 'column' => 'id')),
      'login_member_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsMemberLogin'), 'column' => 'id')),
      'logout_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'logout_member_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsMemberLogout'), 'column' => 'id')),
      'logout_relative_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('RelativeLogout'), 'column' => 'id')),
      'log_value'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'log_code'           => new sfValidatorPass(array('required' => false)),
      'note'               => new sfValidatorPass(array('required' => false)),
      'user_created_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ps_logtimes_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsLogtimes';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'student_id'         => 'ForeignKey',
      'login_at'           => 'ForeignKey',
      'login_relative_id'  => 'ForeignKey',
      'login_member_id'    => 'ForeignKey',
      'logout_at'          => 'Date',
      'logout_member_id'   => 'ForeignKey',
      'logout_relative_id' => 'ForeignKey',
      'log_value'          => 'Number',
      'log_code'           => 'Text',
      'note'               => 'Text',
      'user_created_id'    => 'ForeignKey',
      'user_updated_id'    => 'ForeignKey',
      'created_at'         => 'Date',
      'updated_at'         => 'Date',
    );
  }
}
