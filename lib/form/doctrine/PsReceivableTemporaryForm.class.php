<?php

/**
 * PsReceivableTemporary form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsReceivableTemporaryForm extends BasePsReceivableTemporaryForm {

	public function configure() {

		$ps_receivable_temporary = $this->getObject ();

		$student_id = $ps_receivable_temporary->getStudentId ();
		$receivable_id = $ps_receivable_temporary->getReceivableId ();

		$ps_customer_id = Doctrine::getTable ( 'Student' )->findOneById ( $student_id )
			->getPsCustomerId ();

		$receivable = Doctrine::getTable ( 'Receivable' )->findOneById ( $receivable_id );

		$schoolyear_id = $receivable->getPsSchoolYearId ();

		$ps_workplace_id = $receivable->getPsWorkplaceId ();

		$this->widgetSchema ['school_year_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears () // 'add_empty' => '-Select school year-'
		), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ) ) );

		$this->validatorSchema ['school_year_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsSchoolYear',
				'column' => 'id' ) );

		$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsCustomer',
				'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED, $ps_customer_id ) ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select customer-' ) ) );

		$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsCustomer',
				'column' => 'id' ) );

		$this->widgetSchema ['student_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'Student',
				'query' => Doctrine::getTable ( 'Student' )->setAllStudentsByCustomerId ( $ps_customer_id, $student_id ),
				'add_empty' => _ ( '-Select student-' ) ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select student-' ) ) );

		$this->validatorSchema ['student_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'Student',
				'column' => 'id' ) );

		$this->widgetSchema ['amount'] = new sfWidgetFormInput ( array (), array (
				'type' => 'number',
				'step' => '10000',
				'min' => '0' ) );

		$this->widgetSchema ['receivable_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'Receivable',
				'query' => Doctrine::getTable ( 'Receivable' )->setListReceivableTempByParams ( array (
						'ps_school_year_id' => $schoolyear_id,
						'ps_customer_id' => $ps_customer_id,
						'ps_workplace_id' => $ps_workplace_id ) ),
				'add_empty' => _ ( '-Select receivable-' ) ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select receivable-' ) ) );

		$this->validatorSchema ['receivable_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'Receivable',
				'column' => 'id' ) );

		$this->widgetSchema ['receivable_at'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['receivable_at']->setAttributes ( array (
				'class' => 'receivable_at date_picker',
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );

		$this->validatorSchema ['receivable_at'] = new sfValidatorDate ( array (
				'required' => true,
				'max' => date ( 'Y-m-d' ) ) );

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
