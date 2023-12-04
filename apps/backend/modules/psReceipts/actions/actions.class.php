<?php
require_once dirname ( __FILE__ ) . '/../lib/psReceiptsGeneratorConfiguration.class.php';
require_once dirname ( __FILE__ ) . '/../lib/psReceiptsGeneratorHelper.class.php';

/**
 * psReceipts actions.
 *
 * @package kidsschool.vn
 * @subpackage psReceipts
 * @author kidsschool.vn <contact@kidsschool.vn - ntsc279@gmail.com>
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class psReceiptsActions extends autoPsReceiptsActions {

	public function executeNew(sfWebRequest $request) {

		$this->forward404Unless ( false, sprintf ( 'Object does not exist.' ) );
	}

	public function executeEdit(sfWebRequest $request) {

		$this->receipt = $this->getRoute ()
			->getObject ();

		if (! myUser::isAdministrator () && $this->receipt->getPaymentStatus () == PreSchool::ACTIVE) {
			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'This month has been paid. You can not edit.' ) );
			$this->redirect ( '@ps_receipts' );
		}

		$this->student = $this->receipt->getStudent ();

		$student_id = $this->student->getId ();

		$this->receivable_at = $this->receipt->getReceiptDate ();

		$int_receivable_at = PsDateTime::psDatetoTime ( $this->receivable_at );

		if ($this->receipt->getPaymentStatus () != PreSchool::ACTIVE) {

			$receiptOfStudentNextMonth = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentNextMonth ( $student_id, $int_receivable_at );

			if ($receiptOfStudentNextMonth)
				$this->receipt = $receiptOfStudentNextMonth;
		}

		// Thiet láº­p lai cac gia tri theo $this->receipt
		$int_receivable_at = PsDateTime::psDatetoTime ( $this->receipt->getReceiptDate () );

		// Lay lop hoc cua hoc sinh tai thoi diem bao phi
		$infoClass = $this->student->getMyClassByStudent ( $this->receipt->getReceiptDate () );

		if (! $infoClass) {
			// Lay lop hoc cua hoc sinh Ä‘ang hoáº¡t Ä‘á»™ng
			$infoClass = $this->student->getCurrentClassOfStudent ();
		}

		// Lay thong tin co so dao tao
		$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $infoClass->getPsWorkplaceId () );

		// Láº¥y phiáº¿u thu gáº§n Ä‘Ă¢y nháº¥t
		$student_month = $this->student->getPrecedingMontOfStudent ( $int_receivable_at, $psWorkPlace->getConfigStartDateSystemFee () );

		// Ngay cua phieu thu cáº­n receivable_at nhat
		$receiptPrevDate = $student_month ['receiptPrevDate'];

		// Lay danh sach cac khoan cua phi
		$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );

		$this->receiptPrevDate = $receiptPrevDate;

		$this->form = $this->configuration->getForm ( $this->receipt );
	}

	public function executeUpdate(sfWebRequest $request) {

		$this->receipt = $this->getRoute ()
			->getObject ();

		if ($this->receipt->getPaymentStatus () == PreSchool::ACTIVE) {
			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'This month has been paid. You can not edit.' ) );
			$this->redirect ( '@ps_receipts' );
		}

		$this->student = $this->receipt->getStudent ();

		$this->receivable_at = $this->receipt->getReceiptDate ();

		$this->psConfigLatePayment = null;

		$this->form = $this->configuration->getForm ( $this->receipt );

		$this->processForm ( $request, $this->form );

		$this->setTemplate ( 'edit' );
	}

	// Xuat phieu bao - phieu thu
	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();

		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()
					->getObject () ) ) );

		$ps_receipts = $this->getRoute ()
			->getObject ();
		
		$ps_student = $ps_receipts->getStudent ();

		$this->forward404Unless ( myUser::checkAccessObject ( $ps_student, 'PS_FEE_REPORT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );

		// Tim phieu thu(phieu bao) liá»�n sau nháº¥t - Náº¿u Ä‘Ă£ tá»“n táº¡i thĂ¬ khĂ´ng cho xĂ³a
		$receiptOfStudentNextMonth = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentNextMonth ( $ps_student->getId (), strtotime ( $ps_receipts->getReceiptDate () ) );

		if ($receiptOfStudentNextMonth) {
			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'There are tuition fees of month' ) . ' ' . date ( "m-Y", strtotime ( $receiptOfStudentNextMonth->getReceiptDate () ) ) . ' ' . $this->getContext ()
				->getI18N ()
				->__ ( 'You can not delete.' ) );
			$this->redirect ( '@ps_receipts' );
		}

		// Kiem tra neu phieu thu nay da thanh toan hoáº·c Ä‘Ă£ bĂ¡o cho phá»¥ huynh thi khong cho xoa
		if (!myUser::isAdministrator() && $ps_receipts->getPaymentStatus () == PreSchool::ACTIVE) {
			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'This month has been paid. You can not delete.' ) );
			$this->redirect ( '@ps_receipts' );
		}

		/*
		 * if ($ps_receipts->getIsPublic () == PreSchool::ACTIVE) { $this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ('Data card has been shown to parents. You can not delete.') ); $this->redirect ( '@ps_receipts' ); }
		 */

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			$receiptPrevDate = null;

			$int_receivable_at = PsDateTime::psDatetoTime ( $ps_receipts->getReceiptDate () );

			$notice = $this->getContext ()->getI18N ()->__ ( 'Delete the tuition fee notice %value% successfully.', array ('%value%' => $this->getContext ()->getI18N ()->__ ( 'month' ) . ' ' . date("m-Y",$int_receivable_at ) . ' ' . $this->getContext ()->getI18N ()->__ ( 'of student' ) . ' ' . $ps_student->getFirstName () . ' ' . $ps_student->getLastName () ) );

			// TĂ¬m phiáº¿u thu (chÆ°a thanh toĂ¡n hoáº·c Ä‘Ă£ thanh toĂ¡n) gáº§n phiáº¿u bĂ¡o Ä‘Æ°á»£c chá»�n xĂ³a nháº¥t
			$receipt_prev = Doctrine::getTable ( 'Receipt' )->findPrevOfStudentByDate ( $ps_student->getId (), $int_receivable_at );

			// Ngay cua phieu thu gan nhat
			$receiptPrevDate = $receipt_prev ? $receipt_prev->getReceiptDate () : null;
			
			//echo "AAAAAA".$ps_receipts->getReceiptDate();die;
			
			// Lay danh sach cac khoan phi cua phieu bao
			// $receivable_students = Doctrine::getTable ( 'ReceivableStudent' )->getObjectReceivableStudentOfMonth ( $ps_student->getId (), $ps_receipts->getReceiptDate (), $receiptPrevDate );

			// Lay cac khoan du kien thu trong thang cua phieu bao
			$receivable_students = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentInMonth ( $ps_student->getId (), $ps_receipts->getReceiptDate () );
			
			// lay phieu bao
			$ps_fee_reports = $ps_student->findPsFeeReportOfStudentByDate ( $int_receivable_at );
			
			/*
			 * foreach ( $receivable_students as $receivable_student ) { echo $receivable_student->getStudentId () . '-' . $receivable_student->getReceivableAt () . '</br/>'; } die ();
			 */
			// Xoa Phieu bao - Phieu thu

			if ($ps_receipts->delete () && $ps_fee_reports->delete ()) {

				$this->saveHistoryReceipt ( $ps_receipts, $ps_fee_reports, $receivable_students, 'delete' );

				// Xoa du lieu trong ReceivableStudent
				foreach ( $receivable_students as $receivable_student ) {
					$receivable_student->delete ();
				}
				
				// Xóa cả diễn giải giảm trừ
				Doctrine_Query::create()->delete('PsStudentServiceReduce')->addWhere('student_id=?',$ps_student->getId())->andWhere('date_format(receivable_at,"%Y%m")=?',date('Ym',strtotime($ps_receipts->getReceiptDate())))->execute();
				
				// Xóa trong lịch sử công nợ
				Doctrine_Query::create()->delete('PsNhatKyCongNo')->addWhere('idhocsinh=?',$ps_student->getId())->andWhere('date_format(thoigian,"%Y%m")=?',date('Ym',strtotime($ps_receipts->getReceiptDate())))->execute();
				
			}

			// XĂ³a trong ps_fee_reports_flag_my_class ? KhĂ´ng cáº§n. Khi chá»�n lá»›p Ä‘á»ƒ cháº¡y bĂ¡o phĂ­ váº«n cho hiá»ƒn thá»‹ cĂ¡c lá»›p Ä‘Ă£ tá»«ng cháº¡y

			$this->getUser ()->setFlash ( 'notice', $notice );

			$conn->commit ();
		} catch ( Exception $e ) {

			$conn->rollback ();

			$this->getUser ()->setFlash ( 'error', 'The item was deleted fail.' );
		}

		$this->redirect ( '@ps_receipts' );
	}

	/**
	 * Load lấy số tiền nộp chậm Thanh PV
	 */
	public function executeLoadAmount(sfWebRequest $request) {

		$receipt_no = $request->getParameter ( 'receipt_no' );

		$chietkhau = $request->getParameter ( 'chietkhau' );

		$tracked_at = $request->getParameter ( 'date_at' );

		$replace_date = str_replace ( '/', '-', $tracked_at );

		$date_at = date ( 'Y-m-d', strtotime ( $replace_date ) ); // chuyá»ƒn Ä‘á»‹nh dáº¡ng ngĂ y thĂ¡ng

		$receipt = Doctrine::getTable ( 'Receipt' )->findOneByReceiptNo ( $receipt_no );

		$student_id = $receipt->getStudentId ();

		$int_receivable_at = PsDateTime::psDatetoTime ( $date_at );

		$priceLatePayment = $total_price = $config_amount = 0;

		// Check role
		$ps_student = Doctrine_Core::getTable ( 'Student' )->findOneById ( $student_id );

		if (! myUser::checkAccessObject ( $ps_student, 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			echo $this->getContext ()
				->getI18N ()
				->__ ( 'Not roll data' );

			exit ( 0 );
		} else {

			// Láº¥y lá»›p cá»§a há»�c sinh
			$student_info = Doctrine::getTable ( 'StudentClass' )->getClassByDate ( $student_id, time () );

			if ($student_info) {
				// id cÆ¡ cá»Ÿ
				$ps_workplace_id = $student_info->getPsWorkplaceId ();
				$psConfigLatePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplaceDate ( $ps_workplace_id, $date_at );
				if ($psConfigLatePayment) {
					$config_amount = $psConfigLatePayment->getPrice ();
				}
				// Lay thong tin co so dao tao
				$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $ps_workplace_id );

				if ($psWorkPlace->getConfigChooseChargePaylate () == 1) {
					// láº¥y ra thĂ¡ng Ä‘Ă£ thanh toĂ¡n gáº§n nháº¥t so vá»›i phiáº¿u thu hiá»‡n táº¡i
					$check_receipt_date = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentIsPayment ( $student_id );

					if ($check_receipt_date) { // TĂ­nh khoáº£ng cĂ¡ch giá»¯a 2 thĂ¡ng

						$receipt_date = $check_receipt_date->getReceiptDate ();

						$datetime1 = date_create ( $receipt_date );
						$datetime2 = date_create ( $date_at );
						$interval = date_diff ( $datetime1, $datetime2 );

						$check_month = $interval->format ( '%m' );
					} else { // Náº¿u chÆ°a tá»«ng thanh toĂ¡n láº§n nĂ o, thĂ¬ Ä‘áº¿m xem cĂ³ bao nhiĂªu thĂ¡ng trÆ°á»›c Ä‘Ă³ chÆ°a thanh toĂ¡n

						$check_receipt_date = Doctrine::getTable ( 'Receipt' )->countReceiptOfStudentIsPayment ( $student_id, $int_receivable_at );

						$check_month = count ( $check_receipt_date ) + 1;
					}

					if ($check_month > 1) {

						for($i = 1; $i < $check_month; $i ++) {

							$track_at = date_create ( $date_at );

							date_modify ( $track_at, "-$i month" );

							$date_receipt = date_format ( $track_at, "Y-m-d" );

							$latePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplacePrevMonth ( $ps_workplace_id, $date_receipt );
							if ($latePayment) {
								$total_price += $latePayment->getPrice (); // TĂ­nh tá»•ng khoáº£n pháº¡t ná»™p muá»™n há»�c phĂ­
							}
						}
					}
				}

				// Lay tong so tien du kien cua 1 thang receivable_at(thĂ¡ng Ä‘ang xem) - Dá»± kiáº¿n cĂ¡c khoáº£n thu cá»§a thĂ¡ng receivable_at
				// $totalAmountReceivableAt = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentInMonth ( $student_id, $int_receivable_at );

				$priceLatePayment = $total_price + $config_amount;
				// Lay phieu bao cua thĂ¡ng Ä‘ang chá»�n

				$ps_fee_reports = Doctrine::getTable ( 'PsFeeReports' )->findPsFeeReportOfStudentByDate ( $student_id, strtotime ( $date_at ) );
				if ($ps_fee_reports) {
					return $this->renderPartial ( 'psReceipts/fees/_load_amount', array (
						'ps_fee_reports' => $ps_fee_reports,
						'priceLatePayment' => $priceLatePayment,
						'chietkhau' => $chietkhau ) );
				}
			}
		}
	}

	/**
	 * Chi tiet phieu thu 1.
	 * Náº¿u phiáº¿u thu Ä‘Ă£ thanh toĂ¡n thĂ¬ hiá»ƒn thá»‹ bĂ¬nh thÆ°á»�ng 2. Náº¿u phiáº¿u thu chÆ°a thanh toĂ¡n: BÆ°á»›c 1: TĂ¬m phiáº¿u thu Ä‘Ă£ thanh toĂ¡n lá»›n hÆ¡n gáº§n nháº¥t, náº¿u cĂ³ thĂ¬ hiá»ƒn thá»‹ theo phiáº¿u nĂ y, trĂ¡i láº¡i sang bÆ°á»›c 2 BÆ°á»›c 2: TĂ¬m phiáº¿u thu chÆ°a thanh toĂ¡n lá»›n nháº¥t(thĂ¡ng lá»›n nháº¥t)
	 */
	public function executeShowOLD(sfWebRequest $request) {

		$this->receipt = $this->getRoute ()
			->getObject ();

		if (! $this->receipt) {
			// $this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			$this->getUser ()
				->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
			$this->redirect ( '@ps_receipts' );
		}

		if (! myUser::checkAccessObject ( $this->receipt->getStudent (), 'PS_FEE_REPORT_FILTER_SCHOOL' ) || $this->receipt->getStudent ()
			->getDeletedAt ()) {

			$this->getUser ()
				->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );

			$this->redirect ( '@ps_receipts' );
		} else {

			// TĂ¬m phiáº¿u thu (chÆ°a thanh toĂ¡n hoáº·c Ä‘Ă£ thanh toĂ¡n) gáº§n phiáº¿u bĂ¡o Ä‘Æ°á»£c chá»�n xĂ³a nháº¥t
			$int_receivable_at = PsDateTime::psDatetoTime ( $this->receipt->getReceiptDate () );

			// TEST - Tinh so ngay nghi
			// $ps_logtimes = Doctrine::getTable ( 'PsLogtimes' )->getSumAbsentOfStudent($this->receipt->getStudent ()->getId (), $this->receipt->getReceiptDate());

			// echo 'getNumberAbsent:'.$ps_logtimes;

			$receipt_prev = Doctrine::getTable ( 'Receipt' )->findPrevOfStudentByDate ( $this->receipt->getStudent ()
				->getId (), $int_receivable_at );

			// Ngay cua phieu thu gan nhat
			$receiptPrevDate = $receipt_prev ? $receipt_prev->getReceiptDate () : null;

			// Lay danh sach cac khoan phi cua phieu bao
			/*
			 * $receivable_students = Doctrine::getTable ( 'ReceivableStudent' )->getObjectReceivableStudentOfMonth ( $this->receipt->getStudent ()->getId (), $this->receipt->getReceiptDate (), $receiptPrevDate ); if (myUser::isAdministrator()) { foreach ($receivable_students AS $receivable_student) { echo $receivable_student->getReceivableAt().'<br/>'; } }
			 */

			// Phiáº¿u bĂ¡o thĂ¡ng liá»�n sau nháº¥t
			$this->receiptOfStudentNextMonth = null;

			$this->student = $this->receipt->getStudent ();

			// Lay danh sach nguoi than cua hoc sinh
			$this->relatives = $this->student->getRelativesOfStudent ();

			$student_id = $this->student->getId ();

			// ThĂ¡ng thu phĂ­
			$this->receivable_at = $this->receipt->getReceiptDate ();
			$int_receivable_at = PsDateTime::psDatetoTime ( $this->receivable_at );

			// echo $int_receivable_at;die;

			// Láº¥y lá»›p cá»§a há»�c sinh
			$student_info = $this->student->getClassByDate ( time () );

			$this->psConfigLatePayment = null;
			$this->receiptOfStudentNextMonth = null;

			$this->totalAmount = 0;

			$this->pricePaymentLate = 0;

			if ($student_info) {

				// id cÆ¡ cá»Ÿ
				$ps_workplace_id = $student_info->getPsWorkplaceId ();
				$this->psConfigLatePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplaceDate ( $ps_workplace_id, date ( 'Y-m-d' ) );
			}

			// Tien thua tu phieu thu gan nhat chuyen sang
			$this->balance_last_month_amount = 0;

			if ($this->receipt->getPaymentStatus () == PreSchool::ACTIVE) { // Náº¿u Ä‘Ă£ thanh toĂ¡n

				// Lay lop hoc cua hoc sinh tai thoi diem bao phi
				$infoClass = $this->student->getMyClassByStudent ( $this->receivable_at );

				if (! $infoClass) {
					// Lay lop hoc cua hoc sinh Ä‘ang hoáº¡t Ä‘á»™ng
					$infoClass = $this->student->getCurrentClassOfStudent ();
				}

				// Lay thong tin co so dao tao
				$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $infoClass->getPsWorkplaceId () );

				// Lay phieu thu gáº§n Ä‘Ă¢y nháº¥t
				$student_month = $this->student->getPrecedingMontOfStudent ( $int_receivable_at, $psWorkPlace->getConfigStartDateSystemFee () );

				// Ngay cua phieu thu cáº­n receivable_at nhat
				$receiptPrevDate = $student_month ['receiptPrevDate'];

				// $this->balanceAmount = $this->receipt->getBalanceAmount();// DÆ° cá»§a phiáº¿u theo thĂ¡ng Ä‘ang chá»�n xem

				// $this->collectedAmount = $this->receipt->getCollectedAmount();// Sá»‘ tiá»�n Ä‘Ă£ ná»™p

				// DÆ° cá»§a phiáº¿u thu gáº§n Ä‘Ă¢y nháº¥t
				$this->balanceAmount = $student_month ['BalanceAmount'];

				// Ä�Ă£ ná»™p cá»§a phiáº¿u thu gáº§n Ä‘Ă¢y nháº¥t
				$this->collectedAmount = $student_month ['CollectedAmount'];

				$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];

				// // Tong so tien cua mot phiáº¿u (thĂ¡ng Ä‘ang cháº¡y + cĂ¡c thĂ¡ng trÆ°á»›c chÆ°a thanh toĂ¡n)
				$totalAmount = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );

				if ($totalAmount)
					$this->totalAmount = $totalAmount->getTotalAmount ();

				$this->pricePaymentLate = $this->receipt->getLatePaymentAmount ();

				// echo $this->pricePaymentLate; die;
				// Lay danh sach cac khoan phi cua phieu bao
				$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );

				// Lay phieu bao
				$this->ps_fee_reports = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $this->receivable_at ) );

				// Láº¥y phiáº¿u bĂ¡o cá»§a thĂ¡ng gáº§n trÆ°á»›c Ä‘Ă¢y nháº¥t
				$this->ps_fee_reports_nearest = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $receiptPrevDate ) );

				// TĂ¬m xem cĂ³ phiáº¿u bĂ¡o liá»�n sau khĂ´ng
				$this->receiptOfStudentNextMonth = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentNextMonth ( $student_id, $int_receivable_at );
			} else { // Náº¿u chÆ°a thanh toĂ¡n

				// TĂ¬m xem cĂ³ phiáº¿u bĂ¡o liá»�n sau khĂ´ng lon nhat
				// $receiptOfStudentNextMonth = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentNextMonth ( $student_id, $int_receivable_at );

				$receiptOfStudentNextMonth = $this->receipt;

				$this->receiptOfStudentNextMonth = $receiptOfStudentNextMonth;

				$receiptOfStudentNextMonth = false;

				if ($receiptOfStudentNextMonth) { // Náº¿u cĂ³ thĂ¬ thá»±c hiá»‡n láº¥y theo bĂ¡o phĂ­ nĂ y

					$this->receipt = $receiptOfStudentNextMonth;

					$this->receivable_at = $this->receipt->getReceiptDate ();

					$int_receivable_at = PsDateTime::psDatetoTime ( $this->receivable_at );

					// Lay lop hoc cua hoc sinh tai thoi diem bao phi
					$infoClass = $this->student->getMyClassByStudent ( $this->receivable_at );

					if (! $infoClass) {
						// Lay lop hoc cua hoc sinh Ä‘ang hoáº¡t Ä‘á»™ng
						$infoClass = $this->student->getCurrentClassOfStudent ();
					}

					// Lay thong tin co so dao tao
					$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $infoClass->getPsWorkplaceId () );

					// Láº¥y phiáº¿u thu gáº§n Ä‘Ă¢y nháº¥t
					$student_month = $this->student->getPrecedingMontOfStudent ( $int_receivable_at, $psWorkPlace->getConfigStartDateSystemFee () );

					// Ngay cua phieu thu cáº­n receivable_at nhat
					$receiptPrevDate = $student_month ['receiptPrevDate'];

					// DÆ° cá»§a phiáº¿u thu gáº§n Ä‘Ă¢y nháº¥t
					$this->balanceAmount = $student_month ['BalanceAmount'];

					// Ä�Ă£ ná»™p cá»§a phiáº¿u thu gáº§n Ä‘Ă¢y nháº¥t
					$this->collectedAmount = $student_month ['CollectedAmount'];

					$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];

					// // Tong so tien cua mot phiáº¿u (thĂ¡ng Ä‘ang cháº¡y + cĂ¡c thĂ¡ng trÆ°á»›c chÆ°a thanh toĂ¡n)
					$totalAmount = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );

					if ($totalAmount)
						$this->totalAmount = $totalAmount->getTotalAmount ();

					// Lay danh sach cac khoan phi cua phieu bao
					$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );

					// Lay phieu bao cua thĂ¡ng Ä‘ang chá»�n
					$this->ps_fee_reports = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $this->receivable_at ) );

					// Láº¥y phiáº¿u bĂ¡o cá»§a thĂ¡ng gáº§n Ä‘Ă¢y nháº¥t
					$this->ps_fee_reports_nearest = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $receiptPrevDate ) );
				} else {

					// Lay lop hoc cua hoc sinh tai thoi diem bao phi
					$infoClass = $this->student->getMyClassByStudent ( $this->receivable_at );

					if (! $infoClass) {
						// Lay lop hoc cua hoc sinh Ä‘ang hoáº¡t Ä‘á»™ng
						$infoClass = $this->student->getCurrentClassOfStudent ();
					}

					// Lay thong tin co so dao tao
					$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $infoClass->getPsWorkplaceId () );

					// Lay phieu thu gáº§n Ä‘Ă¢y nháº¥t
					$student_month = $this->student->getPrecedingMontOfStudent ( $int_receivable_at, $psWorkPlace->getConfigStartDateSystemFee () );

					// Ngay cua phieu thu cáº­n receivable_at nhat
					$receiptPrevDate = $student_month ['receiptPrevDate'];

					// echo 'ThĂ¡ng: '.PsDateTime::psTimetoDate($receiptPrevDate);

					// DÆ° cá»§a phiáº¿u thu gáº§n Ä‘Ă¢y nháº¥t
					$this->balanceAmount = $student_month ['BalanceAmount'];

					// Ä�Ă£ ná»™p cá»§a phiáº¿u thu gáº§n Ä‘Ă¢y nháº¥t
					$this->collectedAmount = $student_month ['CollectedAmount'];

					$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];

					// Tong so tien cua mot phiáº¿u (thĂ¡ng Ä‘ang cháº¡y + cĂ¡c thĂ¡ng trÆ°á»›c chÆ°a thanh toĂ¡n)
					$totalAmount = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );

					if ($totalAmount)
						$this->totalAmount = $totalAmount->getTotalAmount ();

					// Lay danh sach cac khoan phi cua phieu bao
					$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );

					// Lay phieu bao
					$this->ps_fee_reports = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $this->receivable_at ) );

					// Láº¥y phiáº¿u bĂ¡o cá»§a thĂ¡ng gáº§n Ä‘Ă¢y nháº¥t
					$this->ps_fee_reports_nearest = $this->student->findPsFeeReportOfStudentByDate ( $receiptPrevDate );
				}

				// Náº¿u cáº¥u hĂ¬nh lĂ  kiá»ƒu lÅ©y tiáº¿n, thĂ¬ pháº£i xem cĂ¡c thĂ¡ng trÆ°á»›c Ä‘Ă³ Ä‘Ă£ thanh toĂ¡n hay chÆ°a
				if ($psWorkPlace->getConfigChooseChargePaylate () == 1) {
					// láº¥y ra thĂ¡ng Ä‘Ă£ thanh toĂ¡n gáº§n nháº¥t so vá»›i phiáº¿u thu hiá»‡n táº¡i
					$check_receipt_date = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentIsPayment ( $student_id );

					if ($check_receipt_date) { // TĂ­nh khoáº£ng cĂ¡ch giá»¯a 2 thĂ¡ng

						$receipt_date = $check_receipt_date->getReceiptDate ();

						$datetime1 = date_create ( $receipt_date );
						$datetime2 = date_create ( $this->receivable_at );
						$interval = date_diff ( $datetime1, $datetime2 );

						$check_month = $interval->format ( '%m' );
					} else { // Náº¿u chÆ°a tá»«ng thanh toĂ¡n láº§n nĂ o, thĂ¬ Ä‘áº¿m xem cĂ³ bao nhiĂªu thĂ¡ng trÆ°á»›c Ä‘Ă³ chÆ°a thanh toĂ¡n

						$check_receipt_date = Doctrine::getTable ( 'Receipt' )->countReceiptOfStudentIsPayment ( $student_id, $int_receivable_at );

						$check_month = count ( $check_receipt_date ) + 1;
					}

					$total_price = 0;

					if ($check_month > 1) {

						for($i = 1; $i < $check_month; $i ++) {

							$track_at = date_create ( $this->receivable_at );

							date_modify ( $track_at, "-$i month" );

							$date_receipt = date_format ( $track_at, "Y-m-d" );

							$latePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplacePrevMonth ( $ps_workplace_id, $date_receipt );
							if ($latePayment) {
								$total_price += $latePayment->getPrice (); // TĂ­nh tá»•ng khoáº£n pháº¡t ná»™p muá»™n há»�c phĂ­
							}
						}
						$this->pricePaymentLate = $total_price;
					}
				}
			}

			// $this->balance_last_month_amount = $this->receipt->getBalanceLastMonthAmount();

			// lay cau hinh thiet lap phi nop muon
			$this->psChargePaylate = $psWorkPlace->getConfigChooseChargePaylate ();

			// Lay tong so tien du kien cua 1 thang receivable_at(thĂ¡ng Ä‘ang xem) - Dá»± kiáº¿n cĂ¡c khoáº£n thu cá»§a thĂ¡ng receivable_at
			$this->totalAmountReceivableAt = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentInMonth ( $student_id, $int_receivable_at );
		}
	}

	/**
	 * Chi tiet phieu thu 1.
	 * Náº¿u phiáº¿u thu Ä‘Ă£ thanh toĂ¡n thĂ¬ hiá»ƒn thá»‹ bĂ¬nh thÆ°á»�ng 2. Náº¿u phiáº¿u thu chÆ°a thanh toĂ¡n: BÆ°á»›c 1: TĂ¬m phiáº¿u thu Ä‘Ă£ thanh toĂ¡n lá»›n hÆ¡n gáº§n nháº¥t: Náº¿u cĂ³ thĂ¬ khĂ´ng cho thá»±c hiá»‡n thanh toĂ¡n phiáº¿u nĂ y vĂ¬ Ä‘Ă£ thá»±c hiá»‡n dá»“n cĂ´ng ná»£ TrĂ¡i láº¡i sang bÆ°á»›c 2 BÆ°á»›c 2: TĂ¬m phiáº¿u thu chÆ°a thanh toĂ¡n lá»›n nháº¥t(thĂ¡ng lá»›n nháº¥t)
	 */
	public function executeShow(sfWebRequest $request) {

		// Phiáº¿u B- ThĂ¡ng Ä‘ang xem
		$this->receipt = $this->getRoute ()
			->getObject ();

		if (! $this->receipt) {
			// $this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			$this->getUser ()
				->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
			$this->redirect ( '@ps_receipts' );
		}

		if (! myUser::checkAccessObject ( $this->receipt->getStudent (), 'PS_FEE_REPORT_FILTER_SCHOOL' ) || $this->receipt->getStudent ()
			->getDeletedAt ()) {

			$this->getUser ()
				->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );

			$this->redirect ( '@ps_receipts' );
		} else {

			// ThĂ¡ng thu phĂ­
			$this->receivable_at = $this->receipt->getReceiptDate ();

			$int_receivable_at = PsDateTime::psDatetoTime ( $this->receipt->getReceiptDate () );
			
			$this->student = $this->receipt->getStudent ();

			$student_id = $this->student->getId ();

			/*
			 * if (myUser::isAdministrator()) { $abc = Doctrine::getTable ( 'PsLogtimes' )->getSumAbsentOfStudent($student_id, '2019-02-01'); echo 'Sá»‘ ngĂ y nghá»‰ há»�c:'.$abc; }
			 */

			// Lay lop hoc cua hoc sinh tai thoi diem cá»§a phiáº¿u bĂ¡o phĂ­
			$infoClass = Doctrine::getTable ( "StudentClass" )->getClassActivateByStudent ( $student_id, $this->receivable_at );
			// Náº¿u khĂ´ng xĂ¡c Ä‘á»‹nh Ä‘Æ°á»£c lá»›p
			if (! $infoClass) {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}

			// Lay danh sach nguoi than cua hoc sinh
			$this->relatives = $this->student->getRelativesOfStudent ();

			// Phiáº¿u bĂ¡o thĂ¡ng liá»�n sau nháº¥t
			$this->receiptOfStudentNextMonth = null;

			// Láº¥y cáº¥u hĂ¬nh Vá»� muá»™n
			$this->psConfigLatePayment = null;

			// Phiáº¿u cá»§a thĂ¡ng liá»�n sau nháº¥t
			$this->receiptOfStudentNextMonth = null;

			$this->totalAmount = 0;

			$this->pricePaymentLate = 0;
			// Tien thua tu phieu thu gan PHIáº¾U A nhat chuyá»ƒn sang PHIáº¾U A
			$this->balance_last_month_amount = 0;

			// Láº¥y lá»›p cá»§a há»�c sinh táº¡i thá»�i Ä‘iá»ƒm hiá»‡n táº¡i
			// $student_info = $this->student->getClassByDate ( time () );

			$ps_workplace_id = $infoClass->getPsWorkplaceId ();

			// Lay thong tin co so dao tao
			$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $ps_workplace_id );
			
			// Lay cau hinh thiet lap cĂ¡ch tĂ­nh phĂ­ ná»™p muá»™n cÆ¡ sá»Ÿ
			$this->psChargePaylate = $psWorkPlace->getConfigChooseChargePaylate ();

			// Láº¥y cáº¥u hĂ¬nh phĂ­ ná»™p muá»™n táº¡i thá»�i Ä‘iá»ƒm hiá»‡n táº¡i
			$this->psConfigLatePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplaceDate ( $ps_workplace_id, date ( 'Y-m-d' ) );

			$configStart = $psWorkPlace->getConfigStartDateSystemFee ();
			
			// Lay phieu thu gần đây nhất - Phiáº¿u A
			$student_month = $this->student->getPrecedingMontOfStudent ( $int_receivable_at, $configStart );

			$receiptFirst = $student_month ['receiptFirst'];

			// Ngay cua - Phiáº¿u A
			$receiptPrevDate = $student_month ['receiptPrevDate'];
			//echo date('Y-m-d',$receiptPrevDate);
			// Ä�Ă£ ná»™p cá»§a - Phiáº¿u A
			$this->collectedAmount = $student_month ['CollectedAmount'];

			$this->old_balance_amount = 0;

			if ($receiptFirst) { // Nếu là phiếu đầu tiên

				// $this->balanceAmount = $this->receipt->getBalanceAmount ();

				$this->balanceAmount = $this->receipt->getBalanceLastMonthAmount ();

				// DÆ° cá»§a phiáº¿u cáº­n Phiáº¿u A nháº¥t
				$this->balance_last_month_amount = $this->receipt->getBalanceLastMonthAmount ();
			} else {
				// echo 'XXXXXXXXXX';
				// DÆ° cá»§a - Phiáº¿u A
				if ($this->receipt->getPaymentStatus () != PreSchool::ACTIVE) {

					// echo '111111111111';

					$this->balanceAmount = $student_month ['balance_last_month_amount'];

					$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];

					// $this->old_balance_amount = $this->receipt->getBalanceLastMonthAmount ();
				} else {

					// echo '2222222222222';

					$this->balanceAmount = $student_month ['BalanceAmount'];

					// DÆ° cá»§a phiáº¿u cáº­n Phiáº¿u A nháº¥t
					$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];

					// $this->old_balance_amount = $this->balanceAmount + $this->balance_last_month_amount;
				}

				// TĂ¬m phiáº¿u trÆ°á»›c cá»§a phiáº¿u A
				$student_month_2 = $this->student->getPrecedingMontOfStudent ( $receiptPrevDate, $configStart );

				if ($student_month_2 ['receiptFirst']) {

					// echo '3333333333333333';

					// Lay phieu thĂ¡ng $receiptPrevDate
					$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $receiptPrevDate );

					$this->balanceAmount = $receipt_2->getBalanceLastMonthAmount (); // $receipt_2->getBalanceAmount ();

					// DÆ° cá»§a phiáº¿u cáº­n Phiáº¿u A nháº¥t
					$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();

					// $this->old_balance_amount = $this->balance_last_month_amount;
				} else {

					// echo '444444444444444444';

					$student_month_2 = $this->student->getPrecedingMontOfStudent ( $student_month_2 ['receiptPrevDate'], $configStart );

					if ($student_month_2 ['receiptFirst']) {

						// echo '555555555555';

						// Lay phieu thĂ¡ng $student_month_2['receiptPrevDate']
						$receipt_2 = Doctrine::getTable ( 'Receipt' )->findOfStudentByDate ( $student_id, $receiptPrevDate );

						// $this->balanceAmount = $receipt_2->getBalanceLastMonthAmount (); // $receipt_2->getBalanceAmount ();

						// $this->balanceAmount = $receipt_2->getBalanceLastMonthAmount () + $receipt_2->getBalanceAmount ();

						$this->balanceAmount = $receipt_2->getBalanceAmount ();

						// DÆ° cá»§a phiáº¿u cáº­n Phiáº¿u A nháº¥t
						$this->balance_last_month_amount = $receipt_2->getBalanceLastMonthAmount ();

						// $this->old_balance_amount = $this->balance_last_month_amount;
					}
				}
			}

			$this->balance_last_month_amount = $this->receipt->getBalanceLastMonthAmount ();

			// lay cau hinh thiet lap phi nop muon
			$this->exportData = $psWorkPlace->getConfigTemplateReceiptExport ();

			// lay cau hinh thiet lap phi nop muon
			$this->psChargePaylate = $psWorkPlace->getConfigChooseChargePaylate ();

			$this->psWorkPlace = $psWorkPlace;

			// Lay danh sach cac khoan phi cua phieu bao

			$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );
			
			//echo $this->receivable_at.'__'.$receiptPrevDate;
			
			// Lay phieu bao
			$this->ps_fee_reports = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $this->receivable_at ) );

			// TĂ¬m xem cĂ³ phiáº¿u bĂ¡o liá»�n sau khĂ´ng
			$this->receiptOfStudentNextMonth = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentNextMonth ( $student_id, $int_receivable_at );

			$this->btnPayment = true;

			// Náº¿u chÆ°a thanh toĂ¡n vĂ  cĂ³ phiáº¿u liá»�n sau thĂ¬ khĂ´ng cho thanh toĂ¡n
			if ($this->receiptOfStudentNextMonth && $this->receipt->getPaymentStatus () != PreSchool::ACTIVE) {
				$this->btnPayment = false;
			}

			if ($this->receipt->getPaymentStatus () == PreSchool::ACTIVE) { // náº¿u Ä‘Ă£ thanh toĂ¡n
				$this->btnPayment = false;
				// Tiá»�n pháº¡t ná»™p muá»™n thu Ä‘Æ°á»£c
				$this->pricePaymentLate = $this->receipt->getLatePaymentAmount ();
			} else {
				// Náº¿u cáº¥u hĂ¬nh lĂ  kiá»ƒu lÅ©y tiáº¿n, thĂ¬ pháº£i xem cĂ¡c thĂ¡ng trÆ°á»›c Ä‘Ă³ Ä‘Ă£ thanh toĂ¡n hay chÆ°a
				if ($this->psChargePaylate == 1) {
					// láº¥y ra thĂ¡ng Ä‘Ă£ thanh toĂ¡n gáº§n nháº¥t so vá»›i phiáº¿u thu hiá»‡n táº¡i
					$check_receipt_date = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentIsPayment ( $student_id );

					if ($check_receipt_date) { // TĂ­nh khoáº£ng cĂ¡ch giá»¯a 2 thĂ¡ng

						$receipt_date = $check_receipt_date->getReceiptDate ();

						$datetime1 = date_create ( $receipt_date );
						$datetime2 = date_create ( $this->receivable_at );
						$interval = date_diff ( $datetime1, $datetime2 );

						$check_month = $interval->format ( '%m' );
					} else { // Náº¿u chÆ°a tá»«ng thanh toĂ¡n láº§n nĂ o, thĂ¬ Ä‘áº¿m xem cĂ³ bao nhiĂªu thĂ¡ng trÆ°á»›c Ä‘Ă³ chÆ°a thanh toĂ¡n

						$check_receipt_date = Doctrine::getTable ( 'Receipt' )->countReceiptOfStudentIsPayment ( $student_id, $int_receivable_at );

						$check_month = count ( $check_receipt_date ) + 1;
					}

					$total_price = 0;

					if ($check_month > 1) {

						for($i = 1; $i < $check_month; $i ++) {

							$track_at = date_create ( $this->receivable_at );

							date_modify ( $track_at, "-$i month" );

							$date_receipt = date_format ( $track_at, "Y-m-d" );

							$latePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplacePrevMonth ( $ps_workplace_id, $date_receipt );
							if ($latePayment) {
								$total_price += $latePayment->getPrice (); // TĂ­nh tá»•ng khoáº£n pháº¡t ná»™p muá»™n há»�c phĂ­
							}
						}
					}
					if ($this->psConfigLatePayment) {
						$this->pricePaymentLate = $this->psConfigLatePayment->getPrice () + $total_price;
					} else {
						$this->pricePaymentLate = $total_price;
					}
				} else {
					if ($this->psConfigLatePayment) {
						$this->pricePaymentLate = $this->psConfigLatePayment->getPrice ();
					}
				} // Ket thuc xu ly phĂ­ ná»™p muá»™n
			}
		}
	}

	/**
	 * Chi tiet phieu thu 1.
	 * Náº¿u phiáº¿u thu Ä‘Ă£ thanh toĂ¡n thĂ¬ hiá»ƒn thá»‹ bĂ¬nh thÆ°á»�ng 2. Náº¿u phiáº¿u thu chÆ°a thanh toĂ¡n: BÆ°á»›c 1: TĂ¬m phiáº¿u thu Ä‘Ă£ thanh toĂ¡n lá»›n hÆ¡n gáº§n nháº¥t, náº¿u cĂ³ thĂ¬ hiá»ƒn thá»‹ theo phiáº¿u nĂ y, trĂ¡i láº¡i sang bÆ°á»›c 2 BÆ°á»›c 2: TĂ¬m phiáº¿u thu chÆ°a thanh toĂ¡n lá»›n nháº¥t(thĂ¡ng lá»›n nháº¥t)
	 */
	public function executeDetail(sfWebRequest $request) {

		$this->receipt = $this->getRoute ()
			->getObject ();

		if (! $this->receipt) {
			// $this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			$this->getUser ()
				->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
			$this->redirect ( '@ps_receipts' );
		}

		if (! myUser::checkAccessObject ( $this->receipt->getStudent (), 'PS_FEE_REPORT_FILTER_SCHOOL' ) || $this->receipt->getStudent ()
			->getDeletedAt ()) {

			$this->getUser ()
				->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );

			$this->redirect ( '@ps_receipts' );
		} else {

			$this->student = $this->receipt->getStudent ();

			$receiptPrevDate = null;

			$this->balanceAmount = 0;

			$this->collectedAmount = 0;

			$this->pricePaymentLate = 0;

			// Tong so tien cua mot phiáº¿u
			$this->totalAmount = 0;

			// Kiem tra thoi gian tam dung nghi hoc

			// Thang bao phi
			$this->receivable_at = $this->receipt->getReceiptDate ();

			// Lay danh sach nguoi than cua hoc sinh
			$this->relatives = $this->student->getRelativesOfStudent ();

			$student_id = $this->receipt->getStudentId ();

			$int_receivable_at = PsDateTime::psDatetoTime ( $this->receivable_at );

			// Lat tong so tien du kien cua 1 thang receivable_at(thĂ¡ng Ä‘em chá»�n)
			$this->totalAmountReceivableAt = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentInMonth ( $student_id, $int_receivable_at );

			// Lay phieu thu cua thang duoc chon
			// $this->receipt = $this->student->findReceiptByDate ( $int_receivable_at );

			// if (! $this->receipt || ($this->receipt && $this->receipt->getPaymentStatus () != PreSchool::ACTIVE)) {

			// Lay lop hoc cua hoc sinh tai thoi diem bao phi
			$infoClass = $this->student->getMyClassByStudent ( $this->receivable_at );

			if (! $infoClass) {
				// Lay lop hoc cua hoc sinh Ä‘ang hoáº¡t Ä‘á»™ng
				$infoClass = $this->student->getCurrentClassOfStudent ();
			}

			// Lay thong tin co so dao tao
			$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $infoClass->getPsWorkplaceId () );

			$student_month = $this->student->getPrecedingMontOfStudent ( $int_receivable_at, $psWorkPlace->getConfigStartDateSystemFee () );

			// Ngay cua phieu thu gan nhat
			$receiptPrevDate = $student_month ['receiptPrevDate'];

			// DÆ° cá»§a phiáº¿u thu gáº§n Ä‘Ă¢y nháº¥t
			$this->balanceAmount = $student_month ['BalanceAmount'];

			// Ä�Ă£ ná»™p cá»§a phiáº¿u thu gáº§n Ä‘Ă¢y nháº¥t
			$this->collectedAmount = $student_month ['CollectedAmount'];

			// print_r($student_month);
			// }

			// Lay danh sach cac khoan phi cua phieu bao
			$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );

			// Tong so tien cua mot phiáº¿u (thĂ¡ng Ä‘ang cháº¡y + cĂ¡c thĂ¡ng trÆ°á»›c chÆ°a thanh toĂ¡n)
			$totalAmount = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );

			if ($totalAmount)
				$this->totalAmount = $totalAmount->getTotalAmount ();

			$this->form = new ReceiptForm ( $this->receipt );

			// lay cau hinh thiet lap phi nop muon
			$this->psChargePaylate = $psWorkPlace->getConfigChooseChargePaylate ();
			// Náº¿u cáº¥u hĂ¬nh lĂ  kiá»ƒu lÅ©y tiáº¿n, thĂ¬ pháº£i xem cĂ¡c thĂ¡ng trÆ°á»›c Ä‘Ă³ Ä‘Ă£ thanh toĂ¡n hay chÆ°a
			if ($psWorkPlace->getConfigChooseChargePaylate () == 1) {
				// láº¥y ra thĂ¡ng Ä‘Ă£ thanh toĂ¡n gáº§n nháº¥t so vá»›i phiáº¿u thu hiá»‡n táº¡i
				$check_receipt_date = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentIsPayment ( $student_id );

				if ($check_receipt_date) { // TĂ­nh khoáº£ng cĂ¡ch giá»¯a 2 thĂ¡ng

					$receipt_date = $check_receipt_date->getReceiptDate ();

					$datetime1 = date_create ( $receipt_date );
					$datetime2 = date_create ( $int_receivable_at );
					$interval = date_diff ( $datetime1, $datetime2 );

					$check_month = $interval->format ( '%m' );
				} else { // Náº¿u chÆ°a tá»«ng thanh toĂ¡n láº§n nĂ o, thĂ¬ Ä‘áº¿m xem cĂ³ bao nhiĂªu thĂ¡ng trÆ°á»›c Ä‘Ă³ chÆ°a thanh toĂ¡n

					$check_receipt_date = Doctrine::getTable ( 'Receipt' )->countReceiptOfStudentIsPayment ( $student_id, $int_receivable_at );

					$check_month = count ( $check_receipt_date ) + 1;
				}

				$total_price = 0;
				if ($check_month > 1) {

					for($i = 1; $i < $check_month; $i ++) {

						$track_at = date_create ( $this->receivable_at );

						date_modify ( $track_at, "-$i month" );

						$date_receipt = date_format ( $track_at, "Y-m-d" );

						$latePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplacePrevMonth ( $infoClass->getPsWorkplaceId (), $date_receipt );
						if ($latePayment) {
							$total_price += $latePayment->getPrice (); // TĂ­nh tá»•ng khoáº£n pháº¡t ná»™p muá»™n há»�c phĂ­
						}
					}
					$this->pricePaymentLate = $total_price;
				}
			}

			// Lay phieu bao
			$this->ps_fee_reports = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $this->receivable_at ) );

			// Tim phieu thu(phieu bao) liá»�n sau thĂ¡ng nĂ y nháº¥t
			$this->receiptOfStudentNextMonth = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentNextMonth ( $student_id, strtotime ( $this->receivable_at ) );

			$this->psConfigLatePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplaceDate ( $psWorkPlace->getId (), date ( 'Y-m-d' ) );
		}
	}

	// Thuc hien luu hanh toan - Save Payment
	public function executePayment(sfWebRequest $request) {

		$this->receipt = $this->getRoute ()->getObject ();

		if (! $this->receipt) {

			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		} elseif ($this->receipt->getPaymentStatus () == PreSchool::ACTIVE) {

			//$this->getUser ()->setFlash ( 'notice', $this->getContext ()->getI18N ()->__ ( 'The bill has been paid.' ) );

			//$this->redirect ( '@ps_receipts' );
		}

		$this->student = $this->receipt->getStudent ();

		if (! myUser::checkAccessObject ( $this->student, 'PS_FEE_REPORT_FILTER_SCHOOL' ) || ! $this->student || ($this->student && $this->student->getDeletedAt ())) {
			$this->getUser ()->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
		}

		// Kiem tra thoi gian tam dung nghi hoc

		// Thang bao phi
		$this->receivable_at = $this->receipt->getReceiptDate ();

		// Lay gia tri thanh toan
		$rec = $request->getParameter ( 'rec' );

		$replace_date = str_replace ( '/', '-', $rec ['payment_date'] );

		$payment_date = date ( 'Y-m-d', strtotime ( $replace_date ) ); // chuyá»ƒn Ä‘á»‹nh dáº¡ng ngĂ y thĂ¡ng

		// Thời gian thanh toán
		if ($payment_date != '1970-01-01' && strtotime ( $payment_date ) <= strtotime ( date ( 'Y-m-d' ) )) {
			$payment_date = $payment_date;
		} else {
			$payment_date = date ( "Y-m-d H:i:s" );
		}
		$user_id = myUser::getUserId();
		// Lay phieu bao cua thang
		$this->ps_fee_reports = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $this->receivable_at ) );
		//Doctrine_Query
		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			// Validate
			if (($rec ['collected_amount'] != 0) && is_numeric ( $rec ['collected_amount'] ) && mb_strlen ( $rec ['note'] <= 255 )) {

				$this->receipt->setCollectedAmount ( $this->receipt->getCollectedAmount() + $rec ['collected_amount'] );
				
				// Thanh toán - ThanhPV
				// Số dư nợ
				$this->receipt->setBalanceAmount ( ( float ) ($rec ['total_payment'] - $rec ['collected_amount']) );

				// Tổng số tiền đã chiết khấu
				$this->receipt->setChietkhau ( $this->receipt->getChietkhau() - ( float )$rec['chietkhau'] );

				$this->receipt->setNote ( PreString::trim ( $rec ['note'] ) );
				$this->receipt->setPaymentRelativeName ( PreString::trim ( $rec ['relative_name'] ) );
				$this->receipt->setCashierName ( PreString::trim ( $rec ['cashier'] ) );
				$this->receipt->setPaymentType ( PreString::trim ( $rec ['payment_type'] ) );
				$this->receipt->setPaymentStatus ( PreSchool::ACTIVE );
				$this->receipt->setPaymentDate ( $payment_date );
				
				$this->receipt->setUserUpdatedId (myUser::getUser()->getId ());
				$this->receipt->setLatePaymentAmount ( $rec ['late_payment_amount'] );
				
				// Lay STT thanh toan
				//$this->receipt->setPaymentOrder ($this->receipt->getMaxPaymentOrder());
								
				$this->receipt->save ();

				$tkkhotien = '1111';
				$tkkhachhang = '131';

				$receipt_no = $this->receipt->getReceiptNo();

				$arr_receipt = explode('-', $receipt_no);

				// Lưu vào nhật ký công nợ - Thu tiền của khách hàng
				$nhatKy = new PsNhatKyCongNo();

				$nhatKy -> setPsCustomerId($this->student->getPsCustomerId());
				$nhatKy -> setPsWorkplaceId($this->student->getPsWorkplaceId());
				$nhatKy -> setTkno($tkkhotien);
				$nhatKy -> setTkco($tkkhachhang);
				$nhatKy -> setThoigian($payment_date);
				$nhatKy -> setChungtu($arr_receipt[0]);
				$nhatKy -> setSochungtu($arr_receipt[1]);
				$nhatKy -> setDoituongco($this->student->getStudentCode());
				$nhatKy -> setIdhocsinh($this->receipt->getStudentId());
				$nhatKy -> setTendichvu($this->receipt->getTitle());
				$nhatKy -> setSoluong(1);
				$nhatKy -> setDongia();
				$nhatKy -> setThanhtien($rec ['collected_amount']); // Số tiền đã thanh toán
				$nhatKy -> setGiamtru(0 - $rec['chietkhau']);

				$nhatKy -> setUserCreatedId ($user_id);
				$nhatKy -> setUserUpdatedId ($user_id);
				$nhatKy -> save();

				$this->getUser ()->setFlash ( 'notice', $this->getContext ()->getI18N ()->__ ( 'Payment successfully.' ) );
			} else {
				$this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ( 'Payment fail. Data invalid.' ) );
			}

			$conn->commit ();
		} catch ( Exception $e ) {

			$conn->rollback ();

			$this->getUser ()->setFlash ( 'error', 'Payment fail.' .$e);

			$this->redirect ( '@ps_receipts' );
		}

		$this->redirect ( '@ps_receipts' );
	}

	/**
	 * Form import phieu thu
	 */
	public function executeImport(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_file = null;

		$import_filter = $request->getParameter ( 'import_filter' );

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
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
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );

		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );

		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_filter[%s]' );
	}

	/**
	 * Form import phiếu thu --- Lưu
	 */
	public function executeImportSave(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_file = null;

		$import_filter = $request->getParameter ( 'import_filter' );

		if ($import_filter) {

			$this->ps_customer_id = isset ( $import_filter ['ps_customer_id'] ) ? $import_filter ['ps_customer_id'] : 0;
		}

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
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
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );

		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );

		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_filter[%s]' );

		/**
		 * * Save Import file excel **
		 */

		$import_filter_form = $request->getParameter ( 'import_filter' );

		$this->formFilter->bind ( $request->getParameter ( 'import_filter' ), $request->getFiles ( 'import_filter' ) );

		// id truong hoc
		$ps_customer_id = $this->formFilter->getValue ( 'ps_customer_id' );

		$students = Doctrine::getTable ( 'Student' )->getAllStudentsByCustomerId ( $ps_customer_id, $ps_workplace_id );

		$array_student = array ();

		$_array_student = array ();

		foreach ( $students as $student ) {

			array_push ( $array_student, $student->getStudentCode () );

			$_array_student [$student->getStudentCode ()] = $student->getId ();
		}

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			if ($this->formFilter->isValid ()) {

				$user_id = myUser::getUserId ();

				$file_classify = $this->getContext ()
					->getI18N ()
					->__ ( 'Fee report import' );

				$file = $this->formFilter->getValue ( 'ps_file' );

				$filename = time () . $file->getOriginalName ();

				$file_link = 'FeeReports' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );

				$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';

				$file->save ( $path_file . $filename );

				$objPHPExcel = PHPExcel_IOFactory::load ( $path_file . $filename );

				$provinceSheet = $objPHPExcel->setActiveSheetIndex ( 0 ); // Set sheet sáº½ Ä‘Æ°á»£c Ä‘á»�c dá»¯ liá»‡u

				$highestRow = $provinceSheet->getHighestRow (); // Láº¥y sá»‘ row lá»›n nháº¥t trong sheet

				$array_error = array ();

				$false = 0;

				$true = 0;

				for($row = 3; $row <= $highestRow; $row ++) {

					$student_code = $provinceSheet->getCellByColumnAndRow ( 1, $row )
						->getValue ();

					$note = $provinceSheet->getCellByColumnAndRow ( 7, $row )
						->getValue ();

					$str_number = strlen ( $note );
					if ($student_code != '') {
						if (in_array ( $student_code, $array_student ) && $str_number < 255) {

							$true ++;

							$receipt = $provinceSheet->getCellByColumnAndRow ( 2, $row )
								->getValue ();

							$receivable_date = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $receipt ) ) ); // chuyá»ƒn Ä‘á»‹nh dáº¡ng

							if ($receivable_date != '1970-01-01') { // Kiá»ƒm tra xem cĂ³ Ä‘Ăºng ngĂ y khĂ´ng
								$receipt_date = $receivable_date;
							} else {
								$receipt_date = date ( 'Y-m-d' );
							}

							$receipt_title = $provinceSheet->getCellByColumnAndRow ( 3, $row )
								->getValue ();

							$receivable = $provinceSheet->getCellByColumnAndRow ( 4, $row )
								->getValue ();

							$collected_amount = $provinceSheet->getCellByColumnAndRow ( 5, $row )
								->getValue ();

							$balance_amount = $provinceSheet->getCellByColumnAndRow ( 6, $row )
								->getValue ();

							$student_id = null;

							foreach ( $_array_student as $key => $_student_id ) {
								if ($key == $student_code) {
									$student_id = $_student_id;
									break;
								}
							}

							if ($student_id > 0) {
								$strtime_receivable_at = PsDateTime::psDatetoTime ( $receipt_date );

								$psReceipt = new PsReceiptTemporary ();

								$psReceipt->setPsCustomerId ( $ps_customer_id );
								$psReceipt->setStudentId ( $student_id );
								$psReceipt->setTitle ( $receipt_title );
								$psReceipt->setReceiptDate ( $receipt_date );
								$psReceipt->setReceivable ( $receivable );
								$psReceipt->setCollectedAmount ( $collected_amount );
								$psReceipt->setBalanceAmount ( $balance_amount );
								$psReceipt->setIsCurrent ( 0 );
								$psReceipt->setIsImport ( 1 );
								$psReceipt->setPaymentStatus ( 1 );
								$psReceipt->setNote ( $note );
								$psReceipt->setUserCreatedId ( $user_id );
								$psReceipt->setUserUpdatedId ( $user_id );

								$psReceipt->save ();
							}
						} else {
							$false ++;
							array_push ( $array_error, $student_code );
						}
					}
				}

				if ($true > 0) {
					// luu lich su import file phieu ghi no
					$ps_history_import = new PsHistoryImport ();
					$ps_history_import->setPsCustomerId ( $ps_customer_id );
					// $ps_history_import -> setPsWorkplaceId(null);
					$ps_history_import->setFileName ( $filename );
					$ps_history_import->setFileLink ( $file_link );
					$ps_history_import->setFileClassify ( $file_classify );
					$ps_history_import->setUserCreatedId ( $user_id );

					$ps_history_import->save ();
				} else {
					unlink ( $path_file . $filename );
					$error_import = $this->getContext ()
						->getI18N ()
						->__ ( 'Import file failed.' );
					$this->getUser ()
						->setFlash ( 'error', $error_import );
					$this->redirect ( '@ps_receipts_import' );
				}

				$error_line = implode ( ' ; ', $array_error );
			} else {
				$error_import = $this->getContext ()
					->getI18N ()
					->__ ( 'formFilter->isValid ().' );
				$this->getUser ()
					->setFlash ( 'error', $error_import );
				$this->redirect ( '@ps_receipts_import' );
			}

			$conn->commit ();
		} catch ( Exception $e ) {

			unlink ( $path_file . $filename );

			$conn->rollback ();

			$error_import = $this->getContext ()
				->getI18N ()
				->__ ( 'Error form.' ) . $e->getMessage ();

			$this->getUser ()
				->setFlash ( 'error', $error_import );
			$this->redirect ( '@ps_receipts_import' );
		}
		if ($false == 0) {
			$successfully = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file successfully %value% data. No error student code', array (
					'%value%' => $true ) );
			$this->getUser ()
				->setFlash ( 'notice', $successfully );
		} else {

			$successfully = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file successfully.' );

			$success_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Successfully : ' ) . $true;

			$error_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Error : ' ) . $false;

			$error_array = $this->getContext ()
				->getI18N ()
				->__ ( 'Studen code' ) . $error_line;

			$this->getUser ()
				->setFlash ( 'notice', $successfully );
			$this->getUser ()
				->setFlash ( 'notice1', $success_number );
			$this->getUser ()
				->setFlash ( 'notice2', $error_number );
			$this->getUser ()
				->setFlash ( 'notice3', $error_array );
		}

		$this->redirect ( '@ps_receipts_import' );
	}

	/**
	 * Import sổ thanh toán
	 */
	public function executeImportreceipt(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_file = null;

		$import_receipt = $request->getParameter ( 'import_receipt' );

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
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
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );

		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );

		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_receipt[%s]' );
	}

	/**
	 * Import sổ thanh toán - Lưu
	 */
	public function executeImportreceiptSave(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_file = null;

		$import_receipt = $request->getParameter ( 'import_receipt' );

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
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
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );

		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );

		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_receipt[%s]' );

		/**
		 * * Save Import file excel **
		 */

		$import_filter_form = $request->getParameter ( 'import_receipt' );

		$this->formFilter->bind ( $request->getParameter ( 'import_receipt' ), $request->getFiles ( 'import_receipt' ) );

		// id truong hoc
		$ps_customer_id = $this->formFilter->getValue ( 'ps_customer_id' );

		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			if ($ps_customer_id != myUser::getPscustomerID ()) {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}
		}

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			if ($this->formFilter->isValid ()) {

				$user_id = myUser::getUserId ();

				$file_classify = $this->getContext ()
					->getI18N ()
					->__ ( 'Import receipt student' );

				$file = $this->formFilter->getValue ( 'ps_file' );

				$filename = time () . $file->getOriginalName ();

				$file_link = 'PsReceipt' . '/' . 'CoSoDaoTao' . $ps_workplace_id . '/' . date ( 'Ym' );

				$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';

				$file->save ( $path_file . $filename );

				$objPHPExcel = PHPExcel_IOFactory::load ( $path_file . $filename );

				$provinceSheet = $objPHPExcel->setActiveSheetIndex ( 0 ); // Set sheet sáº½ Ä‘Æ°á»£c Ä‘á»�c dá»¯ liá»‡u

				$highestRow = $provinceSheet->getHighestRow (); // Láº¥y sá»‘ row lá»›n nháº¥t trong sheet

				$array_error = $error_receipt = array ();

				$false = 0;

				$true = 0;

				for($row = 6; $row <= $highestRow; $row ++) {

					$student_code = $provinceSheet->getCellByColumnAndRow ( 1, $row )
						->getValue ();

					$receipt_no = $provinceSheet->getCellByColumnAndRow ( 3, $row )
						->getValue (); // ma phieu thu

					if ($student_code != '' && $receipt_no != '') { // ma hoc sinh va ma phieu thu khong duoc trong

						$receipt = Doctrine_Core::getTable ( 'Receipt' )->checkStudentAndReceiptNo ( $student_code, $receipt_no );

						if ($receipt) {

							$total_amount = Doctrine::getTable ( 'PsFeeReports' )->getAmountFeeReceiptOfMonth ( $student_code, $receipt->getReceiptDate () );

							if ($total_amount) {

								$amount = $provinceSheet->getCellByColumnAndRow ( 5, $row )
									->getCalculatedValue (); // So tien nop

								if (is_numeric ( $amount )) {

									$true ++;

									$receipt_date = $provinceSheet->getCellByColumnAndRow ( 6, $row )
										->getValue (); // ngay nop tien

									$cashier = $provinceSheet->getCellByColumnAndRow ( 7, $row )
										->getValue (); // thu ngan

									$receivable_date = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $receipt_date ) ) ); // chuyá»ƒn Ä‘á»‹nh dáº¡ng

									if ($receivable_date != '1970-01-01') { // Kiá»ƒm tra xem cĂ³ Ä‘Ăºng ngĂ y khĂ´ng
										$payment_date = $receivable_date;
									} else {
										$payment_date = date ( 'Y-m-d' );
									}

									$balance_amount = $total_amount->getReceivable () - $amount;

									$receipt->setCollectedAmount ( $amount );
									$receipt->setPaymentDate ( $payment_date );
									$receipt->setCashierName ( $cashier );
									$receipt->setBalanceAmount ( $balance_amount ); // so tien du ra
									$receipt->setPaymentStatus ( 1 );
									$receipt->setUserUpdatedId ( $user_id );

									$receipt->save ();
								} else {
									$false ++;
									array_push ( $error_receipt, $row );
								}
							} else {
								$false ++;
								array_push ( $error_receipt, $row );
							}
						} else {
							$false ++;
							array_push ( $error_receipt, $row );
						}
					}
				}

				if ($true > 0) {
					// luu lich su import file phieu ghi no
					$ps_history_import = new PsHistoryImport ();
					$ps_history_import->setPsCustomerId ( $ps_customer_id );
					$ps_history_import->setPsWorkplaceId ( null );
					$ps_history_import->setFileName ( $filename );
					$ps_history_import->setFileLink ( $file_link );
					$ps_history_import->setFileClassify ( $file_classify );
					$ps_history_import->setUserCreatedId ( $user_id );

					$ps_history_import->save ();
				} else {
					unlink ( $path_file . $filename );
				}

				$error_receipt_no = implode ( ' ; ', $error_receipt );
			} else {
				$error_import = $this->getContext ()
					->getI18N ()
					->__ ( 'formFilter->isValid ().' );
				$this->getUser ()
					->setFlash ( 'error', $error_import );
				$this->redirect ( '@ps_receipts_student_import' );
			}

			$conn->commit ();
		} catch ( Exception $e ) {
			unlink ( $path_file . $filename );
			$conn->rollback ();
		}

		if ($false == 0 && $true > 0) {
			$successfully = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file successfully %value% data. No error student code', array (
					'%value%' => $true ) );
			$this->getUser ()
				->setFlash ( 'notice', $successfully );
		} elseif ($true == 0) {

			if (count ( $error_receipt ) > 0) { // loi ma phieu thu
				$er_streceip = $this->getContext ()
					->getI18N ()
					->__ ( 'Line' ) . $error_receipt_no;
			} else {
				$er_streceip = '';
			}

			$error_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Error : ' ) . $false;

			$error_all = $error_number . ' ; ' . $er_streceip;

			$this->getUser ()
				->setFlash ( 'error', $error_all );
		} else {

			$successfully = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file successfully.' );

			$success_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Successfully : ' ) . $true;

			if (count ( $error_receipt ) > 0) { // loi ma phieu thu
				$er_streceip = $this->getContext ()
					->getI18N ()
					->__ ( 'Line' ) . $error_receipt_no;
			} else {
				$er_streceip = '';
			}

			$error_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Error : ' ) . $false;

			$error_all = $error_number . '' . $er_streceip;

			$this->getUser ()
				->setFlash ( 'notice', $successfully );
			$this->getUser ()
				->setFlash ( 'notice1', $success_number );
			$this->getUser ()
				->setFlash ( 'notice2', $error_all );
		}

		$this->redirect ( '@ps_receipts_student_import' );
	}

	
	/**
	 * Import Import số dư đầu kỳ -- Hàm cũ
	 */
	public function executeImportLastMonth(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_file = null;

		$import_receipt = $request->getParameter ( 'import_receipt' );

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
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
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );

		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );

		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_receipt[%s]' );
	}

	/**
	 * Import so du dau ky - Lưu
	 */
	public function executeImportLastMonthSave(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_file = null;

		$import_receipt = $request->getParameter ( 'import_receipt' );

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
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
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );

		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );

		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->getWidgetSchema ()
			->setNameFormat ( 'import_receipt[%s]' );

		/**
		 * * Save Import file excel **
		 */

		$import_filter_form = $request->getParameter ( 'import_receipt' );

		$this->formFilter->bind ( $request->getParameter ( 'import_receipt' ), $request->getFiles ( 'import_receipt' ) );

		// id truong hoc
		$ps_customer_id = $this->formFilter->getValue ( 'ps_customer_id' );

		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			if ($ps_customer_id != myUser::getPscustomerID ()) {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}
		}

		$conn = Doctrine_Manager::connection ();

		try {

			$conn->beginTransaction ();

			if ($this->formFilter->isValid ()) {

				$user_id = myUser::getUserId ();

				$file_classify = $this->getContext ()
					->getI18N ()
					->__ ( 'Import balance last month amount' );

				$file = $this->formFilter->getValue ( 'ps_file' );

				$filename = time () . $file->getOriginalName ();

				$file_link = 'PsReceipt' . '/' . 'CoSoDaoTao' . $ps_workplace_id . '/' . date ( 'Ym' );

				$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';

				$file->save ( $path_file . $filename );

				$objPHPExcel = PHPExcel_IOFactory::load ( $path_file . $filename );

				$provinceSheet = $objPHPExcel->setActiveSheetIndex ( 0 ); // Set sheet sáº½ Ä‘Æ°á»£c Ä‘á»�c dá»¯ liá»‡u

				$highestRow = $provinceSheet->getHighestRow (); // Láº¥y sá»‘ row lá»›n nháº¥t trong sheet

				$highestColumnLetter = $worksheet->getHighestColumn(); // Lấy số lượng cột cuối cùng

				//echo $highestColumnLetter;die;

				$array_error = $error_receipt = array ();

				$error_text = '';
				
				$false = 0;

				$true = 0;

				for($row = 6; $row <= $highestRow; $row ++) {

					$student_code = $provinceSheet->getCellByColumnAndRow ( 1, $row )
						->getValue ();

					$receipt_no = $provinceSheet->getCellByColumnAndRow ( 3, $row )
						->getValue (); // ma phieu thu

					if ($student_code != '' && $receipt_no != '') { // ma hoc sinh va ma phieu thu khong duoc trong

						$receipt = Doctrine_Core::getTable ( 'Receipt' )->checkStudentAndReceiptNo ( $student_code, $receipt_no );
						
						// Neu ton tai va chua thanh toan thi cho import khoan du dau ky
						if ($receipt && $receipt->getPaymentStatus() == 0) {
							$student_id = $receipt->getStudentId ();
							$total_amount = Doctrine_Core::getTable ( 'PsFeeReports' )->checkFeeReportsOfMonth ( $student_id, $receipt->getReceiptDate () );
							
							$balance_last_month_amount_old = $receipt->getBalanceLastMonthAmount();
							
							if ($total_amount) {

								if($balance_last_month_amount_old != 0){
									$error_text .= $row.'; ';
								}
								
								// so tien du dau ky
								$balance_last_month_amount = $provinceSheet->getCellByColumnAndRow ( 4, $row )->getCalculatedValue (); // So tien nop

								if (is_numeric ( $balance_last_month_amount )) {

									$true ++;

									// Phai nop = Tong ban dau - so du dau ky
									$so_tien_phai_nop = $total_amount->getReceivable () - $balance_last_month_amount;

									// echo $so_tien_phai_nop; die;

									$total_amount->setReceivable ( $so_tien_phai_nop );
									$total_amount->save ();

									// Tien du = So thanh toan - so tien phai nop
									$balance_amount = $receipt->getCollectedAmount () - $so_tien_phai_nop;

									$balance_last_month_amount_new = $balance_last_month_amount + $balance_last_month_amount_old;
									
									$receipt->setBalanceLastMonthAmount ( $balance_last_month_amount_new );

									$receipt->setBalanceAmount ( $balance_amount );

									$receipt->setUserUpdatedId ( $user_id );

									$receipt->save ();
								} else {
									$false ++;
									array_push ( $error_receipt, $row );
								}
							} else {
								$false ++;
								array_push ( $error_receipt, $row );
							}
						} else {
							$false ++;
							array_push ( $error_receipt, $row );
						}
					}
				}

				if ($true > 0) {
					// luu lich su import file phieu ghi no
					$ps_history_import = new PsHistoryImport ();
					$ps_history_import->setPsCustomerId ( $ps_customer_id );
					$ps_history_import->setPsWorkplaceId ( null );
					$ps_history_import->setFileName ( $filename );
					$ps_history_import->setFileLink ( $file_link );
					$ps_history_import->setFileClassify ( $file_classify );
					$ps_history_import->setUserCreatedId ( $user_id );

					$ps_history_import->save ();
				} else {
					unlink ( $path_file . $filename );
				}

				$error_receipt_no = implode ( ' ; ', $error_receipt );
			} else {
				$error_import = $this->getContext ()
					->getI18N ()
					->__ ( 'formFilter->isValid ().' );
				$this->getUser ()
					->setFlash ( 'error', $error_import );
				$this->redirect ( '@ps_receipts_last_month_import' );
			}

			$conn->commit ();
		} catch ( Exception $e ) {
			unlink ( $path_file . $filename );
			$conn->rollback ();
		}

		if ($false == 0 && $true > 0) {
			$successfully = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file successfully %value% data. No error student code', array (
					'%value%' => $true ) );
			$this->getUser ()
				->setFlash ( 'notice', $successfully );
		} elseif ($true == 0) {

			if (count ( $error_receipt ) > 0) { // loi ma phieu thu
				$er_streceip = $this->getContext ()
					->getI18N ()
					->__ ( 'Line' ) . $error_receipt_no;
			} else {
				$er_streceip = '';
			}

			$error_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Error : ' ) . $false;

			$error_all = $error_number . ' ; ' . $er_streceip;

			$this->getUser ()
				->setFlash ( 'error', $error_all );
		} else {

			$successfully = $this->getContext ()
				->getI18N ()
				->__ ( 'Import file successfully.' );

			$success_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Successfully : ' ) . $true;

			if (count ( $error_receipt ) > 0) { // loi ma phieu thu
				$er_streceip = $this->getContext ()
					->getI18N ()
					->__ ( 'Line' ) . $error_receipt_no;
			} else {
				$er_streceip = '';
			}

			$error_number = $this->getContext ()
				->getI18N ()
				->__ ( 'Error : ' ) . $false;

			$error_all = $error_number . '<br/>' . $er_streceip;

			$this->getUser ()
				->setFlash ( 'notice', $successfully );
			$this->getUser ()
				->setFlash ( 'notice1', $success_number );
			$this->getUser ()
				->setFlash ( 'notice2', $error_all );
		}

		if($error_text != ''){
			$message_error_text = $this->getContext () ->getI18N () ->__ ( 'Error student import last month line' ).$error_text;
			$this->getUser ()->setFlash ( 'notice4', $message_error_text );
		}
		
		$this->redirect ( '@ps_receipts_last_month_import' );
	}

	/**
	 * Bieu mau import phieu du dau ky
	 */
	public function executeExportBalance(sfWebRequest $request) {

		$student_filters = $request->getParameter ( 'receipt_filters' );

		$ps_customer_id = $student_filters ['ps_customer_id'];

		$ps_workplace_id = $student_filters ['ps_workplace_id'];

		$ps_class_id = $student_filters ['ps_class_id'];

		$ps_month = $student_filters ['ps_year_month'];

		$is_payment = $student_filters ['payment_status'];

		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			if ($ps_customer_id != myUser::getPscustomerID ()) {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}
		}

		$this->exportReportReceiptBalanceLastMonth ( $ps_customer_id, $ps_workplace_id, $ps_class_id, $ps_month, $is_payment );

		$this->redirect ( '@ps_receipts' );
	}

	protected function exportReportReceiptBalanceLastMonth($ps_customer_id, $ps_workplace_id, $ps_class_id, $ps_month, $is_payment) {

		$exportFile = new ExportStudentLogtimesReportHelper ( $this );

		$file_template_pb = 'ps_receipt_balance_last_month.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/uploads/export_data/' . $file_template_pb;

		if ($ps_customer_id <= 0) {
			$ps_customer_id = myUser::getPscustomerID ();
		}
		if ($is_payment == '') {
			$is_payment = 2;
		}

		$list_student = Doctrine::getTable ( 'Receipt' )->getListStudentFeeReceiptOfMonth ( $ps_customer_id, $ps_workplace_id, $ps_class_id, $is_payment, $ps_month );

		$school_name = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, $ps_class_id, $ps_workplace_id);

		// $school_name = Doctrine::getTable('PsCustomer')->findOneById($ps_customer_id);

		if ($ps_class_id > 0) {
			$head_name = $school_name->getClName ();

			$title_info = $this->getContext ()
				->getI18N ()
				->__ ( 'Balance last month in class %value% month %value1%', array (
					'%value%' => $head_name,
					'%value1%' => $ps_month ) );
		} else {
			$head_name = $school_name->getTitle ();

			$title_info = $this->getContext ()
				->getI18N ()
				->__ ( 'Balance last month in workplace %value% month %value1%', array (
					'%value%' => $head_name,
					'%value1%' => $ps_month ) );
		}

		$title_xls = 'PhieuDuDauKy_' . date ( 'Ym', strtotime ( '01-' . $ps_month ) );

		$exportFile->loadTemplate ( $path_template_file );

		$exportFile->setDataExportReceiptBalanceLastMonth ( $school_name, $title_info, $title_xls, $list_student );

		$exportFile->saveAsFile ( "PhieuDuDauKy" . date ( 'Ym', strtotime ( '01-' . $ps_month ) ) . ".xls" );
	}

	/**
	 * Xuat du lieu phieu thanh toan cua hoc sinh
	 */
	public function executeExportPayment(sfWebRequest $request) {

		$student_filters = $request->getParameter ( 'receipt_filters' );

		$ps_customer_id = $student_filters ['ps_customer_id'];

		$ps_workplace_id = $student_filters ['ps_workplace_id'];

		$ps_class_id = $student_filters ['ps_class_id'];

		$ps_month = $student_filters ['ps_year_month'];

		$is_payment = $student_filters ['payment_status'];

		// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			if ($ps_customer_id != myUser::getPscustomerID ()) {
				$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			}
		}

		$this->exportReportReceiptStudentStatistic ( $ps_customer_id, $ps_workplace_id, $ps_class_id, $ps_month, $is_payment );

		$this->redirect ( '@ps_receipts' );
	}

	protected function exportReportReceiptStudentStatistic($ps_customer_id, $ps_workplace_id, $ps_class_id, $ps_month, $is_payment) {

		$exportFile = new ExportStudentLogtimesReportHelper ( $this );

		$file_template_pb = 'ps_receipt_student_statistic.xls';

		$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/uploads/export_data/' . $file_template_pb;

		if ($ps_customer_id <= 0) {
			$ps_customer_id = myUser::getPscustomerID ();
		}
		if ($is_payment == '') {
			$is_payment = 2;
		}

		$list_student = Doctrine::getTable ( 'Receipt' )->getListStudentFeeReceiptOfMonth ( $ps_customer_id, $ps_workplace_id, $ps_class_id, $is_payment, $ps_month );

		$school_name = Doctrine::getTable ( 'MyClass' )->getInfoMyClassByCustomer ( $ps_customer_id, $ps_class_id, $ps_workplace_id );

		// $school_name = Doctrine::getTable('PsCustomer')->findOneById($ps_customer_id);

		if ($ps_class_id > 0) {
			$head_name = $school_name->getClName ();

			if ($is_payment == 0) { // chua thanh toan
				$title_info = $this->getContext ()
					->getI18N ()
					->__ ( 'List student no payment in class %value% month %value1%', array (
						'%value%' => $head_name,
						'%value1%' => $ps_month ) );
			} elseif ($is_payment == 1) { // da thanh toan
				$title_info = $this->getContext ()
					->getI18N ()
					->__ ( 'List student paymented in class %value% month %value1%', array (
						'%value%' => $head_name,
						'%value1%' => $ps_month ) );
			} else { // tat ca trang thai
				$title_info = $this->getContext ()
					->getI18N ()
					->__ ( 'Statistic receipt in class %value% month %value1%', array (
						'%value%' => $head_name,
						'%value1%' => $ps_month ) );
			}
		} else {
			$head_name = $school_name->getTitle ();

			if ($is_payment == 0) { // chua thanh toan
				$title_info = $this->getContext ()
					->getI18N ()
					->__ ( 'List student no payment in workplace %value% month %value1%', array (
						'%value%' => $head_name,
						'%value1%' => $ps_month ) );
			} elseif ($is_payment == 1) { // da thanh toan
				$title_info = $this->getContext ()
					->getI18N ()
					->__ ( 'List student paymented in workplace %value% month %value1%', array (
						'%value%' => $head_name,
						'%value1%' => $ps_month ) );
			} else { // tat ca trang thai
				$title_info = $this->getContext ()
					->getI18N ()
					->__ ( 'Statistic receipt in workplace %value% month %value1%', array (
						'%value%' => $head_name,
						'%value1%' => $ps_month ) );
			}
		}

		$title_xls = 'PHIEUTT_' . date ( 'Ym', strtotime ( '01-' . $ps_month ) );

		$exportFile->loadTemplate ( $path_template_file );

		$exportFile->setDataExportReceiptStudentStatistic ( $school_name, $title_info, $title_xls, $list_student );

		$exportFile->saveAsFile ( "PhieuThuCuaHocSinh" . ".xls" );
	}

	// Gui thong bao cho tung phu huynh
	public function executeNotication(sfWebRequest $request) {

		$receipt_id = $request->getParameter ( 'receipt_id' );
		$student_id = $request->getParameter ( 'student_id' );
		$user_id = myUser::getUserId();
		
		$student = Doctrine::getTable ( 'Student' )->getStudentByField ( $student_id,'first_name,last_name,student_code,ps_customer_id' );
		$receipt = Doctrine_Core::getTable ( 'Receipt' )->getReceiptByField ( $receipt_id,'id,number_push_notication,receipt_date' );
		
		if (! myUser::checkAccessObject ( $student, 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			echo $this->getContext ()
				->getI18N ()
				->__ ( 'Not roll data' );

			exit ( 0 );
		} else {

			$conn = Doctrine_Manager::connection ();

			try {
				
				$conn->beginTransaction ();

				if ($receipt) {
					$receipt->setNumberPushNotication ( $receipt->getNumberPushNotication () + 1 );
					$receipt->save ();
					$receipt_date = $receipt->getReceiptDate ();
					$student_name = $student->getFirstName () . ' ' . $student->getLastName ();
					$student_code = $student->getStudentCode ();

					$ps_customer_id = $student->getPsCustomerId ();

					$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getRelativeSentNotificationByStudent ( $ps_customer_id, null, $student_id );
					
					//lấy ra danh sách người thân của bé
					// $list_relative = Doctrine_Query::create ()->select ( 'student_id, relative_id' )
					// ->from ( 'RelativeStudent' )
					// ->where('student_id =?',$student_id)
					// ->execute ();
					
					// $relative_ids = []; // Mảng để chứa các giá trị relative_id
					
					// $relative_ids_str = '';
					
					// if($list_relative) {
						// foreach ($list_relative as $item) {
							// $relative_ids[] = $item['relative_id'];
						// }
						// //chuyển danh sách id người thân thành string ,
						// $relative_ids_str = implode(',', $relative_ids);
					// }
					
					
					if (count ( $list_received_id ) > 0) {
						
						$registrationIds_ios = array ();
						$registrationIds_android = array ();
						
						$relative_ids = [];
						$relative_ids_str = '';

						foreach ( $list_received_id as $user_nocation ) {

							if ($user_nocation->getNotificationToken () != '') {

								if ($user_nocation->getOsname () == PreSchool::PS_CONST_PLATFORM_IOS) {
									array_push ( $registrationIds_ios, $user_nocation->getNotificationToken () );
								} else {
									array_push ( $registrationIds_android, $user_nocation->getNotificationToken () );
								}
							}
							
							$relative_ids[] = $user_nocation->id;
						}
						$relative_ids_str = implode(',', $relative_ids);

						$psI18n = $this->getContext ()->getI18N ();
						if ((count ( $registrationIds_android ) > 0 || count ( $registrationIds_ios ) > 0)) {
							
							$setting = new \stdClass ();

							$setting->title = $psI18n->__ ( 'Notice of fee receipt' ) . date ( "m-Y", strtotime ( $receipt_date ) );

							$setting->subTitle = $psI18n->__ ( 'Notice of fee receipt of' ) . $student_name;

							$setting->tickerText = $psI18n->__ ( 'Fee receipt from KidsSchool.vn' );

							$content = $psI18n->__ ( 'Student' ) . ": " . $student_code . ' - ' . $student_name;

							$content .= $psI18n->__ ( 'Notice of fee receipt' ) . ": " . date ( "m-Y", strtotime ( $receipt_date ) ) . '. ';

							$setting->message = $content;

							$setting->lights = '1';
							$setting->vibrate = '1';
							$setting->sound = '1';
							$setting->smallIcon = 'ic_small_notification';
							$setting->smallIconOld = 'ic_small_notification_old';

							// Lay avatar nguoi gui thong bao
							$profile = $this->getUser ()
								->getGuardUser ()
								->getProfileShort ();

							if ($profile && $profile->getAvatar () != '') {

								$url_largeIcon = PreString::getUrlMediaAvatar ( $profile->getCacheData (), $profile->getYearData (), $profile->getAvatar (), '01' );

								$largeIcon = PsFile::urlExists ( $url_largeIcon ) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							} else {
								$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
							}

							$setting->largeIcon = $largeIcon;

							$setting->screenCode = PsScreenCode::PS_CONST_SCREEN_REPORT_FEE;
							$setting->itemId = '0';
							$setting->clickUrl = '';

							// Deviceid registration firebase
							if (count ( $registrationIds_ios ) > 0) {
								$setting->registrationIds = $registrationIds_ios;

								$notification = new PsNotification ( $setting );
								$result = $notification->pushNotification ( PreSchool::PS_CONST_PLATFORM_IOS );
							}

							if (count ( $registrationIds_android ) > 0) {
								
								$setting->registrationIds = $registrationIds_android;
								$notification = new PsNotification ( $setting );
								$result 	  = $notification->pushNotification ();
							}
							
							
						} // end sent notication
						$content_notif = $psI18n->__ ( 'Student' ) . ": " . $student_code . ' - ' . $student_name;

						$content_notif .= $psI18n->__ ( 'Notice of fee receipt' ) . ": " . date ( "m-Y", strtotime ( $receipt_date ) ) . '. ';
						
						$notifi = new PsCmsNotifications();
						$notifi-> setPsCustomerId($ps_customer_id);
						$notifi-> setTitle($psI18n->__ ( 'Notice of fee receipt' ) . date ( "m-Y", strtotime ( $receipt_date ) ));
						$notifi-> setDescription($content_notif);
						$notifi-> setIsStatus('sent');
						$notifi-> setDateAt(date('Y-m-d H:i:s'));
						$notifi-> setTextObjectReceived($relative_ids_str);
						$notifi-> setRootScreen('HocPhi');
						$notifi-> setUserCreatedId($user_id);
						$notifi-> save();
						
						$ps_cms_notification_id = $notifi->id;
						
						foreach ($list_received_id as $received) {
							
							$rece = new PsCmsReceivedNotification();
							$rece-> setPsCmsNotificationId($ps_cms_notification_id);
							$rece-> setUserId($received->id);
							$rece-> setIsRead(0);
							$rece-> setDateAt(date('Y-m-d H:i:s'));
							$rece-> setUserCreatedId($user_id);
							$rece-> setIsDelete(0);
							$rece-> save();
						}
					}
				}

				$conn->commit ();

				return $this->renderPartial ( 'psReceipts/load_number_notication', array ('receipt' => $receipt ) );
				
			} catch ( Exception $e ) {

				throw new Exception ( $e->getMessage () );

				$this->logMessage ( "ERROR GUI THONG BAO HOC PHI: " . $e->getMessage () );

				$conn->rollback ();

				echo $this->getContext ()->getI18N ()->__ ( 'No send notication was saved failed.' );

				exit ();
			}
		}
	}

	// Hien thi ra app phu huynh
	protected function executeBatchPublishReceipts(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		if (myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			$records = Doctrine_Query::create ()->from ( 'Receipt' )
				->whereIn ( 'id', $ids )
				->execute ();
		} else {
			$records = Doctrine_Query::create ()->from ( 'Receipt' )
				->whereIn ( 'id', $ids )
				->addWhere ( 'ps_customer_id =?', myUser::getPscustomerID () )
				->execute ();
		}

		foreach ( $records as $record ) {

			$record->setIsPublic ( 1 );

			$record->save ();
		}

		$this->getUser ()
			->setFlash ( 'notice', $this->getContext ()
			->getI18N ()
			->__ ( 'The selected items have been publish successfully.' ) );

		$this->redirect ( '@ps_receipts' );
	}

	// Gui thong bao cho nhieu phu huynh
	protected function executeBatchPushNotication(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );

		if (myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
			$records = Doctrine_Query::create ()->from ( 'Receipt' )
				->whereIn ( 'id', $ids )
				->execute ();
		} else {
			$records = Doctrine_Query::create ()->from ( 'Receipt' )
				->whereIn ( 'id', $ids )
				->addWhere ( 'ps_customer_id =?', myUser::getPscustomerID () )
				->execute ();
		}

		$true = 0;
		
		$student_ids = array();
		
		$user_id = myUser::getUserId();
		
		foreach ( $records as $key => $record ) {

			if ($key == 0) { // chi lay 1 lan
				$ps_customer_id = $record->getPsCustomerId ();
				$receipt_date = $record->getReceiptDate ();
			}

			if ($record->getIsPublic () > 0) { // neu trang thai cho phu huynh xem thi moi gui thong bao

				$student_id = $record->getStudentId ();

				array_push($student_ids, $student_id);
				
				$record->setNumberPushNotication ( $record->getNumberPushNotication () + 1 );

				$record->save ();

				$true ++;
				/*
				$student = Doctrine_Core::getTable ( 'Student' )->getStudentByField ( $student_id,'first_name,last_name,student_code,ps_customer_id' );

				$student_name = $student->getFirstName () . ' ' . $student->getLastName ();

				$student_code = $student->getStudentCode ();

				$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getRelativeSentNotificationByStudent ( $ps_customer_id, null, $student_id );

				if (count ( $list_received_id ) > 0) {

					$registrationIds_ios = array ();
					$registrationIds_android = array ();

					foreach ( $list_received_id as $user_nocation ) {

						if ($user_nocation->getNotificationToken () != '') {

							if ($user_nocation->getOsname () == 'IOS') {
								array_push ( $registrationIds_ios, $user_nocation->getNotificationToken () );
							} else {
								array_push ( $registrationIds_android, $user_nocation->getNotificationToken () );
							}
						}
					}

					$psI18n = $this->getContext ()
						->getI18N ();
					if ((count ( $registrationIds_android ) > 0 || count ( $registrationIds_ios ) > 0)) {

						$setting = new \stdClass ();

						$setting->title = $psI18n->__ ( 'Notice of fee receipt' ) . date ( "m-Y", strtotime ( $receipt_date ) );

						$setting->subTitle = $psI18n->__ ( 'Notice of fee receipt of' ) . $student_name;

						$setting->tickerText = $psI18n->__ ( 'Fee receipt from KidsSchool.vn' );

						$content = $psI18n->__ ( 'Student' ) . ": " . $student_code . ' - ' . $student_name;

						$content .= $psI18n->__ ( 'Notice of fee receipt' ) . ": " . date ( "m-Y", strtotime ( $receipt_date ) ) . '. ';

						$setting->message = $content;

						$setting->lights = '1';
						$setting->vibrate = '1';
						$setting->sound = '1';
						$setting->smallIcon = 'ic_small_notification';
						$setting->smallIconOld = 'ic_small_notification_old';

						// Lay avatar nguoi gui thong bao
						$profile = $this->getUser ()
							->getGuardUser ()
							->getProfileShort ();

						if ($profile && $profile->getAvatar () != '') {

							$url_largeIcon = PreString::getUrlMediaAvatar ( $profile->getCacheData (), $profile->getYearData (), $profile->getAvatar (), '01' );

							$largeIcon = PsFile::urlExists ( $url_largeIcon ) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
						} else {
							$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
						}

						$setting->largeIcon = $largeIcon;

						$setting->screenCode = PsScreenCode::PS_CONST_SCREEN_REPORT_FEE;
						$setting->itemId = '0';
						$setting->clickUrl = '';

						// Deviceid registration firebase
						if (count ( $registrationIds_ios ) > 0) {
							$setting->registrationIds = $registrationIds_ios;

							$notification = new PsNotification ( $setting );
							$result = $notification->pushNotification ( PreSchool::PS_CONST_PLATFORM_IOS );
						}

						if (count ( $registrationIds_android ) > 0) {
							$setting->registrationIds = $registrationIds_android;

							$notification = new PsNotification ( $setting );
							$result = $notification->pushNotification ();
						}
					} // end sent notication
				}
				*/
			}
			
		}
		if(count($student_ids) > 0){
			$list_received_id = Doctrine::getTable ( 'sfGuardUser' )->getRelativeSentNotificationByStudent ( $ps_customer_id, null, $student_ids );
		}else{ 
			$list_received_id = array(); 
		}
		if (count ( $list_received_id ) > 0) {
			
			$registrationIds_ios = array ();
			
			$registrationIds_android = array ();
			
			$psI18n = $this->getContext ()->getI18N ();
			
			$relative_ids = [];
						
			$relative_ids_str = '';
			
			foreach ($list_received_id as $user_nocation ) {
				
				if ($user_nocation->getNotificationToken () != '') {
					
					$setting = new \stdClass ();
					
					$setting->title = $psI18n->__ ( 'Notice of fee receipt' ) . date ( "m-Y", strtotime ( $receipt_date ) );
					
					$setting->subTitle = $psI18n->__ ( 'Notice of fee receipt of' ) . $user_nocation->getStudentName();
					
					$setting->tickerText = $psI18n->__ ( 'Fee receipt from KidsSchool.vn' );
					
					$content = $psI18n->__ ( 'Student' ) . ": " . $user_nocation->getStudentCode() . ' - ' . $user_nocation->getStudentName();
					
					$content .= $psI18n->__ ( 'Notice of fee receipt' ) . ": " . date ( "m-Y", strtotime ( $receipt_date ) ) . '. ';
					
					$setting->message = $content;
					
					$setting->lights = '1';
					$setting->vibrate = '1';
					$setting->sound = '1';
					$setting->smallIcon = 'ic_small_notification';
					$setting->smallIconOld = 'ic_small_notification_old';
					
					// Lay avatar nguoi gui thong bao
					$profile = $this->getUser ()->getGuardUser ()->getProfileShort ();
					
					if ($profile && $profile->getAvatar () != '') {
						
						$url_largeIcon = PreString::getUrlMediaAvatar ( $profile->getCacheData (), $profile->getYearData (), $profile->getAvatar (), '01' );
						
						$largeIcon = PsFile::urlExists ( $url_largeIcon ) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
					} else {
						$largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
					}
					
					$setting->largeIcon = $largeIcon;
					
					$setting->screenCode = PsScreenCode::PS_CONST_SCREEN_REPORT_FEE;
					$setting->itemId = '0';
					$setting->clickUrl = '';
					
					if ($user_nocation->getOsname () == PreSchool::PS_CONST_PLATFORM_IOS ) {
						
						$setting->registrationIds = $user_nocation->getNotificationToken ();
						
						$notification = new PsNotification ( $setting );
						$result = $notification->pushNotification ( PreSchool::PS_CONST_PLATFORM_IOS );
						
					}else{
						
						$setting->registrationIds = $user_nocation->getNotificationToken ();
						
						$notification = new PsNotification ( $setting );
						$result = $notification->pushNotification ();
						
					}
				}
				
				$relative_ids_str = $user_nocation->id;
				
				$content_notif = $psI18n->__( 'Student' ) . ": " . $user_nocation->getStudentCode() . ' - ' . $user_nocation->getStudentName();
				$content_notif .= $psI18n->__( 'Notice of fee receipt' ) . ": " . date ( "m-Y", strtotime ( $receipt_date ) ) . '. ';
				
				$notifi = new PsCmsNotifications();
				$notifi-> setPsCustomerId($ps_customer_id);
				$notifi-> setTitle($psI18n->__ ( 'Notice of fee receipt' ) . date ( "m-Y", strtotime ( $receipt_date ) ));
				$notifi-> setDescription($content_notif);
				$notifi-> setIsStatus('sent');
				$notifi-> setDateAt(date('Y-m-d H:i:s'));
				$notifi-> setTextObjectReceived($relative_ids_str);
				$notifi-> setRootScreen('HocPhi');
				$notifi-> setUserCreatedId($user_id);
				$notifi-> save();
				
				$ps_cms_notification_id = $notifi->id;
					
				$rece = new PsCmsReceivedNotification();
				$rece-> setPsCmsNotificationId($ps_cms_notification_id);
				$rece-> setUserId($user_nocation->id);
				$rece-> setIsRead(0);
				$rece-> setDateAt(date('Y-m-d H:i:s'));
				$rece-> setUserCreatedId($user_id);
				$rece-> setIsDelete(0);
				$rece-> save();
				
			}
			
		}
		
		if ($true == 0) {
			$this->getUser ()
				->setFlash ( 'error', $this->getContext ()
				->getI18N ()
				->__ ( 'You must at least select one item is public' ) );
		} else {
			$this->getUser ()
				->setFlash ( 'notice', $this->getContext ()
				->getI18N ()
				->__ ( 'The selected items have been send notication successfully.' ) );
		}
		$this->redirect ( '@ps_receipts' );
	}

	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );

		if ($form->isValid ()) {

			$notice = $form->getObject ()
				->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';

			try {

				// Luu history
				if (! $form->getObject ()
					->isNew ()) { // Neu la sua

					$receipt_id = $form->getObject ()
						->getId ();

					$ps_receipt = Doctrine::getTable ( 'Receipt' )->findOneById ( $receipt_id );

					if ($ps_receipt) {

						// Lay phieu bao
						$ps_fee_reports = Doctrine::getTable ( 'PsFeeReports' )->findPsFeeReportOfStudentByDate ( $ps_receipt->getStudentId (), strtotime ( $ps_receipt->getReceiptDate () ) );

						if ($ps_fee_reports) {
							$ps_fee_reports->setReceivable ( $form->getValue ( 'ps_fee_report_amount' ) );
							$ps_fee_reports->save ();

							// KhĂ´ng lÆ°u receivable_students vĂ¬ khĂ´ng edit table nĂ y
							$this->saveHistoryReceipt ( $ps_receipt, $ps_fee_reports, null, 'edit' );
						}
					}
				}

				$receipt = $form->save ();

				// Láº¥y phiáº¿u bĂ¡o
				// $psFeeReport = Doctrine::getTable ( 'PsFeeReports' )->findPsFeeReportOfStudentByDate ( $receipt->getStudentId (), strtotime ( $receipt->getReceiptDate () ) );
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
					'object' => $receipt ) ) );

			if ($request->hasParameter ( '_save_and_add' )) {

				$this->getUser ()
					->setFlash ( 'notice', $notice . ' You can add another one below.' );

				$this->redirect ( '@ps_receipts_new' );
			} else {

				$this->getUser ()
					->setFlash ( 'notice', $notice );

				$this->redirect ( array (
						'sf_route' => 'ps_receipts_edit',
						'sf_subject' => $receipt ) );
			}
		} else {

			$this->getUser ()
				->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
		}
	}

	/**
	 * Ham lÆ°u history phieu bĂ¡o, phiáº¿u thu, cĂ¡c khoáº£n pháº£i thu cá»§a phiáº¿u
	 *
	 * @param Object $ps_receipt
	 * @param Object $ps_fee_reports
	 * @param
	 *        	List Object $receivable_students
	 * @param string $action:
	 *        	edit; delete
	 * @return boolean
	 */
	public function saveHistoryReceipt($ps_receipt, $ps_fee_reports, $receivable_students = null, $action = 'edit') {

		if (! $ps_receipt || ! $ps_fee_reports) {
			return false;
		}

		$history_content = '';

		$history_content .= 'ID truong: ' . $ps_receipt->getPsCustomerId () . '\n';

		$history_content .= 'ID phieu bao: ' . $ps_fee_reports->getId () . '\n';
		$history_content .= 'Ma phieu bao: ' . $ps_fee_reports->getPsFeeReportNo () . '\n';
		$history_content .= 'Tong tien phai nop(chua co phi nop muon): ' . $ps_fee_reports->getReceivable () . '\n';

		$history_content .= 'ID phieu thu: ' . $receipt_id . '\n';
		$history_content .= 'Tieu de: ' . $ps_receipt->getTitle () . '\n';
		$history_content .= 'Ma phieu thu: ' . $ps_receipt->getReceiptNo () . '\n';
		$history_content .= 'Phieu cua thang: ' . $ps_receipt->getReceiptDate () . '\n';
		$history_content .= 'So tien da nop: ' . $ps_receipt->getCollectedAmount () . '\n';
		$history_content .= 'So du: ' . $ps_receipt->getBalanceAmount () . '\n';
		$history_content .= 'Du thang truoc: ' . $ps_receipt->getBalanceLastMonthAmount () . '\n';
		$history_content .= 'Phi nop muon: ' . $ps_receipt->getLatePaymentAmount () . '\n';
		$history_content .= 'La import(1-Import; 0-Khong): ' . $ps_receipt->getIsImport () . '\n';
		$history_content .= 'Trang thai thanh toan: ' . $ps_receipt->getPaymentStatus () . '\n';
		$history_content .= 'ID nguoi than nop tien: ' . $ps_receipt->getRelativeId () . '\n';
		$history_content .= 'Ten nguoi nop tien: ' . $ps_receipt->getPaymentRelativeName () . '\n';
		$history_content .= 'Ngay nop tien: ' . $ps_receipt->getPaymentDate () . '\n';
		$history_content .= 'Hinh thuc nop tien(TM: Tien mat ,CK: Chuyen khoan,QT: Quet the): ' . $ps_receipt->getPaymentType () . '\n';
		$history_content .= 'Ten thu ngan: ' . $ps_receipt->getCashierName () . '\n';
		$history_content .= 'Ghi chu cua phieu: ' . $ps_receipt->getNote () . '\n';
		$history_content .= 'Hien thi ra APP phu huynh: ' . $ps_receipt->getIsPublic () . '\n';
		$history_content .= 'Ghi chu sua phieu thu truc tiep: ' . $ps_receipt->getNoteEdit () . '\n';
		$history_content .= 'ID user tao ban dau: ' . $ps_receipt->getUserCreatedId () . '\n';
		$history_content .= 'ID user cap nhat cuoi: ' . $ps_receipt->getUserUpdatedId () . '\n';

		$history_content .= 'DANH SACH CAC KHOAN THU CUA PHIEU:\n';

		foreach ( $receivable_students as $receivable_student ) {

			$history_content .= 'ID: ' . $rs_id . '\n';
			$history_content .= 'ID hoc sinh: ' . $receivable_student->getStudentId () . '\n';
			$history_content .= 'ID dich vu: ' . $receivable_student->getServiceId () . '\n';
			$history_content .= 'ID khoan phai thu khac: ' . $receivable_student->getReceivableId () . '\n';
			$history_content .= 'So luong du kien: ' . $receivable_student->getByNumber () . '\n';
			$history_content .= 'Gia: ' . $receivable_student->getUnitPrice () . '\n';
			$history_content .= 'Giam tru co dinh: ' . $receivable_student->getDiscountAmount () . '\n';
			$history_content .= 'Giam tru theo %: ' . $receivable_student->getDiscount () . '\n';
			$history_content .= 'So luong su dung: ' . $receivable_student->getSpentNumber () . '\n';
			$history_content .= 'Ve muon(0: Khong phai; 1: La dich vu ve muon): ' . $receivable_student->getIsLate () . '\n';
			$history_content .= 'So lan su dung: ' . $receivable_student->getIsNumber () . '\n';
			$history_content .= 'So tien: ' . $receivable_student->getAmount () . '\n';
			$history_content .= 'Thang phat sinh phi: ' . $receivable_student->getReceivableAt () . '\n';
			$history_content .= 'Tinh vao phieu thu cua thang: ' . $receivable_student->getReceiptDate () . '\n';
			$history_content .= 'Ghi chu: ' . $receivable_student->getNote () . '\n';
			$history_content .= 'ID nguoi tao: ' . $receivable_student->getUserCreatedId () . '\n';
			$history_content .= 'Ngay tao: ' . $receivable_student->getUserCreated () . '\n';
			$history_content .= 'ID nguoi cap nhat: ' . $receivable_student->getUserUpdatedId () . '\n';
			$history_content .= 'Ngay cap nhat: ' . $receivable_student->getUserUpdated () . '\n';
		}

		$ps_history_fees = new PsHistoryFees ();

		$ps_history_fees->setPsCustomerId ( $ps_receipt->getPsCustomerId () );
		$ps_history_fees->setPsReceiptId ( $ps_receipt->getId () );
		$ps_history_fees->setReceiptNo ( $ps_receipt->getReceiptNo () );
		$ps_history_fees->setReceiptDate ( $ps_receipt->getReceiptDate () );
		$ps_history_fees->setStudentId ( $ps_receipt->getStudentId () );
		$ps_history_fees->setPsAction ( $action );
		$ps_history_fees->setHistoryContent ( $history_content );
		$ps_history_fees->setCreatedAt ( date ( "Y-m-d H:i:s" ) );
		$ps_history_fees->setUpdatedAt ( date ( "Y-m-d H:i:s" ) );
		$ps_history_fees->setUserCreatedId ( sfContext::getInstance ()->getUser ()
			->getGuardUser ()
			->getId () );

		$ps_history_fees->save ();
	}


	public function executeAmountLastMonthExport(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$class_id = null;

		$ps_school_year_id = null;

		$export_filter = $request->getParameter ( 'export_filter' );

		if ($request->isMethod ( 'post' )) {

			$value_student_filter = $request->getParameter ( 'export_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];

			$class_id = $value_student_filter ['class_id'];

			$ps_month = $value_student_filter ['ps_month'];

			$this->exportFeeReceiptStudentSyntheticExport ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id, $class_id, $ps_month );
		}

		$this->ps_month = isset ( $value_student_filter ['ps_month'] ) ? $value_student_filter ['ps_month'] : date ( "m-Y" );

		// Lay nam hoc hien tai
		if ($ps_school_year_id == '') {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
		} else {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'id', $ps_school_year_id );
		}

		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );

		$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );

		$this->formFilter->setWidget ( 'ps_month', new sfWidgetFormChoice ( array (
				'choices' => PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) ) );

		// Lay thang hien tai

		$this->number_day = PsDateTime::psNumberDaysOfMonth ( $this->ps_month );

		$this->formFilter->setDefault ( 'ps_month', $this->ps_month );

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => true ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					
					//'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),					
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLPsCustomerByParams (),
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
		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		if ($this->ps_school_year_id == '') {
			$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId ();
		}

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

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
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );
		}

		if ($this->ps_workplace_id > 0) {

			// Filters by class
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id,
							'ps_school_year_id' => $this->ps_school_year_id ) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => false ) ) );
		} else {

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorPass () );
		}

		// echo $this->ps_workplace_id;

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'class_id', $this->class_id );

		$this->formFilter->setDefault ( 'ps_month', $this->ps_month );

		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'export_filter[%s]' );
	}

	protected function exportFeeReceiptStudentSyntheticExport($ps_school_year_id, $ps_customer_id, $ps_workplace_id, $class_id, $ps_month, $is_type) {


		if($is_type==1){

			$exportFile = new ExportStudentReportsHelper ( $this );

			$file_template_pb = 'bm_dangkydichvu.xls';

			$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/uploads/export_data/' . $file_template_pb;

			$list_service = Doctrine::getTable ( 'Service' )->getListServiceOfSchool2 ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id, $enable_schedule = 1 );
			// Lay danh sach cac khoan phai thu
			$receivable_params = array (
					'ps_school_year_id' => $ps_school_year_id,
					'ps_customer_id' => $ps_customer_id,
					'ps_workplace_id' => $ps_workplace_id,
					'is_activated' => PreSchool::ACTIVE );

			$list_receivables = Doctrine::getTable ( "Receivable" )->getListReceivableByParams ( $receivable_params );

			// Lay phieu thu cua thang duoc chon
			$date_ExportReceipt = $receivable_at = '01-' . $ps_month;

			$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );

			// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
			if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
				if ($ps_customer_id != myUser::getPscustomerID ()) {
					$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
				}
			}
			
			$all_data_fee = array ();

			// lay danh sach hoc sinh trong lop
			$list_student = Doctrine::getTable ( 'Student' )->getObjectStudentByClass ( $ps_customer_id, $class_id, $receivable_at, $ps_workplace_id );

			$exportFile->loadTemplate ( $path_template_file );

			$exportFile->exportRegisterService ( $list_student, $list_service, $list_receivables, $ps_month );

			$exportFile->saveAsFile ( "DangKyDichVu_" . date ( 'Ym', strtotime ( '01-' . $ps_month ) ) . ".xls" );

		}else{

			$exportFile = new ExportStudentReportsHelper ( $this );

			$file_template_pb = 'bm_sodudauky.xls';

			$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/uploads/export_data/' . $file_template_pb;

			$list_service = Doctrine::getTable ( 'Service' )->getListServiceOfSchool2 ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id, $enable_schedule = 1 );
			// Lay danh sach cac khoan phai thu
			$receivable_params = array (
					'ps_school_year_id' => $ps_school_year_id,
					'ps_customer_id' => $ps_customer_id,
					'ps_workplace_id' => $ps_workplace_id,
					'is_activated' => PreSchool::ACTIVE );

			$list_receivables = Doctrine::getTable ( "Receivable" )->getListReceivableByParams ( $receivable_params );

			// Lay phieu thu cua thang duoc chon
			$date_ExportReceipt = $receivable_at = '01-' . $ps_month;

			$int_date_ExportReceipt = PsDateTime::psDatetoTime ( $date_ExportReceipt );

			// kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
			if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {
				if ($ps_customer_id != myUser::getPscustomerID ()) {
					$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
				}
			}
			$ps_customer = Doctrine::getTable ( 'PsWorkPlaces' )->getWorkPlacesByWorkPlacesId ( $ps_workplace_id );
			// lay so tien phat nop hoc phi muon
			$psConfigLatePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplaceDate ( $ps_workplace_id, date ( 'Y-m-d' ) );

			$priceConfigLatePayment = 0;
			if ($psConfigLatePayment) {
				$priceConfigLatePayment = $psConfigLatePayment->getPrice ();
			}
			$ConfigStartDateSystemFee = $ps_customer->getConfigStartDateSystemFee ();

			$all_data_fee = array ();

			// lay danh sach hoc sinh trong lop
			$list_student = Doctrine::getTable ( 'Student' )->getObjectStudentByClass ( $ps_customer_id, $class_id, $receivable_at, $ps_workplace_id );

			$exportFile->loadTemplate ( $path_template_file );

			//$exportFile->setDataExportStatisticInfoExport ( $school_name, $title_info, $title_xls );

			$exportFile->setDataExportAmountLastMonth ( $list_student, $list_service, $list_receivables, $ps_month, $ConfigStartDateSystemFee );

			$exportFile->saveAsFile ( "SoDuDauKy_" . date ( 'Ym', strtotime ( '01-' . $ps_month ) ) . ".xls" );
		}

	}

	
	/**
	 * Import Import số dư đầu kỳ Import toàn bộ dịch vụ -- Mới
	 */
	public function executeImportAmountLastMonth2(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_file = null;

		$this->ps_month = date('m-Y');

		$import_receipt = $request->getParameter ( 'import_receipt' );

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
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
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		
		$yearsDefaultStart = date('Y-m-d', strtotime(date('Y-m-d'). ' -12 month'));
		$yearsDefaultEnd = date('Y-m-d', strtotime(date('Y-m-d'). ' +5 month'));

		$this->formFilter->setWidget ( 'ps_month', new sfWidgetFormChoice ( array (
				'choices' => PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) ) );

		$this->formFilter->setValidator ( 'ps_month', new sfValidatorPass () );

		$this->formFilter->setDefault ( 'ps_month', $this->ps_month );
		

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );

		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );

		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'import_receipt[%s]' );

		if ($request->isMethod('post')) {
            
            $this->formFilter->bind($request->getParameter('import_receipt'), $request->getFiles('import_receipt'));
            
            //print_r($this->formFilter);die;

            $import_receipt = $request->getParameter ( 'import_receipt' );

            $ps_customer_id = $import_receipt['ps_customer_id'];
            $ps_month = $import_receipt['ps_month'];
            //$ps_month = '04-2023';

            // Tháng thu phí
            $thangThuPhi = '01-'.$ps_month;

            // Chuyển đổi ngày tháng
            $receiptDate = date('Y-m-d',strtotime($thangThuPhi));

            $conn = Doctrine_Manager::connection();
            
            try {

                $conn->beginTransaction();
                
                if ($this->formFilter->isValid()) {

                    $user_id = myUser::getUserId();
                    
                    $file_classify = $this->getContext()->getI18N()->__('Import student');
                    $file = $this->formFilter->getValue('ps_file');
                    $filename = time() . $file->getOriginalName();
                    
                    $file_link = 'FeeReports' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );
					$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';
                    
                    $file->save($path_file . $filename);
                    // Xu ly excel
                    $objPHPExcel = PHPExcel_IOFactory::load($path_file . $filename);
                    $provinceSheet = $objPHPExcel->setActiveSheetIndex(0); // Set sheet sẽ được đọc dữ liệu
                    $highestRow = $provinceSheet->getHighestRow(); // Lấy số row lớn nhất trong sheet
                    
					$highestColumnLetter = $provinceSheet->getHighestColumn(); // Lấy số lượng cột cuối cùng

					$highestColumnNumber = PHPExcel_Cell::columnIndexFromString($highestColumnLetter);

					//echo $highestColumnNumber;die;


                    // Lấy tất cả học sinh trong trường 
                    $listStudents = Doctrine::getTable('Student')->getAllStudentsByCustomerId($ps_customer_id);
                    $array_student = array();
                    foreach($listStudents as $listStudent){
                    	$array_student[strtolower($listStudent->getStudentCode())] = $listStudent->getId();
                    }

					$array_student_service = array();

					$array_receipt = array();

					$count_row6 = 6;
					$count_row7 = 7;
					$_index = 0; $dem = 0;
                    for ($row = 9; $row <= $highestRow; $row++) {
                        
                        $student_code = PreString::trim($provinceSheet->getCellByColumnAndRow(1, $row)->getCalculatedValue());
                        
                        if(isset( $array_student[strtolower($student_code)]) and $array_student[strtolower($student_code)] > 0){

                        	$st_id = $array_student[strtolower($student_code)];
							
							
	                        $noDauKy = PreString::trim($provinceSheet->getCellByColumnAndRow(4, $row)->getCalculatedValue());
	                        $phaiThuTheoThongBao = PreString::trim($provinceSheet->getCellByColumnAndRow(5, $row)->getCalculatedValue());
	                        $hoanTra = PreString::trim($provinceSheet->getCellByColumnAndRow(6, $row)->getCalculatedValue());
	                        $chietKhau = PreString::trim($provinceSheet->getCellByColumnAndRow(7, $row)->getCalculatedValue());
	                        $thucTeThu = PreString::trim($provinceSheet->getCellByColumnAndRow(8, $row)->getCalculatedValue());
	                        $daThuTien = PreString::trim($provinceSheet->getCellByColumnAndRow(9, $row)->getCalculatedValue());
	                        $noCuoiKy = PreString::trim($provinceSheet->getCellByColumnAndRow(10, $row)->getCalculatedValue());

	                        $array_receipt[$_index]['student_id'] = $st_id;
	                        $array_receipt[$_index]['noDauKy'] = $noDauKy;
	                        $array_receipt[$_index]['phaiThuTheoThongBao'] = $phaiThuTheoThongBao;
	                        $array_receipt[$_index]['hoanTra'] = $hoanTra; // Tháng trước
	                        $array_receipt[$_index]['chietKhau'] = $chietKhau; // Tháng trước
	                        $array_receipt[$_index]['thucTeThu'] = $thucTeThu;
	                        $array_receipt[$_index]['daThuTien'] = $daThuTien;
	                        $array_receipt[$_index]['noCuoiKy'] = $noCuoiKy;
	                        
	                        for($index_column = 11; $index_column < $highestColumnNumber; $index_column++ ){

	                        	if(($index_column-11)%4 == 0){

		                        	$service_id = PreString::trim($provinceSheet->getCellByColumnAndRow($index_column, $count_row6)->getCalculatedValue());
		                        	
		                        	$service_type = PreString::trim($provinceSheet->getCellByColumnAndRow($index_column, $count_row7)->getCalculatedValue());

	                        	}
	                        	$giatri = PreString::trim($provinceSheet->getCellByColumnAndRow($index_column, $row)->getCalculatedValue());
	                        	if($service_id != ''){
	                        		$array_student_service[$student_code][$service_id]['service_type'] = $service_type;
	                        		$array_student_service[$student_code][$service_id][] = $giatri;
	                        	}
	                        }
	                        
	                        $_index++;
                        }
                        
                    }

                    
                    foreach($array_student_service as $st_code => $kieudicvu){

                    	if(isset( $array_student[strtolower($st_code)]) and $array_student[strtolower($st_code)] > 0){
                    		$student_id = $array_student[strtolower($st_code)];

                    		foreach($kieudicvu as $ser_id => $dichvu){

                    			$receivableStudent = new ReceivableStudent();

                    			if($dichvu[0] > 0) {  // Giá dịch vụ phải  > 0

	                    			if($dichvu['service_type'] == 1){ // Là dịch vụ cố định

	                    				$receivableStudent -> setStudentId($student_id);
	                    				$receivableStudent -> setServiceId($ser_id);
	                    				$receivableStudent -> setUnitPrice($dichvu[0]); // Giá
	                    				$receivableStudent -> setByNumber($dichvu[1]); // Số lượng
	                    				$receivableStudent -> setSpentNumber($dichvu[1]);
	                    				$receivableStudent -> setDiscountAmount($dichvu[2]); // Giảm trừ
	                    				$receivableStudent -> setAmount($dichvu[3]); // Phải thu
	                    				$receivableStudent -> setReceivableAt($receiptDate);
	                    				$receivableStudent -> setReceiptDate($receiptDate);
	                    				$receivableStudent -> setNumberMonth(1);
	                    				$receivableStudent -> setIsLate(0);
	                    				$receivableStudent -> setIsNumber(1);
	                    				$receivableStudent -> setUserCreatedId($user_id);
	                    				$receivableStudent -> setUserUpdatedId($user_id);
	                    				$receivableStudent -> save();


	                    			}elseif($dichvu['service_type'] == 2){ // Không cố định

	                    				$receivableStudent -> setStudentId($student_id);
	                    				$receivableStudent -> setServiceId($ser_id);
	                    				$receivableStudent -> setUnitPrice($dichvu[0]); // Giá
	                    				$receivableStudent -> setByNumber($dichvu[1]); // Số lượng dự kiến
	                    				$receivableStudent -> setSpentNumber($dichvu[2]);  // Số lượng SD
	                    				$receivableStudent -> setDiscountAmount(0); // Giảm trừ
	                    				$receivableStudent -> setAmount($dichvu[0]*$dichvu[2]); // Phải thu
	                    				$receivableStudent -> setReceivableAt($receiptDate);
	                    				$receivableStudent -> setReceiptDate($receiptDate);
	                    				$receivableStudent -> setNumberMonth(1);
	                    				$receivableStudent -> setIsLate(0);
	                    				$receivableStudent -> setIsNumber(1);
	                    				$receivableStudent -> setUserCreatedId($user_id);
	                    				$receivableStudent -> setUserUpdatedId($user_id);
	                    				$receivableStudent -> save();

	                    			}elseif($dichvu['service_type'] == 3){ // Khoản thu khác

	                    				$receivableStudent -> setStudentId($student_id);
	                    				$receivableStudent -> setReceivableId($ser_id);
	                    				$receivableStudent -> setUnitPrice($dichvu[0]); // Giá
	                    				$receivableStudent -> setByNumber($dichvu[1]); // Số lượng
	                    				$receivableStudent -> setSpentNumber($dichvu[1]);
	                    				$receivableStudent -> setDiscountAmount($dichvu[2]); // Giảm trừ
	                    				$receivableStudent -> setAmount($dichvu[3]); // Phải thu
	                    				$receivableStudent -> setReceivableAt($receiptDate);
	                    				$receivableStudent -> setReceiptDate($receiptDate);
	                    				$receivableStudent -> setNumberMonth(1);
	                    				$receivableStudent -> setIsLate(0);
	                    				$receivableStudent -> setIsNumber(1);
	                    				$receivableStudent -> setUserCreatedId($user_id);
	                    				$receivableStudent -> setUserUpdatedId($user_id);
	                    				$receivableStudent -> save();

	                    			}elseif($dichvu['service_type'] == -1){ // Về muộn

	                    				$receivableStudent -> setStudentId($student_id);
	                    				$receivableStudent -> setReceivableId(null);
	                    				$receivableStudent -> setUnitPrice($dichvu[0]); // Giá
	                    				$receivableStudent -> setByNumber(1); // Số lượng
	                    				$receivableStudent -> setSpentNumber(1);
	                    				$receivableStudent -> setDiscountAmount(0); // Giảm trừ
	                    				$receivableStudent -> setAmount($dichvu[0]); // Phải thu
	                    				$receivableStudent -> setReceivableAt($receiptDate);
	                    				$receivableStudent -> setReceiptDate($receiptDate);
	                    				$receivableStudent -> setNumberMonth(1);
	                    				$receivableStudent -> setIsLate(1);
	                    				$receivableStudent -> setIsNumber(1);
	                    				$receivableStudent -> setUserCreatedId($user_id);
	                    				$receivableStudent -> setUserUpdatedId($user_id);
	                    				$receivableStudent -> save();
	                    				
	                    			}

                    			}

                    		}

                    	}

                    }
                    

                    $conn->commit();

                }
            } catch (Exception $e) {
                $conn->rollback();
                $error_import = 'AAaa'.$e->getMessage() . $this->getContext()->getI18N()->__('Error try-catch.');
                $this->getUser()->setFlash('error', $error_import);
                $this->redirect('@ps_receipts_import_amount_last_month');
            }

            if($dem>0){
                $this->getUser()->setFlash('notice', "Import thành công ".$dem." dữ liệu vào hệ thống");
            }else{
                $this->getUser()->setFlash('warning', "Không có dữ liệu nào được thêm vào hệ thống");
            }

            $this->redirect('@ps_receipts_import_amount_last_month');
        }
       	
	}


	// Chỉ import dịch vụ không cố định
	public function executeImportAmountLastMonth(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_file = null;

		$this->ps_month = date('m-Y');

		$import_receipt = $request->getParameter ( 'import_receipt' );

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
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
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		
		$yearsDefaultStart = date('Y-m-d', strtotime(date('Y-m-d'). ' -12 month'));
		$yearsDefaultEnd = date('Y-m-d', strtotime(date('Y-m-d'). ' +5 month'));

		$this->formFilter->setWidget ( 'ps_month', new sfWidgetFormChoice ( array (
				'choices' => PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) ) );

		$this->formFilter->setValidator ( 'ps_month', new sfValidatorPass () );

		$this->formFilter->setDefault ( 'ps_month', $this->ps_month );
		

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );

		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );

		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'import_receipt[%s]' );

		if ($request->isMethod('post')) {
            
            $this->formFilter->bind($request->getParameter('import_receipt'), $request->getFiles('import_receipt'));
            
            //print_r($this->formFilter);die;

            $import_receipt = $request->getParameter ( 'import_receipt' );

            $ps_customer_id = $import_receipt['ps_customer_id'];
            $ps_month = $import_receipt['ps_month'];
            //$ps_month = '04-2023';

            // Tháng thu phí
            $thangThuPhi = '01-'.$ps_month;

            // Chuyển đổi ngày tháng
            $receiptDate = date('Y-m-d',strtotime($thangThuPhi));

            $conn = Doctrine_Manager::connection();
            
            try {

                $conn->beginTransaction();
                
                if ($this->formFilter->isValid()) {

                    $user_id = myUser::getUserId();
                    
                    $file_classify = $this->getContext()->getI18N()->__('Import student');
                    $file = $this->formFilter->getValue('ps_file');
                    $filename = time() . $file->getOriginalName();
                    
                    $file_link = 'FeeReports' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );
					$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';
                    
                    $file->save($path_file . $filename);
                    // Xu ly excel
                    $objPHPExcel = PHPExcel_IOFactory::load($path_file . $filename);
                    $provinceSheet = $objPHPExcel->setActiveSheetIndex(0); // Set sheet sẽ được đọc dữ liệu
                    $highestRow = $provinceSheet->getHighestRow(); // Lấy số row lớn nhất trong sheet
                    
					$highestColumnLetter = $provinceSheet->getHighestColumn(); // Lấy số lượng cột cuối cùng

					$highestColumnNumber = PHPExcel_Cell::columnIndexFromString($highestColumnLetter);

					//echo $highestColumnNumber;die;


                    // Lấy tất cả học sinh trong trường 
                    $listStudents = Doctrine::getTable('Student')->getAllStudentsByCustomerId($ps_customer_id);
                    $array_student = array();
                    foreach($listStudents as $listStudent){
                    	$array_student[strtolower($listStudent->getStudentCode())] = $listStudent->getId();
                    }

					$array_student_service = array();

					$array_receipt = array();

					$count_row5 = 5;
					$count_row6 = 6;
					$count_row7 = 7;
					$_index = 0; $sochungtu = $dem = 0;
                    for ($row = 8; $row <= $highestRow; $row++) {
                        
                        $student_code = PreString::trim($provinceSheet->getCellByColumnAndRow(1, $row)->getCalculatedValue());
                        
                        if(isset( $array_student[strtolower($student_code)]) and $array_student[strtolower($student_code)] > 0){

                        	$st_id = $array_student[strtolower($student_code)];
							
                        	$noDauKy = PreString::trim($provinceSheet->getCellByColumnAndRow(4, $row)->getCalculatedValue());
	                        $chietkhau_thangtruoc = PreString::trim($provinceSheet->getCellByColumnAndRow(5, $row)->getCalculatedValue());
	                        $hoantra_thangtruoc = PreString::trim($provinceSheet->getCellByColumnAndRow(6, $row)->getCalculatedValue());

	                        // Lọc các khoản dịch vụ
	                        for($index_column = 7; $index_column < $highestColumnNumber; $index_column++ ){
	                        	
	                        	if(($index_column-7)%2 == 0){

		                        	$service_id = PreString::trim($provinceSheet->getCellByColumnAndRow($index_column, $count_row6)->getCalculatedValue());
		                        	$service_title = PreString::trim($provinceSheet->getCellByColumnAndRow($index_column, $count_row5)->getCalculatedValue());

		                        	$array_student_service[$st_id][$service_id][-1] = $service_title;
	                        	}
	                        	$giatri = PreString::trim($provinceSheet->getCellByColumnAndRow($index_column, $row)->getCalculatedValue());
	                        	if($service_id != ''){
	                        		$array_student_service[$st_id][$service_id][] = $giatri;
	                        	}

	                        	
	                        }

	                        $array_receipt[$_index]['student_id'] = $st_id;
	                        $array_receipt[$_index]['noDauKy'] = $noDauKy;
	                        $array_receipt[$_index]['chietkhau_thangtruoc'] = $chietkhau_thangtruoc;
	                        $array_receipt[$_index]['hoantra_thangtruoc'] = $hoantra_thangtruoc;

	                        $_index++;
                        }
                        
                    }

                    // Tiến hành lưu vào database
                    // echo '<pre>';
                    // print_r($array_student_service);
                    // echo '</pre>';
                    // die;
                    foreach($array_receipt as $receipt){
                    	$dem++;
                    	
                    	$noDauKy = $receipt['noDauKy'] + $receipt['chietkhau_thangtruoc'] + $receipt['hoantra_thangtruoc'];
                    	if($noDauKy !=0){

                    		$sochungtu++;

                    		$psReceipt = new Receipt();

	                    	$psReceipt -> setStudentId($receipt['student_id']);
	                    	$psReceipt -> setTitle('PT:'.$receiptDate);
	                    	$psReceipt -> setReceiptNo('PT'.date('Ym',strtotime($receiptDate)).PreSchool::renderCode("%04s",$sochungtu));
	                    	$psReceipt -> setReceiptDate($receiptDate);
	                    	$psReceipt -> setReceiptNumber($sochungtu);
	                    	$psReceipt -> setCollectedAmount(0); // 
	                    	$psReceipt -> setBalanceAmount($noDauKy); // Số tiền phải thu
	                    	$psReceipt -> setChietkhauThangtruoc($receipt['chietkhau_thangtruoc']);
	                    	$psReceipt -> setHoantraThangtruoc($receipt['hoantra_thangtruoc']);
	                    	$psReceipt -> setBalanceLastMonthAmount($noDauKy);
	                    	$psReceipt -> setIsImport(1);
	                    	$psReceipt -> setUserCreatedId($user_id);
	                    	$psReceipt -> setUserUpdatedId($user_id);

	                    	$psReceipt -> save();

	                    	$receipt_id = $psReceipt -> getId();
	                    	
	                    	if($receipt_id > 0){

	                    		$psReceipt -> setReceiptNo('PT'.date('Ym',strtotime($receiptDate)).PreSchool::renderCode("%04s",$receipt_id));
		                    	$psReceipt -> save();

		                    	// Tổng báo phí = nợ đầu kỳ - phải thu theo thông báo 
		                    	$tong_bao_phi = $noDauKy;

		                    	// Lưu phiếu báo
		                    	$psFeeReports = new PsFeeReports ();
								$psFeeReports->setStudentId ( $receipt['student_id'] );
								$psFeeReports->setReceivable ( $tong_bao_phi );
								$prefix_code = 'PB' . date('Ym',strtotime($receiptDate));
								$psFeeReportNo = $prefix_code . '-' . PreSchool::renderCode ( "%04s", $sochungtu );	
								$psFeeReports->setPsFeeReportNo ( $psFeeReportNo );
								$psFeeReports->setReceiptNumber($sochungtu);
								$psFeeReports->setReceivableAt ( $receiptDate );
								$psFeeReports->setUserCreatedId ( $user_id);
								$psFeeReports->setUserUpdatedId ( $user_id);
								
								$psFeeReports->save ();
								
	                    	}
                    	}
                    	
                    }

                    foreach($array_student_service as $student_id => $kieudichvu){

                    	foreach($kieudichvu as $ser_id => $mangdv){
                    		//echo $ser_id.'__'.$soluong.'<br>';

                    		$sl_thua = $mangdv[0];
                    		$tien_thua = $mangdv[1];
                    		$tieude_dichvu = $mangdv[-1];
                    		if($tien_thua!=0){

                    			$dongia = $tien_thua / $sl_thua;

                    			// Thêm vào bảng khoản thu
                    			$receivableStudent = new ReceivableStudent ( false );
								
								$receivableStudent->setTitle ( $tieude_dichvu );
								$receivableStudent->setStudentId ( $student_id );
								$receivableStudent->setReceivableId ( null );
								$receivableStudent->setServiceId ( $ser_id );
								
								$receivableStudent->setDiscount ( 0 );
								$receivableStudent->setDiscountAmount ( 0 );
								
								$receivableStudent->setIsLate ( 0 ); // Ve muon
								
								$receivableStudent->setByNumber ( $sl_thua );
								$receivableStudent->setNumberMonth(1 );
								$receivableStudent->setUnitPrice ( ( float ) $dongia ); // Don gia
								$receivableStudent->setSpentNumber ( 0 ); // So luong su dung thuc te
								$receivableStudent->setAmount ( 0 ); 
								$receivableStudent->setHoantra ( 0 ); // Hoàn trả
								$receivableStudent->setNote ( 'Thừa tiền ăn' );
								$receivableStudent->setReceivableAt ($receiptDate );
								
								$receivableStudent->setReceiptDate ( $receiptDate );
								
								$receivableStudent->setUserCreatedId ( $user_id);
								
								$receivableStudent->setUserUpdatedId ($user_id);
								
								$receivableStudent->save ();
								
                			}
                    		
                    	}
                    }

                    $conn->commit();

                }
            } catch (Exception $e) {
                $conn->rollback();
                $error_import = 'AAaa'.$e->getMessage() . $this->getContext()->getI18N()->__('Error try-catch.');
                $this->getUser()->setFlash('error', $error_import);
                $this->redirect('@ps_receipts_import_amount_last_month');
            }

            if($dem>0){
                $this->getUser()->setFlash('notice', "Import thành công ".$dem." dữ liệu vào hệ thống");
            }else{
                $this->getUser()->setFlash('warning', "Không có dữ liệu nào được thêm vào hệ thống");
            }

            $this->redirect('@ps_receipts_import_amount_last_month');
        }
       	
	}

	// Xem cac lan su dung khoan thu
	public function executeViewReduce(sfWebRequest $request) {

		// ID học sinh
		$id_student = $request->getParameter ( 'sid' );
		$id_service = $request->getParameter ( 'rid' );
		$date_at = $request->getParameter ( 'date' );
		//echo $id_student.'__'.$id_service;
		if ($id_student <= 0) {
			$this->forward404Unless ( $id_student, sprintf ( 'Object does not exist.' ) );
		}

		$this->student = Doctrine::getTable ( 'Student' )->findOneBy ( 'id', $id_student );

		// $this->forward404Unless(myUser::checkAccessObject($this->student, 'PS_MEDICAL_GROWTH_FILTER_SCHOOL'), sprintf('Object does not exist.', $this->student));


		// Lấy danh sách giảm trừ
		$this->psStudentServiceReduce = Doctrine_Query::create()->from('PsStudentServiceReduce')
		->select('title, level, discount, is_type')
		->addWhere('student_id =?',$id_student)
		->andWhere('Service_id =?',$id_service)
		->andWhere('date_format(receivable_at,"%Y%m") =?',date('Ym',$date_at))
		->orderBy('level')
		->execute();

	}


	// Import đăng ký dịch vụ theo tháng
	public function executeImportRegisterService(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_file = null;

		$this->ps_month = date('m-Y');

		$import_receipt = $request->getParameter ( 'import_receipt' );

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
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
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		
		$yearsDefaultStart = date('Y-m-d', strtotime(date('Y-m').'-01'. ' -5 month'));
		$yearsDefaultEnd = date('Y-m-d', strtotime(date('Y-m').'-01'. ' +5 month'));
		//echo $yearsDefaultStart;
		//$yearsDefaultStart = "2023-06-01";
		//$yearsDefaultEnd = "2024-05-01";
		
		$this->formFilter->setWidget ( 'ps_month', new sfWidgetFormChoice ( array (
				'choices' => PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) ) );

		$this->formFilter->setValidator ( 'ps_month', new sfValidatorPass () );

		$this->formFilter->setDefault ( 'ps_month', $this->ps_month );
		

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );

		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );

		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'import_receipt[%s]' );

		if ($request->isMethod('post')) {
            
            $this->formFilter->bind($request->getParameter('import_receipt'), $request->getFiles('import_receipt'));
            
            //print_r($this->formFilter);die;

            $import_receipt = $request->getParameter ( 'import_receipt' );

            $ps_customer_id = $import_receipt['ps_customer_id'];
            $ps_month = $import_receipt['ps_month'];
            //$ps_month = '04-2023';

            // Tháng đăng ký dịch vụ
            $thangThuPhi = '01-'.$ps_month;

            // Chuyển đổi ngày tháng
            $receiptDate = date('Y-m-d',strtotime($thangThuPhi));

            $conn = Doctrine_Manager::connection();
            
            try {

                $conn->beginTransaction();
                
                if ($this->formFilter->isValid()) {

                    $user_id = myUser::getUserId();
                    
                    $file_classify = $this->getContext()->getI18N()->__('Import student');
                    $file = $this->formFilter->getValue('ps_file');
                    $filename = time() . $file->getOriginalName();
                    
                    $file_link = 'FeeReports' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );
					$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';
                    
                    $file->save($path_file . $filename);
                    // Xu ly excel
                    $objPHPExcel = PHPExcel_IOFactory::load($path_file . $filename);
                    $provinceSheet = $objPHPExcel->setActiveSheetIndex(0); // Set sheet sẽ được đọc dữ liệu
                    $highestRow = $provinceSheet->getHighestRow(); // Lấy số row lớn nhất trong sheet
                    
					$highestColumnLetter = $provinceSheet->getHighestColumn(); // Lấy số lượng cột cuối cùng

					$highestColumnNumber = PHPExcel_Cell::columnIndexFromString($highestColumnLetter);

					//echo $highestColumnNumber;die;


                    // Lấy tất cả học sinh trong trường 
                    $listStudents = Doctrine::getTable('Student')->getAllStudentsByCustomerId($ps_customer_id);
                    $array_student = array();
                    foreach($listStudents as $listStudent){
                    	$array_student[strtolower($listStudent->getStudentCode())] = $listStudent->getId();
                    }

					$array_student_service = array();

					$array_receipt = array();

					$count_row5 = 5;
					$count_row6 = 6;
					$_index = 0; $sochungtu = $dem = 0;
                    for ($row = 7; $row <= $highestRow; $row++) {
                        
                        $student_code = PreString::trim($provinceSheet->getCellByColumnAndRow(1, $row)->getCalculatedValue());
                        
                        if(isset( $array_student[strtolower($student_code)]) and $array_student[strtolower($student_code)] > 0){

                        	$st_id = $array_student[strtolower($student_code)];
							
	                        // Lọc các khoản dịch vụ
	                        for($index_column = 4; $index_column < $highestColumnNumber; $index_column++ ){
	                        	
	                        	$service_title = PreString::trim($provinceSheet->getCellByColumnAndRow($index_column, $count_row5)->getCalculatedValue());
	                        	$service_id = PreString::trim($provinceSheet->getCellByColumnAndRow($index_column, $count_row6)->getCalculatedValue());
	                        	

	                        	$danhdau = PreString::trim($provinceSheet->getCellByColumnAndRow($index_column, $row)->getCalculatedValue());

	                        	if($danhdau !='' and $service_id > 0){
	                        		//array_push(, $service_id);
	                        		$array_student_service[$st_id][] = $service_id;
	                        	}

	                        }

	                        $_index++;
                        }
                        
                    }

                    // Tiến hành lưu vào database
                    // echo '<pre>';
                    // print_r(array_keys( $array_student_service) );
                    // echo '</pre>';
                    // die;
                    

                    // Hủy toàn bộ dịch vụ đã đăng ký trước đó để đăng ký lại mới

                    Doctrine_Query::create()->update('StudentService')
				   	->set(array('delete_at' => $receiptDate, 'user_deleted_id'=>$user_id))
				   	->whereIn('student_id', array_keys( $array_student_service))
					->andWhere('delete_at !=""')
				   	->execute();

				   	$psRegularity = Doctrine_Query::create()->from('PsRegularity')->where('ps_customer_id =?',$ps_customer_id)
				   	->orderBy('is_default DESC')->fetchOne();
				   	$regularity_id = null;
				   	if($psRegularity){
				   		$regularity_id = $psRegularity->getId();
				   	}

                    foreach($array_student_service as $student_id => $iddichvu){

                    	foreach($iddichvu as $sid){

	                    	$studentService = new StudentService();

	                    	$studentService -> setServiceId($sid);
	                    	$studentService -> setStudentId($student_id);
	                    	$studentService -> setRegularityId($regularity_id);
	                    	$studentService -> setNumberMonth(1);
	                    	$studentService -> setDiscount(0);
	                    	$studentService -> setDiscountAmount(0);
	                    	$studentService -> setNote('Import');
	                    	$studentService -> setUserCreatedId ($user_id);
	                    	$studentService -> setUserUpdatedId($user_id);

	                    	$studentService -> save();

                    	}

                    }

                    $conn->commit();

                }
            } catch (Exception $e) {
                $conn->rollback();
                $error_import = 'AAaa'.$e->getMessage() . $this->getContext()->getI18N()->__('Error try-catch.');
                $this->getUser()->setFlash('error', $error_import);
                $this->redirect('@import_register_service');
            }

            if($dem>0){
                $this->getUser()->setFlash('notice', "Import thành công ".$dem." dữ liệu vào hệ thống");
            }else{
                $this->getUser()->setFlash('warning', "Không có dữ liệu nào được thêm vào hệ thống");
            }

            $this->redirect('@import_register_service');
        }
       	
	}

	public function executeExportRegisterService(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$class_id = null;

		$ps_school_year_id = null;

		$export_filter = $request->getParameter ( 'export_filter' );

		if ($request->isMethod ( 'post' )) {

			$value_student_filter = $request->getParameter ( 'export_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];

			$class_id = $value_student_filter ['class_id'];

			$ps_month = $value_student_filter ['ps_month'];

			$this->exportFeeReceiptStudentSyntheticExport ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id, $class_id, $ps_month, 1 );
		}

		$this->ps_month = isset ( $value_student_filter ['ps_month'] ) ? $value_student_filter ['ps_month'] : date ( "m-Y" );

		// Lay nam hoc hien tai
		if ($ps_school_year_id == '') {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
		} else {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'id', $ps_school_year_id );
		}

		$yearsDefaultStart = date ( "Y-m", strtotime ( $schoolYearsDefault->getFromDate () ) );

		$yearsDefaultEnd = date ( "Y-m", strtotime ( $schoolYearsDefault->getToDate () ) );

		$this->formFilter->setWidget ( 'ps_month', new sfWidgetFormChoice ( array (
				'choices' => PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) ) );

		// Lay thang hien tai

		$this->number_day = PsDateTime::psNumberDaysOfMonth ( $this->ps_month );

		$this->formFilter->setDefault ( 'ps_month', $this->ps_month );

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => true ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					
					//'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),					
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLPsCustomerByParams (),
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
		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		if ($this->ps_school_year_id == '') {
			$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId ();
		}

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

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
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );
		}

		if ($this->ps_workplace_id > 0) {

			// Filters by class
			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'MyClass',
					'query' => Doctrine::getTable ( 'MyClass' )->setClassByParams ( array (
							'ps_customer_id' => $this->ps_customer_id,
							'ps_workplace_id' => $this->ps_workplace_id,
							'ps_school_year_id' => $this->ps_school_year_id ) ),
					'add_empty' => _ ( '-Select class-' ) ), array (
					'class' => 'select2',
					'style' => "min-width:150px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorDoctrineChoice ( array (
					'model' => 'MyClass',
					'required' => false ) ) );
		} else {

			$this->formFilter->setWidget ( 'class_id', new sfWidgetFormChoice ( array (
					'choices' => array (
							'' => _ ( '-Select class-' ) ) ), array (
					'class' => 'select2',
					'style' => "min-width:200px;",
					'required' => false,
					'data-placeholder' => _ ( '-Select class-' ) ) ) );

			$this->formFilter->setValidator ( 'class_id', new sfValidatorPass () );
		}

		// echo $this->ps_workplace_id;

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->setDefault ( 'class_id', $this->class_id );

		$this->formFilter->setDefault ( 'ps_month', $this->ps_month );

		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'export_filter[%s]' );
	}
	
	
	// Import biểu mẫu xử lý phí
	public function executeImportTemplate(sfWebRequest $request) {

		$this->form = $this->configuration->getForm ();

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_file = null;

		$this->ps_month = date('m-Y');

		$import_receipt = $request->getParameter ( 'import_receipt' );

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => false ) ) );
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
		}

		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );
		
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
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );
		}

		$yearsDefaultStart = date('Y-m-d', strtotime(date('Y-m-d'). ' -12 month'));
		$yearsDefaultEnd = date('Y-m-d', strtotime(date('Y-m-d'). ' +5 month'));

		$this->formFilter->setWidget ( 'ps_month', new sfWidgetFormChoice ( array (
				'choices' => PsDateTime::psRangeMonthYear ( $yearsDefaultStart, $yearsDefaultEnd ) ), array (
				'class' => 'select2',
				'style' => "min-width:100px;",
				'required' => true,
				'placeholder' => _ ( '-Select month-' ),
				'rel' => 'tooltip',
				'data-original-title' => _ ( 'Select month' ) ) ) );

		$this->formFilter->setValidator ( 'ps_month', new sfValidatorPass () );

		$this->formFilter->setDefault ( 'ps_month', $this->ps_month );
		

		$upload_max_size = 2000; // KB
		$upload_max_size_byte = $upload_max_size * 1024; // bytes

		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile ( array (), array (
				'class' => 'form-control btn btn-default btn-success btn-psadmin',
				'style' => 'width:100%;' ) ) );

		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()
					->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );

		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );

		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'import_receipt[%s]' );

		if ($request->isMethod('post')) {
            
            $this->formFilter->bind($request->getParameter('import_receipt'), $request->getFiles('import_receipt'));
            
            //print_r($this->formFilter);die;

            $import_receipt = $request->getParameter ( 'import_receipt' );

            $ps_customer_id = $import_receipt['ps_customer_id'];
            $ps_workplace_id = $import_receipt['ps_workplace_id'];
            $ps_month = $import_receipt['ps_month'];
            //$ps_month = '04-2023';

            // Tháng đăng ký dịch vụ
            $thangThuPhi = '01-'.$ps_month;

            // Chuyển đổi ngày tháng
            $receiptDate = date('Y-m-d',strtotime($thangThuPhi));
				
			$date = new DateTime($receiptDate);
			$date->modify('-1 month');
			$thangtruoc = $date->format('Y-m-d');	
			
            $conn = Doctrine_Manager::connection();
            
            try {

                $conn->beginTransaction();
                
                if ($this->formFilter->isValid()) {

                    $user_id = myUser::getUserId();
                    
                    $file_classify = $this->getContext()->getI18N()->__('Import student');
                    $file = $this->formFilter->getValue('ps_file');
                    $filename = time() . $file->getOriginalName();
                    
                    $file_link = 'FeeReports' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );
					$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';
                    
                    $file->save($path_file . $filename);
                    // Xu ly excel
                    $objPHPExcel = PHPExcel_IOFactory::load($path_file . $filename);
                    
                    $provinceSheet = $objPHPExcel->setActiveSheetIndex(0); // Set sheet sẽ được đọc dữ liệu
                    $highestRow = $provinceSheet->getHighestRow(); // Lấy số row lớn nhất trong sheet
					$highestColumnLetter = $provinceSheet->getHighestColumn(); // Lấy số lượng cột cuối cùng
					$highestColumnNumber = PHPExcel_Cell::columnIndexFromString($highestColumnLetter);


					$list_class = Doctrine::getTable ( 'MyClass' ) -> getClassByCustomerGroup ( $ps_school_year_id, $ps_customer_id, $ps_workplace_id, null, null );
					$array_class = array();
					
					foreach ($list_class as $class){
						$array_class[strtolower($class->getCode())] = $class->getMcId() ;
					}
					
					// Danh sách mã ưu tiên
					$listPolicy = Doctrine_Query::create()->from( 'PsPolicyGroup' )->addwhere ('ps_customer_id=?', $ps_customer_id)
					->andWhere('ps_workplace_id=?',$ps_workplace_id)->execute();

					$array_policy = array();


					foreach($listPolicy as $policy){
						$array_policy[strtolower($policy->getPolicyCode())] = $policy->getId();
					}

					for($row = 4; $row <= $highestRow; $row ++) {
						
						$truonghopsuahocsinh = $error_code = 0;
						// Lay du lieu hoc sinh tu file
						$student_code = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 1, $row )->getCalculatedValue () );
						$ngayvaolop = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 8, $row )->getCalculatedValue () );
						/** 
						 * 	Truong hop sua thong tin hoc sinh va them moi phu huynh thong qua ma hoc sinh ($student_code)
						 *  Khong sua thong tin lop hoc cua hoc sinh
						 *  
						 **/
						if($student_code !=''){
							
							$records = Doctrine_Query::create ()->from ( 'Student' ) ->where ( 'student_code =?', $student_code )->fetchOne ();
							// Neu da ton tai ma hoc sinh
							if($records){
								// kiem tra xem co cung truong hay khong
								if($records -> getPsCustomerId() == $ps_customer_id){
									
									$suahocsinh ++;
									
									$truonghopsuahocsinh = 1;
									
									$student_name = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 2, $row )->getCalculatedValue () );
									$birthday_studen = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 3, $row ) ->getCalculatedValue () );
									$nick_name = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 4, $row ) ->getCalculatedValue () );
									$gioitinh = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 5, $row ) ->getCalculatedValue () );
									$diachi = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 6, $row ) ->getCalculatedValue () );
									
									$ma_lop = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 7, $row ) ->getCalculatedValue () );
									$hocthu7 = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 9, $row ) ->getCalculatedValue () );

									// end student
									
									if(PreString::trim($student_name) !=''){
										$array_name = PreString::getFullName ( $student_name );
										$first_name = $array_name ['first_name'];
										$last_name = $array_name ['last_name'];
									}
									
									// Neu de dinh dang là date
									if(is_numeric ($birthday_studen)){
										$receivable_date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($birthday_studen));
										if($receivable_date != '1970-01-01'){
											$date_student = true;
										}else {
											$date_student = false;
										}
									}else{ // Neu de dinh dang la text
										$receivable_date = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $birthday_studen ) ) ); // chuyển định dạng
										if ($receivable_date != '1970-01-01') { // Kiểm tra xem có đúng ngày không
											$date_student = true;
										} else {
											$date_student = false;
										}
									}
									
									if ($gioitinh != 0 && $gioitinh != 1) {
										$gioitinh = null;
									}
									
									// Neu de dinh dang là date
									if(is_numeric ($ngayvaolop)){
										$InvDate = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($ngayvaolop));
										if($InvDate != '1970-01-01'){
											$start_at = $InvDate;
										}else {
											$start_at = date ( 'Y-m-d' );
										}
									}else{ // Neu de dinh dang la text
										$ngaybatdau = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $ngayvaolop ) ) ); // chuyển định dạng
										if ($ngaybatdau != '1970-01-01') { // Kiểm tra xem có đúng ngày không
											$start_at = $ngaybatdau;
										} else {
											$start_at = date ( 'Y-m-d' );
										}
									}
									//echo $ngayvaolop;die;
									// Xet du lieu
									if($first_name !='' && $last_name != ''){
										$records -> setFirstName($first_name);
										$records -> setLastName($last_name);
									}
									if($nick_name !=''){
										$records -> setCommonName ( $nick_name );
									}
									if($date_student){
										$records -> setBirthday ($receivable_date);
									}
									if($gioitinh != null){
										$records -> setSex($gioitinh);
									}
									if($diachi !=''){
										$records ->setAddress($diachi);
									}

									$records ->setStartDateAt($start_at);

									$records -> save(); // luu du lieu
									
									$student_id = $records->getId();
									
									// Import phu huynh
									if (isset ( $student_id ) && $student_id > 0) {
										
										$i = 0;
										for($k = 10; $k < $highestColumnIndex; $k ++) {
											$start = $row;
											$k_name = $k + 2;
											// Lay ten
											$relative_name = $provinceSheet->getCellByColumnAndRow ( $k_name, $start )->getCalculatedValue ();
											
											if(PreString::trim($relative_name) !=''){
												
												$array_name = PreString::getFullName ( $relative_name );
												$fs_name_re = $array_name ['first_name'];
												$ls_name_re = $array_name ['last_name'];
												
												// Vai tro
												$vaitro = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
														->getCalculatedValue () );
												$k ++;
												// Nguoi bao tro chinh
												$is_main = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
														->getCalculatedValue () );
												
												if ($is_main != 0 && $is_main != 1) {
													$is_main = 0;
												}
												
												$k ++;
												// Cot nay lay ten
												$k ++;
												
												$sex_re = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )->getCalculatedValue () );
												
												if ($sex_re != 0 && $sex_re != 1) {
													$sex_re = null;
												}
												
												$k ++;
												$birthday_re = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
														->getCalculatedValue () );
												
												// Neu de dinh dang là date
												if(is_numeric ($birthday_re)){
													
													$re_birthday = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($birthday_re));
													
													if($re_birthday == '1970-01-01'){
														$re_birthday = null;
													}
													
												}else{ // Neu de dinh dang la text
													
													$re_birthday = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $birthday_re ) ) ); // chuyển định dạng
													
													if ($re_birthday == '1970-01-01') { // Kiểm tra xem có đúng ngày không
														$re_birthday = null;
													}
													
												}
												
												$k ++;
												$phone = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
														->getCalculatedValue () );
												$k ++;
												$email = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
														->getCalculatedValue () );
												$k ++;
												$job = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
														->getCalculatedValue () );
												$k ++;
												$address = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
														->getCalculatedValue () );
												
												// Luu vao DB
												if ($fs_name_re != '' && $ls_name_re != '') {
													
													$relative = new Relative ();
													
													$relative->setPsCustomerId ( $ps_customer_id );
													$relative->setPsWorkplaceId ( $ps_workplace_id );
													$relative->setFirstName ( $fs_name_re );
													$relative->setLastName ( $ls_name_re );
													$relative->setSex ( $sex_re );
													$relative->setYearData ( date ( 'Y' ) );
													$relative->setMobile ( $phone );
													$relative->setNationality ( 'VN' );
													$relative->setAddress ( $address );
													$relative->setBirthday ( $re_birthday );
													$relative->setJob ( $job );
													$relative->setUserCreatedId ( $user_id );
													$relative->setUserUpdatedId ( $user_id );
													
													$chk_email = true;
													
													if ($email != '') {
														$chk_email = false;
														if (psValidatorEmail::validEmail ( $email ) && psValidatorEmail::checkUniqueEmailPsMember ( $email, null, PreSchool::USER_TYPE_RELATIVE )) {
															$chk_email = true;
															$relative->setEmail ( $email );
														} else {
															// Lưu các địa chỉ email bị lỗi hoặc đã tồn tại
															array_push ( $error_email_relative, $email );
														}
													}
													
													$relative->save ();
													
													if ($relative->getId () > 0) {
														$nguoithan ++;
													}
													
													$quanhe = null;
													
													// neu hoc sinh import bi loi thi ko luu quan he, nhung van luu nguoi than
													
													if ($relative->getId () > 0 && isset ( $student_id ) && $student_id > 0) {
														
														// Chen vao bang Email
														if ($email != '' && $chk_email) {
															$ps_email = new PsEmails ();
															$ps_email->setPsEmail ( $email );
															$ps_email->setObjId ( $relative->getId () );
															$ps_email->setObjType ( PreSchool::USER_TYPE_RELATIVE );
															$ps_email->save ();
														}
														
														// Chen du lieu moi quan he - nguoi than
														if (in_array ( PreString::strLower ( $vaitro ), $array_relationship )) {
															
															foreach ( $array_relationship as $key => $relatives ) {
																
																if ($relatives == PreString::strLower ( $vaitro )) {
																	$quanhe = $key;
																	break;
																}
															}
														}
														
														if($quanhe > 0){ // Kiem tra moi quan he
															
															$relative_student = new RelativeStudent ();
															
															$relative_student->setStudentId ( $student_id );
															
															$relative_student->setRelativeId ( $relative->getId () );
															$relative_student->setRelationshipId ( $quanhe );
															$relative_student->setIsParent ( $is_main );
															$relative_student->setIsRole ( $is_main );
															$relative_student->setIsParentMain ( $is_main );
															$relative_student->setRoleService ( $is_main );
															$relative_student->setUserCreatedId ( $user_id );
															$relative_student->setUserUpdatedId ( $user_id );
															
															$relative_student->save ();
															
														}
													} else {
														// loi them quan he giua phu huynh va hoc sinh
														$er_relationship ++;
														array_push ( $relationship_error, $row );
													}
												}
											}else{
												$k=$k+8;
											}
										}
									}
								}
							}
						}
						
						if($truonghopsuahocsinh == 0){
							
							$student_name = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 2, $row )
									->getCalculatedValue () );
							
							if(PreString::trim($student_name) !=''){
							
								$array_name = PreString::getFullName ( $student_name );
								$first_name = $array_name ['first_name'];
								$last_name = $array_name ['last_name'];
		
								$birthday_studen = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 3, $row )
										->getCalculatedValue () );
								
								// Neu de dinh dang là date
								if(is_numeric ($birthday_studen)){
								
									$receivable_date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($birthday_studen)); 
									
									if($receivable_date != '1970-01-01'){
										$date_student = true;
									}else {
										$date_student = false;
									}
									
								}else{ // Neu de dinh dang la text
									
									$receivable_date = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $birthday_studen ) ) ); // chuyển định dạng
									
									if ($receivable_date != '1970-01-01') { // Kiểm tra xem có đúng ngày không
										$date_student = true;
									} else {
										$date_student = false;
									}
									
								}
								
								$ma_uutien = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 4, $row )
										->getCalculatedValue () );
								$policy_id = null;
								if($ma_uutien!='' and $array_policy[strtolower($ma_uutien)] > 0 ){
									$policy_id = $array_policy[strtolower($ma_uutien)];
								}

								$gioitinh = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 5, $row )
										->getCalculatedValue () );
		
								if ($gioitinh != 0 && $gioitinh != 1) {
									$gioitinh = null;
								}
		
								$diachi = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 6, $row )
										->getCalculatedValue () );
		
								$class_id_import = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 7, $row )
									->getCalculatedValue () );
		
								$ngayvaolop = PreString::trim($provinceSheet->getCellByColumnAndRow ( 8, $row )->getCalculatedValue ());
								
								// Neu de dinh dang là date
								if(is_numeric ($ngayvaolop)){
								
									$InvDate = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($ngayvaolop)); 
									
									//echo $ngayvaolop.'_'.$InvDate.'<br/>';
									
									if($InvDate != '1970-01-01'){
										$start_at = $InvDate;
									}else {
										$start_at = date ( 'Y-m-d' );
									}
									
								}else{ // Neu de dinh dang la text
									
									$ngaybatdau = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $ngayvaolop ) ) ); // chuyển định dạng
									
									//echo $ngayvaolop.'1_'.$ngaybatdau.'<br/>';
									
									if ($ngaybatdau != '1970-01-01') { // Kiểm tra xem có đúng ngày không
										$start_at = $ngaybatdau;
									} else {
										$start_at = date ( 'Y-m-d' );
									}
									
								}
								
								$saturday_study = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( 9, $row )
									->getCalculatedValue () ); // Học thứ 7
		
								if ($saturday_study != 1) {
									$saturday_study = 0;
								}
								// end student
								
								if ($first_name != '' && $last_name != '') {
		
									if ($date_student) {
		
										$student_id = 0; // sau moi lan chen hoc sinh moi thi khoi tao lai
		
										$student = new Student ();
										
										if ($student_code != '') {
		
											$records = Doctrine_Query::create ()->from ( 'Student' )
												->where ( 'student_code =?', $student_code )
												->fetchOne ();
		
											if (! $records) {
												$student->setStudentCode ( $student_code );
											} else {
												$student->setStudentCode ( time () );
												$error_code = 1;
												array_push ( $er_student_code, $student_code );
											}
										} else {
											$error_code = 1;
											$student->setStudentCode ( time () );
										}
										
										$student->setPsCustomerId ( $ps_customer_id );
										$student->setPsWorkplaceId ( $ps_workplace_id );
										$student->setFirstName ( $first_name );
										$student->setLastName ( $last_name );
										$student->setBirthday ( $receivable_date );
										$student->setCommonName ( null );
										$student->setPolicyId($policy_id);
										$student->setYearData ( date ( 'Y' ) );
										$student->setSex ( $gioitinh );
										$student->setAddress ( $diachi );
										$student->setNationality ( 'VN' );
										$student->setIsImport ( 1 );
										$student->setUserCreatedId ( $user_id );
										$student->setUserUpdatedId ( $user_id );
		
										$student->save ();
		
										$student_id = $student->getId ();
		
										if ($student_id > 0) {
		
											$true ++;
											
											if ($error_code == 1) {
												$prefix_code = 'A';
												$renderCode = $prefix_code . PreSchool::renderCode ( "%04s", $student_id );
												$student->setStudentCode ( $renderCode );
												$student->save ();
											}
											
											if ($class_id_import != '' and $array_class[strtolower($class_id_import)] > 0) {
												$ps_class_id = $array_class[strtolower($class_id_import)];
											}else{
												$ps_class_id = 0;
											}
											
											//print_r($array_class);

											//echo $ps_class_id;die;

											// chuyen hoc sinh vao lop
											if ($ps_class_id > 0) {
		
												$student_class = new StudentClass ();
												$student_class->setStudentId ( $student_id );
												$student_class->setMyclassId ( $ps_class_id );
												$student_class->setIsActivated ( 1 );
												$student_class->setMyclassMode ( $saturday_study ); // hoc thu 7
												$student_class->setStartAt ( $start_at ); // ngay vao lop
												$student_class->setStopAt ( $stop_at );
												$student_class->setType ( PreSchool::SC_STATUS_OFFICIAL );
												$student_class->setFromMyclassId ( null );
												$student_class->setUserCreatedId ( $user_id );
												$student_class->setUserUpdatedId ( $user_id );
		
												$student_class->save ();
												
												$student->setStartDateAt($start_at);
												$student->setCurrentClassId($ps_class_id);
												$student->save();
											}
										} else {
											$student_id = 0; // neu hoc sinh bi sai thi khoi tao lai
											$number_student_error ++;
											array_push ( $arr_line_student_error, $row );
										}
									} else {
										$student_id = 0; // neu hoc sinh bi sai thi khoi tao lai
										$number_student_error ++;
										array_push ( $arr_line_student_error, $row );
									}
		
									// Import phu huynh
									if (isset ( $student_id ) && $student_id > 0) {
		
										$i = 0;
										for($k = 10; $k < $highestColumnIndex; $k ++) {
											$start = $row;
											$k_name = $k + 2;
											// Lay ten
											$relative_name = $provinceSheet->getCellByColumnAndRow ( $k_name, $start )->getCalculatedValue ();
											
											if(PreString::trim($relative_name) !=''){
												
												$array_name = PreString::getFullName ( $relative_name );
												$fs_name_re = $array_name ['first_name'];
												$ls_name_re = $array_name ['last_name'];
												
												// Vai tro
												$vaitro = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
														->getCalculatedValue () );
												$k ++;
												// Nguoi bao tro chinh
												$is_main = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
														->getCalculatedValue () );
												
												if ($is_main != 0 && $is_main != 1) {
													$is_main = 0;
												}
												
												$k ++;
												// Cot nay lay ten
												$k ++;
												
												$sex_re = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )->getCalculatedValue () );
												
												if ($sex_re != 0 && $sex_re != 1) {
													$sex_re = null;
												}
												
												$k ++;
												$birthday_re = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
													->getCalculatedValue () );
			
												// Neu de dinh dang là date
												if(is_numeric ($birthday_re)){
													
													$re_birthday = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($birthday_re));
													
													if($re_birthday == '1970-01-01'){
														$re_birthday = null;
													}
													
												}else{ // Neu de dinh dang la text
													
													$re_birthday = date ( 'Y-m-d', strtotime ( str_replace ( '/', '-', $birthday_re ) ) ); // chuyển định dạng
													
													if ($re_birthday == '1970-01-01') { // Kiểm tra xem có đúng ngày không
														$re_birthday = null;
													}
													
												}
												
												$k ++;
												$phone = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
													->getCalculatedValue () );
												$k ++;
												$email = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
													->getCalculatedValue () );
												$k ++;
												$job = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
													->getCalculatedValue () );
												$k ++;
												$address = PreString::trim ( $provinceSheet->getCellByColumnAndRow ( $k, $start )
													->getCalculatedValue () );
												
												// Luu vao DB
												if ($fs_name_re != '' && $ls_name_re != '') {
			
													$relative = new Relative ();
			
													$relative->setPsCustomerId ( $ps_customer_id );
													$relative->setPsWorkplaceId ( $ps_workplace_id );
													$relative->setFirstName ( $fs_name_re );
													$relative->setLastName ( $ls_name_re );
													$relative->setSex ( $sex_re );
													$relative->setYearData ( date ( 'Y' ) );
													$relative->setMobile ( $phone );
													$relative->setNationality ( 'VN' );
													$relative->setAddress ( $address );
													$relative->setBirthday ( $re_birthday );
													$relative->setJob ( $job );
													$relative->setUserCreatedId ( $user_id );
													$relative->setUserUpdatedId ( $user_id );
													
													$chk_email = true;
			
													if ($email != '') {
														$chk_email = false;
														if (psValidatorEmail::validEmail ( $email ) && psValidatorEmail::checkUniqueEmailPsMember ( $email, null, PreSchool::USER_TYPE_RELATIVE )) {
															$chk_email = true;
															$relative->setEmail ( $email );
														} else {
															// Lưu các địa chỉ email bị lỗi hoặc đã tồn tại
															array_push ( $error_email_relative, $email );
														}
													}
													
													$relative->save ();
			
													if ($relative->getId () > 0) {
														$nguoithan ++;
													}
			
													$quanhe = null;
			
													// neu hoc sinh import bi loi thi ko luu quan he, nhung van luu nguoi than
			
													if ($relative->getId () > 0 && isset ( $student_id ) && $student_id > 0) {
			
														// Chen vao bang Email
														// if ($email != '' && $chk_email) {
														// 	$ps_email = new PsEmails ();
														// 	$ps_email->setPsEmail ( $email );
														// 	$ps_email->setObjId ( $relative->getId () );
														// 	$ps_email->setObjType ( PreSchool::USER_TYPE_RELATIVE );
														// 	$ps_email->save ();
														// }
			
														// Chen du lieu moi quan he - nguoi than
														if (in_array ( PreString::strLower ( $vaitro ), $array_relationship )) {
			
															foreach ( $array_relationship as $key => $relatives ) {
			
																if ($relatives == PreString::strLower ( $vaitro )) {
																	$quanhe = $key;
																	break;
																}
															}
														}
														
														if($quanhe > 0){ // Kiem tra moi quan he
															
															$relative_student = new RelativeStudent ();
				
															$relative_student->setStudentId ( $student_id );
				
															$relative_student->setRelativeId ( $relative->getId () );
															$relative_student->setRelationshipId ( $quanhe );
															$relative_student->setIsParent ( $is_main );
															$relative_student->setIsRole ( $is_main );
															$relative_student->setIsParentMain ( $is_main );
															$relative_student->setRoleService ( $is_main );
															$relative_student->setUserCreatedId ( $user_id );
															$relative_student->setUserUpdatedId ( $user_id );
				
															$relative_student->save ();
															
														}
													} else {
														// loi them quan he giua phu huynh va hoc sinh
														$er_relationship ++;
														array_push ( $relationship_error, $row );
													}
												}
											}else{
												$k=$k+8;
											}
										}
									}
								}
							}
						}
					}
					
					// Lấy tất cả học sinh trong trường 
                    $listStudents = Doctrine::getTable('Student')->getAllStudentsByCustomerId($ps_customer_id);
                    $array_student = array();
                    foreach($listStudents as $listStudent){
                    	$array_student[strtolower($listStudent->getStudentCode())] = $listStudent->getId();
                    }
					// Sheet 1: Cập nhật ưu tiên
					$provinceSheet0 = $objPHPExcel->setActiveSheetIndex(1); // Set sheet sẽ được đọc dữ liệu
                    $highestRow0 = $provinceSheet0->getHighestRow(); // Lấy số row lớn nhất trong sheet
					$highestColumnLetter0 = $provinceSheet0->getHighestColumn(); // Lấy số lượng cột cuối cùng
					$highestColumnNumber0 = PHPExcel_Cell::columnIndexFromString($highestColumnLetter0);
					
					$array_student_policy = array();
					$demut=0;
					for ($row = 3; $row <= $highestRow0; $row++) {
						
						$student_code = PreString::trim($provinceSheet0->getCellByColumnAndRow(0, $row)->getCalculatedValue());
                        
                        if(isset( $array_student[strtolower($student_code)]) and $array_student[strtolower($student_code)] > 0){

                        	$st_id = $array_student[strtolower($student_code)];
							
							$ma_uutien = PreString::trim ( $provinceSheet0->getCellByColumnAndRow ( 3, $row )->getCalculatedValue () );
							$policy_id = null;
							if($ma_uutien!='' and $array_policy[strtolower($ma_uutien)] > 0 ){
								$policy_id = $array_policy[strtolower($ma_uutien)];
							}
							if($policy_id >0){
								$array_student_policy[$st_id] = $policy_id;
							}
						}
					}
					
					//print_r($array_student_policy);die;
					
					if(count($array_student_policy) > 0){
						// Cập nhật lại các mã ưu tiên
						Doctrine_Query::create()->update('Student')
						->set(array('policy_id' => ''))
						->execute();
						
						foreach($listStudents as $listStudent){
							
							if($array_student_policy[$listStudent->getId()] > 0 ){
								$demut++;
								$listStudent -> setPolicyId($array_student_policy[$listStudent->getId()]);
								$listStudent -> save();
							}
							
						}
					}
					
					// Sheet 2: Đăng ký dịch vụ
					$array_student_service = array();
					
                    $provinceSheet1 = $objPHPExcel->setActiveSheetIndex(2); // Set sheet sẽ được đọc dữ liệu
                    $highestRow1 = $provinceSheet1->getHighestRow(); // Lấy số row lớn nhất trong sheet
					$highestColumnLetter1 = $provinceSheet1->getHighestColumn(); // Lấy số lượng cột cuối cùng
					$highestColumnNumber1 = PHPExcel_Cell::columnIndexFromString($highestColumnLetter1);
					
                    $count_row2 = 2;
					$count_row3 = 3;
					$_index = 0; $sochungtu = $demdv = 0;
                    for ($row = 4; $row <= $highestRow1; $row++) {
                        
                        $student_code = PreString::trim($provinceSheet1->getCellByColumnAndRow(0, $row)->getCalculatedValue());
                        
                        if(isset( $array_student[strtolower($student_code)]) and $array_student[strtolower($student_code)] > 0){

                        	$st_id = $array_student[strtolower($student_code)];
							
	                        // Lọc các khoản dịch vụ
	                        for($index_column = 3; $index_column < $highestColumnNumber1; $index_column++ ){
	                        	
	                        	$service_title = PreString::trim($provinceSheet1->getCellByColumnAndRow($index_column, $count_row2)->getCalculatedValue());
	                        	$service_id = PreString::trim($provinceSheet1->getCellByColumnAndRow($index_column, $count_row3)->getCalculatedValue());
	                        	

	                        	$danhdau = PreString::trim($provinceSheet1->getCellByColumnAndRow($index_column, $row)->getCalculatedValue());

	                        	if($danhdau !='' and $service_id > 0){
	                        		$array_student_service[$st_id][] = $service_id;
	                        	}

	                        }

	                        $_index++;
                        }
                        
                    }

                    // Hủy toàn bộ dịch vụ đã đăng ký trước của học sinh đó để đăng ký lại mới
					if(count($array_student_service) > 0){
						Doctrine_Query::create()->update('StudentService')
						->set(array('delete_at' => $thangtruoc, 'user_deleted_id'=>$user_id))
						->whereIn('student_id', array_keys($array_student_service))
						->andWhere('delete_at IS NULL')
						->execute();
					}

				   	$psRegularity = Doctrine_Query::create()->from('PsRegularity')->where('ps_customer_id =?',$ps_customer_id)
				   	->orderBy('is_default DESC')->fetchOne();
				   	$regularity_id = null;
				   	if($psRegularity){
				   		$regularity_id = $psRegularity->getId();
				   	}

                    foreach($array_student_service as $student_id => $iddichvu){
						$demdv++;
                    	foreach($iddichvu as $sid){

	                    	$studentService = new StudentService();

	                    	$studentService -> setServiceId($sid);
	                    	$studentService -> setStudentId($student_id);
	                    	$studentService -> setRegularityId($regularity_id);
	                    	$studentService -> setNumberMonth(1);
	                    	$studentService -> setDiscount(0);
	                    	$studentService -> setDiscountAmount(0);
	                    	$studentService -> setNote('Import');
	                    	$studentService -> setUserCreatedId ($user_id);
	                    	$studentService -> setUserUpdatedId($user_id);

	                    	$studentService -> save();

                    	}

                    }
					
					// Sheet 3: Thêm cấu hình giảm trừ
                    
                    $provinceSheet2 = $objPHPExcel->setActiveSheetIndex(3); // Set sheet sẽ được đọc dữ liệu
                    $highestRow2 = $provinceSheet2->getHighestRow(); // Lấy số row lớn nhất trong sheet
					$highestColumnLetter2 = $provinceSheet2->getHighestColumn(); // Lấy số lượng cột cuối cùng
					$highestColumnNumber2 = PHPExcel_Cell::columnIndexFromString($highestColumnLetter2);
					$demgt=0;
					for ($row = 4; $row <= $highestRow2; $row++) {
                        
                        $array_service_id = array();

                        $ma_giamtru = PreString::trim($provinceSheet2->getCellByColumnAndRow(0, $row)->getCalculatedValue());
                        $tieude_giamtru = PreString::trim($provinceSheet2->getCellByColumnAndRow(1, $row)->getCalculatedValue());
                        $kieu_giamtru = PreString::trim($provinceSheet2->getCellByColumnAndRow(2, $row)->getCalculatedValue());
                        $tu_giamtru = PreString::trim($provinceSheet2->getCellByColumnAndRow(3, $row)->getCalculatedValue());
                        $den_giamtru = PreString::trim($provinceSheet2->getCellByColumnAndRow(4, $row)->getCalculatedValue());
                        $mucdo_giamtru = PreString::trim($provinceSheet2->getCellByColumnAndRow(5, $row)->getCalculatedValue());
                        $loai_giamtru = PreString::trim($provinceSheet2->getCellByColumnAndRow(6, $row)->getCalculatedValue());
                        
                        if($tieude_giamtru !="" and $ma_giamtru !=""){
							$demgt++;
                        	for($index_column = 7; $index_column < $highestColumnNumber2; $index_column++ ){

                        		$service_id = PreString::trim($provinceSheet2->getCellByColumnAndRow($index_column, $count_row3)->getCalculatedValue());
	                        	
	                        	$giatri = PreString::trim($provinceSheet2->getCellByColumnAndRow($index_column, $row)->getCalculatedValue());

	                        	if($giatri > 0 and $service_id > 0){
	                        		$array_service_id[$service_id] = $giatri;
	                        	}

                        	}

                        	$json_service =  json_encode($array_service_id);

							// Luu vao DB
                        	$psReduceYourself = new PsReduceYourself();

                        	$psReduceYourself -> setPsCustomerId($ps_customer_id);
                        	$psReduceYourself -> setPsWorkplaceId($ps_workplace_id);
                        	$psReduceYourself -> setReduceCode($ma_giamtru);
                        	$psReduceYourself -> setStart($tu_giamtru);
                        	$psReduceYourself -> setStop($den_giamtru);
                        	$psReduceYourself -> setLevel($mucdo_giamtru);
                        	$psReduceYourself -> setStatus($kieu_giamtru);
                        	$psReduceYourself -> setIsType($loai_giamtru);
                        	$psReduceYourself -> setDiscount(0);
                        	$psReduceYourself -> setJsonService($json_service);
                        	$psReduceYourself -> setUserCreatedId ($user_id);
	                    	$psReduceYourself -> setUserUpdatedId($user_id);
                        	$psReduceYourself -> save();
							
                        }

                    }
					
                    $conn->commit();

                }
            } catch (Exception $e) {
                $conn->rollback();
                $error_import = 'AAaa'.$e->getMessage() . $this->getContext()->getI18N()->__('Error try-catch.');
                $this->getUser()->setFlash('error', $error_import);
                $this->redirect('@import_template_receipt');
            }

            if($true > 0){
                $this->getUser()->setFlash('notice', "Thêm mới thành công ".$true." học sinh");
            }

            if($demdv > 0){
                $this->getUser()->setFlash('notice', "Đăng ký dịch vụ thành công cho ".$dem." học sinh");
            }
			
            if($demgt > 0){
                $this->getUser()->setFlash('notice', "Thêm mới ".$demgt." giảm trừ dịch vụ");
            }
			
            if($demut > 0){
                $this->getUser()->setFlash('notice', "Cập nhật thành công ".$demut." học sinh có chế độ ưu tiên");
            }
			
            $this->redirect('@import_template_receipt');
        }
       	
	}

	// Export biểu mẫu cấu hình phí
	public function executeExportTemplate(sfWebRequest $request) {

		$this->formFilter = new sfFormFilter ();

		$ps_customer_id = null;

		$ps_workplace_id = null;

		$class_id = null;

		$ps_school_year_id = null;

		$export_filter = $request->getParameter ( 'export_filter' );

		if ($request->isMethod ( 'post' )) {

			$value_student_filter = $request->getParameter ( 'export_filter' );

			$ps_customer_id = $value_student_filter ['ps_customer_id'];

			$ps_workplace_id = $value_student_filter ['ps_workplace_id'];

			$ps_school_year_id = $value_student_filter ['ps_school_year_id'];

			// Lấy thông tin dịch vụ của trường, năm học
			$psServices = Doctrine_Query::create()->from('Service')->addWhere('ps_customer_id=?',$ps_customer_id)
			->andWhere('ps_workplace_id is null OR ps_workplace_id =?',$ps_workplace_id)
			->andWhere('ps_school_year_id=?',$ps_school_year_id)
			->andWhere('is_activated = 1')
			->orderBy('iorder ASC')
			->execute();


			$exportFile = new ExportStudentReportsHelper ( $this );

			$file_template_pb = 'bm_cauhinhphi.xls';

			$path_template_file = sfConfig::get ( 'sf_web_dir' ) . '/uploads/export_data/' . $file_template_pb;


			$exportFile->loadTemplate ( $path_template_file );

			$exportFile->setDataExportReceipt ( $psServices );

			$exportFile->saveAsFile ( "Cấu hình phí.xls" );

		}

		$this->ps_month = isset ( $value_student_filter ['ps_month'] ) ? $value_student_filter ['ps_month'] : date ( "m-Y" );

		// Lay nam hoc hien tai
		if ($ps_school_year_id == '') {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'is_default', PreSchool::ACTIVE );
		} else {
			$schoolYearsDefault = Doctrine::getTable ( 'PsSchoolYear' )->findOneBy ( 'id', $ps_school_year_id );
		}

		if (! myUser::credentialPsCustomers ( 'PS_FEE_REPORT_FILTER_SCHOOL' )) {

			$this->ps_customer_id = myUser::getPscustomerID ();

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormInputHidden () );

			$this->formFilter->setValidator ( 'ps_customer_id', new sfValidatorInteger ( array (
					'required' => true ) ) );
		} else {

			$this->formFilter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
					'model' => 'PsCustomer',
					
					//'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( PreSchool::CUSTOMER_ACTIVATED ),					
					'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLPsCustomerByParams (),
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
		$this->formFilter->setDefault ( 'ps_customer_id', $this->ps_customer_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		if ($this->ps_school_year_id == '') {
			$this->ps_school_year_id = Doctrine::getTable ( 'PsSchoolYear' )->getPsSchoolYearsDefault ()->fetchOne ()->getId ();
		}

		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

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
					'required' => false,
					'data-placeholder' => _ ( '-Select workplace-' ) ) ) );

			$this->formFilter->setValidator ( 'ps_workplace_id', new sfValidatorPass () );
		}

		
		$this->formFilter->setDefault ( 'ps_school_year_id', $this->ps_school_year_id );

		$this->formFilter->setDefault ( 'ps_workplace_id', $this->ps_workplace_id );

		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'export_filter[%s]' );
	}


}
