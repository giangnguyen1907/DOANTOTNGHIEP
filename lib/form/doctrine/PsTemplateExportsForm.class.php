<?php

/**
 * PsTemplateExports form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsTemplateExportsForm extends BasePsTemplateExportsForm {

	public function configure() {

		/*
		 * $this->widgetSchema ['app_code'] = new sfWidgetFormChoice ( array (
		 * 'choices' => array (
		 * '' => _ ( '-Select-' )
		 * ) + Doctrine::getTable ( 'PsApp' )->getGroupPsApps ()
		 * ), array (
		 * 'class' => 'select2',
		 * 'style' => "min-width:200px;",
		 * 'data-placeholder' => _ ( '-Select-' )
		 * ) );
		 */
		$this->validatorSchema ['app_code'] = new sfValidatorRegex ( array (
				'required' => true,
				'max_length' => 100,
				'pattern' => '/^[a-zA-Z0-9_-]+$/' ), array (
				'required' => 'Required.',
				'max_length' => 'Maximum %max_length% characters',
				'invalid' => 'Invalid code (includes only the characters a-z A-Z 0-9 _ -)' ) );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
				'class' => 'radiobox' ) );

		$post_max_size = ( int ) sfConfig::get ( 'app_post_max_size' ); // KB
		$post_max_size = ( int ) ini_get ( 'post_max_size' ) > $post_max_size ? $post_max_size : ( int ) ini_get ( 'post_max_size' );

		$upload_max_size = ( int ) sfConfig::get ( 'app_upload_max_size' ); // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->path_image = sfConfig::get ( 'app_ps_data_dir' ) . '/template_export_img';

		if ($this->getObject ()->img_file) {

			$this->widgetSchema ['img_file'] = new psWidgetFormInputFileEditable ( array (
					'file_src' => '/media-web/export/img/' . $this->getObject ()->img_file,
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
					'class' => 'form-control btn btn-default btn-success btn-psadmin box_image' ) );
		} else {
			$this->widgetSchema ['img_file'] = new sfWidgetFormInputFile ();
			$this->widgetSchema ['img_file']->setAttributes ( array (
					'class' => 'form-control btn btn-default btn-success btn-psadmin' ) );
		}

		$this->validatorSchema ['img_file'] = new myValidatorFile ( array (
				'required' => false,
				// 'path' => sfConfig::get('app_ps_upload_dir'),
				'path' => $this->path_image,
				'mime_types' => 'web_images',
				'max_size' => $upload_max_size_byte,
				'validated_file_class' => 'sfValidatedFileCustom' ), array (
				'mime_types' => 'Image file must have format: jpg,png,gif.',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) );

		$this->validatorSchema->setMessage ( 'post_max_size', sfContext::getInstance ()->getI18n ()
			->__ ( 'You have uploaded a file that is too big.' ) . '(<=' . $post_max_size . 'MB)' );

		$this->widgetSchema ['name_file'] = new sfWidgetFormInputFile ( array (
				'label' => 'Excel file' ) );

		$this->validatorSchema ['name_file'] = new sfValidatorFile ( array (
				'required' => true,
				'path' => sfConfig::get ( 'app_ps_data_dir' ) . '/template_export' ) );

		$this->widgetSchema ['name_file']->setAttributes ( array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin' ) );

		$this->widgetSchema ['note']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 300 ) );

		$this->addBootstrapForm ();
	}
}
