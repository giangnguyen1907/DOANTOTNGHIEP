<?php
/**
 * PsStudentGrowths filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsStudentGrowthsFormFilter extends BasePsStudentGrowthsFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_MEDICAL_GROWTH_FILTER_SCHOOL' );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		$obj_group_id = $this->getDefault ( 'ps_obj_group_id' );

		$school_year_id = $this->getDefault ( 'school_year_id' );

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		if ($ps_customer_id == '') {
			$ps_customer_id = myUser::getPscustomerID ();
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => false // _ ( '-Select school year-' )
		), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ) ) );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$this->widgetSchema ['school_year_id']->setOption ( 'add_empty', false );

		$school_year_id = $this->getDefault ( 'school_year_id' );

		if ($school_year_id == '') {
			$school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}

		$this->setDefault ( 'school_year_id', $school_year_id );

		$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );
		if ($ps_customer_id > 0) {

			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			// ps_workplace_id filter by examination_id
			$this->widgetSchema ['examination_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsExamination',
					'query' => Doctrine::getTable ( 'PsExamination' )->setSqlListExaminationByParams ( array (
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id,
							'ps_school_year_id' => $school_year_id ) ),
					'add_empty' => '-Select examination-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select examination-' ) ) );

			$this->validatorSchema ['examination_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'PsExamination',
					'column' => 'id' ) );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->widgetSchema ['examination_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select examination-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select examination-' ) ) );

			$this->validatorSchema ['examination_id'] = new sfValidatorPass ();
		}

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkPlaces',
				'column' => 'id' ) );

		// nhom tre
		$this->widgetSchema ['ps_obj_group_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsObjectGroups',
				'query' => Doctrine::getTable ( 'PsObjectGroups' )->setSQL (),
				'add_empty' => '-Select object-' ), array (
				'class' => 'select2',
				'style' => "min-width:200px;",
				'data-placeholder' => _ ( '-Select object-' ) ) );

		$this->validatorSchema ['ps_obj_group_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsObjectGroups',
				'column' => 'id' ) );

		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_school_year_id' => $school_year_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_obj_group_id' => $obj_group_id,
				'is_activated' => PreSchool::ACTIVE );

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

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

	// Add virtual_column_name for filter
	public function addSchoolYearIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$query->where ( 's.ps_customer_id = ?', $value );

		return $query;
	}

	// Add virtual class_id for filter
	public function addPsClassIdColumnQuery($query, $field, $value) {

		$query->addWhere ( 'mc.id = ?', $value );

		return $query;
	}

	// Add virtual examination_id for filter
	public function addExaminationIdColumnQuery($query, $field, $value) {

		$query->addWhere ( 'ex.id = ?', $value );

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

		if (isset($values ['examination_id']) && $values ['examination_id'] > 0) {

			$query->addWhere ( 'ex.id = ?', $values ['examination_id'] );

			$query->leftJoin ( 's.StudentClass sc With (DATE_FORMAT(sc.start_at,"%Y%m%d") <= DATE_FORMAT(ex.input_date_at,"%Y%m%d")  AND (sc.stop_at IS NULL OR  DATE_FORMAT(sc.stop_at,"%Y%m%d") >= DATE_FORMAT(ex.input_date_at,"%Y%m%d")))' );
		} else {

			$date = date ( "Ymd" );

			$query->leftJoin ( 's.StudentClass sc With (DATE_FORMAT(sc.start_at,"%Y%m%d") <= ?  AND (sc.stop_at IS NULL OR  DATE_FORMAT(sc.stop_at,"%Y%m%d") >= ?))', array (
					$date,
					$date ) );
		}

		$query->innerJoin ( 'sc.MyClass mc' );

		if (isset($values ['ps_workplace_id']) && $values ['ps_workplace_id'] > 0) {

			$query->innerJoin ( 'mc.PsClassRooms cr With cr.ps_workplace_id = ?', $values ['ps_workplace_id'] );
		}

		$query->innerJoin ( 'mc.PsObjectGroups ob' );

		if (isset($values ['ps_obj_group_id']) && $values ['ps_obj_group_id'] > 0) {

			$query->addWhere ( 'ob.id = ?', $values ['ps_obj_group_id'] );
		}

		if (isset($values ['ps_class_id']) && $values ['ps_class_id'] > 0) {

			$query->addWhere ( 'mc.id = ?', $values ['ps_class_id'] );
		}

		$query->whereIn ( 'sc.type', array (
				PreSchool::SC_STATUS_OFFICIAL,
				PreSchool::SC_STATUS_TEST ) );

		$query->addWhere ( 'ex.school_year_id = ?', $values ['school_year_id'] );

		return $query;
	}

	/*
	 * public function doBuildQuery(array $values) {
	 * $query = parent::doBuildQuery($values);
	 * $a = $query->getRootAlias();
	 * $query->addSelect ( 'psg.id AS id,'.
	 * 'psg.height AS height, ' .
	 * 'psg.weight AS weight, psg.index_height AS index_height, psg.index_weight AS index_weight, ' .
	 * 'psg.input_date_at AS input_date_at, ' .
	 * 'psg.people_make AS people_make, ' .
	 * 'psg.examination_id AS examination_id, ' .
	 * 'psg.organization_make AS organization_make, ' .
	 * 'psg.note AS note, ' .
	 * 'psg.user_updated_id AS user_updated_id, ' .
	 * 'psg.updated_at AS updated_at, s.id AS student_id');
	 * $query->addSelect('CONCAT(u.first_name, " ", u.last_name) AS updated_by');
	 * $query->leftJoin( $a.'.PsStudentGrowths psg');
	 * $query->innerJoin('psg.PsExaminations ex');
	 * $query->innerJoin('psg.UserUpdated u');
	 * //$query->innerJoin($a.'.StudentClass sc');
	 * $query->leftJoin($a.'.StudentClass sc With (DATE_FORMAT(sc.start_at,"%Y%m%d") <= DATE_FORMAT(ex.input_date_at,"%Y%m%d") AND (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= DATE_FORMAT(ex.input_date_at,"%Y%m%d")))');
	 * if ($values['ps_workplace_id'] > 0) {
	 * $query->innerJoin('sc.MyClass mc');
	 * $query->innerJoin('mc.PsClassRooms cr With cr.ps_workplace_id = ?', $values['ps_workplace_id']);
	 * }
	 * if ($values['examination_id'] > 0) {
	 * $query->addWhere('psg.examination_id = ?', $values['examination_id']);
	 * }
	 * $query->whereIn('sc.type',array(PreSchool::SC_STATUS_OFFICIAL, PreSchool::SC_STATUS_TEST));
	 * if ($values['examination_id'] > 0) {
	 * $query->leftJoin($a.'.StudentClass sc With (DATE_FORMAT(sc.start_at,"%Y%m%d") <= DATE_FORMAT(ex.input_date_at,"%Y%m%d") AND (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= DATE_FORMAT(ex.input_date_at,"%Y%m%d")))');
	 * } else {
	 * $date = date("Ymd");
	 * $query->leftJoin($a.'.StudentClass sc With (DATE_FORMAT(sc.start_at,"%Y%m%d") <= ? AND (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= ?))', array($date,$date));
	 * }
	 * $query->whereIn('sc.type',array(PreSchool::SC_STATUS_OFFICIAL, PreSchool::SC_STATUS_TEST));
	 * return $query;
	 * }
	 */
}
