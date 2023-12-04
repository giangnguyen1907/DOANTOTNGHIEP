<?php
require_once dirname ( __FILE__ ) . '/../lib/psHistoryMobileAppPayAmountsGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psHistoryMobileAppPayAmountsGeneratorHelper.class.php';

/**
 * psHistoryMobileAppPayAmounts actions.
 *
 * @package kidsschool.vn
 * @subpackage psHistoryMobileAppPayAmounts
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psHistoryMobileAppPayAmountsActions extends autoPsHistoryMobileAppPayAmountsActions {

	public function executeNew(sfWebRequest $request) {

		$user_id = $request->getParameter ( 'use_id' );

		if ($user_id) {
			$psHistoryMobileAppPayAmounts = new PsHistoryMobileAppPayAmounts ();

			$psHistoryMobileAppPayAmounts->setUserId ( $user_id );

			$this->form = $this->configuration->getForm ( $psHistoryMobileAppPayAmounts );

			$this->ps_history_mobile_app_pay_amounts = $this->form->getObject ();

			$this->helper = new psHistoryMobileAppPayAmountsGeneratorHelper ();

			return $this->renderPartial ( 'psHistoryMobileAppPayAmounts/formPaySuccess', array (
					'ps_history_mobile_app_pay_amounts' => $this->ps_history_mobile_app_pay_amounts,
					'form' => $this->form,
					'configuration' => $this->configuration,
					'helper' => $this->helper ) );
		} else {
			$this->form = $this->configuration->getForm ();
			$this->ps_history_mobile_app_pay_amounts = $this->form->getObject ();
		}
	}

	public function executeCreate(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();
		$this->ps_history_mobile_app_pay_amounts = $this->form->getObject ();

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'new' );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_history_mobile_app_pay_amounts = $this->getRoute ()
			->getObject ();

		// $old_user_id = $this->ps_history_mobile_app_pay_amounts->getUserId();

		$this->form = $this->configuration->getForm ( $this->ps_history_mobile_app_pay_amounts );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	// public function executeEdit(sfRequest $request)
	// {
	// $this->forward404();
	// }
	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );

		if ($form->isValid ()) {
			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {

				$money = 25000;

				// $expiration_date = $this->getExpiration($form->getValue('pay_created_at'), $form->getValue('amount'), $money);

				$amount = $money * $form->getValue ( 'month' );

				// $expiration_date = $this->getExpiration($form->getValue('pay_created_at'), $amount, $money);

				$ps_history_mobile_app_pay_amounts = $form->save ();

				// if($old_user_id != null){
				// $app_amount = Doctrine::getTable('PsMobileAppAmounts')->findOneBy('user_id', $old_user_id);
				// } else {
				$app_amount = Doctrine::getTable ( 'PsMobileAppAmounts' )->findOneBy ( 'user_id', $form->getObject ()
					->getUserId () );
				// }

				if ($app_amount) {

					$currentExpiration = $app_amount->getExpirationDateAt ();

					$expiration_date = date_add ( date_create ( $currentExpiration ), date_interval_create_from_date_string ( "1 days" ) );

					$expiration_date = $this->getExpiration ( date_format ( $expiration_date, "Y-m-d" ), $amount, $money );

					$app_amount->setUserId ( $form->getObject ()
						->getUserId () );

					$app_amount->setAmount ( $form->getObject ()
						->getAmount () );

					$app_amount->setExpirationDateAt ( $expiration_date );

					$app_amount->setDescription ( $form->getObject ()
						->getDescription () );

					$app_amount->save ();
				} else {

					$expiration_date = $this->getExpiration ( $pay_created_at, $amount, $money );

					$mobile_app_amount = new PsMobileAppAmounts ();

					$mobile_app_amount->setUserId ( $form->getObject ()
						->getUserId () );

					$mobile_app_amount->setAmount ( $form->getObject ()
						->getAmount () );

					$mobile_app_amount->setExpirationDateAt ( $expiration_date );

					$mobile_app_amount->setDescription ( $form->getObject ()
						->getDescription () );

					$mobile_app_amount->save ();
				}
			} catch ( Doctrine_Validator_Exception $e ) {

				$errorStack = $form->getObject ()
					->getErrorStack ();

				$message = get_class ( $form->getObject () ) . ' has ' . count ( $errorStack ) . " field" . (count ( $errorStack ) > 1 ? 's' : null) . " with validation errors: ";
				foreach ( $errorStack as $field => $errors ) {
					$message .= "$field (" . implode ( ", ", $errors ) . "), ";
				}
				$message = trim ( $message, ', ' );

				$this->getUser ()
					->setFlash ( 'error', $message );
				return sfView::SUCCESS;
			}

			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $ps_history_mobile_app_pay_amounts ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {
				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				$this->redirect ( '@ps_history_mobile_app_pay_amounts_new' );
			} else {
				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( '@ps_mobile_app_amounts' );
			}
		} else {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.' );
		}
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {

			$app_amount = Doctrine::getTable ( 'PsMobileAppAmounts' )->findOneBy ( 'user_id', $this->getRoute ()
				->getObject ()
				->getUserId () );

			$history_app_pay_amount = Doctrine::getTable ( 'PsHistoryMobileAppPayAmounts' )->getLastPayByUserId ( $this->getRoute ()
				->getObject ()
				->getUserId (), $this->getRoute ()
				->getObject ()
				->getId () );

			if ($history_app_pay_amount) {
				$app_amount->setAmount ( $history_app_pay_amount->getAmount () );

				$app_amount->setExpirationDateAt ( $this->getExpiration ( $history_app_pay_amount->getPayCreatedAt (), $history_app_pay_amount->getAmount () ) );

				$app_amount->save ();
			} else
				$app_amount->delete ();

			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_mobile_app_amounts' );
	}

	/**
	 * Tính ngày hết hạn dựa vào ngày nạp, số tiền và mức thu của trường
	 *
	 * @param string $pay_at
	 *        	ngày nạp
	 * @param int $amount
	 *        	số tiền
	 * @param int $money
	 *        	mức thu của nhà trường (tháng)
	 * @return datetime
	 */
	public function getExpiration($pay_at, $amount, $money = 25000) {

		$expiration_date = date_create ( $pay_at );

		date_time_set ( $expiration_date, 23, 59, 59 );

		$duration = floor ( $amount / $money );
		if ($duration > 0) {
			date_add ( $expiration_date, date_interval_create_from_date_string ( ($duration - 1) . ' months' ) );
		}

		return $expiration_date = date_format ( $expiration_date, 'Y-m-t H:i:s' );
	}

	/**
	 * Tính ngày hết hạn ajax
	 *
	 * @param sfRequest $request
	 * @return datetime
	 */
	public function executeExpirationAjax(sfRequest $request) {

		$amount = $request->getPostParameter ( 'amount' );

		$pay_created_at = $request->getPostParameter ( 'pay_created_at' );

		$money = 25000;

		$user_id = $request->getPostParameter ( 'user_id' );

		$app_amount = Doctrine::getTable ( 'PsMobileAppAmounts' )->findOneBy ( 'user_id', $user_id );

		if ($app_amount) {
			$currentExpiration = $app_amount->getExpirationDateAt ();

			$expiration_date = date_add ( date_create ( $currentExpiration ), date_interval_create_from_date_string ( "1 days" ) );

			$expiration_date = $this->getExpiration ( date_format ( $expiration_date, "Y-m-d" ), $amount, $money );
		} else {
			$expiration_date = $this->getExpiration ( $pay_created_at, $amount, $money );
		}
		// $expiration_date = date_create($request->getPostParameter('pay_created_at'));

		// date_time_set($expiration_date, 23, 59, 59);

		// $duration = floor($amount/$money) ;
		// if($duration > 0)
		// {
		// date_add($expiration_date, date_interval_create_from_date_string(($duration-1).' months'));
		// }

		echo $expiration_date = date_format ( date_create ( $expiration_date ), 't-m-Y H:i:s' );
		exit ( 0 );
	}

	/**
	 * Tính số tiền nạp dựa theo tháng
	 *
	 * @param sfRequest $request
	 * @return int
	 */
	public function executeAmountAjax(sfRequest $request) {

		$month = $request->getPostParameter ( 'month' );

		$money = 25000;

		$amount = $month * $money;

		echo $amount;

		exit ( 0 );
	}

	/**
	 * Tính số tháng sử dụng dựa trên số tiền
	 *
	 * @param sfRequest $request
	 * @return int
	 */
	public function executeMonthAjax(sfRequest $request) {

		$amount = $request->getPostParameter ( 'amount' );

		$money = 25000;

		$month = floor ( $amount / $money );

		echo $month;

		exit ( 0 );
	}

	public function executeBatchPay(sfRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		if (! $ids) {
			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.' );

			$this->redirect ( '@ps_mobile_app_amounts' );
		}

		$pay_created_at = $request->getParameter ( 'pay_created_at' );

		$month = ( int ) $request->getParameter ( 'month' );

		$money = 25000;

		$amount = $money * $month;

		foreach ( $ids as $id ) {
			$history_app_pay_amount = new PsHistoryMobileAppPayAmounts ();

			$history_app_pay_amount->setUserId ( $id );

			$history_app_pay_amount->setAmount ( $amount );

			$history_app_pay_amount->setPayCreatedAt ( $pay_created_at );

			$history_app_pay_amount->save ();

			$app_amount = Doctrine::getTable ( 'PsMobileAppAmounts' )->findOneBy ( 'user_id', $id );

			if ($app_amount) {

				$currentExpiration = $app_amount->getExpirationDateAt ();

				$expiration_date = date_add ( date_create ( $currentExpiration ), date_interval_create_from_date_string ( "1 days" ) );

				$expiration_date = $this->getExpiration ( date_format ( $expiration_date, "Y-m-d" ), $app_amount, $money );

				$app_amount->setUserId ( $id );

				$app_amount->setAmount ( $amount );

				$app_amount->setExpirationDateAt ( $expiration_date );

				// $app_amount->setDescription($form->getObject()->getDescription());

				$app_amount->save ();
			} else {

				$expiration_date = $this->getExpiration ( $pay_created_at, $amount, $money );

				$mobile_app_amount = new PsMobileAppAmounts ();

				$mobile_app_amount->setUserId ( $id );

				$mobile_app_amount->setAmount ( $amount );

				$mobile_app_amount->setExpirationDateAt ( $expiration_date );

				// $mobile_app_amount->setDescription($form->getObject()->getDescription());

				$mobile_app_amount->save ();
			}
		}

		$this->redirect ( '@ps_mobile_app_amounts' );
		// exit(0);
	}
}
