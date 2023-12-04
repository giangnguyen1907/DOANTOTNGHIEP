<?php

/**
 * psConstantOption module helper.
 *
 * @package    quanlymamnon.vn
 * @subpackage psConstantOption
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psConstantOptionGeneratorHelper extends BasePsConstantOptionGeneratorHelper {

	public function getUrlForAction($action) {

		return 'list' == $action ? 'ps_constant_option' : 'ps_constant_option_' . $action;
	}
}
