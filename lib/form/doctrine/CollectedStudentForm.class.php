<?php

/**
 * CollectedStudent form.
 *
 * @package    Preschool
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CollectedStudentForm extends BaseCollectedStudentForm {

	public function configure() {

		$this->removeFields ();

		$this->mergeForm ( new RecurrenceServiceForm () );

		$this->setWidgets ( array (
				'amount' => new sfWidgetFormInputText ( array (), array (
						'class' => 'row_number' ) ),
				'amount_default' => new sfWidgetFormInputHidden (),
				'student_id' => new sfWidgetFormInputHidden (),
				'service_id' => new sfWidgetFormInputHidden (),
				'receivable_student_id' => new sfWidgetFormInputHidden (),
				'expires_at' => new sfWidgetFormDate ( array (
						'format' => '%month%/%year%' ) ),
				'old_expires_at' => new sfWidgetFormInputHidden (),
				'note' => new sfWidgetFormInputText (),
				'by_number' => new sfWidgetFormInputHidden () ) );

		$this->setDefault ( 'expires_at', null );

		$this->validatorSchema ['amount'] = new sfValidatorNumber ( array (
				'max' => 999999999,
				'min' => 0 ), array (
				'max' => 'Value from %min% to %max%',
				'min' => 'Value from %min% to %max%' ) );

		$this->validatorSchema ['expires_at'] = new sfValidatorDate ( array (
				'required' => false ) );

		$this->validatorSchema->setPostValidator ( new sfValidatorCallback ( array (
				'callback' => array (
						$this,
						'checkExpiresAt' ) ) ) );

		$this->validatorSchema ['note'] = new sfValidatorString ( array (
				'required' => false,
				'max_length' => 255 ), array (
				'max_length' => 'Maximum %max_length% characters' ) );
	}

	protected function removeFields() {

		unset ( $this ['created_at'], $this ['updated_at'], $this ['collected_at'] );
	}

	public function checkExpiresAt($validator, $values) {

		$receivable = $this->object->getReceivableStudent ();

		$recurrence = $receivable->getRecurrenceService ();
		// echo date('Ym', strtotime($receivable->getReceivableAt()))."x";
		if ($recurrence) {
			if (strtotime ( $values ['expires_at'] ) < strtotime ( $recurrence->getExpiresAt () )) {

				$error = new sfValidatorError ( $validator, 'Input value less than current value' );
				// throw an error bound to the password field
				throw new sfValidatorErrorSchema ( $validator, array (
						'expires_at' => $error ) );
			}
		}

		$date1 = date ( 'Ym', strtotime ( $receivable->getReceivableAt () ) );
		$date2 = ($values ['expires_at']) ? date ( 'Ym', strtotime ( $values ['expires_at'] ) ) : $date1;
		if ($date1 > $date2) {
			$error = new sfValidatorError ( $validator, 'Input value less than term date' );
			// throw an error bound to the password field
			throw new sfValidatorErrorSchema ( $validator, array (
					'expires_at' => $error ) );
		}

		return $values;
	}
}
