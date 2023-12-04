<?php

/**
 * PsReview filter form.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsReviewFormFilter extends BasePsReviewFormFilter
{
  public function configure()
  {
    $this->addPsCustomerFormFilter ( 'PS_STUDENT_OFF_SCHOOL_FILTER_SCHOOL', true );

    $ps_customer_id = $this->getDefault ( 'ps_customer_id' );

    if ($ps_customer_id == '') {
      $ps_customer_id = myUser::getPscustomerID ();
    }

    $this->setDefault ( 'ps_customer_id', $ps_customer_id );

    if ($ps_customer_id > 0) {
      // ps_workplace_id filter by ps_customer_id
      $this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
          'model' => 'PsWorkPlaces',
          'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
          'add_empty' => '-Select workplace-' ), array (
          'class' => 'select2',
          'style' => "min-width:200px;",
          'data-placeholder' => _ ( '-Select workplace-' ) ) );

      $this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
          'required' => true,
          'model' => 'PsWorkPlaces',
          'column' => 'id' ) );
    } else {

      $this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
          'choices' => array (
              '' => _ ( '-Select workplace-' ) ) ), array (
          'class' => 'select2',
          'style' => "min-width:200px;",
          'data-placeholder' => _ ( '-Select workplace-' ) ) );

      $this->validatorSchema ['ps_workplace_id'] = new sfValidatorPass ( array (
          'required' => true ) );
    }

    $school_year_id = $this->getDefault ( 'school_year_id' );

    if ($school_year_id == '') {
      $school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
        ->fetchOne ()
        ->getId ();
    }

    $this->setDefault ( 'school_year_id', $school_year_id );

    $this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
        'model' => 'PsSchoolYear',
        'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears () ), array (
        'class' => 'select2',
        'style' => "min-width:150px;",
        'data-placeholder' => _ ( '-Select school year-' ) ) );

    $this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
        'required' => true,
        'model' => 'PsSchoolYear',
        'column' => 'id' ) );

    $ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
    if ($ps_workplace_id == '') {
      $member_id = myUser::getUser ()->getMemberId ();
      $ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
    }

    $this->setDefault ( 'ps_workplace_id', $ps_workplace_id );

    $param_class = array (
        'ps_customer_id' => $ps_customer_id,
        'ps_workplace_id' => $ps_workplace_id,
        'ps_school_year_id' => $school_year_id,
        'is_activated' => PreSchool::ACTIVE
    );

    if ($ps_workplace_id > 0) {

      $this->widgetSchema ['ps_class_id'] = new sfWidgetFormDoctrineChoice ( array (
          'model' => 'MyClass',
          'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class ),
          'add_empty' => _ ( '-Select class-' ) ), array (
          'class' => 'select2',
          'style' => "min-width:150px;",
          'data-placeholder' => _ ( '-Select class-' ) ) );

      $this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice ( array (
          'required' => false,
          'model' => 'MyClass',
          'column' => 'id' ) );
    } else {
      $this->widgetSchema ['ps_class_id'] = new sfWidgetFormChoice ( array (
          'choices' => array (
              '' => _ ( '-Select class-' ) ) ), array (
          'class' => 'select2',
          'style' => "min-width:150px;",
          'data-placeholder' => _ ( '-Select class-' ) ) );

      $this->validatorSchema ['ps_class_id'] = new sfValidatorPass ( array (
          'required' => false ) );
    }

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
  }
  public function addPsClassIdColumnQuery($query, $field, $value)
  {
    $a = $query->getRootAlias();

    $query->addWhere($a . '.ps_class_id = ?', $value);

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
