<?php

/**
 * ReceivableTemp filter form.
 *
 * @package    backend
 * @subpackage filter
 * @author     Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ReceivableTempFormFilter extends BaseReceivableTempFormFilter {

	public function configure() {

		$ps_month = $this->getDefault ( 'ps_month' );
		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		if ($ps_month == '') {
			$ps_month = date ( "m-Y" );
		}

		if ($ps_customer_id == '') {
			$ps_customer_id = myUser::getPscustomerID ();
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		$this->setDefault ( 'ps_month', $ps_month );

		// lay nam hoc hien tai
		$this->widgetSchema ['ps_school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault (),
				'add_empty' => false ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ) ) );

		$this->validatorSchema ['ps_school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$this->widgetSchema ['ps_school_year_id']->setOption ( 'add_empty', false );

		$ps_school_year_id = $this->getDefault ( 'ps_school_year_id' );

		$this->addPsCustomerFormFilter ( 'PS_FEE_RECEIVABLE_FILTER_SCHOOL' );

		if ($ps_school_year_id > 0) {

			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolyear' )->findOneById ( $ps_school_year_id );

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

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_school_year_id' => $ps_school_year_id );

		if ($ps_customer_id > 0) {

			$this->widgetSchema ['ps_myclass_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );
		} else {
			$this->widgetSchema ['ps_myclass_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );
		}

		$this->validatorSchema ['ps_myclass_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'MyClass',
				'column' => 'id' ) );

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

		$query->andWhere ( 'DATE_FORMAT(' . $a . '.receivable_at, "%m-%Y") LIKE ?', $value );

		return $query;
	}

	public function addPsSchoolYearIdColumnQuery($query, $field, $value) {

		$query->andWhere ( 're.ps_school_year_id =? ', $value );

		return $query;
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$query->where ( 're.ps_customer_id = ?', $value );

		return $query;
	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->andWhere ( '(re.ps_workplace_id = ? OR re.ps_workplace_id IS NULL)', $value );

		return $query;
	}

	public function addPsMyclassIdColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$query->andWhere ( '(' . $a . '.ps_myclass_id = ? OR ' . $a . '.ps_myclass_id IS NULL)', $value );

		return $query;
	}

	public function addKeywordsColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( '(LOWER(' . $a . '.note) LIKE ? OR LOWER(re.title) LIKE ?) ', array (
					$keywords,
					$keywords ) );
		}

		return $query;
	}
}
