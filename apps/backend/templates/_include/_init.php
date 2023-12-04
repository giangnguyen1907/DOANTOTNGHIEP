<?php
/**
 * @project_name
 * @subpackage     interpreter 
 *
 * @file _init.php
 * @filecomment filecomment
 * @package_declaration package_declaration
 * @author PC
 * @version 1.0 31-03-2017 -  16:25:33
 */
if ($sf_user->isAuthenticated () && myUser::isAdministrator ()) {

	$ADpsCustomer_form_filter = new sfForm ();
	$ADpsCustomer_form_filter->setWidget ( 'ADpsCustomerID', new sfWidgetFormDoctrineChoice ( array (
			'model' => 'PsCustomer',
			'query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers (),
			'add_empty' => null ), array (
			'onchange' => 'this.form.submit();' ) ) );
	$ADpsCustomer_form_filter->setDefault ( 'ADpsCustomerID', myUser::getPscustomerID () );
}