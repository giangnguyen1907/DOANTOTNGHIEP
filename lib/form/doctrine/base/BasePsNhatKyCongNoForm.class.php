<?php

/**
 * PsNhatKyCongNo form base class.
 *
 * @method PsNhatKyCongNo getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsNhatKyCongNoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'ps_customer_id'  => new sfWidgetFormInputText(),
      'ps_workplace_id' => new sfWidgetFormInputText(),
      'chungtu'         => new sfWidgetFormInputText(),
      'sochungtu'       => new sfWidgetFormInputText(),
      'tkno'            => new sfWidgetFormInputText(),
      'tkco'            => new sfWidgetFormInputText(),
      'thoigian'        => new sfWidgetFormDate(),
      'doituongno'      => new sfWidgetFormInputText(),
      'doituongco'      => new sfWidgetFormInputText(),
      'idhocsinh'       => new sfWidgetFormInputText(),
      'iddichvu'        => new sfWidgetFormInputText(),
      'tendichvu'       => new sfWidgetFormInputText(),
      'donvitinh'       => new sfWidgetFormInputText(),
      'soluong'         => new sfWidgetFormInputText(),
      'dongia'          => new sfWidgetFormInputText(),
      'thanhtien'       => new sfWidgetFormInputText(),
      'giamtru'         => new sfWidgetFormInputText(),
      'machietkhau'     => new sfWidgetFormInputText(),
      'mucdo'           => new sfWidgetFormInputText(),
      'chietkhau'       => new sfWidgetFormInputText(),
      'kieuchietkhau'   => new sfWidgetFormInputText(),
      'makhono'         => new sfWidgetFormInputText(),
      'makhoco'         => new sfWidgetFormInputText(),
      'user_created_id' => new sfWidgetFormInputText(),
      'user_updated_id' => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'  => new sfValidatorInteger(),
      'ps_workplace_id' => new sfValidatorInteger(),
      'chungtu'         => new sfValidatorString(array('max_length' => 255)),
      'sochungtu'       => new sfValidatorString(array('max_length' => 255)),
      'tkno'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'tkco'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'thoigian'        => new sfValidatorDate(array('required' => false)),
      'doituongno'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'doituongco'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'idhocsinh'       => new sfValidatorInteger(array('required' => false)),
      'iddichvu'        => new sfValidatorInteger(array('required' => false)),
      'tendichvu'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'donvitinh'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'soluong'         => new sfValidatorInteger(array('required' => false)),
      'dongia'          => new sfValidatorInteger(array('required' => false)),
      'thanhtien'       => new sfValidatorInteger(array('required' => false)),
      'giamtru'         => new sfValidatorInteger(array('required' => false)),
      'machietkhau'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'mucdo'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'chietkhau'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'kieuchietkhau'   => new sfValidatorInteger(array('required' => false)),
      'makhono'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'makhoco'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user_created_id' => new sfValidatorInteger(),
      'user_updated_id' => new sfValidatorInteger(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_nhat_ky_cong_no[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsNhatKyCongNo';
  }

}
