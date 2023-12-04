<?php use_helper('I18N', 'Number') ?>
<style>
#ui-datepicker-div {
	z-index: 9999 !important
}
</style>
<script type="text/javascript">
function getDaysOfMonth(year, month) { 

	return new Date(year, month + 1, 0).getDate(); 

} 
$(document).ready(function() {
<?php if (myUser::credentialPsCustomers('PS_FEE_CONFIG_LATE_PAYMENT_FILTER_SCHOOL')):?>

$('#ps_config_late_payment_filters_ps_customer_id').change(function() {
	resetOptions('ps_config_late_payment_filters_ps_workplace_id');
	$('#ps_config_late_payment_filters_ps_workplace_id').select2('val','');
	$("#ps_config_late_payment_filters_ps_workplace_id").attr('disabled', 'disabled');

	$.ajax({
    	url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' +$(this).val(),
    	type: 'POST',
    	data: 'psc_id='+$(this).val(),
    	success: function(data){
    		$('#ps_config_late_payment_filters_ps_workplace_id').html(data);
    		$("#ps_config_late_payment_filters_ps_workplace_id").attr('disabled', null);
    	}
    });
});

$('#ps_config_late_payment_ps_customer_id').change(function() {
	resetOptions('ps_config_late_payment_ps_workplace_id');
	$('#ps_config_late_payment_ps_workplace_id').select2('val','');
	$("#ps_config_late_payment_ps_workplace_id").attr('disabled', 'disabled');

	$.ajax({
    	url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' +$(this).val(),
    	type: 'POST',
    	data: 'psc_id='+$(this).val(),
    	success: function(data){
    		$('#ps_config_late_payment_ps_workplace_id').html(data);
    		$("#ps_config_late_payment_ps_workplace_id").attr('disabled', null);
    	}
    });
});

$('#ps_config_late_payment_school_year_id').change(function() {
	
	resetOptions('ps_config_late_payment_from_date');
	$('#ps_config_late_payment_from_date').val("");

	resetOptions('ps_config_late_payment_to_date');
	$('#ps_config_late_payment_to_date').val("");
	
});

$('#ps_config_late_payment_school_year_id').change(function() {

	resetOptions('ps_config_late_payment_ps_month');
	$('#ps_config_late_payment_ps_month').select2('val','');
	
	if ($(this).val() > 0) {
			
	$("#ps_config_late_payment_ps_month").attr('disabled', 'disabled');

	$.ajax({
		url: '<?php echo url_for('@ps_year_month?ym_id=') ?>' + $(this).val(),
        type: "POST",
        data: {'ym_id': $(this).val()},
        processResults: function (data, page) {
      		return {
        		results: data.items
      		};
    	},
	    }).done(function(msg) {
	    	$('#ps_config_late_payment_ps_month').select2('val','');
			$("#ps_config_late_payment_ps_month").html(msg);
			$("#ps_config_late_payment_ps_month").attr('disabled', null);
	    });
	}
});

<?php endif;?>

</script>