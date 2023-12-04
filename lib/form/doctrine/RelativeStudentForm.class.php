<?php

/**
 * RelativeStudent form.
 *
 * @package    backend
 * @subpackage form
 * @author     Nguyen Chien Thang <ntsc279@hotmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class RelativeStudentForm extends BaseRelativeStudentForm {

	public function configure() {

		$this->widgetSchema ['is_parent'] = new sfWidgetFormInputCheckbox ();

		$this->widgetSchema ['is_parent']->setAttributes ( array (
				'class' => 'checkbox style-0' ) );

		$this->setDefault ( 'is_parent', false );

		$this->widgetSchema ['is_parent_main'] = new sfWidgetFormInputCheckbox ();

		$this->widgetSchema ['is_parent_main']->setAttributes ( array (
				'class' => 'checkbox style-0' ) );

		$this->setDefault ( 'is_parent_main', false );

		$this->widgetSchema ['is_role'] = new sfWidgetFormInputCheckbox ();

		$this->widgetSchema ['is_role']->setAttributes ( array (
				'class' => 'checkbox style-0' ) );

		$this->setDefault ( 'is_role', false );

		$this->widgetSchema ['role_service'] = new sfWidgetFormInputCheckbox ();

		$this->widgetSchema ['role_service']->setAttributes ( array (
				'class' => 'checkbox style-0' ) );

		$this->setDefault ( 'role_service', false );

		$this->widgetSchema ['iorder']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 11,
				'min' => 1,
				'required' => true,
				'type' => 'number' ) );

		$this->validatorSchema ['iorder'] = new sfValidatorInteger ( array (
				'required' => true ) );

		$this->setDefault ( 'iorder', 1 );

		$this->widgetSchema ['student_id'] = new sfWidgetFormInputHidden ();

		$this->validatorSchema ['student_id'] = new sfValidatorInteger ( array (
				'required' => true ) );

		if ($this->getObject ()
			->isNew ()) {

			$this->widgetSchema ['keywords'] = new sfWidgetFormInputText ();

			$this->widgetSchema ['keywords']->setAttributes ( array (
					'class' => 'form-control',
					'maxlength' => '30',
					'placeholder' => sfContext::getInstance ()->getI18n ()
						->__ ( 'Keywords: First name, last name, mobile' ) ) );

			$this->widgetSchema ['relationship_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Relationship',
					'query' => Doctrine::getTable ( 'Relationship' )->sqlAllRelationShip (),
					'add_empty' => ('-Select-') ), array (
					'class' => 'select3',
					'style' => "min-width:150px;",
					'data-placeholder' => _ ( '-Select-' ) ) );
		} else {
			$this->widgetSchema ['relationship_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => 'Relationship',
					'query' => Doctrine::getTable ( 'Relationship' )->sqlAllRelationShip (),
					'add_empty' => false ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'data-placeholder' => _ ( '-Select Relation-' ) ) );

			$this->widgetSchema ['relative_id'] = new sfWidgetFormInputHidden ();

			$this->widgetSchema ['student_id'] = new sfWidgetFormInputHidden ();

			$this->validatorSchema ['student_id'] = new sfValidatorInteger ( array (
					'required' => true ) );
		}

		$this->validatorSchema ['relationship_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'Relationship',
				'required' => true ) );
		// $cho = Doctrine_Core :: getTable('Relationship')->findAll();

		// $this->widgetSchema['relationship_id'] = new sfWidgetFormSelect(array('choices' => $cho));

		// $this->validatorSchema['relationship_id'] = new sfValidatorInteger(array('required' => true,'min' => 1), array('required' => 'Required relationship','min' => 'Required relationship'));

		unset ( $this ['created_at'], $this ['updated_at'] );
	}
	
	public function updateObject($values = null) {
		
		return parent::baseUpdateObject ( $values );
	}
}
