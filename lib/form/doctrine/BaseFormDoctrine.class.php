<?php

/**
 * Project form base class.
 *
 * @package Preschool
 * @subpackage form
 * @author Nguyen Chien Thang(ntsc279@hotmail.com)
 *        
 * @version SVN: $Id: sfDoctrineFormBaseTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class BaseFormDoctrine extends sfFormDoctrine {

	public function setup() {

		$this->removeFields();
		
		/*
		if (myUser::isAdministrator())
			$this->setIOrder('iorder', 1);
		else
		$this->setIOrder('iorder', myUser::getPscustomerID());
		*/
		
		$this->setIOrder('iorder', myUser::getPscustomerID());
		
		$this->setIsActivatedWidgetSchema();
		
		// $this->addRequiredFields();
		
		// $this->addBootstrapForm();
		
		unset($this->validatorSchema['user_created_id']);
		unset($this->validatorSchema['user_updated_id']);
	
	}

	protected function removeFields() {

		unset($this['created_at'], $this['updated_at'], $this['user_created_id'], $this['user_updated_id']);
	
	}
	
	// Set required fields as mandatory - add at the end of the configure() method
	protected function addRequiredFields($inputForm = null) {

		if ($inputForm == null) {
			// Set required fields as mandatory - add at the end of the configure() method
			foreach ($this->getFormFieldSchema()->getWidget()->getFields() as $key => $object) {
				
				$label = $this->getFormFieldSchema()->offsetGet($key)->renderLabelName();
				
				if ($this->validatorSchema[$key]->getOption('required') == true) {
					
					$this->widgetSchema->setLabel($key, $label . '<span class="required"> *</span>');
					
					$currAttribute = $this->widgetSchema[$key]->getAttributes();
					
					$currAttribute['required'] = 'required';
					
					$this->widgetSchema[$key]->setAttributes($currAttribute);
				}
			}
		} else {
			
			$label = $this->getFormFieldSchema()->offsetGet($inputForm)->renderLabelName();
			
			if ($this->validatorSchema[$inputForm]->getOption('required') == true) {
				
				$this->widgetSchema->setLabel($inputForm, $label . '<span class="required"> *</span>');
				
			}
		}
	
	}

	/**
	 * auto add value for iorder of table model
	 */
	protected function setIOrder($iorder = 'iorder', $ps_customer_id = null) {

		if (isset($this->widgetSchema[$iorder])) {
			$value = 1;
			if ($this->getObject()->isNew()) {
				
				if ($ps_customer_id > 0 && Doctrine_Core::getTable($this->getModelName())->hasColumn('ps_customer_id')) {
					
					$maxOrder = Doctrine_Core::getTable($this->getModelName())->createQuery('a')->select('MAX(a.' . $iorder . ') AS cnt_order')
						->where('a.ps_customer_id = ?', $ps_customer_id)
						->fetchOne(array());
				} else {
					
					$maxOrder = Doctrine_Core::getTable($this->getModelName())->createQuery()
						->select('MAX(' . $iorder . ') AS cnt_order')
						->fetchOne(array());
				}
				if ($maxOrder)
					$value = $maxOrder->getCntOrder() + 1;
			}
			
			$this->setDefault($iorder, $value);
			
			$this->widgetSchema[$iorder]->setOption('type', 'number');
		}
		
		return;
	
	}

	/**
	 * auto set value for iorder of table model
	 */
	protected function setIsActivatedWidgetSchema($keyActivated = 'is_activated') {

		if (isset($this->widgetSchema[$keyActivated]))
			$this->widgetSchema[$keyActivated] = new sfWidgetFormSelectRadio(array(
					'choices' => PreSchool::loadPsActivity()
			));
		
		$this->setDefault($keyActivated, 1);
		return;
	
	}

	protected function setDateWidgetSchema($keyField, $format = '<div class="col-sm-2">%day%</div><div class="col-sm-2">%month%</div><div class="col-sm-3">%year%') {

		$format = '<div class="col-sm-2">%day%</div><div class="col-sm-2">%month%</div><div class="col-sm-3">%year%</div>';
		
		if (isset($this->widgetSchema[$keyField])) {
			$years = range(date('Y'), sfConfig::get('app_start_year'));
			$Year = sfContext::getInstance()->getI18n()->__('Year');
			$Month = sfContext::getInstance()->getI18n()->__('Month');
			$Day = sfContext::getInstance()->getI18n()->__('Day');
			
			$this->widgetSchema[$keyField] = new psWidgetFormDate(array(
					'date_widget' => new sfWidgetFormDate(array(
							'empty_values' => array(
									'year' => '',
									'month' => '',
									'day' => ''
							),
							'format' => $format,
							'years' => array_combine($years, $years)
					)),
					'config' => '{changeDay: true,changeMonth: true, changeYear: true, buttonText: "<i class=\'fa fa-calendar\'></i>", prevText: "<<", nextText: ">>"}',
					// 'image' => sfContext :: getInstance()->getRequest()->getRelativeUrlRoot() . "/images/calendar-icon.png",
					'culture' => 'vi'
			));
			
			$this->widgetSchema[$keyField]->setAttributes(array(
					'class' => 'form-control'
			));
		}
	
	}

	protected function setMonthYearWidgetSchema($keyField, $format = '%day%/%month%/%year%') {

		if (isset($this->widgetSchema[$keyField])) {
			$years = range(date('Y') + 5, sfConfig::get('app_start_year'));
			$Year = sfContext::getInstance()->getI18n()->__('Year');
			$Month = sfContext::getInstance()->getI18n()->__('Month');
			$Day = sfContext::getInstance()->getI18n()->__('Day');
			
			/*
			 * $this->widgetSchema[$keyField] = new sfWidgetFormJQueryDate(array (
			 * 'date_widget' => new sfWidgetFormDate(array (
			 * 'empty_values' => array (
			 * 'year' => '',
			 * 'month' => '',
			 * 'day' => ''
			 * ),
			 * 'format' => $format,
			 * 'years' => array_combine($years, $years)
			 * )),
			 * 'config' => '{changeMonth: true, changeYear: true}',
			 * 'image' => sfContext :: getInstance()->getRequest()->getRelativeUrlRoot() . "/images2/calendar-icon.png"
			 * ));
			 */
			
			$this->widgetSchema[$keyField] = new sfWidgetFormDate(array(
					'format' => $format,
					'years' => array_combine($years, $years),
					'days' => array(
							'01'
					),
					'empty_values' => array(
							'day' => $Day,
							'month' => $Month,
							'year' => $Year
					)
			));
		}
	
	}

	/**
	 * Ham gan truong hoc cho form *
	 */
	protected function setPsCustomerForm($ps_customer_id = 0) {

		if ($ps_customer_id <= 0) {
			
			$psHeaderFilter = sfContext::getInstance()->getUser()->getAttribute('psHeaderFilter', null, 'admin_module');
			
			$ps_customer_id = 0;
			
			if ($psHeaderFilter)
				$ps_customer_id = $psHeaderFilter['ps_customer_id'];
			
			if ($ps_customer_id <= 0)
				$ps_customer_id = myUser::getUser()->getPsCustomerId();
		}
		
		$ps_customer = Doctrine::getTable('PsCustomer')->findOneById($ps_customer_id);
		
		$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
				'choices' => array(
						$ps_customer->getId() => $ps_customer->getSchoolCode() . '-' . $ps_customer->getSchoolName()
				)
		));
		
		$this->validatorSchema['ps_customer_id'] = new sfValidatorChoice(array(
				'choices' => array(
						$ps_customer_id
				)
		));
		
		$this->widgetSchema['ps_customer_id']->setAttributes(array(
				'class' => 'form-control',
				'required' => true
		));
		
		$this->setDefault('ps_customer_id', $ps_customer_id);
	
	}

	/**
	 * loadPsCustomerForm()
	 * Hien thi ps_province_id; ps_district_id; ps_ward_id; ps_customer_id
	 * Dung trong truong hop object co ps_customer_id
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param
	 *        	string - input form
	 * @return widgetSchema list ps_province_id; ps_district_id; ps_ward_id; ps_customer_id
	 *        
	 */
	protected function loadPsCustomerForm($function_code = null) {

		/*
		 * Khong duoc phep lua chon truong hoac khong cho sua doi truong neu la edit
		 */
		$is_select = false;
		
		if (! $this->getObject()->isNew()) {
			$is_select = false;
			$psCustomer = $this->getObject()->getPsCustomer();
			
			// Xa phuong
			$psWard = $psCustomer->getPsWard();
			$ps_ward_id = $psWard->getId();
			
			// Quan/Huyen
			$ps_district_id = $psWard->getPsDistrictId();
			
			// Tinh thanh
			$ps_province_id = $psWard->getPsDistrict()->getPsProvinceId();
		} else {
			if (myUser::credentialPsCustomers($function_code)) {
				$is_select = true;
			} else {
				$is_select = false;
				$this->getObject()->setPsCustomerId(myUser::getPscustomerID());
			}
		}
	
	}

	/**
	 * addPsCustomerForm()
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param
	 *        	string - input form
	 * @return widgetSchema list PsCustomer
	 *        
	 */
	protected function addPsCustomerForm() {

		$ps_customer_active = null;
		
		if (myUser::isAdministrator()) { // Neu la quan tri co quyen thay doi truong hoc
			
			$ps_customer_active = $this->getObject()->isNew() ? PreSchool::$ps_customer_active[1] : null;
			
			$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers($ps_customer_active, null),
					'add_empty' => true
			));
			
			$this->widgetSchema['ps_customer_id']->setAttributes(array(
					'class' => 'select2'
			));
		} else { // Trai lai xet cho nguoi dung quan tri thong thuong
			
			$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers($ps_customer_active, myUser::getPscustomerID()),
					'add_empty' => false
			));
			
			$this->widgetSchema['ps_customer_id']->setAttributes(array(
					'class' => 'form-control'
			));
			
			// Gan gia tri Don vi
			$this->getObject()->setPsCustomerId(myUser::getPscustomerID());
			
			// Khai bao nay can thay doi tren moi form khac nhau
			$this->validatorSchema['ps_customer_id'] = new sfValidatorInteger(array(
					'required' => true
			));
		}
	
	}

	/**
	 * addPsCustomerFormUser($ps_ward_id) - Hien thi select customer, danh cho man hinh Them moi/ Sua nguoi dung
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param        	
	 *
	 * @return widgetSchema list PsCustomer
	 *        
	 */
	protected function addPsCustomerFormUser($ps_ward_id = null, $function_code = null) {

		if (myUser::credentialPsCustomers($function_code)) { // Neu co quyen thay doi truong hoc
			
			if ($this->getObject()->isNew()) {
				
				if ($ps_ward_id <= 0) {
					$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
							'choices' => array(
									'' => _('-Select customer-')
							)
					));
				} else {
					$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
							'model' => 'PsCustomer',
							'query' => Doctrine::getTable('PsCustomer')->setCustomersByPsWardId($ps_ward_id),
							'add_empty' => _('-Select customer-')
					));
				}
			} else {
				
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
						'choices' => array(
								$this->getObject()->getPsCustomerId() => $this->getObject()
									->getPsCustomer()
									->getSchoolCode() . '-' . $this->getObject()
									->getPsCustomer()
									->getSchoolName()
						)
				));
			}
			
			$this->widgetSchema['ps_customer_id']->setAttributes(array(
					'class' => 'select2',
					'required' => 'required'
			));
			
			$this->validatorSchema['ps_customer_id'] = new sfValidatorInteger(array(
					'required' => true
			));
		} else { // Trai lai xet cho nguoi dung quan tri thong thuong
			
			$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(null, myUser::getPscustomerID()),
					'add_empty' => false
			));
			
			$this->widgetSchema['ps_customer_id']->setAttributes(array(
					'class' => 'form-control'
			));
			
			// Gan gia tri Don vi
			$this->getObject()->setPsCustomerId(myUser::getPscustomerID());
			
			// Khai bao nay can thay doi tren moi form khac nhau
			$this->validatorSchema['ps_customer_id'] = new sfValidatorInteger(array(
					'required' => true
			));
		}
	
	}
	
	/**
	 * set ps_customer_id for form
	 * Thiet lap form an ps_customer_id
	 *
	 * @author Nguyen Chien Thang
	 *
	 * @return void
	 */
	protected function setPsCustomerFormHidden() {
		
		if ($this->getObject()->isNew()) {
			
			$psHeaderFilter = sfContext::getInstance ()->getUser ()->getAttribute ( 'psHeaderFilter', null, 'admin_module' );
			
			if (!$psHeaderFilter) {
				$ps_customer_id = sfContext::getInstance ()->getUser ()->getPsCustomerId ();
			} else {
				$ps_customer_id = $psHeaderFilter ['ps_customer_id'];
			}
			
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
			
		} else {
			$ps_customer_id = $this->getObject()->getPsCustomerId();
		}
		
		$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();
		
		$this->validatorSchema ['ps_customer_id'] = new sfValidatorChoice ( array (
				'choices' => array ($ps_customer_id),
				'required' => true
		) );
	}

	/**
	 * addPsCustomerFormNotEdit($function_code) - Hien thi select customer
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param        	
	 *
	 * @return widgetSchema list PsCustomer
	 */
	protected function addPsCustomerFormNotEdit($function_code = null) {

		if (myUser::credentialPsCustomers($function_code)) { // Neu co quyen thay doi truong hoc
			
			if ($this->getObject()->isNew()) {
				
				$ps_customer_active = PreSchool::ACTIVE; // Lay nhung truong hoc dang hoat dong
				
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers($ps_customer_active, null),
						'add_empty' => _('-Select customer-')
				));
				
				$this->validatorSchema['ps_customer_id'] = new sfValidatorDoctrineChoice(array(
						'model' => 'PsCustomer',
						'required' => true,
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers($ps_customer_active, null)
				));
			} else {
				
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(null, $this->getObject()
							->getPsCustomerId()),
						'add_empty' => false
				));
				
				$this->setDefault('ps_customer_id', $this->getObject()
					->getPsCustomerId());
				
				$this->validatorSchema['ps_customer_id'] = new sfValidatorDoctrineChoice(array(
						'model' => 'PsCustomer',
						'required' => true,
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(null, $this->getObject()
							->getPsCustomerId())
				));
			}
			
			$this->widgetSchema['ps_customer_id']->setAttributes(array(
					'class' => 'select2',
					'required' => true
			));
		} else { // Trai lai xet cho nguoi dung quan tri thong thuong
			
			if ($this->getObject()->isNew()) {
				
				$ps_customer = Doctrine::getTable('PsCustomer')->findOneById(myUser::getPscustomerID());
				
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
						'choices' => array(
								$ps_customer->getId() => $ps_customer->getSchoolCode() . '-' . $ps_customer->getSchoolName()
						)
				));
				
				$this->validatorSchema['ps_customer_id'] = new sfValidatorChoice(array(
						'choices' => array(
								myUser::getPscustomerID()
						)
				));
				
				$this->setDefault('ps_customer_id', $ps_customer->getId());
			} else {
				
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
						'choices' => array(
								$this->getObject()->getPsCustomerId() => $this->getObject()
									->getPsCustomer()
									->getSchoolCode() . '-' . $this->getObject()
									->getPsCustomer()
									->getSchoolName()
						)
				));
				
				$this->validatorSchema['ps_customer_id'] = new sfValidatorChoice(array(
						'choices' => array(
								$this->getObject()->getPsCustomerId()
						)
				));
				
				$this->setDefault('ps_customer_id', $this->getObject()
					->getPsCustomerId());
			}
			
			$this->widgetSchema['ps_customer_id']->setAttributes(array(
					'class' => 'form-control',
					'required' => true
			));
		}
	
	}

	/**
	 * addPsCustomerFormEdit($function_code) - Hien thi select customer
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param        	
	 *
	 * @return widgetSchema list PsCustomer
	 *        
	 */
	protected function addPsCustomerFormEdit($function_code = null) {

		if (myUser::credentialPsCustomers($function_code)) { // Neu co quyen thay doi truong hoc
			
			if ($this->getObject()->isNew()) {
				
				$ps_customer_active = PreSchool::ACTIVE; // Lay nhung truong hoc dang hoat dong
				
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers($ps_customer_active, null),
						'add_empty' => _('-Select customer-')
				));
				
				$this->validatorSchema['ps_customer_id'] = new sfValidatorDoctrineChoice(array(
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers($ps_customer_active, null)
				));
			} else {
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(null, $this->getObject()
							->getPsCustomerId()),
						'add_empty' => _('-Select customer-')
				));
				
				$this->validatorSchema['ps_customer_id'] = new sfValidatorDoctrineChoice(array(
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(null, $this->getObject()
							->getPsCustomerId())
				));
				
				$this->setDefault('ps_customer_id', $this->getObject()->getPsCustomerId());
			}
			
			$this->widgetSchema['ps_customer_id']->setAttributes(array(
					'class' => 'select2',
					'required' => true
			));
		} else { // Trai lai xet cho nguoi dung quan tri thong thuong
			
			$ps_customer = Doctrine::getTable('PsCustomer')->findOneById(myUser::getPscustomerID());
			
			$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
					'choices' => array(
							$ps_customer->getId() => $ps_customer->getSchoolCode() . '-' . $ps_customer->getSchoolName()
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
			
			$this->setDefault('ps_customer_id', $ps_customer->getId());
		}
	
	}
	
	// Add field ps_customer_id in form
	protected function addVirtualPsCustomerField($ps_ward_id = null, $ps_customer, $by_ps_ward_id = true, $function_code = null) {

		if (myUser::credentialPsCustomers($function_code)) {
			
			if ($this->getObject()->isNew()) {
				
				$ps_customer_active = PreSchool::ACTIVE; // Chi lay cac truong dang hoat dong
				
				if ($ps_ward_id <= 0 && $by_ps_ward_id) {
					
					$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
							'choices' => array(
									'' => _('-Select customer-')
							)
					), array(
							'class' => 'select2',
							'style' => "min-width:200px;",
							'data-placeholder' => _('-Select customer-')
					));
				} else {
					$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
							'model' => 'PsCustomer',
							'query' => Doctrine::getTable('PsCustomer')->setCustomersByPsWardId($ps_ward_id, $ps_customer_active),
							'add_empty' => _('-Select customer-')
					));
				}
			} else {
				
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
						'choices' => array(
								$ps_customer->getId() => $ps_customer->getSchoolCode() . '-' . $ps_customer->getSchoolName()
						)
				));
				
				$this->widgetSchema['ps_customer_id']->setAttributes(array(
						'class' => 'select2',
						'required' => true
				));
			}
		} else {
			
			$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
					'choices' => array(
							$ps_customer->getId() => $ps_customer->getSchoolCode() . '-' . $ps_customer->getSchoolName()
					)
			));
			
			$this->widgetSchema['ps_customer_id']->setAttributes(array(
					'class' => 'select2',
					'required' => true
			));
		}
		
		$this->validatorSchema['ps_customer_id'] = new sfValidatorInteger(array(
				'required' => true
		));
	
	}

	protected function addVirtualPsCustomerForm($ps_ward_id = null, $ps_customer_id = null, $by_ps_ward_id = true, $function_code = null) {

		if (myUser::credentialPsCustomers($function_code)) {
			
			if ($this->getObject()->isNew()) {
				
				$ps_customer_active = PreSchool::ACTIVE; // Chi lay cac truong dang hoat dong
				
				if ($ps_ward_id <= 0 && $by_ps_ward_id) {
					$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
							'choices' => array(
									'' => _('-Select customer-')
							)
					), array(
							'class' => 'select2',
							'style' => "min-width:200px;",
							'data-placeholder' => _('-Select customer-')
					));
				} else {
					$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
							'model' => 'PsCustomer',
							'query' => Doctrine::getTable('PsCustomer')->setCustomersByPsWardId($ps_ward_id, $ps_customer_active),
							'add_empty' => _('-Select customer-')
					));
				}
			} else {
				
				$ps_customer = Doctrine::getTable('PsCustomer')->findOneBy('id', $ps_customer_id);
				
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
						'choices' => array(
								$ps_customer_id => $ps_customer->getSchoolCode() . '-' . $ps_customer->getSchoolName()
						)
				));
				
				$this->widgetSchema['ps_customer_id']->setAttributes(array(
						'class' => 'select2',
						'required' => true
				));
			}
		} else {
			
			$ps_customer = Doctrine::getTable('PsCustomer')->findOneBy('id', myUser::getPscustomerID());
			
			$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
					'choices' => array(
							$ps_customer->getId() => $ps_customer->getSchoolCode() . '-' . $ps_customer->getSchoolName()
					)
			));
			
			$this->widgetSchema['ps_customer_id']->setAttributes(array(
					'class' => 'select2',
					'required' => true
			));
		}
		
		$this->validatorSchema['ps_customer_id'] = new sfValidatorInteger(array(
				'required' => true
		));
	
	}
	
	// Ham su dung khi thiet lap select PsCustomer ao va khong cho thay doi gia tri khi sua
	protected function addVirtualPsCustomerFormNotEdit($ps_ward_id, $ps_customer_id = null, $function_code = null) {

		if (myUser::credentialPsCustomers($function_code)) { // Neu co quyen thay doi truong hoc
			
			if ($this->getObject()->isNew()) {
				
				$ps_customer_active = PreSchool::ACTIVE; // Lay nhung truong hoc dang hoat dong
				
				if ($ps_ward_id <= 0) {
					$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
							'choices' => array(
									'' => _('-Select customer-')
							)
					), array(
							'class' => 'select2',
							'style' => "min-width:200px;",
							'data-placeholder' => _('-Select customer-')
					));
				} else {
					$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
							'model' => 'PsCustomer',
							'query' => Doctrine::getTable('PsCustomer')->setCustomersByPsWardId($ps_ward_id, $ps_customer_active),
							'add_empty' => _('-Select customer-')
					));
				}
			} else {
				
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(null),
						'add_empty' => true
				));
				
				// $this->setDefault('ps_customer_id', $ps_customer_id);
			}
			
			$this->widgetSchema['ps_customer_id']->setAttributes(array(
					'class' => 'select2',
					'required' => true
			));
		} else { // Trai lai xet cho nguoi dung quan tri thong thuong
			/*
			 * $this->widgetSchema ['ps_customer_id'] = new sfWidgetFormChoice ( array (
			 * 'choices' => array ($this->getObject()->getPsCustomerId() => $this->getObject()->getPsCustomer()->getSchoolCode(). '-'.$this->getObject()->getPsCustomer()->getSchoolName())
			 * ));
			 * $this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array (
			 * 'model' => 'PsCustomer',
			 * 'query' => Doctrine :: getTable('PsCustomer')->setSQLCustomers(null, myUser::getPscustomerID()),
			 * 'add_empty' => false
			 * ));
			 * $this->widgetSchema['ps_customer_id']->setAttributes(array('class' => 'select2', 'required' => true));
			 */
		}
	
	}

	protected function addVirtualPsCustomerIdFormNotEdit($function_code = null, $ps_customer = null) {

		if (! $this->getObject()->isNew()) {
			
			$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
					'choices' => array(
							$ps_customer->getId() => $ps_customer->getSchoolCode() . '-' . $ps_customer->getSchoolName()
					)
			));
			
			$this->validatorSchema['ps_customer_id'] = new sfValidatorChoice(array(
					'choices' => array(
							$ps_customer->getId()
					)
			));
			
			$this->widgetSchema['ps_customer_id']->setAttributes(array(
					'class' => 'form-control',
					'required' => true
			));
		} else {
			
			if (myUser::credentialPsCustomers($function_code)) {
				
				$ps_customer_active = PreSchool::ACTIVE; // Lay nhung truong hoc dang hoat dong
				
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers($ps_customer_active),
						'add_empty' => _('-Select customer-')
				));
				
				$this->validatorSchema['ps_customer_id'] = new sfValidatorDoctrineChoice(array(
						'model' => 'PsCustomer',
						'required' => true
				));
			} else {
				
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
						'choices' => array(
								$ps_customer->getId() => $ps_customer->getSchoolCode() . '-' . $ps_customer->getSchoolName()
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
				
				$this->setDefault('ps_customer_id', $ps_customer->getId());
			}
		}
	
	}
	
	// Hien thi danh sach Nhan su cua don vi
	public function addUsersExpandedForm($keyCol = 'users_list', $psCustomerId = null) {

		$query = Doctrine::getTable('sfGuardUser')->loadUsersTeacher($psCustomerId);
		
		$this->widgetSchema[$keyCol]->setOption('query', $query);
		
		$this->widgetSchema[$keyCol]->setOption('expanded', false);
	
	}

	public function addGroupExpandedForm($keyCol = 'groups_list', $psCustomerId = null) {

		$query = Doctrine::getTable('sfGuardGroup')->setSQLGroupsUserByCustomer($psCustomerId);
		
		$this->widgetSchema[$keyCol]->setOption('query', $query);
		$this->widgetSchema[$keyCol]->setOption('expanded', true);
	
	}
	
	// Custom user_list for form
	protected function addUsersForm($keyCol = 'users_list', $psCustomerId = null) {

		/*
		 * $this->widgetSchema[$keyCol] = new sfWidgetFormSelectDoubleList(array (
		 * 'choices' => (myUser::getPscustomerID()== null ) ? Doctrine :: getTable('sfGuardUser')->findAll() : Doctrine :: getTable('sfGuardUser')->findBy('ps_customer_id', myUser::getPscustomerID()),
		 * 'associated_first' => true,
		 * 'label_associated' => sfContext::getInstance()->getI18n()->__('Associated'),
		 * 'label_unassociated' => sfContext::getInstance()->getI18n()->__('Unassociated')
		 * ));
		 */
		
		// $this->widgetSchema['users_list']->setOption('renderer_class', 'sfWidgetFormSelectDoubleList');
		$query = Doctrine::getTable('sfGuardUser')->createQuery()->select('*');
		
		if ($psCustomerId > 0)
			$query->addWhere('ps_customer_id = ?', $psCustomerId);
		
		$associated_first = true;
		
		if ($associated_first) {
			$associate_image = 'previous.png';
			$unassociate_image = 'next.png';
			$float = 'left';
		} else {
			$associate_image = 'next.png';
			$unassociate_image = 'previous.png';
			$float = 'right';
		}
		
		$this->widgetSchema[$keyCol] = new sfWidgetFormDoctrineChoice(array(
				'model' => 'sfGuardUser',
				// 'table_method' => 'doUsesList',
				'query' => $query,
				'add_empty' => false,
				'renderer_class' => 'sfWidgetFormSelectDoubleList',
				'renderer_options' => array(
						'associated_first' => $associated_first,
						'label_associated' => sfContext::getInstance()->getI18n()->__('Associated'),
						'label_unassociated' => sfContext::getInstance()->getI18n()->__('Unassociated'),
						'unassociate' => '<img src="' . sfContext::getInstance()->getRequest()->getRelativeUrlRoot() . '/images2/16/' . $unassociate_image . '" alt="&raquo;"',
						'associate' => '<img src="' . sfContext::getInstance()->getRequest()->getRelativeUrlRoot() . '/images2/16/' . $associate_image . '" alt="&laquo;" />'
				)
		)) // 'template' => $styleLayout,
;
		
		/*
		 * $this->validatorSchema[$keyCol] = new sfValidatorDoctrineChoice(array (
		 * 'multiple' => true,
		 * 'model' => 'sfGuardUser',
		 * 'query' => $query,
		 * 'required' => false
		 * ));
		 */
		
		$this->widgetSchema[$keyCol]->setAttributes(array(
				'class' => 'form-control'
		));
	
	}
	
	// Custom user_list for form
	protected function addPermissionsForm($keyCol = 'permissions_list', $groupId = null) {

		$sql_guard_permissions = Doctrine::getTable('sfGuardPermission')->createQuery('Gp')->select('Gp.id AS id, Gp.title AS name,Gp.ps_app_id, Ap.id AS app_permission_id');
		
		$sql_guard_permissions->innerJoin('Gp.PsAppPermission Ap');
		
		$sql_guard_permissions->innerJoin('Ap.PsApp App');
		
		if (! myUser::isAdministrator())
			$sql_guard_permissions->addWhere('Ap.is_system <> 1');
			
			// $query->orderBy('Gp.ps_app_id, iorder');
		$sf_guard_permissions = $sql_guard_permissions->execute();
		
		$queryPsApp = Doctrine::getTable('PsApp')->createQuery()
			->select('id, title')
			->where('ps_app_root IS NOT NULL');
		
		if (! myUser::isAdministrator())
			$queryPsApp->addWhere('is_system <> 1');
		
		$queryPsApp->addWhere('is_activated =?', PreSchool::ACTIVE);
		
		$queryPsApp->orderBy('ps_app_root,iorder,is_system');
		
		$queryPsApp = $queryPsApp->execute();
		
		$chois = null;
		foreach ($queryPsApp as $key => $app) {
			foreach ($sf_guard_permissions as $sf_guard_permission) {
				if ($app->getId() == $sf_guard_permission->getPsAppId()) {
					$chois[$app->getTitle()][$sf_guard_permission->getId()] = $sf_guard_permission->getName();
				}
			}
		}
		
		$this->widgetSchema[$keyCol] = new sfWidgetFormChoice(array(
				'choices' => $chois,
				'multiple' => true,
				'expanded' => true,
				'renderer_options' => array(
						// 'template' => '<strong class="app-group">%group%</strong> %options%'
						'template' => '<div style="min-height:220px;" class="col-xs-12 col-sm-12 col-md-4 col-lg-4"><div class="col-md-12"><strong class="app-group">%group%</strong> %options%</div></div>'
				)
		));
	
	}
	
	// Load dich vu cua nha truong theo $psCustomerId
	public function addServiceExpandedForm($keyCol = 'services_list', $psCustomerId = null, $expanded = true) {

		$query = Doctrine::getTable('Service')->loadServices($psCustomerId, true);
		
		$this->widgetSchema[$keyCol]->setOption('query', $query);
		$this->widgetSchema[$keyCol]->setOption('expanded', $expanded);
		$this->widgetSchema[$keyCol]->setOption('multiple', true);
	
	}
	
	// Load dich vu cua nha truong theo params: $psCustomerId, school_year_id, ps_obj_group_id, ps_workplace_id
	public function addServicesForm($keyCol = 'services_list', $params = array(), $expanded = true) {

		$query = Doctrine::getTable('Service')->loadServices($psCustomerId, true);
		
		$this->widgetSchema[$keyCol]->setOption('query', $query);
		$this->widgetSchema[$keyCol]->setOption('expanded', $expanded);
		$this->widgetSchema[$keyCol]->setOption('multiple', true);
	
	}

	public function addMembersExpandedForm($keyCol = 'ps_memberes_list', $psCustomerId = null) {

		$query = Doctrine::getTable('PsMember')->loadMembers($psCustomerId);
		
		$this->widgetSchema[$keyCol]->setOption('query', $query);
		$this->widgetSchema[$keyCol]->setOption('expanded', false);
		$this->widgetSchema[$keyCol]->setOption('multiple', false);
	
	}

	public function baseUpdateObject($values = null) {

		$object = parent::updateObject($values);
		
		$userId = sfContext::getInstance()->getUser()
			->getGuardUser()
			->getId();
		
		if ($this->getObject()->isNew()) {
			$object->setUserCreatedId($userId);
			$object->setUserUpdatedId($userId);
		} else {
			$object->setUserUpdatedId($userId);
			$currentDateTime = new PsDateTime();
			$object->setUpdatedAt($currentDateTime->getCurrentDateTime());
		}
		
		return $object;
	
	}

	/*
	 * public function updateObject($values = null) {
	 * $object = parent :: updateObject($values);
	 * if ($this->getObject()->isNew()) {
	 * $object->setUserCreatedId(sfContext :: getInstance()->getUser()->getGuardUser()->getId());
	 * $object->setUserUpdatedId(sfContext :: getInstance()->getUser()->getGuardUser()->getId());
	 * } else {
	 * $object->setUserUpdatedId(sfContext :: getInstance()->getUser()->getGuardUser()->getId());
	 * }
	 * if (isset ($this->widgetSchema['user_created_id']) && isset ($this->widgetSchema['user_updated_id'])) {
	 * if ($this->getObject()->isNew()) {
	 * $object->setUserCreatedId(sfContext :: getInstance()->getUser()->getGuardUser()->getId());
	 * $object->setUserUpdatedId(sfContext :: getInstance()->getUser()->getGuardUser()->getId());
	 * } else {
	 * $object->setUserUpdatedId(sfContext :: getInstance()->getUser()->getGuardUser()->getId());
	 * }
	 * }
	 * if ($this->getObject()->isNew()) {
	 * $object->set('user_created_id', sfContext :: getInstance()->getUser()->getGuardUser()->getId());
	 * $object->set('user_updated_id', sfContext :: getInstance()->getUser()->getGuardUser()->getId());
	 * } else {
	 * $object->set('user_updated_id', sfContext :: getInstance()->getUser()->getGuardUser()->getId());
	 * }
	 * return $object;
	 * }
	 */
	
	/**
	 * Set control in form used style css bootstrap and required fields as mandatory
	 *
	 * @author Thangnc
	 * @param string $inputForm
	 *        	The key in form
	 *        	
	 * @param
	 *        	void
	 */
	protected function addBootstrapForm($inputForm = null) {

		if ($inputForm == null) {
			// Set required fields as mandatory - add at the end of the configure() method
			foreach ($this->getFormFieldSchema()
				->getWidget()
				->getFields() as $key => $object) {
				
				if ($this->widgetSchema[$key]->getOption('type') == 'text' || $this->widgetSchema[$key]->getOption('type') == 'email' || $this->widgetSchema[$key]->getOption('type') == 'url' || $this->widgetSchema[$key]->getOption('type') == 'number' || $this->widgetSchema[$key]->getOption('type') == 'search' || $this->widgetSchema[$key]->getOption('type') == 'tel') {
					
					$currAttributeBirthday = $this->widgetSchema[$key]->getAttributes();
					
					if (isset($currAttributeBirthday['class']))
						
						$currAttributeBirthday['class'] = 'form-control ' . $currAttributeBirthday['class'];
					else
						$currAttributeBirthday['class'] = 'form-control';
					
					$this->widgetSchema[$key]->setAttributes($currAttributeBirthday);
				} elseif ($this->widgetSchema[$key]->getOption('type') == 'radio') {
					
					$this->widgetSchema[$key]->setAttributes(array(
							'class' => 'radiobox'
					));
				} elseif ($this->widgetSchema[$key]->getOption('type') == 'checkbox') {
					
					$this->widgetSchema[$key]->setAttributes(array(
							'class' => 'checkbox'
					));
				} elseif ($this->widgetSchema[$key]->getOption('model') != '' && ! $this->widgetSchema[$key]->getOption('expanded')) {
					$this->widgetSchema[$key]->setAttributes(array(
							'class' => 'select2'
					));
				}
				
				$label = $this->getFormFieldSchema()
					->offsetGet($key)
					->renderLabelName();
				
				if ($this->validatorSchema[$key] && ($this->validatorSchema[$key]->getOption('required') == true)) {
					
					$this->widgetSchema->setLabel($key, $label . '<span class="required"> *</span>');
					
					$currAttribute = $this->widgetSchema[$key]->getAttributes();
					
					$currAttribute['required'] = 'required';
					
					$this->widgetSchema[$key]->setAttributes($currAttribute);
				}
				
				if ($this->validatorSchema[$key] && $this->validatorSchema[$key]->getOption('max_length') > 0) {
					
					$currAttribute = $this->widgetSchema[$key]->getAttributes();
					
					$currAttribute['maxlength'] = $this->validatorSchema[$key]->getOption('max_length');
					
					$this->widgetSchema[$key]->setAttributes($currAttribute);
				}
			}
		} else {
			if ($this->validatorSchema[$inputForm]->getOption('type') == 'text') {
				$this->widgetSchema[$inputForm]->setAttributes('class', 'form-control');
			}
			
			if ($this->validatorSchema[$inputForm]->getOption('max_length') > 0) {
				
				$currAttribute = $this->widgetSchema[$inputForm]->getAttributes();
				
				$currAttribute['maxlength'] = $this->validatorSchema[$inputForm]->getOption('max_length');
				
				$this->widgetSchema[$inputForm]->setAttributes($currAttribute);
			}
		}
	
	}

	public function setPsWardIdForm() {

	
	}

	/**
	 * Ham tao danh sach co so cua 1 truong hoc
	 *
	 * @author Nguyen Chien Thang
	 * @version 2.0
	 *         
	 * @param
	 *        	ps_customer_id - ID truong
	 *        	
	 * @return void
	 */
	protected function addPsWorkplaceIdForm($ps_customer_id, $ps_workplace_id, $required = false) {

		if ($ps_customer_id > 0) {
			
			if (myUser::getUser()->getUserType() == PreSchool::USER_TYPE_MANAGER) { // Neu la Use So/Phong
				
				if (myUser::getUser()->getDepartmentType() == PreSchool::MANAGER_TYPE_PROVINCIAL) { // Neu la Use So/Phong
					                                                                                        
						// Lay cac co so dao tao trong tinh
						$ps_province_id = myUser::getUser()->getMember()->getPsProvinceId();
						$query = Doctrine::getTable('PsWorkPlaces')->sqlListByWhere(null, $ps_province_id, PreSchool::ACTIVE);
					} elseif (myUser::getUser()->getDepartmentType() == PreSchool::MANAGER_TYPE_DISTRICT) {
						// Lay cac co so dao tao trong quan/huyen
						$ps_province_id = myUser::getUser()->getMember()->getPsProvinceId();
						$ps_district_id = myUser::getUser()->getMember()->getPsDistrictId();
						$query = Doctrine::getTable('PsWorkPlaces')->sqlListByWhere($ps_district_id, $ps_province_id, PreSchool::ACTIVE);
					}
				} else {
					$query = Doctrine::getTable('PsWorkPlaces')->sqlGetLisstByCustomerId($ps_customer_id, $ps_workplace_id, PreSchool::ACTIVE);
				}
				
				// ps_workplace_id filter by ps_customer_id
				$this->widgetSchema['ps_workplace_id'] = new sfWidgetFormDoctrineChoice(array(
						'model' => 'PsWorkPlaces',
						'query' => $query,
						'add_empty' => '-Select workplace-'
				), array(
						'class' => 'select2',
						'style' => "min-width:200px;",
						'data-placeholder' => _('-Select workplace-')
				));
				
				$this->validatorSchema['ps_workplace_id'] = new sfValidatorDoctrineChoice(array(
						'required' => $required,
						'model' => 'PsWorkPlaces',
						'column' => 'id'
				));
			
		} else {
				
				$this->widgetSchema['ps_workplace_id'] = new sfWidgetFormChoice(array(
						'choices' => array(
								'' => _('-Select workplace-')
						)
				), array(
						'class' => 'select2',
						'style' => "min-width:200px;",
						'data-placeholder' => _('-Select workplace-')
				));
				
				$this->validatorSchema['ps_workplace_id'] = new sfValidatorPass(array(
						'required' => $required
				));
			}	
	}

	/**
	 * Ham tao danh sach thang-nam cua 1 năm hoc
	 * 
	 * @author Nguyen Chien Thang
	 * @version 2.0
	 *         
	 * @param $ps_school_year_id -
	 *        	int, ID năm học
	 * @return void
	 */
	protected function addPsYearMonthForm($ps_school_year_id) {

		$schoolYearsDefault = Doctrine::getTable('PsSchoolYear')->findOneById($ps_school_year_id);
		
		$yearsDefaultStart = date("Y-m", strtotime($schoolYearsDefault->getFromDate()));
		
		$yearsDefaultEnd = date("Y-m", strtotime($schoolYearsDefault->getToDate()));
		
		$this->widgetSchema['ps_year_month'] = new sfWidgetFormChoice(array(
				'choices' => array(
						'' => _('-Select month-')
				) + PsDateTime::psRangeYYYYMM($yearsDefaultStart, $yearsDefaultEnd)
		), array(
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _('-Select month-'),
				'rel' => 'tooltip',
				'data-original-title' => _('Select month')
		));
		
		$this->validatorSchema['ps_year_month'] = new sfValidatorPass(array(
				'required' => true
		));
	
	}

}