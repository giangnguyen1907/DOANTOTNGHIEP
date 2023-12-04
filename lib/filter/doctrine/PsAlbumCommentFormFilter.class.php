<?php

/**
 * PsAlbumComment filter form.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsAlbumCommentFormFilter extends BasePsAlbumCommentFormFilter
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

    $this->widgetSchema['album_id'] = new sfWidgetFormDoctrineChoice(array(
          'model' => 'PsAlbum',
          'add_empty' => '-Chọn album-'
        ), array(
          'class' => 'select2',
          'style' => "min-width:200px;",
          'data-placeholder' => _('-Chọn album-')
        ));

    $this->validatorSchema['album_id'] = new sfValidatorDoctrineChoice(array(
      'model' => 'PsAlbum',
      'required' => false
    ));

    $this->widgetSchema['relative_id'] = new sfWidgetFormDoctrineChoice(array(
          'model' => 'Relative',
          'query' => Doctrine::getTable('Relative')->getListRelative(),
          'add_empty' => '-Chọn phụ huynh-'
        ), array(
          'class' => 'select2',
          'style' => "min-width:200px;",
          'data-placeholder' => _('-Chọn phụ huynh-')
        ));

    $this->validatorSchema['relative_id'] = new sfValidatorDoctrineChoice(array(
      'model' => 'Relative',
      'required' => false
    ));
  }
  public function addRelativeIdColumnQuery($query, $field, $value)
  {
    $a = $query->getRootAlias();

    $query->addWhere($a . '.relative_id = ?', $value);

    return $query;
  }
  public function addAlbumIdColumnQuery($query, $field, $value)
  {
    $a = $query->getRootAlias();

    $query->addWhere($a . '.album_id = ?', $value);

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
