<?php

/**
 * PsTimesheetSummarys filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsTimesheetSummarysFormFilter extends BasePsTimesheetSummarysFormFilter {

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

		$year = date ( "m-Y" );
		// echo $year; die();
		// Lay nam hoc hien tai

		$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );

		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );

		$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );

		$this->widgetSchema ['year_month'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select month-' ) ) + PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) );
		$year_month = $this->getDefault ( 'year_month' );
		// Lay thang hien tai

		$number_day = PsDateTime::psNumberDaysOfMonth ( $year );

		$this->setDefault ( 'year_month', $year );

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

		$ps_department_id = $this->getDefault ( 'ps_department_id' );

		if ($ps_department_id > 0) {
			// Filters member

			$this->widgetSchema ['member_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsMember',
					'query' => Doctrine::getTable ( 'PsMemberDepartments' )->setMemberDepartments ( $ps_department_id ),
					'add_empty' => _ ( '-Select member-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select member-' ) ) );

			$this->validatorSchema ['member_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'PsMember',
					'column' => 'id' ) );
		} else {

			$this->widgetSchema ['member_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select member-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select member-' ) ) );
		}

		$this->showUseFields ();
	}

	protected function showUseFields() {

		$this->useFields ( array (
				'school_year_id',
				'year_month',
				'ps_customer_id',
				'ps_workplace_id',
				'ps_department_id',
				'member_id' ) );
	}
}
