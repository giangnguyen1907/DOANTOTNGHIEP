<?php

/**
 * psFeatureBranchTimes module helper.
 *
 * @package    quanlymamnon.vn
 * @subpackage psFeatureBranchTimes
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psFeatureBranchTimesGeneratorHelper extends BasePsFeatureBranchTimesGeneratorHelper {

	public function linkToEdit($object, $params) {

		return link_to ( '<i class="fa-fw fa fa-pencil txt-color-orange" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'edit' ), $object, array (
				'class' => 'btn btn-xs btn-default btn-edit-td-action',
				'data-backdrop' => 'static',
				'data-toggle' => 'modal',
				'data-target' => '#remoteModal' ) );
	}

	public function linkToDetail($object, $params) {

		if (! $object->getId ())
			return link_to ( '<i class="fa-fw fa fa-eye txt-color-blue" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>', $this->getUrlForAction ( 'detail' ), $object, array (
					'class' => 'btn btn-xs btn-default disabled',
					'data-backdrop' => 'static',
					'data-toggle' => 'modal',
					'data-target' => '#remoteModal' ) );

		$url = url_for ( '@ps_feature_branch_times_detail?id=' . $object->getId () );

		return $link = '<a class="btn btn-xs btn-default" href="' . $url . '"><i class="fa-fw fa fa-eye txt-color-blue" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i></a>';
	}

	public function linkToFilterReset2() {

		$url = url_for ( '@ps_feature_branch_times_by_week');
		
		return $link = '<a class="btn btn-sm btn-default btn-filter-reset btn-psadmin" href="' . $url . '"><i class="fa-fw fa fa-refresh txt-color-blue" title="' . __ ( 'Reset', array (), 'sf_admin'  ) . '"></i>.'. __ ( 'Reset', array (), 'sf_admin'  ).'</a>';
	}
}
