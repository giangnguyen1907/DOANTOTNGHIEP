<?php

/**
 * psService module helper.
 *
 * @package    quanlymamnon.vn
 * @subpackage psService
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psServiceGeneratorHelper extends BasePsServiceGeneratorHelper {

	public function linkToUpdateOrder($object, $params) {

		return '<li class="sf_admin_action_save"><a href="javascript:void(0);" id="save_order" >' . __ ( $params ['label'] ) . '</a></li>';
	}

	public function linkToSave($object, $params) {

		// return '<li class="sf_admin_action_save"><a href="javascript:void(0);" id="save" >'.__($params['label']).'</a></li>';
		$label = '<i class="fa-fw fa fa-floppy-o" aria-hidden="true" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i> ';

		return '<button id="save-test" type="submit" class="btn btn-default btn-success btn-psadmin">' . $label . __ ( $params ['label'], array (), 'sf_admin' ) . '</button>';
	}

	public function linkToNewDetail($object, $params) {

		$label = '<i class="fa-fw fa fa-plus-square" aria-hidden="true" title="' . __ ( $params ['label'], array (), 'messages' ) . '"></i> ';
		return '<button id="btn_adddetail" type="button" class="btn btn-default btn-success btn-psadmin">' . $label . __ ( $params ['label'], array (), 'messages' ) . '</button>';
	}

	public function linkToDetail($object, $params) {

		return link_to ( '<i class="fa-fw fa fa-eye txt-color-blue" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'detail' ), $object, array (
				'class' => 'btn btn-xs btn-default',
				'data-backdrop' => 'static',
				'data-target' => '#remoteModal',
				'data-toggle' => "modal" ) );
	}
}
