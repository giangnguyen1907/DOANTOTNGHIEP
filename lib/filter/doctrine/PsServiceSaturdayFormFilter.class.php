<?php

/**
 * PsServiceSaturday filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsServiceSaturdayFormFilter extends BasePsServiceSaturdayFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_SERVICE_SATURDAY_FILTER_SCHOOL' );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
		
		$school_year_id = $this->getDefault ( 'school_year_id' );
		
		if ($school_year_id == '') {
			$school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}

		if ($ps_customer_id == '') {
			$ps_customer_id = myUser::getPscustomerID ();
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
			$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		}

		$this->setDefault ( 'school_year_id', $school_year_id );

		if ($ps_customer_id > 0) {

			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
		}
		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkPlaces',
				'column' => 'id' ) );

		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => false ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ) ) );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$this->widgetSchema ['school_year_id']->setOption ( 'add_empty', false );

		// $school_year_id = $this->getDefault('school_year_id');

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_school_year_id' => $school_year_id,
				'ps_workplace_id' => $ps_workplace_id,
				'is_activated' => PreSchool::ACTIVE
		);

		if ($ps_workplace_id > 0) {

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
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorPass ( array (
					'required' => false ) );
		}

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();
		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );
	}

	public function getFields() {

		$fields = parent::getFields ();
		$fields ['ps_school_year_id'] = 'ForeignKey';
		return $fields;
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$query->where ( 's.ps_customer_id = ?', $value );

		return $query;
	}

	// Add virtual class_id for filter
	public function addPsClassIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addKeywordsColumnQuery($query, $field, $value) {

		$a = 's';

		$keywords = PreString::trim ( $value );

		if (PreString::length ( $keywords ) > 0) {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( 'LOWER(' . $a . '.student_code) LIKE ? OR LOWER(' . $a . '.first_name) LIKE ? OR LOWER(' . $a . '.last_name) LIKE ? OR (LOWER( CONCAT(' . $a . '.first_name," ", ' . $a . '.last_name) ) LIKE ?) ', array (
					$keywords,
					$keywords,
					$keywords,
					$keywords ) );
		}

		return $query;
	}

	public function doBuildQuery(array $values) {

		$query = parent::doBuildQuery ( $values );

		$a = $query->getRootAlias ();
		// $query->select ('sc.id as sc_id, mc.id as mc_id');
		$date = date ( "Ymd" );

		$query->leftJoin ( 's.StudentClass sc With (DATE_FORMAT(sc.start_at,"%Y%m%d") <= ?  AND (sc.stop_at IS NULL OR  DATE_FORMAT(sc.stop_at,"%Y%m%d") >= ?))', array (
				$date,
				$date ) );

		$query->innerJoin ( 'sc.MyClass mc' );

		if (isset($values ['ps_workplace_id']) && $values ['ps_workplace_id'] > 0) {

			$query->innerJoin ( 'mc.PsClassRooms cr With cr.ps_workplace_id = ?', $values ['ps_workplace_id'] );
		}

		$query->whereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );

		if (isset($values ['ps_class_id']) && $values ['ps_class_id'] > 0) {

			$query->addWhere ( 'mc.id = ?', $values ['ps_class_id'] );
		}

		return $query;
	}
}
