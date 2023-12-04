<?php

/**
 * PsEvaluateIndexStudent filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsEvaluateIndexStudentFormFilter extends BasePsEvaluateIndexStudentFormFilter {

	public function configure() {

		$school_year_id = $this->getDefault ( 'school_year_id' );
		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		$ps_month = $this->getDefault ( 'ps_month' );
		/*
		if ($school_year_id == '') {
			$school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}
		if ($ps_month == '') {
			$ps_month = date ( "m-Y" );
		}
		if($ps_customer_id ==''){
		$ps_customer_id = myUser::getPscustomerID();
		$this->setDefault('ps_customer_id' , $ps_customer_id);
		$member_id = myUser::getUser()->getMemberId();
		$ps_workplace_id = myUser::getWorkPlaceId($member_id);
		}
		*/
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
				'required' => true,
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
				'required' => true ) );

		if (myUser::credentialPsCustomers ( 'PS_EVALUATE_INDEX_STUDENT_FILTER_SCHOOL' )) {

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::ACTIVE ),
					'add_empty' => '-Select customer-' ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select customer-' ) ) );

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => true,
					'model' => 'PsCustomer',
					'column' => 'id' ) );
		} else {

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();

			$this->setDefault ( 'ps_customer_id', myUser::getPscustomerID () );

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorNumber ();
		}

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id > 0) {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ), array (
					'required' => true,
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'required' => true,
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
		}

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorPass ( array (
				'required' => true ) );

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_school_year_id' => $school_year_id );

		if ($ps_workplace_id > 0) {

			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );
		} else {
			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );
		}

		$this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'MyClass',
				'column' => 'id' ) );

		if ($ps_customer_id > 0 || $ps_workplace_id > 0) {

			$this->widgetSchema ['evaluate_subject_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsEvaluateSubject',
					'query' => Doctrine::getTable ( 'PsEvaluateSubject' )->setSQLEvaluateIndexSubjectByParam ( array (
							'is_activated' => PreSchool::ACTIVE,
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id ) ),
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

		$this->widgetSchema ['is_public'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select state-' ) ) + PreSchool::getStatus () ), array (
				'class' => 'select2',
				'data-placeholder' => _ ( '-Select state-' ) ) );

		$this->widgetSchema ['is_awaiting_approval'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select approval state-' ) ) + PreSchool::getStatus () ), array (
				'class' => 'select2',
				'data-placeholder' => _ ( '-Select approval state-' ) ) );

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->addBootstrapFilter ();
	}

	public function addSchoolYearIdColumnQuery($query, $field, $value) {

	}

	public function addPsMonthColumnQuery($query, $field, $value) {

	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

	}

	public function addPsClassIdColumnQuery($query, $field, $value) {

	}

	public function addEvaluateSubjectIdColumnQuery($query, $field, $value) {

	}

	public function addKeywordsColumnQuery($query, $field, $value) {

		// $a = $query->getRootAlias();

		// $keywords = PreString::trim($value);

		// if (PreString::length($keywords) > 0) {

		// $keywords = '%' . PreString::strLower($keywords) . '%';

		// $query->addWhere('(LOWER(' . $a . '.symbol_code) LIKE ? OR LOWER(TRIM(' . $a . '.title)) LIKE ? ) ', array(
		// $keywords,
		// $keywords
		// ));
		// }

		// return $query;
	}
}
