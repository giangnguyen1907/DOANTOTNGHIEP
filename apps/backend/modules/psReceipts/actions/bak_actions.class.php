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

		$this->receipt = $this->getRoute ()->getObject ();
		
		if (!myUser::isAdministrator() && $this->receipt->getPaymentStatus () == PreSchool::ACTIVE) {
			$this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ('This month has been paid. You can not edit.') );
			$this->redirect ( '@ps_receipts' );
		}
		
		$this->student = $this->receipt->getStudent ();
		
		$student_id    = $this->student->getId();
		
		$this->receivable_at = $this->receipt->getReceiptDate ();
		
		$int_receivable_at = PsDateTime::psDatetoTime ($this->receivable_at);			
		
		if ($this->receipt->getPaymentStatus () != PreSchool::ACTIVE) {
		
			$receiptOfStudentNextMonth = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentNextMonth ( $student_id, $int_receivable_at );
				
			if ($receiptOfStudentNextMonth)
				$this->receipt = $receiptOfStudentNextMonth;
		
		}
		
		// Thiet lập lai cac gia tri theo $this->receipt
		$int_receivable_at = PsDateTime::psDatetoTime ($this->receipt->getReceiptDate ());
		
		// Lay lop hoc cua hoc sinh tai thoi diem bao phi
		$infoClass = $this->student->getMyClassByStudent ($this->receipt->getReceiptDate ());
		
		if (! $infoClass) {
			// Lay lop hoc cua hoc sinh đang hoạt động
			$infoClass = $this->student->getCurrentClassOfStudent ();
		}
		
		// Lay thong tin co so dao tao
		$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $infoClass->getPsWorkplaceId () );

		// Lấy phiếu thu gần đây nhất
		$student_month = $this->student->getPrecedingMontOfStudent ( $int_receivable_at, $psWorkPlace->getConfigStartDateSystemFee () );
		
		// Ngay cua phieu thu cận receivable_at nhat
		$receiptPrevDate = $student_month ['receiptPrevDate'];
		
		// Lay danh sach cac khoan cua phi
		$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );
		
		$this->receiptPrevDate = $receiptPrevDate;
		
		$this->form = $this->configuration->getForm ( $this->receipt );
	}
	
	public function executeUpdate(sfWebRequest $request) {
	    
		$this->receipt 				= $this->getRoute()->getObject();
		
		if ($this->receipt->getPaymentStatus () == PreSchool::ACTIVE) {
			$this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ('This month has been paid. You can not edit.') );
			$this->redirect ( '@ps_receipts' );
		}
		
		$this->student 				= $this->receipt->getStudent ();
		
		$this->receivable_at 		= $this->receipt->getReceiptDate ();
		
		$this->psConfigLatePayment 	= null;
		
		$this->form 				= $this->configuration->getForm($this->receipt);
		
		$this->processForm($request, $this->form);
	    
	    $this->setTemplate('edit');
   }
	
	// Xóa phiếu báo - phiếu thu
	public function executeDelete(sfWebRequest $request) {

		$request->checkCSRFProtection ();
		
		$this->dispatcher->notify ( new sfEvent ( $this, 'admin.delete_object', array (
				'object' => $this->getRoute ()->getObject () ) ) );
		
		$ps_receipts = $this->getRoute ()->getObject ();
		
		$ps_student = $ps_receipts->getStudent ();
		
		$this->forward404Unless ( myUser::checkAccessObject ( $ps_student, 'PS_FEE_REPORT_FILTER_SCHOOL' ), sprintf ( 'Object does not exist.' ) );
		
		// Tim phieu thu(phieu bao) liền sau nhất - Nếu đã tồn tại thì không cho xóa
		$receiptOfStudentNextMonth = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentNextMonth ( $ps_student->getId (), strtotime ( $ps_receipts->getReceiptDate () ) );
		
		if ($receiptOfStudentNextMonth) {
			$this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ( 'There are tuition fees of month' ) . ' ' . date ( "m-Y", strtotime ( $receiptOfStudentNextMonth->getReceiptDate () ) ) . ' ' . $this->getContext ()->getI18N ()->__ ( 'You can not delete.' ) );
			$this->redirect ( '@ps_receipts' );
		}
		
		// Lấy phiếu thu của tháng chọn xóa
		// $receipt = $ps_student->findReceiptByDate ( PsDateTime::psDatetoTime ( $ps_fee_reports->getReceivableAt () ) );
		
		// Kiem tra neu phieu thu nay da thanh toan hoặc đã báo cho phụ huynh thi khong cho xoa
		if ($ps_receipts->getPaymentStatus () == PreSchool::ACTIVE) {
			$this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ('This month has been paid. You can not delete.') );
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
			
			$notice = $this->getContext ()->getI18N ()->__ ( 'Delete the tuition fee notice %value% successfully.', array (
					'%value%' => $this->getContext ()->getI18N ()->__ ( 'month' ) . ' ' . PsDateTime::psTimetoDate ( $int_receivable_at, "m-Y" ) . ' ' . $this->getContext ()->getI18N ()->__ ( 'of student' ) . ' ' . $ps_student->getFirstName () . ' ' . $ps_student->getLastName () ) );
			
			// Tìm phiếu thu (chưa thanh toán hoặc đã thanh toán) gần phiếu báo được chọn xóa nhất
			$receipt_prev = Doctrine::getTable ( 'Receipt' )->findPrevOfStudentByDate ( $ps_student->getId (), $int_receivable_at );
			
			// Ngay cua phieu thu gan nhat
			$receiptPrevDate = $receipt_prev ? $receipt_prev->getReceiptDate () : null;
			
			// Lay danh sach cac khoan phi cua phieu bao
			$receivable_students = Doctrine::getTable ( 'ReceivableStudent' )->getObjectReceivableStudentOfMonth ( $ps_student->getId (), $ps_receipts->getReceiptDate (), $receiptPrevDate );
			
			// lay phieu bao
			$ps_fee_reports = $ps_student->findPsFeeReportOfStudentByDate ( $int_receivable_at );
			
			/*
			foreach ($receivable_students AS $receivable_student) {
				echo $receivable_student->getStudentId().'-'.$receivable_student->getReceiptDate().'</br/>';
			}
			die();
			*/
			// Xoa Phieu bao - Phieu thu
			
			if ($ps_receipts->delete () && $ps_fee_reports->delete ()) {
				
				// Xoa du lieu trong ReceivableStudent
				foreach ( $receivable_students as $receivable_student ) {
					$receivable_student->delete ();
				}
			}
						
			// Xóa trong ps_fee_reports_flag_my_class ? Không cần. Khi chọn lớp để chạy báo phí vẫn cho hiển thị các lớp đã từng chạy
			
			$this->getUser ()->setFlash ( 'notice', $notice );
			
			$conn->commit ();
			
		} catch ( Exception $e ) {
			
			$conn->rollback ();
			
			$this->getUser ()->setFlash ( 'error', 'The item was deleted fail.' );
		}
		
		$this->redirect ( '@ps_receipts' );
	}
	
	/**
	 *  Load lại giá tiền nộp học phí muộn
	 *  Thanh PV
	 **/
	public function executeLoadAmount(sfWebRequest $request) {
	    
	    $receipt_no = $request->getParameter ( 'receipt_no');
	    
	    $tracked_at = $request->getParameter ( 'date_at');
	    
	    $replace_date = str_replace( '/', '-', $tracked_at );
	    
	    $date_at = date ( 'Y-m-d', strtotime ( $replace_date ) ); // chuyển định dạng ngày tháng
	    
	    $receipt = Doctrine::getTable('Receipt')->findOneByReceiptNo($receipt_no);
	    
	    $student_id = $receipt->getStudentId();
	    
	    $int_receivable_at = PsDateTime::psDatetoTime ( $date_at );
	    
	    $priceLatePayment = $total_price = $config_amount = 0;
	    
	    // Check role
	    $ps_student = Doctrine_Core::getTable('Student')->findOneById($student_id);
	    
	    if (!myUser::checkAccessObject($ps_student, 'PS_FEE_REPORT_FILTER_SCHOOL')) {
	        
	        echo $this->getContext()->getI18N()->__('Not roll data');
	        
	        exit(0);
	        
	    } else {
	        
	        // Lấy lớp của học sinh
	        $student_info = Doctrine::getTable ( 'StudentClass' )->getClassByDate ( $student_id, time () );
	        
	        if ($student_info) {
	            // id cơ cở
	            $ps_workplace_id = $student_info->getPsWorkplaceId ();
	            $psConfigLatePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplaceDate ( $ps_workplace_id, $date_at );
	            if($psConfigLatePayment){
	               $config_amount = $psConfigLatePayment->getPrice();
	            }
    	        // Lay thong tin co so dao tao
    	        $psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $ps_workplace_id );
    	        
    	        if($psWorkPlace->getConfigChooseChargePaylate() == 1){
    	            // lấy ra tháng đã thanh toán gần nhất so với phiếu thu hiện tại
    	            $check_receipt_date = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentIsPayment ( $student_id );
    	            
    	            if($check_receipt_date){ // Tính khoảng cách giữa 2 tháng
    	                
    	                $receipt_date = $check_receipt_date -> getReceiptDate();
    	                
    	                $datetime1 = date_create($receipt_date);
    	                $datetime2 = date_create($date_at);
    	                $interval = date_diff($datetime1, $datetime2);
    	                
    	                $check_month = $interval->format('%m');
    	                
    	            }else{ // Nếu chưa từng thanh toán lần nào, thì đếm xem có bao nhiêu tháng trước đó chưa thanh toán
    	                
    	                $check_receipt_date = Doctrine::getTable ( 'Receipt' )->countReceiptOfStudentIsPayment ( $student_id, $int_receivable_at );
    	                
    	                $check_month = count($check_receipt_date) + 1;
    	                
    	            }
    	            
    	            if($check_month > 1){
    	                
    	                for ($i=1;$i<$check_month;$i++){
    	                    
    	                    $track_at = date_create($date_at);
    	                    
    	                    date_modify($track_at, "-$i month");
    	                    
    	                    $date_receipt = date_format($track_at, "Y-m-d");
    	                    
    	                    $latePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplacePrevMonth ( $ps_workplace_id, $date_receipt );
    	                    if($latePayment){
    	                       $total_price += $latePayment->getPrice(); // Tính tổng khoản phạt nộp muộn học phí
    	                    }
    	                }
    	                
    	            }
    	        }
    	        
    	        // Lay tong so tien du kien cua 1 thang receivable_at(tháng đang xem) - Dự kiến các khoản thu của tháng receivable_at
    	        //$totalAmountReceivableAt = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentInMonth ( $student_id, $int_receivable_at );
    	        
    	        $priceLatePayment = $total_price + $config_amount;
    	        // Lay phieu bao cua tháng đang chọn
    	        
    	        $ps_fee_reports = Doctrine::getTable('PsFeeReports') -> findPsFeeReportOfStudentByDate($student_id, strtotime ( $date_at ));
    	        if($ps_fee_reports){
        	        return $this->renderPartial('psReceipts/fees/_load_amount', array(
        	            'ps_fee_reports'  => $ps_fee_reports,
        	            'priceLatePayment' => $priceLatePayment,
        	        ));
    	        }
	        }
	    }
	}
	
	/**
	 * Chi tiet phieu thu
	 * 1. Nếu phiếu thu đã thanh toán thì hiển thị bình thường 2. Nếu phiếu thu chưa thanh toán: Bước 1: Tìm phiếu thu đã thanh toán lớn hơn gần nhất, nếu có thì hiển thị theo phiếu này, trái lại sang bước 2 Bước 2: Tìm phiếu thu chưa thanh toán lớn nhất(tháng lớn nhất)
	 */
	public function executeShowOLD(sfWebRequest $request) {

		$this->receipt = $this->getRoute ()->getObject ();
		
		if (! $this->receipt) {
			// $this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			$this->getUser ()->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
			$this->redirect ( '@ps_receipts' );
		}
		
		if (! myUser::checkAccessObject ( $this->receipt->getStudent (), 'PS_FEE_REPORT_FILTER_SCHOOL' ) || $this->receipt->getStudent ()->getDeletedAt ()) {
			
			$this->getUser ()->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
			
			$this->redirect ( '@ps_receipts' );
		} else {
			
			// Tìm phiếu thu (chưa thanh toán hoặc đã thanh toán) gần phiếu báo được chọn xóa nhất
			$int_receivable_at = PsDateTime::psDatetoTime ( $this->receipt->getReceiptDate() );
			
			// TEST - Tinh so ngay nghi
			//$ps_logtimes  = Doctrine::getTable ( 'PsLogtimes' )->getSumAbsentOfStudent($this->receipt->getStudent ()->getId (), $this->receipt->getReceiptDate());
			
			//echo 'getNumberAbsent:'.$ps_logtimes;
			
			$receipt_prev = Doctrine::getTable ( 'Receipt' )->findPrevOfStudentByDate ( $this->receipt->getStudent ()->getId (), $int_receivable_at );
			
			// Ngay cua phieu thu gan nhat
			$receiptPrevDate = $receipt_prev ? $receipt_prev->getReceiptDate () : null;
			
			// Lay danh sach cac khoan phi cua phieu bao
			/*
			$receivable_students = Doctrine::getTable ( 'ReceivableStudent' )->getObjectReceivableStudentOfMonth ( $this->receipt->getStudent ()->getId (), $this->receipt->getReceiptDate (), $receiptPrevDate );
			
			if (myUser::isAdministrator()) {
				foreach ($receivable_students AS $receivable_student) {
					echo $receivable_student->getReceivableAt().'<br/>';
				}
			}
			*/
			
			// Phiếu báo tháng liền sau nhất
			$this->receiptOfStudentNextMonth = null;
			
			$this->student = $this->receipt->getStudent ();
			
			// Lay danh sach nguoi than cua hoc sinh
			$this->relatives = $this->student->getRelativesOfStudent ();
			
			$student_id = $this->student->getId ();
			
			// Tháng thu phí
			$this->receivable_at = $this->receipt->getReceiptDate ();
			$int_receivable_at = PsDateTime::psDatetoTime ( $this->receivable_at );
			
// 			echo $int_receivable_at;die;
			
			// Lấy lớp của học sinh
			$student_info = $this->student->getClassByDate ( time () );
			
			$this->psConfigLatePayment = null;
			$this->receiptOfStudentNextMonth = null;
			
			$this->totalAmount = 0;
			
			$this->pricePaymentLate = 0;
			
			if ($student_info) {
				
				// id cơ cở
				$ps_workplace_id = $student_info->getPsWorkplaceId ();
				$this->psConfigLatePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplaceDate ( $ps_workplace_id, date ( 'Y-m-d' ) );
			}
			
			// Tien thua tu phieu thu gan nhat chuyen sang
			$this->balance_last_month_amount = 0;
			
			if ($this->receipt->getPaymentStatus () == PreSchool::ACTIVE) { // Nếu đã thanh toán
			                                                                
				// Lay lop hoc cua hoc sinh tai thoi diem bao phi
				$infoClass = $this->student->getMyClassByStudent ( $this->receivable_at );
				
				if (! $infoClass) {
					// Lay lop hoc cua hoc sinh đang hoạt động
					$infoClass = $this->student->getCurrentClassOfStudent ();
				}
				
				// Lay thong tin co so dao tao
				$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $infoClass->getPsWorkplaceId () );
				
				// Lay phieu thu gần đây nhất
				$student_month = $this->student->getPrecedingMontOfStudent ( $int_receivable_at, $psWorkPlace->getConfigStartDateSystemFee () );
				
				// Ngay cua phieu thu cận receivable_at nhat
				$receiptPrevDate = $student_month ['receiptPrevDate'];
				
				// $this->balanceAmount = $this->receipt->getBalanceAmount();// Dư của phiếu theo tháng đang chọn xem
				
				// $this->collectedAmount = $this->receipt->getCollectedAmount();// Số tiền đã nộp
				
				// Dư của phiếu thu gần đây nhất
				$this->balanceAmount = $student_month ['BalanceAmount'];
				
				// Đã nộp của phiếu thu gần đây nhất
				$this->collectedAmount = $student_month ['CollectedAmount'];
				
				$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
				
				// // Tong so tien cua mot phiếu (tháng đang chạy + các tháng trước chưa thanh toán)
				$totalAmount = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );
				
				if ($totalAmount)
				    $this->totalAmount = $totalAmount->getTotalAmount ();
				
				$this->pricePaymentLate = $this->receipt->getLatePaymentAmount ();
				
				//echo $this->pricePaymentLate; die;
				// Lay danh sach cac khoan phi cua phieu bao
				$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );
				
				// Lay phieu bao
				$this->ps_fee_reports = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $this->receivable_at ) );
				
				// Lấy phiếu báo của tháng gần trước đây nhất
				$this->ps_fee_reports_nearest = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $receiptPrevDate ) );
				
				// Tìm xem có phiếu báo liền sau không
				$this->receiptOfStudentNextMonth = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentNextMonth ( $student_id, $int_receivable_at );
				
			} else { // Nếu chưa thanh toán
			         
				// Tìm xem có phiếu báo liền sau không lon nhat
				//$receiptOfStudentNextMonth = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentNextMonth ( $student_id, $int_receivable_at );
				
				$receiptOfStudentNextMonth = $this->receipt;
				
				$this->receiptOfStudentNextMonth = $receiptOfStudentNextMonth;
				
				$receiptOfStudentNextMonth = false;
				
				if ($receiptOfStudentNextMonth) {// Nếu có thì thực hiện lấy theo báo phí này
					
					
					$this->receipt = $receiptOfStudentNextMonth;
					
					$this->receivable_at = $this->receipt->getReceiptDate ();
					
					$int_receivable_at = PsDateTime::psDatetoTime ( $this->receivable_at );
					
					// Lay lop hoc cua hoc sinh tai thoi diem bao phi
					$infoClass = $this->student->getMyClassByStudent ( $this->receivable_at );
					
					if (! $infoClass) {
						// Lay lop hoc cua hoc sinh đang hoạt động
						$infoClass = $this->student->getCurrentClassOfStudent ();
					}
					
					// Lay thong tin co so dao tao
					$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $infoClass->getPsWorkplaceId () );
					
					// Lấy phiếu thu gần đây nhất
					$student_month = $this->student->getPrecedingMontOfStudent ( $int_receivable_at, $psWorkPlace->getConfigStartDateSystemFee () );
					
					// Ngay cua phieu thu cận receivable_at nhat
					$receiptPrevDate = $student_month ['receiptPrevDate'];
					
					// Dư của phiếu thu gần đây nhất
					$this->balanceAmount = $student_month ['BalanceAmount'];
					
					// Đã nộp của phiếu thu gần đây nhất
					$this->collectedAmount = $student_month ['CollectedAmount'];
					
					$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
					
					// // Tong so tien cua mot phiếu (tháng đang chạy + các tháng trước chưa thanh toán)
					$totalAmount = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );
					
					if ($totalAmount)
						$this->totalAmount = $totalAmount->getTotalAmount ();
						
					// Lay danh sach cac khoan phi cua phieu bao
					$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );
					
					// Lay phieu bao cua tháng đang chọn
					$this->ps_fee_reports = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $this->receivable_at ) );
					
					// Lấy phiếu báo của tháng gần đây nhất
					$this->ps_fee_reports_nearest = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $receiptPrevDate ) );
					
				    
				} else {
					
					// Lay lop hoc cua hoc sinh tai thoi diem bao phi
					$infoClass = $this->student->getMyClassByStudent ( $this->receivable_at );
					
					if (! $infoClass) {
						// Lay lop hoc cua hoc sinh đang hoạt động
						$infoClass = $this->student->getCurrentClassOfStudent ();
					}
					
					// Lay thong tin co so dao tao
					$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $infoClass->getPsWorkplaceId () );
					
					// Lay phieu thu gần đây nhất
					$student_month = $this->student->getPrecedingMontOfStudent ( $int_receivable_at, $psWorkPlace->getConfigStartDateSystemFee () );
					
					// Ngay cua phieu thu cận receivable_at nhat
					$receiptPrevDate = $student_month ['receiptPrevDate'];
					
					
					//echo 'Tháng: '.PsDateTime::psTimetoDate($receiptPrevDate);
					
					// Dư của phiếu thu gần đây nhất
					$this->balanceAmount = $student_month ['BalanceAmount'];
					
					// Đã nộp của phiếu thu gần đây nhất
					$this->collectedAmount = $student_month ['CollectedAmount'];
					
					$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
					
					// Tong so tien cua mot phiếu (tháng đang chạy + các tháng trước chưa thanh toán)
					$totalAmount = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );
					
					if ($totalAmount)
						$this->totalAmount = $totalAmount->getTotalAmount ();
						
					// Lay danh sach cac khoan phi cua phieu bao
					$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );
					
					// Lay phieu bao
					$this->ps_fee_reports = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $this->receivable_at ) );
					
					// Lấy phiếu báo của tháng gần đây nhất
					$this->ps_fee_reports_nearest = $this->student->findPsFeeReportOfStudentByDate ( $receiptPrevDate );
				}
				
				// Nếu cấu hình là kiểu lũy tiến, thì phải xem các tháng trước đó đã thanh toán hay chưa
				if($psWorkPlace->getConfigChooseChargePaylate() == 1){
				    // lấy ra tháng đã thanh toán gần nhất so với phiếu thu hiện tại
				    $check_receipt_date = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentIsPayment ( $student_id );
				    
				    if($check_receipt_date){ // Tính khoảng cách giữa 2 tháng
				        
    				    $receipt_date = $check_receipt_date -> getReceiptDate();
    				    
    				    $datetime1 = date_create($receipt_date);
    				    $datetime2 = date_create($this->receivable_at);
    				    $interval = date_diff($datetime1, $datetime2);
    				    
    				    $check_month = $interval->format('%m');
    				    
				    } else { // Nếu chưa từng thanh toán lần nào, thì đếm xem có bao nhiêu tháng trước đó chưa thanh toán
				        
				        $check_receipt_date = Doctrine::getTable ( 'Receipt' )->countReceiptOfStudentIsPayment ( $student_id, $int_receivable_at );
				        
				        $check_month = count($check_receipt_date) + 1;
				        
				    }
				    
				    $total_price = 0;
				    
				    if($check_month > 1){
				        
				        for ($i=1;$i<$check_month;$i++){
				            
				            $track_at = date_create($this->receivable_at);
				            
				            date_modify($track_at, "-$i month");
				            
				            $date_receipt = date_format($track_at, "Y-m-d");
				            
				            $latePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplacePrevMonth ( $ps_workplace_id, $date_receipt );
				            if($latePayment){
				            $total_price += $latePayment->getPrice(); // Tính tổng khoản phạt nộp muộn học phí
				            }
				        }
				        $this->pricePaymentLate = $total_price;
				    }
				}
				
			}
			
			//$this->balance_last_month_amount = $this->receipt->getBalanceLastMonthAmount();
			
			// lay cau hinh thiet lap phi nop muon
			$this->psChargePaylate = $psWorkPlace->getConfigChooseChargePaylate();
			
			// Lay tong so tien du kien cua 1 thang receivable_at(tháng đang xem) - Dự kiến các khoản thu của tháng receivable_at			
			$this->totalAmountReceivableAt = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentInMonth ( $student_id, $int_receivable_at );
		}
	}
	
	/**
	 * Chi tiet phieu thu
	 * 1. Nếu phiếu thu đã thanh toán thì hiển thị bình thường
	 * 2. Nếu phiếu thu chưa thanh toán:
	 * 	Bước 1: Tìm phiếu thu đã thanh toán lớn hơn gần nhất:
	 * 		Nếu có thì không cho thực hiện thanh toán phiếu này vì đã thực hiện dồn công nợ
	 * 		Trái lại sang bước 2
	 *  Bước 2: Tìm phiếu thu chưa thanh toán lớn nhất(tháng lớn nhất)
	 */
	public function executeShow(sfWebRequest $request) {

		$this->receipt = $this->getRoute ()->getObject ();
		
		if (! $this->receipt) {
			// $this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			$this->getUser ()->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
			$this->redirect ( '@ps_receipts' );
		}
		
		if (! myUser::checkAccessObject ( $this->receipt->getStudent (), 'PS_FEE_REPORT_FILTER_SCHOOL' ) || $this->receipt->getStudent ()->getDeletedAt ()) {
			
			$this->getUser ()->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
			
			$this->redirect ( '@ps_receipts' );
		} else {
			
			// TEST - Tinh so ngay nghi
			//$ps_logtimes  = Doctrine::getTable ( 'PsLogtimes' )->getSumAbsentOfStudent($this->receipt->getStudent ()->getId (), $this->receipt->getReceiptDate());
			//echo 'getNumberAbsent:'.$ps_logtimes;
			
			$int_receivable_at = PsDateTime::psDatetoTime ( $this->receipt->getReceiptDate() );
			
			// Tìm phiếu thu (chưa thanh toán hoặc đã thanh toán) gần phiếu đang được chọn nhất
			$receipt_prev = Doctrine::getTable ( 'Receipt' )->findPrevOfStudentByDate ( $this->receipt->getStudent ()->getId (), $int_receivable_at );
			
			// Ngay cua phieu thu gan nhat
			$receiptPrevDate = $receipt_prev ? $receipt_prev->getReceiptDate () : null;
			
			// Phiếu báo tháng liền sau nhất
			$this->receiptOfStudentNextMonth = null;
			
			$this->student = $this->receipt->getStudent ();
			
			// Lay danh sach nguoi than cua hoc sinh
			$this->relatives = $this->student->getRelativesOfStudent ();
			
			$student_id = $this->student->getId ();
			
			// Tháng thu phí
			$this->receivable_at = $this->receipt->getReceiptDate ();
			$int_receivable_at = PsDateTime::psDatetoTime ( $this->receivable_at );			
		
			// Lấy lớp tại thời điểm hiện tại của học sinh
			$student_info = $this->student->getClassByDate ( time () );
			
			$this->psConfigLatePayment = null;
			$this->receiptOfStudentNextMonth = null;
			
			$this->totalAmount = 0;
			
			$this->pricePaymentLate = 0;
			
			if ($student_info) {
				
				// id cơ cở
				$ps_workplace_id = $student_info->getPsWorkplaceId ();
				
				$this->psConfigLatePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplaceDate ( $ps_workplace_id, date ( 'Y-m-d' ) );
			}
			
			// Tien thua tu phieu thu gan nhat chuyen sang
			$this->balance_last_month_amount = 0;
			
			if ($this->receipt->getPaymentStatus () == PreSchool::ACTIVE) { // Nếu đã thanh toán
			                                                                
				// Lay lop hoc cua hoc sinh tai thoi diem bao phi
				$infoClass = $this->student->getMyClassByStudent ( $this->receivable_at );
				
				if (! $infoClass) {
					// Lay lop hoc cua hoc sinh đang hoạt động
					$infoClass = $this->student->getCurrentClassOfStudent ();
				}
				
				// Lay thong tin co so dao tao
				$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $infoClass->getPsWorkplaceId () );
				
				// Lay phieu thu gần đây nhất
				$student_month = $this->student->getPrecedingMontOfStudent ( $int_receivable_at, $psWorkPlace->getConfigStartDateSystemFee () );
				
				// Ngay cua phieu thu cận receivable_at nhat
				$receiptPrevDate = $student_month ['receiptPrevDate'];				
				
				// Dư của phiếu thu gần đây nhất
				$this->balanceAmount = $student_month ['BalanceAmount'];
				
				// Đã nộp của phiếu thu gần đây nhất
				$this->collectedAmount = $student_month ['CollectedAmount'];
				
				$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
				
				// // Tong so tien cua mot phiếu (tháng đang chạy + các tháng trước chưa thanh toán)
				$totalAmount = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );
				
				if ($totalAmount)
				    $this->totalAmount = $totalAmount->getTotalAmount ();
				
				$this->pricePaymentLate = $this->receipt->getLatePaymentAmount ();
				
				//echo $this->pricePaymentLate; die;
				// Lay danh sach cac khoan phi cua phieu bao
				$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );
				
				// Lay phieu bao
				$this->ps_fee_reports = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $this->receivable_at ) );
				
				// Lấy phiếu báo của tháng gần trước đây nhất
				$this->ps_fee_reports_nearest = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $receiptPrevDate ) );
				
				// Tìm xem có phiếu báo liền sau không
				$this->receiptOfStudentNextMonth = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentNextMonth ( $student_id, $int_receivable_at );
				
			} else { // Nếu chưa thanh toán
			         
				// Tìm xem có phiếu báo liền sau không lon nhat
				$receiptOfStudentNextMonth = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentNextMonth ( $student_id, $int_receivable_at );
				
				$this->receiptOfStudentNextMonth = $receiptOfStudentNextMonth;
				
				if ($receiptOfStudentNextMonth) {// Nếu có thì thực hiện lấy theo báo phí này					
					
					$this->receipt = $receiptOfStudentNextMonth;
					
					$this->receivable_at = $this->receipt->getReceiptDate ();
					
					$int_receivable_at = PsDateTime::psDatetoTime ( $this->receivable_at );
					
					// Lay lop hoc cua hoc sinh tai thoi diem bao phi
					$infoClass = $this->student->getMyClassByStudent ( $this->receivable_at );
					
					if (! $infoClass) {
						// Lay lop hoc cua hoc sinh đang hoạt động
						$infoClass = $this->student->getCurrentClassOfStudent ();
					}
					
					// Lay thong tin co so dao tao
					$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $infoClass->getPsWorkplaceId () );
					
					// Lấy phiếu thu gần đây nhất
					$student_month = $this->student->getPrecedingMontOfStudent ( $int_receivable_at, $psWorkPlace->getConfigStartDateSystemFee () );
					
					// Ngay cua phieu thu cận receivable_at nhat
					$receiptPrevDate = $student_month ['receiptPrevDate'];
					
					// Dư của phiếu thu gần đây nhất
					$this->balanceAmount = $student_month ['BalanceAmount'];
					
					// Đã nộp của phiếu thu gần đây nhất
					$this->collectedAmount = $student_month ['CollectedAmount'];
					
					$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
					
					// // Tong so tien cua mot phiếu (tháng đang chạy + các tháng trước chưa thanh toán)
					$totalAmount = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );
					
					if ($totalAmount)
						$this->totalAmount = $totalAmount->getTotalAmount ();
						
					// Lay danh sach cac khoan phi cua phieu bao
					$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );
					
					// Lay phieu bao cua tháng đang chọn
					$this->ps_fee_reports = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $this->receivable_at ) );
					
					// Lấy phiếu báo của tháng gần đây nhất
					$this->ps_fee_reports_nearest = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $receiptPrevDate ) );
					
				    
				} else {
					
					// Lay lop hoc cua hoc sinh tai thoi diem bao phi
					$infoClass = $this->student->getMyClassByStudent ( $this->receivable_at );
					
					if (! $infoClass) {
						// Lay lop hoc cua hoc sinh đang hoạt động
						$infoClass = $this->student->getCurrentClassOfStudent ();
					}
					
					// Lay thong tin co so dao tao
					$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $infoClass->getPsWorkplaceId () );
					
					// Lay phieu thu gần đây nhất
					$student_month = $this->student->getPrecedingMontOfStudent ( $int_receivable_at, $psWorkPlace->getConfigStartDateSystemFee () );
					
					// Ngay cua phieu thu cận receivable_at nhat
					$receiptPrevDate 	   = $student_month ['receiptPrevDate'];
					
					// Dư của phiếu thu gần đây nhất
					$this->balanceAmount   = $student_month ['BalanceAmount'];
					
					// Đã nộp của phiếu thu gần đây nhất
					$this->collectedAmount = $student_month ['CollectedAmount'];
					
					$this->balance_last_month_amount = $student_month ['balance_last_month_amount'];
					
					// Tong so tien cua mot phiếu (tháng đang chạy + các tháng trước chưa thanh toán)
					$totalAmount = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );
					
					if ($totalAmount)
						$this->totalAmount = $totalAmount->getTotalAmount ();
						
					// Lay danh sach cac khoan phi cua phieu bao
					$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );
					
					// Lay phieu bao
					$this->ps_fee_reports = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $this->receivable_at ) );
					
					// Lấy phiếu báo của tháng gần đây nhất
					$this->ps_fee_reports_nearest = $this->student->findPsFeeReportOfStudentByDate ( $receiptPrevDate );
				}
				
				// Nếu cấu hình là kiểu lũy tiến, thì phải xem các tháng trước đó đã thanh toán hay chưa
				if($psWorkPlace->getConfigChooseChargePaylate() == 1){
				    // lấy ra tháng đã thanh toán gần nhất so với phiếu thu hiện tại
				    $check_receipt_date = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentIsPayment ( $student_id );
				    
				    if($check_receipt_date){ // Tính khoảng cách giữa 2 tháng
				        
    				    $receipt_date = $check_receipt_date -> getReceiptDate();
    				    
    				    $datetime1 = date_create($receipt_date);
    				    $datetime2 = date_create($this->receivable_at);
    				    $interval  = date_diff($datetime1, $datetime2);
    				    
    				    $check_month = $interval->format('%m');
    				    
				    } else { // Nếu chưa từng thanh toán lần nào, thì đếm xem có bao nhiêu tháng trước đó chưa thanh toán
				        
				        $check_receipt_date = Doctrine::getTable ( 'Receipt' )->countReceiptOfStudentIsPayment ( $student_id, $int_receivable_at );
				        
				        $check_month = count($check_receipt_date) + 1;
				        
				    }
				    
				    $total_price = 0;
				    
				    if($check_month > 1){
				        
				        for ($i=1;$i<$check_month;$i++){
				            
				            $track_at = date_create($this->receivable_at);
				            
				            date_modify($track_at, "-$i month");
				            
				            $date_receipt = date_format($track_at, "Y-m-d");
				            
				            $latePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplacePrevMonth ( $ps_workplace_id, $date_receipt );
				            if($latePayment){
				            $total_price += $latePayment->getPrice(); // Tính tổng khoản phạt nộp muộn học phí
				            }
				        }
				        
				        $this->pricePaymentLate = $total_price;
				    }
				}
				
			}
			
			// lay cau hinh thiet lap phi nop muon
			$this->psChargePaylate = $psWorkPlace->getConfigChooseChargePaylate();
			
			// Lay tong so tien du kien cua 1 thang receivable_at(tháng đang xem) - Dự kiến các khoản thu của tháng receivable_at			
			$this->totalAmountReceivableAt = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentInMonth ( $student_id, $int_receivable_at );
		}
	}

	/**
	 * Chi tiet phieu thu
	 * 1. Nếu phiếu thu đã thanh toán thì hiển thị bình thường 2. Nếu phiếu thu chưa thanh toán: Bước 1: Tìm phiếu thu đã thanh toán lớn hơn gần nhất, nếu có thì hiển thị theo phiếu này, trái lại sang bước 2 Bước 2: Tìm phiếu thu chưa thanh toán lớn nhất(tháng lớn nhất)
	 */
	public function executeDetail(sfWebRequest $request) {

		$this->receipt = $this->getRoute ()->getObject ();
		
		if (! $this->receipt) {
			// $this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
			$this->getUser ()->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
			$this->redirect ( '@ps_receipts' );
		}
		
		if (! myUser::checkAccessObject ( $this->receipt->getStudent (), 'PS_FEE_REPORT_FILTER_SCHOOL' ) || $this->receipt->getStudent ()->getDeletedAt ()) {
			
			$this->getUser ()->setFlash ( 'error', 'Page Not Found or The data you asked for is secure and you do not have proper credentials.', false );
			
			$this->redirect ( '@ps_receipts' );
		} else {
			
			$this->student = $this->receipt->getStudent ();
			
			$receiptPrevDate = null;
			
			$this->balanceAmount = 0;
			
			$this->collectedAmount = 0;
			
			$this->pricePaymentLate = 0;
			
			// Tong so tien cua mot phiếu
			$this->totalAmount = 0;
			
			// Kiem tra thoi gian tam dung nghi hoc
			
			// Thang bao phi
			$this->receivable_at = $this->receipt->getReceiptDate ();
			
			// Lay danh sach nguoi than cua hoc sinh
			$this->relatives = $this->student->getRelativesOfStudent ();
			
			$student_id = $this->receipt->getStudentId ();
			
			$int_receivable_at = PsDateTime::psDatetoTime ( $this->receivable_at );
			
			// Lat tong so tien du kien cua 1 thang receivable_at(tháng đem chọn)
			$this->totalAmountReceivableAt = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentInMonth ( $student_id, $int_receivable_at );
			
			// Lay phieu thu cua thang duoc chon
			// $this->receipt = $this->student->findReceiptByDate ( $int_receivable_at );
			
			// if (! $this->receipt || ($this->receipt && $this->receipt->getPaymentStatus () != PreSchool::ACTIVE)) {
			
			// Lay lop hoc cua hoc sinh tai thoi diem bao phi
			$infoClass = $this->student->getMyClassByStudent ( $this->receivable_at );
			
			if (! $infoClass) {
				// Lay lop hoc cua hoc sinh đang hoạt động
				$infoClass = $this->student->getCurrentClassOfStudent ();
			}
			
			// Lay thong tin co so dao tao
			$psWorkPlace = Doctrine::getTable ( 'PsWorkPlaces' )->findOneById ( $infoClass->getPsWorkplaceId () );
			
			$student_month = $this->student->getPrecedingMontOfStudent ( $int_receivable_at, $psWorkPlace->getConfigStartDateSystemFee () );
			
			// Ngay cua phieu thu gan nhat
			$receiptPrevDate = $student_month ['receiptPrevDate'];
			
			// Dư của phiếu thu gần đây nhất
			$this->balanceAmount = $student_month ['BalanceAmount'];
			
			// Đã nộp của phiếu thu gần đây nhất
			$this->collectedAmount = $student_month ['CollectedAmount'];
			
			// print_r($student_month);
			// }
			
			// Lay danh sach cac khoan phi cua phieu bao
			$this->receivable_student = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );
			
			// Tong so tien cua mot phiếu (tháng đang chạy + các tháng trước chưa thanh toán)
			$totalAmount = Doctrine::getTable ( 'ReceivableStudent' )->getTotalAmountReceivableStudentOfMonth ( $student_id, $this->receivable_at, $receiptPrevDate );
			
			if ($totalAmount)
				$this->totalAmount = $totalAmount->getTotalAmount ();
			
			$this->form = new ReceiptForm ( $this->receipt );
			
			// lay cau hinh thiet lap phi nop muon
			$this->psChargePaylate = $psWorkPlace->getConfigChooseChargePaylate();
			// Nếu cấu hình là kiểu lũy tiến, thì phải xem các tháng trước đó đã thanh toán hay chưa
			if($psWorkPlace->getConfigChooseChargePaylate() == 1){
			    // lấy ra tháng đã thanh toán gần nhất so với phiếu thu hiện tại
			    $check_receipt_date = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentIsPayment ( $student_id );
			    
			    if($check_receipt_date){ // Tính khoảng cách giữa 2 tháng
			        
			        $receipt_date = $check_receipt_date -> getReceiptDate();
			        
			        $datetime1 = date_create($receipt_date);
			        $datetime2 = date_create($int_receivable_at);
			        $interval = date_diff($datetime1, $datetime2);
			        
			        $check_month = $interval->format('%m');
			        
			    }else{ // Nếu chưa từng thanh toán lần nào, thì đếm xem có bao nhiêu tháng trước đó chưa thanh toán
			        
			        $check_receipt_date = Doctrine::getTable ( 'Receipt' )->countReceiptOfStudentIsPayment ( $student_id, $int_receivable_at );
			        
			        $check_month = count($check_receipt_date) + 1;
			        
			    }
			    
			    $total_price = 0;
			    if($check_month > 1){
			        
			        for ($i=1;$i<$check_month;$i++){
			            
			            $track_at = date_create($this->receivable_at);
			            
			            date_modify($track_at, "-$i month");
			            
			            $date_receipt = date_format($track_at, "Y-m-d");
			            
			            $latePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplacePrevMonth ( $infoClass->getPsWorkplaceId (), $date_receipt );
			            if($latePayment){
			                 $total_price += $latePayment->getPrice(); // Tính tổng khoản phạt nộp muộn học phí
			            }
			        }
			        $this->pricePaymentLate = $total_price;
			    }
			}
			
			// Lay phieu bao
			$this->ps_fee_reports = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $this->receivable_at ) );
			
			// Tim phieu thu(phieu bao) liền sau tháng này nhất
			$this->receiptOfStudentNextMonth = Doctrine::getTable ( 'Receipt' )->findReceiptOfStudentNextMonth ( $student_id, strtotime ( $this->receivable_at ) );
			
			$this->psConfigLatePayment = Doctrine::getTable ( 'PsConfigLatePayment' )->getPriceByPsWorkplaceDate ( $psWorkPlace->getId (), date ( 'Y-m-d' ) );
		}
	}
	
	// Thanh toan
	public function executePayment(sfWebRequest $request) {

		$this->receipt = $this->getRoute ()->getObject ();
		
		if (! $this->receipt) {
			
			$this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
		} elseif ($this->receipt->getPaymentStatus () == PreSchool::ACTIVE) {
			
			$this->getUser ()->setFlash ( 'notice', $this->getContext ()->getI18N ()->__ ( 'The bill has been paid.' ) );
			
			$this->redirect ( '@ps_receipts' );
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
		
		$replace_date = str_replace( '/', '-', $rec ['payment_date'] );
		
		$payment_date = date ( 'Y-m-d', strtotime ( $replace_date ) ); // chuyển định dạng ngày tháng
		
		// Kiểm tra xem có đúng ngày không và nhỏ hơn ngày hiện tại hay không
		if ($payment_date != '1970-01-01' && strtotime($payment_date) <= strtotime(date('Y-m-d'))) {
		    $payment_date = $payment_date;
		} else {
		    $payment_date = date ( "Y-m-d H:i:s" );
		}
		
		// Lay phieu bao cua thang
		$this->ps_fee_reports = $this->student->findPsFeeReportOfStudentByDate ( strtotime ( $this->receivable_at ) );
		
		$ps_fee_report_id = $this->ps_fee_reports->getId ();
		
		$conn = Doctrine_Manager::connection ();
		
		try {
			
			$conn->beginTransaction ();
			
			// Validate
			if (($rec ['collected_amount'] >= 0) && is_numeric ( $rec ['collected_amount'] ) && mb_strlen ( $rec ['note'] <= 255 )) {
				
				$this->receipt->setCollectedAmount ( $rec ['collected_amount'] );
				// Ham cu ThangNC
				//$this->receipt->setBalanceAmount ( ( float ) ($rec ['collected_amount'] - $this->ps_fee_reports->getReceivable ()) );
				
				// Hàm mới tính dư nợ - ThanhPV
				$this->receipt->setBalanceAmount ( ( float ) ($rec ['collected_amount'] - $rec['total_payment']) );
				
				$this->receipt->setNote ( PreString::trim ( $rec ['note'] ) );
				$this->receipt->setPaymentRelativeName ( PreString::trim ( $rec ['relative_name'] ) );
				$this->receipt->setCashierName ( PreString::trim ( $rec ['cashier'] ) );
				$this->receipt->setPaymentType ( PreString::trim ( $rec ['payment_type'] ) );
				$this->receipt->setPaymentStatus ( PreSchool::ACTIVE );
				$this->receipt->setPaymentDate ( $payment_date );
				$this->receipt->setUserUpdatedId ( sfContext::getInstance ()->getUser ()->getGuardUser ()->getId () );
				$this->receipt->setLatePaymentAmount($rec ['late_payment_amount']);
				
				$this->receipt->save ();
				
				$this->getUser ()->setFlash ( 'notice', $this->getContext ()->getI18N ()->__ ( 'Payment successfully.' ) );
			
			} else {
				$this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ( 'Payment fail. Data invalid.' ) );
			}
			
			$conn->commit ();
		} catch ( Exception $e ) {
			
			$conn->rollback ();
			
			$this->getUser ()->setFlash ( 'error', 'Payment fail.' );
			
			$this->redirect ( '@ps_receipts' );
		}
		
		$this->redirect ( '@ps_receipts' );
	}
	
	/**
	 * Form import phiếu thu 
	 **/
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
		
		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile (array(), array('class' => 'form-control btn btn-default btn-success btn-psadmin','style' => 'width:100%;')) );
		
		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );
		
		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'import_filter[%s]' );
	}
	
	/**
	 * Form import phiếu thu --- Lưu
	 **/
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
		
		$this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile (array(), array('class' => 'form-control btn btn-default btn-success btn-psadmin','style' => 'width:100%;')) );
		
		$this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
				'required' => true,
				'mime_types' => 'web_excel',
				'max_size' => $upload_max_size_byte ), array (
				'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
				'max_size' => sfContext::getInstance ()->getI18n ()->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
						'%value%' => $upload_max_size ) ) ) ) );
		
		$this->formFilter->setDefault ( 'ps_file', $this->ps_file );
		
		$this->formFilter->getWidgetSchema ()->setNameFormat ( 'import_filter[%s]' );
		
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
				
				$file_classify = $this->getContext ()->getI18N ()->__ ( 'Fee report import' );
				
				$file = $this->formFilter->getValue ( 'ps_file' );
				
				$filename = time () . $file->getOriginalName ();
				
				$file_link = 'FeeReports' . '/' . 'School_' . $ps_customer_id . '/' . date ( 'Ym' );
				
				$path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';
				
				$file->save ( $path_file . $filename );
				
				$objPHPExcel = PHPExcel_IOFactory::load ( $path_file . $filename );
				
				$provinceSheet = $objPHPExcel->setActiveSheetIndex ( 0 ); // Set sheet sẽ được đọc dữ liệu
				
				$highestRow = $provinceSheet->getHighestRow (); // Lấy số row lớn nhất trong sheet
				
				$array_error = array ();
				
				$false = 0;
				
				$true = 0;
				
				for($row = 3; $row <= $highestRow; $row ++) {
					
					$student_code = $provinceSheet->getCellByColumnAndRow ( 1, $row )->getValue ();
					
					$note = $provinceSheet->getCellByColumnAndRow ( 7, $row )->getValue ();
					
					$str_number = strlen ( $note );
					if ($student_code != '') {
						if (in_array ( $student_code, $array_student ) && $str_number < 255) {
							
							$true ++;
							
							$receipt = $provinceSheet->getCellByColumnAndRow ( 2, $row )->getValue ();
							
							$receivable_date = date ( 'Y-m-d', strtotime (str_replace('/', '-', $receipt) ) ); // chuyển định dạng
							
							if ($receivable_date != '1970-01-01') { // Kiểm tra xem có đúng ngày không
								$receipt_date = $receivable_date;
							} else {
								$receipt_date = date ( 'Y-m-d' );
							}
							
							$receipt_title = $provinceSheet->getCellByColumnAndRow ( 3, $row )->getValue ();
							
							$receivable = $provinceSheet->getCellByColumnAndRow ( 4, $row )->getValue ();
							
							$collected_amount = $provinceSheet->getCellByColumnAndRow ( 5, $row )->getValue ();
							
							$balance_amount = $provinceSheet->getCellByColumnAndRow ( 6, $row )->getValue ();
							
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
					$error_import = $this->getContext ()->getI18N ()->__ ( 'Import file failed.' );
					$this->getUser ()->setFlash ( 'error', $error_import );
					$this->redirect ( '@ps_receipts_import' );
				}
				
				$error_line = implode ( ' ; ', $array_error );
			} else {
				$error_import = $this->getContext ()->getI18N ()->__ ( 'formFilter->isValid ().' );
				$this->getUser ()->setFlash ( 'error', $error_import );
				$this->redirect ( '@ps_receipts_import' );
			}
			
			$conn->commit ();
		} catch ( Exception $e ) {
			
			unlink ( $path_file . $filename );
			
			$conn->rollback ();
			
			$error_import = $this->getContext ()->getI18N ()->__ ( 'Error form.' ) . $e->getMessage ();
			
			$this->getUser ()->setFlash ( 'error', $error_import );
			$this->redirect ( '@ps_receipts_import' );
		}
		if ($false == 0) {
			$successfully = $this->getContext ()->getI18N ()->__ ( 'Import file successfully %value% data. No error student code', array (
					'%value%' => $true ) );
			$this->getUser ()->setFlash ( 'notice', $successfully );
		} else {
			
			$successfully = $this->getContext ()->getI18N ()->__ ( 'Import file successfully.' );
			
			$success_number = $this->getContext ()->getI18N ()->__ ( 'Successfully : ' ) . $true;
			
			$error_number = $this->getContext ()->getI18N ()->__ ( 'Error : ' ) . $false;
			
			$error_array = $this->getContext ()->getI18N ()->__ ( 'Studen code' ) . $error_line;
			
			$this->getUser ()->setFlash ( 'notice', $successfully );
			$this->getUser ()->setFlash ( 'notice1', $success_number );
			$this->getUser ()->setFlash ( 'notice2', $error_number );
			$this->getUser ()->setFlash ( 'notice3', $error_array );
		}
		
		$this->redirect ( '@ps_receipts_import' );
	}
	
	
	/**
	 * Import sổ thanh toán
	 *
	 **/
	
	public function executeImportreceipt(sfWebRequest $request)
	{
	    
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
	    
	    $this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile (array(), array('class' => 'form-control btn btn-default btn-success btn-psadmin','style' => 'width:100%;')) );
	    
	    $this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
	        'required' => true,
	        'mime_types' => 'web_excel',
	        'max_size' => $upload_max_size_byte ), array (
	            'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
	            'max_size' => sfContext::getInstance ()->getI18n ()->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
	                '%value%' => $upload_max_size ) ) ) ) );
	    
	    $this->formFilter->setDefault ( 'ps_file', $this->ps_file );
	    
	    $this->formFilter->getWidgetSchema ()->setNameFormat ( 'import_receipt[%s]' );
	    
	}
	
	
	/**
	 * Import sổ thanh toán - Lưu
	 *
	 **/
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
	    
	    $this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile (array(), array('class' => 'form-control btn btn-default btn-success btn-psadmin','style' => 'width:100%;')) );
	    
	    $this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
	        'required' => true,
	        'mime_types' => 'web_excel',
	        'max_size' => $upload_max_size_byte ), array (
	            'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
	            'max_size' => sfContext::getInstance ()->getI18n ()->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
	                '%value%' => $upload_max_size ) ) ) ) );
	    
	    $this->formFilter->setDefault ( 'ps_file', $this->ps_file );
	    
	    $this->formFilter->getWidgetSchema ()->setNameFormat ( 'import_receipt[%s]' );
	    
	    /**
	     * * Save Import file excel **
	     */
	    
	    $import_filter_form = $request->getParameter ( 'import_receipt' );
	    
	    $this->formFilter->bind ( $request->getParameter ( 'import_receipt' ), $request->getFiles ( 'import_receipt' ) );
	    
	    // id truong hoc
	    $ps_customer_id = $this->formFilter->getValue ( 'ps_customer_id' );
	    
	    // kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
	    if (! myUser::credentialPsCustomers('PS_FEE_REPORT_FILTER_SCHOOL')) {
	        if($ps_customer_id != myUser::getPscustomerID()) {
	            $this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
	        }
	    }
	    
	    $conn = Doctrine_Manager::connection ();
	    
	    try {
	        
	        $conn->beginTransaction ();
	        
	        if ($this->formFilter->isValid ()) {
	            
	            $user_id = myUser::getUserId ();
	            
	            $file_classify = $this->getContext ()->getI18N ()->__ ( 'Import receipt student' );
	            
	            $file = $this->formFilter->getValue ( 'ps_file' );
	            
	            $filename = time () . $file->getOriginalName ();
	            
	            $file_link = 'PsReceipt' . '/' . 'CoSoDaoTao' . $ps_workplace_id . '/' . date ( 'Ym' );
	            
	            $path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';
	            
	            $file->save ( $path_file . $filename );
	            
	            $objPHPExcel = PHPExcel_IOFactory::load ( $path_file . $filename );
	            
	            $provinceSheet = $objPHPExcel->setActiveSheetIndex ( 0 ); // Set sheet sẽ được đọc dữ liệu
	            
	            $highestRow = $provinceSheet->getHighestRow ();         // Lấy số row lớn nhất trong sheet
	            
	            $array_error = $error_receipt = array ();
	            
	            $false = 0;
	            
	            $true = 0;
	            
	            for($row = 6; $row <= $highestRow; $row ++) {
	                
	                $student_code = $provinceSheet->getCellByColumnAndRow ( 1, $row )->getValue ();
	                
	                $receipt_no = $provinceSheet->getCellByColumnAndRow ( 3, $row )->getValue (); // ma phieu thu
	                
	                if ($student_code != '' && $receipt_no !='') { // ma hoc sinh va ma phieu thu khong duoc trong
	                    
	                    $receipt = Doctrine_Core::getTable('Receipt')->checkStudentAndReceiptNo($student_code,$receipt_no);
	                    
	                    if($receipt) {
	                        
	                        $total_amount = Doctrine::getTable('PsFeeReports')->getAmountFeeReceiptOfMonth($student_code,$receipt->getReceiptDate());
	                        
	                        if($total_amount){
	                            
	                            $amount = $provinceSheet->getCellByColumnAndRow ( 5, $row )->getValue (); // So tien nop
	                            
	                            if (is_numeric($amount)) {
	                                
	                                $true ++;
	                                
    	                            $receipt_date = $provinceSheet->getCellByColumnAndRow ( 6, $row )->getValue (); // ngay nop tien
    	                            
    	                            $cashier = $provinceSheet->getCellByColumnAndRow ( 7, $row )->getValue (); // thu ngan
    	                            
    	                            $receivable_date = date ( 'Y-m-d', strtotime (str_replace('/', '-', $receipt_date) ) ); // chuyển định dạng
    	                            
    	                            if ($receivable_date != '1970-01-01') { // Kiểm tra xem có đúng ngày không
    	                                $payment_date = $receivable_date;
    	                            } else {
    	                                $payment_date = date ( 'Y-m-d' );
    	                            }
    	                            
    	                            $balance_amount = $amount - $total_amount->getReceivable();
    	                            
    	                            $receipt->setCollectedAmount ($amount);
    	                            $receipt->setPaymentDate($payment_date);
    	                            $receipt->setCashierName($cashier);
    	                            $receipt->setBalanceAmount($balance_amount); // so tien du ra
    	                            $receipt->setPaymentStatus(1);
    	                            $receipt->setUserUpdatedId($user_id);
    	                            
    	                            $receipt->save();
    	                            
	                            } else {
	                                $false ++;
	                                array_push ( $error_receipt, $row );
	                            }
	                        }else {
	                            $false ++;
	                            array_push ( $error_receipt, $row );
	                        }
	                    }else{
	                        $false ++;
	                        array_push ( $error_receipt, $row );
	                    }
	                }
	            }
	            
	            if ($true > 0) {
	                // luu lich su import file phieu ghi no
	                $ps_history_import = new PsHistoryImport ();
	                $ps_history_import->setPsCustomerId ( $ps_customer_id );
	                $ps_history_import -> setPsWorkplaceId(null);
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
	            $error_import = $this->getContext ()->getI18N ()->__ ( 'formFilter->isValid ().' );
	            $this->getUser ()->setFlash ( 'error', $error_import );
	            $this->redirect ( '@ps_receipts_student_import' );
	        }
	        
	        $conn->commit ();
	    } catch ( Exception $e ) {
	        unlink ( $path_file . $filename );
	        $conn->rollback ();
	    }
	    
	    if ($false == 0 && $true > 0) {
	        $successfully = $this->getContext ()->getI18N ()->__ ( 'Import file successfully %value% data. No error student code', array (
	            '%value%' => $true ) );
	        $this->getUser ()->setFlash ( 'notice', $successfully );
	    } elseif($true == 0) {
	        
	        if(count($error_receipt) > 0){ // loi ma phieu thu
	            $er_streceip = $this->getContext ()->getI18N ()->__ ( 'Line' ) . $error_receipt_no;
	        }else{
	            $er_streceip = '';
	        }
	        
	        $error_number = $this->getContext ()->getI18N ()->__ ( 'Error : ' ) . $false;
	        
	        $error_all = $error_number.' ; '.$er_streceip;
	        
	        $this->getUser ()->setFlash ( 'error', $error_all );
	        
	    }else{
	        
	        $successfully = $this->getContext ()->getI18N ()->__ ( 'Import file successfully.' );
	        
	        $success_number = $this->getContext ()->getI18N ()->__ ( 'Successfully : ' ) . $true;
	        
	        if(count($error_receipt) > 0){ // loi ma phieu thu
	            $er_streceip = $this->getContext ()->getI18N ()->__ ( 'Line' ) . $error_receipt_no;
	        }else{
	            $er_streceip = '';
	        }
	        
	        $error_number = $this->getContext ()->getI18N ()->__ ( 'Error : ' ) . $false;
	        
	        $error_all = $error_number.'<br/>'.$er_streceip;
	        
	        $this->getUser ()->setFlash ( 'notice', $successfully );
	        $this->getUser ()->setFlash ( 'notice1', $success_number );
	        $this->getUser ()->setFlash ( 'notice2', $error_all );
	        
	    }
	    
	    $this->redirect ( '@ps_receipts_student_import' );
	}
	
	
	/**
	 * Import Import số dư đầu kỳ
	 *
	 **/
	
	public function executeImportLastMonth(sfWebRequest $request)
	{
	    
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
	    
	    $this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile (array(), array('class' => 'form-control btn btn-default btn-success btn-psadmin','style' => 'width:100%;')) );
	    
	    $this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
	        'required' => true,
	        'mime_types' => 'web_excel',
	        'max_size' => $upload_max_size_byte ), array (
	            'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
	            'max_size' => sfContext::getInstance ()->getI18n ()->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
	                '%value%' => $upload_max_size ) ) ) ) );
	    
	    $this->formFilter->setDefault ( 'ps_file', $this->ps_file );
	    
	    $this->formFilter->getWidgetSchema ()->setNameFormat ( 'import_receipt[%s]' );
	    
	}
	
	
	/**
	 * Import số dư đầu kỳ - Lưu
	 *
	 **/
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
	    
	    $this->formFilter->setWidget ( 'ps_file', new sfWidgetFormInputFile (array(), array('class' => 'form-control btn btn-default btn-success btn-psadmin','style' => 'width:100%;')) );
	    
	    $this->formFilter->setValidator ( 'ps_file', new myValidatorFile ( array (
	        'required' => true,
	        'mime_types' => 'web_excel',
	        'max_size' => $upload_max_size_byte ), array (
	            'mime_types' => 'The excel file must be in the format: xls, xlsx, msexcel...',
	            'max_size' => sfContext::getInstance ()->getI18n ()->__ ( 'The file is too large. Allowed maximum size is %value%KB', array (
	                '%value%' => $upload_max_size ) ) ) ) );
	    
	    $this->formFilter->setDefault ( 'ps_file', $this->ps_file );
	    
	    $this->formFilter->getWidgetSchema ()->setNameFormat ( 'import_receipt[%s]' );
	    
	    /**
	     * * Save Import file excel **
	     */
	    
	    $import_filter_form = $request->getParameter ( 'import_receipt' );
	    
	    $this->formFilter->bind ( $request->getParameter ( 'import_receipt' ), $request->getFiles ( 'import_receipt' ) );
	    
	    // id truong hoc
	    $ps_customer_id = $this->formFilter->getValue ( 'ps_customer_id' );
	    
	    // kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
	    if (! myUser::credentialPsCustomers('PS_FEE_REPORT_FILTER_SCHOOL')) {
	        if($ps_customer_id != myUser::getPscustomerID()) {
	            $this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
	        }
	    }
	    
	    $conn = Doctrine_Manager::connection ();
	    
	    try {
	        
	        $conn->beginTransaction ();
	        
	        if ($this->formFilter->isValid ()) {
	            
	            $user_id = myUser::getUserId ();
	            
	            $file_classify = $this->getContext ()->getI18N ()->__ ( 'Import receipt student' );
	            
	            $file = $this->formFilter->getValue ( 'ps_file' );
	            
	            $filename = time () . $file->getOriginalName ();
	            
	            $file_link = 'PsReceipt' . '/' . 'CoSoDaoTao' . $ps_workplace_id . '/' . date ( 'Ym' );
	            
	            $path_file = sfConfig::get ( 'sf_upload_dir' ) . '/' . 'import_data' . '/' . $file_link . '/';
	            
	            $file->save ( $path_file . $filename );
	            
	            $objPHPExcel = PHPExcel_IOFactory::load ( $path_file . $filename );
	            
	            $provinceSheet = $objPHPExcel->setActiveSheetIndex ( 0 ); // Set sheet sẽ được đọc dữ liệu
	            
	            $highestRow = $provinceSheet->getHighestRow ();         // Lấy số row lớn nhất trong sheet
	            
	            $array_error = $error_receipt = array ();
	            
	            $false = 0;
	            
	            $true = 0;
	            
	            for($row = 6; $row <= $highestRow; $row ++) {
	                
	                $student_code = $provinceSheet->getCellByColumnAndRow ( 1, $row )->getValue ();
	                
	                $receipt_no = $provinceSheet->getCellByColumnAndRow ( 3, $row )->getValue (); // ma phieu thu
	                
	                if ($student_code != '' && $receipt_no !='') { // ma hoc sinh va ma phieu thu khong duoc trong
	                    
	                    $receipt = Doctrine_Core::getTable('Receipt')->checkStudentAndReceiptNo($student_code,$receipt_no);
	                    
	                    if($receipt) {
	                        $student_id = $receipt->getStudentId();
	                        $total_amount = Doctrine_Core::getTable('PsFeeReports')->checkFeeReportsOfMonth($student_id,$receipt->getReceiptDate());
	                        
	                        if($total_amount){
	                            
	                            // Số tiền dư đầu kỳ
	                            $balance_last_month_amount = $provinceSheet->getCellByColumnAndRow ( 4, $row )->getValue (); // So tien nop
	                            
	                            if (is_numeric($balance_last_month_amount)) {
	                                
	                                $true ++;
	                                
	                                // Phải nộp = Tổng ban đầu - số dư đầu kỳ
	                                $so_tien_phai_nop = $total_amount->getReceivable() - $balance_last_month_amount;
	                                
	                                //echo $so_tien_phai_nop; die;
	                                
	                                $total_amount -> setReceivable($so_tien_phai_nop);
	                                $total_amount -> save();
	                                
	                                // Tiền dư = Đã thanh toán - Số tiền phải nộp
	                                $balance_amount = $receipt->getCollectedAmount() - $so_tien_phai_nop;
	                                
	                                $receipt->setBalanceLastMonthAmount($balance_last_month_amount);
	                                
	                                $receipt->setBalanceAmount($balance_amount); 
	                                
	                                $receipt->setUserUpdatedId($user_id);
	                                
	                                $receipt->save();
	                                
	                            } else {
	                                $false ++;
	                                array_push ( $error_receipt, $row );
	                            }
	                        }else {
	                            $false ++;
	                            array_push ( $error_receipt, $row );
	                        }
	                    }else{
	                        $false ++;
	                        array_push ( $error_receipt, $row );
	                    }
	                }
	            }
	            
	            if ($true > 0) {
	                // luu lich su import file phieu ghi no
	                $ps_history_import = new PsHistoryImport ();
	                $ps_history_import->setPsCustomerId ( $ps_customer_id );
	                $ps_history_import -> setPsWorkplaceId(null);
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
	            $error_import = $this->getContext ()->getI18N ()->__ ( 'formFilter->isValid ().' );
	            $this->getUser ()->setFlash ( 'error', $error_import );
	            $this->redirect ( '@ps_receipts_last_month_import' );
	        }
	        
	        $conn->commit ();
	    } catch ( Exception $e ) {
	        unlink ( $path_file . $filename );
	        $conn->rollback ();
	    }
	    
	    if ($false == 0 && $true > 0) {
	        $successfully = $this->getContext ()->getI18N ()->__ ( 'Import file successfully %value% data. No error student code', array (
	            '%value%' => $true ) );
	        $this->getUser ()->setFlash ( 'notice', $successfully );
	    } elseif($true == 0) {
	        
	        if(count($error_receipt) > 0){ // loi ma phieu thu
	            $er_streceip = $this->getContext ()->getI18N ()->__ ( 'Line' ) . $error_receipt_no;
	        }else{
	            $er_streceip = '';
	        }
	        
	        $error_number = $this->getContext ()->getI18N ()->__ ( 'Error : ' ) . $false;
	        
	        $error_all = $error_number.' ; '.$er_streceip;
	        
	        $this->getUser ()->setFlash ( 'error', $error_all );
	        
	    }else{
	        
	        $successfully = $this->getContext ()->getI18N ()->__ ( 'Import file successfully.' );
	        
	        $success_number = $this->getContext ()->getI18N ()->__ ( 'Successfully : ' ) . $true;
	        
	        if(count($error_receipt) > 0){ // loi ma phieu thu
	            $er_streceip = $this->getContext ()->getI18N ()->__ ( 'Line' ) . $error_receipt_no;
	        }else{
	            $er_streceip = '';
	        }
	        
	        $error_number = $this->getContext ()->getI18N ()->__ ( 'Error : ' ) . $false;
	        
	        $error_all = $error_number.'<br/>'.$er_streceip;
	        
	        $this->getUser ()->setFlash ( 'notice', $successfully );
	        $this->getUser ()->setFlash ( 'notice1', $success_number );
	        $this->getUser ()->setFlash ( 'notice2', $error_all );
	        
	    }
	    
	    $this->redirect ( '@ps_receipts_last_month_import' );
	}
	
	
	/**
	 * Biểu mẫu import phiếu dư đầu kỳ
	 */
	public function executeExportBalance(sfWebRequest $request)
	{
	    $student_filters = $request->getParameter('receipt_filters');
	    
	    $ps_customer_id  = $student_filters['ps_customer_id'];
	    
	    $ps_workplace_id = $student_filters['ps_workplace_id'];
	    
	    $ps_class_id = $student_filters['ps_class_id'];
	    
	    $ps_month = $student_filters['ps_year_month'];
	    
	    $is_payment = $student_filters['payment_status'];
	    
	    // kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
	    if (! myUser::credentialPsCustomers('PS_FEE_REPORT_FILTER_SCHOOL')) {
	        if($ps_customer_id != myUser::getPscustomerID()){
	            $this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
	        }
	    }
	    
	    $this->exportReportReceiptBalanceLastMonth($ps_customer_id,$ps_workplace_id,$ps_class_id,$ps_month,$is_payment);
	    
	    $this->redirect('@ps_receipts');
	}
	
	protected function exportReportReceiptBalanceLastMonth($ps_customer_id,$ps_workplace_id,$ps_class_id,$ps_month,$is_payment)
	{
	    $exportFile = new ExportStudentLogtimesReportHelper($this);
	    
	    $file_template_pb = 'ps_receipt_balance_last_month.xls';
	    
	    $path_template_file = sfConfig::get('sf_web_dir') . '/uploads/export_data/' . $file_template_pb;
	    
	    if($ps_customer_id <= 0){
	        $ps_customer_id = myUser::getPscustomerID();
	    }
	    if($is_payment == ''){
	        $is_payment = 2;
	    }
	    
	    $list_student = Doctrine::getTable('Receipt')->getListStudentFeeReceiptOfMonth($ps_customer_id,$ps_workplace_id,$ps_class_id,$is_payment,$ps_month);
	    
	    $school_name = Doctrine::getTable('MyClass')->getInfoMyClassByCustomer($ps_customer_id, $ps_class_id);
	    
	    //$school_name = Doctrine::getTable('PsCustomer')->findOneById($ps_customer_id);
	    
	    if($ps_class_id > 0){
	        $head_name = $school_name->getClName();
	        
	        $title_info = $this->getContext ()->getI18N ()->__( 'Balance last month in class %value% month %value1%', array('%value%'=>$head_name,'%value1%'=>$ps_month) );
	        
	    }else{
	        $head_name = $school_name->getTitle();
	        
	        $title_info = $this->getContext ()->getI18N ()->__( 'Balance last month in workplace %value% month %value1%', array('%value%'=>$head_name,'%value1%'=>$ps_month) );
	    }
	    
	    $title_xls = 'PhieuDuDauKy_'.date('Ym', strtotime('01-'.$ps_month));
	    
	    $exportFile->loadTemplate($path_template_file);
	    
	    $exportFile->setDataExportReceiptBalanceLastMonth($school_name,$title_info,$title_xls,$list_student);
	    
	    $exportFile->saveAsFile("PhieuDuDauKy".date('Ym', strtotime('01-'.$ps_month)).".xls");
	    
	}
	
	
	/**
	 * Xuat du lieu phieu thanh toan cua hoc sinh
	 */
	public function executeExportPayment(sfWebRequest $request)
	{
	    $student_filters = $request->getParameter('receipt_filters');
	    
	    $ps_customer_id  = $student_filters['ps_customer_id'];
	    
	    $ps_workplace_id = $student_filters['ps_workplace_id'];
	    
	    $ps_class_id = $student_filters['ps_class_id'];
	    
	    $ps_month = $student_filters['ps_year_month'];
	    
	    $is_payment = $student_filters['payment_status'];
	    
	    // kiem tra neu khong co quyen loc theo truong thi chi duoc xuat du lieu cua truong dang cong tac
	    if (! myUser::credentialPsCustomers('PS_FEE_REPORT_FILTER_SCHOOL')) {
	        if($ps_customer_id != myUser::getPscustomerID()){
	            $this->forward404Unless ( false, sprintf ( 'Object does not exist .' ) );
	        }
	    }
	    
	    $this->exportReportReceiptStudentStatistic($ps_customer_id,$ps_workplace_id,$ps_class_id,$ps_month,$is_payment);
	    
	    $this->redirect('@ps_receipts');
	}
	
	protected function exportReportReceiptStudentStatistic($ps_customer_id,$ps_workplace_id,$ps_class_id,$ps_month,$is_payment)
	{
	    $exportFile = new ExportStudentLogtimesReportHelper($this);
	    
	    $file_template_pb = 'ps_receipt_student_statistic.xls';
	    
	    $path_template_file = sfConfig::get('sf_web_dir') . '/uploads/export_data/' . $file_template_pb;
	    
	    if($ps_customer_id <= 0){
	        $ps_customer_id = myUser::getPscustomerID();
	    }
	    if($is_payment == ''){
	        $is_payment = 2;
	    }
	    
	    $list_student = Doctrine::getTable('Receipt')->getListStudentFeeReceiptOfMonth($ps_customer_id,$ps_workplace_id,$ps_class_id,$is_payment,$ps_month);
	    
	    $school_name = Doctrine::getTable('MyClass')->getInfoMyClassByCustomer($ps_customer_id, $ps_class_id);
	        
	    //$school_name = Doctrine::getTable('PsCustomer')->findOneById($ps_customer_id);
	    
	    if($ps_class_id > 0){
	        $head_name = $school_name->getClName();
	        
	        if($is_payment == 0){ // chua thanh toan
	            $title_info = $this->getContext ()->getI18N ()->__( 'List student no payment in class %value% month %value1%', array('%value%'=>$head_name,'%value1%'=>$ps_month) );
	        }elseif($is_payment == 1){ // da thanh toan
	            $title_info = $this->getContext ()->getI18N ()->__( 'List student paymented in class %value% month %value1%', array('%value%'=>$head_name,'%value1%'=>$ps_month) );
	        }else{ // tat ca trang thai
	            $title_info = $this->getContext ()->getI18N ()->__( 'Statistic receipt in class %value% month %value1%', array('%value%'=>$head_name,'%value1%'=>$ps_month) );
	        }
	        
	    }else{
	        $head_name = $school_name->getTitle();
	        
	        if($is_payment == 0){ // chua thanh toan
	            $title_info = $this->getContext ()->getI18N ()->__( 'List student no payment in workplace %value% month %value1%', array('%value%'=>$head_name,'%value1%'=>$ps_month) );
	        }elseif($is_payment == 1){ // da thanh toan
	            $title_info = $this->getContext ()->getI18N ()->__( 'List student paymented in workplace %value% month %value1%', array('%value%'=>$head_name,'%value1%'=>$ps_month) );
	        }else{ // tat ca trang thai
	            $title_info = $this->getContext ()->getI18N ()->__( 'Statistic receipt in workplace %value% month %value1%', array('%value%'=>$head_name,'%value1%'=>$ps_month) );
	        }
	    }
	    
	    $title_xls = 'PHIEUTT_'.date('Ym', strtotime('01-'.$ps_month));
	    
	    $exportFile->loadTemplate($path_template_file);
	    
	    $exportFile->setDataExportReceiptStudentStatistic($school_name,$title_info,$title_xls,$list_student);
	    
	    $exportFile->saveAsFile("PhieuThuCuaHocSinh".".xls");
	}	
	
	// Gui thong bao cho tung phu huynh
	public function executeNotication(sfWebRequest $request) {
	    
	    $receipt_id = $request->getParameter ( 'receipt_id');
	    $student_id = $request->getParameter ( 'student_id');
	    
	    $student = Doctrine_Core::getTable('Student')->findOneById($student_id);
	    $receipt = Doctrine_Core::getTable('Receipt')->findOneById($receipt_id );
	    
	    if (!myUser::checkAccessObject($student, 'PS_FEE_REPORT_FILTER_SCHOOL')) {
	        
	        echo $this->getContext()->getI18N()->__('Not roll data');
	        
	        exit(0);
	        
	    } else {
	        
	        $conn = Doctrine_Manager::connection();
	        
	        try {
	            
	            $conn->beginTransaction();
	            
	            if($receipt){
	                
	                $receipt->setNumberPushNotication ( $receipt->getNumberPushNotication() + 1 );
	                $receipt->save();
	                $receipt_date = $receipt->getReceiptDate();
	                $student_name = $student->getFirstName().' '.$student->getLastName();
	                
	                $student_code = $student->getStudentCode();
	                
	                $ps_customer_id = $student->getPsCustomerId();
	                
	                $list_received_id = Doctrine::getTable('sfGuardUser')->getRelativeSentNotificationByStudent($ps_customer_id, $class_id=null, $student_id);
	                
	                if(count($list_received_id) > 0){
	                    
	                    $registrationIds_ios 		= array ();
	                    $registrationIds_android 	= array ();
	                    
	                    foreach ( $list_received_id as $user_nocation ) {
	                        
	                        if ($user_nocation->getNotificationToken() != '') {
	                            
	                            if ($user_nocation->getOsname() == 'IOS'){
	                                array_push ( $registrationIds_ios, $user_nocation->getNotificationToken() );
	                            }else{
	                                array_push ( $registrationIds_android, $user_nocation->getNotificationToken() );
	                            }
	                            
	                        }
	                        
	                    }
	                    
	                    $psI18n = $this->getContext()->getI18N();
	                    if ((count ( $registrationIds_android ) > 0 || count ( $registrationIds_ios ) > 0)) {
	                        
	                        $setting = new \stdClass ();
	                        
	                        $setting->title = $psI18n->__ ( 'Notice of fee receipt').date("m-Y",strtotime($receipt_date));
	                        
	                        $setting->subTitle = $psI18n->__ ( 'Notice of fee receipt of' ). $student_name;
	                        
	                        $setting->tickerText = $psI18n->__ ( 'Fee receipt from KidsSchool.vn' );
	                        
	                        $content = $psI18n->__ ( 'Student' ) . ": " . $student_code . ' - '.$student_name;
	                        
	                        $content .= $psI18n->__ ( 'Notice of fee receipt' ) . ": " . date("m-Y",strtotime($receipt_date)) . '. ';
	                        
	                        $setting->message = $content;
	                        
	                        $setting->lights 	= '1';
	                        $setting->vibrate 	= '1';
	                        $setting->sound 	= '1';
	                        $setting->smallIcon = 'ic_small_notification';
	                        $setting->smallIconOld = 'ic_small_notification_old';
	                        
	                        // Lay avatar nguoi gui thong bao
	                        $profile = $this->getUser()->getGuardUser()->getProfileShort();
	                        
	                        if ($profile && $profile->getAvatar() != '') {
	                            
	                            $url_largeIcon = PreString::getUrlMediaAvatar($profile->getCacheData(), $profile->getYearData(), $profile->getAvatar(), '01');
	                            
	                            $largeIcon = PsFile::urlExists($url_largeIcon) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
	                            
	                        } else {
	                            $largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
	                        }
	                        
	                        $setting->largeIcon 	= $largeIcon;
	                        
	                        $setting->screenCode 	= PsScreenCode::PS_CONST_SCREEN_REPORT_FEE;
	                        $setting->itemId 		= '0';
	                        $setting->clickUrl 		= '';
	                        
	                        // Deviceid registration firebase
	                        if (count($registrationIds_ios) > 0) {
	                            $setting->registrationIds = $registrationIds_ios;
	                            
	                            $notification = new PsNotification ( $setting );
	                            $result = $notification->pushNotification ( PreSchool::PS_CONST_PLATFORM_IOS );
	                        }
	                        
	                        if (count($registrationIds_android) > 0) {
	                            $setting->registrationIds = $registrationIds_android;
	                            
	                            $notification = new PsNotification ( $setting );
	                            $result = $notification->pushNotification ();
	                        }
	                    } // end sent notication
	                }
	            }
	            
	            $conn->commit();
	            
	            return $this->renderPartial('psReceipts/load_number_notication', array(
	                'receipt'  => $receipt,
	            ));
	            
	        } catch (Exception $e) {
	            
	            throw new Exception($e->getMessage());
	            
	            $this->logMessage("ERROR GUI THONG BAO HOC PHI: ".$e->getMessage());
	            
	            $conn->rollback();
	            
	            echo $this->getContext()->getI18N()->__('No send notication was saved failed.');
	            
	            exit();
	            
	        }
	    }
	}

	// Hien thi ra app phu huynh
	protected function executeBatchPublishReceipts(sfWebRequest $request) {

		$ids = $request->getParameter ( 'ids' );
		
		if (myUser::credentialPsCustomers('PS_FEE_REPORT_FILTER_SCHOOL')) {
			$records = Doctrine_Query::create ()->from ( 'Receipt' )->whereIn ( 'id', $ids )->execute ();
		} else {
			$records = Doctrine_Query::create ()->from ( 'Receipt' )->whereIn ( 'id', $ids )->addWhere('ps_customer_id =?', myUser::getPscustomerID())->execute ();
		}
		
		foreach ( $records as $record ) {
			
			$record->setIsPublic ( 1 );
			
			$record->save ();
		}
		
		$this->getUser ()->setFlash ( 'notice', $this->getContext ()->getI18N ()->__ ( 'The selected items have been publish successfully.' ) );
		
		$this->redirect ( '@ps_receipts' );
	}

	// Gui thong bao cho nhieu phu huynh
	protected function executeBatchPushNotication(sfWebRequest $request) {
	    
	    $ids = $request->getParameter ( 'ids' );
	    
	    if (myUser::credentialPsCustomers('PS_FEE_REPORT_FILTER_SCHOOL')) {
			$records = Doctrine_Query::create ()->from ( 'Receipt' )->whereIn ( 'id', $ids )->execute ();
		} else {
			$records = Doctrine_Query::create ()->from ( 'Receipt' )->whereIn ( 'id', $ids )->addWhere('ps_customer_id =?', myUser::getPscustomerID())->execute ();
		}
	    
	    $true = 0;
	    
	    foreach ( $records as $key=>$record ) {
	        
	        if($key == 0){  // chi lay 1 lan
	            $ps_customer_id = $record->getPsCustomerId();
	            $receipt_date = $record->getReceiptDate();
	        }
	        
	        if($record->getIsPublic() > 0){ // neu trang thai cho phu huynh xem thi moi gui thong bao
	            
	            $student_id = $record->getStudentId();
	            
	            $record->setNumberPushNotication ( $record->getNumberPushNotication() + 1 );
	            
	            $record->save ();
	            
	            $true ++;
	            
	            $student = Doctrine::getTable('Student')->findOneById($student_id);
	            
	            $student_name = $student->getFirstName().' '.$student->getLastName();
	            
	            $student_code = $student->getStudentCode();
	            
	            $list_received_id = Doctrine::getTable('sfGuardUser')->getRelativeSentNotificationByStudent($ps_customer_id, $class_id=null, $student_id);
	            
	            if(count($list_received_id) > 0){
	                
	                $registrationIds_ios 		= array ();
	                $registrationIds_android 	= array ();
	                
	                foreach ( $list_received_id as $user_nocation ) {
	                    
	                    if ($user_nocation->getNotificationToken() != '') {
	                        
	                        if ($user_nocation->getOsname() == 'IOS'){
	                            array_push ( $registrationIds_ios, $user_nocation->getNotificationToken() );
	                        }else{
	                            array_push ( $registrationIds_android, $user_nocation->getNotificationToken() );
	                        }
	                        
	                    }
	                    
	                }
	                
	                $psI18n = $this->getContext()->getI18N();
	                if ((count ( $registrationIds_android ) > 0 || count ( $registrationIds_ios ) > 0)) {
	                    
	                    $setting = new \stdClass ();
	                    
	                    $setting->title = $psI18n->__ ( 'Notice of fee receipt').date("m-Y",strtotime($receipt_date));
	                    
	                    $setting->subTitle = $psI18n->__ ( 'Notice of fee receipt of' ). $student_name;
	                    
	                    $setting->tickerText = $psI18n->__ ( 'Fee receipt from KidsSchool.vn' );
	                    
	                    $content = $psI18n->__ ( 'Student' ) . ": " . $student_code . ' - '.$student_name;
	                    
	                    $content .= $psI18n->__ ( 'Notice of fee receipt' ) . ": " . date("m-Y",strtotime($receipt_date)) . '. ';
	                    
	                    $setting->message = $content;
	                    
	                    $setting->lights 	= '1';
	                    $setting->vibrate 	= '1';
	                    $setting->sound 	= '1';
	                    $setting->smallIcon = 'ic_small_notification';
	                    $setting->smallIconOld = 'ic_small_notification_old';
	                    
	                    // Lay avatar nguoi gui thong bao
	                    $profile = $this->getUser()->getGuardUser()->getProfileShort();
	                    
	                    if ($profile && $profile->getAvatar() != '') {
	                        
	                        $url_largeIcon = PreString::getUrlMediaAvatar($profile->getCacheData(), $profile->getYearData(), $profile->getAvatar(), '01');
	                        
	                        $largeIcon = PsFile::urlExists($url_largeIcon) ? $url_largeIcon : PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
	                        
	                    } else {
	                        $largeIcon = PreSchool::PS_CONST_API_URL_IMAGE_DEFAULT_APPLOGO;
	                    }
	                    
	                    $setting->largeIcon 	= $largeIcon;
	                    
	                    $setting->screenCode 	= PsScreenCode::PS_CONST_SCREEN_REPORT_FEE;
	                    $setting->itemId 		= '0';
	                    $setting->clickUrl 		= '';
	                    
	                    // Deviceid registration firebase
	                    if (count($registrationIds_ios) > 0) {
	                        $setting->registrationIds = $registrationIds_ios;
	                        
	                        $notification = new PsNotification ( $setting );
	                        $result = $notification->pushNotification ( PreSchool::PS_CONST_PLATFORM_IOS );
	                    }
	                    
	                    if (count($registrationIds_android) > 0) {
	                        $setting->registrationIds = $registrationIds_android;
	                        
	                        $notification = new PsNotification ( $setting );
	                        $result = $notification->pushNotification ();
	                    }
	                } // end sent notication
	            }
	        }
	    }
	    if($true == 0){
	        $this->getUser ()->setFlash ( 'error', $this->getContext ()->getI18N ()->__ ( 'You must at least select one item is public' ) );
	    }else{
	        $this->getUser ()->setFlash ( 'notice', $this->getContext ()->getI18N ()->__ ( 'The selected items have been send notication successfully.' ) );
	    }
	    $this->redirect ( '@ps_receipts' );
	}
	
	protected function processForm(sfWebRequest $request, sfForm $form) {

		$form->bind ( $request->getParameter ( $form->getName () ), $request->getFiles ( $form->getName () ) );
		
		if ($form->isValid ()) {
			
			$notice = $form->getObject ()->isNew () ? 'The item was created successfully.' : 'The item was updated successfully.';
			
			try {
				
				//Luu history
				if (!$form->getObject ()->isNew()) {// Neu la sua
					
					$receipt_id = $form->getObject ()->getId();
					
					$ps_receipt = Doctrine::getTable ( 'Receipt' )->findOneById($receipt_id);
					
					if ($ps_receipt) {
						
						// Lay phieu bao
						$ps_fee_reports = Doctrine::getTable ( 'PsFeeReports' )->findPsFeeReportOfStudentByDate($ps_receipt->getStudentId(), strtotime($ps_receipt->getReceiptDate()));
						
						$history_content = '';
						
						$history_content .= 'ID truong: '.$ps_receipt->getPsCustomerId().'\n';
						
						if ($ps_fee_reports) {
							$history_content .= 'ID phieu bao: '.$ps_fee_reports->getId().'\n';
							$history_content .= 'Ma phieu bao: '.$ps_fee_reports->getPsFeeReportNo().'\n';						
							$history_content .= 'Tong tien phai nop(chua co phi nop muon): '.$ps_fee_reports->getReceivable().'\n';
						}
						
						$history_content .= 'ID phieu thu: '.$receipt_id.'\n';
						$history_content .= 'Tieu de: '.$ps_receipt->getTitle().'\n';
						$history_content .= 'Ma phieu thu: '.$ps_receipt->getReceiptNo().'\n';
						$history_content .= 'Phieu cua thang: '.$ps_receipt->getReceiptDate().'\n';						
						$history_content .= 'So tien da nop: '.$ps_receipt->getCollectedAmount().'\n';
						$history_content .= 'So du: '.$ps_receipt->getBalanceAmount().'\n';
						$history_content .= 'Du thang truoc: '.$ps_receipt->getBalanceLastMonthAmount().'\n';
						$history_content .= 'Phi nop muon: '.$ps_receipt->getLatePaymentAmount().'\n';
						$history_content .= 'La import(1-Import; 0-Khong): '.$ps_receipt->getIsImport().'\n';
						$history_content .= 'Trang thai thanh toan: '.$ps_receipt->getPaymentStatus().'\n';
						$history_content .= 'ID nguoi than nop tien: '.$ps_receipt->getRelativeId().'\n';
						$history_content .= 'Ten nguoi nop tien: '.$ps_receipt->getPaymentRelativeName().'\n';
						$history_content .= 'Ngay nop tien: '.$ps_receipt->getPaymentDate().'\n';
						$history_content .= 'Hinh thuc nop tien(TM: Tien mat ,CK: Chuyen khoan,QT: Quet the): '.$ps_receipt->getPaymentType().'\n';
						$history_content .= 'Ten thu ngan: '.$ps_receipt->getCashierName().'\n';
						$history_content .= 'Ghi chu cua phieu: '.$ps_receipt->getNote().'\n';
						$history_content .= 'Hien thi ra APP phu huynh: '.$ps_receipt->getIsPublic().'\n';
						$history_content .= 'Ghi chu sua phieu thu truc tiep: '.$ps_receipt->getNoteEdit().'\n';
						$history_content .= 'ID user tao ban dau: '.$ps_receipt->getUserCreatedId().'\n';
						$history_content .= 'ID user cap nhat cuoi: '.$ps_receipt->getUserUpdatedId().'\n';
						
						$ps_history_fees = new PsHistoryFees();
						
						$ps_history_fees->setPsCustomerId($ps_receipt->getPsCustomerId());
						$ps_history_fees->setPsReceiptId($ps_receipt->getId());
						$ps_history_fees->setReceiptNo($ps_receipt->getReceiptNo());
						$ps_history_fees->setReceiptDate($ps_receipt->getReceiptDate());
						$ps_history_fees->setStudentId($ps_receipt->getStudentId());
						$ps_history_fees->setPsAction('edit');
						$ps_history_fees->setHistoryContent($history_content);
						$ps_history_fees->setCreatedAt(date("Y-m-d H:i:s"));
						$ps_history_fees->setUpdatedAt(date("Y-m-d H:i:s"));
						$ps_history_fees->setUserCreatedId(sfContext :: getInstance()->getUser()->getGuardUser()->getId());
						
						$ps_history_fees->save();
					}
				
				}
				
				$receipt = $form->save ();
				
				// Lấy phiếu báo
				$psFeeReport = Doctrine::getTable ( 'PsFeeReports' )->findPsFeeReportOfStudentByDate($receipt->getStudentId(), strtotime($receipt->getReceiptDate()));
				
				if ($psFeeReport) {
					$psFeeReport->setReceivable($form->getValue('ps_fee_report_amount'));
					$psFeeReport->save();
				}				
				
			} catch ( Doctrine_Validator_Exception $e ) {
				
				$errorStack = $form->getObject ()->getErrorStack ();
				
				$message = get_class ( $form->getObject () ) . ' has ' . count ( $errorStack ) . " field" . (count ( $errorStack ) > 1 ? 's' : null) . " with validation errors: ";
				
				foreach ( $errorStack as $field => $errors ) {
					$message .= "$field (" . implode ( ", ", $errors ) . "), ";
				}
				
				$message = trim ( $message, ', ' );
				
				$this->getUser ()->setFlash ( 'error', $message );
				
				return sfView::SUCCESS;
			}
			
			$this->dispatcher->notify ( new sfEvent ( $this, 'admin.save_object', array (
					'object' => $receipt ) ) );
			
			if ($request->hasParameter ( '_save_and_add' )) {
				
				$this->getUser ()->setFlash ( 'notice', $notice . ' You can add another one below.' );
				
				$this->redirect ( '@ps_receipts_new' );
				
			} else {
				
				$this->getUser ()->setFlash ( 'notice', $notice );
				
				$this->redirect ( array (
						'sf_route' => 'ps_receipts_edit',
						'sf_subject' => $receipt ) );
			}
			
		} else {
			
			$this->getUser ()->setFlash ( 'error', 'The item has not been saved due to some errors.', false );
			
		}
	}
}
