<?php

/**
 * PsFeeReceipt filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsFeeReceiptFormFilter extends BasePsFeeReceiptFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_FEE_RECEIPT_NOTICATION_FILTER_SCHOOL' );

		$school_year_id = $this->getDefault ( 'school_year_id' );
		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		$ps_class_id = $this->getDefault ( 'ps_class_id' );
		$ps_month = $this->getDefault ( 'ps_month' );

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
					'choices' => array () + PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
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
			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id,
							'ps_school_year_id' => $school_year_id,
							'is_activated' => PreSchool::ACTIVE ) ),
					'add_empty' => '-Select class-' ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'MyClass',
					'column' => 'id' ) );

			$this->widgetSchema ['ps_class_id']->setAttributes ( array (
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

			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorInteger ( array (
					'required' => false ) );
		}

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->widgetSchema ['payment_status'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => 'Payment status' ) + PreSchool::loadPsPaymentStatus () ), array (
				'class' => 'form-control' ) );

		$this->validatorSchema ['payment_status'] = new sfValidatorChoice ( array (
				'choices' => array_keys ( PreSchool::loadPsPaymentStatus () ),
				'required' => false ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->setDefault ( 'ps_class_id', $ps_class_id );
		$this->setDefault ( 'ps_month', $ps_month );
	}

	// Add virtual column_name for filter
	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->addWhere ( $a . '.ps_customer_id = ?', $value );

		return $query;
	}

	// TRang thai thanh toan
	public function addPaymentStatusColumnQuery($query, $field, $value) {

		if ($value == PreSchool::ACTIVE)
			$query->andWhere ( $a . '.payment_status = ?', $value );
		elseif ($value == PreSchool::NOT_ACTIVE)
			$query->andWhere ( $a . '.payment_status = ?', $value );
		return $query;
	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsClassIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();
		$query->addWhere ( 'sc.myclass_id = ?', $value );
		return $query;
	}

	public function addKeywordsColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( '(LOWER(s.student_code) LIKE ? OR LOWER(TRIM(s.first_name)) LIKE ? OR LOWER(TRIM(s.last_name)) LIKE ? OR LOWER(' . $a . '.receipt_no) LIKE ?) ', array (
					$keywords,
					$keywords,
					$keywords,
					$keywords ) );
		}

		return $query;
	}

	public function addSchoolYearIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsMonthColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->addWhere ( 'DATE_FORMAT(' . $a . '.receipt_date,"%m-%Y") = ?', $value );

		return $query;
	}
}
