<?php
/**
 * FeatureBranchTimes filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FeatureBranchTimesFormFilter extends BaseFeatureBranchTimesFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' );

		if (! myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' )) {

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

		$ps_class_id = $this->getDefault ( 'ps_class_id' );

		if ($school_year_id == '') {
			$school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}

		if ($ps_customer_id == '') {
			$ps_customer_id = myUser::getPscustomerID ();
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
		}

		$this->setDefault ( 'school_year_id', $school_year_id );

		$this->setDefault ( 'ps_class_id', $ps_class_id );

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

		$school_year_id = $this->getDefault ( 'school_year_id' );

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
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => false ) );
		} else {
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorPass ();
		}

		$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );

		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_school_year_id' => $school_year_id,
				'is_activated' => PreSchool::ACTIVE );

		if ($ps_workplace_id > 0) {
			if (myUser::credentialPsCustomers ( 'PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL' ) || ! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_TEACHER' )) {
				$sqlMyClass = Doctrine::getTable ( 'MyClass' )->setClassByParams ( $param_class );
			} else {
				$sqlMyClass = Doctrine::getTable ( 'MyClass' )->getClassIdByUserIdWorkplace ( myUser::getUserId (), $ps_workplace_id,$school_year_id );
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

		$ps_year = date ( 'Y' );
		$weeks = PsDateTime::getWeeksOfYear ( $ps_year );

		$this->widgetSchema ['ps_week'] = new sfWidgetFormChoice ( array (
				'choices' => PsDateTime::getOptionsWeeks ( $weeks ) ), array (
				'class' => 'select2',
				'style' => "min-width:250px;width:100%;",
				'data-placeholder' => _ ( '-Select week-' ) ) );

		$this->validatorSchema ['ps_week'] = new sfValidatorPass ( array (
				'required' => true ) );

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

		$this->widgetSchema ['date_at_from'] = new psWidgetFormFilterInputDate ();

		$this->validatorSchema ['date_at_from'] = new sfValidatorDate ( array (
				'required' => false,
				'max' => date ( 'Y-m-d' ) ), array (
				'invalid' => 'Invalid tracked at',
				'max' => 'Date must be no larger than %max%' ) );

		$this->widgetSchema ['date_at_from']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => sfContext::getInstance ()->getI18n ()
					->__ ( 'From date' ),
				'data-original-title' => sfContext::getInstance ()->getI18n ()
					->__ ( 'From date' ),
				'required' => false ) );

		$this->widgetSchema ['date_at_to'] = new psWidgetFormFilterInputDate ();

		$this->validatorSchema ['date_at_to'] = new sfValidatorDate ( array (
				'required' => false ), array (
				'invalid' => 'Invalid tracked at',
				'max' => 'Date must be no larger than %max%' ) );

		$this->widgetSchema ['date_at_to']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => sfContext::getInstance ()->getI18n ()
					->__ ( 'To date' ),
				'data-original-title' => sfContext::getInstance ()->getI18n ()
					->__ ( 'To date' ),
				'required' => false ) );

		$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['keywords']->setAttributes ( array (
				'class' => 'form-control',
				'placeholder' => sfContext::getInstance ()->getI18n ()
					->__ ( 'Keywords' ) ) );

		$this->validatorSchema ['keywords'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->setDefault ( 'ps_class_id', $ps_class_id );

		/*
		 * $this->widgetSchema['school_year_id'] = new sfWidgetFormDoctrineChoice(array(
		 * 'model' => 'PsSchoolYear',
		 * 'query' => Doctrine::getTable('PsSchoolYear')->setSqlPsSchoolYears(),
		 * 'add_empty' => '-Select school year-'
		 * ), array(
		 * 'class' => 'select2',
		 * 'style' => "min-width:150px;",
		 * 'data-placeholder' => _('-Select school year-')
		 * ));
		 * $this->validatorSchema['school_year_id'] = new sfValidatorDoctrineChoice(array(
		 * 'required' => false,
		 * 'model' => 'PsSchoolYear',
		 * 'column' => 'id'
		 * ));
		 * $school_year_id = $this->getDefault('school_year_id');
		 * // PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL
		 * $this->addPsCustomerFormFilter('PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL');
		 * $ps_customer_id = $this->getDefault('ps_customer_id');
		 * if ($ps_customer_id > 0) {
		 * // ps_workplace_id filter by ps_customer_id
		 * $this->widgetSchema['ps_workplace_id'] = new sfWidgetFormDoctrineChoice(array(
		 * 'model' => 'PsWorkPlaces',
		 * 'query' => Doctrine::getTable('PsWorkPlaces')->setSQLByCustomerId('id, title', $ps_customer_id),
		 * 'add_empty' => '-Select workplace-'
		 * ));
		 * $this->validatorSchema['ps_workplace_id'] = new sfValidatorDoctrineChoice(array(
		 * 'required' => false,
		 * 'model' => 'PsWorkPlaces',
		 * 'column' => 'id'
		 * ));
		 * // feature_id filter by ps_customer_id
		 * $this->widgetSchema['feature_id'] = new sfWidgetFormDoctrineChoice(array(
		 * 'model' => 'Feature',
		 * 'query' => Doctrine::getTable('Feature')->setSQLByCustomerId('id, name', $ps_customer_id),
		 * 'add_empty' => '-Select feature-'
		 * ));
		 * $this->validatorSchema['feature_id'] = new sfValidatorDoctrineChoice(array(
		 * 'required' => false,
		 * 'model' => 'Feature',
		 * 'column' => 'id'
		 * ));
		 * $feature_branch_times_filters['ps_customer_id'] = $ps_customer_id;
		 * $feature_branch_times_filters['school_year_id'] = $this->getDefault('school_year_id');
		 * $feature_branch_times_filters['ps_workplace_id'] = $this->getDefault('ps_workplace_id');
		 * // feature_id filter by ps_customer_id
		 * $this->widgetSchema['ps_feature_branch_id'] = new sfWidgetFormDoctrineChoice(array(
		 * 'model' => 'FeatureBranch',
		 * 'query' => Doctrine::getTable('FeatureBranch')->setSqlFeatureBranchByFilters($feature_branch_times_filters),
		 * 'add_empty' => '-Select feature branch-'
		 * ));
		 * } else {
		 * $this->widgetSchema['ps_workplace_id'] = new sfWidgetFormChoice(array(
		 * 'choices' => array(
		 * '' => _('-Select workplace-')
		 * )
		 * ), array(
		 * 'class' => 'select2',
		 * 'data-placeholder' => _('-Select workplace-')
		 * ));
		 * $this->validatorSchema['ps_workplace_id'] = new sfValidatorInteger(array(
		 * 'required' => false
		 * ));
		 * $this->widgetSchema['feature_id'] = new sfWidgetFormChoice(array(
		 * 'choices' => array(
		 * '' => _('-Select feature-')
		 * )
		 * ), array(
		 * 'class' => 'select2',
		 * 'data-placeholder' => _('-Select feature-')
		 * ));
		 * $this->validatorSchema['feature_id'] = new sfValidatorInteger(array(
		 * 'required' => false
		 * ));
		 * $this->widgetSchema['ps_feature_branch_id'] = new sfWidgetFormChoice(array(
		 * 'choices' => array(
		 * '' => _('-Select feature branch-')
		 * )
		 * ), array(
		 * 'class' => 'select2',
		 * 'data-placeholder' => _('-Select feature branch-')
		 * ));
		 * $this->validatorSchema['ps_feature_branch_id'] = new sfValidatorInteger(array(
		 * 'required' => false
		 * ));
		 * }
		 * $this->widgetSchema['feature_id']->setAttributes(array(
		 * 'style' => 'min-width:200px;',
		 * 'class' => 'select2'
		 * ));
		 * $this->widgetSchema['ps_workplace_id']->setAttributes(array(
		 * 'style' => 'min-width:200px;',
		 * 'class' => 'select2',
		 * 'data-placeholder' => _('-Select workplace-')
		 * ));
		 * $this->widgetSchema['ps_feature_branch_id']->setAttributes(array(
		 * 'style' => 'min-width:200px;',
		 * 'class' => 'select2'
		 * ));
		 * $this->widgetSchema['ps_obj_group_id'] = new sfWidgetFormDoctrineChoice(array(
		 * 'model' => 'PsObjectGroups',
		 * 'query' => Doctrine::getTable('PsObjectGroups')->setSQL(),
		 * 'add_empty' => _('-Select object group-')
		 * ), array(
		 * 'class' => 'select2',
		 * 'style' => "min-width:200px;",
		 * 'data-placeholder' => _('-Select object group-')
		 * ));
		 * $this->validatorSchema['ps_obj_group_id'] = new sfValidatorDoctrineChoice(array(
		 * 'required' => false,
		 * 'model' => 'PsObjectGroups',
		 * 'column' => 'id'
		 * ));
		 * $this->widgetSchema['date_at_from'] = new psWidgetFormFilterInputDate();
		 * $this->widgetSchema['date_at_from']->setAttributes(array(
		 * 'data-dateformat' => 'dd-mm-yyyy',
		 * 'placeholder' => 'dd-mm-yyyy',
		 * 'data-original-title' => sfContext::getInstance()->getI18n()->__('From date'),
		 * ));
		 * $this->widgetSchema['date_at_from']->addOption('tooltip', sfContext::getInstance()->getI18n()->__('From date'));
		 * $this->validatorSchema['date_at_from'] = new sfValidatorDate(array(
		 * 'required' => false
		 * ));
		 * $this->widgetSchema['date_at_to'] = new psWidgetFormFilterInputDate();
		 * $this->widgetSchema['date_at_to']->setAttributes(array(
		 * 'data-dateformat' => 'dd-mm-yyyy',
		 * 'placeholder' => 'dd-mm-yyyy',
		 * 'data-original-title' => sfContext::getInstance()->getI18n()->__('To date'),
		 * ));
		 * $this->widgetSchema['date_at_to']->addOption('tooltip', sfContext::getInstance()->getI18n()->__('To date'));
		 * $this->validatorSchema['date_at_to'] = new sfValidatorDate(array(
		 * 'required' => false
		 * ));
		 * $this->widgetSchema['keywords'] = new sfWidgetFormInputText();
		 * $this->widgetSchema['keywords']->setAttributes(array(
		 * 'class' => 'form-control',
		 * 'placeholder' => sfContext::getInstance()->getI18n()
		 * ->__('Keywords')
		 * ));
		 * $this->validatorSchema['keywords'] = new sfValidatorString(array(
		 * 'required' => false
		 * ));
		 * $ps_workplace_id = $this->getDefault('ps_workplace_id');
		 * $param_class = array(
		 * 'ps_customer_id' => $ps_customer_id,
		 * 'ps_workplace_id' => $ps_workplace_id,
		 * 'ps_school_year_id' => $school_year_id
		 * );
		 * if ($ps_workplace_id > 0) {
		 * $this->widgetSchema['ps_class_id'] = new sfWidgetFormDoctrineChoice(array(
		 * 'model' => 'MyClass',
		 * 'query' => Doctrine::getTable('MyClass')->setClassByParams($param_class),
		 * 'add_empty' => _('-Select class-')
		 * ), array(
		 * 'class' => 'select2',
		 * 'style' => "min-width:150px;",
		 * 'data-placeholder' => _('-Select class-')
		 * ));
		 * } else {
		 * $this->widgetSchema['ps_class_id'] = new sfWidgetFormChoice(array(
		 * 'choices' => array(
		 * '' => _('-Select class-')
		 * )
		 * ), array(
		 * 'class' => 'select2',
		 * 'style' => "min-width:200px;",
		 * 'data-placeholder' => _('-Select class-')
		 * ));
		 * }
		 * $this->validatorSchema['ps_class_id'] = new sfValidatorDoctrineChoice(array(
		 * 'required' => false,
		 * 'model' => 'MyClass',
		 * 'column' => 'id'
		 * ));
		 */
	}

	public function addDateAtFromColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addDateAtToColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addSchoolYearIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsClassIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addKeywordsColumnQuery($query, $field, $value) {

		return $query;
	}

	public function doBuildQuery(array $values) {

		$query = parent::doBuildQuery ( $values );

		$a = $query->getRootAlias ();

		if ($values ['school_year_id'] > 0) {

			$query->where ( 'fb.school_year_id = ?', $values ['school_year_id'] );
		}
		if ($values ['ps_customer_id'] > 0) {

			$query->andWhere ( 'cus.id = ?', $values ['ps_customer_id'] );
		}

		if ($values ['ps_workplace_id'] > 0) {

			$query->andWhere ( 'fb.ps_workplace_id = 0 OR fb.ps_workplace_id IS NULL OR fb.ps_workplace_id = ?', $values ['ps_workplace_id'] );
		}

		if ($values ['date_at_from'] > 0) {
			try {

				$date_at_from = date ( 'Ymd', strtotime ( $values ['date_at_from'] ) );

				$query->andWhere ( 'DATE_FORMAT(' . $a . '.start_at, "%Y%m%d") >= ? ', $date_at_from );
			} catch ( Exception $e ) {
			}
		}

		if ($values ['date_at_to'] > 0) {
			try {

				$date_at_to = date ( 'Ymd', strtotime ( $values ['date_at_to'] ) );

				$query->andWhere ( 'DATE_FORMAT(' . $a . '.end_at, "%Y%m%d") <= ? ', $date_at_to );
			} catch ( Exception $e ) {
			}
		}

		if ($values ['keywords'] != '') {

			$keywords = PreString::trim ( $values ['keywords'] );

			$keywords = PreString::strReplace ( $keywords );

			if (PreString::length ( $keywords ) > 0) {

				$keywords = '%' . PreString::strLower ( $keywords ) . '%';

				$query->andWhere ( 'LOWER(fb.name) LIKE ? OR LOWER(fb.note) LIKE ?', array (
						$keywords,
						$keywords ) );
			}
		}

		if ($values ['ps_class_id'] > 0) {

			$query->leftJoin ( $a . '.PsFeatureBranchTimeMyClass fbtmc' );

			$query->andWhere ( 'length(' . $a . '.note_class_name) = 0 OR length(' . $a . '.note_class_name) IS NULL OR fbtmc.ps_myclass_id =? ', $values ['ps_class_id'] );
		}
		return $query;
	}
}
