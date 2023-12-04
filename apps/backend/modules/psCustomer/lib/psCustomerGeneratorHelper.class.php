<?php

/**
 * psCustomer module helper.
 *
 * @package    quanlymamnon.vn
 * @subpackage psCustomer
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psCustomerGeneratorHelper extends BasePsCustomerGeneratorHelper {

	public function linkToLock($object, $params) {

		return '<li class="sf_admin_action_lock">' . link_to ( __ ( image_tag ( sfConfig::get ( 'sf_admin_module_web_dir' ) . '/images/active.png', array (
				'alt' => __ ( 'Activated', array (), 'sf_admin' ),
				'title' => __ ( 'UnActivated', array (), 'sf_admin' ) ) ), array (), 'sf_admin' ), $this->getUrlForAction ( 'lock' ), $object ) . '</li>';
	}
}
