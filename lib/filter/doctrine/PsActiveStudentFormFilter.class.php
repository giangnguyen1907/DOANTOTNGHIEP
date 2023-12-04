<?php

/**
 * PsActiveStudent filter form.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsActiveStudentFormFilter extends BasePsActiveStudentFormFilter
{
  public function configure()
  {
    $this->widgetSchema ['ps_class_id'] = new sfWidgetFormDoctrineChoice ( array (
        'model' => 'MyClass',
        'add_empty' => '-Select class-',
    ), array(
      'class' => 'select2',
      'style' => "width:100%;min-width: 250px"
    ) );
    
    $this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice ( array (
      'required' => false,
      'model' => 'MyClass',
      'column' => 'id'
    ) );

    $this->widgetSchema ['start_at'] = new psWidgetFormFilterInputDate ();

    $this->validatorSchema ['start_at'] = new sfValidatorDate ( array (
        'required' => false,
    ), array (
        'invalid' => 'Invalid tracked at',
        'max' => 'Date must be no larger than %max%'
    ) );

    $this->widgetSchema ['start_at']->setAttributes ( array (
        'data-dateformat' => 'dd-mm-yyyy',
        'placeholder' => 'Từ ngày (dd-mm-yyyy)',
        'title' => 'Từ ngày (dd-mm-yyyy)',
        'required' => false
    ) );

    $this->widgetSchema ['end_at'] = new psWidgetFormFilterInputDate ();

    $this->validatorSchema ['end_at'] = new sfValidatorDate ( array (
        'required' => false,
    ), array (
        'invalid' => 'Invalid tracked at',
        'max' => 'Date must be no larger than %max%'
    ) );

    $this->widgetSchema ['end_at']->setAttributes ( array (
        'data-dateformat' => 'dd-mm-yyyy',
        'placeholder' => 'Đến ngày (dd-mm-yyyy)',
        'title' => 'Đến ngày (dd-mm-yyyy)',
        'required' => false
    ) );
  }
  public function addPsClassIdColumnQuery($query, $field, $value)
  {
    $a = $query->getRootAlias();

    $query->addWhere($a . '.ps_class_id = ?', $value);

    return $query;
  }
  public function addStartAtColumnQuery($query, $field, $value)
  {
    $a = $query->getRootAlias();

    $query->andWhere ( 'DATE_FORMAT('.$a. '.start_at,"%Y%m%d") >= ?', date ( 'Ymd', strtotime ( $value ) ) );

    return $query;
  }
  public function addEndAtColumnQuery($query, $field, $value)
  {
    $a = $query->getRootAlias();

    $query->andWhere ( 'DATE_FORMAT('.$a. '.end_at,"%Y%m%d") <= ?', date ( 'Ymd', strtotime ( $value ) ) );

    return $query;
  }
}
