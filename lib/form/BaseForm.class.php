<?php

/**
 * Base project form.
 * 
 * @package    Preschool
 * @subpackage form
 * @author     Your name here 
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class BaseForm extends sfFormSymfony {

	public function addCSRFProtection($secret = null) {

		parent::addCSRFProtection ( $secret );
		if (isset ( $this->validatorSchema [self::$CSRFFieldName] )) {
			$this->validatorSchema [self::$CSRFFieldName]->setMessage ( 'csrf_attack', 'This session has expired. Please refresh and try again.' );
			$this->getWidgetSchema ()
				->getFormFormatter ()
				->setNamedErrorRowFormatInARow ( "Token: <li>%error%</li>\n" );
		}
	}

	/*
	 * public function addCSRFProtection($secret = null) {
	 * parent :: addCSRFProtection($secret);
	 * $validatorSchema = $this->getValidatorSchema();
	 * if (isset ($validatorSchema[self :: $CSRFFieldName])) {
	 * $validatorSchema[self :: $CSRFFieldName] = new myValidatorCSRFToken($validatorSchema[self :: $CSRFFieldName]->getOptions());
	 * }
	 * }
	 */

	/**
	 * addFormI18nChoiceCountry()
	 *
	 * @author Nguyen Chien Thang
	 *        
	 * @param
	 *        	string - input form
	 * @return widgetSchema list I18n Country
	 */
	public function addFormI18nChoiceCountry($inputText = 'country_code', $default_value = null) {

		$culture = sfContext::getInstance ()->getUser ()
			->getCulture ();

		$this->widgetSchema [$inputText] = new sfWidgetFormI18nChoiceCountry ( array (
				'culture' => $culture,
				'add_empty' => '-Select country-' ), array (
				'class' => 'select2' ) );

		if ($default_value)
			$this->setDefault ( $inputText, $default_value );
		else
			$this->setDefault ( $inputText, strtoupper ( sfConfig::get ( 'app_ps_default_country' ) ) );

		$this->validatorSchema [$inputText] = new sfValidatorString ();
	}

	public function checkUserIdField() {

		if (isset ( $this->widgetSchema ['user_created_id'] ) && isset ( $this->widgetSchema ['user_updated_id'] )) {
			return true;
		}

		return false;
	}
}