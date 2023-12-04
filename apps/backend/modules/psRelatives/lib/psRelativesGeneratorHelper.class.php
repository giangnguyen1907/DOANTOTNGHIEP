<?php

/**
 * psRelatives module helper.
 *
 * @package    quanlymamnon.vn
 * @subpackage psRelatives
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psRelativesGeneratorHelper extends BasePsRelativesGeneratorHelper {

	public function linkToFilterSearch() {

		return '<button type="submit" rel="tooltip" data-placement="bottom" data-original-title="' . __ ( 'Filter', array (), 'sf_admin' ) . '" class="btn btn-sm btn-default btn-success btn-filter-search btn-psadmin" ><i class="fa-fw fa fa-search"></i></button>';
	}

	public function linkToFilterReset2() {

		return link_to ( '<i class="fa-fw fa fa-refresh"></i> ' . __ ( 'Reset', array (), 'sf_admin' ), $this->getUrlForAction ( 'collection' ), array (
				'action' => 'statistic' ), array (
				'query_string' => '_reset',
				'method' => 'post',
				'class' => 'btn btn-sm btn-default btn-filter-reset btn-psadmin' ) );
	}
}
