<?php

/**
 * PsFeeReceivableStudent form.
 *
 * @package kidsschool.vn
 * @subpackage form
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsFeeReceivableStudentForm extends BasePsFeeReceivableStudentForm {

	public function configure() {

		$fee_receipt = $this->getObject()->getPsFeeReceiptId();
		$ps_fee_receipt = Doctrine::getTable('PsFeeReceipt')->findOneById($fee_receipt);
		$student = Doctrine::getTable('Student')->findOneById($ps_fee_receipt->getStudentId());
		
		$this->widgetSchema['ps_fee_receipt_id'] = new sfWidgetFormChoice(array(
				'choices' => array(
						$ps_fee_receipt->getId() => $ps_fee_receipt->getReceiptNo()
				)
		), array(
				'class' => 'form-control'
		));
		
		$this->validatorSchema ['ps_fee_receipt_id'] = new sfValidatorPass( array (
				'required' => true ) );
		
		$this->widgetSchema['student_id'] = new sfWidgetFormChoice(array(
				'choices' => array(
						$student->getId() => $student->getFirstName() . ' ' . $student->getLastName()
				)
		), array(
				'class' => 'form-control'
		));
		
		$this->widgetSchema['amount']->setAttributes(array(
				'class' => 'form-control',
				'maxlength' => 12,
				'required' => true,
				'type' => 'number'
		));
		
		$this->validatorSchema['amount'] = new sfValidatorInteger(array(
				'required' => true
		));
		
		$this->widgetSchema['spent_number']->setAttributes(array(
				'class' => 'form-control',
				'maxlength' => 12,
				'required' => true,
				'type' => 'number'
		));
		
		$this->validatorSchema ['spent_number'] = new sfValidatorInteger ( array (
				'required' => true ) );

		$this->addBootstrapForm ();
		$this->widgetSchema->setNameFormat ( 'psactivitie[%s]' );
	}

	public function updateObject($values = null) {

		$object = parent::baseUpdateObject ( $values );
	}
}
