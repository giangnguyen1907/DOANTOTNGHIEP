<?php
/**
 * MyClass form.
 *
 * @package    backend
 * @subpackage form
 * @author     Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    1.0
 */
class MyClassForm extends BaseMyClassForm {

	public function configure() {

		unset ( $this ['number_student'] );

		$parameter_form = sfContext::getInstance ()->getRequest ()->getParameter ( 'my_class' );

		$this->addPsCustomerFormNotEdit ( 'PS_STUDENT_CLASS_FILTER_SCHOOL' );
		
		/*
		$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsCustomer',
				'required' => true ) );*/

		$ps_customer_id = $this->getObject ()->getPsCustomerId ();

		if (! $this->getObject ()->isNew ()) {
			$ps_workplace_id = $this->getObject ()->getPsWorkplaceId ();
			
			if ($ps_workplace_id <= 0)
				$ps_workplace_id = $this->getObject ()->getPsClassRooms ()->getPsWorkplaceId ();			
			
			$this->setDefault ( 'ps_workplace_id', $ps_workplace_id);			
		}
		
		$ps_workplace_id = $this->getDefault ( 'ps_workplace_id');

		if ($ps_customer_id > 0) {

			// ps_workplace_id filter by ps_customer_id
			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $ps_customer_id ),
					'add_empty' => '-Select workplace-' ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => true ) );
			
		} else {

			$this->widgetSchema ['ps_workplace_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select workplace-' ) ) );

			$this->validatorSchema ['ps_workplace_id'] = new sfValidatorInteger ( array (
					'required' => true ) );
		}

		if ($ps_workplace_id > 0) {
			$this->widgetSchema ['ps_class_room_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsClassRooms',
					'query' => Doctrine::getTable ( 'PsClassRooms' )->setSqlParams ( 'id, title', array (
							'ps_workplace_id' => $ps_workplace_id,
							'ps_customer_id' => $ps_customer_id ) ),
					'add_empty' => '-Select class room-' ) );

			$this->widgetSchema ['ps_class_room_id']->setOption ( 'add_empty', _ ( '-Select class room-' ) );

			$this->widgetSchema ['ps_class_room_id']->setAttributes ( array (
					'style' => 'min-width:200px;',
					'class' => 'select2' ) );

			$this->validatorSchema ['ps_class_room_id'] = new sfValidatorDoctrineChoice ( array (
					'model' => 'PsClassRooms',
					'required' => true ) );
		} else {

			$this->widgetSchema ['ps_class_room_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class room-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select class room-' ) ) );

			$this->validatorSchema ['ps_class_room_id'] = new sfValidatorInteger ( array (
					'required' => true ) );
		}

		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears () ) );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => false,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$this->validatorSchema ['code'] = new sfValidatorRegex ( array (
				'required' => true,
				'max_length' => 100,
				'pattern' => '/^[a-zA-Z0-9_-]+$/' ), array (
				'required' => 'Required.',
				'max_length' => 'Maximum %max_length% characters',
				'invalid' => 'Invalid code (includes only the characters a-z A-Z 0-9 _ -)' ) );

		/*
		 * $this->validatorSchema ['code'] = new sfValidatorAnd ( array (
		 * new sfValidatorRegex ( array (
		 * 'required' => true,
		 * 'max_length' => 100,
		 * 'pattern' => '/^[a-zA-Z0-9_-]+$/'
		 * ), array (
		 * 'required' => 'Required.',
		 * 'max_length' => 'Maximum %max_length% characters',
		 * 'invalid' => 'Invalid code (includes only the characters a-z A-Z 0-9 _ -)'
		 * ) ),
		 * new sfValidatorDoctrineUnique ( array (
		 * 'model' => 'MyClass',
		 * 'column' => array (
		 * 'code',
		 * 'ps_customer_id'
		 * )
		 * ), array (
		 * 'invalid' => 'Class code already exist.'
		 * ) )
		 * ) );
		 */
		$this->validatorSchema->setPostValidator ( new sfValidatorAnd ( array (

				new sfValidatorDoctrineUnique ( array (
						'model' => 'MyClass',
						'column' => array (
								'code',
								'ps_customer_id',
								'school_year_id' ) ), array (
						'invalid' => 'Class code already exist.' ) ) ) ) );

		// $this->widgetSchema['services_list'] = new sfWidgetFormDoctrineChoice(array('multiple' => true, 'expanded' => true,'model' => 'Service'));

		// $this->addMembersExpandedForm('ps_memberes_list', $ps_customer_id);

		$this->addServiceExpandedForm ( 'services_list', $ps_customer_id, true );

		// echo count($this->widgetSchema['services_list']->getChoices());

		// $this -> embedRelation('ClassService');

		$this->widgetSchema ['code']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 100 ) );
		$this->widgetSchema ['name']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 255 ) );
		$this->widgetSchema ['note']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 255 ) );
		$this->widgetSchema ['description']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 2000 ) );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['is_lastyear'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsPrimaryTeacher () ), array (
				'class' => 'radiobox' ) );

		$this->addBootstrapForm ();

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_CLASS_FILTER_SCHOOL' )) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control',
					'required' => 'required' ) );
		}
	}

	public function updateObject($values = null) {

		$object = parent::baseUpdateObject ( $values );

		if (! $this->getObject ()
			->isNew ()) {
			$number_student = $this->getObject ()
				->getNumberStudentActivitie ();
			$object->setStudentNumber ( $number_student );
		}

		return $object;
	}
}