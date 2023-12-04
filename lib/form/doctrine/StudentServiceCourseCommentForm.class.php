<?php

/**
 * StudentServiceCourseComment form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn - ntsc279@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class StudentServiceCourseCommentForm extends BaseStudentServiceCourseCommentForm {

	public function configure() {

		if ($this->getObject ()
			->isNew ()) {
			$student_id = sfContext::getInstance ()->getRequest ()
				->getParameter ( 'student_id' );
			$course_schedule_id = sfContext::getInstance ()->getRequest ()
				->getParameter ( 'course_schedule_id' );
		} else {

			$student_id = $this->getObject ()
				->getStudentId ();
			$course_schedule_id = $this->getObject ()
				->getPsServiceCourseScheduleId ();
		}

		// echo $student_id;
		// echo '-'.$course_schedule_id;
		// die();
		// if ( $this->getObject ()->isNew ()) {
		$this->widgetSchema ['student_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'Student',
				'query' => Doctrine::getTable ( 'Student' )->setSqlStudentById ( $student_id ) ), array (
				'class' => 'select2',
				'style' => "min-width:200px;" ) );

		$this->validatorSchema ['student_id'] = new sfValidatorDoctrineChoice ( array (
				'model' => 'Student',
				'required' => true ) );

		$this->widgetSchema ['ps_service_course_schedule_id'] = new sfWidgetFormDoctrineChoice ( array (
				'model' => 'Student',
				'query' => Doctrine::getTable ( 'PsServiceCourseSchedules' )->setSqlCourseScheduleById ( $course_schedule_id ) ), array (
				'class' => 'select2',
				'style' => "min-width:200px;" ) );
		$this->addBootstrapForm ();
		// }
	}
}
