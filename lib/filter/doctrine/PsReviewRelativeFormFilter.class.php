<?php

/**
 * PsReviewRelative filter form.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsReviewRelativeFormFilter extends BasePsReviewRelativeFormFilter
{
  public function configure()
  {
    $this->addPsCustomerFormFilter ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' );

    $ps_customer_id = $this->getDefault ( 'ps_customer_id' );
    // echo 'aaa'.$ps_customer_id;
    if ($ps_customer_id > 0) {

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

    $this->widgetSchema['status'] = new sfWidgetFormSelect(
      array (
        'choices' => array (
            '' => _ ( '-Select status-' )
            ) + PreSchool::loadPsActivity()
        ),
      array(
        'class' => 'select2',
        'style' => "min-width:150px;",
    ));

    $this->validatorSchema ['status'] = new sfValidatorString ( array (
        'required' => false ) );

    $this->widgetSchema['category_review_id'] = new sfWidgetFormDoctrineChoice(array(
          'model' => 'PsCategoryReview',
          'add_empty' => '-Chọn danh mục-'
        ), array(
          'class' => 'select2',
          'style' => "min-width:200px;",
          'data-placeholder' => _('-Chọn danh mục-')
        ));

    $this->validatorSchema['category_review_id'] = new sfValidatorDoctrineChoice(array(
      'model' => 'PsCategoryReview',
      'required' => false
    ));
  }
  public function addStatusColumnQuery($query, $field, $value)
  {
    $a = $query->getRootAlias();

    $query->addWhere($a . '.status = ?', $value);

    return $query;
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
  public function addCategoryReviewIdColumnQuery($query, $field, $value)
  {
    $a = $query->getRootAlias();

    $query->addWhere($a . '.category_review_id = ?', $value);

    return $query;
  }
}
