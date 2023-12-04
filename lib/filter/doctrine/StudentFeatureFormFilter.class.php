<?php
/**
 * StudentFeature filter form.
 *
 * @package    backend
 * @subpackage filter
 * @author     Nguyen Chien Thang <ntsc279@hotmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class StudentFeatureFormFilter extends BaseStudentFeatureFormFilter {

	public function configure() {

		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (), // getPsSchoolYearsDefault (),
				'add_empty' => false ), array (
				'class' => 'select2',
				'style' => "min-width:120px;",
				'data-placeholder' => _ ( '-Select school year-' ) ) );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$this->widgetSchema ['school_year_id']->setOption ( 'add_empty', false );

		$this->widgetSchema ['school_year_id']->setAttributes ( array (
				'style' => 'min-width:120px;',
				'class' => 'select2',
				'required' => true ) );

		$school_year_id = $this->getDefault ( 'school_year_id' );

		/* */
		if ($school_year_id <= 0)
			$school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId ();

		$this->setDefault ( 'school_year_id', $school_year_id );

		// if ($school_year_id ==''){
		// $school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId ();
		// }

		// Xem lai dau vao cua ham addPsCustomerFormFilter();
		$this->addPsCustomerFormFilter ( 'PS_STUDENT_FEATURE_FILTER_SCHOOL' );

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_FEATURE_FILTER_SCHOOL' )) {

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => true,
					'model' => 'PsCustomer',
					'column' => 'id' ) );

			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'style' => 'min-width:200px;width:100%;',
					'class' => 'select2',
					'required' => true ) );
		}

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		/**
		 * * BEGIN: Kiem tra phan cong lop hoc **
		 */
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
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
		}

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsWorkPlaces',
				'column' => 'id' ) );

		$this->widgetSchema ['ps_workplace_id']->setAttributes ( array (
				'style' => 'min-width:200px;',
				'class' => 'select2',
				'required' => false ) );

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_school_year_id' => $school_year_id,
				'ps_workplace_id' => $ps_workplace_id,
				'is_activated' => PreSchool::ACTIVE
		);

		// $this->widgetSchema ['school_year_to_date'] = new sfWidgetFormInputHidden();

		if ($ps_customer_id > 0) {

			if (myUser::credentialPsCustomers ( 'PS_STUDENT_FEATURE_FILTER_SCHOOL' ) || ! sfContext::getInstance ()->getUser ()
				->hasCredential ( 'PS_STUDENT_ATTENDANCE_TEACHER' )) {
				$sqlMyClass = Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class );
			} else {
				$sqlMyClass = Doctrine::getTable ( 'MyClass' )->getClassIdByUserIdWorkplace ( myUser::getUserId (), $ps_workplace_id );
			}

			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => $sqlMyClass,
					'add_empty' => _ ( '-Select class-' ) ), array (
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => true,
					'model' => 'MyClass',
					'column' => 'id' ) );
		} else {
			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorInteger ( array (
					'required' => true ) );
		}

		$this->widgetSchema ['ps_class_id']->setAttributes ( array (
				'style' => 'min-width:200px;',
				'class' => 'select2',
				'required' => true ) );

		$this->widgetSchema ['tracked_at'] = new psWidgetFormFilterInputDate ();

		$this->widgetSchema ['tracked_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => 'Tracked at',
				'required' => true ) );

		$this->validatorSchema ['tracked_at'] = new sfValidatorDate ( array (
				'required' => true ) );

		$this->widgetSchema ['tracked_at']->setAttributes ( array (
				'style' => 'max-width:120px;',
				'required' => true ) );

		$tracked_at = $this->getDefault ( 'tracked_at' );

		$ps_class_id = $this->getDefault ( 'ps_class_id' );

		if ($ps_class_id > 0) {

			$params = array ();

			$params ['ps_customer_id'] = $ps_customer_id;
			$params ['ps_school_year_id'] = $school_year_id;
			$params ['ps_workplace_id'] = $ps_workplace_id;
			$params ['ps_myclass_id'] = $ps_class_id;
			$params ['tracked_at'] = $tracked_at;
			$params ['is_activated'] = PreSchool::ACTIVE;
			$params ['number_option'] = PreSchool::ACTIVE;

			$this->widgetSchema ['feature_branch_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'FeatureBranch',
					// 'query' => Doctrine::getTable ( 'FeatureBranch' )->setSqlFeatureBranch ( $ps_customer_id ),
					'query' => Doctrine::getTable ( 'FeatureBranch' )->setSqlFeatureBranchByMyClassParams ( $params ),
					'add_empty' => _ ( '-Select feature branch-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select feature branch-' ) ) );
		} else {
			$this->widgetSchema ['feature_branch_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select feature branch-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select feature branch-' ) ) );
		}

		$this->validatorSchema ['feature_branch_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'FeatureBranch',
				'column' => 'id' ) );

		$this->widgetSchema ['feature_branch_id']->setAttributes ( array (
				'style' => 'min-width:200px;',
				'class' => 'select2',
				'required' => true ) );

		$this->showUseFields ();
	}

	// Add virtual_column_name for filter
	public function addSchoolYearIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsClassIdColumnQuery($query, $field, $value) {

		$query->andWhere ( 'sc.myclass_id = ?', $value );

		return $query;
	}

	public function addTrackedAtColumnQuery($query, $field, $value) {

		/*
		 * if ($value) {
		 * $query->innerJoin('s.PsLogtimes lt With DATE_FORMAT(lt.login_at,"%Y%m%d") = ?', date('Ymd', strtotime($value)))
		 * ->andWhere(' DATE_FORMAT(sc.start_at,"%Y%m%d") <= ?', date('Ymd', strtotime($value)))
		 * ->andWhere('sc.stop_at IS NULL OR DATE_FORMAT(sc.stop_at,"%Y%m%d") >= ?', date('Ymd', strtotime($value)));
		 * } else {
		 * $query->innerJoin('s.PsLogtimes lt');
		 * }
		 */
		$query->andWhere ( 'sc.stop_at IS NULL OR  DATE_FORMAT(sc.stop_at,"%Y%m%d") >= ?', date ( 'Ymd', strtotime ( $value ) ) );
		
		//$query->andWhere ( 'DATE_FORMAT(lt.login_at,"%Y%m%d") = ?', date ( 'Ymd', strtotime ( $value ) ) );
		
		return $query;
	}
	
	public function doBuildQuery(array $values) {
		
		$query = parent::doBuildQuery ( $values );
		
		if (isset($values ['feature_branch_id']) && $values ['feature_branch_id'] > 0) {
			
			$featureBranch = Doctrine::getTable("FeatureBranch")->getFeatureBranchByField($values ['feature_branch_id'], 'is_depend_attendance');
			//echo $values ['tracked_at'];die;
			if($featureBranch->getIsDependAttendance() == PreSchool::ACTIVE){
				$query->andWhere ( 'DATE_FORMAT(lt.login_at,"%Y%m%d") = ?', date ( 'Ymd', strtotime ( $values ['tracked_at'] ) ) );
			}
		}
		
		return $query;
		
	}
	
	// Add virtual class_id for filter
	protected function showUseFields() {

		$this->useFields ( array (
				'school_year_id',
				'ps_customer_id',
				'ps_workplace_id',
				'ps_class_id',
				'feature_branch_id',
				'tracked_at' ) );
	}
}