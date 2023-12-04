<?php

/**
 * PsMenusImports form.
 *
 * @package    KidsSchool.vn
 * @subpackage form
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsMenusImportsForm extends BasePsMenusImportsForm
{
    
    protected $path_image;
    
    protected $file_image;
    
	public function configure() {
		/*
		$this->addPsCustomerFormNotEdit ( 'PS_NUTRITION_MENUS_FILTER_SCHOOL' );
		
		$ps_customer_id = $this->getObject ()->getPsCustomerId ();
		
		$workplace_query = Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id );
		
		if ($ps_customer_id > 0) {
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => "PsWorkplaces",
					'query' => $workplace_query,
			    'add_empty' => _ ( '-Select workplace-' ) ), array (
			        'class' => 'select2 form-control',
			        'style' => "min-width:200px;",
			        'data-placeholder' => _ ( '-Select workplace-' ) ));
		} else {
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
									'class' => 'select2 form-control' ) );
		}
		
		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkplaces',
		        'column' => 'id' ) );
		
		$this->widgetSchema ['date_at'] = new psWidgetFormInputDate ();
		
		$this->widgetSchema ['date_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );
		
		$this->widgetSchema ['date_at']->addOption ( 'add-class', 'id_datepicker' );
		
		$ps_workplace_id = $this->getObject ()->getPsWorkplaceId ();
		
		if ($ps_customer_id > 0) {
			
			$params = array (
					'ps_customer_id' => $ps_customer_id,
					'ps_workplace_id' => $ps_workplace_id );
			
			$this->widgetSchema ['ps_meal_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsMoods',
					'query' => Doctrine::getTable ( 'PsMeals' )->setSQLByParams ( $params ),
					'add_empty' => _ ( '-Select meal-' ) ), array (
							'class' => 'select2 form-control',
							'style' => "min-width:200px;",
							'data-placeholder' => _ ( '-Select meal-' ) ) );
			
			$this->widgetSchema ['ps_meal_id']->setLabel ( 'Meals' );
			
			$this->validatorSchema ['ps_meal_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => $this->getRelatedModelName ( 'PsMeals' ),
					'required' => true ) );
			
		} else {
			
			$this->widgetSchema ['ps_meal_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select meal-' ) ) ), array (
									'class' => 'select2 form-control',
									'style' => "min-width:200px;",
									'data-placeholder' => _ ( '-Select meal-' ) ) );
		}
		
		$this->widgetSchema ['ps_object_group_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsObjectGroups',
				'query' => Doctrine::getTable ( 'PsObjectGroups' )->setSQL ( $this->getObject ()
						->isNew () ? PreSchool::ACTIVE : null ),
				'add_empty' => false // _ ( '-Select group-' )
		), array (
				'class' => 'select2 form-control',
				'style' => "min-width:200px;",
				'data-placeholder' => _ ( '-Select group-' ),
				'required' => true ) );
		
		$this->validatorSchema ['ps_object_group_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => $this->getRelatedModelName ( 'PsObjectGroups' ),
				'required' => true ) );
		
		$this->widgetSchema ['description']->setAttributes ( array (
				'maxlength' => 2000,
				'class' => 'form-control' ) );
		
		*/
	    
	    $this->widgetSchema ['description']->setAttributes ( array (
	        'maxlength' => 2000,
	        'rows'=> 4,
	        'class' => 'form-control' ) );
	    
		$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['ps_meal_id'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['date_at'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['ps_object_group_id'] = new sfWidgetFormInputHidden ();
		
		// $this->widgetSchema['ps_image_id'] = new psWidgetFormSelectImage(array(
		    // 'choices' => array ( '' => "Select icon" ) + Doctrine::getTable('PsImages')->setChoisPsImagesByGroup(PreSchool::FILE_GROUP_FOODS),
		// ), array(
		    // 'class' => 'select2',
		    // 'style' => "width:100%",
		    // 'placeholder' => _('-Select icon-')
		// ));
		
		/*-----------
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
		    ----------*/
		    
		/*
		$this->path_image = sfConfig::get ( 'app_ps_data_dir' ) . '/PSCHOOL/profile/';
		$file_src = 'PSCHOOL/profile/';
		
		$post_max_size = ( int ) sfConfig::get ( 'app_post_max_size' ); // KB
		$post_max_size = ( int ) ini_get ( 'post_max_size' ) > $post_max_size ? $post_max_size : ( int ) ini_get ( 'post_max_size' );
		
		$upload_max_size = ( int ) sfConfig::get ( 'app_upload_max_size' ); // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes
		
		if ($this->getObject ()->image) {
		    $this->widgetSchema ['image'] = new psWidgetFormInputFileEditable ( array (
		        // 'file_src' => sfContext::getInstance()->getRequest()->getRelativeUrlRoot() . '/pschool/' . $file_src . '/' . $this->getObject()->image,
		        'file_src' => '/media-web/root/' . $file_src . '/' . $this->getObject ()->image,
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
		    $this->widgetSchema ['image'] = new sfWidgetFormInputFile ();
		    $this->widgetSchema ['image']->setAttribute ( 'class', 'form-control btn btn-default btn-success btn-psadmin' );
		}
		
		$this->widgetSchema ['image']->setLabel ( 'File image' );
		$this->widgetSchema->setHelp ( 'image', sfContext::getInstance ()->getI18n ()
		    ->__ ( 'The image file must be in the format: jpg, png, gif. File size less than %value%KB.', array (
		        '%value%' => $upload_max_size ) ) );
		    
		    $this->validatorSchema ['image'] = new myValidatorFile ( array (
		        'required' => false,
		        'path' => $this->path_image,
		        'mime_types' => 'web_images',
		        'max_size' => $upload_max_size_byte,
		        'validated_file_class' => 'sfValidatedFileCustom' ), array (
		            'mime_types' => 'The image file must be in the format: jpg, png, gif. File size less than 500KB.',
		            'max_size' => sfContext::getInstance ()->getI18n ()
		            ->__ ( 'File is too large maximum is' ) . ': ' . $upload_max_size . 'KB' ) );
		    
		    $this->validatorSchema->setMessage ( 'post_max_size', sfContext::getInstance ()->getI18n ()
		        ->__ ( 'You have uploaded a file that is too big.' ) . '(<=' . $post_max_size . 'MB)' );
		    
		    $this->validatorSchema ['image_delete'] = new sfValidatorBoolean ();
		    */
			
		$url_toolfile = sfConfig::get('app_admin_module_web_dir').'/kstools/browse.php?type=image';
      
		$this->widgetSchema ['file_image'] = new sfWidgetFormInputText ();
			
		$this->widgetSchema ['file_image']->setAttributes ( array (
			'class' => 'form-control',
			'onclick'=> 'openLoadImages(this,"'.$url_toolfile.'")',
			'placeholder' => "Chọn hình ảnh"
		) );
		
		$this->validatorSchema ['file_image'] = new sfValidatorString ( array (
			'required' => false
		) );
		$this->showUseFields ();
		
		$this->addBootstrapForm ();
		
	}
	
	public function updateObject($values = null) {
	    // if ($this->getValue ( 'file_image_delete' )) {
	        // $this->removeDataFile ( $this->getObject ()->file_image );
	    // }
	    return parent::baseUpdateObject ( $values );
	}
	
	// Renname file image upload
	// public function save($con = null) {
	    
	    // // Get the uploaded image
	    // $image = $this->getValue ( 'file_image' );
	    
	    // $file_image = '';
	    
	    // if ($image) {
	        
	        // $ext = $image->getExtension ( $image->getOriginalExtension () );
	        
	        // $file_image = 'r_' . time () . date ( 'Ymdhisu' ) . $ext;
	        
	        // $image->save ( $file_image );
	    // }
	    
	    // if ($file_image == '') {
	        // $file_image = $this->getObject ()
	        // ->getFileImage ();
	    // }
	    
	    // $sfFilesystem = new sfFilesystem ();
	    
	    // $path_dir = $this->path_image;
	    
	    // if ($sfFilesystem->mkdirs ( $path_dir . 'thumb', 0755 ) && $file_image != '' && is_file ( $path_dir . $file_image )) {
	        
	        // $thumbnail = new sfImage ( $path_dir . $file_image );
	        // $thumbnail->thumbnail ( 250, 250 );
	        // $thumbnail->setQuality ( 100 );
	        
	        // $thumbnail->saveAs ( $path_dir . 'thumb/' . $file_image );
	    // }
	    
	    // return parent::save ( $con );
	// }
	
	// protected function removeDataFile($file_image) {
	    
	    // if (is_file ( $this->path_image . '/' . $file_image )) {
	        // unlink ( $this->path_image . '/' . $file_image );
	    // }
	    
	    // if (is_file ( $this->path_image . '/thumb/' . $file_image )) {
	        // unlink ( $this->path_image . '/thumb/' . $file_image );
	    // }
	// }
	
	// protected function getPath() {
	    
	    // /**
	     // * Cấu trúc đường dẫn file ảnh:
	     // *
	     // * '/uploads/cms_articles/yyyy/mm/dd';
	     // */
	    // if ($this->isNew ()) {
	        // return '/uploads/ps_nutrition/';
	    // } else {
	        // return ('/uploads/ps_nutrition/');
	    // }
	// }
	
	// protected function getFile() {
	    
	    // $fileName = $this->getObject () ->getFileImage ();
	    // return $fileName;
	// }
	protected function showUseFields() {
		
		$this->useFields ( array (
				'ps_customer_id',
				'ps_workplace_id',
				'ps_meal_id',
				'date_at',
				'ps_object_group_id',
				'description',
		        // 'ps_image_id',
		        'file_image'
		) );
	}
	
}
