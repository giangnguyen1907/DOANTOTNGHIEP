<?php

/**
 * PsTemplateExports form base class.
 *
 * @method PsTemplateExports getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsTemplateExportsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'app_code'        => new sfWidgetFormInputText(),
      'title'           => new sfWidgetFormInputText(),
      'name_file'       => new sfWidgetFormTextarea(),
      'img_file'        => new sfWidgetFormTextarea(),
      'is_activated'    => new sfWidgetFormInputCheckbox(),
      'note'            => new sfWidgetFormTextarea(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'app_code'        => new sfValidatorString(array('max_length' => 255)),
      'title'           => new sfValidatorString(array('max_length' => 255)),
      'name_file'       => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'img_file'        => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'is_activated'    => new sfValidatorBoolean(array('required' => false)),
      'note'            => new sfValidatorString(array('max_length' => 300, 'required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_template_exports[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsTemplateExports';
  }

}
