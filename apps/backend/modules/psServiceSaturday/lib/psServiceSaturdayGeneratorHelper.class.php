<?php

/**
 * psServiceSaturday module helper.
 *
 * @package    kidsschool.vn
 * @subpackage psServiceSaturday
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psServiceSaturdayGeneratorHelper extends BasePsServiceSaturdayGeneratorHelper {

	public function linkToEdit($object, $params) {

		if (! $object->getId ())
			return link_to ( '<i class="fa-fw fa fa-pencil txt-color-orange" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'edit' ), $object, array (
					'class' => 'btn btn-xs btn-default btn-edit-td-action' ) );

		$url = url_for ( '@ps_service_saturday_edit?id=' . $object->getPsId () );
		return $link = '<a class="btn btn-xs btn-default" href="' . $url . '"><i class="fa-fw fa fa-pencil txt-color-orange" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i></a>';
		// sau 1 ngay thi khong duoc phep sua
		// $startTime = date("Y-m-d H:i:s");
		// $cenvertedTime = date('Y-m-d H:i:s',strtotime('-1 day',strtotime($startTime)));
		// if(strtotime($cenvertedTime)-strtotime($object->getCreatedAt()) < 0){
		// return $link = '<a class="btn btn-xs btn-default" href="' . $url . '"><i class="fa-fw fa fa-pencil txt-color-orange" title="'.__($params['label'], array(), 'sf_admin').'"></i></a>';
		// }else{
		// return $link = '<a class="btn btn-xs btn-default disabled" href="#"><i class="fa-fw fa fa-pencil txt-color-orange" title="'.__($params['label'], array(), 'sf_admin').'"></i></a>';
		// }
	}
}
