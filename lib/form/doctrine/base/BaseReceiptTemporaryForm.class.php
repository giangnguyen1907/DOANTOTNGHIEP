<?php

/**
 * ReceiptTemporary form base class.
 *
 * @method ReceiptTemporary getObject() Returns the current form's model object
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseReceiptTemporaryForm extends BaseFormDoctrine {

	public function setup() {

		$this->setWidgets ( array (
				'id' => new sfWidgetFormInputHidden (),
				'ps_customer_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'PsCustomer' ),
						'add_empty' => true ) ),
				'student_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'Student' ),
						'add_empty' => false ) ),
				'title' => new sfWidgetFormInputText (),
				'receipt_date' => new sfWidgetFormInputText (),
				'receivable' => new sfWidgetFormInputText (),
				'collected_amount' => new sfWidgetFormInputText (),
				'balance_amount' => new sfWidgetFormInputText (),
				'is_current' => new sfWidgetFormInputText (),
				'is_import' => new sfWidgetFormInputText (),
				'payment_status' => new sfWidgetFormInputCheckbox (),
				'relative_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'Relative' ),
						'add_empty' => true ) ),
				'note' => new sfWidgetFormInputText (),
				'user_created_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'UserCreated' ),
						'add_empty' => true ) ),
				'user_updated_id' => new sfWidgetFormDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'UserUpdated' ),
						'add_empty' => true ) ),
				'created_at' => new sfWidgetFormDateTime (),
				'updated_at' => new sfWidgetFormDateTime () ) );

		$this->setValidators ( array (
				'id' => new sfValidatorChoice ( array (
						'choices' => array (
								$this->getObject ()
									->get ( 'id' ) ),
						'empty_value' => $this->getObject ()
							->get ( 'id' ),
						'required' => false ) ),
				'ps_customer_id' => new sfValidatorDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'PsCustomer' ),
						'required' => false ) ),
				'student_id' => new sfValidatorDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'Student' ) ) ),
				'title' => new sfValidatorString ( array (
						'max_length' => 255,
						'required' => false ) ),
				'receipt_date' => new sfValidatorPass ( array (
						'required' => false ) ),
				'receivable' => new sfValidatorPass ( array (
						'required' => false ) ),
				'collected_amount' => new sfValidatorPass ( array (
						'required' => false ) ),
				'balance_amount' => new sfValidatorPass ( array (
						'required' => false ) ),
				'is_current' => new sfValidatorInteger ( array (
						'required' => false ) ),
				'is_import' => new sfValidatorInteger ( array (
						'required' => false ) ),
				'payment_status' => new sfValidatorBoolean ( array (
						'required' => false ) ),
				'relative_id' => new sfValidatorDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'Relative' ),
						'required' => false ) ),
				'note' => new sfValidatorString ( array (
						'max_length' => 255,
						'required' => false ) ),
				'user_created_id' => new sfValidatorDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'UserCreated' ),
						'required' => false ) ),
				'user_updated_id' => new sfValidatorDoctrineChoice ( array (
						'model' => $this->getRelatedModelName ( 'UserUpdated' ),
						'required' => false ) ),
				'created_at' => new sfValidatorDateTime (),
				'updated_at' => new sfValidatorDateTime () ) );

		$this->widgetSchema->setNameFormat ( 'receipt_temporary[%s]' );

		$this->errorSchema = new sfValidatorErrorSchema ( $this->validatorSchema );

		$this->setupInheritance ();

		parent::setup ();
	}

	public function getModelName() {

		return 'ReceiptTemporary';
	}
}
