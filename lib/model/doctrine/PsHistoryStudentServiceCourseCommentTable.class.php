<?php

/**
 * PsHistoryStudentServiceCourseCommentTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsHistoryStudentServiceCourseCommentTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsHistoryStudentServiceCourseCommentTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsHistoryStudentServiceCourseComment' );
	}

	/**
	 * Hien thi lich su danh gia hoc tap cua hoc sinh
	 *
	 * @author Phung Van Thanh
	 */
	public function getHistoryStudentCourseComment($student_id, $date_at_from = null, $date_at_to = null) {

		// echo $student_id; die();
		$q = $this->createQuery ( 'hsc' )
			->select ( '
            hsc.id as hsc_id, hsc.ps_service_course_schedule_id as ps_service_course_schedule_id,
            hsc.ps_action as ps_action, hsc.history_content as history_content, hsc.created_at as created_at
        ' )
			->
		addWhere ( 'hsc.student_id = ?', $student_id );

		if ($date_at_from != '')
			$q->andWhere ( ' DATE_FORMAT(hsc.created_at,"%Y%m%d") >= ?', date ( "Ymd", strtotime ( $date_at_from ) ) );
		if ($date_at_to != '')
			$q->andWhere ( ' DATE_FORMAT(hsc.created_at,"%Y%m%d") <= ?', date ( "Ymd", strtotime ( $date_at_to ) ) );

		$q->orderBy ( 'hsc.created_at desc' );

		return $q->execute ();
	}
}