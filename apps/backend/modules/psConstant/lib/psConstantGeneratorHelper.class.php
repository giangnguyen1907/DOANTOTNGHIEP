<?php
/**
 * psConstant module helper.
 *
 * @package    quanlymamnon.vn
 * @subpackage psConstant
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psConstantGeneratorHelper extends BasePsConstantGeneratorHelper {

	public function linkToNewPrivate($object, $params) {

		// return '<li class="sf_admin_action_new"><a href="'.url_for('@ps_constant_option').'">' . 'AAA'.__($params['label']) . '</a></li>';
		return '<li class="sf_admin_action_new">' . link_to ( __ ( $params ['label'], array (), 'sf_admin' ), '@ps_constant_option_new' ) . '</li>';
	}

	public function linkToListOption($object, $params) {

		return '<li class="sf_admin_action_new">' . link_to ( __ ( $params ['label'], array (), 'sf_admin' ), '@ps_constant' ) . '</li>';
	}
}