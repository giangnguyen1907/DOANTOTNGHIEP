<?php

/**
 * PsProfessional filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsProfessionalFormFilter extends BasePsProfessionalFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ();
	}
}
