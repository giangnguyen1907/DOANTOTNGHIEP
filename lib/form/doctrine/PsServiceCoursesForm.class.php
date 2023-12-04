<?php

/**
 * PsServiceCourses form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsServiceCoursesForm extends BasePsServiceCoursesForm {

	public function configure() {

		$ps_customer_id = null;

		if ($this->getObject ()
			->isNew ()) { // Add new

			if (! myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_COURSES_FILTER_SCHOOL' )) {

				$ps_customer_id = myUser::getPscustomerID ();

				$this->setDefault ( 'ps_customer_id', $ps_customer_id );
			}
		} else {

			$ps_customer_id = $this->getObject ()
				->getPsService ()
				->getPsCustomerId ();
			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
		}

		if (myUser::credentialPsCustomers ( 'PS_STUDENT_SERVICE_COURSES_FILTER_SCHOOL' )) { // Neu co quyen thay doi truong hoc

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
			}

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

		$this->widgetSchema ['start_at'] = new psWidgetFormInputDate ();
		$this->widgetSchema ['start_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );

		$this->widgetSchema ['end_at'] = new psWidgetFormInputDate ();
		$this->widgetSchema ['end_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );

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
			$this->widgetSchema ['ps_member_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsMember',
					'query' => Doctrine::getTable ( 'PsMember' )->setSQLMembers ( $ps_customer_id ),
					'add_empty' => '-Select member-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select member-' ) ) );
		} else {

			$this->widgetSchema ['ps_service_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select subjects-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select subjects-' ) ) );
			$this->widgetSchema ['ps_member_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select member-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( '-Select member-' ) ) );
		}
		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['note']->setAttributes ( array (
				'maxlength' => 255 ) );
		$this->widgetSchema ['title']->setAttributes ( array (
				'maxlength' => 255,
				'required' => true ) );

		$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsCustomer',
				'required' => true ) );

		$this->validatorSchema ['ps_member_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsMember',
				'required' => true ) );

		$this->validatorSchema ['ps_service_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'Service',
				'required' => true ) );

		$this->validatorSchema ['note'] = new sfValidatorString ( array (
				'required' => false ) );

		$this->validatorSchema ['title'] = new sfValidatorString ( array (
				'required' => true ) );
		$this->validatorSchema ['is_activated'] = new sfValidatorInteger ( array (
				'required' => true ) );

		$this->validatorSchema ['start_at'] = new sfValidatorDate ( array (
				'required' => true ) );
		$this->validatorSchema ['end_at'] = new sfValidatorDate ( array (
				'required' => true ) );

		$this->widgetSchema ['ps_service_id']->setLabel ( 'Subjects title' );
		$this->widgetSchema ['ps_member_id']->setLabel ( 'Teacher' );
		$this->widgetSchema ['title']->setLabel ( 'Course title' );

		$this->addBootstrapForm ();

		$this->showUseFields ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}

	protected function showUseFields() {

		$this->useFields ( array (
				'ps_customer_id',
				'ps_service_id',
				'ps_member_id',
				'title',
				'start_at',
				'end_at',
				'note',
				'is_activated' ) );
	}
}
