<?php
/**
 * PsConfigPayments filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsConfigPaymentsFormFilter extends BasePsConfigPaymentsFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_FEE_CONFIG_PAYMENT_FILTER_SCHOOL' );
	}
}