<?php
/**
 * PsHrDepartments filter form.
 *
 * @package    
 * @subpackage filter
 * @author     Nguyen Chien Thang
 * @version    1.0
 */
class PsHrDepartmentsFormFilter extends BasePsMemberFormFilter {

	protected $ps_customer_id;

	protected $ps_workplace_id;

	public function configure() {

		$ps_customer_root = Doctrine::getTable ( 'PsCustomer' )->getInfoRootById ();

		$this->ps_customer_id = $ps_customer_root->getId ();

		$this->ps_workplace_id = $ps_customer_root->getPsWorkplaceId ();

		$country_code = strtoupper ( sfConfig::get ( 'app_ps_default_country' ) );

		// Nếu là Cán bộ sở/Phòng
		$ps_province_required = true;
		
		if ((myUser::getUser ()->getManagerType () == PreSchool::MANAGER_TYPE_PROVINCIAL) || (myUser::getUser ()->getManagerType() == PreSchool::MANAGER_TYPE_DISTRICT)) { // Nếu là Cán bộ sở/Phòng

			$ps_province_id = myUser::getUser ()->getPsMember ()->getPsProvinceId ();
			
			$this->setDefault ( 'ps_province_id', $ps_province_id );

			$query_province = Doctrine::getTable ( 'PsProvince' )->setSqlPsProvinceByCountry ( $country_code, $ps_province_id );

			$ps_district_id = myUser::getUser ()->getPsMember ()->getPsDistrictId ();
			
		} else {
			if (myUser::isAdministrator ()) {
				$query_province = Doctrine::getTable ( 'PsProvince' )->setSqlPsProvinceByCountry ( $country_code );
				$ps_province_required = false;
			} elseif (myUser::getUser ()->getManagerType () == PreSchool::MANAGER_TYPE_GLOBAL) {
				// Lấy ra danh sách tỉnh thành được quản lý
				$query_province = Doctrine::getTable ( 'PsProvince' )->setSqlPsUserProvinceByUserId ( $country_code, myUser::getUser ()->getId () );
			}
		}
		
		if ($ps_province_required) {		
			$this->widgetSchema ['ps_province_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsProvince',
					'query' => $query_province,
					'add_empty' => false
			), array (
					'class' => 'form-control'
			) );
		} else {
			$this->widgetSchema ['ps_province_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsProvince',
					'query' => $query_province,
					'add_empty' => _ ( '-Select province-' )
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select province-' )
			) );
		}

		$this->validatorSchema ['ps_province_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsProvince',
				'query' => $query_province,
				'required' => $ps_province_required
		) );

		$ps_province_id = $this->getDefault ( 'ps_province_id' );
		
		$ps_district_required = false;
		if (myUser::getUser ()->getManagerType () == PreSchool::MANAGER_TYPE_DISTRICT) {
			$ps_district_required = true;
		}

		if ($ps_province_id > 0) {
			
			$this->widgetSchema ['ps_district_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsDistrict',
					'query' => Doctrine::getTable ( 'PsDistrict' )->setSqlPsDistrictByProvinceId ( $ps_province_id ),
					'add_empty' => _ ( '-Select district-' )
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select district-' )
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

		

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()->__ ( 'Keywords' ),
				'rel' => 'tooltip',
				'data-original-title' => 'Input: Hr code, Fullname, email, mobile'
		) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false
		) );

		$this->widgetSchema ['sex'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => '-Select sex-'
				) + PreSchool::loadPsGender ()
		), array (
				'class' => 'form-control'
		) );

		$this->widgetSchema ['is_status'] = new sfWidgetFormSelect ( array (
				'choices' => array (
						'' => _ ( '-Select status-' )
				) + PreSchool::loadHrStatus ()
		), array (
				'class' => 'form-control'
		) );

		$this->widgetSchema ['rank'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						0 => _ ( '-Select member rank-' )
				) + PreSchool::loadHrRank ()
		), array (
				'class' => 'form-control'
		) );

		$this->validatorSchema ['rank'] = new sfValidatorInteger ( array (
				'required' => false
		) );

	}

	// Add virtual_column_name for filter
	public function addPsProvinceIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->andWhere ( $a . '.ps_province_id = ?', $value );

		return $query;

	}

	// Add virtual_column_name for filter
	public function addPsDistrictIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->andWhere ( $a . '.ps_district_id = ?', $value );

		return $query;

	}

	// Tim kiem member_code,first_name,last_name,mobile
	public function addKeywordsColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			//$query->addSelect ( 'em.id AS e_id' );

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( 'LOWER('.$a.'.email) LIKE ? OR LOWER(' . $a . '.member_code) LIKE ? OR LOWER(' . $a . '.first_name) LIKE ? OR LOWER(' . $a . '.last_name) LIKE ? OR LOWER(' . $a . '.mobile) LIKE ? OR LOWER( CONCAT(' . $a . '.first_name," ", ' . $a . '.last_name) ) LIKE ?', array (
					$keywords,
					$keywords,
					$keywords,
					$keywords,
					$keywords,
					$keywords
			) );
			//$query->leftJoin ( $a . '.PsEmails em With em.obj_type = "T"' );
		}

		return $query;

	}

	public function doBuildQuery(array $values) {

		$query = parent::doBuildQuery ( $values );

		$a = $query->getRootAlias ();

		$query->addWhere ( $a . '.ps_customer_id = ?', $this->ps_customer_id );
		$query->addWhere ( $a . '.ps_workplace_id = ?', $this->ps_workplace_id );

		return $query;

	}

}