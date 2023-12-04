<?php

/**
 * CollectedStudent
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    Preschool
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class CollectedStudent extends BaseCollectedStudent {

	public function getCollectedByDate($student_id, $date) {

		return Doctrine::getTable ( "CollectedStudent" )->getCollectedByDate ( $student_id, $date );
	}

	/**
	 * Ham xu ly Xac nhan thanh toan
	 *
	 * @author Nguyen Chien Thang - thangnc@ithanoi.com
	 * @param array $arr_student_id
	 *        	- mang chua id cua hoc sinh
	 * @param timestamp $date
	 *        	*
	 */
	public function saveCollectedStudent($arr_student_id, $date) {

		$conn = Doctrine_Manager::connection ();
		try {

			$conn->beginTransaction ();

			// Tim cac dich vu (chua co Ve muon)
			// $services = Doctrine:: getTable('Service')->findByStudent($arr_student_id, $date);
			$services = Doctrine::getTable ( 'Service' )->findAllServiceByStudent ( $arr_student_id, $date );

			// Xu ly
			foreach ( $services as $key => $service ) {

				$student_id = ($service->getStudentId () == null) ? $service->get ( 'sd_student_id' ) : $service->getStudentId ();

				if (! $student_id) {
					continue;
				}

				$serviceDetail = $service->findByDate ( $date );
				$amount = 0;

				if ($serviceDetail) {
					$amount = $serviceDetail->getAmount ();
				}

				// So lan su dung
				$by_number = ($service->get ( 'enable_roll' ) == 0) ? 1 : $service->get ( 'number_uses' );

				// Tim don gia trong recurrence_service
				$recurrence_service = Doctrine::getTable ( 'RecurrenceService' )->getAmountByStudentService ( $student_id, $service->getId (), $date );

				if ($recurrence_service) {
					$amount = $recurrence_service->getAmount ();
				}

				$student = Doctrine::getTable ( 'Student' )->find ( $student_id );

				if ($student && $student->getDeletedAt () == null) {

					// insert vao ReceivableStudent
					if ($service->get ( 'rs_id' ) == null) {

						$receivable_student = new ReceivableStudent ( false );

						$receivable_student->setStudentId ( $student->getId () );
						$receivable_student->setServiceId ( $service->getId () );
						$receivable_student->setReceivableId ( null );
						$receivable_student->setAmount ( $amount * $by_number );
						$receivable_student->setByNumber ( $by_number );
						$receivable_student->setIsLate ( 0 );
						$receivable_student->setReceivableAt ( date ( 'Y-m', $date ) . '-01' );
						$receivable_student->setNote ( 'Thanh toán bổ sung' );

						$receivable_student->save ();

						$receivable_student_id = $receivable_student->getId ();
					} else {
						$receivable_student_id = $service->get ( 'rs_id' );
					}

					// insert vao CollectedStudent
					if (! $service->get ( 'cs_id' )) {

						$collected_student = new CollectedStudent ( false );

						$collected_student->setAmount ( $amount * $by_number );
						$collected_student->setStudentId ( $student->getId () );
						$collected_student->setCollectedAt ( date ( 'Y-m', $date ) . '-01' );
						$collected_student->setReceivableStudentId ( $receivable_student_id );
						$collected_student->setNote ( 'Thanh toán bổ sung' );

						$collected_student->save ();
					}
				} // End check student
			} // end foreach

			foreach ( $arr_student_id as $student_id ) {
				$student = Doctrine::getTable ( 'Student' )->find ( $student_id );

				if ($student && $student->getDeletedAt () == null) {

					// Tim cac khoan phai thu bat thuong
					$receivableOnly = Doctrine::getTable ( 'ReceivableStudent' )->getReceivableOnly ( $student->getId (), $date );

					foreach ( $receivableOnly as $receivable ) {

						if ($receivable->get ( 'c_s_id' ) == null) {

							$collected_student = new CollectedStudent ( false );
							$collected_student->setAmount ( $receivable->getAmount () ); // Thu tien = dung gia tri phai thu
							$collected_student->setStudentId ( $student->getId () );
							$collected_student->setCollectedAt ( date ( 'Y-m', $date ) . '-01' );
							$collected_student->setReceivableStudentId ( $receivable->getId () );
							$collected_student->setNote ( 'Thanh toán bổ sung' );
							$collected_student->save ();
						}
					}

					// Tim Khoan da thu bat thuong
					// Ko can

					// Cac khoan phat sinh do ve muon
					$lateTotal = 0;
					$lateObj = $student->getReceivableStudentLateByDate ( $date ); // Neu chua thanh toan

					if ($lateObj == null) {

						$constant = new Constant ();
						$lateValue = $constant->getValueByName ( "LATE_MONEY" ); // Lay so tien ve muon theo gio (cua thang truoc day)
						$lateTimes = $student->getNumberTobeLate ( strtotime ( '-1 month', $date ) );

						$hours = 0;

						if ($lateTimes != null) {
							$hours = PreSchool::getLogtimeTobeLate ( $lateTimes->getTotal () );
							$lateTotal = $hours * $lateValue->getValue ();
						}

						$receivable_student = new ReceivableStudent ( false );

						$receivable_student->setStudentId ( $student->getId () );
						$receivable_student->setServiceId ( null );
						$receivable_student->setReceivableId ( null );
						$receivable_student->setAmount ( $lateTotal );
						$receivable_student->setByNumber ( $hours );
						$receivable_student->setIsLate ( 1 );
						$receivable_student->setReceivableAt ( date ( 'Y-m', $date ) . '-01' );
						$receivable_student->setNote ( 'Thanh toán bổ sung' );

						$receivable_student->save ();

						$rece_student_id = $receivable_student->getId ();

						$collected_student = new CollectedStudent ( false );

						$collected_student->setAmount ( $lateTotal ); // Do day la truong hop thanh toan ly tuong mac dinh dong du so tien
						$collected_student->setStudentId ( $student->getId () );
						$collected_student->setCollectedAt ( date ( 'Y-m', $date ) . '-01' );
						$collected_student->setReceivableStudentId ( $rece_student_id );
						$collected_student->setNote ( 'Thanh toán bổ sung' );
						$collected_student->save ();
					}

					// Cap nhat du dau ky
					$balance = new Balance ();
					$balance->saveOpeningBalance ( $student, $date, $lateTotal );
				}
			}

			$conn->commit ();

			return true;
		} catch ( Exception $e ) {
			throw new Exception ( $e->getMessage () );
			$conn->rollback ();

			return false;
		} // end catch
	} // end function
}