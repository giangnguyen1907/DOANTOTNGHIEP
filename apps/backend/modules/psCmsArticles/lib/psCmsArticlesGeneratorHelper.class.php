<?php

/**
 * psCmsArticles module helper.
 *
 * @package    one.asia
 * @subpackage psCmsArticles
 * @author     one.asia <contact@one.asia - ntsc279@gmail.com>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psCmsArticlesGeneratorHelper extends BasePsCmsArticlesGeneratorHelper {

	public function linkToDetail($object, $params) {

		return link_to ( '<i class="fa-fw fa fa-eye" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i> ' . __ ( $params ['label'], array (), 'sf_admin' ), $this->getUrlForAction ( 'detail' ), $object, array (
				'class' => 'btn btn-sm btn-primary',
				'data-backdrop' => 'static',
				'data-toggle' => 'modal',
				'data-target' => '#remoteModal' ) );
	}

	public function linkToEdit($object, $params) {

		return link_to ( '<i class="fa-fw fa fa-pencil" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i> ' . __ ( $params ['label'], array (), 'sf_admin' ), $this->getUrlForAction ( 'edit' ), $object, array (
				'class' => 'btn btn-sm btn-warning' ) );
	}

	public function linkToDelete($object, $params) {

		if ($object->isNew ()) {
			return '';
		}

		$label = '<i class="fa-fw fa fa-times" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i> ' . __ ( $params ['label'], array (), 'sf_admin' );

		return link_to ( $label, $this->getUrlForAction ( 'delete' ), $object, array (
				'method' => 'delete',
				'confirm' => ! empty ( $params ['confirm'] ) ? __ ( $params ['confirm'], array (), 'sf_admin' ) : $params ['confirm'],
				'class' => 'btn btn-sm btn-danger' ) );
	}
}
