<?php

/**
 * PsAlbums filter form base class.
 *
 * @package    KidsSchool.vn
 * @subpackage filter
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePsAlbumsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ps_customer_id'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'album_key'                     => new sfWidgetFormFilterInput(),
      'url_album'                     => new sfWidgetFormFilterInput(),
      'title'                         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_activated'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'note'                          => new sfWidgetFormFilterInput(),
      'number_push_activated'         => new sfWidgetFormFilterInput(),
      'number_view'                   => new sfWidgetFormFilterInput(),
      'number_like'                   => new sfWidgetFormFilterInput(),
      'number_dislike'                => new sfWidgetFormFilterInput(),
      'number_img'                    => new sfWidgetFormFilterInput(),
      'ps_class_id'                   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass'), 'add_empty' => true)),
      'ps_service_course_schedule_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsServiceCourseSchedules'), 'add_empty' => true)),
      'user_created_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'                    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'                    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'ps_customer_id'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'album_key'                     => new sfValidatorPass(array('required' => false)),
      'url_album'                     => new sfValidatorPass(array('required' => false)),
      'title'                         => new sfValidatorPass(array('required' => false)),
      'is_activated'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'note'                          => new sfValidatorPass(array('required' => false)),
      'number_push_activated'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'number_view'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'number_like'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'number_dislike'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'number_img'                    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ps_class_id'                   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('MyClass'), 'column' => 'id')),
      'ps_service_course_schedule_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('PsServiceCourseSchedules'), 'column' => 'id')),
      'user_created_id'               => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserCreated'), 'column' => 'id')),
      'user_updated_id'               => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserUpdated'), 'column' => 'id')),
      'created_at'                    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'                    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ps_albums_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsAlbums';
  }

  public function getFields()
  {
    return array(
      'id'                            => 'Number',
      'ps_customer_id'                => 'Number',
      'album_key'                     => 'Text',
      'url_album'                     => 'Text',
      'title'                         => 'Text',
      'is_activated'                  => 'Number',
      'note'                          => 'Text',
      'number_push_activated'         => 'Number',
      'number_view'                   => 'Number',
      'number_like'                   => 'Number',
      'number_dislike'                => 'Number',
      'number_img'                    => 'Number',
      'ps_class_id'                   => 'ForeignKey',
      'ps_service_course_schedule_id' => 'ForeignKey',
      'user_created_id'               => 'ForeignKey',
      'user_updated_id'               => 'ForeignKey',
      'created_at'                    => 'Date',
      'updated_at'                    => 'Date',
    );
  }
}
