<?php

/**
 * RecurrenceService form.
 *
 * @package    Preschool
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class RecurrenceServiceForm extends BaseRecurrenceServiceForm {

	public function configure() {

		$this->removeFields ();
	}

	protected function removeFields() {

		unset ( $this ['created_at'], $this ['updated_at'], $this ['effectives_at'] );
	}
}
