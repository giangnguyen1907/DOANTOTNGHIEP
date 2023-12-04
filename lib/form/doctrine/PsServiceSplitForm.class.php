<?php

/**
 * PsServiceSplit form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsServiceSplitForm extends BasePsServiceSplitForm {

	public function configure() {

		// unset($this['service_id']);

		// echo 'AAAAAAAAA';
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}
