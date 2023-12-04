<?php use_helper('I18N', 'Date')?>
<script type="text/javascript">
$(document).ready(function() {

	$('#remoteModal').on('hide.bs.modal', function(e) {
		$(this).removeData('bs.modal');
	});
	
	$('#psactivitie_detail_at').datepicker({
		dateFormat : 'dd-mm-yy',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	})
	$('#psactivitie_detail_end').datepicker({
		dateFormat : 'dd-mm-yy',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		changeMonth : true,
		changeYear : true,
	})
	.on('change', function(e) {
		$('#form_receivable_detail').formValidation('revalidateField', $(this).attr('name'));
	});
	
});
</script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title"><?php echo __('receivable detail') ?></h4>
</div>

<?php echo form_tag_for($form, '@ps_receivable_detail', array('class' => 'form-horizontal', 'id' => 'form_ps_receivable_detail', 'data-fv-addons' => 'i18n')) ?>
<div class="modal-body">
	<?php include_partial('psReceivableDetail/form', array('ps_receivable_detail' => $receivable_detail,'form' => $form, 'configuration' => $configuration, 'helper' => $helper))?>
</div>

<div class="modal-footer">	
		<?php //include_partial('psFeatureBranchTimes/form_actions_custom', array('ps_fee_receivable_student' => $ps_fee_receivable_student, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>
</form>