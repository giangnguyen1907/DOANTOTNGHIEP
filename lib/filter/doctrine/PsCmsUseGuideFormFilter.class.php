<?php

/**
 * PsCmsUseGuide filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsCmsUseGuideFormFilter extends BasePsCmsUseGuideFormFilter {

	public function configure() {

		if (! myUser::credentialPsCustomers ( array (
				'PS_CMS_USEGUIDE_ADD',
				'PS_CMS_USEGUIDE_EDIT',
				'PS_CMS_USEGUIDE_DELETE' ), false )) {
			$this->widgetSchema ['is_activated'] = new sfWidgetFormSelect ( array (
					'choices' => array (
							'1' => 'yes' ) ) );
			$this->widgetSchema ['is_activated']->setAttributes ( array (
					'class' => 'form-control',
					'style' => "min-width:120px;" ) );
		}
	}
}
