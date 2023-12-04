<?php

/**
 * Service
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    Preschool
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Service extends BaseService {

	/*
	 * public function getServiceDetail(){
	 * return $this->getServiceDetailByDate(time());
	 * }
	 */
	public function getAllServiceSplit() {

		// return Doctrine::getTable('ServiceSplit')->findByServiceId($this->getId());
		return Doctrine::getTable ( 'ServiceSplit' )->findByServiceId ( $this->getId () );
	}

	/**
	 * Lay gia gia cua dich vu tuong ung voi thoi diem nhap
	 *
	 * @param unknown_type $date
	 */
	public function getServiceDetailByDate($date) {

		// return Doctrine::getTable('ServiceDetail')->getServiceDetailByDate($this->getId(), $date);
		return Doctrine::getTable ( 'ServiceDetail' )->findOneServiceDetailByDate ( $this->getId (), $date );
	}

	/**
	 *
	 *
	 * FUNCTION: getCountServiceOption()
	 *
	 * @desc: this object -> getCountServiceOption()
	 */
	public function getCountSubjectOption() {

		return Doctrine_Core::getTable ( 'FeatureOptionSubject' )->getCountSubjectOption ( $this->getId () );
	}

	// Lay so luong hoc sinh đã từng đăng ký sử dụng bao gồm cả dịch vụ đã đánh dấu xóa
	public function getCountStudentService() {

		return Doctrine_Core::getTable ( 'StudentService' )->getCountStudentServiceOfServiceId ( $this->getId () );
	}

	// Lay so luong hoc sinh da dung trong cac lan diem danh
	public function getCountStudentServiceDiary() {

		return Doctrine_Core::getTable ( 'StudentServiceDiary' )->getCountStudentServiceDiaryOfServiceId ( $this->getId () );
	}

	// Lay so luong lop dang ky dich vu nay
	public function getCountClassService() {

		return Doctrine_Core::getTable ( 'ClassService' )->getCountClassServiceOfServiceId ( $this->getId () );
	}

	// Lay so luong khoa hoc da mo cho mon hoc nay
	public function getCountPsServiceCourses() {

		return Doctrine_Core::getTable ( 'PsServiceCourses' )->getNumberServiceCoursesByServiceId ( $this->getId () );
	}

	/**
	 * Tim tat ca cac hoc sinh su dung dich vu *
	 */
	public function getStudentsByPsServiceId() {

		return Doctrine_Core::getTable ( 'StudentService' )->getStudentsByPsServiceId ( $this->getId () );
	}

	public function getMaxOrder() {

		return Doctrine::getTable ( 'Service' )->getMaxOrder ( $this->getPsSchoolYearId (), $this->getPsCustomerId (), $this->setPsWorkplaceId () );
	}

	public function getCountServiceInServiceSplit() {

		return Doctrine::getTable ( 'Service' )->getCountServiceInServiceSplit ( $this->getPsSchoolYearId (), $this->getPsCustomerId (), $this->setPsWorkplaceId () );
	}

	// =================== END: NEW VESION =============================================>
	public function getServicesByStudent($student_id, $date) {

		return Doctrine::getTable ( 'Service' )->getServicesByStudent ( $student_id, $date );
	}

	/**
	 * Tim tat ca nhung dich vu co lien quan toi hoc sinh tai thoi diem kiem tra
	 * Bao gom: Dich vu dang ky su dung + dich vu khong dang ky nhung co dung + Dich vu co mat
	 * trong cac khoan phi(receivable_student) chua hoac da thanh toan
	 *
	 * @param int $student_id
	 * @param datime $date
	 * @return list
	 */
	public function findServicesRelatedStudent($student_id, $date) {

		return Doctrine::getTable ( 'Service' )->findServicesRelatedStudent ( $student_id, $date );
	}

	public function findAllServiceByStudent($arr_student_id, $date) {

		return Doctrine::getTable ( 'Service' )->findAllServiceByStudent ( $arr_student_id, $date );
	}

	/**
	 * Tim tat ca cac lop boi dich vu *
	 */
	public function findAllClassByService() {

	}

	/**
	 * Lay gia gia cua dich vu tuong ung voi thoi diem xem
	 *
	 * @param unknown_type $date
	 */
	public function findByDate($date) {

		return Doctrine::getTable ( 'ServiceDetail' )->findByDate ( $this->getId (), $date );
	}

	public function getSummaryUsingService($student_id, $date) {

		return Doctrine::getTable ( 'Service' )->getSummaryUsingService ( $student_id, $date );
	}

	public function findOneSummaryUsingService($student_id, $date) {

		return Doctrine::getTable ( 'Service' )->findOneSummaryUsingService ( $this->getId (), $student_id, $date );
	}

	public function findOneByServiceOfStudent($student_id, $date) {

		return Doctrine::getTable ( 'ReceivableStudent' )->findOne ( $student_id, $this->getId (), $date );
	}

	public function findOneOfStudent($student_id, $date) {

		return Doctrine::getTable ( 'ReceivableStudent' )->findOneOfStudent ( $this->getId (), $student_id, $date );
	}

	public function findOneInReceivableStudent($student_id, $date) {

		return Doctrine::getTable ( 'ReceivableStudent' )->findByReceivableStudent ( $this->getId (), $student_id, $date );
	}

	public function getRecurrenceWithAmount($student_id, $date) {

		return Doctrine::getTable ( 'RecurrenceService' )->getAmountByStudentService ( $student_id, $this->getId (), $date );
	}

	public function getServicesByClass($myclass_id) {

		return Doctrine::getTable ( 'Service' )->getServicesByClass ( $myclass_id );
	}

	public function getStudentServices() {

		return Doctrine::getTable ( 'Service' )->getServicesByStudentId ( $this->getId () );
	}

	// lay' don gia' phu thuoc dieu kien date
	public function getAmountByStudentService($student_id, $date) {

		return Doctrine::getTable ( 'RecurrenceService' )->getAmountByStudentService ( $student_id, $this->getId (), $date );
	}

	public function getAmountByReceivableStudent($student_id, $date) {

		return Doctrine::getTable ( 'ReceivableStudent' )->getAmountByReceivableStudent ( $student_id, $this->getId (), $date );
	}

	/**
	 * NEW *
	 */
	public function findServiceSplitBySpentNumber($spent_number) {

		return Doctrine::getTable ( 'Service' )->findServiceSplitBySpentNumber ( $this->getId (), $spent_number );
	}

	public function findServiceSplitBySpentNumber2($service_id, $spent_number) {

		return Doctrine::getTable ( 'Service' )->findServiceSplitBySpentNumber ( $service_id, $spent_number );
	}

	public function findAllServiceSplit($service_id) {

		return Doctrine::getTable ( 'ServiceSplit' )->findByServiceId ( $service_id );
	}

	public function findAllStudentServiceByStudent($student_id) {

		return Doctrine::getTable ( 'StudentService' )->findAllServiceByStudent ( $student_id );
	}
}