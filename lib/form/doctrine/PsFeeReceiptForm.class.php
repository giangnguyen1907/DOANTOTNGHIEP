<?php

/**
 * PsFeeReceipt form.
 *
 * @package kidsschool.vn
 * @subpackage form
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsFeeReceiptForm extends BasePsFeeReceiptForm {

	public function configure() {
		
		$ps_customer_id = 0;
		// $this->removeFields ();
		if (! $this->getObject()->isNew()) {
			
			$student = $this->getObject()->getStudent();
			
			$ps_customer_id = $student->getPsCustomerId();
			
			// Lay lop hoc cua hoc sinh tai thoi diem dang ky
			$student_class = $student->getClassByDate(PsDateTime::psDatetoTime(date('d-m-Y')));
			
			$this->setDefault('ps_workplace_id', $student_class->getPsWorkplaceId());
			
			$this->setDefault('ps_class_id', $student_class->getMyclassId());
			
			$this->setDefault('student_id', $student->getId());
			
			$ps_customer = Doctrine::getTable('PsCustomer')->findOneById($ps_customer_id);
			
			$this->setDefault('ps_customer_id', $ps_customer_id);
			
			$this->widgetSchema['ps_customer_id'] = new sfWidgetFormChoice(array(
					'choices' => array(
							$ps_customer->getId() => $ps_customer->getSchoolCode() . '-' . $ps_customer->getSchoolName()
					)
			));
			
			$this->validatorSchema['ps_customer_id'] = new sfValidatorChoice(array(
					'choices' => array(
							$ps_customer->getId()
					)
			));
			
			$this->widgetSchema['ps_customer_id']->setAttributes(array(
					'class' => 'form-control',
					'required' => true
			));
			
			$this->widgetSchema['student_id'] = new sfWidgetFormChoice(array(
					'choices' => array(
							$student->getId() => $student->getFirstName() . ' ' . $student->getLastName()
					)
			));
			
			$this->validatorSchema['student_id'] = new sfValidatorChoice(array(
					'choices' => array(
							$student->getId()
					)
			));
			
			$this->widgetSchema['student_id']->setAttributes(array(
					'class' => 'form-control',
					'required' => true
			));
		} else {
			
			if (! myUser::credentialPsCustomers('PS_FEE_RECEIPT_NOTICATION_FILTER_SCHOOL')) {
				
				$ps_customer_id = myUser::getPscustomerID();
				
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormInputHidden();
				
				$this->validatorSchema['ps_customer_id'] = new sfValidatorPass(array(
						'required' => false
				));
			} else {
				
				$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(PreSchool::CUSTOMER_ACTIVATED),
						'add_empty' => _('-All school-')
				), array(
						'class' => 'select2',
						'style' => "min-width:200px;width:100%;",
						'required' => true,
						'data-placeholder' => _('-All school-')
				));
				
				$this->validatorSchema['ps_customer_id'] = new sfValidatorDoctrineChoice(array(
						'model' => 'PsCustomer',
						'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(PreSchool::CUSTOMER_ACTIVATED),
						'required' => true
				));
			}
			
			if ($ps_customer_id <= 0) {
				$ps_customer_id = myUser::getPscustomerID();
				$this->setDefault('ps_customer_id', $ps_customer_id);
			}
			
			$ps_workplace_id = $this->getDefault('ps_workplace_id');
			
			$this->setDefault('ps_workplace_id', $ps_workplace_id);
			
			$ps_school_year_id = $this->getDefault('school_year_id');
			
			$ps_school_year_id = Doctrine::getTable('PsSchoolYear')->findOneBy('is_default', PreSchool::ACTIVE)->getId();
			
			$this->widgetSchema['school_year_id'] = new sfWidgetFormDoctrineChoice(array(
					'model' => 'PsSchoolYear',
					'query' => Doctrine::getTable('PsSchoolYear')->setSqlPsSchoolYears(),
					'add_empty' => false
			), array(
					'class' => 'select2',
					'style' => "width:100%;min-width:150px;",
					'data-placeholder' => _('-Select school year-'),
					'required' => true
			));
			
			$this->validatorSchema['school_year_id'] = new sfValidatorDoctrineChoice(array(
					'model' => 'PsSchoolYear',
					'column' => 'id',
					'query' => Doctrine::getTable('PsSchoolYear')->setSqlPsSchoolYears(),
					'required' => true
			));
			
			$this->setDefault('school_year_id', $ps_school_year_id);
			
			if ($ps_customer_id > 0) {
				
				$this->widgetSchema['ps_workplace_id'] = new sfWidgetFormDoctrineChoice(array(
						'model' => 'PsWorkPlaces',
						'query' => Doctrine::getTable('PsWorkPlaces')->setSQLByCustomerId('id, title', $ps_customer_id),
						'add_empty' => '-Select workplace-'
				));
				
				$this->validatorSchema['ps_workplace_id'] = new sfValidatorDoctrineChoice(array(
						'required' => true,
						'model' => 'PsWorkPlaces',
						//'query' => Doctrine::getTable('PsWorkPlaces')->setSQLByCustomerId('id, title', $ps_customer_id),
						'column' => 'id'
				));
			} else {
				$this->widgetSchema['ps_workplace_id'] = new sfWidgetFormChoice(array(
						'choices' => array(
								'' => _('-Select workplace-')
						)
				), array(
						'class' => 'select2',
						'data-placeholder' => _('-Select workplace-')
				));
				
				$this->validatorSchema['ps_workplace_id'] = new sfValidatorPass(array(
						'required' => true
				));
			}
			
			$ps_workplace_id = $this->getDefault('ps_workplace_id');
			
			if ($ps_workplace_id > 0) {
				$this->widgetSchema['ps_class_id'] = new sfWidgetFormDoctrineChoice(array(
						'model' => 'MyClass',
						'query' => Doctrine::getTable('MyClass')->setClassByParams(array(
								'ps_customer_id' => $ps_customer_id,
								'ps_workplace_id' => $ps_workplace_id,
								'ps_school_year_id' => $ps_school_year_id,
								'is_activated' => PreSchool::ACTIVE
						)),
						'add_empty' => _('-Select class-')
				), array(
						'class' => 'select2',
						'style' => "min-width:200px;",
						'data-placeholder' => _('-Select class-')
				));
				
				$this->validatorSchema['ps_class_id'] = new sfValidatorDoctrineChoice(array(
						'required' => true,
						'model' => 'MyClass',
						'query' => Doctrine::getTable('MyClass')->setClassByParams(array(
								'ps_customer_id' => $ps_customer_id,
								'ps_workplace_id' => $ps_workplace_id,
								'ps_school_year_id' => $ps_school_year_id,
								'is_activated' => PreSchool::ACTIVE
						)),
						'column' => 'id'
				));
			} else {
				
				$this->widgetSchema['ps_class_id'] = new sfWidgetFormChoice(array(
						'choices' => array(
								'' => _('-Select class-')
						)
				), array(
						'class' => 'select2',
						'style' => "min-width:200px;",
						'data-placeholder' => _('-Select class-')
				));
				
				$this->validatorSchema['ps_class_id'] = new sfValidatorPass(array(
						'required' => true
				));
			}
			
			$student_id = $this->getDefault('student_id');
			
			$ps_class_id = $this->getDefault('ps_class_id');
			
			if ($ps_class_id > 0) {
				
				$this->widgetSchema['student_id'] = new sfWidgetFormDoctrineChoice(array(
						'model' => 'Student',
						'query' => Doctrine::getTable('Student')->setSqlListStudentsByClassId($ps_class_id),
						'add_empty' => _('-Select student-')
				), array(
						'class' => 'select2',
						'style' => "min-width:150px;",
						'data-placeholder' => _('-Select student-')
				));
				
				$this->validatorSchema['student_id'] = new sfValidatorDoctrineChoice(array(
						'required' => true,
						'model' => 'Student',
						'query' => Doctrine::getTable('Student')->setSqlListStudentsByClassId($ps_class_id),
						'column' => 'id'
				));
			} else {
				
				$this->widgetSchema['student_id'] = new sfWidgetFormChoice(array(
						'choices' => array(
								'' => _('-Select student-')
						)
				), array(
						'class' => 'select2',
						'style' => "min-width:200px;",
						'data-placeholder' => _('-Select students-')
				));
				
				$this->validatorSchema['student_id'] = new sfValidatorPass(array(
						'required' => true
				));
			}
		}
		
		if ($this->isNew()) {
			$this->validatorSchema['receipt_no'] = new sfValidatorString(array(
					'required' => false
			));
		}
		
		$this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
				new sfValidatorDoctrineUnique(array(
						'model' => 'PsFeeReceipt',
						'column' => array(
								'receipt_no'
						)
				), array(
						'invalid' => 'Code already exist'
				))
		)));
		
		$this->widgetSchema['receivable_amount']->setAttributes(array(
				'class' => 'form-control',
				'maxlength' => 12,
				'required' => true,
				'type' => 'number'
		));
		
		$this->validatorSchema['receivable_amount'] = new sfValidatorInteger(array(
				'required' => true
		));
		
		$this->widgetSchema['collected_amount']->setAttributes(array(
				'class' => 'form-control',
				'maxlength' => 12,
				'required' => true,
				'type' => 'number'
		));
		
		$this->validatorSchema['collected_amount'] = new sfValidatorInteger(array(
				'required' => true
		));
		
		$this->widgetSchema['balance_amount']->setAttributes(array(
				'class' => 'form-control',
				'maxlength' => 12,
				'required' => true,
				'type' => 'number'
		));
		
		$this->validatorSchema['balance_amount'] = new sfValidatorInteger(array(
				'required' => true
		));
		
		$this->widgetSchema['receipt_date'] = new psWidgetFormInputDate();
		
		$this->widgetSchema['receipt_date']->setAttributes(array(
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required'
		));
		
		$this->validatorSchema['receipt_date'] = new sfValidatorDate(array(
				'required' => true,
				'max' => date('Y-m-d')
		));
		
		$this->widgetSchema['payment_date'] = new psWidgetFormInputDate();
		
		$this->widgetSchema['payment_date']->setAttributes(array(
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => false
		));
		
		$this->validatorSchema['payment_date'] = new sfValidatorDate(array(
				'required' => false,
				'max' => date('Y-m-d')
		));
		
		$this->widgetSchema['note']->setAttributes(array(
				'class' => 'form-control',
				'maxlength' => 255
		));
		
		$this->widgetSchema['is_public'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsBoolean()
		), array(
				'class' => 'radiobox'
		));
		
		$this->widgetSchema['payment_status'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsPaymentStatus()
		), array(
				'class' => 'radiobox'
		));
		
		$this->addBootstrapForm();
	
	}

	public function updateObject($values = null) {

		$object = parent::baseUpdateObject($values);
		
		$object->setReceiptNo(PreString::trim($this->getValue('receipt_no')));
		
		return $object;
	
	}

	protected function removeFields() {

		unset($this['created_at'], $this['updated_at'], $this['user_created_id'], $this['user_updated_id']);
		
		$this->widgetSchema['receipt_no']->setAttribute('readonly', 'readonly');
		$this->widgetSchema['receipt_no']->setAttribute('placeholder', sfContext::getInstance()->getI18n()
			->__('Automatic system generated'));
		
		// if ($this->getObject()->isNew()) {
		
		// unset($this['created_at'], $this['updated_at'], $this['user_created_id'], $this['user_updated_id']);
		
		// $this->widgetSchema['receipt_no']->setAttribute('readonly', 'readonly');
		// $this->widgetSchema['receipt_no']->setAttribute('placeholder', sfContext::getInstance()->getI18n()
		// ->__('Automatic system generated'));
		
		// } else {
		// unset($this['created_at'], $this['updated_at'], $this['user_created_id'], $this['user_updated_id']);
		// }
	}

}
