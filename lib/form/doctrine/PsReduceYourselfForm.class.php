<?php

/**
 * PsReduceYourself form.
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsReduceYourselfForm extends BasePsReduceYourselfForm
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

    $this->widgetSchema['start']->setAttributes(array(
        'class' => 'form-control',
        'type' => 'number',
    ));

    $this->widgetSchema['stop']->setAttributes(array(
        'class' => 'form-control',
        'type' => 'number',
    ));

    // $this->widgetSchema['discount']->setAttributes(array(
        // 'class' => 'form-control',
        // 'type' => 'number',
        // 'min' => '0'
    // ));

    $this->widgetSchema['level']->setAttributes(array(
        'class' => 'form-control',
        'type' => 'number',
        'min' => '0'
    ));

    $this->widgetSchema ['status'] = new sfWidgetFormSelect ( array (
        'choices' => PreSchool::loadPsReduceStatus () ), array (
        'class' => 'form-control',
        'required' => true ) );

    $this->validatorSchema ['status'] = new sfValidatorChoice ( array (
        'choices' => array_keys ( PreSchool::loadPsReduceStatus () ),
        'required' => false ) );


	$this->widgetSchema['discount'] = new psWidgetFormSelectRadio(array(
        'choices' => PreSchool::loadPsBoolean()
    ), array(
        'class' => 'radiobox'
    ));
	
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
