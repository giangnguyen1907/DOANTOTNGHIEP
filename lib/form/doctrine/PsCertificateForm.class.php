<?php

/**
 * PsCertificate form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsCertificateForm extends BasePsCertificateForm {

	public function configure() {

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );
		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
