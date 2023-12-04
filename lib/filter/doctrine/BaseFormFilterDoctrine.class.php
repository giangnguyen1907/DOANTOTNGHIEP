<?php
/**
 * Project filter form base class.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterBaseTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class BaseFormFilterDoctrine extends sfFormFilterDoctrine {
	public function setup() {
		$this->removeFields ();
		$this->setIsActivatedWidgetSchemaFormFilter ();
		$this->removeEmptyFormFilter ();
		$this->addBootstrapFilter ();
	}
	protected function removeFields() {
		unset ( $this ['iorder'], $this ['created_at'], $this ['updated_at'], $this ['user_created_id'], $this ['user_updated_id'] );
	}

	/**
	 * removeEmptyFormFilter() - Remove is_empty in form filters
	 *
	 * Add by Nguyen Chien Thang
	 */
	protected function removeEmptyFormFilter() {
		foreach ( $this->getFields () as $key => $fieldType ) {
			if (isset ( $this->widgetSchema [$key] ) && $this->getWidget ( $key )->getOption ( 'with_empty' ))
				$this->getWidget ( $key )->setOption ( 'with_empty', false );
		}
	}

	/**
	 * auto set value for iorder of table model
	 */
	protected function setIsActivatedWidgetSchemaFormFilter($keyActivated = 'is_activated') {
		if (isset ( $this->widgetSchema [$keyActivated] )) {
			$this->widgetSchema [$keyActivated] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => '-Select state-'
					) + PreSchool::loadPsActivity ()
			) );
			$this->validatorSchema [$keyActivated] = new sfValidatorInteger ( array (
					'required' => false
			) );
		}

		return;
	}

	/**
	 * Tao select date, bo sung tien ich chon ngay thang nam *
	 */
	protected function setDateWidgetSchemaFormFilter($keyField, $format = '%day%/%month%/%year%') {
		if (isset ( $this->widgetSchema [$keyField] )) {
			$years = range ( date ( 'Y' ), sfConfig::get ( 'app_start_year' ) );
			$Year = sfContext::getInstance ()->getI18n ()->__ ( 'Year' );
			$Month = sfContext::getInstance ()->getI18n ()->__ ( 'Month' );
			$Day = sfContext::getInstance ()->getI18n ()->__ ( 'Day' );

			$this->widgetSchema [$keyField] = new sfWidgetFormJQueryDate ( array (
					'date_widget' => new sfWidgetFormDate ( array (
							'empty_values' => array (
									'year' => '',
									'month' => '',
									'day' => ''
							),
							'format' => $format,
							'years' => array_combine ( $years, $years )
					) ),
					'config' => '{changeMonth: true, changeYear: true}',
					'image' => sfContext::getInstance ()->getRequest ()->getRelativeUrlRoot () . "/images2/calendar-icon.png"
			) );
			$this->validatorSchema [$keyField] = new sfValidatorInteger ( array (
					'required' => false
			) );
		}
	}

	/**
	 * addI18nChoiceCountryFormFilter()
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param
	 *        	string - input form
	 * @return void
	 *
	 */
	protected function addI18nChoiceCountryFormFilter($inputText = 'country_code') {
		$culture = sfContext::getInstance ()->getUser ()->getCulture ();

		$this->widgetSchema [$inputText] = new sfWidgetFormI18nChoiceCountry ( array (
				'culture' => $culture,
				'add_empty' => '-Select country-'
		), array (
				'class' => 'select2',
				'style' => "min-width:200px;"
		) );
	}

	/**
	 * addPsCustomerFormFilter()
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param $is_activated -
	 *        	flag active
	 * @param $cId -
	 *        	id
	 * @param $function_code string
	 *        	OR array - Ma quyen filters truong hoc
	 *        	
	 * @return void
	 */
	protected function addPsCustomerFormFilter($function_code = null, $required = false) {
		
		$is_role = false;
		//echo $function_code;die;
		if (! is_array ( $function_code )) {
			$is_role = myUser::credentialPsCustomers ( $function_code );
		} else {
			foreach ( $function_code as $code ) {
				if (myUser::credentialPsCustomers ( $code )) {
					$is_role = true;
					break;
				}
			}
		}

		if (! $is_role) { // Neu ko co quyen loc du lieu chuc nang theo truong

			$psHeaderFilter = sfContext::getInstance ()->getUser ()->getAttribute ( 'psHeaderFilter', null, 'admin_module' );
			
			if (!$psHeaderFilter) {
				$ps_customer_id = myUser::getPscustomerID ();
			} else {
				$ps_customer_id = $psHeaderFilter ['ps_customer_id'];
			}

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorChoice ( array (
					'choices' => array ($ps_customer_id),
					'required' => true
			) );
			
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
			
		} else {
			//echo 'AAAAAA';die;
			$query = Doctrine::getTable ( 'PsCustomer' )->setSQLPsCustomerByParams ();

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => $query,
					'add_empty' => _ ( '-All school-' )
			), array (
					'style' => 'min-width:250px;width:100%;',
					'class' => 'select2'
			) );

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => $required,
					'model' => 'PsCustomer',
					'query' => $query,
					'column' => 'id'
			), array (
					'invalid' => _ ( 'Customer Invalid' )
			) );

			/*
			 * $this->validatorSchema ['ps_customer_id'] = new sfValidatorChoice ( array (
			 * 'choices' => array(myUser::getPscustomerID ()),
			 * 'required' => true
			 * ), array ('invalid' => _('Customer Invalid')) );
			 */
		}
	}

	/**
	 * addPsCustomerNotNullFormFilter()
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @return void
	 */
	protected function addPsCustomerNotNullFormFilter() {
		if (! myUser::credentialPsCustomers ()) { // Neu ko co quyen loc du lieu theo truong
			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();
			$this->setDefault ( 'ps_customer_id', myUser::getPscustomerID () );
			$this->validatorSchema ['ps_customer_id'] = new sfValidatorInteger ( array (
					'required' => true
			) );
		} else {

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( null ),
					'add_empty' => false
			), array (
					'style' => 'min-width:250px;width:100%;',
					'class' => 'select2'
			) );

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'PsCustomer',
					'column' => 'id'
			) );
		}
	}

	/**
	 * addPsCustomerFormFilterByWard()
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param $remove -
	 *        	flag show form
	 * @param $is_activated -
	 *        	flag active
	 * @param $cId -
	 *        	id
	 *        	
	 * @return void
	 */
	protected function addPsCustomerFormFilterByWard($ps_ward_id = null, $function_code = null) {
		if (! myUser::credentialPsCustomers ( $function_code )) { // Neu ko co quyen loc du lieu theo truong
			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();
			$this->setDefault ( 'ps_customer_id', myUser::getPscustomerID () );
			$this->validatorSchema ['ps_customer_id'] = new sfValidatorInteger ( array (
					'required' => true
			) );
		} else {

			if ($ps_ward_id > 0) {
				$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable ( 'PsCustomer' )->setCustomersByPsWardId ( $ps_ward_id ),
						'add_empty' => _ ( '-Select customer-' )
				), array (
						'style' => 'min-width:250px;width:100%;',
						'class' => 'select2'
				) );

				$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
						'required' => false,
						'model' => 'PsCustomer',
						'column' => 'id'
				) );
			} else {
				$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormChoice ( array (
						'choices' => array (
								'' => _ ( '-Select customer-' )
						)
				), array (
						'class' => 'select2',
						'style' => "min-width:200px;",
						'data-placeholder' => _ ( '-Select customer-' )
				) );
			}
			$this->validatorSchema ['ps_customer_id'] = new sfValidatorInteger ( array (
					'required' => false
			) );
		}
	}
	
	protected function addVirtualPsCustomerIdFormFilter($function_code = null) {
		if (! myUser::credentialPsCustomers ( $function_code )) { // Neu ko co quyen loc du lieu theo truong
			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();
			$this->setDefault ( 'ps_customer_id', myUser::getPscustomerID () );
			$this->validatorSchema ['ps_customer_id'] = new sfValidatorInteger ( array (
					'required' => true
			) );
		} else {

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( null ),
					'add_empty' => false
			), array (
					'style' => 'min-width:250px;width:100%;',
					'class' => 'select2'
			) );

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'PsCustomer',
					'column' => 'id'
			) );
		}
	}

	/**
	 * Ham tao danh sach co so cua 1 truong hoc
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param
	 *        	ps_customer_id- int, ID truong
	 *        	
	 * @return void
	 */
	protected function addPsWorkplaceIdFormFilter($ps_customer_id, $ps_workplace_id = null) {
		
		if ($ps_customer_id > 0) {

			if (myUser::getUser ()->getUserType () == PreSchool::USER_TYPE_MANAGER) { // Neu la Use So/Phong

				if (myUser::getUser ()->getDepartmentType () == PreSchool::MANAGER_TYPE_PROVINCIAL) { // Neu la Use So

					// Lay cac co so dao tao trong tinh
					$ps_province_id = myUser::getUser ()->getMember ()->getPsProvinceId ();
					$query = Doctrine::getTable ( 'PsWorkPlaces' )->sqlListByWhere ( null, $ps_province_id, PreSchool::ACTIVE );
				} elseif (myUser::getUser ()->getDepartmentType () == PreSchool::MANAGER_TYPE_DISTRICT) { // Phong
				                                                                                          // Lay cac co so dao tao trong quan/huyen
					$ps_province_id = myUser::getUser ()->getMember ()->getPsProvinceId ();
					$ps_district_id = myUser::getUser ()->getMember ()->getPsDistrictId ();
					$query = Doctrine::getTable ( 'PsWorkPlaces' )->sqlListByWhere ( $ps_district_id, $ps_province_id, PreSchool::ACTIVE );
				}
			
			} else {
				$query = Doctrine::getTable ( 'PsWorkPlaces' )->sqlGetLisstByCustomerId ( $ps_customer_id, $ps_workplace_id, PreSchool::ACTIVE );
			}

			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => $query,
					'add_empty' => '-Select workplace-'
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' )
			) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'PsWorkPlaces',
					'query' => $query,
					'column' => 'id'
			) );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' )
					)
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' )
			) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorPass ( array (
					'required' => false
			) );
		}
	}
	
	/**
	 * Ham tao danh sach thang-nam cua 1 năm hoc
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param $ps_school_year_id -
	 *        	int, ID năm học
	 * @return void
	 */
	protected function addPsYearMonthFormFilter($ps_school_year_id) {
		$ps_school_year_default = sfContext::getInstance ()->getUser ()->getAttribute ( 'ps_school_year_default' );

		if ($ps_school_year_id == $ps_school_year_default->id) {

			$yearsDefaultStart = date ( "Y-m", strtotime ( $ps_school_year_default->from_date ) );

			$yearsDefaultEnd = date ( "Y-m", strtotime ( $ps_school_year_default->to_date ) );
		} else {

			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $ps_school_year_id );

			$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );

			$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );
		}

		$year_month_text = array ();
		$year_month_option = array ();

		$k = 0;
		for($i = $yearsDefaultStart; $i <= $yearsDefaultEnd; $i = date ( "Y-m", strtotime ( $yearsDefaultStart . " +" . $k . " Month" ) )) {

			$month_year = date ( "m-Y", strtotime ( $i . '-01' ) );

			array_push ( $year_month_text, $month_year );
			array_push ( $year_month_option, date ( "Ym", strtotime ( $i . '-01' ) ) );

			$k ++;
		}

		$ps_year_month_choices = array_combine ( $year_month_option, $year_month_text );

		$this->widgetSchema ['ps_year_month'] = new sfWidgetFormSelect ( array (
				'choices' => array (
						'' => _ ( '-Select month-' )
				) + $ps_year_month_choices
		), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' )
		) );

		$this->validatorSchema ['ps_year_month'] = new sfValidatorChoice ( array (
				'required' => true,
				'choices' => $year_month_option
		) );
	}

	
	/**
	 * set ps_customer_id for form filters
	 * Thiet lap form an ps_customer_id
	 *
	 * @author Nguyen Chien Thang
	 *
	 * @param $required TRUE
	 *        	OR FALSE
	 *
	 * @return void
	 */
	protected function setPsCustomerFormFilter($required = true) {
	
		$psHeaderFilter = sfContext::getInstance ()->getUser ()->getAttribute ( 'psHeaderFilter', null, 'admin_module' );
	
		if (! $psHeaderFilter) {
			$ps_customer_id = sfContext::getInstance ()->getUser ()->getPsCustomerId ();
		} else {
			$ps_customer_id = $psHeaderFilter ['ps_customer_id'];
		}
	
		$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();
	
		$this->validatorSchema ['ps_customer_id'] = new sfValidatorChoice ( array (
				'choices' => array ($ps_customer_id),
				'required' => $required
		) );
	
		$this->setDefault ( 'ps_customer_id', $ps_customer_id );
	}
	
	/**
	 * set ps_school_year_id for form filters
	 * Thiet lap form an ps_school_year_id
	 *
	 * @author Nguyen Chien Thang
	 *
	 * @param $required TRUE
	 *        	OR FALSE
	 *
	 * @return void
	 */
	protected function setPsSchoolYearFormFilter($required = true) {
		
		$psHeaderFilter = sfContext::getInstance ()->getUser ()->getAttribute ( 'psHeaderFilter', null, 'admin_module' );
		
		$ps_school_year_id = $psHeaderFilter ['ps_school_year_id'];
		
		$this->widgetSchema ['ps_school_year_id'] = new sfWidgetFormInputHidden ();
		
		$this->validatorSchema ['ps_school_year_id'] = new sfValidatorChoice ( array (
				'choices' => array ($ps_school_year_id),
				'required' => $required
		) );
		
	}
	
	/**
	 * Set control in form used style css bootstrap and required fields as mandatory
	 *
	 * @author Thangnc
	 * @param string $inputForm
	 *        	The key in form
	 *        	
	 * @return void
	 */
	protected function addBootstrapFilter() {

		// Set required fields as mandatory - add at the end of the configure() method
		foreach ( $this->getFields () as $key => $fieldType ) {

			if (isset ( $this->widgetSchema [$key] )) {

				$label = $this->getFormFieldSchema ()->offsetGet ( $key )->renderLabelName ();

				if ($fieldType != 'ForeignKey') {

					$this->widgetSchema [$key]->setAttributes ( array (
							'class' => 'form-control',
							'placeholder' => $label
					) );
				} else {
					$this->widgetSchema [$key]->setAttributes ( array (
							'style' => 'min-width:200px;',
							'class' => 'select2',
							'placeholder' => $label
					) );
				}
			}
		}

	}

}
