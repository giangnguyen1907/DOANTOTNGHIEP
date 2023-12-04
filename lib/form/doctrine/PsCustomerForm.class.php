<?php

/**
 * PsCustomer form.
 *
 * @package quanlymamnon.vn
 * @subpackage form
 * @author quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsCustomerForm extends BasePsCustomerForm {

	protected $path_image;

	protected $year_data;

	public function configure() {

		$this->curentRemoveFields();
		
		if ($this->getObject()->isNew()) {
			$this->year_data = date('Y');
		} else {
			$this->year_data = $this->getObject()->getYearData();
		}
		
		$country_code = strtoupper(sfConfig::get('app_ps_default_country'));
		$parameter_form = sfContext::getInstance()->getRequest()->getParameter('ps_customer');
		
		if ($parameter_form != null) {
			
			$ps_province_id = $parameter_form['ps_province_id'];
			
			$ps_district_id = $parameter_form['ps_district_id'];
			
			$ps_ward_id = $parameter_form['ps_ward_id'];
			
			$this->setDefault('ps_district_id', $ps_district_id);
			
			$this->setDefault('ps_province_id', $ps_province_id);
			
			$this->setDefault('ps_ward_id', $ps_ward_id);
		} else {
			
			if (! $this->getObject()->isNew()) {
				
				if (! myUser::isAdministrator()) {
					$this->widgetSchema['school_code']->setAttribute('readonly', 'readonly');
				}
				
				$psCustomer = $this->getObject();
				
				// Xa phuong
				$ps_ward_id = $psCustomer->getPsWardId();
				
				$psWard = $psCustomer->getPsWard();
				
				// Quan/Huyen
				$ps_district_id = $psWard->getPsDistrictId();
				
				// Tinh Thanh
				$ps_province_id = $psWard->getPsDistrict()->getPsProvinceId();
				
				$this->setDefault('ps_district_id', $ps_district_id);
				
				$this->setDefault('ps_province_id', $ps_province_id);
				
				$this->setDefault('ps_ward_id', $ps_ward_id);
			}
		}
		
		$ps_province_id = $this->getDefault('ps_province_id');
		
		$ps_district_id = $this->getDefault('ps_district_id');
		
		$ps_ward_id = $this->getDefault('ps_ward_id');
		
		$this->widgetSchema['ps_province_id'] = new sfWidgetFormDoctrineChoice(array(
				'model' => 'PsProvince',
				'query' => Doctrine::getTable('PsProvince')->setSqlPsProvinceByCountry($country_code),
				'add_empty' => _('-Select province-')
		), array(
				'class' => 'select2',
				'style' => "min-width:200px;",
				'data-placeholder' => _('-Select province-')
		));
		
		$this->widgetSchema['ps_province_id']->setLabel('Province');
		
		$this->validatorSchema['ps_province_id'] = new sfValidatorInteger(array(
				'required' => true
		));
		
		if ($ps_province_id > 0) {
			$this->widgetSchema['ps_district_id'] = new sfWidgetFormDoctrineChoice(array(
					'model' => 'PsDistrict',
					'query' => Doctrine::getTable('PsDistrict')->setSqlPsDistrictByProvinceId($ps_province_id /* , $ps_district_id */
					),
					'add_empty' => _('-Select district-')
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _('-Select district-')
			));
		} else {
			$this->widgetSchema['ps_district_id'] = new sfWidgetFormChoice(array(
					'choices' => array(
							'' => _('-Select district-')
					)
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _('-Select district-')
			));
		}
		
		$this->validatorSchema['ps_district_id'] = new sfValidatorInteger(array(
				'required' => true
		));
		
		if ($ps_district_id > 0) {
			
			$this->widgetSchema['ps_ward_id'] = new sfWidgetFormChoice(array(
					'choices' => array(
							'' => _('-Select Ward-')
					) + Doctrine::getTable('PsWard')->getChoicePsWard($ps_district_id)
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _('-Select Ward-')
			));
		} else {
			
			$this->widgetSchema['ps_ward_id'] = new sfWidgetFormChoice(array(
					'choices' => array(
							'' => _('-Select Ward-')
					)
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _('-Select Ward-')
			));
		}
		
		$this->validatorSchema['ps_ward_id'] = new sfValidatorInteger(array(
				'required' => true
		));
		
		$this->widgetSchema['email']->setOption('type', 'email');
		$this->widgetSchema['url']->setOption('type', 'url');
		
		$post_max_size = (int) sfConfig::get('app_post_max_size'); // KB
		$post_max_size = (int) ini_get('post_max_size') > $post_max_size ? $post_max_size : (int) ini_get('post_max_size');
		
		$upload_max_size = (int) sfConfig::get('app_upload_max_size'); // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes
		
		$this->path_image = $this->getObject()->isNew() ? sfConfig::get('app_ps_upload_dir') . '/' . $this->year_data : sfConfig::get('app_ps_upload_dir') . '/' . $this->year_data;
		
		if ($this->getObject()->logo) {
			
			$this->widgetSchema['logo'] = new psWidgetFormInputFileEditable(array(
					'file_src' => '/media-web/' . $this->year_data . '/logo/' . $this->getObject()->logo,
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
					'class' => 'form-control btn btn-default btn-success btn-psadmin box_image'
			));
		} else {
			$this->widgetSchema['logo'] = new sfWidgetFormInputFile();
			$this->widgetSchema['logo']->setAttributes(array(
					'class' => 'form-control btn btn-default btn-success btn-psadmin'
			) // 'disabled' => 'disabled'
);
		}
		
		$this->validatorSchema['logo'] = new myValidatorFile(array(
				'required' => false,
				// 'path' => sfConfig::get('app_ps_upload_dir'),
				'path' => $this->path_image,
				'mime_types' => 'web_images',
				'max_size' => $upload_max_size_byte,
				'validated_file_class' => 'sfValidatedFileCustom'
		), array(
				'mime_types' => 'Image file must have format: jpg,png,gif.',
				'max_size' => sfContext::getInstance()->getI18n()->__('The file is too large. Allowed maximum size is %value%KB', array(
						'%value%' => $upload_max_size
				))
		));
		
		$this->validatorSchema->setMessage('post_max_size', sfContext::getInstance()->getI18n()
			->__('You have uploaded a file that is too big.') . '(<=' . $post_max_size . 'MB)');
		
		$this->widgetSchema['is_activated'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsCustomerActivated()
		), array(
				'class' => 'radiobox'
		));
		
		$this->widgetSchema['is_deploy'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsCustomerDeploy()
		), array(
				'class' => 'radiobox'
		));
		
		if (myUser::isAdministrator()) {
			
			$this->widgetSchema['is_root'] = new psWidgetFormSelectRadio(array(
					'choices' => PreSchool::loadPsBoolean()
			), array(
					'class' => 'radiobox'
			));
			
			$this->validatorSchema['is_root'] = new sfValidatorInteger(array(
					'required' => true
			));
		} else {
			unset($this['is_root']);
		}
		
		$this->widgetSchema['description']->setAttributes(array(
				'class' => 'form-control',
				'maxlength' => 2000
		));
		
		$this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
				new sfValidatorDoctrineUnique(array(
						'model' => 'PsCustomer',
						'column' => array(
								'school_code'
						)
				), array(
						'invalid' => 'Code already exist'
				))
		)));
		
		$this->addBootstrapForm ();
	}

	protected function curentRemoveFields() {

		unset ( $this ['title'], $this ['activated_at'], $this ['user_activated_id'], $this ['year_data'], $this ['cache_data'] );
		if ($this->isNew ()) {
			$this->widgetSchema ['school_code']->setAttribute ( 'readonly', 'readonly' );
			$this->widgetSchema ['school_code']->setAttribute ( 'placeholder', sfContext::getInstance ()->getI18n ()
				->__ ( 'Automatic system generated' ) );
		}
	}

	/**
	 * Save*
	 */
	public function save($con = null) {

		$this->updateObject ();

		// Get the uploaded image
		$image = $this->getValue ( 'logo' );

		if ($image) {
			$ext = $image->getExtension ( $image->getOriginalExtension () );
			$image->save ( 'PSLG_' . time () . date ( 'Ymdhisu' ) . $ext );
		}

		$object = parent::save ( $con );

		/*
		 * if ($this->isNew()) {
		 * $year_data = $this->year_data;
		 * $CustomerId = $object->getId();
		 * $renderCode = 'KS' . PreSchool::renderCode("%010s", $CustomerId);
		 * $object->setSchoolCode($renderCode);
		 * $object->setCacheData($renderCode);
		 * $object->setYearData($year_data);
		 * // Tao data_cache
		 * $data_cache_dir = sfConfig::get ( 'app_ps_data_cache_dir' );
		 * $folder_cache = PsEndCode::psHash256 ( $CustomerId . $renderCode );
		 * $object->setCacheData($folder_cache);
		 * $object->save();
		 * Doctrine_Lib::makeDirectories ( $data_cache_dir . '/' . $folder_cache, 0777 );
		 * Doctrine_Lib::makeDirectories ( $data_cache_dir . '/' . $folder_cache.'/'. $year_data.'/hr', 0777 );
		 * Doctrine_Lib::makeDirectories ( $data_cache_dir . '/' . $folder_cache.'/'. $year_data.'/student', 0777 );
		 * Doctrine_Lib::makeDirectories ( $data_cache_dir . '/' . $folder_cache.'/'. $year_data.'/relative', 0777 );
		 * $root_customer_path = sfConfig::get('app_ps_data_dir') . '/' . $renderCode;
		 * Doctrine_Lib::makeDirectories($root_customer_path);
		 * Doctrine_Lib::copyDirectory(sfConfig::get('app_ps_sch_code_dir'), $root_customer_path . '/' . $year_data);
		 * }
		 */

		/*
		 * else {
		 * // Tao data_cache
		 * $data_cache_dir = sfConfig::get ( 'app_ps_data_cache_dir' );
		 * $folder_cache = PsEndCode::psHash256 ( $this->getObject()->getId() . $this->getObject()->getSchoolCode() );
		 * if (!is_dir($data_cache_dir . '/' . $folder_cache)) {
		 * $object->setCacheData($folder_cache);
		 * $object->save();
		 * Doctrine_Lib::makeDirectories ( $data_cache_dir . '/' . $folder_cache, 0777 );
		 * Doctrine_Lib::makeDirectories ( $data_cache_dir . '/' . $folder_cache.'/'. $this->getObject()->getYearData().'/hr', 0777 );
		 * Doctrine_Lib::makeDirectories ( $data_cache_dir . '/' . $folder_cache.'/'. $this->getObject()->getYearData().'/student', 0777 );
		 * Doctrine_Lib::makeDirectories ( $data_cache_dir . '/' . $folder_cache.'/'. $this->getObject()->getYearData().'/relative', 0777 );
		 * }
		 * //$root_customer_path = sfConfig::get('app_ps_data_dir') . '/' . $this->getObject()->getYearData();
		 * }
		 */

		return $object;
	}

	public function doSave($con = null) {

		return parent::doSave ( $con );
	}

	public function updateObject($values = null) {

		$object = parent::updateObject ( $values );

		$object->setTitle ( $this->getObject ()
			->getSchoolName () );

		/*
		 * if ($this->getObject()->getSchoolCode() == '') {
		 * $CustomerId = $object->getId();
		 * $renderCode = 'KS' . PreSchool::renderCode("%010s", $CustomerId);
		 * $object->setSchoolCode($renderCode);
		 * $object->setCacheData($renderCode);
		 * $object->setYearData($this->year_data);
		 * $root_customer_path = sfConfig::get('app_ps_data_dir') . '/' . $renderCode;
		 * Doctrine_Lib::makeDirectories($root_customer_path);
		 * // Tao folder chua du lieu cua khach hang
		 * Doctrine_Lib::copyDirectory(sfConfig::get('app_ps_sch_code_dir'), $root_customer_path . '/' . $this->year_data);
		 * }
		 */
		if ($this->isNew ()) {
			$object->setYearData ( $this->year_data );
		}

		if (! $this->isNew () && $this->getObject ()
			->getIsActivated () != 1) { // Neu truong hoc bi khoa

			// Load all user for customer_id
			$user_list = Doctrine::getTable ( 'sfGuardUser' )->findByPsCustomerId ( $this->getObject ()
				->getId () );

			$con = $this->getConnection ();

			try {

				$con->beginTransaction ();

				foreach ( $user_list as $user ) {
					$user->setIsActive ( 0 );
					$user->save ();
				}

				$con->commit ();
			} catch ( Exception $e ) {

				$con->rollBack ();

				throw $e;
			}
		}

		$userId = sfContext::getInstance ()->getUser ()
			->getGuardUser ()
			->getId ();

		if ($this->getObject ()
			->isNew ()) {
			$object->setUserCreatedId ( $userId );
			$object->setUserUpdatedId ( $userId );
		} else {
			$object->setUserUpdatedId ( $userId );
			$currentDateTime = new PsDateTime ();
			$object->setUpdatedAt ( $currentDateTime->getCurrentDateTime () );
		}

		return $object;
	}
}// end class