<?php

/**
 * PsConstantOption filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsConstantOptionFormFilter extends BasePsConstantOptionFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_SYSTEM_CONSTANT_OPTION_FILTER_SCHOOL' );
	}
}
