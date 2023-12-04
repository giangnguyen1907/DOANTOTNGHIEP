<?php

/**
 * ReceivableStudent form.
 *
 * @package    Preschool
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ReceivableStudentMultyForm extends BaseReceivableStudentForm {

	public function configure() {

	}

	public function iniForm() {

		$new_receivables = new BaseForm ();

		$this->embedForm ( 'new', $new_receivables );
	}
}
