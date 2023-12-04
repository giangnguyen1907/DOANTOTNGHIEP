<?php

/**
 * StudentServiceDiary form.
 *
 * @package    Preschool
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class StudentServiceDiaryForm extends BaseStudentServiceDiaryForm {

	public function configure() {

		// $student_id = sfContext::getInstance()->getRequest()->getParameter('student_id');

		// $this->widgetSchema['student_id'] = new sfWidgetFormInputHidden();

		// $this->widgetSchema['student_name'] = new sfWidgetFormInputText();

		// $this->validatorSchema['student_name'] = new sfValidatorString(array(
		// 'required' => true
		// ));

		// $this->widgetSchema['student_name']->setAttribute('readonly', 'readonly');

		// if ($student_id) {
		// $student = Doctrine::getTable('Student')->findOneBy('id', $student_id);
		// }
		// $ps_customer_id = ($student) ? $student->getPsCustomerId() : '' ;
		// $this->widgetSchema['login_relative'] = new sfWidgetFormDoctrineChoice(array(
		// 'model' => 'RelativeStudent',
		// 'query' => Doctrine::getTable('RelativeStudent')->sqlFindByStudentId($student_id, $ps_customer_id)

		// ), array(
		// 'class' => 'select2',
		// 'style' => "min-width:200px;",
		// 'data-placeholder' => _('-Select Relative-')
		// ));

		// $this->widgetSchema['login_relative']->setLabel('Login relative');

		// $this->validatorSchema['login_relative'] = new sfValidatorInteger(array(
		// 'required' => true
		// ));

		// $this->widgetSchema['input_time'] = new sfWidgetFormInputText();

		// $this->validatorSchema['input_time'] = new sfValidatorString(array(
		// 'required' => false
		// ));

		// $this->widgetSchema['service'] = new sfWidgetFormDoctrineChoice(array(
		// 'model' => 'RelativeStudent',
		// 'query' => Doctrine::getTable('RelativeStudent')->sqlFindByStudentId($student_id, $ps_customer_id)

		// ), array(
		// 'class' => 'checkbox'

		// ));

		// $this->validatorSchema['service'] = new sfValidatorInteger(array(
		// 'required' => false
		// ));

		// $this->showUseFields();
		$this->addBootstrapForm ();
	}

	protected function showUseFields() {

		if (myUser::credentialPsCustomers ()) {
			$this->useFields ( array (
					'student_name',
					'login_relative',
					'input_time',
					'service' ) );
		} else {
			$this->useFields ( array (
					'student_name',
					'login_relative' ) );
		}
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
