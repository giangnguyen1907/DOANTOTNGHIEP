<?php
/**
 * Service filter form.
 *
 * @package    Preschool
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ServiceFormFilter extends BaseServiceFormFilter {

	public function configure() {

		//$this->addPsCustomerFormFilter ( array ('PS_STUDENT_SERVICE_FILTER_SCHOOL','PS_STUDENT_SUBJECT_FILTER_SCHOOL' ) );
		
		$this->setPsCustomerFormFilter(true);

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		// Custom ServiceGroup
		/*
		 * if (myUser::credentialPsCustomers ('PS_STUDENT_SERVICE_FILTER_SCHOOL')) {
		 * $queryServiceGroup = Doctrine::getTable ( 'ServiceGroup' )->setSQLServiceGroup ();
		 * } else {
		 * $queryServiceGroup = Doctrine::getTable ( 'ServiceGroup' )->setSQLServiceGroup ( 'id, title', myUser::getPscustomerID () );
		 * }
		 */

		$queryServiceGroup = Doctrine::getTable ( 'ServiceGroup' )->setSQLServiceGroup ( 'id, title', $ps_customer_id );

		if ($ps_customer_id > 0) {
			$this->widgetSchema ['service_group_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'ServiceGroup',
					'query' => $queryServiceGroup,
					'add_empty' => _ ( '-Select service group-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select service group-' ) ) );

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkplaces',
					'query' => Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id,title', $ps_customer_id ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => 'min-width:200px;',
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select workplace-' ) ) );
		} else {
			$this->widgetSchema ['service_group_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select service group-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select service group-' ) ) );

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => 'min-width:200px;' ) );
		}

		$this->validatorSchema ['service_group_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'ServiceGroup',
				'column' => 'id' ) );

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkplaces',
				'column' => 'id' ) );

		/*
		 * $school_year_id = $this->getDefault ( 'ps_school_year_id' );
		 * if ($school_year_id <= 0) {
		 * // Nam hoc dang hoat dong
		 * $ps_school_year_default = sfContext::getInstance ()->getUser ()->getAttribute ( 'ps_school_year_default' );
		 * $this->setDefault ( 'ps_school_year_id', $ps_school_year_default->id );
		 * }
		 */
		/*

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
		*/
		
		// 

		$enable_roll = PreSchool::loadPsRoll ();
		// unset ( $enable_roll [PreSchool::SERVICE_TYPE_SCHEDULE] );

		$this->widgetSchema ['enable_roll'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => '-Select enable roll-' ) + $enable_roll ), array (
				'class' => 'form-control' ) );

		$this->validatorSchema ['enable_roll'] = new sfValidatorChoice ( array (
				'required' => false,
				'choices' => array (
						'',
						PreSchool::SERVICE_TYPE_NOT_FIXED,
						PreSchool::SERVICE_TYPE_FIXED ) ) );

		$this->widgetSchema ['is_default'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => 'Is default' ) + PreSchool::loadPsServiceDefault () ), array (
				'class' => 'form-control' ) );
		
		// $this->setPsSchoolYearFormFilter();

		// $this->disableLocalCSRFProtection ();
	}

	public function addEnableRollColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->andWhere ( $a . '.enable_roll = ?', $value );

		return $query;
	}

	// public function addPsSchoolYearIdColumnQuery($query, $field, $value) {

	// 	$a = $query->getRootAlias ();

	// 	$query->andWhere ( $a . '.ps_school_year_id = ? OR ' . $a . '.ps_school_year_id IS NULL ', $value );

	// 	return $query;
	// }

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->andWhere ( $a . '.ps_workplace_id = ? OR ' . $a . '.ps_workplace_id IS NULL ', $value );

		return $query;
	}
}