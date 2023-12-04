<?php

/**
 * PsSymbol form.
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsSymbolForm extends BasePsSymbolForm
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
    

    $this->widgetSchema['service_id'] = new sfWidgetFormDoctrineChoice(array(
      'model' => 'Service',
      'add_empty' => '-Select service-'
    ), array(
      'class' => 'select2',
      'style' => "min-width:200px;",
      'data-placeholder' => _('-Select service-')
    ));

    $this->validatorSchema['service_id'] = new sfValidatorDoctrineChoice(array(
      'model' => 'Service',
      'required' => false
    ));

    $this->widgetSchema['is_type'] = new psWidgetFormSelectRadio(array(
        'choices' => PreSchool::loadPsBoolean()
    ), array(
        'class' => 'radiobox'
    ));
    /*
    $this->widgetSchema ['note'] = new sfWidgetFormTextarea ();

    $this->widgetSchema['note']->setAttributes(array(
        'class' => 'form-control',
        'rows' => 3
    ));
    */
    $this->addBootstrapForm ();
  }
  public function updateObject($values = null)
  {
      $object = parent::baseUpdateObject($values);

      return $object;
  }
  
}
