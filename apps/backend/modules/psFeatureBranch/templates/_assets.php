<?php use_helper('I18N', 'Number') ?>
<style>
.datepicker, .bootstrap-timepicker-widget, .ui-datepicker-calendar {
	z-index: 9999 !important;
}

.ui-datepicker {
	z-index: 1051 !important;
}

.select2-container {
	width: 100% !important;
	padding: 0;
}
</style>
<script>
	// msg
	var msg_invalid_month_year 	= '<?php echo __('Value is invalid')?>';
	var start_time_end_time_invalid 	= '<?php echo __('The start time must be earlier then the end time')?>';
	var end_time_start_time_invalid 	= '<?php echo __('The end time must be later then the start time')?>';

	var msg_start_date_invalid 	= '<?php echo __('The start date is not a valid')?>';
	var msg_end_date_invalid 	= '<?php echo __('The end date is not a valid')?>';
	
	var monthNameTypeNumber = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
	
</script>
<script type="text/javascript">

$(document).ready(function() {

	//Load ajax
	$('.item-activated').click(function() {
		var id =  $(this).attr('item');
		$.ajax({
	        url: '<?php echo url_for('@ps_feature_branch_activated') ?>',
	        type: 'POST',
	        data: 'id=' + id,
	        success: function(data) {
	        	$('#item-activated-' + id).html(data);
	        	return;
	        }
		});
	});

	$(".widget-body-toolbar a, .btn-group a, .btn-filter-reset").on("contextmenu",function(){
	    return false;
	});
		
	<?php if (myUser::credentialPsCustomers('PS_SYSTEM_FEATURE_BRANCH_FILTER_SCHOOL')):?>

	$('#feature_branch_filters_ps_customer_id').change(function() {      

		if ($(this).val() <= 0) {
			return;
		}

		resetOptions('feature_branch_filters_ps_workplace_id');
		$('#feature_branch_filters_ps_workplace_id').select2('val','');
		
		$("#feature_branch_filters_ps_workplace_id").attr('disabled', 'disabled');

		resetOptions('feature_branch_filters_feature_id');
		$('#feature_branch_filters_feature_id').select2('val','');
		$("#feature_branch_filters_feature_id").attr('disabled', 'disabled');
		
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

	    	$('#feature_branch_filters_ps_workplace_id').select2('val','');

			$("#feature_branch_filters_ps_workplace_id").html(msg);

			$("#feature_branch_filters_ps_workplace_id").attr('disabled', null);
	    });

		$.ajax({
            url: '<?php echo url_for('@ps_feature_by_customer?cid=') ?>' + $(this).val(),
            type: "POST",
            data: {'cid': $(this).val()},
            processResults: function (data, page) {
                return {
                  results: data.items  
                };
            },
        }).done(function(msg) {
        	$('#feature_branch_filters_feature_id').select2('val','');
            $("#feature_branch_filters_feature_id").html(msg);
            $("#feature_branch_filters_feature_id").attr('disabled', null);
        });
                
    });
	
	<?php endif;?>
	// END: filters
	
	
	// Load feature by customer
    $('#feature_branch_ps_customer_id').change(function() {      

    	resetOptions('feature_branch_ps_workplace_id');
		$('#feature_branch_ps_workplace_id').select2('val','');

		$("#feature_branch_ps_workplace_id").attr('disabled', 'disabled');
		
    	$("#feature_branch_feature_id").attr('disabled', 'disabled');

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

	    	$('#feature_branch_ps_workplace_id').select2('val','');
	    	$("#feature_branch_ps_workplace_id").html(msg);
	    	$("#feature_branch_ps_workplace_id").attr('disabled', null);
	    });

    	$.ajax({
          url: '<?php echo url_for('@ps_feature_by_customer?cid=') ?>' + $(this).val(),
          type: "POST",
          data: {},
          processResults: function (data, page) {
              return {
                results: data.items  
              };
          },
	      }).done(function(msg) {
	    	  $('#feature_branch_feature_id').select2('val','');
	    	  $("#feature_branch_feature_id").html(msg);
	    	  $("#feature_branch_feature_id").attr('disabled', null);		 
	      });
    });

    $('.btn-delete-item ').click(function() {
		var item_id = $(this).attr('data-item');		
		$('#ps-form-delete').attr('action', '<?php echo url_for('@ps_feature_branch_times')?>/' + item_id);
	});
});
</script>
<?php include_partial('global/include/_box_modal')?>
<?php include_partial('psFeatureBranch/box_modal_confirm_remover_times');?>