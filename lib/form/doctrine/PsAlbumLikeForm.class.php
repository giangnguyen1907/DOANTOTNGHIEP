<?php

/**
 * PsAlbumLike form.
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsAlbumLikeForm extends BasePsAlbumLikeForm
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

    $this->widgetSchema['album_id'] = new sfWidgetFormDoctrineChoice(array(
          'model' => 'PsAlbum',
          // 'query' => Doctrine::getTable('PsMember')->setSQLByMember(),
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

    $this->widgetSchema['number_like']->setAttributes(array(
        'class' => 'form-control',
        'type' => 'number',
        'min' => '1',
        'readonly' => true
    ));

    $this->addBootstrapForm ();
  }
  public function updateObject($values = null)
  {
      $object = parent::baseUpdateObject($values);

      return $object;
  }
}
