<?php

/**
 * PsSymbolGroup filter form.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsSymbolGroupFormFilter extends BasePsSymbolGroupFormFilter
{
  public function configure()
  {
    $this->addPsCustomerFormFilter ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' );

    $ps_customer_id = $this->getDefault ( 'ps_customer_id' );

    if ($ps_customer_id > 0) {
      // ps_workplace_id filter by ps_customer_id
      $this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
          'model' => 'PsWorkPlaces',
          'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
          'add_empty' => '-Select workplace-' ), array (
          'class' => 'select2',
          'style' => "min-width:200px;",
          'data-placeholder' => _ ( '-Select workplace-' ) ) );
    } else {

      $this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
          'choices' => array (
              '' => _ ( '-Select workplace-' ) ) ), array (
          'class' => 'select2',
          'style' => "min-width:200px;",
          'data-placeholder' => _ ( '-Select workplace-' ) ) );
    }

    $this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
        'required' => false,
        'model' => 'PsWorkPlaces',
        'column' => 'id' ) );

    $ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

    $this->widgetSchema ['is_type'] = new sfWidgetFormSelect ( 
        array (
        'choices' => array (
            '' => _ ( '-Select type-' )
            ) + PreSchool::loadPsSymGroup ()
        ),
        array (
        'class' => 'select2',
        'style' => "min-width:200px;",
        'required' => false ) );

    $this->validatorSchema ['is_type'] = new sfValidatorChoice ( array (
        'choices' => array_keys ( PreSchool::loadPsSymGroup () ),
        'required' => false ) );

    $this->widgetSchema ['is_payment'] = new sfWidgetFormSelect ( 
        array (
        'choices' => array (
            '' => _ ( '-Select payment-' )
            ) + PreSchool::loadPsBoolean ()
        ),
        array (
        'class' => 'select2',
        'style' => "min-width:200px;",
        'required' => false ) );

    $this->validatorSchema ['is_payment'] = new sfValidatorChoice ( array (
        'choices' => array_keys ( PreSchool::loadPsBoolean () ),
        'required' => false ) );
  }
  public function addPsWorkplaceIdColumnQuery($query, $field, $value)
  {
    $a = $query->getRootAlias();

    $query->addWhere($a . '.ps_workplace_id = ?', $value);

    return $query;
  }
  public function addPsCustomerIdColumnQuery($query, $field, $value)
  {
    $a = $query->getRootAlias();

    $query->addWhere($a . '.ps_customer_id = ?', $value);

    return $query;
  }
}
