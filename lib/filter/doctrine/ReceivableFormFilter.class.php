<?php
/**
 * Receivable filter form.
 *
 * @package    Preschool
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ReceivableFormFilter extends BaseReceivableFormFilter {

	public function configure() {

		/*
		 * $school_year_id = $this->getDefault ( 'school_year_id' );
		 * if ($school_year_id <= 0) {
		 * // Nam hoc dang hoat dong
		 * $ps_school_year_default = sfContext::getInstance ()->getUser ()->getAttribute ( 'ps_school_year_default' );
		 * $this->setDefault ( 'ps_school_year_id', $ps_school_year_default->id );
		 * }
		 */
		
		$this->widgetSchema ['ps_school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => "PsSchoolYear",
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => false ), array (
						'class' => 'select2',
						'style' => 'min-width:200px;',
						'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select school year-' ) ) );
		
		$this->validatorSchema ['ps_school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );
		
		$this->addPsCustomerFormFilter ( 'PS_FEE_RECEIVABLE_FILTER_SCHOOL' );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id > 0) {

			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'PsWorkPlaces',
					'column' => 'id' ) );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorInteger ( array (
					'required' => false ) );
		}
	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->andWhere ( $a . '.ps_workplace_id = ? OR ' . $a . '.ps_workplace_id IS NULL ', $value );

		return $query;
	}
}
