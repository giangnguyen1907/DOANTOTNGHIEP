<?php

/**
 * StudentService form.
 *
 * @package    Preschool
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class StudentServiceForm extends BaseStudentServiceForm {

	public function configure() {

		/*
		$this->widgetSchema ['discount_amount'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['discount_amount']->setAttributes ( array (
				'class' => 'form-control',
				'type' => 'number' ) );

		$this->validatorSchema ['discount_amount'] = new sfValidatorNumber ( array (
				'required' => false ) );

		$this->widgetSchema ['discount'] = new sfWidgetFormInputText ();

		$this->widgetSchema ['discount']->setAttributes ( array (
				'class' => 'form-control',
				'type' => 'number' ) );

		$this->validatorSchema ['discount'] = new sfValidatorNumber ( array (
				'required' => false ) );
		*/

		$this->widgetSchema ['select'] = new sfWidgetFormInputCheckbox ();

		$this->validatorSchema ['select'] = new sfValidatorPass ( array (
				'required' => false ) );

		$this->setDefault ( 'select', false );

		$this->widgetSchema ['service_id'] = new sfWidgetFormInputHidden ();

		$this->validatorSchema ['service_id'] = new sfValidatorInteger ( array (
				'required' => true ) );

		$this->widgetSchema ['student_id'] = new sfWidgetFormInputHidden ();

		$this->widgetSchema ['ps_service_course_id'] = new sfWidgetFormInputHidden ();

		$this->validatorSchema ['student_id'] = new sfValidatorInteger ( array (
				'required' => true ) );

		$student_id = $this->getObject()->getStudentId();

		$psStudent = Doctrine::getTable('Student')->findOneById($student_id);

		$query = Doctrine_Query::create()->from('PsRegularity')->addWhere('ps_customer_id =?',$psStudent->getPsCustomerId());
		
		$this->widgetSchema ['regularity_id'] = new sfWidgetFormDoctrineChoice ( array (
			'model' => 'PsRegularity',
			'query' => $query,
			'add_empty' => false));

		$this->widgetSchema ['regularity_id']->setAttributes ( array (
				'class' => 'form-control') );

		/*
		 * if ($this->getObject()->isNew()) {
		 * $this->widgetSchema['keywords'] = new sfWidgetFormInputText();
		 * $this->widgetSchema['keywords']->setAttributes(array(
		 * 'class' => 'form-control',
		 * 'maxlength' => '30',
		 * 'placeholder' => sfContext::getInstance ()->getI18n ()->__ ( 'Keywords: Studentcode, first name, last name' )
		 * ));
		 * }
		 */
		$this->widgetSchema ['select']->setAttributes ( array (
				'class' => 'select checkbox' ) );

		$this->widgetSchema ['note'] = new sfWidgetFormTextarea ();

	    $this->widgetSchema['note']->setAttributes(array(
	        'class' => 'form-control',
	        'rows' => 1
	    ));

		// $this->addBootstrapForm ();

		
	}
	
	public function updateObject($values = null) {

		$object = parent::baseUpdateObject ( $values );
		
		return $object;
	}
}
