<?php

/**
 * ServiceDetail
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    Preschool
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class ServiceDetail extends BaseServiceDetail {

	public function getServiceDetailByDate($service_id, $date) {

		// return Doctrine::getTable("ServiceDetail")->getServiceDetailByDate($service_id, $date);
		return Doctrine::getTable ( "ServiceDetail" )->findOneServiceDetailByDate ( $service_id, $date );
	}
}