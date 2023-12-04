<?php

/**
 * PsFoods form.
 *
 * @package quanlymamnon.vn
 * @subpackage form
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsFoodsForm extends BasePsFoodsForm {

	protected $path_image;
	
	protected $file_image;
	
	public function configure() {

		$this->widgetSchema['is_activated'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsActivity()
		), array(
				'class' => 'radiobox'
		));
		
		//$this->addPsCustomerFormEdit('PS_NUTRITION_FOOD_FILTER_SCHOOL');
		
		if ($this->getObject()->isNew()) { // Add new
			
			$is_select = true;
			
			if (! myUser::credentialPsCustomers('PS_NUTRITION_FOOD_FILTER_SCHOOL')) {
				
				$ps_customer_id = myUser::getPscustomerID();
				
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(null, $ps_customer_id)
				));
				
				$this->validatorSchema['ps_customer_id'] = new sfValidatorPass(array(
						'required' => true
				));
				$this->setDefault('ps_customer_id', $ps_customer_id);
			}
			
		} else {
			
			$ps_customer_id = $this->getObject()->getPsCustomerId();
			
			if($ps_customer_id > 0){
				if (myUser::credentialPsCustomers('PS_NUTRITION_FOOD_FILTER_SCHOOL')) {
					$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
							'model' => 'PsCustomer',
							'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(null, $ps_customer_id),
							'add_empty' => _('-Select customer-')
					));
				}else{
					$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
							'model' => 'PsCustomer',
							'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(null, $ps_customer_id)
					));
					$ps_customer_id = $this->getObject()->getPsCustomerId();
					
					$this->setDefault('ps_customer_id', $ps_customer_id);
					
				}
				$this->validatorSchema['ps_customer_id'] = new sfValidatorPass(array(
						'required' => false
				));
			}else{
				
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(1, null),
						'add_empty' => _('-Select customer-')
				));
				
				
			}
			
			/*
			//$ps_customer_id = $this->getObject()->getPsCustomerId();
			
			if (myUser::credentialPsCustomers('PS_NUTRITION_FOOD_FILTER_SCHOOL')) {
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(null, $ps_customer_id),
						'add_empty' => _('-Select customer-')
				));
				
			}else{
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(null, $ps_customer_id)
				));
				$ps_customer_id = $this->getObject()->getPsCustomerId();
				
				$this->setDefault('ps_customer_id', $ps_customer_id);
				
				$this->validatorSchema['ps_customer_id'] = new sfValidatorPass(array(
						'required' => false
				));
				
			}
			
			//$this->setDefault('ps_customer_id', $ps_customer_id);
			*/
		}
		
		$this->widgetSchema['ps_image_id'] = new psWidgetFormSelectImage(array(
				'choices' => array ( '' => "Select icon" ) + Doctrine::getTable('PsImages')->setChoisPsImagesByGroup(PreSchool::FILE_GROUP_FOODS),
		), array(
				'class' => 'select2',
				'style' => "width:100%",
				'placeholder' => _('-Select icon-')
		));
		
		$this->widgetSchema['note']->setAttributes(array(
				'maxlength' => 255
		));
		$this->widgetSchema['title']->setAttributes(array(
				'maxlength' => 255
		));
		
		
		$this->path_image = sfConfig::get ( 'sf_web_dir' ) . $this->getPath ();
		
		$post_max_size = ( int ) sfConfig::get ( 'app_post_max_size' ); // KB
		$post_max_size = ( int ) ini_get ( 'post_max_size' ) > $post_max_size ? $post_max_size : ( int ) ini_get ( 'post_max_size' );
		
		$upload_max_size = ( int ) sfConfig::get ( 'app_upload_max_size' ); // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes
		
		if ($this->getObject ()->file_image) {
			$this->widgetSchema ['file_image'] = new psWidgetFormInputFileEditable ( array (
					'file_src' => sfContext::getInstance ()->getRequest ()
					->getRelativeUrlRoot () . $this->getPath () . $this->getObject ()->file_image,
					'is_image' => true,
					'edit_mode' => ! $this->isNew (),
					'with_delete' => true,
					'delete_label' => 'Delete',
					'attributes_for_delete' => array (
							'class' => 'checkbox' ),
					// 'attributes_for_file_src' => array('class' => 'img-responsive class-img pull-left'),
					'attributes_for_file_src' => array (
							'class' => 'box_image  pull-left',
							'style' => 'max-width:150px;' ),
					'template' => '<div class="row"><div class="col-sm-12">%input%</div><div class="col-sm-12" style="margin-left:20px;">%delete% %delete_label% %file%</div></div>' ), array (
							'class' => 'form-control btn btn-default btn-success btn-psadmin' ) );
		} else {
			$this->widgetSchema ['file_image'] = new sfWidgetFormInputFile ();
			$this->widgetSchema ['file_image']->setAttribute ( 'class', 'form-control btn btn-default btn-success btn-psadmin' );
		}
		
		$this->widgetSchema ['file_image']->setLabel ( 'Foods image' );
		$this->widgetSchema->setHelp ( 'file_image', sfContext::getInstance ()->getI18n ()
				->__ ( 'The image file must be in the format: jpg, png, gif. File size less than %value%KB.', array (
						'%value%' => $upload_max_size ) ) );
				
				$this->validatorSchema ['file_image'] = new myValidatorFile ( array (
						'required' => false,
						'path' => $this->path_image,
						'mime_types' => 'web_images',
						'max_size' => $upload_max_size_byte,
						'validated_file_class' => 'psValidatedFileCustom' ), array (
								'mime_types' => 'The image file must be in the format: jpg, png, gif. File size less than 500KB.',
								'max_size' => sfContext::getInstance ()->getI18n ()
								->__ ( 'File is too large maximum is' ) . ': ' . $upload_max_size . 'KB' ) );
				
				$this->validatorSchema->setMessage ( 'post_max_size', sfContext::getInstance ()->getI18n ()
						->__ ( 'You have uploaded a file that is too big.' ) . '(<=' . $post_max_size . 'MB)' );
				
				$this->validatorSchema ['file_image_delete'] = new sfValidatorBoolean ();
				
		
		
		$this->addBootstrapForm();
		
		if (! myUser::credentialPsCustomers('PS_NUTRITION_FOOD_FILTER_SCHOOL' )) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control',
					'required' => false
			) );
		}

		$this->showUseFields ();
	}

	public function updateObject($values = null) {
		if ($this->getValue ( 'file_image_delete' )) {
			$this->removeDataFile ( $this->getObject ()->file_image );
		}
		return parent::baseUpdateObject ( $values );
	}

	// Renname file image upload
	public function save($con = null) {
		
		// Get the uploaded image
		$image = $this->getValue ( 'file_image' );
		
		$file_image = '';
		
		if ($image) {
			
			$ext = $image->getExtension ( $image->getOriginalExtension () );
			
			$file_image = 'r_' . time () . date ( 'Ymdhisu' ) . $ext;
			
			$image->save ( $file_image );
		}
		
		if ($file_image == '') {
			$file_image = $this->getObject ()
			->getFileImage ();
		}
		
		$sfFilesystem = new sfFilesystem ();
		
		$path_dir = $this->path_image;
		
		if ($sfFilesystem->mkdirs ( $path_dir . 'thumb', 0755 ) && $file_image != '' && is_file ( $path_dir . $file_image )) {
			
			$thumbnail = new sfImage ( $path_dir . $file_image );
			$thumbnail->thumbnail ( 250, 250 );
			$thumbnail->setQuality ( 100 );
			
			$thumbnail->saveAs ( $path_dir . 'thumb/' . $file_image );
		}
		
		return parent::save ( $con );
	}
	protected function removeFields() {
		
		if ($this->getObject ()->isNew ()) {
			
			unset ( $this ['created_at'], $this ['updated_at'], $this ['user_created_id'], $this ['user_updated_id'] );
			
		} else {
			unset ( $this ['created_at'], $this ['updated_at'], $this ['user_created_id'], $this ['user_updated_id'] );
		}
	}
	
	protected function removeDataFile($file_image) {
		
		if (is_file ( $this->path_image . '/' . $file_image )) {
			unlink ( $this->path_image . '/' . $file_image );
		}
		
		if (is_file ( $this->path_image . '/thumb/' . $file_image )) {
			unlink ( $this->path_image . '/thumb/' . $file_image );
		}
	}
	
	protected function getPath() {
		
		/**
		 * Cấu trúc đường dẫn file ảnh:
		 *
		 * '/uploads/cms_articles/yyyy/mm/dd';
		 */
		if ($this->isNew ()) {
			return '/uploads/ps_nutrition/';
		} else {
			return ('/uploads/ps_nutrition/');
		}
	}
	
	protected function getFile() {
		
		$fileName = $this->getObject () ->getFileImage ();
		return $fileName;
	}
	protected function showUseFields() {

		$this->useFields ( array (
				'ps_customer_id',
				'title',
				'note',
				'ps_image_id',
				'iorder',
				'file_image',
				'is_activated' ) );
	}
}
