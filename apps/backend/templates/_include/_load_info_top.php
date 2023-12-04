<?php
/**
 * @project_name
 * 
 * @subpackage interpreter
 *            
 *             @file _load_customers.php
 *             @filecomment filecomment
 * @package _declaration package_declaration
 * @author PC
 * @version 1.0 31-03-2017 - 16:28:32
 */
if ($sf_user->isAuthenticated() && myUser::credentialPsCustomers ('PS_SYSTEM_CUSTOMER_FILTER_SCHOOL')) :
echo $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
echo $scriptVersion = $detect->getScriptVersion();

	/*
	$info_form_filter = new sfFormFilter();
	$info_form_filter->setWidget('ps_school_year', new sfWidgetFormDoctrineChoice(array(
			'model' => 'PsCustomer',
			'query' => Doctrine::getTable('PsSchoolYear')->setSqlPsSchoolYears(),
			'add_empty' => false
	), array('onchange' => 'this.form.submit();')));
	
	$info_form_filter->setDefault ( 'ps_school_year', $ps_school_year_default->id);
	
	$info_form_filter->setWidget ( 'ps_customer_id', new sfWidgetFormDoctrineChoice ( array (
				'model' => 'PsCustomer','query' => Doctrine::getTable ( 'PsCustomer' )->setSQLPsCustomerAccess (1),
				'add_empty' => false ), array ('onchange' => 'this.form.submit();' ) ) );

	$info_form_filter->setDefault ( 'ps_customer_id', myUser::getPscustomerID () );
	
	$info_form_filter->getWidgetSchema()->setNameFormat('curr_filters[%s]');
	*/

$info_form_filter = new PsHeaderFormFilter();	
?>
<form action="<?php echo url_for('@ps_header_filter');?>" class="form-inline header-search pull-right" method="post">
	<?php if ($info_form_filter->hasGlobalErrors()): ?>
    <?php echo $info_form_filter->renderGlobalErrors() ?>
    <?php endif; ?>
    <?php echo $info_form_filter->renderHiddenFields() ?>
	<div class="form-group">
	<?php echo $info_form_filter ['ps_customer_id']->render();?>
	</div>
	<div class="form-group">
	<?php echo $info_form_filter ['ps_school_year_id']->render ();?>
	</div>
</form>
<?php endif;?>
