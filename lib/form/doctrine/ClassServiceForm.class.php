<?php

/**
 * ClassService form.
 *
 * @package    backend
 * @subpackage form
 * @author     Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ClassServiceForm extends BaseClassServiceForm {

	public function configure() {

		$this->removeFields2 ();

		$this->widgetSchema ['service_id'] = new sfWidgetFormDoctrineChoice ( array (
				'multiple' => true,
				'expanded' => true,
				'model' => $this->getRelatedModelName ( 'Service' ),
				'add_empty' => false ) );

		// $this->widgetSchema->setLabel('service_id', false);
	}

	protected function removeFields2() {

		unset ( $this ['created_at'], $this ['updated_at'], $this ['myclass_id'] );
	}
}
class ClassServiceFormExtends extends ClassServiceForm {

	public function configure() {

		$this->removeFields ();

		$this->widgetSchema ['myclass_id'] = new sfWidgetFormInputHidden ();
		$this->widgetSchema ['service_id'] = new sfWidgetFormDoctrineChoice ( array (
				'multiple' => true,
				'expanded' => true,
				'model' => $this->getRelatedModelName ( 'Service' ),
				'add_empty' => false ) );
	}
}
