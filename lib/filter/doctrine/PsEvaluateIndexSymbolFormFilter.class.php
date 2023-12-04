<?php

/**
 * PsEvaluateIndexSymbol filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsEvaluateIndexSymbolFormFilter extends BasePsEvaluateIndexSymbolFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_EVALUATE_INDEX_SYMBOL_FILTER_SCHOOL' );

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

		$schoolyear_id = $this->getDefault ( 'school_year_id' );

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

		if (! myUser::credentialPsCustomers ( 'PS_EVALUATE_INDEX_SYMBOL_FILTER_SCHOOL' )) {

			$this->setDefault ( 'ps_customer_id', myUser::getPscustomerID () );
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

		$ps_workplaces_id = $this->getDefault ( 'ps_workplace_id' );

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );
	}

	public function addPsMonthColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$date_at = $track_at ? date ( 'Ym', strtotime ( $track_at ) ) : date ( 'Ym' );

		$q->addWhere ( '((s.date_start IS NULL OR DATE_FORMAT(s.date_start,"%Y%m") <=?) AND (s.date_end IS NULL OR DATE_FORMAT(s.date_end,"%Y%m") >=?))', array (
				$date_at,
				$date_at ) );

		return $query;
	}

	public function addSchoolYearIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->addWhere ( '(' . $a . '.school_year_id IS NULL OR ' . $a . '.school_year_id = ?) ', $value );

		return $query;
	}

	public function addKeywordsColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( '(LOWER(' . $a . '.symbol_code) LIKE ? OR LOWER(TRIM(' . $a . '.title)) LIKE ? ) ', array (
					$keywords,
					$keywords ) );
		}

		return $query;
	}
}
