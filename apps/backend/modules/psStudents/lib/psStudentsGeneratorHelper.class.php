<?php
/**
 * psStudents module helper.
 *
 * @package    quanlymamnon.vn
 * @subpackage psStudents
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psStudentsGeneratorHelper extends BasePsStudentsGeneratorHelper {

	public function linkToEdit($object, $params) {

		if ($object->getId () > 0) {
			if ($object->getDeletedAt () == '' || $object->getDeletedAt () == null)
				return link_to ( '<i class="fa-fw fa fa-pencil txt-color-orange" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'edit' ), $object, array (
						'class' => 'btn btn-xs btn-default btn-edit-td-action ' ) );
			else
				return '<a class="btn btn-xs btn-default btn-edit-td-action disabled"><i class="fa-fw fa fa-pencil txt-color-orange"></i></a>';
		} else {
			return '<a class="btn btn-xs btn-default btn-edit-td-action disabled"><i class="fa-fw fa fa-pencil txt-color-orange"></i></a>';
		}
	}

	public function linkToDelete($object, $params) {

		if ($object->isNew ()) {
			return '';
		} elseif ($object->getDeletedAt () != '') {
			return '<a class="btn btn-xs btn-default btn-edit-td-action disabled"><i class="fa-fw fa fa-trash-o txt-color-red"></i></a>';
		} else {
			$label = '<i class="fa-fw fa fa-times txt-color-red" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>';
			return link_to ( $label, $this->getUrlForAction ( 'delete' ), $object, array (
					'method' => 'delete',
					'confirm' => ! empty ( $params ['confirm'] ) ? __ ( $params ['confirm'], array (), 'sf_admin' ) : $params ['confirm'],
					'class' => 'btn btn-xs btn-default pull-right' ) );
		}
	}

	public function linkToFilterReset2() {

		return link_to ( '<i class="fa-fw fa fa-refresh"></i> ' . __ ( 'Reset', array (), 'sf_admin' ), $this->getUrlForAction ( 'collection' ), array (
				'action' => 'synthetic' ), array (
				'query_string' => '_reset',
				'method' => 'post',
				'class' => 'btn btn-sm btn-default btn-filter-reset btn-psadmin' ) );
	}
}
