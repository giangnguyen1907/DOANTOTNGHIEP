<?php

/**
 * PsReceivableTemporary filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsReceivableTemporaryFormFilter extends BasePsReceivableTemporaryFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_FEE_REPORT_FILTER_SCHOOL' );

		$school_year_id = $this->getDefault ( 'school_year_id' );
		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		$ps_month = $this->getDefault ( 'ps_month' );
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

			$this->widgetSchema ['receivable_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select receivable-' ) ) ), array (
					'class' => 'select2',
					'data-placeholder' => _ ( '-Select receivable-' ) ) );

			$this->validatorSchema ['receivable_id'] = new sfValidatorInteger ( array (
					'required' => false ) );
		}

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->setDefault ( 'receivable_id', $receivable_id );
	}

	// Add virtual column_name for filter
	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$query->addWhere ( 'cus.id = ?', $value );

		return $query;
	}

	public function addPsMonthColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->addWhere ( 'DATE_FORMAT(' . $a . '.receivable_at,"%m-%Y") = ?', $value );

		return $query;
	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();
		$query->addWhere ( '(rc.ps_workplace_id IS NULL OR rc.ps_workplace_id = ?)', $value );
		return $query;
	}

	public function addKeywordsColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( '(LOWER(s.student_code) LIKE ? OR LOWER(TRIM(s.first_name)) LIKE ? OR LOWER(TRIM(s.last_name)) LIKE ? OR LOWER(' . $a . '.amount) LIKE ? OR LOWER(' . $a . '.note) LIKE ?) ', array (
					$keywords,
					$keywords,
					$keywords,
					$keywords,
					$keywords ) );
		}

		return $query;
	}

	public function addSchoolYearIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();
		$query->addWhere ( 'rc.ps_school_year_id = ?', $value );
		return $query;
	}
}
