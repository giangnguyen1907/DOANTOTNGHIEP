<?php use_helper('I18N', 'Number')?>
<?php include_partial('global/include/_box_modal')?>
<script type="text/javascript">
$(document).on("ready", function(){
	$(".widget-body-toolbar a, .btn-group a, a[data-target=#remoteModal]").on("contextmenu",function(){
	       return false;
	});	

	$('#ps_history_mobile_app_pay_amounts_ps_customer_id').change(function() {
		$("#ps_history_mobile_app_pay_amounts_user_id").attr('disabled', 'disabled');
		
		$.ajax({
	        url: '<?php echo url_for('@ps_relatives_by_customer?cid=') ?>' + $(this).val(),
	        type: 'POST',
	        data: 'f=<?php echo md5(time().time().time().time())?>&cid=' + $(this).val(),
	        success: function(data) {
	        	$('#ps_history_mobile_app_pay_amounts_user_id').select2('val','');
				$('#ps_history_mobile_app_pay_amounts_user_id').html(data);
				$("#ps_history_mobile_app_pay_amounts_user_id").attr('disabled', null);
				
	        }
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

});
</script>