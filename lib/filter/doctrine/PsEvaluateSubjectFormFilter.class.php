<?php

/**
 * PsEvaluateSubject filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsEvaluateSubjectFormFilter extends BasePsEvaluateSubjectFormFilter {

	public function configure() {
		
		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
		
		if (myUser::credentialPsCustomers ( 'PS_EVALUATE_INDEX_SUBJECT_FILTER_SCHOOL' )) {

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-Select customer-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select customer-' ) ) );
		} else {
			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED, myUser::getPscustomerID () ),
					'add_empty' => _ ( '-Select customer-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select customer-' ) ) );
		}

		$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsCustomer',
				'column' => 'id' ) );

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

		$this->setDefault ( 'ps_customer_id', $ps_customer_id );

		if ($ps_customer_id > 0) {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => "PsWorkplaces",
					'query' => Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id ),
					'add_empty' => _ ( '-Select basis enrollment-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplaces-' ) ) );

			$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => _ ( '-Select workplaces-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplaces-' ) ) );
		}
		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				// 'required' => true,
				'required' => false,
				'model' => 'PsWorkplaces',
				'column' => 'id' ) );

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );
	}

	public function addSchoolYearIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->addWhere ( $a . '.school_year_id IS NULL OR ' . $a . '.school_year_id = ?', $value );

		return $query;
	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->addWhere ( $a . '.ps_workplace_id IS NULL OR ' . $a . '.ps_workplace_id = ?', $value );

		return $query;
	}

	public function addKeywordsColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( '(LOWER(' . $a . '.subject_code) LIKE ? OR LOWER(TRIM(' . $a . '.title))  LIKE ?) ', array (
					$keywords,
					$keywords // $keywords,
			// $keywords,
			// $keywords
			) );
		}

		return $query;
	}
}
