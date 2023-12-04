<?php

/**
 * PsAlbumItems form base class.
 *
 * @method PsAlbumItems getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsAlbumItemsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'album_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsAlbums'), 'add_empty' => false)),
      'title'           => new sfWidgetFormInputText(),
      'url_file'        => new sfWidgetFormTextarea(),
      'url_thumbnail'   => new sfWidgetFormTextarea(),
      'is_activated'    => new sfWidgetFormInputText(),
      'note'            => new sfWidgetFormTextarea(),
      'number_view'     => new sfWidgetFormInputText(),
      'number_like'     => new sfWidgetFormInputText(),
      'number_dislike'  => new sfWidgetFormInputText(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'album_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsAlbums'))),
      'title'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'url_file'        => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'url_thumbnail'   => new sfValidatorString(array('max_length' => 500, 'required' => false)),
      'is_activated'    => new sfValidatorInteger(array('required' => false)),
      'note'            => new sfValidatorString(array('max_length' => 300, 'required' => false)),
      'number_view'     => new sfValidatorInteger(array('required' => false)),
      'number_like'     => new sfValidatorInteger(array('required' => false)),
      'number_dislike'  => new sfValidatorInteger(array('required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_album_items[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsAlbumItems';
  }

}
