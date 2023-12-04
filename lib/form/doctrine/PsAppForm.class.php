<?php
/**
 * PsApp form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsAppForm extends BasePsAppForm {

	public function configure() {

		$this->widgetSchema ['ps_app_root'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => $this->getRelatedModelName ( 'PsApp' ),
				'query' => Doctrine::getTable ( 'PsApp' )->setSQLPsApp ( $this->getObject ()
					->get ( 'id' ), true ),
				'add_empty' => true ) );

		$this->widgetSchema ['ps_app_root']->setDefault ( $this->getObject ()
			->get ( 'ps_app_root' ) );

		// Dinh dang lai ma
		if (! $this->getObject ()
			->isNew ()) {

			if ($this->getObject ()
				->get ( 'ps_app_root' ) > 0) {
				// Lay app_code cua ps_app_root(id)
				$parent_app_code = Doctrine::getTable ( 'PsApp' )->findOneBy ( 'id', $this->getObject ()
					->get ( 'ps_app_root' ) )
					->get ( 'app_code' );
			} else
				// Lay app_code cua ps_app_root(id)
				$parent_app_code = 'PS';

			$this->getObject ()
				->set ( 'app_code', str_replace ( $parent_app_code . '_', '', $this->getObject ()
				->get ( 'app_code' ) ) );
		}

		$this->widgetSchema ['description']->setAttributes ( array (
				'class' => 'form-control',
				'maxlength' => 500 ) );
		$this->widgetSchema ['is_system'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsBoolean () ), array (
				'class' => 'radiobox' ) );
		// $this->widgetSchema['is_activated'] = new sfWidgetFormChoice(array('expanded' => true, 'choices' => PreSchool::loadPsAppActivated()));
		$this->widgetSchema ['is_activated'] = new psWidgetFormSelectRadio ( array (
				'choices' => PreSchool::loadPsAppActivated () ), array (
				'class' => 'radiobox' ) );

		$this->widgetSchema ['ps_app_root']->setDefault ( $this->getObject ()
			->get ( 'ps_app_root' ) );

		if ($this->getObject ()
			->isNew ())
			$this->setDefault ( 'iorder', Doctrine::getTable ( 'PsApp' )->getMaxIorder () + 1 );

		$this->addBootstrapForm ();
	}

	public function updateObject($values = null) {

		$object = parent::updateObject ( $values );

		if (! $this->getObject ()
			->get ( 'ps_app_root' ))
			$object->setAppCode ( 'PS_' . strtoupper ( $this->getObject ()
				->get ( 'app_code' ) ) );
		else {
			// Lay app_code cua ps_app_root
			$app_code = Doctrine::getTable ( 'PsApp' )->findOneById ( $this->getObject ()
				->get ( 'ps_app_root' ) )
				->get ( 'app_code' );
			$object->setAppCode ( $app_code . '_' . strtoupper ( $this->getObject ()
				->get ( 'app_code' ) ) );
		}

		if ($this->getObject ()
			->isNew ()) {
			$object->set ( 'user_created_id', sfContext::getInstance ()->getUser ()
				->getGuardUser ()
				->getId () );
			$object->set ( 'user_updated_id', sfContext::getInstance ()->getUser ()
				->getGuardUser ()
				->getId () );
		} else {
			$object->set ( 'user_updated_id', sfContext::getInstance ()->getUser ()
				->getGuardUser ()
				->getId () );
		}

		return $object;
	}

	/*
	 * protected function doSave($con = null) {
	 * //$this->updateObject();
	 * $object->setIorder($this->getObject()->get('id'));
	 * return parent :: doSave($con);
	 * }
	 */
}