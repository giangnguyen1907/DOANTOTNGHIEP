<?php

/**
 * PsMemberAllowance form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsMemberAllowanceForm extends BasePsMemberAllowanceForm {

	public function configure() {

		$this->widgetSchema ['ps_member_id'] = new sfWidgetFormInputHidden ();

		$ps_member = $this->getObject ()
			->getPsMember ();

		$ps_customer_id = $ps_member->getPsCustomerId ();

		$ps_member_id = $this->getObject ()
			->getPsMemberId ();

		$this->validatorSchema ['ps_member_id'] = new sfValidatorInteger ( array (
				'required' => true ) );

		$this->setDefault ( 'ps_member_id', $ps_member_id );

		if ($this->isNew ()) {

			$ps_customer_id = $this->getObject ()
				->getPsMember ()
				->getPsCustomerId ();
		} else {

			$ps_customer_id = $this->getObject ()
				->getPsAllowance ()
				->getPsCustomerId ();
		}
		$is_activated = PreSchool::ACTIVE;

		// echo $ps_customer_id;die;
		if ($ps_customer_id > 0) {

			$this->widgetSchema ['ps_allowance_id'] = new sfWidgetFormDoctrineChoice ( array (
					'model' => "PsAllowance",
					'query' => Doctrine::getTable ( 'PsAllowance' )->setSQLByCustomerId ( $ps_customer_id, $is_activated ),
					'add_empty' => _ ( '-Select allowances-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;" ) );
		} else {

			$this->widgetSchema ['ps_allowance_id'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'' => _ ( '-Select allowances-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;" ) );
		}

		$this->validatorSchema ['ps_allowance_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsAllowance',
				'column' => 'id' ) );

		$this->widgetSchema ['start_at'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['start_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );

		$this->validatorSchema ['start_at'] = new sfValidatorDate ( array (
				'required' => true ) );

		$start_at = $this->getDefault ( 'start_at' );

		$this->widgetSchema ['stop_at'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['stop_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy'
				 ) );

		$this->validatorSchema ['stop_at'] = new sfValidatorDate ( array (
				'required' => false ) );

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
