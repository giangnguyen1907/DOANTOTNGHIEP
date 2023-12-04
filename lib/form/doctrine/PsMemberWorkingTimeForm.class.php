<?php

/**
 * PsMemberWorkingTime form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsMemberWorkingTimeForm extends BasePsMemberWorkingTimeForm {

	public function configure() {

		$this->widgetSchema ['ps_member_id'] = new sfWidgetFormInputHidden ();

		$ps_member = $this->getObject ()
			->getPsMember ();

		$ps_customer_id = $ps_member->getPsCustomerId ();

		$ps_member_id = $this->getObject ()
			->getPsMemberId ();

		$this->setDefault ( 'ps_member_id', $ps_member_id );

		$params = array (
				'ps_customer_id' => $ps_customer_id );

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