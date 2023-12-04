<?php

/**
 * ReceivableStudent filter form.
 *
 * @package    Preschool
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ReceivableStudentFormFilter extends BaseReceivableStudentFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_FEE_REPORT_FILTER_SCHOOL' );

		$school_year_id = $this->getDefault ( 'school_year_id' );
		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		$ps_month = $this->getDefault ( 'ps_month' );
		$ps_class_id = $this->getDefault ( 'ps_class_id' );
		$receivable_id = $this->getDefault ( 'receivable_id' );

		if ($school_year_id == '') {
			$school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}

		if ($ps_month == '') {
			$ps_month = date ( "m-Y" );
		}

		$this->setDefault ( 'school_year_id', $school_year_id );
		$this->setDefault ( 'ps_customer_id', $ps_customer_id );
		$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		$this->setDefault ( 'ps_month', $ps_month );
		$this->setDefault ( 'ps_class_id', $ps_class_id );
		$this->setDefault ( 'receivable_id', $receivable_id );

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

		$param_class = array (
				'ps_school_year_id' => $school_year_id,
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'is_activated' => PreSchool::ACTIVE
		);

		if ($ps_customer_id > 0) {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'PsWorkPlaces',
					'column' => 'id' ) );

			$this->widgetSchema ['ps_workplace_id']->setAttributes ( array (
					'style' => 'min-width:200px;',
					'class' => 'select2',
					'required' => false ) );

			if (myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' ) || ! sfContext::getInstance ()->getUser ()
				->hasCredential ( 'PS_STUDENT_ATTENDANCE_TEACHER' )) {
				$sqlMyClass = Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class );
			} else {
				$sqlMyClass = Doctrine::getTable ( 'MyClass' )->getClassIdByUserId ( myUser::getUserId () );
			}

			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => $sqlMyClass,
					'add_empty' => _ ( '-Select class-' ) ), array (
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'MyClass',
					'column' => 'id' ) );

			$this->widgetSchema ['ps_class_id']->setAttributes ( array (
					'style' => 'min-width:200px;',
					'class' => 'select2',
					'required' => false ) );

			// Filters student
			$this->widgetSchema ['student_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Student',
					'query' => Doctrine::getTable ( 'Student' )->setSqlListStudentsByClassId ( $ps_class_id ),
					'add_empty' => _ ( '-Select student-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select student-' ) ) );

			$this->validatorSchema ['student_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'Student',
					'required' => false ) );

			$this->widgetSchema ['student_id']->setAttributes ( array (
					'style' => 'min-width:200px;',
					'class' => 'select2',
					'required' => false ) );

			// select khoan phai thu
			$this->widgetSchema ['receivable_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Receivable',
					'query' => Doctrine::getTable ( 'Receivable' )->setListReceivableTempByParams ( array (
							'ps_school_year_id' => $school_year_id,
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id ) ),
					'add_empty' => '-Select receivable-' ) );

			$this->validatorSchema ['receivable_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'Receivable',
					'column' => 'id' ) );

			$this->widgetSchema ['receivable_id']->setAttributes ( array (
					'style' => 'min-width:200px;',
					'class' => 'select2',
					'required' => false ) );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorInteger ( array (
					'required' => false ) );

			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorInteger ( array (
					'required' => false ) );

			$this->widgetSchema ['student_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select student-' ) ) ), array (
					'class' => 'select2',
					'data-placeholder' => _ ( '-Select student-' ) ) );

			$this->validatorSchema ['student_id'] = new sfValidatorInteger ( array (
					'required' => false ) );

			$this->widgetSchema ['receivable_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select receivable-' ) ) ), array (
					'class' => 'select2',
					'required' => false,
					'data-placeholder' => _ ( '-Select receivable-' ) ) );

			$this->validatorSchema ['receivable_id'] = new sfValidatorInteger ( array (
					'required' => false ) );
		}

		// $this->widgetSchema['keywords'] = new sfWidgetFormInputText();

		// $this->widgetSchema['keywords']->setAttributes(array(
		// 'class' => 'form-control',
		// 'placeholder' => sfContext::getInstance()->getI18n()
		// ->__('Keywords')
		// ));

		// $this->validatorSchema['keywords'] = new sfValidatorString(array(
		// 'required' => false
		// ));
	}

	public function addSchoolYearIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();
		$query->addWhere ( 'rc.ps_school_year_id = ?', $value );
		return $query;
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();
		$query->addWhere ( 'rc.ps_customer_id = ?', $value );
		return $query;
	}

	public function addPsMonthColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();
		// $query->addWhere ('DATE_FORMAT('.$a.'.receivable_at,"%m-%Y") LIKE ?', $value );
		return $query;
	}

	public function addReceivableIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();
		// $query->addWhere ('rc.id = ?', $value );
		return $query;
	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();
		// $query->addWhere ('(rc.ps_workplace_id IS NULL OR rc.ps_workplace_id = ?)', $value );
		return $query;
	}

	public function addPsClassIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();
		return $query;
	}

	public function addStudentIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();
		return $query;
	}

	// public function addKeywordsColumnQuery($query, $field, $value)
	// {
	// $a = $query->getRootAlias();

	// $keywords = PreString::trim($value);

	// if (PreString::length($keywords) > 0) {

	// $keywords = '%' . PreString::strLower($keywords) . '%';

	// $query->addWhere('(LOWER(s.student_code) LIKE ? OR LOWER(TRIM(s.first_name)) LIKE ? OR LOWER(TRIM(s.last_name)) LIKE ? OR LOWER(' . $a . '.amount) LIKE ? OR LOWER(' . $a . '.note) LIKE ?) ', array(
	// $keywords,
	// $keywords,
	// $keywords,
	// $keywords,
	// $keywords
	// ));
	// }

	// return $query;
	// }
	public function doBuildQuery(array $values) {

		$query = parent::doBuildQuery ( $values );

		$a = $query->getRootAlias ();

		if (isset($values ['ps_month']) && $values ['ps_month'] != '') {
			$date = '01-' . $values ['ps_month'];
			// echo date('mY', strtotime($date)); die();
			$query->addWhere ( 'DATE_FORMAT(' . $a . '.receivable_at,"%Y%m") = ?', date ( 'Ym', strtotime ( $date ) ) );
			$query->andWhere ( '(DATE_FORMAT(sc.start_at,"%Y%m") <= ? AND sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m") >= ? )', array (
					date ( 'Ym', strtotime ( $date ) ),
					date ( 'Ym', strtotime ( $date ) ) ) );
		} else {
			$query->addWhere ( 'DATE_FORMAT(' . $a . '.receivable_at,"%Y%m") LIKE ?', date ( 'Ym' ) );
			$query->andWhere ( 'DATE_FORMAT(sc.start_at,"%Y%m") <= ?', date ( 'Ym' ) );
			$query->andWhere ( 'sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m") >= ? ', date ( 'Ym' ) );
		}

		if (isset($values ['student_id']) && $values ['student_id'] > 0) {
			$query->addWhere ( $a . '.student_id = ?', $values ['student_id'] );
		}

		if (isset($values ['ps_class_id']) && $values ['ps_class_id'] > 0) {
			$query->addWhere ( 'sc.myclass_id = ?', $values ['ps_class_id'] );
		}

		if (isset($values ['receivable_id']) && $values ['receivable_id'] > 0) {
			$query->addWhere ( $a . '.receivable_id = ?', $values ['receivable_id'] );
		}

		return $query;
	}
}
