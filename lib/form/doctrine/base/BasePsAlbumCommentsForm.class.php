<?php

/**
 * PsAlbumComments form base class.
 *
 * @method PsAlbumComments getObject() Returns the current form's model object
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePsAlbumCommentsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'album_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsAlbums'), 'add_empty' => true)),
      'album_item_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('PsAlbumItems'), 'add_empty' => true)),
      'content'         => new sfWidgetFormInputText(),
      'type'            => new sfWidgetFormInputCheckbox(),
      'is_activated'    => new sfWidgetFormInputCheckbox(),
      'number_like'     => new sfWidgetFormInputText(),
      'number_dislike'  => new sfWidgetFormInputText(),
      'user_created_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'add_empty' => true)),
      'user_updated_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'add_empty' => true)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'album_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsAlbums'), 'required' => false)),
      'album_item_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('PsAlbumItems'), 'required' => false)),
      'content'         => new sfValidatorString(array('max_length' => 255)),
      'type'            => new sfValidatorBoolean(array('required' => false)),
      'is_activated'    => new sfValidatorBoolean(array('required' => false)),
      'number_like'     => new sfValidatorInteger(array('required' => false)),
      'number_dislike'  => new sfValidatorInteger(array('required' => false)),
      'user_created_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserCreated'), 'required' => false)),
      'user_updated_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserUpdated'), 'required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ps_album_comments[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PsAlbumComments';
  }

}
