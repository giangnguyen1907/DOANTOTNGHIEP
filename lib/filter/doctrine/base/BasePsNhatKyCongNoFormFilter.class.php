<?php

/**
 * PsNhatKyCongNo filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsNhatKyCongNoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ps_customer_id'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ps_workplace_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'chungtu'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sochungtu'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'tkno'            => new sfWidgetFormFilterInput(),
      'tkco'            => new sfWidgetFormFilterInput(),
      'thoigian'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'doituongno'      => new sfWidgetFormFilterInput(),
      'doituongco'      => new sfWidgetFormFilterInput(),
      'idhocsinh'       => new sfWidgetFormFilterInput(),
      'iddichvu'        => new sfWidgetFormFilterInput(),
      'tendichvu'       => new sfWidgetFormFilterInput(),
      'donvitinh'       => new sfWidgetFormFilterInput(),
      'soluong'         => new sfWidgetFormFilterInput(),
      'dongia'          => new sfWidgetFormFilterInput(),
      'thanhtien'       => new sfWidgetFormFilterInput(),
      'giamtru'         => new sfWidgetFormFilterInput(),
      'machietkhau'     => new sfWidgetFormFilterInput(),
      'mucdo'           => new sfWidgetFormFilterInput(),
      'chietkhau'       => new sfWidgetFormFilterInput(),
      'kieuchietkhau'   => new sfWidgetFormFilterInput(),
      'makhono'         => new sfWidgetFormFilterInput(),
      'makhoco'         => new sfWidgetFormFilterInput(),
      'user_created_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_updated_id' => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ps_customer_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ps_workplace_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'chungtu'         => new sfValidatorPass(array('required' => false)),
      'sochungtu'       => new sfValidatorPass(array('required' => false)),
      'tkno'            => new sfValidatorPass(array('required' => false)),
      'tkco'            => new sfValidatorPass(array('required' => false)),
      'thoigian'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'doituongno'      => new sfValidatorPass(array('required' => false)),
      'doituongco'      => new sfValidatorPass(array('required' => false)),
      'idhocsinh'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'iddichvu'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tendichvu'       => new sfValidatorPass(array('required' => false)),
      'donvitinh'       => new sfValidatorPass(array('required' => false)),
      'soluong'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'dongia'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'thanhtien'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'giamtru'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'machietkhau'     => new sfValidatorPass(array('required' => false)),
      'mucdo'           => new sfValidatorPass(array('required' => false)),
      'chietkhau'       => new sfValidatorPass(array('required' => false)),
      'kieuchietkhau'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'makhono'         => new sfValidatorPass(array('required' => false)),
      'makhoco'         => new sfValidatorPass(array('required' => false)),
      'user_created_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_updated_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ps_nhat_ky_cong_no_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsNhatKyCongNo';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'ps_customer_id'  => 'Number',
      'ps_workplace_id' => 'Number',
      'chungtu'         => 'Text',
      'sochungtu'       => 'Text',
      'tkno'            => 'Text',
      'tkco'            => 'Text',
      'thoigian'        => 'Date',
      'doituongno'      => 'Text',
      'doituongco'      => 'Text',
      'idhocsinh'       => 'Number',
      'iddichvu'        => 'Number',
      'tendichvu'       => 'Text',
      'donvitinh'       => 'Text',
      'soluong'         => 'Number',
      'dongia'          => 'Number',
      'thanhtien'       => 'Number',
      'giamtru'         => 'Number',
      'machietkhau'     => 'Text',
      'mucdo'           => 'Text',
      'chietkhau'       => 'Text',
      'kieuchietkhau'   => 'Number',
      'makhono'         => 'Text',
      'makhoco'         => 'Text',
      'user_created_id' => 'Number',
      'user_updated_id' => 'Number',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
