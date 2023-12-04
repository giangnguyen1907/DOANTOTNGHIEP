<?php

/**
 * PsAlbumItems filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsAlbumItemsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'album_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsAlbums'), 'add_empty' => true)),
      'title'           => new sfWidgetFormFilterInput(),
      'url_file'        => new sfWidgetFormFilterInput(),
      'url_thumbnail'   => new sfWidgetFormFilterInput(),
      'is_activated'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'note'            => new sfWidgetFormFilterInput(),
      'number_view'     => new sfWidgetFormFilterInput(),
      'number_like'     => new sfWidgetFormFilterInput(),
      'number_dislike'  => new sfWidgetFormFilterInput(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'album_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsAlbums'), 'column' => 'id')),
      'title'           => new sfValidatorPass(array('required' => false)),
      'url_file'        => new sfValidatorPass(array('required' => false)),
      'url_thumbnail'   => new sfValidatorPass(array('required' => false)),
      'is_activated'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'note'            => new sfValidatorPass(array('required' => false)),
      'number_view'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'number_like'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'number_dislike'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_created_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ps_album_items_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsAlbumItems';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'album_id'        => 'ForeignKey',
      'title'           => 'Text',
      'url_file'        => 'Text',
      'url_thumbnail'   => 'Text',
      'is_activated'    => 'Number',
      'note'            => 'Text',
      'number_view'     => 'Number',
      'number_like'     => 'Number',
      'number_dislike'  => 'Number',
      'user_created_id' => 'ForeignKey',
      'user_updated_id' => 'ForeignKey',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
