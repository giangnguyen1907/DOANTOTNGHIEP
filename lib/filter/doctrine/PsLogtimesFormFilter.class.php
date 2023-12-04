<?php

/**
 * PsLogtimes filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsLogtimesFormFilter extends BasePsLogtimesFormFilter {

	public function setup() {

		$this->disableLocalCSRFProtection ();

		$this->setWidgets ( array (
				'student_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'Student' ),
						'add_empty' => true
				) ),
				'login_at' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'DayInMonth' ),
						'add_empty' => true
				) ),
				'login_relative_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'RelativeLogin' ),
						'add_empty' => true
				) ),
				'login_member_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'PsMemberLogin' ),
						'add_empty' => true
				) ),
				'logout_at' => new sfWidgetFormFilterDate ( array (
						'from_date' => new sfWidgetFormDate (),
						'to_date' => new sfWidgetFormDate ()
				) ),
				'logout_member_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'PsMemberLogout' ),
						'add_empty' => true
				) ),
				'logout_relative_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'RelativeLogout' ),
						'add_empty' => true
				) ),
				'log_value' => new sfWidgetFormFilterInput (),
				'note' => new sfWidgetFormFilterInput (),
				'user_created_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'UserCreated' ),
						'add_empty' => true
				) ),
				'user_updated_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'UserUpdated' ),
						'add_empty' => true
				) )
		) );

		$this->setValidators ( array (
				'student_id' => new sfValidatorDoctrineChoice ( array (
						'required' => false,
						'model' => $this->getRelatedModelName ( 'Student' ),
						'column' => 'id'
				) ),
				'login_at' => new sfValidatorDoctrineChoice ( array (
						'required' => false,
						'model' => $this->getRelatedModelName ( 'DayInMonth' ),
						'column' => 'id'
				) ),
				'login_relative_id' => new sfValidatorDoctrineChoice ( array (
						'required' => false,
						'model' => $this->getRelatedModelName ( 'RelativeLogin' ),
						'column' => 'id'
				) ),
				'login_member_id' => new sfValidatorDoctrineChoice ( array (
						'required' => false,
						'model' => $this->getRelatedModelName ( 'PsMemberLogin' ),
						'column' => 'id'
				) ),
				'logout_at' => new sfValidatorDateRange ( array (
						'required' => false,
						'from_date' => new sfValidatorDateTime ( array (
								'required' => false,
								'datetime_output' => 'Y-m-d 00:00:00'
						) ),
						'to_date' => new sfValidatorDateTime ( array (
								'required' => false,
								'datetime_output' => 'Y-m-d 23:59:59'
						) )
				) ),
				'logout_member_id' => new sfValidatorDoctrineChoice ( array (
						'required' => false,
						'model' => $this->getRelatedModelName ( 'PsMemberLogout' ),
						'column' => 'id'
				) ),
				'logout_relative_id' => new sfValidatorDoctrineChoice ( array (
						'required' => false,
						'model' => $this->getRelatedModelName ( 'RelativeLogout' ),
						'column' => 'id'
				) ),
				'log_value' => new sfValidatorPass ( array (
						'required' => false
				) ),
				'note' => new sfValidatorPass ( array (
						'required' => false
				) ),
				'user_created_id' => new sfValidatorDoctrineChoice ( array (
						'required' => false,
						'model' => $this->getRelatedModelName ( 'UserCreated' ),
						'column' => 'id'
				) ),
				'user_updated_id' => new sfValidatorDoctrineChoice ( array (
						'required' => false,
						'model' => $this->getRelatedModelName ( 'UserUpdated' ),
						'column' => 'id'
				) )
		) );

		$this->widgetSchema->setNameFormat ( 'ps_logtimes_filters[%s]' );

		$this->errorSchema = new sfValidatorErrorSchema ( $this->validatorSchema );

		$this->setupInheritance ();

	}

	public function configure() {

		/**
		 * Chỉ thực hiện với năm học hiện tại - đang được kích hoạt.
		 * Nếu chọn xem năm học cũ => chỉ về màn hình hiển thị xem thông tin
		 *
		 * TH1: Có quyền lọc theo trường học => lọc tất cả
		 * TH2: Ko có quyền lọc theo trường, nhưng có quyền điểm danh => Được chọn lớp
		 * TH3: Là GV phụ trách lớp => Có quyền chọn các lớp được giảng dạy
		 */
		$this->addPsCustomerFormFilter ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' );

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {

			$ps_customer_id = myUser::getPscustomerID ();

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorInteger ( array (
					'required' => true
			) );
		} else {

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' )
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => true,
					'data-placeholder' => _ ( '-All school-' )
			) );

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true
			) );
		}

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		$school_year_id = $this->getDefault ( 'school_year_id' );
		$ps_class_id = $this->getDefault ( 'ps_class_id' );

		if ($school_year_id == '') {
			$school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId ();
		}

		if ($ps_customer_id == '') {
			$ps_customer_id = myUser::getPscustomerID ();
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
		}

		$tracked_at = $this->getDefault ( 'tracked_at' );
		if ($tracked_at == '') {
			$tracked_at = date ( 'Y-m-d' );
			$this->setDefault ( 'tracked_at', $tracked_at );
		}

		$this->setDefault ( 'school_year_id', $school_year_id );

		$this->setDefault ( 'ps_class_id', $ps_class_id );

		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => '-Select school year-'
		), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' )
		) );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsSchoolYear',
				'column' => 'id'
		) );

		$school_year_id = $this->getDefault ( 'school_year_id' );

		// PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL
		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

		if ($ps_workplace_id == '') {
			$member_id = myUser::getUser ()->getMemberId ();
			$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		if ($ps_customer_id > 0) {
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' )
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' )
			) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false
			) );
		} else {
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' )
					)
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select workplace-' )
			) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorPass ();
		}

		$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );

		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_school_year_id' => $school_year_id,
				'is_activated' => PreSchool::ACTIVE
		);

		$this->widgetSchema ['attendance_type'] = new sfWidgetFormSelect ( array (
				'choices' => array (
						'' => _ ( 'Attendance go' ),
						'1' => _('Attendance out')
				)
		) );
		
		if ($ps_workplace_id > 0) {

			if (myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ) || ! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_TEACHER' )) {
				$sqlMyClass = Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class );
			} else {
				$sqlMyClass = Doctrine::getTable ( 'MyClass' )->getClassIdByUserIdWorkplace ( myUser::getUserId (), $ps_workplace_id );
			}

			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => $sqlMyClass,
					'add_empty' => _ ( '-Select class-' )
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' )
			) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => false
			) );
		} else {
			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' )
					)
			), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' )
			) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorPass ();
		}

		// Ngay diem danh
		$this->widgetSchema ['tracked_at'] = new psWidgetFormFilterInputDate ();

		$this->validatorSchema ['tracked_at'] = new sfValidatorDate ( array (
				'required' => true,
				'max' => date ( 'Y-m-d' )
		), array (
				'invalid' => 'Invalid tracked at',
				'max' => 'Date must be no larger than %max%'
		) );

		$this->widgetSchema ['tracked_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => 'Tracked at',
				'required' => true
		) );

		$this->widgetSchema ['date_time'] = new sfWidgetFormInputHidden ();
		$this->validatorSchema ['date_time'] = new sfValidatorDate ( array (
				'required' => true
		) );

		$this->setDefault ( 'tracked_at', $tracked_at );
		$this->setDefault ( 'date_time', $tracked_at );

		$this->widgetSchema ['attendance_type']->setAttributes ( array (
				'class' => 'form-control',
				'required' => false
		) );
		
		$this->validatorSchema ['attendance_type'] = new sfValidatorPass ( array (
				'required' => true
		) );

		// $this->showUseFields ();
	}

	// Add virtual_column_name for filter
	public function addSchoolYearIdColumnQuery($query, $field, $value) {

		return $query;

	}
	
	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {
		
		$query->andWhere ( 'c.ps_workplace_id = ?', $value );
		return $query;
		
	}
	
	public function addPsClassIdColumnQuery($query, $field, $value) {

		$query->andWhere ( 'sc.myclass_id = ?', $value );
		return $query;

	}

	public function addTrackedAtColumnQuery($query, $field, $value) {

		return $query;

	}

	public function addAttendanceTypeColumnQuery($query, $field, $value) {

		return $query;

	}

	public function doBuildQuery(array $values) {

		$query = parent::doBuildQuery ( $values );

		$a = $query->getRootAlias ();

		$query->addSelect ( 'lt.id AS id, ' . 'lt.login_at AS login_at, ' . 'lt.log_value AS log_value,lt.log_code AS log_code, ' . 'lt.login_relative_id AS login_relative_id, ' . 'lt.login_member_id AS login_member_id, ' . 'lt.logout_relative_id AS logout_relative_id, ' . 'lt.logout_member_id AS logout_member_id, ' . 'lt.logout_at AS logout_at, lt.created_at AS created_at, lt.updated_at AS updated_at,' . 'lt.note AS note' );

		$query->addSelect ( 'CONCAT(u1.first_name, " ", u1.last_name) as created_by' );
		$query->addSelect ( 'u.id AS u_id, CONCAT(u.first_name, " ", u.last_name) as updated_by' );

		$tracked_at = ($values ['tracked_at'] != '') ? $values ['tracked_at'] : date ( "Y-m-d" );

		$query->leftJoin ( 's.PsLogtimes lt With DATE_FORMAT(lt.login_at,"%Y%m%d") = ?', date ( 'Ymd', strtotime ( $tracked_at ) ) );

		$query->leftJoin ( 'lt.UserCreated u1 With u1.user_type = ?', PreSchool::USER_TYPE_TEACHER );
		$query->leftJoin ( 'lt.UserCreated u With u.user_type = ?', PreSchool::USER_TYPE_TEACHER );

		$query->andWhere ( ' DATE_FORMAT(sc.start_at,"%Y%m%d") <= ?', date ( 'Ymd', strtotime ( $tracked_at ) ) );
		
		$query->andWhere ( 'sc.stop_at IS NULL OR  DATE_FORMAT(sc.stop_at,"%Y%m%d") >= ?', date ( 'Ymd', strtotime ( $tracked_at ) ) );
		
		
		$query->andWhere( ' DATE_FORMAT(sc.start_at,"%Y%m%d") <= ?', date ( 'Ymd', strtotime ( $tracked_at ) ) );

		$log_value = $values ['attendance_type'];

		if ($log_value == 1) {
			$query->addWhere ( 'lt.log_value = ?', $log_value );
		}

		return $query;

	}

	protected function showUseFields() {

		$this->useFields ( array (
				'school_year_id',
				'ps_customer_id',
				'ps_workplace_id',
				'ps_class_id',
				'tracked_at'
		) );

	}

}
