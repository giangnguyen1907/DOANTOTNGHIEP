<?php

/**
 * PsReceiptTemporary form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsReceiptTemporaryForm extends BasePsReceiptTemporaryForm {

	public function configure() {

		$this->addPsCustomerFormNotEdit ( 'PS_FEE_REPORT_FILTER_SCHOOL' );

		$this->validatorSchema ['ps_customer_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'PsCustomer',
				'required' => true ) );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );

		$student_id = $this->getObject ()
			->getStudentId ();

		$tudent_query = Doctrine::getTable ( 'Student' )->setAllStudentsByCustomerId ( $ps_customer_id, $student_id );

		if ($ps_customer_id > 0) {
			$this->widgetSchema ['student_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => "Student",
					'query' => $tudent_query,
					'add_empty' => false ) );
			$this->widgetSchema ['student_id']->setAttributes ( array (
					'required' => true,
					'class' => 'form-control' ) );
		} else {
			$this->widgetSchema ['student_id'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => _ ( '-Select student-' ) ) ), array (
					'class' => 'select2' ) );
		}

		if ($student_id > 0) {

			$this->widgetSchema ['relative_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'RelativeStudent',
					'query' => Doctrine::getTable ( 'RelativeStudent' )->sqlFindByStudentId ( $student_id, $ps_customer_id ),
					'add_empty' => _ ( '-Relative student-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Relative student-' ) ) );

			$this->validatorSchema ['relative_id'] = new sfValidatorDoctrineChoice ( array (
					'required' => false,
					'model' => 'RelativeStudent',
					'column' => 'id' ) );
		} else {
			$this->widgetSchema ['relative_id'] = new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Relative student-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Relative students-' ) ) );

			$this->validatorSchema ['relative_id'] = new sfValidatorPass ( array (
					'required' => false ) );
		}

		$this->widgetSchema ['receipt_date'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['receipt_date']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );

		$this->validatorSchema ['receipt_date'] = new sfValidatorDate ( array (
				'required' => true,
				'max' => date ( 'Y-m-d' ) ) );

		$this->widgetSchema ['is_import'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->addBootstrapForm ();

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			$this->widgetSchema ['ps_customer_id']->setAttributes ( array (
					'class' => 'form-control' ) );
		}
	}
}
