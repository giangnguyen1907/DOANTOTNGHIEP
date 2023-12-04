<?php

/**
 * PsCommentWeek filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsCommentWeekFormFilter extends BasePsCommentWeekFormFilter {

	public function configure() {

		// $this->disableLocalCSRFProtection ();
		$this->addPsCustomerFormFilter ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL', true );
		
		$ps_week = $this->getDefault ( 'ps_week' );
		
		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id == '') {
			$ps_customer_id = myUser::getPscustomerID ();
		}

		$this->setDefault ( 'ps_customer_id', $ps_customer_id );

		if ($ps_customer_id > 0) {
			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => true,
					'model' => 'PsWorkPlaces',
					'column' => 'id' ) );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorPass ( array (
					'required' => true ) );
		}

		$years = range ( date ( 'Y' ) + 1, sfConfig::get ( 'app_begin_year' ) );

		$this->widgetSchema ['ps_year'] = new sfWidgetFormChoice ( array (
				'choices' => array_combine ( $years, $years ) ), array (
				'class' => 'select2',
				'style' => "min-width:80px; width:auto;",
				'data-placeholder' => _ ( '-Select year-' ) ) );

		$this->validatorSchema ['ps_year'] = new sfValidatorPass ( array (
				'required' => true ) );

		$ps_year = date ( 'Y' );

		$this->setDefault ( 'ps_year', $ps_year );

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		if ($ps_workplace_id == '') {
			$member_id = myUser::getUser ()->getMemberId ();
			$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );

		$ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE )
			->getId ();

		$param_class = array (
				'ps_school_year_id' => $ps_school_year_id,
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'is_activated' => PreSchool::ACTIVE
		);

		if ($ps_customer_id > 0) {

			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'MyClass',
					'column' => 'id' ) );
		} else {
			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorPass ( array (
					'required' => false ) );
		}

		$ps_class_id = $this->getDefault ( 'ps_class_id' );

		$this->widgetSchema ['ps_month'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select month-' ) ) + PreSchool::loadPsMonth () ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => false,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) );

		$this->validatorSchema ['ps_month'] = new sfValidatorPass ( array (
				'required' => false ) );

		$ps_month = $this->getDefault ( 'ps_month' );

		$weeks = PsDateTime::getWeeksOfYear ( $ps_year );

		$this->widgetSchema ['ps_week'] = new sfWidgetFormChoice ( array (
				'choices' => PsDateTime::getOptionsWeeks ( $weeks ) ), array (
				'class' => 'select2',
				'style' => "min-width:250px;width:100%;",
				'data-placeholder' => _ ( '-Select district-' ) ) );

		$this->validatorSchema ['ps_week'] = new sfValidatorPass ( array (
				'required' => false ) );

		$this->setDefault ( 'ps_month', $ps_month );

		if ($ps_month > 0) {

			$this->widgetSchema ['ps_week']->setAttribute ( 'disabled', 'disabled' );

			$this->widgetSchema ['ps_week']->setAttribute ( 'style', 'background-color:#fff' );
		}

		// Get week in form
		$form_week_start = null;
		$form_week_end = null;
		$form_week_list = array ();

		if (isset ( $weeks [$ps_week - 1] )) {

			$weeks_form = $weeks [$ps_week - 1];

			$form_week_start = $weeks_form ['week_start'];

			$form_week_end = $weeks_form ['week_end'];

			$form_week_list = $weeks_form ['week_list'];
		}
		$ps_week = $this->getDefault ( 'ps_week' );

		if ($ps_week == '') {
			$ps_week = PsDateTime::getIndexWeekOfYear ( date ( 'Y-m-d' ) );
		}

		$this->setDefault ( 'ps_week', $ps_week );

		$ps_date_at = PsDateTime::getStaturdayOfWeek ( $ps_week, $ps_year );
		// echo $ps_date_at['week_end']; die;

		$this->widgetSchema ['school_year_id'] = new sfWidgetFormInputHidden ();

		$this->validatorSchema ['school_year_id'] = new sfValidatorPass ();

		$this->widgetSchema ['receivable_at'] = new sfWidgetFormInputHidden ();

		$this->validatorSchema ['receivable_at'] = new sfValidatorPass ();

		$this->setDefault ( 'receivable_at', $ps_date_at ['week_end'] );

		$this->setDefault ( 'school_year_id', $ps_school_year_id );

		$this->widgetSchema ['is_activated'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => 'Status' ) + PreSchool::loadBrowseArticles () ), array (
				'class' => 'form-control' ) );

		$this->validatorSchema ['is_activated'] = new sfValidatorChoice ( array (
				'choices' => array_keys ( PreSchool::loadBrowseArticles () ),
				'required' => false ) );

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ),
				'rel' => 'tooltip',
				'data-original-title' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Input: Student code, Fullname' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );
	}

	public function addSchoolYearIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsClassIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsYearColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsMonthColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsWeekColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addIsActivatedColumnQuery($query, $field, $value) {

		if ($value == PreSchool::ACTIVE)
			$query->andWhere ( 're.is_activated = ?', $value );
		elseif (PreString::trim ( $value ) && $value == PreSchool::NOT_ACTIVE)
			$query->andWhere ( 're.id IS NULL OR re.is_activated = ?', $value );

		return $query;
	}

	public function addReceivableAtColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addKeywordsColumnQuery($query, $field, $value) {

		return $query;
	}

	public function doBuildQuery(array $values) {

		$query = parent::doBuildQuery ( $values );

		$a = $query->getRootAlias ();

		$query->addSelect ( 'sc.myclass_id AS class_id,mc.id AS class_id,re.id AS comment_id, re.title AS title, re.comment AS comment,re.is_activated AS is_activated,re.ps_year AS ps_year,re.ps_month AS ps_month, re.ps_week AS ps_week,re.updated_at AS updated_at' );

		$query->addSelect ( 'CONCAT(u.first_name, " ", u.last_name) AS updated_by, mc.iorder AS iorder' );

		if (isset($values ['ps_month']) && $values ['ps_month'] != '') {

			$query->leftJoin ( $a . '.PsCommentWeek re With re.ps_month =?', $values ['ps_month'] );
			$tracked_at = '01-' . $values ['ps_month'] . '-' . $values ['ps_year'];
		} else {
			if(isset($values ['ps_week']))
			$query->leftJoin ( $a . '.PsCommentWeek re With re.ps_week =?', $values ['ps_week'] );
			else
				$query->leftJoin ( $a . '.PsCommentWeek re ');
			//$tracked_at = $values ['receivable_at'];
		}

		$query->leftJoin ( 're.UserUpdated u' );
		if(!isset($tracked_at)){
			$tracked_at = date('Y-m-d');
		}
		$date_at = date ( 'Ym', strtotime ( $tracked_at ) );

		$query->innerJoin ( $a . '.StudentClass sc With (DATE_FORMAT(sc.start_at,"%Y%m") <= ? AND (sc.stop_at IS NULL OR  DATE_FORMAT(sc.stop_at,"%Y%m") >= ?))', array (
				$date_at,
				$date_at ) );

		$query->leftJoin ( 'sc.MyClass mc' );

		if (isset($values ['ps_workplace_id']) && $values ['ps_workplace_id'] > 0) {
			$query->innerJoin ( 'mc.PsClassRooms cr With cr.ps_workplace_id = ?', $values ['ps_workplace_id'] );
		}

		$query->whereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );

		if (isset($values ['ps_class_id']) && $values ['ps_class_id'] > 0) {
			$query->andWhere ( 'sc.myclass_id = ?', $values ['ps_class_id'] );
		}
		if (isset($values ['keywords'])) {
			$keywords = PreString::trim ( $values ['keywords'] );
		}
		if (isset($keywords) && $keywords != '') {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( 'LOWER(' . $a . '.student_code) LIKE ? OR LOWER(' . $a . '.first_name) LIKE ? OR LOWER(' . $a . '.last_name) LIKE ? OR LOWER( CONCAT(' . $a . '.first_name," ", ' . $a . '.last_name) ) LIKE ?', array (
					$keywords,
					$keywords,
					$keywords,
					$keywords ) );
		}

		return $query;
	}
}
