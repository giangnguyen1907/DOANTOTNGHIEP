<?php

/**
 * psSubjectSplits module helper.
 *
 * @package    kidsschool.vn
 * @subpackage psSubjectSplits
 * @author     kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psSubjectSplitsGeneratorHelper extends BasePsSubjectSplitsGeneratorHelper {

	public function getUrlForAction($action) {

		return 'list' == $action ? 'ps_subjects' : 'ps_subject_splits_' . $action;
	}
}
