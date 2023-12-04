<?php

/**
 * PsImages form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsImagesForm extends BasePsImagesForm {

	public function configure() {

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['file_group'] = new sfWidgetFormSelect ( array (
				'choices' => array (
						'' => '-Select group-' ) + PreSchool::loadPsFileGroup () ), array (
				'class' => 'form-control' ) );

		$this->widgetSchema ['title']->setAttributes ( array (
				'maxlength' => 255 ) );

		$this->widgetSchema ['description']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 300 ) );

		if ($this->getObject ()->file_name) {
			$this->widgetSchema ['file_name'] = new psWidgetFormInputFileEditable ( array (
					'file_src' => sfContext::getInstance ()->getRequest ()
						->getRelativeUrlRoot () . '/sys_icon/' . $this->getObject ()->file_name,
					'is_image' => true,
					'edit_mode' => ! $this->isNew (),
					'with_delete' => true,
					'delete_label' => 'Delete',
					'attributes_for_delete' => array (
							'class' => '' ),
					'attributes_for_file_src' => array (
							'class' => 'box_image  pull-left',
							'style' => 'max-width:70px;' ),
					'template' => '<div class="row"><div class="col-sm-12">%input%</div><div class="col-sm-12" style="margin-left:15px;">%delete% %delete_label% %file%</div></div>' ), array (
					'class' => 'form-control btn btn-default btn-success btn-psadmin' ) );
		} else {
			$this->widgetSchema ['file_name'] = new sfWidgetFormInputFile ();
			$this->widgetSchema ['file_name']->setAttribute ( 'class', 'form-control btn btn-default btn-success btn-psadmin' );
		}

		$post_max_size = ( int ) sfConfig::get ( 'app_post_max_size' ); // KB
		$post_max_size = ( int ) ini_get ( 'post_max_size' ) > $post_max_size ? $post_max_size : ( int ) ini_get ( 'post_max_size' );

		$upload_max_size = ( int ) sfConfig::get ( 'app_upload_max_size' ); // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->widgetSchema->setHelp ( 'file_name', sfContext::getInstance ()->getI18n ()
			->__ ( 'The image file must be in the format: jpg, png, gif. File size less than %value%KB.', array (
				'%value%' => $upload_max_size ) ) );

		$this->validatorSchema ['file_name'] = new myValidatorFile ( array (
				'required' => false,
				'path' => sfConfig::get ( 'app_ps_data_sys_icon_dir' ),
				'mime_types' => 'web_images',
				'max_size' => $upload_max_size_byte,
				'validated_file_class' => 'psValidatedFileCustom' ), 
		array (
				'mime_types' => 'The image file must be in the format: jpg, png, gif. File size less than 500KB.',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'File is too large maximum is' ) . ': ' . $upload_max_size . 'KB' ) );

		$this->validatorSchema->setMessage ( 'post_max_size', sfContext::getInstance ()->getI18n ()
			->__ ( 'You have uploaded a file that is too big.' ) . '(<=' . $post_max_size . 'MB)' );

		$this->validatorSchema ['image_delete'] = new sfValidatorBoolean ();

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}

	/**
	 * Save
	 */
	public function save($con = null) {

		$this->updateObject ();

		// Get the uploaded image
		$image = $this->getValue ( 'file_name' );
		if ($image) {
			$ext = $image->getExtension ( $image->getOriginalExtension () );
			$image->save ( 'icon_' . time () . date ( 'Ymdhisu' ) . $ext );
		}

		$object = parent::save ( $con );

		return $object;
	}
}
