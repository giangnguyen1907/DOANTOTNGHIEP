<?php

/**
 * psCommentWeek module helper.
 *
 * @package    kidsschool.vn
 * @subpackage psCommentWeek
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psCommentWeekGeneratorHelper extends BasePsCommentWeekGeneratorHelper {

	public function linkToEdit($object, $params) {

		return link_to ( '<i class="fa-fw fa fa-pencil txt-color-orange" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'edit' ), $object, array (
				'class' => 'btn btn-xs btn-default btn-edit-td-action',
				'data-backdrop' => 'static',
				'data-toggle' => 'modal',
				'data-target' => '#remoteModal' ) );
	}

	public function linkToFilterReset2() {

		return link_to ( '<i class="fa-fw fa fa-refresh"></i> ' . __ ( 'Reset', array (), 'sf_admin' ), $this->getUrlForAction ( 'collection' ), array (
				'action' => 'comment' ), array (
				'query_string' => '_reset',
				'method' => 'post',
				'class' => 'btn btn-sm btn-default btn-filter-reset btn-psadmin' ) );
	}
}
