<?php

/**
 * PsRegularity form.
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsRegularityForm extends BasePsRegularityForm
{
  public function configure()
  {
    $this->addPsCustomerFormNotEdit ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' );

    $this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
        'model' => 'PsCustomer',
        'required' => true ) );

    $ps_customer_id = $this->getDefault ( 'ps_customer_id' );

    if ($ps_customer_id <= 0) {

      $ps_customer_id = $this->getObject ()
        ->getPsCustomerId ();
    }

    $workplace_query = Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id );

    if ($ps_customer_id > 0) {
      $this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
          'model' => "PsWorkplaces",
          'query' => $workplace_query,
          'add_empty' => _ ( '-Select workplace-' ) ) );
    } else {
      $this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormSelect ( array (
          'choices' => array (
              '' => _ ( '-Select workplace-' ) ) ), array (
          'class' => 'select2' ) );
    }

    $this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
        'required' => true,
        'model' => 'PsWorkplaces',
        'column' => 'id' ) );
    
    $this->widgetSchema['is_type'] = new psWidgetFormSelectRadio(array(
        'choices' => PreSchool::loadPsGiamtru()
    ), array(
        'class' => 'radiobox'
    ));
    $this->widgetSchema['discount']->setAttributes(array(
        'class' => 'form-control',
        'type' => 'number',
        'min' => '0'
    ));
    $this->widgetSchema['number']->setAttributes(array(
        'class' => 'form-control',
        'type' => 'number',
        'min' => '1',
        'max' => '12',
        'placeholder' => 'Từ 1 đến 12 tháng'
    ));
    $this->widgetSchema['level']->setAttributes(array(
        'class' => 'form-control',
        'type' => 'number',
        'min' => '0'
    ));
    $this->widgetSchema['is_default'] = new psWidgetFormSelectRadio(array(
        'choices' => PreSchool::loadPsServiceDefault()
    ), array(
        'class' => 'radiobox'
    ));
    $this->addBootstrapForm ();
  }
  public function updateObject($values = null)
  {
      $object = parent::baseUpdateObject($values);

      return $object;
  }
}
