<?php

/**
 * PsEvaluateIndexCriteria filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsEvaluateIndexCriteriaFormFilter extends BasePsEvaluateIndexCriteriaFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_EVALUATE_INDEX_CRITERIA_FILTER_SCHOOL' );

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

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );

		if (! myUser::credentialPsCustomers ( 'PS_EVALUATE_INDEX_CRITERIA_FILTER_SCHOOL' )) {

			$ps_customer_id = myUser::getPscustomerID ();

			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
		} else {

			$ps_customer_id = null;
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

		if ($ps_customer_id > 0) {

			$this->widgetSchema ['evaluate_subject_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsEvaluateSubject',
					'query' => Doctrine::getTable ( 'PsEvaluateSubject' )->setSQLEvaluateIndexSubjectByParam ( array (
							'is_activated' => PreSchool::ACTIVE,
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplaces_id,
							'school_year_id' => $schoolyear_id ) ),
					'add_empty' => '-Select evaluate subject-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select evaluate subject-' ) ) );
		} else {

			$this->widgetSchema ['evaluate_subject_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select evaluate subject-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select evaluate subject-' ) ) );
		}

		$this->validatorSchema ['evaluate_subject_id'] = new sfValidatorPass ( array (
				'required' => false ) );
	}

	public function addSchoolYearIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->addWhere ( '(es.school_year_id IS NULL OR es.school_year_id = ?) ', $value );

		return $query;
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->addWhere ( '(es.ps_customer_id = ?) ', $value );

		return $query;
	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->addWhere ( '(es.ps_workplace_id IS NULL OR es.ps_workplace_id = ?) ', $value );

		return $query;
	}

	public function addEvaluateSubjectIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->addWhere ( '(es.id = ?) ', $value );

		return $query;
	}

	public function addKeywordsColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( '(LOWER(' . $a . '.criteria_code) LIKE ? OR LOWER(TRIM(' . $a . '.title)) LIKE ? OR es.subject_code LIKE ? OR es.title LIKE ? OR CONCAT(es.subject_code, ": ",es.title) LIKE ?) ', array (
					$keywords,
					$keywords,
					$keywords,
					$keywords,
					$keywords ) );
		}

		return $query;
	}
}
