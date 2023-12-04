<?php
/**
 * Relative form.
 *
 * @package    backend
 * @subpackage form
 * @author     Nguyen Chien Thang <ntsc279@hotmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class RelativeForm extends BaseRelativeForm {

	protected $path_image;

	protected $year_data;

	public function configure() {

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_RELATIVE_FILTER_SCHOOL' ) || ! $this->isNew ()) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control',
					'required' => 'required' ) );
		}

		$this->addPsCustomerFormNotEdit ( 'PS_STUDENT_RELATIVE_FILTER_SCHOOL' );

		$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsCustomer',
				'required' => true ) );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id <= 0)
			$ps_customer_id = $this->getObject ()
				->getPsCustomerId ();

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
				'required' => true,
				'model' => 'PsWorkplaces',
				'column' => 'id' ) );

		$this->widgetSchema ['phone']->setAttributes ( array (
				'maxlength' => 12 ) );

		$this->widgetSchema ['first_name']->setAttributes ( array (
				'maxlength' => 255 ) );

		$this->widgetSchema ['last_name']->setAttributes ( array (
				'maxlength' => 255 ) );

		$this->validatorSchema ['identity_card'] = new sfValidatorRegex ( array (
				'required' => null,
				'pattern' => '/^[a-zA-Z0-9]+$/' ), array (
				'invalid' => 'Invalid Identity card (includes only the characters a-zA-Z0-9).' ) );

		// Chung minh ND
		$this->widgetSchema ['identity_card']->setAttributes ( array (
				'maxlength' => 12,
				'minlength' => 8 ) );
		/*
		 * $this->validatorSchema ['email'] = new sfValidatorAnd ( array (
		 * new sfValidatorEmail ( array (
		 * 'required' => false
		 * ), array (
		 * 'invalid' => 'Invalid Email "%value%".'
		 * ) )
		 * ,
		 * new sfValidatorDoctrineUnique ( array (
		 * 'model' => 'Relative',
		 * 'column' => array (
		 * 'email',
		 * 'id'
		 * )
		 * ), array (
		 * 'invalid' => 'Email address already exist.'
		 * ) )
		 * ) );
		 */

		$this->validatorSchema ['email'] = new sfValidatorEmail ( array (
				'required' => false ), array (
				'invalid' => 'Invalid Email "%value%".' ) );

		$this->widgetSchema ['birthday'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['birthday']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy' ) );

		$this->validatorSchema ['birthday'] = new sfValidatorDate ( array (
				'required' => false ) );

		$this->widgetSchema ['card_date'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['card_date']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy' ) );

		$this->validatorSchema ['card_date'] = new sfValidatorDate ( array (
				'required' => false ) );

		$this->addFormI18nChoiceCountry ( 'nationality', $this->getObject ()
			->getNationality () );

		if ($this->isNew ()) {
			$this->year_data = date ( 'Y' );
		} else {
			$this->year_data = $this->getObject ()
				->getYearData ();
		}

		// $this->setDateWidgetSchema('card_date');
		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_RELATIVE_FILTER_SCHOOL' )) {

			$this->_psCustomer = myUser::getPsCustomerById ( myUser::getPscustomerID () );

			$this->path_image = sfConfig::get ( 'app_ps_data_dir' ) . '/' . $this->_psCustomer->getSchoolCode () . '/' . $this->year_data . '/relative';

			$file_src = PreSchool::MEDIA_TYPE_RELATIVE . '/' . $this->_psCustomer->getSchoolCode () . '/' . $this->year_data;
		} else {

			$this->path_image = sfConfig::get ( 'app_ps_data_dir' ) . '/PSCHOOL/relative';

			$file_src = 'PSCHOOL/relative';

			if ($this->getObject ()
				->get ( 'ps_customer_id' ) > 0) {

				$this->_psCustomer = $this->getObject ()
					->getPsCustomer ();
				$this->path_image = sfConfig::get ( 'app_ps_data_dir' ) . '/' . $this->_psCustomer->getSchoolCode () . '/' . $this->year_data . '/relative';

				$file_src = PreSchool::MEDIA_TYPE_RELATIVE . '/' . $this->_psCustomer->getSchoolCode () . '/' . $this->year_data;
			}
		}

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

		$this->widgetSchema ['sex'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsGender () ), array (
				'class' => 'radiobox' ) );

		// Add class style for select control
		$this->widgetSchema ['ethnic_id']->setAttributes ( array (
				'class' => 'select2' ) );
		$this->widgetSchema ['religion_id']->setAttributes ( array (
				'class' => 'select2' ) );

		$this->widgetSchema ['email']->setAttributes ( array (
				'type' => 'email',
				'maxlength' => 255 ) );
		$this->widgetSchema ['mobile']->setAttributes ( array (
				'type' => 'tel',
				'maxlength' => 14,
				'onkeypress' => 'return keyPhone(event)',
				'onkeyup' => 'change(this)' ) );
		$this->widgetSchema ['phone']->setAttributes ( array (
				'type' => 'tel',
				'maxlength' => 12 ) );
		$this->widgetSchema ['address']->setAttributes ( array (
				'maxlength' => 255 ) );
		$this->widgetSchema ['job']->setAttributes ( array (
				'maxlength' => 255 ) );

		$this->addBootstrapForm ();

		$this->showUseFields ();

		$this->mergePostValidator ( new sfValidatorCallback ( array (
				'callback' => array (
						$this,
						'postValidateEmailExits' ) ) ) );

		$this->removeFields ();
	}

	protected function removeFields() {

		unset ( $this ['created_at'], $this ['updated_at'], $this ['user_created_id'], $this ['user_updated_id'], $this ['year_data'] );

		if ($this->getObject ()
			->isNew () && myUser::credentialPsCustomers ( 'PS_STUDENT_RELATIVE_FILTER_SCHOOL' )) {
			$this->widgetSchema ['image']->setAttribute ( 'disabled', 'disabled' );
			$this->widgetSchema->setHelp ( 'image', sfContext::getInstance ()->getI18n ()
				->__ ( 'Added after saving the data' ) );
		}
	}

	protected function showUseFields() {

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_RELATIVE_FILTER_SCHOOL' )) {

			$this->useFields ( array (
					// 'ps_province_id',
					// 'ps_district_id',
					// 'ps_ward_id',
					'ps_customer_id',
					'ps_workplace_id',
					'first_name',
					'last_name',
					'birthday',
					'sex',
					'identity_card',
					'card_date',
					'card_local',
					'nationality',
					'ethnic_id',
					'religion_id',
					'address',
					// 'phone',
					'mobile',
					'email',
					'job',
					'image' ) );
		} else {
			$this->useFields ( array (
					// 'ps_province_id',
					// 'ps_district_id',
					// 'ps_ward_id',
					'ps_customer_id',
					'ps_workplace_id',
					'first_name',
					'last_name',
					'birthday',
					'sex',
					'identity_card',
					'card_date',
					'card_local',
					'nationality',
					'ethnic_id',
					'religion_id',
					'address',
					// 'phone',
					'mobile',
					'email',
					'job',
					'image' ) );
		}
	}

	// Renname file image upload
	public function save($con = null) {

		// Get the uploaded image
		$image = $this->getValue ( 'image' );

		$file_name = '';

		if ($image) {

			$ext = $image->getExtension ( $image->getOriginalExtension () );

			$file_name = 'r_' . time () . date ( 'Ymdhisu' ) . $ext;

			$image->save ( $file_name );
		}

		if ($file_name == '') {
			$file_name = $this->getObject ()
				->getImage ();
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

	public function postValidateEmailExits(sfValidatorCallback $validator, array $values) {

		$email = $values ['email'];

		$objid = $values ['id'];

		$objtype = PreSchool::USER_TYPE_RELATIVE;

		if (! psValidatorEmail::checkUniqueEmailPsMember ( $email, $objid, $objtype )) {
			$error = new sfValidatorError ( $validator, 'Email address already exist.' );
			throw new sfValidatorErrorSchema ( $validator, array (
					"email" => $error ) );
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
				' ' ) );

		$object->setMobile ( $mobile );

		if ($this->isNew ()) {
			$object->setYearData ( $this->year_data );
		}

		$object->setFirstName ( PreString::trim ( $this->getValue ( 'first_name' ) ) );
		$object->setLastName ( PreString::trim ( $this->getValue ( 'last_name' ) ) );
		$object->setAddress ( PreString::trim ( $this->getValue ( 'address' ) ) );
		$object->setJob ( PreString::trim ( $this->getValue ( 'job' ) ) );

		return $object;
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


/*
class sfValidatedFileCustom extends sfValidatedFile {

	private $savedFilename = null;

	// Override sfValidatedFile's save method
  	public function save($file = null, $fileMode = 0666, $create = true, $dirMode = 0777) {
    	// This makes sure we use only one savedFilename (it will be the first)
		if ($this->savedFilename === null)
			$this->savedFilename = $file;

		// Let the original save method do its magic :)
    	return parent::save($this->savedFilename, $fileMode, $create, $dirMode);
  	}
}*/
