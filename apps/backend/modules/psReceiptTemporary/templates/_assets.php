<?php include_partial('global/field_custom/_ps_assets') ?>
<script type="text/javascript">
$(document).ready(function() {
    $('#ps_receipt_temporary_filters_school_year_id').change(function () {
    
        resetOptions('ps_receipt_temporary_filters_ps_month');
        $('#ps_receipt_temporary_filters_ps_month').select2('val','');
    
        if($(this).val() <= 0 ) {
            return;
        }
    
        $("#ps_receipt_temporary_filters_ps_month").attr('disabled', 'disabled');
    
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
            $('#ps_receipt_temporary_filters_ps_month').select2('val','');
            $("#ps_receipt_temporary_filters_ps_month").html(msg);
            $("#ps_receipt_temporary_filters_ps_month").attr('disabled', null);
        });
    
    });


 // filter history

         $('#history_filter_ps_customer_id').change(function() {
         
         	resetOptions('history_filter_ps_workplace_id');
         	$('#history_filter_ps_workplace_id').select2('val','');
         	$("#history_filter_ps_workplace_id").attr('disabled', 'disabled');
         	
             if ($(this).val() > 0) {
             
             	$("#history_filter_ps_workplace_id").attr('disabled', 'disabled');
             	$("#history_filter_class_id").attr('disabled', 'disabled');
             	
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
             
                 	$('#history_filter_ps_workplace_id').select2('val','');
             
             		$("#history_filter_ps_workplace_id").html(msg);
             
             		$("#history_filter_ps_workplace_id").attr('disabled', null);
             
                 });
             }		
         });
         

});
</script>