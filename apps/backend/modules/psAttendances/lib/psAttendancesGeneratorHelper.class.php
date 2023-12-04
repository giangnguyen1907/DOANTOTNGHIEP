<?php

/**
 * psAttendances module helper.
 *
 * @package    kidsschool.vn
 * @subpackage psAttendances
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psAttendancesGeneratorHelper extends BasePsAttendancesGeneratorHelper {

	public function linkToDelete2($object, $params) {

		if ($object->isNew ()) {
			return '';
		}

		$label = '<i class="fa-fw fa fa-times txt-color-red" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>';

		return link_to ( $label, $this->getUrlForAction ( 'delete' ), $object, array (
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

	public function linkToFilterReset3() {

		return link_to ( '<i class="fa-fw fa fa-refresh"></i> ' . __ ( 'Reset', array (), 'sf_admin' ), $this->getUrlForAction ( 'collection' ), array (
				'action' => 'syntheticMonth' ), array (
				'query_string' => '_reset',
				'method' => 'post',
				'class' => 'btn btn-sm btn-default btn-filter-reset btn-psadmin' ) );
	}
}
