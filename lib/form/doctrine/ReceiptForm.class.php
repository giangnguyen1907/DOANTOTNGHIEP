<?php
/**
 * Receipt form.
 *
 * @package    backend
 * @subpackage form
 * @author     Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ReceiptForm extends BaseReceiptForm {

	public function configure() {

		// $this->widgetSchema ['receipt_no']->setAttribute ('readonly', 'readonly');
		$this->removeFields ();

		$this->widgetSchema ['balance_last_month_amount'] = new sfWidgetFormInputText ( array (), array (
				'type' => 'number',
				'style' => 'text-align: right;' ) );

		$this->validatorSchema ['balance_last_month_amount'] = new sfValidatorNumber ();

		$this->widgetSchema ['ps_fee_report_amount'] = new sfWidgetFormInputText ( array (), array (
				'type' => 'number',
				'style' => 'text-align: right;' ) );

		$this->validatorSchema ['ps_fee_report_amount'] = new sfValidatorNumber ( array (
				'max' => 999999999 ), array (
				'max' => 'Value from %min% to %max%' ) );

		$this->widgetSchema ['collected_amount'] = new sfWidgetFormInputText ( array (), array (
				'type' => 'number',
				'style' => 'text-align: right;' ) );

		$this->validatorSchema ['collected_amount'] = new sfValidatorNumber ( array (
				'max' => 999999999 ), array (
				'max' => 'Value from %min% to %max%' ) );

		$this->setDefault ( 'collected_amount', PreNumber::number_format ( $this->getObject ()
			->getCollectedAmount (), 0, ',', '.' ) );

		$this->widgetSchema ['balance_amount'] = new sfWidgetFormInputText ( array (), array (
				'type' => 'number',
				'style' => 'text-align: right;' ) );

		$this->validatorSchema ['balance_amount'] = new sfValidatorNumber ( array (
				'max' => 999999999 ), array (
				'max' => 'Value from %min% to %max%' ) );

		// $this->widgetSchema ['note'] = new sfWidgetFormTextarea ();
		// $this->widgetSchema ['note']->setAttribute ( 'class', 'form-control' );

		$this->validatorSchema ['note'] = new sfValidatorString ( array (
				'required' => false,
				'max_length' => 255 ), array (
				'max_length' => 'Maximum %max_length% characters' ) );

		$this->widgetSchema ['payment_status'] = new sfWidgetFormSelect ( array (
				'choices' => PreSchool::loadPsPaymentStatus () ), array (
				'class' => 'form-control',
				'required' => true ) );

		$this->validatorSchema ['payment_status'] = new sfValidatorChoice ( array (
				'choices' => array_keys ( PreSchool::loadPsPaymentStatus () ),
				'required' => true ) );

		$this->widgetSchema ['is_public'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['payment_type'] = new sfWidgetFormSelect ( array (
				'choices' => array (
						'' => '' ) + PreSchool::loadPsPaymentType () ), array (
				'class' => 'form-control' ) );

		$this->addBootstrapForm ();

		$student_id = $this->getObject ()
			->getStudentId ();

		if ($student_id > 0) {

			// Lay phieu bao
			// $psFeeReport = Doctrine::getTable ( 'PsFeeReports' )->findPsFeeReportOfStudentByDate($student_id, strtotime($this->getObject ()->getReceiptDate()));

			$psFeeReport = $this->getObject ()
				->getStudent ()
				->findPsFeeReportOfStudentByDate ( strtotime ( $this->getObject ()
				->getReceiptDate () ) );

			if ($psFeeReport) {

				$this->setDefault ( 'ps_fee_report_amount', PreNumber::number_format ( $psFeeReport->getReceivable (), 0, '', '' ) );

				// $this->setDefault ('ps_fee_report_amount', $psFeeReport->getReceivable());
			}

			/*
			 * $this->widgetSchema ['student_id'] = new sfWidgetFormDoctrineChoice ( array (
			 * 'model' => "Student",
			 * 'query' => Doctrine::getTable ( 'Student' )->setSqlStudentById ( $student_id ),
			 * 'add_empty' => false ), array (
			 * 'class' => 'form-control' ) );
			 * $this->validatorSchema ['student_id'] = new sfValidatorDoctrineChoice ( array (
			 * 'required' => true,
			 * 'model' => 'Student',
			 * 'column' => 'id' ) );
			 */

			$this->widgetSchema ['student_id'] = new sfWidgetFormInputHidden ();

			$this->validatorSchema ['student_id'] = new sfValidatorInteger ( array (
					'required' => true ) );

			$this->widgetSchema ['relative_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => $this->getRelatedModelName ( 'Relative' ),
					'query' => Doctrine::getTable ( 'Relative' )->setSqlRelativeByStudent ( $student_id ),
					'add_empty' => null ), array (
					'class' => 'form-control' ) );

			$this->validatorSchema ['relative_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => $this->getRelatedModelName ( 'Relative' ),
					'column' => 'id' ) );

			$this->widgetSchema ['payment_date'] = new psWidgetFormInputDate ();

			$this->widgetSchema ['payment_date']->setAttributes ( array (
					'data-dateformat' => 'dd-mm-yyyy',
					'placeholder' => 'dd-mm-yyyy' ) );

			$this->validatorSchema ['payment_date'] = new sfValidatorDate ( array (
					'required' => false ) );
		}

		if ($this->getObject ()
			->getRelativeId () > 0) {
			$this->widgetSchema ['payment_relative_name']->setAttribute ( 'readonly', 'readonly' );
		}

		// $this->showUseFields();
	}

	protected function removeFields() {

		unset ( $this ['created_at'], $this ['updated_at'], $this ['user_created_id'], $this ['user_updated_id'], $this ['receipt_no'] );
		unset ( $this ['is_current'], $this ['file_name'], $this ['ps_customer_id'], $this ['title'], $this ['is_import'], $this ['receipt_date'] );
	}

	protected function showUseFields() {

		$this->useFields ( array (
				'student_id',
				'receipt_no',
				'payment_status',
				'collected_amount',
				'balance_amount',
				'note',
				'is_public' ) );
	}

	public function updateObject($values = null) {

		$object = parent::updateObject ( $values );

		$userId = sfContext::getInstance ()->getUser ()
			->getGuardUser ()
			->getId ();

		if ($this->getObject ()
			->isNew ()) {
			$object->setUserCreatedId ( $userId );
			$object->setUserUpdatedId ( $userId );
		} else {
			$object->setUserUpdatedId ( $userId );
			$currentDateTime = new PsDateTime ();
			$object->setUpdatedAt ( $currentDateTime->getCurrentDateTime () );
		}

		$object->setNoteEdit ( 'Edit by User ID: ' . $userId . '-' . sfContext::getInstance ()->getUser ()
			->getGuardUser ()
			->getFirstName () . ' ' . sfContext::getInstance ()->getUser ()
			->getGuardUser ()
			->getLastName () );

		return $object;
	}
}
class MyReceiptForm extends BaseReceiptForm {

	public function configure() {

		$this->useFields ( array (
				'id' ) );

		$this->removeFields ();

		/**
		 * ***** FOR ReceivableStudent ********************************************************************
		 */

		$this->widgetSchema ['student_id'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['receivable_id'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['service_id'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['by_number'] = new sfWidgetFormInput ();
		$this->widgetSchema ['spent_number'] = new sfWidgetFormInput ();
		$this->widgetSchema ['unit_price'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['amount'] = new sfWidgetFormInputHidden (); // Khoan tien phai thu = Don gia* so luong su dung
		$this->widgetSchema ['is_late'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['receivable_at'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['note'] = new sfWidgetFormInput ();

		/**
		 * ***** END FOR ReceivableStudent ********************************************************************
		 */

		/**
		 * ***** FOR CollectedStudent ************************************************************************
		 */

		$this->widgetSchema ['receivable_student_id'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['collected_student_id'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['collected_receipt_id'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['payment_amount'] = new sfWidgetFormInput ();

		/**
		 * ***** END FOR CollectedStudent ********************************************************************
		 */

		$this->validatorSchema ['by_number'] = new sfValidatorNumber ( array (
				'max' => 999999999,
				'min' => 0 ), array (
				'max' => 'Value from %min% to %max%',
				'min' => 'Value from %min% to %max%' ) );

		$this->validatorSchema ['spent_number'] = new sfValidatorNumber ( array (
				'max' => 999999999,
				'min' => 0 ), array (
				'max' => 'Value from %min% to %max%',
				'min' => 'Value from %min% to %max%' ) );

		$this->validatorSchema ['payment_amount'] = new sfValidatorNumber ( array (
				'max' => 999999999,
				'min' => 0 ), array (
				'max' => 'Value from %min% to %max%',
				'min' => 'Value from %min% to %max%' ) );

		$this->validatorSchema ['note'] = new sfValidatorString ( array (
				'required' => false,
				'max_length' => 255 ), array (
				'max_length' => 'Maximum %max_length% characters' ) );

		$this->widgetSchema->setNameFormat ( 'receipt_form[%s]' );
	}

	protected function removeFields() {

		unset ( $this ['created_at'], $this ['updated_at'] );
	}

	/*
	 * public function bind(array $taintedValues = null, array $taintedFiles = null){
	 * $new_forms = new BaseForm();
	 * if (isset($taintedValues['new'])) {
	 * if (isset($taintedValues['new']['receipt'])) {
	 * $receiptForm = $taintedValues['new']['receipt'];
	 * $receipt = new Receipt(false);
	 * $receipt->setCollectedAmount($receiptForm['collected_amount']);
	 * $receipt->setBalanceAmount($receiptForm['balance_amount']);
	 * $receipt->setNote($receiptForm['note']);
	 * $receipt_form = new ReceiptForm($receipt);
	 * $new_forms->embedForm('new[receipt]',$receipt_form);
	 * }
	 * }
	 * parent::bind($taintedValues, $taintedFiles);
	 * }
	 */
}
