<?php

/**
 * PsReceiptTemporary filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsReceiptTemporaryFormFilter extends BasePsReceiptTemporaryFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' );

		$school_year_id = $this->getDefault ( 'school_year_id' );
		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		$ps_month = $this->getDefault ( 'ps_month' );

		if ($school_year_id == '') {
			$school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}

		if ($ps_month == '') {
			$ps_month = date ( "m-Y" );
		}

		if ($ps_customer_id == '') {
			$ps_customer_id = myUser::getPscustomerID ();
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
		}

		$this->setDefault ( 'school_year_id', $school_year_id );

		$this->setDefault ( 'ps_customer_id', $ps_customer_id );

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

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		$this->widgetSchema ['receipt_date'] = new psWidgetFormFilterInputDate ();

		$this->widgetSchema ['receipt_date']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'data-original-title' => sfContext::getInstance ()->getI18n ()
					->__ ( 'From date' ) ) );

		$this->widgetSchema ['receipt_date']->addOption ( 'tooltip', sfContext::getInstance ()->getI18n ()
			->__ ( 'From date' ) );

		$this->validatorSchema ['receipt_date'] = new sfValidatorDate ( array (
				'required' => false ) );

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();
		$query->addWhere ( $a . '.ps_customer_id = ?', $value );
		return $query;
	}

	public function addPsMonthColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();
		$query->addWhere ( 'DATE_FORMAT(' . $a . '.receipt_date,"%m-%Y") LIKE ?', $value );
		return $query;
	}

	public function addKeywordsColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( '(LOWER(s.student_code) LIKE ? OR LOWER(TRIM(s.first_name)) LIKE ? OR LOWER(TRIM(s.last_name)) LIKE ? OR LOWER(' . $a . '.title) LIKE ? OR LOWER(' . $a . '.note) LIKE ?) ', array (
					$keywords,
					$keywords,
					$keywords,
					$keywords,
					$keywords ) );
		}

		return $query;
	}

	public function addSchoolYearIdColumnQuery($query, $field, $value) {

		// $a = $query->getRootAlias();
		// $query->addWhere ('rc.ps_school_year_id = ?', $value );
		return $query;
	}
}
