<?php

/**
 * PsActiveStudent form.
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsActiveStudentForm extends BasePsActiveStudentForm
{
  public function configure()
  {
    $this->widgetSchema['start_at'] = new psWidgetFormInputDate();
    $this->widgetSchema['start_at']->setAttributes(array(
      'data-dateformat' => 'dd-mm-yyyy',
      'placeholder' => 'dd-mm-yyyy',
      'class' => 'datepicker',
      'style' =>'z-index:2 !important'
    ));

    $this->widgetSchema['end_at'] = new psWidgetFormInputDate();
    $this->widgetSchema['end_at']->setAttributes(array(
      'data-dateformat' => 'dd-mm-yyyy',
      'placeholder' => 'dd-mm-yyyy',
      'class' => 'datepicker',
      'style' =>'z-index:2 !important'
    ));
    $this->widgetSchema['start_time'] = new sfWidgetFormInputText();
    $this->widgetSchema['start_time']->setAttributes(array(
        'placeholder' => '00:00'
    ));

    $this->widgetSchema['end_time'] = new sfWidgetFormInputText();
    $this->widgetSchema['end_time']->setAttributes(array(
        'placeholder' => '00:00'
    ));
    
    $this->widgetSchema ['note'] = new sfWidgetFormTextarea ();

    $this->widgetSchema['note']->setAttributes(array(
        'class' => 'form-control',
        'rows'  => 4
    ));
    $this->addBootstrapForm ();
  }
  public function updateObject($values = null) {

    $object = parent::baseUpdateObject ( $values );

    return $object;
  }
}
