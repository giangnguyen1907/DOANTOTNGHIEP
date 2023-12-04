<?php

/**
 * PsBabyGift form.
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsBabyGiftForm extends BasePsBabyGiftForm
{
  public function configure()
  {
    $this->widgetSchema ['title'] = new sfWidgetFormInputText ();

    $this->widgetSchema['title']->setAttributes(array(
        'class' => 'form-control',
    ));

    $this->widgetSchema ['brief'] = new sfWidgetFormTextarea ();

    $this->widgetSchema['brief']->setAttributes(array(
        'class' => 'form-control',
        'rows' => 5
    ));

    $this->widgetSchema['status'] = new psWidgetFormSelectRadio(array(
        'choices' => PreSchool::loadPsActivity()
    ), array(
        'class' => 'radiobox'
    ));

    $this->widgetSchema['date_at'] = new psWidgetFormInputDate();
    $this->widgetSchema['date_at']->setAttributes(array(
      'data-dateformat' => 'dd-mm-yyyy',
      'placeholder' => 'dd-mm-yyyy',
      'class' => 'datepicker',
      'style' =>'z-index:2 !important'
    ));

    $this->widgetSchema ['content'] = new sfWidgetFormTextarea ( array (), array (
        'class' => 'form-control','rows' => 10 ) );
    
    $this->validatorSchema ['content'] = new sfValidatorString ( array (
        'required' => false ) );

    $url_toolfile = sfConfig::get('app_admin_module_web_dir').'/kstools/browse.php?type=image';
      
    $this->widgetSchema ['image'] = new sfWidgetFormInputText ();
      
    $this->widgetSchema ['image']->setAttributes ( array (
      'class' => 'form-control',
      'onclick'=> 'openLoadImages(this,"'.$url_toolfile.'")',
      'placeholder' => "Chọn hình ảnh"
    ) );

    $this->addBootstrapForm ();
  }
  public function updateObject($values = null)
  {
      $object = parent::baseUpdateObject($values);

      return $object;
  }
}
