<?php

/**
 * PsAlbum filter form.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsAlbumFormFilter extends BasePsAlbumFormFilter
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

    $this->widgetSchema['class_id'] = new sfWidgetFormDoctrineChoice(array(
          'model' => 'MyClass',
          'add_empty' => '-Select class-'
        ), array(
          'class' => 'select2',
          'style' => "min-width:200px;",
          'data-placeholder' => _('-Select class-')
        ));

    $this->validatorSchema['class_id'] = new sfValidatorDoctrineChoice(array(
      'model' => 'MyClass',
      'required' => false
    ));

    $this->widgetSchema['member_id'] = new sfWidgetFormDoctrineChoice(array(
          'model' => 'PsMember',
          'query' => Doctrine::getTable('PsMember')->setSQLByMember(),
          'add_empty' => '-Select member-'
        ), array(
          'class' => 'select2',
          'style' => "min-width:200px;",
          'data-placeholder' => _('-Select member-')
        ));

    $this->validatorSchema['member_id'] = new sfValidatorDoctrineChoice(array(
      'model' => 'PsMember',
      'required' => false
    ));

  }
  public function addMemberIdColumnQuery($query, $field, $value)
  {
    $a = $query->getRootAlias();

    $query->addWhere($a . '.member_id = ?', $value);

    return $query;
  }

  public function addClassIdColumnQuery($query, $field, $value)
  {
    $a = $query->getRootAlias();

    $query->addWhere($a . '.class_id = ?', $value);

    return $query;
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
}
