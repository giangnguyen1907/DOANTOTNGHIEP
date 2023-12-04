<?php
/**
 * psReceipts module helper.
 *
 * @package    kidsschool.vn
 * @subpackage psReceipts
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psReceiptsGeneratorHelper extends BasePsReceiptsGeneratorHelper {

	public function linkToDetail($object, $params) {

		if ($object->getId () > 0) {
			return link_to ( '<i class="fa-fw fa fa-eye txt-color-blue" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'detail' ), $object, array (
					'class' => 'btn btn-xs btn-default',
					'data-backdrop' => 'static',
					'data-toggle' => 'modal',
					'data-target' => '#remoteModal' ) );
		} else {
			return '<a class="btn btn-xs btn-default disabled"><i class="fa-fw fa fa-eye text-muted"></i></a>';
		}
	}

	public function linkToEdit($object, $params) {

		if ($object->getId () > 0) {
			if ($object->getPaymentStatus () != PreSchool::ACTIVE)
				return link_to ( '<i class="fa-fw fa fa-pencil txt-color-orangeDark" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'edit' ), $object, array (
						'class' => 'btn btn-xs btn-default btn-edit-td-action ' ) );
			else
				return '<a class="btn btn-xs btn-default btn-edit-td-action disabled"><i class="fa-fw fa fa-pencil text-muted"></i></a>';
		} else {
			return '<a class="btn btn-xs btn-default btn-edit-td-action disabled"><i class="fa-fw fa fa-pencil text-muted"></i></a>';
		}
	}

	public function linkToDelete($object, $params) {

		if ($object->isNew () || (! myUser::isAdministrator () && $object && ($object->getPaymentStatus () == PreSchool::ACTIVE /* || $object->getIsPublic () == PreSchool::ACTIVE */
		))) {
			$label = '<a class="btn btn-xs btn-default disabled" style="opacity:0.5;"><i class="fa-fw fa fa-times text-muted" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i></a>';
			return $label;
		}

		/*
		 * if ($object->isNew () || ($object && $object->getPsCustomerId () != '133')) {
		 * $label = '<a class="btn btn-xs btn-default disabled" style="opacity:0.5;"><i class="fa-fw fa fa-times text-muted" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i></a>';
		 * return $label;
		 * }
		 */

		$label = '<i class="fa-fw fa fa-times txt-color-red" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>';

		return link_to ( $label, $this->getUrlForAction ( 'delete' ), $object, array (
				'method' => 'delete',
				'confirm' => ! empty ( $params ['confirm'] ) ? __ ( $params ['confirm'], array (), 'sf_admin' ) : $params ['confirm'],
				'class' => 'btn btn-xs btn-default' ) );
	}
}
