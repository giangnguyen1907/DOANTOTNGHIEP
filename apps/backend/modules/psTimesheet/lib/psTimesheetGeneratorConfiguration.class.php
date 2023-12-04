<?php

/**
 * psTimesheet module configuration.
 *
 * @package    kidsschool.vn
 * @subpackage psTimesheet
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psTimesheetGeneratorConfiguration extends BasePsTimesheetGeneratorConfiguration {

	public function getFilterDefaults() {

		$ps_customer_id = myUser::getPscustomerID ();
		return array (
				'ps_customer_id' => $ps_customer_id );
	}
}
