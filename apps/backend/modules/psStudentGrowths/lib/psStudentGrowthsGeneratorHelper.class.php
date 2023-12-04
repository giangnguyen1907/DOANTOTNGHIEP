<?php

/**
 * psStudentGrowths module helper.
 *
 * @package    quanlymamnon.vn
 * @subpackage psStudentGrowths
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psStudentGrowthsGeneratorHelper extends BasePsStudentGrowthsGeneratorHelper {

	public function linkToEdit($object, $params) {

		if (! $object->getId ())
			return link_to ( '<i class="fa-fw fa fa-pencil txt-color-orange" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'edit' ), $object, array (
					'class' => 'btn btn-xs btn-default btn-edit-td-action disabled' ) );
		return link_to ( '<i class="fa-fw fa fa-pencil txt-color-orange" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'edit' ), $object, array (
				'class' => 'btn btn-xs btn-default btn-edit-td-action' ) );
	}

	public function linkToDetail($object, $params) {

		if (! $object->getId ())
			return link_to ( '<i class="fa-fw fa fa-eye txt-color-blue" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'detail' ), $object, array (
					'class' => 'btn btn-xs btn-default disabled',
					'data-backdrop' => 'static',
					'data-toggle' => 'modal',
					'data-target' => '#remoteModal' ) );

		$url = url_for ( '@ps_student_growths_detail?id=' . $object->getStudentId () );

		return $link = '<a data-toggle="modal" data-target="#remoteModal" data-backdrop="static" class="btn btn-xs btn-default" href="' . $url . '"><i class="fa-fw fa fa-eye txt-color-blue" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i></a>';
	}

	public function linkToDelete($object, $params) {

		if (! $object->getId ())
			return link_to ( '<i class="fa-fw fa fa-times txt-color-red" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'delete' ), $object, array (
					'method' => 'delete',
					'confirm' => ! empty ( $params ['confirm'] ) ? __ ( $params ['confirm'], array (), 'sf_admin' ) : $params ['confirm'],
					'class' => 'btn btn-xs btn-default pull-right disabled' ) );
		return link_to ( '<i class="fa-fw fa fa-times txt-color-red" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'delete' ), $object, array (
				'method' => 'delete',
				'confirm' => ! empty ( $params ['confirm'] ) ? __ ( $params ['confirm'], array (), 'sf_admin' ) : $params ['confirm'],
				'class' => 'btn btn-xs btn-default pull-right' ) );
	}

	public function linkToFilterReset2() {

		return link_to ( '<i class="fa-fw fa fa-refresh"></i> ' . __ ( 'Reset', array (), 'sf_admin' ), $this->getUrlForAction ( 'collection' ), array (
				'action' => 'statistic' ), array (
				'query_string' => '_reset',
				'method' => 'post',
				'class' => 'btn btn-sm btn-default btn-filter-reset btn-psadmin' ) );
	}
}
