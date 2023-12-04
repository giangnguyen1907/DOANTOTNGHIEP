<?php
/**
 * PsWorkPlaces filter form.
 *
 * @package    quanlymamnon.vn
 * @subpackage filter
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsWorkPlacesFormFilter extends BasePsWorkPlacesFormFilter {

	public function configure() {

		// Overload ps_customer_id
		$this->addPsCustomerFormFilter ( 'PS_SYSTEM_WORK_PLACES_FILTER_SCHOOL' );
	}
}
