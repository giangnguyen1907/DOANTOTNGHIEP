<?php

/**
 * sfGuardUser form.
 *
 * @package Preschool
 * @subpackage form
 * @author Your name here
 * @version SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsUserDepartmentsForm extends PluginsfGuardUserForm {

	protected $user_type;

	protected $path_avatar;

	public function configure() {
		
		/*
		$this->widgetSchema ['user_type'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['user_type']->setDefault ( PreSchool::USER_TYPE_TEACHER );
		$this->getObject ()->setUserType ( PreSchool::USER_TYPE_TEACHER );
		
		$this->validatorSchema ['user_type'] = new sfValidatorChoice ( array (
				'choices' => array (PreSchool::USER_TYPE_TEACHER),
				'required' => true
		));
		
		
		$ps_customer_root = Doctrine::getTable ( 'PsCustomer' )->getCustomerRootById ( $this->getObject ()->getPsCustomerId () );
		
		$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						$ps_customer_root->getId () => $ps_customer_root->getName ()
				)
		), array (
				'class' => "form-control",
				'style' => "min-width:200px;"
		) );
		
		$this->validatorSchema ['ps_customer_id'] = new sfValidatorChoice( array (
				'choices' => array($ps_customer_root->getId ()),
				'required' => true
		) );
		*/
		
		/*

		$this->widgetSchema ['full_name'] = new psWidgetFormInputRange ( array (

				'input_first' => new sfWidgetFormInputText ( array (), array (
						'required' => 'required',
						'class' => 'form-control',
						'placeholder' => sfContext::getInstance ()->getI18n ()->__ ( 'First name' )
				) ),

				'input_last' => new sfWidgetFormInputText ( array (), array (
						'required' => 'required',
						'class' => 'form-control',
						'placeholder' => sfContext::getInstance ()->getI18n ()->__ ( 'Last name' )
				) ),

				'template' => '<div class="row"><div class="col-md-6">%input_first%</div><div class="col-md-6">%input_last%</div></div>'
		) );

		$this->validatorSchema ['input_first'] = new sfValidatorPass ( array (
				'required' => true
		) );

		$this->validatorSchema ['input_last'] = new sfValidatorPass ( array (
				'required' => true
		) );

		$this->validatorSchema ['full_name'] = new sfValidatorPass ( array (
				'required' => true
		) );

		unset ( $this ['input_first'], $this ['input_last'] );
		*/
		
		/*
		 * 
		// Custom add field member_id
		$model = $this->getRelatedModelName ( 'PsMember' );
		$query = Doctrine::getTable ( 'PsMember' )->setSQLMemberCustomerRootForUser ($this->getObject ()->getPsCustomerId(), $this->getObject ()->getMemberId() );
		$this->widgetSchema ['member_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => $model,
				'query' => $query,
				'add_empty' => true
		) );
		
		$this->widgetSchema ['is_active'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsUserActivated ()
		), array (
				'class' => 'radiobox'
		) );

		$this->validatorSchema ['is_active'] = new sfValidatorChoice ( array (
				'required' => true,
				'choices' => array_keys ( PreSchool::$ps_boolean )
		) );
		*/
		
		/*
		$this->widgetSchema ['manager_type'] = new sfWidgetFormSelect ( array (
				'choices' => array (
						'' => '-Choose management level-'
				) + PreSchool::loadPsDepartmentType ()
		), array (
				'class' => 'form-control'
		) );

		$this->validatorSchema ['manager_type'] = new sfValidatorChoice ( array (
				'required' => true,
				'choices' => array_keys ( PreSchool::$ps_department_type )
		) );
		
		*/

		$this->widgetSchema ['password']->setOption ( 'type', 'password' );

		$this->widgetSchema ['password']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()->__ ( 'Passwords must be at least 8 characters to include digits, uppercase, lowercase.' )
		) );
		
		/*

		$country_code = strtoupper ( sfConfig::get ( 'app_ps_default_country' ) );

		$country_code = strtoupper ( sfConfig::get ( 'app_ps_default_country' ) );

		$ps_province_id = null;

		if ($this->getObject ()->isNew ()) {
			
			$ps_province_query = Doctrine::getTable ( 'PsProvince' )->setSqlPsProvinceByCountry ( $country_code );

			$this->widgetSchema ['ps_province_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsProvince',
					'query' => $ps_province_query,
					'add_empty' => _ ( '-Select province-' )
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select province-' )
			) );
		
		} else {
			$ps_province_query = Doctrine::getTable ( 'PsProvince' )->setSqlPsUserProvinceByUserId ( $this->getObject ()->getId () );

			$ps_user_provinces = Doctrine::getTable ( 'PsUserProvinces' )->getListByUserId ( $this->getObject ()->getId () );

			foreach ( $ps_user_provinces as $ps_user_province ) {
				$ps_province_id = $ps_user_province->getPsProvinceId ();
			}

			$this->widgetSchema ['ps_province_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsProvince',
					'query' => $ps_province_query,
					'add_empty' => false
			), array (
					'class' => 'form-control',
					'style' => "min-width:200px;",
					'required' => true
			) );
		}

		$this->widgetSchema ['ps_province_id']->setLabel ( 'Department of Education' );

		$this->validatorSchema ['ps_province_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsProvince',
				'query' => $ps_province_query,
				'required' => true
		) );

		$this->setDefault ( 'ps_province_id', $ps_province_id );

		$ps_province_id = $this->getDefault ( 'ps_province_id' );

		if ($ps_province_id > 0) {

			$ps_district_query = Doctrine::getTable ( 'PsDistrict' )->setSqlPsUserDistrictByUserId ( $ps_province_id, $this->getObject ()->getId () );

			$ps_district_required = false;

			$arr_ps_district_id = array ();

			if ($this->getObject ()->getManagerType() == PreSchool::MANAGER_TYPE_DISTRICT) {

				$ps_district_required = true;

				$ps_user_districts = Doctrine::getTable ( 'PsUserDistricts' )->getListByUserId ( $this->getObject ()->getId () );

				foreach ( $ps_user_districts as $ps_user_district ) {
					array_push ( $arr_ps_district_id, $ps_user_district->getPsDistrictId () );
				}
			}

			$this->widgetSchema ['ps_district_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsDistrict',
					'query' => $ps_district_query,
					'add_empty' => _ ( '-Select district-' )
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select district-' )
			) );

			$this->validatorSchema ['ps_district_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'PsDistrict',
					'query' => $ps_district_query,
					'required' => true
			) );

			$this->setDefault ( 'ps_district_id', $arr_ps_district_id );
		} else {
			$this->widgetSchema ['ps_district_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select district-' )
					)
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select district-' )
			) );

			$this->validatorSchema ['ps_district_id'] = new sfValidatorPass ( array (
					'required' => true
			) );
		}
		*/

		/*
		 * $this->widgetSchema ['ps_district_id'] = new sfWidgetFormDoctrineChoiceGrouped ( array (
		 * 'model' => 'PsDistrict',
		 * 'query' => Doctrine::getTable ( 'PsDistrict' )->setGroupPsDistricts ( $country_code ),
		 * 'expanded' => true,
		 * 'multiple' => true,
		 * 'group_by' => 'province_name',
		 * 'renderer_options' => array (
		 * 'template' => '<label><strong>%group%</strong></label> %options%' ) ) );
		 * $this->validatorSchema ['ps_district_id'] = new sfValidatorDoctrineChoice ( array (
		 * 'model' => 'PsDistrict',
		 * 'required' => false ) );
		 */
		/*
		$this->path_avatar = sfConfig::get ( 'sf_web_dir' ) . $this->getPathAvatar ();
		$post_max_size = ( int ) sfConfig::get ( 'app_post_max_size' ); // KB
		$post_max_size = ( int ) ini_get ( 'post_max_size' ) > $post_max_size ? $post_max_size : ( int ) ini_get ( 'post_max_size' );

		$upload_max_size = ( int ) sfConfig::get ( 'app_upload_max_size' ); // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		if ($this->getObject ()->avatar) {
			$this->widgetSchema ['avatar'] = new psWidgetFormInputFileEditable ( array (
					'file_src' => sfContext::getInstance ()->getRequest ()->getRelativeUrlRoot () . $this->getPathAvatar () . $this->getObject ()->avatar,
					'is_image' => true,
					'edit_mode' => ! $this->isNew (),
					'with_delete' => true,
					'delete_label' => 'Delete',
					'attributes_for_delete' => array (
							'class' => 'checkbox'
					),
					// 'attributes_for_file_src' => array('class' => 'img-responsive class-img pull-left'),
					'attributes_for_file_src' => array (
							'class' => 'box_image  pull-left',
							'style' => 'max-width:100px;'
					),
					'template' => '<div class="row"><div class="col-sm-12">%input%</div><div class="col-sm-12" style="margin-left:20px;">%delete% %delete_label% %file%</div></div>'
			), array (
					'class' => 'form-control btn btn-default btn-success btn-psadmin'
			) );
		} else {
			$this->widgetSchema ['avatar'] = new sfWidgetFormInputFile ();
			$this->widgetSchema ['avatar']->setAttribute ( 'class', 'form-control btn btn-default btn-success btn-psadmin' );
		}

		$this->widgetSchema ['avatar']->setLabel ( 'Avatar' );
		$this->widgetSchema->setHelp ( 'avatar', sfContext::getInstance ()->getI18n ()->__ ( 'The image file must be in the format: jpg, png, gif. File size less than %value%KB.', array (
				'%value%' => $upload_max_size
		) ) );

		$this->validatorSchema ['avatar'] = new myValidatorFile ( array (
				'required' => false,
				'path' => $this->path_avatar,
				'mime_types' => 'web_images',
				'max_size' => $upload_max_size_byte,
				'validated_file_class' => 'psValidatedFileCustom'
		), array (
				'mime_types' => 'The image file must be in the format: jpg, png, gif. File size less than 500KB.',
				'max_size' => sfContext::getInstance ()->getI18n ()->__ ( 'File is too large maximum is' ) . ': ' . $upload_max_size . 'KB'
		) );

		$this->validatorSchema->setMessage ( 'post_max_size', sfContext::getInstance ()->getI18n ()->__ ( 'You have uploaded a file that is too big.' ) . '(<=' . $post_max_size . 'MB)' );

		$this->validatorSchema ['avatar_delete'] = new sfValidatorBoolean ();
		
		*/

		$this->addBootstrapForm ();

		$this->widgetSchema ['ps_customer_id']->setAttribute ( 'class', 'form-control' );

		if ($this->getObject ()->isNew ()) {
			$this->useFields ( array (
					'user_type',
					//'full_name',
					// 'first_name',
					// 'last_name',
					'ps_customer_id',
					'member_id',
					'username',
					'password',
					'manager_type',
					'is_active'
			) );
		} else {

			//$this->widgetSchema ['ps_province_id']->setAttribute ( 'class', 'form-control' );

			$this->useFields ( array (
					'user_type',
					//'full_name',
					'ps_customer_id',
					'member_id',
					'username',
					'password',
					'manager_type',
					'is_active'
			) );
		}

		$this->validatorSchema->setPostValidator ( new sfValidatorAnd ( array (
				new sfValidatorDoctrineUnique ( array (
						'model' => 'sfGuardUser',
						'column' => array (
								'id'
						)
				) ),
				new sfValidatorDoctrineUnique ( array (
						'model' => 'sfGuardUser',
						'column' => array (
								'username'
						)
				) ),
				
				new sfValidatorDoctrineUnique ( array (
						'model' => 'sfGuardUser',
						'column' => array (
								'member_id',
								'user_type'
						)
				) )
		) ) );

	}

	protected function removeFields() {

		unset ( $this ['created_at'], $this ['updated_at'], $this ['algorithm'], $this ['salt'], $this ['last_login'] );
		unset ( $this ['is_super_admin'], $this ['is_global_super_admin']);

	}

	public function updateObject($values = null) {

		if ($this->getObject ()->getPassword () === $this->getValue ( 'password' )) {
			unset ( $this ['password'] );
		}

		$object = parent::updateObject ( $values );

		$userId = myUser::getUserId ();

		$currentDateTime = new PsDateTime ();

		if ($this->getObject ()->isNew ()) {
			$object->setUserCreatedId ( $userId );
			$object->setUserUpdatedId ( $userId );
			$userKey = PsEndCode::psHash256 ( $object->id );
			$object->setUserKey ( $userKey );
		} else {

			$object->setUserUpdatedId ( $userId );

			$object->setUpdatedAt ( $currentDateTime->getCurrentDateTime () );

			$userKey = PsEndCode::psHash256 ( $object->id );

			$object->setUserKey ( $userKey );
		}

		// Cap nhat tinh thanh
		/*
		$user_department_id = $object->id;
		$ps_user_provinces = Doctrine::getTable ( 'PsUserProvinces' )->getListByUserId ( $user_department_id );

		foreach ( $ps_user_provinces as $ps_user_province ) {
			$ps_user_province->delete ();
		}

		$ps_province_id = $this->getValue ( 'ps_province_id' );

		if ($ps_province_id > 0) {

			$ps_user_provinces = new PsUserProvinces ();
			$ps_user_provinces->setUserId ( $user_department_id );
			$ps_user_provinces->setPsProvinceId ( $ps_province_id );
			$ps_user_provinces->setUserCreatedId ( $userId );
			$ps_user_provinces->setUserUpdatedId ( $userId );
			$ps_user_provinces->setCreatedAt ( $currentDateTime->getCurrentDateTime () );
			$ps_user_provinces->setUpdatedAt ( $currentDateTime->getCurrentDateTime () );

			$ps_user_provinces->save ();
		}

		// Cap nhat Quan/Huyen
		$ps_user_districts = Doctrine::getTable ( 'PsUserDistricts' )->getListByUserId ( $user_department_id );

		foreach ( $ps_user_districts as $ps_obj ) {
			$ps_obj->delete ();
		}

		if ($this->getValue ( 'manager_type' ) == PreSchool::MANAGER_TYPE_PROVINCIAL) {
			unset ( $this ['ps_district_id'] );
		} else {

			$ps_district_id = $this->getValue ( 'ps_district_id' );

			if ($ps_district_id > 0) {

				$ps_user_district = new PsUserDistricts ();
				$ps_user_district->setUserId ( $user_department_id );
				$ps_user_district->setPsDistrictId ( $ps_district_id );
				$ps_user_district->setUserCreatedId ( $userId );
				$ps_user_district->setUserUpdatedId ( $userId );
				$ps_user_district->setCreatedAt ( $currentDateTime->getCurrentDateTime () );
				$ps_user_district->setUpdatedAt ( $currentDateTime->getCurrentDateTime () );

				$ps_user_district->save ();
			}
		}
		*/
		
		return $object;

	}

	public function updateDefaultsFromObject() {

		parent::updateDefaultsFromObject ();
		/*
		if (isset ( $this->widgetSchema ['full_name'] )) {
			$this->setDefault ( 'full_name', array (
					"first" => $this->getObject ()->getFirstName (),
					"last" => $this->getObject ()->getLastName ()
			) );
		}
		*/
	}

	public function processValues($values) {

		$values = parent::processValues ( $values );
		
		/*

		$values ['first_name'] = $values ["full_name"] ["first"];
		$values ['last_name'] = $values ["full_name"] ["last"];
		*/
		
		return $values;

	}

	protected function getPathAvatar() {

		return '/uploads/user_avatar/';

	}

	// Renname file image upload
	public function save($con = null) {

		// Get the uploaded image
		$image = $this->getValue ( 'avatar' );

		$file_name = '';

		if ($image) {

			$ext = $image->getExtension ( $image->getOriginalExtension () );

			$file_name = 't_' . time () . date ( 'Ymdhisu' ) . $ext;

			$image->save ( $file_name );
		}

		if ($file_name == '') {
			$file_name = $this->getObject ()->getAvatar ();
		}

		$sfFilesystem = new sfFilesystem ();

		$path_dir = $this->path_avatar . DIRECTORY_SEPARATOR;

		if ($sfFilesystem->mkdirs ( $path_dir . 'thumb', 0755 ) && $file_name != '') {

			$thumbnail = new sfImage ( $path_dir . $file_name );
			$thumbnail->thumbnail ( 45, 45 );
			$thumbnail->setQuality ( 100 );
			$thumbnail->saveAs ( $path_dir . 'thumb/' . $file_name );
		}

		if ($this->getObject ()->getAvatar () == '' && $file_name != '' && $sfFilesystem->mkdirs ( $path_dir . 'avatar', 0755 )) {

			$avatar = new sfImage ( $path_dir . $file_name );
			$avatar->thumbnail ( 150, 150 );
			$avatar->setQuality ( 100 );
			$avatar->saveAs ( $path_dir . '/' . $file_name );

			$this->getObject ()->setAvatar ( $file_name );
		}

		return parent::save ( $con );

	}

	protected function removeDataFile($file_name) {

		if (is_file ( $this->path_avatar . '/' . $file_name )) {
			unlink ( $this->path_avatar . '/' . $file_name );
		}

		if (is_file ( $this->path_avatar . '/thumb/' . $file_name )) {
			unlink ( $this->path_avatar . '/thumb/' . $file_name );
		}

	}

}
