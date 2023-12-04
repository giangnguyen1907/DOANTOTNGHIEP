<?php

/**
 * PsPolicyGroup form.
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsPolicyGroupForm extends BasePsPolicyGroupForm
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

    $this->widgetSchema['level']->setAttributes(array(
        'class' => 'form-control',
        'type' => 'number',
        'min' => '0'
    ));

    $this->widgetSchema['discount']->setAttributes(array(
        'class' => 'form-control',
        'type' => 'number',
        'min' => '0'
    ));
    /*
    $this->widgetSchema['json_service'] = new sfWidgetFormSelect(array(
      'choices' => array(0,1,2,3,4,5,6,7),
    ),array(
      'class' => 'select2',
      'multiple' => true,
    ));
    
    $this->validatorSchema ['json_service'] = new sfValidatorChoice( array (
        'required' => false,
        'multiple' => true,
        'choices' => array(0,1,2,3,4,5,6,7),
    ) );
    */
    $this->validatorSchema['json_service'] = new sfValidatorPass();

    $this->addBootstrapForm ();
  }
  public function updateObject($values = null)
  {
      $object = parent::baseUpdateObject($values);

      $json_service = $this->getValue('json_service');

      $list_service = json_encode($json_service);
      
      $object->setJsonService($list_service);

      return $object;
  }
}
