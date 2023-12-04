<?php include_partial('global/field_custom/_ps_assets') ?>
<script type="text/javascript">

$(document).ready(function() {

	//Filter
	$('#ps_evaluate_index_criteria_filters_school_year_id').change(function() {      

		resetOptions('ps_evaluate_index_criteria_filters_evaluate_subject_id');
		$('#ps_evaluate_index_criteria_filters_evaluate_subject_id').select2('val','');
		
		if ($(this).val() <= 0) {
			return;
		}

		$("#ps_evaluate_index_criteria_filters_evaluate_subject_id").attr('disabled', 'disabled');
		
		$.ajax({
            url: '<?php echo url_for('@ps_evaluate_subject_by_params') ?>',
            type: "POST",
            data: 'c_id=' + $('#ps_evaluate_index_criteria_filters_ps_customer_id').val() + '&w_id=' + $('#ps_evaluate_index_criteria_filters_ps_workplace_id').val() + '&y_id=' + $('#ps_evaluate_index_criteria_filters_school_year_id').val() + '&state=' + <?php echo PreSchool::ACTIVE?>,
            processResults: function (data, page) {
                return {
                  results: data.items  
                };
            },
        }).done(function(msg) {
//             alert(msg);
        	$('#ps_evaluate_index_criteria_filters_evaluate_subject_id').select2('val','');
            $("#ps_evaluate_index_criteria_filters_evaluate_subject_id").html(msg);
            $("#ps_evaluate_index_criteria_filters_evaluate_subject_id").attr('disabled', null);
        });
			
    });
    
	$('#ps_evaluate_index_criteria_filters_ps_customer_id').change(function() {      
		
		resetOptions('ps_evaluate_index_criteria_filters_ps_workplace_id');
		$('#ps_evaluate_index_criteria_filters_ps_workplace_id').select2('val','');

		resetOptions('ps_evaluate_index_criteria_filters_evaluate_subject_id');
		$('#ps_evaluate_index_criteria_filters_evaluate_subject_id').select2('val','');
		
		if ($(this).val() <= 0) {
			return;
		}

		$("#ps_evaluate_index_criteria_filters_ps_workplace_id").attr('disabled', 'disabled');

		$("#ps_evaluate_index_criteria_filters_evaluate_subject_id").attr('disabled', 'disabled');
				
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

	    	$('#ps_evaluate_index_criteria_filters_ps_workplace_id').select2('val','');

			$("#ps_evaluate_index_criteria_filters_ps_workplace_id").html(msg);

			$("#ps_evaluate_index_criteria_filters_ps_workplace_id").attr('disabled', null);

			$("#ps_evaluate_index_criteria_filters_ps_workplace_id").trigger('change');
			
	    });

		
           
    });

	$('#ps_evaluate_index_criteria_filters_ps_workplace_id').change(function() {      

		resetOptions('ps_evaluate_index_criteria_filters_evaluate_subject_id');
		$('#ps_evaluate_index_criteria_filters_evaluate_subject_id').select2('val','');

		$("#ps_evaluate_index_criteria_filters_evaluate_subject_id").attr('disabled', 'disabled');
		
		$.ajax({
            url: '<?php echo url_for('@ps_evaluate_subject_by_params') ?>',
            type: "POST",
            data: 'c_id=' + $('#ps_evaluate_index_criteria_filters_ps_customer_id').val() + '&w_id=' + $(this).val() + '&y_id=' + $('#ps_evaluate_index_criteria_filters_school_year_id').val() + '&state=' + <?php echo PreSchool::ACTIVE?>,
            processResults: function (data, page) {
                return {
                  results: data.items  
                };
            },
        }).done(function(msg) {
//             alert(msg);
        	$('#ps_evaluate_index_criteria_filters_evaluate_subject_id').select2('val','');
            $("#ps_evaluate_index_criteria_filters_evaluate_subject_id").html(msg);
            $("#ps_evaluate_index_criteria_filters_evaluate_subject_id").attr('disabled', null);
        });
                
    });

    //End filter

    //Form 
    $('#ps_evaluate_index_criteria_ps_customer_id').change(function() {      
		
		resetOptions('ps_evaluate_index_criteria_ps_workplace_id');
		$('#ps_evaluate_index_criteria_ps_workplace_id').select2('val','');
		
// 		if ($(this).val() <= 0) {
// 			return;
// 		}

		$("#ps_evaluate_index_criteria_ps_workplace_id").attr('disabled', 'disabled');


		$("#ps_evaluate_index_criteria_evaluate_subject_id").attr('disabled', 'disabled');
		
		$.ajax({
            url: '<?php echo url_for('@ps_evaluate_subject_by_params') ?>',
            type: "POST",
            data: 'c_id=' + $('#ps_evaluate_index_criteria_ps_customer_id').val() + '&w_id=' + $("#ps_evaluate_index_criteria_ps_workplace_id").val() ,
            processResults: function (data, page) {
                return {
                  results: data.items  
                };
            },
        }).done(function(msg) {
        	$('#ps_evaluate_index_criteria_evaluate_subject_id').select2('val','');
            $("#ps_evaluate_index_criteria_evaluate_subject_id").html(msg);
            $("#ps_evaluate_index_criteria_evaluate_subject_id").attr('disabled', null);
        });
               
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

	    	$('#ps_evaluate_index_criteria_ps_workplace_id').select2('val','');

			$("#ps_evaluate_index_criteria_ps_workplace_id").html(msg);

			$("#ps_evaluate_index_criteria_ps_workplace_id").attr('disabled', null);

			$("#ps_evaluate_index_criteria_ps_workplace_id").trigger('change');
			
	    });
           
    });

	$('#ps_evaluate_index_criteria_ps_workplace_id').change(function() {      

		if ($(this).val() <= 0) {
			return;
		}
		
		resetOptions('ps_evaluate_index_criteria_evaluate_subject_id');
		$('#ps_evaluate_index_criteria_evaluate_subject_id').select2('val','');
		
		$("#ps_evaluate_index_criteria_evaluate_subject_id").attr('disabled', 'disabled');
		
		$.ajax({
            url: '<?php echo url_for('@ps_evaluate_subject_by_params') ?>',
            type: "POST",
            data: 'c_id=' + $('#ps_evaluate_index_criteria_ps_customer_id').val() + '&w_id=' + $("#ps_evaluate_index_criteria_ps_workplace_id").val()  + '&state=' + <?php echo PreSchool::ACTIVE?>,
            processResults: function (data, page) {
                return {
                  results: data.items  
                };
            },
        }).done(function(msg) {
        	$('#ps_evaluate_index_criteria_evaluate_subject_id').select2('val','');
            $("#ps_evaluate_index_criteria_evaluate_subject_id").html(msg);
            $("#ps_evaluate_index_criteria_evaluate_subject_id").attr('disabled', null);
        });
                
    });
    //End-form

});
</script>
<?php include_partial('global/include/_box_modal')?>