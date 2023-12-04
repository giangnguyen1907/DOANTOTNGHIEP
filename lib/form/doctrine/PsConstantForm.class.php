<?php
/**
 * PsConstant form.
 *
 * @package    quanlymamnon.vn
 * @subpackage form
 * @author     quanlymamnon.vn <contact@quanlymamnon.vn>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PsConstantForm extends BasePsConstantForm {

	public function configure() {

		/*
		 * $this->validatorSchema['name']->setOption('required', true);
		 * $this->addRequiredFields('name');
		 * $this->widgetSchema['name']->setOption('is_hidden', false);
		 * $this->widgetSchema['name']->setOption('type', 'text');
		 */
		/*
		 * $this->widgetSchema['name'] = new sfWidgetFormInputText();
		 * $this->validatorSchema['name']->setOption('required', true);
		 * $this->addRequiredFields('name');
		 */
		$this->validatorSchema ['c_code'] = new sfValidatorRegex ( array (
				'required' => true,
				'pattern' => '/^[a-zA-Z0-9_\-]+$/' ), array (
				'invalid' => 'Invalid name (includes only the characters a-zA-Z0-9_-)' ) );

		if ($this->getObject ()
			->getIsNotremove () == 1) {
			$this->widgetSchema ['c_code']->setAttribute ( 'readonly', 'readonly' );
		}

		$this->addBootstrapForm ();
		$this->widgetSchema ['note']->setAttribute ( 'class', 'form-control' );

		unset ( $this ['is_notremove'] );
	}

	public function updateObject($values = null) {

		return parent::baseUpdateObject ( $values );
	}
}