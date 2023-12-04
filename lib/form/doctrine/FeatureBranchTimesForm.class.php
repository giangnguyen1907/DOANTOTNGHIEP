<?php

/**
 * FeatureBranchTimes form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FeatureBranchTimesForm extends BaseFeatureBranchTimesForm {

	public function configure() {

		$this->removeFields ();

		$ps_customer_id = $this->getObject ()
			->getFeatureBranch ()
			->getFeature ()
			->getPsCustomerId ();

		$feature_branch_times_filters ['feature_id'] = $this->getObject ()
			->getFeatureBranch ()
			->getId ();

		$feature_branch = Doctrine::getTable ( 'FeatureBranch' )->findOneById ( $this->getObject ()
			->getFeatureBranch ()
			->getId () );

		$this->widgetSchema ['ps_feature_branch_id'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						$feature_branch->getId () => $feature_branch->getName () ) ), array (
				'class' => 'form-control' ) );

		if ($ps_customer_id > 0) {

			// lay phong theo co so dao tao
			$ps_workplace_id = $this->getObject ()
				->getFeatureBranch ()
				->getPsWorkplaceId ();

			if ($ps_workplace_id <= 0)
				$arr_choices = Doctrine::getTable ( 'PsClassRooms' )->getGroupPsClassRooms ( $ps_customer_id );
			else
				$arr_choices = Doctrine::getTable ( 'PsClassRooms' )->getSqlParams ( 'c.id, c.title', array (
						'ps_customer_id' => $ps_customer_id,
						'ps_workplace_id' => $ps_workplace_id,
						'is_activated' => PreSchool::ACTIVE ) );

			$this->widgetSchema ['ps_class_room_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class room-' ) ) + $arr_choices ), array (
					'class' => 'select2',
					'placeholder' => _ ( '-Select class room-' ) ) );
		} else {
			$this->widgetSchema ['ps_class_room_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class room-' ) ) ), array (
					'class' => 'select2',
					'placeholder' => _ ( '-Select class room-' ) ) );
		}

		$this->widgetSchema ['start_at'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['start_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy' ) );

		$this->widgetSchema ['start_at']->addOption ( 'add-class', 'pickerAt1' );

		$this->widgetSchema ['end_at'] = new psWidgetFormInputDate ();
		$this->widgetSchema ['end_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy' ) );

		$this->widgetSchema ['end_at']->addOption ( 'add-class', 'pickerAt2' );

		$this->validatorSchema ['start_at'] = new sfValidatorDate ( array (
				'required' => true ) );
		$this->validatorSchema ['end_at'] = new sfValidatorDate ( array (
				'required' => true ) );

		$this->widgetSchema ['start_time'] = new psWidgetFormInputTime ();
		$this->widgetSchema ['start_time']->setAttributes ( array (
				'class' => 'startTime timepicker',
				'data-mode' => "24h",
				'required' => true ) );
		$this->validatorSchema ['start_time'] = new sfValidatorTime ( array (
				'required' => true ) );

		$this->widgetSchema ['end_time'] = new psWidgetFormInputTime ();
		$this->widgetSchema ['end_time']->setAttributes ( array (
				'class' => 'endTime timepicker',
				'data-mode' => "24h",
				'required' => true ) );
		$this->validatorSchema ['end_time'] = new sfValidatorTime ( array (
				'required' => true ) );

		$this->widgetSchema ['note']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 5000 ) );

		$this->widgetSchema ['is_saturday'] = new psWidgetFormInputCheckbox ( array () );

		$this->setDefault ( 'is_saturday', false );

		$this->widgetSchema ['is_saturday']->setAttributes ( array (
				'class' => 'checkbox style-0' ) );

		$this->widgetSchema ['is_sunday'] = new psWidgetFormInputCheckbox ( array () );

		$this->widgetSchema ['is_sunday']->setAttributes ( array (
				'class' => 'checkbox style-0' ) );

		$this->setDefault ( 'is_sunday', false );

		if ($this->getObject ()
			->getEndTime () == '' || $this->getObject ()
			->getEndTime () == null) {
			$this->getObject ()
				->setEndTime ( '23:59' );
		}

		/*
		 * $this->widgetSchema ['updated_time'] = new sfWidgetFormInputText ();
		 * $this->validatorSchema ['updated_time'] = new sfValidatorString ( array ('required' => false) );
		 * $this->setDefault ( 'updated_time', $this->getObject ()->getUpdatedAt () );
		 * $this->widgetSchema ['updated_by'] = new sfWidgetFormInputText ();
		 * $this->validatorSchema ['updated_by'] = new sfValidatorString (array('required' => false) );
		 * $this->setDefault( 'updated_by', $this->getObject ()->getUserCreated ()->getName () );
		 */
		/*
		 * if ($this->object->exists ()) {
		 * $this->widgetSchema ['delete'] = new sfWidgetFormInputCheckbox ();
		 * $this->validatorSchema ['delete'] = new sfValidatorPass ( array (
		 * 'required' => false
		 * ) );
		 * $this->widgetSchema ['delete']->setAttributes ( array (
		 * 'class' => 'btn btn-xs btn-default checkbox style-0'
		 * ) );
		 * }
		 */

		// Validate start_time <= end_time
		$this->validatorSchema->setPostValidator ( new sfValidatorOr ( array (

				new sfValidatorAnd ( array (
						new sfValidatorSchemaCompare ( 'start_time', sfValidatorSchemaCompare::NOT_EQUAL, null ),
						new sfValidatorSchemaCompare ( 'end_time', sfValidatorSchemaCompare::EQUAL, null, array (
								'throw_global_error' => false ), array (
								'invalid' => 'Invalid value.' ) ) ) ),

				new sfValidatorSchemaCompare ( 'start_time', sfValidatorSchemaCompare::LESS_THAN_EQUAL, 'end_time', array (
						'throw_global_error' => false ), array (
						'invalid' => 'The start time ("%left_field%") must be before the end time ("%right_field%")' ) ) ) ) );

		// Validate end_at <= start_at
		$this->validatorSchema->setPostValidator ( new sfValidatorOr ( array (

				new sfValidatorAnd ( array (
						new sfValidatorSchemaCompare ( 'start_at', sfValidatorSchemaCompare::NOT_EQUAL, null ),
						new sfValidatorSchemaCompare ( 'end_at', sfValidatorSchemaCompare::EQUAL, null, array (
								'throw_global_error' => false ), array (
								'invalid' => 'Invalid value.' ) ) ) ),

				new sfValidatorSchemaCompare ( 'start_at', sfValidatorSchemaCompare::LESS_THAN_EQUAL, 'end_at', array (
						'throw_global_error' => false ), array (
						'invalid' => 'The start date ("%left_field%") must be before the end date ("%right_field%")' ) ) ) ) );

		$this->addBootstrapForm ();

		$this->widgetSchema->setNameFormat ( 'psactivitie[%s]' );
	}

	protected function removeFields() {

		unset ( $this ['note_class_name'] );
		parent::removeFields ();

		// unset($this['ps_feature_branch_id']);
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}

	/*
	 * public function processValues($values) {
	 * $values = parent::processValues($values);
	 * $values['start_at'] = $values["start_at"];
	 * $values['end_at'] = $values["end_at"];
	 * return $values;
	 * }
	 */
}
