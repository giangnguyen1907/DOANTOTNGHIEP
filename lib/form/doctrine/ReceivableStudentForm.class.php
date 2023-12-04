<?php

/**
 * ReceivableStudent form.
 *
 * @package    Preschool
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ReceivableStudentForm extends BaseReceivableStudentForm {

	public function configure() {

		$this->removeFields ();
	}

	protected function removeFields() {

		unset ( $this ['receivable_at'], $this ['created_at'], $this ['updated_at'], $this ['student_id'], $this ['service_id'] );
	}
}
