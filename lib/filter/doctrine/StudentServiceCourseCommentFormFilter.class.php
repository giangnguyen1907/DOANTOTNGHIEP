<?php

/**
 * StudentServiceCourseCommentFormFilter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class StudentServiceCourseCommentFormFilter extends BaseStudentServiceCourseCommentFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_STUDENT_SERVICE_COURSE_COMMENT_FILTER_SCHOOL' );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		if ($ps_customer_id > 0) {
			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_service_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Service',
					'query' => Doctrine::getTable ( 'Service' )->setServicesTypeScheduleByPsCustomer ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => '-Select subjects-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select subjects-' ) ) );

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkplaces',
					'query' => Doctrine::getTable ( 'PsWorkplaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => 'min-width:200px;',
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select workplace-' ) ) );
		} else {

			$this->widgetSchema ['ps_service_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select subjects-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select subjects-' ) ) );

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => 'min-width:200px',
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select workplace-' ) ) );
		}

		$ps_service_id = $this->getDefault ( 'ps_service_id' );

		if ($ps_service_id > 0) {

			$this->widgetSchema ['ps_service_course_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsServiceCourses',
					'query' => Doctrine::getTable ( 'PsServiceCourses' )->setPsServiceCoursesByPsService ( 'id, title', $ps_service_id, PreSchool::ACTIVE ),
					'add_empty' => '-Select courses-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select courses-' ) ) );

			$this->validatorSchema ['ps_service_course_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => true,
					'model' => 'PsServiceCourses',
					'column' => 'id' ) );
		} else {

			$this->widgetSchema ['ps_service_course_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select courses-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select courses-' ) ) );

			$this->validatorSchema ['ps_service_course_id'] = new sfValidatorPass ( array (
					'required' => true ) );
		}

		$this->widgetSchema ['tracked_at'] = new psWidgetFormFilterInputDate ();

		$this->widgetSchema ['tracked_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'title' => 'Tracked at',
				'required' => true ) );
		// 'max' => date('d-m-Y')
		$tracked_at = $this->getDefault ( 'tracked_at' );

		$ps_service_course_id = $this->getDefault ( 'ps_service_course_id' );

		if ($ps_service_course_id > 0 && ($tracked_at)) {

			$this->widgetSchema ['ps_service_course_schedule_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsServiceCourseSchedules',
					'query' => Doctrine::getTable ( 'PsServiceCourseSchedules' )->setPsServiceCourseSchedulesByPsServiceCourse ( $ps_service_course_id, $tracked_at ),
					'add_empty' => '-Choose a schedule-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Choose a schedule-' ) ) );
		} else {

			$this->widgetSchema ['ps_service_course_schedule_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-No schedule-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-No schedule-' ) ) );
		}

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsWorkplaces',
				'column' => 'id' ) );

		$this->validatorSchema ['ps_service_course_schedule_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsServiceCourseSchedules',
				'column' => 'id' ) );

		$this->validatorSchema ['ps_service_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'Service',
				'column' => 'id' ) );

		$this->widgetSchema ['ps_service_id']->setAttributes ( array (
				'style' => 'min-width:200px;',
				'class' => 'select2',
				'required' => true ) );

		$this->validatorSchema ['tracked_at'] = new sfValidatorDate ( array (
				'required' => true ) );

		// $this->showUseFields ();
	}

	public function addPsCustomerIdColumnQuery($query, $field, $value) {

		$query->addWhere ( 's.ps_customer_id = ?', $value );

		return $query;
	}

	// Co so
	public function addPsWorkplaceIdColumnQuery($query, $field, $value) {

		$query->addWhere ( 'wp.id = ?', $value );

		return $query;
	}

	// Mon hoc
	public function addPsServiceIdColumnQuery($query, $field, $value) {

		$query->addWhere ( 'ss.service_id = ?', $value );

		return $query;
	}

	// Khoa hoc
	public function addPsServiceCourseIdColumnQuery($query, $field, $value) {

		$query->addWhere ( 'sco.id = ?', $value );

		return $query;
	}

	// Ngay hoc
	public function addTrackedAtColumnQuery($query, $field, $value) {

		// $query->leftJoin('s.PsLogtimes lt With DATE_FORMAT(lt.login_at,"%Y%m%d") = ?', date('Ymd', strtotime($value)));
		$query->addWhere ( 'DATE_FORMAT(log.login_at,"%Y%m%d") = ?', date ( 'Ymd', strtotime ( $value ) ) );

		return $query;
	}

	// Lich hoc cu the
	public function addPsServiceCourseScheduleIdColumnQuery($query, $field, $value) {

		// $tracked_at = date('Ymd', strtotime($this->getDefault('tracked_at')));

		// $query->innerJoin ( 'sco.PsServiceCourseSchedules scs With scs.id = ? AND DATE_FORMAT(scs.date_at,"%Y%m%d") = ?', array($value,$tracked_at));
		$query->addWhere ( 'sch.id = ?', $value );

		return $query;
	}

	// Add virtual class_id for filter
	/*
	 * protected function showUseFields() {
	 * $this->useFields ( array (
	 * 'ps_customer_id',
	 * 'ps_workplace_id',
	 * 'ps_service_id',
	 * 'ps_service_course_id',
	 * 'tracked_at',
	 * 'ps_service_course_schedule_id'
	 * ) );
	 * }
	 */
}
