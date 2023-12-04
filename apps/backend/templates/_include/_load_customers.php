<?php
/**
 * @project_name
 * @subpackage     interpreter 
 *
 * @file _load_customers.php
 * @filecomment filecomment
 * @package_declaration package_declaration
 * @author PC
 * @version 1.0 31-03-2017 -  16:28:32
 */
if ($sf_user->isAuthenticated () && myUser::isAdministrator ()) {

	// Thong tin don vi cua user login
	$ps_customer = myUser::getPsCustomerById ( myUser::getUser ()->getPsCustomerId () );

	// $ADpsCustomer_form_filter = new sfForm();
	$ADpsCustomer_form_filter = new sfFormFilter ();

	echo '<form id="ADpsCustomerID_form" action="'.url_for('@homepage').'" method="get" class="header-search pull-right">';

	if (myUser::isAdministrator ()) {

		$is_activated = null;

		$ADpsCustomer_form_filter->setWidget ( 'ADpsCustomerID', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsCustomer','query' => Doctrine::getTable ( 'PsCustomer' )->setSQLCustomers ( $is_activated /* , myUser::getPscustomerID() */),
				'add_empty' => __ ( '-Select-' ) ), array ('onchange' => 'this.form.submit();' ) ) );

		$ADpsCustomer_form_filter->setDefault ( 'ADpsCustomerID', myUser::getPscustomerID () );
		// echo $ADpsCustomer_form_filter['ADpsCustomerID']->render(array('class' => 'select2'));
	} else {

		$ADpsCustomer_form_filter->setWidget ( 'ADpsCustomerID', new sfWidgetFormInputText () );

		$ADpsCustomer_form_filter->setDefault ( 'ADpsCustomerID', $ps_customer->getSchoolName () );
		echo $ADpsCustomer_form_filter ['ADpsCustomerID']->render ( array ('class' => 'form-control no-background','readonly' => 'readonly' ) );
	}

	echo $ADpsCustomer_form_filter->renderHiddenFields ();

	echo '</form>';
}
?>
