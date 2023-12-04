<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_FEE_REPORT_ADD',    1 => 'PS_FEE_REPORT_EDIT',  ),))): ?>
	<?php echo link_to('<i class="fa-fw fa fa-plus"></i> '.__('Add new receivable', array(), 'messages'), url_for('@receivable_list_for_fee_reports').'?ps_date='.strtotime($filters['receivable_at']->getValue()).'&ps_class_id='.$filters['ps_class_id']->getValue(), array('class' => 'btn btn-default btn-success bg-color-green btn-psadmin btn-add-receivable-month', 'data-backdrop' => 'static', 'data-toggle' => 'modal', 'data-target' => '#remoteModal'));?>
<?php endif;?>

<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_FEE_REPORT_ADD',    1 => 'PS_FEE_REPORT_EDIT',  ),))): ?>
<button class="btn btn-default btn-success bg-color-green btn-psadmin"
	href="/web/backend_dev.php/ps_fee_reports/add_new_receivable/action"><?php echo __('Process fee report', array(), 'messages');?></button>
<?php endif;?>