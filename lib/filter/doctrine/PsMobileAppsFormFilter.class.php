<?php

/**
 * PsMobileApps filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsMobileAppsFormFilter extends BasePsMobileAppsFormFilter {

	public function configure() {

		$school_year_id = $this->getDefault ( 'school_year_id' );
		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		$ps_month = $this->getDefault ( 'ps_month' );

		if ($ps_customer_id == '') {
			$ps_customer_id = myUser::getPscustomerID ();
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		$this->setDefault ( 'school_year_id', $school_year_id );

		$this->setDefault ( 'ps_customer_id', $ps_customer_id );

		$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );

		$this->setDefault ( 'ps_month', $ps_month );

		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => '-Select school year-' ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ) ) );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$school_year_id = $this->getDefault ( 'school_year_id' );

		if ($school_year_id > 0) {

			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolyear' )->findOneById ( $school_year_id );

			$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );

			$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );

			$this->widgetSchema ['ps_month'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select month-' ) ) + PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
					'class' => 'select2',
					'style' => "min-width:100px;",
					'placeholder' => _ ( '-Select month-' ),
					'rel' => 'tooltip',
					'data-original-title' => _ ( 'Select month' ) ) );
		} else {
			$this->widgetSchema ['ps_month'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select month-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:100px;",
					'placeholder' => _ ( '-Select month-' ),
					'rel' => 'tooltip',
					'data-original-title' => _ ( 'Select month' ) ) );
		}

		$this->validatorSchema ['ps_month'] = new sfValidatorString ( array (
				'required' => false ) );

		if (myUser::credentialPsCustomers ( 'PS_REPORT_MOBILE_APPS_FILTER_SCHOOL' )) {

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::ACTIVE ),
					'add_empty' => '-Select customer-' ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select customer-' ) ) );

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'PsCustomer',
					'column' => 'id' ) );
		} else {

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();

			$this->setDefault ( 'ps_customer_id', myUser::getPscustomerID () );

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorNumber ();
		}

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id > 0) {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
		}

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorPass ( array (
				'required' => false ) );

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_school_year_id' => $school_year_id );

		$this->widgetSchema ['user_type'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select user type-' ) ) + PreSchool::loadPsUserType () ), array (
				'class' => 'select2',
				'data-placeholder' => _ ( '-Select user type-' ) ) );

		$this->validatorSchema ['user_type'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->widgetSchema ['os_type'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select OS-' ),
						PreSchool::NOT_ACTIVE => _ ( 'IOS' ),
						PreSchool::ACTIVE => _ ( 'Android' ) ) ), array (
				'class' => 'select2',
				'data-placeholder' => _ ( '-Select os type-' ) ) );

		$this->validatorSchema ['os_type'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->addBootstrapFilter ();
	}

	public function addSchoolYearIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsMonthColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->addHaving ( "DATE_FORMAT(MIN({$a}.active_created_at), '%m-%Y') = ? ", $value );

		return $query;
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->andWhere ( "u.ps_customer_id = ?", $value );

		return $query;
	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		$query->andWhere ( 'wp.id = ?', $value );

		return $query;
	}

	public function addOsTypeColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();
		switch ($value) {
			case PreSchool::NOT_ACTIVE :
				$query->andWhere ( "{$a}.osname IS NULL" );
				break;
			case PreSchool::ACTIVE :
				$query->andWhere ( "{$a}.osname IS NOT NULL" );
				break;
			default :
		}

		return $query;
	}

	public function addUserTypeColumnQuery($query, $field, $value) {

		if ($value == PreSchool::USER_TYPE_RELATIVE || $value == PreSchool::USER_TYPE_TEACHER) {
			$a = $query->getRootAlias ();
			$query->andWhere ( "u.user_type = ?", $value );
			return $query;
		}
	}

	public function addKeywordsColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( "(LOWER(TRIM(u.first_name)) LIKE ? OR LOWER(TRIM(u.last_name)) LIKE ? OR LOWER(u.email_address) LIKE ? OR LOWER(u.username) LIKE ? OR LOWER(TRIM(CONCAT(u.first_name,u.last_name))) LIKE ? ) ", array (
					$keywords,
					$keywords,
					$keywords,
					$keywords,
					$keywords ) );
		}

		return $query;
	}

	public function doBuildQueryOLD(array $values) {

		$query = parent::doBuildQuery ( $values );

		$a = $query->getRootAlias ();

		if ($values ['school_year_id'] > 0) {

			$school_year = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ( $values ['school_year_id'] );
			$from_date = date ( 'Ymd', strtotime ( $school_year->getFromDate () ) );
			$to_date = date ( 'Ymd', strtotime ( $school_year->getToDate () ) );

			$query->andWhere ( "(DATE_FORMAT({$a}.active_created_at, '%Y%m%d') >= ? AND DATE_FORMAT({$a}.active_created_at, '%Y%m%d') <= ?)", array (
					$from_date,
					$to_date ) );
		} else {
			$from_date = $to_date = null;
		}

		if ($values ['ps_month'] > 0) {
			$delete_at = date ( 'Ym', strtotime ( '01-' . $values ['ps_month'] ) );
		} else {
			$delete_at = null;
		}

		if ($values ['ps_workplace_id'] > 0) {

			$class_id = Doctrine::getTable ( 'MyClass' )->getClassByParams ( array (
					'ps_school_year_id' => $values ['school_year_id'],
					'ps_customer_id' => $values ['ps_customer_id'],
					'ps_workplace_id' => $values ['ps_workplace_id'],
					'is_activated' => PreSchool::ACTIVE ) )
				->toArray ();

			$class_id = array_column ( $class_id, 'id' );

			$query->leftJoin ( 'rl.RelativeStudent rs' );

			$query->leftJoin ( 'rs.Student s' );

			if (isset ( $delete_at )) {
				$query->andWhere ( '(s.deleted_at IS NULL OR DATE_FORMAT(s.deleted_at,"%Y%m") <=?)', $delete_at );
			} elseif (isset ( $to_date ) && $to_date != '') {
				$query->andWhere ( '(s.deleted_at IS NULL OR DATE_FORMAT(s.deleted_at,"%Y%m%d") <=?)', $to_date );
			} else {
				$query->andWhere ( '(s.deleted_at IS NULL OR DATE_FORMAT(s.deleted_at,"%Y%m%d") <=?)', date ( 'Ymd' ) );
			}

			$query->innerJoin ( 's.StudentClass sc WITH sc.is_activated=?', PreSchool::ACTIVE );

			$query->andWhereIn ( 'sc.myclass_id', $class_id );

			$query->andWhereIn ( 'sc.type', array (
					PreSchool::SC_STATUS_OFFICIAL,
					PreSchool::SC_STATUS_TEST ) );

			// $query->andWhere('rl.ps_workplace_id = ?', $values['ps_workplace_id']);
		}

		return $query;
	}

	public function doBuildQuery(array $values) {

		$query = parent::doBuildQuery ( $values );

		$a = $query->getRootAlias ();
		/*
		 * if($values['ps_month'] != '') { $date_at = date('Ym', strtotime('01-'.$values['ps_month'])); $ps_month = $values['ps_month']; } else { $delete_at = null; $ps_month = null; }
		 */

		$query->innerJoin ( 'rl.RelativeStudent rs' );

		$query->innerJoin ( 'rs.Student s' );

		$query->innerJoin ( 's.StudentClass sc' );

		$query->innerJoin ( 'sc.MyClass mc' );

		$query->innerJoin ( 'mc.PsClassRooms cr' );

		$query->innerJoin ( 'cr.PsWorkPlaces wp' );

		/*
		 * if ($values ['ps_workplace_id'] > 0) { $query->andWhere ( 'wp.id = ?', $values ['ps_workplace_id'] ); }
		 */

		return $query;
	}
}
