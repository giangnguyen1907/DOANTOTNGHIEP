<?php

/**
 * PsTimesheet form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsTimesheetForm extends BasePsTimesheetForm {

	public function configure() {

		$member = $this->getObject ()
			->getPsMember ();

		$this->setDefault ( 'member_id', $member->getId () );

		$this->widgetSchema ['member_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsMember',
				'query' => Doctrine::getTable ( 'PsMember' )->setSQLByMemberId ( $member->getId () ),
				'add_empty' => _ ( '-Select member-' ) ), array (
				'class' => 'select2',
				'style' => "min-width:150px;",
				'data-placeholder' => _ ( '-Select member-' ) ) );

		$this->validatorSchema ['member_id'] = new sfValidatorDoctrineChoice ( array (
				'required' => true,
				'model' => 'PsMember',
				'column' => 'id' ) );

		$this->widgetSchema ['timesheet_at'] = new psWidgetFormInputDate ();

		$this->widgetSchema ['timesheet_at']->setAttributes ( array (
				'data-dateformat' => 'dd-mm-yyyy',
				'placeholder' => 'dd-mm-yyyy',
				'required' => 'required' ) );

		$this->validatorSchema ['timesheet_at'] = new sfValidatorDate ( array (
				'required' => true,
				'max' => date ( 'Y-m-d' ) ) );

		// $this->widgetSchema['date_time'] = new sfWidgetFormInputHidden();

		unset ( $this ['is_io'], $this ['is_error'], $this ['number_time'] );

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
