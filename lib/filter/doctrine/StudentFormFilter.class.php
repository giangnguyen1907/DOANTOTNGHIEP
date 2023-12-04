<?php
/**
 * Student filter form.
 *
 * @package Preschool
 * @subpackage filter
 * @author Your name here
 * @version SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class StudentFormFilter extends BaseStudentFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_STUDENT_MSTUDENT_FILTER_SCHOOL' );

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
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'form-control',
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
				'add_empty' => _ ( '-Select school year-' ) ), array (
				'class' => 'form-control',
				'style' => "min-width:120px;",
				'data-placeholder' => _ ( '-Select school year-' ) ) );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$this->widgetSchema ['school_year_id']->setOption ( 'add_empty', true );

		$school_year_id = $this->getDefault ( 'school_year_id' );
		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		$param_class = array (
				'ps_customer_id'    => $ps_customer_id,
				'ps_workplace_id'   => $ps_workplace_id,
				'ps_school_year_id' => $school_year_id,
				'is_activated'		=> PreSchool::ACTIVE
		);

		// $this->widgetSchema ['school_year_from_date'] = new sfWidgetFormInputHidden();
		// $this->widgetSchema ['school_year_to_date'] = new sfWidgetFormInputHidden();
		$psClass = Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class )->execute();
		$array_class = array();
		foreach ($psClass as $class){
			$array_class[$class->getId()] = $class->getTitle();
		}
		
		/*
		if (myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ) || ! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_TEACHER' )) {
			$sqlMyClass = Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class );
		} else {
			$sqlMyClass = Doctrine::getTable ( 'MyClass' )->getClassIdByUserIdWorkplace ( myUser::getUserId (), $ps_workplace_id );
		}
		*/
		/*
		if ($ps_customer_id > 0) {
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
				'model' => 'MyClass',
				'column' => 'id',
				'required' => false ) );
		*/
		
		$this->widgetSchema ['ps_class_id'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => '-Select class-' ) + $array_class + PreSchool::loadClassNotActive () ), 
				array (
					'class' => 'select2',
					'style' => 'min-width:150px;',
					'data-placeholder' => sfContext::getInstance ()->getI18n () ->__ ( '-Select class-' ) ) );
		
		$this->widgetSchema ['ps_class_id']->setAttributes ( array (
				'style' => 'min-width:200px;',
				'class' => 'select2',
				'required' => false ) );
		
// 		$this->validatorSchema ['ps_class_id'] = new sfValidatorChoice ( array (
// 				'choices' => array_keys ( $array_class + PreSchool::$not_in_class_active),
// 				'required' => false ) );
		
		$this->validatorSchema ['ps_class_id'] = new sfValidatorString ( array (
				'required' => false ) );
		
		$this->widgetSchema ['s_type'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => '-Select state-' ) + array (
						'CTHT' => sfContext::getInstance ()->getI18n ()->__ ( 'Activities' ) . '+' . sfContext::getInstance ()->getI18n ()
							->__ ( 'School test' ) ) + PreSchool::loadStatusStudentClass () ), array (
				'class' => 'select2',
				'style' => 'min-width:150px;',
				// 'multiple ' => true,
				// 'expanded' => false,
				'data-placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( '-Select status-' ) ) );

		$this->widgetSchema ['s_type']->setAttributes ( array (
				'style' => 'min-width:200px;',
				'class' => 'select2',
				'required' => false ) );

		$this->validatorSchema ['s_type'] = new sfValidatorChoice ( array (
				'choices' => array_keys ( array (
						'CTHT' => '' ) + PreSchool::$status_student_not_class ),
				'required' => false ) );
		// 'multiple' => true

		$this->widgetSchema ['sex'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => '-Gender-' ) + PreSchool::loadPsGender () ), array (
				'class' => 'form-control' ) );

		$this->validatorSchema ['sex'] = new sfValidatorChoice ( array (
				'choices' => array_keys ( PreSchool::$ps_gender ),
				'required' => false ) );

		$this->widgetSchema ['delete'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => '-State archives-',
						PreSchool::NOT_ACTIVE => 'Activity',
						PreSchool::ACTIVE => 'Archives' ) ), array (
				'class' => 'form-control' ) );

		$this->validatorSchema ['delete'] = new sfValidatorInteger ( array (
				'required' => false ) );

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );

		/*
		 * if (! myUser::credentialPsCustomers()) { $this->widgetSchema['delete'] = new sfWidgetFormInputHidden(); $this->setDefault('delete', 2); $this->validatorSchema['delete'] = new sfValidatorInteger(array( 'required' => true )); }
		 */

		$this->showUseFields ();
	}

	protected function showUseFields() {

		$this->useFields ( array (
				'school_year_id',
				'ps_customer_id',
				'ps_workplace_id',
				'ps_class_id',
				's_type', // trang thai trong lop hoc
				'sex',
				'delete',
				'keywords' ) );
	}

	public function addKeywordsColumnQuery($query, $field, $value) {

		$a = $query->getRootAlias ();
		
		
		$keywords = PreString::trim ( $value );

		if ($keywords != '') {

			$keywords = '%' . PreString::strLower ( $keywords ) . '%';

			$query->addWhere ( 'LOWER(TRIM(' . $a . '.student_code)) LIKE ? OR LOWER(TRIM(' . $a . '.first_name)) LIKE ? OR LOWER(TRIM(' . $a . '.last_name)) LIKE ? OR (LOWER( CONCAT(TRIM(' . $a . '.first_name)," ", TRIM(' . $a . '.last_name)) ) LIKE ?) ', array (
					$keywords,
					$keywords,
					$keywords,
					$keywords ) );
		}
		
		return $query;
	}

	// Add virtual column_name for filter
	public function addPsCustomerIdColumnQuery($query, $field, $value) {
		
		$a = $query->getRootAlias ();
		
		$query->addWhere ( $a . '.ps_customer_id = ?', $value );
		
		return $query;
	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsClassIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addSchoolYearIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addSTypeColumnQuery($query, $field, $value) {

		/*
		 * $a = $query->getRootAlias ();
		 * if ($value == 'CTHT')
		 * $value = array (
		 * PreSchool::SC_STATUS_OFFICIAL,
		 * PreSchool::SC_STATUS_TEST );
		 * $query->andWhereIn ( 'sc.type', $value );
		 */
		return $query;
	}

	public function addDeleteColumnQuery($query, $field, $value) {

		/*
		 * $a = $query->getRootAlias ();
		 * if ($value == PreSchool::ACTIVE) {
		 * $query->andWhere ( $a . '.deleted_at IS NOT NULL' );
		 * } else {
		 * $query->andWhere ( $a . '.deleted_at IS NULL' );
		 * }
		 */
		return $query;
	}

	public function doBuildQuery(array $values) {

		$query = parent::doBuildQuery ( $values );

		$a = $query->getRootAlias ();
		
		$query->addSelect("sc.id AS sc_id,mc.id AS class_id,mc.name AS class_name");
				
		if (isset($values ['ps_class_id']) && $values ['ps_class_id'] == PreSchool::NOT_IN_CLASS) { // Neu trang thai la chua phan lop
			
			$query->leftJoin ( $a . '.StudentClass sc' );
			$query->leftJoin ( 'sc.MyClass mc' );

			if ($values ['ps_workplace_id'] > 0) {
				$query->andWhere ( $a . '.ps_workplace_id = ?', $values ['ps_workplace_id'] );
			}

			if ($values ['delete'] == PreSchool::ACTIVE) {
				
				$query->addWhere ( $a . '.deleted_at IS NOT NULL' );
			} elseif ($values ['delete'] == PreSchool::NOT_ACTIVE && PreString::length ( $values ['delete'] ) > 0) {
				$query->addWhere ( $a . '.deleted_at IS NULL' );
			}
			
			$query->addWhere ('sc.id IS NULL' );
			
		} elseif(isset($values ['ps_class_id']) && $values ['ps_class_id'] == PreSchool::CLASS_LOCKED){ // Trang thai la lop da khoa
			
			$query->innerJoin ( $a . '.StudentClass sc' );
			
			if ($values ['ps_workplace_id'] > 0) {
				$query->andWhere ( $a . '.ps_workplace_id = ?', $values ['ps_workplace_id'] );
			}
			
			if ($values ['delete'] == PreSchool::ACTIVE) {
				
				$query->addWhere ( $a . '.deleted_at IS NOT NULL' );
				
			} elseif ($values ['delete'] == PreSchool::NOT_ACTIVE && PreString::length ( $values ['delete'] ) > 0) {
				$query->addWhere ( $a . '.deleted_at IS NULL' );
			}
			
			$query->innerJoin ( 'sc.MyClass mc' );
			
			$query->addWhere ('mc.is_activated =?',PreSchool::NOT_ACTIVE );
			
		} else {
			
			$today = date ( 'Ymd' );
			
			if ($values ['school_year_id'] > 0) {

				$school_year = Doctrine::getTable ( 'PsSchoolYear' )->findOneById ($values ['school_year_id']);
				
				if ($school_year->getIsDefault () == PreSchool::ACTIVE && (isset($values ['ps_class_id']) && $values ['ps_class_id'] > 0) ) {
					$query->innerJoin ( $a . '.StudentClass sc With (DATE_FORMAT(sc.start_at,"%Y%m%d") <= ? AND (sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= ?))', array ($today,$today ) );					
					$query->andWhere ( 'sc.is_activated = ?', PreSchool::ACTIVE);
				} elseif($school_year->getIsDefault () == PreSchool::ACTIVE) {
				    $query->andWhere ( 'sc.is_activated = ?', PreSchool::ACTIVE);
					$query->innerJoin ( $a . '.StudentClass sc' );
				}else{
				    $query->innerJoin ( $a . '.StudentClass sc' );
				}

				$query->innerJoin ( 'sc.MyClass mc' );
				
				$query->innerJoin ( 'mc.PsWorkPlaces wp' );
				
				if ($values ['ps_workplace_id'] > 0) {
					$query->andWhere ( 'wp.id = ?', $values ['ps_workplace_id'] );
				}

				$query->innerJoin ( 'mc.PsSchoolYear sy With mc.school_year_id = ?', $values ['school_year_id'] );
				
				if (isset($values ['ps_class_id']) && $values ['ps_class_id'] > 0) {

					$query->andWhere ( 'mc.id = ?', $values ['ps_class_id'] );
				}
				//$query->andWhere ( 'sc.is_activated = ?', PreSchool::ACTIVE);
				
			} else {

				if ($values ['ps_workplace_id'] > 0) {
					
					$query->leftJoin ( $a . '.PsWorkPlaces wp With wp.id =?', $values ['ps_workplace_id'] );
					
				} else {
					$query->leftJoin ( $a . '.PsWorkPlaces wp' );
				}

				$query->leftJoin ( $a . '.StudentClass sc' );

				$query->leftJoin ( 'sc.MyClass mc' );
				
				if (isset($values ['ps_class_id']) && $values ['ps_class_id'] > 0) {

					$query->andWhere ( 'mc.id = ?', $values ['ps_class_id'] );
				}
				
			}
			
			$query->andWhere ( 'mc.is_activated = ?', PreSchool::ACTIVE);
			
			if (isset($values ['delete']) && $values ['delete'] == PreSchool::ACTIVE) {
				$query->addWhere ( $a . '.deleted_at IS NOT NULL' );
			} elseif (isset($values ['delete']) && $values ['delete'] == PreSchool::NOT_ACTIVE && PreString::length ( $values ['delete'] ) > 0) {
				$query->addWhere ( $a . '.deleted_at IS NULL' );
			}
			
			if ($values ['s_type'] == 'CTHT') {

				$value = array (
						PreSchool::SC_STATUS_OFFICIAL,
						PreSchool::SC_STATUS_TEST );

				$query->andWhereIn ( 'sc.type', $value );
				
			} elseif ($values ['s_type'] != '') {
				$query->andWhere ( 'sc.type = ?', $values ['s_type'] );
			} elseif ($values ['s_type'] == '') {
				
				//$value = array (PreSchool::SC_STATUS_OFFICIAL,PreSchool::SC_STATUS_TEST, PreSchool::SC_STATUS_PAUSE, PreSchool::SC_STATUS_HOLD, PreSchool::SC_STATUS_GRADUATION, PreSchool::SC_STATUS_STOP_STUDYING);
				
				//$query->andWhereIn ( 'sc.type', $value );
				
			}
		}

		if ($values ['ps_customer_id'] > 0) {
			$query->addWhere ( $a . '.ps_customer_id = ?', $values ['ps_customer_id'] );
		}

		return $query;
	}	
}
