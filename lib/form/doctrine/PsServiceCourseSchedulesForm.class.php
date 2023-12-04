<?php

/**
 * PsServiceCourseSchedules form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsServiceCourseSchedulesForm extends BasePsServiceCourseSchedulesForm {

	public function configure() {

		$ps_workplace_id = null;

		$ps_customer_id = sfContext::getInstance ()->getRequest ()
			->getParameter ( 'ps_customer_id' );

		if ($this->getObject ()
			->isNew ()) { // Add new

			if (! myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_COURSE_SHEDULES_FILTER_SCHOOL' )) {

				$ps_customer_id = myUser::getPscustomerID ();

				$this->setDefault ( 'ps_customer_id', $ps_customer_id );
			}
		} else {

			$ps_customer_id = $this->getObject ()
				->getPsServiceCourses ()
				->getPsService ()
				->getPsCustomerId ();
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );

			if ($ps_workplace_id = $this->getObject ()
				->getPsClassRoomId () > 0)
				$ps_workplace_id = $this->getObject ()
					->getPsClassRooms ()
					->getPsWorkplaceId ();

			$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );

			$start_time_at = $this->getObject ()
				->getStartTimeAt ();
			$end_time_at = $this->getObject ()
				->getStartTimeAt ();

			$this->setDefault ( 'date', array (
					'from' => $start_time_at ) );
			$this->setDefault ( 'date', array (
					'to' => $end_time_at ) );
		}
		if (myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_COURSE_SHEDULES_FILTER_SCHOOL' )) { // Neu co quyen thay doi truong hoc

			if ($this->getObject ()
				->isNew ()) {

				$ps_customer_active = PreSchool::ACTIVE; // Lay nhung truong hoc dang hoat dong

				$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( $ps_customer_active, null ),
						'add_empty' => _ ( '-Select customer-' ) ) );
			} else {

				$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( null, $ps_customer_id ),
						'add_empty' => _ ( '-Select customer-' ) ) );

				if ($ps_workplace_id = $this->getObject ()
					->getPsClassRoomId () > 0)
					$ps_workplace_id = $this->getObject ()
						->getPsClassRooms ()
						->getPsWorkplaceId ();

				$this->setDefault ( 'ps_workplace_id', $ps_workplace_id );
				$ps_customer_id = $this->getObject ()
					->getPsServiceCourses ()
					->getPsService ()
					->getPsCustomerId ();
				$this->setDefault ( 'ps_customer_id', $ps_customer_id );
			}
			$start_time_at = $this->getObject ()
				->getStartTimeAt ();

			$end_time_at = $this->getObject ()
				->getEndTimeAt ();

			$this->setDefault ( 'date', array (
					'from' => $start_time_at,
					'to' => $end_time_at ) );

			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'select2',
					'required' => 'required' ) );
		} else { // Trai lai xet cho nguoi dung quan tri thong thuong

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( null, myUser::getPscustomerID () ),
					'add_empty' => false ) );

			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control' ) );
		}

		if ($ps_customer_id > 0) {
			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_service_course_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsServiceCourses',
					'query' => Doctrine::getTable ( 'PsServiceCourses' )->setPsServiceCoursesByPsCustomer ( 'id, title', $ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => '-Select courses-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select courses-' ) ) );
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select workplace-' ) ) );
		} else {

			$this->widgetSchema ['ps_service_course_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select courses-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select courses-' ) ) );
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select workplace-' ) ) );
		}

		if ($ps_workplace_id > 0) {
			$this->widgetSchema ['ps_class_room_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsClassRooms',
					'query' => Doctrine::getTable ( 'PsClassRooms' )->setSqlParams ( 'id, title', array (
							'ps_workplace_id' => $this->getDefault ( 'ps_workplace_id' ),
							'ps_customer_id' => $ps_customer_id ) ) ) );

			$this->widgetSchema ['ps_class_room_id']->setOption ( 'add_empty', _ ( '-Select class room-' ) );

			$this->widgetSchema ['ps_class_room_id']->setAttributes ( array (
					'style' => 'min-width:200px;',
					'class' => 'select2' ) );
		} else {

			$this->widgetSchema ['ps_class_room_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class room-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select class room-' ) ) );
		}

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->setDefault ( 'is_activated', PreSchool::ACTIVE );

		$this->widgetSchema ['date_at'] = new psWidgetFormInputDate ();
		$this->widgetSchema ['date_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => false ) );
		$this->widgetSchema ['note']->setAttributes ( array (
				'maxlength' => 255 ) );

		$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsCustomer',
				'required' => true ) );

		$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsWorkPlaces',
				'required' => false ) );

		$this->validatorSchema ['ps_class_room_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsClassRooms',
				'required' => false ) );

		$this->validatorSchema ['ps_service_course_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsServiceCourses',
				'required' => true ) );

		$this->validatorSchema ['note'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->validatorSchema ['date_at'] = new sfValidatorDate ( array (
				'required' => true ) );
		$this->validatorSchema ['is_activated'] = new sfValidatorInteger ( array (
				'required' => false ) );

		// $this->widgetSchema['start_time_at'] = new psWidgetFormInputTime();
		// $this->widgetSchema['start_time_at']->setAttributes(array(
		// 'class' => 'startTime',
		// 'data-mode' => "24h",
		// 'required' => false
		// ));
		// $this->validatorSchema['start_time_at'] = new sfValidatorTime(array(
		// 'required' => false
		// ));

		// $this->widgetSchema['end_time_at'] = new psWidgetFormInputTime();
		// $this->widgetSchema['end_time_at']->setAttributes(array(
		// 'class' => 'endTime',
		// 'data-mode' => "24h",
		// 'required' => false
		// ));
		// $this->validatorSchema['end_time_at'] = new sfValidatorTime(array(
		// 'required' => false
		// ));

		$this->widgetSchema ['ps_service_course_id']->setLabel ( 'Courses title' );

		$this->widgetSchema ['date'] = new sfWidgetFormDateRange ( array (

				'from_date' => new psWidgetFormInputTime (),

				'to_date' => new psWidgetFormInputTime (),

				'template' => '<div class="row"><div class="col-md-6">%from_date%</div><div class="col-md-6">%to_date%</div></div>' ) );

		$this->validatorSchema ['date'] = new sfValidatorDateRange ( array (

				'required' => true,

				'from_date' => new sfValidatorTime ( array (
						'required' => true ) ),

				'to_date' => new sfValidatorTime ( array (
						'required' => true ) ) ), array (
				'invalid' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The start time at (%left_field%) must be before the end time at(%right_field%)' ) ) );

		unset ( $this ['start_time_at'], $this ['end_time_at'] );

		$this->addBootstrapForm ();

		$this->showUseFields ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}

	public function processValues($values) {

		$values = parent::processValues ( $values );
		$values ['start_time_at'] = date ( "H:i", strtotime ( $values ["date"] ["from"] ) );
		$values ['end_time_at'] = date ( "H:i", strtotime ( $values ["date"] ["to"] ) );

		return $values;
	}

	protected function showUseFields() {

		$this->useFields ( array (
				'ps_customer_id',
				'ps_workplace_id',
				'ps_class_room_id',
				'ps_service_course_id',
				'date_at',
				'date',
				'note',
				'is_activated' ) );
	}
}
