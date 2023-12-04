<?php use_helper('I18N', 'Number') ?>
<script type="text/javascript">
$(document).ready(function() {
<?php if (myUser::credentialPsCustomers('PS_FEE_CONFIG_LATE_FEES_FILTER_SCHOOL')):?>
$('#ps_config_late_fees_filters_ps_customer_id').change(function() {
	resetOptions('ps_config_late_fees_filters_ps_workplace_id');
	$('#ps_config_late_fees_filters_ps_workplace_id').select2('val','');
	$("#ps_config_late_fees_filters_ps_workplace_id").attr('disabled', 'disabled');

	$.ajax({
    	url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' +$(this).val(),
    	type: 'POST',
    	data: 'psc_id='+$(this).val(),
    	success: function(data){
    		$('#ps_config_late_fees_filters_ps_workplace_id').html(data);
    		$("#ps_config_late_fees_filters_ps_workplace_id").attr('disabled', null);
    	}
    });
});

$('#ps_config_late_fees_ps_customer_id').change(function() {
	resetOptions('ps_config_late_fees_ps_workplace_id');
	$('#ps_config_late_fees_ps_workplace_id').select2('val','');
	$("#ps_config_late_fees_ps_workplace_id").attr('disabled', 'disabled');

	$.ajax({
    	url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' +$(this).val(),
    	type: 'POST',
    	data: 'psc_id='+$(this).val(),
    	success: function(data){
    		$('#ps_config_late_fees_ps_workplace_id').html(data);
    		$("#ps_config_late_fees_ps_workplace_id").attr('disabled', null);
    	}
    });
});
<?php endif;?>
});
</script>