<?php

/**
 * CollectedStudent form base class.
 *
 * @method CollectedStudent getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCollectedStudentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'student_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'add_empty' => true)),
      'receivable_student_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ReceivableStudent'), 'add_empty' => true)),
      'collected_at'          => new sfWidgetFormDateTime(),
      'amount'                => new sfWidgetFormInputText(),
      'note'                  => new sfWidgetFormInputText(),
      'created_at'            => new sfWidgetFormDateTime(),
      'updated_at'            => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'student_id'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Student'), 'required' => false)),
      'receivable_student_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ReceivableStudent'), 'required' => false)),
      'collected_at'          => new sfValidatorDateTime(array('required' => false)),
      'amount'                => new sfValidatorPass(array('required' => false)),
      'note'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'            => new sfValidatorDateTime(),
      'updated_at'            => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('collected_student[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CollectedStudent';
  }

}
