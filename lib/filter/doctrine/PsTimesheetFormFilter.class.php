<?php

/**
 * PsTimesheet filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsTimesheetFormFilter extends BasePsTimesheetFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_HR_TIMESHEET_FILTER_SCHOOL' );

		if (myUser::credentialPsCustomers ( 'PS_HR_TIMESHEET_FILTER_SCHOOL' )) {

			$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'PsCustomer',
					'column' => 'id' ) );

			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'style' => 'min-width:200px;width:100%;',
					'class' => 'select2',
					'required' => false ) );
		}

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
		$time_at = $this->getDefault ( 'time_at' );

		if ($time_at == '') {
			$time_at = date ( 'Y-m-d' );
			$this->setDefault ( 'time_at', $time_at );
		}
		if ($ps_customer_id <= 0) {
			$ps_customer_id = myUser::getPscustomerID ();
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
		}

		/* BEGIN ps_workplace_id: Lay danh sach co so theo truong hoc */

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

		// lay nam hoc hien tai
		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault (),
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

		// Neu co quyen chon truong hoc de xu ly thi lay lop hoc binh thuong theo truong

		$param_class = array (
				'ps_customer_id' => $ps_customer_id,
				'ps_school_year_id' => $school_year_id,
				'ps_workplace_id' => $ps_workplace_id );

		$this->widgetSchema ['ps_department_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsDepartment',
				'query' => Doctrine::getTable ( 'PsDepartment' )->setDepartmentByWorkplaceId ( $ps_workplace_id, $ps_customer_id ),
				'add_empty' => _ ( '-Select department-' ) ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select department-' ) ) );

		$this->validatorSchema ['ps_department_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsDepartment',
				'column' => 'id' ) );

		// Ngay diem danh
		$this->widgetSchema ['time_at'] = new psWidgetFormFilterInputDate ();

		$this->validatorSchema ['time_at'] = new sfValidatorDate ( array (
				'required' => false,
				'max' => date ( 'Y-m-d' ) ), array (
				'invalid' => 'Invalid tracked at',
				'max' => 'Date must be no larger than %max%' ) );

		$this->widgetSchema ['time_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => 'Time at',
				'required' => false ) );

		$this->widgetSchema ['date_time'] = new sfWidgetFormInputHidden ();
		$this->validatorSchema ['date_time'] = new sfValidatorDate ( array (
				'required' => true ) );

		$this->setDefault ( 'time_at', $time_at );
		$this->setDefault ( 'date_time', $time_at );

		// $this->showUseFields ();
	}

	public function addSchoolYearIdColumnQuery($query, $field, $value) {

		return $query;
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$query->andWhere ( 'mb.ps_customer_id =?', $value );

		return $query;
	}

	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		//$query->andWhere ( 'dp.ps_workplace_id =?', $value );

		return $query;
	}

	public function addPsDepartmentIdColumnQuery($query, $field, $value) {

// 		$query->andWhere ( 'dpm.ps_department_id =?', $value );

		return $query;
	}

	public function addTimeAtColumnQuery($query, $field, $value) {

		return $query;
	}

	protected function showUseFields() {

		$this->useFields ( array (
				'school_year_id',
				'ps_customer_id',
				'ps_workplace_id',
				'ps_department_id' ) );
	}

	public function doBuildQuery(array $values) {

		if ($values ['time_at'] != '') {
			$date_time = $values ['time_at'];
		} else {
			$date_time = date ( 'Y-m-d' );
		}
		// echo $date_time; die();
		$query = parent::doBuildQuery ( $values );

		$a = $query->getRootAlias ();

		
		$query->leftJoin ( 'mb.PsMemberAbsents mba With DATE_FORMAT(mba.absent_at, "%Y%m%d") = ?', date ( 'Ymd', strtotime ( $date_time ) ) );
		//$query->leftJoin ( 'mb.PsMemberAbsents mba' );
		$query->addWhere ( 'mba.id IS NULL' );
		
		if($values ['ps_workplace_id'] > 0){
			$query->andWhere ( 'dp.ps_workplace_id =? OR mb.ps_workplace_id =?', array($values ['ps_workplace_id'],$values ['ps_workplace_id']) );
		}
		
		if($values ['ps_department_id'] > 0){
			$query->andWhere ( 'dpm.ps_department_id =?', $values ['ps_department_id'] );
			$query->andWhere ( 'DATE_FORMAT(dpm.start_at,"%Y%m%d") <= ? AND dpm.stop_at IS NULL OR DATE_FORMAT(dpm.stop_at,"%Y%m%d") >= ?', array(date ( 'Ymd', strtotime ( $date_time ) ),date ( 'Ymd', strtotime ( $date_time ) )) );
		}
		
		return $query;
	}
}
