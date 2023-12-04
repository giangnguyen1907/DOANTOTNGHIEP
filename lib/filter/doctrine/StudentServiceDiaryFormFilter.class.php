<?php

/**
 * StudentServiceDiary filter form.
 *
 * @package    Preschool
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class StudentServiceDiaryFormFilter extends BaseStudentServiceDiaryFormFilter {

	public function configure() {

		$function_code = '';
		// Bo sung ma code
		$this->addPsCustomerFormFilter ( $function_code );

		if (! myUser::credentialPsCustomers ( $function_code )) {

			$ps_customer_id = myUser::getPscustomerID ();
		} else {
			$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
			$ps_school_year_id = $this->getDefault ( 'ps_school_year_id' );

			$this->widgetSchema ['ps_school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsSchoolYear',
					'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
					'add_empty' => _ ( '-Select school year-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select school year-' ) ) );
			if ($ps_school_year_id > 0 || $ps_customer_id > 0) {
				// Filters by class
				$this->widgetSchema ['class_id'] = new sfWidgetFormDoctrineChoice ( array (
						'model' => 'MyClass',
						'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
								'ps_customer_id' => $ps_customer_id,
								'ps_school_year_id' => $ps_school_year_id ) ),
						'add_empty' => _ ( '-Select class-' ) ), array (
						'class' => 'select2',
						'style' => "min-width:200px;",
						'data-placeholder' => _ ( '-Select class-' ) ) );
			} else {
				$this->widgetSchema ['class_id'] = new sfWidgetFormChoice ( array (
						'choices' => array (
								'' => _ ( '-Select class-' ) ) ), array (
						'class' => 'select2',
						'style' => "min-width:200px;",
						'data-placeholder' => _ ( '-Select class-' ) ) );
			}
		}
		$this->validatorSchema ['ps_school_year_id'] = new sfValidatorPass ( array (
				'required' => false ) );

		$this->validatorSchema ['class_id'] = new sfValidatorPass ( array (
				'required' => false ) );
		$this->validatorSchema ['ps_customer_id'] = new sfValidatorPass ( array (
				'required' => false ) );

		$this->widgetSchema ['tracked_at'] = new psWidgetFormFilterInputDate ();

		$this->widgetSchema ['tracked_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'value' => date ( 'd-m-Y' ),
				'title' => 'Date at from' ) );

		$this->widgetSchema ['tracked_at']->addOption ( 'tooltip', 'From date' );

		$this->validatorSchema ['tracked_at'] = new sfValidatorDate ( array (
				'required' => false ) );
	}

	public function getFields() {

		$fields = parent::getFields ();
		$fields ['ps_school_year_id'] = 'ForeignKey';
		return $fields;
	}

	// Add virtual_column_name for filter
	public function addPsSchoolYearIdColumnQuery($query, $field, $value) {

		return $query;
	}

	// Add virtual class_id for filter
	public function addClassIdColumnQuery($q, $field, $value) {

		$q = Doctrine_Query::create ()->from ( 'Student s' );

		$q->addSelect ( 'CONCAT(u.first_name, " ", u.last_name) AS updated_by' );
		$q->addSelect ( 'CONCAT(s.first_name, " ", s.last_name) AS student_name' );
		$q->leftJoin ( 's.StudentServiceDiary as ssd' );
		$q->innerJoin ( 's.PsCustomer cus' );
		$q->leftJoin ( 'ssd.UserUpdated u' );
		$q->leftJoin ( 'ssd.Service sv' );
		$q->leftJoin ( 's.StudentClass sc' );
		$q->orderBy ( 's.last_name' );
		$query->addWhere ( 's.class_id = ?', $value );

		if (! myUser::credentialPsCustomers ( $function_code ) && myUser::getPscustomerID () > 0) {
			$q->where ( 'ssd.ps_customer_id = ?', myUser::getPscustomerID () );
		}

		return $query;
	}

	public function addTrackedAtFromColumnQuery(Doctrine_Query $query, $field, $value) {

		$rootAlias = $query->getRootAlias ();

		return $query->addWhere ( $rootAlias . '.input_date_at >= ?', $value );
	}
}

