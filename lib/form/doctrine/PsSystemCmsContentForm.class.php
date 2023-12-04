<?php

/**
 * PsSystemCmsContent form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsSystemCmsContentForm extends BasePsSystemCmsContentForm {

	public function configure() {

		$this->widgetSchema ['ps_system_cms_content_code'] = new sfWidgetFormSelect ( array (
				'choices' => array (
						'' => '-Select-' ) + PreSchool::loadPsSystemCmsContentCode () ), array (
				'class' => 'select2' ) );
		$this->widgetSchema ['ps_system_cms_content_code']->setLabel ( 'Content code' );

		$this->widgetSchema ['description']->setLabel ( 'Content' );
		$this->widgetSchema ['description']->setAttributes ( array (
				'class' => 'ckeditor' ) );

		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsActivity () ), array (
				'class' => 'radiobox' ) );

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
