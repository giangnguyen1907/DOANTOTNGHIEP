<?php

/**
 * ServiceGroup filter form.
 *
 * @package    backend
 * @subpackage filter
 * @author     Quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ServiceGroupFormFilter extends BaseServiceGroupFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ( 'PS_STUDENT_SERVICE_GROUP_FILTER_SCHOOL' );
	}
}
