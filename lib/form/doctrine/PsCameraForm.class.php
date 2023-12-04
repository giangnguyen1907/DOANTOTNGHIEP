<?php

/**
 * PsCamera form.
 *
 * @package quanlymamnon.vn
 * @subpackage form
 * @author quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsCameraForm extends BasePsCameraForm {

	protected $path_image;

	protected $year_data;

	public function configure() {

		if (! $this->getObject()->isNew()) {
			
			$this->year_data = $this->getObject()->getYearData();
			
			$psCustomer = $this->getObject()
				->getPsWorkPlaces()
				->getPsCustomer();
			
			$this->setDefault('ps_customer_id', $psCustomer->getId());
			
			$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
					'choices' => array(
							$psCustomer->getId() => $psCustomer->getSchoolCode() . '-' . $psCustomer->getSchoolName()
					)
			));
			
			$this->validatorSchema['ps_customer_id'] = new sfValidatorChoice(array(
					'choices' => array(
							$psCustomer->getId()
					)
			));
			
			$this->widgetSchema['ps_customer_id']->setAttributes(array(
					'class' => 'form-control',
					'required' => true
			));
		} else {
			
			$this->year_data = date("Y");
			
			if (myUser::credentialPsCustomers('PS_SYSTEM_CAMERA_FILTER_SCHOOL')) {
				
				$ps_customer_active = PreSchool::ACTIVE; // Lay nhung truong hoc dang hoat dong
				
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers($ps_customer_active),
						'add_empty' => _('-Select customer-')
				));
				
				$this->validatorSchema['ps_customer_id'] = new sfValidatorDoctrineChoice(array(
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers($ps_customer_active),
						'required' => true
				));
			} else {
				
				$psCustomer = Doctrine::getTable('PsCustomer')->findOneBy('id', myUser::getPscustomerID());
				
				$this->setDefault('ps_customer_id', $psCustomer->getId());
				
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
						'choices' => array(
								$psCustomer->getId() => $psCustomer->getSchoolCode() . '-' . $psCustomer->getSchoolName()
						)
				));
				
				$this->validatorSchema['ps_customer_id'] = new sfValidatorChoice(array(
						'choices' => array(
								myUser::getPscustomerID()
						)
				));
				
				$this->widgetSchema['ps_customer_id']->setAttributes(array(
						'class' => 'form-control',
						'required' => true
				));
			}
		}
		
		$ps_customer_id = $this->getDefault('ps_customer_id');
		
		if ($ps_customer_id > 0) {
			$this->widgetSchema['ps_workplace_id'] = new sfWidgetFormDoctrineChoice(array(
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable('PsWorkPlaces')->setSQLByCustomerId('id, title', $ps_customer_id),
					'add_empty' => _('-Select workplaces-')
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _('-Select workplaces-')
			));
		} else {
			$this->widgetSchema['ps_workplace_id'] = new sfWidgetFormChoice(array(
					'choices' => array(
							'' => _('-Select workplaces-')
					)
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _('-Select workplaces-')
			));
		}
		
		$this->validatorSchema['ps_class_room_id'] = new sfValidatorPass(array(
				'required' => false
		));
		
		$this->widgetSchema['url_ip'] = new sfWidgetFormInputText();
		
		$this->validatorSchema['url_ip'] = new sfValidatorString(array(
				'required' => true
		));
		
		$this->widgetSchema['is_activated'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsActivity()
		), array(
				'class' => 'radiobox'
		));
		
		$this->widgetSchema['title']->setAttributes(array(
				'class' => 'form-control',
				'maxlength' => 255
		));
		
		$this->widgetSchema['url_ip']->setAttributes(array(
				'class' => 'form-control',
				'maxlength' => 500
		));
		
		$this->widgetSchema['user_camera']->setAttributes(array(
				'class' => 'form-control',
				'maxlength' => 255
		));
		
		$this->widgetSchema['password_camera']->setAttributes(array(
				'class' => 'form-control',
				'maxlength' => 255
		));
		
		$this->widgetSchema['note']->setAttributes(array(
				'class' => 'form-control',
				'maxlength' => 255
		));
		
		if ($this->getObject()->image_name) {
			
			$file_src = PreSchool::MEDIA_TYPE_CAMERA . '/' . $psCustomer->getSchoolCode() . '/' . $this->year_data;
			
			$this->widgetSchema['image_name'] = new psWidgetFormInputFileEditable(array(
					'file_src' => '/media-web/root/' . $file_src . '/' . $this->getObject()->image_name,
					'is_image' => true,
					'edit_mode' => ! $this->isNew(),
					'with_delete' => true,
					'delete_label' => 'Delete',
					'attributes_for_delete' => array(
							'class' => ''
					),
					'attributes_for_file_src' => array(
							'class' => 'box_image  pull-left',
							'style' => 'max-width:70px;'
					),
					'template' => '<div class="row"><div class="col-sm-12">%input%</div><div class="col-sm-12" style="margin-left:15px;">%delete% %delete_label% %file%</div></div>'
			), array(
					'class' => 'form-control btn btn-default btn-success btn-psadmin'
			));
		} else {
			$this->widgetSchema['image_name'] = new sfWidgetFormInputFile();
			$this->widgetSchema['image_name']->setAttribute('class', 'form-control btn btn-default btn-success btn-psadmin');
		}
		
		$post_max_size = (int) sfConfig::get('app_post_max_size'); // KB
		$post_max_size = (int) ini_get('post_max_size') > $post_max_size ? $post_max_size : (int) ini_get('post_max_size');
		
		$upload_max_size = (int) sfConfig::get('app_upload_max_size'); // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes
		
		$this->widgetSchema->setHelp('image_name', sfContext::getInstance()->getI18n()
			->__('The image file must be in the format: jpg, png, gif. File size less than %value%KB.', array(
				'%value%' => $upload_max_size
		)));
		
		if ($this->getObject()->isNew() && myUser::credentialPsCustomers('PS_SYSTEM_CAMERA_FILTER_SCHOOL')) {
			$this->widgetSchema['image_name']->setAttribute('disabled', 'disabled');
		} else {
			$this->path_image = sfConfig::get('app_ps_data_dir') . '/' . $psCustomer->getSchoolCode() . '/' . $this->year_data . '/camera';
		}
		
		$this->validatorSchema['image_name'] = new myValidatorFile(array(
				'required' => false,
				'path' => $this->path_image,
				'mime_types' => 'web_images',
				'max_size' => $upload_max_size_byte,
				'validated_file_class' => 'psValidatedFileCustom'
		), array(
				'mime_types' => 'The image file must be in the format: jpg, png, gif. File size less than 500KB.',
				'max_size' => sfContext::getInstance()->getI18n()->__('File is too large maximum is') . ': ' . $upload_max_size . 'KB'
		));
		
		$this->validatorSchema->setMessage('post_max_size', sfContext::getInstance()->getI18n()
			->__('You have uploaded a file that is too big.') . '(<=' . $post_max_size . 'MB)');
		
		$this->validatorSchema ['image_name_delete'] = new sfValidatorBoolean ();

		$this->addBootstrapForm ();

		unset ( $this ['year_data'] );
	}

	public function updateObject($values = null) {

		$object = parent::baseUpdateObject ( $values );

		if ($this->getObject ()
			->isNew ()) {
			$object->setYearData ( $this->year_data );
		}

		return $object;
	}

	public function save($con = null) {

		// Get the uploaded image
		$image = $this->getValue ( 'image_name' );

		if ($image) {

			$ext = $image->getExtension ( $image->getOriginalExtension () );

			$file_name = 'cam' . time () . date ( 'Ymdhisu' ) . $ext;

			$image->save ( $file_name );
		}

		return parent::save ( $con );
	}
}// end class

