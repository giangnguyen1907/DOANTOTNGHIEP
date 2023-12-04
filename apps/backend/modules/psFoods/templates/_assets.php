<?php if (myUser::credentialPsCustomers ( 'PS_NUTRITION_FOOD_FILTER_SCHOOL' )):?>

<script type="text/javascript">
$(document).ready(function() {
	// Load district for filter by ps_province_id
    $('#ps_foods_filters_ps_province_id').change(function() {    	
		
		$("#ps_foods_filters_ps_district_id").attr('disabled', 'disabled');
		$('#ps_foods_filters_ps_district_id').select2('val','');
		$('#ps_foods_filters_ps_ward_id').select2('val','');
		
		resetOptions('ps_foods_filters_ps_ward_id');
		$('#ps_foods_filters_ps_ward_id').select2('val','');
		
		resetOptions('ps_foods_filters_ps_customer_id');
		$('#ps_foods_filters_ps_customer_id').select2('val','');
				
		$.ajax({
	        url: '<?php echo url_for('@ps_districts_by_province?pid=') ?>' + $(this).val(),
	        type: "POST",
	        data: {'pid': $(this).val()},
	        processResults: function (data, page) {
          		return {
            		results: data.items  
          		};
        	},
	    }).done(function(msg) {
	    	$('#ps_foods_filters_ps_district_id').select2('val','');
			$("#ps_foods_filters_ps_district_id").html(msg);
			$("#ps_foods_filters_ps_district_id").attr('disabled', null);
	    });
    });
	
	$('#ps_foods_filters_ps_district_id').change(function() {
        $("#ps_foods_filters_ps_ward_id").attr('disabled', 'disabled');
        $.ajax({
            url: '<?php echo url_for('@ps_ward_by_district?did=') ?>' + $(this).val(),
            type: "POST",
            data: {'did': $(this).val()},
            processResults: function (data, page) {
                return {
                  results: data.items  
                };
            },
        }).done(function(msg) {
        	$('#ps_foods_filters_ps_ward_id').select2('val','');
            $("#ps_foods_filters_ps_ward_id").html(msg);
			$("#ps_foods_filters_ps_ward_id").attr('disabled', null);			
        });

      });

    $('#ps_foods_filters_ps_ward_id').change(function() {      
        $("#ps_foods_filters_ps_customer_id").attr('disabled', 'disabled');
		$.ajax({
            url: '<?php echo url_for('@ps_customer_by_ps_ward?wid=') ?>' + $(this).val(),
            type: "POST",
            data: {'did': $(this).val()},
            processResults: function (data, page) {
                return {
                  results: data.items  
                };
            },
        }).done(function(msg) {
        	$('#ps_foods_filters_ps_customer_id').select2('val','');
            $("#ps_foods_filters_ps_customer_id").html(msg);
			$("#ps_foods_filters_ps_customer_id").attr('disabled', null);			
        });
    });
	// END: filters		    
});
</script>
<?php endif;?>