<?php

/**
 * psHistoryFees module helper.
 *
 * @package    KidsSchool.vn
 * @subpackage psHistoryFees
 * @author     KidsSchool.vn <contact@kidsschool.vn>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psHistoryFeesGeneratorHelper extends BasePsHistoryFeesGeneratorHelper {
	
	public function linkToDetail($object, $params) {
		return link_to ( '<i class="fa-fw fa fa-eye txt-color-blue" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'detail' ), $object, array (
				'class' => 'btn btn-xs btn-default'
		) );
	}
}
