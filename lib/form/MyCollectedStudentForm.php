<?php
/**
 * Student form.
 *
 * @package    Preschool
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class MyCollectedStudentForm extends BaseCollectedStudentForm {

	public function configure() {

		$this->useFields ( array (
				'id' ) );
	}

	public function bind(array $taintedValues = null, array $taintedFiles = null) {

		$new_forms = new BaseForm ();

		if (isset ( $taintedValues ['new'] )) {
			foreach ( $taintedValues ['new'] as $key => $new_form ) {
				$collected_student = new CollectedStudent ( false );
				$collected_student->setAmount ( $new_form ['amount'] );
				$collected_student->setStudentId ( $new_form ['student_id'] );
				$collected_student->setReceivableStudentId ( $new_form ['receivable_student_id'] );

				$collected_student_form = new CollectedStudentForm ( $collected_student );

				$new_forms->embedForm ( $key, $collected_student_form );

				// date("Y-m-d",mktime(0, 0, 0, (date("m") + 1), 0, date("Y")))
				// Chu y inser vao bang recurrence
				if (isset ( $new_form ['expires_at'] ) && $new_form ['expires_at'] != null && $new_form ['expires_at'] ['year']) {

					$tmp_time = mktime ( 0, 0, 0, $new_form ['expires_at'] ['month'] + 1, 0, $new_form ['expires_at'] ['year'] );

					$taintedValues ['new'] [$key] ['expires_at'] ['day'] = date ( 'd', $tmp_time );
				}
				// Sau khi insert vao bang recurrence thi se bo di
				unset ( $taintedValues ['new'] [$key] ['amount_default'], $taintedValues ['new'] [$key] ['service_id'], $taintedValues ['new'] [$key] ['old_expires_at'], $taintedValues ['new'] [$key] ['by_number'] );
			}

			$this->embedForm ( 'new', $new_forms );
		}
		parent::bind ( $taintedValues, $taintedFiles );
	}
}