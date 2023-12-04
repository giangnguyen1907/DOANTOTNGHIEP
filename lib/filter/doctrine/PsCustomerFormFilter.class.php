<?php
/**
 * PsCustomer filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsCustomerFormFilter extends BasePsCustomerFormFilter {

	public function configure() {

		$country_code = strtoupper ( sfConfig::get ( 'app_ps_default_country' ) );

		if (myUser::credentialPsCustomers ( 'PS_SYSTEM_CUSTOMER_FILTER_SCHOOL' )) {

			$this->widgetSchema ['ps_province_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsProvince',
					'query' => Doctrine::getTable ( 'PsProvince' )->setSqlPsProvinceByCountry ( $country_code ),
					'add_empty' => _ ( '-Select province-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select province-' ) ) );

			$this->validatorSchema ['ps_province_id'] = new sfValidatorPass ( array (
					'required' => false ) );

			$ps_province_id = $this->getDefault ( 'ps_province_id' );

			if ($ps_province_id > 0) {
				$this->widgetSchema ['ps_district_id'] = new sfWidgetFormDoctrineChoice ( array (
						'model' => 'PsProvince',
						'query' => Doctrine::getTable ( 'PsDistrict' )->setSqlPsDistrictByProvinceId ( $ps_province_id ),
						'add_empty' => _ ( '-Select district-' ) ), array (
						'class' => 'select2',
						'style' => "min-width:200px;",
						'data-placeholder' => _ ( '-Select district-' ) ) );
			} else {

				$this->widgetSchema ['ps_district_id'] = new sfWidgetFormChoice ( array (
						'choices' => array (
								'' => _ ( '-Select district-' ) ) ), array (
						'class' => 'select2',
						'style' => "min-width:200px;",
						'data-placeholder' => _ ( '-Select district-' ) ) );
			}

			$this->validatorSchema ['ps_district_id'] = new sfValidatorPass ( array (
					'required' => false ) );

			// Xa-Phuong
			$ps_district_id = $this->getDefault ( 'ps_district_id' );

			if ($ps_district_id > 0) {

				$this->widgetSchema ['ps_ward_id'] = new sfWidgetFormChoice ( array (
						'choices' => array (
								'' => _ ( '-Select Ward-' ) ) + Doctrine::getTable ( 'PsWard' )->getChoicePsWard ( $ps_district_id ) ), array (
						'class' => 'select2',
						'style' => "min-width:200px;",
						'data-placeholder' => _ ( '-Select Ward-' ) ) );
			} else {

				$this->widgetSchema ['ps_ward_id'] = new sfWidgetFormChoice ( array (
						'choices' => array (
								'' => _ ( '-Select Ward-' ) ) ), array (
						'class' => 'select2',
						'style' => "min-width:200px;",
						'data-placeholder' => _ ( '-Select Ward-' ) ) );
			}

			$this->validatorSchema ['ps_ward_id'] = new sfValidatorPass ( array (
					'required' => false ) );

			$ps_ward_id = $this->getDefault ( 'ps_ward_id' );
		} else {
			$this->widgetSchema ['ps_province_id'] = new sfWidgetFormInputHidden ();
			$this->widgetSchema ['ps_district_id'] = new sfWidgetFormInputHidden ();
			$this->widgetSchema ['ps_ward_id'] = new sfWidgetFormInputHidden ();

			$this->validatorSchema ['ps_province_id'] = new sfValidatorInteger ( array (
					'required' => false ) );
			$this->validatorSchema ['ps_district_id'] = new sfValidatorInteger ( array (
					'required' => false ) );
			$this->validatorSchema ['ps_ward_id'] = new sfValidatorInteger ( array (
					'required' => false ) );
		}

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();
		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->widgetSchema ['tel_fax'] = new sfWidgetFormInputText ();
		$this->widgetSchema ['tel_fax']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Tel-Fax' ) ) );

		$this->validatorSchema ['tel_fax'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->widgetSchema ['is_activated'] = new sfWidgetFormSelect ( array (
				'choices' => array (
						'' => '-Select state-' ) + PreSchool::loadPsCustomerActivated () ) );
		$this->widgetSchema ['is_activated']->setAttributes ( array (
				'class' => 'select2',
				'style' => "min-width:150px;" ) );

		$this->validatorSchema ['is_activated'] = new sfValidatorChoice ( array (
				'required' => false,
				'choices' => array_keys ( PreSchool::$ps_customer_active ) ) );

		$this->widgetSchema ['is_deploy'] = new sfWidgetFormSelect ( array (
				'choices' => array (
						'' => '-Select deploy state-' ) + PreSchool::loadPsCustomerDeploy () ) );
		$this->widgetSchema ['is_deploy']->setAttributes ( array (
				'class' => 'select2',
				'style' => "min-width:150px;" ) );

		$this->validatorSchema ['is_deploy'] = new sfValidatorChoice ( array (
				'required' => false,
				'choices' => array_keys ( PreSchool::$ps_customer_deploy ) ) );
	}

	// Add virtual_column_name for filter
	public function addPsProvinceIdColumnQuery($query, $field, $value) {

		if ($value != '') {
			$query->andWhere ( 'p.id = ?', $value );
		}

		return $query;
	}

	// Add virtual_column_name for filter
	public function addPsDistrictIdColumnQuery($query, $field, $value) {

		if ($value > 0) {

			$query->andWhere ( 'd.id = ?', $value );
		}

		return $query;
	}

	// Add virtual_column_name for filter
	public function addPsWardIdColumnQuery($query, $field, $value) {

		if ($value > 0) {

			$query->andWhere ( 'pw.id = ?', $value );
		}

		return $query;
	}

	public function addIsActivatedColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->addWhere ( $a . ".is_activated = ? ", $value );

		return $query;
	}

	public function addIsDeployColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->addWhere ( $a . ".is_deploy = ? ", $value );

		return $query;
	}

	// Tim kiem
	public function addKeywordsColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( 'LOWER(' . $a . '.school_code) LIKE ? OR LOWER(' . $a . '.school_name) LIKE ? OR LOWER(' . $a . '.title) LIKE ? OR LOWER(' . $a . '.address) LIKE ?', array (
					$keywords,
					$keywords,
					$keywords,
					$keywords ) );
		}

		return $query;
	}

	// Tim kiem
	public function addTelFaxColumnQuery($query, $field, $value) {
		
		$a = $query->getRootAlias ();
		
		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( 'LOWER(' . $a . '.tel) LIKE ? OR LOWER(' . $a . '.fax) LIKE ? OR LOWER(' . $a . '.mobile) LIKE ?', array (
					$keywords,
					$keywords,
					$keywords ) );
		}

		return $query;
	}
}