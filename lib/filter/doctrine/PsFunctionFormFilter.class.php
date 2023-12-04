<?php

/**
 * PsFunction filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsFunctionFormFilter extends BasePsFunctionFormFilter {

	public function configure() {

		$this->addPsCustomerFormFilter ();
	}
}
