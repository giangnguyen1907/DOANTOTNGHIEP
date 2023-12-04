<?php

/**
 * PsAdviceCategories filter form.
 *
 * @package    kidsschool.vn
 * @subpackage filter
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsAdviceCategoriesFormFilter extends BasePsAdviceCategoriesFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_STUDENT_ADVICE_CATEGORIES_FILTER_SCHOOL' );

		$ps_customer_id = $this->getDefault ( 'ps_customer_id' );
	}
}