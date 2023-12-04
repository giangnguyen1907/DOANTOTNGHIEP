<?php use_helper('I18N', 'Number')?>
<style>
.datepicker {
	z-index: 1051 !important;
}

.ui-datepicker {
	z-index: 1051 !important;
}

.select2-container {
	width: 100% !important;
	padding: 0;
}
</style>
<?php include_partial('global/include/_box_modal')?>
<?php include_partial('psMobileAppAmounts/delete_confirm') ?>
<script type="text/javascript">
	$(document).on("ready", function(){
		$(".widget-body-toolbar a, .btn-group a, .sf_admin_list_td_title a, a[data-target=#remoteModal]").on("contextmenu",function(){
			return false;
		});	
		$('body').on('hidden.bs.modal', '.modal', function () {
			$(this).removeData('bs.modal');
		});
		$('#confirmDelete').on('hide.bs.modal', function(e) {
			$(this).removeData('bs.modal');
		});

		$('#ps_mobile_app_amounts_filters_ps_customer_id').change(function() {
			$("#ps_mobile_app_amounts_filters_ps_workplace_id").attr('disabled', 'disabled');
			
			$.ajax({
				url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
				type: 'POST',
				data: 'f=<?php echo md5(time().time().time().time())?>&psc_id=' + $(this).val(),
				success: function(data) {
					$('#ps_mobile_app_amounts_filters_ps_workplace_id').select2('val','');
					$('#ps_mobile_app_amounts_filters_ps_workplace_id').html(data);
					$("#ps_mobile_app_amounts_filters_ps_workplace_id").attr('disabled', null);
					
				}
			});
		});

	$("#ps_history_mobile_app_pay_amounts_amount").on('change',function(){
		$.ajax({
			url: '<?php echo url_for('@ps_history_app_pay_amounts_get_expiration') ?>',
			type: 'post',
			data: 'f=<?php echo md5(time().time().time().time())?>pay_created_at='+$('#ps_history_mobile_app_pay_amounts_pay_created_at').val()+'&amount='+$('#ps_history_mobile_app_pay_amounts_amount').val(),
			success: function(data){
				$('#ps_history_mobile_app_pay_amounts_expiration_date').val(data);
			}
		});
	});

	$("#ps_history_mobile_app_pay_amounts_pay_created_at").on('change',function(){
		$.ajax({
			url: '<?php echo url_for('@ps_history_app_pay_amounts_get_expiration') ?>',
			type: 'post',
			data: 'f=<?php echo md5(time().time().time().time())?>pay_created_at='+$('#ps_history_mobile_app_pay_amounts_pay_created_at').val()+'&amount='+$('#ps_history_mobile_app_pay_amounts_amount').val(),
			success: function(data){
				$('#ps_history_mobile_app_pay_amounts_expiration_date').val(data);
			}
		});
	});
		$('.delete').click(function(){
			$('#deleteHistory').attr('action','<?php echo url_for('@ps_history_mobile_app_pay_amounts_delete?id=') ?>'+$(this).data('id'));
		});
	});
</script>