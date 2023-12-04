<?php

/**
 * psFeatureBranch module helper.
 *
 * @package    quanlymamnon.vn
 * @subpackage psFeatureBranch
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psFeatureBranchGeneratorHelper extends BasePsFeatureBranchGeneratorHelper {

	public function linkToSchedule2($object, $params) {

		return link_to ( '<i class="fa-fw fa fa-eye txt-color-blue" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'schedule' ), $object, array (
				'class' => 'btn btn-xs btn-default',
				'data-backdrop' => 'static',
				'data-toggle' => 'modal',
				'data-target' => '#remoteModal' ) );
	}

	public function linkToSchedule($object, $params) {

		return link_to ( '<i class="fa-fw fa fa-calendar txt-color-blue" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'schedule' ), $object, array (
				'class' => 'btn btn-xs btn-default btn-edit-td-action',
				'rel' => 'tooltip',
				'data-original-title' => __ ( $params ['label'] ),
				'data-placement' => 'bottom' ) );
	}

	public function linkToDetail($object, $params) {

		return link_to ( '<i class="fa-fw fa fa-eye txt-color-blue" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'detail' ), $object, array (
				'class' => 'btn btn-xs btn-default',
				'data-backdrop' => 'static',
				'data-toggle' => 'modal',
				'data-target' => '#remoteModal',
				'rel' => 'tooltip',
				'data-original-title' => __ ( $params ['label'] ),
				'data-placement' => 'bottom' ) );
	}

	public function linkToEdit($object, $params) {

		return link_to ( '<i class="fa-fw fa fa-pencil txt-color-orange" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'edit' ), $object, array (
				'class' => 'btn btn-xs btn-default btn-edit-td-action',
				'rel' => 'tooltip',
				'data-original-title' => __ ( $params ['label'] ),
				'data-placement' => 'bottom' ) );
	}

	public function linkToDelete($object, $params) {

		if ($object->isNew ()) {
			return '';
		}

		$label = '<i class="fa-fw fa fa-times txt-color-red" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>';

		return link_to ( $label, $this->getUrlForAction ( 'delete' ), $object, array (
				'method' => 'delete',
				'confirm' => ! empty ( $params ['confirm'] ) ? __ ( $params ['confirm'], array (), 'sf_admin' ) : $params ['confirm'],
				'class' => 'btn btn-xs btn-default pull-right',
				'rel' => 'tooltip',
				'data-original-title' => __ ( $params ['label'] ),
				'data-placement' => 'bottom' ) );
	}
}
