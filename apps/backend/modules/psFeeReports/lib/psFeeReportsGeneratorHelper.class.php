<?php

/**
 * psFeeReports module helper.
 *
 * @package    kidsschool.vn
 * @subpackage psFeeReports
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psFeeReportsGeneratorHelper extends BasePsFeeReportsGeneratorHelper {

	public function linkToDetail($object, $params) {

		if ($object->getId () > 0) {
			return link_to ( '<i class="fa-fw fa fa-eye txt-color-blue" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'detail' ), $object, array (
					'class' => 'btn btn-xs btn-default',
					'data-backdrop' => 'static',
					'data-toggle' => 'modal',
					'data-target' => '#remoteModal' ) );
		} else {
			return '<a class="btn btn-xs btn-default disabled"><i class="fa-fw fa fa-eye txt-color-blue"></i></a>';
		}
	}

	public function linkToEdit($object, $params) {

		if ($object->getId () > 0) {
			return link_to ( '<i class="fa-fw fa fa-pencil txt-color-orange" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'edit' ), $object, array (
					'class' => 'btn btn-xs btn-default btn-edit-td-action ' ) );
		} else {
			return '<a class="btn btn-xs btn-default btn-edit-td-action disabled"><i class="fa-fw fa fa-pencil txt-color-orange"></i></a>';
		}
	}

	public function linkToEditReceipt($object, $params) {

		if ($object->getId () > 0) {
			return link_to ( '<i class="fa-fw fa fa-pencil txt-color-orange" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'edit' ), $object, array (
					'class' => 'btn btn-xs btn-default btn-edit-td-action ' ) );
		} else {
			return '<a class="btn btn-xs btn-default btn-edit-td-action disabled"><i class="fa-fw fa fa-pencil txt-color-orange"></i></a>';
		}
	}

	public function linkToDelete($object, $params) {

		if ($object->isNew () || ($object && $object->getPaymentStatus () == PreSchool::ACTIVE)) {
			$label = '<a class="btn btn-xs btn-default disabled" style="opacity:0.5;"><i class="fa-fw fa fa-times txt-color-red" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i></a>';
			return $label;
		}

		$label = '<i class="fa-fw fa fa-times txt-color-red" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>';

		return link_to ( $label, $this->getUrlForAction ( 'delete' ), $object, array (
				'method' => 'delete',
				'confirm' => ! empty ( $params ['confirm'] ) ? __ ( $params ['confirm'], array (), 'sf_admin' ) : $params ['confirm'],
				'class' => 'btn btn-xs btn-default' ) );
	}

	public function linkToDelete2($object, $params) {

		$label = '<i class="fa fa-trash fa-lg fa-fw txt-color-red" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i> ' . __ ( $params ['label'], array (), 'sf_admin' );
		return link_to ( $label, $this->getUrlForAction ( 'delete' ), $object, array (
				'method' => 'delete',
				'confirm' => ! empty ( $params ['confirm'] ) ? __ ( $params ['confirm'], array (), 'sf_admin' ) : $params ['confirm'],
				'class' => '' ) );
	}

	public function linkToFilterSearch() {

		return '<button type="submit" rel="tooltip" data-placement="bottom" data-original-title="' . __ ( 'See the fee' ) . '" class="btn btn-sm btn-default btn-success btn-filter-search btn-psadmin" ><i class="fa-fw fa fa-search"></i></button>';
	}

	public function linkToFilterSearchReceivableStudent() {

		return '<button type="submit" rel="tooltip" data-placement="bottom" data-original-title="' . __ ( 'Search' ) . '" class="btn btn-sm btn-default btn-success btn-filter-search btn-psadmin" ><i class="fa-fw fa fa-search"></i></button>';
	}
}
