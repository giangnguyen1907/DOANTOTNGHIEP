<?php
require_once dirname ( __FILE__ ) . '/../lib/psMobileAppAmountsGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psMobileAppAmountsGeneratorHelper.class.php';

/**
 * psMobileAppAmounts actions.
 *
 * @package kidsschool.vn
 * @subpackage psMobileAppAmounts
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psMobileAppAmountsActions extends autoPsMobileAppAmountsActions {

	public function executeHistory(sfRequest $request) {

		$this->filter_value = $this->getFilters ();

		$user_id = $request->getParameter ( 'user_id' );
		if ($user_id <= 0) {

			$this->forward404Unless ( $user_id, sprintf ( 'Object does not exist.' ) );
		}
		// $this->appAmount = Doctrine::getTable('PsMobileAppAmounts')->findOneBy('user_id', $user_id);
		// $this->histories = Doctrine::getTable('PsHistoryMobileAppPayAmounts')->getHistoryPayByUserId($user_id);
		$this->app_amount = Doctrine::getTable ( 'PsMobileAppAmounts' )->findOneBy ( 'user_id', $user_id );
		$this->pager = new sfDoctrinePager ( 'PsHistoryMobileAppPayAmounts', 10 );

		$this->pager->setQuery ( Doctrine::getTable ( 'PsHistoryMobileAppPayAmounts' )->getHistoryPayByUserId ( $user_id ) );

		$this->pager->setPage ( $request->getParameter ( 'page', 1 ) );

		$this->pager->init ();

		$this->list_history = $this->pager->getResults ();

		return $this->renderPartial ( 'psMobileAppAmounts/historySuccess', array (
				'list_history' => $this->list_history,
				'pager' => $this->pager,
				'app_amount' => $this->app_amount ) );
	}

	public function executeListHistory(sfRequest $request) {

		if ($request->isXmlHttpRequest ()) {
			$user_id = $request->getParameter ( 'user_id' );

			if ($user_id < 0) {
				exit ( 0 );
			}
			$this->app_amount = Doctrine::getTable ( 'PsMobileAppAmounts' )->findOneBy ( 'user_id', $user_id );

			$this->pager = new sfDoctrinePager ( 'PsHistoryMobileAppPayAmounts', 2 );

			$this->pager->setQuery ( Doctrine::getTable ( 'PsHistoryMobileAppPayAmounts' )->getHistoryPayByUserId ( $user_id ) );

			$this->pager->setPage ( $request->getParameter ( 'page', 1 ) );

			$this->pager->init ();

			$this->list_history = $this->pager->getResults ();

			return $this->renderPartial ( 'psMobileAppAmounts/list_history', array (
					'list_history' => $this->list_history,
					'pager' => $this->pager,
					'app_amount' => $this->app_amount ) );
		} else {
			exit ( 0 );
		}
	}
}
