<?php
require_once dirname ( __FILE__ ) . '/../lib/psReceivableMonthGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psReceivableMonthGeneratorHelper.class.php';
/**
 * psReceivableMonth actions.
 *
 * @package kidsschool.vn
 * @subpackage psReceivableMonth
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psReceivableMonthActions extends autoPsReceivableMonthActions {

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$object = $this->getRoute ()
			->getObject ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $object ) ) );

		$this->forward404Unless ( myUser::checkAccessObject ( $object->getReceivable (), 'PS_FEE_REPORT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist .' ) );

		if ($object->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->getUser ()
			->setFlash ( 'notice', 'The item was deleted successfully.' );

		$this->redirect ( '@ps_receivable_month' );
	}

	// Xoa khoan phai thu da gan cho thang
	public function executePutDelete(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$request->checkCSRFProtection ();

			$receivable_month_id = $request->getParameter ( 'item_id' );

			$list_receivable_temp_receivable_at = array ();

			if ($receivable_month_id <= 0) {
				$this->getUser ()
					->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', true );
			} else {

				$receivableTemp = Doctrine::getTable ( "ReceivableTemp" )->findOneById ( $receivable_month_id );

				if (! $receivableTemp || ($receivableTemp && ! myUser::checkAccessObject ( $receivableTemp->getReceivable (), 'PS_FEE_REPORT_FILTER_SCHOOL' ))) {
					$this->getUser ()
						->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', true );
				} else {
					if ($receivableTemp->delete ()) {
						$this->getUser ()
							->setFlash ( 'notice', 'Receivable in the month was delete successfully.', false );
					} else {
						$this->getUser ()
							->setFlash ( 'error', 'Delete fee receivable in the month occurred error.', false );
					}
				}

				$ps_fee_reports_filters = $request->getParameter ( 'ps_fee_reports_filters' );

				$params = array ();
				$params ['ps_customer_id'] = $ps_fee_reports_filters ['ps_customer_id'];
				$params ['ps_workplace_id'] = $ps_fee_reports_filters ['ps_workplace_id'];
				$params ['ps_school_year_id'] = $ps_fee_reports_filters ['ps_school_year_id'];

				$params ['date'] = strtotime ( $ps_fee_reports_filters ['receivable_at'] );
				$params ['ps_myclass_id'] = $ps_fee_reports_filters ['ps_class_id'];

				$list_receivable_temp_receivable_at = Doctrine::getTable ( "Receivable" )->getListReceivableTempByParams ( $params );
			}

			return $this->renderPartial ( 'psReceivableMonth/list_receivable_month', array (
					'list_receivable_temp_receivable_at' => $list_receivable_temp_receivable_at ) );
		}

		exit ( 0 );
	}

	// Luu khoan phai cua thang cho lop
	public function executePutSave(sfWebRequest $request) {

		if ($request->isXmlHttpRequest ()) {

			$receivable_month = $request->getParameter ( 'receivable_month' );

			$_params = $request->getParameter ( 'params' );

			if (isset ( $_params ['ps_myclass_id'] ) && $_params ['ps_myclass_id'] > 0) {

				$params = array ();
				$params ['ps_customer_id'] = $_params ['ps_customer_id'];
				$params ['ps_workplace_id'] = $_params ['ps_workplace_id'];
				$params ['ps_school_year_id'] = $_params ['ps_school_year_id'];
				$params ['date'] = $_params ['receivable_at']; // int time
				$params ['ps_myclass_id'] = $_params ['ps_myclass_id'];
				$params ['is_activated'] = PreSchool::ACTIVE;

				// Kiem tra quyen
				$my_class = Doctrine::getTable ( 'MyClass' )->findOneById ( $params ['ps_myclass_id'] );

				if (! myUser::checkAccessObject ( $my_class, 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
					$this->getUser ()
						->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
					$list_receivable_temp_receivable_at = array ();
				} else {

					// Luu vao ReceivableTemp
					$number_new_item = 0;

					$conn = Doctrine_Manager::connection ();

					try {

						$conn->beginTransaction ();

						foreach ( $receivable_month as $obj ) {
							if (isset ( $obj ['ids'] ) && $obj ['ids'] > 0) {

								$receivable_at = date ( "Y-m-d", $_params ['receivable_at'] );

								// Kiem tra lai table Receivable truoc khi chen
								$receivable = Doctrine::getTable ( "Receivable" )->findOneById ( $obj ['ids'] );

								// Validate
								if ($receivable && is_numeric ( $obj ['amount'] ) && mb_strlen ( $obj ['note'] ) <= 255) {
									$receivable_temp = new ReceivableTemp ();
									$receivable_temp->setReceivableId ( $obj ['ids'] );
									$receivable_temp->setAmount ( $obj ['amount'] );
									$receivable_temp->setNote ( $obj ['note'] );
									$receivable_temp->setReceivableAt ( date ( "Y-m-d", $_params ['receivable_at'] ) );
									$receivable_temp->setPsMyclassId ( $_params ['ps_myclass_id'] );
									$receivable_temp->setUserCreatedId ( sfContext::getInstance ()->getUser ()
										->getGuardUser ()
										->getId () );
									$receivable_temp->setUserUpdatedId ( sfContext::getInstance ()->getUser ()
										->getGuardUser ()
										->getId () );

									$receivable_temp->save ();

									if ($receivable_temp->getId () > 0)
										$number_new_item ++;
								}
							}
						}

						$conn->commit ();
					} catch ( Exception $e ) {
						$conn->rollback ();
					}

					if ($number_new_item > 0)
						$this->getUser ()
							->setFlash ( 'notice', $this->getContext ()
							->getI18N ()
							->__ ( '%value% The receivable for month was updated successfully.', array (
								'%value%' => $number_new_item ), 'sf_admin' ), false );
					else
						$this->getUser ()
							->setFlash ( 'error', 'Updated the receivable for month failed.', false );

					$list_receivable_temp_receivable_at = Doctrine::getTable ( "Receivable" )->getListReceivableTempByParams ( $params );
				}

				return $this->renderPartial ( 'psReceivableMonth/list_receivable_month', array (
						'list_receivable_temp_receivable_at' => $list_receivable_temp_receivable_at ) );
			}
		} else {
			exit ( 0 );
		}
	}
}
