<?php
/**
 * @project_name
 * @subpackage     interpreter
 *
 * @file RulePassword.php
 * Kiem tra dieu kien cua password nhap vao
 * Explaining $\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$
 *   $ = beginning of string
 *   \S* = any set of characters
 *   (?=\S{8,}) 	= of at least length 8
 *   (?=\S*[a-z]) 	= containing at least one lowercase letter
 *   (?=\S*[A-Z]) 	= and at least one uppercase letter
 *   (?=\S*[\d]) 	= and at least one number
 *   (?=\S*[\W]) 	= and at least a special character (non-word characters)
 *   $ = end of the string
 * 
 * @author thangnc@newwaytech.vn
 * @version 1.0 01-05-2017
 */
namespace App\PsValidator;

use Respect\Validation\Validator as v;
use App\PsUtil\PsI18n;

class PsPasswordValidation {
	
	protected $min 	= 0;
	protected $pattern = '';	
	protected $psI18n;
	
	public function __construct($min, $pattern, $lang = APP_CONFIG_LANGUAGE) {
		
		$this->min 		= $min;
		
		$this->pattern 	= $pattern;
		
		$this->psI18n 		= new PsI18n ( APP_CONFIG_LANGUAGE );		
	}
	
	public function validate($name, $input) {
		
		$success1 = v::stringType ()->length ($this->min, null )->validate ( $input ); // true
		
		$success2 = v::regex ( $this->pattern )->validate($input);
		
		//$return[$name]  	= ($success1 || !$success2) ? $this->psI18n->__ ( 'msg_err_regular_password' ) : null;
		
		return ($success1 && $success2);
	}
}