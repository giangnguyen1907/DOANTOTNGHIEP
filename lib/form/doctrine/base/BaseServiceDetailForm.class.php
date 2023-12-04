<?php

/**
 * ServiceDetail form base class.
 *
 * @method ServiceDetail getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseServiceDetailForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'service_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Service'), 'add_empty' => false)),
      'amount'     => new sfWidgetFormInputText(),
      'by_number'  => new sfWidgetFormInputText(),
      'detail_at'  => new sfWidgetFormDateTime(),
      'detail_end' => new sfWidgetFormDateTime(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'service_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Service'))),
      'amount'     => new sfValidatorPass(),
      'by_number'  => new sfValidatorPass(),
      'detail_at'  => new sfValidatorDateTime(),
      'detail_end' => new sfValidatorDateTime(),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('service_detail[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ServiceDetail';
  }

}
