<?php use_helper('I18N', 'Date')?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title"><?php echo __('Ps fee receivable student') ?></h4>
</div>

<?php echo form_tag_for($form, '@ps_fee_receivable_student', array('class' => 'form-horizontal', 'id' => 'form_ps_fee_receivable_student', 'data-fv-addons' => 'i18n')) ?>
<div class="modal-body">
	<?php include_partial('psFeeReceivableStudent/form', array('ps_fee_receivable_student' => $ps_fee_receivable_student,'ps_fee_receipt' => $ps_fee_receipt, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper))?>
</div>

<div class="modal-footer">	
		<?php //include_partial('psFeatureBranchTimes/form_actions_custom', array('ps_fee_receivable_student' => $ps_fee_receivable_student, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>
</form>