<?php

/**
 * PsObjectGroups form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsObjectGroupsForm extends BasePsObjectGroupsForm {

	public function configure() {

		$this->widgetSchema ['title']->setAttributes ( array (
				'maxlength' => '255' ) );
		$this->widgetSchema ['note']->setAttributes ( array (
				'maxlength' => '255' ) );
		$this->widgetSchema ['iorder']->setAttributes ( array (
				'min' => 0 ) );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
