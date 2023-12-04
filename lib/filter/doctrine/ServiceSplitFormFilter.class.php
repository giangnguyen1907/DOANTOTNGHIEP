<?php

/**
 * ServiceSplit filter form.
 *
 * @package    backend
 * @subpackage filter
 * @author     Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ServiceSplitFormFilter extends BaseServiceSplitFormFilter {

	public function configure() {

		$this->widgetSchema ['service_id'] = new sfWidgetFormInputHidden ();
		$this->validatorSchema ['service_id'] = new sfValidatorInteger ( array (
				'required' => true ) );
	}
}
