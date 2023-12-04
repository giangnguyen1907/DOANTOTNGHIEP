<?php

/**
 * FeatureBranch filter form.
 *
 * @package    backend
 * @subpackage filter
 * @author     Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FeatureBranchFormFilter extends BaseFeatureBranchFormFilter {

	public function configure() {

		/*
		 * if ($school_year_id <= 0) {
		 * // Nam hoc dang hoat dong
		 * $ps_school_year_default = sfContext::getInstance ()->getUser ()->getAttribute ( 'ps_school_year_default' );
		 * $this->setDefault ( 'school_year_id', $ps_school_year_default->id );
		 * }
		 */
		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => '-Select school year-' ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ) ) );

		// $this->widgetSchema ['school_year_id']->setOption ( 'add_empty', true );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		// PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL
		$this->addPsCustomerFormFilter ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id > 0) {

			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'PsWorkPlaces',
					'column' => 'id' ) );

			// feature_id filter by ps_customer_id
			$this->widgetSchema ['feature_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Feature',
					'query' => Doctrine::getTable ( 'Feature' )->setSQLByCustomerId ( 'id, name', $ps_customer_id ),
					'add_empty' => '-Select feature-' ) );
			// $this->widgetSchema ['feature_id']->setOption ( 'add_empty', _ ( '-Select feature-' ) );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorInteger ( array (
					'required' => false ) );

			$this->widgetSchema ['feature_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select feature-' ) ) ), array (
					'class' => 'select2',
					'data-placeholder' => _ ( '-Select feature-' ) ) );

			$this->validatorSchema ['feature_id'] = new sfValidatorInteger ( array (
					'required' => false ) );
		}

		$this->widgetSchema ['feature_id']->setAttributes ( array (
				'style' => 'min-width:200px;',
				'class' => 'select2' ) );

		$this->widgetSchema ['ps_workplace_id']->setAttributes ( array (
				'style' => 'min-width:200px;',
				'class' => 'select2' ) );

		$this->widgetSchema ['is_study'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select study state-' ) ) + PreSchool::getIsStudy () ), array (
				'class' => 'select2',
				'data-placeholder' => _ ( '-Select study state-' ) ) );
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		if ($value > 0) {
			// $query->leftJoin('f.PsCustomer cus');
			$query->andWhere ( 'f.ps_customer_id = ?', $value );
		}

		return $query;
	}
}
