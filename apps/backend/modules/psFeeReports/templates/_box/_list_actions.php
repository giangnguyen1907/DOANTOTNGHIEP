<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_FEE_REPORT_ADD',    1 => 'PS_FEE_REPORT_EDIT',  ),))): ?>
	<?php echo link_to('<i class="fa-fw fa fa-plus"></i> '.__('Add new receivable', array(), 'messages'), url_for('@receivable_list_for_fee_reports'), array('class' => 'btn btn-default btn-success bg-color-green btn-psadmin btn-add-receivable-month', 'data-backdrop' => 'static', 'data-toggle' => 'modal', 'data-target' => '#remoteModal'));?>
<?php endif; ?>

<?php if ($sf_user->hasCredential(array(  0 =>   array(    0 => 'PS_FEE_REPORT_ADD',    1 => 'PS_FEE_REPORT_EDIT',  ),))): ?>
<button class="btn btn-default btn-success bg-color-green btn-psadmin"
	form="id-form">
	<span class="fa fa-asterisk"></span> <?php echo __('Process fee report', array(), 'messages');?></button>
<?php endif; ?>