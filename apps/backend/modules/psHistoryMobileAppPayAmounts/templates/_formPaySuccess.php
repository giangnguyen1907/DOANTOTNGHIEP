<?php use_helper('I18N', 'Date')?>
<?php include_partial('psHistoryMobileAppPayAmounts/assets')?>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">×</button>
	<h4 class="modal-title"><?php echo __('Pay for: %%user%%', array('%%user%%' => Doctrine::getTable('sfGuardUser')->findOneBy('id', $ps_history_mobile_app_pay_amounts->getUserId()))) ?></h4>
</div>

<?php echo form_tag_for($form, '@ps_history_mobile_app_pay_amounts', array('class' => 'form-horizontal', 'id' => 'ps-form', 'data-fv-addons' => 'i18n')) ?>
<div class="modal-body" style="overflow: hidden;">
	<?php include_partial('psHistoryMobileAppPayAmounts/form_pay', array('ps_history_mobile_app_pay_amounts' => $ps_history_mobile_app_pay_amounts, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper))?>
</div>

<div class="modal-footer">	
	<?php include_partial('psHistoryMobileAppPayAmounts/form_actions', array('ps_history_mobile_app_pay_amounts' => $ps_history_mobile_app_pay_amounts, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>

</form>
<style type="text/css">
#ui-datepicker-div {
	z-index: 1151 !important;
}
</style>

<script type="text/javascript">
	$(document).ready(function(){
		$(document).on('hide.bs.modal', '#remoteModal' , function(e){
		    var contentArea = $(this).find('#remoteModal');
		    contentArea.html('');
		});

		$('#ps_history_mobile_app_pay_amounts_user_id').select2({
			  dropdownParent: $('#remoteModal'),
			  dropdownCssClass : 'no-search'
		});	

		$('#ps_history_mobile_app_pay_amounts_month').select2({
			dropdownParent: $('#remoteModal'),
			dropdownCssClass: 'no-search'
		});

		$('#ps_history_mobile_app_pay_amounts_pay_created_at').datepicker({			
			prevText : '<i class="fa fa-chevron-left"></i>',
		    nextText : '<i class="fa fa-chevron-right"></i>',
			changeMonth: true,
	        changeYear: true,
	        dateFormat: 'dd-mm-yy'
		}).on('changeDate', function(e) {
		     $('#ps_history_mobile_app_pay_amounts_pay_created_at').formValidation('revalidateField', '	ps_history_mobile_app_pay_amounts[pay_created_at]');
		});	
	});

	$("#ps_history_mobile_app_pay_amounts_amount").on('change',function(){
		$.ajax({
			url: '<?php echo url_for('@ps_history_app_pay_amounts_get_expiration') ?>',
			type: 'post',
			data: 'pay_created_at='+$('#ps_history_mobile_app_pay_amounts_pay_created_at').val()+'&amount='+$('#ps_history_mobile_app_pay_amounts_amount').val()+'&user_id='+$('#ps_history_mobile_app_pay_amounts_user_id').val(),
			success: function(data){
				$('#ps_history_mobile_app_pay_amounts_expiration_date').val(data);
			}
		});
	});

	$("#ps_history_mobile_app_pay_amounts_pay_created_at").on('change',function(){
		$.ajax({
			url: '<?php echo url_for('@ps_history_app_pay_amounts_get_expiration') ?>',
			type: 'post',
			data: 'pay_created_at='+$('#ps_history_mobile_app_pay_amounts_pay_created_at').val()+'&amount='+$('#ps_history_mobile_app_pay_amounts_amount').val()+'&user_id='+$('#ps_history_mobile_app_pay_amounts_user_id').val(),
			success: function(data){
				$('#ps_history_mobile_app_pay_amounts_expiration_date').val(data);
			}
		});
	});

	$('#ps_history_mobile_app_pay_amounts_month').on('change', function(){
		$.ajax({
			url: '<?php echo url_for('@ps_history_app_pay_amount_get_amount') ?>',
			type: 'post',
			data: 'month='+$('#ps_history_mobile_app_pay_amounts_month').val(),
			success: function(data){
				$('#ps_history_mobile_app_pay_amounts_amount').val(data);
				$('#ps_history_mobile_app_pay_amounts_amount').trigger('change');
			}
		});
	});

	if($('#ps_history_mobile_app_pay_amounts_amount').val() == 0){
		$('#ps_history_mobile_app_pay_amounts_month').trigger('change');
	}
	else {
		$.ajax({
			url: '<?php echo url_for('@ps_history_app_pay_amount_get_month') ?>',
			type: 'post',
			data: 'amount='+$('#ps_history_mobile_app_pay_amounts_amount').val(),
			success: function(data){
				$('#ps_history_mobile_app_pay_amounts_month').val(data);
				$('#ps_history_mobile_app_pay_amounts_month').trigger('change');
			}
		})
	}



</script>