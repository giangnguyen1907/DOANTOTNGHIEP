<?php

// http://stackoverflow.com/questions/2578397/symfony-1-4-custom-error-message-for-csrf-in-forms
// http://discover-symfony.blogspot.com/2011/03/how-to-change-csrf-attack-detected-in.html
/*
 * class myValidatorCSRFToken extends sfValidatorBase {
 * /*
 * protected function configure($options = array (), $messages = array ()) {
 * parent :: configure($options, $messages);
 * $this->addMessage('csrf_attack', 'Your session has expired. Please return to the home page and try again.');
 * }
 * protected function doClean($value) {
 * try {
 * return parent :: doClean($value);
 * } catch (sfValidatorError $e) {
 * throw new sfValidatorErrorSchema($this, array (
 * $e
 * ));
 * }
 * }
 */

/*
 * protected function configure($options = array (), $messages = array ()) {
 * $this->addRequiredOption('token');
 * $this->setOption('required', true);
 * $this->addMessage('csrf_attack', 'Your session has expired. Please return to the home page and try again.');
 * }
 * protected function doClean($value) {
 * if ($value != $this->getOption('token')) {
 * $exception = new sfValidatorError($this, 'csrf_attack');
 * throw new sfValidatorErrorSchema($this, array (
 * $exception
 * ));
 * }
 * return $value;
 * }
 * }
 */
?>