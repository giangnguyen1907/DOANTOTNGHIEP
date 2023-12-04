<?php

/**
 * PsMemberDepartments form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsMemberDepartmentsForm extends BasePsMemberDepartmentsForm {

	public function configure() {

		$this->widgetSchema ['ps_member_id'] = new sfWidgetFormInputHidden ();

		// $this->addPsCustomerFormNotEdit('PS_HR_HR_FILTER_SCHOOL');
		$ps_member = $this->getObject ()
			->getPsMember ();

		$ps_customer_id = $ps_member->getPsCustomerId ();

		$ps_member_id = $this->getObject ()
			->getPsMemberId ();

		$this->setDefault ( 'ps_member_id', $ps_member_id );

		// $this->widgetSchema['ps_department_id'] = new sfWidgetFormDoctrineChoice(array(
		// 'model' => "PsDepartment",
		// 'query' => Doctrine::getTable('PsDepartment')->setDepartmentByPsCustomer($ps_customer_id),
		// 'add_empty' => _('-Select departments-')
		// ), array(
		// 'class' => 'select2',
		// 'style' => "min-width:200px;",
		// 'data-placeholder' => _('-Select departments-')
		// ));

		// $this->validatorSchema['ps_department_id'] = new sfValidatorDoctrineChoice(array(
		// 'required' => false,
		// 'model' => 'PsDepartment',
		// 'column' => 'id'
		// ));

		// ======================================================================
		$params = array (
				'ps_customer_id' => $ps_customer_id );

		$choices = Doctrine::getTable ( 'PsDepartment' )->getChoisGroupDepartmentByCustomer ( $ps_customer_id, $this->getObject ()
			->getPsDepartmentId (), PreSchool::ACTIVE );

		$this->widgetSchema ['ps_department_id'] = new sfWidgetFormSelect ( array (
				'choices' => array (
						'' => '-Select departments-' ) + $choices ) );

		$this->validatorSchema ['ps_department_id'] = new sfValidatorInteger ( array (
				'required' => true ) );

		// ======================================================================
		$this->widgetSchema ['ps_function_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => "PsFunction",
				'query' => Doctrine::getTable ( 'PsFunction' )->setFunctionByCutomer ( $ps_customer_id ),
				'add_empty' => _ ( '-Select functions-' ) ), array (
				'class' => 'select2',
				'style' => "min-width:200px;",
				'data-placeholder' => _ ( '-Select functions-' ) ) );

		$this->validatorSchema ['ps_function_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsFunction',
				'column' => 'id' ) );

		$this->widgetSchema ['start_at'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['start_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );

		$this->validatorSchema ['start_at'] = new sfValidatorDate ( array (
				'required' => true ) );

		$this->widgetSchema ['stop_at'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['stop_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				) );

		$this->validatorSchema ['stop_at'] = new sfValidatorDate ( array (
				'required' => false ) );

		$this->widgetSchema ['is_current']->setDefault ( PreSchool::ACTIVE );

		$this->widgetSchema ['is_current'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
				'class' => 'radiobox' ) );

		$this->addBootstrapForm ();

		$this->widgetSchema ['ps_department_id']->setAttributes ( array (
				'class' => 'form-control',
				'required' => true ) );

		$this->widgetSchema ['ps_function_id']->setAttributes ( array (
				'class' => 'form-control',
				'required' => true ) );
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}