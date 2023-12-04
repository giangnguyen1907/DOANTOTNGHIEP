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
//if ($sf_user->isAuthenticated() && myUser::isAdministrator()) :
if ($sf_user->isAuthenticated() && myUser::credentialPsCustomers ('PS_SYSTEM_CUSTOMER_FILTER_SCHOOL')) :
	$info_form_filter = new PsHeaderFormFilter();
	
	//echo $module = sfContext::getInstance()->getRouting(); // Module dang load
	//echo $action = sfContext::getInstance()->getActionName();
?>
<form action="<?php echo url_for('@ps_header_filter');?>" class="form-inline" method="post">
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
	<div class="form-group">
	<a rel="tooltip" href="<?php echo url_for('@ps_header_filter_reset');?>" data-placement="auto" data-original-title="<?php echo __('Reset');?>" class="btn btn-sm btn-default btn-filter-reset btn-psadmin"><i class="fa-fw fa fa-refresh"></i></a></label>
	</div>
</form>
<?php endif;?>
