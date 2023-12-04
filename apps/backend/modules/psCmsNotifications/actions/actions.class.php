<?php
require_once dirname ( __FILE__ ) . '/../lib/psCmsNotificationsGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psCmsNotificationsGeneratorHelper.class.php';

/**
 * psCmsNotifications actions.
 *
 * @package kidsschool.vn
 * @subpackage psCmsNotifications
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psCmsNotificationsActions extends autoPsCmsNotificationsActions {

	public function executeIndex(sfWebRequest $request) {
		// sorting
		if ($request->getParameter ( 'sort' ) && $this->isValidSortColumn ( $request->getParameter ( 'sort' ) )) {
			$this->setSort ( array (
					$request->getParameter ( 'sort' ),
					$request->getParameter ( 'sort_type' ) ) );
		}
		
		// pager
		if ($request->getParameter ( 'page' )) {
			$this->setPage ( $request->getParameter ( 'page' ) );
		}
		
		$this->pager = $this->getPager ();
		$this->sort = $this->getSort ();
	}
}
