<?php use_helper('I18N', 'Number') ?>
<?php include_partial('global/include/_box_modal_messages');?>
<style>
.datepicker {
	z-index: 1051 !important;
}

.ui-datepicker {
	z-index: 1051 !important;
}

.list-inline {
	margin-left: 0;
}
</style>

<script type="text/javascript">
$(document).ready(function() {

	$('#receivable_filters_ps_customer_id').change(function() {
	
		if ($(this).val() <= 0) {
			return;
		}
		
		resetOptions('receivable_filters_ps_workplace_id');
		$('#receivable_filters_ps_workplace_id').select2('val','');
	
		if ($(this).val() > 0) {
	
			$("#receivable_filters_ps_workplace_id").attr('disabled', 'disabled');
						
			$.ajax({
				url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
		        type: "POST",
		        data: {'psc_id': $(this).val()},
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
	
		    	$('#receivable_filters_ps_workplace_id').select2('val','');
	
				$("#receivable_filters_ps_workplace_id").html(msg);
	
				$("#receivable_filters_ps_workplace_id").attr('disabled', null);
		    });	
		}		
	});

	$('#receivable_ps_customer_id').change(function() {
		
		if ($(this).val() <= 0) {
			return;
		}
		
		resetOptions('receivable_ps_workplace_id');
		$('#receivable_ps_workplace_id').select2('val','');
	
		if ($(this).val() > 0) {
	
			$("#receivable_ps_workplace_id").attr('disabled', 'disabled');
						
			$.ajax({
				url: '<?php echo url_for('@ps_work_places_by_customer?psc_id=') ?>' + $(this).val(),
		        type: "POST",
		        data: {'psc_id': $(this).val()},
		        processResults: function (data, page) {
	          		return {
	            		results: data.items
	          		};
	        	},
		    }).done(function(msg) {
	
		    	$('#receivable_ps_workplace_id').select2('val','');
	
				$("#receivable_ps_workplace_id").html(msg);
	
				$("#receivable_ps_workplace_id").attr('disabled', null);
		    });	
		}		
	});

});
</script>
<?php include_partial('global/include/_box_modal')?>