<?php
require_once dirname ( __FILE__ ) . '/../lib/psConfigLatePaymentsGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psConfigLatePaymentsGeneratorHelper.class.php';

/**
 * psConfigLatePayments actions.
 *
 * @package kidsschool.vn
 * @subpackage psConfigLatePayments
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psConfigLatePaymentsActions extends autoPsConfigLatePaymentsActions {

	public function executeEdit(sfWebRequest $request) {

		$this->ps_config_late_payments = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_config_late_payments, 'PS_FEE_CONFIG_LATE_PAYMENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->form = $this->configuration->getForm ( $this->ps_config_late_payments );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->ps_config_late_payments = $this->getRoute ()
			->getObject ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->ps_config_late_payments, 'PS_FEE_CONFIG_LATE_PAYMENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		$this->form = $this->configuration->getForm ( $this->ps_config_late_payments );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->forward404Unless ( myUser::checkAccessObject ( $this->getRoute ()
			->getObject (), 'PS_FEE_CONFIG_LATE_PAYMENT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		if ($this->getRoute ()
			->getObject ()
			->delete ()) {
			$this->getUser ()
				->setFlash ( 'notice', 'The item was deleted successfully.' );
		}

		$this->redirect ( '@ps_config_late_payments' );
	}

	public function executeIndex(sfWebRequest $request) {

		// $this->filter_value = $this->getFilters ();

		// $this->filter_value ['school_year_id'] = (isset ( $this->filter_value ['school_year_id'] )) ? $this->filter_value ['school_year_id'] : '';

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

	public function executeSaveConfigLate(sfWebRequest $request) {

		$configlatepayment = $request->getParameter ( 'configlatepayment' );
		$psconfiglatepayment = $request->getParameter ( 'ps_config_late_payment' );

		// print_r($configlatepayment);
		// die;

		$ps_customer_id = $psconfiglatepayment ['ps_customer_id'];
		$ps_workplace_id = $psconfiglatepayment ['ps_workplace_id'];
		$ps_school_year_id = $psconfiglatepayment ['ps_school_year_id'];

		if (! myUser::credentialPsCustomers ( 'PS_FEE_CONFIG_LATE_PAYMENT_FILTER_SCHOOL' )) {
			$check_customer = myUser::getPscustomerID ();
			if ($check_customer != $ps_customer_id) {
				$this->forward404Unless ( $ps_customer_id, sprintf ( 'Object does not exist.' ) );
			}
		}

		$user_id = myUser::getUserId ();

		$conn = Doctrine_Manager::connection ();

		try {
			$conn->beginTransaction ();

			$index = 0;

			$error_date = 0;

			foreach ( $configlatepayment as $key => $configlate ) {

				$start_month = date ( 'd-m-Y', $key );

				$from_date = $configlate ['from_date'];

				$to_date = $configlate ['to_date'];

				$price = $configlate ['price'];

				// kiem tra ngay cau hinh co nam trong thang hay khong va ngay bat dau nho hon ngay ket thuc
				if (date ( 'Ym', strtotime ( $start_month ) ) == date ( 'Ym', strtotime ( $from_date ) ) && date ( 'Ym', strtotime ( $start_month ) ) == date ( 'Ym', strtotime ( $to_date ) ) && date ( 'Ymd', strtotime ( $from_date ) ) < date ( 'Ymd', strtotime ( $to_date ) )) {
					$index ++;
					// kiem tra xem thang nay da co du lieu chua
					$check_config_late = Doctrine_Core::getTable ( 'PsConfigLatePayment' )->updateConfigLatePayment ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id, $start_month );

					if (! $check_config_late) { // neu chua ton tai thi them moi

						$check_config_late = new PsConfigLatePayment ();

						$check_config_late->setPsCustomerId ( $ps_customer_id );
						$check_config_late->setPsWorkplaceId ( $ps_workplace_id );
						$check_config_late->setSchoolYearId ( $ps_school_year_id );

						$check_config_late->setFromDate ( date ( "Y-m-d", strtotime ( $from_date ) ) );
						$check_config_late->setToDate ( date ( "Y-m-d", strtotime ( $to_date ) ) );
						$check_config_late->setPrice ( $price );
						$check_config_late->setIsActivated ( 1 );
						$check_config_late->setUserCreatedId ( $user_id );
						$check_config_late->setUserUpdatedId ( $user_id );

						$check_config_late->save ();
					} else {

						// neu du lieu khong thay doi thi bo qua
						if (date ( "Y-m-d", strtotime ( $from_date ) ) != date ( "Y-m-d", strtotime ( $check_config_late->getFromDate () ) ) || date ( "Y-m-d", strtotime ( $to_date ) ) != date ( "Y-m-d", strtotime ( $check_config_late->getToDate () ) ) || $price != $check_config_late->getPrice ()) {

							$check_config_late->setFromDate ( date ( "Y-m-d", strtotime ( $from_date ) ) );
							$check_config_late->setToDate ( date ( "Y-m-d", strtotime ( $to_date ) ) );
							$check_config_late->setPrice ( $price );
							$check_config_late->setIsActivated ( 1 );
							$check_config_late->setUserUpdatedId ( $user_id );

							$check_config_late->save ();
						}
					}
				} else {
					// kiem tra xem thang nay da co du lieu chua
					$check_config_late = Doctrine_Core::getTable ( 'PsConfigLatePayment' )->updateConfigLatePayment ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id, $start_month );

					if (! $check_config_late) {

						$formdate = date ( "Y-m", strtotime ( $start_month ) ) . '-15';

						$todate = date ( "Y-m", strtotime ( $start_month ) ) . '-'.date ( "t", strtotime ( $start_month ) );

						$check_config_late = new PsConfigLatePayment ();

						$check_config_late->setPsCustomerId ( $ps_customer_id );
						$check_config_late->setPsWorkplaceId ( $ps_workplace_id );
						$check_config_late->setSchoolYearId ( $ps_school_year_id );

						$check_config_late->setFromDate ( date ( "Y-m-d", strtotime ( $formdate ) ) );
						$check_config_late->setToDate ( date ( "Y-m-d", strtotime ( $todate ) ) );
						$check_config_late->setPrice ( 0 );
						$check_config_late->setIsActivated ( 1 );
						$check_config_late->setUserCreatedId ( $user_id );
						$check_config_late->setUserUpdatedId ( $user_id );

						$check_config_late->save ();
					}
				}
			}

			$conn->commit ();
		} catch ( Exception $e ) {

			throw new Exception ( $e->getMessage () );

			$this->getUser ()
				->setFlash ( 'error', 'Config late payment was saved failed.' );

			$conn->rollback ();
		}

		$message = $this->getContext ()
			->getI18N ()
			->__ ( 'Config late payment was saved successfully. You can add another one below.' );

		$this->getUser ()
			->setFlash ( 'notice', $message );

		$this->redirect ( '@config_late_payments' );
	}

	public function executeConfigLate(sfWebRequest $request) {

		$user_id = myUser::getUserId ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$ps_school_year_id = null;

		$this->filter_list_student = array ();

		$delay_filter = $request->getParameter ( 'delay_filter' );

		if ($request->isMethod ( 'post' )) {

			// Handle the form submission
			$value_student_filter = $request->getParameter ( 'delay_filter' );

			$this->ps_customer_id = $value_student_filter ['ps_customer_id'];

			$this->ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$this->ps_school_year_id = $value_student_filter ['ps_school_year_id'];
		} else {

			$member_id = myUser::getUser ()->getMemberId ();

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->ps_workplace_id = myUser::getWorkPlaceId ( $member_id );

			$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}

		if ($this->ps_school_year_id == '') {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
		} else {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'id', $this->ps_school_year_id );
		}

		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );

		$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );

		$this->ps_month = PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd );

		$list_config = Doctrine::getTable ( 'PsConfigLatePayment' )->getListConfigLatePayment ( $this->ps_school_year_id, $this->ps_customer_id, $this->ps_workplace_id );

		if ($list_config) {
			$this->list_config = $list_config;
		} else {
			$this->list_config = array ();
		}

		if ($delay_filter) {

			$this->ps_workplace_id = isset ( $delay_filter ['ps_workplace_id'] ) ? $delay_filter ['ps_workplace_id'] : 0;

			$this->ps_school_year_id = isset ( $delay_filter ['ps_school_year_id'] ) ? $delay_filter ['ps_school_year_id'] : 0;

			if ($this->ps_workplace_id > 0) {

				$this->forward404Unless ( $this->ps_workplace_id, sprintf ( 'Object does not exist.' ) );

				$ps_workplace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlaceActivatedById ( $this->ps_workplace_id );

				$this->forward404Unless ( myUser::checkAccessObject ( $ps_workplace, 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

				$this->ps_customer_id = $ps_workplace->getPsCustomerId ();
			}
		}

		if (! myUser::credentialPsCustomers ( 'PS_STUDENT_ATTENDANCE_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => true ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),
					'add_empty' => _ ( '-All school-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => true,
					'data-placeholder' => _ ( '-All school-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsCustomer',
					'required' => true ) ) );
		}
		if ($this->ps_customer_id == '') {
			$this->ps_customer_id = myUser::getPscustomerID ();
			$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
			$member_id = myUser::getUser ()->getMemberId ();
			$this->ps_workplace_id = myUser::getWorkPlaceId ( $member_id );
		}

		if ($this->ps_school_year_id == '') {
			$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()
				->fetchOne ()
				->getId ();
		}

		if ($this->date_at == '') {
			$this->date_at = date ( 'd-m-Y' );
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

		if ($this->ps_customer_id > 0) {

			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'query' => Doctrine::getTable ( 'PsWorkPlaces' )->setSQLByCustomerId ( 'id, title', $this->ps_customer_id, PreSchool::ACTIVE ),
					'add_empty' => _ ( '-Select workplace-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;width:100%;",
					'required' => true,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'PsWorkPlaces',
					'required' => true ) ) );
		} else {
			$this->formFilter->setWidget ( 'ps_workplace_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select workplace-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => true,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );
		}

		$this->formFilter->setWidget ( 'ps_school_year_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'query' => Doctrine::getTable ( 'PsSchoolYear' )->setSqlPsSchoolYears (),
				'add_empty' => false ), array (
				'class' => 'select2',
				'style' => "width:100%;min-width:150px;",
				'data-placeholder' => _ ( '-Select school year-' ),
				'required' => true ) ) );

		$this->formFilter->setValidator ( 'ps_school_year_id', new sfValidatorDoctrineChoice ( array (
				'model' => 'PsSchoolYear',
				'column' => 'id',
				'required' => true ) ) );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );
		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'delay_filter[%s]' );
	}
}
