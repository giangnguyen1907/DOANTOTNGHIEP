<?php

/**
 * PsServiceSaturdayDate form.
 *
 * @package    kidsschool.vn
 * @subpackage form
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsServiceSaturdayDateForm extends BasePsServiceSaturdayDateForm {

	public function configure() {

		// $this->widgetSchema['service_date'] = new sfWidgetFormInputCheckbox();
		// $this->widgetSchema ['service_date'] = new psWidgetFormInputDate ();

		// lay ra thu 7 của tháng hien tai va thang ke tiep
		$service_date = PsDateTime::psListDaysValueOfMonth ( "Sat" );

		$this->widgetSchema ['service_date'] = new sfWidgetFormChoice ( array (
				'choices' => array_combine ( $service_date, $service_date ),
				'renderer_options' => array (
						'template' => '<label>%options%</label' ) ) );

		$this->widgetSchema ['service_date']->setOption ( 'expanded', true );
		$this->widgetSchema ['service_date']->setOption ( 'multiple', true );

		unset ( $this ['ps_service_saturday_id'], $this ['deleted_at'], $this ['note'], $this ['is_status'], $this ['feeback_note'] );

		// $this->addBootstrapForm();
	}
}
