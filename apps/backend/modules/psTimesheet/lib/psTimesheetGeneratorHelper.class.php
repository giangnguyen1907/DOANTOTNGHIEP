<?php

/**
 * psTimesheet module helper.
 *
 * @package    kidsschool.vn
 * @subpackage psTimesheet
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psTimesheetGeneratorHelper extends BasePsTimesheetGeneratorHelper {

	public function linkToList($params) {

		$label = '<i class="fa-fw fa fa-list-ul" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i> ';

		$url = url_for ( 'ps_timesheet_review' );
		return $link = '<a class="btn btn-default btn-success bg-color-green btn-sm btn-psadmin pull-left" href="' . $url . '"><i class="fa-fw fa fa-list-ul" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i>' . __ ( $params ['label'], array (), 'sf_admin' ) . '</a>';
	}
}
