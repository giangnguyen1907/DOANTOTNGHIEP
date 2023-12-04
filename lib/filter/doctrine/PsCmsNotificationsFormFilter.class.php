<?php

/**
 * PsCmsNotifications filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsCmsNotificationsFormFilter2 extends BasePsCmsNotificationsFormFilter {

	public function configure() {

		$is_status = $this->getDefault ( 'is_status' );

		if ($is_status == PreSchool::PS_CMS_NOTIFICATIONS_SENT) {

			$school_year_id = $this->getDefault ( 'school_year_id' );

			if ($school_year_id == '') {
				$school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
					->fetchOne ()
					->getId ();
			}

			$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsSchoolYear',
					'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
					'add_empty' => false ), array (
					'class' => 'form-control',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select   year-' ) ) );

			$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => true,
					'model' => 'PsSchoolYear',
					'column' => 'id' ) );

			$this->widgetSchema ['school_year_id']->setOption ( 'add_empty', false );

			$this->setDefault ( 'school_year_id', $school_year_id );

			$this->addPsCustomerFormFilter ( 'PS_CMS_NOTIFICATIONS_FILTER_SCHOOL' );

			$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
			$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );

			if ($ps_customer_id > 0) {

				if (myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_FILTER_WORKPLACES' )) { // Quyền quản lý thư của các cơ sở trong trường

					// ps_workplace_id filter by ps_customer_id
					$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
							'model' => 'PsWorkPlaces',
							'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
							'add_empty' => '-Select workplace-' ), array (
							'class' => 'select2',
							'required' => false,
							'style' => "min-width:200px;",
							'data-placeholder' => _ ( '-Select workplace-' ) ) );

					$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
							'required' => false,
							'model' => 'PsWorkPlaces',
							'column' => 'id' ) );
				} else { // Không được chọn cơ sở

					if ($ps_workplace_id <= 0) {
						$member_id = myUser::getUser ()->getMemberId ();
						$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
					}

					$query = Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id )
						->addWhere ( 'id = ?', $ps_workplace_id );

					// ps_workplace_id filter by ps_customer_id
					$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
							'model' => 'PsWorkPlaces',
							'query' => $query,
							'add_empty' => false ), array (
							'required' => true,
							'class' => 'form-control' ) );

					$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
							'required' => true,
							'model' => 'PsWorkPlaces',
							'column' => 'id' ) );
				}
			} else {

				$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
						'choices' => array (
								'' => _ ( '-Select workplace-' ) ) ), array (
						'class' => 'select2',
						'required' => false,
						'style' => "min-width:200px;",
						'data-placeholder' => _ ( '-Select workplace-' ) ) );
			}

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'PsWorkPlaces',
					'column' => 'id' ) );

			// Thanh da them - Start
			$param_class = array (
					'ps_customer_id' => $ps_customer_id,
					'ps_workplace_id' => $ps_workplace_id,
					'ps_school_year_id' => $school_year_id,
					'is_activated' => PreSchool::ACTIVE );

			if ($ps_workplace_id > 0) {

				if (myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ) || ! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_TEACHER' )) {
					$sqlMyClass = Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class );
				} else {
					$sqlMyClass = Doctrine::getTable ( 'MyClass' )->getClassIdByUserIdWorkplace ( myUser::getUserId (), $ps_workplace_id );
				}

				$this->widgetSchema ['ps_class_id'] = new sfWidgetFormDoctrineChoice ( array (
						'model' => 'MyClass',
						'query' => $sqlMyClass,
						'add_empty' => _ ( '-Select class-' ) ), array (
						'class' => 'select2',
						'style' => "min-width:200px;width:100%;",
						'required' => false,
						'data-placeholder' => _ ( '-Select class-' ) ) );

				$this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice ( array (
						'model' => 'MyClass',
						'required' => false ) );
			} else {

				$this->widgetSchema ['ps_class_id'] = new sfWidgetFormChoice ( array (
						'choices' => array (
								'' => _ ( '-Select class-' ) ) ), array (
						'class' => 'select2',
						'style' => "min-width:200px;",
						'required' => false,
						'data-placeholder' => _ ( '-Select class-' ) ) );

				$this->validatorSchema ['ps_class_id'] = new sfValidatorPass ();
			}
			// Thanh da them - End
		}

		$this->widgetSchema ['date_at_from'] = new psWidgetFormFilterInputDate ();

		$this->widgetSchema ['date_at_from']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'data-original-title' => sfContext::getInstance ()->getI18n ()
					->__ ( 'From date' ) ) );

		$this->widgetSchema ['date_at_from']->addOption ( 'tooltip', sfContext::getInstance ()->getI18n ()
			->__ ( 'From date' ) );

		$this->validatorSchema ['date_at_from'] = new sfValidatorDate ( array (
				'required' => false ) );

		$this->widgetSchema ['date_at_to'] = new psWidgetFormFilterInputDate ();

		$this->widgetSchema ['date_at_to']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'data-original-title' => sfContext::getInstance ()->getI18n ()
					->__ ( 'To date' ) ) );

		$this->widgetSchema ['date_at_to']->addOption ( 'tooltip', sfContext::getInstance ()->getI18n ()
			->__ ( 'To date' ) );

		$this->validatorSchema ['date_at_to'] = new sfValidatorDate ( array (
				'required' => false ) );

		$this->widgetSchema ['is_status'] = new sfWidgetFormInputHidden ();

		$this->validatorSchema ['is_status'] = new sfValidatorString ( array (
				'required' => true ) );

		// unset($this['school_year_id']);

		$this->showUseFields ( $is_status );
	}

	public function addDateAtFromColumnQuery($query, $field, $value) {

		$rootAlias = $query->getRootAlias ();

		return $query->addWhere ( $rootAlias . '.date_at >= ?', $value );
	}

	public function addDateAtToColumnQuery($query, $field, $value) {

		$rootAlias = $query->getRootAlias ();

		return $query->addWhere ( $rootAlias . '.date_at <= ?', $value );
	}

	/*
	 * public function addTypeColumnQuery($query, $field, $value) { $rootAlias = $query->getRootAlias ();
	 * $query->addWhere ( $rootAlias . '.is_status = ?', $value );
	 * return $query; }
	 */
	protected function showUseFields($is_status = '') {

		if ($is_status == PreSchool::PS_CMS_NOTIFICATIONS_RECEIVED) {
			$this->useFields ( array (
					'title',
					'description',
					'date_at_from',
					'date_at_to',
					'is_status' ) );
		} else {
			$this->useFields ( array (
					'school_year_id',
					'ps_customer_id',
					'ps_workplace_id',
					'ps_class_id', // Thanh da them
					'title',
					'description',
					'date_at_from',
					'date_at_to',
					'is_status' ) );
		}
	}
}
class PsCmsNotificationsFormFilter extends BasePsCmsNotificationsFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_CMS_NOTIFICATIONS_FILTER_SCHOOL' );

		if (! myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_FILTER_SCHOOL' )) {

			$ps_customer_id = myUser::getPscustomerID ();

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorInteger ( array (
					'required' => true ) );
		} else {

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => true,
					'data-placeholder' => _ ( '-All school-' ) ) );

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true ) );
		}

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		$school_year_id = $this->getDefault ( 'school_year_id' );

		if ($school_year_id == '') {
			$school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}

		$this->setDefault ( 'school_year_id', $school_year_id );

		$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );

		$this->setDefault ( 'ps_customer_id', $ps_customer_id );

		if ($ps_customer_id > 0) {
			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'required' => false,
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'required' => false,
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
				'add_empty' => false ), // _ ( '-Select school year-' )
		array (
				'class' => 'form-control',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ) ) );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$this->widgetSchema ['school_year_id']->setOption ( 'add_empty', false );

		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_school_year_id' => $school_year_id,
				'is_activated' => PreSchool::ACTIVE );

		// Thanh da them - Start
		if ($ps_workplace_id > 0) {

			if (myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ) || ! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_TEACHER' )) {
				$sqlMyClass = Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class );
			} else {
				$sqlMyClass = Doctrine::getTable ( 'MyClass' )->getClassIdByUserIdWorkplace ( myUser::getUserId (), $ps_workplace_id );
			}

			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => $sqlMyClass,
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => false ) );
		} else {

			$this->widgetSchema ['ps_class_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorPass ();
		}
		// Thanh da them - End

		$this->widgetSchema ['type'] = new sfWidgetFormInputHidden ();

		$this->validatorSchema ['type'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->widgetSchema ['date_at_from'] = new psWidgetFormFilterInputDate ();

		$this->widgetSchema ['date_at_from']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'data-original-title' => sfContext::getInstance ()->getI18n ()
					->__ ( 'From date' ) ) );

		$this->widgetSchema ['date_at_from']->addOption ( 'tooltip', sfContext::getInstance ()->getI18n ()
			->__ ( 'From date' ) );

		$this->validatorSchema ['date_at_from'] = new sfValidatorDate ( array (
				'required' => false ) );

		$this->widgetSchema ['date_at_to'] = new psWidgetFormFilterInputDate ();

		$this->widgetSchema ['date_at_to']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'data-original-title' => sfContext::getInstance ()->getI18n ()
					->__ ( 'To date' ) ) );

		$this->widgetSchema ['date_at_to']->addOption ( 'tooltip', sfContext::getInstance ()->getI18n ()
			->__ ( 'To date' ) );

		$this->validatorSchema ['date_at_to'] = new sfValidatorDate ( array (
				'required' => false ) );
	}

	public function addPsCustomerIdColumnQuery(Doctrine_Query $query, $field, $value) {

		return $query;
		// return $query->addWhere($rootAlias . '.ps_customer_id = ?', $value);
	}

	public function addPsClassIdColumnQuery(Doctrine_Query $query, $field, $value) {

		return $query;
	}

	public function addDateAtFromColumnQuery(Doctrine_Query $query, $field, $value) {

		$rootAlias = $query->getRootAlias ();

		return $query->addWhere ( $rootAlias . '.date_at >= ?', $value );
	}

	public function addDateAtToColumnQuery(Doctrine_Query $query, $field, $value) {

		$rootAlias = $query->getRootAlias ();

		return $query->addWhere ( $rootAlias . '.date_at <= ?', $value );
	}

	public function addTypeColumnQuery($query, $field, $value) {

		$rootAlias = $query->getRootAlias ();
		if ($value == 'sent') { // Gui thong bao di
			$query->addWhere ( $rootAlias . '.is_status = ?', $value );
			$role = myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_ALL' );
			if (! myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_FILTER_SCHOOL' )) { // nếu không có quyền chọn trường
				if ($role) {
					$query->addWhere ( $rootAlias . '.ps_customer_id = ?', myUser::getPscustomerID () );
				} else {
					$query->addWhere ( $rootAlias . '.user_created_id = ?', myUser::getUserId () );
				}
			}
			// $query->addWhere('RN.user_id = ?', myUser::getUserId());
			return $query;
		} elseif ($value == 'received') { // Nhận thông báo
			$query->addWhere ( 'RN.is_delete = ?', 0 );
			$query->addWhere ( $rootAlias . '.user_created_id != ?', myUser::getUserId () ); // ko lay thu minh gui cho minh
			$query->addWhere ( 'RN.user_id = ?', myUser::getUserId () );
			return $query;
		} elseif ($value == 'trash') { // Thư trong thùng rác

			$role = myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_ALL' ) && myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_DELETE' );
			if ($role) {
				if (! myUser::credentialPsCustomers ( 'PS_CMS_NOTIFICATIONS_FILTER_SCHOOL' )) {
					$query->addWhere ( $rootAlias . '.ps_customer_id = ?', myUser::getPscustomerID () );
				}
				$query->addWhere ( $rootAlias . '.is_status = ?', $value );
			} else {
				$query->addWhere ( 'RN.user_id = ?', myUser::getUserId () );
				$query->addWhere ( 'RN.is_delete = ?', 1 );
			}
			return $query;
		} else {
			$query->addWhere ( $rootAlias . '.is_status = ?', $value );
			$query->addWhere ( $rootAlias . '.user_created_id = ?', myUser::getUserId () );
			return $query;
		}
	}

	public function doBuildQuery(array $values) {

		$query = parent::doBuildQuery ( $values );

		$a = $query->getRootAlias ();

		$query->leftJoin ( $a . '.PsCmsNotificationsClass ntc' );

		if (isset($values ['ps_class_id']) && $values ['ps_class_id'] > 0) {

			$query->andWhere ( 'ntc.ps_class_id = ?', $values ['ps_class_id'] );
		}

		if (isset($values ['ps_customer_id']) && $values ['ps_customer_id'] > 0) {
			$query->andWhere ( $a . '.ps_customer_id = ?', $values ['ps_customer_id'] );
		}

		return $query;
	}
}
