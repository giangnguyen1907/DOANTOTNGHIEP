<?php

/**
 * FeatureOptionFeature form.
 *
 * @package    backend
 * @subpackage form
 * @author     Nguyen Chien Thang <ntsc279@hotmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FeatureOptionFeatureForm extends BaseFeatureOptionFeatureForm {

	public function configure() {

		// $this->removeFields();
		$this->widgetSchema ['type'] = new sfWidgetFormSelect ( array (
				'choices' => PreSchool::loadPsFeatureOptionFeature () ) );

		$this->setDefault ( 'order_by', Doctrine_Core::getTable ( 'FeatureOptionFeature' )->getMaxOrderBy ( sfContext::getInstance ()->getRequest ()
			->getParameter ( 'feature_branch_id' ) ) + 1 );

		// echo sfContext::getInstance()->getRequest()->getParameter('feature_branch_id');
		// $maxOrder = Doctrine_Core :: getTable('FeatureOptionFeature')->createQuery()->select('MAX(order_by) AS cnt_order')->fetchOne(array ());
	}

	/*
	 * public function removeFields()
	 * {
	 * unset($this['created_at'], $this['updated_at'], $this['feature_branch_id'], $this['feature_option_id']);
	 * }
	 */
	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}