<?php

/**
 * psMenus module helper.
 *
 * @package    quanlymamnon.vn
 * @subpackage psMenus
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psMenusGeneratorHelper extends BasePsMenusGeneratorHelper {

	public function linkToDetail($object, $params) {

		return link_to ( '<i class="fa-fw fa fa-eye txt-color-blue" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'detail' ), $object, array (
				'class' => 'btn btn-xs btn-default' ) );
	}
}
