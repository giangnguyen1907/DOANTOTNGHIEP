<?php

/**
 * PsEvaluateIndexStudent form.
 *
 * @package kidsschool.vn
 * @subpackage form
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsEvaluateIndexStudentForm extends BasePsEvaluateIndexStudentForm {

	public function configure() {

		$school_year = Doctrine::getTable('PsSchoolYear')->getPsSchoolYearsDefault()->fetchOne();
		
		$school_year_id = $school_year ? $school_year->getId() : '';
		
		if (myUser::credentialPsCustomers('PS_EVALUATE_INDEX_STUDENT_FILTER_SCHOOL')) {
			
			$this->widgetSchema['ps_customer_id'] = new sfWidgetFormDoctrineChoice(array(
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(PreSchool::ACTIVE),
					'add_empty' => '-Select customer-'
			), array(
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _('-Select customer-')
			));
			
			$this->validatorSchema['ps_customer_id'] = new sfValidatorDoctrineChoice(array(
					'required' => false,
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable('PsCustomer')->setSQLCustomers(PreSchool::ACTIVE),
					'column' => 'id'
			));
		} else {
			
			$this->widgetSchema['ps_customer_id'] = new sfWidgetFormInputHidden();
			
			$this->setDefault('ps_customer_id', myUser::getPscustomerID());
		}
		
		$ps_customer_id = $this->getDefault('ps_customer_id');
		
		if ($ps_customer_id > 0) {
			
			$this->widgetSchema['ps_workplace_id'] = new sfWidgetFormDoctrineChoice(array(
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable('PsWorkPlaces')->setSQLByCustomerId('id, title', $ps_customer_id),
					'add_empty' => '-Select workplace-'
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _('-Select workplace-')
			));
			
			$this->validatorSchema['ps_workplace_id'] = new sfValidatorDoctrineChoice(array(
					'required' => false,
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable('PsWorkPlaces')->setSQLByCustomerId('id, title', $ps_customer_id),
					'column' => 'id'
			));
		} else {
			
			$this->widgetSchema['ps_workplace_id'] = new sfWidgetFormChoice(array(
					'choices' => array(
							'' => _('-Select workplace-')
					)
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _('-Select workplace-')
			));
			
			$this->validatorSchema['ps_workplace_id'] = new sfValidatorPass(array(
					'required' => false
			));
		}
		
		$ps_workplace_id = $this->getDefault('ps_workplace_id');
		$param_class = array(
				'ps_customer_id' => $ps_customer_id,
				'ps_workplace_id' => $ps_workplace_id,
				'ps_school_year_id' => $school_year_id
		);
		
		if ($ps_workplace_id > 0) {
			
			$this->widgetSchema['ps_class_id'] = new sfWidgetFormDoctrineChoice(array(
					'model' => 'MyClass',
					'query' => Doctrine::getTable('MyClass')->setClassByParams($param_class),
					'add_empty' => _('-Select class-')
			), array(
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _('-Select class-')
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
		}
		
		$this->validatorSchema['ps_class_id'] = new sfValidatorDoctrineChoice(array(
				'required' => true,
				'model' => 'MyClass',
				'column' => 'id'
		));
		
		if ($ps_customer_id > 0 || $ps_workplace_id > 0) {
			
			$this->widgetSchema['evaluate_subject_id'] = new sfWidgetFormDoctrineChoice(array(
					'model' => 'PsEvaluateSubject',
					'query' => Doctrine::getTable('PsEvaluateSubject')->setSQLEvaluateIndexSubjectByParam(array(
							'is_activated' => PreSchool::ACTIVE,
							'ps_customer_id' => $ps_customer_id,
							'ps_workplace_id' => $ps_workplace_id
					)),
					'add_empty' => '-Select evaluate subject-'
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _('-Select evaluate subject-')
			));
		} else {
			
			$this->widgetSchema['evaluate_subject_id'] = new sfWidgetFormChoice(array(
					'choices' => array(
							'' => _('-Select evaluate subject-')
					)
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _('-Select evaluate subject-')
			));
		}
		
		$this->validatorSchema['evaluate_subject_id'] = new sfValidatorPass(array(
				'required' => true
		));
		
		$ps_class_id = $this->getDefault('ps_class_id');
		
		if ($ps_class_id > 0) {
			// Student
			$this->widgetSchema['student_id'] = new sfWidgetFormDoctrineChoice(array(
					'model' => 'Student',
					'query' => Doctrine::getTable('Student')->setSqlListStudentsByClassId($ps_class_id),
					'add_empty' => _('-Select students-')
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _('-Select students-')
			));
			
			$this->validatorSchema['student_id'] = new sfValidatorDoctrineChoice(array(
					'required' => true,
					// 'required' => false,
					'model' => 'Student',
					'column' => 'id'
			));
			
			$student_id = $this->getDefault('student_id');
		} else {
			// Student
			$this->widgetSchema['student_id'] = new sfWidgetFormChoice(array(
					'choices' => array(
							'' => _('-Select students-')
					)
			), array(
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _('-Select students-')
			));
			
			$this->validatorSchema['student_id'] = new sfValidatorPass(array(
					'required' => true
			)); // 'required' => false

		}
		
		$this->widgetSchema['is_public'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsActivity()
		), array(
				'class' => 'radiobox'
		));
		
		$this->widgetSchema['is_awaiting_approval'] = new psWidgetFormSelectRadio(array(
				'choices' => PreSchool::loadPsActivity()
		), array(
				'class' => 'radiobox'
		));
		
		/*
		 * $this->widgetSchema['date_at'] = new psWidgetFormFilterInputDate();
		 * $this->widgetSchema['date_at']->setAttributes(array(
		 * 'data-dateformat' => 'dd-mm-yyyy',
		 * 'placeholder' => 'dd-mm-yyyy',
		 * 'data-original-title' => sfContext::getInstance()->getI18n()->__('Date at'),
		 * ));
		 * $this->widgetSchema['date_at']->addOption('tooltip', sfContext::getInstance()->getI18n()->__('Date at'));
		 * $this->validatorSchema['date_at'] = new sfValidatorDate(array(
		 * 'required' => true
		 * ));
		 */
		$this->widgetSchema['date_at'] = new sfWidgetFormChoice(array(
				'choices' => array(
						'' => _('-Select month-')
				) + PsDateTime::psRangeMonthYear($yearsDefaultStart, $yearsDefaultEnd)
		), array(
				'class' => 'select2',
				'style' => "min-width:100px;",
				'placeholder' => _('-Select month-'),
				'rel' => 'tooltip',
				'data-original-title' => _('Select month')
		));
		
		$this->validatorSchema['date_at'] = new sfValidatorString(array(
				'required' => false
		));
		
		$this->addBootstrapForm();
	
	}

}
