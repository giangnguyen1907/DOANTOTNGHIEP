<?php

/**
 * PsOffSchool filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsOffSchoolFormFilter extends BasePsOffSchoolFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_STUDENT_OFF_SCHOOL_FILTER_SCHOOL', true );

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

		$school_year_id = $this->getDefault ( 'school_year_id' );

		if ($school_year_id == '') {
			$school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}

		$this->setDefault ( 'school_year_id', $school_year_id );

		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears () ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ) ) );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id' );
		if ($ps_workplace_id == '') {
			$member_id = myUser::getUser ()->getMemberId ();
			$ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );

		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_school_year_id' => $school_year_id,
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
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select class-' ) ) );

			$this->validatorSchema ['ps_class_id'] = new sfValidatorPass ( array (
					'required' => false ) );
		}

		$this->widgetSchema ['is_activated'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select state-' ) ) + PreSchool::getOffSchoolStatus () ), array (
				'class' => 'form-control',
				'style' => "min-width:130px;",
				'data-placeholder' => _ ( '-Select state-' ) ) );

		$this->widgetSchema ['start_at'] = new psWidgetFormFilterInputDate ();

		$this->validatorSchema ['start_at'] = new sfValidatorDate ( array (
				'required' => false ), array (
				'invalid' => 'Invalid tracked at',
				'max' => 'Date must be no larger than %max%' ) );

		$this->widgetSchema ['start_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => 'Start at',
				'required' => false ) );

		$this->widgetSchema ['stop_at'] = new psWidgetFormFilterInputDate ();

		$this->validatorSchema ['stop_at'] = new sfValidatorDate ( array (
				'required' => false ), array (
				'invalid' => 'Invalid tracked at',
				'max' => 'Date must be no larger than %max%' ) );

		$this->widgetSchema ['stop_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => 'Stop at',
				'required' => false ) );

		// $this->showUseFields();
	}

	protected function showUseFields() {

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_OFF_SCHOOL_FILTER_SCHOOL' )) {

			$this->useFields ( array (
					'school_year_id',
					'ps_customer_id',
					'ps_workplace_id',
					'ps_class_id',
					'is_activated' // trang thai
			) );
		} else {

			$this->useFields ( array (
					'school_year_id',
					'ps_workplace_id',
					'ps_class_id' ) );
		}
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		// $query->where('s.ps_customer_id = ? ', $value);
		return $query;
	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsClassIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addIsActivatedColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addStartAtColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addStopAtColumnQuery($query, $field, $value) {

		return $query;
	}

	public function doBuildQuery(array $values) {

		$query = parent::doBuildQuery ( $values );

		$a = $query->getRootAlias ();

		$query->leftJoin ( 'mc.PsClassRooms cr' );

		$query->leftJoin ( 'cr.PsWorkPlaces wp' );

		if (isset($values ['ps_class_id']) && $values ['ps_class_id'] > 0) {

			$query->andWhere ( 'mc.id = ?', $values ['ps_class_id'] );
		}

		if (isset($values ['ps_workplace_id']) && $values ['ps_workplace_id'] > 0) {

			$query->andWhere ( 'wp.id = ?', $values ['ps_workplace_id'] );
		}

		if (isset($values ['ps_customer_id']) && $values ['ps_customer_id'] > 0) {

			$query->andWhere ( 's.ps_customer_id = ?', $values ['ps_customer_id'] );
		}

		if (isset ( $values ['is_activated'] )) {

			$query->andWhere ( $a . '.is_activated = ?', $values ['is_activated'] );
		}

		if (isset($values ['start_at']) && $values ['start_at'] != '') {
			$query->andWhere ( ' DATE_FORMAT(' . $a . '.from_date,"%Y%m%d") <= ? AND DATE_FORMAT(' . $a . '.to_date,"%Y%m%d") >= ?', array (
					date ( 'Ymd', strtotime ( $values ['start_at'] ) ),
					date ( 'Ymd', strtotime ( $values ['start_at'] ) ) ) );
		}
		if (isset($values ['stop_at']) && $values ['stop_at'] != '') {
			$query->andWhere ( ' DATE_FORMAT(' . $a . '.from_date,"%Y%m%d") <= ? AND DATE_FORMAT(' . $a . '.to_date,"%Y%m%d") >= ?', array (
					date ( 'Ymd', strtotime ( $values ['stop_at'] ) ),
					date ( 'Ymd', strtotime ( $values ['stop_at'] ) ) ) );
		}

		return $query;
	}
}
