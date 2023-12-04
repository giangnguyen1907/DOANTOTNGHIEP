<?php

/**
 * PsAlbum form.
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsAlbumForm extends BasePsAlbumForm
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

    $this->widgetSchema ['media'] = new sfWidgetFormTextarea ();

    $this->widgetSchema['media']->setAttributes(array(
        'class' => 'form-control',
        'rows' => 3
    ));

    $this->widgetSchema ['title'] = new sfWidgetFormInput ();

    $this->widgetSchema['title']->setAttributes(array(
        'class' => 'form-control',
    ));

    $this->widgetSchema['total_like']->setAttributes(array(
        'class' => 'form-control',
        'type' => 'number',
        'min' => '0',
        'readonly' => true
    ));

    $this->widgetSchema['total_comment']->setAttributes(array(
        'class' => 'form-control',
        'type' => 'number',
        'min' => '0',
        'readonly' => true
    ));

    $this->widgetSchema['status'] = new psWidgetFormSelectRadio(array(
        'choices' => PreSchool::loadPsActivity()
    ), array(
        'class' => 'radiobox'
    ));

    $this->widgetSchema['member_id'] = new sfWidgetFormDoctrineChoice(array(
          'model' => 'PsMember',
          'query' => Doctrine::getTable('PsMember')->setSQLByMember(),
          'add_empty' => '-Chọn giáo viên-'
        ), array(
          'class' => 'select2',
          'style' => "min-width:200px;",
          'data-placeholder' => _('-Chọn giáo viên-')
        ));

    $this->validatorSchema['member_id'] = new sfValidatorDoctrineChoice(array(
      'model' => 'PsMember',
      'required' => false
    ));

    $this->widgetSchema['class_id'] = new sfWidgetFormDoctrineChoice(array(
          'model' => 'MyClass',
          // 'query' => Doctrine::getTable('PsMember')->setSQLByMember(),
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

    $this->addBootstrapForm ();

    $this->validatorSchema['media'] = new sfValidatorPass();

  }

  public function updateObject($values = null)
  {
      $object = parent::baseUpdateObject($values);

      $media = $this->getValue('media');

      $list_media = implode(';',$media);

      $object->setMedia($list_media);

      return $object;
  }
}
