<?php

/**
 * PsTemplateExports filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsTemplateExportsFormFilter extends BasePsTemplateExportsFormFilter {

	public function configure() {

		$this->widgetSchema ['app_code'] = new sfWidgetFormChoice ( array (
				'choices' => array (
						'' => _ ( '-Select-' ) ) + Doctrine::getTable ( 'PsApp' )->getGroupPsApps () ), array (
				'class' => 'select2',
				'style' => "min-width:200px;",
				'data-placeholder' => _ ( '-Select-' ) ) );
	}
}
