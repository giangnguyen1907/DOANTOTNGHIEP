<?php
/**
 * sfGuardUser filter form.
 *
 * @package    Preschool
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrinePluginFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardUserFormFilter extends PluginsfGuardUserFormFilter {

	public function configure() {

		/*
		 * $ps_ward_id = null;
		 * if (myUser::credentialPsCustomers('PS_SYSTEM_USER_FILTER_SCHOOL')) {
		 * $country_code = strtoupper ( sfConfig::get ( 'app_ps_default_country' ) );
		 * $this->widgetSchema ['ps_province_id'] = new sfWidgetFormDoctrineChoice ( array (
		 * 'model' => 'PsProvince',
		 * 'query' => Doctrine::getTable ( 'PsProvince' )->setSqlPsProvinceByCountry ( $country_code),
		 * 'add_empty' => _ ( '-Select province-' )
		 * ), array (
		 * 'class' => 'select2',
		 * 'style' => "min-width:200px;",
		 * 'data-placeholder' => _ ( '-Select province-' )
		 * ) );
		 * $this->validatorSchema ['ps_province_id'] = new sfValidatorPass ( array (
		 * 'required' => false
		 * ) );
		 * $ps_province_id = $this->getDefault ( 'ps_province_id' );
		 * if ($ps_province_id > 0) {
		 * $this->widgetSchema ['ps_district_id'] = new sfWidgetFormDoctrineChoice ( array (
		 * 'model' => 'PsProvince',
		 * 'query' => Doctrine::getTable ( 'PsDistrict' )->setSqlPsDistrictByProvinceId ( $ps_province_id ),
		 * 'add_empty' => _ ( '-Select district-' )
		 * ), array (
		 * 'class' => 'select2',
		 * 'style' => "min-width:200px;",
		 * 'data-placeholder' => _ ( '-Select district-' )
		 * ) );
		 * } else {
		 * $this->widgetSchema ['ps_district_id'] = new sfWidgetFormChoice ( array (
		 * 'choices' => array ('' => _('-Select district-'))
		 * ), array (
		 * 'class' => 'select2',
		 * 'style' => "min-width:200px;",
		 * 'data-placeholder' => _('-Select district-')
		 * ) );
		 * }
		 * $this->validatorSchema ['ps_district_id'] = new sfValidatorPass ( array (
		 * 'required' => false
		 * ) );
		 * // Xa-Phuong
		 * $ps_district_id = $this->getDefault ( 'ps_district_id' );
		 * if ($ps_district_id > 0) {
		 * $this->widgetSchema ['ps_ward_id'] = new sfWidgetFormChoice ( array (
		 * 'choices' => array (
		 * '' => _ ( '-Select Ward-' )
		 * ) + Doctrine::getTable ( 'PsWard' )->getChoicePsWard ( $ps_district_id )
		 * ), array (
		 * 'class' => 'select2',
		 * 'style' => "min-width:200px;",
		 * 'data-placeholder' => _ ( '-Select Ward-' )
		 * ) );
		 * } else {
		 * $this->widgetSchema ['ps_ward_id'] = new sfWidgetFormChoice ( array ('choices' => array ('' => _ ( '-Select Ward-' ))
		 * ), array (
		 * 'class' => 'select2',
		 * 'style' => "min-width:200px;",
		 * 'data-placeholder' => _ ( '-Select Ward-' )
		 * ) );
		 * }
		 * $this->validatorSchema ['ps_ward_id'] = new sfValidatorPass ( array (
		 * 'required' => false
		 * ) );
		 * $ps_ward_id = $this->getDefault ( 'ps_ward_id' );
		 * } else {
		 * $this->widgetSchema ['ps_province_id'] = new sfWidgetFormInputHidden();
		 * $this->widgetSchema ['ps_district_id'] = new sfWidgetFormInputHidden();
		 * $this->widgetSchema ['ps_ward_id'] = new sfWidgetFormInputHidden();
		 * $this->validatorSchema ['ps_province_id'] = new sfValidatorInteger(array('required' => false));
		 * $this->validatorSchema ['ps_district_id'] = new sfValidatorInteger(array('required' => false));
		 * $this->validatorSchema ['ps_ward_id'] = new sfValidatorInteger(array('required' => false));
		 * }
		 * // Overload ps_customer_id
		 * $this->addPsCustomerFormFilterByWard($ps_ward_id);
		 */

		// $this->addPsCustomerFormFilter('PS_SYSTEM_USER_FILTER_SCHOOL');

		/*
		 * if (!myUser::credentialPsCustomers ( 'PS_SYSTEM_USER_FILTER_SCHOOL' )) { // Neu ko co quyen loc du lieu theo truong
		 * $ps_customer_id = myUser::getPscustomerID ();
		 * $this->widgetSchema['ps_customer_id'] = new sfWidgetFormInputHidden();
		 * $this->setDefault('ps_customer_id', $ps_customer_id);
		 * } else {
		 * $ps_customer_id = null;
		 * }
		 */
		
		$this->widgetSchema['ps_customer_id']->setLabel('Province');
		
		if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_USER_FILTER_SCHOOL' )) { // Neu ko co quyen loc du lieu theo truong

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();
			$this->setDefault ( 'ps_customer_id', myUser::getPscustomerID () );
			$this->validatorSchema ['ps_customer_id'] = new sfValidatorInteger ( array (
					'required' => true ) );
		} else {

			$add_empty = myUser::isAdministrator () ? _ ( '-All Customer/School-' ) : false;
			
			$query = Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( null );

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => $query,
					'add_empty' => $add_empty ), array (
					'style' => 'min-width:250px;width:100%;',
					'class' => 'select2' ) );

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'PsCustomer',
					'query' => $query,
					'column' => 'id' ) );
		}

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id > 0) {
			
			$ps_workplace_query = Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id );

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => $ps_workplace_query,
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
					
			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkPlaces',
				'query' => $ps_workplace_query,
				'column' => 'id' ) );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'form-control',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
							
			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorPass( array (
				'required' => false ) );
		}	

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();
		
		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );
		
		if (!myUser::credentialPsCustomers ( 'PS_SYSTEM_USER_MANAGER_DEPARTMENT' ) || ! (myUser::getUser()->getUserType() == PreSchool::USER_TYPE_MANAGER)) {// Quyền quản lý Sở/Phòng
			$loadPsUserType = PreSchool::loadPsUserType ();
			//unset($loadPsUserType[PreSchool::USER_TYPE_MANAGER]);
		} else {
			$loadPsUserType = PreSchool::loadPsUserType ();
		}
		
		$this->widgetSchema ['user_type'] = new sfWidgetFormChoice ( array (
				'choices' => array ('' => '-User type-' ) + $loadPsUserType ), array ('class' => 'form-control' ) );

		$this->widgetSchema ['is_active'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => '-Select state-' ) + PreSchool::loadPsUserActivated () ), array (
				'class' => 'form-control' ) );

		// $this->validatorSchema['is_active'] = new sfValidatorChoice(array('choices' => array_keys(PreSchool::$ps_user_active), 'required' => false));
		$this->validatorSchema ['is_active'] = new sfValidatorChoice ( array (
				'required' => false,
				'choices' => array_keys ( PreSchool::$ps_user_active ) ) );

		$this->widgetSchema ['app_device_id_active'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => '-Select status app actived-' ) + PreSchool::getAppMobileActived () ), array (
				'class' => 'form-control' ) );

		// $this->validatorSchema['is_active'] = new sfValidatorChoice(array('choices' => array_keys(PreSchool::$ps_user_active), 'required' => false));
		$this->validatorSchema ['app_device_id_active'] = new sfValidatorChoice ( array (
				'required' => false,
				'choices' => array(0,1)) );
	}

	public function getFields() {

		if (myUser::isAdministrator ())
			return array (
					'id' => 'Number',
					'member_id' => 'Text',
					'ps_customer_id' => 'ForeignKey', // customer
					'is_global_super_admin' => 'Boolean',
					'first_name' => 'Text',
					'last_name' => 'Text',
					'username' => 'Text',
					'algorithm' => 'Text',
					'salt' => 'Text',
					'password' => 'Text',
					'is_active' => 'Number',
					'is_super_admin' => 'Boolean',
					'user_type' => 'Text',
					'last_login' => 'Date',
					'created_at' => 'Date',
					'updated_at' => 'Date',
					'groups_list' => 'ManyKey',
					'permissions_list' => 'ManyKey' );
		else {
			return parent::getFields ();
		}
	}

	// Add virtual_column_name for filter
	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		$a = 's';

		$query->leftJoin ( $a . ".PsMember m With " . $a . ".user_type IN('".PreSchool::USER_TYPE_TEACHER."','".PreSchool::USER_TYPE_MANAGER."') ");
		$query->leftJoin ( $a . '.PsRelative r With ' . $a . '.user_type= ? ', PreSchool::USER_TYPE_RELATIVE );

		$query->addWhere ( '(r.ps_workplace_id =? OR m.ps_workplace_id =?)', array ($value,$value ) );

		return $query;
	}

	// Add virtual_column_name for filter
	public function addIsActiveColumnQuery($query, $field, $value) {

		$a = 's';

		$query->addWhere ( $a . ".is_active = ? ", $value );

		return $query;
	}
	
	// Add virtual_column_name for filter
	public function addAppDeviceIdActiveColumnQuery($query, $field, $value) {
		
		$a = 's';
		
		if ($value == PreSchool::ACTIVE) {
			
			$query->addWhere ( $a . ".app_device_id IS NOT NULL");
		
		} elseif ($value == PreSchool::NOT_ACTIVE && $value != '') {
			$query->addWhere ( $a . ".app_device_id IS NULL");
		}
		
		return $query;
	}

	// Add virtual_column_name for filter
	public function addUserTypeColumnQuery($query, $field, $value) {

		$alias = 's'; // $query->getRootAlias();
		
		/*
		if ($value == PreSchool::USER_TYPE_TEACHER) // Giao vien - nhan su
			$query->andWhere ( $alias . ".user_type= ?", 'T' );
		elseif ($value == PreSchool::USER_TYPE_RELATIVE) // phu huynh
			$query->andWhere ( $alias . ".user_type= ?", 'R' );		
		elseif ($value == PreSchool::USER_TYPE_MANAGER) // Quan ly cap So/Phong
			$query->andWhere ( $alias . ".user_type= ?", 'M' );
		*/
		
		$query->andWhere ( $alias . ".user_type= ?", $value );
			
		return $query;
	}

	// Tim kiem
	public function addKeywordsColumnQuery($query, $field, $value) {

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( 'LOWER(s.username) LIKE ? OR LOWER(s.first_name) LIKE ? OR LOWER(s.last_name) LIKE ?', array (
					$keywords,
					$keywords,
					$keywords ) );
		}

		return $query;
	}
}