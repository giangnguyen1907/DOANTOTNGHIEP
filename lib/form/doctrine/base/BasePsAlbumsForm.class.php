<?php

/**
 * PsAlbums form base class.
 *
 * @method PsAlbums getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsAlbumsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                            => new sfWidgetFormInputHidden(),
      'ps_customer_id'                => new sfWidgetFormInputText(),
      'album_key'                     => new sfWidgetFormInputText(),
      'url_album'                     => new sfWidgetFormTextarea(),
      'title'                         => new sfWidgetFormInputText(),
      'is_activated'                  => new sfWidgetFormInputText(),
      'note'                          => new sfWidgetFormTextarea(),
      'number_push_activated'         => new sfWidgetFormInputText(),
      'number_view'                   => new sfWidgetFormInputText(),
      'number_like'                   => new sfWidgetFormInputText(),
      'number_dislike'                => new sfWidgetFormInputText(),
      'number_img'                    => new sfWidgetFormInputText(),
      'ps_class_id'                   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass'), 'add_empty' => true)),
      'ps_service_course_schedule_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsServiceCourseSchedules'), 'add_empty' => true)),
      'user_created_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'                    => new sfWidgetFormDateTime(),
      'updated_at'                    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'ps_customer_id'                => new sfValidatorInteger(),
      'album_key'                     => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'url_album'                     => new sfValidatorString(array('required' => false)),
      'title'                         => new sfValidatorString(array('max_length' => 255)),
      'is_activated'                  => new sfValidatorInteger(array('required' => false)),
      'note'                          => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'number_push_activated'         => new sfValidatorInteger(array('required' => false)),
      'number_view'                   => new sfValidatorInteger(array('required' => false)),
      'number_like'                   => new sfValidatorInteger(array('required' => false)),
      'number_dislike'                => new sfValidatorInteger(array('required' => false)),
      'number_img'                    => new sfValidatorInteger(array('required' => false)),
      'ps_class_id'                   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('MyClass'), 'required' => false)),
      'ps_service_course_schedule_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsServiceCourseSchedules'), 'required' => false)),
      'user_created_id'               => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id'               => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'                    => new sfValidatorDateTime(),
      'updated_at'                    => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'PsAlbums', 'column' => array('album_key')))
    );

    $this->widgetSchema->setNameFormat('ps_albums[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsAlbums';
  }

}
