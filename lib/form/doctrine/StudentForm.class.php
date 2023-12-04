<?php
/**
 * Student form.
 *
 * @package Preschool
 * @subpackage form
 * @author Your name here
 * @version SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class StudentForm extends BaseStudentForm {

	protected $path_image;

	protected $year_data;

	public function configure() {

		$this->addPsCustomerFormNotEdit ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' );

		$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsCustomer',
				'required' => true ) );

		$this->addFormI18nChoiceCountry ( 'nationality', $this->getObject ()
			->getNationality () );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id <= 0) {

			$ps_customer_id = $this->getObject ()
				->getPsCustomerId ();
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
							'' => _ ( '-Select basis enrollment-' ) ) ), array (
					'class' => 'select2' ) );
		}

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkplaces',
				'column' => 'id' ) );

		/*
		 * $years = range(date('Y'),sfConfig::get('app_start_year'));
		 * $Year = sfContext::getInstance()->getI18n()->__('Year');
		 * $Month = sfContext::getInstance()->getI18n()->__('Month');
		 * $Day = sfContext::getInstance()->getI18n()->__('Day');
		 * $this->widgetSchema['birthday'] = new sfWidgetFormJQueryDate(
		 * array( 'date_widget' => new sfWidgetFormDate(array('empty_values' => array('year' => '', 'month' => '', 'day' => ''),'format' => '%day%/%month%/%year%','years' => array_combine($years, $years))),
		 * 'config' => '{changeMonth: true, changeYear: true}',
		 * 'image' => sfContext::getInstance()->getRequest()->getRelativeUrlRoot()."/images2/calendar-icon.png"
		 * ));
		 * $this->widgetSchema['sex'] = new sfWidgetFormChoice(array('expanded' => true, 'choices' => PreSchool::getGender()));
		 * $this->widgetSchema['country_id'] = new sfWidgetFormChoice(array('choices' => Doctrine::getTable('Country')->getListBoxCountries()));
		 * $this->widgetSchema['country_id']->setDefault(1);
		 * $this->validatorSchema['country_id'] = new sfValidatorInteger(array( 'required' => false));
		 * $this->widgetSchema['status'] = new sfWidgetFormSelect(array('choices' => Doctrine::getTable('Student')->getStatus()));
		 */

		$this->widgetSchema ['birthday'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['birthday']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );

		$this->validatorSchema ['birthday'] = new sfValidatorDate ( array (
				'required' => true ) );

		$this->widgetSchema ['sex'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsGender () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['start_date_at'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['start_date_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy' ) );

		$this->validatorSchema ['start_date_at'] = new sfValidatorDate ( array (
				'required' => false ) );

		/*
		 * $this->widgetSchema['status'] = new sfWidgetFormChoice(array(
		 * 'choices' => PreSchool::loadStatusStudent()
		 * ), array(
		 * 'class' => 'select2',
		 * 'style' => "min-width:200px;",
		 * 'data-placeholder' => _('-Select status-')
		 * ));
		 */

		$this->validatorSchema ['address'] = new sfValidatorString ( array (
				'required' => true,
				'max_length' => 255 ), array (
				'required' => 'Required',
				'max_length' => 'The maximum field is %max_length% characters' ) );

		$this->validatorSchema ['first_name'] = new sfValidatorString ( array (
				'required' => true,
				'max_length' => 255 ), array (
				'required' => 'Required Firstname',
				'max_length' => 'The maximum field is %max_length% characters' ) );

		$this->validatorSchema ['last_name'] = new sfValidatorString ( array (
				'required' => true,
				'max_length' => 255 ), array (
				'required' => 'Required Lastname',
				'max_length' => 'The maximum field is %max_length% characters' ) );

		$this->validatorSchema ['birthday'] = new sfValidatorDate ( array (
				'required' => true,
				'max' => date ( 'Y-m-d' ) ), array (
				'required' => 'Required Birthday',
				'invalid' => 'Invalid Birthday',
				'max' => 'Birth date must be no larger than' . '(%max%)' ) );

		if ($this->isNew ()) {
			$this->validatorSchema ['student_code'] = new sfValidatorString ( array (
					'required' => false ) );

			$this->year_data = date ( 'Y' );
		} else {
			$this->year_data = $this->getObject ()
				->getYearData ();
		}
		
		$this->path_image = sfConfig::get ( 'app_root_path_image' ).'/PSM'.PreSchool::renderCode("%05s", myUser::getPscustomerID ()).'/avatar';
		
		$file_src = sfConfig::get ( 'app_file_src' ).'/'.'PSM'.PreSchool::renderCode("%05s", myUser::getPscustomerID ()).'/avatar';
		
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {

			$this->_psCustomer = myUser::getPsCustomerById ( myUser::getPscustomerID () );

			//$this->path_image = sfConfig::get ( 'app_ps_data_dir' ) . '/' . $this->_psCustomer->getSchoolCode () . '/' . $this->year_data . '/profile';

			//$file_src = PreSchool::MEDIA_TYPE_STUDENT . '/' . $this->_psCustomer->getSchoolCode () . '/' . $this->year_data;
		} else {
			
			//$this->path_image = sfConfig::get ( 'app_ps_data_dir' ) . '/PSCHOOL/profile/';

			//$file_src = 'PSCHOOL/profile/';

			if ($this->getObject ()
				->get ( 'ps_customer_id' ) > 0) {

				$this->_psCustomer = $this->getObject ()
					->getPsCustomer (); // myUser :: getPsCustomerById($this->getObject()->get('ps_customer_id'));

				//$this->path_image = sfConfig::get ( 'app_ps_data_dir' ) . '/' . $this->_psCustomer->getSchoolCode () . '/' . $this->year_data . '/profile';

				//$file_src = PreSchool::MEDIA_TYPE_STUDENT . '/' . $this->_psCustomer->getSchoolCode () . '/' . $this->year_data;
			}
		}

		$post_max_size = ( int ) sfConfig::get ( 'app_post_max_size' ); // KB
		$post_max_size = ( int ) ini_get ( 'post_max_size' ) > $post_max_size ? $post_max_size : ( int ) ini_get ( 'post_max_size' );

		$upload_max_size = ( int ) sfConfig::get ( 'app_upload_max_size' ); // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		if ($this->getObject ()->image) {
			$this->widgetSchema ['image'] = new psWidgetFormInputFileEditable ( array (
					// 'file_src' => sfContext::getInstance()->getRequest()->getRelativeUrlRoot() . '/pschool/' . $file_src . '/' . $this->getObject()->image,
					'file_src' => $file_src . '/' . $this->getObject ()->image,
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

		$this->validatorSchema->setPostValidator ( new sfValidatorAnd ( array (
				new sfValidatorDoctrineUnique ( array (
						'model' => 'Student',
						'column' => array (
								'student_code' ) ), array (
						'invalid' => 'Code already exist' ) ) ) ) );

		$this->widgetSchema ['caphoc'] = new sfWidgetFormChoice ( array (
				'choices' => PreSchool::loadCapHoc () ), 
				array (
					'class' => 'select2',
					'style' => 'min-width:150px;',
					'data-placeholder' => ''));
		
		$this->widgetSchema ['caphoc']->setAttributes ( array (
				'style' => 'min-width:200px;',
				'class' => 'select2',
				'required' => false ) );

		$this->widgetSchema ['chuongtrinh'] = new sfWidgetFormChoice ( array (
				'choices' => PreSchool::loadChuongTrinhDaoTao () ), 
				array (
					'class' => 'select2',
					'style' => 'min-width:150px;',
					'data-placeholder' => ''));
		
		$this->widgetSchema ['chuongtrinh']->setAttributes ( array (
				'style' => 'min-width:200px;',
				'class' => 'select2',
				'required' => false ) );

		$this->showUseFields ();

		// $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

		$this->addBootstrapForm ();

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' ) || ! $this->isNew ()) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control' ) );
		}

		$this->removeFields ();
	}

	protected function showUseFields() {

		$this->useFields ( array (
				'ps_customer_id',
				'ps_workplace_id',
				'student_code',
				'first_name',
				'last_name',
				'birthday',
				'sex',
				'start_date_at',
				'common_name',
				'nationality',
				'ethnic_id',
				'religion_id',
				'address',
				// 'status',
				'policy_id',
				'type_year',
				'image',
				'caphoc',
				'chuongtrinh',
				'khoihoc',
				'doituong' ) );
	}

	public function updateObject($values = null) {

		if ($this->getValue ( 'image_delete' )) {
			$this->removeDataFile ( $this->getObject ()->image );
		}

		$object = parent::baseUpdateObject ( $values );

		if ($this->isNew ()) {
			$object->setYearData ( $this->year_data );
		}

		$object->setStudentCode ( PreString::trim ( $this->getValue ( 'student_code' ) ) );
		$object->setFirstName ( PreString::trim ( $this->getValue ( 'first_name' ) ) );
		$object->setLastName ( PreString::trim ( $this->getValue ( 'last_name' ) ) );
		$object->setAddress ( PreString::trim ( $this->getValue ( 'address' ) ) );
		$object->setCommonName ( PreString::trim ( $this->getValue ( 'common_name' ) ) );
		$object->setNickName ( PreString::trim ( $this->getValue ( 'nick_name' ) ) );

		return $object;
	}

	// Renname file image upload
	public function save($con = null) {

		// Get the uploaded image
		$image = $this->getValue ( 'image' );

		$file_name = '';

		if ($image) {

			$ext = $image->getExtension ( $image->getOriginalExtension () );
			
			//$file_name = 'hinhanh_' . time (). $ext;
			
			$full_name = $this->getObject ()->getFirstName().$this->getObject ()->getLastName();
			
			$file_name = time().'_'. PreString::covert_to_latin($full_name). $ext;
			
			$image->save ( $file_name );
		}

		if ($file_name == '') {
			$file_name = $this->getObject ()->getImage ();
		}

		$sfFilesystem = new sfFilesystem ();
		$path_dir = $this->path_image . DIRECTORY_SEPARATOR;
		$path_file_root = $path_dir . $file_name;

		$sfFilesystem = new sfFilesystem ();

		$path_dir = $this->path_image . DIRECTORY_SEPARATOR;

		if ($sfFilesystem->mkdirs ( $path_dir . 'thumb', 0755 ) && $file_name != '') {
			$thumbnail = new sfImage ( $path_dir . $file_name );
			$thumbnail->thumbnail ( 45, 45 );
			$thumbnail->setQuality ( 100 );
			$thumbnail->saveAs ( $path_dir . 'thumb/' . $file_name );
		}

		if ($this->getObject ()
			->getAvatar () == '' && $file_name != '' && $sfFilesystem->mkdirs ( $path_dir . 'avatar', 0755 )) {

			$avatar = new sfImage ( $path_dir . $file_name );
			$avatar->thumbnail ( 150, 150 );
			$avatar->setQuality ( 100 );
			$avatar->saveAs ( $path_dir . 'avatar/' . $file_name );

			$this->getObject ()
				->setAvatar ( $file_name );
		}

		return parent::save ( $con );
	}

	protected function removeFields() {

		if ($this->getObject ()
			->isNew ()) {
			// unset ($this['created_at'], $this['updated_at'], $this['user_created_id'], $this['user_updated_id'], $this['student_code']);

			unset ( $this ['created_at'], $this ['updated_at'], $this ['user_created_id'], $this ['user_updated_id'] );

			$this->widgetSchema ['student_code']->setAttribute ( 'readonly', 'readonly' );
			$this->widgetSchema ['student_code']->setAttribute ( 'placeholder', sfContext::getInstance ()->getI18n ()
				->__ ( 'Automatic system generated' ) );

			if (myUser::credentialPsCustomers ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' )) {

				$this->widgetSchema ['image']->setAttribute ( 'disabled', 'disabled' );
				$this->widgetSchema->setHelp ( 'image', sfContext::getInstance ()->getI18n ()
					->__ ( 'Added after saving the data' ) );
			}
		} else {
			unset ( $this ['created_at'], $this ['updated_at'], $this ['user_created_id'], $this ['user_updated_id'] );
		}
		unset ( $this ['year_data'],$this ['class_id'] );
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

