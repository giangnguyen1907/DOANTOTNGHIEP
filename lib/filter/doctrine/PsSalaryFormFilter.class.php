<?php

/**
 * PsSalary filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsSalaryFormFilter extends BasePsSalaryFormFilter {

	public function configure() {

		if (! myUser::credentialPsCustomers ( 'PS_HR_SALARY_FILTER_SCHOOL' )) { // Neu ko co quyen loc du lieu theo truong

			$ps_customer_id = myUser::getPscustomerID ();

			$this->widgetSchema ['ps_customer_id'] = new sfWidgetFormInputHidden ();

			$this->setDefault ( 'ps_customer_id', $ps_customer_id );
		} else {

			$ps_customer_id = null;
		}

		// $this->widgetSchema['is_activated'] = new sfWidgetFormChoice(array(
		// 'choices' => PreSchool::getStatus()
		// ), array(
		// 'class' => 'select2',
		// 'data-placeholder' => _('-Select state-')
		// ));

		// $this->showUseFields();
	}
}
