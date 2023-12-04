<?php

/**
 * PsApp filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsAppFormFilter extends BasePsAppFormFilter {

	public function configure() {

		$this->widgetSchema ['ps_app_root'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => $this->getRelatedModelName ( 'PsApp' ),
				'query' => Doctrine::getTable ( 'PsApp' )->setSQLPsApp ( null, true ),
				'add_empty' => _ ( '--Select Application--' ) ), array (
				'class' => 'select2',
				'style' => "min-width:200px;" ) );

		// Nhà phát triển
		$this->widgetSchema ['is_system'] = new sfWidgetFormSelect ( array (
				'choices' => array (
						'' => 'Is system' ) + PreSchool::loadPsBoolean () ) );
		$this->widgetSchema ['is_system']->setAttributes ( array (
				'class' => 'select2',
				'style' => "min-width:150px;" ) );

		$this->widgetSchema ['is_activated'] = new sfWidgetFormSelect ( array (
				'choices' => array (
						'' => '-Select state-' ) + PreSchool::loadPsAppActivated () ) );
		$this->widgetSchema ['is_activated']->setAttributes ( array (
				'class' => 'select2',
				'style' => "min-width:150px;" ) );
	}
}
