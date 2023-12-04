<?php
class PsHrDepartmentsForm extends BasePsMemberForm {

	protected $path_image;

	protected $year_data;

	public function configure() {
		
		$country_code = strtoupper ( sfConfig::get ( 'app_ps_default_country' ) );
		
		$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormInputHidden ();

		$workplace = Doctrine::getTable ( 'PsWorkplaces' )->getPsWorkPlaceRoot ();
		$ps_workplace_id = $workplace->getId ();
		$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );

		if ($this->getObject ()->isNew ()) {

			$ps_customer_root = Doctrine::getTable ( 'PsCustomer' )->getCustomerRootById ();
			$ps_customer_id = $ps_customer_root->getId ();
			$school_code = $ps_customer_root->getSchoolCode ();

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorChoice ( array (
					'choices' => array (
							$ps_customer_id
					),
					'required' => true
			) );
			
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );			
		} else {

			$ps_customer_id = $this->getObject ()->getPsCustomerId ();

			$school_code = $this->getObject ()->getPsCustomer ()->getSchoolCode ();

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorChoice ( array (
					'choices' => array (
							$ps_customer_id
					),
					'required' => true
			));
		}
		
		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorChoice ( array (
				'choices' => array (
						$ps_workplace_id
				),
				'required' => true
		));
		
		if ((myUser::getUser ()->getManagerType () == PreSchool::MANAGER_TYPE_PROVINCIAL) || (myUser::getUser ()->getManagerType () == PreSchool::MANAGER_TYPE_DISTRICT)) { // Nếu là Cán bộ sở/Phòng

			$ps_province_id = myUser::getUser ()->getPsMember ()->getPsProvinceId ();

			$this->setDefault ( 'ps_province_id', $ps_province_id );

			$query_province = Doctrine::getTable ( 'PsProvince' )->setSqlPsProvinceByCountry ( $country_code, $ps_province_id );

			$ps_district_id = myUser::getUser ()->getPsMember ()->getPsDistrictId ();
		
		} else {
			
			if (myUser::isAdministrator ()) {
				
				$query_province = Doctrine::getTable ( 'PsProvince' )->setSqlPsProvinceByCountry ( $country_code );
			
			} elseif (myUser::getUser ()->getManagerType () == PreSchool::MANAGER_TYPE_GLOBAL) {
				// Lấy ra danh sách tỉnh thành được quản lý
				$query_province = Doctrine::getTable ( 'PsProvince' )->setSqlPsUserProvinceByUserId ( $country_code, myUser::getUser ()->getId () );
			}
			
			$ps_province_id = $this->getObject ()->getPsProvinceId();
			$ps_district_id = $this->getObject ()->getPsDistrictId ();
		}
		
		$this->widgetSchema ['ps_province_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => $this->getRelatedModelName ( 'PsProvinces' ),
				'query' => $query_province,
				'add_empty' => _ ( '-Select province-' )
		), array (
				'class' => 'select2',
				'style' => "min-width:200px;",
				'data-placeholder' => _ ( '-Select province-' )
		));

		$this->validatorSchema ['ps_province_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => $this->getRelatedModelName ( 'PsProvinces' ),
				'query' => $query_province,
				'required' => true
		) );

		$ps_district_required = false;
		if (myUser::getUser ()->getManagerType () == PreSchool::MANAGER_TYPE_DISTRICT) {
			$ps_district_required = true;
		}
		
		if ($ps_province_id > 0) {
			
			$query_district = Doctrine::getTable ( 'PsDistrict' )->setSqlPsDistrictByProvinceId ( $ps_province_id );
			
			$this->widgetSchema ['ps_district_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsDistrict',
					'query' =>  $query_district,
					'add_empty' => _ ( '-Select district-' )
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select district-' )
			) );
			
			$this->validatorSchema ['ps_district_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'PsDistrict',
					'query' => $query_district,
					'required' => $ps_district_required
			) );
			
		} else {

			$this->widgetSchema ['ps_district_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select district-' )
					)
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select district-' )
			) );

			$this->validatorSchema ['ps_district_id'] = new sfValidatorPass ( array (
					'required' => $ps_district_required
			) );
		}

		$this->validatorSchema ['identity_card'] = new sfValidatorRegex ( array (
				'required' => false,
				'pattern' => '/^[a-zA-Z0-9]+$/'
		), array (
				'invalid' => 'Invalid Identity card (includes only the characters a-zA-Z0-9).'
		) );

		$this->widgetSchema ['email'] = new sfWidgetFormInputText ();

		$this->validatorSchema ['email'] = new sfValidatorEmail ( array (
				'required' => false
		), array (
				'invalid' => 'Invalid Email "%value%".'
		) );

		$this->widgetSchema ['birthday'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['birthday']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required'
		) );

		$this->validatorSchema ['birthday'] = new sfValidatorDate ( array (
				'required' => true
		) );

		$this->widgetSchema ['card_date'] = new psWidgetFormInputDate ();

		$this->validatorSchema ['card_date'] = new sfValidatorDate ( array (
				'required' => false
		) );

		$this->widgetSchema ['card_date']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => false
		) );

		$this->addFormI18nChoiceCountry ( 'nationality', $this->getObject ()->getNationality () );

		if ($this->getObject ()->isNew ()) {
			$this->year_data = date ( 'Y' );
		} else {
			$this->year_data = $this->getObject ()->getYearData ();
		}

		$this->path_image = sfConfig::get ( 'app_ps_data_dir' ) . '/' . $school_code . '/' . $this->year_data . '/hr';
		$file_src = PreSchool::MEDIA_TYPE_TEACHER . '/' . $school_code . '/' . $this->year_data;

		$post_max_size = ( int ) sfConfig::get ( 'app_post_max_size' ); // KB
		$post_max_size = ( int ) ini_get ( 'post_max_size' ) > $post_max_size ? $post_max_size : ( int ) ini_get ( 'post_max_size' );

		$upload_max_size = ( int ) sfConfig::get ( 'app_upload_max_size' ); // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		if ($this->getObject ()->image) {
			$this->widgetSchema ['image'] = new psWidgetFormInputFileEditable ( array (
					'file_src' => '/media-web/root/' . $file_src . '/' . $this->getObject ()->image,
					'is_image' => true,
					'edit_mode' => ! $this->isNew (),
					'with_delete' => true,
					'delete_label' => 'Delete',
					'attributes_for_delete' => array (
							'class' => 'checkbox'
					),
					'attributes_for_file_src' => array (
							'class' => 'box_image  pull-left',
							'style' => 'max-width:150px;'
					),
					'template' => '<div class="row"><div class="col-sm-12">%input%</div><div class="col-sm-12" style="margin-left:20px;">%delete% %delete_label% %file%</div></div>'
			), array (
					'class' => 'form-control btn btn-default btn-success btn-psadmin'
			) );
		} else {
			$this->widgetSchema ['image'] = new sfWidgetFormInputFile ();
			$this->widgetSchema ['image']->setAttribute ( 'class', 'form-control btn btn-default btn-success btn-psadmin' );
		}

		$this->widgetSchema ['image']->setLabel ( 'File image' );
		$this->widgetSchema->setHelp ( 'image', sfContext::getInstance ()->getI18n ()->__ ( 'The image file must be in the format: jpg, png, gif. File size less than %value%KB.', array (
				'%value%' => $upload_max_size
		) ) );

		$this->validatorSchema ['image'] = new myValidatorFile ( array (
				'required' => false,
				'path' => $this->path_image,
				'mime_types' => 'web_images',
				'max_size' => $upload_max_size_byte,
				'validated_file_class' => 'sfValidatedFileCustom'
		), array (
				'mime_types' => 'The image file must be in the format: jpg, png, gif. File size less than 500KB.',
				'max_size' => sfContext::getInstance ()->getI18n ()->__ ( 'File is too large maximum is' ) . ': ' . $upload_max_size . 'KB'
		) );

		$this->validatorSchema->setMessage ( 'post_max_size', sfContext::getInstance ()->getI18n ()->__ ( 'You have uploaded a file that is too big.' ) . '(<=' . $post_max_size . 'MB)' );

		$this->validatorSchema ['image_delete'] = new sfValidatorBoolean ();

		$this->widgetSchema ['sex'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsGender ()
		), array (
				'class' => 'radiobox'
		) );

		$this->widgetSchema ['religion_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => "PsReligion",
				'query' => Doctrine::getTable ( 'PsReligion' )->setSQLSelectReligion (),
				'add_empty' => _ ( '-Select religion-' )
		) );

		$this->validatorSchema ['religion_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsReligion',
				'column' => 'id'
		) );

		// Add class style for select control
		$this->widgetSchema ['ethnic_id']->setAttributes ( array (
				'class' => 'select2'
		) );

		$this->widgetSchema ['religion_id']->setAttributes ( array (
				'class' => 'select2'
		) );

		$this->widgetSchema ['email']->setAttributes ( array (
				'type' => 'email',
				'maxlength' => 255
		) );

		$this->widgetSchema ['first_name']->setAttributes ( array (
				'maxlength' => 255
		) );

		$this->widgetSchema ['last_name']->setAttributes ( array (
				'maxlength' => 255
		) );

		$this->widgetSchema ['card_local']->setAttributes ( array (
				'maxlength' => 255
		) );

		$this->widgetSchema ['mobile']->setAttributes ( array (
				'type' => 'tel',
				'maxlength' => 14
		) );

		$this->widgetSchema ['phone']->setAttributes ( array (
				'type' => 'tel',
				'maxlength' => 12
		) );

		$this->widgetSchema ['address']->setAttributes ( array (
				'maxlength' => 255
		) );

		$this->validatorSchema ['member_code'] = new sfValidatorString ( array (
				'required' => false
		) );

		$this->widgetSchema ['is_status']->setLabel ( 'Status' );

		$this->widgetSchema ['is_status'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadHrStatus ()
		), array (
				'class' => 'radiobox'
		) );

		$this->widgetSchema ['is_status']->setDefault ( PreSchool::HR_STATUS_WORKING );

		$this->addBootstrapForm ();

		$this->widgetSchema ['ps_workplace_id']->setAttributes ( array (
				'class' => 'form-control'
		) );

		$this->removeFields ();

		$this->mergePostValidator ( new sfValidatorCallback ( array (
				'callback' => array (
						$this,
						'postValidateEmailExits'
				)
		) ) );

	}

	protected function removeFields() {

		if ($this->getObject ()->isNew ()) {
			unset ( $this ['created_at'], $this ['updated_at'], $this ['user_created_id'], $this ['user_updated_id'] );

			$this->widgetSchema ['member_code']->setAttribute ( 'readonly', 'readonly' );
			$this->widgetSchema ['member_code']->setAttribute ( 'placeholder', sfContext::getInstance ()->getI18n ()->__ ( 'Automatic system generated' ) );
		} else {
			unset ( $this ['created_at'], $this ['updated_at'], $this ['user_created_id'], $this ['user_updated_id'] );
		}

		unset ( $this ['year_data'] );

	}

	public function postValidateEmailExits(sfValidatorCallback $validator, array $values) {

		$email = $values ['email'];
		$objid = $values ['id'];
		$objtype = PreSchool::USER_TYPE_TEACHER;

		if (! psValidatorEmail::checkUniqueEmailPsMember ( $email, $objid, $objtype )) {

			$error = new sfValidatorError ( $validator, 'Email address already exist.' );
			throw new sfValidatorErrorSchema ( $validator, array (
					"email" => $error
			) );
		}
		return $values;

	}

	public function updateObject($values = null) {

		if ($this->getValue ( 'image_delete' )) {
			$this->removeDataFile ( $this->getObject ()->image );
		}

		$object = parent::baseUpdateObject ( $values );

		$mobile = $object->getMobile ();

		$mobile = PreString::strReplace ( $mobile, array (
				'.',
				',',
				';',
				' '
		) );

		$object->setMobile ( $mobile );

		if ($this->isNew ()) {
			$object->setYearData ( $this->year_data );
		}

		$object->setMemberCode ( PreString::trim ( $this->getValue ( 'member_code' ) ) );
		$object->setFirstName ( PreString::trim ( $this->getValue ( 'first_name' ) ) );
		$object->setLastName ( PreString::trim ( $this->getValue ( 'last_name' ) ) );
		$object->setAddress ( PreString::trim ( $this->getValue ( 'address' ) ) );

		return $object;

	}

	// Renname file image upload
	public function save($con = null) {

		// Get the uploaded image
		$image = $this->getValue ( 'image' );

		$file_name = '';

		if ($image) {

			$ext = $image->getExtension ( $image->getOriginalExtension () );

			$file_name = 't_' . time () . date ( 'Ymdhisu' ) . $ext;

			$image->save ( $file_name );
		}

		if ($file_name == '') {
			$file_name = $this->getObject ()->getImage ();
		}

		$sfFilesystem = new sfFilesystem ();

		$path_dir = $this->path_image . DIRECTORY_SEPARATOR;

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
			$avatar->saveAs ( $path_dir . 'avatar/' . $file_name );

			$this->getObject ()->setAvatar ( $file_name );
		}

		return parent::save ( $con );

	}

	protected function removeDataFile($file_name) {

		if (is_file ( $this->path_image . '/' . $file_name )) {
			unlink ( $this->path_image . '/' . $file_name );
		}

		if (is_file ( $this->path_image . '/thumb/' . $file_name )) {
			unlink ( $this->path_image . '/thumb/' . $file_name );
		}

	}

}