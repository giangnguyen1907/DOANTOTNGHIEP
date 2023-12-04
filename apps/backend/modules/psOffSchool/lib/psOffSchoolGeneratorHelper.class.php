<?php

/**
 * psOffSchool module helper.
 *
 * @package    kidsschool.vn
 * @subpackage psOffSchool
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psOffSchoolGeneratorHelper extends BasePsOffSchoolGeneratorHelper {

	public function linkToSave($object, $params) {

		$label = '<i class="fa-fw fa fa-floppy-o" aria-hidden="true" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i> ';

		return '<button type="submit" class="btn btn-default btn-success btn-sm btn-psadmin btn-submit-check">' . $label . __ ( $params ['label'], array (), 'sf_admin' ) . '</button>';
	}

	public function linkToSaveAndAdd($object, $params) {

		$label = '<i class="fa-fw fa fa-cloud-upload" aria-hidden="true" title="' . __ ( $params ['label'], array (), 'sf_admin' ) . '"></i> ';

		return '<button type="submit" value="' . __ ( $params ['label'], array (), 'sf_admin' ) . '" name="_save_and_add" class="btn btn-default btn-success btn-sm btn-psadmin btn-submit-check">' . $label . __ ( $params ['label'], array (), 'sf_admin' ) . '</button>';
	}
}
