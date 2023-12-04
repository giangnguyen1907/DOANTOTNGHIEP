<?php

/**
 * PsCmsArticles form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsCmsArticlesForm extends BasePsCmsArticlesForm {

	protected $path_image;

	protected $file_name;

	public function configure() {
		
		//$this->addPsCustomerFormNotEdit ( 'PS_CMS_ARTICLES_FILTER_SCHOOL' );
		
		$this->setPsCustomerFormHidden();
		
		$ps_customer_id = $this->getDefault('ps_customer_id');
		$ps_workplace_id = $this->getDefault('ps_workplace_id');
		$ps_class_ids = array();
		
		if ($this->getObject()->isNew()) {
			
			$this->addPsWorkplaceIdForm ( $ps_customer_id, null, true );
			$ps_workplace_id = $this->getDefault('ps_workplace_id');
			
		}else{
			
			$ps_customer_id = $this->getObject()->getPsCustomerId();
			$ps_workplace_id = $this->getObject()->getPsWorkplaceId();
			$articles_id = $this->getObject()->getId();
			
			$psCmsArticlesClass = Doctrine::getTable('PsCmsArticlesClass')->checkArticleClassById($articles_id);
			foreach ($psCmsArticlesClass as $articles_class){
				array_push($ps_class_ids,$articles_class->getPsClassId());
			}
		}
		
		$workplace_query = Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id );
		
		if ($ps_customer_id > 0) {
			
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => "PsWorkplaces",
					'query' => $workplace_query,
					'add_empty' => _ ( '-Select basis enrollment-' ) ) );
		} else {
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
									'class' => 'select2' ) );
		}
		
		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkplaces',
				'query' => $workplace_query,
				'column' => 'id' ) );
		
		$school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId ();
		$param_class = array(
				'ps_school_year_id' => $school_year_id,
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'is_activated'=> PreSchool::ACTIVE
		);
		
		$this->widgetSchema ['school_year_id'] = new sfWidgetFormInputHidden ();
		$this->validatorSchema ['school_year_id'] = new sfValidatorString ( array (
				'required' => true ) );
		
		$this->setDefault ( 'school_year_id', $school_year_id );
		
		if($ps_workplace_id > 0){
			
			$this->widgetSchema['ps_class_ids'] = new sfWidgetFormDoctrineChoice(array(
					'model' => 'MyClass',
					'query' => Doctrine::getTable('MyClass')->setClassByParams($param_class),
					'multiple' => true
			), array(
					'required' => false,
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _('-Select class-')
			));
			
			$this->validatorSchema ['ps_class_ids'] = new sfValidatorDoctrineChoice( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable('MyClass')->setClassByParams($param_class),
					'required' => false,
					'multiple' => true,
					
			) );
		}else{
			
			$this->widgetSchema ['ps_class_ids'] = new sfWidgetFormChoice ( array (
					'choices' => array (),'multiple' => true ), array (
									'class' => 'select2',
									'style' => "min-width:200px;",
									'required' => false,
									'data-placeholder' => _ ( '-Select class-' ) ) );
			
			$this->validatorSchema ['ps_class_ids'] = new sfValidatorPass (array('required' => false));
		}
		
		$this->setDefault ( 'ps_class_ids', $ps_class_ids );
		
		$this->widgetSchema ['ps_class_ids']->setLabel('Class');
		
		$this->widgetSchema ['note'] = new sfWidgetFormTextarea ( array (), array (
				'class' => 'form-control',
				'style' => 'max-height:180px;resize:none;',
				'maxlength' => '250' ) );

		$this->validatorSchema ['note'] = new sfValidatorString ( array (
				'required' => true ) );

		$this->widgetSchema ['description'] = new sfWidgetFormTextarea ( array (), array (
				'class' => 'form-control' ) );

		$this->validatorSchema ['description'] = new sfValidatorString ( array (
				'required' => true ) );

		$this->widgetSchema ['is_access'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadCmsArticleAccess () ), array (
				'class' => 'radiobox' ) );
		
		$this->validatorSchema ['is_access'] = new sfValidatorChoice ( array (
				'choices' => array_keys ( PreSchool::$ps_is_access ),
				'required' => true ) );

		if (myUser::credentialPsCustomers ( 'PS_CMS_ARTICLES_SYSTEM' )) { // Neu co quyen dang thong tin len toan he thong
			
			$this->widgetSchema ['is_global'] = new psWidgetFormSelectRadio ( array ('choices' => PreSchool::loadPsBoolean ()), array ('class' => 'radiobox'));
			//unset ( $this ['is_global'] );
		} else {
			$this->setDefault ( 'is_global', 0 );
			unset ( $this ['is_global'] );
		}
		
		if (!myUser::credentialPsCustomers ( 'PS_CMS_ARTICLES_LOCK' )) { // Neu co quyen khoa album

			$this->widgetSchema ['is_publish'] = new psWidgetFormSelectRadio ( array (
					'choices' => PreSchool::loadCmsArticlesLock () ), array (
					'class' => 'radiobox' ) );
		} else {
			
			$is_publish = PreSchool::loadCmsArticles ();
			
			$this->widgetSchema ['is_publish'] = new psWidgetFormSelectRadio ( array (
					'choices' => $is_publish ), array (
					'class' => 'radiobox' ));
			
		}
		
		$this->path_image = sfConfig::get ( 'sf_web_dir' ) . $this->getPath ();

		$post_max_size = ( int ) sfConfig::get ( 'app_post_max_size' ); // KB
		$post_max_size = ( int ) ini_get ( 'post_max_size' ) > $post_max_size ? $post_max_size : ( int ) ini_get ( 'post_max_size' );

		$upload_max_size = ( int ) sfConfig::get ( 'app_upload_max_size' ); // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		if ($this->getObject ()->file_name) {
			$this->widgetSchema ['file_name'] = new psWidgetFormInputFileEditable ( array (
					'file_src' => sfContext::getInstance ()->getRequest ()
						->getRelativeUrlRoot () . $this->getPath () . $this->getObject ()->file_name,
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
			$this->widgetSchema ['file_name'] = new sfWidgetFormInputFile ();
			$this->widgetSchema ['file_name']->setAttribute ( 'class', 'form-control btn btn-default btn-success btn-psadmin' );
		}

		$this->widgetSchema ['file_name']->setLabel ( 'Article image' );
		$this->widgetSchema->setHelp ( 'file_name', sfContext::getInstance ()->getI18n ()
			->__ ( 'The image file must be in the format: jpg, png, gif. File size less than %value%KB.', array (
				'%value%' => $upload_max_size ) ) );

		$this->validatorSchema ['file_name'] = new myValidatorFile ( array (
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

		$this->validatorSchema ['file_name_delete'] = new sfValidatorBoolean ();

		$this->addBootstrapForm ();
		$this->removeFields ();
		unset ( $this ['user_publish_id'] );
	}

	public function updateObject($values = null) {

		if ($this->getValue ( 'file_name_delete' )) {
			$this->removeDataFile ( $this->getObject ()->file_name );
		}
		/*
		$object = parent::baseUpdateObject ( $values );
		
		$object->setPsClassIds ( implode(",",$this->getValue ( 'ps_class_ids2' )) );
		
		return $object;
		*/
		return parent::baseUpdateObject ( $values );
	}

	// Renname file image upload
	public function save($con = null) {

		// Get the uploaded image
		$image = $this->getValue ( 'file_name' );

		$file_name = '';

		if ($image) {

			$ext = $image->getExtension ( $image->getOriginalExtension () );

			$file_name = 'r_' . time () . date ( 'Ymdhisu' ) . $ext;

			$image->save ( $file_name );
		}

		if ($file_name == '') {
			$file_name = $this->getObject ()
				->getFileName ();
		}

		$sfFilesystem = new sfFilesystem ();

		$path_dir = $this->path_image;

		if ($sfFilesystem->mkdirs ( $path_dir . 'thumb', 0755 ) && $file_name != '' && is_file ( $path_dir . $file_name )) {

			$thumbnail = new sfImage ( $path_dir . $file_name );

			// Get width, height from image upload
			$data = getimagesize ( $path_dir . $file_name );
			$width = $data [0];
			$height = $data [1];

			if ($width > 300) {
				$new_width = 300;
				$new_height = $new_width * $height / $width;
				$thumbnail->thumbnail ( $new_width, $new_height );
			} else {
				$thumbnail->thumbnail ( $width, $height );
			}

			$thumbnail->setQuality ( 100 );
			$thumbnail->saveAs ( $path_dir . 'thumb/' . $file_name );
		}

		return parent::save ( $con );
	}

	protected function removeFields() {

		if ($this->getObject ()->isNew ()) {

			unset ( $this ['created_at'],$this ['school'], $this ['updated_at'], $this ['user_created_id'], $this ['user_updated_id'] );

			/*
			 * if (myUser::credentialPsCustomers ( 'PS_CMS_ARTICLES_FILTER_SCHOOL' )) {
			 * $this->widgetSchema ['file_name']->setAttribute ( 'disabled', 'disabled' );
			 * $this->widgetSchema->setHelp ( 'file_name', sfContext::getInstance ()->getI18n ()->__ ( 'Added after saving the data' ) );
			 * }
			 */
		} else {
			unset ( $this ['created_at'], $this ['updated_at'], $this ['user_created_id'], $this ['user_updated_id'] );
		}
	}

	protected function removeDataFile($file_name) {

		if (is_file ( $this->path_image . '/' . $file_name )) {
			unlink ( $this->path_image . '/' . $file_name );
		}

		if (is_file ( $this->path_image . '/thumb/' . $file_name )) {
			unlink ( $this->path_image . '/thumb/' . $file_name );
		}
	}

	protected function getPath() {

		/*
		 * if ($this->isNew()) {
		 * return '/uploads/cms_articles/';
		 * } else {
		 * $customerId = $this->getObject()->getPsCustomerId();
		 * $createdAt = strtotime($this->getObject()->getCreatedAt());
		 * $yyyy = date("Y", $createdAt);
		 * $mmDD = date("m", $createdAt);
		 * return ('/uploads/cms_articles/' . $customerId . '/' . $yyyy . '/' . $mmDD . '/');
		 * }
		 */
		/**
		 * Cấu trúc đường dẫn file ảnh:
		 *
		 * '/uploads/cms_articles/yyyy/mm/dd';
		 */
		if ($this->isNew ()) {
			return '/uploads/cms_articles/' . date ( "Y/m/d" ) . '/';
		} else {
			return ('/uploads/cms_articles/' . date ( "Y/m/d", strtotime ( $this->getObject ()
				->getCreatedAt () ) )) . '/';
		}
	}

	protected function getFile() {

		$fileName = $this->getObject ()
			->getFileName ();
		return $fileName;
	}
}
